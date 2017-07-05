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
CREATE TABLE `affaire_demande_ressource` (
    `Id_affaire` INT NOT NULL default '0',
	`Id_demande_ressource` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`,`Id_demande_ressource`  ) 
)");

mysql_query("
CREATE TABLE `demande_ressource` (
  `Id_demande_ressource` int(11) unsigned NOT NULL auto_increment,
  `Id_cr` varchar(255) NOT NULL,
  `Id_ic` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `date` date NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `annonce` varchar(255) NOT NULL,
  `date_reponse` varchar(255) NOT NULL,
  `salaire_debut` int(11) NOT NULL,
  `salaire_fin` int(11) NOT NULL,
  `description` text NOT NULL,
  `diplome` int(11) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `date_mission` date NOT NULL,
  `duree_mission` varchar(255) NOT NULL,
  `competences` text NOT NULL,
  PRIMARY KEY  (`Id_demande_ressource`)
)");

mysql_query("
CREATE TABLE `demande_ressource_candidat` (
  `Id_candidat` int(10) unsigned NOT NULL auto_increment,
  `Id_demande_ressource` int(11) NOT NULL,
  `Id_ressource` int(11) NOT NULL,
  `Id_cr` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`Id_candidat`)
)");

mysql_query("
CREATE TABLE `statut_demande_ressource` (
  `Id_statut` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_statut`)
)");


mysql_query("
INSERT INTO `statut_demande_ressource` (`Id_statut`, `libelle`) VALUES 
(1, 'En cours'),
(2, 'Pourvu par recrutement'),
(3, 'Pourvu par collaborateur'),
(4, 'Veille'),
(5, 'Non pourvu'),
(6, 'Fin veille'),
(7, 'Demande traitée')
");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `affaire_demande_ressource` (
    `Id_affaire` INT NOT NULL default '0',
	`Id_demande_ressource` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`,`Id_demande_ressource`  ) 
)");

mysql_query("
CREATE TABLE `demande_ressource` (
  `Id_demande_ressource` int(11) unsigned NOT NULL auto_increment,
  `Id_cr` varchar(255) NOT NULL,
  `Id_ic` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `date` date NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `annonce` varchar(255) NOT NULL,
  `date_reponse` varchar(255) NOT NULL,
  `salaire_debut` int(11) NOT NULL,
  `salaire_fin` int(11) NOT NULL,
  `description` text NOT NULL,
  `diplome` int(11) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `date_mission` date NOT NULL,
  `duree_mission` varchar(255) NOT NULL,
  `competences` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_demande_ressource`)
)");

mysql_query("
CREATE TABLE `demande_ressource_candidat` (
  `Id_candidat` int(10) unsigned NOT NULL auto_increment,
  `Id_demande_ressource` int(11) NOT NULL,
  `Id_ressource` int(11) NOT NULL,
  `Id_cr` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`Id_candidat`)
)");

mysql_query("
CREATE TABLE `statut_demande_ressource` (
  `Id_statut` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_statut`)
)");


mysql_query("
INSERT INTO `statut_demande_ressource` (`Id_statut`, `libelle`) VALUES 
(1, 'En cours'),
(2, 'Pourvu par recrutement'),
(3, 'Pourvu par collaborateur'),
(4, 'Veille'),
(5, 'Non pourvu'),
(6, 'Fin veille')
(7, 'Attente retour IC/Client')
");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `affaire_demande_ressource` (
    `Id_affaire` INT NOT NULL default '0',
	`Id_demande_ressource` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`,`Id_demande_ressource`  ) 
)");

mysql_query("
CREATE TABLE `demande_ressource` (
  `Id_demande_ressource` int(11) unsigned NOT NULL auto_increment,
  `Id_cr` varchar(255) NOT NULL,
  `Id_ic` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `date` date NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `annonce` varchar(255) NOT NULL,
  `date_reponse` varchar(255) NOT NULL,
  `salaire_debut` int(11) NOT NULL,
  `salaire_fin` int(11) NOT NULL,
  `description` text NOT NULL,
  `diplome` int(11) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `date_mission` date NOT NULL,
  `duree_mission` varchar(255) NOT NULL,
  `competences` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_demande_ressource`)
)");

mysql_query("
CREATE TABLE `demande_ressource_candidat` (
  `Id_candidat` int(10) unsigned NOT NULL auto_increment,
  `Id_demande_ressource` int(11) NOT NULL,
  `Id_ressource` int(11) NOT NULL,
  `Id_cr` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`Id_candidat`)
)");

mysql_query("
CREATE TABLE `statut_demande_ressource` (
  `Id_statut` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_statut`)
)");


mysql_query("
INSERT INTO `statut_demande_ressource` (`Id_statut`, `libelle`) VALUES 
(1, 'En cours'),
(2, 'Pourvu'),
(3, 'Veille'),
(4, 'Non pourvu'),
(5, 'Fin veille'),
(6, 'NC')
");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `affaire_demande_ressource` (
    `Id_affaire` INT NOT NULL default '0',
	`Id_demande_ressource` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`,`Id_demande_ressource`  ) 
)");

mysql_query("
CREATE TABLE `demande_ressource` (
  `Id_demande_ressource` int(11) unsigned NOT NULL auto_increment,
  `Id_cr` varchar(255) NOT NULL,
  `Id_ic` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `date` date NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `annonce` varchar(255) NOT NULL,
  `date_reponse` varchar(255) NOT NULL,
  `salaire_debut` int(11) NOT NULL,
  `salaire_fin` int(11) NOT NULL,
  `description` text NOT NULL,
  `diplome` int(11) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `date_mission` date NOT NULL,
  `duree_mission` varchar(255) NOT NULL,
  `competences` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_demande_ressource`)
)");

mysql_query("
CREATE TABLE `demande_ressource_candidat` (
  `Id_candidat` int(10) unsigned NOT NULL auto_increment,
  `Id_demande_ressource` int(11) NOT NULL,
  `Id_ressource` int(11) NOT NULL,
  `Id_cr` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`Id_candidat`)
)");

mysql_query("
CREATE TABLE `statut_demande_ressource` (
  `Id_statut` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY  (`Id_statut`)
)");


mysql_query("
INSERT INTO `statut_demande_ressource` (`Id_statut`, `libelle`) VALUES 
(1, 'En cours'),
(2, 'Pourvu'),
(3, 'Veille'),
(4, 'Non pourvu'),
(5, 'Fin veille'),
(6, 'NC')
");


?>