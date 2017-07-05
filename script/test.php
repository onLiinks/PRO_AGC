<?php
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

//echo '0';
error_reporting(E_ALL);
ini_set("display_errors", 1);

//sendNewEmployeeMessage('Yannick BETOU', 'yannick.betou@proservia.fr', 'Hervé', 'B');
//echo '1';

Script::synchroTaleo();
var_dump($candidates);


//var_dump(ContratDelegation->cdExistsWithRessourceOnPeriod('0000003273', '2014-04-22', '2016-04-22');

?>
