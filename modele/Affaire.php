<?php

/**
 * Fichier Affaire.php
 *
 * @author    Anthony Anne
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Affaire
 * @package    ProjetAGC
 */
class Affaire {

    /**
     * Identifiant de l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_affaire;
    /**
     * Compte associé à l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_compte;
    /**
     * Contact 1 associé à l'affaire
     *
     * @access public
     * @var string
     */
    public $Id_contact1;
    /**
     * Contact 2 associé à l'affaire
     *
     * @access public
     * @var string 
     */
    public $Id_contact2;
    /**
     * Créateur de l'affaire
     *
     * @access public
     * @var string
     */
    public $createur;
    /**
     * Commercial de l'affaire
     *
     * @access public
     * @var string 
     */
    public $commercial;
    /**
     * Apporteur de l'affaire
     *
     * @access public
     * @var string
     */
    public $apporteur;
    /**
     * Rédacteur 1 de l'affaire
     *
     * @access private
     * @var string 
     */
    private $redacteur1;
    /**
     * Rédacteur 2 de l'affaire
     *
     * @access private
     * @var string
     */
    private $redacteur2;
    /**
     * Identifiant du statut de l'affaire
     *
     * @access private
     * @var int 
     */
    public $Id_statut;
    /**
     * Identifiant de l'agence
     *
     * @access public
     * @var string
     */
    public $Id_agence;
    /**
     * Tableau des compétences associées à l'affaire
     *
     * @access public
     * @var array 
     */
    public $competence;
    /**
     * Tableau des langues associées à l'affaire
     *
     * @access public
     * @var array
     */
    public $langue;
    /**
     * Tableau des exigences associées à l'affaire
     *
     * @access public
     * @var array 
     */
    public $exigence;
    /**
     * Indique si l'affaire est une archive
     *
     * @access private
     * @var int
     */
    private $archive;
    /**
     * Identifiant de la description de l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_description;
    /**
     * Identifiant de l'analyse commerciale de l'affaire
     *
     * @access private
     * @var int
     */
    private $Id_analyse_commerciale;
    /**
     * Identifiant de l'analyse de risque de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_analyse_risque;
    /**
     * Identifiant de la décision
     *
     * @access private
     * @var int
     */
    private $Id_decision;
    /**
     * Identifiant de l'environnement
     *
     * @access private
     * @var int 
     */
    private $Id_environnement;
    /**
     * Identifiant du planning de l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_planning;
    /**
     * Tableau des identifiants des propositions pour l'affaire
     *
     * @access public
     * @var array 
     */
    public $proposition;
    /**
     * Identifiant du pole associé à l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_pole;
    /**
     * Date de création de l'affaire
     *
     * @access public
     * @var date 
     */
    public $date_creation;
    /**
     * Date de modification de l'affaire
     *
     * @access public
     * @var date
     */
    public $date_modification;
    /**
     * Nombre de jour estimé pour l'affaire
     *
     * @access public
     * @var double 
     */
    public $nb_jour_estime;
    /**
     * Identifiant de la session pour les affaires du pôle formation
     *
     * @access public
     * @var int
     */
    public $Id_session;
    /**
     * Identifiant du type de contrat de l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_type_contrat;
    /**
     * Commentaires pour les champs retirés
     *
     * @access private
     * @var string
     */
    private $commentaire;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'une affaire
     *
     * @access private
     * @var array
     */
    private $erreurs;

    /**
     * Constructeur de la classe affaire
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'Affaire
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */

            if (!$code && count($tab) == 3) {
                $this->Id_affaire = '';
                $this->Id_compte = '';
                $this->Id_contact1 = '';
                $this->Id_contact2 = '';
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->commercial = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->apporteur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->redacteur1 = '';
                $this->redacteur2 = '';
                $this->Id_statut = '';
                $this->Id_agence = Utilisateur::getAgency($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur);
                $this->competence = '';
                $this->niveau_competence = '';
                $this->langue = '';
                $this->niveau_langue = '';
                $this->exigence = '';
                $this->Id_description = '';
                $this->Id_analyse_commerciale = '';
                $this->Id_analyse_risque = '';
                $this->Id_decision = '';
                $this->Id_environnement = '';
                $this->Id_planning = '';
                $this->proposition = '';
                $this->Id_pole = $tab['Id_pole'];
                $this->archive = 0;
                $this->date_creation = '';
                $this->date_modification = '';
                $this->resp_qualif = '';
                $this->sdm = '';
                $this->resp_tec1 = '';
                $this->resp_tec2 = '';
                $this->nb_jour_estime = '';
                $this->doc = '';
                $this->ouverture_compte = '';
                $this->Id_type_contrat = $tab['Id_type_contrat'];
                $this->Id_session = '';
                $this->commentaire = '';
            }
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_affaire = '';
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->commercial = $tab['commercial'];
                $this->apporteur = $tab['apporteur'];
                $this->redacteur1 = $tab['redacteur1'];
                $this->redacteur2 = $tab['redacteur2'];
                $this->Id_compte = $tab['Id_compte'];
                $this->Id_contact1 = $tab['Id_contact1'];
                $this->Id_contact2 = $tab['Id_contact2'];
                $this->Id_statut = $tab['Id_statut'];
                $this->Id_agence = $tab['Id_agence'];
                $this->exigence = $tab['exigence'];
                $this->Id_description = $tab['Id_description'];
                $this->Id_analyse_commerciale = $tab['Id_analyse_commerciale'];
                $this->Id_analyse_risque = $tab['Id_analyse_risque'];
                $this->Id_decision = $tab['Id_decision'];
                $this->Id_environnement = $tab['Id_environnement'];
                $this->Id_planning = $tab['Id_planning'];
                $this->proposition = $tab['proposition'];
                $this->archive = $tab['archive'];
                $this->date_creation = DATETIME;
                $this->date_modification = DATETIME;
                $this->doc = $tab['doc'];
                $this->ouverture_compte = $tab['ouverture_compte'];
                $this->resp_qualif = $tab['resp_qualif'];
                $this->sdm = $tab['sdm'];
                $this->resp_tec1 = $tab['resp_tec1'];
                $this->resp_tec2 = $tab['resp_tec2'];
                $this->nb_jour_estime = $tab['nb_jour_estime'];
                $this->Id_type_contrat = $tab['Id_type_contrat'];
                $this->Id_pole = $tab['Id_pole'];
                $this->Id_session = $tab['Id_session'];

                if ($tab['langue']) {
                    $nb_langue = count($tab['langue']);
                    $this->langue = array();
                    $this->niveau_langue = array();
                    $i = 0;
                    while ($i < $nb_langue) {
                        if ($tab['langue'][$i]) {
                            $this->langue[] = $tab['langue'][$i];
                            $this->niveau_langue[] = $tab['niveau_langue'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['competence']) {
                    $nb_competence = count($tab['competence']);
                    $this->competence = array();
                    $this->niveau_competence = array();
                    $i = 0;
                    while ($i < $nb_competence) {
                        $this->competence[] = $tab['competence'][$i];
                        $this->niveau_competence[] = $tab['niveau_competence'][$i];
                        ++$i;
                    }
                }
                $this->Id_raison_perdue = $tab['Id_raison_perdue'];
                $this->commentaire_perdue = $tab['commentaire_perdue'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_affaire = $code;
                $this->Id_compte = $ligne->id_compte;
                $this->createur = $ligne->createur;
                $this->commercial = $ligne->commercial;
                $this->apporteur = $ligne->apporteur;
                $this->redacteur1 = $ligne->redacteur1;
                $this->redacteur2 = $ligne->redacteur2;
                $this->Id_compte = $ligne->id_compte;
                $this->Id_contact1 = $ligne->id_contact1;
                $this->Id_contact2 = $ligne->id_contact2;
                $this->Id_statut = $ligne->id_statut;
                $this->Id_agence = $ligne->id_agence;
                $this->archive = $ligne->archive;
                $this->date_creation = $ligne->date_creation;
                $this->date_modification = $ligne->date_modification;
                $this->doc = $ligne->doc;
                $this->ouverture_compte = $ligne->ouverture_compte;
                $this->Id_type_contrat = $ligne->id_type_contrat;
                $this->Id_session = $ligne->id_session;
                $this->resp_qualif = $ligne->resp_qualif;
                $this->sdm = $ligne->sdm;
                $this->resp_tec1 = $ligne->resp_tec1;
                $this->resp_tec2 = $ligne->resp_tec2;
                $this->nb_jour_estime = $ligne->nb_jour_estime;
                $this->commentaire = $ligne->commentaire;

                $ligne = $db->query('SELECT Id_description, Id_intitule,ville FROM description WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_description = $ligne->id_description;
                $this->Id_intitule = $ligne->id_intitule;
                $this->ville = $ligne->ville;

                $ligne = $db->query('SELECT Id_planning, date_debut, date_fin_commande,date_fin_previsionnelle, duree FROM planning WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_planning = $ligne->id_planning;
                $this->date_debut = $ligne->date_debut;
                $this->date_fin_commande = $ligne->date_fin_commande;
                $this->date_fin_previsionnelle = $ligne->date_fin_previsionnelle;
                $this->duree = $ligne->duree;
                $result = $db->query('SELECT Id_proposition FROM proposition WHERE Id_affaire=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->proposition[] = $ligne->id_proposition;
                }
                $this->Id_pole = $db->query('SELECT Id_pole FROM affaire_pole WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow()->id_pole;

                $result = $db->query('SELECT Id_competence, Id_niveau_competence FROM affaire_competence WHERE Id_affaire=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->competence[] = $ligne->id_competence;
                    $this->niveau_competence[] = $ligne->id_niveau_competence;
                }

                $result = $db->query('SELECT Id_langue, Id_niveau_langue FROM affaire_langue WHERE Id_affaire=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->langue[] = $ligne->id_langue;
                    $this->niveau_langue[] = $ligne->id_niveau_langue;
                }
                $this->Id_analyse_commerciale = $db->query('SELECT Id_analyse_commerciale FROM analyse_commerciale WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow()->id_analyse_commerciale;
                $this->Id_analyse_risque = $db->query('SELECT Id_analyse_risque FROM analyse_risque WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow()->id_analyse_risque;
                $this->Id_decision = $db->query('SELECT Id_decision FROM decision WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow()->id_decision;

                $result = $db->query('SELECT Id_exigence FROM affaire_exigence WHERE Id_affaire=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->exigence[] = $ligne->id_exigence;
                }
                $ligne = $db->query('SELECT Id_environnement, env_technique FROM environnement WHERE Id_affaire=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_environnement = $ligne->id_environnement;
                $this->env_technique = $ligne->env_technique;
                $ligne = $db->query('SELECT Id_raison, commentaire FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string((int) $code) . ' AND Id_statut=6')->fetchRow();
                $this->Id_raison_perdue = $ligne->id_raison;
                $this->commentaire_perdue = $ligne->commentaire;
                $result->free();
                $db->disconnect();
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_affaire = $code;
                $this->commercial = $tab['commercial'];
                $this->apporteur = $tab['apporteur'];
                $this->redacteur1 = $tab['redacteur1'];
                $this->redacteur2 = $tab['redacteur2'];
                $this->Id_compte = $tab['Id_compte'];
                $this->Id_contact1 = $tab['Id_contact1'];
                $this->Id_contact2 = $tab['Id_contact2'];
                $this->Id_statut = $tab['Id_statut'];
                $this->Id_agence = $tab['Id_agence'];
                $this->Id_description = $tab['Id_description'];
                $this->Id_analyse_commerciale = $tab['Id_analyse_commerciale'];
                $this->Id_analyse_risque = $tab['Id_analyse_risque'];
                $this->Id_decision = $tab['Id_decision'];
                $this->Id_environnement = $tab['Id_environnement'];
                $this->Id_planning = $tab['Id_planning'];
                $this->proposition = $tab['proposition'];
                $this->archive = $tab['archive'];
                $this->date_modification = DATETIME;
                $this->ouverture_compte = $tab['ouverture_compte'];
                $this->Id_type_contrat = $tab['Id_type_contrat'];
                $this->Id_session = $tab['Id_session'];

                if ($tab['doc'] != '') {
                    $this->doc = $tab['doc'];
                    if (self::getDoc($code) != $this->doc) {
                        if (is_file(PROPALE_DIR . self::getDoc($code))) {
                            unlink(PROPALE_DIR . self::getDoc($code));
                        }
                    }
                } else {
                    $this->doc = self::getDoc($code);
                }

                $this->resp_qualif = $tab['resp_qualif'];
                $this->sdm = $tab['sdm'];
                $this->resp_tec1 = $tab['resp_tec1'];
                $this->resp_tec2 = $tab['resp_tec2'];
                $this->nb_jour_estime = $tab['nb_jour_estime'];
                $this->Id_pole = $tab['Id_pole'];

                if ($tab['competence']) {
                    $nb_competence = count($tab['competence']);
                    $i = 0;
                    while ($i < $nb_competence) {
                        $this->competence[] = $tab['competence'][$i];
                        $this->niveau_competence[] = $tab['niveau_competence'][$i];
                        ++$i;
                    }
                }
                if ($tab['exigence']) {
                    $nb_exigence = count($tab['exigence']);
                    $i = 0;
                    while ($i < $nb_exigence) {
                        $this->exigence[] = $tab['exigence'][$i];
                        ++$i;
                    }
                }
                if ($tab['langue']) {
                    $nb_langue = count($tab['langue']);
                    $i = 0;
                    while ($i < $nb_langue) {
                        if ($tab['langue'][$i]) {
                            $this->langue[] = $tab['langue'][$i];
                            $this->niveau_langue[] = $tab['niveau_langue'][$i];
                        }
                        ++$i;
                    }
                }
                $this->Id_raison_perdue = $tab['Id_raison_perdue'];
                $this->commentaire_perdue = $tab['commentaire_perdue'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une affaire
     *
     * @return string
     */
    public function form() {
        $agence = new Agence($this->Id_agence, array());
        
        $pole = new Pole($this->Id_pole, array());
        if (!empty($this->Id_affaire)) {
            $proposition = new Proposition(self::lastProposition($this->Id_affaire), array());
            $libellPole = Pole::getLibelle($this->Id_pole) . '<input type="hidden" name="Id_pole" id="Id_pole" value="' . $this->Id_pole . '" />';
            $archives = '<hr /><br />
				<h2 onclick="toggleZone(' . "'commentaire'" . ')" class="cliquable">Archives</h2><br />
				<div id="commentaire" style="display:none">
					' . nl2br($this->commentaire) . '
				</div>';
            $demandesRessources = '<div class="clearer"><hr /></div><br />
				<h2 onclick="toggleZone(\'demandeRessource\')" class="cliquable">Demande de Recrutement</h2><br />
				<div id="demandeRessource" style="display:none">
					' . DemandeRessource::search($this->Id_affaire, '', '', '', '', array(), '', '', '', '', '', '', '', '', '') . '
				</div>';
            $demande = $this->recruitmentRequestLink();
            $htmlHisto = '<h2>Historique des changements de statut</h2>' . $this->formStatusHistory() . '<hr />';
            if ($this->Id_pole == 1 && ($this->Id_type_contrat == 2 || $this->Id_type_contrat == 3)) {
                $write = 'disabled="disabled"';
                $hiddenRQ = '<input type="hidden" name="resp_qualif" id="resp_qualif" value="' . $this->resp_qualif . '">';
                $hiddenSDM = '<input type="hidden" name="sdm" id="sdm" value="' . $this->sdm . '">';
                $hiddenRT1 = '<input type="hidden" name="resp_tec1" id="resp_tec1" value="' . $this->resp_tec1 . '">';
                $hiddenRT2 = '<input type="hidden" name="resp_tec2" id="resp_tec2" value="' . $this->resp_tec2 . '">';
                if (Utilisateur::getTechnicalModuleRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur)) {
                    $write = $hiddenRQ = $hiddenSDM = $hiddenRT1 = $hiddenRT2 = '';
                }
                $resp_qualif = new Utilisateur($this->resp_qualif, array());
                $sdm = new Utilisateur($this->sdm, array());
                $resp_tec1 = new Utilisateur($this->resp_tec1, array());
                $resp_tec2 = new Utilisateur($this->resp_tec2, array());
                $htmlEtatTech = '
	            <hr />
				<h2><img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_TECHNICAL_MODULE . '</div>\', FULLHTML);" onmouseout="return nd();"></img> Etat technique</h2><br />
				Responsable de la qualification :
			    <select name="resp_qualif" id="resp_qualif" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_qualif->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_qualif->getList('OP') . '
	            </select>
	            ' . $hiddenRQ . '
				&nbsp;&nbsp;
				Service Delivery Manager :
			    <select name="sdm" id="sdm" ' . $write . '>
	                <option value="">' . SDM_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $sdm->getList('COM') . '
					<option value="">-------------------------</option>
					' . $sdm->getList('OP') . '
	            </select>
	            ' . $hiddenSDM . '
				<br /><br />
				Responsable technique 1 :
			    <select name="resp_tec1" id="resp_tec1" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_tec1->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_tec1->getList('OP') . '
	            </select>
	            ' . $hiddenRT1 . '
				&nbsp;&nbsp;
				Responsable technique 2 :
			    <select name="resp_tec2" id="resp_tec2" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_tec2->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_tec2->getList('OP') . '
	            </select>
	            ' . $hiddenRT2 . '
	            <br /><br />
				Nombre de jours de charges estimés :
			    <input type="text"  $write name="nb_jour_estime" id="nb_jour_estime" value="' . $_GET['nb_jour_estime'] . '" size="2" />';
            }
        } else {
            $proposition = new Proposition(null, null);
            $libellPole = '<select id="Id_pole" name="Id_pole" onchange="changeFormAffaire();">
                    <option value="">' . POLE_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $pole->getList() . '
                </select>';
        }
        if ($this->Id_statut == 6) {
            $htmlPerdu = '<script>changeStatut()</script>';
        }
        $langue = new Langue('', '');
        $statut = new Statut($this->Id_statut, array());
        $planning = new Planning($this->Id_planning, array());
        $description = new Description($this->Id_description, array());
        $commercial = new Utilisateur($this->commercial, array());
        $apporteur = new Utilisateur($this->apporteur, array());
        $redacteur1 = new Utilisateur($this->redacteur1, array());
        $redacteur2 = new Utilisateur($this->redacteur2, array());

        $titreCom = 'Proposition commerciale / Ressources affectées';
        $html .= '
		<form name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_affaire" method="post">
		<h2><span class="infoFormulaire"> * </span> POLE : ' . $libellPole . ' | TYPE DE CONTRAT : ' . TypeContrat::getLibelle($this->Id_type_contrat) . '</h2><br />
		' . $demande . ' ' . $htmlHisto . '
	        <div class="submit">
			    <input type="hidden" name="Id_affaire" id="Id_affaire" value="' . $this->Id_affaire . '" />
				<input type="hidden" name="Id_type_contrat" id="Id_type_contrat" value="' . $this->Id_type_contrat . '" />
				<input type="hidden" name="Id_proposition" id="Id_proposition" value="' . $this->proposition[0] . '" />
				<input type="hidden" name="Id_environnement" id="Id_environnement" value="' . $this->Id_environnement . '" />
				<input type="hidden" name="Id_intitule" id="Id_intitule" value="' . $description->Id_intitule . '" />
                                <input type="hidden" name="Id_planning" id="Id_planning" value="' . $this->Id_planning . '" />
		        <button type="submit" class="button save" value="' . SAVE_BUTTON . '" onclick="return verifAffaire(this.form)">' . SAVE_BUTTON . '</button>
		    </div><br /><hr />
			<h2>Etat commercial</h2><br />
			<div class="center">
			    <span class="infoFormulaire"> * </span> Agence :
		        <select id="Id_agence" name="Id_agence">
                    <option value="">' . AGENCE_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $agence->getList() . '
                </select>
			    &nbsp;&nbsp;
			    <span class="infoFormulaire"> * </span> Statut :
		        <select id="Id_statut" name="Id_statut" onchange="changeStatut()">
                    <option value="">' . STATUS_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $statut->getList() . '
                </select>
				&nbsp;&nbsp;
				Créateur :
                ' . $this->createur . '
				<br /><br />
				' . $htmlPerdu . '
				<div id="maj"></div>
				Commercial :
				<select name="commercial">
                    <option value="">' . MARKETING_PERSON_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $commercial->getList('COM') . '
					<option value="">-------------------------</option>
					' . $commercial->getList('OP') . '
					<option value="">-------------------------</option>
					' . $commercial->getList('FOR') . '
                </select>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				Apporteur :
				<select name="apporteur">
                    <option value="">Sélectionner un apporteur</option>
				    <option value="">-------------------------</option>
                    ' . $apporteur->getList('COM') . '
					<option value="">-------------------------</option>
					' . $apporteur->getList('OP') . '
					<option value="">-------------------------</option>
					' . $apporteur->getList('FOR') . '
                </select>
				<br /><br />	
				Rédacteur 1 :
		        <select name="redacteur1">
                    <option value="">' . EDITOR_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $redacteur1->getList('COM') . '
					<option value="">-------------------------</option>
					' . $redacteur1->getList('OP') . '
					<option value="">-------------------------</option>
					' . $redacteur1->getList('FOR') . '
                </select>
			    &nbsp;&nbsp;|&nbsp;&nbsp;
				Rédacteur 2 :
			    <select name="redacteur2">
                    <option value="">' . EDITOR_SELECT . '</option>
				    <option value="">-------------------------</option>
					' . $redacteur2->getList('COM') . '
					<option value="">-------------------------</option>
                    ' . $redacteur2->getList("OP") . '
					<option value="">-------------------------</option>
					' . $redacteur2->getList('FOR') . '
                </select>
			    <br /><br />
			    <div id="etatTech">
			    ' . $htmlEtatTech . '
				</div>
			</div>
			<br />
			<div class="clearer"><hr /></div><br />
			<h2 onclick="afficherCoordonnee();toggleZone(' . "'coordonnee'" . ')" class="cliquable"><img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_CONTACT_DETAILS_MODULE . '</div>\', FULLHTML);" onmouseout="return nd();"></img> Coordonnées et contact Client ou Prospect</h2><br />
			<div id="coordonnee">
			    <script>afficherCoordonnee();</script>
				<input type="hidden" name="Id_compte" value="' . $this->Id_compte . '">
				<input type="hidden" name="Id_contact1" value="' . $this->Id_contact1 . '">
				<input type="hidden" name="Id_contact2" value="' . $this->Id_contact2 . '">
				<input type="hidden" name="ouverture_compte" value="' . $this->ouverture_compte . '">
 		    </div>
			<div class="clearer"><hr /></div><br />
			<h2 onclick="toggleZone(' . "'planning'" . ')" class="cliquable">Planning</h2><br />
			<div id="planning">
				' . $planning->form($this->Id_type_contrat, $this->Id_pole) . '
			</div>
			<div class="clearer"><hr /></div><br />
			<h2 onclick="toggleZone(' . "'description'" . ')" class="cliquable">Description</h2><br />
			<div id="description">
				' . $description->form($this->Id_type_contrat, $this->Id_pole) . '
			</div>
			<div class="clearer"><hr /></div><br />
			<div id="envTech">
			</div>
			<h2 onclick="toggleZone(' . "'propcom'" . ')" class="cliquable">' . $titreCom . '</h2><br />
			<div id="propcom">
				' . $proposition->form($this->Id_type_contrat, $this->Id_pole) . '
			</div>
			' . $demandesRessources . '
			' . $formRessourceC . '
			' . $suiviCa . '
			' . $archives . '
			<div class="clearer"><hr /></div><br />
			<div class="submit">
                            <button type="submit" class="button save" value="' . SAVE_BUTTON . '" onclick="return verifAffaire(this.form)">' . SAVE_BUTTON . '</button>
			</div>
		</form>
		<script type="text/javascript">Event.observe(window, \'load\', function() {changeFormAffaire();})</script>
';
        return $html;
    }

    /**
     * Formulaire de création / modification des coordonnées
     *
     * @return string
     */
    public function contactDetailsForm() {
        if ($this->Id_compte) {
            $compte = CompteFactory::create(null, $this->Id_compte);
            $listCompte = $compte->getList();
            $infocompte = $compte->infoCompte();
            $contact1 = ContactFactory::create(null, 'CG-' . $this->Id_contact1);
            $listContact1 = $contact1->getList($this->Id_compte);
            $contact2 = ContactFactory::create(null, 'CG-' . $this->Id_contact2);
            $listContact2 = $contact2->getList($this->Id_compte);
        }
        $ouv[Affaire::isNewCustomer($this->Id_affaire)] = 'checked="checked"';
        $html = '
		    <div class="left">
			    <span class="infoFormulaire"> * </span><input id="prefix" type="text" size="2" onkeyup="prefixCompte()">
                <span id="compte">
				Compte :
	            <select id="Id_compte" name="Id_compte" onchange="changeCompte(this.value); infoCompte(this.value)">
                    <option value="">' . CUSTOMERS_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $listCompte . '
                </select>
				</span>
			    &nbsp;
				<br /><br />
				<div id="contact"> 
				    <span class="infoFormulaire"> * </span> Contact 1 :
		            <select id="Id_contact1" name="Id_contact1">
                        <option value="">' . CONTACT_SELECT . '</option>
				        <option value="">-------------------------</option>
                        ' . $listContact1 . '
                    </select>
			        <br /><br />
				    Contact 2 :
			        <select id="Id_contact2" name="Id_contact2">
                        <option value="">' . CONTACT_SELECT . '</option>
				        <option value="">-------------------------</option>
                        ' . $listContact2 . '
                    </select>
					<br /><br />
			        Ouverture de compte : 
			        ' . YES . ' <input type="radio" name="ouverture_compte" value="1" ' . $ouv['1'] . ' />
			        ' . NO . ' <input type="radio" name="ouverture_compte" value="0" ' . $ouv['0'] . ' />
		        </div>
		    </div>		 
			<div class="right" id="infoCompte">
			    ' . $infocompte . '
            </div><br /><br />
';
        return $html;
    }

    /**
     * Affichage du lien vers la page de demande de recrutement
     *
     * @return string
     */
    public function recruitmentRequestLink() {
        $html = '<input type="button" value="Demande de recrutement au service RH" onclick="javascript:ouvre_popup(\'../membre/index.php?a=demander_ressource&Id_affaire=' . $this->Id_affaire . '\')">';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ', Id_compte = "' . mysql_real_escape_string($this->Id_compte) . '", Id_contact1 = "' . mysql_real_escape_string($this->Id_contact1) . '" ,
		Id_contact2 = "' . mysql_real_escape_string($this->Id_contact2) . '", commercial = "' . mysql_real_escape_string($this->commercial) . '", redacteur1 = "' . mysql_real_escape_string($this->redacteur1) . '",
		redacteur2 = "' . mysql_real_escape_string($this->redacteur2) . '", Id_statut = ' . mysql_real_escape_string((int) $this->Id_statut) . ', Id_agence = "' . mysql_real_escape_string($this->Id_agence) . '",
		date_modification="' . mysql_real_escape_string($this->date_modification) . '", nb_jour_estime="' . mysql_real_escape_string($this->nb_jour_estime) . '", resp_qualif = "' . mysql_real_escape_string($this->resp_qualif) . '",
		sdm = "' . mysql_real_escape_string($this->sdm) . '", resp_tec1 = "' . mysql_real_escape_string($this->resp_tec1) . '", resp_tec2 = "' . mysql_real_escape_string($this->resp_tec2) . '",
		doc = "' . mysql_real_escape_string($this->doc) . '", ouverture_compte = ' . mysql_real_escape_string((int) $this->ouverture_compte) . ', Id_type_contrat = ' . mysql_real_escape_string((int) $this->Id_type_contrat) . ',
		Id_session = ' . mysql_real_escape_string((int) $this->Id_session) . ',apporteur = "' . mysql_real_escape_string($this->apporteur) . '"';
        if ($this->Id_affaire) {
            $requete = 'UPDATE affaire ' . $set . ' WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '';
        } else {
            $requete = 'INSERT INTO affaire ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation= "' . mysql_real_escape_string($this->date_creation) . '"';
        }
        $db->query($requete);

        $_SESSION['affaire'] = ($this->Id_affaire == '') ? mysql_insert_id() : (int) $this->Id_affaire;
        if ($this->Id_affaire) {
            $db->query('DELETE FROM affaire_competence WHERE Id_affaire=' . mysql_real_escape_string((int) $this->Id_affaire) . '');
            $db->query('DELETE FROM affaire_langue WHERE Id_affaire=' . mysql_real_escape_string((int) $this->Id_affaire) . '');
            $db->query('DELETE FROM affaire_exigence WHERE Id_affaire=' . mysql_real_escape_string((int) $this->Id_affaire) . '');
            $db->query('UPDATE affaire_pole SET Id_pole=' . mysql_real_escape_string((int) $this->Id_pole) . ' WHERE Id_affaire =' . mysql_real_escape_string((int) $this->Id_affaire) . '');
        } elseif (!$this->Id_affaire) {
            $db->query('INSERT INTO affaire_pole SET Id_affaire=' . mysql_real_escape_string($_SESSION['affaire']) . ', Id_pole=' . mysql_real_escape_string((int) $this->Id_pole) . '');

            if ($this->Id_pole == 4) {
                self::informPoleMail($_SESSION['affaire'], $this->Id_pole, $this->Id_agence, $this->Id_statut, $this->createur);
            }
        }
        $nb_competence = count($this->competence);
        $i = 0;
        while ($i < $nb_competence) {
            $db->query('INSERT INTO affaire_competence SET Id_affaire=' . mysql_real_escape_string($this->Id_affaire) . ', Id_competence=' . mysql_real_escape_string((int) $this->competence[$i]) . ', Id_niveau_competence=' . mysql_real_escape_string((int) $this->niveau_competence[$i]) . '');
            ++$i;
        }
        $nb_competence = count($this->langue);
        $i = 0;
        while ($i < $nb_langue) {
            $db->query('INSERT INTO affaire_langue SET Id_affaire=' . mysql_real_escape_string($this->Id_affaire) . ', Id_langue=' . mysql_real_escape_string((int) $this->langue[$i]) . ', Id_niveau_langue=' . mysql_real_escape_string((int) $this->niveau_langue[$i]) . '');
            ++$i;
        }
        $nb_exigence = count($this->exigence);
        $i = 0;
        while ($i < $nb_exigence) {
            $db->query('INSERT INTO affaire_exigence SET Id_affaire=' . mysql_real_escape_string($this->Id_affaire) . ', Id_exigence=' . mysql_real_escape_string((int) $this->exigence[$i]) . '');
            ++$i;
        }
        self::updateStatut($this->Id_statut, $_SESSION['affaire'], $this->Id_raison_perdue, $this->commentaire_perdue);
    }

    /**
     * Recherche d'une affaire
     *
     * @param int Identifiant du type de contrat
     * @param int Identifiant du compte client
     * @param int Identifiant du statut
     * @param int Identifiant du commercial
     * @param int Identifiant du redacteur
     * @param int Identifiant du pôle
     * @param string Identifiant de l'agence
     * @param string Valeur de la ville du lieu de mission
     * @param string Identifiant de l'agence
     * @param double Chiffre d'Affaire
     * @param string Type de comparaison du chiffre d'affaire : inférieur, supérieur
     * @param double Marge de l'Affaire
     * @param string Type de comparaison de la marge : inférieur, supérieur
     * @param string Identifiant du contact
     * @param int Identifiant de l'intitulé
     * @param date Date inférieure à date modification de l'affaire
     * @param date Date supérieure à date modification de l'affaire
     * @param int Nombre d'affaires à afficher
     *
     * @return string
     */
    public static function search($Id_type_contrat, $Id_compte, $Id_statut, $commercial, $redacteur, $Id_pole, $Id_agence, $ville, $Id_affaire, $ca, $type_ca, $marge = null, $type_marge = null, $Id_contact, $Id_intitule, $debut, $fin, $nb=50, $Id_ressource, $Id_raison_perdue, $motcle = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat', 'Id_compte', 'Id_statut', 'commercial', 'redacteur', 'Id_pole', 'Id_agence', 'ville', 'Id_affaire', 'ca', 'type_ca', 'marge', 'type_marge', 'Id_contact', 'Id_intitule', 'debut', 'fin', 'nb', 'Id_ressource', 'Id_raison_perdue', 'motcle', 'output');
        $columns = array(array('Id','id_affaire'), array('Compte','none'), array('Contact','none'),
                         array('Intitulé','intitule'),array('Statut','statut'),array('Début','date_debut'), array('Fin commande','date_fin_commande'),
                         array('Commercial','commercial'), array('Pôle','pole'), array('Type contrat','type_contrat'), array('CA','none'));
        $db = connecter();
        if ($Id_raison_perdue) {
            $j1 = 'INNER JOIN historique_statut hs ON a.Id_affaire = hs.Id_affaire';
        }
        if (($ca && $type_ca) || ($marge && $type_marge) || $Id_ressource) {
            $j2 = ' INNER JOIN proposition p ON a.Id_affaire=p.Id_affaire';
        }
        if ($Id_ressource) {
            $j3 = ' INNER JOIN contrat_delegation cd ON cd.Id_affaire=a.Id_affaire';
        }
        if ($motcle) {
            $j4 = ' LEFT JOIN listing_affaires la ON la.Id_affaire = a.Id_affaire';    
        }
        $requete = 'SELECT DISTINCT a.Id_affaire,a.Id_compte,a.commercial,Id_intitule,
                (SELECT libelle FROM intitule WHERE Id_intitule=d.Id_intitule) AS intitule,a.Id_statut,
                (SELECT trig FROM statut WHERE Id_statut=a.Id_statut) AS statut, Id_contact1,
                (SELECT trig FROM com_type_contrat WHERE Id_type_contrat=a.Id_type_contrat) AS type_contrat, Id_type_contrat,
                (SELECT date_debut FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_debut,
                (SELECT DATE_FORMAT(date_debut, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_debut_fr,
                (SELECT date_fin_commande FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_fin_commande,
                (SELECT DATE_FORMAT(date_fin_commande, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_fin_commande_fr,
                a.archive,resume,
                 (SELECT trig FROM pole WHERE Id_pole=ap.Id_pole) AS pole, Id_pole FROM affaire a
		 INNER JOIN description d ON a.Id_affaire=d.Id_affaire
		 INNER JOIN affaire_pole ap ON a.Id_affaire=ap.Id_affaire 
		 ' . $j1 . ' ' . $j2 . ' ' . $j3 . ' ' . $j4 . ' WHERE a.archive=0 ';
        if ($Id_type_contrat) {
            $requete .= ' AND Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_compte) {
            $requete .= ' AND a.Id_compte ="' . $Id_compte . '"';
        }
        if ($Id_contact) {
            $requete .= ' AND (Id_contact1 ="' . $Id_contact . '" OR Id_contact2 ="' . $Id_contact . '")';
        }
        if ($Id_statut) {
            $requete .= ' AND a.Id_statut =' . (int) $Id_statut . '';
        }
        if ($commercial) {
            $requete .= ' AND a.commercial ="' . $commercial . '"';
        }
        if ($redacteur) {
            $requete .= ' AND (redacteur1 ="' . $redacteur . '" OR redacteur2 ="' . $redacteur . '")';
        }
        if ($debut && $fin) {
            $requete .= ' AND a.date_modification BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        if ($Id_agence) {
            $requete.= ' AND Id_agence LIKE "%' . $Id_agence . '%"';
        }
        if ($Id_pole) {
            $requete.= ' AND ap.Id_pole=' . (int) $Id_pole;
        }
        if ($ville) {
            $requete.= ' AND ville LIKE "%' . $ville . '%"';
        }
        if ($Id_affaire) {
            $requete.= ' AND a.Id_affaire =' . (int) $Id_affaire;
        }
        if ($Id_intitule) {
            $requete.= ' AND Id_intitule =' . (int) $Id_intitule;
        }
        if ($ca && $type_ca) {
            $requete.= ' AND chiffre_affaire ' . $type_ca . '' . (float) $ca . '';
        }
        if ($marge && $type_marge) {
            $requete.= ' AND marge ' . $type_marge . '' . (float) $marge . '';
        }
        if ($Id_ressource) {
            $requete.= ' AND cd.Id_ressource="' . $Id_ressource . '"';
        }
        if ($Id_raison_perdue) {
            $requete.= ' AND hs.Id_raison="' . $Id_raison_perdue . '"';
        }
        if ($motcle) {
            $motclesplit = explode(' ', utf8_decode($motcle));
            foreach ($motclesplit as $i) {
                $requete .= ' AND ( la.client LIKE "%' . $i . '%"
				OR la.contact LIKE "%' . $i . '%"
				OR la.intitule LIKE "%' . $i . '%"
                                OR la.collaborateur LIKE "%' . $i . '%")';
            }
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
            $paramsOrder .= 'orderBy=id_affaire';
            $orderBy = 'id_affaire';
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
                'onclick' => 'afficherAffaire({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterAffaire&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy2 = $value[1];
                    if($value[1] == $output['orderBy'])
                        if($output['direction'] == 'DESC') {
                            $direction2 = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction2 = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!$output['orderBy']) {
                        $direction2 = 'ASC';
                        $img['id_affaire'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction2 = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onClick="afficherAffaire({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy2 . '\', \'direction\' : \'' . $direction2 . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_affaire'] . '</td>
                            <td>' . self::showCustomer($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showContact($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showTitle($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['statut'] . '</td>
                            <td>' . $ligne['date_debut_fr'] . '</td>
                            <td>' . $ligne['date_fin_commande_fr'] . '</td>
                            <td>' . $ligne['commercial'] . '</td>
                            <td>' . $ligne['pole'] . '</td>
                            <td>' . $ligne['type_contrat'] . '</td>
                            <td>' . self::showRevenue($ligne, array('csv' => false)) . '</td>
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
            header('Content-Disposition: attachment; filename="affaires.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {     
                echo $ligne['id_affaire'] . ';';
                echo '"' . self::showCustomer($ligne, array('csv' => true)) . '";';
                echo '"' . self::showContact($ligne, array('csv' => true)) . '";';
                echo '"' . self::showTitle($ligne, array('csv' => true)) . '";';
                echo '"' . $ligne['statut'] . '";';
                echo $ligne['date_debut_fr'] . ';';
                echo $ligne['date_fin_commande_fr'] . ';';
                echo '"' . $ligne['commercial'] . '";';
                echo '"' . $ligne['pole'] . '";';
                echo '"' . $ligne['type_contrat'] . '";';
                echo self::showRevenue($ligne, array('csv' => true)) . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }
    
    /**
     * Affiche un tableau permettant d'éditer les pondérations
     *
     * @param int Identifiant du type de contrat
     * @param int Identifiant du statut
     * @param int Identifiant du commercial
     * @param int Identifiant du pôle
     * 
     * @return string
     */
    public static function editRevenueCase($Id_type_contrat = null, $Id_statut = '3,4', $commercial = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat', 'Id_statut_ponderation', 'commercial', 'Id_pole', 'output');
        $columns = array(array('Id','id_affaire'), array('Statut','statut'), array('Date limite','date_limite'), array('Date demande','date_demande'),
                         array('Agence','agence'), array('Commercial','commercial'), array('Compte','none'), array('Contact','none'),
                         array('Intitulé','intitule'),array('Date début','date_debut'), array('Date fin','date_fin_commande'),
                         array('CA Total','none'), array('% pondération','none'), array('CA pondéré','none'));
        $db = connecter();
        $requete = 'SELECT DISTINCT a.Id_affaire, a.Id_compte, a.commercial, a.Id_contact1, 
        (SELECT libelle FROM intitule WHERE Id_intitule=d.Id_intitule) AS intitule, 
        (SELECT trig FROM statut WHERE Id_statut=a.Id_statut) AS statut, 
        (SELECT date_limite FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_limite, 
        (SELECT DATE_FORMAT(date_limite, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_limite_fr, 
        (SELECT date_demande FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_demande, 
        (SELECT DATE_FORMAT(date_demande, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_demande_fr, 
        (SELECT date_debut FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_debut,
        (SELECT DATE_FORMAT(date_debut, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_debut_fr,
        (SELECT date_fin_commande FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_fin_commande,
        (SELECT DATE_FORMAT(date_fin_commande, "%d-%m-%Y") FROM planning pl WHERE pl.Id_affaire = a.Id_affaire) AS date_fin_commande_fr,
        (SELECT libelle FROM agence ag WHERE ag.Id_agence = a.Id_agence) AS agence
        FROM affaire a
        INNER JOIN description d ON a.Id_affaire=d.Id_affaire
        INNER JOIN affaire_pole ap ON a.Id_affaire=ap.Id_affaire
        WHERE a.archive=0 ';
        if ($Id_type_contrat) {
            $requete .= ' AND a.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }        
        if ($Id_statut) {
            $requete .= '  AND a.Id_statut IN (' . $Id_statut . ')';
        }
        else {
            $requete .= '  AND a.Id_statut IN (3,4)';
        }
        if ($commercial) {
            $requete .= ' AND a.commercial ="' . $commercial . '"';
        }
        if ($Id_pole) {
            $requete.= ' AND ap.Id_pole=' . (int) $Id_pole;
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
            $paramsOrder .= 'orderBy=date_demande';
            $orderBy = 'date_demande';
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
                'onclick' => 'afficherCAAffaire({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterCAAffaire&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy2 = $value[1];
                    if($value[1] == $output['orderBy'])
                        if($output['direction'] == 'DESC') {
                            $direction2 = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction2 = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!$output['orderBy']) {
                        $direction2 = 'DESC';
                        $img['date_demande'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction2 = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onClick="afficherCAAffaire({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy2 . '\', \'direction\' : \'' . $direction2 . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_affaire'] . '</td>
                            <td>' . $ligne['statut'] . '</td>
                            <td>' . $ligne['date_limite_fr'] . '</td>
                            <td>' . $ligne['date_demande_fr'] . '</td>
                            <td>' . $ligne['agence'] . '</td>
                            <td>' . $ligne['commercial'] . '</td>
                            <td>' . self::showCustomer($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showContact($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showTitle($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['date_debut_fr'] . '</td>
                            <td>' . $ligne['date_fin_commande_fr'] . '</td>
                            <td>' . self::showRevenue($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showWeighting($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showWeightingRevenue($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtonsWeighting($ligne) . '</td>
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
            header('Content-Disposition: attachment; filename="affaires.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {     
                echo $ligne['id_affaire'] . ';';
                echo '"' . $ligne['statut'] . '";';
                echo $ligne['date_limite_fr'] . ';';
                echo $ligne['date_demande_fr'] . ';';
                echo '"' . $ligne['agence'] . '";';
                echo '"' . $ligne['commercial'] . '";';
                echo '"' . self::showCustomer($ligne, array('csv' => true)) . '";';
                echo '"' . self::showContact($ligne, array('csv' => true)) . '";';
                echo '"' . self::showTitle($ligne, array('csv' => true)) . '";';
                echo $ligne['date_debut_fr'] . ';';
                echo $ligne['date_fin_commande_fr'] . ';';
                echo self::showRevenue($ligne, array('csv' => true)) . ';';
                echo self::showWeighting($ligne, array('csv' => true)) . ';';
                echo self::showWeightingRevenue($ligne, array('csv' => true)) . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Suppression d'une affaire
     */
    public function delete() {
        $db = connecter();
        //récupération du numéro de la session associée à l'affaire avant la délétion de l'affaire dans la base
        $ligne1 = $db->query('SELECT Id_session FROM affaire WHERE Id_affaire= ' . mysql_real_escape_string((int) $this->Id_affaire))->fetchRow();

        $db->query('DELETE FROM affaire WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM planning WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM description WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM decision WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM affaire_competence WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM affaire_langue WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM affaire_pole WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM affaire_exigence WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM analyse_commerciale WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM analyse_risque WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        $db->query('DELETE FROM historique_statut WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));

        $result = $db->query('SELECT Id_environnement FROM environnement WHERE Id_affaire= ' . mysql_real_escape_string((int) $this->Id_affaire));
        while ($ligne = $result->fetchRow()) {
            $environnement = new Environnement($ligne->id_environnement, array());
            $environnement->delete();
        }
        $result = $db->query('SELECT Id_proposition FROM proposition WHERE Id_affaire= ' . mysql_real_escape_string((int) $this->Id_affaire));
        while ($ligne = $result->fetchRow()) {
            $proposition = new Proposition($ligne->id_proposition, array());
            $proposition->delete();
        }
        $result = $db->query('SELECT Id_contrat_delegation FROM contrat_delegation WHERE Id_affaire= ' . mysql_real_escape_string((int) $this->Id_affaire));
        while ($ligne = $result->fetchRow()) {
            $contrat_delegation = new ContratDelegation($ligne->id_contrat_delegation, array());
            $contrat_delegation->delete();
        }
        if ($this->doc) {
            if (is_file(PROPALE_DIR . $this->doc)) {
                unlink(PROPALE_DIR . $this->doc);
            }
        }
        //dans le cas d'une affaire du pôle formation : mise à jour des données de la session associées à l'affaire après mise à jour de la table affaire
        if ($ligne1->id_session) {
            $session = new Session($ligne1->id_session, array());
            $session->updateSession();
        }
    }

    /**
     * Archivage d'une affaire
     */
    public function archive() {
        $db = connecter();
        //récupération du numéro de la session associée à l'affaire avant la délétion de l'affaire dans la base
        $ligne1 = $db->query('SELECT Id_session FROM affaire WHERE Id_affaire= ' . mysql_real_escape_string((int) $this->Id_affaire))->fetchRow();
        $db->query('UPDATE affaire SET archive="1", Id_session="" WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
        //dans le cas d'une affaire du pôle formation : mise à jour des données de la session associées à l'affaire après mise à jour de la table affaire
        if ($ligne->id_session) {
            $session = new Session($ligne1->id_session, array());
            $session->updateSession();
        }
    }

    /**
     * Desarchivage d'une affaire
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE affaire SET archive="0" WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire));
    }

    /**
     * Affichage des filtres de recherche pour les affaires
     *
     * @return string
     */
    public static function searchForm() {
        if ($_SESSION['filtre']['commercial'] == '') {
            $_SESSION['filtre']['commercial'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        if ($_SESSION['filtre']['Id_statut'] == ''
                && (!array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, Utilisateur::getGroupAdList('COM'))
                && !array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, Utilisateur::getGroupAdList('FOR'))
                && !array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, Utilisateur::getGroupAdList('OP')))) {
            $statut = new Statut('8', array());
        } else {
            $statut = new Statut($_SESSION['filtre']['Id_statut'], array());
        }
        $type_contrat = new TypeContrat($_SESSION['filtre']['Id_type_contrat'], array());
        $pole = new Pole($_SESSION['filtre']['Id_pole'], array());
        $commercial = new Utilisateur($_SESSION['filtre']['commercial'], array());
        $redacteur = new Utilisateur($_SESSION['filtre']['redacteur'], array());
        $agence = new Agence($_SESSION['filtre']['Id_agence'], array());
        $compte = CompteFactory::create('CG', (is_null($_SESSION['filtre']['Id_compte']) ? '' : $_SESSION['filtre']['Id_compte']));
        $contact = ContactFactory::create('CG', (is_null($_SESSION['filtre']['Id_contact']) ? '' : $_SESSION['filtre']['Id_contact']));
        $intitule = new Intitule($_SESSION['filtre']['Id_intitule'], array());
        $ressourceMat = RessourceFactory::create('MAT', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceAGC = RessourceFactory::create('CAN_AGC', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSal = RessourceFactory::create('SAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSt = RessourceFactory::create('ST', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceInt = RessourceFactory::create('INT', $_SESSION['filtre']['Id_ressource'], array());
        $html = '
			Nb : 
			<select id="nb" onchange="afficherAffaire()">
				<option value="50">50</option>
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="500">Toutes</option>
			</select>
			&nbsp;
			Id : <input id="Id_affaire" type="text" onkeyup="afficherAffaire()" size="2" value=' . $_SESSION['filtre']['Id_affaire'] . ' >
			&nbsp;&nbsp;
			CA : 
			<select id="type_ca">
				<option value="">----</option>
				<option value="=">=</option>
				<option value=">">></option>
				<option value="<"><</option>
			</select>
			&nbsp;&nbsp;
			<input id="ca" type="text" onkeyup="afficherAffaire()" size="8" /> &euro;
			&nbsp;&nbsp;
			<select id="Id_statut" onchange="changeStatutSearch();afficherAffaire();">
				<option value="">Par statut</option>
				<option value="">----------------------------</option>
				' . $statut->getList() . '
			</select>
			&nbsp;&nbsp;
			<span id="maj"></span>
			&nbsp;&nbsp;
			<select id="Id_type_contrat" onchange="afficherAffaire()">
				<option value="">Par type de contrat</option>
				<option value="">----------------------------</option>
				' . $type_contrat->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_pole" onchange="afficherAffaire()">
				<option value="">Par pôle</option>
				<option value="">----------------------------</option>
				' . $pole->getList() . '
			</select>
                        Mots clés : <input id="motcleaffaire" onmouseover="return overlib(\'<div class=commentaire>« Un espace correspond à ET. ex : unix solaris »</div>\', FULLHTML);" onmouseout="return nd();" type="text" onkeyup="afficherAffaire()" value="' . $_SESSION['filtre']['motcleaffaire'] . '" />
			<br /><br />
			Ville : <input id="ville" type="text" onkeyup="afficherAffaire()" value=' . $_SESSION['filtre']['ville'] . ' >
			&nbsp;&nbsp;
			<select id="Id_compte" onchange="afficherAffaire()">
				<option value="">Par compte</option>
				<option value="">----------------------------</option>
				' . $compte->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_contact" onchange="afficherAffaire()">
				<option value="">Par contact</option>
				<option value="">----------------------------</option>
				' . $contact->getList() . '
			</select>
			&nbsp;&nbsp;				
			<select id="commercial" onchange="afficherAffaire()">
				<option value="">Par commercial</option>
				<option value="">----------------------------</option>
				' . $commercial->getList('COM') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('OP') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('FOR') . '
			</select>
			<br /><br />
			<select id="redacteur" onchange="afficherAffaire()">
				<option value="">Par rédacteur</option>
				<option value="">----------------------------</option>
				' . $redacteur->getList('COM') . '
				<option value="">-------------------------</option>
				' . $redacteur->getList('OP') . '
				<option value="">-------------------------</option>
				' . $redacteur->getList('FOR') . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_agence" onchange="afficherAffaire()">
				<option value="">Par agence</option>
				<option value="">----------------------------</option>
				' . $agence->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_intitule" onchange="afficherAffaire()">
				<option value="">Par intitulé</option>
				<option value="">----------------------------</option>
				' . $intitule->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_ressource" onchange="afficherAffaire()">
				<option value="">Par ressource</option>
                                ' . $ressourceMat->getList() . '
                                ' . $ressourceAGC->getList() . '
                                ' . $ressourceSal->getList() . '
                                ' . $ressourceSt->getList() . '
                                ' . $ressourceInt->getList() . '
			</select>
			&nbsp;
			du <input id="debut" type="text" onfocus="showCalendarControl(this)" size="8" value=' . $_SESSION['filtre']['debut'] . ' >
			&nbsp;
			au <input id="fin" type="text" onfocus="showCalendarControl(this)"  size="8" value=' . $_SESSION['filtre']['fin'] . ' >
			&nbsp;&nbsp;
			<input type="button" onclick="afficherAffaire()" value="Go !">
';
        if ($_SESSION['filtre']['Id_statut'] == 6) {
            $html .= '<script type="text/javascript">changeStatutSearch()</script>';
        }
        return $html;
    }
    
    /**
     * Affichage des filtres de recherche pour l'édition des CA
     *
     * @return string
     */
    public static function searchRevenueCaseForm() {
        if ($_SESSION['filtre']['commercial'] == '') {
            $_SESSION['filtre']['commercial'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        if ($_SESSION['filtre']['Id_statut_ponderation'] == '') {
            $statut = Statut::getListMultiple(array(3, 4), array(3,4));
        } else {
            $statuts = split(',', $_SESSION['filtre']['Id_statut_ponderation']);
            $statut = Statut::getListMultiple($statuts, array(3,4));
        }
        $type_contrat = new TypeContrat($_SESSION['filtre']['Id_type_contrat'], array());
        $pole = new Pole($_SESSION['filtre']['Id_pole'], array());
        $commercial = new Utilisateur($_SESSION['filtre']['commercial'], array());
        $html = '
			<select id="Id_type_contrat" onchange="afficherCAAffaire()">
				<option value="">Par type de contrat</option>
				<option value="">----------------------------</option>
				' . $type_contrat->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_pole" onchange="afficherCAAffaire()">
				<option value="">Par pôle</option>
				<option value="">----------------------------</option>
				' . $pole->getList() . '
			</select>
			&nbsp;&nbsp;				
			<select id="commercial" onchange="afficherCAAffaire()">
				<option value="">Par commercial</option>
				<option value="">----------------------------</option>
				' . $commercial->getList('COM') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('OP') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('FOR') . '
			</select>
                        &nbsp;&nbsp;
                        <select id="Id_statut_ponderation" onchange="afficherCAAffaire();" multiple="multiple">
				<option value="">Par statut</option>
				<option value="">----------------------------</option>
				' . $statut . '
			</select>

			&nbsp;&nbsp;
			<input type="button" onclick="afficherCAAffaire()" value="Go !">
                        &nbsp;&nbsp;&nbsp;&nbsp;<b><a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterRefPonderation\')">Référentiel de Pondération</a></b>
';
        return $html;
    }

    /**
     * Affichage de l'identifiant du type de contrat
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdTypeContrat($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_type_contrat FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_type_contrat;
    }

    /**
     * Affichage de l'identifiant du pole de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdPole($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_pole FROM affaire_pole WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_pole;
    }

    /**
     * Affichage de l'identifiant de l'agence
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdAgence($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_agence FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_agence;
    }

    /**
     * Affichage de l'identifiant du statut de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdStatut($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_statut FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_statut;
    }

    /**
     * Affichage de l'identifiant du compte de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdCompte($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_compte FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_compte;
    }

    /**
     * Affichage de l'identifiant du contact1 de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return string
     */
    public static function getIdContact1($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_contact1 FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_contact1;
    }

    /**
     * Affichage de l'identifiant du planning
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIdPlanning($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_planning FROM planning WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->id_planning;
    }

    /**
     * Affichage de ou des numeros d'affaires CEGID associés à l'affaire AGC.
     *
     * @param int Identifiant de l'affaire AGC
     * @param date permet d'extraire les affaires CEGID créées après cette date
     * @param date permet d'extraire les affaires CEGID créées avant cette date
     *
     */
    public static function getIdAffaireCEGID($Id_affaire, $debut = '', $tableShow = false) {
        $db = connecter_cegid();
        if ($debut) {
            $req = ' AND AFF_DATEFIN >= "' . mysql_real_escape_string($debut) . '"';
        }

        $result = $db->query('SELECT DISTINCT AFF_AFFAIRE, AFF_DATEDEBUT FROM AFFAIRE 
			WHERE (AFF_CHARLIBRE1 LIKE "%' . mysql_real_escape_string($Id_affaire) . '%" OR AFF_CHARLIBRE2
			LIKE "%' . $Id_affaire . '%" OR AFF_CHARLIBRE3 LIKE "%' . mysql_real_escape_string($Id_affaire) . '%") ' . $req . ' ');
        
        $i = 0;
        while ($ligne = $result->fetchRow()) {
            if($i != 0) $html .= ' ; ';
            $html .= $ligne->aff_affaire;
            $i++;
        }
        $nb = strlen($html);
        if (strlen($html) > 60 && $tableShow === false) {
            return substr($html, 0, 60) . '...';
        } else {
            return $html;
        }
    }

    /**
     * Affichage du formulaire de recherche d'une affaire par statut
     *
     * @return string
     */
    public static function searchCasesStatusForm() {
        $db = connecter();
        $result = $db->query('SELECT * FROM statut');
        while ($ligne = $result->fetchRow()) {
            $html .= '<p class="center"><a href="index.php?a=consulterAffaire&amp;Id_statut=' . $ligne->id_statut . '">Affaire(s) ' . $ligne->libelle . '</a></p>';
        }
        return $html;
    }

    /**
     * Mise à jour de la date du changement de statut de l'affaire
     *
     * @param int Identifiant du statut
     * @param int Identifiant de l'affaire
     * @param int Identifiant de la raison
     * @param string Commentaire sur le changement du statut
     *
     */
    public static function updateStatut($Id_statut, $Id_affaire, $Id_raison, $commentaire) {
        $db = connecter();
        if ($Id_statut != self::lastStatus($Id_affaire)) {
            $db->query('INSERT INTO historique_statut SET date="' . mysql_real_escape_string(DATETIME) . '", Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ',
			Id_raison=' . mysql_real_escape_string((int) $Id_raison) . ', commentaire="' . mysql_real_escape_string($commentaire) . '", Id_statut=' . mysql_real_escape_string((int) $Id_statut) . ',
			Id_utilisateur = "' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
        }
    }

    /**
     * Suppression d'un historique de statut d'affaire
     *
     */
    public static function deleteStatusHistory($Id_affaire, $Id_statut, $date) {
        $db = connecter();
        $db->query('DELETE FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ' AND Id_statut=' . mysql_real_escape_string((int) $Id_statut) . ' AND date="' . mysql_real_escape_string($date) . '"');
    }

    /**
     * Validation d'un historique de statut d'affaire
     *
     */
    public static function validStatusHistory($Id_affaire, $Id_statut, $date, $Id_utilisateur) {
        $db = connecter();
        $hms = $db->query('SELECT DATE_FORMAT(now(),"%H:%i:%s") as hms')->fetchRow()->hms;
        $req = 'INSERT INTO historique_statut
		SET Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ', Id_statut=' . mysql_real_escape_string((int) $Id_statut) . ', date="' . mysql_real_escape_string(DateMysqltoFr($date, "mysql")) . ' ' . $hms . '", Id_utilisateur="' . mysql_real_escape_string($Id_utilisateur) . '"';
        echo '<p class="infoFormulaire">L\'historique des changements de statut a été mis à jour.</p>';
        $db->query($req);
    }

    /**
     * Affichage du formulaire de l'historique des statuts de l'affaire
     *
     * @return string
     */
    public function formStatusHistory() {
        $db = connecter();
        $result = $db->query('SELECT Id_statut,date,commentaire,Id_utilisateur,nom FROM historique_statut LEFT
		OUTER JOIN raison_perdue ON historique_statut.Id_raison=raison_perdue.Id_raison_perdue 
		WHERE Id_affaire =' . mysql_real_escape_string((int) $this->Id_affaire) . ' ORDER BY date DESC');
        $html = '
		    <div id="historiqueStatut">
		    <table class="sortable">
			    <tr>
				    <th>Statut</th>
					<th>Date</th>
					<th>Créateur</th>					
					<th>Raison</th>
					<th>Commentaire</th>
				</tr>';
        $i = 1;
        while ($ligne = $result->fetchRow()) {
            $statut = new Statut($ligne->id_statut, array());
            $createur = new Utilisateur($ligne->id_utilisateur, array());
            $html .= '
			    <tr>
				    <td>
		                <select id="histoStatut' . $i . '">
                            <option value="">' . STATUS_SELECT . '</option>
				            <option value="">-------------------------</option>
                            ' . $statut->getList() . '
                        </select>
					</td>
					<td><input type="text" id="histoDate' . $i . '" onfocus="showCalendarControl(this)" value="' . FormatageDate(substr($ligne->date, 0, 10)) . '" size="8"></td>
					<td>
				        <select id="histoIdUtilisateur' . $i . '">
                            <option value="">' . MARKETING_PERSON_SELECT . '</option>
				            <option value="">-------------------------</option>
                            ' . $createur->getList('COM') . '
					        <option value="">-------------------------</option>
					        ' . $createur->getList('OP') . '
					        <option value="">-------------------------</option>
					        ' . $createur->getList('FOR') . '
                        </select>					
					</td>
					<td>' . $ligne->nom . '</td>
					<td>' . $ligne->commentaire . '</td>
					<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer ce statut ? \')) { deleteHistoriqueStatut(\'' . $this->Id_affaire . '\',\'' . $ligne->id_statut . '\',\'' . $ligne->date . '\') }" /></td>
				    <td width="5%" height="20"><input type="button" class="boutonValider" onclick="if (confirm(\'Valider le statut ?\')) { deleteHistoriqueStatut(\'' . $this->Id_affaire . '\',\'' . $ligne->id_statut . '\',\'' . $ligne->date . '\');validHistoriqueStatut(\'' . $this->Id_affaire . '\',\'' . $i . '\');}" /></td>
				</tr>';
            ++$i;
        }
        $html .= '</table></div>';
        return $html;
    }

    /**
     * Affichage de l'historique des statuts de l'affaire
     *
     * @return string
     */
    public function statusHistory() {
        $db = connecter();
        $result = $db->query('SELECT Id_statut,date,commentaire,Id_utilisateur,nom FROM historique_statut LEFT
		OUTER JOIN raison_perdue ON historique_statut.Id_raison=raison_perdue.Id_raison_perdue 
		WHERE Id_affaire =' . mysql_real_escape_string((int) $this->Id_affaire) . ' ORDER BY date DESC');
        $html = '
		    <div id="historiqueStatut">
		    <table class="sortable">
			    <tr>
				    <th>Statut</th>
					<th>Date</th>
					<th>Raison</th>
					<th>Commentaire</th>
					<th>Créateur</th>
				</tr>';
        while ($ligne = $result->fetchRow()) {
            $html .= '
			    <tr>
				    <td>' . Statut::getLibelle($ligne->id_statut) . '</td>
					<td>' . $ligne->date . '</td>
					<td>' . $ligne->nom . '</td>
					<td>' . $ligne->commentaire . '</td>
					<td>' . $ligne->id_utilisateur . '</td>
				</tr>';
        }
        $html .= '</table></div>';
        return $html;
    }

    /**
     * Affiche l'identifiant de la dernière proposition associée à l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function lastProposition($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_proposition FROM proposition WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ' ORDER BY Id_proposition DESC LIMIT 1')->fetchRow()->id_proposition;
    }

    /**
     * Affiche l'identifiant du dernier statut associé à l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function lastStatus($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT Id_statut FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . '
		AND date=(SELECT max(date) FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ')')->fetchRow()->id_statut;
    }

    /**
     * Affiche la raison de la perte de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return string
     */
    public static function lostCaseReason($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT commentaire FROM historique_statut WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . ' AND Id_statut=6')->fetchRow()->commentaire;
    }

    /**
     * Consultation des informations d'une affaire
     *
     * @return string
     */
    public function consultation() {
        $compte = CompteFactory::create(null, $this->Id_compte);
        $contact1 = ContactFactory::create(null, 'CG-' . $this->Id_contact1);
        $contact2 = ContactFactory::create(null, 'CG-' . $this->Id_contact2);
        $description = new Description($this->Id_description, array());
        $planning = new Planning($this->Id_planning, array());
        $proposition = new Proposition(self::lastProposition($this->Id_affaire), array());

        if (isset($this->Id_environnement)) {
            $environnment = new Environnement($this->Id_environnement, array());
            $env = '<br /><fieldset>' . $environnment->consultation() . '</fieldset>';
        }
        if (isset($this->resp_qualif)) {
            $EtatTec = '
			<h2>Etat Technique</h2><br />
			Responsable de qualification : ' . $this->resp_qualif . ' <br />
            Responsable Technique 1 : ' . $this->resp_tec1 . ' <br />
			Responsable Technique 2 : ' . $this->resp_tec2 . ' <br />
			Service Delivery Manager : ' . $this->sdm . ' <br />';
        }
        $nb_competence = count($this->competence);
        if ($nb_competence) {
            $i = 0;
            $comp = '<br /><fieldset><h2>Compétences requises</h2>';
            while ($i < $nb_competence) {
                $comp.= Competence::getLibelle($this->competence[$i]) . ' -> ' . Competence::getLevel($this->niveau_competence[$i]) . '<br />';
                ++$i;
            }
            $comp .= '</fieldset>';
        }
        $nb_langue = count($this->langue);
        if ($nb_langue) {
            $i = 0;
            $lang = '<br /><fieldset><h2>Langues requises</h2>';
            while ($i < $nb_langue) {
                $lang.= Langue::getLibelle($this->langue[$i]) . ' -> ' . Langue::getLevel($this->niveau_langue[$i]) . '<br />';
                ++$i;
            }
            $lang .= '</fieldset>';
        }
        if (!empty($this->exigence)) {
            foreach ($this->exigence as $i) {
                $exigence = new Exigence($i, array());
                $exig .= ' | ' . $exigence->nom;
            }
            $exig .= '<br />';
        }
        $html = '
			<fieldset>
			    <div class="left">
			        <h2>Etat Commercial</h2><br />
			        <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_REDACTOR . '</div>\', FULLHTML);" onmouseout="return nd();"></img>
				    Rédacteur 1 : ' . $this->redacteur1 . ' &nbsp;|
			        Rédacteur 2 : ' . $this->redacteur2 . '
			    </div>
                            <div class="right">
			        ' . $EtatTec . '
			    </div>
			</fieldset>
			<br />
			<fieldset>
			    <h2>Historique des changements de statut</h2>
			    ' . $this->statusHistory() . '
			</fieldset>
			<br />
			<fieldset>
			    <h2>Coordonnées et contact Client ou Prospect</h2>
			    <div class="left">
			        Compte : ' . $compte->nom . ' <br />
			        Contact 1 : ' . $contact1->getName() . ' <br />
			        Contact 2 : ' . $contact2->getName() . ' <br />
				    Ouverture de compte : ' . yesno($this->ouverture_compte) . '
			    </div>
			    <div class="right">
			        ' . $compte->infoCompte() . '
			    </div>
			</fieldset>
			<br />
			<fieldset>
			    ' . $planning->consultation() . '
			</fieldset>
			<br />
			<fieldset>
			    ' . $description->consultation($this->Id_pole) . ' ' . $exig . '
			</fieldset>
		    ' . $comp . '' . $lang . '' . $env . '
		    ' . $an_com . ' ' . $an_ris . ' ' . $dec . '
			<br />
			<fieldset>
			    ' . $proposition->consultation($this->Id_type_contrat, $this->Id_pole) . '
			</fieldset>
			<br />
			<fieldset>
			    <h2>Liste des contrats délégation de l\'affaire</h2>
                            ' . ContratDelegation::search('', $this->Id_affaire, '', '', '', '', '') . '
			</fieldset>
			<br /><br />
';
        return $html;
    }

    /**
     * Affichage de la raison de la perte d'une affaire
     *
     * @return string
     */
    public function lostReason() {
        $html = '
			<span class="infoFormulaire"> * </span> Raisons :
			<select name="Id_raison_perdue">
			    <option value="">' . REASON_SELECT . '</option>
			    <option value="">----------------------------------</option>
			    ' . $this->getLostReasonList() . '
			</select>
			<br /><br />
			Commentaires :<br />
			<textarea name="commentaire_perdue" id="tinyarea5" cols="60" rows="10">' . $this->commentaire_perdue . '</textarea><br /><br />';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les raisons de la perte d'une affaire
     *
     * @return string
     */
    public function getLostReasonList($Id_raison_perdue = null) {
        if ($Id_raison_perdue == null)
            $affaire[$this->Id_raison_perdue] = 'selected="selected"';
        else
            $affaire[$Id_raison_perdue] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM raison_perdue');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_raison_perdue . ' ' . $affaire[$ligne->id_raison_perdue] . '>' . $ligne->nom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du responsable de la qualification
     *
     * @param int Identifiant de l'affaire
     *
     * @return string
     */
    public static function getQualificationResponsible($i) {
        $db = connecter();
        return $db->query('SELECT resp_qualif FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $i))->fetchRow()->resp_qualif;
    }

    /**
     * Indique s'il y a une une ouverture de compte pour cette affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function isNewCustomer($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT ouverture_compte FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow()->ouverture_compte;
    }

    /**
     * Indique si l'affaire est une affaire récurrente (date de signature antérieure au premier janvier de l'année en cours)
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function isRecursive($Id_affaire) {
        $db = connecter();
        return $db->query('SELECT hs.Id_affaire FROM historique_statut hs INNER JOIN planning p
        ON p.Id_affaire=hs.Id_affaire WHERE hs.Id_statut=5 AND date_fin_commande>="' . mysql_real_escape_string(ANNEE) . '-01-01"
		AND date<"' . mysql_real_escape_string(ANNEE) . '-01-01" AND hs.Id_affaire="' . mysql_real_escape_string((int) $Id_affaire) . '"')->numRows();
    }

    /**
     * Affichage du lien du document pour l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return string
     */
    public static function getDoc($i) {
        $db = connecter();
        return $db->query('SELECT doc FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $i))->fetchRow()->doc;
    }

    /**
     * Envoi un mail d'informtion de création d'une nouvelle affaire pour les pôles
     *
     * @param int Identifiant de l'affaire
     * @param string créateur de l'affaire
     *
     * @return string
     */
    public static function informPoleMail($Id_affaire, $Id_pole, $Id_agence, $Id_statut, $createur) {

        //texte du mail, attention mise en page importante pour la mise en page dans le mail
        $subject = 'Nouvelle affaire AGC pour votre pôle';
        $dest = 'pole.architecture@proservia.fr';
        $text = 'Bonjour,
			
Une nouvelle affaire AGC ' . Statut::getLibelle($Id_statut) . ' n° ' . $Id_affaire . ' pour l\'agence de ' . Agence::getLibelle($Id_agence) . ' a été créée pour votre pôle.

' . BASE_URL . 'com/index.php?a=afficherAffaire&Id_affaire=' . $Id_affaire . '';
        $hdrs = array(
            'From' => $createur . '@proservia.fr',
            'Subject' => $subject,
            'To' => $dest
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
    
    /**
     * Retourne un tableau contenant les contrat délégations
     *
     * @param int Identifiant de l'affaire
     *
     * @return array
     */
    public function getCDList() {
        $db = connecter();
        $result = $db->query('SELECT cd.Id_contrat_delegation FROM contrat_delegation cd
                              WHERE cd.Id_affaire="' . mysql_real_escape_string($this->Id_affaire) . '"');
        $array = array();
        while ($ligne = $result->fetchRow()) {
            array_push($array, $ligne->id_contrat_delegation);
        }
        return $array;
    }

    /**
     * Affiche les ressources positionnées sur l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getResourceList($Id_affaire) {
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_ressource, type_ressource FROM proposition_ressource pr INNER JOIN proposition p
		ON p.Id_proposition=pr.Id_proposition WHERE Id_affaire="' . mysql_real_escape_string($Id_affaire) . '"');
        while ($ligne = $result->fetchRow()) {
            $ressource = RessourceFactory::create($ligne->type_ressource, $ligne->id_ressource, null);
            $html .= ' - ' . $ressource->getName();
        }
        return $html;
    }

    /**
     * Affichage du formulaire rapide de création d'une affaire
     *
     * @return string
     */
    public function rapidForm() {
        $commercial = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        $agence = new Agence(Utilisateur::getAgency($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur), array());
        $compte = CompteFactory::create('CG', null);
        $pole = new Pole('', '');
        $type_contrat = new TypeContrat('', '');
        $html = '
			<select id="commercial">
				<option value="">' . MARKETING_PERSON_SELECT . '</option>
				<option value="">-------------------------</option>
				' . $commercial->getList('COM') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('OP') . '
				<option value="">-------------------------</option>
				' . $commercial->getList('FOR') . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_agence">
				<option value="">' . AGENCE_SELECT . '</option>
				<option value="">-------------------------</option>
				' . $agence->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_compte">
				<option value="">' . CUSTOMERS_SELECT . '</option>
				<option value="">-------------------------</option>
				' . $compte->getList() . '
			</select>
		    <select id="Id_pole">
			    <option value="">' . POLE_SELECT . '</option>
			    <option value="">-------------------------</option>
			    ' . $pole->getList() . '
		    </select>			
			&nbsp;&nbsp;
			<select id="Id_type_contrat">
				<option value="">Type de contrat</option>
				<option value="">-------------------------</option>
				' . $type_contrat->getList() . '
			</select>
			&nbsp;&nbsp;
			<input id="chiffre_affaire" type="text" size="8"> €
            <input type="button" value="Créer l\'affaire" onclick="if (confirm(\'' . CONFIRM_CASE_CREATION . '\')) {creerAffaire()}" /></td>
		';
        return $html;
    }

    /**
     * Affichage du formulaire de création d'un commentaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return string
     */
    public static function commentPerWeekForm($Id_affaire, $year, $week) {
        $db = connecter();
        $result = $db->query('SELECT * FROM commentaire_affaire_semaine cas
		WHERE Id_affaire="' . mysql_real_escape_string($Id_affaire) . '" AND year="' . mysql_real_escape_string($year) . '" AND week="' . mysql_real_escape_string($week) . '"');
        $ligne = $result->fetchRow();

        $html = '
		<form name="formulaire" enctype="multipart/form-data" action="index.php?a=saveCommentPerWeek" method="post">
		    <div class="center">
			    Affaire AGC n° ' . $Id_affaire . ' / Semaine ' . $week . ' / ' . $year . '
			    <br /><br />
			    Commentaires :<br />
			    <textarea name="comment" cols="60" rows="10">' . $ligne->comment . '</textarea>
			    <br /><br />
			    % de pondération :
		        <input type="text" name="weighting_pct" value="' . $ligne->weighting_pct . '" size="2" />
			    <br /><br />
                <input type="hidden" name="Id_affaire" value="' . $Id_affaire . '" />
			    <input type="hidden" name="week" value="' . $week . '" />
			    <input type="hidden" name="year" value="' . $year . '" />
		    </div>
			<div class="submit">
				<input type="submit" value="' . SAVE_BUTTON . '" />
			</div>
		</form>		
		';
        return $html;
    }
    
    /**
     * Affichage de la liste des affaires
     *
     * @param int Identifiant de l'affaire
     * @param string Type d'affaire
     *
     * @return string
     */
    public static function getList($type, $Id_affaire) {
        $aff[$Id_affaire] = 'selected="selected"';
        
        if($type == 'AGC') {
            $db = connecter();
            $result = $db->query('SELECT a.Id_affaire, i.libelle FROM affaire a 
                                  INNER JOIN description d ON a.Id_affaire = d.Id_affaire
                                  INNER JOIN intitule i ON d.Id_intitule =  i.Id_intitule WHERE Id_statut IN (5, 8)');
            while ($ligne = $result->fetchRow()) {
                $html .= '<option value="' . $ligne->id_affaire . '" ' . $aff[$ligne->id_affaire] . '>' . $ligne->libelle . ' | ' . $ligne->id_affaire . '</option>';
            }
        }
        else if($type == 'SF') {
            $sfClient = new SFClient();
            $result = $sfClient->query('SELECT Id, Name FROM Opportunity WHERE IsDeleted = false AND IsWon = true ORDER BY Name');
            foreach ($result->records as $record) {
                $html .= '<option value="' . $record->Id . '" ' . $aff[$record->Id] . '>' . $record->Name . ' | ' . $record->Id . '</option>';
            }
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public static function saveCommentPerWeek($Id_affaire, $year, $week, $comment, $weightingPct) {
        $db = connecter();
        $requete = 'INSERT INTO commentaire_affaire_semaine SET Id_affaire="' . mysql_real_escape_string($Id_affaire) . '",
		year="' . mysql_real_escape_string($year) . '", week="' . mysql_real_escape_string($week) . '", comment="' . mysql_real_escape_string($comment) . '",
                weighting_pct="' . mysql_real_escape_string($weightingPct) . '"
		ON DUPLICATE KEY UPDATE comment="' . mysql_real_escape_string($comment) . '", weighting_pct="' . mysql_real_escape_string($weightingPct) . '"';
        $db->query($requete);
    }
    
    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */
    
    public function showIdCase($record) {
        if($record['reference_affaire']) {
            if(is_numeric($record['id_affaire']))
                return '<a onclick="window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['reference_affaire'] . '</a>';
            else    
                return '<a onclick="window.open(\'' . SF_URL . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['reference_affaire'] . '</a>';
        }
        else if($record['id_affaire']) {
            if(is_numeric($record['id_affaire']))
                return '<a onclick="window.open(\'../com/index.php?a=modifier_affaire&Id_affaire=' . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['id_affaire'] . '</a>';
            else    
                return '<a onclick="window.open(\'' . SF_URL . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['id_affaire'] . '</a>';
        }
        return 'HA';
    }

    public function showCustomer($record) {
        $compte = CompteFactory::create(null, $record['id_compte']);
        return $compte->nom;
    }
    
    public function showContact($record) {
        $contact = ContactFactory::create('CG', $record['id_contact1']);
        return $contact->getName();
    }
    
    public function showTitle($record, $args) {
        $compte = CompteFactory::create(null, $record['id_compte']);
        if(!$args['csv']) {
            if ($record['resume']) {
                $desc = str_replace('"', "'", mysql_escape_string($record['resume']));
            }
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . $compte->tel . ' <hr />Demande client : ' . FormatageDate($record['date_demande']) . '<br />Date limite : ' . FormatageDate($record['date_limite']) . '<br />Début prestation : ' . FormatageDate($record['date_debut']) . ' <br />Fin de commande de la prestation : ' . FormatageDate($record['date_fin_commande']) . '<hr />' . self::getResourceList($record['id_affaire']) . '<hr />' . $desc . '</div>\', FULLHTML);" onmouseout="return nd();"';
            if($record['intitule'])
                return '<div ' . $info . '>'. $record['intitule'].'</div>';
            else
                return '<div ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return $record['intitule'];
    }
    
    public function showRevenue($record, $args) {
        $ca = Proposition::getCa(self::lastProposition($record['id_affaire']));
        if(!$args['csv'])
            return '<div id="ca' . $record['id_affaire'] . '" style="text-align:right;">' . number_format($ca, 0, ',', ' ') . '</div>';
        else
            return number_format($ca, 0, ',', ' ');
    }
    
    public function showWeighting($record, $args) {
        $idProposition = self::lastProposition($record['id_affaire']); 
        if(!empty($idProposition)){
            $proposition = new Proposition($idProposition, array());
            $ponderation = $proposition->getLastWeighting()->ponderation;
            if(!empty($ponderation)) {
                $caPondere = ($ca * ($ponderation / 100));
                if(!$args['csv'])
                    return '<div id="edit' . $record['id_affaire'] . '" style="text-align:right;">' . $ponderation . '</div>';
                else
                    return $ponderation;
            }
            else {
                if(!$args['csv']) return '<div id="edit' . $record['id_affaire'] . '" style="text-align:right;">0</div>';
                else return '0';
            }
        }
        else {
            if(!$args['csv']) return '<div id="edit' . $record['id_affaire'] . '" style="text-align:right;">0</div>';
            else return '0';
        }
    }
    
    public function showWeightingRevenue($record, $args) {
        $idProposition = self::lastProposition($record['id_affaire']); 
        if(!empty($idProposition)){
            $ca = Proposition::getCa(self::lastProposition($record['id_affaire']));
            $proposition = new Proposition($idProposition, array());
            $ponderation = $proposition->getLastWeighting()->ponderation;
            if(!empty($ponderation)) {
                $caPondere = round(($ca * ($ponderation / 100)));
                if(!$args['csv'])
                    return '<div id="caPondere' . $record['id_affaire'] . '" style="text-align:right;">' . number_format($caPondere, 0, ',', ' ') . '</div>';
                else
                    return number_format($caPondere, 0, ',', ' ');
            }
            else {
                if(!$args['csv']) return '<div id="caPondere' . $record['id_affaire'] . '" style="text-align:right;">0</div>';
                else return '0';
            }
        }
        else {
            if(!$args['csv']) return '<div id="caPondere' . $record['id_affaire'] . '" style="text-align:right;">0</div>';
            else return '0';
        }
    }
    
    public function showButtons($record) {
        $htmlAdmin = '<td><a href="index.php?a=afficherAffaire&amp;Id_affaire=' . $record['id_affaire'] . '"><img src="' . IMG_CONSULT . '"></a></td>';
        if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_affaire'])) {
            $htmlAdmin .= '<td><a href="index.php?a=modifier_affaire&amp;Id_affaire=' . $record['id_affaire'] . '"><img src="' . IMG_EDIT . '"></a></td>';
        }
        if (Utilisateur::getCaseDeleteRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_affaire'])) {
            $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer l affaire n° ' . $record['id_affaire'] . ' ?\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_affaire'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        }
        return $htmlAdmin;
    }
    
    public function showButtonsWeighting($record) {
        if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_affaire'])) {
            $idProposition = self::lastProposition($record['id_affaire']);
            $ca = Proposition::getCa($idProposition);
            $html .= '<a id="editButton' . $record['id_affaire'] . '" onclick="return false;" href="index.php?a=modifier_affaire&amp;Id_affaire=' . $record['id_affaire'] . '"><img src="' . IMG_EDIT . '"></a>';
            $html .= '<script type="text/javascript">
                            new Ajax.InPlaceCollectionEditor(\'edit' . $record['id_affaire'] . '\', \'../source/index.php?a=sauvegarderPonderation&Id_proposition=' . $idProposition . '&ponderation=' . $record['id_affaire'] . '&chiffre_affaire=' . $ca . '\',
                            {
                                collection: [["10", "10%"], ["20", "20%"], ["40", "40%"], ["60", "60%"], ["80", "80%"]] ,
                                savingText: \'Enregistrement\',
                                okText: \'Valider\',
                                cancelText: \'Annuler\',
                                externalControl: \'editButton' . $record['id_affaire'] . '\',
                                externalControlOnly: true,
                                onCancel: function(obj) {
                                    var ca = $("ca' . $record['id_affaire'] . '").innerHTML.replace(/ /g,\'\');
                                    var ponderation = $("edit' . $record['id_affaire'] . '").innerHTML;
                                    $("caPondere' . $record['id_affaire'] . '").update(
                                        format(Math.round( ca * ( ponderation / 100) ),0,\' \')
                                    )
                                },
                                onFormReady: function(obj,form) { 
                                    var select = form.getElements().first();
                                    select.id = \'editor_field' . $record['id_affaire'] . '\';
                                    var ca = $("ca' . $record['id_affaire'] . '").innerHTML.replace(/ /g,\'\');
                                    var ponderation = select.value;
                                    $("caPondere' . $record['id_affaire'] . '").update(
                                        format(Math.round( ca * ( ponderation / 100) ),0,\' \')
                                    )
                                    Event.observe(select,\'change\',function(){
                                        var ca = $("ca' . $record['id_affaire'] . '").innerHTML.replace(/ /g,\'\');
                                        var ponderation = select.value;
                                        $("caPondere' . $record['id_affaire'] . '").update(
                                            format(Math.round( ca * ( ponderation / 100) ),0,\' \')
                                        )
                                    });
                                }
                            });
                        </script>';
        }
        return $html;
    }
}

?>