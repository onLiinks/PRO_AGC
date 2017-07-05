<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

$id_connexion=mysql_connect('localhost','root','');
mysql_select_db("AGC_PROSERVIA",$id_connexion) or die ('Echec connexion BDD');

$requete = '
SELECT Id_candidature, Id_ressource, commentaire
FROM candidature
';

$resultat = mysql_query($requete,$id_connexion) or die ('Echec SELECT :'.mysql_error());
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
	$com = mysql_escape_string($tableau['commentaire']);
	$result = mysql_query('SELECT hac.Id_candidature, hac.Id_utilisateur, DATE_FORMAT(hac.date_action,"%d-%m-%Y") as date, am.libelle
		FROM historique_action_candidature hac
		INNER JOIN action_mener am ON am.Id_action_mener = hac.Id_action_mener 
		WHERE hac.Id_candidature = '.$tableau['Id_candidature']
	,$id_connexion) or die ('Echec SELECT :'.mysql_error());
	while ($tableau2 = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$com .= '\n\n'.$tableau2['libelle'].' par '.$tableau2['Id_utilisateur'].' le '.$tableau2['date'];
	    $requete2 = 'UPDATE candidature SET commentaire="'.$com.'" WHERE Id_candidature="'.$tableau['Id_candidature'].'"';
	    mysql_query($requete2,$id_connexion) or die ('Echec UPDATE etat : '.mysql_error());
	}
}



$requete = '
SELECT Id_candidature, Id_ressource, commentaire
FROM candidature
';

$resultat = mysql_query($requete,$id_connexion) or die ('Echec SELECT :'.mysql_error());
while ($tableau = mysql_fetch_array($resultat,MYSQL_ASSOC)) {
	$com = mysql_escape_string($tableau['commentaire']);
	$result = mysql_query('SELECT dr.Id_demande_ressource,comp_profil,client,Id_ic,DATE_FORMAT(drc.date,"%d-%m-%Y") as date FROM demande_ressource dr 
		INNER JOIN demande_ressource_candidat drc ON dr.Id_demande_ressource=drc.Id_demande_ressource 
		WHERE Id_ressource='.$tableau['Id_ressource'],$id_connexion) or die ('Echec SELECT :'.mysql_error());
	while ($tableau2 = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$com .= '\n\nPositionné sur la demande '.$tableau2['comp_profil'].' ('.$tableau2['Id_demande_ressource'].') pour le client '.$tableau2['client'].' par '.$tableau2['Id_ic'].' le '.$tableau2['date'];
	    $requete2 = 'UPDATE candidature SET commentaire="'.$com.'" WHERE Id_candidature="'.$tableau['Id_candidature'].'"';
	    mysql_query($requete2,$id_connexion) or die ('Echec UPDATE etat : '.mysql_error().'<br />'.$com);
	}
}

?>
