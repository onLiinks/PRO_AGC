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

mysql_query("ALTER TABLE `entretien` ADD `ref_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_client2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client2` VARCHAR(255) NOT NULL");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `entretien` ADD `ref_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_client2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client2` VARCHAR(255) NOT NULL");




//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `entretien` ADD `ref_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_client2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client2` VARCHAR(255) NOT NULL");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `entretien` ADD `ref_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client1` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_client2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `ref_contact2` VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE `entretien` ADD `tel_client2` VARCHAR(255) NOT NULL");

?>