<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Reasons;

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
}
