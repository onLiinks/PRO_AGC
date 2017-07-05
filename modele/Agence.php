<?php

/**
 * Fichier Agence.php
 *
 * @author    Anthony Anne
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Agence
 */
class Agence {

    /**
     * Identifiant de l'agence
     *
     * @access private
     * @var int 
     */
    private $Id_agence;
    /**
     * Adresse de l'agence
     *
     * @access private
     * @var string 
     */
    private $adresse;
    /**
     * Code postal de l'agence
     *
     * @access private
     * @var string 
     */
    private $code_postal;
    /**
     * Nom de l'agence
     *
     * @access public
     * @var string 
     */
    public $nom;
    /**
     * Ville de l'agence
     *
     * @access public
     * @var string 
     */
    public $ville;
    /**
     * Téléphone de l'agence
     *
     * @access private
     * @var string 
     */
    private $tel;
    /**
     * Fax de l'agence
     *
     * @access private
     * @var string 
     */
    private $fax;
    /**
     * Mail de l'agence
     *
     * @access private
     * @var string 
     */
    private $mail;

    /**
     * Constructeur de la classe Agence
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'agence
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_agence = '';
                $this->adresse = '';
                $this->code_postal = '';
                $this->nom = '';
                $this->ville = '';
                $this->tel = '';
                $this->fax = '';
                $this->mail = '';
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT adresse,code_postal,ag.libelle,ville,telephone,fax FROM agence ag 
		                            INNER JOIN societe s ON s.Id_societe=ag.Id_societe 
									WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '" AND Id_agence="' . mysql_real_escape_string($code) . '"')->fetchRow();
                $this->Id_agence = $code;
                $this->adresse = $ligne->adresse;
                $this->code_postal = $ligne->code_postal;
                $this->libelle = $ligne->libelle;
                $this->ville = $ligne->ville;
                $this->tel = $ligne->telephone;
                $this->fax = $ligne->fax;
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Recherche d'une agence
     * 		   
     * @return string
     */
    public static function search() {
        $db = connecter();
        $result = $db->query('SELECT Id_agence,ag.libelle FROM agence ag 
		INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '" ORDER BY ag.libelle');
        $html = '
			<table class="sortable hovercolored">
			    <tr>
				    <th>Id</th>
					<th>Libelle</th>
			    </tr>
';
        while ($ligne = $result->fetchRow()) {
            $html .= '
		        <tr class="row">
			        <td>' . $ligne->id_agence . '</td>
			        <td>' . $ligne->libelle . '</td>
		        </tr>
';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les agences pour la partie commerciale
     *
     * @return string	
     */
    public function getList() {
        $agence[$this->Id_agence] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_agence,ag.libelle FROM agence ag INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '" ORDER BY ag.libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_agence . '" ' . $agence[$ligne->id_agence] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les agences pour la partie RH
     *
     * @return string	
     */
    public function getRHList() {
        $agence[$this->Id_agence] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_agence, ag.libelle FROM agence ag 
		INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '"
		AND Id_agence NOT IN("ALC","ANE","ELI","LYN") ORDER BY ag.libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_agence . ' ' . $agence[$ligne->id_agence] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'agence
     *
     * @param int Identifiant de l'agence
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM agence WHERE Id_agence = "' . mysql_real_escape_string($i) . '"')->fetchRow()->libelle;
    }

    /**
     * Affichage du libelle de l'agence pour les RH
     *
     * @param int Identifiant de l'agence
     *
     * @return string	   
     */
    public static function getRHLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM agence WHERE Id_agence = "' . mysql_real_escape_string($i) . '"')->fetchRow()->libelle;
    }

    /**
     * Affichage de l'identifiant de l'agence
     *
     * @param string Libellé de l'agence
     *
     * @return int	   
     */
    public static function getIdAgence($libelle) {
        $db = connecter();
        return $db->query('SELECT Id_agence FROM agence WHERE libelle LIKE "' . mysql_real_escape_string($libelle) . '%"')->fetchRow()->id_agence;
    }

    /**
     * Affichage de la liste des agences avec checkbox dans la fiche candidat
     *
     * @param array liste des agences sélectionnées
     *
     * @return string
     */
    public static function getCheckboxList($agence_souhaitee) {
        $db = connecter();
        if (!empty($agence_souhaitee)) {
            foreach ($agence_souhaitee as $i) {
                $mobilite[$i] = 'checked="checked"';
            }
        }
        $result = $db->query('SELECT DISTINCT Id_agence, ag.libelle FROM agence ag 
		INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '"
		AND Id_agence NOT IN("ALC","ANE","ELI","LYN") ORDER BY ag.libelle');

        $i = 0;
        $html .= '<span class="infoFormulaire"> * </span> Agence(s) de rattachement : <br/>';
        while ($ligne = $result->fetchRow()) {
            $html .= '<input type="checkbox" name="agence_souhaitee[]" value="' . $ligne->id_agence . '" ' . $mobilite[$ligne->id_agence] . '> ' . $ligne->libelle . ' </input>';
            ++$i;
            if ($i % 4 == 0) {
                $html .= '<br />';
            }
        }
        $html .= '<br/><br/>';
        return $html;
    }

    /**
     * Affichage de la liste des agences dans un champs select dans filtre de recherche des candidats
     *
     * @param array liste des agences sélectionnées
     *
     * @return string
     */
    public static function getSelectList($agence_souhaitee) {
        //tableau pour permettre de noter en 'selected' les agences sélectionnées
        if (!empty($agence_souhaitee)) {
            foreach ($agence_souhaitee as $i) {
                $mobilite[$i] = 'selected="selected"';
            }
        }
        $db = connecter();
        //récupération des agences de Proservia
        $result = $db->query('SELECT DISTINCT Id_agence, ag.libelle FROM agence ag 
		INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '"
		AND Id_agence NOT IN("ALC","ANE","ELI","LYN") ORDER BY Id_agence');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_agence . '" ' . $mobilite[$ligne->id_agence] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    /**
     * Récupération de la liste des agences au format JSON
     *
     * @return string
     */
    public static function getJsonList() {
        $db = connecter();
        //récupération des agences de Proservia
        $result = $db->query('SELECT DISTINCT Id_agence, ag.libelle FROM agence ag 
		INNER JOIN societe s ON s.Id_societe=ag.Id_societe WHERE s.libelle="' . mysql_real_escape_string($_SESSION['societe']) . '"
		AND Id_agence NOT IN("ALC","ANE","ELI","LYN") ORDER BY Id_agence');
        while ($ligne = $result->fetchRow()) {
            $html .= '["' . $ligne->id_agence . '", "' . $ligne->libelle . '"],';
        }
        return '[' . $html . ']';
    }

    /**
     * Récupération du mail destinataire de la demande de changement
     *
     * @param string Identifiant de l'agence
     *
     * @return string
     */
    public static function getRHrecipientMail($Id_agence) {
        $db = connecter();
        $mails = $db->query('SELECT mail FROM agence_rh WHERE Id_agence = "' . mysql_real_escape_string($Id_agence) . '"');
        $a = array();
        while ($ligne = $mails->fetchRow()) {
            array_push($a, $ligne->mail);
        }
        return $a;
    }

}

?>