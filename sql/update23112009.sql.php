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

mysql_query("
CREATE TABLE `directeur_agence` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_agence` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_utilisateur`, `Id_agence`)
)");

mysql_query("
INSERT INTO `directeur_agence` VALUES
('caroline.secq','LIL'),
('jeanmarc.alpago','TOU'),
('jeanmarc.alpago','BDX'),
('olivier.savier','PAR ISP'),
('olivier.savier','PAR TSM'),
('olivier.savier','PAR B&A'),
('nicolas.cardon','001'),
('nicolas.cardon','REN'),
('nicolas.cardon','LAN'),
('nicolas.cardon','TRS'),
('olivier.lagree','REN'),
('beatrice.fichera','LAN'),
('thomas.bertin','CAE'),
('thomas.bertin','LHA'),
('thomas.bertin','ROU'),
('philippe.langlet','LYO'),
('philippe.langlet','SOP'),
('philippe.langlet','AIX'),
('patrick.wiscart','PAR TSM'),
('vincent.barre','NIO')
");

mysql_query("
CREATE TABLE `responsable_pole` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_pole` INT(1) NOT NULL default '0',
  PRIMARY KEY  (`Id_utilisateur`, `Id_pole`)
)");

mysql_query("
INSERT INTO `responsable_pole` VALUES
('eric.vrignaud','1'),
('brigitte.cohen','3'),
('jerome.vittu','4'),
('christian.dumont','5')
");



//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `directeur_agence` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_agence` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_utilisateur`, `Id_agence`)
)");

mysql_query("
INSERT INTO `directeur_agence` VALUES
('caroline.secq','LIL'),
('jeanmarc.alpago','TOU'),
('jeanmarc.alpago','BDX'),
('olivier.savier','PAR ISP'),
('olivier.savier','PAR TSM'),
('olivier.savier','PAR B&A'),
('nicolas.cardon','001'),
('nicolas.cardon','REN'),
('nicolas.cardon','LAN'),
('nicolas.cardon','TRS'),
('olivier.lagree','REN'),
('beatrice.fichera','LAN'),
('thomas.bertin','CAE'),
('thomas.bertin','LHA'),
('thomas.bertin','ROU'),
('philippe.langlet','LYO'),
('philippe.langlet','SOP'),
('philippe.langlet','AIX'),
('patrick.wiscart','PAR TSM'),
('vincent.barre','NIO')
");

mysql_query("
CREATE TABLE `responsable_pole` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_pole` INT(1) NOT NULL default '0',
  PRIMARY KEY  (`Id_utilisateur`, `Id_pole`)
)");

mysql_query("
INSERT INTO `responsable_pole` VALUES
('eric.vrignaud','1'),
('brigitte.cohen','3'),
('jerome.vittu','4'),
('christian.dumont','5')
");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `directeur_agence` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_agence` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_utilisateur`, `Id_agence`)
)");

mysql_query("
INSERT INTO `directeur_agence` VALUES
('caroline.secq','LIL'),
('jeanmarc.alpago','TOU'),
('jeanmarc.alpago','BDX'),
('olivier.savier','PAR ISP'),
('olivier.savier','PAR TSM'),
('olivier.savier','PAR B&A'),
('nicolas.cardon','001'),
('nicolas.cardon','REN'),
('nicolas.cardon','LAN'),
('nicolas.cardon','TRS'),
('olivier.lagree','REN'),
('beatrice.fichera','LAN'),
('thomas.bertin','CAE'),
('thomas.bertin','LHA'),
('thomas.bertin','ROU'),
('philippe.langlet','LYO'),
('philippe.langlet','SOP'),
('philippe.langlet','AIX'),
('patrick.wiscart','PAR TSM'),
('vincent.barre','NIO')
");

mysql_query("
CREATE TABLE `responsable_pole` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_pole` INT(1) NOT NULL default '0',
  PRIMARY KEY  (`Id_utilisateur`, `Id_pole`)
)");

mysql_query("
INSERT INTO `responsable_pole` VALUES
('eric.vrignaud','1'),
('brigitte.cohen','3'),
('jerome.vittu','4'),
('christian.dumont','5')
");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `directeur_agence` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_agence` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_utilisateur`, `Id_agence`)
)");

mysql_query("
INSERT INTO `directeur_agence` VALUES
('caroline.secq','LIL'),
('jeanmarc.alpago','TOU'),
('jeanmarc.alpago','BDX'),
('olivier.savier','PAR ISP'),
('olivier.savier','PAR TSM'),
('olivier.savier','PAR B&A'),
('nicolas.cardon','001'),
('nicolas.cardon','REN'),
('nicolas.cardon','LAN'),
('nicolas.cardon','TRS'),
('olivier.lagree','REN'),
('beatrice.fichera','LAN'),
('thomas.bertin','CAE'),
('thomas.bertin','LHA'),
('thomas.bertin','ROU'),
('philippe.langlet','LYO'),
('philippe.langlet','SOP'),
('philippe.langlet','AIX'),
('patrick.wiscart','PAR TSM'),
('vincent.barre','NIO')
");

mysql_query("
CREATE TABLE `responsable_pole` (
  `Id_utilisateur` varchar(100) NOT NULL default '',
  `Id_pole` INT(1) NOT NULL default '0',
  PRIMARY KEY  (`Id_utilisateur`, `Id_pole`)
)");

mysql_query("
INSERT INTO `responsable_pole` VALUES
('eric.vrignaud','1'),
('brigitte.cohen','3'),
('jerome.vittu','4'),
('christian.dumont','5')
");


?>