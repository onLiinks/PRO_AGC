<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 

mysql_query("
CREATE TABLE `demande_changement` (
	`Id_demande_changement` INT NOT NULL AUTO_INCREMENT,
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`libelle` VARCHAR(255) NOT NULL default '',
	`ancien` VARCHAR(255) NOT NULL default '',
	`nouveau` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',	
	`date_creation` DATETIME,
	`date_souhaite` DATE,
	`valide_par` VARCHAR(255) NOT NULL default '',
	`date_validation` DATE,
	`integre_par` VARCHAR(255) NOT NULL default '',
	`date_integration` DATE,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_demande_changement` ) 
)");

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 

mysql_query("
CREATE TABLE `demande_changement` (
	`Id_demande_changement` INT NOT NULL AUTO_INCREMENT,
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`libelle` VARCHAR(255) NOT NULL default '',
	`ancien` VARCHAR(255) NOT NULL default '',
	`nouveau` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',	
	`date_creation` DATETIME,
	`date_souhaite` DATE,
	`valide_par` VARCHAR(255) NOT NULL default '',
	`date_validation` DATE,
	`integre_par` VARCHAR(255) NOT NULL default '',
	`date_integration` DATE,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_demande_changement` ) 
)");

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 

mysql_query("
CREATE TABLE `demande_changement` (
	`Id_demande_changement` INT NOT NULL AUTO_INCREMENT,
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`libelle` VARCHAR(255) NOT NULL default '',
	`ancien` VARCHAR(255) NOT NULL default '',
	`nouveau` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',	
	`date_creation` DATETIME,
	`date_souhaite` DATE,
	`valide_par` VARCHAR(255) NOT NULL default '',
	`date_validation` DATE,
	`integre_par` VARCHAR(255) NOT NULL default '',
	`date_integration` DATE,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_demande_changement` ) 
)");