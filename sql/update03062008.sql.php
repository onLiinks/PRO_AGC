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


mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN frais_journalier DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN cout_journalier DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN tarif_journalier DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN duree DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN marge DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE proposition_ressource ADD COLUMN ca DOUBLE NOT NULL default '0';
)");

mysql_query("
ALTER TABLE ressource CHANGE situation Id_situation INT NOT NULL default '0';
)");

mysql_query("
ALTER TABLE ressource ADD COLUMN departement VARCHAR(255);
)");

mysql_query("
ALTER TABLE ressource ADD COLUMN pays VARCHAR(255);
)");

mysql_query("
ALTER TABLE ressource ADD COLUMN type_embauche VARCHAR(3);
)");

mysql_query("
ALTER TABLE ressource ADD COLUMN heure_embauche DOUBLE;
)");

mysql_query("
ALTER TABLE ressource MODIFY COLUMN date_embauche DATE;
)");


mysql_query("
CREATE TABLE `action` (
	`Id_action` INT NOT NULL AUTO_INCREMENT,
	`tache` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	`date_debut` DATE,
	`date_fin` DATE,
	`nature` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_action` ) 
)");

mysql_query("
CREATE TABLE `situation` (
	`Id_situation` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_situation` ) 
)");

mysql_query("
CREATE TABLE `contrat_delegation` (
	`Id_contrat_delegation` INT NOT NULL AUTO_INCREMENT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	`date_modification` DATETIME,
	`Id_affaire` INT NOT NULL default '0',
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`cout_journalier` DOUBLE NOT NULL default '0',
	`type_mission` VARCHAR(255) NOT NULL default '',
	`indemnites_ref` VARCHAR(255) NOT NULL default '',
	`nature_mission` VARCHAR(255) NOT NULL default '',
	`st_nom` VARCHAR(255) NOT NULL default '',
	`st_prenom` VARCHAR(255) NOT NULL default '',
	`st_societe` VARCHAR(255) NOT NULL default '',
	`st_adresse` VARCHAR(255) NOT NULL default '',
	`st_siret` VARCHAR(255) NOT NULL default '',
	`st_ape` VARCHAR(255) NOT NULL default '',
	`st_tarif` DOUBLE NOT NULL default '0',
	`st_commentaire` TEXT,
	`horaire` VARCHAR(255) NOT NULL default '',
	`commentaire_horaire` TEXT,
	`moyen_acces` VARCHAR(255) NOT NULL default '',
	`type_indemnite` INT NOT NULL default '0',
	`indemnite` TEXT,
	`commentaire_indemnite` TEXT,
	`archive` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_contrat_delegation` , `Id_affaire`, `Id_ressource`)
)");

mysql_query("
INSERT INTO `situation` VALUES
('','Marié'),
('','Célibataire'),
('','Bénéficiaire du PACS'),
('','Vie maritale'),
('','Veuf'),
('','Divorcé')
");

?>
<script language='javascript' type='text/javascript'>
alert('La mise à jour des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>	