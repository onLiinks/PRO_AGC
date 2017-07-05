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

function formatSecuriteSocial($chaine) {
    $chaine      = str_replace(' ','',$chaine);
	$sex         = substr($chaine,0,1);
	$annee_naiss = substr($chaine,1,2);
	$mois_naiss  = substr($chaine,3,2);
	# Cas Département de naissance en outre mer : http://fr.wikipedia.org/wiki/Numéro_INSEE
	$dpt   = substr($chaine,5,3);
	$domtom = array(971,972,973,974,975,976);
	if (in_array($dpt, $domtom)) {
	    $dpt_naiss   = substr($chaine,5,3);
	    $com_naiss   = substr($chaine,7,2);
	} else {
		$dpt_naiss   = substr($chaine,5,2);
	    $com_naiss   = substr($chaine,7,3);
	}
	$position    = substr($chaine,10,3);
	$cle         = substr($chaine,13,2);
	return $sex.' '.$annee_naiss.' '.$mois_naiss.' '.$dpt_naiss.' '.$com_naiss.' '.$position.' '.$cle;
}

$resultat = mysql_query("SELECT Id_ressource, securite_sociale FROM ressource WHERE securite_sociale !=0");
while ($tableau = mysql_fetch_array($resultat)) {
    $nss = formatSecuriteSocial($tableau['securite_sociale']);
	echo $nss.'<br />';
    mysql_query('UPDATE ressource set securite_sociale="'.$nss.'" WHERE Id_ressource='.$tableau['Id_ressource'].'');
}