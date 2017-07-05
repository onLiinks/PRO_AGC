<?php

/**
 * Fichier PlanningSession.php
 *
 * @author    Frédérique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Planning_Session
 */
class PlanningSession {

    /**
     * Identifiant de l'instance Planning_Session
     *
     * @access public
     * @var int 
     */
    public $Id_planning_session;
    /**
     * Identifiant de la session correspondant au planning
     *
     * @access public
     * @var int 
     */
    public $Id_session;
    /**
     * Liste des dates ponctuelles de la session
     *
     * @access public
     * @var array 
     */
    public $date;
    /**
     * Liste des dates de début des périodes intermédiaires de la session
     *
     * @access public
     * @var array 
     */
    public $periode_debut;
    /**
     * Liste des dates de fin des périodes intermédiaires de la session
     *
     * @access public
     * @var array 
     */
    public $periode_fin;
    /**
     * Date de début
     *
     * @access public
     * @var date 
     */
    public $dateDebut;
    /**
     * Date de fin
     *
     * @access public
     * @var date
     */
    public $dateFin;
    /**
     * Date de fin prévisionnelle
     *
     * @access public
     * @var date
     */
    public $dateFinPrev;
    /**
     * Durée de la session en jour
     *
     * @access public
     * @var int
     */
    public $nb_Jour;

    /**
     * Constructeur de la classe Planning_Session
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'instance Planning_Session
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_planning_session = '';
                $this->Id_session = '';
                $this->dateDebut = '';
                $this->dateFin = '';
                $this->dateFinPrev = '';
                $this->nb_Jour = '';
                $this->date = array();
                $this->periode_debut = array();
                $this->periode_fin = array();
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_planning_session = '';
                $this->Id_session = $tab['Id_session'];
                $this->date = $tab['date'];
                $this->periode_debut = $tab['periode_debut'];
                $this->periode_fin = $tab['periode_fin'];
                $this->dateDebut = $tab['dateDebut'];
                $this->dateFin = $tab['dateFin'];
                $this->dateFinPrev = $tab['dateFinPrev'];
                $this->nb_Jour = $tab['nb_Jour'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM planning_session WHERE Id_plan_session=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_planning_session = $code;
                $this->Id_session = $ligne->id_session;
                $this->dateDebut = $ligne->datedebut;
                $this->dateFin = $ligne->datefin;
                $this->dateFinPrev = $ligne->datefinprev;
                $this->nb_Jour = $ligne->nb_jour;

                //récup des dates ponctuelles de la session
                $result2 = $db->query('SELECT d.date FROM date AS d INNER JOIN planning_date AS dp ON d.Id_date=dp.Id_date 
							 WHERE dp.Id_plan_session=' . mysql_real_escape_string((int) $this->Id_planning_session) . ' ORDER BY d.date');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->date[] = $ligne2->date;
                }

                //récup des périodes intermédiaires de la session
                $result2 = $db->query('SELECT periode_debut, periode_fin FROM periode_session 
							 WHERE Id_planning_session=' . mysql_real_escape_string((int) $this->Id_planning_session) . ' ORDER BY periode_debut');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->periode_debut[] = $ligne2->periode_debut;
                    $this->periode_fin[] = $ligne2->periode_fin;
                }
            }

            /* Cas 4 : un code et un tableau : prendre  infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_planning_session = $code;
                $this->Id_session = $tab['Id_session'];
                $this->date = $tab['date'];
                $this->periode_debut = $tab['periode_debut'];
                $this->periode_fin = $tab['periode_fin'];
                $this->dateDebut = $tab['dateDebut'];
                $this->dateFin = $tab['dateFin'];
                $this->dateFinPrev = $tab['dateFinPrev'];
                $this->nb_Jour = $tab['nb_Jour'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification de l'instance Planning_Session
     *
     * @return string
     */
    public function form() {
        //Mise en forme des dates entre la base de données et l'affichage français
        $html = '<input type="hidden" name="Id_planning_session" id="Id_planning_session" value="' . $this->Id_planning_session . '" />
				<div class="left">
					Date de début de session :&nbsp;&nbsp;
					<input type="text" onFocus="showCalendarControl(this, function(){joursOuvresSession(1)})"  id="dateDebut" name="dateDebut" size="8" value="' . FormatageDate($this->dateDebut) . '"/><br/><br/>
				</div>
				<div class="right">
					Date de fin de session :&nbsp;&nbsp;
					<input type="text" onfocus="showCalendarControl(this, function(){joursOuvresSession(1)})" id="dateFin" name="dateFin" size="8" value="' . FormatageDate($this->dateFin) . '"/><br/><br/>
					Date de fin prévisionnelle de session :&nbsp;&nbsp;
					<input type="text" onfocus="showCalendarControl(this)" id="dateFinPrev" name="dateFinPrev" size="8" value="' . FormatageDate($this->dateFinPrev) . '"/><br/><br/>							
				</div>
				<div class="left">
					Date(s) ponctuelle(s) si la session n\'est pas continue :<br/><br/>
						' . $this->formDateSup() . '
					<input type="button" onclick="ajoutDateSession()" value="+">
					<input type="button" onclick="enleveDateSession()" value="-">
						<br/><br/><br/>	
				</div>
				<div class="right">
					Période(s) si la session n\'est pas continue :<br/><br/>
						' . $this->formPeriodeSup() . '
					<input type="button" onclick="ajoutPeriodeSession()" value="+">
					<input type="button" onclick="enlevePeriodeSession()" value="-">
						<br/><br/><br/>	
				</div>
				<div class="center"><br/><br/>
					<div id="nombre_Jour">
							<input type="button" onclick="joursOuvresSession(1)" value="Calcul nb Jours">&nbsp;&nbsp;&nbsp;&nbsp;
							Durée de la session :&nbsp;&nbsp;
							<input type="text" id="nb_Jour" name="nb_Jour" value="' . $this->nb_Jour . '" size="8">&nbsp;&nbsp;jours	
						<br/><br/></div>
				<br/></div>';
        return $html;
    }

    /**
     * Formulaire de création/modification des dates ponctuelles
     *
     * @return string
     */
    public function formDateSup() {
        $count = count($this->date);
        //Si le tableau est déjà rempli : formulaire pour modification, il faut afficher toutes les dates ponctuelles
        if ($count > 0) {
            $html = '';
            $nb = 1;
            //emboitement des div et afichage de chaque date ponctuelle
            while ($nb < $count) {
                $nb2 = $nb + 1;
                //Mise en forme de la date entre la base de données et l'affichage français
                $this->date[$nb - 1] = FormatageDate($this->date[$nb - 1]);
                $html .= 'Date n°' . $nb . '&nbsp;:&nbsp;
							<input type="text" onfocus="showCalendarControl(this)" id="date' . $nb . '" 
									name="date[]" size="8" value="' . $this->date[$nb - 1] . '">
						<br/><br/>
						<div id="autreDate' . $nb2 . '">';
                ++$nb;
            }
            // ajout de la dernière date ponctuelle
            $html .= $this->addDate($count);
            $nb = 1;
            // fermeture des div
            while ($nb < $count) {
                $html .= '</div>';
                ++$nb;
            }
        }
        // si tableau est vide, affichage d'une seule date ponctuelle
        else if ($count == 0) {
            $html = $this->addDate(1);
        }
        return $html;
    }

    /**
     * Ajout d'un nouveau champ de saisie d'une date ponctuelle
     * 
     * @param int numéro de la date ponctuelle
     *
     * @return string
     */
    public function addDate($nb) {
        // numéro de la prochaine date ponctuelle
        $nb2 = $nb + 1;
        //Mise en forme de la date entre la base de données et l'affichage français
        $this->date[$nb - 1] = FormatageDate($this->date[$nb - 1]);

        $html = 'Date n°' . $nb . '&nbsp;:&nbsp;
				<input type="text"  onfocus="showCalendarControl(this)" id="date' . $nb . '" 
						name="date[]" size="8" value="' . $this->date[$nb - 1] . '">
				<br/><br/>
		<div id="autreDate' . $nb2 . '"><input type="hidden" id="nb_Date" name="nb_Date[]" value=' . $nb . '></div>';
        return $html;
    }

    /**
     * Formulaire de création/modification des périodes intermédiaires
     *
     * @return string
     */
    public function formPeriodeSup() {
        $count = count($this->periode_debut);
        //Si le tableau est déjà rempli : formulaire pour modification, il faut afficher toutes les périodes
        if ($count > 0) {
            $html = '';
            $nb = 1;
            //emboitement des div et afichage de chaque période
            while ($nb < $count) {
                $nb2 = $nb + 1;
                //Mise en forme des dates entre la base de données et l'affichage français
                $html .= 'Période n°' . $nb . '&nbsp;&nbsp;du&nbsp; 
							<input type="text"  onfocus="showCalendarControl(this)"  id="periode_debut' . $nb . '" 
									name="periode_debut[]" size="8" value="' . FormatageDate($this->periode_debut[$nb - 1]) . '">
							&nbsp;&nbsp;au&nbsp;
							<input type="text"  onfocus="showCalendarControl(this)"  id="periode_fin' . $nb . '" 
									name="periode_fin[]" size="8" value="' . FormatageDate($this->periode_fin[$nb - 1]) . '">
							<br/><br/>
						<div id="autrePeriode' . $nb2 . '">';
                ++$nb;
            }
            // ajout de la dernière période
            $html .= $this->addPeriod($count);
            $nb = 1;
            // fermeture des div
            while ($nb < $count) {
                $html .= '</div>';
                ++$nb;
            }
        }
        // si tableau est vide, affichage d'une seule période
        else if ($count == 0) {
            $html = $this->addPeriod(1);
        }
        return $html;
    }

    /**
     * Ajout de nouveaux champs pour saisie d'une nouvelle période intérmédiaire
     *
     * @param int numéro de la période
     *
     * @return string
     */
    public function addPeriod($nb) {
        // numéro de la prochaine période
        $nb2 = $nb + 1;
        //Mise en forme des dates entre la base de données et l'affichage français
        $html = 'Période n°' . $nb . '&nbsp;&nbsp;du&nbsp; 
				<input type="text"  onfocus="showCalendarControl(this)"  id="periode_debut' . $nb . '" 
						name="periode_debut[]" size="8" value="' . FormatageDate($this->periode_debut[$nb - 1]) . '">
				&nbsp;&nbsp;au&nbsp;
				<input type="text"  onfocus="showCalendarControl(this)"  id="periode_fin' . $nb . '" 
						name="periode_fin[]" size="8" value="' . FormatageDate($this->periode_fin[$nb - 1]) . '">
				<br/><br/>
		<div id="autrePeriode' . $nb2 . '"><input type="hidden" id="nb_Periode" name="nb_Periode[]" value=' . $nb . '></div>';

        return $html;
    }

    /**
     * Enregistrement des données du planning dans la BDD 
     *
     */
    public function save() {
        $db = connecter();
        //Mise en forme des dates pour compatibilité avec la base de donnée
        if ($this->dateDebut != '') {
            $this->dateDebut = DateMysqltoFr($this->dateDebut, 'mysql');
        }
        if ($this->dateFin != '') {
            $this->dateFin = DateMysqltoFr($this->dateFin, 'mysql');
        }
        if ($this->dateFinPrev != '') {
            $this->dateFinPrev = DateMysqltoFr($this->dateFinPrev, 'mysql');
        }

        $set = ' SET Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '", 
						nb_jour = "' . mysql_real_escape_string($this->nb_Jour) . '", 
						dateDebut = "' . mysql_real_escape_string($this->dateDebut) . '", 
						dateFinPrev ="' . mysql_real_escape_string($this->dateFinPrev) . '",
						dateFin="' . mysql_real_escape_string($this->dateFin) . '"';

        //Si l'instance planning existait avant, requete en UPDATE sinon insert
        if ($this->Id_planning_session) {
            $requete = 'UPDATE planning_session ' . $set . ' WHERE Id_plan_session = ' . mysql_real_escape_string((int) $this->Id_planning_session);
            //pour éliminer les dates supplémentaires existantes déjà et par la suite tout remis à jour
            $db->query('DELETE FROM planning_date WHERE Id_plan_session = ' . mysql_real_escape_string((int) $this->Id_planning_session));
            //pour éliminer les périodes supplémentaires existantes déjà et par la suite tout remis à jour
            $db->query('DELETE FROM periode_session WHERE Id_planning_session = ' . mysql_real_escape_string((int) $this->Id_planning_session));
        } else {
            $requete = 'INSERT INTO planning_session ' . $set;
        }
        $db->query($requete);

        //récupération de l'identifiant si l'instance n'existait pas avant
        if ($this->Id_planning_session == '') {
            $this->Id_planning_session = mysql_insert_id();
        }

        //Enregistrement des dates intermédiaires si elles existent
        $i = 0;
        $nb_date = count($this->date);
        while ($i < $nb_date) {
            if ($this->date[$i]) {
                //Mise en forme des dates pour compatibilité avec la base de donnée
                $this->date[$i] = DateMysqltoFr($this->date[$i], 'mysql');
                //récup de l'identifiant de la date dans la base de données
                $ligne2 = $db->query('SELECT Id_date FROM date WHERE date = "' . mysql_real_escape_string($this->date[$i]) . '"')->fetchRow();
                //si la date n'existait pas, création d'un enregistrement 
                if (count($ligne2) == 0) {
                    $db->query('INSERT INTO date SET date="' . mysql_real_escape_string($this->date[$i]) . '"');
                    $id = mysql_insert_id();
                } else {
                    $id = $ligne2->id_date;
                }
                $db->query('INSERT INTO planning_date SET Id_plan_session="' . mysql_real_escape_string($this->Id_planning_session) . '", Id_date="' . $id . '"');
            }
            ++$i;
        }
        //Enregistrement des dates intermédiaires si elles existent
        $i = 0;
        $nb_periode = count($this->periode_debut);
        while ($i < $nb_periode) {
            if ($this->periode_debut[$i] || $this->periode_fin[$i]) {
                //Mise en forme des dates pour compatibilité avec la base de donnée
                $db->query('INSERT INTO periode_session SET Id_planning_session="' . mysql_real_escape_string($this->Id_planning_session) . '", 
				periode_debut="' . mysql_real_escape_string(DateMysqltoFr($this->periode_debut[$i], 'mysql')) . '", periode_fin="' . mysql_real_escape_string(DateMysqltoFr($this->periode_fin[$i], 'mysql')) . '"');
            }
            ++$i;
        }
    }

    /**
     * Affichage des information du planning de la session en consultation
     *
     * @return string
     */
    public function consultation() {
        //Mise en forme des dates entre la base de données et l'affichage français
        $html = '
		    <div class="left">
			    <b>Date de début de session :</b>  ' . FormatageDate($this->dateDebut) . '<br/><br/>
			</div>
			<div class="right">
				<b>Date de fin de session :</b> ' . FormatageDate($this->dateFin) . '<br/><br/>
				<b>Date de fin prévisionnelle de session :</b> ' . FormatageDate($this->dateFinPrev) . '<br/><br/>
			</div>
		    <div class="center">
				<b>Durée de la session :</b> ' . $this->nb_Jour . ' jour(s)<br/><br/>
			</div>
			<div class="left">';

        //affichage des dates ponctuelles uniquement si il y en a
        if (count($this->date)) {
            $html .= '<b>Date(s) ponctuelle(s) :</b> ';
            $i = 0;
            $nb = count($this->date);
            while ($i < $nb) {
                $html .= FormatageDate($this->date[$i]) . '<br/><span class="marge"></span>
							<span class="marge"></span><span class="marge"></span><span class="marge"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                ++$i;
            }
        }
        $html .= '</div><div class="right">';
        //affichage des périodes uniquement si il y en a
        $nb = count($this->periode_debut);
        if ($nb) {
            $html .= '<b>Période(s) :</b> ';
            $i = 0;
            while ($i < $nb) {
                $html .= 'du ' . FormatageDate($this->periode_debut[$i]) . ' au ' . FormatageDate($this->periode_fin[$i]) . '<br/>
							  <span class="marge"></span><span class="marge"></span>&nbsp;&nbsp;&nbsp;&nbsp;';
                ++$i;
            }
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Information du planning de la session à joindre et à afficher dans l'affaire 
     *
     * @param int identifiant de la session dont il faut récupérer le planning
     *
     * @return string
     */
    public static function infoSession($Id_session) {
        //récup des données concernant la session dans la base de données
        $db = connecter();
        $ligne = $db->query('SELECT dateDebut, dateFin, dateFinPrev, nb_jour FROM planning_session WHERE Id_session = "' . mysql_real_escape_string((int) $Id_session) . '"')->fetchRow();

        //mise en format des dates
        if ($ligne->datedebut != "0000-00-00") {
            $debut = FormatageDate($ligne->datedebut);
        }
        if ($ligne->datefin != "0000-00-00") {
            $fin = FormatageDate($ligne->datefin);
        }
        if ($ligne->datefinprev != "0000-00-00") {
            $fin_prev = FormatageDate($ligne->datefinprev);
        }
        //Affichage dans l'affaire
        $html .= '<span class="vert"> * </span> Début de la prestation :
				<input id="date_debut" name="date_debut" type="text" value="' . $debut . '" size="8" readonly=true><br /><br />
				<span class="vert"> * </span> Fin de la prestation :
				<input id="date_fin" name="date_fin_commande" type="text" value="' . $fin . '" size="8" readonly=true><br /><br />
				<span class="vert"> * </span> Fin prévisionnelle de la prestation:
				<input id="date_fin_previsionnelle" name="date_fin_previsionnelle" type="text" value="' . $fin_prev . '" size="8" readonly=true><br /><br />				
				Durée de la prestation : <br />
				<input type="text" id="duree" name="duree" value="' . $ligne->nb_jour . '" size="1" readonly=true>
				jours (ouvrés)
				';

        return $html;
    }

    /**
     * Mise à jour des données associées à la session dans les affaires associées lors de la modification d'une session
     *   
     */
    public function updateRelatedCases() {
        //récupération des affaires associées à la session
        $db = connecter();
        if ($this->Id_session) {
            $result = $db->query('SELECT affaire.Id_affaire FROM affaire INNER JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire
		    WHERE Id_pole=3 AND archive=0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));
            while ($ligne = $result->fetchRow()) {
                $db->query('UPDATE planning SET date_debut="' . mysql_real_escape_string($this->dateDebut) . '", date_fin_commande="' . mysql_real_escape_string($this->dateFin) . '", 
			    date_fin_previsionnelle="' . mysql_real_escape_string($this->dateFinPrev) . '", duree="' . mysql_real_escape_string($this->nb_Jour) . '" WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire));
            }
        }
    }

    /**
     * Création liste de toutes les dates ouvrées de la session
     *  
     * @return array
     *  
     */
    public function listeDates() {
        $listeDate = array();

        //Si il n'y a qu'une seule date, récupération de la date
        if ($this->dateDebut == $this->dateFin) {
            $tDeb = explode('-', FormatageDate($this->dateDebut));
            $listeDate['jour'][0] = $tDeb[0];
            $listeDate['mois'][0] = $tDeb[1];
            $listeDate['annee'][0] = $tDeb[2];
        } else { //si il y a plusieurs dates, récupération de toutes les dates
            $nbPeriode = count($this->periode_debut);
            $nbDate = count($this->date);
            //si seulement une date de début et de fin : liste de toutes les dates des jours ouvrés dans cette période
            if (!$nbDate && !$nbPeriode) {
                //Calcul des dates entre la date de début et la date de fin
                $listeDate = $this->datesOuvres($this->dateDebut, $this->dateFin, 0);
            } else { //si il y a des dates ponctuelles ou des périodes : récupération de toutes les dates par ordre chronologique
                //premier élément de la liste : la date de début
                $tDeb = explode('-', FormatageDate($this->dateDebut));
                $listeDate['jour'][0] = $tDeb[0];
                $listeDate['mois'][0] = $tDeb[1];
                $listeDate['annee'][0] = $tDeb[2];

                /* si il n'y a que des dates ponctuelles : liste des dates ponctuelles en enlevant les dates de début et 
                  de fin si sont présentes */
                if ($nbDate && !$nbPeriode) {
                    $j = 0; //compteur des dates ponctuelles
                    $i = 1; //compteur de la liste
                    while ($j < $nbDate) {
                        if (($this->date[$j] != $this->dateDebut) && ($this->date[$j] != $this->dateFin)) {
                            $tDate = explode('-', FormatageDate($this->date[$j]));
                            $listeDate['jour'][$i] = $tDate[0];
                            $listeDate['mois'][$i] = $tDate[1];
                            $listeDate['annee'][$i] = $tDate[2];
                            ++$i;
                        }
                        ++$j;
                    }
                }
                /* si il n'y a que des périodes : liste des dates des jours ouvrés pour chaque période en enlevant les dates 
                  de début et de fin */ else if (!$nbDate && $nbPeriode) {
                    $j = 0;
                    while ($j < $nbPeriode) {
                        /* si la date de début de la période est celle de début de session (déjà comptée dans la liste), 
                          début de la période un jour plus tard */
                        if ($this->periode_debut[$j] == $this->dateDebut) {
                            $tDate = explode('-', FormatageDate($this->periode_debut[$j]));
                            $this->periode_debut[$j] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] + 1, $tDate[2]));
                        }
                        /* si la date de fin de la période est celle de fin de session (comptée après dans la liste), 
                          fin de la période un jour plus tôt */
                        if ($this->periode_fin[$j] == $this->dateFin) {
                            $tDate = explode('-', FormatageDate($this->periode_fin[$j]));
                            $this->periode_fin[$j] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] - 1, $tDate[2]));
                        }
                        /* récupération de la liste des dates des jours ouvrés pour la période et association à la 
                          liste des dates de la session */
                        $listeTempo = $this->datesOuvres($this->periode_debut[$j], $this->periode_fin[$j], count($listeDate['jour']));
                        $listeDate['jour'] = array_merge($listeDate['jour'], $listeTempo['jour']);
                        $listeDate['mois'] = array_merge($listeDate['mois'], $listeTempo['mois']);
                        $listeDate['annee'] = array_merge($listeDate['annee'], $listeTempo['annee']);
                        ++$j;
                    }
                } else {
                    /* si il y a des dates ponctuelles et des périodes, liste de toutes les dates en rangeant les dates 
                      par ordre chronologique et en enlevant les dates de début et de fin */
                    $nbD = 0; //compteur des dates ponctuelles
                    $nbP = 0; //compteur des périodes
                    $finiD = 0; // indicateur que toutes les dates ponctuelles ont été récupérées
                    $finiP = 0; // indicateur que toutes les périodes ont été récupérées
                    //tant que toutes les dates et les périodes n'ont pas été récupérées
                    while (!$finiD || !$finiP) {

                        //calcul du timestamp des dates pour les comparer
                        $tDate = explode('-', FormatageDate($this->date[$nbD]));
                        $tPeriode = explode('-', FormatageDate($this->periode_debut[$nbP]));
                        $timestampDate = mktime(0, 0, 0, $tDate[1], $tDate[0], $tDate[2]);
                        $timestampPeriode = mktime(0, 0, 0, $tPeriode[1], $tPeriode[0], $tPeriode[2]);

                        /* si la date ponctuelle en cours est avant la période en cours et que les dates ponctuelles n'ont 
                          pas toutes déjà été récupérées ou si la date ponctuelle en cours est après la période en cours mais
                          toutes les périodes ont déjà été récupérées */
                        if ((($timestampDate < $timestampPeriode) && !$finiD) || (($timestampDate > $timestampPeriode) && $finiP)) {
                            //récupération de la date que si elle ne correspond ni à la date de début ni à celle de fin
                            if (($this->date[$nbD] != $this->dateDebut) && ($this->date[$nbD] != $this->dateFin)) {
                                $numero = count($listeDate['jour']);
                                $tDate = explode('-', FormatageDate($this->date[$nbD]));
                                $listeDate['jour'][$numero] = $tDate[0];
                                $listeDate['mois'][$numero] = $tDate[1];
                                $listeDate['annee'][$numero] = $tDate[2];
                            }
                            //si la date traitée est la dernière, passe l'indicateur de fin à 1
                            if ($nbD == ($nbDate - 1)) {
                                $finiD = 1;
                            } else { //sinon augmente le compteur de date
                                ++$nbD;
                            }
                        }
                        /* si la période en cours est avant la date ponctuelle en cours et que les périodes n'ont pas toutes déjà 
                          été récupérées ou si la période en cours est après la date ponctuelle en cours mais toutes les dates
                          ponctuelles ont déjà été récupérées */ else {
                            /* si la date de début de la période est celle de début de session (déjà comptée dans la liste), 
                              début de la période un jour plus tard */
                            if ($this->periode_debut[$nbP] == $this->dateDebut) {
                                $this->periode_debut[$nbP] = date("Y-m-d", mktime(0, 0, 0, $tPeriode[1], $tPeriode[0] + 1, $tPeriode[2]));
                            }
                            /* si la date de fin de la période est celle de fin de session (comptée après dans la liste), 
                              fin de la période un jour plus tôt */
                            if ($this->periode_fin[$nbP] == $this->dateFin) {
                                $tDate = explode('-', FormatageDate($this->periode_fin[$nbP]));
                                $this->periode_fin[$nbP] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] - 1, $tDate[2]));
                            }

                            //récupération de la liste des dates des jours ouvrés pour la période et association 
                            //à la liste des dates de la session
                            $listeTempo = $this->datesOuvres($this->periode_debut[$nbP], $this->periode_fin[$nbP], count($listeDate['jour']));
                            $listeDate['jour'] = array_merge($listeDate['jour'], $listeTempo['jour']);
                            $listeDate['mois'] = array_merge($listeDate['mois'], $listeTempo['mois']);
                            $listeDate['annee'] = array_merge($listeDate['annee'], $listeTempo['annee']);

                            //si la période traitée est la dernière, passe l'indicateur de fin à 1
                            if ($nbP == ($nbPeriode - 1)) {
                                $finiP = 1;
                            } else { //sinon augmente le compteur de période
                                ++$nbP;
                            }
                        }
                    }
                }
                //dernier élément de la liste : la date de fin
                $tFin = explode('-', FormatageDate($this->dateFin));
                $numero = count($listeDate['jour']);
                $listeDate['jour'][$numero] = $tFin[0];
                $listeDate['mois'][$numero] = $tFin[1];
                $listeDate['annee'][$numero] = $tFin[2];
            }
        }

        return $listeDate;
    }

    /**
     * Liste des jours ouvrés dans une période donnée 
     *   
     * @param date date de début
     * @param date date de fin
     * @param int indice pour le début du tableau
     *
     * @return array tableau des dates ouvrées dans la période  
     *  
     */
    public function datesOuvres($debut, $fin, $k) {
        $liste = array();
        $liste['jour'] = array();
        $liste['mois'] = array();
        $liste['annee'] = array();

        //calcul du timestamp des dates pour les comparer
        $tDeb = explode('-', FormatageDate($debut));
        $tFin = explode('-', FormatageDate($fin));
        $timestampStart = mktime(0, 0, 0, $tDeb[1], $tDeb[0], $tDeb[2]);
        $timestampEnd = mktime(0, 0, 0, $tFin[1], $tFin[0], $tFin[2]) + 60 * 60;

        while ($timestampStart <= $timestampEnd) {

            // Calul des samedis et dimanches
            $jour_julien = unixtojd($timestampStart);
            $jour_semaine = jddayofweek($jour_julien, 0);

            //récupération de la date que si elle est dans la semaine
            if (($jour_semaine != 0) && ($jour_semaine != 6)) { //Samedi (6) et dimanche (0)
                $jour = date('d', $timestampStart);
                $mois = date('m', $timestampStart);
                $annee = date('Y', $timestampStart);

                //récupération de la date que si elle n'est pas une des dates fériées fixes 
                if (!($jour == '01' && $mois == '01') && !($jour == '01' && $mois == '05') && !($jour == '08' && $mois == '05') &&
                        !($jour == '14' && $mois == '07') && !($jour == '15' && $mois == '08') && !($jour == '01' && $mois == '11') &&
                        !($jour == '11' && $mois == '11') && !($jour == '25' && $mois == '12')) {
                    // Calcul du jour de Pâques
                    $date_paques = easter_date($annee);
                    $jour_paques = date('d', $date_paques);
                    $mois_paques = date('m', $date_paques);
                    // Calcul du Lundi de Pâques (1er jour après Pâques)
                    $date_lundiPaques = $date_paques + 86400;
                    $jour_lundiPaques = date('d', $date_lundiPaques);
                    $mois_lundiPaques = date('m', $date_lundiPaques);
                    // Calcul du jour de l'Ascension (39ème jour après Pâques)
                    $date_ascension = $date_lundiPaques + (38 * 86400);
                    $jour_ascension = date('d', $date_ascension);
                    $mois_ascension = date('m', $date_ascension);
                    // Calcul du jour de la Pentecôte (49ème jour après Pâques)
                    $date_pentecote = $date_lundiPaques + (48 * 86400);
                    $jour_pentecote = date('d', $date_pentecote);
                    $mois_pentecote = date('m', $date_pentecote);
                    // Calcul du Lundi de la Pentecôte (1er jour après Pentecôte)
                    $date_lundiPentecote = $date_pentecote + (86400);
                    $jour_lundiPentecote = date('d', $date_lundiPentecote);
                    $mois_lundiPentecote = date('m', $date_lundiPentecote);

                    //récupération de la date que si elle n'est pas une des dates fériées 
                    if (!($jour_paques == $jour && $mois_paques == $mois) &&
                            !($jour_lundiPaques == $jour && $mois_lundiPaques == $mois) &&
                            !($jour_ascension == $jour && $mois_ascension == $mois) &&
                            !($jour_pentecote == $jour && $mois_pentecote == $mois) &&
                            !($jour_lundiPentecote == $jour && $mois_lundiPentecote == $mois)) {

                        $liste['jour'][$k] = $jour;
                        $liste['mois'][$k] = $mois;
                        $liste['annee'][$k] = $annee;
                        ++$k;
                    }
                }
            }
            $timestampStart = $timestampStart + 86400;
        }
        return $liste;
    }

}

?>