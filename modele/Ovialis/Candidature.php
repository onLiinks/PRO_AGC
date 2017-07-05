<?php

class Ovialis_Candidature extends Candidature {
    /**
     * Formulaire de création / modification d'une candidature
     *
     * @return string
     */
    public function form() {
        $ressource = new Ovialis_Ressource($this->Id_ressource, array());
        $staff[$this->staff] = 'checked="checked"';
        if ($this->Id_candidature) {
            $htmlH = $this->history(1);
            $htmlHAction = $this->actionHistory(1);
            if ($this->lien_cv) {
                $hmtlCv = "CV actuel : <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cv . "'>" . $this->lien_cv . "</a><br /><br />";
            }
            if ($this->lien_cvp) {
                $hmtlCvp = "CV actuel Groupe Proservia : <a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $this->lien_cvp . "'>" . $this->lien_cvp . "</a><br /><br />";
            }
            if ($this->lien_lm) {
                $hmtlLm = "Lettre de motivation actuelle : <a href='../membre/index.php?a=ouvrirCV&cv=" . LM_DIR . $this->lien_lm . "'>" . $this->lien_lm . "</a><br /><br />";
            }
            if ($this->Id_etat == 6) {
                $htmlTV = $this->typeValidation();
            }
            if ($this->Id_etat == 8 || $this->Id_etat == 9) {
                $htmlTV = $this->typeEmbauche();
            }
            $htmlEnt = '<a href="index.php?a=creer_entretien&Id_candidature=' . $this->Id_candidature . '">Feuille Entretien</a>';

            if ($this->isCvweb()) {
                $htmlModif = ' | <a href="javascript:ouvre_popup(\'index.php?a=modifCvweb&Id_candidature=' . $this->Id_candidature . '\')">Modifications CVweb (' . $this->nbCvWebModification() . ')</a>';
                $htmlPostulation = ' | <a href="javascript:ouvre_popup(\'index.php?a=postulationCvweb&Id_cvweb=' . $this->isCvweb() . '\')">Postulations CVweb (' . $this->nbCvWebApply() . ')</a>';
            }
            $htmlPositionnement = Ressource::getPositioning($this->Id_ressource, 1);
        }
        $html = '
		    <h2>' . $htmlEnt . '
			' . $htmlModif . '
			' . $htmlPostulation . '</h2>
			<form name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_candidature" method="post" class="serialize">
                <div class="submit">
				    <input type="hidden" name="Id" id="Id_candidature" value="' . $this->Id_candidature . '" />
	                <input type="submit" value="' . SAVE_BUTTON . '" onclick="noPrompt();return verifFormOvialis(this.form)" />
			        <br /><br />
					<span class="infoFormulaire"> * </span> : ' . FORCED_FIELD . ' <br />
					<span class="infoFormulaire"> ** </span> : ' . HIRE_FORCED_FIELD . '
		        </div>
				' . $ressource->form() . '
				<div class="clearer"><br /><hr /></div>
				<div class="left">
					Date de saisie de la candidature :
		            <input type="text" id="date" name="date" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->date) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
					Date de réponse :
		            <input type="text" name="date_reponse" onfocus="showCalendarControl(this)" value="' . FormatageDate($this->date_reponse) . '" size="8" /><span class="infoFormulaire"> (jj-mm-aaaa)</span><br /><br />
				    Nature de la candidature : 
				    <select id="nature" name="Id_nature">
                        <option value="">' . NATURE_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getNatureListe() . '
				    </select>
				    <br /><br />
				    <span class="infoFormulaire"> * </span>Etat de la candidature : 
				    <select id="etat" name="Id_etat" onchange="updateEtatCandidature(this.value)">
                        <option value="">' . STATE_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getEtatListe() . '
				    </select>
                                    <br/><br />
                                        Agence de rattachement : 
                                        <select name="agence_souhaitee">
                                            <option value="">Sélectionner une agence de rattachement</option>
                                            <option value="">--------------------------------------------------------------------</option>
                                            ' . Agence::getSelectList($this->agence_souhaitee) . '<br/>
                                        </select>
				    <br /><br />
					<div id="typeval">' . $htmlTV . '</div>
					<div id="historique">' . $htmlH . '</div>
				    Action menée : 
				    <select name="Id_action_mener" onchange="updateActionCandidature(this.value)">
                        <option value="">' . ACTION_SELECT . '</option>
                        <option value="">----------------------------</option>
			            ' . $this->getActionMener() . '
				    </select>
				    <br /><br />					
				    Staff :
					' . YES . ' <input type="radio" name="staff" ' . $staff['1'] . ' value="1" />
				    ' . NO . ' <input type="radio" name="staff" ' . $staff['0'] . ' value="0" /><br /><br />
				    Type de contrats recherchés :
					<br />Veuillez appuyer sur CTRL pour en sélectionner plusieurs<br /><br />
				    <select name="type_contrat[]" multiple size="5">
                        <option value="">Sélectionner un type de contrat</option>
                        <option value="">----------------------------</option>
			            ' . self::getSearchContractTypeForm($this->Id_candidature) . '
                    </select>
			    </div>
				<div class="right">
					' . $hmtlCv . '
					<input type="hidden" id="cv_actuel" value="' . $this->lien_cv . '" />
					CV :
                    <input type="hidden" name="MAX_FILE_SIZE" value="6000000">
                    <input id="cv" name="cv" type="file"> (< 5 Mo )<br /><hr /><br />
                    ' . $hmtlLm . '
					Lettre de Motivation :
					<input name="lm" type="file"> (< 5 Mo )<br /><hr /><br />
                    ' . $hmtlCvp . '
					CV Groupe Proservia : 
                    <input name="cvp" type="file"> (< 5 Mo )<br /><hr /><br />
				    Commentaire : <br />
                    <textarea name="commentaire">' . $this->commentaire . '</textarea><br/><br/><br/><br/>
                    <div id="historique_action">' . $htmlHAction . '</div><br />
					<div id="historique_positionnement">' . $htmlPositionnement . '</div>
				</div>
				<div class="clearer"><br /><hr /></div>
				' . $ressource->hiringForm() . '
				<div class="clearer"><br /><hr /><br /></div>
			    <div class="submit">
				    <input type="hidden" name="class" value="' . __CLASS__ . '" />
	                <input type="submit" value="' . SAVE_BUTTON . '" onclick="noPrompt();return verifFormOvialis(this.form)" />
		        </div>
		    </form>
';
        return $html;
    }
    
    /**
     * Affichage du formulaire type d'embauche du candidat
     *
     * @return string
     */
    public function typeEmbauche() {
        $tp[$this->type_embauche] = 'checked="checked"';
        $html = '
		Embauché sur :
		Profil <input type="radio" name="type_embauche_etat" ' . $tp['profil'] . ' value="profil" />
		Mission <input type="radio" name="type_embauche_etat" ' . $tp['mission'] . ' value="mission" /><br /><br />
';
        return $html;
    }
}

?>
