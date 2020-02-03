<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Administrators;
use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Permissions;

class Administration
{
    public static function index($request, $response)
    {
        if (isset($_SESSION["force_reset_password"])) {
            return $response->render(__DIR__ . '/../Views/Administration/change-password.php');
        } else {
            $ldapUsers = LdapUsers::getAll();
            $administrators = Administrators::getAll();
            $permissions = Permissions::getAll();

            return $response->render(__DIR__ . '/../Views/Administration/users.php', array(
                "ldapUsers" => $ldapUsers,
                "administrators" => $administrators,
                "permissions" => $permissions
            ));
        }
    }

    public static function motivations($request, $response)
    {
        return $response->render(__DIR__ . '/../Views/Administration/motivations.php');
    }
}
