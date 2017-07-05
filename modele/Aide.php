<?php

/**
 * Fichier Aide.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Aide
 */
class Aide {

    /**
     * Affichage de l'aide pour l'application
     *
     * @return string	   
     */
    static function display() {
        $html = '
	        <div class="center">
            <a href="' . URL_DOC_AGC . '">Lien vers la documentation</a> | 
			<a href="' . URL_DOC_REPORT_COM_AGC . '">Lien vers la documentation des rapports commerciaux</a> | 
			<a href="' . URL_DOC_REPORT_RH_AGC . '">Lien vers la documentation des rapports RH</a><br /><br />
			</div>
			<fieldset>
			<legend>Boutons AGC</legend><br/>
			<div class="left">
				<img src="' . IMG_CONSULT . '">  Bouton de consultation <br /><br />
		        <img src="' . IMG_EDIT . '">  Bouton de modification <br /><br />
				<img src="' . IMG_ENTRETIEN . '">  Accès feuille d\'entretien <br /><br />
				<img src="' . IMG_CV . '">  CV original d\'un candidat <br /><br />
				<img src="' . IMG_CVP . '">  CV au format PROSERVIA d\'un candidat <br /><br />
				<img src="' . IMG_LM . '">  Lettre de motivation d\'un candidat <br /><br />
			</div>
			<div class="right">
	            <img src="' . IMG_FLECHE_BAS . '">  Bouton d\'archivage <br /><br />
				<img src="' . IMG_FLECHE_HAUT . '">  Bouton de desarchivage <br /><br />
		        <img src="' . IMG_CLOSE . '">  Bouton de suppression
			</div>
			</fieldset>
			<br />
			<fieldset>
			<legend>Correspondance des trigrammes</legend><br/>
			<div class="left">
                ' . self::getTrigram("statut") . '<br />
				' . self::getTrigram("pole") . '
			</div>
			<div class="right">
				' . self::getTrigram("com_type_contrat") . '
			</div>			
			</fieldset>			
';

        //affichage du code couleur
        $html .= '<br />
			<fieldset>
					<legend>Code couleur des candidatures non lues</legend><br/>
					Les candidats non lus sont répartis dans les agences selon leur département d\'habitation.<br /><br />
			<div class="left">
				<table><tr>
					<td bgcolor="' . CANDIDAT_AIX_SOP . '" width="30" height="20"></td><td>Agences d\'Aix et Sophia </td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_BDX . '" width="30" height="20"></td><td>Agence de Bordeaux</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_LAN . '" width="30" height="20"></td><td>Agence de Lannion</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_LIL . '" width="30" height="20"></td><td>Agence de Lille</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_LYO . '" width="30" height="20"></td><td>Agence de Lyon</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_001 . '" width="30" height="20"></td><td>Agence de Nantes</td>
				</tr></table></div>
				<div class="right">
				<table><tr>
					<td bgcolor="' . CANDIDAT_NIO . '" width="30" height="20"></td><td>Agence de Niort</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_CAE_LHA_ROU . '" width="30" height="20"></td><td>Agences de Normandie : Caen, Le Havre, Rouen</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_PAR . '" width="30" height="20"></td><td>Agence de Paris</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_REN . '" width="30" height="20"></td><td>Agence de Rennes</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_TOU . '" width="30" height="20"></td><td>Agence de Toulouse</td>
				</tr><tr>
					<td bgcolor="' . CANDIDAT_TRS . '" width="30" height="20"></td><td>Agence de Tours</td>
				</tr>
				</table>
		    </div>
			</fieldset><br />
			<fieldset>
				<legend>MISES A JOUR</legend><br />
				' . Version::getVersions() . '
			</fieldset>
			';
        return $html;
    }

    /**
     * Affichage des astuces de l'application
     *
     * @return string	   
     */
    static function astuce() {
        $html = '
			<fieldset>
			<legend>ASTUCES</legend><br/>
			<div class="left">
			    <h2>Pour ne plus avoir les 3 demandes de mot de passe au lancement de l\'AGC</h2>
                <ul>
				    <li>Taper about:config dans la barre d’adresse de firefox</li>
					<li>Dans la zone de filtre taper « ntlm »</li>
					<li>Double-click sur "network.automatic-ntlm-auth.trusted-uris"</li>
					<li>Dans la fenêtre taper cette adresse : http://srv108.proservia.lan,</li>
					<li>Fermer firefox et redémarrer</li>
				</ul>	
			</div>
			</fieldset>
			<br /><br />
';
        return $html;
    }

    /**
     * Affichage du trigramme du statut
     *
     * @param int Identifiant du statut
     *
     * @return string	   
     */
    public static function getTrigram($table) {
        $db = connecter();
        $result = $db->query('SELECT * FROM ' . mysql_real_escape_string($table) . '');
        $html = '<table class="sortable" style="width:300px">';
        while ($ligne = $result->fetchRow()) {
            $html .= '
			<tr>
			    <td>' . $ligne->trig . '</td>
			    <td>' . $ligne->libelle . '</td>
			</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Affichage de l'aide pour la pondération
     *
     * @return string	   
     */
    static function displayHelpWeighting() {
        $html = '
            <div>
                <table>
                    <thead class="helpHeader">
                        <tr>
                            <td class="tdHelp">Affaires <b>« en cours de rédaction »</b></td>
                            <td class="tdHelp">Affaires <b>« remises »</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="headerWeighting">
                            <td class="tdHelp">10%</td>
                            <td class="tdHelp">10%</td>
                        </tr>
                        <tr class="bodyWeighting">
                            <td class="tdHelp">Affaires sans Visibilité (Client pas connu <b>et/ou</b> difficulté pour traiter la demande)</td>
                            <td>
                                Affaires sans Visibilité (Client pas connu <b>et/ou</b> difficulté pour traiter la demande)
                            </td>
                        </tr>
                        <tr class="headerWeighting">
                            <td class="tdHelp">20%</td>
                            <td class="tdHelp">20%</td>
                        </tr>
                        <tr class="bodyWeighting">
                            <td class="tdHelp">Affaires avec Visibilité (le client connu <b>et</b> on répond à sa demande sans difficulté)</td>
                            <td>
                                <ul class="ulTable">                            
                                    <li>Proservia fait partie de l\'ensemble des sociétés consultées classiques (multi-référencements) <span class="highlight">et/ou</span></li>
                                    <li>Il y a une forte concurrence sur l\'appel d\'offre <span class="highlight">et/ou</span></li>
                                    <li>La demande concerne un ou des Profils atypiques <span class="highlight">et/ou</span></li>
                                    <li>La demande est une 1ère consultation prospect</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="headerWeighting">
                            <td class="tdHelp"></td>
                            <td class="tdHelp">40%</td>
                        </tr>
                        <tr class="bodyWeighting">
                            <td class="tdHelp"></td>
                            <td>
                                <ul class="ulTable">                            
                                    <li>Proservia fait partie de l\'ensemble des sociétés consultées classiques (multi-référencements) <span class="highlight">+</span></li>
                                    <li>Bonne QRC (qualité relation cliente) <span class="highlight">et/ou</span></li>
                                    <li>Proservia est Short listé</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="headerWeighting">
                            <td></td>
                            <td class="tdHelp">60%</td>
                        </tr>
                        <tr class="bodyWeighting">
                            <td></td>
                            <td>
                                <ul class="ulTable">
                                    <li>Bonne présentation cliente (présentation du collaborateur, soutenance...)</li>
                                    <li>Bon taux de transformation <b>habituel chez le client</b> (affaires déjà faites avec le client)</li>
                                </ul>
                            </td>
                        </tr>
                        <tr class="headerWeighting">
                            <td></td>
                            <td class="tdHelp">80%</td>
                        </tr>
                        <tr class="bodyWeighting">
                            <td></td>
                            <td>
                                <ul class="ulTable">
                                    <li>Retours positifs client ou autre / discours encourageant du client <span class="highlight">et/ou</span></li>
                                    <li>Découverte de sponsors <span class="highlight">et/ou</span></li>
                                    <li>Audit /  Due diligence souhaité par le client avant décision (diligence raisonnable)</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        ';
        return $html;
    }
}

?>