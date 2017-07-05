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

mysql_query("ALTER TABLE `contrat_delegation` DROP column nom");
mysql_query("ALTER TABLE `contrat_delegation` DROP column adresse");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_fixe");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_portable");
mysql_query("ALTER TABLE `contrat_delegation` DROP column mail ");
mysql_query("ALTER TABLE `contrat_delegation` DROP column etat_matrimonial");
mysql_query("ALTER TABLE `contrat_delegation` DROP column securite_sociale");
mysql_query("ALTER TABLE `contrat_delegation` DROP column statut");
mysql_query("ALTER TABLE `contrat_delegation` DROP column nationalite");
mysql_query("ALTER TABLE `contrat_delegation` DROP column type_embauche");
mysql_query("ALTER TABLE `contrat_delegation` DROP column profil");
mysql_query("ALTER TABLE `contrat_delegation` DROP column fin_cdd");
mysql_query("ALTER TABLE `contrat_delegation` DROP column Id_service");


$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `contrat_delegation` DROP column nom");
mysql_query("ALTER TABLE `contrat_delegation` DROP column adresse");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_fixe");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_portable");
mysql_query("ALTER TABLE `contrat_delegation` DROP column mail ");
mysql_query("ALTER TABLE `contrat_delegation` DROP column etat_matrimonial");
mysql_query("ALTER TABLE `contrat_delegation` DROP column securite_sociale");
mysql_query("ALTER TABLE `contrat_delegation` DROP column statut");
mysql_query("ALTER TABLE `contrat_delegation` DROP column nationalite");
mysql_query("ALTER TABLE `contrat_delegation` DROP column type_embauche");
mysql_query("ALTER TABLE `contrat_delegation` DROP column profil");
mysql_query("ALTER TABLE `contrat_delegation` DROP column fin_cdd");
mysql_query("ALTER TABLE `contrat_delegation` DROP column Id_service");

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `contrat_delegation` DROP column nom");
mysql_query("ALTER TABLE `contrat_delegation` DROP column adresse");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_fixe");
mysql_query("ALTER TABLE `contrat_delegation` DROP column tel_portable");
mysql_query("ALTER TABLE `contrat_delegation` DROP column mail ");
mysql_query("ALTER TABLE `contrat_delegation` DROP column etat_matrimonial");
mysql_query("ALTER TABLE `contrat_delegation` DROP column securite_sociale");
mysql_query("ALTER TABLE `contrat_delegation` DROP column statut");
mysql_query("ALTER TABLE `contrat_delegation` DROP column nationalite");
mysql_query("ALTER TABLE `contrat_delegation` DROP column type_embauche");
mysql_query("ALTER TABLE `contrat_delegation` DROP column profil");
mysql_query("ALTER TABLE `contrat_delegation` DROP column fin_cdd");
mysql_query("ALTER TABLE `contrat_delegation` DROP column Id_service");


?>