<?php

/**
 * Fichier SFClient.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe SFClient
 */
class SFClient extends SforceEnterpriseClient {
    /**
     * Constructeur de la classe SFClient
     *
     * Constructeur initialisant les données membres
     *
     */    
    public function __construct($forceNewSession = false) {
        // Création de la connexion à Salesforce
        parent::__construct();
        $this->createConnection(SF_WSDL, array('proxy_host' => 'http://213.56.106.7',
            'proxy_port' => 8080, 'proxy_login' => 'tachesadmin', 
            'proxy_password' => 'F@llaitP@s'), 
            array('encoding' => 'ISO-8859-1')
        );      
        if($forceNewSession === false) {
            $file = fopen(SF_TMP, 'c+');
            $sId = fgets($file);
            $sLocation = fgets($file);

            if($sId === false) {
                $this->login(SF_USER, SF_PASSWD . SF_TOKEN);
                $file = fopen(SF_TMP, 'c+');
                fputs($file, $this->getSessionId() . PHP_EOL . $this->getLocation());
                fclose($file);
            }
            else {
                $this->setEndpoint($sLocation);
                $this->setSessionHeader($sId);
            }
        }
        else {
            $this->login(SF_USER, SF_PASSWD . SF_TOKEN);
        }
    }
    
    public function query($query) {
        try {
            return parent::query($query);
        } catch (Exception $exc) {
            $this->login(SF_USER, SF_PASSWD . SF_TOKEN);
            $file = fopen(SF_TMP, 'c+');
            fputs($file, $this->getSessionId() . PHP_EOL . $this->getLocation());
            fclose($file);
            return parent::query($query);
        }
    }
    
    public function queryMore($queryLocator) {
        try {
            return parent::queryMore($queryLocator);
        } catch (Exception $exc) {
            $this->login(SF_USER, SF_PASSWD . SF_TOKEN);
            $file = fopen(SF_TMP, 'c+');
            fputs($file, $this->getSessionId() . PHP_EOL . $this->getLocation());
            fclose($file);
            return parent::queryMore($queryLocator);
        }
    }
    
    public function logout() {
        $file = fopen(SF_TMP, 'r+');
        ftruncate($file, 0);
        try {
            parent::logout();
        } catch (Exception $exc) { }
    }
}
?>
