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

$resultat = mysql_query("SELECT Id_contrat_delegation,nature_mission,renouvellement FROM contrat_delegation");
while ($tableau = mysql_fetch_array($resultat)) {
    $nature_mission  = '';
    $remplacement    = '';
   
    if($tableau['nature_mission'] == 'Remplacement') {
       $nature_mission = 'Renouvellement';
	   $remplacement   = 1;
	}
	if($tableau['nature_mission'] == 'Nouvelle mission') {
	    if($tableau['renouvellement'] == 0) {
	        $nature_mission = 'Nouvelle mission';
	        $remplacement   = 0;
	    }
	    if($tableau['renouvellement'] == 1) {
	        $nature_mission = 'Renouvellement';
	        $remplacement   = 0;
	    }
	}
    mysql_query('UPDATE contrat_delegation set nature_mission="'.$nature_mission.'", renouvellement='.$remplacement.' WHERE Id_contrat_delegation='.$tableau['Id_contrat_delegation'].'');
}



mysql_query("ALTER TABLE `contrat_delegation` change renouvellement remplacement INT(1) NOT NULL DEFAULT 0");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

$resultat = mysql_query("SELECT Id_contrat_delegation,nature_mission,renouvellement FROM contrat_delegation");
while ($tableau = mysql_fetch_array($resultat)) {
    $nature_mission  = '';
    $remplacement    = '';
   
    if($tableau['nature_mission'] == 'Remplacement') {
       $nature_mission = 'Renouvellement';
	   $remplacement   = 1;
	}
	if($tableau['nature_mission'] == 'Nouvelle mission') {
	    if($tableau['renouvellement'] == 0) {
	        $nature_mission = 'Nouvelle mission';
	        $remplacement   = 0;
	    }
	    if($tableau['renouvellement'] == 1) {
	        $nature_mission = 'Renouvellement';
	        $remplacement   = 0;
	    }
	}
    mysql_query('UPDATE contrat_delegation set nature_mission="'.$nature_mission.'", renouvellement='.$remplacement.' WHERE Id_contrat_delegation='.$tableau['Id_contrat_delegation'].'');
}



mysql_query("ALTER TABLE `contrat_delegation` change renouvellement remplacement INT(1) NOT NULL DEFAULT 0");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

$resultat = mysql_query("SELECT Id_contrat_delegation,nature_mission,renouvellement FROM contrat_delegation");
while ($tableau = mysql_fetch_array($resultat)) {
    $nature_mission  = '';
    $remplacement    = '';
   
    if($tableau['nature_mission'] == 'Remplacement') {
       $nature_mission = 'Renouvellement';
	   $remplacement   = 1;
	}
	if($tableau['nature_mission'] == 'Nouvelle mission') {
	    if($tableau['renouvellement'] == 0) {
	        $nature_mission = 'Nouvelle mission';
	        $remplacement   = 0;
	    }
	    if($tableau['renouvellement'] == 1) {
	        $nature_mission = 'Renouvellement';
	        $remplacement   = 0;
	    }
	}
    mysql_query('UPDATE contrat_delegation set nature_mission="'.$nature_mission.'", renouvellement='.$remplacement.' WHERE Id_contrat_delegation='.$tableau['Id_contrat_delegation'].'');
}



mysql_query("ALTER TABLE `contrat_delegation` change renouvellement remplacement INT(1) NOT NULL DEFAULT 0");

?>