<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

class LdapUserRequired
{
    public function __invoke($request, $response)
    {
        if (!Session::isTeacher()) {
            $response->redirect('/login');
            exit;
        }
    }
}
