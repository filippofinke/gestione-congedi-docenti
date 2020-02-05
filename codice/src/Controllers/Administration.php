<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Administrators;
use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Permissions;
use FilippoFinke\Models\Reasons;

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

    public static function reasons($request, $response)
    {
        $reasons = Reasons::getAll();
        return $response->render(__DIR__ . '/../Views/Administration/reasons.php', array(
            'reasons' => $reasons
        ));
    }
}
