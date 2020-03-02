<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Validators;
use FilippoFinke\Models\Container;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Requests as ModelsRequests;
use FilippoFinke\Models\Substitutes;
use FilippoFinke\Utils\Database;
use FilippoFinke\Libs\Session;

/**
 * Requests.php
 * Controller che si occupa di gestire tutti i percorsi relativi ai congedi.
 *
 * @author Filippo Finke
 */
class Requests
{
    /**
     * Metodo utilizzato per inserire un congedo.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function insert($request, $response)
    {
        $username = $_SESSION["username"];
        $week = $request->getParam('week');
        $reasons = $request->getParam('reasons');
        $reasons = explode(",", $reasons);
        $substitutes = json_decode($request->getParam("substitutes"), true);
        foreach ($substitutes as $index => $substitute) {
            foreach ($substitute as $key => $value) {
                $substitutes[$index][$key] = htmlspecialchars($value);
            }
        }
        if (($week == "A" || $week == "B") && is_array($reasons) && is_array($substitutes)) {
            Database::getConnection()->beginTransaction();
            $id = \FilippoFinke\Models\Requests::insert($username, $week);
            if ($id) {
                foreach ($reasons as $rId) {
                    if (!Reasons::connect($rId, $id)) {
                        Database::getConnection()->rollBack();
                        return $response->withStatus(400);
                    }
                }
                foreach ($substitutes as $substitute) {
                    if ((strlen($substitute["substitute"] ?? "") > 0 && !Validators::isValidName($substitute["substitute"] ?? ""))
                    || !Validators::isValidDescription($substitute["room"] ?? "", 0, 5)
                    || !Validators::isValidDescription($substitute["class"] ?? "", 0, 15)
                    || !Substitutes::insert(
                        $id,
                        $substitute["from_date"],
                        $substitute["to_date"],
                        $substitute["type"] ?? "",
                        $substitute["room"] ?? "",
                        $substitute["substitute"] ?? "",
                        $substitute["class"] ?? ""
                    )) {
                        Database::getConnection()->rollBack();
                        return $response->withStatus(400);
                    }
                }
                Database::getConnection()->commit();
                return $response->withStatus(201);
            }
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per aggiornare un congedo.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function update($request, $response)
    {
        $id = $request->getAttribute('id');
        $approve = $request->getParam('approve');
        if ($approve) {
            if (ModelsRequests::setContainer($id, Container::ADMINISTRATION)) {
                return $response->withStatus(200);
            } else {
                return $response->withStatus(400);
            }
        } else {
            $week = $request->getParam('week');
            $reasons = $request->getParam('reasons');
            $status = $request->getParam('status');
            $observations = $request->getParam('observations');
            $reasons = explode(",", $reasons);
            $substitutes = json_decode($request->getParam("substitutes"), true);
            foreach ($substitutes as $index => $substitute) {
                foreach ($substitute as $key => $value) {
                    $substitutes[$index][$key] = htmlspecialchars($value);
                }
            }
            if (($week == "A" || $week == "B") && is_array($reasons) && is_array($substitutes)) {
                Database::getConnection()->beginTransaction();

                if (isset($status)
                && isset($observations)
                && Session::isAdministration()) {
                    if (!Validators::isValidDescription($observations)
                    || !\FilippoFinke\Models\Requests::update($id, null, $status, $observations)) {
                        Database::getConnection()->rollBack();
                        return $response->withStatus(400);
                    }
                }

                if (
                    \FilippoFinke\Models\Requests::update($id, $week)
                &&  \FilippoFinke\Models\Requests::deleteReasons($id)
                &&  \FilippoFinke\Models\Requests::deleteSubstitutes($id)
                ) {
                    foreach ($reasons as $rId) {
                        if (!Reasons::connect($rId, $id)) {
                            Database::getConnection()->rollBack();
                            return $response->withStatus(400);
                        }
                    }
                    foreach ($substitutes as $substitute) {
                        if ((strlen($substitute["substitute"] ?? "") > 0 && !Validators::isValidName($substitute["substitute"] ?? ""))
                        || !Validators::isValidDescription($substitute["room"] ?? "", 0, 5)
                        || !Validators::isValidDescription($substitute["class"] ?? "", 0, 15)
                        || !Substitutes::insert(
                            $id,
                            $substitute["from_date"],
                            $substitute["to_date"],
                            $substitute["type"] ?? "",
                            $substitute["room"] ?? "",
                            $substitute["substitute"] ?? "",
                            $substitute["class"] ?? ""
                        )) {
                            Database::getConnection()->rollBack();
                            return $response->withStatus(400);
                        }
                    }
                    Database::getConnection()->commit();
                    return $response->withStatus(200);
                } else {
                    Database::getConnection()->rollBack();
                    return $response->withStatus(400);
                }
            }
            return $response->withStatus(400);
        }
    }

    public static function pdf($request, $response)
    {
        $id = $request->getAttribute('id');
        return ModelsRequests::generatePdfForId($id);
    }
}
