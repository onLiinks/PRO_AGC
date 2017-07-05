<?php

/**
 * Fichier EtatAction.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe EtatAction
 */
class EtatAction {

    /**
     * Identifiant de l'etat de l'action
     *
     * @access private
     * @var int 
     */
    private $Id_etat_action;
    /**
     * Libellé de de l'etat de l'action
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe EtatAction
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'état de l'acion
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_etat_action = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_etat_action = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM etat_action WHERE Id_etat_action=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_etat_action = $code;
                $this->libelle = $ligne->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_etat_action = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les états d'action
     *
     * @return string	
     */
    public function getList() {
        $etatAction[$this->Id_etat_action] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM etat_action ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_etat_action . ' ' . $etatAction[$ligne->id_etat_action] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'état de l'action
     *
     * @param int Identifiant de l'état de l'action
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM etat_action WHERE Id_etat_action=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
