 <?php

/**
 * Fichier Script.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */
session_name('agc');
session_start();

/**
 * Déclaration de la classe Script
 */
class Script {

    /**
     * Mise à jour des dates de fin de commande et date de fin prévisionnelle des affaires AGC ayant pour type de contrat Assistance Technique
     *
     */
    public static function updateCasesDate() {
        $db = connecter();
        /* Contrat Assistance Technique */
        $result = $db->query('SELECT DISTINCT aff.Id_affaire as Id_aff,date_debut,date_fin_commande,date_fin_previsionnelle,
        (SELECT min(debut) FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition AND debut!="0000-00-00") as mindate,
        (SELECT max(fin) FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition) as maxdate,
                (SELECT max(fin_prev) FROM proposition_ressource WHERE Id_proposition=p1.Id_proposition) as maxdateprevisionnelle
        FROM affaire aff INNER JOIN proposition p1 ON p1.Id_affaire=aff.Id_affaire
        INNER JOIN proposition_ressource ON p1.Id_proposition=proposition_ressource.Id_proposition
                INNER JOIN planning ON planning.Id_affaire=aff.Id_affaire
        AND Id_type_contrat=1 AND debut !="0000-00-00" AND fin !="0000-00-00"');
        while ($ligne = $result->fetchRow()) {
            $db->query('UPDATE planning SET date_fin_commande="' . mysql_real_escape_string($ligne->maxdate) . '",
                        date_fin_previsionnelle="' . mysql_real_escape_string($ligne->maxdateprevisionnelle) . '"
                        WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_aff) . '');
        }

        /* Contrat Infogérance / Forfait de service */
        $result2 = $db->query('SELECT DISTINCT(aff.Id_affaire) as Id_aff,date_debut,date_fin_commande,date_fin_previsionnelle,
        (SELECT min(debut) FROM proposition_periode WHERE Id_proposition=p1.Id_proposition AND debut!="0000-00-00") as mindate,
        (SELECT max(fin) FROM proposition_periode WHERE Id_proposition=p1.Id_proposition and Id_periode!=6) as maxdate,
                (SELECT max(fin) FROM proposition_periode WHERE Id_proposition=p1.Id_proposition and Id_periode=6) as maxdateprevisionnelle
        FROM affaire aff INNER JOIN proposition p1 ON p1.Id_affaire=aff.Id_affaire
        INNER JOIN proposition_periode ON p1.Id_proposition=proposition_periode.Id_proposition
                INNER JOIN planning ON planning.Id_affaire=aff.Id_affaire
        AND debut !="0000-00-00" AND fin !="0000-00-00" and date_debut!="2008-12-31" and aff.Id_affaire NOT IN (2557,2576,2577,2578,2579,3208) ORDER BY aff.Id_affaire');
        while ($ligne2 = $result2->fetchRow()) {
            $date_prev = '';
            if ($ligne2->maxdateprevisionnelle != '0000-00-00') {
                $date_prev = ', date_fin_previsionnelle="' . mysql_real_escape_string($ligne2->maxdateprevisionnelle) . '"';
            }
            $db->query('UPDATE planning SET date_debut="' . mysql_real_escape_string($ligne2->mindate) . '",
                        date_fin_commande="' . mysql_real_escape_string($ligne2->maxdate) . '" ' . mysql_real_escape_string($date_prev) . '
                        WHERE Id_affaire=' . mysql_real_escape_string($ligne2->id_aff) . '');
        }
        return 'Mise à jour OK.';
    }

    /**
     * Mise à jour des statuts d'affaire en fonction des dates de début et de fin de comman de l'affaire AGC
     *
     */
    public static function updateCasesStatus() {
        $db = connecter();
        $c = strtotime(strftime('%d-%m-%Y', time()));
        $result = $db->query('SELECT DISTINCT affaire.Id_affaire,date_debut,date_fin_commande,Id_statut,commercial FROM affaire
                INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
                INNER JOIN proposition ON proposition.Id_affaire=affaire.Id_affaire
                INNER JOIN description ON description.Id_affaire=affaire.Id_affaire
                WHERE chiffre_affaire!=0 AND date_debut!="0000-00-00" AND date_fin_commande!="0000-00-00"
                AND Id_compte!="" AND Id_contact1!="" AND Id_statut IN (5,8,9) ORDER BY affaire.Id_affaire');
        while ($ligne = $result->fetchRow()) {
            if (strtotime($ligne->date_debut) <= $c && strtotime($ligne->date_fin_commande) >= $c) {
                if ($ligne->id_statut != 8) {
                    $db->query('UPDATE affaire SET Id_statut=8 WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '",
                                        Id_statut=8,Id_utilisateur="' . mysql_real_escape_string($ligne->commercial) . '",
                                        commentaire="Mise à jour automatique"');
                }
            } elseif (strtotime($ligne->date_fin_commande) <= $c) {
                if ($ligne->id_statut != 9) {
                    $db->query('UPDATE affaire SET Id_statut=9 WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '",
                                        Id_statut=9,Id_utilisateur="' . mysql_real_escape_string($ligne->commercial) . '",
                                        commentaire="Mise à jour automatique"');
                }
            }
        }
        return 'Mise à jour OK.';
    }

    /**
     * Mise à jour de la table de listing des affaires AGC
     *
     */
    public static function updateCasesListing() {

        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;

            $db = connecter();
            $db->query('DELETE FROM listing_affaires');
            $result = $db->query('SELECT affaire.Id_affaire,agence.libelle as libelleagence,commercial,statut.trig as libellestatut,pole.trig as libellepole,
                    com_type_contrat.trig as libelletc,Id_compte,Id_contact1,date_creation, date_modification, planning.date_debut,planning.date_fin_commande,planning.date_fin_previsionnelle,
                    intitule.libelle as libelleintitule, resume, planning.date_pec, resp_tec1, resp_tec2, planning.date_demande FROM affaire
                    INNER JOIN statut ON statut.Id_statut=affaire.Id_statut
                    INNER JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire
                    INNER JOIN pole ON affaire_pole.Id_pole=pole.Id_pole
                    INNER JOIN com_type_contrat ON com_type_contrat.Id_type_contrat=affaire.Id_type_contrat
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
                    INNER JOIN agence ON agence.Id_agence=affaire.Id_agence
                    INNER JOIN description ON description.Id_affaire=affaire.Id_affaire
                    LEFT JOIN intitule ON intitule.Id_intitule=description.Id_intitule');

            while ($ligne = $result->fetchRow()) {
                $collab = $historique_statut = $historique_commentaire = '';
                $Id_proposition = Affaire::lastProposition($ligne->id_affaire);
                $compte = CompteFactory::create(null, $ligne->id_compte);
                $contact = ContactFactory::create(null, $ligne->id_contact1);

                $res_collab = $db->query('SELECT DISTINCT Id_ressource FROM proposition_ressource
                            WHERE Id_proposition="' . mysql_real_escape_string($Id_proposition) . '" AND fin >="' . mysql_real_escape_string(DATE) . '"');
                while ($ligne_collab = $res_collab->fetchRow()) {
                    $collab .= ' - ' . Ressource::getName($ligne_collab->id_ressource);
                }

                $result2 = $db->query('SELECT Id_statut, date FROM historique_statut WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"');
                while ($ligne2 = $result2->fetchRow()) {
                    $historique_statut .= ' - ' . Statut::getLibelle($ligne2->id_statut) . ' le ' . FormatageDate(substr($ligne2->date, 0, 10)) . '\n';
                }

                $result3 = $db->query('SELECT comment, weighting_pct, week FROM commentaire_affaire_semaine
                            WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"');
                while ($ligne3 = $result3->fetchRow()) {
                    $historique_commentaire .= ' - S ' . $ligne3->week . ' ' . $ligne3->weighting_pct . '%  ' . $ligne3->comment . '\n';
                }

                $ligne_chiffre = $db->query('SELECT chiffre_affaire,marge,date_remise FROM proposition WHERE Id_proposition="' . mysql_real_escape_string($Id_proposition) . '"')->fetchRow();
                $r = $db->query('INSERT INTO listing_affaires SET Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '", statut="' . mysql_real_escape_string($ligne->libellestatut) . '",
                            agence="' . mysql_real_escape_string($ligne->libelleagence) . '", commercial="' . mysql_real_escape_string($ligne->commercial) . '",
                            client="' . mysql_real_escape_string($compte->nom) . '", intitule="' . mysql_real_escape_string($ligne->libelleintitule) . '",
                            description="' . mysql_real_escape_string(htmlscperso(stripslashes($ligne->resume), ENT_QUOTES)) . '",
                            pole="' . mysql_real_escape_string($ligne->libellepole) . '", type_contrat="' . mysql_real_escape_string($ligne->libelletc) . '", collaborateur="' . mysql_real_escape_string($collab) . '", date_creation="' . mysql_real_escape_string($ligne->date_creation) . '",
                            debut="' . mysql_real_escape_string($ligne->date_debut) . '", date_fin_commande="' . mysql_real_escape_string($ligne->date_fin_commande) . '", date_fin_previsionnelle="' . mysql_real_escape_string($ligne->date_fin_previsionnelle) . '",
                            ca="' . mysql_real_escape_string($ligne_chiffre->chiffre_affaire) . '", marge="' . mysql_real_escape_string($ligne_chiffre->marge) . '", date_modification="' . mysql_real_escape_string($ligne->date_modification) . '",
                            date_remise="' . mysql_real_escape_string($ligne_chiffre->date_remise) . '", resp_tec1="' . mysql_real_escape_string($ligne->resp_tec1) . '", resp_tec2="' . mysql_real_escape_string($ligne->resp_tec2) . '",
                            date_pec="' . mysql_real_escape_string($ligne->date_pec) . '", Id_affaire_cegid="' . mysql_real_escape_string(Affaire::getIdAffaireCEGID($ligne->id_affaire, "")) . '",
                            recurrente="' . mysql_real_escape_string(Affaire::isRecursive($ligne->id_affaire)) . '",
                            historique_statut="' . mysql_real_escape_string($historique_statut) . '",
                            historique_commentaire="' . mysql_real_escape_string($historique_commentaire) . '", contact = "' . mysql_real_escape_string($contact->getName()) . '",
                            date_demande = "' . mysql_real_escape_string($ligne->date_demande) . '"
                ');
            }
        }
    }

    /**
     * Mise à jour de la table de listing des rendez vous Cegid
     *
     */
    public static function updateAppointmentListing() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;

            $db = connecter();
            $db_cegid = connecter_cegid();
            $result_cegid = $db_cegid->query('SELECT UTILISAT.US_abrege AS commercial, RAC_DATEACTION AS date_action, RAC_TYPEACTION AS type_action,
                        RAC_BLOCNOTE AS note, T_LIBELLE AS client FROM UTILISAT
                        RIGHT OUTER JOIN RESSOURCE ON UTILISAT.US_utilisateur = RESSOURCE.ARS_UTILASSOCIE
                        RIGHT OUTER JOIN ACTIONS ON RESSOURCE.ARS_RESSOURCE = ACTIONS.RAC_INTERVENANT
                        RIGHT OUTER JOIN RTANNUAIRE ON ACTIONS.RAC_AUXILIAIRE = RTANNUAIRE.C_AUXILIAIRE
                        WHERE RAC_typeaction in ("RDS","RDP","RDV") AND RAC_ETATACTION="REA" AND C_TYPECONTACT="T" AND C_AUXILIAIRE=rac_auxiliaire AND C_NUMEROCONTACT=rac_numerocontact');

            $db->query('DELETE FROM listing_rendezvous');
            while ($ligne_cegid = $result_cegid->fetchRow()) {
                $db->query('INSERT INTO listing_rendezvous SET
                                        commercial="' . mysql_real_escape_string($ligne_cegid->commercial) . '", date_action="' . mysql_real_escape_string($ligne_cegid->date_action) . '",
                                        type_action="' . mysql_real_escape_string($ligne_cegid->type_action) . '", note="' . mysql_real_escape_string($ligne_cegid->note) . '",
                                        client="' . mysql_real_escape_string($ligne_cegid->client) . '"');
            }
        }
    }

    /**
     * Mise à jour de la table de listing des candidatures AGC
     *
     */
    public static function updateApplicationListing() {
        $db = connecter();
        $db->query('DELETE FROM listing_candidature');
        $result = $db->query('SELECT DISTINCT c.Id_candidature,c.Id_ressource,e.Id_entretien,c.createur,e.Id_commercial as commercial,
                    c.date,lien_cv,r.Id_profil,p.libelle as profil,r.Id_cursus,cu.libelle as cursus,c.Id_etat,
                        c.Id_nature, nc.libelle as nature, e.Id_preavis, pr.libelle as preavis,
                        r.Id_exp_info,ei.libelle as exp_info,staff,nom,prenom,tel_fixe,tel_portable,
                        pretention_basse,pretention_haute,th,c.archive,c.commentaire,attente_pro,mot_cle,
                        code_postal,date_disponibilite
            FROM candidature c
                    INNER JOIN ressource r ON r.Id_ressource=c.Id_ressource
                        LEFT JOIN entretien e ON e.Id_candidature=c.Id_candidature
                        LEFT JOIN exp_info ei ON ei.Id_exp_info=r.Id_exp_info
                        LEFT JOIN nature_candidature nc ON nc.Id_nature_candidature=c.Id_nature
                        LEFT JOIN profil p ON p.Id_profil=r.Id_profil
                        LEFT JOIN cursus cu ON cu.Id_cursus=r.Id_cursus
                        LEFT JOIN preavis pr ON pr.Id_preavis=e.Id_preavis');

        while ($ligne = $result->fetchRow()) {
            $langue = $createur = $etat = $Id_etat = $date = $mobilite = '';
            $result2 = $db->query('SELECT * FROM entretien_langue WHERE Id_entretien="' . mysql_real_escape_string($ligne->id_entretien) . '"');
            while ($ligne2 = $result2->fetchRow()) {
                $langue .= '|' . $ligne2->id_langue . '-' . $ligne2->id_niveau_langue;
            }

            $result3 = $db->query('SELECT hc.Id_etat,hc.date,ec.libelle,hc.Id_utilisateur FROM historique_candidature hc
                        LEFT JOIN etat_candidature ec ON ec.Id_etat_candidature=hc.Id_etat
                        WHERE hc.Id_candidature="' . mysql_real_escape_string($ligne->id_candidature) . '" ORDER BY date DESC LIMIT 0,1');
            while ($ligne3 = $result3->fetchRow()) {
                $createur = $ligne3->id_utilisateur;
                $Id_etat = $ligne3->id_etat;
                $etat = $ligne3->libelle;
                $date = $ligne3->date;
            }

            $result4 = $db->query('SELECT * FROM entretien_mobilite WHERE Id_entretien="' . mysql_real_escape_string($ligne->id_entretien) . '"');
            while ($ligne4 = $result4->fetchRow()) {
                $mobilite .= '|' . $ligne4->id_mobilite;
            }
            $db->query('INSERT INTO listing_candidature SET
                        Id_candidature="' . mysql_real_escape_string($ligne->id_candidature) . '", Id_ressource="' . mysql_real_escape_string($ligne->id_ressource) . '",
                        Id_entretien="' . mysql_real_escape_string($ligne->id_entretien) . '", createur="' . mysql_real_escape_string($createur) . '",commercial="' . mysql_real_escape_string($ligne->commercial) . '",
                        date="' . mysql_real_escape_string($date) . '", lien_cv="' . mysql_real_escape_string($ligne->lien_cv) . '", Id_profil="' . mysql_real_escape_string($ligne->id_profil) . '",
                        profil="' . mysql_real_escape_string($ligne->profil) . '",Id_cursus="' . mysql_real_escape_string($ligne->id_cursus) . '",cursus="' . mysql_real_escape_string($ligne->cursus) . '",
                        Id_etat="' . mysql_real_escape_string($Id_etat) . '",etat="' . mysql_real_escape_string($etat) . '",Id_nature="' . mysql_real_escape_string($ligne->id_nature) . '",
                        nature="' . mysql_real_escape_string($ligne->nature) . '",Id_preavis="' . mysql_real_escape_string($ligne->id_preavis) . '",preavis="' . mysql_real_escape_string($ligne->preavis) . '",
                        Id_exp_info="' . mysql_real_escape_string($ligne->id_exp_info) . '",exp_info="' . mysql_real_escape_string($ligne->exp_info) . '",staff="' . mysql_real_escape_string($ligne->staff) . '",
                        nom="' . mysql_real_escape_string($ligne->nom) . '",prenom="' . mysql_real_escape_string($ligne->prenom) . '",tel_fixe="' . mysql_real_escape_string($ligne->tel_fixe) . '",
                        tel_portable="' . mysql_real_escape_string($ligne->tel_portable) . '",pretention_basse="' . mysql_real_escape_string($ligne->pretention_basse) . '",
                        pretention_haute="' . mysql_real_escape_string($ligne->pretention_haute) . '",th="' . mysql_real_escape_string($ligne->th) . '",
                        commentaire="' . mysql_real_escape_string($ligne->commentaire) . '",mot_cle="' . mysql_real_escape_string($ligne->mot_cle) . '",
                        attente_pro="' . mysql_real_escape_string($ligne->attente_pro) . '",code_postal="' . mysql_real_escape_string($ligne->code_postal) . '",
                        date_disponibilite="' . mysql_real_escape_string($ligne->date_disponibilite) . '", langue_niveau="' . mysql_real_escape_string($langue) . '",
                        mobilite="' . mysql_real_escape_string($mobilite) . '", archive="' . mysql_real_escape_string($ligne->archive) . '"');
        }
    }

	/**
     * Export des utilisateurs pour l'application de gestion des entretiens dans un fichier texte
     *
     */
    public static function oscExport() {
        $db = connecter();
        $res = Bdd::getDatabases();
        foreach ($res as $l) {
            $_SESSION['societe'] = $l;
            $cegid_databases = Bdd::getCegidDatabases($l, true);
            $contents = '';
            $requeteSalarie = '';
            $s = '';
            $ie = '';

            $db = connecter_cegid();
            // On va récupérer l'ensemble des utilisateurs Cegid qui sont des collaborateurs
            $i = 0;
            foreach ($cegid_databases as $cegid_database) {
                $requeteSalarie .= ($i != 0) ? ' UNION' : '';
                $requeteSalarie .= ' SELECT DISTINCT "' . $cegid_database['code_societe'] . '" AS code_societe, "' . $cegid_database['cegid_database'] . '" AS societe, S.PSA_SALARIE,US_ABREGE,PGD.PSE_EMAILPROF,RESPONSVAR, RESPONSABS,
                                    PSA_LIBELLE, PSA_PRENOM,PSA_LIBELLEEMPLOI , PSA_DATENAISSANCE, S.PSA_DATEENTREE,
                                    S.PSA_DATESORTIE, PSA_INDICE, S.PSA_ETABLISSEMENT, S.PSA_TRAVAILN1, CC2.CC_LIBELLE AS libelleemploi, CC1.CC_LIBELLE AS cds,
                                    PMI3.PMI_LIBELLE AS indice
                                    FROM ' . $cegid_database['cegid_database'] . '.DBO.SALARIES S
                                    INNER JOIN ' . $cegid_database['cegid_database'] . '.DBO.UTILISAT U ON PSA_SALARIE=U.US_AUXILIAIRE
                                    LEFT JOIN ' . $cegid_database['cegid_database'] . '.DBO.PGDEPORTSAL PGD ON PGD.PSE_SALARIE=S.PSA_SALARIE
                                    LEFT JOIN ' . $cegid_database['cegid_database'] . '.DBO.CHOIXCOD CC1 ON PSA_LIBREPCMB2 = CC1.CC_CODE AND CC_TYPE = "PL2"
                                    LEFT OUTER JOIN ' . $cegid_database['cegid_database'] . '.DBO.CHOIXCOD CC2 ON PSA_LIBELLEEMPLOI=CC2.CC_CODE AND CC2.CC_TYPE="PLE"
                                    LEFT OUTER JOIN ' . $cegid_database['cegid_database'] . '.DBO.MINIMUMCONVENT PMI3 ON PSA_INDICE=PMI3.PMI_CODE AND PMI3.PMI_NATURE="IND"
                                    AND US_ABREGE!= " "';
                $i++;
            }
            $resultSalarie = $db->query($requeteSalarie);
           
            $salaries = array();
            while ($ligneSalarie = $resultSalarie->fetchRow()) {
                $salaries[strtolower(withoutAccent($ligneSalarie->us_abrege))] = $ligneSalarie->psa_salarie;
                $s[] = $ligneSalarie;
            }
           
            // On va récupérer l'ensemble des utilisateurs Cegid qui sont des intervenants extérieurs
            $i = 0;
            foreach ($cegid_databases as $cegid_database) {
                $requeteIntervenantExterieur .= ($i != 0) ? ' UNION' : '';
                $requeteIntervenantExterieur .= ' SELECT DISTINCT "' . $cegid_database['code_societe'] . '" AS code_societe, "'.
                                $cegid_database['cegid_database'] . '" AS societe,  I.PSI_INTERIMAIRE, U.US_ABREGE,
                                                                                D.PSE_EMAILPROF, D.PSE_RESPONSVAR, D.PSE_RESPONSABS, I.PSI_LIBELLE, I.PSI_PRENOM,
                                                                            I.PSI_LIBELLEEMPLOI, I.PSI_DATENAISSANCE, I.PSI_DATEENTREE, I.PSI_DATESORTIE, I.PSI_INDICE,
                                                                                I.PSI_ETABLISSEMENT, I.PSI_TRAVAILN1, (SELECT SOC_DATA FROM PARAMSOC WHERE SOC_NOM = "SO_LIBELLE") AS NOM_SOCIETE
                                                                                FROM ' . $cegid_database['cegid_database'] . '.DBO.UTILISAT U LEFT JOIN SALARIES S ON U.US_AUXILIAIRE = S.PSA_SALARIE
                                                                                LEFT JOIN ' . $cegid_database['cegid_database'] . '.DBO.DEPORTSAL D ON U.US_AUXILIAIRE = D.PSE_SALARIE
                                                                                LEFT JOIN ' . $cegid_database['cegid_database'] . '.DBO.INTERIMAIRES I ON U.US_AUXILIAIRE = I.PSI_INTERIMAIRE
                                                                                                WHERE ((I.PSI_TYPEINTERIM<>"CAN" AND I.PSI_TYPEINTERIM<>"SAL"))';
                $i++;
            }
            $resultIntervenantExterieur = $db->query($requeteIntervenantExterieur);
           
            $intervenantsExterieurs = array();
            while($ligneIntervenantExterieur = $resultIntervenantExterieur->fetchRow()){
                // L'équivalent du champ psa_salarie pour la table interimaire psi_interimaire
                $intervenantsExterieurs[strtolower(withoutAccent($ligneIntervenantExterieur->us_abrege))] = $ligneIntervenantExterieur->psi_interimaire;
                $ie[] = $ligneIntervenantExterieur;
            }
           
            // On va récupérer les valideurs pour les salaries
            $requeteIntervenant = '';
            $j = 0;
            foreach ($cegid_databases as $cegid_database) {
                $requeteIntervenant .= ($j != 0) ? ' UNION' : '';
                $requeteIntervenant .= ' SELECT US_ABREGE, PSE_SALARIE FROM ' . $cegid_database['cegid_database'] . '.DBO.PGDEPORTSAL PGD
                              INNER JOIN ' . $cegid_database['cegid_database'] . '.DBO.UTILISAT U ON PSE_SALARIE=U.US_AUXILIAIRE
                                  WHERE PSA_SALARIE IS NULL';
                $j++;
            }
            $resultIntervenant = $db->query($requeteIntervenant);
           
            $intervenants = array();
            while ($ligneIntervenant = $resultIntervenant->fetchRow()) {
                $intervenants[$ligneIntervenant->pse_salarie] = strtolower(withoutAccent($ligneIntervenant->us_abrege));
            }          
         

            //Dans cette partie, on traite les internes
            foreach ($s as $ligne) {
                if (strpos($ligne->us_abrege, 'ABSENCE') !== false) {
                    list($a, $b) = explode('@', $ligne->pse_emailprof);
                    $ligne->us_abrege = $a;
                } else if (strpos($ligne->us_abrege, 'VISITE') !== false) {
                    list($a, $b) = explode('@', $ligne->pse_emailprof);
                    $ligne->us_abrege = $a;
                }
                $date_naissance = str_replace(' 00:00:00', '', $ligne->psa_datenaissance);
                $date_entree = str_replace(' 00:00:00', '', $ligne->psa_dateentree);
                $date_sortie = str_replace(' 00:00:00', '', $ligne->psa_datesortie);

                /*
                  $res1      = $db->query('SELECT DISTINCT PSA_SALARIE FROM UTILISAT WHERE US_AUXILIAIRE="'.$ligne->pse_responsabs.'"');
                  $ligne1   = $res1->fetchRow();
                  $resp_abs = strtolower(withoutAccent($ligne1->psa_salarie));

                  $res2     = $db->query('SELECT DISTINCT PSA_SALARIE FROM UTILISAT WHERE US_AUXILIAIRE="'.$ligne->pse_responsvar.'"');
                  $ligne2   = $res2->fetchRow();
                  $resp_var = strtolower(withoutAccent($ligne2->psa_salarie));
                 */
                $staff = ($ligne->psa_travailn1 == '001') ? 1 : 0;
               
                if($intervenants[$ligne->responsabs]) {
                    $ligne->responsabs = $salaries[$intervenants[$ligne->responsabs]];
                }

                $contents .= $ligne->psa_salarie . ';' . strtolower(withoutAccent($ligne->us_abrege)) . ';' . $ligne->psa_libelle . ';' . $ligne->psa_prenom . ';';
                $contents .= $date_naissance . ';' . $ligne->pse_emailprof . ';' . $date_entree . ';' . $date_sortie . ';';
                $contents .= $staff . ';' . $ligne->psa_etablissement . ';' . $ligne->responsabs . ';' . $ligne->responsvar . ';' . $ligne->libelleemploi . ';' . $ligne->cds . ';';
                $contents .= $ligne->code_societe . ';' . $ligne->indice . ';';
                $contents .= "\n";
            }
           
            //Dans cette partie, on traite les externes
            foreach ($it as $ligne){
                if(strpos($ligne->us_abrege, 'ABSENCE') !== false){
                        list($a, $b) = explode('@', $ligne->pse_emailprof);
                        $ligne->us_abrege = $a;
                }
                else if (strpos($ligne->us_abrege, 'VISITE') !== false) {
                        list($a, $b) = explode('@', $ligne->pse_emailprof);
                        $ligne->us_abrege = $a;                
                }
                $date_naissance = str_replace(' 00:00:00', '', $ligne->psi_datenaissance);
                $date_entree = str_replace(' 00:00:00', '', $ligne->psi_dateentree);
                $date_sortie = str_replace(' 00:00:00', '', $ligne->psi_datesortie);
               
                $staff = ($ligne->psi_travailn1 == '001') ? 1 : 0;
               
                if($intervenants[$ligne->reponsabs]){
                        $ligne->responsabs = $salaries[$intervenants[$ligne->responsabs]];
                }
                $contents .= $ligne->psa_salarie . ';' . strtolower(withoutAccent($ligne->us_abrege)) . ';' . $ligne->psa_libelle . ';' . $ligne->psa_prenom . ';';
                $contents .= $date_naissance . ';' . $ligne->pse_emailprof . ';' . $date_entree . ';' . $date_sortie . ';';
                $contents .= $staff . ';' . $ligne->psa_etablissement . ';' . $ligne->responsabs . ';' . $ligne->responsvar . ';';
                $contents .= $ligne->code_societe . ';';
                $contents .= "\n";
            }
           
            // On inscrit les salaries internes et externes dans le fichier
            $file = '../script/exportOSC_' . $l . '.txt';
            // Open file to write
            $fh = fopen($file, 'w');
            fwrite($fh, $contents);
            fclose($fh);
        }
    }
    /**
     * Export des données commerciales pour Idea v2
     *
     */
    public static function exportIdea() {
        $sfClient = new SforceEnterpriseClient();
        $sfClient->createConnection(SF_WSDL, null, array('encoding' => 'ISO-8859-1'));
        $sfClient->login(SF_USER, SF_PASSWD . SF_TOKEN);
        $options = new QueryOptions(2000);
        $sfClient->setQueryOptions($options);
        /*WHERE a.archive = 0 AND a.Id_type_contrat IN (2) AND hs.Id_statut IN (5,8)'
         * SELECT ID_AGC__c, Name, Account.Name, (SELECT Contact.Name FROM OpportunityContactRoles WHERE isPrimary = true), Type_opportunite__c
FROM Opportunity WHERE RecordTypeId = '012D0000000JuJKIA0' AND StageName IN ('Gain confirmé (bon de cde ferme)', 'Gain non confirmé')
         */
        $opportunities = $sfClient->query('
                SELECT ID_AGC__c, Name, Account.Name, (SELECT Contact.Name FROM OpportunityContactRoles WHERE isPrimary = true), Type_opportunite__c
                FROM Opportunity WHERE RecordTypeId = \'012D0000000JuJKIA0\' AND StageName IN (\'Gain confirmé (bon de cde ferme)\', \'Gain non confirmé\')
        ');

        $done = false;
        while (!$done) {
            $nOp = count($opportunities->records);
            $i = 0;
            while ($i < $nOp) {
                $currentArray = array();
                echo date('d-m-Y H:i:s') . ' - ' . $opportunities->records[$i]->ID_AGC__c . PHP_EOL;

                array_push($currentArray, $opportunities->records[$i]->ID_AGC__c);
                array_push($currentArray, $opportunities->records[$i]->Account->Name);
                array_push($currentArray, $opportunities->records[$i]->OpportunityContactRoles->records[0]->Contact->Name);
                array_push($currentArray, $opportunities->records[$i]->Name);

                $list[$i] = $currentArray;
                $i++;
            }
            if ($opportunities->done != true) {
                try {
                    $opportunities = $sfClient->queryMore($opportunities->queryLocator);
                } catch (Exception $e) {
                    print_r($sfClient->getLastRequest());
                    echo $e->faultstring;
                    $sfClient->login(SF_USER, SF_PASSWD . SF_TOKEN);
                }
            } else {
                $done = true;
            }
        }

        $fp = fopen('../idea/export_idea.csv', 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
    }

    /**
     * Vérification de l'état des candidatures en fonction des dates dans l'historique
     *
     * @return String
     */
    public static function checkApplicantStatus() {
        $db = connecter();
        $result = $db->query('SELECT Id_candidature,Id_etat,date,createur FROM candidature ORDER BY createur');
        while ($ligne = $result->fetchRow()) {
            $result2 = $db->query('SELECT candidature.Id_candidature FROM historique_candidature hc
                        INNER JOIN candidature ON candidature.Id_candidature=hc.Id_candidature
            WHERE hc.Id_candidature=' . mysql_real_escape_string($ligne->id_candidature) . ' AND hc.Id_etat!=' . mysql_real_escape_string($ligne->id_etat) . '
            AND hc.date=(SELECT DISTINCT(max(date)) FROM historique_candidature
            WHERE Id_candidature=' . mysql_real_escape_string($ligne->id_candidature) . ') and createur!="" and candidature.date >="2009-01-01"
                        ORDER BY createur, candidature.Id_candidature');
            $ligne2 = $result2->fetchRow();
            if ($ligne2->id_candidature) {
                echo 'Candidature n° ' . $ligne2->id_candidature . ' créée le ' . FormatageDate($ligne->date) . ' par ' . $ligne->createur . '<br />';
            }
        }
    }

    /**
     * Vérification de la cohérence des statuts d'affaire entre leut statut effectif et ceux enregistrés dans l'historique des statuts
     *
     * @return String
     */
    public static function checkStatusCase() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,Id_statut FROM affaire');
        while ($ligne = $result->fetchRow()) {
            $result2 = $db->query('SELECT Id_statut FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . ' AND date=(SELECT max(date) FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire) . ')');
            $ligne2 = $result2->fetchRow();
            if ($ligne2->id_statut != $ligne->id_statut) {
                $html .= 'L\'affaire n°' . $ligne->id_affaire . ' a le statut ' . $ligne->id_statut . ' et son dernier statut dans l\'historique est' . $ligne2->id_statut . '<br />';
            }
        }
        return $html;
    }

    /**
     * Vérification de la présence des ressources dans une affaire en fonction des contrats délégation créés
     *
     * @return String
     */
    public static function checkResourcePresencePerCase() {
        $db = connecter();
        $result = $db->query('SELECT Id_ressource,Id_affaire FROM contrat_delegation cd where cd.Id_ressource NOT IN
                (SELECT DISTINCT Id_ressource FROM proposition_ressource pr INNER JOIN proposition p ON p.Id_proposition=pr.Id_proposition
                WHERE p.Id_affaire=cd.Id_affaire)');
        while ($ligne = $result->fetchRow()) {
            $html .= 'La ressource n° ' . $ligne->id_ressource . ' est absente de l\'affaire ' . $ligne->id_affaire . '<br />';
        }
        return $html;
    }

    /**
     * Vérification de la présence des ressources dans une affaire en fonction des contrats délégation créés
     *
     * @return String
     */
    public static function majCodeRessourceCEGID() {
        $db = connecter();
        $result = $db->query('SELECT r.Id_ressource,Id_candidature,securite_sociale FROM ressource r
                INNER JOIN candidature c ON c.Id_ressource=r.Id_ressource WHERE securite_sociale!=""');
        while ($ligne = $result->fetchRow()) {
            $db = connecter_cegid();
            $result2 = $db->query('SELECT ARS_RESSOURCE FROM RESSOURCE
                                LEFT JOIN SALARIES ON ars_salarie=salaries.psa_salarie
                                LEFT JOIN DEPORTSAL ON pse_salarie=salaries.psa_salarie
                                LEFT OUTER JOIN CHOIXCOD CC2 ON PSA_LIBELLEEMPLOI=CC2.CC_CODE AND CC2.CC_TYPE="PLE"
                LEFT OUTER JOIN COMMUN CO3 ON PSA_SITUATIONFAMIL=CO3.CO_CODE AND CO3.CO_TYPE="PSF"
                            LEFT OUTER JOIN PAYS PY4 ON PSA_NATIONALITE=PY4.PY_PAYS
                                LEFT OUTER JOIN CHOIXCOD CC5 ON PSA_LIBREPCMB1=CC5.CC_CODE AND CC5.CC_TYPE="PL1"
                                WHERE PSA_NUMEROSS="' . str_replace(" ", "", $ligne->securite_sociale) . '"');

            $ligne2 = $result2->fetchRow();
            if ($ligne2->ars_ressource != "") {
                //$db->query('UPDATE proposition_ressource SET Id_ressource="'.$ligne2->ars_ressource.'" WHERE Id_ressource="'.$ligne->id_ressource.'"');
                $html .= 'La ressource n° ' . $ligne->id_ressource . ' avec le numéro candidat n° ' . $ligne->id_candidature . ' est liée à la ressource CEGID ' . $ligne2->ars_ressource . '<br />';
            }
        }
        echo $html;
    }

    /**
     * Vérification et contrôle relatifs aux affaires AGC
     *
     * @return String
     */
    public static function generalCheck() {
        $db = connecter();
        $erreur = '';
        $result = $db->query('SELECT Id_proposition FROM proposition_ressource WHERE Id_proposition NOT IN (SELECT Id_proposition FROM proposition)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'La proposition n°' . $ligne->id_proposition . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire_pole WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM planning WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Planning : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM description WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Description : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire_competence WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Affaire Compétences : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire_exigence WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Affaire Exigence : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire_langue WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Affaire Langue : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM decision WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Decision : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM analyse_commerciale WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Analyse Commerciale : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM analyse_risque WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Analyse Risque : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM historique_statut WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Historique Statut : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM environnement WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Environnement : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM contrat_delegation WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire) and Id_affaire!=0');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Contrat délégation : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_contrat_delegation FROM cd_indemnite WHERE Id_contrat_delegation NOT IN (SELECT Id_contrat_delegation FROM contrat_delegation)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Contrat Délégation Indémnité : Le contrat délégation n°' . $ligne->id_contrat_delegation . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_cd FROM ordre_mission WHERE Id_cd NOT IN (SELECT Id_contrat_delegation FROM contrat_delegation)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Ordre Mission : Le contrat délégation n°' . $ligne->id_cd . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_session FROM planning_session WHERE Id_session NOT IN (SELECT Id_session FROM session)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Planning Session : La session n°' . $ligne->id_session . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_session FROM doc_formation WHERE Id_session NOT IN (SELECT Id_session FROM session)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Doc Formation : La session n°' . $ligne->id_session . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_proposition FROM proposition_formation WHERE Id_proposition NOT IN (SELECT Id_proposition FROM proposition)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Proposition Formation : La proposition n°' . $ligne->id_proposition . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_session FROM proposition_session WHERE Id_session NOT IN (SELECT Id_session FROM session)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Proposition Session : La session n°' . $ligne->id_session . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_proposition FROM proposition_periode WHERE Id_proposition NOT IN (SELECT Id_proposition FROM proposition)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Proposition Période : La proposition n°' . $ligne->id_proposition . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM participant WHERE Id_affaire NOT IN (SELECT Id_affaire FROM affaire)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Participant : L\'affaire n°' . $ligne->id_affaire . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_plan_session FROM planning_date WHERE Id_plan_session NOT IN (SELECT Id_plan_session FROM planning_session)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Planning Date : Le planning session n°' . $ligne->id_plan_session . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_session FROM logistique WHERE Id_session NOT IN (SELECT Id_session FROM session)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Logistique : La session n°' . $ligne->id_session . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_ressource FROM candidature WHERE Id_ressource NOT IN (SELECT Id_ressource FROM ressource)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Ressource : La ressource n°' . $ligne->id_ressource . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_ressource FROM ressource WHERE Id_ressource NOT IN (SELECT Id_ressource FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Ressource : La ressource n°' . $ligne->id_ressource . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_ressource FROM proposition_ressource WHERE Id_ressource NOT IN (SELECT Id_ressource FROM ressource)');
        while ($ligne = $result->fetchRow()) {
            if ((int) $ligne->id_ressource) {
                $erreur .= 'Proposition Ressource : La ressource n°' . $ligne->id_ressource . ' n\'existe plus.<br />';
            }
        }
        $result = $db->query('SELECT Id_ressource FROM contrat_delegation WHERE Id_ressource NOT IN (SELECT Id_ressource FROM ressource) AND Id_ressource!=0');
        while ($ligne = $result->fetchRow()) {
            if ((int) $ligne->id_ressource) {
                $erreur .= 'CD Ressource : La ressource n°' . $ligne->id_ressource . ' n\'existe plus.<br />';
            }
        }
        $result = $db->query('SELECT Id_ressource FROM ressource_specialite WHERE Id_ressource NOT IN (SELECT Id_ressource FROM ressource)');
        while ($ligne = $result->fetchRow()) {
            if ((int) $ligne->id_ressource) {
                $erreur .= 'Spécialité Ressource : La ressource n°' . $ligne->id_ressource . ' n\'existe plus.<br />';
            }
        }
        $result = $db->query('SELECT Id_candidature FROM entretien WHERE Id_candidature NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien : La candidature n°' . $ligne->id_candidature . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidature FROM modif_candidat WHERE Id_candidature NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Modif Candidat : La candidature n°' . $ligne->id_candidature . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidature FROM historique_candidature WHERE Id_candidature NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Historique Candidature : La candidature n°' . $ligne->id_candidature . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidature FROM historique_action_candidature WHERE Id_candidature NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Historique Action Candidature : La candidature n°' . $ligne->id_candidature . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidat FROM candidat_agence WHERE Id_candidat NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Candidat Agence : La candidature n°' . $ligne->id_candidat . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidat FROM candidat_annonce WHERE Id_candidat NOT IN (SELECT Id_cvweb FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Candidat Annonce : La candidature cvweb n°' . $ligne->id_candidat . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_candidat FROM candidat_typecontrat WHERE Id_candidat NOT IN (SELECT Id_candidature FROM candidature)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Candidat Type Contrat : La candidature cvweb n°' . $ligne->id_candidat . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_entretien FROM entretien_certification WHERE Id_entretien NOT IN (SELECT Id_entretien FROM entretien)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien Certification : L\'entretien n°' . $ligne->id_entretien . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_entretien FROM entretien_competence WHERE Id_entretien NOT IN (SELECT Id_entretien FROM entretien)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien Compétence : L\'entretien n°' . $ligne->id_entretien . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_entretien FROM entretien_critere WHERE Id_entretien NOT IN (SELECT Id_entretien FROM entretien)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien Critère : L\'entretien n°' . $ligne->id_entretien . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_entretien FROM entretien_langue WHERE Id_entretien NOT IN (SELECT Id_entretien FROM entretien)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien Langue : L\'entretien n°' . $ligne->id_entretien . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_entretien FROM entretien_mobilite WHERE Id_entretien NOT IN (SELECT Id_entretien FROM entretien)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'Entretien Mobilite : L\'entretien n°' . $ligne->id_entretien . ' n\'existe plus.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire WHERE Id_affaire NOT IN (SELECT Id_affaire FROM listing_affaires)');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'L\'affaire n°' . $ligne->id_affaire . ' n\'est pas dans le listing des affaires.<br />';
        }
        $result = $db->query('SELECT Id_affaire FROM affaire WHERE commercial=""');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'L\'affaire n°' . $ligne->id_affaire . ' n\'a pas de commercial.<br />';
        }
        $result = $db->query('SELECT Id_candidature FROM candidature WHERE archive=1');
        while ($ligne = $result->fetchRow()) {
            $erreur .= 'La candidature n°' . $ligne->id_candidature . ' est une archive.<br />';
        }

        $erreur.= self::checkDoubleApplicant() . '<br />';
        $erreur.= self::checkApplicantFiles() . '<br />';
        $erreur.= self::checkStatusCase() . '<br />';
        echo $erreur;
        echo 'Traitement terminée.';
    }

    /**
     * Fonction qui renvoit les affaires opérationnelles ou terminées qui n'ont pas le statut signée dans leur historique.
     *
     */
    public static function checkCasesSignature() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,commercial,Id_statut FROM affaire WHERE Id_statut IN (8,9)');
        $html = '
                <h2>Affaires Opérationnelles et Terminées pour lesquelles il manque le statut "signée" dans l\'historique</h2>
                <table border=1>
                    <tr>
                        <th>Id affaire</th>
                                <th>Commercial</th>
                                <th>Statut</th>
                                <th>Date OP</th>
                        </tr>
                ';
        while ($ligne = $result->fetchRow()) {
            $Id_statut = array();
            $result2 = $db->query('SELECT DISTINCT(Id_statut) FROM historique_statut WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"');
            while ($ligne2 = $result2->fetchRow()) {
                $Id_statut[] = $ligne2->id_statut;
            }
            if (!in_array(5, $Id_statut)) {
                $result3 = $db->query('SELECT DATE_ADD(min(date),INTERVAL -30 SECOND) as date FROM historique_statut WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"
                            AND Id_statut=8');
                $ligne3 = $result3->fetchRow();

                /* $db->query('INSERT INTO historique_statut SET Id_affaire="'.$ligne->id_affaire.'",Id_utilisateur="'.$ligne->commercial.'",
                  date="'.$ligne3->date.'",Id_statut=5'); */

                $html .= '
                                <tr>
                                    <td><a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a></td>
                                        <td>' . $ligne->commercial . '</td>
                                        <td>' . Statut::getLibelle($ligne->id_statut) . '</td>
                                        <td>' . $ligne3->date . '</td>
                                </tr>
                                ';
            }
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Fonction qui vérifie la durée en jours ouvrés pour les ressources AT titulaire en fonction des des dates de début et de fin de commande renseignée
     *
     */
    public static function checkWorkingDaysNumberPerResource() {
        $db = connecter();
        $result = $db->query('SELECT affaire.Id_affaire,commercial,Id_compte,Id_ressource,pr.duree,debut,fin FROM proposition_ressource pr
                INNER JOIN proposition p ON p.Id_proposition=pr.Id_proposition
                INNER JOIN affaire ON affaire.Id_affaire=p.Id_affaire
                INNER JOIN planning pl ON pl.Id_affaire=affaire.Id_affaire
                WHERE type="T" AND Id_type_contrat=1 AND date_fin_commande >= "2010-01-01"
                AND Id_statut IN (5,8,9) ORDER BY commercial');
        $html = '
                <h2>Affaires pour lesquelles la ressource n\'intervient pas le nombre total de jours ouvrés sur la période d\'intervention !</h2>
                <table border=1>
                    <tr>
                        <th>Id affaire</th>
                                <th>Commercial</th>
                                <th>Client</th>
                                <th>Ressource</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée Théorique</th>
                                <th>Durée Saisie</th>
                        </tr>  
                ';
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $nb_ouv = workingDays($ligne->debut, $ligne->fin);
            if ($nb_ouv != $ligne->duree) {
                $html .= '
                                <tr>
                                    <td><a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a></td>
                                        <td>' . $ligne->commercial . '</td>
                                        <td>' . $compte->nom . '</td>
                                        <td>' . Ressource::getName($ligne->id_ressource) . '</td>
                                        <td>' . $ligne->debut . '</td>
                                        <td>' . $ligne->fin . '</td>
                                        <td>' . $nb_ouv . '</td>
                                        <td>' . $ligne->duree . '</td>
                                </tr>
                                ';
            }
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Fonction qui vérifie la cohérence des dates de fin des ressources
     *
     */
    public static function checkDatePerResource() {
        $db = connecter();
        $result = $db->query('SELECT CONCAT_WS("_",affaire.Id_affaire,Id_ressource,fin_prev) AS valeur, affaire.Id_affaire,count(*) AS nb_rep,
                commercial,Id_compte,Id_ressource,debut,fin,fin_prev
                FROM proposition_ressource pr
                INNER JOIN proposition ON proposition.id_proposition=pr.id_proposition
                INNER JOIN affaire ON proposition.id_affaire=affaire.id_affaire
                INNER JOIN planning ON planning.id_affaire=affaire.Id_affaire
                WHERE Id_statut IN (5,8,9) AND date_fin_commande >= "2010-01-01"
                GROUP BY valeur HAVING COUNT(*) > 1');
        $html = '
                <h2>Affaires pour lesquelles les dates de fin prévisionnelle des ressources ne sont pas cohérentes !</h2>
                <table border=1>
                    <tr>
                        <th>Id affaire</th>
                                <th>Commercial</th>
                                <th>Client</th>
                                <th>Ressource</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Fin prév</th>
                        </tr>  
                ';
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $html .= '
                        <tr>
                                <td><a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a></td>
                                <td>' . $ligne->commercial . '</td>
                                <td>' . $compte->nom . '</td>
                                <td>' . Ressource::getName($ligne->id_ressource) . '</td>
                                <td>' . $ligne->debut . '</td>
                                <td>' . $ligne->fin . '</td>
                                <td>' . $ligne->fin_prev . '</td>
                        </tr>
                        ';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Vérification des affaires AGC opérationnelles terminées n'étant pas répértoriées dans CEGID
     *
     * @return String
     */
    public static function checkLinkCasesAGCCEGID() {
        $db = connecter_cegid();
        $result = $db->query('SELECT DISTINCT AFF_CHARLIBRE1 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE1 !=""
                UNION
                SELECT DISTINCT AFF_CHARLIBRE2 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE2 !=""
                UNION
                SELECT DISTINCT AFF_CHARLIBRE3 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE3 !=""
                ');
        $affCegid = array();
        while ($ligne = $result->fetchRow()) {
            $affCegid[] = $ligne->affaire;
        }

        $result = $db->query('SELECT DISTINCT AFF_CHARLIBRE1 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE1 LIKE "%-%"
                UNION
                SELECT DISTINCT AFF_CHARLIBRE2 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE2 LIKE "%-%"
                UNION
                SELECT DISTINCT AFF_CHARLIBRE3 as affaire FROM AFPIECEAFFAIRE
        INNER JOIN AFFAIRE ON AFFAIRE.AFF_AFFAIRE=AFPIECEAFFAIRE.AFF_AFFAIRE
                WHERE AFF_CHARLIBRE3 LIKE "%-%"
                ');
        $listAff = array();
        while ($ligne = $result->fetchRow()) {
            $listAff = explode('-', $ligne->affaire);
            foreach ($listAff as $i) {
                $affCegid[] = $i;
            }
        }
        $db = connecter();
        $result2 = $db->query('SELECT affaire.Id_affaire,commercial,Id_compte,Id_statut,
        DATE_FORMAT(date_debut, "%d-%m-%Y") as date_debut, DATE_FORMAT(date_fin_commande, "%d-%m-%Y") as date_fin_commande
        FROM affaire
                INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
        WHERE affaire.Id_statut IN (8,9) AND affaire.Id_affaire NOT IN ("' . mysql_real_escape_string(implode('","', $affCegid)) . '")
                AND     date_fin_commande > "2009-12-31" ORDER BY affaire.Id_affaire DESC');

        $html = '
                <h2>Affaires AGC Opérationnelles / Terminées qui ne sont pas répertoriées dans CEGID !</h2>
                <table border=1>
                    <tr>
                        <th>Id affaire</th>
                                <th>Commercial</th>
                                <th>Client</th>
                                <th>Début</th>
                                <th>Fin</th>
                        </tr>  
                ';
        while ($ligne2 = $result2->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne2->id_compte);
            $html .= '
                        <tr>
                                <td><a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne2->id_affaire . '\')">' . $ligne2->id_affaire . '</a></td>
                                <td>' . $ligne2->commercial . '</td>
                                <td>' . $compte->nom . '</td>
                                <td>' . $ligne2->date_debut . '</td>
                                <td>' . $ligne2->date_fin_commande . '</td>
                        </tr>
                        ';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Fonction qui vérifie les affaires en anomalie concernant l'affectation du commercial entre CEGID et l'AGC
     *
     */
    public static function checkComercialAffectationError() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,commercial,count(DISTINCT commercial) as nb
                FROM budget WHERE Id_affaire!=0 GROUP BY Id_affaire HAVING COUNT(DISTINCT commercial) > 1;');
        $html = '
                <h2>Affaires AGC ayant des commerciaux différents entre CEGID et l\'AGC.</h2>
                ';
        while ($ligne = $result->fetchRow()) {
            $html .= '
                        Id_affaire : <a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a><br />
                        ' . Statistique::getCAPrevisionnelDetails($ligne->id_affaire) . '<br /><br /><hr /><br />
                        ';
        }
        return $html;
    }

    /**
     * Fonction qui vérifie les affaires en anomalie concernant l'affectation de l'agence entre CEGID et l'AGC
     *
     */
    public static function checkAgencyAffectationError() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,Id_agence,count(DISTINCT Id_agence) as nb
                FROM budget WHERE Id_affaire!=0 GROUP BY Id_affaire HAVING COUNT(DISTINCT Id_agence) > 1;');
        $html = '
                <h2>Affaires AGC ayant des agences différentes entre CEGID et l\'AGC.</h2>
                ';
        while ($ligne = $result->fetchRow()) {
            $html .= '
                        Id_affaire : <a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a><br />
                        Détails : <br />' . Statistique::getCAPrevisionnelDetails($ligne->id_affaire) . '<br /><br /><hr /><br />
                        ';
        }
        return $html;
    }

    /**
     * Fonction qui vérifie les affaires en anomalie concernant l'affectation de type de contrat entre CEGID et l'AGC
     *
     */
    public static function checkContractTypeAffectationError() {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire,Id_type_contrat,count(DISTINCT Id_type_contrat) as nb
                FROM budget WHERE Id_affaire!=0 GROUP BY Id_affaire HAVING COUNT(DISTINCT Id_type_contrat) > 1;');
        $html = '
                <h2>Affaires AGC ayant des types de contrat différents entre CEGID et l\'AGC.</h2>
                ';
        while ($ligne = $result->fetchRow()) {
            $html .= '
                        Id_affaire : <a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a><br />
                        Détails : <br />' . Statistique::getCAPrevisionnelDetails($ligne->id_affaire) . '<br /><br /><hr /><br />
                        ';
        }
        return $html;
    }

    /**
     * Affiche les candidats saisis en doublons dans l'AGC
     *
     * @return String
     */
    public static function checkDoubleApplicant() {
        $db = connecter();
        $html = '
                <h2>Candidats en doublons</h2>
                <table>
                    <tr>
                        <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date création</th>
                                <th>Créateur</th>
                ';
        $result = $db->query("SELECT CONCAT_WS('_',nom,prenom,date_naissance) AS valeur, candidature.Id_candidature,
                    candidature.createur, date, nom, prenom, COUNT(*) AS nb_rep FROM ressource
                    INNER JOIN candidature ON ressource.Id_ressource=candidature.Id_ressource
                    GROUP BY valeur HAVING COUNT(*) > 1 ORDER BY nom,prenom");
        while ($ligne = $result->fetchRow()) {
            $html .= '
                        <tr>
                            <td>' . $ligne->nom . '</td>
                                <td>' . $ligne->prenom . '</td>
                                <td>' . FormatageDate($ligne->date) . '</td>
                                <td>' . $ligne->createur . '</td>
                        </tr>';
        }
        $html .= '</table>';
        echo $html;
    }

    /**
     * Affiche les collaborateurs ayant changé de mission durant les 2 derniers mois par défaut. Possibilité de filtrer sur une période  
     *
     * @return String
     */
    public static function missionChangePerResource() {
        if (!$_POST['debut'] && !$_POST['fin']) {
            $lastmonth = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
            $debut = date('Y-m', $lastmonth) . '-01';
            $fin = date('Y-m') . '-31';
        } else {
            $debut = DateMysqltoFr($_POST['debut'], 'mysql');
            $fin = DateMysqltoFr($_POST['fin'], 'mysql');
        }
        $ressourceTal = RessourceFactory::create('CAN_TAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSal = RessourceFactory::create('SAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSt = RessourceFactory::create('ST', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceInt = RessourceFactory::create('INT', $_SESSION['filtre']['Id_ressource'], array());
        $agence = new Agence($_POST['agence'], array());

        $requete = 'SELECT cd.Id_contrat_delegation,cd.createur,cd.Id_ressource,cd.debut_mission,cd.fin_mission,cd.lieu_mission, cd.type_ressource
                FROM contrat_delegation cd WHERE cd.Id_ressource !=""
                                        AND cd.fin_mission >= "' . $debut . '" AND cd.fin_mission <= "' . $fin . '"
                                        AND cd.fin_mission = (SELECT max(fin_mission) FROM contrat_delegation WHERE Id_ressource=cd.Id_ressource
                                        AND fin_mission >= "' . $debut . '" AND fin_mission <= "' . $fin . '" AND lieu_mission=cd.lieu_mission)
                                        ';

        if ($_POST['ressource']) {
            $requete .= ' AND cd.Id_ressource="' . $_POST['ressource'] . '"';
        }
        $requete.= 'ORDER BY cd.Id_ressource,cd.Id_contrat_delegation,cd.fin_mission';
        $bdd[$_SESSION['societe']] = 'selected="selected"';
        $html .= '
                <h2>CHANGEMENT DE MISSION ENTRE LE ' . FormatageDate($debut) . ' ET LE ' . FormatageDate($fin) . '</h2>
                    <form action="../script/changementMission.php" method="post"><br />
                <select Id="Id_societe" name="societe" onchange="selectBdd()">
                                    <option value="" >Par société</option>
                                        <option value="1" ' . $bdd['PROSERVIA'] . '>PROSERVIA</option>
                                        <option value="2" ' . $bdd['OVIALIS'] . '>OVIALIS</option>
                                        <option value="5" ' . $bdd['NETLEVEL'] . '>NETLEVEL</option>
                                        <option value="4" ' . $bdd['NEEDPROFILE'] . '>NEEDPROFILE</option>
                        </select>
                                &nbsp;&nbsp;
                                Début : <input type="text" name="debut" onfocus="showCalendarControl(this)" value="' . FormatageDate($debut) . '" size="8" />
                                Fin : <input type="text" name="fin" onfocus="showCalendarControl(this)" value="' . FormatageDate($fin) . '" size="8" />
                                &nbsp;&nbsp;
                        <select name="ressource">
                                <option value="">Par ressource</option>
                                <option value="">----------------------------</option>
                                ' . $ressourceTal->getList() . '
                                ' . $ressourceSal->getList() . '
                                ' . $ressourceSt->getList() . '
                                ' . $ressourceInt->getList() . '
                        </select>
                                &nbsp;&nbsp;
                        <select name="agence">
                                <option value="">Par agence</option>
                                <option value="">----------------------------</option>
                                ' . $agence->getList() . '
                        </select>                              
                    <input type="submit" value="Go!" /><br /><br />
            </form>
                <table class="sortable">
                    <tr>
                        <th>Id_contrat_delegation</th>
                                <th>Créateur</th>
                                <th>Nom / Prénom</th>
                                <th>Agence</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Lieu</th>
                ';

        $_SESSION['societe'] = Bdd::getCegidDatabase($_POST['societe']);

        $db = connecter();
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $ligne2 = $db->query('SELECT cd.Id_ressource, cd.type_ressource FROM contrat_delegation cd
                    WHERE cd.Id_ressource="' . mysql_real_escape_string($ligne->id_ressource) . '" AND cd.lieu_mission!="' . mysql_real_escape_string($ligne->lieu_mission) . '"
                                        AND cd.fin_mission >= "' . mysql_real_escape_string($debut) . '" AND cd.fin_mission <= "' . mysql_real_escape_string($fin) . '"
                                        AND cd.fin_mission = (SELECT max(fin_mission) FROM contrat_delegation WHERE Id_ressource=cd.Id_ressource
                                        AND fin_mission >= "' . mysql_real_escape_string($debut) . '" AND fin_mission <= "' . mysql_real_escape_string($fin) . '" AND lieu_mission=cd.lieu_mission)
                                        ORDER BY cd.Id_ressource,cd.fin_mission')->fetchRow();

            if ($ligne2->id_ressource) {
                $afficher = true;
                $ressource = RessourceFactory::create($ligne->type_ressource, $ligne->id_ressource, null);
                if ($_POST['agence'] && ($_POST['agence'] != $ressource->getAgency())) {
                    $afficher = false;
                }
                if ($afficher) {
                    $html .= '
                                    <tr>
                                        <td>' . $ligne->id_contrat_delegation . '</td>
                                                    <td>' . $ligne->createur . '</td>
                                            <td>' . $ressource->getName() . '</td>
                                                    <td>' . Agence::getLibelle($ressource->getAgency()) . '</td>
                                            <td>' . FormatageDate($ligne->debut_mission) . '</td>
                                            <td>' . FormatageDate($ligne->fin_mission) . '</td>
                                            <td>' . $ligne->lieu_mission . '</td>
                                    </tr>';
                }
            }
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Vérifie la cohérence des fichiers des candidatures stockés dans l'arborescence.
     *
     * @return String
     */
    public static function checkApplicantFiles() {
        $db = connecter();
        $result = $db->query('SELECT Id_candidature,lien_cv,lien_cvp,lien_lm FROM candidature
                WHERE lien_cv!="" or lien_cvp!="" or lien_lm!=""');
        while ($ligne = $result->fetchRow()) {
            if ($ligne->lien_cv && !file_exists(CV_DIR . $ligne->lien_cv)) {
                echo 'Candidature : ' . $ligne->id_candidature . ', Le fichier : ' . $ligne->lien_cv . ' n\'existe pas. <br />';
            }
            if ($ligne->lien_cvp && !file_exists(CV_DIR . $ligne->lien_cvp)) {
                echo 'Candidature : ' . $ligne->id_candidature . ', Le fichier : ' . $ligne->lien_cvp . ' n\'existe pas. <br />';
            }
            if ($ligne->lien_lm && !file_exists(LM_DIR . $ligne->lien_lm)) {
                echo 'Candidature : ' . $ligne->id_candidature . ', Le fichier : ' . $ligne->lien_lm . ' n\'existe pas. <br />';
            }
        }
        $dir = opendir(CV_DIR);
        while ($File = readdir($dir)) {
            if ($File != '.' && $File != '..') {
                if ($db->query('SELECT Id_candidature FROM candidature WHERE lien_cv ="' . mysql_real_escape_string($File) . '" OR lien_cvp="' . mysql_real_escape_string($File) . '"')->fetchRow()->id_candidature == '') {
                    echo 'Le fichier "' . $File . '" est associé à aucun candidat. <br />';
                }
            }
        }
        closedir($dir);
        $dir = opendir(LM_DIR);
        while ($File = readdir($dir)) {
            if ($File != '.' && $File != '..') {
                if ($db->query('SELECT Id_candidature FROM candidature WHERE lien_lm ="' . mysql_real_escape_string($File) . '"')->fetchRow()->id_candidature == '') {
                    echo 'Le fichier "' . $File . '" est associé à aucun candidat. <br />';
                }
            }
        }
    }

    /**
     * Envoi de mail concenrnant la bonne ou mauvais réalisation des calculs concernant le CA prévisionnel
     *
     */
    public static function mailAlertScriptCAPrevisionnel() {
        $db = connecter();
        $res = $db->query('SELECT sum(ca_facture+ca_provisoire+ca_regule) as ca_facture,sum(ca_commande) as ca_commande FROM budget');
        $ligne = $res->fetchRow();
        if ($ligne->ca_facture == 0 || $ligne->ca_commande == 0) {
            $subject = 'Erreur Traitement CA Prévisionnel !';
            $dest = 'anthony.anne@proservia.fr';
            $text = 'Bonjour,
                       
Le Traitement du CA Prévisionnel a rencontré une erreur.';
            $hdrs = array(
                'From' => 'dsi-support374@proservia.fr',
                'Subject' => $subject
            );
            $crlf = "\n";
            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);
            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);
            $send = $mail_object->send($dest, $hdrs, $body);
            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
        } else {
            $subject = 'Traitement CA Prévisionnel OK !';
            $dest = 'anthony.anne@proservia.fr';
            $text = 'Bonjour,
                       
Le Traitement du CA Prévisionnel s\'est bien déroulé.';
            $hdrs = array(
                'From' => 'dsi-support374@proservia.fr',
                'Subject' => $subject
            );
            $crlf = "\n";
            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);
            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);
            $send = $mail_object->send($dest, $hdrs, $body);
            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
        }
    }

    /**
     * Optimisation des tables des bases de données
     *
     */
    public static function optimizeTable() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $result = $db->query('SHOW TABLES');
            while ($ligne = $result->fetchRow()) {
                $table1 = strtolower('tables_in_agc_' . $l->libelle);
                $db->query('OPTIMIZE TABLE ' . $ligne->$table1 . '');
            }
        }
    }

    /**
     * Récupère les ODM ayant été validés sur l'OSC
     *
     */
    public static function getValidateODM() {
        $connexion = ssh2_connect(OSC_SRV, OSC_PORT);
        if ($connexion) {
            if (ssh2_auth_pubkey_file($connexion, 'infoint', '/home/infoint/.ssh/id_dsa.pub', '/home/infoint/.ssh/id_dsa')) {
                $db = connecter();
                $res = Bdd::getDatabases();
                foreach ($res as $l) {
                    if ($_SESSION['societe'] == 'PROSERVIA' || $_SESSION['societe'] == 'NETLEVEL') {
                        $_SESSION['societe'] = $l;
                        $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($l);
                        if ($handle = opendir(FILES_ODM_WAITING . $_SESSION['societe'] . '/')) {
                            $db = connecter();
                            while (false !== ($file = readdir($handle))) {
                                if ($file != "." && $file != "..") {
                                    if (ssh2_scp_recv($connexion, '/var/www/osc/odm-valide/' . $_SESSION['societe'] . '/' . $file, FILES_ODM_VALIDATE . $_SESSION['societe'] . '/' . $file)) {
                                        list($id_odm) = explode('.', $file);
                                        $db->query('UPDATE ordre_mission SET envoye = 1, retour = 1, date_retour = "' . mysql_real_escape_string(DATE) . '" WHERE Id_ordre_mission = ' . mysql_real_escape_string($id_odm));
                                        unlink(FILES_ODM_WAITING . $_SESSION['societe'] . '/' . $file);

                                        $odm = new OrdreMission($id_odm, array());
                                        $ressource = new Salarie($odm->Id_ressource, array());
                                        $responsable = new Utilisateur($odm->responsable, array());
                                        $createur = new Utilisateur($odm->createur, array());

                                        $hdrs = array(
                                            'From' => 'osc@proservia.fr',
                                            'Subject' => 'Ordre de mission validé'
                                        );
                                        $crlf = "\n";

                                        $mime = new Mail_mime($crlf);
                                        $mime->setHTMLBody('Bonjour <br /><br />
                                                                    L\'ordre de mission de ' . $ressource->prenom . ' ' . $ressource->nom . ' a été validé. Voici un lien vers son ordre de mission validé.<br />
                                                                    <a href=\'' . BASE_URL . 'odm-valide/' . $_SESSION['societe'] . '/' . $id_odm . '.pdf\'>Lien vers l\'ordre de mission</a><br /><br />

                                                                    Cordialement<br />
                                                                    ');

                                        $body = $mime->get();
                                        $hdrs = $mime->headers($hdrs);

                                        // Create the mail object using the Mail::factory method
                                        $params['host'] = SMTP_HOST;
                                        $params['port'] = SMTP_PORT;
                                        $mail_object = Mail::factory('smtp', $params);
                                       
                                        $send = $mail_object->send(array($responsable->mail, $createur->mail), $hdrs, $body);

                                        if (PEAR::isError($send)) {
                                            print($send->getMessage());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Envoi d'un mail de relance pour la validation des ODM
     *
     */
    public static function reopeningODM() {
        $db = connecter();
        $db_cegid = connecter_cegid();
        $res = $db->query('SELECT Id_ordre_mission, relance, Id_ressource, date_creation, date_envoi FROM ordre_mission odm WHERE odm.retour = 0 AND odm.envoye = 1 AND (odm.relance < 2 OR odm.relance IS NULL)');

        while ($l = $res->fetchRow()) {
            $date_sortie = $db_cegid->queryOne('SELECT PSA_DATESORTIE FROM RESSOURCE INNER JOIN SALARIES ON ARS_SALARIE=SALARIES.PSA_SALARIE WHERE ARS_RESSOURCE = "' . $l->id_ressource . '"');
            if($date_sortie < DATE && $date_sortie != '1900-01-01 00:00:00') {
                $db->query('UPDATE ordre_mission odm SET odm.relance = 2 WHERE odm.Id_ordre_mission = "' . mysql_real_escape_string($l->id_ordre_mission) . '"');
                continue;
            }
           
            $creationDatePlusOneMonth = strtotime(date("Y-m-d", strtotime($l->date_envoi)) . " +1 month");
            $creationDatePlusTwoMonth = strtotime(date("Y-m-d", strtotime($l->date_envoi)) . " +2 month");
           
            $ressource = RessourceFactory::create('SAL', $l->id_ressource, null);
            $req = new HTTP_Request(OSC_URL . 'script/getResourceMail.php');
            $req->setMethod(HTTP_REQUEST_METHOD_POST);
            $req->addPostData('code_ressource', $ressource->code_ressource);
            $req->addPostData('societe', $_SESSION['societe']);

            $resp = $req->sendRequest();
            if (!PEAR::isError($resp)) {
                if ($req->getResponseCode() == 200) {
                    $mail = $req->getResponseBody();
                }
            } else {
                echo $resp->getMessage();
            }
            if (($l->relance == 0 || $l->relance == '') && DATE == date('Y-m-d', $creationDatePlusOneMonth) && $mail != 'NULL') {
                $data .= DATE . ';' . $l->id_ordre_mission . ';' . $l->relance . ';' . $ressource->code_ressource . ';' . $l->id_ressource . ';' . $l->date_creation . ';' . $mail . '; ' . PHP_EOL;
                $odm = new OrdreMission($l->id_ordre_mission, array());
                $ressource = RessourceFactory::create('SAL', $odm->Id_ressource, null);
                $responsable = new Utilisateur($odm->responsable, array());
                $createur = new Utilisateur($odm->createur, array());

                $hdrs = array(
                    'From' => $createur->mail,
                    'Subject' => 'Relance validation ordre de mission',
                    'To' => $mail
                );
                $crlf = "\n";

                $mime = new Mail_mime($crlf);
                $mime->setHTMLBody('Bonjour ' . $ressource->prenom . ',<br /><br />
                    Nous vous rappelons qu\'un Ordre De Mission détaillant votre intervention est en attente de validation sur l\'OSC.<br />
                    Nous vous remercions de bien vouloir le valider rapidement en cliquant ici : <a href="' . OSC_URL . '/membre/index.php?a=consulterODM&Id=' . $ressource->code_ressource . '">Lien vers l\'ordre de mission</a><br /><br />
                    Pour tout complément d\'information, merci de vous rapprocher de votre responsable commercial.<br /><br />
                    Cordialement<br />
                ');

                $body = $mime->get();
                $hdrs = $mime->headers($hdrs);

                // Create the mail object using the Mail::factory method
                $params['host'] = SMTP_HOST;
                $params['port'] = SMTP_PORT;
                $mail_object = Mail::factory('smtp', $params);

                $send = $mail_object->send($mail, $hdrs, $body);
                if (PEAR::isError($send))
                    print($send->getMessage());
                else {
                    $db->query('UPDATE ordre_mission odm SET odm.relance = 1 WHERE odm.Id_ordre_mission = "' . mysql_real_escape_string($l->id_ordre_mission) . '"');
                }
            } elseif ($l->relance == 1 && DATE == date('Y-m-d', $creationDatePlusTwoMonth) && $mail != 'NULL') {
                $data .= DATE . ';' . $l->id_ordre_mission . ';' . $l->relance . ';' . $ressource->code_ressource . ';' . $l->id_ressource . ';' . $l->date_creation . ';' . $mail . '; ' . PHP_EOL;
                $odm = new OrdreMission($l->id_ordre_mission, array());
                $ressource = RessourceFactory::create('SAL', $odm->Id_ressource, null);
                $responsable = new Utilisateur($odm->responsable, array());
                $createur = new Utilisateur($odm->createur, array());
                $to = $ressource->getCRHMail();
                if ($to == null) {
                    $to = Agence::getRHrecipientMail($ressource->getAgency());
                }
                    
                $hdrs = array(
                    'From' => $createur->mail,
                    'Subject' => 'Relance validation ordre de mission',
                    'To' => array_merge(array($mail, $responsable->mail), $to)
                );
                $crlf = "\n";
               
                $mime = new Mail_mime($crlf);
                $mime->setHTMLBody('Bonjour ' . $ressource->prenom . ',<br /><br />
                    Nous vous rappelons qu\'un Ordre De Mission détaillant votre intervention est en attente de validation sur l\'OSC.<br />
                    Nous vous remercions de bien vouloir le valider rapidement en cliquant ici : <a href="' . OSC_URL . '/membre/index.php?a=consulterODM&Id=' . $ressource->code_ressource . '">Lien vers l\'ordre de mission</a><br /><br />
                    Pour tout complément d\'information, merci de vous rapprocher de votre responsable commercial.<br /><br />
                    Cordialement<br />
                ');

                $body = $mime->get();
                $hdrs = $mime->headers($hdrs);

                // Create the mail object using the Mail::factory method
                $params['host'] = SMTP_HOST;
                $params['port'] = SMTP_PORT;
                $mail_object = Mail::factory('smtp', $params);

                $send = $mail_object->send(array_merge(array($mail, $responsable->mail), $to), $hdrs, $body);
                if (PEAR::isError($send))
                    print($send->getMessage());
                else {
                    $db->query('UPDATE ordre_mission odm SET odm.relance = 2 WHERE odm.Id_ordre_mission = "' . mysql_real_escape_string($l->id_ordre_mission) . '"');
                }
            }
        }

        $filename = '/var/log/relance_odm.txt';
        if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'a')) {
                echo "Impossible d'ouvrir le fichier ($filename)";
                exit;
            }
            if (fwrite($handle, $data) === FALSE) {
                echo "Impossible d'écrire dans le fichier ($filename)";
                exit;
            }
            fclose($handle);
        }
    }

    /**
     * Envoi d'un mail à l'ADV contenant la liste des ODM non validés depuis 2 mois
     *
     */
    public static function listingUnvalidatedODM() {
        $db = connecter();
        $creationDateMinusTwoMonth = strtotime(DATE . " -2 month");
        $res = $db->query('SELECT Id_ordre_mission, date_envoi, Id_ressource, Id_agence FROM ordre_mission odm
                            WHERE odm.retour = 0 AND date_envoi != "0000-00-00"
                            AND date_envoi BETWEEN "2011-01-01" AND "' . date('Y-m-d', $creationDateMinusTwoMonth) . '"');

        $cd = new ContratDelegation(null, null);
        $list = array();

        $i = 1;
        while ($l = $res->fetchRow()) {
            $currentArray = array();
            $ressource = RessourceFactory::create('SAL', $l->id_ressource, null);
            $req = new HTTP_Request(OSC_URL . 'script/getResourceMail.php');
            $req->setMethod(HTTP_REQUEST_METHOD_POST);
            $req->addPostData('code_ressource', $ressource->code_ressource);
            $req->addPostData('societe', $_SESSION['societe']);

            if (!PEAR::isError($req->sendRequest())) {
                if ($req->getResponseCode() == 200) {
                    $mail = $req->getResponseBody();
                }
            }

            array_push($currentArray, $l->id_ordre_mission);

            array_push($currentArray, $l->id_ressource);

            array_push($currentArray, $ressource->nom . ' ' . $ressource->prenom);

            array_push($currentArray, $l->date_envoi);

            array_push($currentArray, $mail);

            $list[$l->id_agence][0] = array('Id ordre mission', 'Id ressource', 'nom', 'date envoi', 'mail');
            $list[$l->id_agence][$i] = $currentArray;
            $i++;
        }

        foreach ($list as $agence => $odm) {
            $file = URL_TMP . 'odm_non_valide_' . $agence . '.csv';
            $fp = fopen($file, 'w');
            foreach ($list[$agence] as $fields) {
                fputcsv($fp, $fields, ';');
            }
            fclose($fp);

            $dest = $cd->getDestinataireMail($agence, 1);
            $lAgence = Agence::getLibelle($agence);
            $hdrs = array(
                'From' => 'agc@proservia.fr',
                'Subject' => 'Listing ODM non validé ' . $lAgence,
                'To' => $dest
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->setTXTBody('Vous trouverez joint un listing des ordres de mission non validé depuis 2 mois pour l\'agence de ' . $lAgence . '.');
            $mime->addAttachment($file, 'application/csv');

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send($dest, $hdrs, $body);

            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
            unlink($file);
        }
    }

    /**
     * Mise à jour des CD avec l'identifiant de ressource salarié
     *
     */
    public static function updateCDFromResource() {
        $db = connecter();
        $db_cegid = connecter_cegid();
        $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_SESSION['societe']);
        $cegid_databases = $_SESSION['cegid_databases'];
       
        /* POUR LES CANDIDATS AGC */
        $res = $db->query('SELECT DISTINCT cd.Id_contrat_delegation, cd.Id_ressource, r.securite_sociale FROM contrat_delegation cd INNER JOIN ressource r ON r.Id_ressource = cd.Id_ressource WHERE Id_ressource_sal = ""');
        while ($l = $res->fetchRow()) {
            if (is_numeric($l->id_ressource)) {
                $i = 0;
                foreach ($cegid_databases as $cegid_database) {
                    $requete .= ($i != 0) ? ' UNION' : '';
                    $requete .= ' SELECT ARS_RESSOURCE FROM ' . $cegid_database . '.DBO.SALARIES INNER JOIN ' . $cegid_database . '.DBO.RESSOURCE ON ' . $cegid_database . '.DBO.RESSOURCE.ARS_SALARIE=' . $cegid_database . '.DBO.SALARIES.PSA_SALARIE WHERE PSA_NUMEROSS = replace("' . $l->securite_sociale . '"," ","")';
                    $i++;
                }
                $id_cegid = $db_cegid->query($requete)->fetchOne();
                if ($id_cegid) {
                    $db->query('UPDATE contrat_delegation SET Id_ressource_sal = "' . $id_cegid . '" WHERE Id_contrat_delegation = ' . $l->id_contrat_delegation);
                }
            }
        }
       
        //Candidats Taleo
        $res = $db->query('SELECT DISTINCT cd.Id_contrat_delegation, cd.Id_ressource, cd.type_ressource FROM contrat_delegation cd WHERE type_ressource = "CAN_TAL" AND Id_ressource_sal = ""');
        while ($l = $res->fetchRow()) {
            if (is_numeric($l->id_ressource)) {
                $ressource = RessourceFactory::create($l->type_ressource, $l->id_ressource, null);
                $i = 0;
                $requete = '';
                foreach ($cegid_databases as $cegid_database) {
                    $requete .= ($i != 0) ? ' UNION' : '';
                    $requete .= ' SELECT ARS_RESSOURCE FROM ' . $cegid_database . '.DBO.SALARIES INNER JOIN ' . $cegid_database . '.DBO.RESSOURCE ON ' . $cegid_database . '.DBO.RESSOURCE.ARS_SALARIE=' . $cegid_database . '.DBO.SALARIES.PSA_SALARIE WHERE PSA_NUMEROSS LIKE "' . str_replace(' ', '', $ressource->securite_sociale)  . '%"';
                    $i++;
                }
                $id_cegid = $db_cegid->query($requete)->fetchOne();
                if ($id_cegid) {
                    $db->query('UPDATE contrat_delegation SET Id_ressource_sal = "' . $id_cegid . '" WHERE Id_contrat_delegation = ' . $l->id_contrat_delegation);
                }
            }
        }
    }

    /**
     * Recharge les droits sur les contrat délégations
     */
    public static function reloadRights() {
        $db = connecter();
        $res = Bdd::getDatabases();
        foreach ($res as $l) {
            $_SESSION['societe'] = $l;
            $db = connecter();
            $sfClient = new SFClient();
            try {
                $opportunities = $sfClient->query('
                        SELECT Id, ID_AGC__c, Name, Owner.Email, droits_CD_AGC__c, (SELECT User.Email, TeamMemberRole FROM OpportunityTeamMembers) FROM Opportunity
                ');
            } catch (Exception $e) {
                print_r($sfClient->getLastRequest());
                echo $e->faultstring;
            }

            $db->query('TRUNCATE droit_opportunite');

            !$done = false;
            while (!$done) {
                $nOp = count($opportunities->records);
                $i = 0;
                while ($i < $nOp) {
                    $j = 0;
                    $prop = explode('@', $opportunities->records[$i]->Owner->Email);
                    $db->query('INSERT INTO droit_opportunite SET Id_opportunite = "' . $opportunities->records[$i]->Id . '", reference_affaire = "' . $opportunities->records[$i]->ID_AGC__c . '", type = "proprietaire", utilisateur = "' . $prop[0] . '"');

                    $team = array();
                    if ($opportunities->records[$i]->OpportunityTeamMembers != null) {
                        foreach ($opportunities->records[$i]->OpportunityTeamMembers->records as $v) {
                            $teamMember = explode('@', $v->User->Email);
                            if($v->TeamMemberRole == 'Administration des Ventes')
                                $db->query('INSERT INTO droit_opportunite SET Id_opportunite = "' . $opportunities->records[$i]->Id . '", reference_affaire = "' . $opportunities->records[$i]->ID_AGC__c . '", type = "equipe_adv", utilisateur = "' . $teamMember[0] . '"');
                            else
                                $db->query('INSERT INTO droit_opportunite SET Id_opportunite = "' . $opportunities->records[$i]->Id . '", reference_affaire = "' . $opportunities->records[$i]->ID_AGC__c . '", type = "equipe_vente", utilisateur = "' . $teamMember[0] . '"');
                        }
                    }

                    $dCD = explode(';', $opportunities->records[$i]->droits_CD_AGC__c);
                    $nCD = count($dCD);
                    while ($j < $nCD) {
                        if ($dCD[$j] != null) {
                            $cd = explode('@', $dCD[$j]);
                            $db->query('INSERT INTO droit_opportunite SET Id_opportunite = "' . $opportunities->records[$i]->Id . '", reference_affaire = "' . $opportunities->records[$i]->ID_AGC__c . '", type = "equipe_cd", utilisateur = "' . $cd[0] . '"');
                        }
                        $j++;
                    }
                    $i++;
                }
                if ($opportunities->done != true) {
                    try {
                        $opportunities = $sfClient->queryMore($opportunities->queryLocator);
                    } catch (Exception $e) {
                        $done = true;
                        print_r($sfClient->getLastRequest());
                        echo $e->faultstring;
                    }
                } else {
                    $done = true;
                }
            }

            $sfClient->logout();
        }
    }
   
    /**
     * Export des lignes de frais des contrats délégation pour intégration dans Notilus
     *
     */
    public static function exportNotilus() {
        $db = connecter();
        $db_cegid = connecter_cegid();
        $db_cegid->query('TRUNCATE TABLE [Z_TRAITEMENT].[dbo].[NOT_FILTRE_NATURE]');
        $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_SESSION['societe']);
        $cegid_databases = $_SESSION['cegid_databases'];
        $cegid_databases_trig = array ('PROSERVIA' => 'PRO', 'DAMCONSULT' => 'DCO', 'TAPFIN' => 'TAP', 'TIMARANCE' => 'TIM',
           'DAMILOIT' => 'DAM', 'EXPERISIT' => 'EXP', 'OVIALIS_EXP' => 'OVS', 'FINATEL' => 'FIN');
       
       
        $result = $db->query(
            'SELECT cd.Id_contrat_delegation, cd.type_ressource, cd.Id_ressource, cd.Id_ressource_sal, cdi.Id_indemnite, i.Id_population FROM cd_indemnite cdi
            INNER JOIN contrat_delegation cd ON cd.Id_contrat_delegation = cdi.Id_contrat_delegation
            INNER JOIN indemnite i ON i.Id_indemnite = cdi.Id_indemnite
            WHERE (((cd.debut_mission BETWEEN CURDATE() - INTERVAL 150 DAY AND CURDATE())
            OR (cd.fin_mission BETWEEN CURDATE() - INTERVAL 150 DAY AND CURDATE())
            OR (cd.fin_mission BETWEEN CURDATE() AND CURDATE() + INTERVAL 15 DAY)
            OR (cd.debut_mission < CURDATE() AND cd.fin_mission > CURDATE())))
            AND (Id_affaire != "")
            AND cd.statut = "V"'
        );
       
        $a = array();
        while ($ligne = $result->fetchRow()) {
            if($ligne->type_ressource == 'SAL') {
                if(!array_key_exists($ligne->id_ressource, $a)) {
                    $a[$ligne->id_ressource] = $ligne->id_ressource;
                }
            }
            else {
                if(!array_key_exists($ligne->id_ressource, $a)) {
                    $a[$ligne->id_ressource_sal] = $ligne->id_ressource_sal;
                }
            }
        }

        $whereRes = ' (';
        foreach ($a as $id_ressource => $value) {
            $whereRes .= '\'' . $id_ressource . '\',';
        }
        $whereRes = rtrim($whereRes, ",") . ')';
       
        $i = 0;
        foreach ($cegid_databases as $cegid_database) {
            $requete .= ($i != 0) ? ' UNION' : '';
            $requete .= ' SELECT \'' . $cegid_database . '\' AS SOCIETE, PSA_SALARIE, PSA_DATESORTIE, ARS_RESSOURCE
                FROM ' . $cegid_database . '.DBO.RESSOURCE
                LEFT JOIN ' . $cegid_database . '.DBO.SALARIES ON ars_salarie=' . $cegid_database . '.DBO.salaries.psa_salarie
                WHERE ARS_RESSOURCE IN ' . $whereRes . '
                AND (PSA_DATESORTIE = \'1900-01-01 00:00:00.000\' OR PSA_DATESORTIE > dateadd(month,-3,(getdate())))';
            $i++;
        }

        $resulCegid =  $db_cegid->query($requete);
        $aSal = array();
        while ($ligne = $resulCegid->fetchRow()) {
            $aSal[$ligne->ars_ressource] = array('societe' => $ligne->societe, 'code_salarie' => $ligne->psa_salarie, 'date_sortie' => $ligne->psa_datesortie);
        }

        $result->seek(0);
        while ($ligne = $result->fetchRow()) {
            if($ligne->type_ressource == 'SAL') {
                $societe = $aSal[$ligne->id_ressource]['societe'];
                $code_salarie = $aSal[$ligne->id_ressource]['code_salarie'];
                $date_sortie = $aSal[$ligne->id_ressource]['date_sortie'];
            }
            else {
                $societe = $aSal[$ligne->id_ressource_sal]['societe'];
                $code_salarie = $aSal[$ligne->id_ressource_sal]['code_salarie'];
                $date_sortie = $aSal[$ligne->id_ressource_sal]['date_sortie'];
            }
            $code_salarie_def = $cegid_databases_trig[$societe] . substr($code_salarie, 3);
           
           
            if($code_salarie != '' && $ligne->id_population != ''  && $date_sortie != '') {
                $db_cegid->query('
                    INSERT INTO [Z_TRAITEMENT].[dbo].[NOT_FILTRE_NATURE] ([code_personne],[code_population])
                    VALUES("' . $code_salarie_def . '","' . $ligne->id_population . '")');
            }
            else
                continue;
        }        
       
        $result = $db->query(
            'SELECT cd.Id_contrat_delegation, cd.type_ressource, cd.Id_ressource, cd.Id_ressource_sal, cdi.Id_indemnite, i.Id_population FROM cd_indemnite cdi
            INNER JOIN contrat_delegation cd ON cd.Id_contrat_delegation = cdi.Id_contrat_delegation
            INNER JOIN indemnite i ON i.Id_indemnite = cdi.Id_indemnite
            WHERE Id_affaire = "" AND cd.statut = "V"
            AND  cd.Id_contrat_delegation=(SELECT max(cd2.Id_contrat_delegation) FROM contrat_delegation cd2 WHERE cd2.Id_ressource=cd.Id_ressource AND cd2.Id_affaire = "" AND cd2.statut = "V")'
        );

        $a = array();
        while ($ligne = $result->fetchRow()) {
            if($ligne->type_ressource == 'SAL') {
                if(!array_key_exists($ligne->id_ressource, $a)) {
                    $a[$ligne->id_ressource] = $ligne->id_ressource;
                }
            }
            else {
                if(!array_key_exists($ligne->id_ressource, $a)) {
                    $a[$ligne->id_ressource_sal] = $ligne->id_ressource_sal;
                }
            }
        }

        $whereRes = ' (';
        foreach ($a as $id_ressource => $value) {
            $whereRes .= '\'' . $id_ressource . '\',';
        }
        $whereRes = rtrim($whereRes, ",") . ')';
       
        $i = 0;
        foreach ($cegid_databases as $cegid_database) {
            $requete .= ($i != 0) ? ' UNION' : '';
            $requete .= ' SELECT \'' . $cegid_database . '\' AS SOCIETE, PSA_SALARIE, PSA_DATESORTIE, ARS_RESSOURCE
                FROM ' . $cegid_database . '.DBO.RESSOURCE
                LEFT JOIN ' . $cegid_database . '.DBO.SALARIES ON ars_salarie=' . $cegid_database . '.DBO.salaries.psa_salarie
                WHERE ARS_RESSOURCE IN ' . $whereRes . '
                AND (PSA_DATESORTIE = \'1900-01-01 00:00:00.000\' OR PSA_DATESORTIE > dateadd(month,-3,(getdate())))';
            $i++;
        }

        $resulCegid =  $db_cegid->query($requete);
        $aSal = array();
        while ($ligne = $resulCegid->fetchRow()) {
            $aSal[$ligne->ars_ressource] = array('societe' => $ligne->societe, 'code_salarie' => $ligne->psa_salarie, 'date_sortie' => $ligne->psa_datesortie);
        }

        $result->seek(0);
        while ($ligne = $result->fetchRow()) {
            if($ligne->type_ressource == 'SAL') {
                $societe = $aSal[$ligne->id_ressource]['societe'];
                $code_salarie = $aSal[$ligne->id_ressource]['code_salarie'];
                $date_sortie = $aSal[$ligne->id_ressource]['date_sortie'];
            }
            else {
                $societe = $aSal[$ligne->id_ressource_sal]['societe'];
                $code_salarie = $aSal[$ligne->id_ressource_sal]['code_salarie'];
                $date_sortie = $aSal[$ligne->id_ressource_sal]['date_sortie'];
            }
            $code_salarie_def = $cegid_databases_trig[$societe] . substr($code_salarie, 3);
           
           
            if($code_salarie != '' && $ligne->id_population != ''  && $date_sortie != '') {
                $db_cegid->query('
                    INSERT INTO [Z_TRAITEMENT].[dbo].[NOT_FILTRE_NATURE] ([code_personne],[code_population])
                    VALUES("' . $code_salarie_def . '","' . $ligne->id_population . '")');
            }
            else
                continue;
        }
    }
   
    /**
     * Alerte des CD arrivant à terme avec avertissement du créateur et propriétaire de l'opportunité
     *
     */
    public static function alertRenewCD() {
        $db = connecter();
        $res = $db->query('
            SELECT * FROM contrat_delegation
            WHERE fin_mission = (DATE(NOW()) + INTERVAL 1 MONTH) AND statut = "V"
        ');
       
        $mailArray = array();
        while ($l = $res->fetchRow()) {
            $affaire = new Opportunite($l->id_affaire, null);
            $dates = $affaire->getRangeDates();
           
            if(DateTime::createFromFormat('d-m-Y', $dates['date_max']) <= DateTime::createFromFormat('Y-m-d', $l->fin_mission)) {
                $cd = new ContratDelegation($l->id_contrat_delegation, null);
                $createur = new Utilisateur($cd->createur, null);
               
                $mailArray[$createur->mail][] = array('contrat_delegation' => $cd, 'affaire' => $affaire);
                $mailArray[$affaire->mail_proprietaire][] = array('contrat_delegation' => $cd, 'affaire' => $affaire);
            }
        }

        foreach ($mailArray as $key => $ligne) {
            $mailCD = '';
            foreach ($ligne as $cd) {
                $mailCD .= '- <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $cd['contrat_delegation']->Id_contrat_delegation . '">' . $cd['contrat_delegation']->intitule . '</a> pour l\'opportunité <a href="' . SF_URL . $cd['contrat_delegation']->Id_affaire . '">' . $cd['contrat_delegation']->reference_affaire . '</a><br />';
            }
           
            $hdrs = array(
                'From' => 'noreply@salesforce.com',
                'Subject' => 'INFORMATION - Liste des contrats délégation arrivant à échéance',
                'To' => $key
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->addHTMLImage("../ui/images/logo.png", "image/png");
            $mime->addHTMLImage("../ui/images/alerte_cd.jpg", "image/jpg");
            $mime->setHTMLBody('
            <html><body>
            <img src="logo.png" /><br /><br />
            <img src="alerte_cd.jpg" /><br /><br />
            Bonjour,<br /><br />

            Nous vous informons que des contrats délégation arrivent à échéance dans 30 jours sans opportunités prolongées.<br /><br />

            Nous vous invitons à faire le nécessaire en prolongeant les opportunités qui doivent l\'être.<br /><br />

            Une fois prolongées, une duplication automatique des contrats délégation sera réalisée.<br /><br />

            Ci-joint la liste des contrats délégation à échéance avec la référence de l\'opportunité :<br />
            ' . $mailCD . '<br />

            Cordialement,<br />
            L\'équipe support
            </body></html>');

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send($key, $hdrs, $body);
            $send = $mail_object->send('consultation.facturation@proservia.fr', $hdrs, $body);
            $send = $mail_object->send('mathieu.perrin@proservia.fr', $hdrs, $body);
            $data .= DATE . ';' . $l->id_cd . ';' . $id_dcd . ';' . $createur->mail . '; ' . PHP_EOL;
        }
    }
   
	/**
     * Renouvellement des CD arrivant à terme avec avertissement du créateur et propriétaire
     *
     */
    public static function renewCD() {
        $db = connecter();
        //On vérifie les CD qui finisse de J -15 à J+30
        $res = $db->query('
            SELECT * FROM contrat_delegation 
            WHERE (fin_mission <= (DATE(NOW()) + INTERVAL 1 MONTH) AND fin_mission >= (DATE(NOW()) - INTERVAL 15 DAY))
            AND statut = "V" AND agence != "PWS"
        ');
        
        $mailArray = array();
        while ($l = $res->fetchRow()) {
            if(is_numeric($l->id_affaire)) continue;
            $cd = new ContratDelegation($l->id_contrat_delegation, null);
            //var_dump($cd);
            $affaire = new Opportunite($l->id_affaire, null);
            //on ne duplique qu'un CD finissant en même temps que l'oppy originel
            if (DateTime::createFromFormat('Y-m-d', $affaire->date_fin_commande) == DateTime::createFromFormat('d-m-Y', $cd->fin_mission)) {//substr(,0,10)
                //on vérifie qu'il n'y a pas déjà eu duplication
                $duplicate = $cd->getIdDuplicated();
                //Nous cherchons si l'oppy se poursuit sur la période suivante
                $oppies = $affaire->getLinkedOpportunities();
                $nextOppy = false;
                
                foreach ($oppies as $oppy) {
                    if (DateTime::createFromFormat('Y-m-d', $oppy->Date_debut_de_commande__c) > DateTime::createFromFormat('Y-m-d', $affaire->date_fin_commande) && DateTime::createFromFormat('Y-m-d', $oppy->Date_debut_de_commande__c) < (DateTime::createFromFormat('Y-m-d', $affaire->date_fin_commande)->modify('+1 month'))
                        && ($oppy->StageName == 'Accord client' || $oppy->StageName == 'Signature client' )) {
                        $nextOppy = $oppy;
                    }
                }
                
                if($duplicate == 0 && $nextOppy != false) {
                    if (ContratDelegation::cdExistsWithRessourceOnPeriod($cd->Id_ressource, $nextOppy->Date_debut_de_commande__c, $nextOppy->Date_fin_de_commande__c) == false) {//on vérifie qu'il n' y a pas déjà un CD sur la ressource et la période
                        $createur = new Utilisateur($cd->createur, null);
                        //On duplique le CD
                        $idDuplicatedCD = $cd->duplicate($nextOppy->Id, true);
                        
                        $mailArray[$createur->mail][] = array('contrat_delegation' => $cd, 'affaire' => $affaire, 'id_duplicated_cd' => $idDuplicatedCD);
                        //$mailArray[$affaire->mail_proprietaire][] = array('contrat_delegation' => $cd, 'affaire' => $affaire, 'id_duplicated_cd' => $idDuplicatedCD);                        
                                    
                        // Tableau des destinataires
                        // On récupère les destinataires : tous les rédacteurs potentiels de l'affaire (propriétaire oppy + rédacteurs contrats délegs sur l'oppy)
                        $resRedacteurs = $db->query('
                            SELECT u.id_utilisateur, u.mail FROM utilisateur u
                            JOIN droit_opportunite do ON u.id_utilisateur = do.utilisateur
                            WHERE do.Id_opportunite = "'.$l->id_affaire.'" AND do.type != "equipe_adv" 
                        ');
                        while ($rowDest = $resRedacteurs->fetchRow()) {
                            // Si le rédacteur n'est pas identique au créateur on ajoute dans le tableau de mail
                            if($rowDest->mail != $createur->mail){
                                $mailArray[$rowDest->mail][] = array('contrat_delegation' => $cd, 'affaire' => $affaire, 'id_duplicated_cd' => $idDuplicatedCD);
                            }
                        }

                        echo ' - Dupliqué'. '<br /> ';
                    }
                }
            }
        }

        //envoi des mails
        foreach ($mailArray as $key => $ligne) {
            $mailCD = '';
            foreach ($ligne as $cd) {
                $mailCD .= '- <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $cd['id_duplicated_cd'] . '">' . $cd['contrat_delegation']->intitule . '</a> pour l\'opportunité <a href="' . SF_URL . $cd['contrat_delegation']->Id_affaire . '">' . $cd['contrat_delegation']->reference_affaire . '</a><br />';
            }
            
            $hdrs = array(
                'From' => 'noreply@salesforce.com',
                'Subject' => 'INFORMATION - Liste des contrats délégation ayant été dupliqués',
                'To' => $key
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->addHTMLImage("../ui/images/logo.png", "image/png");
            $mime->addHTMLImage("../ui/images/duplication_cd.jpg", "image/jpg");
            $mime->setHTMLBody('
            <html><body>
            <img src="logo.png" /><br /><br />
            <img src="duplication_cd.jpg" /><br /><br />
            Bonjour,<br /><br />

            Nous vous informons que des contrats délégation ont été automatiquement dupliqués suite à des opportunités prolongées.<br /><br />

            Nous vous invitons à vérifier chacun de ces contrats délégation et de les transmettre à l\'Administration des Ventes pour validité finale.<br /><br />

            Ci-joint la liste des contrats délégation automatiquement dupliqués avec la référence de l\'opportunité :<br />
            ' . $mailCD . '<br />

            Cordialement,<br />
            L\'équipe support
            </body></html>');

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            // Envoi du mail
            $send = $mail_object->send($key, $hdrs, $body);
            
            //$send = $mail_object->send('consultation.facturation@proservia.fr', $hdrs, $body);
            $send = $mail_object->send('cron.etudedev.dsi@proservia.fr', $hdrs, $body);
            //$send = $mail_object->send('yannick.betou@proservia.fr', $hdrs, $body);
            $data .= DATE . ';' . $l->id_cd . ';' . $id_dcd . ';' . $createur->mail . '; ' . PHP_EOL;
        }

        $filename = '/var/log/duplication_cd.txt';
        if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'a')) {
                echo "Impossible d'ouvrir le fichier ($filename)";
                exit;
            }
            if (fwrite($handle, $data) === FALSE) {
                echo "Impossible d'écrire dans le fichier ($filename)";
                exit;
            }
            fclose($handle);
        }
    }

    /**
     * Récupération du matricule des collaborateurs à partir de Cegid : INUTILE POUR LE MOMENT
     *
     *//*
      public static function majCodeRessource() {
      $db  = connecter();
      $res = $db->query('SELECT * FROM societe');
      while ($l = $res->fetchRow()) {
      $i = $j = 0;
      $_SESSION['societe'] = $l->libelle;
      $db = connecter();
      $result = $db->query('SELECT Id_ressource, securite_sociale FROM ressource WHERE securite_sociale != ""');
      while ($ligne = $result->fetchRow()) {
      $db2 = connecter_cegid();
      $result2 = $db2->query('SELECT PSA_SALARIE FROM RESSOURCE
      LEFT JOIN SALARIES ON ars_salarie=salaries.psa_salarie
      LEFT JOIN DEPORTSAL ON pse_salarie=salaries.psa_salarie
      LEFT OUTER JOIN CHOIXCOD CC2 ON PSA_LIBELLEEMPLOI=CC2.CC_CODE AND CC2.CC_TYPE="PLE"
      LEFT OUTER JOIN COMMUN CO3 ON PSA_SITUATIONFAMIL=CO3.CO_CODE AND CO3.CO_TYPE="PSF"
      LEFT OUTER JOIN PAYS PY4 ON PSA_NATIONALITE=PY4.PY_PAYS
      LEFT OUTER JOIN CHOIXCOD CC5 ON PSA_LIBREPCMB1=CC5.CC_CODE AND CC5.CC_TYPE="PL1"
      WHERE PSA_NUMEROSS="'.str_replace(" ","",$ligne->securite_sociale).'"');

      $code_ressource = $result2->fetchOne();
      if($code_ressource != "") {
      $db->query('UPDATE ressource SET code_ressource = "'.$code_ressource.'" WHERE Id_ressource="'.$ligne->id_ressource.'"');
      $html .= 'La ressource n° '.$ligne->id_ressource.'  est liée à la ressource CEGID '.$code_ressource.'<br />';
      $i++;
      }
      $j++;
      }
      echo $_SESSION['societe'].' = '.$i.'/'.$j.', ';
      }
      echo '<br />'.$html;
      }
     */

    /**
     * Script de duplication de base de données AGC : ENCORE EN COURS DE DEV !!!!!
     *
     * @return String
     *//*      
      public static function dupliquerBase()
      {
      exec('mysqldump -u root AGC_PROSERVIA > AGC_PROSERVIA.sql');
      mysql_query("CREATE database `AGC_xxxx`");
      exec('mysql -u root AGC_xxxx < AGC_PROSERVIA.sql');
      exec('rm AGC_PROSERVIA');

      $tableatronquer = array(
      '0' => 'action',
      '1' => 'affaire',
      '2' => 'affaire_competence',
      '3' => 'affaire_exigence',
      '4' => 'affaire_langue',
      '5' => 'affaire_pole',
      '6' => 'analyse_commerciale',
      '7' => 'analyse_risque',
      '8' => 'annonce',
      '9' => 'budget',
      '10' => 'candidat_agence',
      '11' => 'candidat_annonce',
      '12' => 'candidat_typecontrat',
      '13' => 'candidature',
      '14' => 'cd_indemnite',
      '15' => 'contrat_delegation',
      '16' => 'date',
      '17' => 'decision',
      '18' => 'demande_changement',
      '19' => 'description',
      '20' => 'doc_formation',
      '21' => 'entretien',
      '22' => 'entretien_certification',
      '23' => 'entretien_competence',
      '24' => 'entretien_critere',
      '25' => 'entretien_langue',
      '26' => 'entretien_mobilite',
      '27' => 'env_couverture',
      '28' => 'env_pdt',
      '29' => 'env_reseaux',
      '30' => 'env_serveur',
      '31' => 'environnement',
      '32' => 'formateur',
      '33' => 'formateur_salle',
      '34' => 'historique_action_candidature',
      '35' => 'historique_candidature',
      '36' => 'historique_statut',
      '37' => 'listing_affaires',
      '38' => 'logistique',
      '39' => 'modif_candidat',
      '40' => 'ordre_mission',
      '41' => 'participant',
      '42' => 'periode_session',
      '43' => 'planning',
      '44' => 'planning_date',
      '45' => 'planning_session',
      '46' => 'proposition',
      '47' => 'proposition_periode',
      '48' => 'proposition_formation',
      '49' => 'proposition_ressource',
      '50' => 'proposition_session',
      '51' => 'rendezvous',
      '52' => 'rendezvous2',
      '53' => 'ressource',
      '54' => 'salle',
      '55' => 'session',
      '56' => 'stats_ca',
      '57' => 'utilisateur'
      );

      $nb2               = count($tableatronquer);
      $databaseatronquer = array('0' => 'AGC_xxxx');
      $nb                = count($databaseatronquer);

      $i = 0;
      while($i < $nb) {
      mysql_select_db($databaseatronquer[$i]) or die ('pas de connection');
      $j = 0;
      while($j < $nb2) {
      mysql_query('TRUNCATE TABLE '.$tableatronquer[$j].'');
      ++$j;
      }
      ++$i;
      }
      } */
   
    /**
     * Creation de compte chatter free pour toutes les personnes sans compte SF
     */
    public static function compteChatterFree() {
        if ($_GET['collab'] == 'true') {
            $doCollab = true;
        } else {
            $doCollab = false;
        }
        if ($_GET['ai'] == 'false') {
            $doAi = false;
        } else {
            $doAi = true;
        }
        $afterMail = '';
        if ($_GET['integrale'] == 'true') {
            $afterMail = '.integrale';
        }
       
        $log = 'start<br/>';
        $chatterFreeProfileId = '00eD0000001PMTqIAO';
        //On récupère tous les compte SF
        $sfClient = new SFClient();
        $sf_info = array();
        $result = $sfClient->query('SELECT Id, Username, Email, IsActive, ProfileId, Alias, MaintenirActif__c, EmployeeNumber FROM User');
        $queryLocator = $result->queryLocator;
        $done = false;
        while (!$done) {
            foreach ($result->records as $record) {
                $record->Username = strtolower($record->Username);
                $ar = array();
                $ar['mainteniractif'] = $record->MaintenirActif__c;
                $ar['id'] = $record->Id;
                $ar['username'] = $record->Username;
                $ar['matricule'] = $record->EmployeeNumber;
                $ar['active'] = $record->IsActive;
                if (substr_count($record->ProfileId, $chatterFreeProfileId) > 0) {
                    $ar['free'] = true;
                } else {
                    $ar['free'] = false;
                }
                $sf_info[$record->EmployeeNumber] = $ar;
            }
            if ($result->done) {
                $done = true;
            } else {
                $result = $sfClient->queryMore($queryLocator);
            }
        }
        //var_dump($sf_info);
        $ad_info = array();
        $ds = ldap_connect(LDAP_SRV);      
        if ($ds) {
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            $userad = 'user_osc@proservia.lan';
            $pwd = utf8_encode(stripslashes('Proservi@DSI'));
            $r = @ldap_bind($ds, $userad, $pwd);

            if ($r != false) {
                $infos = array();
                $dn = BASE_DN;
                $filter = '(&(objectCategory=person)(objectClass=user)(memberOf=CN=SHP-PROSERVIA-ALL,OU=APPLICATIONS INTERNES,DC=proservia,DC=lan))';
                $restriction = array('dn', 'sAMAccountName', 'cn', 'sn', 'givenName', 'mail', 'telephoneNumber', 'description', 'employeeId');
               
                $cookie = '';
                do {
                    ldap_control_paged_result($ds, 1000, true, $cookie);
                   
                    $sr = @ldap_search($ds, $dn, $filter, $restriction);
                    $infos2 = @ldap_get_entries($ds, $sr);
                    $infos = array_merge($infos, $infos2);
                   
                    ldap_control_paged_result_response($ds, $result, $cookie);
                } while($cookie !== null && $cookie != '');
                //var_dump($infos);
                foreach ($infos as $info) {
                    if (isset($info['employeeid'])) {
                        $info['mail'][0] = strtolower($info['mail'][0]);
                        $ar = array();
                        $ar['job'] = $info['description'][0];
                        $ar['mail'] = $info['mail'][0];
                        $ar['matricule'] = $info['employeeid'][0];
                        $ar['phone'] = $info['telephonenumber'][0];
                        $ad_info[$info['employeeid'][0]] = $ar;
                    }
                }
            }
        }
        //var_dump($ad_info);
        $db = connecter_cegid();
        $i = 0;
        $requeteSalaries = '';
        $cegid_databases = array ('PROSERVIA' => 'PRO', 'DAMCONSULT' => 'DCO', 'TAPFIN' => 'TAP', 'TIMARANCE' => 'TIM', 'DAMILOIT' => 'DAM', 'EXPERISIT' => 'EXP', 'OVIALIS_EXP' => 'OVS');
        foreach ($cegid_databases as $cegid_database => $id_base) {
            $requeteSalaries .= ($i != 0) ? ' UNION' : '';
            $requeteSalaries .= ' (
                SELECT "' . $id_base . '"+RIGHT(PSA_SALARIE,7) AS MATRICULE, "' . $cegid_database . '" AS SOCIETE, PSA_SALARIE, PSA_TRAVAILN1, PSA_LIBELLE,PSA_PRENOM, PSA_DATEENTREE, PSA_DATESORTIE,
                ET_LIBELLE, ET_ADRESSE1, ET_ADRESSE2, ET_ADRESSE3, ET_CODEPOSTAL, ET_PAYS, ET_VILLE, ET_TELEPHONE,
                US_ABREGE, PSE_EMAILPROF, AI
                FROM ' . $cegid_database . '.dbo.SALARIES
                LEFT JOIN ' . $cegid_database . '.dbo.ETABLISS ON PSA_ETABLISSEMENT = ET_ETABLISSEMENT
                LEFT JOIN ' . $cegid_database . '.dbo.UTILISAT ON US_AUXILIAIRE = PSA_SALARIE
                LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = PSA_SALARIE
                LEFT JOIN (
                    SELECT DISTINCT [PSA_SALARIE] AS AI_SALARIE, 1 as AI
                    FROM [' . $cegid_database . '].[dbo].[SALARIES]
                    LEFT JOIN [' . $cegid_database . '].[dbo].[HISTOANALPAIE] ON (PHA_SALARIE = PSA_SALARIE AND SUBSTRING(PHA_SECTION,4,3) = "PRO")
                    WHERE PHA_SECTION IS NOT NULL
                ) AS AI ON AI_SALARIE = PSA_SALARIE
                WHERE  PSE_EMAILPROF != "" AND US_ABREGE IS NOT NULL
            )';
            $i++;
        }
        $res = $db->query($requeteSalaries);
        $sf_toupdate = array();
        $sf_toinsert = array();
        while ($sal = $res->fetchRow()) {
            $sal->pse_emailprof = strtolower($sal->pse_emailprof);
            if (isset($sf_info[$sal->matricule])) {//deja un compte sf
                //$log .= 'déja un compte SF pour : ' . $sal->pse_emailprof . '<br />';
                if (strtotime($sal->psa_datesortie) != false && strtotime($sal->psa_datesortie) < (time() + 0 * 24 * 3600)/* SI 1900 -> CDI -> enverra false*/ && !($sf_info[$sal->matricule]['mainteniractif']) && $sf_info[$sal->matricule]['active']) {
                    //update pour rendre inactive
                    $sObject = new stdClass();
                    $sObject->Id = $sf_info[$sal->matricule]['id'];
                    $sObject->IsActive = false;
                   
                    $sf_toupdate[] = $sObject;
                    $log .= 'COMPTE RENDU INACTIF pour : ' . $sal->pse_emailprof . ' : '.$sf_info[$sal->matricule]['id'].'<br />';
                }
            } else {
                if ($sal->societe == 'PROSERVIA' && ($sal->psa_travailn1 == '001' || (($sal->psa_travailn1 == '002' && $doCollab) || ($sal->ai && $doAi)))) {
                    if (strtotime($sal->psa_datesortie) == false || strtotime($sal->psa_datesortie) > (time() + 7 * 24 * 3600)) {//si la personne est encore proservia
                        if (!isset($ad_info[$sal->pse_matricule])) {
                            $ad_info[$sal->pse_emailprof] = array();
                            $ad_info[$sal->pse_emailprof]['phone'] = $sal->et_telephone;
                            $ad_info[$sal->pse_emailprof]['job'] = 'COLLABORATEUR';
                            //var_dump($sal->pse_emailprof);
                        }
                        //Create Account
                        $sObject = new stdClass();
                        $sObject->FirstName = $sal->psa_prenom;
                        $sObject->LastName = $sal->psa_libelle;
                        $sObject->Username = $sal->pse_emailprof.$afterMail;
                        $sObject->Email = $sal->pse_emailprof;
                        $sObject->FederationIdentifier = $sal->pse_emailprof;
                        $sObject->CommunityNickname = strtolower($sal->us_abrege);
                        $sObject->IsActive = false;
                        $sObject->Alias = strtolower(substr($sal->psa_prenom, 0, 1)).strtolower(substr($sal->psa_libelle, 0, 4));
                        $sObject->Title = $ad_info[$sal->pse_emailprof]['job'].' - PROSERVIA';
                        $sObject->CompanyName = 'PROSERVIA';
                        $sObject->Department = trim(str_replace('PROSERVIA', '', $sal->et_libelle));
                        $sObject->Division = 'Agence';
                        $sObject->Phone = $ad_info[$sal->pse_emailprof]['phone'] ? $ad_info[$sal->pse_emailprof]['phone'] : $sal->et_telephone;
                        $sObject->Street = $sal->et_adresse1.' '.$sal->et_adresse2.' '.$sal->et_adresse3;
                        $sObject->City = $sal->et_ville;
                        $sObject->Country = $sal->et_pays;
                        $sObject->PostalCode = $sal->et_codepostal;
                        $sObject->EmailEncodingKey = 'ISO-8859-1';
                        $sObject->TimeZoneSidKey = 'Europe/Paris';
                        $sObject->LocaleSidKey = 'fr';
                        $sObject->LanguageLocaleKey = 'fr';
                        $sObject->ProfileId = $chatterFreeProfileId;
                        $sObject->EmployeeNumber = $sal->matricule;

                        $sObject->D_bloquer_opportunit_ferm_e__c = false;
                        $sObject->DefaultGroupNotificationFrequency = 'N';
                        $sObject->DigestFrequency = 'D';
                        $sObject->ForecastEnabled = false;
                        $sObject->ReceivesAdminInfoEmails = false;
                        $sObject->ReceivesInfoEmails = false;
                        $sObject->UserPermissionsAvantgoUser = false;
                        $sObject->UserPermissionsCallCenterAutoLogin = false;
                        $sObject->UserPermissionsChatterAnswersUser = false;
                        $sObject->UserPermissionsInteractionUser = false;
                        $sObject->UserPermissionsMarketingUser = false;
                        $sObject->UserPermissionsMobileUser = false;
                        $sObject->UserPermissionsOfflineUser = false;
                        $sObject->UserPermissionsSFContentUser = false;
                        $sObject->UserPreferencesActivityRemindersPopup = true;
                        $sObject->UserPreferencesApexPagesDeveloperMode = false;
                        $sObject->UserPreferencesDisableAllFeedsEmail = false;
                        $sObject->UserPreferencesDisableBookmarkEmail = false;
                        $sObject->UserPreferencesDisableChangeCommentEmail = false;
                        $sObject->UserPreferencesDisableFileShareNotificationsForApi = false;
                        $sObject->UserPreferencesDisableFollowersEmail = false;
                        $sObject->UserPreferencesDisableLaterCommentEmail = false;
                        $sObject->UserPreferencesDisableLikeEmail = true;
                        $sObject->UserPreferencesDisableMentionsPostEmail = false;
                        $sObject->UserPreferencesDisableMessageEmail = false;
                        $sObject->UserPreferencesDisableProfilePostEmail = false;
                        $sObject->UserPreferencesDisableSharePostEmail = false;
                        $sObject->UserPreferencesDisCommentAfterLikeEmail = false;
                        $sObject->UserPreferencesDisMentionsCommentEmail = false;
                        $sObject->UserPreferencesDisProfPostCommentEmail = false;
                        $sObject->UserPreferencesEnableAutoSubForFeeds = false;
                        $sObject->UserPreferencesEventRemindersCheckboxDefault = true;
                        $sObject->UserPreferencesHideCSNDesktopTask = false;
                        $sObject->UserPreferencesHideCSNGetChatterMobileTask = false;
                        $sObject->UserPreferencesOptOutOfTouch = false;
                        $sObject->UserPreferencesReminderSoundOff = false;
                        $sObject->UserPreferencesTaskRemindersCheckboxDefault = true;
                        //$sObject->UserType = 'CsnOnly';

                        $sObject->MaintenirActif__c = false;

                        $sf_toinsert[] = $sObject;

                        $log .= 'CREATION COMPTE pour : ' . $sal->pse_emailprof . '<br />';
                    } else {
                        //parti de l'entreprise et aucun compte SF -> rien a faire
                        //$log .= 'employee sorti : ' . $sal->pse_emailprof . '<br />';
                    }
                }
            }
        }
        $log .= '<br /><br />FIN TRAITEMENT - DEBUT REQUETE<br /><br />';
        try {
            $max = 200;
            $i = 0;
            while (count($sf_toinsert) > ($i * $max)) {
                $createResponse = $sfClient->create(array_slice($sf_toinsert, $i * $max, $max), 'User');
                $i++;
                foreach ($createResponse as $response) {
                    if ($response->success) {
                        $log .= 'success pour '.$response->id.'<br />';
                    } else {
                        $log .= 'erreur : ';
                        foreach ($response->errors as $error) {
                            $log .= $error->message.' / ';
                        }
                        $log .= '<br />';
                    }
                }
            };
           
            $max = 200;
            $i = 0;
            while (count($sf_toupdate) > ($i * $max)) {
                $updateResponse = $sfClient->update(array_slice($sf_toupdate, $i * $max, $max), 'User');
                $i++;
                foreach ($updateResponse as $response) {
                    if ($response->success) {
                        $log .= 'success pour '.$response->id.'<br />';
                    } else {
                        $log .= 'erreur : ';
                        foreach ($response->errors as $error) {
                            $log .= $error->message.' / ';
                        }
                        $log .= '<br />';
                    }
                }
            };
        } catch (Exception $ex) {
            //var_dump($ex);
            throw new AGCException($ex->getMessage());
        }
        $log .= 'end<br />';
        echo $log;
        sendMailCron('[AGC]Script création compte chatter free', $log);
    }
   
    /**
     * synchro des compte Taleo et autres infos sur Taleo
     */
    public static function synchroTaleo() {
       $log = 'debut<br />';
       
       $tcfind = new TaleoClient(TALEO_FIND_WSDL);
       $tcsmartorgfind = new TaleoClient(TALEO_SMARTORGFIND_WSDL);
       $tcsmartorgintegration = new TaleoClient(TALEO_SMARTORGINTEGRATION_WSDL);
       $tcenterpriseintegration = new TaleoClient(TALEO_ENTERPRISEINTEGRATION_WSDL);
       
       $db = connecter_cegid();
       $res = Bdd::getDatabases();
       /*
       PARTIE SERVICES
        */
       $query = '
           <ns1:query alias="SimpleProjection" projectedClass="UDSElement">
                   <ns1:projections>
                           <ns1:projection>
                                   <ns1:field path="Description"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="Number"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="Active"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="Code"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="Complete"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="UserDefinedSelection,Number"/>
                           </ns1:projection>
                   </ns1:projections>
                   <ns1:filterings>
                        <ns1:filtering>
                            <ns1:equal><ns1:field path="UserDefinedSelection,Number"/><ns1:long>10380</ns1:long></ns1:equal>
                        </ns1:filtering>
                   </ns1:filterings>
                   <ns1:sortings>
                       <ns1:sorting ascending="true">
                           <ns1:field path="Code" />
                       </ns1:sorting>
                   </ns1:sortings>
           </ns1:query>';
       
       $resTalServices = $tcfind->query($query);
       
       //var_dump($resTalServices);
       
       $talServices = array();
       foreach ($resTalServices as $service) {
           $talServices[$service->Code] = new stdClass;
           $talServices[$service->Code]->description = $service->Description->value->_;
           $talServices[$service->Code]->active = $service->Active;
           $talServices[$service->Code]->number = $service->Number;
       }
       
       // Création de l'entête de la requète Taleo
       $r = '<m:submitDocument xmlns:m="http://www.taleo.com/ws/integration/toolkit/2005/07">
                <Document xmlns="http://www.taleo.com/ws/integration/toolkit/2005/07">
                    <Attributes>
                        <Attribute name="version">http://www.taleo.com/ws/tee800/2009/01</Attribute>
                    </Attributes>
                    <Content>
            <ImportEntities>';
       
       foreach ($res as $l) {
            $_SESSION['societe'] = $l;
            $cegid_databases = Bdd::getCegidDatabases($l);
            $contents = '';
            $requeteServices = '';
            $db = connecter_cegid();
            $i = 0;
            foreach ($cegid_databases as $cegid_database) {
                $requeteServices .= ($i != 0) ? ' UNION' : '';
                $requeteServices .= ' SELECT "' . $cegid_database . '" AS societe, PGS_CODESERVICE, PGS_NOMSERVICE
                    FROM ' . $cegid_database . '.dbo.PGSERVICES ';
                $i++;
            }
       
            $cegServices = $db->query($requeteServices);
           
            while ($service = $cegServices->fetchRow()) {
                $service->pgs_codeservice = substr($service->societe, 0, 3).''.$service->pgs_codeservice;
                $service->pgs_nomservice = str_replace('&', ' ', $service->societe.' - '.$service->pgs_nomservice);
                if (isset($talServices[$service->pgs_codeservice])) {// update ?
                    if (utf8_encode($service->pgs_nomservice) != $talServices[$service->pgs_codeservice]->description || $talServices[$service->pgs_codeservice]->active == 'false') {
                        $r .= '<UserDefinedSelection-merge xmlns="http://www.taleo.com/ws/tee800/2009/01">
                             <UserDefinedSelection>
                                <Number searchType="search" searchTarget="." searchValue="10380"/>
                                <Elements>
                                   <UDSElement>
                                      <Code searchType="search" searchTarget="." searchValue="'.$service->pgs_codeservice.'"/>
                                      <Active>true</Active>
                                      <Description>
                                         <value locale="en">'.utf8_encode($service->pgs_nomservice).'</value>
                                         <value locale="fr-FR">'.utf8_encode($service->pgs_nomservice).'</value>
                                      </Description>
                                   </UDSElement>
                                </Elements>
                             </UserDefinedSelection>
                          </UserDefinedSelection-merge>';
                          $log .= 'modification du service '.$service->pgs_codeservice.'<br />';
                    }
                    unset($talServices[$service->pgs_codeservice]);
                } else {//create
                    $r .= '<UDSElement-create xmlns="http://www.taleo.com/ws/tee800/2009/01">
                 <UDSElement>
                    <Active>true</Active>
                    <Code>'.$service->pgs_codeservice.'</Code>
                    <Complete>true</Complete>
                    <Description>
                       <value locale="en">'.utf8_encode($service->pgs_nomservice).'</value>
                       <value locale="fr-FR">'.utf8_encode($service->pgs_nomservice).'</value>
                    </Description>
                    <Sequence>1</Sequence>
                    <UserDefinedSelection>
                       <UserDefinedSelection>
                          <Number searchType="search" searchTarget="." searchValue="10380"/>
                       </UserDefinedSelection>
                    </UserDefinedSelection>
                 </UDSElement>
                </UDSElement-create>';
                    $log .= 'creation du service '.$service->pgs_codeservice.'<br />';
                }
            }
       }
       var_dump($talServices);
       foreach ($talServices as $serviceCode => $serviceTal) {
           //supprimer le service
           if ($serviceCode != null && $serviceTal->active != 'false') {
               $r .= '<UserDefinedSelection-merge xmlns="http://www.taleo.com/ws/tee800/2009/01">
                <UserDefinedSelection>
                   <Number searchType="search" searchTarget="." searchValue="10380"/>
                   <Elements>
                      <UDSElement>
                         <Code searchType="search" searchTarget="." searchValue="'.$serviceCode.'"/>
                         <Active>false</Active>
                         <Description>
                            <value locale="en">INACTIF '.$serviceTal->description.'</value>
                            <value locale="fr-FR">INACTIF '.$serviceTal->description.'</value>
                         </Description>
                      </UDSElement>
                   </Elements>
                </UserDefinedSelection>
             </UserDefinedSelection-merge>';
               $log .= 'désactivation du service '.$serviceCode.'<br />';
           }
           
       }
           
       $r .= '</ImportEntities>
                </Content>
            </Document>
        </m:submitDocument>';
           
       $result = $tcenterpriseintegration->submitDocument($r);
       
       
       /*
       PARTIE CANDIDATS
        */
       $resTalCandidates = $tcfind->getCandidatesWithSS();
       
       $talCandidates = array();
       foreach ($resTalCandidates as $candidate) {
           $candidate->NumeroSS = str_replace(' ', '', $candidate->NumeroSS);
           $talCandidates[$candidate->NumeroSS] = new stdClass;
           $talCandidates[$candidate->NumeroSS]->number = $candidate->Number;
           $talCandidates[$candidate->NumeroSS]->matricule = $candidate->EmployeeNumber;
       }
       
       $cegSal = array();
       $cegid_databases = array ('PROSERVIA', 'DAMCONSULT', 'TAPFIN', 'TIMARANCE', 'DAMILOIT', 'EXPERISIT', 'OVIALIS_EXP');;
       $requeteSalaries = '';
       $i = 0;
       foreach ($cegid_databases as $cegid_database) {
          $requeteSalaries .= ($i != 0) ? ' UNION' : '';
          $requeteSalaries .= ' (SELECT "' . $cegid_database . '" AS societe, PSA_SALARIE, PSA_TRAVAILN1, PSA_NUMEROSS, PSA_PRENOM, PSA_LIBELLE, PSA_DATESORTIE
            FROM ' . $cegid_database . '.dbo.salaries
            ) ';
          $i++;
       }
       
       $db = connecter_cegid();
       $response = $db->query($requeteSalaries);
       
       $r = '<m:submitDocument xmlns:m="http://www.taleo.com/ws/integration/toolkit/2005/07">
            <Document xmlns="http://www.taleo.com/ws/integration/toolkit/2005/07">
                <Attributes>
<Attribute name="version">http://www.taleo.com/ws/tee800/2009/01</Attribute>
                </Attributes>
                <Content>
<ImportEntities>';
       
       while ($sal = $response->fetchRow()) {
                 if (! (strtotime($sal->psa_datesortie) < (time() + 7 * 24 * 3600) && strtotime($sal->psa_datesortie) != false) ) {//si le salarié n'est pas sorti
            if (isset($talCandidates[$sal->psa_numeross])) {
                if ($talCandidates[$sal->psa_numeross]->matricule != $sal->psa_salarie) {
                    $r .= '<Candidate-merge xmlns="http://www.taleo.com/ws/tee800/2009/01">
                            <Candidate>
                               <Number searchType="search" searchValue="'.$talCandidates[$sal->psa_numeross]->number.'"/>
                               <EmployeeNumber>'.$sal->psa_salarie.'</EmployeeNumber>
                            </Candidate>
                         </Candidate-merge>';
                    $log .= 'ajout du numero sur le candidat '.$sal->psa_prenom.' '.$sal->psa_libelle.$talCandidates[$sal->psa_numeross]->matricule.'<br />';
                } else {
                    //deja un matricule
                }
            } else {
                //pas de candidats sur taleo.
            }
                }
       }
       
       $r .= '</ImportEntities>
                </Content>
            </Document>
        </m:submitDocument>';
             
       $result = $tcenterpriseintegration->submitDocument($r);
       
       /*
        * PARTIE USER
        */
       $query = '<ns1:query alias="SimpleProjection" projectedClass="User">
                   <ns1:projections>
                           <ns1:projection>
                                   <ns1:field path="UserAccount,Loginname"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="UserAccount,AccountStatus,Code"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="UserAccount,AccountStatus,Number"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="UserTypes,Code"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="LastName"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="FirstName"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="CorrespondenceEmail"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="EmployeeID"/>
                           </ns1:projection>
                           <ns1:projection>
                                   <ns1:field path="UserNo"/>
                           </ns1:projection>
                   </ns1:projections>
                   <ns1:filterings>
                       
                   </ns1:filterings>
                   <ns1:sortings>
                       <ns1:sorting ascending="true">
                           <ns1:field path="UserAccount,Loginname" />
                       </ns1:sorting>
                   </ns1:sortings>
           </ns1:query>';
       
       $resTalSal = $tcsmartorgfind->query($query, false, array('mappingVersion' => 'http://www.taleo.com/ws/so800/2009/01'));
       $talSal = array();
       $talMatricule = array();
       foreach ($resTalSal as $sal) {
           $talSal[$sal->CorrespondenceEmail] = new stdClass();
           $talSal[$sal->CorrespondenceEmail]->lastname = $sal->LastName;
           $talSal[$sal->CorrespondenceEmail]->firstname = $sal->FirstName;
           $talSal[$sal->CorrespondenceEmail]->numuser = $sal->UserNo;
           if ($sal->UserAccount->UserAccount->AccountStatus->AccountStatus->Number == 1) {
               $talSal[$sal->CorrespondenceEmail]->active = true;
           } else {
               $talSal[$sal->CorrespondenceEmail]->active = false;
           }
           $talMatricule[$sal->EmployeeID] = $talSal[$sal->CorrespondenceEmail];
       }
       
       $cegSal = array();
        $cegid_databases = array ('PROSERVIA' => 'PRO', 'DAMCONSULT' => 'DCO', 'TAPFIN' => 'TAP', 'TIMARANCE' => 'TIM', 'DAMILOIT' => 'DAM', 'EXPERISIT' => 'EXP', 'OVIALIS_EXP' => 'OVS');
        $requeteSalaries = '';
        $i = 0;
        foreach ($cegid_databases as $cegid_database => $id_base) {
            $requeteSalaries .= ($i != 0) ? ' UNION' : '';
            $requeteSalaries .= ' (SELECT "' . $cegid_database . '" AS societe, "' . $id_base . '"+ RIGHT(PSA_SALARIE, 7) AS MATRICULE, PSA_SALARIE, PSA_LIBELLE, PSA_PRENOM, CC_LIBELLE, CC_CODE, PSE_EMAILPROF, PSA_DATESORTIE
                ,CASE WHEN CC_CODE IN ("A06", "A08", "A10", "A12", "A16", "A18", "A21", "A22", "A61", "A62", "A66", "A96", "I10", "I74", "B01") THEN 1 ELSE 0 END AS MUSTHAVEACCOUNT
                FROM ' . $cegid_database . '.[dbo].[SALARIES]
                LEFT JOIN ' . $cegid_database . '.[dbo].[CHOIXCOD] ON PSA_LIBELLEEMPLOI = CC_CODE
                LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = PSA_SALARIE
                WHERE CC_TYPE = "PLE" AND PSE_EMAILPROF IS NOT NULL AND PSE_EMAILPROF != ""
                ) ';
            $i++;
        }
        $response = $db->query($requeteSalaries);
        //var_dump($response);
        while ($sal = $response->fetchRow()) {
            $cegSal[] = $sal;
        }
       
       $r = '<m:submitDocument xmlns:m="http://www.taleo.com/ws/integration/toolkit/2005/07">
            <Document xmlns="http://www.taleo.com/ws/integration/toolkit/2005/07">
                <Attributes>
                    <Attribute name="version">http://www.taleo.com/ws/so800/2009/01</Attribute>
                </Attributes>
                <Content>
            <ImportEntities>';
       
       foreach ($cegSal as $sal) {
           $sal->pse_emailprof = strtolower($sal->pse_emailprof);
           if (strtotime($sal->psa_datesortie) < (time() + 7 * 24 * 3600) && strtotime($sal->psa_datesortie) != false) {//salarié sorti ?
               if ((isset($talSal[$sal->pse_emailprof]) && $talSal[$sal->pse_emailprof]->active) || (isset($talMatricule[$sal->matricule]) && $talMatricule[$sal->matricule]->active) ) {
                   $r .= '<User-merge xmlns="http://www.taleo.com/ws/so800/2009/01">
                        <User>
                           <UserNo searchType="search" searchTarget="." searchValue="'.$talSal[$sal->pse_emailprof]->numuser.'"/>
                         <UserAccount>
                            <UserAccount>
                               <AccountStatus>
                                  <AccountStatus>
                                     <Code searchType="search" searchValue="INACTIVE" />
                                  </AccountStatus>
                               </AccountStatus>
                            </UserAccount>
                         </UserAccount>
                        </User>
                        <Null/>
                        <Null/>
                     </User-merge>';
                   $log .= 'désactivation du compte '.$sal->pse_emailprof.'<br />';
               }
           } else {
               if (!(isset($talSal[$sal->pse_emailprof])|| isset($talMatricule[$sal->matricule])) && $sal->musthaveaccount == 1) {
                   $r .= '<User-create xmlns="http://www.taleo.com/ws/so800/2009/01">
                        <User>
                           <LastName>'. utf8_encode($sal->psa_libelle) .'</LastName>
                           <FirstName>'. utf8_encode($sal->psa_prenom) .'</FirstName>
                           <CorrespondenceEmail>'.strtolower($sal->pse_emailprof).'</CorrespondenceEmail>
                           <EmployeeID>'.$sal->matricule.'</EmployeeID>
                           <UserAccount><UserAccount>
                               <Loginname>'. str_replace(' ', '',cleanStr(strtolower(substr($sal->psa_prenom, 0, 1))) . cleanStr(strtolower($sal->psa_libelle))) .'</Loginname>
                           </UserAccount></UserAccount>
                        </User>
                        <Password>proservia%44</Password>
                        <ForceChangePassword>true</ForceChangePassword>
                     </User-create>
                     '/*<User-merge xmlns="http://www.taleo.com/ws/so800/2009/01">
                        <User>
                            <CorrespondenceEmail searchType="search" searchTarget="." searchValue="'.strtolower($sal->pse_emailprof).'"/>
                            <UserTypes>
                                <UserType>
                                   <Code searchType="search" searchValue="08_MPW_DemandeurProservia"/>
                                </UserType>
                            </UserTypes>
                        </User>
                        <Null/>
                        <Null/>
                     </User-merge>'*/.'
                     <User-merge xmlns="http://www.taleo.com/ws/so800/2009/01">
                        <User>
                           <CorrespondenceEmail searchType="search" searchTarget="." searchValue="'.strtolower($sal->pse_emailprof).'"/>
                         <UserAccount>
                            <UserAccount>
                               <AccountStatus>
                                  <AccountStatus>
                                     <Code searchType="search" searchValue="INACTIVE" />
                                  </AccountStatus>
                               </AccountStatus>
                            </UserAccount>
                         </UserAccount>
                        </User>
                        <Null/>
                        <Null/>
                     </User-merge>';
                   $log .= 'création du compte '.$sal->pse_emailprof.'<br />';
               }
           }
       }
       
       $r .= '</ImportEntities>
                </Content>
            </Document>
        </m:submitDocument>';
       
       $tcsmartorgintegration->submitDocument($r);

       $log .= 'fin<br />';
       sendMailCron('CRON SYNCHRO TALEO', $log);
       echo $log;
    }
   
    /**
     * envoie des demandes de poste proservia et experis vers RJO
     */
    public static function rightrjoJob($employerId, $jobBoard, $jobSite) {
       $log = 'debut<br />';
       
       try {
            $tcfind = new TaleoClient();
            $rightrjoSoap = new RightrjoClient();
            $jobs = array();
            $query = '
                 <ns1:query alias="SimpleProjection" projectedClass="SourcingRequest" locale="fr-FR">
                     <ns1:projections>
                         <ns1:projection>
                             <ns1:field path="OpenDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="CloseDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,ContestNumber"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,TargetStartDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,DescriptionExternalHTML"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,ExternalQualificationHTML"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,Title"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Name"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Name"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Parent,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobType,Description"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobType,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,CorrespondenceEmail"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,LastName"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,FirstName"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="SourcingRequestStatus,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="SourcingRequestStatus,Description"/>
                         </ns1:projection>
                     </ns1:projections>
                     <ns1:filterings>
                         <ns1:filtering>
                             <ns1:equal>
                                 <ns1:field path="SourcingRequestStatus,Number"/>
                                 <ns1:integer>2</ns1:integer>
                             </ns1:equal>
                         </ns1:filtering>
                         <ns1:filtering>
                             <ns1:equal>
                                 <ns1:field path="JobBoard,RecruitmentSource,Identifier"/>
                                 <ns1:string>'.$jobBoard.'</ns1:string>
                             </ns1:equal>
                         </ns1:filtering>
                     </ns1:filterings>
                     <ns1:sortings>
                     </ns1:sortings>
                 </ns1:query>';

            $requisitions = $tcfind->query($query, false, null, 1, 20);

            $correspondanceLocationId = array('170431072' => '276','970431072' => '277','1170431072' => '278','1070431072' => '279','8870431072' => '280','8970431072' => '281','9270431072' => '282',
                '9170431072' => '283','9370431072' => '284','9070431072' => '285','10770431072' => '286','10870431072' => '287','11170431072' => '288','11070431072' => '289','10970431072' => '290','10370431072' => '291',
                '10670431072' => '292','10570431072' => '293','10470431072' => '294','470431072' => '295','670431072' => '296','870431072' => '297','570431072' => '298','770431072' => '299','2070431072' => '300','2470431072' => '301',
                '2170431072' => '302','2270431072' => '303','2370431072' => '304','2770431072' => '305','2870431072' => '306','3370431072' => '307','3270431072' => '308','3170431072' => '309','2970431072' => '311','3070431072' => '310',
                '11870431072' => '312','11970431072' => '313','12170431072' => '314','12270431072' => '316','12070431072' => '315','3670431072' => '317','3870431072' => '318','3770431072' => '319','5370431072' => '320','5670431072' => '321',
                '5470431072' => '323','5770431072' => '322','5570431072' => '324','14270431072' => '325','14370431072' => '326','14470431072' => '327','13370431072' => '328','14170431072' => '332','13470431072' => '333','13770431072' => '329',
                '13870431072' => '330','13570431072' => '334','13970431072' => '335','14070431072' => '336','13670431072' => '331','12770431072' => '337','12870431072' => '338','13170431072' => '339','13270431072' => '340','13070431072' => '341',
                '12970431072' => '342','8070431072' => '343','8170431072' => '344','8270431072' => '345','8370431072' => '346','4870431072' => '347','5270431072' => '348','5170431072' => '349','4970431072' => '350','5070431072' => '351',
                '7170431072' => '352','7770431072' => '353','7670431072' => '354','7970431072' => '355','7870431072' => '356','7270431072' => '358','7470431072' => '357','7570431072' => '359','7370431072' => '360','11570431072' => '361','11770431072' => '362',
                '11670431072' => '363','1470431072' => '364','1670431072' => '365','1870431072' => '366','1970431072' => '367','1570431072' => '368','1770431072' => '369','4470431072' => '370','4670431072' => '371','4570431072' => '372','4770431072' => '373',
                '3970431072' => '374','4370431072' => '375','4070431072' => '376','4270431072' => '377','4170431072' => '378','5870431072' => '379','6070431072' => '380','5970431072' => '382','6270431072' => '383','6370431072' => '381','6470431072' => '384',
                '6170431072' => '385','9470431072' => '386','9970431072' => '387','10170431072' => '388','10070431072' => '389','9870431072' => '394','9570431072' => '390','9670431072' => '391','10270431072' => '392','9770431072' => '393');

            $correspondanceIndustryId = array('170431072' => '585','2170431072' => '585','6170431072' => '585','6370431072' => '585','6470431072' => '583','8170431072' => '746','8270431072' => '746','8370431072' => '746','8470431072' => '746','8570431072' => '746','48170431072' => '746');

            //var_dump($requisitions[1]->Requisition->Requisition->JobInformation->JobInformation->Title->value->_);
            foreach ($requisitions as $requisition) {
                $job = array();
                $job['action'] = 'add';
                $job['jobtitle'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->Title->value->_;
                $job['jobdescription'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->DescriptionExternalHTML->value->_;
                $job['candidateprofile'] = '';
                $job['candidateskills'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->ExternalQualificationHTML->value->_;
                                if($requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Number == '')
                                        $job['positiontypeid'] = 1;
                                else
                                        $job['positiontypeid'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Number;
                                         
                $job['positiontypedescription'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Description->value->_;
                $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Parent->JobField->Parent->JobField->Number;
                if (!isset($correspondanceIndustryId[$industryId])) {
                    $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Parent->JobField->Number;
                }
                if (!isset($correspondanceIndustryId[$industryId])) {
                    $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Number;
                }
                $job['industrysectorid'] = '-10';
                $job['industrysectordescription'] = /*$correspondanceIndustryId[*/$industryId/*]*/;
                /*$job['salaryupper'] = '1500';
                $job['salarylower'] = '1200';*/
                $job['salaryoverride'] = 'A voir';
                $job['publishdate'] = substr($requisition->OpenDate, 0, 19).'.0000000'.substr($requisition->OpenDate, 19, 3).':00';
                $job['expirydate'] = $requisition->CloseDate ? (substr($requisition->CloseDate, 0, 19).'.0000000'.substr($requisition->CloseDate, 19, 3).':00') : '2079-01-01T00:00:00.0000000+01:00';//'2014-06-05T16:26:13.6501873+06:00';
                $locationId = $requisition->Requisition->Requisition->JobInformation->JobInformation->PrimaryLocation->Location->Parent->Location->Number;
                if (!(isset($correspondanceLocationId[$locationId]))) {
                    $locationId = $requisition->Requisition->Requisition->JobInformation->JobInformation->PrimaryLocation->Location->Number;
                }
                $job['locationid'] = '-10';
                $job['locationdescription'] = /*$correspondanceLocationId[*/$locationId/*]*/;
                $job['employerjobreferencenumber'] = $requisition->Requisition->Requisition->ContestNumber;
                $job['applicationcontactname'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->FirstName.' '.$requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->LastName;
                $job['applicationemail'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->CorrespondenceEmail;
                $job['applicationwebsite'] = $jobSite;
                $jobs[] = $job;
                $job['action'] = 'update';
                $jobs[] = $job;
                $log .= 'ajout et update de '.$requisition->Requisition->Requisition->ContestNumber.'<br />';
            }
            //var_dump($jobs);
            $result = $rightrjoSoap->send($employerId, $jobs);
            var_dump($result);

            //to delete
            //status 3 = publication retirée
            //satus 4 = expirée
            $query = ' <ns1:query alias="SimpleProjection" projectedClass="SourcingRequest" locale="fr-FR">
                     <ns1:projections>
                         <ns1:projection>
                             <ns1:field path="OpenDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="CloseDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,ContestNumber"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,TargetStartDate"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,DescriptionExternalHTML"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,ExternalQualificationHTML"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,Title"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Name"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,PrimaryLocation,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Name"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobField,Parent,Parent,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobType,Description"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,JobType,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,CorrespondenceEmail"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,LastName"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="Requisition,JobInformation,RecruiterOwner,FirstName"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="SourcingRequestStatus,Number"/>
                         </ns1:projection>
                         <ns1:projection>
                             <ns1:field path="SourcingRequestStatus,Description"/>
                         </ns1:projection>
                     </ns1:projections>
                     <ns1:filterings>
                         <ns1:filtering>
                             <ns1:or>
                                <ns1:equal>
                                        <ns1:field path="SourcingRequestStatus,Number"/>
                                        <ns1:integer>3</ns1:integer>
                                </ns1:equal>
                                <ns1:equal>
                                        <ns1:field path="SourcingRequestStatus,Number"/>
                                        <ns1:integer>4</ns1:integer>
                                </ns1:equal>
                            </ns1:or>
                         </ns1:filtering>
                         <ns1:filtering>
                             <ns1:equal>
                                 <ns1:field path="JobBoard,RecruitmentSource,Identifier"/>
                                 <ns1:string>'.$jobBoard.'</ns1:string>
                             </ns1:equal>
                         </ns1:filtering>
                         <ns1:filtering>
                             <ns1:greaterThan>
                                 <ns1:field path="CloseDate"/>
                                 <ns1:date>'.date('Y-m-d', (time() - (7 * 24 * 60 * 60))).'</ns1:date>
                             </ns1:greaterThan>
                         </ns1:filtering>
                     </ns1:filterings>
                     <ns1:sortings>
                     </ns1:sortings>
                 </ns1:query>';

            $requisitionsToDelete = $tcfind->query($query, false, null, 1, 20);
            //var_dump($requisitionsToDelete);
            $listTodelete = array();
            foreach ($requisitionsToDelete as $requisition) {
                $job = array();
                $job['action'] = 'update';
                $job['jobtitle'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->Title->value->_;
                $job['jobdescription'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->DescriptionExternalHTML->value->_;
                $job['candidateprofile'] = '';
                $job['candidateskills'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->ExternalQualificationHTML->value->_;
                                if($requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Number == '')
                                        $job['positiontypeid'] = 1;
                                else
                                        $job['positiontypeid'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Number;
                                         
                $job['positiontypedescription'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobType->JobType->Description->value->_;
                $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Parent->JobField->Parent->JobField->Number;
                if (!isset($correspondanceIndustryId[$industryId])) {
                    $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Parent->JobField->Number;
                }
                if (!isset($correspondanceIndustryId[$industryId])) {
                    $industryId = $requisition->Requisition->Requisition->JobInformation->JobInformation->JobField->JobField->Number;
                }
                $job['industrysectorid'] = '-10';
                $job['industrysectordescription'] = /*$correspondanceIndustryId[*/$industryId/*]*/;
                /*$job['salaryupper'] = '1500';
                $job['salarylower'] = '1200';*/
                $job['salaryoverride'] = 'A voir';
                $job['publishdate'] = substr($requisition->OpenDate, 0, 19).'.0000000'.substr($requisition->OpenDate, 19, 3).':00';
                $job['expirydate'] = $requisition->CloseDate ? (substr($requisition->CloseDate, 0, 19).'.0000000'.substr($requisition->CloseDate, 19, 3).':00') : '2079-01-01T00:00:00.0000000+01:00';//'2014-06-05T16:26:13.6501873+06:00';
                $locationId = $requisition->Requisition->Requisition->JobInformation->JobInformation->PrimaryLocation->Location->Parent->Location->Number;
                if (!(isset($correspondanceLocationId[$locationId]))) {
                    $locationId = $requisition->Requisition->Requisition->JobInformation->JobInformation->PrimaryLocation->Location->Number;
                }
                $job['locationid'] = '-10';
                $job['locationdescription'] = /*$correspondanceLocationId[*/$locationId/*]*/;
                $job['employerjobreferencenumber'] = $requisition->Requisition->Requisition->ContestNumber;
                $job['applicationcontactname'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->FirstName.' '.$requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->LastName;
                $job['applicationemail'] = $requisition->Requisition->Requisition->JobInformation->JobInformation->RecruiterOwner->User->CorrespondenceEmail;
                $job['applicationwebsite'] = $jobSite;
                $listTodelete[] = $job;
                $log .= 'désactivation de '.$requisition->Requisition->Requisition->ContestNumber.'<br />';
            }
            $result = $rightrjoSoap->send($employerId, $listTodelete);
            var_dump($result);
            sendMailCron('RightRJO_CRON - OK', $log);
       }catch (Exception $e) {
            sendMailCron('RightRJO_CRON - ERREUR', $e.' '.$log);
        }
        $log .= 'fin';
       echo $log;
    }
   
    /**
     * synchro des recruteurs proservia sur les demandes de postes SF
     */
    public static function synchroSF() {
       $log = 'debut<br />';
       
       $tab = array('proservia' => '04_MPW_RecruteurProservia', 'experis' => '05_MPW_RecruteurExperis');
       
       $sfMetaClient = new SFMetadataClient();
       $tcsmartorgfind = new TaleoClient(TALEO_SMARTORGFIND_WSDL);        
       
       foreach ( $tab as $societe => $profilTaleo ) {      
        /* Mise à jour des du choix de recruteur du les demandes de postes */
               //Récupération des recruteurs
        $query = '<ns1:query alias="SimpleProjection" projectedClass="User">
                    <ns1:projections>
                            <ns1:projection>
                                    <ns1:field path="UserAccount,Loginname"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="UserTypes,Code"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="LastName"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="FirstName"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="CorrespondenceEmail"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="EmployeeID"/>
                            </ns1:projection>
                    </ns1:projections>
                    <ns1:filterings>
                        <ns1:filtering>
                             <ns1:equal><ns1:field path="UserAccount,AccountStatus,Code"/><ns1:string>ACTIVE</ns1:string></ns1:equal>
                         </ns1:filtering>
                         <ns1:filtering>
                             <ns1:equal><ns1:field path="UserTypes,Code"/><ns1:string>'.$profilTaleo.'</ns1:string></ns1:equal>
                         </ns1:filtering>
                    </ns1:filterings>
                    <ns1:sortings>
                        <ns1:sorting ascending="true">
                            <ns1:field path="UserAccount,Loginname" />
                        </ns1:sorting>
                    </ns1:sortings>
            </ns1:query>';

        $picklistValues = array();

        $resTalSal = $tcsmartorgfind->query($query, false, array('mappingVersion' => 'http://www.taleo.com/ws/so800/2009/01'));
        foreach ($resTalSal as $sal) {
            if (strstr($sal->CorrespondenceEmail, '@'.$societe.'') != false) {
                $log .= 'recruteur '.$societe.' : '.$sal->CorrespondenceEmail.'<br />';
                $val = new stdClass();
                $val->fullName = $sal->CorrespondenceEmail;
                $val->default    = false;
                $picklistValues[] = $val;
            }
            //var_dump($sal);
        }

         $obj = new SforceCustomObject();
         $obj->currentName   = 'Requisition__c.Recruteur_'.$societe.'__c';
         $obj->metadata      = new SforceCustomField();
         $obj->metadata->fullName    = 'Requisition__c.Recruteur_'.$societe.'__c';  
         $obj->metadata->label    = 'Recruteur';  
         $obj->metadata->type        = 'Picklist';
         $obj->metadata->picklist    = new stdClass();
         $obj->metadata->picklist->sorted            = false;
         $obj->metadata->picklist->picklistValues    = $picklistValues;

         //print_r($sfMetaClient->update($obj));
       }
       $log .= 'fin';
       sendMailCron('CRON SYNCHRO SF', $log);
       echo $log;
    }

    /**
     *
     */
    public static function sendAlertSmsNumbers() {
       $log = 'debut<br />';
             
       $people = array();

                $requete = '';
                $db = connecter_cegid();
                $i = 0;
                $cegid_databases = array ('PROSERVIA', 'DAMILOSAS', 'DAMCONSULT', 'TAPFIN', 'TIMARANCE', 'DAMILOIT', 'EXPERISIT', 'OVIALIS', 'OVIALIS_EXP');
                foreach ($cegid_databases as $cegid_database) {
                        $requete .= ($i != 0) ? ' UNION' : '';
                        $requete .= ' SELECT "' . $cegid_database . '" AS SOCIETE,PSA_SALARIE, PSA_TRAVAILN1, PSA_LIBELLE,PSA_PRENOM, PSA_DATEENTREE, PSA_DATESORTIE,
                                ET_LIBELLE, ET_ADRESSE1, ET_ADRESSE2, ET_ADRESSE3, ET_CODEPOSTAL, ET_PAYS, ET_VILLE, ET_TELEPHONE,
                                US_ABREGE, PSE_EMAILPROF
                                FROM ' . $cegid_database . '.dbo.salaries
                                LEFT JOIN ' . $cegid_database . '.dbo.ETABLISS ON PSA_ETABLISSEMENT = ET_ETABLISSEMENT
                                LEFT JOIN ' . $cegid_database . '.dbo.UTILISAT ON US_AUXILIAIRE = PSA_SALARIE
                                LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = PSA_SALARIE
                                WHERE  PSE_EMAILPROF != ""
                                ORDER BY PSA_DATESORTIE DESC';
                        $i++;
                }
                //var_dump($requete);
                $results = $db->query($requete);
                while ($sal = $results->fetchRow()) {
                        $people[strtolower($sal->pse_emailprof)] = $sal;
                       
                        $domains = array ('groupe.proservia.fr', 'proservia.fr', 'manpowergroup-companies.fr', 'ovialis.fr', 'experis-it.fr', 'tapfin.fr');
                        $addresses = array();
                        foreach ($domains as $domain) {
                                $toTest = array(
                                        strtolower(trim(trim(cleanStr($sal->psa_prenom))).'.'.trim(trim(cleanStr($sal->psa_libelle)))),
                                        strtolower(substr(trim(trim(cleanStr($sal->psa_prenom))),0,1).''.trim(trim(cleanStr($sal->psa_libelle))))
                                        );
                                foreach ($toTest as $test) {
                                        $addresses[] = str_replace(' ', '' , $test.'@'.$domain);
                                        $addresses[] = str_replace(array(' ', '-', '\''), '' ,$test.'@'.$domain);
                                        $addresses[] = str_replace(' ', '-' ,$test.'@'.$domain);
                                }
                                $addresses[] = strtolower($sal->us_abrege).'@'.$domain;
                                $addresses[] = strtolower($sal->us_libelle).'@'.$domain;
                        }
                        foreach ($addresses as $address) {
                                $people[$address] = $sal;
                        }
       }
             
        $ds = ldap_connect('gazeille');//gazeille

        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        $userad = 'user_osc@proservia.lan';
        $pwd = utf8_encode(stripslashes('Proservi@DSI'));
        $r = ldap_bind($ds, $userad, $pwd);
       
       $infos = array();
       $dn = BASE_DN;
       $filter = '(&(objectCategory=person)(objectClass=user)(mobile=*))';
       $restriction = array('cn', 'sAMAccountName', 'mail', 'mobile', 'userAccountControl', 'lockoutTime', 'department');
       
       $sr = ldap_search($ds, $dn, $filter, $restriction);
       $infos = ldap_get_entries($ds, $sr);
       
       $fp = fopen('/tmp/smslist', 'w');
       
       foreach ($infos as $info) {
           if (isset($info['mail'])) {
               if (isset($info['mobile'])) {
                    if ($info['useraccountcontrol'][0] == '512' || $info['useraccountcontrol'][0] == '544' || $info['useraccountcontrol'][0] == '66048') {
                            if (!isset($people[strtolower($info['mail'][0])])) {
                                    //var_dump($info);
                                    $company = 'NULL';
                                    $status = 'NULL';
                                    $agency = 'NULL';
                            } else  {
                                    $company = $sal->societe;
                                    $agency = $sal->et_libelle;
                                    if ($people[strtolower($info['mail'][0])]->psa_travailn1 == '001') {
                                            $status = 'staff';
                                    } else {
                                            $status = 'collab';
                                    }
                            }
                            $department = $info['department'][0];
                            $phoneNumber = str_replace(' ', '', $info['mobile'][0]);
                            if (substr($phoneNumber, 0, 1) == '0') {
                                    $phoneNumber = '+33'.substr($phoneNumber, 1);
                            }
                            $line = $phoneNumber.';'.$status.';'.$company.';'.$agency.';'.$department.';'.$info['mail'][0];
                            $log .= $line.'<br />';
                            fwrite($fp, $line.PHP_EOL);
                         }
               }
           }
       }

       fclose($fp);
       
       $ssh  = ssh2_connect('proservia.systonic.net', 2222);
       //var_dump($ssh);
       var_dump(ssh2_auth_password($ssh, 'root', '@DSIextr@neT'));
       var_dump(ssh2_scp_send($ssh , '/tmp/smslist', '/var/www/html/sms/smslist'));
       $log .= 'fin';
       echo $log;
    }
   
    /**
     * Vérifie que les utilisateurs AD sont actifs dans cegid
     */
    public static function checkADUser() {
       $log = 'matricule;cn;id;mail;inCegid;present;datesortie;statutad;conclusion'."\r\n";

       $ad_info = array();
        $ds = ldap_connect(LDAP_SRV);      
        if ($ds) {
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            $userad = 'user_osc@proservia.lan';
            $pwd = utf8_encode(stripslashes('Proservi@DSI'));
            $r = @ldap_bind($ds, $userad, $pwd);

            if ($r != false) {
                $infos = array();
                $dn = BASE_DN;
                $restriction = array('cn', 'sAMAccountName', 'givenName', 'mail', 'description', 'Company', 'userAccountControl', 'lockoutTime', 'employeeId');
               
               
                $filter = '(&(objectCategory=person)(objectClass=user))';
                $cookie = '';
                do {
                    ldap_control_paged_result($ds, 1000, true, $cookie);

                    $sr = @ldap_search($ds, $dn, $filter, $restriction);
                    $infos2 = @ldap_get_entries($ds, $sr);
                    $infos = array_merge($infos, $infos2);

                    ldap_control_paged_result_response($ds, $sr, $cookie);
                } while($cookie !== null && $cookie != '');
               
               
                //var_dump($infos);
                $genericMatricules = array();
                foreach ($infos as $info) {
                    if (isset($info['cn']) && !in_array($info['employeeid'][0], $genericMatricules)) {
                        $info['mail'][0] = strtolower($info['mail'][0]);
                        $newArray = array();
                        $newArray['cn'] = $info['cn'][0];
                        $newArray['job'] = $info['description'][0];
                        $newArray['company'] = $info['company'][0];
                        $newArray['accountname'] = $info['samaccountname'][0];
                        $status = 'NULL';
                        if ($info['useraccountcontrol'][0] == '512' || $info['useraccountcontrol'][0] == '544' || $info['useraccountcontrol'][0] == '66048') {
                            $status = 'active';
                        }
                        if ($info['useraccountcontrol'][0] == '514' || $info['useraccountcontrol'][0] == '66050') {
                            $status = 'locked';
                        }
                        $newArray['status'] = $status;
                        $newArray['mail'] = $info['mail'][0];
                        $newArray['matricule'] = $info['employeeid'][0];
                        if (isset($info['employeeid']) && !in_array($info['employeeid'][0], $genericMatricules)) {
                            $ad_info[$info['employeeid'][0]] = $newArray;
                        } else {
                            $result  = 'ERROR';
                            if ($status == 'active') {
                                $result = 'ERROR-YES_AD_NO_CEGID';
                            }
                            if ($status == 'locked') {
                                $result = 'NO_AD_NO_CEGID';
                            }
                            if (!isset($_GET['filter']) || strpos($result, $_GET['filter']) !== false) {
                                $log .= $info['employeeid'][0].';'.$info['cn'][0].';'.$info['samaccountname'][0].';'.$info['mail'][0].';NO;;;'.$status.';'.$result."\r\n";
                            }
                        }
                    }
                }
            }
                     
            $tabSal = array();
            $db = connecter_cegid();
            $i = 0;
            $requete = '';
            $cegid_databases = array ('PROSERVIA' => 'PRO', 'DAMCONSULT' => 'DCO', 'TAPFIN' => 'TAP', 'TIMARANCE' => 'TIM',
           'DAMILOIT' => 'DAM', 'EXPERISIT' => 'EXP', 'OVIALIS_EXP' => 'OVS', 'FINATEL' => 'FIN');
            foreach ($cegid_databases as $cegid_database => $id_base) {
                $requete .= ($i != 0) ? ' UNION' : '';
                $requete .= ' (SELECT "' . $cegid_database . '" AS SOCIETE, "'.$id_base.'" + RIGHT(PSA_SALARIE, 7) AS MATRICULE, PSA_SALARIE, PSA_TRAVAILN1, PSA_LIBELLE,PSA_PRENOM, PSA_DATEENTREE, PSA_DATESORTIE,
                 ET_LIBELLE, ET_ADRESSE1, ET_ADRESSE2, ET_ADRESSE3, ET_CODEPOSTAL, ET_PAYS, ET_VILLE, ET_TELEPHONE,
                 US_ABREGE, US_LIBELLE, PSE_EMAILPROF
                 FROM ' . $cegid_database . '.dbo.salaries
                 LEFT JOIN ' . $cegid_database . '.dbo.ETABLISS ON PSA_ETABLISSEMENT = ET_ETABLISSEMENT
                 LEFT JOIN ' . $cegid_database . '.dbo.UTILISAT ON US_AUXILIAIRE = PSA_SALARIE
                 LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = PSA_SALARIE
                 ) ';
                $i++;
            }
            $response = $db->query('SELECT * FROM ('.$requete.') AS TOUT ORDER BY PSA_DATESORTIE DESC');
           
            while ($sal = $response->fetchRow()) {
                $tabSal[$sal->matricule] = $sal;
            }
           
            foreach ($ad_info as $matricule => $info) {
                if (isset($tabSal[$matricule])) {
                    $sal = $tabSal[$matricule];
                    $inCegid = 'YES';
                    //var_dump(substr($sal->psa_datesortie, 0, 10));
                    if (strtotime(substr($sal->psa_datesortie, 0, 10)) == false || strtotime(substr($sal->psa_datesortie, 0, 10)) > (time() + 1 * 24 * 3600)) {//si la personne est encore proservia
                        $employee = 'IN';
                    } else {
                        $employee = 'OUT';
                    }
                    $result = '';
                    if ($employee == 'IN' && $info['status'] == 'active') {
                        $result = 'OK-YES_AD_YES_CEGID';
                    }
                    if ($employee == 'OUT' && $info['status'] == 'active') {
                        $result = 'ERROR-YES_AD_OUT_CEGID';
                    }
                    if ($employee == 'IN' && $info['status'] == 'locked') {
                        $result = 'NO_AD_YES_CEGID';
                    }
                    if ($employee == 'OUT' && $info['status'] == 'locked') {
                        $result = 'OK-NO_AD_OUT_CEGID';
                    }
                } else {
                    $inCegid = 'NO';
                    $employee = '';
                    if ($info['status'] == 'active') {
                        $result = 'ERROR-YES_AD_NO_CEGID';
                    } else if ($info['status'] == 'locked') {
                        $result = 'NO_AD_NO_CEGID';
                    } else {
                        $result = 'ERROR';
                    }
                }
                if (!isset($_GET['filter']) || strpos($result, $_GET['filter']) !== false) {
                    $log .= $info['matricule'].';'.$info['cn'].';'.$info['accountname'].';'.$info['mail'].';'.$inCegid.';'.$employee.';'.$sal->psa_datesortie.';'.$info['status'].';'.$result."\r\n";
                }
            }
        } else {
            $log .= 'error - no ad connection';
        }
       
       $log .= '';
       echo $log;
    }    
   
    /**
     * Vérifie que les comptes LDAP sont actifs dans CEGID
     */
    public static function checkLDAPUser() {
        if ($ldap = ldap_connect('213.56.106.24')) {
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            $filter = '(uid=*)';
            $fields = array('mail', 'accountStatus', 'cn', 'uid', 'employeeNumber');
            ldap_bind($ldap, 'uid=extranet,ou=admin,ou=messagerie,dc=groupe,dc=proservia,dc=fr', '?xtr@n?t');

            $sr = ldap_search($ldap, 'OU=comptes,OU=messagerie,DC=groupe,DC=proservia,DC=fr', $filter, $fields);

            $entries = ldap_get_entries($ldap, $sr);
            //var_dump($entries);
            $i = 0;
            $ldap_info = array();
            foreach ($entries as $entry) {
                if (isset($entry['mail'])) {
                    $tab = array();
                    $entry['mail'][0] = strtolower($entry['mail'][0]);
                    $tab['mail'] = $entry['mail'][0];
                    $tab['name'] = $entry['cn'][0];
                    $status = $entry['accountstatus'][0];
                    if ($status == 'active') {
                        $status = 'enabled';
                    }
                    $tab['status'] = $status;
                    $tab['uid'] = $entry['uid'][0];
                    $tab['matricule'] = $entry['employeenumber'][0];;
                    $ldap_info[$i] = $tab;
                    $i++;
                }
               
            }
            //var_dump($ldap_info);
           
            $tabSal = array();
            $db = connecter_cegid();
            $i = 0;
            $requete = '';
            $cegid_databases = array ('PROSERVIA', 'DAMCONSULT', 'TAPFIN', 'TIMARANCE', 'DAMILOIT', 'EXPERISIT', 'OVIALIS_EXP');
            foreach ($cegid_databases as $cegid_database) {
                $requete .= ($i != 0) ? ' UNION' : '';
                $requete .= ' (SELECT "' . $cegid_database . '" AS SOCIETE, PSA_SALARIE, PSA_TRAVAILN1, PSA_LIBELLE,PSA_PRENOM, PSA_DATEENTREE, PSA_DATESORTIE,
                 ET_LIBELLE, ET_ADRESSE1, ET_ADRESSE2, ET_ADRESSE3, ET_CODEPOSTAL, ET_PAYS, ET_VILLE, ET_TELEPHONE,
                 US_ABREGE, US_LIBELLE, PSE_EMAILPROF
                 FROM ' . $cegid_database . '.dbo.salaries
                 LEFT JOIN ' . $cegid_database . '.dbo.ETABLISS ON PSA_ETABLISSEMENT = ET_ETABLISSEMENT
                 LEFT JOIN ' . $cegid_database . '.dbo.UTILISAT ON US_AUXILIAIRE = PSA_SALARIE
                 LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = PSA_SALARIE
                 ) ';
                $i++;
            }
            $response = $db->query('SELECT * FROM ('.$requete.') AS TOUT ORDER BY PSA_DATESORTIE DESC');
            //var_dump($response);
            while ($sal = $response->fetchRow()) {
                $tabSal[(int)$sal->psa_salarie] = $sal;
                $tabSal[strtolower($sal->pse_emailprof)] = $sal;
            }
            $log = 'matricule;mail;uid;nom;dansCegid;employeePresent;dateDeSortie;statutLdap;conclusion'."\r\n";
            foreach ($ldap_info as $i => $info) {
                $sal = null;
                if (isset($tabSal[(int)$info['matricule']])) {
                    $sal = $tabSal[(int)$info['matricule']];
                } elseif(isset($tabSal[$info['mail']])) {
                    $sal = $tabSal[$info['mail']];
                }
               
                if ($sal != null) {//si on retrouve le salarié
                    $inCegid = 'YES';
                    if (strtotime(substr($sal->psa_datesortie, 0, 10)) == false || strtotime(substr($sal->psa_datesortie, 0, 10)) > (time() + 1 * 24 * 3600)) {//si la personne est encore proservia
                        $employee = 'IN';
                    } else {
                        $employee = 'OUT';
                    }
                    $result = 'ERROR';
                    if ($employee == 'IN' && $info['status'] == 'enabled') {
                        $result = 'OK-YES_LDAP_YES_CEGID';
                    }
                    if ($employee == 'OUT' && $info['status'] == 'enabled') {
                        $result = 'ERROR-YES_LDAP_OUT_CEGID';
                    }
                    if ($employee == 'IN' && $info['status'] == 'locked') {
                        $result = 'NO_LDAP_YES_CEGID';
                    }
                    if ($employee == 'OUT' && $info['status'] == 'locked') {
                        $result = 'OK-NO_LDAP_OUT_CEGID';
                    }
                } else {
                    $inCegid = 'NO';
                    $employee = '';
                    if ($info['status'] == 'enabled') {
                        $result = 'ERROR-YES_LDAP_NO_CEGID';
                    } else if ($info['status'] == 'locked') {
                        $result = 'NO_LDAP_NO_CEGID';
                    } else {
                        $result = 'ERROR';
                    }
                }
                if (!isset($_GET['filter']) || strpos($result, $_GET['filter']) !== false) {
                    $log .= $info['matricule'].';'.$info['mail'].';'.$info['uid'].';'.$info['name'].';'.$inCegid.';'.$employee.';'.$sal->psa_datesortie.';'.$info['status'].';'.$result."\r\n";
                }
            }
            ldap_close($ldap);
            echo $log;
        } else {
            die('NO LDAP ACCESS');
        }
    }
   
    /**
     * Export de tous les salariés pour intégration AD
     */
    public static function exportSalarie() {
       $log = 'matricule;prenom;nom;fonction;service;societe;agence;email;site_web_societe;addresse_rue1;addresse_rue2;addresse_rue3;addresse_cp;addresse_ville;adresse_departement;addresse_pays;responsable_prenom;responsable_nom;responsable_matricule;staff;tel_court;tel_direct;tel_mobile;fax'."\r\n";
       
       //bases :
       $cegid_databases = array ('PROSERVIA' => 'PRO', 'DAMCONSULT' => 'DCO', 'TAPFIN' => 'TAP', 'TIMARANCE' => 'TIM',
           'DAMILOIT' => 'DAM', 'EXPERISIT' => 'EXP', 'OVIALIS_EXP' => 'OVS', 'FINATEL' => 'FIN');
       $societe = array ('PROSERVIA' => 'PROSERVIA', 'DAMCONSULT' => 'DAMILO CONSULTING', 'TAPFIN' => 'TAPFIN', 'TIMARANCE' => 'TIMARANCE',
           'DAMILOIT' => 'DAMILO IT', 'EXPERISIT' => 'EXPERIS IT', 'OVIALIS_EXP' => 'EXPERIS IT', 'FINATEL' => 'PROSERVIA');
       $siteweb = array ('PROSERVIA' => 'http://proservia.fr/', 'TAPFIN' => 'http://www.tapfin.com/', 'EXPERIS IT' => 'http://www.experis-it.fr/');
       $db = connecter_cegid();
       $i = 0;
       $requete = '';
       foreach ($cegid_databases as $cegid_database => $id_base) {
            $requete .= ($i != 0) ? ' UNION' : '';
            $requete .= ' (SELECT "' . $cegid_database . '" AS SOCIETE, RIGHT(SAL.PSA_SALARIE,7) AS MATRICULE_SAL, SAL.PSA_SALARIE, SAL.PSA_NUMEROSS, SAL.PSA_TRAVAILN1,
                SAL.PSA_LIBELLE, SAL.PSA_PRENOM, SAL.PSA_DATEENTREE, SAL.PSA_DATESORTIE, RIGHT(RES.PSA_SALARIE,7) AS MATRICULE_RES,
                RES.PSA_LIBELLE AS RES_LIBELLE, RES.PSA_PRENOM AS RES_PRENOM, RES.PSA_SALARIE AS RES_SALARIE,
                ET_LIBELLE, ET_ADRESSE1, ET_ADRESSE2, ET_ADRESSE3, ET_CODEPOSTAL, ET_PAYS, ET_VILLE, ET_TELEPHONE,
                US_ABREGE, US_LIBELLE, PSE_EMAILPROF, CC_LIBELLE, PGS_NOMSERVICE
                FROM ' . $cegid_database . '.dbo.SALARIES as SAL
                LEFT JOIN ' . $cegid_database . '.dbo.PGAFFECTROLERH ON (PFH_SALARIE = SAL.PSA_SALARIE AND PFH_TYPEAFFECTROLE = "SAL" AND PFH_ROLERH = "RES" AND PFH_MODULERH = "GAL")
                LEFT JOIN ' . $cegid_database . '.dbo.SALARIES AS RES ON PFH_REFERENTRH = RES.PSA_SALARIE
                LEFT JOIN ' . $cegid_database . '.dbo.ETABLISS ON SAL.PSA_ETABLISSEMENT = ET_ETABLISSEMENT
                LEFT JOIN ' . $cegid_database . '.dbo.UTILISAT ON US_AUXILIAIRE = SAL.PSA_SALARIE
                LEFT JOIN ' . $cegid_database . '.dbo.DEPORTSAL ON PSE_SALARIE = SAL.PSA_SALARIE
                LEFT JOIN ' . $cegid_database . '.dbo.CHOIXCOD ON (SAL.PSA_LIBELLEEMPLOI = CC_CODE AND CC_TYPE = "PLE")
                LEFT JOIN ' . $cegid_database . '.dbo.SERVICES ON PSE_CODESERVICE = PGS_CODESERVICE
             ) ';
            $i++;
        }
       
        $response = $db->query('SELECT * FROM ('.$requete.') AS TOUT WHERE PSA_DATESORTIE > GETDATE() OR PSA_DATESORTIE = "1900-01-01 00:00:00.000" ORDER BY PSA_DATESORTIE DESC');
        //var_dump($response);
        while ($sal = $response->fetchRow()) {
            $log .= $cegid_databases[$sal->societe].$sal->matricule_sal.';'.$sal->psa_prenom.';'.$sal->psa_libelle.';'.$sal->cc_libelle.';'.$sal->pgs_nomservice.';'.
                    $societe[$sal->societe].';'.$sal->et_libelle.';'.$sal->pse_emailprof.';'.$siteweb[$societe[$sal->societe]].';'.$sal->et_adresse1.';'.$sal->et_adresse2.';'.$sal->et_adresse3.
                    ';'.$sal->et_codepostal.';'.$sal->et_ville.';'/*.ADDRESSE_DEPARTEMENT*/.';'.$sal->et_pays.';'.$sal->res_prenom.';'.$sal->res_libelle.';'.
                    $cegid_databases[$sal->societe].$sal->matricule_res.';'.(($sal->psa_travailn1 == 1) ? '1' : '0').';'./*TEL_COURT.*/';'/*.TEL_DIRECT*/.';'/*.TEL_MOBILE*/.';'/*.FAX*/."\r\n";
        }
       
       $log .= '';
       echo $log;
    }
}

?>
 

