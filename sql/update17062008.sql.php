<?php
###############################################
#													#
# 		Script permettant de cr�er la base de donn�es				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('gescom') or die ('pas de connection'); 


mysql_query("
CREATE TABLE `langue` (
    `Id_langue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_langue` ) 
)");

mysql_query("
CREATE TABLE `bilan_utilisateur` (
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	`responsable` VARCHAR(255) NOT NULL default '',
	`mois` INT(2) NOT NULL default '0',
	`annee` INT(4) NOT NULL default '0',
	`date_creation` DATETIME,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_utilisateur`, `mois`, `annee` )
)");


mysql_query("
CREATE TABLE `candidature` (
	`Id_candidature` INT NOT NULL AUTO_INCREMENT,
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`date` DATE,
	`Id_nature` INT NOT NULL default '0',
	`Id_etat` INT NOT NULL default '0',
	`createur` VARCHAR(255) NOT NULL default '',
	`commentaire` TEXT,
	`archive` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_candidature` , `Id_ressource` , `Id_nature` , `Id_etat` , `createur`)
)");


mysql_query("
CREATE TABLE `entretien` (
	`Id_entretien` INT NOT NULL AUTO_INCREMENT,
	`Id_candidature` INT NOT NULL default '0',
	`Id_recruteur` VARCHAR(255) NOT NULL default '',
	`Id_commercial` VARCHAR(255) NOT NULL default '',
	`Id_disponibilite` INT NOT NULL default '0',
	`ps_inf` DOUBLE NOT NULL default '0',
	`ps_sup` DOUBLE NOT NULL default '0',
	`attente_pro` TEXT,
	`Id_profil_envisage` INT NOT NULL default '0',
	`avancement_recherche` VARCHAR(255) NOT NULL default '',
	`connaissance_proservia` INT(1) NOT NULL default '0',
	`commentaire` TEXT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	PRIMARY KEY ( `Id_entretien`, `Id_candidature`, `Id_recruteur`, `date_creation` )
)");

mysql_query("
CREATE TABLE `mobilite` (
    `Id_mobilite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_mobilite` ) 
)");

mysql_query("
CREATE TABLE `disponibilite` (
    `Id_disponibilite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_disponibilite` ) 
)");

mysql_query("
CREATE TABLE `entretien_mobilite` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_mobilite` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien` , `Id_mobilite` ) 
)");

mysql_query("
CREATE TABLE `entretien_langue` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_langue` INT NOT NULL default '0',
	`niveau` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_entretien` , `Id_langue` ) 
)");

mysql_query("
CREATE TABLE `entretien_critere` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_critere` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien` , `Id_critere` ) 
)");

mysql_query("
CREATE TABLE `critere_recherche` (
    `Id_critere_recherche` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_critere_recherche` ) 
)");


mysql_query("
CREATE TABLE `raison_perdue` (
	`Id_raison_perdue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_raison_perdue` )
)");


mysql_query("
CREATE TABLE `indemnite` (
	`Id_indemnite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`type` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_indemnite` ) 
)");



mysql_query("
CREATE TABLE `cd_indemnite` (
	`Id_contrat_delegation` INT NOT NULL default '0',
	`Id_indemnite` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_contrat_delegation` , `Id_indemnite` ) 
)");


mysql_query("
CREATE TABLE `nature_candidature` (
	`Id_nature_candidature` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_nature_candidature` ) 
)");

mysql_query("
CREATE TABLE `etat_candidature` (
	`Id_etat_candidature` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_candidature` ) 
)");

mysql_query("
CREATE TABLE `historique_candidature` (
    `Id_candidature` INT NOT NULL default '0',
	`Id_etat` INT NOT NULL default '0',
	`date` DATETIME,
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_candidature` , `Id_etat`, `date`, `Id_utilisateur` )
)");

mysql_query("
CREATE TABLE `departement` (
	`code` VARCHAR(2) NOT NULL default '',
	`nom` VARCHAR(100) NOT NULL default '',
	PRIMARY KEY ( `code` )
)");

mysql_query("
CREATE TABLE `pays` (
    `id` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(80) NOT NULL default '',
	`code` CHAR(2) NOT NULL default '',
	PRIMARY KEY ( `id` )
)");

mysql_query("
INSERT INTO `nature_candidature` VALUES
('','ANPE'),
('','APEC CVth�que'),
('','APEC Annonce'),
('','R�gionJob CVth�que'),
('','R�gionJob Annonce'),
('','Spontan�e'),
('','CVWeb Proservia'),
('','Les Jeudis Annonce'),
('','Les Jeudis CVth�que'),
('','Cooptation'),
('','Forum/Salon (pr�cisez)')
");

mysql_query("
INSERT INTO `etat_candidature` VALUES
('','Non lu'),
('','Lu'),
('','Convoqu�'),
('','1er entretien'),
('','2nd entretien'),
('','Valid�'),
('','Non valid�'),
('','Embauch�')
");

mysql_query("
INSERT INTO `raison_perdue` VALUES
('','Trop cher'),
('','Concurrent meilleur'),
('','Technique'),
('','D�lai')
");

mysql_query("
INSERT INTO `langue` VALUES
('','Anglais'),
('','Espagnol'),
('','Allemand'),
('','Italien')
");

mysql_query("
INSERT INTO `indemnite` VALUES
('','Indemnit�s de repas (Ticket Restaurant)','1'),
('','Repas du soir','1'),
('','Forfait SNCF 2�me classe','1'),
('','Indemnit�s kilom�triques','1'),
('','P�age','1'),
('','Carburant','1'),
('','Taxi','1'),
('','Transport en commun','1'),
('','H�bergement','1'),
('','Indemnit�s de repas (Ticket Restaurant)','2'),
('','Repas du soir (sur justificatif)','2'),
('','Repas du soir (forfaitaire)','2'),
('','Forfait SNCF 2�me classe','2'),
('','Indemnit�s kilom�triques','2'),
('','P�age','2'),
('','Carburant','2'),
('','Taxi','2'),
('','Transport en commun','2'),
('','Hotel prise en charge Collaborateur','2'),
('','Hotel prise en charge Proservia','2'),
('','Forfait Province','2'),
('','Forfait D�partement (75/92/93/94)','2'),
('','Indemnit�s de repas (Ticket Restaurant)','3'),
('','Abonnement R�gion parisienne','3')
");

mysql_query("
INSERT INTO `disponibilite` VALUES
('','Imm�diate'),
('','1 mois'),
('','2 mois'),
('','3 mois')
");

mysql_query("
INSERT INTO `mobilite` VALUES
('','Ouest'),
('','Est'),
('','IDF'),
('','Sud Est'),
('','Sud Ouest'),
('','Nord Est'),
('','Nord Ouest'),
('','Centre'),
('','Nantes'),
('','Rennes'),
('','Lannion'),
('','Brest'),
('','Le Havre'),
('','Caen'),
('','Rouen'),
('','Paris'),
('','Lille'),
('','Niort'),
('','Lyon'),
('','Bordeaux'),
('','Toulouse'),
('','Aix en Provence'),
('','Sophia'),
('','Tours'),
('','Orl�ans'),
('','Le Mans'),
('','Redon')
");

mysql_query("
INSERT INTO `critere_recherche` VALUES
('','Salaire'),
('','Mission'),
('','Evolution'),
('','Formation'),
('','Culture entreprise')
");


mysql_query("
INSERT INTO `departement` VALUES
('01','Ain'),
('02','Aisne'),
('03','Allier'),
('04','Alpes-de-Haute-Provence'),
('05','Hautes-Alpes'),
('06','Alpes-Maritimes'),
('07','Ard�che'),
('08','Ardennes'),
('09','Ari�ge'),
('10','Aube'),
('11','Aude'),
('12','Aveyron'),
('13','Bouches-du-Rh�ne'),
('14','Calvados'),
('15','Cantal'),
('16','Charente'),
('17','Charente-Maritime'),
('18','Cher'),
('19','Corr�ze'),
('21','C�te-d\'Or'),
('22','C�tes-d\'Armor'),
('23','Creuse'),
('24','Dordogne'),
('25','Doubs'),
('26','Dr�me'),
('27','Eure'),
('28','Eure-et-loir'),
('29','Finist�re'),
('2A','Corse-du-sud'),
('2B','Haute-Corse'),
('30','Gard'),
('31','Haute-Garonne'),
('32','Gers'),
('33','Gironde'),
('34','H�rault'),
('35','Ille-et-Vilaine'),
('36','Indre'),
('37','Indre-et-Loire'),
('38','Is�re'),
('39','Jura'),
('40','Landes'),
('41','Loir-et-Cher'),
('42','Loire'),
('43','Haute-Loire'),
('44','Loire-Atlantique'),
('45','Loiret'),
('46','Lot'),
('47','Lot-et-Garonne'),
('48','Loz�re'),
('49','Maine-et-Loire'),
('50','Manche'),
('51','Marne'),
('52','Haute-Marne'),
('53','Mayenne'),
('54','Meurthe-et-Moselle'),
('55','Meuse'),
('56','Morbihan'),
('57','Moselle'),
('58','Ni�vre'),
('59','Nord'),
('60','Oise'),
('61','Orne'),
('62','Pas-de-Calais'),
('63','Puy-de-D�me'),
('64','Pyr�n�es-Atlantiques'),
('65','Hautes-Pyr�n�es'),
('66','Pyr�n�es-orientales'),
('67','Bas-Rhin'),
('68','Haut-Rhin'),
('69','Rh�ne'),
('70','Haute-Sa�ne'),
('71','Sa�ne-et-Loire'),
('72','Sarthe'),
('73','Savoie'),
('74','Haute-Savoie'),
('75','Ville de Paris'),
('76','Seine-Maritime'),
('77','Seine-et-Marne'),
('78','Yvelines'),
('79','Deux-s�vres'),
('80','Somme'),
('81','Tarn'),
('82','Tarn-et-Garonne'),
('83','Var'),
('84','Vaucluse'),
('85','Vend�e'),
('86','Vienne'),
('87','Haute-Vienne'),
('88','Vosges'),
('89','Yonne'),
('90','Territoire de Belfort'),
('91','Essonne'),
('92','Hauts-de-Seine'),
('93','Seine-Saint-Denis'),
('94','Val-de-Marne'),
('95','Val-d\'Oise')
");



mysql_query("
INSERT INTO pays VALUES
('','France','fr'),
('','Afghanistan','af'),
('','Afrique du sud','za'),
('','Albanie','al'),
('','Alg�rie','dz'),
('','Allemagne','de'),
('','Arabie saoudite','sa'),
('','Argentine','ar'),
('','Australie','au'),
('','Autriche','at'),
('','Belgique','be'),
('','Br�sil','br'),
('','Bulgarie','bg'),
('','Canada','ca'),
('','Chili','cl'),
('','Chine (R�p. pop.)','cn'),
('','Colombie','co'),
('','Cor�e, Sud','kr'),
('','Costa Rica','cr'),
('','Croatie','hr'),
('','Danemark','dk'),
('','�gypte','eg'),
('','�mirats arabes unis','ae'),
('','�quateur','ec'),
('','�tats-Unis','us'),
('','El Salvador','sv'),
('','Espagne','es'),
('','Finlande','fi'),
('','Gr�ce','gr'),
('','Hong Kong','hk'),
('','Hongrie','hu'),
('','Inde','in'),
('','Indon�sie','id'),
('','Irlande','ie'),
('','Isra�l','il'),
('','Italie','it'),
('','Japon','jp'),
('','Jordanie','jo'),
('','Liban','lb'),
('','Malaisie','my'),
('','Maroc','ma'),
('','Mexique','mx'),
('','Norv�ge','no'),
('','Nouvelle-Z�lande','nz'),
('','P�rou','pe'),
('','Pakistan','pk'),
('','Pays-Bas','nl'),
('','Philippines','ph'),
('','Pologne','pl'),
('','Porto Rico','pr'),
('','Portugal','pt'),
('','R�publique tch�que','cz'),
('','Roumanie','ro'),
('','Royaume-Uni','uk'),
('','Russie','ru'),
('','Singapour','sg'),
('','Su�de','se'),
('','Suisse','ch'),
('','Taiwan','tw'),
('','Thailande','th'),
('','Turquie','tr'),
('','Ukraine','ua'),
('','Venezuela','ve'),
('','Yougoslavie','yu'),
('','Samoa','as'),
('','Andorre','ad'),
('','Angola','ao'),
('','Anguilla','ai'),
('','Antarctique','aq'),
('','Antigua et Barbuda','ag'),
('','Arm�nie','am'),
('','Aruba','aw'),
('','Azerba�djan','az'),
('','Bahamas','bs'),
('','Bahrain','bh'),
('','Bangladesh','bd'),
('','Bi�lorussie','by'),
('','Belize','bz'),
('','Benin','bj'),
('','Bermudes (Les)','bm'),
('','Bhoutan','bt'),
('','Bolivie','bo'),
('','Bosnie-Herz�govine','ba'),
('','Botswana','bw'),
('','Bouvet (�les)','bv'),
('','Territoire britannique de l\'oc�an Indien','io'),
('','Vierges britanniques (�les)','vg'),
('','Brunei','bn'),
('','Burkina Faso','bf'),
('','Burundi','bi'),
('','Cambodge','kh'),
('','Cameroun','cm'),
('','Cap Vert','cv'),
('','Cayman (�les)','ky'),
('','R�publique centrafricaine','cf'),
('','Tchad','td'),
('','Christmas (�le)','cx'),
('','Cocos (�les)','cc'),
('','Comores','km'),
('','R�p. D�m. du Congo','cg'),
('','Cook (�les)','ck'),
('','Cuba','cu'),
('','Chypre','cy'),
('','Djibouti','dj'),
('','Dominique','dm'),
('','R�publique Dominicaine','do'),
('','Timor','tp'),
('','Guin�e Equatoriale','gq'),
('','�rythr�e','er'),
('','Estonie','ee'),
('','Ethiopie','et'),
('','Falkland (�le)','fk'),
('','F�ro� (�les)','fo'),
('','Fidji (R�publique des)','fj'),
('','Guyane fran�aise','gf'),
('','Polyn�sie fran�aise','pf'),
('','Territoires fran�ais du sud','tf'),
('','Gabon','ga'),
('','Gambie','gm'),
('','G�orgie','ge'),
('','Ghana','gh'),
('','Gibraltar','gi'),
('','Groenland','gl'),
('','Grenade','gd'),
('','Guadeloupe','gp'),
('','Guam','gu'),
('','Guatemala','gt'),
('','Guin�e','gn'),
('','Guin�e-Bissau','gw'),
('','Guyane','gy'),
('','Ha�ti','ht'),
('','Heard et McDonald (�les)','hm'),
('','Honduras','hn'),
('','Islande','is'),
('','Iran','ir'),
('','Irak','iq'),
('','C�te d\'Ivoire','ci'),
('','Jama�que','jm'),
('','Kazakhstan','kz'),
('','Kenya','ke'),
('','Kiribati','ki'),
('','Cor�e du Nord','kp'),
('','Koweit','kw'),
('','Kirghizistan','kg'),
('','Laos','la'),
('','Lettonie','lv'),
('','Lesotho','ls'),
('','Lib�ria','lr'),
('','Libye','ly'),
('','Liechtenstein','li'),
('','Lithuanie','lt'),
('','Luxembourg','lu'),
('','Macau','mo'),
('','Mac�doine','mk'),
('','Madagascar','mg'),
('','Malawi','mw'),
('','Maldives (�les)','mv'),
('','Mali','ml'),
('','Malte','mt'),
('','Marshall (�les)','mh'),
('','Martinique','mq'),
('','Mauritanie','mr'),
('','Maurice','mu'),
('','Mayotte','yt'),
('','Micron�sie (�tats f�d�r�s de)','fm'),
('','Moldavie','md'),
('','Monaco','mc'),
('','Mongolie','mn'),
('','Montserrat','ms'),
('','Mozambique','mz'),
('','Myanmar','mm'),
('','Namibie','na'),
('','Nauru','nr'),
('','Nepal','np'),
('','Antilles n�erlandaises','an'),
('','Nouvelle Cal�donie','nc'),
('','Nicaragua','ni'),
('','Niger','ne'),
('','Nigeria','ng'),
('','Niue','nu'),
('','Norfolk (�les)','nf'),
('','Mariannes du Nord (�les)','mp'),
('','Oman','om'),
('','Palau','pw'),
('','Panama','pa'),
('','Papouasie-Nouvelle-Guin�e','pg'),
('','Paraguay','py'),
('','Pitcairn (�les)','pn'),
('','Qatar','qa'),
('','R�union (La)','re'),
('','Rwanda','rw'),
('','G�orgie du Sud et Sandwich du Sud (�les)','gs'),
('','Saint-Kitts et Nevis','kn'),
('','Sainte Lucie','lc'),
('','Saint Vincent et les Grenadines','vc'),
('','Samoa','ws'),
('','Saint-Marin (R�p. de)','sm'),
('','S�o Tom� et Pr�ncipe (R�p.)','st'),
('','S�n�gal','sn'),
('','Seychelles','sc'),
('','Sierra Leone','sl'),
('','Slovaquie','sk'),
('','Slov�nie','si'),
('','Somalie','so'),
('','Sri Lanka','lk'),
('','Sainte H�l�ne','sh'),
('','Saint Pierre et Miquelon','pm'),
('','Soudan','sd'),
('','Suriname','sr'),
('','Svalbard et Jan Mayen (�les)','sj'),
('','Swaziland','sz'),
('','Syrie','sy'),
('','Tadjikistan','tj'),
('','Tanzanie','tz'),
('','Togo','tg'),
('','Tokelau','tk'),
('','Tonga','to'),
('','Trinit� et Tobago','tt'),
('','Tunisie','tn'),
('','Turkm�nistan','tm'),
('','Turks et Ca�ques (�les)','tc'),
('','Tuvalu','tv'),
('','�les Mineures �loign�es des �tats-Unis','um'),
('','Ouganda','ug'),
('','Uruguay','uy'),
('','Ouzb�kistan','uz'),
('','Vanuatu','vu'),
('','Vatican (Etat du)','va'),
('','Vietnam','vn'),
('','Vierges (�les)','vi'),
('','Wallis et Futuna (�les)','wf'),
('','Sahara Occidental','eh'),
('','Yemen','ye'),
('','Za�re','zr'),
('','Zambie','zm'),
('','Zimbabwe','zw'),
('','La Barbad','bb')
");




?>