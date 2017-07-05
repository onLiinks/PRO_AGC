<?php
###############################################
#													#
# 		Script permettant de créer les agences                				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

mysql_query("
CREATE TABLE `agence` (
	`Id_agence` VARCHAR(10) NOT NULL default '',
	`libelle` VARCHAR(255) NOT NULL default '',
	`Id_societe` INT(2) NOT NULL default '0',
	PRIMARY KEY ( `Id_agence`)
)");


mysql_query("
INSERT INTO `agence` VALUES
('001','Nantes','1'),
('AIX','Aix','1'),
('ALC','Alcyon','1'),
('ANE','Altique net','1'),
('BDX','Bordeaux','1'),
('CAE','Caen','1'),
('CAR','Wiztivi Carquefou','3'),
('ELI','Elitex','1'),
('LAN','Lannion','1'),
('LHA','Le Havre','1'),
('LIL','Lille','1'),
('LYO','Lyon','1'),
('NIO','Niort','1'),
('ONA','Ovialis Nantes','2'),
('ONI','Ovialis Niort','2'),
('ORE','Ovialis Rennes','2'),
('OSI','Ovialis Siège','2'),
('PAR B&A','Paris B&A','1'),
('PAR ISP','Paris ISP','1'),
('PAR TSM','Paris TSM','1'),
('REN','Rennes','1'),
('ROU','Rouen','1'),
('SOP','Sophia','1'),
('TOU','Toulouse','1'),
('TRS','Tours','1')
");

mysql_query("DROP TABLE `bdd_societe`");

mysql_query("ALTER TABLE `utilisateur` change Id_bdd_societe Id_societe INT(1) NOT NULL DEFAULT 0");

mysql_query("
CREATE TABLE `societe` (
	`Id_societe` int(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_societe` ) 
)");

mysql_query("
INSERT INTO `societe` VALUES
	('','PROSERVIA'),
	('','OVIALIS'),
	('','WIZTIVI')
");

mysql_query("ALTER TABLE `utilisateur` change Id_bdd_societe Id_societe INT(1) NOT NULL DEFAULT 0");


mysql_query("
ALTER TABLE `utilisateur` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_utilisateur` , `statut`, `archive`, `Id_agence`, `Id_societe` )
");



?>