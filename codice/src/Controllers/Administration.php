<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Models\Administrators;
use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Models\Permissions;
use FilippoFinke\Models\Reasons;

/**
 * Administration.php
 * Controller che si occupa di gestire tutti i percorsi di amministrazione.
 * 
 * @author Filippo Finke
 */
class Administration
{
    /**
     * Metodo che si occupa di renderizzare la pagina di gestione utenti.
     * 
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function index($request, $response)
    {
        // Controllo se l'utente deve eseguire il reset della password.
        if (isset($_SESSION["force_reset_password"])) {
            // Mostro la schermata di reset password.
            return $response->render(__DIR__ . '/../Views/Administration/change-password.php');
        } else {
            $ldapUsers = LdapUsers::getAll();
            $administrators = Administrators::getAll();
            $permissions = Permissions::getAll();

            // Mostro la schermata di gestione utenti.
            return $response->render(__DIR__ . '/../Views/Administration/users.php', array(
                "ldapUsers" => $ldapUsers,
                "administrators" => $administrators,
                "permissions" => $permissions
            ));
        }
    }

    /**
     * Metodo che si occupa di renderizzare la pagina di gestione motivi.
     * 
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function reasons($request, $response)
    {
        $reasons = Reasons::getAll();
        return $response->render(__DIR__ . '/../Views/Administration/reasons.php', array(
            'reasons' => $reasons
        ));
    }
}
