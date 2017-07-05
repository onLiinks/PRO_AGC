<?php
#########################################################################################################
#																										#
# Script permettant de mettre à jour la base de données	pour intégrer les affaires du pôle formation	#
#																										#
#########################################################################################################

require_once '../config/config.php';

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection');
//Ajout d'une couleur aux départements pour mettre en couleur les candidatures non lues selon l'agence correspondante 
mysql_query("ALTER TABLE `departement` add column couleur VARCHAR(10) NULL");
//pour l'agence de Niort
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_NIO.'" WHERE Id_departement in ("79","86","16","87","23","19")');
//pour l'agence de Lille
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LIL.'" WHERE Id_departement in ("62","80","59","02")');
//pour l'agence de Lyon
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LYO.'" WHERE Id_departement in ("21","58","71","39","25","70","90","01","69","42","74","73","07","26","38","03","63","15","43","48")');
//pour l'agence de Sophia et d'Aix
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_AIX_SOP.'" WHERE Id_departement in ("06","83","13","84","30","34","05","04","2A","2B",)');
//pour l'agence de Bordeaux
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_BDX.'" WHERE Id_departement in ("17","33","24","47","40","64")');
//pour l'agence de Toulouse
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TOU.'" WHERE Id_departement in ("46","12","34","82","81","32","31","65","09","66","11")');
//pour l'agence de Lannion
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LAN.'" WHERE Id_departement in ("29","22")');
//pour l'agence de Rennes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_REN.'" WHERE Id_departement in ("35","56","53")');
//pour l'agence de Nantes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_001.'" WHERE Id_departement in ("72","44","49","85")');
//pour l'agence de Tours
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TRS.'" WHERE Id_departement in ("37","41","18","36","45")');
//pour l'agence de Paris
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_PAR.'" WHERE Id_departement in ("75","77","78","91","92","93","94","95","28","89","10","51","08","55","54","57","67","68","88","52","971","972","973","974","975","976")');
//pour l'agence de Rouen, Le Havre et Caen
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_CAE_LHA_ROU.'" WHERE Id_departement in ("50","14","27","61","60","76")');
//Insertion du libéllé "sans préavis"
mysql_query('INSERT INTO `preavis` SET Id_preavis = 4, libelle = "Sans préavis"');
//Mise à jour de la table entretien suite à l'insertion du libéllé 'Sans préavis'
mysql_query('UPDATE entretien SET Id_preavis=4 WHERE  date_disponibilite != "0000-00-00" AND date_disponibilite < "2009-06-02" AND Id_preavis NOT IN (1,2,3)');



mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');
//Ajout d'une couleur aux départements pour mettre en couleur les candidatures non lues selon l'agence correspondante 
mysql_query("ALTER TABLE `departement` add column couleur VARCHAR(10) NULL");
//pour l'agence de Niort
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_NIO.'" WHERE Id_departement in ("79","86","16","87","23","19")');
//pour l'agence de Lille
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LIL.'" WHERE Id_departement in ("62","80","59","02")');
//pour l'agence de Lyon
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LYO.'" WHERE Id_departement in ("21","58","71","39","25","70","90","01","69","42","74","73","07","26","38","03","63","15","43","48")');
//pour l'agence de Sophia et d'Aix
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_AIX_SOP.'" WHERE Id_departement in ("06","83","13","84","30","34","05","04","2A","2B",)');
//pour l'agence de Bordeaux
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_BDX.'" WHERE Id_departement in ("17","33","24","47","40","64")');
//pour l'agence de Toulouse
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TOU.'" WHERE Id_departement in ("46","12","34","82","81","32","31","65","09","66","11")');
//pour l'agence de Lannion
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LAN.'" WHERE Id_departement in ("29","22")');
//pour l'agence de Rennes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_REN.'" WHERE Id_departement in ("35","56","53")');
//pour l'agence de Nantes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_001.'" WHERE Id_departement in ("72","44","49","85")');
//pour l'agence de Tours
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TRS.'" WHERE Id_departement in ("37","41","18","36","45")');
//pour l'agence de Paris
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_PAR.'" WHERE Id_departement in ("75","77","78","91","92","93","94","95","28","89","10","51","08","55","54","57","67","68","88","52","971","972","973","974","975","976")');
//pour l'agence de Rouen, Le Havre et Caen
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_CAE_LHA_ROU.'" WHERE Id_departement in ("50","14","27","61","60","76")');
//Insertion du libéllé "sans préavis"
mysql_query('INSERT INTO `preavis` SET Id_preavis = 4, libelle = "Sans préavis"');
//Mise à jour de la table entretien suite à l'insertion du libéllé 'Sans préavis'
mysql_query('UPDATE entretien SET Id_preavis=4 WHERE  date_disponibilite != "0000-00-00" AND date_disponibilite < "2009-06-02" AND Id_preavis NOT IN (1,2,3)');


mysql_select_db('AGC_OVIALIS') or die ('pas de connection');
//Ajout d'une couleur aux départements pour mettre en couleur les candidatures non lues selon l'agence correspondante 
mysql_query("ALTER TABLE `departement` add column couleur VARCHAR(10) NULL");
//pour l'agence de Niort
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_NIO.'" WHERE Id_departement in ("79","86","16","87","23","19")');
//pour l'agence de Lille
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LIL.'" WHERE Id_departement in ("62","80","59","02")');
//pour l'agence de Lyon
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LYO.'" WHERE Id_departement in ("21","58","71","39","25","70","90","01","69","42","74","73","07","26","38","03","63","15","43","48")');
//pour l'agence de Sophia et d'Aix
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_AIX_SOP.'" WHERE Id_departement in ("06","83","13","84","30","34","05","04","2A","2B",)');
//pour l'agence de Bordeaux
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_BDX.'" WHERE Id_departement in ("17","33","24","47","40","64")');
//pour l'agence de Toulouse
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TOU.'" WHERE Id_departement in ("46","12","34","82","81","32","31","65","09","66","11")');
//pour l'agence de Lannion
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_LAN.'" WHERE Id_departement in ("29","22")');
//pour l'agence de Rennes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_REN.'" WHERE Id_departement in ("35","56","53")');
//pour l'agence de Nantes
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_001.'" WHERE Id_departement in ("72","44","49","85")');
//pour l'agence de Tours
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_TRS.'" WHERE Id_departement in ("37","41","18","36","45")');
//pour l'agence de Paris
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_PAR.'" WHERE Id_departement in ("75","77","78","91","92","93","94","95","28","89","10","51","08","55","54","57","67","68","88","52","971","972","973","974","975","976")');
//pour l'agence de Rouen, Le Havre et Caen
mysql_query('UPDATE `departement` SET couleur = "'.CANDIDAT_CAE_LHA_ROU.'" WHERE Id_departement in ("50","14","27","61","60","76")');
//Insertion du libéllé "sans préavis"
mysql_query('INSERT INTO `preavis` SET Id_preavis = 4, libelle = "Sans préavis"');
//Mise à jour de la table entretien suite à l'insertion du libéllé 'Sans préavis'
mysql_query('UPDATE entretien SET Id_preavis=4 WHERE  date_disponibilite != "0000-00-00" AND date_disponibilite < "2009-06-02" AND Id_preavis NOT IN (1,2,3)');



?>