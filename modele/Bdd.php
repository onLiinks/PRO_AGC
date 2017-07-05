<?php

/**
 * Fichier Bdd.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Bdd
 */
class Bdd {

    /**
     * Affichage d'une select box contenant les bases de données des sociétés
     *
     * @return string
     */
    public static function getList() {
        $db = connecter_default();
        $result = $db->query('SELECT * FROM societe ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_societe . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libellé de la BDD
     *
     * @param int Identifiant de la BDD
     *
     * @return string	   
     */
    public static function getLibelle($i) {
        $db = connecter_default();
        return $db->query('SELECT libelle FROM societe WHERE Id_societe=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }
    
    /**
     * Affichage de la base Cegid a utiliser
     *
     * @param int Identifiant de la BDD
     *
     * @return string	   
     */
    public static function getCegidDatabase($i) {
        $db = connecter_default();
        return $db->query('SELECT cegid_database FROM societe WHERE Id_societe=' . mysql_real_escape_string((int) $i))->fetchRow()->cegid_database;
    }
    
    /**
     * Affichage de la base Cegid a utiliser
     *
     * @param int Identifiant de la BDD
     *
     * @return string	   
     */
    public static function getCegidDatabaseByCode($i) {
        $db = connecter_default();
        return $db->query('SELECT cegid_database FROM societe WHERE code_societe="' . mysql_real_escape_string($i).'"')->fetchRow()->cegid_database;
    }
    
    /**
     * Affichage de la base Cegid a utiliser
     *
     * @param int Identifiant de la BDD
     *
     * @return string	   
     */
    public static function getAgcDatabaseByLibelle($i) {
        $db = connecter_default();
        return $db->query('SELECT agc_database FROM societe WHERE libelle=' . mysql_real_escape_string((int) $i))->fetchRow()->agc_database;
    }
    
    /**
     * Récupération des bases Cegid en cours
     *
     * @return string	   
     */
    public static function getCegidDatabases($i, $withCode = false) {
        $db = connecter_default();
        $code_societe = ($withCode === true) ? ', code_societe' : '';
        $result = $db->query('SELECT cegid_database ' . $code_societe . ' FROM societe
            WHERE (date_entree < NOW() AND (date_fusion > NOW() OR date_fusion = "0000-00-00"))
                    AND agc_database = "' . mysql_real_escape_string($i) . '"');
        $companies = array();
        while ($ligne = $result->fetchRow()) {
            if($withCode === true) {
                array_push($companies, array('cegid_database' => $ligne->cegid_database, 'code_societe' => $ligne->code_societe));
            }
            else {
                array_push($companies, $ligne->cegid_database);
            }
        }
        return $companies;
    }
    
    /**
     * Récupération des bases AGC en cours
     *
     * @return string	   
     */
    public static function getDatabases() {
        $db = connecter_default();
        $result = $db->query('SELECT DISTINCT agc_database FROM societe
            WHERE (date_entree < NOW() AND (date_fusion > NOW() OR date_fusion = "0000-00-00"))');
        $companies = array();
        while ($ligne = $result->fetchRow()) {
            array_push($companies, $ligne->agc_database);
        }
        return $companies;
    }
}

?>