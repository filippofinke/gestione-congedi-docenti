<?php
namespace FilippoFinke\Models;
use FilippoFinke\Utils\Database;
use PDOException;

class Permissions {

    public static function getAll() {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM permissions";
        try {
            $stm = $pdo->query($query);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

}