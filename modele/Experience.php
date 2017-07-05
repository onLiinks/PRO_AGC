<?php

/**
 * Fichier Experience.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Experience
 */
class Experience {

    /**
     * Identifiant de l'experience
     *
     * @access private
     * @var int 
     */
    private $Id_exp_info;
    /**
     * Libelle de l'experience
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'une experience
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Experience
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'experience
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_exp_info = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_exp_info = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM exp_info WHERE Id_exp_info=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_exp_info = $code;
                $this->libelle = $ligne->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_exp_info = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une experience
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> Expérience :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> ' . $this->erreurs['libelle'] . '<br /><br />
			</div>
            <div class="submit">
		        <input type="hidden" name="Id" value="' . (int) $this->Id_exp_info . '" />
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
     * Le champ libelle est obligatoire
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
        $set = ' SET Id_exp_info = ' . mysql_real_escape_string((int) $this->Id_exp_info) . ', libelle = "' . mysql_real_escape_string($this->libelle) . '"';
        if ($this->Id_exp_info) {
            $requete = 'UPDATE exp_info ' . $set . ' WHERE Id_exp_info = ' . mysql_real_escape_string((int) $this->Id_exp_info);
        } else {
            $requete = 'INSERT INTO exp_info ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'une experience
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM exp_info ORDER BY libelle';
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            $html = '
		        <p class="pagination">' . $paged_data['links'] . '</p>
		        <table class="sortable hovercolored">
		            <tr>
			            <th>Id</th>
						<th>Libelle</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_exp_info'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_exp_info'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
';
                }
                $html .= '
                <tr class="row' . $j . '">
	                <td>' . $ligne['id_exp_info'] . '</td>
					<td>' . $ligne['libelle'] . '</td>
	                ' . $htmlAdmin . '
                </tr>
';
                ++$i;
            }
            $html .= '
                </table>
                <p class="pagination">' . $paged_data['links'] . '</p>
				<input type="hidden" name="class" value="' . __CLASS__ . '">
	            <input type="hidden" name="action"> 				
				</form>
';
        }
        return $html;
    }

    /**
     * Suppression d'une expérience
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM exp_info WHERE Id_exp_info = ' . mysql_real_escape_string((int) $this->Id_exp_info));
    }

    /**
     * Affichage du libelle d'une expérience
     *
     * @param int Identifiant de l'experience
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM exp_info WHERE Id_exp_info=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les contrats
     *
     * @return string
     */
    public function getList() {
        $exp[$this->Id_exp_info] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_exp_info, libelle FROM exp_info ORDER BY Id_exp_info');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_exp_info . ' ' . $exp[$ligne->id_exp_info] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

}

?>
