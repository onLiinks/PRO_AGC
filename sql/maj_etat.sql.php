<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

$requete = 'SELECT Id_candidature,
(SELECT hc2.Id_etat FROM historique_candidature hc2 WHERE hc2.Id_candidature=hc1.Id_candidature 
AND date=(SELECT max(hc3.date) FROM historique_candidature hc3 WHERE hc2.Id_candidature=hc1.Id_candidature)
) as Id_etat
FROM historique_candidature hc1
';



$resultat = mysql_query($requete,$id_connexion) or die ('Echec SELECT');
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
    $requete2 = 'UPDATE candidature SET Id_etat_candidature="'.$tableau['Id_etat'].'" WHERE Id_candidature="'.$tableau['Id_candidature'].'"';
    mysql_query($requete2,$id_connexion) or die ('Echec UPDATE etat');}

?>