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

mysql_query("ALTER TABLE `planning` change date_fin date_fin_commande DATE");
mysql_query("ALTER TABLE `planning` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `listing_affaires` change fin date_fin_commande DATE");
mysql_query("ALTER TABLE `listing_affaires` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `planning_session` ADD column dateFinPrev DATE");

mysql_query("ALTER TABLE `budget` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");

$resultat = mysql_query("SELECT Id_affaire, date_fin_commande FROM planning");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
	mysql_query('UPDATE listing_affaires set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
}

$resultat = mysql_query("SELECT Id_session, dateFin FROM planning_session");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning_session set dateFinPrev="'.$tableau['dateFin'].'" WHERE Id_session='.$tableau['Id_session'].'');
}


	$mysqli = new mysqli('localhost', 'root', '', 'AGC_PROSERVIA');
	if (mysqli_connect_errno()) {
		echo 'Echec lors de la connexion MYSQL.<br />';
	}
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource','../membre/index.php?a=consulterDemandeRessource','g');");
	$id_g = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource',' 	../membre/index.php?a=demander_ressource','d');");
	$id_d = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_d.");");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `planning` change date_fin date_fin_commande DATE");
mysql_query("ALTER TABLE `planning` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `listing_affaires` change fin date_fin_commande DATE");
mysql_query("ALTER TABLE `listing_affaires` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `planning_session` ADD column dateFinPrev DATE");

mysql_query("ALTER TABLE `budget` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");

$resultat = mysql_query("SELECT Id_affaire, date_fin_commande FROM planning");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
	mysql_query('UPDATE listing_affaires set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
}

$resultat = mysql_query("SELECT Id_session, dateFin FROM planning_session");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning_session set dateFinPrev="'.$tableau['dateFin'].'" WHERE Id_session='.$tableau['Id_session'].'');
}

	$mysqli = new mysqli('localhost', 'root', '', 'AGC_OVIALIS');
	if (mysqli_connect_errno()) {
		echo 'Echec lors de la connexion MYSQL.<br />';
	}
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource','../membre/index.php?a=consulterDemandeRessource','g');");
	$id_g = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource',' 	../membre/index.php?a=demander_ressource','d');");
	$id_d = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_d.");");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `planning` change date_fin date_fin_commande DATE");
mysql_query("ALTER TABLE `planning` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `listing_affaires` change fin date_fin_commande DATE");
mysql_query("ALTER TABLE `listing_affaires` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `planning_session` ADD column dateFinPrev DATE");

mysql_query("ALTER TABLE `budget` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");

$resultat = mysql_query("SELECT Id_affaire, date_fin_commande FROM planning");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
	mysql_query('UPDATE listing_affaires set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
}

$resultat = mysql_query("SELECT Id_session, dateFin FROM planning_session");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning_session set dateFinPrev="'.$tableau['dateFin'].'" WHERE Id_session='.$tableau['Id_session'].'');
}

	$mysqli = new mysqli('localhost', 'root', '', 'AGC_WIZTIVI');
	if (mysqli_connect_errno()) {
		echo 'Echec lors de la connexion MYSQL.<br />';
	}
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource','../membre/index.php?a=consulterDemandeRessource','g');");
	$id_g = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource',' 	../membre/index.php?a=demander_ressource','d');");
	$id_d = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_d.");");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `planning` change date_fin date_fin_commande DATE");
mysql_query("ALTER TABLE `planning` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `listing_affaires` change fin date_fin_commande DATE");
mysql_query("ALTER TABLE `listing_affaires` ADD column date_fin_previsionnelle DATE");
mysql_query("ALTER TABLE `planning_session` ADD column dateFinPrev DATE");

mysql_query("ALTER TABLE `budget` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `stats_ca` change ca_probable ca_commande DOUBLE NOT NULL DEFAULT 0");

$resultat = mysql_query("SELECT Id_affaire, date_fin_commande FROM planning");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
	mysql_query('UPDATE listing_affaires set date_fin_previsionnelle="'.$tableau['date_fin_commande'].'" WHERE Id_affaire='.$tableau['Id_affaire'].'');
}

$resultat = mysql_query("SELECT Id_session, dateFin FROM planning_session");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE planning_session set dateFinPrev="'.$tableau['dateFin'].'" WHERE Id_session='.$tableau['Id_session'].'');
}

	$mysqli = new mysqli('localhost', 'root', '', 'AGC_NEEDPROFILE');
	if (mysqli_connect_errno()) {
		echo 'Echec lors de la connexion MYSQL.<br />';
	}
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource','../membre/index.php?a=consulterDemandeRessource','g');");
	$id_g = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO menu VALUES ('','Demande Ressource',' 	../membre/index.php?a=demander_ressource','d');");
	$id_d = $mysqli->insert_id;
	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_g.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (41,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (32,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (14,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (8,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (2,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (1,".$id_d.");");	
	$mysqli->query("INSERT INTO groupe_ad_menu VALUES (43,".$id_d.");");


?>