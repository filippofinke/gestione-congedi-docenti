<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;

class Administrators {

    public static function getByEmail($email) {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM administrators WHERE email = :email";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        if($stm->execute()) {
            return $stm->fetch(\PDO::FETCH_ASSOC);
        }
        return false;
    }

}