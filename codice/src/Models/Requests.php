<?php

namespace FilippoFinke\Models;

use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Utils\Database;
use PDOException;

class Requests
{
    public static function getWaitingCountByUsername($username)
    {
        $pdo = Database::getConnection();
        $query = "SELECT COUNT(*) as 'count' FROM requests WHERE username = :username AND status = :status";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":username", $username);
        $stm->bindValue(":status", RequestStatus::WAITING);
        try {
            if ($stm->execute()) {
                return $stm->fetch()["count"];
            }
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
