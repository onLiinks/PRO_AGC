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

mysql_query("
INSERT INTO `specialite` VALUES
('','Stockage'),
('','Sauvegarde'),
('','Syst�mes Multi OS'),
('','Production - Windows'),
('','Production - Open Source'),
('','Production - Multi OS'),
('','Avant vente'),
('','Gouvernance / m�thodo'),
('','Profil CPC'),
('','Profil CNO'),
('','Comp�tences fonctionnelles'),
('','Back up'),
('','CSPN'),
('','Mesasgerie'),
('','Profils dev')
");

mysql_query("
CREATE TABLE `ressource_specialite` (
  `Id_ressource` INT(1) NULL default '0',
  `Id_specialite` INT(1) NULL default '0',
  PRIMARY KEY  (`Id_ressource`, `Id_specialite`)
)");


mysql_query("UPDATE specialite SET libelle='Fonction Hors Informatique' WHERE Id_specialite=37");

$resultat = mysql_query("SELECT Id_specialite,Id_ressource FROM ressource WHERE Id_specialite!=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO ressource_specialite set Id_ressource="'.$tableau['Id_ressource'].'",Id_specialite='.$tableau['Id_specialite'].'');
}

mysql_query("ALTER TABLE `ressource` DROP column Id_specialite");
mysql_query("ALTER TABLE `candidature` DROP column stage");
mysql_query("ALTER TABLE `candidature` DROP column independant");
mysql_query("ALTER TABLE `candidature` DROP column alternance");
mysql_query("ALTER TABLE `description` DROP column Id_profil1");
mysql_query("ALTER TABLE `description` DROP column Id_profil2");
mysql_query("ALTER TABLE `description` DROP column Id_cursus");
mysql_query("ALTER TABLE `description` DROP column experience_requise");

?>