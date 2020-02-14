<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * AuthRequired.php
 * Classe utilizzata per controllare se un utente
 * ha l'accesso a determinati percorsi come utente autenticato.
 *
 * @author Filippo Finke
 */
class AuthRequired
{

    /**
     * Controlla se l'utente ha eseguito l'accesso.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isAuthenticated()) {
            $response->redirect('/login');
            exit;
        }
    }
}
