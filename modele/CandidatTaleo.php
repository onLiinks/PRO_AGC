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
class CandidatTaleo implements IRessource {

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
                $tc = new TaleoClient();
                $o = $tc->getCandidateInformation($code);

                $this->Id_ressource = $o->Number;
                $this->type_ressource = 'CAN';
                //$this->code_ressource = $ligne->code_ressource;
                $this->civilite = $o->Civilite;
                //$this->origine = $ligne->origine;
                $this->nom = $o->LastName;
                $this->nom_jeune_fille = $o->NomJeuneFille;
                $this->prenom = $o->FirstName;
                $this->adresse = str_replace("'", " ", $o->Address . ' ' . $o->Address2);
                $this->code_postal = $o->ZipCode;
                $this->ville = $o->City;
                $this->Id_dpt_naiss = $o->DepartementNaissance;
                $this->Id_pays_residence = $o->Country;
                $this->Id_pays_naissance = $o->PaysNaissance;
                $this->tel_fixe = $o->HomePhone;
                $this->tel_portable = $o->MobilePhone;
                $this->mail = $o->EmailAddress;
                $this->statut = $o->Statut;
                $this->securite_sociale = $o->NumeroSS;
                $time = strtotime($o->DateNaissance);
                $this->date_naissance = date('d-m-Y', $time);
                $this->ville_naissance = $o->LieuNaissance;
                $this->Id_nationalite = $o->Nationalite;
                $this->Id_etat_matrimonial = $o->EtatMatrimonial;
                $this->type_embauche = $o->TypeEmbauche;
                $time = strtotime($o->DateEmbauche);
                $this->date_embauche = date('d-m-Y', $time);
                $this->heure_embauche = $o->HeureEmbauche;
                $time = strtotime($o->DateFinContrat);
                $this->fin_cdd = date('d-m-Y', $time);
                $this->id_motif_cdd = $o->IdMotifCDD;
                $this->motif_cdd = $o->MotifCDD;
                $this->salarie_remplace = $o->SalarieRemplace;
                $this->periode_essai = $o->periode_essai;
                $this->Id_profil = $o->IdProfil;
                $this->profil = $o->Profil;
                $this->profil_cegid = $o->ProfilCegid;
                $this->salaire = $o->Salaire;
                $this->Id_service = $o->IdService;
                $this->Id_contrat_proservia = $o->ContratProservia;
                $this->Id_agence = $o->IdAgenceRattachement;
                $this->agence = $o->AgenceRattachement;
                $this->info_complementaire = $o->InfoComplementaire;
                $this->type_gestion = $o->TypeGestion;
                $this->libelle_emploi_comp = $o->LibelleEmploiComp;
                $this->societe = Bdd::getCegidDatabaseByCode(substr($this->Id_service, 0, 3));
                
                if ($o->TH == 1) {
                    $this->th = 1;
                }
                else {
                    $this->th = 0;
                }
                
                //Staff n'est plus visible en production -> heritage
                if ($o->Staff == 1) {
                    $this->embauche_staff = 1;
                    $this->etat = 'Staff';
                }
                else if ($o->Staff == 2) {
                    $this->embauche_staff = 0;
                    $this->etat = 'Collab';
                }
                //Nouveau systeme : typeembauche
                if ($o->TypeEmbauche2 > 0) {
					$this->type_embauche2 = $o->TypeEmbauche2;
                    switch ($o->TypeEmbauche2) {
                        case 1:
                            $this->embauche_staff = 1;
                            $this->etat = 'Personnel de structure (Staff)';
                            break;
                        case 2 :
                            $this->embauche_staff = 0;
                            $this->etat = 'Consultants pôle A/I ou Delivery Manager';
                            break;
                        case 3 :
                            $this->embauche_staff = 0;
                            $this->etat = 'Collaborateur sur site client ou itinérance';
							$this->itinerant = 1;
                            break;
                        case 4 :
                            $this->embauche_staff = 0;
                            $this->etat = 'Collaborateur sur CDS';
                            break;
                    }
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
     * Affichage d'une select box contenant les ressources
     *
     * @param string Type de la ressource : candidat validé / embauché / non candidat
     *
     * @return string
     */
    public function getList($type = null) {
        $ressource[$this->Id_ressource] = 'selected="selected"';
        $html .= '<option value="">+-----------------------------------------------+</option>
                <option value="">CANDIDATS EMBAUCHES</option>
                <option value="">+-----------------------------------------------+</option>';
        $tc = new TaleoClient();
        $query = '
            <ns1:query alias="SimpleProjection" projectedClass="Candidate">
                    <ns1:projections>
                            <ns1:projection>
                                    <ns1:field path="Number"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="LastName"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="FirstName"/>
                            </ns1:projection>
                            <ns1:projection>
                                    <ns1:field path="EmailAddress"/>
                            </ns1:projection>
                    </ns1:projections>
                    <ns1:filterings>
                        <ns1:filtering>
                            <ns1:equal>
                                <ns1:field path="InternalApplication"/>
                                <ns1:boolean>true</ns1:boolean>
                            </ns1:equal>
                        </ns1:filtering>
                        <ns1:filtering>
                            <ns1:isNull>
                                <ns1:field path="EmployeeNumber"/>
                            </ns1:isNull>
                        </ns1:filtering>
                        <ns1:filtering>
                            <ns1:or>
                                <ns1:equal>
                                    <ns1:field path="TalentUser,Profile,Studies,Staff,UDSElement.Code"/>
                                    <ns1:string>2</ns1:string>
                                </ns1:equal>
                                <ns1:equal>
                                    <ns1:field path="TalentUser,Profile,Studies,TypeEmbauche,UDSElement.Code"/>
                                    <ns1:string>2</ns1:string>
                                </ns1:equal>
                                <ns1:equal>
                                    <ns1:field path="TalentUser,Profile,Studies,TypeEmbauche,UDSElement.Code"/>
                                    <ns1:string>3</ns1:string>
                                </ns1:equal>
                                <ns1:equal>
                                    <ns1:field path="TalentUser,Profile,Studies,TypeEmbauche,UDSElement.Code"/>
                                    <ns1:string>4</ns1:string>
                                </ns1:equal>
                            </ns1:or>
                        </ns1:filtering>
                    </ns1:filterings>
                    <ns1:sortings>
                        <ns1:sorting ascending="true">
                            <ns1:field path="LastName" />
                        </ns1:sorting>
                    </ns1:sortings>
            </ns1:query>';
        $result = $tc->query($query);

        foreach ($result as $o) {
            $html .= '<option class="grisc" value="' . $o->Number . '" ' . $ressource[$o->Number] . '>' . utf8_decode($o->LastName) . ' ' . utf8_decode($o->FirstName);
            if($o->EmailAddress != '') $html .= ' (' . utf8_decode($o->EmailAddress) . ')';
            $html .= '</option>';
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
        $service = new Service(substr($this->Id_service, 3), null);
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
				Itinérant : ' . ($this->type_embauche2 == 3 ? 'Oui' : 'Non') . '<br /><br />
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
     * Affichage du Nom et du prénom de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public function getName() {
        return $this->nom . ' ' . $this->prenom;
    }

    /**
     * Affichage de l'email de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getMail($i) {
        if ((int) $i) {
            $db = connecter();
            $ligne = $db->query('SELECT mail FROM ressource WHERE Id_ressource=' . mysql_real_escape_string((int) $i))->fetchRow();
            return $ligne->mail;
        } else {
            $db = connecter_cegid();
            $ligne = $db->query('SELECT PSE_EMAILPROF FROM DEPORTSAL d LEFT JOIN SALARIES s ON d.pse_salarie=s.psa_salarie LEFT JOIN RESSOURCE r ON r.ars_salarie=s.psa_salarie WHERE ARS_RESSOURCE="' . $i . '"
			 UNION SELECT PSE_EMAILPROF FROM ALCYON.DBO.DEPORTSAL LEFT JOIN ALCYON.DBO.SALARIES ON pse_salarie=ALCYON.DBO.salaries.psa_salarie LEFT JOIN ALCYON.DBO.RESSOURCE ON ars_salarie=ALCYON.DBO.salaries.psa_salarie WHERE ARS_RESSOURCE="' . $i . '"
			 UNION SELECT PSE_EMAILPROF FROM ALTIQUENET.DBO.DEPORTSAL LEFT JOIN ALTIQUENET.DBO.SALARIES ON pse_salarie=ALTIQUENET.DBO.salaries.psa_salarie LEFT JOIN ALTIQUENET.DBO.RESSOURCE ON ars_salarie=ALTIQUENET.DBO.salaries.psa_salarie WHERE ARS_RESSOURCE="' . $i . '"
			 UNION SELECT PSE_EMAILPROF FROM ELITEX.DBO.DEPORTSAL LEFT JOIN ELITEX.DBO.SALARIES ON pse_salarie=ELITEX.DBO.salaries.psa_salarie LEFT JOIN ELITEX.DBO.RESSOURCE ON ars_salarie=ELITEX.DBO.salaries.psa_salarie WHERE ARS_RESSOURCE="' . $i . '"
			 UNION SELECT PSE_EMAILPROF FROM LYNT.DBO.DEPORTSAL LEFT JOIN LYNT.DBO.SALARIES ON pse_salarie=LYNT.DBO.salaries.psa_salarie LEFT JOIN LYNT.DBO.RESSOURCE ON ars_salarie=LYNT.DBO.salaries.psa_salarie WHERE ARS_RESSOURCE="' . $i . '"')->fetchRow();
            return $ligne->pse_emailprof;
        }
    }

    /**
     * Affichage de l'identifiant du profil de la ressource
     *
     * @param Identifiant de la ressource
     *
     * @return int
     */
    public static function getIdProfil($i) {
        if ((is_numeric($i))) {
            $db = connecter();
            return $db->query('SELECT Id_profil FROM ressource WHERE Id_ressource=' . mysql_real_escape_string((int) $i))->fetchRow()->id_profil;
        } else {
            $db = connecter_cegid();
            return $db->query('SELECT PSA_LIBELLEEMPLOI FROM SALARIES WHERE PSA_SALARIE="' . self::getCegidEmployeeCode($i) . '"
			UNION SELECT PSA_LIBELLEEMPLOI FROM ALCYON.DBO.SALARIES WHERE PSA_SALARIE="' . self::getCegidEmployeeCode($i) . '"
			UNION SELECT PSA_LIBELLEEMPLOI FROM ALTIQUENET.DBO.SALARIES WHERE PSA_SALARIE="' . self::getCegidEmployeeCode($i) . '"
			UNION SELECT PSA_LIBELLEEMPLOI FROM ELITEX.DBO.SALARIES WHERE PSA_SALARIE="' . self::getCegidEmployeeCode($i) . '"
			UNION SELECT PSA_LIBELLEEMPLOI FROM LYNT.DBO.SALARIES WHERE PSA_SALARIE="' . self::getCegidEmployeeCode($i) . '"')->fetchRow()->psa_libelleemploi;
        }
    }

    /**
     * Affichage de l'identifiant du code salarie de la ressource
     *
     * @param Identifiant de la ressource
     *
     * @return int
     */
    public static function getCegidEmployeeCode($i) {
        if ((is_numeric($i))) {
            $db = connecter_cegid();
            return $db->query('SELECT PSA_SALARIE FROM SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
		    '/*UNION SELECT PSA_SALARIE FROM ALCYON.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
		    UNION SELECT PSA_SALARIE FROM ELITEX.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
		    UNION SELECT PSA_SALARIE FROM ALTIQUENET.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
			UNION SELECT PSA_SALARIE FROM LYNT.DBO.SALARIES WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"'*/)->fetchRow()->psa_salarie;
        } else {
            $db = connecter_cegid();
            return $db->query('SELECT ARS_SALARIE FROM RESSOURCE WHERE ARS_RESSOURCE="' . $i . '"
		    '/*UNION SELECT ARS_SALARIE FROM ALCYON.DBO.RESSOURCE WHERE ARS_RESSOURCE="' . $i . '"
		    UNION SELECT ARS_SALARIE FROM ALTIQUENET.DBO.RESSOURCE WHERE ARS_RESSOURCE="' . $i . '"
		    UNION SELECT ARS_SALARIE FROM ELITEX.DBO.RESSOURCE WHERE ARS_RESSOURCE="' . $i . '"
			UNION SELECT ARS_SALARIE FROM LYNT.DBO.RESSOURCE WHERE ARS_RESSOURCE="' . $i . '"'*/)->fetchRow()->ars_salarie;
        }
    }

    /**
     * Affichage de le code ressource Cegid à partir du code ressource AGC
     *
     * @param Identifiant de la ressource agc
     *
     * @return int
     */
    public static function getCegidResourceCode($i) {
        if ((is_numeric($i))) {
            $db = connecter_cegid();
            return $db->query('SELECT ARS_RESSOURCE FROM RESSOURCE
            LEFT OUTER JOIN SALARIES ON (ARS_SALARIE=PSA_SALARIE AND ARS_TYPERESSOURCE="SAL")
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"
            '/*UNION SELECT ARS_RESSOURCE FROM ALCYON.DBO.RESSOURCE
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
            WHERE PSA_NUMEROSS="' . self::getSocialInsuranceNumber($i) . '"'*/)->fetchRow()->ars_ressource;
        } else {
            return $i;
        }
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
     * Affichage du type de contrat de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getContractType($i) {
        $db = connecter_cegid();
        $ligne = $db->query('SELECT PCI_TYPECONTRAT FROM CONTRATTRAVAIL WHERE PCI_SALARIE="' . self::getCegidEmployeeCode($i) . '" AND PCI_DEBUTCONTRAT=(select max(PCI_DEBUTCONTRAT) FROM contrattravail WHERE pci_salarie="' . self::getCegidEmployeeCode($i) . '")
		 '/*UNION SELECT PCI_TYPECONTRAT FROM ALCYON.DBO.CONTRATTRAVAIL WHERE PCI_SALARIE="' . self::getCegidEmployeeCode($i) . '" AND PCI_DEBUTCONTRAT=(select max(PCI_DEBUTCONTRAT) FROM ALCYON.DBO.contrattravail WHERE pci_salarie="' . self::getCegidEmployeeCode($i) . '")
		 UNION SELECT PCI_TYPECONTRAT FROM ALTIQUENET.DBO.CONTRATTRAVAIL WHERE PCI_SALARIE="' . self::getCegidEmployeeCode($i) . '" AND PCI_DEBUTCONTRAT=(select max(PCI_DEBUTCONTRAT) FROM ALTIQUENET.DBO.contrattravail WHERE pci_salarie="' . self::getCegidEmployeeCode($i) . '")
		 UNION SELECT PCI_TYPECONTRAT FROM ELITEX.DBO.CONTRATTRAVAIL WHERE PCI_SALARIE="' . self::getCegidEmployeeCode($i) . '" AND PCI_DEBUTCONTRAT=(select max(PCI_DEBUTCONTRAT) FROM ELITEX.DBO.contrattravail WHERE pci_salarie="' . self::getCegidEmployeeCode($i) . '")'*/)->fetchRow();
        if ($ligne->pci_typecontrat == 'CCD') {
            return 'CDD';
        } elseif ($ligne->pci_typecontrat == 'CDI') {
            return 'CDI';
        }
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
        return $this->embauche_staff;
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
        $service = new Service(substr($this->Id_service, 3), null);
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
