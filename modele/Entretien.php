<?php

/**
 * Fichier Entretien.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Entretien
 */
class Entretien {

    /**
     * Identifiant de l'entretien
     *
     * @access private
     * @var int 
     */
    private $Id_entretien;
    /**
     * Identifiant du recruteur
     *
     * @access private
     * @var int 
     */
    private $Id_recruteur;
    /**
     * Identifiant du commercial
     *
     * @access private
     * @var int 
     */
    private $Id_commercial;
    /**
     * Identifiant de la candidature
     *
     * @access private
     * @var int 
     */
    private $Id_candidature;
    /**
     * Identifiant de la disponibilite
     *
     * @access private
     * @var int 
     */
    public $Id_preavis;
    /**
     * Date de la disponibilite
     *
     * @access private
     * @var date 
     */
    public $date_disponibilite;
    /**
     * Préavis négociable
     *
     * @access private
     * @var int 
     */
    public $preavis_negociable;
    /**
     * Tableaux des identifiants de mobilite
     *
     * @access public
     * @var array 
     */
    public $mobilite;
    /**
     * Tableaux des identifiants des certifications
     *
     * @access public
     * @var array 
     */
    public $certification;
    /**
     * Tableaux des identifiants des compétences
     *
     * @access public
     * @var array 
     */
    public $competence;
    /**
     * Tableaux des identifiants de niveaux
     *
     * @access public
     * @var array 
     */
    public $niveau_competence;
    /**
     * Tableaux des identifiants de langues
     *
     * @access public
     * @var array 
     */
    public $langue;
    /**
     * Tableaux des identifiants de niveaux de langue
     *
     * @access public
     * @var array 
     */
    public $niveau_langue;
    /**
     * Prétention salariale inférieure
     *
     * @access private
     * @var double 
     */
    public $pretention_basse;
    /**
     * Prétention salariale supérieure
     *
     * @access private
     * @var double 
     */
    public $pretention_haute;
    /**
     * Tarif Journalier pour un indépendant
     *
     * @access private
     * @var double 
     */
    public $tarif_journalier;
    /**
     * Attente Professionnelle
     *
     * @access private
     * @var string 
     */
    private $attente_pro;
    /**
     * Identifiant du profil envisagé
     *
     * @access private
     * @var int 
     */
    private $Id_profil_envisage;
    /**
     * Avancement de la recherche
     *
     * @access private
     * @var string 
     */
    private $avancement_recherche;
    /**
     * Tableau des critères de recherche
     *
     * @access public
     * @var array 
     */
    public $critere_recherche;
    /**
     * Connaissance de Proservia
     *
     * @access private
     * @var int 
     */
    private $connaissance_proservia;
    /**
     * Date de début du stage
     *
     * @access private
     * @var date 
     */
    private $debut_stage;
    /**
     * Date de la fin du stage
     *
     * @access private
     * @var date 
     */
    private $fin_stage;
    /**
     * Créateur de la fiche entretien
     *
     * @access private
     * @var string 
     */
    private $createur;
    /**
     * Date de création de la fiche entretien
     *
     * @access private
     * @var datetime 
     */
    private $date_creation;
    /**
     * Commentaire du RH sur l'entretien
     *
     * @access public
     * @var string 
     */
    public $commentaire_rh;
    /**
     * Commentaire du COM sur l'entretien
     *
     * @access public
     * @var string 
     */
    public $commentaire_com;
    /**
     * Commentaire du RT sur l'entretien
     *
     * @access public
     * @var string 
     */
    public $commentaire_rt;
    /**
     * Référence cliente n°1
     *
     * @access public
     * @var string 
     */
    public $ref_client1;
    /**
     * Nom du contact de la référence cliente n°1
     *
     * @access public
     * @var string 
     */
    public $ref_contact1;
    /**
     * Téléphone de la référence cliente n°1
     *
     * @access public
     * @var string 
     */
    public $tel_client1;
    /**
     * Référence cliente n°2
     *
     * @access public
     * @var string 
     */
    public $ref_client2;
    /**
     * Nom du contact de la référence cliente n°2
     *
     * @access public
     * @var string 
     */
    public $ref_contact2;
    /**
     * Téléphone de la référence cliente n°2
     *
     * @access public
     * @var string 
     */
    public $tel_client2;

    /**
     * Constructeur de la classe Entretien
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'entretien
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_entretien = '';
                $this->Id_recruteur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->Id_commercial = '';
                $this->Id_candidature = '';
                $this->Id_preavis = '';
                $this->date_disponibilite = '';
                $this->preavis_negociable = '';
                $this->mobilite = new Mobilite('', array());
                $this->certification = '';
                $this->competence = '';
                $this->niveau_competence = '';
                $this->langue = '';
                $this->niveau_langue = '';
                $this->pretention_basse = '';
                $this->pretention_haute = '';
                $this->tarif_journalier = '';
                $this->indemnite_stage = '';
                $this->attente_pro = '';
                $this->Id_profil_envisage = '';
                $this->avancement_recherche = '';
                $this->critere_recherche = '';
                $this->debut_stage = '';
                $this->fin_stage = '';
                $this->connaissance_proservia = '';
                $this->createur = '';
                $this->date_creation = '';
                $this->commentaire_rh = '';
                $this->commentaire_com = '';
                $this->commentaire_rt = '';
                $this->mot_cle = '';
                $this->ref_client1 = '';
                $this->ref_client2 = '';
                $this->ref_contact1 = '';
                $this->ref_contact2 = '';
                $this->tel_client1 = '';
                $this->tel_client2 = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_entretien = '';
                $this->Id_recruteur = $tab['Id_recruteur'];
                $this->Id_commercial = $tab['Id_commercial'];
                $this->Id_candidature = (int) $tab['Id_candidature'];
                $this->Id_preavis = (int) $tab['Id_preavis'];
                $this->date_disponibilite = $tab['date_disponibilite'];
                $this->preavis_negociable = (int) $tab['preavis_negociable'];
                $this->pretention_basse = htmlscperso(stripslashes($tab['pretention_basse']), ENT_QUOTES);
                $this->pretention_haute = htmlscperso(stripslashes($tab['pretention_haute']), ENT_QUOTES);
                $this->tarif_journalier = htmlscperso(stripslashes($tab['tarif_journalier']), ENT_QUOTES);
                $this->indemnite_stage = htmlscperso(stripslashes($tab['indemnite_stage']), ENT_QUOTES);
                $this->attente_pro = $tab['attente_pro'];
                $this->Id_profil_envisage = (int) $tab['Id_profil_envisage'];
                $this->avancement_recherche = $tab['avancement_recherche'];
                $this->debut_stage = $tab['debut_stage'];
                $this->fin_stage = $tab['fin_stage'];
                $this->connaissance_proservia = (int) $tab['connaissance_proservia'];
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->date_creation = DATETIME;
                $this->commentaire_rh = $tab['commentaire_rh'];
                $this->commentaire_com = $tab['commentaire_com'];
                $this->commentaire_rt = $tab['commentaire_rt'];
                $this->mot_cle = $tab['mot_cle'];
                $this->ref_client1 = $tab['ref_client1'];
                $this->ref_client2 = $tab['ref_client2'];
                $this->ref_contact1 = $tab['ref_contact1'];
                $this->ref_contact2 = $tab['ref_contact2'];
                $this->tel_client1 = $tab['tel_client1'];
                $this->tel_client2 = $tab['tel_client2'];

                $this->mobilite = new Mobilite('', $tab);

                if (!empty($tab['critere_recherche'])) {
                    foreach ($tab['critere_recherche'] as $i) {
                        $this->critere_recherche[] = $i;
                    }
                }
                if (!empty($tab['certification'])) {
                    foreach ($tab['certification'] as $i) {
                        $this->certification[] = $i;
                    }
                }

                if ($tab['competence']) {
                    $nb_competence = count($tab['competence']);
                    $i = 0;
                    while ($i < $nb_competence) {
                        $this->competence[] = $tab['competence'][$i];
                        $this->niveau_competence[] = $tab['niveau_competence'][$i];
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
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM entretien WHERE Id_entretien=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_entretien = $code;
                $this->Id_recruteur = $ligne->id_recruteur;
                $this->Id_commercial = $ligne->id_commercial;
                $this->Id_candidature = $ligne->id_candidature;
                $this->Id_preavis = $ligne->id_preavis;
                $this->date_disponibilite = $ligne->date_disponibilite;
                $this->preavis_negociable = $ligne->preavis_negociable;
                $this->pretention_basse = $ligne->pretention_basse;
                $this->pretention_haute = $ligne->pretention_haute;
                $this->tarif_journalier = $ligne->tarif_journalier;
                $this->indemnite_stage = $ligne->indemnite_stage;
                $this->attente_pro = strip_tags($ligne->attente_pro);
                $this->Id_profil_envisage = $ligne->id_profil_envisage;
                $this->avancement_recherche = $ligne->avancement_recherche;
                $this->debut_stage = $ligne->debut_stage;
                $this->fin_stage = $ligne->fin_stage;
                $this->connaissance_proservia = $ligne->connaissance_proservia;
                $this->createur = $ligne->createur;
                $this->date_creation = $ligne->date_creation;
                $this->commentaire_rh = strip_tags($ligne->commentaire_rh);
                $this->commentaire_com = strip_tags($ligne->commentaire_com);
                $this->commentaire_rt = strip_tags($ligne->commentaire_rt);
                $this->mot_cle = $ligne->mot_cle;
                $this->ref_client1 = $ligne->ref_client1;
                $this->ref_client2 = $ligne->ref_client2;
                $this->ref_contact1 = $ligne->ref_contact1;
                $this->ref_contact2 = $ligne->ref_contact2;
                $this->tel_client1 = $ligne->tel_client1;
                $this->tel_client2 = $ligne->tel_client2;

                $this->mobilite = new Mobilite($this->Id_entretien, array());
                $result = $db->query('SELECT Id_critere FROM entretien_critere WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
                while ($ligne = $result->fetchRow()) {
                    $this->critere_recherche[] = $ligne->id_critere;
                }
                $result = $db->query('SELECT Id_certification FROM entretien_certification WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
                while ($ligne = $result->fetchRow()) {
                    $this->certification[] = $ligne->id_certification;
                }
                $result = $db->query('SELECT Id_langue, Id_niveau_langue FROM entretien_langue WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
                while ($ligne = $result->fetchRow()) {
                    $this->langue[] = $ligne->id_langue;
                    $this->niveau_langue[] = $ligne->id_niveau_langue;
                }
                $result = $db->query('SELECT entretien_competence.Id_competence, entretien_competence.Id_niveau_competence FROM entretien_competence INNER JOIN competence ON entretien_competence.Id_competence=competence.Id_competence
									  WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien) . ' ORDER BY competence.libelle');
                while ($ligne = $result->fetchRow()) {
                    $this->competence[] = $ligne->id_competence;
                    $this->niveau_competence[] = $ligne->id_niveau_competence;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_entretien = $code;
                $this->Id_candidature = (int) $tab['Id_candidature'];
                $this->Id_recruteur = $tab['Id_recruteur'];
                $this->Id_commercial = $tab['Id_commercial'];
                $this->Id_preavis = (int) $tab['Id_preavis'];
                $this->date_disponibilite = $tab['date_disponibilite'];
                $this->preavis_negociable = (int) $tab['preavis_negociable'];
                $this->pretention_basse = htmlscperso(stripslashes($tab['pretention_basse']), ENT_QUOTES);
                $this->pretention_haute = htmlscperso(stripslashes($tab['pretention_haute']), ENT_QUOTES);
                $this->tarif_journalier = htmlscperso(stripslashes($tab['tarif_journalier']), ENT_QUOTES);
                $this->indemnite_stage = htmlscperso(stripslashes($tab['indemnite_stage']), ENT_QUOTES);
                $this->attente_pro = $tab['attente_pro'];
                $this->Id_profil_envisage = (int) $tab['Id_profil_envisage'];
                $this->avancement_recherche = $tab['avancement_recherche'];
                $this->debut_stage = $tab['debut_stage'];
                $this->fin_stage = $tab['fin_stage'];
                $this->connaissance_proservia = (int) $tab['connaissance_proservia'];
                $this->commentaire_rh = $tab['commentaire_rh'];
                $this->commentaire_com = $tab['commentaire_com'];
                $this->commentaire_rt = $tab['commentaire_rt'];
                $this->mot_cle = $tab['mot_cle'];
                $this->ref_client1 = $tab['ref_client1'];
                $this->ref_client2 = $tab['ref_client2'];
                $this->ref_contact1 = $tab['ref_contact1'];
                $this->ref_contact2 = $tab['ref_contact2'];
                $this->tel_client1 = $tab['tel_client1'];
                $this->tel_client2 = $tab['tel_client2'];
                $this->mobilite = new Mobilite($this->Id_entretien, $tab);

                if (!empty($tab['critere_recherche'])) {
                    foreach ($tab['critere_recherche'] as $i) {
                        $this->critere_recherche[] = $i;
                    }
                }
                if (!empty($tab['certification'])) {
                    foreach ($tab['certification'] as $i) {
                        $this->certification[] = $i;
                    }
                }

                if ($tab['competence']) {
                    $nb_competence = count($tab['competence']);
                    $i = 0;
                    while ($i < $nb_competence) {
                        $this->competence[] = $tab['competence'][$i];
                        $this->niveau_competence[] = $tab['niveau_competence'][$i];
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
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire d'affichage / modification d'une fiche entretien
     *
     * @return string	   
     */
    public function form() {
        $Id_recruteur = ($this->Id_recruteur == '' ) ? $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur : $this->Id_recruteur;
        $recruteur = new Utilisateur($Id_recruteur, array());
        $commercial = new Utilisateur($this->Id_commercial, array());
        $preavis = new Preavis($this->Id_preavis, array());
        $profil = new Profil($this->Id_profil_envisage, array());
        $preav[$this->preavis_negociable] = 'checked="checked"';

        $candidature = new Candidature($this->Id_candidature, array());
        $ressource = RessourceFactory::create('CAN_AGC', $candidature->Id_ressource, null);
        if ($candidature->lien_cv) {
            $lien_cv = " | <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $candidature->lien_cv . "'><img src=" . IMG_CV . "></a>";
        }
        if ($candidature->lien_cvp) {
            $lien_cvp = " | <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $candidature->lien_cvp . "'><img src=" . IMG_CVP . "></a>";
        }
        $langue = new Langue('', '');
        if (array_intersect($candidature->type_contrat, array(1, 2))) {
            $dateHtml = '
			Début du stage :
			<input type="text" name="debut_stage" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->debut_stage) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
			Fin du stage :
			<input type="text" name="fin_stage" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->fin_stage) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
';
        }
        if (array_intersect($candidature->type_contrat, array(5))) {
            $salaireHtml = '
			Tarif Journalier :
			<input type="text" name="tarif_journalier" id="tarif_journalier" value="' . $this->tarif_journalier . '" size="3" /> &euro; <br /><br />
';
        }
        if (array_intersect($candidature->type_contrat, array(1, 2))) {
            $salaireHtml .= '
			Indemnités de stage :
			<input type="text" name="indemnite_stage" id="indemnite_stage" value="' . $this->indemnite_stage . '" size="3" /> &euro; <br /><br />
';
        }
        $salaireHtml .= '
			<span class="infoFormulaire"> * </span> Prétention salariale (annuel brut) : <br />
		    entre <input type="text" id="pretention_basse" name="pretention_basse" value="' . $this->pretention_basse . '" size="4" maxlength="4" /> K&euro;
			et <input type="text" id="pretention_haute" name="pretention_haute" value="' . $this->pretention_haute . '" size="4" maxlength="4" /> K&euro;<br /><br />
';
        $html = '
		<h2><a href="index.php?a=modifier&Id=' . (int) $this->Id_candidature . '&amp;class=Candidature">Feuille Candidat ' . $ressource->getName() . '</a>
		' . $lien_cv . ' ' . $lien_cvp . '</h2>
	    <form name="formulaire" enctype="multipart/form-data" action="../membre/index.php?a=enregistrer_entretien" method="post">
		    <input type="hidden" id="Id_entretien" name="Id" value="' . (int) $this->Id_entretien . '" />
            <div class="submit">
	            <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifEntretien(this.form)" />
		    </div>
			<div class="left">
 				<span class="infoFormulaire"> * </span> Recruteur : 
			    <select id="Id_recruteur" name="Id_recruteur">
                    <option value="">' . RECRUITER_SELECT . '</option>
				    <option value="">-------------------------</option>
				    ' . $recruteur->getList('RH') . '
                </select>
			    <br /><br />
				Ingénieur Commercial : 
			    <select name="Id_commercial">
                    <option value="">' . MARKETING_PERSON_SELECT . '</option>
				    <option value="">-------------------------</option>
				    ' . $commercial->getList('COM') . '
					<option value="">-------------------------</option>
					' . $commercial->getList('OP') . '
                </select>
			    <br /><br />
				<span class="infoFormulaire"> *1 </span> 
				Date de disponibilité :
				<input type="text" name="date_disponibilite" id="date_disponibilite" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->date_disponibilite) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
				<span class="infoFormulaire"> *1 </span> Préavis :
			    <select id="Id_preavis" name="Id_preavis">
                    <option value="">' . NOTICE_SELECT . '</option>
				    <option value="">-------------------------</option>
				    ' . $preavis->getList() . '
                </select>
				Négociable : <input type="checkbox" name="preavis_negociable" value="1" ' . $preav['1'] . '>
				<br /><br />
				<span class="infoFormulaire"> * </span> Mobilité : ' . $this->mobilite->form() . '<br /><br />
				Langue(s) : 
				<img src="' . IMG_PLUS . '" onclick="ajout(\'Langue\')">
				<img src="' . IMG_MOINS . '" onclick="enleve(\'Langue\')"><br />
				' . $langue->formLangue("", $this->Id_entretien) . '
				<br /><br />
				Avancement recherche emploi :
                <select name="avancement_recherche">
                    <option value="">' . PROGRESS_SELECT . '</option>
				    <option value="">--------------------------</option>
                    ' . $this->getProgressList() . '
				</select><br /><br />
				Critères de recherche :
                    ' . CritereRecherche::getCheckboxList($this->Id_entretien) . '<br /><br />
				Connaissance de Proservia : (1 : très faible, 5 : très bonne)
                <select name="connaissance_proservia">
                    ' . $this->getProserviaKnowledgeList() . '
				</select>
				<br /><br />
				' . $dateHtml . '
				' . $salaireHtml . '
			    Evolution envisagée :
                <select name="Id_profil_envisage">
                    <option value="">' . PROFIL_SELECT . '</option>
				    <option value="">-------------------------</option>
					' . $profil->getList() . '
                </select>
			    <br /><br />
				<div class="left">
			        Référence client 1 : <br /><br />
                    Client : <input type="text" name="ref_client1" value="' . $this->ref_client1 . '" /><br /><br />
				    Contact : <input type="text" name="ref_contact1" value="' . $this->ref_contact1 . '" /><br /><br />
			        Tél : <input type="text" name="tel_client1" value="' . $this->tel_client1 . '" size="12" />
					<br /><br />
				</div>
				<div class="right">
			        Référence client 2 : <br /><br />
                    Client : <input type="text" name="ref_client2" value="' . $this->ref_client2 . '" /><br /><br />
				    Contact : <input type="text" name="ref_contact2" value="' . $this->ref_contact2 . '" /><br /><br />
				    Tél : <input type="text" name="tel_client2" value="' . $this->tel_client2 . '" size="12" />
					<br /><br />
				</div>
			    Compétence(s) : <br />
					' . Competence::getCheckboxList('', $this->Id_entretien) . '
			    <br /><br />				
			    Certification(s) : <br />
					' . Certification::getCheckboxList($this->Id_entretien) . '
			    <br /><br />				
			</div>
			<div class="right">
			    Attentes professionnelles actuelles :
                <textarea name="attente_pro">' . $this->attente_pro . '</textarea><br /><br />
				Commentaire RH :
                <textarea name="commentaire_rh">' . $this->commentaire_rh . '</textarea><br /><br />
				Commentaire COM :
                <textarea name="commentaire_com">' . $this->commentaire_com . '</textarea><br /><br />
				Commentaire RT :
                <textarea name="commentaire_rt">' . $this->commentaire_rt . '</textarea><br /><br />
				<span class="infoFormulaire"> * </span> Mots clés :
                <textarea id="mot_cle" name="mot_cle">' . $this->mot_cle . '</textarea>
            </div>
			<div class="submit">
				<input type="hidden" id="Id_candidature" name="Id_candidature" value="' . (int) $this->Id_candidature . '" />
			    <input type="hidden" name="class" value="' . __CLASS__ . '" />
	            <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifEntretien(this.form)" />
		    </div>
		</form>
		<span class="infoFormulaire"> *1 : Veuillez saisir l\'un ou l\'autre.</span>
';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien) . ', Id_candidature = ' . mysql_real_escape_string((int) $this->Id_candidature) . ', 
		Id_recruteur = "' . $this->Id_recruteur . '", Id_commercial = "' . $this->Id_commercial . '", Id_preavis = ' . mysql_real_escape_string((int) $this->Id_preavis) . ', 
		date_disponibilite ="' . mysql_real_escape_string(DateMysqltoFr($this->date_disponibilite, 'mysql')) . '", preavis_negociable = ' . mysql_real_escape_string((int) $this->preavis_negociable) . ', 
		tarif_journalier = ' . mysql_real_escape_string((float) $this->tarif_journalier) . ', pretention_basse = ' . mysql_real_escape_string((float) $this->pretention_basse) . ', 
		pretention_haute = ' . mysql_real_escape_string((float) $this->pretention_haute) . ', attente_pro = "' . mysql_real_escape_string($this->attente_pro) . '", 
		Id_profil_envisage = ' . mysql_real_escape_string((int) $this->Id_profil_envisage) . ', ref_client1 = "' . mysql_real_escape_string($this->ref_client1) . '",
        ref_client2 = "' . mysql_real_escape_string($this->ref_client2) . '", ref_contact1 = "' . mysql_real_escape_string($this->ref_contact1) . '",	ref_contact2 = "' . mysql_real_escape_string($this->ref_contact2) . '",	
		tel_client1 = "' . mysql_real_escape_string($this->tel_client1) . '", tel_client2 = "' . mysql_real_escape_string($this->tel_client2) . '", avancement_recherche = "' . mysql_real_escape_string($this->avancement_recherche) . '", 
		debut_stage = "' . mysql_real_escape_string(DateMysqltoFr($this->debut_stage, 'mysql')) . '", fin_stage = "' . mysql_real_escape_string(DateMysqltoFr($this->fin_stage, 'mysql')) . '", 
		connaissance_proservia = ' . mysql_real_escape_string((int) $this->connaissance_proservia) . ', commentaire_rh = "' . mysql_real_escape_string($this->commentaire_rh) . '", 
		commentaire_com = "' . mysql_real_escape_string($this->commentaire_com) . '", commentaire_rt = "' . mysql_real_escape_string($this->commentaire_rt) . '", mot_cle = "' . mysql_real_escape_string($this->mot_cle) . '",
		indemnite_stage = "' . mysql_real_escape_string($this->indemnite_stage) . '"';
        if ($this->Id_entretien) {
            $requete = 'UPDATE entretien ' . $set . ' WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien);
        } else {
            $requete = 'INSERT INTO entretien ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation = "' . mysql_real_escape_string($this->date_creation) . '"';
        }
        $db->query($requete);

        $nb_critere = count($this->critere_recherche);
        $nb_langue = count($this->langue);
        $nb_cert = count($this->certification);
        $nb_competence = count($this->competence);
        $tab_mobilite = $this->mobilite->getRegion_souhaitee();
        $nb_region_souhaitee = count($tab_mobilite);

        $Id_entretien = ($this->Id_entretien == '') ? mysql_insert_id() : (int) $this->Id_entretien;

        if ($this->Id_entretien) {
            $db->query('DELETE FROM entretien_mobilite WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
            $db->query('DELETE FROM entretien_critere WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
            $db->query('DELETE FROM entretien_langue WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
            $db->query('DELETE FROM entretien_certification WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
            $db->query('DELETE FROM entretien_competence WHERE Id_entretien=' . mysql_real_escape_string((int) $this->Id_entretien));
        }

        $i = 0;
        while ($i < $nb_region_souhaitee) {
            $db->query('INSERT INTO entretien_mobilite SET Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . ', Id_mobilite="' . mysql_real_escape_string($tab_mobilite[$i]) . '"');
            ++$i;
        }
        $i = 0;
        while ($i < $nb_critere) {
            $db->query('INSERT INTO entretien_critere SET Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . ', Id_critere=' . mysql_real_escape_string((int) $this->critere_recherche[$i]));
            ++$i;
        }
        $i = 0;
        while ($i < $nb_langue) {
            $db->query('INSERT INTO entretien_langue SET Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . ', Id_langue=' . mysql_real_escape_string((int) $this->langue[$i]) . ', Id_niveau_langue=' . mysql_real_escape_string((int) $this->niveau_langue[$i]));
            ++$i;
        }
        $i = 0;
        while ($i < $nb_cert) {
            $db->query('INSERT INTO entretien_certification SET Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . ', Id_certification=' . mysql_real_escape_string((int) $this->certification[$i]));
            ++$i;
        }
        $i = 0;
        while ($i < $nb_competence) {
            $db->query('INSERT INTO entretien_competence SET Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . ', Id_competence=' . mysql_real_escape_string((int) $this->competence[$i]) . ', Id_niveau_competence=' . mysql_real_escape_string((int) $this->niveau_competence[$i]));
            ++$i;
        }
    }

    /**
     * Suppression d'un entretien
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM entretien WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
        $db->query('DELETE FROM entretien_langue WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
        $db->query('DELETE FROM entretien_critere WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
        $db->query('DELETE FROM entretien_mobilite WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
        $db->query('DELETE FROM entretien_certification WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
        $db->query('DELETE FROM entretien_competence WHERE Id_entretien = ' . mysql_real_escape_string((int) $this->Id_entretien));
    }

    /**
     * Affichage d'une select box contenant les avancements
     *
     * @return string
     */
    public function getProgressList() {
        $avancement[$this->avancement_recherche] = 'selected="selected"';
        $html = '
			<option value="Début" ' . $avancement['Début'] . '>Début</option>
			<option value="Entretien" ' . $avancement['Entretien'] . '>Entretien</option>
			<option value="Négociation" ' . $avancement['Négociation'] . '>Négociation</option>
			<option value="Proposition" ' . $avancement['Proposition'] . '>Proposition</option>
';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les connaissances de proservia
     *
     * @return string
     */
    public function getProserviaKnowledgeList() {
        $connaissance[$this->connaissance_proservia] = 'selected="selected"';
        while ($i < 6) {
            $html .= '<option value=' . $i . ' ' . $connaissance[$i] . '>' . $i . '</option>';
            ++$i;
        }
        return $html;
    }

    /**
     * Consultation de la fiche entretien
     *
     * @return string
     */
    public function consultation() {
        $db = connecter();
        $nb_competence = count($this->competence);
        if ($nb_competence) {
            $i = 0;
            while ($i < $nb_competence) {
                $comp.= Competence::getLibelle($this->competence[$i]) . ' -> ' . Competence::getLevel($this->niveau_competence[$i]) . '<br />';
                ++$i;
            }
        }
        $nb_critere = count($this->critere_recherche);
        if ($nb_critere) {
            $i = 0;
            while ($i < $nb_critere) {
                $critere.= CritereRecherche::getLibelle($this->critere_recherche[$i]) . '<br />';
                ++$i;
            }
        }
        $nb_certification = count($this->certification);
        if ($nb_certification) {
            $i = 0;
            while ($i < $nb_certification) {
                $cert.= Certification::getLibelle($this->certification[$i]) . '<br />';
                ++$i;
            }
        }
        $nb_langue = count($this->langue);
        if ($nb_langue) {
            $i = 0;
            while ($i < $nb_langue) {
                $lang.= Langue::getLibelle($this->langue[$i]) . ' -> ' . Langue::getLevel($this->niveau_langue[$i]) . '<br />';
                ++$i;
            }
        }
        $tab_mobilite = $this->mobilite->getRegion_souhaitee();
        if ($tab_mobilite) {
            $mob = '';
            $var = empty($tab_mobilite) ? array() : $tab_mobilite;
            foreach ($var as $i) {
                $mobilite = split('-', $i);
                $taille = count($mobilite);
                switch ($taille) {
                    case 1: //cas d'une zone
                        $mob .= $db->query('SELECT libelle FROM zone WHERE Id_zone = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->libelle . '<br />';
                        break;

                    case 2://cas d'une région
                        $mob .= $db->query('SELECT libelle FROM region WHERE Id_region = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->libelle . '<br />';
                        break;

                    case 3://cas d'un département
                        $mob .= $db->query('SELECT nom FROM departement WHERE Id_departement = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->nom . '<br />';
                        break;
                }
            }
        }
        $recruteur = new Utilisateur($this->Id_recruteur, array());
        $commercial = new Utilisateur($this->Id_commercial, array());
        $candidature = new Candidature($this->Id_candidature, array());

        if (array_intersect($candidature->type_contrat, array(1, 2))) {
            $dateHtml = 'Début du stage : ' . FormatageDate($this->debut_stage) . '<br /><br />Fin du stage : ' . FormatageDate($this->fin_stage) . ' <br /><br />';
            $salaireHtml = 'Indemnités de stage : ' . $this->indemnite_stage . ' &euro; <br /><br />';
        }
        if (array_intersect($candidature->type_contrat, array(5))) {
            $salaireHtml .= 'Tarif Journalier : ' . $this->tarif_journalier . ' &euro; <br /><br />';
        }
        $salaireHtml .= 'Prétention salariale (annuel brut) : <br />
		    entre ' . $this->pretention_basse . ' K&euro; et ' . $this->pretention_haute . ' K&euro; <br /><br />';
        $html = '
			<h2>FEUILLE D\'ENTRETIEN</h2>
			<div class="left">
 				Recruteur : 
				    ' . $recruteur->nom . '
			    <br /><br />
				Ingénieur Commercial : 
				    ' . $commercial->nom . '
			    <br /><br />
				Date de disponibilité :
					' . FormatageDate($this->date_disponibilite) . '<br /><br />
				Préavis :
				    ' . Preavis::getLibelle($this->Id_preavis) . ' <br />
				Négociable : ' . yesno($this->preavis_negociable) . '
				<br /><br />
				Mobilité : <br /> ' . $mob . '<br /><br />
				Langue(s) : <br />
				' . $lang . '
				<br /><br />
				Avancement recherche emploi :
                    ' . $this->avancement_recherche . '
				<br /><br />
				Critères de recherche :
                    ' . $critere . '
				<br /><br />
				Connaissance de Proservia : 
                    ' . (int) $this->connaissance_proservia . '
				<br /><br />
				' . $dateHtml . '
				' . $salaireHtml . '
			    Evolution envisagée :
					' . Profil::getLibelle($this->Id_profil_envisage) . '
			    <br /><br />
			    Compétence(s) : <br />
					' . $comp . '
			    <br /><br />				
			    Certification(s) : <br />
					' . $cert . '
			    <br /><br />
			</div>
			<div class="right">
			    Attentes professionnelles actuelles :
                ' . $this->attente_pro . '<br /><br />
				Commentaire RH :
                ' . $this->commentaire_rh . '<br /><br />
				Commentaire COM :
                ' . $this->commentaire_com . '<br /><br />
				Commentaire RT :
                ' . $this->commentaire_rt . '<br /><br />
				Mots clés :
                ' . $this->mot_cle . '				
            </div>
			<div class="clearer"></div>
';
        return $html;
    }

    /**
     * Edition de la partie entretien de la fiche candidature en pdf
     * 
     * param object pdf en cours de création
     * param int identifiant de la ressource correspondant à la candidature
     */
    public function edit(&$pdf, $Id_ressource, $independant) {
        //création des instances pour récupérer les informations concernant le cr, le com et le candidat 
        $recruteur = new Utilisateur($this->Id_recruteur, array());
        $commercial = new Utilisateur($this->Id_commercial, array());
        $ressource = RessourceFactory::create('CAN_AGC', $Id_ressource, array());
        //Récupération du type de préavis
        if ($this->Id_preavis && $this->Id_preavis == 4) {
            $preavis = Preavis::getLibelle($this->Id_preavis);
        } else if ($this->Id_preavis) {
            if ($this->preavis_negociable) {
                $preavis = Preavis::getLibelle($this->Id_preavis) . ' (Négociable)';
            } else {
                $preavis = Preavis::getLibelle($this->Id_preavis) . ' (Non négociable)';
            }
        }
        //récupération des zones de mobilité du candidat : traitement Id_mobilite en tableau 
        //puis selon le nombre de case, recherche de la zone, de la région ou du département
        $tab_mobilite = $this->mobilite->getRegion_souhaitee();
        if ($tab_mobilite) {
            $db = connecter();
            $mob = " ";
            $var = empty($tab_mobilite) ? array() : $tab_mobilite;
            $virgule = "";
            foreach ($var as $i) {
                $mobilite = split('-', $i);
                $taille = count($mobilite);
                switch ($taille) {
                    case 1: //cas d'une zone
                        $mob .= $virgule . $db->query('SELECT libelle FROM zone WHERE Id_zone = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->libelle;
                        $virgule = ", ";
                        break;

                    case 2://cas d'une région
                        $mob .= $virgule . $db->query('SELECT libelle FROM region WHERE Id_region = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->libelle;
                        $virgule = ", ";
                        break;

                    case 3://cas d'un département
                        $mob .= $virgule . $db->query('SELECT nom FROM departement WHERE Id_departement = "' . mysql_real_escape_string($mobilite[$taille - 1]) . '"')->fetchRow()->nom;
                        $virgule = ", ";
                        break;
                }
            }
        }
        $nb_langue = count($this->langue);
        //récupération de toutes les langues parlées du candidat
        if ($nb_langue) {
            $i = 0;
            $virgule = "";
            while ($i < $nb_langue) {
                $lang .= $virgule . Langue::getLibelle($this->langue[$i]) . ' -> ' . Langue::getLevel($this->niveau_langue[$i]);
                $virgule = ", ";
                ++$i;
            }
        }
        $nb_certification = count($this->certification);
        //récupération des certifications du candidat
        if ($nb_certification) {
            $i = 0;
            $virgule = "";
            while ($i < $nb_certification) {
                $cert .= $virgule . Certification::getLibelle($this->certification[$i]);
                $virgule = ", ";
                ++$i;
            }
        }
        //affichage de la date de disponibilité si celle-ci est renseignée
        if ($this->date_disponibilite == "0000-00-00") {
            $date_disponibilite = " ";
        } else {
            $date_disponibilite = FormatageDate($this->date_disponibilite);
        }
        //traitement de toutes les zones de texte pour affichage en pdf
        $attente = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->attente_pro))));
        $com_rh = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->commentaire_rh))));
        $com_com = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->commentaire_com))));
        $com_rt = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->commentaire_rt))));
        $mot_cle = convert_smart_quotes(strip_tags(htmlenperso_decode(str_replace("\r\n", ", ", $this->mot_cle))));
        //constantes pour l'affichage des compétences du candidats
        $nb_competence = count($this->competence);
        $i = 0;
        //début de la partie entretien du pdf
        $y3 = $pdf->GetY();
        $pdf->setLeftMargin(15);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->MultiCellTag(90, 7, "<t2>Recruteur : </t2><t3>{$recruteur->nom}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Préavis : </t2><t3>{$preavis}</t3>", 0, 'L', 0);
        if ($independant) {
            $pdf->MultiCellTag(90, 7, "<t2>Tarif journalier : </t2><t3>{$this->tarif_journalier} K€</t3>", 0, 'L', 0);
            $pdf->setY($pdf->GetY() + 7);
        }
        $pdf->MultiCellTag(90, 7, "<t2>Prétention salariale (annuel brut) : </t2><t3>entre {$this->pretention_basse} K€ et {$this->pretention_haute} K€</t3>", 0, 'L', 0);
        $pdf->setXY(105, $y3);
        $pdf->setLeftMargin(105);
        $pdf->MultiCellTag(90, 7, "<t2>Commercial : </t2><t3>{$commercial->nom}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Date disponibilité : </t2><t3>" . $date_disponibilite . "</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Evolution envisagée : </t2><t3>" . Profil::getLibelle($this->Id_profil_envisage) . "</t3>", 0, 'L', 0);
        $y4 = $pdf->GetY();
        $longueur_mobilite = (int) $pdf->GetStringWidth($mob);
        $nb_ligne1 = (int) ($longueur_mobilite / 70) - 1;
        $y_fin1 = ($y4 + ($nb_ligne1 * 7));
        $longueur_langue = (int) $pdf->GetStringWidth($lang);
        $nb_ligne2 = (int) ($longueur_langue / 70) - 1;
        $y_fin2 = ($y4 + ($nb_ligne2 * 7));
        //si l'affichage est arrivé à la fin de la page, rajoute une nouvelle page
        if ($y4 > 250 || $y_fin1 > 257 || $y_fin2 > 257) {
            $pdf->AddPage();
            $pdf->setXY(100, 20);
            $pdf->SetTextColor(0, 75, 150);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCellTag(80, 2, "{$ressource->civilite}. {$ressource->prenom} {$ressource->nom}", 0, 'C', 0);
            $y4 = $pdf->GetY() + 10;
            $pdf->setLeftMargin(15);
        }
        $pdf->setXY(15, $y4);
        $pdf->setLeftMargin(15);
        $pdf->MultiCellTag(90, 7, "<t2>Mobilité : </t2><t3>{$mob}</t3>", 0, 'L', 0);
        $y5 = $pdf->GetY();
        $pdf->setXY(105, $y4);
        $pdf->setLeftMargin(105);
        $pdf->MultiCellTag(90, 7, "<t2>Langues : </t2><t3>{$lang}</t3>", 0, 'L', 0);
        //sélection du y le plus bas pour continuer l'affichage après
        if ($pdf->GetY() > $y5) {
            $y5 = $pdf->GetY();
        }
        $longueur_mot_cle = (int) $pdf->GetStringWidth($mot_cle);
        $nb_ligne = (int) ($longueur_mot_cle / 70) - 1;
        $y_fin = ($y5 + ($nb_ligne * 7));
        //si l'affichage est arrivé à la fin de la page, rajoute une nouvelle page
        if ($y5 > 250 || $y_fin > 257) {
            $pdf->AddPage();
            $pdf->setXY(100, 20);
            $pdf->SetTextColor(0, 75, 150);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCellTag(80, 2, "{$ressource->civilite}. {$ressource->prenom} {$ressource->nom}", 0, 'C', 0);
            $y5 = $pdf->GetY() + 10;
            $pdf->setLeftMargin(15);
        }
        $pdf->setXY(15, $y5);
        $pdf->MultiCellTag(90, 7, "<t2>Certification : </t2><t3> {$cert} </t3>", 0, 'L', 0);
        $y6 = $pdf->GetY();
        $pdf->setXY(105, $y5);
        $pdf->MultiCellTag(90, 7, "<t2>Mot clés : </t2><t4> {$mot_cle} </t4>", 0, 'L', 0);
        //sélection du y le plus bas pour continuer l'affichage après
        if ($pdf->GetY() > $y6) {
            $y6 = $pdf->GetY();
        }
        $longueur_attente = (int) $pdf->GetStringWidth($attente);
        $nb_ligne = (int) ($longueur_attente / 70) - 1;
        $y_fin1 = ($y6 + ($nb_ligne * 7));
        $longueur_rh = (int) $pdf->GetStringWidth($com_rh);
        $nb_ligne = (int) ($longueur_rh / 70) - 1;
        $y_fin2 = ($y6 + ($nb_ligne * 7));
        //si l'affichage est arrivé à la fin de la page, rajoute une nouvelle page
        if ($y6 > 250 || $y_fin1 > 257 || $y_fin2 > 257) {
            $pdf->AddPage();
            $pdf->setXY(100, 20);
            $pdf->SetTextColor(0, 75, 150);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCellTag(80, 2, "{$ressource->civilite}. {$ressource->prenom} {$ressource->nom}", 0, 'C', 0);
            $y6 = $pdf->GetY() + 10;
            $pdf->setLeftMargin(15);
        }
        $pdf->setXY(15, $y6);
        $pdf->MultiCellTag(90, 7, "<t2>Attentes professionelles : </t2><t4> {$attente}</t4>", 0, 'L', 0);
        $y7 = $pdf->GetY();
        $pdf->setXY(105, $y6);
        $pdf->MultiCellTag(90, 7, "<t2>Commentaire RH : </t2><t4> {$com_rh}</t4>", 0, 'L', 0);
        //sélection du y le plus bas pour continuer l'affichage après
        if ($pdf->GetY() > $y7) {
            $y7 = $pdf->GetY();
        }
        $longueur_com = (int) $pdf->GetStringWidth($com_com);
        $nb_ligne = (int) ($longueur_com / 70) - 1;
        $y_fin1 = ($y7 + ($nb_ligne * 7));
        $longueur_rt = (int) $pdf->GetStringWidth($com_rt);
        $nb_ligne = (int) ($longueur_rt / 70) - 1;
        $y_fin2 = ($y7 + ($nb_ligne * 7));
        //si l'affichage est arrivé à la fin de la page, rajoute une nouvelle page
        if ($y7 > 250 || $y_fin1 > 257 || $y_fin2 > 257) {
            $pdf->AddPage();
            $pdf->setXY(100, 20);
            $pdf->SetTextColor(0, 75, 150);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCellTag(80, 2, "{$ressource->civilite}. {$ressource->prenom} {$ressource->nom}", 0, 'C', 0);
            $y7 = $pdf->GetY() + 10;
            $pdf->setLeftMargin(15);
        }
        $pdf->setXY(15, $y7);
        $pdf->MultiCellTag(90, 7, "<t2>Commentaire COM : </t2><t4> {$com_com}</t4>", 0, 'L', 0);
        $pdf->setXY(105, $y7);
        $pdf->MultiCellTag(90, 7, "<t2>Commentaire RT : </t2><t4> {$com_rt}</t4>", 0, 'L', 0);
    }

}

?>
