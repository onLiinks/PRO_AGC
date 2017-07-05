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
 * Initialisation de variables
 */
$titre = '';
$filtre = '';
$contenu = '';
$zone = ZONE_ADMINISTRATION;
$menuDroit = MENU_DROIT;
$menuGauche = MENU_GAUCHE;

/**
 * Initialisation de variables dépendant de la zone 
 */
$squelette = SOURCE_HTML;

/**
 * Réinitialisation de la session
 */
session_start();
checkSession();

if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}
if (AUTH_TYPE == 'SSO') {
    $as = new SimpleSAML_Auth_Simple(SSO_PROVIDER_NAME);
    if (!$as->isAuthenticated()) {
        $as->login();
    }
    $_SESSION[SESSION_PREFIX.'logged']->initializeUser();
}

try {
    if($_GET['a'] != 'changerUtilisateur' || $_SESSION[SESSION_PREFIX.'logged']->impersonate['state'] !== true)
        $_SESSION[SESSION_PREFIX.'logged']->checkEntry($zone);

    /**
     * Fabrication de la page à envoyer au client
     */
    switch ($_GET['a']) {

        case 'enregistrer':
            $titre = $_POST['class'];
            $class = new $_POST['class']($_POST['Id'], $_POST);
            $url = 'index.php?a=consulter' . $_POST['class'];
            if ($class->check()) {
                $class->save();
                header('location: ' . $url . '');
            } else {
                $contenu = $class->form();
            }
            break;

        case 'action':
            switch ($_POST['action']) {
                case 'supprimer':
                    $i = 0;
                    $n = count($_POST[$_POST['class']]);
                    while ($i < $n) {
                        $class = new $_POST['class']($_POST[$_POST['class']][$i], array());
                        $class->delete();
                        ++$i;
                    }
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    break;
            }
            break;

        case 'supprimer':
            $class = new $_GET['class']($_GET['Id'], array());
            $class->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            break;

        case 'modifier':
        case 'creer':
            $titre = $_GET['class'];
            $class = new $_GET['class']($_GET['Id'], array());
            $contenu = $class->form();
            break;

        case 'consulterStatut':
            $titre = STATUS;
            $contenu = Statut::search();
            break;

        case 'consulterAgence':
            $titre = AGENCES;
            $contenu = Agence::search();
            break;

        case 'consulterPole':
            $titre = POLES;
            $contenu = Pole::search();
            break;

        case 'consulterExigence':
            $titre = DEMANDS;
            $contenu = Exigence::search();
            break;

        case 'consulterProfil':
            $titre = PROFILES;
            $contenu = Profil::search();
            break;

        case 'consulterExperience':
            $titre = EXPERIENCE;
            $contenu = Experience::search();
            break;

        case 'consulterCursus':
            $titre = DEGREE_COURSES;
            $contenu = Cursus::search();
            break;

        case 'consulterUtilisateur':
            $titre = USERS;
            $contenu = Utilisateur::search();
            break;

        case 'infoUtilisateur':
            $titre = USERS;
            $utilisateur = new Utilisateur($_GET['Id_utilisateur'], array());
            $contenu = $utilisateur->consultation();
            break;

        case 'changerUtilisateur':
            $titre = USERS;
            $tempId = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
            $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
            $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur = $tempId;
            $_SESSION[SESSION_PREFIX.'logged']->impersonate($_GET['user']);
            $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
            break;

        case 'gestionDroitGroupeAdZoneAcces':
            $squelette = DEFAULT_HTML;
            $titre = AD_GROUP_ACCES_ZONE_RIGHTS;
            $contenu = GestionDroit::groupAdAccesZoneForm();
            break;

        case 'gestionDroitGroupeAdMenu':
            $squelette = DEFAULT_HTML;
            $titre = AD_GROUP_ACCES_MENU_RIGHTS;
            $contenu = GestionDroit::groupAdMenuForm();
            break;

        case 'consulterLog':
            $titre = 'Affichage des Logs';
            $filtre = Log::searchForm();
            $contenu = '<div id="page"></div><script>afficherLog()</script>';
            break;

        /**
         * Page par défaut
         *
         */
        default :
            $contenu = '<h2>Bienvenue sur l\'AGC</h2><br />';
            $contenu .= Version::getCurrentVersion()->getMessage(true);
    }
} catch (AGCException $e) {
    header('location: ../public/index.php?url=' . urlencode($_SERVER['REQUEST_URI']));
}

/**
 * Inclusion du HTML
 */
require_once $squelette;
?>