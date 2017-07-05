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


$req = mysql_query("UPDATE affaire SET Id_statut=9 WHERE Id_affaire IN (1196,1185,54,1210,7,1232,2533,1228,1193,693,1214,1211,121,897,1208,1222,402,212,1148,185,1202,694,1233,1235,585,1234,588,1194,993,584,531,528,527,525,1199,62,756,586,1378,1215,61,218,1224,540,97,1195,1192,1186,1212,1189,1190,81,1221,1200,1184,1236,1203,1201,20,1223,184,1191,1207,686,1187,1625,1209,1198,168)");

$tabaffaire = array(1196,1185,54,1210,7,1232,2533,1228,1193,693,1214,1211,121,897,1208,1222,402,212,1148,185,1202,694,1233,1235,585,1234,588,1194,993,584,531,528,527,525,1199,62,756,586,1378,1215,61,218,1224,540,97,1195,1192,1186,1212,1189,1190,81,1221,1200,1184,1236,1203,1201,20,1223,184,1191,1207,686,1187,1625,1209,1198,168);
$nb = count($tabaffaire);

$i = 0;
while ($i < $nb) {
    mysql_query('INSERT INTO historique_statut SET Id_affaire="'.$tabaffaire[$i].'", date="2009-05-12 09:36:00", Id_statut=9, Id_utilisateur="anthony.anne",commentaire="MAJ automatique des statuts à la demande de Nathalie B"');
	++$i;
}

?>