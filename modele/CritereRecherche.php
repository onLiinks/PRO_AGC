<?php

/**
 * Fichier CritereRecherche.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe CritereRecherche
 */
class CritereRecherche {

    /**
     * Identifiant du critère de recherche
     *
     * @access private
     * @var int 
     */
    private $Id_critere_recherche;
    /**
     * Libelle du critère de recherche
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe CritereRecherche
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du critere de recherche
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_critere_recherche = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_critere_recherche = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM critere_recherche WHERE Id_critere_recherche=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_critere_recherche = $code;
                $this->libelle = $ligne->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_critere_recherche = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage des checkbox contenant les criteres de recherche
     *
     * @param int Valeur de l'identifiant de l'entretien
     * 			
     * @return string
     */
    public static function getCheckboxList($Id_entretien = 0) {
        if ($Id_entretien) {
            $entretien = new Entretien($Id_entretien, array());
            if (!empty($entretien->critere_recherche)) {
                foreach ($entretien->critere_recherche as $i) {
                    $critere_recherche[$i] = 'checked="checked"';
                }
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM critere_recherche');
        while ($ligne = $result->fetchRow()) {
            $html .= '<input type="checkbox" name="critere_recherche[]" value=' . $ligne->id_critere_recherche . ' ' . $critere_recherche[$ligne->id_critere_recherche] . ' />' . $ligne->libelle . '';
        }
        return $html;
    }

    /**
     * Affichage du libelle du critère de recherche
     *
     * @param int Identifiant du critère de recherche
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM critere_recherche WHERE Id_critere_recherche=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
