<?php

/**
 * Fichier Log.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Log
 */
class Log {

    /**
     * Constructeur de la classe Intitule
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant d'un intitule
     * @param array Tableau passé en argument : tableau $_POST ici 
     */
    public function __construct($Id_affaire, $log) {
        $this->Id_affaire = $Id_affaire;
        $this->log = serialize($log);
    }

    /**
     * Recherche d'un log
     *
     * @param int Identifiant de l'affaire
     * @param string Créateur du log
     * @param date Date antérieure à la date de création du log
     * @param date Date postérieure à la date de création du log
     *
     * @return string
     */
    public static function search($Id_affaire, $Id_utilisateur, $debut, $fin) {
        $requete = 'SELECT * FROM log_update_affaire WHERE Id_affaire !=0';

        if ($Id_affaire) {
            $requete .= ' AND Id_affaire="' . (int) $Id_affaire . '"';
        }
        if ($Id_utilisateur) {
            $requete .= ' AND Id_utilisateur="' . $Id_utilisateur . '"';
        }
        if ($debut && $fin) {
            $requete .= ' AND date BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        $requete .= ' ORDER BY date DESC';
        $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
            'fileName' => 'javascript:HTML_AJAX.replace(\'page\',\'../source/index.php?a=consulterLog&pageID=%d&Id_affaire=' . $Id_affaire . '&Id_utilisateur=' . $Id_utilisateur . '&debut=' . $debut . '&fin=' . $fin . '\');', //Pager replaces "%d" with the page number...
            'perPage' => TAILLE_LISTE, 'delta' => DELTA, 'itemData' => $data);

        $paged_data = Pager_Wrapper_MDB2(connecter(), $requete, $pager_params);

        if (!$paged_data['totalItems']) {
            $html = NO_DATA_INFO;
        } else {
            $html = '
			    <p class="pagination">' . $paged_data['links'] . '</p>
		        <table class="sortable">
		            <tr>
				        <th>Date</th>
					    <th>Id affaire</th>
					    <th>Utilisateur</th>
						<th>@ IP</th>
					    <th>Log</th>
		            </tr>
';
            $i = 0;
            foreach ($paged_data['data'] as $ligne) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                $html .= '
                <tr class="row' . $j . '">
	                <td>' . $ligne['date'] . '</td>
			        <td>' . $ligne['id_affaire'] . '</td>
			        <td>' . $ligne['id_utilisateur'] . '</td>
					<td>' . $ligne['adresse_ip'] . '</td>
			        <td>' . $ligne['log'] . '</td>
		        </tr>
';
                ++$i;
            }
            $html .= '</table><p class="pagination">' . $paged_data['links'] . '</p>';
        }
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $db->query('INSERT INTO log_update_affaire SET Id_affaire = "' . mysql_real_escape_string((int) $this->Id_affaire) . '", 
		date = "' . mysql_real_escape_string(DATETIME) . '", Id_utilisateur="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '", 
		adresse_ip="' . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . '", log="' . mysql_real_escape_string($this->log) . '"');
    }

    /**
     * Affichage du formulaire de recherche d'un log
     *
     * @return string	 
     */
    public function searchForm() {
        if (empty($_SESSION['filtre']['Id_utilisateur'])) {
            $_SESSION['filtre']['Id_utilisateur'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        $utilisateur = new Utilisateur($_SESSION['filtre']['Id_utilisateur'], array());
        $html = '
		n° Affaire: <input id="Id_affaire" type="text" onkeyup="afficherLog()" value="' . $_SESSION['filtre']['Id_affaire'] . '" size="2" />
        &nbsp;&nbsp;				
		<select id="Id_utilisateur" onchange="afficherLog()">
            <option value="">Par utilisateur</option>
            <option value="">----------------------------</option>
		    ' . $utilisateur->getList('COM') . '
            <option value="">----------------------------</option>
			' . $utilisateur->getList('OP') . '
        </select>
		&nbsp;&nbsp;
		du <input id="debut" type="text" onfocus="showCalendarControl(this)" size="8" value=' . $_SESSION['filtre']['debut'] . ' >
		&nbsp;
		au <input id="fin" type="text" onfocus="showCalendarControl(this)"  size="8" value=' . $_SESSION['filtre']['fin'] . ' >
		<input type="button" onclick="afficherLog()" value="Go !">
';
        return $html;
    }

}

?>