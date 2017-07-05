<?php
###############################################
#													#
# 		Script permettant de créer les agences                				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection');

mysql_query("
CREATE TABLE `bilan_activite` (
	`Id_bilan_activite` int(1) NOT NULL auto_increment,
    `date_creation` DATETIME,
    `createur` VARCHAR(100) NOT NULL default '',
	`responsable` VARCHAR(100) NOT NULL default '',
	`commercial` VARCHAR(100) NOT NULL default '',
	`date` DATE NOT NULL DEFAULT '0000-00-00',
	`mois` VARCHAR(10) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_bilan_activite` ) 
)");

mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=creer&class=BilanActivite','d')");
mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=consulterBilanActivite','g')");
mysql_query("INSERT INTO `groupe_ad_menu` VALUES 
('1','48'),
('2','48'),
('3','48'),
('4','48'),
('5','48'),
('6','48'),
('7','48'),
('8','48'),
('1','49'),
('2','49'),
('3','49'),
('4','49'),
('5','49'),
('6','49'),
('7','49'),
('8','49')
");

mysql_query("
CREATE TABLE `session_php` (
    `sess_id` char(40) NOT NULL,
    `sess_datas` text NOT NULL,
    `sess_expire` bigint(20) NOT NULL,
    UNIQUE KEY `sess_id` (`sess_id`)
)");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');
mysql_query("
CREATE TABLE `bilan_activite` (
	`Id_bilan_activite` int(1) NOT NULL auto_increment,
    `date_creation` DATETIME,
    `createur` VARCHAR(100) NOT NULL default '',
	`responsable` VARCHAR(100) NOT NULL default '',
	`commercial` VARCHAR(100) NOT NULL default '',
	`date` DATE NOT NULL DEFAULT '0000-00-00',
	`mois` VARCHAR(10) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_bilan_activite` ) 
)");

mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=creer&class=BilanActivite','d')");
mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=consulterBilanActivite','g')");
mysql_query("INSERT INTO `groupe_ad_menu` VALUES 
('1','48'),
('2','48'),
('3','48'),
('4','48'),
('5','48'),
('6','48'),
('7','48'),
('8','48'),
('1','49'),
('2','49'),
('3','49'),
('4','49'),
('5','49'),
('6','49'),
('7','49'),
('8','49')
");

mysql_query("
CREATE TABLE `session_php` (
    `sess_id` char(40) NOT NULL,
    `sess_datas` text NOT NULL,
    `sess_expire` bigint(20) NOT NULL,
    UNIQUE KEY `sess_id` (`sess_id`)
)");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');
mysql_query("
CREATE TABLE `bilan_activite` (
	`Id_bilan_activite` int(1) NOT NULL auto_increment,
    `date_creation` DATETIME,
    `createur` VARCHAR(100) NOT NULL default '',
	`responsable` VARCHAR(100) NOT NULL default '',
	`commercial` VARCHAR(100) NOT NULL default '',
	`date` DATE NOT NULL DEFAULT '0000-00-00',
	`mois` VARCHAR(10) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_bilan_activite` ) 
)");

mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=creer&class=BilanActivite','d')");
mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=consulterBilanActivite','g')");
mysql_query("INSERT INTO `groupe_ad_menu` VALUES 
('1','48'),
('2','48'),
('3','48'),
('4','48'),
('5','48'),
('6','48'),
('7','48'),
('8','48'),
('1','49'),
('2','49'),
('3','49'),
('4','49'),
('5','49'),
('6','49'),
('7','49'),
('8','49')
");

mysql_query("
CREATE TABLE `session_php` (
    `sess_id` char(40) NOT NULL,
    `sess_datas` text NOT NULL,
    `sess_expire` bigint(20) NOT NULL,
    UNIQUE KEY `sess_id` (`sess_id`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');
mysql_query("
CREATE TABLE `bilan_activite` (
	`Id_bilan_activite` int(1) NOT NULL auto_increment,
    `date_creation` DATETIME,
    `createur` VARCHAR(100) NOT NULL default '',
	`responsable` VARCHAR(100) NOT NULL default '',
	`commercial` VARCHAR(100) NOT NULL default '',
	`date` DATE NOT NULL DEFAULT '0000-00-00',
	`mois` VARCHAR(10) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_bilan_activite` ) 
)");

mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=creer&class=BilanActivite','d')");
mysql_query("INSERT INTO `menu` VALUES ('','Bilan Activité','../com/index.php?a=consulterBilanActivite','g')");
mysql_query("INSERT INTO `groupe_ad_menu` VALUES 
('1','48'),
('2','48'),
('3','48'),
('4','48'),
('5','48'),
('6','48'),
('7','48'),
('8','48'),
('1','49'),
('2','49'),
('3','49'),
('4','49'),
('5','49'),
('6','49'),
('7','49'),
('8','49')
");

mysql_query("
CREATE TABLE `session_php` (
    `sess_id` char(40) NOT NULL,
    `sess_datas` text NOT NULL,
    `sess_expire` bigint(20) NOT NULL,
    UNIQUE KEY `sess_id` (`sess_id`)
)");


?>