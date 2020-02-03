<?php
namespace FilippoFinke\Libs;

class Session
{
    public static function isAuthenticated()
    {
        return (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] == true);
    }

    public static function authenticate($data = null)
    {
        $_SESSION["authenticated"] = true;
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    public static function logout()
    {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        session_destroy();
    }

    public static function isTeacher()
    {
        $permissions = ["Docente", "Segreteria", "Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    public static function isSecretary()
    {
        $permissions = ["Segreteria", "Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    public static function isAdministration()
    {
        $permissions = ["Vice direzione", "Direzione"];
        return in_array($_SESSION["permission"], $permissions);
    }

    public static function isAdministrator()
    {
        return $_SESSION["permission"] == "Administrator";
    }
}
