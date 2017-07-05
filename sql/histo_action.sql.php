<?php
#############################################################################################################
#																										    #
# Script permettant de mettre à jour la base de données	pour intégrer la notion de dates aux actions menées	#
#																										    #
#############################################################################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 
//Création de la table `historique_action_candidature` contenant les dates à laquelle les actions ont été menées sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");


mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
//Création de la table `historique_action_candidature` contenant les dates à laquelle les actions ont été menées sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");


mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
//Création de la table `historique_action_candidature` contenant les dates à laquelle les actions ont été menées sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");

?>