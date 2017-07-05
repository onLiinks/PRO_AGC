<?php

/**
 * Fichier Profil.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Profil
 */
class Metier {

    /**
     * Identifiant du métier
     *
     * @access private
     * @var int 
     */
    private $Id_metier;
    
    /**
     * Identifiant de catégorie
     *
     * @access private
     * @var int 
     */
    private $Id_categorie_metier;

    /**
     * Libellé du métier
     *
     * @access public
     * @var string 
     */
    public $libelle;
    
    /**
     * Adresse du métier sur le site Proservia RH
     *
     * @access public
     * @var string 
     */
    public $url;

    /**
     * Constructeur de la classe Metier
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant du metier
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_metier = '';
                $this->Id_categorie_metier = '';
                $this->libelle = '';
                $this->url = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */ elseif (!$code && !empty($tab)) {
                $this->Id_metier = '';
                $this->Id_categorie_metier = htmlscperso(stripslashes($tab['Id_categorie_metier']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->url = htmlscperso(stripslashes($tab['url']), ENT_QUOTES);
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */ elseif ($code && empty($tab)) {
                $db = connecter();
                $r = $db->query('SELECT Id_categorie_metier, libelle, url FROM metier 
                                WHERE Id_metier=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_metier = $code;
                $this->Id_categorie_metier = $r->id_categorie_metier;
                $this->libelle = $r->libelle;
                $this->url = $r->url;
            }

            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */ elseif ($code && !empty($tab)) {
                $this->Id_metier = $code;
                $this->Id_categorie_metier = htmlscperso(stripslashes($tab['Id_categorie_metier']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->url = htmlscperso(stripslashes($tab['url']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }
    
    /**
     * Formulaire de création / modification d'un métier
     *
     * @return string	   
     */
    public function form() {
        $html = '
        <form enctype="multipart/form-data" action="../rh/index.php?a=enregistrer_metier" method="post">
            <div class="center">
                <span class="infoFormulaire"> * </span> Famille métier :
                <select name="Id_categorie_metier">
                    <option value="">Sélectionner une famille</option>
                    <option value="">----------------------------</option>
                    ' . $this->getFamilyList() . '
                </select>
                <span class="infoFormulaire">' . $this->erreurs['Id_categorie_metier'] . '</span>
                <br /><br />
                <span class="infoFormulaire"> * </span> Métier :
                <input type="text" name="libelle" value="' . $this->libelle . '" /> <span class="infoFormulaire">' . $this->erreurs['libelle'] . '</span>
                <br /><br />
                Lien : <br />
                ' . PROSERVIA_RH_WEBSITE . '<input type="text" id="url" name="url" value="' . $this->url . '" onChange="$(\'link\').href = \''.PROSERVIA_RH_WEBSITE.'\'+$(\'url\').value;" /> (<a id="link" href="' . PROSERVIA_RH_WEBSITE . $this->url . '" target="_blank">Tester le lien</a>)
            </div>
            <div class="submit">
                <input type="hidden" name="Id" value="' . (int) $this->Id_metier . '" />
                <input type="hidden" name="class" value="' . __CLASS__ . '" />
                <input type="submit" value="' . SAVE_BUTTON . '" />
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
        if ($this->libelle == '') {
            $this->erreurs['libelle'] = NAME_ERROR;
        }
        if ($this->Id_categorie_metier == '') {
            $this->erreurs['Id_categorie_metier'] = 'Veuillez choisir une famille métier';
        }
        return count($this->erreurs) == 0;
    }
    
    /**
     * Recherche d'un métier
     *
     * @return string
     */
    public static function search($libelle, $Id_categorie_metier, $output = array('type' => 'TABLE')) {
        $arguments = array('libelle', 'Id_categorie_metier', 'output');
        $columns = array(array('Métier','lmetier'), array('Famille métier','lfamille'));
        $db = connecter();
        $requete = 'SELECT m.Id_metier, m.Id_categorie_metier, m.libelle AS lmetier,
                    cm.libelle AS lfamille FROM metier m
                    INNER JOIN categorie_metier cm ON cm.Id_categorie_metier = m .Id_categorie_metier
                    WHERE Id_metier != 0 ';
        if ($libelle) {
            $requete .= ' AND m.libelle LIKE "' . $libelle . '%"';
        }
        if ($Id_categorie_metier) {
            $requete .= ' AND cm.Id_categorie_metier = "' . $Id_categorie_metier . '"';
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
            $paramsOrder .= 'orderBy=lmetier';
            $orderBy = 'lmetier';
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
                'onclick' => 'afficherAnnonce({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterMetier&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
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
                        $img['lmetier'] = '<img src="' . IMG_ASC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherMetier({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';
                
                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'odd' : 'even';

                    $html .= '
                    <tr class="row' . $j . '">
                        <td>' . $ligne['lmetier'] . '</td>
                        <td>' . $ligne['lfamille'] . '</td>
                        <td><a href="../rh/index.php?a=modifier_metier&amp;Id=' . $ligne['id_metier'] . '"><img src="' . IMG_EDIT . '" /></a></td>
                        <td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer_metier&amp;Id=' . $ligne['id_metier'] . '\') }" /></td>
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
            header('Content-Disposition: attachment; filename="metiers.csv"');
            
            echo 'Identifiant;';
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['id_metier'] . ';';
                echo '"' . $ligne['lmetier'] . '";';
                echo '"' . $ligne['lfamille'] . '";';
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
        $metier = new Metier(null, null);
        $html = '
            Métier <input id="libelle" name="libelle" type="text" onkeyup="afficherMetier()" value="' . $_SESSION['filtre']['libelle'] . '" />&nbsp;
            <select id="Id_categorie_metier" name="Id_categorie_metier" onchange="afficherMetier()">
                <option value="">Sélectionner une famille</option>
                <option value="">----------------------------</option>
                ' . $metier->getFamilyList() . '
            </select>&nbsp;
            <input type="button" onclick="afficherMetier()" value="Go !">
            <input type="reset" value="Refresh" onclick="initForm(\'Metier\')">';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_metier = ' . mysql_real_escape_string((int) $this->Id_metier) . ',
                Id_categorie_metier = ' . mysql_real_escape_string((int) $this->Id_categorie_metier) . ',
                libelle = "' . mysql_real_escape_string($this->libelle) . '",
                url = "' . mysql_real_escape_string($this->url) . '"';
        if ($this->Id_metier) {
            $requete = 'UPDATE metier ' . $set . ' WHERE Id_metier = ' . mysql_real_escape_string((int) $this->Id_metier);
        } else {
            $requete = 'INSERT INTO metier ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Suppression d'un métier
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM metier WHERE Id_metier = ' . mysql_real_escape_string((int) $this->Id_metier));
    }

    /**
     * Affichage d'une select box contenant les métiers
     *
     * @return string	
     */
    public function getList() {
        $metier[$this->Id_metier] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_metier, libelle FROM metier ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_metier . ' ' . $metier[$ligne->id_metier] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    /**
     * Récupération de la liste des métiers au format JSON
     *
     * @return string
     */
    public static function getJsonList() {
        $db = connecter();
        //récupération des agences de Proservia
        $result = $db->query('SELECT Id_metier, libelle FROM metier ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '["' . $ligne->id_metier . '", "' . $ligne->libelle . '"],';
        }
        return '[' . $html . ']';
    }

    /**
     * Affichage du libelle du métier
     *
     * @param int Identifiant du métier 
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter();
        return $db->query('SELECT libelle FROM metier WHERE Id_metier=' . mysql_real_escape_string((int) $i))->fetchRow()->libelle;
    }
    
    /**
     * Affichage d'une select box contenant les familles métiers
     *
     * @return string	
     */
    public function getFamilyList() {
        $metier[$this->Id_categorie_metier] = 'selected="selected"';
        $db = connecter();
        $result = $db->query('SELECT Id_categorie_metier, libelle FROM categorie_metier ORDER BY libelle');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value=' . $ligne->id_categorie_metier . ' ' . $metier[$ligne->id_categorie_metier] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
}

?>
