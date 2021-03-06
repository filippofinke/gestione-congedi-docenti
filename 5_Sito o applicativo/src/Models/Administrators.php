<?php

namespace FilippoFinke\Models;

use FilippoFinke\Libs\Mail;
use FilippoFinke\Utils\Database;
use PDOException;

/**
 * Administrators.php
 * Classe utilizzata per intefacciarsi con la tabella "administrators".
 *
 * @author Filippo Finke
 */
class Administrators
{
    /**
     * Metodo utilizzato per ricavare tutti gli amministratori.
     *
     * @return array Array di amministratori.
     */
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM administrators";
        try {
            $stm = $pdo->query($query);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per ricavare un amministratore dalla sua
     * email.
     *
     * @param $email L'indirizzo email da cercare.
     * @return array L'amministratore oppure false.
     */
    public static function getByEmail($email)
    {
        $pdo = Database::getConnection();
        $query = "SELECT * FROM administrators WHERE email = :email";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        try {
            $stm->execute();
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per eliminare un amministratore usando la sua
     * email.
     *
     * @param $email L'indirizzo email da cercare.
     * @return boolean True oppure false.
     */
    public static function delete($email)
    {
        $pdo = Database::getConnection();
        $query = "DELETE FROM administrators WHERE email = :email";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per inserire un amministratore.
     *
     * @param $name Il nome.
     * @param $lastName Il cognome.
     * @param $email L'indirizzo email.
     * @return boolean True oppure false.
     */
    public static function insert($name, $lastName, $email)
    {
        $pdo = Database::getConnection();
        $query = "INSERT INTO administrators(name, last_name, email, password) VALUES (:name, :last_name, :email, :password)";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':name', $name);
        $stm->bindParam(':last_name', $lastName);
        $stm->bindParam(':email', $email);
        $password = self::randomPassword();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stm->bindParam(':password', $hash);
        try {
            $content = "<p>Salve,<br>";
            $content .= "è stato creato un account amministratore con questo indirizzo email.<br>";
            $content .= "Credenziali di accesso:<br>";
            $content .= "Email: ".$email."<br>";
            $content .= "Password: ".$password."</p>";
            return $stm->execute() && Mail::send($email, 'Credenziali di accesso | Gestione congedi', $content);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Metodo utilizzato per impostare la password di un amministratore.
     *
     * @param $email L'indirizzo email dell'amministratore.
     * @param $password La nuova password.
     * @return boolean True oppure false.
     */
    public static function setPassword($email, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo = Database::getConnection();
        $query = "UPDATE administrators SET password = :password WHERE email = :email";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':email', $email);
        $stm->bindParam(':password', $hash);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per generare una password random di 20 caratteri.
     *
     * @return string La password generata randomicamente.
     */
    private static function randomPassword()
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 20; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
