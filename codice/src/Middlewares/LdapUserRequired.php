<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * LdapUserRequired.php
 * Classe utilizzata per controllare se un utente
 * ha l'accesso a determinati percorsi come utente LDAP.
 *
 * @author Filippo Finke
 */
class LdapUserRequired
{
    /**
     * Controlla se l'utente ha eseguito l'accesso con LDAP.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isTeacher()) {
            $response->redirect('/login');
            exit;
        }
    }
}
