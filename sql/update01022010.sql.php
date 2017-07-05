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
CREATE TABLE `etat_matrimonial` (
    `Id_etat_matrimonial` INT(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_matrimonial`,`libelle` ) 
)");

mysql_query("
INSERT INTO `etat_matrimonial` (`Id_etat_matrimonial`, `libelle`) VALUES 
('1', 'Marié'),
('2', 'Célibataire'),
('3', 'PACS'),
('4', 'Vie maritale'),
('5', 'Veuf'),
('6', 'Divorcé')
");

mysql_query("ALTER TABLE `ressource` ADD column Id_etat_matrimonial INT(1) NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_matrimonial` (
    `Id_etat_matrimonial` INT(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_matrimonial`,`libelle` ) 
)");

mysql_query("
INSERT INTO `etat_matrimonial` (`Id_etat_matrimonial`, `libelle`) VALUES 
('1', 'Marié'),
('2', 'Célibataire'),
('3', 'PACS'),
('4', 'Vie maritale'),
('5', 'Veuf'),
('6', 'Divorcé')
");

mysql_query("ALTER TABLE `ressource` ADD column Id_etat_matrimonial INT(1) NOT NULL DEFAULT '0'");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_matrimonial` (
    `Id_etat_matrimonial` INT(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_matrimonial`,`libelle` ) 
)");

mysql_query("
INSERT INTO `etat_matrimonial` (`Id_etat_matrimonial`, `libelle`) VALUES 
('1', 'Marié'),
('2', 'Célibataire'),
('3', 'PACS'),
('4', 'Vie maritale'),
('5', 'Veuf'),
('6', 'Divorcé')
");

mysql_query("ALTER TABLE `ressource` ADD column Id_etat_matrimonial INT(1) NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `etat_matrimonial` (
    `Id_etat_matrimonial` INT(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_matrimonial`,`libelle` ) 
)");

mysql_query("
INSERT INTO `etat_matrimonial` (`Id_etat_matrimonial`, `libelle`) VALUES 
('1', 'Marié'),
('2', 'Célibataire'),
('3', 'PACS'),
('4', 'Vie maritale'),
('5', 'Veuf'),
('6', 'Divorcé')
");

mysql_query("ALTER TABLE `ressource` ADD column Id_etat_matrimonial INT(1) NOT NULL DEFAULT '0'");

?>