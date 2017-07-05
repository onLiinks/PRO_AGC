<?php

/**
 * Fichier index.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */
/**
 * Inclusion de fichiers
 */
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

set_error_handler("Error::errorHandler");
register_shutdown_function('Error::shutdownHandler');

/**
 * Initialisation des variables 
 */
$titre = '';
$squelette = DEFAULT_HTML;

session_start();
checkSession();

if ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) {
    $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
} else {
    if (!$_SESSION[SESSION_PREFIX.'logged'])
        $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
    if($_POST['url'])
        $url = $url = str_replace("/AGC/", "", $_POST['url']);
    else
        $url = $url = str_replace("/AGC/", "", $_GET['url']);
    switch ($_GET['a']) {
        /**
         * Vérification d'un utilisateur
         */
        case 'verifUser':
            $method = (isset($_POST['method'])) ? $_POST['method'] : $_GET['method'];
            if ($method == 'ad' && $_SESSION[SESSION_PREFIX.'logged']->checkToken('post') && $_SESSION[SESSION_PREFIX.'logged']->checkUser($_POST['login'], $_POST['pass'], $method)
                     || ($method == 'sso' && $_SESSION[SESSION_PREFIX.'logged']->verifUser($_POST['login'], $_POST['pass'], $method))) {
                if ($url)
                    header('location: ../' . $url);
                else
                    $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
            } else {
                header('location: ../public/index.php?url=' . urlencode($url));
            }
            break;

        default:
            if ($_COOKIE['agcid']) {
                if ($_SESSION[SESSION_PREFIX.'logged']->initializeUser()) {
                    if ($url)
                        header('location: ../' . $url);
                    else
                        $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
                } else {
                    setcookie('agcid', '', time() - 4200, '/', $_SERVER['HTTP_HOST'], false);
                    header('location: ../public/index.php?url=' . urlencode($url));
                }
            } else {
                $contenu = $_SESSION[SESSION_PREFIX.'logged']->logBox();
            }
    }
}

/**
 * Inclusion du HTML
 */
require_once($squelette);
?>