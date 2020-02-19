<?php

namespace FilippoFinke\Libs;

use FilippoFinke\Models\Administrator;
use FilippoFinke\Models\Administrators;

/**
 * LocalAuth.php
 * Classe utilizzata per gestire il login locale.
 *
 * @author Filippo Finke
 */
class LocalAuth
{
    /**
     * Metodo utilizzato per provare ad eseguire il login locale.
     *
     * @param $email L'email da utilizzare.
     * @param $password La password da utilizzare.
     * @param $bypass Se utilizzare oppure no la password.
     * @return bool True se il login è riuscito altrimenti false.
     */
    public static function login($email, $password, $bypass = false)
    {
        $user = Administrators::getByEmail($email);
        if ($user && (password_verify($password, $user["password"]) || $bypass)) {
            // Controllo se l'utente è nuovo e quindi deve impostare la nuova password.
            if ($user["last_login"] == null) {
                $_SESSION["force_reset_password"] = true;
            }
            return new Administrator($email, $user["name"], $user["last_name"]);
        }
        return false;
    }
}
