<?php
/**
  * Fichier RightrjoJob.php
  *
  * @author Yannick BETOU
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Inclusion de fichiers
  */
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

// Proservia
Script::rightrjoJob('13729', 'JB-10000', 'http://www.proserviarecrute.fr/');

// Experis
Script::rightrjoJob('12233', 'CWS-9001', 'http://www.experis-it.fr/nousrejoindre');

?>