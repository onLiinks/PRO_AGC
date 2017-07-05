<?php

header('Content-Type:text/html; charset=iso-8859-1');

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
$zone = ZONE_PARTAGE;
$menuDroit = MENU_DROIT;
$menuGauche = MENU_GAUCHE;

/**
 * Initialisation de variables dépendant de la zone 
 */
$squelette = SOURCE_HTML;
if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}

if (AUTH_TYPE == 'SSO') {
    $as = new SimpleSAML_Auth_Simple('default-sp');
    if (!$as->isAuthenticated()) {
        $as->login();
    }
    $_SESSION[SESSION_PREFIX.'logged']->initializeUser();
}

if ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur != '') {
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
                if($_POST['class'] == 'Annonce')
                    $url = '../rh/index.php?a=consulter' . $_POST['class'];
                header('location: ' . $url . '');
            } else {
                $contenu = $class->form();
            }
            break;

        case 'supprimer':
            $class = new $_GET['class']($_GET['Id'], array());
            $class->delete();
            if($_GET['class'] != 'OrdreMission')
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            break;

        case 'modifier':
        case 'creer':
            $titre = $_GET['class'];
            if ($_GET['Id']) {
                $titre .= ' n°' . (int) $_GET['Id'];
            }
            $class = new $_GET['class']($_GET['Id'], array());
            $contenu = $class->form();
            break;

        case 'archiver':
            $class = new $_GET['class']($_GET['Id'], '');
            $class->archive();
            ?>
            <script type='text/javascript'>
                alert("<?php echo SUCCESS_ARCHIV; ?>");
                history.go(-1);
            </script>
            <?php
            break;

        case 'desarchiver':
            $class = new $_GET['class']($_GET['Id'], '');
            $class->unarchive();
            ?>
            <script type='text/javascript'>
                alert("<?php echo SUCCESS_UNARCHIV; ?>");
                history.go(-1);
            </script>
            <?php
            break;

        case 'enregistrer_candidature' :
            $ressource = RessourceFactory::create('CAN_AGC', $_POST['Id_ressource'], $_POST);
            $ressource->save();
            $_POST['Id_ressource'] = $_SESSION['ressource'];
            $nom = htmlscperso(strtoupper(withoutAccent(str_replace("'", "", $_POST['nom_ressource']))), ENT_QUOTES);
            $prenom = htmlscperso(formatPrenom(withoutAccent(str_replace("'", "", $_POST['prenom_ressource']))), ENT_QUOTES);
            $upload = new HTTP_Upload('fr');
            $files = $upload->getFiles();
            foreach ($files as $file) {
                if (PEAR::isError($file)) {
                    die($file->getMessage());
                }
                if ($file->isValid()) {
                    $file->setName('real');
                    if ($file->getProp('form_name') == 'cv') {
                        $lien = stripslashes('CV_' . $nom . '_' . $prenom . '_' . DATEFR . '.' . $file->upload['ext']);
                        $file->upload['name'] = $lien;
                        $_POST['lien_cv'] = $lien;
                        $dest_dir = CV_DIR;
                    }
                    if ($file->getProp('form_name') == 'cvp') {
                        $lien = stripslashes('CVP_' . $nom . '_' . $prenom . '_' . DATEFR . '.' . $file->upload['ext']);
                        $file->upload['name'] = $lien;
                        $_POST['lien_cvp'] = $lien;
                        $dest_dir = CV_DIR;
                    }
                    if ($file->getProp('form_name') == 'lm') {
                        $lien = stripslashes('LM_' . $nom . '_' . $prenom . '_' . DATEFR . '.' . $file->upload['ext']);
                        $file->upload['name'] = $lien;
                        $_POST['lien_lm'] = $lien;
                        $dest_dir = LM_DIR;
                    }

                    $dest_name = $file->moveTo($dest_dir);

                    if (PEAR::isError($dest_name)) {
                        die($dest_name->getMessage());
                    }
                } elseif ($file->isMissing()) {
                    //echo "No file selected\n";
                } elseif ($file->isError()) {
                    //echo $file->errorMsg() . "\n";
                }
            }
            $candidature = new Candidature($_POST['Id'], $_POST);
            $candidature->save();

            if($_POST['th'] == 1 && in_array($_POST['Id_etat'], array(8, 9))) {
                $candidature->sendDisabledHiringMail();
            }
            
            if ($_POST['staff'] == 1 && in_array($_POST['Id_etat'], array(8, 9))) {
                ?>
                <script>
                    if(confirm('Envoyer un mail au DRH pour finaliser la création du contrat délégation ?')) {
                        location.replace('index.php?a=envoyerMailEmbaucheStaff&Id=<?php echo $_SESSION['candidature']; ?>');
                    } else {
                        location.replace('index.php?a=consulterCandidature');
                    }
                </script>
                <?php
            }
            ?>
            <script>
                alert("<?php echo CANDIDATE_SAVED; ?>"); 
                if(confirm('Voulez-vous aller sur la feuille d\'entretien ?')) {
                    location.replace('index.php?a=creer_entretien&Id_candidature=<?php echo $_SESSION["candidature"]; ?>');
                } else {
                    location.replace('index.php?a=consulterCandidature');
                }
            </script>
            <?php
            break;

        case 'envoyerMailEmbaucheStaff':
            $candidature = new Candidature($_GET['Id'], array());
            $candidature->sendStaffHiringMail();
            $candidature->sendStafffHiringADVMail();
            ?>
            <script type='text/javascript'>
                alert("Votre e-mail a été correctement envoyé aux Services RH et ADV");
                location.replace('index.php?a=consulterCandidature');
            </script>
            <?php
            break;

        case 'enregistrer_demande_changement':
            $nb_chgt = count($_POST['nouveau']);
            $i = 0;
            while ($i < $nb_chgt) {
                if ($_POST['nouveau'][$i] != '' && $_POST['ancien'][$i] != $_POST['nouveau'][$i]) {
                    $_INFO['Id_ressource'] = $_POST['Id_ressource'];
                    $_INFO['type_ressource'] = $_POST['type_ressource'];
                    $_INFO['libelle'] = $_POST['libelle'][$i];
                    $_INFO['ancien'] = $_POST['ancien'][$i];
                    $_INFO['nouveau'] = $_POST['nouveau'][$i];
                    $_INFO['date_souhaite'] = $_POST['date_souhaite'][$i];
                    $_INFO['valide_par'] = $_POST['valide_par'][$i];
                    $_INFO['date_validation'] = $_POST['date_validation'][$i];
                    $_INFO['integre_par'] = $_POST['integre_par'][$i];
                    $_INFO['date_integration'] = $_POST['date_integration'][$i];
                    $_INFO['commentaire'] = $_POST['commentaire'][$i];
                    $dc = new DemandeChangement($_POST['Id'][$i], $_INFO);
                    $dc->save();
                }
                ++$i;
            }
            header('location: index.php?a=consulterDemandeChangement');
            break;

        case 'afficherCandidature':
            $candidature = new Candidature($_GET['Id_candidature'], array());
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight($candidature->staff, $candidature->Id_etat)) {
                $contenu = $candidature->consultation();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'consulterAnnonce':
            $titre = ANNONCE;
            $filtre = Annonce::searchForm();
            $contenu = '<div id="pageAnnonce"></div><script>afficherAnnonce()</script>';
            break;
        
        case 'majAnnonce':
            $annonce = new Annonce($_GET['Id'], null);
            $annonce->date_modification = date("Y-m-d", strtotime($annonce->date_modification . ' +1 month'));
            $annonce->save();
            $titre = ANNONCE;
            $filtre = Annonce::searchForm();
            $contenu = '<div id="pageAnnonce"></div><script>afficherAnnonce()
                showInformationMessage(\'Votre annonce a été renouvelée pour 1 mois.\', 5000);</script>';
            break;

        case 'modifCvweb':
            $squelette = DEFAULT_HTML;
            $contenu = Candidature::cvWebHistoryModification($_GET['Id_candidature']);
            break;

        case 'postulationCvweb':
            $squelette = DEFAULT_HTML;
            $contenu = Candidature::postulation($_GET['Id_cvweb']);
            break;

        case 'consulterCandidature':
            $titre = APPLICANT;
            $filtre = Candidature::searchForm();
            $contenu = '<div id="pageCandidature"></div><script>afficherCandidature()</script>';
            break;

        case 'consulterDemandeChangement':
            $titre = ASKCHANGE;
            $filtre = DemandeChangement::searchForm();
            $contenu = '<div id="page"></div><script>afficherDemandeChangement()</script>';
            break;

        case 'creer_entretien':
            $candidature = new Candidature($_GET['Id_candidature'], array());
            if (!$candidature->Id_entretien) {
                $entretien = new Entretien('', $_GET);
            } else {
                $entretien = new Entretien($candidature->Id_entretien, array());
            }
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight($candidature->staff, $candidature->Id_etat)) {
                $titre = 'FEUILLE D\'ENTRETIEN CANDIDAT n° ' . (int) $_GET['Id_candidature'];
                $contenu = $entretien->form();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'enregistrer_entretien' :
            $entretien = new Entretien($_POST['Id'], $_POST);
            $entretien->save();
?>
            <script>
                alert("<?php echo MEETING_SAVED; ?>");
                location.replace('index.php?a=consulterCandidature');
            </script>
<?php
            break;

            case 'enregistrerLieuxPrestation' :
                $LieuxPrestation = new LieuxPrestation($_POST['Id'], $_POST);

                if ($LieuxPrestation->check() !== '') {
                    $contenu = $LieuxPrestation->form();
                }
                else {
                  $LieuxPrestation->save();
?>
                  <script>
                    alert("<?php echo LIEU_PRESTATION_SAVED; ?>");
                    location.replace('index.php?a=consulterLieuxPrestation');
                  </script>
<?php
            }
            break;

        case 'modifierLieuxPrestation':
            $titre = 'Modifier / Ajouter lieu de prestation';
            $squelette = DEFAULT_HTML;
            $LieuxPrestation = new LieuxPrestation($_GET['Id'], array());
            $contenu = $LieuxPrestation->form();
            break;

        case 'consulterLieuxPrestation':
            $titre = LIEUX_PRESTATION;
            $filtre = LieuxPrestation::SearchForm();
            $contenu = '<div id="page">'.LieuxPrestation::search($_GET['libelle'], $_GET['id_type_lieux_prestation'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction'])).'</div>';
            break;

        case 'consulterUtilisateur':
            $titre = USERS;
            $contenu = Utilisateur::search();
            break;

        case 'consulterNews':
            $titre = APP_UPDATE;
            $version = new Version($_GET['version']);
            $contenu = $version->getMessage(true);
            break;

        case 'consulterAgence':
            $titre = AGENCES;
            $contenu = Agence::search();
            break;

        case 'consulterRefPonderation':
            $titre = HELP_WEIGHTING;
            break;

        case 'consulterAide':
            $contenu = Aide::displayHelpWeighting();
            $titre = HELP;
            $contenu = Aide::display();
            break;

        case 'rechercheCV':
            $_SESSION['mot'] = $_GET['mot'];
            $titre = CV_SEARCH;
            $filtre = Candidature::cvSearchForm();
            if ($_SESSION['mot']) {
                $contenu = Candidature::cvSearch($_GET['mot']);
            } else {
                $contenu = '';
            }
            break;

        case 'exportCD':
            $titre = CONTRAT_DELEGATION;
            $filtre = ContratDelegation::searchExportForm();
            $contenu = '<div id="page"></div><script>afficherExportCD()</script>';
            break;
            
        case 'consulterCD':
            if ($_GET['Id_cd']) {
                $_SESSION['filtre']['Id_cd'] = $_GET['Id_cd'];
            }
            if ($_GET['Id_affaire_comp']) {
                $_SESSION['filtre']['Id_affaire_comp'] = str_replace('%A0', '', urlencode($_GET['Id_affaire_comp']));
            }
            if ($_GET['archive']) {
                $_SESSION['filtre']['archive'] = $_GET['archive'];
            }
            if ($_GET['Id_ressource']) {
                $_SESSION['filtre']['Id_ressource'] = $_GET['Id_ressource'];
            }
            $titre = CONTRAT_DELEGATION;
            $filtre = ContratDelegation::searchForm();
            $contenu = '<div id="page"></div><script>afficherCD()</script>';
            break;
            
            
        case 'consulterCollabSansCD':
            if ($_GET['date']) {
                $_SESSION['filtre']['date'] = $_GET['date'];
            }
            if ($_GET['Id_agence']) {
                $_SESSION['filtre']['Id_agence'] = $_GET['Id_agence'];
            }
            $titre = COLLAB_SANS_CONTRAT_DELEGATION;
            $filtre = ContratDelegation::getCollabWithoutContratDelegForm($_SESSION['filtre']['date'], $_SESSION['filtre']['Id_agence']);
            $contenu = '<div id="page"></div><script>afficherCollabSansCD()</script>';
            break;
            
        case 'consulterContratDelegWorkTime':
            if ($_GET['Id_cd']) {
                $_SESSION['filtre']['Id_cd'] = $_GET['Id_cd'];
            }
            if ($_GET['Id_affaire']) {
                $_SESSION['filtre']['Id_affaire'] = $_GET['Id_affaire'];
            }
            if ($_GET['Id_createur']) {
                $_SESSION['filtre']['Id_createur'] = $_GET['Id_createur'];
            }
            if ($_GET['Id_ressource']) {
                $_SESSION['filtre']['Id_ressource'] = $_GET['Id_ressource'];
            }
            if ($_GET['month']) {
                $_SESSION['filtre']['month'] = $_GET['month'];
            }
            if ($_GET['Id_agence']) {
                $_SESSION['filtre']['year'] = $_GET['year'];
            }
            $titre = CONTRAT_DELEGATION_WORK_TIME;
            $filtre = ContratDelegation::getContratDelegWorkTimeForm($_SESSION['filtre']['Id_cd'], $_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['Id_createur'], $_SESSION['filtre']['Id_ressource'],$_SESSION['filtre']['Id_agence'], $_SESSION['filtre']['month'], $_SESSION['filtre']['year']);
            $contenu = '<div id="page"></div><script>afficherContratDelegWorkTime()</script>';
            break;

		case 'consulterRefacturationCD':
            if ($_GET['Id_cd']) {
                $_SESSION['filtre']['Id_cd'] = $_GET['Id_cd'];
            }
            if ($_GET['Id_ressource']) {
                $_SESSION['filtre']['Id_ressource'] = $_GET['Id_ressource'];
            }
            $titre = 'REFACTURATION '.CONTRAT_DELEGATION;
            $filtre = ContratDelegation::refacturationForm();
            $contenu = '<div id="page"></div><script>afficherRefacturationCD()</script>';
            break;

        case 'modifierContratDelegation':
            $titre = 'Contrat Délégation n° ' . (int) $_GET['Id'];
            $squelette = DEFAULT_HTML;
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->form();
            break;

        case 'editerContratDelegation':
            $cd = new ContratDelegation($_GET['Id'], array());
            $contenu = $cd->edit();
            break;

        case 'modifierContratDelegationHorsAffaire':
            $titre = 'Contrat Délégation Hors Affaire n° ' . (int) $_GET['Id'];
            $squelette = DEFAULT_HTML;
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->withoutCaseForm();
            break;

        case 'editerContratDelegationHorsAffaire':
            $cd = new ContratDelegation($_GET['Id'], array());
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight($cd->embauche_staff, 8)) {
                $contenu = $cd->editWithoutCase();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'dupliquerCD':
            $cd = new ContratDelegation($_GET['Id'], array());
            $cd->duplicate();
            ?>
            <script type='text/javascript'>
                alert("<?php echo CD_DUPLICATED; ?>");
                location.replace('index.php?a=consulterCD');
            </script>
            <?php
            break;

        case 'modifierBlocNotes':
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, $_POST);
            $titre = MY_NOTE;
            $contenu = $utilisateur->noteBook();
            break;

        case 'enregistrerBlocNotes':
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, $_POST);
            $utilisateur->saveNoteBook();
            header('location: index.php');
            break;

        // Edition des fiches candidats en pdf    
        case 'editerCandidat':
            $candidature = new Candidature($_GET['Id_candidature'], array());
            $utilisateur = new Utilisateur($_SESSION['logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight($candidature->staff, $candidature->Id_etat)) {
                $contenu = $candidature->edit();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'ouvrirCV':
            $file = $_GET['cv'];
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                exit;
            } else {
                $contenu = UNAVAILABLE_DOC;
            }
            break;

        ########  CAS CONCERNANT LES DEMANDES DE RESSOURCE  ########

        case 'demander_ressource':
            $titre = ASKRESOURCE;
            if (isset($_GET['Id_affaire'])) {
                $squelette = DEFAULT_HTML;
                if(is_numeric($_GET['Id_affaire']))
                    $_GET['Id_affaire'] = convertSalesforceId($_GET['Id_affaire']);
                $dr = new DemandeRessource(0, array());
                $contenu .= $dr->form();
            } else {
                $dr = new DemandeRessource(0, array());
                $contenu .= $dr->form();
            }
            break;

        case 'envoyer_demande_ressource':
            $dates = array();
            $id_candidats = array();
            $id_cr = array();
            $commentaires = array();

            $keys = array_keys($_POST);
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 12) == 'dateCandidat') {
                    $dates[] = $_POST[$key];
                }
                if (substr($key, 0, 8) == 'candidat') {
                    $id_candidats[] = $_POST[$key];
                }
                if (substr($key, 0, 10) == 'crCandidat') {
                    $id_cr[] = $_POST[$key];
                }
                if (substr($key, 0, 19) == 'commentaireCandidat') {
                    $commentaires[] = $_POST[$key];
                }
            }
            $_POST['dateCandidat'] = $dates;
            $_POST['candidat'] = $id_candidats;
            $_POST['crCandidat'] = $id_cr;
            $_POST['commentaireCandidat'] = $commentaires;
            $dr = $demandeRessource = new DemandeRessource($_POST['Id_demande_ressource'], array());
            $demandeRessource = new DemandeRessource($_POST['Id_demande_ressource'], $_POST);

            if ($dr->statut == $_POST['statut'])
                $demandeRessource->save();
            else
                $demandeRessource->save(1);
            header('location: index.php?a=consulterDemandeRessource');
            break;

        case 'dupliquerDemandeRessource':
            $dr = new DemandeRessource($_GET['Id'], array());
            $dr->duplicate($_GET['dupliquerStatuts'], $_GET['dupliquerCandidats']);
            break;

        // Edition des fiches de demande de recrutement en pdf 
        case 'editerCandidatDemandeRessource':
            $dr = new DemandeRessource($_GET['Id_demande_ressource'], array());
            $dr->editRecruitmentRequest();
            break;

        case 'consulterDemandeRessource':
            $titre = RESOURCES_ASKING;
            if ($_GET['Id_affaire']) {
                $_SESSION['filtre']['Id_affaire_demande'] = $_GET['Id_affaire'];
            }
            if ($_GET['Id_affaire_comp']) {
                $_SESSION['filtre']['Id_affaire_demande_comp'] = str_replace('%A0', '', urlencode($_GET['Id_affaire_comp']));
            }
            $filtre = DemandeRessource::searchForm();
            $contenu = '<div id="pageDemandeRessource"></div><script>afficherDemandeRessource({ page: 1, sort: []})</script>';
            break;

        case 'editerDemandesRessource':
            DemandeRessource::edit($_GET['demandes']);
            break;

        case 'commentPerWeekForm':
            $contenu = Affaire::commentPerWeekForm($_GET['Id_affaire'], $_GET['year'], $_GET['week']);
            break;

        case 'saveCommentPerWeek':
            Affaire::saveCommentPerWeek($_POST['Id_affaire'], $_POST['year'], $_POST['week'], $_POST['comment'], $_POST['weighting_pct']);
            $contenu = 'Les informations ont été correctement enregistrées';
            break;

        case 'consulterAnomalie':
            $contenu = '<h2>LISTES DES ANOMALIES</h2><br />
			' . Script::checkDatePerResource() . '<br /><br />
			' . Script::checkWorkingDaysNumberPerResource() . '<br /><br />
			' . Script::checkStatusCase() . '<br /><br />
			' . Script::checkLinkCasesAGCCEGID() . '<br /><br />
			<iframe height="400px" width="100%" src="' . URL_LINK_REPORT . '?%2fAGC%2fAGC+National%2fAffaires+CEGID+Sans+Code+AGC&rs:Command=Render"></iframe>
			<br /><br />
		    <iframe height="400px" width="100%" src="' . URL_LINK_REPORT . '?%2fAGC%2fAGC+National%2fAffaires+Operationnelles+Termin%C3%A9es+Sans+CA&rs:Command=Render"></iframe>
			<br /><br />
			<iframe height="400px" width="100%" src="' . URL_LINK_REPORT . '?%2fAGC%2fAGC+National%2fAffaires+Op%C3%A9rationnelles+Termin%C3%A9es+Sans+Contrat+D%C3%A9l%C3%A9gation&rs:Command=Render"></iframe>';
            /*
              '.Script::checkComercialAffectationError().'<br /><br />
              '.Script::checkAgencyAffectationError().'<br /><br />
              '; */
            break;

        case 'clear':
            $_SESSION['logged']->clearSession();
            break;
        
        /**
         * Quitter une session
         */
        case 'quitter':
            $_SESSION['logged']->quit();
            break;

        default:
            $contenu = '<h2>Bienvenue sur l\'AGC</h2><br />';
            $contenu .= Version::getCurrentVersion()->getMessage();
    }
} else {
    if ($_GET['no_redirect']) {
        die('<a href="../public/?url=' . urlencode($_SERVER['REQUEST_URI']).'">Se connecter</a>');
    }
    header('location: ../public/?url=' . urlencode($_SERVER['REQUEST_URI']));
}

/**
 * Inclusion du HTML
 */
require_once $squelette;
?>
