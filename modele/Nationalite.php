<?php

/**
 * Fichier Nationalite.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe Nationalite
 */
class Nationalite {

    /**
     * Identifiant de la nationalite
     *
     * @access private
     * @var int 
     */
    private $Id_nationalite;
    /**
     * Libell� de la nationalite
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Nationalit�
     *
     * @param Identifiant de la nationalit�
     * 			
     */
    public function __construct($Id) {
        $this->Id_nationalite = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM nationalite WHERE Id_nationalite =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les nationalit�s
     *
     * @return string
     */
    public function getList() {
        $nationalite[$this->Id_nationalite] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM nationalite ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_nationalite . ' ' . $nationalite[$ligne->id_nationalite] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la Nationalit�
     *
     * @param int Identifiant de la Nationalit�
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM nationalite WHERE Id_nationalite=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>