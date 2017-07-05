<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connexion = mysql_connect('localhost','root','');  
if (!$connexion ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 


$requete = 'SELECT aaaamm FROM mois';

$resultat = mysql_query($requete,$connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
    $annee = substr($tableau['aaaamm'],0,4);
	$m     = substr($tableau['aaaamm'],5,2);
    $requete2 = 'UPDATE mois SET aaaamm="'.$annee.'-'.$m.'" WHERE aaaamm="'.$tableau['aaaamm'].'"';
    mysql_query($requete2,$connexion) or die ('Echec UPDATE planning');}



?>
<script language='javascript' type='text/javascript'>
alert('Mise à jour des mois avec succès'); 
</script>