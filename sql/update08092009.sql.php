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

mysql_query("ALTER TABLE `budget` CHANGE ca_previsionnel ca_probable DOUBLE NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `budget` CHANGE ca_previsionnel ca_probable DOUBLE NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `budget` CHANGE ca_previsionnel ca_probable DOUBLE NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `budget` CHANGE ca_previsionnel ca_probable DOUBLE NOT NULL DEFAULT '0'");


?>