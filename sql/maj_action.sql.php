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


mysql_query("ALTER TABLE `action` change tache objet VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column responsable VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_rendezvous VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_affaire VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_compte VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_contact VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column demandeur VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column executant VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` DROP column nature");

$resultat = mysql_query("SELECT * FROM action");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE action SET createur="'.strtolower($tableau['createur']).'", demandeur="'.strtolower($tableau['createur']).'", executant="'.strtolower($tableau['createur']).'",responsable="'.strtolower($tableau['responsable']).'" WHERE Id_action="'.$tableau['Id_action'].'"');
}


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `action` change tache objet VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column responsable VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_rendezvous VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_affaire VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_compte VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_contact VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column demandeur VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column executant VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` DROP column nature");

$resultat = mysql_query("SELECT * FROM action");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE action SET createur="'.strtolower($tableau['createur']).'", demandeur="'.strtolower($tableau['createur']).'", executant="'.strtolower($tableau['createur']).'",responsable="'.strtolower($tableau['responsable']).'" WHERE Id_action="'.$tableau['Id_action'].'"');
}


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `action` change tache objet VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column responsable VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_rendezvous VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_affaire VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_compte VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column Id_contact VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column demandeur VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` add column executant VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `action` DROP column nature");

$resultat = mysql_query("SELECT * FROM action");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE action SET createur="'.strtolower($tableau['createur']).'", demandeur="'.strtolower($tableau['createur']).'", executant="'.strtolower($tableau['createur']).'",responsable="'.strtolower($tableau['responsable']).'" WHERE Id_action="'.$tableau['Id_action'].'"');
}

?>