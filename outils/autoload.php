<?php

/**
 * Fichier autoload.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGE
 */

/**
 * Chargement automatique des classes
 *
 * @param string Nom de la classe à charger
 */
function AGC_autoload_lib($nom) {
    if (file_exists('../libraries/fpdf/class.' . strtolower($nom) . '.php')) {
        require_once('fpdf/class.' . strtolower($nom) . '.php');
    }
}

function AGC_autoload($nom) {
    $nom = str_replace("_", "/", $nom) . ".php";
    require_once($nom);
}

require_once('/var/simplesamlphp/lib/_autoload.php');
require_once('../libraries/Pager_Wrapper.php');
spl_autoload_register('AGC_autoload_lib');
spl_autoload_register('AGC_autoload');