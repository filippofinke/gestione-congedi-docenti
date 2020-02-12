<?php

namespace FilippoFinke\Libs;

class Validators
{
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidName($name)
    {
        return self::isValidAlphabetAndAccents($name, 20);
    }

    public static function isValidLastName($name)
    {
        return self::isValidAlphabetAndAccents($name, 20);
    }

    public static function isValidLdapUsername($username)
    {
        // Da verificare
        return $username && strlen($username) <= 20;
    }

    public static function isValidPassword($password)
    {
        return strlen($password) >= 6;
    }

    public static function isValidAlphabetAndAccents($text, $max, $min = 1)
    {
        return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]{'.$min.','.$max.'}$/', $text);
    }


    public static function isValidDescription($text, $min = 1, $max = 255) {
        $safe = htmlspecialchars($text);
        return strlen($text) >= $min && strlen($text) <= $max && $safe == $text;
    }
}
