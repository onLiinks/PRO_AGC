<?php

/**
 * Fichier Service.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Service
 */
class Service {

    /**
     * Identifiant du service
     *
     * @access private
     * @var string 
     */
    private $Id_service;
    /**
     * Libellé du service
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Responsable du service
     *
     * @access public
     * @var string 
     */
    public $responsable;
    /**
     * Responsable absence du service
     *
     * @access public
     * @var string 
     */
    public $resp_abs;
    /**
     * Responsable augmentation du service
     *
     * @access public
     * @var string 
     */
    public $resp_var;
    /**
     * Societe
     *
     * @access public
     * @var string 
     */
    public $societe;

    /**
     * Constructeur de la classe Service
     *
     * @param string Identifiant du service
     *
     */
    public function __construct() {
        $argv = func_get_args();
        switch(func_num_args()) {
            case 1:
                self::__construct1($argv[0]);
            break;
            case 2:
                self::__construct2( $argv[0], $argv[1] );
            break;
         }
    }
    
    /**
     * Constructeur de la classe Service
     *
     * @param string Identifiant du service
     *
     */
    public function __construct1($Id_service) {
        $this->Id_service = $Id_service;
        $db = connecter_cegid();
        $requete = 'SELECT PGS_CODESERVICE,PGS_NOMSERVICE,RESPONSGAL,RESPONSABS,RESPONSVAR 
                      FROM PROSERVIA.DBO.PGSERVICES 
                      WHERE PGS_CODESERVICE="' . $this->Id_service . '"';
        $ligne = $db->query($requete)->fetchRow();
        $this->libelle = $ligne->pgs_nomservice;
        $this->responsable = $ligne->responsabs;//$ligne->responsgal;
        $this->resp_abs = $ligne->responsabs;
        $this->resp_var = $ligne->responsvar;
        $this->societe = 'PROSERVIA';
    }
    
    /**
     * Constructeur de la classe Service
     *
     * @param string Identifiant du service
     *
     */
    public function __construct2($Id_service, $societe) {
        $this->Id_service = $Id_service;
        $db = connecter_cegid();
        $requete .= 'SELECT PGS_CODESERVICE,PGS_NOMSERVICE,RESPONSGAL,RESPONSABS,RESPONSVAR 
                      FROM ' . $societe . '.DBO.PGSERVICES 
                      WHERE PGS_CODESERVICE="' . $this->Id_service . '"';
        $ligne = $db->query($requete)->fetchRow();
        $this->libelle = $ligne->pgs_nomservice;
        $this->responsable = $ligne->responsabs;//$ligne->responsgal;
        $this->resp_abs = $ligne->responsabs;
        $this->resp_var = $ligne->responsvar;
        $this->societe = $societe;
    }

    /**
     * Affichage d'une select box contenant les services
     *
     * @return string
     */
    public function getList() {
        $service[$this->Id_service] = 'selected="selected"';
        $db = connecter_cegid();
        $result = $db->query('SELECT PGS_CODESERVICE,PGS_NOMSERVICE FROM ' . $this->societe . '.DBO.PGSERVICES ORDER BY PGS_NOMSERVICE');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->pgs_codeservice . ' ' . $service[$ligne->pgs_codeservice] . '>' . $ligne->pgs_nomservice . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du service
     *
     * @param int Identifiant du service
     *
     * @return string	   
     */
    public static function getLibelle($id, $societe = 'PROSERVIA') {
        $db = connecter_cegid();
        return $db->query('SELECT PGS_NOMSERVICE FROM ' . $societe . '.DBO.PGSERVICES WHERE PGS_CODESERVICE="' . $id . '"')->fetchRow()->pgs_nomservice;
    }
    
    /**
     * Affichage de l'identifiant du service
     *
     * @param string Libelle du service
     *
     * @return string	   
     */
    public static function getId($id, $societe = 'PROSERVIA') {
        $db = connecter_cegid();
        return $db->query('SELECT PGS_CODESERVICE FROM ' . $societe . '.DBO.PGSERVICES WHERE PGS_NOMSERVICE="' . $id . '"')->fetchRow()->pgs_codeservice;
    }

    /**
     * Affichage du responsable du service
     *
     * @return string	   
     */
    public function getManager() {
        $db = connecter_cegid();
        $ligne = $db->query('SELECT PSA_LIBELLE,PSA_PRENOM 
                          FROM ' . $this->societe . '.DBO.SALARIES 
                          WHERE PSA_SALARIE="' . $this->responsable . '"')->fetchRow();
        if($ligne->psa_libelle == '') {
            $ligne = $db->query('SELECT PSE_EMAILPROF 
                          FROM ' . $this->societe . '.DBO.DEPORTSAL 
                          WHERE PSE_SALARIE="' . $this->responsable . '"')->fetchRow();
            return formatPrenomNom($ligne->pse_emailprof, true, true);
        }
        else {
            return $ligne->psa_prenom . ' ' . $ligne->psa_libelle;
        }
    }
    
    /**
     * Affichage du responsable du service
     *
     * @return string	   
     */
    public function getManagerMail() {
        $db = connecter_cegid();
        $ligne = $db->query('SELECT PSE_EMAILPROF 
                          FROM ' . $this->societe . '.DBO.DEPORTSAL 
                          WHERE PSE_SALARIE="' . $this->responsable . '"')->fetchRow();
        return $ligne->pse_emailprof;
    }

}

?>