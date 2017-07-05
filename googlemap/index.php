<?php
/**
  * Fichier index.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Inclusion de fichiers
  */
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

/**
  * Fabrication de la page � envoyer au client
  */
switch($_GET['a']) {

	case 'consulterMap':

		#http://code.google.com/intl/fr/apis/maps/signup.html Pour g�n�rer la cl� correspondante au serveur
		$api = new NXGoogleMapsAPI(); // Cr�ation d'une instance de la classe
		$api->setWidth(800); // D�finition de la largeur de la carte
		$api->setHeight(600); // D�finition de la hauteur de la carte
		$api->setZoomFactor(14); // D�finition du zoom
		//$api->setZoomLevel(10);
		$api->addControl(GLargeMapControl); // Ajout des contr�leurs
		$api->addControl(GMapTypeControl); // Ajout des types de vue
		$api->addControl(GOverviewMapControl); // Ajout de la carte miniature

		//$api->addAddress("49 avenue des champs �lys�es Paris", "Haagen-dazs Paris", true);
		$api->addAddress($_GET['b'], $_GET['b'], true);
		?>
		<html>
		<head> <?php echo $api->getHeadCode(); ?> </head>
		<body onLoad=" <?php echo $api->getOnLoadCode();?> "> <?php echo $api->getBodyCode(); ?> 
		</body>
		</html>
		<?php
		break;
}