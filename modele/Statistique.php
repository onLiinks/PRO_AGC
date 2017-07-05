<?php

/**
 * Fichier Statistique.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Statistique
 */
class Statistique {

    /**
     * Fonction qui sert à insérer les donées relatives aux objectifs, chiffres d'affaires facturés en rapport avec les données de l'AGC
     *
     */
    public static function statsObjectif() {
        $db = connecter();
        $result = $db->query('SELECT * FROM societe');
        while ($ligne = $result->fetchRow()) {
            $_SESSION['societe'] = $ligne->libelle;
            $db2 = connecter();
            $db2->query('DELETE FROM budget');
        }
        $fp = fopen(SIDCDGC_BUDGET_URL, 'r');
        $mots = explode("\n", fread($fp, filesize(SIDCDGC_BUDGET_URL)));
        $taille = count($mots);
        $i = 0;
        while ($i < $taille) {
            list($type, $societe, $annee, $mois, $commercial, $Id_agence, $Id_pole, $Id_type_contrat, $Id_affaire, $Id_affaire_cegid, $nature, $ca) = explode(';', $mots[$i]);
            $ca_budget = $ca_provisoire = $ca_facture = $ca_regule = $ca_commande = $ca_commande_total = 0;
            $tab_Id_aff = array();
            if ($type == 'REA') {
                if ($nature == 110) {
                    $ca_provisoire = $ca;
                } elseif ($nature == 120) {
                    $ca_facture = $ca;
                } elseif ($nature == 130) {
                    $ca_regule = $ca;
                }
            } elseif ($type == 'BUD') {
                $ca_budget = $ca;
            }

            if ($societe != $_SESSION['societe']) {
                $_SESSION['societe'] = $societe;
                $db = connecter();
            }
            $tab_Id_aff = explode('-', $Id_affaire);

            $nbAff = count($tab_Id_aff);
            if ($nbAff > 1) {
                $j = 0;
                while ($j < $nbAff) {
                    $ca_commande = Affaire::caCommandeMensuelRessource($tab_Id_aff[$j], $Id_type_contrat, $mois, $annee);
                    $ca_commande_total += $ca_commande;
                    ++$j;
                }
                $ratio = array();
                $j = 0;
                while ($j < $nbAff) {
                    $ratio[$j] = 1 / $nbAff;
                    if ($ca_commande_total != 0) {
                        $ratio[$j] = Affaire::caCommandeMensuelRessource($tab_Id_aff[$j], $Id_type_contrat, $mois, $annee) / $ca_commande_total;
                    }
                    $ratio[$j] = round($ratio[$j], 2);
                    $ca_provisoire_ratio = $ratio[$j] * $ca_provisoire;
                    $ca_facture_ratio = $ratio[$j] * $ca_facture;
                    $ca_regule_ratio = $ratio[$j] * $ca_regule;

                    $db->query('INSERT INTO budget SET mois="' . mysql_real_escape_string($annee) . '-' . mysql_real_escape_string($mois) . '", commercial="' . mysql_real_escape_string(strtolower($commercial)) . '"
			      , Id_agence="' . mysql_real_escape_string($Id_agence) . '", Id_pole="' . mysql_real_escape_string($Id_pole) . '"
			      , Id_type_contrat="' . mysql_real_escape_string($Id_type_contrat) . '", Id_affaire="' . mysql_real_escape_string($tab_Id_aff[$j]) . '", Id_affaire_cegid="' . mysql_real_escape_string($Id_affaire_cegid) . '"
			      , ca_budget="' . mysql_real_escape_string($ca_budget) . '", ca_facture="' . mysql_real_escape_string($ca_facture_ratio) . '", ca_regule="' . mysql_real_escape_string($ca_regule_ratio) . '"
			      , ca_provisoire="' . mysql_real_escape_string($ca_provisoire_ratio) . '"');
                    ++$j;
                }
            } else {
                $db->query('INSERT INTO budget SET mois="' . mysql_real_escape_string($annee) . '-' . mysql_real_escape_string($mois) . '", commercial="' . mysql_real_escape_string(strtolower($commercial)) . '"
			    , Id_agence="' . mysql_real_escape_string($Id_agence) . '", Id_pole="' . mysql_real_escape_string($Id_pole) . '"
			    , Id_type_contrat="' . mysql_real_escape_string($Id_type_contrat) . '", Id_affaire="' . mysql_real_escape_string($tab_Id_aff[0]) . '", Id_affaire_cegid="' . mysql_real_escape_string($Id_affaire_cegid) . '"
			    , ca_budget="' . mysql_real_escape_string($ca_budget) . '", ca_facture="' . mysql_real_escape_string($ca_facture) . '", ca_regule="' . mysql_real_escape_string($ca_regule) . '"
			    , ca_provisoire="' . mysql_real_escape_string($ca_provisoire) . '"');
            }
            ++$i;
        }
        fclose($fp);

        $_SESSION['societe'] = 'PROSERVIA';
        $db = connecter();

        $fp = fopen(ANNUAL_BUDGET, 'r');
        $mots = explode("\n", fread($fp, filesize(ANNUAL_BUDGET)));
        $taille = count($mots);
        $i = 0;
        while ($i < $taille) {
            $ca_recurrent = $ca_a_trouver = 0;
            list($annee, $type, $Id, $ca_recurrent, $ca_a_trouver) = explode(';', $mots[$i]);
            $db->query('INSERT INTO budget SET mois="' . mysql_real_escape_string($annee) . '-01", ' . mysql_real_escape_string($type) . '="' . mysql_real_escape_string($Id) . '",
			ca_recurrent="' . mysql_real_escape_string($ca_recurrent) . '", ca_a_trouver="' . mysql_real_escape_string($ca_a_trouver) . '"');
            ++$i;
        }
        fclose($fp);
    }

    /**
     * Fonction qui sert à insérer les donées relatives aux objectifs, chiffres d'affaires facturés en rapport avec les données de l'AGC
     *
     */
    public static function statsCASigneOpTermine() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $result = $db->query('SELECT a.Id_affaire,commercial,Id_agence,Id_pole,Id_type_contrat,
			DATE_FORMAT(date_debut,"%Y-%m") as debut, DATE_FORMAT(date_fin_commande,"%Y-%m") as fin FROM affaire a
			INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire
			INNER JOIN proposition pr ON pr.Id_affaire=a.Id_affaire 
			INNER JOIN affaire_pole ap ON pr.Id_affaire=ap.Id_affaire 
			WHERE chiffre_affaire!=0 AND date_debut !="0000-00-00" AND date_fin_commande !="0000-00-00"
			AND Id_statut IN (5,8,9) ORDER BY a.Id_affaire');

            while ($ligne = $result->fetchRow()) {
                $result2 = $db->query('SELECT mois FROM mois WHERE mois BETWEEN "' . mysql_real_escape_string($ligne->debut) . '" AND "' . mysql_real_escape_string($ligne->fin) . '"');
                while ($ligne2 = $result2->fetchRow()) {
                    $month = explode('-', $ligne2->mois);
                    $caCommande = Affaire::caCommandeMensuelRessource($ligne->id_affaire, $ligne->id_type_contrat, $month[1], $month[0]);
                    if ($caCommande != 0) {
                        $db->query('INSERT INTO budget SET Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '", 
				        commercial="' . mysql_real_escape_string($ligne->commercial) . '", Id_agence="' . mysql_real_escape_string($ligne->id_agence) . '", Id_pole="' . mysql_real_escape_string($ligne->id_pole) . '",
				        Id_type_contrat="' . mysql_real_escape_string($ligne->id_type_contrat) . '", mois="' . mysql_real_escape_string($ligne2->mois) . '", 
				        ca_commande = "' . mysql_real_escape_string($caCommande) . '"');
                    }
                }
            }
        }
    }

    /**
     * Fonction qui sert à insérer les donées relatives aux chiffres d'affaires probables pour les affaires AGC Signées, Opérationnelles ou terminées.
     *
     */
    public static function statsCAProbableSigneOpTermine() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $result = $db->query('SELECT a.Id_affaire,commercial,Id_agence,Id_pole,Id_type_contrat,
			DATE_FORMAT(date_fin_commande,"%Y-%m-") as debut, 
		    DATE_FORMAT(date_fin_previsionnelle,"%Y-%m") as fin
			FROM affaire a
			INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
			INNER JOIN proposition pr ON pr.Id_affaire=a.Id_affaire 
			INNER JOIN affaire_pole ap ON pr.Id_affaire=ap.Id_affaire 
			WHERE chiffre_affaire!=0 AND date_debut !="0000-00-00" AND date_fin_commande !="0000-00-00"
			AND Id_statut IN (5,8,9) 
			AND date_fin_previsionnelle!=(SELECT date_fin_commande FROM planning WHERE Id_affaire=a.Id_affaire)');
            while ($ligne = $result->fetchRow()) {
                $result2 = $db->query('SELECT mois FROM mois WHERE mois BETWEEN "' . mysql_real_escape_string($ligne->debut) . '" AND "' . mysql_real_escape_string($ligne->fin) . '"');
                while ($ligne2 = $result2->fetchRow()) {
                    $month = explode('-', $ligne2->mois);
                    $caProbable = Affaire::caProbableMensuelRessource($ligne->id_affaire, $ligne->id_type_contrat, $month[1], $month[0]);
                    if ($caProbable != 0) {
                        $db->query('INSERT INTO budget SET Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '", 
				        commercial="' . mysql_real_escape_string($ligne->commercial) . '", Id_agence="' . mysql_real_escape_string($ligne->id_agence) . '", Id_pole="' . mysql_real_escape_string($ligne->id_pole) . '",
				        Id_type_contrat="' . mysql_real_escape_string($ligne->id_type_contrat) . '", mois="' . mysql_real_escape_string($ligne2->mois) . '", 
					    ca_probable = "' . mysql_real_escape_string($caProbable) . '"');
                    }
                }
            }
        }
    }

    /**
     * Fonction qui sert à insérer les données relatives aux chiffres d'affaires des autres affaires : Non signées, Non Opérationnelles, Non terminées en rapport avec les données de l'AGC
     * On considère pour ces affaires qu'il n'y a donc pas de CA probable dans l'avenir.
     */
    public static function statsCAAutreAffaires() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $db->query('DELETE FROM stats_ca');
            $result = $db->query('SELECT a.Id_affaire,Id_type_contrat,
			DATE_FORMAT(date_debut,"%Y-%m") as debut, DATE_FORMAT(date_fin_commande,"%Y-%m") as fin
			FROM affaire a
			INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
			INNER JOIN proposition pr ON pr.Id_affaire=a.Id_affaire 
			WHERE chiffre_affaire!=0 AND Id_statut NOT IN (5,8,9)');
            while ($ligne = $result->fetchRow()) {
                $result2 = $db->query('SELECT mois FROM mois WHERE mois BETWEEN "' . mysql_real_escape_string($ligne->debut) . '" AND "' . mysql_real_escape_string($ligne->fin) . '"');
                while ($ligne2 = $result2->fetchRow()) {
                    $month = explode('-', $ligne2->mois);
                    $caAutre = Affaire::caCommandeMensuelRessource($ligne->id_affaire, $ligne->id_type_contrat, $month[1], $month[0]);
                    if ($caAutre != 0) {
                        $db->query('INSERT INTO stats_ca SET Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '", mois="' . mysql_real_escape_string($ligne2->mois) . '", 
					    ca_probable = "' . $caAutre . '"');
                    }
                }
            }
        }
    }

    /**
     * Fonction qui affiche le détail des lignes de la table budget pour une affaire donnée.
     *
     */
    public static function getCAPrevisionnelDetails($Id_affaire) {
        $db = connecter();
        $html = '
		<h2>Détail de la table budget pour l\'affaire ' . $Id_affaire . '.</h2>
		<table border=1>
		    <tr>
		        <th>Id affaire</th>
				<th>Client</th>
				<th>Mois</th>
				<th>Commercial</th>
				<th>Agence</th>
				<th>Pôle</th>
				<th>Type de contrat</th>
				<th>CA Facturé</th>
				<th>CA Régulé</th>
				<th>CA Provisoire</th>
				<th>CA Commandé</th>
				<th>CA Probable</th>
			</tr>	
		';

        $result = $db->query('SELECT * FROM budget WHERE Id_affaire=' . mysql_real_escape_string($Id_affaire) . '');
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, Affaire::getIdCompte($Id_affaire));
            $html .= '
			<tr>
				<td><a href="#" onclick="javascript:window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '\')">' . $ligne->id_affaire . '</a></td>
				<td>' . $compte->nom . '</td>
				<td>' . $ligne->mois . '</td>
				<td>' . $ligne->commercial . '</td>
				<td>' . Agence::getLibelle($ligne->id_agence) . '</td>
				<td>' . Pole::getLibelle($ligne->id_pole) . '</td>
				<td>' . TypeContrat::getLibelle($ligne->id_type_contrat) . '</td>
				<td>' . $ligne->ca_facture . '</td>
				<td>' . $ligne->ca_regule . '</td>
				<td>' . $ligne->ca_provisoire . '</td>
				<td>' . $ligne->ca_commande . '</td>
				<td>' . $ligne->ca_probable . '</td>
			</tr>
			';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Fonction qui met à jour la table stats_odm
     *
     */
    public static function statsODM() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($l->libelle);
            $db = connecter();
            $db->query('DELETE FROM stats_odm');
            $result = $db->query('SELECT Id_ordre_mission,Id_compte,Id_ressource FROM ordre_mission ORDER BY Id_ordre_mission DESC');
            while ($ligne = $result->fetchRow()) {
                $compte = CompteFactory::create(null, $ligne->id_compte);
                $ressource = RessourceFactory::create('SAL', $ligne->id_ressource, null);
                $db->query('INSERT INTO stats_odm SET Id_ordre_mission="' . mysql_real_escape_string($ligne->id_ordre_mission) . '"
				, compte="' . mysql_real_escape_string($compte->nom) . '", ressource="' . mysql_real_escape_string($ressource->getName()) . '",
				mail="' . mysql_real_escape_string(Ressource::getMail($ligne->id_ressource)) . '"');
            }
        }
    }

    /**
     * Fonction qui remplit la table  bilan_activite_commerciale
     *
     */
    public static function comercialActivityReport() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe');
        //$annee = date('Y');
        //$i     = date('W');

        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();

            $fp = fopen(SIDCDGC_NBJPRESENCECCIAUXPARSEM_URL, 'r');
            $mots = explode("\n", fread($fp, filesize(SIDCDGC_NBJPRESENCECCIAUXPARSEM_URL)));
            $taille = count($mots);
            $i = 0;
            while ($i < $taille) {
                list($annee, $semaine, $matricule, $nbJPresence) = explode(';', $mots[$i]);
                $login = Utilisateur::getLogin(substr($matricule, 4));
                if ($login != '') {
                    $db->query('INSERT INTO presence SET 
			        annee="' . mysql_real_escape_string($annee) . '", 
			        semaine="' . mysql_real_escape_string($semaine) . '",
			        commercial="' . mysql_real_escape_string($login) . '",
			        nbj_presence="' . mysql_real_escape_string($nbJPresence) . '"
			        ON DUPLICATE KEY UPDATE
			        nbj_presence="' . mysql_real_escape_string($nbJPresence) . '"
			        ');
                }
                ++$i;
            }

            $annee = 2009;
            $fin = 2010;
            while ($annee <= $fin) {
                if (date('Y') == $annee) {
                    $nbWorkingDaysYear = workingDays(date('Y') . '-01-01', DATE);
                } else {
                    $nbWorkingDaysYear = workingDays($annee . '-01-01', $annee . '-12-31');
                }

                $result = $db->query('SELECT left(bu.mois,4),bu.commercial,sum(ca_budget) as objectif,
					sum(ca_facture+ca_regule+ca_provisoire+ca_commande+ca_probable) as ca_previsionnel
					FROM budget bu WHERE left(bu.mois,4)="' . mysql_real_escape_string($annee) . '" AND bu.commercial !=""
					GROUP BY left(bu.mois,4),bu.commercial');

                while ($ligne = $result->fetchRow()) {
                    $i = 1;
                    while ($i <= 53) {
                        $weekDates = weekDates($i, $annee);
                        $lundiSemaine = $weekDates[1];
                        $dimancheSemaine = $weekDates[7];

                        if ($i == date('W') && $annee == date('Y')) {
                            $date = DATE;
                        } else {
                            $date = $dimancheSemaine;
                        }
                        echo 'annee :' . $annee . ' | semaine :' . $i . ' | Lundi : ' . $lundiSemaine . ' | Dimanche :' . $date . '<br />';

                        $db->query('INSERT INTO report_activite_commerciale 
							SET annee="' . mysql_real_escape_string($annee) . '", semaine="' . mysql_real_escape_string($i) . '",commercial="' . mysql_real_escape_string($ligne->commercial) . '",
							ca_objectif_annuel="' . mysql_real_escape_string($ligne->objectif) . '",ca_previsionnel_annee="' . mysql_real_escape_string(round($ligne->ca_previsionnel, 2)) . '"
                            ON DUPLICATE KEY UPDATE
								ca_objectif_annuel="' . mysql_real_escape_string($ligne->objectif) . '",
								ca_previsionnel_annee="' . mysql_real_escape_string(round($ligne->ca_previsionnel, 2)) . '"
							');

                        $ligne2 = $db->query('SELECT count(DISTINCT a.Id_affaire) as nb FROM historique_statut hs INNER JOIN affaire a 
							ON a.Id_affaire=hs.Id_affaire WHERE hs.Id_statut=5 
							AND date>="' . mysql_real_escape_string($annee) . '-01-01" and date<="' . mysql_real_escape_string($date) . '" AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $result3 = $db->query('SELECT a.Id_affaire,a.Id_compte FROM historique_statut hs INNER JOIN affaire a 
							ON a.Id_affaire=hs.Id_affaire WHERE hs.Id_statut=5 
							AND YEARWEEK(DATE_ADD(date,INTERVAL 1 WEEK),3)="' . mysql_real_escape_string($annee) . mysql_real_escape_string($i) . '" AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"');

                        $nbAffaireSignee = 0;
                        $compteAffaireSignee = '';

                        while ($ligne3 = $result3->fetchRow()) {
                            $compte = CompteFactory::create(null, $ligne3->id_compte);
                            $nbAffaireSignee +=1;
                            $compteAffaireSignee .= ' - ' . $compte->nom;
                        }

                        $ligne4 = $db->query('SELECT sum(ca_facture+ca_regule+ca_provisoire+ca_commande+ca_probable) as ca_trouve
							FROM budget b INNER JOIN historique_statut hs ON hs.Id_affaire=b.Id_affaire 
							WHERE hs.Id_statut=5 AND date>="' . mysql_real_escape_string($annee) . '-01-01" and date<="' . $date . '" AND left(b.mois,4)="' . mysql_real_escape_string($annee) . '" 
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne5 = $db->query('SELECT sum(ca_facture+ca_regule+ca_provisoire+ca_commande+ca_probable) as ca_trouve_sem_prec
							FROM budget b INNER JOIN historique_statut hs ON hs.Id_affaire=b.Id_affaire 
							WHERE hs.Id_statut=5 AND YEARWEEK(DATE_ADD(date,INTERVAL 1 WEEK),3)="' . mysql_real_escape_string($annee) . mysql_real_escape_string($i) . '" 
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne6 = $db->query('SELECT sum(chiffre_affaire) as ca_pis FROM proposition p INNER JOIN affaire a
							ON a.Id_affaire=p.Id_affaire WHERE Id_statut=1 AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne7 = $db->query('SELECT sum(chiffre_affaire) as ca_ecr FROM proposition p INNER JOIN affaire a
							ON a.Id_affaire=p.Id_affaire WHERE Id_statut=3 AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne8 = $db->query('SELECT sum(chiffre_affaire) as ca_aq FROM proposition p INNER JOIN affaire a
							ON a.Id_affaire=p.Id_affaire WHERE Id_statut=7 AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne9 = $db->query('SELECT sum(chiffre_affaire) as ca_rem FROM proposition p INNER JOIN affaire a
							ON a.Id_affaire=p.Id_affaire WHERE Id_statut=4 AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ca_pipe_total = $ligne6->ca_pis + $ligne7->ca_ecr + $ligne8->ca_aq + $ligne9->ca_rem;

                        $ligne10 = $db->query('SELECT count(Id_affaire) as nb FROM affaire WHERE Id_statut=3
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne11 = $db->query('SELECT count(Id_affaire) as nb FROM affaire WHERE Id_statut=4
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne12 = $db->query('SELECT count(Id_affaire) as nb FROM affaire WHERE Id_statut=7
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne13 = $db->query('SELECT count(Id_rendezvous) as nb FROM rendezvous 
							WHERE date BETWEEN "' . $annee . '-01-01" AND "' . $date . '" AND createur="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne14 = $db->query('SELECT count(Id_rendezvous) as nb FROM rendezvous 
							WHERE YEARWEEK(DATE_ADD(date,INTERVAL 1 WEEK),3)="' . mysql_real_escape_string($annee) . mysql_real_escape_string($i) . '" 
							AND createur="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne15 = $db->query('SELECT count(Id_rendezvous) as nb FROM rendezvous 
							WHERE YEARWEEK(date,3)="' . mysql_real_escape_string($annee) . mysql_real_escape_string($i) . '" AND date <="' . mysql_real_escape_string($date) . '" 
							AND createur="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne16 = $db->query('SELECT count(Id_rendezvous) as nb FROM rendezvous 
							WHERE date >"' . mysql_real_escape_string($date) . '" AND createur="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $ligne17 = $db->query('SELECT count(Id_rendezvous) as nb FROM rendezvous 
							WHERE type="Prospect" AND date BETWEEN "' . mysql_real_escape_string($annee) . '-01-01" AND "' . mysql_real_escape_string($date) . '" AND createur="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        $pct_prospection = $nb_rdv_moy_sem = 0;
                        if ($ligne13->nb != 0) {
                            $pct_prospection = round(100 * $ligne17->nb / $ligne13->nb, 2);
                        }

                        $ligne18 = $db->query('SELECT count(DISTINCT(Id_ressource)) as nb FROM proposition_ressource pr
							INNER JOIN proposition p ON p.Id_proposition=pr.Id_proposition
							INNER JOIN affaire a ON p.Id_affaire=a.Id_affaire
							WHERE type="T" AND Id_statut IN (5,8,9) AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"
							AND fin >= "' . mysql_real_escape_string($lundiSemaine) . '" AND debut<= "' . mysql_real_escape_string($lundiSemaine) . '"')->fetchRow();

                        if ($ligne18->nb < 10) {
                            $obj_pct_prospection = 75;
                        } elseif ($ligne18->nb >= 10 && $ligne18->nb < 20) {
                            $obj_pct_prospection = 50;
                        } elseif ($ligne18->nb >= 20) {
                            $obj_pct_prospection = 25;
                        }

                        $ligne19 = $db->query('SELECT sum(nbj_presence) as nbj FROM presence
							WHERE annee="' . mysql_real_escape_string($annee) . '" AND semaine<="' . mysql_real_escape_string($i) . '"
							AND commercial="' . mysql_real_escape_string($ligne->commercial) . '"')->fetchRow();

                        if ($ligne19->nbj != 0) {
                            $nb_rdv_moy_sem = round(5 * $ligne13->nb / $ligne19->nbj, 2);
                        }

                        $db->query('UPDATE report_activite_commerciale SET
								nb_signature_annee="' . mysql_real_escape_string($ligne2->nb) . '",
								nb_signature_sem_prec="' . mysql_real_escape_string($nbAffaireSignee) . '",
								compte_affaire_signee="' . mysql_real_escape_string($compteAffaireSignee) . '",
								ca_trouve="' . mysql_real_escape_string(round($ligne4->ca_trouve, 2)) . '",
								ca_trouve_sem_prec="' . mysql_real_escape_string(round($ligne5->ca_trouve_sem_prec, 2)) . '",
								ca_affaire_pis="' . mysql_real_escape_string($ligne6->ca_pis) . '",
								ca_affaire_ecr="' . mysql_real_escape_string($ligne7->ca_ecr) . '",
								ca_affaire_aq="' . mysql_real_escape_string($ligne8->ca_aq) . '",
								ca_affaire_rem="' . mysql_real_escape_string($ligne9->ca_rem) . '",
								ca_pipe_total="' . mysql_real_escape_string($ca_pipe_total) . '",
								nb_affaire_ecr="' . mysql_real_escape_string($ligne10->nb) . '",
								nb_affaire_aq="' . mysql_real_escape_string($ligne11->nb) . '",
								nb_affaire_rem="' . mysql_real_escape_string($ligne12->nb) . '",
								nb_rdv_annee="' . mysql_real_escape_string($ligne13->nb) . '",
								nb_rdv_sem_prec="' . mysql_real_escape_string($ligne14->nb) . '",
								nb_rdv_sem="' . mysql_real_escape_string($ligne15->nb) . '",
								nb_rdv_futur="' . mysql_real_escape_string($ligne16->nb) . '",
								nb_rdv_moy_sem="' . mysql_real_escape_string($nb_rdv_moy_sem) . '",
								nbj_presence_annee="' . mysql_real_escape_string($ligne19->nbj) . '",
								pct_prospection="' . mysql_real_escape_string($pct_prospection) . '",
								obj_pct_prospection="' . mysql_real_escape_string($obj_pct_prospection) . '",
								nb_collab_titulaire="' . mysql_real_escape_string($ligne18->nb) . '"
								WHERE commercial="' . mysql_real_escape_string($ligne->commercial) . '" AND annee="' . mysql_real_escape_string($annee) . '" AND semaine="' . mysql_real_escape_string($i) . '"');
                        ++$i;
                    }
                }
                ++$annee;
            }
        }
    }

    /**
     * Fonction qui met à jour la table stats_ca_pondere
     *
     */
    public static function statsCAPondere() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe WHERE Id_societe = 1');
        
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $db->query('TRUNCATE TABLE stats_ca_pondere');
            $result = $db->query('SELECT a.Id_affaire,ag.libelle as libelle_agence,s.libelle as libelle_statut, ap.Id_pole, a.Id_type_contrat,
                                      a.Id_compte, d.Id_description, a.commercial, pl.date_debut,pl.date_fin_commande, a.Id_statut,
                                      date_demande, 
                                      (
                                        PERIOD_DIFF(DATE_FORMAT(pl.date_fin_commande, "%Y%m") , DATE_FORMAT(pl.date_debut, "%Y%m" ) ) 
                                        + CASE WHEN ((DATE_FORMAT(pl.date_fin_commande, "%d") > DATE_FORMAT(pl.date_debut, "%d")) AND (DATE_FORMAT(pl.date_fin_commande, "%d") - DATE_FORMAT(pl.date_debut, "%d") > 15)) THEN 1 ELSE 0 END
                                      ) AS mois
                                      FROM affaire a
                                      INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
                                      INNER JOIN description d ON d.Id_affaire = a.Id_affaire
                                      INNER JOIN statut s ON s.Id_statut=a.Id_statut 
                                      INNER JOIN agence ag ON ag.Id_agence = a.Id_agence
                                      INNER JOIN affaire_pole ap ON ap.Id_affaire = a.Id_affaire
                                      WHERE a.id_statut IN (3,4)
                                      ORDER BY a.Id_affaire DESC');
            
            while ($ligne = $result->fetchRow()) {
                $intitule = Description::getIntitule($ligne->id_description);
                $compte = CompteFactory::create(null, $ligne->id_compte);
                $compte = $compte->nom;
                if ($ligne->id_type_contrat == 3 && $ligne->id_pole == 3) {
                    $proposition = new Proposition(Affaire::lastProposition($ligne->id_affaire),array());

                    if($proposition->Id_proposition == '') {
                        continue;
                    }
                    
                    if($ligne->id_statut == 3)
                        list($annee, $mois, $jour) = explode('-', $ligne->date_demande);
                    elseif($ligne->id_statut == 4)
                        list($annee, $mois, $jour) = explode('-', $proposition->date_remise);
                    
                    $ligne_ca_pondere = $proposition->getLastWeighting();
                    if($ligne_ca_pondere == NULL) {
                        $ligne_ca_pondere->ponderation = 0;
                        $ligne_ca_pondere->chiffre_affaire = $proposition->chiffre_affaire;
                    }
                    
                    $ca_pondere =  $ligne_ca_pondere->chiffre_affaire * ($ligne_ca_pondere->ponderation / 100);
                    $db->query('INSERT INTO stats_ca_pondere VALUES (' . mysql_real_escape_string($ligne->id_affaire) . ', "' . mysql_real_escape_string($ligne->libelle_statut) . '",
                                "' . mysql_real_escape_string($ligne->libelle_agence) . '", "' . mysql_real_escape_string($ligne->commercial) . '", ' . mysql_real_escape_string($annee) . ',
                                "' . mysql_real_escape_string($compte) . '", "' . mysql_real_escape_string($intitule) . '",
                                ' . $ligne_ca_pondere->chiffre_affaire . ', ' . $ligne_ca_pondere->ponderation . ', ' . $ca_pondere . ')');
                }
                else {
                    if($ligne->mois == 0) $ligne->mois = 1;
                    $result1 = $db->query('SELECT (DATE_FORMAT( pl.date_fin_commande, "%Y") - DATE_FORMAT( pl.date_debut, "%Y" )) + 1 AS annee,
                                        DATE_FORMAT( pl.date_debut, "%Y") AS annee_depart
                                        FROM affaire a
                                        INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
                                        WHERE a.Id_affaire = ' . $ligne->id_affaire)->fetchRow();

                    $proposition = new Proposition(Affaire::lastProposition($ligne->id_affaire),array());
                    if($proposition->Id_proposition == '') {
                        continue;
                    }
                    $ligne_ca_pondere = $proposition->getLastWeighting();
                    if($ligne_ca_pondere == NULL) {
                        $ligne_ca_pondere->ponderation = 0;
                        $ligne_ca_pondere->chiffre_affaire = $proposition->chiffre_affaire;
                    }
                    $ca_par_mois = $ligne_ca_pondere->chiffre_affaire / $ligne->mois;

                    $arr = array();
                    $i = 1;
                    while ($i <= $result1->annee) {
                        if($result1->annee > 1 && $i == 1) {
                            $arr[$result1->annee_depart] = $db->queryOne('SELECT 
                                            (
                                                PERIOD_DIFF(CONCAT(DATE_FORMAT(pl.date_debut, "%Y"), "12"), DATE_FORMAT(pl.date_debut, "%Y%m") )
                                                + CASE WHEN ((DATE_FORMAT(pl.date_debut, "%d") < CAST(31 AS SIGNED)) AND (CAST(12 AS SIGNED) > 15) - DATE_FORMAT(pl.date_debut, "%d")) THEN 1 ELSE 0 END                                        
                                            ) AS mois
                                            FROM affaire a
                                            INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
                                            WHERE a.Id_affaire = ' . $ligne->id_affaire);
                            if($arr[$result1->annee_depart] == 0) $arr[$result1->annee_depart] = 1;
                        }
                        elseif($result1->annee > 1 && $i == $result1->annee) {
                            $arr[$result1->annee_depart] = $db->queryOne('SELECT 
                                            (
                                                PERIOD_DIFF(DATE_FORMAT(pl.date_fin_commande, "%Y%m"), CONCAT(DATE_FORMAT(pl.date_fin_commande, "%Y"), "01")) 
                                                + CASE WHEN ((DATE_FORMAT(pl.date_fin_commande, "%d") > CAST(01 AS SIGNED)) AND (DATE_FORMAT(pl.date_fin_commande, "%d") - CAST(01 AS SIGNED) > 15)) THEN 1 ELSE 0 END                                        
                                            ) AS mois
                                            FROM affaire a
                                            INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire  
                                            WHERE a.Id_affaire = ' . $ligne->id_affaire);
                            if($arr[$result1->annee_depart] == 0) $arr[$result1->annee_depart] = 1;
                        }
                        elseif($result1->annee == 1) {
                            $arr[$result1->annee_depart] = $db->queryOne('SELECT 
                                            (
                                              PERIOD_DIFF(DATE_FORMAT(pl.date_fin_commande, "%Y%m") , DATE_FORMAT(pl.date_debut, "%Y%m" ) ) 
                                              + CASE WHEN ((DATE_FORMAT(pl.date_fin_commande, "%d") > DATE_FORMAT(pl.date_debut, "%d")) AND (DATE_FORMAT(pl.date_fin_commande, "%d") - DATE_FORMAT(pl.date_debut, "%d") > 15)) THEN 1 ELSE 0 END
                                            ) AS mois
                                            FROM affaire a
                                            INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
                                            WHERE a.Id_affaire = ' . $ligne->id_affaire);
                            if($arr[$result1->annee_depart] == 0) $arr[$result1->annee_depart] = 1;
                        }
                        else {
                            $arr[$result1->annee_depart] = $db->queryOne('SELECT 
                                            (
                                                PERIOD_DIFF(CONCAT(DATE_FORMAT(pl.date_debut, "%Y"), "12"), CONCAT(DATE_FORMAT(pl.date_debut, "%Y"), "01") )
                                                + CASE WHEN ((DATE_FORMAT(pl.date_debut, "%d") < CAST(31 AS SIGNED)) AND (CAST(12 AS SIGNED) > 15) - DATE_FORMAT(pl.date_debut, "%d")) THEN 1 ELSE 0 END                                        
                                            ) AS mois
                                            FROM affaire a
                                            INNER JOIN planning pl ON pl.Id_affaire=a.Id_affaire 
                                            WHERE a.Id_affaire = ' . $ligne->id_affaire);
                            if($arr[$result1->annee_depart] == 0) $arr[$result1->annee_depart] = 1;
                        }
                        $ca = $ca_par_mois * $arr[$result1->annee_depart];
                        $ca_pondere =  $ca * ($ligne_ca_pondere->ponderation / 100);
                        $db->query('INSERT INTO stats_ca_pondere VALUES (' . mysql_real_escape_string($ligne->id_affaire) . ', "' . mysql_real_escape_string($ligne->libelle_statut) . '",
                                    "' . mysql_real_escape_string($ligne->libelle_agence) . '", "' . mysql_real_escape_string($ligne->commercial) . '", ' . mysql_real_escape_string($result1->annee_depart) . ',
                                    "' . mysql_real_escape_string($compte) . '", "' . mysql_real_escape_string($intitule) . '",
                                    ' . $ca . ', ' . $ligne_ca_pondere->ponderation . ', ' . $ca_pondere . ')');
                        
                        $result1->annee_depart++;
                        $i++;
                    }
                }
            }
        }
    }
    
    /**
     * Fonction qui met à jour la table stats_hebdo_agence
     *
     */
    public static function statsHebdoAgence() {
        $db = connecter();
        $res = $db->query('SELECT * FROM societe WHERE Id_societe = 1');
        
        while ($l = $res->fetchRow()) {
            $_SESSION['societe'] = $l->libelle;
            $db = connecter();
            $db->query('TRUNCATE TABLE stats_hebdo_agence');
            $result = $db->query('SELECT c.Id_candidature, c.type_embauche, r.nom, r.prenom, 
                                  WEEK(r.date_embauche,3) AS semaine_embauche, 
                                  CAST(DATE_FORMAT(r.date_embauche,\'%c\') AS UNSIGNED) AS mois_embauche,
                                  CAST(DATE_FORMAT(r.date_embauche,\'%Y\') AS UNSIGNED) AS annee_embauche FROM candidature c 
                                  INNER JOIN ressource r ON c.Id_ressource = r.Id_ressource');
            if (PEAR::isError($result)) {
                var_dump($result);
                die($result->getMessage());
            }
            while ($ligne = $result->fetchRow()) {
                $idCandidature = $ligne->id_candidature;
                $nomCandidature = $ligne->nom;
                $prenomCandidature = $ligne->prenom;
                $result_etat = $db->query('SELECT Id_etat, date, WEEK(date,3) AS semaine, 
                                           CAST(DATE_FORMAT(date,\'%c\') AS UNSIGNED) AS mois,
                                           CAST(DATE_FORMAT(date,\'%Y\') AS UNSIGNED) AS annee
                                           FROM historique_candidature WHERE Id_candidature = ' . $ligne->id_candidature);
                if (PEAR::isError($result_etat)) {
                    die($result_etat->getMessage());
                }
                
                $etat = array();
                $result_etat2 = $db->query('SELECT Id_etat, date, WEEK(date,3) AS semaine, 
                                           CAST(DATE_FORMAT(date,\'%c\') AS UNSIGNED) AS mois,
                                           CAST(DATE_FORMAT(date,\'%Y\') AS UNSIGNED) AS annee
                                           FROM historique_candidature WHERE Id_candidature = ' . $ligne->id_candidature);
                while ($ligne_etat2 = $result_etat2->fetchRow()) {
                    $etat[$ligne_etat2->id_etat] = $ligne_etat2->date;
                }
                    
                if (PEAR::isError($result_etat2)) {
                    die($result_etat2->getMessage());
                }
                
                $result_agence = $db->query('SELECT Id_agence FROM candidat_agence 
                                             WHERE Id_candidat = ' . $ligne->id_candidature);
                if (PEAR::isError($result_agence)) {
                    die($result_agence->getMessage());
                }
                
                while ($ligne_agence = $result_agence->fetchRow()) {
                    while ($ligne_etat = $result_etat->fetchRow()) {
                        // Candidat avec entretien planifié
                        if(in_array($ligne_etat->id_etat,array(4,5))) {  
                            if($idCandidature != $idCandidatureEntPlan[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['entplan']++;
                                $idCandidatureEntPlan[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat avec entretien réalisé
                        if(in_array($ligne_etat->id_etat,array(4,5))) {
                            if(array_key_exists(15,$etat) && ($etat[4] <= $etat[15] || $etat[5] <= $etat[15])){}
                            elseif(array_key_exists(17,$etat) && ($etat[4] <= $etat[17] || $etat[5] <= $etat[17])){}
                            else {
                                if($idCandidature != $idCandidatureEntRea[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                    $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['entrea']++;
                                    $idCandidatureEntRea[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                                }
                            }
                        }
                        // Candidat validé
                        if(in_array($ligne_etat->id_etat,array(4,5))) {
                            if(array_key_exists(7,$etat) && ($etat[4] <= $etat[7] || $etat[5] <= $etat[7])){}
                            elseif(array_key_exists(6,$etat) && ($etat[4] <= $etat[6] || $etat[5] <= $etat[6])){
                                if($idCandidature != $idCandidatureVal[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                    $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['candval']++;
                                    $idCandidatureVal[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                                }
                            }
                            else {
                                if($idCandidature != $idCandidatureVal[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                    $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['candval']++;
                                    $idCandidatureVal[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                                }
                            }
                        }
                        // Candidat qualif tel
                        if(in_array($ligne_etat->id_etat,array(3,18))) {
                            if($idCandidature != $idCandidatureQualifTel[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['candqualiftel']++;
                                $idCandidatureQualifTel[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat transfo CDD - CDI
                        if($ligne_etat->id_etat==23) {
                            if($idCandidature != $idCandidatureTransCDDCDI[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['trancddcdi']++;
                                $idCandidatureTransCDDCDI[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat transfo Interim - CDD
                        if($ligne_etat->id_etat==24) {
                            if($idCandidature != $idCandidatureTransIntCDD[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['transintcdd']++;
                                $idCandidatureTransIntCDD[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat transfo Interim - CDI
                        if($ligne_etat->id_etat==25) {
                            if($idCandidature != $idCandidatureTransIntCDI[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['transintcdi']++;
                                $idCandidatureTransIntCDI[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché CDI sur profil
                        if($ligne_etat->id_etat==9 && $ligne->type_embauche == 'profil') {
                            if($idCandidature != $idCandidatureEmbCDIPr[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcdipr']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcdiprnom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbCDIPr[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché CDI sur mission
                        if($ligne_etat->id_etat==9 && ($ligne->type_embauche == 'mission'||$ligne->type_embauche=='')) {
                            if($idCandidature != $idCandidatureEmbCDIMiss[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcdimiss']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcdimissnom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbCDIMiss[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché CDD sur profil
                        if($ligne_etat->id_etat==8&& $ligne->type_embauche == 'profil') {
                            if($idCandidature != $idCandidatureEmbCDDPr[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcddpr']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcddprnom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbCDDPr[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché CDD sur mission
                        if($ligne_etat->id_etat==8 && ($ligne->type_embauche == 'mission'||$ligne->type_embauche=='')) {
                            if($idCandidature != $idCandidatureEmbCDDMiss[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcddmiss']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embcddmissnom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbCDDMiss[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché ST
                        if($ligne_etat->id_etat==21) {
                            if($idCandidature != $idCandidatureEmbST[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embst']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embstnom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbST[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat embauché stage
                        if($ligne_etat->id_etat==10) {
                            if($idCandidature != $idCandidatureEmbStage[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embstage']++;
                                $tab[$ligne->annee_embauche][$ligne->mois_embauche][$ligne->semaine_embauche][$ligne_agence->id_agence]['embstagenom'] .= $nomCandidature . ' ' . $prenomCandidature . ', ';
                                $idCandidatureEmbStage[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                        // Candidat contacté
                        if(in_array($ligne_etat->id_etat,array(3,4,5,8,9,16,18))) {
                            if($idCandidature != $idCandidatureContacte[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]) {
                                $tab[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence]['contacte']++;
                                $idCandidatureContacte[$ligne_etat->annee][$ligne_etat->mois][$ligne_etat->semaine][$ligne_agence->id_agence] = $idCandidature;
                            }
                        }
                    }
                    
                }
            }
            
            
            
            
            foreach ($tab as $annee => $tabAnnee) {
                foreach ($tabAnnee as $mois => $tabMois) {
                    foreach ($tabMois as $semaine => $tabSemaine) {
                        foreach ($tabSemaine as $agence => $tabAgence) {
                            $db->query('INSERT INTO stats_hebdo_agence (semaine, mois, annee, agence,
                                        contact, ent_plan, ent_rea, contact_val, qualif_tel, trans_cdd_cdi,
                                        trans_int_cdi, trans_int_cdd, emb_cdi_pr, emb_cdi_pr_nom,
                                        emb_cdi_miss, emb_cdi_miss_nom, emb_cdd_pr, emb_cdd_pr_nom,
                                        emb_cdd_miss, emb_cdd_miss_nom, emb_st, emb_st_nom,
                                        emb_stage, emb_stage_nom) 
                                        VALUES(' . $semaine . ',' . $mois . ',' . $annee . ',"' . $agence . '",
                                        ' . (empty($tabAgence['contacte'])?0:$tabAgence['contacte']) . ',
                                        ' . (empty($tabAgence['entplan'])?0:$tabAgence['entplan']) . ',
                                        ' . (empty($tabAgence['entrea'])?0:$tabAgence['entrea']) . ',
                                        ' . (empty($tabAgence['candval'])?0:$tabAgence['candval']) . ',
                                        ' . (empty($tabAgence['candqualiftel'])?0:$tabAgence['candqualiftel']) . ',
                                        ' . (empty($tabAgence['transcddcdi'])?0:$tabAgence['transcddcdi']) . ',
                                        ' . (empty($tabAgence['transintcdi'])?0:$tabAgence['transintcdi']) . ',
                                        ' . (empty($tabAgence['transintcdd'])?0:$tabAgence['transintcdd']) . ',
                                        ' . (empty($tabAgence['embcdipr'])?0:$tabAgence['embcdipr']) . ',
                                        ' . (empty($tabAgence['embcdiprnom'])?'""':'"'.$tabAgence['embcdiprnom'].'"') . ',
                                        ' . (empty($tabAgence['embcdimiss'])?0:$tabAgence['embcdimiss']) . ',
                                        ' . (empty($tabAgence['embcdimissnom'])?'""':'"'.$tabAgence['embcdimissnom'].'"') . ',
                                        ' . (empty($tabAgence['embcddpr'])?0:$tabAgence['embcddpr']) . ',
                                        ' . (empty($tabAgence['embcddprnom'])?'""':'"'.$tabAgence['embcddprnom'].'"') . ',
                                        ' . (empty($tabAgence['embcddmiss'])?0:$tabAgence['embcddmiss']) . ',
                                        ' . (empty($tabAgence['embcddmissnom'])?'""':'"'.$tabAgence['embcddmissnom'].'"') . ',
                                        ' . (empty($tabAgence['embst'])?0:$tabAgence['embst']) . ',
                                        ' . (empty($tabAgence['embstnom'])?'""':'"'.$tabAgence['embstnom'].'"') . ',
                                        ' . (empty($tabAgence['embstage'])?0:$tabAgence['embstage']) . ',
                                        ' . (empty($tabAgence['embstagenom'])?'""':'"'.$tabAgence['embstagenom'].'"') . ')');
                        }
                    }
                }
            }
        }
    }
}

?>