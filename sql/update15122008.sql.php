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

mysql_query("ALTER TABLE `description` add column typeAT VARCHAR(15) NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `utilisateur` add column Id_bdd_societe VARCHAR(50) NOT NULL DEFAULT ''");
mysql_query("UPDATE `utilisateur` set Id_bdd_societe=1");

mysql_query("
ALTER TABLE `affaire` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_affaire` , `Id_compte`, `createur`, `Id_statut`, `archive`, `Id_type_contrat` )
");

mysql_query("
ALTER TABLE `annonce` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_annonce` , `reference`, `libelle`, `localisation`, `createur`, `archive` )
");

mysql_query("
ALTER TABLE `candidature` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_candidature` , `Id_ressource`, `Id_nature`, `Id_etat`, `createur`, `archive`, `stage`, `Id_cvweb`, `Id_action_mener` )
");

mysql_query("
ALTER TABLE `entretien` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_entretien` , `Id_candidature`, `Id_recruteur`, `attente_pro`, `createur`, `date_creation`, `mot_cle` )
");

mysql_query("
ALTER TABLE `rendezvous` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_rendezvous` , `createur`, `date`, `type` )
");

mysql_query("
ALTER TABLE `rendezvous2` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_rendezvous` , `createur`, `date`, `type` )
");

mysql_query("
ALTER TABLE `ressource` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_ressource` , `nom`, `Id_profil`, `createur`, `archive` )
");

mysql_query("
ALTER TABLE `utilisateur` DROP PRIMARY KEY , ADD PRIMARY KEY ( `Id_utilisateur` , `statut`, `archive`, `Id_agence`, `Id_bdd_societe` )
");

mysql_query("ALTER TABLE `ville` add column Id_departement VARCHAR(6) NOT NULL DEFAULT ''");

mysql_query("UPDATE `ville` set Id_departement='44000' WHERE Id_ville=1");
mysql_query("UPDATE `ville` set Id_departement='35000' WHERE Id_ville=2");
mysql_query("UPDATE `ville` set Id_departement='22300' WHERE Id_ville=3");
mysql_query("UPDATE `ville` set Id_departement='29200' WHERE Id_ville=4");
mysql_query("UPDATE `ville` set Id_departement='76600' WHERE Id_ville=5");
mysql_query("UPDATE `ville` set Id_departement='14000' WHERE Id_ville=6");
mysql_query("UPDATE `ville` set Id_departement='76000' WHERE Id_ville=7");
mysql_query("UPDATE `ville` set Id_departement='75000' WHERE Id_ville=8");
mysql_query("UPDATE `ville` set Id_departement='59000' WHERE Id_ville=9");
mysql_query("UPDATE `ville` set Id_departement='79000' WHERE Id_ville=10");
mysql_query("UPDATE `ville` set Id_departement='69000' WHERE Id_ville=11");
mysql_query("UPDATE `ville` set Id_departement='33000' WHERE Id_ville=12");
mysql_query("UPDATE `ville` set Id_departement='31000' WHERE Id_ville=13");
mysql_query("UPDATE `ville` set Id_departement='13100' WHERE Id_ville=14");
mysql_query("UPDATE `ville` set Id_departement='06560' WHERE Id_ville=15");
mysql_query("UPDATE `ville` set Id_departement='37000' WHERE Id_ville=16");
mysql_query("UPDATE `ville` set Id_departement='45000' WHERE Id_ville=17");
mysql_query("UPDATE `ville` set Id_departement='72000' WHERE Id_ville=18");
mysql_query("UPDATE `ville` set Id_departement='35600' WHERE Id_ville=19");
mysql_query("UPDATE `ville` set Id_departement='13000' WHERE Id_ville=20");

mysql_query("
CREATE TABLE `bdd_societe` (
	`Id_bdd_societe` int(1) NOT NULL auto_increment,
	`libelle` VARCHAR(50) NOT NULL default '',
	PRIMARY KEY ( `Id_bdd_societe` ) 
)");

mysql_query("
INSERT INTO `bdd_societe` VALUES
	('','PROSERVIA'),
	('','OVIALIS'),
	('','WIZTIVI')
");


exec('mysqldump -u root gescom > gescom.sql');

mysql_query("CREATE database `AGC_WIZTIVI`");
mysql_query("CREATE database `AGC_OVIALIS`");
mysql_query("CREATE database `AGC_PROSERVIA`");

exec('mysql -u root AGC_PROSERVIA < gescom.sql');
exec('mysql -u root AGC_WIZTIVI < gescom.sql');
exec('mysql -u root AGC_OVIALIS < gescom.sql');

$tableatronquer = array(
'0' => 'action',
'1' => 'affaire',
'2' => 'affaire_competence',
'3' => 'affaire_exigence',
'4' => 'affaire_langue',
'5' => 'affaire_pole',
'6' => 'analyse_commerciale',
'7' => 'analyse_risque',
'8' => 'annonce',
'9' => 'bilan_utilisateur',
'10' => 'candidat_annonce',
'11' => 'candidat_typecontrat',
'12' => 'candidature',
'13' => 'categorie_certification',
'14' => 'cd_indemnite',
'15' => 'contrat_delegation',
'16' => 'decision',
'17' => 'description',
'18' => 'entretien',
'19' => 'entretien_certification',
'20' => 'entretien_competence',
'21' => 'entretien_critere',
'22' => 'entretien_langue',
'23' => 'entretien_mobilite',
'24' => 'env_couverture',
'25' => 'env_pdt',
'26' => 'env_reseaux',
'27' => 'env_serveur',
'28' => 'environnement',
'29' => 'historique_candidature',
'30' => 'historique_statut',
'31' => 'modif_candidat',
'32' => 'newsletter',
'33' => 'organisation',
'34' => 'planning',
'35' => 'proposition',
'36' => 'proposition_periode',
'37' => 'proposition_ressource',
'38' => 'raison_decision',
'39' => 'raison_perdue',
'40' => 'rendezvous',
'41' => 'rendezvous2',
'42' => 'ressource',
'43' => 'template',
'44' => 'utilisateur'
);

$nb2 = count($tableatronquer);

$databaseatronquer = array('0' => 'AGC_OVIALIS', '1' => 'AGC_WIZTIVI');
$nb = count($databaseatronquer);

$i=0;
while($i < $nb) {
    mysql_select_db($databaseatronquer[$i]) or die ('pas de connection');
	$j = 0;
	while($j < $nb2) {
	    mysql_query('TRUNCATE TABLE '.$tableatronquer[$j].'');
		++$j;
	}
	++$i;
}