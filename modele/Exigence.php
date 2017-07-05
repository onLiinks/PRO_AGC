<?php

/**
 * Fichier Exigence.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Exigence
 */
class Exigence {

    /**
     * Identifiant de l'exigence
     *
     * @access private
     * @var int 
     */
    private $Id_exigence;
    /**
     * Nom de l'exigence
     *
     * @access public
     * @var string 
     */
    public $nom;
    /**
     * Description de l'exigence
     *
     * @access private
     * @var string 
     */
    private $description;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'une exigence
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe exigence
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'exigence
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_exigence = '';
                $this->nom = '';
                $this->description = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_exigence = '';
                $this->nom = htmlscperso(stripslashes($tab['nom']), ENT_QUOTES);
                $this->description = $tab['description'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM exigence WHERE Id_exigence=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_exigence = $code;
                $this->nom = $ligne->nom;
                $this->description = $ligne->description;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_exigence = $code;
                $this->nom = htmlscperso(stripslashes($tab['nom']), ENT_QUOTES);
                $this->description = $tab['description'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une exigence
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="left">
                <span class="infoFormulaire"> * </span> Exigence :
                <input type="text" name="nom" value="' . $this->nom . '" /> ' . $this->erreurs['nom'] . '
			</div>
			<div class="right">
				<span>Description :</span>
                <span><textarea name="description" id="tinyarea1" rows="6" cols="50">' . $this->description . '</textarea></span><br /><br />
			</div>
            <div class="submit">
		        <input type="hidden" name="Id" value="' . (int) $this->Id_exigence . '" />
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
        if ($this->nom == '') {
            $this->erreurs['nom'] = NAME_ERROR;
        }
        return count($this->erreurs) == 0;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_exigence = ' . mysql_real_escape_string((int) $this->Id_exigence) . ', nom = "' . mysql_real_escape_string($this->nom) . '", description = "' . mysql_real_escape_string($this->description) . '"';
        if ($this->Id_exigence) {
            $requete = 'UPDATE exigence ' . $set . ' WHERE Id_exigence = ' . mysql_real_escape_string((int) $this->Id_exigence);
        } else {
            $requete = 'INSERT INTO exigence ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'une exigence
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM exigence ORDER BY nom';
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
			            <th>Exigence</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_exigence'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_exigence'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
			        <td><input type="checkbox" name="Exigence[]" value="' . $ligne['id_exigence'] . '"></td>
';
                }
                $html .= '
                <tr class="row' . $j . '">
	                <td>' . $ligne['nom'] . '</td>
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
     * Suppression d'une exigence
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM exigence WHERE Id_exigence = ' . mysql_real_escape_string((int) $this->Id_exigence));
    }

    /**
     * Affichage des checkbox contenant les exigences
     *
     * @param int Valeur de l'identifiant de l'affaire
     * 			
     * @return string
     */
    public static function getCheckboxList($Id_affaire) {
        if ($Id_affaire) {
            $affaire = new Affaire($Id_affaire, array());
            if (!empty($affaire->exigence)) {
                foreach ($affaire->exigence as $i) {
                    $exigence[$i] = 'checked="checked"';
                }
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM exigence');
        while ($ligne = $result->fetchRow()) {
            $html .= '<input type="checkbox" name="exigence[]" value="' . $ligne->id_exigence . '" ' . $exigence[$ligne->id_exigence] . ' />
            ' . $ligne->nom . '<br />';
        }
        return $html;
    }

}

?>
