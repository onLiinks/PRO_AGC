<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

mysql_query("ALTER TABLE `description` add column typeInfog VARCHAR(20) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `description` add column pct_site DOUBLE NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `description` add column pct_dist DOUBLE NOT NULL DEFAULT 0");

mysql_query("DROP TABLE com_type_contrat");

mysql_query("
CREATE TABLE `com_type_contrat` (
	`Id_type_contrat` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	`pole` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_type_contrat` ) 
)");

mysql_query("
INSERT INTO `com_type_contrat` VALUES
('','Assistance Technique','1,2,4,5'),
('','Infogérance / Forfait de service','1,2'),
('','Projet Forfaitaire','1,2,3,4,5')
");

$requete = 'UPDATE affaire SET Id_type_contrat=7 WHERE Id_type_contrat=1';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 1');


$requete2 = 'SELECT Id_affaire FROM affaire WHERE Id_type_contrat=2';
$resultat2 = mysql_query($requete2,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat2,MYSQL_ASSOC))
{
    $requete3 = 'UPDATE description SET typeInfog="Sur Site" WHERE Id_affaire="'.$tableau['Id_affaire'].'"';
    mysql_query($requete3,$id_connexion) or die ('Echec SELECT date de modification');}

$requete2 = 'SELECT Id_affaire FROM affaire WHERE Id_type_contrat=3';
$resultat2 = mysql_query($requete2,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat2,MYSQL_ASSOC))
{
    $requete3 = 'UPDATE description SET typeInfog="A distance" WHERE Id_affaire="'.$tableau['Id_affaire'].'"';
    mysql_query($requete3,$id_connexion) or die ('Echec SELECT date de modification');}

$requete2 = 'SELECT Id_affaire FROM affaire WHERE Id_type_contrat=4';
$resultat2 = mysql_query($requete2,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat2,MYSQL_ASSOC))
{
    $requete3 = 'UPDATE description SET typeInfog="Couplée" WHERE Id_affaire="'.$tableau['Id_affaire'].'"';
    mysql_query($requete3,$id_connexion) or die ('Echec SELECT date de modification');}

$requete = 'UPDATE affaire SET Id_type_contrat=8 WHERE Id_type_contrat IN (2,3,4)';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 2');

$requete = 'UPDATE affaire SET Id_type_contrat=9 WHERE Id_type_contrat=5';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 3');
$requete = 'UPDATE affaire SET Id_type_contrat=1 WHERE Id_type_contrat=7';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 4');

$requete = 'UPDATE affaire SET Id_type_contrat=2 WHERE Id_type_contrat=8';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 5');

$requete = 'UPDATE affaire SET Id_type_contrat=3 WHERE Id_type_contrat=9';
mysql_query($requete,$id_connexion) or die ('Echec UPDATE 6');



$requete = 'SELECT DISTINCT aff.Id_affaire as Id_aff,
(SELECT min(debut) FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition and debut!="0000-00-00") as mindate,
(SELECT max(fin) as maxdate FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition) as maxdate
FROM affaire aff INNER JOIN proposition p1 ON p1.Id_affaire=aff.Id_affaire
INNER JOIN proposition_ressource ON p1.Id_proposition=proposition_ressource.Id_proposition 
and Id_type_contrat=1 and debut !="0000-00-00" and fin !="0000-00-00"';
$resultat = mysql_query($requete,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
    $requete2 = 'UPDATE planning SET date_debut="'.$tableau['mindate'].'", date_fin="'.$tableau['maxdate'].'" WHERE Id_affaire="'.$tableau['Id_aff'].'"';
    mysql_query($requete2,$id_connexion) or die ('Echec UPDATE planning');}



mysql_query("
CREATE TABLE `budget` (
	`type` VARCHAR(255) NOT NULL default '',
	`societe` VARCHAR(255) NOT NULL default '',
	`mois` VARCHAR(7) NOT NULL default '',
	`commercial` VARCHAR(50) NOT NULL default '',
	`Id_agence` VARCHAR(10) NOT NULL default '',
	`Id_pole` INT(9) NOT NULL default '0',
	`Id_type_contrat` INT(9) NOT NULL default '0',
	`ca` DOUBLE NOT NULL default '0'
)");


mysql_query("CREATE TABLE mois(
    aaaamm char(6), 
	debmois date, 
	finmois date
)");


$a=2008;
$k='01';
while ($a < 2016) {
    $i=1;
    while ($i < 13) {
	    $j = ($i < 10 ) ? '0'.$i : $i;
		$m = mktime( 0, 0, 0, $j, 1, $a );
	    $nb = date('t',$m);
		mysql_query("INSERT INTO mois VALUES ($a$j, $a$j$k, $a$j$nb)");
	    ++$i;
	}
    ++$a;
}



?>