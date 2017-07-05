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

mysql_query("RENAME TABLE rendezvous TO rendezvous2");

mysql_query("
CREATE TABLE `rendezvous` (
	`Id_rendezvous` INT NOT NULL AUTO_INCREMENT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date` DATE,
	`type` VARCHAR(255) NOT NULL default '',
	`Id_compte` VARCHAR(255) NOT NULL default '',
	`Id_contact` VARCHAR(255) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_rendezvous` ) 
)");


mysql_query("ALTER TABLE `utilisateur` add column Id_agence VARCHAR(20) NOT NULL DEFAULT ''");


mysql_query("INSERT INTO `statut` set libelle='Opérationnelle'");
mysql_query("INSERT INTO `statut` set libelle='Terminée'");

mysql_query("ALTER TABLE `contrat_delegation` add column renouvellement INT(1) NOT NULL DEFAULT 0");

mysql_query("ALTER TABLE `affaire` add column Id_type_contrat INT(1) NOT NULL DEFAULT 0");

mysql_query("ALTER TABLE `intitule` change Id_prestation Id_pole INT(1) NOT NULL DEFAULT 0");

mysql_query("ALTER TABLE `proposition_periode` add column debut DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `proposition_periode` add column fin DATE NOT NULL DEFAULT '0000-00-00'");

mysql_query("ALTER TABLE `description` add column cds INT(1) NOT NULL DEFAULT '0'");

mysql_query("ALTER TABLE `candidature` add column date_reponse DATE NOT NULL DEFAULT '0000-00-00'");
mysql_query("ALTER TABLE `candidature` add column Id_action_mener INT(1) NOT NULL DEFAULT '0'");

mysql_query("ALTER TABLE `entretien` add column mot_cle TEXT NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `ressource` add column Id_specialite INT(2) NOT NULL DEFAULT '0'");

mysql_query("DROP TABLE `exp_info`");


mysql_query("
CREATE TABLE `action_mener` (
  `Id_action_mener` int(1) NOT NULL auto_increment,
  `libelle` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`Id_action_mener`)
)");

mysql_query("
INSERT INTO `action_mener` VALUES 
('', 'Attente retour candidat'),
('', 'Ne donne pas suite'),
('', 'Entretien tél non validé'),
('', 'Annulé RDV')
");

mysql_query("
CREATE TABLE `specialite` (
  `Id_specialite` int(2) NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`Id_specialite`)
)");

mysql_query("
INSERT INTO `specialite` VALUES 
('', 'Formateur'),
('', 'Test applicatifs'),
('', 'Test et validation'),
('', 'Help desk / support'),
('', 'Infogérance'),
('', 'Virtualisation'),
('', 'Intégration d\'applications'),
('', 'Intégration d\'ERP'),
('', 'Informatique décisionnelle'),
('', 'DSI'),
('', 'Sécurité'),
('', 'TMA'),
('', 'TRA'),
('', 'Compression video'),
('', 'Systèmes Microsoft'),
('', 'Systèmes Open Source'),
('', 'Maintenance/déploiement'),
('', 'Réseaux fixes'),
('', 'Réseaux mobiles'),
('', 'Développement objet'),
('', 'Ingénieur support'),
('', 'Supervision'),
('', 'Généraliste'),
('', 'Mainframe'),
('', 'Multimédia'),
('', 'Web'),
('', 'Qualité'),
('', 'Optique / Optronique'),
('', 'Architecture'),
('', 'VoIP'),
('', 'TV Numérique'),
('', 'Analyste programmeur'),
('', 'Analyste concepteur'),
('', 'Chef de projet développement'),
('', 'Chef de projet prod'),
('', 'Profil atypique (sans qualif informatique)'),
('', 'Fonctions support')
");


mysql_query("
CREATE TABLE `exp_info` (
  `Id_exp_info` int(3) NOT NULL auto_increment,
  `libelle` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`Id_exp_info`)
)");

mysql_query("
INSERT INTO `exp_info` VALUES 
('', '0-6 mois'),
('', '6 mois-1 an'),
('', '1 an-3 ans'),
('', '3 ans- 5 ans'),
('', '5 ans- 10 ans'),
('', '> 10 ans')
");

mysql_query("UPDATE `ressource` set Id_exp_info=5 where Id_exp_info=4");


mysql_query("UPDATE `candidature` add column date_reponse DATE NOT NULL DEFAULT '0000-00-00'");


/* LES AFFAIRES SUIVANTES SONT DES CENTRES DE SERVICES */ 
mysql_query("UPDATE `description` set cds=1 where Id_affaire=1908");
mysql_query("UPDATE `description` set cds=1 where Id_affaire=1493");
mysql_query("UPDATE `description` set cds=1 where Id_affaire=1114");
mysql_query("UPDATE `description` set cds=1 where Id_affaire=57");
mysql_query("UPDATE `description` set cds=1 where Id_affaire=11");


mysql_query("ALTER TABLE `proposition_ressource` add column type VARCHAR(20) NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `proposition_ressource` add column prix_inclus INT(1) NOT NULL DEFAULT '0'");

mysql_query("ALTER TABLE `critere_recherche` change column nom libelle VARCHAR(25) NOT NULL DEFAULT ''");


/* CHANGEMENT DES INTITULES AVEC ATTRIBUTION DES BONS TYPE DES CONTRAT */
$resultat = mysql_query("SELECT Id_intitule FROM intitule WHERE Id_pole=2");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE intitule set Id_pole=1 where Id_intitule='.$tableau['Id_intitule'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_intitule FROM intitule WHERE Id_pole=3");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE intitule set Id_pole=2 where Id_intitule='.$tableau['Id_intitule'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_intitule FROM intitule WHERE Id_pole=4");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE intitule set Id_pole=3 where Id_intitule='.$tableau['Id_intitule'].'') or die ('Erreur !');
}

$resultat = mysql_query("SELECT Id_intitule FROM intitule WHERE Id_pole=5");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE intitule set Id_pole=4 where Id_intitule='.$tableau['Id_intitule'].'') or die ('Erreur !');
}


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
('','Infogérance / CDS - sur site','1,2'),
('','Infogérance / CDS - à distance','1,2'),
('','Infogérance / CDS - sur site et à distance','1,2'),
('','Projet Forfaitaire','1,2,3,4,5')
");



/* AFFAIRE ASSOCIEES AU POLE GOUVERNANCE DES SI */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=6 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=5') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE CONSEIL / EXPERTISE ET AU TYPE DE PRESTATION CONSEIL & EXPERTISE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=4 and Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE DEV ET AU TYPE DE PRESTATION CONSEIL & EXPERTISE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=2 and Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=2') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE FORMATION ET AU TYPE DE PRESTATION CONSEIL & EXPERTISE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=3 and Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE INFOGERANCE ET AU TYPE DE PRESTATION CONSEIL & EXPERTISE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=5 and Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE TELESERVICES ET AU TYPE DE PRESTATION CONSEIL & EXPERTISE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=1 and Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');
}


/* AFFAIRE ASSOCIEES AU POLE CONSEIL / EXPERTISE ET AU TYPE DE PRESTATION ASSISTANCE TECHNIQUE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=4 and Id_prestation=1 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=1 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');	
}

/* AFFAIRE ASSOCIEES AUX POLES INFOGERANCE ET TELESERVICES ET AU TYPE DE PRESTATION TELESERVICES INFOGERANCE*/
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=1 and Id_prestation=2 and affaire.Id_affaire IN (SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=5 and Id_prestation=2) and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=4 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=1') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE TELESERVICES ET AU TYPE DE PRESTATION TELESERVICES INFOGERANCE*/
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=1 and Id_prestation=2 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=3 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=1') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE INFOGERANCE ET AU TYPE DE PRESTATION TELESERVICES INFOGERANCE*/
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=5 and Id_prestation=2 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=5') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE GOUVERNANCE DES SI ET AU TYPE DE PRESTATION TELESERVICES INFOGERANCE*/
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=6 and Id_prestation=2 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=5') or die ('Erreur !');
}


/* AFFAIRE ASSOCIEES AU POLE INFOGERANCE ET AU TYPE DE PRESTATION ASSISTANCE TECHNIQUE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=5 and Id_prestation=1 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=1 WHERE Id_affaire='.$tableau['Id_affaire'].'')or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=1') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE CONSEIL / EXPERTISE ET AU TYPE DE PRESTATION ASSISTANCE TECHNIQUE */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=4 and Id_prestation=1 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=1 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');
}

/* AFFAIRE ASSOCIEES AU POLE INFOGERANCE ET AU TYPE DE PRESTATION TMA / CDS */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire_pole INNER JOIN affaire ON affaire.Id_affaire=affaire_pole.Id_affaire WHERE Id_pole=5 and Id_prestation=3 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=1') or die ('Erreur !');
}


/* AFFAIRE ASSOCIEES AU TYPE DE PRESTATION ASSISTANCE TECHNIQUE ET SANS POLE  */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=1 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=1 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
}


/* AFFAIRES TYPE DE TELESERVICES / INFOGERANCE SANS POLE */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=2 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=3 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=1') or die ('Erreur !');
}

/* AFFAIRES TYPE DE PRESTATION TMA CENTRE DE SERVICES SANS POLE */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=3 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 WHERE Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=2') or die ('Erreur !');
}

/* AFFAIRES ASSOCIEES AU POLE FORMATION AVEC OU SANS POLE */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=4");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=3') or die ('Erreur !');	
}

/* AFFAIRES ASSOCIEES AU POLE CONSEIL & EXPERTISE SANS POLE */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=5 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=4') or die ('Erreur !');	
}


/* AFFAIRES ASSOCIEES AU POLE GOUVERNANCE DES SI SANS POLE */
$resultat = mysql_query("SELECT Id_affaire FROM affaire WHERE Id_prestation=6 and Id_type_contrat=0");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=5 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('DELETE FROM affaire_pole where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
	mysql_query('INSERT INTO affaire_pole set Id_affaire='.$tableau['Id_affaire'].', Id_pole=5') or die ('Erreur !');	
}

/* AFFAIRES AYANT POUR TYPE DE CONTRAT INFOGERANCE A DISTANCE MAIS ETANT EN REALITE SUR SITE ! */
$resultat = mysql_query("SELECT affaire.Id_affaire FROM affaire INNER JOIN description ON affaire.Id_affaire=description.Id_affaire and Id_type_contrat=3 and site='IN'");
while ($tableau = mysql_fetch_array($resultat)) {
    mysql_query('UPDATE affaire set Id_type_contrat=2 where Id_affaire='.$tableau['Id_affaire'].'') or die ('Erreur !');
}


/* ATTENTION A LA TABLE ORGANISATION ET AFFAIRE_POLE */
mysql_query("DROP TABLE pole");

mysql_query("
CREATE TABLE `pole` (
	`Id_pole` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_pole` ) 
)");

mysql_query("
INSERT INTO `pole` VALUES
('','Exploitation / Production'),
('','Développement'),
('','Formation'),
('','Conseil / Expertise'),
('','Gouvernance des SI')
");

mysql_query("UPDATE organisation set Id_pole=1 where Id_pole=5"); /* CHANGER LE CHIFFRE DANS LES DROITS DES UTILISATEURS !!!!!!!!!!!!!!!!! */


mysql_query("ALTER TABLE `pole` change nom libelle VARCHAR(255) NOT NULL DEFAULT ''");




/* PARTIE NEWSLETTER */
/*
--
-- Structure de la table `template`
--
*/
mysql_query("
CREATE TABLE `template` (
  `id` int(9) NOT NULL,
  `zn_libelle` text NOT NULL,
  `zn_description` text NOT NULL,
  `zn_code` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
/*
--
-- Contenu de la table `template`
--
*/
mysql_query("
INSERT INTO `template` (`id`, `zn_libelle`, `zn_description`, `zn_code`) VALUES
(0, 'Aucun', 'Aucun template', ''),
(1, 'Ajout signature', 'Ajoute une image de signature Ã  la newsletter', 'code template'),
(2, 'zone 2', 'zone description 2', 'zone code 2');
");


/*
--
-- Structure de la table `newsletter`
--
*/
mysql_query("
CREATE TABLE `newsletter` (
  `id` int(9) NOT NULL auto_increment,
  `id_ref` varchar(255) NOT NULL,
  `dt_creation` date NOT NULL,
  `dt_envoi` date default NULL,
  `dt_modification` date default NULL,
  `dt_suppression` date default NULL,
  `zn_libelle_mail` text,
  `zn_corps_mail` text,
  `zn_destinataires` text,
  `zn_regions_concernees` text,
  `zn_profils_concernes` text,
  `zn_competences_concernees` text,
  `zn_certifications_concernees` text,
  `id_createur` varchar(255) NOT NULL,
  `id_template` int(9) default NULL,
  `id_annonces` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_ref` (`id_ref`),
  KEY `id_template` (`id_template`),
  KEY `id_createur` (`id_createur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;
");


/*
--
-- Contenu de la table `newsletter`
--
*/

mysql_query("
INSERT INTO `newsletter` (`id`, `id_ref`, `dt_creation`, `dt_envoi`, `dt_modification`, `dt_suppression`, `zn_libelle_mail`, `zn_corps_mail`, `zn_destinataires`, `zn_regions_concernees`, `zn_profils_concernes`, `zn_competences_concernees`, `zn_certifications_concernees`, `id_createur`, `id_template`, `id_annonces`) VALUES
(0, 'news1', '2008-09-10', NULL, '2008-11-20', NULL, 'objet du mail', '<P>corps</P>', 'sauvage.n@gmail.com;test@test.fr', '1_2_3_4_5_6_7_8_9_10_11_12_13_14_15_16_17_18_19_20_21_22_23', '1_2_3_4_5_6_7_8_9_10_11_12_13_14_15_16_17_18_19_20_21_22_23_24_25_26_27_28_29_30_31_32_33_34_35_36_37_38', '1_2_3_4_5_6_7_8_9_10_11_12_13_14_15_16_17_18_19_20_21_22_23_24_25_26_27_28_29_30_31_32_33_34_35_36_37_38_39_40_41_42_43_44_45_46_47_48_49_50_51_52_53_54_55_56_57_58_59_60_61_62_63_64_65_66_67_68_69_70_71_72_73_74_75_76_77_78_79_80_81_82_83_84_85_86_87_88_89_90_91_92_93_94_95_96_97_98_99_100_101_102_103_104_105_106_107_108_109_110_111_112_113_114', '1_2_3_4', 'sauvage.nicolas', 0, '');
");

/*
--
-- Contraintes pour la table `newsletter`
--*/
mysql_query("
ALTER TABLE `newsletter`
  ADD CONSTRAINT `newsletter_ibfk_1` FOREIGN KEY (`id_template`) REFERENCES `template` (`id`);
");

mysql_query("
ALTER TABLE `proposition_ressource` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_proposition` , `Id_ressource`, `tarif_journalier`, `debut`, `fin` )
");