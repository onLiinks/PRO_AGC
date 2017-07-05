<?php
#########################################################################################################
#																										#
# Script permettant de mettre à jour la base de données	pour intégrer les affaires du pôle formation	#
#																										#
#########################################################################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 

//Création de la table session contenant les informations principales d'une session
mysql_query("
CREATE TABLE `session` (
	Id_session INT NOT NULL AUTO_INCREMENT,
	nom_session VARCHAR(255) NOT NULL,
	createur VARCHAR(255) NOT NULL,
	archive int(1) NOT NULL default 0,
	type int(1) NULL,
	description TEXT NULL,
	date_creation DATE NULL,
	date_modification DATE NULL,
	nb_inscrits int NULL,
	code_postal VARCHAR(255) NULL,
	ville VARCHAR(255) NULL,
	Id_intitule int(2) NULL,
	PRIMARY KEY ( `Id_session` ) 
)");

//Création de la table proposition_session contenant les informations commerciales de la session
mysql_query("
CREATE TABLE `proposition_session` (
	Id_propSession INT NOT NULL AUTO_INCREMENT,
	Id_session INT NULL,
	coutFormateurJour double NULL,
	coutFormateur double NULL,
	coutSalleJour double NULL,
	coutSalle double NULL,
	coutSupportUnitaire double NULL,
	coutSupport double NULL,
	coutSupForm double NULL,
	typeAutreFrais TEXT NULL,
	autreFrais double NULL,
	ca double NULL,
	charge double NULL,
	marge double NULL,
	PRIMARY KEY ( `Id_propSession` ) 
)");

//Création de la table planning_session contenant les informations sur les dates et la durée de la session
mysql_query("
CREATE TABLE `planning_session` (
	Id_plan_session INT NOT NULL AUTO_INCREMENT,
	Id_session INT NULL,
	nb_jour INT NULL, 
	dateDebut DATE NULL,
	dateFin DATE NULL,
	PRIMARY KEY ( `Id_plan_session` ) 
)");

//Création de la table periode_session contenant les périodes intermédiaires du planning de la session
mysql_query("
CREATE TABLE `periode_session` (
	Id_periode_session INT NOT NULL AUTO_INCREMENT,
	Id_planning_session INT NOT NULL,
	periode_debut date NULL,
	periode_fin date NULL,
	PRIMARY KEY ( `Id_periode_session` ) 
)");

//Création de la table date contenant les dates ponctuelles du planning de la session
mysql_query("
CREATE TABLE `date` (
	Id_date INT NOT NULL AUTO_INCREMENT,
	date DATE NULL,
	PRIMARY KEY ( `Id_date` ) 
)");

//Création de la table planning_date contenant les associations entre la table planning_session et la table date
mysql_query("
CREATE TABLE `planning_date` (
	Id_date INT NOT NULL,
	Id_plan_session INT NOT NULL,
	PRIMARY KEY ( Id_date, Id_plan_session ) 
)");



//Création de la table formateur contenant les informations concernant les formateurs
mysql_query("
CREATE TABLE `formateur` (
	Id_formateur INT NOT NULL AUTO_INCREMENT,
	nom VARCHAR(255) NULL,
	prenom VARCHAR(255) NULL,
	tel_fixe VARCHAR(255) NULL,
	tel_portable VARCHAR(255) NULL,
	mail VARCHAR(255) NULL,
	page_web VARCHAR(255) NULL,
	societe VARCHAR(255) NULL,
	competence TEXT NULL,
	genre INT(1) NULL,
	Id_statut INT(3) NULL,
	salaire double NULL,
	archive int(1) NOT NULL default 0,
	PRIMARY KEY ( `Id_formateur` ) 
)"); 

//Création de la table salle contenant les informations concernant les salles
mysql_query("
CREATE TABLE `salle` (
	Id_salle INT NOT NULL AUTO_INCREMENT,
	nom VARCHAR(255) NULL,
	lieu VARCHAR(255) NULL,
	adresse VARCHAR(255) NULL,
	code_postal VARCHAR(255) NULL,
	ville VARCHAR(255) NULL,
	tel VARCHAR(255) NULL,
	descriptif TEXT NULL,
	acces VARCHAR(255) NULL,
	prix double NULL,
	archive int(1) NOT NULL default 0,
	PRIMARY KEY ( `Id_salle` ) 
)");

//Création de la table formateur_salle contenant les associations entre la session, le formateur et la salle
mysql_query("
CREATE TABLE `formateur_salle` (
	Id_session INT NOT NULL,
	Id_formateur INT NOT NULL DEFAULT 0,
	Id_salle INT NOT NULL DEFAULT 0,
	PRIMARY KEY ( `Id_session`,`Id_formateur`,`Id_salle`  ) 
)");

//Création de la table proposition_formation contenant les informations commerciales spécifiques au pôle formation d'une affaire 
// complément de la table proposition
mysql_query("
CREATE TABLE `proposition_formation` (
	Id_propFormation INT NOT NULL AUTO_INCREMENT,
	Id_proposition int NULL,
	nb_inscrit int NULL,
	lien_bdc VARCHAR(255) NULL,
	PRIMARY KEY ( `Id_propFormation` ) 
)");

//Ajout du pourcentage de reussite à la table analyse_risque
mysql_query("ALTER TABLE `analyse_risque` add column pourcentage_reussite double NULL");

//Ajout d'un identifiant de session à la table affaire
mysql_query("ALTER TABLE `affaire` add column Id_session INT(11) NULL");

//Création de la table logistique contenant les informations de la checklist (lignes cochées et remarques associées)
mysql_query("
CREATE TABLE `logistique` (
	Id_session INT NOT NULL,
	Id_checklist INT NOT NULL,
	valeur int(1) NULL,
	rmq varchar(255) NULL,
	PRIMARY KEY ( Id_session, Id_checklist ) 
)");

//Création de la table checklist contenant les libéllés de la checklist
mysql_query("
CREATE TABLE `checklist` (
	Id_checklist INT NOT NULL AUTO_INCREMENT,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_checklist` ) 
)");

//Insertion des libéllés de la checklist
mysql_query("
INSERT into checklist values (1,'Réservation de la salle'),(2,'Réservation du consultant/formateur'),
(3,'Recherche des supports de cours'),(4,'Etablissement de la proposition'),
(5,'Etablissement du bon de commande'),(6,'Mise à jour de CEGID'),
(7,'Réception du bon de commande client'),(8,'Mise à jour récurrente'),
(9,'Mise à jour planning salle'),(10,'Validation réservation de la salle (bon de commande)'),
(11,'Validation du consultant-formateur (bon de commande)'),(12,'Etablissement fiche de préparation de la salle(GLPI)'),
(13,'Commande supports de cours'),(14,'Mise à jour de l\'AGC'),
(15,'Envoie des convocations'),(16,'Etablissement feuille(s) de présence(s)'),
(17,'Etablissment des fiches \"Evaluations stagiaires\"'),(18,'Etablissement des fiches \"Evaluation formateur\"'),
(19,'Etablissement des \"Attestations de stage\"'),(20,'Réception des support de cours'),
(21,'Préparation du \"dossier de stage formateur\"'),(22,'Remise du \"dossier de stage formateur\" au formateur'),
(23,'Retour du \"dossier de stage formateur\"'),(24,'Déclenchement facturation et mise à jour récurrent'),
(25,'Classement dossier et mise à jour du bilan pédagogique')	
");					

//Création de la table statut_formateur contenant les libéllés des statuts des formateurs
mysql_query("
CREATE TABLE `statut_formateur` (
	Id_statut INT NOT NULL AUTO_INCREMENT,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_statut` ) 
)");

//Insertion des libéllés des statuts des formateurs
mysql_query("
INSERT into statut_formateur values (1,'Travailleur indépendant'),(2,'Salarié en CDI'),
(3,'Salarié en CDD'),(4,'Formateur occasionnel salariée'),
(5,'Bénévole'),(6,'Partenaire')
");

//Création de la table participant contenant les inscriptions à une session pour l'affaire
mysql_query("
CREATE TABLE `participant` (
	Id_participant INT NOT NULL AUTO_INCREMENT,
	Id_affaire INT NOT NULL,
	nom	VARCHAR(255) NULL,
	prenom VARCHAR(255) NULL,
	prix_unitaire double NULL,
	PRIMARY KEY ( `Id_participant` ) 
)");

//Création de la table doc_formation contenant les informations sur l'édition de documents
mysql_query("
CREATE TABLE `doc_formation` (
	Id_doc INT NOT NULL,
	Id_session INT NOT NULL,
	createur VARCHAR(255) NOT NULL,
	date_edition DATETIME NOT NULL,
	PRIMARY KEY ( Id_doc, Id_session ) 
)");

//Création de la table type_doc pour contenir les type de documents pour le pôle formation
mysql_query("
CREATE TABLE `type_doc` (
	Id_doc int NOT NULL AUTO_INCREMENT,
	type VARCHAR(255) NOT NULL,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_doc` ) 
)");

//Insertion des libéllés des types de documents pour le pôle formation
mysql_query("
INSERT into type_doc values (1,'Checklist','Checklist'),
(2,'Presence','Attestation(s) de présence'),
(3,'Attestation','Attestation(s) de formation'),
(4,'EvaluationStagaire','Fiche(s) d\'évaluation Stagiaire'),
(5,'EvaluationFormateur','Fiche d\'évaluation Formateur'),
(6,'Convocation','Convocation'),
(7,'Convention','Convention(s) de formation'),
(8,'BDC','Bon(s) de commande'),
(9,'BI','Bon(s) d\'intervention'),
(10,'Confirmation','Lettre de confirmation')
");	

?>