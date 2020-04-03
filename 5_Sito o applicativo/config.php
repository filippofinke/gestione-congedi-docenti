<?php
/**
 * Config.php
 * File nel quale risiede la configurazione del progetto.
 *
 * @author Filippo Finke
 */

/**
 * Configurazioni per gli errori.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Url di base.
 */
define("BASE_URL", "");

/**
 * Configurazioni riguardanti la banca dati.
 */
// Indirizzo di connessione del database.
define("DB_HOST", "127.0.0.1");
// Il nome del database.
define("DB_NAME", "congedi");
// L'username dell'utente da utilizzare per collegarsi al database.
define("DB_USERNAME", "root");
// La password da utilizzarre per il collegamento al database.
define("DB_PASSWORD", "filospinato22");

/**
 * Configurazioni riguardanti le email.
 */
define("EMAIL_FROM", "no-reply@gestione-congedi-cpt.ch");

/**
 * Configurazioni riguardanti LDAP.
 */
// Specifia se usare LDAP locale oppure esterno.
define("EXTERNAL_LDAP", true);
// Il server LDAP da interrogare.
define("LDAP_HOST", "sv-104-dc.cpt.local");
// Il prefisso del dominio da appendere all'inizio di ogni nome utente.
define("LDAP_PREFIX", "CPT\\");
// Il percorso nel quale eseguire la ricerca degli utenti LDAP.
define("LDAP_SEARCH_DN", "DC=CPT,DC=local");
// I gruppi che hanno il permesso di accedere all'applicativo tramite LDAP.
define("LDAP_ALLOWED_GROUPS", array(
    "docenti", // Gruppo docenti.
    "docente", // Gruppo docenti.
    "direzione", // Gruppo direzione.
    "amministrazione", // Gruppo amministrazione.
    "studente" // Utilizzato per test.
));

/**
 * Configurazione del calendario.
 */
// Orari da mostrare.
define("CALENDAR_HOURS", array(
    array("start" => "08:20", "end" => "09:05", "allow" => true),
    array("start" => "09:05", "end" => "09:50", "allow" => true),
    array("start" => "10:05", "end" => "10:50", "allow" => true),
    array("start" => "10:50", "end" => "11:35", "allow" => true),
    array("start" => "11:35", "end" => "12:20", "allow" => true),
    array("start" => "12:30", "end" => "13:15", "allow" => false),
    array("start" => "13:15", "end" => "14:00", "allow" => true),
    array("start" => "14:00", "end" => "14:45", "allow" => true),
    array("start" => "15:00", "end" => "15:45", "allow" => true),
    array("start" => "15:45", "end" => "16:30", "allow" => true),
    array("start" => "16:30", "end" => "17:15", "allow" => true),
    array("space" => true),
    array("start" => "16:00", "end" => "16:45", "allow" => true),
    array("start" => "16:45", "end" => "17:30", "allow" => true),
    array("start" => "17:30", "end" => "18:15", "allow" => true),
    array("start" => "18:30", "end" => "19:15", "allow" => true),
    array("start" => "19:15", "end" => "20:00", "allow" => true),
    array("start" => "20:15", "end" => "21:00", "allow" => true),
    array("start" => "21:00", "end" => "21:45", "allow" => true),
));
// Giorni del calendario.
define("CALENDAR_LABELS", array("Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"));
