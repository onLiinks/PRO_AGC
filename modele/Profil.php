<?php

/**
 * Fichier Profil.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Profil
 */
class Profil {

    /**
     * Identifiant du profil
     *
     * @access private
     * @var int 
     */
    private $Id_profil;

    /**
     * Libellé du profil
     *
     * @access public
     * @var string 
     */
    public $libelle;

    /**
     * Tableau contenant les erreurs suite à la création / modification d'un profil
     *
     * @access private
     * @var array 
     */
    private $erreurs;

    /**
     * Constructeur de la classe Profil
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du profil
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_profil = '';
                $this->libelle = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_profil = '';
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $db = connecter();
                $this->Id_profil = $code;
                if (is_numeric($this->Id_profil)) {
                    $this->libelle = $db->query('SELECT * FROM profil WHERE Id_profil=' . mysql_real_escape_string((int) $code))->fetchRow()->libelle;
                } else {
                    $db = connecter_cegid();
                    $this->libelle = $db->query('SELECT CC_LIBELLE FROM CHOIXCOD WHERE CC_TYPE = "PL4" AND CC_CODE=' . mysql_real_escape_string((int) $code))->fetchRow()->cc_libelle;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_profil = $code;
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un profil
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="' . FORM_URL_ADMIN_SAVE . '" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> Profil :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> ' . $this->erreurs['libelle'] . '
            </div>
            <div class="submit">
	            <input type="hidden" name="Id" value="' . $this->Id_profil . '" />
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
     * Le champs libelle est obligatoire
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
        $set = ' SET Id_profil = ' . mysql_real_escape_string((int) $this->Id_profil) . ', libelle = "' . mysql_real_escape_string($this->libelle) . '"';
        if ($this->Id_profil) {
            $requete = 'UPDATE profil ' . $set . ' WHERE Id_profil = ' . mysql_real_escape_string((int) $this->Id_profil);
        } else {
            $requete = 'INSERT INTO profil ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Recherche d'un profil
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM profil ORDER BY libelle';
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
			            <th>Profil</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td><a href="../gestion/index.php?a=modifier&amp;Id=' . $ligne['id_profil'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a></td>
	                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $ligne['id_profil'] . '&amp;class=' . __CLASS__ . '\') }" /></td>
			        <td><input type="checkbox" name="Profil[]" value="' . $ligne['id_profil'] . '"></td>
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
     * Suppression d'un profil
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM profil WHERE Id_profil = ' . mysql_real_escape_string((int) $this->Id_profil));
    }

    /**
     * Affichage d'une select box contenant les profils
     *
     * @return string	
     */
    public function getList() {
        $profil[$this->Id_profil] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM profil ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_profil . ' ' . $profil[$ligne->id_profil] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    public function getEntitledList($societe = 'PROSERVIA') {
        $profil[$this->Id_profil] = 'selected="selected"';
        $db = connecter_cegid();
        $result = $db->query('SELECT CC_CODE AS Id_profil, CC_LIBELLE AS libelle FROM ' . $societe . '.DBO.CHOIXCOD WHERE CC_TYPE="PLE" ORDER BY CC_LIBELLE');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_profil . ' ' . $profil[$ligne->id_profil] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle du profil
     *
     * Si le profil est numerique, on récupère les informations de la base AGC
     * Sinon on récupère les informations de CEGID	  
     *
     * @param int Identifiant du profil 
     *
     * @return string
     */
    public static function getLibelle($i, $societe = 'PROSERVIA') {
        $db = connecter();
        if ((is_numeric($i))) {
            return $db->query('SELECT libelle FROM profil WHERE Id_profil=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
        } else {
            $db = connecter_cegid();
            return $db->query('SELECT CC_LIBELLE FROM ' . $societe . '.DBO.CHOIXCOD WHERE CC_TYPE="PLE" AND CC_CODE="' . mysql_real_escape_string($i) . '"')->fetchRow()->cc_libelle;
        }
    }

    /**
     * Affichage du libelle du profil
     *
     * Si le profil est numerique, on récupère les informations de la base AGC
     * Sinon on récupère les informations de CEGID	  
     *
     * @param int Identifiant du profil 
     *
     * @return string
     */
    public static function getLibelleCegid($i, $societe = 'PROSERVIA') {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT CC_LIBELLE FROM ' . $societe . '.DBO.CHOIXCOD WHERE CC_TYPE="PL4" AND CC_CODE="' . mysql_real_escape_string($i) . '"')->fetchRow()->cc_libelle;
    }

    /**
     * Affichage d'une select box contenant les profils de Cegid
     *
     * @return string	
     */
    public function getListCegid($societe = 'PROSERVIA') {
        $profil[$this->Id_profil] = 'selected="selected"';
        $db = connecter_cegid();
        $result = $db->query('SELECT CC_CODE, CC_LIBELLE FROM ' . $societe . '.DBO.CHOIXCOD WHERE CC_TYPE = "PL4" ORDER BY CC_LIBELLE');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->cc_code . ' ' . $profil[$ligne->cc_code] . '>' . $ligne->cc_libelle . '</option>';
        }
        return $html;
    }

}

?>
