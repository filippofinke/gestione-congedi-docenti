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
     * @param $file Il contenuto del file.
     * @param $filename Il nome del file da inviare.
     * @return bool True se è stata inviata altrimenti false.
     */
    public static function send($to, $subject, $message, $content = null, $filename = null)
    {
        $separator = md5(time());
        $eol = "\r\n";

        // header principale
        $headers = "From: <test@test.com>" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // messaggio
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol . $eol;

        if ($content && $filename) {
            // allegati
            $content = chunk_split(base64_encode($content));
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol;
            $body .= $content . $eol . $eol;
            $body .= "--" . $separator . "--";
        }

        return mail($to, $subject, $body, $headers);
    }
}
