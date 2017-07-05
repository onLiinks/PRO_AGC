<?php
#################################################
#												#
# Script permettant de créer la base de données	#
#												#
#################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection');

mysql_query("
CREATE TABLE `log_update_affaire` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `date` DATETIME,
  `Id_utilisateur` VARCHAR(100),
  `adresse_ip` VARCHAR(15),
  `log` TEXT
)");

mysql_query("
CREATE TABLE `listing_candidature` (
  `Id_candidature` INT NOT NULL DEFAULT 0,
  `Id_ressource` INT NOT NULL DEFAULT 0,  
  `Id_entretien` INT NOT NULL DEFAULT 0,
  `createur` VARCHAR(100),  
  `commercial` VARCHAR(100),  
  `date` DATE,
  `lien_cv` VARCHAR(255),
  `Id_profil` INT NOT NULL DEFAULT 0,
  `profil` VARCHAR(255),
  `Id_cursus` INT NOT NULL DEFAULT 0,
  `cursus` VARCHAR(255),
  `Id_etat` INT NOT NULL DEFAULT 0,
  `etat` VARCHAR(255),
  `Id_nature` INT NOT NULL DEFAULT 0,
  `nature` VARCHAR(255),
  `Id_preavis` INT NOT NULL DEFAULT 0,
  `preavis` VARCHAR(255),
  `Id_exp_info` INT NOT NULL DEFAULT 0,
  `exp_info` VARCHAR(15),
  `staff` INT NOT NULL DEFAULT 0,
  `nom` VARCHAR(100),  
  `prenom` VARCHAR(100),
  `tel_fixe` VARCHAR(20),
  `tel_portable` VARCHAR(20),
  `pretention_basse` DOUBLE,
  `pretention_haute` DOUBLE,
  `th` INT NOT NULL DEFAULT 0,
  `archive` INT NOT NULL DEFAULT 0
)");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `log_update_affaire` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `date` DATETIME,
  `Id_utilisateur` VARCHAR(100),
  `adresse_ip` VARCHAR(15),
  `log` TEXT
)");

mysql_query("
CREATE TABLE `listing_candidature` (
  `Id_candidature` INT NOT NULL DEFAULT 0,
  `Id_ressource` INT NOT NULL DEFAULT 0,  
  `Id_entretien` INT NOT NULL DEFAULT 0,
  `createur` VARCHAR(100),  
  `commercial` VARCHAR(100),  
  `date` DATE,
  `lien_cv` VARCHAR(255),
  `Id_profil` INT NOT NULL DEFAULT 0,
  `profil` VARCHAR(255),
  `Id_cursus` INT NOT NULL DEFAULT 0,
  `cursus` VARCHAR(255),
  `Id_etat` INT NOT NULL DEFAULT 0,
  `etat` VARCHAR(255),
  `Id_nature` INT NOT NULL DEFAULT 0,
  `nature` VARCHAR(255),
  `Id_preavis` INT NOT NULL DEFAULT 0,
  `preavis` VARCHAR(255),
  `Id_exp_info` INT NOT NULL DEFAULT 0,
  `exp_info` VARCHAR(15),
  `staff` INT NOT NULL DEFAULT 0,
  `nom` VARCHAR(100),  
  `prenom` VARCHAR(100),
  `tel_fixe` VARCHAR(20),
  `tel_portable` VARCHAR(20),
  `pretention_basse` DOUBLE,
  `pretention_haute` DOUBLE,
  `th` INT NOT NULL DEFAULT 0,
  `archive` INT NOT NULL DEFAULT 0
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `log_update_affaire` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `date` DATETIME,
  `Id_utilisateur` VARCHAR(100),
  `adresse_ip` VARCHAR(15),
  `log` TEXT
)");

mysql_query("
CREATE TABLE `listing_candidature` (
  `Id_candidature` INT NOT NULL DEFAULT 0,
  `Id_ressource` INT NOT NULL DEFAULT 0,  
  `Id_entretien` INT NOT NULL DEFAULT 0,
  `createur` VARCHAR(100),  
  `commercial` VARCHAR(100),  
  `date` DATE,
  `lien_cv` VARCHAR(255),
  `Id_profil` INT NOT NULL DEFAULT 0,
  `profil` VARCHAR(255),
  `Id_cursus` INT NOT NULL DEFAULT 0,
  `cursus` VARCHAR(255),
  `Id_etat` INT NOT NULL DEFAULT 0,
  `etat` VARCHAR(255),
  `Id_nature` INT NOT NULL DEFAULT 0,
  `nature` VARCHAR(255),
  `Id_preavis` INT NOT NULL DEFAULT 0,
  `preavis` VARCHAR(255),
  `Id_exp_info` INT NOT NULL DEFAULT 0,
  `exp_info` VARCHAR(15),
  `staff` INT NOT NULL DEFAULT 0,
  `nom` VARCHAR(100),  
  `prenom` VARCHAR(100),
  `tel_fixe` VARCHAR(20),
  `tel_portable` VARCHAR(20),
  `pretention_basse` DOUBLE,
  `pretention_haute` DOUBLE,
  `th` INT NOT NULL DEFAULT 0,
  `archive` INT NOT NULL DEFAULT 0
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `log_update_affaire` (
  `Id_affaire` INT NOT NULL DEFAULT 0,
  `date` DATETIME,
  `Id_utilisateur` VARCHAR(100),
  `adresse_ip` VARCHAR(15),
  `log` TEXT
)");

mysql_query("
CREATE TABLE `listing_candidature` (
  `Id_candidature` INT NOT NULL DEFAULT 0,
  `Id_ressource` INT NOT NULL DEFAULT 0,  
  `Id_entretien` INT NOT NULL DEFAULT 0,
  `createur` VARCHAR(100),  
  `commercial` VARCHAR(100),  
  `date` DATE,
  `lien_cv` VARCHAR(255),
  `Id_profil` INT NOT NULL DEFAULT 0,
  `profil` VARCHAR(255),
  `Id_cursus` INT NOT NULL DEFAULT 0,
  `cursus` VARCHAR(255),
  `Id_etat` INT NOT NULL DEFAULT 0,
  `etat` VARCHAR(255),
  `Id_nature` INT NOT NULL DEFAULT 0,
  `nature` VARCHAR(255),
  `Id_preavis` INT NOT NULL DEFAULT 0,
  `preavis` VARCHAR(255),
  `Id_exp_info` INT NOT NULL DEFAULT 0,
  `exp_info` VARCHAR(15),
  `staff` INT NOT NULL DEFAULT 0,
  `nom` VARCHAR(100),  
  `prenom` VARCHAR(100),
  `tel_fixe` VARCHAR(20),
  `tel_portable` VARCHAR(20),
  `pretention_basse` DOUBLE,
  `pretention_haute` DOUBLE,
  `th` INT NOT NULL DEFAULT 0,
  `archive` INT NOT NULL DEFAULT 0
)");

?>