<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('gescom') or die ('pas de connection'); 


mysql_query("ALTER TABLE `analyse_commerciale` add column potentiel INT NOT NULL DEFAULT 0");

mysql_query("ALTER TABLE `affaire` add column resp_qualif VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `affaire` add column resp_tec1 VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `affaire` add column resp_tec2 VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `affaire` add column nb_jour_estime DOUBLE NOT NULL DEFAULT 0");


mysql_query("ALTER TABLE `planning` add column date_pec DATE NOT NULL DEFAULT '0000-00-00'");



mysql_query("ALTER TABLE `environnement` add column nb_imprimante INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `environnement` change nb_site nb_site_fr INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `environnement` add column nb_site_ext INT NOT NULL DEFAULT '0'");

mysql_query("
CREATE TABLE `env_couverture` (
	`langue` VARCHAR(255) NOT NULL default '',
	`plage_horaire_hd` VARCHAR(255) NOT NULL default '',
	`plage_horaire_sup` VARCHAR(255) NOT NULL default '',
	`contact` VARCHAR(255) NOT NULL default '',
	`exploitabilite` VARCHAR(255) NOT NULL default '',
	`Id_environnement` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_environnement` )
)");

mysql_query("
CREATE TABLE `env_serveur` (
	`systeme` VARCHAR(255) NOT NULL default '',
	`version` VARCHAR(255) NOT NULL default '',
	`roles` VARCHAR(255) NOT NULL default '',
	`nb_serveur` INT(4) NOT NULL default '0',
	`supervision` INT(1) NOT NULL default '0',
	`Id_environnement` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_environnement` )
)");

mysql_query("
CREATE TABLE `env_pdt` (
	`type` VARCHAR(255) NOT NULL default '',
	`editeur` VARCHAR(255) NOT NULL default '',
	`version` VARCHAR(255) NOT NULL default '',
	`Id_environnement` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_environnement` )
)");


mysql_query("
CREATE TABLE `env_reseaux` (
	`modele` VARCHAR(255) NOT NULL default '',
	`version` VARCHAR(255) NOT NULL default '',
	`roles` VARCHAR(255) NOT NULL default '',
	`nombre` INT(4) NOT NULL default '0',
	`supervision` INT(1) NOT NULL default '0',
	`Id_environnement` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_environnement` )
)");


mysql_query("
CREATE TABLE `env_tache` (
	`supervision` TEXT,
	`exploitation` TEXT,
	`assistance` TEXT,
	`administration` TEXT,
	`Id_environnement` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_environnement` )
)");



?>
<script language='javascript' type='text/javascript'>
alert('La mise à jour des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>