<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

class AdministratorRequired
{
    public function __invoke($request, $response)
    {
        if (!Session::isAdministrator()) {
            $response->redirect('/');
            exit;
        }
    }
}
