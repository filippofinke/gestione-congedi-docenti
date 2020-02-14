<?php

namespace FilippoFinke\Libs;

/**
 * Validators.php
 * Classe utilizzata per eseguire la validazione dei campi.
 *
 * @author Filippo Finke
 */
class Validators
{
    /**
     * Metodo che controlla se del testo è una email valida.
     *
     * @param $email Il testo da controllare.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Metodo che controlla se del testo è un nome valido.
     *
     * @param $name Il testo da controllare.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidName($name)
    {
        return self::isValidAlphabetAndAccents($name, 20);
    }

    /**
     * Metodo che controlla se del testo è un cognome valido.
     *
     * @param $lastName Il testo da controllare.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidLastName($lastName)
    {
        return self::isValidAlphabetAndAccents($lastName, 20);
    }

    /**
     * Metodo che controlla se del testo è un username valido.
     *
     * @param $username Il testo da controllare.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidLdapUsername($username)
    {
        // Da verificare
        return $username && strlen($username) <= 20;
    }

    /**
     * Metodo che controlla se del testo è una password valida.
     *
     * @param $password Il testo da controllare.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidPassword($password)
    {
        return strlen($password) >= 6;
    }

    /**
     * Metodo che controlla se del testo contiene solamente
     * caratteri dell'alfabeto e accenti.
     *
     * @param $text Il testo da controllare.
     * @param $max La lunghezza massima.
     * @param $min La lunghezza minima, default = 1.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidAlphabetAndAccents($text, $max, $min = 1)
    {
        return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]{'.$min.','.$max.'}$/', $text);
    }

    /**
     * Metodo che controlla se del testo è una descrizione valida.
     *
     * @param $text Il testo da controllare.
     * @param $min La lunghezza minima, default = 1.
     * @param $max La lunghezza massima, default = 255.
     * @return bool True se il testo è valido altrimento false.
     */
    public static function isValidDescription($text, $min = 1, $max = 255)
    {
        $safe = htmlspecialchars($text);
        return strlen($text) >= $min && strlen($text) <= $max && $safe == $text;
    }
}
