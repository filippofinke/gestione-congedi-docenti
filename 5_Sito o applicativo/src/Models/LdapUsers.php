<?php

namespace FilippoFinke\Models;

use FilippoFinke\Utils\Database;
use PDOException;

/**
 * LdapUsers.php
 * Classe utilizzata per intefacciarsi con la tabella "users".
 *
 * @author Filippo Finke
 */
class LdapUsers
{
    /**
     * Metodo utilizzato per ricavare tutti gli utenti LDAP.
     *
     * @return array Array di utenti LDAP.
     */
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM users";
        try {
            $stm = $pdo->query($query);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per ricavare un utente LDAP dal suo username.
     *
     * @param $username L'username da cercare.
     * @return array L'amministratore oppure false.
     */
    public static function getByUsername($username)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM users WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        try {
            $stm->execute();
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per inserire un utente LDAP.
     *
     * @param $username L'username.
     * @param $name Il nome.
     * @param $lastName Il cognome.
     * @return boolean True oppure false.
     */
    public static function insert($username, $name, $lastName)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO users(username, name, last_name) VALUES(:username, :name, :last_name)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':last_name', $lastName);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per impostare il permesso di un utente LDAP.
     *
     * @param $username L'username.
     * @param $permission Il permesso.
     * @return boolean True oppure false.
     */
    public static function setPermission($username, $permission)
    {
        $pdo = Database::getConnection();
        $query = "UPDATE users SET permission = :permission WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        $stm->bindParam(':permission', $permission);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per eliminare un utente LDAP usando l'username.
     *
     * @param $username L'username da eliminare.
     * @return boolean True oppure false.
     */
    public static function delete($username)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM users WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $username);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
