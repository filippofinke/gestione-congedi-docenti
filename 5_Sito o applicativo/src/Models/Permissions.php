<?php
namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use PDOException;

/**
 * Permissions.php
 * Classe utilizzata per intefacciarsi con la tabella "permission".
 *
 * @author Filippo Finke
 */
class Permissions
{
    /**
     * Metodo getter per ricavare tutti i permessi.
     *
     * @return array Un array di permessi.
     */
    public static function getAll()
    {
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
