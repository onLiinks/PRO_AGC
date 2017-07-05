<?php

/**
 * Fichier Pays.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Pays
 */
class Pays {

    /**
     * Identifiant du pays
     *
     * @access private
     * @var int
     */
    private $Id;

    /**
     * @access public
     * @var string 
     */
    public $nom;

    /**
     * @access public
     * @var string 
     */
    public $code;

    /**
     * @access public
     * @var string 
     */
    public $alpha2;

    /**
     * @access public
     * @var string 
     */
    public $alpha3;

    /**
     * @access public
     * @var string 
     */
    public $nom_en;
    
    /**
     * Constructeur de la classe Pays
     *
     * @param Identifiant du pays
     * 			
     */
    public function __construct($Id) {
        $this->Id = $Id;
        $db = connecter();
        $ligne = $db->query('SELECT * FROM insee_pays WHERE Id =' . mysql_real_escape_string((int) $Id))->fetchRow();
        $this->nom = $ligne->nom;
        $this->code = $ligne->code;
        $this->alpha2 = $ligne->alpha2;
        $this->alpha3 = $ligne->alpha3;
        $this->nom_en = $ligne->nom_en;
    }

    /**
     * Affichage d'une select box contenant les pays
     *
     * @return string
     */
    public function getList() {
        $pays[$this->Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM insee_pays ORDER BY nom');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->Id . ' ' . $pays[$ligne->Id] . '>' . $ligne->nom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du pays
     *
     * @param int Identifiant du pays
     *
     * @return string
     */
    public static function getLibelle($Id) {
        $db = connecter();
        return $db->query('SELECT nom FROM insee_pays WHERE Id=' . mysql_real_escape_string((int) $Id))->fetchRow()->nom;
    }

}

?>