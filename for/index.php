<?php
header('Content-Type:text/html; charset=iso-8859-1');
/**
  * Fichier index.php
  *
  * @author Frédérique Potet
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
$titre   = '';
$filtre  = '';
$contenu = '';
$zone    = ZONE_PARTAGE;


if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}

if ( $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur != '') {
	
	/**
	  * Fabrication de la page à envoyer au client
	  */
    switch ($_GET['a']) {
			
         // Archivage d'une session
		case 'supprSession' :
 	        $session = new Session($_GET['Id_session'],'');
			$liste   = $session->relatedCasesList();
			if ($liste != '') {
				//interdiction de supprimer une session si elle est associée à des affaires
				echo '<SCRIPT language="Javascript">
						alert ("Vous ne pouvez pas supprimer cette session car elle est encore associée aux affaires :\n\t\t'.$liste.'");
						location.replace(\'../com/index.php?a=consulterSession\');
					</script>';
			} else {
				$session->delete();
				echo '<SCRIPT language="Javascript">
						alert ("La session a été supprimée");
						location.replace(\'../com/index.php?a=consulterSession\');
					</script>';	
			}	
            break;
            
         // Archivage d'une session
		case 'archiverSession' :
 	        $session = new Session($_GET['Id_session'],'');
			$liste   = $session->relatedCasesList();
			if ($liste != '') {
				//interdiction de supprimer une session si elle est associée à des affaires
				echo '<SCRIPT language="Javascript">
						alert ("Vous ne pouvez pas archiver cette session car elle est encore associée aux affaires :\n\t\t'.$liste.'");
						location.replace(\'../com/index.php?a=consulterSession\');
					</script>';
			} else {
				$session->archive();
				echo '<SCRIPT language="Javascript">
						alert ("'.SUCCESS_ARCHIV.'");
						location.replace(\'../com/index.php?a=consulterSession\');
					</script>';	
			}	
            break;
			
		// Archivage d'une salle
		case 'archiverSalle' :
 	        $salle = new Salle($_GET['Id_salle'],'');
			$salle->archive();
			echo '<SCRIPT language="Javascript">
						alert ("'.SUCCESS_ARCHIV.'");
						location.replace(\'../com/index.php?a=consulterSalle\');
					</script>';
            break;

		// Archivage d'un formateur
		case 'archiverFormateur' :
 	        $formateur = new Formateur($_GET['Id_formateur'],'');
			$formateur->archive();
			echo '<script language="javascript">
						alert ("'.SUCCESS_ARCHIV.'");
						location.replace(\'../com/index.php?a=consulterFormateur\');
					</script>';
            break;
        
       		// Edition des documents de la session
		case 'edition' :				
			//création du nom du dossire pour le session : "date de debut_nom de la session"
			$session 		  = new Session ($_GET['id_session'], array());
			$planning_session = new PlanningSession ($session->Id_planning, array());
			$nom_session 	  = htmlscperso(formatageString($session->nom_session), ENT_QUOTES);
			$nomDossier 	  = DOC_SESSION_DIR.formatageDate($planning_session->dateDebut).'_'.$nom_session;
			//si le dossier n'existe pas, création du dossier avec les droits en ecriture
			if (!is_dir($nomDossier)) {
				mkdir($nomDossier, 0777);
			}				
			$anglais 		= ' ';
			//récupération du type du document demandé et appel de la fonction d'édition
			$editionSession = new EditionSession ($_GET['id_session'], array());
			
			switch ($_GET['type']) {			
				//Edition de la checklist d'une affaire
				case 'Checklist' :
					$editionSession->editionChecklist($nomDossier);
					break;
				
				//Edition des attestations de présence
				case 'Presence' :
					$editionSession->editionPresence($nomDossier);
					break;
		
				//Edition des attestations de stage
				case 'Attestation' :
					$editionSession->editionAttestation($nomDossier);
					break;
				
				//Edition des feuilles d'évaluation de la formation par les stagiaires
				case 'EvaluationStagaire' :
					$editionSession->editionEvaluationStagiaire($nomDossier);
					break;
				
				//Edition des feuilles d'évaluation de la formation par les stagiaires
				case 'EvaluationFormateur' :
					$editionSession->editionEvaluationFormateur($nomDossier);
					break;
									
				//Edition des feuilles de convocation pour les participants
				case 'Convocation' :
					$editionSession->editionConvocation($nomDossier);
					break;
				
				//Edition des conventions de formation pour les clients
				case 'Convention' :
					$editionSession->editionConvention($nomDossier);
					break;
				
				//Edition des bons de commande pour les clients
				case 'BDC' :
					$editionSession->editionBDC($nomDossier, $_GET['condition']);
					if ($_GET['condition'] == 2) {
						$check = ' checked ';
					}
					$anglais = ' (Conditions de ventes anglais 
								 <input type="checkbox" id="condition" name="condition" value="2" '.$check.'>)';
					break;
				
				//Edition des bons d'intervention pour les formateurs
				case 'BI' :
					$editionSession->editionBI($nomDossier);
					break;
				
				//Edition de la lettre de confirmation d'inscription à la session
				case 'Confirmation' :
					$editionSession->editionConfirmation($nomDossier);
					break;
			}
			$editionSession->save($_GET['id_doc']);
			//affichage de la date et l'heure de l'édition
			echo '<img src="../ui/images/valider.png"> Edité le '.date("d-m-y").' à '.date("H:i").'&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="button" onclick="edition(\''.$_GET['type'].'\', '.(int)$_GET['id_session'].', 1)" value="Re-Editer">'.
				  $anglais;
							
            break;		
    }
} else {
    header('location: ../public/?url='.urlencode($_SERVER['REQUEST_URI']));
}
?>