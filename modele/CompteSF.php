<?php

/**
 * Fichier CompteSF.php
 *
 * @author    Mathieu Perrin
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe CompteSF
 */
class CompteSF {

    /**
     * Identifiant du Compte
     *
     * @access private
     * @var int 
     */
    private $Id_compte;

    /**
     * Identifiant Cegid du Compte
     *
     * @access private
     * @var int 
     */
    public $Id_cegid;

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
     * Pays
     *
     * @access public
     * @var string 
     */
    public $pays;

    /**
     * Ville de facturation
     *
     * @access public
     * @var string 
     */
    public $ville_fac;

    /**
     * Adresse de facturation
     *
     * @access public
     * @var string 
     */
    public $adresse_fac;

    /**
     * Code Postal de facturation
     *
     * @access public
     * @var string 
     */
    public $code_postal_fac;

    /**
     * Pays de facturation
     *
     * @access public
     * @var string 
     */
    public $pays_fac;

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
                $db = connecter();
                $sfClient = new SFClient();
                $ligne = $sfClient->query('
                    SELECT Name, Id, BillingStreet, BillingPostalCode, BillingCity, BillingCountry,
                    ShippingStreet, ShippingPostalCode, ShippingCity, ShippingCountry, 
                    Phone, SIRET__c, Code_NAF__c, Conditions_de_reglement__c, Owner.Name, ID_Compte_CEGID__c
                    FROM Account WHERE Id = \'' . mysql_real_escape_string($code) . '\'')->records[0];
                $this->Id_compte = $code;
                $this->nom = $ligne->Name;
                $this->adresse = $ligne->BillingStreet;
                $this->tiers_facture = $ligne->t_facture;
                $this->code_postal = $ligne->BillingPostalCode;
                $this->ville = $ligne->BillingCity;
                $this->pays = $ligne->BillingCountry;
                $this->adresse_fac = $ligne->ShippingStreet;
                $this->code_postal_fac = $ligne->ShippingPostalCode;
                $this->ville_fac = $ligne->ShippingCity;
                $this->pays_fac = $ligne->ShippingCountry;
                $this->tel = $ligne->Phone;
                $this->siret = $ligne->SIRET__c;
                $this->ape = $ligne->Code_NAF__c;
                $this->condition_reglement = $ligne->Conditions_de_reglement__c;
                $this->createur = $ligne->Owner->Name;
                $this->Id_cegid = $ligne->ID_Compte_CEGID__c;
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
        $sfClient = new SFClient();
        $db = connecter();
        $requete = 'SELECT Id, Name, BillingStreet, BillingPostalCode, BillingCity, Phone from Account WHERE Statut__c = \'Ouvert\'';
        if ($nom) {
            $requete.= ' AND Name LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($nom), ENT_QUOTES)) . '%"';
        }
        if ($ville) {
            $requete.= ' AND BillingCity LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($ville), ENT_QUOTES)) . '%"';
        }
        if ($cp) {
            $requete.= ' AND BillingPostalCode LIKE "%' . mysql_real_escape_string(htmlscperso(stripslashes($cp), ENT_QUOTES)) . '%"';
        }
        if ($createur) {
            $requete .= ' AND Owner.Username LIKE "' . $createur . '%"';
        }
        $result = $sfClient->query($requete);

        $data = array();
        foreach ($result->records as $record) {
            array_push($data, $record);
        }

        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            if (count($data) != 0) {
                $datagrid = new Structures_DataGrid(TAILLE_LISTE);
                $datagrid->setDefaultSort(array('Name' => 'ASC'));
                $test = $datagrid->bind($data, array(), "Array");

                $datagrid->setRendererOption('onMove', 'afficherCompte', true);
                $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
                $datagrid->setRendererOption('oddRowAttributes', array('class' => 'oddeven'), true);
                $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
                $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);

                $datagrid->addColumn(new Structures_DataGrid_Column('Nom du compte', 'Name', 'Name'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Code Postal', 'BillingPostalCode', 'BillingPostalCode'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Ville', null, 'BillingCity', null, null, array('CompteSF', 'showCity')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Téléphone', 'Phone', 'Phone'));
                $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('CompteSF', 'showButton')));

                $nbComptes = $datagrid->getRecordCount();
            }

            if (count($data) != 0) {
                foreach (func_get_args() as $key => $value) {
                    if ($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'Name');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'ASC');

                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterCompte&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= $datagrid->getOutput();
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif ($output['type'] == 'CSV') {
            if (count($data) != 0) {
                header("Pragma: public");
                header('Content-type: text/x-csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="comptes.csv"');

                $datagrid = new Structures_DataGrid();
                $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
                $rendererOptions = array('filename' => "comptes.csv", 'numberAlign' => false, 'delimiter' => ";");
                $datagrid->bind($data, array(), "Array");
                $datagrid->addColumn(new Structures_DataGrid_Column('Nom du compte', 't_libelle', 't_libelle'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Code Postal', 't_codepostal', 't_codepostal'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Ville', 't_ville', 't_ville'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Téléphone', 't_telephone', 't_telephone'));
                $res = $datagrid->render('CSV', $rendererOptions);
            }
        }

        return $html;
    }

    /**
     * Affichage d'une select box contenant les comptes
     *
     * @return string	
     */
    public function getList($prefix = '', $restrict = false) {
        $compte['SF-' . $this->Id_compte] = 'selected="selected"';
        $sfClient = new SFClient();
        $db = connecter();
        if ($prefix) {
            if($restrict)
                $restrictQuery = ' AND AccountId = \'' . mysql_real_escape_string($this->Id_compte) . '\'';
            $req .= ' WHERE Id IN (Select AccountId FROM Opportunity WHERE (ID_AGC__c = \'' . mysql_real_escape_string($prefix) . '\' OR Account.Name LIKE \'%' . mysql_real_escape_string($prefix) . '%\') ' . $restrictQuery . ')';
        }
        $result = $sfClient->query('SELECT Name, Id, BillingPostalCode, Etat_du_compte__c FROM Account ' . $req . ' ORDER BY Name');
        foreach ($result->records as $record) {
            // Bien laisser les 2 espaces après le O ou le R car il y en a un dans CEGID.
            $class = '';
            if ($record->Etat_du_compte__c == 'O') {
                $class = 'class=roworange';
            } elseif ($record->Etat_du_compte__c == 'R') {
                $class = 'class=rowrouge';
            }
            $html .= '<option ' . $class . ' value="SF-' . $record->Id . '" ' . $compte['SF-' . $record->Id] . '>' . $record->Name . ' | ' . $record->BillingPostalCode . '</option>';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les affaires du compte
     *
     * @return string	
     */
    public function getListOp($idAffaire = '', $prefix = '', $idMere = '') {
        if ($idAffaire)
            $op[$idAffaire] = 'selected="selected"';
        $sfClient = new SFClient();
        $db = connecter();
        if ($prefix) {
            $req = ' AND Name LIKE \'%' . mysql_real_escape_string($prefix) . '%\'';
        }
        if ($idMere) {
            $req .= ' AND N_Opportunit_M_re__c = \'' . $idMere . '\'';
        }
        $result = $sfClient->query('SELECT Id, ID_AGC__c, Name, Date_debut_de_commande__c, Date_fin_de_commande__c FROM Opportunity WHERE AccountId = \'' . $this->Id_compte . '\' ' . $req . ' AND StageName IN (\'Signature client\', \'Accord client\')  AND Societ_Instance_IT__c =\'PROSERVIA\'  ORDER BY Date_debut_de_commande__c DESC');
        foreach ($result->records as $record) {
            $html .= '<option value="' . $record->Id . '" ' . $op[$record->Id] . '>' . $record->ID_AGC__c . ' | ' . $record->Name . ' | ' . FormatageDate($record->Date_debut_de_commande__c) . ' à ' . FormatageDate($record->Date_fin_de_commande__c) . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les affaires formation du compte
     *
     * @return string	
     */
    public function getListOpFor($idAffaire = '', $prefix = '', $idMere = '') {
        if ($idAffaire)
            $op[$idAffaire] = 'selected="selected"';
        $sfClient = new SFClient();
        $db = connecter();
        if ($prefix) {
            $req = ' AND Name LIKE \'%' . mysql_real_escape_string($prefix) . '%\'';
        }
        if ($idMere) {
            $req .= ' AND N_Opportunit_M_re__c = \'' . $idMere . '\'';
        }
        $result = $sfClient->query('SELECT Id, ID_AGC__c, Name FROM Opportunity WHERE AccountId = \'' . $this->Id_compte . '\' ' . $req . '
                                    AND (StageName = \'Gain non confirmé\' OR StageName = \'Gain confirmé (bon de cde ferme)\' OR StageName = \'Accord client\' OR StageName = \'Signature client\')
                                    AND Pole__c = \'Formation\' AND (Date_debut_de_commande__c = LAST_N_DAYS:30 OR Date_debut_de_commande__c > TODAY) AND Societ_Instance_IT__c =\'PROSERVIA\'
                                    ORDER BY Date_debut_de_commande__c DESC');
        foreach ($result->records as $record) {
            $html .= '<option value="' . $record->Id . '" ' . $op[$record->Id] . '>' . $record->Name . ' | ' . $record->ID_AGC__c . '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les lieux de prestations
     *
     * @return string	
     */
    public function getListLieuxPrestation($lieuMission = NULL) {
        $sfClient = new SFClient();
        $db = connecter();        
        $result = $sfClient->query('SELECT Name, Rue__c, Code_Postal__c, Ville__c, Pays__c FROM Lieu_de_prestation__c WHERE Compte__c = \'' . $this->Id_compte . '\' ');
        foreach ($result->records as $record) {
            $address = $record->Rue__c . ' ' . $record->Code_Postal__c . ' ' . $record->Ville__c . ' ' . $record->Pays__c;
            $selected = '';
            if($address == $lieuMission)
                $selected = 'selected="selected"'; 
            $html .= '<option value="' . $record->Id . '" data-address="' . $address . '" ' . $selected . '>' . $record->Name. '</option>';
        }
        return $html;
    }
    
    /**
     * Affichage du formulaire de recherche d'un compte
     *
     * @return string	 
     */
    public function searchForm() {
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
		Mode de règlement : ' . $this->getModeReglement() . ' <br />
		Code Postal : ' . $this->code_postal . ' &nbsp;
		Ville       : ' . $this->ville . ' <br />
                Pays       : ' . $this->pays . ' <br />
		Tél         : ' . $this->tel . ' <br />
		SIRET       : ' . $this->siret . ' <br />
		APE         : ' . $this->ape . ' <br />
		Créateur    : ' . $this->createur . '
';
        return $html;
    }

    /**
     * Affichage des informations sur les conditions de règlement
     *
     * @return string	 
     */
    public function getModeReglement() {
        return $this->condition_reglement;
    }

    /**
     * Affichage de l'adresse de facturation
     *
     * @return string	 
     */
    public function getAdresseFacturation() {
        return htmlscperso(stripslashes($this->adresse_fac . ' ' . $this->code_postal_fac . ' ' . $this->ville_fac . ' ' . $this->pays_fac), ENT_QUOTES);
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showCity($params) {
        extract($params);
        return '<a href="javascript:ouvre_popup(\'../googlemap/index.php?a=consulterMap&amp;b=' . $record->BillingStreet . ' ' . $record->BillingPostalCode . ' ' . $record->BillingCity . '\')">' . $record->BillingCity . '</a>';
    }

    public function showButton($params) {
        extract($params);
        if (self::getNbCase($record->Id)) {
            return '<a href="index.php?a=consulterAffaire&Id_compte=' . $record->Id . '"><img src="' . IMG_INFO . '"></a>';
        }
    }

}

?>