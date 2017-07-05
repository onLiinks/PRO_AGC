<?php

require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;
$dr = new DemandeRessource(0, array());

$db = connecter();
$groupe_ad = Utilisateur::getGroupAdList('COM');
$groupe_ad2 = Utilisateur::getGroupAdList('OP');
$groupe_ad3 = Utilisateur::getGroupAdList('ADI');
$demandes = Array();
$result = $db->query('SELECT Id_utilisateur, groupe_ad FROM utilisateur ORDER BY nom');
while ($ligne = $result->fetchRow()) {
    $tab = array_intersect(explode(',', $ligne->groupe_ad), $groupe_ad);
    $tab2 = array_intersect(explode(',', $ligne->groupe_ad), $groupe_ad2);
    $tab3 = array_intersect(explode(',', $ligne->groupe_ad), $groupe_ad3);
    if (!empty($tab) || !empty($tab2) || !empty($tab3)) {
        $utilisateur = new Utilisateur(strtolower($ligne->id_utilisateur), array());
        $result2 = $db->query('SELECT Id_demande_ressource FROM demande_ressource WHERE archive = 0 AND statut = 1 AND Id_ic LIKE "' . mysql_real_escape_string(strtolower($ligne->id_utilisateur)) . '"');
        $de = 0;
        $demandes = array();
        while ($ligne2 = $result2->FetchRow()) {
            $de = 1;
            array_push($demandes, $ligne2->id_demande_ressource);
        }
        if ($de == 1) {
            DemandeRessource::edit(serialize($demandes), $ligne->id_utilisateur);

            $hdrs = array(
                'From' => MAIL_CONTACT,
                'Subject' => 'Récapitulatif de vos demandes de recrutement',
                'To' => strtolower($ligne->id_utilisateur) . '@proservia.fr'
            );
            $text = <<<EOT
Bonjour {$utilisateur->prenom},
				
Voici le récapitulatif de vos demandes de recrutement en cours.
EOT;
            $crlf = "\n";
            $file = strtolower($ligne->id_utilisateur) . '.pdf';

            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $mime->addAttachment($file, 'application/pdf');

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send(strtolower($ligne->id_utilisateur) . '@proservia.fr', $hdrs, $body);
            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
            unlink($file);
        }
    }
}


$demandesAgence = Array();
$result = $db->query('SELECT Id_demande_ressource, agence FROM demande_ressource WHERE archive = 0 AND statut = 1 AND agence != ""');
while ($ligne = $result->fetchRow()) {
    if(empty($demandesAgence[$ligne->agence])) {
        $demandesAgence[$ligne->agence] = Array();
    }
    array_push($demandesAgence[$ligne->agence], $ligne->id_demande_ressource);
    
}

foreach ($demandesAgence as $agence => $com) {
    $ag = new Agence($agence, Array());
    $ag = $ag->libelle;
    DemandeRessource::edit(serialize($com), $ag);
    
    $idDirecteur = Utilisateur::getAgencyDirector($agence);
    $directeur = new Utilisateur($idDirecteur, Array());
    $hdrs = array(
        'From' => MAIL_CONTACT,
        'Subject' => 'Récapitulatif des demandes de recrutement ' . $ag,
        'To' => $idDirecteur . '@proservia.fr'
    );
    $text = <<<EOT
Bonjour {$directeur->prenom},

Voici le récapitulatif des demandes de recrutement en cours pour l'agence {$ag}.
EOT;
    $crlf = "\n";

    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($text);
    $mime->addAttachment($ag . '.pdf', 'application/pdf');

    $body = $mime->get();
    $hdrs = $mime->headers($hdrs);

    // Create the mail object using the Mail::factory method
    $params['host'] = SMTP_HOST;
    $params['port'] = SMTP_PORT;
    $mail_object = Mail::factory('smtp', $params);

    $send = $mail_object->send(strtolower($idDirecteur) . '@proservia.fr', $hdrs, $body);
    if (PEAR::isError($send)) {
            print($send->getMessage());
    }
    unlink($ag . '.pdf');
}

?>