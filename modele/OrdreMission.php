<?php

/**
 * Fichier OrdreMission.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe OrdreMission
 */
class OrdreMission {
    /**
     * Identifiant de l'ordre de mission
     *
     * @access private
     * @var int 
     */
    private $Id_ordre_mission;
    /**
     * Créateur de l'ordre de mission
     *
     * @access private
     * @var string 
     */
    public $createur;
    /**
     * Date de création de l'ordre de mission
     *
     * @access private
     * @var date 
     */
    private $date_creation;
    /**
     * Identifiant de la ressource
     *
     * @access private
     * @var string 
     */
    public $Id_ressource;
    /**
     * Profil de la ressource
     *
     * @access private
     * @var string 
     */
    private $profil;
    /**
     * Identifiant du compte
     *
     * @access private
     * @var string 
     */
    private $Id_compte;
    /**
     * Téléphone du compte
     *
     * @access private
     * @var string 
     */
    private $telephone;
    /**
     * Lieu de la mission
     *
     * @access private
     * @var string 
     */
    private $lieu_mission;
    /**
     * Moyen d'accès
     *
     * @access private
     * @var string 
     */
    private $moyen_acces;
    /**
     * Date de début
     *
     * @access private
     * @var date 
     */
    private $date_debut;
    /**
     * Date de fin
     *
     * @access private
     * @var date 
     */
    private $date_fin;
    /**
     * Durée
     *
     * @access private
     * @var double 
     */
    private $duree;
    /**
     * Contact
     *
     * @access private
     * @var string 
     */
    private $contact;
    /**
     * Responsable 
     *
     * @access private
     * @var string 
     */
    public $responsable;
    /**
     * Tâches
     *
     * @access private
     * @var string 
     */
    private $tache;
    /**
     * Frais
     *
     * @access private
     * @var string 
     */
    private $frais;
    /**
     * Horaire
     *
     * @access private
     * @var string 
     */
    private $horaire;
    /**
     * Commentaire horaire
     *
     * @access private
     * @var string 
     */
    private $commentaire_horaire;
    /**
     * Astreinte (oui ou non)
     *
     * @access private
     * @var string 
     */
    private $astreinte;
    /**
     * Commentaire astreinte
     *
     * @access private
     * @var string 
     */
    private $commentaire_astreinte;
    /**
     * Indemnité de repas
     *
     * @access private
     * @var string 
     */
    private $indemnites_repas;
    /**
     * Agence de l'ordre de mission
     *
     * @access private
     * @var string 
     */
    private $Id_agence;
    /**
     * Ordre de mission envoyé
     *
     * @access private
     * @var int 
     */
    private $envoye;
    /**
     * Date à laquelle l'odre de mission a été envoyé
     *
     * @access private
     * @var date 
     */
    private $date_envoi;
    /**
     * Ordre de mission retourné
     *
     * @access private
     * @var int 
     */
    private $retour;
    /**
     * Date à laquelle l'odre de mission a été retourné
     *
     * @access private
     * @var date 
     */
    private $date_retour;
    /**
     * Identifiant du contrat délégation
     *
     * @access private
     * @var int 
     */
    private $Id_cd;

    /**
     * Code otp
     *
     * @access private
     * @var int 
     */
    private $code_otp;
    
    /**
     * Constructeur de la classe OrdreMission
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'ordre de mission
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_ordre_mission = '';
                $this->createur = '';
                $this->date_creation = '';
                $this->Id_ressource = '';
                $this->profil = '';
                $this->Id_compte = '';
                $this->telephone = '';
                $this->lieu_mission = '';
                $this->moyen_acces = '';
                $this->date_debut = '';
                $this->date_fin = '';
                $this->duree = '';
                $this->contact = '';
                $this->responsable = '';
                $this->tache = '';
                $this->frais = '';
                $this->horaire = '';
                $this->commentaire_horaire = '';
                $this->astreinte = '';
                $this->commentaire_astreinte = '';
                $this->indemnites_repas = '';
                $this->Id_agence = '';
                $this->envoye = '';
                $this->date_envoi = '';
                $this->retour = '';
                $this->date_retour = '';
                $this->Id_cd = '';
                $this->code_otp = '';
                $this->equipement_securite_a_prevoir = '';
                $this->formations_specifiques_exigees = '';
                $this->smr = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_ordre_mission = '';
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->date_creation = DATETIME;
                $this->Id_cd = (int) $tab['Id_cd'];
                $this->envoye = (int) $tab['envoye'];
                $this->retour = (int) $tab['retour'];

                if (count($tab) == 2) {
                    $cd = new ContratDelegation($this->Id_cd, array());
                    $ressource = RessourceFactory::create($cd->type_ressource, $cd->Id_ressource, null);
                    $this->Id_ressource = Salarie::getIdBySocialInsuranceNumber($ressource->securite_sociale);
                    if($cd->type_ressource == 'CAN_TAL')
                        $this->profil = $ressource->profil;
                    else 
                        $this->profil = Profil::getLibelle($ressource->getIdProfil());
                    
                    if(is_numeric($cd->Id_affaire)) {
                        $this->Id_compte = Affaire::getIdCompte($cd->Id_affaire);
                        $this->Id_agence = Affaire::getIdAgence($cd->Id_affaire);
                    }
                    else {
                        $affaire = new Opportunite($cd->Id_affaire, array());
                        $this->Id_compte = $affaire->Id_compte;
                        if ($cd->agence_mission != null && $cd->agence_mission != '') {
                            $this->Id_agence = Agence::getIdAgence($cd->agence_mission);
                        } else {
                            $this->Id_agence = Agence::getIdAgence($affaire->Id_agence);
                        }
                    }
                    $compte = CompteFactory::create(null, $this->Id_compte);
                    $this->telephone = $compte->tel;
                    $this->lieu_mission = $cd->lieu_mission;
                    $this->moyen_acces = $cd->moyen_acces;
                    $this->date_debut = $cd->debut_mission;
                    $this->date_fin = $cd->fin_mission;
                    $this->duree = $cd->duree_mission;
                    $this->contact = $cd->contact1;
                    $this->responsable = $cd->createur;
                    $this->tache = $cd->tache;
                    $this->horaire = $cd->horaire;
                    $this->commentaire_horaire = $cd->commentaire_horaire;
                    $this->astreinte = $cd->astreinte;
                    $this->commentaire_astreinte = $cd->commentaire_astreinte;
                    $this->code_otp = $cd->code_otp;
                    
                    $this->equipement_securite_a_prevoir = $cd->equipement_securite_a_prevoir;
                    $this->formations_specifiques_exigees = $cd->formations_specifiques_exigees;
                    $this->smr = $cd->smr;
                    
                    foreach($cd->indemnite as $indemnite) {
                        $cond = $plafond = '';
                        if($indemnite['type'] == 10 || $indemnite['type'] == 12) {
                            if($indemnite['condition'] != '') $cond = ' ('. $indemnite['condition'] .')';
                            if($indemnite['plafond'] != 0) $plafond = '  Plafond : '. $indemnite['plafond'] .' km&#10;';
                            $this->frais .= '- ' . $indemnite['nom'] . ' ' . $cond . '&#10;' . $plafond;
                        }
                        elseif($indemnite['type'] == 11) {
                            $this->indemnites_repas .= '- ' . $indemnite['nom'] . '&#10;';
                        }
                    }
                    $this->frais .= $cd->commentaire_indemnite;
                    if($this->indemnites_repas=='')
                        $this->indemnites_repas = LUNCHEON_VOUCHER;
                } else {
                    $this->Id_ressource = $tab['Id_ressource'];
                    $this->profil = $tab['profil'];
                    $this->Id_compte = $tab['Id_compte'];
                    $this->Id_agence = $tab['Id_agence'];
                    $this->telephone = $tab['telephone'];
                    $this->lieu_mission = $tab['lieu_mission'];
                    $this->moyen_acces = $tab['moyen_acces'];
                    $this->date_debut = $tab['date_debut'];
                    $this->date_fin = $tab['date_fin'];
                    $this->duree = $tab['duree'];
                    $this->contact = $tab['contact'];
                    $this->responsable = $tab['responsable'];
                    $this->tache = $tab['tache'];
                    $this->frais = $tab['frais'];
                    $this->horaire = $tab['horaire'];
                    $this->commentaire_horaire = $tab['commentaire_horaire'];
                    $this->astreinte = $tab['astreinte'];
                    $this->commentaire_astreinte = $tab['commentaire_astreinte'];
                    $this->indemnites_repas = $tab['indemnites_repas'];
                    $this->code_otp = $tab['code_otp'];
                    $this->equipement_securite_a_prevoir = $tab['equipement_securite_a_prevoir'];
                    $this->formations_specifiques_exigees = $tab['formations_specifiques_exigees'];
                    $this->smr = $tab['smr'];
                }
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM ordre_mission WHERE Id_ordre_mission =' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_ordre_mission = $code;
                $this->createur = $ligne->createur;
                $this->date_creation = $ligne->date_creation;
                $this->Id_ressource = $ligne->id_ressource;
                $this->profil = $ligne->profil;
                $this->Id_compte = $ligne->id_compte;
                $this->telephone = $ligne->telephone;
                $this->lieu_mission = $ligne->lieu_mission;
                $this->moyen_acces = $ligne->moyen_acces;
                $this->date_debut = FormatageDate($ligne->date_debut);
                $this->date_fin = FormatageDate($ligne->date_fin);
                $this->duree = $ligne->duree;
                $this->contact = $ligne->contact;
                $this->responsable = $ligne->responsable;
                $this->tache = $ligne->tache;
                $this->frais = $ligne->frais;
                $this->horaire = $ligne->horaire;
                $this->commentaire_horaire = $ligne->commentaire_horaire;
                $this->astreinte = $ligne->astreinte;
                $this->commentaire_astreinte = $ligne->commentaire_astreinte;
                $this->indemnites_repas = $ligne->indemnites_repas;
                $this->Id_agence = $ligne->id_agence;
                $this->envoye = $ligne->envoye;
                $this->retour = $ligne->retour;
                $this->Id_cd = $ligne->id_cd;
                $this->date_envoi = FormatageDate($ligne->date_envoi);
                $this->date_retour = FormatageDate($ligne->date_retour);
                $this->code_otp = $ligne->code_otp;
                $this->equipement_securite_a_prevoir = $ligne->equipement_securite_a_prevoir;
                $this->formations_specifiques_exigees = $ligne->formations_specifiques_exigees;
                $this->smr = $ligne->smr;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_ordre_mission = $code;
                $this->Id_ressource = $tab['Id_ressource'];
                $this->profil = $tab['profil'];
                $this->Id_agence = $tab['Id_agence'];
                $this->Id_compte = $tab['Id_compte'];
                $this->telephone = $tab['telephone'];
                $this->lieu_mission = $tab['lieu_mission'];
                $this->moyen_acces = $tab['moyen_acces'];
                $this->date_debut = $tab['date_debut'];
                $this->date_fin = $tab['date_fin'];
                $this->duree = $tab['duree'];
                $this->contact = $tab['contact'];
                $this->responsable = $tab['responsable'];
                $this->tache = $tab['tache'];
                $this->frais = $tab['frais'];
                $this->horaire = $tab['horaire'];
                $this->commentaire_horaire = $tab['commentaire_horaire'];
                $this->astreinte = $tab['astreinte'];
                $this->commentaire_astreinte = $tab['commentaire_astreinte'];
                $this->indemnites_repas = $tab['indemnites_repas'];
                $this->envoye = (int) $tab['envoye'];
                $this->retour = (int) $tab['retour'];
                $this->Id_cd = (int) $tab['Id_cd'];
                $this->date_envoi = $tab['date_envoi'];
                $this->date_retour = $tab['date_retour'];
                $this->code_otp = $tab['code_otp'];
                $this->equipement_securite_a_prevoir = $tab['equipement_securite_a_prevoir'];
                $this->formations_specifiques_exigees = $tab['formations_specifiques_exigees'];
                $this->smr = $tab['smr'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un ordre de mission
     *
     * @return string	   
     */
    public function form() {
        $compte = CompteFactory::create(null, $this->Id_compte);
        if(get_class($compte) == 'CompteSF') {
            $c = $compte->nom . ' | ' . $compte->code_postal . '<input id="Id_compte" name="Id_compte" type="hidden" value="' . $this->Id_compte . '">';
        }
        else if(get_class($compte) == 'CompteCegid') {
            $c = '<select id="Id_compte" name="Id_compte">
                    <option value="">' . CUSTOMERS_SELECT . '</option>
				    <option value="">-------------------------</option>
                    ' . $compte->getList() . '
                </select>';
        }
        $salarie = new Salarie($this->Id_ressource, array());
        if ($salarie->societe == 'COPIE_ARKES') {
                $salarie->societe = 'PROSERVIA';
        }
        $resproservia = new Utilisateur($this->responsable, array());
        if(($this->date_creation <= '2012-12-21' && $this->Id_ordre_mission) || $_SESSION['societe'] == 'OVIALIS') {
            $ma[$this->moyen_acces] = 'selected="selected"';
            $moyen_acces = '<span class="infoFormulaire"> * </span>Moyen d\'accès utilisé :
			    <select id="moyen_acces" name="moyen_acces">
				    <option value="">--------------------</option>
				    <option value="Personnel" ' . $ma['Personnel'] . '>Personnel</option>
					<option value="Véhicule Groupe Proservia" ' . $ma['Véhicule Groupe Proservia'] . '>Véhicule Groupe Proservia</option>
					<option value="Location" ' . $ma['Location'] . '>Location</option>
					<option value="Train" ' . $ma['Train'] . '>Train</option>
					<option value="Avion" ' . $ma['Avion'] . '>Avion</option>
					<option value="Transport En Commun" ' . $ma['Transport En Commun'] . '>Transport En Commun</option>
				</select>
				<br /><br />';
        }
        
        if($this->Id_cd != null) {
            if ($salarie->statut == 'CADRE') {
                $horaires = self::contractStatusTimeMsg($salarie->statut, $this->horaire, $salarie->societe) . '<input id="horaire" name="horaire" type="hidden" value="' . $this->horaire . '">';
                $this->commentaire_horaire = $this->horaire . ' ' . $this->commentaire_horaire;
            } elseif ($salarie->statut == 'ETAM') {
                $horaires = self::contractStatusTimeMsg($salarie->statut, $this->horaire, $salarie->societe) . '<input id="horaire" name="horaire" type="hidden" value="' . $this->horaire . '">';
            }
        }
        else {
            $horaire[$this->horaire] = 'selected="selected"';
            $horaires = 'Horaires :
                        <select id="horaire" name="horaire">
                            <option value="">--------------------</option>
                            <option value="35 h" ' . $horaire['35 h'] . '>35 h</option>
                            <option value="35.5 h" ' . $horaire['35.5 h'] . '>35.5 h</option>
                            <option value="36 h" ' . $horaire['36 h'] . '>36 h</option>
                            <option value="36.5 h" ' . $horaire['36.5 h'] . '>36.5 h</option>
                            <option value="37 h" ' . $horaire['37 h'] . '>37 h</option>
                            <option value="37.5 h" ' . $horaire['37.5 h'] . '>37.5 h</option>
                            <option value="38 h" ' . $horaire['38 h'] . '>38 h</option>
                            <option value="38.5 h" ' . $horaire['38.5 h'] . '>38.5 h</option>
                            <option value="39 h" ' . $horaire['39 h'] . '>39 h</option>
                            <option value="39.5 h" ' . $horaire['39.5 h'] . '>39.55 h</option>
                            <option value="40 h" ' . $horaire['40 h'] . '>40 h</option>
                            <option value="Autre" ' . $horaire['Autre'] . '>Autre</option>
                        </select>';
        }
        if($this->equipement_securite_a_prevoir || $this->formations_specifiques_exigees || $this->smr) {
            $politique_secu = '
                Equipement de sécurité à prévoir : ' . $this->equipement_securite_a_prevoir . '<input type="hidden" id="equipement_securite_a_prevoir" name="equipement_securite_a_prevoir" value="' . $this->equipement_securite_a_prevoir . '" /><br /><br />
                Formations spécifiques exigées : ' . $this->formations_specifiques_exigees . '<input type="hidden" id="formations_specifiques_exigees" name="formations_specifiques_exigees" value="' . $this->formations_specifiques_exigees . '" /><br /><br />
                Suivi Médical Renforcé à prévoir : ' . $this->smr . '<input type="hidden" id="smr" name="smr" value="' . $this->smr . '" />';
        }
        
        $html = '
        <form name="formulaire" enctype="multipart/form-data" action="../fac/index.php?a=enregistrerODM" method="post">
			<div class="submit">
			    <input type="hidden" name="Id" value="' . (int) $this->Id_ordre_mission . '" />
				<input type="hidden" name="Id_cd" value="' . (int) $this->Id_cd . '" />
				<input type="hidden" name="Id_agence" value="' . $this->Id_agence . '" />
                                <input type="hidden" name="code_otp" value="' . $this->code_otp . '" />
				<button type="submit" class="button save" value="' . SAVE_BUTTON . '" onclick="return verifODM(this.form)">' . SAVE_BUTTON . '</button>
		    </div>
			<div class="left">
				<span class="infoFormulaire"> * </span> 
				Ressource : 
				<select id="Id_ressource" name="Id_ressource" onchange="new Ajax.Updater(\'horaire\', \'../source/index.php?a=updateODMTime\', {method: \'get\', evalScripts: true, parameters: \'&Id_ressource=\'+this.value+\'&horaire=' . $this->horaire . '\', onlyLatestOfClass:getFunctionName(arguments.callee.toString())});" >
					<option value="">' . RESSOURCE_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $salarie->getEmployeeList() . '
                </select>
				<br /><br />
				Profil : <input type="text" name="profil" value="' . $this->profil . '" size="40"/>
				<br /><br />
				<span class="infoFormulaire"> * </span> Client : ' . $c . '
                </select>
				<br /><br />
				<span class="infoFormulaire"> * </span> 
				Lieu des interventions : <input type="text" id="lieu_mission" name="lieu_mission" value="' . $this->lieu_mission . '" size="40" /><br /><br />
				Téléphone : <input type="text" name="telephone" value="' . $this->telephone . '" /><br /><br />
				' . $moyen_acces . '
				<span class="infoFormulaire"> * </span> 
				Date de début : <input type="text" id="date_debut" name="date_debut" value="' . $this->date_debut . '" onfocus="showCalendarControl(this)" size="8" /><br /><br />
				<span class="infoFormulaire"> * </span> 
				Date de fin : <input type="text" id="date_fin" name="date_fin" value="' . $this->date_fin . '" onfocus="showCalendarControl(this)" size="8" /><br /><br />
				<span class="infoFormulaire"> * </span> 
				Durée estimée : <input type="text" id="duree" name="duree" value="' . $this->duree . '" size="4" /> (jours) <br /><br />
				<span class="infoFormulaire"> * </span> 
				Interlocuteur client : <input type="text" id="contact" name="contact" value="' . $this->contact . '" size="30" /><br /><br />
				<span class="infoFormulaire"> * </span> 
				Responsable ' . $salarie->societe . ' : 
				<select id="responsable" name="responsable">
					<option value="">' . RESSOURCE_SELECT . '</option>
					<option value="">-------------------------</option>
					' . $resproservia->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resproservia->getList('OP') . '
                </select>
				<br /><br />
				<span class="infoFormulaire"> * </span> 
			    Description des tâches : <br />
				<textarea name="tache" >' . $this->tache . '</textarea>
				<br /><br />
                                ' . $politique_secu . '
			</div>
			<div class="right">
				Frais de déplacements et de séjours : <br />
				<textarea name="frais" >' . $this->frais . '</textarea>
				<br /><br />
                <span id="horaire">' . $horaires . '</span>
                <br /><br />
				Commentaire horaire : <br />
				<textarea name="commentaire_horaire" >' . $this->commentaire_horaire . '</textarea>
                <br /><br />
				Astreinte : ' . $this->astreinte . '<input type="hidden" id="astreinte" name="astreinte" value="' . $this->astreinte . '" />
                <br /><br />
				Commentaire astreinte : <br />
                <textarea name="commentaire_astreinte" >' . $this->commentaire_astreinte . '</textarea>
				<br /><br />				
			    Indemnités repas : <br />
				<textarea name="indemnites_repas" >' . $this->indemnites_repas . '</textarea>
			</div>
			<div class="submit">
                        <button type="submit" class="button save" value="' . SAVE_BUTTON . '" onclick="return verifODM(this.form)">' . SAVE_BUTTON . '</button>';
        if ($this->envoye == 1) {
            $html .= '<input type="hidden"  name="envoye" value="0">';
        }
        $html .= '
		    </div>
		</form>
';
        return $html;
    }

    /**
     * Recherche d'un ordre de mission
     *
     * @param int Identifiant de l'ordre de mission
     * @param string Créateur de l'ordre de mission
     * @param string Identifiant de la ressource de l'ordre de mission
     * @param date Date antérieure à la date de création de l'ordre de mission
     * @param date Date postérieure à la date de création de l'ordre de mission
     * @param string Identifiant de l'agence de la ressource de l'ordre de mission
     * @param string Identifiant du responsable de la ressource de l'ordre de mission
     * @param string Identifiant du compte client de l'ordre de mission	  
     *
     * @return string
     */
    public static function search($Id_odm, $createur, $Id_ressource, $debut, $fin, $Id_agence, $responsable, $Id_compte, $output = array('type' => 'TABLE'), $finishing = false) {
        $arguments = array('Id_odm', 'createur', 'Id_ressource', 'debut', 'fin', 'Id_agence', 'responsable', 'Id_compte', 'output');
        $columns = array(array('Id','Id_ordre_mission'), array('Ressource','none'), array('Date création','date_creation'),array('Date début','date_debut'),array('Date fin','date_fin'),
                         array('Créateur','createur'),array('Agence','agence'),array('Resp. Proservia','responsable'), array('Client','none'),
                         array('Envoyé','date_envoi'), array('Retour','date_retour'));
        $db = connecter();
        if ($Id_compte)
            $innerCompte = 'INNER JOIN contrat_delegation cd ON odm.Id_cd = cd.Id_contrat_delegation';
        $requete = 'SELECT odm.Id_ordre_mission, odm.Id_ressource, odm.Id_cd, odm.date_creation, odm.createur, a.libelle AS agence,
                            odm.Id_compte, odm.date_envoi, odm.date_retour, odm.responsable, odm.retour, odm.envoye, 
                            DATE_FORMAT(odm.date_creation, "%d-%m-%Y") as date_creation_fr, odm.date_debut, odm.date_fin,
                            DATE_FORMAT(odm.date_debut, "%d-%m-%Y") as date_debut_fr,DATE_FORMAT(odm.date_fin, "%d-%m-%Y") as date_fin_fr
                    FROM ordre_mission odm
                    INNER JOIN agence a ON odm.Id_agence = a.Id_agence ' . $innerCompte . '
                    WHERE Id_ordre_mission != 0';

        if ($Id_odm) {
            $requete .= ' AND odm.Id_ordre_mission =' . (int) $Id_odm . '';
        }
        if ($createur) {
            $requete .= ' AND odm.createur ="' . $createur . '"';
        }
        if ($Id_ressource) {
            $requete .= ' AND odm.Id_ressource ="' . $Id_ressource . '"';
        }
        if ($debut && $fin) {
            $requete .= ' AND odm.date_creation BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        if ($Id_agence) {
            $requete .= ' AND odm.Id_agence LIKE "%' . $Id_agence . '%"';
        }
        if ($responsable) {
            $requete .= ' AND odm.responsable ="' . $responsable . '"';
        }
        if ($Id_compte) {
            $requete .= ' AND cd.compte LIKE "' . $Id_compte . '%"';
        }
        if ($finishing && $finishing != 'null') {
            $requete .= ' AND (date_fin > NOW()  AND date_fin < ADDDATE(NOW(), INTERVAL 31 DAY))';
        }
        
        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        }
        else {
            $paramsOrder .= 'orderBy=Id_ordre_mission';
            $orderBy = 'Id_ordre_mission';
        }
        if($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherODM({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterODM&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if($value[1] == $output['orderBy'])
                        if($output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!$output['orderBy']) {
                        $direction = 'ASC';
                        $img['Id_ordre_mission'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherODM({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_ordre_mission'] . '</td>
                            <td>' . self::showResource($ligne) . '</td>
                            <td>' . $ligne['date_creation_fr'] . '</td>
                            <td>' . $ligne['date_debut_fr'] . '</td>
                            <td>' . $ligne['date_fin_fr'] . '</td>
                            <td>' . $ligne['createur'] . '</td>
                            <td>' . $ligne['agence'] . '</td>
                            <td>' . $ligne['responsable'] . '</td>
                            <td>' . self::showCustomer($ligne) . '</td>
                            <td>' . self::showSendDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showReturnDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        }
        elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="ordre_mission.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo $ligne['id_ordre_mission'] . ';';
                echo '"' . self::showResource($ligne) . '";';
                echo $ligne['date_creation_fr'] . ';';
                echo $ligne['date_debut_fr'] . ';';
                echo $ligne['date_fin_fr'] . ';';
                echo '"' . $ligne['createur'] . '";';
                echo '"' . $ligne['agence'] . '";';
                echo '"' . $ligne['responsable'] . '";';
                echo '"' . self::showCustomer($ligne) . '";';
                echo self::showSendDate($ligne, array('csv' => true)) . ';';
                echo self::showReturnDate($ligne, array('csv' => true)) . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_ordre_mission = ' . mysql_real_escape_string((int) $this->Id_ordre_mission) . ', Id_ressource = "' . mysql_real_escape_string($this->Id_ressource) . '", profil = "' . mysql_real_escape_string($this->profil) . '"
		 , Id_compte = "' . mysql_real_escape_string($this->Id_compte) . '", telephone = "' . mysql_real_escape_string(formatTel($this->telephone)) . '", lieu_mission = "' . mysql_real_escape_string($this->lieu_mission) . '", 
		 moyen_acces = "' . mysql_real_escape_string($this->moyen_acces) . '", date_debut = "' . mysql_real_escape_string(DateMysqltoFr($this->date_debut, 'mysql')) . '", 
		 date_fin = "' . mysql_real_escape_string(DateMysqltoFr($this->date_fin, 'mysql')) . '", duree = "' . mysql_real_escape_string($this->duree) . '", contact = "' . mysql_real_escape_string($this->contact) . '", 
		 responsable = "' . mysql_real_escape_string($this->responsable) . '" , tache = "' . mysql_real_escape_string($this->tache) . '", frais = "' . mysql_real_escape_string($this->frais) . '",
                 horaire = "' . mysql_real_escape_string($this->horaire) . '", commentaire_horaire = "' . mysql_real_escape_string($this->commentaire_horaire) . '",
		 indemnites_repas = "' . mysql_real_escape_string($this->indemnites_repas) . '", Id_agence = "' . mysql_real_escape_string($this->Id_agence) . '", 
		 envoye = ' . mysql_real_escape_string((int) $this->envoye) . ', retour = ' . mysql_real_escape_string((int) $this->retour) . ', Id_cd = ' . mysql_real_escape_string((int) $this->Id_cd) . ',
                code_otp = "' . mysql_real_escape_string($this->code_otp) . '",         
                astreinte = "' . mysql_real_escape_string($this->astreinte) . '", commentaire_astreinte = "' . mysql_real_escape_string($this->commentaire_astreinte) . '",
                equipement_securite_a_prevoir="' . mysql_real_escape_string($this->equipement_securite_a_prevoir) . '",formations_specifiques_exigees="' . mysql_real_escape_string($this->formations_specifiques_exigees) . '",
                smr="' . mysql_real_escape_string($this->smr) . '"';

        if ($this->Id_ordre_mission) {
            $requete = 'UPDATE ordre_mission ' . $set . ' WHERE Id_ordre_mission = ' . mysql_real_escape_string((int) $this->Id_ordre_mission);
        } else {
            $requete = 'INSERT INTO ordre_mission ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation = "' . mysql_real_escape_string($this->date_creation) . '"';
        }
        $db->query($requete);
        
        if($this->Id_cd != 0) {
            $cd = new ContratDelegation($this->Id_cd, array());
            $cd->statut = 'V';
            $cd->save();
        }
    }

    /**
     * Editer l'ordre de mission en pdf
     * 
     * @param string Destination pour l'enregistrement du PDF 
     *                  - I = envoyer au navigateur via le plugin
     *                  - D = envoyer au navigateur en forçant l'enregistrement
     * 			- F = enregistre le PDF
     * 			- S = retourne le document en string
     */
    public function edit($serialize = false, $dest = 'I') {       
        $_SESSION['titre'] = ODM;
        $pdf = new FPDF_TABLE();
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetY(30);
        $this->tache = convert_smart_quotes(strip_tags(htmlenperso_decode($this->tache)));
        $this->frais = convert_smart_quotes(strip_tags(htmlenperso_decode($this->frais)));
        $this->indemnites_repas = convert_smart_quotes(strip_tags(htmlenperso_decode($this->indemnites_repas)));
        $this->horaire = convert_smart_quotes(strip_tags(htmlenperso_decode($this->horaire)));
        $this->commentaire_horaire = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_horaire)));
        $this->commentaire_astreinte = convert_smart_quotes(strip_tags(htmlenperso_decode($this->commentaire_astreinte)));
                
        $compte = CompteFactory::create(null, $this->Id_compte);
        $ressource = RessourceFactory::create('SAL', $this->Id_ressource, null);
        if ($ressource->societe == 'COPIE_ARKES') {
            $ressource->societe = 'PROSERVIA';
        }
        $pdf->SetStyle('t1', 'arial', 'B', 10, '70,110,165');
        $pdf->SetStyle('t2', 'arial', '', 8, '0,0,0');
        $pdf->SetStyle('t3', 'arial', '', 7, '70,110,165');
        $pdf->SetStyle('t4', 'arial', 'B', 10, '0,0,0');
        $pdf->setXY(124, 12);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCellTag(72, 2, "  n° {$this->Id_ordre_mission}", 0, 'C', 0);
        $pdf->setXY(100, 17);
        $pdf->SetTextColor(70, 110, 165);
        $pdf->SetFont('Arial', '', 7);
        
        if ($this->code_otp != '' && $this->code_otp != null) {
            $codeOptStr = ' | Code OTP : '.$this->code_otp;
        } else {
            $codeOptStr = '';
        }
        
        $pdf->MultiCellTag(105, 2, "Contrat délégation n° <a href='../membre/index.php?a=editerContratDelegation&Id={$this->Id_cd}'>{$this->Id_cd}</a>".$codeOptStr, 0, 'L', 0);
        $Id_affaire = ContratDelegation::getIdAffaire($this->Id_cd);
        if ($Id_affaire) {
            $affaireCEGID = Affaire::getIdAffaireCEGID($Id_affaire, DateMysqltoFr($this->date_debut, 'mysql'));
            $pdf->setXY(100, 21);
            $pdf->MultiCellTag(105, 2, "Opportunité n° <a href='../com/index.php?a=afficherAffaire&Id_affaire={$Id_affaire}'>{$Id_affaire}</a> | Affaire(s) CEGID / HQ {$affaireCEGID}", 0, 'L', 0);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->setLeftMargin(3);
        $pdf->setXY(10, 30);
        $pdf->MultiCellTag(190, 5, '<t1>' . ODM . '</t1>', 1, 'C', 0);
        $pdf->setXY(15, $pdf->GetY() + 2);
        $y = $pdf->GetY();
        $pdf->setLeftMargin(15);
        $pdf->MultiCellTag(70, 7, "<t2>Nom :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Prénom :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Qualification :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Client :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Lieu des interventions :</t2>", 0, 'L', 0);

        if ($pdf->GetStringWidth($this->lieu_mission) > 58) {
            $pdf->setY($pdf->GetY() + 7);
        }
        $pdf->MultiCellTag(70, 7, "<t2>Téléphone :</t2>", 0, 'L', 0);
        if(($this->date_creation <= '2012-12-21' && $this->Id_ordre_mission) || $_SESSION['societe'] == 'OVIALIS') {
            $pdf->MultiCellTag(70, 7, "<t2>Moyen d'accès :</t2>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(70, 7, "<t2>Date de début :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Date de fin :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Durée estimée :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Interlocuteur Client :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(70, 7, "<t2>Responsable " . $ressource->societe . " :</t2>", 0, 'L', 0);
        $pdf->MultiCellTag(180, 7, self::contractStatusTimeMsg($ressource->statut, $this->horaire, $ressource->societe), 0, 'L', 0);

        $pdf->setLeftMargin(100);
        $pdf->setY($y);
        $pdf->MultiCellTag(90, 7, "<t3>{$ressource->nom} </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>{$ressource->prenom} </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>" . $this->profil . " </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>" . $compte->nom . " </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(110, 7, "<t3>{$this->lieu_mission}</t3>", 0, 'L', 0);
        if ($pdf->GetStringWidth($this->lieu_mission) > 58) {
            $pdf->setY($pdf->GetY() + 7);
        }
        $pdf->MultiCellTag(90, 7, "<t3>{$this->telephone} </t3>", 0, 'L', 0);
        if(($this->date_creation <= '2012-12-21' && $this->Id_ordre_mission) || $_SESSION['societe'] == 'OVIALIS') {
            $pdf->MultiCellTag(90, 7, "<t3>{$this->moyen_acces} </t3>", 0, 'L', 0);
        }
        $pdf->MultiCellTag(90, 7, "<t3>" . $this->date_debut . " </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>" . $this->date_fin . " </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>{$this->duree} jours</t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>{$this->contact} </t3>", 0, 'L', 0);
        $pdf->MultiCellTag(90, 7, "<t3>" . Utilisateur::getName($this->responsable) . " </t3>", 0, 'L', 0);
        $pdf->setLeftMargin(15);

        if ($ressource->statut == 'ETAM') {
            $pdf->setY($pdf->GetY() + 14);
        } elseif ($ressource->statut == 'CADRE') {
            $pdf->setY($pdf->GetY() + 7);
        }

        if($this->commentaire_horaire) {
            $yf = $pdf->GetY();
            $pdf->MultiCellTag(170, 7, "<t2>Commentaire horaires :</t2>\n<t3>{$this->commentaire_horaire}</t3>", 0, 'L', 0);
            $y2 = $pdf->GetY();
            if ($this->commentaire_horaire) {
                $pdf->setY($y2);
            } else {
                $pdf->setY($y2 + 7);
            }
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->setLeftMargin(15);
                $pdf->setY(30);
            }
        }
        
        $pdf->MultiCellTag(70, 7, "<t2>Astreinte :</t2> <t3>{$this->astreinte}</t3>", 0, 'L', 0);
        if($this->commentaire_astreinte) {
            $yf = $pdf->GetY();
            $pdf->MultiCellTag(170, 7, "<t2>Commentaire astreinte :</t2>\n<t3>{$this->commentaire_astreinte}</t3>", 0, 'L', 0);
            $y2 = $pdf->GetY();
            if ($this->commentaire_astreinte) {
                $pdf->setY($y2);
            } else {
                $pdf->setY($y2 + 7);
            }
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->setLeftMargin(15);
                $pdf->setY(30);
            }
        }

        if ($pdf->GetStringWidth($this->tache) > 1100) {
            $pdf->MultiCellTag(170, 6, "<t2>Description des tâches :</t2> \n<t3>" . substr($this->tache, 0, 1100) . "...</t3>", 0, 'L', 0);
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
            $pdf->MultiCellTag(170, 6, "<t2>Description des tâches (suite) :</t2>\n<t3>" . substr($this->tache, 1100, 5000) . "</t3>", 0, 'L', 0);
        } else {
            $pdf->MultiCellTag(170, 6, "<t2>Description des tâches :</t2> \n<t3>" . $this->tache . "</t3>", 0, 'L', 0);
        }
        $yf = $pdf->GetY();
        $pdf->MultiCellTag(170, 7, "<t2>Frais de déplacements et de séjours :</t2>\n<t3>{$this->frais}</t3>", 0, 'L', 0);
        $y2 = $pdf->GetY();
        if ($this->frais) {
            $pdf->setY($y2);
        } else {
            $pdf->setY($y2 + 7);
        }
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        $yf = $pdf->GetY();
        $pdf->MultiCellTag(170, 7, "<t2>Indemnités repas :</t2>\n<t3>{$this->indemnites_repas} </t3>", 0, 'L', 0);
        $y3 = $pdf->GetY();
        $pdf->setY($y3);
        if ($pdf->GetY() + 30 > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        $yf = $pdf->GetY();
        $cd = new ContratDelegation($this->Id_cd, array());
        $itinerant = $cd->itinerant == '1' ? 'Oui' : 'Non';
        $pdf->MultiCellTag(170, 7, "<t2>Itinérant :</t2> <t3>{$itinerant}</t3>\n", 0, 'L', 0);
        $y3 = $pdf->GetY();
        $pdf->setY($y3);
        if ($pdf->GetY() + 30 > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        
        if($this->equipement_securite_a_prevoir || $this->formations_specifiques_exigees || $this->smr) {
            $yf = $pdf->GetY();
            $pdf->MultiCellTag(170, 7, "<t2>Equipement de sécurité à prévoir :</t2> <t3>{$this->equipement_securite_a_prevoir}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(170, 7, "<t2>Formations spécifiques exigées :</t2> <t3>{$this->formations_specifiques_exigees}</t3>", 0, 'L', 0);
            $pdf->MultiCellTag(170, 7, "<t2>Suivi Médical Renforcé à prévoir :</t2> <t3>{$this->smr}</t3>", 0, 'L', 0);
            $y3 = $pdf->GetY();
            $pdf->setY($y3 + 7);
            if ($pdf->GetY() + 30 > 250) {
                $pdf->AddPage();
                $pdf->setLeftMargin(15);
                $pdf->setY(30);
            }
        }
        else {
            $pdf->setY($y3 + 7);
        }
        
        $yf = $pdf->GetY();
        $pdf->MultiCellTag(170, 7, "<t4>Je certifie avoir pris connaissance des politiques et règles de sécurité Proservia et m'engage à prendre en compte les politiques et règles sécurité en vigeur chez le client.</t4>", 0, 'L', 0);
        $y3 = $pdf->GetY();
        $pdf->setY($y3 + 7);
        if ($pdf->GetY() + 30 > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        $yf = $pdf->GetY();
        $pdf->MultiCellTag(170, 7, "<t4>Dans le cas de prêt de matériel client (y compris badge) je m'engage à le signaler à mon manager Proservia et à signer l'attestation de remise de matériel fournie par le service RH.</t4>", 0, 'L', 0);
        $y3 = $pdf->GetY();
        $pdf->setY($y3 + 7);
        if ($pdf->GetY() + 30 > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        
        
        $pdf->MultiCellTag(110, 7, "<t4>" . ODMCANCELINFO . "</t4>", 0, 'L', 0);
        if ($_SESSION['societe'] == 'OVIALIS') {
            $pdf->MultiCellTag(110, 7, "<t4>Ordre de mission à retourner</t4>", 0, 'L', 0);
            
        }
        $pdf->setY($pdf->GetY() + 5);
        
        $pdf->yLieu = $pdf->GetY();
        if ($_SESSION['societe'] == 'PROSERVIA' || $_SESSION['societe'] == 'NETLEVEL')
            $pdf->MultiCellTag(120, 7, "<t4>Fait à </t4>", 0, 'L', 0);
        else
            $pdf->MultiCellTag(120, 7, "<t4>Fait à Carquefou</t4>", 0, 'L', 0);
        
        $pdf->setXY($pdf->GetX() + 50, $pdf->GetY() - 7);
        $pdf->MultiCellTag(120, 7, "<t4>, le " . DateMysqltoFr($this->date_creation) . "</t4>", 0, 'L', 0);
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
            $pdf->setLeftMargin(15);
            $pdf->setY(30);
        }
        $pdf->yConnaissance = $pdf->GetY();

        $pdf->setY($pdf->GetY() + 14);
        $y = $pdf->GetY();
        $pdf->MultiCellTag(110, 7, "<t4>Collaborateur</t4>", 0, 'L', 0);
        $pdf->collaborateur = $ressource->getName();
        $pdf->yR = $pdf->GetY();
        $pdf->xR = $pdf->GetX();
        $pdf->setXY($pdf->GetX() + 80, $y);
        $pdf->MultiCellTag(110, 7, "<t4>Responsable " . $ressource->societe . "</t4>", 0, 'L', 0);
        $pdf->yDA = $pdf->GetY();
        $pdf->xDA = $pdf->GetX() + 80;
        $pdf->responsable = $this->responsable;

        if ($serialize) {
            return serialize($pdf);
        } else {
            $pdf->Output(FILES_ODM_WAITING . $_SESSION['societe'] . '/' . $this->Id_ordre_mission . '.pdf', $dest);
        }
    }

    /**
     * Affichage du formulaire de recherche d'un ordre de mission
     *
     * @return string	 
     */
    public function searchForm() {
        if (empty($_SESSION['filtre']['createur'])) {
            $_SESSION['filtre']['createur'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        $createur = new Utilisateur($_SESSION['filtre']['createur'], array());
        $ressource = new Salarie($_SESSION['filtre']['Id_ressource'], array());
        $agence = new Agence($_SESSION['filtre']['Id_agence'], array());
        $utilisateur = new Utilisateur($_SESSION['filtre']['responsable'], array());
        $compte = CompteFactory::create('CG', (is_null($_SESSION['filtre']['Id_compte']) ? '' : $_SESSION['filtre']['Id_compte']));
        $html = '
		n° ODM: <input id="Id_odm" type="text" onkeyup="afficherODM()" value="' . $_SESSION['filtre']['Id_odm'] . '" size="2" />
        &nbsp;&nbsp;				
		<select id="createur" onchange="afficherODM()">
            <option value="">Par créateur</option>
            <option value="">----------------------------</option>
		    ' . $createur->getList('REL') . '
        </select>
		&nbsp;&nbsp;
		<select id="Id_ressource" onchange="afficherODM()">
			<option value="">Par ressource</option>
			<option value="">----------------------------</option>
			' . $ressource->getEmployeeList() . '
			<option value="">-------------------------</option>
			' . $ressource->getExEmployeeList() . '
		</select>
		&nbsp;&nbsp;				
		<select id="Id_agence" onchange="afficherODM()">
			<option value="">Par agence</option>
			<option value="">----------------------------</option>
			' . $agence->getList() . '
		</select>
		<br /><br />
                Compte : <input id="Id_compte" type="text" onkeyup="afficherODM()" value="' . $_SESSION['filtre']['Id_compte'] . '" />
                &nbsp;&nbsp;
		<select id="responsable" onchange="afficherODM()">
                    <option value="">Par responsable</option>
                    <option value="">----------------------------</option>
                            ' . $utilisateur->getList('COM') . '
                                <option value="">-------------------------</option>
                                ' . $utilisateur->getList('OP') . '			
                </select>
		&nbsp;&nbsp;
		du <input id="debut" type="text" onfocus="showCalendarControl(this)" size="8" value=' . $_SESSION['filtre']['debut'] . ' >
		&nbsp;
		au <input id="fin" type="text" onfocus="showCalendarControl(this)"  size="8" value=' . $_SESSION['filtre']['fin'] . ' >
                <input onchange="afficherODM()" type="checkbox" name="finishing" id="finishing" value="1">Se finissant dans moins d\'un mois</input>
		<input type="button" onclick="afficherODM()" value="Go !">
';
        return $html;
    }

    /**
     * Affichage de la phrase pour le planning et la modulation des horaires
     *
     * @param string Statut de la ressource
     *
     * @return string	 
     */
    public static function contractStatusTimeMsg($statut, $horaire, $societe) {
        if ($statut == 'CADRE') {
            return ODMCADRETIME;
        } elseif ($statut == 'ETAM') {
            if($horaire != null)
                return sprintf(ODMETAMTIME, $horaire, $horaire, $societe);
            else
                return sprintf(ODMETAMTIME, 35, 35, $societe);
        }
    }

    /**
     * Mise à jour du champ envoye de l'odre de mission
     *
     * @param int Identifiant de l'ordre de mission
     * @param date d'envoi de retour de l'ordre de mission
     * @param int Indique si l'ordre de mission a été envoyé	  
     *
     */
    public function send($date_envoi, $envoiOK) {
        $db = connecter();

        $pdf = $this->edit(true);

        $responsable = new Utilisateur($this->responsable, array());
        $ressource = RessourceFactory::create('SAL', $this->Id_ressource, null);
        $compte = CompteFactory::create(null, $this->Id_compte);
        $req = new HTTP_Request(OSC_URL . 'script/receiveODM.php');
        $req->setMethod(HTTP_REQUEST_METHOD_POST);
        $req->addPostData('Id_ordre_mission', $this->Id_ordre_mission);
        $req->addPostData('pdf', $pdf);
        $req->addPostData('societe', $_SESSION['societe']);
        $req->addPostData('code_ressource', $ressource->code_ressource);
        $req->addPostData('client', $compte->nom);

        if (!PEAR::isError($req->sendRequest())) {
            if ($req->getResponseCode() == 200) {
                $retour = json_decode($req->getResponseBody());
                if ($retour->code == '0') {
                    if ($_SESSION['societe'] == 'PROSERVIA' || $_SESSION['societe'] == 'NETLEVEL') {
                        $hdrs = array(
                            'From' => $responsable->mail,
                            'Cc' => (new Utilisateur($this->createur,array()))->mail,
                            'Subject' => 'Nouvel ordre de mission'
                        );
                        $crlf = "\n";

                        $mime = new Mail_mime($crlf);
                        $mime->setHTMLBody('Bonjour ' . $ressource->prenom . ',<br /><br /> 
                                            Nous vous informons qu?un nouvel Ordre De Mission détaillant votre future prestation est disponible sur l\'OSC.<br />
                                            Nous vous remercions de bien vouloir en prendre connaissance (rapidement) et le valider en cliquant ici : <a href="' . OSC_URL . 'membre/index.php?a=consulterODM&Id=' . $ressource->code_ressource . '">Lien vers l\'ordre de mission</a><br /><br />
                                            Pour tout complément d\'information, merci de vous rapprocher de votre responsable commercial.<br /><br />
                                            Cordialement');

                        $body = $mime->get();
                        $hdrs = $mime->headers($hdrs);
                        
                        $hdrs2 = array(
                            'From' => (new Utilisateur($this->createur,array()))->mail,
                            'Subject' => 'Nouvel ordre de mission'
                        );

                        $mime2 = new Mail_mime($crlf);
                        $mime2->setHTMLBody('Bonjour,<br /><br /> 
                                            Nous vous informons qu\'un nouvel Ordre De Mission a été créé et envoyé au collaborateur ' . $ressource->prenom . ' ' . $ressource->nom . '.<br />
                                            Vous recevrez un second mail lorsque le collaborateur l\'aura validé.<br /><br />
                                            Cordialement');

                        $body2 = $mime2->get();
                        $hdrs2 = $mime2->headers($hdrs);

                        // Create the mail object using the Mail::factory method
                        $params['host'] = SMTP_HOST;
                        $params['port'] = SMTP_PORT;
                        $mail_object = Mail::factory('smtp', $params);
                        
                        $send = '';//$mail_object->send($retour->mail, $hdrs, $body);
                        if (PEAR::isError($send)) {
                            print($send->getMessage());
                        }
                        
                        $send2 = '';//$mail_object->send($responsable->mail, $hdrs2, $body2);
                        if (PEAR::isError($send2)) {
                            print($send2->getMessage());
                        }
                        
                        $this->edit(false, 'F');

                        $datas = array(
                            'success' => true,
                            'disable' => true,
                            'html' => utf8_encode('L\'ordre de mission a été envoyé.')
                        );
                    }
                    $db->query('UPDATE ordre_mission SET envoye=' . mysql_real_escape_string($envoiOK) . ', date_envoi="' . mysql_real_escape_string(DATE) . '" WHERE Id_ordre_mission = ' . mysql_real_escape_string((int) $this->Id_ordre_mission));
                } elseif ($retour->code == '1') {
                    $datas = array(
                        'success' => true,
                        'disable' => true,
                        'html' => utf8_encode('L\'ordre de mission a remplacé et annulé l\'ancien.')
                    );
                    $this->edit(false, 'F');
                    $db->query('UPDATE ordre_mission SET envoye=' . mysql_real_escape_string($envoiOK) . ', date_envoi="' . mysql_real_escape_string(DATE) . '" WHERE Id_ordre_mission = ' . mysql_real_escape_string((int) $this->Id_ordre_mission));
                } elseif ($retour->code == '2') {
                    $datas = array(
                        'success' => false,
                        'disable' => true,
                        'html' => utf8_encode('L\'ordre de mission a déjà été validé, vous devez en recréer un nouveau et avertir le collaborateur.')
                    );
                } elseif ($retour->code == '3') {
                    $datas = array(
                        'success' => false,
                        'disable' => false,
                        'html' => utf8_encode('Le collaborateur n\'existe pas dans l\'OSC. Veuillez réessayer d\'envoyer l\'ODM demain.')
                    );
                } elseif ($retour->code == '4') {
                    $datas = array(
                        'success' => false,
                        'disable' => false,
                        'html' => utf8_encode('Le compte du collaborateur n\'existe pas encore, nous ne pouvons donc lui envoyer d\'email. Veuillez réenvoyer l\'ODM dans quelques jours.')
                    );
                }
            } else {
                $datas = array(
                    'success' => false,
                    'disable' => false,
                    'html' => utf8_encode('L\'OSC rencontre un problème technique. Merci de réessayer prochainement.')
                );
            }
            header("X-JSON: " . json_encode($datas));
        }
    }

    /**
     * Mise à jour du champ retour de l'odre de mission
     *
     * @param int Identifiant de l'ordre de mission
     * @param date date de retour de l'ordre de mission
     * @param int Indique si l'ordre de mission a été retourné
     *
     */
    public static function sendBack($Id, $date_retour, $retourOK) {
        $db = connecter();
        $db->query('UPDATE ordre_mission SET retour=' . mysql_real_escape_string($retourOK) . ',date_retour="' . mysql_real_escape_string(DateMysqltoFr($date_retour, 'mysql')) . '" WHERE Id_ordre_mission = ' . mysql_real_escape_string((int) $Id));
    }

    /**
     * Suppression d'un ordre de mission
     */
    public function delete() {
        $ressource = RessourceFactory::create('SAL', $this->Id_ressource, null);
        $req = new HTTP_Request(OSC_URL . 'script/deleteODM.php');
        $req->setMethod(HTTP_REQUEST_METHOD_POST);
        $req->addPostData('Id_ordre_mission', $this->Id_ordre_mission);
        $req->addPostData('societe', $_SESSION['societe']);

        if (!PEAR::isError($req->sendRequest())) {
            if ($req->getResponseCode() == 200) {
                $retour = json_decode($req->getResponseBody());
                $db = connecter();
                $db->query('DELETE FROM ordre_mission WHERE Id_ordre_mission = ' . mysql_real_escape_string((int) $this->Id_ordre_mission));
                if ($retour->code == '0') {
                    if ($_SESSION['societe'] == 'PROSERVIA' || $_SESSION['societe'] == 'NETLEVEL') {
                        $hdrs = array(
                            'From' => (new Utilisateur($this->createur,array()))->mail,
                            'Subject' => 'Suppression ordre de mission'
                        );
                        $crlf = "\n";

                        $compte = CompteFactory::create(null, $this->Id_compte);
                        $mime = new Mail_mime($crlf);
                        $mime->setHTMLBody('Bonjour ' . $ressource->prenom . '<br /><br />
						Votre ordre de mission du ' . FormatageDate($this->date_creation) . ' pour ' . $compte->nom . ' vient d\'être supprimé.<br />
											
						Cordialement');

                        $body = $mime->get();
                        $hdrs = $mime->headers($hdrs);

                        // Create the mail object using the Mail::factory method
                        $params['host'] = SMTP_HOST;
                        $params['port'] = SMTP_PORT;
                        $mail_object = Mail::factory('smtp', $params);

                        $send = '';//$mail_object->send($retour->mail, $hdrs, $body);

                        if (PEAR::isError($send)) {
                            print($send->getMessage());
                        }
                    }
                    echo '<script type="text/javascript">
				          	alert("L\'ordre de mission a été supprimé.");
				          	window.location.replace("' . $_SERVER['HTTP_REFERER'] . '");
						  </script>';
                } elseif ($retour->code == '1') {
                    echo '<script type="text/javascript">
				          	alert("L\'ordre de mission a déjà été validé, vous devez en recréer un nouveau et avertir le collaborateur.");
				          	window.location.replace("' . $_SERVER['HTTP_REFERER'] . '");
                                              </script>';
                } elseif ($retour->code == '2') {
                    echo '<script type="text/javascript">
				          	alert("L\'ordre de mission a déjà été supprimé.");
				          	window.location.replace("' . $_SERVER['HTTP_REFERER'] . '");
                                              </script>';
                }
            } else {
                echo '<script type="text/javascript">
				          	alert("L\'OSC rencontre un problème technique. Merci de réessayer prochainement.");
				          	window.location.replace("' . $_SERVER['HTTP_REFERER'] . '");
					</script>';
            }
        }
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showResource($record) {
        $ressource = RessourceFactory::create('SAL', $record['id_ressource'], null);
        return $ressource->getName();
    }
    
    public function showCustomer($record) {
        $db = connecter();
        $compte = $db->query('SELECT compte FROM contrat_delegation WHERE Id_contrat_delegation = ' . mysql_real_escape_string((int)$record['id_cd']))->fetchOne();
        return $compte;
    }
    
    public function showSendDate($record, $args) {
        $odmenvoye = $odmenvoyedate = $odmretour = $odmretourdate = '';
        if ($record['envoye'] == 1 || !Utilisateur::getMissionOrderRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_ordre_mission'])) {
            $odmenvoye = 'checked="checked" disabled="disabled"';
            $odmenvoyedate = 'disabled="disabled"';
        }
        if (!$args['csv']) return '<input id="date_envoi' . $record['id_ordre_mission'] . '" type="text" readonly="readonly" value="' . FormatageDate($record['date_envoi']) . '" size="8" ' . $odmenvoyedate . '><input type="checkbox" id="envoye' . $record['id_ordre_mission'] . '" ' . $odmenvoye . ' onclick="if (confirm(\'Envoyer l\\\'ordre de mission au collaborateur ?\')) { ODMEnvoye(' . $record['id_ordre_mission'] . '); } else { this.checked = 0; }" />';
        else return FormatageDate($record['date_envoi']);
    }
    
    public function showReturnDate($record, $args) {
        $odmenvoye = $odmenvoyedate = $odmretour = $odmretourdate = '';
        $right = Utilisateur::getMissionOrderRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_ordre_mission']);
        if ($record['retour'] == 1 || !$right) {
            $odmretour = 'checked="checked" disabled="disabled"';
            $odmretourdate = 'disabled="disabled"';
        }
        else if($record['envoye'] == 1 && $record['retour'] == 0 && $right) {
            $odmretour = 'onclick="ODMRetour(' . $record['id_ordre_mission'] . ');"';
            $odmretourdate = 'onfocus="showCalendarControl(this)"';
        }
        else {
            $odmretour = 'readonly="readonly"';
            $odmretourdate = 'readonly="readonly"';
        }
        if (!$args['csv']) return '<input id="date_retour' . $record['id_ordre_mission'] . '" type="text" value="' . FormatageDate($record['date_retour']) . '" size="8" ' . $odmretourdate . '><input type="checkbox" id="retour' . $record['id_ordre_mission'] . '" ' . $odmretour . ' />';
        else return FormatageDate($record['date_retour']);
    }
    
    public function showButtons($record) {
        if (Utilisateur::getMissionOrderRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $record['id_ordre_mission'])) {
            $htmlAdmin = '
                <td><a href="index.php?a=modifierODM&amp;Id=' . $record['id_ordre_mission'] . '"><img src="' . IMG_EDIT . '"></a></td>
                <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../membre/index.php?a=supprimer&amp;Id=' . $record['id_ordre_mission'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        }
        if ($_SESSION['societe'] == 'OVIALIS') {
            $pdf = '<a href="index.php?a=editerODM&amp;Id=' . $record['id_ordre_mission'] . '"><img src="' . IMG_PDF . '"></a>';
        } elseif (file_exists(FILES_ODM_VALIDATE . $_SESSION['societe'] . '/' . $record['id_ordre_mission'] . '.pdf') && $record['envoye'] == 1) {
            $pdf = '<a href="' . FILES_ODM_VALIDATE . $_SESSION['societe'] . '/' . $record['id_ordre_mission'] . '.pdf"><img src="' . IMG_PDF . '"></a>';
        } elseif (file_exists(FILES_ODM_WAITING . $_SESSION['societe'] . '/' . $record['id_ordre_mission'] . '.pdf') && $record['retour'] == 1) {
            $pdf = '<a href="' . FILES_ODM_WAITING . $_SESSION['societe'] . '/' . $record['id_ordre_mission'] . '.pdf"><img src="' . IMG_PDF . '"></a>';
        } else {
            $pdf = '<a href="index.php?a=editerODM&amp;Id=' . $record['id_ordre_mission'] . '"><img src="' . IMG_PDF . '"></a>';
        }
        return $pdf . $htmlAdmin;
    }
}

?>
