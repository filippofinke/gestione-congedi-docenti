<?php
namespace FilippoFinke\Libs;

/**
 * Mail.php
 * Classe utilizzata per gestire l'invio di posta elettronica.
 *
 * @author Filippo Finke
 */
class Mail
{
    /**
     * L'email dal quale verranno inviati i messaggi.
     */
    private static $fromEmail;

    /**
     * Metodo setter per l'email dal quale inviare i messaggi.
     * 
     * @param $fromEmail L'email.
     */
    public static function setFromEmail($fromEmail)
    {
        self::$fromEmail = $fromEmail;
    }

    /**
     * Metodo per inviare email.
     * 
     * @param $to L'email al quale inviare le email.
     * @param $subject Il titolo dell'email.
     * @param $body Il contenuto dell'email.
     * @return bool True se è stata inviata altrimenti false.
     */
    public static function send($to, $subject, $body)
    {
        $header = "From: ".self::$fromEmail."\r\n";
        $header.= "MIME-Version: 1.0\r\n";
        $header.= "Content-Type: text/html; charset=utf-8\r\n";
        $header.= "X-Priority: 1\r\n";
        return mail($to, $subject, $body, $header);
    }
}
