<?php
use FilippoFinke\Utils\Database;
use FilippoFinke\Libs\Ldap;
use FilippoFinke\Libs\Mail;
use FilippoFinke\Libs\Session;
use FilippoFinke\Middlewares\AdministrationRequired;
use FilippoFinke\Router;
use FilippoFinke\Middlewares\AdministratorRequired;
use FilippoFinke\Middlewares\AuthRequired;
use FilippoFinke\Middlewares\LdapUserRequired;
use FilippoFinke\Middlewares\SecretaryRequired;
use FilippoFinke\RouteGroup;

/**
 * index.php
 * File principale dell'applicativo web.
 * Si occupa di inizializzare tutte le dipendenze.
 *
 * @author Filippo Finke
 */

// Avvio sessione.
session_start();

// Controllo la presenza del file di configurazione.
if (!file_exists("config.php")) {
    exit("File di configurazione 'config.php' mancante! Puoi ricavarlo attraverso il file di esempio 'config_sample.php!");
}
// Includo del file di configurazione dell'applicativo.
require __DIR__ . '/config.php';
$_SERVER["REQUEST_URI"] = str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]);

// Includo dell'autoloader del gestore di pacchetti Composer.
require __DIR__ . '/vendor/autoload.php';

// Imposto indirizzo del server MySQL.
Database::setHost(DB_HOST);
// Imposto del database da utilizzare.
Database::setDatabase(DB_NAME);
// Imposto del nome utente per accedere al database.
Database::setUsername(DB_USERNAME);
// Imposto della password per accedere al database.
Database::setPassword(DB_PASSWORD);

// Imposto l'indirizzo email dal quale inviare la posta elettronica.
Mail::setFromEmail(EMAIL_FROM);

// Imposto del server LDAP da interrogare.
Ldap::setHost(LDAP_HOST);
// Imposto il prefisso per gli username.
Ldap::setPrefix(LDAP_PREFIX);
// Imposto il percorso di ricerca LDAP.
Ldap::setDn(LDAP_SEARCH_DN);
// Imposto i gruppi permessi al login dell'applicativo.
Ldap::setAllowedGroups(LDAP_ALLOWED_GROUPS);

// Oggetto nel quale verranno salvati tutti i percorsi dell'applicativo.
$router = new Router();

/**
 * Percorsi riguardanti gli assets.
 */
// Percorso per file javascript.
$router->get('/assets/js/{asset}', 'FilippoFinke\Controllers\Assets::js');
// Percorso per file css.
$router->get('/assets/css/{asset}', 'FilippoFinke\Controllers\Assets::css');
// Percorso per i font.
$router->get('/assets/fonts/{asset}', 'FilippoFinke\Controllers\Assets::fonts');
// Percorso per le immagini.
$router->get('/assets/img/{asset}', 'FilippoFinke\Controllers\Assets::img');

/**
 * Percorsi riguardanti l'accesso.
 */
// Percorso pagina di accesso.
$router->get('/login', 'FilippoFinke\Controllers\Auth::index');
// Percorso login attraverso token di recupero.
$router->get('/login/{token}', 'FilippoFinke\Controllers\Auth::tokenLogin');
// Percorso per eseguire il login.
$router->post('/login', 'FilippoFinke\Controllers\Auth::doLogin');
// Percorso per disconnettersi.
$router->get('/logout', 'FilippoFinke\Controllers\Auth::logout');
// Percorso per richiedere il recupero password.
$router->post('/forgot-password', 'FilippoFinke\Controllers\Auth::forgotPassword');

/**
 * Percorsi per la gestione dell'applicativo.
 */
// Gruppo di percorsi di gestione.
$adminRoutes = new RouteGroup();
// Aggiunta dei percorsi al gruppo.
$adminRoutes->add(
    // Percorso per l'aggiornamento di una motivazione.
    $router->put('/reasons/{id:[0-9]+}', 'FilippoFinke\Controllers\Reasons::update'),
    // Percorso per eliminare una motivazione.
    $router->delete('/reasons/{id:[0-9]+}', 'FilippoFinke\Controllers\Reasons::delete'),
    // Percorso per inserire una motivazione.
    $router->post('/reasons', 'FilippoFinke\Controllers\Reasons::insert'),
    // Pagina di gestione utenti.
    $router->get('/administration', 'FilippoFinke\Controllers\Administration::index'),
    // Pagina di gestione motivazioni.
    $router->get('/administration/reasons', 'FilippoFinke\Controllers\Administration::reasons'),
    // Percorso per aggiornare un utente.
    $router->put('/users/{username}', 'FilippoFinke\Controllers\Users::update'),
    // Percorso per l'inserimento di un utente.
    $router->post('/users', 'FilippoFinke\Controllers\Users::insert'),
    // Percorso per impostare la password ad un utente.
    $router->put('/users', 'FilippoFinke\Controllers\Users::setPassword'),
    // Percorso per l'eliminazione di un utente.
    $router->delete('/users', 'FilippoFinke\Controllers\Users::delete')
)
// Aggiunta controllo autenticazione.
->before(new AuthRequired())
// Aggiunta controllo permesso amministratore.
->before(new AdministratorRequired());

/**
 * Percorsi per utenti LDAP.
 */
// Gruppo di percorsi per utenti LDAP.
$dashboardRoutes = new RouteGroup();
// Aggiunta dei percorsi al gruppo.
$dashboardRoutes->add(
    // Pagina di visione e modifica congedi.
    $router->get('/dashboard/{id:[0-9]+}', 'FilippoFinke\Controllers\Dashboard::index')
    // Controllo che l'utente appartenga alla segreteria.
    ->before(new SecretaryRequired()),
    // Pagina principale.
    $router->get('/dashboard', 'FilippoFinke\Controllers\Dashboard::index'),
    // Pagina di istoriato personale.
    $router->get('/dashboard/history', 'FilippoFinke\Controllers\Dashboard::history'),
    // Pagina di istoriato generale.
    $router->get('/dashboard/administration/history', 'FilippoFinke\Controllers\Dashboard::history')
    ->before(new AdministrationRequired()),
    // Pagina di congedi in attesa.
    $router->get('/dashboard/sent', 'FilippoFinke\Controllers\Dashboard::sent'),
    // Pagina congedi in attesa segreteria.
    $router->get('/dashboard/secretariat', 'FilippoFinke\Controllers\Dashboard::secretariat')
    // Controllo che l'utente appartenga alla segreteria.
    ->before(new SecretaryRequired()),
    // Percorso per la generazione del pdf.
    $router->get('/requests/{id:[0-9]+}/pdf', 'FilippoFinke\Controllers\Requests::pdf'),
    // Percorso per la creazione di un congedo.
    $router->post('/requests', 'FilippoFinke\Controllers\Requests::insert'),
    // Percorso per l'aggiornamento dei dati di un congedo.
    $router->put('/requests/{id:[0-9]+}', 'FilippoFinke\Controllers\Requests::update')
    // Controllo che l'utente appartenga alla segreteria.
    ->before(new SecretaryRequired()),
    // Pagina congedi in attesa direzione.
    $router->get('/dashboard/administration', 'FilippoFinke\Controllers\Dashboard::administration')
    // Controllo che l'utente appartenga all'amministrazione.
    ->before(new AdministrationRequired())
)
// Aggiunta controllo autenticazione.
->before(new AuthRequired())
// Aggiunta controllo permesso LDAP.
->before(new LdapUserRequired());

/**
 * Percorso di default utilizzato per il redirezionamento degli utenti.s
 */
$router->get('/', function ($req, $res) {
    // Controllo se l'utente è amministratore.
    if (Session::isAdministrator()) {
        // Redirect al pannello admin.
        $res->redirect(BASE_URL . '/administration');
    } else {
        // Redirect al pannello utenti LDAP.
        $res->redirect(BASE_URL . '/dashboard');
    }
    // Aggiunta controllo autenticazione.
})->before(new AuthRequired());

// Avvio la gestione della richiesta.
$router->start();
