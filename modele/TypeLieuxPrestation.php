<?php

/**
 * Fichier TypeLieuxPrestation.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Pays
 */
class TypeLieuxPrestation {

    /**
     * Identifiant du TypeLieuxPrestation
     *
     * @access private
     * @var int
     */
    private $Id;

    /**
     * @access public
     * @var int 
     */
    public $code;

    /**
     * @access public
     * @var string 
     */
    public $libelle;

  
    /**
     * Constructeur de la classe TypeLieuxPrestation
     *
     * @param Identifiant du type de lieux
     * 			
     */
    public function __construct($Id) {
        $this->Id = $Id;
        $db = connecter();
        $ligne = $db->query('SELECT * FROM type_lieux_prestation WHERE Id =' . mysql_real_escape_string((int) $Id))->fetchRow();
        $this->code = $ligne->code;
        $this->libelle = $ligne->libelle;
    }

    /**
     * Affichage d'une select box contenant les type de lieux
     *
     * @return string
     */
    public function getList() {
        $type[$this->Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM type_lieux_prestation ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->Id . ' ' . $type[$ligne->Id] . '>' . $ligne->code . ' - ' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle
     *
     * @param int Identifiant
     *
     * @return string
     */
    public static function getLibelle($Id) {
        $db = connecter();
        return $db->query('SELECT libelle FROM type_lieux_prestation WHERE Id=' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage du code
     *
     * @param int Identifiant
     *
     * @return int
     */
    public static function getCode($Id) {
        $db = connecter();
        return $db->query('SELECT code FROM type_lieux_prestation WHERE Id=' . mysql_real_escape_string((int) $Id))->fetchRow()->code;
    }

}

?>