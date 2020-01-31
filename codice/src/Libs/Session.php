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
        if(is_array($data)) {
            foreach($data as $key => $value) {
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
}
