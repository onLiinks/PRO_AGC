<?php

/**
 * Fichier Analyse.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Analyse
 */
class Analyse {

    /**
     * Identifiant de l'analyse des risques
     *
     * @access private
     * @var int 
     */
    private $Id_analyse_risque;
    /**
     * Identifiant de l'analyse commerciale
     *
     * @access private
     * @var int 
     */
    private $Id_analyse_commerciale;
    /**
     * Informations sur le dernier projet réalisé avec le client
     *
     * @access private
     * @var string 
     */
    private $dernier_projet;
    /**
     * Indique l'existence ou non d'un cahier des charges
     *
     * @access private
     * @var int 
     */
    private $cdc;
    /**
     * Indique si l'interlocuteur est le décideur
     *
     * @access private
     * @var int 
     */
    private $decideur;
    /**
     * Indique si le budget a été défini
     *
     * @access private
     * @var int 
     */
    private $budget_defini;
    /**
     * Indique le montant du budget
     *
     * @access private
     * @var double 
     */
    private $montant_budget;
    /**
     * Indique si un rendez vous a été effectué avec le client
     *
     * @access private
     * @var int 
     */
    private $rdv;
    /**
     * Indique si les concurrents sont identifiés
     *
     * @access private
     * @var int 
     */
    private $concurrents_identifies;
    /**
     * Indique si les partenaires sont identifiés
     *
     * @access private
     * @var int 
     */
    private $partenaires_identifies;
    /**
     * Informations sur les concurrents
     *
     * @access private
     * @var string 
     */
    private $concurrents;
    /**
     * Informations sur les partenaires
     *
     * @access private
     * @var string 
     */
    private $partenaires;
    /**
     * Informations sur les risques liés à la proposition
     *
     * @access private
     * @var string 
     */
    private $risque_proposition;
    /**
     * Informations sur les risques liés au projet
     *
     * @access private
     * @var string 
     */
    private $risque_projet;
    /**
     * Potentiel de signature
     *
     * @access private
     * @var int 
     */
    private $potentiel;
    /**
     * Niveau global du risque
     *
     * @access private
     * @var string 
     */
    private $niveau;
    /**
     * Poucentage de réussite
     *
     * @access private
     * @var double 
     */
    private $pourcentage_reussite;
    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_affaire;

    /**
     * Constructeur de la classe analyse
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'analyse
     * @param string Valeur du type d'analyse (RIS et COM)
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $type, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_analyse_risque = '';
                $this->Id_analyse_commerciale = '';
                $this->dernier_projet = '';
                $this->cdc = '';
                $this->decideur = '';
                $this->budget_defini = '';
                $this->montant_budget = '';
                $this->rdv = '';
                $this->concurrents_identifies = '';
                $this->partenaires_identifies = '';
                $this->concurrents = '';
                $this->partenaires = '';
                $this->risque_proposition = '';
                $this->risque_projet = '';
                $this->potentiel = '';
                $this->niveau = '';
                $this->pourcentage_reussite = '';
                $this->Id_affaire = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_analyse_risque = '';
                $this->Id_analyse_commerciale = '';
                $this->dernier_projet = $tab['dernier_projet'];
                $this->cdc = $tab['cdc'];
                $this->decideur = $tab['decideur'];
                $this->budget_defini = $tab['budget_defini'];
                $this->montant_budget = htmlscperso(stripslashes($tab['montant_budget']), ENT_QUOTES);
                $this->rdv = $tab['rdv'];
                $this->concurrents_identifies = (int) $tab['concurrents_identifies'];
                $this->partenaires_identifies = (int) $tab['partenaires_identifies'];
                $this->concurrents = $tab['concurrents'];
                $this->partenaires = $tab['partenaires'];
                $this->risque_proposition = $tab['risque_proposition'];
                $this->risque_projet = $tab['risque_projet'];
                $this->potentiel = (int) $tab['potentiel'];
                $this->niveau = $tab['niveau'];
                $this->pourcentage_reussite = $tab['pourcentage_reussite'];
                $this->Id_affaire = (int) $tab['Id_affaire'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                if ($type == 'COM') {
                    $ligne = $db->query('SELECT * FROM analyse_commerciale WHERE Id_analyse_commerciale=' . mysql_real_escape_string((int) $code))->fetchRow();
                    $this->Id_analyse_commerciale = $code;
                    $this->dernier_projet = $ligne->dernier_projet;
                    $this->cdc = $ligne->cdc;
                    $this->decideur = $ligne->decideur;
                    $this->budget_defini = $ligne->budget_defini;
                    $this->montant_budget = $ligne->montant_budget;
                    $this->rdv = $ligne->rdv;
                    $this->concurrents_identifies = $ligne->concurrents_identifies;
                    $this->partenaires_identifies = $ligne->partenaires_identifies;
                    $this->concurrents = $ligne->concurrents;
                    $this->partenaires = $ligne->partenaires;
                    $this->Id_affaire = $ligne->id_affaire;
                    $this->potentiel = $ligne->potentiel;
                } elseif ($type == 'RIS') {
                    $ligne = $db->query('SELECT * FROM analyse_risque WHERE Id_analyse_risque=' . mysql_real_escape_string((int) $code))->fetchRow();
                    $this->Id_analyse_risque = $code;
                    $this->risque_proposition = $ligne->risque_proposition;
                    $this->risque_projet = $ligne->risque_projet;
                    $this->niveau = $ligne->niveau;
                    $this->pourcentage_reussite = $ligne->pourcentage_reussite;
                    $this->Id_affaire = $ligne->id_affaire;
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                if ($type == 'COM') {
                    $this->Id_analyse_commerciale = $code;
                } elseif ($type == 'RIS') {
                    $this->Id_analyse_risque = $code;
                }
                $this->dernier_projet = $tab['dernier_projet'];
                $this->cdc = $tab['cdc'];
                $this->decideur = $tab['decideur'];
                $this->budget_defini = $tab['budget_defini'];
                $this->montant_budget = htmlscperso(stripslashes($tab['montant_budget']), ENT_QUOTES);
                $this->rdv = $tab['rdv'];
                $this->concurrents_identifies = (int) $tab['concurrents_identifies'];
                $this->partenaires_identifies = (int) $tab['partenaires_identifies'];
                $this->concurrents = $tab['concurrents'];
                $this->partenaires = $tab['partenaires'];
                $this->risque_proposition = $tab['risque_proposition'];
                $this->risque_projet = $tab['risque_projet'];
                $this->potentiel = (int) $tab['potentiel'];
                $this->niveau = $tab['niveau'];
                $this->pourcentage_reussite = $tab['pourcentage_reussite'];
                $this->Id_affaire = (int) $tab['Id_affaire'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire  de creation / modification de l'analyse des risques
     *
     * @return string	   
     */
    public function riskForm($Id_pole=null) {
        if ($Id_pole == 3) {
            $html = '<div class="center">
						Pourcentage de réussite : <input type="text" name="pourcentage_reussite" value="' . $this->pourcentage_reussite . '" size="3"> %
					</div> ';
        } else {
            $risque[$this->niveau] = 'checked="checked"';
            $html = '
				<div class="left">
					Risques liés à la réalistion de la proposition (investissement prévu / probabilité de gagner) <br />
					<textarea name="risque_proposition" rows="6" cols="50">' . $this->risque_proposition . '</textarea>
				</div>	
				<div class="left">	
					Risques liés à la réalistion du projet (technique, contexte, volume, compétence) <br />
					<textarea name="risque_projet" rows="6" cols="50">' . $this->risque_projet . '</textarea>
				</div>
				<div class="center">
					<br /><br />
					Niveau global de risque :
					Faible <input type="radio" name="niveau" ' . $risque['Faible'] . ' value="Faible">
					Moyen <input type="radio" name="niveau" ' . $risque['Moyen'] . ' value="Moyen">
					Critique <input type="radio" name="niveau" ' . $risque['Critique'] . ' value="Critique">
					Inacceptable <input type="radio" name="niveau" ' . $risque['Inacceptable'] . ' value="Inacceptable">
				</div>
';
        }
        $html .= '<input type="hidden" name="Id_analyse_risque" value="' . (int) $this->Id_analyse_risque . '">';
        return $html;
    }

    /**
     * Formulaire de creation / modification de l'analyse commerciale
     *
     * @return string	   
     */
    public function businessForm() {
        $cdc[$this->cdc] = 'checked="checked"';
        $decideur[$this->decideur] = 'checked="checked"';
        $budget[$this->budget_defini] = 'checked="checked"';
        $rdv[$this->rdv] = 'checked="checked"';
        $ci[$this->concurrents_identifies] = 'checked="checked"';
        $pi[$this->partenaires_identifies] = 'checked="checked"';
        $html = '
            <div class="left">
				Derniers projets réalisés avec le client (résultat et tarifs pratiqués)<br /><br />
                <textarea name="dernier_projet" rows="6" cols="50">' . $this->dernier_projet . '</textarea>
			</div>	
			<div class="right">
				Cahier des charges existant :
				' . YES . ' <input type="radio" name="cdc" ' . $cdc['1'] . ' value="1">
				' . NO . ' <input type="radio" name="cdc" ' . $cdc['0'] . ' value="0"><br /><br />
				Interlocuteurs = Décideurs :
				' . YES . ' <input type="radio" name="decideur" ' . $decideur['1'] . ' value="1">
				' . NO . ' <input type="radio" name="decideur" ' . $decideur['0'] . ' value="0"><br /><br />
				Enveloppe budgétaire :
				' . YES . ' <input type="radio" name="budget_defini" ' . $decideur['1'] . ' value="1">
				' . NO . ' <input type="radio" name="budget_defini" ' . $decideur['0'] . ' value="0">
				Montant :
		        <input type="text" name="montant_budget" value="' . $this->montant_budget . '" size="9" /> €<br /><br />
				RDV sur site effectué :
				' . YES . ' <input type="radio" name="rdv" ' . $rdv['1'] . ' value="1">
				' . NO . ' <input type="radio" name="rdv" ' . $rdv['0'] . ' value="0"><br /><br />
                Potentiel de signer :
			    <select name="potentiel">
				    <option value="">---</option>
                    ' . $this->getPotentialList() . '
                </select>
				&nbsp; (1:faible, 5:fort)
			</div>
			<div class="clearer"></div>
			<div class="left">
				Concurrents identifiés :
				' . YES . ' <input type="radio" name="concurrents_identifies" ' . $ci['1'] . ' value="1">
				' . NO . ' <input type="radio" name="concurrents_identifies" ' . $ci['0'] . ' value="0"><br /><br />
				Noms concurrents
                <textarea name="concurrents" rows="6" cols="50">' . $this->concurrents . '</textarea>
			</div>
			<div class="right">
				Partenaires identifiés :
				' . YES . ' <input type="radio" name="partenaires_identifies" ' . $pi['1'] . ' value="1">
				' . NO . ' <input type="radio" name="partenaires_identifies" ' . $pi['0'] . ' value="0"><br /><br />
				Noms partenaires
                <textarea name="partenaires" rows="6" cols="50">' . $this->partenaires . '</textarea>
            </div>
			<input type="hidden" name="Id_analyse_commerciale" value="' . (int) $this->Id_analyse_commerciale . '">
';
        return $html;
    }

    /**
     * Enregistre les données de l'analyse des risques dans la BDD
     */
    public function saveRiskAnalysis() {
        $db = connecter();
        $set = ' SET Id_analyse_risque = ' . mysql_real_escape_string((int) $this->Id_analyse_risque) . ', Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ',
		risque_proposition = "' . mysql_real_escape_string($this->risque_proposition) . '", risque_projet = "' . mysql_real_escape_string($this->risque_projet) . '" ,
		niveau = "' . mysql_real_escape_string($this->niveau) . '", pourcentage_reussite = "' . mysql_real_escape_string($this->pourcentage_reussite) . '"';
        if ($this->Id_analyse_risque) {
            $requete = 'UPDATE analyse_risque ' . $set . ' WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '';
        } else {
            $requete = 'INSERT INTO analyse_risque ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Enregistre les données de l'analyse commerciale dans la BDD
     */
    public function saveBusinessAnalysis() {
        $db = connecter();
        $set = ' SET Id_analyse_commerciale = ' . mysql_real_escape_string((int) $this->Id_analyse_commerciale) . ', Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ',
		dernier_projet = "' . mysql_real_escape_string($this->dernier_projet) . '", cdc = ' . mysql_real_escape_string((int) $this->cdc) . ' , decideur = ' . mysql_real_escape_string((int) $this->decideur) . ',
		budget_defini = ' . mysql_real_escape_string((int) $this->budget_defini) . ', montant_budget = ' . mysql_real_escape_string((float) $this->montant_budget) . ',
		rdv = ' . mysql_real_escape_string((int) $this->rdv) . ', concurrents_identifies = ' . mysql_real_escape_string((int) $this->concurrents_identifies) . ',
		partenaires_identifies = ' . mysql_real_escape_string((int) $this->partenaires_identifies) . ', concurrents = "' . mysql_real_escape_string($this->concurrents) . '",
		partenaires = "' . $this->partenaires . '", potentiel = "' . (int) $this->potentiel . '"';
        if ($this->Id_analyse_commerciale) {
            $requete = 'UPDATE analyse_commerciale ' . $set . ' WHERE Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . '';
        } else {
            $requete = 'INSERT INTO analyse_commerciale ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Consultation de l'analyse commerciale d'une affaire
     */
    public function businessAnalysisConsultation() {
        if ($this->budget_defini) {
            $montant = '| Montant : ' . $this->montant_budget . ' &euro;';
        }
        $html = '
			<h2>Analyse commerciale</h2>
            <div class="left">
				<h3>Derniers projets réalisés avec le client (résultat et tarifs pratiqués)</h3><br />
                ' . $this->dernier_projet . ' <br />
				Concurrents identifiés : ' . yesno($this->concurrents_identifies) . ' <br />
				' . $this->concurrents . '
				Partenaires identifiés : ' . yesno($this->partenaires_identifies) . ' <br />
				' . $this->partenaires . '
			</div>	
			<div class="right">
				Cahier des charges existant : ' . yesno($this->cdc) . ' <br />
				Interlocuteurs = Décideurs : ' . yesno($this->decideur) . ' <br />
				Enveloppe budgétaire : ' . yesno($this->budget_defini) . ' 
				' . $montant . ' <br />
				RDV sur site effectué : ' . yesno($this->rdv) . ' <br />
				Potentiel de signer (1:faible, 5:fort) : ' . (int) $this->potentiel . ' 
			</div>
';
        return $html;
    }

    /**
     * Consultation de l'analyse des risques d'une affaire
     */
    public function riskAnalysisConsultation($Id_pole=null) {
        if ($this->niveau) {
            $niveau = '| Niveau : ' . $this->niveau . '';
        }
        $html = '
			<h2>Analyse des risques ' . $niveau . '</h2>';

        if ($Id_pole == 3) {
            $html .= '<br/><br/><div class="center"> <b>Pourcentage de réussite : </b>';
            if ($this->pourcentage_reussite != '') {
                $html .= $this->pourcentage_reussite . ' %';
            } else {
                $html .= 'Non déteminé';
            }
            $html .= '</div><br/><br/>';
        } else {
            $html .='
				<div class="left">
					<h3>Risques liés à la réalistion de la proposition (investissement prévu / probabilité de gagner)</h3><br />
					' . $this->risque_proposition . '
				</div>
				<div class="right">
					<h3>Risques liés à la réalistion du projet (technique, contexte, volume, compétence)</h3><br />
					' . $this->risque_projet . '
				</div>
';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les potentiels de signer
     *
     * @return string	
     */
    public function getPotentialList() {
        $potentiel[$this->potentiel] = 'selected="selected"';
        $i = 1;
        while ($i < 6) {
            $html .= '<option value=' . $i . ' ' . $potentiel[$i] . '>' . $i . '</option>';
            ++$i;
        }
        return $html;
    }

}

?>
