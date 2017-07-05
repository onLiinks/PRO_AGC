<?php

/**
 * Fichier ContactCegid.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe ContactCegid
 */
class ContactCegid {

    /**
     * Identifiant du contact
     *
     * @access private
     * @var int 
     */
    private $Id_contact;
    /**
     * Nom du contact
     *
     * @access public
     * @var string 
     */
    public $nom;
    /**
     * Prénom du contact
     *
     * @access public
     * @var string 
     */
    public $prenom;
    /**
     * Adresse du contact
     *
     * @access private
     * @var string 
     */
    private $adresse;
    /**
     * Code Postal du contact
     *
     * @access private
     * @var string 
     */
    private $code_postal;
    /**
     * Ville du contact
     *
     * @access private
     * @var string 
     */
    private $ville;
    /**
     * Téléphone portable du contact
     *
     * @access public
     * @var string 
     */
    public $tel;
    /**
     * Mail du contact
     *
     * @access public
     * @var string 
     */
    public $mail;
    /**
     * Fonction du contact
     *
     * @access public
     * @var string 
     */
    public $fonction;
    /**
     * Identifiant du compte du contact
     *
     * @access private
     * @var string 
     */
    private $Id_compte;
    /**
     * Remarque sur le contact
     *
     * @access private
     * @var string 
     */
    private $remarque;

    /**
     * Constructeur de la classe Contact
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 			
     * @param int Valeur de l'identifiant du contact
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_contact = '';
                $this->nom = '';
                $this->prenom = '';
                $this->adresse = '';
                $this->code_postal = '';
                $this->ville = '';
                $this->tel = '';
                $this->mail = '';
                $this->fonction = '';
                $this->Id_compte = '';
            }

            /* Cas 2 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                list($cont, $compte) = explode('-', $code, 2);
                $db = connecter_cegid();
                $db_a = connecter();
                $ligne = $db->query('SELECT C_AUXILIAIRE, C_NOM, C_PRENOM, C_TYPECONTACT, CO1.CO_LIBELLE AS C4, T_NATUREAUXI, 
				C_NUMEROCONTACT, T_AUXILIAIRE, C_RVA, T_SIRET, T_ADRESSE1, T_CODEPOSTAL, T_VILLE FROM RTANNUAIRE 
				LEFT OUTER JOIN COMMUN CO1 ON T_NATUREAUXI=CO1.CO_CODE AND CO1.CO_TYPE="NTT" 
				WHERE (C_TYPECONTACT = "T" AND C_FERME<>"X" AND C_NUMEROCONTACT="' . mysql_real_escape_string($cont) . '" 
				AND C_AUXILIAIRE="' . mysql_real_escape_string($compte) . '" AND ( (T_NATUREAUXI = "CLI" OR T_NATUREAUXI = "PRO")))')->fetchRow();
                $this->Id_contact = $code;
                $this->nom = $ligne->c_nom;
                $this->prenom = $ligne->c_prenom;
                $this->adresse = $ligne->t_adresse1;
                $this->code_postal = $ligne->t_codepostal;
                $this->ville = $ligne->t_ville;
                $this->tel = $ligne->c_telephone;
                $this->mail = $ligne->c_rva;
                $this->fonction = $ligne->c_fonction;
                $this->Id_compte = $ligne->c_auxiliaire;
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Recherche d'un contact
     *
     * @param string Nom ou Prénom du contact
     * @param string Société du contact 
     * @param string Ville du contact
     * @param string Code Postal du contact
     * @param string Adresse éléctronique du contact
     * @param string Nature du contact : Client / Prospect
     * 	  
     * @return string
     */
    public static function search($nom, $societe, $ville, $cp, $mail, $nature, $createur, $output = array('type' => 'TABLE')) {
        $arguments = array('nom', 'societe', 'ville', 'cp', 'mail', 'nature', 'createur', 'output');
        $columns = array(array('Nom / Prénom','c_nom'), array('Fonction','c_fonction'), array('Société','rta.t_libelle'),
                         array('Mail','c_rva'),array('Téléphone','c_telephone'));
        $db = connecter_cegid();
        if($createur) {
            $join = 'LEFT OUTER JOIN RTTIERS RTT ON RTA.T_AUXILIAIRE = RTT.T_AUXILIAIRE
                    LEFT OUTER JOIN UTILISAT US2 ON RPR_CREATEUR=US2.US_UTILISATEUR ';
        }
        $requete = 'SELECT DISTINCT C_AUXILIAIRE, C_NOM, C_PRENOM, C_FONCTION, RTA.T_LIBELLE, C_TYPECONTACT, CO1.CO_LIBELLE AS C6, 
		RTA.T_NATUREAUXI, C_NUMEROCONTACT, RTA.T_AUXILIAIRE, C_RVA, C_TELEPHONE, RTA.T_ADRESSE1, RTA.T_CODEPOSTAL, RTA.T_VILLE 
                FROM COMMERCIAL, RTANNUAIRE RTA
		LEFT OUTER JOIN COMMUN CO1 ON RTA.T_NATUREAUXI=CO1.CO_CODE AND CO1.CO_TYPE="NTT" 
                ' . $join . '
		WHERE (C_TYPECONTACT = "T" AND C_FERME<>"X" AND ( (RTA.T_NATUREAUXI = "CLI" OR RTA.T_NATUREAUXI = "PRO"))) ';

        if ($nom) {
            $requete.= ' AND C_NOM LIKE "%' . $nom . '%" OR C_PRENOM LIKE "%' . $nom . '%"';
        }
        if ($societe) {
            $requete.= ' AND RTA.T_LIBELLE LIKE "%' . $societe . '%"';
        }
        if ($ville) {
            $requete.= ' AND RTA.T_VILLE LIKE "%' . $ville . '%"';
        }
        if ($cp) {
            $requete.= ' AND RTA.T_CODEPOSTAL LIKE "%' . $cp . '%"';
        }
        if ($mail) {
            $requete.= ' AND C_RVA LIKE "%' . $mail . '%"';
        }
        if ($nature) {
            $requete.= ' AND RTA.T_NATUREAUXI = "' . $nature . '"';
        }
        if ($createur) {
            $createur = strtolower($createur);
            $requete2 = 'SELECT COMMERCIAL.GCL_COMMERCIAL FROM UTILISAT 
                        INNER JOIN COMMERCIAL ON  COMMERCIAL.GCL_UTILASSOCIE = UTILISAT.US_UTILISATEUR
                        WHERE US_ABREGE="' . $createur . '"';
            $result2 = $db->query($requete2);
            $ligne2 = $result2->fetchRow();
            $requete .= ' AND COMMERCIAL.GCL_UTILASSOCIE=US2.US_UTILISATEUR AND us_abrege = "' . $createur . '" OR 
			RTT.T_REPRESENTANT = "' . $ligne2->gcl_commercial . '" OR RTT.YTC_REPRESENTANT2 = "' . $ligne2->gcl_commercial . '" OR 
			RTT.YTC_REPRESENTANT3= "' . $ligne2->gcl_commercial . '"';
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
            $paramsOrder .= 'orderBy=c_nom';
            $orderBy = 'c_nom';
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
                'onclick' => 'afficherContact({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterContact&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $img['c_nom'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherContact({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . self::showName($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['c_fonction'] . '</td>
                            <td>' . $ligne['t_libelle'] . '</td>
                            <td>' . self::showMail($ligne, array('csv' => false)) . '</td>
                            <td>' . $ligne['c_telephone'] . '</td>
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
            header('Content-Disposition: attachment; filename="contacts.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {     
                echo '"' . self::showName($ligne, array('csv' => true)) . '";';
                echo '"' . $ligne['c_fonction'] . '";';
                echo '"' . $ligne['t_libelle'] . '";';
                echo '"' . self::showMail($ligne, array('csv' => true)) . '";';
                echo '"' . $ligne['c_telephone'] . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche d'un contact
     *
     * @return string	 
     */
    public static function searchForm() {
        $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
        $html = '
		    Nom ou Prénom <input type="text" size="8" id="nom" onkeyup="afficherContact()">
                    &nbsp;&nbsp;
                    Société : <input type="text" size="8" id="societe" onkeyup="afficherContact()">
                    &nbsp;&nbsp;
                    Ville : <input type="text" size="8" id="ville" onkeyup="afficherContact()">
                    &nbsp;&nbsp;
                    Code Postal : <input type="text" size="2" id="cp" onkeyup="afficherContact()">
                    &nbsp;&nbsp;
                    Mail : <input type="text" id="mail" onkeyup="afficherContact()">
                    &nbsp;&nbsp;
                    <select id="nature" onchange="afficherContact()">
                        <option value="">Par nature</option>
                        <option value="">-----------</option>
                        <option value="CLI">Client</option>
                        <option value="PRO">Prospect</option>
                    </select>
                    &nbsp;&nbsp;
                    <select id="createur" onchange="afficherContact()">
                        <option value="">Par commercial</option>
                        <option value="">----------------------------</option>
                        ' . $utilisateur->getList('COM') . '
                    </select>
';
        return $html;
    }

    /**
     * Affichage d'une select box contenant les contacts
     *
     * @param string Identifiant  de la société du contact
     *
     * @return string
     */
    public function getList($Id_compte = '') {
        $contact[$this->Id_contact] = 'selected="selected"';
        $db = connecter_cegid();
        $db_a = connecter();
        if ($Id_compte) {
            $cp = explode('-', $Id_compte, 2);
            $req = 'AND (C_AUXILIAIRE ="' . mysql_real_escape_string($cp[1]) . '")';
        }
        $requete = 'SELECT C_NOM, C_PRENOM, C_AUXILIAIRE, C_NUMEROCONTACT FROM RTANNUAIRE WHERE (C_TYPECONTACT = "T" AND C_FERME<>"X" AND ( (T_NATUREAUXI = "CLI" OR T_NATUREAUXI = "PRO")) ' . $req . ') ORDER BY C_NOM';
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="CG-' . $ligne->c_numerocontact . '-' . $ligne->c_auxiliaire . '" ' . $contact['CG-' . $ligne->c_numerocontact . '-' . $ligne->c_auxiliaire] . '>' . $ligne->c_nom . ' ' . $ligne->c_prenom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du nombre d'affaire pour le contact
     *
     * @param string Identifiant  du contact
     * 	  
     * @return int
     */
    public static function getNbCase($Id_contact) {
        $db = connecter();
        $db_a = connecter();
        return $db->query('SELECT Id_affaire FROM affaire WHERE Id_contact1 ="' . mysql_real_escape_string($Id_contact) . '" OR Id_contact2="' . mysql_real_escape_string($Id_contact) . '"')->numRows();
    }

    /**
     * Affichage du nom et du prénom du contact
     *
     * @param string Identifiant  du contact
     * 	
     * @return string
     */
    public function getName() {
        return $this->nom . ' ' . $this->prenom;
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showName($record) {
        return $record['c_nom'] . ' ' . $record['c_prenom'];
    }
    
    public function showMail($record, $args) {
        if (!$args['csv']) return '<a href="mailto:' . $record['c_rva'] . '">' . $record['c_rva'] . '</a>';
        else return $record['c_rva'];
    }
    
    public function showButton($record) {
        if (self::getNbCase($record['c_numerocontact'] . '-' . $record['c_auxiliaire'])) {
            return '<a href="index.php?a=consulterAffaire&Id_contact=' . $record['c_numerocontact'] . '-' . $record['c_auxiliaire'] . '"><img src="' . IMG_INFO . '"></a>';
        }
    }
}

?>