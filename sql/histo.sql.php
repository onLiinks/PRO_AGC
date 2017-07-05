<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

$requete = 'SELECT Id_affaire FROM affaire aff WHERE aff.Id_statut NOT IN (SELECT Id_statut FROM historique_statut WHERE Id_affaire=aff.Id_affaire)';
$resultat = mysql_query($requete,$id_connexion);

while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC))
{
    $requete2 = 'SELECT date_modification, Id_statut, commercial FROM affaire WHERE Id_affaire="'.$tableau['Id_affaire'].'"';
    $resultat2 = mysql_query($requete2,$id_connexion) or die ('Echec SELECT date de modification');	while ($tableau2 = mysql_fetch_array($resultat2,MYSQL_ASSOC)) {	    $requete3 = 'INSERT INTO historique_statut set Id_affaire="'.$tableau['Id_affaire'].'", date="'.$tableau2['date_modification'].'", Id_statut="'.$tableau2['Id_statut'].'", Id_utilisateur="'.$tableau2['commercial'].'", commentaire=""';
        mysql_query($requete3,$id_connexion) or die ('Echec SELECT date de modification');	}}

?>