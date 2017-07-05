<?php

/**
 * Fichier Ressource.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Ressource
 */
class Ovialis_Ressource extends Ressource {
/**
     * Formulaire de création / modification d'une ressource
     *
     * @return string
     */
    public function form() {
        $profil = new Profil($this->Id_profil, array());
        $cursus = new Cursus($this->Id_cursus, array());
        $expinfo = new Experience($this->Id_exp_info, array());
        $pays_residence = new Pays($this->Id_pays_residence);
        $origine[$this->origine] = 'checked="checked"';
        $civ[$this->civilite] = 'checked="checked"';
        $th[$this->th] = 'checked="checked"';
        $specialite = new Specialite('', '');
        $html = '
			<h2>INFORMATIONS CANDIDAT</h2>
			<div class="left">
                        <span class="infoFormulaire"> ** </span>
			    ' . MISS . ' <input type="radio" name="civilite_ressource" ' . $civ['Melle'] . ' value="Melle" />
			    ' . MADAM . ' <input type="radio" name="civilite_ressource" ' . $civ['Mme'] . ' value="Mme" />
				' . MISTER . ' <input type="radio" name="civilite_ressource" ' . $civ['M'] . ' value="M" /><br /><br />
				<span class="infoFormulaire"> * </span> Nom :
		        <input type="text" id="nom_ressource" name="nom_ressource" value="' . $this->nom . '" />
				<br /><br />
				<span class="infoFormulaire"> * </span> Prénom : 
		        <input type="text" id="prenom_ressource" name="prenom_ressource" value="' . $this->prenom . '" onkeyup="homonyme()" />
				<br /><div id="homonyme"> </div><br />
				<span class="infoFormulaire"> ** </span> Adresse : 
		        <input type="text" id="adresse" name="adresse_ressource" value="' . $this->adresse . '" size="50" /><br /><br />
				Code Postal : 
		        <input type="text" name="cp_ressource" value="' . $this->code_postal . '" size="5" /><br /><br />
				Ville : 
		        <input type="text" name="ville_ressource" value="' . $this->ville . '" size="30" /><br /><br />
				Pays de résidence:
				<select name="Id_pays_residence">
                    <option value="">' . COUNTRY_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $pays_residence->getList() . '
                </select>
			    <br /><br />
				<span class="infoFormulaire"> (*) </span> Tél fixe : 
		        <input type="text" id="tel_fixe" name="tel_fixe_ressource" value="' . $this->tel_fixe . '" size="10" /><br /><br />
				<span class="infoFormulaire"> (*) </span> Tél portable : 
		        <input type="text" id="tel_portable" name="tel_portable_ressource" value="' . $this->tel_portable . '" size="10" /><br /><br />
				Mail :
		        <input type="text" id="mail" name="mail_ressource" value="' . $this->mail . '" size="30" />
			</div>
            <div class="right">
				<span class="infoFormulaire"> * </span> Profil :
				<select id="profil" name="Id_profil">
                    <option value="">' . PROFIL_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $profil->getList() . '
                </select><br /><br />
				Spécialité : <br />Veuillez appuyer sur CTRL pour en sélectionner plusieurs<br /><br />
				<select id="Id_specialite" name="Id_specialite[]" multiple size="13">
                    <option value="">' . SPECIALTY_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $specialite->getList($this->Id_ressource) . '
                </select><br /><br />
				Cursus :
				<select id="Id_cursus" name="Id_cursus">
                    <option value="">' . CURSUS_SELECT . '</option>
                    <option value="">----------------------------</option>
			        ' . $cursus->getList() . '
                </select><br /><br />
                            Expérience Informatique : 
			    <select id="Id_exp_info" name="Id_exp_info">
                    <option value="">' . DURATION_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $expinfo->getList() . '
                </select>
				<br /><br />
				<span class="infoFormulaire"> ** </span>
                                Travailleur Handicapé : Oui<input type="radio" name="th" value="1" ' . $th[1] . ' />
				Non<input type="radio" name="th" value="0" ' . $th[0] . '  />
                <br /><br />
			</div>
			<input type="hidden" name="Id_ressource" value="' . $this->Id_ressource . '" />
			<input type="hidden" name="origine_ressource" value="Candidat" />
';
        return $html;
    }
}

?>