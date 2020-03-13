<?php

namespace FilippoFinke\Models;

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

    /**
     * Metodo utilizzato per ricavare lo stato del congedo come stringa.
     *
     * @param $status Lo stato da ricavare.
     * @return string La stringa dello stato.
     */
    public static function get($status)
    {
        switch ($status) {
            case self::WAITING:
                return 'In attesa';
            case self::ACCEPTED:
                return 'Accettata';
            case self::REJECTED:
                return 'Respinta';
            case self::NOTICED:
                return 'Constatata';
            default:
                return null;
        }
    }
}
