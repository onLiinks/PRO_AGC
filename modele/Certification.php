<?php

/**
 * Fichier Certification.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Certification
 */
class Certification {

    /**
     * Identifiant de la certification
     *
     * @access private
     * @var int 
     */
    private $Id_certification;
    /**
     * Libellé de la certification
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Identifiant de la catégorie de la certification
     *
     * @access private
     * @var int 
     */
    private $Id_cat_cert;

    /**
     * Constructeur de la classe certification
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la certification
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_certification = '';
                $this->libelle = '';
                $this->Id_cat_cert = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_certification = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->Id_cat_cert = (int) $tab['Id_cat_cert'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM certification WHERE Id_certification=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_certification = $code;
                $this->libelle = $ligne->libelle;
                $this->Id_cat_cert = $ligne->id_cat_cert;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_certification = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->Id_cat_cert = (int) $tab['Id_cat_cert'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage des checkbox contenant les certifications
     *
     * @param int Valeur de l'identifiant de l'entretien
     * 			
     * @return string
     */
    public static function getCheckboxList($Id_entretien) {
        if ($Id_entretien) {
            $entretien = new Entretien($Id_entretien, array());
            if (!empty($entretien->certification)) {
                foreach ($entretien->certification as $i) {
                    $cert[$i] = 'checked="checked"';
                }
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM categorie_certification ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<img src="' . IMG_PLUS . '" id="imgs' . $ligne->id_cat_cert . '" onclick="deroule(\'s' . $ligne->id_cat_cert . '\')">' . $ligne->libelle . '<br />';
            $result2 = $db->query('SELECT * FROM certification WHERE Id_cat_cert=' . mysql_real_escape_string($ligne->id_cat_cert) . '');
            $html .= '<div id="s' . $ligne->id_cat_cert . '" style="display:none">';
            while ($ligne2 = $result2->fetchRow()) {
                $html .= '<input type="checkbox" name="certification[]" value=' . $ligne2->id_certification . ' ' . $cert[$ligne2->id_certification] . ' />' . $ligne2->libelle . '<br />';
            }
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la certification
     *
     * @param int Identifiant de la certification
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM certification WHERE Id_certification=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
