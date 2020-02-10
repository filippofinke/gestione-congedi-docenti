<?php

namespace FilippoFinke\Models;

class Container{
    
    public const SECRETARY = 0;

    public const ADMINISTRATION = 1;

    public static function get($container) {
        switch($container) {
            case self::SECRETARY: 
                return 'segreteria';
            case self::ADMINISTRATION:
                return 'direzione';
            default:
                return null;
        }
    }
}