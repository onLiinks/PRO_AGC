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
CREATE TABLE `etat_action` (
    `Id_etat_action` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_action` ) 
)");
mysql_query("ALTER TABLE `action` ADD column Id_etat_action INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` ADD column archive INT NOT NULL DEFAULT '0'");

mysql_query("
INSERT INTO `etat_action` VALUES
('','En cours'),
('','Terminée')
");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_action` (
    `Id_etat_action` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_action` ) 
)");
mysql_query("ALTER TABLE `action` ADD column Id_etat_action INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` ADD column archive INT NOT NULL DEFAULT '0'");

mysql_query("
INSERT INTO `etat_action` VALUES
('','En cours'),
('','Terminée')
");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_action` (
    `Id_etat_action` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_action` ) 
)");
mysql_query("ALTER TABLE `action` ADD column Id_etat_action INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` ADD column archive INT NOT NULL DEFAULT '0'");

mysql_query("
INSERT INTO `etat_action` VALUES
('','En cours'),
('','Terminée')
");


//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_action` (
    `Id_etat_action` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_action` ) 
)");
mysql_query("ALTER TABLE `action` ADD column Id_etat_action INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` ADD column archive INT NOT NULL DEFAULT '0'");

mysql_query("
INSERT INTO `etat_action` VALUES
('','En cours'),
('','Terminée')
");


?>