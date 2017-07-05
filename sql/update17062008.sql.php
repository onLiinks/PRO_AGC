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
('','APEC CVthèque'),
('','APEC Annonce'),
('','RégionJob CVthèque'),
('','RégionJob Annonce'),
('','Spontanée'),
('','CVWeb Proservia'),
('','Les Jeudis Annonce'),
('','Les Jeudis CVthèque'),
('','Cooptation'),
('','Forum/Salon (précisez)')
");

mysql_query("
INSERT INTO `etat_candidature` VALUES
('','Non lu'),
('','Lu'),
('','Convoqué'),
('','1er entretien'),
('','2nd entretien'),
('','Validé'),
('','Non validé'),
('','Embauché')
");

mysql_query("
INSERT INTO `raison_perdue` VALUES
('','Trop cher'),
('','Concurrent meilleur'),
('','Technique'),
('','Délai')
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
('','Indemnités de repas (Ticket Restaurant)','1'),
('','Repas du soir','1'),
('','Forfait SNCF 2ème classe','1'),
('','Indemnités kilométriques','1'),
('','Péage','1'),
('','Carburant','1'),
('','Taxi','1'),
('','Transport en commun','1'),
('','Hébergement','1'),
('','Indemnités de repas (Ticket Restaurant)','2'),
('','Repas du soir (sur justificatif)','2'),
('','Repas du soir (forfaitaire)','2'),
('','Forfait SNCF 2ème classe','2'),
('','Indemnités kilométriques','2'),
('','Péage','2'),
('','Carburant','2'),
('','Taxi','2'),
('','Transport en commun','2'),
('','Hotel prise en charge Collaborateur','2'),
('','Hotel prise en charge Proservia','2'),
('','Forfait Province','2'),
('','Forfait Département (75/92/93/94)','2'),
('','Indemnités de repas (Ticket Restaurant)','3'),
('','Abonnement Région parisienne','3')
");

mysql_query("
INSERT INTO `disponibilite` VALUES
('','Immédiate'),
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
('','Orléans'),
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
('07','Ardèche'),
('08','Ardennes'),
('09','Ariège'),
('10','Aube'),
('11','Aude'),
('12','Aveyron'),
('13','Bouches-du-Rhône'),
('14','Calvados'),
('15','Cantal'),
('16','Charente'),
('17','Charente-Maritime'),
('18','Cher'),
('19','Corrèze'),
('21','Côte-d\'Or'),
('22','Côtes-d\'Armor'),
('23','Creuse'),
('24','Dordogne'),
('25','Doubs'),
('26','Drôme'),
('27','Eure'),
('28','Eure-et-loir'),
('29','Finistère'),
('2A','Corse-du-sud'),
('2B','Haute-Corse'),
('30','Gard'),
('31','Haute-Garonne'),
('32','Gers'),
('33','Gironde'),
('34','Hérault'),
('35','Ille-et-Vilaine'),
('36','Indre'),
('37','Indre-et-Loire'),
('38','Isère'),
('39','Jura'),
('40','Landes'),
('41','Loir-et-Cher'),
('42','Loire'),
('43','Haute-Loire'),
('44','Loire-Atlantique'),
('45','Loiret'),
('46','Lot'),
('47','Lot-et-Garonne'),
('48','Lozère'),
('49','Maine-et-Loire'),
('50','Manche'),
('51','Marne'),
('52','Haute-Marne'),
('53','Mayenne'),
('54','Meurthe-et-Moselle'),
('55','Meuse'),
('56','Morbihan'),
('57','Moselle'),
('58','Nièvre'),
('59','Nord'),
('60','Oise'),
('61','Orne'),
('62','Pas-de-Calais'),
('63','Puy-de-Dôme'),
('64','Pyrénées-Atlantiques'),
('65','Hautes-Pyrénées'),
('66','Pyrénées-orientales'),
('67','Bas-Rhin'),
('68','Haut-Rhin'),
('69','Rhône'),
('70','Haute-Saône'),
('71','Saône-et-Loire'),
('72','Sarthe'),
('73','Savoie'),
('74','Haute-Savoie'),
('75','Ville de Paris'),
('76','Seine-Maritime'),
('77','Seine-et-Marne'),
('78','Yvelines'),
('79','Deux-sèvres'),
('80','Somme'),
('81','Tarn'),
('82','Tarn-et-Garonne'),
('83','Var'),
('84','Vaucluse'),
('85','Vendée'),
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
('','Algérie','dz'),
('','Allemagne','de'),
('','Arabie saoudite','sa'),
('','Argentine','ar'),
('','Australie','au'),
('','Autriche','at'),
('','Belgique','be'),
('','Brésil','br'),
('','Bulgarie','bg'),
('','Canada','ca'),
('','Chili','cl'),
('','Chine (Rép. pop.)','cn'),
('','Colombie','co'),
('','Corée, Sud','kr'),
('','Costa Rica','cr'),
('','Croatie','hr'),
('','Danemark','dk'),
('','Égypte','eg'),
('','Émirats arabes unis','ae'),
('','Équateur','ec'),
('','États-Unis','us'),
('','El Salvador','sv'),
('','Espagne','es'),
('','Finlande','fi'),
('','Grèce','gr'),
('','Hong Kong','hk'),
('','Hongrie','hu'),
('','Inde','in'),
('','Indonésie','id'),
('','Irlande','ie'),
('','Israël','il'),
('','Italie','it'),
('','Japon','jp'),
('','Jordanie','jo'),
('','Liban','lb'),
('','Malaisie','my'),
('','Maroc','ma'),
('','Mexique','mx'),
('','Norvège','no'),
('','Nouvelle-Zélande','nz'),
('','Pérou','pe'),
('','Pakistan','pk'),
('','Pays-Bas','nl'),
('','Philippines','ph'),
('','Pologne','pl'),
('','Porto Rico','pr'),
('','Portugal','pt'),
('','République tchèque','cz'),
('','Roumanie','ro'),
('','Royaume-Uni','uk'),
('','Russie','ru'),
('','Singapour','sg'),
('','Suède','se'),
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
('','Arménie','am'),
('','Aruba','aw'),
('','Azerbaïdjan','az'),
('','Bahamas','bs'),
('','Bahrain','bh'),
('','Bangladesh','bd'),
('','Biélorussie','by'),
('','Belize','bz'),
('','Benin','bj'),
('','Bermudes (Les)','bm'),
('','Bhoutan','bt'),
('','Bolivie','bo'),
('','Bosnie-Herzégovine','ba'),
('','Botswana','bw'),
('','Bouvet (Îles)','bv'),
('','Territoire britannique de l\'océan Indien','io'),
('','Vierges britanniques (Îles)','vg'),
('','Brunei','bn'),
('','Burkina Faso','bf'),
('','Burundi','bi'),
('','Cambodge','kh'),
('','Cameroun','cm'),
('','Cap Vert','cv'),
('','Cayman (Îles)','ky'),
('','République centrafricaine','cf'),
('','Tchad','td'),
('','Christmas (Île)','cx'),
('','Cocos (Îles)','cc'),
('','Comores','km'),
('','Rép. Dém. du Congo','cg'),
('','Cook (Îles)','ck'),
('','Cuba','cu'),
('','Chypre','cy'),
('','Djibouti','dj'),
('','Dominique','dm'),
('','République Dominicaine','do'),
('','Timor','tp'),
('','Guinée Equatoriale','gq'),
('','Érythrée','er'),
('','Estonie','ee'),
('','Ethiopie','et'),
('','Falkland (Île)','fk'),
('','Féroé (Îles)','fo'),
('','Fidji (République des)','fj'),
('','Guyane française','gf'),
('','Polynésie française','pf'),
('','Territoires français du sud','tf'),
('','Gabon','ga'),
('','Gambie','gm'),
('','Géorgie','ge'),
('','Ghana','gh'),
('','Gibraltar','gi'),
('','Groenland','gl'),
('','Grenade','gd'),
('','Guadeloupe','gp'),
('','Guam','gu'),
('','Guatemala','gt'),
('','Guinée','gn'),
('','Guinée-Bissau','gw'),
('','Guyane','gy'),
('','Haïti','ht'),
('','Heard et McDonald (Îles)','hm'),
('','Honduras','hn'),
('','Islande','is'),
('','Iran','ir'),
('','Irak','iq'),
('','Côte d\'Ivoire','ci'),
('','Jamaïque','jm'),
('','Kazakhstan','kz'),
('','Kenya','ke'),
('','Kiribati','ki'),
('','Corée du Nord','kp'),
('','Koweit','kw'),
('','Kirghizistan','kg'),
('','Laos','la'),
('','Lettonie','lv'),
('','Lesotho','ls'),
('','Libéria','lr'),
('','Libye','ly'),
('','Liechtenstein','li'),
('','Lithuanie','lt'),
('','Luxembourg','lu'),
('','Macau','mo'),
('','Macédoine','mk'),
('','Madagascar','mg'),
('','Malawi','mw'),
('','Maldives (Îles)','mv'),
('','Mali','ml'),
('','Malte','mt'),
('','Marshall (Îles)','mh'),
('','Martinique','mq'),
('','Mauritanie','mr'),
('','Maurice','mu'),
('','Mayotte','yt'),
('','Micronésie (États fédérés de)','fm'),
('','Moldavie','md'),
('','Monaco','mc'),
('','Mongolie','mn'),
('','Montserrat','ms'),
('','Mozambique','mz'),
('','Myanmar','mm'),
('','Namibie','na'),
('','Nauru','nr'),
('','Nepal','np'),
('','Antilles néerlandaises','an'),
('','Nouvelle Calédonie','nc'),
('','Nicaragua','ni'),
('','Niger','ne'),
('','Nigeria','ng'),
('','Niue','nu'),
('','Norfolk (Îles)','nf'),
('','Mariannes du Nord (Îles)','mp'),
('','Oman','om'),
('','Palau','pw'),
('','Panama','pa'),
('','Papouasie-Nouvelle-Guinée','pg'),
('','Paraguay','py'),
('','Pitcairn (Îles)','pn'),
('','Qatar','qa'),
('','Réunion (La)','re'),
('','Rwanda','rw'),
('','Géorgie du Sud et Sandwich du Sud (Îles)','gs'),
('','Saint-Kitts et Nevis','kn'),
('','Sainte Lucie','lc'),
('','Saint Vincent et les Grenadines','vc'),
('','Samoa','ws'),
('','Saint-Marin (Rép. de)','sm'),
('','São Tomé et Príncipe (Rép.)','st'),
('','Sénégal','sn'),
('','Seychelles','sc'),
('','Sierra Leone','sl'),
('','Slovaquie','sk'),
('','Slovénie','si'),
('','Somalie','so'),
('','Sri Lanka','lk'),
('','Sainte Hélène','sh'),
('','Saint Pierre et Miquelon','pm'),
('','Soudan','sd'),
('','Suriname','sr'),
('','Svalbard et Jan Mayen (Îles)','sj'),
('','Swaziland','sz'),
('','Syrie','sy'),
('','Tadjikistan','tj'),
('','Tanzanie','tz'),
('','Togo','tg'),
('','Tokelau','tk'),
('','Tonga','to'),
('','Trinité et Tobago','tt'),
('','Tunisie','tn'),
('','Turkménistan','tm'),
('','Turks et Caïques (Îles)','tc'),
('','Tuvalu','tv'),
('','Îles Mineures Éloignées des États-Unis','um'),
('','Ouganda','ug'),
('','Uruguay','uy'),
('','Ouzbékistan','uz'),
('','Vanuatu','vu'),
('','Vatican (Etat du)','va'),
('','Vietnam','vn'),
('','Vierges (Îles)','vi'),
('','Wallis et Futuna (Îles)','wf'),
('','Sahara Occidental','eh'),
('','Yemen','ye'),
('','Zaïre','zr'),
('','Zambie','zm'),
('','Zimbabwe','zw'),
('','La Barbad','bb')
");




?>