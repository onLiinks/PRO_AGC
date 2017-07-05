<?php

/**
 * Fichier PlanningSession.php
 *
 * @author    Fr�d�rique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * D�claration de la classe Planning_Session
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
     * Liste des dates de d�but des p�riodes interm�diaires de la session
     *
     * @access public
     * @var array 
     */
    public $periode_debut;
    /**
     * Liste des dates de fin des p�riodes interm�diaires de la session
     *
     * @access public
     * @var array 
     */
    public $periode_fin;
    /**
     * Date de d�but
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
     * Date de fin pr�visionnelle
     *
     * @access public
     * @var date
     */
    public $dateFinPrev;
    /**
     * Dur�e de la session en jour
     *
     * @access public
     * @var int
     */
    public $nb_Jour;

    /**
     * Constructeur de la classe Planning_Session
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'instance Planning_Session
     * @param array Tableau pass� en argument : tableau $_POST ici
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

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
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

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM planning_session WHERE Id_plan_session=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_planning_session = $code;
                $this->Id_session = $ligne->id_session;
                $this->dateDebut = $ligne->datedebut;
                $this->dateFin = $ligne->datefin;
                $this->dateFinPrev = $ligne->datefinprev;
                $this->nb_Jour = $ligne->nb_jour;

                //r�cup des dates ponctuelles de la session
                $result2 = $db->query('SELECT d.date FROM date AS d INNER JOIN planning_date AS dp ON d.Id_date=dp.Id_date 
							 WHERE dp.Id_plan_session=' . mysql_real_escape_string((int) $this->Id_planning_session) . ' ORDER BY d.date');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->date[] = $ligne2->date;
                }

                //r�cup des p�riodes interm�diaires de la session
                $result2 = $db->query('SELECT periode_debut, periode_fin FROM periode_session 
							 WHERE Id_planning_session=' . mysql_real_escape_string((int) $this->Id_planning_session) . ' ORDER BY periode_debut');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->periode_debut[] = $ligne2->periode_debut;
                    $this->periode_fin[] = $ligne2->periode_fin;
                }
            }

            /* Cas 4 : un code et un tableau : prendre  infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
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
     * Formulaire de cr�ation / modification de l'instance Planning_Session
     *
     * @return string
     */
    public function form() {
        //Mise en forme des dates entre la base de donn�es et l'affichage fran�ais
        $html = '<input type="hidden" name="Id_planning_session" id="Id_planning_session" value="' . $this->Id_planning_session . '" />
				<div class="left">
					Date de d�but de session :&nbsp;&nbsp;
					<input type="text" onFocus="showCalendarControl(this, function(){joursOuvresSession(1)})"  id="dateDebut" name="dateDebut" size="8" value="' . FormatageDate($this->dateDebut) . '"/><br/><br/>
				</div>
				<div class="right">
					Date de fin de session :&nbsp;&nbsp;
					<input type="text" onfocus="showCalendarControl(this, function(){joursOuvresSession(1)})" id="dateFin" name="dateFin" size="8" value="' . FormatageDate($this->dateFin) . '"/><br/><br/>
					Date de fin pr�visionnelle de session :&nbsp;&nbsp;
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
					P�riode(s) si la session n\'est pas continue :<br/><br/>
						' . $this->formPeriodeSup() . '
					<input type="button" onclick="ajoutPeriodeSession()" value="+">
					<input type="button" onclick="enlevePeriodeSession()" value="-">
						<br/><br/><br/>	
				</div>
				<div class="center"><br/><br/>
					<div id="nombre_Jour">
							<input type="button" onclick="joursOuvresSession(1)" value="Calcul nb Jours">&nbsp;&nbsp;&nbsp;&nbsp;
							Dur�e de la session :&nbsp;&nbsp;
							<input type="text" id="nb_Jour" name="nb_Jour" value="' . $this->nb_Jour . '" size="8">&nbsp;&nbsp;jours	
						<br/><br/></div>
				<br/></div>';
        return $html;
    }

    /**
     * Formulaire de cr�ation/modification des dates ponctuelles
     *
     * @return string
     */
    public function formDateSup() {
        $count = count($this->date);
        //Si le tableau est d�j� rempli : formulaire pour modification, il faut afficher toutes les dates ponctuelles
        if ($count > 0) {
            $html = '';
            $nb = 1;
            //emboitement des div et afichage de chaque date ponctuelle
            while ($nb < $count) {
                $nb2 = $nb + 1;
                //Mise en forme de la date entre la base de donn�es et l'affichage fran�ais
                $this->date[$nb - 1] = FormatageDate($this->date[$nb - 1]);
                $html .= 'Date n�' . $nb . '&nbsp;:&nbsp;
							<input type="text" onfocus="showCalendarControl(this)" id="date' . $nb . '" 
									name="date[]" size="8" value="' . $this->date[$nb - 1] . '">
						<br/><br/>
						<div id="autreDate' . $nb2 . '">';
                ++$nb;
            }
            // ajout de la derni�re date ponctuelle
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
     * @param int num�ro de la date ponctuelle
     *
     * @return string
     */
    public function addDate($nb) {
        // num�ro de la prochaine date ponctuelle
        $nb2 = $nb + 1;
        //Mise en forme de la date entre la base de donn�es et l'affichage fran�ais
        $this->date[$nb - 1] = FormatageDate($this->date[$nb - 1]);

        $html = 'Date n�' . $nb . '&nbsp;:&nbsp;
				<input type="text"  onfocus="showCalendarControl(this)" id="date' . $nb . '" 
						name="date[]" size="8" value="' . $this->date[$nb - 1] . '">
				<br/><br/>
		<div id="autreDate' . $nb2 . '"><input type="hidden" id="nb_Date" name="nb_Date[]" value=' . $nb . '></div>';
        return $html;
    }

    /**
     * Formulaire de cr�ation/modification des p�riodes interm�diaires
     *
     * @return string
     */
    public function formPeriodeSup() {
        $count = count($this->periode_debut);
        //Si le tableau est d�j� rempli : formulaire pour modification, il faut afficher toutes les p�riodes
        if ($count > 0) {
            $html = '';
            $nb = 1;
            //emboitement des div et afichage de chaque p�riode
            while ($nb < $count) {
                $nb2 = $nb + 1;
                //Mise en forme des dates entre la base de donn�es et l'affichage fran�ais
                $html .= 'P�riode n�' . $nb . '&nbsp;&nbsp;du&nbsp; 
							<input type="text"  onfocus="showCalendarControl(this)"  id="periode_debut' . $nb . '" 
									name="periode_debut[]" size="8" value="' . FormatageDate($this->periode_debut[$nb - 1]) . '">
							&nbsp;&nbsp;au&nbsp;
							<input type="text"  onfocus="showCalendarControl(this)"  id="periode_fin' . $nb . '" 
									name="periode_fin[]" size="8" value="' . FormatageDate($this->periode_fin[$nb - 1]) . '">
							<br/><br/>
						<div id="autrePeriode' . $nb2 . '">';
                ++$nb;
            }
            // ajout de la derni�re p�riode
            $html .= $this->addPeriod($count);
            $nb = 1;
            // fermeture des div
            while ($nb < $count) {
                $html .= '</div>';
                ++$nb;
            }
        }
        // si tableau est vide, affichage d'une seule p�riode
        else if ($count == 0) {
            $html = $this->addPeriod(1);
        }
        return $html;
    }

    /**
     * Ajout de nouveaux champs pour saisie d'une nouvelle p�riode int�rm�diaire
     *
     * @param int num�ro de la p�riode
     *
     * @return string
     */
    public function addPeriod($nb) {
        // num�ro de la prochaine p�riode
        $nb2 = $nb + 1;
        //Mise en forme des dates entre la base de donn�es et l'affichage fran�ais
        $html = 'P�riode n�' . $nb . '&nbsp;&nbsp;du&nbsp; 
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
     * Enregistrement des donn�es du planning dans la BDD 
     *
     */
    public function save() {
        $db = connecter();
        //Mise en forme des dates pour compatibilit� avec la base de donn�e
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
            //pour �liminer les dates suppl�mentaires existantes d�j� et par la suite tout remis � jour
            $db->query('DELETE FROM planning_date WHERE Id_plan_session = ' . mysql_real_escape_string((int) $this->Id_planning_session));
            //pour �liminer les p�riodes suppl�mentaires existantes d�j� et par la suite tout remis � jour
            $db->query('DELETE FROM periode_session WHERE Id_planning_session = ' . mysql_real_escape_string((int) $this->Id_planning_session));
        } else {
            $requete = 'INSERT INTO planning_session ' . $set;
        }
        $db->query($requete);

        //r�cup�ration de l'identifiant si l'instance n'existait pas avant
        if ($this->Id_planning_session == '') {
            $this->Id_planning_session = mysql_insert_id();
        }

        //Enregistrement des dates interm�diaires si elles existent
        $i = 0;
        $nb_date = count($this->date);
        while ($i < $nb_date) {
            if ($this->date[$i]) {
                //Mise en forme des dates pour compatibilit� avec la base de donn�e
                $this->date[$i] = DateMysqltoFr($this->date[$i], 'mysql');
                //r�cup de l'identifiant de la date dans la base de donn�es
                $ligne2 = $db->query('SELECT Id_date FROM date WHERE date = "' . mysql_real_escape_string($this->date[$i]) . '"')->fetchRow();
                //si la date n'existait pas, cr�ation d'un enregistrement 
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
        //Enregistrement des dates interm�diaires si elles existent
        $i = 0;
        $nb_periode = count($this->periode_debut);
        while ($i < $nb_periode) {
            if ($this->periode_debut[$i] || $this->periode_fin[$i]) {
                //Mise en forme des dates pour compatibilit� avec la base de donn�e
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
        //Mise en forme des dates entre la base de donn�es et l'affichage fran�ais
        $html = '
		    <div class="left">
			    <b>Date de d�but de session :</b>  ' . FormatageDate($this->dateDebut) . '<br/><br/>
			</div>
			<div class="right">
				<b>Date de fin de session :</b> ' . FormatageDate($this->dateFin) . '<br/><br/>
				<b>Date de fin pr�visionnelle de session :</b> ' . FormatageDate($this->dateFinPrev) . '<br/><br/>
			</div>
		    <div class="center">
				<b>Dur�e de la session :</b> ' . $this->nb_Jour . ' jour(s)<br/><br/>
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
        //affichage des p�riodes uniquement si il y en a
        $nb = count($this->periode_debut);
        if ($nb) {
            $html .= '<b>P�riode(s) :</b> ';
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
     * Information du planning de la session � joindre et � afficher dans l'affaire 
     *
     * @param int identifiant de la session dont il faut r�cup�rer le planning
     *
     * @return string
     */
    public static function infoSession($Id_session) {
        //r�cup des donn�es concernant la session dans la base de donn�es
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
        $html .= '<span class="vert"> * </span> D�but de la prestation :
				<input id="date_debut" name="date_debut" type="text" value="' . $debut . '" size="8" readonly=true><br /><br />
				<span class="vert"> * </span> Fin de la prestation :
				<input id="date_fin" name="date_fin_commande" type="text" value="' . $fin . '" size="8" readonly=true><br /><br />
				<span class="vert"> * </span> Fin pr�visionnelle de la prestation:
				<input id="date_fin_previsionnelle" name="date_fin_previsionnelle" type="text" value="' . $fin_prev . '" size="8" readonly=true><br /><br />				
				Dur�e de la prestation : <br />
				<input type="text" id="duree" name="duree" value="' . $ligne->nb_jour . '" size="1" readonly=true>
				jours (ouvr�s)
				';

        return $html;
    }

    /**
     * Mise � jour des donn�es associ�es � la session dans les affaires associ�es lors de la modification d'une session
     *   
     */
    public function updateRelatedCases() {
        //r�cup�ration des affaires associ�es � la session
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
     * Cr�ation liste de toutes les dates ouvr�es de la session
     *  
     * @return array
     *  
     */
    public function listeDates() {
        $listeDate = array();

        //Si il n'y a qu'une seule date, r�cup�ration de la date
        if ($this->dateDebut == $this->dateFin) {
            $tDeb = explode('-', FormatageDate($this->dateDebut));
            $listeDate['jour'][0] = $tDeb[0];
            $listeDate['mois'][0] = $tDeb[1];
            $listeDate['annee'][0] = $tDeb[2];
        } else { //si il y a plusieurs dates, r�cup�ration de toutes les dates
            $nbPeriode = count($this->periode_debut);
            $nbDate = count($this->date);
            //si seulement une date de d�but et de fin : liste de toutes les dates des jours ouvr�s dans cette p�riode
            if (!$nbDate && !$nbPeriode) {
                //Calcul des dates entre la date de d�but et la date de fin
                $listeDate = $this->datesOuvres($this->dateDebut, $this->dateFin, 0);
            } else { //si il y a des dates ponctuelles ou des p�riodes : r�cup�ration de toutes les dates par ordre chronologique
                //premier �l�ment de la liste : la date de d�but
                $tDeb = explode('-', FormatageDate($this->dateDebut));
                $listeDate['jour'][0] = $tDeb[0];
                $listeDate['mois'][0] = $tDeb[1];
                $listeDate['annee'][0] = $tDeb[2];

                /* si il n'y a que des dates ponctuelles : liste des dates ponctuelles en enlevant les dates de d�but et 
                  de fin si sont pr�sentes */
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
                /* si il n'y a que des p�riodes : liste des dates des jours ouvr�s pour chaque p�riode en enlevant les dates 
                  de d�but et de fin */ else if (!$nbDate && $nbPeriode) {
                    $j = 0;
                    while ($j < $nbPeriode) {
                        /* si la date de d�but de la p�riode est celle de d�but de session (d�j� compt�e dans la liste), 
                          d�but de la p�riode un jour plus tard */
                        if ($this->periode_debut[$j] == $this->dateDebut) {
                            $tDate = explode('-', FormatageDate($this->periode_debut[$j]));
                            $this->periode_debut[$j] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] + 1, $tDate[2]));
                        }
                        /* si la date de fin de la p�riode est celle de fin de session (compt�e apr�s dans la liste), 
                          fin de la p�riode un jour plus t�t */
                        if ($this->periode_fin[$j] == $this->dateFin) {
                            $tDate = explode('-', FormatageDate($this->periode_fin[$j]));
                            $this->periode_fin[$j] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] - 1, $tDate[2]));
                        }
                        /* r�cup�ration de la liste des dates des jours ouvr�s pour la p�riode et association � la 
                          liste des dates de la session */
                        $listeTempo = $this->datesOuvres($this->periode_debut[$j], $this->periode_fin[$j], count($listeDate['jour']));
                        $listeDate['jour'] = array_merge($listeDate['jour'], $listeTempo['jour']);
                        $listeDate['mois'] = array_merge($listeDate['mois'], $listeTempo['mois']);
                        $listeDate['annee'] = array_merge($listeDate['annee'], $listeTempo['annee']);
                        ++$j;
                    }
                } else {
                    /* si il y a des dates ponctuelles et des p�riodes, liste de toutes les dates en rangeant les dates 
                      par ordre chronologique et en enlevant les dates de d�but et de fin */
                    $nbD = 0; //compteur des dates ponctuelles
                    $nbP = 0; //compteur des p�riodes
                    $finiD = 0; // indicateur que toutes les dates ponctuelles ont �t� r�cup�r�es
                    $finiP = 0; // indicateur que toutes les p�riodes ont �t� r�cup�r�es
                    //tant que toutes les dates et les p�riodes n'ont pas �t� r�cup�r�es
                    while (!$finiD || !$finiP) {

                        //calcul du timestamp des dates pour les comparer
                        $tDate = explode('-', FormatageDate($this->date[$nbD]));
                        $tPeriode = explode('-', FormatageDate($this->periode_debut[$nbP]));
                        $timestampDate = mktime(0, 0, 0, $tDate[1], $tDate[0], $tDate[2]);
                        $timestampPeriode = mktime(0, 0, 0, $tPeriode[1], $tPeriode[0], $tPeriode[2]);

                        /* si la date ponctuelle en cours est avant la p�riode en cours et que les dates ponctuelles n'ont 
                          pas toutes d�j� �t� r�cup�r�es ou si la date ponctuelle en cours est apr�s la p�riode en cours mais
                          toutes les p�riodes ont d�j� �t� r�cup�r�es */
                        if ((($timestampDate < $timestampPeriode) && !$finiD) || (($timestampDate > $timestampPeriode) && $finiP)) {
                            //r�cup�ration de la date que si elle ne correspond ni � la date de d�but ni � celle de fin
                            if (($this->date[$nbD] != $this->dateDebut) && ($this->date[$nbD] != $this->dateFin)) {
                                $numero = count($listeDate['jour']);
                                $tDate = explode('-', FormatageDate($this->date[$nbD]));
                                $listeDate['jour'][$numero] = $tDate[0];
                                $listeDate['mois'][$numero] = $tDate[1];
                                $listeDate['annee'][$numero] = $tDate[2];
                            }
                            //si la date trait�e est la derni�re, passe l'indicateur de fin � 1
                            if ($nbD == ($nbDate - 1)) {
                                $finiD = 1;
                            } else { //sinon augmente le compteur de date
                                ++$nbD;
                            }
                        }
                        /* si la p�riode en cours est avant la date ponctuelle en cours et que les p�riodes n'ont pas toutes d�j� 
                          �t� r�cup�r�es ou si la p�riode en cours est apr�s la date ponctuelle en cours mais toutes les dates
                          ponctuelles ont d�j� �t� r�cup�r�es */ else {
                            /* si la date de d�but de la p�riode est celle de d�but de session (d�j� compt�e dans la liste), 
                              d�but de la p�riode un jour plus tard */
                            if ($this->periode_debut[$nbP] == $this->dateDebut) {
                                $this->periode_debut[$nbP] = date("Y-m-d", mktime(0, 0, 0, $tPeriode[1], $tPeriode[0] + 1, $tPeriode[2]));
                            }
                            /* si la date de fin de la p�riode est celle de fin de session (compt�e apr�s dans la liste), 
                              fin de la p�riode un jour plus t�t */
                            if ($this->periode_fin[$nbP] == $this->dateFin) {
                                $tDate = explode('-', FormatageDate($this->periode_fin[$nbP]));
                                $this->periode_fin[$nbP] = date("Y-m-d", mktime(0, 0, 0, $tDate[1], $tDate[0] - 1, $tDate[2]));
                            }

                            //r�cup�ration de la liste des dates des jours ouvr�s pour la p�riode et association 
                            //� la liste des dates de la session
                            $listeTempo = $this->datesOuvres($this->periode_debut[$nbP], $this->periode_fin[$nbP], count($listeDate['jour']));
                            $listeDate['jour'] = array_merge($listeDate['jour'], $listeTempo['jour']);
                            $listeDate['mois'] = array_merge($listeDate['mois'], $listeTempo['mois']);
                            $listeDate['annee'] = array_merge($listeDate['annee'], $listeTempo['annee']);

                            //si la p�riode trait�e est la derni�re, passe l'indicateur de fin � 1
                            if ($nbP == ($nbPeriode - 1)) {
                                $finiP = 1;
                            } else { //sinon augmente le compteur de p�riode
                                ++$nbP;
                            }
                        }
                    }
                }
                //dernier �l�ment de la liste : la date de fin
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
     * Liste des jours ouvr�s dans une p�riode donn�e 
     *   
     * @param date date de d�but
     * @param date date de fin
     * @param int indice pour le d�but du tableau
     *
     * @return array tableau des dates ouvr�es dans la p�riode  
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

            //r�cup�ration de la date que si elle est dans la semaine
            if (($jour_semaine != 0) && ($jour_semaine != 6)) { //Samedi (6) et dimanche (0)
                $jour = date('d', $timestampStart);
                $mois = date('m', $timestampStart);
                $annee = date('Y', $timestampStart);

                //r�cup�ration de la date que si elle n'est pas une des dates f�ri�es fixes 
                if (!($jour == '01' && $mois == '01') && !($jour == '01' && $mois == '05') && !($jour == '08' && $mois == '05') &&
                        !($jour == '14' && $mois == '07') && !($jour == '15' && $mois == '08') && !($jour == '01' && $mois == '11') &&
                        !($jour == '11' && $mois == '11') && !($jour == '25' && $mois == '12')) {
                    // Calcul du jour de P�ques
                    $date_paques = easter_date($annee);
                    $jour_paques = date('d', $date_paques);
                    $mois_paques = date('m', $date_paques);
                    // Calcul du Lundi de P�ques (1er jour apr�s P�ques)
                    $date_lundiPaques = $date_paques + 86400;
                    $jour_lundiPaques = date('d', $date_lundiPaques);
                    $mois_lundiPaques = date('m', $date_lundiPaques);
                    // Calcul du jour de l'Ascension (39�me jour apr�s P�ques)
                    $date_ascension = $date_lundiPaques + (38 * 86400);
                    $jour_ascension = date('d', $date_ascension);
                    $mois_ascension = date('m', $date_ascension);
                    // Calcul du jour de la Pentec�te (49�me jour apr�s P�ques)
                    $date_pentecote = $date_lundiPaques + (48 * 86400);
                    $jour_pentecote = date('d', $date_pentecote);
                    $mois_pentecote = date('m', $date_pentecote);
                    // Calcul du Lundi de la Pentec�te (1er jour apr�s Pentec�te)
                    $date_lundiPentecote = $date_pentecote + (86400);
                    $jour_lundiPentecote = date('d', $date_lundiPentecote);
                    $mois_lundiPentecote = date('m', $date_lundiPentecote);

                    //r�cup�ration de la date que si elle n'est pas une des dates f�ri�es 
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