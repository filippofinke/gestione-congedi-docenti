<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * AdministratorRequired.php
 * Classe utilizzata per controllare se un utente
 * ha l'accesso a determinati percorsi come amministratore.
 *
 * @author Filippo Finke
 */
class AdministratorRequired
{
    /**
     * Controlla se l'utente è amministratore.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isAdministrator()) {
            $response->redirect(BASE_URL . '/');
            exit;
        }
    }
}
