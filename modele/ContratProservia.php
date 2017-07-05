<?php

/**
 * Fichier ContratProservia.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe ContratProservia
 */
class ContratProservia {

    /**
     * Identifiant du contrat
     *
     * @access private
     * @var int 
     */
    private $Id_contrat_proservia;
    /**
     * Libellé du contrat
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe ContratProservia
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du contrat
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_contrat_proservia = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_contrat_proservia = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $this->Id_contrat_proservia = $code;
                $this->libelle = self::getLibelle($code);
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_contrat_proservia = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les contrats
     *
     * @return string
     */
    public function getList() {
        $cp[$this->Id_contrat_proservia] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_contrat_proservia, libelle FROM contrat_proservia ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_contrat_proservia . ' ' . $cp[$ligne->id_contrat_proservia] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du contrat
     *
     * @param int Identifiant du contrat
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM contrat_proservia WHERE Id_contrat_proservia=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
