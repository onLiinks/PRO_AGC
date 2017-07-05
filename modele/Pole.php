<?php

/**
 * Fichier Pole.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe Pole
 */
class Pole {

    /**
     * Identifiant du p�le
     *
     * @access private
     * @var int 
     */
    private $Id_pole;
    /**
     * Libelle du p�le
     *
     * @access public
     * @var string 
     */
    public $libelle;
    /**
     * Tableau contenant les erreurs suite � la cr�ation / modification d'un p�le
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Pole
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du p�le
     * @param array Tableau pass� en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_pole = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
            elseif (!$code && !empty($tab)) {
                $this->Id_pole = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_pole = $code;
                $this->libelle = $db->query('SELECT * FROM pole WHERE Id_pole=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_pole = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de cr�ation / modification d'un p�le
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> P�le :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> ' . $this->erreurs['libelle'] . '
            </div>
            <div class="submit">
	            <input type="hidden" name="Id" value="' . $this->Id_pole . '" />
				<input type="hidden" name="class" value="' . __CLASS__ . '" />
	            <input type="submit" value="' . SAVE_BUTTON . '" />
	        </div>
        </form>
';
        return $html;
    }

    /**
     * V�rification du formulaire
     *
     * Le champs libell� est obligatoire
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
     * Enregistre les donn�es dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_pole = ' . mysql_real_escape_string((int) $this->Id_pole) . ', libelle = "' . mysql_real_escape_string($this->libelle) . '"';
        if ($this->Id_pole) {
            $requete = 'UPDATE pole ' . $set . ' WHERE Id_pole = ' . mysql_real_escape_string((int) $this->Id_pole);
        } else {
            $requete = 'INSERT INTO pole ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'un p�le
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM pole ORDER BY libelle';
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                $partie_admin = '<input type="button" onClick="cocherTout()" value="Tout cocher" /> 
				<input type="button" onClick="decocherTout()" value="Tout d�cocher" />
                // Pour la s�lection : <input type="button" class="boutonSupprimer" onmouseover="return overlib(\'' . DELETE_ALL . '\');" onmouseout="return nd();" onclick="checkedModif(\'supprimer\',\'' . __CLASS__ . '\');">';
            }
            $html = '
			    <form name="formulaire" action="' . FORM_URL_ADMIN_ACTION . '" method="post">
				' . $partie_admin . '
		        <p class="pagination">' . $paged_data['links'] . '</p>
		        <table class="sortable hovercolored">
		            <tr>
			            <th>P�le</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_pole'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_pole'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
			        <td><input type="checkbox" name="Pole[]" value="' . $ligne['id_pole'] . '"></td>
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
     * Suppression d'un p�le
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM pole WHERE Id_pole = ' . mysql_real_escape_string((int) $this->Id_pole));
    }

    /**
     * Affichage d'une select box contenant les p�les
     *
     * @return string	
     */
    public function getList() {
        $pole[$this->Id_pole] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM pole');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_pole . ' ' . $pole[$ligne->id_pole] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les p�les provenant de Cegid
     *
     * @return string	
     */
    public function getListCegid() {
        $pole[$this->Id_pole] = 'selected="selected"';
        $db = connecter_cegid();
        $result = $db->query('SELECT CC_CODE, CC_LIBELLE FROM CHOIXCOD WHERE CC_TYPE = \'PL1\'');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->cc_code . ' ' . $pole[$ligne->cc_code] . '>' . $ligne->cc_libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage d'un menu contenant les P�les
     *
     * @return string
     */
    public static function getMenuList() {
        $db = connecter();
        $result = $db->query('SELECT Id_pole, libelle FROM pole ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '
		    <li><a href="javascript:void(0)" onclick="lienTypeContrat(' . $ligne->id_pole . ')">' . $ligne->libelle . '</a>
				<ul class="smenu">
					<div id="lientc' . $ligne->id_pole . '" class="lient" style="display:none">
					&nbsp;
					</div>
				</ul>
			</li>';
        }
        return $html;
    }

    /**
     * Affichage du libell� du pole
     *
     * @param int Identifiant du pole
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM pole WHERE Id_pole=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }
    
    /**
     * Affichage de l'identifiant du pole
     *
     * @param string Libelle du pole
     *
     * @return int	   
     */
    public static function getIdPole($i) {
        $db = connecter();
        return $db->query('SELECT Id_pole FROM pole WHERE libelle LIKE "%' . mysql_real_escape_string($i) . '%"')->fetchRow()->id_pole;
    }
    
    /**
     * Affichage du libell� du pole
     *
     * @param int Identifiant du pole
     *
     * @return string	   
     */
    public static function getCegidLibelle($i) {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT CC_CODE, CC_LIBELLE FROM CHOIXCOD WHERE CC_TYPE = \'PL1\' AND CC_CODE=\'' . mysql_real_escape_string($i) . '\'')->fetchRow()->cc_libelle;
    }

    /**
     * Affichage du trigramme du p�le
     *
     * @param int Identifiant du p�le
     *
     * @return string	   
     */
    public static function getTrigram($i) {
        $db = connecter();
        return $db->query('SELECT trig FROM pole WHERE Id_pole=' . mysql_real_escape_string((int) $i))->fetchRow()->trig;
    }

}

?>
