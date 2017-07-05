<?php

/**
 * Fichier Logistique.php
 *
 * @author    Frédérique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe Logistique
 */
class Logistique {

    /**
     * Identifiant de la session
     *
     * @access public
     * @var int
     */
    public $Id_session;
    /**
     * Liste des éléments de la checklist, en deuxième dimension valeur pour la valeur check 
     * et rmq pour les remarques
     *
     * @access public
     * @var array
     */
    public $chekclist;

    /**
     * Constructeur de la classe Logistique
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de l'instance Logistique
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            $this->checklist = array();
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_session = '';
            }
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_session = $tab['Id_session'];
                //récupération des identifiants de la checklist pour les utiliser comme indice du tableau de la checklist
                $db = connecter();
                $result = $db->query('SELECT Id_checklist FROM checklist');
                //pour chaque élément de la checklist, si une valeur est passé dans le formulaire, affectation de la valeur
                while ($ligne = $result->fetchRow()) {
                    if (isset($tab[$ligne->id_checklist])) {
                        if ($tab[$ligne->id_checklist] == 2) {
                            $this->checklist[$ligne->id_checklist]['valeur'] = 2;
                        } else {
                            $this->checklist[$ligne->id_checklist]['valeur'] = 1;
                        }
                    }
                    $this->checklist[$ligne->id_checklist]['rmq'] = $tab['rmq' . $ligne->id_checklist];
                }
            }
            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $result = $db->query('SELECT Id_checklist, valeur, rmq FROM logistique WHERE Id_session=' . mysql_real_escape_string((int) $code));
                $this->Id_session = $code;
                while ($ligne = $result->fetchRow()) {
                    $this->checklist[$ligne->id_checklist]['valeur'] = $ligne->valeur;
                    $this->checklist[$ligne->id_checklist]['rmq'] = $ligne->rmq;
                }
            }
            /* Cas 4 : un code et un tableau : prendre des infos dans la base de 
              données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_session = $code;
                //récupération des identifiants de la checklist pour les utiliser comme indice du tableau de la checklist
                $db = connecter();
                $result = $db->query('SELECT Id_checklist FROM checklist');

                //pour chaque élément de la checklist, si une valeur est passé dans le formulaire, affectation de la valeur
                while ($ligne = $result->fetchRow()) {
                    if (isset($tab[$ligne->id_checklist])) {
                        if ($tab[$ligne->id_checklist] == 2) {
                            $this->checklist[$ligne->id_checklist]['valeur'] = 2;
                        } else {
                            $this->checklist[$ligne->id_checklist]['valeur'] = 1;
                        }
                    }
                    $this->checklist[$ligne->id_checklist]['rmq'] = $tab['rmq' . $ligne->id_checklist];
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une instance Logistique
     *
     * @return string
     */
    public function form() {
        //Récupération des libellés de la checklist dans la base de donnée	
        $db = connecter();
        $result = $db->query('SELECT Id_checklist, libelle FROM checklist');
        //affichage de la checklist
        $i = 0;
        while ($ligne = $result->fetchRow()) {
            $j = ($i % 2 == 0) ? 'odd' : 'even';
            if ($this->checklist[$ligne->id_checklist]['valeur'] == 2) {
                //élément barré et donc non modifiable
                $html .= '<div id="logistique' . $ligne->id_checklist . '"><table >
							<tr class="row' . $j . '">
								<td width="20" height="30"><span class="marge"></span>
									<input type="checkbox" onclick="this.checked=false">
									<input type="hidden" name="' . $ligne->id_checklist . '" value="2"></td>
								<td width="300" height="30">&nbsp;&nbsp;<s>' . $ligne->libelle . '</s></td>
								<td width="70" height="30">
									<input type="text" id="rmq' . $ligne->id_checklist . '" name="rmq' . $ligne->id_checklist . '" 
									 size="60" value="' . $this->checklist[$ligne->id_checklist]['rmq'] . '" ></td>
								<td width="70" height="30">
									<input type="button" onClick="barrer(' . $ligne->id_checklist . ', 0, ' . "'" . $j . "'" . ')" value="+"></td>
							</tr>
						</table></div>';
            } else {
                //élément modifiable
                if ($this->checklist[$ligne->id_checklist]['valeur'] == 1) {
                    $select = 'checked';
                } elseif ($this->checklist[$ligne->id_checklist]['valeur'] == 0) {
                    $select = '';
                }
                $this->checklist[$ligne->id_checklist]['rmq'] = str_replace('"', "'", $this->checklist[$ligne->id_checklist]['rmq']);
                $html .= '<div id="logistique' . $ligne->id_checklist . '"><table >
							<tr class="row' . $j . '"><td width="20" height="30">
								<span class="marge"></span><input type="checkbox" name="' . $ligne->id_checklist . '" ' . $select . ' />
							</td><td width="300" height="30">
								&nbsp;&nbsp;' . $ligne->libelle . '
							</td><td width="70" height="30">
								<input type="text" id="rmq' . $ligne->id_checklist . '" name="rmq' . $ligne->id_checklist . '" 
								 size="60" value="' . $this->checklist[$ligne->id_checklist]['rmq'] . '">
							</td>
							<td width="70" height="30">
								<input type="button" onClick="barrer(' . $ligne->id_checklist . ', 2, ' . "'" . $j . "'" . ')" value="-"></td>
							</tr>
						</table></div>';
            }
            ++$i;
        }
        $html .= '<br/><br/>';
        return $html;
    }

    /**
     * Enregistrement des données de la checklist dans la base de données 
     *
     */
    public function save() {
        //suppresion de tous les enregistrements précédents concernant cette session 
        $db = connecter();
        $db->query('DELETE FROM logistique WHERE Id_session = ' . mysql_real_escape_string((int) $this->Id_session));
        //récupération des identifiants de la checklist 
        $result1 = $db->query('SELECT Id_checklist FROM checklist');

        //création de tous les nouveaux enregistrements pour cette session
        while ($ligne1 = $result1->fetchRow()) {
            if ($this->checklist[$ligne1->id_checklist]['valeur'] || ($this->checklist[$ligne1->id_checklist]['rmq'] != '')) {
                $db->query('INSERT INTO logistique SET Id_session=' . mysql_real_escape_string((int) $this->Id_session) . ', 
								Id_checklist="' . mysql_real_escape_string($ligne1->id_checklist) . '",
								valeur="' . mysql_real_escape_string($this->checklist[$ligne1->id_checklist]['valeur']) . '", 
								rmq="' . mysql_real_escape_string($this->checklist[$ligne1->id_checklist]['rmq']) . '"');
            }
        }
    }

    /**
     * Affichage des données de la checklist en consultation
     *
     * @return string
     */
    public function consultation() {
        //Récupération des identifiants et des libéllés de la checklist
        $db = connecter();
        $result = $db->query('SELECT Id_checklist, libelle FROM checklist');
        $html .= '<table>
					<tr><td width="10" height="20"> </td><td width="230" height="20"> </td>
						<td  width="10" height="20" align="center"><b>Fait</b></td>
						<td width="180" height="20" align="center"><b>Remarques</b></td>
						<td width="10" height="20"> </td>
					</tr>
					<tr>
					</tr>';
        $i = 0;
        //Affichage de la checklist
        while ($ligne = $result->fetchRow()) {
            $j = ($i % 2 == 0) ? 'odd' : 'even';
            //Si l'élément vaut 2, affichage de l'élément barré
            if ($this->checklist[$ligne->id_checklist]['valeur'] == 2) {
                //si élément barré
                $html .= '<tr class="row' . $j . '">
								<td width="10" height="20"> </td>
								<td align="left" width="230" height="20"> -&nbsp;&nbsp;<s>' . $ligne->libelle . '</s></td>
								<td align="center" width="10" height="20"><hr/></td>
								<td align="center" width="180" height="20">' . $this->checklist[$ligne->id_checklist]['rmq'] . '</td>
								<td width="10" height="20"> </td>
							</tr>';
            } else { //sinon affichage de l'émément normalement
                //Si la valeur de l'élément vaut 1, élément fait
                $fait = ($this->checklist[$ligne->id_checklist]['valeur'] == 1) ? 'x' : '';
                $html .= '<tr class="row' . $j . '">
								<td width="10" height="20"> </td>
								<td align="left" width="230" height="20"> -&nbsp;&nbsp;' . $ligne->libelle . '</td>
								<td align="center" width="10" height="20">' . $fait . '</td>
								<td align="center" width="180" height="20">' . $this->checklist[$ligne->id_checklist]['rmq'] . '</td>
								<td width="10" height="20"> </td>
							</tr>';
            }
            ++$i;
        }
        $html .= '</table><br/><br/><br/>';
        return $html;
    }

    /**
     * Fonction pour barrer ou "dé-barrer" une ligne
     *
     * @param int l'identifiant de l'élément de la checklist
     * @param int la valeur de l'élément
     * @param string le type de ligne
     * @param string les remarques associées à la ligne
     *
     * @return string
     */
    public static function crossOut($Id_checklist, $valeur, $row, $rmq) {
        //Récupération du libéllé de l'élément
        $db = connecter();
        $ligne = $db->query('SELECT libelle FROM checklist WHERE Id_checklist=' . mysql_real_escape_string((int) $Id_checklist))->fetchRow();
        //Si la valeur vaut 2, affichage de l'élément barré
        if ($valeur == 2) {
            $html = '<div id="logistique' . $Id_checklist . '"><table >
							<tr class="row' . $row . '">
								<td width="20" height="30"><span class="marge"></span>
									<input type="checkbox" onclick="this.checked=false">
									<input type="hidden" name="' . $Id_checklist . '" value="2"></td>
								<td width="300" height="30">&nbsp;&nbsp;<s>' . $ligne->libelle . '</s></td>
								<td width="70" height="30">
									<input type="text" id="rmq' . $Id_checklist . '" name="rmq' . $Id_checklist . '" 
									 size="60" value="' . $rmq . '" ></td>
								<td width="70" height="30">
									<input type="button" onClick="barrer(' . $Id_checklist . ', 0, ' . "'" . $row . "'" . ')" value="+"></td>
							</tr>
						</table></div>';
        } else { //Si la valeur vaut 1, affichage de l'élément "dé-barré"
            $html = '<div id="logistique' . $Id_checklist . '"><table >
							<tr class="row' . $row . '"><td width="20" height="30">
								<span class="marge"></span><input type="checkbox" name="' . $Id_checklist . '"/>
							</td><td width="300" height="30">
								&nbsp;&nbsp;' . $ligne->libelle . '
							</td><td width="70" height="30">
								<input type="text" id="rmq' . $Id_checklist . '" name="rmq' . $Id_checklist . '" size="60" value="' . $rmq . '">
							</td>
							<td width="70" height="30">
								<input type="button" onClick="barrer(' . $Id_checklist . ', 2, ' . "'" . $row . "'" . ')" value="-"></td>
							</tr>
						</table></div>';
        }
        return $html;
    }

    /**
     * Edition de la checklist
     *
     * @return string
     */
    public function edition() {
        //Récupération des identifiants et des libéllés de la checklist
        $db = connecter();
        $result = $db->query('SELECT Id_checklist, libelle FROM checklist');
        $html .= '<table border=2 bordercolor=black ><tr>
						<td width="230" height="20" align="center">Tâches à réaliser </td>' .
                '<td  width="10" height="20" align="center"><b>Fait</b></td>' .
                '<td width="180" height="20" align="center"><b>Remarques</b></td>' .
                '</tr>';

        $i = 0;
        //Affichage de la checklist
        while ($ligne = $result->fetchRow()) {
            $j = ($i % 2 == 0) ? 'odd' : 'even';
            //Si l'élément vaut 2, affichage de l'élément barré
            if ($this->checklist[$ligne->id_checklist]['valeur'] == 2) {
                //si élément barré
                $html .= '<tr class="row' . $j . '">
								<td align="left" width="400" height="20"> -&nbsp;&nbsp;<s>' . $ligne->libelle . '</s></td>
								<td align="center" width="10" height="20"><hr/></td>
								<td align="center" width="180" height="20">' . $this->checklist[$ligne->id_checklist]['rmq'] . '</td>
							</tr> ';
            } else { //sinon affichage de l'émément normalement
                //Si la valeur de l'élément vaut 1, élément fait
                $fait = ($this->checklist[$ligne->id_checklist]['valeur'] == 1) ? 'x' : '';
                $html .= '	<tr class="row' . $j . '">
								<td align="left" width="400" height="20"> -&nbsp;&nbsp;' . $ligne->libelle . '</td>
								<td align="center" width="10" height="20">' . $fait . '</td>
								<td align="center" width="180" height="20">' . $this->checklist[$ligne->id_checklist]['rmq'] . '</td>
							</tr>';
            }
            ++$i;
        }
        $html .= '</table><br/><br/><br/>';
        return $html;
    }

}

?>