<?php

/**
 * Fichier Specialite.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Specialite
 */
class Specialite {

    /**
     * Identifiant de la spécialité
     *
     * @access private
     * @var int 
     */
    private $Id_specialite;
    /**
     * Libellé de la spécialité
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Specialite
     *
     * @param Identifiant de la spécialité
     * 			
     */
    public function __construct($Id) {
        $this->Id_specialite = $Id;
        $db = connecter();
        $this->libelle = $db->query('SELECT * FROM specialite WHERE Id_specialite =' . mysql_real_escape_string((int) $Id))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les spécialités
     *
     * @return string
     */
    public function getList($Id_ressource = 0) {
        $specialite[$this->Id_specialite] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM specialite ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            if ($Id_ressource) {
                $ligne2 = $db->query('SELECT count(Id_ressource) as nb FROM ressource_specialite WHERE Id_specialite=' . mysql_real_escape_string($ligne->id_specialite) . ' AND Id_ressource=' . mysql_real_escape_string((int) $Id_ressource))->fetchRow();
                if ($ligne2->nb > 0) {
                    $html .= '<option value=' . $ligne->id_specialite . ' selected="selected">' . $ligne->libelle . '</option>';
                } else {
                    $html .= '<option value=' . $ligne->id_specialite . '>' . $ligne->libelle . '</option>';
                }
            } else {
                $html .= '<option value=' . $ligne->id_specialite . ' ' . $specialite[$ligne->id_specialite] . '>' . $ligne->libelle . '</option>';
            }
        }
        return $html;
    }

    /**
     * Affichage de la liste des spécialités dans un champs select dans filtre de recherche des candidats
     *
     * @param array liste des spécialités
     *
     * @return string
     */
    public static function getSelectList($Id_specialite) {
        //tableau pour permettre de noter en 'selected' les spécialités sélectionnées
        if (!empty($Id_specialite)) {
            foreach ($Id_specialite as $i) {
                $specialite[$i] = 'selected="selected"';
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM specialite ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_specialite . '" ' . $specialite[$ligne->id_specialite] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

}

?>