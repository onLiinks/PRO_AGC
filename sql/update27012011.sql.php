<?php
###############################################
#													#
# 		Script permettant de cr�er la base de donn�es				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` ADD COLUMN prioritaire INT(1) NOT NULL DEFAULT 0;");
mysql_query("CREATE TABLE historique_statut_demande_ressource (
                Id_demande_ressource INT NOT NULL default '0',
                Id_statut INT NOT NULL default '0',
                date DATETIME, Id_utilisateur VARCHAR(255) NOT NULL default '',
                PRIMARY KEY (Id_demande_ressource, Id_statut, date, Id_utilisateur)
            );");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` ADD COLUMN prioritaire INT(1) NOT NULL DEFAULT 0;");
mysql_query("CREATE TABLE historique_statut_demande_ressource (
                Id_demande_ressource INT NOT NULL default '0',
                Id_statut INT NOT NULL default '0',
                date DATETIME, Id_utilisateur VARCHAR(255) NOT NULL default '',
                PRIMARY KEY (Id_demande_ressource, Id_statut, date, Id_utilisateur)
            );");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` ADD COLUMN prioritaire INT(1) NOT NULL DEFAULT 0;");
mysql_query("CREATE TABLE historique_statut_demande_ressource (
                Id_demande_ressource INT NOT NULL default '0',
                Id_statut INT NOT NULL default '0',
                date DATETIME, Id_utilisateur VARCHAR(255) NOT NULL default '',
                PRIMARY KEY (Id_demande_ressource, Id_statut, date, Id_utilisateur)
            );");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `demande_ressource` ADD COLUMN prioritaire INT(1) NOT NULL DEFAULT 0;");
mysql_query("CREATE TABLE historique_statut_demande_ressource (
                Id_demande_ressource INT NOT NULL default '0',
                Id_statut INT NOT NULL default '0',
                date DATETIME, Id_utilisateur VARCHAR(255) NOT NULL default '',
                PRIMARY KEY (Id_demande_ressource, Id_statut, date, Id_utilisateur)
            );");

?>