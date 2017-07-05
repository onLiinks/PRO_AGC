<?php

require_once('../config/connect.php');
require_once('../config/config.php');
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

/* TRANSFERT  DES TABLES */

$dumpAGC = 'dumpAGC.sql';
$dumpCVWeb = 'dumpCVWeb.sql';
$remote_file = '/tmp/';
$local_file = '/tmp/';
$tablesAGC = 'annonce certification categorie_certification categorie_competence createur_mail_proservia dpt_mail_proservia mail_proservia competence cursus exp_info langue nationalite niveau_competence niveau_langue preavis profil metier categorie_metier region ville zone type_contrat';
$tablesCVWeb = 'candidat_annonce';

// Création du dump de la base de donnée
$error = exec('mysqldump -u agcuser --password="@GCOutiLCommercialE" ' . DB_PREFIX . BASE . ' ' . $tablesAGC . ' > ' . $local_file . $dumpAGC);

// Connexion en SSH au serveur Systonic
$connexion = ssh2_connect(CVWEB_SERVEUR, CVWEB_PORTSSH) or die('Connexion SSH impossible.');
if (ssh2_auth_pubkey_file($connexion, 'apache', '/var/www/.ssh/id_rsa.pub', '/var/www/.ssh/id_rsa')) {
    // Transfert du fichier dump
    if (!ssh2_scp_send($connexion, $local_file . $dumpAGC, $remote_file . $dumpAGC)) {
        echo 'Impossible de copier le fichier';
    } else {
        // Import du dump
        if (ssh2_exec($connexion, 'mysql -u ' . CVWEB_USER . ' --password="' . CVWEB_PASSWD . '" ' . CVWEB_BASE . ' < ' . $remote_file . $dumpAGC)) {
            // Suppression des dumps sur les 2 serveurs.
            ssh2_exec($connexion, 'rm -rf ' . $remote_file . $dumpAGC);
            exec('rm -rf ' . $local_file . $dumpAGC);
            if (ssh2_exec($connexion, 'mysqldump -u ' . CVWEB_USER . ' --password="' . CVWEB_PASSWD . '" ' . CVWEB_BASE . ' ' . $tablesCVWeb . ' > ' . $remote_file . $dumpCVWeb)) {
                if (ssh2_scp_recv($connexion, $remote_file . $dumpCVWeb, $local_file . $dumpCVWeb)) {
                    // Suppression des dumps sur les 2 serveurs.
                    $error = exec('mysql -u agcuser --password="@GCOutiLCommercialE" ' . DB_PREFIX . BASE . ' < ' . $local_file . $dumpCVWeb);
                    ssh2_exec($connexion, 'rm -rf ' . $remote_file . $dumpCVWeb);
                    exec('rm -rf ' . $local_file . $dumpCVWeb);
                }
            }
        }
    }
} else
    echo 'Authentification impossible.';


/* INSERTION DANS LA BASE AGC */

define('DATETIME', date('Y-m-d H:i:s'));
define('DATE', date('Y-m-d'));

// Connexion à la base de donnée de CVWeb
$mysqliCVWeb = new mysqli(CVWEB_SERVEUR, CVWEB_USER, CVWEB_PASSWD, CVWEB_BASE);
// Connexion à la base de donnée de l'AGC
$mysqliAGC = new mysqli(SERVEUR, USER, PASSWD, DB_PREFIX . BASE);
$db = connecter();

if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

$res = $mysqliAGC->query('SELECT Id_cvweb FROM candidature where Id_cvweb !=0');
$les_id_cvweb = array();
while ($result = $res->fetch_assoc()) {
    array_push($les_id_cvweb, $result['Id_cvweb']);
}

$res = $mysqliCVWeb->query('SELECT * FROM candidat WHERE temp=0 and archive=0');

$res5 = $mysqliAGC->query('DELETE FROM modif_candidat');

while ($result = $res->fetch_assoc()) {
    if (!in_array($result['Id_candidat'], $les_id_cvweb)) {//Cas ou il s'agit d'un nouveau candidat jamais encore saisi dans l'AGC
        $requete = "INSERT INTO ressource SET 
			origine='Candidat', 
			nom='" . $result['nom'] . "', 
			nom_jeune_fille='" . $result['nom_jeune_fille'] . "', 
			prenom='" . $result['prenom'] . "', 
			adresse='" . $result['adresse'] . "', 
			code_postal='" . $result['code_postal'] . "', 
			ville='" . $result['ville'] . "', 
			tel_fixe='" . $result['tel_fixe'] . "', 
			tel_portable='" . $result['tel_portable'] . "', 
			mail='" . $result['mail'] . "',
			date_naissance='" . $result['date_naissance'] . "', 
			ville_naissance='" . $result['ville_naissance'] . "', 
			Id_nationalite='" . $result['Id_nationalite'] . "', 
			Id_dpt_naiss='" . $result['Id_dpt_naiss'] . "', 
			Id_pays_residence='" . $result['Id_pays_residence'] . "', 
			Id_cursus='" . $result['Id_cursus'] . "', 
			civilite='" . $result['civilite'] . "',	
			Id_exp_info='" . $result['Id_exp_info'] . "',
			Id_profil='" . $result['Id_profil'] . "'";

        $mysqliAGC->query($requete);

        $Id_ressource = $mysqliAGC->insert_id;
        $requete = "INSERT INTO candidature SET
			Id_ressource='" . $Id_ressource . "', 
			date='" . $result['date_creation'] . "',
                        Id_nature ='7',
			Id_etat='1', 
			createur='cvweb',
			archive='0', 
			lien_cv='" . $result['lien_cv'] . "', 
			lien_cvp='" . $result['lien_cvp'] . "', 
			lien_lm='" . $result['lien_lm'] . "', 
			type_validation='',
			Id_cvweb='" . $result['Id_candidat'] . "'";

        $mysqliAGC->query($requete);

        $Id_candidature = $mysqliAGC->insert_id;
        $requete = "INSERT INTO entretien SET 
			Id_candidature='" . $Id_candidature . "', 
			Id_preavis='" . $result['Id_preavis'] . "',
			pretention_basse='" . $result['pretention_basse'] . "',
			pretention_haute='" . $result['pretention_haute'] . "',
			createur='cvweb',
			date_creation='" . $result['date_modification'] . "',
			date_disponibilite='" . $result['date_disponibilite'] . "', 
			preavis_negociable='" . $result['preavis_negociable'] . "', 
			tarif_journalier='" . $result['tarif_journalier'] . "'";

        $mysqliAGC->query($requete);
        $Id_entretien = $mysqliAGC->insert_id;

        $res2 = $mysqliCVWeb->query('SELECT * FROM candidat_certification WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO entretien_certification SET 
				Id_entretien='" . $Id_entretien . "', 
				Id_certification='" . $result2['Id_certification'] . "'");
        }

        $res2 = $mysqliCVWeb->query('SELECT * FROM candidat_competence WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO entretien_competence SET 
				Id_entretien='" . $Id_entretien . "', 
				Id_competence='" . $result2['Id_competence'] . "',
				Id_niveau_competence='" . $result2['Id_niveau_competence'] . "'");
        }

        $res2 = $mysqliCVWeb->query('SELECT * FROM candidat_langue WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO entretien_langue SET 
				Id_entretien='" . $Id_entretien . "', 
				Id_langue='" . $result2['Id_langue'] . "',
				Id_niveau_langue='" . $result2['Id_niveau_langue'] . "'");
        }

        $res2 = $mysqliCVWeb->query('SELECT * FROM candidat_mobilite WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO entretien_mobilite SET 
				Id_entretien='" . $Id_entretien . "', 
				Id_mobilite='" . $result2['Id_mobilite'] . "'");
        }

        $res2 = $mysqliCVWeb->query('SELECT * FROM candidat_typecontrat WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO candidat_typecontrat SET 
				Id_candidat='" . $Id_candidature . "', 
				Id_type_contrat='" . $result2['Id_type_contrat'] . "'");
        }
    } else {
        $res3 = $mysqliAGC->query('SELECT Id_candidature FROM candidature WHERE Id_cvweb = ' . $result['Id_candidat']);
        $result3 = $res3->fetch_assoc();
        $res2 = $mysqliCVWeb->query('SELECT * FROM historique WHERE Id_candidat = ' . $result['Id_candidat']);
        while ($result2 = $res2->fetch_assoc()) {
            $mysqliAGC->query("INSERT INTO modif_candidat SET 
				Id_candidature='" . $result3['Id_candidature'] . "', 
				date='" . $result2['date'] . "', 
				remarque='" . mysql_real_escape_string($result2['remarque']) . "'");

            $c = new Candidature($result3['Id_candidature'], null);
            $e = new Entretien($c->Id_entretien, null);
            $r = new Ressource($c->Id_ressource, null);
            $dom = new DOMDocument;
            $dom->loadHTML($result2['remarque']);
            $tr = $dom->getElementsByTagName('tr');

            for ($i = 0; $i < $tr->length; $i++) {
                $td = $tr->item($i)->childNodes;
                $doc2 = new DOMDocument();
                $doc2->appendChild($doc2->importNode($td->item(1), true));
                $html = $doc2->saveHTML();
                $ancien = htmlenperso_decode(utf8_decode(str_replace(array('<td>', '</td>', '\r\n', '\r', '\n'), "", mysql_real_escape_string($html))));
                $doc2 = new DOMDocument();
                $doc2->appendChild($doc2->importNode($td->item(2), true));
                $html = $doc2->saveHTML();
                $nouveau = htmlenperso_decode(utf8_decode(str_replace(array('<td>', '</td>', '\r\n', '\r', '\n'), "", mysql_real_escape_string($html))));
                switch (utf8_decode($td->item(0)->nodeValue)) {
                    /* Cas Simple */
                    case 'Civilité' :
                        if ($r->civilite == $ancien) {
                            $r->civilite = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Nom' :
                        if ($r->nom == $ancien) {
                            $r->nom = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Nom de jeune fille' :
                        if ($r->nom_jeune_fille == $ancien) {
                            $r->nom_jeune_fille = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Prénom' :
                        if ($r->prenom == $ancien) {
                            $r->prenom = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Adresse' :
                        if ($r->adresse == $ancien) {
                            $r->adresse = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Code postal' :
                        if ($r->code_postal == $ancien) {
                            $r->code_postal = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Ville' :
                        if ($r->ville == $ancien) {
                            $r->ville = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Téléphone fixe' :
                        if ($r->tel_fixe == $ancien) {
                            $r->tel_fixe = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Téléphone portable' :
                        if ($r->tel_portable == $ancien) {
                            $r->tel_portable = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Adresse électronique' :
                        if ($r->mail == $ancien) {
                            $r->mail = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Date de naissance' :
                        if ($r->date_naissance == DateMysqltoFr($ancien, 'mysql')) {
                            $r->date_naissance = DateMysqltoFr($nouveau, 'mysql');
                            $r->save();
                        }
                        break;
                    case 'Ville de naissance' :
                        if ($r->ville_naissance == $ancien) {
                            $r->ville_naissance = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Date de disponibilité' :
                        if ($e->date_disponibilite == DateMysqltoFr($ancien, 'mysql')) {
                            $e->date_disponibilite = DateMysqltoFr($nouveau, 'mysql');
                            $e->save();
                        }
                        break;
                    case 'Prétention salariale haute' :
                        if ($e->pretention_haute == $ancien) {
                            $e->pretention_haute = $nouveau;
                            $e->save();
                        }
                        break;
                    case 'Prétention salariale basse' :
                        if ($e->pretention_basse == $ancien) {
                            $e->pretention_basse = $nouveau;
                            $e->save();
                        }
                        break;
                    case 'Tarif journalier' :
                        if ($e->tarif_journalier == $ancien) {
                            $e->tarif_journalier = $nouveau;
                            $e->save();
                        }
                        break;

                    /* Cas Complexe */
                    case 'Nationalité' :
                        $ancien = $db->query('SELECT Id_nationalite FROM nationalite WHERE libelle = "' . $ancien . '"')->fetchRow()->id_nationalite;
                        if ($r->Id_nationalite == $ancien || ($ancien == '' && $r->Id_nationalite == 0)) {
                            $nouveau = $db->query('SELECT Id_nationalite FROM nationalite WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_nationalite;
                            $r->Id_nationalite = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Etat matrimonial' :
                        $ancien = $db->query('SELECT Id_etat_matrimonial FROM etat_matrimonial WHERE libelle = "' . $ancien . '"')->fetchRow()->id_etat_matrimonial;
                        if ($r->Id_etat_matrimonial == $ancien || ($ancien == '' && $r->Id_etat_matrimonial == 0)) {
                            $nouveau = $db->query('SELECT Id_etat_matrimonial FROM etat_matrimonial WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_etat_matrimonial;
                            $r->Id_etat_matrimonial = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Département de naissance' :
                        $ancien = $db->query('SELECT Id_departement FROM departement WHERE nom = "' . $ancien . '"')->fetchRow()->id_departement;
                        if ($r->Id_dpt_naiss == $ancien || ($ancien == '' && $r->Id_dpt_naiss == 0)) {
                            $nouveau = $db->query('SELECT Id_departement FROM departement WHERE nom = "' . $nouveau . '"')->fetchRow()->id_departement;
                            $r->Id_dpt_naiss = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Pays de résidence' :
                        $ancien = $db->query('SELECT Id_pays FROM pays WHERE nom = "' . $ancien . '"')->fetchRow()->id_pays;
                        if ($r->Id_pays_residence == $ancien || ($ancien == '' && $r->Id_pays_residence == 0)) {
                            $nouveau = $db->query('SELECT Id_pays FROM pays WHERE nom = "' . $nouveau . '"')->fetchRow()->id_pays;
                            $r->Id_pays_residence = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Préavis négociable' :
                        $ancien = ($ancien == 'oui') ? 1 : 0;
                        if ($e->preavis_negociable == $ancien) {
                            $nouveau = ($nouveau == 'oui') ? 1 : 0;
                            $e->preavis_negociable = $nouveau;
                            $e->save();
                        }
                        break;
                    case 'Préavis' :
                        $ancien = $db->query('SELECT Id_preavis FROM preavis WHERE libelle = "' . $ancien . '"')->fetchRow()->id_preavis;
                        if ($e->Id_preavis == $ancien || ($ancien == '' && $r->Id_preavis == 0)) {
                            $nouveau = $db->query('SELECT Id_preavis FROM preavis WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_preavis;
                            $e->Id_preavis = $nouveau;
                            $e->save();
                        }
                        break;
                    case 'Expérience informatique' :
                        $ancien = $db->query('SELECT Id_exp_info FROM exp_info WHERE libelle = "' . $ancien . '"')->fetchRow()->id_exp_info;
                        if ($r->Id_exp_info == $ancien || ($ancien == '' && $r->Id_exp_info == 0)) {
                            $nouveau = $db->query('SELECT Id_exp_info FROM exp_info WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_exp_info;
                            $r->Id_exp_info = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Profil' :
                        $ancien = $db->query('SELECT Id_profil FROM profil WHERE libelle = "' . $ancien . '"')->fetchRow()->id_profil;
                        if ($r->Id_profil == $ancien || ($ancien == '' && $r->Id_profil == 0)) {
                            $nouveau = $db->query('SELECT Id_profil FROM profil WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_profil;
                            $r->Id_profil = $nouveau;
                            $r->save();
                        }
                        break;
                    case 'Cursus' :
                        $ancien = $db->query('SELECT Id_cursus FROM cursus WHERE libelle = "' . $ancien . '"')->fetchRow()->id_cursus;
                        if ($r->Id_cursus == $ancien || ($ancien == '' && $r->Id_cursus == 0)) {
                            $nouveau = $db->query('SELECT Id_cursus FROM cursus WHERE libelle = "' . $nouveau . '"')->fetchRow()->id_cursus;
                            $r->Id_cursus = $nouveau;
                            $r->save();
                        }
                        break;

                    /* Cas tableaux */
                    case 'Régions souhaitées' :
                        $ligneNouveau = explode('<br>', $nouveau);
                        $db->query('DELETE FROM entretien_mobilite WHERE Id_entretien = ' . $c->Id_entretien);
                        foreach ($ligneNouveau as $j) {
                            $chaine = '';
                            $z = $db->query('SELECT Id_zone FROM zone WHERE libelle = "' . $j . '"')->fetchRow();
                            if (is_null($z)) {
                                $r = $db->query('SELECT Id_region, Id_zone FROM region WHERE libelle = "' . $j . '"')->fetchRow();
                                if (is_null($r)) {
                                    $d = $db->query('SELECT Id_departement, Id_region FROM departement WHERE nom = "' . $j . '"')->fetchRow();
                                    if (!is_null($d)) {
                                        $dr = $db->query('SELECT Id_region, Id_zone FROM region WHERE Id_region = "' . $d->id_region . '"')->fetchRow();
                                        $chaine .= $dr->id_zone . '-' . $dr->id_region . '-' . $d->id_departement;
                                    }
                                } else {
                                    $chaine .= $r->id_zone . '-' . $r->id_region;
                                }
                            } else {
                                $chaine .= $z->id_zone;
                            }
                            if ($chaine != 0 && !empty($chaine))
                                $db->query('INSERT INTO entretien_mobilite SET Id_entretien = "' . $c->Id_entretien . '", Id_mobilite = "' . $chaine . '"');
                        }
                        break;
                    case 'Type de contrat recherché' :
                        $ligneNouveau = explode(' ', $nouveau);
                        $db->query('DELETE FROM candidat_typecontrat WHERE Id_candidat = ' . $c->Id_candidature);
                        foreach ($ligneNouveau as $j) {
                            $id = $db->query('SELECT Id_type_contrat FROM type_contrat WHERE libelle = "' . $j . '"')->fetchRow()->id_type_contrat;
                            if ($id != 0 && !empty($id))
                                $db->query('INSERT INTO candidat_typecontrat SET Id_type_contrat = "' . $id . '", Id_candidat = "' . $c->Id_candidature . '"');
                        }
                        break;
                    case 'Certification' :
                        $ligneNouveau = explode(' ', $nouveau);
                        $db->query('DELETE FROM entretien_certification WHERE Id_entretien = ' . $c->Id_entretien);
                        foreach ($ligneNouveau as $j) {
                            $id = $db->query('SELECT Id_certification FROM certification WHERE libelle = "' . $j . '"')->fetchRow()->id_certification;
                            if ($id != 0 && !empty($id))
                                $db->query('INSERT INTO entretien_certification SET Id_certification = "' . $id . '", Id_entretien = "' . $c->Id_entretien . '"');
                        }
                        break;
                    case 'Compétence' :
                        $ligneNouveau = explode('<br>', $nouveau);
                        $db->query('DELETE FROM entretien_competence WHERE Id_entretien = ' . $c->Id_entretien);
                        foreach ($ligneNouveau as $j) {
                            list($comp, $niveau) = explode(' - ', $j);
                            $idComp = $db->query('SELECT Id_competence FROM competence WHERE libelle = "' . $comp . '"')->fetchRow()->id_competence;
                            $idNiv = $db->query('SELECT Id_niveau_competence FROM niveau_competence WHERE libelle = "' . $niveau . '"')->fetchRow()->id_niveau_competence;
                            if ($idComp != 0 && !empty($idComp))
                                $db->query('INSERT INTO entretien_competence SET Id_competence = "' . $idComp . '", Id_entretien = "' . $c->Id_entretien . '", Id_niveau_competence = "' . $idNiv . '"');
                        }
                        break;
                    case 'Langue' :
                        $ligneNouveau = explode('<br>', $nouveau);
                        $db->query('DELETE FROM entretien_langue WHERE Id_entretien = ' . $c->Id_entretien);
                        foreach ($ligneNouveau as $j) {
                            list($langue, $niveau) = explode(' - ', $j);
                            $idLangue = $db->query('SELECT Id_langue FROM langue WHERE libelle = "' . $langue . '"')->fetchRow()->id_langue;
                            $idNiv = $db->query('SELECT Id_niveau_langue FROM niveau_langue WHERE libelle = "' . $niveau . '"')->fetchRow()->id_niveau_langue;
                            if ($idLangue != 0 && !empty($idLangue))
                                $db->query('INSERT INTO entretien_langue SET Id_langue = "' . $idLangue . '", Id_entretien = "' . $c->Id_entretien . '", Id_niveau_langue = "' . $idNiv . '"');
                        }
                        break;
                }
            }
        }

        $reslien = $mysqliAGC->query('SELECT lien_cv,lien_cvp,lien_lm FROM candidature WHERE Id_cvweb = ' . $result['Id_candidat'] . ' ');
        while ($resl = $reslien->fetch_assoc()) {
            $lien_cv = $resl['lien_cv'];
            $lien_cvp = $resl['lien_cvp'];
            $lien_lm = $resl['lien_lm'];
        }

        $res6 = $mysqliCVWeb->query('SELECT lien_cv,lien_cvp,lien_lm FROM candidat WHERE Id_candidat = ' . $result['Id_candidat'] . ' ');
        while ($result6 = $res6->fetch_assoc()) {
            if ($result6['lien_cv'] != $lien_cv && $result6['lien_cv'] != '') {
                $mysqliAGC->query('UPDATE candidature SET lien_cv="' . $result6['lien_cv'] . '" WHERE Id_cvweb = ' . $result['Id_candidat'] . ' ');
                if (is_file('../CVtheque/' . $lien_cv)) {
                    unlink('../CVtheque/' . $lien_cv);
                }
            }
            if ($result6['lien_cvp'] != $lien_cvp && $result6['lien_cvp'] != '') {
                $mysqliAGC->query('UPDATE candidature SET lien_cvp="' . $result6['lien_cvp'] . '" WHERE Id_cvweb = ' . $result['Id_candidat'] . ' ');
                if (is_file('../CVtheque/' . $lien_cvp)) {
                    unlink('../CVtheque/' . $lien_cvp);
                }
            }
            if ($result6['lien_lm'] != $lien_lm && $result6['lien_lm'] != '') {
                $mysqliAGC->query('UPDATE candidature SET lien_lm="' . $result6['lien_lm'] . '" WHERE Id_cvweb = ' . $result['Id_candidat'] . ' ');
                if (is_file('../LMtheque/' . $lien_lm)) {
                    unlink('../LMtheque/' . $lien_lm);
                }
            }
        }
    }
}

// Connexion aux bases de données
$mysqli = new mysqli(CVWEB_SERVEUR, CVWEB_USER, CVWEB_PASSWD, CVWEB_BASE);

if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

$connexion = ssh2_connect(CVWEB_SERVEUR, CVWEB_PORTSSH) or die('Connexion SSH impossible.');

if (ssh2_auth_pubkey_file($connexion, 'apache', '/var/www/.ssh/id_rsa.pub', '/var/www/.ssh/id_rsa')) {
    $remote_file_cv = '/var/www/html/cvweb/CVtheque/';
    $local_file_cv = '/var/www/AGC/CVtheque/';
    $remote_file_lm = '/var/www/html/cvweb/LMtheque/';
    $local_file_lm = '/var/www/AGC/LMtheque/';
    if ($result = $mysqli->query("SELECT lien_cv, lien_cvp, lien_lm FROM candidat WHERE temp=0 and archive=0 and (lien_cv != '' || lien_cvp !='' || lien_lm !='')")) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['lien_cv'])) {
                if (ssh2_scp_recv($connexion, $remote_file_cv . $row['lien_cv'], $local_file_cv . $row['lien_cv'])) {
                    ssh2_exec($connexion, 'rm -rf ' . $remote_file_cv . $row['lien_cv']);
                    $mysqli->query('UPDATE candidat set lien_cv="" WHERE lien_cv = "' . $row['lien_cv'] . '"');
                } else {
                    echo 'Impossible de copier le fichier ' . $row['lien_cv'];
                }
            }
            if (!empty($row['lien_cvp'])) {
                if (ssh2_scp_recv($connexion, $remote_file_cv . $row['lien_cvp'], $local_file_cv . $row['lien_cvp'])) {
                    ssh2_exec($connexion, 'rm -rf ' . $remote_file_cv . $row['lien_cvp']);
                    $mysqli->query('UPDATE candidat set lien_cvp="" WHERE lien_cvp = "' . $row['lien_cvp'] . '"');
                } else {
                    echo 'Impossible de copier le fichier ' . $row['lien_cvp'];
                }
            }
            if (!empty($row['lien_lm'])) {
                if (ssh2_scp_recv($connexion, $remote_file_lm . $row['lien_lm'], $local_file_lm . $row['lien_lm'])) {
                    ssh2_exec($connexion, 'rm -rf ' . $remote_file_lm . $row['lien_lm']);
                    $mysqli->query('UPDATE candidat set lien_lm="" WHERE lien_lm = "' . $row['lien_lm'] . '"');
                } else {
                    echo 'Impossible de copier le fichier ' . $row['lien_lm'];
                }
            }
        }
    }
} else
    echo 'Authentification impossible.';
$mysqli->close();
?>
