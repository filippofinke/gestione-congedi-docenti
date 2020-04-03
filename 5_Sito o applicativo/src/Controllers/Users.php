<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Validators;
use FilippoFinke\Models\Administrators;
use FilippoFinke\Models\LdapUsers;

/**
 * Users.php
 * Controller che si occupa di gestire tutti i percorsi relativi agli utenti.
 *
 * @author Filippo Finke
 */
class Users
{
    /**
     * Metodo utilizzato per aggiornare un utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function update($request, $response)
    {
        $username = $request->getAttribute('username');
        $permission = $request->getParam('permission');
        if ($permission && LdapUsers::setPermission($username, $permission)) {
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per inserire un utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function insert($request, $response)
    {
        $type = $request->getParam('type');
        if ($type == "administrator") {
            $name = $request->getParam('name');
            $lastName = $request->getParam('lastName');
            $email = $request->getParam('email');
            if (Validators::isValidEmail($email)
            && Validators::isValidName($name)
            && Validators::isValidLastName($lastName)) {
                if (Administrators::insert($name, $lastName, $email)) {
                    return $response->withStatus(201);
                } else {
                    return $response->withStatus(500);
                }
            }
        } elseif ($type == "ldap") {
            $name = $request->getParam('name');
            $lastName = $request->getParam('lastName');
            $username = $request->getParam('username');
            $permission = $request->getParam('permission');
            if (Validators::isValidName($name)
            && Validators::isValidLastName($lastName)
            && Validators::isValidLdapUsername($username)
            && $permission) {
                if (LdapUsers::insert($username, $name, $lastName)
                && LdapUsers::setPermission($username, $permission)) {
                    return $response->withStatus(201);
                } else {
                    return $response->withStatus(500);
                }
            }
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per aggiornare la password di un utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function setPassword($request, $response)
    {
        $password = $request->getParam('password');
        if (Validators::isValidPassword($password) && Administrators::setPassword($_SESSION["username"], $password)) {
            unset($_SESSION["force_reset_password"]);
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per eliminare un utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function delete($request, $response)
    {
        $email = $request->getParam('email');
        $username = $request->getParam('username');
        if ($email && Validators::isValidEmail($email)) {
            if ($_SESSION["username"] == $email) {
                return $response->withStatus(401);
            } elseif (Administrators::delete($email)) {
                return $response->withStatus(200);
            }
        } elseif ($username && LdapUsers::delete($username)) {
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }
}
