<?php

/**
 * Fichier Devis.php
 *
 * @author    Mathieu Perrin
 * @copyright    Proservia
 * @package    ProjetAGC
 */

class Devis {
    /**
     * Tableau des profils associés au devis
     *
     * @access public
     * @var array 
     */
    public $profil;
    
    /**
     * Constructeur de la classe affaire
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'Affaire
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($id_devis, $id_opportunite) {
        try {
            if ($id_devis) {
                $sfClient = new SFClient();
                $db = connecter();
                $result = $sfClient->query('
                    SELECT Id, Name, BillingName, BillingStreet, BillingCity, BillingState, BillingPostalCode, BillingCountry,
                        Date_d_but_prestation__c, Date_fin_de_prestation__c, Contact.Name, Contact.Id, GrandTotal, 
                        (SELECT Id, PricebookEntry.Product2.Name, UnitPrice, Quantity, PricebookEntry.Product2.Id, PricebookEntry.Product2.ProductCode,
                        Description, PricebookEntry.Product2.Salaire_moyen__c, Date_fin_de_commande__c, Date_debut_de_commande__c, Agence__c
                        FROM QuoteLineItems),
                        AdditionalName, AdditionalStreet, AdditionalCity, AdditionalState, AdditionalPostalCode, 
                        AdditionalCountry, Description, Opportunity.Account.Name, Opportunity.Account.Id,
                        Conditions_de_Facturation__c, R_f_rence_Devis__c, Am_nagement_du_travail__c,Code_otp__c
                    FROM Quote WHERE Id = \'' . mysql_real_escape_string($id_devis) .'\'  AND Status IN (\'Accepté\', \'Signé\')');
                $this->Id_devis = $id_devis;
                $this->reference_devis = $result->records[0]->R_f_rence_Devis__c;
                $this->Id_compte = 'SF-' . $result->records[0]->Opportunity->Account->Id;
                $this->Id_compte = $result->records[0]->Opportunity->Account->Id;
                $this->nom = $result->records[0]->Name;
                $this->date_debut = $result->records[0]->Date_d_but_prestation__c;
                $this->date_fin_commande = $result->records[0]->Date_fin_de_prestation__c;
                $this->duree = $result->records[0]->Jours_Ouvres__c;
                $this->description = $result->records[0]->Description;
                $this->contact = $result->records[0]->Contact->Name;
                $this->Id_contact = 'SF-' . $result->records[0]->Contact->Id;
                $this->condition_reglement = $result->records[0]->Conditions_de_Facturation__c;
                $this->ca = $result->records[0]->GrandTotal;
                $this->couverture_horaire = $result->records[0]->Am_nagement_du_travail__c;
                
                $this->adresse_fac = $result->records[0]->BillingStreet;
                $this->code_postal_fac = $result->records[0]->BillingPostalCode;
                $this->ville_fac = $result->records[0]->BillingCity;
                $this->pays_fac = $result->records[0]->BillingCountry;
                
                $this->code_otp = $result->records[0]->Code_otp__c;
                
                $this->profil = array();
                $i = 0;
                while($i < $result->records[0]->QuoteLineItems->size) {
                    $ar = array();
                    $ar['id'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Id;
                    $ar['nom'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Name;
                    $ar['quantite'] = $result->records[0]->QuoteLineItems->records[$i]->Quantity;
                    $ar['prix_vente'] = $result->records[0]->QuoteLineItems->records[$i]->UnitPrice;
                    $ar['salaire_moyen'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Salaire_moyen__c;
                    $ar['description'] = $result->records[0]->QuoteLineItems->records[$i]->Description;
                    $ar['date_debut'] = $result->records[0]->QuoteLineItems->records[$i]->Date_debut_de_commande__c;
                    $ar['date_fin'] = $result->records[0]->QuoteLineItems->records[$i]->Date_fin_de_commande__c;
                    $ar['code_produit'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->ProductCode;
                    $ar['agence'] = $result->records[0]->QuoteLineItems->records[$i]->Agence__c;
                    array_push($this->profil, $ar);
                    $i++;
                }
            }
            else if ($id_opportunite) {
                $sfClient = new SFClient();
                $db = connecter();
                $result = $sfClient->query('
                    SELECT Id, Name, BillingName, BillingStreet, BillingCity, BillingState, BillingPostalCode, BillingCountry,
                        Date_d_but_prestation__c, Date_fin_de_prestation__c, Contact.Name, Contact.Id, GrandTotal, 
                        (SELECT Id, PricebookEntry.Product2.Name, UnitPrice, Quantity, PricebookEntry.Product2.Id, PricebookEntry.Product2.ProductCode,
                        Description, PricebookEntry.Product2.Salaire_moyen__c, Date_fin_de_commande__c, Date_debut_de_commande__c, Agence__c
                        FROM QuoteLineItems),
                        AdditionalName, AdditionalStreet, AdditionalCity, AdditionalState, AdditionalPostalCode, 
                        AdditionalCountry, Description, Opportunity.Account.Name, Opportunity.Account.Id,
                        Conditions_de_Facturation__c, R_f_rence_Devis__c, Am_nagement_du_travail__c,Code_otp__c
                    FROM Quote WHERE OpportunityId = \'' . mysql_real_escape_string($id_opportunite) .'\' AND Status IN (\'Accepté\', \'Signé\')
                    ORDER BY CreatedDate DESC LIMIT 1');
                $this->Id_devis = $result->records[0]->Id;
                $this->reference_devis = $result->records[0]->R_f_rence_Devis__c;
                $this->compte = $result->records[0]->Opportunity->Account->Name;
                $this->Id_compte = 'SF-' . $result->records[0]->Opportunity->Account->Id;
                $this->nom = $result->records[0]->Name;
                $this->date_debut = $result->records[0]->Date_d_but_prestation__c;
                $this->date_fin_commande = $result->records[0]->Date_fin_de_prestation__c;
                $this->duree = $result->records[0]->Jours_Ouvres__c;
                $this->description = $result->records[0]->Description;
                $this->contact = $result->records[0]->Contact->Name;
                $this->Id_contact = 'SF-' . $result->records[0]->Contact->Id;
                $this->condition_reglement = $result->records[0]->Conditions_de_Facturation__c;
                $this->ca = $result->records[0]->GrandTotal;
                $this->couverture_horaire = $result->records[0]->Am_nagement_du_travail__c;
                
                $this->adresse_fac = $result->records[0]->BillingStreet;
                $this->code_postal_fac = $result->records[0]->BillingPostalCode;
                $this->ville_fac = $result->records[0]->BillingCity;
                $this->pays_fac = $result->records[0]->BillingCountry;
                
                $this->code_otp = $result->records[0]->Code_otp__c;
                
                $this->profil = array();
                $i = 0;
                while($i < $result->records[0]->QuoteLineItems->size) {
                    $ar = array();
                    $ar['id'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Id;
                    $ar['nom'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Name;
                    $ar['quantite'] = $result->records[0]->QuoteLineItems->records[$i]->Quantity;
                    $ar['prix_vente'] = $result->records[0]->QuoteLineItems->records[$i]->UnitPrice;
                    $ar['salaire_moyen'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->Salaire_moyen__c;
                    $ar['description'] = $result->records[0]->QuoteLineItems->records[$i]->Description;
                    $ar['date_debut'] = $result->records[0]->QuoteLineItems->records[$i]->Date_debut_de_commande__c;
                    $ar['date_fin'] = $result->records[0]->QuoteLineItems->records[$i]->Date_fin_de_commande__c;
                    $ar['code_produit'] = $result->records[0]->QuoteLineItems->records[$i]->PricebookEntry->Product2->ProductCode;
                    $ar['agence'] = $result->records[0]->QuoteLineItems->records[$i]->Agence__c;
                    array_push($this->profil, $ar);
                    $i++;
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }
    
    /**
     * Affichage d'une liste contenant les profils du devis
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
            $html .= '<option ' . $onMouseEvent . ' data-codeproduit="' . $value['code_produit'] . '" data-agencemission="' . $value['agence'] . '" data-salaire="' . $salaire . '"  data-datedebut="' . DateMysqltoFr($value['date_debut']) . '" data-datefin="' . DateMysqltoFr($value['date_fin']) . '" data-prix="' . $value['prix_vente'] . '" data-duree="' . $value['quantite'] . '" value="' . $key . '" ' . $p[$key] . '>' . $value['nom'] . '</option>';
        }
        return $html;
    }
}

?>