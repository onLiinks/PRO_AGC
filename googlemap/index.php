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
  * Fabrication de la page à envoyer au client
  */
switch($_GET['a']) {

	case 'consulterMap':

		#http://code.google.com/intl/fr/apis/maps/signup.html Pour générer la clé correspondante au serveur
		$api = new NXGoogleMapsAPI(); // Création d'une instance de la classe
		$api->setWidth(800); // Définition de la largeur de la carte
		$api->setHeight(600); // Définition de la hauteur de la carte
		$api->setZoomFactor(14); // Définition du zoom
		//$api->setZoomLevel(10);
		$api->addControl(GLargeMapControl); // Ajout des contrôleurs
		$api->addControl(GMapTypeControl); // Ajout des types de vue
		$api->addControl(GOverviewMapControl); // Ajout de la carte miniature

		//$api->addAddress("49 avenue des champs élysées Paris", "Haagen-dazs Paris", true);
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