<?php

namespace FilippoFinke\Models;

/**
 * Container.php
 * Classe che tiene conto dei contenitori.
 *
 * @author Filippo Finke
 */
class Container
{
    /**
     * Contenitore della segreteria.
     */
    public const SECRETARY = 0;

    /**
     * Contenitore di amministrazione.
     */
    public const ADMINISTRATION = 1;

    /**
     * Metodo per ricavare il nome del contenitore.
     *
     * @param $container L'indice del contenitore.
     * @return string Il nome del contenitore.
     */
    public static function get($container)
    {
        switch ($container) {
            case self::SECRETARY:
                return 'segreteria';
            case self::ADMINISTRATION:
                return 'direzione';
            default:
                return null;
        }
    }
}
