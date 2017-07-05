<?php
#########################################################################################################
#																										#
# Script permettant de mettre � jour la base de donn�es	pour int�grer les affaires du p�le formation	#
#																										#
#########################################################################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 

//Cr�ation de la table session contenant les informations principales d'une session
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

//Cr�ation de la table proposition_session contenant les informations commerciales de la session
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

//Cr�ation de la table planning_session contenant les informations sur les dates et la dur�e de la session
mysql_query("
CREATE TABLE `planning_session` (
	Id_plan_session INT NOT NULL AUTO_INCREMENT,
	Id_session INT NULL,
	nb_jour INT NULL, 
	dateDebut DATE NULL,
	dateFin DATE NULL,
	PRIMARY KEY ( `Id_plan_session` ) 
)");

//Cr�ation de la table periode_session contenant les p�riodes interm�diaires du planning de la session
mysql_query("
CREATE TABLE `periode_session` (
	Id_periode_session INT NOT NULL AUTO_INCREMENT,
	Id_planning_session INT NOT NULL,
	periode_debut date NULL,
	periode_fin date NULL,
	PRIMARY KEY ( `Id_periode_session` ) 
)");

//Cr�ation de la table date contenant les dates ponctuelles du planning de la session
mysql_query("
CREATE TABLE `date` (
	Id_date INT NOT NULL AUTO_INCREMENT,
	date DATE NULL,
	PRIMARY KEY ( `Id_date` ) 
)");

//Cr�ation de la table planning_date contenant les associations entre la table planning_session et la table date
mysql_query("
CREATE TABLE `planning_date` (
	Id_date INT NOT NULL,
	Id_plan_session INT NOT NULL,
	PRIMARY KEY ( Id_date, Id_plan_session ) 
)");



//Cr�ation de la table formateur contenant les informations concernant les formateurs
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

//Cr�ation de la table salle contenant les informations concernant les salles
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

//Cr�ation de la table formateur_salle contenant les associations entre la session, le formateur et la salle
mysql_query("
CREATE TABLE `formateur_salle` (
	Id_session INT NOT NULL,
	Id_formateur INT NOT NULL DEFAULT 0,
	Id_salle INT NOT NULL DEFAULT 0,
	PRIMARY KEY ( `Id_session`,`Id_formateur`,`Id_salle`  ) 
)");

//Cr�ation de la table proposition_formation contenant les informations commerciales sp�cifiques au p�le formation d'une affaire 
// compl�ment de la table proposition
mysql_query("
CREATE TABLE `proposition_formation` (
	Id_propFormation INT NOT NULL AUTO_INCREMENT,
	Id_proposition int NULL,
	nb_inscrit int NULL,
	lien_bdc VARCHAR(255) NULL,
	PRIMARY KEY ( `Id_propFormation` ) 
)");

//Ajout du pourcentage de reussite � la table analyse_risque
mysql_query("ALTER TABLE `analyse_risque` add column pourcentage_reussite double NULL");

//Ajout d'un identifiant de session � la table affaire
mysql_query("ALTER TABLE `affaire` add column Id_session INT(11) NULL");

//Cr�ation de la table logistique contenant les informations de la checklist (lignes coch�es et remarques associ�es)
mysql_query("
CREATE TABLE `logistique` (
	Id_session INT NOT NULL,
	Id_checklist INT NOT NULL,
	valeur int(1) NULL,
	rmq varchar(255) NULL,
	PRIMARY KEY ( Id_session, Id_checklist ) 
)");

//Cr�ation de la table checklist contenant les lib�ll�s de la checklist
mysql_query("
CREATE TABLE `checklist` (
	Id_checklist INT NOT NULL AUTO_INCREMENT,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_checklist` ) 
)");

//Insertion des lib�ll�s de la checklist
mysql_query("
INSERT into checklist values (1,'R�servation de la salle'),(2,'R�servation du consultant/formateur'),
(3,'Recherche des supports de cours'),(4,'Etablissement de la proposition'),
(5,'Etablissement du bon de commande'),(6,'Mise � jour de CEGID'),
(7,'R�ception du bon de commande client'),(8,'Mise � jour r�currente'),
(9,'Mise � jour planning salle'),(10,'Validation r�servation de la salle (bon de commande)'),
(11,'Validation du consultant-formateur (bon de commande)'),(12,'Etablissement fiche de pr�paration de la salle(GLPI)'),
(13,'Commande supports de cours'),(14,'Mise � jour de l\'AGC'),
(15,'Envoie des convocations'),(16,'Etablissement feuille(s) de pr�sence(s)'),
(17,'Etablissment des fiches \"Evaluations stagiaires\"'),(18,'Etablissement des fiches \"Evaluation formateur\"'),
(19,'Etablissement des \"Attestations de stage\"'),(20,'R�ception des support de cours'),
(21,'Pr�paration du \"dossier de stage formateur\"'),(22,'Remise du \"dossier de stage formateur\" au formateur'),
(23,'Retour du \"dossier de stage formateur\"'),(24,'D�clenchement facturation et mise � jour r�current'),
(25,'Classement dossier et mise � jour du bilan p�dagogique')	
");					

//Cr�ation de la table statut_formateur contenant les lib�ll�s des statuts des formateurs
mysql_query("
CREATE TABLE `statut_formateur` (
	Id_statut INT NOT NULL AUTO_INCREMENT,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_statut` ) 
)");

//Insertion des lib�ll�s des statuts des formateurs
mysql_query("
INSERT into statut_formateur values (1,'Travailleur ind�pendant'),(2,'Salari� en CDI'),
(3,'Salari� en CDD'),(4,'Formateur occasionnel salari�e'),
(5,'B�n�vole'),(6,'Partenaire')
");

//Cr�ation de la table participant contenant les inscriptions � une session pour l'affaire
mysql_query("
CREATE TABLE `participant` (
	Id_participant INT NOT NULL AUTO_INCREMENT,
	Id_affaire INT NOT NULL,
	nom	VARCHAR(255) NULL,
	prenom VARCHAR(255) NULL,
	prix_unitaire double NULL,
	PRIMARY KEY ( `Id_participant` ) 
)");

//Cr�ation de la table doc_formation contenant les informations sur l'�dition de documents
mysql_query("
CREATE TABLE `doc_formation` (
	Id_doc INT NOT NULL,
	Id_session INT NOT NULL,
	createur VARCHAR(255) NOT NULL,
	date_edition DATETIME NOT NULL,
	PRIMARY KEY ( Id_doc, Id_session ) 
)");

//Cr�ation de la table type_doc pour contenir les type de documents pour le p�le formation
mysql_query("
CREATE TABLE `type_doc` (
	Id_doc int NOT NULL AUTO_INCREMENT,
	type VARCHAR(255) NOT NULL,
	libelle	VARCHAR(255),
	PRIMARY KEY ( `Id_doc` ) 
)");

//Insertion des lib�ll�s des types de documents pour le p�le formation
mysql_query("
INSERT into type_doc values (1,'Checklist','Checklist'),
(2,'Presence','Attestation(s) de pr�sence'),
(3,'Attestation','Attestation(s) de formation'),
(4,'EvaluationStagaire','Fiche(s) d\'�valuation Stagiaire'),
(5,'EvaluationFormateur','Fiche d\'�valuation Formateur'),
(6,'Convocation','Convocation'),
(7,'Convention','Convention(s) de formation'),
(8,'BDC','Bon(s) de commande'),
(9,'BI','Bon(s) d\'intervention'),
(10,'Confirmation','Lettre de confirmation')
");	

?>