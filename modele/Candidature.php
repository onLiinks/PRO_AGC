<?php

/**
 * Fichier Candidature.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Candidature
 */
class Candidature {

    /**
     * Identifiant de la candidature
     *
     * @access public
     * @var int
     */
    public $Id_candidature;

    /**
     * Identifiant de la ressource
     *
     * @access public
     * @var srting
     */
    public $Id_ressource;

    /**
     * Date de la candidature
     *
     * @access private
     * @var date
     */
    private $date;

    /**
     * Indentifiant de l'etat de la candidature
     *
     * @access public
     * @var int
     */
    public $Id_etat;

    /**
     * Type de la validation de la candidature
     *
     * @access public
     * @var string
     */
    public $type_validation;

    /**
     * Type de l'embauche du candidat
     *
     * @access public
     * @var string
     */
    public $type_embauche;

    /**
     * Indentifiant de la nature de la candidature
     *
     * @access private
     * @var int
     */
    private $Id_nature;

    /**
     * Tableau des types de contrat souhaités par le candidat
     *
     * @access public
     * @var array
     */
    public $type_contrat;

    /**
     * La ou les agences de rattachement
     * @var array
     * @access private
     */
    private $agence_souhaitee;

    /**
     * Créateur de la candidature
     *
     * @access public
     * @var string
     */
    public $createur;

    /**
     * Lien vers le CV du candidat
     *
     * @access public
     * @var string
     */
    public $lien_cv;

    /**
     * Lien vers le CV au format proservia du candidat
     *
     * @access public
     * @var string
     */
    public $lien_cvp;

    /**
     * Lien vers la lettre de motivation du candidat
     *
     * @access public
     * @var string
     */
    public $lien_lm;

    /**
     * Commentaire sur la candidature
     *
     * @access private
     * @var string
     */
    private $commentaire;

    /**
     * Indique si la candidature est une archive
     *
     * @access private
     * @var int
     */
    private $archive;

    /**
     * Tableau contenant les erreurs suite à la création / modification d'une candidature
     *
     * @access public
     * @var array
     */
    public $erreurs;

    /**
     * Identifiant de la candidature web
     *
     * @access public
     * @var int
     */
    public $Id_cvweb;

    /**
     * Date de réponse de la candidature
     *
     * @access public
     * @var date
     */
    public $date_reponse;

    /**
     * Identifiant de l'action menée
     *
     * @access public
     * @var int
     */
    public $Id_action_mener;

    /**
     * Indique si la candidature est staff ou non
     *
     * @access public
     * @var  int
     */
    public $staff;

    /**
     * Constructeur de la classe Candidature
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la candidature
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        $this->agence_souhaitee = array();
        $this->type_contrat = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_candidature = '';
                $this->Id_ressource = '';
                $this->date = DATE;
                $this->Id_nature = '';
                $this->Id_etat = '';
                $this->type_validation = '';
                $this->type_embauche = '';
                $this->createur = '';
                $this->type_contrat = '';
                $this->lien_cv = '';
                $this->lien_cvp = '';
                $this->lien_lm = '';
                $this->commentaire = '';
                $this->archive = 0;
                $this->Id_cvweb = '';
                $this->date_reponse = '';
                $this->Id_action_mener = '';
                $this->staff = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_candidature = '';
                $this->Id_ressource = $tab['Id_ressource'];
                $this->date = $tab['date'];
                $this->Id_nature = (int) $tab['Id_nature'];
                $this->Id_etat = (int) $tab['Id_etat'];
                $this->type_validation = htmlscperso(stripslashes($tab['type_validation']), ENT_QUOTES);
                $this->type_embauche = htmlscperso(stripslashes($tab['type_embauche_etat']), ENT_QUOTES);
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->type_contrat = $tab['type_contrat'];
                $this->lien_cv = $tab['lien_cv'];
                $this->lien_cvp = $tab['lien_cvp'];
                $this->lien_lm = $tab['lien_lm'];
                $this->commentaire = $tab['commentaire'];
                $this->archive = (int) $tab['archive'];
                $this->date_reponse = $tab['date_reponse'];
                $this->Id_action_mener = (int) $tab['Id_action_mener'];
                $this->agence_souhaitee = $tab['agence_souhaitee'];
                $this->staff = (int) $tab['staff'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_candidature = $code;
                $this->Id_ressource = $ligne->id_ressource;
                $this->date = $ligne->date;
                $this->Id_nature = $ligne->id_nature;
                $this->Id_etat = $db->query('SELECT Id_etat FROM historique_candidature WHERE Id_candidature = ' . mysql_real_escape_string((int) $code) . ' AND date=(SELECT MAX(date) FROM historique_candidature WHERE Id_candidature = ' . mysql_real_escape_string((int) $code) . ')')->fetchRow()->id_etat;
                $this->type_validation = $ligne->type_validation;
                $this->type_embauche = $ligne->type_embauche;
                $this->createur = $ligne->createur;
                $this->lien_cv = $ligne->lien_cv;
                $this->lien_cvp = $ligne->lien_cvp;
                $this->lien_lm = $ligne->lien_lm;
                $this->commentaire = strip_tags($ligne->commentaire);
                $this->archive = $ligne->archive;
                $this->date_reponse = $ligne->date_reponse;
                $this->Id_action_mener = $ligne->id_action_mener;
                $this->Id_cvweb = $ligne->id_cvweb;
                $this->staff = $ligne->staff;
                $this->Id_entretien = $db->query('SELECT Id_entretien FROM entretien WHERE Id_candidature=' . mysql_real_escape_string((int) $code))->fetchRow()->id_entretien;

                //récup des agences souhaitées
                $result = $db->query('SELECT Id_agence FROM candidat_agence WHERE Id_candidat=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->agence_souhaitee[] = $ligne->id_agence;
                }
                $result = $db->query('SELECT Id_type_contrat FROM candidat_typecontrat WHERE Id_candidat=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->type_contrat[] = $ligne->id_type_contrat;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_candidature = $code;
                $this->Id_ressource = $tab['Id_ressource'];
                $this->date = $tab['date'];
                $this->Id_nature = (int) $tab['Id_nature'];
                $this->Id_etat = (int) $tab['Id_etat'];
                $this->type_validation = htmlscperso(stripslashes($tab['type_validation']), ENT_QUOTES);
                $this->type_embauche = htmlscperso(stripslashes($tab['type_embauche_etat']), ENT_QUOTES);
                $this->type_contrat = $tab['type_contrat'];
                $this->commentaire = $tab['commentaire'];
                $this->archive = (int) $tab['archive'];
                $this->date_reponse = $tab['date_reponse'];
                $this->Id_action_mener = (int) $tab['Id_action_mener'];
                $this->agence_souhaitee = $tab['agence_souhaitee'];
                $this->staff = (int) $tab['staff'];

                $db = connecter();
                $requete = 'SELECT lien_cv, lien_cvp, lien_lm, Id_cvweb,createur FROM candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $code);
                $result = $db->query($requete);
                $ligne = $result->fetchRow();
                $this->Id_cvweb = $ligne->id_cvweb;
                $this->createur = $ligne->createur;

                if ($tab['lien_cv'] != '') {
                    $this->lien_cv = $tab['lien_cv'];
                    if ($ligne->lien_cv != $this->lien_cv) {
                        if (is_file(CV_DIR . $ligne->lien_cv)) {
                            unlink(CV_DIR . $ligne->lien_cv);
                        }
                    }
                } else {
                    $this->lien_cv = $ligne->lien_cv;
                }
                if ($tab['lien_cvp'] != '') {
                    $this->lien_cvp = $tab['lien_cvp'];
                    if ($ligne->lien_cvp != $this->lien_cvp) {
                        if (is_file(CV_DIR . $ligne->lien_cvp)) {
                            unlink(CV_DIR . $ligne->lien_cvp);
                        }
                    }
                } else {
                    $this->lien_cvp = $ligne->lien_cvp;
                }
                if ($tab['lien_lm'] != '') {
                    $this->lien_lm = $tab['lien_lm'];
                    if ($ligne->lien_lm != $this->lien_lm) {
                        if (is_file(LM_DIR . $ligne->lien_lm)) {
                            unlink(LM_DIR . $ligne->lien_lm);
                        }
                    }
                } else {
                    $this->lien_lm = $ligne->lien_lm;
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une candidature
     *
     * @return string
     */
    public function form() {
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, array());
        $staff[$this->staff] = 'checked="checked"';
        if ($this->Id_candidature) {
            $htmlH = $this->history(1);
            $htmlHAction = $this->actionHistory(1);
            if ($this->lien_cv) {
                $hmtlCv = "CV actuel : <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cv . "'>" . $this->lien_cv . "</a><br /><br />";
            }
            if ($this->lien_cvp) {
                $hmtlCvp = "CV actuel Groupe Proservia : <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cvp . "'>" . $this->lien_cvp . "</a><br /><br />";
            }
            if ($this->lien_lm) {
                $hmtlLm = "Lettre de motivation actuelle : <a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $this->lien_lm . "'>" . $this->lien_lm . "</a><br /><br />";
            }
            if ($this->Id_etat == 6) {
                $htmlTV = $this->typeValidation();
            }
            if ($this->Id_etat == 8 || $this->Id_etat == 9) {
                $htmlTV = $this->typeEmbauche();
            }
            $htmlEnt = '<a href="index.php?a=creer_entretien&Id_candidature=' . $this->Id_candidature . '">Feuille Entretien</a>';

            if ($this->isCvweb()) {
                $htmlModif = ' | <a href="javascript:ouvre_popup(\'index.php?a=modifCvweb&Id_candidature=' . $this->Id_candidature . '\')">Modifications CVweb (' . $this->nbCvWebModification() . ')</a>';
                $htmlPostulation = ' | <a href="javascript:ouvre_popup(\'index.php?a=postulationCvweb&Id_cvweb=' . $this->isCvweb() . '\')">Postulations CVweb (' . $this->nbCvWebApply() . ')</a>';
            }
            $htmlPositionnement = $ressource->getPositioning(1);
        }
        $html = '
		    <h2>' . $htmlEnt . '
			' . $htmlModif . '
			' . $htmlPostulation . '</h2>
			<form name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_candidature" method="post" class="serialize">
                <div class="submit">
				    <input type="hidden" name="Id" id="Id_candidature" value="' . $this->Id_candidature . '" />
	                <input type="submit" value="' . SAVE_BUTTON . '" onclick="noPrompt();return verifForm(this.form)" />
			        <br /><br />
					<span class="infoFormulaire"> * </span> : ' . FORCED_FIELD . ' <br />
					<span class="infoFormulaire"> ** </span> : ' . HIRE_FORCED_FIELD . '
		        </div>
				' . $ressource->form() . '
				<div class="clearer"><br /><hr /></div>
				<div class="left">
					Date de saisie de la candidature :
		            <input type="text" id="date" name="date" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->date) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
					Date de réponse :
		            <input type="text" name="date_reponse" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->date_reponse) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
				    <span class="infoFormulaire"> * </span>Nature de la candidature : 
				    <select id="nature" name="Id_nature">
                        <option value="">' . NATURE_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getNatureListe() . '
				    </select>
				    <br /><br />
				    <span class="infoFormulaire"> * </span>Etat de la candidature : 
				    <select id="etat" name="Id_etat" onchange="updateEtatCandidature(this.value)">
                        <option value="">' . STATE_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getEtatListe() . '
				    </select>
                                    <br/><br />
                                        <span class="infoFormulaire"> * </span> Agence de rattachement : 
                                        <select name="agence_souhaitee">
                                            <option value="">Sélectionner une agence de rattachement</option>
                                            <option value="">--------------------------------------------------------------------</option>
                                            ' . Agence::getSelectList($this->agence_souhaitee) . '<br/>
                                        </select>
				    <br /><br />
					<div id="typeval">' . $htmlTV . '</div>
					<div id="historique">' . $htmlH . '</div>
				    Action menée : 
				    <select name="Id_action_mener" onchange="updateActionCandidature(this.value)">
                        <option value="">' . ACTION_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getActionMener() . '
				    </select>
				    <br /><br />					
				    Staff :
					' . YES . ' <input type="radio" name="staff" ' . $staff['1'] . ' value="1" />
				    ' . NO . ' <input type="radio" name="staff" ' . $staff['0'] . ' value="0" /><br /><br />
				    Type de contrats recherchés :
					<br />Veuillez appuyer sur CTRL pour en sélectionner plusieurs<br /><br />
				    <select name="type_contrat[]" multiple size="5">
                        <option value="">Sélectionner un type de contrat</option>
                        <option value="">----------------------------</option>
			            ' . self::getSearchContractTypeForm($this->Id_candidature) . '
                    </select>
			    </div>
				<div class="right">
					' . $hmtlCv . '
					<input type="hidden" id="cv_actuel" value="' . $this->lien_cv . '" />
					<span class="infoFormulaire"> * </span> CV :
                    <input type="hidden" name="MAX_FILE_SIZE" value="6000000">
                    <input id="cv" name="cv" type="file"> (< 5 Mo )<br /><hr /><br />
                    ' . $hmtlLm . '
					Lettre de Motivation :
					<input name="lm" type="file"> (< 5 Mo )<br /><hr /><br />
                    ' . $hmtlCvp . '
					CV Groupe Proservia : 
                    <input name="cvp" type="file"> (< 5 Mo )<br /><hr /><br />
				    Commentaire : <br />
                    <textarea name="commentaire">' . $this->commentaire . '</textarea><br/><br/><br/><br/>
                    <div id="historique_action">' . $htmlHAction . '</div><br />
					<div id="historique_positionnement">' . $htmlPositionnement . '</div>
				</div>
				<div class="clearer"><br /><hr /></div>
                                ';
            $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight($this->staff, $this->Id_etat))
                $html .= $ressource->hiringForm() . '<div class="clearer"><br /><hr /><br /></div>';
            $html .= '
			    <div class="submit">
				    <input type="hidden" name="class" value="' . __CLASS__ . '" />
	                <input type="submit" value="' . SAVE_BUTTON . '" onclick="noPrompt();return verifForm(this.form)" />
		        </div>
		    </form>';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature) . ', Id_ressource = ' . mysql_real_escape_string((int) $this->Id_ressource) . ',
		date = "' . mysql_real_escape_string(DateMysqltoFr($this->date, 'mysql')) . '", Id_nature = ' . mysql_real_escape_string((int) $this->Id_nature) . ', Id_etat = ' . mysql_real_escape_string((int) $this->Id_etat) . ',
		type_validation="' . mysql_real_escape_string($this->type_validation) . '", type_embauche="' . mysql_real_escape_string($this->type_embauche) . '", lien_cv = "' . mysql_real_escape_string($this->lien_cv) . '",
		lien_cvp = "' . mysql_real_escape_string($this->lien_cvp) . '", lien_lm = "' . mysql_real_escape_string($this->lien_lm) . '", commentaire = "' . mysql_real_escape_string($this->commentaire) . '",
		archive = ' . mysql_real_escape_string((int) $this->archive) . ', Id_cvweb = ' . mysql_real_escape_string((int) $this->Id_cvweb) . ',
		date_reponse="' . mysql_real_escape_string($this->date_reponse = DateMysqltoFr($this->date_reponse, 'mysql')) . '",
		Id_action_mener=' . mysql_real_escape_string((int) $this->Id_action_mener) . ', staff=' . mysql_real_escape_string((int) $this->staff) . '';
        if ($this->Id_candidature) {
            $db->query('DELETE FROM candidat_agence WHERE Id_candidat = ' . mysql_real_escape_string((int) $this->Id_candidature));
            $db->query('DELETE FROM candidat_typecontrat WHERE Id_candidat = ' . mysql_real_escape_string((int) $this->Id_candidature));
            $requete = 'UPDATE candidature ' . $set . ' WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature);
            $_SESSION['candidature'] = $this->Id_candidature;
            if ($this->createur == 'cvweb') {
                $db->query('UPDATE candidature SET createur = "' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '" WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
            }
        } else {
            $requete = 'INSERT INTO candidature ' . $set . ' , createur = "' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '"';
        }
        $db->query($requete);
        if ($this->Id_candidature == '') {
            $this->Id_candidature = mysql_insert_id();
            $_SESSION['candidature'] = $this->Id_candidature;
            self::updateEtat($this->Id_etat, mysql_insert_id());
            if ($this->Id_action_mener) {
                self::updateAction($this->Id_action_mener, $this->Id_candidature);
            }
        }
        //Enregistrement des agences souhaitées
        $db->query('INSERT INTO candidat_agence SET Id_candidat = "' . mysql_real_escape_string((int) $this->Id_candidature) . '", Id_agence = "' . mysql_real_escape_string($this->agence_souhaitee) . '"');

        //Enregistrement des agences souhaitées
        if (!empty($this->type_contrat)) {
            foreach ($this->type_contrat as $i) {
                $db->query('INSERT INTO candidat_typecontrat SET Id_candidat = "' . mysql_real_escape_string((int) $this->Id_candidature) . '", Id_type_contrat = "' . mysql_real_escape_string($i) . '"');
            }
        }
    }

    /**
     * Recherche d'une candidature
     *
     * @return string
     */
    public static function search($createur, $commercial, $Id_etat, $Id_nature, $nom_candidat, $Id_preavis, $Id_profil, $pretention_basse, $pretention_haute, $type_date, $debut, $fin, $cp, $type_contrat = array(), $Id_cursus, $Id_candidature, $tv, $te, $motcle, $embauche, $Id_action_mener, $Id_specialite = array(), $mobilite, $agence_souhaitee = array(), $createurEtat, $Id_exp_info, $th, $output = array('type' => 'TABLE')) {
        $arguments = array('createur', 'commercial', 'Id_etat', 'Id_nature', 'nom_candidat', 'Id_preavis', 'Id_profil', 'pretention_basse', 'pretention_haute', 'type_date', 'debut', 'fin', 'cp', 'type_contrat', 'Id_cursus', 'Id_candidature', 'tv', 'te', 'motcle', 'embauche', 'Id_action_mener', 'Id_specialite', 'mobilite', 'agence_souhaitee', 'createurEtat', 'Id_exp_info', 'th', 'output');
        $columns = array(array('Id','Id_candidature'), array('Nom / Prénom','nom'), array('Tél','tel_portable'),
                         array('Nature','none'),array('Etat','none'),array('Date','date'),
                         array('Profil','none'));
        $db = connecter();
        if ($Id_preavis || $commercial || $pretention_basse || $pretention_haute || $cp || $motcle || $mobilite) {
            $j1 = 'INNER JOIN entretien ON candidature.Id_candidature=entretien.Id_candidature';
        }
        if ($mobilite) {
            $j2 = 'INNER JOIN entretien_mobilite ON entretien_mobilite.Id_entretien=entretien.Id_entretien';
        }
        if ((int) $type_date) {
            $j3 = 'INNER JOIN historique_candidature ON candidature.Id_candidature=historique_candidature.Id_candidature';
        }
        $requete = 'SELECT DISTINCT candidature.Id_candidature, candidature.Id_ressource, candidature.Id_etat,Id_nature,
				    DATE_FORMAT(candidature.date, "%d-%m-%Y") AS date_m,tel_portable,lien_cv, lien_lm, lien_cvp, ressource.nom, 
				    ressource.prenom, ressource.code_postal, staff 
				    FROM candidature ' . $j1 . ' ' . $j2 . ' ' . $j3 . ' ' . $j4 . '
				    INNER JOIN ressource ON candidature.Id_ressource=ressource.Id_ressource 
				    WHERE candidature.archive=0';

        if ($createur) {
            $requete .= ' AND candidature.createur ="' . $createur . '"';
        }
        if ($commercial) {
            $requete .= ' AND Id_commercial ="' . $commercial . '"';
        }
        if ($Id_etat) {
            $requete .= ' AND candidature.Id_etat =' . (int) $Id_etat;
        }
        if ($Id_nature) {
            $requete .= ' AND Id_nature =' . (int) $Id_nature;
        }
        if ($nom_candidat) {
            $nom_candidat = str_replace('"', '', $nom_candidat);
            $requete .= ' AND nom LIKE "%' . $nom_candidat . '%"';
        }
        if ($Id_preavis) {
            $requete .= ' AND Id_preavis =' . (int) $Id_preavis;
        }
        if ($Id_exp_info) {
            $requete .= ' AND Id_exp_info =' . (int) $Id_exp_info;
        }
        if ($Id_profil) {
            $requete .= ' AND Id_profil =' . (int) $Id_profil;
        }
        if ($pretention_basse && $pretention_haute) {
            $requete .= ' AND (((' . (float) $pretention_basse . ' BETWEEN pretention_basse AND pretention_haute) OR (' . (float) $pretention_haute . ' BETWEEN pretention_basse AND pretention_haute))
						  OR ((' . (float) $pretention_basse . ' <= pretention_basse AND ' . (float) $pretention_haute . ' >= pretention_haute) AND (pretention_basse < pretention_haute)))';
        } elseif ($pretention_basse || $pretention_haute) {
            if ($pretention_basse) {
                $requete .= ' AND ' . (float) $pretention_basse . ' BETWEEN pretention_basse AND pretention_haute';
            }
            if ($pretention_haute) {
                $requete .= ' AND ' . (float) $pretention_haute . ' BETWEEN pretention_basse AND pretention_haute';
            }
        }
        if ($type_date == 'creation') {
            if ($debut && $fin) {
                $requete .= ' AND candidature.date BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
            }
        } else if ((int) $type_date) {
            $createurEtat = str_replace('\\', '', $createurEtat);
            $requete .= ' AND historique_candidature.Id_etat=' . (int) $type_date . '
                    AND historique_candidature.Id_utilisateur IN ("' . $createurEtat . '")';
            if ($debut && $fin) {
                $requete .= ' AND DATE_FORMAT(historique_candidature.date, "%Y-%m-%d") BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '"
                        AND "' . DateMysqltoFr($fin, 'mysql') . '"';
            }
        }
        if ($cp) {
            $requete .= ' AND code_postal LIKE "' . $cp . '%"';
        }
        if ($Id_candidature) {
            $requete .= ' AND candidature.Id_candidature =' . (int) $Id_candidature;
        }
        if ($Id_cursus) {
            $requete .= ' AND Id_cursus=' . (int) $Id_cursus;
        }
        if ($tv) {
            $requete .= ' AND type_validation="' . $tv . '"';
        }
        if ($te) {
            $requete .= ' AND type_embauche="' . $te . '"';
        }
        if ($motcle) {
            $motclesplit = explode(' ', utf8_encode($motcle));
            foreach ($motclesplit as $i) {
                $requete .= ' AND ( mot_cle LIKE "%' . $i . '%"
				OR attente_pro LIKE "%' . $i . '%"
				OR candidature.commentaire LIKE "%' . $i . '%")';
            }
        }
        if ($Id_action_mener) {
            $requete .= ' AND Id_action_mener =' . (int) $Id_action_mener;
        }
        if ($embauche) {
            $requete .= ' AND candidature.Id_etat IN (8,9)';
        }
        if ($th) {
            $requete .= ' AND ressource.th=1';
        }
        if ($mobilite) {
            $res = @explode(',', $mobilite);
            $p = 0;
            $nb_pdt = count($res);
            while ($p < $nb_pdt) {
                $Id_mob_ville = '-' . $db->query('SELECT Id_ville FROM ville WHERE LEFT(Id_departement,2) = "' . mysql_real_escape_string($res[$p]) . '"')->fetchRow()->id_ville;
                $Id_mob_region = '1-' . $db->query('SELECT Id_region FROM departement WHERE LEFT(Id_departement,2) = "' . mysql_real_escape_string($res[$p]) . '"')->fetchRow()->id_region;
                $Id_mob_dpt = $Id_mob_region . '-' . $res[$p];
                $requete .= ' AND (Id_mobilite="1" OR Id_mobilite = "' . mysql_real_escape_string($Id_mob_ville) . '" OR Id_mobilite="' . mysql_real_escape_string($Id_mob_region) . '" OR Id_mobilite="' . mysql_real_escape_string($Id_mob_dpt) . '")';
                ++$p;
            }
        }
        if ($agence_souhaitee[0]) {
            foreach ($agence_souhaitee as $i) {
                $requete .= ' AND candidature.Id_candidature IN
				(SELECT Id_candidat FROM candidat_agence WHERE Id_agence = "' . mysql_real_escape_string($i) . '")';
            }
        }
        if ($type_contrat[0]) {
            foreach ($type_contrat as $i) {
                $requete .= ' AND candidature.Id_candidature IN
				(SELECT Id_candidat FROM candidat_typecontrat WHERE Id_type_contrat = "' . mysql_real_escape_string($i) . '")';
            }
        }
        if ($Id_specialite[0]) {
            foreach ($Id_specialite as $i) {
                $requete .= ' AND candidature.Id_ressource IN
				(SELECT Id_ressource FROM ressource_specialite WHERE Id_specialite = "' . mysql_real_escape_string($i) . '")';
            }
        }

        $params = '';
        foreach (func_get_args() as $key => $value) {
            if($arguments[$key] == 'agence_souhaitee' || $arguments[$key] == 'type_contrat' || $arguments[$key] == 'Id_specialite') {
                $value = implode(";", $value);
            }
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        }
        else {
            $paramsOrder .= 'orderBy=nom';
            $orderBy = 'nom';
        }
        if($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=ASC';
            $direction = 'ASC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherCandidature({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterCandidature&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $direction = 'DESC';
                        $img['nom'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherCandidature({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
                foreach ($paged_data['data'] as $ligne) {
                    if ($utilisateur->getResourceRight($ligne['staff'], $ligne['id_etat'])) {
                        if ($ligne['id_etat'] == 1) {
                            $dpt = substr($ligne['code_postal'], 0, 2);
                            $j = self::lineColorPerDepartment($dpt);
                            $ligne['color'] = $j;
                        }
                    }
                    else {
                        continue;
                    }
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . self::showId($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showName($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showPhone($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showNature($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showState($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showProfile($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtons($ligne, array('csv' => false)) . '</td>
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
            header('Content-Disposition: attachment; filename="candidature.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo self::showId($ligne, array('csv' => true)) . ';';
                echo '"' . self::showName($ligne, array('csv' => true)) . '";';
                echo '"' . self::showPhone($ligne, array('csv' => true)) . '";';
                echo '"' . self::showNature($ligne, array('csv' => true)) . '";';
                echo '"' . self::showState($ligne, array('csv' => true)) . '";';
                echo '"' . self::showDate($ligne, array('csv' => true)) . '";';
                echo '"' . self::showProfile($ligne, array('csv' => true)) . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Consultation de la fiche candidat
     *
     * @return string
     */
    public function consultation() {
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, array());
        if (!empty($this->type_contrat)) {
            foreach ($this->type_contrat as $i) {
                $htmlTypeContrat .= self::getSearchContractTypeLibelle($i) . '<br />';
            }
        }
        $lien_cv = $lien_cvp = $lien_lm = '';
        if ($this->lien_cv) {
            $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cv . "'><img src=" . IMG_CV . "></a></td>";
        }
        if ($this->lien_cvp) {
            $lien_cvp = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cvp . "'><img src=" . IMG_CVP . "></a></td>";
        }
        if ($this->lien_lm) {
            $lien_lm = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $this->lien_lm . "'><img src=" . IMG_LM . "></a></td>";
        }
        if ($this->Id_entretien) {
            $entretien = new Entretien($this->Id_entretien, array());
            $htmlEntretien = $entretien->consultation();
        }
        $html = '
		    <h2>CANDIDATURE ' . $this->Id_candidature . ' : ' . $ressource->prenom . ' ' . $ressource->nom . '</h2>
			<a href="../membre/index.php?a=creer_entretien&amp;Id_candidature=' . $this->Id_candidature . '"><img src="' . IMG_ENTRETIEN . '"></a>
			<a href="../membre/index.php?a=modifier&amp;Id=' . $this->Id_candidature . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a>
			<a onclick="ouvre_popup2(\'../membre/index.php?a=editerCandidat&Id_candidature=' . $this->Id_candidature . '\', \'editionCandidat\')"><img src="' . IMG_PDF . '"></a>
			' . $lien_cv . ' ' . $lien_cvp . ' ' . $lien_lm . '
			<div class="left">
			    Type de contrats recherchés : 
				' . $htmlTypeContrat . '<br /><br />
				Date de saisie de la candidature : ' . FormatageDate($this->date) . '<br /><br />
				Nature : ' . $this->getNature() . '<br /><br />
				Etat : ' . $this->getStatus($this->Id_etat) . '<br /><br />
				Action menée : ' . self::getLibelleActionMener($this->Id_action_mener) . '<br /><br />
		        Créateur : ' . $this->createur . '<br /><br />
			</div>
			<div class="right">
				  Commentaire : ' . $this->commentaire . '<br /><br />
				  Agence de rattachement : 
				  ';
        if (!empty($this->agence_souhaitee)) {
            foreach ($this->agence_souhaitee as $i) {
                $html .= Agence::getRHLibelle($i) . '<br/><span class="marge"></span><span class="marge"></span><span class="marge"></span><span class="marge"></span><span class="marge"></span>';
            }
        }
        $html .= '<br/><br/></div>
			<div class="clearer"></div><br /><hr /><br />
			' . $this->history(0) . '
			<div class="clearer"></div><hr /><br />
			' . $this->actionHistory(0) . '
			<div class="clearer"></div><hr /><br />
			' . $ressource->getPositioning() . '
			<div class="clearer"></div><hr />
			' . $ressource->consultation() . '
			<div class="clearer"></div><hr />
			' . $htmlEntretien . '
';
        return $html;
    }

    /**
     * Affichage du formulaire type de validation de la candidature
     *
     * @return string
     */
    public function typeValidation() {
        if(!$this->type_validation)
            $this->type_validation = 'mission';
        $tp[$this->type_validation] = 'checked="checked"';
        $html = '
		Validé sur :
		Profil <input type="radio" name="type_validation" ' . $tp['profil'] . ' value="profil" />
		Mission <input type="radio" name="type_validation" ' . $tp['mission'] . ' value="mission" /><br /><br />
';
        return $html;
    }

    /**
     * Affichage du formulaire type d'embauche du candidat
     *
     * @return string
     */
    public function typeEmbauche() {
        $tp[$this->type_embauche] = 'checked="checked"';
        $html = '
		<span class="infoFormulaire"> * </span>Embauché sur :
		Profil <input type="radio" name="type_embauche_etat" ' . $tp['profil'] . ' value="profil" />
		Mission <input type="radio" name="type_embauche_etat" ' . $tp['mission'] . ' value="mission" /><br /><br />
';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les actions menées
     *
     * @return string
     */
    public function getActionMener($Id_action_mener=0) {
        if ($Id_action_mener) {
            $cd[$Id_action_mener] = 'selected="selected"';
        } else {
            $cd[$this->Id_action_mener] = 'selected="selected"';
        }
        $db = connecter();
        $result = $db->query('SELECT Id_action_mener, libelle FROM action_mener ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_action_mener . ' ' . $cd[$ligne->id_action_mener] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'action ménée
     *
     * @param int Identifiant de l'action menée
     *
     * @return string
     */
    public static function getLibelleActionMener($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM action_mener WHERE Id_action_mener=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage de l'historique des etats de la candidature
     *
     * @param int
     *
     * @return string
     */
    public function history($i) {
        $db = connecter();
        $result = $db->query('SELECT Id_etat, DATE_FORMAT(date, "%d-%m-%Y") as date_h, date, Id_utilisateur
		FROM historique_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature) . ' ORDER BY date DESC');
        $html = '<h2>HISTORIQUE</h2><table class="sortable"><tr><th>Etat</th><th>Date (jj-mm-aaaa)</th><th>Réalisé par</th></tr>';
        $j = 0;
        while ($ligne = $result->fetchRow()) {
            if ($i) {
                $htmlSupp = '<td width="5%" height="20"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { supprimerHc(' . $this->Id_candidature . ',' . $ligne->id_etat . ',\'' . $ligne->date . '\') }" /></td>';
                $htmlValid = '<td width="5%" height="20"><input type="button" class="boutonValider" onclick="if (confirm(\'Valider la ligne ?\')) { validerHc(' . $this->Id_candidature . ',' . $ligne->id_etat . ',' . $j . '); supprimerHc(' . $this->Id_candidature . ',' . $ligne->id_etat . ',\'' . $ligne->date . '\') }" /></td>';
                $htmlInput = '<td width="30%" height="20"><input type="text"  onfocus="showCalendarControl(this)" id="date' . $j . '" value="' . $ligne->date_h . '" size="8" /></td>';
                $utilisateur = new Utilisateur($ligne->id_utilisateur, array());
                $htmlUser = '
                    <select id="Id_utilisateur' . $j . '">
                        <option value="">' . RECRUITER_SELECT . '</option>
                        <option value="">-------------------------</option>
                        ' . $utilisateur->getList('RH') . '
                        <option value="">-------------------------</option>
                        <option value="">' . MARKETING_PERSON_SELECT . '</option>
                        <option value="">-------------------------</option>
                        ' . $utilisateur->getList('COM') . '
                        <option value="">----------------------------</option>
                        ' . $utilisateur->getList('OP') . '
                </select>';
            } else {
                $htmlInput = '<td width="40%" height="20">' . $ligne->date_h . '</td>';
                $htmlUser = Utilisateur::getName($ligne->id_utilisateur);
            }
            $html .= '<tr>
			<td width="30%" height="20">' . self::getStatus($ligne->id_etat) . '</td>
			' . $htmlInput . '
			<td width="30%" height="20">' . $htmlUser . '</td>
			' . $htmlSupp . '
			' . $htmlValid . '
			</tr>';
            ++$j;
        }
        $html .= '</table><br /><br />';
        return $html;
    }

    /**
     * Affichage de l'historique des etats de la candidature
     *
     * @return string
     */
    public static function deleteHistory($Id_candidature, $Id_etat, $date) {
        $db = connecter();
        $db->query('DELETE FROM historique_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . '
		AND Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ' AND date="' . mysql_real_escape_string($date) . '"');
        $db->query('UPDATE candidature SET Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ' WHERE Id_candidature = ' . mysql_real_escape_string((int) $Id_candidature));
    }

    /**
     * Affichage de l'historique des etats de la candidature
     *
     * @param int Identifiant de la candidature
     * @param int Identifiant de l'état de la candidature
     * @param date date correspondant à l'état
     *
     * @return string
     */
    public static function validateHistory($Id_candidature, $Id_etat, $date, $Id_utilisateur) {
        $db = connecter();
        $hms = $db->query('SELECT DATE_FORMAT(now(),"%H:%i:%s") as hms')->fetchRow()->hms;
        $db->query('INSERT INTO historique_candidature SET Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . ', Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ',
		date="' . mysql_real_escape_string(DateMysqltoFr($date, 'mysql')) . ' ' . mysql_real_escape_string($hms) . '", Id_utilisateur="' . mysql_real_escape_string($Id_utilisateur) . '"');
        $db->query('UPDATE candidature SET Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ' WHERE Id_candidature = ' . mysql_real_escape_string((int) $Id_candidature));
    }

    /**
     * Archivage d'une candidature
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE candidature SET archive="1" WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
    }

    /**
     * Desarchivage d'une candidature
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE candidature SET archive="0" WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
    }

    /**
     * Suppression d'une candidature
     */
    public function delete() {
        $db = connecter();
        if ($this->lien_cv) {
            if (is_file(CV_DIR . $this->lien_cv)) {
                unlink(CV_DIR . $this->lien_cv);
            }
        }
        if ($this->lien_cvp) {
            if (is_file(CV_DIR . $this->lien_cvp)) {
                unlink(CV_DIR . $this->lien_cvp);
            }
        }
        if ($this->lien_lm) {
            if (is_file(LM_DIR . $this->lien_lm)) {
                unlink(LM_DIR . $this->lien_lm);
            }
        }
        $ligne = $db->query('SELECT Id_ressource FROM candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature . ''))->fetchRow();
        $ressource = RessourceFactory::create('CAN_AGC', $ligne->id_ressource, array());
        $ressource->delete();

        $Idcvweb = $this->isCvweb();
        if ($Idcvweb) {
            $db->query('DELETE FROM candidat_annonce WHERE Id_candidat =' . mysql_real_escape_string((int) $Idcvweb . ''));
        }
        $db->query('DELETE FROM candidature WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
        $db->query('DELETE FROM candidat_typecontrat WHERE Id_candidat = ' . mysql_real_escape_string((int) $this->Id_candidature));
        $db->query('DELETE FROM historique_candidature WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
        $db->query('DELETE FROM historique_action_candidature WHERE Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature));
        $db->query('DELETE FROM candidat_agence WHERE Id_candidat = ' . mysql_real_escape_string((int) $this->Id_candidature));
        $result = $db->query('SELECT Id_entretien FROM entretien WHERE Id_candidature= ' . mysql_real_escape_string((int) $this->Id_candidature));
        while ($ligne = $result->fetchRow()) {
            $entretien = new Entretien($ligne->id_entretien, array());
            $entretien->delete();
        }
    }

    /**
     * Mise à jour de l'état de la candidature d'une ressource
     *
     * @param int Identifiant de l'état de la candidature
     * @param int Identifiant de la candidature à mettre à jour
     */
    public static function updateEtat($Id_etat, $Id_candidature) {
        $db = connecter();
        $db->query('INSERT INTO historique_candidature SET Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . ',
		date="' . mysql_real_escape_string(DATETIME) . '", Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ', Id_utilisateur="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
        $db->query('UPDATE candidature SET Id_etat=' . mysql_real_escape_string((int) $Id_etat) . ' WHERE Id_candidature = ' . mysql_real_escape_string((int) $Id_candidature));
    }

    /**
     * Affichage d'une select box contenant les natures des candidatures
     *
     * @return string
     */
    public function getNatureListe($Id_nature = 0) {
        if ($Id_nature) {
            $candidature[$Id_nature] = 'selected="selected"';
        } else {
            $candidature[$this->Id_nature] = 'selected="selected"';
        }
        $db = connecter();
        $result = $db->query('SELECT Id_nature_candidature, libelle FROM nature_candidature ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_nature_candidature . ' ' . $candidature[$ligne->id_nature_candidature] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les etats des candidatures
     *
     * @return string
     */
    public function getEtatListe($Id_etat = 0) {
        if ($Id_etat) {
            $candidature[$Id_etat] = 'selected="selected"';
        } else {
            $candidature[$this->Id_etat] = 'selected="selected"';
        }
        $db = connecter();
        $result = $db->query('SELECT Id_etat_candidature, libelle FROM etat_candidature');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_etat_candidature . ' ' . $candidature[$ligne->id_etat_candidature] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du nom de l'etat de la candidature
     *
     * @param int Identifiant de l'etat de la candidature
     *
     * @return string
     */
    public static function getStatus($Id_etat) {
        $db = connecter();
        return $db->query('SELECT libelle FROM etat_candidature WHERE Id_etat_candidature=' . mysql_real_escape_string((int) $Id_etat))->fetchRow()->libelle;
    }

    /**
     * Affichage du nom de la nature de la candidature
     *
     * @return string
     */
    public function getNature() {
        $db = connecter();
        return $db->query('SELECT libelle FROM nature_candidature WHERE Id_nature_candidature=' . mysql_real_escape_string((int) $this->Id_nature))->fetchRow()->libelle;
    }

    /**
     * Fonction qui vérifie s'il y a une homonyme de la candidature
     *
     * @param string prenom de la personne
     * @param string nom de la personne
     *
     * @return bool
     */
    public static function homonym($prenom, $nom) {
        $db = connecter();
        return $db->query('SELECT count(Id_candidature) as nb FROM candidature
		INNER JOIN ressource ON ressource.Id_ressource=candidature.Id_ressource 
		WHERE prenom="' . mysql_real_escape_string(htmlscperso(stripslashes(formatPrenom(withoutAccent(str_replace("'", "", utf8_decode($prenom))))), ENT_QUOTES)) . '"
		AND nom="' . mysql_real_escape_string(htmlscperso(stripslashes(strtoupper(withoutAccent(str_replace("'", "", utf8_decode($nom))))), ENT_QUOTES)) . '"')->fetchRow()->nb;
    }

    /**
     * Fonction qui affiche les modifications réalisées par un candidat sur cvweb
     *
     * @param int Identifiant de la candidature
     *
     * @return string
     */
    public static function cvWebHistoryModification($Id_candidature) {
        $db = connecter();
        $requete = 'SELECT candidature.Id_candidature,lien_cv,lien_cvp,lien_lm,candidature.Id_ressource, modif_candidat.date, remarque FROM modif_candidat INNER JOIN candidature ON candidature.Id_candidature=modif_candidat.Id_candidature';
        if ($Id_candidature) {
            $requete .= ' WHERE candidature.Id_candidature = ' . mysql_real_escape_string((int) $Id_candidature);
        }
        $requete .= ' ORDER BY modif_candidat.date DESC';

        $result = $db->query($requete);
        $pager_options = array('mode' => 'Sliding', 'perPage' => 10, 'delta' => 2);
        $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_options); //show the results

        if (!$paged_data['totalItems']) {
            $html = 'Aucun résultat n\'a été trouvé pour votre requête';
        } else {
            $html .= '
		        <p class="pagination">' . $paged_data['links'] . '</p>
 		        <table class="sortable">
		            <tr>
			            <th>Id</th>
						<th>Date </th>
						<th>Nom / Prénom</th>
						<th>Mise à jour effectuée</th>
		            </tr>
';
            foreach ($paged_data['data'] as $ligne) {
                $ressource = RessourceFactory::create('CAN_AGC', $ligne['id_ressource'], null);
                $lien_cv = $lien_cvp = $lien_lm = '';
                if ($ligne['lien_cv']) {
                    $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $ligne['lien_cv'] . "'><img src=" . IMG_CV . "></a></td>";
                }
                if ($ligne['lien_cvp']) {
                    $lien_cvp = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $ligne['lien_cvp'] . "'><img src=" . IMG_CVP . "></a></td>";
                }
                if ($ligne['lien_lm']) {
                    $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $ligne['lien_lm'] . "'><img src=" . IMG_LM . "></a></td>";
                }
                $htmlAdmin = '
			        <td><a href="../membre/index.php?a=modifier&amp;Id=' . $ligne['id_candidature'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
				    <td><a href="../membre/index.php?a=creer_entretien&amp;Id_candidature=' . $ligne['id_candidature'] . '"><img src="' . IMG_ENTRETIEN . '"></a></td>
';
                $html .= '
                    <tr class="row' . $j . '">
						<td>' . $ligne['id_candidature'] . '</td>
						<td>' . date("d/m/Y", strtotime($ligne['date'])) . '</td>
						<td>' . $ressource->getName() . '</td>
						<td><table>' . $ligne['remarque'] . '</table></td>
			            <td><a href="../membre/index.php?a=afficherCandidature&amp;Id_candidature=' . $ligne['id_candidature'] . '"><img src="' . IMG_CONSULT . '"></a></td>
						' . $lien_cv . '
						' . $lien_cvp . '
						' . $lien_lm . '
						' . $htmlAdmin . '
                    </tr>
';
            }
            $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
        }
        return $html;
    }

    /**
     * Indique si le candidat est originaire de cvweb ou non
     *
     * @return bool
     */
    public function isCvweb() {
        $db = connecter();
        return $db->query('SELECT Id_cvweb FROM candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature))->fetchRow()->id_cvweb;
    }

    /**
     * Affichage du formulaire de recherche d'une candidature
     *
     * @return string
     */
    public static function searchForm() {
        $candidature = new Candidature('', array());
        $createur = new Utilisateur($_SESSION['filtre']['createur'], array());
        $createurEtat = new Utilisateur($_SESSION['filtre']['createurEtat'], array());
        $commercial = new Utilisateur($_SESSION['filtre']['commercial'], array());
        $profil = new Profil($_SESSION['filtre']['Id_profil'], array());
        $cursus = new Cursus($_SESSION['filtre']['Id_cursus'], array());
        $preavis = new Preavis($_SESSION['filtre']['Id_preavis'], array());
        $expinfo = new Experience($_SESSION['filtre']['Id_exp_info'], array());
        $motcle = $_SESSION['filtre']['motcle'];
        $tv[$_SESSION['filtre']['tv']] = 'selected="selected"';
        $type_date[$_SESSION['filtre']['type_date']] = 'selected="selected"';
        if ($_SESSION['filtre']['th'] == 1) {
            $thFilter = 'checked="checked"';
        } else {
            $thFilter = '';
        }
        if ($_SESSION['filtre']['embauche'] == 1) {
            $embaucheFilter = 'checked="checked"';
        } else {
            $embaucheFilter = '';
        }
        $html = '
		<div id="candidatForm">
			<form method="post">
			<table><tr><td colspan="2">
                        Id :
			<input id="Id_candidature" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['Id_candidature'] . '" size="2"/>
			Nom : 
			<input id="nom_candidat" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['nom_candidat'] . '" size="25" />
			<select id="createur" onchange="afficherCandidature()">
			    <option value="">Par créateur</option>
				<option value="">----------------------------</option>
				' . $createur->getList("RH") . '
			</select>
			<select id="commercial" onchange="afficherCandidature()">
				<option value="">Par commercial</option>
				<option value="">----------------------------</option>
				' . $commercial->getList("COM") . '
				<option value="">----------------------------</option>
				' . $commercial->getList("OP") . '
			</select>
			<select id="Id_etat" onchange="afficherCandidature()">
				<option value="">Par état</option>
				<option value="">----------------------------</option>
				' . $candidature->getEtatListe($_SESSION['filtre']['Id_etat']) . '
			</select>
			<select id="Id_action_mener" onchange="afficherCandidature()">
				<option value="">Par action</option>
				<option value="">----------------------------</option>
				' . $candidature->getActionMener($_SESSION['filtre']['Id_action_mener']) . '
			</select>
			<select id="Id_preavis" onchange="afficherCandidature()">
			    <option value="">Par préavis</option>
				<option value="">----------------------------</option>
				' . $preavis->getList() . '
			</select>
			<select id="Id_exp_info" onchange="afficherCandidature()">
			    <option value="">Par expérience</option>
				<option value="">----------------------------</option>
				' . $expinfo->getList() . '
			</select>
			Embauché <input id="embauche" type="checkbox" "' . $embaucheFilter . '" onclick="afficherCandidature()"/>
			</td></tr><tr><td colspan="2">
			<select id="Id_nature" onchange="afficherCandidature()">
				<option value="">Par nature</option>
				<option value="">----------------------------</option>
				' . $candidature->getNatureListe($_SESSION['filtre']['Id_nature']) . '
			</select>
			<select id="Id_cursus" onchange="afficherCandidature()">
				<option value="">Par cursus</option>
				<option value="">----------------------------</option>
			    ' . $cursus->getList() . '
			</select>
			<select id="Id_profil" onchange="afficherCandidature()">
			    <option value="">Par profil</option>
				<option value="">----------------------------</option>
				' . $profil->getList() . '
			</select>
			Date 
			<select id="type_date" onchange="afficherCandidature()">
				<option value="creation" ' . $type_date['creation'] . '>Création candidature</option>
			    ' . $candidature->getEtatListe($_SESSION['filtre']['type_date']) . '
			</select>
			&nbsp;
			<select id="createurEtat" onchange="afficherCandidature()" multiple>
			    <option value="">Par</option>
				<option value="">----------------------------</option>
				' . $createurEtat->getList("RH") . '
				<option value="">----------------------------</option>
				' . $createurEtat->getList("COM") . '
			</select>
			&nbsp;
			du <input id="debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['debut'] . '" size="8" />
			&nbsp;
			au <input id="fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['fin'] . '" size="8" />
			<select id="tv" onchange="afficherCandidature()">
				<option value="">Par Type de validation</option>
				<option value="">----------------------------</option>
				<option value="profil" ' . $tv['profil'] . '>Profil</option>
				<option value="mission" ' . $tv['mission'] . '>Mission</option>
			</select>
			&nbsp;
			TH <input id="th" type="checkbox" "' . $thFilter . '" onclick="afficherCandidature()"/>
			</td></tr><tr><td></td></tr><tr><td>
			Prétention salariale > 
			<input id="pretention_basse" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['pretention_basse'] . '" size="3" /> K&euro;
			< <input id="pretention_haute" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['pretention_haute'] . '" size="4" /> K&euro;
			Dépt d\'habitation 
			<input id="cp" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['cp'] . '" size="3" />
			Mobilité : <input id="mobilite" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['mobilite'] . '" size="10" />
                        Mots clés : <input id="motcle" onmouseover="return overlib(\'<div class=commentaire>« Un espace correspond à ET. ex : unix solaris »</div>\', FULLHTML);" onmouseout="return nd();" type="text" onkeyup="afficherCandidature()" value="' . $_SESSION['filtre']['motcle'] . '" />
                    <br />
		    </td><td rowspan="2"><select id="Id_specialite" name="Id_specialite[]" onchange="afficherCandidature()" multiple size="4">
				<option value="">Par spécialité</option>
				<option value="">----------------------------</option>
				' . Specialite::getSelectList($_SESSION['filtre']['Id_specialite']) . '
			</select>
	        <select id="agence" name="agence[]" onchange="afficherCandidature()" multiple size="4">
				<option value="">Par agence de rattachement</option>
				<option value="">----------------------------</option>
				' . Agence::getSelectList($_SESSION['filtre']['agence_souhaitee']) . '
			</select>
		    <select id="type_contrat" name="type_contrat[]" onchange="afficherCandidature()" multiple size="4">
			    <option value="">Par type de contrat</option>
				<option value="">----------------------------</option>
				' . self::getSelectTypeContratListe($_SESSION['filtre']['type_contrat']) . '
			</select>
			</td></tr><tr><td>
			<input type="button" onclick="afficherCandidature()" value="Go !">
			<input type="reset" onclick="initForm(\'Candidature\')" value="Refresh">
                        </td></tr></table>
			</form>
		</div>
                <script type="text/javascript">MultiSelect.makeDropdown("createurEtat",{height: 200, arrowSrc: "../ui/images/listbox_arrow.jpg"});</script>';
        return $html;
    }

    /**
     * Affichage du formulaire de recherche d'un cv
     *
     * @return string
     */
    public static function cvSearchForm() {
        $html = '
        <form action="../membre/index.php" method="GET">
        <input type="hidden" name="a" value="rechercheCV" />
		Mot recherché : 
		<input type="text" name="mot" value="' . $_SESSION['mot'] . '" />
        &nbsp;&nbsp;
        <input type="submit" value="Go!" />
        </form>
';
        return $html;
    }

    /**
     * Affichage des postulations provenant de cvweb
     *
     * @param int Identifiant du candidat cvweb
     * @param int nb de resultat par page
     *
     * @return Chaine HTML générée pour afficher la liste des annonces
     */
    public static function postulation($Id_cvweb) {
        $requete = 'SELECT candidat_annonce.Id_annonce, candidature.Id_candidature, DATE_FORMAT(candidat_annonce.date, "%d-%m-%Y") as date,
                    texte_annonce,localisation, Id_ressource, gc.city,gc.zip,
                    CONCAT(reference_recruteur, " ", reference_commercial, " ", annonce.Id_annonce) AS reference
                    FROM candidat_annonce
                    INNER JOIN candidature ON candidature.Id_cvweb=candidat_annonce.Id_candidat
                    INNER JOIN annonce ON candidat_annonce.Id_annonce=annonce.Id_annonce
                    LEFT JOIN geography_city gc ON gc.id_geography_city = annonce.localisation
                    WHERE Id_cvweb !=""';
        if ($Id_cvweb) {
            $requete .= ' AND Id_candidat=' . (int) $Id_cvweb;
        }
        $requete .= ' ORDER BY candidat_annonce.date DESC';
        if ($nb) {
            $requete .= ' LIMIT 0,' . $nb;
        }
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));

        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            $html = '
		        <p class="pagination">' . $paged_data['links'] . '</p>
 		        <table class="sortable">
		            <tr>
			            <th>Date Postulation</th>
						<th>Nom / Prénom</th>
						<th>Réf Annonce</th>
						<th>Lieu</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $ressource = RessourceFactory::create('CAN_AGC', $ligne['id_ressource'], null);
                $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($ligne['texte_annonce'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                $html .= '
					<tr ' . $info . '>
						<td>' . $ligne['date'] . '</td>
						<td><a href="../membre/index.php?a=afficherCandidature&amp;Id_candidature=' . $ligne['id_candidature'] . '" >' . $ressource->getName() . '</a></td>
						<td><a href="../rh/index.php?a=modification_annonce&amp;Id=' . $ligne['id_annonce'] . '">' . $ligne['reference'] . '</a></td>
						<td>' . $ligne['city'] . ' (' . $ligne['zip'] . ')' . '</td>
					</tr>
					';
            }
            $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
        }
        return $html;
    }

    /**
     * Affichage des candidats dont le CV contient le mot passé en paramètre
     *
     * @param string mot recherché dans le CV
     *
     * @return string
     */
    public static function cvSearch($mot) {
        $mot2 = str_replace(' ', '_', $mot);
        $mot2 = str_replace("\'", '_', $mot2);
        exec('find ' . CV_DIR . ' -type f -exec grep "' . $mot . '" {} \; > ' . RECHERCHE_CV_DIR . 'recherche-' . $mot2 . '');
        system('sed -i "s/Binary file ..\\/CVtheque\\///g" ' . RECHERCHE_CV_DIR . 'recherche-' . $mot2 . '');
        system('sed -i "s/ matches/\",\"/g" ' . RECHERCHE_CV_DIR . 'recherche-' . $mot2 . '');
        system('sed -i "/^$/d" ' . RECHERCHE_CV_DIR . 'recherche-' . $mot2 . '');

        shell_exec('cat ' . RECHERCHE_CV_DIR . 'recherche-' . $mot2 . ' | grep CV > ' . RECHERCHE_CV_DIR . 'search-' . $mot2 . '');
        shell_exec('cat ' . RECHERCHE_CV_DIR . 'search-' . $mot2 . ' | sort -d');
        $listecv = shell_exec('sed \':z;N;$! bz;s/\n//g\' ' . RECHERCHE_CV_DIR . 'search-' . $mot2 . '');
        shell_exec('rm ' . RECHERCHE_CV_DIR . 'recherche*');

        if (!$listecv) {
            $html = NO_DATA_INFO;
        } else {
            $db = connecter();
            $requete = 'SELECT DISTINCT candidature.Id_candidature, candidature.Id_ressource, Id_etat,Id_nature,
			DATE_FORMAT(date, "%d-%m-%Y") as date,tel_portable,lien_cv, lien_lm, lien_cvp, ressource.nom, ressource.prenom 
			FROM candidature INNER JOIN ressource ON candidature.Id_ressource=ressource.Id_ressource 
			WHERE candidature.archive=0 AND (lien_cv IN ("' . $listecv . '") OR lien_cvp IN ("' . $listecv . '"))';
            $requete = str_replace(",\"\"", '', $requete);
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => 'javascript:HTML_AJAX.replace(\'page\',\'../source/index.php?a=rechercheCV&pageID=%d&mot=' . $mot . '\');', //Pager replaces "%d" with the page number...
                'perPage' => TAILLE_LISTE, 'delta' => DELTA, 'itemData' => $data,);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html = '
	                    <table class="sortable">
	                    <tr>
		                    <th>Id</th>
			                <th>Nom / Prénom</th>
					        <th>Tél</th>
					        <th>Nature</th>
					        <th>Etat</th>
					        <th>Date</th>
					        <th>Profil</th>
	                    </tr>';
                foreach ($paged_data['data'] as $ligne) {
                    $candidature = new Candidature($ligne['id_candidature'], array());
                    $entretien = new Entretien($candidature->Id_entretien, array());
                    $ressource = RessourceFactory::create('CAN_AGC', $ligne['id_ressource'], null);
                    $lien_cv = $lien_cvp = $lien_lm = '';
                    if ($ligne['lien_cv']) {
                        $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $ligne['lien_cv'] . "'><img src=" . IMG_CV . "></a></td>";
                    }
                    if ($ligne['lien_cvp']) {
                        $lien_cvp = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $ligne['lien_cvp'] . "'><img src=" . IMG_CVP . "></a></td>";
                    }
                    if ($ligne['lien_lm']) {
                        $lien_lm = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $ligne['lien_lm'] . "'><img src=" . IMG_LM . "></a></td>";
                    }
                    $htmlAdmin = '
 					            <td><a href="../membre/index.php?a=modifier&amp;Id=' . $ligne['id_candidature'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
					            <td><a href="../membre/index.php?a=creer_entretien&amp;Id_candidature=' . $ligne['id_candidature'] . '"><img src="' . IMG_ENTRETIEN . '"></a></td>
                            ';
                    $info = '';
                    if ($candidature->commentaire) {
                        $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($candidature->commentaire)) . '<hr />' . str_replace('"', "'", mysql_escape_string($candidature->history(0))) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                    }
                    $info2 = '';
                    if ($entretien->commentaire_rh || $entretien->commentaire_com || $entretien->commentaire_rt) {
                        $info2 = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_rh)) . '<hr />' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_com)) . '<hr />' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_rt)) . '</div>\', FULLHTML);" onmouseout="return nd();"';
                    }
                    $html .= '
				            <tr class="row' . $j . '">
					            <td ' . $info . '>' . $ligne['id_candidature'] . '</td>
					            <td ' . $info2 . '>' . $ressource->getName() . '</td>
					            <td>' . $ligne['tel_portable'] . '</td>
					            <td>' . $candidature->getNature() . '</td>
					            <td>' . $candidature->getStatus($ligne['id_etat']) . '</td>
					            <td>' . $ligne['date'] . '</td>
					            <td>' . Profil::getLibelle(Ressource::getIdProfil($ligne['id_ressource'])) . '</td>
					            <td><a href="../membre/index.php?a=afficherCandidature&amp;Id_candidature=' . $ligne['id_candidature'] . '"><img src="' . IMG_CONSULT . '"></a></td>
					            ' . $lien_cv . '
					            ' . $lien_cvp . '
					            ' . $htmlAdmin . '
				            </tr>';
                }
            }
            $html .= '</table><br />';
        }
        return $html;
    }

    /**
     * Définit la couleur de la ligne du candidat selon son département d'habitation pour la liste des candidats
     *
     * @return string
     */
    public static function lineColorPerDepartment($dpt) {
        if ($dpt == 20) {
            $dpt = '2A';
        }
        $db = connecter();
        return 'style = "line-height: 2.2em;height:25px;background-color:' . $db->query('SELECT couleur FROM departement WHERE Id_departement = "' . mysql_real_escape_string($dpt) . '"')->fetchRow()->couleur . '"';
    }

    /**
     * Edition de la partie ressource de la zone 'information candidat' de la fiche candidature en pdf
     */
    public function edit() {
        //affichage du titre de la feuille : son numéro de candidature
        $_SESSION['titre'] = 'FICHE CANDIDATURE N°' . (int) $this->Id_candidature;
        //création des instances ressource et entretien pour récupérer les information concernant le candidat et son entretien
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, array());
        $entretien = new Entretien($this->Id_entretien, array());
        //récupération de l'historique des états de la candidature
        $db = connecter();
        $result = $db->query('SELECT Id_etat, DATE_FORMAT(date, "%d-%m-%Y") as date_h, Id_utilisateur
		FROM historique_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature) . ' ORDER BY date DESC');
        $historique_E = array();
        $historique_E['Id_etat'] = array();
        $historique_E['date_h'] = array();
        $historique_E['Id_utilisateur'] = array();
        while ($ligne = $result->fetchRow()) {
            array_push($historique_E['Id_etat'], $ligne->id_etat);
            array_push($historique_E['date_h'], $ligne->date_h);
            array_push($historique_E['Id_utilisateur'], $ligne->id_utilisateur);
        }
        $nb_historique_E = count($historique_E['Id_etat']);
        //récupération de l'historique des actions de la candidature
        $result2 = $db->query('SELECT Id_action_mener, DATE_FORMAT(date_action, "%d-%m-%Y") as date_h, Id_utilisateur
		FROM historique_action_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature) . ' ORDER BY date_action DESC');
        $historique_A = array();
        $historique_A['Id_action_mener'] = array();
        $historique_A['date_h'] = array();
        $historique_A['Id_utilisateur'] = array();
        while ($ligne2 = $result2->fetchRow()) {
            array_push($historique_A['Id_action_mener'], $ligne2->id_action_mener);
            array_push($historique_A['date_h'], $ligne2->date_h);
            array_push($historique_A['Id_utilisateur'], $ligne2->id_utilisateur);
        }
        $nb_historique_A = count($historique_A['Id_action_mener']);


        //récupération du positionnement du candidat
        $result3 = $db->query('SELECT dr.Id_demande_ressource,comp_profil,client,Id_ic,DATE_FORMAT(drc.date,"%d-%m-%Y") as date
		FROM demande_ressource dr INNER JOIN demande_ressource_candidat drc ON dr.Id_demande_ressource=drc.Id_demande_ressource 
		WHERE Id_ressource="' . mysql_real_escape_string($this->Id_ressource) . '"');
        $pos = array();
        $pos['intitule'] = array();
        $pos['client'] = array();
        $pos['commercial'] = array();
        $pos['date'] = array();
        while ($ligne3 = $result3->fetchRow()) {
            array_push($pos['intitule'], $ligne3->comp_profil);
            array_push($pos['client'], $ligne3->client);
            array_push($pos['commercial'], $ligne3->id_ic);
            array_push($pos['date'], $ligne3->date);
        }
        $nb_pos = count($pos['intitule']);

        //récupération de l'action et de l'état de la candidature
        $action_mener = self::getLibelleActionMener($this->Id_action_mener);
        $etat = $this->getStatus($this->Id_etat);
        //récupération des agences de rattachement
        $slach = '';
        if (!empty($this->agence_souhaitee)) {
            foreach ($this->agence_souhaitee as $i) {
                $agence_rattachement .= $slach . Agence::getLibelle($i);
                $slach = ' / ';
            }
        }

        $independant = 0;
        if (!empty($this->type_contrat)) {
            foreach ($this->type_contrat as $i) {
                if ($i == 5) {
                    $independant = 1;
                }
                /* Garder les doubles quote pour affichage correct sur le PDF */
                $htmlTypeContrat .= "-" . self::getSearchContractTypeLibelle($i);
            }
        }

        //traitement des commentaires de la candidature pour affichage en pdf
        $commentaire = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->commentaire))));
        //début de la création du pdf
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(false, 20);
        $pdf->AddPage();
        $pdf->SetStyle('t1', 'arial', 'B', 12, '70, 110, 165');
        $pdf->SetStyle('t2', 'arial', '', 10, '0,0,0');
        $pdf->SetStyle('t3', 'arial', '', 9, '70, 110, 165');
        $pdf->SetStyle('t4', 'arial', '', 7, '0,30,150');
        $pdf->SetStyle('t5', 'arial', 'U', 8, '0,0,0');
        $pdf->SetStyle('t6', 'arial', '', 9, '70, 110, 165');
        //affichage des données concernant l'identité du candidat
        $ressource->edit1($pdf);
        //affichage de l'historique de la candidature
        $pdf->setLeftMargin(10);
        $pdf->setXY(10, $pdf->GetY() + 3);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(190, 5, '<t1>HISTORIQUE CANDIDATURE</t1>', 1, 'C', 0);
        $y4 = $pdf->GetY() + 3;
        //liste des état
        if ($nb_historique_E) {
            $pdf->setXY(45, $y4);
            $pdf->MultiCellTag(20, 5, '<t5>Etat</t5>', 0, 'C', 0);
            $pdf->setXY(90, $y4);
            $pdf->MultiCellTag(20, 5, '<t5>Date</t5>', 0, 'C', 0);
            $pdf->setXY(135, $y4);
            $pdf->MultiCellTag(30, 5, '<t5>Réalisé par</t5>', 0, 'C', 0);
            $y8 = $pdf->GetY();
            $i = 0;
            while ($i < $nb_historique_E) {
                $pdf->setXY(45, $y8);
                $pdf->MultiCellTag(40, 5, "<t6> " . $this->getStatus($historique_E['Id_etat'][$i]) . " </t6>", 0, 'L', 0);
                $pdf->setXY(90, $y8);
                $pdf->MultiCellTag(45, 5, "<t6> " . $historique_E['date_h'][$i] . " </t6>", 0, 'L', 0);
                $pdf->setXY(135, $y8);
                $pdf->MultiCellTag(50, 5, "<t6> " . $historique_E['Id_utilisateur'][$i] . " </t6>", 0, 'L', 0);
                $y8 = $pdf->GetY();
                ++$i;
            }
        } else {
            $pdf->setXY(20, $y4);
            $pdf->MultiCellTag(100, 7, "Il n'y a pas d'historique renseigné", 0, 'L', 0);
        }
        //affichage des actions menées
        $pdf->setLeftMargin(10);
        $pdf->setXY(10, $pdf->GetY() + 3);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(190, 5, '<t1>ACTIONS MENEES</t1>', 1, 'C', 0);
        $y4 = $pdf->GetY() + 3;
        //liste des actions
        if ($nb_historique_A) {
            $pdf->setXY(45, $y4);
            $pdf->MultiCellTag(20, 5, '<t5>Action</t5>', 0, 'C', 0);
            $pdf->setXY(90, $y4);
            $pdf->MultiCellTag(20, 5, '<t5>Date</t5>', 0, 'C', 0);
            $pdf->setXY(135, $y4);
            $pdf->MultiCellTag(30, 5, '<t5>Réalisé par</t5>', 0, 'C', 0);
            $y8 = $pdf->GetY();
            $i = 0;
            while ($i < $nb_historique_A) {
                $pdf->setXY(45, $y8);
                $pdf->MultiCellTag(40, 5, "<t6> " . $this->getLibelleActionMener($historique_A['Id_action_mener'][$i]) . " </t6>", 0, 'L', 0);
                $pdf->setXY(90, $y8);
                $pdf->MultiCellTag(45, 5, "<t6> " . $historique_A['date_h'][$i] . " </t6>", 0, 'L', 0);
                $pdf->setXY(135, $y8);
                $pdf->MultiCellTag(50, 5, "<t6> " . $historique_A['Id_utilisateur'][$i] . " </t6>", 0, 'L', 0);
                $y8 = $pdf->GetY();
                ++$i;
            }
        } else {
            $pdf->setXY(20, $y4);
            $pdf->MultiCellTag(100, 7, "Il n'y a pas d'action renseignée", 0, 'L', 0);
        }

        //affichage du positionnement du candidat
        $pdf->setLeftMargin(10);
        $pdf->setXY(10, $pdf->GetY() + 3);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(190, 5, '<t1>POSITIONNEMENT</t1>', 1, 'C', 0);
        $y5 = $pdf->GetY() + 3;
        //liste des actions
        if ($nb_pos) {
            $pdf->setXY(30, $y5);
            $pdf->MultiCellTag(40, 5, '<t5>Intitulé</t5>', 0, 'C', 0);
            $pdf->setXY(80, $y5);
            $pdf->MultiCellTag(30, 5, '<t5>Client</t5>', 0, 'C', 0);
            $pdf->setXY(115, $y5);
            $pdf->MultiCellTag(40, 5, '<t5>Commercial</t5>', 0, 'C', 0);
            $pdf->setXY(155, $y5);
            $pdf->MultiCellTag(20, 5, '<t5>Date</t5>', 0, 'C', 0);
            $y9 = $pdf->GetY();
            $i = 0;
            while ($i < $nb_pos) {
                $pdf->setXY(30, $y9);
                $pdf->MultiCellTag(40, 5, "<t6> " . $pos['intitule'][$i] . " </t6>", 0, 'L', 0);
                $pdf->setXY(80, $y9);
                $pdf->MultiCellTag(30, 5, "<t6> " . $pos['client'][$i] . " </t6>", 0, 'L', 0);
                $pdf->setXY(115, $y9);
                $pdf->MultiCellTag(40, 5, "<t6> " . $pos['commercial'][$i] . " </t6>", 0, 'L', 0);
                $pdf->setXY(155, $y9);
                $pdf->MultiCellTag(20, 5, "<t6> " . $pos['date'][$i] . " </t6>", 0, 'L', 0);
                $y9 = $pdf->GetY();
                ++$i;
            }
        } else {
            $pdf->setXY(20, $y5);
            $pdf->MultiCellTag(100, 7, "Il n'y a pas de positionnement renseigné", 0, 'L', 0);
        }

        //affichage des information du candidat
        $pdf->setLeftMargin(10);
        $pdf->setXY(10, $pdf->GetY() + 3);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(190, 5, '<t1>INFORMATION CANDIDAT</t1>', 1, 'C', 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $y1 = $pdf->GetY() + 5;
        $pdf->setXY(15, $y1);
        $pdf->setLeftMargin(15);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->MultiCellTag(70, 7, "<t2>Etat candidature : </t2><t3>{$etat}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Agence de rattachement : </t2><t3>{$agence_rattachement}</t3>", 0, 'L', 0);
        $y2 = $pdf->GetY();
        $pdf->setLeftMargin(105);
        $pdf->setY($y1);
        $pdf->MultiCellTag(70, 7, "<t2>Action menée : </t2><t3>{$action_mener}</t3>", 0, 'L', 0);
        $pdf->setY($y2);
        $pdf->MultiCellTag(90, 7, "<t2>Type de contrat recherchés : </t2><t3>" . $htmlTypeContrat . "</t3>", 0, 'L', 0);
        $pdf->setXY(15, $y2);
        //affichage des données ressource
        $ressource->edit2($pdf);
        $pdf->setXY(15, $pdf->GetY());
        $pdf->MultiCellTag(180, 7, "<t2>Commentaires : </t2><t4>{$commentaire}</t4>", 0, 'L', 0);
        //affichage des données de l'entretien
        $pdf->setXY(10, $pdf->GetY() + 3);
        $pdf->MultiCellTag(190, 5, '<t1>FEUILLE ENTRETIEN</t1>', 1, 'C', 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $y3 = $pdf->GetY() + 5;
        $pdf->setXY(15, $y3);
        //si une fiche entretien a été renseignée, affichage des informations
        if ($this->Id_entretien) {
            $entretien->edit($pdf, $this->Id_ressource, $independant);
        } else {
            $pdf->MultiCellTag(100, 7, "Il n'y a pas de feuille entretien renseignée", 0, 'L', 0);
        }
        $pdf->Output();
    }

    /**
     * Affichage de l'historique des actions menées pour la candidature
     *
     * @param int Autorise les modifications
     * @param int Afficher ou non le titre
     *
     * @return string
     */
    public function actionHistory($i, $title=1) {
        $db = connecter();
        $result = $db->query('SELECT Id_action_mener, DATE_FORMAT(date_action, "%d-%m-%Y") as date_h, date_action, Id_utilisateur
		FROM historique_action_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature) . ' AND Id_positionnement <=> NULL ORDER BY date_action DESC');
        $html = '<h2>ACTIONS MENEES SANS DEMANDE DE RESSOURCE</h2>
		<table class="sortable">
		    <tr>
		        <th>Action</th>
		        <th>Réalisé par</th>
		        <th>Date (jj-mm-aaaa)</th>
		    </tr>';
        $j = 0;
        while ($ligne = $result->fetchRow()) {
            if ($i) {
                $htmlSupp = '<td width="5%" height="20"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { supprimerHAction(' . $this->Id_candidature . ',' . $ligne->id_action_mener . ',\'' . $ligne->date_action . '\') }" /></td>';
                $htmlValid = '<td width="5%" height="20"><input type="button" class="boutonValider" onclick="if (confirm(\'Valider la ligne ?\')) { validerHAction(' . $this->Id_candidature . ',' . $ligne->id_action_mener . ',' . $j . '); supprimerHAction(' . $this->Id_candidature . ',' . $ligne->id_action_mener . ',\'' . $ligne->date_action . '\') }" /></td>';
                $htmlInput = '<td width="30%" height="20"><input type="text"  onfocus="showCalendarControl(this)" id="date_action' . $j . '" value="' . $ligne->date_h . '" size="8" /></td>';
            } else {
                $htmlInput = '<td width="40%" height="20">' . $ligne->date_h . '</td>';
            }
            $html .= '<tr>
			<td width="30%" height="20">' . self::getLibelleActionMener($ligne->id_action_mener) . '</td>
			<td width="30%" height="20">' . $ligne->id_utilisateur . '</td>
			' . $htmlInput . '
			' . $htmlSupp . '
			' . $htmlValid . '
			</tr>';
            ++$j;
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Affichage de l'historique des actions menées pour la candidature
     *
     * @param int Autorise les modifications
     *
     * @return string
     */
    public function actionDemandeRessource($Id_positionnement, $nb) {
        $db = connecter();
        $result = $db->query('SELECT Id_action_mener, DATE_FORMAT(date_action, "%d-%m-%Y") as date_h, date_action, Id_utilisateur
		FROM historique_action_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature) . ' AND Id_positionnement = ' . mysql_real_escape_string((int) $Id_positionnement) . ' ORDER BY date_action DESC');
        $html = '<table>
		    <tr>
		        <th>Action</th>
		        <th>Date (jj-mm-aaaa)</th>
		        <th>Réalisé par</th>
		    </tr>';
        $j = 0;
        while ($ligne = $result->fetchRow()) {
            $htmlSupp = '<td width="5%" height="20"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { supprimerActionDemandeRessource(' . $this->Id_candidature . ',' . $ligne->id_action_mener . ',\'' . $ligne->date_action . '\',' . $Id_positionnement . ',' . $nb . ') }" /></td>';
            if ($j % 2 == 0)
                $html .= '<tr class="rowodd">';
            else
                $html .= '<tr class="roweven">';
            $html .= '
			<td width="30%" height="20">' . self::getLibelleActionMener($ligne->id_action_mener) . '</td>
			<td width="40%" height="20">' . $ligne->date_h . '</td>
			<td width="30%" height="20">' . $ligne->id_utilisateur . '</td>
			' . $htmlSupp . '
			</tr>';
            ++$j;
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Suppression d'une action menée
     *
     * @return string
     */
    public static function deleteActionHistory($Id_candidature, $Id_action_mener, $date_action) {
        $db = connecter();
        $db->query('DELETE FROM historique_action_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . '
		AND Id_action_mener=' . mysql_real_escape_string((int) $Id_action_mener) . ' AND date_action="' . mysql_real_escape_string($date_action) . '"');
    }

    /**
     * Validation et enregistrement d'une action menée
     *
     * @param int Identifiant de la candidature
     * @param int Identifiant de l'action menée
     * @param date date correspondant à l'état
     * @param int Identifiant du positionnement
     *
     * @return string
     */
    public static function validateActionHistory($Id_candidature, $Id_action_mener, $date_action, $Id_positionnement) {
        $db = connecter();
        $hms = $db->query('SELECT DATE_FORMAT(now(),"%H:%i:%s") as hms')->fetchRow()->hms;
        $db->query('INSERT INTO historique_action_candidature SET Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . ',
		Id_action_mener=' . mysql_real_escape_string((int) $Id_action_mener) . ', Id_positionnement=' . mysql_real_escape_string((int) $Id_positionnement) . ',
		date_action="' . mysql_real_escape_string(DateMysqltoFr($date_action, 'mysql')) . ' ' . mysql_real_escape_string($hms) . '", Id_utilisateur="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
    }

    /**
     * Mise à jour de l'action menée pour la candidature
     *
     * @param int Identifiant de l'action menée
     * @param int Identifiant de la candidature à mettre à jour
     * @param int Identifiant du positionnement
     */
    public static function updateAction($Id_action_mener, $Id_candidature, $Id_positionnement=0) {
        $db = connecter();
        $db->query('INSERT INTO historique_action_candidature SET Id_candidature=' . mysql_real_escape_string((int) $Id_candidature) . ',
		date_action="' . mysql_real_escape_string(DATETIME) . '", Id_action_mener=' . mysql_real_escape_string((int) $Id_action_mener) . ', Id_utilisateur="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '", Id_positionnement=' . mysql_real_escape_string((int) $Id_positionnement));
        $db->query('UPDATE candidature SET Id_action_mener=' . mysql_real_escape_string((int) $Id_action_mener) . ' WHERE Id_candidature=' . mysql_real_escape_string((int) $Id_candidature));
    }

    /**
     * Affiche le nombre de modification effectuées par un caniddat cvweb
     *
     * @return int
     */
    public function nbCvWebModification() {
        $db = connecter();
        return '<span class="vert">' . $db->query('SELECT count(*) as nb FROM modif_candidat WHERE Id_candidature=' . mysql_real_escape_string((int) $this->Id_candidature))->fetchRow()->nb . '</span>';
    }

    /**
     * Affiche le nombre d'annonces auxquelles un candidat cvweb a postulé
     *
     * @return int
     */
    public function nbCvWebApply() {
        $db = connecter();
        return '<span class="vert">' . $db->query('SELECT count(*) as nb FROM candidat_annonce WHERE Id_candidat=' . mysql_real_escape_string((int) $this->Id_cvweb))->fetchRow()->nb . '</span>';
    }

    /**
     * Affichage d'une select box contenant les candidats
     *
     * @param int Type du candidat : candidat embauché (2) / non embauché (1) / candidat potentiel (3)
     * @param String Identifiant de la ressource du candidat
     *
     * @return string
     */
    public function getApplicantList($type, $Id_ressource='', $search='') {
        $db = connecter();
        if ($Id_ressource) {
            $candidature[$Id_ressource] = 'selected="selected"';
        } else {
            $candidature[$this->Id_ressource] = 'selected="selected"';
        }
        if ($search && $search != '*') {
            $where = ' WHERE ressource.nom LIKE "' . mysql_real_escape_string($search) . '%" ';
        }
        if ($search != 1) {
            $requete = 'SELECT DISTINCT candidature.Id_ressource, ressource.nom, ressource.prenom
					    FROM candidature
					    INNER JOIN ressource ON candidature.Id_ressource=ressource.Id_ressource';
            if ($type == 1) {
                $requete .= ' WHERE candidature.Id_etat NOT IN (8,9,10) ORDER BY ressource.nom';
                $html .= '
				<option value="">+-----------------------------------------------+</option>
			    <option value="">CANDIDATS NON EMBAUCHES</option>
			    <option value="">+-----------------------------------------------+</option>';
            } elseif ($type == 2) {
                $requete .= ' WHERE candidature.Id_etat IN (8,9) ORDER BY ressource.nom';
                $html .= '
				<option value="">+-----------------------------------------------+</option>
			    <option value="">CANDIDATS EMBAUCHES</option>
			    <option value="">+-----------------------------------------------+</option>';
            } elseif ($type == 3) {
                $requete .= $where . ' ORDER BY ressource.nom';
                $html .= '
				<option value="">+-----------------------------------------------+</option>
			    <option value="">CANDIDATS</option>
			    <option value="">+-----------------------------------------------+</option>';
            }
            $result = $db->query($requete);
            while ($ligne = $result->fetchRow()) {
                $html .= '<option class="grisc" value="' . $ligne->id_ressource . '" ' . $candidature[$ligne->id_ressource] . '>' . substr($ligne->nom, 0, 10) . ' ' . substr($ligne->prenom, 0, 10) . '</option>';
            }
        }
        return $html;
    }

    /**
     * Affichage du libelle du type de contrat recherché
     *
     * @param int Identifiant du type de contrat
     *
     * @return string
     */
    public static function getSearchContractTypeLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM type_contrat WHERE Id_type_contrat=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage du libelle du type de contrat recherché
     *
     * @param int Identifiant du type de contrat
     *
     * @return string
     */
    public function getSearchContractTypeForm($Id_candidature = 0) {
        $db = connecter();
        $result = $db->query('SELECT * FROM type_contrat ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $ligne2 = $db->query('SELECT count(Id_type_contrat) as nb
			FROM candidat_typecontrat WHERE Id_candidat="' . $Id_candidature . '"
			AND Id_type_contrat="' . mysql_real_escape_string($ligne->id_type_contrat) . '"')->fetchRow();
            if ($ligne2->nb > 0) {
                $html .= '<option value=' . $ligne->id_type_contrat . ' selected="selected">' . $ligne->libelle . '</option>';
            } else {
                $html .= '<option value=' . $ligne->id_type_contrat . '>' . $ligne->libelle . '</option>';
            }
        }
        return $html;
    }

    /**
     * Affichage de la liste des types de contrat dans un champs select dans filtre de recherche des candidats
     *
     * @param array liste des types de contrats sélectionnés
     *
     * @return string
     */
    public static function getSelectTypeContratListe($type_contrat) {
        //tableau pour permettre de noter en 'selected' les types de contrats sélectionnées
        if (!empty($type_contrat)) {
            foreach ($type_contrat as $i) {
                $tc[$i] = 'selected="selected"';
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM type_contrat ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_type_contrat . '" ' . $tc[$ligne->id_type_contrat] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Envoi du mail avec les informations sur le candidat Staff embauché
     *
     */
    public function sendStaffHiringMail() {
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, null);
        $subject = 'Nouveau Recrutement Staff ' . $ressource->getName() . '';
        $dest = MAIL_EMBAUCHE_STAFF_DEST;
        $text = 'Bonjour,
			
Voici le lien concernant le nouveau recrutement staff de ' . $ressource->getName() . '
		
' . BASE_URL . 'membre/index.php?a=afficherCandidature&Id_candidature=' . $this->Id_candidature . '';
        $hdrs = array(
            'From' => $this->createur . '@proservia.fr',
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


//      $send = $mail_object->send($dest, $hdrs, $body);
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }

    }

    /**
     * Envoi du mail avec les informations sur le candidat Staff embauché à destination de l'ADV
     *
     */
    public function sendStafffHiringADVMail() {
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, null);
        $Id_profil_AGC = $ressource->id_profil;
        $id_profil_cegid = $ressource->id_profil_cegid;
        $profil_AGC = str_replace("'", " ", Profil::GetLibelle($Id_profil_AGC));
        $profil_cegid = str_replace("'", " ", Profil::getLibelleCegid($id_profil_cegid));

        $subject = 'Nouveau Recrutement Staff ' . $ressource->getName() . '';
        $dest = MAIL_EMBAUCHE_STAFF_ADV;
        $text = 'Bonjour,

Nous vous informons de l\'arrivée de ' . $ressource->getName() . ' (Code Ressource ' . $this->Id_ressource . ') à compter du ' . $this->date_reponse . ' :

- Imputation : Hors affaire.
- Profil AGC : ' . $profil_AGC . '
- Profil CEGID : ' . $profil_cegid . '
- Agence de rattachement : ' . $ressource->Id_contrat_proservia . '

Cordialement,

' . $this->createur . '@proservia.fr';

        $hdrs = array(
            'From' => $this->createur . '@proservia.fr',
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

        $send = $mail_object->send('youssouf.coulibaly-ext@manpowergroup-companies.fr', $hdrs, $body);
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }

    }
    
    /**
     * Envoi du mail avec les informations sur le candidat Staff embauché
     *
     */
    public function sendDisabledHiringMail() {
        $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, null);
        $subject = 'Nouveau Recrutement d\'un candidat handicapé ' . $ressource->getName() . '';
        $dest = explode(',', MAIL_EMBAUCHE_HANDICAPE_DEST);
        $text = 'Bonjour,
			
Voici le lien concernant le nouveau recrutement de ' . $ressource->getName() . '
		
' . BASE_URL . 'membre/index.php?a=afficherCandidature&Id_candidature=' . $this->Id_candidature . '';
        $hdrs = array(
            'From' => $this->createur . '@proservia.fr',
            'Subject' => $subject,
            'To' => MAIL_EMBAUCHE_HANDICAPE_DEST
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

//        $send = $mail_object->send($dest, $hdrs, $body);
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showId($record, $args) {
        $candidature = new Candidature($record['id_candidature'], array());
        if ($candidature->commentaire) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($candidature->commentaire)) . '<hr />' . str_replace('"', "'", mysql_escape_string($candidature->history(0))) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if (!$args['csv'])
            return '<div ' . $info . ' ' . $record['color'] . '>' . $record['id_candidature'] . '</div>';
        else
            return $record['id_candidature'];
    }
    
    public function showPhone($record, $args) {
        if (!$args['csv'])
            return '<div ' . $record['color'] . '>' . $record['tel_portable'] . '</div>';
        else
            return $record['tel_portable'];
    }
    
    public function showDate($record, $args) {
        if (!$args['csv'])
            return '<div ' . $record['color'] . '>' . $record['date_m'] . '</div>';
        else
            return $record['date_m'];
    }

    public function showName($record, $args) {
        $candidature = new Candidature($record['id_candidature'], array());
        $ressource = RessourceFactory::create('CAN_AGC', $record['id_ressource'], null);
        $entretien = new Entretien($candidature->Id_entretien, array());
        if ($entretien->commentaire_rh || $entretien->commentaire_com || $entretien->commentaire_rt) {
            $info2 = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_rh)) . '<hr />' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_com)) . '<hr />' . str_replace('"', "'", mysql_escape_string($entretien->commentaire_rt)) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if (!$args['csv'])
            return '<div ' . $info2 . ' ' . $record['color'] . '>' . $ressource->getName() . '</div>';
        else
            return $ressource->getName();
    }

    public function showNature($record, $args) {
        $candidature = new Candidature($record['id_candidature'], array());
        if (!$args['csv'])
            return '<div ' . $record['color'] . '>' . $candidature->getNature() . '</div>';
        else return $candidature->getNature();
    }

    public function showState($record, $args) {
        $candidature = new Candidature($record['id_candidature'], array());
        if (!$args['csv'])
            return '<div ' . $record['color'] . '>' . $candidature->getStatus($record['id_etat']) . '</div>';
        else return $candidature->getStatus($record['id_etat']);
    }

    public function showProfile($record, $args) {
        if (!$args['csv'])
            return '<div ' . $record['color'] . '>' . Profil::getLibelle(Ressource::getIdProfil($record['id_ressource'])) . '</div>';
        else return Profil::getLibelle(Ressource::getIdProfil($record['id_ressource']));
    }

    public function showButtons($record, $args) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            $admin = 1;
        }

        $lien_cv = $lien_cvp = $lien_lm = $htmlAdmin = '';
        $htmlModif = '<td><a href="../membre/index.php?a=afficherCandidature&amp;Id_candidature=' . $record['id_candidature'] . '"><img src="' . IMG_CONSULT . '"></a></td>
          <td><a href="../membre/index.php?a=editerCandidat&Id_candidature=' . $record['id_candidature'] . '"><img src="' . IMG_PDF . '"></a></td>';
        if ($record['lien_cv']) {
            $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $record['lien_cv'] . "'><img src=" . IMG_CV . "></a></td>";
        }
        if ($record['lien_cvp']) {
            $lien_cvp = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $record['lien_cvp'] . "'><img src=" . IMG_CVP . "></a></td>";
        }
        if ($record['lien_lm']) {
            $lien_lm = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $record['lien_lm'] . "'><img src=" . IMG_LM . "></a></td>";
        }
        $class = ($_SESSION['societe'] == 'OVIALIS') ? 'Ovialis_Candidature' : __CLASS__;
        $htmlAdmin .= '<td><a href="../membre/index.php?a=modifier&amp;Id=' . $record['id_candidature'] . '&amp;class=' . $class . '"><img src="' . IMG_EDIT . '"></a></td>
                    <td><a href="../membre/index.php?a=creer_entretien&amp;Id_candidature=' . $record['id_candidature'] . '"><img src="' . IMG_ENTRETIEN . '"></a></td>';
        if ($record['archive'] == 0) {
            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' la candidature ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_candidature'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a></td>';
        } elseif ($record['archive'] == 1) {
            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' la candidature ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_candidature'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '"></a></td>';
        }
        if ($admin) {
            $ressource = RessourceFactory::create('CAN_AGC', $record['id_ressource'], null);
            $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer la candidature de ' . $ressource->getName() . ' ?\')) { location.replace(\'../gestion/index.php?a=supprimer&amp;Id=' . $record['id_candidature'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        }
        return $htmlModif . $lien_cv . $lien_cvp . $lien_lm . $htmlAdmin;
    }

}

?>
