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

mysql_query("ALTER TABLE `contrat_delegation` ADD column Id_sous_traitant INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` DROP column responsable");

mysql_query("
INSERT INTO `menu` VALUES
('','Sous-Traitant','../com/index.php?a=creer&class=SousTraitant','d'),
('','Sous-Traitant','../com/index.php?a=consulterSousTraitant','g')
");

mysql_query("
INSERT INTO `groupe_ad_menu` VALUES
('1','52'),
('1','53'),
('2','52'),
('2','53'),
('3','52'),
('3','53'),
('4','52'),
('4','53'),
('5','52'),
('5','53'),
('6','52'),
('6','53'),
('7','52'),
('7','53'),
('8','52'),
('8','53')
");	


mysql_query("
CREATE TABLE `sous_traitant` (
  `Id_sous_traitant` int(11) unsigned NOT NULL auto_increment,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `societe` varchar(255) NOT NULL default '',
  `adresse` varchar(255) NOT NULL default '',
  `siret` varchar(20) NOT NULL default '',
  `ape` varchar(20) NOT NULL default '',
  `tarif` varchar(20) NOT NULL default '',
  `commentaire` TEXT,
  PRIMARY KEY  (`Id_sous_traitant`)
)");


mysql_query("
INSERT INTO `sous_traitant` VALUES
('','ANDRE', 'Gil', 'CLESYS', '', '', '', 0, ''),
('','BALLUET', 'Elodie', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','BARLET', 'ERIC', '', '', '', '', 350, 'Le 19 novembre est jour férié à Monaco. Nous sommes obligés de facturer seulement 4 jours au client, mais nous payons les 5 jours de présence d\'Eric.\r\n'),
('','BELMONTE', 'Gérard', 'PROGEI', '21 allée de la pépinière 95130 FRANCONVILLE', '      ', '', 447, ''),
('','BILLY ', 'Stéphane', 'CSIE Technologies', 'CSIE Technologies - Bat 3 - Parc de Canteranne avenue de Canteranne 33600 PESSAC', '41132190400020', '6202A', 370, ''),
('','BOST', 'Melvine', 'COGIVEA', '85 rue des Maréchaux, 79140 ECHIRE', '50156661600014', '722C', 168, ''),
('','BOUDAUD', 'Gérald', 'COGIVEA', '85 rue des Maréchaux, 79410 ECHIRE', '50156661600014', '722C', 258, ''),
('','BOUVET', 'Christophe', '', '', '', '', 0, ''),
('','BREGEON', 'Sébastien', 'Lynt', '', '', '', 330, ''),
('','BRISSON ', 'Bertrand', 'RANDSTAD', '10 rue des Canonniers 59000 LILLE ', '', '', 130, '+ part patronale ticket restaurant = 3,80 euros'),
('','CHEVRIER', 'David', 'SARL Blue XML', '40, Boulevard Jean Ingres 44100 Nantes', '482 016 276 00024', '5829B', 290, ''),
('','CLERGET', 'OLIVIER', 'OCFSI', '8 bis rue du pré de l&#039;arche 93360 NEUILLY PLAISANCE', '48748348900026', '721Z', 475, '<p>Versement d\'une prime exceptionnelle de 100&euro; par jour travaill&eacute; pour le mois de juillet 2009&nbsp;uniquement (relatif aux d&eacute;placements du mois de juin 2009 en Grande Bretagne pour le compte de BNP PARIBAS - vu avec Microsoft).</p>'),
('','COHEN', 'Philippe', 'COFORM', '', '', '', 320, ''),
('','COUDERC', 'Claude', 'CADRES EN MISSION', '12 rue du Chapeau Rouge 44000 NANTES', '424 151 678', '', 530, ''),
('','DELAUNAY', 'Christian', 'FTEL', '', '', '', 420, ''),
('','DEMANDRE', 'Michel', 'GILEM', '18-20 rue Pasteur 94278 Le Kremlin Bicêtre cedex', 'B 344 054 358', '', 440, '<p>Michel Demandre GILEM</p>'),
('','DENIAU', 'Jérôme', 'Inform', '', '', '', 0, 'Le 28 et 29 /08: 880 € / jour\r\nLe 9 et 10 /09: 520 € / jour'),
('','DIMA', 'Ioana', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','Du Saussay', 'Alexis', '', '', '', '', 280, '<p>Base de 40&euro; de l\'heure. Pas de majoration appliqu&eacute;e sur horaire de nuit.</p>'),
('','DUPONT', 'Cédric', 'LYNT', '2 rue Robert Schuman', '', '', 300, ''),
('','DUPONT', 'Stéphanie', 'COGIVEA', '85 rue des Maréchaux, 79140 ECHIRE', '50156661600014', '722C', 168, ''),
('','EXER DATA', 'COM', 'EXER DATA COM', 'MARCQ EN BAOREUL ', '', '', 450, ''),
('','GAFFORY', 'Sandra', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','GALALEM', 'Solenn', 'PHONE REGIE', '', '', '', 175, ''),
('','GARIN', 'Sébastien', 'PHONE REGIE', '', '', '', 175, ''),
('','GONCALVES', 'Manuel', 'GONCALVES', '3 rue de Paris 91400 SACLAY', '503 858 250 00015', '', 416, ''),
('','GUERAULT', 'Stéphanie', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','GUILBAUD', 'Christophe', 'LYNT', '2, rue Robert Schuman', '', '', 392, ''),
('','JACQUEMIN', 'INGRID', 'EXPECTRA', '', '', '', 292, ''),
('','Jerome', 'Mique', '', '', '', '', 340, ''),
('','JINIOUX', 'Christelle', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','JOLLY', 'Marc', 'TIPINFO (EURL)', '4 allée des Roses - La Clairière - 78120 RAMBOUILLET', ' 503441119', '', 430, '<p>TIPINFO&nbsp; /&nbsp; EURL</p>\r\n<p>SIRET : 503441119</p>\r\n<p>SIREN :50344111900013</p>\r\nTVA : FR77503444119</span></div>\r\nCompte bancaire TIPINFO&nbsp;: 30066 10827 00010729101 51&nbsp;au CIC de Rambouillet'),
('','LARHANTEC', 'Yann', '', '', '', '', 0, ''),
('','Larhantec', 'Yann', 'ALC', '', '', '', 0, ''),
('','LERET', 'Jacques', 'Opus Technologies', '16-18 rue Morane Saulnier 78140 VELIZY VILLACOUBLAY', 'B383546298', '6202A', 600, '<p>Jacques LERET sous traitant Opus Technologies mission au 4/5 fin de mission fin avril et perl&eacute;e sur mai et juin.</p>'),
('','LIONCERF', 'François', 'Indépendant', '26 cours Ferdinand de Lesseps 92500 RUEIL-MALMAISON', '49379283200013', '', 378.5, '<p>Fran&ccedil;ois LIONCERF ind&eacute;pendant embauch&eacute; chez PREDICA le 1/07/09</p>'),
('','LOISEL', 'Christophe', 'SARL BLUEXML', '40, Boulevard Jean Ingres 44100 Nantes ', '482 016 276 00024', '5829B', 290, ''),
('','LUCAZEAU', 'Alexandre', 'COGIVEAU', '85 rue des Maréchaux, 79140 ECHIRE', '50156661600014', '722C', 307, ''),
('','MABENO', 'Jack', 'NOETEK', '', '', '', 0, ''),
('','MARTIN', 'Guillaume', 'OVIALIS', '1 rue Augustin Fresnel, La Fleuriaye, 44470 Carquefou Cedex', '50945644800018', '6202A', 284, ''),
('','MAUXION', 'Stéphanie', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 0, ''),
('','MENEY', 'PASCAL', 'PDA4WORK', '78C BOULEVARD DU GENERAL LECLERC 59100 ROUBAIX', '', '', 200, '<p>pascal.meney@pda4work.com</p>'),
('','MICAULT', 'Laetitia', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','MONNOT', 'Stéphane', 'ESKAL', '', '', '', 410, ''),
('','MORIN', 'Sophie', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','MOUANGA', 'LUDORIS', 'SB.Bd', '142 rue Casanova - 93200 Saint Denis ', '43505283200019', '', 420, ''),
('','NAICKER', 'Salomi', 'PHONE REGIE', '', '', '', 170, ''),
('','PINEAU', 'ERIC', 'PROD EXPERT', '86 rue de Cléry', '491904488', '', 400, ''),
('','PINEAU', 'Wilfrid', 'IDEIA', '35 rue de Chanzy 75011 PARIS', 'B433085420', '6202A', 472.8, '<p>Wilfrid PINEAU embauch&eacute; chez PREDICA le 1/07/09</p>'),
('','QUAEYBEUR', 'Alain', 'SPI Communication', '40 rue de l&#039;Eglise 75015 PARIS', 'B394106959', '804 C', 504.4, '<p>Alain QUAEYBEUR sous-traitant de SPI Communication</p>'),
('','QUILLEVERC', 'Maryline', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris Cedex 15', '301251880', '8299Z', 175, ''),
('','RIOU', 'Edwina', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','SABIN', 'Jérome', 'indépendant', '5 rue Carl Von Linné 77420 Champs sur Marne', '42271502900019', '', 465.6, ''),
('','SALAM', 'Ismail', 'OCEANE Consulting', '43 rue Saint Augustin 75002 PARIS', 'B442944930', '6202A', 320, '<p>Ismail SALAM sous traitant OCEANE Consulting&nbsp; fin mission le 29/05/09</p>'),
('','SAMAIR', 'Thanvad', '', '', '', '', 600, ''),
('','SEBODE', 'Emilie', 'AZ CORPORATIONS', '3, rue Cambronne 75740 Paris cedex 15', '301251880', '8299Z', 175, ''),
('','THUAULT', 'Florian', 'PANDA Services', '4 avenue Jean JAURES – 94220 CHARENTON LE PONT', '478 090 608', '', 420, ''),
('','TOURRAND', 'Véronique', 'G-FIT', '142 rue de Charonne 75011 PARIS', 'B 4180651816', '722Z', 400, ''),
('','TUTTOBENE', 'CHRISTOPHE', 'MANPOWER', 'IMMEUBLE EUROSUD 213 BD DE TURIN 59777 EURALILLE', '', '', 178, '')
");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `contrat_delegation` ADD column Id_sous_traitant INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` DROP column responsable");

mysql_query("
INSERT INTO `menu` VALUES
('','Sous-Traitant','../com/index.php?a=creer&class=SousTraitant','d'),
('','Sous-Traitant','../com/index.php?a=consulterSousTraitant','g')
");

mysql_query("
INSERT INTO `groupe_ad_menu` VALUES
('1','52'),
('1','53'),
('2','52'),
('2','53'),
('3','52'),
('3','53'),
('4','52'),
('4','53'),
('5','52'),
('5','53'),
('6','52'),
('6','53'),
('7','52'),
('7','53'),
('8','52'),
('8','53')
");	


mysql_query("
CREATE TABLE `sous_traitant` (
  `Id_sous_traitant` int(11) unsigned NOT NULL auto_increment,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `societe` varchar(255) NOT NULL default '',
  `adresse` varchar(255) NOT NULL default '',
  `siret` varchar(20) NOT NULL default '',
  `ape` varchar(20) NOT NULL default '',
  `commentaire` TEXT,
  PRIMARY KEY  (`Id_sous_traitant`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `contrat_delegation` ADD column Id_sous_traitant INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` DROP column responsable");

mysql_query("
INSERT INTO `menu` VALUES
('','Sous-Traitant','../com/index.php?a=creer&class=SousTraitant','d'),
('','Sous-Traitant','../com/index.php?a=consulterSousTraitant','g')
");

mysql_query("
INSERT INTO `groupe_ad_menu` VALUES
('1','52'),
('1','53'),
('2','52'),
('2','53'),
('3','52'),
('3','53'),
('4','52'),
('4','53'),
('5','52'),
('5','53'),
('6','52'),
('6','53'),
('7','52'),
('7','53'),
('8','52'),
('8','53')
");	


mysql_query("
CREATE TABLE `sous_traitant` (
  `Id_sous_traitant` int(11) unsigned NOT NULL auto_increment,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `societe` varchar(255) NOT NULL default '',
  `adresse` varchar(255) NOT NULL default '',
  `siret` varchar(20) NOT NULL default '',
  `ape` varchar(20) NOT NULL default '',
  `commentaire` TEXT,
  PRIMARY KEY  (`Id_sous_traitant`)
)");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `contrat_delegation` ADD column Id_sous_traitant INT NOT NULL DEFAULT '0'");
mysql_query("ALTER TABLE `action` DROP column responsable");

mysql_query("
INSERT INTO `menu` VALUES
('','Sous-Traitant','../com/index.php?a=creer&class=SousTraitant','d'),
('','Sous-Traitant','../com/index.php?a=consulterSousTraitant','g')
");

mysql_query("
INSERT INTO `groupe_ad_menu` VALUES
('1','52'),
('1','53'),
('2','52'),
('2','53'),
('3','52'),
('3','53'),
('4','52'),
('4','53'),
('5','52'),
('5','53'),
('6','52'),
('6','53'),
('7','52'),
('7','53'),
('8','52'),
('8','53')
");	


mysql_query("
CREATE TABLE `sous_traitant` (
  `Id_sous_traitant` int(11) unsigned NOT NULL auto_increment,
  `nom` varchar(70) NOT NULL,
  `prenom` varchar(70) NOT NULL,
  `societe` varchar(255) NOT NULL default '',
  `adresse` varchar(255) NOT NULL default '',
  `siret` varchar(20) NOT NULL default '',
  `ape` varchar(20) NOT NULL default '',
  `commentaire` TEXT,
  PRIMARY KEY  (`Id_sous_traitant`)
)");



?>