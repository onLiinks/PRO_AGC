<?php

/**
 * Fichier Session.php
 *
 * @author    Frédérique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Session
 */
class Session {

    /**
     * Identifiant de la session
     *
     * @access public
     * @var int
     */
    public $Id_session;

    /**
     * Identifiants des opportunités rattachées à la session
     *
     * @access public
     * @var array
     */
    public $Ids_opportunites;

    /**
     * Nom de la session
     *
     * @access public
     * @var string
     */
    public $nom_session;

    /**
     * Créateur de la session
     *
     * @access public
     * @var string
     */
    public $createur;

    /**
     * Type de contrat de la session
     *
     * @access private
     * @var int
     */
    private $type;

    /**
     * Description de la session
     *
     * @access private
     * @var string
     */
    private $description;

    /**
     * Indique si la session est une archive
     *
     * @access private
     * @var int
     */
    private $archive;

    /**
     * Identifiant de la proposition commercial de la session
     *
     * @access private
     * @var int
     */
    private $Id_propSession;

    /**
     * Identifiant du planning de la session
     *
     * @access public
     * @var int 
     */
    public $Id_planning;

    /**
     * Identifiant de l'intitulé de la session
     *
     * @access public
     * @var int
     */
    public $Id_intitule;

    /**
     * Ville où se déroule la session
     *
     * @access public
     * @var string
     */
    public $ville;

    /**
     * Code postal de la ville où se déroule la session
     *
     * @access public
     * @var string
     */
    public $code_postal;

    /**
     * Date de création de la session
     *
     * @access public
     * @var date
     */
    public $date_creation;

    /**
     * Date de la dernière modification de la session
     *
     * @access public
     * @var date
     */
    public $date_modification;

    /**
     * Nombre de personnes inscrites à la session
     *
     * @access public
     * @var int
     */
    public $nb_Inscrits;

    /**
     * Le ou les formateurs de la session
     *
     * @access public
     * @var array
     */
    public $formateur;

    /**
     * Le nombre de ligne formateur/salle
     *
     * @access public
     * @var int
     */
    public $nb_formateur;

    /**
     * La ou les salles de la session
     *
     * @access public
     * @var array
     */
    public $salle;

    /**
     * Tableau contenant les erreurs suite à la création / modification d'une session
     *
     * @access private
     * @var array
     */
    private $erreurs;

    /**
     * Constructeur de la classe session
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la Session
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            $this->formateur = array();
            $this->salle = array();
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_session = '';
                $this->Ids_opportunites = '';
                $this->nom_session = '';
                $this->createur = '';
                $this->archive = 0;
                $this->type = '';
                $this->description = '';
                $this->Id_planning = '';
                $this->Id_propSession = '';
                $this->Id_intitule = '';
                $this->date_creation = '';
                $this->date_modification = '';
                $this->nb_Inscrits = '';
                $this->code_postal = '';
                $this->ville = '';
                $this->nb_formateur = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_session = '';
                $this->nom_session = $tab['nom_session'];
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->type = $tab['type'];
                $this->description = $tab['description'];
                $this->Id_planning = $tab['Id_planning_session'];
                $this->Id_propSession = $tab['Id_propSession'];
                $this->Id_intitule = $tab['Id_intitule'];
                $this->nb_Inscrits = $tab['nb_Inscrits'];
                $this->ville = strtoupper($tab['ville']);
                $this->code_postal = $tab['code_postal'];
                $this->formateur = $tab['formateur'];
                $this->salle = $tab['salle'];
                $this->nb_formateur = $tab['nb_formateur'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM session WHERE Id_session=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_session = $code;
                $this->nom_session = $ligne->nom_session;
                $this->createur = $ligne->createur;
                $this->archive = $ligne->archive;
                $this->type = $ligne->type;
                $this->Id_intitule = $ligne->id_intitule;
                $this->description = $ligne->description;
                $this->date_creation = $ligne->date_creation;
                $this->date_modification = $ligne->date_modification;
                $this->nb_Inscrits = $ligne->nb_inscrits;
                $this->code_postal = $ligne->code_postal;
                $this->ville = $ligne->ville;
                $this->nb_formateur = $ligne->nb_formateur;

                //récup du planning de la session
                $this->Id_planning = $db->query('SELECT Id_plan_session FROM planning_session WHERE Id_session=' . mysql_real_escape_string((int) $code))->fetchRow()->id_plan_session;

                //récup de la partie commerciale de la session
                $this->Id_propSession = $db->query('SELECT Id_propSession FROM proposition_session WHERE Id_session=' . mysql_real_escape_string((int) $code))->fetchRow()->id_propsession;

                //récup des formateurs et des salles
                $result4 = $db->query('SELECT Id_formateur, Id_salle FROM formateur_salle WHERE Id_session=' . mysql_real_escape_string((int) $code));
                while ($ligne4 = $result4->fetchRow()) {
                    $this->formateur[] = $ligne4->id_formateur;
                    $this->salle[] = $ligne4->id_salle;
                }
                
                //récup des formateurs et des salles
                $result = $db->query('SELECT Id_session, Id_opportunite FROM session_opportunite WHERE Id_session=' . mysql_real_escape_string((int) $code));
                $this->Ids_opportunites = array();
                while ($ligne = $result->fetchRow()) {
                    array_push($this->Ids_opportunites, $ligne->id_opportunite);
                }
                
                $result2 = $db->query('SELECT nom, prenom, prix_unitaire, id_compte, Id_affaire FROM participant WHERE Id_session="' . mysql_real_escape_string((int) $this->Id_session) . '" ORDER BY nom');
                $this->participants = array();
                while ($ligne2 = $result2->fetchRow()) {
                    $a = array();
                    $a['nom'] = $ligne2->nom;
                    $a['prenom'] = $ligne2->prenom;
                    $a['prix_unitaire'] = $ligne2->prix_unitaire;
                    $a['compte'] = $ligne2->id_compte;
                    $a['affaire'] = $ligne2->id_affaire;
                    array_push($this->participants, $a);
                }
            }

            /* Cas 4 : un code et un tableau : prendre infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_session = $code;
                $this->nom_session = $tab['nom_session'];
                $this->type = $tab['type'];
                $this->description = $tab['description'];
                $this->Id_planning = $tab['Id_planning_session'];
                $this->Id_propSession = $tab['Id_propSession'];
                $this->Id_intitule = $tab['Id_intitule'];
                $this->nb_Inscrits = $tab['nb_Inscrits'];
                $this->ville = strtoupper($tab['ville']);
                $this->code_postal = $tab['code_postal'];
                $this->formateurs = $tab['formateurs'];
                $this->salles = $tab['salles'];
                $this->formateur = $tab['formateur'];
                $this->salle = $tab['salle'];
                $this->nb_formateur = $tab['nb_formateur'];
                
                $this->participants = array();
                $c = count($tab['nomParticipant']);
                for($i = 0; $i < $c; $i++) {
                    $a = array();
                    $a['nom'] = $tab['nomParticipant'][$i];
                    $a['prenom'] = $tab['prenomParticipant'][$i];
                    $a['prix_unitaire'] = $tab['prix_unitaireParticipant'][$i];
                    $a['compte'] = $tab['compteParticipant'][$i];
                    $a['affaire'] = $tab['affaireParticipant'][$i];
                    array_push($this->participants, $a);
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une session
     *
     * @return string
     */
    public function form() {
        $planning = new PlanningSession($this->Id_planning, array());
        $logistique = new Logistique($this->Id_session, array());
        $select1 = '';
        $select2 = '';
        if ($this->type == 1) {
            $select1 = 'checked';
        } else if ($this->type == 2) {
            $select2 = 'checked';
        }

        $html .= '<h2>Pôle formation | Session de formation </h2><br />
		    <form id="formulaire" name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_session" method="post">
                        <div class="submit">
                            <input type="hidden" name="Id_session" id="Id_session" value="' . (int) $this->Id_session . '" >
                            <input type="hidden" name="createur" value="' . $this->createur . '" />
                            <input type="hidden" id="opType" name="opType" value="sfc"/>
                            <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifSession(this.form)" >
                        </div>';
        
        if($this->Id_session) {
            $html .= '<br /><hr /><br /><h2 onclick="toggleZone(\'opportunite\')" class="cliquable">Opportunités</h2><br />
                      <div id="opportunite">
                            ' . $this->relatedOpportunitiesList(true) .'
                      </div>';
            $listePar = '<div class="clearer"><hr /></div><br />
                    <h2 onclick="toggleZone(' . "'participants'" . ')" class="cliquable">Liste participants</h2><br />
                    <div id="participants" style="display:none">
                        ' . $this->formParticipant() . '<br/>
                        <div id="nb_inscription">
                            <input type="button" onclick="addTrainee()" value="Ajouter Inscription">
                            <b>Nombre d\'inscrits : </b><input type="text" id="nb_inscrit" name="nb_Inscrits" value="' . (int) $this->nb_Inscrits . '">
                        </div>
                    </div>';
            $hideDesc = 'style="display:none"';
        }
        $html .= '
                    <hr /><br />
                    <h2 onclick="toggleZone(' . "'description'" . ')" class="cliquable">Description</h2><br />
                    <div id="description" ' . $hideDesc . '>
                        <div class="left">
                            <span class="infoFormulaire"> * </span> Nom de la session :  
                            <input type="text" id="nom_session" name="nom_session" size="40" value="' . $this->nom_session . '"><br /><br />
                            Type de formation : 
                            <input type="radio" ' . $select1 . ' name="type" value=1>&nbsp;&nbsp;Intra-entreprise
                            <input type="radio" ' . $select2 . ' name="type" value=2>&nbsp;&nbsp;Inter-entreprise
                            <br/><br/>
                        </div>
                        <div class="right">
                            Résumé : <br/><textarea name="description" id="tinyarea1">' . $this->description . '</textarea><br/><br/>
                        </div><br/>					
                        <div class="center" ><br/><br/>
                            ' . $this->trainerRoomForm() . '
                            <br/><br/>
                            <input type="button" onclick="ajoutFormateurSalle()" value="Ajouter Formateur/Salle">
                            <span class="marge"></span>
                            <input type="button" onclick="enleveFormateurSalle()" value="Supprimer Formateur/Salle">
                            <span class="marge"></span>
                            <input type="button" onclick="ouvre_popup2(\'../com/index.php?a=formulaire_formateur&pop=1\', \'fenetreFormateur\')"
                                                             value="Créer un formateur">
                            <span class="marge"></span>
                            <input type="button" onclick="ouvre_popup2(\'../com/index.php?a=formulaire_salle&pop=1\', \'fenetreSalle\')"
                                                             value="Créer une salle"><br/><br/>
                        </div>
                    </div>
                    <div class="clearer"><hr /></div><br />
                    <h2 onclick="toggleZone(' . "'planning'" . ')" class="cliquable">Planning</h2><br />
                    <div id="planning" style="display:none">
                        ' . $planning->form() . '
                    </div>
                    <div class="clearer"><hr /></div><br />
                    <h2 onclick="toggleZone(' . "'logistique'" . ')" class="cliquable">Ressources formatives/Logistique</h2><br />
                    <div id="logistique" style="display:none">
                        ' . $logistique->form() . '
                    </div>
                    ' . $listePar .'
                    <hr />
                    <div class="submit">
                        <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifSession(this.form)" />
                    </div>
                    </form>';
        return $html;
    }

    /**
     * Formulaire d'affichage des champs formateur et salle
     *
     * @return string
     */
    public function trainerRoomForm() {
        $count = count($this->formateur);
        //Si le tableau est déjà rempli : formulaire pour modification, il faut afficher tous les formateur/salle
        if ($count > 0) {
            $html = '';
            $nb = 1;
            //emboitement des div et afichage de chaque binôme formateur/salle
            while ($nb < $count) {
                //Récupération des listes des formateurs et des salles
                $formateur = new Formateur($this->formateur[$nb - 1], '');
                $salle = new Salle($this->salle[$nb - 1], '');
                $nb2 = $nb + 1;

                $html .= '<div id="Dformateur' . $nb . '">
						Formateur : <select onchange="prixFormateur()" id="formateur' . $nb . '" name="formateur[]">
									<option value="">' . TRAINER_SELECT . '</option>
									' . $formateur->getList() . '
									</select><span class="marge"></span>
						Salle : <select onchange="prixSalle()" id="salle' . $nb . '" name="salle[]">
									<option value="">' . ROOM_SELECT . '</option>' . $salle->getList() . '
									</select><span class="marge"></span>
						<input type="button" onclick="majListe(' . $nb . ')" value="Rafraichir listes">
						</div><br/>
						<div id="autreFormateur' . $nb2 . '">';
                ++$nb;
            }
            // ajout du dernier binôme formateur/salle
            $html .= $this->addTrainerRoom($count);
            $nb = 1;
            // fermeture des div
            while ($nb < $count) {
                $html .= '</div>';
                ++$nb;
            }
        }
        // si tableau est vide, affichage d'un seul groupe formateur/salle
        else if ($count == 0) {
            $html .= $this->addTrainerRoom(1);
        }
        return $html;
    }

    /**
     * Ajout de nouveau champs formateur et salle
     * 
     * @param int numéro du groupe formateur/salle
     *
     * @return string
     */
    public function addTrainerRoom($nb) {
        //Numéro du prochain groupe formateur/salle
        $nb2 = $nb + 1;
        //Récupération des listes des formateurs et des salles
        $formateur = new Formateur($this->formateur[$nb - 1], '');
        $salle = new Salle($this->salle[$nb - 1], '');
        $html = '<div id="Dformateur' . $nb . '">
					Formateur : <select onchange="prixFormateur()" id="formateur' . $nb . '" name="formateur[]">
						<option value="">' . TRAINER_SELECT . '</option>
						' . $formateur->getList() . '
					</select><span class="marge"></span>
					Salle : <select onchange="prixSalle()" id="salle' . $nb . '" name="salle[]">
						<option value="">' . ROOM_SELECT . '</option>
						' . $salle->getList() . '
					</select><span class="marge"></span>
					<input type="button" onclick="majListe(' . $nb . ')" value="Rafraichir listes">
				</div><br/>
				<div id="autreFormateur' . $nb2 . '"><input type="hidden" id="nb_formateur" name="nb_formateur" value=' . $nb . '></div>';

        return $html;
    }

    /**
     * Enregistrement des données de la session dans la base de données
     *
     */
    public function save() {
        $db = connecter();
        //construit la requête avec les données de l'instance qui appelle la fonction
        $set = ' SET nom_session = "' . $this->nom_session . '", 
						type = "' . (int) $this->type . '" , 
						description = "' . $this->description . '", 
						ville="' . $this->ville . '", 
						code_postal="' . $this->code_postal . '",
						date_modification="' . DATETIME . '",
						archive="' . (int) $this->archive . '", 
						Id_intitule="' . (int) $this->Id_intitule . '",
						nb_inscrits="' . (int) $this->nb_Inscrits . '"';

        //si la session existait déjà, c'est une mise à jour de la base sinon on insert une nouvelle instance dans la table
        if ((int) $this->Id_session) {
            $requete = 'UPDATE session ' . $set . ' WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session);
            $result1 = $db->query('DELETE FROM formateur_salle WHERE Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '"');
        } else {
            $requete = 'INSERT INTO session ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation= "' . mysql_real_escape_string(DATETIME) . '"';
        }

        //connexion et envoie de la requête
        $result = $db->query($requete);

        //Si on vient juste d'entrer les données dans la session, récupére l'Id de la base de données et on le stocke dans la session
        if ($this->Id_session == '') {
            $this->Id_session = mysql_insert_id();
        }
        $_SESSION['session'] = $this->Id_session;

        //Enregistrement des formateurs et des salles
        $i = 0;
        while ($i < $this->nb_formateur) {
            if (($this->formateur[$i] != '') || ($this->salle[$i] != '')) {
                $result3 = $db->query('INSERT INTO formateur_salle SET Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '", 
                                        Id_formateur = "' . mysql_real_escape_string((int) $this->formateur[$i]) . '",
                                        Id_salle = "' . mysql_real_escape_string((int) $this->salle[$i]) . '"');
            }
            ++$i;
        }
        
        $db->query('DELETE FROM participant WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        //Enregistrement des inscriptions à la session pour l'affaire
        $nb_participant = count($this->participants);
        $i = 0;
        while ($i < $nb_participant) {
            if (($this->participants[$i]['nom'] != '' && $this->participants[$i]['nom'] != ' ') 
                    || ($this->participants[$i]['prix_unitaire'] != 0 && $this->participants[$i]['prix_unitaire'] != '')) {
                $this->participants[$i]['nom'] = strtoupper($this->participants[$i]['nom']);
                $this->participants[$i]['prenom'] = formatPrenom($this->participants[$i]['prenom']);
                $db->query('INSERT INTO participant SET Id_session="' . mysql_real_escape_string($this->Id_session) . '",
                            nom="' . mysql_real_escape_string($this->participants[$i]['nom']) . '",
                            prenom="' . mysql_real_escape_string($this->participants[$i]['prenom']) . '",
                            prix_unitaire=' . mysql_real_escape_string((float) $this->participants[$i]['prix_unitaire']) . ',
                            Id_affaire="' . mysql_real_escape_string($this->participants[$i]['affaire']) . '",
                            Id_compte="' . mysql_real_escape_string($this->participants[$i]['compte']) . '"');
            }
            ++$i;
        }
    }
    
    public function linkToOpportunity($idOpportunite) {
        $op = new Opportunite($idOpportunite);
        $db = connecter();
        $db->query('INSERT INTO session_opportunite SET Id_session = "' . $this->Id_session . '", Id_opportunite = "' . $idOpportunite . '",
                    Id_compte="' . $op->Id_compte . '", reference_affaire="' . $op->reference_affaire . '"');
        array_push($this->Ids_opportunites, $idOpportunite);
    }

    /**
     * Affichage du formulaire de recherche d'une session
     *
     * @return string
     */
    public static function searchForm() {
        //Création de l'instance Intitule pour pouvoir récupérer la liste des intitulés
        $intitule = new Intitule($_SESSION['filtre']['Id_intitule'], array());

        //Champs permettant de selectionner les critéres de recherche
        $html = '<div class="center">
			Id : 
			<input id="Id_session" type="text" onKeyup="afficherSession()" size="2" value="' . $_SESSION['filtre']['Id_session'] . '">
                        &nbsp;&nbsp;
                        N° opportunité :
			<input id="reference_affaire" type="text" onKeyup="afficherSession()" size="6" value="' . $_GET['Id_affaire'] . '">
			&nbsp;&nbsp;
			Nom : 
			<input id="nom_session" type="text" onKeyup="afficherSession()" size="20" value="' . $_SESSION['filtre']['nom_session'] . '">
			&nbsp;&nbsp;
			<select id="Id_intitule" onchange="afficherSession()">
				<option value="">Par Intitulé</option>
				<option value="">----------------------------</option>
				' . $intitule->getList(3) . '
			</select>
			&nbsp;&nbsp;
			Ville : <input id="ville" type="text" onKeyup="afficherSession()" value="' . $_SESSION['filtre']['ville'] . '" >
			&nbsp;&nbsp;
			<select id="type_session" onchange="afficherSession()">
				<option value="">Par type de session</option>
				<option value="">----------------------------</option>
				<option value="1"> Intra-entreprise </option>
				<option value="2"> Inter-entreprise </option>
			</select>
			&nbsp;&nbsp;
			Du <input id="debut" type="text" onfocus="showCalendarControl(this)" size="8" value="' . $_SESSION['filtre']['debut'] . '" >
			&nbsp;&nbsp;
			au <input id="fin" type="text" onfocus="showCalendarControl(this)" size="8" value="' . $_SESSION['filtre']['fin'] . '" >
			&nbsp;&nbsp;
			<input type="button" onclick="afficherSession()" value="Go !">
			</div>';
        return $html;
    }

    /**
     * Liste des sessions correpondant aux critéres recherchées
     *
     * @param int identifiant de la session recherchée
     * @param string partie ou nom complet du nom de la session
     * @param int Type de session recherché
     * @param string partie ou nom complet de la ville de la session
     * @param float  ca recherché
     * @param string type de ca recherché
     * @param float  marge recherchée
     * @param string type de marge recherchée
     * @param int identifiant de l'intitulé recherché
     * @param date date après laquelle la session recherchée commence
     * @param date date avant laquelle la session recherchée finis
     * @param int  nombre d'enregistrement sur une page
     *
     * @return string
     */
    public static function search($Id_session, $reference_affaire, $nom_session, $type_session, $ville, $Id_intitule, $debut, $fin, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_session', 'reference_affaire', 'nom_session', 'type_session', 'ville', 'Id_intitule', 'debut', 'fin', 'output');
        $columns = array(array('Id', 'Id_session'), array('Nom', 'nom_session'), array('Intitulé', 'libelle'),
            array('Type', 'type'), array('Début', 'dateDebut'), array('Fin', 'dateFin'));
        $db = connecter();
        $requete = 'SELECT DISTINCT s.Id_session, s.nom_session, s.type, s.ville,
                    (SELECT libelle FROM intitule WHERE s.Id_intitule = Id_intitule) AS libelle, pls.dateDebut, pls.dateFin,
                    DATE_FORMAT(pls.dateDebut, "%d-%m-%Y") as date_debut_fr,
                    DATE_FORMAT(pls.dateFin, "%d-%m-%Y") as date_fin_fr
                    FROM session AS s
                    INNER JOIN planning_session AS pls ON s.Id_session=pls.Id_session
                    LEFT JOIN session_opportunite so ON so.Id_session = s.Id_session
                    WHERE s.archive=0';

        if ($Id_session) {
            $requete .= ' AND s.Id_session =' . (int) $Id_session . '';
        }
        if ($nom_session != '') {
            $requete .= ' AND s.nom_session LIKE "%' . $nom_session . '%"';
        }
        if ($type_session) {
            $requete .= ' AND s.type ="' . (int) $type_session . '"';
        }
        if ($debut) {
            $requete .= ' AND pls.dateDebut >="' . DateMysqltoFr($debut, 'mysql') . '"';
        }
        if ($fin) {
            $requete .= ' AND pls.dateFin <="' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        if ($ville) {
            $requete .= ' AND s.ville LIKE "%' . $ville . '%"';
        }
        if ($Id_intitule) {
            $requete .= ' AND s.Id_intitule =' . (int) $Id_intitule;
        }
        if ($reference_affaire) {
            $requete .= ' AND so.reference_affaire = ' . (int) $reference_affaire;
        }

        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if ($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        } else {
            $paramsOrder .= 'orderBy=Id_session';
            $orderBy = 'Id_session';
        }
        if ($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        } else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;

        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherSession({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterSession&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if ($value[1] == $output['orderBy'])
                        if ($output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        } else {
                            $direction = 'DESC';
                            $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        } else if (!$output['orderBy']) {
                        $direction = 'ASC';
                        $img['Id_session'] = '<img src="' . IMG_DESC . '" />';
                    } else {
                        $direction = 'ASC';
                    }
                    if ($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherSession({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;

                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_session'] . '</td>
                            <td>' . $ligne['nom_session'] . '</td>
                            <td>' . $ligne['libelle'] . '</td>
                            <td>' . self::showType($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['date_debut_fr'] . '</td>
                            <td>' . $ligne['date_fin_fr'] . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        } elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="sessions.csv"');

            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['id_session'] . ';';
                echo '"' . $ligne['nom_session'] . '";';
                echo '"' . $ligne['libelle'] . '";';
                echo '"' . self::showType($ligne, array('csv' => true)) . '";';
                echo $ligne['date_debut_fr'] . ';';
                echo $ligne['date_fin_fr'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Archivage d'une session
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE session SET archive="1" WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
    }

    /**
     * Suppression d'une session
     */
    public function delete() {
        $db = connecter();
        $ligne = $db->query('SELECT Id_plan_session FROM planning_session WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session))->fetchRow();
        $db->query('DELETE FROM planning_date WHERE Id_plan_session = ' . mysql_real_escape_string((int) $ligne->id_plan_session));
        $db->query('DELETE FROM periode_session WHERE Id_planning_session = ' . mysql_real_escape_string((int) $ligne->id_plan_session));
        $db->query('DELETE FROM planning_session WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        $db->query('DELETE FROM formateur_salle WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        $db->query('DELETE FROM proposition_session WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        $db->query('DELETE FROM logistique WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        $db->query('DELETE FROM doc_formation WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        $db->query('DELETE FROM session WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
    }

    /**
     * Affichage des données d'une session en consultation
     *
     * @return string
     */
    public function consultation() {
        $html = '<input type="hidden" name="Id_session" id="Id_session" value="' . (int) $this->Id_session . '" >
            <h2>' . $this->nom_session . ' </h2><br />';

        //Zone détail de la session
        $html .= '<div class="center">
						Session ';
        if ($this->type == 1) {
            $html .='intra';
        } else if ($this->type == 2) {
            $html .='inter';
        }

        $html .= '-entreprise</div>';
        $html .= '<br /><hr /><br /><h2>Opportunités</h2><br />
                      <div id="opportunite">
                            ' . $this->relatedOpportunitiesList(false) .'
                      </div>';
        //zone description
        $html .= '<hr /><br /><h2>Description</h2><br />
                        <div class="left">';

        //affichage des formateurs et des salles
        $html .= '<table >
					<tr align="left">
						<td  height="20" ><b>Formateur(s)</td>
						<td  height="20"><b>Salle(s)</td>
					</tr>';

        $nb = count($this->formateur);
        $i = 0;
        while ($i < $nb) {
            if (!$this->formateur[$i]) {
                $html .= '<tr align="left"><td>Non déterminé</td>';
            } else {
                $html .= '<tr align="left">
							<td>' . Formateur::getLastName($this->formateur[$i]) . ' ' . Formateur::getName($this->formateur[$i]) . '</td>';
            }
            if (!$this->salle[$i]) {
                $html .= '<td>Non déterminé</td></tr>';
            } else {
                $html .= '<td>' . Salle::getName($this->salle[$i]) . ' - ' . Salle::getPlace($this->salle[$i]) . '</td></tr>';
            }
            ++$i;
        }

        $html .= '</table><br/><br/></div>
				  <div class="right">
				      <b>Résumé :</b> <br/><br/>' . $this->description . '<br/><br/>
				  </div><br/><br/><br/>';

        //zone planning
        $planning = new PlanningSession($this->Id_planning, array());
        $html .= '<div class="center"><hr /></div><br />
		<h2>Planning</h2><br /><br />
				  ' . $planning->consultation();

        //zone ressource formative/logistique
        $logistique = new Logistique($this->Id_session, array());
        $edition = new EditionSession($this->Id_session, array());

        $html .= '<div class="center"><hr /><br />
					<h2>Ressources formatives/Logistique</h2><br /><br/>
						' . $logistique->consultation() . '
					</div><br/>' . $edition->documentsList() . '<br/><br/>';

        //zone Dépense et ca (propSession)
        $propSession = new PropSession($this->Id_propSession, array());
        $html .= '<div class="center"><hr /><br />
					<h2>Liste participants</h2></div><br />
						' . $this->sessionParticipantList() . '
					<br/><br/>';
        return $html;
    }

    /**
     * Liste des sessions disponibles pour afficher dans le formulaire de saisie/mofification d'une affaire
     *
     * @param int identifiant de l'affaire où la liste va être affichée
     *
     * @return string
     */
    public static function getAvailableList($Id_affaire, $Id_session = null) {
        $db = connecter();
        //récupération de la session associée à cette affaire si elle existe déjà
        $ligne = $db->query('SELECT Id_session FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow();

        if ($Id_session == null) {
            $Id_session = $ligne->id_session;
        }
        //Si une session est associée à l'affaire, récupération et affichage des info de la session
        if ($Id_session) {
            $select[$Id_session] = 'selected="selected"';
            $ligne2 = $db->query('SELECT type, ville, code_postal, Id_intitule FROM session WHERE Id_session=' . mysql_real_escape_string((int) $Id_session))->fetchRow();
            if ($ligne2->type == 1) {
                $type = 'intra-entreprise';
            } else if ($ligne2->type == 2) {
                $type = 'inter-entreprise';
            }
            $info = '<input type="hidden" id="Id_sessionActuel" name="Id_sessionActuel" value="' . $ligne->id_session . '">
					<div id="infoSessionDescription"><br/>	Session ' . $type . '<br/>
					<b>Intitulé : </b>
					<input type="text" id="libelle_intitule" name="libelle_intitule"
							value="' . Intitule::getLibelle($ligne2->id_intitule) . '" readonly=true>
					<br/><input type="hidden" id="Id_intitule" name="Id_intitule" value="' . $ligne2->id_intitule . '"><br/>
					<b>Ville : </b><input type="text" id="ville" name="ville" value="' . $ligne2->ville . '" readonly=true>
					<span class="marge"></span>
					<b>Code postal : </b><input type="text" id="cp" name="cp" value="' . $ligne2->code_postal . '" readonly=true ><br/><br/>
					</div>';
        }
        // si il n'y a pas de session associée, les champs intitulé, ville et code postal sont passés en champs cachés 
        //(sinon absence de ces champs dans le formulaire de l'affaire, ce qui pose problème lors de la vérification du formulaire)
        else {
            $info = '<div id="infoSessionDescription">
						<input type="hidden" id="Id_intitule" name="Id_intitule" value="">
						<input type="hidden" id="ville" name="ville" value=""><input type="hidden" id="cp" name="cp" value="">
					</div>';
        }

        //Récupération des sessions intra-entreprise et ajout à la liste
        $html = '<div id="listeSession"> <select id="Id_session" name="Id_session" onChange="infoSession()">
					<option value=" ">' . SESSION_SELECT . '</option>
					<option value=" ">----------------------------------------</option>
					<option value=" ">** ' . INTRA_SESSION . ' **</option>
					<option value=" ">----------------------------------------</option>';

        $result2 = $db->query('SELECT Id_session, nom_session FROM session WHERE archive = "0" AND type = "1"');
        //Vérification que les sessions intra ne sont pas déjà associées à une affaire, si oui elles ne sont pas 
        //ajoutées à la liste des sessions disponibles
        while ($ligne2 = $result2->fetchRow()) {
            $ligne3 = $db->query('SELECT Id_affaire FROM affaire WHERE Id_session = "' . mysql_real_escape_string($ligne2->id_session) . '"')->fetchRow();
            if (!count($ligne3) || ($ligne3->id_affaire == $Id_affaire)) {
                $html .= '<option value="' . (int) $ligne2->id_session . '" ' . $select[$ligne2->id_session] . '>' . $ligne2->id_session .
                        '&nbsp;-&nbsp;' . $ligne2->nom_session . '</option>';
            }
        }

        //Récupération des sessions inter-entreprise et ajout à la liste
        $html .= '<option value=" "> </option>
				  <option value=" ">----------------------------------------</option>
				  <option value=" ">** ' . INTER_SESSION . ' **</option>
				  <option value=" ">----------------------------------------</option>';

        $result2 = $db->query('SELECT Id_session, nom_session FROM session WHERE archive = "0" AND type = "2"');
        while ($ligne2 = $result2->fetchRow()) {
            $html .= '<option value="' . (int) $ligne2->id_session . '" ' . $select[$ligne2->id_session] . '>' . $ligne2->id_session .
                    '&nbsp;-&nbsp;' . $ligne2->nom_session . '</option>';
        }

        //Récupération des sessions sans type et ajout à la iste
        $html .= '<option value=" "> </option>
				  <option value=" ">----------------------------------------</option>
				  <option value=" ">** ' . WITHOUT_TYPE_SESSION . ' **</option>
				  <option value=" ">----------------------------------------</option>';

        $result2 = $db->query('SELECT Id_session, nom_session FROM session WHERE archive = "0" AND type = "0"');
        while ($ligne2 = $result2->fetchRow()) {
            $html .= '<option value="' . (int) $ligne2->id_session . '" ' . $select[$ligne2->id_session] . '>' . $ligne2->id_session .
                    '&nbsp;-&nbsp;' . $ligne2->nom_session . '</option>';
        }

        $html .= '</select>
				<span class="marge"></span>
				<input type="button" onclick="rafraichirListe()" value="Rafraichir"><br/><br/>
				<span class="marge"></span>
				<input type="button" onclick="popUpSession()" value="Voir la session">
				<span class="marge"></span>
				<input type="button" onclick="ouvre_popup2(\'../com/index.php?a=formulaire_session&pop=1\', \'creationSession\')"
						value="Créer session">
				<span class="marge"></span><span class="marge"></span>
				<input type="button" onclick="popUpModifSession()" value="Modifier la session"><br/>
				' . $info . '</div>';
        return $html;
    }

    /**
     * Information de base de la session à afficher et joindre dans l'affaire
     *
     * @return string
     */
    public function infoSession() {
        if ($this->type == 1) {
            $type = 'intra';
        } elseif ($this->type == 2) {
            $type = 'inter';
        }
        $html = '<br/>Session ' . $type . '-entreprise <br/>
				<b>Intitulé : </b>
				<input type="text" id="Libelle_intitule" name="Libelle_intitule" value="' . Intitule::getLibelle($this->Id_intitule) . '" readonly=true>
				<br/><input type="hidden" id="Id_intitule" name="Id_intitule" value="' . $this->Id_intitule . '"><br/>
				<b>Ville : </b><input type="text" id="ville" name="ville" value="' . $this->ville . '" readonly=true >
				<span class="marge"></span>
				<b>Code postal : </b><input type="text" id="cp" name="cp" value="' . $this->code_postal . '" readonly=true ><br/><br/>';
        return $html;
    }

    /**
     * Information de la session à afficher dans la fiche consultation d'une affaire qui lui est associée
     *
     * @param int identifiant de l'affaire à afficher en consultation
     *
     * @return string
     */
    public static function consultationInfoSession($Id_affaire) {
        $db = connecter();
        //récupération de la session associée à l'affaire envoyé en paramètre
        $ligne = $db->query('SELECT Id_session FROM affaire WHERE Id_affaire=' . mysql_real_escape_string((int) $Id_affaire))->fetchRow();
        //récupération des données de la session
        $ligne2 = $db->query('SELECT Id_session, nom_session, type FROM session WHERE Id_session=' . mysql_real_escape_string((int) $ligne->id_session))->fetchRow();

        if ($ligne2->type == 1) {
            $type = 'session intra-entreprise';
        } else if ($ligne2->type == 2) {
            $type = 'session inter-entreprise';
        }
        //affichage des données
        $html = '<b>Session n°' . $ligne2->id_session . '</b> : ' . $ligne2->nom_session . '<br/>
				' . $type . ' <br/>';
        return $html;
    }

    /**
     * Affichage de la liste des participants à la session dans le formulaire de la session ou dans la consultation 
     * de la session
     *
     * @return string
     */
    public function sessionParticipantList() {
        $count = count($this->participants);
        //Si il n'y a pas d'inscrit
        if ($count <= 0) {
            $html = '<h3>Il n\'y a pas de participants inscrits</h3>';
        } else { //si il y a des inscrits
            $html = '<table>
                        <tr>
                                <td width="20" height="25"></td>
                                <td width="20" height="25"></td>
                                <td width="110" height="25" align="left"><b>Nom</b></td>
                                <td width="110" height="25" align="left"><b>Prénom</b></td>
                                <td width="200" height="25" align="left"><b>Société</b></td>
                                <td width="100" height="25"></td>
                        </tr>';
            $i = 0;
            //affichage des participants
            while ($i < $count) {
                $o = new Opportunite($this->participants[$i]['affaire']);
                $c = CompteFactory::create(null, $o->Id_compte);
                $j = ($i % 2 == 0) ? 'even' : 'odd';
                $html .='<tr>
                            <td width="20" height="25"></td>
                            <td width="20" height="25" class="row' . $j . '"><b>' . ($i + 1) . '-</b></td>
                            <td width="110" height="25" align="left" class="row' . $j . '">' . $this->participants[$i]['nom'] . '</td>
                            <td width="110" height="25" align="left" class="row' . $j . '">' . $this->participants[$i]['prenom'] . '</td>
                            <td width="200" height="25" align="left" class="row' . $j . '">' . $c->nom . '</td>
                            <td width="100" height="25"></td>
                        </tr>';
                ++$i;
            }
            $html .='</table>';
        }
        return $html;
    }
    
    /**
     * Formulaire pour inscriptions pour les propositions commerciales du pole formation
     *
     * @return string
     */
    public function formParticipant() {
        $oppies = $this->getOpportunitiesList();
        $count = count($this->participants);
        //Si le tableau est déjà rempli : formulaire pour modification, il faut afficher toutes les dinscriptions
        if ($count > 0) {
            $html = '';
            $cpt = 0;
            $nb = 1;
            $html .= '<table id="tabTrainee" style="width:auto;">';
            //emboitement des div et afichage de chaque inscription
            while ($nb <= $count) {
                $html .= '
                    <tr id="inscription' . $nb . '">
                        <td><b>' . $nb . '-</b></td>
                        <td><input id="nomParticipant' . $nb . '" name="nomParticipant[]" type="text" value="' . $this->participants[$cpt]['nom'] . '"></td>
                        <td><input id="prenomParticipant' . $nb . '" name="prenomParticipant[]" type="text" value="' . $this->participants[$cpt]['prenom'] . '"></td>
                        <td><select id="affaireParticipant'.$nb.'" name="affaireParticipant[]">' . $oppies .'</select></td>
                        <td>
                            <input type="button" onclick="deleteTrainee(' . $nb . ')" value="Supprimer Inscription">
                            <script>
                                var options = $$(\'select#affaireParticipant'.$nb.' option\');
                                var len = options.length;
                                for (var i = 0; i < len; i++) {
                                    if(options[i].value == "'. $this->participants[$cpt]['affaire'] .'")
                                        options[i].selected = true;
                                }
                            </script>
                        </td>
                    </tr>';
                ++$nb;
                ++$cpt;
            }
            //$html .= $this->addParticipant($count);
            $html .= '</table>';
            
        }
        // si tableau est vide, affichage d'une seule inscription
        elseif ($count == 0) {
            $html .= '<table id="tabTrainee" style="width:auto;"></table>';
        }

        return $html;
    }

    /**
     * Formulaire pour ajouter des inscriptions pour les propositions commerciales du pole formation
     *
     * @param int numero de la nouvelle inscription
     *
     * @return string
     */
    public function addParticipant($nb) {
        //numéro de la prochaine inscription
        $nb2 = $nb + 1;
        //formulaire pour une nouvelle inscription
        $html = '
            <tr id="inscription' . $nb . '">
                <td><b>' . $nb . '-</b></td>
                <td><input id="nomParticipant' . $nb . '" name="nomParticipant[]" type="text" value=""></td>
                <td><input id="prenomParticipant' . $nb . '" name="prenomParticipant[]" type="text" value=""></td>
                <td><select id="affaireParticipant'.$nb.'" name="affaireParticipant[]">' . $this->getOpportunitiesList($nb) .'</select></td>
                <td><input type="button" onclick="deleteTrainee(' . $nb . ')" value="Supprimer Inscription" /></td>
            </tr>';

        return $html;
    }

    /**
     * Mise à jour des informations de la session lors de l'enregistrement d'une affaire
     * 
     */
    public function updateSession() {
        $propSession = new PropSession($this->Id_propSession, array());
        $propSession->updateSession($this->type);
    }

    /**
     * Listing des opportunités associées à une session
     *
     * @return string
     */
    public function relatedOpportunitiesList($add= false) {
        $i = 0;
        $html .= '
                <table class="hovercolored">
                <tr>
                    <th>Identifiant</th>
                    <th>Compte</th>
                    <th>Intitulé</th>
                    <th>Commercial</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>CA</th>
                </tr>';
        foreach ($this->Ids_opportunites as $idOpportunite) {
            $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
            $opportunite = new Opportunite($idOpportunite, null);
            $c = new Utilisateur($opportunite->commercial, array());
            $html .= '
                        <tr ' . $j . '>
                            <td>' . $opportunite->reference_affaire . '</td>
                            <td>' . $opportunite->nomCompte . '</td>
                            <td>' . $opportunite->Id_intitule . '</td>
                            <td>' . $c->prenom . ' ' . $c->nom . '</td>
                            <td>' . FormatageDate($opportunite->date_debut) . '</td>
                            <td>' . FormatageDate($opportunite->date_fin_commande) . '</td>
                            <td>' . $opportunite->ca . '</td>
                            <td><a onclick="updateCaseInformation(\'' . $opportunite->Id_affaire . '\');return false" class="cliquable">Détails</a></td>
                        </tr>';
            $i++;
        }
        $html .= '</table><br />';
        if($add) {
            $html .= 'Compte : <input id="prefix" type="text" size="4" onkeyup="prefixCompteCD(1)">
                          <span id="compte">
                            <select id="Id_compte" name="Id_compte" onchange="showCaseList(this.value, 1);">
                                <option value="">' . CUSTOMERS_SELECT . '</option>
                                <option value="">-------------------------</option>
                            </select>
                          </span>&nbsp;&nbsp;';
            $html .= '<span id="affaire">Opportunité : 
                        <select name="Id_affaire" id="Id_affaire" onchange="updateCaseInformation(this.value);">
                            <option value="">Sélectionner une opportunité</option>
                            <option value="">-------------------------</option>
                        </select>
                        </span>&nbsp;<img src="../ui/images/plus_icon.jpg" onClick="linkToOpportunity($F(\'Id_session\'),$F(\'Id_affaire\'))" />
                        <script>prefixCompteCD(1);</script>';
        }
        $html .= '<br /><br /><div id="infoOpp"></div>';
        return $html;
    }
    
    /**
     * Listing des affaires associées à une session pour interdire ou permettre la suppression de la session
     *
     * @return string
     */
    public function relatedCasesList() {
        //récupération des affaires associées à la session
        $db = connecter();
        $result = $db->query('SELECT Id_affaire FROM affaire WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        //création de la liste des affaires associées à la session
        while ($ligne = $result->fetchRow()) {
            $liste .= '- ' . $ligne->id_affaire . '\n\t\t';
        }
        return $liste;
    }

    /**
     * Mise à jour des données associées à la session dans les affaires associées lors de la modification d'une session
     *
     */
    public function updateRelatedCases() {
        //récupération des affaires associées à la session
        $db = connecter();
        $result = $db->query('SELECT affaire.Id_affaire FROM affaire INNER JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire
		WHERE Id_pole=3 AND archive=0 AND Id_session IS NOT NULL AND Id_session!=0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        while ($ligne = $result->fetchRow()) {
            // 1 - mise à jours des informations de la description (intitulé, ville, code postal)
            $result1 = $db->query('UPDATE description SET Id_intitule=' . mysql_real_escape_string((int) $this->Id_intitule) . ', ville="' . mysql_real_escape_string($this->ville) . '", cp="' . mysql_real_escape_string($this->code_postal) . '"
					     WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire));
        }
        // 2 - mise à jours des informations du planning (dates de début et fin, durée)
        $planning = new PlanningSession($this->Id_planning, array());
        $planning->updateRelatedCases();
        // 3 - mise à jours des informations financières (calcul charges au prorata, calcul marge)
        $propSession = new PropSession($this->Id_propSession, array());
        $propSession->updateRelatedCases($this->nb_Inscrits, $this->type);
    }
    
    /**
     * Récupération de la liste des comptes
     *
     */
    public function getAccountList($i, $idCompte) {
        //récupération des affaires associées à la session
        $db = connecter();
        $select[$idCompte] = 'selected="selected"';
        $result = $db->query('SELECT DISTINCT Id_compte FROM session_opportunite WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $html .= '<select id="compteParticipant'.$i.'" name="compteParticipant[]">';
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $html .= '<option value="'. $ligne->id_compte .'" ' . $select[$ligne->id_compte] . '>'. $compte->nom .'</option>';
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * Récupération de la liste des comptes
     *
     */
    public function getOpportunitiesList() {
        //récupération des affaires associées à la session
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_opportunite FROM session_opportunite WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        while ($ligne = $result->fetchRow()) {
            $op = new Opportunite($ligne->id_opportunite);
            $html .= '<option value="'. $ligne->id_opportunite .'" ' . $select[$ligne->id_opportunite] . '>'. $op->nomCompte .' | ' . $op->Id_intitule .' | ' . $op->reference_affaire . '</option>';
        }
        return $html;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showType($record) {
        if ($record['type'] == 1) {
            return 'Intra';
        } else if ($record['type'] == 2) {
            return 'Inter';
        }
    }

    public function showButtons($record) {
        $htmlAdmin = '
            <td><a href="index.php?a=infoSession&amp;Id_session=' . $record['id_session'] . '"><img src="' . IMG_CONSULT . '"></a></td>
            <td><a href="../com/index.php?a=afficherSession&amp;Id_session=' . $record['id_session'] . '"><img src="' . IMG_EDIT . '"></a></td>
            <td>
                <a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' la session n° ' . $record['id_session'] . ' ?\')) { location.replace(\'../for/index.php?a=archiverSession&amp;Id_session=' . $record['id_session'] . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a>
            </td>';
        return $htmlAdmin;
    }

}

?>