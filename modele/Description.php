<?php

/**
 * Fichier Description.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Description
 */
class Description {

    /**
     * Identifiant de la description
     *
     * @access private
     * @var int 
     */
    private $Id_description;
    /**
     * Identifiant de l'intitule de l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_intitule;
    /**
     * Ville de l'affaire
     *
     * @access public
     * @var string 
     */
    public $ville;
    /**
     * Département de l'affaire
     *
     * @access public
     * @var int 
     */
    public $cp;
    /**
     * Site
     *
     * @access private
     * @var string 
     */
    private $site;
    /**
     * Origine de l'affaire
     *
     * @access private
     * @var string 
     */
    private $origine;
    /**
     * Nature du projet
     *
     * @access private
     * @var string 
     */
    private $nature;
    /**
     * Description de l'affaire
     *
     * @access public
     * @var string 
     */
    public $resume;
    /**
     * Indique si l'affaire est un centre de service
     *
     * @access private
     * @var int 
     */
    private $cds;
    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_affaire;

    /**
     * Constructeur de la classe Description
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de la description
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_description = '';
                $this->Id_intitule = '';
                $this->ville = '';
                $this->cp = '';
                $this->site = '';
                $this->origine = '';
                $this->nature = '';
                $this->resume = '';
                $this->cds = '';
                $this->Id_affaire = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_description = '';
                $this->Id_intitule = $tab['Id_intitule'];
                $this->ville = htmlscperso(stripslashes($tab['ville']), ENT_QUOTES);
                $this->cp = htmlscperso(stripslashes($tab['cp']), ENT_QUOTES);
                $this->site = $tab['site'];
                $this->origine = $tab['origine'];
                $this->nature = $tab['nature'];
                $this->resume = $tab['resume'];
                $this->cds = $tab['cds'];
                $this->Id_affaire = $tab['Id_affaire'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM description WHERE Id_description=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_description = $code;
                $this->Id_intitule = $ligne->id_intitule;
                $this->ville = $ligne->ville;
                $this->cp = $ligne->cp;
                $this->site = $ligne->site;
                $this->origine = $ligne->origine;
                $this->nature = $ligne->nature;
                $this->resume = $ligne->resume;
                $this->cds = $ligne->cds;
                $this->Id_affaire = $ligne->id_affaire;
            }

            /* Cas 4 : un code et un tableau : prendre infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_description = $code;
                $this->Id_intitule = $tab['Id_intitule'];
                $this->ville = htmlscperso(stripslashes($tab['ville']), ENT_QUOTES);
                $this->cp = htmlscperso(stripslashes($tab['cp']), ENT_QUOTES);
                $this->site = $tab['site'];
                $this->origine = $tab['origine'];
                $this->nature = $tab['nature'];
                $this->resume = $tab['resume'];
                $this->cds = $tab['cds'];
                $this->Id_affaire = $tab['Id_affaire'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire
     *
     * @param Int identifiant de la prestation
     * @param Int identifiant du pôle
     *
     * @return string
     */
    public function form($Id_type_contrat, $Id_pole = NULL) {
        $intitule = new Intitule($this->Id_intitule, array());
        $origine[$this->origine] = 'checked="checked"';
        $nature[$this->nature] = 'checked="checked"';

        //Pour une affaire du pôle formation
        if ($Id_type_contrat == 3 && $Id_pole == 3) {
            //affichage de la liste des sessions disponibles et des informations associées (type de session, Intitulé, ville, code postal)
            $html .= '
			<div class="left">Session : ' . Session::getAvailableList($this->Id_affaire) . '</div>
			<div class="right">
				Besoins / Enjeux du client
                <textarea name="resume" rows="6" cols="50">' . $this->resume . '</textarea><br /><br />
                <input type="hidden" name="Id_description" value="' . $this->Id_description . '" /> 
			</div>';
        } else {
            $html .= '<div class="left">
		    		<div id="intituleAffaire">
				    Intitulé : 
				        <select name="Id_intitule">
                            <option value="">' . TITLE_SELECT . '</option>
						    <option value="">-------------------------</option>
                            ' . $intitule->getList($Id_pole) . '
                        </select>
				    <br /><br />
				    </div>
				    Description de l\'affaire
		            <textarea name="resume" rows="6" cols="50">' . $this->resume . '</textarea><br /><br />
		            <input type="hidden" name="Id_description" value="' . $this->Id_description . '" /> 
			    </div>';
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_description = ' . mysql_real_escape_string((int) $this->Id_description) . ', Id_intitule = ' . mysql_real_escape_string((int) $this->Id_intitule) . ', 
						ville = "' . mysql_real_escape_string($this->ville) . '", cp = "' . mysql_real_escape_string($this->cp) . '", site = "' . mysql_real_escape_string($this->site) . '", origine = "' . mysql_real_escape_string($this->origine) . '",
						nature = "' . mysql_real_escape_string($this->nature) . '", resume = "' . mysql_real_escape_string($this->resume) . '", 
						cds = ' . mysql_real_escape_string((int) $this->cds) . ', Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '';
        if ($this->Id_description) {
            $requete = 'UPDATE description ' . $set . ' WHERE Id_description = ' . mysql_real_escape_string((int) $this->Id_description);
        } else {
            $requete = 'INSERT INTO description ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Consultation des informations d'une affaire
     *
     * @param Int identifiant du pôle
     *
     * @return String
     */
    public function consultation($Id_pole = NULL) {
        if (isset($this->resume)) {
            $resume = '<h3>Description de l\'affaire : </h3><br />' . $this->resume . ' <br />';
        }
        //Pour les affaires du pôle formation, affichage de la session et du type de session
        if ($Id_pole == 3) {
            $session = Session::consultationInfoSession($this->Id_affaire);
        }
        $html = '
			<h2>Description</h2>
			<div class="left">
			    ' . $session . '
			    Intitulé : ' . Intitule::getLibelle($this->Id_intitule) . ' <br />
			</div>
			<div class="right">
			    ' . $resume . '
			</div>
';
        return $html;
    }
    
    /**
     * Récupère l'intitulé de l'affaire
     *
     * @param int Identifiant de l'affaire
     *
     * @return int
     */
    public static function getIntitule($Id_description) {
        $db = connecter();
        return $db->query('SELECT libelle FROM intitule i INNER JOIN description d ON i.Id_intitule=d.Id_intitule WHERE d.Id_description = ' . mysql_real_escape_string((int) $Id_description))->fetchRow()->libelle;
    }

}

?>
