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
//Cr�ation de la table candidat_agence pour les agences souhait�es dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
//Cr�ation de la table candidat_agence pour les agences souhait�es dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
//Cr�ation de la table candidat_agence pour les agences souhait�es dans les modifications rh
mysql_query("
CREATE TABLE `candidat_agence` (
	Id_candidat int NOT NULL,
	Id_agence VARCHAR(10) NOT NULL,
	PRIMARY KEY ( Id_candidat, Id_agence ) 
)");

?>