<?php

namespace FilippoFinke\Models;

use FilippoFinke\Models\RequestStatus;
use FilippoFinke\Utils\Database;
use PDOException;

/**
 * Requests.php
 * Classe utilizzata per gestire i congedi.
 *
 * @author Filippo Finke
 */
class Requests
{
    /**
     * Metodo utilizzato per ricavare i congedi in attesa di un utente.
     *
     * @param $username L'username dell'utente.
     * @return array Array di congedi.
     */
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

    /**
     * Metodo utilizzato per ricavare i congedi grazie al loro stato e contenitore.
     *
     * @param $status Lo stato dei congedi.
     * @param $container Il contenitore dei congedi.
     * @return array Array di congedi.
     */
    public static function getByStatusAndContainer($status, $container)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM requests WHERE status = :status AND container = :container ORDER BY created_at DESC";
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

    /**
     * Metodo utilizzato per impostare un contenitore.
     *
     * @param $id L'id del congedo.
     * @param $container Il contenitore.
     * @return array True o false.
     */
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

    /**
     * Metodo utilizzato per ricavare un congedo dal suo id.
     *
     * @param $id L'id del congedo.
     * @return array Il congedo.
     */
    public static function getById($id)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM requests WHERE id = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":id", $id);
        try {
            $stm->execute();
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function update($id, $week)
    {
        $pdo = Database::getConnection();
        $query = "UPDATE requests SET week = :week WHERE id = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":id", $id);
        $stm->bindparam(":week", $week);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function deleteReasons($id)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM request_reason WHERE request = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':id', $id);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
        }
        return false;
    }

    public static function deleteSubstitutes($id)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM substitutes WHERE request = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':id', $id);
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
