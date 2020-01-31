<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Ldap;
use FilippoFinke\Libs\Session;
use FilippoFinke\Libs\LocalAuth;

class Auth
{
    public static function index($request, $response)
    {
        $response->withHeader("Content-Type", "text/html");
        return $response->render(__DIR__ . '/../Views/Auth/login.php');
    }

    public static function doLogin($request, $response)
    {
        $username = $request->getParam("username");
        $password = $request->getParam("password");
        if ($username && $password) {
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $user = LocalAuth::login($username, $password);
            } else {
                $user = Ldap::login($username, $password);
            }
            if ($user) {
                $user->updateLastLogin();
                Session::authenticate(array(
                    "username" => $user->getUsername(),
                    "name" => $user->getName(),
                    "lastName" => $user->getLastName(),
                    "permission" => $user->getPermission()
                ));
                return $response->withStatus(200);
            } else {
                return $response->withStatus(401);
            }
        } else {
            return $response->withStatus(400);
        }
    }

    public static function logout($request, $response)
    {
        Session::logout();
        return $response->redirect("/login");
    }
}
