<?php

/**
 * Fichier Langue.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Langue
 */
class Langue {

    /**
     * Identifiant de la langue
     *
     * @access private
     * @var int 
     */
    private $Id_langue;
    /**
     * Libellé de la langue
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Constructeur de la classe Langue
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la Langue
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_langue = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_langue = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_langue = $code;
                $this->libelle = $db->query('SELECT * FROM langue WHERE Id_langue=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_langue = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage d'une select box contenant les langues
     *
     * @return string
     */
    public function getList() {
        $langue[$this->Id_langue] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM langue ORDER BY Id_langue');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_langue . ' ' . $langue[$ligne->id_langue] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du formulaire de selection des langues
     *
     * @param int Valeur de l'identifiant de l'affaire
     * @param int Valeur de l'identifiant de l'entretien
     *
     * @return string
     */
    public function formLangue($Id_affaire, $Id_entretien) {
        $count = 0;
        if ($Id_entretien) {
            $entretien = new Entretien($Id_entretien, array());
            $count = count($entretien->langue);
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            while ($i < $count) {
                $langue = new Langue($entretien->langue[$i], array());
                ++$i;
                $html .= '
	            <div id="autre' . __CLASS__ . $i . '">
				    <select name="langue[]">
                        <option value="">' . LANGUAGE_SELECT . '</option>
			            <option value="">-------------------------</option>
                        ' . $langue->getList() . '
                    </select>
			        <select name="niveau_langue[]">
                        <option value="">' . LEVEL_SELECT . '</option>
			            <option value="">-------------------------</option>
                        ' . $langue->getLevelList("", $Id_entretien) . '
                    </select>
				</div>
';
            }
            $html .= '
		    <div id="autre' . __CLASS__ . $nb_suivant . '">
		        <input type="hidden" id="nb_Langue" value="' . $nb_courant . '">
		    </div>
';
        } elseif ($Id_affaire) {
            $affaire = new Affaire($Id_affaire, array());
            $count = count($affaire->langue);
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            while ($i < $count) {
                $langue = new Langue($affaire->langue[$i], array());
                ++$i;
                $html .= '
				<div id="autre' . __CLASS__ . $i . '">
	                <select name="langue[]">
                        <option value="">' . LANGUAGE_SELECT . '</option>
			            <option value="">-------------------------</option>
                        ' . $langue->getList() . '
                    </select>
			        <select name="niveau_langue[]">
                        <option value="">' . LEVEL_SELECT . '</option>
			            <option value="">-------------------------</option>
                        ' . $langue->getLevelList($Id_affaire, "") . '
                    </select>
				</div>
';
            }
            $html .= '
		    <div id="autre' . __CLASS__ . $nb_suivant . '">
		        <input type="hidden" id="nb_Langue" value="' . $nb_courant . '">
		    </div>
';
        } elseif (!$Id_affaire && !$Id_entretien) {
            $html .= self::add(1);
        }
        return $html;
    }

    /**
     * Fonction appelée en Ajax qui permet d'ajouter une langue
     *
     * @param int nombre de langue courante
     */
    public static function add($nb) {
        $nb2 = $nb + 1;
        $langue = new Langue('', '');
        $html = '
	        <select name="langue[]">
                <option value="">' . LANGUAGE_SELECT . '</option>
		        <option value="">-------------------------</option>
                ' . $langue->getList() . '
            </select>
		    <select name="niveau_langue[]">
                <option value="">' . LEVEL_SELECT . '</option>
		        <option value="">-------------------------</option>
                ' . $langue->getLevelList("", "") . '
            </select>
			<div id="autre' . __CLASS__ . $nb2 . '"><input type="hidden" id="nb_Langue" value="' . $nb . '"></div>
';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les niveaux de langues
     *
     * @param int Identifiant de l'affaire
     * @param int Identifiant de l'entretien
     *
     * @return string	
     */
    public function getLevelList($Id_affaire, $Id_entretien) {
        $db = connecter();
        $result = $db->query('SELECT * FROM niveau_langue');
        while ($ligne = $result->fetchRow()) {
            if ($Id_affaire) {
                $ligne2 = $db->query('SELECT Id_niveau_langue FROM affaire_langue 
				WHERE Id_langue=' . mysql_real_escape_string((int) $this->Id_langue) . ' AND Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow();
                $tab[$ligne2->id_niveau_langue] = 'selected="selected"';
            } elseif ($Id_entretien) {
                $ligne2 = $db->query('SELECT Id_niveau_langue FROM entretien_langue 
				WHERE Id_langue=' . mysql_real_escape_string((int) $this->Id_langue) . ' AND Id_entretien=' . mysql_real_escape_string((int) $Id_entretien))->fetchRow();
                $tab[$ligne2->id_niveau_langue] = 'selected="selected"';
            }
            $html .= '<option value=' . $ligne->id_niveau_langue . ' ' . $tab[$ligne->id_niveau_langue] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de la langue
     *
     * @param int Identifiant de la langue
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM langue WHERE Id_langue=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage du niveau de la langue
     *
     * @param int Identifiant de la langue
     *
     * @return string	   
     */
    public static function getLevel($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM niveau_langue WHERE Id_niveau_langue=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
