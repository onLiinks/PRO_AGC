<?php

/**
 * Fichier Departement.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Departement
 */
class Departement {

    /**
     * Identifiant du département
     *
     * @access private
     * @var string 
     */
    private $Id;
    /**
     * Nom du département
     *
     * @access public
     * @var string 
     */
    public $nom;
    /**
     * Identifiant de la région
     *
     * @access private
     * @var string 
     */
    private $Id_region;

    /**
     * Constructeur de la classe Departement
     *
     * @param Identifiant du département
     * 			
     */
    public function __construct($Id) {
        $this->Id = $Id;
        $db = connecter();
        $ligne = $db->query('SELECT * FROM insee_departements WHERE Id ="' . mysql_real_escape_string($Id) . '"')->fetchRow();
        $this->nom = $ligne->nom;
        $this->Id_region = $ligne->id_regions;
    }

    /**
     * Affichage d'une select box contenant les departements
     *
     * @return string
     */
    public function getList() {
        $departement[$this->Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM insee_departements ORDER BY code');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->Id . ' ' . $departement[$ligne->Id] . '>' . $ligne->code . ' - ' . $ligne->nom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du departement
     *
     * @param int Identifiant du departement
     *
     * @return string
     */
    public static function getLibelle($Id) {
        $db = connecter();
        return $db->query('SELECT nom FROM insee_departements WHERE Id=' . mysql_real_escape_string((int) $id))->fetchRow()->nom;
    }
    
    /**
     * Affichage du code du departement
     *
     * @param int Identifiant du departement
     *
     * @return string
     */
    public static function getCode($Id) {
        $db = connecter();
        return $db->query('SELECT code FROM insee_departements WHERE Id=' . mysql_real_escape_string((int) $id))->fetchRow()->code;
    }

}

?>