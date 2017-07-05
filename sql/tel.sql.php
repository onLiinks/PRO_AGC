<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

function format_tel($tel)
{
	$tel = str_replace(' ','',$tel);
	$tel = str_replace('.','',$tel);
	$tel = str_replace('-','',$tel);
	$tel = str_replace('/','',$tel);
	return $tel;
}


$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

$requete = "SELECT tel_fixe, tel_portable FROM ressource INNER JOIN candidature ON candidature.Id_ressource=ressource.Id_ressource and Id_etat=1";
$resultat = mysql_query($requete,$id_connexion);

while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC))
{
    $tel_fixe = format_tel($tableau['tel_fixe']);
    $requete2 = 'UPDATE ressource set tel_fixe="'.$tel_fixe.'" WHERE tel_fixe="'.$tableau['tel_fixe'].'"';
    $resultat2 = mysql_query($requete2,$id_connexion) or die ('Echec UPDATE tel fixe');
	
	$tel_portable = format_tel($tableau['tel_portable']);
    $requete3 = 'UPDATE ressource set tel_portable="'.$tel_portable.'" WHERE tel_portable="'.$tableau['tel_portable'].'"';
    $resultat3 = mysql_query($requete3,$id_connexion) or die ('Echec UPDATE tel portable');
}

?>