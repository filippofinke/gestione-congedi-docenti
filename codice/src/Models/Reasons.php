<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use \PDOException;

class Reasons
{
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM reasons";
        try {
            $stm = $pdo->query($query);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insert($name, $description)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO reasons(name, description) VALUES (:name, :description)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':description', $description);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM reasons WHERE id = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':id', $id);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($id, $name, $description)
    {
        $pdo = Database::getConnection();
        $query = "UPDATE reasons SET name = :name, description = :description WHERE id = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':id', $id);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':description', $description);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
