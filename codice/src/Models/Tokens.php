<?php

namespace FilippoFinke\Models;

use FilippoFinke\Libs\LocalAuth;
use FilippoFinke\Libs\Mail;
use FilippoFinke\Utils\Database;
use FilippoFinke\Libs\Session;
use PDOException;

/**
 * Tokens.php
 * Classe utilizzata per intefacciarsi con la tabella "tokens".
 *
 * @author Filippo Finke
 */
class Tokens
{
    public static function login($token)
    {
        $hash = hash('sha256', $token);
        $pdo = Database::getConnection();
        $query = "SELECT * FROM tokens WHERE token = :token";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':token', $hash);
        try {
            $stm->execute();
            $token = $stm->fetch(\PDO::FETCH_ASSOC);
            if ($token && time() - strtotime($token["created_at"]) <= 60 * 10) {
                $_SESSION["force_reset_password"] = true;
                $user = LocalAuth::login($token["email"], false, true);
                $user->updateLastLogin();
                Session::authenticate(array(
                    "username" => $user->getUsername(),
                    "name" => $user->getName(),
                    "lastName" => $user->getLastName(),
                    "permission" => $user->getPermission()
                ));
                self::resetTokens($token["email"]);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function resetTokens($email)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM tokens WHERE email = :email";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function sendToken($email)
    {
        $pdo = Database::getConnection();
        $token = self::generateToken();
        $hash = hash('sha256', $token);
        $query = "INSERT INTO tokens(email, token) VALUES(:email, :token)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        $stm->bindParam(':token', $hash);
        try {
            return $stm->execute() && Mail::send($email, 'Recupero password', 'Codice di recupero: '.$token);
        } catch (PDOException $e) {
            return false;
        }
    }

    private static function generateToken()
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 20; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
