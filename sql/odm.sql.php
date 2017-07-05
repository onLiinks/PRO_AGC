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
CREATE TABLE `ordre_mission` (
	`Id_ordre_mission` INT NOT NULL AUTO_INCREMENT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATE NOT NULL DEFAULT '0000-00-00',
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`profil` VARCHAR(255) NOT NULL default '',
	`Id_compte` VARCHAR(255) NOT NULL default '',
	`telephone` VARCHAR(255) NOT NULL default '',
	`lieu_mission` VARCHAR(255) NOT NULL default '',
	`moyen_acces` VARCHAR(255) NOT NULL default '',
	`date_debut` DATE NOT NULL DEFAULT '0000-00-00',
	`date_fin` DATE NOT NULL DEFAULT '0000-00-00',
	`duree` DOUBLE NOT NULL default '0',
	`contact` VARCHAR(255) NOT NULL default '',
	`resp_proservia` VARCHAR(255) NOT NULL default '',
	`tache` TEXT,
	`frais` TEXT,
	`indemnites_repas` TEXT,
	`Id_agence` VARCHAR(255) NOT NULL default '',
	`envoye` INT(1) NOT NULL default '0',
	`retour` INT(1) NOT NULL default '0',
	`Id_cd` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_ordre_mission`, `Id_cd`)
)");


mysql_query("ALTER TABLE `rendezvous` add column objet VARCHAR(255) NOT NULL DEFAULT ''");


mysql_query("
CREATE TABLE `stats_ca` (
	`Id_affaire` INT NOT NULL default '0',
	`mois` VARCHAR(7) NOT NULL default '',
	`ca_facture` DOUBLE NOT NULL DEFAULT '0',
	`ca_probable` DOUBLE NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`, `mois`)
)");

mysql_query("ALTER TABLE `affaire` add column commercial VARCHAR(255) NOT NULL DEFAULT ''");


/* AFFECTATION DES CREATEUR A LA COLONNE COMMERCIAL */
$resultat = mysql_query("SELECT Id_affaire,createur FROM affaire");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set commercial="'.$tableau['createur'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
}
