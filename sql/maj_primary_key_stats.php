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
mysql_query("ALTER TABLE `stats_hebdo_cr` DROP PRIMARY KEY");
mysql_query("ALTER TABLE `stats_hebdo_agence` DROP PRIMARY KEY");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');
mysql_query("ALTER TABLE `stats_hebdo_cr` DROP PRIMARY KEY");
mysql_query("ALTER TABLE `stats_hebdo_agence` DROP PRIMARY KEY");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');
mysql_query("ALTER TABLE `stats_hebdo_cr` DROP PRIMARY KEY");
mysql_query("ALTER TABLE `stats_hebdo_agence` DROP PRIMARY KEY");


//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');
mysql_query("ALTER TABLE `stats_hebdo_cr` DROP PRIMARY KEY");
mysql_query("ALTER TABLE `stats_hebdo_agence` DROP PRIMARY KEY");
?>