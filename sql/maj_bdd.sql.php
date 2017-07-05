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


exec('mysqldump -u root AGC_PROSERVIA > AGC_PROSERVIA.sql');


mysql_query("DROP database `AGC_WIZTIVI`");
mysql_query("DROP database `AGC_OVIALIS`");
mysql_query("CREATE database `AGC_WIZTIVI`");
mysql_query("CREATE database `AGC_OVIALIS`");

exec('mysql -u root AGC_WIZTIVI < AGC_PROSERVIA.sql');
exec('mysql -u root AGC_OVIALIS < AGC_PROSERVIA.sql');

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
'10' => 'budget',
'11' => 'candidat_annonce',
'12' => 'candidat_typecontrat',
'13' => 'candidature',
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
'34' => 'ordre_mission',
'35' => 'planning',
'36' => 'proposition',
'37' => 'proposition_periode',
'38' => 'proposition_ressource',
'39' => 'rendezvous',
'40' => 'rendezvous2',
'41' => 'ressource',
'42' => 'stats_ca',
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