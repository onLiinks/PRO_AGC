<?php

/**
 * Fichier Preavis.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Preavis
 */
class Preavis {

    /**
     * Identifiant du préavis
     *
     * @access private
     * @var int 
     */
    private $Id_preavis;
    /**
     * Libellé du préavis
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Preavis
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du préavis
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_preavis = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_preavis = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_preavis = $code;
                $this->libelle = $db->query('SELECT * FROM preavis WHERE Id_preavis=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_preavis = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les préavis
     *
     * @return string
     */
    public function getList() {
        $preavis[$this->Id_preavis] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_preavis, libelle FROM preavis ORDER BY Id_preavis');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_preavis . ' ' . $preavis[$ligne->id_preavis] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du préavis
     *
     * @param int Identifiant du préavis
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM preavis WHERE Id_preavis=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
