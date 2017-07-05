<?php
/**
 * Fichier menuDroit.html.php
 *
 * Menu de droite
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */
?>
<h2><?php echo CREATION; ?></h2><br />
<ul class='menu'>

    <?php
    echo GestionMenu::afficherMenu('d');

    if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2))) {
        echo '
		    <br /><hr /><h2>' . SYNTHESE . '</h2><br />
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+Bilan+Hebdo%2fBilan+Hebdo&rs:Command=Render&Commercial=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">Rapport Hebdo</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+COM%2fCAPonderes&rs:Command=Render&Societe=' . $_SESSION['societe'] . '&Commercial=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">CA pondérés</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+COM%2fPonderationDateADate&rs:Command=Render&Societe=' . $_SESSION['societe'] . '&Commercial=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">Pondération date à date</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+COM%2fAffairesArrivantATerme&rs:Command=Render&Societe=' . $_SESSION['societe'] . '&Commercial=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">Affaires arrivant à terme</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+COM%2fAffairesSignees&rs:Command=Render&Societe=' . $_SESSION['societe'] . '&Commercial=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">Affaires signées</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fSalaire+du+marche&rs:Command=Render\')">Sal. Marché</a></li>
		    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fSalaire+du+marche+Graphe&rs:Command=Render\')">Sal. Marché Graphe</a></li>';
    }
    if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 15, 16, 17, 22))) {
        echo '
                    <br /><hr /><h2>' . SYNTHESE . '</h2><br />
		    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_REPORT . '%2fAGC+RH&ViewMode=List\')">Tous les rapports</a></li>
		    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fOrigine+Candidature&rs:Command=Render\')">Par Origine</a></li>
		    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fSalaire+du+marche&rs:Command=Render\')">Sal. Marché</a></li>
		    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fSalaire+du+marche+Graphe&rs:Command=Render\')">Sal. Marché Graphe</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fProfil+CR&rs:Command=Render\')">Profil par CR</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fProfil+Agence&rs:Command=Render\')">Profil par Agence</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fActivite+hebdo+CR&rs:Command=Render\')">Stats Hebdo CR</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fDemande+de+recrutement+Agence&rs:Command=Render\')">Demande Recrutement Agence</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fDemande+de+recrutement+CR&rs:Command=Render\')">Demande Recrutement CR</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+RH%2fDemande+de+recrutement+IC\')">Demande Recrutement IC</a></li>
                    <br /><hr /><br />
                    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/verifDoublonCandidature.php\')">Vérif doublon</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/verifFichierCandidature.php\')">Vérif fichier</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/verifEtatCandidature.php\')">Vérif état</a></li>
                    ';
    }


    if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 15, 16, 22, 18, 19))) {
        echo '
                    <br /><hr /><h2>' . SYNTHESE . '</h2><br />
                    <li><a href="javascript:void(0)" onclick="window.open(\'' . URL_LINK_REPORT . '?%2fAGC%2fAGC+ADV%2fOrdreMission&rs:Command=Render&rc:toolbar=true&Createur=' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '\')">ODM</a></li>';
    }
    if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 18, 19))) {
        echo '
                    <br /><hr /><br />
		    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/changementMission.php\')">Chgt Mission</a></li>
			';
    }
    if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
        echo '
                    <br /><hr /><br />
                    <li><a href="https://srvintra.proservia.lan/AGC//membre/index.php?a=exportCD">Export CD</a></li>
		    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/verifStatutAffaire.php\')">Vérif Statut Aff</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/script/generalCheck.php\')">Vérif générale</a></li>
                    <li><a href="https://srvintra.proservia.lan/AGC/script/exportOSC.php">Export OSC</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open(\'https://srvintra.proservia.lan/AGC/membre/index.php?a=consulterAnomalie\')">Anomalies</a></li>
                    ';
    }
    ?>
</ul>