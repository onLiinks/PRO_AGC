<?php
###############################################
#													#
# 		Script permettant de cr�er la base de donn�es				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL


$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("gescom",$id_connexion);

$requete = "SELECT lien_cv, lien_cvp FROM candidature";
$resultat = mysql_query($requete,$id_connexion);

while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC))

{
    $lien_cv = strtr($tableau['lien_cv'],'����������������������������������������������������', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $requete2 = 'UPDATE candidature set lien_cv="'.$lien_cv.'" WHERE lien_cv="'.$tableau['lien_cv'].'"';
    $resultat2 = mysql_query($requete2,$id_connexion);
	
	$lien_cvp = strtr($tableau['lien_cvp'],'����������������������������������������������������', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $requete3 = 'UPDATE candidature set lien_cvp="'.$lien_cvp.'" WHERE lien_cvp="'.$tableau['lien_cvp'].'"';
    $resultat3 = mysql_query($requete3,$id_connexion);
}

?>