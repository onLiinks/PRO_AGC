<?php

/**
 * Fichier Salle.php
 *
 * @author    Frédérique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Salle
 */
class Salle {

    /**
     * Identifiant de la Salle
     *
     * @access public
     * @var int 
     */
    public $Id_salle;
    /**
     * Nom de la Salle
     *
     * @access public
     * @var string 
     */
    public $nom_salle;
    /**
     * Lieu de la Salle
     *
     * @access public
     * @var string 
     */
    public $lieu;
    /**
     * Adresse de la Salle
     *
     * @access public
     * @var string 
     */
    public $adresse;
    /**
     * ville de la Salle
     *
     * @access public
     * @var string 
     */
    public $ville;
    /**
     * Code postal de la Salle
     *
     * @access public
     * @var string 
     */
    public $code_postal;
    /**
     * Téléphone de la Salle
     *
     * @access public
     * @var string 
     */
    public $tel;
    /**
     * Lien vers le plan d'accès de la Salle
     *
     * @access public
     * @var string 
     */
    public $acces;
    /**
     * Prix de réservation de la Salle
     *
     * @access public
     * @var double 
     */
    public $prix;
    /**
     * Descriptif de la Salle
     *
     * @access public
     * @var string 
     */
    public $descriptif;

    /**
     * Constructeur de la classe Salle
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'instance Salle
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_salle = '';
                $this->nom_salle = '';
                $this->lieu = '';
                $this->adresse = '';
                $this->ville = '';
                $this->code_postal = '';
                $this->tel = '';
                $this->prix = '';
                $this->descriptif = '';
                $this->acces = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_salle = '';
                $this->nom_salle = htmlscperso(stripslashes(strtoupper($tab['nom_salle'])), ENT_QUOTES);
                $this->lieu = htmlscperso(stripslashes($tab['lieu']), ENT_QUOTES);
                $this->adresse = htmlscperso(stripslashes($tab['adresse']), ENT_QUOTES);
                $this->ville = htmlscperso(stripslashes(strtoupper($tab['ville'])), ENT_QUOTES);
                $this->code_postal = $tab['code_postal'];
                $this->tel = formatTel($tab['tel']);
                $this->prix = $tab['prix'];
                $this->descriptif = $tab['descriptif'];
                $this->acces = $tab['acces'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM salle WHERE Id_salle=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_salle = $code;
                $this->nom_salle = $ligne->nom;
                $this->lieu = $ligne->lieu;
                $this->adresse = $ligne->adresse;
                $this->ville = $ligne->ville;
                $this->code_postal = $ligne->code_postal;
                $this->tel = $ligne->tel;
                $this->prix = $ligne->prix;
                $this->descriptif = $ligne->descriptif;
                $this->acces = $ligne->acces;
            }

            /* Cas 4 : un code et un tableau : prendre infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_salle = $code;
                $this->nom_salle = htmlscperso(stripslashes(strtoupper($tab['nom_salle'])), ENT_QUOTES);
                $this->lieu = htmlscperso(stripslashes($tab['lieu']), ENT_QUOTES);
                $this->adresse = htmlscperso(stripslashes($tab['adresse']), ENT_QUOTES);
                $this->ville = htmlscperso(stripslashes(strtoupper($tab['ville'])), ENT_QUOTES);
                $this->code_postal = $tab['code_postal'];
                $this->tel = formatTel($tab['tel']);
                $this->prix = $tab['prix'];
                $this->descriptif = $tab['descriptif'];

                $db = connecter();
                $ligne = $db->query('SELECT acces FROM salle WHERE Id_salle=' . mysql_real_escape_string((int) $code))->fetchRow();
                if ($tab['acces'] != '') {
                    $this->acces = $tab['acces'];
                    if ($ligne->acces != $this->acces) {
                        if (is_file(PLANACCES_DIR . $ligne->acces)) {
                            unlink(PLANACCES_DIR . $ligne->acces);
                        }
                    }
                } else {
                    $this->acces = $ligne->acces;
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une salle
     *
     * @return string
     */
    public function form() {
        if ($this->acces) {
            $acces = '<br/><br/><span class="marge"></span>Plan actuel : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <a href="javascript:ouvre_popup(\'' . PLANACCES_DIR . $this->acces . '\')">' . $this->acces . '</a><br /><br />';
        }
        $html = '
		<h2>Pôle formation | Salle </h2><br />
		<form id="formulaire" name="formulaire" enctype="multipart/form-data" action="index.php?a=enregistrer_salle" method="post">
			<div class="submit">
				<input type="hidden" name="Id_salle" id="Id_salle" value="' . $this->Id_salle . '" >
				<input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifSalle(this.form)" >
			</div><br /><hr />
			<div class="center"><h2>  </h2><br />
			    <span class="infoFormulaire"> * </span> 
					Nom&nbsp;:&nbsp;<input type="text" id="nom_salle" name="nom_salle" size="40" value="' . $this->nom_salle . '">
					&nbsp;&nbsp;<br/><br/>
					Lieu &nbsp;:&nbsp;<input type="text" id="lieu" name="lieu" size="40" value="' . $this->lieu . '"><br/><br/>
			</div><hr />
            <div class="left">
				Adresse :<span class="marge">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="adresse" name="adresse" size="40" value="' . $this->adresse . '"></span><br/><br/>
				Ville :<span class="marge"></span><span class="marge">
				<input type="text" id="ville" name="ville" value="' . $this->ville . '"></span><br/><br/>
				Code postal :<span class="marge">
				<input type="text" id="code_postal" name="code_postal" value="' . $this->code_postal . '" size="4"></span><br/><br/>
			</div><br/>
			<div class="right"><br/>
				Téléphone :<span class="marge">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="tel" name="tel" value="' . $this->tel . '"><br/><br/>
			</div><br/>
			<div class="center"><hr/></div>
            <div class="left">
			    Descriptif/Remarques<br/><textarea name="descriptif" id="tinyarea1">' . $this->descriptif . '</textarea><br/><br/><br/>
			</div><br/>
			<div class="right"><br/><br/>
				<input type="hidden" name="MAX_FILE_SIZE" value="6000000">' . $acces . '
				<span class="marge">Plan d\'accès :</span> <span class="marge"><input id="acces" name="acces" type="file"></span>
				<br/><br/>
			</div><br/>
			<div class="center"><hr/></div>
		    <div class="center"><br/><br/>
				Prix : <input type="text" id="prix" name="prix" value="' . $this->prix . '">&nbsp;euros par jour<br/><br/>
			    <br/><hr/>
			</div>
		    <div class="submit">
			    <input type="submit" value="' . SAVE_BUTTON . '" onclick="return verifSalle(this.form)" />
		    </div>
		</form>';
        return $html;
    }

    /**
     * Enregistrement des données de la salle dans la base de données 
     *
     */
    public function save() {
        $db = connecter();
        //construit la requête avec les données de l'instance qui appelle la fonction
        $set = ' SET nom = "' . mysql_real_escape_string($this->nom_salle) . '", lieu = "' . mysql_real_escape_string($this->lieu) . '", adresse = "' . mysql_real_escape_string($this->adresse) . '",  
						ville = "' . mysql_real_escape_string($this->ville) . '", code_postal = "' . mysql_real_escape_string($this->code_postal) . '", tel="' . mysql_real_escape_string($this->tel) . '",
						prix="' . mysql_real_escape_string((float) $this->prix) . '", descriptif="' . mysql_real_escape_string($this->descriptif) . '", acces="' . mysql_real_escape_string($this->acces) . '"';
        //si la salle existait déjà, c'est une mise à jour de la base sinon on insert une nouvelle instance dans la table
        if ($this->Id_salle) {
            $requete = 'UPDATE salle ' . $set . ' WHERE id_salle = ' . mysql_real_escape_string((int) $this->Id_salle);
        } else {
            $requete = 'INSERT INTO salle ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Liste des salles pour afficher dans le formulaire d'une session
     *
     * @return string
     */
    public function getList() {
        $db = connecter();
        $result = $db->query('SELECT Id_salle, nom, lieu, ville FROM salle WHERE archive = "0" ORDER BY lieu, ville, nom');
        $select[$this->Id_salle] = 'selected="selected"';
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . (int) $ligne->id_salle . '"  ' . $select[$ligne->id_salle] . '>' . $ligne->lieu . '&nbsp;-&nbsp;' .
                    $ligne->ville . '&nbsp;-&nbsp;' . $ligne->nom . '&nbsp;(' . $ligne->id_salle . ')</option>';
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche d'une salle
     *
     * @return string
     */
    public static function searchForm() {
        $html = '<div class="center">
			Nb : 
			<select id="nb" onchange="afficherSalle()">
				<option value="50">50</option>
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option value="500">Toutes</option>
			</select>
			&nbsp;
			Id : <input id="Id_salle" type="text" onKeyup="afficherSalle()" size="2" value=' . $_SESSION['filtre']['Id_salle'] . ' >
			&nbsp;&nbsp;
			Nom : <input id="nom_salle" type="text" onKeyup="afficherSalle()" value=' . $_SESSION['filtre']['nom_salle'] . ' >
			&nbsp;&nbsp;
			Lieu : <input id="lieu" type="text" onKeyup="afficherSalle()" value=' . $_SESSION['filtre']['lieu'] . ' >
			<br /><br />
			Prix : 
			<select id="type_prix">
				<option value="">----</option>
				<option value="=">=</option>
				<option value=">">></option>
				<option value="<"><</option>
			</select>
			&nbsp;&nbsp;
			<input id="prix" type="text" onKeyup="afficherSalle()" size="8" /> &euro;
			&nbsp;&nbsp;
			Ville : <input id="ville" type="text" onKeyup="afficherSalle()" value=' . $_SESSION['filtre']['ville'] . ' >
			&nbsp;&nbsp;
			<input type="button" onclick="afficherSalle()" value="Go !">
			</div>';
        return $html;
    }

    /**
     * Affichage de la liste des salles selon les critéres sélectionnées
     *
     * @param int identifiant de la salle recherchée
     * @param string partie ou nom complet du nom de la salle
     * @param string partie ou nom complet du lieu de la salle
     * @param string type de prix recherché
     * @param float  prix de la salle
     * @param string partie ou nom complet de la ville de la salle
     * @param int nombre d'enregistrement sur une page
     *
     * @return string
     */
    public static function search($Id_salle, $nom_salle, $lieu, $type_prix, $prix, $ville, $nb = 50, $output = array('type' => 'TABLE')) {
        $arguments = array('Id_salle', 'nom_salle', 'lieu', 'type_prix', 'prix', 'ville', 'nb', 'output');
        $columns = array(array('Id','Id_salle'), array('Nom','nom'), array('Lieu','lieu'),
                         array('Ville','ville'),array('Prix','prix'),array('Téléphone','tel'));
        $db = connecter();
        //construction de la requête pour récupérer la liste des salles correspondant aux critéres sélectionnés
        $requete = 'SELECT Id_salle, nom, lieu, ville, prix, tel FROM salle WHERE archive = "0"';
        if ($Id_salle) {
            $requete .= ' AND Id_salle =' . (int) $Id_salle . '';
        }
        if ($nom_salle) {
            $requete .= ' AND nom LIKE "%' . $nom_salle . '%"';
        }
        if ($lieu) {
            str_replace("_", " ", $lieu);
            $requete .= ' AND lieu LIKE "%' . $lieu . '%"';
        }
        if ($ville) {
            str_replace("_", " ", $ville);
            $requete .= ' AND ville LIKE "%' . $ville . '%"';
        }
        if (($prix != '') && ($type_prix)) {
            $requete .= ' AND prix' . $type_prix . (float) $prix;
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
            $paramsOrder .= 'orderBy=Id_salle';
            $orderBy = 'Id_salle';
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
                'onclick' => 'afficherSalle({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterSalle&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $img['Id_salle'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherSalle({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_salle'] . '</td>
                            <td>' . $ligne['nom'] . '</td>
                            <td>' . $ligne['lieu'] . '</td>
                            <td>' . $ligne['ville'] . '</td>
                            <td>' . $ligne['prix'] . '</td>
                            <td>' . $ligne['tel'] . '</td>
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
            header('Content-Disposition: attachment; filename="salles.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo $ligne['id_salle'] . ';';
                echo '"' . $ligne['nom'] . '";';
                echo '"' . $ligne['lieu'] . '";';
                echo '"' . $ligne['ville'] . '";';
                echo $ligne['prix'] . ';';
                echo $ligne['tel'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Archivage d'une salle
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE salle SET archive="1" WHERE Id_salle = ' . mysql_real_escape_string((int) $this->Id_salle));
    }

    /**
     * Affichage des données d'une salle en consultation
     *
     * @return string
     */
    public function consultation() {
        $html = '<div class="center"><h2>' . $this->nom_salle . ' | ' . $this->lieu . '</h2><br /></div><hr />
		<div class="left">
			Coordonnées : &nbsp;' . $this->adresse . '<br>
			<span class="marge"></span><span class="marge"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                $this->code_postal . ', ' . $this->ville . '<br/><br/>
		</div><br/>
		<div class="right"><br/>
			Téléphone : ' . $this->tel . '<br/><br/>
		</div><br/>
		<div class="center"><hr/></div>
		<div class="left">
			Descriptif/Remarques :<br/><br/> ' . $this->descriptif . '<br/><br/>
		</div><br/>';
        if ($this->acces) {
            $html .='
			<div class="right"><br/><br/>
				Plan d\'accès : <a href="javascript:ouvre_popup(\'' . PLANACCES_DIR . $this->acces . '\')"> ' . $this->acces . ' </a><br/><br/>
			</div><br/>';
        }
        $html .= '
		<div class="center"><hr/></div>
		<div class="center"><br/><br/>
		    Prix : ' . $this->prix . ' euros par jour <br/><br/><br/><hr/>
		</div>';
        return $html;
    }

    /**
     * Renvoie le nom de la salle passée en paramètre
     *
     * @param int identifiant de la salle 
     *
     * @return string
     */
    public static function getName($i) {
        $db = connecter();
        return $db->query('SELECT nom FROM salle WHERE Id_salle = ' . mysql_real_escape_string((int) $i))->fetchRow()->nom;
    }

    /**
     * Renvoie le lieu de la salle passée en paramètre
     *
     * @param int identifiant de la salle 
     *
     * @return string
     */
    public static function getPlace($i) {
        $db = connecter();
        return $db->query('SELECT lieu FROM salle WHERE Id_salle = ' . mysql_real_escape_string((int) $i))->fetchRow()->lieu;
    }

    /**
     * Calcul du cout total de la salle selon la durée fournie puis affichage dans la session
     *
     * @param int durée de la session en jour
     *
     * @return string
     */
    public function roomCost($nbJ) {
        $total = ($nbJ != '') ? (float) $this->prix * (float) $nbJ : 0;
        $html = '
		<table>
		    <tr>
				<td width="180" height="25">Coût de la salle : </td>
				<td width="120" height="25">coût journalier :</td>
				<td width="120" height="25">
					<input type="text" id="coutSalleJ" name="coutSalleJ" onKeyup="calculcoutSTotal()" value="' . $this->prix . '" size="8"> euros</td>
			</tr>
			<tr>
				<td width="180" height="25"></td>
				<td width="120" height="25">coût total :</td>
				<td width="120" height="25"><div id="coutSalleTotal">
			        <input type="text" id="coutSalle" name="coutSalle" onKeyup="chargeTotalSession()" value="' . $total . '" size="8"> euros</div></td>
			</tr>
		</table>';
        return $html;
    }

    /**
     * Renvoie le cout journalier de la salle
     *
     * @return float
     */
    public function getPrice() {
        $db = connecter();
        return $db->query('SELECT prix FROM salle WHERE Id_salle = ' . mysql_real_escape_string((int) $this->Id_salle))->fetchRow()->prix;
    }
    
    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */
    
    public function showButtons($params) {
        extract($params);
        $htmlAdmin = '
            <td><a href="index.php?a=infoSalle&amp;Id_salle=' . $ligne['id_salle'] . '">
                    <img src="' . IMG_CONSULT . '"></a></td>
            <td><a href="../com/index.php?a=afficherSalle&amp;Id_salle=' . $ligne['id_salle'] . '">
                    <img src="' . IMG_EDIT . '"></a></td>
            <td><a href="javascript:void(0)" 
                            onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' la salle n°' . $ligne['id_salle'] . ' ?\')) 
                            { location.replace(\'../for/index.php?a=archiverSalle&amp;Id_salle=' . $ligne['id_salle'] . '\') }">
                    <img src="' . IMG_FLECHE_BAS . '"></a></td>';
        return $htmlAdmin;
    }
}

?>
