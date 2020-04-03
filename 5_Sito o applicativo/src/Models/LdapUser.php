<?php
namespace FilippoFinke\Models;

use FilippoFinke\Models\LdapUsers;
use FilippoFinke\Utils\Database;
use PDOException;

/**
 * LdapUser.php
 * Classe che rappresenta un utente.
 *
 * @author Filippo Finke
 */
class LdapUser
{

    /**
     * L'username dell'utente.
     */
    private $username;

    /**
     * Il nome dell'utente.
     */
    private $name;

    /**
     * Il cognome dell'utente.
     */
    private $lastName;

    /**
     * Il permesso dell'utente.
     */
    private $permission;

    /**
     * Metodo getter per ricavare l'username dell'utente.
     *
     * @return String L'username dell'utente.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Metodo getter per ricavare il nome dell'utente.
     *
     * @return String Il nome dell'utente.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Metodo getter per ricavare il cognome dell'utente.
     *
     * @return String Il cognome dell'utente.
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Metodo getter per ricavare il permesso dell'utente.
     *
     * @return String Il permesso dell'utente.
     */
    public function getPermission()
    {
        return $this->permission;
    }


    /**
     * Metodo costruttore con 4 parametri.
     *
     * @param $username L'username dell'utente.
     * @param $name Il nome dell'utente.
     * @param $lastName Il cognome dell'utente.
     * @param $permission Il permesso dell'utente.
     */
    public function __construct($username, $name, $lastName)
    {
        $this->username = $username;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->setPermission();
    }

    /**
     * Metodo utilizzato per impostare il permesso dell'utente.
     */
    private function setPermission()
    {
        $data = LdapUsers::getByUsername($this->username);
        if ($data) {
            $this->permission = $data["permission"];
        } else {
            LdapUsers::insert($this->username, $this->name, $this->lastName);
            $this->permission = "Docente";
        }
    }

    /**
     * Metodo utilizzato per aggiornare l'ultimo accesso.
     */
    public function updateLastLogin()
    {
        $pdo = Database::getConnection();
        $query = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE username = :username";
        $stm = $pdo->prepare($query);
        $stm->bindParam(':username', $this->username);
        try {
            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Metodo utilizzato per stampare le informazioni dell utente.
     *
     * @return String Una stringa nel formato "Nome Cognome".
     */
    public function __toString()
    {
        return $this->name." ".$this->lastName;
    }
}
