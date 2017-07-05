<?php

/**
 * Fichier Periode.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe Periode
 */
class Periode {

    /**
     * Identifiant de la p�riode
     *
     * @access private
     * @var int 
     */
    private $Id_periode;
    /**
     * Libell� de la p�riode
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe P�riode
     *
     * @param Identifiant de la p�riode
     *
     */
    public function __construct($Id) {
        $this->Id_periode = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM periode WHERE Id_periode =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les p�riodes
     *
     * @return string
     */
    public function getList() {
        $periode[$this->Id_periode] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM periode ORDER BY Id_periode');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_periode . ' ' . $periode[$ligne->id_periode] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la p�riode
     *
     * @param int Identifiant de la p�riode
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM periode WHERE Id_periode=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>