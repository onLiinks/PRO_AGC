<?php

/**
 * Fichier Periode.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Periode
 */
class Periode {

    /**
     * Identifiant de la période
     *
     * @access private
     * @var int 
     */
    private $Id_periode;
    /**
     * Libellé de la période
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Période
     *
     * @param Identifiant de la période
     *
     */
    public function __construct($Id) {
        $this->Id_periode = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM periode WHERE Id_periode =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les périodes
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
     * Affichage du libelle de la période
     *
     * @param int Identifiant de la période
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM periode WHERE Id_periode=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>