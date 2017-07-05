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
mysql_query("INSERT INTO societe set libelle='NEEDPROFILE'");
mysql_query("INSERT INTO pole set libelle='Needprofile'");
mysql_query("UPDATE com_type_contrat set pole ='1,2,4,5,6,7' WHERE Id_type_contrat=1");
mysql_query("UPDATE com_type_contrat set pole ='1,2,3,4,5,6,7' WHERE Id_type_contrat=3");

mysql_query("INSERT INTO intitule set libelle='Pack Offre découverte', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 1 an abonnement', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 10 annonces', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « standard »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « sur mesure »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Bannière pub', Id_pole=7");


mysql_select_db('AGC_OVIALIS') or die ('pas de connection');
mysql_query("INSERT INTO societe set libelle='NEEDPROFILE'");
mysql_query("INSERT INTO pole set libelle='Needprofile'");
mysql_query("UPDATE com_type_contrat set pole ='1,2,4,5,6,7' WHERE Id_type_contrat=1");
mysql_query("UPDATE com_type_contrat set pole ='1,2,3,4,5,6,7' WHERE Id_type_contrat=3");

mysql_query("INSERT INTO intitule set libelle='Pack Offre découverte', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 1 an abonnement', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 10 annonces', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « standard »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « sur mesure »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Bannière pub', Id_pole=7");

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');
mysql_query("INSERT INTO societe set libelle='NEEDPROFILE'");
mysql_query("INSERT INTO pole set libelle='Needprofile'");
mysql_query("UPDATE com_type_contrat set pole ='1,2,4,5,6,7' WHERE Id_type_contrat=1");
mysql_query("UPDATE com_type_contrat set pole ='1,2,3,4,5,6,7' WHERE Id_type_contrat=3");

mysql_query("INSERT INTO intitule set libelle='Pack Offre découverte', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 1 an abonnement', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Pack 10 annonces', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « standard »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Réalisation d\'un télésite « sur mesure »', Id_pole=7");
mysql_query("INSERT INTO intitule set libelle='Bannière pub', Id_pole=7");


exec('mysqldump -u root AGC_PROSERVIA > AGC_PROSERVIA.sql');
mysql_query("CREATE database `AGC_NEEDPROFILE`");
exec('mysql -u root AGC_NEEDPROFILE < AGC_PROSERVIA.sql');

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
'9' => 'budget',
'10' => 'candidat_agence',
'11' => 'candidat_annonce',
'12' => 'candidat_typecontrat',
'13' => 'candidature',
'14' => 'cd_indemnite',
'15' => 'contrat_delegation',
'16' => 'date',
'17' => 'decision',
'18' => 'demande_changement',
'19' => 'description',
'20' => 'doc_formation',
'21' => 'entretien',
'22' => 'entretien_certification',
'23' => 'entretien_competence',
'24' => 'entretien_critere',
'25' => 'entretien_langue',
'26' => 'entretien_mobilite',
'27' => 'env_couverture',
'28' => 'env_pdt',
'29' => 'env_reseaux',
'30' => 'env_serveur',
'31' => 'environnement',
'32' => 'formateur',
'33' => 'formateur_salle',
'34' => 'historique_action_candidature',
'35' => 'historique_candidature',
'36' => 'historique_statut',
'37' => 'listing_affaires',
'38' => 'logistique',
'39' => 'modif_candidat',
'40' => 'ordre_mission',
'41' => 'participant',
'42' => 'periode_session',
'43' => 'planning',
'44' => 'planning_date',
'45' => 'planning_session',
'46' => 'proposition',
'47' => 'proposition_periode',
'48' => 'proposition_formation',
'49' => 'proposition_ressource',
'50' => 'proposition_session',
'51' => 'rendezvous',
'52' => 'rendezvous2',
'53' => 'ressource',
'54' => 'salle',
'55' => 'session',
'56' => 'stats_ca',
'57' => 'utilisateur'
);

$nb2 = count($tableatronquer);

$databaseatronquer = array('0' => 'AGC_NEEDPROFILE');
$nb = count($databaseatronquer);

$i = 0;
while($i < $nb) {
    mysql_select_db($databaseatronquer[$i]) or die ('pas de connection');
	$j = 0;
	while($j < $nb2) {
	    mysql_query('TRUNCATE TABLE '.$tableatronquer[$j].'');
		++$j;
	}
	++$i;
}