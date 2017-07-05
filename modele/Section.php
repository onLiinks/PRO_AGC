<?php

/**
 * Fichier Section.php
 *
 * @author Yannick BETOU
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Section
 */
class Section {

    /**
     * Identifiant de la Section
     *
     * @access private
     * @var string
     */
    private $Id_section;
    /**
     * Libellé de la section
     *
     * @access public
     * @var string
     */
    public $libelle;

    /**
     * Constructeur de la classe Section
     *
     * @param string Identifiant de la BU
     *
     */
    public function __construct($Id_section) {
        $this->Id_section = $Id_section;
        $db = connecter_cegid();
        $ligne = $db->query('SELECT S_SECTION, S_LIBELLE, S_ABREGE FROM SECTION WHERE S_SECTION = "' . $Id_section . '"')->fetchRow();
        $this->libelle = $ligne->s_libelle;
    }

    /**
     * Affichage d'une select box contenant les section
     *
     * @return string
     */
    public function getList() {
        $db = connecter_cegid();
        $section[$this->Id_section] = 'selected="selected"';
        $result = $db->query('SELECT S_SECTION, S_LIBELLE, S_ABREGE FROM SECTION WHERE ('
                . ' S_SECTION LIKE \'PDTHDFMC\' OR'
                . ' S_SECTION LIKE \'PFEHDFMC\' OR'
                . ' S_SECTION LIKE \'PPIHDFMC\' OR'
                . ' S_SECTION LIKE \'PSIHDFMC\' OR'
                . ' S_SECTION LIKE \'P05HDFMC\' OR'
                . ' S_SECTION LIKE \'P06HDFMC\')'
                . ' AND S_AXE = \'A1\''
                );
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->s_section . ' ' . $cds[$ligne->s_section] . '>' . $ligne->s_libelle . '</option>';
        } 
        return $html;
    }

    /**
     * Affichage du libelle de la section d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getSectionByResource($i) {
        $db = connecter_cegid();
        return $db->query('SELECT S_LIBELLE FROM SECTION LEFT JOIN VENTIL ON V_SECTION = S_SECTION WHERE ('
                . ' S_SECTION LIKE \'PDTHDFMC\' OR'
                . ' S_SECTION LIKE \'PFEHDFMC\' OR'
                . ' S_SECTION LIKE \'PPIHDFMC\' OR'
                . ' S_SECTION LIKE \'PSIHDFMC\' OR'
                . ' S_SECTION LIKE \'P05HDFMC\' OR'
                . ' S_SECTION LIKE \'P06HDFMC\')'
                . ' AND S_AXE = \'A1\''
                . ' AND V_COMPTE = "' . $i . '"')->fetchRow()->s_libelle;
    }
    
    /**
     * Affichage de l'identifiant de la section d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getIdSectionByResource($i) {
        $db = connecter_cegid();
        return $db->query('SELECT S_SECTION FROM SECTION LEFT JOIN VENTIL ON V_SECTION = S_SECTION WHERE ('
                . ' S_SECTION LIKE \'PDTHDFMC\' OR'
                . ' S_SECTION LIKE \'PFEHDFMC\' OR'
                . ' S_SECTION LIKE \'PPIHDFMC\' OR'
                . ' S_SECTION LIKE \'PSIHDFMC\' OR'
                . ' S_SECTION LIKE \'P05HDFMC\' OR'
                . ' S_SECTION LIKE \'P06HDFMC\')'
                . ' AND S_AXE = \'A1\''
                . ' AND V_COMPTE = "' . $i . '"')->fetchRow()->s_section;
    }
    
    /**
     * Affichage du libelle de la section
     *
     * @param int Identifiant du service
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter_cegid();
        return $db->query('SELECT * FROM SECTION WHERE S_SECTION = "' . $i . '"')->fetchRow()->s_libelle;
    }
    
    /**
     * Affichage de l'identifiant de la section
     *
     * @param string Libelle de la section
     *
     * @return string
     */
    public static function getIdSection($i) {
        $db = connecter_cegid();
        return $db->query('SELECT S_SECTION FROM SECTION WHERE ('
                . ' S_SECTION LIKE \'PDTHDFMC\' OR'
                . ' S_SECTION LIKE \'PFEHDFMC\' OR'
                . ' S_SECTION LIKE \'PPIHDFMC\' OR'
                . ' S_SECTION LIKE \'PSIHDFMC\' OR'
                . ' S_SECTION LIKE \'P05HDFMC\' OR'
                . ' S_SECTION LIKE \'P06HDFMC\')'
                . ' AND S_AXE = \'A1\''
                . ' AND S_LIBELLE = "' . $i . '"')->fetchRow()->s_section;
    }
}

?>