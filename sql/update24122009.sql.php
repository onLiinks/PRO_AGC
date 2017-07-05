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

mysql_query("ALTER TABLE `demande_ressource` CHANGE `profil` `profil` TINYINT( 2 ) NOT NULL ,
CHANGE `experience` `experience` TINYINT( 1 ) NOT NULL");
mysql_query("ALTER TABLE `demande_ressource` ADD `candidat_retenu` INT( 11 ) NOT NULL");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` CHANGE `profil` `profil` TINYINT( 2 ) NOT NULL ,
CHANGE `experience` `experience` TINYINT( 1 ) NOT NULL");
mysql_query("ALTER TABLE `demande_ressource` ADD `candidat_retenu` INT( 11 ) NOT NULL");



//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` CHANGE `profil` `profil` TINYINT( 2 ) NOT NULL ,
CHANGE `experience` `experience` TINYINT( 1 ) NOT NULL");
mysql_query("ALTER TABLE `demande_ressource` ADD `candidat_retenu` INT( 11 ) NOT NULL");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` CHANGE `profil` `profil` TINYINT( 2 ) NOT NULL ,
CHANGE `experience` `experience` TINYINT( 1 ) NOT NULL");
mysql_query("ALTER TABLE `demande_ressource` ADD `candidat_retenu` INT( 11 ) NOT NULL");

?>