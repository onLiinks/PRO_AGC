<?php

/**
 * Fichier TypeContrat.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe TypeContrat
 */
class TypeContrat {

    /**
     * Identifiant du type de contrat
     *
     * @access private
     * @var int 
     */
    private $Id_type_contrat;
    /**
     * Libellé du type de contrat
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Tableaux des identifiants des poles pour lequel on peut effectuer ce type de contrat
     *
     * @access public
     * @var array 
     */
    public $pole;

    /**
     * Constructeur de la classe TypeContrat
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du type de contrat
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_type_contrat = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_type_contrat = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_type_contrat = $code;
                $this->libelle = $db->query('SELECT * FROM com_type_contrat WHERE Id_type_contrat=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_type_contrat = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les types de contrat
     *
     * @return string	
     */
    public function getList() {
        $tc[$this->Id_type_contrat] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM com_type_contrat ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_type_contrat . ' ' . $tc[$ligne->id_type_contrat] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage d'un menu contenant les types de contrats
     *
     * @param int Identifiant du pôle
     *
     * @return string
     */
    public static function getMenuList($Id_pole = null) {
        $db = connecter();
        if ($Id_pole !== null) {
            $where = 'WHERE pole LIKE "%' . mysql_real_escape_string($Id_pole) . '%"';
            $pole = '&amp;Id_pole=' . mysql_real_escape_string($Id_pole);
        }
        $result = $db->query('SELECT Id_type_contrat, libelle FROM com_type_contrat ' . $where . ' ORDER BY Id_type_contrat');
        while ($ligne = $result->fetchRow()) {
            if ($ligne->id_type_contrat == 4)
                $html .= '<a href="../com/index.php?a=formulaire_affaire&amp;Id_type_contrat=3&amp;Id_pole=3"><p class="typec">' . $ligne->libelle . '</p></a>';
            else
                $html .= '<a href="../com/index.php?a=formulaire_affaire&amp;Id_type_contrat=' . $ligne->id_type_contrat . $pole . '"><p class="typec">' . $ligne->libelle . '</p></a>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du type de contrat
     *
     * @param int Identifiant du type de contrat
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM com_type_contrat WHERE Id_type_contrat=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage du trigramme du type de contrat
     *
     * @param int Identifiant du type de contrat
     *
     * @return string	   
     */
    public static function getTrigram($i) {
        $db = connecter();
        return $db->query('SELECT trig FROM com_type_contrat WHERE Id_type_contrat=' . mysql_real_escape_string((int) $i))->fetchRow()->trig;
    }

    /**
     * Affichage de l'identifiant du type de contrat
     *
     * @param string Libelle du type de contrat
     *
     * @return int	   
     */
    public static function getIdTypeContrat($i) {
        $db = connecter();
        return $db->query('SELECT Id_type_contrat FROM com_type_contrat WHERE libelle LIKE "%' . mysql_real_escape_string($i) . '%"')->fetchRow()->id_type_contrat;
    }
}

?>
