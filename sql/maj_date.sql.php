<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

$requete = 'SELECT DISTINCT aff.Id_affaire as Id_aff,
(SELECT min(debut) FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition and debut!="0000-00-00") as mindate,
(SELECT max(fin) as maxdate FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition) as maxdate
FROM affaire aff INNER JOIN proposition p1 ON p1.Id_affaire=aff.Id_affaire
INNER JOIN proposition_ressource ON p1.Id_proposition=proposition_ressource.Id_proposition 
and Id_type_contrat=1 and debut !="0000-00-00" and fin !="0000-00-00"';
$resultat = mysql_query($requete,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
    $requete2 = 'UPDATE planning SET date_debut="'.$tableau['mindate'].'", date_fin="'.$tableau['maxdate'].'" WHERE Id_affaire="'.$tableau['Id_aff'].'"';
    mysql_query($requete2,$id_connexion) or die ('Echec UPDATE planning');}


?>