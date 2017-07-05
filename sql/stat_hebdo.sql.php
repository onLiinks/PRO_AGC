<?php
#############################################################################################################
#																										    #
# Script permettant de mettre à jour la base de données	pour intégrer la notion de dates aux actions menées	#
#																										    #
#############################################################################################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 
//Création des tables `stat_hebdo_cr` et `stat_hebdo_agence` pour les reports des activités hebdomadaires des rh
mysql_query("
CREATE TABLE `stats_hebdo_cr` (
	Id_utilisateur VARCHAR(255) NOT NULL,
	nom_utilisateur VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_utilisateur`,`stage`) 
)");

mysql_query("
CREATE TABLE `stats_hebdo_agence` (
	Id_agence VARCHAR(255) NOT NULL,
	nom_agence VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_agence`,`stage`) 
)");

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
//Création des tables `stat_hebdo_cr` et `stat_hebdo_agence` pour les reports des activités hebdomadaires des rh
mysql_query("
CREATE TABLE `stats_hebdo_cr` (
	Id_utilisateur VARCHAR(255) NOT NULL,
	nom_utilisateur VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_utilisateur`,`stage`) 
)");

mysql_query("
CREATE TABLE `stats_hebdo_agence` (
	Id_agence VARCHAR(255) NOT NULL,
	nom_agence VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_agence`,`stage`) 
)");

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
//Création des tables `stat_hebdo_cr` et `stat_hebdo_agence` pour les reports des activités hebdomadaires des rh
mysql_query("
CREATE TABLE `stats_hebdo_cr` (
	Id_utilisateur VARCHAR(255) NOT NULL,
	nom_utilisateur VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_utilisateur`,`stage`) 
)");

mysql_query("
CREATE TABLE `stats_hebdo_agence` (
	Id_agence VARCHAR(255) NOT NULL,
	nom_agence VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_agence`,`stage`) 
)");

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection'); 
//Création des tables `stat_hebdo_cr` et `stat_hebdo_agence` pour les reports des activités hebdomadaires des rh
mysql_query("
CREATE TABLE `stats_hebdo_cr` (
	Id_utilisateur VARCHAR(255) NOT NULL,
	nom_utilisateur VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_utilisateur`,`stage`) 
)");

mysql_query("
CREATE TABLE `stats_hebdo_agence` (
	Id_agence VARCHAR(255) NOT NULL,
	nom_agence VARCHAR(255) NOT NULL,
	semaine int NOT NULL,
	mois int NOT NULL,
	annee int NOT NULL,
	nb_entretien_prevu int NOT NULL,
	nb_entretien_realise int NOT NULL,
	nb_valide int NOT NULL,
	nb_embauche int NOT NULL,
	liste_emb_nom VARCHAR(255) NULL,
	liste_emb_profil VARCHAR(255) NULL,
	nb_cv_envoye int NOT NULL,
	liste_cv_nom VARCHAR(255) NULL,
	liste_cv_profil VARCHAR(255) NULL,
	stage int(1) NOT NULL,
	PRIMARY KEY ( `annee`,`semaine`,`Id_agence`,`stage`) 
)");


?>