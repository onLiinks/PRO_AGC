<?php

/**
 * Fichier Agence.php
 *
 * @author    Anthony Anne
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe LieuxPrestation
 */
class LieuxPrestation {

    /**
     * Identifiant du lieu de prestation
     *
     * @access private
     * @var int
     */
    private $Id;
    /**
     * Code
     *
     * @access private
     * @var string
     */
    private $code;
    /**
     * Libelle
     *
     * @access private
     * @var string
     */
    private $libelle;
    /**
     * id_commune
     *
     * @access public
     * @var int
     */
    public $id_communes;
    /**
     * id_type_lieux_prestation
     *
     * @access public
     * @var int
     */
    public $id_type_lieux_prestation;
    /**
     * adresse_1
     *
     * @access public
     * @var string
     */
    public $adresse_1;
    /**
     * adresse_2
     *
     * @access private
     * @var string
     */
    private $adresse_2;

    /**
     * Constructeur de la classe LieuxPrestation
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'agence
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($Id = 0, $tab = array(0)) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de Id et pas de tableau : les champs sont vides */
            if ($Id == 0 && empty($tab)) {
                $this->Id = $Id;
                $this->code = '';
                $this->libelle = '';
                $this->id_communes = '';
                $this->id_type_lieux_prestation = 0;
                $this->adresse_1 = '';
                $this->adresse_2 = '';
                $this->Id_compte = NULL;
            }

            /* Cas 2 : pas de Id et un tableau : retour de formulaire en mode création  */
            elseif ($Id == 0 && !empty($tab)) {
                $this->Id = $Id;
                $this->code = htmlscperso(stripslashes($tab['code']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->id_communes = htmlscperso(stripslashes($tab['id_communes']), ENT_QUOTES);
                $this->id_type_lieux_prestation = htmlscperso(stripslashes($tab['id_type_lieux_prestation']), ENT_QUOTES);
                $this->adresse_1 = htmlscperso(stripslashes($tab['adresse_1']), ENT_QUOTES);
                $this->adresse_2 = htmlscperso(stripslashes($tab['adresse_2']), ENT_QUOTES);
                $this->Id_compte = htmlscperso(stripslashes($tab['id_compte_salesforce']), ENT_QUOTES);
            }

            /* Cas 3 : un Id et pas de tableau : prendre les infos dans la base de données */
            elseif ($Id != 0 && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM lieux_prestation WHERE Id="' . mysql_real_escape_string($Id) . '"')->fetchRow();
                $this->Id = $Id;
                $this->code = $ligne->code;
                $this->libelle = $ligne->libelle;
                $this->id_communes = $ligne->id_communes;
                $this->id_type_lieux_prestation = $ligne->id_type_lieux_prestation;
                $this->adresse_1 = $ligne->adresse_1;
                $this->adresse_2 = $ligne->adresse_2;
                $this->Id_compte = $ligne->id_compte_salesforce;
            }
            /* Cas 4 : un Id et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($Id != 0 && !empty($tab)) {
                $this->Id = $Id;
                $this->code = htmlscperso(stripslashes($tab['code']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->id_communes = htmlscperso(stripslashes($tab['id_communes']), ENT_QUOTES);
                $this->id_type_lieux_prestation = htmlscperso(stripslashes($tab['id_type_lieux_prestation']), ENT_QUOTES);
                $this->adresse_1 = htmlscperso(stripslashes($tab['adresse_1']), ENT_QUOTES);
                $this->adresse_2 = htmlscperso(stripslashes($tab['adresse_2']), ENT_QUOTES);
                $this->Id_compte = htmlscperso(stripslashes($tab['id_compte_salesforce']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'un Lieu de prestation
     *
     * @return string
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="../membre/index.php?a=enregistrerLieuxPrestation" method="post">
            <div class="center">
                <span style="display:none;">Source : </span><select style="display:none;" id="opType" name="opType" onChange="displayLieuxPrestation();prefixCompteCD();">
                        <option value="">Type d\'affaire</option>
                        <option value="">----------------------------</option>
                        './/<option value="agc" ' . $type['agc'] . '>AGC</option>
                        '<option value="sfc" ' . $type['sfc'] . '>Salesforce</option>
                    </select>
                    <br /><br />
                <span class="infoFormulaire"> * </span>Compte Salesforce : <input id="prefix" type="text" size="4" onkeyup="prefixCompteCD(1)">
                <img src="' . IMG_HELP_OVER . '" alt="" class="helpOrb" onmouseover="return overlib(\'<div class=commentaire>Vous pouvez entrer un nom de compte Salesforce</div>\', FULLHTML);" onmouseout="return nd();" />
                <span id="compte">
                    <select id="Id_compte" name="Id_compte" onchange="showCaseList(this.value, 1);">
                        <option value="">' . CUSTOMERS_SELECT . '</option>
                        <option value="">-------------------------</option>
                    </select>
                </span>
                <span class="infoFormulaire">' . $this->erreurs['doublon'] . '</span>
                <br />
                <br />
                <span class="infoFormulaire"> * </span> Lieu de prestation : <input type="text" name="libelle" value="' . $this->libelle . '" /> <span class="infoFormulaire">' . $this->erreurs['libelle'] . '</span>
                <br /><br />
                <span class="infoFormulaire"> * </span> Code : <input type="text" name="code" value="' . $this->code . '" /> <span class="infoFormulaire">' . $this->erreurs['code'] . '</span>
                <br /><br />
                <span class="infoFormulaire"> * </span> Type Lieux Prestation :
                <select name="id_type_lieux_prestation">
                    <option value="">Sélectionner un type</option>
                    <option value="">----------------------------</option>
                    ' . $this->getTypeLieuxPrestationList() . '
                </select>
                <span class="infoFormulaire">' . $this->erreurs['id_type_lieux_prestation'] . '</span>
                <br /><br />
                Adresse : <input type="text" name="adresse_1" value="' . $this->adresse_1 . '" size = "40" />
                <br /><br />
                Adresse 2 : <input type="text" name="adresse_2" value="' . $this->adresse_2 . '" size = "40" />
                <br /><br />
                <span class="infoFormulaire"> * </span> Commune : <select name="id_communes" onchange="afficherDetailsCommune(this.value)">
                    <option value="">Sélectionner un commune</option>
                    <option value="">----------------------------</option>
                    ' . $this->getCommunesList() . '
                </select>
                <span class="infoFormulaire">' . $this->erreurs['id_communes'] . '</span>
                <br /><br />
                <div id="detailsCommune">' . $this->getCommunesDetails($this->id_communes) . '</div>
            </div>
            <br /><br />
            <div class="submit">
                <input type="hidden" name="Id" value="' . (int) $this->Id . '" />
                <input type="hidden" name="class" value="' . __CLASS__ . '" />
                <input type="submit" value="' . SAVE_BUTTON . '" /> &nbsp&nbsp&nbsp&nbsp <input type="button" onclick="window.open(\'../membre/index.php?a=consulterLieuxPrestation\', \'_self\')" value="Annuler" />
            </div>
            </div>
        </form>';
    return $html;
    }

    /**
     * Vérification du formulaire
     *
     * Le champ nom est obligatoire
     *
     * @return bool
     */
    public function check() {
        $retourErreur = '';
        if ($this->libelle == '') {
            $this->erreurs['libelle'] = NAME_ERROR;
            $retourErreur .= $this->erreurs['libelle'].'<br />';
        }
        if ($this->id_type_lieux_prestation == '') {
            $this->erreurs['id_type_lieux_prestation'] = 'Veuillez choisir un type de lieu';
            $retourErreur .= $this->erreurs['id_type_lieux_prestation'].'<br />';
        }
        if ($this->code == '') {
            $this->erreurs['code'] = 'Veuillez choisir un code';
            $retourErreur .= $this->erreurs['code'].'<br />';
        }
        if ($this->id_communes == '') {
            $this->erreurs['id_communes'] = 'Veuillez choisir une commune';
            $retourErreur .= $this->erreurs['id_communes'].'<br />';
        }
            $db = connecter();
            $verifDoublon = $db->query('SELECT lp.id AS id, ic.code_insee AS code_insee, lp.libelle AS libelle, count(*) AS count FROM lieux_prestation lp INNER JOIN insee_communes ic ON ic.id = lp.id_communes WHERE ic.code_insee in (select code_insee from insee_communes where id="' . mysql_real_escape_string($this->id_communes) . '") AND lp.id_type_lieux_prestation="' . mysql_real_escape_string($this->id_type_lieux_prestation) . '" LIMIT 1');
            
            
            while ($doublon = $verifDoublon->fetchRow()) {
              $codeInsee = $doublon->code_insee;
              $libelle = $doublon->libelle;
              $idLP = $doublon->id; 

              switch ($doublon->count) {
                case 0 :
                    break;
                default :
                    if ($this->Id == 0) {
                      $this->erreurs['doublon'] = 'Cette association "Type de lieu" ('.$this->id_type_lieux_prestation.') / "code INSEE de la commune" ('.$codeInsee.') existe déjà avec le libellé "'.$libelle.'".';
                      $this->erreurs['doublon'] .= '<br />';
                      $this->erreurs['doublon'] .= 'Pour modifier le lieu de prestation déjà existant, <a href="index.php?a=modifierLieuxPrestation&Id='.$idLP.'" target="_ ">cliquez ici</a>.';
                      $retourErreur .= $this->erreurs['doublon'].'<br />';
                  }
                }

        }
        return $retourErreur;
    }

    /**
     * Affichage d'une select box contenant les Lieux de prestation
     *
     * @return string
     */
    public function getList() {
        $lieux_prestation[$this->Id] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT lp.id as id,lp.Code,lp.libelle,ic.nom_commune, ic.code_postal FROM lieux_prestation lp
                                LEFT JOIN insee_communes ic ON ic.Id = lp.id_communes
                                ORDER BY lp.libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id . '">' . $ligne->code . ' - ' . $ligne->libelle . ' (' . $ligne->code_postal . ' ' . $ligne->nom_commune . ')</option>';
        }
        return $html;
    }


    /**
     * Affichage du libelle du lieu de prestation
     *
     * @param int Identifiant du lieu de prestation
     *
     * @return string
     */
    public static function getLibelle($id) {
        $db = connecter();
        return $db->query('SELECT libelle FROM lieux_prestation WHERE Id = "' . mysql_real_escape_string($Id) . '"')->fetchRow()->libelle;
    }


    /**
     * Affichage du Code du lieu de prestation
     *
     * @param int Identifiant du lieu de prestation
     *
     * @return string
     */
    public static function getCode($id) {
        $db = connecter();
        return $db->query('SELECT code FROM lieux_prestation WHERE Id = "' . mysql_real_escape_string($Id) . '"')->fetchRow()->code;
    }


    /**
     * Récupération de la liste des agences au format JSON
     *
     * @return string
     */
    public static function getJsonList() {
        $db = connecter();
        //récupération des agences de Proservia
        $result = $db->query('SELECT DISTINCT Id,libelle,code FROM lieux_prestation ORDER BY Id');
        while ($ligne = $result->fetchRow()) {
            $html .= '["' . $ligne->id . '", "' . $ligne->code . '", "' . $ligne->libelle . '"],';
        }
        return '[' . $html . ']';
    }

    /**
     * Affichage d'une select box contenant les communes
     *
     * @return string
     */
    public function getTypeLieuxPrestationList() {
        $TypeLieuxPrestation[$this->id_type_lieux_prestation] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT * FROM type_lieux_prestation ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id . '"" ' . $TypeLieuxPrestation[$ligne->id] . '>' . $ligne->libelle . ' (Code : ' . $ligne->code . ')</option>';
        }
        return $html;
    }

    /**
     * Affichage d'une select box contenant les communes
     *
     * @return string
     */
    public function getCommunesList($libelle = '') {
        $commune[$this->id_communes] = 'selected="selected"';
        $db = connecter();
        $requete = 'SELECT icom.Id as id_communes, icom.code_insee, icom.nom_commune,icom.code_postal
                                FROM insee_communes icom
                                LEFT JOIN insee_departements idep ON icom.id_departements = idep.Id
                                LEFT JOIN insee_regions ireg ON idep.id_regions = ireg.Id
                                WHERE icom.Id <> 0';
        if ($libelle != ''){
            $requete .= ' AND (icom.code_insee LIKE "%' . $libelle . '%" OR icom.nom_commune LIKE "%' . $libelle . '%" OR icom.code_postal LIKE "%' . $libelle . '%" OR idep.nom LIKE "%' . $libelle . '%")';
                }
        $requete .= ' ORDER BY icom.nom_commune';

        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_communes . '" ' . $commune[$ligne->id_communes] . '">' . $ligne->nom_commune . ' - Code Postal : ' .
            $ligne->code_postal . ' - INSEE : '. $ligne->code_insee.') </option>';
        }
        return $html;
    }

    /**
     * Affichage des  information détaillées de la communes
     *
     * @return string
     */
    public function getCommunesDetails($id_communes = 0) {
        $db = connecter();
        $requete = 'SELECT icom.Id as id_communes, icom.code_insee,icom.code_commune,icom.nom_commune,icom.code_postal,idep.code,idep.nom as nom_departement,ireg.nom as nom_region,
                                icom.code_meta4, icom.coordonnees_gps, icom.superficie, ipay.alpha2, ipay.nom as nom_pays
                                FROM insee_communes icom
                                LEFT JOIN insee_departements idep ON icom.id_departements = idep.Id
                                LEFT JOIN insee_regions ireg ON idep.id_regions = ireg.Id
                                LEFT JOIN insee_pays ipay ON ireg.id_pays = ipay.Id
                                WHERE icom.Id = '. $id_communes;
        $result = $db->query($requete);
        if($id_communes != 0){
            while ($ligne = $result->fetchRow()) {
                $html .= 'Commune : '.$ligne->nom_commune . '<br />' .
                         'Code INSEE : '. $ligne->code_insee . '<br />' .
                         'Code Postal : '. $ligne->code_postal . '<br />' .
                         'Département : '. $ligne->nom_departement . '<br />' .
                         'Région : '. $ligne->nom_region . '<br />' .
                         'Pays : '. $ligne->nom_pays . ' ('. $ligne->alpha2 . ')<br />' .
                         'Code PeopleNet : '. $ligne->code_meta4 . '<br />' .
                         'Coordonnées GPS : '. $ligne->coordonnees_gps . '<br />' .
                         'Superficie : '. $ligne->superficie;
            }
        } else $html .= 'Veuillez sélectionner une commune pour en afficher les détails';
        return $html;
    }

    /**
     * Affichage les  information détaillées du lieu de prestation
     *
     * @return string
     */
    public function getLieuPrestationDetails($lieuxPresta = 0) {
        $db = connecter();
        $requete = 'SELECT icom.Id as id_communes, lp.code as code_lp, lp.libelle, lp.adresse_1, lp.adresse_2, typlp.libelle as type, icom.code_insee,icom.code_commune,icom.nom_commune,icom.code_postal,idep.code,idep.nom as nom_departement,ireg.nom as nom_region,
                                icom.code_meta4, icom.coordonnees_gps, icom.superficie, ipay.alpha2, ipay.nom as nom_pays
                                FROM lieux_prestation lp
                                INNER JOIN insee_communes icom ON icom.id = lp.id_communes
                                INNER JOIN type_lieux_prestation typlp ON typlp.id = lp.id_type_lieux_prestation
                                LEFT JOIN insee_departements idep ON icom.id_departements = idep.Id
                                LEFT JOIN insee_regions ireg ON idep.id_regions = ireg.Id
                                LEFT JOIN insee_pays ipay ON ireg.id_pays = ipay.Id
                                WHERE lp.id = '. $lieuxPresta;
        $result = $db->query($requete);
        if($lieuxPresta != 0){
            while ($ligne = $result->fetchRow()) {
                $htmlLieuPrestation = 'Code du lieu de prestation : '.$ligne->code_lp . '<br />' .
                         'Lieu de prestation : '.$ligne->libelle . '<br />' .
                         'Type de lieu de prestation : '.$ligne->type . '<br />' .
                         'Adresse 1 : '.$ligne->adresse_1 . '<br />' .
                         'Adresse 2 : '.$ligne->adresse_2 . '<br />' .
                         'Code postal : '.$ligne->code_postal . '<br />' .
                         'Commune : '.$ligne->nom_commune . '<br />' .
                         'Code INSEE : '. $ligne->code_insee . '<br />' .
                         'Département : '. $ligne->nom_departement . '<br />' .
                         'Région : '. $ligne->nom_region . '<br />' .
                         'Pays : '. $ligne->nom_pays . ' ('. $ligne->alpha2 . ')<br />' .
                         'Code PeopleNet : '. $ligne->code_meta4 . '<br />' .
                         'Coordonnées GPS : '. $ligne->coordonnees_gps . '<br />' .
                         'Superficie : '. $ligne->superficie;
            }
        } else $htmlLieuPrestation = 'Veuillez sélectionner un lieu de prestation pour plus de détails.';
        return $htmlLieuPrestation;
    }


    /**
     * Recherche d'un lieu de prestation
     *
     * @return string
     */
    public static function search($libelle, $id_type_lieux_prestation, $output = array('type' => 'TABLE')) {
        $arguments = array('libelle', 'id_type_lieux_prestation', 'output');
        $columns = array(array('Lieu de prestation','lieux_prestation_libelle'), array('Type de lieux','ltype'), array('Commune','nom_commune'), array('Compte Salesforce','id_compte_salesforce'));
        $db = connecter();
        $requete = 'SELECT lp.Id as id_lieux_prestation, lp.code as lieux_prestation_code, lp.libelle as lieux_prestation_libelle, icom.Id as id_communes, icom.code_insee,icom.code_commune,icom.nom_commune,icom.code_postal,idep.nom as nom_departement,ireg.nom as nom_region, tlp.libelle as ltype, lp.id_compte_salesforce as id_compte_salesforce
                    FROM lieux_prestation lp
                    LEFT JOIN type_lieux_prestation tlp ON tlp.Id = lp.id_type_lieux_prestation
                    LEFT JOIN insee_communes icom ON icom.Id = lp.id_communes
                    LEFT JOIN insee_departements idep ON icom.id_departements = idep.Id
                    LEFT JOIN insee_regions ireg ON idep.id_regions = ireg.Id
                    WHERE lp.Id != 0 ';
        if ($libelle != '') {
            $requete .= ' AND (lp.code LIKE "%' . $libelle . '%" OR lp.libelle LIKE "%' .$libelle . '%"
                          OR icom.code_insee LIKE "%' . $libelle . '%" OR icom.nom_commune LIKE "%' . $libelle . '%" OR icom.code_postal LIKE "%' . $libelle . '%" OR idep.nom LIKE "%' . $libelle . '%") ';
        }
        if ($id_type_lieux_prestation != 0) {
            $requete .= ' AND lp.id_type_lieux_prestation = "' . $id_type_lieux_prestation . '"';
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
            $paramsOrder .= 'orderBy=lieux_prestation_libelle';
            $orderBy = 'lieux_prestation_libelle';
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
                'onclick' => 'afficherLieuPrestation({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);

            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p align="center"><a href="../membre/index.php?a=modifierLieuxPrestation&amp;Id=0">Ajouter un lieu de prestation</a></p>
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterLieuxPrestation&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $img['lieux_prestation_libelle'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherLieuPrestation({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'odd' : 'even';

                    $html .= '
                    <tr class="row' . $j . '">
                        <td>' . $ligne['lieux_prestation_code'] . ' - ' . $ligne['lieux_prestation_libelle'] . '</td>
                        <td>' . $ligne['ltype'] . '</td>
                        <td>' . $ligne['code_postal'] . ' ' . $ligne['nom_commune'] . ' - CODE INSEE : ' . $ligne['code_insee'] . '</td>
                        <td>' . $ligne['id_compte_salesforce'] . '</td>
                        <td><a href="../membre/index.php?a=modifierLieuxPrestation&amp;Id=' . $ligne['id_lieux_prestation'] . '"><img src="' . IMG_EDIT . '" /></a></td>
                    </tr>';
                    ++$i;
                }
                $html .= '</table><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        }
        elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Lieux_prestation.csv"');

            echo 'Identifiant;';
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['id_lieux_prestation'] . ';';
                echo '"' . $ligne['lieux_prestation_libelle'] . '";';
                echo '"' . $ligne['ltype'] . '";';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche
     *
     * @return string
     */
    public static function searchForm() {
        $LieuxPrestation = new LieuxPrestation(null, null);
        $html = '
            Rechercher Lieu de prestation <input id="libelle" name="libelle" type="text" onkeyup="afficherLieuPrestation()" value="' . $_SESSION['filtre']['libelle'] . '" />&nbsp;
            <select id="id_type_lieux_prestation" name="id_type_lieux_prestation" onchange="afficherLieuPrestation()">
                <option value="">Sélectionner une type de lieu</option>
                <option value="">----------------------------</option>
                ' . $LieuxPrestation->getTypeLieuxPrestationList() . '
            </select>';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET code = "' . mysql_real_escape_string($this->code) . '",
                libelle = "' . mysql_real_escape_string($this->libelle) . '",
                id_communes  = "' . mysql_real_escape_string((int) $this->id_communes) . '",
                id_type_lieux_prestation = "' . mysql_real_escape_string((int) $this->id_type_lieux_prestation) . '",
                adresse_1 = "' . mysql_real_escape_string($this->adresse_1) . '",
                adresse_2 = "' . mysql_real_escape_string($this->adresse_2) . '",
                id_compte_salesforce = "' . mysql_real_escape_string($this->Id_compte) . '"';
        if ($this->Id != 0) {
            $requete = 'UPDATE lieux_prestation ' . $set . ' WHERE Id = ' . mysql_real_escape_string((int) $this->Id);
        } else {
            $requete = 'INSERT INTO lieux_prestation ' . $set;
        }
        $db->query($requete);
    }
}
