<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use PDOException;

class LdapUsers
{
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM users";
        try {
            $stm = $pdo->query($query);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getByUsername($username)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM users WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        try {
            $stm->execute();
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insert($username, $name, $lastName)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO users(username, name, last_name) VALUES(:username, :name, :last_name)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':last_name', $lastName);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function setPermission($username, $permission)
    {
        $pdo = Database::getConnection();
        $query = "UPDATE users SET permission = :permission WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        $stm->bindParam(':permission', $permission);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}