<?php

/**
 * Fichier Update.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */
session_start();

/**
 * Déclaration de la classe Update
 */
class Update {

    /**
     * Mise à jour des types de Ressources en Titulaire pour de l'AT
     *
     */
    public static function majTypeRessourceAT() {
        $db = connecter();
        $result = $db->query('SELECT affaire.Id_affaire,proposition.Id_proposition,Id_ressource FROM proposition_ressource
		INNER JOIN proposition ON proposition_ressource.Id_proposition=proposition.Id_proposition 
		INNER JOIN affaire ON affaire.Id_affaire=proposition.Id_affaire WHERE Id_type_contrat=1 AND type=""');
        while ($ligne = $result->fetchRow()) {
            $db->query('UPDATE proposition_ressource SET type="T"
			WHERE Id_proposition=' . $ligne->id_proposition . ' AND Id_ressource="' . $ligne->id_ressource . '" AND type=""');
        }
        return 'Mise à jour des types OK.';
    }

    /**
     * Export des candidats pour Needprofile
     *
     */
    public static function exportNeedprofile() {
        $db = connecter();
        $result = $db->query('SELECT nom,prenom,mail,lien_cv,profil.libelle as profil FROM ressource
		INNER JOIN candidature ON ressource.Id_ressource=candidature.Id_ressource 
		LEFT JOIN profil ON profil.Id_profil=ressource.Id_profil 
		WHERE mail!="" and lien_cv!="" AND date < "2008-11-06" 
		AND date > "2008-06-01" AND Id_etat IN (2,7,15,16,17,18) ORDER BY nom');
        while ($ligne = $result->fetchRow()) {
            $contents .= $ligne->nom . ';' . $ligne->prenom . ';' . $ligne->mail . ';' . $ligne->profil . ';' . $ligne->lien_cv . ';';
            $contents .= "\n";
            if (is_file('../CVtheque/' . $ligne->lien_cv)) {
                exec('cp ../CVtheque/' . $ligne->lien_cv . ' CV_Needprofile/');
            }
            else {
                echo 'Le cv ' . $ligne->lien_cv . ' n\'existe pas';
            }
        }
        $file = '../script/exportNeedprofile.txt';
        // Open file to write
        $fh = fopen($file, 'w');
        fwrite($fh, $contents);
        fclose($fh);
    }

    /**
     * Script de mise à jour du 22122009
     *
     */
    public static function maj09022010() {
        $db = connecter();
        $result = $db->query('SELECT * FROM demande_ressource');
        while ($ligne = $result->fetchRow()) {
            $agence = $db->queryOne('SELECT Id_agence FROM utilisateur WHERE Id_utilisateur = "' . $ligne->id_ic . '"');
            $db->query('UPDATE demande_ressource SET agence = "' . $agence . '" WHERE Id_demande_ressource = "' . $ligne->id_demande_ressource . '" AND agence=""');
        }
    }

    /**
     * Script de mise à jour du 22122009
     *
     */
    public static function maj22122009() {
        $db = connecter();
        $db->query('DELETE FROM specialite WHERE Id_specialite IN (2,5,8,9,14,20,21,22,23,25,26,29,30,32,33,34,35,36)');
        $db->query('UPDATE ressource SET Id_specialite=0 WHERE Id_specialite IN (2,5,8,9,14,20,21,22,23,25,26,29,30,32,33,34,35,36)');

        $result = $db->query('SELECT Id_candidature,candidat_typecontrat.id_candidat FROM candidat_typecontrat INNER JOIN candidature ON candidature.Id_cvweb=candidat_typecontrat.Id_candidat WHERE candidat_typecontrat.id_candidat!=0');
        while ($ligne = $result->fetchRow()) {
            $db->query('UPDATE candidat_typecontrat SET Id_candidat="' . $ligne->id_candidature . '" WHERE Id_candidat="' . $ligne->id_candidat . '"');
        }

        $result = $db->query('SELECT Id_candidature FROM candidature WHERE stage=1 and alternance=0');
        while ($ligne = $result->fetchRow()) {
            $db->query('INSERT INTO candidat_typecontrat SET Id_candidat="' . $ligne->id_candidature . '", Id_type_contrat="1"');
        }
        $result = $db->query('SELECT Id_candidature FROM candidature WHERE stage=1 and alternance=1');
        while ($ligne = $result->fetchRow()) {
            $db->query('INSERT INTO candidat_typecontrat SET Id_candidat="' . $ligne->id_candidature . '", Id_type_contrat="2"');
        }
        $result = $db->query('SELECT Id_candidature FROM candidature WHERE independant=1');
        while ($ligne = $result->fetchRow()) {
            $db->query('INSERT INTO candidat_typecontrat SET Id_candidat="' . $ligne->id_candidature . '", Id_type_contrat="5"');
        }

        /* $requete = 'SELECT Id_description,resume,Id_profil1,Id_profil2,Id_cursus,experience_requise FROM description
          INNER JOIN affaire ON affaire.id_affaire=description.Id_affaire WHERE Id_type_contrat=1 AND
          (Id_profil1!="" OR Id_profil2!="" OR Id_cursus!="" OR experience_requise!="")';
          $result  = $db->query($requete);
          while ($ligne = $result->fetchRow()) {
          $resume = htmlscperso(stripslashes($ligne->resume), ENT_QUOTES).'<br /><hr /><br />Profil 1 : '.Profil::getLibelle($ligne->id_profil1).
          '<br /><br />Profil 2 : '.Profil::getLibelle($ligne->id_profil2).'
          '<br /><hr /><br />Cursus : '.Cursus::getLibelle($ligne->id_cursus).'
          '<br /><hr /><br />Expérience requise : '.Experience::getLibelle($ligne->experience_requise).''
          $requete2 = 'UPDATE description SET resume="'.$resume.'" WHERE Id_description="'.$ligne->id_description.'"';
          $db->query($requete2);
          } */
    }

    /**
     * Script de mise à jour du 13012010
     *
     */
    public static function maj13102010() {
        $db = connecter();
        $result = $db->query('SELECT Id_candidat FROM candidat_agence WHERE Id_agence="PAR ISP"
		AND Id_candidat IN (SELECT Id_candidat FROM candidat_agence WHERE Id_agence="PAR TSM") 
		AND Id_candidat IN (SELECT Id_candidat FROM candidat_agence WHERE Id_agence="PAR B&A")');
        while ($ligne = $result->fetchRow()) {
            $db->query('DELETE FROM candidat_agence WHERE Id_candidat=' . $ligne->id_candidat . ' and Id_agence="PAR TSM"');
            $db->query('DELETE FROM candidat_agence WHERE Id_candidat=' . $ligne->id_candidat . ' and Id_agence="PAR ISP"');
            $db->query('DELETE FROM candidat_agence WHERE Id_candidat=' . $ligne->id_candidat . ' and Id_agence="PAR B&A"');
            $db->query('INSERT INTO candidat_agence set Id_agence="PAR", Id_candidat=' . $ligne->id_candidat . '');
        }
    }

    /**
     * Script de mise à jour du 05052010
     *
     */
    public static function maj05052010() {
        $db = connecter();
        $result = $db->query('SELECT Id_proposition,Id_ressource,debut_mission,duree_mission,fin_mission FROM contrat_delegation c INNER JOIN proposition p ON c.Id_affaire=p.Id_affaire WHERE p.Id_affaire=454');

        while ($ligne = $result->fetchRow()) {
            $db->query('INSERT INTO proposition_ressource
			SET Id_proposition="' . $ligne->id_proposition . '",Id_ressource="' . $ligne->id_ressource . '",
			duree="' . $ligne->duree_mission . '",debut="' . $ligne->debut_mission . '",
			fin="' . $ligne->fin_mission . '",fin_prev="' . $ligne->fin_mission . '",type="T",inclus=1');
        }
    }

    /**
     * Script de mise à jour du 27052010
     * MAJ des affaires pistes d'annie soyez et d'alexandure Laurendot en Non Traitée à la demande d'Olivier Savier
     *
     */
    public static function maj27052010() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,commercial FROM affaire WHERE (commercial="annie.soyez" OR commercial="alexandre.laurendot" OR commercial="estelle.quinquis") AND Id_statut=1');
        while ($ligne = $result->fetchRow()) {
            $db->query('UPDATE affaire SET Id_statut=2 WHERE Id_affaire="' . $ligne->id_affaire . '"');

            $db->query('INSERT INTO historique_statut SET Id_affaire="' . $ligne->id_affaire . '",
			date="2010-05-27 10:15:00",Id_utilisateur="' . $ligne->commercial . '",Id_statut=2,
			commentaire="MAJ automatique à la demande d\'Olivier Savier"');
        }
    }

    /**
     * Script de mise à jour du 19012010
     * Récupère toutes les informations enlever lors de la simplification des formulaires d'affaire
     * et les stocker dans un champ de commentaire
     *
     */
    public static function maj19102010() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire FROM affaire');
        while ($ligne = $result->fetchRow()) {
            // Description
            $result2 = $db->query('SELECT d.ville, d.cp, d.origine, d.nature, d.cds FROM description d WHERE d.Id_affaire = ' . $ligne->id_affaire);
            $commentaire = 'Description
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= 'Ville : ' . $ligne2->ville . ' - CP : ' . $ligne2->cp . ' - Origine : ' . $ligne2->origine . ' - Nature : ' . $ligne2->nature . ' - CDS : ';
                $commentaire .= ( $ligne2->cds) ? 'Oui' : 'Non';
            }
            $result2 = $db->query('SELECT e.nom AS nom FROM affaire_exigence ae INNER JOIN exigence e ON e.Id_exigence = ae.Id_exigence WHERE ae.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			Exigences : ';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= $ligne2->nom . ' - ';
            }

            // Compétences Techniques
            $result2 = $db->query('SELECT c.libelle AS comp, nc.libelle AS niveau FROM affaire_competence ac
								   LEFT JOIN competence c ON c.Id_competence = ac.Id_competence
								   LEFT JOIN niveau_competence nc ON nc.Id_niveau_competence = ac.Id_niveau_competence WHERE ac.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			
			Compétences techniques
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= '' . $ligne2->comp . ' - ' . $ligne2->niveau . ' | ';
            }
            $result2 = $db->query('SELECT l.libelle AS langue, nl.libelle AS niveau FROM affaire_langue al
								   LEFT JOIN langue l ON l.Id_langue = al.Id_langue
								   LEFT JOIN niveau_langue nl ON nl.Id_niveau_langue = al.Id_niveau_langue WHERE al.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= '' . $ligne2->langue . ' - ' . $ligne2->niveau . ' | ';
            }

            // Analyse commerciale
            $result2 = $db->query('SELECT acom.dernier_projet, acom.cdc, acom.decideur, acom.budget_defini, acom.montant_budget,
								   acom.rdv, acom.concurrents_identifies, acom.partenaires_identifies,
								   acom.concurrents, acom.partenaires, acom.potentiel
								   FROM analyse_commerciale acom WHERE acom.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			
			Analyse commerciale
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= 'Derniers projets : ' . $ligne2->dernier_projet . '
				Cahier des charges existant : ';
                $commentaire .= ( $ligne2->cdc) ? 'Oui' : 'Non';
                $commentaire .= '
				Interlocuteurs = Décideurs : ';
                $commentaire .= ( $ligne2->decideur) ? 'Oui' : 'Non';
                $commentaire .= '
				Enveloppe budgétaire : ';
                $commentaire .= ( $ligne2->budget_defini) ? 'Oui' : 'Non';
                $commentaire .= ' - Montant : ' . $ligne2->montant_budget;
                $commentaire .= '
				RDV sur site effectué : ';
                $commentaire .= ( $ligne2->rdv) ? 'Oui' : 'Non';
                $commentaire .= '
				Potentiel de signer :' . $ligne2->potentiel;
                $commentaire .= '
				Concurrents identifiés : ';
                $commentaire .= ( $ligne2->concurrents_identifies) ? 'Oui' : 'Non';
                $commentaire .= ' - ' . $ligne2->concurrents;
                $commentaire .= '
				Partenaires identifiés : ';
                $commentaire .= ( $ligne2->partenaires_identifies) ? 'Oui' : 'Non';
                $commentaire .= ' - ' . $ligne2->partenaires;
            }

            // Analyse des risques
            $result2 = $db->query('SELECT ar.risque_proposition, ar.risque_projet, ar.niveau, ar.pourcentage_reussite FROM analyse_risque ar WHERE ar.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			
			Analyse des risques
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= 'Risques liés à la réalistion de la proposition : ' . $ligne2->risque_proposition . '
				Risques liés à la réalistion du projet : ' . $ligne2->risque_projet . '
				Niveau global de risque : ' . $ligne2->niveau;
            }

            // Décision
            $result2 = $db->query('SELECT decision.repondre FROM decision WHERE decision.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			
			Décision : Go / No-Go
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= 'Décision de répondre : ';
                $commentaire .= ( $ligne2->pdecision) ? 'Oui' : 'Non';
            }

            // Ressources
            $result2 = $db->query('SELECT r.nom, r.prenom, pr.frais_journalier, pr.cout_journalier, pr.tarif_journalier, pr.duree, pr.marge, pr.ca, pr.debut, pr.fin, pr.type, pr.inclus, pr.fin_prev
    		 FROM proposition_ressource pr
			 LEFT JOIN proposition p ON p.Id_proposition = pr.Id_proposition
			 INNER JOIN ressource r ON r.Id_ressource = pr.Id_ressource
			 WHERE p.Id_affaire = ' . $ligne->id_affaire);
            $commentaire .= '
			
			Ressources
			';
            while ($ligne2 = $result2->fetchRow()) {
                $commentaire .= $ligne2->nom . ' ' . $ligne2->prenom . ' | ';
                $commentaire .= ' Frais journalier : ' . $ligne2->frais_journalier . ' | Cout journalier : ' . $ligne2->cout_journalier . ' | Tarif journalier : ' . $ligne2->tarif_journalier . ' | ';
                $commentaire .= ' Duree : ' . $ligne2->duree . ' | Marge : ' . $ligne2->marge . ' | CA : ' . $ligne2->ca . ' | Debut : ' . $ligne2->debut . ' | Fin : ' . $ligne2->fin . ' | ';
                $commentaire .= ' Fin prev. : ' . $ligne2->fin_prev . ' | Type : ' . $ligne2->type . ' | Inclus : ';
                $commentaire .= ( $ligne2->inclus) ? 'Oui' : 'Non';
                $commentaire .= '
				';
            }

            $db->query('UPDATE affaire SET commentaire = "' . $commentaire . '" WHERE Id_affaire = ' . $ligne->id_affaire);
        }
    }

    /**
     * Script de mise à jour du 27012011
     *
     * Ajoute le statut courant d'une demande de ressource à l'historique
     * pour historiser les statuts datant d'avant la mise en production
     * de l'historisation des statuts des demandes de ressource
     *
     */
    public static function maj27012011() {
        $db = connecter();
        $result = $db->query('SELECT Id_demande_ressource, Id_cr, statut, date FROM demande_ressource');
        while ($ligne = $result->fetchRow()) {
            $db->query('INSERT INTO historique_statut_demande_ressource SET
                    Id_demande_ressource = "' . $ligne->id_demande_ressource . '",
                    Id_utilisateur = "' . $ligne->id_cr . '",
                    Id_statut = "' . $ligne->statut . '",
                    date = "' . $ligne->date . '"');
        }
    }

    /**
     * Mise à jour des noms des comptes client associés au rendez-vous en fonction de l'identifiant du compte client
     *
     */
    public static function majCompteRendezVous() {
        $db = connecter();
        $result = $db->query('SELECT Id_rendezvous,Id_compte FROM rendezvous');
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $db->query('UPDATE rendezvous SET compte="' . $compte->nom . '"WHERE Id_rendezvous=' . $ligne->id_rendezvous . '');
        }
    }

    /**
     * Mise à jour des intitulés du pôle AI
     *
     */
    public static function majIntitulePoleAI() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();

            $db->query('ALTER TABLE intitule CHANGE Id_pole Id_pole VARCHAR(10) NOT NULL DEFAULT ""');

            $db->query('UPDATE intitule SET Id_pole="1,4" WHERE Id_intitule=6');
            $db->query('UPDATE intitule SET Id_pole="1,4" WHERE Id_intitule=7');

            $db->query('DELETE FROM intitule WHERE Id_intitule IN (29,30,31,32,33,34,35,36,37,38,39,40,44,45,46,47,48,49,50,51,52,53,54,55,56)');

            $db->query('INSERT INTO intitule SET libelle="Architecture Système",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="Architecture Réseau",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="Architecture Securité",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="Architecture Stockage",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="Ingénierie Securité",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="Ingénierie Stockage",Id_pole=4');
            $db->query('INSERT INTO intitule SET libelle="CDS Expertise",Id_pole=4');

            $db->query('UPDATE description SET Id_intitule="88" WHERE Id_intitule IN (30,36,37,38,44)');
            $db->query('UPDATE description SET Id_intitule="89" WHERE Id_intitule IN (39,49)');
            $db->query('UPDATE description SET Id_intitule="90" WHERE Id_intitule IN (48)');
            $db->query('UPDATE description SET Id_intitule="6" WHERE Id_intitule IN (29,31,32,34,35,40,45,46,47,50,52,54,56)');
            $db->query('UPDATE description SET Id_intitule="7" WHERE Id_intitule IN (51,55)');
            $db->query('UPDATE description SET Id_intitule="93" WHERE Id_intitule IN (53)');
            $db->query('UPDATE description SET Id_intitule="94" WHERE Id_intitule IN (33)');
        }
    }

    /**
     * Mise à jour des tables agences AGC
     *
     */
    public static function majAgenceAGC() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();

            $db->query('ALTER TABLE agence ADD COLUMN adresse VARCHAR(255) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE agence ADD COLUMN code_postal VARCHAR(10) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE agence ADD COLUMN ville VARCHAR(255) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE agence ADD COLUMN telephone VARCHAR(30) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE agence ADD COLUMN fax VARCHAR(30) NOT NULL DEFAULT ""');

            $db = connecter_cegid();
            $result = $db->query('SELECT * FROM ETABLISS');
            while ($ligne = $result->fetchRow()) {
                $db = connecter();
                $db->query('UPDATE agence SET adresse="' . $ligne->et_adresse1 . ' ' . $ligne->et_adresse2 . ' ' . $ligne->et_adresse3 . '" WHERE Id_agence="' . $ligne->et_etablissement . '"');
                $db->query('UPDATE agence SET code_postal="' . $ligne->et_codepostal . '" WHERE Id_agence="' . $ligne->et_etablissement . '"');
                $db->query('UPDATE agence SET ville="' . $ligne->et_ville . '" WHERE Id_agence="' . $ligne->et_etablissement . '"');
                $db->query('UPDATE agence SET telephone="' . $ligne->et_telephone . '" WHERE Id_agence="' . $ligne->et_etablissement . '"');
                $db->query('UPDATE agence SET fax="' . $ligne->et_fax . '" WHERE Id_agence="' . $ligne->et_etablissement . '"');
            }
        }
    }

    /**
     * Mise à jour des utilisateurs : ajout du nom, prenom, mail
     *
     */
    public static function majUtilisateur() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();

            $db->query('ALTER TABLE utilisateur ADD column nom VARCHAR(100) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE utilisateur ADD column prenom VARCHAR(100) NOT NULL DEFAULT ""');
            $db->query('ALTER TABLE utilisateur ADD column mail VARCHAR(100) NOT NULL DEFAULT ""');

            $result = $db->query('SELECT Id_utilisateur FROM utilisateur');
            while ($ligne = $result->FetchRow()) {
                $db2 = connecter_cegid();
                $req2 = 'SELECT US_ABREGE, US_LIBELLE FROM UTILISAT WHERE US_ABREGE="' . strtoupper($ligne->id_utilisateur) . '"
				ORDER BY SUBSTRING(US_ABREGE,CHARINDEX( ".", US_ABREGE)+1,10)';
                $result2 = $db2->query($req2);
                $ligne2 = $result2->FetchRow();
                $nomprenom = explode(' ', $ligne2->us_libelle);
                $req3 = 'UPDATE utilisateur SET nom="' . strtoupper($nomprenom[1]) . '", prenom="' . formatPrenom($nomprenom[0]) . '",
				mail="' . $ligne->id_utilisateur . '@proservia.fr" WHERE Id_utilisateur="' . $ligne->id_utilisateur . '"';
                $db->query($req3);
            }
        }
    }

    /**
     * Ajout de la colonne apporteur avec pour valeur par défaut le commercial de l'affaire
     *
     */
    public static function majApporteur() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();

            $db->query('ALTER TABLE affaire ADD column apporteur VARCHAR(100) NOT NULL DEFAULT ""');

            $result = $db->query('SELECT commercial FROM affaire');
            while ($ligne = $result->FetchRow()) {
                $req = 'UPDATE affaire SET apporteur="' . $ligne->commercial . '" WHERE commercial="' . $ligne->commercial . '"';
                $db->query($req);
            }
        }
    }
    
    /**
     * Mise à jour des agences de rattachement pour respecter le champ à sélection unique
     */
    public static function updateApplicantAgency() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            
            $result = $db->query('SELECT DISTINCT substr(Id_agence,1,3) as Id_agence, ag.code_postal FROM agence ag 
		WHERE ag.Id_societe = 1 AND Id_agence NOT IN("ALC","ANE","ELI","LYN")');

            $agences = Array();
            $i = 0;
            while ($ligne = $result->fetchRow()) {
                if($ligne->id_agence == "AIX")
                    $zip = "13090";
                elseif($ligne->id_agence == "STR")
                    $zip = "67000";
                elseif($ligne->id_agence == "PAR")
                    $zip = "75008";
                else
                    $zip = $ligne->code_postal;
                
                $result2 = $db->query('SELECT latitude, longitude FROM geography_city WHERE zip = "' . $zip . '"')->fetchRow();
                $agences[$i]['id_agence'] = $ligne->id_agence;
                $agences[$i]['latitude'] = $result2->latitude;
                $agences[$i]['longitude'] = $result2->longitude;
                $i++;
            }

            $nb_ag =count($agences);
            $result = $db->query('SELECT c.Id_candidature, r.code_postal FROM candidature c INNER JOIN ressource r ON c.Id_ressource = r.Id_ressource');
            while ($ligne = $result->fetchRow()) {
                $result2 = $db->query('SELECT latitude, longitude FROM geography_city WHERE zip = "' . $ligne->code_postal . '"')->fetchRow();

                $min_keys = Array(); 
                $tabDistance = Array();
                $i = 0;
                while($i < $nb_ag) {
                    $radius = 6378100; // radius of earth in meters
                    $latDist = $agences[$i]['latitude'] - $result2->latitude;
                    $lngDist = $agences[$i]['longitude'] - $result2->longitude;
                    $latDistRad = deg2rad($latDist);
                    $lngDistRad = deg2rad($lngDist);
                    $sinLatD = sin($latDistRad);
                    $sinLngD = sin($lngDistRad);
                    $cosLat1 = cos(deg2rad($agences[$i]['latitude']));
                    $cosLat2 = cos(deg2rad($result2->latitude));
                    $a = $sinLatD*$sinLatD + $cosLat1*$cosLat2*$sinLngD*$sinLngD*$sinLngD;
                    if($a<0) $a = -1*$a;
                    $c = 2*atan2(sqrt($a), sqrt(1-$a));
                    $distance = $radius*$c;

                    $tabDistance[$agences[$i]['id_agence']] = $distance;
                    $i++;
                }

                foreach($tabDistance as $ag => $dist)
                    if($dist == min($tabDistance)) array_push($min_keys, $ag);

                $db->query('DELETE FROM candidat_agence WHERE Id_candidat = ' . mysql_real_escape_string((int) $ligne->id_candidature));
                $db->query('INSERT INTO candidat_agence SET Id_candidat = "' . mysql_real_escape_string((int) $ligne->id_candidature) . '", Id_agence = "' . mysql_real_escape_string($min_keys[0]) . '"');
            }
        }
    }

}

?>
