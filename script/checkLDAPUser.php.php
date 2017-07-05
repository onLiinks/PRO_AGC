<?php
/**
  * Fichier checkZimbraUser.php
  *
  * @author Yannick BETOU
  * @copyright Proservia
  * @package ScriptCron
  */

/**
  * Inclusion de fichiers
  */
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

Script::checkLDAPUser();

?>