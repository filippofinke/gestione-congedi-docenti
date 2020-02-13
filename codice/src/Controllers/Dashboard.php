<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Requests;
use FilippoFinke\Models\Substitutes;

class Dashboard
{
    public static function index($request, $response)
    {
        $reasons = Reasons::getAll();
        $id = $request->getAttribute("id");
        if ($id) {
            $request = Requests::getById($id);
            if ($request) {
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
                return $response->redirect("/dashboard/secretariat");
            }
        } else {
            return $response->render(
                __DIR__ . '/../Views/Dashboard/index.php',
                array('reasons' => $reasons)
            );
        }
    }

    public static function sent($request, $response)
    {
        return $response->render(
            __DIR__ . '/../Views/Dashboard/sent.php'
        );
    }

    public static function secretariat($request, $response)
    {
        return $response->render(
            __DIR__ . '/../Views/Dashboard/secretariat.php'
        );
    }
}
