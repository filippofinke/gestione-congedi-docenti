<?php

namespace FilippoFinke\Middlewares;
use FilippoFinke\Libs\Session;

class AuthRequired {

    public function __invoke($request, $response)
    {
        if(!Session::isAuthenticated()) {
            $response->redirect('/login');
            exit;
        }
    }

}