<?php

/**
 * Fichier ContactSF.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe ContactSF
 */
class ContactSF {

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
                $sfClient = new SFClient();
                $db_a = connecter();
                $ligne = $sfClient->query('
                    SELECT Id, LastName, FirstName, Title, AccountId, Email, Telephone_Ligne_directe__c, 
                            Account.BillingStreet, Account.BillingPostalCode, Account.BillingCity 
                    FROM Contact WHERE Id = \'' . mysql_real_escape_string($code) .'\'')->records[0];
                $this->Id_contact = $code;
                $this->nom = $ligne->LastName;
                $this->prenom = $ligne->FirstName;
                $this->adresse = $ligne->Account->BillingStreet;
                $this->code_postal = $ligne->Account->BillingPostalCode;
                $this->ville = $ligne->Account->BillingCity;
                $this->tel = $ligne->Telephone_Ligne_directe__c;
                $this->mail = $ligne->Email;
                $this->fonction = $ligne->Title;
                $this->Id_compte = $ligne->AccountId;
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
        $sfClient = new SFClient();

        $requete = 'SELECT LastName, FirstName, Title, Account.Name, Email, Telephone_Ligne_directe__c FROM Contact
                    WHERE A_quitte_entreprise__c = false ';

        if ($nom) {
            $requete.= ' AND (LastName LIKE "%' . $nom . '%" OR FirstName LIKE "%' . $nom . '%")';
        }
        if ($societe) {
            $requete.= ' AND Account.Name LIKE "%' . $societe . '%"';
        }
        if ($ville) {
            $requete.= ' AND Account.BillingCity LIKE "%' . $ville . '%"';
        }
        if ($cp) {
            $requete.= ' AND Account.BillingPostalCode LIKE "%' . $cp . '%"';
        }
        if ($mail) {
            $requete.= ' AND Email LIKE "%' . $mail . '%"';
        }
        if ($nature) {
            $requete.= ' AND Account.Type = "' . $nature . '"';
        }
        if ($createur) {
            $requete .= ' AND Owner.Username LIKE "' . $createur . '%"';
        }
        $result = $sfClient->query($requete);
        
        $data = array();
        foreach ($result->records as $record) {
            array_push($data, $record);
	}

        if($output['type'] == '' || $output['type'] == 'TABLE') {
            if (count($data) != 0) {
                $datagrid = new Structures_DataGrid(TAILLE_LISTE);
                $datagrid->setDefaultSort(array('LastName' => 'ASC'));
                $test = $datagrid->bind($data, array(), "Array");

                $datagrid->setRendererOption('onMove', 'afficherContact', true);
                $datagrid->setRendererOption('evenRowAttributes', array('class' => 'roweven'), true);
                $datagrid->setRendererOption('oddRowAttributes', array('class' => 'oddeven'), true);
                $datagrid->setRendererOption('sortIconDESC', '<img src="' . IMG_DESC . '" />', true);
                $datagrid->setRendererOption('sortIconASC', '<img src="' . IMG_ASC . '" />', true);

                $datagrid->addColumn(new Structures_DataGrid_Column('Nom / Prénom', null, 'LastName', null, null, array('ContactSF', 'showName')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Fonction', 'Title', 'Title'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Société', null, null, null, null, array('ContactSF', 'showAccount')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Mail', null, 'Email', null, null, array('ContactSF', 'showMail')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Téléphone', 'Telephone_Ligne_directe__c', 'Telephone_Ligne_directe__c'));
                $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, null, null, array('ContactSF', 'showButton')));

                $nbContacts = $datagrid->getRecordCount();
            }


            if (count($data) != 0) {
                foreach (func_get_args() as $key => $value) {
                    if($arguments[$key] != 'output')
                        $params .= $arguments[$key] . '=' . $value . '&';
                }
                $params .= 'type=CSV';
                $params .= '&orderBy' . '=' . (($output['orderBy']) ? $output['orderBy'] : 'LastName');
                $params .= '&direction' . '=' . (($output['direction']) ? $output['direction'] : 'ASC');
                
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '<span style="float:left">Export : <a href="../source/index.php?a=consulterContact&' . $params . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" /></a></span></p>';
                $html .= $datagrid->getOutput();
                $html .= '<p class="pagination">' . $datagrid->getOutput(DATAGRID_RENDER_PAGER) . '</p>';
            }
            else
                $html .= NO_DATA_INFO;
        }
        elseif($output['type'] == 'CSV') {
            if (count($data) != 0) {
                header("Pragma: public");
                header('Content-type: text/x-csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="contacts.csv"');

                $datagrid = new Structures_DataGrid();
                $datagrid->setDefaultSort(array($output['orderBy'] => $output['direction']));
                $rendererOptions = array( 'filename' => "contacts.csv", 'numberAlign' => false, 'delimiter' => ";");
                $datagrid->bind($data, array(), "Array");
                $datagrid->addColumn(new Structures_DataGrid_Column('Nom / Prénom', null, 'LastName', null, null, array('ContactSF', 'showName')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Fonction', 'Title', 'Title'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Société', null, null, null, null, array('ContactSF', 'showAccount')));
                $datagrid->addColumn(new Structures_DataGrid_Column('Mail', 'Email', 'Email'));
                $datagrid->addColumn(new Structures_DataGrid_Column('Téléphone', 'Telephone_Ligne_directe__c', 'Telephone_Ligne_directe__c'));
                $res = $datagrid->render('CSV', $rendererOptions);
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
        $sfClient = new SFClient();
        $db_a = connecter();
        if ($Id_compte) {
            $cp = explode('-', $Id_compte);
            $req = 'AND (AccountId ="' . mysql_real_escape_string($cp[1]) . '")';
        }
        $result = $sfClient->query('SELECT Id, LastName, FirstName FROM Contact WHERE A_quitte_entreprise__c = false ORDER BY LastName');
        foreach ($result->records as $record) {
            $html .= '<option value="SF-' . $record->Id . '" ' . $contact[$record->Id] . '>' . $record->LastName . ' ' . $record->FirstName . '</option>';
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

    public function showName($params) {
        extract($params);
        return $record->LastName . ' ' . $record->FirstName;
    }
    
    public function showAccount($params) {
        extract($params);
        return $record->Account->Name;
    }
    
    public function showMail($params) {
        extract($params);
        return '<a href="mailto:' . $record->Email . '">' . $record->Email . '</a>';
    }
    
    public function showButton($params) {
        extract($params);
        if (self::getNbCase($record->c_numerocontact . '-' . $record->c_auxiliaire)) {
            return '<a href="index.php?a=consulterAffaire&Id_contact=' . $record->c_numerocontact . '-' . $record->c_auxiliaire . '"><img src="' . IMG_INFO . '"></a>';
        }
    }
}

?>