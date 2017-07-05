<?php

/**
 * Fichier GroupeAD.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Nationalite
 */
class GroupeAD {

    /**
     * Identifiant du groupe AD
     *
     * @access private
     * @var int 
     */
    private $Id_groupe_ad;
    /**
     * Libellé du groupe AD
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
        $this->Id_groupe_ad = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM groupe_ad WHERE Id_groupe_ad =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les groupes AD
     *
     * @return string
     */
    public function getList() {
        $groupeAd[$this->Id_groupe_ad] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM groupe_ad ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_groupe_ad . ' ' . $groupeAd[$ligne->id_groupe_ad] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du groupe AD
     *
     * @param int Identifiant du groupe AD
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM groupe_ad WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>