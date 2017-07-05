<?php
/**
  * Fichier News.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * D�claration de la classe News
  */
class News 
{
    
	/**
	  * Affichage des news pour l'application
	  *
	  * @return string	   
	  */
    static function debut() 
	{
		$html = <<<EOT
        <div id='news'>
            <h2>Pour consulter les mises � jour du 08/08/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=361&amp;date=2013-08-08'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 15/07/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=360&amp;date=2013-07-15'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 25/04/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=357&amp;date=2013-04-25'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 19/03/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=356&amp;date=2013-03-19'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 20/12/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=355&amp;date=2012-12-20'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 04/12/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=354&amp;date=2012-12-04'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 08/11/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=353&amp;date=2012-11-08'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 25/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=352&amp;date=2012-10-25'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 09/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=351&amp;date=2012-10-09'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 01/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=350&amp;date=2012-10-01'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 18/09/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=347&amp;date=2012-09-18'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 12/07/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=346&amp;date=2012-07-12'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 19/04/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=345&amp;date=2012-04-19'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 29/03/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=344&amp;date=2012-03-29'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 07/02/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=343&amp;date=2012-02-07'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 30/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=342&amp;date=2012-01-30'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 19/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=341&amp;date=2012-01-19'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 12/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=340&amp;date=2012-01-12'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 28/10/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=330&amp;date=2011-10-28'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 20/09/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=321&amp;date=2011-09-20'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 17/06/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=320&amp;date=2011-06-17'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 12/05/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=319&amp;date=2011-05-12'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 07/04/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=318&amp;date=2011-04-07'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 28/12/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=13&amp;date=2010-12-28'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 01/07/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=12&amp;date=2010-07-01'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 03/06/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=11&amp;date=2010-06-03'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 20/05/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=10&amp;date=2010-05-20'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 06/05/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=9&amp;date=2010-05-06'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 02/03/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=8&amp;date=2010-03-02'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 20/11/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=7&amp;date=2009-11-20'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 08/10/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=6&amp;date=2009-10-08'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 08/07/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=5&amp;date=2009-07-08'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 29/05/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=4&amp;date=2009-05-29'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 16/03/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=3&amp;date=2009-03-16'>ici</a></h2>
            <h2>Pour consulter les mises � jour du 10/12/08 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=2&amp;date=2008-12-08'>ici</a></h2>
        </div>
        <script>afficherNews();</script>
EOT;
        return $html;
    }
	
	/**
	  * Affichage des news pour l'application
	  *
	  * @return string	   
	  */
    static function display($i,$date)
	{
            if ($i == 1) {
		$html = <<<EOT
		<div class='center'>
            <h2>Partie Commerciale</h2><br />
		    <ul>
		        <li>Modification du formulaire de s�lection des comp�tences pour une affaire</li>
				<li>Ajout des candidats valid�s dans la liste des ressources</li>
				<li>Ajout de la fonction de duplication d'un contrat d�l�gation</li>
		    </ul> 
            <br /><hr />
		    <h2>Partie RH</h2><br />
            <ul>		
		        <li>Les champs adresse �lectronique et Profil sont obligatoires</li>
		        <li>Int�gration de la notion d'ind�pendant + pr�tention co�t journalier</li>
		        <li>Int�gration des comp�tences et certifications</li>
		        <li>Modification du formulaire de s�lection des langues et niveaux de langues</li>
		        <li>Modification du formulaire de s�lection de la mobilit� d'un candidat</li>
		        <li>Ajout du filtre de recherche par identifiant du candidat</li>
		        <li>Ajout de la notion de dur�e d'experience en informatique</li>
		    </ul>
            <br /><br />
		    Correction BUG des mots de passe d'authentification avec accent
            <h2>Partie Commerciale</h2><br />
		    <ul>
		        <li>Modification du formulaire partie Infog�rance</li>
				<li>Ajout d'un onglet Propales Infog�rance</li>
		    </ul> 
            <br /><hr />
		    <h2>Partie RH</h2><br />
            <ul>		
		        <li>Gestion des homonymes</li>
		        <li>Ajout des niveaux pour les comp�tences</li>
		        <li>Int�gration du formulaire de cr�ation des annonces pour le site en ligne</li>
				<li>Modification des notions de pr�avis et de disponibilit�</li>
		    </ul>
		</div>
EOT;
        }
		elseif($i == 2) {
		    $html = <<<EOT
		    <div class='center'>
	            <h2>Partie Commerciale</h2><br />
			    <ul>
			        <li>Nouveau proc�d� de saisi des rendez-vous</li>
					<li>Ajout d'un onglet "Historique RDV" dans le menu gauche pour consulter les anciens rendez-vous saisis</li>
					<li>Possibilit� de recherche des rendez-vous par agence</li>
					<li>Les rendez-vous avec un compte rendu (saisi dans l'AGC d�sormais) apparaissent en vert, les autres en orange</li>
					<li>Ajout des statuts d'affaire "Op�rationnelle" et "Termin�e"</li>
					<li>Lors de la saisie d'une affaire, on choisit d'abord le p�le, ensuite le type de contrat</li>
					<li>R�affectation automatique des affaires existantes selon le nouveau mod�le P�le / Type contrat</li>
					<li>Certains champs sont devenus obligatoires dans la saisie d'une affaire</li>
					<li>Suppression du choix du p�le dans le formulaire d'une affaire puisqu'on le choisi avant</li>
					<li>Ajout d'une partie "Etat Technique" dans le formulaire affaire pour les types de contrat Infog�rance destin�e aux RT</li>
					<li>L'environnement Technique pour la partie Infog�rance a �t� enti�rement revu et sera compl�t� par les RT</li>
					<li>Ajout d'une case � choisir "Centre de service" pour les contrats de type Infog�rance</li>
					<li>Ajout d'une lise d�roulante "Potentiel de signer" dans la partie "Analyse Commerciale" pour les contrats de type Infog�rance</li>
					<li>Remise en forme de l'affichage des comp�tences dans le formulaire affaire</li>
					<li>Correction des bug d'affichage dans les contrats d�l�gation</li>
					<li>Ajout d'une case � cocher "Renouvellement" dans le contrat d�l�gation</li>
					<li>Possibilit� de cr�er directement le contrat d�l�gation au bout de la ligne d'une ressource associ�e � une affaire</li>
					<li>Modification de la partie "Proposition Commerciale" pour les contrats de type Infog�rance</li>
					<li>Ajout du module "ressources affect�es" avec les notions de "titulaire" / "suppl�ant" et ressource "Inclus dans le forfait" pour les contrats de type Infog�rance</li>
					<li>Possibilit� de suivre l'historique des propositions commerciales pour les contrats de type Infog�rance</li>
					<li>Possibilit� d'ajouter les documents relatifs � la proposition commerciale sign�e pour les contrats de type Infog�rance</li>
					<li>Ajout d'un module de suivi de CA pour les affaires sign�es avec une remont�e automatique des informations de CEGID</li>
					<li>Ajout d'un onglet "Candidats EMB" dans le menu de gauche pour afficher seulement les candidats embauch�s</li>
					<li>Ajout d'un onglet "Propales Infog�rance" dans le menu de gauche afin de suivre les propositions commerciales pour les contrats de type Infog�rance</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
	                <li>L'ensemble des modifications demand�es par le service RH a �t� r�alis�e.</li>
					<li>La premi�re personne qui modifie une candidature de cvweb devient le cr�ateur de la fiche.</li>
					<li>Ajout d'un filtre de recherche par mobilit� (D�partement).</li>
					<li>Le filtre mot cl� va rechercher en plus dans les attentes professionnelles et le commentaire de la fiche candidat.</li>
					<li>Tri par ordre d'arriv�e croissante et non pas par ordre alphab�tique lorsque le filtre est sur "non lu".</li>
			    </ul>
		    </div>
EOT;
		}
		elseif($i == 3) {
		    $html = <<<EOT
		    <div class='center'>
	            <h2>Partie Commerciale</h2><br />
			    <ul>
			        <li>Tous les rendez vous dont la date n'est pas encore pass�e sont en orange</li>
					<li>Affichage en consultation des donn�es collaborateurs lors de la s�lection de ce dernier dans le contrat d�l�gation</li>
					<li>Ajout des Ordres de mission</li>
					<li>Int�gration des collaborateurs ALTIQUE dans la liste d�roulante des ressources (pr�fix�es par ALC-)</li>
					<li>Ajout de l'information nombre de collaborateurs titulaires en suivi et nombre d'affaires op�rationnelles par commercial en page d'accueil</li>
					<li>Ajout de l'information "Vous avez xxx affaires remises et xxx affaires en cours de r�daction non modifi�es depuis plus d'1 mois" par commercial en page d'accueil</li>
					<li>D�placement de l'information "type de facturation : r�gie / forfaitis�" au niveau de la partie proposition commerciale</li>
					<li>Ajustement du CA pr�visionnel en fonction des types de contrat et de la dur�e d'intervention des collaborateurs pour l'AT</li>
					<li>Ajout des notions de ressources affect�es et de ressources compl�mentaires pour les types de contrats Infog�rance / Forfait de service</li>
					<li>Cr�ation d'un script mettant � jour automatiquement les dates de d�but et de fin des affaires AT en fonction de la date la plus ancienne et la plus r�cente des collaborateurs associ�s � l'affaire</li>
					<li>Cr�ation d'un script mettant � jour automatiquement les statuts des affaires en "op�rationnelle" / "termin�e" en fonction de la date de fin si leur num�ro AGC est dans CEGID</li>
					<li>Cr�ation d'un report : Affaires Sign�e / Op�rationnelles / Termin�es sans Contrat d�l�gation</li>
					<li>Cr�ation d'un report : Affaires Sign�e / Op�rationnelles / Termin�es sans CA</li>
					<li>Cr�ation d'un report : Affaires Sign�e / Op�rationnelles / Termin�es avec Contrats d�l�gation incomplets</li>
					<li>Le chiffre d'affaire ainsi que les dates de d�but et fin sont obligatoires pour les statuts d'affaire "remise","sign�e","op�rationnelle","termin�e"</li>
					<li>Ajout du bouton CPI (Chef de Projet Infog�rance) � c�t� de Titulaire / Suppl�ant</li>
					<li>Le tri des rendez vous pour les agences de Paris est fonctionnel</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
	                <li>Ajout du champ Fin CDD dans le formulaire candidat, partie "Informations d'embauche"</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Facturation / Relance</h2><br />
	            <ul>		
	                <li>Possibilit� de consulter / editer les contrats d�l�gation</li>
					<li>Possibilit� de cr�er / consulter / editer les ordres de mission</li>
			    </ul>				
		    </div>
EOT;
		}
		elseif($i == 4) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>G�n�ral</h2><br />
				<ul>
					<li>Int�gration de la nouvelle charte graphique pour les documents PDF</li>
					<li>Int�gration de la soci�t� WIZTIVI.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
                    <li>Dans la liste des ressources, une fois que les candidats ont �t� embauch�s, ils apparaissent uniquement dans la liste collaborateur (grise).</li>
                    <li>Il est d�sormais possible de saisir des actions selon diff�rents crit�res (Id affaire, Id rendezvous, client, contact�) via l�onglet � Saisir action � dans le menu de droite.</li>
					<li>Un filtre par ressource a �t� ajout� dans la page des Contrats D�l�gation</li>
					<li>Correction des mod�les de documents �dit�s par le p�le Formation</li>
					<li>Possibilit� de g�o localiser le client en cliquant sur la ville dans la page � Compte �</li>
			    </ul> 
		    </div>
EOT;
		}
		elseif($i == 5) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>G�n�ral</h2><br />
				<ul>
			        <li>Modification de la gestion des droits d'utilisateur</li>
					<li>Int�gration de la nouvelle charte graphique</li>
					<li>Int�gration de la soci�t� NEEDPROFILE</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Ajout des champs CA et remarque proposition dans le contrat d�l�gation</li>
					<li>Ajout de la notion de demande de changement relatif � un collaborateur</li>
					<li>Ajout de liens vers les rapports AGC nationaux, par commerciaux, par agences, par p�les</li>
					<li>Optimisation de l�affichage des ressources dans la partie proposition commerciale</li>
                    <li>Optimisation des calculs relatifs aux nombres de jours ouvr�s, de la marge et du chiffre d�affaire</li>
                    <li>Ajout de la notion de contrat d�l�gation Hors affaire</li>
					<li>Possibilit� de dupliquer un contrat d�l�gation directement depuis l�interface d�affichage des ces derniers</li>
					<li>Possibilit� d�utiliser le bouton � Demande de recrutement au service RH � en sp�cifiant l�adresse email du ou des charg�(s) de recrutement concern�(s)</li>
					<li>Possibilit� de mettre une dur�e en demi-journ�e dans la saisie d�une session de formation</li>
					<li>Affichage des commentaires du planning d�une affaire dans la consultation d�une affaire</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Ajout de l'historisation des actions relatives � un candidat</li>
					<li>Ajout de liens vers les rapports AGC des ressources humaines</li>
					<li>Ajout d'un code couleur pour les candidatures non lues provenant de cvweb en fonction de leur d�partement d'habitation</li>
					<li>Ajout du pays de r�sidence dans la partie embauche du formulaire de saisie d�un candidat (cette Information sera affich�e sur le contrat d�l�gation pour les nouveaux collaborateurs)</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Facturation / Relance</h2><br />
	            <ul>		
	                ...
			    </ul>
	            <br /><hr />
			    <h2>Partie Service Paie</h2><br />
	            <ul>		
	                <li>Ajout d'un lien vers une page affichant les changements de mission des collaborateurs</li>
			    </ul>				
		    </div>
EOT;
		}
		elseif($i == 6) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>G�n�ral</h2><br />
				<ul>
				    <li>Ajout d'un script de v�rification de la coh�rence des statuts des affaires.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Possibilit� de s�lectionner un sous-traitant existant dans le contrat d�l�gation au niveau de la ressource</li>
					<li>Ajout de la date de fin pr�visionnelle de l'affaire</li>
					<li>Ajout de la date de fin pr�visionnelle de la ressource dans la partie proposition commerciale</li>
					<li>Ajout du lien vers le rapport hebdomadaire par commercial</li>
					<li>Ajout du lien vers le rapport Previsionnel CA</li>
					<li>Ajout du lien vers le rapport des affaires arrivant � terme</li>
					<li>Mise en place des envois automatique des rapports aux diff�rents acteurs commerciaux de l'AGC</li>
					<li>Ajout du formulaire de cr�ation des bilans d'activit�</li>
					<li>Ajout du filtre par mois dans les bilans d'activit�</li>
					<li>Ajout de la liste d�roulante des agences au niveau des rendez-vous</li>
					<li>Suppression du responsable dans le formulaire de cr�ation d'une action</li>
					<li>Ajout de script de mise � jour automatique des informations relatives aux rapports �galement le midi</li>
					<li>Ajout des affaires de type de contrat Projet Forfaitaire dans l'affichage Propale Infog�rance du menu de gauche</li>
					<li>Ajout du CA Probable dans l'�ch�ancier de facturation</li>
					<li>Ajout du calcul automatique du nombre de jours ouvr�s dans le contrat d�l�gation</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Ajout de la notion de demande de recrutement</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Facturation / Relance</h2><br />
	            <ul>		
	                <li>Ajout du lien vers le rapport des ordres de mission</li>
					<li>Ajout du lien vers le rapport Previsionnel CA</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Service Paie</h2><br />
	            <ul>		
	                ...
			    </ul>				
		    </div>
EOT;
		}
		elseif($i == 7) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>G�n�ral</h2><br />
				<ul>
				    <li>Mise � jour de l'ergonomie.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Possibilit� d'�diter les rendez-vous en pdf</li>
					<li>Mise en place de s�lection par d�faut dans les contrats d�l�gation</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Modification du filtrage pour le code postal</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Facturation / Relance</h2><br />
	            <ul>		
	                <li>Ajout des colonnes client, ressource et agence dans le rapport des ordres de mission</li>
					<li>Correction de la r�cup�ration de l'agence dans les ODM pour la r�gion parisienne</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Service Paie</h2><br />
	            <ul>		
	                ...
			    </ul>
		    </div>
EOT;
		}
		elseif($i == 8) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>G�n�ral</h2><br />
				<ul>
				    <li>Mise � jour de l'ergonomie.</li>
					<li>Ajout d�info bulles d�aide.</li>
					<li>Suppression de l�ensemble des boutons � annuler �.</li>
					<li>Ajout de la rubrique � A venir � dans le menu aide permettant de connaitre les futures �volutions / correctifs de l�AGC.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Ajout d�un champ � Apporteur � dans la fiche affaire qui permettra de faire la distinction entre l�apporteur de l�affaire et le commercial en charge de l�affaire.</li>
					<li>Calcul de marge ajust�e dans la partie proposition commerciale pour les ressources en AT.</li>
					<li>L��ch�ancier de facturation (en bas de la fiche affaire) est d�sormais strictement identique � celui du rapport � Pr�visionnel CA �.</li> 
			    </ul>
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Remplacement du filtre � intitul� � par � profil � pour la partie demande de ressources.</li>
			    </ul>
	            <br /><hr />
			    <h2>Partie Facturation / Relance</h2><br />
	            <ul>		
					...
			    </ul>
	            <br /><hr />
			    <h2>Partie Service Paie</h2><br />
	            <ul>		
	                ...
			    </ul>
		    </div>
EOT;
        }
		elseif($i == 9) {
		    $html = <<<EOT
			<fieldset>
			    <legend>DERNIERE MISE A JOUR (06/05/2010)</legend><br />
			    <div class="left">
			        <h2>G�n�ral</h2><br />
				    <ul>
				        <li>Ajout des astuces sur la page d'accueil</li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Suppression du chargement automatique des anomalies � l'accueil</li>
					    <li>Ajout de la liste des ressources par affaire dans l'infobulle de l'interface de listing des affaires</li>
					    <li>Ajout des liens vers les affaires AGC depuis le contrat d�l�gation et l'ordre de mission</li>
					    <li>Ajout du listing des contrats d�l�gation par affaire sur la fiche de consultation d'une affaire</li>
                        <li>Correction du probl�me de calcul de marge par ressource lorsque les frais journalier n'�taient pas d�clar�s</li>
					    <li>Ajout de la notion de SDM dans les types de ressources</li>
						<li>Ajout des int�rimaires dans la liste d�roulante des ressources</li>
			        </ul>
	                <br />
				</div>
                <div class="right">				
			        <h2>Partie RH</h2><br />
	                <ul>
					    ...
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
					    <li>Ajout des num�ros d'affaires AGC et CEGID sur les ODM</li>
						<li>Ajout de 3 coches en haut � droite du contrat d�l�gation (Pr�pa Fact, ODM, CA, Affaire CEGID)</li>
					    <li>Correction du chargement du contrat d�l�gation en PDF (Il n'est plus n�cessaire de cliquer sur "Refresh Page" sous internet explorer)</li>
			        </ul>
	                <br />
			        <h2>Partie Service Paie</h2><br />
	                <ul>		
	                    ...
			        </ul>
				</div>	
		    </fieldset>
EOT;
		}
		elseif($i == 10) {
		    $html = <<<EOT
			<fieldset>
			    <legend>DERNIERE MISE A JOUR (20/05/2010)</legend><br />
			    <div class="left">
			        <h2>G�n�ral</h2><br />
				    <ul>
				        <li></li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Possibilit� de modifier les historiques de changement de statut d'une affaire</li>
					    <li>Am�lioration de la gestion de la suppression des ressources dans le formulaire affaire</li>
					    <li>Affichage de (Mat�riel) dans l'interface de listing des contrats d�l�gation pour les contrats d�l�gation mat�riel</li>
			        </ul>
	                <br />
				</div>
                <div class="right">				
			        <h2>Partie RH</h2><br />
	                <ul>
					    ...
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
                        ...
			        </ul>
	                <br />
			        <h2>Partie Service Paie</h2><br />
	                <ul>		
	                    ...
			        </ul>
				</div>	
		    </fieldset>
EOT;
		}
		elseif($i == 11) {
		    $html = <<<EOT
			<fieldset>
			    <legend>DERNIERE MISE A JOUR (03/06/2010)</legend><br />
			    <div class="left">
			        <h2>G�n�ral</h2><br />
				    <ul>
				        <li></li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Les directeurs d'agence ont d�sormais les droits en modification sur les affaires de leurs agences</li>
						<li>Ajout de la colonne P�le dans l'interface de listing des affaires</li>
					    <li>L'expediteur d'un contrat d�l�gation est d�sormais en copie du mail envoy�</li>
					    <li>Les cliens "� risque" dans CEGID (Feu Orange ou Rouge) sont d�sormais affich�s d'une couleur diff�rente dans la liste d�roulante de clients</li>
			        </ul>
	                <br />
				</div>
                <div class="right">				
			        <h2>Partie RH</h2><br />
	                <ul>
					    <li>Demandes -�Changement du terme ��demande en cours�� en ��demande active�� </li>
						<li>Demandes -�Dans la liste des demandes, ajout du bouton CV sur la ligne du candidat (accueil des demandes + cr�ation de la demande)</li>
						<li>Candidats -�Ajout des sp�cialit�s ��ERP��, ��CRM��, ��support applicatif��, ��d�cisionnel��, ��t�l�com mobiles�� et ��t�l�com ToIP/VoIP�</li>
						<li>Candidats -�Ajout du cursus ��> � BAC+5��</li>
						<li>Candidats -�Ajout du cursus informatique ��non dipl�mant��</li>
						<li>Candidats -�Cr�ation d'un filtre sur les ��travailleurs handicap�s��</li>
						<li>Candidats -�Ajouter du statut ��embauche sous traitant��</li>
						<li>Candidats -�Masquer les agences de rattachement (lynt, elytex, etc.)</li>
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
                        <li>Sur les ODM, la date indiqu�e sur le PDF "Fait le" est �gale � la date d'envoi si elle existe, et � la date du jour sinon.</li>
			        </ul>
	                <br />
			        <h2>Partie Service Paie</h2><br />
	                <ul>		
	                    ...
			        </ul>
				</div>	
		    </fieldset>
EOT;
		}
		elseif($i == 12) {
		    $html = <<<EOT
			<fieldset>
			    <legend>DERNIERE MISE A JOUR (01/07/2010)</legend><br />
			    <div class="left">
			        <h2>G�n�ral</h2><br />
				    <ul>
				        <li></li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Mise en place d'un rapport de listing des affaires disponible <a href="http://srv108.proservia.lan/Reports/Pages/Report.aspx?ItemPath=%2fAGC%2fAGC+National%2fListe+detaillee+des+Affaires+AGC">ici</a></li>
			        </ul>
	                <br />
				</div>
                <div class="right">				
			        <h2>Partie RH</h2><br />
	                <ul>
					    <li>Ajout du statut de l�affaire dans la partie � demande de ressource �.</li>
						<li>jout d�un filtre pour n�avoir que les candidats embauch�s dans la partie candidat.</li>
						<li>Suppression du menu � Candidat EMB �</li>
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
                        <li>Tous les membres du service facturation peuvent modifier les ordres de mission.</li>
						<li>Ajout de l�intitul� de l�affaires dans le contrat d�l�gation.</li>
						<li>Am�lioration de l�affichage des affaires CEGID qui s�affichent dans l�ODM.</li>
			        </ul>
	                <br />
			        <h2>Partie Service Paie</h2><br />
	                <ul>		
	                    ...
			        </ul>
				</div>	
		    </fieldset>
EOT;
		}
		elseif($i == 318) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Am�lioration du syt�me de mis � jour.</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Mis en place des demandes de changment.</li>
                            <li>Possibilit� de rentrer le TJM sur les CD peu importe le type d'affaire</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>...</li>
                        </ul><br />
                            <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>...</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout d'un crit�re type de recrutement sur les demandes de recrutement</li>
                            <li>Possibilit� de dupliquer une demande de recrutement avec son historique</li>
                            <li>Ajout d'un filtre sur l'historique des demandes dans le listing des demandes de recrutement</li>
                            <li>Ajout d'une nature de candidature "Needprofile"</li>
                            <li>Ajout d'un �tat de candidat "Transformation CDD-CDI"</li>
                            <li>Les colonnes d'embauches sont maintenant scind�es en "Par profil" et "Par mission" dans le rapport hebdo</li>
                            <li>Lors d'un changement de statut sur une demande de recrutement, l'historique est mis � jour directement</li>
                            <li>Le champs "Par profil" et "Par mission" est maintenant obligatoire pour un candidat embauch�</li>
                            <li>Les champs "description du poste" et "comp�tences" doivent maintenant contenir 20 caract�res minimum sur une demande de recrutement</li>
                            <li>Ajout des IC dans la liste "r�alis� par" des entretiens</li>
                            <li>Dans le rapport hebdo, l'embauche est maintenant comptabilis� au cr�ateur du statut</li>
                            <li>Probl�me de lien sur les candidats positionn�s sur les demandes de recrutement</li>
                            <li>Un candidat vide n'est plus cr�� � la cr�ation d'une demande de recrutement</li>
                            <li>Possibilit� de s�lectionner plusieurs personnes dans les filtre sur l'historique</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 319) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>...</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Nouveau syst�me de listes li�es pour la s�lection d'une ressource sur les contrats d�l�gation</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Relance automatique des collaborateurs pour les ODM</li>
                        </ul><br />
                            
                    </div>
                    <div class="right">
                    <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Ajout d'un champ concernant les �volutions pr�vus dans les contrats d�l�gation</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout des �tats "Transformation Interim - CDD" et "'Transformation Interim - CDI"</li>
                            <li>Ajouter d'un filtre "prioritaire" sur les demandes de recrutement</li>
                            <li>Sur le r�capitulatifs des demandes de recrutement, les demandes prioritaires sont maintenant plus visible</li>
                            <li>Revu de l'affichage des candidats � la cr�ation d'une demande de recrutement pour coller � l'�dition d'une demande</li>
                            <li>Revu du syst�me de s�lection de la mobilit� sur les fiches candidats et CVWeb</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 320) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Une grande partie des listes est (enfin) triable en cliquant sur l'ent�te des colonnes</li>
                            <li>R�organisation des menus</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Int�gration de la pond�ration (avec historique) sur les affaires</li>
                            <li>Ajout d'un listing des affaires permettant de mettre � jour la pond�ration de mani�re directe</li>
                            <li>Ajout d'un filtre par mots cl�s sur le listing des affaires</li>
                            <li>Ajout de rapports pour les CA pond�r�s</li>
                            <li>Les directeurs d'agence re�oivent un mail r�capitulatif des demandes de recrutement en cours pour leurs agences tous les vendredi</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Ajout d'un compte des ODM sur le rapport des ODM non retourn�s</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Ajout de la possibilit� de valider et int�grer une demande de changement directement sur la demande</li>
                            <li>Ajout d'un filtre par �tat "valider" et "int�grer" sur le listing des demandes de changement</li>
                            <li>Les dates souhait�es des demandes de changement sont maintenant des champs obligatoires</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout d'un filtre par type de recrutement sur les demandes de recrutement</li>
                            <li>Ajout des types de contrat "Contrat d'apprentissage" et "Contrat de professionnalisation"</li>
                        </ul>                       
                    </div>
EOT;
                }
                elseif($i == 321) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Int�gration de Netlevel</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Correction d'un bug d'arrondi des CA pr�visionnel</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>...</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>...</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Le champ agence de rattachement n'as plus de s�lection par d�faut et est maintenant obligatoire</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 330) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Gestion des cookies multi-soci�t�s</li>
                            <li>Possibilit� d'exporter au format CSV les listes</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Revue du syst�me de gestion des contrats d�l�gation (maintenant li� aux ressources affaires)</li>
                            <li>Mise en place d'un workflow de validation des contrats d�l�gation par l'ADV</li>
                            <li>Revue du workflow des statuts d'une affaire avec incidence sur la pond�ration</li>
                            <li>Les directeurs de r�gion peuvent maintenant modifier les affaires de leur r�gion</li>
                            <li>Regroupement des agences de Paris en une seule agence</li>
                            <li>Les candidats ayant pour statut "embauche interimaire" ou "embauche sous-traitant" apparaissent maintenant dans les listes des ressources sur une affaire</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Ajout de la validation des contrats d�l�gation avant de pouvoir cr�er les ODM</li>
                            <li>Mise en place d'un workflow de validation des contrats d�l�gation par l'ADV</li>
                        </ul><br />
                    
                        <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Revue de l'affichage des demandes de changement</li>
                            <li>Ajout du service contr�le de gestion dans le workflow des demandes de changement</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout des �tats "Transformation ST - CDI" et "Transformation ST - CDD" pour les candidats</li>
                            <li>Ajout de champs obligatoires pour la saisie d'un candidat embauch� (civilit�, nom de jeune fille, date fin cdd et travailleur handiap�)</li>
                            <li>Ajout de v�rification sur le num�ro de s�curit� sociale (sexe, ann�e naissance, mois naissance, d�partement et cl� de v�rification)</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 340) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Int�gration de Salesforce</li>
                            <li>Implementation du m�canisme de SSO</li>
                        </ul>
                    </div>
EOT;
		}
                elseif($i == 341) {
		    $html = <<<EOT
   <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la redirection apr�s une authentification</li>
                            <li>R�organisation des groupes pour les droits</li>
                            <li>Correction de bug mineur suite � la version 3.4.0</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Possibilit� de dupliquer un contrat d�l�gation sur une affaire diff�rente</li>
                            <li>Utilisation d'une r�f�rence affaire plus simple</li>
                            <li>Le filtre par affaire dans les contrats d�l�gation affiche maintenant les contrats d�l�gation des affaires SFC et AGC</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout de la possibilit� de refuser une demande de changement (envoi un mail au cr�ateur de de la demande)</li>
                            <li>Correction d'un bug sur le filtre par affaire des demandes de recrutement</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 342) {
		    $html = <<<EOT
   <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la d�connexion</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Gestion des droits contrats d�l�gation revu pour plus de rapidit� � l'affichage</li>
                            <li>Utilisation d'une r�f�rence affaire plus simple</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Retour sur l'ancien mode listing pour le suivi candidats pour plus de rapidit� (plus possible de trier)</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 343) {
		    $html = <<<EOT
   <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la d�connexion</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Ajout du menu de cr�ation d'un contrat d�l�gation avec s�lection de l'opportunit� SFC</li>
                            <li>Ajout de l'intitul�  d'opportunit�s SFC sur le listing CD</li>
                            <li>Modification du script d'export vers Idea pour prendre en comptes les opportunit�s SFC</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Le num�ro d'affaire Cegid doit s'afficher de nouveau sur les CD</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout d'un groupe CRH</li>
                            <li>A l'embauche d'un candidat, possibilit� de saisir les dates sous la forme xx-xx-xxxx ou xx/xx/xxxx</li>
                            <li>Envoi d'un mail aux RRH en cas d'embauche d'un candidat handicap�</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 344) {
		    $html = <<<EOT
   <div class="left">
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Remont�e de la description depuis l'opportunit� SFC dans les t�ches des contrats d�l�gation</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
                        <ul>
                            <li>Possibilit� de d�finir plusieurs destinataires pour les mails envoy�s lors de la gestion des CD</li>
                            <li>N'apparaissent dans les listes des anciens salari�s que les personnes dont la date de sortie est effectivement pass�</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>S�paration du type de contrat sur les annonces</li>
                            <li>Ajout de la nature "CV Freelance-info" sur les candidatures</li>
                            <li>Ajout en automatique de la mention "(H/F)" sur les annonces</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 345) {
		    $html = <<<EOT
   <div class="left">
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Lors de la duplication d'un contrat d�l�gation, vous n'avez plus la possibilit� de changer de compte (pour cela, il faut en cr�er un nouveau) et ne pouvez s�lectionner que les opportunit�s li�es</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Lors de la s�lection de la mobilit� d'un candidat, apparaissent maintenant en gras les r�gions ayant des d�partements s�lectionn�s</li>
                            <li>Possibilit� de dupliquer une demande de ressource en reprenant l'historique du statut, l'historique des candidats ou les deux</li>
                            <li>La localisation est maintenant obligatoire dans les annonces</li>
                            <li>Ajout d'un domaine pour les annonces (utile pour la recherche dans CVWeb</li>
                            <li>Ajout d'un lien vers l'ensemble des rapports</li>
                            <li>La saisie de l'exp�rience informatique est maintenant non obligatoire si "Fonctions Hors Informatique" est s�lectionn�</li>
                            <li>Pour un �tat de candidature valid�, la s�lection sur profil est par d�faut</li>
                            <li>Ajout d'un type de contrat "Int�rimaire" sur les informations d'embauche</li>
                            <li>En cas de champs modifi�s sur une fiche candidat, une confirmation vous sera demand� avant de quitter la page</li>
                            <li>Pour les demandes de ressource en veille, le client n'est plus obligatoire</li>
                            <li>Ajout d'un filtre sur l'identifiant des demandes ressource</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 346) {
		    $html = <<<EOT
   <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Simplification du formulaire des demandes de changement (moins de champs � remplir)</li>
                            <li>Les demandes de changement staff sont maintenant envoy�es � Franck et Eliane</li>
                            <li>En cas de s�lection d'un service CDS dans les demandes de changement, obligation de remplir le champ CDS</li>
                            <li>Correction d'un bug permettant la cr�ation de demande de changement avec des valeurs identiques</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Les relances des ODM sont maintenant envoy�es aux responsables saisies sur le contrat d�l�gation plut�t qu'au DA</li>
                            <li>Apparait maintenant sur les contrats d�l�gation le champ "type" (New Business, Renouvellement) issu de Salesforce</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Lors de la synchronisation des candidats entre l'AGC et CVWeb, les informations modifi�es par le candidat mettent � jour les informations de l'AGC si non modifi�es par un CR</li>
                            <li>Adaptation des formulaires candidat sur la base Ovialis</li>
                        </ul>                       
                    </div>
EOT;
		}elseif($i == 347) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Int�gration du service formation avec Salesforce</li>
                        </ul><br />
                    </div>
EOT;
		}elseif($i == 350) {
		    $html = <<<EOT
   <div>
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Mise � jour de la charte graphique</li>
                        </ul><br />
                    </div>
EOT;
                }elseif($i == 351) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2><br />
                        <ul>
                            <li>Mise � la charte des documents g�n�r�s par l'AGC (CD, ODM, demandes ressource, documents formation...)</li>
                        </ul><br /><br />
                        <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Ajout du champ "Apporteur d'affaire" issu de Salesforce sur les contrats d�l�gation</li>
                        </ul>
                        </div>
                        <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Remplacement du domaine dans les annonces par le m�tier, mise en corr�lation avec le nouveau site internet</li>
                        </ul>                       
                    </div>
                    </div>
EOT;
		}elseif($i == 352) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie RH</h2><br />
                        <ul>
                            <li>Nouveau format d'annonce</li>
                        </ul><br />
                        </div>
EOT;
		}elseif($i == 353) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Correction du bug emp�chant la suppression d'une annonce</li>
                            <li>La table associant un CR � son adresse mail agence est maintenant mise � jour automatiquement (mail re�u en cas de postulation sur CVWeb)</li>
                            <li>Correction d'un bug emp�chant de consulter les offres auxquelles un candidat a postul�</li>
                        </ul>
                        <br />
                        <h2>Partie Commerciale</h2>
                        <br />
                        <ul>
                            <li>Possibilit� d'effectuer des demandes de changement pour du personnel de structure</li>
                        </ul>
                        <br />
                    </div>
                    <div class="right">
                        <h2>Partie Service Paie</h2>
                        <br />
                        <ul>
                            <li>Les valideurs d'une demande de changement peuvent maintenant r�-ouvrir une demande si celle-ci a �t� refus�</li>
                        </ul>
                        <br />
                    </div>
EOT;
		}elseif($i == 354) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Possibilit� de cr�er un nouveau m�tier pour les annonces par les CRH</li>
                        </ul>
                        <br />

                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Ajout de la s�lection d'une raison en cas de refus d'un contrat d�l�gation</li>
                        </ul>
                        <br />
                    </div>
EOT;
		}elseif($i == 355) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie Commerciale</h2>
                        <br />
                        <ul>
                            <li>Int�gration du nouvel accord de gestion des notes de frais sur les CD</li>
                        </ul>
                        <br />
                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Le valideur re�oit en copie le mail de refus lors d'un retour de CD</li>
                            <li>Correction d'un bug lors d'un refus de CD avec caract�re sp�ciaux</li>
                        </ul>
                        <br />
                    </div>
EOT;
                }elseif($i == 356) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Saisie des horaires sur les CD modifi�</li>
                        </ul>
                        <br />
                    </div>
EOT;
                }elseif($i == 357) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Partie Commerciale</h2>
                        <br />
                        <ul>
                            <li>Int�gration de Taleo dans les contrats d�l�gation</li>
                        </ul>
                        <br />
                    </div>
EOT;
                }elseif($i == 360) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2>
                        <br />
                        <ul>
                            <li>Int�gration des soci�t�s ARCHITECH et TIMARANCE</li>
                        </ul>
                        <br />
                        <h2>Partie Commerciale</h2>
                        <br />
                        <ul>
                            <li>Revue des frais</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Possibilit� d'indiquer la raison en cas de r�-ouverture d'un contrat d�l�gation</li>
                        </ul>
                        <br />
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Possibilit� d'indiquer le salaire lors d'un contrat d�l�gation pour le staff</li>
                        </ul>
                    </div>
EOT;
                }elseif($i == 361) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>G�n�ral</h2>
                        <br />
                        <ul>
                            <li>Correction de divers bugs mineurs suite � la migration</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>L'ADV est maintenant en copie en cas de r�-ouverture d'un contrat d�l�gation</li>
                        </ul>
                        <br />
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Ajout dans Taleo des  motifs de CDD, repris sur les contrats d�l�gation</li>
                        </ul>
                    </div>
EOT;
                }
                

                $_SESSION[SESSION_PREFIX.'logged']->utilisateur->version = str_replace('.','',VERSION);
                $version = ($_GET['nb']) ? chunk_split($_GET['nb'], 1, '.') : VERSION;
                $html = "
                    <fieldset>
                        <legend class=\"cliquable\" id='news_lengend' onclick=\"toggleNews('news_content')\"><img id=\"news_contentImage\" src=\"../ui/images/plus.gif\">MISE A JOUR " . $version . " (" . DateMysqltoFr($date) . ")</legend><br />
                        <div id='news_content' style='display:none'>
                            " . $html . "
                        </div>
		    </fieldset>
                    <br /><br />
                    <script type=\"text/javascript\">new Effect.Parallel([
                                new Effect.Pulsate('news_lengend', { pulses: 3 }),
                                new Effect.Highlight('news_lengend', { startcolor: '#ffff99',
                            endcolor: '#ffffff' })
                        ], {
                        duration: 1.5
                      });</script>";
        return $html;
    }
	
    /**
	  * Affichage des �volutions de l'application � venir
	  *
	  * @return string	   
	  */
    static function toCome() 
	{
		$html = '';
        return $html;
    }
	
}
?>