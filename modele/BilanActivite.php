<?php

/**
 * Fichier BilanActivite.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe BilanActivite
 */
class BilanActivite {

    /**
     * Identifiant du bilan d'activité
     *
     * @access private
     * @var int 
     */
    private $Id_bilan_activite;
    /**
     * Date de création du bilan d'activité
     *
     * @access private
     * @var date 
     */
    private $date_creation;
    /**
     * Créateur du bilan d'activité
     *
     * @access private
     * @var string 
     */
    private $createur;
    /**
     * Responsable associé au bilan d'activité
     *
     * @access public
     * @var string 
     */
    public $responsable;
    /**
     * Commercial associé au bilan d'activité
     *
     * @access public
     * @var string 
     */
    public $commercial;
    /**
     * Date de réalisation du bilan d'activité
     *
     * @access public
     * @var string 
     */
    public $date;
    /**
     * Mois concerné par le bilan d'activité
     *
     * @access public
     * @var string 
     */
    public $mois;
    /**
     * Commentaire sur l'avancement du CA
     *
     * @access public
     * @var string 
     */
    public $avancement_ca;
    /**
     * Commentaire sur l'avancement de la marge
     *
     * @access public
     * @var string 
     */
    public $avancement_marge;
    /**
     * Commentaire sur les affaires en cours
     *
     * @access public
     * @var string 
     */
    public $affaires_en_cours;
    /**
     * Commentaire sur les rendez vous
     *
     * @access public
     * @var string 
     */
    public $rdv;
    /**
     * Commentaire sur les ouvertures de comptes
     *
     * @access public
     * @var string 
     */
    public $ouverture_compte;
    /**
     * Commentaire sur les intercontrats
     *
     * @access public
     * @var string 
     */
    public $intercontrats;
    /**
     * Commentaire sur le bilan des collaborateurs suivis
     *
     * @access public
     * @var string 
     */
    public $bilan_collab_suivi;
    /**
     * Commentaire sur les relations humaines
     *
     * @access public
     * @var string 
     */
    public $rh;
    /**
     * Commentaire sur le bilan des heures sup
     *
     * @access public
     * @var string 
     */
    public $bilan_heures_sup;
    /**
     * Commentaire sur les actions à définir
     *
     * @access public
     * @var string 
     */
    public $action_a_definir;
    /**
     * Commentaires divers
     *
     * @access public
     * @var string 
     */
    public $point_divers;
    /**
     * Conclusion du bilan
     *
     * @access public
     * @var string 
     */
    public $conclusion;
    /**
     * Date de la prochaine réunion
     *
     * @access public
     * @var date 
     */
    public $date_prochaine_reunion;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'un bilan d'activité
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Statut
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du statut
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_bilan_activite = '';
                $this->date_creation = '';
                $this->createur = '';
                $this->responsable = '';
                $this->commercial = '';
                $this->date = DATE;
                $this->mois = date('Y-m', strtotime('-1 month'));
                $this->avancement_ca = '';
                $this->avancement_marge = '';
                $this->affaires_en_cours = '';
                $this->rdv = '';
                $this->ouverture_compte = '';
                $this->intercontrats = '';
                $this->bilan_collab_suivi = '';
                $this->rh = '';
                $this->bilan_heures_sup = '';
                $this->action_a_definir = '';
                $this->point_divers = '';
                $this->conclusion = '';
                $this->date_prochaine_reunion = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_bilan_activite = '';
                $this->date_creation = DATETIME;
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->responsable = htmlscperso(stripslashes($tab['responsable']), ENT_QUOTES);
                $this->commercial = htmlscperso(stripslashes($tab['commercial']), ENT_QUOTES);
                $this->date = $tab['date'];
                $this->mois = $tab['mois'];
                $this->avancement_ca = $tab['avancement_ca'];
                $this->avancement_marge = $tab['avancement_marge'];
                $this->affaires_en_cours = $tab['affaires_en_cours'];
                $this->rdv = $tab['rdv'];
                $this->ouverture_compte = $tab['ouverture_compte'];
                $this->intercontrats = $tab['intercontrats'];
                $this->bilan_collab_suivi = $tab['bilan_collab_suivi'];
                $this->rh = $tab['rh'];
                $this->bilan_heures_sup = $tab['bilan_heures_sup'];
                $this->action_a_definir = $tab['action_a_definir'];
                $this->point_divers = $tab['point_divers'];
                $this->conclusion = $tab['conclusion'];
                $this->date_prochaine_reunion = $tab['date_prochaine_reunion'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM bilan_activite WHERE Id_bilan_activite=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_bilan_activite = $code;
                $this->date_creation = $ligne->date_creation;
                $this->createur = $ligne->createur;
                $this->responsable = $ligne->responsable;
                $this->commercial = $ligne->commercial;
                $this->date = $ligne->date;
                $this->mois = $ligne->mois;
                $this->avancement_ca = $ligne->avancement_ca;
                $this->avancement_marge = $ligne->avancement_marge;
                $this->affaires_en_cours = $ligne->affaires_en_cours;
                $this->rdv = $ligne->rdv;
                $this->ouverture_compte = $ligne->ouverture_compte;
                $this->intercontrats = $ligne->intercontrats;
                $this->bilan_collab_suivi = $ligne->bilan_collab_suivi;
                $this->rh = $ligne->rh;
                $this->bilan_heures_sup = $ligne->bilan_heures_sup;
                $this->action_a_definir = $ligne->action_a_definir;
                $this->point_divers = $ligne->point_divers;
                $this->conclusion = $ligne->conclusion;
                $this->date_prochaine_reunion = $ligne->date_prochaine_reunion;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_bilan_activite = $code;
                $this->responsable = htmlscperso(stripslashes($tab['responsable']), ENT_QUOTES);
                $this->commercial = htmlscperso(stripslashes($tab['commercial']), ENT_QUOTES);
                $this->date = $tab['date'];
                $this->mois = $tab['mois'];
                $this->avancement_ca = $tab['avancement_ca'];
                $this->avancement_marge = $tab['avancement_marge'];
                $this->affaires_en_cours = $tab['affaires_en_cours'];
                $this->rdv = $tab['rdv'];
                $this->ouverture_compte = $tab['ouverture_compte'];
                $this->intercontrats = $tab['intercontrats'];
                $this->bilan_collab_suivi = $tab['bilan_collab_suivi'];
                $this->rh = $tab['rh'];
                $this->bilan_heures_sup = $tab['bilan_heures_sup'];
                $this->action_a_definir = $tab['action_a_definir'];
                $this->point_divers = $tab['point_divers'];
                $this->conclusion = $tab['conclusion'];
                $this->date_prochaine_reunion = $tab['date_prochaine_reunion'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un bilan d'activité
     *
     * @return string	   
     */
    public function form() {
        $responsable = new Utilisateur($this->responsable, array());
        $commercial = new Utilisateur($this->commercial, array());
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_COM_SAVE . '" method="post">
            <div>
		        Date du bilan :
		        <input name="date" onfocus="showCalendarControl(this)" type="text" value="' . FormatageDate($this->date) . '" size="8">
				&nbsp;&nbsp;
		        Mois concerné :
				<select name="mois" id="mois">
                    <option value="">' . MONTH_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . getMonthList($this->mois) . '
                </select>
				&nbsp;&nbsp;
				Responsable :
				<select name="responsable">
                    <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $responsable->getList('COM') . '
                </select>
                &nbsp;&nbsp;
				Commercial :
				<select name="commercial" id="commercial">
                    <option value="">' . MARKETING_PERSON_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $commercial->getList('COM') . '
                </select>
				<br /><br />
		        <input type="button" onclick="cacherMenu();afficherIframeRapport()" value="Afficher le Rapport">
		        <div id="iframeRapport">&nbsp;</div>
			</div>	
			<div class="left">
			    Avancement CA : <br /><br />
                <textarea name="avancement_ca" rows="8" cols="50">' . $this->avancement_ca . '</textarea>
				<br /><br />
			    Avancement Marge : <br /><br />
                <textarea name="avancement_marge" rows="8" cols="50">' . $this->avancement_marge . '</textarea>
				<br /><br />
				Affaires en cours : <br /><br />
                <textarea name="affaires_en_cours" rows="8" cols="50">' . $this->affaires_en_cours . '</textarea>
				<br /><br />
				RDV : <br /><br />
                <textarea name="rdv" rows="8" cols="50">' . $this->rdv . '</textarea>
				<br /><br />
				Ouverture(s) de compte(s) : <br /><br />
                <textarea name="ouverture_compte" rows="8" cols="50">' . $this->ouverture_compte . '</textarea>
				<br /><br />
				Intercontrat(s) : <br /><br />
                <textarea name="intercontrats" rows="8" cols="50">' . $this->intercontrats . '</textarea>
				<br /><br />
		        Date de la prochaine réunion :
		        <input name="date_prochaine_reunion" onfocus="showCalendarControl(this)" type="text" value="' . FormatageDate($this->date_prochaine_reunion) . '" size="8"><br /><br />				
			</div>
            <div class="right">			
				Bilan collaborateurs en suivi : <br /><br />
                <textarea name="bilan_collab_suivi" rows="8" cols="50">' . $this->bilan_collab_suivi . '</textarea>
				<br /><br />
				RH : <br /><br />
                <textarea name="rh" rows="8" cols="50">' . $this->rh . '</textarea>
				<br /><br />
				Bilan des heures sup : <br /><br />
                <textarea name="bilan_heures_sup" rows="8" cols="50">' . $this->bilan_heures_sup . '</textarea>
				<br /><br />
				Action(s) à définir : <br /><br />
                <textarea name="action_a_definir" rows="8" cols="50">' . $this->action_a_definir . '</textarea>
				<br /><br />
				Points divers : <br /><br />
                <textarea name="point_divers" rows="8" cols="50">' . $this->point_divers . '</textarea>
				<br /><br />
				Conclusion : <br /><br />
                <textarea name="conclusion" rows="8" cols="50">' . $this->conclusion . '</textarea>
			</div>	
            <div class="submit">
	            <input type="hidden" name="Id" value="' . (int) $this->Id_bilan_activite . '" />
                <input type="hidden" name="class" value="' . __CLASS__ . '" />
	            <input type="submit"  value="' . SAVE_BUTTON . '" />
	        </div>
        </form>
';
        return $html;
    }

    /**
     * Vérification du formulaire
     *
     * Le champ responsable est obligatoire
     * Le champ commercial est obligatoire
     * 		   
     * @return bool
     */
    public function check() {
        if ($this->responsable == '') {
            $this->erreurs['responsable'] = MANAGER_ERROR;
        }
        if ($this->commercial == '') {
            $this->erreurs['commercial'] = COMMERCIAL_ERROR;
        }
        return count($this->erreurs) == 0;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_bilan_activite = ' . mysql_real_escape_string((int) $this->Id_bilan_activite) . ', responsable = "' . mysql_real_escape_string($this->responsable) . '",
		commercial="' . mysql_real_escape_string($this->commercial) . '", date="' . mysql_real_escape_string(DateMysqltoFr($this->date, 'mysql')) . '", mois="' . mysql_real_escape_string($this->mois) . '",
		avancement_ca="' . mysql_real_escape_string($this->avancement_ca) . '", avancement_marge="' . mysql_real_escape_string($this->avancement_marge) . '", affaires_en_cours="' . mysql_real_escape_string($this->affaires_en_cours) . '",
		rdv="' . mysql_real_escape_string($this->rdv) . '", ouverture_compte="' . mysql_real_escape_string($this->ouverture_compte) . '", intercontrats="' . mysql_real_escape_string($this->intercontrats) . '",
		bilan_collab_suivi="' . mysql_real_escape_string($this->bilan_collab_suivi) . '", rh="' . mysql_real_escape_string($this->rh) . '", bilan_heures_sup="' . mysql_real_escape_string($this->bilan_heures_sup) . '",
		action_a_definir="' . mysql_real_escape_string($this->action_a_definir) . '", point_divers="' . mysql_real_escape_string($this->point_divers) . '", conclusion="' . mysql_real_escape_string($this->conclusion) . '",
		date_prochaine_reunion="' . mysql_real_escape_string(DateMysqltoFr($this->date_prochaine_reunion, 'mysql')) . '"
		';
        if ($this->Id_bilan_activite) {
            $requete = 'UPDATE bilan_activite ' . $set . ' WHERE Id_bilan_activite = ' . (int) $this->Id_bilan_activite;
        } else {
            $requete = 'INSERT INTO bilan_activite ' . $set . ', createur = "' . $this->createur . '", date_creation = "' . mysql_real_escape_string(DATETIME) . '"';
        }
        $db->query($requete);
    }

    /**
     * Recherche d'un bilan d'activité
     *
     * @return string
     */
    public static function search($Id_bilan_activite, $responsable, $commercial, $mois, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_bilan_activite', 'responsable', 'commercial', 'mois', 'output');
        $columns = array(array('Mois','mois'), array('Date','date'), array('Responsable','responsable'),
                         array('Commercial','commercial'));
        $db = connecter();
        $requete = 'SELECT Id_bilan_activite, responsable, date, commercial, mois, conclusion,
                    DATE_FORMAT(date, "%d-%m-%Y") AS date_fr
                    FROM bilan_activite WHERE Id_bilan_activite !=""';
        if ($Id_bilan_activite) {
            $requete .= ' AND Id_bilan_activite=' . (int) $Id_bilan_activite;
        }
        if ($responsable) {
            $requete .= ' AND responsable="' . $responsable . '"';
        }
        if ($commercial) {
            $requete .= ' AND commercial="' . $commercial . '"';
        }
        if ($mois) {
            $requete .= ' AND mois="' . $mois . '"';
        }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        }
        else {
            $paramsOrder .= 'orderBy=date';
            $orderBy = 'date';
        }
        if($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherBilanActivite({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterBilanActivite&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if($value[1] == $output['orderBy'])
                        if($output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!$output['orderBy']) {
                        $direction = 'ASC';
                        $img['date'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherBilanActivite({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . self::showMonth($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showResponsable($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showCommercial($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        }
        elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="bilan_activite.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo '"' . self::showMonth($ligne, array('csv' => true)) . '";';
                echo self::showDate($ligne, array('csv' => true)) . ';';
                echo '"' . self::showResponsable($ligne, array('csv' => true)) . '";';
                echo '"' . self::showCommercial($ligne, array('csv' => true)) . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Suppression d'un bilan d'activité
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM bilan_activite WHERE Id_bilan_activite = ' . mysql_real_escape_string((int) $this->Id_bilan_activite));
    }

    /**
     * Affichage du formulaire de recherche d'un bilan d'activité
     *
     * @return string	 
     */
    public function searchForm() {
        $responsable = new Utilisateur($_SESSION['filtre']['responsable'], array());
        $commercial = new Utilisateur($_SESSION['filtre']['commercial'], array());
        $html = '
		n° Bilan d\'activité : <input id="Id_bilan_activite" type="text" onkeyup="afficherBilanActivite()" value="' . $_SESSION['filtre']['Id_bilan_activite'] . '" size="2" />
        &nbsp;&nbsp;		
		<select id="responsable" onchange="afficherBilanActivite()">
            <option value="">Par responsable</option>
            <option value="">----------------------------</option>
		    ' . $responsable->getList("COM") . '
        </select>
		&nbsp;&nbsp;
		<select id="commercial" onchange="afficherBilanActivite()">
			<option value="">Par commercial</option>
			<option value="">----------------------------</option>
			' . $commercial->getList("COM") . '
		</select>
		&nbsp;&nbsp;
		<select id="mois" onchange="afficherBilanActivite()">
			<option value="">Par mois</option>
			<option value="">----------------------------</option>
			' . getMonthList($_SESSION['filtre']['mois']) . '
		</select>		
';
        return $html;
    }

    /**
     * Editer le bilan d'activité en pdf
     */
    public function edit() {
        $_SESSION['titre'] = 'BILAN D\'ACTIVITE';
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetY(30);
        $pdf->setLeftMargin(3);

        $this->avancement_ca = convert_smart_quotes(strip_tags(htmlenperso_decode($this->avancement_ca)));
        $this->avancement_marge = convert_smart_quotes(strip_tags(htmlenperso_decode($this->avancement_marge)));
        $this->affaires_en_cours = convert_smart_quotes(strip_tags(htmlenperso_decode($this->affaires_en_cours)));
        $this->rdv = convert_smart_quotes(strip_tags(htmlenperso_decode($this->rdv)));
        $this->ouverture_compte = convert_smart_quotes(strip_tags(htmlenperso_decode($this->ouverture_compte)));
        $this->intercontrats = convert_smart_quotes(strip_tags(htmlenperso_decode($this->intercontrats)));
        $this->bilan_collab_suivi = convert_smart_quotes(strip_tags(htmlenperso_decode($this->bilan_collab_suivi)));
        $this->rh = convert_smart_quotes(strip_tags(htmlenperso_decode($this->rh)));
        $this->bilan_heures_sup = convert_smart_quotes(strip_tags(htmlenperso_decode($this->bilan_heures_sup)));
        $this->action_a_definir = convert_smart_quotes(strip_tags(htmlenperso_decode($this->action_a_definir)));
        $this->point_divers = convert_smart_quotes(strip_tags(htmlenperso_decode($this->point_divers)));
        $this->conclusion = convert_smart_quotes(strip_tags(htmlenperso_decode($this->conclusion)));

        $pdf->SetStyle('t1', 'arial', 'B', 10, '0,75,150');
        $pdf->SetStyle('t2', 'arial', '', 8, '0,0,0');
        $pdf->SetStyle('t3', 'arial', '', 7, '0,75,150');
        $pdf->SetStyle('t4', 'arial', '', 6, '0,0,0');
        $pdf->setXY(120, 12);
        $pdf->SetTextColor(0, 75, 150);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCellTag(72, 2, "n° {$this->Id_bilan_activite}", 0, 'C', 0);
        $pdf->setXY(70, $pdf->GetY() + 4);
        $pdf->MultiCellTag(130, 2, '<t1>' . Utilisateur::getName($this->commercial) . ' - ' . $this->mois . '</t1>', 0, "C", 0);
        $pdf->setXY(70, $pdf->GetY() + 3);
        $fin = date('Y-m', strtotime($this->mois . '+1 month'));
        $pdf->MultiCellTag(130, 2, '<a href="' . URL_LINK_REPORT . '?%2fAGC%2fAGC+Bilan%2fBilan&rs:Command=Render&rc:toolbar=false&Commercial=' . $this->commercial . '&Debut=' . $this->mois . '&Fin=' . $fin . '">Lien rapport</a>', 0, "C", 0);
        $pdf->setY($pdf->GetY() + 3);
        $pdf->MultiCellTag(200, 3, '<t3>Date de la prochaine réunion : ' . FormatageDate($this->date_prochaine_reunion) . '</t3>', 0, 'C', 0);
        $y0 = $pdf->GetY() + 2;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->setLeftMargin(3);
        $pdf->setXY(3, $y0);
        $pdf->MultiCellTag(95, 5, '<t1>Avancement CA</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->avancement_ca) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->avancement_ca}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Avancement Marge</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->avancement_marge) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->avancement_marge}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Affaires en cours</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->affaires_en_cours) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->affaires_en_cours}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>RDV</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->rdv) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->rdv}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Ouverture(s) de compte(s)</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->ouverture_compte) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->ouverture_compte}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Intercontrat(s)</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->intercontrats) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->intercontrats}</t4>", 0, 'L', 0);
        }

        $pdf->setLeftMargin(110);
        $pdf->setY($y0);

        $pdf->MultiCellTag(95, 5, '<t1>Bilan des collaborateurs en suivi</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->bilan_collab_suivi) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->bilan_collab_suivi}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>RH</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->rh) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->rh}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Bilan des heures sup</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->bilan_heures_sup) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->bilan_heures_sup}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Action(s) à définir</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->action_a_definir) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->action_a_definir}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Point Divers</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->point_divers) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->point_divers}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t1>Conclusion</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->conclusion) {
            $pdf->MultiCellTag(95, 5, "<t4>{$this->conclusion}</t4>", 0, 'L', 0);
        }

        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
            $pdf->setY($y0);
        }
        $pdf->Output();
    }

    public function showMonth($record, $args) {
        if(!$args['csv']) {
            if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
                if ($record['conclusion']) {
                    $info = 'onmouseover="return overlib(\'<div class=commentaire>' . mysql_escape_string($record['conclusion']) . '<br /><hr /><br/>Prochaine Réunion : ' . FormatageDate($record['date_prochaine_reunion']) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                }
            }
            if($record['mois'])
                return '<div ' . $info . '>'. getMonthLibelle($record['mois']).'</div>';
            else
                return '<div ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return getMonthLibelle($record['mois']);
    }
    
    public function showDate($record, $args) {
        if(!$args['csv']) {
            if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
                if ($record['conclusion']) {
                    $info = 'onmouseover="return overlib(\'<div class=commentaire>' . mysql_escape_string($record['conclusion']) . '<br /><hr /><br/>Prochaine Réunion : ' . FormatageDate($record['date_prochaine_reunion']) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                }
            }
            if($record['date_fr'])
                return '<div ' . $info . '>'. $record['date_fr'].'</div>';
            else
                return '<div ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return $record['date_fr'];
    }
    
    public function showCommercial($record, $args) {
        if(!$args['csv']) {
            if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
                if ($record['conclusion']) {
                    $info = 'onmouseover="return overlib(\'<div class=commentaire>' . mysql_escape_string($record['conclusion']) . '<br /><hr /><br/>Prochaine Réunion : ' . FormatageDate($record['date_prochaine_reunion']) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                }
            }
            if($record['commercial'])
                return '<div ' . $info . '>'. $record['commercial'].'</div>';
            else
                return '<div ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return $record['commercial'];
    }
    
    public function showResponsable($record, $args) {
        if(!$args['csv']) {
            if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
                if ($record['conclusion']) {
                    $info = 'onmouseover="return overlib(\'<div class=commentaire>' . mysql_escape_string($record['conclusion']) . '<br /><hr /><br/>Prochaine Réunion : ' . FormatageDate($record['date_prochaine_reunion']) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                }
            }
            if($record['responsable'])
                return '<div ' . $info . '>'. $record['responsable'].'</div>';
            else
                return '<div ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return $record['responsable'];
    }
    
    public function showButtons($record) {
        if (Utilisateur::getDroitEditionBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
            $htmlAdmin = '<td><a href="../com/index.php?a=editerBilanActivite&amp;Id=' . $record['id_bilan_activite'] . '"><img src="' . IMG_PDF . '"></a></td>';
        }
        if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_bilan_activite'])) {
            $htmlAdmin .= '<td><a href="../com/index.php?a=modifierBilanActivite&amp;Id=' . $record['id_bilan_activite'] . '"><img src="' . IMG_EDIT . '"></a></td>';
        }
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            $htmlAdmin .= '
                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../gestion/index.php?a=supprimer&amp;Id=' . $record['id_bilan_activite'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        }
        return $htmlAdmin;
    }
}

?>
