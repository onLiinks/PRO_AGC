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
require_once '../config/config_rh.php';
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

set_error_handler("Error::errorHandler");
register_shutdown_function('Error::shutdownHandler');

/**
 * Réinitialisation de la session
 */
session_start();
checkSession();

/**
 * Initialisation de variables
 */
$titre = '';
$filtre = '';
$contenu = '';
$zone = ZONE_RH;
$menuDroit = MENU_DROIT;
$menuGauche = MENU_GAUCHE;

/**
 * Initialisation de variables dépendant de la zone
 */
$squelette = SOURCE_HTML;

$utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_POST);

if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}

try {
    $_SESSION[SESSION_PREFIX.'logged']->checkEntry($zone);

    /**
     * Fabrication de la page à envoyer au client
     */
    switch ($_GET['a']) {
        case 'creer_metier':
        case 'modifier_metier':
            $titre = 'Métier';
            $metier = new Metier($_GET['Id'], array());
            $contenu = $metier->form();
        break;
        
        case 'enregistrer_metier':
            $titre = 'Métier';
            $metier = new Metier($_POST['Id'], $_POST);
            $url = '../rh/index.php?a=consulter_metier';
            if ($metier->check()) {
                $metier->save();
                header('location: ' . $url . '');
            } else {
                $contenu = $metier->form();
            }
        break;
            
        case 'consulter_metier':
            $titre = 'Métier';
            $filtre = Metier::searchForm();
            $contenu = '<div id="pageMetier"></div><script>afficherMetier()</script>';
        break;
    
        case 'supprimer_metier':
            $metier = new Metier($_GET['Id'], array());
            $metier->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        break;

        case 'consulterAnnonce':
            $titre = ANNONCE;
            $filtre = Annonce::searchForm();
            $contenu = '<div id="pageAnnonce"></div><script>afficherAnnonce()</script>';
        break;
        
        case 'modification_annonce':
            $titre = ANNONCE;
            if ($_GET['Id']) {
                $titre .= ' n°' . (int) $_GET['Id'];
            }
            $class = new Annonce($_GET['Id'], $_POST);
            $contenu = $class->form_part2();
        break;
        
        case 'supprimer_annonce':
            $class = new Annonce($_GET['Id'], array());
            $class->delete();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        break;

        /**
         * Page par défaut
         *
         */
        default :
            $contenu .= Version::getCurrentVersion()->getMessage();
            $contenu .= '<br />
                        <input id="user" name="user" type="hidden" value="' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '" />
			<fieldset>
				<legend>' . MY_NOTE . '</legend><br />
				' . $utilisateur->blocnotes . '
			</fieldset><br />
			<fieldset>
				<legend>' . UNREAD_APPLICATION . '</legend><br />
                                <div id="pageCandidature">
                                    ' . Candidature::search('', '', 1, '', '', '', '', '', '', '', '', '', '', array(), '', '', '', '', '', '', '', array(), '', array(), '', '', '') . '
                                </div>
                        </fieldset><br />
			<fieldset>
				<legend>' . MY_ANNONCES . '</legend><br />
                                <div id="pageAnnonce">
                                    ' . Annonce::search('', '', '', '', '', '', $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '
                                </div>
			</fieldset><br />
			<fieldset>
				<legend>' . RESOURCES_ASKING . '</legend><br />
				<div id="pageDemandeRessource">
                                    ' . DemandeRessource::search('', '', '', '', '', array(), '', $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, '', '','','','','','') . '
				</div>
			</fieldset><br />';
    }
} catch (AGCException $e) {
    $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
}

/**
 * Inclusion du HTML
 */
require_once $squelette;
?>