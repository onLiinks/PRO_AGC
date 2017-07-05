<?php

/**
 * Fichier Proposition.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Proposition
 */
class Proposition {

    /**
     * Identifiant de la proposition
     *
     * @access private
     * @var int 
     */
    public $Id_proposition;

    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int
     */
    private $Id_affaire;

    /**
     * Tableau des identifiants des ressources proposées
     *
     * @access public
     * @var array 
     */
    public $ressource;

    /**
     * Coût global de la proposition
     *
     * @access private
     * @var double
     */
    private $cout;

    /**
     * Frais journalier de la proposition
     *
     * @access public
     * @var double
     */
    public $frais_journalier;

    /**
     * Tarif journalier de la proposition
     *
     * @access public
     * @var double
     */
    public $tarif_journalier;

    /**
     * Chiffre d'affaire de la proposition
     *
     * @access public
     * @var int
     */
    public $chiffre_affaire;

    /**
     * Ponderation de la proposition
     *
     * @access public
     * @var int
     */
    public $ponderation;

    /**
     * Chiffre d'affaire pondérée de la proposition
     *
     * @access public
     * @var float
     */
    public $ca_ponderee;

    /**
     * Marge Totale de la proposition
     *
     * @access public
     * @var string
     */
    public $marge_totale;

    /**
     * Pour le pôle formation, nombre d'inscrits à une session pour l'affaire
     *
     * @access public
     * @var int
     */
    public $nb_inscrit;

    /**
     * Pour le pôle formation, liste des inscrits à une session pour l'affaire
     *
     * @access public
     * @var array
     */
    public $nomParticipant;

    /**
     * Pour le pôle formation, liste des inscrits à une session pour l'affaire
     *
     * @access public
     * @var array
     */
    public $prenomParticipant;

    /**
     * Pour le pôle formation, liste des inscrits à une session pour l'affaire
     *
     * @access public
     * @var array
     */
    public $prix_unitaireParticipant;

    /**
     * Pour le pôle formation, lien du bon de commande de l'affaire
     *
     * @access public
     * @var string
     */
    public $lien_bdc;

    /**
     * Commentaire sur la proposition
     *
     * @access private
     * @var string
     */
    public $remarque;
    private $intitule_phase1;
    private $intitule_phase2;
    private $intitule_phase3;
    private $intitule_phase4;
    private $intitule_licence;
    private $intitule_materiel;
    private $intitule_autre;
    private $cout_phase1;
    private $cout_phase2;
    private $cout_phase3;
    private $cout_phase4;
    private $cout_licence;
    private $cout_materiel;
    private $cout_autre;
    private $ca_phase1;
    private $ca_phase2;
    private $ca_phase3;
    private $ca_phase4;
    private $ca_licence;
    private $ca_materiel;
    private $ca_autre;

    /**
     * Constructeur de la classe proposition
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la proposition
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_proposition = '';
                $this->Id_affaire = '';
                $this->cout_total = '';
                $this->frais_journalier = array();
                $this->tarif_journalier = array();
                $this->cout_journalier = array();
                $this->duree_ressource = array();
                $this->marge_ressource = array();
                $this->ca_ressource = array();
                $this->debut_ressource = array();
                $this->fin_ressource = array();
                $this->fin_prev_ressource = array();
                $this->type_ressource = array();
                $this->chiffre_affaire = '';
                $this->ponderation = '';
                $this->ca_ponderee = '';
                $this->marge_totale = '';
                $this->remarque = '';
                $this->intitule_phase1 = '';
                $this->intitule_phase2 = '';
                $this->intitule_phase3 = '';
                $this->intitule_phase4 = '';
                $this->intitule_licence = '';
                $this->intitule_materiel = '';
                $this->intitule_autre = '';
                $this->cout_phase1 = '';
                $this->cout_phase2 = '';
                $this->cout_phase3 = '';
                $this->cout_phase4 = '';
                $this->cout_licence = '';
                $this->cout_materiel = '';
                $this->cout_autre = '';
                $this->ca_phase1 = '';
                $this->ca_phase2 = '';
                $this->ca_phase3 = '';
                $this->ca_phase4 = '';
                $this->ca_licence = '';
                $this->ca_materiel = '';
                $this->ca_autre = '';
                $this->date_remise = '';
                $this->reference = '';
                $this->nb_inscrit = '';
                $this->lien_bdc = '';
                $this->ressource = array();
                $this->periode = array();
                $this->annee = array();
                $this->duree = array();
                $this->debut = array();
                $this->fin = array();
                $this->cout = array();
                $this->ca = array();
                $this->marge = array();
                $this->typeAT = '';
                $this->typeInfog = '';
                $this->pct_site = '';
                $this->pct_dist = '';
                $this->nomParticipant = array();
                $this->prenomParticipant = array();
                $this->prix_unitaireParticipant = array();
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_proposition = '';
                $this->cout_total = htmlscperso(stripslashes($tab['cout_total']), ENT_QUOTES);
                $this->frais_journalier = $tab['frais_journalier'];
                $this->tarif_journalier = $tab['tarif_journalier'];
                $this->chiffre_affaire = htmlscperso(stripslashes($tab['chiffre_affaire']), ENT_QUOTES);
                $this->ponderation = ($tab['ponderation'] == 0) ? 0 : htmlscperso(stripslashes($tab['ponderation']), ENT_QUOTES);
                $this->ca_ponderee = (htmlscperso(stripslashes($tab['chiffre_affaire']), ENT_QUOTES) / 100) * htmlscperso(stripslashes($tab['ponderation']), ENT_QUOTES);
                $this->marge_totale = htmlscperso(stripslashes($tab['marge_totale']), ENT_QUOTES);
                $this->remarque = $tab['remarque_proposition'];
                $this->Id_profil = $tab['Id_profil'];
                $this->Id_affaire = $tab['Id_affaire'];
                $this->intitule_phase1 = htmlscperso(stripslashes($tab['intitule_phase1']), ENT_QUOTES);
                $this->intitule_phase2 = htmlscperso(stripslashes($tab['intitule_phase2']), ENT_QUOTES);
                $this->intitule_phase3 = htmlscperso(stripslashes($tab['intitule_phase3']), ENT_QUOTES);
                $this->intitule_phase4 = htmlscperso(stripslashes($tab['intitule_phase4']), ENT_QUOTES);
                $this->intitule_licence = htmlscperso(stripslashes($tab['intitule_licence']), ENT_QUOTES);
                $this->intitule_materiel = htmlscperso(stripslashes($tab['intitule_materiel']), ENT_QUOTES);
                $this->intitule_autre = htmlscperso(stripslashes($tab['intitule_autre']), ENT_QUOTES);
                $this->cout_phase1 = htmlscperso(stripslashes($tab['cout_phase1']), ENT_QUOTES);
                $this->cout_phase2 = htmlscperso(stripslashes($tab['cout_phase2']), ENT_QUOTES);
                $this->cout_phase3 = htmlscperso(stripslashes($tab['cout_phase3']), ENT_QUOTES);
                $this->cout_phase4 = htmlscperso(stripslashes($tab['cout_phase4']), ENT_QUOTES);
                $this->cout_licence = htmlscperso(stripslashes($tab['cout_licence']), ENT_QUOTES);
                $this->cout_materiel = htmlscperso(stripslashes($tab['cout_materiel']), ENT_QUOTES);
                $this->cout_autre = htmlscperso(stripslashes($tab['cout_autre']), ENT_QUOTES);
                $this->ca_phase1 = htmlscperso(stripslashes($tab['ca_phase1']), ENT_QUOTES);
                $this->ca_phase2 = htmlscperso(stripslashes($tab['ca_phase2']), ENT_QUOTES);
                $this->ca_phase3 = htmlscperso(stripslashes($tab['ca_phase3']), ENT_QUOTES);
                $this->ca_phase4 = htmlscperso(stripslashes($tab['ca_phase4']), ENT_QUOTES);
                $this->ca_licence = htmlscperso(stripslashes($tab['ca_licence']), ENT_QUOTES);
                $this->ca_materiel = htmlscperso(stripslashes($tab['ca_materiel']), ENT_QUOTES);
                $this->ca_autre = htmlscperso(stripslashes($tab['ca_autre']), ENT_QUOTES);
                $this->date_remise = htmlscperso(stripslashes($tab['date_remise']), ENT_QUOTES);
                $this->reference = htmlscperso(stripslashes($tab['reference']), ENT_QUOTES);
                $this->nb_inscrit = $tab['nb_inscrit'];
                $this->lien_bdc = htmlscperso(stripslashes($tab['lien_bdc']), ENT_QUOTES);

                $this->nomParticipant = $tab['nomParticipant'];
                $this->prenomParticipant = $tab['prenomParticipant'];
                $this->prix_unitaireParticipant = $tab['prix_unitaireParticipant'];

                $this->typeAT = htmlscperso(stripslashes($tab['typeAT']), ENT_QUOTES);
                $this->typeInfog = htmlscperso(stripslashes($tab['typeInfog']), ENT_QUOTES);
                $this->pct_site = htmlscperso(stripslashes($tab['pct_site']), ENT_QUOTES);
                $this->pct_dist = htmlscperso(stripslashes($tab['pct_dist']), ENT_QUOTES);

                if ($tab['ressource']) {
                    $nb_ressource = count($tab['ressource']);
                    $i = 0;
                    $this->frais_journalier = array();
                    $this->tarif_journalier = array();
                    $this->cout_journalier = array();
                    $this->duree_ressource = array();
                    $this->marge_ressource = array();
                    $this->ca_ressource = array();
                    $this->debut_ressource = array();
                    $this->fin_ressource = array();
                    $this->fin_prev_ressource = array();
                    $this->type_ressource = array();
                    while ($i < $nb_ressource) {
                        if ($tab['ressource'][$i]) {
                            $this->ressource[] = $tab['ressource'][$i];
                            $this->type_ressource_prop[] = $tab['type_ressource_prop'][$i];
                            $this->frais_journalier[] = $tab['frais_journalier'][$i];
                            $this->cout_journalier[] = $tab['cout_journalier'][$i];
                            $this->tarif_journalier[] = $tab['tarif_journalier'][$i];
                            $this->duree_ressource[] = $tab['duree_ressource'][$i];
                            $this->marge_ressource[] = $tab['marge_ressource'][$i];
                            $this->ca_ressource[] = $tab['ca_ressource'][$i];
                            $this->debut_ressource[] = $tab['debut_ressource'][$i];
                            $this->fin_ressource[] = $tab['fin_ressource'][$i];
                            $this->fin_prev_ressource[] = $tab['fin_prev_ressource'][$i];
                            $this->type_ressource[] = $tab['type_ressource'][$i];
                        }
                        ++$i;
                    }
                }

                if ($tab['ressource_i']) {
                    $nb_ressource_i = count($tab['ressource_i']);
                    $i = 0;
                    $this->ressource_i = array();
                    $this->duree_ressource_i = array();
                    $this->debut_ressource_i = array();
                    $this->fin_ressource_i = array();
                    $this->fin_prev_ressource_i = array();
                    $this->type_ressource_i = array();
                    while ($i < $nb_ressource_i) {
                        if ($tab['ressource_i'][$i]) {
                            $this->ressource_i[] = $tab['ressource_i'][$i];
                            $this->type_ressource_prop_i[] = $tab['type_ressource_prop_i'][$i];
                            $this->duree_ressource_i[] = $tab['duree_ressource_i'][$i];
                            $this->debut_ressource_i[] = $tab['debut_ressource_i'][$i];
                            $this->fin_ressource_i[] = $tab['fin_ressource_i'][$i];
                            $this->fin_prev_ressource_i[] = $tab['fin_prev_ressource_i'][$i];
                            $this->type_ressource_i[] = $tab['type_ressource_i'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['periode']) {
                    $nb_periode = count($tab['periode']);
                    $i = 0;
                    $this->annee = array();
                    $this->periode = array();
                    $this->duree = array();
                    $this->debut = array();
                    $this->fin = array();
                    $this->cout = array();
                    $this->ca = array();
                    $this->marge = array();
                    while ($i < $nb_periode) {
                        $this->annee[] = $tab['annee'][$i];
                        $this->periode[] = $tab['periode'][$i];
                        $this->duree[] = $tab['duree'][$i];
                        $this->debut[] = $tab['debut'][$i];
                        $this->fin[] = $tab['fin'][$i];
                        $this->cout[] = $tab['cout'][$i];
                        $this->ca[] = $tab['ca'][$i];
                        $this->marge[] = $tab['marge'][$i];
                        ++$i;
                    }
                }
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM proposition WHERE Id_proposition=' . mysql_real_escape_string((int) $code . ''))->fetchRow();
                $this->Id_proposition = $code;
                $this->Id_affaire = $ligne->id_affaire;
                $this->cout_total = $ligne->cout;
                $this->frais_journalier = $ligne->frais_journalier;
                $this->tarif_journalier = $ligne->tarif_journalier;
                $this->chiffre_affaire = $ligne->chiffre_affaire;
                $this->marge_totale = $ligne->marge;
                $this->remarque = $ligne->remarque;
                $this->intitule_phase1 = $ligne->intitule_phase1;
                $this->intitule_phase2 = $ligne->intitule_phase2;
                $this->intitule_phase3 = $ligne->intitule_phase3;
                $this->intitule_phase4 = $ligne->intitule_phase4;
                $this->intitule_licence = $ligne->intitule_licence;
                $this->intitule_materiel = $ligne->intitule_materiel;
                $this->intitule_autre = $ligne->intitule_autre;
                $this->cout_phase1 = $ligne->cout_phase1;
                $this->cout_phase2 = $ligne->cout_phase2;
                $this->cout_phase3 = $ligne->cout_phase3;
                $this->cout_phase4 = $ligne->cout_phase4;
                $this->cout_licence = $ligne->cout_licence;
                $this->cout_materiel = $ligne->cout_materiel;
                $this->cout_autre = $ligne->cout_autre;
                $this->ca_phase1 = $ligne->ca_phase1;
                $this->ca_phase2 = $ligne->ca_phase2;
                $this->ca_phase3 = $ligne->ca_phase3;
                $this->ca_phase4 = $ligne->ca_phase4;
                $this->ca_licence = $ligne->ca_licence;
                $this->ca_materiel = $ligne->ca_materiel;
                $this->ca_autre = $ligne->ca_autre;
                $this->date_remise = $ligne->date_remise;
                $this->reference = $ligne->reference;
                $this->typeAT = $ligne->typeat;
                $this->typeInfog = $ligne->typeinfog;
                $this->pct_site = $ligne->pct_site;
                $this->pct_dist = $ligne->pct_dist;

                $result2 = $db->query('SELECT * FROM proposition_ressource WHERE Id_proposition=' . mysql_real_escape_string((int) $code) . ' AND inclus=0 ORDER BY debut ASC');
                $this->frais_journalier = array();
                $this->tarif_journalier = array();
                $this->cout_journalier = array();
                $this->duree_ressource = array();
                $this->marge_ressource = array();
                $this->ca_ressource = array();
                $this->debut_ressource = array();
                $this->fin_ressource = array();
                $this->fin_prev_ressource = array();
                $this->type_ressource = array();
                while ($ligne2 = $result2->fetchRow()) {
                    $this->id_prop_ressource[] = $ligne2->id_prop_ress;
                    $this->ressource[] = $ligne2->id_ressource;
                    $this->type_ressource_prop[] = $ligne2->type_ressource;
                    $this->frais_journalier[] = $ligne2->frais_journalier;
                    $this->cout_journalier[] = $ligne2->cout_journalier;
                    $this->tarif_journalier[] = $ligne2->tarif_journalier;
                    $this->duree_ressource[] = $ligne2->duree;
                    $this->marge_ressource[] = $ligne2->marge;
                    $this->ca_ressource[] = $ligne2->ca;
                    $this->debut_ressource[] = $ligne2->debut;
                    $this->fin_ressource[] = $ligne2->fin;
                    $this->fin_prev_ressource[] = $ligne2->fin_prev;
                    $this->type_ressource[] = $ligne2->type;
                    $this->id_cd_ressource[] = $ligne2->id_contrat_delegation;
                }

                $this->ressource_i = array();
                $this->duree_ressource_i = array();
                $this->debut_ressource_i = array();
                $this->fin_ressource_i = array();
                $this->fin_prev_ressource_i = array();
                $this->type_ressource_i = array();
                $result2 = $db->query('SELECT * FROM proposition_ressource WHERE Id_proposition=' . mysql_real_escape_string((int) $code) . ' AND inclus=1 ORDER BY debut ASC');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->id_prop_ressource_i[] = $ligne2->id_prop_ress;
                    $this->ressource_i[] = $ligne2->id_ressource;
                    $this->type_ressource_prop_i[] = $ligne2->type_ressource;
                    $this->duree_ressource_i[] = $ligne2->duree;
                    $this->debut_ressource_i[] = $ligne2->debut;
                    $this->fin_ressource_i[] = $ligne2->fin;
                    $this->fin_prev_ressource_i[] = $ligne2->fin_prev;
                    $this->type_ressource_i[] = $ligne2->type;
                }
                $this->annee = array();
                $this->periode = array();
                $this->duree = array();
                $this->debut = array();
                $this->fin = array();
                $this->cout = array();
                $this->ca = array();
                $this->marge = array();
                $result2 = $db->query('SELECT Id_periode, annee, duree, debut, fin, cout, ca, marge FROM proposition_periode WHERE Id_proposition=' . mysql_real_escape_string((int) $code) . '');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->periode[] = $ligne2->id_periode;
                    $this->annee[] = $ligne2->annee;
                    $this->duree[] = $ligne2->duree;
                    $this->debut[] = $ligne2->debut;
                    $this->fin[] = $ligne2->fin;
                    $this->cout[] = $ligne2->cout;
                    $this->ca[] = $ligne2->ca;
                    $this->marge[] = $ligne2->marge;
                }

                //pour les affaires du pôle formation, récupération des informations spécifiques dans la base de données
                $ligne2 = $db->query('SELECT nb_inscrit, lien_bdc FROM proposition_formation WHERE Id_proposition=' . mysql_real_escape_string((int) $code) . '')->fetchRow();
                $this->nb_inscrit = $ligne2->nb_inscrit;
                $this->lien_bdc = $ligne2->lien_bdc;


                //récupération de la liste des participants avec leur, nom, prénom et le prix unitaire de chaque inscription
                $result2 = $db->query('SELECT nom, prenom, prix_unitaire FROM participant WHERE Id_affaire="' . mysql_real_escape_string((int) $this->Id_affaire) . '"');
                while ($ligne2 = $result2->fetchRow()) {
                    $this->nomParticipant[] = $ligne2->nom;
                    $this->prenomParticipant[] = $ligne2->prenom;
                    $this->prix_unitaireParticipant[] = $ligne2->prix_unitaire;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_proposition = $code;
                $this->cout_total = htmlscperso(stripslashes($tab['cout_total']), ENT_QUOTES);
                $this->frais_journalier = $tab['frais_journalier'];
                $this->tarif_journalier = $tab['tarif_journalier'];
                $this->chiffre_affaire = htmlscperso(stripslashes($tab['chiffre_affaire']), ENT_QUOTES);
                $this->ponderation = ($tab['ponderation'] == 0) ? 0 : htmlscperso(stripslashes($tab['ponderation']), ENT_QUOTES);
                $this->ca_ponderee = (htmlscperso(stripslashes($tab['chiffre_affaire']), ENT_QUOTES) / 100) * htmlscperso(stripslashes($tab['ponderation']), ENT_QUOTES);
                $this->marge_totale = htmlscperso(stripslashes($tab['marge_totale']), ENT_QUOTES);
                $this->remarque = $tab['remarque_proposition'];
                $this->Id_profil = $tab['Id_profil'];
                $this->Id_affaire = $tab['Id_affaire'];
                $this->intitule_phase1 = htmlscperso(stripslashes($tab['intitule_phase1']), ENT_QUOTES);
                $this->intitule_phase2 = htmlscperso(stripslashes($tab['intitule_phase2']), ENT_QUOTES);
                $this->intitule_phase3 = htmlscperso(stripslashes($tab['intitule_phase3']), ENT_QUOTES);
                $this->intitule_phase4 = htmlscperso(stripslashes($tab['intitule_phase4']), ENT_QUOTES);
                $this->intitule_licence = htmlscperso(stripslashes($tab['intitule_licence']), ENT_QUOTES);
                $this->intitule_materiel = htmlscperso(stripslashes($tab['intitule_materiel']), ENT_QUOTES);
                $this->intitule_autre = htmlscperso(stripslashes($tab['intitule_autre']), ENT_QUOTES);
                $this->cout_phase1 = htmlscperso(stripslashes($tab['cout_phase1']), ENT_QUOTES);
                $this->cout_phase2 = htmlscperso(stripslashes($tab['cout_phase2']), ENT_QUOTES);
                $this->cout_phase3 = htmlscperso(stripslashes($tab['cout_phase3']), ENT_QUOTES);
                $this->cout_phase4 = htmlscperso(stripslashes($tab['cout_phase4']), ENT_QUOTES);
                $this->cout_licence = htmlscperso(stripslashes($tab['cout_licence']), ENT_QUOTES);
                $this->cout_materiel = htmlscperso(stripslashes($tab['cout_materiel']), ENT_QUOTES);
                $this->cout_autre = htmlscperso(stripslashes($tab['cout_autre']), ENT_QUOTES);
                $this->ca_phase1 = htmlscperso(stripslashes($tab['ca_phase1']), ENT_QUOTES);
                $this->ca_phase2 = htmlscperso(stripslashes($tab['ca_phase2']), ENT_QUOTES);
                $this->ca_phase3 = htmlscperso(stripslashes($tab['ca_phase3']), ENT_QUOTES);
                $this->ca_phase4 = htmlscperso(stripslashes($tab['ca_phase4']), ENT_QUOTES);
                $this->ca_licence = htmlscperso(stripslashes($tab['ca_licence']), ENT_QUOTES);
                $this->ca_materiel = htmlscperso(stripslashes($tab['ca_materiel']), ENT_QUOTES);
                $this->ca_autre = htmlscperso(stripslashes($tab['ca_autre']), ENT_QUOTES);
                $this->date_remise = htmlscperso(stripslashes($tab['date_remise']), ENT_QUOTES);
                $this->reference = htmlscperso(stripslashes($tab['reference']), ENT_QUOTES);
                $this->nb_inscrit = $tab['nb_inscrit'];
                $this->typeAT = htmlscperso(stripslashes($tab['typeAT']), ENT_QUOTES);
                $this->typeInfog = htmlscperso(stripslashes($tab['typeInfog']), ENT_QUOTES);
                $this->pct_site = htmlscperso(stripslashes($tab['pct_site']), ENT_QUOTES);
                $this->pct_dist = htmlscperso(stripslashes($tab['pct_dist']), ENT_QUOTES);

                if ($tab['nb_ressource'][0] > 0) {
                    $nb_ressource = $tab['nb_ressource'][0];
                    $i = 0;
                    $this->id_prop_ressource = array();
                    $this->frais_journalier = array();
                    $this->tarif_journalier = array();
                    $this->cout_journalier = array();
                    $this->duree_ressource = array();
                    $this->marge_ressource = array();
                    $this->ca_ressource = array();
                    $this->debut_ressource = array();
                    $this->fin_ressource = array();
                    $this->fin_prev_ressource = array();
                    $this->type_ressource = array();
                    while ($i < $nb_ressource) {
                        if ($tab['ressource'][$i]) {
                            $this->id_prop_ressource[] = $tab['id_prop_ress'][$i];
                            $this->type_ressource_prop[] = $tab['type_ressource_prop'][$i];
                            $this->ressource[] = $tab['ressource'][$i];
                            $this->frais_journalier[] = $tab['frais_journalier'][$i];
                            $this->cout_journalier[] = $tab['cout_journalier'][$i];
                            $this->tarif_journalier[] = $tab['tarif_journalier'][$i];
                            $this->duree_ressource[] = $tab['duree_ressource'][$i];
                            $this->marge_ressource[] = $tab['marge_ressource'][$i];
                            $this->ca_ressource[] = $tab['ca_ressource'][$i];
                            $this->debut_ressource[] = $tab['debut_ressource'][$i];
                            $this->fin_ressource[] = $tab['fin_ressource'][$i];
                            $this->fin_prev_ressource[] = $tab['fin_prev_ressource'][$i];
                            $this->type_ressource[] = $tab['type_ressource'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['nb_ressource_i'][0] > 0) {
                    $nb_ressource_i = $tab['nb_ressource_i'][0];
                    $i = 0;
                    $this->ressource_i = array();
                    $this->duree_ressource_i = array();
                    $this->debut_ressource_i = array();
                    $this->fin_ressource_i = array();
                    $this->fin_prev_ressource_i = array();
                    $this->type_ressource_i = array();
                    while ($i < $nb_ressource_i) {
                        if ($tab['ressource_i'][$i]) {
                            $this->id_prop_ressource_i[] = $tab['id_prop_ress_i'][$i];
                            $this->ressource_i[] = $tab['ressource_i'][$i];
                            $this->type_ressource_prop_i[] = $tab['type_ressource_prop_i'][$i];
                            $this->duree_ressource_i[] = $tab['duree_ressource_i'][$i];
                            $this->debut_ressource_i[] = $tab['debut_ressource_i'][$i];
                            $this->fin_ressource_i[] = $tab['fin_ressource_i'][$i];
                            $this->fin_prev_ressource_i[] = $tab['fin_prev_ressource_i'][$i];
                            $this->type_ressource_i[] = $tab['type_ressource_i'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['periode']) {
                    $nb_periode = count($tab['periode']);
                    $i = 0;
                    $this->annee = array();
                    $this->periode = array();
                    $this->duree = array();
                    $this->debut = array();
                    $this->fin = array();
                    $this->cout = array();
                    $this->ca = array();
                    $this->marge = array();
                    while ($i < $nb_periode) {
                        $this->annee[] = $tab['annee'][$i];
                        $this->periode[] = $tab['periode'][$i];
                        $this->duree[] = $tab['duree'][$i];
                        $this->debut[] = $tab['debut'][$i];
                        $this->fin[] = $tab['fin'][$i];
                        $this->cout[] = $tab['cout'][$i];
                        $this->ca[] = $tab['ca'][$i];
                        $this->marge[] = $tab['marge'][$i];
                        ++$i;
                    }
                }
                $this->nomParticipant = $tab['nomParticipant'];
                $this->prenomParticipant = $tab['prenomParticipant'];
                $this->prix_unitaireParticipant = $tab['prix_unitaireParticipant'];

                //pour les affaires du pôle formation : si le lien vers le bon de commande a changé, association du fichier avec le nouveau lien
                $db = connecter();
                $ligne = $db->query('SELECT lien_bdc FROM proposition_formation WHERE Id_proposition=' . mysql_real_escape_string((int) $code))->fetchRow();
                if ($tab['lien_bdc'] != '') {
                    $this->lien_bdc = $tab['lien_bdc'];
                    if ($ligne->lien_bdc != $this->lien_bdc) {
                        if (is_file(BDC_DIR . $ligne->lien_bdc)) {
                            unlink(BDC_DIR . $ligne->lien_bdc);
                        }
                    }
                } else {
                    $this->lien_bdc = $ligne->lien_bdc;
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une proposition
     *
     * @return string
     */
    public function form($Id_type_contrat, $Id_pole=NULL) {
        $affaire = new Affaire($this->Id_affaire, array());
        if (!empty($this->Id_affaire)) {
            $ponderation = $this->getLastWeighting();
            $histoPonderation = $this->getWeightingHistory();
        }
        else
            $ponderation = 0;
        if ($affaire->Id_statut != 3 && $affaire->Id_statut != 4)
            $readonly = 'readonly="readonly" disabled="disabled"';

        $select[$ponderation->ponderation] = "selected='selected'";
        $html .= '
				<input type="button" value="Calculer les totaux depuis les ressources" onclick="if(confirm(\'Voulez-vous totaliser le chiffre d\\\'affaire et coût de revient en fonction des lignes ressources ?\')) {updateTotalRevenue(); return false;} else {return false;}">
                                &nbsp;&nbsp;&nbsp;&nbsp;<b><a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterRefPonderation\')">Référentiel de Pondération</a></b><br />
				<input type="hidden" id="prop" value="' . $this->Id_proposition . '">
                                <input type="hidden" id="save_ressource" name="save_ressource" value="0">
                <br />
				<table>
				    <tr id="totalpropal">
				        <td>Coût de revient total : <input type="text" id="cout_total" name="cout_total" value="' . $this->cout_total . '" size="6" /> &euro;</td>
                        <td><span class="vert"> * </span>CA Total affaire : <input type="text" id="cha" name="chiffre_affaire" value="' . $this->chiffre_affaire . '" size="6"/> &euro;</td>
                        <td>Pondération : 
                            <select name="ponderation" id="ponderation"  readonly="readonly" ' . $readonly . '>
                                <option value="0" ' . $select[0] . '>0%</option>
                                <option value="10" ' . $select[10] . '>10%</option>
                                <option value="20" ' . $select[20] . '>20%</option>
                                <option value="40" ' . $select[40] . '>40%</option>
                                <option value="60" ' . $select[60] . '>60%</option>
                                <option value="80" ' . $select[80] . '>80%</option>
                                <option value="100" ' . $select[100] . '>100%</option>
                            </select>
                        </td>
                        <td>CA pondéré : <input type="text" id="ca_pondere" name="ca_pondere" readonly="readonly" value="' . $ponderation->ca_ponderee . '" /></td>
                        <td><input type="button" value="Calculer" onclick="$(ca_pondere).value = Math.round($(chiffre_affaire).value * ($(ponderation).value / 100))"></td>
                                    </tr>
				</table>
                                ' . $histoPonderation . '<br />';
        if ($_SESSION['societe'] == 'WIZTIVI' || $_SESSION['societe'] == 'NEEDPROFILE') {
            $html .= $this->formProposition2();
        } else {
            if (in_array($Id_type_contrat, array(1, 2))) {
                if ($Id_type_contrat == 2) {
                    $typeinfog[$this->typeInfog] = 'checked="checked"';
                    $formTypeInfog = '
				    <span class="vert"> * </span> 
				    Sur site <input type="radio" name="typeInfog" ' . $typeinfog['Sur site'] . ' value="Sur site">
				    A distance <input type="radio" name="typeInfog" ' . $typeinfog['A distance'] . ' value="A distance"> <br />
				    Couplée <input type="radio" name="typeInfog" ' . $typeinfog['Couplée'] . ' value="Couplée">
				    <input type="text" name="pct_site" value="' . $this->pct_site . '" size="2" /> % Sur site
				    / <input type="text" name="pct_dist" value="' . $this->pct_dist . '" size="2" /> % A distance
				    <br /><br />';
                    $doc = Affaire::getDoc($this->Id_affaire);
                    if ($doc) {
                        $hmtlDoc = 'Documents actuels : <a href="' . PROPALE_DIR . $doc . '">' . $doc . '</a><br /><br />';
                    }
                    $htmlPropal = '<br />
					' . $formTypeInfog . '
					' . $hmtlDoc . '
					Les documents de l\'affaire : ( Fichier zip )
					<input type="hidden" name="MAX_FILE_SIZE" value="15000000" />
					<input name="doc" type="file" /> (< 15 Mo )
                                        <br /><br />
                                        <input type="hidden" id="prop" value="' . $this->Id_proposition . '">
					<button type="button" class="button add" style="width: 7em;" onclick="ajoutRessource()" value="Ajouter">Ajouter</button>
                                        <a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterCD&Id_affaire=' . $this->Id_affaire . '&archive=2\')">Contrats associés à l\'affaire</a>
					<table style="table-layout:fixed;border-collapse:collapse;">
						<tr>
							<th width="25%">Ressource(s)</th>
							<th width="6%">Type</th>
							<th width="6%">Frais J.</th>
							<th width="6%">Coût J.</th>
							<th width="5%">Prix de vente</th>
							<th width="7%">Début</th>
							<th width="7%">Fin</th>
							<th width="7%">Fin Prév</th>
							<th width="5%">Durée (J)</th>
							<th width="5%">Marge (%)</th>
							<th width="9%">CA (&euro;)</th>
							<th width="6%"> </th>
                                                        <th width="6%"> </th>
					    </tr>
					</table>
                                        <div id="resourceTable">';
                    $htmlPropal .= $this->resourceForm() . '</div>';
                } elseif ($Id_type_contrat == 1) {
                    $tat[$this->typeAT] = 'checked="checked"';
                    $formTypeAT = '
				    <span class="vert"> * </span> Type de facturation :
				    Régie <input type="radio" name="typeAT" ' . $tat["Régie"] . ' value="Régie">
				    Forfaitisé <input type="radio" name="typeAT" ' . $tat["Forfaitisé"] . ' value="Forfaitisé"><br /><br />
					Remarque : <br />
	                <textarea name="remarque_proposition" rows="6" cols="70">' . $this->remarque . '</textarea>
                        <br /><br />';

                    $html .= '
					' . $formTypeAT . '
					<input type="hidden" id="prop" value="' . $this->Id_proposition . '">
					<button type="button" class="button add" style="width: 7em;" onclick="ajoutRessource()" value="Ajouter">Ajouter</button>
                                        <a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterCD&Id_affaire=' . $this->Id_affaire . '&archive=2\')">Contrats associés à l\'affaire</a>
					<table style="table-layout:fixed;border-collapse:collapse;">
						<tr>
							<th width="25%">Ressource(s)</th>
							<th width="6%">Type</th>
							<th width="6%">Frais J.</th>
							<th width="6%">Coût J.</th>
							<th width="5%">Prix de vente</th>
							<th width="7%">Début</th>
							<th width="7%">Fin</th>
							<th width="7%">Fin Prév</th>
							<th width="5%">Durée (J)</th>
							<th width="5%">Marge (%)</th>
							<th width="9%">CA (&euro;)</th>
							<th width="6%"> </th>
                                                        <th width="6%"> </th>
					    </tr>
					</table>
                                        <div id="resourceTable">';
                    $html .= $this->resourceForm() . '</div>';
                }
                $html .= $htmlPropal;
            }
            //pour les affaires du pôle formation, formulaire spécifique
            else if ($Id_type_contrat == 3 && $Id_pole == 3) {
                $html = $this->formPropositionSession();
                $html .= '<hr />';
                $html .= '<input type="hidden" id="prop" value="' . $this->Id_proposition . '">
					<button type="button" class="button add" style="width: 7em;" onclick="ajoutRessource()" value="Ajouter">Ajouter</button>
                                        <a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterCD&Id_affaire=' . $this->Id_affaire . '&archive=2\')">Contrats associés à l\'affaire</a>
					<table style="table-layout:fixed;border-collapse:collapse;">
						<tr>
							<th width="25%">Ressource(s)</th>
							<th width="6%">Type</th>
							<th width="6%">Frais J.</th>
							<th width="6%">Coût J.</th>
							<th width="5%">Prix de vente</th>
							<th width="7%">Début</th>
							<th width="7%">Fin</th>
							<th width="7%">Fin Prév</th>
							<th width="5%">Durée (J)</th>
							<th width="5%">Marge (%)</th>
							<th width="9%">CA (&euro;)</th>
							<th width="6%"> </th>
                                                        <th width="6%"> </th>
					    </tr>
					</table>
                                        <div id="resourceTable">';
                $html .= $this->resourceForm() . '</div>';
            } else {
                $html .= '<input type="hidden" id="prop" value="' . $this->Id_proposition . '">
					<button type="button" class="button add" style="width: 7em;" onclick="ajoutRessource()" value="Ajouter">Ajouter</button>
                                        <a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterCD&Id_affaire=' . $this->Id_affaire . '&archive=2\')">Contrats associés à l\'affaire</a>
					<table style="table-layout:fixed;border-collapse:collapse;">
						<tr>
							<th width="25%">Ressource(s)</th>
							<th width="6%">Type</th>
							<th width="6%">Frais J.</th>
							<th width="6%">Coût J.</th>
							<th width="5%">Prix de vente</th>
							<th width="7%">Début</th>
							<th width="7%">Fin</th>
							<th width="7%">Fin Prév</th>
							<th width="5%">Durée (J)</th>
							<th width="5%">Marge (%)</th>
							<th width="9%">CA (&euro;)</th>
							<th width="6%"> </th>
                                                        <th width="6%"> </th>
					    </tr>
					</table>
                                        <div id="resourceTable">';
                $html .= $this->resourceForm() . '</div>';
            }
        }
        return $html;
    }

    public function resourceForm() {
        $count = count($this->ressource);
        if ($count > 0) {
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            $proposition = new Proposition($this->Id_proposition, array());
            while ($i < $count) {
                $cd = null;
                $lienCD = $htmlArchive = '';
                $j = $i + 1;
                $ressource = RessourceFactory::create($proposition->type_ressource_prop[$i], $proposition->ressource[$i], null);
                $id_prop_ress = $proposition->id_prop_ressource[$i];
                $duree_ressource = $proposition->duree_ressource[$i];
                $debut_ressource = FormatageDate($proposition->debut_ressource[$i]);
                $fin_ressource = FormatageDate($proposition->fin_ressource[$i]);
                $fin_prev_ressource = FormatageDate($proposition->fin_prev_ressource[$i]);

                $idCD = $proposition->getIdCD($proposition->id_prop_ressource[$i]);
                $trColor = $readonly = $disabled = $cal = $cal2 = '';
                if (is_null($idCD)) {
                    $affaire = new Affaire($this->Id_affaire, array());
                    $trColor = 'bgcolor="#A4A4F9"';
                    $cal = 'onfocus="showCalendarControl(this)"';
                    $cal2 = 'onfocus="showCalendarControl(this, function(){updateDureeRessource(' . $j . ')})"';
                    $lienCD = '<td width="5%"><script>document.observe("date:frobbed", function(event) {calculRessource(\'' . $j . '\')});</script><input type="button" value="calculer" onclick="calculRessource(\'' . $j . '\')" /></td>';
                    if ($affaire->Id_statut == 5 || $affaire->Id_statut == 8)
                        $lienCD .= '<td width="5%"><input type="button" value="contrat" onclick="genererCD(' . $j . ',\'' . $proposition->ressource[$i] . '\')" style="width:45px" /></td>';
                    else
                        $lienCD .= '<td width="5%"></td>';
                    $lienCD .= '<td width="2%"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer cette ressource ? \')) { deleteRessourceProposition(\'' . $idCD . '\',\'' . $id_prop_ress . '\',\'' . $proposition->Id_proposition . '\') }" /></td>';
                }
                else {
                    $cd = new ContratDelegation($idCD, array());
                    $readonly = 'readonly="readonly"';
                    $disabled = 'disabled="disabled"';
                    if (is_null($cd->statut) || $cd->statut == 'A') {
                        $trColor = 'bgcolor="#A4A4F9"';
                        $lienCD = '<td width="3%"><a href="javascript:void(0)" onclick="sendCD(\'' . $idCD . '\',\'' . $proposition->Id_proposition . '\', \'Envoyer par mail au service facturation ?\')"><img src="' . IMG_MAIL . '"></a></td>
                                    <td width="2%"><a href="index.php?a=editerContratDelegation&amp;Id=' . $idCD . '"><img src="' . IMG_PDF . '"></a></td>
                                    <td width="2%"><img src="' . IMG_EDIT . '" onClick="ouvre_popup(\'../com/index.php?a=modifierContratDelegation&amp;Id=' . $idCD . '\');" /></td>
                                    <td width="3%"><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { duplicateRessourceProposition(\'' . $idCD . '\',\'' . $proposition->Id_proposition . '\') }"><img src="' . IMG_COPY . '"></a></td>
                                    <td width="2%"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer cette ressource ? \')) { deleteRessourceProposition(\'' . $idCD . '\',\'' . $id_prop_ress . '\',\'' . $proposition->Id_proposition . '\') }" /></td>';
                    } elseif ($cd->statut == 'E') {
                        $lienCD = '<td width="4%"><a href="index.php?a=editerContratDelegation&amp;Id=' . $idCD . '"><img src="' . IMG_PDF . '"></a></td>
                                    <td width="4%"><img src="' . IMG_EDIT . '" onClick="ouvre_popup(\'../com/index.php?a=modifierContratDelegation&amp;Id=' . $idCD . '\');" /></td>
                                    <td width="4%"><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { duplicateRessourceProposition(\'' . $idCD . '\',\'' . $proposition->Id_proposition . '\') }"><img src="' . IMG_COPY . '"></a></td>';
                    } elseif ($cd->statut == 'R') {
                        $trColor = 'bgcolor="#EDC3C3"';
                        $lienCD = '<td width="3%"><a href="index.php?a=editerContratDelegation&amp;Id=' . $idCD . '"><img src="' . IMG_PDF . '"></a></td>
                                    <td width="3%"><img src="' . IMG_EDIT . '" onClick="ouvre_popup(\'../com/index.php?a=modifierContratDelegation&amp;Id=' . $idCD . '\');" /></td>
                                    <td width="3%"><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { duplicateRessourceProposition(\'' . $idCD . '\',\'' . $proposition->Id_proposition . '\') }"><img src="' . IMG_COPY . '"></a></td>
                                    <td width="3%"><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer cette ressource ? \')) { deleteRessourceProposition(\'' . $idCD . '\',\'' . $id_prop_ress . '\',\'' . $proposition->Id_proposition . '\') }" /></td>';
                    } elseif ($cd->statut == 'V') {
                        $trColor = 'bgcolor="#BAE0BA"';
                        if ($cd->archive == 0) {
                            $htmlArchive = '<a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $idCD . '&amp;class=ContratDelegation\') }"><img src="' . IMG_FLECHE_BAS . '"></a>';
                        } elseif ($cd->archive == 1) {
                            $htmlArchive = '<a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' le contrat ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $idCD . '&amp;class=ContratDelegation\') }"><img src="' . IMG_FLECHE_HAUT . '"></a>';
                        }
                        $lienCD = '<td width="4%"><a href="index.php?a=editerContratDelegation&amp;Id=' . $idCD . '"><img src="' . IMG_PDF . '"></a></td>
                                    <td width="4%"><a href="javascript:void(0)" onclick="if (confirm(\'Voulez-vous dupliquer le contrat pour cette affaire ?\')) { duplicateRessourceProposition(\'' . $idCD . '\',\'' . $proposition->Id_proposition . '\') }"><img src="' . IMG_COPY . '"></a></td>
                                    <td width="4%">' . $htmlArchive . '</td>';
                    }
                }

                $type['T'] = $type['S'] = $type['CPI'] = $type['SDM'] = '';
                $type[$proposition->type_ressource[$i]] = 'selected="selected"';

                $type_ress = $proposition->type_ressource[$i];
                $type_ress_prop = $proposition->type_ressource_prop[$i];
                $fj = $proposition->frais_journalier[$i];
                $cj = $proposition->cout_journalier[$i];
                $tj = $proposition->tarif_journalier[$i];
                $mr = $proposition->marge_ressource[$i];
                $cr = $proposition->ca_ressource[$i];
                ++$i;
                if($cd->archive == 1) continue;
                $html .= '
                    <div id="autreRessource' . $i . '">
                        <table style="table-layout:fixed;border-collapse:collapse;">
                            <tr ' . $trColor . ' style="text-align:center;height:25px;">
                                <td width="25%">
                                    ' . $ressource->getName() . '
                                    <input type="hidden" id="ressource' . $i . '" name="ressource[]" value="' . $ressource->Id_ressource . '" />
                                    <input type="hidden" id="id_prop_ress' . $i . '" name="id_prop_ress[]" value="' . $id_prop_ress . '" />
                                    <input type="hidden" id="type_ressource_prop' . $i . '" name="type_ressource_prop[]" value="' . $type_ress_prop . '" />
                                </td>
                                <td width="6%">
                                    <select name="type_ressource[]" ' . $disabled . ' onchange="$(\'type_ressource' . $i . '\').value = this.value">
                                        <option value="">Type</option>
                                        <option value="T" ' . $type['T'] . '>T</option>
                                        <option value="S" ' . $type['S'] . '>S</option>
                                        <option value="CPI" ' . $type['CPI'] . '>CPI</option>
                                        <option value="SDM" ' . $type['SDM'] . '>SDM</option>
                                    </select>
                                    <input type="hidden" id="type_ressource' . $i . '" name="type_ressource[]" value="' . $type_ress . '" />
                                </td>
                                <td width="6%"><input type="text" id="frais_ressource' . $i . '" name="frais_journalier[]" value="' . $fj . '" size="5" ' . $readonly . ' /></td>
                                <td width="6%" id="coutJ' . $i . '"><input type="text" id="cout_ressource' . $i . '" name="cout_journalier[]" value="' . $cj . '" size="5" ' . $readonly . ' /></td>
                                <td width="5%" id="tarifJ' . $i . '"><input type="text" id="tarif_ressource' . $i . '" name="tarif_journalier[]" value="' . $tj . '" size="5" ' . $readonly . ' /></td>
                                <td width="7%" id="debut' . $i . '"><input type="text" id="debut_ressource' . $i . '" name="debut_ressource[]" ' . $cal2 . ' value="' . $debut_ressource . '" size="8" ' . $readonly . ' /></td>
                                <td width="7%" id="fin' . $i . '"><input type="text" id="fin_ressource' . $i . '" name="fin_ressource[]" ' . $cal2 . ' value="' . $fin_ressource . '" size="8" ' . $readonly . ' /></td>
                                <td width="7%" id="fin_prev' . $i . '"><input type="text" id="fin_prev_ressource' . $i . '" name="fin_prev_ressource[]" ' . $cal . ' value="' . $fin_prev_ressource . '" size="8" ' . $readonly . ' /></td>
                                <td width="5%" id="duree' . $i . '"><input type="text" id="duree_ressource' . $i . '" name="duree_ressource[]" value="' . $duree_ressource . '" size="2" ' . $readonly . ' /></td>
                                <td width="5%" id="marge' . $i . '"><input type="text" id="marge_ressource' . $i . '" readonly name="marge_ressource[]" value="' . $mr . '" size="5" ' . $readonly . ' /></td>
                                <td width="9%" id="ca' . $i . '"><input id="ca_ressource' . $i . '" type="text" readonly name="ca_ressource[]" value="' . $cr . '" size="12" ' . $readonly . ' /></td>
                                ' . $lienCD . '
                            </tr>
                        </table>
                    </div>';
            }
            $html .= '<div id="autreRessource' . $nb_suivant . '">
                        <input type="hidden" id="nb_ressource" name="nb_ressource[]" value="' . $nb_courant . '">
                    </div>';
        } elseif ($count == 0) {
            $html = $this->addResource(1);
        }
        return $html;
    }

    public function addResource($nb) {
        $nb2 = $nb + 1;
        $t = TYPE_RESSOURCE_SELECT;
        $html = <<<EOT
		<table style="table-layout:fixed">
			<tr style="text-align:center">
				<td width="25%">
                                    <select id="type_ressource_prop{$nb}" name="type_ressource_prop[]" onchange="updateCaseResourceListByType({$nb})">
                                        <option value="">{$t}</option>
                                        <option value="">-------------------------</option>
                                        <option value="MAT">Matériel</option>
                                        <option value="CAN_AGC">Candidats embauchés (AGC)</option>
                                        <option value="SAL">Salariés</option>
                                        <option value="ST">Sous-traitants</option>
                                        <option value="INT">Intérimaires</option>
                                    </select>
                                    <select id='ressource{$nb}' name='ressource[]' onchange='coutJ({$nb})'>
                                            <option value=''>Sélectionner une ressource</option>
                                            <option value="">----------------------------</option>
                                            <option value='TJM'>Ressource TJM</option>
                                    </select>
				</td>
				<td width="6%">
				<select name="type_ressource[]">
                                    <option value="">Type</option>
                                    <option value="T" selected="selected">T</option>
                                    <option value="S">S</option>
                                    <option value="CPI">CPI</option>
                                    <option value="SDM">SDM</option>
				</select>
			    </td>
				<td width="6%"><input type='text' id='frais_ressource{$nb}' name='frais_journalier[]' size='5' value='0' /></td>
				<td width="6%" id='coutJ{$nb}'><input type='text' id='cout_ressource{$nb}' name='cout_journalier[]' size='5' /></td>
				<td width="5%" id='tarifJ{$nb}'><input type='text' id='tarif_ressource{$nb}' name='tarif_journalier[]' size='5' /></td>
				<td width="7%" id='debut{$nb}'><input type='text' id='debut_ressource{$nb}' name='debut_ressource[]' onChange="updateDureeRessource({$nb})" onfocus='showCalendarControl(this, function(){updateDureeRessource({$nb})})' size='8'/></td>
				<td width="7%" id='fin{$nb}'><input type='text' id='fin_ressource{$nb}' name='fin_ressource[]' onChange="updateDureeRessource({$nb})" onfocus="showCalendarControl(this, function(){updateDureeRessource({$nb})})" size='8'/></td>
				<td width="7%" id='fin_prev{$nb}'><input type='text' id='fin_prev_ressource{$nb}' name='fin_prev_ressource[]' onfocus='showCalendarControl(this)' size='8'/></td>
				<td width="5%" id='duree{$nb}'><input type='text' id='duree_ressource{$nb}' name='duree_ressource[]' size='2'/></td>
				<td width="5%" id='marge{$nb}'><input type='text' id='marge_ressource{$nb}' readonly name='marge_ressource[]' size='5' /></td>
				<td width="9%" id='ca{$nb}'><input id='ca_ressource{$nb}' type='text' readonly name='ca_ressource[]' size='12' /></td>
				<td width="5%"><script>document.observe("date:frobbed", function(event) {calculRessource(\'{$nb}\')});</script><input type="button" value="calculer" onclick="calculRessource('{$nb}')" /></td>
                                <td width="6%"></td>
			</tr>
		</table>
		<div id='autreRessource$nb2'><input type='hidden' id='nb_ressource' name='nb_ressource[]' value='$nb'></div>
EOT;
        return $html;
    }

    public function includedResourceForm() {
        $count = count($this->ressource_i);
        if ($count > 0) {
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            $proposition = new Proposition($this->Id_proposition, array());
            while ($i < $count) {
                $j = $i + 1;
                $ressource = RessourceFactory::create($proposition->type_ressource_i[$i], $proposition->ressource_i[$i], null);
                $duree_ressource = $proposition->duree_ressource_i[$i];
                $debut_ressource = FormatageDate($proposition->debut_ressource_i[$i]);
                $fin_ressource = FormatageDate($proposition->fin_ressource_i[$i]);
                $fin_prev_ressource = FormatageDate($proposition->fin_prev_ressource_i[$i]);

                if (in_array(Affaire::getIdStatut($this->Id_affaire), array(5, 8)) && $proposition->ressource_i[$i] != '') {
                    $lienCD = '<td><img src="' . IMG_CDELEG . '" alt="Contrat délégation" onclick="genererCD(' . $j . ',\'' . $proposition->ressource_i[$i] . '\')"></td>';
                }
                $type['T'] = $type['S'] = $type['CPI'] = $type['SDM'] = '';
                $type[$proposition->type_ressource_i[$i]] = 'selected="selected"';
                ++$i;
                $html .= '
	            <div id="autreRessourceInclus' . $i . '">
				    <table>
	                    <tr>
							<td width="40%">
								' . Ressource::getName($ressource->Id_ressource) . ' <input type="hidden" id="ressource_i' . $i . '" name="ressource_i[]" value="' . $ressource->Id_ressource . '" />
							</td>							
							<td>
							<select name="type_ressource_i[]">
								<option value="">Type</option>
								<option value="T" ' . $type['T'] . '>T</option>
								<option value="S" ' . $type['S'] . '>S</option>
								<option value="CPI" ' . $type['CPI'] . '>CPI</option>
								<option value="SDM" ' . $type['SDM'] . '>SDM</option>
							</select>
					        </td>
							<td id="debut_i' . $i . '"><input type="text" id="debut_ressource_i' . $i . '" name="debut_ressource_i[]" onfocus="showCalendarControl(this)" value="' . $debut_ressource . '" size="8" /></td>
							<td id="fin_i' . $i . '"><input type="text" id="fin_ressource_i' . $i . '" name="fin_ressource_i[]" onfocus="showCalendarControl(this)" value="' . $fin_ressource . '" size="8" /></td>
							<td id="fin_prev_i' . $i . '"><input type="text" id="fin_prev_ressource_i' . $i . '" name="fin_prev_ressource_i[]" onfocus="showCalendarControl(this)" value="' . $fin_prev_ressource . '" size="8" /></td>
							<td id="duree_i' . $i . '"><input type="text" id="duree_ressource_i' . $i . '" name="duree_ressource_i[]" value="' . $duree_ressource . '" size="2" /></td>
							' . $lienCD . '
							<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'Voulez vous supprimer cette ressource ? \')) { deleteRessourceProposition(\'' . $this->Id_proposition . '\',\'' . $ressource->Id_ressource . '\',\'' . $debut_ressource . '\',\'' . $fin_ressource . '\',\'' . $duree_ressource . '\',\'' . $i . '\') }" /></td>
					    </tr>
				    </table>
				</div>
';
            }
            $html .= '
			<div id="autreRessourceInclus' . $nb_suivant . '">
		        <input type="hidden" id="nb_ressource_i" name="nb_ressource_i[]" value="' . $nb_courant . '">
		    </div>
';
        } elseif ($count == 0) {
            $html = $this->addIncludedRessource(1);
        }
        return $html;
    }

    public function formProposition2() {

        if ($this->Id_affaire) {
            $affaire = new Affaire($this->Id_affaire, array());
            $count = count($affaire->proposition);
            $i = 0;
            while ($i < $count) {
                $proposition = new Proposition($affaire->proposition[$i], array());
                ++$i;
                $html .= '<div id="autreProposition' . $i . '">
				    ' . $proposition->ajoutProposition2($i) . '
				</div>';
                $j = $i + 1;
            }
            $html .= '<div id="autreProposition' . $j . '">
		        <input type="hidden" id="nb_proposition" name="nb_proposition" value="' . $i . '">
		    </div>
';
        } else {
            $html .= $this->ajoutProposition2(1);
        }
        return $html;
    }

    public function ajoutProposition2($n_pr) {
        if ($this->Id_proposition) {
            $proposition = new Proposition($this->Id_proposition, array());
            $proposition->date_remise = FormatageDate($proposition->date_remise);
            $html .= <<<EOT
				<h3>Proposition commerciale n° $n_pr : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Date de remise : <input name='date_remise[]' onfocus='showCalendarControl(this)' type='text' value='{$proposition->date_remise}' size='8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Référence : <input name='reference[]' type='text' value='{$proposition->reference}' size='40'></h3><br />
			    <div class='center'>
					<input type='button' onclick='ajoutAnnee({$n_pr})' value='Ajouter Année'>
					<input type='button' onclick='enleveAnnee({$n_pr})' value='Supprimer Année'>
				</div>
EOT;
            $nb_annee = count(array_unique($proposition->annee));
            if ($nb_annee > 0) {
                $i = 0;
                while ($i < $nb_annee) {
                    ++$i;
                    $html .= <<<EOT
			        <div id='pr$n_pr|an$i'>{$proposition->addYear($n_pr, $i)}</div><br />
EOT;
                    $j = $i + 1;
                }
                $html .= <<<EOT
			    <div id='pr$n_pr|an$j'><input type='hidden' id='pr-$n_pr-an' name='pr-$n_pr-an' value='$i'></div>
			    Remarque proposition n° $n_pr : <br />
			    <textarea name='remarque_proposition[]' cols='100' rows='10'>{$proposition->remarque}</textarea>
			   <hr />
EOT;
            } else {
                $html .= <<<EOT
			    <div id='pr$n_pr|an1'>{$proposition->addYear($n_pr, 1)}</div><br />
				<div id='pr$n_pr|an2'><input type='hidden' id='pr-$n_pr-an' name='pr-$n_pr-an' value='1'></div>
			    Remarque proposition n° $n_pr : <br />
			    <textarea name='remarque_proposition[]' cols='100' rows='10'>{$proposition->remarque}</textarea>
			    <hr />
EOT;
            }
        } else {
            $n_pr2 = $n_pr + 1;
            $html .= <<<EOT
			<h3>Proposition commerciale n° $n_pr : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Date de remise : <input name='date_remise[]' onfocus='showCalendarControl(this)' type='text' size='8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Référence : <input name='reference[]' type='text' size='40'></h3><br />
		    <div class='center'>
				<input type='button' onclick='ajoutAnnee({$n_pr})' value='Ajouter Année'>
				<input type='button' onclick='enleveAnnee({$n_pr})' value='Supprimer Année'>
			</div><br />
	        {$this->addYear($n_pr, 1)}
		    <div id='pr$n_pr|an1'></div>
			<br />
			Remarque proposition n° $n_pr : <br />
	        <textarea name='remarque_proposition[]' cols='80' rows='10'>{$proposition->remarque}</textarea>
			<hr />
			<div id='autreProposition$n_pr2'><input type='hidden' id='nb_proposition' name='nb_proposition' value='$n_pr'></div>
			<br />
EOT;
        }
        return $html;
    }

    public function addYear($n_pr, $n_an) {
        if ($this->Id_proposition) {
            $proposition = new Proposition($this->Id_proposition, array());
            $db = connecter();
            $ligne = $db->query('SELECT count(Id_periode) as nb FROM proposition_periode WHERE Id_proposition=' . mysql_real_escape_string((int) $this->Id_proposition) . ' AND annee=' . mysql_real_escape_string($n_an) . '')->fetchRow();
            $nb_periode = $ligne->nb;
            $n_an2 = $n_an + 1;
            $html = <<<EOT
				<div class='center'>
				    Année $n_an :
				</div>
				<input type='button' onclick='ajoutPeriode({$n_pr},{$n_an})' value='Ajouter Période'>
				<input type='button' onclick='enlevePeriode({$n_pr},{$n_an})' value='Supprimer Période'>
				<table>
					<tr>
						<th width='100px'>Période</th>
						<th width='80px'>Durée (mois)</th>
						<th width='50px'>Début</th>
						<th width='50px'>Fin </th>
						<th width='100px'>Coût de revient (&euro;)</th>
						<th width='80px'>CA (&euro;)</th>
						<th width='80px'>Marge (%)</th>
					</tr>
				</table>
EOT;
            if ($nb_periode > 0) {
                $i = 0;
                while ($i < $nb_periode) {
                    ++$i;
                    $html .= <<<EOT
				    <div id='pr$n_pr|pe$i|an$n_an'>
				    {$proposition->addPeriod($n_pr, $i, $n_an)}
				    </div>
EOT;
                    $j = $i + 1;
                }
                $html .= <<<EOT
			    <div id='pr$n_pr|pe$j|an$n_an'><input type='hidden' id='pr-$n_pr-n_periode-an-$n_an' name='pr-$n_pr-n_periode-an-$n_an' value='$i'></div>
EOT;
            } else {
                $html .= <<<EOT
				<div id='pr$n_pr|pe1|an$n_an'>
				{$proposition->addPeriod($n_pr, 1, $n_an)}
				</div>
				<div id='pr$n_pr|pe2|an$n_an'><input type='hidden' id='pr-$n_pr-n_periode-an-$n_an' name='pr-$n_pr-n_periode-an-$n_an' value='1'></div>
EOT;
            }
        } else {
            $n_an2 = $n_an + 1;
            $html = <<<EOT
			<div class='center'>
			    Année $n_an :
			</div>
			<div id='pr$n_pr|an$n_an'>
				<input type='button' onclick='ajoutPeriode({$n_pr},{$n_an})' value='Ajouter Période'>
				<input type='button' onclick='enlevePeriode({$n_pr},{$n_an})' value='Supprimer Période'>
				<table>
					<tr>
						<th width='100px'>Période</th>
						<th width='80px'>Durée (mois)</th>
						<th width='50px'>Début</th>
						<th width='50px'>Fin </th>
						<th width='100px'>Coût de revient (&euro;)</th>
						<th width='80px'>CA (&euro;)</th>
						<th width='80px'>Marge (%)</th>
					</tr>
				</table>
				{$this->addPeriod($n_pr, 1, $n_an)}
				<div id='pr$n_pr|pe2|an$n_an'>
				</div>	
			</div><br /><br />
		    <div id='pr$n_pr|an$n_an2'><input type='hidden' id='pr-$n_pr-an' name='pr-$n_pr-an' value='$n_an'></div>
EOT;
        }
        return $html;
    }

    public function addPeriod($n_pr, $n_pe, $n_an) {
        if ($this->Id_proposition) {
            $db = connecter();
            $result = $db->query('SELECT Id_periode FROM proposition_periode WHERE Id_proposition=' . mysql_real_escape_string((int) $this->Id_proposition) . ' AND annee=' . mysql_real_escape_string($n_an) . '');
            $tabperiode = array();
            while ($ligne = $result->fetchRow()) {
                $tabperiode[] = $ligne->id_periode;
            }
            $periode = new Periode($tabperiode[$n_pe - 1], array());
            $ligne = $db->query('SELECT duree, DATE_FORMAT(debut, "%d-%m-%Y") as debut,
			DATE_FORMAT(fin, "%d-%m-%Y") as fin, cout, ca, marge FROM proposition_periode 
			WHERE Id_proposition=' . mysql_real_escape_string((int) $this->Id_proposition) . ' 
			AND annee=' . mysql_real_escape_string($n_an) . '
			AND Id_periode="' . mysql_real_escape_string($tabperiode[$n_pe - 1]) . '"')->fetchRow();

            $html = '
				<table>
				<tr>
					<td width="100px">
					<img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_PERIOD . '</div>\', FULLHTML);" onmouseout="return nd();"></img>
						<select name="pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" id="pe' . $n_pe . '">
							<option value="">Sélectionner une période</option>
							<option value="">-------------------------</option>
							' . $periode->getList() . '
						</select>
					</td>
					<td width="50px"><input type="text" name="duree_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $ligne->duree . '" size="2" /></td>
					<td width="50px"><input type="text" name="debut_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onfocus="showCalendarControl(this)" value="' . $ligne->debut . '" size="8" /></td>
					<td width="50px"><input type="text" name="fin_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onfocus="showCalendarControl(this)" value="' . $ligne->fin . '" size="8" /></td>
					<td width="100px"><input type="text" name="cout_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $ligne->cout . '" id="cout_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" size="5" /></td>
					<td width="80px"><input type="text" name="ca_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $ligne->ca . '" id="ca_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onkeyup="margeInfogerance(' . $n_pe . ',' . $n_pr . ',' . $n_an . ')" size="12" /></td>
					<td width="80px"><input type="text" id="marge_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" name="marge_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $ligne->marge . '" size="5" /></td>
					<input type="hidden" name="annee' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $n_an . '" />
				</tr>
				</table>
				<input type="hidden" id="cha" value="' . $this->chiffre_affaire . '" />
';
        } else {
            $periode = new Periode('', '');
            $n_pe2 = $n_pe + 1;
            $html = '
			<div id="pr' . $n_pr . '|pe' . $n_pe . '|an' . $n_an . '">
				<table>
				<tr>
					<td width="100px">
						<select name="pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" id="pe' . $n_pe . '">
							<option value="">Sélectionner une période</option>
							<option value="">-------------------------</option>
							' . $periode->getList() . '
						</select>
					</td>
					<td width="50px"><input type="text" name="duree_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" size="2" /></td>
					<td width="50px"><input type="text" name="debut_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onfocus="showCalendarControl(this)" size="8" /></td>
					<td width="50px"><input type="text" name="fin_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onfocus="showCalendarControl(this)" size="8" /></td>
					<td width="100px"><input type="text" name="cout_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" id="cout_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" size="5" /></td>
					<td width="80px"><input type="text" name="ca_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" id="ca_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" onkeyup="margeInfogerance(' . $n_pe . ',' . $n_pr . ',' . $n_an . ')" size="12" /></td>
					<td width="80px"><input type="text" id="marge_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" name="marge_pe' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" size="5" /></td>
					<input type="hidden" name="annee' . $n_pe . '-pr-' . $n_pr . '-an' . $n_an . '" value="' . $n_an . '" />
				</tr>
				</table>
			</div>
			<input type="hidden" id="cha" value="' . $this->chiffre_affaire . '" />
			<div id="pr' . $n_pr . '|pe' . $n_pe2 . '|an' . $n_an . '">
			    <input type="hidden" id="pr-' . $n_pr . '-n_periode-an-' . $n_an . '" name="pr-' . $n_pr . '-n_periode-an-' . $n_an . '" value="' . $n_pe . '">
			</div>
';
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save($Id_pole = null, $save_ca_ress = null) {
        $db = connecter();
        $set = ' SET Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition) . ', date_saisie = "' . mysql_real_escape_string(DATETIME) . '", cout = ' . mysql_real_escape_string((float) $this->cout_total) . ', frais_journalier = ' . mysql_real_escape_string((float) $this->frais_journalier) . ', tarif_journalier = ' . mysql_real_escape_string((float) $this->tarif_journalier) . ', chiffre_affaire = ' . (float) mysql_real_escape_string($this->chiffre_affaire) . ', marge = ' . mysql_real_escape_string((float) $this->marge_totale) . ', remarque = "' . mysql_real_escape_string($this->remarque) . '", Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '
         , intitule_phase1 = "' . mysql_real_escape_string($this->intitule_phase1) . '", cout_phase1 = ' . mysql_real_escape_string((float) $this->cout_phase1) . ', ca_phase1 = ' . mysql_real_escape_string((float) $this->ca_phase1) . ', intitule_phase2 = "' . mysql_real_escape_string($this->intitule_phase2) . '", cout_phase2 = ' . mysql_real_escape_string((float) $this->cout_phase2) . ', ca_phase2 = ' . mysql_real_escape_string((float) $this->ca_phase2) . ', intitule_phase3 = "' . mysql_real_escape_string($this->intitule_phase3) . '", cout_phase3 = ' . mysql_real_escape_string((float) $this->cout_phase3) . ', ca_phase3 = ' . mysql_real_escape_string((float) $this->ca_phase3) . '
		 , cout_phase4 = ' . mysql_real_escape_string((float) $this->cout_phase4) . ', ca_phase4 = ' . mysql_real_escape_string((float) $this->ca_phase4) . ', intitule_phase4 = "' . mysql_real_escape_string($this->intitule_phase4) . '"
		 , intitule_licence = "' . mysql_real_escape_string($this->intitule_licence) . '", cout_licence = ' . mysql_real_escape_string((float) $this->cout_licence) . ', ca_licence = ' . mysql_real_escape_string((float) $this->ca_licence) . ', intitule_materiel = "' . mysql_real_escape_string($this->intitule_materiel) . '", cout_materiel = ' . mysql_real_escape_string((float) $this->cout_materiel) . ', ca_materiel = ' . mysql_real_escape_string((float) $this->ca_materiel) . ', intitule_autre = "' . mysql_real_escape_string($this->intitule_autre) . '", cout_autre = ' . mysql_real_escape_string((float) $this->cout_autre) . ', ca_autre = ' . mysql_real_escape_string((float) $this->ca_autre) . '
		 , date_remise = "' . mysql_real_escape_string(DateMysqltoFr($this->date_remise, 'mysql')) . '", reference = "' . mysql_real_escape_string($this->reference) . '"
		 , typeAT = "' . mysql_real_escape_string($this->typeAT) . '", typeInfog = "' . mysql_real_escape_string($this->typeInfog) . '"
		 , pct_site = ' . mysql_real_escape_string((float) $this->pct_site) . ', pct_dist = ' . mysql_real_escape_string((float) $this->pct_dist) . '';

        if ($this->Id_proposition) {
            $requete = 'UPDATE proposition ' . $set . ' WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition) . '';
        } else {
            $requete = 'INSERT INTO proposition ' . $set;
        }

        $t = $db->query($requete);
        $Id_proposition = ($this->Id_proposition == '') ? mysql_insert_id() : $this->Id_proposition;

        if ($this->Id_proposition) {
            $db->query('DELETE FROM proposition_periode WHERE Id_proposition=' . mysql_real_escape_string((int) $this->Id_proposition) . '');
            $pond = $this->getLastWeighting();
        }

        $nb_ressource = count($this->ressource);
        $i = 0;
        $ca_total = 0;
        $cout_revient = 0;
        $a = array();
        while ($i < $nb_ressource) {
            if ($this->ressource[$i]) {
                if ($this->ressource[$i] == 'MAT' || $this->ressource[$i] == 'LOG' || $this->ressource[$i] == 'LIC') {
                    $this->ca_ressource[$i] = $this->tarif_journalier[$i];
                    $ca_total += $this->ca_ressource[$i];
                    $cout_revient += $this->cout_journalier[$i];
                } else {
                    $this->ca_ressource[$i] = ($this->duree_ressource[$i] * $this->tarif_journalier[$i]);
                    $ca_total += $this->ca_ressource[$i];
                    $cout_revient += ($this->duree_ressource[$i] * $this->cout_journalier[$i]);
                }
                if ($this->tarif_journalier[$i] == 0)
                    $this->marge_ressource[$i] = 100;
                else
                    $this->marge_ressource[$i] = 100 * ($this->tarif_journalier[$i] - ($this->frais_journalier[$i] + $this->cout_journalier[$i])) / $this->tarif_journalier[$i];
                
                $this->marge_ressource[$i] = round($this->marge_ressource[$i], 2);
                $this->ca_ressource[$i] = round($this->ca_ressource[$i], 2);
                $db->query('INSERT INTO proposition_ressource SET Id_prop_ress = ' . (int) $this->id_prop_ressource[$i] . ', Id_proposition=' . mysql_real_escape_string($Id_proposition) . ', Id_ressource="' . mysql_real_escape_string($this->ressource[$i]) . '",
			    cout_journalier=' . mysql_real_escape_string((float) $this->cout_journalier[$i]) . ', frais_journalier=' . mysql_real_escape_string((float) $this->frais_journalier[$i]) . ',
			    tarif_journalier=' . mysql_real_escape_string((float) $this->tarif_journalier[$i]) . ', duree="' . mysql_real_escape_string($this->duree_ressource[$i]) . '",
			    marge="' . mysql_real_escape_string((float) $this->marge_ressource[$i]) . '", ca="' . mysql_real_escape_string((float) $this->ca_ressource[$i]) . '",
			    debut="' . mysql_real_escape_string(DateMysqltoFr($this->debut_ressource[$i], 'mysql')) . '", fin ="' . mysql_real_escape_string(DateMysqltoFr($this->fin_ressource[$i], 'mysql')) . '",
			    fin_prev ="' . mysql_real_escape_string(DateMysqltoFr($this->fin_prev_ressource[$i], 'mysql')) . '", type ="' . mysql_real_escape_string($this->type_ressource[$i]) . '", inclus ="0",
                            type_ressource ="' . mysql_real_escape_string($this->type_ressource_prop[$i]) . '"
                            ON DUPLICATE KEY UPDATE
                            Id_ressource="' . mysql_real_escape_string($this->ressource[$i]) . '",
			    cout_journalier=' . mysql_real_escape_string((float) $this->cout_journalier[$i]) . ', frais_journalier=' . mysql_real_escape_string((float) $this->frais_journalier[$i]) . ',
			    tarif_journalier=' . mysql_real_escape_string((float) $this->tarif_journalier[$i]) . ', duree="' . mysql_real_escape_string($this->duree_ressource[$i]) . '",
			    marge="' . mysql_real_escape_string((float) $this->marge_ressource[$i]) . '", ca="' . mysql_real_escape_string((float) $this->ca_ressource[$i]) . '",
			    debut="' . mysql_real_escape_string(DateMysqltoFr($this->debut_ressource[$i], 'mysql')) . '", fin ="' . mysql_real_escape_string(DateMysqltoFr($this->fin_ressource[$i], 'mysql')) . '",
			    fin_prev ="' . mysql_real_escape_string(DateMysqltoFr($this->fin_prev_ressource[$i], 'mysql')) . '", type ="' . mysql_real_escape_string($this->type_ressource[$i]) . '", inclus ="0",
                            type_ressource ="' . mysql_real_escape_string($this->type_ressource_prop[$i]) . '"');
            }
            array_push($a, DateMysqltoFr($this->fin_ressource[$i], 'mysql'));
            ++$i;
        }

        if ($pond) {
            $ponderation = $pond->ponderation;
            $ca = $pond->chiffre_affaire;
        } else {
            $ponderation = 0;
            $ca = 0;
        }

        $affaire = new Affaire($this->Id_affaire, array());
        if ($affaire->Id_statut == 5 || $affaire->Id_statut == 8 || $affaire->Id_statut == 9) {
            $this->ponderation = 100;
            $this->ca_ponderee = ($this->chiffre_affaire / 100 ) * $this->ponderation;
        }
        elseif($affaire->Id_statut == 3 || $affaire->Id_statut == 4) {
            if($a[0]) {
                $planning = new Planning($affaire->Id_planning, array());
                $d = max($a);
                $planning->date_fin_commande = $d;
                $planning->date_fin_previsionnelle = $d;
                $planning->save();
            }
        }
        if ((($ponderation != $this->ponderation && $this->ponderation != 0) || ($this->chiffre_affaire != $ca && $this->chiffre_affaire != ''))) {
            $db = connecter();
            $db->query('INSERT INTO ponderation SET Id_proposition = ' . mysql_real_escape_string($Id_proposition) . ', date = "' . mysql_real_escape_string(DATETIME) . '",
                    ponderation = ' . mysql_real_escape_string((int) $this->ponderation) . ', chiffre_affaire = ' . mysql_real_escape_string((float) $this->chiffre_affaire) . ',
                    ca_ponderee = ' . mysql_real_escape_string((float) $this->ca_ponderee));
        }

        
        //Pour les affaires du pôle formation,
        if ($Id_pole == 3) {
            //insertion dans la base de données des données spécifiques au pôle formation
            $set1 = 'SET Id_proposition="' . mysql_real_escape_string($Id_proposition) . '", nb_inscrit = "' . mysql_real_escape_string($this->nb_inscrit) . '", lien_bdc="' . mysql_real_escape_string($this->lien_bdc) . '"';
            if ($this->Id_proposition) {
                $requete1 = 'UPDATE proposition_formation ' . $set1 . ' WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition) . '';
                //suppression de toutes les inscriptions précédentes
                $requete = 'DELETE FROM participant WHERE Id_affaire=' . mysql_real_escape_string((int) $this->Id_affaire) . '';
                $db->query($requete);
            } else {
                $requete1 = 'INSERT INTO proposition_formation ' . $set1;
            }
            $db->query($requete1);

            //Enregistrement des inscriptions à la session pour l'affaire
            $nb_participant = count($this->nomParticipant);
            $i = 0;
            while ($i < $nb_participant) {
                if (($this->nomParticipant[$i] != '' && $this->nomParticipant[$i] != ' ') || ($this->prix_unitaireParticipant[$i] != 0 && $this->prix_unitaireParticipant[$i] != '')) {
                    $this->nomParticipant[$i] = strtoupper($this->nomParticipant[$i]);
                    $this->prenomParticipant[$i] = formatPrenom($this->prenomParticipant[$i]);
                    $db->query('INSERT INTO participant set Id_affaire="' . mysql_real_escape_string($this->Id_affaire) . '", nom="' . mysql_real_escape_string($this->nomParticipant[$i]) . '", prenom="' . mysql_real_escape_string($this->prenomParticipant[$i]) . '", prix_unitaire=' . mysql_real_escape_string((float) $this->prix_unitaireParticipant[$i]) . '');
                }
                ++$i;
            }
        }
    }

    /**
     * Mise à jour des informations d'une ressource lors de la modification du contrat délégation
     * 
     * @param int Id_prop_ress Identifiant de ressource sur la proposition
     */
    public static function updateRessource($Id_prop_ress = null, $information = null) {
        $db = connecter();
        if ($information['Id_ressource'] == 'MAT' || $information['Id_ressource'] == 'LOG' || $information['Id_ressource'] == 'LIC') {
            $ca = round($information['tarif_journalier'], 2);
        } else {
            $ca = round($information['tarif_journalier'] * $information['duree_mission'], 2);
        }
        if ($information['tarif_journalier'] == 0)
            $marge = 100;
        else
            $marge = 100 * ($information['tarif_journalier'] - ($information['frais_journalier'] + $information['cout_journalier'])) / $information['tarif_journalier'];
        $marge = round($marge, 2);

        $db->query('UPDATE proposition_ressource SET Id_ressource = "' . $information['Id_ressource'] . '", 
                    cout_journalier = "' . $information['cout_journalier'] . '", frais_journalier = "' . $information['frais_journalier'] . '",
                    tarif_journalier = "' . $information['tarif_journalier'] . '", debut = "' . DateMysqltoFr($information['debut_mission'], 'mysql') . '",
                    fin = "' . DateMysqltoFr($information['fin_mission'], 'mysql') . '", fin_prev = "' . DateMysqltoFr($information['fin_mission'], 'mysql') . '",
                    duree = "' . $information['duree_mission'] . '", ca = "' . $ca . '", marge = "' . $marge . '"
                    WHERE Id_prop_ress = ' . mysql_real_escape_string((int) $Id_prop_ress));
    }

    /**
     * Enregistrement d'une pondération à partir du listing de pondération
     *
     * @return string Etat de l'enregistrement
     */
    public function saveWeighting() {
        $db = connecter();
        $pond = $this->getLastWeighting();

        if ($pond) {
            $ponderation = $pond->ponderation;
            $ca = $pond->chiffre_affaire;
        } else {
            $ponderation = 0;
            $ca = 0;
        }

        if (($ponderation != $this->ponderation) || ($this->chiffre_affaire != $ca)) {
            $resul = $db->query('INSERT INTO ponderation SET Id_proposition = ' . mysql_real_escape_string($this->Id_proposition) . ', date = "' . mysql_real_escape_string(DATETIME) . '",
                                ponderation = ' . mysql_real_escape_string((int) $this->ponderation) . ', chiffre_affaire = ' . mysql_real_escape_string((float) $this->chiffre_affaire) . ',
                                ca_ponderee = ' . mysql_real_escape_string((float) $this->ca_ponderee));
        }
        return 'Enregistré.';
    }

    /**
     * Consultation d'une proposition
     *
     * @param int Identifiant du type de contrat
     * @param Int identifiant du pôle
     *
     * @return string
     */
    public function consultation($Id_type_contrat, $Id_pole=NULL, $seeWeightingHistory = true) {
        if ($Id_type_contrat == 1) {
            $html = '<h2>Proposition commerciale</h2><br /><br />
                    Type de facturation : ' . $this->typeAT . ' |
                    Coût de revient total : ' . $this->cout_total . ' &euro; |
                    Chiffre d\'affaire : ' . $this->chiffre_affaire . ' &euro;
                    <br /><br />';
            if ($seeWeightingHistory) {
                $html .= '<div class="center">
                            ' . $this->getWeightingHistory() . '
                        </div>';
            }
            $html .= '<br />' . $htmlRessource;
        } elseif ($Id_type_contrat == 2) {
            $html = '<h2>Proposition commerciale</h2><br />
                    Type Infogérance : ' . $this->typeInfog . ' | Répartition</h3> Site : ' . $this->pct_site . ' / Distance : ' . $this->pct_dist . '<br /><br />
                    <div class="center">
                    Coût de revient total : ' . $this->cout_total . ' &euro;';
            if ($seeWeightingHistory)
                $html .= $this->getWeightingHistory();
            $html .= '</div><br />';
        } elseif ($Id_type_contrat == 3) {
            //affichage en consultation pour les affaires du pôle formation
            if ($Id_pole == 3) {
                $this->date_remise = FormatageDate($this->date_remise);
                $html = '<h2>Proposition commerciale</h2>
                        <div class="left"><br/><br/>
                            Date de remise : ' . $this->date_remise . '<br/>
                            <br/><h3>Liste des participants :</h3>
                            ' . $this->participantList() . '
                        </div><br/><br/>
                        <div class="left">
                            Remarque :<br/>
                            ' . $this->remarque . '<br/><br/>
                            <b>Nombre d\'inscrits : </b>' . (int) $this->nb_inscrit . '
                            <br />
                        </div><br/><br/>
                        <br/><br/><br/><br/>
                        <div class="left">
                            Total des charges : ' . (float) $this->cout_total . ' euros 
                        </div>
                        <br/><br/>';
                if ($seeWeightingHistory) {
                    $html .= '<div class="center">
                                ' . $this->getWeightingHistory() . '
                            </div>';
                }

                //affichage d'un lien vers le bon de commande
                if ($this->lien_bdc != '') {
                    $html .= '<div class="right">Bon de commande : <a href="javascript:ouvre_popup(\'' . BDC_DIR . $this->lien_bdc . '\')">' . $this->lien_bdc . '</a><br /></div><br /><br/>';
                }
            } else {
                $html = '<h2>Proposition commerciale</h2><br /><br />
                        <div class="center">
                            Coût de revient total : ' . $this->cout_total . '<br />';
                if ($seeWeightingHistory)
                    $html .= $this->getWeightingHistory();
                $html .= '</div><br />';
            }
        }
        return $html;
    }

    /**
     * Suppression d'une proposition
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM proposition WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition));
        $db->query('DELETE FROM ponderation WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition));
        $db->query('DELETE FROM proposition_ressource WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition));
        $db->query('DELETE FROM proposition_periode WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition));
        //pour les affaires du pôle formation, suppression des données dans la table proposition_formation
        $db->query('DELETE FROM proposition_formation WHERE Id_proposition = ' . mysql_real_escape_string((int) $this->Id_proposition));
    }

    /**
     * Affichage des liens pour consulter les propositions
     *
     * @return string
     */
    public static function statusSearch() {
        $db = connecter();
        $result = $db->query('SELECT * FROM statut');
        while ($ligne = $result->fetchRow()) {
            $html .= '<a href="../com/index.php?a=consulterProposition&amp;Id_statut_prop=' . $ligne->id_statut . '">Propales ' . $ligne->libelle . '</a>';
        }
        return $html;
    }

    /**
     * Affichage des liens pour consulter les propositions
     *
     * @return string
     */
    public function getNbResources() {
        $db = connecter();
        return $nb = $db->query('SELECT COUNT(*) FROM proposition_ressource WHERE Id_proposition = ' . $this->Id_proposition)->fetchOne();
        ;
    }

    /**
     * Affichage des filtres de recherche pour les proposition avant-vente
     *
     * @return string
     */
    public static function searchForm($id_statut) {
        $type_contrat = new TypeContrat($_SESSION['filtre']['Id_type_contrat_prop'], array());
        $pole = new Pole($_SESSION['filtre']['Id_pole_prop'], array());
        $html = '
			<select id="Id_type_contrat_prop" onchange="afficherPropositionAV({data: ' . $id_statut . '})">
				<option value="">Par type de contrat</option>
				<option value="">----------------------------</option>
				' . $type_contrat->getList() . '
			</select>
			&nbsp;&nbsp;
			<select id="Id_pole_prop" onchange="afficherPropositionAV({data: ' . $id_statut . '})">
				<option value="">Par pôle</option>
				<option value="">----------------------------</option>
				' . $pole->getList() . '
			</select>
			&nbsp;&nbsp;
			<input type="button" onclick="afficherPropositionAV({data: ' . $id_statut . '})" value="Go !">
';
        return $html;
    }

    /**
     * Affichage des Proposition pistes
     */
    public static function rechercher1($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,
                    Id_compte,nb_jour_estime,resp_qualif,resp_tec1,resp_tec2,resume FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE Id_statut=1 AND (resp_tec1="" or resp_tec2="")';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '1', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_demande' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', null, 'nb_jour_estime', null, null, array('Proposition', 'showEstimatedCharge'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_demande');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=1&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $res = $datagrid->render('CSV', $rendererOptions); 
        }
        return $html;
    }

    /**
     * Affichage des Proposition non répondues
     */
    public static function rechercher2($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,
                Id_compte,resp_qualif,resume FROM affaire 
                INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
		INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire 
                INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
		LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                WHERE Id_statut=2';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '2', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_demande' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_demande');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=2&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Proposition en cours de rédaction
     */
    public static function rechercher3($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,
                createur,Id_compte,nb_jour_estime,resp_qualif,resp_tec1,resp_tec2,resume,date_limite FROM affaire 
                INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
                INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
		LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                WHERE Id_statut=3';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '3', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_demande' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', null, 'nb_jour_estime', null, null, array('Proposition', 'showEstimatedCharge'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Date de remise souhaitée', null, 'date_limite', null, null, array('Proposition', 'showAnticipatedLimitDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_demande');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=3&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $cDateSouhaitee = new Structures_DataGrid_Column('Date de remise souhaitée', 'date_limite', 'date_limite');
            $cDateSouhaitee->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateSouhaitee);
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Proposition remises, en attente
     */
    public static function rechercher4($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,Id_compte,nb_jour_estime,
            resp_qualif,resp_tec1,resp_tec2,resume,hs.date AS date_histo FROM affaire 
            INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
            INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
            INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
            LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
            INNER JOIN historique_statut hs ON hs.Id_affaire = affaire.Id_affaire AND hs.Id_statut = affaire.Id_statut
            WHERE affaire.Id_statut=4';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '4', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_modification' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', null, 'nb_jour_estime', null, null, array('Proposition', 'showEstimatedCharge'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Date de remise', null, 'date_histo', null, null, array('Proposition', 'showLimitDate'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => true)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_modification');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=4&?' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $cDateSouhaitee = new Structures_DataGrid_Column('Date de remise', 'date_histo', 'date_histo');
            $cDateSouhaitee->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateSouhaitee);
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => true, 'csv' => true)));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Proposition gagnées
     */
    public static function rechercher5($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,
                    Id_compte,resp_tec1,resp_tec2,resume FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire 
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE Id_statut=5';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '5', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_modification' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA du PROJET (&euro;)', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_modification');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=5&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA du PROJET (&euro;)', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => true, 'csv' => true)));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Propositions perdues
     */
    public static function rechercher6($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT DISTINCT(affaire.Id_affaire),date_demande,ag.libelle AS agence,createur,
                    Id_compte,resp_qualif,resp_tec1,resp_tec2,resume FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE affaire.Id_statut=6';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '6', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_demande' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_demande');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=6&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false, 'csv' => true)));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Proposition à attribuer
     */
    public static function rechercher7($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,
                    Id_compte,repondre,nb_jour_estime,resp_qualif,date_limite FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire 
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN decision ON decision.Id_affaire=affaire.Id_affaire
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE Id_statut=7';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '7', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_modification' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', null, 'nb_jour_estime', null, null, array('Proposition', 'showEstimatedCharge'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', null, 'resp_qualif', null, null, array('Proposition', 'showRespQual'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Date de remise souhaitée', null, 'date_limite', null, null, array('Proposition', 'showAnticipatedLimitDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_modification');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=7&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Qualif', 'resp_qualif', 'resp_qualif'));
            $cDateSouhaitee = new Structures_DataGrid_Column('Date de remise souhaitée', 'date_limite', 'date_limite');
            $cDateSouhaitee->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateSouhaitee);
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Propositions opérationnelles
     */
    public static function rechercher8($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,Id_compte,
                    resp_tec1,resp_tec2,resume FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire 
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE Id_statut=8';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '8', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_modification' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_modification');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=8&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false, 'csv' => true)));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage des Propositions terminées
     */
    public static function rechercher9($Id_type_contrat = null, $Id_pole = null, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_type_contrat_prop', 'Id_pole_prop', 'output');
        $requete = 'SELECT affaire.Id_affaire,date_demande,ag.libelle AS agence,createur,Id_compte,
                    resp_tec1,resp_tec2,resume FROM affaire 
                    INNER JOIN description ON affaire.Id_affaire=description.Id_affaire 
                    INNER JOIN planning ON planning.Id_affaire=affaire.Id_affaire 
                    INNER JOIN agence ag ON ag.Id_agence=affaire.Id_agence
                    LEFT JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire 
                    WHERE Id_statut=9';
        if ($Id_type_contrat) {
            $requete .= ' AND affaire.Id_type_contrat =' . (int) $Id_type_contrat . '';
        }
        if ($Id_pole) {
            $requete.= ' AND affaire_pole.Id_pole=' . (int) $Id_pole;
        }

        $db = connecter();

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            $datagrid = new Structures_DataGrid(TAILLE_LISTE);
            $datagrid->setRendererOption('onMove', 'afficherPropositionAV', true);
            $datagrid->setRendererOption('onMoveData', '9', true);
            $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
            $datagrid->setRendererOption('oddRowAttributes', array('class' => 'rowodd'), true);
            $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
            $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);
            $datagrid->setDefaultSort(array('date_modification' => 'DESC'));

            $test = $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $datagrid->addColumn(new Structures_DataGrid_Column('Date demande', null, 'date_demande', null, null, array('Proposition', 'showApplicationDate'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', null, 'agence', null, null, array('Proposition', 'showAgency'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', null, 'createur', null, null, array('Proposition', 'showCreator'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec1', null, null, array('Proposition', 'showRespTec1'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', null, 'resp_tec2', null, null, array('Proposition', 'showRespTec2'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false)));
            $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('Proposition', 'showButtons'), array('color' => false)));
            $nbAffaires = $datagrid->getRecordCount();

            if (!empty($nbAffaires)) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'date_modification');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'DESC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterPropositionAV&Id_statut_prop=9&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= '<div class="hovercolored" style="clear:both">' . $datagrid->getOutput() . '</div>';
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="affaires.csv"');

            $datagrid = new Structures_DataGrid();
            $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
            $rendererOptions = array( 'filename' => "affaires.csv", 'numberAlign' => false, 'delimiter' => ";");
            $datagrid->bind($requete, array('dbc' => $db), 'MDB2');
            $cDateDemande = new Structures_DataGrid_Column('Date demande', 'date_demande', 'date_demande');
            $cDateDemande->format('dateFromMysql', 'd/m/Y');
            $datagrid->addColumn($cDateDemande);
            $datagrid->addColumn(new Structures_DataGrid_Column('Agence', 'agence', 'agence'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Commercial', 'createur', 'createur'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Client', null, null, null, null, array('Proposition', 'showCustomer'), array('color' => false, 'csv' => true)));
            $datagrid->addColumn(new Structures_DataGrid_Column('Charge estimée en J', 'nb_jour_estime', 'nb_jour_estime'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec1', 'resp_tec1'));
            $datagrid->addColumn(new Structures_DataGrid_Column('Resp Technique', 'resp_tec2', 'resp_tec2'));
            $datagrid->addColumn(new Structures_DataGrid_Column('CA', null, null, null, null, array('Proposition', 'showRevenue'), array('color' => false, 'csv' => true)));
            $res = $datagrid->render('CSV', $rendererOptions);
        }
        return $html;
    }

    /**
     * Affichage du chiffre d'affaire de la proposition
     *
     * @param int Identifiant de la proposition
     *
     * @return double
     */
    public static function getCa($i) {
        $db = connecter();
        return $db->query('SELECT chiffre_affaire FROM proposition WHERE Id_proposition=' . mysql_real_escape_string((int) $i))->fetchRow()->chiffre_affaire;
    }

    /**
     * Affichage de la marge de la proposition
     *
     * @param int Identifiant de la proposition
     *
     * @return double
     */
    public static function getMarge($i) {
        $db = connecter();
        return $db->query('SELECT marge FROM proposition WHERE Id_proposition=' . mysql_real_escape_string((int) $i))->fetchRow()->marge;
    }

    /**
     * Formulaire de proposition commerciale pour les affaires du pôle formation
     *
     * @return string
     */
    public function formPropositionSession() {
        //Si un bon de commande est déjà lié à l'affaire, affichage d'un lien vers le bon de commande actuel en pop up et possibilité de changer le bon de commande
        if ($this->lien_bdc != '') {
            $bdc = '<input Id="bdc_actuel" name="bdc_actuel" type="hidden" value="' . $this->lien_bdc . '">
					Bon de commande actuel : <a href="javascript:ouvre_popup(\'' . BDC_DIR . $this->lien_bdc . '\')">' . $this->lien_bdc . '&nbsp;&nbsp;</a><br /><br />';
        } else {
            $bdc = '<input Id="bdc_actuel" name="bdc_actuel" type="hidden" value="">';
        }
        $affaire = new Affaire($this->Id_affaire, array());
        if (!empty($this->Id_affaire)) {
            $ponderation = $this->getLastWeighting();
            $histoPonderation = $this->getWeightingHistory();
        }
        else
            $ponderation = 0;
        if ($affaire->Id_statut != 3 && $affaire->Id_statut != 4)
            $readonly = 'readonly="readonly" disabled="disabled"';
        $select[$ponderation->ponderation] = "selected='selected'";
        //affichage de la date de remise et des remarques
        $html .= '<br/>
					<div class="left"><br/><br/>
					Date de remise : <input name="date_remise" onfocus="showCalendarControl(this)" type="text" value="' . FormatageDate($this->date_remise) . '" size="8"><br/><br/><br/>
					<input name="Id_proposition" type="hidden" value="' . $this->Id_proposition . '">
					</div>
					<div class="right">
						Remarque :<br/>
							<textarea name="remarque_proposition">' . $this->remarque . '</textarea>
				    </div><br/>
		            <div class="center"><br/><br/><b>Inscription des participants :</b><br/><br>
						<b>
								Nom<span class="marge"></span><span class="marge"></span>&nbsp;&nbsp;&nbsp;&nbsp;
								Prénom<span class="marge"></span>&nbsp;&nbsp;
								Prix inscription (euros)<span class="marge"></span><span class="marge"></span>
						</b>
								' . $this->formParticipant() . '
					<br/><div id="nb_inscription"><input type="button" onclick="ajoutParticipant()" value="Ajouter Inscription"><span class="marge"></span><span class="marge"></span>
					<b>Nombre d\'inscrits : </b><input type="text"  onkeyup="infoSessionProposition1()" id="nb_inscrit" name="nb_inscrit" value="' . (int) $this->nb_inscrit . '"></div>
					<br/><br/><br/>
		            <br/><br/>
                                <table> 
					<tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left" rowspan="2"><b><a href="javascript:void(0)" onclick="window.open(\'../membre/index.php?a=consulterRefPonderation\')">Référentiel de Pondération</a></b></td>
						<td width="200" height="25" align="left"></td>
						<td width="100" height="25" align="left"></td>
					</tr>
				</table>
				<div id="ca">
				<table> 
					<tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">CA total :</td>
						<td width="200" height="25" align="left"><input type="text" onkeyup="infoSessionProposition1()" id="chiffre_affaire" name="chiffre_affaire" value="' . (float) $this->chiffre_affaire . '" > euros &nbsp;&nbsp;<input type="button" onclick="calculCaAffaireSession(3,0)" value="Somme des inscriptions"></td>
						<td width="100" height="25" align="left"></td>
					</tr>
				</table>
				</div>
                                    <div id="chargeAffaireSession">
                                        <table>	
                                            <tr>
                                                    <td width="110" height="25"></td>
                                                    <td width="110" height="25" align="left">Total des charges :</td>
                                                    <td width="200" height="25" align="left"><input type="text" id="cout_total" name="cout_total" value="' . (float) $this->cout_total . '" readonly=true> euros </td>
                                                    <td width="100" height="25"></td>
                                            </tr>
                                            <tr>
                                                    <td width="110" height="25"></td>
                                                    <td width="110" height="25" align="left">Marge :</td>
                                                    <td width="200" height="25" align="left"><input type="text" id="marge_totale" name="marge_totale" value="' . (float) $this->marge_totale . '" readonly=true> % </td>
                                                    <td width="100" height="25"></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <table>
                                        <tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">Pondération :</td>
						<td width="200" height="25" align="left">
                                                    <select name="ponderation" id="ponderation"  readonly="readonly" ' . $readonly . '>
                                                        <option value="10" ' . $select[10] . '>10%</option>
                                                        <option value="20" ' . $select[20] . '>20%</option>
                                                        <option value="40" ' . $select[40] . '>40%</option>
                                                        <option value="60" ' . $select[60] . '>60%</option>
                                                        <option value="80" ' . $select[80] . '>80%</option>
                                                        <option value="100" ' . $select[100] . '>100%</option>
                                                    </select>
                                                </td>
						<td width="100" height="25"></td>
					</tr>
                                        <tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">CA pondéré :</td>
						<td width="200" height="25" align="left"><input type="text" id="ca_pondere" name="ca_pondere" readonly="readonly" ' . $readonly . ' value="' . $ponderation->ca_ponderee . '" /> &nbsp;&nbsp;<input type="button" value="Calculer" onclick="$(ca_pondere).value = Math.round($(chiffre_affaire).value * ($(ponderation).value / 100))"></td>
						<td width="100" height="25"></td>
					</tr>
				</table><br /> ' . $histoPonderation . '
				<br/><br/><br/></div><div class="right" align="right">
				' . $bdc . '
				Bon de Commande : 
				<input type="hidden" name="MAX_FILE_SIZE" value="6000000">
				<input id="acces" name="lien_bdc" type="file">&nbsp;<br/><br/>
				</div><div class="center"> </div>';

        return $html;
    }

    /**
     * Formulaire pour inscriptions pour les propositions commerciales du pole formation
     *
     * @return string
     */
    public function formParticipant() {
        $count = count($this->nomParticipant);
        //Si le tableau est déjà rempli : formulaire pour modification, il faut afficher toutes les dinscriptions
        if ($count > 0) {
            $html = '';
            $cpt = 0;
            $nb = 1;
            //emboitement des div et afichage de chaque inscription
            while ($nb < $count) {
                $nb2 = $nb + 1;
                $html .= '<div id="inscription' . $nb . '">
								<b>' . $nb . '-</b>
								<input onkeyup="calculNbInscrit(1)" id="nomParticipant' . $nb . '" name="nomParticipant[]" type="text" value="' . $this->nomParticipant[$cpt] . '">
								<input id="prenomParticipant' . $nb . '" name="prenomParticipant[]" type="text" value="' . $this->prenomParticipant[$cpt] . '">
								<input onkeyup="calculCaAffaireSession(1, 0)" id="prix_unitaireParticipant' . $nb . '" name="prix_unitaireParticipant[]" type="text" value="' . $this->prix_unitaireParticipant[$cpt] . '"><span class="marge"></span>
								<input type="button" onclick="enleveParticipant(' . $nb . ')" value="Supprimer Inscription">
								<br/></div>
								<div id="autreParticipant' . $nb2 . '">';
                ++$nb;
                ++$cpt;
            }
            // ajout de la dernière inscription
            $html .= $this->addParticipant($count);
            $nb = 1;
            // fermeture des div
            while ($nb < $count) {
                $html .= '</div>';
                ++$nb;
            }
        }
        // si tableau est vide, affichage d'une seule inscription
        elseif ($count == 0) {
            $html .= $this->addParticipant(1);
        }

        return $html;
    }

    /**
     * Formulaire pour ajouter des inscriptions pour les propositions commerciales du pole formation
     *
     * @param int numero de la nouvelle inscription
     *
     * @return string
     */
    public function addParticipant($nb) {
        //numéro de la prochaine inscription
        $nb2 = $nb + 1;
        //formulaire pour une nouvelle inscription
        $html = '<div id="inscription' . $nb . '">
					<b>' . $nb . '-</b>
					<input onkeyup="calculNbInscrit(1)" id="nomParticipant' . $nb . '" name="nomParticipant[]" type="text" value="' . $this->nomParticipant[$nb - 1] . '">
					<input id="prenomParticipant' . $nb . '" name="prenomParticipant[]" type="text" value="' . $this->prenomParticipant[$nb - 1] . '">
					<input onkeyup="calculCaAffaireSession(1, 0)" id="prix_unitaireParticipant' . $nb . '" name="prix_unitaireParticipant[]" type="text" value="' . $this->prix_unitaireParticipant[$nb - 1] . '"><span class="marge"></span>
					<input type="button" onclick="enleveParticipant(' . $nb . ')" value="Supprimer Inscription">
					<br/></div>				
					<div id="autreParticipant' . $nb2 . '"><input type="hidden" id="nb_participant" name="nb_participant" value=' . $nb . '></div>
				';

        return $html;
    }

    /**
     * Affichage de la liste des inscrits pour les propositions commerciales du pole formation
     *
     * @return string
     */
    public function participantList() {
        $nb_participant = count($this->nomParticipant);
        //Si le tableau est vide : pas d'inscrits pour l'affaire
        if (!$nb_participant) {
            $html = '<h3>Il n\'y a pas de participants inscrits</h3>';
        }
        //Sinon affichage des participants
        else {
            $html = '<table>
						<tr>
							<td width="20" height="25"></td>
							<td width="110" height="25" align="left"><b>Nom</b></td>
							<td width="110" height="25" align="left"><b>Prénom</b></td>
							<td width="200" height="25" align="left"><b>Prix unitaire</b> (en euros)</td>
							<td width="100" height="25"></td>
						</tr>';
            $i = 0;
            while ($i < $nb_participant) {
                $html .='<tr>
							<td width="20" height="25"></td>
							<td width="110" height="25" align="left">' . $this->nomParticipant[$i] . '</td>
							<td width="110" height="25" align="left">' . $this->prenomParticipant[$i] . '</td>
							<td width="200" height="25" align="left">' . $this->prix_unitaireParticipant[$i] . '</td>
							<td width="100" height="25"></td>
						</tr>';
                ++$i;
            }
            $html .='</table>';
        }
        return $html;
    }

    /**
     * Suppression d'une ressource dans une proposition d'affaire
     *
     */
    public static function deleteRessourceProposition($IdPropRess) {
        $db = connecter();
        $db->query('DELETE FROM proposition_ressource WHERE Id_prop_ress=' . mysql_real_escape_string((int) $IdPropRess));
    }

    /**
     * Récupération de la denière pondération
     *
     * @return object Object representation of the weighting database
     */
    public function getLastWeighting() {
        $db = connecter();
        $result = $db->query('SELECT ponderation,chiffre_affaire,ca_ponderee FROM ponderation
                    WHERE Id_proposition = ' . $this->Id_proposition . ' 
                    AND date = (SELECT MAX(date) FROM ponderation WHERE Id_proposition = ' . $this->Id_proposition . ')')
                ->fetchRow();

        return $result;
    }

    /**
     * Récupération de l'historique de pondération
     *
     * @return string Object representation of the weighting database
     */
    public function getWeightingHistory() {
        $db = connecter();
        $result = $db->query('SELECT DATE_FORMAT(date, "%d-%m-%Y %H:%i") AS date2,ponderation,chiffre_affaire,ca_ponderee FROM ponderation
                    WHERE Id_proposition = ' . $this->Id_proposition . ' ORDER BY date DESC');

        if ($result->numRows() == 0)
            return '';
        else {
            $html = '<table class="sortable">
                     <thead>
                        <tr>
                            <th>Date</th>
                            <th>CA</th>
                            <th>Pondération</th>
                            <th>CA pondérée</th>
                        </tr>
                    </thead>
                    <tbody>    ';
            while ($ligne = $result->fetchRow()) {
                $html .= '<tr>
                            <td>' . $ligne->date2 . '</td>
                            <td>' . number_format($ligne->chiffre_affaire, 0, ',', ' ') . ' &euro;</td>
                            <td>' . $ligne->ponderation . ' %</td>
                            <td>' . number_format($ligne->ca_ponderee, 0, ',', ' ') . ' &euro;</td>
                        </tr>';
            }
            $html .= '</tbody></table>';
            return $html;
        }
    }

    /**
     * Récupère l'identifiant du contrat délégation selon une proposition et une ressource
     *
     * @param string Identifiant de la ressource sur la proposition
     *
     * @return int Identifiant du contrat délégation
     */
    public function getIdCD($Id_prop_ress) {
        $db = connecter();
        return $db->query('SELECT Id_contrat_delegation FROM contrat_delegation 
            WHERE Id_prop_ress = "' . mysql_real_escape_string($Id_prop_ress) . '"')->fetchRow()->id_contrat_delegation;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showApplicationDate($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }

        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['date_demande'])
            return '<div ' . $j . ' ' . $info . '>' . DateMysqltoFr($record['date_demande']) . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showAnticipatedLimitDate($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['date_limite'])
            return '<div ' . $j . ' ' . $info . '>' . DateMysqltoFr($record['date_limite']) . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showLimitDate($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['date_histo']) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            return '<div ' . $j . ' ' . $info . '>' . $date . '</div>';
        }
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showAgency($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['agence'])
            return '<div ' . $j . ' ' . $info . '>' . $record['agence'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showCreator($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['createur'])
            return '<div ' . $j . ' ' . $info . '>' . $record['createur'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showCustomer($params, $userParam) {
        extract($params);
        $compte = CompteFactory::create(null, $record['id_compte']);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if(!$userParam['csv']) {
            if ($record['id_compte'])
                return '<div ' . $j . ' ' . $info . '>' . $compte->nom . '</div>';
            else
                return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
        }
        else
            return $compte->nom;
    }

    public function showEstimatedCharge($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['nb_jour_estime'])
            return '<div ' . $j . ' ' . $info . '>' . $record['nb_jour_estime'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">0</div>';
    }

    public function showRespQual($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['resp_qualif'])
            return '<div ' . $j . ' ' . $info . '>' . $record['resp_qualif'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showRespTec1($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['resp_tec1'])
            return '<div ' . $j . ' ' . $info . '>' . $record['resp_tec1'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showRespTec2($params, $userParam) {
        extract($params);
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if ($record['resp_tec2'])
            return '<div ' . $j . ' ' . $info . '>' . $record['resp_tec2'] . '</div>';
        else
            return '<div ' . $j . ' ' . $info . ' style="width:100%">&nbsp;</div>';
    }

    public function showRevenue($params, $userParam) {
        extract($params);
        $proposition = new Proposition(Affaire::lastProposition($record['id_affaire']), array());
        if ($record['resume']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['resume'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        }
        if ($userParam['color'] == true) {
            list($date, $heure) = explode(' ', DateMysqltoFr($record['date_histo'], 'fr', true));
            if (strtotime($date) < strtotime(strftime('%d-%m-%Y', (time() - 30 * 24 * 3600)))) {
                $j = 'class="rowrouge"';
            }
        }
        if(!$userParam['csv']) {
            if ($record['id_affaire'])
                return '<div ' . $j . ' ' . $info . '>' . number_format($proposition->chiffre_affaire, 0, ',', ' ') . '</div>';
            else
                return '<div ' . $j . ' ' . $info . ' style="width:100%">0</div>';
        }
        else
            return number_format($proposition->chiffre_affaire, 0, ',', ' ');
    }

    public function showButtons($params, $userParam) {
        extract($params);
        if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_affaire'])) {
            $htmlAdmin .= '<td><a href="index.php?a=modifier_affaire&amp;Id_affaire=' . $record['id_affaire'] . '"><img src="' . IMG_EDIT . '"></a></td>';
        }
        return $htmlAdmin;
    }

}

?>
