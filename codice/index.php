<?php
use FilippoFinke\Utils\Database;
use FilippoFinke\Libs\Ldap;
use FilippoFinke\Libs\Mail;
use FilippoFinke\Router;
use FilippoFinke\Middlewares\AdministratorRequired;
use FilippoFinke\Middlewares\AuthRequired;
use FilippoFinke\Middlewares\LdapUserRequired;
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

$router = new Router();

$router->get('/assets/js/{asset}', 'FilippoFinke\Controllers\Assets::js');
$router->get('/assets/css/{asset}', 'FilippoFinke\Controllers\Assets::css');
$router->get('/assets/fonts/{asset}', 'FilippoFinke\Controllers\Assets::fonts');
$router->get('/assets/img/{asset}', 'FilippoFinke\Controllers\Assets::img');

$router->get('/login', 'FilippoFinke\Controllers\Auth::index');
$router->get('/login/{token}', 'FilippoFinke\Controllers\Auth::tokenLogin');
$router->post('/login', 'FilippoFinke\Controllers\Auth::doLogin');
$router->get('/logout', 'FilippoFinke\Controllers\Auth::logout');
$router->post('/forgot-password', 'FilippoFinke\Controllers\Auth::forgotPassword');

$adminRoutes = new RouteGroup();
$adminRoutes->add(
    $router->get('/administration', 'FilippoFinke\Controllers\Administration::index'),
    $router->get('/administration/motivations', 'FilippoFinke\Controllers\Administration::motivations'),
    $router->put('/users/{username}', 'FilippoFinke\Controllers\Users::update'),
    $router->post('/users', 'FilippoFinke\Controllers\Users::insert'),
    $router->put('/users', 'FilippoFinke\Controllers\Users::setPassword'),
    $router->delete('/users', 'FilippoFinke\Controllers\Users::delete')
)
->before(new AuthRequired())
->before(new AdministratorRequired());

$dashboardRoutes = new RouteGroup();
$dashboardRoutes->add(
    $router->get('/dashboard', 'FilippoFinke\Controllers\Dashboard::index')
)
->before(new AuthRequired())
->before(new LdapUserRequired());


$router->get('/', function ($req, $res) {
    if ($_SESSION["permission"] == "Administrator") {
        $res->redirect('/administration');
    } else {
        $res->redirect('/dashboard');
    }
})->before(new AuthRequired());

$router->start();
