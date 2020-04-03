<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * SecretaryRequired.php
 * Classe utilizzata per controllare se un utente
 * ha il permesso della segreteria.
 *
 * @author Filippo Finke
 */
class SecretaryRequired
{

    /**
     * Controlla se l'utente fa parte del gruppo della segreteria.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isSecretary()) {
            $response->redirect(BASE_URL . '/dashboard');
            exit;
        }
    }
}
