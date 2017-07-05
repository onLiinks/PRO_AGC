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

mysql_query("ALTER TABLE `com_type_contrat` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `com_type_contrat` SET trig='AT' WHERE Id_type_contrat=1");
mysql_query("UPDATE `com_type_contrat` SET trig='INF' WHERE Id_type_contrat=2");
mysql_query("UPDATE `com_type_contrat` SET trig='PF' WHERE Id_type_contrat=3");

mysql_query("ALTER TABLE `statut` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `statut` SET trig='PIS' WHERE Id_statut=1");
mysql_query("UPDATE `statut` SET trig='NTR' WHERE Id_statut=2");
mysql_query("UPDATE `statut` SET trig='ECR' WHERE Id_statut=3");
mysql_query("UPDATE `statut` SET trig='REM' WHERE Id_statut=4");
mysql_query("UPDATE `statut` SET trig='SIG' WHERE Id_statut=5");
mysql_query("UPDATE `statut` SET trig='PER' WHERE Id_statut=6");
mysql_query("UPDATE `statut` SET trig='AQU' WHERE Id_statut=7");
mysql_query("UPDATE `statut` SET trig='OPE' WHERE Id_statut=8");
mysql_query("UPDATE `statut` SET trig='TER' WHERE Id_statut=9");




//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `com_type_contrat` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `com_type_contrat` SET trig='AT' WHERE Id_type_contrat=1");
mysql_query("UPDATE `com_type_contrat` SET trig='INF' WHERE Id_type_contrat=2");
mysql_query("UPDATE `com_type_contrat` SET trig='PF' WHERE Id_type_contrat=3");

mysql_query("ALTER TABLE `statut` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `statut` SET trig='PIS' WHERE Id_statut=1");
mysql_query("UPDATE `statut` SET trig='NTR' WHERE Id_statut=2");
mysql_query("UPDATE `statut` SET trig='ECR' WHERE Id_statut=3");
mysql_query("UPDATE `statut` SET trig='REM' WHERE Id_statut=4");
mysql_query("UPDATE `statut` SET trig='SIG' WHERE Id_statut=5");
mysql_query("UPDATE `statut` SET trig='PER' WHERE Id_statut=6");
mysql_query("UPDATE `statut` SET trig='AQU' WHERE Id_statut=7");
mysql_query("UPDATE `statut` SET trig='OPE' WHERE Id_statut=8");
mysql_query("UPDATE `statut` SET trig='TER' WHERE Id_statut=9");




//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `com_type_contrat` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `com_type_contrat` SET trig='AT' WHERE Id_type_contrat=1");
mysql_query("UPDATE `com_type_contrat` SET trig='INF' WHERE Id_type_contrat=2");
mysql_query("UPDATE `com_type_contrat` SET trig='PF' WHERE Id_type_contrat=3");

mysql_query("ALTER TABLE `statut` add column trig VARCHAR(5) NOT NULL DEFAULT ''");
mysql_query("UPDATE `statut` SET trig='PIS' WHERE Id_statut=1");
mysql_query("UPDATE `statut` SET trig='NTR' WHERE Id_statut=2");
mysql_query("UPDATE `statut` SET trig='ECR' WHERE Id_statut=3");
mysql_query("UPDATE `statut` SET trig='REM' WHERE Id_statut=4");
mysql_query("UPDATE `statut` SET trig='SIG' WHERE Id_statut=5");
mysql_query("UPDATE `statut` SET trig='PER' WHERE Id_statut=6");
mysql_query("UPDATE `statut` SET trig='AQU' WHERE Id_statut=7");
mysql_query("UPDATE `statut` SET trig='OPE' WHERE Id_statut=8");
mysql_query("UPDATE `statut` SET trig='TER' WHERE Id_statut=9");

?>