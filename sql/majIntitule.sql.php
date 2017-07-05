<?php
#####################################################################################################
#												                                                	#
# Script permettant de mettre � jour la liste des intitul� pour les affaires du p�le formation		#
#																									#
#####################################################################################################

/*
	Liste des correspondances anciens intitul�s -> nouveaux
	
		ANCIEN 			->		NOUVEAU
		
	Base de Donn�es			Informatique Technique 
	Bureautique				Bureautique/Multim�dia 
	Citrix					Informatique Technique 
	D�veloppement			Informatique Technique 
	Infrastructure			Informatique Technique 
	Langues Etrang�res		Langues Etrang�res 					Pas de modification
	Messagerie				Informatique Technique 
	Multimedia				Bureautique/Multim�dia 
	Portail					Informatique Technique 
	Production				Informatique Technique 
	Projet/Qualit�			Informatique Technique 
	Qualit� (ITIL, CMMI)	Itil 								Toutes les affaires pr�c�dentes associ�es � Qualit� (Itil, CMMi) passent en Itil
	Qualit� (ITIL, CMMI)	CMMi 										sauf celles du client E-Testing qui doivent passer en CMMi
	Ressources Humaines		Ressources Humaines 				Pas de modification
	S�curit�				Informatique Technique

*/
// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 


//Modification des intitul�s et cr�ation de l'intitul� CMMi
mysql_query("UPDATE intitule SET libelle='Informatique Technique' where libelle ='Base de Donn�es'");
mysql_query("UPDATE intitule SET libelle='Bureautique/Multim�dia' where libelle ='Bureautique'");
mysql_query("UPDATE intitule SET libelle='ITIL' where libelle ='Qualit� (ITIL, CMMI)'");
mysql_query("INSERT INTO intitule SET libelle='CMMI', Id_pole=3");


//Mise � jour des identifiants des nouveaux intitul�s dans l'affaire
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

//Suppression des anciens intitul�s
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