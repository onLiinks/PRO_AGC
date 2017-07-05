<?php

/**
 * Fichier GestionMenu.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe GestionMenu
 */
class GestionMenu {

    /**
     * Affichage du menu
     *
     * @param String Position du menu à afficher
     *
     * @return string
     */
    public static function afficherMenu($pos='g') {
        $db = connecter();
        $requete = 'SELECT DISTINCT m.libelle, url FROM menu m INNER JOIN groupe_ad_menu gm
		ON m.Id_menu=gm.Id_menu WHERE position="' . $pos . '"';
        if ($_SESSION[SESSION_PREFIX.'logged']->groupe_ad[0] !== null) {
            $requete .= ' AND Id_groupe_ad IN (' . mysql_real_escape_string(implode(',', $_SESSION[SESSION_PREFIX.'logged']->groupe_ad)) . ')';
        }
        $requete .= ' ORDER BY m.ordre, m.Id_menu';
        
        $result = $db->query($requete);
        while ($ligne = $result->fetchRow()) {
            if ($ligne->libelle == 'Affaire') {
                $html .= '
					<li><a href="#" onclick="lienAffaire()">Affaire</a>
			            <div id="lienaffaire" style="display:none">
				            <ul class="smenu">
				                ' . TypeContrat::getMenuList() . '
			                </ul>
		                </div>
		            </li>';
            } elseif ($ligne->libelle == 'Propale Infog') {
                $html .= '
				    <li><a href="#" onclick="lienPropale()">Suivi Avant-Vente</a>
	                    <div id="lienpropale" style="display:none">
			            <ul class="smenu">
			                ' . Proposition::statusSearch() . '
		                </ul>
		                </div>
	                </li>';
            } elseif ($ligne->libelle == 'CA prévisionnel') {
                $html .= '<li><a onclick="window.open(\'' . $ligne->url . '\')" href="javascript:void(0)">' . $ligne->libelle . '</a></li>';
            } else {
                $html .= '<li><a href="' . $ligne->url . '">' . $ligne->libelle . '</a></li>';
            }
        }
        echo $html;
    }

}

?>