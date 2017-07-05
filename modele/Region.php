<?php

/**
 * Fichier Region.php
 *
 * @author Youssouf Coulibaly
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Pays
 */
class Region {

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
     * @var int
     */
    public $Id_pays;

    /**
     * Constructeur de la classe Region
     *
     * @param Identifiant de la région
     * 			
     */
    public function __construct($Id) {
        $this->Id = $Id;
        $db = connecter();
        $ligne = $db->query('SELECT * FROM insee_regions WHERE Id =' . mysql_real_escape_string((int) $Id))->fetchRow();
        $this->nom = $ligne->nom;
        $this->code = $ligne->code;
        $this->Id_pays = $ligne->Id_pays;
    }

    /**
     * Affichage d'une select box contenant les régions
     *
     * @return string
     */
    public function getList() {
        $region[$this->Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM insee_regions ORDER BY nom');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->Id . ' ' . $region[$ligne->id] . '>' . $ligne->nom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la région
     *
     * @param int Identifiant de la région
     *
     * @return string
     */
    public static function getLibelle($Id) {
        $db = connecter();
        return $db->query('SELECT nom FROM insee_regions WHERE Id=' . mysql_real_escape_string((int) $id))->fetchRow()->nom;
    }

}

?>