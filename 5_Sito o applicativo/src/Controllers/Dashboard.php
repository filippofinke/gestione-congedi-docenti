<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Session;
use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Requests;
use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Models\Substitutes;

/**
 * Dashboard.php
 * Controller che si occupa di gestire tutti i percorsi relativi alle
 * pagine mostrate agli utenti LDAP.
 *
 * @author Filippo Finke
 */
class Dashboard
{
    /**
     * Metodo che si occupa di renderizzare la pagina di creazione di
     * un congedo.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function index($request, $response)
    {
        $reasons = Reasons::getAll();
        $id = $request->getAttribute("id");
        // Se è presente un identificativo apri la visualizzazione in modifica.
        if ($id) {
            $request = Requests::getById($id);
            if ($request && $request["status"] == RequestStatus::WAITING) {
                $toCheck = Reasons::getByRequestId($request["id"]);
                $mapped = array_map(function ($e) {
                    return $e["id"];
                }, $toCheck);
                return $response->render(
                    __DIR__ . '/../Views/Dashboard/index.php',
                    array(
                        'reasons' => $reasons,
                        'request' => array(
                            'request' => $request,
                            'user' => LdapUsers::getByUsername($request["username"]),
                            'reasons' => $mapped,
                            'substitutes' => Substitutes::getByRequestId($request["id"])
                        )
                    )
                );
            } else {
                if (Session::isAdministration()) {
                    return $response->redirect(BASE_URL . "/dashboard/administration");
                } else {
                    return $response->redirect(BASE_URL . "/dashboard/secretariat");
                }
            }
        } else {
            return $response->render(
                __DIR__ . '/../Views/Dashboard/index.php',
                array('reasons' => $reasons)
            );
        }
    }

    /**
     * Metodo che si occupa di renderizzare la pagina che mostra i
     * congedi in attesa dell'utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function sent($request, $response)
    {
        return $response->render(
            __DIR__ . '/../Views/Dashboard/sent.php'
        );
    }

    /**
     * Metodo che si occupa di renderizzare il contenitore della segreteria.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function secretariat($request, $response)
    {
        return $response->render(
            __DIR__ . '/../Views/Dashboard/secretariat.php'
        );
    }
    /**
     * Metodo che si occupa di renderizzare il contenitore della direzione.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function administration($request, $response)
    {
        return $response->render(
            __DIR__ . '/../Views/Dashboard/administration.php'
        );
    }

    /**
     * Metodo che si occupa di renderizzare il contenitore dell'istoriato.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function history($request, $response)
    {
        $personal = false;
        if ($request->getUri() == "/dashboard/history") {
            $history = Requests::getPersonalHistory($_SESSION["username"]);
            $personal = true;
        } else {
            $history = Requests::getAll();
        }
        return $response->render(
            __DIR__ . '/../Views/Dashboard/history.php',
            array(
                'history' => $history,
                'personal' => $personal
            )
        );
    }
}
