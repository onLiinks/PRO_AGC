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
  * Réinitialisation de la session
  */
session_start();

/**
  * Initialisation de variables
  */
$titre         = '';
$filtre        = '';
$contenu       = '';
$menuDroit     = MENU_DROIT;
$menuGauche    = MENU_GAUCHE;

/**
  * Initialisation de variables dépendant de la zone 
  */
$squelette = SOURCE_HTML;
	
	/**
	  * Fabrication de la page à envoyer au client
	  */
    switch($_GET['a']) {
		case 'migration_prosper': 
		    $titre      = 'MIGRATION DE PROSPER'; 
		    $migration  = new Migration();
	        break;
	}

/**
  * Inclusion du HTML
  */
require_once $squelette;

?>