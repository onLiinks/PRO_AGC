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


mysql_query("DROP TABLE `region`");
mysql_query("DROP TABLE `departement`");
mysql_query("DROP TABLE `situation`");
mysql_query("DROP TABLE `annonce`");
mysql_query("DROP TABLE `cursus`");

mysql_query("ALTER TABLE `candidature` add column independant INT(1) NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE `entretien` add column tarif_journalier DOUBLE");

mysql_query("alter table profil change nom libelle varchar(255)");
mysql_query("alter table nationalite change nom libelle varchar(50)");
mysql_query("alter table disponibilite change nom libelle varchar(20)");

mysql_query("alter table entretien_langue change Id_niveau Id_niveau_langue INT(1)");
mysql_query("alter table entretien change dispo_nego dispo_negociable INT(1)");
mysql_query("alter table entretien change ps_inf pretention_basse double");
mysql_query("alter table entretien change ps_sup pretention_haute double");

mysql_query("alter table ressource change lieu_naissance ville_naissance varchar(255)");
mysql_query("alter table ressource change departement Id_dpt_naiss varchar(3)");
mysql_query("alter table ressource change Id_nationalite Id_nationalite INT(3)");
mysql_query("alter table ressource change Id_situation Id_etat_matrimonial INT(2)");
mysql_query("alter table ressource change Id_pays Id_pays_residence INT(3)");

mysql_query("alter table ressource add column Id_exp_info INT(1)");

mysql_query("update ressource set civilite='M' where civilite='Mr'");

mysql_query("update ressource set Id_pays_residence ='72' where Id_pays_residence='1'");
mysql_query("update ressource set Id_pays_residence ='121' where Id_pays_residence='152'");
mysql_query("update ressource set Id_pays_residence ='201' where Id_pays_residence='57'");

mysql_query("UPDATE entretien_langue SET Id_niveau_langue=4 WHERE Id_niveau_langue=3");
mysql_query("UPDATE entretien_langue SET Id_niveau_langue=3 WHERE Id_niveau_langue=2");
mysql_query("UPDATE entretien_langue SET Id_niveau_langue=2 WHERE Id_niveau_langue=1");


mysql_query("DROP TABLE `mobilite`");

mysql_query("alter table entretien_mobilite change Id_mobilite Id_mobilite varchar(20)");


$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=1");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-6"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-19"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-21"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite='1'");

$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=2");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-1"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-11"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=2");


$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=4");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-9"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-22"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-23"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=4");

$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=5");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-2"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-14"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-17"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=5");


$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=6");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-8"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-16"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-18"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-20"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=6");


$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=7");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-4"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-12"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=7");

$resultat = mysql_query("SELECT Id_entretien FROM entretien_mobilite WHERE Id_mobilite=8");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-7"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-3"');
	mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-5"');
    mysql_query('INSERT INTO entretien_mobilite set Id_entretien='.$tableau['Id_entretien'].', Id_mobilite="1-15"');
}
$resultat = mysql_query("delete FROM entretien_mobilite WHERE Id_mobilite=8");


mysql_query("update entretien_mobilite set Id_mobilite ='1-13' where Id_mobilite='3'");
mysql_query("update entretien_mobilite set Id_mobilite ='-1' where Id_mobilite='9'");
mysql_query("update entretien_mobilite set Id_mobilite ='-2' where Id_mobilite='10'");
mysql_query("update entretien_mobilite set Id_mobilite ='-3' where Id_mobilite='11'");
mysql_query("update entretien_mobilite set Id_mobilite ='-4' where Id_mobilite='12'");
mysql_query("update entretien_mobilite set Id_mobilite ='-5' where Id_mobilite='13'");
mysql_query("update entretien_mobilite set Id_mobilite ='-6' where Id_mobilite='14'");
mysql_query("update entretien_mobilite set Id_mobilite ='-7' where Id_mobilite='15'");
mysql_query("update entretien_mobilite set Id_mobilite ='-8' where Id_mobilite='16'");
mysql_query("update entretien_mobilite set Id_mobilite ='-9' where Id_mobilite='17'");
mysql_query("update entretien_mobilite set Id_mobilite ='-10' where Id_mobilite='18'");
mysql_query("update entretien_mobilite set Id_mobilite ='-11' where Id_mobilite='19'");
mysql_query("update entretien_mobilite set Id_mobilite ='-12' where Id_mobilite='20'");
mysql_query("update entretien_mobilite set Id_mobilite ='-13' where Id_mobilite='21'");
mysql_query("update entretien_mobilite set Id_mobilite ='-14' where Id_mobilite='22'");
mysql_query("update entretien_mobilite set Id_mobilite ='-15' where Id_mobilite='23'");
mysql_query("update entretien_mobilite set Id_mobilite ='-16' where Id_mobilite='24'");
mysql_query("update entretien_mobilite set Id_mobilite ='-17' where Id_mobilite='25'");
mysql_query("update entretien_mobilite set Id_mobilite ='-18' where Id_mobilite='26'");
mysql_query("update entretien_mobilite set Id_mobilite ='-19' where Id_mobilite='27'");

mysql_query("DROP TABLE `certification`");

mysql_query("
CREATE TABLE `certification` (
	`Id_certification` INT(3) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	`Id_cat_cert` INT(3) NOT NULL default '0',
	PRIMARY KEY ( `Id_certification` )
)");

mysql_query("
INSERT INTO `certification` VALUES 
('','MCP','1'),
('','MCTS','1'),
('','MCPD','1'),
('','MCDST','1'),
('','MCSA','1'),
('','MCSE','1'),
('','MCAD','1'),
('','MCSD.NET','1'),
('','MCDBA','1'),
('','MCT','1'),
('','MCITP Server Administrator','1'),
('','MCITP Enterprise Administrator','1'),
('','CCENT','2'),
('','CCNA','2'),
('','CCDA','2'),
('','CCNP','2'),
('','CCDP','2'),
('','CCIP','2'),
('','CCSP','2'),
('','CCVP','2'),
('','CCIE','2'),
('','CCA','3'),
('','CCEA','3'),
('','CCIA','3'),
('','LPI','4')
");


mysql_query("
CREATE TABLE `categorie_certification` (
	`Id_cat_cert` INT(3) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_cat_cert` )
)");

mysql_query("
INSERT INTO `categorie_certification` VALUES 
('','Microsoft'),
('','Cisco'),
('','Citrix'),
('','Linux')
");

mysql_query("
CREATE TABLE `entretien_certification` (
	`Id_entretien` INT(3) NOT NULL default '0',
	`Id_certification` INT(3) NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien`, `Id_certification` )
)");

mysql_query("
CREATE TABLE `entretien_competence` (
	`Id_entretien` INT(3) NOT NULL default '0',
	`Id_competence` INT(3) NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien`, `Id_competence` )
)");

mysql_query("DROP TABLE `profil`");

mysql_query("
CREATE TABLE `profil` (
	`Id_profil` INT(3) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_profil` ) 
)");

mysql_query("
INSERT INTO `profil` VALUES 
('','Chef de Projet'),
('','Ingenieur Systèmes et Réseaux'),
('','Architecte logiciel'),
('','Administrateur Base de Données'),
('','Ingénieur d\'études et développement'),
('','Technicien Réseaux et Télécoms'),
('','Analyste d\'exploitation'),
('','Technicien d\'exploitation'),
('','Technicien Systèmes Réseaux'),
('','Responsable Technique'),
('','Ingénieur Sécurité'),
('','Ingénieur Réseaux / Télécoms'),
('','Admin Systèmes et Réseaux'),
('','Développeur'),
('','Ingénieur de Production'),
('','Architecte Technique'),
('','Intégrateur d\'exploitation'),
('','Directeur de projet'),
('','Analyste fonctionnel'),
('','Consultant en SI'),
('','Expert Technique'),
('','Chef de projet Infogérance'),
('','Responsable de Projet Infogérance')
");

mysql_query("UPDATE description SET Id_profil1=101 WHERE Id_profil1=2");
mysql_query("UPDATE description SET Id_profil1=102 WHERE Id_profil1=4");
mysql_query("UPDATE description SET Id_profil1=103 WHERE Id_profil1=45");
mysql_query("UPDATE description SET Id_profil1=104 WHERE Id_profil1=8");
mysql_query("UPDATE description SET Id_profil1=105 WHERE Id_profil1=44");
mysql_query("UPDATE description SET Id_profil1=106 WHERE Id_profil1=12");
mysql_query("UPDATE description SET Id_profil1=107 WHERE Id_profil1=14");
mysql_query("UPDATE description SET Id_profil1=108 WHERE Id_profil1=16");
mysql_query("UPDATE description SET Id_profil1=109 WHERE Id_profil1=43");
mysql_query("UPDATE description SET Id_profil1=110 WHERE Id_profil1=52");
mysql_query("UPDATE description SET Id_profil1=111 WHERE Id_profil1=42");
mysql_query("UPDATE description SET Id_profil1=112 WHERE Id_profil1=41");
mysql_query("UPDATE description SET Id_profil1=113 WHERE Id_profil1=32");
mysql_query("UPDATE description SET Id_profil1=114 WHERE Id_profil1=34");
mysql_query("UPDATE description SET Id_profil1=115 WHERE Id_profil1=40");
mysql_query("UPDATE description SET Id_profil1=116 WHERE Id_profil1=37");
mysql_query("UPDATE description SET Id_profil1=117 WHERE Id_profil1=39");
mysql_query("UPDATE description SET Id_profil1=118 WHERE Id_profil1=46");
mysql_query("UPDATE description SET Id_profil1=119 WHERE Id_profil1=47");
mysql_query("UPDATE description SET Id_profil1=120 WHERE Id_profil1=48");
mysql_query("UPDATE description SET Id_profil1=121 WHERE Id_profil1=49");
mysql_query("UPDATE description SET Id_profil1=122 WHERE Id_profil1=50");
mysql_query("UPDATE description SET Id_profil1=123 WHERE Id_profil1=51");

mysql_query("UPDATE description SET Id_profil1=1 WHERE Id_profil1=101");
mysql_query("UPDATE description SET Id_profil1=2 WHERE Id_profil1=102");
mysql_query("UPDATE description SET Id_profil1=3 WHERE Id_profil1=103");
mysql_query("UPDATE description SET Id_profil1=4 WHERE Id_profil1=104");
mysql_query("UPDATE description SET Id_profil1=5 WHERE Id_profil1=105");
mysql_query("UPDATE description SET Id_profil1=6 WHERE Id_profil1=106");
mysql_query("UPDATE description SET Id_profil1=7 WHERE Id_profil1=107");
mysql_query("UPDATE description SET Id_profil1=8 WHERE Id_profil1=108");
mysql_query("UPDATE description SET Id_profil1=9 WHERE Id_profil1=109");
mysql_query("UPDATE description SET Id_profil1=10 WHERE Id_profil1=110");
mysql_query("UPDATE description SET Id_profil1=11 WHERE Id_profil1=111");
mysql_query("UPDATE description SET Id_profil1=12 WHERE Id_profil1=112");
mysql_query("UPDATE description SET Id_profil1=13 WHERE Id_profil1=113");
mysql_query("UPDATE description SET Id_profil1=14 WHERE Id_profil1=114");
mysql_query("UPDATE description SET Id_profil1=15 WHERE Id_profil1=115");
mysql_query("UPDATE description SET Id_profil1=16 WHERE Id_profil1=116");
mysql_query("UPDATE description SET Id_profil1=17 WHERE Id_profil1=117");
mysql_query("UPDATE description SET Id_profil1=18 WHERE Id_profil1=118");
mysql_query("UPDATE description SET Id_profil1=19 WHERE Id_profil1=119");
mysql_query("UPDATE description SET Id_profil1=20 WHERE Id_profil1=120");
mysql_query("UPDATE description SET Id_profil1=21 WHERE Id_profil1=121");
mysql_query("UPDATE description SET Id_profil1=22 WHERE Id_profil1=122");
mysql_query("UPDATE description SET Id_profil1=23 WHERE Id_profil1=123");


mysql_query("UPDATE description SET Id_profil2=101 WHERE Id_profil2=2");
mysql_query("UPDATE description SET Id_profil2=102 WHERE Id_profil2=4");
mysql_query("UPDATE description SET Id_profil2=103 WHERE Id_profil2=45");
mysql_query("UPDATE description SET Id_profil2=104 WHERE Id_profil2=8");
mysql_query("UPDATE description SET Id_profil2=105 WHERE Id_profil2=44");
mysql_query("UPDATE description SET Id_profil2=106 WHERE Id_profil2=12");
mysql_query("UPDATE description SET Id_profil2=107 WHERE Id_profil2=14");
mysql_query("UPDATE description SET Id_profil2=108 WHERE Id_profil2=16");
mysql_query("UPDATE description SET Id_profil2=109 WHERE Id_profil2=43");
mysql_query("UPDATE description SET Id_profil2=110 WHERE Id_profil2=52");
mysql_query("UPDATE description SET Id_profil2=111 WHERE Id_profil2=42");
mysql_query("UPDATE description SET Id_profil2=112 WHERE Id_profil2=41");
mysql_query("UPDATE description SET Id_profil2=113 WHERE Id_profil2=32");
mysql_query("UPDATE description SET Id_profil2=114 WHERE Id_profil2=34");
mysql_query("UPDATE description SET Id_profil2=115 WHERE Id_profil2=40");
mysql_query("UPDATE description SET Id_profil2=116 WHERE Id_profil2=37");
mysql_query("UPDATE description SET Id_profil2=117 WHERE Id_profil2=39");
mysql_query("UPDATE description SET Id_profil2=118 WHERE Id_profil2=46");
mysql_query("UPDATE description SET Id_profil2=119 WHERE Id_profil2=47");
mysql_query("UPDATE description SET Id_profil2=120 WHERE Id_profil2=48");
mysql_query("UPDATE description SET Id_profil2=121 WHERE Id_profil2=49");
mysql_query("UPDATE description SET Id_profil2=122 WHERE Id_profil2=50");
mysql_query("UPDATE description SET Id_profil2=123 WHERE Id_profil2=51");


mysql_query("UPDATE description SET Id_profil2=1 WHERE Id_profil2=101");
mysql_query("UPDATE description SET Id_profil2=2 WHERE Id_profil2=102");
mysql_query("UPDATE description SET Id_profil2=3 WHERE Id_profil2=103");
mysql_query("UPDATE description SET Id_profil2=4 WHERE Id_profil2=104");
mysql_query("UPDATE description SET Id_profil2=5 WHERE Id_profil2=105");
mysql_query("UPDATE description SET Id_profil2=6 WHERE Id_profil2=106");
mysql_query("UPDATE description SET Id_profil2=7 WHERE Id_profil2=107");
mysql_query("UPDATE description SET Id_profil2=8 WHERE Id_profil2=108");
mysql_query("UPDATE description SET Id_profil2=9 WHERE Id_profil2=109");
mysql_query("UPDATE description SET Id_profil2=10 WHERE Id_profil2=110");
mysql_query("UPDATE description SET Id_profil2=11 WHERE Id_profil2=111");
mysql_query("UPDATE description SET Id_profil2=12 WHERE Id_profil2=112");
mysql_query("UPDATE description SET Id_profil2=13 WHERE Id_profil2=113");
mysql_query("UPDATE description SET Id_profil2=14 WHERE Id_profil2=114");
mysql_query("UPDATE description SET Id_profil2=15 WHERE Id_profil2=115");
mysql_query("UPDATE description SET Id_profil2=16 WHERE Id_profil2=116");
mysql_query("UPDATE description SET Id_profil2=17 WHERE Id_profil2=117");
mysql_query("UPDATE description SET Id_profil2=18 WHERE Id_profil2=118");
mysql_query("UPDATE description SET Id_profil2=19 WHERE Id_profil2=119");
mysql_query("UPDATE description SET Id_profil2=20 WHERE Id_profil2=120");
mysql_query("UPDATE description SET Id_profil2=21 WHERE Id_profil2=121");
mysql_query("UPDATE description SET Id_profil2=22 WHERE Id_profil2=122");
mysql_query("UPDATE description SET Id_profil2=23 WHERE Id_profil2=123");

mysql_query("UPDATE ressource SET Id_profil=101 WHERE Id_profil=2");
mysql_query("UPDATE ressource SET Id_profil=102 WHERE Id_profil=4");
mysql_query("UPDATE ressource SET Id_profil=103 WHERE Id_profil=45");
mysql_query("UPDATE ressource SET Id_profil=104 WHERE Id_profil=8");
mysql_query("UPDATE ressource SET Id_profil=105 WHERE Id_profil=44");
mysql_query("UPDATE ressource SET Id_profil=106 WHERE Id_profil=12");
mysql_query("UPDATE ressource SET Id_profil=107 WHERE Id_profil=14");
mysql_query("UPDATE ressource SET Id_profil=108 WHERE Id_profil=16");
mysql_query("UPDATE ressource SET Id_profil=109 WHERE Id_profil=43");
mysql_query("UPDATE ressource SET Id_profil=110 WHERE Id_profil=52");
mysql_query("UPDATE ressource SET Id_profil=111 WHERE Id_profil=42");
mysql_query("UPDATE ressource SET Id_profil=112 WHERE Id_profil=41");
mysql_query("UPDATE ressource SET Id_profil=113 WHERE Id_profil=32");
mysql_query("UPDATE ressource SET Id_profil=114 WHERE Id_profil=34");
mysql_query("UPDATE ressource SET Id_profil=115 WHERE Id_profil=40");
mysql_query("UPDATE ressource SET Id_profil=116 WHERE Id_profil=37");
mysql_query("UPDATE ressource SET Id_profil=117 WHERE Id_profil=39");
mysql_query("UPDATE ressource SET Id_profil=118 WHERE Id_profil=46");
mysql_query("UPDATE ressource SET Id_profil=119 WHERE Id_profil=47");
mysql_query("UPDATE ressource SET Id_profil=120 WHERE Id_profil=48");
mysql_query("UPDATE ressource SET Id_profil=121 WHERE Id_profil=49");
mysql_query("UPDATE ressource SET Id_profil=122 WHERE Id_profil=50");
mysql_query("UPDATE ressource SET Id_profil=123 WHERE Id_profil=51");

mysql_query("UPDATE ressource SET Id_profil=1 WHERE Id_profil=101");
mysql_query("UPDATE ressource SET Id_profil=2 WHERE Id_profil=102");
mysql_query("UPDATE ressource SET Id_profil=3 WHERE Id_profil=103");
mysql_query("UPDATE ressource SET Id_profil=4 WHERE Id_profil=104");
mysql_query("UPDATE ressource SET Id_profil=5 WHERE Id_profil=105");
mysql_query("UPDATE ressource SET Id_profil=6 WHERE Id_profil=106");
mysql_query("UPDATE ressource SET Id_profil=7 WHERE Id_profil=107");
mysql_query("UPDATE ressource SET Id_profil=8 WHERE Id_profil=108");
mysql_query("UPDATE ressource SET Id_profil=9 WHERE Id_profil=109");
mysql_query("UPDATE ressource SET Id_profil=10 WHERE Id_profil=110");
mysql_query("UPDATE ressource SET Id_profil=11 WHERE Id_profil=111");
mysql_query("UPDATE ressource SET Id_profil=12 WHERE Id_profil=112");
mysql_query("UPDATE ressource SET Id_profil=13 WHERE Id_profil=113");
mysql_query("UPDATE ressource SET Id_profil=14 WHERE Id_profil=114");
mysql_query("UPDATE ressource SET Id_profil=15 WHERE Id_profil=115");
mysql_query("UPDATE ressource SET Id_profil=16 WHERE Id_profil=116");
mysql_query("UPDATE ressource SET Id_profil=17 WHERE Id_profil=117");
mysql_query("UPDATE ressource SET Id_profil=18 WHERE Id_profil=118");
mysql_query("UPDATE ressource SET Id_profil=19 WHERE Id_profil=119");
mysql_query("UPDATE ressource SET Id_profil=20 WHERE Id_profil=120");
mysql_query("UPDATE ressource SET Id_profil=21 WHERE Id_profil=121");
mysql_query("UPDATE ressource SET Id_profil=22 WHERE Id_profil=122");
mysql_query("UPDATE ressource SET Id_profil=23 WHERE Id_profil=123");


mysql_query("UPDATE entretien SET Id_profil_envisage=101 WHERE Id_profil_envisage=2");
mysql_query("UPDATE entretien SET Id_profil_envisage=102 WHERE Id_profil_envisage=4");
mysql_query("UPDATE entretien SET Id_profil_envisage=103 WHERE Id_profil_envisage=45");
mysql_query("UPDATE entretien SET Id_profil_envisage=104 WHERE Id_profil_envisage=8");
mysql_query("UPDATE entretien SET Id_profil_envisage=105 WHERE Id_profil_envisage=44");
mysql_query("UPDATE entretien SET Id_profil_envisage=106 WHERE Id_profil_envisage=12");
mysql_query("UPDATE entretien SET Id_profil_envisage=107 WHERE Id_profil_envisage=14");
mysql_query("UPDATE entretien SET Id_profil_envisage=108 WHERE Id_profil_envisage=16");
mysql_query("UPDATE entretien SET Id_profil_envisage=109 WHERE Id_profil_envisage=43");
mysql_query("UPDATE entretien SET Id_profil_envisage=110 WHERE Id_profil_envisage=52");
mysql_query("UPDATE entretien SET Id_profil_envisage=111 WHERE Id_profil_envisage=42");
mysql_query("UPDATE entretien SET Id_profil_envisage=112 WHERE Id_profil_envisage=41");
mysql_query("UPDATE entretien SET Id_profil_envisage=113 WHERE Id_profil_envisage=32");
mysql_query("UPDATE entretien SET Id_profil_envisage=114 WHERE Id_profil_envisage=34");
mysql_query("UPDATE entretien SET Id_profil_envisage=115 WHERE Id_profil_envisage=40");
mysql_query("UPDATE entretien SET Id_profil_envisage=116 WHERE Id_profil_envisage=37");
mysql_query("UPDATE entretien SET Id_profil_envisage=117 WHERE Id_profil_envisage=39");
mysql_query("UPDATE entretien SET Id_profil_envisage=118 WHERE Id_profil_envisage=46");
mysql_query("UPDATE entretien SET Id_profil_envisage=119 WHERE Id_profil_envisage=47");
mysql_query("UPDATE entretien SET Id_profil_envisage=120 WHERE Id_profil_envisage=48");
mysql_query("UPDATE entretien SET Id_profil_envisage=121 WHERE Id_profil_envisage=49");
mysql_query("UPDATE entretien SET Id_profil_envisage=122 WHERE Id_profil_envisage=50");
mysql_query("UPDATE entretien SET Id_profil_envisage=123 WHERE Id_profil_envisage=51");

mysql_query("UPDATE entretien SET Id_profil_envisage=1 WHERE Id_profil_envisage=101");
mysql_query("UPDATE entretien SET Id_profil_envisage=2 WHERE Id_profil_envisage=102");
mysql_query("UPDATE entretien SET Id_profil_envisage=3 WHERE Id_profil_envisage=103");
mysql_query("UPDATE entretien SET Id_profil_envisage=4 WHERE Id_profil_envisage=104");
mysql_query("UPDATE entretien SET Id_profil_envisage=5 WHERE Id_profil_envisage=105");
mysql_query("UPDATE entretien SET Id_profil_envisage=6 WHERE Id_profil_envisage=106");
mysql_query("UPDATE entretien SET Id_profil_envisage=7 WHERE Id_profil_envisage=107");
mysql_query("UPDATE entretien SET Id_profil_envisage=8 WHERE Id_profil_envisage=108");
mysql_query("UPDATE entretien SET Id_profil_envisage=9 WHERE Id_profil_envisage=109");
mysql_query("UPDATE entretien SET Id_profil_envisage=10 WHERE Id_profil_envisage=110");
mysql_query("UPDATE entretien SET Id_profil_envisage=11 WHERE Id_profil_envisage=111");
mysql_query("UPDATE entretien SET Id_profil_envisage=12 WHERE Id_profil_envisage=112");
mysql_query("UPDATE entretien SET Id_profil_envisage=13 WHERE Id_profil_envisage=113");
mysql_query("UPDATE entretien SET Id_profil_envisage=14 WHERE Id_profil_envisage=114");
mysql_query("UPDATE entretien SET Id_profil_envisage=15 WHERE Id_profil_envisage=115");
mysql_query("UPDATE entretien SET Id_profil_envisage=16 WHERE Id_profil_envisage=116");
mysql_query("UPDATE entretien SET Id_profil_envisage=17 WHERE Id_profil_envisage=117");
mysql_query("UPDATE entretien SET Id_profil_envisage=18 WHERE Id_profil_envisage=118");
mysql_query("UPDATE entretien SET Id_profil_envisage=19 WHERE Id_profil_envisage=119");
mysql_query("UPDATE entretien SET Id_profil_envisage=20 WHERE Id_profil_envisage=120");
mysql_query("UPDATE entretien SET Id_profil_envisage=21 WHERE Id_profil_envisage=121");
mysql_query("UPDATE entretien SET Id_profil_envisage=22 WHERE Id_profil_envisage=122");
mysql_query("UPDATE entretien SET Id_profil_envisage=23 WHERE Id_profil_envisage=123");


mysql_query("UPDATE description SET Id_cursus=101 WHERE Id_cursus=3");
mysql_query("UPDATE description SET Id_cursus=102 WHERE Id_cursus=5");
mysql_query("UPDATE description SET Id_cursus=103 WHERE Id_cursus=6");
mysql_query("UPDATE description SET Id_cursus=104 WHERE Id_cursus=8");
mysql_query("UPDATE description SET Id_cursus=105 WHERE Id_cursus=9");
mysql_query("UPDATE description SET Id_cursus=106 WHERE Id_cursus=11");
mysql_query("UPDATE description SET Id_cursus=107 WHERE Id_cursus=12");
mysql_query("UPDATE description SET Id_cursus=108 WHERE Id_cursus=14");
mysql_query("UPDATE description SET Id_cursus=109 WHERE Id_cursus=16");
mysql_query("UPDATE description SET Id_cursus=110 WHERE Id_cursus=22");
mysql_query("UPDATE description SET Id_cursus=111 WHERE Id_cursus=23");

mysql_query("UPDATE description SET Id_cursus=2 WHERE Id_cursus=101");
mysql_query("UPDATE description SET Id_cursus=3 WHERE Id_cursus=102");
mysql_query("UPDATE description SET Id_cursus=4 WHERE Id_cursus=103");
mysql_query("UPDATE description SET Id_cursus=13 WHERE Id_cursus=104");
mysql_query("UPDATE description SET Id_cursus=8 WHERE Id_cursus=105");
mysql_query("UPDATE description SET Id_cursus=9 WHERE Id_cursus=106");
mysql_query("UPDATE description SET Id_cursus=15 WHERE Id_cursus=107");
mysql_query("UPDATE description SET Id_cursus=6 WHERE Id_cursus=108");
mysql_query("UPDATE description SET Id_cursus=16 WHERE Id_cursus=109");
mysql_query("UPDATE description SET Id_cursus=14 WHERE Id_cursus=110");
mysql_query("UPDATE description SET Id_cursus=5 WHERE Id_cursus=111");

mysql_query("UPDATE ressource SET Id_cursus=101 WHERE Id_cursus=3");
mysql_query("UPDATE ressource SET Id_cursus=102 WHERE Id_cursus=5");
mysql_query("UPDATE ressource SET Id_cursus=103 WHERE Id_cursus=6");
mysql_query("UPDATE ressource SET Id_cursus=104 WHERE Id_cursus=8");
mysql_query("UPDATE ressource SET Id_cursus=105 WHERE Id_cursus=9");
mysql_query("UPDATE ressource SET Id_cursus=106 WHERE Id_cursus=11");
mysql_query("UPDATE ressource SET Id_cursus=107 WHERE Id_cursus=12");
mysql_query("UPDATE ressource SET Id_cursus=108 WHERE Id_cursus=14");
mysql_query("UPDATE ressource SET Id_cursus=109 WHERE Id_cursus=16");
mysql_query("UPDATE ressource SET Id_cursus=110 WHERE Id_cursus=22");
mysql_query("UPDATE ressource SET Id_cursus=111 WHERE Id_cursus=23");


mysql_query("UPDATE ressource SET Id_cursus=2 WHERE Id_cursus=101");
mysql_query("UPDATE ressource SET Id_cursus=3 WHERE Id_cursus=102");
mysql_query("UPDATE ressource SET Id_cursus=4 WHERE Id_cursus=103");
mysql_query("UPDATE ressource SET Id_cursus=13 WHERE Id_cursus=104");
mysql_query("UPDATE ressource SET Id_cursus=8 WHERE Id_cursus=105");
mysql_query("UPDATE ressource SET Id_cursus=9 WHERE Id_cursus=106");
mysql_query("UPDATE ressource SET Id_cursus=15 WHERE Id_cursus=107");
mysql_query("UPDATE ressource SET Id_cursus=6 WHERE Id_cursus=108");
mysql_query("UPDATE ressource SET Id_cursus=16 WHERE Id_cursus=109");
mysql_query("UPDATE ressource SET Id_cursus=14 WHERE Id_cursus=110");
mysql_query("UPDATE ressource SET Id_cursus=5 WHERE Id_cursus=111");


mysql_query("
CREATE TABLE `cursus` (
	`Id_cursus` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(30) NOT NULL default '',
	PRIMARY KEY ( `Id_cursus` ) 
)");

mysql_query("
INSERT INTO `cursus`  VALUES
('','BAC Pro.'),
('','BAC'),
('','BTS'),
('','DUT'),
('','DUT GTR'),
('','Ecole d\'ingénieur'),
('','DEUG'),
('','Licence'),
('','Maîtrise'),
('','Master'),
('','Master 2'),
('','DESS / DEA'),
('','Autre Bac +2'),
('','Autre Bac +3'),
('','Autre Bac +4'),
('','Autre Bac +5')
");

mysql_query("
CREATE TABLE `etat_matrimonial` (
	`Id_etat_matrimonial` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(30) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_matrimonial` ) 
)");

mysql_query("
INSERT INTO `etat_matrimonial`  VALUES
(1, 'Marié'),
(2, 'Célibataire'),
(3, 'PACS'),
(4, 'Vie maritale'),
(5, 'Veuf'),
(6, 'Divorcé')
");

mysql_query("DROP TABLE `niveau_langue`");

mysql_query("
CREATE TABLE `niveau_langue` (
	`Id_niveau_langue` INT(1) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_niveau_langue` ) 
)");

mysql_query("
INSERT INTO `niveau_langue` VALUES
	('1','Scolaire'),
	('2','Technique'),
	('3','Courant'),
	('4','Bilingue')
");

mysql_query("
CREATE TABLE `ville` (
	`Id_ville` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NULL default '',
	PRIMARY KEY ( `Id_ville` )
)");

mysql_query("
INSERT INTO `ville` VALUES 
(1, 'Nantes'),
(2, 'Rennes'),
(3, 'Lannion'),
(4, 'Brest'),
(5, 'Le Havre'),
(6, 'Caen'),
(7, 'Rouen'),
(8, 'Paris'),
(9, 'Lille'),
(10, 'Niort'),
(11, 'Lyon'),
(12, 'Bordeaux'),
(13, 'Toulouse'),
(14, 'Aix en Provence'),
(15, 'Sophia'),
(16, 'Tours'),
(17, 'Orléans'),
(18, 'Le Mans'),
(19, 'Redon'),
(20, 'Marseille')
");

mysql_query("
CREATE TABLE `zone` (
	`Id_zone` INT(3) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(30) NOT NULL default '',
	PRIMARY KEY ( `Id_zone` )
)");

mysql_query("
INSERT INTO `zone` VALUES 
(1, 'France'),
(2, 'Europe'),
(3, 'International')
");

mysql_query("
CREATE TABLE `exp_info` (
  `Id_exp_info` int(3) NOT NULL auto_increment,
  `libelle` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`Id_exp_info`)
)");

mysql_query("
INSERT INTO `exp_info` VALUES 
(1, '0-6 mois'),
(2, '6 mois-1 an'),
(3, '1 an-3 ans'),
(4, '+ de 3 ans')
");

mysql_query("
CREATE TABLE `annonce` (
	`Id_annonce` INT NOT NULL AUTO_INCREMENT,
	`reference` VARCHAR(40) NULL default '',
	`libelle` VARCHAR(255) NULL default '',
	`date_creation` DATETIME,
	`date_modification` DATETIME,
	`descriptif` TEXT,
	`createur` VARCHAR(255) NULL default '',
	`localisation` VARCHAR(255) NULL default '',
	`archive` INT(1) NULL default '0',
	PRIMARY KEY ( `Id_annonce` )
)");

mysql_query("
CREATE TABLE `region` (
	`Id_region` INT(2) NOT NULL unique default '0',
	`libelle` VARCHAR(100) NOT NULL default '',
	`Id_zone` INT(2) NOT NULL default '0',
	PRIMARY KEY ( `Id_region`, `Id_zone` )
)");

mysql_query("
INSERT INTO `region` VALUES
	('1','Alsace','1'),
	('2','Aquitaine','1'),
	('3','Auvergne','1'),
	('4','Basse-Normandie','1'),
	('5','Bourgogne','1'),
	('6','Bretagne','1'),
	('7','Centre','1'),
	('8','Champagne-Ardennes','1'),
	('9','Corse','1'),
	('10','Départements d\'Outre Mer','1'),
	('11','Franche-Comté','1'),
	('12','Haute-Normandie','1'),
	('13','Ile de France','1'),
	('14','Languedoc-Roussillon','1'),
	('15','Limousin','1'),
	('16','Lorraine','1'),
	('17','Midi Pyrénées','1'),
	('18','Nord-Pas-de-Calais','1'),
	('19','Pays de Loire','1'),
	('20','Picardie','1'),
	('21','Poitou Charente','1'),
	('22','Provence / Alpes /Côte d\'Azur','1'),
	('23','Rhône-Alpes','1')
");

 
/////////////////////////////////////////////////////////////TABLE DEPARTEMENT //////////////////////////////////////////////////////

mysql_query("
CREATE TABLE `departement` (
	`Id_departement` VARCHAR(3) NOT NULL default '',
	`nom` VARCHAR(100) NOT NULL default '',
	`Id_region` INT(2) NOT NULL default '0',
	PRIMARY KEY ( `Id_departement`, `Id_region` )
)");

mysql_query("
INSERT INTO `departement` VALUES
	('01','Ain','23'),
	('02','Aisne','20'),
	('03','Allier','3'),
	('04','Alpes-de-Haute-Provence','22'),
	('05','Hautes-Alpes','22'),
	('06','Alpes-Maritimes','22'),
	('07','Ardèche','23'),
	('08','Ardennes','8'),
	('09','Ariège','17'),
	('10','Aube','8'),
	('11','Aude','14'),
	('12','Aveyron','17'),
	('13','Bouches-du-Rhône','22'),
	('14','Calvados','4'),
	('15','Cantal','3'),
	('16','Charente','21'),
	('17','Charente-Maritime','21'),
	('18','Cher','7'),
	('19','Corèze','15'),
	('21','Côte-d\'Or','5'),
	('22','Côtes-d\'Armor','6'),
	('23','Creuse','15'),
	('24','Dordogne','2'),
	('25','Doubs','11'),
	('26','Drôme','23'),
	('27','Eure','12'),
	('28','Eure-et-Loir','7'),
	('29','Finistère','6'),
	('2A','Corse du Sud','9'),
	('2B','Haute-Corse','9'),
	('30','Gard','14'),
	('31','Haute-Garonne','17'),
	('32','Gers','17'),
	('33','Gironde','2'),
	('34','Hérault','14'),
	('35','Ille-et-Vilaine','6'),
	('36','Indre','7'),
	('37','Indre-et-Loire','7'),
	('38','Isère','23'),
	('39','Jura','11'),
	('40','Landes','2'),
	('41','Loir-et-Cher','7'),
	('42','Loire','23'),
	('43','Haute-Loire','3'),
	('44','Loire-Atlantique','19'),
	('45','Loiret','7'),
	('46','Lot','17'),
	('47','Lot-et-Garonne','2'),
	('48','Lozère','14'),
	('49','Maine-et-Loire','19'),
	('50','Manche','4'),
	('51','Marne','8'),
	('52','Haute-Marne','8'),
	('53','Mayenne','19'),
	('54','Meurthe-et-Moselle','16'),
	('55','Meuse','16'),
	('56','Morbihan','6'),
	('57','Moselle','16'),
	('58','Nièvre','5'),
	('59','Nord','18'),
	('60','Oise','20'),
	('61','Orne','4'),
	('62','Pas-de-Calais','18'),
	('63','Puy-de-Dôme','3'),
	('64','Pyrénées-Atlantiques','2'),
	('65','Hautes-Pyrénées','17'),
	('66','Pyrénées-Orientales','14'),
	('67','Bas-Rhin','1'),
	('68','Haut-Rhin','1'),
	('69','Rhône','23'),
	('70','Haute-Saône','11'),
	('71','Saône-et-Loire','5'),
	('72','Sarthe','19'),
	('73','Savoie','23'),
	('74','Haute-Savoie','23'),
	('75','Paris','13'),
	('76','Seine-Maritime','12'),
	('77','Seine-et-Marne','13'),
	('78','Yvelines','FRPA'),
	('79','Deux-Sèvres','21'),
	('80','Somme','20'),
	('81','Tarn','17'),
	('82','Tarn-et-Garonne','17'),
	('83','Var','22'),
	('84','Vaucluse','22'),
	('85','Vendée','19'),
	('86','Vienne','21'),
	('87','Haute-Vienne','15'),
	('88','Vosges','16'),
	('89','Yonne','5'),
	('90','Territoire de Belfort','11'),
	('91','Essonne','13'),
	('92','Hauts-de-Seine','13'),
	('93','Seine-Saint-Denis','13'),
	('94','Val-de-Marne','13'),
	('95','Val-d\'Oise','13'),
	('971','Guadeloupe','10'),
	('972','Martinique','10'),
	('973','Guyane','10'),
	('974','La Réunion','10'),
	('975','Saint-Pierre-et-Miquelon','10'),
	('976','Mayotte','10')
");

mysql_query("DROP TABLE `langue`");

mysql_query("
CREATE TABLE `langue` (
    `Id_langue` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_langue` ) 
)");

mysql_query("
INSERT INTO `langue` VALUES
('','Anglais'),
('','Espagnol'),
('','Allemand'),
('','Italien'),
('','Arabe'),
('','Japonais'),
('','Russe'),
('','Afrikaans'),
('','Albanais'),
('','Amharique'),
('','Anguar'),
('','Arménien'),
('','Aymara'),
('','Azéri'),
('','Basque'),
('','Bengalî'),
('','Bichelamar'),
('','Biélorusse'),
('','Birman'),
('','Bulgare'),
('','Cantonais'),
('','Carolinien'),
('','Catalan'),
('','Chamorro'),
('','Chichewa'),
('','Chinois'),
('','Cinghalais'),
('','Coréen'),
('','Créole de Guinée-Bissau'),
('','Créole haïtien'),
('','Créole seychellois'),
('','Croate'),
('','Danois'),
('','Divehi'),
('','Dzongkha'),
('','Estonien'),
('','Fidjien'),
('','Filipino'),
('','Finnois'),
('','Français'),
('','Galicien'),
('','Gallois'),
('','Géorgien'),
('','Gilbertin'),
('','Grec'),
('','Guarani'),
('','Hatohabei'),
('','Hawaiien'),
('','Hébreu'),
('','Hindi'),
('','Hindoustani'),
('','Hiri Motu'),
('','Hongrois'),
('','Iban'),
('','Indonésien'),
('','Inuktitut'),
('','Irlandais'),
('','Islandais'),
('','Kazakh'),
('','Khmer'),
('','Kirghiz'),
('','Kirundi'),
('','Kiswahili'),
('','Kurde'),
('','Ladin'),
('','Lao'),
('','Latin'),
('','Letton'),
('','Limbourgeois'),
('','Lituanien'),
('','Luxembourgeois'),
('','Macédonien'),
('','Malais'),
('','Maltais'),
('','Mandarin'),
('','Mannois'),
('','Maori'),
('','Maori des Îles Cook'),
('','Marshallais'),
('','Mirandais'),
('','Mongol'),
('','Monténégrin'),
('','Nauruan'),
('','Ndébélé'),
('','Néerlandais'),
('','Népalais'),
('','Norvégien'),
('','Occitan-Aranais'),
('','Ouïghour'),
('','Ourdou'),
('','Ouzbek'),
('','Pachto'),
('','Paluan'),
('','Persan'),
('','Polonais'),
('','Portugais'),
('','Quechua'),
('','Romanche'),
('','Roumain'),
('','Ruthène'),
('','Samoan'),
('','Sango'),
('','Sarde'),
('','Serbe'),
('','Sesotho'),
('','Shikomor'),
('','Shona'),
('','Sindebele'),
('','Slovaque'),
('','Slovène'),
('','Somali'),
('','Sorabe'),
('','Sotho du Nord'),
('','Sotho du Sud'),
('','Suédois'),
('','Swati'),
('','Tadjik'),
('','Tamoul'),
('','Tchèque'),
('','Tétoum'),
('','Thaï'),
('','Tibétain'),
('','Tigrinya'),
('','Tok Pisin'),
('','Tongien'),
('','Tsonga'),
('','Tswana'),
('','Turc'),
('','Turkmène'),
('','Tuvaluan'),
('','Ukrainien'),
('','Venda'),
('','Vietnamien'),
('','Xhosa'),
('','Zoulou')
");

mysql_query("DROP TABLE `pays`");

mysql_query("
CREATE TABLE `pays` (
    `Id_pays` INT(3) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(80) NOT NULL default '',
	PRIMARY KEY ( `Id_pays` )
)");

mysql_query("
INSERT INTO pays VALUES
('', 'Afghanistan'),
('', 'Afrique du sud'),
('', 'Albanie'),
('', 'Algérie'),
('', 'Allemagne'),
('', 'Andorre'),
('', 'Angola'),
('', 'Anguilla'),
('', 'Antarctique'),
('', 'Antigua et Barbuda'),
('', 'Antilles néerlandaises'),
('', 'Arabie saoudite'),
('', 'Argentine'),
('', 'Arménie'),
('', 'Aruba'),
('', 'Australie'),
('', 'Autriche'),
('', 'Azerbaïdjan'),
('', 'Bahamas'),
('', 'Bahrain'),
('', 'Bangladesh'),
('', 'Belgique'),
('', 'Belize'),
('', 'Benin'),
('', 'Bermudes (Les)'),
('', 'Bhoutan'),
('', 'Biélorussie'),
('', 'Bolivie'),
('', 'Bosnie-Herzégovine'),
('', 'Botswana'),
('', 'Bouvet (Îles)'),
('', 'Brésil'),
('', 'Brunei'),
('', 'Bulgarie'),
('', 'Burkina Faso'),
('', 'Burundi'),
('', 'Cambodge'),
('', 'Cameroun'),
('', 'Canada'),
('', 'Cap Vert'),
('', 'Cayman (Îles)'),
('', 'Chili'),
('', 'Chine (Rép. pop.)'),
('', 'Christmas (Île)'),
('', 'Chypre'),
('', 'Cocos (Îles)'),
('', 'Colombie'),
('', 'Comores'),
('', 'Cook (Îles)'),
('', 'Corée du Nord'),
('', 'Corée, Sud'),
('', 'Costa Rica'),
('', 'Côte d''Ivoire'),
('', 'Croatie'),
('', 'Cuba'),
('', 'Danemark'),
('', 'Djibouti'),
('', 'Dominique'),
('', 'Égypte'),
('', 'El Salvador'),
('', 'Émirats arabes unis'),
('', 'Équateur'),
('', 'Érythrée'),
('', 'Espagne'),
('', 'Estonie'),
('', 'États-Unis'),
('', 'Ethiopie'),
('', 'Falkland (Île)'),
('', 'Féroé (Îles)'),
('', 'Fidji (République des)'),
('', 'Finlande'),
('', 'France'),
('', 'Gabon'),
('', 'Gambie'),
('', 'Géorgie'),
('', 'Géorgie du Sud et Sandwich du Sud (Îles)'),
('', 'Ghana'),
('', 'Gibraltar'),
('', 'Grèce'),
('', 'Grenade'),
('', 'Groenland'),
('', 'Guadeloupe'),
('', 'Guam'),
('', 'Guatemala'),
('', 'Guinée'),
('', 'Guinée Equatoriale'),
('', 'Guinée-Bissau'),
('', 'Guyane'),
('', 'Guyane française'),
('', 'Haïti'),
('', 'Heard et McDonald (Îles)'),
('', 'Honduras'),
('', 'Hong Kong'),
('', 'Hongrie'),
('', 'Îles Mineures Éloignées des États-Unis'),
('', 'Inde'),
('', 'Indonésie'),
('', 'Irak'),
('', 'Iran'),
('', 'Irlande'),
('', 'Islande'),
('', 'Israël'),
('', 'Italie'),
('', 'Jamaïque'),
('', 'Japon'),
('', 'Jordanie'),
('', 'Kazakhstan'),
('', 'Kenya'),
('', 'Kirghizistan'),
('', 'Kiribati'),
('', 'Koweit'),
('', 'La Barbad'),
('', 'Laos'),
('', 'Lesotho'),
('', 'Lettonie'),
('', 'Liban'),
('', 'Libéria'),
('', 'Libye'),
('', 'Liechtenstein'),
('', 'Lithuanie'),
('', 'Luxembourg'),
('', 'Macau'),
('', 'Macédoine'),
('', 'Madagascar'),
('', 'Malaisie'),
('', 'Malawi'),
('', 'Maldives (Îles)'),
('', 'Mali'),
('', 'Malte'),
('', 'Mariannes du Nord (Îles)'),
('', 'Maroc'),
('', 'Marshall (Îles)'),
('', 'Martinique'),
('', 'Maurice'),
('', 'Mauritanie'),
('', 'Mayotte'),
('', 'Mexique'),
('', 'Micronésie (États fédérés de)'),
('', 'Moldavie'),
('', 'Monaco'),
('', 'Mongolie'),
('', 'Montserrat'),
('', 'Mozambique'),
('', 'Myanmar'),
('', 'Namibie'),
('', 'Nauru'),
('', 'Nepal'),
('', 'Nicaragua'),
('', 'Niger'),
('', 'Nigeria'),
('', 'Niue'),
('', 'Norfolk (Îles)'),
('', 'Norvège'),
('', 'Nouvelle Calédonie'),
('', 'Nouvelle-Zélande'),
('', 'Oman'),
('', 'Ouganda'),
('', 'Ouzbékistan'),
('', 'Pakistan'),
('', 'Palau'),
('', 'Panama'),
('', 'Papouasie-Nouvelle-Guinée'),
('', 'Paraguay'),
('', 'Pays-Bas'),
('', 'Pérou'),
('', 'Philippines'),
('', 'Pitcairn (Îles)'),
('', 'Pologne'),
('', 'Polynésie française'),
('', 'Porto Rico'),
('', 'Portugal'),
('', 'Qatar'),
('', 'Rép. Dém. du Congo'),
('', 'République centrafricaine'),
('', 'République Dominicaine'),
('', 'République tchèque'),
('', 'Réunion (La)'),
('', 'Roumanie'),
('', 'Royaume-Uni'),
('', 'Russie'),
('', 'Rwanda'),
('', 'Sahara Occidental'),
('', 'Saint Pierre et Miquelon'),
('', 'Saint Vincent et les Grenadines'),
('', 'Saint-Kitts et Nevis'),
('', 'Saint-Marin (Rép. de)'),
('', 'Sainte Hélène'),
('', 'Sainte Lucie'),
('', 'Samoa'),
('', 'Samoa'),
('', 'São Tomé et Príncipe (Rép.)'),
('', 'Sénégal'),
('', 'Seychelles'),
('', 'Sierra Leone'),
('', 'Singapour'),
('', 'Slovaquie'),
('', 'Slovénie'),
('', 'Somalie'),
('', 'Soudan'),
('', 'Sri Lanka'),
('', 'Suède'),
('', 'Suisse'),
('', 'Suriname'),
('', 'Svalbard et Jan Mayen (Îles)'),
('', 'Swaziland'),
('', 'Syrie'),
('', 'Tadjikistan'),
('', 'Taiwan'),
('', 'Tanzanie'),
('', 'Tchad'),
('', 'Territoire britannique de l''océan Indien'),
('', 'Territoires français du sud'),
('', 'Thailande'),
('', 'Timor'),
('', 'Togo'),
('', 'Tokelau'),
('', 'Tonga'),
('', 'Trinité et Tobago'),
('', 'Tunisie'),
('', 'Turkménistan'),
('', 'Turks et Caïques (Îles)'),
('', 'Turquie'),
('', 'Tuvalu'),
('', 'Ukraine'),
('', 'Uruguay'),
('', 'Vanuatu'),
('', 'Vatican (Etat du)'),
('', 'Venezuela'),
('', 'Vierges (Îles)'),
('', 'Vierges britanniques (Îles)'),
('', 'Vietnam'),
('', 'Wallis et Futuna (Îles)'),
('', 'Yemen'),
('', 'Yougoslavie'),
('', 'Zaïre'),
('', 'Zambie'),
('', 'Zimbabwe')
");


mysql_query("
CREATE TABLE `affaire_langue` (
	`Id_affaire` INT NOT NULL default '0',
	`Id_langue` INT(3) NOT NULL default '0',
	`Id_niveau_langue` INT(3) NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire`, `Id_langue` )
)");


$resultat = mysql_query("SELECT Id_affaire FROM affaire_competence WHERE Id_competence=79");
while ($tableau = mysql_fetch_array($resultat)) {
	$b = $tableau['Id_affaire'];
    mysql_query('INSERT INTO affaire_langue set Id_affaire='.$b.', Id_langue="1", Id_niveau_langue="0"') or die( var_dump($tableau).mysql_error());
}

mysql_query("delete FROM affaire_competence WHERE Id_competence=143");
mysql_query("delete FROM affaire_competence WHERE Id_competence=142");
mysql_query("delete FROM affaire_competence WHERE Id_competence=141");
mysql_query("delete FROM affaire_competence WHERE Id_competence=79");


mysql_query("UPDATE affaire_competence SET Id_competence=201 WHERE Id_competence=1");
mysql_query("UPDATE affaire_competence SET Id_competence=202 WHERE Id_competence=3");
mysql_query("UPDATE affaire_competence SET Id_competence=203 WHERE Id_competence=5");
mysql_query("UPDATE affaire_competence SET Id_competence=204 WHERE Id_competence=8");
mysql_query("UPDATE affaire_competence SET Id_competence=205 WHERE Id_competence=12");
mysql_query("UPDATE affaire_competence SET Id_competence=206 WHERE Id_competence=15");
mysql_query("UPDATE affaire_competence SET Id_competence=207 WHERE Id_competence=24");
mysql_query("UPDATE affaire_competence SET Id_competence=208 WHERE Id_competence=25");
mysql_query("UPDATE affaire_competence SET Id_competence=209 WHERE Id_competence=26");
mysql_query("UPDATE affaire_competence SET Id_competence=210 WHERE Id_competence=30");
mysql_query("UPDATE affaire_competence SET Id_competence=211 WHERE Id_competence=33");
mysql_query("UPDATE affaire_competence SET Id_competence=212 WHERE Id_competence=35");
mysql_query("UPDATE affaire_competence SET Id_competence=213 WHERE Id_competence=38");
mysql_query("UPDATE affaire_competence SET Id_competence=214 WHERE Id_competence=39");
mysql_query("UPDATE affaire_competence SET Id_competence=215 WHERE Id_competence=40");
mysql_query("UPDATE affaire_competence SET Id_competence=216 WHERE Id_competence=42");
mysql_query("UPDATE affaire_competence SET Id_competence=217 WHERE Id_competence=140");
mysql_query("UPDATE affaire_competence SET Id_competence=218 WHERE Id_competence=44");
mysql_query("UPDATE affaire_competence SET Id_competence=219 WHERE Id_competence=45");
mysql_query("UPDATE affaire_competence SET Id_competence=220 WHERE Id_competence=46");
mysql_query("UPDATE affaire_competence SET Id_competence=221 WHERE Id_competence=47");
mysql_query("UPDATE affaire_competence SET Id_competence=222 WHERE Id_competence=139");
mysql_query("UPDATE affaire_competence SET Id_competence=223 WHERE Id_competence=49");
mysql_query("UPDATE affaire_competence SET Id_competence=224 WHERE Id_competence=138");
mysql_query("UPDATE affaire_competence SET Id_competence=225 WHERE Id_competence=137");
mysql_query("UPDATE affaire_competence SET Id_competence=226 WHERE Id_competence=136");
mysql_query("UPDATE affaire_competence SET Id_competence=227 WHERE Id_competence=135");
mysql_query("UPDATE affaire_competence SET Id_competence=228 WHERE Id_competence=56");
mysql_query("UPDATE affaire_competence SET Id_competence=229 WHERE Id_competence=134");
mysql_query("UPDATE affaire_competence SET Id_competence=230 WHERE Id_competence=133");
mysql_query("UPDATE affaire_competence SET Id_competence=231 WHERE Id_competence=61");
mysql_query("UPDATE affaire_competence SET Id_competence=232 WHERE Id_competence=62");
mysql_query("UPDATE affaire_competence SET Id_competence=233 WHERE Id_competence=132");
mysql_query("UPDATE affaire_competence SET Id_competence=234 WHERE Id_competence=131");
mysql_query("UPDATE affaire_competence SET Id_competence=235 WHERE Id_competence=130");
mysql_query("UPDATE affaire_competence SET Id_competence=236 WHERE Id_competence=68");
mysql_query("UPDATE affaire_competence SET Id_competence=237 WHERE Id_competence=69");
mysql_query("UPDATE affaire_competence SET Id_competence=238 WHERE Id_competence=129");
mysql_query("UPDATE affaire_competence SET Id_competence=239 WHERE Id_competence=128");
mysql_query("UPDATE affaire_competence SET Id_competence=240 WHERE Id_competence=127");
mysql_query("UPDATE affaire_competence SET Id_competence=241 WHERE Id_competence=126");
mysql_query("UPDATE affaire_competence SET Id_competence=242 WHERE Id_competence=74");
mysql_query("UPDATE affaire_competence SET Id_competence=243 WHERE Id_competence=75");
mysql_query("UPDATE affaire_competence SET Id_competence=244 WHERE Id_competence=76");
mysql_query("UPDATE affaire_competence SET Id_competence=245 WHERE Id_competence=77");
mysql_query("UPDATE affaire_competence SET Id_competence=246 WHERE Id_competence=125");
mysql_query("UPDATE affaire_competence SET Id_competence=247 WHERE Id_competence=124");
mysql_query("UPDATE affaire_competence SET Id_competence=248 WHERE Id_competence=123");
mysql_query("UPDATE affaire_competence SET Id_competence=249 WHERE Id_competence=122");
mysql_query("UPDATE affaire_competence SET Id_competence=250 WHERE Id_competence=121");
mysql_query("UPDATE affaire_competence SET Id_competence=251 WHERE Id_competence=120");
mysql_query("UPDATE affaire_competence SET Id_competence=252 WHERE Id_competence=86");
mysql_query("UPDATE affaire_competence SET Id_competence=253 WHERE Id_competence=119");
mysql_query("UPDATE affaire_competence SET Id_competence=254 WHERE Id_competence=89");
mysql_query("UPDATE affaire_competence SET Id_competence=255 WHERE Id_competence=118");
mysql_query("UPDATE affaire_competence SET Id_competence=256 WHERE Id_competence=92");
mysql_query("UPDATE affaire_competence SET Id_competence=257 WHERE Id_competence=94");
mysql_query("UPDATE affaire_competence SET Id_competence=258 WHERE Id_competence=117");
mysql_query("UPDATE affaire_competence SET Id_competence=259 WHERE Id_competence=97");
mysql_query("UPDATE affaire_competence SET Id_competence=260 WHERE Id_competence=98");
mysql_query("UPDATE affaire_competence SET Id_competence=261 WHERE Id_competence=99");
mysql_query("UPDATE affaire_competence SET Id_competence=262 WHERE Id_competence=116");
mysql_query("UPDATE affaire_competence SET Id_competence=263 WHERE Id_competence=101");
mysql_query("UPDATE affaire_competence SET Id_competence=264 WHERE Id_competence=102");
mysql_query("UPDATE affaire_competence SET Id_competence=265 WHERE Id_competence=104");
mysql_query("UPDATE affaire_competence SET Id_competence=266 WHERE Id_competence=105");
mysql_query("UPDATE affaire_competence SET Id_competence=267 WHERE Id_competence=106");
mysql_query("UPDATE affaire_competence SET Id_competence=268 WHERE Id_competence=115");
mysql_query("UPDATE affaire_competence SET Id_competence=269 WHERE Id_competence=108");
mysql_query("UPDATE affaire_competence SET Id_competence=270 WHERE Id_competence=109");
mysql_query("UPDATE affaire_competence SET Id_competence=271 WHERE Id_competence=110");
mysql_query("UPDATE affaire_competence SET Id_competence=272 WHERE Id_competence=111");
mysql_query("UPDATE affaire_competence SET Id_competence=273 WHERE Id_competence=112");
mysql_query("UPDATE affaire_competence SET Id_competence=274 WHERE Id_competence=113");
mysql_query("UPDATE affaire_competence SET Id_competence=275 WHERE Id_competence=114");
mysql_query("UPDATE affaire_competence SET Id_competence=276 WHERE Id_competence=144");
mysql_query("UPDATE affaire_competence SET Id_competence=277 WHERE Id_competence=145");
mysql_query("UPDATE affaire_competence SET Id_competence=278 WHERE Id_competence=146");
mysql_query("UPDATE affaire_competence SET Id_competence=279 WHERE Id_competence=147");
mysql_query("UPDATE affaire_competence SET Id_competence=280 WHERE Id_competence=148");
mysql_query("UPDATE affaire_competence SET Id_competence=281 WHERE Id_competence=149");
mysql_query("UPDATE affaire_competence SET Id_competence=282 WHERE Id_competence=150");
mysql_query("UPDATE affaire_competence SET Id_competence=283 WHERE Id_competence=151");
mysql_query("UPDATE affaire_competence SET Id_competence=284 WHERE Id_competence=152");
mysql_query("UPDATE affaire_competence SET Id_competence=285 WHERE Id_competence=153");
mysql_query("UPDATE affaire_competence SET Id_competence=286 WHERE Id_competence=154");
mysql_query("UPDATE affaire_competence SET Id_competence=287 WHERE Id_competence=155");
mysql_query("UPDATE affaire_competence SET Id_competence=288 WHERE Id_competence=156");
mysql_query("UPDATE affaire_competence SET Id_competence=289 WHERE Id_competence=157");
mysql_query("UPDATE affaire_competence SET Id_competence=290 WHERE Id_competence=158");
mysql_query("UPDATE affaire_competence SET Id_competence=291 WHERE Id_competence=159");
mysql_query("UPDATE affaire_competence SET Id_competence=292 WHERE Id_competence=160");
mysql_query("UPDATE affaire_competence SET Id_competence=293 WHERE Id_competence=161");


mysql_query("UPDATE affaire_competence SET Id_competence=1 WHERE Id_competence=201");
mysql_query("UPDATE affaire_competence SET Id_competence=2 WHERE Id_competence=202");
mysql_query("UPDATE affaire_competence SET Id_competence=3 WHERE Id_competence=203");
mysql_query("UPDATE affaire_competence SET Id_competence=4 WHERE Id_competence=204");
mysql_query("UPDATE affaire_competence SET Id_competence=5 WHERE Id_competence=205");
mysql_query("UPDATE affaire_competence SET Id_competence=6 WHERE Id_competence=206");
mysql_query("UPDATE affaire_competence SET Id_competence=7 WHERE Id_competence=207");
mysql_query("UPDATE affaire_competence SET Id_competence=8 WHERE Id_competence=208");
mysql_query("UPDATE affaire_competence SET Id_competence=9 WHERE Id_competence=209");
mysql_query("UPDATE affaire_competence SET Id_competence=10 WHERE Id_competence=210");
mysql_query("UPDATE affaire_competence SET Id_competence=11 WHERE Id_competence=211");
mysql_query("UPDATE affaire_competence SET Id_competence=12 WHERE Id_competence=212");
mysql_query("UPDATE affaire_competence SET Id_competence=13 WHERE Id_competence=213");
mysql_query("UPDATE affaire_competence SET Id_competence=14 WHERE Id_competence=214");
mysql_query("UPDATE affaire_competence SET Id_competence=15 WHERE Id_competence=215");
mysql_query("UPDATE affaire_competence SET Id_competence=16 WHERE Id_competence=216");
mysql_query("UPDATE affaire_competence SET Id_competence=17 WHERE Id_competence=217");
mysql_query("UPDATE affaire_competence SET Id_competence=18 WHERE Id_competence=218");
mysql_query("UPDATE affaire_competence SET Id_competence=19 WHERE Id_competence=219");
mysql_query("UPDATE affaire_competence SET Id_competence=20 WHERE Id_competence=220");
mysql_query("UPDATE affaire_competence SET Id_competence=21 WHERE Id_competence=221");
mysql_query("UPDATE affaire_competence SET Id_competence=22 WHERE Id_competence=222");
mysql_query("UPDATE affaire_competence SET Id_competence=23 WHERE Id_competence=223");
mysql_query("UPDATE affaire_competence SET Id_competence=24 WHERE Id_competence=224");
mysql_query("UPDATE affaire_competence SET Id_competence=25 WHERE Id_competence=225");
mysql_query("UPDATE affaire_competence SET Id_competence=26 WHERE Id_competence=226");
mysql_query("UPDATE affaire_competence SET Id_competence=27 WHERE Id_competence=227");
mysql_query("UPDATE affaire_competence SET Id_competence=28 WHERE Id_competence=228");
mysql_query("UPDATE affaire_competence SET Id_competence=29 WHERE Id_competence=229");
mysql_query("UPDATE affaire_competence SET Id_competence=30 WHERE Id_competence=230");
mysql_query("UPDATE affaire_competence SET Id_competence=31 WHERE Id_competence=231");
mysql_query("UPDATE affaire_competence SET Id_competence=32 WHERE Id_competence=232");
mysql_query("UPDATE affaire_competence SET Id_competence=33 WHERE Id_competence=233");
mysql_query("UPDATE affaire_competence SET Id_competence=34 WHERE Id_competence=234");
mysql_query("UPDATE affaire_competence SET Id_competence=35 WHERE Id_competence=235");
mysql_query("UPDATE affaire_competence SET Id_competence=36 WHERE Id_competence=236");
mysql_query("UPDATE affaire_competence SET Id_competence=37 WHERE Id_competence=237");
mysql_query("UPDATE affaire_competence SET Id_competence=38 WHERE Id_competence=238");
mysql_query("UPDATE affaire_competence SET Id_competence=39 WHERE Id_competence=239");
mysql_query("UPDATE affaire_competence SET Id_competence=40 WHERE Id_competence=240");
mysql_query("UPDATE affaire_competence SET Id_competence=41 WHERE Id_competence=241");
mysql_query("UPDATE affaire_competence SET Id_competence=42 WHERE Id_competence=242");
mysql_query("UPDATE affaire_competence SET Id_competence=43 WHERE Id_competence=243");
mysql_query("UPDATE affaire_competence SET Id_competence=44 WHERE Id_competence=244");
mysql_query("UPDATE affaire_competence SET Id_competence=45 WHERE Id_competence=245");
mysql_query("UPDATE affaire_competence SET Id_competence=46 WHERE Id_competence=246");
mysql_query("UPDATE affaire_competence SET Id_competence=47 WHERE Id_competence=247");
mysql_query("UPDATE affaire_competence SET Id_competence=48 WHERE Id_competence=248");
mysql_query("UPDATE affaire_competence SET Id_competence=49 WHERE Id_competence=249");
mysql_query("UPDATE affaire_competence SET Id_competence=50 WHERE Id_competence=250");
mysql_query("UPDATE affaire_competence SET Id_competence=51 WHERE Id_competence=251");
mysql_query("UPDATE affaire_competence SET Id_competence=52 WHERE Id_competence=252");
mysql_query("UPDATE affaire_competence SET Id_competence=53 WHERE Id_competence=253");
mysql_query("UPDATE affaire_competence SET Id_competence=54 WHERE Id_competence=254");
mysql_query("UPDATE affaire_competence SET Id_competence=55 WHERE Id_competence=255");
mysql_query("UPDATE affaire_competence SET Id_competence=56 WHERE Id_competence=256");
mysql_query("UPDATE affaire_competence SET Id_competence=57 WHERE Id_competence=257");
mysql_query("UPDATE affaire_competence SET Id_competence=58 WHERE Id_competence=258");
mysql_query("UPDATE affaire_competence SET Id_competence=59 WHERE Id_competence=259");
mysql_query("UPDATE affaire_competence SET Id_competence=60 WHERE Id_competence=260");
mysql_query("UPDATE affaire_competence SET Id_competence=61 WHERE Id_competence=261");
mysql_query("UPDATE affaire_competence SET Id_competence=62 WHERE Id_competence=262");
mysql_query("UPDATE affaire_competence SET Id_competence=63 WHERE Id_competence=263");
mysql_query("UPDATE affaire_competence SET Id_competence=64 WHERE Id_competence=264");
mysql_query("UPDATE affaire_competence SET Id_competence=65 WHERE Id_competence=265");
mysql_query("UPDATE affaire_competence SET Id_competence=66 WHERE Id_competence=266");
mysql_query("UPDATE affaire_competence SET Id_competence=67 WHERE Id_competence=267");
mysql_query("UPDATE affaire_competence SET Id_competence=68 WHERE Id_competence=268");
mysql_query("UPDATE affaire_competence SET Id_competence=69 WHERE Id_competence=269");
mysql_query("UPDATE affaire_competence SET Id_competence=70 WHERE Id_competence=270");
mysql_query("UPDATE affaire_competence SET Id_competence=71 WHERE Id_competence=271");
mysql_query("UPDATE affaire_competence SET Id_competence=72 WHERE Id_competence=272");
mysql_query("UPDATE affaire_competence SET Id_competence=73 WHERE Id_competence=273");
mysql_query("UPDATE affaire_competence SET Id_competence=74 WHERE Id_competence=274");
mysql_query("UPDATE affaire_competence SET Id_competence=75 WHERE Id_competence=275");
mysql_query("UPDATE affaire_competence SET Id_competence=76 WHERE Id_competence=276");
mysql_query("UPDATE affaire_competence SET Id_competence=77 WHERE Id_competence=277");
mysql_query("UPDATE affaire_competence SET Id_competence=78 WHERE Id_competence=278");
mysql_query("UPDATE affaire_competence SET Id_competence=79 WHERE Id_competence=279");
mysql_query("UPDATE affaire_competence SET Id_competence=80 WHERE Id_competence=280");
mysql_query("UPDATE affaire_competence SET Id_competence=81 WHERE Id_competence=281");
mysql_query("UPDATE affaire_competence SET Id_competence=82 WHERE Id_competence=282");
mysql_query("UPDATE affaire_competence SET Id_competence=83 WHERE Id_competence=283");
mysql_query("UPDATE affaire_competence SET Id_competence=84 WHERE Id_competence=284");
mysql_query("UPDATE affaire_competence SET Id_competence=85 WHERE Id_competence=285");
mysql_query("UPDATE affaire_competence SET Id_competence=86 WHERE Id_competence=286");
mysql_query("UPDATE affaire_competence SET Id_competence=87 WHERE Id_competence=287");
mysql_query("UPDATE affaire_competence SET Id_competence=88 WHERE Id_competence=288");
mysql_query("UPDATE affaire_competence SET Id_competence=89 WHERE Id_competence=289");
mysql_query("UPDATE affaire_competence SET Id_competence=90 WHERE Id_competence=290");
mysql_query("UPDATE affaire_competence SET Id_competence=91 WHERE Id_competence=291");
mysql_query("UPDATE affaire_competence SET Id_competence=92 WHERE Id_competence=292");
mysql_query("UPDATE affaire_competence SET Id_competence=93 WHERE Id_competence=293");


mysql_query("delete FROM competence WHERE Id_competence=143");
mysql_query("delete FROM competence WHERE Id_competence=142");
mysql_query("delete FROM competence WHERE Id_competence=141");
mysql_query("delete FROM competence WHERE Id_competence=79");


mysql_query("drop table domaine");

mysql_query("
CREATE TABLE `categorie_competence` (
	`Id_cat_comp` INT(3) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_cat_comp` )
)");

mysql_query("
INSERT INTO `categorie_competence` VALUES
('1','Bases de données'),
('2','Langage'),
('3','Réseaux'),
('4','Système'),
('5','Télécoms'),
('6','Serveurs d\'application'),
('7','Techniques'),
('8','Outils de supervision'),
('9','Outils'),
('10','Méthodologie'),
('11','Multimédia'),
('12','Compression'),
('13','Fonctionnel')
");

mysql_query("drop table competence");

mysql_query("
CREATE TABLE `competence` (
	`Id_competence` INT NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	`description` TEXT,
	`Id_cat_comp` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_competence` , `Id_cat_comp` ) 
)");

mysql_query("
INSERT INTO `competence` VALUES 
('','Access','','1'),
('','Delphi','','1'),
('','Adabas','','1'),
('','Db2','','1'),
('','Oracle designer','','1'),
('','Progress','','1'),
('','Oracle','','1'),
('','Microsoft SQL server','','1'),
('','Sybase','','1'),
('','C++','','2'),
('','Cobol','','2'),
('','Gap/rpg','','2'),
('','Perl','','2'),
('','Sql','','2'),
('','Pl1','','2'),
('','Smalltalk','','2'),
('','Bull Gcos 6','','4'),
('','Xml','','2'),
('','Html','','2'),
('','Java','','2'),
('','Ns-dk','','2'),
('','Bull Gcos 7','','4'),
('','4gl','','2'),
('','Bull Gcos 8','','4'),
('','FreeBSD','','4'),
('','Ims','','4'),
('','Linux Mandrake','','4'),
('','Delphi','','2'),
('','Linux Redhat','','4'),
('','Linux Suse','','4'),
('','Windev','','2'),
('','Autre','','2'),
('','MAC OS','','4'),
('','Mpe','','4'),
('','Novell Netware','','4'),
('','Php','','2'),
('','Servlet','','2'),
('','OS/400','','4'),
('','Os/2','','4'),
('','Sap','','4'),
('','Aix','','4'),
('','Javascript','','2'),
('','Asp','','2'),
('','Jsp','','2'),
('','Uml','','2'),
('','HP ux','','4'),
('','Sco','','4'),
('','Solaris','','4'),
('','Vax','','4'),
('','Vm','','4'),
('','Vms','','4'),
('','Lan server','','3'),
('','Windows 2000','','4'),
('','Tcp/ip','','3'),
('','Windows 95-98','','4'),
('','Tuxedo','','3'),
('','As/400','','3'),
('','Windows 2003','','4'),
('','Internet','','3'),
('','Ethernet','','3'),
('','Intranet','','3'),
('','Windows XP','','4'),
('','Ftp','','3'),
('','Numeris','','3'),
('','Cisco','','3'),
('','Autre','','3'),
('','Hp openview','','3'),
('','Windows NT','','4'),
('','Wan','','3'),
('','Ntbeui','','3'),
('','FDDI','','3'), 
('','ATM','','3'),
('','HDLC','','3'),
('','PPP','','3'),
('','ADSL','','3'),
('','C','','2'),
('','C#','','2'),
('','Visual C++','','2'),
('','Scripts shell','','2'),
('','Scripts MS DOS','','2'),
('','Visual Basic','','2'),
('','MySQL','','1'),
('','VOIP','','3'),
('','TOIP','','3'),
('','WAN','','3'),
('','LAN','','3'),
('','MAN','','3'),
('','Active Directory','','4'),
('','DNS','','4'),
('','DHCP','','4'),
('','UNIX','','4'),
('','Citrix','','4'),
('','Temps réel','','4'),
('','GSM','','5'),
('','GPRS','','5'),
('','UMTS','','5'),
('','EDGE','','5'),
('','WEBSPHERE','','6'),
('','WEBLOGIC','','6'),
('','TOMCAT','','6'),
('','JBOSS','','6'),
('','Assembleur x86','','7'),
('','MMX/SSE','','7'),
('','Assembleur GCC','','7'),
('','MIPS','','7'),
('','LINUX RTAI','','7'),
('','VXWORKS','','7'),
('','VHDL','','7'),
('','PAL, PLA, FPGA','','7'),
('','NAGIOS / CAKTI','','8'),
('','HP OpenView','','8'),
('','LANDESK','','9'),
('','TRACKIT','','9'),
('','SMS','','9'),
('','INSTALLSHIELD','','9'),
('','BUSINESS OBJECT','','9'),
('','MS Project','','10'),
('','COGNOS','','10'),
('','UML','','10'),
('','CMMi','','10'),
('','ITIL','','10'),
('','OBJECTEERING','','10'),
('','CLEARCASE','','10'),
('','RATIONAL ROSE','','10'),
('','ECLIPSE','','10'),
('','WSAD','','10'),
('','DirectShow','','11'),
('','DirectGraphics','','11'),
('','OpenGL','','11'),
('','Video4Linux','','11'),
('','VIDEO MPEG-2','','12'),
('','VIDEO MPEG-4 AVC/H.264','','12'),
('','AUDIO MPEG','','12'),
('','AUDIO DOLBY','','12'),
('','Management d\'équipes','','13'),
('','Avant vente','','13'),
('','Veille Technologique','','13'),
('','Commercial','','13')
");


?>
<script language='javascript' type='text/javascript'>
alert('La mise à jour des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>