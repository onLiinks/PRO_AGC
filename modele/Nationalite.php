<?php

/**
 * Fichier Nationalite.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Nationalite
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
     * Libellé de la nationalite
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Nationalité
     *
     * @param Identifiant de la nationalité
     * 			
     */
    public function __construct($Id) {
        $this->Id_nationalite = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM nationalite WHERE Id_nationalite =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les nationalités
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
     * Affichage du libelle de la Nationalité
     *
     * @param int Identifiant de la Nationalité
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM nationalite WHERE Id_nationalite=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>