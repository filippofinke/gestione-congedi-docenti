<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Container;
use FilippoFinke\Models\Reasons;
use FilippoFinke\Models\Requests;
use FilippoFinke\Models\RequestStatus;

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
        return $response->render(
            __DIR__ . '/../Views/Dashboard/sent.php'
        );
    }

    public static function secretariat($request, $response) {
        $request = Requests::getByStatusAndContainer(RequestStatus::WAITING, Container::SECRETARY);
        var_dump($request);
    }
}
