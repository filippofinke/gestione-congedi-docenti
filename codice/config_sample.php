<?php
/**
 * Config.php
 * File nel quale risiede la configurazione del progetto.
 * 
 * @author Filippo Finke
 */

/**
 * Configurazioni riguardanti la banca dati.
 */
// Indirizzo di connessione del database.
define("DB_HOST", "127.0.0.1");
// Il nome del database.
define("DB_NAME", "congedi");
// L'username dell'utente da utilizzare per collegarsi al database.
define("DB_USERNAME", "USERNAME");
// La password da utilizzarre per il collegamento al database.
define("DB_PASSWORD", "PASSWORD");

/**
 * Configurazioni riguardanti le email.
 */
define("EMAIL_FROM", "no-reply@gestione-congedi-cpt.ch");

/**
 * Configurazioni riguardanti LDAP.
 */
// Il server LDAP da interrogare.
define("LDAP_HOST", "sv-104-dc.cpt.local");
// Il prefisso del dominio da appendere all'inizio di ogni nome utente.
define("LDAP_PREFIX", "CPT\\");
// Il percorso nel quale eseguire la ricerca degli utenti LDAP.
define("LDAP_SEARCH_DN", "DC=CPT,DC=local");
// I gruppi che hanno il permesso di accedere all'applicativo tramite LDAP.
define("LDAP_ALLOWED_GROUPS", array(
    "docenti", // Gruppo docenti
    "amministrazione" // Gruppo amministrazione che comprende Segretariato, Direzione e Vice direzione.
));