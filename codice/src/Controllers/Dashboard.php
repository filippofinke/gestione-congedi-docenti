<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Requests;

class Dashboard
{
    public static function index($request, $response)
    {
        $reasons = Reasons::getAll();
        return $response->render(
            __DIR__ . '/../Views/Dashboard/index.php',
            array('reasons' => $reasons)
        );
    }

    public static function sent($request, $response) {
        $requests = Requests::getWaitingByUsername($_SESSION["username"]);
        return $response->render(
            __DIR__ . '/../Views/Dashboard/sent.php',
            array("requests" => $requests)
        );
    }
}
