<?php

/**
 * Fichier Ville.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Ville
 */
class Ville {

    /**
     * Identifiant de la ville
     *
     * @access private
     * @var int
     */
    private $Id_ville;
    /**
     * Nom de la ville
     *
     * @access public
     * @var string
     */
    public $nom;
    /**
     * Code postal de la ville
     *
     * @access public
     * @var string
     */
    public $code_postal;
    /**
     * Région de la ville
     *
     * @access public
     * @var string
     */
    public $region;
    /**
     * Département de la ville
     *
     * @access public
     * @var string
     */
    public $departement;

    /**
     * Constructeur de la classe Ville
     *
     * @param Identifiant de la ville
     *
     */
    public function __construct($Id) {
        $this->Id_ville = $Id;
        $db = connecter();
        $ligne = $db->query('SELECT id_geography_city, region_1, region_2, zip, city FROM geography_city WHERE id_geography_city =' . mysql_real_escape_string((int) $Id))->fetchRow();
        $this->nom = $ligne->city;
        $this->code_postal = $ligne->zip;
        $this->region = $ligne->region_1;
        $this->departement = $ligne->region_2;
    }

    /**
     * Affichage d'une select box contenant les pays
     *
     * @return string
     */
    public static function getList($search = '') {
        $db = connecter();
        $requete = 'SELECT DISTINCT id_geography_city, city, zip FROM geography_city';
        if ($search) {
            $requete .= ' WHERE city like "' . mysql_real_escape_string($search) . '%" OR zip LIKE "' . mysql_real_escape_string($search) . '%"';
        }
        $requete .= ' ORDER BY city';
        $result = $db->query($requete);
        $html = '<ul>';
        while ($ligne = $result->fetchRow()) {
            $html .= '<li id="' . $ligne->id_geography_city . '">' . $ligne->city . ' (' . $ligne->zip . ')</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Affichage du libelle du pays
     *
     * @param int Identifiant du pays
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT city FROM geography_city WHERE id_geography_city=' . mysql_real_escape_string((int) $i))->fetchRow()->city;
    }

}

?>