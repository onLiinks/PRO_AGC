<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('gescom') or die ('pas de connection'); 

mysql_query("UPDATE `candidature` SET Id_etat=19 WHERE Id_action_mener=2");
mysql_query("UPDATE `candidature` SET Id_action_mener=6 WHERE Id_action_mener=1");
mysql_query("UPDATE `candidature` SET Id_etat=17 WHERE Id_action_mener=4");

mysql_query("DROP TABLE `action_mener`");

mysql_query("
CREATE TABLE `action_mener` (
  `Id_action_mener` int(1) NOT NULL auto_increment,
  `libelle` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`Id_action_mener`)
)");

mysql_query("
INSERT INTO `action_mener` VALUES 
('', 'CV envoyé client'),
('', 'Présentation client'),
('', 'Reprise de contact'),
('', 'A relancer'),
('', 'CV envoyé au commercial'),
('', 'Attente retour candidat')
");

mysql_query("
INSERT INTO `etat_candidature` VALUES
('', 'Annulé RDV'),
('', 'Entretien tél non validé'),
('', 'Ne donne pas suite')
");

mysql_query("DELETE FROM `etat_candidature` WHERE Id_etat_candidature=11");
mysql_query("DELETE FROM `etat_candidature` WHERE Id_etat_candidature=12");
mysql_query("DELETE FROM `etat_candidature` WHERE Id_etat_candidature=13");
mysql_query("DELETE FROM `etat_candidature` WHERE Id_etat_candidature=14");


$resultat = mysql_query("SELECT Id_candidature FROM candidature WHERE Id_etat=11");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE candidature set Id_action_mener=1, Id_etat="" where Id_candidature='.$tableau['Id_candidature'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_candidature FROM candidature WHERE Id_etat=12");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE candidature set Id_action_mener=2, Id_etat="" where Id_candidature='.$tableau['Id_candidature'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_candidature FROM candidature WHERE Id_etat=13");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE candidature set Id_action_mener=3, Id_etat="" where Id_candidature='.$tableau['Id_candidature'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_candidature FROM candidature WHERE Id_etat=14");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE candidature set Id_action_mener=4, Id_etat="" where Id_candidature='.$tableau['Id_candidature'].'') or die ('Erreur !');
}