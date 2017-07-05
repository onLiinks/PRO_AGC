<?php

/**
 * Fichier Competence.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Competence
 */
class Competence {

    /**
     * Identifiant de la compétence
     *
     * @access private
     * @var int 
     */
    private $Id_competence;
    /**
     * Libelle de la compétence
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Description de la compétence
     *
     * @access private
     * @var string 
     */
    private $description;
    /**
     * Identifiant de la catégorie de la compétence
     *
     * @access public
     * @var int 
     */
    public $Id_cat_comp;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'une compétence
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Competence
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la compétence
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_competence = '';
                $this->libelle = '';
                $this->description = '';
                $this->Id_cat_comp = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_competence = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->description = $tab['description'];
                $this->Id_cat_comp = (int) $tab['Id_cat_comp'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM competence WHERE Id_competence=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_competence = $code;
                $this->libelle = $ligne->libelle;
                $this->description = $ligne->description;
                $this->Id_cat_comp = $ligne->id_cat_comp;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_competence = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->description = $tab['description'];
                $this->Id_cat_comp = (int) $tab['Id_cat_comp'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affiche les checkboxes des compétences
     *
     * @param int Id_affaire Identifiant de l'affaire
     * @param int Id_entretien Identifiant de l'entretien
     *
     * @return string
     */
    public static function getCheckboxList($Id_affaire, $Id_entretien) {
        if ($Id_affaire) {
            $affaire = new Affaire($Id_affaire, array());
            if (!empty($affaire->competence)) {
                foreach ($affaire->competence as $i) {
                    $comp[$i] = 'checked="checked"';
                }
            }
        }
        if ($Id_entretien) {
            $entretien = new Entretien($Id_entretien, array());
            if (!empty($entretien->competence)) {
                foreach ($entretien->competence as $i) {
                    $comp[$i] = 'checked="checked"';
                }
            }
        }

        $db = connecter();
        $result = $db->query('SELECT * FROM categorie_competence ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<img src="' . IMG_PLUS . '" alt="+" id="imga' . $ligne->id_cat_comp . '" onclick="deroule(\'a' . $ligne->id_cat_comp . '\')">' . $ligne->libelle . '<br />';
            $result2 = $db->query('SELECT * FROM competence WHERE Id_cat_comp=' . mysql_real_escape_string($ligne->id_cat_comp) . ' AND archive = 0 ORDER BY libelle');
            $html .= '<div id="a' . $ligne->id_cat_comp . '" style="display:none">';
            while ($ligne2 = $result2->fetchRow()) {
                $html .= '&nbsp;&nbsp;|--<input type="checkbox" name="competence[]" id="c' . $ligne2->id_competence . '" onclick="disableComboBox(\'c' . $ligne2->id_competence . '\', \'nc' . $ligne2->id_competence . '\')" value=' . $ligne2->id_competence . ' ' . $comp[$ligne2->id_competence] . ' />' . $ligne2->libelle . ' <select class="niveau_competence" name="niveau_competence[]" id="nc' . $ligne2->id_competence . '"><option value="">Sélectionner un niveau</option><option value="">--------------------</option>' . self::getLevelList($Id_affaire, $Id_entretien, $ligne2->id_competence) . '</select><br />';
                $script .= '<script>disableComboBox(\'c' . $ligne2->id_competence . '\', \'nc' . $ligne2->id_competence . '\')</script>';
            }
            $html .= '</div>';
        }
        return $html . $script;
    }

    /**
     * Affiche les SELECT du niveau de la compétence
     *
     * @param int Id_affaire Identifiant de l'affaire
     * @param int Id_entretien Identifiant de l'entretien
     * @param int Id_competence Identifiant de la compétence
     *
     * @return string
     */
    public function getLevelList($Id_affaire, $Id_entretien, $Id_competence) {
        $db = connecter();
        $result = $db->query('SELECT * FROM niveau_competence');
        while ($ligne = $result->fetchRow()) {
            if ($Id_entretien) {
                $result2 = $db->query('SELECT Id_niveau_competence FROM entretien_competence 
				WHERE Id_competence=' . mysql_real_escape_string((int) $Id_competence) . ' 
				AND Id_entretien=' . mysql_real_escape_string((int) $Id_entretien) . '');
                while ($ligne2 = $result2->fetchRow()) {
                    $tab[$ligne2->id_niveau_competence] = 'selected="selected"';
                }
            } elseif ($Id_affaire) {
                $result2 = $db->query('SELECT Id_niveau_competence FROM affaire_competence 
				WHERE Id_competence=' . mysql_real_escape_string((int) $Id_competence) . ' 
				AND Id_affaire=' . mysql_real_escape_string((int) $Id_affaire) . '');
                while ($ligne2 = $result2->fetchRow()) {
                    $tab[$ligne2->id_niveau_competence] = 'selected="selected"';
                }
            }
            $html .= '<option value=' . $ligne->id_niveau_competence . ' ' . $tab[$ligne->id_niveau_competence] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la competence
     *
     * @param int Identifiant de la compétence
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM competence WHERE Id_competence=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage du niveau de la compétence
     *
     * @param int Identifiant de la compétence
     *
     * @return string	   
     */
    public static function getLevel($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM niveau_competence WHERE Id_niveau_competence=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
