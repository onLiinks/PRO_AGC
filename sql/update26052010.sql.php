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

mysql_query("ALTER TABLE `report_activite_commerciale` DROP column `nb_j_travaille_annee`");

mysql_query("
CREATE TABLE `presence` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `nbj_presence` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `report_activite_commerciale` DROP column `nb_j_travaille_annee`");

mysql_query("
CREATE TABLE `presence` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `nbj_presence` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `report_activite_commerciale` DROP column `nb_j_travaille_annee`");

mysql_query("
CREATE TABLE `presence` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `nbj_presence` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");


//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `report_activite_commerciale` DROP column `nb_j_travaille_annee`");

mysql_query("
CREATE TABLE `presence` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `nbj_presence` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

?>