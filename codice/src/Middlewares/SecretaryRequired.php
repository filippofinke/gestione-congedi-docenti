<?php

namespace FilippoFinke\Middlewares;
use FilippoFinke\Libs\Session;

class SecretaryRequired {

    public function __invoke($request, $response)
    {
        if(!Session::isSecretary()) {
            $response->redirect('/dashboard');
            exit;
        }
    }

}