<?php
/**
  * Fichier News.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Déclaration de la classe News
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
            <h2>Pour consulter les mises à jour du 08/08/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=361&amp;date=2013-08-08'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 15/07/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=360&amp;date=2013-07-15'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 25/04/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=357&amp;date=2013-04-25'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 19/03/13 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=356&amp;date=2013-03-19'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 20/12/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=355&amp;date=2012-12-20'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 04/12/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=354&amp;date=2012-12-04'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 08/11/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=353&amp;date=2012-11-08'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 25/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=352&amp;date=2012-10-25'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 09/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=351&amp;date=2012-10-09'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 01/10/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=350&amp;date=2012-10-01'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 18/09/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=347&amp;date=2012-09-18'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 12/07/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=346&amp;date=2012-07-12'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 19/04/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=345&amp;date=2012-04-19'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 29/03/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=344&amp;date=2012-03-29'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 07/02/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=343&amp;date=2012-02-07'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 30/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=342&amp;date=2012-01-30'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 19/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=341&amp;date=2012-01-19'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 12/01/12 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=340&amp;date=2012-01-12'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 28/10/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=330&amp;date=2011-10-28'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 20/09/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=321&amp;date=2011-09-20'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 17/06/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=320&amp;date=2011-06-17'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 12/05/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=319&amp;date=2011-05-12'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 07/04/11 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=318&amp;date=2011-04-07'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 28/12/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=13&amp;date=2010-12-28'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 01/07/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=12&amp;date=2010-07-01'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 03/06/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=11&amp;date=2010-06-03'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 20/05/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=10&amp;date=2010-05-20'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 06/05/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=9&amp;date=2010-05-06'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 02/03/10 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=8&amp;date=2010-03-02'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 20/11/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=7&amp;date=2009-11-20'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 08/10/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=6&amp;date=2009-10-08'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 08/07/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=5&amp;date=2009-07-08'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 29/05/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=4&amp;date=2009-05-29'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 16/03/09 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=3&amp;date=2009-03-16'>ici</a></h2>
            <h2>Pour consulter les mises à jour du 10/12/08 cliquez <a href='../membre/index.php?a=consulterNews&amp;nb=2&amp;date=2008-12-08'>ici</a></h2>
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
		        <li>Modification du formulaire de sélection des compétences pour une affaire</li>
				<li>Ajout des candidats validés dans la liste des ressources</li>
				<li>Ajout de la fonction de duplication d'un contrat délégation</li>
		    </ul> 
            <br /><hr />
		    <h2>Partie RH</h2><br />
            <ul>		
		        <li>Les champs adresse électronique et Profil sont obligatoires</li>
		        <li>Intégration de la notion d'indépendant + prétention coût journalier</li>
		        <li>Intégration des compétences et certifications</li>
		        <li>Modification du formulaire de sélection des langues et niveaux de langues</li>
		        <li>Modification du formulaire de sélection de la mobilité d'un candidat</li>
		        <li>Ajout du filtre de recherche par identifiant du candidat</li>
		        <li>Ajout de la notion de durée d'experience en informatique</li>
		    </ul>
            <br /><br />
		    Correction BUG des mots de passe d'authentification avec accent
            <h2>Partie Commerciale</h2><br />
		    <ul>
		        <li>Modification du formulaire partie Infogérance</li>
				<li>Ajout d'un onglet Propales Infogérance</li>
		    </ul> 
            <br /><hr />
		    <h2>Partie RH</h2><br />
            <ul>		
		        <li>Gestion des homonymes</li>
		        <li>Ajout des niveaux pour les compétences</li>
		        <li>Intégration du formulaire de création des annonces pour le site en ligne</li>
				<li>Modification des notions de préavis et de disponibilité</li>
		    </ul>
		</div>
EOT;
        }
		elseif($i == 2) {
		    $html = <<<EOT
		    <div class='center'>
	            <h2>Partie Commerciale</h2><br />
			    <ul>
			        <li>Nouveau procédé de saisi des rendez-vous</li>
					<li>Ajout d'un onglet "Historique RDV" dans le menu gauche pour consulter les anciens rendez-vous saisis</li>
					<li>Possibilité de recherche des rendez-vous par agence</li>
					<li>Les rendez-vous avec un compte rendu (saisi dans l'AGC désormais) apparaissent en vert, les autres en orange</li>
					<li>Ajout des statuts d'affaire "Opérationnelle" et "Terminée"</li>
					<li>Lors de la saisie d'une affaire, on choisit d'abord le pôle, ensuite le type de contrat</li>
					<li>Réaffectation automatique des affaires existantes selon le nouveau modèle Pôle / Type contrat</li>
					<li>Certains champs sont devenus obligatoires dans la saisie d'une affaire</li>
					<li>Suppression du choix du pôle dans le formulaire d'une affaire puisqu'on le choisi avant</li>
					<li>Ajout d'une partie "Etat Technique" dans le formulaire affaire pour les types de contrat Infogérance destinée aux RT</li>
					<li>L'environnement Technique pour la partie Infogérance a été entièrement revu et sera complété par les RT</li>
					<li>Ajout d'une case à choisir "Centre de service" pour les contrats de type Infogérance</li>
					<li>Ajout d'une lise déroulante "Potentiel de signer" dans la partie "Analyse Commerciale" pour les contrats de type Infogérance</li>
					<li>Remise en forme de l'affichage des compétences dans le formulaire affaire</li>
					<li>Correction des bug d'affichage dans les contrats délégation</li>
					<li>Ajout d'une case à cocher "Renouvellement" dans le contrat délégation</li>
					<li>Possibilité de créer directement le contrat délégation au bout de la ligne d'une ressource associée à une affaire</li>
					<li>Modification de la partie "Proposition Commerciale" pour les contrats de type Infogérance</li>
					<li>Ajout du module "ressources affectées" avec les notions de "titulaire" / "suppléant" et ressource "Inclus dans le forfait" pour les contrats de type Infogérance</li>
					<li>Possibilité de suivre l'historique des propositions commerciales pour les contrats de type Infogérance</li>
					<li>Possibilité d'ajouter les documents relatifs à la proposition commerciale signée pour les contrats de type Infogérance</li>
					<li>Ajout d'un module de suivi de CA pour les affaires signées avec une remontée automatique des informations de CEGID</li>
					<li>Ajout d'un onglet "Candidats EMB" dans le menu de gauche pour afficher seulement les candidats embauchés</li>
					<li>Ajout d'un onglet "Propales Infogérance" dans le menu de gauche afin de suivre les propositions commerciales pour les contrats de type Infogérance</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
	                <li>L'ensemble des modifications demandées par le service RH a été réalisée.</li>
					<li>La première personne qui modifie une candidature de cvweb devient le créateur de la fiche.</li>
					<li>Ajout d'un filtre de recherche par mobilité (Département).</li>
					<li>Le filtre mot clé va rechercher en plus dans les attentes professionnelles et le commentaire de la fiche candidat.</li>
					<li>Tri par ordre d'arrivée croissante et non pas par ordre alphabétique lorsque le filtre est sur "non lu".</li>
			    </ul>
		    </div>
EOT;
		}
		elseif($i == 3) {
		    $html = <<<EOT
		    <div class='center'>
	            <h2>Partie Commerciale</h2><br />
			    <ul>
			        <li>Tous les rendez vous dont la date n'est pas encore passée sont en orange</li>
					<li>Affichage en consultation des données collaborateurs lors de la sélection de ce dernier dans le contrat délégation</li>
					<li>Ajout des Ordres de mission</li>
					<li>Intégration des collaborateurs ALTIQUE dans la liste déroulante des ressources (préfixées par ALC-)</li>
					<li>Ajout de l'information nombre de collaborateurs titulaires en suivi et nombre d'affaires opérationnelles par commercial en page d'accueil</li>
					<li>Ajout de l'information "Vous avez xxx affaires remises et xxx affaires en cours de rédaction non modifiées depuis plus d'1 mois" par commercial en page d'accueil</li>
					<li>Déplacement de l'information "type de facturation : régie / forfaitisé" au niveau de la partie proposition commerciale</li>
					<li>Ajustement du CA prévisionnel en fonction des types de contrat et de la durée d'intervention des collaborateurs pour l'AT</li>
					<li>Ajout des notions de ressources affectées et de ressources complémentaires pour les types de contrats Infogérance / Forfait de service</li>
					<li>Création d'un script mettant à jour automatiquement les dates de début et de fin des affaires AT en fonction de la date la plus ancienne et la plus récente des collaborateurs associés à l'affaire</li>
					<li>Création d'un script mettant à jour automatiquement les statuts des affaires en "opérationnelle" / "terminée" en fonction de la date de fin si leur numéro AGC est dans CEGID</li>
					<li>Création d'un report : Affaires Signée / Opérationnelles / Terminées sans Contrat délégation</li>
					<li>Création d'un report : Affaires Signée / Opérationnelles / Terminées sans CA</li>
					<li>Création d'un report : Affaires Signée / Opérationnelles / Terminées avec Contrats délégation incomplets</li>
					<li>Le chiffre d'affaire ainsi que les dates de début et fin sont obligatoires pour les statuts d'affaire "remise","signée","opérationnelle","terminée"</li>
					<li>Ajout du bouton CPI (Chef de Projet Infogérance) à côté de Titulaire / Suppléant</li>
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
	                <li>Possibilité de consulter / editer les contrats délégation</li>
					<li>Possibilité de créer / consulter / editer les ordres de mission</li>
			    </ul>				
		    </div>
EOT;
		}
		elseif($i == 4) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>Général</h2><br />
				<ul>
					<li>Intégration de la nouvelle charte graphique pour les documents PDF</li>
					<li>Intégration de la société WIZTIVI.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
                    <li>Dans la liste des ressources, une fois que les candidats ont été embauchés, ils apparaissent uniquement dans la liste collaborateur (grise).</li>
                    <li>Il est désormais possible de saisir des actions selon différents critères (Id affaire, Id rendezvous, client, contact…) via l’onglet « Saisir action » dans le menu de droite.</li>
					<li>Un filtre par ressource a été ajouté dans la page des Contrats Délégation</li>
					<li>Correction des modèles de documents édités par le pôle Formation</li>
					<li>Possibilité de géo localiser le client en cliquant sur la ville dans la page « Compte »</li>
			    </ul> 
		    </div>
EOT;
		}
		elseif($i == 5) {
		    $html = <<<EOT
		    <div class='center'>
			    <h2>Général</h2><br />
				<ul>
			        <li>Modification de la gestion des droits d'utilisateur</li>
					<li>Intégration de la nouvelle charte graphique</li>
					<li>Intégration de la société NEEDPROFILE</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Ajout des champs CA et remarque proposition dans le contrat délégation</li>
					<li>Ajout de la notion de demande de changement relatif à un collaborateur</li>
					<li>Ajout de liens vers les rapports AGC nationaux, par commerciaux, par agences, par pôles</li>
					<li>Optimisation de l’affichage des ressources dans la partie proposition commerciale</li>
                    <li>Optimisation des calculs relatifs aux nombres de jours ouvrés, de la marge et du chiffre d’affaire</li>
                    <li>Ajout de la notion de contrat délégation Hors affaire</li>
					<li>Possibilité de dupliquer un contrat délégation directement depuis l’interface d’affichage des ces derniers</li>
					<li>Possibilité d’utiliser le bouton « Demande de recrutement au service RH » en spécifiant l’adresse email du ou des chargé(s) de recrutement concerné(s)</li>
					<li>Possibilité de mettre une durée en demi-journée dans la saisie d’une session de formation</li>
					<li>Affichage des commentaires du planning d’une affaire dans la consultation d’une affaire</li>
			    </ul> 
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Ajout de l'historisation des actions relatives à un candidat</li>
					<li>Ajout de liens vers les rapports AGC des ressources humaines</li>
					<li>Ajout d'un code couleur pour les candidatures non lues provenant de cvweb en fonction de leur département d'habitation</li>
					<li>Ajout du pays de résidence dans la partie embauche du formulaire de saisie d’un candidat (cette Information sera affichée sur le contrat délégation pour les nouveaux collaborateurs)</li>
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
			    <h2>Général</h2><br />
				<ul>
				    <li>Ajout d'un script de vérification de la cohérence des statuts des affaires.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Possibilité de sélectionner un sous-traitant existant dans le contrat délégation au niveau de la ressource</li>
					<li>Ajout de la date de fin prévisionnelle de l'affaire</li>
					<li>Ajout de la date de fin prévisionnelle de la ressource dans la partie proposition commerciale</li>
					<li>Ajout du lien vers le rapport hebdomadaire par commercial</li>
					<li>Ajout du lien vers le rapport Previsionnel CA</li>
					<li>Ajout du lien vers le rapport des affaires arrivant à terme</li>
					<li>Mise en place des envois automatique des rapports aux différents acteurs commerciaux de l'AGC</li>
					<li>Ajout du formulaire de création des bilans d'activité</li>
					<li>Ajout du filtre par mois dans les bilans d'activité</li>
					<li>Ajout de la liste déroulante des agences au niveau des rendez-vous</li>
					<li>Suppression du responsable dans le formulaire de création d'une action</li>
					<li>Ajout de script de mise à jour automatique des informations relatives aux rapports également le midi</li>
					<li>Ajout des affaires de type de contrat Projet Forfaitaire dans l'affichage Propale Infogérance du menu de gauche</li>
					<li>Ajout du CA Probable dans l'échéancier de facturation</li>
					<li>Ajout du calcul automatique du nombre de jours ouvrés dans le contrat délégation</li>
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
			    <h2>Général</h2><br />
				<ul>
				    <li>Mise à jour de l'ergonomie.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Possibilité d'éditer les rendez-vous en pdf</li>
					<li>Mise en place de sélection par défaut dans les contrats délégation</li>
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
					<li>Correction de la récupération de l'agence dans les ODM pour la région parisienne</li>
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
			    <h2>Général</h2><br />
				<ul>
				    <li>Mise à jour de l'ergonomie.</li>
					<li>Ajout d’info bulles d’aide.</li>
					<li>Suppression de l’ensemble des boutons « annuler ».</li>
					<li>Ajout de la rubrique « A venir » dans le menu aide permettant de connaitre les futures évolutions / correctifs de l’AGC.</li>
			    </ul>
				<br /><hr />
	            <h2>Partie Commerciale</h2><br />
			    <ul>
					<li>Ajout d’un champ « Apporteur » dans la fiche affaire qui permettra de faire la distinction entre l’apporteur de l’affaire et le commercial en charge de l’affaire.</li>
					<li>Calcul de marge ajustée dans la partie proposition commerciale pour les ressources en AT.</li>
					<li>L’échéancier de facturation (en bas de la fiche affaire) est désormais strictement identique à celui du rapport « Prévisionnel CA ».</li> 
			    </ul>
	            <br /><hr />
			    <h2>Partie RH</h2><br />
	            <ul>		
					<li>Remplacement du filtre « intitulé » par « profil » pour la partie demande de ressources.</li>
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
			        <h2>Général</h2><br />
				    <ul>
				        <li>Ajout des astuces sur la page d'accueil</li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Suppression du chargement automatique des anomalies à l'accueil</li>
					    <li>Ajout de la liste des ressources par affaire dans l'infobulle de l'interface de listing des affaires</li>
					    <li>Ajout des liens vers les affaires AGC depuis le contrat délégation et l'ordre de mission</li>
					    <li>Ajout du listing des contrats délégation par affaire sur la fiche de consultation d'une affaire</li>
                        <li>Correction du problème de calcul de marge par ressource lorsque les frais journalier n'étaient pas déclarés</li>
					    <li>Ajout de la notion de SDM dans les types de ressources</li>
						<li>Ajout des intérimaires dans la liste déroulante des ressources</li>
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
					    <li>Ajout des numéros d'affaires AGC et CEGID sur les ODM</li>
						<li>Ajout de 3 coches en haut à droite du contrat délégation (Prépa Fact, ODM, CA, Affaire CEGID)</li>
					    <li>Correction du chargement du contrat délégation en PDF (Il n'est plus nécessaire de cliquer sur "Refresh Page" sous internet explorer)</li>
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
			        <h2>Général</h2><br />
				    <ul>
				        <li></li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Possibilité de modifier les historiques de changement de statut d'une affaire</li>
					    <li>Amélioration de la gestion de la suppression des ressources dans le formulaire affaire</li>
					    <li>Affichage de (Matériel) dans l'interface de listing des contrats délégation pour les contrats délégation matériel</li>
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
			        <h2>Général</h2><br />
				    <ul>
				        <li></li>
			        </ul>
				    <br />
	                <h2>Partie Commerciale</h2><br />
			        <ul>
				        <li>Les directeurs d'agence ont désormais les droits en modification sur les affaires de leurs agences</li>
						<li>Ajout de la colonne Pôle dans l'interface de listing des affaires</li>
					    <li>L'expediteur d'un contrat délégation est désormais en copie du mail envoyé</li>
					    <li>Les cliens "à risque" dans CEGID (Feu Orange ou Rouge) sont désormais affichés d'une couleur différente dans la liste déroulante de clients</li>
			        </ul>
	                <br />
				</div>
                <div class="right">				
			        <h2>Partie RH</h2><br />
	                <ul>
					    <li>Demandes - Changement du terme « demande en cours » en « demande active » </li>
						<li>Demandes - Dans la liste des demandes, ajout du bouton CV sur la ligne du candidat (accueil des demandes + création de la demande)</li>
						<li>Candidats - Ajout des spécialités « ERP », « CRM », « support applicatif », « décisionnel », « télécom mobiles » et « télécom ToIP/VoIP»</li>
						<li>Candidats - Ajout du cursus « > à BAC+5 »</li>
						<li>Candidats - Ajout du cursus informatique « non diplômant »</li>
						<li>Candidats - Création d'un filtre sur les « travailleurs handicapés »</li>
						<li>Candidats - Ajouter du statut « embauche sous traitant »</li>
						<li>Candidats - Masquer les agences de rattachement (lynt, elytex, etc.)</li>
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
                        <li>Sur les ODM, la date indiquée sur le PDF "Fait le" est égale à la date d'envoi si elle existe, et à la date du jour sinon.</li>
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
			        <h2>Général</h2><br />
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
					    <li>Ajout du statut de l’affaire dans la partie « demande de ressource ».</li>
						<li>jout d’un filtre pour n’avoir que les candidats embauchés dans la partie candidat.</li>
						<li>Suppression du menu « Candidat EMB »</li>
			        </ul>
	                <br />
			        <h2>Partie Facturation / Relance</h2><br />
	                <ul>		
                        <li>Tous les membres du service facturation peuvent modifier les ordres de mission.</li>
						<li>Ajout de l’intitulé de l’affaires dans le contrat délégation.</li>
						<li>Amélioration de l’affichage des affaires CEGID qui s’affichent dans l’ODM.</li>
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
                        <h2>Général</h2><br />
                        <ul>
                            <li>Amélioration du sytème de mis à jour.</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Mis en place des demandes de changment.</li>
                            <li>Possibilité de rentrer le TJM sur les CD peu importe le type d'affaire</li>
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
                            <li>Ajout d'un critère type de recrutement sur les demandes de recrutement</li>
                            <li>Possibilité de dupliquer une demande de recrutement avec son historique</li>
                            <li>Ajout d'un filtre sur l'historique des demandes dans le listing des demandes de recrutement</li>
                            <li>Ajout d'une nature de candidature "Needprofile"</li>
                            <li>Ajout d'un état de candidat "Transformation CDD-CDI"</li>
                            <li>Les colonnes d'embauches sont maintenant scindées en "Par profil" et "Par mission" dans le rapport hebdo</li>
                            <li>Lors d'un changement de statut sur une demande de recrutement, l'historique est mis à jour directement</li>
                            <li>Le champs "Par profil" et "Par mission" est maintenant obligatoire pour un candidat embauché</li>
                            <li>Les champs "description du poste" et "compétences" doivent maintenant contenir 20 caractères minimum sur une demande de recrutement</li>
                            <li>Ajout des IC dans la liste "réalisé par" des entretiens</li>
                            <li>Dans le rapport hebdo, l'embauche est maintenant comptabilisé au créateur du statut</li>
                            <li>Problème de lien sur les candidats positionnés sur les demandes de recrutement</li>
                            <li>Un candidat vide n'est plus créé à la création d'une demande de recrutement</li>
                            <li>Possibilité de sélectionner plusieurs personnes dans les filtre sur l'historique</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 319) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>...</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Nouveau système de listes liées pour la sélection d'une ressource sur les contrats délégation</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Relance automatique des collaborateurs pour les ODM</li>
                        </ul><br />
                            
                    </div>
                    <div class="right">
                    <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Ajout d'un champ concernant les évolutions prévus dans les contrats délégation</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout des états "Transformation Interim - CDD" et "'Transformation Interim - CDI"</li>
                            <li>Ajouter d'un filtre "prioritaire" sur les demandes de recrutement</li>
                            <li>Sur le récapitulatifs des demandes de recrutement, les demandes prioritaires sont maintenant plus visible</li>
                            <li>Revu de l'affichage des candidats à la création d'une demande de recrutement pour coller à l'édition d'une demande</li>
                            <li>Revu du système de sélection de la mobilité sur les fiches candidats et CVWeb</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 320) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Une grande partie des listes est (enfin) triable en cliquant sur l'entête des colonnes</li>
                            <li>Réorganisation des menus</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Intégration de la pondération (avec historique) sur les affaires</li>
                            <li>Ajout d'un listing des affaires permettant de mettre à jour la pondération de manière directe</li>
                            <li>Ajout d'un filtre par mots clés sur le listing des affaires</li>
                            <li>Ajout de rapports pour les CA pondérés</li>
                            <li>Les directeurs d'agence reçoivent un mail récapitulatif des demandes de recrutement en cours pour leurs agences tous les vendredi</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Ajout d'un compte des ODM sur le rapport des ODM non retournés</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Ajout de la possibilité de valider et intégrer une demande de changement directement sur la demande</li>
                            <li>Ajout d'un filtre par état "valider" et "intégrer" sur le listing des demandes de changement</li>
                            <li>Les dates souhaitées des demandes de changement sont maintenant des champs obligatoires</li>
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
                        <h2>Général</h2><br />
                        <ul>
                            <li>Intégration de Netlevel</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Correction d'un bug d'arrondi des CA prévisionnel</li>
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
                            <li>Le champ agence de rattachement n'as plus de sélection par défaut et est maintenant obligatoire</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 330) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Gestion des cookies multi-sociétés</li>
                            <li>Possibilité d'exporter au format CSV les listes</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Revue du système de gestion des contrats délégation (maintenant lié aux ressources affaires)</li>
                            <li>Mise en place d'un workflow de validation des contrats délégation par l'ADV</li>
                            <li>Revue du workflow des statuts d'une affaire avec incidence sur la pondération</li>
                            <li>Les directeurs de région peuvent maintenant modifier les affaires de leur région</li>
                            <li>Regroupement des agences de Paris en une seule agence</li>
                            <li>Les candidats ayant pour statut "embauche interimaire" ou "embauche sous-traitant" apparaissent maintenant dans les listes des ressources sur une affaire</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Ajout de la validation des contrats délégation avant de pouvoir créer les ODM</li>
                            <li>Mise en place d'un workflow de validation des contrats délégation par l'ADV</li>
                        </ul><br />
                    
                        <h2>Partie Service Paie</h2><br />
	                <ul>
	                    <li>Revue de l'affichage des demandes de changement</li>
                            <li>Ajout du service contrôle de gestion dans le workflow des demandes de changement</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout des états "Transformation ST - CDI" et "Transformation ST - CDD" pour les candidats</li>
                            <li>Ajout de champs obligatoires pour la saisie d'un candidat embauché (civilité, nom de jeune fille, date fin cdd et travailleur handiapé)</li>
                            <li>Ajout de vérification sur le numéro de sécurité sociale (sexe, année naissance, mois naissance, département et clé de vérification)</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 340) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Intégration de Salesforce</li>
                            <li>Implementation du mécanisme de SSO</li>
                        </ul>
                    </div>
EOT;
		}
                elseif($i == 341) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la redirection après une authentification</li>
                            <li>Réorganisation des groupes pour les droits</li>
                            <li>Correction de bug mineur suite à la version 3.4.0</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Possibilité de dupliquer un contrat délégation sur une affaire différente</li>
                            <li>Utilisation d'une référence affaire plus simple</li>
                            <li>Le filtre par affaire dans les contrats délégation affiche maintenant les contrats délégation des affaires SFC et AGC</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout de la possibilité de refuser une demande de changement (envoi un mail au créateur de de la demande)</li>
                            <li>Correction d'un bug sur le filtre par affaire des demandes de recrutement</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 342) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la déconnexion</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Gestion des droits contrats délégation revu pour plus de rapidité à l'affichage</li>
                            <li>Utilisation d'une référence affaire plus simple</li>
                        </ul><br />
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Retour sur l'ancien mode listing pour le suivi candidats pour plus de rapidité (plus possible de trier)</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 343) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Correction d'un bug lors de la déconnexion</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Ajout du menu de création d'un contrat délégation avec sélection de l'opportunité SFC</li>
                            <li>Ajout de l'intitulé  d'opportunités SFC sur le listing CD</li>
                            <li>Modification du script d'export vers Idea pour prendre en comptes les opportunités SFC</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <h2>Partie Facturation / Relance</h2><br />
	                <ul>
                            <li>Le numéro d'affaire Cegid doit s'afficher de nouveau sur les CD</li>
                        </ul><br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Ajout d'un groupe CRH</li>
                            <li>A l'embauche d'un candidat, possibilité de saisir les dates sous la forme xx-xx-xxxx ou xx/xx/xxxx</li>
                            <li>Envoi d'un mail aux RRH en cas d'embauche d'un candidat handicapé</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 344) {
		    $html = <<<EOT
   <div class="left">
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Remontée de la description depuis l'opportunité SFC dans les tâches des contrats délégation</li>
                        </ul><br />
                        <h2>Partie Facturation / Relance</h2><br />
                        <ul>
                            <li>Possibilité de définir plusieurs destinataires pour les mails envoyés lors de la gestion des CD</li>
                            <li>N'apparaissent dans les listes des anciens salariés que les personnes dont la date de sortie est effectivement passé</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Séparation du type de contrat sur les annonces</li>
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
                            <li>Lors de la duplication d'un contrat délégation, vous n'avez plus la possibilité de changer de compte (pour cela, il faut en créer un nouveau) et ne pouvez sélectionner que les opportunités liées</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Lors de la sélection de la mobilité d'un candidat, apparaissent maintenant en gras les régions ayant des départements sélectionnés</li>
                            <li>Possibilité de dupliquer une demande de ressource en reprenant l'historique du statut, l'historique des candidats ou les deux</li>
                            <li>La localisation est maintenant obligatoire dans les annonces</li>
                            <li>Ajout d'un domaine pour les annonces (utile pour la recherche dans CVWeb</li>
                            <li>Ajout d'un lien vers l'ensemble des rapports</li>
                            <li>La saisie de l'expérience informatique est maintenant non obligatoire si "Fonctions Hors Informatique" est sélectionné</li>
                            <li>Pour un état de candidature validé, la sélection sur profil est par défaut</li>
                            <li>Ajout d'un type de contrat "Intérimaire" sur les informations d'embauche</li>
                            <li>En cas de champs modifiés sur une fiche candidat, une confirmation vous sera demandé avant de quitter la page</li>
                            <li>Pour les demandes de ressource en veille, le client n'est plus obligatoire</li>
                            <li>Ajout d'un filtre sur l'identifiant des demandes ressource</li>
                        </ul>                       
                    </div>
EOT;
		}
                elseif($i == 346) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Simplification du formulaire des demandes de changement (moins de champs à remplir)</li>
                            <li>Les demandes de changement staff sont maintenant envoyées à Franck et Eliane</li>
                            <li>En cas de sélection d'un service CDS dans les demandes de changement, obligation de remplir le champ CDS</li>
                            <li>Correction d'un bug permettant la création de demande de changement avec des valeurs identiques</li>
                        </ul><br />
	                <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Les relances des ODM sont maintenant envoyées aux responsables saisies sur le contrat délégation plutôt qu'au DA</li>
                            <li>Apparait maintenant sur les contrats délégation le champ "type" (New Business, Renouvellement) issu de Salesforce</li>
                        </ul><br />
                    </div>
                    <div class="right">
                    <br />
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Lors de la synchronisation des candidats entre l'AGC et CVWeb, les informations modifiées par le candidat mettent à jour les informations de l'AGC si non modifiées par un CR</li>
                            <li>Adaptation des formulaires candidat sur la base Ovialis</li>
                        </ul>                       
                    </div>
EOT;
		}elseif($i == 347) {
		    $html = <<<EOT
   <div class="left">
                        <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Intégration du service formation avec Salesforce</li>
                        </ul><br />
                    </div>
EOT;
		}elseif($i == 350) {
		    $html = <<<EOT
   <div>
                        <h2>Général</h2><br />
                        <ul>
                            <li>Mise à jour de la charte graphique</li>
                        </ul><br />
                    </div>
EOT;
                }elseif($i == 351) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2><br />
                        <ul>
                            <li>Mise à la charte des documents générés par l'AGC (CD, ODM, demandes ressource, documents formation...)</li>
                        </ul><br /><br />
                        <h2>Partie Commerciale</h2><br />
                        <ul>
                            <li>Ajout du champ "Apporteur d'affaire" issu de Salesforce sur les contrats délégation</li>
                        </ul>
                        </div>
                        <div class="right">
                        <h2>Partie RH</h2><br />
	                <ul>
                            <li>Remplacement du domaine dans les annonces par le métier, mise en corrélation avec le nouveau site internet</li>
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
                            <li>Correction du bug empêchant la suppression d'une annonce</li>
                            <li>La table associant un CR à son adresse mail agence est maintenant mise à jour automatiquement (mail reçu en cas de postulation sur CVWeb)</li>
                            <li>Correction d'un bug empêchant de consulter les offres auxquelles un candidat a postulé</li>
                        </ul>
                        <br />
                        <h2>Partie Commerciale</h2>
                        <br />
                        <ul>
                            <li>Possibilité d'effectuer des demandes de changement pour du personnel de structure</li>
                        </ul>
                        <br />
                    </div>
                    <div class="right">
                        <h2>Partie Service Paie</h2>
                        <br />
                        <ul>
                            <li>Les valideurs d'une demande de changement peuvent maintenant ré-ouvrir une demande si celle-ci a été refusé</li>
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
                            <li>Possibilité de créer un nouveau métier pour les annonces par les CRH</li>
                        </ul>
                        <br />

                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Ajout de la sélection d'une raison en cas de refus d'un contrat délégation</li>
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
                            <li>Intégration du nouvel accord de gestion des notes de frais sur les CD</li>
                        </ul>
                        <br />
                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>Le valideur reçoit en copie le mail de refus lors d'un retour de CD</li>
                            <li>Correction d'un bug lors d'un refus de CD avec caractère spéciaux</li>
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
                            <li>Saisie des horaires sur les CD modifié</li>
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
                            <li>Intégration de Taleo dans les contrats délégation</li>
                        </ul>
                        <br />
                    </div>
EOT;
                }elseif($i == 360) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2>
                        <br />
                        <ul>
                            <li>Intégration des sociétés ARCHITECH et TIMARANCE</li>
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
                            <li>Possibilité d'indiquer la raison en cas de ré-ouverture d'un contrat délégation</li>
                        </ul>
                        <br />
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Possibilité d'indiquer le salaire lors d'un contrat délégation pour le staff</li>
                        </ul>
                    </div>
EOT;
                }elseif($i == 361) {
		    $html = <<<EOT
                    <div class="left">
                        <h2>Général</h2>
                        <br />
                        <ul>
                            <li>Correction de divers bugs mineurs suite à la migration</li>
                        </ul>
                    </div>
                    <div class="right">
                        <h2>Partie Administration des ventes</h2>
                        <br />
                        <ul>
                            <li>L'ADV est maintenant en copie en cas de ré-ouverture d'un contrat délégation</li>
                        </ul>
                        <br />
                        <h2>Partie RH</h2>
                        <br />
                        <ul>
                            <li>Ajout dans Taleo des  motifs de CDD, repris sur les contrats délégation</li>
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
	  * Affichage des évolutions de l'application à venir
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