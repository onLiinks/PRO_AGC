<?php

/**
 * Fichier SFMetadataClient.php
 *
 * @author Yannick BETOU
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * D�claration de la classe SFClient
 */
class SFMetadataClient extends SforceMetadataClient {
    /**
     * Constructeur de la classe SFClient
     *
     * Constructeur initialisant les donn�es membres
     *
     */    
    public function __construct() {
        // Cr�ation de la connexion � Salesforce
        $sfClient = new SFClient();
        parent::__construct(SF_METADATA_WSDL, $sfClient->login(SF_USER, SF_PASSWD . SF_TOKEN), $sfClient->getConnection());
    }
}
?>
