<?php

/**
 * Fichier Planning.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Planning
 */
class Planning {

    /**
     * Identifiant du planning
     *
     * @access private
     * @var int 
     */
    private $Id_planning;
    /**
     * Date de la demande du client
     *
     * @access public
     * @var date 
     */
    public $date_demande;
    /**
     * Date limite de réponse
     *
     * @access public
     * @var date 
     */
    public $date_limite;
    /**
     * Date de début du planning
     *
     * @access public
     * @var date 
     */
    public $date_debut;
    /**
     * Date de soutenance du planning
     *
     * @access private
     * @var date 
     */
    private $date_soutenance;
    /**
     * Durée du planning
     *
     * @access public
     * @var double 
     */
    public $duree;
    /**
     * Type de la durée du planning
     *
     * @access public
     * @var string 
     */
    public $type_duree;
    /**
     * Date de fin de commande du planning
     *
     * @access public
     * @var date 
     */
    public $date_fin_commande;
    /**
     * Date de fin prévisionnelle du planning
     *
     * @access public
     * @var date 
     */
    public $date_fin_previsionnelle;
    /**
     * Commentaire sur le planning
     *
     * @access private
     * @var string 
     */
    private $commentaire;
    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_affaire;
    /**
     * Date de prise en charge par le pole
     *
     * @access private
     * @var date 
     */
    private $date_pec;

    /**
     * Constructeur de la classe Planning
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant du planning
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_planning = '';
                $this->date_demande = strftime('%Y-%m-%d', time());
                $this->date_limite = strftime('%Y-%m-%d', time() + 20 * 24 * 3600);
                $this->heure_limite = '';
                $this->date_debut = '';
                $this->date_soutenance = '';
                $this->duree = '';
                $this->type_duree = '';
                $this->date_fin_commande = '';
                $this->date_fin_previsionnelle = '';
                $this->commentaire = '';
                $this->Id_affaire = '';
                $this->date_pec = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_planning = '';
                $this->date_demande = $tab['date_demande'];
                $this->date_limite = $tab['date_limite'];
                $this->heure_limite = $tab['heure_limite'];
                $this->date_debut = $tab['date_debut'];
                $this->date_soutenance = $tab['date_soutenance'];
                $this->duree = htmlscperso(stripslashes($tab['duree']), ENT_QUOTES);
                $this->type_duree = htmlscperso(stripslashes($tab['type_duree']), ENT_QUOTES);
                $this->date_fin_commande = $tab['date_fin_commande'];
                $this->date_fin_previsionnelle = $tab['date_fin_previsionnelle'];
                $this->commentaire = $tab['commentaire'];
                $this->Id_affaire = $tab['Id_affaire'];
                $this->date_pec = $tab['date_pec'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM planning WHERE Id_planning=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_planning = $code;
                $this->date_demande = $ligne->date_demande;
                $this->date_limite = $ligne->date_limite;
                $this->heure_limite = $ligne->heure_limite;
                $this->date_debut = $ligne->date_debut;
                $this->date_soutenance = $ligne->date_soutenance;
                $this->duree = $ligne->duree;
                $this->type_duree = $ligne->type_duree;
                $this->date_fin_commande = $ligne->date_fin_commande;
                $this->date_fin_previsionnelle = $ligne->date_fin_previsionnelle;
                $this->commentaire = $ligne->commentaire;
                $this->Id_affaire = $ligne->id_affaire;
                $this->date_pec = $ligne->date_pec;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_planning = $code;
                $this->date_demande = $tab['date_demande'];
                $this->date_limite = $tab['date_limite'];
                $this->heure_limite = $tab['heure_limite'];
                $this->date_debut = $tab['date_debut'];
                $this->date_soutenance = $tab['date_soutenance'];
                $this->duree = htmlscperso(stripslashes($tab['duree']), ENT_QUOTES);
                $this->type_duree = htmlscperso(stripslashes($tab['type_duree']), ENT_QUOTES);
                $this->date_fin_commande = $tab['date_fin_commande'];
                $this->date_fin_previsionnelle = $tab['date_fin_previsionnelle'];
                $this->commentaire = $tab['commentaire'];
                $this->Id_affaire = $tab['Id_affaire'];
                $this->date_pec = $tab['date_pec'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de la classe Planning
     *
     * @param int Identifiant du Type de contrat
     * @param Int identifiant du pôle	  
     *
     * @return string	   
     */
    public function form($Id_type_contrat, $Id_pole = null) {
        $type_d[$this->type_duree] = 'checked="checked"';

        if ($Id_type_contrat == 2) {
            if ($this->date_pec == '0000-00-00') {
                $htmldatepec = 'Date de prise en charge par le Pôle : <input id="date_pec" name="date_pec" readonly type="text" value="' . FormatageDate($this->date_pec) . '" size="8"> <a href="javascript:NewCssCal(\'date_pec\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />';
            } else {
                $htmldatepec = 'Date de prise en charge par le Pôle : ' . FormatageDate($this->date_pec) . '<br /><br />';
            }
        }
        $html = '
		<div class="left">
		    Date demande client :
		    <input id="date_demande" name="date_demande" type="text" value="' . FormatageDate($this->date_demande) . '" size="8"> <a href="javascript:NewCssCal(\'date_demande\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
		    Date limite de réponse :
		    <input id="date_limite" name="date_limite" type="text" value="' . FormatageDate($this->date_limite) . '" size="8"> <a href="javascript:NewCssCal(\'date_limite\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
		    Heure limite de réponse :
		    <input name="heure_limite" type="text" value="' . $this->heure_limite . '" size="2"><br /><br />				
		    ' . $htmldatepec . '
		    Date de soutenance / présentation :
		    <input id="date_soutenance" name="date_soutenance" type="text" value="' . FormatageDate($this->date_soutenance) . '" size="8"> <a href="javascript:NewCssCal(\'date_soutenance\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
			    
';
        //Pour les affaires du pôle formation, les informations de date de début, date de fin et durée de l'affaire sont associées à la session
        if ($Id_type_contrat == 3 && $Id_pole == 3) {
            $html .= '<div id="infoSessionPlanning">';
            //Si il y a une session associé: affichage des informations si elles sont remplies mais non modifiable
            if ($this->Id_affaire && ($this->date_debut != "0000-00-00" || $this->date_fin_commande != "0000-00-00" || $this->duree != 0)) {
                $html .= '<span class="vert"> * </span> Début de la prestation :
					<input id="date_debut" name="date_debut" type="text" value="' . FormatageDate($this->date_debut) . '" size="8" readonly=true><br /><br />
					<span class="vert"> * </span> Fin de la prestation :
					<input id="date_fin_commande" name="date_fin_commande" type="text" value="' . FormatageDate($this->date_fin_commande) . '" size="8" readonly=true><br/><br />
					Fin prévisionnelle de la prestation :
					<input id="date_fin_previsionnelle" name="date_fin_previsionnelle" type="text" value="' . FormatageDate($this->date_fin_previsionnelle) . '" size="8" readonly=true><br/><br />
					Durée de la prestation : <br />
					<input type="text" id="duree" name="duree" value="' . $this->duree . '" size="1" readonly=true>
					jours (ouvrés)
					';
            }
            //si il n'y a pas de session associée à l'affaire, les champs sont cachés 
            else {
                $html .= '<input id="date_debut" name="date_debut" type="hidden" value="" >
						<input id="date_fin_commande" name="date_fin_commande" type="hidden" value="" >
						<input id="date_fin_previsionnelle" name="date_fin_previsionnelle" type="hidden" value="" >
						<input type="hidden" id="duree" name="duree" value="" >';
            }
            $html .= '</div>';
        } else {
            $html .='<span class="vert"> * </span> Date de début de l\'affaire :
			<input id="date_debut" name="date_debut" type="text" value="' . FormatageDate($this->date_debut) . '" size="8" onChange="updateDureePlanning();" /> <a href="javascript:NewCssCal(\'date_debut\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
			<span class="vert"> * </span> Date de fin de commande de l\'affaire :
			<input id="date_fin_commande" name="date_fin_commande" value="' . FormatageDate($this->date_fin_commande) . '" size="8" onChange="updateDureePlanning();" /> <a href="javascript:NewCssCal(\'date_fin_commande\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
			Date de fin prévisionnelle de l\'affaire :
			<input id="date_fin_previsionnelle" name="date_fin_previsionnelle" type="text" value="' . FormatageDate($this->date_fin_previsionnelle) . '" size="8" onChange="updateDureePlanning();" /> <a href="javascript:NewCssCal(\'date_fin_previsionnelle\')"><img src="' . IMG_CALENDAR . '" alt="Choisir une date"></a><br /><br />
			Durée de l\'affaire : <br />
			<span id="duree_planning"><input type="text" id="duree" name="duree" value="' . $this->duree . '" size="2"></span>
			jours (ouvrés) <input type="radio" name="type_duree" value="j" ' . $type_d['j'] . '> &nbsp;
			mois <input type="radio" name="type_duree" value="m" ' . $type_d['m'] . '>
			année(s) <input type="radio" name="type_duree" value="a" ' . $type_d['a'] . '>
';
        }
        $html .='<br /><br /></div>
		<div class="right">
			Commentaire : <br />
			<textarea name="commentaire" >' . $this->commentaire . '</textarea>
		</div>
';
        return $html;
    }

    /**
     * Consultation du planning d'une affaire
     */
    public function consultation() {
        if ($this->date_pec != '0000-00-00') {
            $date_pec = 'Date de prise en charge : ' . FormatageDate($this->date_pec);
        }
        $html = '
			<h2>Planning</h2>
			<div class="left">
			    Date demande client : ' . FormatageDate($this->date_demande) . ' <br />
                Date limite de réponse : ' . FormatageDate($this->date_limite) . ' <br />
				Heure limite de réponse : ' . $this->heure_limite . ' <br />
				Date de soutenance / Présentation : ' . FormatageDate($this->date_soutenance) . ' <br />
			    ' . $date_pec . '
			</div>
			<div class="right">
			    Date de début de l\'affaire : ' . FormatageDate($this->date_debut) . ' <br />
			    Date de fin de commande de l\'affaire : ' . FormatageDate($this->date_fin_commande) . ' <br />
				Date de fin prévisionnelle de l\'affaire : ' . FormatageDate($this->date_fin_previsionnelle) . ' <br />
			    Durée de l\'affaire : ' . $this->duree . ' ' . self::getTimeTypeName($this->type_duree) . ' <br/><br/>
			    <h3>Commentaire :</h3> ' . $this->commentaire . '
			</div>
';
        return $html;
    }

    /**
     * Affichage du type de la durée
     *
     * @param string j:jours, m:mois, a:annee
     *
     * @return String
     */
    public static function getTimeTypeName($i) {
        if ($i == 'j') {
            return DAYS;
        } elseif ($i == 'm') {
            return MONTHS;
        } elseif ($i == 'a') {
            return YEARS;
        }
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        if (Affaire::getQualificationResponsible($this->Id_affaire)) {
            $this->date_pec = DATE;
        }
        $set = ' SET Id_planning = ' . mysql_real_escape_string((int) $this->Id_planning) . ', date_demande = "' . mysql_real_escape_string(DateMysqltoFr($this->date_demande, 'mysql')) . '", 
		date_limite = "' . mysql_real_escape_string(DateMysqltoFr($this->date_limite, 'mysql')) . '", heure_limite = "' . mysql_real_escape_string($this->heure_limite) . '", date_debut = "' . mysql_real_escape_string(DateMysqltoFr($this->date_debut, 'mysql')) . '", 
		date_soutenance = "' . mysql_real_escape_string(DateMysqltoFr($this->date_soutenance, 'mysql')) . '", duree = ' . mysql_real_escape_string((float) $this->duree) . ', type_duree = "' . mysql_real_escape_string($this->type_duree) . '", 
		date_fin_commande = "' . mysql_real_escape_string(DateMysqltoFr($this->date_fin_commande, 'mysql')) . '", date_fin_previsionnelle = "' . mysql_real_escape_string(DateMysqltoFr($this->date_fin_previsionnelle, 'mysql')) . '", 
		commentaire = "' . mysql_real_escape_string($this->commentaire) . '", Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ', date_pec = "' . mysql_real_escape_string($this->date_pec) . '"';
        if ($this->Id_planning) {
            $requete = 'UPDATE planning ' . $set . ' WHERE Id_planning = ' . mysql_real_escape_string((int) $this->Id_planning);
        } else {
            $requete = 'INSERT INTO planning ' . $set;
        }
        $db->query($requete);
    }
}

?>
