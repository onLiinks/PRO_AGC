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

##################################################################################

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 

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

################################################################################

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 

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