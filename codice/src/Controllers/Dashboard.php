<?php
namespace FilippoFinke\Controllers;

class Dashboard
{
    public static function index($request, $response)
    {
        return $response->render(__DIR__ . '/../Views/Dashboard/index.php');
    }
}
