<?php
/**
  * Fichier changementMission.php
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

$contenu = Script::missionChangePerResource();

require_once DEFAULT_HTML;

?>