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
CREATE TABLE `stats_odm` (
  `Id_ordre_mission` INT NOT NULL DEFAULT '0',
  `ressource` varchar(100) NOT NULL default '',
  `compte` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id_ordre_mission`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `stats_odm` (
  `Id_ordre_mission` INT NOT NULL DEFAULT '0',
  `ressource` varchar(100) NOT NULL default '',
  `compte` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id_ordre_mission`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `stats_odm` (
  `Id_ordre_mission` INT NOT NULL DEFAULT '0',
  `ressource` varchar(100) NOT NULL default '',
  `compte` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id_ordre_mission`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `stats_odm` (
  `Id_ordre_mission` INT NOT NULL DEFAULT '0',
  `ressource` varchar(100) NOT NULL default '',
  `compte` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id_ordre_mission`)
)");


?>