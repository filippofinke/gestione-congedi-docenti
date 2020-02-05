<?php
namespace FilippoFinke\Libs;

/**
 * Session.php
 * Classe utilizzata per gestire la sessione corrente.
 *
 * @author Filippo Finke
 */
class Session
{
    /**
     * Metodo utilizzato per controllare se un utente ha eseguito il login oppure no.
     *
     * @return bool True se è autenticato altrimenti false.
     */
    public static function isAuthenticated()
    {
        return (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] == true);
    }

    /**
     * Metodo utilizzato per autenticare l'utente corrente.
     *
     *  @param $data Un array contenente i parametri da salvare.
     */
    public static function authenticate($data = null)
    {
        $_SESSION["authenticated"] = true;
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    /**
     * Metodo utilizzato per eseguire la disconnessione.
     */
    public static function logout()
    {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        session_destroy();
    }

    /**
     * Metodo utilizzato per verificare se un utente è un docente.
     *
     * @return bool True se appartiene al gruppo altrimenti false.
     */
    public static function isTeacher()
    {
        $permissions = ["Docente", "Segreteria", "Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    /**
     * Metodo utilizzato per verificare se un utente fa parte della segreteria.
     *
     * @return bool True se appartiene al gruppo altrimenti false.
     */
    public static function isSecretary()
    {
        $permissions = ["Segreteria", "Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    /**
     * Metodo utilizzato per verificare se un utente fa parte della direzione.
     *
     * @return bool True se appartiene al gruppo altrimenti false.
     */
    public static function isAdministration()
    {
        $permissions = ["Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    /**
     * Metodo utilizzato per verificare se un utente è amministratore.
     *
     * @return bool True se appartiene al gruppo altrimenti false.
     */
    public static function isAdministrator()
    {
        return $_SESSION["permission"] == "Administrator";
    }
}
