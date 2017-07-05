<?php

/**
 * Fichier ContratDelegation.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe ContratDelegation
 */
class ContratDelegation {

    /**
     * Identifiant du contrat délégation
     *
     * @access private
     * @var int
     */
    public $Id_contrat_delegation;

    /**
     * Identifiant de l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_affaire;

    /**
     * Source de l'affaire (AGC ou SF)
     *
     * @access public
     * @var int
     */
    public $source;

    /**
     * Identifiant de la ressource
     *
     * @access public
     * @var string
     */
    public $Id_ressource;

    /**
     * Date d'embauche de la ressource
     *
     * @access private
     * @var date
     */
    private $date_embauche;

    /**
     * Heure d'embauche de la ressource
     *
     * @access private
     * @var double
     */
    private $heure_embauche;

    /**
     * Salaire de la ressource
     *
     * @access private
     * @var double
     */
    private $salaire;

    /**
     * Créateur du contrat délégtation
     *
     * @access public
     * @var string
     */
    public $createur;

    /**
     * Date de création du contrat délégtation
     *
     * @access private
     * @var date
     */
    private $date_creation;

    /**
     * Date de modification du contrat délégtation
     *
     * @access private
     * @var date
     */
    private $date_modification;

    /**
     * Date d'envoi du contrat délégtation au services ADV et paie
     *
     * @access private
     * @var date
     */
    private $date_envoi;

    /**
     * Statut du CD
     *
     * @access public
     * @var datetime
     */
    public $statut;
    
    /**
     * Identifiant de la raison du refus
     *
     * @access public
     * @var datetime
     */
    public $Id_cause_refus;
    
    /**
     * Commentaire sur la raison du refus
     *
     * @access public
     * @var datetime
     */
    public $commentaire_refus;
	
	/**
     * date de debut de la mission
     *
     * @access public
     * @var datetime
     */
    public $debut_mission;

    /**
     * Date de fin de mission
     *
     * @access public
     * @var datetime
     */
    public $fin_mission;

    /**
     * Id du devis
     *
     * @access public
     * @var datetime
     */
    public $Id_devis;
    
    /**
     * Identifiant de ressource créer sur la propoisition
     *
     * @access public
     * @var datetime
     */
    public $Id_prop_ress;
    public $Id_compte;
    private $compte;
    private $mode_reglement;
    public $contact1;
    private $contact2;
    private $commercial;
    private $sdm;
    private $agence;
    private $pole;
    private $type_contrat;
    private $type_infogerance;
    public $intitule;
    private $ca_affaire;
    public $reference_affaire;
    public $reference_affaire_mere;
    public $reference_devis;
    public $reference_bdc;
    private $Id_cegid;
    private $type;
    public $type_ressource;
    public $code_otp;
    public $code_produit;
    public $agence_mission;
    public $Id_compte_final;
    public $compte_final;

    /**
     * Constructeur de la classe ContratDelegation
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du contrat délégation
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_contrat_delegation = '';
                $this->Id_duplication = '';
                $this->Id_prop_ress = '';
                $this->createur = '';
                $this->date_creation = '';
                $this->date_modification = '';
                $this->date_envoi = '';
                $this->statut = 'A';
                $this->Id_cause_refus = '';
                $this->commentaire_refus = '';
                $this->Id_cause_reouverture = '';
                $this->commentaire_reouverture = '';
                $this->Id_affaire = '';
                $this->source = '';
                $this->adresse_facturation = '';
                $this->contact_principal = '';
                $this->fonction_cprincipal = '';
                $this->cout_journalier = '';
                $this->frais_journalier_ress = '';
                $this->cout_journalier_ress = '';
                $this->type_mission = '';
                $this->debut_mission = '';
                $this->fin_mission = '';
                $this->duree_mission = '';
                $this->lieu_mission = '';
                $this->tache = '';
                $this->indemnites_ref = 'Non';
                $this->Id_ressource = '';
                $this->salaire = 0;
                $this->type_ressource = '';
                $this->materiel = 0;
                $this->horaire = '';
                $this->commentaire_horaire = '';
                $this->moyen_acces = '';
                $this->Id_type_indemnite = '';
                $this->indemnite_destination = '';
                $this->indemnite_region = '';
                $this->indemnite_type_deplacement = '';
                $this->indemnite = '';
                $this->commentaire_indemnite = '';
                $this->astreinte = 'Non';
                $this->commentaire_astreinte = '';
                $this->nature_mission = '';
                $this->remplacement = '';
                $this->st_nom = '';
                $this->st_prenom = '';
                $this->st_societe = '';
                $this->st_adresse = '';
                $this->st_siret = '';
                $this->st_ape = '';
                $this->st_tarif = '';
                $this->st_commentaire = '';
                $this->archive = 0;
                $this->Id_devis = '';
                $this->code_otp = '';
                $this->code_produit = '';
                $this->agence_mission = '';
                $this->Id_compte_final = '';
                $this->compte_final = '';
                $this->politique_securite_demandee = '';
                $this->necessite_plan_prevention = '';
                $this->equipement_securite_a_prevoir = '';
                $this->mission_implique_isolement = '';
                $this->formations_specifiques_exigees = '';
                $this->poste_implique_habilitation = '';
                $this->presence_politique_client = '';
                $this->documents_Proservia_specifiques = '';
                $this->smr = '';
				$this->itinerant = 0;
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_contrat_delegation = '';
                $this->Id_duplication = '';
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->date_creation = DATETIME;
                $this->date_modification = DATETIME;
                $this->statut = 'A';
                $this->frais_journalier_ress = htmlscperso(stripslashes($tab['frais_journalier_ress']), ENT_QUOTES);
                $this->cout_journalier_ress = htmlscperso(stripslashes($tab['cout_journalier_ress']), ENT_QUOTES);

                /* Info Générales */
                $this->Id_affaire = $tab['Id_affaire'];
                $this->Id_devis = $tab['Id_devis'];
                $this->source = $tab['source'];
                $this->Id_prop_ress = $tab['Id_prop_ress'];
                $this->cout_journalier = htmlscperso(stripslashes($tab['cout_journalier']), ENT_QUOTES);
                $this->type_mission = $tab['type_mission'];
                $this->adresse_facturation = htmlscperso(stripslashes($tab['adresse_facturation']), ENT_QUOTES);
                $this->contact_principal = htmlscperso(stripslashes($tab['contact_principal']), ENT_QUOTES);
                $this->fonction_cprincipal = htmlscperso(stripslashes($tab['fonction_cprincipal']), ENT_QUOTES);
                $this->Id_compte = htmlscperso(stripslashes($tab['Id_compte']), ENT_QUOTES);
                $this->compte = htmlscperso(stripslashes($tab['compte']), ENT_QUOTES);
                $this->mode_reglement = htmlscperso(stripslashes($tab['mode_reglement']), ENT_QUOTES);
                $this->contact1 = htmlscperso(stripslashes($tab['contact1']), ENT_QUOTES);
                $this->contact2 = htmlscperso(stripslashes($tab['contact2']), ENT_QUOTES);
                $this->commercial = htmlscperso(stripslashes($tab['commercial']), ENT_QUOTES);
                $this->apporteur = htmlscperso(stripslashes($tab['apporteur']), ENT_QUOTES);
                $this->sdm = htmlscperso(stripslashes($tab['sdm']), ENT_QUOTES);
                $this->agence = htmlscperso(stripslashes($tab['agence']), ENT_QUOTES);
                $this->pole = htmlscperso(stripslashes($tab['pole']), ENT_QUOTES);
                $this->type_contrat = htmlscperso(stripslashes($tab['type_contrat']), ENT_QUOTES);
                $this->type_infogerance = htmlscperso(stripslashes($tab['type_infogerance']), ENT_QUOTES);
                $this->type = htmlscperso(stripslashes($tab['type']), ENT_QUOTES);
                $this->intitule = htmlscperso(stripslashes($tab['intitule']), ENT_QUOTES);
                $this->ca_affaire = htmlscperso(stripslashes($tab['ca_affaire']), ENT_QUOTES);
                $this->reference_affaire = $tab['reference_affaire'];
                $this->reference_affaire_mere = $tab['reference_affaire_mere'];
                $this->reference_devis = $tab['reference_devis'];
                $this->reference_bdc = $tab['reference_bdc'];
                $this->Id_cegid = $tab['Id_cegid'];
                $this->nom_ressource = htmlscperso(stripslashes($tab['nom_ressource']));
                $this->prenom_ressource = htmlscperso(stripslashes($tab['prenom_ressource']));
                $this->embauche_staff = htmlscperso(stripslashes($tab['embauche_staff']));

                $this->code_otp = htmlscperso(stripslashes($tab['code_otp']));
                $this->code_produit = htmlscperso(stripslashes($tab['code_produit']));
                $this->agence_mission = htmlscperso(stripslashes($tab['agence_mission']));
                $this->Id_compte_final = htmlscperso(stripslashes($tab['Id_compte_final']));
                $this->compte_final = htmlscperso(stripslashes($tab['compte_final']));
                
                $this->politique_securite_demandee = $tab['politique_securite_demandee'];
                $this->necessite_plan_prevention = htmlscperso(stripslashes($tab['necessite_plan_prevention']));
                $this->equipement_securite_a_prevoir = htmlscperso(stripslashes($tab['equipement_securite_a_prevoir']));
                $this->mission_implique_isolement = htmlscperso(stripslashes($tab['mission_implique_isolement']));
                $this->formations_specifiques_exigees = htmlscperso(stripslashes($tab['formations_specifiques_exigees']));
                $this->poste_implique_habilitation = htmlscperso(stripslashes($tab['poste_implique_habilitation']));
                $this->presence_politique_client = htmlscperso(stripslashes($tab['presence_politique_client']));
                $this->documents_Proservia_specifiques = htmlscperso(stripslashes($tab['documents_Proservia_specifiques']));
                $this->smr = htmlscperso(stripslashes($tab['smr']));
                
                if (count($tab) == 2) {
                    $affaire = new Affaire($this->Id_affaire, array());
                    $planning = new Planning($affaire->Id_planning, array());
                    $description = new Description($affaire->Id_description, array());
                    $this->debut_mission = FormatageDate($planning->date_debut);
                    $this->fin_mission = FormatageDate($planning->date_fin_commande);
                    $this->duree_mission = $planning->duree;
                    $this->lieu_mission = $description->ville . ' ' . $description->cp;
                    $this->indemnites_ref = 'Non';
                    $this->astreinte = 'Non';
                    $this->remplacement = 0;
                } else {
                    $this->debut_mission = DateMysqltoFr($tab['debut_mission']);
                    $this->fin_mission = DateMysqltoFr($tab['fin_mission']);
                    $this->duree_mission = $tab['duree'];
                    $this->lieu_mission = $tab['lieu_mission'];
                    $this->indemnites_ref = $tab['indemnites_ref'];
                    $this->astreinte = $tab['astreinte'];
                    $this->remplacement = $tab['remplacement'];
                }

                $this->materiel = $tab['materiel'];
                $this->tache = $tab['tache'];
                $this->Id_ressource = str_replace('"', '', $tab['Id_ressource']);
                $this->type_ressource = $tab['type_ressource'];
                $this->salaire = $tab['salaire'];

                /* Poste du collaborateur  */

                $this->horaire = $tab['horaire'];
                $this->commentaire_horaire = $tab['commentaire_horaire'];
                $this->moyen_acces = $tab['moyen_acces'];
                $this->Id_type_indemnite = $tab['Id_type_indemnite'];
                $this->indemnite_destination = $tab['indemnite_destination'];
                $this->indemnite_region = $tab['indemnite_region'];
                $this->indemnite_type_deplacement = $tab['indemnite_type_deplacement'];

                if (!empty($tab['indemnite'])) {
                    foreach ($tab['indemnite'] as $i) {
                        $this->indemnite[] = array('id' => $i, 'plafond' => $tab['plafond' . $i]);
                    }
                }
                $this->commentaire_indemnite = $tab['commentaire_indemnite'];
                $this->commentaire_astreinte = $tab['commentaire_astreinte'];
                $this->nature_mission = $tab['nature_mission'];
                $this->type_mission = $tab['type_mission'];
				$this->itinerant = $tab['itinerant'];

                /*  Sous Traitant  */
                $this->st_nom = htmlscperso(stripslashes($tab['st_nom']), ENT_QUOTES);
                $this->st_prenom = htmlscperso(stripslashes($tab['st_prenom']), ENT_QUOTES);
                $this->st_societe = htmlscperso(stripslashes($tab['st_societe']), ENT_QUOTES);
                $this->st_adresse = htmlscperso(stripslashes($tab['st_adresse']), ENT_QUOTES);
                $this->st_siret = htmlscperso(stripslashes($tab['st_siret']), ENT_QUOTES);
                $this->st_ape = htmlscperso(stripslashes($tab['st_ape']), ENT_QUOTES);
                $this->st_tarif = htmlscperso(stripslashes($tab['st_tarif']), ENT_QUOTES);
                $this->st_commentaire = $tab['st_commentaire'];
                $this->archive = $tab['archive'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM contrat_delegation WHERE Id_contrat_delegation =' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_contrat_delegation = $code;
                $this->Id_duplication = $ligne->id_duplication;
                $this->Id_prop_ress = $ligne->id_prop_ress;
                $this->createur = $ligne->createur;
                $this->date_creation = $ligne->date_creation;
                $this->date_modification = $ligne->date_modification;
                $this->date_envoi = $ligne->date_envoi;
                $this->statut = $ligne->statut;
                $this->Id_cause_refus = $ligne->id_refus_cd;
                $this->commentaire_refus = $ligne->commentaire_refus;
                $this->Id_cause_reouverture = $ligne->id_reouverture_cd;
                $this->commentaire_reouverture = $ligne->commentaire_reouverture;
                $this->Id_affaire = $ligne->id_affaire;
                $this->source = $ligne->source;
                $this->adresse_facturation = $ligne->adresse_facturation;
                $this->contact_principal = $ligne->contact_principal;
                $this->fonction_cprincipal = $ligne->fonction_cprincipal;
                $this->cout_journalier = $ligne->cout_journalier;
                $this->type_mission = $ligne->type_mission;
                $this->debut_mission = FormatageDate($ligne->debut_mission);
                $this->fin_mission = FormatageDate($ligne->fin_mission);
                $this->duree_mission = $ligne->duree_mission;
                $this->lieu_mission = $ligne->lieu_mission;
                $this->tache = $ligne->tache;
                $this->indemnites_ref = $ligne->indemnites_ref;
                $this->Id_ressource = $ligne->id_ressource;
                $this->type_ressource = $ligne->type_ressource;
                $this->salaire = $ligne->salaire;
                $this->materiel = $ligne->materiel;
                $this->horaire = $ligne->horaire;
                $this->commentaire_horaire = $ligne->commentaire_horaire;
                $this->moyen_acces = $ligne->moyen_acces;
                $this->Id_type_indemnite = $ligne->id_type_indemnite;
                $this->indemnite_destination = $ligne->indemnite_destination;
                $this->indemnite_region = $ligne->indemnite_region;
                $this->indemnite_type_deplacement = $ligne->indemnite_type_deplacement;
                $this->commentaire_indemnite = $ligne->commentaire_indemnite;
                $this->astreinte = $ligne->astreinte;
                $this->commentaire_astreinte = $ligne->commentaire_astreinte;
                $this->nature_mission = $ligne->nature_mission;
                $this->remplacement = $ligne->remplacement;
                $this->st_nom = $ligne->st_nom;
                $this->st_prenom = $ligne->st_prenom;
                $this->st_societe = $ligne->st_societe;
                $this->st_adresse = $ligne->st_adresse;
                $this->st_siret = $ligne->st_siret;
                $this->st_ape = $ligne->st_ape;
                $this->st_tarif = $ligne->st_tarif;
                $this->st_commentaire = $ligne->st_commentaire;
                $this->archive = $ligne->archive;
                $this->Id_compte = $ligne->id_compte;
                $this->compte = $ligne->compte;
                $this->mode_reglement = $ligne->mode_reglement;
                $this->contact1 = $ligne->contact1;
                $this->contact2 = $ligne->contact2;
                $this->commercial = $ligne->commercial;
                $this->apporteur = $ligne->apporteur;
                $this->sdm = $ligne->sdm;
                $this->agence = $ligne->agence;
                $this->pole = $ligne->pole;
                $this->type_contrat = $ligne->type_contrat;
                $this->type_infogerance = $ligne->type_infogerance;
                $this->type = $ligne->type;
                $this->intitule = $ligne->intitule;
                $this->ca_affaire = $ligne->ca_affaire;
                $this->reference_affaire = $ligne->reference_affaire;
                $this->reference_affaire_mere = $ligne->reference_affaire_mere;
                $this->reference_devis = $ligne->reference_devis;
                $this->reference_bdc = $ligne->reference_bdc;
                $this->Id_cegid = $ligne->id_cegid;
                $this->nom_ressource = $ligne->nom_ressource;
                $this->prenom_ressource = $ligne->prenom_ressource;
                $this->embauche_staff = $ligne->embauche_staff;
                $this->Id_devis = $ligne->id_devis;
                $this->code_otp = $ligne->code_otp;
                $this->code_produit = $ligne->code_produit;
                $this->agence_mission = $ligne->agence_mission;
                $this->Id_compte_final = $ligne->id_compte_final;
                $this->compte_final = $ligne->compte_final;
                $this->politique_securite_demandee = $ligne->politique_securite_demandee;
                $this->necessite_plan_prevention = $ligne->necessite_plan_prevention;
                $this->equipement_securite_a_prevoir = $ligne->equipement_securite_a_prevoir;
                $this->mission_implique_isolement = $ligne->mission_implique_isolement;
                $this->formations_specifiques_exigees = $ligne->formations_specifiques_exigees;
                $this->poste_implique_habilitation = $ligne->poste_implique_habilitation;
                $this->presence_politique_client = $ligne->presence_politique_client;
                $this->documents_Proservia_specifiques = $ligne->documents_proservia_specifiques;
                $this->smr = $ligne->smr;
				$this->itinerant = $ligne->itinerant;

                $result = $db->query('SELECT cdi.Id_indemnite, cdi.plafond, i.type, i.nom, i.condition FROM cd_indemnite cdi
                                      INNER JOIN indemnite i ON cdi.Id_indemnite = i.Id_indemnite
                                      WHERE Id_contrat_delegation=' . mysql_real_escape_string((int) $code));
                $this->indemnite = array();
                while ($ligne = $result->fetchRow()) {
                    $this->indemnite[] = array('id' => $ligne->id_indemnite, 'nom' => $ligne->nom, 'condition' => $ligne->condition, 'type' => $ligne->type, 'plafond' => $ligne->plafond);
                }

                $result = $db->query('SELECT frais_journalier, cout_journalier FROM proposition_ressource WHERE Id_prop_ress = ' . mysql_real_escape_string((int) $this->Id_prop_ress));
                while ($ligne = $result->fetchRow()) {
                    $this->frais_journalier_ress = $ligne->frais_journalier;
                    $this->cout_journalier_ress = $ligne->cout_journalier;
                }
            }
            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_contrat_delegation = $code;
                $this->date_modification = DATETIME;
                $this->statut = 'A';
                $this->frais_journalier_ress = htmlscperso(stripslashes($tab['frais_journalier_ress']), ENT_QUOTES);
                ;
                $this->cout_journalier_ress = htmlscperso(stripslashes($tab['cout_journalier_ress']), ENT_QUOTES);
                ;

                /* Info Générales */
                $this->Id_affaire = $tab['Id_affaire'];
                $this->Id_devis = $tab['Id_devis'];
                $this->source = $tab['source'];
                $this->Id_prop_ress = $tab['Id_prop_ress'];
                $this->adresse_facturation = htmlscperso(stripslashes($tab['adresse_facturation']), ENT_QUOTES);
                $this->contact_principal = htmlscperso(stripslashes($tab['contact_principal']), ENT_QUOTES);
                $this->fonction_cprincipal = htmlscperso(stripslashes($tab['fonction_cprincipal']), ENT_QUOTES);
                $this->Id_compte = htmlscperso(stripslashes($tab['Id_compte']), ENT_QUOTES);
                $this->compte = htmlscperso(stripslashes($tab['compte']), ENT_QUOTES);
                $this->mode_reglement = htmlscperso(stripslashes($tab['mode_reglement']), ENT_QUOTES);
                $this->contact1 = htmlscperso(stripslashes($tab['contact1']), ENT_QUOTES);
                $this->contact2 = htmlscperso(stripslashes($tab['contact2']), ENT_QUOTES);
                $this->commercial = htmlscperso(stripslashes($tab['commercial']), ENT_QUOTES);
                $this->apporteur = htmlscperso(stripslashes($tab['apporteur']), ENT_QUOTES);
                $this->sdm = htmlscperso(stripslashes($tab['sdm']), ENT_QUOTES);
                $this->agence = htmlscperso(stripslashes($tab['agence']), ENT_QUOTES);
                $this->pole = htmlscperso(stripslashes($tab['pole']), ENT_QUOTES);
                $this->type_contrat = htmlscperso(stripslashes($tab['type_contrat']), ENT_QUOTES);
                $this->type_infogerance = htmlscperso(stripslashes($tab['type_infogerance']), ENT_QUOTES);
                $this->type = htmlscperso(stripslashes($tab['type']), ENT_QUOTES);
                $this->intitule = htmlscperso(stripslashes($tab['intitule']), ENT_QUOTES);
                $this->ca_affaire = htmlscperso(stripslashes($tab['ca_affaire']), ENT_QUOTES);
                $this->reference_affaire = htmlscperso(stripslashes($tab['reference_affaire']), ENT_QUOTES);
                $this->reference_affaire_mere = htmlscperso(stripslashes($tab['reference_affaire_mere']), ENT_QUOTES);
                $this->reference_devis = htmlscperso(stripslashes($tab['reference_devis']), ENT_QUOTES);
                $this->reference_bdc = htmlscperso(stripslashes($tab['reference_bdc']), ENT_QUOTES);
                $this->Id_cegid = htmlscperso(stripslashes($tab['Id_cegid']), ENT_QUOTES);
                $this->cout_journalier = htmlscperso(stripslashes($tab['cout_journalier']), ENT_QUOTES);
                $this->type_mission = $tab['type_mission'];
                $this->debut_mission = $tab['debut_mission'];
                $this->fin_mission = $tab['fin_mission'];
                $this->duree_mission = $tab['duree'];
                $this->lieu_mission = $tab['lieu_mission'];
                $this->tache = $tab['tache'];
                $this->indemnites_ref = $tab['indemnites_ref'];
                $this->Id_ressource = $tab['Id_ressource'];
                $this->type_ressource = $tab['type_ressource'];
                $this->salaire = $tab['salaire'];
                $this->materiel = $tab['materiel'];
                $this->nom_ressource = htmlscperso(stripslashes($tab['nom_ressource']));
                $this->prenom_ressource = htmlscperso(stripslashes($tab['prenom_ressource']));
                $this->embauche_staff = htmlscperso(stripslashes($tab['embauche_staff']));

                $this->code_otp = htmlscperso(stripslashes($tab['code_otp']));
                $this->code_produit = htmlscperso(stripslashes($tab['code_produit']));
                $this->agence_mission = htmlscperso(stripslashes($tab['agence_mission']));
                $this->Id_compte_final = htmlscperso(stripslashes($tab['Id_compte_final']));
                $this->compte_final = htmlscperso(stripslashes($tab['compte_final']));
                
                $this->politique_securite_demandee = $tab['politique_securite_demandee'];
                $this->necessite_plan_prevention = htmlscperso(stripslashes($tab['necessite_plan_prevention']));
                $this->equipement_securite_a_prevoir = htmlscperso(stripslashes($tab['equipement_securite_a_prevoir']));
                $this->mission_implique_isolement = htmlscperso(stripslashes($tab['mission_implique_isolement']));
                $this->formations_specifiques_exigees = htmlscperso(stripslashes($tab['formations_specifiques_exigees']));
                $this->poste_implique_habilitation = htmlscperso(stripslashes($tab['poste_implique_habilitation']));
                $this->presence_politique_client = htmlscperso(stripslashes($tab['presence_politique_client']));
                $this->documents_Proservia_specifiques = htmlscperso(stripslashes($tab['documents_Proservia_specifiques']));
                $this->smr = htmlscperso(stripslashes($tab['smr']));
                
                /* Poste du collaborateur  */
                $this->horaire = $tab['horaire'];
                $this->commentaire_horaire = $tab['commentaire_horaire'];
                $this->moyen_acces = $tab['moyen_acces'];
                $this->Id_type_indemnite = $tab['Id_type_indemnite'];
                $this->indemnite_destination = $tab['indemnite_destination'];
                $this->indemnite_region = $tab['indemnite_region'];
                $this->indemnite_type_deplacement = $tab['indemnite_type_deplacement'];
                if (!empty($tab['indemnite'])) {
                    foreach ($tab['indemnite'] as $i) {
                        $this->indemnite[] = array('id' => $i, 'plafond' => $tab['plafond' . $i]);
                    }
                }
                $this->commentaire_indemnite = $tab['commentaire_indemnite'];
                $this->astreinte = $tab['astreinte'];
                $this->commentaire_astreinte = $tab['commentaire_astreinte'];
                /*  Mission  */
                $this->nature_mission = $tab['nature_mission'];
                $this->remplacement = $tab['remplacement'];
				$this->itinerant = $tab['itinerant'];

                /*  Sous Traitant  */
                $this->st_nom = htmlscperso(stripslashes($tab['st_nom']), ENT_QUOTES);
                $this->st_prenom = htmlscperso(stripslashes($tab['st_prenom']), ENT_QUOTES);
                $this->st_societe = htmlscperso(stripslashes($tab['st_societe']), ENT_QUOTES);
                $this->st_adresse = htmlscperso(stripslashes($tab['st_adresse']), ENT_QUOTES);
                $this->st_siret = htmlscperso(stripslashes($tab['st_siret']), ENT_QUOTES);
                $this->st_ape = htmlscperso(stripslashes($tab['st_ape']), ENT_QUOTES);
                $this->st_tarif = htmlscperso(stripslashes($tab['st_tarif']), ENT_QUOTES);
                $this->st_commentaire = $tab['st_commentaire'];
                $this->archive = $tab['archive'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un contrat délégation
     *
     * @return string
     */
    public function form() {
        // Vérification si opportunité AGC ou Salesforce
		// Si AGC
        if (is_numeric($this->Id_affaire)) {
            $affaire = new Affaire($this->Id_affaire, array());
            $this->pole = Pole::getLibelle($affaire->Id_pole);
            $this->type_contrat = TypeContrat::getLibelle($affaire->Id_type_contrat);
            $this->agence = Agence::getLibelle($affaire->Id_agence);
            $this->intitule = Intitule::getLibelle($affaire->Id_intitule);
            $this->ca_affaire = Proposition::getCa(Affaire::lastProposition($this->Id_affaire));
            $type_affaire['CG'] = "selected='selected'";
            $con1 = ContactFactory::create(null, $affaire->Id_contact1);
            $this->contact1 = $con1->nom . ' ' . $con1->prenom;
            $con2 = ContactFactory::create(null, $affaire->Id_contact2);
            $this->contact2 = $con2->nom . ' ' . $con2->prenom;
            
            $compte = CompteFactory::create(null, $affaire->Id_compte);
            $this->compte = $compte->nom;
            if (!$this->contact_principal) {
                $this->contact_principal = $compte->getContactPrincipal();
            }
            if (!$this->fonction_cprincipal) {
                $this->fonction_cprincipal = $compte->getFonctionContactPrincipal();
            }
            $description = new Description($affaire->Id_description, array());
            if (!$this->tache) {
                if ($description->resume) {
                    $this->tache = strip_tags(htmlenperso_decode($description->resume));
                }
            }
            $check_type_mission[$this->type_mission] = 'checked="checked"';
            $type_mission = '
                <label><input type="radio" name="type_mission" value="Régie" ' . $check_type_mission['Régie'] . ' /> Régie</label><br />
                <label><input type="radio" name="type_mission" value="Forfait" ' . $check_type_mission['Forfait'] . ' /> Forfait</label><br />
                <label><input type="radio" name="type_mission" value="Préembauche" ' . $check_type_mission['Préembauche'] . ' /> Préembauche</label>'; 
        // Si SLF
		} else {
			// On récupère l'opportunité
            $affaire = new Opportunite($this->Id_affaire, array());
			
            $dates = $affaire->getDates();
            
            $this->Id_compte_final = $affaire->Id_compte_final;
            $this->compte_final = $affaire->nomCompteFinal;
			// Récupértion du compte client final
            $clientFinal = CompteFactory::create('SF', $this->Id_compte_final);
            
            // Champs communs à tous les types d'opportunités SFC
            $this->agence = $affaire->Id_agence;
            $this->pole = $affaire->Id_pole;
            $this->type_contrat = $affaire->Id_type_contrat;
            $contactPrincipal = $affaire->getContactPrincipal();
            if (!$this->contact_principal) {
                $this->contact_principal = $contactPrincipal[0];
            }
            if (!$this->fonction_cprincipal) {
                $this->fonction_cprincipal = $contactPrincipal[1];
            }
            // Champs concernant la politique de sécurité client
            $this->politique_securite_demandee = $affaire->politique_securite_demandee;
            $this->necessite_plan_prevention = $affaire->necessite_plan_prevention;
            $this->equipement_securite_a_prevoir = $affaire->equipement_securite_a_prevoir;
            $this->mission_implique_isolement = $affaire->mission_implique_isolement;
            $this->formations_specifiques_exigees = $affaire->formations_specifiques_exigees;
            $this->poste_implique_habilitation = $affaire->poste_implique_habilitation;
            $this->presence_politique_client = $affaire->presence_politique_client;
            $this->documents_Proservia_specifiques = $affaire->documents_Proservia_specifiques;
            $this->smr = $affaire->smr;
            
            $version = new Version(370);
            
            //Si pas de devis renseigné, on cherche le devis synchro, sinon on continue sans devis
            if(($affaire->Id_type_contrat == 'Assistance Technique Proservia' || $affaire->Id_type_contrat == 'Assistance Technique') && !$affaire->debloquer_devis && $affaire->date_creation >= $version->date_version) {
                $this->Id_devis = $affaire->Id_devis;
            }
            if ($this->Id_devis != '' && $this->Id_devis != null) {
                $devis = new Devis($this->Id_devis, null);
                $profil = '
                    <select id="profil" name="profil" onChange="updateProfilInformation(this)">
                        <option value="">' . PROFIL_SELECT . '</option>
                        <option value="">----------------------------</option>
                        ' . $devis->getProfilList() . '
                    </select><span id="desc"></span>';
                $this->reference_devis = $devis->reference_devis;
                $this->intitule = $devis->nom;
                $this->Id_compte = $devis->Id_compte;
                $compte = CompteFactory::create('SF', $devis->Id_compte);
                $this->compte = $compte->nom;
                $this->Id_cegid = $compte->Id_cegid;
                if (!$this->adresse_facturation) {
					$this->adresse_facturation = $compte->getAdresseFacturation();
                }
                $con1 = ContactFactory::create(null, $devis->Id_contact);
                $this->contact1 = $con1->nom . ' ' . $con1->prenom;
                $this->mode_reglement = $devis->condition_reglement;
                $this->ca_affaire = $devis->ca;
                if($this->statut !== 'R') {
                    $this->debut_mission = FormatageDate($devis->date_debut);
                    $this->fin_mission = FormatageDate($devis->date_fin_commande);
                }
                // Lieux de prestation du compte client
                $optionsCompte = $compte->getListLieuxPrestation($this->lieu_mission);
                // Lieux de prestations du compte client final
                $optionsClientFinal = $clientFinal->getListLieuxPrestation($this->lieu_mission);
                // Lieux de prestation
                $lieuxPresta = '
                    <select id="lieuxPresta" name="lieuxPresta" onChange="updateLieuMission(this)">
                        <option value="">Sélectionner un site</option>
                        ' . $optionsCompte . '
                        ' . $optionsClientFinal . '
                    </select>'; 
                if($this->lieu_mission == '')
                    $this->lieu_mission = $devis->adresse_fac . ' ' . ' ' . $devis->code_postal_fac . ' ' . $devis->ville_fac . ' ' . $devis->pays_fac;
                $this->duree_mission = $devis->duree;
                if (!$this->tache) {
                    $this->tache = $devis->description;
                }
                if (!$this->commentaire_horaire) {
                    $this->commentaire_horaire = $devis->couverture_horaire;
                }
                $sous_titre = 'DEVIS N° : ' . $devis->reference_devis;
                if($this->type_mission === null)
                    $check_type_mission['Régie'] = 'checked="checked"';
                else
                    $check_type_mission[$this->type_mission] = 'checked="checked"';
                $type_mission = '
                            <label><input type="radio" name="type_mission" value="Régie" ' . $check_type_mission['Régie'] . ' /> Régie</label><br />
                            <label><input type="radio" name="type_mission" value="Forfait" ' . $check_type_mission['Forfait'] . ' /> Forfait</label><br />
                            <label><input type="radio" name="type_mission" value="Préembauche" ' . $check_type_mission['Préembauche'] . ' /> Préembauche</label>'; 
                $this->code_otp = $devis->code_otp;
            }
            else {
                $check_type_mission[$this->type_mission] = 'checked="checked"';
                if($this->type_contrat == 'Infogérance Proservia' || $this->type_contrat == 'Projet Proservia') {
                    $type_inf = 'Type d\'infogérance : ' . $affaire->type_infogerance . ' <input type="hidden" name="type_infogerance" id="type_infogerance" value="' . $affaire->type_infogerance . '"/><br /><br />';
                    if($this->type_mission === null)
                        $check_type_mission['Forfait'] = 'checked="checked"';
                    $type_mission = '
                        <label><input type="radio" name="type_mission" value="Forfait" ' . $check_type_mission['Forfait'] . ' /> Forfait</label>';
                }
                else {
                    if($this->type_mission === null)
                        $check_type_mission['Régie'] = 'checked="checked"';
                    else
                        $check_type_mission[$this->type_mission] = 'checked="checked"';
                    $type_mission = '
                        <label><input type="radio" name="type_mission" value="Régie" ' . $check_type_mission['Régie'] . ' /> Régie</label><br />
                        <label><input type="radio" name="type_mission" value="Forfait" ' . $check_type_mission['Forfait'] . ' /> Forfait</label><br />
                        <label><input type="radio" name="type_mission" value="Préembauche" ' . $check_type_mission['Préembauche'] . ' /> Préembauche</label>'; 
                }
                $profil = '
                    <select id="profil" name="profil" onChange="updateProfilInformation(this)">
                        <option value="">' . PROFIL_SELECT . '</option>
                        <option value="">----------------------------</option>
                        ' . $affaire->getProfilList() . '
                    </select><span id="desc"></span>';                        
                        
                $this->intitule = $affaire->Id_intitule;
                $this->Id_compte = $affaire->Id_compte;
                $compte = CompteFactory::create('SF', $affaire->Id_compte);
                $this->compte = $compte->nom;
                $this->Id_cegid = $compte->Id_cegid;
                if (!$this->adresse_facturation) {
                    $this->adresse_facturation = $compte->getAdresseFacturation();
                }
				
				// Récupértion du compte client final
                $clientFinal = CompteFactory::create('SF', $this->Id_compte_final);
  
                $con1 = ContactFactory::create(null, $affaire->Id_contact1);
                $this->contact1 = $con1->nom . ' ' . $con1->prenom;
                $con2 = ContactFactory::create(null, $affaire->Id_contact2);
                $this->contact2 = $con2->nom . ' ' . $con2->prenom;
                $this->mode_reglement = $affaire->condition_reglement;
                $this->ca_affaire = $affaire->ca;
                /*if($this->statut !== 'R') {
                    $this->debut_mission = FormatageDate($devis->date_debut);
                    $this->fin_mission = FormatageDate($devis->date_fin_commande);
                }*/
                $this->duree_mission = $affaire->duree;
				
                // Lieux de prestation du compte client
                $optionsCompte = $compte->getListLieuxPrestation($this->lieu_mission);
                // Lieux de prestations du compte client final
                $optionsClientFinal = $clientFinal->getListLieuxPrestation($this->lieu_mission);
                // Lieux de prestation
                $lieuxPresta = '
                    <select id="lieuxPresta" name="lieuxPresta" onChange="updateLieuMission(this)">
                        <option value="">Sélectionner un site</option>
                        ' . $optionsCompte . '
                        ' . $optionsClientFinal . '
                    </select>';
					
                if (!$this->tache) {
                    $this->tache = $affaire->description;
                }
            }
        }

        // Champs commun à tous les types d'opportunités
        $this->apporteur = formatPrenomNom($affaire->apporteur);
        
        $this->type_infogerance = $affaire->type_infogerance;
        $this->type = $affaire->type;
        $this->reference_affaire = $affaire->reference_affaire;
        $this->reference_affaire_mere = $affaire->reference_affaire_mere;
        $this->reference_bdc = $affaire->Id_bdc;
        $type_affaire['SF'] = "selected='selected'";
        
        if($this->nature_mission === null) {
            if($affaire->type == 'New Business') {
                $cd['Nouvelle mission'] = 'checked="checked"';
            }
            else if($affaire->type == 'Renouvellement') {
                $cd['Renouvellement'] = 'checked="checked"';
            }
        }
        else {
            $cd[$this->nature_mission] = 'checked="checked"';
        }
        
        $c = new Utilisateur($affaire->commercial, array());
        $this->commercial = $c->prenom . ' ' . $c->nom;
        
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        $ressourceList = $ressource->getList();

        $check_indemnites_ref[$this->indemnites_ref] = 'selected="selected"';
        $cd[$this->type_mission] = 'checked="checked"';
        $re[$this->remplacement] = 'checked="checked"';
        if (!$this->materiel) {
            $this->materiel = 0;
        }
        if ($ressource->type_ressource == 'MAT')
            $this->materiel = 1;
        $mat[$this->materiel] = 'checked="checked"';
        $cd[$this->moyen_acces] = 'selected="selected"';
        $horaire[$this->horaire] = 'selected="selected"';
        $type_ressource[$this->type_ressource] = 'selected="selected"';
        $astreinte[$this->astreinte] = 'checked="checked"';
        
		// Itinérant
        $iti[$this->itinerant] = 'checked="checked"';
        		
        $version = new Version(355);
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) > $version->date_version || !$this->Id_contrat_delegation) && $_SESSION['societe'] != 'OVIALIS') {
            $type_indemnite = Indemnite::getTypeList($this->Id_contrat_delegation);
            $Ind = '<script>afficherIndemnites(2);</script>';
        }
        else {
            $moyen_acces = '<span class="infoFormulaire"> * </span> Moyen d\'accès utilisé :
                            <select name="moyen_acces" id="moyen_acces">
                                <option value="">--------------------</option>
                                <option value="Personnel" ' . $cd['Personnel'] . '>Personnel</option>
                                <option value="Véhicule Groupe Proservia" ' . $cd['Véhicule Groupe Proservia'] . '>Véhicule Groupe Proservia</option>
                                <option value="Location" ' . $cd['Location'] . '>Location</option>
                                <option value="Train" ' . $cd['Train'] . '>Train</option>
                                <option value="Avion" ' . $cd['Avion'] . '>Avion</option>
                                <option value="Transport En Commun" ' . $cd['Transport En Commun'] . '>Transport En Commun</option>
                            </select>
                            <br /><br />';
            $type_indemnite .= '
                <select name="Id_type_indemnite" id="type_indemnite" onchange="afficherIndemnites(1)">
                    <option value="0">Aucune</option>
                    ' . Indemnite::getOldTypeList($this->Id_contrat_delegation) . '
                </select>';
            $Ind = '<script>afficherIndemnites(1);</script>';
            $com_ind = '<br /><br />
                Commentaires sur les indemnités : <br /><br />
                <textarea name="commentaire_indemnite" id="tinyarea4" rows="8" cols="50">' . $this->commentaire_indemnite . '</textarea>';
        }
        
        if ($this->Id_contrat_delegation) {
            $dupliquer = '<button type="button" class="button add" style="width: 8em;" value="Dupliquer" onclick="if (confirm(\'Le contrat sera dupliqué sur la même opportunité ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;redirect=1&amp;Id=' . $this->Id_contrat_delegation . '\')}">Dupliquer</button>';    
        }
        if ($affaire->sdm) {
            $sdm = 'Service Delivery Manager : ' . Utilisateur::getName($affaire->sdm) . ' <input type="hidden" name="sdm" id="sdm" value="' . $this->sdm . '"/><br /><br />';
        }
        if (is_null($cd->statut) || $cd->statut == 'A') {
            $statut = '<b style="font-size: small;">Statut : En attente d\'envoi aux services ADV et paie</b><br /><br />';
        }
        if ($this->Id_prop_ress) {
            $cout = '<span id="frais_journalier_span">Frais journalier : <input type="text" id="frais_journalier_ress" name="frais_journalier_ress" value="' . $this->frais_journalier_ress . '" size="5" /><br /><br /></span>';
            $cout .= '<span id="cout_journalier_span">Coût journalier : </span><span id="coutJCD"><input type="text" id="cout_journalier_ress" name="cout_journalier_ress" value="' . $this->cout_journalier_ress . '" size="5" /></span>';
        }
        if($this->politique_securite_demandee == 1) {
            $politique_secu = '<br /><br />
                                <fieldset>
                                    <legend id="politiqueSecu">POLITIQUE SECURITE</legend><br />
                                    Politique de sécurité demandée : ' . (($this->politique_securite_demandee == 1) ? 'Oui' : 'Non') . '<input type="hidden" id="politique_securite_demandee" name="politique_securite_demandee" value="' . $this->politique_securite_demandee . '" /><br /><br />
                                    Nécessite un plan de prévention : ' . $this->necessite_plan_prevention . '<input type="hidden" id="necessite_plan_prevention" name="necessite_plan_prevention" value="' . $this->necessite_plan_prevention . '" /><br /><br />
                                    Equipement de sécurité à prévoir : ' . $this->equipement_securite_a_prevoir . '<input type="hidden" id="equipement_securite_a_prevoir" name="equipement_securite_a_prevoir" value="' . $this->equipement_securite_a_prevoir . '" /><br /><br />
                                    Mission implique l\'isolement travailleur : ' . (($this->mission_implique_isolement == 1) ? 'Oui' : 'Non') . '<input type="hidden" id="mission_implique_isolement" name="mission_implique_isolement" value="' . $this->mission_implique_isolement . '" /><br /><br />
                                    Suivi Médical Renforcé à prévoir : ' . $this->smr . '<input type="hidden" id="smr" name="smr" value="' . $this->smr . '" /><br /><br />
                                    Formations spécifiques exigées : ' . $this->formations_specifiques_exigees . '<input type="hidden" id="formations_specifiques_exigees" name="formations_specifiques_exigees" value="' . $this->formations_specifiques_exigees . '" /><br /><br />
                                    Le poste implique une habilitation : ' . $this->poste_implique_habilitation . '<input type="hidden" id="poste_implique_habilitation" name="poste_implique_habilitation" value="' . $this->poste_implique_habilitation . '" /><br /><br />
                                    Présence politique ou documents sécurité clients : ' . $this->presence_politique_client . '<input type="hidden" id="presence_politique_client" name="presence_politique_client" value="' . $this->presence_politique_client . '" /><br /><br />
                                    Documents Proservia spécifiques : ' . $this->documents_Proservia_specifiques . '<input type="hidden" id="documents_Proservia_specifiques" name="documents_Proservia_specifiques" value="' . $this->documents_Proservia_specifiques . '" />
                                </fieldset>';
        }
                
        $html = '
        <form name="formulaire" enctype="multipart/form-data" action="../com/index.php?a=enregistrerContratDelegation" method="post">
			<div id="cd">
			    <div class="submit">
  			        <input type="hidden" name="Id" id="Id_cd" value="' . (int) $this->Id_contrat_delegation . '" />
  			        <input type="hidden" name="Id_compte" id="Id_compte" value="' . $this->Id_compte . '" />
                                <input type="hidden" name="Id_compte_final" id="Id_compte_final" value="' . $this->Id_compte_final . '" />
                                <input type="hidden" name="reference_affaire" id="reference_affaire" value="' . (int) $this->reference_affaire . '" />
                                <input type="hidden" name="reference_affaire_mere" id="reference_affaire_mere" value="' . (int) $this->reference_affaire_mere . '" />
                                <input type="hidden" name="reference_devis" id="reference_devis" value="' . $this->reference_devis . '" />
                                <input type="hidden" name="reference_bdc" id="reference_bdc" value="' . $this->reference_bdc . '" />
                                <input type="hidden" name="date_min" id="date_min" value="' . $dates['date_min'] . '" />
                                <input type="hidden" name="date_max" id="date_max" value="' . $dates['date_max'] . '" />
                                <input type="hidden" name="Id_prop_ress" id="Id_prop_ress" value="' . (int) $this->Id_prop_ress . '" />
                                <input type="hidden" name="Id_affaire" value="' . $this->Id_affaire . '" />
                                <input type="hidden" name="Id_proposition" id="Id_prop" value="' . $affaire->proposition[0] . '" />
                                <button class="button save" style="width: 10em;"  type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCD(this.form)">' . SAVE_BUTTON . '</button>
                                ' . $dupliquer . '
		        </div>
			    <div class="left">
                                <b style="font-size: small;"><span id="sous_titre">' . $sous_titre . '</span></b><br /><br />
                                <span id="statut_text">' . $statut . '</span>
			        <fieldset>
				        <legend>INFORMATIONS GENERALES</legend><br />
                                        <input type="hidden" name="Id_cegid" id="Id_cegid" value="' . $this->Id_cegid . '"/>
				        Commercial : ' . $this->commercial . ' <input type="hidden" name="commercial" id="commercial" value="' . $this->commercial . '"/><br /><br />
                                        Apporteur d\'affaires : ' . $this->apporteur . ' <input type="hidden" name="apporteur" id="apporteur" value="' . $this->apporteur . '"/><br /><br />
				        ' . $sdm . '
				        Agence : ' . $this->agence . ' <input type="hidden" name="agence" id="agence" value="' . $this->agence . '"/><br /><br />
				        Pôle : ' . $this->pole . ' <input type="hidden" name="pole" id="pole" value="' . $this->pole . '"/><br /><br />
				        Type de contrat : ' . $this->type_contrat . ' <input type="hidden" name="type_contrat" id="type_contrat" value="' . $this->type_contrat . '"/><br /><br />
				        ' . $type_inf . '
                                        Type : ' . $this->type . ' <input type="hidden" name="type" id="type" value="' . $this->type . '"/><br /><br />
                                        Intitule : ' . $this->intitule . ' <input type="hidden" name="intitule" id="intitule" value="' . $this->intitule . '"/><br /><br />
                                        Client : ' . $this->compte . ' <input type="hidden" name="compte" id="compte" value="' . $this->compte . '"/><br /><br />
                                        Client final : ' . $this->compte_final . ' <input type="hidden" name="compte_final" id="compte_final" value="' . $this->compte_final . '"/><br /><br />
			            <span class="infoFormulaire"> * </span> Adresse de Facturation : 
				        <input type="text" id="adresse_facturation" name="adresse_facturation" value="' . $this->adresse_facturation . '" size="50" />
				        <br /><br />
				        Contact client 1 : ' . $this->contact1 . ' <input type="hidden" name="contact1" id="contact1" value="' . $this->contact1 . '"/><br /><br />
  			            Contact client 2 : ' . $this->contact2 . ' <input type="hidden" name="contact2" id="contact2" value="' . $this->contact2 . '"/><br /><br />
				        <span class="infoFormulaire"> * </span> Nom de la personne habilitée à signer les contrats : 
				        <input type="text" id="contact_principal" name="contact_principal" value="' . $this->contact_principal . '" />
				        <br /><br />
			            <span class="infoFormulaire"> * </span> Fonction :
				        <input type="text" id="fonction_cprincipal" name="fonction_cprincipal" value="' . $this->fonction_cprincipal . '" />
			            <br /><br />
				        Condition de règlement :
			            ' . $this->mode_reglement . ' <input type="hidden" name="mode_reglement" id="mode_reglement" value="' . $this->mode_reglement . '"/>
				        <br /><br />
                                        CA Opportunité :
                                        ' . number_format($this->ca_affaire, 0, ',', ' ') . '&euro; <input type="hidden" name="ca_affaire" id="ca_affaire" value="' . $this->ca_affaire . '"/>';
        $html .= $cout;
        $proposition = new Proposition(Affaire::lastProposition($this->Id_affaire), array());
        $html .= $proposition->consultation($affaire->Id_type_contrat, $affaire->Id_pole, false);

        $html .= '
            <span id="infoGen">
                <br /><br />
                <span class="infoFormulaire"> * </span> Type mission :<br />
                ' . $type_mission . '
                <br /><br />
                <span class="infoFormulaire"> * </span> Indemnités à refacturer :
                <select name="indemnites_ref">
                    <option value="" ' . $check_indemnites_ref[''] . '></option>
                    <option value="Oui" ' . $check_indemnites_ref['Oui'] . '>Oui</option>
                    <option value="Non" ' . $check_indemnites_ref['Non'] . '>Non</option>
                </select>
                <br /><br />
                Code OTP :
                    <input type="text" id="code_otp" name="code_otp" value="' . $this->code_otp . '" />
                        <br />
            </span>
                    </fieldset><br /><br />
                    <fieldset>
                        <legend>MISSION</legend><br />
                        <span id="profilSpan">' . $profil . '</span><br /><br />
                        <span class="infoFormulaire"> * </span> <span id="montantFac">Montant HT Facturé / jour et par intervenant : </span>
                        <input type="text" id="cout_journalier" name="cout_journalier" size="4" value="' . $this->cout_journalier . '" /> &euro;<br /><br />
                        <span id="infoMission">
                            <span class="infoFormulaire"> * </span> Nouvelle mission <input type="radio" name="nature_mission" value="Nouvelle mission" ' . $cd['Nouvelle mission'] . ' />
                            Renouvellement <input type="radio" name="nature_mission" value="Renouvellement" ' . $cd['Renouvellement'] . ' />
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Remplacement : Oui <input type="radio" name="remplacement" value="1" ' . $re['1'] . ' />
                            Non <input type="radio" name="remplacement" value="0" ' . $re['0'] . ' />
                            <br /><br />	
                        </span>
                        <span class="infoFormulaire"> * </span> Date de début de mission :  <input type="text" id="date_debut" name="debut_mission" value="' . $this->debut_mission . '" size="8" /> <a href="javascript:NewCssCal(\'date_debut\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a>
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Date de fin de mission :  <input type="text" id="date_fin_commande" name="fin_mission" value="' . $this->fin_mission . '" size="8" /> <a href="javascript:NewCssCal(\'date_fin_commande\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a>
                            <br /><br />
                        <span class="infoFormulaire"> * </span> Durée estimée de la mission : <span id="duree_planning"><input type="text" id="duree" name="duree" value="' . $this->duree_mission . '" size="2" /></span> (jours)
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Lieu de Prestation : ' . $lieuxPresta . ' <input type="text" id="lieu_mission" name="lieu_mission" value="' . $this->lieu_mission . '" size="40" />
                            <br /><br />
							<span class="infoFormulaire"> * </span> Itinérant : 
                            Oui <input onClick="showIndemnityBox()" type="radio" name="itinerant" value="1" ' . $iti['1'] . ' />
                            Non <input onClick="showIndemnityBox()" type="radio" name="itinerant" value="0" ' . $iti['0'] . ' />
                            <br /><br />
                            Agence de la mission : <span id="agence_mission_lbl">' . $this->agence_mission . '</span><input type="hidden" id="agence_mission" name="agence_mission" value="' . $this->agence_mission . '"/>
                            <br /><br />
                            Code produit (GCM) : <input type="text" id="code_produit" name="code_produit" value="' . $this->code_produit . '" size="40" />
                    <br /></fieldset><br /><br />
			        <fieldset>
				        <legend>RESSOURCES</legend><br />
					    <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_RESOURCE_CD . '</div>\', FULLHTML);" onmouseout="return nd();"></img>
			            <span class="infoFormulaire"> * </span> Matériel :
					    Oui <input type="radio" name="materiel" value="1" ' . $mat['1'] . ' onchange="cdHideInput();" />
				        Non <input type="radio" name="materiel" value="0" ' . $mat['0'] . ' onchange="cdHideInput();" />
				        <br /><br />
	                    <span class="infoFormulaire"> * </span>
                            <select id="type_ressource" name="type_ressource" onchange="updateResourceListByType(\'' . $this->Id_ressource . '\')">
                                <option value="">' . TYPE_RESSOURCE_SELECT . '</option>
                                <option value="">-------------------------</option>
                                <option value="MAT" ' . $type_ressource['MAT'] . '>Matériel</option>
                                <option value="CAN_TAL" ' . $type_ressource['CAN_TAL'] . '>Candidats embauchés (Taleo)</option>
                                <option value="SAL" ' . $type_ressource['SAL'] . '>Salariés</option>
                                <option value="ST" ' . $type_ressource['ST'] . ' ' . $type_ressource['ST '] . '>Sous-traitants</option>
                                <option value="INT" ' . $type_ressource['INT'] . '>Intérimaires</option>
                            </select>
                            <select id="Id_ressource" name="Id_ressource" onchange="coutJCD();infoRessourceCD();">
                                <option value="">' . RESSOURCE_SELECT . '</option>
                                <option value="">-------------------------</option>
                                ' . $ressourceList . '
                            </select>
				        <br /><br />
				    <div id="infoRessource">&nbsp;</div>
				    <br /></fieldset>
			    </div>
                            <div class="right">
			        <fieldset>
				        <legend id="posteCollab">POSTE DU COLLABORATEUR</legend><br />
				        <span id="legendPosteCollab">Définition des tâches (concernant l\'ODM) :</span> <br />
				        <textarea name="tache" id="tinyarea2" rows="8" cols="50">' . $this->tache . '</textarea>
			            <span id="infoPoste">
                                        <br /><br />
				        <span class="infoFormulaire"> * </span>
				        Horaires :
				        <select id="horaire" name="horaire">
				            <option value="">--------------------</option>
				            <option value="35 h" ' . $horaire['35 h'] . '>35 h</option>
                                            <option value="35.5 h" ' . $horaire['35.5 h'] . '>35.5 h</option>
                                            <option value="36 h" ' . $horaire['36 h'] . '>36 h</option>
                                            <option value="36.5 h" ' . $horaire['36.5 h'] . '>36.5 h</option>
                                            <option value="37 h" ' . $horaire['37 h'] . '>37 h</option>
                                            <option value="37.5 h" ' . $horaire['37.5 h'] . '>37.5 h</option>
                                            <option value="38 h" ' . $horaire['38 h'] . '>38 h</option>
                                            <option value="38.5 h" ' . $horaire['38.5 h'] . '>38.5 h</option>
                                            <option value="39 h" ' . $horaire['39 h'] . '>39 h</option>
                                            <option value="39.5 h" ' . $horaire['39.5 h'] . '>39.5 h</option>
                                            <option value="40 h" ' . $horaire['40 h'] . '>40 h</option>
                                            <option value="Autre" ' . $horaire['Autre'] . '>Autre</option>
				        </select>
				        <br /><br />
				        Commentaires (sera indiqué sur l\'Ordre de Mission) : <br /><br />
                        <textarea name="commentaire_horaire" id="tinyarea3" rows="8" cols="50">' . $this->commentaire_horaire . '</textarea><br /><br />
				        ' . $moyen_acces . '
                                        <span class="infoFormulaire"> * </span> Indemnités : 
				        ' . $type_indemnite . '
				        <div id="indemnites">
				            ' . $Ind . '
				        </div>
                                        ' . $com_ind . '
				        <br /><br />
				        <span class="infoFormulaire"> * </span> Astreinte :
				        ' . YES . ' <input type="radio" name="astreinte" value="Oui" ' . $astreinte['Oui'] . ' />
				        ' . NO . ' <input type="radio" name="astreinte" value="Non" ' . $astreinte['Non'] . ' />
				        <br /><br />
				        Commentaires sur l\'astreinte : <br /><br />
                                        <textarea name="commentaire_astreinte" id="tinyarea5" rows="8" cols="50">' . $this->commentaire_astreinte . '</textarea><br /><br />
                                        </span>
				    </fieldset>
                               ' . $politique_secu . '
			    </div>
			    <div class="submit">
				    <button class="button save" style="width: 10em;"  type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCD(this.form)">' . SAVE_BUTTON . '</button>
                                    <script type="text/javascript">
                                        infoRessourceCD();
                                        cdHideInput();
                                    </script>
		        </div>
			</div>	
		</form>
';
        return $html;
    }

    /**
     * Formulaire de création / modification d'un contrat délégation
     *
     * @return string
     */
    public function createForm() {
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        $description = new Description($affaire->Id_description, array());
        $ressourceList = $ressource->getList();

        $cd[$this->indemnites_ref] = 'checked="checked"';
        $cd[$this->type_mission] = 'checked="checked"';
        $cd[$this->nature_mission] = 'checked="checked"';
        $re[$this->remplacement] = 'checked="checked"';

        if (!$this->materiel) {
            $this->materiel = 0;
        }
        if ($ressource->type_ressource == 'MAT')
            $this->materiel = 1;
        $mat[$this->materiel] = 'checked="checked"';
        $cd[$this->moyen_acces] = 'selected="selected"';
        $horaire[$this->horaire] = 'selected="selected"';
        $type_ressource[$ressource->type_ressource] = 'selected="selected"';
        $astreinte[$this->astreinte] = 'checked="checked"';
		
		$lieuxPresta = '
                    <select id="lieuxPresta" name="lieuxPresta" onChange="updateLieuMission(this)">
                        <option value="">Sélectionner un site</option>
                    </select>';
        
        $version = new Version(355);
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) > $version->date_version || !$this->Id_contrat_delegation) && $_SESSION['societe'] != 'OVIALIS') {
            $type_indemnite = Indemnite::getTypeList($this->Id_contrat_delegation);
            $Ind = '<script>afficherIndemnites(2);</script>';
        }
        else {
            $moyen_acces = '<span class="infoFormulaire"> * </span> Moyen d\'accès utilisé :
                            <select name="moyen_acces" id="moyen_acces">
                                <option value="">--------------------</option>
                                <option value="Personnel" ' . $cd['Personnel'] . '>Personnel</option>
                                <option value="Véhicule Groupe Proservia" ' . $cd['Véhicule Groupe Proservia'] . '>Véhicule Groupe Proservia</option>
                                <option value="Location" ' . $cd['Location'] . '>Location</option>
                                <option value="Train" ' . $cd['Train'] . '>Train</option>
                                <option value="Avion" ' . $cd['Avion'] . '>Avion</option>
                                <option value="Transport En Commun" ' . $cd['Transport En Commun'] . '>Transport En Commun</option>
                            </select>
                            <br /><br />';
            $type_indemnite .= '
                <select name="Id_type_indemnite" id="type_indemnite" onchange="afficherIndemnites(1)">
                    <option value="0">Aucune</option>
                    ' . Indemnite::getOldTypeList($this->Id_contrat_delegation) . '
                </select>';
            $Ind = '<script>afficherIndemnites(1);</script>';
            $com_ind = '<br /><br />
                Commentaires sur les indemnités : <br /><br />
                <textarea name="commentaire_indemnite" id="tinyarea4" rows="8" cols="50">' . $this->commentaire_indemnite . '</textarea>';
        }
        
        
        if ($this->Id_contrat_delegation) {
            $dupliquer = '<button type="button" class="button add" style="width: 8em;" value="Dupliquer" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $this->Id_contrat_delegation . '\')}">Dupliquer</button>';
        }
        if (!$this->tache) {
            if ($description->resume) {
                $this->tache = strip_tags(htmlenperso_decode($description->resume));
            }
        }
        if ($affaire->sdm) {
            $sdm = 'Service Delivery Manager : ' . Utilisateur::getName($affaire->sdm) . ' <input type="hidden" name="sdm" id="sdm" value="' . $this->sdm . '"/><br /><br />';
        }
        if (is_null($cd->statut) || $cd->statut == 'A') {
            $statut = '<b style="font-size: small;">Statut : En attente d\'envoi aux services ADV et paie</b><br /><br />';
        }
        if ($this->Id_prop_ress) {
            $cout = '<span id="frais_journalier_span">Frais journalier : <input type="text" id="frais_journalier_ress" name="frais_journalier_ress" value="' . $this->frais_journalier_ress . '" size="5" /><br /><br /></span>';
            $cout .= '<span id="cout_journalier_span">Coût journalier : </span><span id="coutJCD"><input type="text" id="cout_journalier_ress" name="cout_journalier_ress" value="' . $this->cout_journalier_ress . '" size="5" /></span>';
        }
		
		// Itinérant
        $iti[$this->itinerant] = 'checked="checked"';
		
        $html = '
        <form name="formulaire" enctype="multipart/form-data" action="../com/index.php?a=enregistrerContratDelegation" method="post">
			<div id="cd">
			    <div class="submit">
  			        <input type="hidden" name="Id" id="Id_cd" value="' . (int) $this->Id_contrat_delegation . '" />
                                <input type="hidden" name="reference_affaire" id="reference_affaire" value="' . (int) $this->reference_affaire . '" />
                                <input type="hidden" name="reference_affaire_mere" id="reference_affaire_mere" value="' . (int) $this->reference_affaire_mere . '" />
                                <input type="hidden" name="reference_devis" id="reference_devis" value="' . $this->reference_devis . '" />
                                <input type="hidden" name="reference_bdc" id="reference_bdc" value="' . $this->reference_bdc . '" />
                                <input type="hidden" name="date_min" id="date_min" />
                                <input type="hidden" name="date_max" id="date_max" />
                                <input type="hidden" name="Id_prop_ress" id="Id_prop_ress" value="' . (int) $this->Id_prop_ress . '" />
                                <input type="hidden" name="Id_affaire" value="' . $this->Id_affaire . '" />
                                <input type="hidden" name="Id_proposition" id="Id_prop" value="' . $affaire->proposition[0] . '" />
                                <button class="button save" style="width: 10em;"  type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCD(this.form)">' . SAVE_BUTTON . '</button>
                                ' . $dupliquer . '
		        </div>
			    <div class="left">
                                <b style="font-size: small;"><span id="titre"></span></b><br /><br />
                                <span id="sous_titre"></span><br /><br />
                                <span id="statut_text">' . $statut . '</span>
                                <fieldset>
                                <legend>OPPORTUNITE</legend><br />';
        if (is_numeric($this->Id_affaire)) {
            $type['agc'] = 'selected="selected"';
        } else {
            $type['sfc'] = 'selected="selected"';
        }
        //if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 4, 5))) {
            $html .= '
                    Source : <select id="opType" name="opType" onChange="displayLieuxPrestation();prefixCompteCD();">
                        <option value="">Type d\'affaire</option>
                        <option value="">----------------------------</option>
                        <option value="agc" ' . $type['agc'] . '>AGC</option>
                        <option value="sfc" ' . $type['sfc'] . '>Salesforce</option>
                    </select>
                    <br /><br />';
        /*} else {
            $html .= '<input type="hidden" id="opType" name="opType" value="sfc"/>';
        }*/
        if($this->type_contrat == 'Infogérance Proservia' || $this->type_contrat == 'Projet Proservia')
            $type_inf = 'Type d\'infogérance : ' . $this->type_infogerance . ' <input type="hidden" name="type_infogerance" id="type_infogerance" value="' . $this->type_infogerance . '"/><br /><br />';
        $html .= '<img src="' . IMG_HELP_OVER . '" alt="" class="helpOrb" onmouseover="return overlib(\'<div class=commentaire>Vous pouvez entrer un nom de compte ou un numéro d\\\'opportunité</div>\', FULLHTML);" onmouseout="return nd();" />
                <span class="infoFormulaire"> * </span>Compte : <input id="prefix" type="text" size="4" onkeyup="prefixCompteCD(1)">
                  <span id="compte">
                    <select id="Id_compte" name="Id_compte" onchange="showCaseList(this.value, 1);">
                        <option value="">' . CUSTOMERS_SELECT . '</option>
                        <option value="">-------------------------</option>
                    </select>
                  </span><br /><br />';
        $html .= '<div id="affaire"><span class="infoFormulaire"> * </span>Opportunité : 
                    <select name="Id_affaire" id="Id_affaire" onchange="updateCaseInformation(this.value);">
                        <option value="">Sélectionner une opportunité</option>
                        <option value="">-------------------------</option>
                    </select>
                    </div>
                    <script>prefixCompteCD(1);</script>';
        $html .= '</fieldset><br /><br />
                    <fieldset id="infoOpp">
                        <legend>INFORMATIONS GENERALES</legend><br />
                        Sélectionner une opportunité pour mettre à jour cette partie.
                        <span id="infoGen">
                        </span>
                        <br />
                    </fieldset><br /><br />
                    <fieldset>
                        <legend>MISSION</legend><br />
                        <span id="profilSpan">
                            <select id="profil" name="profil" onChange="updateProfilInformation(this)">
                                <option value="">' . PROFIL_SELECT . '</option>
                                <option value="">----------------------------</option>
                                    ' . $profilList . '
                            </select>
                        </span>
                        <br /><br />
                        <span class="infoFormulaire"> * </span> <span id="montantFac">Montant HT Facturé / jour et par intervenant : </span>
                        <input type="text" id="cout_journalier" name="cout_journalier" size="4" value="' . $this->cout_journalier . '" /> &euro;<br /><br />
                        <span id="infoMission">
                            <span class="infoFormulaire"> * </span> Nouvelle mission <input type="radio" name="nature_mission" value="Nouvelle mission" ' . $cd['Nouvelle mission'] . ' />
                            Renouvellement <input type="radio" name="nature_mission" value="Renouvellement" ' . $cd['Renouvellement'] . ' />
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Remplacement : Oui <input type="radio" name="remplacement" value="1" ' . $re['1'] . ' />
                            Non <input type="radio" name="remplacement" value="0" ' . $re['0'] . ' />
                            <br /><br />	
                        </span>
                        <span class="infoFormulaire"> * </span> Date de début de mission :  <input type="text" id="date_debut" name="debut_mission" value="' . $this->debut_mission . '" size="8" /> <a href="javascript:NewCssCal(\'date_debut\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a>
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Date de fin de mission :  <input type="text" id="date_fin_commande" name="fin_mission" value="' . $this->fin_mission . '" size="8" /> <a href="javascript:NewCssCal(\'date_fin_commande\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a>
                            <br /><br />
                        <span class="infoFormulaire"> * </span> Durée estimée de la mission : <span id="duree_planning"><input type="text" id="duree" name="duree" value="' . $this->duree_mission . '" size="2" /></span> (jours)
                            <br /><br />
                            <span class="infoFormulaire"> * </span> Lieu de Prestation : ' . $lieuxPresta . ' <input type="text" id="lieu_mission" name="lieu_mission" value="' . $this->lieu_mission . '" size="40" />
                               <br /><br />
							   <span class="infoFormulaire"> * </span> Itinérant : 
								Oui <input onClick="showIndemnityBox()" type="radio" name="itinerant" value="1" ' . $iti['1'] . ' />
								Non <input onClick="showIndemnityBox()" type="radio" name="itinerant" value="0" ' . $iti['0'] . ' />
								<br /><br />
                               Agence de la mission : <span id="agence_mission_lbl">' . $this->agence_mission . '</span><input type="hidden" id="agence_mission" name="agence_mission" value="' . $this->agence_mission . '"/>
                               <br /><br />
                               Code produit (GCM) : <input type="text" id="code_produit" name="code_produit" value="' . $this->code_produit . '" size="40" />
                    <br /></fieldset><br /><br />
			        <fieldset>
				        <legend>RESSOURCES</legend><br />
					    <img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_RESOURCE_CD . '</div>\', FULLHTML);" onmouseout="return nd();"></img>
			            <span class="infoFormulaire"> * </span> Matériel :
					    Oui <input type="radio" name="materiel" value="1" ' . $mat['1'] . ' onchange="cdHideInput();" />
				        Non <input type="radio" name="materiel" value="0" ' . $mat['0'] . ' onchange="cdHideInput();" />
				        <br /><br />
	                    <span class="infoFormulaire"> * </span>
                            <select id="type_ressource" name="type_ressource" onchange="updateResourceListByType(\'' . $this->Id_ressource . '\')">
                                <option value="">' . TYPE_RESSOURCE_SELECT . '</option>
                                <option value="">-------------------------</option>
                                <option value="MAT" ' . $type_ressource['MAT'] . '>Matériel</option>
                                <option value="CAN_AGC" ' . $type_ressource['CAN_AGC'] . '>Candidats embauchés (AGC)</option>
                                <option value="CAN_TAL" ' . $type_ressource['CAN_TAL'] . '>Candidats embauchés (Taleo)</option>
                                <option value="SAL" ' . $type_ressource['SAL'] . '>Salariés</option>
                                <option value="ST" ' . $type_ressource['ST'] . ' ' . $type_ressource['ST '] . '>Sous-traitants</option>
                                <option value="INT" ' . $type_ressource['INT'] . '>Intérimaires</option>
                            </select>
                            <select id="Id_ressource" name="Id_ressource" onchange="coutJCD();infoRessourceCD();">
                                <option value="">' . RESSOURCE_SELECT . '</option>
                                <option value="">-------------------------</option>
                                ' . $ressourceList . '
                            </select>
				        <br /><br />
				    <div id="infoRessource">&nbsp;</div>
				    <script>infoRessourceCD();</script>
				    <br /></fieldset>
			    </div>
                <div class="right">
			        <fieldset>
				        <legend id="posteCollab">POSTE DU COLLABORATEUR</legend><br />
				        <span id="legendPosteCollab">Définition des tâches (concernant l\'ODM) :</span> <br />
				        <textarea name="tache" id="tinyarea2" rows="8" cols="50">' . $this->tache . '</textarea>
			            <span id="infoPoste">
                                        <br /><br />
				        <span class="infoFormulaire"> * </span>
				        Horaires :
				        <select id="horaire" name="horaire">
				            <option value="">--------------------</option>
				            <option value="35 h" ' . $horaire['35 h'] . '>35 h</option>
                                            <option value="35.5 h" ' . $horaire['35.5 h'] . '>35.5 h</option>
                                            <option value="36 h" ' . $horaire['36 h'] . '>36 h</option>
                                            <option value="36.5 h" ' . $horaire['36.5 h'] . '>36.5 h</option>
                                            <option value="37 h" ' . $horaire['37 h'] . '>37 h</option>
                                            <option value="37.5 h" ' . $horaire['37.5 h'] . '>37.5 h</option>
                                            <option value="38 h" ' . $horaire['38 h'] . '>38 h</option>
                                            <option value="38.5 h" ' . $horaire['38.5 h'] . '>38.5 h</option>
                                            <option value="39 h" ' . $horaire['39 h'] . '>39 h</option>
                                            <option value="39.5 h" ' . $horaire['39.5 h'] . '>39.5 h</option>
                                            <option value="40 h" ' . $horaire['40 h'] . '>40 h</option>
                                            <option value="Autre" ' . $horaire['Autre'] . '>Autre</option>
				        </select>
				        <br /><br />
				        Commentaires (sera indiqué sur l\'Ordre de Mission) : <br /><br />
                        <textarea name="commentaire_horaire" id="tinyarea3" rows="8" cols="50">' . $this->commentaire_horaire . '</textarea><br /><br />
                                        ' . $moyen_acces . '
				        <span class="infoFormulaire"> * </span> Indemnités : 
				        ' . $type_indemnite . '
				        <br /><br />
				        <div id="indemnites">
				            ' . $Ind . '
				        </div>
                                        ' . $com_ind . '
				        <br />
				        <span class="infoFormulaire"> * </span> Astreinte :
				        ' . YES . ' <input type="radio" name="astreinte" value="Oui" ' . $astreinte['Oui'] . ' />
				        ' . NO . ' <input type="radio" name="astreinte" value="Non" ' . $astreinte['Non'] . ' />
				        <br /><br />
				        Commentaires sur l\'astreinte : <br /><br />
                        <textarea name="commentaire_astreinte" id="tinyarea5" rows="8" cols="50">' . $this->commentaire_astreinte . '</textarea>
                                        </span>
				    </fieldset>
                                    <span id="politique_securite">
                                        
                                    </span>
			    </div>
			    <div class="submit">
				    <button class="button save" style="width: 10em;"  type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCD(this.form)">' . SAVE_BUTTON . '</button>
                                    <script type="text/javascript">
                                        cdHideInput();
                                    </script>
		        </div>
			</div>	
		</form>
';
        return $html;
    }

    /**
     * Formulaire de création / modification d'un contrat délégation hors affaire
     *
     * @return string
     */
    public function withoutCaseForm() {
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        $ressourceList = $ressource->getList();
        
        $cd[$this->moyen_acces] = 'selected="selected"';
        $horaire[$this->horaire] = 'selected="selected"';
        $type_ressource[$this->type_ressource] = 'selected="selected"';
        $version = new Version(355);
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) > $version->date_version || !$this->Id_contrat_delegation) && $_SESSION['societe'] != 'OVIALIS') {
            $type_indemnite = Indemnite::getTypeList($this->Id_contrat_delegation);
            $Ind = '<script>afficherIndemnites(2);</script>';
        }
        else {
            $moyen_acces = '<span class="infoFormulaire"> * </span> Moyen d\'accès utilisé :
                            <select name="moyen_acces" id="moyen_acces">
                                <option value="">--------------------</option>
                                <option value="Personnel" ' . $cd['Personnel'] . '>Personnel</option>
                                <option value="Véhicule Groupe Proservia" ' . $cd['Véhicule Groupe Proservia'] . '>Véhicule Groupe Proservia</option>
                                <option value="Location" ' . $cd['Location'] . '>Location</option>
                                <option value="Train" ' . $cd['Train'] . '>Train</option>
                                <option value="Avion" ' . $cd['Avion'] . '>Avion</option>
                                <option value="Transport En Commun" ' . $cd['Transport En Commun'] . '>Transport En Commun</option>
                            </select>
                            <br /><br />';
            $type_indemnite .= '
                <select name="Id_type_indemnite" id="type_indemnite" onchange="afficherIndemnites(1)">
                    <option value="0">Aucune</option>
                    ' . Indemnite::getOldTypeList($this->Id_contrat_delegation) . '
                </select>';
            $Ind = '<script>afficherIndemnites(1);</script>';
            $com_ind = '<br />
                Commentaires sur les indemnités : <br /><br />
                <textarea name="commentaire_indemnite" id="tinyarea4" rows="8" cols="50">' . $this->commentaire_indemnite . '</textarea>';
        }
        $astreinte[$this->astreinte] = 'checked="checked"';
        if ($this->Id_contrat_delegation) {
            $dupliquer = '<input type="button" value="Dupliquer" onclick="if (confirm(\'Voulez-vous dupliquer le contrat délégation ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $this->Id_contrat_delegation . '\')}" />';
        }
        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        if ($utilisateur->getResourceRight(1, 8)) {
            $staff = '<option value="CAN_STA" ' . $type_ressource['CAN_STA'] . '>Candidats embauchés STAFF (Taleo)</option>';
        }
        if (is_null($cd->statut) || $cd->statut == 'A') {
            $statut = '<b style="font-size: small;">Statut : En attente d\'envoi aux services ADV et paie</b><br /><br />';
        }
        $html = '
        <form name="formulaire" enctype="multipart/form-data" action="../com/index.php?a=enregistrerContratDelegationHorsAffaire" method="post">
			<div class="submit">
			    <input type="hidden" name="Id" id="Id_cd" value="' . (int) $this->Id_contrat_delegation . '" />
				<input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCDHA(this.form)" />
				' . $dupliquer . '
		    </div>
                    <span id="statut_text">' . $statut . '</span>
            <div class="left">
				<h2>COLLABORATEUR</h2><br />
	                <span class="infoFormulaire"> * </span>
                        <select id="type_ressource" name="type_ressource" onchange="updateResourceListByType(\'' . $this->Id_ressource . '\')">
                            <option value="">' . TYPE_RESSOURCE_SELECT . '</option>
                            <option value="">-------------------------</option>
                            <option value="MAT" ' . $type_ressource['MAT'] . '>Matériel</option>
                            <option value="CAN_AGC" ' . $type_ressource['CAN_AGC'] . '>Candidats embauchés (AGC)</option>
                            <option value="CAN_TAL" ' . $type_ressource['CAN_TAL'] . '>Candidats embauchés (Taleo)</option>
                            ' . $staff . '
                            <option value="SAL" ' . $type_ressource['SAL'] . '>Salariés</option>
                            <option value="ST" ' . $type_ressource['ST'] . '>Sous-traitants</option>
                            <option value="INT" ' . $type_ressource['INT'] . '>Intérimaires</option>
                        </select>
                        <select id="Id_ressource" name="Id_ressource" onchange="coutJCD();infoRessourceCD();">
                            <option value="">' . RESSOURCE_SELECT . '</option>
                            <option value="">-------------------------</option>
                            ' . $ressourceList . '
                        </select>
				    <br /><br />
				<div id="infoRessource">&nbsp;</div>
				<script>infoRessourceCD();</script>
				<h2>POSTE DU COLLABORATEUR</h2><br />
				Définition des tâches (concernant l\'ODM) : <br />
				<textarea name="tache" id="tinyarea2" rows="8" cols="50">' . $this->tache . '</textarea>
			    <br /><br />
				Horaires :
				<select name="horaire">
				    <option value="">--------------------</option>
				    <option value="35 h" ' . $horaire['35 h'] . '>35 h</option>
                                            <option value="35.5 h" ' . $horaire['35.5 h'] . '>35.5 h</option>
                                            <option value="36 h" ' . $horaire['36 h'] . '>36 h</option>
                                            <option value="36.5 h" ' . $horaire['36.5 h'] . '>36.5 h</option>
                                            <option value="37 h" ' . $horaire['37 h'] . '>37 h</option>
                                            <option value="37.5 h" ' . $horaire['37.5 h'] . '>37.5 h</option>
                                            <option value="38 h" ' . $horaire['38 h'] . '>38 h</option>
                                            <option value="38.5 h" ' . $horaire['38.5 h'] . '>38.5 h</option>
                                            <option value="39 h" ' . $horaire['39 h'] . '>39 h</option>
                                            <option value="39.5 h" ' . $horaire['39.5 h'] . '>39.55 h</option>
                                            <option value="40 h" ' . $horaire['40 h'] . '>40 h</option>
                                            <option value="Autre" ' . $horaire['Autre'] . '>Autre</option>
				</select>
				<br /><br />
				Commentaires (sera indiqué sur l\'Ordre de Mission) : <br /><br />
                <textarea name="commentaire_horaire" id="tinyarea3" rows="8" cols="50">' . $this->commentaire_horaire . '</textarea><br /><br />
				' . $moyen_acces . '
                                <span class="infoFormulaire"> * </span> Indemnités : 
                                ' . $type_indemnite . '
                                <br /><br />
                                <div id="indemnites">
                                    ' . $Ind . '
                                </div>
                                ' . $com_ind . '
				<br /><br />
				<span class="infoFormulaire"> * </span> Astreinte :
				' . YES . ' <input type="radio" name="astreinte" value="Oui" ' . $astreinte['Oui'] . ' />
				' . NO . ' <input type="radio" name="astreinte" value="Non" ' . $astreinte['Non'] . ' />
				<br /><br />
				Commentaires sur l\'astreinte : <br /><br />
                <textarea name="commentaire_astreinte" id="tinyarea5" rows="8" cols="50">' . $this->commentaire_astreinte . '</textarea>
			</div>
			<div class="submit">
				<input type="hidden" name="Id_proposition" id="Id_prop" value="' . $affaire->proposition[0] . '" />
				<input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifCDHA(this.form)"/>
		    </div>
		</form>
';
        return $html;
    }
    
    /**
     * Formulaire pour le retour d'un CD
     *
     * @return string
     */
    public function rejectForm() {
        $content = '
            <form name="formulaire" enctype="multipart/form-data" method="post">
                <label for="">
                    Raison du retour : 
                    <select id="Id_cause_refus" name="Id_cause_refus">
                        ' . $this->getRejectCauseList() . '
                    </select>
                </label><br /><br />
                <label for="">Commentaire : <textarea id="commentaire_refus" name="commentaire_refus">' . $this->commentaire_refus . '</textarea></label><br />
            </form>
        ';
        $footer .= '<button type="button" class="button add" onclick="rejectCD(' . $this->Id_contrat_delegation . ', 2);">Retourner</button>';
        $footer .= '&nbsp;&nbsp;<button type="button" class="button delete" onclick="Modalbox.hide();">Annuler</button>';
        return json_encode(array('content' => utf8_encode($content), 'footer' => utf8_encode($footer)));
    }
    
    /**
     * Formulaire pour la ré-ouverture d'un CD
     *
     * @return string
     */
    public function reopenForm() {
        $content = '
            <form name="formulaire" enctype="multipart/form-data" method="post">
                <label for="">
                    Raison de la ré-ouverture : 
                    <select id="Id_cause_reouverture" name="Id_cause_reouverture">
                        ' . $this->getRejectCauseList() . '
                    </select>
                </label><br /><br />
                <label for="">Commentaire : <textarea id="commentaire_reouverture" name="commentaire_reouverture">' . $this->commentaire_reouverture . '</textarea></label><br />
            </form>
        ';
        $footer .= '<button type="button" class="button add" onclick="reopenCD(' . $this->Id_contrat_delegation . ', 2);">Ré-ouvrir</button>';
        $footer .= '&nbsp;&nbsp;<button type="button" class="button delete" onclick="Modalbox.hide();">Annuler</button>';
        return json_encode(array('content' => utf8_encode($content), 'footer' => utf8_encode($footer)));
    }

    /**
     * Recherche d'un contrat délégation
     *
     * @return string
     */
    public static function search($Id_cd, $Id_affaire, $createur, $Id_ressource, $agence, $statut = null, $archive, $motcle = null, $reference_affaire_mere = null, $origine = null, $output = array('type' => 'TABLE'), $finishing = false) {
        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        $arguments = array('Id_cd', 'Id_affaire', 'createur', 'Id_ressource', 'statut', 'archive', 'motcle', 'reference_affaire_mere', 'origine', 'output');
        $columns = array(array('Id cd', 'Id_contrat_delegation'), array('Id opportunité', 'reference_affaire'), array('Id opportunité mère', 'reference_affaire_mere'), array('Nom', 'intitule'),
            array('Date création', 'date_creation'), array('Créateur', 'createur'), array('Ressource', 'none'), array('Agence', 'agence', 'width:70px;'),
            array('Début', 'debut_mission'), array('Fin', 'fin_mission'), array('Durée', 'duree_mission'), array('Statut', 'statut'));
        $db = connecter();
        $requete = 'SELECT Id_contrat_delegation, Id_affaire, Id_ressource, st_nom, st_prenom,
		DATE_FORMAT(date_creation, "%d-%m-%Y") as date_creation_fr, date_creation, createur, uc.nom as uc_nom, uc.prenom as uc_prenom,
		DATE_FORMAT(debut_mission, "%d-%m-%Y") as debut_mission_fr, debut_mission, 
		DATE_FORMAT(fin_mission, "%d-%m-%Y") as fin_mission_fr, fin_mission, duree_mission,
                statut, cd.archive, reference_affaire, reference_affaire_mere, intitule, type_ressource, nom_ressource, prenom_ressource,
                embauche_staff, agence
		FROM contrat_delegation cd
        LEFT JOIN utilisateur uc ON uc.Id_utilisateur = createur
        WHERE nom_ressource <> "" ';
        if ($utilisateur->getResourceRight(1, 8) === false){ 
             $requete .= ' AND debut_mission <>  "0000-00-00" ';
        }
        if ($archive == 1) {
            $requete .= '  AND cd.archive  = 1';
        } elseif ($archive == 2) {
            $requete .= ' AND (cd.archive = 0 OR cd.archive = 1)';
        } else {
            $requete .= '  AND cd.archive = 0';
        }
        if ($Id_cd) {
            $requete .= ' AND Id_contrat_delegation= ' . (int) $Id_cd;
        }
        if ($Id_affaire) {
            $requete .= ' AND (Id_affaire="' . $Id_affaire . '" OR reference_affaire="' . $Id_affaire . '")';
        }
        if ($reference_affaire_mere) {
            $requete .= ' AND (reference_affaire_mere="' . $reference_affaire_mere . '" OR reference_affaire="' . $reference_affaire_mere . '")';
        }
        if ($createur) {
            $requete .= ' AND createur= "' . $createur . '"';
        }
        if ($Id_ressource) {
            $requete .= ' AND (Id_ressource= "' . $Id_ressource . '" OR Id_ressource_sal= "' . $Id_ressource . '")';
        }
        if ($agence) {
            $requete .= ' AND agence = "' . Agence::getLibelle($agence) . '"';
        }
        if ($statut) {
            $requete .= ' AND statut = "' . $statut . '"';
        }
        if ($utilisateur->getResourceRight($ligne['embauche_staff'], 8) === false){
            $requete .= ' AND intitule <> ""';
        }

        if ($motcle) {
            $motclesplit = explode(' ', utf8_decode($motcle));
            foreach ($motclesplit as $i) {
                $requete .= ' AND ( compte LIKE "%' . $i . '%"
				OR contact1 LIKE "%' . $i . '%"
				OR contact2 LIKE "%' . $i . '%"
                                OR intitule LIKE "%' . $i . '%")';
            }
        }

		if ($origine == 'PWS') {
            //$requete .= '  AND PSA_TRAVAILN3 IN("030", "031", "032") ';
        }

        if ($finishing && $finishing != 'null') {
            $requete .= ' AND (fin_mission > NOW()  AND fin_mission < ADDDATE(NOW(), INTERVAL 31 DAY))';
        }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=Id_contrat_delegation';
            $orderBy = 'Id_contrat_delegation';
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
                'onclick' => 'afficherCD({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);

            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterCD&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
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
                        $img['Id_contrat_delegation'] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'ASC';
                    }
                    if ($value[1] == 'none')
                        $html .= '<th style="' . $value[2] . '">' . $value[0] . '</th>';
                    else
                        $html .= '<th style="' . $value[2] . '"><a href="#" onclick="afficherCD({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;

                foreach ($paged_data['data'] as $ligne) {
                    if ($ligne['id_affaire'] == 0) {
                        if ($utilisateur->getResourceRight($ligne['embauche_staff'], 8) === true) {
                        
                        } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $ligne['createur']) {
                            
                        } else {
                            continue;
                        }
                    }

                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_contrat_delegation'] . '</td>
                            <td>' . self::showIdCase($ligne) . '</td>
                            <td>' . $ligne['reference_affaire_mere'] . '</td>
                            <td>' . self::showName($ligne) . '</td>
                            <td>' . $ligne['date_creation_fr'] . '</td>
                            <td>' . utf8_decode($ligne['uc_nom']) . ' '. utf8_decode($ligne['uc_prenom']) . '</td>
                            <td>' . self::showResource($ligne) . '</td>
                            <td>' . $ligne['agence'] . '</td>
                            <td>' . $ligne['debut_mission_fr'] . '</td>
                            <td>' . $ligne['fin_mission_fr'] . '</td>
                            <td>' . $ligne['duree_mission'] . '</td>
                            <td>' . $ligne['statut'] . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="contrats_delegation.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                if ($ligne['id_affaire'] == 0) {
                    if ($utilisateur->getResourceRight($ligne['embauche_staff'], 8) === true) {
                        
                    } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $ligne['createur']) {
                        
                    } else {
                        continue;
                    }
                }

                echo $ligne['id_contrat_delegation'] . ';';
                echo self::showIdCase($ligne) . ';';
                echo $ligne['reference_affaire_mere'] . ';';
                echo '"' . self::showName($ligne) . '";';
                echo $ligne['date_creation_fr'] . ';';
                echo '"' . utf8_decode($ligne['uc_nom']) . ' ' . utf8_decode($ligne['uc_prenom']) .  '";';
                echo '"' . self::showResource($ligne) . '";';
                echo $ligne['agence'] . ';';
                echo $ligne['debut_mission_fr'] . ';';
                echo $ligne['fin_mission_fr'] . ';';
                echo $ligne['duree_mission'] . ';';
                echo $ligne['statut'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }
    
    /**
     * Recherche d'un contrat délégation
     *
     * @return string
     */
    public static function searchExport($dateDebut = null, $dateFin = null, $output = array('type' => 'TABLE')) {
        $arguments = array('debut', 'fin', 'output');
        $columns = array(array('Id opportunité', 'reference_affaire'), array('Id cd', 'Id_contrat_delegation'), array('Id affaire Cegid', 'none'),
            array('Id compte Cegid', 'Id_cegid'), array('Libellé client', 'compte'), array('Adresse', 'none'));
        $db = connecter();
        $requete = 'SELECT Id_contrat_delegation, Id_affaire, reference_affaire, Id_cegid, compte, adresse_facturation, embauche_staff FROM contrat_delegation
                    WHERE archive = 0 ';
        if ($dateDebut) {
            $requete .= ' AND date_creation >= "' . DateMysqltoFr($dateDebut, 'mysql') . '"';
        }
        if ($dateFin) {
            $requete .= ' AND date_creation <= "' . DateMysqltoFr($dateFin, 'mysql') . '"';
        }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=Id_contrat_delegation';
            $orderBy = 'Id_contrat_delegation';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherExportCD({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);

            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=exportCD&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
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
                        $img['Id_contrat_delegation'] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'ASC';
                    }
                    if ($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherExportCD({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;

                foreach ($paged_data['data'] as $ligne) {
                    if ($ligne['id_affaire'] == 0) {
                        if ($utilisateur->getResourceRight($ligne['embauche_staff'], 8) === true) {
                            
                        } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $ligne['createur']) {
                            
                        } else {
                            continue;
                        }
                    }
                    
                    if($ligne['id_affaire'] || $ligne['reference_affaire']) {
                        if ($ligne['reference_affaire'])
                            $affaireCEGID = Affaire::getIdAffaireCEGID($ligne['reference_affaire'], '', true);
                        else
                            $affaireCEGID = Affaire::getIdAffaireCEGID($ligne['id_affaire'], '', true);
                    }
                    
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . self::showIdCase($ligne) . '</td>
                            <td>' . $ligne['id_contrat_delegation'] . '</td>
                            <td>' . $affaireCEGID . '</td>
                            <td>' . $ligne['id_cegid'] . '</td>
                            <td>' . $ligne['compte'] . '</td>
                            <td>' . $ligne['adresse_facturation'] . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="contrats_delegation.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                if ($ligne['id_affaire'] == 0) {
                    if ($utilisateur->getResourceRight($ligne['embauche_staff'], 8) === true) {
                        
                    } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $ligne['createur']) {
                        
                    } else {
                        continue;
                    }
                }

                if($ligne['id_affaire'] || $ligne['reference_affaire']) {
                    if ($ligne['reference_affaire'])
                        $affaireCEGID = Affaire::getIdAffaireCEGID($ligne['reference_affaire'], '', true);
                    else
                        $affaireCEGID = Affaire::getIdAffaireCEGID($ligne['id_affaire'], '', true);
                }
                
                echo self::showIdCase($ligne) . ';';
                echo $ligne['id_contrat_delegation'] . ';';
                echo '"' . $affaireCEGID . '";';
                echo $ligne['id_cegid'] . ';';
                echo $ligne['compte'] . ';';
                echo $ligne['adresse_facturation'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation) . ', Id_affaire = "' . mysql_real_escape_string($this->Id_affaire) . '",
		    adresse_facturation = "' . mysql_real_escape_string($this->adresse_facturation) . '", contact_principal = "' . mysql_real_escape_string($this->contact_principal) . '",
			fonction_cprincipal = "' . mysql_real_escape_string($this->fonction_cprincipal) . '", Id_ressource = "' . mysql_real_escape_string($this->Id_ressource) . '", type_ressource = "' . mysql_real_escape_string($this->type_ressource) . '",
			date_modification = "' . mysql_real_escape_string($this->date_modification) . '", cout_journalier = ' . mysql_real_escape_string((float) $this->cout_journalier) . ',
			type_mission = "' . mysql_real_escape_string($this->type_mission) . '", indemnites_ref = "' . mysql_real_escape_string($this->indemnites_ref) . '",
			nature_mission = "' . mysql_real_escape_string($this->nature_mission) . '", remplacement = ' . mysql_real_escape_string((int) $this->remplacement) . ',
			st_nom = "' . mysql_real_escape_string($this->st_nom) . '", st_prenom = "' . mysql_real_escape_string($this->st_prenom) . '", st_societe = "' . mysql_real_escape_string($this->st_societe) . '",
			st_adresse = "' . mysql_real_escape_string($this->st_adresse) . '", st_siret = "' . mysql_real_escape_string($this->st_siret) . '", st_ape = "' . mysql_real_escape_string($this->st_ape) . '",
			st_tarif = ' . (float) mysql_real_escape_string($this->st_tarif) . ', st_commentaire = "' . mysql_real_escape_string($this->st_commentaire) . '",
			horaire = "' . mysql_real_escape_string($this->horaire) . '", commentaire_horaire = "' . mysql_real_escape_string($this->commentaire_horaire) . '",
			moyen_acces = "' . mysql_real_escape_string($this->moyen_acces) . '", Id_type_indemnite = ' . mysql_real_escape_string((int) $this->Id_type_indemnite) . ',
                        indemnite_destination = "' . mysql_real_escape_string($this->indemnite_destination) . '", indemnite_region = "' . mysql_real_escape_string($this->indemnite_region) . '",
                        indemnite_type_deplacement = "' . mysql_real_escape_string($this->indemnite_type_deplacement) . '",
			commentaire_indemnite = "' . mysql_real_escape_string($this->commentaire_indemnite) . '", astreinte = "' . mysql_real_escape_string($this->astreinte) . '",
			commentaire_astreinte = "' . mysql_real_escape_string($this->commentaire_astreinte) . '", archive = ' . mysql_real_escape_string((int) $this->archive) . ',
			debut_mission = "' . mysql_real_escape_string(DateMysqltoFr($this->debut_mission, 'mysql')) . '",
			fin_mission = "' . mysql_real_escape_string(DateMysqltoFr($this->fin_mission, 'mysql')) . '",statut = "' . mysql_real_escape_string($this->statut) . '",
			duree_mission = "' . mysql_real_escape_string((int)$this->duree_mission) . '", lieu_mission = "' . mysql_real_escape_string($this->lieu_mission) . '",
			materiel="' . mysql_real_escape_string($this->materiel) . '", tache = "' . mysql_real_escape_string($this->tache) . '",
                        Id_prop_ress=' . mysql_real_escape_string((int) $this->Id_prop_ress) . ', compte="' . mysql_real_escape_string($this->compte) . '",
                        mode_reglement="' . mysql_real_escape_string($this->mode_reglement) . '", contact1="' . mysql_real_escape_string($this->contact1) . '",
                        contact2="' . mysql_real_escape_string($this->contact2) . '", commercial="' . mysql_real_escape_string($this->commercial) . '",
                        sdm="' . mysql_real_escape_string($this->sdm) . '", agence="' . mysql_real_escape_string($this->agence) . '",
                        pole="' . mysql_real_escape_string($this->pole) . '", type_contrat="' . mysql_real_escape_string($this->type_contrat) . '",
                        type_infogerance="' . mysql_real_escape_string($this->type_infogerance) . '",
                        intitule="' . mysql_real_escape_string($this->intitule) . '", ca_affaire="' . mysql_real_escape_string($this->ca_affaire) . '",
                        reference_affaire="' . mysql_real_escape_string((int) $this->reference_affaire) . '", reference_affaire_mere="' . mysql_real_escape_string((int) $this->reference_affaire_mere) . '",
                        reference_devis="' . mysql_real_escape_string($this->reference_devis) . '", reference_bdc="' . mysql_real_escape_string($this->reference_bdc) . '",
                        Id_cegid="' . mysql_real_escape_string($this->Id_cegid) . '",Id_compte="' . mysql_real_escape_string($this->Id_compte) . '",
                        type="' . mysql_real_escape_string($this->type) . '", apporteur="' . mysql_real_escape_string($this->apporteur) . '",
                        nom_ressource="' . mysql_real_escape_string($this->nom_ressource) . '", prenom_ressource="' . mysql_real_escape_string($this->prenom_ressource) . '",
                        embauche_staff="' . mysql_real_escape_string($this->embauche_staff) . '",salaire="' . mysql_real_escape_string($this->salaire) . '",
                        Id_devis="' . mysql_real_escape_string($this->Id_devis) . '",code_otp="' . mysql_real_escape_string($this->code_otp) . '",code_produit="' . mysql_real_escape_string($this->code_produit) . '",
                        agence_mission="' . mysql_real_escape_string($this->agence_mission) . '",
                        compte_final="' . mysql_real_escape_string($this->compte_final) . '",Id_compte_final="' . mysql_real_escape_string($this->Id_compte_final) . '",
                        politique_securite_demandee="' . mysql_real_escape_string($this->politique_securite_demandee) . '",necessite_plan_prevention="' . mysql_real_escape_string($this->necessite_plan_prevention) . '",
                        equipement_securite_a_prevoir="' . mysql_real_escape_string($this->equipement_securite_a_prevoir) . '",mission_implique_isolement="' . mysql_real_escape_string($this->mission_implique_isolement) . '",
                        formations_specifiques_exigees="' . mysql_real_escape_string($this->formations_specifiques_exigees) . '",poste_implique_habilitation="' . mysql_real_escape_string($this->poste_implique_habilitation) . '",
                        presence_politique_client="' . mysql_real_escape_string($this->presence_politique_client) . '",documents_Proservia_specifiques="' . mysql_real_escape_string($this->documents_Proservia_specifiques) . '",
                        smr="' . mysql_real_escape_string($this->smr) . '", itinerant="' . mysql_real_escape_string((int)$this->itinerant) . '"';

        
        if ($this->Id_contrat_delegation) {
            $requete = 'UPDATE contrat_delegation ' . $set . ' WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation);
        } else {
            $requete = 'INSERT INTO contrat_delegation ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation = "' . mysql_real_escape_string($this->date_creation) . '"';
        }
        $e = $db->query($requete);
        $_SESSION['Id_contrat_delegation'] = ($this->Id_contrat_delegation == '') ? mysql_insert_id() : (int) $this->Id_contrat_delegation;
        if ($this->Id_contrat_delegation) {
            $db->query('DELETE FROM cd_indemnite WHERE Id_contrat_delegation=' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
        }
        if (!empty($this->indemnite)) {
            foreach ($this->indemnite as $i) {
                $db->query('INSERT INTO cd_indemnite SET Id_contrat_delegation=' . mysql_real_escape_string($_SESSION['Id_contrat_delegation']) . ', Id_indemnite=' . mysql_real_escape_string((int) $i['id']) . ', plafond="' . mysql_real_escape_string((int) $i['plafond']) . '"');
            }
        }
        if ($this->Id_prop_ress) {
            Proposition::updateRessource($this->Id_prop_ress, array('Id_ressource' => $this->Id_ressource, 'cout_journalier' => $this->cout_journalier_ress, 'frais_journalier' => $this->frais_journalier_ress, 'tarif_journalier' => $this->cout_journalier, 'debut_mission' => $this->debut_mission, 'fin_mission' => $this->fin_mission, 'duree_mission' => $this->duree_mission));
        }
    }

    /**
     * Editer le contrat délégation en pdf
     */
    public function edit($stock = 0) {
        $_SESSION['titre'] = CONTRAT_DELEGATION;
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetY(30);
        $pdf->setLeftMargin(3);

        $affaire = new Affaire($this->Id_affaire, array());
        $version = new Version(355);
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) <= $version->date_version || !$this->Id_contrat_delegation)  || $_SESSION['societe'] == 'OVIALIS') {
             $htmlIndemnite .= Indemnite::getType($this->Id_type_indemnite) . ' | ';
        }
        if (!empty($this->indemnite)) {
            $j = 0;
            foreach ($this->indemnite as $i) {
                //$indemnite = new Indemnite($i['id'], array());
                $htmlIndemnite .= ($j > 0) ? ' | ' : '';
                if($i['plafond'] != 0) $plafond = '  Plafond : '. $i['plafond'] .' km';
                $htmlIndemnite .= $i['nom'] . ' ' . $plafond;
                $j++;
            }
        }
        if ($this->Id_ressource) {
            $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        }

        if ($this->salaire) {
            $salaire_mensuel = round(1000 * ($this->salaire / 12), 2);
        }

        $this->commentaire_horaire = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_horaire)));
        $this->st_commentaire = convert_smart_quotes(strip_tags(htmlenperso_decode($this->st_commentaire)));
        $this->commentaire_indemnite = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_indemnite)));
        $this->tache = convert_smart_quotes(strip_tags(htmlenperso_decode($this->tache)));
        $this->commentaire_astreinte = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_astreinte)));
        $proposition = new Proposition(Affaire::lastProposition($this->Id_affaire), array());
        $rem = convert_smart_quotes(strip_tags(htmlenperso_decode($proposition->remarque)));

        if ($this->reference_affaire)
            $affaireCEGID = Affaire::getIdAffaireCEGID($this->reference_affaire, "", true);
        else
            $affaireCEGID = Affaire::getIdAffaireCEGID($this->Id_affaire, "", true);

        $pdf->SetStyle('t1', 'arial', 'B', 10, '70,110,165');
        $pdf->SetStyle('t2', 'arial', '', 8, '0,0,0');
        $pdf->SetStyle('t3', 'arial', '', 7, '70,110,165');
        $pdf->SetStyle('t4', 'arial', '', 6, '171,64,75');
        $pdf->setXY(180, 3);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCellTag(100, 4, "[   ] Prépa. Fact \n[   ] ODM\n[   ] SF\n[   ] Affaire CEGID", 0, 'L', 0);
        $pdf->setXY(129, 12);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(72, 2, "n° {$this->Id_contrat_delegation}", 0, 'C', 0);
        $pdf->setXY(100, 17);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 7);
        if ($this->reference_affaire) {
            if (is_numeric($this->Id_affaire)) {
                $lienAff = '<t2>Opportunité  n° :</t2> <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $this->Id_affaire . '\'>' . $this->reference_affaire . '</a>';
            }
            else {
                if($this->reference_affaire_mere)
                    $lienAff = '<t2>Opportunité mère n° :</t2> ' . $this->reference_affaire_mere . '<t2> - Opportunité n° :</t2> <t3><a href=\'' . SF_URL . $this->Id_affaire . '\'>' . $this->reference_affaire . '</a></t3>';
                else
                    $lienAff = '<t2>Opportunité n° :</t2> <a href=\'' . SF_URL . $this->Id_affaire . '\'>' . $this->reference_affaire . '</a>';
            }
        }
        else if ($this->Id_affaire) {
            if (is_numeric($this->Id_affaire))
                $lienAff = '<t2>Opportunité n° :</t2> <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $this->Id_affaire . '\'>' . $this->Id_affaire . '</a>';
            else {
                if($this->reference_affaire_mere)
                    $lienAff = '<t2>Opportunité mère n° :</t2> ' . $this->Id_affaire . '<t2> - Opportunité n° :</t2> <t3><a href=\'' . SF_URL . $this->Id_affaire . '\'>' . $this->reference_affaire . '</a></t3>';
                else
                    $lienAff = '<t2>Opportunité n° :</t2> <a href=\'' . SF_URL . $this->Id_affaire . '\'>' . $this->Id_affaire . '</a>';
            }
        }
        
        $pdf->setXY(3, 30);
        $pdf->MultiCellTag(204, 5, '<t1>REFERENCES</t1>', 'B', 'L', 0);
        $pdf->setY($pdf->GetY() + 2);
        $pdf->MultiCellTag(204, 5, $lienAff, 0, 'L', 0);
        $pdf->MultiCellTag(204, 5, "<t2>Affaire(s) CEGID n° :</t2> <t3>{$affaireCEGID}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(204, 5, "<t2>Code compte CEGID :</t2> <t3>{$this->Id_cegid}</t3>", 0, 'L', 0);
        if($this->type_contrat == 'Assistance Technique Proservia' && $this->reference_devis) {
            $pdf->MultiCellTag(204, 5, "<t2>Devis n° :</t2> <t3><a href='" . SF_URL . $this->Id_affaire . "'>" . $this->reference_devis . "</a></t3>", 0, 'L', 0);
        }
        if ($this->reference_bdc != '' && $this->reference_bdc != null) {
            $pdf->MultiCellTag(204, 5, "<t2>BDC n° :</t2> <t3>{$this->reference_bdc}</t3>", 0, 'L', 0);
        }
        if ($this->code_produit != '' && $this->code_produit != null) {
            $pdf->MultiCellTag(204, 5, "<t2>Code produit (GCM) :</t2> <t3>{$this->code_produit}</t3>", 0, 'L', 0);
        }
        if ($this->code_otp != '' && $this->code_otp != null) {
            $pdf->MultiCellTag(204, 5, "<t2>Code OTP :</t2> <t3>{$this->code_otp}</t3>", 0, 'L', 0);
        }
        
        $y0 = $pdf->GetY() + 5;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->setLeftMargin(3);
        $pdf->setXY(3, $y0);
        $pdf->MultiCellTag(95, 5, '<t1>INFORMATIONS GENERALES</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        $pdf->MultiCellTag(95, 5, "<t2>Commercial :</t2> <t3>" . $this->commercial . "</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Apporteur d'affaire :</t2> <t3>" . $this->apporteur . "</t3>", 0, 'L', 0);

        if ($affaire->sdm) {
            $pdf->MultiCellTag(95, 5, "<t2>Service Delivery Manager :</t2> <t3>" . $this->sdm . "</t3>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, '<t2>Agence :</t2> <t3>' . $this->agence . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Pôle(s) :</t2> <t3>' . $this->pole . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Type de contrat :</t2> <t3>' . $this->type_contrat . '</t3>', 0, 'L', 0);
        if($this->type_contrat == 'Infogérance Proservia')
            $pdf->MultiCellTag(95, 5, '<t2>Type d\'infogérance :</t2> <t3>' . $this->type_infogerance . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Type :</t2> <t3>' . $this->type . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Intitulé :</t2> <t3>' . $this->intitule . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Client :</t2> <t3>{$this->compte}</t3>", 0, 'L', 0);
        if ($this->compte_final != '') {
            $pdf->MultiCellTag(95, 5, "<t2>Client final :</t2> <t3>{$this->compte_final}</t3>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(95, 5, "<t2>Adresse de Facturation :</t2> <t3>" . htmlscperso_decode($this->adresse_facturation, ENT_QUOTES) . "</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Contact client 1 :</t2> <t3>' . $this->contact1 . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, '<t2>Contact client 2 :</t2> <t3>' . $this->contact2 . '</t3>', 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Nom de la personne habilitée à signer les contrats :</t2> <t3>{$this->contact_principal}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Fonction :</t2> <t3>{$this->fonction_cprincipal}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Condition de règlement :</t2> <t3>{$this->mode_reglement}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Montant HT Facturé / jour et par intervenant :</t2> <t3>{$this->cout_journalier} ".chr(128)."</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>CA Affaire :</t2> <t3>{$this->ca_affaire} ".chr(128)."</t3>", 0, 'L', 0);


        if ($pdf->GetStringWidth($rem) > 1500) {
            $pdf->MultiCellTag(95, 5, "<t2>Remarque proposition :</t2> \n<t3>" . substr($rem, 0, 1500) . "...</t3>", 0, 'L', 0);
            $pdf->AddPage();
            $pdf->setLeftMargin(3);
            $pdf->setY(30);
            $pdf->MultiCellTag(95, 5, "<t2>Remarque proposition (suite) :</t2> \n<t3>" . substr($rem, 1500, 5000) . "</t3>", 0, 'L', 0);
        } else {
            $pdf->MultiCellTag(95, 5, "<t2>Remarque proposition :</t2> \n<t3>" . $rem . "</t3>", 0, 'L', 0);
        }

        if ($this->materiel == 0) {
            $pdf->MultiCellTag(95, 5, "<t2>Régie - Forfait - Préembauche :</t2> <t3>{$this->type_mission}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Indemnités à refacturer :</t2> <t3>{$this->indemnites_ref}</t3>", 0, 'L', 0);
        }
        //$pdf->MultiCellTag(95, 5, "<t2>Code produit (GCM) :</t2> <t3>{$this->code_produit}</t3>", 0, 'L', 0);
        //$pdf->MultiCellTag(95, 5, "<t2>Code OTP :</t2> <t3>{$this->code_otp}</t3>", 0, 'L', 0);
        $pdf->setY($pdf->GetY() + 1);
        $pdf->MultiCellTag(95, 5, '<t1>MISSION</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        if ($this->materiel == 0) {
            $pdf->MultiCellTag(95, 5, "<t2>Nouvelle mission ou Renouvellement :</t2> <t3>{$this->nature_mission}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Remplacement :</t2> <t3>" . yesno($this->remplacement) . "</t3>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(95, 5, "<t2>Date de début de mission :</t2> <t3>{$this->debut_mission}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Date de fin de mission :</t2> <t3>{$this->fin_mission}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Durée estimée de la mission :</t2> <t3>{$this->duree_mission} jours</t3>", 0, 'L', 0);
        if ($this->agence_mission != '' && $this->agence_mission != null) {
            $pdf->MultiCellTag(95, 5, "<t2>Agence mission :</t2> <t3>{$this->agence_mission}</t3>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(95, 5, "<t2>Lieu de Prestation :</t2> <t3>{$this->lieu_mission}</t3>", 0, 'L', 0);
        $pdf->setY($pdf->GetY() + 1);
        
        if($this->politique_securite_demandee == 1) {
            $pdf->MultiCellTag(95, 5, '<t1>POLITIQUE SECURITE</t1>', 1, 'C', 0);
            $pdf->setY($pdf->GetY() + 2);
            $pdf->MultiCellTag(95, 5, "<t2>Politique de sécurité demandée :</t2> <t3>" . (($this->politique_securite_demandee == 1) ? 'Oui' : 'Non') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Nécessite t-il un plan de prévention :</t2> <t3>{$this->necessite_plan_prevention}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Equipement de sécurité à prévoir :</t2> <t3>{$this->equipement_securite_a_prevoir}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Mission implique l'isolement travailleur :</t2> <t3>" . (($this->mission_implique_isolement == 1) ? 'Oui' : 'Non') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Suivi Médical Renforcé à prévoir :</t2> <t3>{$this->smr}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Formations spécifiques exigées :</t2> <t3>{$this->formations_specifiques_exigees}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Le poste implique une habilitation :</t2> <t3>{$this->poste_implique_habilitation}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Présence politique ou documents sécurité clients :</t2> <t3>{$this->presence_politique_client}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Documents Proservia spécifiques :</t2> <t3>{$this->documents_Proservia_specifiques}</t3>", 0, 'L', 0);
            $pdf->setY($pdf->GetY() + 3);
        }

        $pdf->setY($y0);
        $pdf->setLeftMargin(110);


        if ($this->materiel == 0) {
            $pdf->MultiCellTag(97, 5, '<t1>POSTE DU COLLABORATEUR</t1>', 1, 'C', 0);
            $pdf->setY($pdf->GetY() + 2);
            $pdf->MultiCellTag(95, 5, "<t2>Définition des tâches (concernant l'ODM) :</t2> \n  <t4>" . htmlscperso_decode($this->tache, ENT_QUOTES) . "</t4>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Horaires :</t2> <t3>{$this->horaire}</t3>", 0, 'L', 0);
            if ($this->commentaire_horaire) {
                $pdf->MultiCellTag(95, 5, "<t2>Commentaires (Détail des horaires) :</t2> \n  <t4>{$this->commentaire_horaire}</t4>", 0, 'L', 0);
            }
            $version = new Version(355);
            if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) <= $version->date_version || !$this->Id_contrat_delegation) || $_SESSION['societe'] == 'OVIALIS') {
                $pdf->MultiCellTag(95, 5, "<t2>Moyen d'accès utilisé :</t2> <t3>{$this->moyen_acces}</t3>", 0, 'L', 0);
            }
            $pdf->MultiCellTag(95, 5, "<t2>Indemnités :</t2> <t3>{$htmlIndemnite}</t3>", 0, 'L', 0);
            if ($this->commentaire_indemnite) {
                $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2>\n <t4>{$this->commentaire_indemnite}</t4>", 0, 'L', 0);
            }
            $pdf->MultiCellTag(95, 5, "<t2>Astreinte :</t2> <t3>{$this->astreinte}</t3>", 0, 'L', 0);
            if ($this->commentaire_astreinte) {
                $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2>\n <t4>{$this->commentaire_astreinte}</t4>", 0, 'L', 0);
            }
            if ($pdf->GetY() > 200) {
                $pdf->AddPage();
                $pdf->setY($y0);
            } else {
                $pdf->setY($pdf->GetY() + 3);
            }
            if ($this->type_ressource == 'CAN_TAL' || $this->type_ressource == 'CAN_AGC') {
                $typeRessource = 'Nouveau Collaborateur n°' . $ressource->getIdCandidature();
            } elseif ($this->type_ressource == 'SAL') {
                $typeRessource = 'COLLABORATEUR';
            } elseif ($this->type_ressource == 'ST') {
                $typeRessource = 'SOUS-TRAITANT';
            }
            $pdf->MultiCellTag(95, 5, '<t1>' . $typeRessource . '</t1>', 1, 'C', 0);
            $pdf->setY($pdf->GetY() + 2);
            if ($ressource->type_ressource != 'ST ' && $ressource->type_ressource != 'STG') {
				$pdf->MultiCellTag(95, 5, "<t2>Numéro de ressource :</t2> <t3>{$this->Id_ressource}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Nom :</t2> <t3>{$ressource->nom}</t3> <t2>| Nom de jeune fille :</t2> <t3>{$ressource->nom_jeune_fille}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Prénom :</t2> <t3>{$ressource->prenom}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Adresse :</t2> <t3>" . htmlscperso_decode($ressource->adresse, ENT_QUOTES) . " {$ressource->code_postal} " . htmlscperso_decode($ressource->ville, ENT_QUOTES) . "</t3>", 0, 'L', 0);

                if ($this->type_ressource == 'CAN_TAL') {
                    $service = new Service(substr($ressource->Id_service, 3), $ressource->societe);
                    $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
					$itinerant = $this->itinerant == 1 ? 'Oui' : 'Non';
                    $pdf->MultiCellTag(95, 5, "<t2>Itinérant :</t2> <t3>{$itinerant}</t3>", 0, 'L', 0);
                    if($ressource->embauche_staff == true) {
                        if ($this->salaire) {
                            $salaire = $this->salaire;
                            $salaire_mensuel = round(1000 * ($this->salaire / 12), 2);
                        }
                    }
                    else {
                        if ($ressource->salaire) {
                            $salaire = $ressource->salaire;
                            $salaire_mensuel = round(1000 * ($ressource->salaire / 12), 2);
                        }
                    }
                    $pdf->MultiCellTag(95, 5, "<t2>Etablissement :</t2> <t3>" . $ressource->agence . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Tél :</t2> <t3>" . chunk_split($ressource->tel_fixe, 2, ' ') . "</t3>  <t2>Portable :</t2> <t3>" . chunk_split($ressource->tel_portable, 2, ' ') . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Mail :</t2> <t3>{$ressource->mail}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>N° Sécurité Sociale :</t2> <t3>{$ressource->securite_sociale}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Date de naissance :</t2> <t3>{$ressource->date_naissance}</t3> <t2>| Lieu de naissance :</t2> <t3>{$ressource->ville_naissance}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Lieu de naissance :</t2> <t3>{$ressource->ville_naissance}, {$ressource->Id_dpt_naiss}, {$ressource->Id_pays_naissance}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Nationalité :</t2> <t3>{$ressource->Id_nationalite}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Etat Matrimonial :</t2> <t3>" . $ressource->Id_etat_matrimonial . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Travailleur handicapé :</t2> <t3>" . (($ressource->th) ? 'oui' : 'non') . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Date d'embauche :</t2> <t3>" . $ressource->date_embauche . "</t3>  <t2>| Heure d'embauche (h) :</t2> <t3>{$ressource->heure_embauche} (h)</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Libellé emploi :</t2> <t3>{$ressource->profil}</t3>", 0, 'L', 0);
                    if($ressource->libelle_emploi_comp != '') {
                        $pdf->MultiCellTag(95, 5, "<t2>Libellé emploi complémentaire :</t2> <t3>{$ressource->libelle_emploi_comp}</t3>", 0, 'L', 0);
                    }
                    $pdf->MultiCellTag(95, 5, "<t2>Profil :</t2> <t3>{$ressource->profil_cegid}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Statut :</t2> <t3>{$ressource->statut}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Contrat PROSERVIA :</t2> <t3>" . $ressource->Id_contrat_proservia . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Type d'embauche :</t2> <t3>{$ressource->type_embauche}</t3>", 0, 'L', 0);
                    if ($ressource->type_embauche != 'CDI' && $ressource->type_embauche != 'Contrat de professionnalisation CDI') {
                            $pdf->MultiCellTag(95, 5, "<t2>Fin contrat :</t2> <t3>" . $ressource->fin_cdd . "</t3>", 0, 'L', 0);
                    }
                    if ($ressource->type_embauche == 'CDD') {
                        $pdf->MultiCellTag(95, 5, "<t2>Motif du CDD :</t2> <t3>" . $ressource->motif_cdd . "</t3>", 0, 'L', 0);
                        if ($ressource->id_motif_cdd == 2) {
                            $pdf->MultiCellTag(95, 5, "<t2>Salarié remplacé :</t2> <t3>" . $ressource->salarie_remplace . "</t3>", 0, 'L', 0);
                        }
                    }
                    $pdf->MultiCellTag(95, 5, "<t2>Salaire annuel brut :</t2> <t3>{$salaire} K".chr(128)."</t3> <t2>| Salaire mensuel brut :</t2> <t3>{$salaire_mensuel} ".chr(128)."</t3> ", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Informations complémentaires sur le contrat :</t2> \n <t3>{$ressource->info_complementaire}</t3> ", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t3><a href='".FICHE_DEMANDE_ACCES."'>Lien vers la fiche demande accès et matériel SI</a></t3>", 0, 'L', 0);
                }
                else if ($this->type_ressource == 'CAN_AGC') {
                    $service = new Service($ressource->Id_service, $ressource->societe);
                    $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
					$itinerant = $this->itinerant == 1 ? 'Oui' : 'Non';
                    $pdf->MultiCellTag(95, 5, "<t2>Itinérant :</t2> <t3>{$itinerant}</t3>", 0, 'L', 0);
                    if ($ressource->date_naissance != '0000-00-00') {
                        $ressource->date_naissance = FormatageDate($ressource->date_naissance);
                    }
                    if ($ressource->date_naissance == '0000-00-00') {
                        $ressource->date_naissance = '';
                    }
                    if ($ressource->salaire) {
                        $salaire_mensuel = round(1000 * ($ressource->salaire / 12), 2);
                    }
                    $pdf->MultiCellTag(95, 5, "<t2>Etablissement :</t2> <t3>" . Agence::getLibelle($ressource->Id_agence) . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Tél :</t2> <t3>" . chunk_split($ressource->tel_fixe, 2, ' ') . "</t3>  <t2>Portable :</t2> <t3>" . chunk_split($ressource->tel_portable, 2, ' ') . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Mail :</t2> <t3>{$ressource->mail}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>N° Sécurité Sociale :</t2> <t3>{$ressource->securite_sociale}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Date de naissance :</t2> <t3>{$ressource->date_naissance}</t3> <t2>| Lieu de naissance :</t2> <t3>{$ressource->ville_naissance}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Nationalité :</t2> <t3>{$ressource->nationalite}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Etat Matrimonial :</t2> <t3>" . EtatMatrimonial::getLibelle($ressource->Id_etat_matrimonial) . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Travailleur handicapé :</t2> <t3>" . (($ressource->th) ? 'oui' : 'non') . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Date d'embauche :</t2> <t3>" . FormatageDate($ressource->date_embauche) . "</t3>  <t2>| Heure d'embauche (h) :</t2> <t3>{$ressource->heure_embauche} (h)</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Libellé emploi :</t2> <t3>{$ressource->profil}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Profil :</t2> <t3>{$ressource->profil_cegid}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Statut :</t2> <t3>{$ressource->statut}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Contrat PROSERVIA :</t2> <t3>" . ContratProservia::getLibelle($ressource->Id_contrat_proservia) . "</t3>", 0, 'L', 0);
					if ($ressource->type_embauche != 'CDI') {
						$pdf->MultiCellTag(95, 5, "<t2>Type d'embauche :</t2> <t3>{$ressource->type_embauche}</t3>", 0, 'L', 0);
					}
                    $pdf->MultiCellTag(95, 5, "<t2>Fin contrat :</t2> <t3>" . FormatageDate($ressource->fin_cdd) . "</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Salaire annuel brut :</t2> <t3>{$ressource->salaire} K?</t3> <t2>| Salaire mensuel brut :</t2> <t3>{$salaire_mensuel} €</t3> ", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Informations complémentaires sur le contrat :</t2> \n <t3>{$ressource->info_complementaire}</t3> ", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t3><a href='https://srvextra.proservia.lan/partage/3-DGAF/36-DSI/CIRCUIT%20ARRIVEE/DSI-%20Fiche%20demande%20acc%C3%A8s%20et%20mat%C3%A9riel%20SI.docx'>Lien vers la fiche demande accès et matériel SI</a></t3>", 0, 'L', 0);
                }
                else {
                    $service = new Service($ressource->Id_service, $ressource->societe);
                    $pdf->MultiCellTag(95, 5, "<t2>Société :</t2> <t3>{$ressource->societe}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
					$itinerant = $this->itinerant == 1 ? 'Oui' : 'Non';
                    $pdf->MultiCellTag(95, 5, "<t2>Itinérant :</t2> <t3>{$itinerant}</t3>", 0, 'L', 0);
                    $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
                }
            } elseif ($ressource->type_ressource == 'ST ' || $ressource->type_ressource == 'STG') {
                $pdf->MultiCellTag(95, 5, "<t2>Nom :</t2> <t3>{$this->st_nom}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Prénom :</t2> <t3>{$this->st_prenom}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Adresse :</t2> <t3>{$this->st_adresse}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Société :</t2> <t3>{$this->st_societe}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Code SIRET :</t2> <t3>{$this->st_siret}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Code APE :</t2> <t3>{$this->st_ape}</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Tarifs Sous-Traitant :</t2> <t3>{$this->st_tarif} (".chr(128)." / j)</t3>", 0, 'L', 0);
                $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2> <t3>{$this->st_commentaire}</t3>", 0, 'L', 0);
            }
        } else {
            $pdf->MultiCellTag(97, 5, '<t1>MATERIEL</t1>', 1, 'C', 0);
            $pdf->setY($pdf->GetY() + 2);
            if ($this->Id_ressource == 'MAT') {
                $mat = 'Matériel';
            } elseif ($this->Id_ressource == 'LIC') {
                $mat = 'Licence';
            } elseif ($this->Id_ressource == 'LOG') {
                $mat = 'Logiciel';
            }
            $pdf->MultiCellTag(95, 5, "<t2>Type :</t2> <t3>{$mat}</t3>", 0, 'L', 0);
            $pdf->setY($pdf->GetY() + 2);
            $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2> \n  <t4>{$this->tache}</t4>", 0, 'L', 0);
        }



        if (!$stock) {
            $pdf->Output();
        } else {
            $pdf->Output(URL_TMP . CD_NAME_FILE . $this->Id_contrat_delegation . '.pdf', 'F');
        }
    }

    /**
     * Editer le contrat délégation hors affaire en pdf
     */
    public function editWithoutCase($stock = 0) {
        $_SESSION['titre'] = CONTRAT_DELEGATION;
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetY(30);
        $pdf->setLeftMargin(3);

        $version = new Version(355);
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) <= $version->date_version || !$this->Id_contrat_delegation) || $_SESSION['societe'] == 'OVIALIS') {
             $htmlIndemnite .= Indemnite::getType($this->Id_type_indemnite) . ' | ';
        }
        if (!empty($this->indemnite)) {
            $j = 0;
            foreach ($this->indemnite as $i) {
                $indemnite = new Indemnite($i['id'], array());
                $htmlIndemnite .= ($j > 0) ? ' | ' : '';
                $htmlIndemnite .= $indemnite->nom;
                $j++;
            }
        }
        
        if ($this->Id_ressource) {
            $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        }
        $this->commentaire_horaire = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_horaire)));
        $this->commentaire_indemnite = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_indemnite)));
        $this->tache = convert_smart_quotes(strip_tags(htmlenperso_decode($this->tache)));
        $this->commentaire_astreinte = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_astreinte)));

        $pdf->SetStyle('t1', 'arial', 'B', 10, '70,110,165');
        $pdf->SetStyle('t2', 'arial', '', 8, '0,0,0');
        $pdf->SetStyle('t3', 'arial', '', 7, '70,110,165');
        $pdf->SetStyle('t4', 'arial', '', 6, '171,64,75');
        $pdf->setXY(129, 12);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCellTag(72, 2, "n° {$this->Id_contrat_delegation}", 0, 'C', 0);
        $y0 = $pdf->GetY() + 15;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->setLeftMargin(3);
        $pdf->setXY(3, $y0);
        $pdf->MultiCellTag(97, 5, '<t1>POSTE DU COLLABORATEUR</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);

        $pdf->MultiCellTag(95, 5, "<t2>Définition des tâches (concernant l'ODM) :</t2> \n  <t4>{$this->tache}</t4>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Horaires :</t2> <t3>{$this->horaire}</t3>", 0, 'L', 0);
        if ($this->commentaire_horaire) {
            $pdf->MultiCellTag(95, 5, "<t2>Commentaires (Détail des horaires) :</t2> \n  <t4>{$this->commentaire_horaire}</t4>", 0, 'L', 0);
        }
        if((DateTime::createFromFormat('Y-m-d H:i:s', $this->date_creation) <= $version->date_version || !$this->Id_contrat_delegation) || $_SESSION['societe'] == 'OVIALIS') {
            $pdf->MultiCellTag(95, 5, "<t2>Moyen d'accès utilisé :</t2> <t3>{$this->moyen_acces}</t3>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(95, 5, "<t2>Indemnités :</t2> <t3>{$htmlIndemnite}</t3>", 0, 'L', 0);

        if ($this->commentaire_indemnite) {
            $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2>\n <t4>{$this->commentaire_indemnite}</t4>", 0, 'L', 0);
        }

        $pdf->MultiCellTag(95, 5, "<t2>Astreinte :</t2> <t3>{$this->astreinte}</t3>", 0, 'L', 0);

        if ($this->commentaire_astreinte) {
            $pdf->MultiCellTag(95, 5, "<t2>Commentaires :</t2>\n <t4>{$this->commentaire_astreinte}</t4>", 0, 'L', 0);
        }
        if ($pdf->GetY() > 200) {
            $pdf->AddPage();
            $pdf->setY($y0);
        } else {
            $pdf->setY($pdf->GetY() + 3);
        }

        if (is_numeric($ressource->Id_ressource)) {
            $typeRessource = 'Nouveau Collaborateur n°' . $ressource->getIdCandidature($ressource->Id_ressource);
        } elseif ($ressource->type_ressource == 'SAL') {
            $typeRessource = 'COLLABORATEUR';
        }

        $pdf->MultiCellTag(95, 5, '<t1>' . $typeRessource . '</t1>', 1, 'C', 0);
        $pdf->setY($pdf->GetY() + 2);
        $pdf->MultiCellTag(95, 5, "<t2>Nom :</t2> <t3>{$ressource->nom}</t3> <t2>| Nom de jeune fille :</t2> <t3>{$ressource->nom_jeune_fille}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Prénom :</t2> <t3>{$ressource->prenom}</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(95, 5, "<t2>Adresse :</t2> <t3>{$ressource->adresse} {$ressource->code_postal} {$ressource->ville}</t3>", 0, 'L', 0);

        if ($this->type_ressource == 'CAN_TAL' || $this->type_ressource == 'CAN_STA' ) {
            $service = new Service(substr($ressource->Id_service, 3), $ressource->societe);
            $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
            if($ressource->embauche_staff == true) {
                if ($this->salaire) {
                    $salaire = $this->salaire;
                    $salaire_mensuel = round(1000 * ($this->salaire / 12), 2);
                }
            }
            else {
                if ($ressource->salaire) {
                    $salaire = $ressource->salaire;
                    $salaire_mensuel = round(1000 * ($ressource->salaire / 12), 2);
                }
            }
            $pdf->MultiCellTag(95, 5, "<t2>Etablissement :</t2> <t3>" . $ressource->agence . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Tél :</t2> <t3>" . chunk_split($ressource->tel_fixe, 2, ' ') . "</t3>  <t2>Portable :</t2> <t3>" . chunk_split($ressource->tel_portable, 2, ' ') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Mail :</t2> <t3>{$ressource->mail}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>N° Sécurité Sociale :</t2> <t3>{$ressource->securite_sociale}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Date de naissance :</t2> <t3>{$ressource->date_naissance}</t3> <t2>| Lieu de naissance :</t2> <t3>{$ressource->ville_naissance}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Nationalité :</t2> <t3>{$ressource->Id_nationalite}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Etat Matrimonial :</t2> <t3>" . $ressource->Id_etat_matrimonial . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Travailleur handicapé :</t2> <t3>" . (($ressource->th) ? 'oui' : 'non') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Date d'embauche :</t2> <t3>" . $ressource->date_embauche . "</t3>  <t2>| Heure d'embauche (h) :</t2> <t3>{$ressource->heure_embauche} (h)</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Libellé emploi :</t2> <t3>{$ressource->profil}</t3>", 0, 'L', 0);
            if($ressource->libelle_emploi_comp != '') {
				$pdf->MultiCellTag(95, 5, "<t2>Libellé emploi complémentaire :</t2> <t3>{$ressource->libelle_emploi_comp}</t3>", 0, 'L', 0);
			}
            $pdf->MultiCellTag(95, 5, "<t2>Profil :</t2> <t3>{$ressource->profil_cegid}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Statut :</t2> <t3>{$ressource->statut}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Contrat PROSERVIA :</t2> <t3>" . $ressource->Id_contrat_proservia . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Type d'embauche :</t2> <t3>{$ressource->type_embauche}</t3>", 0, 'L', 0);
            if ($ressource->type_embauche != 'CDI' && $ressource->type_embauche != 'Contrat de professionnalisation CDI') {
                    $pdf->MultiCellTag(95, 5, "<t2>Fin contrat :</t2> <t3>" . $ressource->fin_cdd . "</t3>", 0, 'L', 0);
            }
            if ($ressource->type_embauche == 'CDD') {
                $pdf->MultiCellTag(95, 5, "<t2>Motif du CDD :</t2> <t3>" . $ressource->motif_cdd . "</t3>", 0, 'L', 0);
                if ($ressource->id_motif_cdd == 2) {
                    $pdf->MultiCellTag(95, 5, "<t2>Salarié remplacé :</t2> <t3>" . $ressource->salarie_remplace . "</t3>", 0, 'L', 0);
                }
            }
            $pdf->MultiCellTag(95, 5, "<t2>Salaire annuel brut :</t2> <t3>{$salaire} K".chr(128)."</t3> <t2>| Salaire mensuel brut :</t2> <t3>{$salaire_mensuel} €</t3> ", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Informations complémentaires sur le contrat :</t2> \n <t3>{$ressource->info_complementaire}</t3> ", 0, 'L', 0);
        }
        else if ($this->type_ressource == 'CAN_AGC') {
            $service = new Service($ressource->Id_service, $ressource->societe);
            $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
            if ($ressource->date_naissance != '0000-00-00') {
                $ressource->date_naissance = FormatageDate($ressource->date_naissance);
            }
            if ($ressource->date_naissance == '0000-00-00') {
                $ressource->date_naissance = '';
            }
            if ($ressource->salaire) {
                $salaire_mensuel = round(1000 * ($ressource->salaire / 12), 2);
            }
            $pdf->MultiCellTag(95, 5, "<t2>Etablissement :</t2> <t3>" . Agence::getLibelle($ressource->Id_agence) . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Tél :</t2> <t3>" . chunk_split($ressource->tel_fixe, 2, ' ') . "</t3>  <t2>Portable :</t2> <t3>" . chunk_split($ressource->tel_portable, 2, ' ') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Mail :</t2> <t3>{$ressource->mail}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>N° Sécurité Sociale :</t2> <t3>{$ressource->securite_sociale}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Date de naissance :</t2> <t3>{$ressource->date_naissance}</t3> <t2>| Lieu de naissance :</t2> <t3>{$ressource->ville_naissance}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Nationalité :</t2> <t3>{$ressource->nationalite}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Etat Matrimonial :</t2> <t3>" . EtatMatrimonial::getLibelle($ressource->Id_etat_matrimonial) . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Travailleur handicapé :</t2> <t3>" . (($ressource->th) ? 'oui' : 'non') . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Date d'embauche :</t2> <t3>" . FormatageDate($ressource->date_embauche) . "</t3>  <t2>| Heure d'embauche (h) :</t2> <t3>{$ressource->heure_embauche} (h)</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Libellé emploi :</t2> <t3>{$ressource->profil}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Profil :</t2> <t3>{$ressource->profil_cegid}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Statut :</t2> <t3>{$ressource->statut}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Contrat PROSERVIA :</t2> <t3>" . ContratProservia::getLibelle($ressource->Id_contrat_proservia) . "</t3>", 0, 'L', 0);
            if ($ressource->type_embauche != 'CDI') {
                    $pdf->MultiCellTag(95, 5, "<t2>Type d'embauche :</t2> <t3>{$ressource->type_embauche}</t3>", 0, 'L', 0);
            }
            $pdf->MultiCellTag(95, 5, "<t2>Fin contrat :</t2> <t3>" . FormatageDate($ressource->fin_cdd) . "</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Salaire annuel brut :</t2> <t3>{$ressource->salaire} K?</t3> <t2>| Salaire mensuel brut :</t2> <t3>{$salaire_mensuel} €</t3> ", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Informations complémentaires sur le contrat :</t2> \n <t3>{$ressource->info_complementaire}</t3> ", 0, 'L', 0);
        }
        else {
            $service = new Service($ressource->Id_service, $ressource->societe);
            $pdf->MultiCellTag(95, 5, "<t2>Société :</t2> <t3>{$ressource->societe}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Service :</t2> <t3>{$service->libelle}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(95, 5, "<t2>Responsable Hiérarchique :</t2> <t3>{$service->getManager()}</t3>", 0, 'L', 0);
        }
                
        if (!$stock) {
            $pdf->Output();
        } else {
            $pdf->Output(URL_TMP . CD_NAME_FILE . $this->Id_contrat_delegation . '.pdf', 'F');
        }
    }

    public function envoyerMail() {

        //texte du mail, attention mise en page importante pour la mise en page dans le mail
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        if ($this->Id_affaire) {
            if (is_numeric($this->Id_affaire)) {//AGC NON SALESFORCE
                $db = connecter();
                $c = strtotime(strftime('%d-%m-%Y', time()));

                $affaire = new Affaire($this->Id_affaire, array());
                $idAgence = $affaire->Id_agence;
                $idTypeContrat = $affaire->Id_type_contrat;
                $idPole = $affaire->Id_pole;
                $cds = $affaire->getCDList();
                $a = array();
                for ($i = 0; $i < count($cds); $i++) {
                    $cd = new ContratDelegation($cds[$i], array());
                    array_push($a, DateMysqltoFr($cd->fin_mission, 'mysql'));
                }
                $planning = new Planning($affaire->Id_planning, array());
                $d = max($a);
                $planning->date_fin_commande = $d;
                $planning->date_fin_previsionnelle = $d;
                $planning->save();

                if ((strtotime($planning->date_debut) <= $c && strtotime($planning->date_fin_commande) >= $c) && $affaire->Id_statut != 8) {
                    $db->query('UPDATE affaire SET Id_statut=8 WHERE Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '", 
                                        Id_statut=8,Id_utilisateur="' . mysql_real_escape_string($affaire->commercial) . '",
                                        commentaire="Mise à jour automatique via contrat délég"');
                } elseif (strtotime($planning->date_debut) >= $c && $affaire->Id_statut != 5) {
                    $db->query('UPDATE affaire SET Id_statut=5 WHERE Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '", 
                                        Id_statut=5,Id_utilisateur="' . mysql_real_escape_string($affaire->commercial) . '",
                                        commentaire="Mise à jour automatique via contrat délég"');
                }
            } else {
                $affaire = new Opportunite($this->Id_affaire, array());
                $idAgence = Agence::getIdAgence($affaire->Id_agence);
                $idTypeContrat = TypeContrat::getIdTypeContrat($affaire->Id_type_contrat);
                $idPole = Pole::getIdPole($affaire->Id_pole);
            }
            
            $compte = CompteFactory::create(null, $affaire->Id_compte);
            if ($idTypeContrat == 3 && $idPole == 3) {
                $_SESSION['dest1'] = $this->getDestinataireMail('FORM', 4);
                $copie = $this->getDestinataireMail($idAgence, 1);
                array_push($copie, ((new Utilisateur($this->createur,array()))->mail));
            } else {
                $_SESSION['dest1'] = $this->getDestinataireMail($idAgence, 1);
                if ($ressource->type_embauche != 'Intérimaire') {
                    $gestionnairePaieMail = null;
                    if ($ressource->type_ressource == 'SAL') {
                        $gestionnairePaieMail = $ressource->getGestionnairePaieMail();
                    }
                    if ($gestionnairePaieMail != null) {
                        $_SESSION['dest2'] = $gestionnairePaieMail;
                    }else {
                        $_SESSION['dest2'] = $this->getDestinataireMail($ressource->getAgency(), 2);
                    }
                } else {
                    $_SESSION['dest2'] = array();
                }
            }

            if ($ressource->type_ressource == 'MAT') {
                if ($this->Id_ressource == 'MAT') {
                    $mat = 'du matériel';
                } elseif ($this->Id_ressource == 'LIC') {
                    $mat = 'de la licence';
                } elseif ($this->Id_ressource == 'LOG') {
                    $mat = 'du logiciel';
                }
                $subject = 'Contrat Délégation pour ' . $mat . ' pour ' . $compte->nom . '';
                $text = 'Bonjour,

Voici le contrat délégation pour ' . $mat . ' pour le compte : ' . $compte->nom . '.

' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
            } else {
                $subject = 'Contrat Délégation ' . $ressource->getName($this->Id_ressource) . ' pour ' . $compte->nom . '';
                $text = 'Bonjour,

Voici le contrat délégation de ' . $ressource->getName($this->Id_ressource) . ' pour le compte : ' . $compte->nom . '.

' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
            }
        } else {//Hors affaire
            if ($this->embauche_staff) {
                $subject = 'Contrat Délégation Hors Affaire Staff ' . $ressource->getName($this->Id_ressource) . '';
                $_SESSION['dest1'] = explode(',', MAIL_CDHA_DEST);
            } else {
                $subject = 'Contrat Délégation Hors Affaire Collab ' . $ressource->getName($this->Id_ressource) . '';
                $_SESSION['dest1'] = $this->getDestinataireMail($ressource->getAgency(), 1);
                if ($ressource->type_embauche != 'Intérimaire') {
                    $gestionnairePaieMail = null;
                    if ($ressource->type_ressource == 'SAL') {
                        $gestionnairePaieMail = $ressource->getGestionnairePaieMail();
                    }
                    if ($gestionnairePaieMail != null) {
                        $_SESSION['dest2'] = $gestionnairePaieMail;
                    }else {
                        $_SESSION['dest2'] = $this->getDestinataireMail($ressource->getAgency(), 2);
                    }
                } else {
                    $_SESSION['dest2'] = array();
                }
            }
            $text = 'Bonjour,
			
Voici le contrat délégation Hors Affaire de ' . $ressource->getName($this->Id_ressource) . '
		
' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
        }

        if($copie) {
            $hdrs = array(
                'From' => (new Utilisateur($this->createur,array()))->mail,
                'Subject' => $subject,
                'To' => $_SESSION['dest1'],
                'Cc' => $copie
            );
        }
        else {
            $hdrs = array(
                'From' => (new Utilisateur($this->createur,array()))->mail,
                'Subject' => $subject,
                'To' => $_SESSION['dest1']
            );
        }
        $crlf = "\n";
        $file = URL_TMP . CD_NAME_FILE . $this->Id_contrat_delegation . '.pdf';

        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($text);
        $mime->addAttachment($file, 'application/pdf');

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        // Create the mail object using the Mail::factory method
        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        if($copie) {
            $send = $mail_object->send(array_merge($_SESSION['dest1'], $copie), $hdrs, $body);
        }
        else
            $send = $mail_object->send($_SESSION['dest1'], $hdrs, $body);

        if (is_null($this->date_envoi)) {
            $hdrs = array(
                'From' => (new Utilisateur($this->createur,array()))->mail,
                'Subject' => $subject,
                'To' => $_SESSION['dest2']
            );
            $crlf = "\n";
            $file = URL_TMP . CD_NAME_FILE . $this->Id_contrat_delegation . '.pdf';

            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $mime->addAttachment($file, 'application/pdf');

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);
            $send = $mail_object->send($_SESSION['dest2'], $hdrs, $body);
        }
        $this->statut = 'E';
        $this->save();
        $this->updateSendDate();
        
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
        unlink($file);
        //
        ////Envoyer un mail au responsable du service
        if ($this->embauche_staff || $ressource->etat == 'Consultants pôle A/I ou Delivery Manager') {
            $service = new Service(substr($ressource->Id_service,3), $ressource->societe);
            //var_dump($service);
            sendNewEmployeeMessage($service->getManager(), $service->getManagerMail(), $ressource->prenom, $ressource->nom);
        }

        if ($this->embauche_staff){
            $message = 'Bonjour,\n\n
Nous vous informons de l\'arrivée prochaine de ' . $ressource->prenom . ' ' . $ressource->nom . ' :\n\n
- Recrutement Staff\n
- Imputation : Hors affaire.\n
- Intitulé de poste : ' . $ressource->profil . '\n
- Profil CEGID : ' . $ressource->profil_cegid . '\n
- Agence de rattachement : ' . $ressource->agence . '\n
- Date d\'arrivée prévu le '. $ressource->date_embauche .'\n\n

Cordialement,\n\n

Equipe Back Office - DSIF ManpowerGroup';

           sendNewEmployeeForADV('Nouveau Recrutement Staff : ' .$ressource->prenom . ' '.$ressource->nom, $message);

        }
    }
    
    public function envoyerMailCDS($cdsService) {
        //texte du mail, attention mise en page importante pour la mise en page dans le mail
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        if ($this->Id_affaire) {
            if (is_numeric($this->Id_affaire)) {//AGC NON SALESFORCE
                $affaire = new Affaire($this->Id_affaire, array());
            }
            else {
                $affaire = new Opportunite($this->Id_affaire, array());
            }
            
            $compte = CompteFactory::create(null, $affaire->Id_compte);
            $_SESSION['dest1'] = $this->getDestinataireMail($cdsService, 3);
            //on met en copie l'adv
            $copie = $this->getDestinataireMail($ressource->getAgency(), 1);
            
            if ($ressource->type_ressource == 'MAT') {
                if ($this->Id_ressource == 'MAT') {
                    $mat = 'du matériel';
                } elseif ($this->Id_ressource == 'LIC') {
                    $mat = 'de la licence';
                } elseif ($this->Id_ressource == 'LOG') {
                    $mat = 'du logiciel';
                }
                $subject = 'Contrat Délégation CDS pour ' . $mat . ' pour ' . $compte->nom . '';
                $text = 'Bonjour,

Voici le contrat délégation CDS pour ' . $mat . ' pour le compte : ' . $compte->nom . '.

' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
            } else {
                $subject = 'Contrat Délégation ' . $ressource->getName($this->Id_ressource) . ' pour ' . $compte->nom . '';
                $text = 'Bonjour,

Voici le contrat délégation de ' . $ressource->getName($this->Id_ressource) . ' pour le compte : ' . $compte->nom . '.

' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
            }
        } else {
            $subject = 'Contrat Délégation Hors Affaire Collab ' . $ressource->getName($this->Id_ressource) . '';
            $_SESSION['dest1'] = $this->getDestinataireMail($cdsService, 3);
            $text = 'Bonjour,
			
Voici le contrat délégation Hors Affaire de ' . $ressource->getName($this->Id_ressource) . '
		
' . BASE_URL . 'membre/index.php?a=consulterCD&Id_cd=' . $this->Id_contrat_delegation . '';
        }

        $hdrs = array(
            'From' => (new Utilisateur($this->createur,array()))->mail,
            'Subject' => $subject,
            'To' => $_SESSION['dest1'],
            'Cc' => $copie,
        );
        
        $crlf = "\n";
        $file = URL_TMP . CD_NAME_FILE . $this->Id_contrat_delegation . '.pdf';

        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($text);
        $mime->addAttachment($file, 'application/pdf');

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        // Create the mail object using the Mail::factory method
        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        $send = $mail_object->send(array_merge($_SESSION['dest1'], $copie), $hdrs, $body);

        if ($cdsService == 'CDSSU') {
            $this->statut = 'U';
        } else {
            $this->statut = 'I';
        }
        $this->save();

        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
        //unlink($file);
    }

    /**
     * Affichage du formulaire de recherche d'un contrat délégation
     *
     * @return string
     */
    public function searchForm() {
        /*
          if (empty($_SESSION['filtre']['createur'])) {
          $_SESSION['filtre']['createur'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
          }
         */
        if ($_SESSION['filtre']['archive']) {
            $archive[$_SESSION['filtre']['archive']] = 'selected="selected"';
        }
        $createur = new Utilisateur($_SESSION['filtre']['createur'], array());
        $ressourceMat = RessourceFactory::create('MAT', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceTal = RessourceFactory::create('CAN_TAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSal = RessourceFactory::create('SAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSt = RessourceFactory::create('ST', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceInt = RessourceFactory::create('INT', $_SESSION['filtre']['Id_ressource'], array());
        $agence = new Agence($_SESSION['filtre']['Id_agence'], array());
        $statut[$_SESSION['filtre']['statut']] = 'selected="selected"';
        $html = '
		n° Contrat Délegation : <input id="Id_cd" type="text" onkeyup="afficherCD()" value="' . $_SESSION['filtre']['Id_cd'] . '" size="5" />
                &nbsp;&nbsp;
                n° opportunité mère : <input id="reference_affaire_mere" type="text" onkeyup="afficherCD()" value="' . $_GET['Id_affaire_mere'] . '" size="5" />
                &nbsp;&nbsp;
		n° opportunité : <input id="Id_affaire" type="text" onkeyup="afficherCD()" value="' . $_GET['Id_affaire'] . '" size="5" />
                &nbsp;&nbsp;	
                Mots clés : <input id="motclecd" type="text" onkeyup="afficherCD()" value="' . $_SESSION['filtre']['motclecd'] . '" />
                &nbsp;&nbsp;	    
		<select id="createur" onchange="afficherCD()">
                    <option value="">Par créateur</option>
                    <option value="">----------------------------</option>
                    ' . $createur->getList("COM") . '
                    <option value="">----------------------------</option>
                    ' . $createur->getList("OP") . '
                    <option value="">----------------------------</option>
                    ' . $createur->getList("RH") . '
                </select>
		&nbsp;&nbsp;
		<select id="Id_ressource" onchange="afficherCD()">
                    <option value="">Par ressource</option>
                    ' . $ressourceMat->getList() . '
                    ' . $ressourceTal->getList() . '
                    ' . $ressourceSal->getList() . '
                    ' . $ressourceSt->getList() . '
                    ' . $ressourceInt->getList() . '
		</select>
                &nbsp;&nbsp;
		<select id="Id_agence" onchange="afficherCD()">
                    <option value="">Par agence</option>
                    <option value="">----------------------------</option>
                    ' . $agence->getList() . '
		</select>
                &nbsp;&nbsp;
		<select id="statut" onchange="afficherCD()">
                    <option value="">Par statut</option>
                    <option value="">----------------------------</option>
                    <option value="A" ' . $statut['A'] . '>En attente</option>
                    <option value="I" ' . $statut['I'] . '>En Vérification CDS SI</option>
                    <option value="U" ' . $statut['U'] . '>En vérification CDS SU</option>
                    <option value="E" ' . $statut['E'] . '>En vérification</option>
                    <option value="V" ' . $statut['V'] . '>Vérifié</option>
                    <option value="R" ' . $statut['R'] . '>Retourné</option>
		</select>&nbsp;&nbsp;
                <select id="archive" onchange="afficherCD()">
                    <option value="">Par archivage</option>
                    <option value="">----------------------------</option>
                    <option value="1" ' . $archive['1'] . '>Archivée</option>
                    <option value="0" ' . $archive['0'] . '>Non archivée</option>
                    <option value="2" ' . $archive['2'] . '>Archivée et non archivée</option>
                </select>
                <select id="origine" onchange="afficherCD()">
                    <option value="">Origine</option>
                    <option value="">----------------------------</option>
                    <option value="PWS" >PWS</option>
                    <option value="PDS" >PDS</option>
                    <option value="FINATEL" >FINATEL</option>
                </select>
                <input onchange="afficherCD()" type="checkbox" name="finishing" id="finishing" value="1">Se finissant dans moins d\'un mois</input>';
        return $html;
    }
    
    /**
     * Affichage des filtres de recherche pour l'export de contrat délégation
     *
     * @return string
     */
    public static function searchExportForm() {
        $html = 'Créé entre le  <input id="debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['debut'] . '" size="8" />
                et le <input id="fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['fin'] . '" size="8" />
			&nbsp;&nbsp;
			<input type="button" onclick="afficherExportCD()" value="Go !" />
                        <input type="reset" value="Refresh" onclick="initSearchForm(afficherExportCD)">
';
        return $html;
    }

    public function duplicateForm() {
        $content .= '<form id="duplicateCD" name="duplicateCD">
                <input type="hidden" name="Id_compte" id="Id_compte" value="' . $this->Id_compte . '" />
                <input type="hidden" name="reference_affaire" id="reference_affaire" value="' . $this->reference_affaire . '" />
                <input type="hidden" name="reference_affaire_mere" id="reference_affaire_mere" value="' . $this->reference_affaire_mere . '" />
                    Source : <select id="opType" name="opType" onChange="prefixCompteCD(false, true);">
                        <option value="">Type d\'affaire</option>
                        <option value="">----------------------------</option>
                        <option value="agc">AGC</option>
                        <option value="sfc" selected="selected">Salesforce</option>
                    </select>
                    <br /><br />';
        $content .= '<span class="infoFormulaire"> * </span>Compte : <input id="prefix" type="text" size="4" onkeyup="prefixCompteCD(0)">
                  <span id="compte">
                    <select id="Id_compte" name="Id_compte" onchange="showCaseList(this.value,0);">
                        <option value="">' . CUSTOMERS_SELECT . '</option>
                        <option value="">-------------------------</option>
                    </select>
                  </span><br /><br />';
        $content .= '<div id="affaire"><span class="infoFormulaire"> * </span>Opportunité : 
                    <select name="Id_affaire" id="Id_affaire" >
                        <option value="">Sélectionner une opportunité</option>
                        <option value="">-------------------------</option>
                    </select>
                    </form>
                    </div><br /><br />';
        $footer .= '<button type="button" class="button add" onclick="Modalbox.show(\'../com/index.php?a=dupliquerCD&amp;Id=' . $this->Id_contrat_delegation . '\', {params: Form.serialize(\'duplicateCD\'), infiniteLoading: true, afterLoad: function() { Modalbox.hide();},afterHide: function() {showInformationMessage(\'Votre contrat délégation a été correctement dupliqué.\',5000);afficherCD();}}); return false;">Dupliquer autre opportunité</button>';
        $footer .= '&nbsp;&nbsp;<button type="button" class="button delete" onclick="Modalbox.hide();">Annuler</button>';
        return json_encode(array('content' => utf8_encode($content), 'footer' => utf8_encode($footer)));
    }

    /**
     * Duplication d'un contrat délégation
     */
    public function duplicate($idAffaire = null, $duplicateCreator = false) {
        $db = connecter();
        if ($this->Id_prop_ress) {
            $ligne = $db->query('SELECT * FROM proposition_ressource WHERE Id_prop_ress = ' . mysql_real_escape_string((int) $this->Id_prop_ress))->fetchRow();
            $db->query('INSERT INTO proposition_ressource SET Id_proposition = "' . mysql_real_escape_string($ligne->id_proposition) . '",
                        Id_ressource = "' . mysql_real_escape_string($ligne->id_ressource) . '", frais_journalier = "' . mysql_real_escape_string($ligne->frais_journalier) . '", 
                        cout_journalier = "' . mysql_real_escape_string($ligne->cout_journalier) . '", tarif_journalier = "' . mysql_real_escape_string($ligne->tarif_journalier) . '",
                        duree = "' . mysql_real_escape_string($ligne->duree) . '", marge = "' . mysql_real_escape_string($ligne->marge) . '",
                        ca = "' . mysql_real_escape_string($ligne->ca) . '", debut = "' . mysql_real_escape_string($ligne->debut) . '",
                        fin = "' . mysql_real_escape_string($ligne->fin) . '", type = "' . mysql_real_escape_string($ligne->type) . '",
                        inclus = "' . mysql_real_escape_string($ligne->inclus) . '", fin_prev = "' . mysql_real_escape_string($ligne->fin_prev) . '"');
        }
        $Id_prop_ress = mysql_insert_id();

        $ligne = $db->query('SELECT * FROM contrat_delegation WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation))->fetchRow();

        $ligne->tache = str_replace('"', "'", $ligne->tache);
        $ligne->commentaire_horaire = str_replace('"', "'", $ligne->commentaire_horaire);
        $ligne->commentaire_indemnite = str_replace('"', "'", $ligne->commentaire_indemnite);
        $ligne->commentaire_astreinte = str_replace('"', "'", $ligne->commentaire_astreinte);

        if ($idAffaire != null) {
            $ligne->id_affaire = $idAffaire;
            if (is_numeric($ligne->id_affaire)) {
                $affaire = new Affaire($ligne->id_affaire, array());
                $ligne->pole = Pole::getLibelle($affaire->Id_pole);
                $ligne->type_contrat = TypeContrat::getLibelle($affaire->Id_type_contrat);
                $ligne->agence = Agence::getLibelle($affaire->Id_agence);
                $ligne->intitule = Intitule::getLibelle($affaire->Id_intitule);
                $ligne->ca_affaire = Proposition::getCa(Affaire::lastProposition($this->Id_affaire));
                $compte = CompteFactory::create(null, $affaire->Id_compte);
                $ligne->contact_principal = $compte->getContactPrincipal();
                $ligne->fonction_cprincipal = $compte->getFonctionContactPrincipal();
            } else {
                $affaire = new Opportunite($ligne->id_affaire, null);
                // Champs commun à tous les types d'opportunités SFC
                $ligne->agence = $affaire->Id_agence;
                $ligne->pole = $affaire->Id_pole;
                $ligne->type_contrat = $affaire->Id_type_contrat;
                
                $ligne->fin_mission = $affaire->date_fin_commande;
                $ligne->debut_mission = $affaire->date_debut;
                $ligne->intitule = $affaire->Id_intitule;
                $ligne->type = $affaire->type;
                
                // Traitement en fonction du type d'opportunité
                if($ligne->type_contrat == 'Assistance Technique Proservia') {
                    $devis = new Devis(null, $idAffaire);
                    $ligne->reference_devis = $devis->reference_devis;
                    $ligne->intitule = $devis->nom;
                    $compte = CompteFactory::create('SF', $devis->Id_compte);
                    $ligne->compte = $compte->nom;
                    $ligne->id_cegid = $compte->Id_cegid;
                    $ligne->adresse_facturation = $compte->getAdresseFacturation();
                    $con1 = ContactFactory::create(null, $devis->Id_contact);
                    $ligne->contact1 = $con1->nom . ' ' . $con1->prenom;
                    $ligne->mode_reglement = $devis->condition_reglement;
                    $ligne->ca_affaire = $devis->ca;
                    $contactPrincipal = $affaire->getContactPrincipal();
                    $ligne->contact_principal = $contactPrincipal[0];
                    $ligne->fonction_cprincipal = $contactPrincipal[1];
                }
                else {
                    if($this->type_contrat == 'Infogérance Proservia') {
                        $ligne->type_infogerance = $affaire->type_infogerance;
                    }
                }
            }
            $c = new Utilisateur($affaire->commercial, array());
            $ligne->commercial = $c->prenom . ' ' . $c->nom;
            $c = CompteFactory::create(null, $affaire->Id_compte);
            $ligne->compte = $c->nom;
            $ligne->mode_reglement = $c->getModeReglement();
            $ligne->adresse_facturation = $c->getAdresseFacturation();
            $c = ContactFactory::create(null, $affaire->Id_contact1);
            $ligne->contact1 = $c->nom . ' ' . $c->prenom;
            $c = ContactFactory::create(null, $affaire->Id_contact2);
            $ligne->contact2 = $c->nom . ' ' . $c->prenom;
            
            // Champs commun à tous les types d'opportunités (AGC et SFC)
            $ligne->apporteur = formatPrenomNom($affaire->apporteur);
            $ligne->type_infogerance = $affaire->type_infogerance;
            $ligne->type = $affaire->type;
            $ligne->reference_affaire = $affaire->reference_affaire;
            $ligne->reference_affaire_mere = $affaire->reference_affaire_mere;
        }
        $creator = ($duplicateCreator === true) ? $ligne->createur : $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;

        $r = $db->query('INSERT INTO contrat_delegation
                            SET Id_duplication = "' .mysql_real_escape_string((int) $ligne->id_contrat_delegation) . '", createur="' . mysql_real_escape_string($creator) . '", date_creation="' . mysql_real_escape_string(DATETIME) . '", date_modification="' . mysql_real_escape_string(DATETIME) . '",
                                Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '", Id_ressource="' . mysql_real_escape_string($ligne->id_ressource) . '", type_ressource = "' . mysql_real_escape_string($ligne->type_ressource) . '",
                                cout_journalier="' . mysql_real_escape_string($ligne->cout_journalier) . '", type_mission="' . mysql_real_escape_string($ligne->type_mission) . '",
                                indemnites_ref="' . mysql_real_escape_string($ligne->indemnites_ref) . '", nature_mission="' . mysql_real_escape_string($ligne->nature_mission) . '",
                                remplacement="' . mysql_real_escape_string($ligne->remplacement) . '", st_nom="' . mysql_real_escape_string($ligne->st_nom) . '", st_prenom="' . mysql_real_escape_string($ligne->st_prenom) . '",
                                st_societe="' . mysql_real_escape_string($ligne->st_societe) . '", st_adresse="' . mysql_real_escape_string($ligne->st_adresse) . '", st_siret="' . mysql_real_escape_string($ligne->st_siret) . '",
                                st_ape="' . mysql_real_escape_string($ligne->st_ape) . '", st_tarif="' . mysql_real_escape_string($ligne->st_tarif) . '", st_commentaire="' . mysql_real_escape_string($ligne->st_commentaire) . '",
                                horaire="' . mysql_real_escape_string($ligne->horaire) . '", commentaire_horaire="' . mysql_real_escape_string($ligne->commentaire_horaire) . '",
                                moyen_acces="' . mysql_real_escape_string($ligne->moyen_acces) . '", Id_type_indemnite="' . mysql_real_escape_string($ligne->id_type_indemnite) . '",
                                indemnite_destination = "' . mysql_real_escape_string($this->indemnite_destination) . '", indemnite_region = "' . mysql_real_escape_string($this->indemnite_region) . '",
                                indemnite_type_deplacement = "' . mysql_real_escape_string($this->indemnite_type_deplacement) . '", commentaire_indemnite="' . mysql_real_escape_string($ligne->commentaire_indemnite) . '",
                                archive="' . mysql_real_escape_string($ligne->archive) . '", astreinte="' . mysql_real_escape_string($ligne->astreinte) . '",
                                commentaire_astreinte="' . mysql_real_escape_string($ligne->commentaire_astreinte) . '", debut_mission="' . mysql_real_escape_string($ligne->debut_mission) . '",
                                fin_mission="' . mysql_real_escape_string($ligne->fin_mission) . '", duree_mission="' . mysql_real_escape_string($ligne->duree_mission) . '",
                                lieu_mission="' . mysql_real_escape_string($ligne->lieu_mission) . '", contact_principal="' . mysql_real_escape_string($ligne->contact_principal) . '",
                                fonction_cprincipal="' . mysql_real_escape_string($ligne->fonction_cprincipal) . '", adresse_facturation="' . mysql_real_escape_string($ligne->adresse_facturation) . '",
                                materiel="' . mysql_real_escape_string($ligne->materiel) . '", tache="' . mysql_real_escape_string($ligne->tache) . '",
                                Id_prop_ress = "' . mysql_real_escape_string($Id_prop_ress) . '", compte="' . mysql_real_escape_string($ligne->compte) . '",
                                mode_reglement="' . mysql_real_escape_string($ligne->mode_reglement) . '", contact1="' . mysql_real_escape_string($ligne->contact1) . '",
                                contact2="' . mysql_real_escape_string($ligne->contact2) . '", commercial="' . mysql_real_escape_string($ligne->commercial) . '",
                                sdm="' . mysql_real_escape_string($ligne->sdm) . '", agence="' . mysql_real_escape_string($ligne->agence) . '",
                                pole="' . mysql_real_escape_string($ligne->pole) . '", type_contrat="' . mysql_real_escape_string($ligne->type_contrat) . '",
                                type_infogerance="' . mysql_real_escape_string($ligne->type_infogerance) . '",
                                intitule="' . mysql_real_escape_string($ligne->intitule) . '", ca_affaire="' . mysql_real_escape_string($ligne->ca_affaire) . '",
                                reference_affaire="' . mysql_real_escape_string($ligne->reference_affaire) . '", reference_affaire_mere="' . mysql_real_escape_string($ligne->reference_affaire_mere) . '",
                                reference_devis="' . mysql_real_escape_string($ligne->reference_devis) . '",reference_bdc="' . mysql_real_escape_string($ligne->reference_bdc) . '",
                                Id_cegid="' . mysql_real_escape_string($ligne->id_cegid) . '",Id_compte="' . mysql_real_escape_string($this->Id_compte) . '",
                                type="' . mysql_real_escape_string($ligne->type) . '", apporteur = "' .  mysql_real_escape_string($ligne->apporteur) . '",
                                nom_ressource="' . mysql_real_escape_string($ligne->nom_ressource) . '", prenom_ressource="' . mysql_real_escape_string($ligne->prenom_ressource) . '",
                                embauche_staff="' . mysql_real_escape_string($ligne->embauche_staff) . '",salaire="' . mysql_real_escape_string($ligne->salaire) . '",
                                Id_devis="' . mysql_real_escape_string($ligne->Id_devis) . '",code_otp="' . mysql_real_escape_string($ligne->code_otp) . '",code_produit="' . mysql_real_escape_string($ligne->code_produit) . '",
                                agence_mission="' . mysql_real_escape_string($ligne->agence_mission) . '",
                                compte_final="' . mysql_real_escape_string($ligne->compte_final) . '",Id_compte_final="' . mysql_real_escape_string($ligne->Id_compte_final) . '"');
        $Id_cd = mysql_insert_id();

        if (!empty($this->indemnite)) {
            foreach ($this->indemnite as $i) {
                $db->query('INSERT INTO cd_indemnite SET Id_contrat_delegation=' . mysql_real_escape_string($Id_cd) . ', Id_indemnite=' . mysql_real_escape_string((int) $i['id']) . '');
            }
        }
        return $Id_cd;
    }

    /**
     * Affichage des informations de la ressource pour le contrat délégation
     *
     * @return string
     */
    public function infoRessourceCD($type_ressource = null, $Id_ressource = 0) {
        $ressource = RessourceFactory::create($type_ressource, $Id_ressource, array());
        if ($this->Id_contrat_delegation && $Id_ressource == $this->Id_ressource) {
            $nom = $this->st_nom;
            $prenom = $this->st_prenom;
            $societe = $this->st_societe;
            $adresse = $this->st_adresse;
            $siret = $this->st_siret;
            $ape = $this->st_ape;
            $tarif = $this->st_tarif;
            $commentaire = $this->st_commentaire;
        } else {
            $nom = $ressource->nom;
            $prenom = $ressource->prenom;
            //$societe     = $ressource->societe;
            $adresse = $ressource->adresse . ' ' . $ressource->code_postal . ' ' . $ressource->ville;
            $siret = $ressource->siret;
            $ape = $ressource->ape;
            //$tarif       = $ressource->tarif;
        }

        if ($type_ressource == 'ST') {
            $html .= '
                Nom : <input type="text" id="st_nom" readonly name="st_nom" value="' . $nom . '" size="30" /><br /><br />
                Prénom : <input type="text" readonly name="st_prenom" value="' . $prenom . '" /><br /><br />
                Adresse : <input type="text" name="st_adresse" value="' . $adresse . '" size="50" /><br /><br />
                Société : <input type="text" name="st_societe" value="' . $societe . '" size="20" /><br /><br />
                N° SIRET :
                <input type="text" name="st_siret" value="' . $siret . '" size="20" /><br /><br />
                Code APE :
                <input type="text" name="st_ape" value="' . $ape . '" size="4" /><br /><br />
                <span class="infoFormulaire"> * </span>
                Tarifs sous-traitant :
                <input type="text" id="st_tarif" name="st_tarif" value="' . $tarif . '" size="5" /> ( &euro; / jour )<br /><br />
                Commentaires : <br /><br />
                <textarea name="st_commentaire" rows="8" cols="50">' . $commentaire . '</textarea><br /><br />';
        }
        else if($type_ressource == 'CAN_TAL' || $type_ressource == 'CAN_STA' ) {
            $service = new Service(substr($ressource->Id_service, 3), $ressource->societe);
            $agence = $ressource->agence;
            $html .= '
                <label>Nom : </label><span data-nom="nom" data-libelle="nom">' . $ressource->nom . '</span><input type="hidden" name="nom_ressource" id="nom_ressource" value="' . $ressource->nom . '"/><br /><br />
                <label>Prénom : </label><span data-nom="prenom" data-libelle="prénom">' . $ressource->prenom . '</span><input type="hidden" name="prenom_ressource" id="prenom_ressource" value="' . $ressource->prenom . '"/><br /><br />
                <label>Nom de jeune fille : </label>' . $ressource->nom_jeune_fille . '<br /><br />
                <label>Adresse : </label><span data-nom="adresse" data-libelle="adresse">' . $ressource->adresse . ' ' . $ressource->code_postal . ' ' . $ressource->ville . '</span><br /><br />
                <label>Tél fixe : </label>' . $ressource->tel_fixe . '<br /><br />
                <label>Tél portable : </label>' . $ressource->tel_portable . '<br /><br />
                <label>Mail : </label><span data-nom="mail" data-libelle="mail">' . $ressource->mail . '</span><br /><br />
                <label>Date de naissance : </label><span data-nom="date_naissance" data-libelle="date de naissance">' . $ressource->date_naissance . '</span><br /><br />
                <label>Nationalité : </label><span data-nom="nationalite" data-libelle="nationalité">' . $ressource->Id_nationalite . '</span><br /><br />
                <label>Lieu de naissance : </label><span data-nom="ville_naissance" data-libelle="ville de naissance">' . $ressource->ville_naissance . '</span>, <span data-nom="dept_naissance" data-libelle="département de naissance">' . $ressource->Id_dpt_naiss . '</span>, <span data-nom="pays_naissance" data-libelle="pays de naissance">' . $ressource->Id_pays_naissance . '</span><br /><br />
                <label>Etat Matrimonial : </label>' . $ressource->Id_etat_matrimonial . ' <br /><br />
                <label>Travailleur handicapé : </label><span data-nom="th" data-libelle="travailleur handicapé">' . (($ressource->th) ? 'oui' : 'non') . '</span><br /><br />
                <label>Responsable Hiérarchique : </label><span data-nom="responsable" data-libelle="responsable hiérarchique">' . $service->getManager() . '</span><br /><br />
                <label>Service : </label><span data-nom="service" data-libelle="service">' . $service->libelle . '</span><br /><br />
                <label>Agence : </label><span data-nom="agence" data-libelle="agence">' . $agence . '</span><br /><br />
                ' . $ressource->etat . ' <br /><br />
                <label>Profil : </label><span data-nom="profil" data-libelle="profil">' . $ressource->profil_cegid . '</span><br /><br />
				<label>Itinérant : </label><span data-nom="itinerant" data-libelle="itinérant">' . ($ressource->type_embauche2 == 3 ? 'Oui' : 'Non') . '</span><br /><br />
                <label>Libellé emploi : </label><span data-nom="libelle_emploi" data-libelle="libellé emploi">' . $ressource->profil . '</span><br /><br />';
            if ($ressource->libelle_emploi_comp != '') {
                $html .= '<label>Libellé emploi complémentaire : </label>' . $ressource->libelle_emploi_comp . '<br /><br />';
            }
                $html .= '<label>Embauche : </label><span data-nom="type_embauche" data-libelle="type de contrat">' . $ressource->type_embauche . '</span><br /><br />';
            if ($ressource->type_embauche != 'CDI' && $ressource->type_embauche != 'Contrat de professionnalisation CDI') {
                $html .= '<label>Fin CDD : </label><span data-nom="fin_cdd" data-libelle="date de fin de contrat">' . $ressource->fin_cdd . '</span><br /><br />';
            }
            if ($ressource->type_embauche == 'CDD') {
                $html .= '<label>Motif du CDD : </label><span data-nom="motif_cdd" data-libelle="motif du CDD">' . $ressource->motif_cdd . '</span><br /><br />';
                if ($ressource->id_motif_cdd == 2) {
                    $html .= '<label>Salarie remplacé : </label>' . $ressource->salarie_remplace . ' <br /><br />';
                }
            }
            $html .= '
                <label>Statut : </label><span data-nom="statut" data-libelle="statut d\'embauche">' . $ressource->statut . '</span><br /><br />
                <label>Contrat Proservia : </label>' . $ressource->Id_contrat_proservia . ' <br /><br />
                <label>N° sécurité sociale : </label><span data-nom="securite_sociale" data-libelle="numéro de sécurité social">' . $ressource->securite_sociale . '</span><br /><br />
                <label>Date d\'embauche : </label><span data-nom="date_embauche" data-libelle="date d\'embauche">' . $ressource->date_embauche . '</span> | Heure d\'embauche : ' . $ressource->heure_embauche . ' (h)<br /><br />';
            if($ressource->embauche_staff == true) {
                $s = ($this->salaire) ? $this->salaire : $ressource->salaire;
                $html .= '
                    <label>Salaire annuel : </label><input type="texte" name="salaire" value="' . $s . '" size="3"/> K&euro; <br /><br />';
            }
            else {
                $html .= '
                    <label>Salaire annuel : </label><span data-nom="salaire" data-libelle="salaire">' . $ressource->salaire . '</span> K&euro; <br /><br />';
            }
            $html .= '
                <input type="hidden" name="embauche_staff" id="embauche_staff" value="' . $ressource->embauche_staff . '"/>
                <div class="clearer"></div>';
        }
        else if($type_ressource == 'CAN_AGC') {
            $service = new Service($ressource->Id_service, $ressource->societe);
            $agence = Agence::getLibelle($ressource->getAgency());
            $html .= '
                Nom : ' . $ressource->nom . '<input type="hidden" name="nom_ressource" id="nom_ressource" value="' . $ressource->nom_ressource . '"/><br /><br />
                Prénom : ' . $ressource->prenom . '<input type="hidden" name="prenom_ressource" id="prenom_ressource" value="' . $ressource->prenom_ressource . '"/><br /><br />
                Nom de jeune fille : ' . $ressource->nom_jeune_fille . ' <br /><br />
                Adresse : ' . $ressource->adresse . ' ' . $ressource->code_postal . ' ' . $ressource->ville . ' <br /><br />
                Responsable Hiérarchique : ' . $service->getManager() . ' <br /><br />
                Service : ' . $service->libelle . ' <br /><br />
                Agence : ' . $agence . ' <br /><br />
                ' . $ressource->etat . ' <br /><br />
                Tél fixe : ' . $ressource->tel_fixe . ' <br /><br />
                Tél portable : ' . $ressource->tel_portable . ' <br /><br />
                Mail : ' . $ressource->mail . ' <br /><br />
                Nationalité : ' . $ressource->nationalite . ' <br /><br />
                Pays de naissance : ' . Pays::getLibelle($ressource->Id_pays_naissance) . ' <br /><br />
                Etat Matrimonial : ' . EtatMatrimonial::getlibelle($ressource->Id_etat_matrimonial) . ' <br /><br />
                Profil : ' . $ressource->profil . ' <br /><br />
                Embauche : ' . $ressource->type_embauche . ' <br /><br />';
            if ($ressource->type_embauche != 'CDI') {
                $html .= 'Fin contrat : ' . FormatageDate($ressource->fin_cdd) . ' <br /><br />';
            }
            $html .= '
                Statut : ' . $ressource->statut . ' <br /><br />
                Contrat Proservia : ' . ContratProservia::getLibelle($ressource->Id_contrat_proservia) . ' <br /><br />
                N° sécurité sociale : ' . $ressource->securite_sociale . ' <br /><br />
                Date d\'embauche : ' . FormatageDate($ressource->date_embauche) . '  Heure d\'embauche : ' . $ressource->heure_embauche . ' (h)<br /><br />
                Salaire annuel : ' . $ressource->salaire . ' K&euro; <br /><br />
                <input type="hidden" name="embauche_staff" id="embauche_staff" value="' . $ressource->embauche_staff . '"/>
                <div class="clearer"></div>';
        }
        else {
            $service = new Service($ressource->Id_service, $ressource->societe);
            $agence = Agence::getLibelle($ressource->getAgency());
            $html .= '
                Nom : ' . $ressource->nom . '<input type="hidden" name="nom_ressource" id="nom_ressource" value="' . $ressource->nom_ressource . '"/><br /><br />
                Prénom : ' . $ressource->prenom . '<input type="hidden" name="prenom_ressource" id="prenom_ressource" value="' . $ressource->prenom_ressource . '"/><br /><br />
                Nom de jeune fille : ' . $ressource->nom_jeune_fille . ' <br /><br />
                Adresse : ' . $ressource->adresse . ' ' . $ressource->code_postal . ' ' . $ressource->ville . ' <br /><br />
                Responsable Hiérarchique : ' . $service->getManager() . ' <br /><br />
                Pôle : ' . $ressource->pole . ' <br /><br />
                Service : ' . $service->libelle . ' <br /><br />
                Agence : ' . $agence . ' <br /><br />
                Société : ' . $ressource->societe . ' <br /><br />
                ' . $ressource->etat . ' <br /><br />
                <a href="javascript:ouvre_popup(\'index.php?a=demande_changement&Id_ressource=' . $Id_ressource . '\')">Demande de changement</a>';
        }
        
        if('FINATEL' === "" || strpos($agence, 'FINATEL') === 0) {
            $tasks = '
                Mission de technicien itinérant Proservia On Site Services.<br />
                Intervention chez nos clients Proservia ; <br />
                Maintien en Conditions Opérationnel de support de proximité auprès des utilisateurs, déploiement de matériel, audit et inventaire';
        }
        
        $cds = $this->ressourceIsCds($type_ressource, $Id_ressource);
        if ($cds) {
            $html .= '<script>$("statut_text").innerHTML ="<b style=\"font-size: small;\">Statut : En attente d\'envoi aux services Validation '.$cds.' puis ADV et paie</b><br \><br \>";</script>';
        } else {
            $html .= '<script>$("statut_text").innerHTML = "<b style=\"font-size: small;\">Statut : En attente d\'envoi aux services ADV et paie</b><br \><br \>";</script>';
        }
        
        return json_encode(array('infoRessource' => utf8_encode($html), 'tasks' => utf8_encode($tasks)));
    }

    /**
     * Récupération des informations de la ressource sur la proposition associée
     *
     * @return array Informations de la ressource
     */
    public function getPropsitionResource() {
        $db = connecter();
        return $db->query('SELECT * FROM proposition_ressource 
                            WHERE Id_prop_ress = ' . mysql_real_escape_string($this->Id_prop_ress) . '')->fetchRow();
    }

    /**
     * Récupération du mail destinataire du contrat délégation
     *
     * @param string Id_agence
     * @param int service
     *
     * @return string
     */
    public function getDestinataireMail($Id_agence, $service) {
        $db = connecter();
        $r = $db->query('SELECT mail FROM destinataire_cd WHERE Id_agence = "' . mysql_real_escape_string($Id_agence) . '" AND service="' . mysql_real_escape_string($service) . '"');
        $a = array();
        while ($ligne = $r->fetchRow()) {
            array_push($a, $ligne->mail);
        }
        return $a;
    }

    /**
     * Récupération du numéro de téléphone destinataire du contrat délégation
     *
     * @param string Id_agence
     * @param int service
     *
     * @return string
     */
    public function getDestinataireNumber($service, $mail = null) {
        $db = connecter();
        return $db->query('SELECT DISTINCT numero FROM destinataire_cd WHERE service="' . mysql_real_escape_string($service) . '" AND mail = "' . $mail . '"')->fetchRow()->numero;
    }

    /**
     * Archivage d'un contrat délégation
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE contrat_delegation SET archive="1" WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
    }

    /**
     * Desarchivage d'un contrat délégation
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE contrat_delegation SET archive="0" WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
    }

    /**
     * Validation d'un contrat délégation
     */
    public function validate() {
        $db = connecter();
        $c = strtotime(strftime('%d-%m-%Y', time()));
        $db->query('UPDATE contrat_delegation SET statut="V" WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));

        $utilisateur = new Utilisateur($this->createur, array());
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        if ($this->Id_affaire != 0) {
            if (is_numeric($this->Id_affaire)) {
                $affaire = new Affaire($this->Id_affaire, array());
                $idAffaire = $affaire->Id_affaire;
                $cds = $affaire->getCDList();
                $a = array();
                for ($i = 0; $i < count($cds); $i++) {
                    $cd = new ContratDelegation($cds[$i], array());
                    array_push($a, DateMysqltoFr($cd->fin_mission, 'mysql'));
                }
                $planning = new Planning($affaire->Id_planning, array());
                $d = max($a);
                $planning->date_fin_commande = $d;
                $planning->date_fin_previsionnelle = $d;
                $planning->save();

                if ((strtotime($planning->date_debut) <= $c && strtotime($planning->date_fin_commande) >= $c) && $affaire->Id_statut != 8) {
                    $db->query('UPDATE affaire SET Id_statut=8 WHERE Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '", 
                                        Id_statut=8,Id_utilisateur="' . mysql_real_escape_string($affaire->commercial) . '",
                                        commentaire="Mise à jour automatique via contrat délég"');
                } elseif (strtotime($planning->date_debut) >= $c && $affaire->Id_statut != 5) {
                    $db->query('UPDATE affaire SET Id_statut=5 WHERE Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . '');
                    $db->query('INSERT INTO historique_statut SET Id_affaire=' . mysql_real_escape_string($affaire->Id_affaire) . ', date="' . mysql_real_escape_string(DATETIME) . '", 
                                        Id_statut=5,Id_utilisateur="' . mysql_real_escape_string($affaire->commercial) . '",
                                        commentaire="Mise à jour automatique via contrat délég"');
                }
                $idAgence = $affaire->Id_agence;
                $idTypeContrat = $affaire->Id_type_contrat;
                $idPole = $affaire->Id_pole;
            } else {
                $affaire = new Opportunite($this->Id_affaire, array());
                $idAffaire = $affaire->reference_affaire;
                $idAgence = Agence::getIdAgence($affaire->Id_agence);
                $idTypeContrat = TypeContrat::getIdTypeContrat($affaire->Id_type_contrat);
                $idPole = Pole::getIdPole($affaire->Id_pole);
            }

            $compte = CompteFactory::create(null, $affaire->Id_compte);
            $subject = 'Contrat Délégation ' . $ressource->getName() . ' pour ' . $compte->nom . '';
            $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
            list($advFirstName) = explode('.', $advMail);
            $advFirstName = ucfirst($advFirstName);
            $advNumber = $this->getDestinataireNumber(1, $advMail);
            $gestionnairePaieMail = null;
			if ($ressource->type_ressource == 'SAL') {
				$gestionnairePaieMail = $ressource->getGestionnairePaieMail();
			}
			if ($gestionnairePaieMail != null) {
				$_SESSION['dest2'] = $gestionnairePaieMail;
			}else {
				$_SESSION['dest2'] = $this->getDestinataireMail($ressource->getAgency(), 2);
			}
            $text = 'Bonjour,
			
Voici le contrat délégation de ' . $ressource->getName() . '
		
pour le compte : ' . $compte->nom . '.
		
' . BASE_URL . 'fac/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '';

            if ($ressource->type_ressource == 'MAT') {
                if ($this->Id_ressource == 'MAT') {
                    $mat = 'du matériel';
                } elseif ($this->Id_ressource == 'LIC') {
                    $mat = 'de la licence';
                } elseif ($this->Id_ressource == 'LOG') {
                    $mat = 'du logiciel';
                }
                if ($idTypeContrat && $idPole == 3) {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié et validé par le service formation.<br />
                                Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié et validé par le service ADV.<br />
                                Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                                ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            } else {
                if ($idTypeContrat && $idPole == 3) {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié et validé par le service formation.<br />
                                Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié et validé par le service ADV.<br />
                                Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                                ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            }
        } else {
            if (!$ressource->isStaff()) {
                $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
                $advNumber = $this->getDestinataireNumber(1, $advMail);
                list($advFirstName) = explode('.', $advMail);
                $advFirstName = ucfirst($advFirstName);
                $subject = 'Contrat Délégation Hors Affaire Collab ' . $ressource->getName() . '';
                $gestionnairePaieMail = null;
				if ($ressource->type_ressource == 'SAL') {
					$gestionnairePaieMail = $ressource->getGestionnairePaieMail();
				}
				if ($gestionnairePaieMail != null) {
					$_SESSION['dest2'] = $gestionnairePaieMail;
				}else {
					$_SESSION['dest2'] = $this->getDestinataireMail($ressource->getAgency(), 2);
				}
                $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été vérifié et validé par le service ADV.<br />
                        Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                        ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
            } else {
                $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été vérifié et validé.<br />
                        Celui-ci est consultable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=editerContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=editerContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
            }

            $text = 'Bonjour,
			
Voici le contrat délégation Hors Affaire de ' . $ressource->getName() . '
		
' . BASE_URL . 'fac/index.php?a=editerContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '';
        }

        /* Envoi du mail au commercial */

        if (!$ressource->isStaff()) {
            $hdrs = array(
                'From' => $advMail,
                'Subject' => 'Validation du contrat délégation n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail
            );
        } else {
            $hdrs = array(
                'From' => $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr',
                'Subject' => 'Validation du contrat délégation staff n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail
            );
        }
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setHTMLBody($textCom);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        // Create the mail object using the Mail::factory method
        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        $send = $mail_object->send((new Utilisateur($this->createur,array()))->mail, $hdrs, $body);

        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
    }

    /**
     * Rejet d'un contrat délégation
     */
    public function reject($idRefus, $commentaire) {
        $db = connecter();
        $db->query('UPDATE contrat_delegation SET statut="R", Id_refus_cd = ' . $idRefus . ', commentaire_refus = "' . $commentaire . '"
                    WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));

        $utilisateur = new Utilisateur($this->createur, array());
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        $com = ($commentaire) ? ' ainsi qu\'un commentaire : ' . $commentaire : '';
        $raison = $this->getRejectCauseLibelle();
        if ($this->Id_affaire != 0) {
            if (is_numeric($this->Id_affaire)) {
                $affaire = new Affaire($this->Id_affaire, array());
                $idAffaire = $affaire->Id_affaire;
                $idAgence = $affaire->Id_agence;
                $idTypeContrat = $affaire->Id_type_contrat;
                $idPole = $affaire->Id_pole;
            } else {
                $affaire = new Opportunite($this->Id_affaire, array());
                $idAffaire = $affaire->reference_affaire;
                $idAgence = Agence::getIdAgence($affaire->Id_agence);
                $idTypeContrat = TypeContrat::getIdTypeContrat($affaire->Id_type_contrat);
                $idPole = Pole::getIdPole($affaire->Id_pole);
            }
            $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
            list($advFirstName) = explode('.', $advMail);
            $advFirstName = ucfirst($advFirstName);
            $advNumber = $this->getDestinataireNumber(1, $advMail);
            if ($ressource->type_ressource == 'MAT') {
                if ($this->Id_ressource == 'MAT') {
                    $mat = 'du matériel';
                } elseif ($this->Id_ressource == 'LIC') {
                    $mat = 'de la licence';
                } elseif ($this->Id_ressource == 'LOG') {
                    $mat = 'du logiciel';
                }
                if ($idTypeContrat == 3 && $idPole == 3) {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié par le service formation et certaines informations sont incorrects ou manquantes.<br />
                                Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                                Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                               Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié par le service ADV et certaines informations sont incorrects ou manquantes.<br />
                               Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                               Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                               ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            } else {
                if ($this->statut == 'U' || $this->statut == 'I') {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié par un responsable CDS et certaines informations sont incorrects ou manquantes.<br />
                        Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                        Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                        ' . $advFirstName . ' du CDS reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                } else {
                    if ($idTypeContrat == 3 && $idPole == 3) {
                        $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                    Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié par le service formation et certaines informations sont incorrects ou manquantes.<br />
                                    Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                                    Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                    } else {
                        $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                    Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été vérifié par le service ADV et certaines informations sont incorrects ou manquantes.<br />
                                    Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                                    Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                                    ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                    }
                }
            }
        } else {
            if (!$ressource->isStaff()) {
                $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
                $advNumber = $this->getDestinataireNumber(1, $advMail);
                list($advFirstName) = explode('.', $advMail);
                $advFirstName = ucfirst($advFirstName);
                if ($this->statut == 'U' || $this->statut == 'I') {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été vérifié par un responsable CDS et certaines informations sont incorrects ou manquantes.<br />
                        Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                        Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                        ' . $advFirstName . ' du CDS reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été vérifié par le service ADV et certaines informations sont incorrects ou manquantes.<br />
                        Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                        Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                        ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            } else {
                $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                            Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été vérifié et certaines informations sont incorrects ou manquantes.<br />
                            Voici la raison du refus : "' . $raison . '"' . $com . '.<br />
                            Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '</a>';
            }
        }

        if (!$ressource->isStaff()) {
            $sender = $advMail;
            $hdrs = array(
                'From' => $sender,
                'Subject' => 'Retour du contrat délégation n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail,
                'Cc' => $sender
            );
        } else {
            $sender = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr';
            $hdrs = array(
                'From' => $sender,
                'Subject' => 'Retour du contrat délégation n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail,
                'Cc' => $sender
            );
        }
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setHTMLBody($textCom);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        // Create the mail object using the Mail::factory method
        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        $send = $mail_object->send(array((new Utilisateur($this->createur,array()))->mail, $sender), $hdrs, $body);

        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
    }

    /**
     * Réouverture d'un contrat délégation
     */
    public function reopen($idReouverture, $commentaire) {
        $db = connecter();
        $db->query('UPDATE contrat_delegation SET statut="R", Id_reouverture_cd = ' . $idReouverture . ', commentaire_reouverture = "' . $commentaire . '"
                    WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));

        $utilisateur = new Utilisateur($this->createur, array());
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, array());
        $com = ($commentaire) ? ' ainsi qu\'un commentaire : ' . $commentaire : '';
        $raison = $this->getReopenCauseLibelle();
        if ($this->Id_affaire != 0) {
            if (is_numeric($this->Id_affaire)) {
                $affaire = new Affaire($this->Id_affaire, array());
                $idAffaire = $affaire->Id_affaire;
                $idAgence = $affaire->Id_agence;
                $idTypeContrat = $affaire->Id_type_contrat;
                $idPole = $affaire->Id_pole;
            } else {
                $affaire = new Opportunite($this->Id_affaire, array());
                $idAffaire = $affaire->reference_affaire;
                $idAgence = Agence::getIdAgence($affaire->Id_agence);
                $idTypeContrat = TypeContrat::getIdTypeContrat($affaire->Id_type_contrat);
                $idPole = Pole::getIdPole($affaire->Id_pole);
            }
            $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
            list($advFirstName) = explode('.', $advMail);
            $advFirstName = ucfirst($advFirstName);
            $advNumber = $this->getDestinataireNumber(1, $advMail);
            if ($ressource->type_ressource == 'MAT') {
                if ($this->Id_ressource == 'MAT') {
                    $mat = 'du matériel';
                } elseif ($this->Id_ressource == 'LIC') {
                    $mat = 'de la licence';
                } elseif ($this->Id_ressource == 'LOG') {
                    $mat = 'du logiciel';
                }
                if ($idTypeContrat == 3 && $idPole == 3) {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été réouvert par le service formation.<br />
                                Voici la raison de la ré-ouverture : "' . $raison . '"' . $com . '.<br />
                                Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $mat . ' et concernant l\'affaire n° ' . $idAffaire . ' a été réouvert par le service ADV.<br />
                                Voici la raison de la ré-ouverture : "' . $raison . '"' . $com . '.<br />
                                Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                                ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            } else {
                if ($idTypeContrat == 3 && $idPole == 3) {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été réouvert par le service formation.<br />
                                Voici la raison de la ré-ouverture : "' . $raison . '"' . $com . '.<br />
                                Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a>';
                } else {
                    $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                                Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' et concernant l\'affaire n° ' . $idAffaire . ' a été réouvert par le service ADV.<br />
                                Voici la raison de la ré-ouverture : "' . $raison . '"' . $com . '.<br />
                                Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegation&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                                ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
                }
            }
        } else {
            $advMail = $_SESSION[SESSION_PREFIX.'logged']->mail;
            $advNumber = $this->getDestinataireNumber(1, $advMail);
            list($advFirstName) = explode('.', $advMail);
            $advFirstName = ucfirst($advFirstName);
            $textCom = 'Bonjour ' . $utilisateur->prenom . ',<br /><br /> 
                        Le contrat délégation n° ' . $this->Id_contrat_delegation . ' pour ' . $ressource->prenom . ' ' . $ressource->nom . ' a été réouvert par le service ADV.<br />
                        Voici la raison de la ré-ouverture : "' . $raison . '"' . $com . '.<br />
                        Celui-ci est modifiable à l\'adresse : <a href="' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '">' . BASE_URL . 'com/index.php?a=modifierContratDelegationHorsAffaire&Id=' . $this->Id_contrat_delegation . '</a><br /><br />
                        ' . $advFirstName . ' de l\'ADV reste joignable au poste interne ' . $advNumber . ' ou à l\'adresse mail ' . $advMail;
        }

        if (!$ressource->isStaff()) {
            $sender = $advMail;
            $hdrs = array(
                'From' => $sender,
                'Subject' => 'Ré-ouverture du contrat délégation n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail,
                'Cc' => $sender
            );
        } else {
            $sender = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr';
            $hdrs = array(
                'From' => $sender,
                'Subject' => 'Ré-ouverture du contrat délégation n°' . $this->Id_contrat_delegation,
                'To' => (new Utilisateur($this->createur,array()))->mail,
                'Cc' => $sender
            );
        }
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setHTMLBody($textCom);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        // Create the mail object using the Mail::factory method
        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        $send = $mail_object->send(array((new Utilisateur($this->createur,array()))->mail, $sender), $hdrs, $body);

        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
    }

    /**
     * Mise à jour de la date d'envoi du contrat délégation
     * 
     */
    public function updateSendDate() {
        $db = connecter();
        $db->query('UPDATE contrat_delegation SET date_envoi = "' . DATETIME . '" WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
    }

    /**
     * Suppression d'un contrat délégation
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM contrat_delegation WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
        $db->query('DELETE FROM cd_indemnite WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation));
        $ligne = $db->query('SELECT Id_ordre_mission FROM ordre_mission WHERE Id_cd=' . mysql_real_escape_string((int) $this->Id_contrat_delegation . ''))->fetchRow();
        $odm = new OrdreMission($ligne->id_ordre_mission, array());
        $odm->delete();

        if ($this->Id_affaire != 0) {
            $affaire = new Affaire($this->Id_affaire, array());
            $cds = $affaire->getCDList();
            if (count($cds) > 0) {
                $a = array();
                for ($i = 0; $i < count($cds); $i++) {
                    $cd = new ContratDelegation($cds[$i], array());
                    array_push($a, DateMysqltoFr($cd->fin_mission, 'mysql'));
                }
                $planning = new Planning($affaire->Id_planning, array());
                $d = max($a);
                $planning->date_fin_commande = $d;
                $planning->date_fin_previsionnelle = $d;
                $planning->save();
            }
        }
    }

    /**
     * Affichage de l'identifiant de l'affaire
     *
     * @param int Identifiant du contrat délégation
     *
     * @return int
     */
    public static function getIdAffaire($Id_cd) {
        $db = connecter();
        $r = $db->query('SELECT reference_affaire, Id_affaire FROM contrat_delegation WHERE Id_contrat_delegation=' . mysql_real_escape_string((int) $Id_cd))->fetchRow();
        if ($r->reference_affaire)
            return $r->reference_affaire;
        else
            return $r->id_affaire;
    }
    
    /**
     * Affichage de la liste des raisons de refus
     *
     * @param string Id_agence
     * @param int service
     *
     * @return string
     */
    public function getRejectCauseList($rejectCause = null) {
        $metier[$this->Id_cause_refus] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_refus_cd, libelle FROM refus_cd ORDER BY ordre');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_refus_cd . ' ' . $metier[$ligne->id_refus_cd] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    /**
     * Renvoi la rasion du refus
     *
     * @return int
     */
    public function getRejectCauseLibelle() {
        $db = connecter();
        $r = $db->query('SELECT rcd.libelle FROM contrat_delegation cd
                         INNER JOIN refus_cd rcd ON cd.Id_refus_cd = rcd.Id_refus_cd
                         WHERE cd.Id_contrat_delegation=' . mysql_real_escape_string($this->Id_contrat_delegation))->fetchOne();
        return $r;
    }
    
    /**
     * Renvoi la rasion de la ré-ouverture
     *
     * @return int
     */
    public function getReopenCauseLibelle() {
        $db = connecter();
        $r = $db->query('SELECT rcd.libelle FROM contrat_delegation cd
                         INNER JOIN refus_cd rcd ON cd.Id_reouverture_cd = rcd.Id_refus_cd
                         WHERE cd.Id_contrat_delegation=' . mysql_real_escape_string($this->Id_contrat_delegation))->fetchOne();
        return $r;
    }
    
    /**
     * Retourne l'identifiant du 1er contrat dupliqué trouvé
     *
     * @return int
     */
    public function getIdDuplicated() {
        $db = connecter();
        $r = $db->query('SELECT Id_contrat_delegation FROM contrat_delegation WHERE Id_duplication=' . mysql_real_escape_string((int) $this->Id_contrat_delegation))->fetchRow();
        return $r->id_contrat_delegation;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showIdCase($record) {
        if ($record['reference_affaire']) {
            if ($_GET['type'] == 'CSV')
                return $record['reference_affaire'];
            else if (is_numeric($record['id_affaire']))
                return '<a onclick="window.open(\'../com/index.php?a=afficherAffaire&Id_affaire=' . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['reference_affaire'] . '</a>';
            else
                return '<a onclick="window.open(\'' . SF_URL . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['reference_affaire'] . '</a>';
        }
        else if ($record['id_affaire']) {
            if ($_GET['type'] == 'CSV')
                return $record['id_affaire'];
            else if (is_numeric($record['id_affaire']))
                return '<a onclick="window.open(\'../com/index.php?a=afficherAffaire&Id_affaire=' . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['id_affaire'] . '</a>';
            else
                return '<a onclick="window.open(\'' . SF_URL . $record['id_affaire'] . '\')" href="javascript:void(0)">' . $record['id_affaire'] . '</a>';
        }
        return 'HA';
    }

    public function showName($record) {
        if ($record['reference_affaire'] || $record['id_affaire']) {
            return $record['intitule'];
        } else {
            return 'Hors affaire';
        }
    }

    public function showResource($record) {
        if ($record['st_nom'] && $record['st_prenom']) {
            $personne = $record['st_nom'] . ' ' . $record['st_prenom'] . ' (Sous-Traitant)';
        } elseif ($record['id_ressource'] == 'MAT' || $record['id_ressource'] == 'LOG' || $record['id_ressource'] == 'LIC') {
            $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], array());
            $personne = '<p class="rowrouge">' . $ressource->getName() . '</p>';
        } else {
            if($record['nom_ressource']) {
                return $record['nom_ressource'] . ' ' . $record['prenom_ressource'];
            }
            else {
                $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], array());
                $personne = $ressource->getName();
            }
        }
        return $personne;
    }

    public function showButtons($record) {
        $msgEnvoiMail = 'Envoyer par mail aux services concernés ?';
        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        $htmlAdmin = $htmlEdit = $htmlModif = '';
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(4, 5, 18, 19))) {//ADV
            if ($record['id_affaire'] == 0) {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if ($record['statut'] == 'E') {
                    $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" onmouseover="return overlib(\'<div class=commentaire>Valider</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Retourner</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    $htmlAdmin = '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                } elseif ($record['statut'] == 'V') {
                    $htmlAdmin = '<td><input type="button" class="boutonExclamation" onclick="reopenCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Réouvrir</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    $htmlAdmin .= '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                }
            } else {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if ($record['statut'] == 'E') {
                    $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" /></td>';
                    $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" /></td>';
                    $htmlAdmin = '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                } elseif ($record['statut'] == 'V') {
                    $htmlAdmin = '<td><input type="button" class="boutonExclamation" onclick="reopenCD(' . $record['id_contrat_delegation'] . ', 1)" /></td>';
                    $htmlAdmin .= '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                }
            }
        } else if (//RESPONSABLES CDS
                (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(10)) && ($record['statut'] == 'I' || $record['statut'] == 'U')) ||
                (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(25)) && $record['statut'] == 'I') ||
                (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(26)) && $record['statut'] == 'U')
                        ) {
            if ($record['id_affaire'] == 0) {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" onmouseover="return overlib(\'<div class=commentaire>Valider</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Retourner</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlAdmin .= '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
            } else {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" /></td>';
                $htmlAdmin .= '<td><a href="../com/index.php?a=modifierContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';

            }
        } else if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {//ADMIN
            if ($record['id_affaire'] == 0) {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" onmouseover="return overlib(\'<div class=commentaire>Valider</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Retourner</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonExclamation" onclick="reopenCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Réouvrir</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><a href="javascript:void(0)" onclick="sendCD(\'' . $record['id_contrat_delegation'] . '\', \'undefined\', \'' . $msgEnvoiMail . '\')"><img src="' . IMG_MAIL . '" onmouseover="return overlib(\'<div class=commentaire>Envoyer CD</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin = '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin .= '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $record['id_contrat_delegation'] . '&amp;HA=1\') }"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if ($record['archive'] == 0) {
                    $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '" onmouseover="return overlib(\'<div class=commentaire>Archiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                } elseif ($record['archive'] == 1) {
                    $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '" onmouseover="return overlib(\'<div class=commentaire>Désarchiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                }
                $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../gestion/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" onmouseover="return overlib(\'<div class=commentaire>Supprimer</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
            } else {
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" onmouseover="return overlib(\'<div class=commentaire>Valider</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Retourner</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><input type="button" class="boutonExclamation" onclick="reopenCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Réouvrir</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                $htmlModif .= '<td><a href="javascript:void(0)" onclick="sendCD(\'' . $record['id_contrat_delegation'] . '\', \'undefined\', \'' . $msgEnvoiMail . '\')"><img src="' . IMG_MAIL . '" onmouseover="return overlib(\'<div class=commentaire>Envoyer CD</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin = '<td><a href="../fac/index.php?a=creerODM&amp;Id_cd=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_ODM . '" onmouseover="return overlib(\'<div class=commentaire>Créer ODM</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin .= '<td><a href="../com/index.php?a=modifierContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlModif .= '<td><a href="../com/index.php?a=changeCDOwnerForm&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_IMPERSONATE . '" onmouseover="return overlib(\'<div class=commentaire>Changer le propriétaire</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="duplicateCD(' . $record['id_contrat_delegation'] . '); return false;"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if ($record['archive'] == 0) {
                    $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '" onmouseover="return overlib(\'<div class=commentaire>Archiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                } elseif ($record['archive'] == 1) {
                    $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '" onmouseover="return overlib(\'<div class=commentaire>Désarchiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                }
                $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../gestion/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" onmouseover="return overlib(\'<div class=commentaire>Supprimer</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
            }
        } else {
            if ($record['id_affaire'] == 0) {//Hors affaire
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if ($record['embauche_staff'] == true && array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 12, 13, 24))) {
                    $msgEnvoiMail = 'Envoyer par mail aux services paie ?';
                    if ($record['statut'] == 'A') {
                        $htmlModif = '<td><a href="javascript:void(0)" onclick="sendCD(\'' . $record['id_contrat_delegation'] . '\', \'undefined\', \'' . $msgEnvoiMail . '\')"><img src="' . IMG_MAIL . '" onmouseover="return overlib(\'<div class=commentaire>Envoyer CD</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                    } elseif ($record['statut'] == 'E') {
                        $htmlModif = '<td><input type="button" class="boutonValider" onclick="validateCD(' . $record['id_contrat_delegation'] . ')" onmouseover="return overlib(\'<div class=commentaire>Valider</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                        $htmlModif .= '<td><input type="button" class="boutonRejeter" onclick="rejectCD(' . $record['id_contrat_delegation'] . ', 1)" onmouseover="return overlib(\'<div class=commentaire>Retourner</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                    } elseif ($record['statut'] == 'R') {
                        $htmlModif = '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                    } elseif ($record['statut'] == 'V') {
                        if ($record['archive'] == 0) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '" onmouseover="return overlib(\'<div class=commentaire>Archiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        } elseif ($record['archive'] == 1) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '" onmouseover="return overlib(\'<div class=commentaire>Désarchiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        }
                    }
                } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $record['createur']) {
                    $msgEnvoiMail = 'Envoyer par mail au service facturation ?';
                    if ($record['statut'] == 'A') {
                        $htmlModif = '<td><a href="javascript:void(0)" onclick="sendCD(\'' . $record['id_contrat_delegation'] . '\', \'undefined\', \'' . $msgEnvoiMail . '\')"><img src="' . IMG_MAIL . '" onmouseover="return overlib(\'<div class=commentaire>Envoyer CD</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin = '<td><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $record['id_contrat_delegation'] . '&amp;HA=1\') }"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" onmouseover="return overlib(\'<div class=commentaire>Supprimer</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    } elseif ($record['statut'] == 'E') {
                        $htmlModif = '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin = '<td><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $record['id_contrat_delegation'] . '&amp;HA=1\') }"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" onmouseover="return overlib(\'<div class=commentaire>Supprimer</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    } elseif ($record['statut'] == 'R') {
                        $htmlModif = '<td><a href="../com/index.php?a=modifierContratDelegationHorsAffaire&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin = '<td><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $record['id_contrat_delegation'] . '&amp;HA=1\') }"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" onmouseover="return overlib(\'<div class=commentaire>Supprimer</div>\', FULLHTML);" onmouseout="return nd();" /></td>';
                    } elseif ($record['statut'] == 'V') {
                        $htmlAdmin = '<td><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { location.replace(\'../com/index.php?a=dupliquerCD&amp;Id=' . $record['id_contrat_delegation'] . '&amp;HA=1\') }"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        if ($record['archive'] == 0) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '" onmouseover="return overlib(\'<div class=commentaire>Archiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        } elseif ($record['archive'] == 1) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '" onmouseover="return overlib(\'<div class=commentaire>Désarchiver</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        }
                    }
                }
            } else {
                $msgEnvoiMail = 'Envoyer par mail au service facturation ?';
                $htmlEdit = '<td><a href="index.php?a=editerContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_PDF . '" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_affaire']) || $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $record['createur']) {//Si a les droits de modif ou si est proprio
                    if ($record['statut'] == 'A') {
                        $htmlModif = '<td><a href="javascript:void(0)" onclick="sendCD(\'' . $record['id_contrat_delegation'] . '\', \'undefined\', \'' . $msgEnvoiMail . '\')"><img src="' . IMG_MAIL . '" onmouseover="return overlib(\'<div class=commentaire>Envoyer CD</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=modifierContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=changeCDOwnerForm&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_IMPERSONATE . '" onmouseover="return overlib(\'<div class=commentaire>Changer le propriétaire</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="javascript:void(0)" onclick="duplicateCD(' . $record['id_contrat_delegation'] . '); return false;"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
                    } elseif ($record['statut'] == 'E') {
                        $htmlModif = '<td><a href="../com/index.php?a=modifierContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '"></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=changeCDOwnerForm&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_IMPERSONATE . '" onmouseover="return overlib(\'<div class=commentaire>Changer le propriétaire</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="javascript:void(0)" onclick="duplicateCD(' . $record['id_contrat_delegation'] . '); return false;"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
                    } elseif ($record['statut'] == 'R') {
                        $htmlModif = '<td><a href="../com/index.php?a=modifierContratDelegation&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_EDIT . '" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="../com/index.php?a=changeCDOwnerForm&amp;Id=' . $record['id_contrat_delegation'] . '"><img src="' . IMG_IMPERSONATE . '" onmouseover="return overlib(\'<div class=commentaire>Changer le propriétaire</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlModif .= '<td><a href="javascript:void(0)" onclick="duplicateCD(' . $record['id_contrat_delegation'] . '); return false;"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
                    } elseif ($record['statut'] == 'V') {
                        $htmlModif .= '<td><a href="javascript:void(0)" onclick="duplicateCD(' . $record['id_contrat_delegation'] . '); return false;"><img src="' . IMG_COPY . '" onmouseover="return overlib(\'<div class=commentaire>Dupliquer</div>\', FULLHTML);" onmouseout="return nd();" /></a></td>';
                        if ($record['archive'] == 0) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a></td>';
                        } elseif ($record['archive'] == 1) {
                            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_contrat_delegation'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '"></a></td>';
                        }
                    }
                }
            }
        }
        return $htmlEdit . $htmlModif . $htmlAdmin;
    }

    /**
     * Affichage d'un tableau contenant la liste des collaborateurs n'ayant pas de contrat deleg à une certaine date
     *
     * @return string
     */
    public function getCollabWithoutContratDeleg($date = null, $Id_agence = null, $cds = null, $origine = null, $withHa = null, $cdstatus = null, $retirerAbsent = null, $retirerStaff = 1, $output = array('type' => 'TABLE')) {
        if (!$date) {
            $date = date('d-m-Y');
        }
        $arguments = array('date','Id_agence','cds','origine','withha','cdstatus','retirerabsent','retirerstaff');
        $columns = array(array('Id_ressource', 'ARS_RESSOURCE'), array('Nom', 'PSA_NOM'), array('Societe', 'SOCIETE'), array('Responsable absence', 'RABS_NOM'),array('Etablissement', 'ET_LIBELLE'), array('Date entrée', 'PSA_DATEENTREE'), array('Date sortie', 'PSA_DATESORTIE'));
        
        $db = connecter();
        $dbCegid = connecter_cegid();
        
        $tempTableResName = 'tempRes';
        $tempTableHaName = 'tempHa';
        $premisseCegid = 'DECLARE @'.$tempTableResName.' TABLE (res varchar(20) UNIQUE) DECLARE @'.$tempTableHaName.' TABLE(res varchar(20) UNIQUE) ';
        
        //on retrouve dabord tous les collab en mission en ce moment la
        $requete = 'SELECT Id_ressource,type_ressource,Id_ressource_sal,Id_affaire FROM contrat_delegation cd WHERE (type_ressource != "ST") AND (debut_mission <= "'.DateMysqltoFr($date, 'mysql').'" AND fin_mission >= "'.DateMysqltoFr($date, 'mysql').'") AND ';
        if ($cdstatus == 2) {
            $requete .= '(statut = "V" OR statut = "I" OR statut = "U" OR statut = "E" OR statut = "A" OR statut = "R")';
        } elseif ($cdstatus == 1) {
            $requete .= '(statut = "V" OR statut = "I" OR statut = "U" OR statut = "E")';
        } else {
            $requete .= '(statut = "V")';
        }
        $result = $db->query($requete);
        
        $alreadyRes = array();
        while ($ligne = $result->fetchRow()) {
            if ($ligne->type_ressource != 'SAL') {
                $resId = $ligne->id_ressource_sal;
            } else {
                $resId = $ligne->id_ressource;
            }
            if (!in_array($resId, $alreadyRes)) {
                $alreadyRes[] = $resId;
                $premisseCegid .= 'begin try insert into @'.$tempTableResName.' values (\''.$resId.'\') end try begin catch end catch ';
            }
            
        }
                
        //on retrouve dabord tous les collab en HA
        $requete = 'SELECT Id_ressource,type_ressource,Id_ressource_sal,Id_affaire FROM contrat_delegation cd WHERE (type_ressource != "ST") AND (Id_affaire = \'\') AND ';
        if ($cdstatus == 2) {
            $requete .= '(statut = "V" OR statut = "I" OR statut = "U" OR statut = "E" OR statut = "A" OR statut = "R")';
        } elseif ($cdstatus == 1) {
            $requete .= '(statut = "V" OR statut = "I" OR statut = "U" OR statut = "E")';
        } else {
            $requete .= '(statut = "V")';
        }
        $result = $db->query($requete);
        
        $alreadyHa = array();
        while ($ligne = $result->fetchRow()) {
            if ($ligne->type_ressource != 'SAL') {
                $resId = $ligne->id_ressource_sal;
            } else {
                $resId = $ligne->id_ressource;
            }
            if (!in_array($resId, $alreadyHa)) {
                $alreadyHa[] = $resId;
                $premisseCegid .= 'begin try insert into @'.$tempTableHaName.' values (\''.$resId.'\') end try begin catch end catch ';
            }
        }
        
        $i = 0;
        foreach ($_SESSION['cegid_databases'] as $cegid_database) {
            $requeteCegid .= ($i != 0) ? ' UNION' : '';
            $requeteCegid .= ' SELECT "' . $cegid_database . '" AS SOCIETE, PSE_EMAILPROF,ARS_AUXILIAIRE,ARS_RESSOURCE,ARS_TYPERESSOURCE,ARS_LIBELLE,ARS_LIBELLE2,ARS_ADRESSE1,ARS_ADRESSE2,
                            ARS_ADRESSE3,ARS_CODEPOSTAL,ARS_VILLE,ARS_TELEPHONE,ARS_TELEPHONE2,SAL.PSA_LIBELLE,SAL.PSA_PRENOM,SAL.PSA_NOMJF,SAL.PSA_NUMEROSS,
                            SAL.PSA_SALARIE,SAL.PSA_ADRESSE1,SAL.PSA_ADRESSE2,SAL.PSA_ADRESSE3,SAL.PSA_CODEPOSTAL,SAL.PSA_VILLE,SAL.PSA_PAYS,SAL.PSA_PAYSNAISSANCE,SAL.PSA_LIBELLEEMPLOI,SAL.PSA_TRAVAILN1,
                            SAL.PSA_TELEPHONE, SAL.PSA_PORTABLE, SAL.PSA_DATENAISSANCE, SAL.PSA_COMMUNENAISS, SAL.PSA_NATIONALITE,SAL.PSA_SITUATIONFAMIL,SAL.PSA_DATEENTREE,SAL.PSA_DATESORTIE,SAL.PSA_CIVILITE
                            , PSE_CODESERVICE,CC2.CC_LIBELLE,CC3.CC_LIBELLE AS cc3_libelle,CC5.CC_LIBELLE AS POLE,CO3.CO_LIBELLE,PY4.PY_NATIONALITE, ET_ETABLISSEMENT ,ET_LIBELLE, 
                            VE_CDS.V_SECTION AS V_SECTION_CDS, VE_STAFF.V_SECTION AS V_SECTION_STAFF, NOM_RESPONSABS + " - " + RESPONSABS AS RABS_NOM, SAL.PSA_TRAVAILN3,
                            CASE WHEN EXISTS(SELECT * FROM ' . $cegid_database . '.DBO.ABSENCESALARIE WHERE "'.DateMysqltoFr($date, 'mysql').'" >= PCN_DATEDEBUTABS AND "'.DateMysqltoFr($date, 'mysql').'" <= PCN_DATEFINABS AND PCN_SALARIE = SAL.PSA_SALARIE) THEN 1 ELSE 0 END AS ABSENT
                            FROM ' . $cegid_database . '.DBO.RESSOURCE
                            LEFT JOIN ' . $cegid_database . '.DBO.SALARIES AS SAL ON ars_salarie= SAL.psa_salarie
                            LEFT JOIN ' . $cegid_database . '.DBO.pgaffectrolerh AS N1 ON N1.pfh_salarie = SAL.psa_salarie AND N1.PFH_ROLERH="RES" AND N1.PFH_MODULERH="ABS"
                            LEFT JOIN ' . $cegid_database . '.DBO.PGDEPORTSAL ON pse_salarie = N1.pfh_salarie
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.CHOIXCOD CC2 ON PSA_LIBELLEEMPLOI=CC2.CC_CODE AND CC2.CC_TYPE="PLE"
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.CHOIXCOD CC3 ON PSA_LIBREPCMB4=CC3.CC_CODE AND CC3.CC_TYPE="PL4"
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.COMMUN CO3 ON PSA_SITUATIONFAMIL=CO3.CO_CODE AND CO3.CO_TYPE="PSF"
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.PAYS PY4 ON PSA_NATIONALITE=PY4.PY_PAYS 
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.CHOIXCOD CC5 ON PSA_LIBREPCMB1=CC5.CC_CODE AND CC5.CC_TYPE="PL1"
                            LEFT OUTER JOIN ' . $cegid_database . '.DBO.ETABLISS ET ON ET_ETABLISSEMENT = SAL.PSA_ETABLISSEMENT
                            LEFT JOIN ' . $cegid_database . '.DBO.VENTIL VE_CDS ON VE_CDS.V_COMPTE = SAL.PSA_SALARIE AND (VE_CDS.V_NATURE LIKE "SA1" AND (SUBSTRING(VE_CDS.V_SECTION,4,1) = "7" OR SUBSTRING(VE_CDS.V_SECTION,4,1) = "8" OR SUBSTRING(VE_CDS.V_SECTION,4,1) = "9" ))
                            LEFT JOIN ' . $cegid_database . '.DBO.VENTIL VE_STAFF ON VE_STAFF.V_COMPTE = SAL.PSA_SALARIE AND (VE_STAFF.V_NATURE LIKE "SA1" AND VE_STAFF.V_TAUXMONTANT > 50 AND (RIGHT(VE_STAFF.V_SECTION, 2) = "DS"  OR RIGHT(VE_STAFF.V_SECTION, 2) = "DP") )
                            WHERE ARS_FERME != "X"';
            $i++;
        }
        //on cherche tous les collab actuellement disponible sauf ceux trouvées précédemment
        $requeteCegid = $premisseCegid.' SELECT ha.res as ha ,V_SECTION_CDS ,V_SECTION_STAFF ,SOCIETE, PSA_SALARIE, PSA_LIBELLE + " " + PSA_PRENOM + " - " + PSA_SALARIE AS PSA_NOM, ARS_RESSOURCE, PSA_DATEENTREE, PSA_DATESORTIE, ET_LIBELLE, RABS_NOM, ABSENT FROM ('.$requeteCegid.') AS SALARIES'
                . ' LEFT JOIN (SELECT res FROM @'.$tempTableResName.') as tr ON tr.res = ARS_RESSOURCE LEFT JOIN (SELECT res FROM @'.$tempTableHaName.') as ha ON ha.res = ARS_RESSOURCE'
                . ' WHERE tr.res IS NULL AND PSA_DATEENTREE < "'.DateMysqltoFr($date, 'mysql').'" AND (PSA_DATESORTIE > "'.DateMysqltoFr($date, 'mysql').'" OR PSA_DATESORTIE = "1900-01-01 00:00:00.000") ';
        
        if ($cds === '1') {
            $requeteCegid .= ' AND V_SECTION_CDS IS NOT NULL'; 
        } else {
            if($cds === '0') {
                $requeteCegid .= ' AND V_SECTION_CDS IS NULL'; 
            }
        }
        
        if ($retirerStaff == 1) {
             $requeteCegid .= ' AND V_SECTION_STAFF IS NULL AND PSA_TRAVAILN1 != "001"'; 
        }
        
        if ($Id_agence != null && preg_match('#[A-Z]{3}#', $Id_agence)) {
            $requeteCegid .= ' AND ET_ETABLISSEMENT = "'. $Id_agence.'"'; 
        }
        
        if ($origine == 'PWS') {
            $requeteCegid .= ' AND PSA_TRAVAILN3 IN("030", "031", "032") '; 
        } elseif ($origine == 'PDS') {
            $requeteCegid .= ' AND PSA_TRAVAILN3 IN("026") '; 
        } elseif ($origine == 'FINATEL') {
            $requeteCegid .= ' AND PSA_TRAVAILN3 IN("021") '; 
        } elseif ($origine == 'PROSERVIA') {
            $requeteCegid .= ' AND PSA_TRAVAILN3 NOT IN("021", "026", "030", "031", "032") '; 
        }
        
        if ($withHa === '0') {
            $requeteCegid .= ' AND ha.res IS NULL '; 
        }
        
        if ($retirerAbsent == 1) {
             $requeteCegid .= ' AND ABSENT = 0 '; 
         }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output') {
                $params .= $arguments[$key] . '=' . $value . '&';
            }
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=ARS_RESSOURCE';
            $orderBy = 'ARS_RESSOURCE';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requeteCegid .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        
        
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                    'fileName' => '#%d', 'urlVar' => 'page',
                    'onclick' => 'afficherCollabSansCD({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                    'perPage' => TAILLE_LISTE, 'delta' => DELTA);

            $paged_data = Pager_Wrapper_MDB2($dbCegid, $requeteCegid, $pager_params);
            
            //var_dump($paged_data);
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '<p>Seulement les contrats délégation validés sont pris en compte.</p>
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterCollabSansCD&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s), (*) avec un contrat delegation Hors Affaire</span></p>
                    <table class="hovercolored">
                        <tr>';
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
                            $img['ARS_RESSOURCE'] = '<img src="' . IMG_DESC . '" />';
                        } else {
                            $direction = 'ASC';
                        }
                    if ($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherCollabSansCD({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;

                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td><a href="../membre/index.php?a=consulterCD&Id_ressource=' . $ligne['ars_ressource'] . '">' . $ligne['ars_ressource'] . '</a></td>
                            <td>' . $ligne['psa_nom'] . (($ligne['ha'] != null) ? ' *' : '') .'</td>
                            <td>' . $ligne['societe'] . '</td>
                            <td>' . $ligne['rabs_nom'] . '</td>
                            <td>' . $ligne['et_libelle'] . '</td>
                            <td>' . DateMysqltoFr($ligne['psa_dateentree'], 'fr') . '</td>
                            <td>' . ((DateMysqltoFr($ligne['psa_datesortie'], 'fr') == '01-01-1900') ? '-' : DateMysqltoFr($ligne['psa_datesortie'], 'fr')) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $dbCegid->query($requeteCegid);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="contrats_delegation.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['ars_ressource'] . ';';
                echo '"' . $ligne['psa_nom'] . '";';
                echo '"' . $ligne['societe'] . '";';
                echo '"' . $ligne['rabs_nom'] . '";';
                echo '"' . $ligne['et_libelle'] . '";';
                echo DateMysqltoFr($ligne['psa_dateentree'], 'fr') . ';';
                echo ((DateMysqltoFr($ligne['psa_datesortie'], 'fr') == '01-01-1900') ? '-' : DateMysqltoFr($ligne['psa_datesortie'], 'fr')) . ';';
                echo PHP_EOL;
            }
        } elseif ($output['type'] == 'CSVMAIL') {
            $result = $dbCegid->query($requeteCegid);
            $csv = '';
            foreach ($columns as $value) {
                $csv .= $value[0] . ';';
            }
            $csv .= PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                $csv .= $ligne['ars_ressource'] . ';';
                $csv .= '"' . $ligne['psa_nom'] . '";';
                $csv .= '"' . $ligne['societe'] . '";';
                $csv .= '"' . $ligne['rabs_nom'] . '";';
                $csv .= '"' . $ligne['et_libelle'] . '";';
                $csv .= DateMysqltoFr($ligne['psa_dateentree'], 'fr') . ';';
                $csv .= ((DateMysqltoFr($ligne['psa_datesortie'], 'fr') == '01-01-1900') ? '-' : DateMysqltoFr($ligne['psa_datesortie'], 'fr')) . ';';
                $csv .= PHP_EOL;
            }
            return $csv;
        }
        return $html;
    }
    
    /**
     * Affichage du formulaire permettant de choisir la date
     *
     * @return string
     */
    public function getCollabWithoutContratDelegForm($date = null,$id_agence = '') {
        if (!$date) {
            $date = date('d-m-Y');
        }

        $agence = new Agence($id_agence,array());
        $html = '
                <label for="date">Date : </label><input onfocus="showCalendarControl(this)" type="text" name="date" id="date" value="'.$date.'"></input>'
                . '<select id="Id_agence" name="Id_agence" >
			<option value="">Par agence</option>
			<option value="">----------------------------</option>
			' . $agence->getList() . '
		</select>'
                . '<select id="cds" name="cds" >
			<option value="">Par CDS</option>
                        <option value="">----------------------------</option>
			<option value="1">Avec CDS</option>
                        <option value="0">Sans CDS</option>
		</select>'
		. '<select id="origine" name="origine" >
                        <option value="">-</option>
					<option value="PWS" >PWS</option>
                    <option value="PDS" >PDS</option>
                    <option value="FINATEL" >FINATEL</option>
		</select>'
		. '<select id="withha" name="withha" >
			<option value="1">Avec Hors Affaire</option>
                        <option value="0">Sans Hors Affaire</option>
		</select>'
		. '<select id="cdstatus" name="cdstatus" >
					<option value="0">Uniquement CD validé</option>
					<option value="1">CD envoyé ou validé</option>
					<option value="2">CD envoyé ou validé ou brouillon ou retourné</option>
		</select>'
		 . '<select id="retirerabsent" name="retirerabsent" >
 			<option value="1">Retirer les absent</option>
                         <option value="0">Garder les absent</option>
 		</select>'
 		 . '<select id="retirerstaff" name="retirerstaff" >
 			<option value="1">Retirer les staff</option>
                         <option value="0">Garder les staff</option>
 		</select>'
                . '<input type="button" onclick="afficherCollabSansCD()" value="Go !">';
        return $html;
    }
    
    /**
     * Affichage d'un tableau contenant la liste contrat deleg avec leur temps de travail et le temps de travail des ressources associés
     *
     * @return string
     */
    public function getContratDelegWorkTime($Id_cd, $Id_affaire, $createur, $Id_ressource, $month = '', $year = '',$output = array('type' => 'TABLE')) {
        $columns = array(array('Id Contrat délégation', 'Id_contrat_delegation'), array('Nom Ressource', 'none'), array('Statut', 'none'), array('Nom CD', 'intitule'), array('Date début CD', 'debut_mission'), array('Date fin CD', 'fin_mission'), array('Horaire CD', 'horaire'));
        
        if ($month == '') {
            $month = (int)date('m');
            if ($month < 10) {
                $month = '0'.$month;
            }
        }
        if ($year == '' || $year < 1994) {
            $year = (int)date('Y');
        }
        
        //Calcul des semaines, on utilises seulement les semaines ayant leur dimanche dans ce mois
        //on recupère le premier dimanche du mois, puis on récupère les 4 
        $weeks = array();
        $weekNumberToWeekId = array();
        $i=0;
        $firstSunday = strtotime($year.'-'.$month.'-01');
        do
        {
            if(date("w", $firstSunday) != 0)
            {
                $firstSunday += (24 * 3600); // add 1 day
            }
        } while(date("w", $firstSunday) != 0);
        
        $columns[] = array('<', 'previousmonth');
        while ($i < 5) {
            $weeks[$i]['sunday'] = $firstSunday + ($i * (7 * 24 * 3600));
            $weeks[$i]['txt'] = date('d/m/Y', ($weeks[$i]['sunday'] - (6 * 24 * 3600))) . ' à ' . date('d/m/Y', $weeks[$i]['sunday']);
            $weekNumberToWeekId[(int)date('W', $weeks[$i]['sunday'])] = $i;
            $columns[] = array($weeks[$i]['txt'], 'none');
            $i++;
        }
        $columns[] = array('>', 'nextmonth');
        
        $db = connecter();
        $dbHor = connecter_cegid();
        
        //on retrouve dabord tous les contrat delegation
        $date_debut = date('Y-m-d',(strtotime($year.'-'.$month.'-01') - (24 * 3600)));
        $date_fin = date('Y-m-d', $weeks[4]['sunday']);
        $requete = 'SELECT intitule,debut_mission,fin_mission,horaire,agence,Id_contrat_delegation,Id_ressource,type_ressource,Id_ressource_sal,Id_affaire FROM contrat_delegation cd WHERE type_ressource = "SAL" AND ( ((debut_mission > "'.$date_debut.'" AND debut_mission < "'.$date_fin.'") OR (fin_mission > "'.$date_debut.'" AND fin_mission < "'.$date_fin.'")) OR (debut_mission < "'.$date_debut.'" AND fin_mission > "'.$date_fin.'") )';//(((DC > DM && DC < FM) || (FC > DM && FC < FM)) || (DC<DM && FC > FM)) 
        
        if ($Id_cd) {
            $requete .= ' AND Id_contrat_delegation= ' . (int) $Id_cd;
        }
        if ($Id_affaire) {
            $requete .= ' AND (Id_affaire="' . $Id_affaire . '" OR reference_affaire="' . $Id_affaire . '")';
        }
        if ($createur) {
            $requete .= ' AND createur= "' . $createur . '"';
        }
        if ($Id_ressource) {
            $requete .= ' AND (Id_ressource= "' . $Id_ressource . '" OR Id_ressource_sal= "' . $Id_ressource . '")';
        }
        
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output') {
                $params .= $arguments[$key] . '=' . $value . '&';
            }
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=Id_contrat_delegation';
            $orderBy = 'Id_contrat_delegation';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherContratDelegWorkTime({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);

        $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
        
        //Table temporaire cegid
        $tempTableCDName = 'tempCD';
        $premisseHor = 'DECLARE @'.$tempTableCDName.' TABLE (res varchar(20),Id_contrat_delegation int, db datetime, df datetime) ';
        
        foreach ($paged_data['data'] as $ligne) {
             if ($ligne['id_ressource_sal'] != '') {
                $resId = $ligne['id_ressource_sal'];
            } else {
                $resId = $ligne['id_ressource'];
            }
            $premisseHor .= 'begin try insert into @'.$tempTableCDName.' values (\''.$resId.'\','.$ligne['id_contrat_delegation'].',\''.$ligne['debut_mission'].'\',\''.$ligne['fin_mission'].'\') end try begin catch end catch ';
        }
        
        $date_debut = date('Y-m-d', ($weeks[0]['sunday'] - (2 * 24 * 3600)));
        $date_fin = date('Y-m-d', ($weeks[4]['sunday'] + (24 * 3600)));
        $requeteHor = $premisseHor . ' SELECT cd.Id_contrat_delegation,etemptation.etemptation.hophjoup.MATRI,NOMPRE,EMCODE,hjpoinh as worked_time,datepart(ww,DAT) week,datepart(yy,DAT) as year
                            , CASE EMCATEGO WHEN "02" THEN "CADRE" ELSE "NON CADRE" END as statut
                            FROM [etemptation].[etemptation].[hopempl]
                            LEFT JOIN etemptation.etemptation.hophjoup ON etemptation.etemptation.hophjoup.MATRI = etemptation.etemptation.hopempl.MATRI
                            LEFT JOIN (SELECT * FROM @'.$tempTableCDName.') as cd ON cd.res = EMCODE
                            WHERE (etemptation.etemptation.hophjoup.INBADG = 1)
                            AND datepart(dw,DAT) = 7
                            AND DAT > "'.$date_debut.'" AND DAT < "'.$date_fin.'"
                            ORDER BY EMCODE,week';
        $resInfo = array();
        $result = $dbHor->query($requeteHor);
        //var_dump($result);
        while ($ligne = $result->fetchRow()) {
            if (!isset($resInfo[$ligne->id_contrat_delegation])) {
                $resInfo[$ligne->id_contrat_delegation] = array();
                $resInfo[$ligne->id_contrat_delegation]['nom'] = $ligne->nompre;
                $resInfo[$ligne->id_contrat_delegation]['statut'] = $ligne->statut;
                $resInfo[$ligne->id_contrat_delegation]['res'] = $ligne->emcode;
            }
            $resInfo[$ligne->id_contrat_delegation]['week'.$weekNumberToWeekId[(int)$ligne->week]] = ($ligne->worked_time / 60);
        }

        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            $html .= '<p class="pagination">' . $paged_data['links'] . '<span style="float:left">&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                <table class="hovercolored">
                    <tr>';
            foreach ($columns as $value) {
                $orderBy = $value[1];
                if ($value[1] == $output['orderBy']){
                    if ($output['direction'] == 'DESC') {
                        $direction = 'ASC';
                        $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'DESC';
                        $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                    } 
               }else if (!$output['orderBy']) {
                    $direction = 'ASC';
                    $img['Id_contrat_delegation'] = '<img src="' . IMG_DESC . '" />';
                } else {
                    $direction = 'ASC';
                }  
                switch ($value[1]) {
                    case 'nextmonth' :
                        $html .= '<th><a href="#" onclick="'
                            . 'if ($F(\'month\') == \'12\') { $(\'month\').value = \'01\';$(\'year\').value = parseInt($(\'year\').value)+1; } else { $(\'month\').value = (parseInt($(\'month\').value) +1) < 10 ? (\'0\' + (parseInt($(\'month\').value) +1)) : parseInt($(\'month\').value) +1; };'
                            . 'afficherContratDelegWorkTime({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $output['orderBy'] . '\', \'direction\' : \'' . $output['direction'] . '\'}]});">' . $value[0] . '</a></th>';
                        break;
                    case 'previousmonth' :
                        $html .= '<th><a href="#" onclick="'
                            . 'if ($F(\'month\') == \'01\') { $(\'month\').value = \'12\';$(\'year\').value = parseInt($(\'year\').value)-1; } else { $(\'month\').value = (parseInt($(\'month\').value) - 1) < 10 ? (\'0\' + (parseInt($(\'month\').value) -1)) : parseInt($(\'month\').value) -1; };'
                            . 'afficherContratDelegWorkTime({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $output['orderBy'] . '\', \'direction\' : \'' . $output['direction'] . '\'}]});">' . $value[0] . '</a></th>';
                        break;
                    case 'none' :
                        $html .= '<th>' . $value[0] . '</th>'; 
                        break;
                    default :
                        $html .= '<th><a href="#" onclick="afficherContratDelegWorkTime({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                        break;
                }                    
            }
            $html .= '</tr>';

            $i = 0;

            foreach ($paged_data['data'] as $ligne) {
                $hourCD = (float)$ligne['horaire'];
                $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                $html .= '
                    <tr ' . $j . '>
                        <td>'.$ligne['id_contrat_delegation'].'</td>
                        <td><a href="../membre/index.php?a=consulterCD&Id_ressource=' . $resInfo[$ligne['id_contrat_delegation']]['res'] . '">' . $resInfo[$ligne['id_contrat_delegation']]['nom'] . '</a></td>
                        <td>'.$resInfo[$ligne['id_contrat_delegation']]['statut'].'</td>                            
                        <td>'.$ligne['intitule'].'</td>    
                        <td>'.$ligne['debut_mission'].'</td>
                        <td>'.$ligne['fin_mission'].'</td>    
                        '.( $hourCD > 0 ? '<td>'.$ligne['horaire'].'</td>' : '<td class="worktime-alert-color">'.$ligne['horaire'].'</td>' ).'
                        <td></td>';
                foreach ($weeks as $id => $week) {
                    $class = '';
                    if ($hourCD > 0 && $resInfo[$ligne['id_contrat_delegation']]['week'.$id] > ($hourCD*1.02)) {
                        $class = 'worktime-warning-color';
                    }
                    if ($hourCD > 0 && $resInfo[$ligne['id_contrat_delegation']]['week'.$id] > ($hourCD*1.1)) {
                        $class = 'worktime-alert-color';
                    }
                    $html .= '<td class="'.$class.'">' . round($resInfo[$ligne['id_contrat_delegation']]['week'.$id],2) . '</td>';
                }
                        
                $html .= '<td></td>
                          </tr>';
                ++$i;
            }
            $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
        }
        return $html;
    }
    
    /**
     * @return string
     */
    public function getContratDelegWorkTimeForm($Id_cd ,$Id_affaire, $Id_createur, $Id_ressource, $month = '', $year = '') {
        if ($month == '') {
            $month = (int)date('m');
        }
        if ($year == '' || $year < 1994) {
            $year = (int)date('Y');
        }
        
        $html = 'Mois : <select id="month" name="month" >';
        $i = 0;
        while ($i < 12) {
            $i++;
            $monthTxt = $i;
            if ($monthTxt < 10) {
                $monthTxt = '0'.$monthTxt;
            }
            $html .= '<option value="'.$monthTxt.'" ';
            if ($i  == (int)$month) {
                $html .= ' selected = "selected" ';
            }
            $html .= '>'.$monthTxt.'</option>';
        }		
	$html .= '</select>'
                .' Année : <select id="year" name="year" >';
        $i = (int)date('Y') - 5;
        while ($i < ((int)date('Y') + 1)) {
            $i++;
            $html .= '<option value="'.$i.'" ';
            if ($i  == (int)$year) {
                $html .= ' selected = "selected" ';
            }
            $html .= '>'.$i.'</option>';
        }
	$html .= '</select>';

        $createur = new Utilisateur($Id_createur, array());
        $ressourceSal = RessourceFactory::create('SAL', $Id_ressource, array());
        $ressourceSt = RessourceFactory::create('ST', $Id_ressource, array());
        $ressourceInt = RessourceFactory::create('INT', $Id_ressource, array());
        $html .= '
		n° Contrat Délegation : <input id="Id_cd" type="text" onkeyup="afficherContratDelegWorkTime()" value="' . $Id_cd . '" size="5" />
                &nbsp;&nbsp;
		n° opportunité : <input id="Id_affaire" type="text" onkeyup="afficherContratDelegWorkTime()" value="' . $Id_affaire . '" size="5" />
                &nbsp;&nbsp;	
		<select id="createur" onchange="afficherContratDelegWorkTime()">
                    <option value="">Par créateur</option>
                    <option value="">----------------------------</option>
                    ' . $createur->getList("COM") . '
                    <option value="">----------------------------</option>
                    ' . $createur->getList("OP") . '
                </select>
		&nbsp;&nbsp;
		<select id="Id_ressource" onchange="afficherContratDelegWorkTime()">
                    <option value="">Par ressource</option>
                    ' . $ressourceSal->getList() . '
                    ' . $ressourceSt->getList() . '
                    ' . $ressourceInt->getList() . '
		</select>
                &nbsp;&nbsp';
        $html .= '<input type="button" onclick="afficherContratDelegWorkTime()" value="Go !">';
        return $html;
    }
    
    
    /**
     * teste si la ressource fait partie d'un CDS
     * renvoie le service CDS ou false
     * @return string
     */
    public function ressourceIsCds($typeRessource = null, $idRessource = null) {
        if ($typeRessource == null) {
            $typeRessource = $this->type_ressource;
        }
        if ($idRessource == null) {
            $idRessource = $this->Id_ressource;
        }
        //die(var_dump($idRessource.' '.$typeRessource));
        /*on teste si le collab vient d'un CDS*/
        $cds = false;
        if ( $typeRessource == 'SAL' || $typeRessource == 'CAN_TAL' || $typeRessource == 'CAN_AGC') {
           $dbCegid = connecter_cegid();
           $i=0;
           foreach ($_SESSION['cegid_databases'] as $cegid_database) {
               $requeteCegid .= ($i != 0) ? ' UNION' : '';
               $requeteCegid .= ' (SELECT V_SECTION FROM ' . $cegid_database . '.DBO.RESSOURCE
                   LEFT JOIN ' . $cegid_database . '.DBO.SALARIES ON ARS_SALARIE = PSA_SALARIE
                   LEFT JOIN ' . $cegid_database . '.DBO.VENTIL VE ON V_COMPTE = PSA_SALARIE 
                   WHERE (V_NATURE LIKE "SA1" AND (SUBSTRING(V_SECTION,4,1) = "7" OR SUBSTRING(V_SECTION,4,1) = "8" OR SUBSTRING(V_SECTION,4,1) = "9"))
                   AND ARS_RESSOURCE = "' . $idRessource . '") ';
               $i++;
           }
           $resultCegid = $dbCegid->query($requeteCegid);
           $ligne = $resultCegid->fetchRow();
           if ($ligne->v_section != null) {
               if (in_array($ligne->v_section, array('SIE9MPMC', 'SIE9VLMC', 'SIE9MCMC', 'SIE9ITMC', 'SIE9DMMC'))) {
                  $cds = 'CDSSU';
               }
               if (in_array($ligne->v_section, array('SIE9EXMC', 'SIE9ADMC', 'SIE9IGMC'))) {
                  $cds = 'CDSSI';
               }
           }
        }
        return $cds;
    }
    
    
    /**
     * Affichage du formulaire de recherche d'un contrat délégation
     *
     * @return string
     */
    public function refacturationForm() {
        /*
          if (empty($_SESSION['filtre']['createur'])) {
          $_SESSION['filtre']['createur'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
          }
         */
        if ($_SESSION['filtre']['archive']) {
            $archive[$_SESSION['filtre']['archive']] = 'selected="selected"';
        }
        $ressourceMat = RessourceFactory::create('MAT', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceTal = RessourceFactory::create('CAN_TAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSal = RessourceFactory::create('SAL', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceSt = RessourceFactory::create('ST', $_SESSION['filtre']['Id_ressource'], array());
        $ressourceInt = RessourceFactory::create('INT', $_SESSION['filtre']['Id_ressource'], array());
        $statut[$_SESSION['filtre']['statut']] = 'selected="selected"';
        $html = '
		n° Contrat Délegation : <input id="Id_cd" type="text" onkeyup="afficherCD()" value="' . $_SESSION['filtre']['Id_cd'] . '" size="5" />
                &nbsp;&nbsp;
		n° opportunité : <input id="Id_affaire" type="text" onkeyup="afficherCD()" value="' . $_GET['Id_affaire'] . '" size="5" />
                &nbsp;&nbsp;
                Mots clés : <input id="motclecd" type="text" onkeyup="afficherCD()" value="' . $_SESSION['filtre']['motclecd'] . '" />
		&nbsp;&nbsp;
		<select id="Id_ressource" onchange="afficherCD()">
                    <option value="">Par ressource</option>
                    ' . $ressourceMat->getList() . '
                    ' . $ressourceTal->getList() . '
                    ' . $ressourceSal->getList() . '
                    ' . $ressourceSt->getList() . '
                    ' . $ressourceInt->getList() . '
		</select>
                &nbsp;&nbsp;
		du <input id="debut" type="text" onfocus="showCalendarControl(this)" size="8" value=' . $_SESSION['filtre']['debut'] . ' >
		&nbsp;
		au <input id="fin" type="text" onfocus="showCalendarControl(this)"  size="8" value=' . $_SESSION['filtre']['fin'] . ' >
                    <input type="button" onclick="afficherRefacturationCD()" value="Go !">
';
        return $html;
    }
    
    /**
     * Rapport sur les infos de refacturation des CD
     *
     * @return string
     */
    public static function refacturationCD($Id_cd, $Id_affaire, $Id_ressource, $motcle = null, $debut, $fin, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_cd', 'Id_affaire', 'createur', 'Id_ressource', 'statut', 'archive', 'motcle', 'reference_affaire_mere', 'output');
        $columns = array(array('Id cd', 'Id_contrat_delegation'), array('Intitule', 'intitule'), array('Agence', 'agence'), array('Pôle', 'pole'), array('Refacturation', 'indemnites_ref'),
             array('Client', 'compte'), array('N°SalesForce', 'reference_affaire'), array('Affaire CEGID', 'reference_affaire'), array('BDC', 'reference_bdc'),
            array('Id Ressource', 'id_ressource'), array('Nom Ressource', 'id_ressource'), array('Date début mission', 'debut_mission'), array('Date fin mission', 'fin_mission'), 
            );
        $db = connecter();
        $requete = 'SELECT contrat_delegation.*,
		DATE_FORMAT(debut_mission, "%d-%m-%Y") as debut_mission_fr,
		DATE_FORMAT(fin_mission, "%d-%m-%Y") as fin_mission_fr
		FROM contrat_delegation WHERE archive = 0';
        
        if ($Id_cd) {
            $requete .= ' AND Id_contrat_delegation= ' . (int) $Id_cd;
        }
        if ($Id_affaire) {
            $requete .= ' AND (Id_affaire="' . mysql_escape_string($Id_affaire) . '" OR reference_affaire="' . mysql_escape_string($Id_affaire) . '")';
        }
        if ($Id_ressource) {
            $requete .= ' AND (Id_ressource= "' . mysql_escape_string($Id_ressource) . '" OR Id_ressource_sal= "' . mysql_escape_string($Id_ressource) . '")';
        }
        if ($debut && $fin) {
            $requete .= ' AND debut_mission BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }    
        $requete .= ' AND statut = "V"';
        if ($motcle) {
            $motclesplit = explode(' ', utf8_decode($motcle));
            foreach ($motclesplit as $i) {
                $requete .= ' AND ( compte LIKE "%' . $i . '%"
				OR contact1 LIKE "%' . $i . '%"
				OR contact2 LIKE "%' . $i . '%"
                                OR intitule LIKE "%' . $i . '%")';
            }
        }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=Id_contrat_delegation';
            $orderBy = 'Id_contrat_delegation';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        
        $dbCegid = connecter_cegid();
        $requeteCegid = 'SELECT DISTINCT AFF_AFFAIRE, AFF_DATEDEBUT, AFF_CHARLIBRE1, AFF_CHARLIBRE2, AFF_CHARLIBRE3 FROM dbo.AFFAIRE';
        $resultCegid = $dbCegid->query($requeteCegid);
        $tabAffaireCegid = array();
        while ($data = $resultCegid->fetchRow()) {
            $tabAffaireCegid[$data->aff_charlibre1] .= ','.$data->aff_affaire;
            $tabAffaireCegid[$data->aff_charlibre2] .= ','.$data->aff_affaire;
            $tabAffaireCegid[$data->aff_charlibre3] .= ','.$data->aff_affaire;
        }
        
        //id_ressource_sal ou id_ressource
        $dbCegid = connecter_cegid();
        $requeteCegid = 'SELECT * FROM dbo.RESSOURCE WHERE ARS_TYPERESSOURCE = "SAL"';
        $resultCegid = $dbCegid->query($requeteCegid);
        $tabRessourceCegid = array();
        while ($data = $resultCegid->fetchRow()) {
            $tabRessourceCegid[$data->ars_ressource] = array('id' => $data->ars_salarie, 'nom' => $data->ars_libelle.' '.$data->ars_libelle2);
        }
        
        $dbCegid = connecter_cegid();
        $requeteCegid = 'SELECT * FROM SALARIES';
        $resultCegid = $dbCegid->query($requeteCegid);
        $tabSalarieCegid = array();
        while ($data = $resultCegid->fetchRow()) {
            $tabSalarieCegid[$data->psa_salarie] = array('id' => $data->psa_salarie, 'nom' => $data->psa_libelle.' '.$data->psa_prenom);
        }
        
        
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherRefacturationCD({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);

            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterRefacturationCD&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
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
                        $img['Id_contrat_delegation'] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'ASC';
                    }
                    if ($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherRefacturationCD({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;

                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_contrat_delegation'] . '</td>
                            <td>' . $ligne['intitule'] . '</td>
                            <td>' . $ligne['agence'] . '</td>
                            <td>' . $ligne['pole'] . '</td>
                            <td>' . $ligne['indemnites_ref'] . '</td>
                            <td>' . $ligne['compte'] . '</td>
                            <td>' . $ligne['reference_affaire'] . '</td>
                            <td>' . substr($tabAffaireCegid[$ligne['reference_affaire']], 1) . '</td>
                            <td>' . $ligne['reference_bdc'] . '</td>
                            <td>' . ($ligne['id_ressource_sal'] != '' ? $tabSalarieCegid[$ligne['id_ressource_sal']]['id'] : $tabRessourceCegid[$ligne['id_ressource']]['id']) . '</td>
                            <td>' . ($ligne['id_ressource_sal'] != '' ? $tabSalarieCegid[$ligne['id_ressource_sal']]['nom'] : ($ligne['nom_ressource'] == '' ? $tabRessourceCegid[$ligne['id_ressource']]['nom'] : $ligne['nom_ressource'].' '.$ligne['prenom_ressource'] )) . '</td>
                            <td>' . $ligne['debut_mission'] . '</td>
                            <td>' . $ligne['fin_mission'] . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="refacturation_cd.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['id_contrat_delegation'] . ';';
                echo $ligne['intitule'] . ';';
                echo $ligne['agence'] . ';';
                echo $ligne['pole'] . ';';
                echo $ligne['indemnites_ref'] . ';';
                echo $ligne['compte'] . ';';
                echo $ligne['reference_affaire'] . ';';
                echo substr($tabAffaireCegid[$ligne['reference_affaire']], 1) . ';';
                echo $ligne['reference_bdc'] . ';';
                echo '"' . ($ligne['id_ressource_sal'] != '' ? $tabSalarieCegid[$ligne['id_ressource_sal']]['id'] : $tabRessourceCegid[$ligne['id_ressource']]['id']) . '";';
                echo '"' . ($ligne['id_ressource_sal'] != '' ? $tabSalarieCegid[$ligne['id_ressource_sal']]['nom'] : ($ligne['nom_ressource'] == '' ? $tabRessourceCegid[$ligne['id_ressource']]['nom'] : $ligne['nom_ressource'].' '.$ligne['prenom_ressource'] )) . '";';
                echo $ligne['debut_mission'] . ';';
                echo $ligne['fin_mission'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }
    
    
    public function changeCDOwner($newOwner) {
        $db = connecter();
        $requete = 'UPDATE contrat_delegation SET createur = "' . mysql_real_escape_string($newOwner) . '" WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int) $this->Id_contrat_delegation);
        $db->query($requete);
        $html = '<div>Le contrat délégation '.$Id_cd.' '
                . '<a href="index.php?a=editerContratDelegation&amp;Id='.$this->Id_contrat_delegation.'"><img src="../ui/images/pdf.png" onmouseover="return overlib(\'<div class=commentaire>Visualiser</div>\', FULLHTML);" onmouseout="return nd();"></a>'
                . '<a href="../com/index.php?a=modifierContratDelegation&amp;Id='.$this->Id_contrat_delegation.'"><img src="../ui/images/edit_inline.gif" onmouseover="return overlib(\'<div class=commentaire>Modifier</div>\', FULLHTML);" onmouseout="return nd();"></a>'
                . ' a été affecté à '.$newOwner.'.</div>';        
        return $html;
    }
    
    public function changeCDOwnerForm($Id_cd) {
        $createur = new Utilisateur(null, array());
        $html = '<form name="formulaire" action="../com/index.php" method="get">'
                . '<input type="hidden" name="a" id="a" value="changeCDOwner">'
                . '<input type="hidden" name="Id" id="Id" value="'.$Id_cd.'">'
                . 'Nouveau propriétaire :<br /><br /><select id="createur" name="createur">
                    ' . $createur->getList("OP") . '
                </select>'
                .'<br /><br /><button class="button save" style="width: 10em;" type="submit" value="Sauvegarder">Sauvegarder</button>'
                . '</form>';
        return $html;
    }
    
    public static function cdExistsWithRessourceOnPeriod($id_ressource, $date_debut, $date_fin) {
        $db = connecter();
        $requete = 'SELECT Id_contrat_delegation FROM contrat_delegation WHERE Id_ressource="'.mysql_real_escape_string($id_ressource).'" AND NOT(statut IN ("R", "A"))
            AND (
                (
                    "'.mysql_real_escape_string($date_debut).'"  >= debut_mission
                    AND "'.mysql_real_escape_string($date_debut).'"  <= fin_mission
                )
                OR (
                    "'.mysql_real_escape_string($date_fin).'" >= debut_mission
                    AND "'.mysql_real_escape_string($date_fin).'" <= fin_mission
                )
                OR (
                    "'.mysql_real_escape_string($date_debut).'"  < debut_mission
                    AND "'.mysql_real_escape_string($date_fin).'" > fin_mission
                )
            )';
        $res = $db->query($requete);
        //var_dump($res);
        $exist = $res->fetchAll();
        if (count($exist) > 0) {
            return $exist;//a déjà un cd
        } else {
            return false;//est libre
        }
    }
}

?>
