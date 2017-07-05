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

mysql_query("ALTER TABLE `action` DROP column responsable");
mysql_query("ALTER TABLE `budget` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `proposition_ressource` ADD column fin_prev DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `stats_ca` DROP column ca_commande");
mysql_query("ALTER TABLE `rendezvous` ADD column Id_agence VARCHAR(255) NOT NULL DEFAULT ''");

$resultat = mysql_query("SELECT fin FROM proposition_ressource");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE proposition_ressource set fin_prev="'.$tableau['fin'].'" WHERE fin="'.$tableau['fin'].'"');
}

$resultat = mysql_query("SELECT Id_rendezvous,createur,utilisateur.Id_agence FROM rendezvous INNER JOIN utilisateur ON utilisateur.Id_utilisateur=rendezvous.createur");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE rendezvous set Id_agence="'.$tableau['Id_agence'].'" WHERE Id_rendezvous="'.$tableau['Id_rendezvous'].'"');
}

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `action` DROP column responsable");
mysql_query("ALTER TABLE `budget` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `proposition_ressource` ADD column fin_prev DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `stats_ca` DROP column ca_commande");
mysql_query("ALTER TABLE `rendezvous` ADD column Id_agence VARCHAR(255) NOT NULL DEFAULT ''");

$resultat = mysql_query("SELECT fin FROM proposition_ressource");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE proposition_ressource set fin_prev="'.$tableau['fin'].'" WHERE fin="'.$tableau['fin'].'"');
}

$resultat = mysql_query("SELECT Id_rendezvous,createur,utilisateur.Id_agence FROM rendezvous INNER JOIN utilisateur ON utilisateur.Id_utilisateur=rendezvous.createur");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE rendezvous set Id_agence="'.$tableau['Id_agence'].'" WHERE Id_rendezvous="'.$tableau['Id_rendezvous'].'"');
}

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `action` DROP column responsable");
mysql_query("ALTER TABLE `budget` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `proposition_ressource` ADD column fin_prev DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `stats_ca` DROP column ca_commande");
mysql_query("ALTER TABLE `rendezvous` ADD column Id_agence VARCHAR(255) NOT NULL DEFAULT ''");

$resultat = mysql_query("SELECT fin FROM proposition_ressource");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE proposition_ressource set fin_prev="'.$tableau['fin'].'" WHERE fin="'.$tableau['fin'].'"');
}

$resultat = mysql_query("SELECT Id_rendezvous,createur,utilisateur.Id_agence FROM rendezvous INNER JOIN utilisateur ON utilisateur.Id_utilisateur=rendezvous.createur");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE rendezvous set Id_agence="'.$tableau['Id_agence'].'" WHERE Id_rendezvous="'.$tableau['Id_rendezvous'].'"');
}

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `action` DROP column responsable");
mysql_query("ALTER TABLE `budget` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` ADD column ca_probable DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `proposition_ressource` ADD column fin_prev DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `stats_ca` DROP column ca_commande");
mysql_query("ALTER TABLE `rendezvous` ADD column Id_agence VARCHAR(255) NOT NULL DEFAULT ''");

$resultat = mysql_query("SELECT fin FROM proposition_ressource");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE proposition_ressource set fin_prev="'.$tableau['fin'].'" WHERE fin="'.$tableau['fin'].'"');
}

$resultat = mysql_query("SELECT Id_rendezvous,createur,utilisateur.Id_agence FROM rendezvous INNER JOIN utilisateur ON utilisateur.Id_utilisateur=rendezvous.createur");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE rendezvous set Id_agence="'.$tableau['Id_agence'].'" WHERE Id_rendezvous="'.$tableau['Id_rendezvous'].'"');
}

?>