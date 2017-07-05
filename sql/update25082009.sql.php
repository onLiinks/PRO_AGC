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

mysql_query("DROP TABLE `budget_annuel`");
mysql_query("ALTER TABLE `budget` add COLUMN ca_recurrent INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `budget` add COLUMN ca_a_trouver INT NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("DROP TABLE `budget_annuel`");
mysql_query("ALTER TABLE `budget` add COLUMN ca_recurrent INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `budget` add COLUMN ca_a_trouver INT NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("DROP TABLE `budget_annuel`");
mysql_query("ALTER TABLE `budget` add COLUMN ca_recurrent INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `budget` add COLUMN ca_a_trouver INT NOT NULL DEFAULT '0'");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("DROP TABLE `budget_annuel`");
mysql_query("ALTER TABLE `budget` add COLUMN ca_recurrent INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `budget` add COLUMN ca_a_trouver INT NOT NULL DEFAULT '0'");


?>