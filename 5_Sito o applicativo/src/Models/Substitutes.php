<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use PDOException;

/**
 * Substitutes.php
 * Classe utilizzata per rappresentare i sostituti di una richiesta di congedo.
 *
 * @author Filippo Finke
 */
class Substitutes
{
    /**
     * Metodo utilizzato per ricavare i sostituti di una richiesta di congedo.
     *
     * @param $id L'id della richiesta di congedo.
     * @return array Array di sostituti.
     */
    public static function getByRequestId($id)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM substitutes WHERE request = :id";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":id", $id);
        try {
            $stm->execute();
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per inserire un sostituto ad una richiesta di congedo.
     *
     * @param $request L'id della richiesta di congedo.
     * @param $from La data di inizio.
     * @param $to La data di fine.
     * @param $type Il tipo di supplenza.
     * @param $room L'aula.
     * @param $substitute Il supplente.
     * @param $class La classe.
     * @return boolean True o false.
     */
    public static function insert($request, $from, $to, $type, $room, $substitute, $class)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO substitutes VALUES(:id, :from, :to, :type, :room, :substitute, :class)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(":id", $request);
        $stm->bindParam(":from", $from);
        $stm->bindParam(":to", $to);
        $type = ($type == "")?null:$type;
        $stm->bindParam(":type", $type);
        $stm->bindParam(":room", $room);
        $stm->bindParam(":substitute", $substitute);
        $stm->bindParam(":class", $class);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
