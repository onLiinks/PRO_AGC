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
CREATE TABLE `report_activite_commerciale` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `ca_objectif_annuel` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_previsionnel_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_signature_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_signature_sem_prec` INT(4) NOT NULL DEFAULT 0,
  `ca_trouve` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_trouve_sem_prec` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_pis` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_ecr` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_aq` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `ca_affaire_rem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_pipe_total` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_affaire_ecr` INT(4) NOT NULL DEFAULT 0,
  `nb_affaire_aq` INT(4) NOT NULL DEFAULT 0,  
  `nb_affaire_rem` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_sem` INT(4) NOT NULL DEFAULT 0,  
  `nb_rdv_futur` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_moy_sem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `obj_pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `nb_j_travaille_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_collab_titulaire` INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `report_activite_commerciale` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `ca_objectif_annuel` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_previsionnel_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_signature_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_signature_sem_prec` INT(4) NOT NULL DEFAULT 0,
  `ca_trouve` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_trouve_sem_prec` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_pis` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_ecr` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_aq` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `ca_affaire_rem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_pipe_total` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_affaire_ecr` INT(4) NOT NULL DEFAULT 0,
  `nb_affaire_aq` INT(4) NOT NULL DEFAULT 0,  
  `nb_affaire_rem` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_sem` INT(4) NOT NULL DEFAULT 0,  
  `nb_rdv_futur` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_moy_sem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `obj_pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `nb_j_travaille_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_collab_titulaire` INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `report_activite_commerciale` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `ca_objectif_annuel` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_previsionnel_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_signature_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_signature_sem_prec` INT(4) NOT NULL DEFAULT 0,
  `ca_trouve` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_trouve_sem_prec` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_pis` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_ecr` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_aq` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `ca_affaire_rem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_pipe_total` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_affaire_ecr` INT(4) NOT NULL DEFAULT 0,
  `nb_affaire_aq` INT(4) NOT NULL DEFAULT 0,  
  `nb_affaire_rem` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_sem` INT(4) NOT NULL DEFAULT 0,  
  `nb_rdv_futur` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_moy_sem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `obj_pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `nb_j_travaille_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_collab_titulaire` INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `report_activite_commerciale` (
  `annee` INT(4) NOT NULL DEFAULT 0,
  `semaine` INT(2) NOT NULL DEFAULT 0,
  `commercial` VARCHAR(100) NOT NULL DEFAULT '',
  `ca_objectif_annuel` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_previsionnel_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_signature_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_signature_sem_prec` INT(4) NOT NULL DEFAULT 0,
  `ca_trouve` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_trouve_sem_prec` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_pis` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_ecr` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_affaire_aq` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `ca_affaire_rem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `ca_pipe_total` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_affaire_ecr` INT(4) NOT NULL DEFAULT 0,
  `nb_affaire_aq` INT(4) NOT NULL DEFAULT 0,  
  `nb_affaire_rem` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_annee` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_sem` INT(4) NOT NULL DEFAULT 0,  
  `nb_rdv_futur` INT(4) NOT NULL DEFAULT 0,
  `nb_rdv_moy_sem` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `obj_pct_prospection` FLOAT(25,2) NOT NULL DEFAULT '0.00',  
  `nb_j_travaille_annee` FLOAT(25,2) NOT NULL DEFAULT '0.00',
  `nb_collab_titulaire` INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY ( `annee`,`semaine`,`commercial`)
)");


?>