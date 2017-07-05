<?php

/**
 * Fichier Affaire.php
 *
 * @author    Anthony Anne
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Affaire
 * @package    ProjetAGC
 */
class Opportunite {
    /**
     * Identifiant de l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_affaire;
    
    /**
     * Compte associé à l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_compte;
    
    /**
     * Client final
     *
     * @access public
     * @var int 
     */
    public $Id_compte_final;
    
    /**
     * Contact 1 associé à l'affaire
     *
     * @access public
     * @var string
     */
    public $Id_contact1;
    
    /**
     * Contact 2 associé à l'affaire
     *
     * @access public
     * @var string 
     */
    public $Id_contact2;
    
    /**
     * Créateur de l'affaire
     *
     * @access public
     * @var string
     */
    public $createur;
    
    /**
     * Commercial de l'affaire
     *
     * @access public
     * @var string 
     */
    public $commercial;
    
    /**
     * Apporteur de l'affaire
     *
     * @access public
     * @var string
     */
    public $apporteur;
    
    /**
     * Identifiant du statut de l'affaire
     *
     * @access private
     * @var int 
     */
    public $Id_statut;
    
    /**
     * Identifiant de l'agence
     *
     * @access public
     * @var string
     */
    public $Id_agence;
    
    /**
     * Tableau des profils associés à l'opportunité
     *
     * @access public
     * @var array 
     */
    public $profil;

    /**
     * Identifiant de la description de l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_description;

    /**
     * Identifiant du pole associé à l'affaire
     *
     * @access public
     * @var int
     */
    public $Id_pole;

    /**
     * Identifiant du type de contrat de l'affaire
     *
     * @access public
     * @var int 
     */
    public $Id_type_contrat;
    
    /**
     * Type d'opportunité
     *
     * @access public
     * @var int 
     */
    public $type;

    
    /**
     * Debloquer devis ou on
     *
     * @access public
     * @var boolean
     */
    public $debloquer_devis;
    
    /**
     * Constructeur de la classe affaire
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'Affaire
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code) {
        try {
            if ($code) {
                $sfClient = new SFClient();
                $db = connecter();
                $result = $sfClient->query('
                    SELECT Id, Owner.Email, Pole__c, RecordType.Name, Name, Agence__c, ID_AGC__c, N_Opportunit_M_re__c,
                    AccountId, Amount, StageName, Date_debut_de_commande__c, Date_fin_de_commande__c, Description, 
                    Type, Nombre_de_jours_commande__c, (SELECT ContactId, isPrimary FROM OpportunityContactRoles),
                    Probability, ExpectedRevenue, Type_opportunite__c, Account.Name, Type_Infog_rance__c,
                    (SELECT Quantity, Cout_de_revient__c, Frais__c, UnitPrice, Cout_de_revient_Total__c, TotalPrice, 
                            Marge__c, Description, PricebookEntry.Product2.Salaire_moyen__c , PricebookEntry.Product2.Statut__c,
                            PricebookEntry.Product2.Name, PricebookEntry.Product2.Id, PricebookEntry.Product2.ProductCode,
                            Date_fin_de_commande__c, Date_debut_de_commande__c, Agence__c
                    FROM OpportunityLineItems), Apporteur_d_affaires__r.Email,SyncedQuoteId, RecordTypeId,
                    CreatedDate, (SELECT Id, Name, Reference_BDC_client__c FROM PurchaseOrder__r), Debloquer_devis__c,
                    Client_final__r.Id,Client_final__r.Name,
                    Politique_securite_demandee__c,Necessite_plan_prevention__c,SMR__c,
                    Equipement_securite_a_prevoir__c,Mission_implique_isolement__c,Formations_specifiques_exigees__c,
                    Poste_implique_habilitation__c,Presence_politique_client__c,Documents_Proservia_specifiques__c
                    FROM Opportunity WHERE Id = \'' . mysql_real_escape_string($code) .'\' AND Societ_Instance_IT__c =\'PROSERVIA\'');
                $this->Id_affaire = $code;
                $this->date_creation = DateTime::createFromFormat('Y-m-d?H:i:s?????', $result->records[0]->CreatedDate);
                $this->reference_affaire = $result->records[0]->ID_AGC__c;
                $this->reference_affaire_mere = $result->records[0]->N_Opportunit_M_re__c;
                $this->mail_proprietaire = $result->records[0]->Owner->Email;
                $com = explode('@', $result->records[0]->Owner->Email);
                $this->commercial = $com[0];
                $app = explode('@', $result->records[0]->Apporteur_d_affaires__r->Email);
                $this->apporteur = $app[0];
                $this->Id_compte = 'SF-' . $result->records[0]->AccountId;
                $this->Id_compte_final = 'SF-' . $result->records[0]->Client_final__r->Id;
                $this->nomCompte = $result->records[0]->Account->Name;
                $this->nomCompteFinal = $result->records[0]->Client_final__r->Name;
                $this->Id_agence = $result->records[0]->Agence__c;
                $this->Id_pole = $result->records[0]->Pole__c;
                $this->Id_intitule = $result->records[0]->Name;
                $this->type_opportunite = $result->records[0]->Type_opportunite__c;
                $this->Id_type_contrat = $result->records[0]->RecordType->Name;
                $this->record_type = $result->records[0]->RecordTypeId;
                $this->type_infogerance = $result->records[0]->Type_Infog_rance__c;
                $this->Id_statut = $result->records[0]->StageName;
                $this->date_debut = $result->records[0]->Date_debut_de_commande__c;
                $this->date_fin_commande = $result->records[0]->Date_fin_de_commande__c;
                $this->duree = $result->records[0]->Nombre_de_jours_commande__c;
                $this->description = $result->records[0]->Description;
                $this->type = $result->records[0]->Type;
                $this->ca = $result->records[0]->Amount;
                $this->ca_prev = $result->records[0]->ExpectedRevenue;
                $this->probabilite = $result->records[0]->Probability;
                $this->Id_devis = $result->records[0]->SyncedQuoteId;
                $this->Id_bdc = $result->records[0]->PurchaseOrder__r->records[0]->Name;
                $this->debloquer_devis = $result->records[0]->Debloquer_devis__c;
                $this->politique_securite_demandee = ($result->records[0]->Politique_securite_demandee__c=='Oui') ? 1 : 0;
                $this->necessite_plan_prevention = $result->records[0]->Necessite_plan_prevention__c;
                $this->equipement_securite_a_prevoir = $result->records[0]->Equipement_securite_a_prevoir__c;
                $this->mission_implique_isolement = $result->records[0]->Mission_implique_isolement__c;
                $this->formations_specifiques_exigees = $result->records[0]->Formations_specifiques_exigees__c;
                $this->poste_implique_habilitation = $result->records[0]->Poste_implique_habilitation__c;
                $this->presence_politique_client = $result->records[0]->Presence_politique_client__c;
                $this->documents_Proservia_specifiques = $result->records[0]->Documents_Proservia_specifiques__c;
                $this->smr = $result->records[0]->SMR__c;
                if($result->records[0]->OpportunityContactRoles->records[0]->ContactId)
                    $this->Id_contact1 = 'SF-' . $result->records[0]->OpportunityContactRoles->records[0]->ContactId;
                if($result->records[0]->OpportunityContactRoles->records[1]->ContactId)
                    $this->Id_contact2 = 'SF-' . $result->records[0]->OpportunityContactRoles->records[1]->ContactId;
                $this->profil = array();
                $i = 0;
                while($i < $result->records[0]->OpportunityLineItems->size) {
                    $ar = array();
                    $ar['id'] = $result->records[0]->OpportunityLineItems->records[$i]->PricebookEntry->Product2->Id;
                    $ar['salaire_moyen'] = $result->records[0]->OpportunityLineItems->records[$i]->PricebookEntry->Product2->Salaire_moyen__c;
                    $ar['statut'] = $result->records[0]->OpportunityLineItems->records[$i]->PricebookEntry->Product2->Statut__c;
                    $ar['nom'] = $result->records[0]->OpportunityLineItems->records[$i]->PricebookEntry->Product2->Name;
                    $ar['quantite'] = $result->records[0]->OpportunityLineItems->records[$i]->Quantity;
                    $ar['cout_revient'] = $result->records[0]->OpportunityLineItems->records[$i]->Cout_de_revient__c;
                    $ar['frais'] = $result->records[0]->OpportunityLineItems->records[$i]->Frais__c;
                    $ar['prix_vente'] = $result->records[0]->OpportunityLineItems->records[$i]->UnitPrice;
                    $ar['cout_revient_total'] = $result->records[0]->OpportunityLineItems->records[$i]->Cout_de_revient_Total__c;
                    $ar['prix_vente_total'] = $result->records[0]->OpportunityLineItems->records[$i]->TotalPrice;
                    $ar['marge'] = $result->records[0]->OpportunityLineItems->records[$i]->Marge__c;
                    $ar['description'] = $result->records[0]->OpportunityLineItems->records[$i]->Description;
                    $ar['date_debut'] = $result->records[0]->OpportunityLineItems->records[$i]->Date_debut_de_commande__c;
                    $ar['date_fin'] = $result->records[0]->OpportunityLineItems->records[$i]->Date_fin_de_commande__c;
                    $ar['code_produit'] = $result->records[0]->OpportunityLineItems->records[$i]->PricebookEntry->Product2->ProductCode;
                    $ar['agence'] = $result->records[0]->OpportunityLineItems->records[$i]->Agence__c;
                    array_push($this->profil, $ar);
                    $i++;
                }
            }
        } catch (Exception $e) {
//            var_dump($this);
            var_dump($e);
            throw new AGCException($e->getMessage());
        }
    }
    
    /**
     * Affichage du contact/fonction principal du compte
     *
     * @return string	 
     */
    public function getContactPrincipal() {
        $sfClient = new SFClient();
        $db = connecter();
        $ligne = $sfClient->query('SELECT (SELECT Contact.Name, Role FROM OpportunityContactRoles WHERE isPrimary = true) 
                    FROM Opportunity WHERE Id = \'' . mysql_real_escape_string($this->Id_affaire) .'\'')->records[0];
        $ar[0] = $ligne->OpportunityContactRoles->records[0]->Contact->Name;
        $ar[1] = $ligne->OpportunityContactRoles->records[0]->Role;
        return $ar;
    }
    
    /**
     * Affichage d'une liste contenant les profils de l'opportunités
     *
     * @return string	 
     */
    public function getProfilList($profil = '') {
        $p[$profil] = 'selected="selected"';
        foreach ($this->profil as $key => $value) {
            $salaire = $value['salaire_moyen'] * 12;
            $salaire = substr($salaire, 0, -3);
            if($value['description']) {
                $desc = str_replace('"', "'", mysql_escape_string($value['description']));
                $onMouseEvent = 'onmouseover="$(\'desc\').update(\'Description : '.$desc.'\')" onmouseout="$(\'desc\').update(\'\')"';
            }
            $html .= '<option ' . $onMouseEvent . ' data-codeproduit="' . $value['code_produit'] . '"  data-agencemission="' . $value['agence'] . '"  data-salaire="' . $salaire . '" data-datedebut="' . DateMysqltoFr($value['date_debut']) . '" data-datefin="' . DateMysqltoFr($value['date_fin']) . '" data-prix="' . $value['prix_vente'] . '" data-duree="' . $value['quantite'] . '" value="' . $key . '" ' . $p[$key] . '>' . $value['nom'] . '</option>';
        }
        return $html;
    }
    
    /**
     * Récupère les dates de validités des opportunités liées
     * Si AT = dates des devis, sinon dates des opportunités
     * 
     * @return Array	 
     */
    public function getRangeDates() {
        $sfClient = new SFClient();
        $version = new Version(370);
        if($this->record_type == '012D0000000JuJPIA0' && !$this->debloquer_devis && $this->date_creation >= $version->date_version) {
            $db = connecter();
            if($this->reference_affaire_mere == null) {
                $result = $sfClient->query('
                    SELECT Id, Date_d_but_prestation__c, Date_fin_de_prestation__c FROM Quote
                    WHERE OpportunityId IN 
                        (SELECT Id FROM Opportunity
                            WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\')
                        )
                    AND Status IN (\'Accepté\', \'Signé\')
                ');
            }
            else {
                $result = $sfClient->query('
                    SELECT Id, Date_d_but_prestation__c, Date_fin_de_prestation__c FROM Quote
                    WHERE OpportunityId IN 
                        (SELECT Id FROM Opportunity
                            WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\')
                        )
                    AND Status IN (\'Accepté\', \'Signé\')
                ');
            }

            if($result->size != 0) {
                $dateMin = DateTime::createFromFormat('Y-m-d', $result->records[0]->Date_d_but_prestation__c);
                $dateMax = DateTime::createFromFormat('Y-m-d', $result->records[0]->Date_fin_de_prestation__c);
                foreach ($result->records as $value) {                
                    if(DateTime::createFromFormat('Y-m-d', $value->Date_d_but_prestation__c) < $dateMin) {
                        $dateMin = DateTime::createFromFormat('Y-m-d', $value->Date_d_but_prestation__c);
                    }
                    if(DateTime::createFromFormat('Y-m-d', $value->Date_fin_de_prestation__c) > $dateMax) {
                        $dateMax = DateTime::createFromFormat('Y-m-d', $value->Date_fin_de_prestation__c);
                    }
                }

                $dates =  array('date_min' => $dateMin->format('d-m-Y'), 'date_max' => $dateMax->format('d-m-Y'));
            }
        }
        else {
            $db = connecter();
            if($this->reference_affaire_mere == null) {
                $result = $sfClient->query('
                    SELECT N_Opportunit_M_re__c Id, MIN(Date_debut_de_commande__c) DateMin, MAX(Date_fin_de_commande__c) DateMax
                    FROM Opportunity
                    WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\')
                    AND Date_debut_de_commande__c != null AND Date_fin_de_commande__c != null
                    GROUP BY N_Opportunit_M_re__c
                ');
            }
            else {
                $result = $sfClient->query('
                    SELECT N_Opportunit_M_re__c Id, MIN(Date_debut_de_commande__c) DateMin, MAX(Date_fin_de_commande__c) DateMax
                    FROM Opportunity
                    WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\')
                    AND Date_debut_de_commande__c != null AND Date_fin_de_commande__c != null
                    GROUP BY N_Opportunit_M_re__c
                ');
            }

            $dateMin = DateTime::createFromFormat('Y-m-d', $result->records[0]->fields->DateMin);
            $dateMax = DateTime::createFromFormat('Y-m-d', $result->records[0]->fields->DateMax);
            foreach ($result->records as $value) {                
                if(DateTime::createFromFormat('Y-m-d', $value->fields->DateMin) < $dateMin) {
                    $dateMin = DateTime::createFromFormat('Y-m-d', $value->fields->DateMin);
                }
                if(DateTime::createFromFormat('Y-m-d', $value->fields->DateMax) > $dateMax) {
                    $dateMax = DateTime::createFromFormat('Y-m-d', $value->fields->DateMax);
                }
            }
            $dates =  array('date_min' => $dateMin->format('d-m-Y'), 'date_max' => $dateMax->format('d-m-Y'));
        }
        
        return $dates;
    }
    
    /**
     * Récupère les dates de l'oppy
     * @return Array	 
     */
    public function getDates() {
        $dateMin = $dateMax = '';
        if(!is_null($this->date_debut)) {
            $dateMin = DateTime::createFromFormat('Y-m-d', $this->date_debut);
            $dateMin = $dateMin->format('d-m-Y');
        }
            
        if(!is_null($this->date_fin_commande)) {
                $dateMax = DateTime::createFromFormat('Y-m-d', $this->date_fin_commande);
                $dateMax = $dateMax->format('d-m-Y');
        }

		$dates =  array('date_min' => $dateMin, 'date_max' => $dateMax);
        return $dates;
    }
    
    /**
     * Récupère les infos des oppys liés
     * 
     * @return Array	 
     */
    public function getLinkedOpportunities() {
        $sfClient = new SFClient();
        if($this->reference_affaire_mere == null) {
            $result = $sfClient->query('
                SELECT Id, Owner.Email, Pole__c, RecordType.Name, Name, Agence__c, ID_AGC__c, N_Opportunit_M_re__c,
                AccountId, Amount, StageName, Date_debut_de_commande__c, Date_fin_de_commande__c, Description, 
                Type,Probability, ExpectedRevenue, Type_opportunite__c, Account.Name, Type_Infog_rance__c
                FROM Opportunity
                WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire) .'\')');
        }
        else {
            $result = $sfClient->query('
                SELECT Id, Owner.Email, Pole__c, RecordType.Name, Name, Agence__c, ID_AGC__c, N_Opportunit_M_re__c,
                AccountId, Amount, StageName, Date_debut_de_commande__c, Date_fin_de_commande__c, Description, 
                Type,Probability, ExpectedRevenue, Type_opportunite__c, Account.Name, Type_Infog_rance__c
                FROM Opportunity
                WHERE (ID_AGC__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\' OR N_Opportunit_M_re__c = \'' . mysql_real_escape_string($this->reference_affaire_mere) .'\')');
        }
        return $result->records;
    }
    
    /**
     * Affichage d'une select box contenant les affaires du compte
     *
     * @return string	
     */
    public function getListDevis($idDevis) {
        if ($idDevis)
            $dev[$idDevis] = 'selected="selected"';
        $sfClient = new SFClient();
        $db = connecter();
        $result = $sfClient->query('SELECT Id, Name FROM Quote WHERE OpportunityId = \'' . $this->Id_compte . '\' ' . $req . ' AND Status IN (\'Accepté\', \'Signé\')  ORDER BY Name');
        foreach ($result->records as $record) {
            $html .= '<option value="' . $record->Id . '" ' . $dev[$record->Id] . '>'. $record->Name . '</option>';
        }
        return $html;
    }
}

?>