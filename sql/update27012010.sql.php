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
CREATE TABLE `destinataire_cd` (
    `mail` VARCHAR(100) NOT NULL default '',
	`Id_agence` VARCHAR(10) NOT NULL default '',
	PRIMARY KEY ( `mail`,`Id_agence` ) 
)");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`) VALUES 
('sandra.david@proservia.fr', 'NIO'),
('sandra.david@proservia.fr', 'TRS'),
('sandra.david@proservia.fr', 'BDX'),
('sandra.david@proservia.fr', 'TOU'),
('sandra.david@proservia.fr', 'SOP'),
('sandra.david@proservia.fr', 'LYO'),
('audrey.tetedoie@proservia.fr', 'ROU'),
('audrey.tetedoie@proservia.fr', 'LHA'),
('audrey.tetedoie@proservia.fr', 'CAE'),
('audrey.tetedoie@proservia.fr', 'LIL'),
('julie.malary@proservia.fr', 'PAR ENE'),
('julie.malary@proservia.fr', 'PAR MES'),
('julie.malary@proservia.fr', 'PAR TMS'),
('julie.malary@proservia.fr', 'CAR'),
('alexandra.puyol@proservia.fr', 'REN'),
('alexandra.puyol@proservia.fr', 'LAN'),
('alexandra.puyol@proservia.fr', '001'),
('guenola.baudry@proservia.fr', 'PAR B&A'),
('guenola.baudry@proservia.fr', 'PAR ISP'),
('delphine.richard@ovialis.fr', 'ONA'),
('delphine.richard@ovialis.fr', 'ONI'),
('delphine.richard@ovialis.fr', 'ORE'),
('delphine.richard@ovialis.fr', 'OSI')
");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("
CREATE TABLE `destinataire_cd` (
    `mail` VARCHAR(100) NOT NULL default '',
	`Id_agence` VARCHAR(10) NOT NULL default '',
	PRIMARY KEY ( `mail`,`Id_agence` ) 
)");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`) VALUES 
('sandra.david@proservia.fr', 'NIO'),
('sandra.david@proservia.fr', 'TRS'),
('sandra.david@proservia.fr', 'BDX'),
('sandra.david@proservia.fr', 'TOU'),
('sandra.david@proservia.fr', 'SOP'),
('sandra.david@proservia.fr', 'LYO'),
('audrey.tetedoie@proservia.fr', 'ROU'),
('audrey.tetedoie@proservia.fr', 'LHA'),
('audrey.tetedoie@proservia.fr', 'CAE'),
('audrey.tetedoie@proservia.fr', 'LIL'),
('julie.malary@proservia.fr', 'PAR ENE'),
('julie.malary@proservia.fr', 'PAR MES'),
('julie.malary@proservia.fr', 'PAR TMS'),
('julie.malary@proservia.fr', 'CAR'),
('alexandra.puyol@proservia.fr', 'REN'),
('alexandra.puyol@proservia.fr', 'LAN'),
('alexandra.puyol@proservia.fr', '001'),
('guenola.baudry@proservia.fr', 'PAR B&A'),
('guenola.baudry@proservia.fr', 'PAR ISP'),
('delphine.richard@ovialis.fr', 'ONA'),
('delphine.richard@ovialis.fr', 'ONI'),
('delphine.richard@ovialis.fr', 'ORE'),
('delphine.richard@ovialis.fr', 'OSI')
");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("
CREATE TABLE `destinataire_cd` (
    `mail` VARCHAR(100) NOT NULL default '',
	`Id_agence` VARCHAR(10) NOT NULL default '',
	PRIMARY KEY ( `mail`,`Id_agence` ) 
)");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`) VALUES 
('sandra.david@proservia.fr', 'NIO'),
('sandra.david@proservia.fr', 'TRS'),
('sandra.david@proservia.fr', 'BDX'),
('sandra.david@proservia.fr', 'TOU'),
('sandra.david@proservia.fr', 'SOP'),
('sandra.david@proservia.fr', 'LYO'),
('audrey.tetedoie@proservia.fr', 'ROU'),
('audrey.tetedoie@proservia.fr', 'LHA'),
('audrey.tetedoie@proservia.fr', 'CAE'),
('audrey.tetedoie@proservia.fr', 'LIL'),
('julie.malary@proservia.fr', 'PAR ENE'),
('julie.malary@proservia.fr', 'PAR MES'),
('julie.malary@proservia.fr', 'PAR TMS'),
('julie.malary@proservia.fr', 'CAR'),
('alexandra.puyol@proservia.fr', 'REN'),
('alexandra.puyol@proservia.fr', 'LAN'),
('alexandra.puyol@proservia.fr', '001'),
('guenola.baudry@proservia.fr', 'PAR B&A'),
('guenola.baudry@proservia.fr', 'PAR ISP'),
('delphine.richard@ovialis.fr', 'ONA'),
('delphine.richard@ovialis.fr', 'ONI'),
('delphine.richard@ovialis.fr', 'ORE'),
('delphine.richard@ovialis.fr', 'OSI')
");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("
CREATE TABLE `destinataire_cd` (
    `mail` VARCHAR(100) NOT NULL default '',
	`Id_agence` VARCHAR(10) NOT NULL default '',
	PRIMARY KEY ( `mail`,`Id_agence` ) 
)");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`) VALUES 
('sandra.david@proservia.fr', 'NIO'),
('sandra.david@proservia.fr', 'TRS'),
('sandra.david@proservia.fr', 'BDX'),
('sandra.david@proservia.fr', 'TOU'),
('sandra.david@proservia.fr', 'SOP'),
('sandra.david@proservia.fr', 'LYO'),
('audrey.tetedoie@proservia.fr', 'ROU'),
('audrey.tetedoie@proservia.fr', 'LHA'),
('audrey.tetedoie@proservia.fr', 'CAE'),
('audrey.tetedoie@proservia.fr', 'LIL'),
('julie.malary@proservia.fr', 'PAR ENE'),
('julie.malary@proservia.fr', 'PAR MES'),
('julie.malary@proservia.fr', 'PAR TMS'),
('julie.malary@proservia.fr', 'CAR'),
('alexandra.puyol@proservia.fr', 'REN'),
('alexandra.puyol@proservia.fr', 'LAN'),
('alexandra.puyol@proservia.fr', '001'),
('guenola.baudry@proservia.fr', 'PAR B&A'),
('guenola.baudry@proservia.fr', 'PAR ISP'),
('delphine.richard@ovialis.fr', 'ONA'),
('delphine.richard@ovialis.fr', 'ONI'),
('delphine.richard@ovialis.fr', 'ORE'),
('delphine.richard@ovialis.fr', 'OSI')
");

?>