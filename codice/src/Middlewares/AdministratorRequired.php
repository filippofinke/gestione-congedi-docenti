<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * AdministratorRequired.php
 * Classe utilizzata per controllare se un utente
 * ha l'accesso a determinati percorsi come Amministratore.
 *
 * @author Filippo Finke
 */
class AdministratorRequired
{
    /**
     * Controlla il permesso dell'utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isAdministrator()) {
            $response->redirect('/');
            exit;
        }
    }
}
