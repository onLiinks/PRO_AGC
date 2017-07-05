<?php

/**
 * Fichier Intitule.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe Intitule
 */
class Intitule {

    /**
     * Identifiant de l'intitul�
     *
     * @access private
     * @var int 
     */
    private $Id_intitule;
    /**
     * Libell� de l'intitul�
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Identifiant du p�le
     *
     * @access private
     * @var string 
     */
    private $Id_pole;

    /**
     * Constructeur de la classe Intitule
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant d'un intitule
     * @param array Tableau pass� en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_intitule = '';
                $this->libelle = '';
                $this->Id_pole = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
            elseif (!$code && !empty($tab)) {
                $this->Id_intitule = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->Id_pole = $tab['Id_pole'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM intitule WHERE Id_intitule=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_intitule = $code;
                $this->libelle = $ligne->libelle;
                $this->Id_pole = $ligne->id_pole;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_intitule = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->Id_pole = $tab['Id_pole'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les intitules
     *
     * @param int Identifiant du P�le
     *
     * @return string	
     */
    public function getList($Id_pole = 0) {
        $intitule[$this->Id_intitule] = 'selected="selected"';
        $db = connecter();
        $requete = 'SELECT * FROM intitule';
        if ($Id_pole) {
            $requete .= ' WHERE Id_pole LIKE "%' . mysql_real_escape_string($Id_pole) . '%"';
        }
        $requete .= ' ORDER BY libelle';
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_intitule . ' ' . $intitule[$ligne->id_intitule] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'intitul�
     *
     * @param int Identifiant de l'intitul�
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM intitule WHERE Id_intitule=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
