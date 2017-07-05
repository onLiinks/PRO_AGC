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
require_once '../config/config_com.php';
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
$zone = ZONE_ADMINISTRATIF;
$menuDroit = MENU_DROIT;
$menuGauche = MENU_GAUCHE;

/**
 * Initialisation de variables dépendant de la zone 
 */
$squelette = SOURCE_HTML;

if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}

try {
    $_SESSION[SESSION_PREFIX.'logged']->checkEntry($zone);

    /**
     * Fabrication de la page à envoyer au client
     */
    switch ($_GET['a']) {

        case 'consulterODM':
            $titre = ODM;
            $filtre = OrdreMission::searchForm();
            $contenu = '<div id="page"></div><script>afficherODM()</script>';
            break;

        case 'creerODM':
            $titre = ODM;
            $odm = new OrdreMission($_GET['Id'], $_GET);
            $contenu = $odm->form();
            break;

        case 'modifierODM':
            $titre = ODM;
            $odm = new OrdreMission($_GET['Id'], array());
            $contenu = $odm->form();
            break;

        case 'editerODM':
            $odm = new OrdreMission($_GET['Id'], array());
            $contenu = $odm->edit();
            break;

        case 'enregistrerODM':
            if ($_POST['Id'] == '0') {
                $_POST['Id'] = null;
            }
            $odm = new OrdreMission($_POST['Id'], $_POST);
            $odm->save();
            ?>
            <script type='text/javascript'>
                alert("<?php echo ODM_SAVED; ?>");
                location.replace('index.php?a=consulterODM')
            </script>
            <?php
            break;

        case 'consulterCD':
            $titre = CONTRAT_DELEGATION;
            $filtre = ContratDelegation::searchForm();
            $contenu = '<div id="page"></div><script>afficherCD()</script>';
            break;

        case 'editerContratDelegation':
            $cd = new ContratDelegation($_GET['Id'], array());
            $contenu = $cd->edit();
            break;

        case 'editerContratDelegationHorsAffaire':
            $cd = new ContratDelegation($_GET['Id'], array());
            $contenu = $cd->editWithoutCase();
            break;

        /**
         * Page par défaut
         *
         */
        default :
            $debut_semaine = debutsem(date('Y'), date('m'), date('d'));
            $fin_semaine = finsem(date('Y'), date('m'), date('d'));
            $contenu .= Version::getCurrentVersion()->getMessage();
            $contenu .= '<br />
			<fieldset>
				<legend>' . MY_WEEK_ODM . '</legend><br />
                                <input id="user" name="user" type="hidden" value="' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '" />
                                <div id="page">
                                    ' . OrdreMission::search('', $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, '', $debut_semaine, $fin_semaine, '', '', '') . '
                                </div>
			</fieldset><br /><br />';
    }
} catch (AGCException $e) {
    header('location: ../public/index.php?url=' . urlencode($_SERVER['REQUEST_URI']));
}

/**
 * Inclusion du HTML
 */
require_once $squelette;
?>