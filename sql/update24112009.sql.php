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

mysql_query("ALTER TABLE `ressource` DROP column Id_etat_matrimonial");
mysql_query("ALTER TABLE `ressource` ADD column Id_contrat_proservia INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `ressource` ADD column th INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `candidature` ADD column staff INT(1) NOT NULL DEFAULT '0'");

mysql_query("
CREATE TABLE `contrat_proservia` (
  `Id_contrat_proservia` int(1) unsigned NOT NULL auto_increment,
  `libelle` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_contrat_proservia`)
)");

mysql_query("
INSERT INTO `contrat_proservia` VALUES
('','PCP'),
('','CPC'),
('','CNO')
");

mysql_query("INSERT INTO `exp_info` VALUES ('','> 20 ans')");
mysql_query("INSERT INTO `nature_candidature` VALUES ('','CAPEMPLOI')");
mysql_query("INSERT INTO `action_mener` VALUES ('','A nouveau disponible')");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` DROP column Id_etat_matrimonial");
mysql_query("ALTER TABLE `ressource` ADD column Id_contrat_proservia INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `ressource` ADD column th INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `candidature` ADD column staff INT(1) NOT NULL DEFAULT '0'");

mysql_query("
CREATE TABLE `contrat_proservia` (
  `Id_contrat_proservia` int(1) unsigned NOT NULL auto_increment,
  `libelle` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_contrat_proservia`)
)");

mysql_query("
INSERT INTO `contrat_proservia` VALUES
('','PCP'),
('','CPC'),
('','CNO')
");

mysql_query("INSERT INTO `exp_info` VALUES ('','> 20 ans')");
mysql_query("INSERT INTO `nature_candidature` VALUES ('','CAPEMPLOI')");
mysql_query("INSERT INTO `action_mener` VALUES ('','A nouveau disponible')");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` DROP column Id_etat_matrimonial");
mysql_query("ALTER TABLE `ressource` ADD column Id_contrat_proservia INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `ressource` ADD column th INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `candidature` ADD column staff INT(1) NOT NULL DEFAULT '0'");

mysql_query("
CREATE TABLE `contrat_proservia` (
  `Id_contrat_proservia` int(1) unsigned NOT NULL auto_increment,
  `libelle` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_contrat_proservia`)
)");

mysql_query("
INSERT INTO `contrat_proservia` VALUES
('','PCP'),
('','CPC'),
('','CNO')
");

mysql_query("INSERT INTO `exp_info` VALUES ('','> 20 ans')");
mysql_query("INSERT INTO `nature_candidature` VALUES ('','CAPEMPLOI')");
mysql_query("INSERT INTO `action_mener` VALUES ('','A nouveau disponible')");


//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` DROP column Id_etat_matrimonial");
mysql_query("ALTER TABLE `ressource` ADD column Id_contrat_proservia INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `ressource` ADD column th INT(1) NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `candidature` ADD column staff INT(1) NOT NULL DEFAULT '0'");

mysql_query("
CREATE TABLE `contrat_proservia` (
  `Id_contrat_proservia` int(1) unsigned NOT NULL auto_increment,
  `libelle` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`Id_contrat_proservia`)
)");

mysql_query("
INSERT INTO `contrat_proservia` VALUES
('','PCP'),
('','CPC'),
('','CNO')
");

mysql_query("INSERT INTO `exp_info` VALUES ('','> 20 ans')");
mysql_query("INSERT INTO `nature_candidature` VALUES ('','CAPEMPLOI')");
mysql_query("INSERT INTO `action_mener` VALUES ('','A nouveau disponible')");


?>