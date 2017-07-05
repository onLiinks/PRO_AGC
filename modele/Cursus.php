<?php

/**
 * Fichier Cursus.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Cursus
 */
class Cursus {

    /**
     * Identifiant du cursus
     *
     * @access private
     * @var int 
     */
    private $Id_cursus;
    /**
     * Libellé du cursus
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'un cursus
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe cursus
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du cursus
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_cursus = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_cursus = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_cursus = $code;
                $this->libelle = $db->query('SELECT * FROM cursus WHERE Id_cursus=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_cursus = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un cursus
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> Cursus :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> ' . $this->erreurs['libelle'] . '
            </div>
            <div class="submit">
		        <input type="hidden" name="Id" value="' . (int) $this->Id_cursus . '" />
		        <input type="hidden" name="class" value="' . __CLASS__ . '" />
	            <input type="submit" value="' . SAVE_BUTTON . '" />
		    </div>
        </form>
';
        return $html;
    }

    /**
     * Vérification du formulaire
     *
     * Le champ nom est obligatoire
     * 		   
     * @return bool
     */
    public function check() {
        if ($this->libelle == '') {
            $this->erreurs['libelle'] = NAME_ERROR;
        }
        return count($this->erreurs) == 0;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_cursus = ' . mysql_real_escape_string((int) $this->Id_cursus) . ', libelle = "' . mysql_real_escape_string($this->libelle) . '"';
        if ($this->Id_cursus) {
            $requete = 'UPDATE cursus ' . $set . ' WHERE Id_cursus = ' . mysql_real_escape_string((int) $this->Id_cursus);
        } else {
            $requete = 'INSERT INTO cursus ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'un cursus
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM cursus ORDER BY libelle';
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                $partie_admin = '<input type="button" onClick="cocherTout()" value="Tout cocher" /> 
				<input type="button" onClick="decocherTout()" value="Tout décocher" />
                // Pour la sélection : <input type="button" class="boutonSupprimer" onmouseover="return overlib(\'' . DELETE_ALL . '\');" onmouseout="return nd();" onclick="checkedModif(\'supprimer\',\'' . __CLASS__ . '\');">';
            }
            $html = '
			    <form name="formulaire" action="' . FORM_URL_ADMIN_ACTION . '" method="post">
				' . $partie_admin . '
		        <p class="pagination">' . $paged_data['links'] . '</p>
		        <table class="sortable hovercolored">
		            <tr>
			            <th>Cursus</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_cursus'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_cursus'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
			        <td><input type="checkbox" name="Cursus[]" value="' . $ligne['id_cursus'] . '"></td>
';
                }
                $html .= '
                <tr class="row' . $j . '">
	                <td>' . $ligne['libelle'] . '</td>
	                ' . $htmlAdmin . '
                </tr>
';
                ++$i;
            }
            $html .= '</table><p class="pagination">' . $paged_data['links'] . '</p>
				' . $partie_admin . '
				<input type="hidden" name="class" value="' . __CLASS__ . '">
	            <input type="hidden" name="action">
				</form>
';
        }
        return $html;
    }

    /**
     * Suppression d'un cursus
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM cursus WHERE Id_cursus = ' . mysql_real_escape_string((int) $this->Id_cursus));
    }

    /**
     * Affichage d'une select box contenant les cursus
     *
     * @return string	
     */
    public function getList() {
        $cursus[$this->Id_cursus] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM cursus ORDER BY Id_cursus');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_cursus . ' ' . $cursus[$ligne->id_cursus] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du cursus
     *
     * @param int Identifiant du cursus
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM cursus WHERE Id_cursus=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

}

?>
