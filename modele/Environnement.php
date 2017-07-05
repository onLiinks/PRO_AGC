<?php

/**
 * Fichier Environnement.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Environnement
 */
class Environnement {

    /**
     * Identifiant de l'environnement
     *
     * @access private
     * @var int 
     */
    private $Id_environnement;
    /**
     * Nombre de poste
     *
     * @access private
     * @var int 
     */
    private $nb_poste;
    /**
     * Nombre de PC fixes
     *
     * @access private
     * @var int 
     */
    private $nb_pcfixe;
    /**
     * Nombre de PC portables
     *
     * @access private
     * @var int 
     */
    private $nb_portable;
    /**
     * Nombre de serveurs
     *
     * @access private
     * @var int 
     */
    private $nb_serveur;
    /**
     * Nombre de serveurs pour environement serveur
     *
     * @access private
     * @var array 
     */
    private $nb_serveur_tab;
    /**
     * Nombre de sites en france
     *
     * @access private
     * @var int 
     */
    private $nb_site_fr;
    /**
     * Nombre de sites à l'etranger
     *
     * @access private
     * @var int 
     */
    private $nb_site_ext;
    /**
     * Nombre d'imprimante
     *
     * @access private
     * @var int 
     */
    private $nb_imprimante;
    /**
     * Nombre d'imprimante reseaux
     *
     * @access private
     * @var int 
     */
    private $nb_imprimante_reseaux;
    /**
     * Nombre d'imprimante local
     *
     * @access private
     * @var int 
     */
    private $nb_imprimante_local;
    /**
     * Nombre d'imprimante copieur
     *
     * @access private
     * @var int 
     */
    private $nb_imprimante_copieur;
    /**
     * Nombre d'utilisateurs
     *
     * @access private
     * @var int 
     */
    private $nb_utilisateur;
    /**
     * Complément d'informations
     *
     * @access private
     * @var string 
     */
    private $complement;
    /**
     * Environnement technique
     *
     * @access private
     * @var string 
     */
    private $env_technique;
    /**
     * Identifiant de l'affaire
     *
     * @access private
     * @var int 
     */
    private $Id_affaire;

    /**
     * Constructeur de la classe environnement
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l' environnement
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_environnement = '';
                $this->nb_poste = '';
                $this->nb_pcfixe = '';
                $this->nb_portable = '';
                $this->nb_serveur = '';
                $this->nb_imprimante = '';
                $this->nb_imp_reseaux = '';
                $this->nb_imp_local = '';
                $this->nb_imp_copieur = '';
                $this->nb_site_fr = '';
                $this->nb_site_ext = '';
                $this->nb_utilisateur = '';
                $this->complement = '';
                $this->env_technique = '';
                $this->Id_affaire = '';
                $this->langue = '';
                $this->plage_horaire_hd = '';
                $this->plage_horaire_sup = '';
                $this->contact = '';
                $this->reversibilite = '';
                $this->Id_type_pdt = '';
                $this->editeur_pdt = '';
                $this->version_pdt = '';
                $this->modele_reseau = '';
                $this->version_reseau = '';
                $this->roles_reseau = '';
                $this->nb_reseau = '';
                $this->sup_reseau = '';
                $this->exp_reseau = '';
                $this->adm_reseau = '';
                $this->systeme_serveur = '';
                $this->version_serveur = '';
                $this->roles_serveur = '';
                $this->nb_serveur_tab = '';
                $this->sup_serveur = '';
                $this->exp_serveur = '';
                $this->adm_serveur = '';
                $this->commentaire_tache = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_environnement = '';
                $this->nb_poste = htmlscperso(stripslashes($tab['nb_poste']), ENT_QUOTES);
                $this->nb_pcfixe = htmlscperso(stripslashes($tab['nb_pcfixe']), ENT_QUOTES);
                $this->nb_portable = htmlscperso(stripslashes($tab['nb_portable']), ENT_QUOTES);
                $this->nb_serveur = htmlscperso(stripslashes($tab['nb_serveur']), ENT_QUOTES);
                $this->nb_imprimante = htmlscperso(stripslashes($tab['nb_imprimante']), ENT_QUOTES);
                $this->nb_imp_reseaux = htmlscperso(stripslashes($tab['nb_imp_reseaux']), ENT_QUOTES);
                $this->nb_imp_local = htmlscperso(stripslashes($tab['nb_imp_local']), ENT_QUOTES);
                $this->nb_imp_copieur = htmlscperso(stripslashes($tab['nb_imp_copieur']), ENT_QUOTES);
                $this->nb_site_fr = htmlscperso(stripslashes($tab['nb_site_fr']), ENT_QUOTES);
                $this->nb_site_ext = htmlscperso(stripslashes($tab['nb_site_ext']), ENT_QUOTES);
                $this->nb_utilisateur = htmlscperso(stripslashes($tab['nb_utilisateur']), ENT_QUOTES);
                $this->complement = $tab['complement'];
                $this->env_technique = $tab['env_technique'];
                $this->Id_affaire = (int) $tab['Id_affaire'];

                $this->langue = htmlscperso(stripslashes($tab['langue']), ENT_QUOTES);
                $this->plage_horaire_hd = htmlscperso(stripslashes($tab['plage_horaire_hd']), ENT_QUOTES);
                $this->plage_horaire_sup = htmlscperso(stripslashes($tab['plage_horaire_sup']), ENT_QUOTES);
                $this->contact = htmlscperso(stripslashes($tab['contact']), ENT_QUOTES);
                $this->reversibilite = htmlscperso(stripslashes($tab['reversibilite']), ENT_QUOTES);

                if ($tab['Id_type_pdt']) {
                    $nb_type = count($tab['Id_type_pdt']);
                    $i = 0;
                    while ($i < $nb_type) {
                        if ($tab['Id_type_pdt'][$i]) {
                            $this->Id_type_pdt[] = $tab['Id_type_pdt'][$i];
                            $this->editeur_pdt[] = $tab['editeur_pdt'][$i];
                            $this->version_pdt[] = $tab['version_pdt'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['modele_reseau']) {
                    $nb_modele = count($tab['modele_reseau']);
                    $i = 0;
                    while ($i < $nb_modele) {
                        if ($tab['modele_reseau'][$i]) {
                            $this->modele_reseau[] = $tab['modele_reseau'][$i];
                            $this->version_reseau[] = $tab['version_reseau'][$i];
                            $this->roles_reseau[] = $tab['roles_reseau'][$i];
                            $this->nb_reseau[] = $tab['nb_reseau'][$i];
                            $this->sup_reseau[] = $tab['sup_reseau'][$i];
                            $this->exp_reseau[] = $tab['exp_reseau'][$i];
                            $this->adm_reseau[] = $tab['adm_reseau'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['systeme_serveur']) {
                    $nb_systeme = count($tab['systeme_serveur']);
                    $i = 0;
                    while ($i < $nb_systeme) {
                        if ($tab['systeme_serveur'][$i]) {
                            $this->systeme_serveur[] = $tab['systeme_serveur'][$i];
                            $this->version_serveur[] = $tab['version_serveur'][$i];
                            $this->roles_serveur[] = $tab['roles_serveur'][$i];
                            $this->nb_serveur_tab[] = $tab['nb_serveur_tab'][$i];
                            $this->sup_serveur[] = $tab['sup_serveur'][$i];
                            $this->exp_serveur[] = $tab['exp_serveur'][$i];
                            $this->adm_serveur[] = $tab['adm_serveur'][$i];
                        }
                        ++$i;
                    }
                }
                $this->commentaire_tache = $tab['commentaire_tache'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM environnement WHERE Id_environnement =' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_environnement = $code;
                $this->nb_poste = $ligne->nb_poste;
                $this->nb_pcfixe = $ligne->nb_pcfixe;
                $this->nb_portable = $ligne->nb_portable;
                $this->nb_serveur = $ligne->nb_serveur;
                $this->nb_imprimante = $ligne->nb_imprimante;
                $this->nb_imp_reseaux = $ligne->nb_imp_reseaux;
                $this->nb_imp_local = $ligne->nb_imp_local;
                $this->nb_imp_copieur = $ligne->nb_imp_copieur;
                $this->nb_site_fr = $ligne->nb_site_fr;
                $this->nb_site_ext = $ligne->nb_site_ext;
                $this->nb_utilisateur = $ligne->nb_utilisateur;
                $this->complement = $ligne->complement;
                $this->env_technique = $ligne->env_technique;
                $this->Id_affaire = $ligne->id_affaire;
                $this->commentaire_tache = $ligne->commentaire_tache;

                if (in_array(Affaire::getIdTypeContrat($this->Id_affaire), array(2, 3, 4))) {
                    $ligne = $db->query('SELECT * FROM env_couverture WHERE Id_environnement =' . mysql_real_escape_string((int) $code))->fetchRow();
                    $this->langue = $ligne->langue;
                    $this->plage_horaire_hd = $ligne->plage_horaire_hd;
                    $this->plage_horaire_sup = $ligne->plage_horaire_sup;
                    $this->contact = $ligne->contact;
                    $this->reversibilite = $ligne->reversibilite;

                    $result = $db->query('SELECT * FROM env_pdt WHERE Id_environnement =' . mysql_real_escape_string((int) $code));
                    while ($ligne = $result->fetchRow()) {
                        $this->Id_type_pdt[] = $ligne->id_type_pdt;
                        $this->editeur_pdt[] = $ligne->editeur;
                        $this->version_pdt[] = $ligne->version;
                    }
                    $result = $db->query('SELECT * FROM env_reseaux WHERE Id_environnement =' . mysql_real_escape_string((int) $code));
                    while ($ligne = $result->fetchRow()) {
                        $this->modele_reseau[] = $ligne->modele;
                        $this->version_reseau[] = $ligne->version;
                        $this->roles_reseau[] = $ligne->roles;
                        $this->nb_reseau[] = $ligne->nombre;
                        $this->sup_reseau[] = $ligne->supervision;
                        $this->exp_reseau[] = $ligne->exploitation;
                        $this->adm_reseau[] = $ligne->administration;
                    }

                    $result = $db->query('SELECT * FROM env_serveur WHERE Id_environnement =' . mysql_real_escape_string((int) $code));
                    while ($ligne = $result->fetchRow()) {
                        $this->systeme_serveur[] = $ligne->systeme;
                        $this->version_serveur[] = $ligne->version;
                        $this->roles_serveur[] = $ligne->roles;
                        $this->nb_serveur_tab[] = $ligne->nb_serveur;
                        $this->sup_serveur[] = $ligne->supervision;
                        $this->exp_serveur[] = $ligne->exploitation;
                        $this->adm_serveur[] = $ligne->administration;
                    }
                }
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_environnement = $code;
                $this->nb_poste = htmlscperso(stripslashes($tab['nb_poste']), ENT_QUOTES);
                $this->nb_pcfixe = htmlscperso(stripslashes($tab['nb_pcfixe']), ENT_QUOTES);
                $this->nb_portable = htmlscperso(stripslashes($tab['nb_portable']), ENT_QUOTES);
                $this->nb_serveur = htmlscperso(stripslashes($tab['nb_serveur']), ENT_QUOTES);
                $this->nb_imprimante = htmlscperso(stripslashes($tab['nb_imprimante']), ENT_QUOTES);
                $this->nb_imp_reseaux = htmlscperso(stripslashes($tab['nb_imp_reseaux']), ENT_QUOTES);
                $this->nb_imp_local = htmlscperso(stripslashes($tab['nb_imp_local']), ENT_QUOTES);
                $this->nb_imp_copieur = htmlscperso(stripslashes($tab['nb_imp_copieur']), ENT_QUOTES);
                $this->nb_site_fr = htmlscperso(stripslashes($tab['nb_site_fr']), ENT_QUOTES);
                $this->nb_site_ext = htmlscperso(stripslashes($tab['nb_site_ext']), ENT_QUOTES);
                $this->nb_utilisateur = htmlscperso(stripslashes($tab['nb_utilisateur']), ENT_QUOTES);
                $this->complement = $tab['complement'];
                $this->env_technique = $tab['env_technique'];
                $this->Id_affaire = (int) $tab['Id_affaire'];
                $this->langue = htmlscperso(stripslashes($tab['langue']), ENT_QUOTES);
                $this->plage_horaire_hd = htmlscperso(stripslashes($tab['plage_horaire_hd']), ENT_QUOTES);
                $this->plage_horaire_sup = htmlscperso(stripslashes($tab['plage_horaire_sup']), ENT_QUOTES);
                $this->contact = htmlscperso(stripslashes($tab['contact']), ENT_QUOTES);
                $this->reversibilite = htmlscperso(stripslashes($tab['reversibilite']), ENT_QUOTES);

                if ($tab['Id_type_pdt']) {
                    $nb_type = count($tab['Id_type_pdt']);
                    $i = 0;
                    while ($i < $nb_type) {
                        if ($tab['Id_type_pdt'][$i]) {
                            $this->Id_type_pdt[] = $tab['Id_type_pdt'][$i];
                            $this->editeur_pdt[] = $tab['editeur_pdt'][$i];
                            $this->version_pdt[] = $tab['version_pdt'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['modele_reseau']) {
                    $nb_modele = count($tab['modele_reseau']);
                    $i = 0;
                    while ($i < $nb_modele) {
                        if ($tab['modele_reseau'][$i]) {
                            $this->modele_reseau[] = $tab['modele_reseau'][$i];
                            $this->version_reseau[] = $tab['version_reseau'][$i];
                            $this->roles_reseau[] = $tab['roles_reseau'][$i];
                            $this->nb_reseau[] = $tab['nb_reseau'][$i];
                            $this->sup_reseau[] = $tab['sup_reseau'][$i];
                            $this->exp_reseau[] = $tab['exp_reseau'][$i];
                            $this->adm_reseau[] = $tab['adm_reseau'][$i];
                        }
                        ++$i;
                    }
                }
                if ($tab['systeme_serveur']) {
                    $nb_systeme = count($tab['systeme_serveur']);
                    $i = 0;
                    while ($i < $nb_systeme) {
                        if ($tab['systeme_serveur'][$i]) {
                            $this->systeme_serveur[] = $tab['systeme_serveur'][$i];
                            $this->version_serveur[] = $tab['version_serveur'][$i];
                            $this->roles_serveur[] = $tab['roles_serveur'][$i];
                            $this->nb_serveur_tab[] = $tab['nb_serveur_tab'][$i];
                            $this->sup_serveur[] = $tab['sup_serveur'][$i];
                            $this->exp_serveur[] = $tab['exp_serveur'][$i];
                            $this->adm_serveur[] = $tab['adm_serveur'][$i];
                        }
                        ++$i;
                    }
                }
                $this->commentaire_tache = $tab['commentaire_tache'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un environnement
     *
     * @return string	   
     */
    public function form($Id_type_contrat, $Id_pole) {
        if ($Id_type_contrat == 2) {
            $rev[$this->reversibilite] = 'checked="checked"';
            $html = '
			Merci de remplir dans la mesure du possible le Périmètre Technique avec le maximum de précision.<br /> 
			La qualification du besoin n\'en sera que meilleure et plus rapide
            <div class="left">
 			    <h3>Parc Informatique</h3>
			    Nombre de postes de travail :
                <input type="text" name="nb_poste" value="' . $this->nb_poste . '" size="2" /><br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_pcfixe" value="' . $this->nb_pcfixe . '" size="2" /> PC fixes </span>
                <br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_portable" value="' . $this->nb_portable . '" size="2" />
                portables</span><br /><br />
				Nombre d\'imprimantes (réseaux/local/copieur) :
                <input type="text" name="nb_imprimante" value="' . $this->nb_imprimante . '" size="2" /><br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_imp_reseaux" value="' . $this->nb_imp_reseaux . '" size="2" /> réseaux </span>
                <br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_imp_local" value="' . $this->nb_imp_local . '" size="2" />
                local</span><br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_imp_copieur" value="' . $this->nb_imp_copieur . '" size="2" />
                copieur</span><br /><br />
				Nombre d\'utilisateurs :
                <input type="text" name="nb_utilisateur" value="' . $this->nb_utilisateur . '" size="2" /><br /><br />
				Nombre de sites en France :
                <input type="text" name="nb_site_fr" value="' . $this->nb_site_fr . '" size="2" /><br /><br />
				Nombre de sites à l\'étranger :
                <input type="text" name="nb_site_ext" value="' . $this->nb_site_ext . '" size="2" /><br /><br />
			</div>
			<div class="right">
				<h3>Couverture demandée</h3><br />
				Langue(s) de couverture obligatoire(s) :
                <input type="text" name="langue" value="' . $this->langue . '" /><br /><br />
				Plage horaire de couverture helpdesk :
                <input type="text" name="plage_horaire_hd" value="' . $this->plage_horaire_hd . '" /><br /><br />
				Plage horaire de couverture supervision :
                <input type="text" name="plage_horaire_sup" value="' . $this->plage_horaire_sup . '" /><br /><br />
				Contact privilégié (téléphone, courriel,...) :
                <input type="text" name="contact" value="' . $this->contact . '" /><br /><br />
				Réversibilité fournie par le client / Ancien Prestataire : <br />
                ' . YES . ' <input type="radio" name="reversibilite" value="1" ' . $rev['1'] . ' />
				' . NO . ' <input type="radio" name="reversibilite" value="0" ' . $rev['0'] . ' />
				<br /><br />
			</div>
            <div class="clearer"></div>			
				<img src="' . IMG_PLUS . '" onclick="ajoutSysteme()">
				<img src="' . IMG_MOINS . '" onclick="enleveSysteme()"><br />
				<h3>Environnement Serveurs</h3><br />
					<table>    
						<tr>
				            <th width="15%">Système</th>
					        <th width="15%">Version</th>
					        <th width="15%">Rôles</th>
					        <th width="15%">Nb</th>
                            <th width="10%">Supervision</th>
						    <th width="10%">Exploitation</th>
						    <th width="10%">Administration</th>							
					    </tr>
					</table>	
				' . $this->systemForm() . '
				<br /><br />
				<img src="' . IMG_PLUS . '" onclick="ajoutReseau()">
				<img src="' . IMG_MOINS . '" onclick="enleveReseau()"><br />
				<h3>Environnement Réseaux</h3><br />
				<table>
				    <tr>
					    <th width="15%">Modèle</th>
					    <th width="15%">Version</th>
					    <th width="15%">Rôles</th>
					    <th width="15%">Nb</th>
					    <th width="10%">Supervision</th>
						<th width="10%">Exploitation</th>
						<th width="10%">Administration</th>
					</tr>
                </table>
				' . $this->netWorkForm() . '
				<br /><br />
				<img src="' . IMG_PLUS . '" onclick="ajoutPdt()">
				<img src="' . IMG_MOINS . '" onclick="enlevePdt()"><br />
				<h3>Environnement Postes de travail</h3><br />
				<table>
				    <tr>
					    <th>Type</th>
					    <th>Editeur</th>
					    <th>Version</th>
					</tr>
				</table>
				' . $this->workStationForm() . '
			<br /><br />
			<div class="left">
			    <h3>Tâches particulières attendues :</h3>
                <textarea name="commentaire_tache">' . $this->commentaire_tache . '</textarea>
			    <br /><br />
			</div>
            <div class="right">
			    <h3>Complément d\'informations :</h3>
                <textarea name="complement">' . $this->complement . '</textarea>
			</div>	
			<input type="hidden" name="Id_environnement" value="' . $this->Id_environnement . '" />
';
        } elseif ($Id_pole != 6) {
            $html = '
            <div class="left">
				Nombre de postes de travail :
				<input type="text" name="nb_poste" value="' . $this->nb_poste . '" size="2" /><br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_pcfixe" value="' . $this->nb_pcfixe . '" size="2" /> PC fixes :</span>
				<br /><br />
				&nbsp;&nbsp;<span>dont <input type="text" name="nb_portable" value="' . $this->nb_portable . '" size="2"/>
				portables</span><br /><br />
				Nombre de serveurs : 
				<input type="text" name="nb_serveur" value="' . $this->nb_serveur . '" size="2"/><br /><br />
				Nombre de sites :
				<input type="text" name="nb_site_fr" value="' . $this->nb_site_fr . '" size="2"/>
				Nombre d\'utilisateurs :
				<input type="text" name="nb_utilisateur" value="' . $this->nb_utilisateur . '" size="2"/>			
			</div>
            <div class="right">
				Environnement technique (OS, messagerie, BDD, sécurité ...) <br />
                <textarea name="env_technique">' . $this->env_technique . '</textarea><br /><br />
				Complément d\'informations <br />
                <textarea name="complement">' . $this->complement . '</textarea>
			</div>
			<input type="hidden" name="Id_environnement" value="' . (int) $this->Id_environnement . '" />
';
        } elseif ($Id_pole == 6) {
            $html = '
            <div class="center">
				Environnement technique (OS, messagerie, BDD, sécurité ...) <br />
                <textarea name="env_technique">' . $this->env_technique . '</textarea><br /><br />
				Complément d\'informations <br />
                <textarea name="complement">' . $this->complement . '</textarea>
			</div>
			<input type="hidden" name="Id_environnement" value="' . (int) $this->Id_environnement . '" />
';
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement) . ', nb_poste = ' . mysql_real_escape_string((int) $this->nb_poste) . ', 
		    nb_pcfixe = ' . mysql_real_escape_string((int) $this->nb_pcfixe) . ', nb_portable = ' . mysql_real_escape_string((int) $this->nb_portable) . ', nb_serveur = ' . mysql_real_escape_string((int) $this->nb_serveur) . ', 
			nb_site_fr = ' . mysql_real_escape_string((int) $this->nb_site_fr) . ', nb_site_ext = ' . mysql_real_escape_string((int) $this->nb_site_ext) . ', 
			nb_utilisateur = ' . mysql_real_escape_string((int) $this->nb_utilisateur) . ', complement = "' . mysql_real_escape_string($this->complement) . '", env_technique = "' . mysql_real_escape_string($this->env_technique) . '", 
			Id_affaire = ' . mysql_real_escape_string((int) $this->Id_affaire) . ', nb_imprimante = ' . mysql_real_escape_string((int) $this->nb_imprimante) . ', 
			nb_imp_reseaux = ' . mysql_real_escape_string((int) $this->nb_imp_reseaux) . ', nb_imp_local = ' . mysql_real_escape_string((int) $this->nb_imp_local) . ', 
			nb_imp_copieur = ' . mysql_real_escape_string((int) $this->nb_imp_copieur) . ', commentaire_tache = "' . mysql_real_escape_string($this->commentaire_tache) . '"';
        if ($this->Id_environnement) {
            $requete = 'UPDATE environnement ' . $set . ' WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement);
        } else {
            $requete = 'INSERT INTO environnement ' . $set;
        }
        $db->query($requete);
        $Id_environnement = ($this->Id_environnement == '') ? mysql_insert_id() : $this->Id_environnement;

        if ($this->Id_environnement) {
            $db->query('DELETE FROM env_serveur WHERE Id_environnement=' . mysql_real_escape_string((int) $this->Id_environnement));
            $db->query('DELETE FROM env_reseaux WHERE Id_environnement=' . mysql_real_escape_string((int) $this->Id_environnement));
            $db->query('DELETE FROM env_pdt WHERE Id_environnement=' . mysql_real_escape_string((int) $this->Id_environnement));
            $db->query('DELETE FROM env_couverture WHERE Id_environnement=' . mysql_real_escape_string((int) $this->Id_environnement));
        }
        $nb_systeme = count($this->systeme_serveur);
        $nb_modele = count($this->modele_reseau);
        $nb_type = count($this->Id_type_pdt);

        $i = 0;
        while ($i < $nb_systeme) {
            $db->query('INSERT INTO env_serveur SET Id_env_serveur="", systeme="' . mysql_real_escape_string($this->systeme_serveur[$i]) . '", version="' . mysql_real_escape_string($this->version_serveur[$i]) . '", roles="' . mysql_real_escape_string($this->roles_serveur[$i]) . '", nb_serveur="' . mysql_real_escape_string($this->nb_serveur_tab[$i]) . '", supervision=' . mysql_real_escape_string((int) $this->sup_serveur[$i]) . ', exploitation=' . mysql_real_escape_string((int) $this->exp_serveur[$i]) . ', administration=' . mysql_real_escape_string((int) $this->adm_serveur[$i]) . ', Id_environnement=' . mysql_real_escape_string((int) $Id_environnement));
            ++$i;
        }
        $i = 0;
        while ($i < $nb_modele) {
            $db->query('INSERT INTO env_reseaux SET Id_env_reseaux="", modele="' . mysql_real_escape_string($this->modele_reseau[$i]) . '", version="' . mysql_real_escape_string($this->version_reseau[$i]) . '", roles="' . mysql_real_escape_string($this->roles_reseau[$i]) . '", nombre="' . mysql_real_escape_string($this->nb_reseau[$i]) . '", supervision=' . mysql_real_escape_string((int) $this->sup_reseau[$i]) . ', exploitation=' . mysql_real_escape_string((int) $this->exp_reseau[$i]) . ', administration=' . mysql_real_escape_string((int) $this->adm_reseau[$i]) . ', Id_environnement=' . mysql_real_escape_string((int) $Id_environnement));
            ++$i;
        }
        $i = 0;
        while ($i < $nb_type) {
            $db->query('INSERT INTO env_pdt SET Id_env_pdt="", Id_type_pdt=' . mysql_real_escape_string((int) $this->Id_type_pdt[$i]) . ', editeur="' . mysql_real_escape_string($this->editeur_pdt[$i]) . '", version="' . mysql_real_escape_string($this->version_pdt[$i]) . '", Id_environnement=' . mysql_real_escape_string((int) $Id_environnement));
            ++$i;
        }
        if ($this->langue || $this->plage_horaire_hd || $this->plage_horaire_sup || $this->contact || $this->reversibilite) {
            $db->query('INSERT INTO env_couverture SET Id_env_couv="", langue="' . mysql_real_escape_string($this->langue) . '", plage_horaire_hd="' . mysql_real_escape_string($this->plage_horaire_hd) . '", plage_horaire_sup="' . mysql_real_escape_string($this->plage_horaire_sup) . '", contact="' . mysql_real_escape_string($this->contact) . '", reversibilite="' . mysql_real_escape_string($this->reversibilite) . '", Id_environnement=' . mysql_real_escape_string((int) $Id_environnement));
        }
    }

    /**
     * Formulaire de création / modification de l'environnement technique système
     *
     * @return string	   
     */
    public function systemForm() {
        if ($this->Id_environnement) {
            $count = count($this->systeme_serveur);
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            while ($i < $count) {
                $ss = $this->systeme_serveur[$i];
                $vs = $this->version_serveur[$i];
                $rs = $this->roles_serveur[$i];
                $nb = $this->nb_serveur_tab[$i];
                $supList = self::getYesNoList($this->sup_serveur[$i]);
                $expList = self::getYesNoList($this->exp_serveur[$i]);
                $admList = self::getYesNoList($this->adm_serveur[$i]);
                ++$i;
                $html .= '
	            <div id="autreSysteme' . $i . '">
				    <table>
                        <tr>
                            <td><input type="text" name="systeme_serveur[]" value="' . $ss . '" /></td>
						    <td><input type="text" name="version_serveur[]" value="' . $vs . '" /></td>
						    <td><input type="text" name="roles_serveur[]" value="' . $rs . '" /></td>
						    <td><input type="text" name="nb_serveur_tab[]" value="' . $nb . '" /></td>
						    <td>
							    <select name="sup_serveur[]">
					            ' . $supList . '
				                </select>
							</td>
						    <td>
							    <select name="exp_serveur[]">
					            ' . $expList . '
				                </select>
							</td>
						    <td>
							    <select name="adm_serveur[]">
					            ' . $admList . '
				                </select>
							</td>								
					    </tr>
				    </table>
				</div>
';
            }
            $html .= '
			<div id="autreSysteme' . $nb_suivant . '">
		        <input type="hidden" id="nb_systeme" value="' . $nb_courant . '">
		    </div>
';
        } elseif (!$this->Id_environnement) {
            $html = self::addSystemForm(1);
        }
        return $html;
    }

    /**
     * Formulaire d'ajout de l'environnement technique système
     *
     * @param int Nombre d'environnement système déjà ajouté
     *
     * @return string	   
     */
    public static function addSystemForm($nb) {
        $nb2 = $nb + 1;
        $html .= '
	    <table>
            <tr>
                <td><input type="text" name="systeme_serveur[]" /></td>
			    <td><input type="text" name="version_serveur[]" /></td>
			    <td><input type="text" name="roles_serveur[]" /></td>
				<td><input type="text" name="nb_serveur_tab[]" /></td>
				<td>
					<select name="sup_serveur[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>
				<td>
					<select name="exp_serveur[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>
				<td>
					<select name="adm_serveur[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>				
			</tr>
		</table>
		<div id="autreSysteme' . $nb2 . '"><input type="hidden" id="nb_systeme" value="' . $nb . '"></div>
';
        return $html;
    }

    /**
     * Formulaire de création / modification de l'environnement technique réseau
     *
     * @return string	   
     */
    public function netWorkForm() {
        if ($this->Id_environnement) {
            $count = count($this->modele_reseau);
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            while ($i < $count) {
                $mr = $this->modele_reseau[$i];
                $vr = $this->version_reseau[$i];
                $rr = $this->roles_reseau[$i];
                $nb = $this->nb_reseau[$i];
                $supList = self::getYesNoList($this->sup_reseau[$i]);
                $expList = self::getYesNoList($this->exp_reseau[$i]);
                $admList = self::getYesNoList($this->adm_reseau[$i]);
                ++$i;
                $html .= '
	            <div id="autreReseau' . $i . '">
				    <table>
						<tr>
                            <td><input type="text" name="modele_reseau[]" value="' . $mr . '" /></td>
			                <td><input type="text" name="version_reseau[]" value="' . $vr . '" /></td>
			                <td><input type="text" name="roles_reseau[]" value="' . $rr . '" /></td>
				            <td><input type="text" name="nb_reseau[]" value="' . $nb . '" /></td>
				            <td>
							    <select name="sup_reseau[]">
					            ' . $supList . '
				                </select>
							</td>
						    <td>
							    <select name="exp_reseau[]">
					            ' . $expList . '
				                </select>
							</td>
						    <td>
							    <select name="adm_reseau[]">
					            ' . $admList . '
				                </select>
							</td>							
			            </tr>
				    </table>
				</div>
';
            }
            $html .= '
		    <div id="autreReseau' . $nb_suivant . '">
		        <input type="hidden" id="nb_reseau" value="' . $nb_courant . '">
		    </div>
';
        } elseif (!$this->Id_environnement) {
            $html = self::addNetworkForm(1);
        }
        return $html;
    }

    /**
     * Formulaire d'ajout de l'environnement technique réseau
     *
     * @param int Nombre d'environnement réseau déjà ajouté
     *
     * @return string	   
     */
    public static function addNetworkForm($nb) {
        $nb2 = $nb + 1;
        $html .= '
	    <table>
            <tr>
                <td><input type="text" name="modele_reseau[]" /></td>
			    <td><input type="text" name="version_reseau[]" /></td>
			    <td><input type="text" name="roles_reseau[]" /></td>
				<td><input type="text" name="nb_reseau[]" /></td>
				<td>
					<select name="sup_reseau[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>
				<td>
					<select name="exp_reseau[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>
				<td>
					<select name="adm_reseau[]">
					    ' . self::getYesNoList() . '
				    </select>
				</td>
			</tr>
		</table>
		<div id="autreReseau' . $nb2 . '"><input type="hidden" id="nb_reseau" value="' . $nb . '"></div>
';
        return $html;
    }

    /**
     * Formulaire de création / modification de l'environnement technique poste de travail
     *
     * @return string	   
     */
    public function workStationForm() {
        if ($this->Id_environnement) {
            $count = count($this->Id_type_pdt);
            $nb_courant = $count;
            $nb_suivant = $nb_courant + 1;
            $i = 0;
            while ($i < $count) {
                $ep = $this->editeur_pdt[$i];
                $vp = $this->version_pdt[$i];
                $pdtList = self::getTypePdtListe($this->Id_type_pdt[$i]);
                ++$i;
                $html .= '
	            <div id="autrePdt' . $i . '">
				    <table>
						<tr>
                            <td>
							<select name="Id_type_pdt[]">
							    <option value="">' . TYPE_SELECT . '</option>
								<option value="">--------------------</option>
								' . $pdtList . '
							</select>
							</td>
			                <td><input type="text" name="editeur_pdt[]" value="' . $ep . '"/></td>
			                <td><input type="text" name="version_pdt[]" value="' . $vp . '"/></td>
			            </tr>
				    </table>
				</div>
';
            }
            $html .= '
				<div id="autrePdt' . $nb_suivant . '">
		            <input type="hidden" id="nb_pdt" value="' . $nb_courant . '">
		        </div>
';
        } elseif (!$this->Id_environnement) {
            $html = self::addWorkStationForm(1);
        }
        return $html;
    }

    /**
     * Formulaire d'ajout de l'environnement technique poste de travail
     *
     * @param int Nombre d'environnement poste de travail déjà ajouté
     *
     * @return string	   
     */
    public static function addWorkStationForm($nb) {
        $nb2 = $nb + 1;
        $html .= '
	    <table>
            <tr>
                <td>
				    <select name="Id_type_pdt[]">
				        <option value="">' . TYPE_SELECT . '</option>
					    <option value="">--------------------</option>
					    ' . self::getTypePdtListe("") . '
				    </select>
				</td>	
			    <td><input type="text" name="editeur_pdt[]" /></td>
			    <td><input type="text" name="version_pdt[]" /></td>
			</tr>
		</table>
		<div id="autrePdt' . $nb2 . '"><input type="hidden" id="nb_pdt" value="' . $nb . '"></div>
';
        return $html;
    }

    /**
     * Consultation de l'environnement d'une affaire
     */
    public function consultation() {
        if (Affaire::getIdTypeContrat($this->Id_affaire) == 2) {
            if ($this->systeme_serveur) {
                $nb_systeme = count($this->systeme_serveur);
                $i = 0;
                while ($i < $nb_systeme) {
                    $htmlEnvSysteme .= '
				    <tr>
					    <td>' . $this->systeme_serveur[$i] . '</td>
						<td>' . $this->version_serveur[$i] . '</td>
						<td>' . $this->roles_serveur[$i] . '</td>
						<td>' . $this->nb_serveur_tab[$i] . '</td>
						<td>' . yesno($this->sup_serveur[$i]) . '</td>
						<td>' . yesno($this->exp_serveur[$i]) . '</td>
						<td>' . yesno($this->adm_serveur[$i]) . '</td>
					</tr>
';
                    ++$i;
                }
                $htmlSysteme = '
				<hr />
			    <h2>Environnement Serveur</h2>
			    <table class="sortable">
			        <tr>
					    <th>Système</th>
					    <th>Version</th>
					    <th>Rôles</th>
					    <th>Nb</th>
					    <th>Supervision</th>
						<th>Exploitation</th>
						<th>Administration</th>
					</tr>
					' . $htmlEnvSysteme . '
			    </table>
				<hr />
';
            }
            if ($this->modele_reseau) {
                $nb_modele = count($this->modele_reseau);
                $i = 0;
                while ($i < $nb_modele) {
                    $htmlEnvReseaux .= '
				    <tr>
					    <td>' . $this->modele_reseau[$i] . '</td>
						<td>' . $this->version_reseau[$i] . '</td>
						<td>' . $this->roles_reseau[$i] . '</td>
						<td>' . $this->nb_reseau[$i] . '</td>
						<td>' . yesno($this->sup_reseau[$i]) . '</td>
						<td>' . yesno($this->exp_reseau[$i]) . '</td>
						<td>' . yesno($this->adm_reseau[$i]) . '</td>
					</tr>
';
                    ++$i;
                }
                $htmlReseaux = '
			    <h2>Environnement Réseau</h2>
			    <table class="sortable">
			        <tr>
					    <th>Modèle</th>
					    <th>Version</th>
					    <th>Rôles</th>
					    <th>Nb</th>
					    <th>Supervision</th>
						<th>Exploitation</th>
						<th>Administration</th>
					</tr>
					' . $htmlEnvReseaux . '
			    </table>
				<hr />
';
            }
            if ($this->Id_type_pdt) {
                $nb_pdt = count($this->Id_type_pdt);
                $i = 0;
                while ($i < $nb_pdt) {
                    $htmlEnvPdt .= '
				    <tr>
					    <td>' . self::getNomTypePdt($this->Id_type_pdt[$i]) . '</td>
						<td>' . $this->editeur_pdt[$i] . '</td>
						<td>' . $this->version_pdt[$i] . '</td>
					</tr>
';
                    ++$i;
                }
                $htmlPdt = '
			    <h2>Environnement Poste de Travail</h2>
			    <table class="sortable">
			        <tr>
					    <th>Type</th>
					    <th>Editeur</th>
					    <th>Version</th>
					</tr>
					' . $htmlEnvPdt . '
			    </table>
				<hr />
';
            }
            $html = '
			    <h2>Environnement Technique</h2>
			    <div class="left">
			        Nombre de postes de travail : ' . $this->nb_poste . ' dont ' . $this->nb_pcfixe . ' PC fixes et
			        ' . $this->nb_portable . ' portables <br />
			        Nombre de sites en France : ' . $this->nb_site_fr . '<br />
				    Nombre de sites à l\'étranger : ' . $this->nb_site_ext . ' <br /><br />
					Nombre d\'imprimantes : ' . $this->nb_imprimante . ' dont : <br />
			        - ' . $this->nb_imprimante_reseaux . ' réseaux <br />
			        - ' . $this->nb_imprimante_local . ' local <br />
			        - ' . $this->nb_imprimante_copieur . ' copieur <br />
					Nombre d\'utilisateurs : ' . $this->nb_utilisateur . '<br />
			        Nombre de sites en France : ' . $this->nb_site_fr . '<br />
				    Nombre de sites à l\'étranger : ' . $this->nb_site_ext . '<br /><br />
					<h3>Tâches particulières attendues : </h3> ' . $this->commentaire_tache . '
			    </div>
                <div class="right">
					<h3>Couverture demandée</h3><br />
					Langue(s) de couverture obligatoire(s) :
	                ' . $this->langue . '<br /><br />
					Plage horaire de couverture helpdesk :
	                ' . $this->plage_horaire_hd . '<br /><br />
					Plage horaire de couverture supervision :
	                ' . $this->plage_horaire_sup . '<br /><br />
					Contact privilégié (téléphone, courriel,...) :
	                ' . $this->contact . '<br /><br />
					Réversibilité fournie par le client / Ancien Prestataire : ' . yesno($this->reversibilite) . '<br /><br />
					<h3>Complément d\'informations : </h3> ' . $this->complement . '
			    </div>
				<div class="clearer"></div>
				' . $htmlSysteme . '
				' . $htmlReseaux . '
				' . $htmlPdt . '
';
        } else {
            $html = '
			    <h2>Environnement Technique</h2>
			    <div class="left">
			        Nombre de postes de travail : ' . $this->nb_poste . ' dont ' . $this->nb_pcfixe . ' PC fixes et
			        ' . $this->nb_portable . ' portables <br />
			        Nombre de serveurs : ' . $this->nb_serveur . '<br />
			        Nombre de sites en France : ' . $this->nb_site_fr . '<br />
				    Nombre de sites à l\'étranger : ' . $this->nb_site_ext . '
			    </div>
                <div class="right">			
			        <h3>Complément d\'informations :</h3><br /> ' . $this->complement . ' <br />
			        <h3>Environnement technique : </h3><br /> ' . $this->env_technique . '
			    </div>
';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les types d'environnement de poste de travail
     *
     * @param int Identifiant du type de poste de travail
     *
     * @return string
     */
    public function getTypePdtListe($Id) {
        $pdt[$Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM type_pdt ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_type_pdt . ' ' . $pdt[$ligne->id_type_pdt] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du nom de l'environnement de poste de travail
     *
     * @param int Identifiant du type de poste de travail
     *
     * @return string
     */
    public static function getNomTypePdt($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM type_pdt WHERE Id_type_pdt=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }

    /**
     * Affichage d'une select box contenant les types d'environnement de poste de travail
     *
     * @param int -1 : Inconnu, 0 : NON, 1 : OUI
     *
     * @return string
     */
    public function getYesNoList($Id = -1) {
        $pdt[$Id] = 'selected="selected"';
        $html = '
		    <option value="-1" ' . $pdt[-1] . '>' . UNKNOWN . '</option>
	        <option value="0" ' . $pdt[0] . '>' . NO . '</option>
		    <option value="1" ' . $pdt[1] . '>' . YES . '</option>
		';
        return $html;
    }

    /**
     * Suppression d'un environnement
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM environnement WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement));
        $db->query('DELETE FROM env_couverture WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement));
        $db->query('DELETE FROM env_pdt WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement));
        $db->query('DELETE FROM env_reseaux WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement));
        $db->query('DELETE FROM env_serveur WHERE Id_environnement = ' . mysql_real_escape_string((int) $this->Id_environnement));
    }

}

?>
