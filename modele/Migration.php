<?php
/**
  * Fichier Migration.php
  *
  * @author    Anthony Anne
  * @copyright    Proservia
  * @package    ProjetAGC
  */

/**
  * Déclaration de la classe Migration
  */
class Migration 
{

	/**
	  * Constructeur de la classe Migration
	  *
	  * Constructeur : initialiser suivant la présence ou non de l'identifiant
	  *
	  * @param int Valeur de l'identifiant de la candidature
	  * @param array Tableau passé en argument : tableau $_POST ici 
	  */
    public function __construct()
	{
		$db      = connecter();
		$requete = 'SELECT * FROM candidat_prosper1 ORDER BY nom2';
		$result  = $db->query($requete) or die ('SELECT * FROM candidat_prosper1 : '.mysql_error());
		while ($ligne = $result->fetchRow()) {
		    $tab = array();
			
			/*  GESTION DE l'ORIGINE */
			$tab['origine_ressource'] = 'Candidat';
			
			/*  GESTION DU PRENOM ET DU NOM */
			$nom            = str_replace("'", "", $ligne->nom2);
			$prenom         = str_replace("'", "", $ligne->prenom2);
			$ligne->nom2    = htmlscperso(strtoupper(withoutAccent($nom)), ENT_QUOTES);
			$ligne->prenom2 = htmlscperso(formatPrenom(withoutAccent($prenom)), ENT_QUOTES);
			
		    /*  GESTION DU PROFIL */
			
			$tab['independant'] = 0;
			$tab['stage'] = 0;
			
			if($ligne->profil2 == 1) {
			    $tab['Id_profil'] = 9;
			}
			elseif($ligne->profil2 == 2) {
			    $tab['Id_profil'] = 13;
			}
			elseif($ligne->profil2 == 3) {
			    $tab['Id_profil'] = 2;
			}
			elseif($ligne->profil2 == 4) {
			    $tab['Id_profil'] = 5;
			}
			elseif($tab['Id_profil'] == 5) {
			    $tab['Id_profil'] = 21;
			}
			elseif($ligne->profil2 == 6) {
			    $tab['Id_profil'] = 1;
			}
			elseif($ligne->profil2 == 7) {
			    $tab['Id_profil'] = '';
			}
			elseif($ligne->profil2 == 8) {
			    $tab['Id_profil'] = 7;
			}
			elseif($ligne->profil2 == 9) {
			    $tab['Id_profil'] = 1;
			}
			elseif($ligne->profil2 == 10) {
			    $tab['Id_profil'] = 9;
			}
			elseif($ligne->profil2 == 11) {
			    $tab['Id_profil'] = 8;
			}
			elseif($ligne->profil2 == 12) {
			    $tab['independant'] = 1;
			}
			elseif($ligne->profil2 == 13) {
			    $tab['Id_profil'] = 6;
			}
			elseif($ligne->profil2 == 14) {
			    $tab['Id_profil'] = 12;
			}
			elseif($ligne->profil2 == 15) {
			    $tab['Id_profil'] = 12;
			}
			elseif($ligne->profil2 == 16) {
			    $tab['Id_profil'] = 6;
			}
			elseif($ligne->profil2 == 17) {
			    $tab['Id_profil'] = 4;
			}
			elseif($ligne->profil2 == 18) {
			    $tab['Id_profil'] = 15;
			}
			elseif($ligne->profil2 == 19) {
			    $tab['Id_profil'] = 5;
			}
			elseif($ligne->profil2 == 20) {
			    $tab['Id_profil'] = 5;
			}
			elseif($ligne->profil2 == 21) {
			    $tab['Id_profil'] = 11;
			}
			elseif($ligne->profil2 == 22) {
			    $tab['Id_profil'] = 6;
			}
			elseif($ligne->profil2 == 23) {
			    $tab['stage'] = 1;
			}
			elseif($ligne->profil2 == 24) {
			    $tab['Id_profil'] = 12;
			}
			elseif($ligne->profil2 == 25) {
			    $tab['Id_profil'] = 2;
			}
			elseif($ligne->profil2 == 26) {
			    $tab['Id_profil'] = '';
			}
			elseif($ligne->profil2 == 27) {
			    $tab['Id_profil'] = 9;
			}
			
			/*  GESTION DU CREATEUR, DE LA DATE D'EMBAUCHE DE LA RESSOURCE */
			$requete2             = 'SELECT * FROM candidat_prosper2 WHERE Id_candidat="'.$ligne->id_candidat2.'"';
			$result2              = $db->query($requete2);
			$ligne2               = $result2->fetchRow();
			$tab['createur']      = self::formatCreateur($ligne2->createur);
			$tab['createur2']     = self::formatCreateur($ligne2->createur2);
			$tab['Id_recruteur']  = self::formatCreateur($ligne2->createur);
			$tab['date_embauche'] = DateMysqltoFr($ligne2->date_em);
			
			$tab['commentaire_com'] = $ligne2->commentaire_com;
						
			/* ENREGISTREMENT DE LA RESSOURCE */
			$tab['nom_ressource']          = $ligne->nom2;
			$tab['prenom_ressource']       = $ligne->prenom2;
			
			/*  GESTION DE LA CIVILITE */
			if($ligne->civilite2 == 'Monsieur') {
		        $tab['civilite_ressource'] = MISTER;
			}
			elseif($ligne->civilite2 == 'Mademoiselle') {
		        $tab['civilite_ressource'] = MISS;
			}
			elseif($ligne->civilite2 == 'Madame') {
		        $tab['civilite_ressource'] = MADAM;
			}
			
			$tab['adresse_ressource']      = $ligne->adresse2;
			$tab['cp_ressource']           = $ligne->code_postal2;
			$tab['ville_ressource']        = $ligne->ville2;
			$tab['tel_fixe_ressource']     = $ligne->tel_fixe2;
			$tab['tel_portable_ressource'] = $ligne->tel_portable2;
			$tab['mail_ressource']         = $ligne->mail2;
			$tab['date']                   = DateMysqltoFr($ligne->date2);
			
			$ressource = new Ressource('',$tab);
			$ressource->save();
			$tab['Id_ressource'] = $_SESSION['ressource'];
			
			/*  GESTION DE LA NATURE DES CANDIDATURES */
			if($ligne->nature2 == '-2095670057') {
			    $tab['Id_nature'] = 14;
			}
			elseif($ligne->nature2 == '-1625819612') {
			    $tab['Id_nature'] = 7;
			}
			elseif($ligne->nature2 == '-1169241284') {
			    $tab['Id_nature'] = 2;
			}
			elseif($ligne->nature2 == '-1117087266') {
			    $tab['Id_nature'] = 3;
			}
			elseif($ligne->nature2 == '6') {
			    $tab['Id_nature'] = 10;
			}
			elseif($ligne->nature2 == '1') {
			    $tab['Id_nature'] = 6;
			}
			elseif($ligne->nature2 == '15') {
			    $tab['Id_nature'] = 11;
			}
			elseif($ligne->nature2 == '498119038') {
			    $tab['Id_nature'] = 19;
			}
			elseif($ligne->nature2 == '823974545') {
			    $tab['Id_nature'] = 4;
			}
			elseif($ligne->nature2 == '1172513926') {
			    $tab['Id_nature'] = 9;
			}
			elseif($ligne->nature2 == '1203959752') {
			    $tab['Id_nature'] = 12;
			}
			elseif($ligne->nature2 == '1383395024') {
			    $tab['Id_nature'] = 5;
			}
			elseif($ligne->nature2 == '1597886351') {
			    $tab['Id_nature'] = 8;
			}
			elseif($ligne->nature2 == '1627809575') {
			    $tab['Id_nature'] = 1;
			}
			
			/*  GESTION DES COMMENTAIRES  */
			$tab['commentaire']  = '';
            $tab['commentaire'] .= 'Suite Entretien : '.$ligne2->suite_entretien.'<br /><hr /><br />';
		    $tab['commentaire'] .= 'Désignation Suivi : '.$ligne->etat_cdt.'<br /><hr /><br />';
			$tab['commentaire'] .= 'CV : '.mysql_real_escape_string($ligne->lien_cv2).'<br /><hr /><br />';
			$tab['commentaire'] .= 'CV PROSERVIA : '.mysql_real_escape_string($ligne->lien_cvp2).'<br /><hr /><br />';
			$tab['commentaire'] .= 'Commentaire Origine : '.$ligne->com_origine.'<br /><hr /><br />';
			$tab['commentaire'] .= 'Commentaire Suivi : '.$ligne->com_suivi.'<br /><hr /><br />';
			$tab['commentaire'] .= 'Commentaire Candidat : '.$ligne->com_cdt.'<br /><hr /><br />';
			
			
			/* ENREGISTREMENT DE L'ETAT DE LA CANDIDATURE */
			if($ligne2->suite_entretien == 'Candidat validé') {
			    $tab['Id_etat'] = 6;    
			}
			elseif($ligne2->suite_entretien == 'Pas validé') {
			    $tab['Id_etat'] = 7;    
			}
			
			/* ENREGISTREMENT DU CANDIDAT */
			$candidature = new Candidature('',$tab);
			$candidature->save();
			$Id_candidature = $_SESSION['candidature'];
			
			/* ENREGISTREMENT DANS LA TABLE HISTORIQUE_CANDIDATURE */
			/*  COMMENTER LA LIGNE UPDATE ETAT DANS HISTORIQUE CANDIDATURE */
			if($ligne2->dateentretien1) {
				$requete3 = 'INSERT INTO historique_candidature SET Id_candidature='.$Id_candidature.', Id_etat="4", date="'.$ligne2->dateentretien1.'", Id_utilisateur="'.$tab['createur'].'"';
			    $db->query($requete3) or die ('INSERT INTO historique_candidature 1 : '.mysql_error());
			}
			if($ligne2->dateentretien2) {
				$requete4 = 'INSERT INTO historique_candidature SET Id_candidature='.$Id_candidature.', Id_etat="5", date="'.$ligne2->dateentretien2.'", Id_utilisateur="'.$tab['createur2'].'"';
			    $db->query($requete4) or die ('INSERT INTO historique_candidature 2 : '.mysql_error());
			}
			
			$requete6 = 'SELECT * FROM candidat_prosper3 WHERE Id_candidat="'.$ligne->id_candidat2.'"';
			$result6  = $db->query($requete6);
			$ligne6   = $result6->fetchRow();
			
			
			/*  GESTION DE L'ETAT MATRIMONIAL */
			if($ligne6->etat_civil_cdt == 'mariée' || $ligne6->etat_civil_cdt == 'M' || $ligne6->etat_civil_cdt == 'Mariée' || $ligne6->etat_civil_cdt == 'Marié' || $ligne6->etat_civil_cdt == 'marié') {
			    $requete10 = 'UPDATE ressource SET Id_etat_matrimonial="1" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete10) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->etat_civil_cdt == 'C' || $ligne6->etat_civil_cdt == 'c' || $ligne6->etat_civil_cdt == 'célibataire' || $ligne6->etat_civil_cdt == 'Célibataire') {
			    $requete10 = 'UPDATE ressource SET Id_etat_matrimonial="3" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete10) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->etat_civil_cdt == 'vie maritale' || $ligne6->etat_civil_cdt == 'Vie maritale') {
			    $requete10 = 'UPDATE ressource SET Id_etat_matrimonial="4" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete10) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->etat_civil_cdt == 'divorcé') {
			    $requete10 = 'UPDATE ressource SET Id_etat_matrimonial="6" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete10) or die ('UPDATE ressource : '.mysql_error());
			}
			
			
			/* COMMENTAIRE SUR LES ATTENTES PROFESSIONELLES */
			$attente_pro  = $ligne->souhait_cdt.'<br /><hr /><br />';
			$attente_pro .= 'Année formation 1 : '.$ligne6->annee_f1.'<br />';
			$attente_pro .= 'Option formation 1 : '.$ligne6->option_formation1.'<br /><hr /><br />';
			$attente_pro .= 'Formation 2 : '.$ligne6->formation2_cdt.'<br />';
			$attente_pro .= 'Année formation 2 : '.$ligne6->annee_f2.'<br /><hr /><br />';
			$attente_pro .= 'Formation spécifique : '.$ligne6->formation_specifique.'<br /><hr /><br />';
			$attente_pro .= 'Disponibilité : '.$ligne6->dispo_cdt.'<br /><hr /><br />';
			$attente_pro .= 'Experience : '.$ligne6->exp_cdt.'<br /><hr /><br />';
			$attente_pro .= 'Langues : '.$ligne6->langue1_cdt.' '.$ligne6->langue2_cdt.'<br /><hr /><br />';
			$attente_pro .= 'Niveau Intervention : '.$ligne6->niveau_intervention.'<br /><hr /><br />';
			$attente_pro .= 'Prétentions : '.$ligne6->pretention;
			
			/* ENREGISTREMENT DANS LA TABLE ENTRETIEN */
			$requete5 = 'INSERT INTO entretien SET Id_entretien="", Id_candidature='.$Id_candidature.', Id_recruteur="'.$tab['createur'].'", createur="'.$tab['createur'].'", date_creation="'.DATETIME.'", commentaire_com="'.$ligne2->commentaire_com.'", attente_pro="'.$attente_pro.'"';
			$db->query($requete5) or die ('INSERT INTO entretien : '.mysql_error());
			$Id_entretien =  mysql_insert_id();

            /*  GESTION DE LA MOBILITE  */			
			if($ligne6->id_mobilite1 == '-2074090575') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-13"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-2063452622') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-2"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-17"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-1806687144') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-1770274801') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-13"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-1565783010') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-1"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-5"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-8"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-11"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
				$requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-16"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-1264000635') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-10"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-919646324') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-3"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-421753205') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '-392826768') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-6"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-7"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-19"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-21"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-7"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-15"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());		
			}
			elseif($ligne6->id_mobilite1 == '-265386116') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-18"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-20"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());		
			}
			elseif($ligne6->id_mobilite1 == '421749233') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-6"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '480318791') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-3"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-14"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());		
			}
			elseif($ligne6->id_mobilite1 == '620400560') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-11"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '850747748') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-1"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '1147270805') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-22"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-23"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '1199386575') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-2"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '1200592791') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-4"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '1215014662') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-12"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite1 == '1888776907') {
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-4"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete7 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-12"';
			    $db->query($requete7) or die ('INSERT INTO entretien_mobilite : '.mysql_error());		
			}
			
			
			/*  GESTION DE LA MOBILITE  SUITE */
			if($ligne6->id_mobilite2 == '-1693565788') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-12"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '-1523381245') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-11"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '-1133068962') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-2"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '-796803705') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-1"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '-200315062') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-4"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-12"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());	
			}
			elseif($ligne6->id_mobilite2 == '-95850342') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-3"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '1') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '2') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-6"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-7"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-19"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-21"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-7"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-15"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());	
			}
			elseif($ligne6->id_mobilite2 == '4') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-13"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '5') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-1"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-5"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-8"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-11"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
				$requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-16"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());	
			}
			elseif($ligne6->id_mobilite2 == '6') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-3"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-14"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());	
			}
			elseif($ligne6->id_mobilite2 == '7') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-18"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-20"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());		
			}
			elseif($ligne6->id_mobilite2 == '16515285') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-13"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '76358242') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-10"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '97043357') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="-4"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '411291272') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-6"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '903191228') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-22"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-23"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '1653676813') {
			    $requete5 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-2"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1-17"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite : '.mysql_error());
			}
			elseif($ligne6->id_mobilite2 == '1891464057') {
			    $requete8 = 'INSERT INTO entretien_mobilite SET Id_entretien='.$Id_entretien.', Id_mobilite="1"';
			    $db->query($requete8) or die ('INSERT INTO entretien_mobilite 2 : '.mysql_error());
			}
			
		    /* GESTION DU CURSUS */
			if($ligne6->num_formation1 == '3') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="12" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '2') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="2" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '5') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="3" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '6') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="4" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '4') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="5" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '7') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="10" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '8') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="11" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '9') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="15" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '10') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="6" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
			elseif($ligne6->num_formation1 == '11') {
			    $requete9 = 'UPDATE ressource SET Id_cursus="14" WHERE Id_ressource='.$tab['Id_ressource'];
			    $db->query($requete9) or die ('UPDATE ressource : '.mysql_error());
			}
 	    }
	    echo 'Migration de PROSPER réussie !';
	}
	
	/**
	  * Fonction qui retourne le login du createur en fonction de son prenom nom
	  *
	  * @param Trigramme de la personne dans PROSPER
	  *
	  * @return string
	  */
    public static function formatCreateur($trig)
	{
		if($trig == 'AC') {
		    $login = 'alexandra.canaff';
		} 
		elseif($trig == 'AL') {
		    $login = 'alexandre.laurendot';
		}
		elseif($trig == 'LC') {
		    $login = 'annelaure.couturier';
		}
		elseif($trig == 'AS') {
		    $login = 'annie.soyez';
		}
		elseif($trig == 'AB') {
		    $login = 'aurelie.barlier';
		}
		elseif($trig == 'AO') {
		    $login = 'aurelie.ortega';
		}
		elseif($trig == 'BD') {
		    $login = 'baptiste.depaquy';
		}
		elseif($trig == 'BF') {
		    $login = 'beatrice.fichera';
		}
		elseif($trig == 'BP') {
		    $login = 'bruno.petorelli';
		}
		elseif($trig == 'CS') {
		    $login = 'caroline.secq';
		}
		elseif($trig == 'CC') {
		    $login = 'cathy.cuccia';
		}
		elseif($trig == 'CA') {
		    $login = 'cecile.carre';
		}
		elseif($trig == 'DR') {
		    $login = 'daniel.rigaut';
		}
		elseif($trig == 'DC') {
		    $login = 'david.courtois';
		}
		elseif($trig == 'DI') {
		    $login = 'david.isabet';
		}
		elseif($trig == 'DL') {
		    $login = 'david.latimier';
		}
		elseif($trig == 'DH') {
		    $login = 'delphine.hennebelle';
		}
		elseif($trig == 'EG') {
		    $login = 'emmanuel.grall';
		}
		elseif($trig == 'EB') {
		    $login = 'eric.bibolet';
		}
		elseif($trig == 'EP') {
		    $login = 'eric.palazy';
		}
		elseif($trig == 'EQ') {
		    $login = 'estelle.quinquis';
		}
		elseif($trig == 'FH') {
		    $login = 'florence.helleu';
		}
		elseif($trig == 'GJ') {
		    $login = 'gautier.jacquemont';
		}
		elseif($trig == 'GR') {
		    $login = 'gregory.robert';
		}
		elseif($trig == 'IL') {
		    $login = 'ivan.lamouret';
		}
		elseif($trig == 'JA') {
		    $login = 'jeanmarc.alpago';
		}
		elseif($trig == 'JV') {
		    $login = 'jerome.vittu';
		}
		elseif($trig == 'JS') {
		    $login = 'johan.schneider';
		}
		elseif($trig == 'JR') {
		    $login = 'julie.richaudeau';
		}
		elseif($trig == 'LB') {
		    $login = 'loic.boisumeau';
		}
		elseif($trig == 'MS') {
		    $login = 'loic.boisumeau';
		}
		elseif($trig == 'MB') {
		    $login = 'mathieu.brule';
		}
		elseif($trig == 'ML') {
		    $login = 'melanie.lages';
		}
		elseif($trig == 'NA') {
		    $login = 'nicolas.cardon';
		}
		elseif($trig == 'OL') {
		    $login = 'olivier.lagree';
		}
		elseif($trig == 'OS') {
		    $login = 'olivier.savier';
		}
		elseif($trig == 'OP') {
		    $login = 'oswald.petit';
		}
		elseif($trig == 'PW') {
		    $login = 'patrick.wiscart';
		}
		elseif($trig == 'PH') {
		    $login = 'phlippe.harand';
		}
		elseif($trig == 'RB') {
		    $login = 'remy.besnard';
		}
		elseif($trig == 'RS') {
		    $login = 'roberto.sanchez';
		}
		elseif($trig == 'MS') {
		    $login = 'marieothilie.stevner';
		}		
		elseif($trig == 'SM') {
		    $login = 'sandrine.mounissami';
		}
		elseif($trig == 'TB') {
		    $login = 'thomas.bertin';
		}
		elseif($trig == 'VB') {
		    $login = 'valerie.bruhat';
		}
		elseif($trig == 'VF') {
		    $login = 'veronique.fontana';
		}
		elseif($trig == 'VA') {
		    $login = 'vincent.barre';
		}		
		return $login;
	}
}
