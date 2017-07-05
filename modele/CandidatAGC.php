<?php

/**
 * Fichier Ressource.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Ressource
 */
class CandidatAGC implements IRessource {

    /**
     * Identifiant de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_ressource;
    /**
     * Code de la ressource
     *
     * @access public
     * @var string 
     */
    public $code_ressource;
    /**
     * Origine de la ressource
     *
     * @access public
     * @var int
     */
    public $origine;
    /**
     * Civilité de la ressource
     *
     * @access public
     * @var string 
     */
    public $civilite;
    /**
     * Nom de la ressource
     *
     * @access public
     * @var array
     */
    public $nom;
    /**
     * Nom de jeune fille de la ressource
     *
     * @access private
     * @var string
     */
    public $nom_jeune_fille;
    /**
     * Prénom de la ressource
     *
     * @access public
     * @var string
     */
    public $prenom;
    /**
     * Adresse de la ressource
     *
     * @access public
     * @var string
     */
    public $adresse;
    /**
     * Code postal de la ressource
     *
     * @access public
     * @var int
     */
    public $code_postal;
    /**
     * Ville de la ressource
     *
     * @access public
     * @var string
     */
    public $ville;
    /**
     * Identifiant du département de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_dpt_naiss;
    /**
     * Identifiant du Pays de résidence de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_pays_residence;
    /**
     * Identifiant du Pays de naissance de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_pays_naissance;
    /**
     * Téléphone fixe de la ressource
     *
     * @access public
     * @var string
     */
    public $tel_fixe;
    /**
     * Téléphone portable de la ressource
     *
     * @access public
     * @var string
     */
    public $tel_portable;
    /**
     * Mail de la ressource
     *
     * @access public
     * @var string
     */
    public $mail;
    /**
     * Statut de la ressource
     *
     * @access public
     * @var string
     */
    public $statut;
    /**
     * Numéro de sécurité sociale de la ressource
     *
     * @access public
     * @var string
     */
    public $securite_sociale;
    /**
     * Date de naissance de la ressource
     *
     * @access public
     * @var date
     */
    public $date_naissance;
    /**
     * Lieu de naissance de la ressource
     *
     * @access public
     * @var string
     */
    public $ville_naissance;
    /**
     * Nationalité de la ressource
     *
     * @access public
     * @var string
     */
    public $nationalite;
    /**
     * Type d'embauche de la ressource
     *
     * @access private
     * @var string
     */
    public $type_embauche;
    /**
     * Date d'embauche de la ressource
     *
     * @access public
     * @var date
     */
    public $date_embauche;
    /**
     * Heure d'embauche de la ressource
     *
     * @access public
     * @var double
     */
    public $heure_embauche;
    /**
     * Fin du cdd de la ressource
     *
     * @access public
     * @var date
     */
    public $fin_cdd;
    /**
     * Durée de la periode d'essai de la ressource
     *
     * @access public
     * @var int
     */
    public $periode_essai;
    /**
     * Identifiant du profil de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_profil;
    /**
     * Identifiant du profil de la ressource cegid
     *
     * @access public
     * @var int
     */
    public $Id_profil_cegid;
    /**
     * Identifiant de la spécialité de la ressource
     *
     * @access public
     * @var array
     */
    public $Id_specialite;
    /**
     * Cursus de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_cursus;
    /**
     * Identifiant de la durée d'experience en Informatique
     *
     * @access public
     * @var int
     */
    public $Id_exp_info;
    /**
     * Salaire annuel brut de la ressource
     *
     * @access public
     * @var double
     */
    public $salaire;
    /**
     * Identifiant du contrat Proservia
     *
     * @access public
     * @var int
     */
    public $Id_contrat_proservia;
    /**
     * Identifiant du service de la ressource
     *
     * @access public
     * @var string
     */
    public $Id_service;
    /**
     * Pôle de la ressource
     *
     * @access public
     * @var string
     */
    public $pole;
    /**
     * Agence de la ressource
     *
     * @access public
     * @var string
     */
    public $Id_agence;
    /**
     * Indique si la ressource est un travailleur handicapé
     *
     * @access public
     * @var int
     */
    public $th;
    /**
     * Information complémentaire sur l'embauche
     *
     * @access public
     * @var string
     */
    public $info_complementaire;
    /**
     * Créateur de la ressource
     *
     * @access private
     * @var string
     */
    private $createur;
    /**
     * Indique si la ressource est une archive
     *
     * @access private
     * @var int
     */
    private $archive;

    /**
     * Constructeur de la classe Ressource
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la ressource
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        $this->Id_specialite = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_ressource = '';
                $this->code_ressource = '';
                $this->origine = '';
                $this->civilite = '';
                $this->nom = '';
                $this->nom_jeune_fille = '';
                $this->prenom = '';
                $this->adresse = '';
                $this->code_postal = '';
                $this->ville = '';
                $this->Id_dpt_naiss = '';
                $this->Id_pays_residence = 72;
                $this->Id_pays_naissance = 72;
                $this->tel_fixe = '';
                $this->tel_portable = '';
                $this->mail = '';
                $this->statut = '';
                $this->securite_sociale = '';
                $this->date_naissance = '';
                $this->ville_naissance = '';
                $this->Id_nationalite = 70;
                $this->nationalite = '';
                $this->Id_etat_matrimonial = '';
                $this->type_embauche = '';
                $this->date_embauche = '';
                $this->heure_embauche = '';
                $this->fin_cdd = '';
                $this->periode_essai = '';
                $this->Id_profil = '';
                $this->Id_profil_cegid = '';
                $this->Id_cursus = '';
                $this->Id_exp_info = '';
                $this->salaire = '';
                $this->Id_service = '';
                $this->Id_contrat_proservia = '';
                $this->pole = '';
                $this->Id_agence = '';
                $this->th = '';
                $this->info_complementaire = '';
                $this->createur = '';
                $this->archive = 0;
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_ressource = '';
                $this->code_ressource = htmlscperso(stripslashes($tab['code_ressource']), ENT_QUOTES);
                $this->origine = $tab['origine_ressource'];
                $this->civilite = $tab['civilite_ressource'];
                $this->nom = htmlscperso(stripslashes($tab['nom_ressource']), ENT_QUOTES);
                $this->nom_jeune_fille = htmlscperso(stripslashes($tab['nom_jeune_fille']), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes($tab['prenom_ressource']), ENT_QUOTES);
                $this->adresse = htmlscperso(stripslashes($tab['adresse_ressource']), ENT_QUOTES);
                $this->code_postal = htmlscperso(stripslashes($tab['cp_ressource']), ENT_QUOTES);
                $this->ville = htmlscperso(stripslashes($tab['ville_ressource']), ENT_QUOTES);
                $this->Id_dpt_naiss = $tab['Id_dpt_naiss'];
                $this->Id_pays_residence = (int) $tab['Id_pays_residence'];
                $this->Id_pays_naissance = (int) $tab['Id_pays_naissance'];
                $this->tel_fixe = htmlscperso(stripslashes($tab['tel_fixe_ressource']), ENT_QUOTES);
                $this->tel_portable = htmlscperso(stripslashes($tab['tel_portable_ressource']), ENT_QUOTES);
                $this->mail = htmlscperso(stripslashes($tab['mail_ressource']), ENT_QUOTES);
                $this->statut = $tab['statut_ressource'];
                $this->securite_sociale = htmlscperso(stripslashes($tab['securite_sociale']), ENT_QUOTES);
                $this->date_naissance = $tab['date_naissance'];
                $this->ville_naissance = htmlscperso(stripslashes($tab['ville_naissance']), ENT_QUOTES);
                $this->Id_nationalite = (int) $tab['Id_nationalite'];
                $this->Id_etat_matrimonial = (int) $tab['Id_etat_matrimonial'];
                $this->nationalite = Nationalite::GetLibelle($this->Id_nationalite);
                $this->type_embauche = $tab['type_embauche'];
                $this->date_embauche = $tab['date_embauche'];
                $this->heure_embauche = $tab['heure_embauche'];
                $this->fin_cdd = $tab['fin_cdd'];
                $this->periode_essai = htmlscperso(stripslashes($tab['periode_essai']), ENT_QUOTES);
                $this->Id_profil = (int) $tab['Id_profil'];
                $this->Id_profil_cegid = (int) $tab['Id_profil_cegid'];
                $this->profil = Profil::GetLibelle($this->Id_profil);
                $this->Id_specialite = $tab['Id_specialite'];
                $this->Id_cursus = (int) $tab['Id_cursus'];
                $this->Id_exp_info = (int) $tab['Id_exp_info'];
                $this->salaire = htmlscperso(stripslashes(str_replace(',', '.', $tab['salaire'])), ENT_QUOTES);
                $this->Id_service = $tab['Id_service'];
                $this->Id_contrat_proservia = (int) $tab['Id_contrat_proservia'];
                $this->pole = $tab['pole'];
                $this->Id_agence = $tab['Id_agence'];
                $this->th = $tab['th'];
                $this->info_complementaire = htmlscperso(stripslashes($tab['info_complementaire']), ENT_QUOTES);
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->archive = (int) $tab['archive'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM ressource WHERE Id_ressource=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_ressource = $code;
                $this->type_ressource = 'CAN';
                $this->code_ressource = $ligne->code_ressource;
                $this->civilite = $ligne->civilite;
                $this->origine = $ligne->origine;
                $this->nom = $ligne->nom;
                $this->nom_jeune_fille = $ligne->nom_jeune_fille;
                $this->prenom = $ligne->prenom;
                $this->adresse = str_replace("'", " ", $ligne->adresse);
                $this->code_postal = $ligne->code_postal;
                $this->ville = $ligne->ville;
                $this->Id_dpt_naiss = $ligne->id_dpt_naiss;
                $this->Id_pays_residence = $ligne->id_pays_residence;
                $this->Id_pays_naissance = $ligne->id_pays_naissance;
                $this->tel_fixe = $ligne->tel_fixe;
                $this->tel_portable = $ligne->tel_portable;
                $this->mail = $ligne->mail;
                $this->statut = $ligne->statut;
                $this->securite_sociale = $ligne->securite_sociale;
                $this->date_naissance = $ligne->date_naissance;
                $this->ville_naissance = $ligne->ville_naissance;
                $this->Id_nationalite = $ligne->id_nationalite;
                $this->nationalite = Nationalite::GetLibelle($this->Id_nationalite);
                $this->Id_etat_matrimonial = $ligne->id_etat_matrimonial;
                $this->type_embauche = $ligne->type_embauche;
                $this->date_embauche = $ligne->date_embauche;
                $this->heure_embauche = $ligne->heure_embauche;
                $this->fin_cdd = $ligne->fin_cdd;
                $this->periode_essai = $ligne->periode_essai;
                $this->Id_profil = $ligne->id_profil;
                $this->Id_profil_cegid = $ligne->id_profil_cegid;
                $this->profil = str_replace("'", " ", Profil::GetLibelle($this->Id_profil));
                $this->profil_cegid = str_replace("'", " ", Profil::getLibelleCegid($this->Id_profil_cegid));
                $this->Id_cursus = $ligne->id_cursus;
                $this->Id_exp_info = $ligne->id_exp_info;
                $this->salaire = $ligne->salaire;
                $this->Id_service = $ligne->id_service;
                $this->Id_contrat_proservia = $ligne->id_contrat_proservia;
                $this->pole = $ligne->pole;

                if (self::isStaff($code)) {
                    $this->etat = 'Staff';
                } else {
                    $this->etat = 'Collab';
                }

                $this->Id_agence = $ligne->id_agence;
                $this->th = $ligne->th;
                $this->info_complementaire = $ligne->info_complementaire;
                $service = new Service($this->Id_service);
                $this->responsable = $service->responsable;
                $this->resp_abs = $service->resp_abs;
                $this->createur = $ligne->createur;
                $this->archive = $ligne->archive;

                $result = $db->query('SELECT Id_specialite FROM ressource_specialite WHERE Id_ressource=' . mysql_real_escape_string((int) $code));
                while ($ligne = $result->fetchRow()) {
                    $this->Id_specialite[] = $ligne->id_specialite;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_ressource = $code;
                $this->code_ressource = htmlscperso(stripslashes($tab['code_ressource']), ENT_QUOTES);
                $this->origine = $tab['origine_ressource'];
                $this->civilite = htmlscperso(stripslashes($tab['civilite_ressource']), ENT_QUOTES);
                $this->nom = htmlscperso(stripslashes($tab['nom_ressource']), ENT_QUOTES);
                $this->nom_jeune_fille = htmlscperso(stripslashes($tab['nom_jeune_fille']), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes($tab['prenom_ressource']), ENT_QUOTES);
                $this->adresse = htmlscperso(stripslashes($tab['adresse_ressource']), ENT_QUOTES);
                $this->code_postal = htmlscperso(stripslashes($tab['cp_ressource']), ENT_QUOTES);
                $this->ville = htmlscperso(stripslashes($tab['ville_ressource']), ENT_QUOTES);
                $this->Id_dpt_naiss = $tab['Id_dpt_naiss'];
                $this->Id_pays_residence = (int) $tab['Id_pays_residence'];
                $this->Id_pays_naissance = (int) $tab['Id_pays_naissance'];
                $this->tel_fixe = htmlscperso(stripslashes($tab['tel_fixe_ressource']), ENT_QUOTES);
                $this->tel_portable = htmlscperso(stripslashes($tab['tel_portable_ressource']), ENT_QUOTES);
                $this->mail = htmlscperso(stripslashes($tab['mail_ressource']), ENT_QUOTES);
                $this->statut = $tab['statut_ressource'];
                $this->securite_sociale = htmlscperso(stripslashes($tab['securite_sociale']), ENT_QUOTES);
                $this->date_naissance = $tab['date_naissance'];
                $this->ville_naissance = htmlscperso(stripslashes($tab['ville_naissance']), ENT_QUOTES);
                $this->Id_nationalite = (int) $tab['Id_nationalite'];
                $this->nationalite = Nationalite::GetLibelle($this->Id_nationalite);
                $this->Id_etat_matrimonial = (int) $tab['Id_etat_matrimonial'];
                $this->type_embauche = $tab['type_embauche'];
                $this->date_embauche = $tab['date_embauche'];
                $this->heure_embauche = $tab['heure_embauche'];
                $this->fin_cdd = $tab['fin_cdd'];
                $this->periode_essai = htmlscperso(stripslashes($tab['periode_essai']), ENT_QUOTES);
                $this->Id_profil = (int) $tab['Id_profil'];
                $this->Id_profil_cegid = $tab['Id_profil_cegid'];
                $this->profil = Profil::GetLibelle($this->Id_profil);
                $this->Id_specialite = $tab['Id_specialite'];
                $this->Id_cursus = (int) $tab['Id_cursus'];
                $this->Id_exp_info = (int) $tab['Id_exp_info'];
                $this->salaire = htmlscperso(stripslashes(str_replace(',', '.', $tab['salaire'])), ENT_QUOTES);
                $this->Id_service = $tab['Id_service'];
                $this->Id_contrat_proservia = (int) $tab['Id_contrat_proservia'];
                $this->info_complementaire = htmlscperso(stripslashes($tab['info_complementaire']), ENT_QUOTES);
                $this->pole = $tab['pole'];
                $this->Id_agence = $tab['Id_agence'];
                $this->th = $tab['th'];
                $this->createur = $tab['createur'];
                $this->archive = (int) $tab['archive'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une ressource
     *
     * @return string
     */
    public function form() {
        $profil = new Profil($this->Id_profil, array());
        $cursus = new Cursus($this->Id_cursus, array());
        $expinfo = new Experience($this->Id_exp_info, array());
        $pays_residence = new Pays($this->Id_pays_residence);
        $origine[$this->origine] = 'checked="checked"';
        $civ[$this->civilite] = 'checked="checked"';
        $th[$this->th] = 'checked="checked"';
        $specialite = new Specialite('', '');
        $html = '
			<h2>INFORMATIONS CANDIDAT</h2>
			<div class="left">
                        <span class="infoFormulaire"> ** </span>
			    ' . MISS . ' <input type="radio" name="civilite_ressource" ' . $civ['Melle'] . ' value="Melle" />
			    ' . MADAM . ' <input type="radio" name="civilite_ressource" ' . $civ['Mme'] . ' value="Mme" />
				' . MISTER . ' <input type="radio" name="civilite_ressource" ' . $civ['M'] . ' value="M" /><br /><br />
				<span class="infoFormulaire"> * </span> Nom :
		        <input type="text" id="nom_ressource" name="nom_ressource" value="' . $this->nom . '" />
				<br /><br />
				<span class="infoFormulaire"> * </span> Prénom : 
		        <input type="text" id="prenom_ressource" name="prenom_ressource" value="' . $this->prenom . '" onkeyup="homonyme()" />
				<br /><div id="homonyme"> </div><br />
				<span class="infoFormulaire"> ** </span> Adresse : 
		        <input type="text" id="adresse" name="adresse_ressource" value="' . $this->adresse . '" size="50" /><br /><br />
				Code Postal : 
		        <input type="text" name="cp_ressource" value="' . $this->code_postal . '" size="5" /><br /><br />
				Ville : 
		        <input type="text" name="ville_ressource" value="' . $this->ville . '" size="30" /><br /><br />
				Pays de résidence:
				<select name="Id_pays_residence">
                    <option value="">' . COUNTRY_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $pays_residence->getList() . '
                </select>
			    <br /><br />
				<span class="infoFormulaire"> (*) </span> Tél fixe : 
		        <input type="text" id="tel_fixe" name="tel_fixe_ressource" value="' . $this->tel_fixe . '" size="10" /><br /><br />
				<span class="infoFormulaire"> (*) </span> Tél portable : 
		        <input type="text" id="tel_portable" name="tel_portable_ressource" value="' . $this->tel_portable . '" size="10" /><br /><br />
				<span class="infoFormulaire"> * </span> Mail :
		        <input type="text" id="mail" name="mail_ressource" value="' . $this->mail . '" size="30" />
			</div>
            <div class="right">
				<span class="infoFormulaire"> * </span> Profil :
				<select id="profil" name="Id_profil">
                    <option value="">' . PROFIL_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $profil->getList() . '
                </select><br /><br />
				<span class="infoFormulaire"> * </span>
				Spécialité : <br />Veuillez appuyer sur CTRL pour en sélectionner plusieurs<br /><br />
				<select id="Id_specialite" name="Id_specialite[]" multiple size="13">
                    <option value="">' . SPECIALTY_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $specialite->getList($this->Id_ressource) . '
                </select><br /><br />
				<span class="infoFormulaire"> (*) </span> Cursus :
				<select id="Id_cursus" name="Id_cursus">
                    <option value="">' . CURSUS_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $cursus->getList() . '
                </select><br /><br />
				<span class="infoFormulaire"> (*) </span> Expérience Informatique : 
			    <select id="Id_exp_info" name="Id_exp_info">
                    <option value="">' . DURATION_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $expinfo->getList() . '
                </select>
				<br /><br />
				<span class="infoFormulaire"> ** </span>
                                Travailleur Handicapé : Oui<input type="radio" name="th" value="1" ' . $th[1] . ' />
				Non<input type="radio" name="th" value="0" ' . $th[0] . '  />
                <br /><br />
			</div>
			<input type="hidden" name="Id_ressource" value="' . $this->Id_ressource . '" />
			<input type="hidden" name="origine_ressource" value="Candidat" />
';
        return $html;
    }

    /**
     * Formulaire d'embauche d'une ressource
     *
     * @return string
     */
    public function hiringForm() {
        $statut[$this->statut] = 'checked="checked"';
        $type_embauche[$this->type_embauche] = 'checked="checked"';
        $service = new Service($this->Id_service);
        $departement = new Departement($this->Id_dpt_naiss);
        $pays_naissance = new Pays($this->Id_pays_naissance);
        $nationalite = new Nationalite($this->Id_nationalite);
        $cp = new ContratProservia($this->Id_contrat_proservia, array());
        $em = new EtatMatrimonial($this->Id_etat_matrimonial, array());
        $agence = new Agence($this->Id_agence, array());
        $profil = new Profil($this->Id_profil_cegid, array());
        $html = '
			<h2>INFORMATIONS D\'EMBAUCHE</h2>
			<div class="left">
                        <span class="infoFormulaire"> ** 
                            <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>Obligatoire seulement dans le cas d\\\'une civilité Mme.</div>\', FULLHTML);" onmouseout="return nd();"></img>
                        </span>Nom de jeune fille : 
		        <input type="text" name="nom_jeune_fille" value="' . $this->nom_jeune_fille . '" /><br /><br />
				<span class="infoFormulaire"> ** </span> Date de naissance :
		        <input type="text" id="date_naiss" name="date_naissance" value="' . FormatageDate($this->date_naissance) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
				<span class="infoFormulaire"> ** </span> Lieu de naissance : 
		        <input type="text" id="ville_naiss" name="ville_naissance" value="' . $this->ville_naissance . '" size="30" /><br /><br />
				<span class="infoFormulaire"> ** <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>Obligatoire seulement dans le cas où le pays de naissance est la France.</div>\', FULLHTML);" onmouseout="return nd();"></img></span> Département de naissance :
		        <select id="Id_dpt_naiss" name="Id_dpt_naiss">
                    <option value="">' . DEPARTMENT_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $departement->getList() . '
                </select>
				<br /><br />
				Pays de naissance :
				<select id="Id_pays_naiss" name="Id_pays_naissance">
                    <option value="">' . COUNTRY_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $pays_naissance->getList() . '
                </select>				
			    <br /><br />
				<span class="infoFormulaire"> ** </span> Nationalité :
				<select id="Id_nationalite" name="Id_nationalite">
                    <option value="">' . NATIONALITY_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $nationalite->getList() . '
                </select>
				<br /><br />
				Etat Matrimonial :
				<select id="Id_etat_matrimonial" name="Id_etat_matrimonial">
                    <option value="">' . CIVIL_STATUS_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $em->getList() . '
                </select>
				<br /><br />				
				<span class="infoFormulaire"> ** </span> <label>CDD<input type="radio" name="type_embauche" value="CDD" ' . $type_embauche['CDD'] . '  /></label>
				<label>CDI<input type="radio" name="type_embauche" value="CDI" ' . $type_embauche['CDI'] . '  /></label>
                                <label>Intérimaire<input type="radio" name="type_embauche" value="INT" ' . $type_embauche['INT'] . '  /></label>
                                <label>Contrat d\'apprentissage<input type="radio" name="type_embauche" value="APP" ' . $type_embauche['APP'] . '  /></label>
                                <label>Contrat de professionnalisation<input type="radio" name="type_embauche" value="PRO" ' . $type_embauche['PRO'] . '  /></label>
                <br /><br />
			<span class="infoFormulaire"> ** 
                            <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>Obligatoire seulement dans le cas d\\\'un contrat CDD, d\\\'apprentissage, de professionnalisation ou intérimaire.</div>\', FULLHTML);" onmouseout="return nd();"></img>
                        </span>
                        Fin contrat :
		        <input type="text" id="fin_cdd" name="fin_cdd" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->fin_cdd) . '" size="8" /><br /><br />
                        Informations complémentaires sur le contrat : <br />
                        <textarea id="info_complementaire" name="info_complementaire" rows="2" cols="20">' . $this->info_complementaire . '</textarea>
                </div>
			<div class="right">
                <span class="infoFormulaire"> ** </span> Etam<input type="radio" name="statut_ressource" value="ETAM" ' . $statut['ETAM'] . '  />
				Cadre<input type="radio" name="statut_ressource" value="CADRE" ' . $statut['CADRE'] . '  /><br /><br />
				<span class="infoFormulaire"> ** </span> N° sécurité sociale :
		        <input type="text" id="num_ss" name="securite_sociale" value="' . $this->securite_sociale . '" size="30" /><br /><br />
				<span class="infoFormulaire"> ** </span> Service :
				<select id="Id_service" name="Id_service">
                    <option value="">' . SERVICE_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $service->getList() . '
                </select><br /><br />
				<span class="infoFormulaire"> ** </span> Agence :
				<select id="Id_agence" name="Id_agence">
                    <option value="">' . AGENCE_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $agence->getRHList() . '
                </select><br /><br />	
                <span class="infoFormulaire"> ** </span> Profil d\'embauche :
				<select id="Id_profil_cegid" name="Id_profil_cegid">
                    <option value="">' . PROFIL_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $profil->getListCegid() . '
                </select><br /><br />	
				Contrat Proservia :
				<select name="Id_contrat_proservia">
                    <option value="">' . PROSERVIA_CONTRACT_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $cp->getList() . '
                </select><br /><br />
				<span class="infoFormulaire"> ** </span> Date d\'embauche :
				<input type="text" id="date_embauche" name="date_embauche" onfocus="showCalendarControl(this)" type="text" value="' . FormatageDate($this->date_embauche) . '" size="8" /><br /><br />
 			    Heure d\'embauche : 
				<input type="text" name="heure_embauche" value="' . $this->heure_embauche . '" size="2" /> h<br /><br />
 			    <span class="infoFormulaire"> * </span> Salaire annuel : 
				<input type="text" id="salaire" name="salaire" value="' . $this->salaire . '" size="2" /> K&euro;<br />
				<span class="infoFormulaire"> ATTENTION : Salaire en K &euro; </span>
		    </div>
';
        return $html;
    }

    /**
     * Enregistre les données de la ressource dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_ressource = ' . mysql_real_escape_string((int) $this->Id_ressource) . ', code_ressource = "' . mysql_real_escape_string($this->code_ressource) . '",
		origine = "' . mysql_real_escape_string($this->origine) . '", civilite = "' . mysql_real_escape_string($this->civilite) . '", nom = "' . mysql_real_escape_string($this->nom) . '", nom_jeune_fille = "' . mysql_real_escape_string($this->nom_jeune_fille) . '",
		prenom = "' . mysql_real_escape_string($this->prenom) . '", Id_contrat_proservia = "' . mysql_real_escape_string((int) $this->Id_contrat_proservia) . '", adresse = "' . mysql_real_escape_string($this->adresse) . '",
		code_postal = "' . mysql_real_escape_string($this->code_postal) . '", ville = "' . mysql_real_escape_string($this->ville) . '", Id_dpt_naiss = "' . mysql_real_escape_string($this->Id_dpt_naiss) . '",
		Id_pays_residence = ' . mysql_real_escape_string((int) $this->Id_pays_residence) . ', tel_fixe = "' . mysql_real_escape_string(formatTel($this->tel_fixe)) . '",
		tel_portable = "' . mysql_real_escape_string(formatTel($this->tel_portable)) . '", mail = "' . mysql_real_escape_string($this->mail) . '", Id_pays_naissance = ' . mysql_real_escape_string((int) $this->Id_pays_naissance) . ',
		th = ' . ((is_null($this->th)) ? 'NULL' : mysql_real_escape_string($this->th)) . ', statut = "' . mysql_real_escape_string($this->statut) . '", securite_sociale = "' . mysql_real_escape_string(formatSecuriteSocial($this->securite_sociale)) . '",
		date_naissance = "' . mysql_real_escape_string(DateMysqltoFr($this->date_naissance, 'mysql')) . '", ville_naissance = "' . mysql_real_escape_string($this->ville_naissance) . '",
		Id_nationalite = ' . mysql_real_escape_string((int) $this->Id_nationalite) . ', type_embauche = "' . mysql_real_escape_string($this->type_embauche) . '",
		date_embauche = "' . mysql_real_escape_string(DateMysqltoFr($this->date_embauche, 'mysql')) . '", heure_embauche = ' . mysql_real_escape_string((float) $this->heure_embauche) . ',
		fin_cdd = "' . mysql_real_escape_string(DateMysqltoFr($this->fin_cdd, 'mysql')) . '", periode_essai = "' . mysql_real_escape_string($this->periode_essai) . '",
		Id_profil = ' . mysql_real_escape_string((int) $this->Id_profil) . ', Id_cursus = ' . mysql_real_escape_string((int) $this->Id_cursus) . ', Id_etat_matrimonial = ' . mysql_real_escape_string((int) $this->Id_etat_matrimonial) . ',
		Id_exp_info = ' . mysql_real_escape_string((int) $this->Id_exp_info) . ', Id_service = "' . mysql_real_escape_string($this->Id_service) . '", Id_agence = "' . mysql_real_escape_string($this->Id_agence) . '",
		salaire = ' . mysql_real_escape_string((float) $this->salaire) . ', archive = ' . mysql_real_escape_string((int) $this->archive) . ', info_complementaire = "' . mysql_real_escape_string($this->info_complementaire) . '",
                Id_profil_cegid = "' .  mysql_real_escape_string($this->Id_profil_cegid) . '"';

        if ($this->Id_ressource) {
            $requete = 'UPDATE ressource ' . $set . ' WHERE Id_ressource = ' . mysql_real_escape_string((int) $this->Id_ressource) . '';
            $_SESSION['ressource'] = $this->Id_ressource;
        } else {
            $requete = 'INSERT INTO ressource ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '"';
        }

        $rs = $db->query($requete);

        if ($this->Id_ressource == '') {
            $_SESSION['ressource'] = mysql_insert_id();
        }
        $result = $db->query('DELETE FROM ressource_specialite WHERE Id_ressource = ' . mysql_real_escape_string((int) $_SESSION['ressource']) . '');
        //Enregistrement des spécialités de la ressource
        if (!empty($this->Id_specialite)) {
            foreach ($this->Id_specialite as $i) {
                $db->query('INSERT INTO ressource_specialite SET Id_ressource = "' . mysql_real_escape_string((int) $_SESSION['ressource']) . '", Id_specialite = "' . mysql_real_escape_string((int) $i) . '"');
            }
        }
    }

    /**
     * Suppression d'une ressource
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM ressource WHERE Id_ressource = ' . mysql_real_escape_string((int) $this->Id_ressource));
        $db->query('DELETE FROM proposition_ressource WHERE Id_ressource = ' . mysql_real_escape_string((int) $this->Id_ressource));
    }

    /**
     * Affichage d'une select box contenant les ressources
     *
     * @param string Type de la ressource : candidat validé / embauché / non candidat
     *
     * @return string
     */
    public function getList($type = null) {
        $ressource[$this->Id_ressource] = 'selected="selected"';
        $db_cegid = connecter_cegid();
        $db = connecter();
        $result = $db_cegid->query('SELECT PSA_NUMEROSS, PSA_DATESORTIE FROM PROSERVIA.DBO.SALARIES');
        $num_ss = array();
        while ($ligne = $result->fetchRow()) {
            $num_ss[$ligne->psa_numeross] = $ligne->psa_datesortie;
        }
        
        if ($type == 'VAL') {
            $html .= '<option value="">+-----------------------------------------------+</option>
		    <option value="">VALIDES / EMBAUCHES</option>
		    <option value="">+-----------------------------------------------+</option>';
            $result = $db->query('SELECT ressource.Id_ressource, nom, prenom, securite_sociale, candidature.Id_candidature, mail
                FROM ressource 
                INNER JOIN candidature ON ressource.Id_ressource=candidature.Id_ressource AND staff=0 AND Id_etat IN ("6","8","9","21","22")
                WHERE candidature.archive=0 AND ressource.archive=0 ORDER BY nom');
            while ($ligne = $result->fetchRow()) {
                $n_secu = str_replace('.', "", str_replace('-', "", str_replace(' ', "", $ligne->securite_sociale)));
                $date_embauche = $db->query('SELECT date FROM historique_candidature 
                                            WHERE Id_candidature = ' . mysql_real_escape_string((int) $ligne->id_candidature) . '
                                                AND Id_etat IN ("8","9","21","22")
                                            ORDER BY date DESC LIMIT 0,1'
                                )->fetchOne();

                if($num_ss[$n_secu] < $date_embauche && $num_ss[$n_secu] != '1900-01-01 00:00:00')
                        $html .= '<option class="grisc" value="' . $ligne->id_ressource . '" ' . $ressource[$ligne->id_ressource] . '>' . substr($ligne->nom, 0, 10) . ' ' . substr($ligne->prenom, 0, 10) . '(' . substr($ligne->mail, 0, 10) . ')</option>';
            }
        }
        elseif ($type == 'EMB' || 'CAN_AGC') {
            $html .= '<option value="">+-----------------------------------------------+</option>
		    <option value="">CANDIDATS EMBAUCHES</option>
		    <option value="">+-----------------------------------------------+</option>';
            $result = $db->query('SELECT ressource.Id_ressource, nom, prenom, securite_sociale, candidature.Id_candidature, mail
                FROM ressource 
                INNER JOIN candidature ON ressource.Id_ressource=candidature.Id_ressource AND staff=0 
                AND ((Id_etat IN ("8","9","21","22")
                AND securite_sociale NOT LIKE "%0000%" AND securite_sociale NOT LIKE "%X%" AND securite_sociale !="")
                OR Id_etat IN ("23","24","25","26","27"))
                WHERE candidature.archive=0 AND ressource.archive=0 ORDER BY nom');
            while ($ligne = $result->fetchRow()) {
                $n_secu = str_replace('.', "", str_replace('-', "", str_replace(' ', "", $ligne->securite_sociale)));
                $date_embauche = $db->query('SELECT date FROM historique_candidature 
                                            WHERE Id_candidature = ' . mysql_real_escape_string((int) $ligne->id_candidature) . '
                                                AND Id_etat IN ("8","9","21","22")
                                            ORDER BY date DESC LIMIT 0,1'
                                )->fetchOne();

                if($num_ss[$n_secu] < $date_embauche && $num_ss[$n_secu] != '1900-01-01 00:00:00')
                        $html .= '<option class="grisc" value="' . $ligne->id_ressource . '" ' . $ressource[$ligne->id_ressource] . '>' . substr($ligne->nom, 0, 10) . ' ' . substr($ligne->prenom, 0, 10) . '(' . substr($ligne->mail, 0, 10) . ')</option>';
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
        if (!empty($this->Id_specialite)) {
            foreach ($this->Id_specialite as $i) {
                $specialite = new Specialite($i, array());
                $htmlSpecialite .= $specialite->libelle . '<br/>';
            }
        }
        if ($this->type_embauche == 'CDD') {
            $htmlFinCDD = ' | Fin CDD : ' . FormatageDate($this->fin_cdd);
        }
        $cursus = new Cursus($this->Id_cursus, array());
        $pays_residence = new Pays($this->Id_pays_residence, array());
        $pays_naissance = new Pays($this->Id_pays_naissance, array());
        $departement = new Departement($this->Id_dpt_naiss, array());
        $service = new Service($this->Id_service, null);
        $nationalite = new Nationalite($this->Id_nationalite);
        $em = new EtatMatrimonial($this->Id_etat_matrimonial, array());
        $agence = new Agence($this->Id_agence, array());
        $html = '
		    <h2>INFORMATIONS CANDIDAT</h2>
			<div class="left">
			    Civilité : ' . $this->civilite . '<br /><br />
			    Nom : ' . $this->nom . '<br /><br />
				Nom jeune fille : ' . $this->nom_jeune_fille . '<br /><br />
				Prénom : ' . $this->prenom . '<br /><br />
				Date de naissance : ' . DateMysqltoFr($this->date_naissance, 'mysql') . '<br /><br />
				Lieu de naissance : ' . $this->ville_naissance . '<br /><br />
				Département de naissance : ' . $departement->nom . '<br /><br />
				Pays de naissance : ' . $pays_naissance->nom . '<br /><br />
				Nationalité : ' . $nationalite->libelle . '<br /><br />
				Etat Matrimonial : ' . $em->libelle . '<br /><br />
				Adresse : ' . $this->adresse . '<br /><br />
				Code Postal : ' . $this->code_postal . '<br /><br />
				Ville : ' . $this->ville . '<br /><br />
				Pays de résidence : ' . $pays_residence->nom . '<br /><br />
			</div>
			<div class="right">			
				Tél fixe : ' . $this->tel_fixe . '<br /><br />
				Tél portable : ' . $this->tel_portable . '<br /><br />
				Mail : <a href="mailto:' . $this->mail . '">' . $this->mail . '</a><br /><br />
				Statut du candidat : ' . $this->statut . '<br /><br />
				Contrat : ' . $this->type_embauche . ' ' . $htmlFinCDD . '<br /><br />
				Contrat Proservia : ' . ContratProservia::getLibelle($this->Id_contrat_proservia) . '<br /><br />
				Profil : ' . Profil::getLibelle($this->Id_profil) . '<br /><br />
				Spécialité : ' . $htmlSpecialite . '<br /><br />
				Cursus : ' . $cursus->libelle . '<br /><br />
				Expérience Informatique : ' . $this->getExpInfo() . '<br /><br />
				Service : ' . $service->libelle . '<br /><br />
				Agence : ' . $agence->libelle . '<br /><br />
				Travailleur Handicapé : ' . yesno($this->th) . '<br /><br />
            	Date d\'embauche : ' . DateMysqltoFr($this->date_embauche, 'mysql') . '<br /><br />
			    Heure d\'embauche : ' . $this->heure_embauche . ' heure<br /><br />
 			    Salaire annuel : ' . $this->salaire . ' K&euro;
			</div>
			<div class="clearer"></div>
';
        return $html;
    }

    /**
     * Affichage de l'experience informatique
     *
     * @return string
     */
    public function getExpInfo() {
        $db = connecter();
        return $db->query('SELECT libelle FROM exp_info WHERE Id_exp_info=' . mysql_real_escape_string((int) $this->Id_exp_info))->fetchRow()->libelle;
    }

    /**
     * Calcul du coût journalier d'un collaborateur
     *
     * @param double valeur des frais journalier associés à la ressource
     *
     * @return double
     */
    public function dailyCost($fraisJ=0) {
        if ($this->statut == 'CADRE') {
            $rapport = 218;
        } elseif ($this->statut == 'ETAM') {
            $rapport = 228;
        }
        if ($this->type_embauche == 'CDD') {
            $precarite = 1;
            $frais_structure = 1.8;
        } elseif ($this->type_embauche == 'CDI') {
            $precarite = 1;
            $frais_structure = 1.6;
        }
        if ($rapport) {
            $CJ = (1000 * $this->salaire * $frais_structure * $precarite) / $rapport;
        }
        /*
          if($fraisJ) {
          $CJ = $CJ + $fraisJ;
          } */
        return round($CJ, 2);
    }

    /**
     * Affichage du coût journalier d'un collaborateur pour une proposition précise
     *
     * @param int Identifiant de la proposition commerciale
     *
     * @return string
     */
    public function caseDailyCost($Id_proposition) {
        $db = connecter();
        
        return $db->query('SELECT tarif_journalier FROM proposition_ressource WHERE Id_proposition=' . mysql_real_escape_string((int) $Id_proposition) . '
		AND Id_ressource="' . mysql_real_escape_string($this->Id_ressource) . '"')->fetchRow()->tarif_journalier;
    }

    /**
     * Affichage du Nom et du prénom et l'email de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public function getName() {
        return $this->nom . ' ' . $this->prenom. ' (' . $this->mail .')';
    }

    /**
     * Affichage de l'email de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getMail($i) {
        $db = connecter();
        $ligne = $db->query('SELECT mail FROM ressource WHERE Id_ressource=' . mysql_real_escape_string((int) $i))->fetchRow();
        return $ligne->mail;
    }

    /**
     * Affichage de l'identifiant du profil de la ressource
     *
     * @param Identifiant de la ressource
     *
     * @return int
     */
    public function getIdProfil() {
        return $this->Id_profil;
    }

    /**
     * Affichage de l'identifiant du code salarie de la ressource
     *
     * @param Identifiant de la ressource
     *
     * @return int
     */
    public static function getCegidEmployeeCode($i) {
        $db = connecter_cegid();
        return $db->query(
            'SELECT PSA_SALARIE FROM SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
             UNION SELECT PSA_SALARIE FROM ALCYON.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
             UNION SELECT PSA_SALARIE FROM ELITEX.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
             UNION SELECT PSA_SALARIE FROM ALTIQUENET.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
             UNION SELECT PSA_SALARIE FROM LYNT.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"'
        )->fetchRow()->psa_salarie;
    }

    /**
     * Affichage de le code ressource Cegid à partir du code ressource AGC
     *
     * @param Identifiant de la ressource agc
     *
     * @return int
     */
    public static function getCegidResourceCode($i) {
        $db = connecter_cegid();
        return $db->query('SELECT ARS_RESSOURCE FROM RESSOURCE
            LEFT OUTER JOIN SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
            UNION SELECT ARS_RESSOURCE FROM ALCYON.DBO.RESSOURCE
            LEFT OUTER JOIN ALCYON.DBO.SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
            UNION SELECT ARS_RESSOURCE FROM ELITEX.DBO.RESSOURCE
            LEFT OUTER JOIN ELITEX.DBO.SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
            UNION SELECT ARS_RESSOURCE FROM ALTIQUENET.DBO.RESSOURCE
            LEFT OUTER JOIN ALTIQUENET.DBO.SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
            UNION SELECT ARS_RESSOURCE FROM LYNT.DBO.RESSOURCE
            LEFT OUTER JOIN LYNT.DBO.SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"'
        )->fetchRow()->ars_ressource;
    }

    /**
     * Affichage de l'identifiant de l'agence du salarie
     *
     * @param int Identifiant de la ressource
     *
     * @return int
     */
    public function getAgency() {
        return $this->Id_agence;
    }

    /**
     * Affichage du numéro de sécurité sociale de la ressource
     *
     * @param int Identifiant de la ressource candidat
     *
     * @return string
     */
    public static function getSocialInsuranceNumber($i) {
        $db = connecter();
        return $db->query('SELECT replace(securite_sociale," ","") as securite_sociale FROM ressource WHERE Id_ressource=' . mysql_real_escape_string((int) $i))->fetchRow()->securite_sociale;
    }

    /**
     * Affichage du numéro de candidature
     *
     * @param int Identifiant de la ressource candidat
     *
     * @return int
     */
    public function getIdCandidature() {
        return $this->Id_ressource;
    }

    /**
     * Indique si la ressource est staff ou non
     *
     * @param int Identifiant de la ressource candidat
     *
     * @return int
     */
    public function isStaff() {
        $db = connecter();
        return $db->query('SELECT staff FROM candidature c INNER JOIN ressource r ON r.Id_ressource=c.Id_ressource
		WHERE c.Id_ressource=' . mysql_real_escape_string((int) $i))->fetchRow()->staff;
    }

    /**
     * Affichage des positionnements d'une ressource par rapport à une demande de recrutement
     *
     * @param int Identifiant de la ressource
     *
     * @return int
     */
    public function getPositioning($modif = 0) {
        $id_candidature = self::getIdCandidature($this->Id_ressource);
        $requete = 'SELECT dr.Id_demande_ressource,comp_profil,client,Id_ic,DATE_FORMAT(drc.date,"%d-%m-%Y") as date, drc.Id_candidat FROM demande_ressource dr
		INNER JOIN demande_ressource_candidat drc ON dr.Id_demande_ressource=drc.Id_demande_ressource 
		WHERE Id_ressource="' . $this->Id_ressource . '"';

        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            $html = '
			<h2>POSITIONNEMENT</h2>
		        <table>
		            <tr>
			            <th>Initulé</th>
						<th>Client / Action</th>
						<th>Commercial / Réalisé par</th>
						<th>Date Affectation / Date Création</th>
		            </tr>';
            $l = 0;
            foreach ($paged_data['data'] as $ligne) {
                $db = connecter();

                $resultAction = $db->query('SELECT Id_action_mener, DATE_FORMAT(date_action, "%d-%m-%Y") as date_h, date_action, Id_utilisateur
				FROM historique_action_candidature WHERE Id_candidature=' . mysql_real_escape_string((int) $id_candidature) . ' AND Id_positionnement = ' . mysql_real_escape_string((int) $ligne['id_candidat']) . ' ORDER BY date_action DESC');
                $j = ($l % 2 == 0) ? 'odd' : 'even';
                $html .= '
                <tr class="row' . $j . '" >
	                <td><a href="../membre/index.php?a=editerCandidatDemandeRessource&Id_demande_ressource=' . $ligne['id_demande_ressource'] . '">' . $ligne['comp_profil'] . '</a></td>
					<td>' . $ligne['client'] . '</td>
					<td>' . $ligne['id_ic'] . '</td>
					<td>' . $ligne['date'] . '</td>
                </tr>';
                $k = 0;
                while ($ligneAction = $resultAction->fetchRow()) {
                    if ($modif) {
                        $htmlSupp = '<td width="5%" height="20"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { supprimerHAction(' . $id_candidature . ',' . $ligneAction->id_action_mener . ',\'' . $ligneAction->date_action . '\', ' . $ligne['id_candidat'] . ') }" /></td>';
                        $htmlValid = '<td width="5%" height="20"><input type="button" class="boutonValider" onclick="if (confirm(\'Valider la ligne ?\')) { validerHAction(' . $id_candidature . ',' . $ligneAction->id_action_mener . ',' . $l . $k . ',' . $ligne['id_candidat'] . '); supprimerHAction(' . $id_candidature . ',' . $ligneAction->id_action_mener . ',\'' . $ligneAction->date_action . '\')}" /></td>';
                        $htmlInput = '<td width="30%" height="20"><input type="text"  onfocus="showCalendarControl(this)" id="date_action' . $l . $k . '" value="' . $ligneAction->date_h . '" size="8" /></td>';
                    } else {
                        $htmlInput = '<td width="40%" height="20">' . $ligneAction->date_h . '</td>';
                    }
                    $html .= '<tr class="row' . $j . '">
				    <td style="background-color:#FFF;"></td>
					<td width="30%" height="20">' . Candidature::getLibelleActionMener($ligneAction->id_action_mener) . '</td>
					<td width="30%" height="20">' . $ligneAction->id_utilisateur . '</td>
					' . $htmlInput . '
					' . $htmlSupp . '
					' . $htmlValid . '
					</tr>';
                    ++$k;
                }
                ++$l;
            }
            $html .= '</table>';
        }
        return $html;
    }

    /**
     * Edition de la partie ressource (partie 'identité' du candidat) de la fiche candidature en pdf
     *
     * @param object pdf en cours de création
     */
    public function edit1($pdf) {
        //création d'une instance pour récupérer la nationalité du candidat
        $nationalite = new Nationalite($this->Id_nationalite);
        //affichage de la date de naissance si celle-ci est renseignée
        $date_naissance = ($this->date_naissance == '0000-00-00') ? ' ' : FormatageDate($this->date_naissance);
        //début de la partie 'identité' du pdf
        $pdf->setXY(100, 20);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCellTag(80, 2, "{$this->civilite}. {$this->prenom} {$this->nom}", 0, 'C', 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $y = $pdf->GetY() + 10;
        $pdf->setXY(15, $y);
        $pdf->setLeftMargin(15);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->MultiCellTag(70, 7, "<t2>Nom : </t2><t3>{$this->nom}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Nationalité : </t2><t3>{$nationalite->libelle}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Adresse : </t2><t3>{$this->adresse}</t3>", 0, 'L', 0);
        $pdf->setLeftMargin(30);
        $pdf->MultiCellTag(70, 7, "<t3> {$this->code_postal} {$this->ville}</t3>", 0, 'L', 0);
        $y1 = $pdf->GetY();
        $pdf->setLeftMargin(105);
        $pdf->setY($y);
        $pdf->MultiCellTag(70, 7, "<t2>Prénom : </t2><t3>{$this->prenom}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Date de naissance : </t2><t3>" . $date_naissance . "</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Tel. fixe/portable : </t2><t3>{$this->tel_fixe} / {$this->tel_portable}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Mail : </t2><t3>{$this->mail}</t3>", 0, 'L', 0);
        if ($y1 > $pdf->GetY()) {
            $pdf->setY($y1);
        }
    }

    /**
     * Edition de la partie ressource de la zone 'information candidat' de la fiche candidature en pdf
     *
     * @param object pdf en cours de création
     */
    public function edit2($pdf) {
        //création des instances pour récupérer la spécialité, le cursus et le service du candidat
        if (!empty($this->Id_specialite)) {
            foreach ($this->Id_specialite as $i) {
                $specialite = new Specialite($i, array());
                $htmlSpecialite .= $specialite->libelle . "\n";
            }
        }
        $cursus = new Cursus($this->Id_cursus, array());
        //affichage de la date d'embauche si celle-ci est renseignée
        $date_embauche = ($this->date_embauche == '0000-00-00') ? ' ' : FormatageDate($this->date_embauche);

        if ($this->type_embauche == 'CDD' || $this->type_embauche == 'APP' || $this->type_embauche == 'PRO' || $this->type_embauche == 'INT') {
            $htmlFinCDD = " <t2>Fin contrat : </t2>" . FormatageDate($this->fin_cdd);
        }

        //début de la partie ressource de la zone 'information candidat' du pdf
        $pdf->setLeftMargin(15);
        $pdf->MultiCellTag(70, 7, "<t2>Profil : </t2><t3>" . Profil::getLibelle($this->Id_profil) . "</t3>", 0, 'L', 0);
        $y = $pdf->GetY();
        $pdf->MultiCellTag(90, 7, "<t2>Spécialité : </t2><t3>{$htmlSpecialite}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Diplôme : </t2><t3> {$cursus->libelle} </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Expérience informatique : </t2><t3> {$this->getExpInfo()} </t3>", 0, 'L', 0);
        $y2 = $pdf->GetY();
        $pdf->setLeftMargin(105);
        $pdf->setY($y);
        $pdf->MultiCellTag(90, 7, "<t2>Contrat : </t2><t3>{$this->type_embauche} {$htmlFinCDD}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t2>Contrat Proservia : </t2><t3>" . ContratProservia::getLibelle($this->Id_contrat_proservia) . "</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Date d'embauche : </t2><t3> {$date_embauche}</t3>", 0, 'L', 0);
        $pdf->setXY(10, $y2 + 3);
    }

}

?>
