<?php

/**
 * Fichier DemandeRessource.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe DemandeChangement
 */
class DemandeRessource {

    /**
     * Identifiant de la demande
     *
     * @access private
     * @var int
     */
    private $Id_demande_ressource;

    /**
     * Identifiant de l'affaire associée à la demande
     *
     * @access public
     * @var int
     */
    public $Id_affaire;

    /**
     * Référence de l'affaire associée à la demande
     *
     * @access public
     * @var int
     */
    public $reference_affaire;

    /**
     * Identifiant du chargé de recrutement
     *
     * @access public
     * @var string
     */
    public $Id_cr;

    /**
     * Identifiant de l'ingénieur commercial
     *
     * @access public
     * @var string
     */
    public $Id_ic;

    /**
     * Demande archivé ou non
     *
     * @access public
     * @var boolean
     */
    public $archive;

    /**
     * Date de création de la demande
     *
     * @access public
     * @var date
     */
    public $date;

    /**
     * Type de profil recherché
     *
     * @access public
     * @var string
     */
    public $profil;

    /**
     * Complément pour le type de profil recherché
     *
     * @access public
     * @var string
     */
    public $com_profil;

    /**
     * Commentaire relatif à la demande de recrutement
     *
     * @access public
     * @var string
     */
    public $commentaire;

    /**
     * Lien vers une annonce si existante
     *
     * @access public
     * @var string
     */
    public $annonce;

    /**
     * Etat de la demande (en cours, fin de veille, NC, pourvu, non pourvu, veille)
     *
     * @access public
     * @var string
     */
    public $statut;

    /**
     * Client pour lequel travaillera la ressource
     *
     * @access public
     * @var string
     */
    public $client;

    /**
     * Statut de l'affaire référente
     *
     * @access public
     * @var string
     */
    public $statut_affaire;

    /**
     * Lieu pour lequel travaillera la ressource
     *
     * @access public
     * @var string
     */
    public $lieu;

    /**
     * Agence de rattachement de la ressource
     *
     * @access public
     * @var string
     */
    public $agence;

    /**
     * Date de réponse souhaitée
     *
     * @access public
     * @var string
     */
    public $date_reponse;

    /**
     * Tableau contenant les candidats d'une demande
     *
     * @access public
     * @var array
     */
    public $candidats;

    /**
     * Tableau contenant l'historiques des statuts d'une demande
     *
     * @access public
     * @var array
     */
    public $statuts;

    /**
     * Compétences d'un poste
     *
     * @access public
     * @var string
     */
    public $competences;

    /**
     * Fourchette de salaire d'un poste
     *
     * @access public
     * @var string
     */
    public $salaire_debut;

    /**
     * Fourchette de salaire d'un poste
     *
     * @access public
     * @var string
     */
    public $salaire_fin;

    /**
     * Description d'un poste
     *
     * @access public
     * @var string
     */
    public $description;

    /**
     * Diplome exigé pour un poste
     *
     * @access public
     * @var string
     */
    private $diplome;

    /**
     * Année d'experience pour un poste
     *
     * @access public
     * @var string
     */
    public $experience;

    /**
     * Date de début du poste
     *
     * @access public
     * @var string
     */
    public $date_mission;

    /**
     * Durée du poste
     *
     * @access public
     * @var string
     */
    public $duree_mission;

    /**
     * Id du candidat retenu pour l'affaire
     *
     * @access private
     * @var int
     */
    private $candidat_retenu;

    /**
     * Indique si la demande est prioritaire ou non
     *
     * @access private
     * @var boolean
     */
    private $prioritaire;

    /**
     * Type de recrutement pour la demande
     *
     * @access public
     * @var int
     */
    public $type_demande;

    /**
     * Constructeur de la classe DemandeRessource
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la demande
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_demande_ressource = '';
                $this->Id_affaire = $_GET['Id_affaire'];
                $this->reference_affaire = $_GET['reference_affaire'];
                $this->Id_cr = '';
                $this->Id_ic = '';
                $this->archive = '';
                $this->date = DATEFR;
                $this->profil = '';
                $this->comp_profil = '';
                $this->commentaire = '';
                $this->annonce = '';
                $this->statut = '';
                $this->client = '';
                $this->statut_affaire = '';
                $this->lieu = '';
                $this->agence = '';
                $this->date_reponse = '';
                $this->candidats = array();
                $this->competences = '';
                $this->salaire_debut = '';
                $this->salaire_fin = '';
                $this->description = '';
                $this->diplome = '';
                $this->experience = '';
                $this->date_mission = '';
                $this->duree_mission = '';
                $this->candidat_retenu = '';
                $this->prioritaire = '';
                $this->type_demande = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_demande_ressource = '';
                $this->Id_affaire = $tab['Id_affaire'];
                $this->reference_affaire = (int) $tab['reference_affaire'];
                $this->Id_cr = $tab['cr'];
                $this->Id_ic = (empty($tab['ic'])) ? $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur : $tab['ic'];
                $this->profil = htmlscperso(stripslashes($tab['profil']), ENT_QUOTES);
                $this->comp_profil = htmlscperso(stripslashes($tab['comp_profil']), ENT_QUOTES);
                $this->commentaire = htmlscperso(stripslashes($tab['commentaire']), ENT_QUOTES);
                $this->annonce = htmlscperso(stripslashes($tab['annonce']), ENT_QUOTES);
                $this->statut = htmlscperso(stripslashes($tab['statut']), ENT_QUOTES);
                $this->client = htmlscperso(stripslashes($tab['client']), ENT_QUOTES);
                $this->statut_affaire = htmlscperso(stripslashes($tab['statut_affaire']), ENT_QUOTES);
                $this->lieu = htmlscperso(stripslashes($tab['lieu']), ENT_QUOTES);
                $this->agence = htmlscperso(stripslashes($tab['agence']), ENT_QUOTES);
                $this->date_reponse = htmlscperso(stripslashes($tab['date_reponse']), ENT_QUOTES);
                $this->date = htmlscperso(stripslashes($tab['date_demande']), ENT_QUOTES);
                $this->competences = htmlscperso(stripslashes($tab['competences']), ENT_QUOTES);
                $this->salaire_debut = htmlscperso(stripslashes($tab['salaire_debut']), ENT_QUOTES);
                $this->salaire_fin = htmlscperso(stripslashes($tab['salaire_fin']), ENT_QUOTES);
                $this->description = htmlscperso(stripslashes($tab['description']), ENT_QUOTES);
                $this->diplome = htmlscperso(stripslashes($tab['diplome']), ENT_QUOTES);
                $this->experience = htmlscperso(stripslashes($tab['experience']), ENT_QUOTES);
                $this->date_mission = htmlscperso(stripslashes($tab['date_mission']), ENT_QUOTES);
                $this->duree_mission = htmlscperso(stripslashes($tab['duree_mission']), ENT_QUOTES);
                $this->candidat_retenu = htmlscperso(stripslashes($tab['candidat_retenu']), ENT_QUOTES);
                $this->prioritaire = (int) $tab['prioritaire'];
                $this->type_demande = (int) $tab['type_demande'];

                if (isset($tab['candidats'])) {
                    $this->candidats = $tab['candidats'];
                } else {
                    $i = 0;
                    $c = count($tab['dateCandidat']);
                    while ($i < $c) {
                        $this->candidats[$i]['date'] = $tab['dateCandidat'][$i];
                        $this->candidats[$i]['Id_ressource'] = $tab['candidat'][$i];
                        $this->candidats[$i]['Id_cr'] = $tab['crCandidat'][$i];
                        $this->candidats[$i]['commentaire'] = $tab['commentaireCandidat'][$i];
                        $i++;
                    }
                }
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $this->Id_demande_ressource = $code;
                $db = connecter();
                $ligne = $db->query('SELECT Id_affaire, reference_affaire FROM affaire_demande_ressource WHERE Id_demande_ressource =' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_affaire = $ligne->id_affaire;
                $this->reference_affaire = $ligne->reference_affaire;
                $result = $db->query('SELECT * FROM demande_ressource WHERE Id_demande_ressource =' . mysql_real_escape_string((int) $code));
                $ligne = $result->fetchRow();

                $this->Id_cr = $ligne->id_cr;
                $this->Id_ic = $ligne->id_ic;
                $this->statut = $db->queryOne('SELECT Id_statut FROM historique_statut_demande_ressource WHERE Id_demande_ressource = ' . mysql_real_escape_string($code) . ' ORDER BY date DESC LIMIT 0, 1;');
                $this->client = $ligne->client;
                $this->statut_affaire = $ligne->statut_affaire;
                $this->date = DateMysqltoFr($ligne->date, 'fr');
                $this->profil = $ligne->profil;
                $this->comp_profil = $ligne->comp_profil;
                $this->lieu = $ligne->lieu;
                $this->agence = $ligne->agence;
                $this->commentaire = $ligne->commentaire;
                $this->annonce = $ligne->annonce;
                $this->date_reponse = $ligne->date_reponse;
                $this->competences = $ligne->competences;
                $this->salaire_debut = $ligne->salaire_debut;
                $this->salaire_fin = $ligne->salaire_fin;
                $this->description = $ligne->description;
                $this->diplome = $ligne->diplome;
                $this->experience = $ligne->experience;
                $this->date_mission = DateMysqltoFr($ligne->date_mission, 'fr');
                $this->duree_mission = $ligne->duree_mission;
                $this->candidat_retenu = $ligne->candidat_retenu;
                $this->prioritaire = $ligne->prioritaire;
                $this->type_demande = $ligne->type_demande;
                $this->libelleAgence = $db->queryOne('SELECT libelle FROM agence WHERE Id_agence = "' . mysql_real_escape_string($this->agence) . '"');
                $result = $db->query('SELECT Id_candidat, Id_demande_ressource, Id_ressource, Id_cr, DATE_FORMAT(date, "%d-%m-%Y") as datefr, commentaire FROM demande_ressource_candidat WHERE Id_demande_ressource =' . mysql_real_escape_string((int) $code) . ' ORDER BY date DESC');
                $i = 0;
                while ($row = $result->fetchRow(MDB2_FETCHMODE_OBJECT)) {
                    $this->candidats[$i]['Id_candidat'] = $row->id_candidat;
                    $this->candidats[$i]['Id_demande_ressource'] = $row->id_demande_ressource;
                    $this->candidats[$i]['Id_ressource'] = $row->id_ressource;
                    $this->candidats[$i]['Id_cr'] = $row->id_cr;
                    $this->candidats[$i]['date'] = $row->datefr;
                    $this->candidats[$i]['commentaire'] = $row->commentaire;
                    $i++;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_demande_ressource = $code;
                $this->Id_affaire = $tab['Id_affaire'];
                $this->reference_affaire = $tab['reference_affaire'];
                $this->Id_cr = $tab['cr'];
                $this->Id_ic = (empty($tab['ic'])) ? $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur : $tab['ic'];
                $this->profil = htmlscperso(stripslashes($tab['profil']), ENT_QUOTES);
                $this->comp_profil = htmlscperso(stripslashes($tab['comp_profil']), ENT_QUOTES);
                $this->commentaire = htmlscperso(stripslashes($tab['commentaire']), ENT_QUOTES);
                $this->annonce = htmlscperso(stripslashes($tab['annonce']), ENT_QUOTES);
                $this->statut = htmlscperso(stripslashes($tab['statut']), ENT_QUOTES);
                $this->client = htmlscperso(stripslashes($tab['client']), ENT_QUOTES);
                $this->statut_affaire = htmlscperso(stripslashes($tab['statut_affaire']), ENT_QUOTES);
                $this->lieu = htmlscperso(stripslashes($tab['lieu']), ENT_QUOTES);
                $this->agence = htmlscperso(stripslashes($tab['agence']), ENT_QUOTES);
                $this->date_reponse = htmlscperso(stripslashes($tab['date_reponse']), ENT_QUOTES);
                $this->date = htmlscperso(stripslashes($tab['date_demande']), ENT_QUOTES);
                $this->competences = htmlscperso(stripslashes($tab['competences']), ENT_QUOTES);
                $this->salaire_debut = htmlscperso(stripslashes($tab['salaire_debut']), ENT_QUOTES);
                $this->salaire_fin = htmlscperso(stripslashes($tab['salaire_fin']), ENT_QUOTES);
                $this->description = htmlscperso(stripslashes($tab['description']), ENT_QUOTES);
                $this->diplome = htmlscperso(stripslashes($tab['diplome']), ENT_QUOTES);
                $this->experience = htmlscperso(stripslashes($tab['experience']), ENT_QUOTES);
                $this->date_mission = htmlscperso(stripslashes($tab['date_mission']), ENT_QUOTES);
                $this->duree_mission = htmlscperso(stripslashes($tab['duree_mission']), ENT_QUOTES);
                $this->candidat_retenu = htmlscperso(stripslashes($tab['candidat_retenu']), ENT_QUOTES);
                $this->prioritaire = (int) $tab['prioritaire'];
                $this->type_demande = (int) $tab['type_demande'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création d'une demande de recrutement
     *
     * @return string
     */
    public function form() {
        $prioritaire[$this->prioritaire] = 'checked="checked"';
        $type_demande[$this->type_demande] = 'selected="selected"';
        $recruteur[$this->Id_cr] = 'selected="selected"';
        $action = '../membre/index.php?a=envoyer_demande_ressource&Id_affaire=' . $this->Id_affaire;
        $cr = new Utilisateur($this->Id_cr, array());
        $profil = new Profil($this->profil, array());
        if ($this->Id_affaire) {
            if (is_numeric($this->Id_affaire)) {
                $affaire = new Affaire($this->Id_affaire, array());
                $description = new Description($affaire->Id_description, array());
                $resume = $description->resume;
                $planning = new Planning($affaire->Id_planning, array());
                $dateDebut = DateMysqltoFr($planning->date_debut, 'fr');
                $intitule = Intitule::getLibelle($description->Id_intitule);
                $statut = Statut::getLibelle($affaire->Id_statut);
                $typeContrat = TypeContrat::getLibelle($affaire->Id_type_contrat);
                $link = '../com/index.php?a=afficherAffaire&Id_affaire=' . $this->Id_affaire;
                $profilList = $profil->getList();
            } else {
                $affaire = new Opportunite($this->Id_affaire, array());
                $dateDebut = DateMysqltoFr($affaire->date_debut, 'fr');
                $resume = $affaire->description;
                $intitule = $affaire->Id_intitule;
                $statut = $affaire->Id_statut;
                $typeContrat = $affaire->Id_type_contrat;
                $link = SF_URL . $this->Id_affaire;
                $profilList = $affaire->getProfilList($this->profil);
                $onChange = 'onChange="e = $$(\'#profil option\').find(function(ele){return !!ele.selected}); if(e.salaire){$(\'salaire_fin\').value = e.salaire;}else if(e.attributes[3]){$(\'salaire_fin\').value = e.attributes[3].value;}if(e.text==\'Forfait de service sur CDS\'||e.text==\'Forfait de service sur SITE\'||e.text==\'Forfait de négoce\'){$(\'salaire_fin\').writeAttribute(\'readonly\', null);} else{$(\'salaire_fin\').writeAttribute(\'readonly\', \'readonly\');}"';
                $readonlysalaire = 'readonly="readonly"';
            }
        } else {
            $profilList = $profil->getList();
        }
        $compte = CompteFactory::create(null, $affaire->Id_compte);

        $ic = (empty($this->Id_ic)) ? new Utilisateur($affaire->commercial, array()) : new Utilisateur($this->Id_ic, array());

        $statuts = (!empty($this->statut)) ? $this->getStatutListe($this->statut) : $this->getStatutListe();
        $this->client = (empty($this->client)) ? $compte->nom : $this->client;
        $this->lieu = (empty($this->lieu)) ? $description->ville : $this->lieu;
        $this->agence = (empty($this->agence)) ? $ic->Id_agence : $this->agence;
        $this->description = (empty($this->description)) ? $resume : $this->description;
        $expinfo = new Experience($this->experience, array());
        $this->date_mission = (empty($this->date_mission)) ? $dateDebut : $this->date_mission;
        if (empty($this->competences) && !empty($affaire->competence)) {
            $i = 0;
            foreach ($affaire->competence as $competence) {
                $i++;
                $competences .= '- ' . Competence::getLibelle($competence);
                $competences .= ( $i == count($affaire->competence)) ? '' : "\n";
            }
            $this->competences = $competences;
        }
        if (empty($this->diplome)) {
            $cursus = new Cursus($description->Id_cursus, array());
        } else {
            $cursus = new Cursus($this->diplome, array());
        }
        $readonly = ($this->statut == 2) ? '' : 'disabled="disabled"';
        if ($this->salaire_debut != 0) {
            $htmlSal = '<label for="salaire_debut">Salaire entre </label>
		    	<input name="salaire_debut" id="salaire_debut" type="text" value="' . $this->salaire_debut . '" size="8">
		    	<label for="salaire_fin"> et </label>';
        } else {
            $htmlSal = '<label for="salaire_fin">Salaire maximum : </label>';
        }

        $html = '
		<form name="demande_ressource" enctype="multipart/form-data" action="' . $action . '" method="post">
                    
		<div style="width:100%;margin:auto;">';
        if (!empty($this->Id_demande_ressource))
            $html .= '<a href="#" onclick="recapDemandeRessource(' . $this->Id_demande_ressource . ')">Envoyer le récapitulatif de la demande au commercial</a><br /><br />';
        $html .= '
		<h2>Informations Demande</h2>
		<br />
		<div style="padding-left:5%;">Les champs en blanc sont à remplir par les commerciaux. Les champs en violet sont à remplir par les chargés de recrutement.</div>
		<br />';
        if ($this->Id_affaire) {
            $html .= '<div style="padding-left:5%;">
			Intitulé de l\'affaire : ' . $intitule . '<br />
			Client de l\'affaire : ' . $compte->nom . '<br />
			Statut de l\'affaire : ' . $statut . '<br />
                        <input type="hidden" name="statut_affaire" value="' . $statut . '" />
			Type d\'affaire : ' . $typeContrat . '<br />
			<a href="' . $link . '" target="_blank" >Lien vers l\'affaire</a></div><br />';
        }
        /* if ($this->Id_affaire) {
          $disable = 'readonly="readonly"';
          } */
        $html .= '<div class="left"><label for="Id_affaire">Identifiant de l\'affaire : </label><input name="affaire" id="affaire" type="text" value="' . $this->reference_affaire . '" /><br /><br />';
        $html .= '
                        <span class="infoFormulaire"> * </span>
		    	<label for="date_demande">Date :</label>
		    	<input name="date_demande" id="date_demande" type="text" value="' . $this->date . '" /><br /><br />
		    	<span class="infoFormulaire"> * </span>
		    	<label for="profil">Profil :</label>
                        <select id="profil" name="profil" ' . $onChange . '>
                            <option value="">' . PROFIL_SELECT . '</option>
                            <option value="">----------------------------</option>
			        ' . $profilList . '
                        </select><span id="desc"></span><br /><br />
                <label for="comp_profil">Intitulé :</label>
		    	<input name="comp_profil" id="comp_profil" type="text" value="' . $this->comp_profil . '" size="40"><br /><br />
		    	<span class="infoFormulaire"><img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>Non obligatoire dans le cas d\\\'une demande en veille.</div>\', FULLHTML);" onmouseout="return nd();"></img> (*) </span>
		    	<label for="client">Client :</label>
		    	<input name="client" id="client" type="text" value="' . $this->client . '" autocomplete="off">
		    	<div style="display: none; position: absolute;" id="updateClient"></div><br /><br />
		    	<script type="text/javascript">getListeClient()</script>
		    	<span class="infoFormulaire"> * </span>
		    	<label for="lieu">Lieu :</label>
		    	<input name="lieu"  id="lieu" type="text" value="' . $this->lieu . '"><br /><br />
		    	<label for="commentaire">Commentaire :</label><br />
		    	<textarea name="commentaire"  id="commentaire" type="text" rows="5" cols="30">' . $this->commentaire . '</textarea>
		    </div>
		    <div class="right">
                        <span class="infoFormulaire"> * </span>
                        <label for="type_demande">Type de recrutement :</label>
                        <select id="type_demande" name="type_demande">
                            <option value="">' . TYPE_SELECT . '</option>
                            <option value="">----------------------------</option>
                            <option value="0" ' . $type_demande[0] . '>Nouvelle affaire</option>
                            <option value="1" ' . $type_demande[1] . '>Remplacement collaborateur</option>
                            <option value="2" ' . $type_demande[2] . '>Sur profil</option>
                        </select>
                        <br /><br />
                        <div id="statut_actuel" >
                            <span class="infoFormulaire"> * </span>
                            <label for="statut">Statut :</label>
                            <select style="background-color:#7C4199;color:white;" name="statut" id="statut" onChange="updateStatutDemandeRessource(this.value);">
                                <option value="">' . STATUS_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $statuts . '
                            </select>
                        </div>
                        <br />
                        <div id="historique_statut">
                            ' . $this->getStatusHistory() . '
                        </div>
                        <br /><br />
				<label for="candidat_retenu">Candidat retenu :</label>
				<select name="candidat_retenu" id="candidat_retenu" ' . $readonly . '>
					<option value="">' . RESSOURCE_SELECT . '</option>
					<option value="">----------------------------</option>
                	';
        $html .= ( count($this->candidats) != 0) ? $this->getApplicantsList($this->candidat_retenu) : '';
        $html .= '
				</select>
                                <br /><br />
				<span class="infoFormulaire"> * </span>
		    	<label for="ic">IC :</label>
		    	<select id="ic" name="ic">
					<option value="">' . MARKETING_PERSON_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $ic->getList('COM') . '
					<option value="">----------------------------</option>
					' . $ic->getList('OP') . '
                                        <option value="">----------------------------</option>
					' . $ic->getList('ADI') . '
                </select><br /><br />
                <span class="infoFormulaire"> * </span>
		    	<label for="cr">CR :</label>
		    	<select id="cr" name="cr">
					<option value="">' . RECRUITER_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $cr->getList('RH') . '
                </select><br /><br />
                <span class="infoFormulaire"> * </span>
		    	<label for="agence">Agence de rattachement :</label>
                <select id="agence" name="agence" >
				<option value="">Par agence de rattachement</option>
				<option value="">----------------------------</option>
				' . Agence::getSelectList(array($this->agence)) . '
				</select><br /><br />
                <label for="date_reponse">Date réponse :</label>
		    	<input name="date_reponse" id="date_reponse" type="text" value="' . $this->date_reponse . '"><br /><br />
		    	<label for="annonce">Annonce :</label>
		    	<input name="annonce" id="annonce" type="text" value="' . $this->annonce . '" size="30" style="background-color:#7C4199;color:white;"><br /><br />
		    	<label for="prioritaire">Prioritaire :</label><input type="checkbox" id="prioritaire" name="prioritaire" value="1" ' . $prioritaire['1'] . ' />
		    </div>
		    <div class="clearer">
		    	<br />
		    	<hr />
		    	<br />
		    	<h2>Fiche Demande</h1>
		    </div>
		    <div class="left">
		    	<span class="infoFormulaire"> * </span>
		    	' . $htmlSal . '
		    	<input name="salaire_fin" id="salaire_fin" type="text" value="' . $this->salaire_fin . '" size="8" ' . $readonlysalaire . '> K€<br /><br />
		    	<label for="diplome">Niveau de diplôme exigé :</label>
		    	<select name="diplome">
                	<option value="">' . CURSUS_SELECT . '</option>
			    	<option value="">-------------------------</option>
                    ' . $cursus->getList() . '
                </select><br /><br />
		    	<label for="experience">Nombre d’années d’expérience exigé :</label>
			    <select name="experience">
                    <option value="">' . DURATION_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $expinfo->getList() . '
                </select><br /><br />
		    	<label for="description"><span class="infoFormulaire"> * </span>Description du poste (merci de développer au maximum les fonctions de cette mission) :</label><br />
		    	<textarea name="description" id="description" type="text" rows="5" cols="30">' . strip_tags($this->description) . '</textarea><br /><br />
		    </div>
		    <div class="right">
		    	<label for="date_mission">Date de démarrage de la mission :</label>
		    	<input name="date_mission" id="date_mission" onfocus="showCalendarControl(this)" type="text" value="' . $this->date_mission . '" size="8"><br /><br />
		    	<span class="infoFormulaire"> * </span>
		    	<label for="duree_mission">Durée de la mission (si la durée n\'est pas connue, préciser le type de contrat CDD/CDI) :</label><br />
		    	<input name="duree_mission" id="duree_mission" type="text" value="' . $this->duree_mission . '"><br /><br />
		    	<label for="competences"><span class="infoFormulaire"> * </span>Compétences requises :</label><br />
		    	<textarea name="competences" id="competences" type="text" rows="5" cols="30">' . $this->competences . '</textarea>
		    </div>
            <div class="submit">
                <input type="hidden" name="Id_affaire" id="Id_affaire" value="' . $this->Id_affaire . '" />
            	<input type="hidden" id="reference_affaire" name="reference_affaire" value="' . $this->reference_affaire . '" />
            	<input type="hidden" id="Id_demande_ressource" name="Id_demande_ressource" value="' . $this->Id_demande_ressource . '" />
                <input type="hidden" name="class" value="' . __CLASS__ . '" />
                <button type="submit" class="button save" value="' . SAVE_BUTTON . '" onclick="return verifDemandeRessource(this.form)">' . SAVE_BUTTON . '</button>
            </div>
        </div>';

        $proposition = new Proposition(Affaire::lastProposition($this->Id_affaire), array());
        if (!$bRess = empty($proposition->ressource) || !$bRessI = empty($proposition->ressource_i)) {
            $html .= '<hr /><h2>Collaborateurs positionnés</h2><br /><div style="text-align:center;">';
            if (!$bRess) {
                foreach ($proposition->ressource as $ressource) {
                    $ressource = RessourceFactory::create('CAN_AGC', $ressource, null);
                    //$ressource = new Ressource($ressource, array());
                    if (Ressource::getIdCandidature($ressource->Id_ressource))
                        $html .= '<a href="../membre/index.php?a=afficherCandidature&Id_candidature=' . Ressource::getIdCandidature($ressource->Id_ressource) . '">' . $ressource->nom . ' ' . $ressource->prenom . '</a><br />';
                    else
                        $html .= $ressource->nom . ' ' . $ressource->prenom . '<br />';
                }
            }

            if (!$bRessI) {
                foreach ($proposition->ressource_i as $ressource) {
                    $ressource = RessourceFactory::create('CAN_AGC', $ressource, null);
                    if (Ressource::getIdCandidature($ressource->Id_ressource))
                        $html .= '<a href="../membre/index.php?a=afficherCandidature&Id_candidature=' . Ressource::getIdCandidature($ressource->Id_ressource) . '">' . $ressource->nom . ' ' . $ressource->prenom . '</a><br />';
                    else
                        $html .= $ressource->nom . ' ' . $ressource->prenom . '<br />';
                }
            }
            $html .= '</div><br />';
        }

        if (!empty($this->Id_demande_ressource))
            $html .= $this->applicantForm();
        else
            $html .= $this->applicantForm2();

        $html .= '</form>';
        return $html;
    }

    /**
     * Formulaire de création d'un candidat identifié
     *
     * @return string
     */
    public function applicantForm() {
        $db = connecter();
        $candidats = new Candidature(0, array());
        $html = '<hr />
		<h2>Ressources Proposées</h2>
		<br />
		<div><table style="border-collapse:collapse">';
        $nbCandidat = count($this->candidats);
        $i = 0;
        while ($i < $nbCandidat) {
            $k = ($i % 2 == 1) ? 'odd' : 'even';
            $j = $i + 1;
            $cr = new Utilisateur($this->candidats[$i]['Id_cr'], array());
            $res = RessourceFactory::create('CAN_AGC', $this->candidats[$i]['Id_ressource'], null);
            $candidature = Ressource::getIdCandidature($this->candidats[$i]['Id_ressource']);
            $candidature = new Candidature($candidature, array());
            $res = $db->query('SELECT ec.libelle, Id_candidature,lien_cv FROM etat_candidature ec
        				RIGHT JOIN candidature c ON ec.Id_etat_candidature = c.Id_etat 
        				INNER JOIN demande_ressource_candidat drc ON c.Id_ressource = drc.Id_ressource 
        				WHERE drc.Id_demande_ressource = ' . mysql_real_escape_string($this->Id_demande_ressource) . ' AND drc.Id_ressource = ' . mysql_real_escape_string($this->candidats[$i]['Id_ressource']));
            $ligne2 = $res->fetchRow();
            $candidat = RessourceFactory::create('CAN_AGC', $this->candidats[$i]['Id_ressource'], null);
            $html .= '<tr class="row' . $k . '">
				<td style="padding-top:5px;"><label for="dateCandidat' . ($j) . '">Date :</label>
		    	<input name="dateCandidat' . ($j) . '" id="dateCandidat' . ($j) . '" onfocus="showCalendarControl(this)" type="text" value="' . $this->candidats[$i]['date'] . '" size="8"></td>
		    	<td style="padding-top:5px;"><label for="candidat' . ($j) . '">Candidat :</label>
		    	' . $candidat->nom . ' ' . $candidat->prenom . '</td>
		    	<td style="padding-top:5px;"><label for="crCandidat' . ($j) . '">CR :</label>
		    	<select id="crCandidat' . ($j) . '" name="crCandidat' . ($j) . '">
					<option value="">' . RECRUITER_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $cr->getList('RH') . '
	                </select></td>
		    	<td style="padding-top:5px;"><label for="commentaireCandidat' . ($j) . '">Commentaire :</label>
		    	<input name="commentaireCandidat' . ($j) . '" id="commentaireCandidat' . ($j) . '" type="text" value="' . $this->candidats[$i]['commentaire'] . '" size="50">
		    	<input id="Id_candidat' . ($j) . '" type="hidden" value="' . $this->candidats[$i]['Id_candidat'] . '" name="Id_candidat' . ($j) . '"/>
		    	<input id="candidat' . ($j) . '" type="hidden" value="' . $this->candidats[$i]['Id_ressource'] . '" name="Id_candidat' . ($j) . '"/>
		    	<input id="Id_candidature' . ($j) . '" type="hidden" value="' . $candidature->Id_candidature . '" name="Id_candidature' . ($j) . '"/></td>
		    	<td style="padding-top:5px;"><input type="button" id="buttonCandidat' . ($j) . '" name="buttonCandidat' . ($j) . '" value="Mettre à jour" onclick="majCandidatDemandeRessource(' . ($j) . ')" /></td>
		    	<td style="padding-top:5px;"><input type="button" onclick="if (confirm(\'Voulez vous supprimer ce candidat ?\')) { suppressionCandidatDemandeRessource(\'' . $this->candidats[$i]['Id_candidat'] . '\') }" class="boutonSupprimer"></td></tr>
		    	<tr class="row' . $k . '"><td colspan="6"><div id="etatCandidat' . $j . '">Etat candidature : ' . $ligne2->libelle . ' - <a href="../membre/index.php?a=afficherCandidature&Id_candidature=' . $ligne2->id_candidature . '" target="_blank">Lien vers la fiche du candidat</a> | <a href="../membre/index.php?a=ouvrirCV&cv=' . CV_DIR . $ligne2->lien_cv . '"><img src="' . IMG_CV . '"></a></td>
		    	</tr>
		    	<tr class="row' . $k . '">
		    	<td style="padding-bottom:5px;border-bottom:1px solid #000;" colspan="6">Action : <select name="Id_action_mener" onchange="updateActionDemandeRessource(this.value,' . $j . ')">
                        <option value="">' . ACTION_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $candidature->getActionMener() . '
				    </select><br />
		    	<div id="historique_ressource' . ($j) . '">' . $candidature->actionDemandeRessource($this->candidats[$i]['Id_candidat'], $j) . '</div>
		    	</td>
		    	</tr>
		    	</div>';
            $i++;
        }
        $cr = new Utilisateur(0, array());
        $html .= '<tr>
				<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="dateCandidat' . ($nbCandidat + 1) . '">Date :</label>
		    	<input name="dateCandidat' . ($nbCandidat + 1) . '" id="dateCandidat' . ($nbCandidat + 1) . '" onfocus="showCalendarControl(this)" type="text" value="' . DATEFR . '" size="8"></td>
				<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="candidat' . ($nbCandidat + 1) . '">Candidat :</label>
				<select id="candidat' . ($nbCandidat + 1) . '" name="candidat' . ($nbCandidat + 1) . '" onChange="majEtatCandidat(' . ($nbCandidat + 1) . ');">
					<option value="">' . RESSOURCE_SELECT . '</option>
					' . $candidats->getApplicantList(3, '') . '
	                </select></td>
		    	<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="crCandidat' . ($nbCandidat + 1) . '">CR :</label>
		    	<select id="crCandidat' . ($nbCandidat + 1) . '" name="crCandidat' . ($nbCandidat + 1) . '">
					<option value="">' . RECRUITER_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $cr->getList('RH') . '
	                </select></td>
		    	<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="commentaireCandidat' . ($nbCandidat + 1) . '">Commentaire :</label>
		    	<input name="commentaireCandidat' . ($nbCandidat + 1) . '" id="commentaireCandidat' . ($nbCandidat + 1) . '" type="text" value="' . $this->candidats[$i]['commentaire'] . '" size="50"></td>
		    	<input id="Id_candidat' . ($i + 1) . '" type="hidden" value="" name="Id_candidat' . ($i + 1) . '"/></td>
		    	<td style="padding-top:5px;border-top:1px solid #ddd;" colspan="2"><input type="button" id="buttonCandidat' . ($i + 1) . '" name="buttonCandidat' . ($i + 1) . '" value="Proposer" onclick="ajoutCandidatDemandeRessource()" /></td></tr>
		    	<tr><td style="padding-bottom:5px;border-bottom:1px solid #ddd;" colspan="6"><div id="etatCandidat' . ($nbCandidat + 1) . '">Etat candidature : </div>
		    	<div id="autreCandidatRessource' . ($nbCandidat + 2) . '">
					<input id="nb_candidat_ressource" type="hidden" value="' . ($nbCandidat + 1) . '" name="nb_candidat_ressource[]"/>
				</div></td>
				</tr>
	    </table></div>';
        return $html;
    }

    /**
     * Formulaire de création d'un candidat identifié
     *
     * @return string
     */
    public function applicantForm2() {
        $candidats = new Candidature(0, array());
        $html = '<hr />
		<h2>Ressources Proposées</h2>
		<br />
		<div>
		<table style="border-collapse:collapse">';
        $cr = new Utilisateur(0, array());
        //<input name="filterApplicant" id="filterApplicant" type="text" value="" size="4" onKeyUp="majListeCandidat(this, 1)">
        $html .= '<tr>
				<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="dateCandidat1">Date :</label>
		    	<input name="dateCandidat1" id="dateCandidat1" onfocus="showCalendarControl(this)" type="text" value="' . DATEFR . '" size="8"></td>
				<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="candidat1">Candidat :</label>
                    <select id="candidat1" name="candidat1" onChange="majEtatCandidat(1);">
					<option value="">' . RESSOURCE_SELECT . '</option>
					' . $candidats->getApplicantList(3, '') . '
	            </select></td>
		    	<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="crCandidat1">CR :</label>
		    	<select id="crCandidat1" name="crCandidat1">
					<option value="">' . RECRUITER_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $cr->getList('RH') . '
	                </select></td>
		    	<td style="padding-top:5px;border-top:1px solid #ddd;"><label for="commentaireCandidat1">Commentaire :</label>
		    	<input name="commentaireCandidat1" id="commentaireCandidat1" type="text" value="' . $this->candidats[$i]['commentaire'] . '" size="50"></td>
		    	<input id="Id_candidat1" type="hidden" value="" name="Id_candidat1"/></td><td><input type="button" id="buttonCandidat1" name="buttonCandidat1" value="Proposer" onclick="ajoutCandidatDemandeRessource(1)" /></td></tr>
		    	<tr><td style="padding-bottom:5px;border-bottom:1px solid #ddd;" colspan="6"><div id="etatCandidat1">Etat candidature : </div>
                        <div id="autreCandidatRessource2">
					<input id="nb_candidat_ressource" type="hidden" value="1" name="nb_candidat_ressource[]"/>
				</div></td>
				</tr>
	    </table></div>';
        return $html;
    }

    /**
     * Recherche d'une demande de recrutement
     *
     * @param int Numéro de l'affaire lié
     * @param string Profil de la demande
     * @param string Statut de l'annonce
     * @param date Permet de trier par rapport à la date de la demande
     * @param string Lieu de l'affaire
     * @param string Identifiant du commercial
     * @param string Identifiant du chargé de recrutement
     * @param boolean Affiche ou non les candidats correspondant à la demande
     *
     * @return string
     */
    public static function search($Id_affaire, $profil, $statut, $date_debut, $date_fin, $agence = array(), $ic, $cr, $archive, $action, $h_cr, $h_date_debut, $h_date_fin, $h_statut, $prioritaire = 0, $type_recrutement = '', $Id_demande_ressource = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_affaire_demande', 'profil', 'statut', 'dr_debut', 'dr_fin', 'agenceDemandesRessource', 'ic', 'cr', 'archive', 'action', 'h_cr', 'h_date_debut', 'h_date_fin', 'h_statut', 'prioritaire', 'type_recrutement', 'output');
        $columns = array(array('Date', 'date'), array('Intitulé', 'comp_profil'), array('Statut', 'statut'),
            array('Statut d\'affaire', 'statut_affaire'), array('Client', 'client'), array('IC', 'Id_ic'),
            array('CR', 'Id_cr'));
        $db = connecter();
        $requete = 'SELECT DISTINCT comp_profil, dr.Id_demande_ressource, dr.date, DATE_FORMAT(dr.date, "%d-%m-%Y") as datefr, dr.archive,
		profil, dr.statut, lieu, Id_ic, dr.Id_cr, dr.commentaire, client, a.Id_statut, prioritaire, adr.Id_affaire, statut_affaire
		FROM demande_ressource dr 
		LEFT JOIN affaire_demande_ressource adr ON adr.Id_demande_ressource=dr.Id_demande_ressource
		LEFT JOIN affaire a ON a.Id_affaire=adr.Id_affaire';
        if ($action) {
            $requete .= '
		LEFT JOIN demande_ressource_candidat drc ON drc.Id_demande_ressource = dr.Id_demande_ressource
		LEFT JOIN historique_action_candidature hac ON hac.Id_positionnement = drc.Id_candidat';
        }
        if ((int) $h_statut) {
            $requete .= ' INNER JOIN historique_statut_demande_ressource hsdr ON hsdr.Id_demande_ressource = dr.Id_demande_ressource';
        }
        $requete .= ' WHERE';
        if ($archive != 2) {
            $requete .= ' dr.archive  = "' . $archive . '"';
        } elseif ($archive == 2) {
            $requete .= ' (dr.archive = 0 OR dr.archive = 1)';
        } else {
            $requete .= ' dr.archive = 0';
        }
        if ($profil) {
            $requete .= ' AND profil ="' . $profil . '"';
        }
        if ($statut) {
            $requete .= ' AND dr.statut = ' . $statut . '';
        }
        if ($date_debut && $date_fin) {
            $requete .= ' AND (dr.date BETWEEN "' . DateMysqltoFr($date_debut, 'mysql') . '" AND "' . DateMysqltoFr($date_fin, 'mysql') . '")';
        }
        if ($lieu) {
            $requete .= ' AND lieu LIKE "%' . $lieu . '%"';
        }
        if ($ic) {
            $requete .= ' AND Id_ic LIKE "%' . $ic . '%"';
        }
        if ($cr) {
            $requete .= ' AND dr.Id_cr="' . $cr . '"';
        }
        if ($action) {
            $requete .= ' AND hac.Id_action_mener=' . $action;
        }
        if ($Id_affaire) {
            $requete .= ' AND (adr.Id_affaire="' . $Id_affaire . '" OR adr.reference_affaire="' . $Id_affaire . '")';
        }
        if ($Id_demande_ressource) {
            $requete .= ' AND dr.Id_demande_ressource=' . (int) $Id_demande_ressource;
        }
        if ($prioritaire) {
            $requete .= ' AND dr.prioritaire=1';
        }
        if (is_numeric($type_recrutement)) {
            $requete .= ' AND dr.type_demande=' . $type_recrutement;
        }
        if ((int) $h_statut) {
            $h_cr = str_replace('\\', '', $h_cr);
            $requete .= ' AND hsdr.Id_statut = ' . (int) $h_statut . '
                    AND hsdr.Id_utilisateur IN ("' . $h_cr . '")';
            if ($h_date_debut && $h_date_fin) {
                $requete .= ' AND DATE_FORMAT(hsdr.date, "%Y-%m-%d") BETWEEN "' . DateMysqltoFr($h_date_debut, 'mysql') . '"
                        AND "' . DateMysqltoFr($h_date_fin, 'mysql') . '"';
            }
        }

        $nbAgence = count($agence);
        $i = 0;
        $req = '';

        while ($i < $nbAgence) {
            if ($agence[$i] != '') {
                $req .= ( empty($req)) ? ' AND (' : ' OR';
                $req .= ' agence = "' . $agence[$i] . '"';
            } else {
                unset($agence[$i]);
            }
            ++$i;
        }
        $nbAgence = count($agence);
        if ($nbAgence != 0)
            $req .= ')';
        $requete .= $req;

        if ($_GET['a'] == 'consulterDemandeRessource' && $_GET['nb'] != 10) {
            $nb = TAILLE_LISTE;
        } else {
            $nb = 10;
        }

        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output') {
                if ($arguments[$key] == 'agenceDemandesRessource') {
                    $value = implode(";", $value);
                }
                $params .= $arguments[$key] . '=' . $value . '&';
            }
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=date';
            $orderBy = 'date';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherDemandeRessource({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => $nb, 'delta' => DELTA);

            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {                
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if ($value[1] == $output['orderBy'])
                        if ($output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        } else {
                            $direction = 'DESC';
                            $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        } else if (!$output['orderBy']) {
                        $direction = 'ASC';
                        $img['date'] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'ASC';
                    }
                    if ($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherDemandeRessource({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $demandes[] = $ligne['id_demande_ressource'];
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . self::afficherDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherIntitule($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherStatut($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherStatutAffaire($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherClient($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherIC($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherCR($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherDupliquer($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherPDF($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherModifier($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherSupprimer($ligne, array('csv' => false)) . '</td>
                            <td>' . self::afficherArchiver($ligne, array('csv' => false)) . '</td>
                        </tr>';
                    ++$i;
                }
                $pre = '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterDemandeRessource&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;<b>Total des demandes : ' . $paged_data['totalItems'] . '</b> - <a style="vertical-align:top;" href=\'../membre/index.php?a=editerDemandesRessource&demandes=' . serialize($demandes) . '\'>Générer un récapitulatif des demandes</a></span></p>
                    <table class="hovercolored">
                        <tr>';
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
                $html = $pre . $html;
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="demandes_ressource.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo self::afficherDate($ligne, array('csv' => true)) . ';';
                echo '"' . self::afficherIntitule($ligne, array('csv' => true)) . '";';
                echo '"' . self::afficherStatut($ligne, array('csv' => true)) . '";';
                echo '"' . self::afficherStatutAffaire($ligne, array('csv' => true)) . '";';
                echo '"' . self::afficherClient($ligne, array('csv' => true)) . '";';
                echo '"' . self::afficherIC($ligne, array('csv' => true)) . '";';
                echo '"' . self::afficherCR($ligne, array('csv' => true)) . '";';

                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche d'une demande
     *
     * @return string
     */
    public static function searchForm() {
        $dr = new DemandeRessource('', array());
        $candidature = new Candidature(0, array());
        $commercial = new Utilisateur($_SESSION['filtre']['ic'], array());
        if ($_SESSION['filtre']['archive']) {
            $demande[$_SESSION['filtre']['archive']] = 'selected="selected"';
        }
        if ($_SESSION['filtre']['type_recrutement']) {
            $type[$_SESSION['filtre']['type_recrutement']] = 'selected="selected"';
        }
        if ($_SESSION['filtre']['cr']) {
            $recruteur = new Utilisateur($_SESSION['filtre']['cr'], array());
            $r[$_SESSION['filtre']['cr']] = 'selected="selected"';
        }
        else
            $recruteur = new Utilisateur($_SESSION['Auth']->Id_utilisateur, array());
        $h_recruteur = new Utilisateur($_SESSION['filtre']['h_cr'], array());
        $statut = ($_SESSION['filtre']['statut']) ? $dr->getStatutListe($_SESSION['filtre']['statut']) : $dr->getStatutListe(1);
        $profil = new Profil($_SESSION['filtre']['profil'], array());
        $utilisateur = new Utilisateur(0, array());
        $html = '
		<table><tr>
			<td valign="top">
                            Id demande : 
                            <input id="Id_demande_ressource" type="text" onkeyup="afficherDemandeRessource({ page: 1, sort: []})" value="' . $_SESSION['filtre']['Id_demande_ressource'] . '" size="3" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Id affaire : 
                            <input id="Id_affaire_demande" type="text" onkeyup="afficherDemandeRessource({ page: 1, sort: []})" value="' . $_SESSION['filtre']['Id_affaire_demande'] . '" size="3" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="prioritaire">Prioritaire : <input type="checkbox" id="prioritaire" /></label>
                        </td>
			<td valign="top"><select id="cr" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par recruteur</option>
					<option value="">----------------------------</option>
					' . $recruteur->getList("RH") . '
				</select>
			</td>
			<td valign="top" style="width: 200px;"><select id="ic" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par commercial</option>
					<option value="">----------------------------</option>
					' . $commercial->getList("COM") . '
					<option value="">----------------------------</option>
					' . $commercial->getList("OP") . '
				</select>
			</td>
			<td valign="top" rowspan="2"><select id="agenceDemandesRessource" name="agenceDemandesRessource[]" onchange="afficherDemandeRessource({ page: 1, sort: []})" multiple size="4">
					<option value="">Par agence de rattachement</option>
					<option value="">----------------------------</option>
					' . Agence::getSelectList($_SESSION['filtre']['agenceDemandesRessource']) . '
				</select>
			</td>
			</tr>
			<tr>
			<td valign="top"><select id="statut" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par statut</option>
					<option value="">----------------------------</option>
					' . $statut . '
				</select>&nbsp;&nbsp;&nbsp;&nbsp;
                                <select id="type_recrutement" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par type de recrutement</option>
					<option value="">----------------------------</option>
					<option value="0" ' . $type['0'] . '>Nouvelle affaire</option>
                                        <option value="1" ' . $type['1'] . '>Remplacement collaborateur</option>
                                        <option value="2" ' . $type['2'] . '>Sur profil</option>
				</select>
			</td>
			<td valign="top"><select id="profil" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par profil</option>
					<option value="">----------------------------</option>
					' . $profil->getList() . '
				</select>
			</td>
			<td valign="top"><select id="archive" onchange="afficherDemandeRessource({ page: 1, sort: []})">
					<option value="">Par archivage</option>
					<option value="">----------------------------</option>
					<option value="1" ' . $demande['1'] . '>Archivée</option>
					<option value="0" ' . $demande['0'] . '>Non archivée</option>
					<option value="2" ' . $demande['2'] . '>Archivée et non archivée</option>
				</select>
			</td>
		</tr>
		<tr>
		<td>
                    Date création du <input id="dr_debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['dr_debut'] . '" size="8" />
                    au <input id="dr_fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['dr_fin'] . '" size="8" />
		</td>
		<td>
                    <select id="action" onchange="afficherDemandeRessource({ page: 1, sort: []})">
                        <option value="">Par action</option>
                        <option value="">----------------------------</option>
                        ' . $candidature->getActionMener($_SESSION['filtre']['action']) . '
		    </select>
		</td>
                <td colspan="2">
                    <select id="h_statut" >
                        <option value="">Statut</option>
                        <option value="">----------------------------</option>
                        ' . $dr->getStatutListe() . '
                    </select>
                    <select id="h_cr" multiple>
                        <option value="">Par</option>
                        <option value="">----------------------------</option>
                        ' . $h_recruteur->getList("RH") . '
                        <option value="">-------------------------</option>
                        <option value="">' . MARKETING_PERSON_SELECT . '</option>
                        <option value="">-------------------------</option>
                        ' . $utilisateur->getList('COM') . '
                    </select>
                    du <input id="h_date_debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['h_date_debut'] . '" size="8" />
                    au <input id="h_date_fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['h_date_fin'] . '" size="8" />
		</td>
                </tr></table>
		<input type="button" onclick="afficherDemandeRessource({ page: 1, sort: []})" value="Go !">
		<input type="reset" onclick="initForm(\'' . __CLASS__ . '\')" value="Refresh">
                <script type="text/javascript">MultiSelect.makeDropdown("h_cr",{height: 200, arrowSrc: "../ui/images/listbox_arrow.jpg"});</script>';
        return $html;
    }
    
    /**
     * Affichage du formulaire de recherche d'une demande
     *
     * @return string
     */
    public function duplicateForm() {
        $content = '<form id="duplicateDemandeRessource" name="duplicateDemandeRessource">
                Dupliquer l\'historique des statuts : 
                    <label>Oui<input type="radio" value="1" name="dupliquerStatuts" checked></label>
                    <label>Non<input type="radio" value="0" name="dupliquerStatuts"></label><br /><br />
                Dupliquer l\'historique des candidats : 
                    <label>Oui<input type="radio" value="1" name="dupliquerCandidats" checked></label>
                    <label>Non<input type="radio" value="0" name="dupliquerCandidats"></label><br /><br />
                </form>';
        $footer .= '<button type="button" class="button add" onclick="Modalbox.show(\'../membre/index.php?a=dupliquerDemandeRessource&amp;Id=' . $this->Id_demande_ressource . '\', {params: Form.serialize(\'duplicateDemandeRessource\'), infiniteLoading: true, afterLoad: function() {Modalbox.hide();},afterHide: function() {showInformationMessage(\'Votre demande de recrutement a été correctement dupliqué.\',5000);afficherDemandeRessource();}}); return false;">Dupliquer</button>';
        $footer .= '&nbsp;&nbsp;<button type="button" class="button delete" onclick="Modalbox.hide();">Annuler</button>';
        return json_encode(array('content' => utf8_encode($content), 'footer' => utf8_encode($footer)));
    }

    /**
     * Affichage d'une select box contenant les statut d'une demande
     *
     * @param int Statut sélectionné
     *
     * @return string
     */
    public function getStatutListe($statut = 0) {
        if ($statut) {
            $demande[$statut] = 'selected="selected"';
        } else {
            $demande[$this->statut] = 'selected="selected"';
        }
        $db = connecter();
        $result = $db->query('SELECT Id_statut, libelle FROM statut_demande_ressource');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_statut . '" ' . $demande[$ligne->id_statut] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Edition d'une fiche de demande de recrutement en pdf
     */
    public function editRecruitmentRequest() {
        $c = new Cursus($this->diplome, array());
        $r = RessourceFactory::create('CAN_AGC', 0, array('Id_exp_info' => $this->experience));
        //affichage du titre de la feuille : son numéro de candidature
        $_SESSION['titre'] = 'DEMANDE DE RECRUTEMENT';
        //début de la création du pdf
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->AddPage('', 3.5);
        $pdf->SetStyle('t1', 'Arial', 'B', 12, '255,255,255');

        // En-tête du document
        $pdf->setXY(103, 14);
        $pdf->setLeftMargin(103);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, "Référence : " . $this->Id_demande_ressource . " | Référence affaire : " . $this->Id_affaire);
        $pdf->setXY(103, $pdf->GetY() + 3.5);
        $pdf->setLeftMargin(103);
        $pdf->write(4, "Commercial : " . formatPrenomNom($this->Id_ic));
        $pdf->setXY(103, $pdf->GetY() + 3.5);
        $pdf->setLeftMargin(103);
        $pdf->write(4, "Date de réponse : " . $this->date_reponse);

        // Titre : intitulé du postes
        $pdf->setXY(10, $pdf->GetY() + 15);
        $pdf->SetFillColor(70, 110, 165);
        $pdf->MultiCellTag(190, 8, '<t1>' . mb_strtoupper(htmlenperso_decode($this->comp_profil, ENT_QUOTES, 'ISO-8859-1')) . '</t1>', 0, 'C', true);
        $pdf->SetFillColor(255, 255, 255);

        // Client et lieu mission
        $pdf->setXY(20, $pdf->GetY() + 10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, "•	");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->write(4, "Nom du client et lieu de la mission :");
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, $this->client . " - " . $this->lieu);

        // Description du poste
        $pdf->setXY(20, $pdf->GetY() + 10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, "•	");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->write(4, "Description du poste ");
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, convert_smart_quotes(strip_tags(htmlenperso_decode($this->description, ENT_QUOTES, 'ISO-8859-1'))));

        // Competences
        $pdf->setXY(20, $pdf->GetY() + 10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, "•	");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->write(4, "Compétences requises :");
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, $this->competences);

        // Info complémentaires
        $pdf->setXY(20, $pdf->GetY() + 10);
        $pdf->write(4, "•	");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->write(4, "Informations complémentaires :");
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->write(4, "-	Niveau de diplôme exigé : " . $c->libelle);
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->write(4, "-	Nombre d'années d'expérience exigé : " . unhtmlenperso($r->getExpInfo()));
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->write(4, "-	Fourchette de salaire : entre " . $this->salaire_debut . " et " . $this->salaire_fin . " K€");
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->write(4, "-	Date de démarrage de la mission : " . $this->date_mission);
        $pdf->setXY(30, $pdf->GetY() + 5);
        $pdf->setLeftMargin(30);
        $pdf->write(4, "-	Durée de la mission : " . $this->duree_mission);
        $pdf->Output();
    }

    /**
     * Edition des fiches de demandes de ressource
     */
    public static function edit($demandes, $file = null) {
        $db = connecter();
        $demandes = unserialize(stripcslashes($demandes));
        $nbDemandes = count($demandes);
        $demandesRessource = array();
        $ics = array();
        $i = 0;
        while ($i < $nbDemandes) {
            $d = new DemandeRessource($demandes[$i], array());
            $demandesRessource[] = $d;
            $ics[] = $d->Id_ic;
            if ($d->prioritaire)
                $nbDemandesPrioritaire++;
            $i++;
        }
        usort($demandesRessource, 'compare_demanderessource_agence_ic');
        $ics = array_unique($ics);
        while ($ic = current($ics)) {
            $u = new Utilisateur($ic, array());
            $icstring .= $u->prenom . ' ' . $u->nom;
            $icstring .= (!next($ics)) ? '' : ', ';
        }

        //début de la création du pdf

        $pdf = new FPDF_TABLE('L');
        $pdf->Table_Init(6, false, false);
        $pdf->SetAutoPageBreak(true);
        $pdf->AddPage();

        $pdf->SetStyle('t1', 'Arial', 'B', 14, '70, 110, 165');
        $pdf->SetStyle('t2', 'Arial', '', 10, '171,64,75');
        $pdf->SetStyle('t3', 'Arial', 'B', 12, '171,64,75');
        $pdf->SetStyle('b', 'Arial', 'B', 10, '0,0,0');
        $pdf->SetFont('Arial', '', 10);


        // En-tête du document
        $pdf->SetDrawColor(70, 110, 165);
        $pdf->SetLineWidth(0.5);
        $pdf->setXY(10, 10);
        $pdf->MultiCellTag(0, 8, dateFr(true), 0, 'R');
        $pdf->setXY(10, 10);
        $pdf->MultiCellTag(277, 8, ' ', 'T', 'L');
        $pdf->setXY(10, 10);
        $pdf->MultiCellTag(230, 8, '<t1>Etat des demandes actives de ' . htmlscperso_decode($icstring, ENT_QUOTES) . '</t1>', 0, 'L');
        if ($nbDemandesPrioritaire > 0) {
            if ($nbDemandesPrioritaire > 1) {
                $prio = 'dont ' . $nbDemandesPrioritaire . ' prioritaires';
            } else {
                $prio = 'dont ' . $nbDemandesPrioritaire . ' prioritaire';
            }
        }
        $pdf->MultiCellTag(0, 8, '<t2>Nombre de demandes actives : ' . $nbDemandes . ' ' . $prio . '</t2>', 'B', 'L');

        foreach ($demandesRessource as $demande) {
            $data = array();
            $cr = new Utilisateur($demande->Id_cr, array());
            $ic = new Utilisateur($demande->Id_ic, array());
            $p = new Profil($demande->profil, array());
            // Tableau récapitulatif de la demande
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetDrawColor(70, 110, 165);
            $pdf->SetLineWidth(0.5);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->setXY(10, $pdf->GetY() + 10);

            $AvailPageH = $pdf->PageBreakTrigger - ($pdf->GetY() + 8 + 8 + 8 + 8 + (8 * ($pdf->NbLines(0, $demande->commentaire))));
            if ($AvailPageH <= 0) {
                $pdf->AddPage();
            }

            if ($demande->prioritaire)
                $pdf->MultiCellTag(0, 8, '<t3>Prioritaire</t3>', 'TLR', 'C');
            $pdf->MultiCellTag(0, 8, '<b>Date de demande :</b> ' . htmlscperso_decode($demande->date, ENT_QUOTES), 'TLR', 'L');
            $pdf->setXY(10, $pdf->GetY() - 8);
            $pdf->MultiCellTag(0, 8, '<b>Intitulé de poste :</b> ' . htmlscperso_decode($demande->comp_profil, ENT_QUOTES), 'TLR', 'C');
            $pdf->setXY(10, $pdf->GetY() - 8);
            $pdf->MultiCellTag(0, 8, '<b>Date réponse :</b> ' . htmlscperso_decode($demande->date_reponse, ENT_QUOTES), 'TLR', 'R');
            $pdf->MultiCellTag(0, 8, '<b>CR :</b> ' . htmlscperso_decode($cr->prenom, ENT_QUOTES) . ' ' . htmlscperso_decode($cr->nom, ENT_QUOTES), 'LR', 'L');
            $pdf->setXY(10, $pdf->GetY() - 8);
            $pdf->MultiCellTag(0, 8, '<b>Client :</b> ' . htmlscperso_decode($demande->client, ENT_QUOTES), 'LR', 'C');
            $pdf->setXY(10, $pdf->GetY() - 8);
            $pdf->MultiCellTag(0, 8, '<b>Lieu :</b> ' . htmlscperso_decode($demande->lieu, ENT_QUOTES), 'LR', 'R');
            $pdf->MultiCellTag(0, 8, '<b>IC :</b> ' . htmlscperso_decode($ic->prenom, ENT_QUOTES) . ' ' . htmlscperso_decode($ic->nom, ENT_QUOTES), 'LR', 'L');
            $pdf->setXY(10, $pdf->GetY());
            $pdf->MultiCellTag(0, 8, '<b>Commentaires :</b> ' . htmlscperso_decode($demande->commentaire, ENT_QUOTES), 'BLR', 'L');
            $pdf->setXY(10, $pdf->GetY() + ($pdf->NbLines(0, $demande->commentaire) - 1));

            // Candidats de la demande
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.1);
            $pdf->SetTextColor(70, 110, 165);
            $pdf->setXY(10, $pdf->GetY() + 2);
            if (count($demande->candidats) > 0)
                $pdf->write(4, "Candidats identifiés");
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->setXY(10, $pdf->GetY() + 5);

            $pdf->Table_Init(6, false, false);

            $table_default_type = array(
                'WIDTH' => 6,
                'T_COLOR' => array(10, 10, 10),
                'T_SIZE' => 9,
                'T_FONT' => 'Arial',
                'T_ALIGN' => 'C',
                'V_ALIGN' => 'T',
                'T_TYPE' => '',
                'LN_SIZE' => 8,
                'BG_COLOR' => array(250, 250, 250),
                'BRD_COLOR' => array(0, 0, 0),
                'BRD_SIZE' => 0.2,
                'BRD_TYPE' => '1',
                'BRD_TYPE_NEW_PAGE' => '',
                'TEXT' => '',
            );



            $pdf->Set_Table_Type($table_subtype);

            for ($i = 0; $i < 6; $i++)
                $header_type[$i] = $table_default_type;
            $header_type[0]['WIDTH'] = 20;
            $header_type[1]['WIDTH'] = 40;
            $header_type[2]['WIDTH'] = 100;
            $header_type[3]['WIDTH'] = 40;
            $header_type[4]['WIDTH'] = 40;
            $header_type[5]['WIDTH'] = 35;

            $pdf->Set_Header_Type($header_type);
            $pdf->Draw_Header();

            $data_type = Array();
            for ($i = 0; $i < 6; $i++)
                $data_type[$i] = $table_default_type;
            $pdf->Set_Data_Type($data_type);

            $i = 0;
            while ($i < count($demande->candidats)) {
                $etat = $db->queryOne('SELECT ec.libelle FROM etat_candidature ec
        					INNER JOIN candidature c ON ec.Id_etat_candidature = c.Id_etat 
        					INNER JOIN demande_ressource_candidat drc ON c.Id_ressource = drc.Id_ressource 
        					WHERE drc.Id_demande_ressource = ' . mysql_real_escape_string($demande->Id_demande_ressource) . ' AND drc.Id_ressource = ' . mysql_real_escape_string($demande->candidats[$i]['Id_ressource']));
                $action = $db->queryOne('SELECT am.libelle FROM historique_action_candidature AS hac
	    					INNER JOIN action_mener am ON am.Id_action_mener = hac.Id_action_mener 
	    					WHERE hac.Id_positionnement = ' . mysql_real_escape_string($demande->candidats[$i]['Id_candidat']) . ' ORDER BY hac.date_action DESC');
                $r = RessourceFactory::create('CAN_AGC', $demande->candidats[$i]['Id_ressource'], null);

                $date = $demande->candidats[$i]['date'];
                $nom = htmlscperso_decode($r->nom, ENT_QUOTES) . ' ' . htmlscperso_decode($r->prenom, ENT_QUOTES);
                $com = htmlscperso_decode($demande->candidats[$i]['commentaire'], ENT_QUOTES);
                $cr = htmlscperso_decode(formatPrenomNom($demande->candidats[$i]['Id_cr']), ENT_QUOTES);
                $etat = htmlscperso_decode('Etat : ' . $etat, ENT_QUOTES);
                $action = htmlscperso_decode('Action : ' . $action, ENT_QUOTES);

                $data[0]['TEXT'] = $date;
                $data[1]['TEXT'] = $nom;
                $data[2]['TEXT'] = $com;
                $data[2]['T_ALIGN'] = 'L';
                $data[3]['TEXT'] = $cr;
                $data[4]['TEXT'] = $etat;
                $data[4]['T_ALIGN'] = 'L';
                $data[5]['TEXT'] = $action;
                $data[5]['T_ALIGN'] = 'L';
                $pdf->Draw_Data($data);

                $i++;
            }
        }
        if (!is_null($file)) {
            $d = new DemandeRessource($demandes[0], array());
            $pdf->Output($file . '.pdf', 'F');
        } else {

            $pdf->Output();
        }
    }

    /**
     * Affiche une ligne pour ajouter un candidat (ajax)
     *
     * @param int Identifiant du prochain candidat
     *
     */
    public function addApplicantRessource($nb, $newApplication = 0) {
        $db = connecter();
        $idDernierCandidat = $db->queryOne('SELECT LAST_INSERT_ID()');
        $nb2 = $nb + 1;
        $constanteSelectRessource = RESSOURCE_SELECT;
        $constanteSelectRecruteur = RECRUITER_SELECT;
        $cr = new Utilisateur($this->Id_cr, array());
        $candidats = new Candidature(0, array());
        $date = DATEFR;

        $html = <<<EOT
		<hr class="blackLine" />
		<label for="dateCandidat{$nb}">Date :</label>
		<input name="dateCandidat{$nb}" id="dateCandidat{$nb}" onfocus="showCalendarControl(this)" type="text" value="{$date}" size="8">
		<label for="candidat{$nb}">Candidat :</label>
    	<select id="candidat{$nb}" name="candidat{$nb}" onChange="majEtatCandidat({$nb});">
			<option value="">{$constanteSelectRessource}</option>
			'{$candidats->getApplicantList(3, '')}'
        </select>
    	<label for="crCandidat{$nb}">CR :</label>
    	<select id="crCandidat{$nb}" name="crCandidat{$nb}">
			<option value="">{$constanteSelectRecruteur}</option>
			<option value="">-------------------------</option>
			{$cr->getList('RH')}
        </select>
    	<label for="commentaireCandidat{$nb}">Commentaire :</label>
    	<input name="commentaireCandidat{$nb}" id="commentaireCandidat{$nb}" type="text" value="" size="50">
    	<input id="Id_candidat{$nb}" type="hidden" value="" name="Id_candidat{$nb}"/>
EOT;
        if ($newApplication == 0)
            $html .= '<input type="button" id="buttonCandidat' . $nb . '" name="buttonCandidat' . $nb . '" value="Proposer" onclick="ajoutCandidatDemandeRessource()" />';
        else
            $html .= '<input type="button" id="buttonCandidat' . $nb . '" name="buttonCandidat' . $nb . '" value="Proposer" onclick="ajoutCandidatDemandeRessource(1)" />';
        $html .= <<<EOT
	    <div id="etatCandidat{$nb}">Etat candidature : </div>
    	<div id="autreCandidatRessource{$nb2}">
			<input id="nb_candidat_ressource" type="hidden" value="{$nb}" name="nb_candidat_ressource[]"/>
			<input id="dernierCandidat" type="hidden" value="{$idDernierCandidat}" name="dernierCandidat"/>
		</div>
EOT;
        return $html;
    }

    /**
     * Enregistre les données d'une demande dans la BDD
     */
    public function save($updateStatut = 0) {
        $db = connecter();
        if(is_numeric($this->Id_affaire)) {
            $p = Profil::getLibelle($this->profil);
        }
        else {
            $a = new Opportunite($this->Id_affaire, array());
            $p = $a->profil[$this->profil]['nom'];
        }
        $set = ' SET Id_demande_ressource = "' . mysql_real_escape_string($this->Id_demande_ressource) . '", Id_cr = "' . mysql_real_escape_string($this->Id_cr) . '"
                        , Id_ic = "' . mysql_real_escape_string($this->Id_ic) . '", statut = "' . mysql_real_escape_string((int) $this->statut) . '"
        		, date = "' . mysql_real_escape_string(DateMysqltoFr($this->date, 'mysql')) . '", profil = "' . mysql_real_escape_string($this->profil) . '"
        		, comp_profil = "' . mysql_real_escape_string($this->comp_profil) . '", agence = "' . mysql_real_escape_string($this->agence) . '"
        		, lieu = "' . mysql_real_escape_string($this->lieu) . '", commentaire = "' . mysql_real_escape_string($this->commentaire) . '"
        		, annonce = "' . mysql_real_escape_string($this->annonce) . '", date_reponse = "' . mysql_real_escape_string($this->date_reponse) . '"
        		, client = "' . mysql_real_escape_string($this->client) . '", salaire_debut = "' . mysql_real_escape_string($this->salaire_debut) . '"
        		, salaire_fin = "' . mysql_real_escape_string($this->salaire_fin) . '", description = "' . mysql_real_escape_string($this->description) . '"
        		, diplome = "' . mysql_real_escape_string($this->diplome) . '", experience = "' . mysql_real_escape_string($this->experience) . '"
        		, date_mission = "' . mysql_real_escape_string(DateMysqltoFr($this->date_mission, 'mysql')) . '", duree_mission = "' . mysql_real_escape_string($this->duree_mission) . '"
        		, competences = "' . mysql_real_escape_string($this->competences) . '", candidat_retenu = "' . mysql_real_escape_string($this->candidat_retenu) . '"
                        , prioritaire = "' . mysql_real_escape_string((int) $this->prioritaire) . '", type_demande = ' . mysql_real_escape_string((int) $this->type_demande) . '
                        , statut_affaire = "' . mysql_real_escape_string($this->statut_affaire) . '", desc_profil = "' . mysql_real_escape_string($p) . '"
		';
        if ($this->Id_demande_ressource) {
            $requete = 'UPDATE demande_ressource ' . $set . ' WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $this->Id_demande_ressource);
        } else {
            $requete = 'INSERT INTO demande_ressource ' . $set;
        }
        
        $db->query($requete);
        $idDemandeRessource = (empty($this->Id_demande_ressource)) ? $db->queryOne('SELECT LAST_INSERT_ID()') : $this->Id_demande_ressource;

        if ($updateStatut) {
            if ($this->Id_demande_ressource == '') {
                $r = $db->query('INSERT INTO historique_statut_demande_ressource SET Id_demande_ressource = "' . mysql_real_escape_string($idDemandeRessource) . '",
                            Id_statut = "' . mysql_real_escape_string($this->statut) . '", date = "' . mysql_real_escape_string(DATETIME) . '",
                            Id_utilisateur = "' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
            }
        }

        $db->query('DELETE FROM affaire_demande_ressource WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $idDemandeRessource));
        if (!empty($this->Id_affaire)) {
            $db->query('INSERT INTO affaire_demande_ressource SET Id_affaire = "' . mysql_real_escape_string($this->Id_affaire) . '", Id_demande_ressource = "' . mysql_real_escape_string((int) $idDemandeRessource) . '", reference_affaire = "' . mysql_real_escape_string($this->reference_affaire) . '"');
        }

        // Enregistrement des candidats
        $c = count($this->candidats);
        if ($c != 0 && $this->candidats[0]['Id_ressource'] != "") {
            $i = 0;
            while ($i < $c) {
                $db->query('INSERT INTO demande_ressource_candidat SET Id_demande_ressource = "' . mysql_real_escape_string($idDemandeRessource) . '", Id_ressource = "' . mysql_real_escape_string($this->candidats[$i]['Id_ressource']) . '"
						, Id_cr = "' . mysql_real_escape_string($this->candidats[$i]['Id_cr']) . '", commentaire = "' . mysql_real_escape_string(utf8_decode($this->candidats[$i]['commentaire'])) . '"
						, date = "' . mysql_real_escape_string(DateMysqltoFr($this->candidats[$i]['date'], 'mysql') . ' ' . date('H:i:s')) . '"');
                $i++;
            }
        }

        if (!$this->Id_demande_ressource) {
            $message = new Mail_mime("\r\n");
            $headers = array("From" => $this->Id_ic . '@proservia.fr', "To" => $this->Id_cr . '@proservia.fr', "Subject" => 'Nouvelle demande de recrutement');
            $ic = formatPrenomNom($this->Id_ic);
            $cr = formatPrenomNom($this->candidats['Id_cr']);
            $lien = '<a href="' . BASE_URL . 'membre/index.php?a=modifier&class=DemandeRessource&Id=' . $idDemandeRessource . '">demande n° ' . $idDemandeRessource . '</a>';
            $body = <<<EOT
<html><body>Bonjour {$cr},<br /><br />
				
{$ic} vient de vous affecter une nouvelle demande de recrutement.<br />
Lien vers la demande de recrutement : {$lien}</body></html>
EOT;
            $message->setHTMLBody($body);

            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $body = $message->get();
            $hdrs = $message->headers($headers);
            $mail_object = Mail::factory('smtp', $params);
            $send = '';//$mail_object->send($this->Id_cr . '@proservia.fr', $hdrs, $body);
            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
        }
    }

    /**
     * Enregistre les données d'un candidat dans la BDD
     */
    public function saveCandidat() {
        $db = connecter();
        $set = ' SET Id_demande_ressource = "' . mysql_real_escape_string($this->candidats['Id_demande_ressource']) . '", Id_ressource = "' . mysql_real_escape_string($this->candidats['Id_ressource']) . '"
				, Id_cr = "' . mysql_real_escape_string($this->candidats['Id_cr']) . '", commentaire = "' . mysql_real_escape_string(utf8_decode($this->candidats['commentaire'])) . '"
        		, date = "' . mysql_real_escape_string($this->candidats['date'] . ' ' . date('H:i:s')) . '"
		';
        if ($this->candidats['Id_candidat']) {
            $requete = 'UPDATE demande_ressource_candidat ' . $set . ' WHERE Id_candidat = ' . mysql_real_escape_string((int) $this->candidats['Id_candidat']);
        } else {
            $requete = 'INSERT INTO demande_ressource_candidat ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Duplication d'une demande de recrutement
     */
    public function duplicate($duplicateHistory = 0, $duplicateApplicants = 0) {
        $db = connecter();
        $db->query('INSERT INTO demande_ressource
		            SET Id_cr = "' . mysql_real_escape_string($this->Id_cr) . '"
				, Id_ic = "' . mysql_real_escape_string($this->Id_ic) . '", statut = "' . mysql_real_escape_string((int) $this->statut) . '"
	        		, date = "' . mysql_real_escape_string(DateMysqltoFr($this->date, 'mysql')) . '", profil = "' . mysql_real_escape_string($this->profil) . '"
	        		, comp_profil = "' . mysql_real_escape_string($this->comp_profil) . '", agence = "' . mysql_real_escape_string($this->agence) . '"
	        		, lieu = "' . mysql_real_escape_string($this->lieu) . '", commentaire = "' . mysql_real_escape_string($this->commentaire) . '"
	        		, annonce = "' . mysql_real_escape_string($this->annonce) . '", date_reponse = "' . mysql_real_escape_string($this->date_reponse) . '"
	        		, client = "' . mysql_real_escape_string($this->client) . '", salaire_debut = "' . mysql_real_escape_string($this->salaire_debut) . '"
	        		, salaire_fin = "' . mysql_real_escape_string($this->salaire_fin) . '", description = "' . mysql_real_escape_string($this->description) . '"
	        		, diplome = "' . mysql_real_escape_string($this->diplome) . '", experience = "' . mysql_real_escape_string($this->experience) . '"
	        		, date_mission = "' . mysql_real_escape_string(DateMysqltoFr($this->date_mission, 'mysql')) . '", duree_mission = "' . mysql_real_escape_string($this->duree_mission) . '"
	        		, competences = "' . mysql_real_escape_string($this->competences) . '", candidat_retenu = "' . mysql_real_escape_string($this->candidat_retenu) . '"
                                , statut_affaire = "' . mysql_real_escape_string($this->statut_affaire) . '"');
        $Id_dr = mysql_insert_id();
        if (!empty($this->Id_affaire)) {
            $db->query('INSERT INTO affaire_demande_ressource SET Id_affaire = "' . mysql_real_escape_string((int) $this->Id_affaire) . '", Id_demande_ressource = "' . mysql_real_escape_string($Id_dr) . '"');
        }
        if ($duplicateApplicants) {
            $candidats = count($this->candidats);
            $i = 0;
            while ($i < $candidats) {
                $db->query('INSERT INTO demande_ressource_candidat SET Id_demande_ressource = "' . mysql_real_escape_string($Id_dr) . '",
                            Id_ressource =  "' . mysql_real_escape_string($this->candidats[$i]['Id_ressource']) . '", Id_cr = "' . mysql_real_escape_string($this->candidats[$i]['Id_cr']) . '",
                            date = "' . mysql_real_escape_string(DateMysqltoFr($this->candidats[$i]['date'], 'mysql')) . '", commentaire = "' . mysql_real_escape_string($this->candidats[$i]['commentaire']) . '"');
                ++$i;
            }
        }
        if ($duplicateHistory) {
            $result = $db->query('SELECT Id_demande_ressource, Id_statut, date, Id_utilisateur FROM historique_statut_demande_ressource WHERE Id_demande_ressource =' . mysql_real_escape_string((int) $this->Id_demande_ressource) . ' ORDER BY date DESC');

            while ($row = $result->fetchRow()) {
                $db->query('INSERT INTO historique_statut_demande_ressource SET Id_demande_ressource=' . mysql_real_escape_string((int) $Id_dr) . ',
                Id_statut=' . (int) $row->id_statut . ', date="' . $row->date . '",
                Id_utilisateur="' . $row->id_utilisateur . '"');
            }
        }
    }

    /**
     * Mise à jour du statut de la demande de recrutement
     *
     * @param int Identifiant du statut de la demande
     * @param int Identifiant de la demande à mettre à jour
     */
    public static function updateStatut($Id_statut, $Id_demande_ressource) {
        $db = connecter();
        $db->query('INSERT INTO historique_statut_demande_ressource SET Id_demande_ressource = "' . mysql_real_escape_string($Id_demande_ressource) . '",
                        Id_statut = "' . mysql_real_escape_string($Id_statut) . '", date = "' . mysql_real_escape_string(DATETIME) . '",
                        Id_utilisateur = "' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
        $db->query('UPDATE demande_ressource SET statut = ' . mysql_real_escape_string((int) $Id_statut) . ' WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $Id_demande_ressource));
    }

    /**
     * Archivage d'une demande
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE demande_ressource SET archive="1" WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $this->Id_demande_ressource));
    }

    /**
     * Desarchivage d'une demande
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE demande_ressource SET archive="0" WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $this->Id_demande_ressource));
    }

    /**
     * Suppression d'une demande
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM dr, adr, drc USING demande_ressource AS dr
		LEFT JOIN affaire_demande_ressource AS adr ON dr.Id_demande_ressource=adr.Id_demande_ressource 
		LEFT JOIN demande_ressource_candidat AS drc ON dr.Id_demande_ressource=drc.Id_demande_ressource 		
		WHERE dr.Id_demande_ressource = ' . mysql_real_escape_string((int) $this->Id_demande_ressource));
    }

    /**
     * Suppression d'une demande
     *
     * @param int Id du candidat
     */
    public static function deleteApplicant($id) {
        $db = connecter();
        $db->query('DELETE FROM demande_ressource_candidat WHERE Id_candidat = ' . mysql_real_escape_string((int) $id));
        $db->query('UPDATE historique_action_candidature SET Id_positionnement = 0 WHERE Id_positionnement = ' . mysql_real_escape_string((int) $id));
    }

    public function getStatusHistory() {
        $db = connecter();
        $result = $db->query('SELECT Id_demande_ressource, Id_statut, date, DATE_FORMAT(date, "%d-%m-%Y") as datefr, Id_utilisateur FROM historique_statut_demande_ressource WHERE Id_demande_ressource =' . mysql_real_escape_string((int) $this->Id_demande_ressource) . ' ORDER BY date DESC');
        $html .= '<table class="sortable">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Réalisé par</th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 0;
        while ($row = $result->fetchRow()) {
            $htmlSupp = '<td width="5%" height="20"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { supprimerStatutDR(' . $this->Id_demande_ressource . ',' . $row->id_statut . ',\'' . $row->date . '\') }" /></td>';
            $htmlValid = '<td width="5%" height="20"><input type="button" class="boutonValider" onclick="if (confirm(\'Valider la ligne ?\')) { validerStatutDR(' . $this->Id_demande_ressource . ',' . $row->id_statut . ',' . $i . '); supprimerStatutDR(' . $this->Id_demande_ressource . ',' . $row->id_statut . ',\'' . $row->date . '\'); }" /></td>';
            $utilisateur = new Utilisateur($row->id_utilisateur, array());
            $htmlUser = '
                    <select id="Id_utilisateur' . $i . '">
                        <option value="">' . RECRUITER_SELECT . '</option>
                        <option value="">-------------------------</option>
			' . $utilisateur->getList('RH') . '
                        <option value="">-------------------------</option>
                        <option value="">' . MARKETING_PERSON_SELECT . '</option>
                        <option value="">-------------------------</option>
                        ' . $utilisateur->getList('COM') . '
                        <option value="">-------------------------</option>
                        ' . $utilisateur->getList('ADI') . '
                    </select>';
            $html .= '<tr>
                        <td>' . self::getLibelleStatut($row->id_statut) . '</td>
                        <td><input type="text" onfocus="showCalendarControl(this)" id="date' . $i . '" value="' . $row->datefr . '" size="8"></td>
                        <td>' . $htmlUser . '</td>
                        ' . $htmlSupp . '
                        ' . $htmlValid . '
                    </tr>';
            $i++;
        }
        $html .= '</tbody>
                </table>';
        return $html;
    }

    /**
     * Affichage de l'historique des etats de la candidature
     *
     * @return string
     */
    public static function deleteHistory($Id_demande_ressource, $Id_statut, $date) {
        $db = connecter();
        $db->query('DELETE FROM historique_statut_demande_ressource WHERE Id_demande_ressource = ' . mysql_real_escape_string((int) $Id_demande_ressource) . '
		AND Id_statut =' . mysql_real_escape_string((int) $Id_statut) . ' AND date = "' . mysql_real_escape_string($date) . '"');
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
    public static function validateHistory($Id_demande_ressource, $Id_statut, $date, $Id_utilisateur) {
        $db = connecter();
        $hms = $db->query('SELECT DATE_FORMAT(now(),"%H:%i:%s") as hms')->fetchRow()->hms;
        $db->query('INSERT INTO historique_statut_demande_ressource SET Id_demande_ressource=' . mysql_real_escape_string((int) $Id_demande_ressource) . ',
                Id_statut=' . mysql_real_escape_string((int) $Id_statut) . ', date="' . mysql_real_escape_string(DateMysqltoFr($date, 'mysql') . ' ' . $hms) . '",
                Id_utilisateur="' . mysql_real_escape_string($Id_utilisateur) . '"');
    }

    /**
     * Recupère le libellé d'un statut de demande de recrutement
     *
     * @param int Id du statut
     *
     * @return string
     */
    public function getLibelleStatut($idStatut = 0) {
        $db = connecter();
        return $db->queryOne('SELECT libelle FROM  statut_demande_ressource WHERE Id_statut = ' . mysql_real_escape_string((int) $idStatut));
    }

    /**
     * Affichage d'une select box contenant les clients
     *
     * @return string
     */
    public function getCustomerList($client = '') {
        $db = connecter();
        $requete = 'SELECT DISTINCT client FROM demande_ressource';
        if ($client) {
            $requete .= ' WHERE client like "%' . mysql_real_escape_string($client) . '%"';
        }
        $requete .= ' ORDER BY client';
        $result = $db->query($requete);
        $html = '<ul>';
        while ($ligne = $result->fetchRow()) {
            $html .= '<li id="' . $ligne->client . '">' . $ligne->client . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les candidats de la demande
     *
     * @return string
     */
    public function getApplicantsList($candidat = '') {
        $utilisateur[$candidat] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT r.nom, r.prenom, r.Id_ressource FROM demande_ressource_candidat drc
	    			INNER JOIN ressource r ON r.Id_ressource = drc.Id_ressource 
	    			WHERE drc.Id_demande_ressource = ' . mysql_real_escape_string($this->Id_demande_ressource));
        $html = '<ul>';
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_ressource . '" ' . $utilisateur[$ligne->id_ressource] . '>' . strtoupper($ligne->nom) . ' ' . formatPrenom($ligne->prenom) . '</option>';
        }
        $html .= '</ul>';
        return $html;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function afficherDate($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'class="rowrouge"';
        if (!$arg['csv'])
            return '<div id="rowCand' . $record['id_demande_ressource'] . '" ' . $prio . ' class="cliquable" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . $record['datefr'] . '</div>';
        else
            return $record['datefr'];
    }

    public function afficherStatut($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '"  onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . self::getLibelleStatut($record['statut']) . '</div>';
        else
            return self::getLibelleStatut($record['statut']);
    }

    public function afficherStatutAffaire($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . $record['statut_affaire'] . '</div>';
        else
            return $record['statut_affaire'];
    }

    public function afficherIntitule($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . $record['comp_profil'] . '</div>';
        else
            return $record['comp_profil'];
    }

    public function afficherIC($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . formatPrenomNom($record['id_ic']) . '</div>';
        else
            return formatPrenomNom($record['id_ic']);
    }

    public function afficherCR($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . formatPrenomNom($record['id_cr']) . '</div>';
        else
            return formatPrenomNom($record['id_cr']);
    }

    public function afficherClient($record, $arg) {
        if ($record['prioritaire'] == 1)
            $prio = 'rowrouge';
        if (!$arg['csv'])
            return '<div class="cliquable ' . $prio . '" onclick="afficherCandidats(' . $record['id_demande_ressource'] . ')">' . $record['client'] . '</div>';
        else
            return $record['client'];
    }

    public function afficherDupliquer($record) {
        return '<a href="javascript:void(0)" onclick="duplicateDemandeRessource(' . $record['id_demande_ressource'] . ');"><img src="' . IMG_COPY . '"></a>';
    }

    public function afficherPDF($record) {
        return '<a href="../membre/index.php?a=editerCandidatDemandeRessource&Id_demande_ressource=' . $record['id_demande_ressource'] . '"><img src="' . IMG_PDF . '"/></a>';
    }

    public function afficherModifier($record) {
        return '<a href="../membre/index.php?a=modifier&amp;Id=' . $record['id_demande_ressource'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a>';
    }

    public function afficherArchiver($record) {
        if ($record['archive'] == 0) {
            $return = '<a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' cette demande ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_demande_ressource'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a>';
        } elseif ($record['archive'] == 1) {
            $return = '<a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' cette demande ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_demande_ressource'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '"></a>';
        }
        return $return;
    }

    public function afficherSupprimer($record) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return '<input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../gestion/index.php?a=supprimer&amp;Id=' . $record['id_demande_ressource'] . '&amp;class=' . __CLASS__ . '\') }" />';
        }
    }

}

?>
