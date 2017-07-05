<?php
#####################################################################################################
#												                                                	#
# Script permettant de mettre à jour la liste des intitulé pour les affaires du pôle formation		#
#																									#
#####################################################################################################

/*
	Liste des correspondances anciens intitulés -> nouveaux
	
		ANCIEN 			->		NOUVEAU
		
	Base de Données			Informatique Technique 
	Bureautique				Bureautique/Multimédia 
	Citrix					Informatique Technique 
	Développement			Informatique Technique 
	Infrastructure			Informatique Technique 
	Langues Etrangères		Langues Etrangères 					Pas de modification
	Messagerie				Informatique Technique 
	Multimedia				Bureautique/Multimédia 
	Portail					Informatique Technique 
	Production				Informatique Technique 
	Projet/Qualité			Informatique Technique 
	Qualité (ITIL, CMMI)	Itil 								Toutes les affaires précédentes associées à Qualité (Itil, CMMi) passent en Itil
	Qualité (ITIL, CMMI)	CMMi 										sauf celles du client E-Testing qui doivent passer en CMMi
	Ressources Humaines		Ressources Humaines 				Pas de modification
	Sécurité				Informatique Technique

*/
// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 


//Modification des intitulés et création de l'intitulé CMMi
mysql_query("UPDATE intitule SET libelle='Informatique Technique' where libelle ='Base de Données'");
mysql_query("UPDATE intitule SET libelle='Bureautique/Multimédia' where libelle ='Bureautique'");
mysql_query("UPDATE intitule SET libelle='ITIL' where libelle ='Qualité (ITIL, CMMI)'");
mysql_query("INSERT INTO intitule SET libelle='CMMI', Id_pole=3");


//Mise à jour des identifiants des nouveaux intitulés dans l'affaire
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=64");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=65");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=66");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=67");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=68");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=63");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=70");
mysql_query("UPDATE description SET Id_intitule=69  WHERE Id_intitule=71");
mysql_query("UPDATE description SET Id_intitule=73  WHERE Id_intitule=74");
mysql_query("UPDATE description SET Id_intitule=77  WHERE Id_affaire IN (SELECT Id_affaire FROM affaire WHERE Id_compte=\"9ETESTING\")");

//Suppression des anciens intitulés
mysql_query("DELETE FROM intitule WHERE Id_intitule=64");
mysql_query("DELETE FROM intitule WHERE Id_intitule=65");
mysql_query("DELETE FROM intitule WHERE Id_intitule=66");
mysql_query("DELETE FROM intitule WHERE Id_intitule=67");
mysql_query("DELETE FROM intitule WHERE Id_intitule=68");
mysql_query("DELETE FROM intitule WHERE Id_intitule=63");
mysql_query("DELETE FROM intitule WHERE Id_intitule=70");
mysql_query("DELETE FROM intitule WHERE Id_intitule=71");
mysql_query("DELETE FROM intitule WHERE Id_intitule=74");

?>