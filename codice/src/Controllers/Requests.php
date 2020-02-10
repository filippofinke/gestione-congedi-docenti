<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Substitutes;
use FilippoFinke\Utils\Database;

class Requests
{
    public static function insert($request, $response)
    {
        $username = $_SESSION["username"];
        $week = $request->getParam('week');
        $reasons = $request->getParam('reasons');
        $reasons = explode(",", $reasons);
        $substitutes = json_decode($request->getParam("substitutes"), true);
        foreach($substitutes as $index => $substitute) {
            foreach($substitute as $key => $value) {
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
                    if (!Substitutes::insert(
                        $id,
                        $substitute["from_date"],
                        $substitute["to_date"],
                        $substitute["type"],
                        $substitute["room"],
                        $substitute["substitute"],
                        $substitute["class"]
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
}
