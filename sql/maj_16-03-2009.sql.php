<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA2') or die ('pas de connection');mysql_query("ALTER TABLE `proposition` add column typeAT VARCHAR(15) NOT NULL DEFAULT ''");mysql_query("ALTER TABLE `proposition` add column typeInfoG VARCHAR(20) NOT NULL DEFAULT ''");mysql_query("ALTER TABLE `proposition` add column pct_site DOUBLE NOT NULL DEFAULT 0");mysql_query("ALTER TABLE `proposition` add column pct_dist DOUBLE NOT NULL DEFAULT 0");$resultat = mysql_query("SELECT Id_affaire,typeAT,typeInfog FROM description");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE proposition SET typeAT="'.$tableau['typeAT'].'", typeInfog="'.$tableau['typeInfog'].'", pct_site="'.$tableau['pct_site'].'", pct_dist="'.$tableau['pct_dist'].'" WHERE Id_affaire="'.$tableau['Id_affaire'].'"');
}mysql_query("ALTER TABLE `description` DROP typeAT");mysql_query("ALTER TABLE `description` DROP typeInfoG");mysql_query("ALTER TABLE `description` DROP pct_site");mysql_query("ALTER TABLE `description` DROP pct_dist");
mysql_query("ALTER TABLE `budget` DROP ca");
mysql_query("ALTER TABLE `budget` add column ca_budget DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `budget` add column ca_facture DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `budget` add column ca_regule DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `budget` add column ca_commande DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `budget` add column Id_affaire INT NOT NULL DEFAULT 0");

mysql_query("ALTER TABLE `proposition_ressource` add column inclus INT(1) NOT NULL DEFAULT '0'");

$resultat2 = mysql_query("SELECT Id_proposition FROM proposition_ressource WHERE prix_inclus!=''");
while ($tableau2 = mysql_fetch_array($resultat2)) {
    mysql_query('UPDATE proposition SET inclus="1" WHERE Id_proposition="'.$tableau2['Id_proposition'].'"');
}

mysql_query("ALTER TABLE `proposition_ressource` DROP prix_inclus");
mysql_query("DROP TABLE `prestation`");



mysql_query("ALTER TABLE `contrat_delegation` add column nom VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column adresse VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column tel_fixe VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column tel_portable VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column mail VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column etat_matrimonial VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column securite_sociale VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column statut VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column nationalite VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column type_embauche VARCHAR(3) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column profil VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `contrat_delegation` add column fin_cdd DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `contrat_delegation` add column Id_service VARCHAR(255) NOT NULL DEFAULT ''");

?>