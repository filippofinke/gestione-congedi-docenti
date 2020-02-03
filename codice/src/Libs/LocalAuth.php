<?php

namespace FilippoFinke\Libs;
use FilippoFinke\Models\Administrator;
use FilippoFinke\Models\Administrators;

class LocalAuth
{
    public static function login($email, $password, $bypass = false)
    {
        $user = Administrators::getByEmail($email);
        if ($user) {
            if (password_verify($password, $user["password"]) || $bypass) {
                if($user["last_login"] == null) {
                    $_SESSION["force_reset_password"] = true;
                }
                return new Administrator($email, $user["name"], $user["last_name"]);
            }
        }
        return false;
    }
}
