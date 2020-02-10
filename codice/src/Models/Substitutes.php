<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use PDOException;

class Substitutes {

    public static function insert($request, $from, $to, $type, $room, $substitute, $class) {
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