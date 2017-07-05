<?php

/**
 * Fichier Cds.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Cds
 */
class Cds {

    /**
     * Identifiant du cds
     *
     * @access private
     * @var string
     */
    private $Id_cds;
    /**
     * Libellé du cds
     *
     * @access public
     * @var string
     */
    public $libelle;

    /**
     * Constructeur de la classe Cds
     *
     * @param string Identifiant du service
     *
     */
    public function __construct($Id_cds) {
        $this->Id_cds = $Id_cds;
        $db = connecter_cegid();
        $ligne = $db->query('SELECT CC_CODE, CC_LIBELLE FROM CHOIXCOD WHERE CC_TYPE = "PL2" AND CC_CODE = "' . $Id_cds . '"')->fetchRow();
        $this->libelle = $ligne->cc_libelle;
    }

    /**
     * Affichage d'une select box contenant les cds
     *
     * @return string
     */
    public function getList() {
        $db = connecter_cegid();
        $cds[$this->Id_cds] = 'selected="selected"';
        $result = $db->query('SELECT CC_CODE, CC_LIBELLE FROM CHOIXCOD WHERE CC_TYPE = "PL2"');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->cc_code . ' ' . $cds[$ligne->cc_code] . '>' . $ligne->cc_libelle . '</option>';
        } 
        return $html;
    }

    /**
     * Affichage du libelle du CDS d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getCDSByResource($i) {
        $db = connecter_cegid();
        return $db->query('SELECT CC_LIBELLE FROM SALARIES LEFT JOIN CHOIXCOD ON PSA_LIBREPCMB2 = CC_CODE WHERE CC_TYPE = "PL2" AND PSA_SALARIE = "' . $i . '"')->fetchRow()->cc_libelle;
    }
    
    /**
     * Affichage de l'identifiant du CDS d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getIdCDSByResource($i) {
        $db = connecter_cegid();
        return $db->query('SELECT CC_CODE FROM SALARIES LEFT JOIN CHOIXCOD ON PSA_LIBREPCMB2 = CC_CODE WHERE CC_TYPE = "PL2" AND PSA_SALARIE = "' . $i . '"')->fetchRow()->cc_code;
    }
    
    /**
     * Affichage du libelle du CDS
     *
     * @param int Identifiant du service
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter_cegid();
        return $db->query('SELECT * FROM CHOIXCOD WHERE CC_TYPE = "PL2" AND CC_CODE = "' . $i . '"')->fetchRow()->cc_libelle;
    }
    
    /**
     * Affichage de l'identifiant du CDS
     *
     * @param string Libelle du CDS
     *
     * @return string
     */
    public static function getIdCDS($i) {
        $db = connecter_cegid();
        return $db->query('SELECT CC_CODE FROM CHOIXCOD WHERE CC_TYPE = "PL2" AND CC_LIBELLE = "' . $i . '"')->fetchRow()->cc_code;
    }
}

?>