<?php

namespace Filippofinke\Models;

/**
 * RequestStatus.php
 * Classe utilizzata per rappresentare lo stato di una richiesta.
 *
 * @author Filippo Finke
 */
class RequestStatus
{
    /**
     * Richiesta in attesa.
     */
    public const WAITING = 0;
    
    /**
     * Richiesta accettata.
     */
    public const ACCEPTED = 1;

    /**
     * Richiesta respinta.
     */
    public const REJECTED = 2;

    /**
     * Richiesta constatata.
     */
    public const NOTICED = 3;
}
