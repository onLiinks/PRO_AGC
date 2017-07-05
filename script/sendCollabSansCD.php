<?php
/**
  * Fichier sendCollabSansCD.php
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

$_SESSION['cegid_databases'] = array('PROSERVIA');

$csvProservia = ContratDelegation::getCollabWithoutContratDeleg(null, null, '0', 'PROSERVIA', 0, 1, array('type' => 'CSVMAIL'));
$csvPws = ContratDelegation::getCollabWithoutContratDeleg(null, null, '0', 'PWS', 0, 1, array('type' => 'CSVMAIL'));
$csvPds = ContratDelegation::getCollabWithoutContratDeleg(null, null, '0', 'PDS', 0, 1, array('type' => 'CSVMAIL'));
$csvFinatel = ContratDelegation::getCollabWithoutContratDeleg(null, null, '0', 'FINATEL', 0, 1, array('type' => 'CSVMAIL'));

$to = 'yannick.betou@proservia.fr';
$hdrs = array(
	'From' => 'noreply@proservia.fr',
	'Subject' => 'Rapport Collab sans CD',
	'To' => $to,
);
$crlf = "\n";

$numPws = substr_count($csvPws, PHP_EOL) -1;
$numPds = substr_count($csvPds, PHP_EOL) -1;
$numFinatel = substr_count($csvFinatel, PHP_EOL) -1;
$numProservia = substr_count($csvProservia, PHP_EOL) -1;

$mime = new Mail_mime($crlf);
$message = 'Bonjour,<br>Voici les collaborateurs sans contrat delegation au '.date('d-m-Y').'.<br />'
		  . '<ul>'
		. '<li>Old PWS : '.strval($numPws).'</li>'
		. '<li>Old PDS : '.strval($numPds).'</li>'
		. '<li>Old Finatel : '.strval($numFinatel).'</li>'
		. '<li>Proservia : '.strval($numProservia).'</li>'
		. '<li>Total : '.strval($numPws+$numPds+$numFinatel+$numProservia).'</li>'
		. '</ul><br />'
		. 'AGC';
$mime->setHTMLBody($message);

$mime->addAttachment($csvPws, 'text/x-csv', 'collab_sans_cd_pws.csv', false, 'utf-8');
$mime->addAttachment($csvPds, 'text/x-csv', 'collab_sans_cd_pds.csv', false, 'utf-8');
$mime->addAttachment($csvFinatel, 'text/x-csv', 'collab_sans_cd_finatel.csv', false, 'utf-8');
$mime->addAttachment($csvProservia, 'text/x-csv', 'collab_sans_cd_proservia.csv', false, 'utf-8');

$body = $mime->get();
$hdrs = $mime->headers($hdrs);

// Create the mail object using the Mail::factory method
$params['host'] = SMTP_HOST;
$params['port'] = SMTP_PORT;
$mail_object = Mail::factory('smtp', $params);

$send = $mail_object->send(array($to), $hdrs, $body);

?>
