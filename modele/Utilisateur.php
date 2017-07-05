<?php

/**
 * Fichier utilisateur.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe utilisateur
 */
class Utilisateur {

    /**
     * Identifiant de l'utilisateur
     *
     * @access private
     * @var string
     */
    private $Id_utilisateur;
    /**
     * Nom de l'utilisateur
     *
     * @access public
     * @var string
     */
    public $nom;
    /**
     * Prénom de l'utilisateur
     *
     * @access public
     * @var string
     */
    public $prenom;
    /**
     * Mail de l'utilisateur
     *
     * @access private
     * @var string 
     */
    public $mail;
    /**
     * Bloc Notes de l'utilisateur
     *
     * @access public
     * @var string
     */
    public $blocnotes;
    /**
     * Indique si l'utilisateur est archivé
     *
     * @access private
     * @var int
     */
    private $archive;
    /**
     * Identifiant de l'agence de l'utilisateur
     *
     * @access public
     * @var string
     */
    public $Id_agence;
    /**
     * Dernière visite de l'utilisateur
     *
     * @access private
     * @var datetime
     */
    public $last_visit;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'un utilisateur
     *
     * @access private
     * @var array
     */
    private $erreurs;

    /**
     * Constructeur de la classe utilisateur
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du utilisateur
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_utilisateur = '';
                $this->nom = '';
                $this->prenom = '';
                $this->mail = '';
                $this->groupe_ad = array();
                $this->Id_agence = '';
                $this->blocnotes = '';
                $this->archive = 0;
                $this->last_visit = '';
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $this->Id_utilisateur = $code;
                $db = connecter();
                $ligne = $db->query('SELECT * FROM utilisateur WHERE Id_utilisateur = "' . mysql_real_escape_string($code) . '"')->fetchRow();
                $this->nom = $ligne->nom;
                $this->prenom = $ligne->prenom;
                $this->mail = $ligne->mail;
                $this->groupe_ad = $ligne->groupe_ad;
                $this->Id_agence = $ligne->id_agence;
                $this->blocnotes = $ligne->blocnotes;
                $this->last_visit = $ligne->last_visit;
                $this->archive = $ligne->archive;
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire d'affichage / modification d'un utilisateur
     *
     * @return string
     */
    public function consultation() {
        $html = '
		    <h2>Informations Personnelles</h2>
            <div class="left">
                Nom : ' . $this->nom . '<br /><br />
				Prénom : ' . $this->prenom . '<br /><br />
			    E-Mail : ' . $this->mail . '<br /><br />
				Agence : ' . Agence::getLibelle($this->Id_agence) . '
		    </div>
			<div class="right">
			    Dernière visite : ' . $this->last_visit . ' <br /><br />
			</div>
			<div class="center">
			<br /><hr /><br />
			<h2>Bloc Notes</h2><br />
			    ' . $this->blocnotes . '
			<br /><hr /><br />	
			<h2>Groupes AD</h2><br />
			    ' . $this->groupe_ad . '<br /><br />
		    </div>';
        return $html;
    }

    /**
     * Affichage du bloc Notes d'un utilisateur
     *
     * @return string
     */
    public function noteBook() {
        $html = '
        <form enctype="multipart/form-data" action="../membre/index.php?a=enregistrerBlocNotes" method="post">
            <textarea name="blocnotes" id="tinyarea1">' . $this->blocnotes . '</textarea>
		    <div class="submit">
	            <input type="submit" value="' . SAVE_BUTTON . '" />
	        </div>
        </form>';
        return $html;
    }

    /**
     * Enregistrement du bloc notes
     *
     * @return string
     */
    public function saveNoteBook() {
        $db = connecter();
        $db->query('UPDATE utilisateur SET blocnotes = "' . mysql_real_escape_string($this->blocnotes) . '" WHERE Id_utilisateur = "' . mysql_real_escape_string($this->Id_utilisateur) . '"');
    }

    /**
     * Recherche d'un utilisateur
     *
     * @return string
     */
    public static function search() {
        $requete = 'SELECT * FROM utilisateur ORDER BY Id_utilisateur';
        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, array('mode' => MODE, 'perPage' => TAILLE_LISTE, 'delta' => DELTA));
        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                $htmlEnteteAdmin = '
				<th>Dernière visite</th>
				<th>Agence</th>
				<th>Groupe AD</th>';
            }
            $html = '
		        <p class="pagination">' . $paged_data['links'] . '</p>
 		        <table class="sortable hovercolored">
		            <tr>
			            <th>Nom</th>
						<th>Prénom</th>
						' . $htmlEnteteAdmin . '
		            </tr>';
            $groupead = array();
            foreach ($paged_data['data'] as $ligne) {
                $htmlGroupeAd = '';
                if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
                    $htmlAdmin = '
			        <td>' . $ligne['last_visit'] . '</td>
			        <td>' . Agence::getLibelle($ligne['id_agence']) . '</td>';
                    $groupead = explode(',', $ligne['groupe_ad']);
                    if (!empty($groupead)) {
                        foreach ($groupead as $i) {
                            $htmlGroupeAd .= GroupeAD::getLibelle($i) . '<br />';
                        }
                    }
                    $htmlAdmin .= '
					<td>' . $htmlGroupeAd . '</td>
			        <td><a href="../gestion/index.php?a=infoUtilisateur&amp;Id_utilisateur=' . $ligne['id_utilisateur'] . '"><img src="' . IMG_CONSULT . '"></a></td>
                                <td><a href="../gestion/index.php?a=changerUtilisateur&amp;user=' . $ligne['id_utilisateur'] . '"><img src="' . IMG_IMPERSONATE . '"></a></td>';
                    if ($ligne['archive'] == 0) {
                        $j = '';
                        $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' cet utilisateur ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $ligne['id_utilisateur'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a></td>';
                    } elseif ($ligne['archive'] == 1) {
                        $j = 'orange';
                        $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' cet utilisateur ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $ligne['id_utilisateur'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '"></a></td>';
                    }
                }
                $html .= '
                <tr class="row' . $j . '">
	                <td>' . $ligne['nom'] . '</td>
					<td>' . $ligne['prenom'] . '</td>
			        ' . $htmlAdmin . '
                </tr>';
            }
            $html .= '</table><p class="pagination">' . $paged_data['links'] . '</p>';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les utilisateurs
     *
     * @param string type du groupe demandé
     *
     * @return string
     */
    public function getList($type) {
        $utilisateur[$this->Id_utilisateur] = 'selected="selected"';
        $groupeAd = self::getGroupAdList($type);
        $db = connecter();
        $result = $db->query('SELECT Id_utilisateur,nom,prenom, groupe_ad, mail FROM utilisateur ORDER BY nom');
        while ($ligne = $result->fetchRow()) {
            $tab = array_intersect(explode(',', $ligne->groupe_ad), $groupeAd);
            if (!empty($tab)) {
                $html .= '<option value=' . strtolower($ligne->id_utilisateur) . ' ' . $utilisateur[strtolower($ligne->id_utilisateur)] . '>' . $ligne->nom . ' ' . $ligne->prenom . ' (' . $ligne->mail . ')' . '</option>';
            }
        }
        if($type == 'RH') {
            $html .= '<option value="Manpower IT" ' . $utilisateur['Manpower IT'] . '>Manpower IT</option>';
            $html .= '<option value="Autre ss-traitant" ' . $utilisateur['Autre ss-traitant'] . '>Autre ss-traitant</option>';
        }
        return $html;
    }
    
    /**
     * Renvoit une liste des utilisateurs au format JSON
     *
     * @param string type du groupe demandé
     *
     * @return string
     */
    public function getJsonList($type) {
        $groupeAd = self::getGroupAdList($type);
        $db = connecter();
        $result = $db->query('SELECT Id_utilisateur,nom,prenom, groupe_ad, mail FROM utilisateur ORDER BY nom');
        $html .= '[';
        while ($ligne = $result->fetchRow()) {
            $tab = array_intersect(explode(',', $ligne->groupe_ad), $groupeAd);
            if (!empty($tab)) {
                $n = str_split($ligne->nom, 1);
                $p = str_split($ligne->prenom, 1);
                $m = str_split($ligne->mail, 1);
                
                $html .= '["' . strtoupper($p[0]) . '' . strtoupper($n[0]) . '","' . strtoupper($p[0]) . '' . strtoupper($n[0]) . '"],';
            }
        }
        $html .= ']';
        return $html;
    }

    /**
     * Renvoie la liste des groupes AD correspondant au type
     *
     * @param string type du groupe demandé
     *
     * @return string
     */
    public static function getGroupAdList($i) {
        switch ($i) {
            case 'COM':
                $groupeAd = array(3, 4, 5, 13, 14);
                break;
            case 'FOR':
                $groupeAd = array(4, 5);
                break;
            case 'OP':
                $groupeAd = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 23);
                break;
            case 'RH':
                $groupeAd = array(15, 16, 22, 17);
                break;
            case 'REL':
                $groupeAd = array(18, 19);
                break;
            case 'ADI':
                $groupeAd = array(24);
                break;
        }
        return $groupeAd;
    }

    /**
     * Archivage d'un utilisateur
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE utilisateur SET archive="1" WHERE Id_utilisateur = "' . mysql_real_escape_string($this->Id_utilisateur) . '"');
    }

    /**
     * Desarchivage d'un utilisateur
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE utilisateur SET archive="0" WHERE Id_utilisateur = "' . mysql_real_escape_string($this->Id_utilisateur) . '"');
    }

    /**
     * Droit de modification d'un utilisateur sur une affaire
     */
    public static function getCaseModificationRight($Id_utilisateur, $Id_affaire) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return 1;
        }
        $db = connecter();
        if(is_numeric($Id_affaire)) {
            if ($db->query('SELECT Id_affaire FROM affaire WHERE (createur ="' . mysql_real_escape_string($Id_utilisateur) . '" OR commercial ="' . mysql_real_escape_string($Id_utilisateur) . '" OR redacteur1 ="' . mysql_real_escape_string($Id_utilisateur) . '" OR redacteur2 ="' . mysql_real_escape_string($Id_utilisateur) . '" OR sdm ="' . mysql_real_escape_string($Id_utilisateur) . '") AND Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->numRows()) {
                return 1;
            } else {
                $ag = Affaire::getIdAgence($Id_affaire);
                if (self::isAgencyDirector($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $ag) ||
                    self::isRegionDirector($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $ag)) {
                    return 1;
                }
                $pole = Affaire::getIdPole($Id_affaire);
                $type_contrat = Affaire::getIdTypeContrat($Id_affaire);
                if ($pole == 1 && ($type_contrat == 2 || $type_contrat == 3) && array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(6, 7, 8, 9))) {
                    return 1;
                } elseif ($pole == 4 && array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(11, 12))) {
                    return 1;
                } elseif ($pole == 3 && array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(4, 5))) {
                    return 1;
                } elseif ($_SESSION['societe'] == 'OVIALIS' && array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(19))) {
                    return 1;
                }
                return 0;
            }
        }
        else {
            if ($db->query('SELECT Id_opportunite FROM droit_opportunite 
                WHERE utilisateur ="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_opportunite = "' . mysql_real_escape_string($Id_affaire) . '"')->numRows()) {
                return 1;
            }
            return 0;
        }
        return 0;
    }

    /**
     * Droit de suppression d'un utilisateur sur une affaire
     */
    public static function getCaseDeleteRight($Id_utilisateur, $Id_affaire) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return 1;
        }
        $db = connecter();
        if ($db->query('SELECT Id_affaire FROM affaire WHERE createur ="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->numRows()) {
            return 1;
        }
    }

    /**
     * Droit de modification d'un ordre demission
     */
    public static function getMissionOrderRight($Id_utilisateur, $Id_odm) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15, 16, 22, 18, 19, 20, 21))) {
            return 1;
        }
        $db = connecter();
        return $db->query('SELECT Id_ordre_mission FROM ordre_mission WHERE createur ="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_ordre_mission=' . mysql_real_escape_string((int) $Id_odm))->numRows();
    }

    /**
     * Droit de validation d'une demande de changement
     */
    public static function getDroitValidationDemandeChangement($staff = false) {
        if($staff === true) {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15))) {
                return true;
            }
        }
        else {
            if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15, 16, 22, 20))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Droit d'intégration d'une demande de changement
     */
    public static function getDroitIntegrationDemandeChangement($Id_dc) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15, 16, 22, 20, 21))) {
            return 1;
        }
    }

    /**
     * Droit de modification d'un bilan d'activité
     */
    public static function getDroitBilanActivite($Id_utilisateur, $Id_ba) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return 1;
        }
        $db = connecter();
        return $db->query('SELECT Id_bilan_activite FROM bilan_activite WHERE responsable ="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_bilan_activite=' . mysql_real_escape_string((int) $Id_ba))->numRows();
    }

    /**
     * Droit d'édition d'un bilan d'activité
     */
    public static function getDroitEditionBilanActivite($Id_utilisateur, $Id_ba) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15))) {
            return 1;
        }
        $db = connecter();
        return $db->query('SELECT Id_bilan_activite FROM bilan_activite WHERE (responsable ="' . mysql_real_escape_string($Id_utilisateur) . '" OR commercial="' . mysql_real_escape_string($Id_utilisateur) . '") AND Id_bilan_activite=' . mysql_real_escape_string((int) $Id_ba))->numRows();
    }

    /**
     * Droit de modification d'un rendez vous
     */
    public static function getAppointmentRight($Id_utilisateur, $Id_rendezvous) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return 1;
        }
        $db = connecter();
        return $db->query('SELECT Id_rendezvous FROM rendezvous WHERE createur ="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_rendezvous=' . mysql_real_escape_string((int) $Id_rendezvous))->numRows();
    }

    /**
     * Droit de modification d'un rendez vous
     */
    public static function getActionRight($Id_utilisateur, $Id_action) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            return 1;
        }
        $db = connecter();
        return $db->query('SELECT Id_action FROM action WHERE (createur ="' . mysql_real_escape_string($Id_utilisateur) . '" OR demandeur ="' . mysql_real_escape_string($Id_utilisateur) . '" OR executant ="' . mysql_real_escape_string($Id_utilisateur) . '") AND Id_action=' . mysql_real_escape_string((int) $Id_action))->numRows();
    }

    /**
     * Droit de modification de l'etat technique de l'affaire
     */
    public static function getTechnicalModuleRight($Id_utilisateur) {
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 6, 7, 8, 9))) {
            return 1;
        }
    }

    /**
     * Indique si l'utilisateur est directeur de l'agence passée en paramètre
     */
    public static function isAgencyDirector($Id_utilisateur, $Id_agence) {
        $db = connecter();
        return $db->query('SELECT Id_agence FROM directeur_agence WHERE Id_utilisateur="' . mysql_real_escape_string($Id_utilisateur) . '" AND Id_agence ="' . mysql_real_escape_string($Id_agence) . '"')->numRows();
    }
    
    /**
     * Indique si l'utilisateur est directeur de l'agence passée en paramètre
     */
    public static function isRegionDirector($Id_utilisateur, $Id_agence) {
        $db = connecter();
        return $db->query('SELECT dr.Id_region FROM directeur_region dr 
                            INNER JOIN agence a ON a.Id_region = dr.Id_region 
                            WHERE dr.Id_directeur_region = "' . mysql_real_escape_string($Id_utilisateur) . '" 
                            AND a.Id_agence = "' . mysql_real_escape_string($Id_agence) . '"')->numRows();
    }
    
    /**
     * Récupère le directeur d'une agence
     */
    public static function getAgencyDirector($Id_agence) {
        $db = connecter();
        return $db->query('SELECT Id_utilisateur FROM directeur_agence WHERE Id_agence ="' . mysql_real_escape_string($Id_agence) . '"')->fetchOne();
    }

    /**
     * Récupérer les subordonnés de l'utilisateur courant
     *
     * @return array tableau des identifiants salarie de CEGID des subordonnes de l'utilisateur courant
     */
    public function getSubordonnes() {
        $db = connecter_cegid();
        $db_a = connecter();
        $result = $db->query('SELECT PSE_SALARIE FROM DEPORTSAL WHERE
		PSE_RESPONSABS = "' . mysql_real_escape_string($this->getCegidEmployeeCode()) . '" OR PSE_RESPONSVAR = "' . mysql_real_escape_string($this->getCegidEmployeeCode()) . '"');
        $subordonnes = array();
        while ($ligne = $result->fetchRow()) {
            $subordonnes[] = $ligne->pse_salarie;
        }
        return $subordonnes;
    }

    /**
     * Droit de visibilié sur les candidats Staff
     */
    public function getResourceRight($staff, $Id_etat) {
        if ($staff == 0) {
            return true;
        } else {
            if (in_array($Id_etat, array(8, 9)) && !array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 15, 16, 20))) {
                return false;
            }
            return true;
        }
    }

    /**
     * Affichage de l'identifiant du code salarie de l'utilisateur
     *
     * @return string
     */
    public function getCegidEmployeeCode() {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT US_AUXILIAIRE FROM UTILISAT WHERE US_ABREGE="' . mysql_real_escape_string(strtolower($this->Id_utilisateur)) . '"')->fetchRow()->us_auxiliaire;
    }

    /**
     * Nombre de collaborateurs titulaires associés
     */
    public function nbEmployee() {
        $db = connecter();
        return $db->query('SELECT count(DISTINCT(Id_ressource)) as nb FROM proposition_ressource INNER JOIN
		proposition ON proposition.Id_proposition=proposition_ressource.Id_proposition INNER JOIN 
		affaire ON proposition.Id_affaire=affaire.Id_affaire 
		WHERE type="T" AND Id_statut=8 AND commercial="' . mysql_real_escape_string($this->Id_utilisateur) . '"')->fetchRow()->nb;
    }

    /**
     * Nombre d'affaire selon statut
     */
    public function nbCase($mois, $annee, $Id_statut) {
        $db = connecter();
        $requete = 'SELECT count(Id_affaire) as nb_affaire FROM affaire WHERE commercial="' . mysql_real_escape_string($this->Id_utilisateur) . '"';
        if ($Id_statut) {
            $requete .= ' AND Id_statut =' . mysql_real_escape_string((int) $Id_statut) . '';
        }
        if ($mois) {
            $requete .= ' AND date_creation BETWEEN "' . mysql_real_escape_string($annee) . '-' . mysql_real_escape_string($mois) . '-01' . '" AND "' . mysql_real_escape_string($annee) . '-' . mysql_real_escape_string($mois) . '-31' . '"';
        }
        return $db->query($requete)->fetchRow()->nb_affaire;
    }

    /**
     * Nombre d'affaires non modifiées depuis plus d'un mois selon statut
     *
     * @param Identifiant du statut de l'affaire
     *
     * @return string
     */
    public function nbCaseWithoutModification($Id_statut) {
        $db = connecter();
        $ligne = $db->query('SELECT count(Id_affaire) as nb FROM affaire WHERE date_modification < DATE_ADD(NOW(),INTERVAL -1 MONTH)
		            AND commercial="' . mysql_real_escape_string($this->Id_utilisateur) . '" AND Id_statut="' . mysql_real_escape_string((int) $Id_statut) . '"')->fetchRow();
        $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($this->caseWithoutModification($Id_statut))) . '</div>\', FULLHTML);" onmouseout="return nd();"';
        return '<a ' . $info . '>' . $ligne->nb . '</a>';
    }

    /**
     * Affichage des affaires non modifiées depuis plus d'un mois
     *
     * @param Identifiant du statut de l'affaire
     *
     * @return string
     */
    public function caseWithoutModification($Id_statut) {
        $db = connecter();
        $result = $db->query('SELECT Id_affaire FROM affaire WHERE date_modification < DATE_ADD(NOW(),INTERVAL -1 MONTH)
		 AND commercial="' . mysql_real_escape_string($this->Id_utilisateur) . '" AND Id_statut="' . mysql_real_escape_string((int) $Id_statut) . '"');
        while ($ligne = $result->fetchRow()) {
            $html .= '<a href="index.php?a=modifier_affaire&Id_affaire=' . $ligne->id_affaire . '">' . $ligne->id_affaire . '</a><br />';
        }
        return $html;
    }

    /**
     * Affichage du Nom et du prénom de l'utilisateur
     *
     * @param int Identifiant de l'utilisateur
     *
     * @return string
     */
    public static function getName($i) {
        $utilisateur = new Utilisateur($i, array());
        return $utilisateur->nom . ' ' . $utilisateur->prenom . ' (' . $utilisateur->mail . ')';
    }

    /**
     * Affichage de l'identifiant de l'agence de l'utilisateur
     *
     * @param string Identifiant de l'utilisateur
     *
     * @return int
     */
    public static function getAgency($i) {
        $db = connecter();
        return $db->query('SELECT Id_agence FROM utilisateur WHERE Id_utilisateur="' . mysql_real_escape_string($i) . '"')->fetchRow()->id_agence;
    }

    /**
     * Affichage du login du commercial en fonction de son matricule salarié
     *
     * @param string Identifiant de l'utilisateur
     *
     * @return int
     */
    public static function getLogin($matricule) {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT lower(US_ABREGE) as login FROM SALARIES INNER JOIN UTILISAT ON PSA_SALARIE=UTILISAT.US_AUXILIAIRE
                        WHERE PSA_SALARIE="' . mysql_real_escape_string($matricule) . '"')->fetchRow()->login;
    }

}

?>