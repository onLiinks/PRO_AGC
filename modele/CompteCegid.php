<?php

/**
 * Fichier CompteCegid.php
 *
 * @author    Anthony Anne
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe CompteCegid
 */
class CompteCegid {

    /**
     * Identifiant du Compte
     *
     * @access private
     * @var int 
     */
    private $Id_compte;
    /**
     * Nom du Compte
     *
     * @access private
     * @var string 
     */
    public $nom;
    /**
     * Ville
     *
     * @access public
     * @var string 
     */
    public $ville;
    /**
     * Adresse de livraison
     *
     * @access public
     * @var string 
     */
    public $adresse;
    /**
     * Tiers facturé
     *
     * @access public
     * @var string 
     */
    public $tiers_facture;
    /**
     * Code Postal
     *
     * @access public
     * @var string 
     */
    public $code_postal;
    /**
     * Téléphone
     *
     * @access public
     * @var string 
     */
    public $tel;
    /**
     * Code Siret du compte
     *
     * @access public
     * @var string 
     */
    public $siret;
    /**
     * Code APE du compte
     *
     * @access public
     * @var string 
     */
    public $ape;
    /**
     * Condition de réglement
     *
     * @access public
     * @var string 
     */
    public $condition_reglement;
    /**
     * Créateur du compte enregistré dans cegid
     *
     * @access public
     * @var string 
     */
    public $createur;

    /**
     * Constructeur de la classe Compte
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant du Compte
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_compte = '';
                $this->nom = '';
                $this->adresse = '';
                $this->tiers_facture = '';
                $this->code_postal = '';
                $this->ville = '';
                $this->tel = '';
                $this->siret = '';
                $this->ape = '';
                $this->condition_reglement = '';
                $this->createur = '';
            }

            /* Cas 2 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter_cegid();
                $db_a = connecter();
                $ligne = $db->query('SELECT T_LIBELLE, T_ADRESSE1, T_CODEPOSTAL, T_VILLE, T_TELEPHONE, CO1.CO_LIBELLE AS C1, 
				T_NATUREAUXI, T_TIERS, T_FACTURE, T_AUXILIAIRE, US2.US_LIBELLE AS C4, T_SIRET, T_APE, T_MODEREGLE FROM RTTIERS 
				LEFT OUTER JOIN COMMUN CO1 ON T_NATUREAUXI=CO1.CO_CODE AND CO1.CO_TYPE="NTT" 
				LEFT OUTER JOIN UTILISAT US2 ON RPR_CREATEUR=US2.US_UTILISATEUR 
				WHERE ((T_NATUREAUXI="CLI" OR T_NATUREAUXI="PRO" OR T_NATUREAUXI="FOU") AND T_FERME<>"X") AND T_AUXILIAIRE ="' . mysql_real_escape_string($code) . '"')->fetchRow();
                $this->Id_compte = $code;
                $this->nom = $ligne->t_libelle;
                $this->adresse = $ligne->t_adresse1;
                $this->tiers_facture = $ligne->t_facture;
                $this->code_postal = $ligne->t_codepostal;
                $this->ville = $ligne->t_ville;
                $this->tel = $ligne->t_telephone;
                $this->siret = $ligne->t_siret;
                $this->ape = $ligne->t_ape;
                $this->condition_reglement = $ligne->t_moderegle;
                $this->createur = $ligne->c4;
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Recherche d'un compte
     * 		   
     * @return string
     */
    public static function search($nom, $ville, $cp, $createur, $output = array('type' => 'TABLE')) {        
        $arguments = array('nom', 'ville', 'cp', 'createur', 'output');
        $columns = array(array('Nom du compte','t_libelle'), array('Code postal','t_codepostal'), array('Ville','t_ville'),
                         array('Téléphone','t_telephone'));
        $db = connecter_cegid();
        $db_a = connecter();
        $requete = 'SELECT DISTINCT T_LIBELLE, T_ADRESSE1, T_CODEPOSTAL, T_VILLE, T_TELEPHONE, CO1.CO_LIBELLE AS C1, T_NATUREAUXI, 
		T_TIERS, T_AUXILIAIRE, US2.US_ABREGE , US2.US_LIBELLE AS C4, T_SIRET, T_APE, T_MODEREGLE FROM COMMERCIAL,RTTIERS 
		LEFT OUTER JOIN COMMUN CO1 ON T_NATUREAUXI=CO1.CO_CODE AND CO1.CO_TYPE="NTT" 
		LEFT OUTER JOIN UTILISAT US2 ON RPR_CREATEUR=US2.US_UTILISATEUR WHERE ((T_NATUREAUXI="CLI" OR T_NATUREAUXI="PRO") AND T_FERME<>"X")';
        if ($nom) {
            $requete.= ' AND T_LIBELLE LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($nom), ENT_QUOTES)) . '%"';
        }
        if ($ville) {
            $requete.= ' AND T_VILLE LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($ville), ENT_QUOTES)) . '%"';
        }
        if ($cp) {
            $requete.= ' AND T_CODEPOSTAL LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($cp), ENT_QUOTES)) . '%"';
        }
        if ($createur) {
            $createur = strtolower($createur);
            $requete2 = 'SELECT COMMERCIAL.GCL_COMMERCIAL FROM UTILISAT 
						INNER JOIN COMMERCIAL ON  COMMERCIAL.GCL_UTILASSOCIE = UTILISAT.US_UTILISATEUR
						WHERE US_ABREGE="' . $createur . '"';
            $result2 = $db->query($requete2);
            $ligne2 = $result2->fetchRow();
            $requete .= ' AND COMMERCIAL.GCL_UTILASSOCIE=US2.US_UTILISATEUR AND us_abrege = "' . $createur . '" OR 
			T_REPRESENTANT = "' . $ligne2->gcl_commercial . '" OR YTC_REPRESENTANT2 = "' . $ligne2->gcl_commercial . '" OR 
			YTC_REPRESENTANT3= "' . $ligne2->gcl_commercial . '"';
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
            $paramsOrder .= 'orderBy=t_libelle';
            $orderBy = 't_libelle';
        }
        if($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=ASC';
            $direction = 'ASC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherCompte({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterCompte&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $direction = 'DESC';
                        $img['t_libelle'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherCompte({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['t_libelle'] . '</td>
                            <td>' . $ligne['t_codepostal'] . '</td>
                            <td>' . self::showCity($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['t_telephone'] . '</td>
                            <td>' . self::showButton($ligne) . '</td>
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
            header('Content-Disposition: attachment; filename="comptes.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo '"' . $ligne['t_libelle'] . '";';
                echo $ligne['t_codepostal'] . ';';
                echo '"' . self::showCity($ligne, array('csv' => true)) . '";';
                echo '"' . $ligne['t_telephone'] . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les comptes
     *
     * @return string	
     */
    public function getList($prefix = '') {
        $compte[$this->Id_compte] = 'selected="selected"';
        $db = connecter_cegid();
        $db_a = connecter();
        if ($prefix) {
            $req = 'AND (T_LIBELLE LIKE "%' . mysql_real_escape_string($prefix) . '%")';
        }
        $result = $db->query('SELECT T_LIBELLE, T_CODEPOSTAL, T_AUXILIAIRE, T_ETATRISQUE FROM TIERS WHERE ((T_NATUREAUXI="CLI" OR T_NATUREAUXI="PRO") AND T_FERME<>"X" ' . $req . ') ORDER BY T_LIBELLE');
        while ($ligne = $result->fetchRow()) {
            // Bien laisser les 2 espaces après le O ou le R car il y en a un dans CEGID.
            $class = '';
            if ($ligne->t_etatrisque == 'O  ') {
                $class = 'class=roworange';
            } elseif ($ligne->t_etatrisque == 'R  ') {
                $class = 'class=rowrouge';
            }
            $html .= '<option ' . $class . ' value="CG-' . $ligne->t_auxiliaire . '" ' . $compte[$ligne->t_auxiliaire] . '>' . $ligne->t_libelle . ' | ' . $ligne->t_codepostal . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les affaires du compte
     *
     * @return string	
     */
    public function getListOp($idAffaire = '', $prefix = '', $idMere = '') {
        if($idAffaire)
            $op[$idAffaire] = 'selected="selected"';
        $db = connecter();
        if ($prefix) {
            $req = 'WHERE (Name LIKE \'%' . mysql_real_escape_string($prefix) . '%\')';
        }
        $result = $db->query('SELECT a.Id_affaire, i.libelle FROM affaire a
                                INNER JOIN description d ON a.Id_affaire = d.Id_affaire
                                INNER JOIN intitule i ON i.Id_intitule = d.Id_intitule 
                                WHERE Id_compte = \'CG-' . $this->Id_compte .'\' ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_affaire . '" ' . $op[$ligne->id_affaire] . '>' . $ligne->id_affaire . ' | ' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche d'un compte
     *
     * @return string	 
     */
    public static function searchForm() {
        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        $html = '
			Nom : <input id="nom" type="text" onkeyup="afficherCompte()" />
			&nbsp;&nbsp;
			Ville : <input id="ville" type="text" onkeyup="afficherCompte()" />
			&nbsp;&nbsp;
			Code Postal : <input id="cp" type="text" size="3" onkeyup="afficherCompte()" />
			&nbsp;&nbsp;
			<select id="createur" onchange="afficherCompte()">
				<option value="">Par commercial</option>
				<option value="">----------------------------</option>
				' . $utilisateur->getList('COM') . '
			</select>
';
        return $html;
    }

    /**
     * Affichage des informations sur le compte
     *
     * @return string	 
     */
    public function infoCompte() {
        $html = '
		Adresse     : ' . $this->adresse . ' <br />
		Adresse de Facturation : ' . $this->getAdresseFacturation() . ' <br />
		Contact Principal : ' . $this->getContactPrincipal() . ' | Fonction : ' . $this->getFonctionContactPrincipal() . ' <br />
		Mode de règlement : ' . $this->condition_reglement . ' <br />
		Code Postal : ' . $this->code_postal . ' &nbsp;
		Ville       : ' . $this->ville . ' <br />
		Tél         : ' . $this->tel . ' <br />
		SIRET       : ' . $this->siret . ' <br />
		APE         : ' . $this->ape . ' <br />
		Créateur    : ' . $this->createur . '
';
        return $html;
    }

    /**
     * Affichage du contact principal du compte
     *
     * @return string	 
     */
    public function getContactPrincipal() {
        $db = connecter_cegid();
        $db_a = connecter();
        $ligne = $db->query('SELECT C_NOM, C_PRENOM FROM RTANNUAIRE WHERE C_AUXILIAIRE ="' . mysql_real_escape_string($this->Id_compte) . '" AND C_PRINCIPAL="X"')->fetchRow();
        return $ligne->c_nom . ' ' . $ligne->c_prenom;
    }

    /**
     * Affichage de la fonction du contact principal du compte
     *
     * @return string	 
     */
    public function getFonctionContactPrincipal() {
        $db = connecter_cegid();
        $db_a = connecter();
        return htmlscperso(stripslashes($db->query('SELECT C_FONCTION FROM RTANNUAIRE WHERE C_AUXILIAIRE ="' . mysql_real_escape_string($this->Id_compte) . '" AND C_PRINCIPAL="X"')->fetchRow()->c_fonction), ENT_QUOTES);
    }

    /**
     * Affichage de l'adresse de facturation
     *
     * @return string	 
     */
    public function getAdresseFacturation() {
        $db = connecter_cegid();
        $db_a = connecter();
        $ligne = $db->query('SELECT T_ADRESSE1, T_ADRESSE2, T_ADRESSE3, T_CODEPOSTAL, T_VILLE 
		FROM RTTIERS WHERE T_AUXILIAIRE ="' . mysql_real_escape_string($this->Id_compte) . '"')->fetchRow();
        return htmlscperso(stripslashes($ligne->t_adresse1 . '' . $ligne->t_adresse2 . '' . $ligne->t_adresse3 . '' . $ligne->t_codepostal . ' ' . $ligne->t_ville), ENT_QUOTES);
    }

    /**
     * Affichage de la nature du compte : 
     *
     * @return string	 
     */
    public function getNature() {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT CO1.CO_LIBELLE AS nature FROM RTTIERS 
		LEFT OUTER JOIN COMMUN CO1 ON T_NATUREAUXI=CO1.CO_CODE AND CO1.CO_TYPE="NTT" 
		WHERE ((T_NATUREAUXI="CLI" OR T_NATUREAUXI="PRO") AND T_FERME<>"X") AND T_AUXILIAIRE="' . mysql_real_escape_string($this->Id_compte) . '"')->fetchRow()->nature;
    }
    
    /**
     * Affichage des informations sur les conditions de règlement
     *
     * @return string	 
     */
    public function getModeReglement() {
        $db = connecter_cegid();
        $db_a = connecter();
        return $db->query('SELECT MR_LIBELLE FROM MODEREGL WHERE MR_MODEREGLE ="' . mysql_real_escape_string($this->condition_reglement) . '"')->fetchRow()->mr_libelle;
    }

    /**
     * Affichage du nombre d'affaire pour le compte
     *
     * @return int	 
     */
    public static function getNbCase($Id_compte) {
        $db = connecter();
        return $db->query('SELECT count(Id_affaire) as nb FROM affaire WHERE Id_compte ="' . mysql_real_escape_string($Id_compte) . '"')->fetchRow()->nb;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showCity($record, $args) {
        if (!$args['csv']) return '<a href="javascript:ouvre_popup(\'../googlemap/index.php?a=consulterMap&amp;b=' . $record['t_adresse1'] . ' ' . $record['t_ville'] . '\')">' . $record['t_ville'] . '</a>';
        else return $record['t_ville'];
    }
    
    public function showButton($record) {
        if (self::getNbCase($record[t_auxiliaire])) {
            return '<a href="index.php?a=consulterAffaire&Id_compte=' . $record['t_auxiliaire'] . '"><img src="' . IMG_INFO . '"></a>';
        }        
    }
}

?>
