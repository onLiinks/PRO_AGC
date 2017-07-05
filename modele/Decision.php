<?php

/**
 * Fichier Decision.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe Decision
 */
class Decision {

    /**
     * Identifiant de la decision
     *
     * @access public
     * @var int 
     */
    public $Id_decision;
    /**
     * Indique le type de r�ponse
     *
     * @access private
     * @var string 
     */
    private $repondre;
    /**
     * Identifiant de la raison de la decision
     *
     * @access public
     * @var int
     */
    public $Id_raison_decision;
    /**
     * D�cideur de la r�union de lancement
     *
     * @access public
     * @var string 
     */
    public $decideur_reunion_lancement;
    /**
     * D�cideur de la r�union de bouclage
     *
     * @access public
     * @var string 
     */
    public $decideur_reunion_bouclage;
    /**
     * Date de la r�union de lancement 
     *
     * @access public
     * @var date 
     */
    public $date_reunion_lancement;
    /**
     * Date de la r�union de bouclage
     *
     * @access public
     * @var date 
     */
    public $date_reunion_bouclage;
    /**
     * Commentaire sur la d�cision
     *
     * @access public
     * @var string 
     */
    public $commentaire;
    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_affaire;

    /**
     * Constructeur de la classe decision
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de la decision
     * @param array Tableau pass� en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_decision = '';
                $this->Id_raison_decision = '';
                $this->repondre = '';
                $this->decideur_reunion_lancement = '';
                $this->decideur_reunion_bouclage = '';
                $this->date_reunion_lancement = '';
                $this->date_reunion_bouclage = '';
                $this->Id_affaire = '';
                $this->commentaire_decision = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
            elseif (!$code && !empty($tab)) {
                $this->Id_decision = '';
                $this->repondre = $tab['repondre'];
                $this->Id_raison_decision = (int) $tab['Id_raison_decision'];
                $this->decideur_reunion_lancement = htmlscperso(stripslashes($tab['decideur_reunion_lancement']), ENT_QUOTES);
                $this->decideur_reunion_bouclage = htmlscperso(stripslashes($tab['decideur_reunion_bouclage']), ENT_QUOTES);
                $this->date_reunion_lancement = $tab['date_reunion_lancement'];
                $this->date_reunion_bouclage = $tab['date_reunion_bouclage'];
                $this->Id_affaire = (int) $tab['Id_affaire'];
                $this->commentaire_decision = $tab['commentaire_decision'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM decision WHERE Id_decision=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_decision = $code;
                $this->repondre = $ligne->repondre;
                $this->Id_raison_decision = $ligne->id_raison_decision;
                $this->decideur_reunion_lancement = $ligne->decideur_reunion_lancement;
                $this->decideur_reunion_bouclage = $ligne->decideur_reunion_bouclage;
                $this->date_reunion_lancement = $ligne->date_reunion_lancement;
                $this->date_reunion_bouclage = $ligne->date_reunion_bouclage;
                $this->Id_affaire = $ligne->id_affaire;
                $this->commentaire_decision = $ligne->commentaire;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_decision = $code;
                $this->repondre = $tab['repondre'];
                $this->Id_raison_decision = (int) $tab['Id_raison_decision'];
                $this->decideur_reunion_lancement = htmlscperso(stripslashes($tab['decideur_reunion_lancement']), ENT_QUOTES);
                $this->decideur_reunion_bouclage = htmlscperso(stripslashes($tab['decideur_reunion_bouclage']), ENT_QUOTES);
                $this->date_reunion_lancement = $tab['date_reunion_lancement'];
                $this->date_reunion_bouclage = $tab['date_reunion_bouclage'];
                $this->Id_affaire = (int) $tab['Id_affaire'];
                $this->commentaire_decision = $tab['commentaire_decision'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de cr�ation / modification de la d�cision
     *
     * @return string	   
     */
    public function form() {
        $decision[$this->repondre] = 'checked="checked"';
        $html = '
            <div class="center">
			    D�cision de r�pondre :
				' . YES . ' <input type="radio" name="repondre" ' . $decision['Oui'] . ' value="Oui" onclick="typeReponse(this.value)" />
				' . NO . ' <input type="radio" name="repondre" ' . $decision['Non'] . ' value="Non" onclick="typeReponse(this.value)" /><br /><br />
			<div id="reponse">
			' . $this->goNoGoAnswerForm($this->repondre) . '
            </div>			
				<input type="hidden" name="Id_decision" value="' . (int) $this->Id_decision . '" />
            </div>
';
        return $html;
    }

    /**
     * Affichage du formulaire correspondant au type de la r�ponse GO / NOGO
     *
     * @param String r�sultat de la r�ponse : Oui ou Non
     *
     * @return string	   
     */
    public function goNoGoAnswerForm($repondre) {
        if ($repondre == 'Oui') {
            $html = '
			R�union de lancement de la proposition |
			Qui : </span><span><input type="text" name="decideur_reunion_lancement" value="' . $this->decideur_reunion_lancement . '" />
			Quand : </span><span><input type="text" onfocus="showCalendarControl(this)" name="date_reunion_lancement" value="' . FormatageDate($this->date_reunion_lancement) . '" size="8" /><br /><br />
			R�union de bouclage de la proposition |
			Qui : </span><span><input type="text" name="decideur_reunion_bouclage" value="' . $this->decideur_reunion_bouclage . '" />
			Quand : </span><span><input type="text" onfocus="showCalendarControl(this)" name="date_reunion_bouclage" value="' . FormatageDate($this->date_reunion_bouclage) . '" size="8" />
';
        } elseif ($repondre == 'Non') {
            $html = '
			Raisons :
			<select name="Id_raison_decision">
			<option value="">' . REASON_SELECT . '</option>
			<option value="">---------------------------------------------</option>
			' . $this->getReasonList() . '
			</select>
			<br /><br />
			Commentaires :<br />
			<textarea name="commentaire_decision" id="tinyarea11" cols="60" rows="10">' . $this->commentaire_decision . '</textarea>
';
        }
        return $html;
    }

    /**
     * Consultation de l'analyse des risques d'une affaire
     */
    public function consultation() {
        if ($this->repondre == 'Oui') {
            $repondre = '
			R�union de lancement de la proposition |
			Qui : ' . $this->decideur_reunion_lancement . '
			Quand : ' . FormatageDate($this->date_reunion_lancement) . '<br /><br />
			R�union de bouclage de la proposition |
            Qui : ' . $this->decideur_reunion_bouclage . '
			Quand : ' . FormatageDate($this->date_reunion_bouclage) . '
';
        } elseif ($this->repondre == 'Non') {
            $db = connecter();
            $ligne = $db->query('SELECT nom FROM raison_decision WHERE Id_raison_decision=' . mysql_real_escape_string((int) $this->Id_raison_decision))->fetchRow();
            $repondre = '
			Raison : ' . $ligne->nom . '<br /><br />
			Commentaires : ' . $this->commentaire_decision . '<br /><br />
';
        }
        $html = '
			<h2>D�cision</h2><br />
            <div class="center">
			    D�cision de r�pondre :</span> <span>' . $this->repondre . '<br /><br />
				' . $repondre . '
            </div>
';
        return $html;
    }

    /**
     * Enregistre les donn�es dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_decision = ' . mysql_real_escape_string((int) $this->Id_decision) . ', Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ', 
		repondre = "' . mysql_real_escape_string($this->repondre) . '", Id_raison_decision = ' . mysql_real_escape_string((int) $this->Id_raison_decision) . ', 
		decideur_reunion_lancement = "' . mysql_real_escape_string($this->decideur_reunion_lancement) . '", decideur_reunion_bouclage = "' . mysql_real_escape_string($this->decideur_reunion_bouclage) . '", 
		date_reunion_lancement = "' . mysql_real_escape_string(DateMysqltoFr($this->date_reunion_lancement, 'mysql')) . '", date_reunion_bouclage = "' . mysql_real_escape_string(DateMysqltoFr($this->date_reunion_bouclage, 'mysql')) . '", 
		commentaire = "' . mysql_real_escape_string($this->commentaire_decision) . '"';
        if ($this->Id_decision) {
            $requete = 'UPDATE decision ' . $set . ' WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '';
        } else {
            $requete = 'INSERT INTO decision ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Affichage d'une select box contenant les raisons des decisions
     *
     * @return string	
     */
    public function getReasonList() {
        $decision[$this->Id_raison_decision] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM raison_decision');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_raison_decision . ' ' . $decision[$ligne->id_raison_decision] . '>' . $ligne->nom . '</option>';
        }
        return $html;
    }

}

?>
