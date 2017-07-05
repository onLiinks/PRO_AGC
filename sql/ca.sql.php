<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL


$id_connexion=mysql_connect('localhost','root','');
mysql_select_db('gescom',$id_connexion);

$requete = 'select proposition_ressource.Id_proposition, proposition_ressource.cout_journalier, proposition_ressource.frais_journalier, proposition_ressource.tarif_journalier, affaire.Id_affaire, duree FROM affaire INNER JOIN proposition ON affaire.Id_affaire=proposition.Id_affaire INNER JOIN proposition_ressource ON proposition.Id_proposition=proposition_ressource.Id_proposition where Id_prestation=1 and ca!=0 and proposition_ressource.tarif_journalier!=0 and proposition_ressource.cout_journalier!=0 group by Id_affaire';

$resultat = mysql_query($requete,$id_connexion);

while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC))
{
	if($tableau['cout_journalier'] && $tableau['tarif_journalier'] ) {
	    $ca = round(($tableau['tarif_journalier']*$tableau['duree']),2);
	    $marge = round(100 * (($tableau['tarif_journalier'] - $tableau['cout_journalier']) / $tableau['tarif_journalier']),2);
        $requete2 = 'UPDATE proposition_ressource SET ca="'.$ca.'", marge="'.$marge.'"  WHERE Id_proposition="'.$tableau['Id_proposition'].'"';
        $resultat2 = mysql_query($requete2,$id_connexion);
	}
}
?>