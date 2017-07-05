<?php

/**
 * Fichier GestionDroit.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe GestionDroit
 */
class GestionDroit {

    /**
     * Affichage du formulaire de gestion des droits par groupe AD par zone d'accès
     *
     * @return string
     */
    public function groupAdAccesZoneForm() {
        $db = connecter();
        $result = $db->query('SELECT * FROM zone_acces');
        while ($ligne = $result->fetchRow()) {
            $tabidzoneacces[] = $ligne->id_zone_acces;
            $tablibellezoneacces[] = $ligne->libelle;
        }
        $result2 = $db->query('SELECT * FROM groupe_ad');
        while ($ligne2 = $result2->fetchRow()) {
            $tabidgroupead[] = $ligne2->id_groupe_ad;
            $tablibellegroupead[] = $ligne2->trig;
        }

        $i = 0;
        $nbgroupead = count($tabidgroupead);
        while ($i < $nbgroupead) {
            $htmlgroup .= '<th>' . $tablibellegroupead[$i] . '</th>';
            ++$i;
        }

        $j = 0;
        $nbzoneacces = count($tabidzoneacces);
        while ($j < $nbzoneacces) {
            $l = $j % 2;
            $l = ($l == 0) ? 'odd' : 'even';
            $k = 0;
            $htmlzone .= '<tr class="row' . $l . '"><td>' . $tablibellezoneacces[$j] . '</td>';
            while ($k < $nbgroupead) {
                $ligne3 = $db->query('SELECT * FROM groupe_ad_zone_acces WHERE Id_groupe_ad="' . mysql_real_escape_string($tabidgroupead[$k]) . '" AND Id_zone_acces="' . mysql_real_escape_string($tabidzoneacces[$j]) . '"')->fetchRow();
                $check[$k][$j] = '';
                if ($ligne3->id_groupe_ad != 0) {
                    $check[$k][$j] = 'checked="checked"';
                }
                $htmlzone .= '
			    <td><input type="checkbox" ' . $check[$k][$j] . ' onclick="appliquerDroitGroupeAdZoneAcces(' . $tabidgroupead[$k] . ',' . $tabidzoneacces[$j] . ')" /></td>';
                ++$k;
            }
            $htmlzone .= '</tr>';
            ++$j;
        }
        $html = '
			<table class="hovercolored">
				<tr>
					<th></th>
					' . $htmlgroup . '
				</tr>
					' . $htmlzone . '
			</table>
';
        return $html;
    }

    /**
     * Mettre à jour le droit pour le groupe ad donné pour la zone d'accès donnée
     *
     * @param int Identifiant du groupe AD
     * @param int Identifiant de la zone d'accès
     * 	  
     */
    public static function applyGroupAdZoneAccesRight($Id_groupe_ad, $Id_zone_acces) {
        $db = connecter();
        $ligne = $db->query('SELECT * FROM groupe_ad_zone_acces WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ' AND Id_zone_acces=' . mysql_real_escape_string((int) $Id_zone_acces))->fetchRow();
        if ($ligne->id_groupe_ad == '') {
            $requete2 = 'INSERT INTO groupe_ad_zone_acces SET Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ', Id_zone_acces=' . mysql_real_escape_string((int) $Id_zone_acces);
        } else {
            $requete2 = 'DELETE FROM groupe_ad_zone_acces WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ' AND Id_zone_acces=' . mysql_real_escape_string((int) $Id_zone_acces);
        }
        $db->query($requete2);
    }

    /**
     * Affichage du formulaire de gestion des droits par zone d'accès par menu
     *
     * @return string
     */
    public function groupAdMenuForm() {
        $db = connecter();
        $result = $db->query('SELECT * FROM menu WHERE position = "g" ORDER BY ordre');
        while ($ligne = $result->fetchRow()) {
            $tabidmenug[] = $ligne->id_menu;
            $tablibellemenug[] = $ligne->libelle;
        }
        $result = $db->query('SELECT * FROM menu WHERE position = "d" ORDER BY ordre');
        while ($ligne = $result->fetchRow()) {
            $tabidmenud[] = $ligne->id_menu;
            $tablibellemenud[] = $ligne->libelle;
        }
        $result2 = $db->query('SELECT * FROM groupe_ad');
        while ($ligne2 = $result2->fetchRow()) {
            $tabidgroupead[] = $ligne2->id_groupe_ad;
            $tablibellegroupead[] = $ligne2->trig;
        }
        $i = 0;
        $nbgroupead = count($tabidgroupead);
        while ($i < $nbgroupead) {
            $htmlgroup .= '<th>' . $tablibellegroupead[$i] . '</th>';
            ++$i;
        }
        $j = 0;
        $nbmenu = count($tabidmenug);
        $htmlmenu .= '<th>Menu consultation</th>' .$htmlgroup;
        while ($j < $nbmenu) {
            $l = $j % 2;
            $l = ($l == 0) ? 'odd' : 'even';
            $k = 0;
            
            $htmlmenu .= '<tr class="row' . $l . '"><td>' . $tablibellemenug[$j] . '</td>';
            while ($k < $nbgroupead) {
                $ligne3 = $db->query('SELECT * FROM groupe_ad_menu WHERE Id_groupe_ad="' . mysql_real_escape_string($tabidgroupead[$k]) . '" AND Id_menu="' . mysql_real_escape_string($tabidmenug[$j]) . '"')->fetchRow();
                $check[$k][$j] = '';
                $bgcol = 'orange';
                if ($ligne3->id_groupe_ad != '') {
                    $check[$k][$j] = 'checked="checked"';
                    $bgcol = 'vert';
                }
                $htmlmenu .= '
			    <td class="row' . $bgcol . '" data-groupe="' . $tabidgroupead[$k] . '" data-menu="' . $tabidmenug[$j] . '" ><input type="checkbox" ' . $check[$k][$j] . ' onclick="appliquerDroitGroupeAdMenu(' . $tabidgroupead[$k] . ',' . $tabidmenug[$j] . ')" /></td>';
                ++$k;
            }
            $htmlmenu .= '</tr>';
            ++$j;
        }

        $j = 0;
        $nbmenu = count($tabidmenud);
        $htmlmenu .= '<th>Menu création</th>' .$htmlgroup;
        while ($j < $nbmenu) {
            $l = $j % 2;
            $l = ($l == 0) ? 'odd' : 'even';
            $k = 0;
            
            $htmlmenu .= '<tr class="row' . $l . '"><td>' . $tablibellemenud[$j] . '</td>';
            while ($k < $nbgroupead) {
                $ligne3 = $db->query('SELECT * FROM groupe_ad_menu WHERE Id_groupe_ad="' . mysql_real_escape_string($tabidgroupead[$k]) . '" AND Id_menu="' . mysql_real_escape_string($tabidmenud[$j]) . '"')->fetchRow();
                $check[$k][$j] = '';
                $bgcol = 'orange';
                if ($ligne3->id_groupe_ad != '') {
                    $check[$k][$j] = 'checked="checked"';
                    $bgcol = 'vert';
                }
                $htmlmenu .= '
			    <td class="row' . $bgcol . '"  data-groupe="' . $tabidgroupead[$k] . '" data-menu="' . $tabidmenud[$j] . '" ><input type="checkbox" ' . $check[$k][$j] . ' onclick="appliquerDroitGroupeAdMenu(' . $tabidgroupead[$k] . ',' . $tabidmenud[$j] . ')" /></td>';
                ++$k;
            }
            $htmlmenu .= '</tr>';
            ++$j;
        }
        $html = '
			<table class="hovercolored">
				<tr>
					<th></th>
				</tr>
					' . $htmlmenu . '
			</table>
';
        return $html;
    }

    /**
     * Valider le droit pour la zone d'accès pour le menu donné
     *
     * @param int Identifiant du groupe AD
     * @param int Identifiant du menu
     *
     */
    public static function applyGroupAdMenuRight($Id_groupe_ad, $Id_menu) {
        $db = connecter();
        $ligne = $db->query('SELECT * FROM groupe_ad_menu WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ' AND Id_menu=' . mysql_real_escape_string((int) $Id_menu))->fetchRow();
        if ($ligne->id_groupe_ad == '') {
            $requete2 = 'INSERT INTO groupe_ad_menu SET Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ', Id_menu=' . mysql_real_escape_string((int) $Id_menu);
        } else {
            $requete2 = 'DELETE FROM groupe_ad_menu WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ' AND Id_menu=' . mysql_real_escape_string((int) $Id_menu);
        }
        $db->query($requete2);
    }

    /**
     * Vérifier les droits d'un groupe ad sur un menu
     *
     * @return bool
     */
    public static function getDroitMenu($Id_groupe_ad, $Id_menu) {
        $droit = false;
        $db = connecter();
        $ligne = $db->query('SELECT Id_groupe_ad FROM groupe_ad_menu WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad) . ' AND Id_menu=' . mysql_real_escape_string((int) $Id_menu))->fetchRow();
        if ($ligne->id_groupe_ad != '') {
            $droit = true;
        }
        return $droit;
    }

    /**
     * Vérifier les droits d'un groupe ad sur un menu
     *
     * @param int Identifiant du groupe AD	  
     *
     * @return string	  
     */
    public static function getAccueilDefault($Id_groupe_ad) {
        $rep = 'public';
        $db = connecter();
        $ligne = $db->query('SELECT rep FROM zone_acces INNER JOIN groupe_ad ON groupe_ad.Id_zone_acces_default=zone_acces.Id_zone_acces 
		WHERE Id_groupe_ad=' . mysql_real_escape_string((int) $Id_groupe_ad))->fetchRow();
        if ($ligne->rep != '') {
            $rep = $ligne->rep;
        }
        return $rep;
    }

    /**
     * Vérifier les droits d'un groupe ad sur un menu
     *
     * @param string Libellé du groupe AD
     *
     * @return int
     */
    public static function getIdGroupeAd($libelle_groupe_ad) {
        $db = connecter();
        return $db->query('SELECT Id_groupe_ad FROM groupe_ad WHERE libelle = "' . mysql_real_escape_string($libelle_groupe_ad) . '"')->fetchRow()->id_groupe_ad;
    }

    /**
     * Message affichant l'interdiction d'accès
     *
     *
     * @return string
     */
    public static function forbiddenAccess() {
        $html =
                '<fieldset>
	        <legend>ACCES REFUSE</legend><br /><br />
		    <div class="left">
			    <img src="' . IMG_STOP . '">
			    <br /><br />
				' . ACCESS_REFUSE . '
				<br /><br />
			</div>
	    </fieldset>';
        return $html;
    }

}

?>