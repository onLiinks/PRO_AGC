<?php

/**
 * Fichier EtatMatrimonial.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe EtatMatrimonial
 */
class EtatMatrimonial {

    /**
     * Identifiant de l'�tat matrimonial
     *
     * @access private
     * @var int 
     */
    private $Id_etat_matrimonial;
    /**
     * Libell� de l'�tat matrimonial
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe EtatMatrimonial
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'�tat
     * @param array Tableau pass� en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && count($tab) == 0) {
                $this->Id_etat_matrimonial = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
            elseif (!$code && count($tab) != 0) {
                $this->Id_etat_matrimonial = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && count($tab) == 0) {
                $this->Id_etat_matrimonial = $code;
                $this->libelle = self::getLibelle($code);
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
            elseif ($code && count($tab) != 0) {
                $this->Id_etat_matrimonial = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les �tats
     *
     * @return string
     */
    public function getList() {
        $em[$this->Id_etat_matrimonial] = 'selected="selected"';
        $db = connecter();
        $requete = 'SELECT Id_etat_matrimonial, libelle FROM etat_matrimonial ORDER BY libelle';
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_etat_matrimonial . ' ' . $em[$ligne->id_etat_matrimonial] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'�tat Matrimonial
     *
     * @param int Identifiant de l'�tat Matrimonial
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM etat_matrimonial WHERE Id_etat_matrimonial=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
