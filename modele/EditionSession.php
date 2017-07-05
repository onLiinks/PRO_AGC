<?php

/**
 * Fichier EditionSession.php
 *
 * @author    Fr�d�rique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * D�claration de la classe EditionSession
 */
class EditionSession extends Session {

    /**
     * Tableau contenant les informations d'�dition pour chaque document de la session
     *
     * @access public
     * @var array
     */
    public $doc;

    /**
     * Constructeur de la classe EditionSession
     *
     * Constructeur : initialiser suivant la pr�sence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la session
     * @param array Tableau pass� en argument : tableau $_POST ici 
     */
    public function __construct($code, $tab) {
        try {
            parent::__construct($code, $tab);

            /* les instances de la classe EditionSession ne sont cr��es que pour des sessions qui existent d�j�
              (car cr��es � partir de la fonction consultation de la classe session) donc pas de constructeur
              selon les quatre cas habituels */
            $this->description = str_replace('\n', ' ', $this->description);

            //r�cup�ration des informations sur les �ditions d�j� r�alis�es pour la session
            $this->doc['Id_doc'] = array();
            $db = connecter();
            $result = $db->query('SELECT Id_doc, createur, DATE_FORMAT(date_edition,\'%d-%m-%Y\') AS date, 
									DATE_FORMAT(date_edition,\'%Hh%i\') AS heure FROM doc_formation WHERE Id_session=' . mysql_real_escape_string((int) $code));
            while ($ligne = $result->fetchRow()) {
                $this->doc[$ligne->id_doc]['edite'] = 1;
                $this->doc[$ligne->id_doc]['createur'] = $ligne->createur;
                $this->doc[$ligne->id_doc]['date_edition'] = $ligne->date;
                $this->doc[$ligne->id_doc]['heure_edition'] = $ligne->heure;
            }
        } catch (Exception $e) {
            throw new GesComException($e->getMessage());
        }
    }

    /**
     * Enregistrement de l'�dition du document dans la base de donn�es
     *
     * @param int Identifiant du type de document dont il faut enregistrer l'�dition
     *
     */
    public function save($Id_doc) {
        $db = connecter();
        //suppression de l'ancien enregistrement si il existe 
        $db->query('DELETE FROM doc_formation WHERE Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '" AND Id_doc="' . mysql_real_escape_string((int) $Id_doc) . '"');
        //enregistrement de l'�dition avec la date du jour et l'heure
        $db->query('INSERT INTO doc_formation SET Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '", Id_doc = "' . mysql_real_escape_string((int) $Id_doc) . '",
					date_edition="' . mysql_real_escape_string(DATETIME) . '", createur="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '"');
    }

    /**
     * Edition des attestations de pr�sence � faire signer par les participants pendant la session
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionPresence($nomDossier) {
        $db = connecter();
        //r�cup�ration des dates du planning en calculant toutes les dates des jours ouvr�s de la session
        $planning = new PlanningSession($this->Id_planning, array());
        $dates = array();
        $dates = $planning->listeDates();
        $nbJour = count($dates['jour']);

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
            $espaceVersion = ' ';
        } else {
            $marge = 'margin-left 	: 2px;
							   margin-right : 2px';
            $espaceVersion = '<br/>';
        }

        //r�cup�ration des affaires associ�es � la session
        $result = $db->query('SELECT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            //r�cup�ration du nombre d'inscrits pour l'affaire
            $ligne1 = $db->query('SELECT COUNT(nom) AS nb_inscrit
                                    FROM participant WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session) . '
                                    AND Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"')->fetchRow();

            //selon le nombre d'inscrits : mod�les diff�rents
            //pour les affaires ayant plusieurs inscrits : feuille d'attestation de groupe
            if ((int) $ligne1->nb_inscrit > 1) {
                //ouverture ou cr�ation du fichier (un fichier par affaire)
                $nomFichier = str_replace("'", "", $compte->nom);
                $nomFichier = htmlscperso(formatageString($nomFichier), ENT_QUOTES);

                //D�but du document : insertion du css
                $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                        <head>
                            <!--[if gte mso 9]>
                            <xml>
                                <w:WordDocument>
                                    <w:View>Print</w:View>
                                    <w:Zoom>100</w:Zoom>
                                    <w:DoNotOptimizeForBrowser/>
                                </w:WordDocument>
                            </xml>
                            <![endif]-->
                            <link rel=File-List href="document_files/filelist.xml">
                            <style><!-- 
                                @page
                                {
                                    size:21cm 29.7cmt;  /* A4 */
                                    margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                    mso-page-orientation: portrait;  
                                    mso-header: url("document_files/headerfooter.htm") h1;
                                    mso-footer: url("document_files/headerfooter.htm") f1;	
                                }
                                @page Section1 { }
                                div.Section1 { page:Section1; }
                                p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                            --></style>
                                <style type="text/css">
									table 
										{
											font-family : arial;
											font-size 	: 14px;
										}
									th
										{
											text-align 		 : center;
											color 			 : ' . COULEUR_TEXTE2 . ';
											background-color : ' . COULEUR_TAB1 . ';
										}
									h1
										{
											font-family 	 : arial;
											font-size 		 : 20px;
											text-align 		 : center;
											font-weight 	 : bold;
											color 			 : ' . COULEUR_TEXTE2 . ';
											background-color : ' . COULEUR_TAB1 . ';
											margin-top 		 : 0px;
											' . $marge . '
										}
									h2
										{
											font-family 	: arial;
											font-size 		: 14px;
											text-align 		: left;
											font-weight 	: normal;
											vertical-align 	: top;
										}
									h3
										{
											font-family 	: arial;
											font-size 		: 9px;
											text-align 		: left;
											font-weight 	: normal;
											vertical-align 	: top;
										}
									p
										{
											font-family : arial;
											font-size 	: 14px;
											text-align 	: right;
										}
									.pied_de_page
										{
											font-family : arial;
											color 		: ' . COULEUR_TEXTE1 . ';
											font-size 	: 10px;
											text-align 	: left;
										}
								</style>
                                                                </head>
                <body>
                <div class=Section1>';

                //r�cup�ration de la liste des inscrits pour l'affaire
                $result2 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"
                                        AND Id_session = ' . (int) $this->Id_session . ' ORDER BY nom');
                $participant['nom'] = array();
                $participant['prenom'] = array();
                while ($ligne2 = $result2->fetchRow()) {
                    array_push($participant['nom'], $ligne2->nom);
                    array_push($participant['prenom'], $ligne2->prenom);
                }

                //r�cup�ration du ou des formateurs de la session
                $result2 = $db->query('SELECT f.nom, f.prenom 
							   FROM formateur AS f INNER JOIN formateur_salle AS fs ON f.Id_formateur=fs.Id_formateur 
							   WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
                $formateur['nom'] = array();
                $formateur['prenom'] = array();
                while ($ligne2 = $result2->fetchRow()) {
                    array_push($formateur['nom'], $ligne2->nom);
                    array_push($formateur['prenom'], $ligne2->prenom);
                }

                //calcul du nombre de page : maximum cinq dates en largeur et 6 participants en hauteur sur une page
                $nbPage = 1;
                $nbParticipant = (int) $ligne1->nb_inscrit;
                if ($nbParticipant > 6) {
                    $nbPage = (int) ($nbParticipant / 6); //calcul du nombre de page
                    if ($nbParticipant % 6) { //si le nombre de participants n'est pas un multiple de 5 ajout d'une page
                        ++$nbPage;
                    }
                }

                $Jour = 0; //compteur du nombre de dates affich�es
                while ($Jour < $nbJour) {
                    $page = 0; //compteur du nombre de pages pour une s�rie de cinq dates
                    $particip = 0; //compteur des participants affich�s
                    while ($page < $nbPage) {
                        $i = $Jour; //indice pour le tableau des dates, commence au nombre de dates d�j� affich�es
                        //insertion d'un saut de page � chaque d�but de nouvelles pages sauf pour la premi�re page
                        if ($i || $page) {
                            $doc .= "<br clear=all style='page-break-before:always'>";
                        }

                        if (($nbParticipant < 6) || (($nbParticipant > 6) && ($page == ($nbPage - 1)) && ($nbParticipant % 6))) {
                            $espace1 = '<br/>';
                        } else {
                            $espace1 = '';
                        }

                        //pour chaque page, affichage des informations g�n�rales
                        $doc .= '<h1>ATTESTATION DE PRESENCE</h1>';
                        $doc .= '<div align="left">' . $espace1 . '<table><tr>
								<td width="100" height="25"><h2>Soci�t� :</h2></td>
								<td width="400" height="25"><h2>' . $compte->nom . '</h2></td>
								<td width="80" height="25"><h2>Adresse :</h2></td>
								<td width="400" height="25"><h2>' . $compte->adresse . '</h2></td>
							</tr><tr>
								<td width="100" height="25"><h2>Stage :</h2></td>
								<td width="400" height="25"><h2>' . $this->nom_session . '</h2></td>
								<td width="80" height="25"> </td>
								<td width="400" height="25"><h2>' . $compte->code_postal . ' ' . $compte->ville . '</h2></td>
							</tr><tr>
								<td width="100" height="25"><h2>Formateur :</h2></td>
								<td colspan="3" width="400" height="25"><h2>' . $formateur['nom'][0] . ' ' . $formateur['prenom'][0];
                        //si il y a plusieurs formateurs affichage de tous les formateurs � la suite
                        $k = 1; //indice du tableau des formateurs
                        while ($k < count($formateur['nom'])) {
                            $doc .= ' - ' . $formateur['nom'][$k] . ' ' . $formateur['prenom'][$k];
                            ++$k;
                        }
                        $doc .= '</h2></td></tr></table></div>';

                        //tableau pour les signatures : premi�re ligne : affichage des dates
                        $doc .= '<div align="center">' . $espace1 . '
							<table border="1" cellspacing="0" bordercolor=' . COULEUR_TAB1 . '><tr>
								<th width="200" height="20"><b>Stagiaires</b></th>';
                        $d = 0; //compteur du nombre de dates pour la page
                        while (($i < $nbJour) && ($d < 5)) {
                            //affichage date tant que en dessous du nombre de dates totales ou du nombre de dates autoris�es par page
                            $doc .= '<th width="150" height="20">' . $dates['jour'][$i] . '-' . $dates['mois'][$i] . '-' . $dates['annee'][$i] . '
									 </th>';
                            ++$i; //incr�mentation de l'indice pour le tableau des dates
                            ++$d; //incr�mentation du compteur de dates par page
                        }
                        $doc .= '</tr>';

                        //tableau pour les signatures : affichage des participants en ligne avec les cases pour les signatures
                        $j = 0; //compteur du nombre de participants pour la page
                        while (($particip < $nbParticipant) && ($j < 6)) {
                            /* affichage participant tant que en dessous du nombre de participants totales ou du nombre de participants 
                              autoris�s par page */
                            //premi�re case : le nom du participant
                            $doc .= '<tr>
								 <td rowspan="2" width="200" height="80" align="center"> ' . $participant['nom'][$particip] . '<br/>
									' . $participant['prenom'][$particip] . '</td>';
                            //pour les cases suivantes : affichage sur deux lignes (une pour le matin et une pour l'apr�s-midi)
                            $k = 0; //compteur de case pour afficher autant de case 'matin' que de date dans la page
                            while ($k < $d) {
                                $doc .= '<td width="150" height="40" align="left" valign="top"><h3>matin</h3></td>';
                                ++$k;
                            }
                            $doc .= '</tr><tr>';
                            $k = 0; //compteur de case pour afficher autant de case 'apr�s-midi' que de date dans la page
                            while ($k < $d) {
                                $doc .= '<td width="150" height="40" align="left" valign="top"><h3>apr�s-midi</h3></td>';
                                ++$k;
                            }
                            $doc .= '</tr>';
                            ++$j; //incr�mentation du compteur de participants par page
                            ++$particip; //incr�mentation de l'indice pour le tableau des participants
                        }
                        //fin du tableau des signatures
                        $doc .= '</table></div>';

                        $doc .= '' . $espace1 . '<p>Signature du formateur et cachet du centre de formation : </p><br/><br/>';

                        //selon le nombre de participants affich�s dans le tableau, affichage d'espace pour placer le pied de page
                        switch ($j) {
                            case 1 :
                                $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
										 <br/><br/><br/>';
                                break;
                            case 2 :
                                $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                                break;
                            case 3 :
                                $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                                break;
                            case 4 :
                                $doc .= '<br/><br/><br/><br/><br/><br/><br/>';
                                break;
                            case 5 :
                                $doc .= '<br/><br/>';
                                break;
                        }
                        ++$page; //incr�mentation du compteur de nombre de page pour une s�rie de cinq dates
                    }
                    $Jour = $i; //le nombre de jours affich�s correspond � la nouvelle position de l'indice du tableau de dates
                }
                $doc .= '</div>
                </body>
                </html>';

                $entete = fopen(ENTETE, "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $header .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
                $data .= base64_encode($doc) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
                $data .= base64_encode($header) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
                $data .= base64_encode($logo) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
                $data .= base64_encode($pied) . "\n\n";
                $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

                $fichier = fopen($nomDossier . "/Attestation_Presence_" . $nomFichier . ".doc", 'wb');
                fwrite($fichier, $data);
                fclose($fichier);
            }
            //sinon pour les affaire ayant un seul inscrit: feuille d'attestation de pr�sence individuelle
            else if ((int) $ligne1->nb_inscrit <= 1) {
                //D�but du document : insertion du css
                $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                        <head>
                            <!--[if gte mso 9]>
                            <xml>
                                <w:WordDocument>
                                    <w:View>Print</w:View>
                                    <w:Zoom>100</w:Zoom>
                                    <w:DoNotOptimizeForBrowser/>
                                </w:WordDocument>
                            </xml>
                            <![endif]-->
                            <link rel=File-List href="document_files/filelist.xml">
                            <style><!-- 
                                @page
                                {
                                    size:21cm 29.7cmt;  /* A4 */
                                    margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                    mso-page-orientation: portrait;  
                                    mso-header: url("document_files/headerfooter.htm") h1;
                                    mso-footer: url("document_files/headerfooter.htm") f1;	
                                }
                                @page Section1 { }
                                div.Section1 { page:Section1; }
                                p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                            --></style>
                                <style type="text/css">
									table 
										{
											font-family : arial;
											font-size 	: 16px;
										}
									th
										{
											font-weight 	 : bold;
											text-align 		 : center;
											color 			 : ' . COULEUR_TEXTE2 . ';
											background-color : ' . COULEUR_TAB1 . ';
										}
									h1
										{
											font-weight 	 : bold;
											font-family 	 : arial;
											font-size 		 : 22px;
											text-align 		 : center;
											color 			 : ' . COULEUR_TEXTE2 . ';
											background-color : ' . COULEUR_TAB1 . ';
											margin-top 		 : 0px;
											' . $marge . '
										}
									h2
										{
											font-weight : bold;
											font-family : arial;
											font-size 	: 18px;
											text-align 	: left;
										}
									p
										{
											font-family : arial;
											font-size 	: 16px;
											text-align 	: right;
										}
									.pied_de_page
										{
											font-family : arial;
											color 		: ' . COULEUR_TEXTE1 . ';
											font-size 	: 10px;
											text-align 	: left;
										}
								</style>
                                                                </head>
                <body>
                <div class=Section1>';

                //calcul du nombre de page pour le candidat : maximum 8 dates par page
                $nbPage = 1; //nombre de pages pour le candidat
                if ($nbJour > 8) {
                    $nbPage = (int) ($nbJour / 8);
                    if ($nbJour % 8) {
                        ++$nbPage;
                    }
                }
                //R�cup�ration du participant pour l'affaire
                $ligne1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"
                                        AND Id_session = ' . (int) $this->Id_session)->fetchrow();

                //mise en forme des chaines de cract�res � utiliser pour le nom du fichier
                $nomFichier = str_replace("'", "", $compte->nom);
                $nomFichier = htmlscperso(formatageString($nomFichier), ENT_QUOTES);
                $nomParticipant = str_replace("'", "", $ligne1->nom);
                $nomParticipant = htmlscperso(formatageString($nomParticipant), ENT_QUOTES);
                $prenomParticipant = str_replace("'", "", $ligne1->prenom);
                $prenomParticipant = htmlscperso(formatageString($prenomParticipant), ENT_QUOTES);

                $page = 0; //compteur de page
                $i = 0; //indice pour le tableau des dates
                while ($page < $nbPage) {
                    //insertion d'un saut de page sauf pour la premi�re
                    if ($page) {
                        $doc .= "<br clear=all style='page-break-before:always'>";
                    }

                    //d�but de la mise en forme
                    $doc .= '<h1>Attestation de pr�sence</h1><br/> 
							<h2>Stage : ' . $this->nom_session . '</h2>
							<div align="left"><br/><table><tr>
									<td width="80" height="25"> Soci�t� : </td>
									<td width="600" height="25"> ' . $compte->nom . ' </td>
								</tr><tr>
									<td width="80" height="25"> Nom : </td>
									<td width="600" height="25"> ' . $ligne1->prenom . ' ' . $ligne1->nom . '</td>
								</tr>
							</table></div>';
                    //tableau des dates
                    $doc .= '<div align="center"><br/><br/><br/>
							<table border="1" cellspacing="0" bordercolor=' . COULEUR_TAB1 . '><tr>
								<th width="180" height="40">DATE</th>
								<th width="180" height="40">SIGNATURE</th>
							</tr>';

                    /* insertion des dates : tant que l'indice i est en dessous du nombre de date et que le compteur j est inf�rieur 
                      au nombre de dates possibles par page */
                    $j = 0; //compteur du nombre de dates pour la page
                    while (($i < $nbJour) && ($j < 8)) { //taille du tableau de date
                        $doc .= '<tr>
                                <td width="180" height="40" align="center"> ' .
                                $dates['jour'][$i] . '-' . $dates['mois'][$i] . '-' . $dates['annee'][$i] . ' </td>
									<td width="120" height="40"> </td>
								</tr>';
                        ++$i;
                        ++$j;
                    }
                    $doc .= '</table></div>';

                    //selon le nombre de dates affich�es dans le tableau, affichage d'espace pour placer le pied de page
                    switch ($j) {
                        case 1 :
                            $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                            break;
                        case 2 :
                            $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                            break;
                        case 3 :
                            $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                            break;
                        case 4 :
                            $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                            break;
                        case 5 :
                            $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/>';
                            break;
                        case 6 :
                            $doc .= '<br/><br/><br/><br/><br/>';
                            break;
                        case 7 :
                            $doc .= '<br/><br/><br/>';
                            break;
                    }

                    $doc .= '<p> Le ' . FormatageDate($planning->dateFin) . ' <br/>
												Signature et cachet du centre de formation : </p><br/><br/><br/>';



                    //incr�mentation du compteur de page
                    ++$page;
                }
                $doc .= '</div>
                </body>
                </html>';

                $entete = fopen(ENTETE, "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $header .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
                while (!feof($entete)) { //on parcourt toutes les lignes
                    $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
                }
                fclose($entete);

                $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
                $data .= base64_encode($doc) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
                $data .= base64_encode($header) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
                $data .= base64_encode($logo) . "\n\n";
                $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
                $data .= base64_encode($pied) . "\n\n";
                $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

                $fichier = fopen($nomDossier . "/Attestation_Presence_" . $nomParticipant . "_" . $prenomParticipant . "_" . $nomFichier . ".doc", 'wb');
                fwrite($fichier, $data);
                fclose($fichier);
            }
        }
    }

    /**
     * Edition des attestations de stage de la session
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionAttestation($nomDossier) {
        //r�cup�ration des dates du planning
        $db = connecter();
        $planning = new PlanningSession($this->Id_planning, array());

        //mise en forme des dates
        //cas 1 : que des dates ponctuelles (et moins de sept dates), affichage des dates
        if (($planning->nb_Jour < 7) && (count($planning->date) > 0) && (count($planning->periode_debut) == 0)) {
            $docDate = 'les : ' . formatageDate($planning->dateDebut) . ', ';
            //pour les dates ponctuelles
            $i = 0; //indice du tableau de dates
            $nbDate = 1; //compteur du nombre de dates affich�es
            while ($i < count($planning->date)) {
                if (($planning->date[$i] != $planning->dateDebut) && ($planning->date[$i] != $planning->dateFin)) {
                    $docDate .= formatageDate($planning->date[$i]) . ', ';
                    ++$nbDate;
                    //toutes les deux dates, retour � la ligne
                    if (!($nbDate % 2)) {
                        $docDate .= '<br/>';
                    }
                }
                ++$i;
            }
            $docDate .= formatageDate($planning->dateFin) . ' ';
            //nombre d'espace variant selon le nombre de lignes utilis�es pour afficher les dates
            if ($i > 2) {
                $espaceDate = '<br/>';
            } else {
                $espaceDate = '<br/><br/>';
            }
            //cas 2 : que des p�riodes (et moins de quatre p�riodes), affichage des p�riodes
        } else if ((count($planning->periode_debut) > 0) && (count($planning->periode_debut) < 4) && (count($planning->date) == 0)) {
            $docDate = 'du ' . formatageDate($planning->periode_debut[0]) . ' au ' . formatageDate($planning->periode_fin[0]);
            $i = 1; //indice du tableau de p�riode
            while ($i < count($planning->periode_debut)) {
                $docDate .= ',<br/> du ' . formatageDate($planning->periode_debut[$i]) . ' au ' . formatageDate($planning->periode_fin[$i]);
                ++$i;
            }
            //nombre d'espace variant selon le nombre de lignes utilis�es pour afficher les dates
            if ($i < 3) {
                $espaceDate = '<br/><br/>';
            } else {
                $espaceDate = '<br/>';
            }
            //cas 3 : sinon affichage que des dates de d�but et de fin
        } else {
            $docDate = 'du ' . formatageDate($planning->dateDebut) . ' au ' . formatageDate($planning->dateFin) . ' ';
            $espaceDate = '<br/><br/>';
        }

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
            $espaceVersion = ' ';
        } else {
            $marge = 'margin-left 	: 2px;
							   margin-right : 2px';
            $espaceVersion = '<br/>';
        }

        //D�but du document : insertion du css
        $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family 	 : arial;
							font-size 		 : 16px;
							background-color : ' . LIGNE2 . ';
							text-align 		 : center;
						}
					th
						{
							font-weight : bold;
							font-size 	: 18px;
						}
					h1
						{
							font-weight 	 : bold;
							font-family 	 : arial;
							font-size 		 : 20px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							text-align 		 : center;
							margin-top 		 : 0px;
							' . $marge . '
						}
					h2
						{
							font-weight   : normal;
							font-family   : arial;
							font-size     : 16px;
							text-align    : right;
							margin-right  : 15px;
							margin-top 	  : 1px;
							margin-bottom : 1px;
						}
					.marge
						{
							margin-right : 160px;
						}
					p
						{
							font-family : arial;
							font-size   : 16px;
							text-align  : left;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align 	: left;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

        $page1 = true; //indicateur pour la premi�re page
        //r�cup�ration des affaires associ�es � la session
        $result = $db->query('SELECT DISTINCT Id_affaire FROM participant p
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        while ($ligne = $result->fetchRow()) {
            //Pour chaque affaire, r�cup�ration de la liste des participants
            $result1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"
                                AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));

            //cr�ation d'une page par participant
            $modeleVide = true; //indicateur pour produire un mod�le vide si il n'y a pas de participants renseign�s
            $leParticipant = ($ligne1 = $result1->fetchrow());
            while ($leParticipant || $modeleVide) {
                $modeleVide = false; //passage de l'indicateur du mod�le vide � 0 pour ne produire qu'un seul mod�le vide par page
                //insertion d'un saut de page sauf pour la premi�re page
                if (!$page1) {
                    $doc .= "<br clear=all style='page-break-before:always'>";
                } else {
                    $page1 = false; //si on �tait � la premi�re page, passage de l'indicateur � false
                }


                //attestation
                $doc .= '<h1>ATTESTATION DE STAGE</h1><br/><br/><br/><br/>
						<p>Je soussign�e, Madame Ludivine CRIAUD, en qualit� de consultante formatrice PROSERVIA, atteste que ' .
                        $ligne1->prenom . ' ' . $ligne1->nom . ' a suivi la formation suivante : </p>
						<br/><br/><br/><div align="center"><table><tr>
									<td width="300" height="25">Stage : </td>
								</tr><tr>
									<th width="300" height="25"> ' . $this->nom_session . '</th>
								</tr><tr>
									<td width="300" height="25"> </td>
								</tr><tr>
									<td width="300" height="25">' . $docDate . '</td>
								</tr><tr>
									<td width="300" height="25">pour une dur�e de ' . $planning->nb_Jour . ' jour(s) </td>
								</tr>
						</table></div>
								<br/><br/><br/><br/>
						<p>Fait � Carquefou, le ' . formatageDate($planning->dateFin) . '<br/>
											Pour servir et valoir ce que de droit.</p><br/><br/><br/>
						<div class="marge"><h2><b>Ludivine CRIAUD</b></h2></div>
						<h2>Directrice P�le Formation PROSERVIA</h2>
						' . $espaceDate . '<br/><br/><br/><br/>';

                //incr�mentation des lignes d'enregistrements r�cup�r�s et affectation de la variable lePerticpant pour la boucle
                $leParticipant = ($ligne1 = $result1->fetchrow());
            }
        }
        $doc .= '</div>
                </body>
                </html>';

        //�criture dans le fichier et fermeture du fichier
        $entete = fopen(ENTETE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $header .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($doc) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($header) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($logo) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($pied) . "\n\n";
        $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

        $fichier = fopen($nomDossier . "/Attestation_de_stage.doc", 'wb');
        fwrite($fichier, $data);
        fclose($fichier);
    }

    /**
     * Edition de la checklist de la session
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionChecklist($nomDossier) {
        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
        } else {
            $marge = 'margin-left  : 2px;
					  margin-right : 2px';
        }

        //D�but du document : insertion du css
        $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: left;
							color 		: ' . COULEUR_TAB1 . ';
							margin-top 	: 0px;
							' . $marge . '
						}
					th
						{
							font-weight : bold;
							font-size 	: 14px;
							text-align 	: center;
						}
					h1
						{
							font-weight 	 : bold;
							font-size 		 : 20px;
							text-align 		 : center;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							margin-top 		 : 0px;
							' . $marge . '
						}
					hr
						{
							color 			 : ' . COULEUR_TAB1 . ';
							background-color : ' . COULEUR_TAB1 . ';
							height			 : 2px;
							width  			 : 25px;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

        $doc .= '<h1>CHECKLIST SESSION N�' . $this->Id_session . ' : ' . $this->nom_session . '</h1>';
        //r�cup�ration de la checklist avec la classe Logistique
        $logistique = new Logistique($this->Id_session, array());
        $doc .= $logistique->edition() . '<br/>';

        $doc .= '</div>
                </body>
                </html>';

        $entete = fopen(ENTETE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $header .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($doc) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($header) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($logo) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($pied) . "\n\n";
        $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

        $fichier = fopen($nomDossier . "/Checklist.doc", 'wb');
        fwrite($fichier, $data);
        fclose($fichier);
    }

    /**
     * Edition des feuilles d'�valuations de la formation par les stagiaires
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionEvaluationStagiaire($nomDossier) {
        //cr�ation de l'instance planning
        $planning = new PlanningSession($this->Id_planning, array());

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
            $espaceVersion = '';
        } else {
            $marge = 'margin-left  : 2px;
							  margin-right : 2px';
            $espaceVersion = '';
        }

        //r�cup�ration des affaires associ�es � la session
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        //D�but du document : insertion du css
        $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 16px;
							' . $marge . '
						}
					th
						{
							font-weight 	 : bold;
							text-align 		 : left;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
						}
					td
						{
							color 		: ' . COULEUR_TEXTE1 . ';
							text-align 	: center;
						}
					hr
						{
							color 			 : ' . COULEUR_TEXTE1 . ';
							background-color : ' . COULEUR_TEXTE1 . ';
							height 			 : 1px;
						}
					h1
						{
							font-weight 	 : bold;
							font-family 	 : arial;
							font-size 		 : 18px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							text-align 		 : center;
							margin-top 		 : 0px;
							' . $marge . '
						}
					h2
						{
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	:12px;
							text-align 	:left;
							margin-bottom : 0px;
						}
					h3
						{
							font-weight : normal;
							font-size 	: 16px;
							text-align 	: left;
						}
					.carre
						{
							font-weight   : normal;
							font-family   : MT Extra;
							font-size 	  : 14px;
						}
					p
						{
							font-family   : arial;
							color 		  : ' . COULEUR_TEXTE1 . ';
							font-size 	  : 9px;
							text-align 	  : left;
							margin-top 	  : 0px;
							margin-bottom : 5px;
						}
					span
						{
							color 		   : #C0C8C7;
							vertical-align : bottom;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align 	: left;
						} 
				</style>
                                </head>
                <body>
                <div class=Section1>';


        $page1 = true; //indicateur si premi�re page ou non pour ins�rer les sauts de page en d�but sauf sur la premi�re page
        //parcours des affaires associ�es � la session
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);

            //Pour chaque affaire, r�cup�ration de la liste des participants
            $result1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire).'"
                                    AND Id_session = ' . (int) $this->Id_session);

            //r�cup�ration des formateurs
            $result2 = $db->query('SELECT f.nom, f.prenom 
						   FROM formateur AS f INNER JOIN formateur_salle AS fs ON f.Id_formateur=fs.Id_formateur 
						   WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
            $formateur['nom'] = array();
            $formateur['prenom'] = array();
            while ($ligne2 = $result2->fetchRow()) {
                array_push($formateur['nom'], $ligne2->nom);
                array_push($formateur['prenom'], $ligne2->prenom);
            }

            //cr�ation d'une page par participant
            $modeleVide = true; //indicateur pour produire un mod�le vide si il n'y a pas de participants renseign�s
            $leParticipant = ($ligne1 = $result1->fetchrow());
            //boucle pour faire une page par stagiaire ou un mod�le vide
            while ($leParticipant || $modeleVide) {
                $modeleVide = false; //passage de l'indicateur du mod�le vide � false pour ne produire qu'un seul mod�le vide par affaire
                //insertion d'un saut de page sauf pour la premi�re page
                if (!$page1) {
                    //quand on est pas sur la premi�re page, insertion d'un saut de page
                    $doc .= "<br clear=all style='page-break-before:always'>";
                } else {
                    $page1 = false; //indicateur de la premi�re page pass� � false
                }

                //tableau des informations de la formation et du participant
                $doc .= '<h1>EVALUATION STAGIAIRE DE FIN DE STAGE � A CHAUD �</h1>
						<table><tr>
								<td width="100" height="15" valign="top" align="left"><h3>Soci�t� : </h3></td>
								<td width="300" height="15" valign="top" align="left"><h3>' . $compte->nom . '</h3></td>
								<td width="100" height="15" valign="top" align="left"><h3>Date : </h3></td>
								<td width="300" height="15" valign="top" align="left"><h3>' . formatageDate($planning->dateFin) . '</h3></td>
							</tr><tr>
								<td width="100" height="10" valign="top"><h2> Nom : </h2></td>
								<td width="300" height="10" valign="top"><h2>' . $ligne1->nom . '</h2></td>
								<td width="100" height="10" valign="top"><h2> Pr�nom : </h2></td>
								<td width="300" height="10" valign="top"><h2>' . $ligne1->prenom . '</h2></td>
							</tr><tr>
								<td width="100" height="10" valign="top"><h2> T�l�phone : </h2></td>
								<td width="300" height="10" valign="top"> </td>
								<td width="100" height="10" valign="top"><h2> E-mail : </h2></td>
								<td width="300" height="10" valign="top"> </td>
							</tr><tr>
								<td width="100" height="10" valign="top"><h2> Cours : </h2></td>
								<td width="300" height="10" valign="top"><h2>' . $this->nom_session . '</h2></td>
								<td width="100" height="10" valign="top"><h2> Formateur : </h2></td>
								<td width="300" height="10" valign="top"><h2>' . $formateur['nom'][0] . ' ' . $formateur['prenom'][0];

                //affichage de tous les formateurs si il y en a plusieurs
                $k = 1;
                while ($k < count($formateur['nom'])) {
                    $doc .= ' - ' . $formateur['nom'][$k] . ' ' . $formateur['prenom'][$k];
                    ++$k;
                }
                $doc .= '</h2></td>
								</tr>
						</table>' . $espaceVersion;

                //tableau d'�valuation
                $doc .= '<table border="1" cellspacing="0" bordercolor=' . COULEUR_TAB1 . '><tr>
					<th  width="160" height="10">Votre Appr�ciation</th>
					<th  width="110" height="10" align="center"><h1><img src="document_files\tbon.jpg" width="30" height="30"></h1></th>
					<th  width="110" height="10" align="center"><h1><img src="document_files\bon.jpg" width="30" height="30"></h1></td>
					<th  width="110" height="10" align="center"><h1><img src="document_files\passable.jpg" width="30" height="30"></h1></th>
					<th  width="110" height="10" align="center"><h1><img src="document_files\mauvais.jpg" width="30" height="30"></h1></th>
				</tr><tr>
					<td  width="200" height="10"></td>
					<td  width="110" height="10">Tr�s bon</td>
					<td  width="110" height="10">Bon</td>
					<td  width="110" height="10">Passable</td>
					<td  width="110" height="10">Mauvais</td>
				</tr><tr>
					<th colspan="5" width="600" height="10">L\'organisation de la formation</th>
				</tr><tr>
					<td width="200" height="5"><h2>L\'acc�s au centre de formation</h2></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="5"><h2>L\'accueil</h2></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="5"><h2>La salle de formation</h2></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="5"><h2>Le mat�riel mis � disposition</h2></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="5"><h2>Le nombre de participants</h2></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
					<td width="110" height="5"><span class="carre"></span></td>
				</tr><tr>
					<th colspan="5" width="600" height="10">Le contenu du stage</th>
				</tr><tr>
					<td width="200" height="10"><h2>Le support de cours</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>La partie th�orique</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>La partie pratique</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>La dur�e des exercices</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>La dur�e du stage</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>Le niveau technique</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<th  colspan="5" width="600" height="10">Le formateur</th>
				</tr><tr>
					<td width="200" height="10"><h2>Connaissance du sujet</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>P�dagogie</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>Respect des horaires</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<td width="200" height="10"><h2>Votre �valuation globale</h2></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
					<td width="110" height="10"><span class="carre"></span></td>
				</tr><tr>
					<th colspan="5" width="600" height="3"> </th>
				</tr><tr>
					<td colspan="5" width="600" height="10"><h2>Recommanderiez-vous ce stage ? ' . YES . ' <span class="carre">B</span> ' . NO . ' <span class="carre">B</span> *POURQUOI ?</h2></td>
						<span>&nbsp;___________________________________________________________________
						<br/>&nbsp;___________________________________________________________________</span></td>
				</tr><tr>
					<td colspan="5" width="600" height="10"><h2>Quelles autres formations aimeriez-vous suivre ?</h2>
					 - <span>________________________</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <span>________________________</span><br/>
					 - <span>________________________</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <span>________________________</span></td>
				</tr><tr>
					<td  colspan="5" width="600" height="10"><h2>Avez-vous d\'autres remarques � faire ?</h2>
						<span>&nbsp;___________________________________________________________________
						<br/>&nbsp;___________________________________________________________________</span></td>
				</tr><tr>
					<th colspan="5" width="600" height="3"> </th>
				</tr> </table>
				<p>La loi N� 78-77 du 6 janvier 1978 relative � l�informatique, aux Fichiers et aux Libert�s s�applique aux r�ponses 
					faites � ces �crans. Elle garantit un droit d�acc�s et de rectification pour les donn�es vous concernant.</p>';


                //passage au participant suivant
                $leParticipant = ($ligne1 = $result1->fetchrow());
            }
        }
        $doc .= '</div>
                </body>
                </html>';

        $entete = fopen(ENTETE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $header .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen(SMILEY_MAUVAIS, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $mauvais .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen(SMILEY_PASSABLE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $passable .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen(SMILEY_BON, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $bon .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen(SMILEY_T_BON, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $tbon .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($doc) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($header) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($logo) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($pied) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\mauvais.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($mauvais) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\passable.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($passable) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bon.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($bon) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\tbon.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($tbon) . "\n\n";
        $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

        $fichier = fopen($nomDossier . "/Evaluation_Stagiaire.doc", 'wb');
        fwrite($fichier, $data);
        fclose($fichier);
    }

    /**
     * Edition des feuilles d'�valuations de la formation par le ou les formateurs
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionEvaluationFormateur($nomDossier) {

        //cr�ation de l'instance de planning pour r�cup�rer les dates de la session
        $planning = new PlanningSession($this->Id_planning, array());

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
            $espaceVersion = '<br/> ';
        } else {
            $marge = 'margin-left  : 2px;
							  margin-right : 2px';
            $espaceVersion = '';
        }

        //r�cup�ration des nom des clients pour toutes les affaires associ�es � la session	
        $db = connecter();
        //$result = $db->query('SELECT Id_affaire, Id_compte FROM affaire WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $result = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $compte['nom'] = array();
        while ($ligne = $result->fetchRow()) {
            $societe = CompteFactory::create(null, $ligne->id_compte);
            array_push($compte['nom'], $societe->nom);
        }

        //r�cup�ration des salles utilis�es au cours de la session
        $result2 = $db->query('SELECT s.nom, s.lieu, s.ville FROM salle AS s INNER JOIN formateur_salle AS fs ON s.Id_salle=fs.Id_salle 
					 WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $salle['nom'] = array();
        $salle['lieu'] = array();
        $salle['ville'] = array();

        while ($ligne2 = $result2->fetchRow()) {
            array_push($salle['nom'], $ligne2->nom);
            array_push($salle['lieu'], $ligne2->lieu);
            array_push($salle['ville'], $ligne2->ville);
        }

        //D�but du document : insertion du css
        $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
							' . $marge . '
						}
					th
						{
							font-weight 	 : bold;
							text-align 		 : center;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
						}
					td
						{
							color   	: ' . COULEUR_TEXTE1 . ';
							text-align  : center;
						}
					h1
						{
							font-weight 	 : bold;
							font-family 	 : arial;
							font-size 		 : 20px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							text-align 		 : center;
							margin-top 		 : 0px;
							' . $marge . '
						}
					h2
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 12px;
							text-align 	: left;
							font-weight : normal;
						}
					h3
						{
							font-family   : arial;
							color 		  : ' . COULEUR_TEXTE1 . ';
							font-size 	  : 16px;
							text-align 	  : center;
							font-weight   : bold;
							margin-top	  : 5px;
							margin-bottom : 5px;
						}
					.carre
						{
							font-weight   : normal;
							font-family   : MT Extra;
							font-size 	  : 16px;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align 	: left;
						}
					span
						{
							color 		   : #C0C8C7;
							vertical-align : bottom;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

        $page1 = true; //indicateur de la premi�re page pour les insertions de sauts de page
        $modeleVide = true; //indicateur pour produire un mod�le vide si il n'y a pas de participants renseign�s
        //r�cup�ration du formateur
        $result1 = $db->query('SELECT f.nom, f.prenom, f.mail, f.tel_fixe, f.tel_portable 
						FROM formateur AS f INNER JOIN formateur_salle AS fs ON f.Id_formateur=fs.Id_formateur 
						WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $leFormateur = ($ligne1 = $result1->fetchrow());
        //boucle pour faire une page par formateur ou un mod�le vide
        while ($leFormateur || $modeleVide) {
            $modeleVide = false; //passage de l'indicateur du mod�le vide � false pour ne produire qu'un seul mod�le vide par page
            //insertion d'un saut de page sauf pour la premi�re page
            if (!$page1) {
                $doc .= "<br clear=all style='page-break-before:always'>";
            } else {
                $page1 = false; //passage de l'indicateur de la premi�re page � false
            }

            //pour le num�ro de t�l�phone, utilisation du fixe et si non renseign�, utilisation du t�l�phone portable
            if ($ligne1->tel_fixe != '') {
                $tel = $ligne1->tel_fixe;
            } else {
                $tel = $ligne1->tel_portable;
            }

            //tableau des informations de la session et du formateur
            $doc .= '<h1>FICHE EVALUATION FORMATEUR</h1>
					<h3>Stage "' . $this->nom_session . '" </h3>
					<table><tr>
						<td width="90" height="15" valign="top"><h2> Soci�t� : </h2></td>
						<td width="210" height="15" valign="top"><h2>' . $compte['nom'][0];
            //insertion de tous les clients si il y en a plusieurs
            $k = 1;
            while ($k < count($compte['nom'])) {
                $doc .= '<br/>' . $compte['nom'][$k];
                ++$k;
            }
            $doc .= '</h2></td>
						<td width="130" height="15" valign="top"><h2> Lieu du stage : </h2></td>
						<td width="300" height="15" valign="top"><h2>' . $salle['nom'][0] . ' - ' . $salle['lieu'][0] . ' - ' . $salle['ville'][0];
            //insertion de toutes les salles, si il y en a plusieurs
            $k = 1;
            while ($k < count($salle['nom'])) {
                $doc.='<br/>' . $salle['nom'][$k] . ' - ' . $salle['lieu'][$k] . ' - ' . $salle['ville'][$k];
                ++$k;
            }
            $doc .= '</h2></td>
					</tr><tr>
						<td colspan="2" width="300" height="15" valign="top"><h2> ' . $this->nb_Inscrits . ' stagiaire(s) </h2></td>
						<td colspan="2" width="430" height="15" valign="top"><h2> Du  ' . formatageDate($planning->dateDebut) . ' au  ' .
                    formatageDate($planning->dateFin) . '  (' . $planning->nb_Jour . ' jour(s))</h2></td>
					</tr><tr>
						<td width="90" height="10" valign="top"><h2> Nom : </h2></td>
						<td width="210" height="10" valign="top"><h2>' . $ligne1->nom . '</h2></td>
						<td width="130" height="10" valign="top"><h2> Pr�nom : </h2></td>
						<td width="300" height="10" valign="top"><h2>' . $ligne1->prenom . '</h2></td>
					</tr><tr>
						<td width="90" height="10" valign="top"><h2> T�l�phone : </h2></td>
						<td width="210" height="10" valign="top"><h2>' . $tel . '</h2></td>
						<td width="130" height="10" valign="top"><h2> E-mail : </h2></td>
						<td width="300" height="10" valign="top"><h2>' . $ligne1->mail . '</h2></td>
					</tr>
				</table>' . $espaceVersion;

            //tableau d'�valuation
            $doc .= '<table border="1" cellspacing="0" bordercolor=' . COULEUR_TAB1 . '>
				<tr>
					<th colspan="6" width="900" height="10">Evaluation</th>
				</tr><tr>
					<td  colspan="6" width="300" height="10"><FONT SIZE="2" color=' . COULEUR_TEXTE1 . '> Quelle impression gardez-vous 
						de cette formation concernant chacun des points suivants ?</td>
				</tr><tr>
					<td  width="300" height="10"> </td>
					<td  width="80" height="10">Tr�s bon</td>
					<td  width="80" height="10">Bon</td>
					<td  width="80" height="10">Passable</td>
					<td  width="80" height="10">Mauvais</td>
					<td  width="200" height="10"><FONT SIZE="3" color=' . COULEUR_TEXTE1 . '>Commentaires</font></td>
				</tr><tr>
					<th colspan="6" width="900" height="10">L\'Environnement</th>
				</tr><tr>
					<td width="300" height="5"><h2> Equipement de la salle de formation</h2></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="200" height="5" align="center"> </td>
				</tr><tr>
					<td width="300" height="5"><h2> Confort des Locaux</h2></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="200" height="5" align="center"> </td>
				</tr><tr>
					<td width="300" height="5"><h2> Installation de la salle</h2></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="80" height="5" align="center"><span class="carre"></span></td>
					<td width="200" height="5" align="center"> </td>
				</tr><tr>
					<th colspan="6" width="900" height="10">La P�dagogie</th>
				</tr><tr>
					<td width="300" height="10"><h2> Le timing de la formation</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> La pertinence du contenu</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<th colspan="6" width="900" height="10">Le groupe</th>
				</tr><tr>
					<td width="300" height="10"><h2> Motivation du groupe</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> L\'ambiance du groupe</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> Le respect des pr�-requis</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> Le niveau d\'atteinte des objectifs</h2></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="80" height="10" align="center"><span class="carre"></span></td>
					<td width="200" height="10" align="center"> </td>
				</tr><tr>
					<th  colspan="6" width="900" height="10">Quels sont d\'apr�s vous ...</th>
				</tr><tr>
					<td width="300" height="10"><h2> Les th�mes � renforcer</h2></td>
					<td colspan="5" width="300" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> Les th�mes � supprimer</h2></td>
					<td colspan="5" width="300" height="10" align="center"> </td>
				</tr><tr>
					<td width="300" height="10"><h2> Les th�mes � ajouter</h2></td>
					<td colspan="5" width="300" height="10" align="center"> </td>
				</tr><tr>
					<td colspan="6" width="900" height="10"><h2>Les s�quences p�dagogiques � modifier (ordre des chapitres) : </h2></td>
				</tr><tr>
					<th colspan="6" width="900" height="10">Commentaires</th>
				</tr><tr>
					<td colspan="6" rowspan="2" width="900" height="30"><h2> Probl�mes p�dagogiques :&nbsp;&nbsp;
							<span>_________________________________________________________________
							<br/>&nbsp;&nbsp;______________________________________________________________________________________</span>
							</h2></td>
				</tr><tr height="30">
				</tr><tr>
					<td colspan="6" rowspan="2" width="900" height="30"><h2> Probl�mes techniques :&nbsp;&nbsp;&nbsp;
							<span>___________________________________________________________________
							<br/>&nbsp;&nbsp;______________________________________________________________________________________</span>
							</h2></td>
				</tr><tr height="30">
				</tr><tr>
					<td colspan="6" rowspan="2" width="900" height="30"><h2> Divers :&nbsp;&nbsp;&nbsp;
							<span>_______________________________________________________________________________
							<br/>&nbsp;&nbsp;______________________________________________________________________________________</span>
							</h2></td>
				</tr><tr height="30">
				</tr><tr>
					<th colspan="6" width="900" height="10">Merci d\'�crire au dos tous les commentaires suppl�mentaires</th>
				</tr></table>';

            //passage au formateur suivant
            $leFormateur = ($ligne1 = $result1->fetchrow());
        }
        $doc .= '</div>
                </body>
                </html>';

        $entete = fopen(ENTETE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $header .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($doc) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($header) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($logo) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($pied) . "\n\n";
        $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

        $fichier = fopen($nomDossier . "/Evaluation_Formateur.doc", 'wb');
        fwrite($fichier, $data);
        fclose($fichier);
    }

    /**
     * Edition de la lettre de confirmation d'inscription � la session
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionConfirmation($nomDossier) {
        //D�but du document : insertion du css
        $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
						}
					th
						{
							font-weight : bold;
							text-align 	: left;
						}
					h2
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: left;
							font-weight : bold;
						}
					h3
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: right;
							font-weight : normal;
						}
					p
						{
							font-family : arial;
							font-size 	: 16px;
							text-align 	: left;
						}
				</style>
                </head>
                <body>
                <div class=Section1>
            ';

        //lettre de confirmation
        $doc .= '<br/><br/><br/><h3>Carquefou, le ' . DATEFR . '</h3><br/> 
				<h2>Objet : Confirmation d\'inscription</h2><br/><br/><br/>
				<p>Madame, Monsieur,</p>
				<p>Nous avons bien enregistr� votre commande de formation, pour le stage : 
					<b>"' . $this->nom_session . '"</b>, et nous vous en remercions.</p>
				<p>Vous trouverez ci-jointes les convocations nominatives � destination des participants(es).<br/><br/>
				PROSERVIA d�livrera aux stagiaires, � la fin de la session, une attestation de stage nominative.<br/><br/>
				La feuille de pr�sence ainsi que la facture tenant lieu de convention simplifi�e vous seront adress�es � l\'issue 
					de la formation.</p><br/>
				<p>Nous restons � votre disposition pour tout compl�ment d\'information et vous prions d\'agr�er, Madame, Monsieur, 
					l\'expression de nos meilleures salutations.</p><br/><br/>
				<div align="right">
					<table><tr>
						<th>Ludivine CRIAUD</th>
					</tr><tr>
						<td>Consultante - Formatrice</td>
					</tr></table>
				 </div>
				<br/><br/><br/><br/><br/><br/>
                                </div>
                </body>
                </html>';

        //�criture dans le fichier et fermeture du fichier
        $entete = fopen(ENTETE, "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $header .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
        while (!feof($entete)) { //on parcourt toutes les lignes
            $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
        }
        fclose($entete);

        $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($doc) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
        $data .= base64_encode($header) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($logo) . "\n\n";
        $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
        $data .= base64_encode($pied) . "\n\n";
        $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

        $fichier = fopen($nomDossier . '/Courrier_confirmation.doc', 'wb');
        fwrite($fichier, $data);
        fclose($fichier);
    }

    /**
     * Cr�ation liens mailto pour envoie de mails de confirmation d'inscription pr�remplis
     * 
     * @return string liste des lien � afficher
     *
     */
    public function editionConfirmation2() {
        //sujet du mail
        $sujet = 'Confirmation d\'inscription formation PROSERVIA';

        //texte du mail, attention mise en page importante pour la mise en page dans le mail 
        $texte = 'Carquefou, le ' . DATEFR . '
	
	Madame, Monsieur,
	
	Nous avons bien enregistr� votre commande de formation, pour le stage : "' . $this->nom_session . '", et nous vous en remercions.
	Vous trouverez ci-jointes les convocations nominatives � destination des participants(es).
	
	PROSERVIA d�livrera aux stagiaires, � la fin de la session, une attestation de stage nominative.
	
	La feuille de pr�sence ainsi que la facture tenant lieu de convention simplifi�e vous seront adress�es � l\'issue de la formation.
	Nous restons � votre disposition pour tout compl�ment d\'information et vous prions d\'agr�er, Madame, Monsieur, l\'expression de 
	nos meilleures salutations.
	
	
	Marie-Laure LEVALET 
	Assistante Administrative 
	marielaure.levalet@proservia.fr
	T�l : 02 28 01 52 52 - Fax : 02 28 01 52 50
	www.groupe-proservia.fr

	
	
PROSERVIA Si�ge : 
Atlanpole La Fleuriaye - 1, rue Augustin Fresnel 
BP 20701 44 481 CARQUEFOU CEDEX
Afin de contribuer au respect de l\'environnement, merci de n\'imprimer ce courriel que si n�cessaire.';

        //encodage du texte pour conserver les accents
        $texte = rawurlencode($texte);

        //r�cup�ration des affaires associ�es � la session
        $db = connecter();
        $result = $db->query('SELECT Id_affaire, Id_compte, Id_contact1 FROM affaire WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $doc = '';
        //pour chaque affaire, cr�ation du lien mailto affich� dans la session avec toutes les informations du mail 
        while ($ligne = $result->fetchRow()) {
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $doc .='<a href="mailto:?subject=' . $sujet . '&body=' . $texte . '">Mail de confirmation pour ' . $compte->nom . '</a>
					<br/>';
        }
        return $doc;
    }

    /**
     * Edition des convocations � la session
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionConvocation($nomDossier) {

        $planning = new PlanningSession($this->Id_planning, array());
        $nbPage = count($this->salle);
        if ($nbPage == 0)
            $nbPage = 1;

        //r�cup�ration des affaires associ�es � la session
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $espaceVersion = ' ';
        } else {
            $espaceVersion = '<br/>';
        }

        //parcours des affaires
        while ($ligne = $result->fetchRow()) {
            //pour chaque affaire cr�ation d'un fichier word avec le nom du compte dans le nom du fichier
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $nomCompte = str_replace("'", "", $compte->nom);
            $nomCompte = htmlscperso(formatageString($nomCompte), ENT_QUOTES);

            //D�but du document : insertion du css
            $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
						}
					th
						{
							font-weight : normal;
							text-align 	: right;
						}
					td
						{
							text-align : center;
						}
					h1
						{
							font-family : arial;
							font-size 	: 22px;
							color 		: ' . COULEUR_TAB1 . ';
							text-align 	: center;
						}
					h2
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: center;
							font-weight : bold;
						}
					h3
						{
							font-family : arial;
							font-size 	: 15px;
							text-align 	: center;
							color 		: ' . COULEUR_TAB1 . ';
							font-weight : bold;
						}
					h4
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: right;
							font-weight : bold;
						}
					h5
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: center;
							font-weight : bold;
						}
					p
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: justify;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align  : left;
						}
					</style>
                                        </head>
                <body>
                <div class=Section1>';

            //Pour chaque affaire, r�cup�ration de la liste des participants
            $result1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire).'"
                                    AND Id_session = ' . (int) $this->Id_session);
            $page1 = true; //indicateur si premi�re page ou non pour ins�rer les sauts de page en d�but sauf sur la premi�re page
            //cr�ation d'une page par participant
            $modeleVide = true; //indicateur pour produire un mod�le vide si il n'y a pas de participants renseign�s
            $leParticipant = ($ligne1 = $result1->fetchrow());
            //boucle pour faire une page par participant ou un mod�le vide si il n'y a pas de participant renseign�
            while ($leParticipant || $modeleVide) {
                $modeleVide = false; //passage de l'indicateur du mod�le vide � false pour ne produire qu'un seul mod�le vide par page
                $page = 0;
                while ($page < $nbPage) {
                    $salle = new Salle($this->salle[$page], array());
                    $modeleVide2 = true;

                    if (($salle->nom_salle != '') || $modeleVide2) {
                        $modeleVide = false;
                        //insertion d'un saut de page sauf pour la premi�re page
                        if (!$page1) {
                            $doc .= "<br clear=all style='page-break-before:always'>";
                        } else {
                            $page1 = false; //passage de l'indicateur de la premi�re page � false
                        }

                        //coordonn�es du participant dans l'entreprise
                        $doc .= '<div align="center">
								<table><tr>
									<td rowspan="7" width="200" height="90"><table border=1 cellspacing=0><tr><td><h1>CONVOCATION</h1></td>
												</tr></table></td>	
								</tr><tr>
									<th width="300" height="15">' . $compte->nom . '</th>
								</tr><tr>
									<th width="300" height="15">' . $ligne1->nom . ' ' . $ligne1->prenom . '</th>
								</tr><tr>
									<th width="300" height="15">' . $compte->adresse . '</th>
								</tr><tr>
									<th width="300" height="15">' . $compte->code_postal . ' ' . $compte->ville . '</th>
								</tr><tr>
									<th width="300" height="15"> </td>
								</tr><tr>
									<th width="300" height="15">Carquefou, le ' . DATEFR . '</th>
								</tr></table>
							</div>';

                        //lettre de convocation
                        $doc .= '<br/>
							<p>Madame, Monsieur,</p>
							<p>Nous vous confirmons votre inscription au stage :</p>
							<h2>"' . $this->nom_session . '"</h2><br/>
							<p>Nous aurons le plaisir de vous accueillir :</p>
							<div align="center">
							<table border=1 cellspacing=0>
								<tr>
									<td colspan="2" width="550" height="20"><h5>D�roulement de la formation</h5></td>
								</tr><tr>
									<td width="150" height="50"><h5>Lieu</h5></td>
									<td width="400" height="50"> Salle : ' . $salle->nom_salle . '<br/>' . $salle->lieu . ', ' .
                                $salle->adresse . '<br/>' . $salle->code_postal . ' ' . $salle->ville . ' </td>
								</tr><tr>
									<td width="150" height="20"><h5>Dur�e</h5></td>
									<td width="400" height="20"> ' . $planning->nb_Jour . ' jour(s) : du ' . formatageDate($planning->dateDebut)
                                . ' au ' . formatageDate($planning->dateFin) . ' </td>
								</tr><tr>
									<td width="150" height="20"><h5>Horaires des cours</h5></td>
									<td width="400" height="20"> 9h00 � 12h30 et 14h00 � 17h30 </td>
								</tr>
							</table></div><br/>
							<h3>Attention le premier jour de la formation commence � 9h30</h3>
							<p><font color=' . COULEUR_TEXTE1 . '><b>Pour vous rendre sur le lieu de la formation :</b>
								</font> Consultez le descriptif et le plan d\'acc�s joints � cette convocation.</p>
							<p><font color=' . COULEUR_TEXTE1 . '><b>Pour l\'h�bergement et la restauration :</b>
								</font> notre centre d\'accueil est � proximit� de diverses formules d\'h�tels et de restaurants, 
								pour lesquelles vous n\'aurez pas besoin de v�hicule.</p>
							<p>Dans l\'attente de vous accueillir, nous restons � votre disposition pour tous compl�ments d\'informations 
								et vous prions de croire, Madame, Monsieur, en l\'expression de nos meilleures salutations.</p>
							<h4>L\'�quipe Formation.</h4>';
                    }
                    ++$page;
                }
                //passage au participant suivant
                $leParticipant = ($ligne1 = $result1->fetchrow());
            }
            $doc .= '</div>
                </body>
                </html>';


            $entete = fopen(ENTETE, "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $header .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($doc) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($header) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($logo) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($pied) . "\n\n";
            $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

            $fichier = fopen($nomDossier . '/Convocation_' . $nomCompte . '.doc', 'wb');
            fwrite($fichier, $data);
            fclose($fichier);
        }
    }

    /**
     * Edition des conventions de formation
     *
     * @param string nom du dossier correspondant � la session
     *
     */
    public function editionConvention($nomDossier) {
        //cr�ation de l'instance de planning pour r�cup�rer la liste des dates de la session
        $planning = new PlanningSession($this->Id_planning, array());
        $date = explode('-', $planning->dateDebut);
        $dates = array();
        $dates = $planning->listeDates();
        $nbJour = count($dates['jour']);

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $espaceVersion = ' ';
        } else {
            $espaceVersion = '<br/>';
        }

        //r�cup�ration des salles utilis�es au cours de la session
        $db = connecter();
        $result2 = $db->query('SELECT s.nom, s.lieu, s.ville, s.adresse, s.code_postal
						FROM salle AS s INNER JOIN formateur_salle AS fs ON s.Id_salle=fs.Id_salle 
						WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $salle['nom'] = array();
        $salle['lieu'] = array();
        $salle['ville'] = array();
        $salle['adresse'] = array();
        $salle['cp'] = array();
        while ($ligne2 = $result2->fetchRow()) {
            array_push($salle['nom'], $ligne2->nom);
            array_push($salle['lieu'], $ligne2->lieu);
            array_push($salle['ville'], $ligne2->ville);
            array_push($salle['adresse'], $ligne2->adresse);
            array_push($salle['cp'], $ligne2->code_postal);
        }

        //r�cup�ration des affaires associ�es � la session avec le nombre d'inscrits et le ca pour ces affaires	
        /*$result = $db->query('SELECT a.Id_affaire, a.Id_compte, a.Id_contact1, p.chiffre_affaire, pf.nb_inscrit 
					FROM affaire AS a INNER JOIN proposition AS p ON a.Id_affaire=p.Id_affaire 
									  INNER JOIN proposition_formation AS pf ON p.Id_proposition =pf.Id_proposition 
					WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));*/
        
        $result = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
      
        //parcours des affaires
        while ($ligne = $result->fetchRow()) {
            $op = new Opportunite($ligne->id_affaire);
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $contact1 = ContactFactory::create(null, $op->Id_contact1);
            
            $ligneI = $db->query('SELECT COUNT(nom) AS nb_inscrit
                                    FROM participant WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session) . '
                                    AND Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"')->fetchRow();

            /* cr�ation de la r�f�rence de la convention : les 3 premi�re lettre client, puis le mois et l'ann�e (sur deux chiffres) 
              de la date de d�but de la session et No1 */
            $nomFichier = str_replace("'", "", $compte->nom);
            $nomFichier = htmlscperso(formatageString($nomFichier), ENT_QUOTES);
            $date[0] = substr($date[0], 2);
            $nom = substr(strtoupper($nomFichier), 0, 3);
            $ref = $nom . $date[1] . $date[0] . 'N01';

            //cr�ation du fichier : un par affaire
            //D�but du document : insertion du css
            $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
						}
					th
						{
							font-weight 	 : normal;
							text-align 		 : center;
							vertical-align 	 : middle;
							font-size 		 : 14px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
						}
					h1
						{
							font-family 	: arial;
							vertical-align 	: middle;
							font-size 		: 28px;
							color 			: ' . COULEUR_TEXTE2 . ';
							text-align 		: center; 
						}
					h2
						{
							font-family : arial;
							font-size 	: 22px;
							font-weight : normal;
							text-align 	: center;
							color 		: ' . COULEUR_TAB1 . ';
						}
					h3
						{
							font-family   : arial;
							font-size 	  : 14px;
							text-align 	  : left;
							font-weight   : bold;
							margin-top 	  : 20px;
							margin-bottom : 10px;
						}
					h4
						{
							font-family : arial;
							font-size  	: 14px;
							text-align 	: left;
							font-weight : normal;
						}
					h5
						{
							font-family   : arial;
							font-size 	  : 16px;
							text-align    : center;
							font-weight   : bold;
							margin-bottom : 2px;
						}
					.tx
						{
							font-family : arial;
							font-size  	: 14px;
							text-align  : center;
							font-weight : normal;
							margin-top  : 0px;
						}
					h6
						{
							font-family : arial;
							font-size 	: 12px;
							text-align 	: left;
							font-weight : normal;
						}
					p
						{
							font-family   : arial;
							font-size 	  : 14px;
							text-align 	  : justify;
							margin-top 	  : 5px;
							margin-bottom : 10px;
						}
					.marge
						{
							margin-left	 : 129px;
							font-family  : arial;
							font-size 	 : 14px;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align 	: left;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

            //Page 1 de la convention
            //titre de la convention
            $doc .= '<div align="center"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
							<table border=1 cellspacing=0 bgcolor=' . COULEUR_TAB1 . '><tr>
								<td width="600" height="60"><h1>CONVENTION DE FORMATION</h1></td>
							</tr></table><br/><br/>
							<table border=0>
								<tr>
									<td width="600" height="100">
										<h2>CONVENTION n�' . $ref . '</h2>
									</td>
								</tr>
								<tr>
									<td width="600" height="300"> </td>
								</tr>
							</table>
						</div><br/><br/>';

            //insertion d'un saut de page
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 2 de la convention
            //affichage des informations du client et de Proservia
            $doc .= '<div align="center"><br><br/>
							<table border=1 cellspacing=0 ><tr>
								<th width="600" height="50">CONDITIONS GENERALES <br/> DE FORMATION</th>
							</tr></table><br/><br/><br/>
					</div>
						<p>Convention conclue en application de l�article L920-1 du Code  du travail </p><br/>
						<p>entre :</p>
						<p>' . $compte->nom . '</p>
						<p>SIEGE SOCIAL : ' . $compte->adresse . '</p>
						<p>' . $compte->code_postal . ' ' . $compte->ville . '</p><br/>
						<p>Repr�sent� par : ' . $contact1->prenom . ' ' . $contact1->nom . '</p><br/>
						<p>Ci-apr�s d�sign�e : "LE CLIENT"</p>
						<p>D\'une part</p><br/>
						<p>et</p>
						<p>Soci�t� PROSERVIA</p>
						<p>S.A.S au capital de 821 682 50 Euros</p>
						<p>Si�ge : 1, rue Augustin Fresnel - BP 20701</p>
						<p>44481 CARQUEFOU Cedex<br/></p><br/>
						<p>Repr�sent� par Monsieur Alain ROUMILHAC, Pr�sident</p><br/>
						<p>Ci-apr�s d�sign�e : "LE PRESTATAIRE"</p>
						<p>d\'autre part,</p><br/>
						<p>Ci-apr�s d�sign�es collectivement par "les parties" ou individuellement par "la partie".</p>';

            //insertion d'un saut de page
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 3 de la convention
            //texte de la convention
            $doc .= '	<h3>CLAUSES GENERALES</h3>
						<p>Il est expos� et convenu ce qui suit :</p>
						<p>L\'objet de la pr�sente convention est de fixer les obligations et responsabilit�s de
							PROSERVIA et du CLIENT dans leurs relations contractuelles.</p>
						<h3>ARTICLE 1 - OBJET</h3> 
						<p>Cette convention a pour objet la fourniture, par PROSERVIA � la demande du CLIENT,
							d\'une prestation de formation en ' . $op->type_opportunite . '.</p>
						<h6>INTITULE DE LA FORMATION :</h6>
						<h5>' . $this->nom_session . '</h5><br/>
						<p>Il est express�ment pr�cis�, que toute t�che n\'entrant pas dans le cadre ci-dessus, fera l\'objet
							d\'un avenant qui sera n�goci� s�par�ment.</p>
						<p>Cette convention est exclusive de toute notion de personnel dit int�rimaire.</p>
						<p>Le CLIENT ne peut, sans accord �crit de PROSERVIA, c�der le b�n�fice de la pr�sente convention.</p>
						<h3>ARTICLE 2 - DUREE ET MODALITES DE REALISATION</h3>
						<p>La convention sera conclue pour une p�riode de ' . $planning->nb_Jour . ' jour(s) de formation entre ' .
                    $compte->nom . ' et PROSERVIA, pour une p�riode d�finie comme suit : </p><br/>';
            //insertion de la liste des dates de la session
            $j = 0;
            $i = 0;
            //si il y a moins de 10 dates, la liste peut tenir sur une colonne
            if ($nbJour < 11) {
                $doc .= '<table>';
                while ($j < $nbJour) {
                    $doc .= '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;- le ' . $dates['jour'][$j] . '-' . $dates['mois'][$j] . '-' . $dates['annee'][$j]
                            . '</td></tr>';
                    ++$j;
                    ++$i;
                }
                $doc .= '</table>';
            } else { //si il y a plus de 10 dates, pr�sentation de la liste sur deux colonnes
                $doc .= '<table>';
                $j = 0;
                while ($j < $nbJour) {
                    //si le compteur est pair, d�but de la ligne
                    if (!($j % 2)) {
                        $doc .= '<tr>';
                    }
                    $doc .= '<td width="200">&nbsp;&nbsp;&nbsp;&nbsp;- le ' . $dates['jour'][$j] . '-' . $dates['mois'][$j] . '-' .
                            $dates['annee'][$j] . '</td>';
                    //si le compteur est impair, fin de la ligne
                    if ($j % 2) {
                        $doc .= '</tr>';
                        ++$i;
                    }
                    ++$j;
                }
                if ($j % 2) {
                    $doc .= '</tr>';
                    ++$i;
                }
                $doc .= '</table>';
            }

            //liste des salles o� se d�roule la session
            $doc .= '<br/>
					<p>Lieu d\'ex�cution :&nbsp;&nbsp;-&nbsp;' . $salle['nom'][0] . ', ' . $salle['lieu'][0] . '</p>' .
                    '<div class="marge">' . $salle['adresse'][0] . ' - ' . $salle['cp'][0] . ' ' . $salle['ville'][0] . '</div>';
            $k = 1;
            while ($k < count($salle['nom'])) {
                $doc .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;' .
                        $salle['nom'][$k] . ', ' . $salle['lieu'][$k] . '</p>' .
                        '<div class="marge">' . $salle['adresse'][$k] . ' - ' . $salle['cp'][0] . ' ' . $salle['ville'][$k] . '</div>';
                ++$k;
            }

            //ajout des espaces selon le nombre de dates et de salles affich�es
            if ($i < 10) {
                $doc .= '<br/><br/>';
                while ($i < 9) {
                    $doc .= '<br/>';
                    ++$i;
                }
            }
            if ($k == 1) {
                $doc .= '<br/>';
            }

            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 4 de la convention

            $cl = new ChiffreEnLettre();
            $euros = (int) $op->ca;
            $centimes = (int) (($op->ca - $euros) * 100);
            if ($centimes) {
                $texteCentimes = ' et ' . $cl->conversion($centimes) . ' centimes';
            }

            //texte de la convention
            $doc .= '	<br/><br/><br/><h3>ARTICLE 3 - CONDITIONS FINANCIERES</h3>
						<p>Le montant total de la formation s\'�l�ve � :</p>
						<h5>' . $op->ca . ' Euros HT</h5>
						<div class="tx">(soit ' . $cl->conversion($euros) . ' euros' . $texteCentimes . ' hors taxe)</div>
						<p>Ce tarif comprend les moyens mis en �uvre pour la bonne ex�cution de la formation assur�e
							par notre formateur.</p>
						<h3>ARTICLE 4 - PAIEMENT</h3>
						<p>Nos prix sont fermes et d�finitifs. Nos prix sont �tablis hors taxes, 
						ils sont calcul�s d�part agence. Tout changement de TVA entra�nera automatiquement le r�ajustement des prix TTC.</p>
						<h3>ARTICLE 5 - CONDITIONS DE REGLEMENT</h3>
						<p>Si la formation est r�alis�e en coop�ration avec notre partenaire VASCOO, 
						le prix est payable dans un d�lai de 30 jours � compter du lendemain du dernier jour de la formation ou 
						du cours dispens�, sous r�serve de l�acceptation du dossier de la soci�t� cliente par la soci�t� 
						d\'assurance cr�dit de notre partenaire. En cas de refus, le r�glement est demand� lors du premier jour de cours.</p>
						<p>Si la formation est uniquement r�alis�e par le Groupe PROSERVIA, le paiement se fera � r�ception de 
						la facture et sans escompte, sauf accord commercial contraire (� pr�ciser sur votre bon de commande).</p>
						<h3>ARTICLE 6 - DELAIS DE PAIEMENT</h3>
						<p>En cas de retard de paiement, les sommes restant dues porteront int�r�t de 
						plein doit et sans qu\'une mise en demeure soit n�cessaire, au taux �gal au taux d�int�r�t 
						appliqu� par la Banque Centrale Europ�enne � son op�ration de refinancement 
						la plus r�cente (soit 1% pour 2010), major� de 10 points de pourcentage.</p><br /><br /><br />
						<br /><br /><br /><br /><br /><br /><br /><br />';


            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 6 de la convention

            $doc .= '<br/><br/><h3>ARTICLE 7 - ANNULATION - REMPLACEMENT</h3>
						<p>D�une fa�on g�n�rale, toute annulation d\'inscription de la part du client doit �tre signal�e et confirm�e par �crit.</p>
						<p>Pour les formations linguistiques, les modalit�s d�annulation sont les suivantes : </p>
						<p>&nbsp;&nbsp;&nbsp;- Une annulation intervenant plus de 48 Heures avant le d�but du cours ne donne lieu � aucune facturation.</p>
						<p>&nbsp;&nbsp;&nbsp;- Une annulation intervenant moins de 48 Heures avant le d�but du cours donne lieu � une facturation du montant int�gral du cours.</p>
						<p>Pour les autres formations, les modalit�s d�annulation sont les suivantes : </p>
						<p>&nbsp;&nbsp;&nbsp;- Une annulation intervenant plus de deux semaines avant le d�but du cours ne donne lieu � aucune facturation.</p>
						<p>&nbsp;&nbsp;&nbsp;- Une annulation intervenant entre une et deux semaines avant le d�but du cours donne lieu � une facturation �gale � 30% du montant du cours.</p>
						<p>&nbsp;&nbsp;&nbsp;- Une annulation intervenant moins d\'une semaine avant le d�but du cours donne lieu � une facturation du montant int�gral du cours.</p>
						<p>Pour sa part, PROSERVIA se r�serve la possibilit� d\'annuler un stage dans les situations suivantes : 
						en cas d�insuffisance du  nombre d�inscription, de probl�me d\'approvisionnement de supports de cours, 
						de probl�mes techniques  (dans ce cas, les stagiaires inscrits seront pr�venus au moins une semaine 
						avant le d�but du stage) ou concernant directement l�intervenant, et ce sans d�dommagement.</p>
						<p>Aucun cours ne peut �tre annul� moins de 10 jours avant son d�but.</p>
                        <p>PROSERVIA et le Client sont lib�r�s en cas de force majeure.</p>
						<p>Les remplacements de participants sont admis � tout moment, sans frais, sous r�serve d�en informer 
						par �crit PROSERVIA et de lui transmettre par �crit les noms et coordonn�es des 
						rempla�ants au plus tard  4 jours ouvr�s avant le d�but de la formation.
						</p><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';


            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 6 de la convention
            $doc .= '<br/><br/><h3>ARTICLE 8 - PROPRIETE INTELLECTUELLE</h3>
						<p>L\'utilisation des documents remis lors des cours est soumise aux articles L122-1 et suivants du 
						Code de la Propri�t� Intellectuelle: "toute repr�sentation ou reproduction int�grale ou partielle 
						faite sans le consentement de l\'auteur ou de ses ayants droits ou ayants cause est illicite". 
						Les "copies ou reproductions strictement r�serv�es � l\'usage priv� du copiste et non destin�es 
						� une utilisation collective" et "les analyses et courtes citations, sous r�serve que soient 
						indiqu�s clairement le nom de l\'auteur et la source".</p>
						<p>Par cons�quent,  le Client s\'interdit de reproduire, directement ou indirectement, en totalit� ou en partie, 
						d\'adapter, de modifier, de traduire, de repr�senter, de commercialiser ou de diffuser � des membres 
						de son personnel non participants aux formations organis�es ou dispens�es par PROSERVIA, 
						ou � des tiers, les supports de cours et ressources p�dagogiques mis � la seule disposition 
						des participants, sans l\'autorisation expresse, pr�alable et �crite de PROSERVIA ou de ses ayants droit.</p>
						<p>Les savoir faire et donn�es de PROSERVIA, du formateur ou du Client restent leur propri�t� respective.</p>
						<p>PROSERVIA reste libre de fournir pour elle-m�me ou pour des tiers des services 
						identiques ou similaires � ceux fournis au Client.</p>
						<h3>ARTICLE 9 - RESPONSABILITES</h3>
						<p>PROSERVIA ne pourra en aucun cas �tre d�clar�e responsable d\'un pr�judice financier, 
						commercial ou d\'une autre nature, caus� directement ou indirectement par les prestations fournies.</p>
						<p>PROSERVIA se r�serve le droit sans indemnit� quelconque :</p>
                        <p>&nbsp;&nbsp;&nbsp;- De refuser toute inscription de la part d�un Client qui ne serait pas � jour de ses paiements</p>
                        <p>&nbsp;&nbsp;&nbsp;- D�exclure � tout moment tout participant dont le comportement g�nerait le bon d�roulement de la formation et ne respecterait pas les dispositions des pr�sentes CGV</p>
                        <p>&nbsp;&nbsp;&nbsp;- D�exclure tout participant qui aurait proc�d� � de fausses d�clarations lors de l�inscription et ce, sans indemnit�</p>
                        <h3>ARTICLE 10 - CONVENTION DE NON SOLLICITATION DU PERSONNEL</h3>
						<p>Sans accord pr�alable et �crit de PROSERVIA, le Client renonce � engager ou � faire travailler 
						directement ou par personne interpos�e tout formateur de PROSERVIA quelle que soit sa sp�cialisation, 
						et m�me si la sollicitation initiale est formul�e par ce dernier. Toute r�mun�ration occulte est 
						�galement interdite.</p>
						<p>Cette renonciation est valable � compter de la derni�re formation dispens�e 
						augment�e d\'une dur�e minimum de 12 mois.
						</p><br /><br /><br />';

            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 6 de la convention

            $doc .= '<br/><br/>
						<h3>ARTICLE 11 - CERTIFICATION</h3>
						<p>Les modalit�s et d�lais de certification sont donn�s � titre indicatif. 
						PROSERVIA ne pourra �tre tenue responsable de modifications d\'�ch�ances ou de 
						versions impos�es par les �diteurs dont d�pendent les certifications.
						</p>
						<h3>ARTICLE 12 - ATTRIBUTION DE JURIDICTION</h3>
						<p>Les pr�sentes Conditions G�n�rales de Vente sont soumises au droit fran�ais.</p>
						<p>L\'�lection de domicile est faite par le site vendeur � son agence commerciale.</p>
						<p>En cas de contestation relative au pr�sent contrat, le Tribunal de Commerce local sera comp�tent.</p>
						<p>Le fait de passer commande entra�ne l\'acceptation des pr�sentes conditions g�n�rales de vente.</p>
						<h3>ARTICLE 13 - REMARQUES</h3>
						<p>Nous insistons sur la n�cessit� de respecter les niveaux requis permettant de profiter 
						pleinement des formations dispens�es, et de respecter le rythme et de la r�gularit� de 
						l�apprentissage pr�vus au d�part afin d�obtenir les r�sultats vis�s.</p>
						<p>PROSERVIA se r�serve le droit de citer � des fins commerciales le nom de la soci�t� cliente 
						et le type de formation dispens�e.
						</p>
						<h3>ARTICLE 14 - CONFLITS D\'INTERETS</h3>
						<p>La soci�t� PROSERVIA s\'engage � prendre toutes les dispositions n�cessaires pour �viter, dans
							les relations de ses collaborateurs avec le personnel, toute action qui pourrait entra�ner 
							un conflit avec les int�r�ts du CLIENT.</p><br/><br/>
						<p>Fait � CARQUEFOU, le ' . DATEFR . '.</p><br/>
						<table><tr>
								<td width="300" height="100" valign="top" align="center">POUR LE CLIENT</td>
								<td width="300" height="100" valign="top" align="center">POUR PROSERVIA</td>
						</tr><tr>
								<td width="300" height="60"> </td>
								<td width="300" height="60" valign="top" align="center">Alain ROUMILHAC<br/>Pr�sident</td>
						</tr></table>
						<p>*Faire pr�c�der la signature de la mention "LU ET APPROUVE" et du nom du signataire, puis
							apposer le cachet de la Soci�t�.</p>';


            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 6 de la convention
            //Annexes de la convention : nom de la session et liste des participants
            $doc .= '<div align="center"><br>
							<table border=1 cellspacing=0><tr>
								<th width="600" height="40">ANNEXES</th>
							</tr></table><br/>
					</div>
					<div align="left">
						<h3>1- Description de la formation : </h3><br/>
						<p>' . $this->nom_session . '</p><br/><br/>
						<h3>2- Participants :</h3><br/>
						<p>Nombre de stagiaires : forfait �tabli pour ' . $ligneI->nb_inscrit . ' stagiaire(s).</p><br/>
						<p>Stagiaires :</p><br/>';

            //Pour chaque affaire, r�cup�ration de la liste des participants
            $result1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire).'"
                                    AND Id_session = ' . (int) $this->Id_session);

            $i = 0;
            //si il y a moins de 15 inscrit, la liste peut tenir sur une colonne
            if ($ligneI->nb_inscrit < 15) {
                $doc .= '<table>';
                //les participants
                while ($ligne1 = $result1->fetchrow()) {
                    $doc .= '<tr>
								<td width="300" height="25">- ' . $ligne1->prenom . ' ' . $ligne1->nom . '</td>
							</tr>';
                    ++$i;
                }
                //ajout de ligne vide pour aller jusqu'au pied de la page
                while ($i < 15) {
                    $doc .='<tr>
								<td width="300" height="25"> </td>
							</tr>';
                    ++$i;
                }
                $doc .= '</table>';
            } else { //si il y a plus de 15 inscrits, pr�sentation de la liste sur deux colonnes
                $doc .= '<table>';
                $j = 0;
                while ($ligne1 = $result1->fetchrow()) {
                    //si le compteur est pair, d�but de la ligne
                    if (!($j % 2)) {
                        $doc .= '<tr>';
                    }
                    $doc .='<td width="300" height="25">- ' . $ligne1->prenom . ' ' . $ligne1->nom . '</td>';
                    //si le compteur est impair, fin de la ligne
                    if ($j % 2) {
                        $doc .= '</tr>';
                        ++$i;
                    }
                    ++$j;
                }
                //ajout de ligne vide pour aller jusqu'au pied de la page
                while ($i < 15) {
                    $doc .='<tr>
								<td width="300" height="25"> </td>
								<td width="300" height="25"> </td>
							</tr>';
                    ++$i;
                }
                $doc .= '</table>';
            }
            $doc .='</div></div>
                </body>
                </html>';

            //fin de la convention
            //�criture dans le fichier et fermeture du fichier
            $entete = fopen(ENTETE, "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $header .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($doc) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($header) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($logo) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($pied) . "\n\n";
            $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

            $fichier = fopen($nomDossier . '/Convention_' . $nomFichier . '_' . $ref . '.doc', 'wb');
            fwrite($fichier, $data);
            fclose($fichier);
        }
    }

    /**
     * Edition des bons de commande
     *
     * @param string nom du dossier correspondant � la session
     * @param int indicateur du type de condition de vente � �diter dans le bon de commande
     *
     */
    public function editionBDC($nomDossier, $condition) {
        //r�cup�ration des dates du planning
        $planning = new PlanningSession($this->Id_planning, array());
        //mise en forme des dates
        //cas 1 : que des dates ponctuelles (et moins de cinq dates), affichage des dates
        if (($planning->nb_Jour < 7) && (count($planning->date) > 0) && (count($planning->periode_debut) == 0)) {
            $docDate = 'les ' . formatageDate($planning->dateDebut) . ', ';
            //pour les dates ponctuelles
            $i = 0; //indice du tableau de dates
            $nbDate = 1; //compteur du nombre de dates affich�es
            while ($i < count($planning->date)) {
                if (($planning->date[$i] != $planning->dateDebut) && ($planning->date[$i] != $planning->dateFin)) {
                    $docDate .= formatageDate($planning->date[$i]) . ', ';
                    ++$nbDate;
                }
                ++$i;
            }
            $docDate .= formatageDate($planning->dateFin) . ' ';
            //cas 2 : que des p�riodes (et moins de trois p�riodes), affichage des p�riodes
        } else if ((count($planning->periode_debut) > 0) && (count($planning->periode_debut) < 3) && (count($planning->date) == 0)) {
            $docDate = 'du ' . formatageDate($planning->periode_debut[0]) . ' au ' . formatageDate($planning->periode_fin[0]);
            $i = 1; //indice du tableau de p�riode
            while ($i < count($planning->periode_debut)) {
                $docDate .= ', du ' . formatageDate($planning->periode_debut[$i]) . ' au ' . formatageDate($planning->periode_fin[$i]);
                ++$i;
            }
            //cas 3 : sinon affichage que des dates de d�but et de fin
        } else {
            $docDate = 'du ' . formatageDate($planning->dateDebut) . ' au ' . formatageDate($planning->dateFin) . ' ';
        }

        //r�cup�ration des affaires associ�es � la session
        $db = connecter();
        $result = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session));

        //r�cup�ration des salles utilis�es au cours de la session
        $result2 = $db->query('SELECT s.nom, s.lieu, s.ville 
					 FROM salle AS s INNER JOIN formateur_salle AS fs ON s.Id_salle=fs.Id_salle 
					 WHERE fs.Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        $salle['nom'] = array();
        $salle['lieu'] = array();
        $salle['ville'] = array();
        while ($ligne2 = $result2->fetchRow()) {
            array_push($salle['nom'], $ligne2->nom);
            array_push($salle['lieu'], $ligne2->lieu);
            array_push($salle['ville'], $ligne2->ville);
        }
        $nbSalle = count($salle['nom']);

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
            $espaceVersion = ' ';
        } else {
            $marge = 'margin-left  : 2px;
							  margin-right : 2px';
            $espaceVersion = '<br/>';
        }

        //parcours des affaires
        while ($ligne = $result->fetchRow()) {
            $op = new Opportunite($ligne->id_affaire);
            $compte = CompteFactory::create(null, $ligne->id_compte);
            $contact1 = ContactFactory::create(null, $op->Id_contact1);
            $contact2 = ContactFactory::create(null, $op->Id_contact2);
            
            $ligneI = $db->query('SELECT COUNT(nom) AS nb_inscrit
                                    FROM participant WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session) . '
                                    AND Id_affaire="' . mysql_real_escape_string($ligne->id_affaire) . '"')->fetchRow();

            //cr�ation d'un fichier par affaire
            $nom = str_replace("'", "", $compte->nom);
            $nom = htmlscperso(formatageString($nom), ENT_QUOTES);

            //D�but du document : insertion du css
            $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family    : arial;
							font-size 	   : 14px;
							vertical-align :top;
							margin-top 	   : 4px;
							margin-bottom  : 4px;
							' . $marge . '
						}
					th
						{
							font-weight : normal;
							text-align  : left;
						}
					td
						{
							text-align : left;
						}
					h1
						{
							font-family 	 : arial;
							font-size 		 : 20px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							text-align 	  	 : center;
							margin-top 	  	 : 0px;
							margin-bottom 	 : 0px;
							' . $marge . '
						}
					h2
						{
							font-family : arial;
							font-size 	: 12px;
							margin-top 	: 0px;
							color 		: ' . COULEUR_TEXTE1 . ';
							text-align 	: left;
							font-weight : normal;
							font-style  : italic;
						}
					h3
						{
							font-family   : arial;
							font-size 	  : 14px;
							text-align 	  : left;
							color 		  : ' . COULEUR_TAB1 . ';
							font-weight   : bold;
							margin-bottom : 0px;
						}
					h4
						{
							font-family   : arial;
							font-size 	  : 14px;
							text-align 	  : left;
							color 		  : ' . COULEUR_TAB1 . ';
							font-weight   : bold;
							margin-top 	  : 0px;
							margin-bottom : 5px;
						}
					h5
						{
							font-family   : arial;
							font-size 	  : 13px;
							color 		  : ' . COULEUR_TAB1 . ';
							text-align    : left;
							font-weight   : bold;
							margin-top 	  : 10px;
							margin-bottom : 5px;
						}
					h6
						{
							font-family   : arial;
							font-size 	  : 12px;
							color 		  : ' . COULEUR_TAB1 . ';
							text-align 	  : left;
							margin-top 	  : 1px;
							margin-bottom : 0px;
						}
					.carre
						{
							font-weight   : normal;
							font-family   : MT Extra;
							font-size 	  : 16px;
						}
					.tx
						{
							font-family   : arial;
							font-size 	  : 10px;
							text-align 	  : right;
							margin-top 	  : 1px;
							margin-bottom : 0px;
						}
					.tx2
						{
							font-family   : arial;
							font-size 	  : 12px;
							text-align 	  : right;
							margin-top 	  : 1px;
							margin-bottom : 0px;
						}
					p
						{
							font-family   : arial;
							font-size 	  : 12px;
							color 		  : ' . COULEUR_TAB1 . ';
							text-align 	  : justify;
							margin-top 	  : 7px;
							margin-bottom : 5px;
						}
					.marge
						{
							margin-left: 129px;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 8px;
							text-align 	: left;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

            //Page 1 du bon de commande
            //titre
            $doc .= '<h1>DEVIS/BON DE COMMANDE </h1>
					<h2>Formulaire � compl�ter et retourner � l\'attention de Marie-Laure LEVALET : PROSERVIA - Atlanpole La<br/> 
						Fleuriaye -	1 rue Augustin Fresnel - BP 20701 - 44 481 CARQUEFOU CEDEX -  Par Fax : 02.28.01.52.50</h2>';

            //Informations sur la soci�t�
            $doc .= '<table><tr>
						<td colspan="2" width="600" height="10"><h3>SOCIETE</h3></td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Raison Sociale</b> : ' .
                    $compte->nom . '</td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Adresse</b> : ' . $compte->adresse . '</td>
					</tr><tr>
						<td width="300" height="15" bgcolor="' . LIGNE1 . '"> <b>Code Postal</b> : ' . $compte->code_postal . '</td>
						<td width="300" height="15" bgcolor="' . LIGNE1 . '"> <b>Ville</b> : ' . $compte->ville . ' </td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Interlocuteur</b> : ' .
                    $contact1->getName();
            if ($op->Id_contact2 != '') {
                $doc .= ' et ' . $contact2->getName();
            }
            $doc .= '</td>
					</tr><tr>
						<td width="300" height="15" bgcolor="' . LIGNE1 . '"> <b>Tel</b> : ' . $compte->tel .
                    '&nbsp;&nbsp;&nbsp;<b>Fax</b> : </td>
						<td width="300" height="15" bgcolor="' . LIGNE1 . '"> <b>E-mail</b> : ' . $contact1->mail . '</td>
					</tr></table>';

            //Informations sur la formation vendue
            if ($this->type == 2) {
                $type = ' inter-entreprise';
                $nbLigne = 5;
            } else if ($this->type == 1) {
                $type = ' intra-entreprise <span class="carre">B</div> dans nos locaux &nbsp;&nbsp; <span class="carre">B</span> dans vos locaux';
                $nbLigne = 5;
            } else {
                $type = '<span class="tx2"> <span class="carre">B</span> inter-entreprise  <span class="carre">B</span> intra-entreprise 
						<div class="marge"> <span class="carre">B</span> dans nos locaux &nbsp;<span class="carre">B</span> dans vos locaux</div></span>';
                $nbLigne = 4;
            }

            $doc .= '<table><tr>
						<td width="600" height="10"><h3>FORMATION</h3></td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Prestation</b> : ' . $this->nom_session . '</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Dates</b> : ' . $docDate . '</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Lieu</b> : ' . $salle['nom'][0] . ' - ' . $salle['lieu'][0] . ' - ' .
                    $salle['ville'][0];
            $i = 1;
            while ($i < $nbSalle) {
                $doc .= '<b> / </b>' . $salle['nom'][$i] . ' - ' . $salle['lieu'][$i] . ' - ' . $salle['ville'][$i];
                ++$i;
            }
            $doc .= '</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Nombre de participants</b> : ' . $ligneI->nb_inscrit . '</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Co�t hors-taxe du stage</b> : ' . $op->ca .
                    ' Euros</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Dur�e</b> : ' . $planning->nb_Jour . ' jour(s)</td>
					</tr><tr>
						<td width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Formule de stage</b> : ' . $type . '</td>
					</tr></table>';

            //Liste des participants
            $result1 = $db->query('SELECT nom, prenom FROM participant WHERE Id_affaire="' . mysql_real_escape_string($ligne->id_affaire).'"
                                    AND Id_session = ' . (int) $this->Id_session);
            $doc .= '<table><tr>
								<td colspan="2" width="600" height="10"><h3>PARTICIPANT(S)</h3></td>
							</tr>';
            $j = 0; //compteur de participants
            $i = 0; //compteur de lignes
            $couleur = '';
            while ($ligne1 = $result1->fetchrow()) {
                if (!($i % 2)) { //alternance des couleurs entre les lignes pairs et les lignes impairs
                    $couleur = 'bgcolor="' . LIGNE1 . '"';
                } else {
                    $couleur = 'bgcolor="' . LIGNE2 . '"';
                }
                //si le compteur est pair, d�but de la ligne
                if (!($j % 2)) {
                    $doc .= '<tr>';
                }

                $doc .= '<td width="300" height="15" ' . $couleur . '>- ' . $ligne1->prenom . ' ' . $ligne1->nom . '</td>';
                //si le compteur est impair, fin de la ligne
                if ($j % 2) {
                    $doc .= '</tr>';
                    ++$i;
                }
                ++$j;
            }
            //si le compteur est impair � la fin c'est que le nombre d'inscrit est impair : ajout de la derni�re case
            if ($j % 2) {
                $doc .='<td width="150" height="15" ' . $couleur . '>- </td></tr>';
                ++$i;
            }
            //tableau compl�t� pour inscrire 8 ou 6 participants selon si type de formation est sur 1 ou 2 lignes
            while ($i < $nbLigne) {
                if (!($i % 2)) { //alternance des couleurs entre les lignes pairs et les lignes impairs
                    $couleur = 'bgcolor="' . LIGNE1 . '"';
                } else {
                    $couleur = 'bgcolor="' . LIGNE2 . '"';
                }
                $doc .= '<tr>
							<td width="150" height="15" ' . $couleur . '>- </td>
							<td width="150" height="15" ' . $couleur . '>- </td>
						</tr>';
                ++$i;
            }
            $doc .= '</table>';

            //Informations sur la facturation
            $doc .= '<table><tr>
						<td colspan="2" width="600" height="10"><h3>ADRESSE DE FACTURATION OU ORGANISME PAYEUR</h3>
							<h6>A pr�ciser si adresse diff�rente de l�adresse de commande</h6></td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE1 . '"> <b>Organisme payeur</b> : </td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Adresse</b> : </td>
					</tr><tr>
						<td width="210" height="15" bgcolor="' . LIGNE1 . '"> <b>Code postal</b> : </td>
						<td width="390" height="15" bgcolor="' . LIGNE1 . '"> <b>Ville</b> : </td>
					</tr><tr>
						<td colspan="2" width="600" height="15" bgcolor="' . LIGNE2 . '"> <b>Siret / APE </b> : </td>
					</tr><tr>
						<td width="210" height="15" bgcolor="' . LIGNE1 . '">Le r�glement sera effectu� par : </td>
						<td width="390" height="15" bgcolor="' . LIGNE1 . '">Demandeur ? </td>
					</tr><tr>
						<td width="210" height="15" bgcolor="' . LIGNE1 . '"></td>
						<td width="390" height="15" bgcolor="' . LIGNE1 . '">Organisme collecteur ? sous r�serve de son accord</td>
					</tr><tr>
						<td width="210" height="15" bgcolor="' . LIGNE2 . '"><b>Modalit� de r�glement</b></td>
						<td width="390" height="15" bgcolor="' . LIGNE2 . '">� r�ception facture</td>
					</tr><tr>
						<td colspan="2" width="600" height="10" > </td>
					</tr><tr>
						<td colspan="2" width="600" height="20" valign="bottom"><b>J\'accepte les conditions g�n�rales de vente 
							de PROSERVIA pr�cis�es en page 2</b> :</td>
					</tr><tr>
						<td colspan="2" width="600" height="15"><font size="1">
							<b>POUR QUE LE BON DE COMMANDE SOIT VALIDE, MERCI DE <u>DATER</u> ET <u>SIGNER</u> :</b></font></td>
					</tr><tr>
						<td colspan="2" width="600" height="10" valign="bottom">Bon pour commande, Carquefou le ' . DATEFR . '</td>
					</tr><tr>
						<td width="600" height="10"></td>
						<td width="600" height="10" align="right" valign="bottom">
							<div class="tx">Signature et cachet de la soci�t�</div></td>
					</tr></table></div>';


            //insertion d'un saut de page 
            $doc .= "<br clear=all style='page-break-before:always'>";

            //Page 2 du bon de commande
            $doc .= '<h4>Conditions g�n�rales de vente</h4>';

            if ($condition == 1) {
                $doc .= '<h5>Paiement : </h5>
				<p>Nos prix sont fermes et d�finitifs. Nos prix sont �tablis hors taxes, ils sont calcul�s d�part agence. 
					Tout changement de TVA entra�nera automatiquement le r�ajustement des prix TTC.</p>
				<h5>Conditions de r�glement:</h5>
				<p>Nos prestations sont r�glables � r�ception de la facture et sans escompte, ou 15 jours, ou 30 jours, 
					suivant accord commercial (� pr�ciser sur votre bon de commande).</p> 
				<h5>D�lais de paiement:</h5>
				<p>En cas de retard de paiement, les sommes restant dues porteront int�r�t de plein doit et sans qu\'une 
					mise en demeure soit n�cessaire, au taux de 2% le mois, sans que cette clause nuise � l\'exigibilit� de 
					la dette.</p>
				<h5>Annulation :</h5>
				<p>Toute annulation d\'inscription de la part du client doit �tre signal�e et confirm�e par �crit.</p>
				<p>Une annulation intervenant plus de deux semaines avant le d�but du cours ne donne lieu � aucune facturation.</p>
				<p>Une annulation intervenant entre une et deux semaines avant le d�but du cours donne lieu � une facturation 
					�gale � 30% du montant du cours.</p>
				<p>Une annulation intervenant moins d\'une semaine avant le d�but du cours donne lieu � une facturation du montant
					int�gral du cours. </p>
				<p>Pour sa part, PROSERVIAS se r�serve la possibilit� d\'annuler un stage dans les situations suivantes : en cas de 
					nombre d\'inscrits insuffisant, de probl�me d\'approvisionnement de supports de cours, de probl�me technique  
					(dans ce cas, les stagiaires inscrits seront pr�venus au moins une semaine avant le d�but du stage) ou concernant 
					directement l�intervenant, et ce sans d�dommagement.</p> 
				<h5>Support de cours :</h5>
				<p>L\'utilisation des documents remis lors des cours est soumise aux articles 40 et 41 de la loi du 11 mars 1957: 
					"toute repr�sentation ou reproduction int�grale ou partielle faite sans le consentement de l\'auteur ou de ses 
					ayants droits ou ayants cause est illicite".</p>
				<p>L\'article 41 de la m�me loi n\'autorise que les "copies ou reproductions strictement r�serv�es � l\'usage priv� 
					du copiste et non destin�es � une utilisation collective" et "les analyses et courtes citations, sous r�serve 
					que soient indiqu�s clairement le nom de l\'auteur et la source".</p>
				<p>Toute repr�sentation ou reproduction, par quelque proc�d� que ce soit, ne respectant pas la l�gislation en 
					vigueur constituerait une contrefa�on sanctionn�e par les articles 425 et 429 du code p�nal.</p>
				<h5>Responsabilit�s :</h5>
				<p>PROSERVIA ne pourra en aucun cas �tre d�clar�e responsable d\'un pr�judice financier, commercial ou d\'une autre 
					nature, caus� directement ou indirectement par les prestations fournies.</p>
				<h5>Attribution de comp�tence :</h5>
				<p>L\'�lection de domicile est faite par le site vendeur � son agence commerciale. </p>
				<p>En cas de contestation relative � l\'ex�cution du contrat de vente ou au paiement du prix, ainsi que de 
					contestations relatives plus particuli�rement � l\'interpr�tation ou l\'ex�cution de pr�sentes clauses ou 
					conditions, le Tribunal de Commerce local dont d�pend l\'agence est seul comp�tent.</p>
				<p>Le fait de passer commande entra�ne l\'acceptation des pr�sentes conditions g�n�rales de vente.</p>
				<h5>Certification :</h5>
				<p>Les modalit�s et d�lais de certification sont donn�s � titre indicatif. PROSERVIA ne pourra �tre tenue 
					responsable de modifications d\'�ch�ances ou de versions impos�es par les �diteurs dont d�pendent les 
					certifications.</p>
				<h5>Remarques :</h5>
				<p>Nous insistons sur la n�cessit� du respect des niveaux requis qui permettent de profiter pleinement des 
					formations dispens�es.</p>';
            } else {
                $doc .= '<h5>Paiement</h5> 
				<p>Nos prix sont �tablis hors taxes, ils sont calcul�s d�part agence. Tout changement de 
					TVA entra�nera automatiquement le r�ajustement des prix TTC.</p>
				<h5>Conditions de r�glement:</h5> 
				<p>Nos prestations sont r�glables � r�ception de la facture et sans escompte.</P>
				<h5>D�lais de paiement:</h5> 
				<p>En cas de retard de paiement, les sommes restant dues porteront int�r�t de plein doit 
					et sans qu\'une mise en demeure soit n�cessaire, au taux de 2% le mois, sans que cette
					clause nuise � l\'exigibilit� de la dette.</p>
				<h5>Annulation</h5>
				<p>Toute annulation d\'inscription de la part du client doit �tre signal�e et confirm�e 
					par �crit.</p>
				<p>Une annulation intervenant plus 48 Heures avant le d�but du cours ne donne lieu � 
					aucune facturation.</p>
				<p>Une annulation intervenant moins de 48 Heures avant le d�but du cours donne lieu � une
					facturation du montant int�gral du cours.</p> 
				<h5>Support de cours</h5>
				<p>L\'utilisation des documents remis lors des cours est soumise aux articles 40 et 41 
					de la loi du 11 mars 1957: "toute repr�sentation ou reproduction int�grale ou 
					partielle faite sans le consentement de l\'auteur ou de ses ayants droits ou ayants 
					cause est illicite".</p>
				<p>L\'article 41 de la m�me loi n\'autorise que les "copies ou reproductions strictement 
					r�serv�es � l\'usage priv� du copiste et non destin�es � une utilisation collective" 
					et "les analyses et courtes citations, sous r�serve que soient indiqu�s clairement 
					le nom de l\'auteur et la source".</p>
				<p>Toute repr�sentation ou reproduction, par quelque proc�d� que ce soit, ne respectant 
					pas la l�gislation en vigueur constituerait une contrefa�on sanctionn�e par les 
					articles 425 et 429 du code p�nal.</p>
				<h5>Responsabilit�s</h5>
				<p>PROSERVIA ne pourra en aucun cas �tre d�clar�e responsable d\'un pr�judice financier,
					commercial ou d\'une autre nature, caus� directement ou indirectement par les 
					prestations fournies.</p>
				<h5>Attribution de comp�tence</h5>
				<p>L\'�lection de domicile est faite par le site vendeur � son agence commerciale.</p>
				<p>En cas de contestation relative � l\'ex�cution du contrat de vente ou au 
					paiement du prix, ainsi que de contestations relatives plus particuli�rement 
					� l\'interpr�tation ou l\'ex�cution de pr�sentes clauses ou conditions, le Tribunal 
					de Commerce local dont d�pend l\'agence est seul comp�tent.</p>
				<p>Le fait de passer commande entra�ne l\'acceptation des pr�sentes conditions 
					g�n�rales de vente.</p>
				<h5>Remarques</h5>
				<p>Nous insistons sur la n�cessit� du respect du rythme et de la r�gularit� de 
					l�apprentissage pr�vus au d�part, afin d�obtenir les r�sultats vis�s.</p>
				<br/><br/><br/><br/><br/><br/><br/><br/><br/>';
            }
            $doc .= '</div>
                </body>
                </html>';
            //fin du bon de commande
            //�criture dans le fichier et fermeture du fichier
            $entete = fopen(ENTETE, "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $header .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($doc) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($header) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($logo) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($pied) . "\n\n";
            $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

            $fichier = fopen($nomDossier . '/BDC_' . $nom . '.doc', 'wb');
            fwrite($fichier, $data);
            fclose($fichier);
        }
    }

    /**
     * Edition des bons d'intervention pour les formateurs
     *
     * @ param string nom du dossier correspondant � la session
     *
     */
    public function editionBI($nomDossier) {
        //r�cup�ration des dates du planning
        $planning = new PlanningSession($this->Id_planning, array());
        $dates = array();
        $dates = $planning->listeDates();
        $nbJour = count($dates['jour']);

        //calcul du nombre de page pour chaque formateur : d�pends du nombre de date
        $nbPage = 1;
        if ($nbJour > 5) {
            $nbPage = (int) ($nbJour / 5);
            if ($nbJour % 5) {
                ++$nbPage;
            }
        }

        //adaptation selon la version de word
        if (VERSION_WORD == 2007) {
            $marge = 'margin-left : 4px;';
        } else {
            $marge = 'margin-left  : 2px;
							  margin-right : 2px';
        }

        //r�cup�ration de l'affaire associ�e � la session (bon d'intervention produit uniquement pour des formation intra-entreprise)
        $db = connecter();
        $ligne1 = $db->query('SELECT DISTINCT Id_affaire, so.Id_compte FROM participant p
            INNER JOIN session_opportunite so ON p.Id_affaire = so.Id_opportunite AND so.Id_session=p.Id_session
            WHERE p.Id_session=' . mysql_real_escape_string((int) $this->Id_session))->fetchRow();
        /*$ligne1 = $db->query('SELECT a.Id_affaire, a.Id_compte, a.Id_contact1 FROM affaire AS a 
										INNER JOIN proposition AS p ON a.Id_affaire=p.Id_affaire 
										INNER JOIN proposition_formation AS pf ON p.Id_proposition =pf.Id_proposition 
										WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session))->fetchRow();
        */
        
        $op = new Opportunite($ligne1->id_affaire);
        $compte = CompteFactory::create(null, $ligne1->id_compte);
        $contact1 = ContactFactory::create(null, $op->Id_contact1);

        //r�cup�ration des formateurs de la session
        $result = $db->query('SELECT f.nom, f.prenom 
					FROM formateur AS f INNER JOIN formateur_salle AS fs ON f.Id_formateur=fs.Id_formateur 
					WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session));
        //parcours des formateurs
        while ($ligne = $result->fetchRow()) {
            //cr�ation d'un fichier par formateur
            $nom = str_replace("'", "", $ligne->nom);
            $nom = htmlscperso(formatageString($nom), ENT_QUOTES);
            $prenom = str_replace("'", "", $ligne->prenom);
            $prenom = htmlscperso(formatageString($prenom), ENT_QUOTES);

            //D�but du document : insertion du css
            $doc = '<html xmlns:o=\'urn:schemas-microsoft-com:office:office\' xmlns:w=\'urn:schemas-microsoft-com:office:word\' xmlns=\'http://www.w3.org/TR/REC-html40\'>
                    <head>
                        <!--[if gte mso 9]>
                        <xml>
                            <w:WordDocument>
                                <w:View>Print</w:View>
                                <w:Zoom>100</w:Zoom>
                                <w:DoNotOptimizeForBrowser/>
                            </w:WordDocument>
                        </xml>
                        <![endif]-->
                        <link rel=File-List href="document_files/filelist.xml">
                        <style><!-- 
                            @page
                            {
                                size:21cm 29.7cmt;  /* A4 */
                                margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
                                mso-page-orientation: portrait;  
                                mso-header: url("document_files/headerfooter.htm") h1;
                                mso-footer: url("document_files/headerfooter.htm") f1;	
                            }
                            @page Section1 { }
                            div.Section1 { page:Section1; }
                            p.MsoHeader, p.MsoFooter { border: 1px solid black; }
                        --></style>
                        <style type="text/css">
					table 
						{
							font-family : arial;
							font-size 	: 14px;
						}
					th
						{
							font-weight 	: normal;
							text-align 		: left;
							vertical-align 	: top;
						}
					td
						{
							text-align 	   : center;
							vertical-align : middle;
						}
					h1
						{
							font-family 	 : arial;
							font-size 		 : 20px;
							color 			 : ' . COULEUR_TEXTE2 . ';
							background-color : ' . COULEUR_TAB1 . ';
							text-align 		 : center;
							margin-top 		 : 0px;
							' . $marge . '
						}
					h2
						{
							font-family : arial;
							font-size 	: 18px;
							color 		: ' . COULEUR_TEXTE1 . ';
							text-align 	: left;
						}
					h3
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: left;
							color 		: ' . COULEUR_TAB1 . ';
						}
					h4
						{
							font-family : arial;
							font-size 	: 14px;
							text-align 	: center;
							color 		: ' . COULEUR_TEXTE2 . '; 
							font-weight : bold;
						}
					p
						{
							font-family   : arial;
							font-size 	  : 16px;
							text-align 	  : left;
							margin-top 	  : 5px;
							margin-bottom : 5px;
						}
					.pied_de_page
						{
							font-family : arial;
							color 		: ' . COULEUR_TEXTE1 . ';
							font-size 	: 10px;
							text-align 	: left;
						}
				</style>
                                </head>
                <body>
                <div class=Section1>';

            $page = 0; //compteur de pages
            $i = 0; //indice pour parcourir le tableau de dates 
            while ($page < $nbPage) {
                //insertion d'un saut de page sauf pour la premi�re page
                if ($page) {
                    $doc .= "<br clear=all style='page-break-before:always'>";
                }

                //information sur le client et la session, insertion du logo dans le tableau
                $doc .= '<h1>BON DE TRAVAIL</h1>
						<br/><table><tr>
							<th width="120" height="20"><h2>SOCIETE :</h2></th>
							<th width="300" height="20"><h2>' . $compte->nom . '</h2></th>
						</tr><tr>
							<th width="120" height="20"><h3><b>Contact client </b>:</h3></th>
							<th width="300" height="20"><h3>' . $contact1->getName() . '</h3></th>
						</tr><tr>
							<th width="120" height="20"></th>
							<th width="300" height="20"><h3>' . $compte->adresse . '</h3></th>
						</tr><tr>
							<th width="120" height="20"></th>
							<th width="300" height="20"><h3>' . $compte->code_postal . ' ' . $compte->ville . '</h3></th>
						</tr><tr>
							<th colspan="2" width="420" height="10"></th>
						</tr><tr>
							<th width="120" height="20"><b>Prestation :</b></th>
							<th width="300" height="20">Animation de la formation "' . $this->nom_session . '"</th>
						</tr><tr>
							<th width="120" height="20"><b>Intervenant :</b></th>
							<th width="300" height="20">' . $ligne->prenom . ' ' . $ligne->nom . '</th>
						</tr></table>
                <div align="center"><br/><br/>
				<table border="1" cellspacing="0" bordercolor=' . COULEUR_TAB1 . '><tr>
						<td width="180" height="40" align="center" bgColor=' . COULEUR_TAB1 . '><h4>Date de l\'intervention</h4></td>
						<td width="180" height="40" align="center" bgColor=' . COULEUR_TAB1 . '><h4>Nom de l\'intervenant</h4></td>
						<td width="180" height="40" align="center" bgColor=' . COULEUR_TAB1 . '><h4>Nombre de jours effectu�s</h4></td>
					</tr>';

                //tableau des dates; attention tenir compte que tous les n lignes : saut de page plus tableau informations client/session
                $j = 0; //compteur du nombre de dates par page
                //affichage des dates tant q'uon a pas atteind la fin des dates de la session et le nombre de dates possibles par page
                while (($i < $nbJour) && ($j < 5)) {
                    $doc .= '<tr>
								<td width="180" height="40" align="center"> ' . $dates['jour'][$i] . '-' . $dates['mois'][$i] . '-' .
                            $dates['annee'][$i] . ' </td>
								<td width="120" height="40" align="center">' . $ligne->prenom . ' ' . $ligne->nom . '</td>
								<td width="120" height="40" align="center">1</td>
							</tr>';
                    ++$i;
                    ++$j;
                }
                if ($j == 1) {
                    $jour = 'jour';
                } else {
                    $jour = 'jours';
                }
                $doc .= '<tr>
						<td colspan="2" width="360" height="40" align="center" border="0">
							<FONT color=' . COULEUR_TEXTE1 . '><b>TOTAL </b></font></td>
						<td width="180" height="40" align="center">
							<FONT color=' . COULEUR_TEXTE1 . '><b> ' . $j . ' ' . $jour . '<b/></font></td>
					</tr></table></div><br/><br/>
					<div align="left">
						<table><tr>
							<th width="300" height="20"><b> PROSERVIA </b></th>
							<th width="300" height="20"><b> Le Client : ' . $compte->nom . ' <b/></th>
						</tr><tr>
							<th width="300" height="20">Nom du signataire : Ludivine CRIAUD</th>
							<th width="300" height="20">Nom du signataire : ���������</th>
						</tr><tr>
							<th width="300" height="20">Signature et Cachet de l�entreprise :</th>
							<th width="300" height="20">Signature et Cachet de l�entreprise :</th>
						</tr></table></div><br/><br/><br/><br/><br/>';

                //ajout d'espace pour atteindre le pied de page selon le nombre de dates affich�es dans la page
                switch ($j) {
                    case 1 :
                        $doc .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                        break;
                    case 2 :
                        $doc .= '<br/><br/><br/><br/><br/><br/><br/>';
                        break;
                    case 3 :
                        $doc .= '<br/><br/><br/><br/><br/>';
                        break;
                    case 4 :
                        $doc .= '<br/><br/>';
                        break;
                }
                ++$page; //incr�mentation du nombre de page
            }
            $doc .= '</div>
                </body>
                </html>';

            //�criture dans le fichier et fermeture du fichier
            $entete = fopen(ENTETE, "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $header .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/bandeau1024.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $logo .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $entete = fopen('../for/imageFormation/pieddepage.jpg', "rb");
            while (!feof($entete)) { //on parcourt toutes les lignes
                $pied .= fgets($entete, 4096); // lecture du contenu de la ligne
            }
            fclose($entete);

            $data = "MIME-Version: 1.0\nContent-Type: multipart/related; boundary=\"----=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\"\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($doc) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\headerfooter.htm') . "\nContent-Transfer-Encoding: base64\nContent-Type: text/html; charset=\"utf-8\"\n\n";
            $data .= base64_encode($header) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\bandeau1024.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($logo) . "\n\n";
            $data .= "------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ\nContent-Location: file:///C:/" . preg_replace('!\\\!', '/', 'document_files\pieddepage.jpg') . "\nContent-Transfer-Encoding: base64\nContent-Type: image/jpeg\n\n";
            $data .= base64_encode($pied) . "\n\n";
            $data .= '------=_NextPart_ERTUP.EFETZ.FTYIIBVZR.EYUUREZ--';

            $fichier = fopen($nomDossier . '/BonIntervention_' . $nom . '_' . $prenom . '_' . formatageDate($planning->dateDebut) . '.doc', 'wb');
            fwrite($fichier, $data);
            fclose($fichier);
        }
    }

    /**
     * Liste des boutons et des documents �dit�s affich�s dans la fonction consultation de la session 
     *
     * @return string code html � afficher
     */
    public function documentsList() {
        /* affichage en tableau des boutons pour �diter les documents avec, si les documents ont �t� �dit�s, 
          la date et l'heure de l'�dition */
        $html = '<div classe="left"><b> Edition de document</b><br/>
			<input type="hidden" id="version" name="version" value="' . VERSION_WORD . '">
			<table>';

        //r�cup�ration des types de documents
        $db = connecter();
        $result = $db->query('SELECT * FROM type_doc');
        while ($ligne = $result->fetchRow()) {
            if ($ligne->type == 'BDC') {
                $condition = ' (Conditions de vente anglais <input type="checkbox" id="condition" name="condition" value="2">)';
            } else {
                $condition = '';
            }
            $html .= '<tr>
				<td width="100" height="25"><input type="hidden" id="Id_doc' . $ligne->type . '" value="' . $ligne->id_doc . '"></td>
				<td width="200" height="25" align ="left">' . $ligne->libelle . ' : </td>
				<td width="400" height="25" align ="left"><div id="edition' . $ligne->type . '">';
            if ($this->doc[$ligne->id_doc]['edite']) {
                $html .= 'Edit� le ' . $this->doc[$ligne->id_doc]['date_edition'] . ' � ' . $this->doc[$ligne->id_doc]['heure_edition'] .
                        '&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" onclick="edition(\'' . $ligne->type . '\', ' . (int) $this->Id_session . ',1)" value="Re-Editer">' .
                        $condition;
            } else {
                $html .= '<input type="button" onclick="edition(\'' . $ligne->type . '\', ' . (int) $this->Id_session . ',0)" value=" Editer ">' .
                        $condition;
            }
            $html .= '</div></td>
					  <td width="100" height="25"></td>
					  </tr>';
            if ($ligne->type == 'Confirmation') {
                //pour les confirmations d'inscription : cr�ation d'un lien mailto pour pouvoir envoyer la lettre de confirmation par mail
                $html .= '<tr>
					<td width="100" height="25"></td>
					<td width="200" height="25" align ="left"></td>
					<td width="400" height="25" align ="left"><div id="editionConfirmation2">
								' . $this->editionConfirmation2($this->Id_session) . '</div></td>
					<td width="100" height="25"></td>
				</tr>';
            }
        }
        $html .= '</table></div>';
        return $html;
    }

}

?>
