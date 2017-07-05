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
//Création de la table candidat_agence pour les agences souhaitées dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
//Création de la table candidat_agence pour les agences souhaitées dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
//Création de la table candidat_agence pour les agences souhaitées dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

?>