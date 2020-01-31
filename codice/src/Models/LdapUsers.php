<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;

class LdapUsers {

    public static function getByUsername($username) {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM users WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        if($stm->execute()) {
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } 
        return false;
    }

    public static function insert($username, $name, $lastName) {
        $pdo = Database::getConnection();
        $query = "INSERT INTO users(username, name, last_name) VALUES(:username, :name, :last_name)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':last_name', $lastName);
        return $stm->execute();
    }


}