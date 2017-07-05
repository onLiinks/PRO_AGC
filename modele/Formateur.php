<?php

/**
 * Fichier Formateur.php
 *
 * @author    Fr�d�rique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * D�claration de la classe Formateur
 */
class Formateur {

    /**
     * Identifiant du formateur
     *
     * @access public
     * @var int 
     */
    public $Id_formateur;
    /**
     * Genre du formateur
     *
     * @access public
     * @var int 
     */
    public $genre;
    /**
     * Nom du formateur
     *
     * @access public
     * @var string
     */
    public $nom;
    /**
     * Pr�nom du formateur
     *
     * @access public
     * @var string
     */
    public $prenom;
    /**
     * Comp�tences du formateur
     *
     * @access public
     * @var string
     */
    public $competences;
    /**
     * Adesse de la page web du formateur
     *
     * @access public
     * @var string
     */
    public $page_web;
    /**
     * Statut du formateur
     *
     * @access public
     * @var int
     */
    public $Id_statut;
    /**
     * Num�ro de t�l�phone fixe du formateur
     *
     * @access public
     * @var string
     */
    public $tel_fixe;
    /**
     * Num�ro de t�l�phone portable du formateur
     *
     * @access public
     * @var string
     */
    public $tel_portable;
    /**
     * Salaire journalier du formateur
     *
     * @access public
     * @var double
     */
    public $salaire;
    /**
     * Mail du formateur
     *
     * @access public
     * @var string
     */
    public $mail;
    /**
     * Soci�t� du formateur
     *
     * @access public
     * @var string
     */
    public $societe;

    /**
     * Constructeur de la classe Formateur
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'instance Formateur
     * @param array Tableau pass� en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_formateur = '';
                $this->genre = '';
                $this->nom = '';
                $this->prenom = '';
                $this->competences = '';
                $this->page_web = '';
                $this->Id_statut = '';
                $this->salaire = '';
                $this->tel_fixe = '';
                $this->tel_portable = '';
                $this->mail = '';
                $this->societe = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode cr�ation  */
            elseif (!$code && !empty($tab)) {
                $this->Id_formateur = '';
                $this->genre = (int) $tab['genre'];
                $this->nom = htmlscperso(stripslashes(strtoupper($tab['nom_formateur'])), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes(formatPrenom($tab['prenom'])), ENT_QUOTES);
                $this->competences = $tab['competences'];
                $this->page_web = $tab['page_web'];
                $this->Id_statut = (int) $tab['Id_statut'];
                $this->salaire = $tab['salaire'];
                $this->tel_fixe = formatTel($tab['tel_fixe']);
                $this->tel_portable = formatTel($tab['tel_portable']);
                $this->mail = $tab['mail'];
                $this->societe = strtoupper($tab['societe']);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de donn�es */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM formateur WHERE Id_formateur=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_formateur = $code;
                $this->genre = $ligne->genre;
                $this->nom = $ligne->nom;
                $this->prenom = $ligne->prenom;
                $this->competences = $ligne->competence;
                $this->page_web = $ligne->page_web;
                $this->Id_statut = $ligne->id_statut;
                $this->salaire = $ligne->salaire;
                $this->tel_fixe = $ligne->tel_fixe;
                $this->tel_portable = $ligne->tel_portable;
                $this->mail = $ligne->mail;
                $this->societe = $ligne->societe;
            }

            /* Cas 4 : un code et un tableau : prendre infos dans la base de donn�es et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_formateur = $code;
                $this->genre = (int) $tab['genre'];
                $this->nom = htmlscperso(stripslashes(strtoupper($tab['nom_formateur'])), ENT_QUOTES);
                $this->prenom = htmlscperso(stripslashes(formatPrenom($tab['prenom'])), ENT_QUOTES);
                $this->competences = $tab['competences'];
                $this->page_web = $tab['page_web'];
                $this->Id_statut = (int) $tab['Id_statut'];
                $this->salaire = $tab['salaire'];
                $this->tel_fixe = formatTel($tab['tel_fixe']);
                $this->tel_portable = formatTel($tab['tel_portable']);
                $this->mail = $tab['mail'];
                $this->societe = strtoupper($tab['societe']);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de cr�ation / modification d'une session
     *
     * @return string
     */
    public function form() {
        $select[$this->genre] = 'checked';
        $html = '
		<h2>P�le formation | Formateur </h2><br />
		<form id="formulaire" name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_formateur" method="post">
			<div class="submit">
				<input type="hidden" name="Id_formateur" id="Id_formateur" value="' . (int) $this->Id_formateur . '" >
				<input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifFormateur(this.form)" >
			</div><br /><hr />
			<div class="center"><h2>  </h2><br />
		    <input type="radio" ' . $select[1] . ' name="genre" value="1">&nbsp;&nbsp;' . MISS . '
		    <input type="radio" ' . $select[2] . ' name="genre" value="2">&nbsp;&nbsp;' . MADAM . '
		    <input type="radio" ' . $select[3] . ' name="genre" value="3">&nbsp;&nbsp;' . MISTER . '
		    <input type="radio" ' . $select[4] . ' name="genre" value="4">&nbsp;&nbsp;' . SOCIETY . '
		    <span class="marge"></span><span class="infoFormulaire"> * </span> 
		    Nom&nbsp;:&nbsp;<input type="text" id="nom_formateur" name="nom_formateur" value="' . $this->nom . '">&nbsp;&nbsp;
		    Pr�nom&nbsp;:&nbsp;<input type="text" id="prenom" name="prenom" value="' . $this->prenom . '"><br/><br/></div><hr />
		    <div class="left">
			    T�l. fixe :<span class="marge"></span><input type="text" id="tel_fixe" name="tel_fixe" value="' . $this->tel_fixe . '"><br/><br/>
				T�l. portable&nbsp;: &nbsp;<input type="text" id="tel_portable" name="tel_portable" value="' . $this->tel_portable . '"><br/><br/>
			</div><br/>
			<div class="right"><br/>
				Mail&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="mail" name="mail" size="40" value="' . $this->mail . '"><br/><br/>
				Page web&nbsp;:&nbsp;<input type="text" id="page_web" name="page_web" size="40" value="' . $this->page_web . '"><br/><br/>
			</div><br/>
			<div class="center"><hr/></div>
		    <div class="left">
				Comp�tences<br/><textarea name="competences" id="tinyarea1">' . $this->competences . '</textarea><br/><br/><br/>
			</div><br/>
			<div class="right"><br/><br/>
			    <span class="marge">Statut : </span>
				<span class="marge">
				    <select name="Id_statut" >
					    <option value="">' . STATUS_SELECT . '</option>
					    <option value="">-------------------------</option>
					    ' . $this->listeStatut() . '
				    </select>
				</span><br/><br/>
				<span class="marge">Soci�t� :</span>
				<span class="marge"> 
					<input type="text" id="societe" name="societe" value="' . $this->societe . '">
				</span>
			</div><br/>
			<div class="center"><hr/></div>
            <div class="center"><br/><br/>
			    <span class="marge">Salaire :</span>
				<span class="marge"> 
				    <input type="text" id="salaire" name="salaire" value="' . $this->salaire . '">&nbsp;&nbsp;euros par jour
				</span><br/><br/><br/><hr/>
			</div>
		    <div class="submit">
			    <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifFormateur(this.form)" />
			</div>
		</form>';
        return $html;
    }

    /**
     * Enregistre les donn�es du formateur dans la BDD 
     *
     */
    public function save() {
        $db = connecter();
        //construit la requ�te avec les donn�es de l'instance qui appelle la fonction
        $set = ' SET genre = "' . mysql_real_escape_string((int) $this->genre) . '", nom = "' . mysql_real_escape_string($this->nom) . '", prenom = "' . mysql_real_escape_string($this->prenom) . '", 
		    competence = "' . mysql_real_escape_string($this->competences) . '", page_web = "' . mysql_real_escape_string($this->page_web) . '", tel_fixe="' . mysql_real_escape_string($this->tel_fixe) . '", 
			tel_portable="' . mysql_real_escape_string($this->tel_portable) . '", mail="' . mysql_real_escape_string($this->mail) . '",salaire="' . mysql_real_escape_string((float) $this->salaire) . '", 
			Id_statut="' . mysql_real_escape_string((int) $this->Id_statut) . '", societe="' . mysql_real_escape_string($this->societe) . '"';
        //si le formateur existait d�j�, c'est une mise � jour de la base sinon on insert une nouvelle instance dans la table
        if ((int) $this->Id_formateur) {
            $requete = 'UPDATE formateur ' . $set . ' WHERE id_formateur = ' . mysql_real_escape_string((int) $this->Id_formateur);
        } else {
            $requete = 'INSERT INTO formateur ' . $set;
        }
        //connexion et envoie de la requ�te
        $db->query($requete);
    }

    /**
     * Liste des formateurs pour afficher dans la zone description de la session
     *
     * @return string
     */
    public function getList() {
        $db = connecter();
        $result = $db->query('SELECT Id_formateur, nom, prenom FROM formateur WHERE archive ="0" ORDER BY nom');
        $select[$this->Id_formateur] = 'selected="selected"';
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . (int) $ligne->id_formateur . '" ' . $select[$ligne->id_formateur] . '>' . $ligne->nom . '&nbsp;&nbsp;' . $ligne->prenom . ' (n�' . $ligne->id_formateur . ')</option>';
        }
        return $html;
    }

    /**
     * Affichage du filtre de recherche d'un formateur
     *
     * @return string
     */
    public static function searchForm() {
        //Champs permettant de selectionner les crit�res de recherche
        $html = '<div class="center">
			Nb : 
			<select id="nb" onchange="afficherFormateur()">
				<option value="50">50</option>
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="500">Toutes</option>
			</select>
			&nbsp;
			Id :  
			<input id="Id_formateur" type="text" onkeyup="afficherFormateur()" size="2" value=' . $_SESSION['filtre']['Id_formateur'] . '>
			&nbsp;&nbsp;
			Nom : <input id="nom_formateur" type="text" onKeyup="afficherFormateur()" value=' . $_SESSION['filtre']['nom_formateur'] . '>
			<br /><br />
			Salaire : 
			<select id="type_salaire">
				<option value="">----</option>
				<option value="=">=</option>
				<option value=">">></option>
				<option value="<"><</option>
			</select>
			&nbsp;&nbsp;
			<input id="salaire" type="text" onKeyup="afficherFormateur()" size="8" /> &euro;
			&nbsp;&nbsp;
			Comp�tences : <input id="competence" type="text" onKeyup="afficherFormateur()" value=' . $_SESSION['filtre']['competence'] . '>
			&nbsp;&nbsp;
			<input type="button" onclick="afficherFormateur()" value="Go !">
			</div>';
        return $html;
    }

    /**
     * Affichage de la liste des formateurs selon les crit�res s�lectionn�es
     *
     * @param int identifiant du formateur recherch�
     * @param string partie ou nom complet du nom du formateur
     * @param string type de salaire recherch�
     * @param float  salaire du formateur
     * @param string partie ou mot complet � rechercher dans les comp�tences du formateur
     * @param int	  nombre d'enregistrement sur une page
     *
     * @return string
     */
    public static function search($Id_formateur, $nom_formateur, $type_salaire, $salaire, $competence, $nb = 50, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_formateur', 'nom_formateur', 'type_salaire', 'salaire', 'competence', 'nb', 'output');
        $columns = array(array('Id','Id_formateur'), array('Nom','nom'), array('Pr�nom','prenom'),
                         array('salaire','type'),array('Mail','mail'));
        $db = connecter();
        $requete = 'SELECT Id_formateur, nom, prenom, competence, salaire, mail FROM formateur WHERE archive="0"';
        if ($Id_formateur) {
            $requete .= ' AND Id_formateur =' . (int) $Id_formateur . '';
        }
        if ($nom_formateur) {
            $requete .= ' AND nom LIKE "%' . $nom_formateur . '%"';
        }
        if ($competence) {
            $requete .= ' AND competence LIKE "%' . $competence . '%"';
        }
        if (($salaire != '') && $type_salaire) {
            $requete .= ' AND salaire' . $type_salaire . (float) $salaire;
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
            $paramsOrder .= 'orderBy=Id_formateur';
            $orderBy = 'Id_formateur';
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
                'onclick' => 'afficherFormateur({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterFormateur&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' r�sultat(s)</span></p>
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
                        $img['Id_formateur'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherFormateur({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_formateur'] . '</td>
                            <td>' . self::showName($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showFirstName($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showSalary($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showMail($ligne, array('csv' => false)) . '</td>
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
            header('Content-Disposition: attachment; filename="formateurs.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo $ligne['id_formateur'] . ';';
                echo '"' . self::showName($ligne, array('csv' => true)) . '";';
                echo '"' . self::showFirstName($ligne, array('csv' => true)) . '";';
                echo self::showSalary($ligne, array('csv' => true)) . ';';
                echo '"' . self::showMail($ligne, array('csv' => true)) . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Archivage d'un formateur
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE formateur SET archive="1" WHERE Id_formateur = ' . mysql_real_escape_string((int) $this->Id_formateur));
    }

    /**
     * Liste des statuts possibles d'un formateur
     *
     * @return string
     */
    public function listeStatut() {
        $db = connecter();
        $result = $db->query('SELECT Id_statut, libelle FROM statut_formateur');
        $select[$this->Id_statut] = 'selected="selected"';
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_statut . '" ' . $select[$ligne->id_statut] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage des donn�es d'un formateur en consultation
     *
     * @return string
     */
    public function consultation() {
        $db = connecter();
        $statut = $db->query('SELECT libelle FROM statut_formateur WHERE Id_statut = ' . mysql_real_escape_string((int) $this->Id_statut))->fetchRow()->libelle;
        $html = '<br /><div class="center"><h2><br />';
        //Info sur civilit� du formateur
        if ($this->genre == 1) {
            $html.= MISS;
        } elseif ($this->genre == 2) {
            $html.= MADAM;
        } elseif ($this->genre == 3) {
            $html.= MISTER;
        } elseif ($this->genre == 4) {
            $html.= SOCIETY;
        }
        $html .= '
		    &nbsp;&nbsp;' . $this->nom . '&nbsp;&nbsp;' . $this->prenom . '</h2><br/></div><hr />
		    <div class="left">
			    T�l. fixe&nbsp;:&nbsp;' . $this->tel_fixe . '<br/><br/>
				T�l. portable&nbsp;:&nbsp;' . $this->tel_portable . '<br/><br/>
			</div><br/>
			<div class="right"><br/>
				Mail&nbsp;:&nbsp;<a href="mailto:' . $this->mail . '">' . $this->mail . '</a><br/><br/>
				Page web&nbsp;:&nbsp;<a href="javascript:ouvre_popup(\'' . $this->page_web . '\')"> ' . $this->page_web . ' </a><br/><br/>
			</div><br/>
			<div class="center"><hr/></div>
		    <div class="left">
				Comp�tences : <br/><br/>' . $this->competences . '<br/><br/>
			</div><br/>
			<div class="right"><br/>
				<span class="marge">  Statut  : ' . $statut . '</span><br/><br/>
				<span class="marge">  Soci�t� : ' . $this->societe . '</span><br/><br/>
			</div><br/>
			<div class="center"></div><hr/>
		    <div class="center"><br/><br/>
			    Salaire : ' . $this->salaire . ' euros<br/><br/><br/><hr/>
			</div>';
        return $html;
    }

    /**
     * R�cup�ration du nom du formateur fourni en param�tre
     *
     * @param int identifiant du formateur
     *
     * @return string
     */
    public static function getLastName($i) {
        $db = connecter();
        return $db->query('SELECT nom FROM formateur WHERE Id_formateur = ' . mysql_real_escape_string((int) $i))->fetchRow()->nom;
    }

    /**
     * R�cup�ration du pr�nom du formateur fourni en param�tre
     *
     * @param int identifiant du formateur 
     *
     * @return string
     */
    public static function getName($i) {
        $db = connecter();
        return $db->query('SELECT prenom FROM formateur WHERE Id_formateur = ' . mysql_real_escape_string((int) $i))->fetchRow()->prenom;
    }

    /**
     * Calcul cout du formateur selon la dur�e fournie en param�tre puis affichage dans le formulaire de la session
     *
     * @param int dur�e de la session en jour
     *
     * @return string
     */
    public function trainerCost($nbJ) {
        if ($nbJ != '') {
            $total = $this->salaire * $nbJ;
        }
        $html = '
		    <table>
		        <tr>
			        <td width="180" height="25">Co�t formateur : </td>
				    <td width="120" height="25">Co�t journalier :</td>
				    <td width="120" height="25">
				        <input type="text" id="coutFormateurJ" name="coutFormateurJ" onKeyup="calculcoutFTotal()" value="' . $this->salaire . '" size="8"> euros</td>
			    </tr>
			    <tr>
				    <td width="180" height="25"> </td>
				    <td width="120" height="25">co�t total :</td>
				    <td width="120" height="25"><div id="coutFormateurTotal">
				        <input type="text" id="coutFormateur" name="coutFormateur" onKeyup="chargeTotalSession()" value="' . $total . '" size="8"> euros</div></td>
			    </tr>
			</table>';
        return $html;
    }

    /**
     * R�cup�ration du salaire journalier
     *
     * @return float
     */
    public function getSalary() {
        $db = connecter();
        return $db->query('SELECT salaire FROM formateur WHERE Id_formateur = ' . mysql_real_escape_string((int) $this->Id_formateur))->fetchRow()->salaire;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */
    public function showName($record, $args) {
        if (!$args['csv']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['competence'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            return '<div ' . $info . '>' . $record['nom'] . '</a>';
        }
        else return $record['nom'];
    }
    
    public function showFirstName($record, $args) {
        if (!$args['csv']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['competence'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            return '<div ' . $info . '>' . $record['prenom'] . '</div>';
        }
        else return $record['prenom'];
    }
    
    public function showSalary($record, $args) {
        if (!$args['csv']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['competence'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            return '<div ' . $info . '>' . $record['salaire'] . '</div>';
        }
        else return $record['salaire'];
    }
    
    public function showMail($record, $args) {
        if (!$args['csv']) {
            $info = 'onmouseover="return overlib(\'<div class=commentaire>' . str_replace('"', "'", mysql_escape_string($record['competence'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            return '<div ' . $info . '><a href="mailto:' . $record['mail'] . '">' . $record['mail'] . '</a></div>';
        }
        else return $record['mail'];
    }
    
    public function showButtons($record) {
        $htmlAdmin = '
            <td><a href="index.php?a=infoFormateur&amp;Id_formateur=' . $record['id_formateur'] . '">
                                            <img src="' . IMG_CONSULT . '"></a></td>
            <td><a href="../com/index.php?a=afficherFormateur&amp;Id_formateur=' . $record['id_formateur'] . '">
                                            <img src="' . IMG_EDIT . '"></a></td>
            <td><a href="javascript:void(0)" 
                      onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' le formateur ?\')) 
                      {location.replace(\'../for/index.php?a=archiverFormateur&amp;Id_formateur=' . $record['id_formateur'] . '\')}">
                    <img src="' . IMG_FLECHE_BAS . '"></a></td>';
        return $htmlAdmin;
    }
}

?>
