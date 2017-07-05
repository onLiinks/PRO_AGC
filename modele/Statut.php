<?php

/**
 * Fichier Statut.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Statut
 */
class Statut {

    /**
     * Identifiant du statut
     *
     * @access private
     * @var int 
     */
    private $Id_statut;
    /**
     * Libelle du statut
     *
     * @access private
     * @var string 
     */
    public $libelle;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'un statut
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Statut
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du statut
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_statut = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_statut = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_statut = $code;
                $this->libelle = $db->query('SELECT * FROM statut WHERE Id_statut=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_statut = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un statut
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> Statut :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> ' . $this->erreurs['libelle'] . '
            </div>
            <div class="submit">
	            <input type="hidden" name="Id" value="' . (int) $this->Id_statut . '" />
                <input type="hidden" name="class" value="' . __CLASS__ . '" />				
	            <input type="submit"  value="' . SAVE_BUTTON . '" />
	        </div>
        </form>
';
        return $html;
    }

    /**
     * Vérification du formulaire
     *
     * Le champs nom est obligatoire
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
        $set = ' SET Id_statut = ' . mysql_real_escape_string((int) $this->Id_statut) . ', libelle = "' . mysql_real_escape_string($this->libelle) . '"';
        if ($this->Id_statut) {
            $requete = 'UPDATE statut ' . $set . ' WHERE Id_statut = ' . mysql_real_escape_string((int) $this->Id_statut);
        } else {
            $requete = 'INSERT INTO statut ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'un statut
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM statut ORDER BY libelle';
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                $partie_admin = '<input type="button" onClick="cocherTout()" value="Tout cocher" /> 
				<input type="button" onClick="decocherTout()" value="Tout décocher" />
                // Pour la sélection : <input type="button" class="boutonSupprimer" onmouseover="return overlib(\'' . DELETE_ALL . '\');" onmouseout="return nd();" onclick="checkedModif(\'supprimer\',\'' . __CLASS__ . '\');">';
                $admin = 1;
            }
            $html = '
			    <form name="formulaire" action="' . FORM_URL_ADMIN_ACTION . '" method="post">
				' . $partie_admin . '
		        <p class="pagination">' . $paged_data['links'] . '</p>
		        <table class="sortable hovercolored">
		            <tr>
			            <th>Statut</th>
		            </tr>
';
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                $htmlAdmin = '';
                if ($admin) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_statut'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_statut'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
			        <td><input type="checkbox" name="Statut[]" value="' . $ligne['id_statut'] . '"></td>
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
            $html .= '
                </table>
                <p class="pagination">' . $paged_data['links'] . '</p>
				' . $partie_admin . '
				<input type="hidden" name="class" value="' . __CLASS__ . '">
				<input type="hidden" name="action">
				</form>
';
        }
        return $html;
    }

    /**
     * Suppression d'un statut
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM statut WHERE Id_statut = ' . mysql_real_escape_string((int) $this->Id_statut));
    }

    /**
     * Affichage d'une select box contenant les statuts
     *
     * @return string	
     */
    public function getList() {
        $statut[$this->Id_statut] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM statut');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_statut . ' ' . $statut[$ligne->id_statut] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les statuts
     *
     * @return string	
     */
    public static function getListMultiple($satuts = array(), $statutFilter = null) {
        foreach ($satuts as $value) {
            $statut[$value] = 'selected="selected"';
        }

        if($statutFilter) {
            $filter = ' WHERE Id_statut IN (';
            $i = 0;
            while ($i < count($statutFilter)) {
                if($i == count($statutFilter) - 1)
                    $filter .= $statutFilter[$i];
                else 
                    $filter .= $statutFilter[$i] . ',';
                $i++;
            }
            $filter .= ')';
        }

        $db = connecter();
        $result = $db->query('SELECT * FROM statut' . $filter);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_statut . ' ' . $statut[$ligne->id_statut] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du statut
     *
     * @param int Identifiant du statut
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM statut WHERE Id_statut=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage du trigramme du statut
     *
     * @param int Identifiant du statut
     *
     * @return string	   
     */
    public static function getTrigram($i) {
        $db = connecter();
        return $db->query('SELECT trig FROM statut WHERE Id_statut=' . mysql_real_escape_string((int) $i))->fetchRow()->trig;
    }

    /**
     * Affichage d'un menu contenant les statuts
     *
     * @param int Identifiant du statut
     *
     * @return string
     */
    public static function getMenuList($Id_pole) {
        $db = connecter();
        $result = $db->query('SELECT Id_statut, libelle FROM statut');
        while ($ligne = $result->fetchRow()) {
            $html .= '<a href="../com/index.php?a=consulterProposition&amp;Id_statut=' . $ligne->id_statut . '&amp;Id_pole=' . $Id_pole . '"><p class="typec">' . $ligne->libelle . '</p></a>';
        }
        return $html;
    }

}

?>
