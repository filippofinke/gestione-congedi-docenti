<?php

namespace FilippoFinke\Models;

use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Utils\Database;
use PDOException;

class Requests
{
    public static function getWaitingByUsername($username)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM requests WHERE username = :username AND status = :status";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":username", $username);
        $stm->bindValue(":status", RequestStatus::WAITING);
        try {
            $stm->execute();
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function getByStatusAndContainer($status, $container)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM requests WHERE status = :status AND container = :container";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":status", $status);
        $stm->bindValue(":container", $container);
        try {
            $stm->execute();
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function setContainer($id, $container)
    {
        $pdo = Database::getConnection();
        $query = "UPDATE requests SET container = :container WHERE id = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":container", $container);
        $stm->bindValue(":id", $id);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function insert($username, $week)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO requests(username, week) VALUES (:username, :week)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":username", $username);
        $stm->bindParam(":week", $week);
        try {
            if ($stm->execute()) {
                return $pdo->lastInsertId();
            }
        } catch (PDOException $e) {
        }
        return false;
    }
}
