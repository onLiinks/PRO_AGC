<?php
#############################################################################################################
#																										    #
# Script permettant de mettre � jour la base de donn�es	pour int�grer la notion de dates aux actions men�es	#
#																										    #
#############################################################################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 
//Cr�ation de la table `historique_action_candidature` contenant les dates � laquelle les actions ont �t� men�es sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");


mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
//Cr�ation de la table `historique_action_candidature` contenant les dates � laquelle les actions ont �t� men�es sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");


mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
//Cr�ation de la table `historique_action_candidature` contenant les dates � laquelle les actions ont �t� men�es sur les candidatures
mysql_query("
CREATE TABLE `historique_action_candidature` (
	Id_candidature INT NOT NULL,
	Id_utilisateur VARCHAR(255) NOT NULL,
	Id_action_mener int(1) NOT NULL,
	date_action DATETIME NOT NULL,
	PRIMARY KEY ( `Id_candidature`,`Id_action_mener`,`Id_utilisateur`,`date_action`) 
)");

?>