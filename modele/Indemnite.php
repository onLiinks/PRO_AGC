<?php

/**
 * Fichier Indemnite.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Indemnite
 */
class Indemnite {

    /**
     * Identifiant de l'indemnite
     *
     * @access private
     * @var int 
     */
    private $Id_indemnite;
    /**
     * Nom de l'indemnite
     *
     * @access public
     * @var string 
     */
    public $nom;
    /**
     * Type de l'indemnite
     *
     * @access private
     * @var int 
     */
    private $type;

    /**
     * Constructeur de la classe Indemnite
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'indemnite
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_indemnite = '';
                $this->nom = '';
                $this->type = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_indemnite = '';
                $this->nom = htmlscperso(stripslashes($tab['nom']), ENT_QUOTES);
                $this->type = htmlscperso(stripslashes($tab['type']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM indemnite WHERE Id_indemnite=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_indemnite = $code;
                $this->nom = $ligne->nom;
                $this->type = $ligne->type;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_indemnite = $code;
                $this->nom = htmlscperso(stripslashes($tab['nom']), ENT_QUOTES);
                $this->type = htmlscperso(stripslashes($tab['type']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Affichage des checkbox contenant les indemnités
     *
     * @param int Valeur de l'identifiant du contrat delegation
     * @param int Valeur du type de l'indemnite
     *
     * @return string
     */
    public static function getCheckboxList($Id_cd, $destination, $region, $type_deplacement) {
        if($type_deplacement === 'pas_deplacement')
            $html = '<br /><div id="aideTypeDep">Aide frais.</div>';
        elseif($type_deplacement === 'petit_deplacement')
            $html = '<br /><div id="aideTypeDep">Aide frais petit déplacement.</div>';
        elseif($type_deplacement === 'grand_deplacement')
            $html = '<br /><div id="aideTypeDep">Aide frais grand déplacement.</div>';
        
        if($region === 'etranger' &&  $destination != 'PWS') {
            $html .= '<br /><a href="https://manpowergroupfr.my.salesforce.com/sfc/p/#D0000000nKuq/a/D0000000Tdar/r3Pq_XhkQEGzA5rjRqyR.OlWlJNkzNKJ_hMCtuM9Y2Y=">Formulaire de demande de déplacement.</a>';
            return $html;
        }
        
        if ($Id_cd) {
            $cd = new ContratDelegation($Id_cd, array());
            if (!empty($cd->indemnite)) {
                foreach ($cd->indemnite as $i) {
                    $indemnite[$i['id']] = 'checked="checked"';
                    $plafondIndemnite[$i['id']] = $i['plafond'];
                }
            }
        }

        $db = connecter();
        $result = $db->query('SELECT i.nom, i.condition, ri.type, i.Id_indemnite, ti.nom AS nom_type, i.exclusion,
                                i.plafond
                              FROM regle_indemnite ri 
                              INNER JOIN indemnite i ON ri.Id_indemnite = i.Id_indemnite
                              INNER JOIN type_indemnite ti ON ti.Id_type_indemnite = i.type
                              WHERE destination="' . mysql_real_escape_string($destination).'"
                              AND region="' . mysql_real_escape_string($region).'"
                              AND type_deplacement="' . mysql_real_escape_string($type_deplacement).'"
                              ORDER BY i.type,i.ordre,i.nom');

        $nom_type = '';
        while ($ligne = $result->fetchRow()) {
            if($nom_type != $ligne->nom_type)
                $html .= '<br/><b>' . $ligne->nom_type . '</b><br/>';
            
            if($ligne->type == 'defaut' && !$Id_cd) {
                $checked = 'checked="checked"';
            }
            elseif($ligne->type == 'optionnel') {
                $checked = '';
            }
             
           if($ligne->condition != '')
                $condition = '(' . $ligne->condition . ')';
            else
                $condition = '';
            
            if($ligne->exclusion && $ligne->plafond) {
                $onclick = 'onclick="excludeIndemnity(\'' . $ligne->exclusion . '\'); showIndemnityQuota("plafond' . $ligne->id_indemnite . '");showIndemnityBox();"';
            }
            else if(!$ligne->exclusion && $ligne->plafond) {
                $onclick = 'onclick="showIndemnityQuota(\'plafond' . $ligne->id_indemnite . '\');showIndemnityBox();"';
            }
            else if($ligne->exclusion && !$ligne->plafond) {
                $onclick = 'onclick="excludeIndemnity(\'' . $ligne->exclusion . '\');showIndemnityBox();"';
            }
            else {
                $onclick = 'onclick="showIndemnityBox()"';
            }
                
            $displayPlafond = '';
            if($ligne->plafond) {
                if(!$indemnite[$ligne->id_indemnite])
                    $displayPlafond = 'style="display:none;"';
                $plafond = '<label id="plafond' . $ligne->id_indemnite . '" ' . $displayPlafond . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plafond en km : <input type="text" name="plafond' . $ligne->id_indemnite . '" value="' . $plafondIndemnite[$ligne->id_indemnite] . '" /><br /></label>';
            }
            else {
                $plafond = '';
            }

            $html .= '
                <label id="l' . $ligne->id_indemnite . '">
                    <input type="checkbox" id="i' . $ligne->id_indemnite . '" name="indemnite[]" value="' . $ligne->id_indemnite . '" ' . $indemnite[$ligne->id_indemnite] . ' ' . $checked . ' ' . $onclick . ' />
                   ' . $ligne->nom . ' ' .$condition . ' 
                <br />
                </label>
                ' . $plafond;
            $nom_type = $ligne->nom_type;
        }
        return $html;
    }
    
    /**
     * Affichage des checkbox contenant les indemnités
     *
     * @param int Valeur de l'identifiant du contrat delegation
     * @param int Valeur du type de l'indemnite
     *
     * @return string
     */
    public static function getOldCheckboxList($Id_cd, $type) {
        if ($Id_cd) {
            $cd = new ContratDelegation($Id_cd, array());
            if (!empty($cd->indemnite)) {
                foreach ($cd->indemnite as $i) {
                    $indemnite[$i['id']] = 'checked="checked"';
                }
            }
        }
        $db = connecter();
        $result = $db->query('SELECT * FROM indemnite WHERE type=' . mysql_real_escape_string((int) $type));
        while ($ligne = $result->fetchRow()) {
            $html .= '<input type="checkbox" name="indemnite[]" value="' . $ligne->id_indemnite . '" ' . $indemnite[$ligne->id_indemnite] . ' />
               ' . $ligne->nom . ' <br />';
        }
        return $html;
    }

    /**
     * Explication des remboursements selon le type
     *
     * @param int Valeur du type de l'indemnité
     *
     * @return string	  
     */
    public static function type($i) {
        switch ($i) {
            case 1:
                $html = COMPENSATION_CASE1;
                break;
            case 2:
                $html = COMPENSATION_CASE2;
                break;
            case 3:
                $html = COMPENSATION_CASE3;
                break;
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les type d'indemnités
     *
     * @param int Valeur de l'identifiant du contrat delegation
     *
     * @return string	
     */
    public static function getTypeList($Id_cd) {
        $db = connecter();
        if ($Id_cd) {
            $cd = new ContratDelegation($Id_cd, array());
            $cd_indemnite_destination[$cd->indemnite_destination] = 'selected="selected"';
            $cd_indemnite_region[$cd->indemnite_region] = 'selected="selected"';
            $cd_indemnite_type_deplacement[$cd->indemnite_type_deplacement] = 'selected="selected"';
        }
        $html = '
            <select id="indemnite_destination" name="indemnite_destination" onChange="afficherIndemnites(2);" >
                <option value="">Destination</option>
                <option value="">-----------</option>
                <option value="client" ' . $cd_indemnite_destination['client'] . '>Site client</option>
                <option value="agence" ' . $cd_indemnite_destination['agence'] . '>Agence de rattachement</option>
                <option value="field" ' . $cd_indemnite_destination['field'] . '>Field</option>
                <option value="PWS" ' . $cd_indemnite_destination['PWS'] . '>PWS</option>
            </select>';
        $html .= '<select id="indemnite_region" name="indemnite_region" onChange="afficherIndemnites(2);" >
                <option value="">Région</option>
                <option value="">-----------</option>
                <option value="idf" ' . $cd_indemnite_region['idf'] . '>IDF</option>
                <option value="province" ' . $cd_indemnite_region['province'] . '>Province</option>
                <option value="etranger" ' . $cd_indemnite_region['etranger'] . '>Etranger</option>
                <option value="mission" ' . $cd_indemnite_region['mission'] . '>Mission</option>
                <option value="occasionnel" ' . $cd_indemnite_region['occasionnel'] . '>Occasionnel</option>
            </select>
            <select id="indemnite_type_deplacement" name="indemnite_type_deplacement" onChange="afficherIndemnites(2);" >
                <option value="">Type de déplacement</option>
                <option value="">-----------</option>
                <option value="pas_deplacement" ' . $cd_indemnite_type_deplacement['pas_deplacement'] . '>Frais</option>
                <option value="petit_deplacement" ' . $cd_indemnite_type_deplacement['petit_deplacement'] . '>Frais petit déplacement</option>
                <option value="grand_deplacement" ' . $cd_indemnite_type_deplacement['grand_deplacement'] . '>Frais grand déplacement</option>
            </select>
        ';
            
        
        return $html;
    }
    
    /**
     * Affichage d'une select box contenant les type d'indemnités
     *
     * @param int Valeur de l'identifiant du contrat delegation
     *
     * @return string	
     */
    public static function getOldTypeList($Id_cd) {
        $db = connecter();
        if ($Id_cd) {
            $contratD = new ContratDelegation($Id_cd, array());
            $cd[$contratD->Id_type_indemnite] = 'selected="selected"';
        }
        $result = $db->query('SELECT * FROM type_indemnite WHERE Id_type_indemnite < 10');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_type_indemnite . '" onmouseover="return overlib(\'' . str_replace('"', "'", mysql_escape_string(self::type($ligne->id_type_indemnite))) . '\')" onmouseout="return nd()" ' . $cd[$ligne->id_type_indemnite] . '>' . $ligne->nom . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du type de l'indemnité
     *
     * @param int Valeur du type de l'indemnité
     *
     * @return string	
     */
    public static function getType($Id_type) {
        if ($Id_type == 0) {
            return 'Aucune';
        }
        $db = connecter();
        return $db->query('SELECT nom FROM type_indemnite WHERE Id_type_indemnite=' . mysql_real_escape_string((int) $Id_type))->fetchRow()->nom;
    }

}

?>
