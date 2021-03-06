<?php

namespace FilippoFinke\Middlewares;

use FilippoFinke\Libs\Session;

/**
 * AdministrationRequired.php
 * Classe utilizzata per controllare se un utente
 * ha l'accesso a determinati percorsi come amministrazione.
 *
 * @author Filippo Finke
 */
class AdministrationRequired
{
    /**
     * Controlla se l'utente fa parte dell'amministrazione.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     */
    public function __invoke($request, $response)
    {
        if (!Session::isAdministration()) {
            $response->redirect(BASE_URL . '/');
            exit;
        }
    }
}
