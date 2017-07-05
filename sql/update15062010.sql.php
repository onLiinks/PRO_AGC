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
CREATE TABLE `commentaire_affaire_semaine` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `year` INT(4) NOT NULL DEFAULT 0,
  `week` INT(2) NOT NULL DEFAULT 0,
  `comment` TEXT,
  `weighting_pct` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `Id_affaire`,`year`,`week`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `commentaire_affaire_semaine` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `year` INT(4) NOT NULL DEFAULT 0,
  `week` INT(2) NOT NULL DEFAULT 0,
  `comment` TEXT,
  `weighting_pct` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `Id_affaire`,`year`,`week`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `commentaire_affaire_semaine` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `year` INT(4) NOT NULL DEFAULT 0,
  `week` INT(2) NOT NULL DEFAULT 0,
  `comment` TEXT,
  `weighting_pct` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `Id_affaire`,`year`,`week`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `commentaire_affaire_semaine` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `year` INT(4) NOT NULL DEFAULT 0,
  `week` INT(2) NOT NULL DEFAULT 0,
  `comment` TEXT,
  `weighting_pct` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `Id_affaire`,`year`,`week`)
)");

?>