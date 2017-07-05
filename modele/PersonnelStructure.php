<?php
/**
 * Fichier PersonnelStructure.php
 *
 * @author Mathieu PERRIN
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Ressource
 */
class PersonnelStructure {

    /**
     * Identifiant de la ressource
     *
     * @access public
     * @var int
     */
    public $Id_personnel_structure;
    /**
     * Identifiant de la ressource
     *
     * @access public
     * @var int
     */
    public $code_ressource;
    /**
     * Nom de la ressource
     *
     * @access public
     * @var array
     */
    public $nom;
    /**
     * Prénom de la ressource
     *
     * @access public
     * @var string
     */
    public $prenom;
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
     * Fin du cdd de la ressource
     *
     * @access public
     * @var date
     */
    public $fin_cdd;
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
     * Identifiant du service de la ressource
     *
     * @access public
     * @var string
     */
    public $Id_service;
    /**
     * Agence de la ressource
     *
     * @access public
     * @var string
     */
    public $Id_agence;
    /**
     * Type de poste (staff ou collab)
     *
     * @access public
     * @var int
     */
    public $etat;

    /**
     * Constructeur de la classe Ressource
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la ressource
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_personnel_structure = '';
                $this->code_ressource = '';
                $this->nom = '';
                $this->prenom = '';
                $this->mail = '';
                $this->statut = '';
                $this->fin_cdd = '';
                $this->Id_profil = '';
                $this->Id_profil_cegid = '';
                $this->profil = '';
                $this->profil_cegid = '';
                $this->Id_service = '';
                $this->Id_agence = '';
                $this->etat = 'Staff';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_personnel_structure = '';
                $this->code_ressource = htmlscperso(stripslashes($tab['code_ressource']), ENT_QUOTES);
                $this->nom = htmlscperso(stripslashes($tab['nom_ressource']), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes($tab['prenom_ressource']), ENT_QUOTES);
                $this->mail = htmlscperso(stripslashes($tab['mail_ressource']), ENT_QUOTES);
                $this->statut = $tab['statut_ressource'];
                $this->fin_cdd = $tab['fin_cdd'];
                $this->Id_profil = (int) $tab['Id_profil'];
                $this->profil = Profil::GetLibelle($this->Id_profil);
                $this->Id_profil_cegid = (int) $tab['Id_profil_cegid'];
                $this->Id_service = $tab['Id_service'];
                $this->Id_agence = $tab['Id_agence'];
                $this->etat = 'Staff';
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter_cegid();
                $requete = 'SELECT PSA_SALARIE, PSA_LIBELLE, PSA_PRENOM, PSE_EMAILPROF, PSA_INDICE, PSA_DATELIBRE1, PSE_CODESERVICE,
                            PSA_TRAVAILN1, CC2.CC_LIBELLE, CC3.CC_LIBELLE AS cc3_libelle, PSA_ETABLISSEMENT
                            FROM SALARIES
                            LEFT JOIN DEPORTSAL ON pse_salarie=salaries.psa_salarie
                            LEFT OUTER JOIN CHOIXCOD CC2 ON PSA_LIBELLEEMPLOI=CC2.CC_CODE AND CC2.CC_TYPE="PLE"
                            LEFT OUTER JOIN CHOIXCOD CC3 ON PSA_LIBREPCMB4=CC3.CC_CODE AND CC3.CC_TYPE="PL4"
                            WHERE PSA_SALARIE="' . $code . '"';
                $result = $db->query($requete);
                $ligne = $result->fetchRow();
                $this->Id_personnel_structure = $code;
                $this->code_ressource = $ligne->psa_salarie;
                $this->nom = $ligne->psa_libelle;
                $this->prenom = $ligne->psa_prenom;
                $this->mail = $ligne->pse_emailprof;
                $this->Id_agence = $ligne->psa_etablissement;
                if ($ligne->psa_indice == '0001') {
                    $this->statut = 'CADRE';
                } elseif ($ligne->psa_indice == '0003') {
                    $this->statut = 'ETAM';
                }
                $this->fin_cdd = str_replace(' 00:00:00', '', $ligne->psa_datelibre1);
                $this->Id_service = $ligne->pse_codeservice;
                if ($ligne->psa_travailn1 == '001') {
                    $this->etat = 'Personnel de structure';
                } elseif ($ligne->psa_travailn1 == '002') {
                    $this->etat = 'Collaborateur';
                }
                $this->profil = str_replace("'", " ", $ligne->cc_libelle);
                $this->profil_cegid = str_replace("'", " ", $ligne->cc3_libelle);
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_personnel_structure = $code;
                $this->code_ressource = htmlscperso(stripslashes($tab['code_ressource']), ENT_QUOTES);
                $this->nom = htmlscperso(stripslashes($tab['nom_ressource']), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes($tab['prenom_ressource']), ENT_QUOTES);
                $this->mail = htmlscperso(stripslashes($tab['mail_ressource']), ENT_QUOTES);
                $this->statut = $tab['statut_ressource'];
                $this->fin_cdd = $tab['fin_cdd'];
                $this->Id_profil = (int) $tab['Id_profil'];
                $this->Id_profil_cegid = $tab['Id_profil_cegid'];
                $this->profil = Profil::GetLibelle($this->Id_profil);
                $this->Id_service = $tab['Id_service'];
                $this->Id_agence = $tab['Id_agence'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }
    
    /**
     * Affichage d'une select box contenant les salaries staff
     *
     * @return string
     */
    public function getList() {
        $ressource[$this->Id_personnel_structure] = 'selected="selected"';
        $html .= '<option value="">+-----------------------------------------------+</option>
		<option value="">Personnel de Structure</option>
		<option value="">+-----------------------------------------------+</option>';
        $db = connecter_cegid();
        $requete = 'SELECT PSA_SALARIE, PSA_LIBELLE, PSA_PRENOM, PSA_CODEPOSTAL, PSA_CODESTAT FROM SALARIES WHERE (PSA_DATESORTIE >= CONVERT(varchar(8), GETDATE(), 112) OR PSA_DATESORTIE = "") AND PSA_TRAVAILN1 = "001"';
        if ($_SESSION['societe'] == 'PROSERVIA') {
            $requete .= ' UNION SELECT PSA_SALARIE, "ALC-"+PSA_LIBELLE, PSA_PRENOM, PSA_CODEPOSTAL, PSA_CODESTAT FROM ALCYON.DBO.SALARIES WHERE (PSA_DATESORTIE >= CONVERT(varchar(8), GETDATE(), 112) OR PSA_DATESORTIE = "") AND PSA_TRAVAILN1 = "001"
		     UNION SELECT PSA_SALARIE, "ALC-"+PSA_LIBELLE, PSA_PRENOM, PSA_CODEPOSTAL, PSA_CODESTAT FROM ALTIQUENET.DBO.SALARIES WHERE (PSA_DATESORTIE >= CONVERT(varchar(8), GETDATE(), 112) OR PSA_DATESORTIE = "") AND PSA_TRAVAILN1 = "001"
		     UNION SELECT PSA_SALARIE, "ALC-"+PSA_LIBELLE, PSA_PRENOM, PSA_CODEPOSTAL, PSA_CODESTAT FROM ELITEX.DBO.SALARIES WHERE (PSA_DATESORTIE >= CONVERT(varchar(8), GETDATE(), 112) OR PSA_DATESORTIE = "") AND PSA_TRAVAILN1 = "001"
			 UNION SELECT PSA_SALARIE, PSA_LIBELLE, PSA_PRENOM, PSA_CODEPOSTAL, PSA_CODESTAT FROM LYNT.DBO.SALARIES WHERE (PSA_DATESORTIE >= CONVERT(varchar(8), GETDATE(), 112) OR PSA_DATESORTIE = "") AND PSA_TRAVAILN1 = "001"';
        }
        $requete .= ' ORDER BY PSA_LIBELLE';
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->psa_salarie . '" ' . $ressource[$ligne->psa_salarie] . '>' . substr($ligne->psa_libelle, 0, 10) . ' ' . substr($ligne->psa_prenom, 0, 10) . ' | ' . $ligne->psa_codepostal . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage du Nom et du prénom de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public function getName() {
        $db = connecter_cegid();
        $ligne = $db->query('SELECT PSA_LIBELLE,PSA_PRENOM FROM SALARIES WHERE PSA_SALARIE="' . $this->Id_personnel_structure . '"
                     UNION SELECT PSA_LIBELLE,PSA_PRENOM FROM ALCYON.DBO.SALARIES WHERE PSA_SALARIE="' . $this->Id_personnel_structure . '"
                     UNION SELECT PSA_LIBELLE,PSA_PRENOM FROM ALTIQUENET.DBO.SALARIES WHERE PSA_SALARIE="' . $this->Id_personnel_structure . '"
                     UNION SELECT PSA_LIBELLE,PSA_PRENOM FROM ELITEX.DBO.SALARIES WHERE PSA_SALARIE="' . $this->Id_personnel_structure . '"
                     UNION SELECT PSA_LIBELLE,PSA_PRENOM FROM LYNT.DBO.SALARIES WHERE PSA_SALARIE="' . $this->Id_personnel_structure . '"')->fetchRow();
        return $ligne->psa_libelle . ' ' . $ligne->psa_prenom;
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
}

?>
