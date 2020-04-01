<?php
namespace FilippoFinke\Libs;

use FilippoFinke\Models\LdapUser;

/**
 * Ldap.php
 * Classe utilizzata per gestire la connessione LDAP.
 *
 * @author Filippo Finke
 */
class Ldap
{
    /**
     * Il server LDAP da interrogare.
     */
    private static $host;

    /**
     * La porta al quale collegarsi. Default = 389
     */
    private static $port = 389;

    /**
     * Il prefisso da appendere agli utenti LDAP.
     */
    private static $prefix;

    /**
     * Il percorso di ricerca LDAP.
     */
    private static $dn;

    /**
     * I gruppi che possono accedere all'applicativo.
     */
    private static $allowedGroups;

    /**
     * Metodo setter per il server LDAP.
     *
     * @param $host Il server LDAP.
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    /**
     * Metodo setter per la porta LDAP.
     *
     * @param $port La porta LDAP.
     */
    public static function setPort($port)
    {
        self::$port = $port;
    }

    /**
     * Metodo setter per il prefisso LDAP.
     *
     * @param $prefix Il prefisso LDAP.
     */
    public static function setPrefix($prefix)
    {
        self::$prefix = $prefix;
    }

    /**
     * Metodo setter per il percorso di ricerca LDAP.
     *
     * @param $dn Il percorso di ricerca LDAP.
     */
    public static function setDn($dn)
    {
        self::$dn = $dn;
    }
    
    /**
     * Metodo setter per i gruppi permessi al login.
     *
     * @param $allowedGroups I gruppi che possono accedere.
     */
    public static function setAllowedGroups($allowedGroups)
    {
        self::$allowedGroups = $allowedGroups;
    }

    /**
     * Metodo utilizzato per controllare se una stringa contiene
     * i gruppi permessi al login.
     *
     * @param $group La stringa da controllare.
     * @return bool True se il gruppo è permesso altrimenti false.
     */
    private static function isAllowed($group)
    {
        $group = strtolower($group);
        foreach (self::$allowedGroups as $allowed) {
            if (strpos($group, $allowed) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Metodo utilizzato per autenticarsi sul server LDAP.
     *
     * @param $username Il nome utente.
     * @param $password La password da utilizzare.
     * @return bool True se l'accesso è eseguito altrimenti false.
     */
    public static function login($username, $password)
    {
        if (EXTERNAL_LDAP) {
            $data = ExternalLdap::login($username, $password);
            if ($data && self::isAllowed($data["appartenenza"])) {
                list($name, $lastName) = explode(".", $data["username"], 2);
                return new LdapUser($username, ucfirst($name), ucfirst($lastName));
            } else {
                return false;
            }
        }
        
        $connectionString = ldap_connect("ldap://".self::$host.":".self::$port."/");

        ldap_set_option($connectionString, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connectionString, LDAP_OPT_REFERRALS, 0);

        if ($connectionString) {
            $bind = @ldap_bind($connectionString, self::$prefix.$username, $password);
            if ($bind) {
                $filter = "(sAMAccountName=" . $username . ")";
                $result = ldap_search($connectionString, self::$dn, $filter, array());
                $entries = ldap_get_entries($connectionString, $result);
                if ($entries["count"] == 1) {
                    if (($entries[0]["useraccountcontrol"][0] & 2) == 2) {
                        // Account disabilitato.
                        return false;
                    }
                    
                    $name = utf8_encode($entries[0]["givenname"][0]);
                    $lastName = utf8_encode($entries[0]["sn"][0]);
                    $groups = $entries[0]["memberof"];
                    foreach ($groups as $key => $value) {
                        if ($key != "count") {
                            if (self::isAllowed($value)) {
                                return new LdapUser($username, $name, $lastName);
                            }
                        }
                    }

                    // L'utente non ha il permesso.
                    return false;
                } else {
                    // L'utente non è stato trovato.
                    return false;
                }
            } else {
                // Errore username o password.
                return false;
            }
        } else {
            // Errore nella stringa di connessione al server LDAP.
            return false;
        }
    }
}
