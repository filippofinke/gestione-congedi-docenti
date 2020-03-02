<?php
namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Ldap;
use FilippoFinke\Libs\Session;
use FilippoFinke\Libs\LocalAuth;
use FilippoFinke\Libs\Validators;
use FilippoFinke\Models\LdapUser;
use FilippoFinke\Models\Tokens;

/**
 * Auth.php
 * Controller che si occupa di gestire tutti i percorsi relativi all'autenticazione.
 *
 * @author Filippo Finke
 */
class Auth
{
    /**
     * Metodo che si occupa di renderizzare la pagina di accesso.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function index($request, $response)
    {
        return $response->render(__DIR__ . '/../Views/Auth/login.php');
    }

    /**
     * Metodo utilizzato per verificare le credenziali dell'utente.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function doLogin($request, $response)
    {
        $username = $request->getParam("username");
        $password = $request->getParam("password");
        if ($username && Validators::isValidPassword($password)) {
            if (Validators::isValidEmail($username)) {
                $user = LocalAuth::login($username, $password);
            } else {
                $user = Ldap::login($username, $password);
            }
            // Solo per debug
            //$user = new LdapUser('filippo.finke', 'Filippo', 'Finke');
            
            if ($user) {
                $user->updateLastLogin();
                Session::authenticate(array(
                    "username" => $user->getUsername(),
                    "name" => $user->getName(),
                    "lastName" => $user->getLastName(),
                    "permission" => $user->getPermission()
                ));
                return $response->withStatus(200);
            } else {
                return $response->withStatus(401);
            }
        } else {
            return $response->withStatus(400);
        }
    }

    /**
     * Metodo utilizzato per eseguire il logout.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function logout($request, $response)
    {
        Session::logout();
        return $response->redirect(BASE_URL . "/login");
    }

    /**
     * Metodo utilizzato per la richiesta di un'email di recupero password.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function forgotPassword($request, $response)
    {
        $email = $request->getParam('email');
        if (Validators::isValidEmail($email) && Tokens::resetTokens($email)) {
            Tokens::sendToken($email);
            return $response->withStatus(200);
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per autenticarsi attraverso il token di recupero password.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function tokenLogin($request, $response)
    {
        $token = $request->getAttribute('token');
        if (Tokens::login($token)) {
            $response->redirect(BASE_URL . '/administration');
        } else {
            $response->redirect(BASE_URL . '/login');
        }
    }
}
