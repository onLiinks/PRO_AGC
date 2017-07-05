<?php
/**
 * Fichier index.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */
/**
 * Inclusion de fichiers
 */
require_once '../config/config_com.php';
require_once '../config/config.php';
require_once AUTOLOAD_URL;
require_once FUNCTION_URL;

set_error_handler("Error::errorHandler");
register_shutdown_function('Error::shutdownHandler');

/**
 * Réinitialisation de la session
 */
session_start();
checkSession();

/**
 * Initialisation de variables
 */
$titre = '';
$filtre = '';
$contenu = '';
$zone = ZONE_COMMERCIALE;
$menuDroit = MENU_DROIT;
$menuGauche = MENU_GAUCHE;

/**
 * Initialisation de variables dépendant de la zone
 */
$squelette = SOURCE_HTML;

if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}

try {
    $_SESSION[SESSION_PREFIX.'logged']->checkEntry($zone);
    
    /**
     * Fabrication de la page à envoyer au client
     */
    switch ($_GET['a']) {

        case 'modifier':
        case 'creer':
            $titre = $_GET['class'];
            if ($_GET['Id']) {
                $titre .= ' n°' . $_GET['Id'];
            }
            $class = new $_GET['class']($_GET['Id'], array());
            $contenu = $class->form();
            break;

        case 'enregistrer':
            $titre = $_POST['class'];
            $class = new $_POST['class']($_POST['Id'], $_POST);
            $url = 'index.php?a=consulter' . $_POST['class'];
            if ($class->check()) {
                $class->save();
                header('location: ' . $url . '');
            } else {
                $contenu = $class->form();
            }
            break;

        case 'consulterAffaire':
            $titre = CASES;

            if ($_GET['Id_compte']) {
                $_SESSION['filtre']['Id_compte'] = $_GET['Id_compte'];
            }
            if ($_GET['Id_contact']) {
                $_SESSION['filtre']['Id_contact'] = $_GET['Id_contact'];
            }
            if ($_GET['Id_statut']) {
                $_SESSION['filtre']['Id_statut'] = $_GET['Id_statut'];
            }
            if ($_GET['commercial']) {
                $_SESSION['filtre']['commecial'] = $_GET['commercial'];
            }
            $filtre = Affaire::searchForm();
            $contenu = "<div id='page'>" . CASE_LOAD . " <br /><img src=" . IMG_LOAD . " alt='chargement'></div><script>afficherAffaire()</script>";
            break;

        case 'afficherAffaire':
            $affaire = new Affaire($_GET['Id_affaire'], array());
            $titre = '
			<fieldset>
			<div class="left">
				AFFAIRE n° <a href="../com/index.php?a=modifier_affaire&Id_affaire=' . $affaire->Id_affaire . '">' . $affaire->Id_affaire . '</a>
				--> ' . Statut::getLibelle($affaire->Id_statut) . '<br />
			    Apporteur : ' . $affaire->apporteur . ' <br />
			    Créateur : ' . $affaire->createur . ' <br />
			    Commercial : ' . $affaire->commercial . ' <br />
			</div>
            <div class="right">
                Agence : ' . Agence::getLibelle($affaire->Id_agence) . '<br />
			   	Pôle : ' . Pole::getLibelle($affaire->Id_pole) . '<br />
			    Type de contrat : ' . TypeContrat::getLibelle($affaire->Id_type_contrat) . '
			</div>
			</fieldset>';
            $contenu = $affaire->consultation();
            break;

        case 'consulterContact':
            $titre = CONTACTS;
            $contact = ContactFactory::create('CG');
            $filtre = $contact::searchForm();
            $contenu = "<div id='page'></div><script>afficherContact()</script>";
            break;

        case 'consulterProposition':
            $filtre = Proposition::searchForm($_GET['Id_statut_prop']);
            $contenu = "<div id='page'>";
            if ($_GET['Id_statut_prop'] == 1) {
                $titre = 'PROPOSITIONS PISTE';
                $contenu .= '<script>afficherPropositionAV({data: 1})</script>';
            } elseif ($_GET['Id_statut_prop'] == 2) {
                $titre = 'PROPOSITIONS NON TRAITEES';
                $contenu .= '<script>afficherPropositionAV({data: 2})</script>';
            } elseif ($_GET['Id_statut_prop'] == 3) {
                $titre = 'PROPOSITIONS EN COURS DE REDACTION';
                $contenu .= '<script>afficherPropositionAV({data: 3})</script>';
            } elseif ($_GET['Id_statut_prop'] == 4) {
                $titre = 'PROPOSITIONS REMISES';
                $contenu .= '<script>afficherPropositionAV({data: 4})</script>';
            } elseif ($_GET['Id_statut_prop'] == 5) {
                $titre = 'PROPOSITIONS GAGNEES';
                $contenu .= '<script>afficherPropositionAV({data: 5})</script>';
            } elseif ($_GET['Id_statut_prop'] == 6) {
                $titre = 'PROPOSITIONS PERDUES';
                $contenu .= '<script>afficherPropositionAV({data: 6})</script>';
            } elseif ($_GET['Id_statut_prop'] == 7) {
                $titre = 'PROPOSITIONS ATTRIBUEES';
                $contenu .= '<script>afficherPropositionAV({data: 7})</script>';
            } elseif ($_GET['Id_statut_prop'] == 8) {
                $titre = 'PROPOSITIONS OPERATIONNELLES';
                $contenu .= '<script>afficherPropositionAV({data: 8})</script>';
            } elseif ($_GET['Id_statut_prop'] == 9) {
                $titre = 'PROPOSITIONS TERMINEES';
                $contenu .= '<script>afficherPropositionAV({data: 9})</script>';
            }
            $contenu .= "</div>";
            break;

        case 'consulterCompte':
            $titre = CUSTOMERS;
            $compte = CompteFactory::create('CG');
            $filtre = $compte::searchForm();
            $contenu = '<div id="page"></div><script>afficherCompte()</script>';
            break;

        case 'consulterBilanActivite':
            $titre = 'BILAN D\'ACTIVITE';
            $filtre = BilanActivite::searchForm();
            $contenu = '<div id="page"></div><script>afficherBilanActivite()</script>';
            break;

        case 'consulterCD':
            $titre = CONTRAT_DELEGATION;
            $filtre = ContratDelegation::searchForm();
            $contenu = '<div id="page"></div><script>afficherCD()</script>';
            break;

        case 'formulaire_affaire':
            $titre = CASES;
            $affaire = new Affaire('', $_GET);
            $contenu = $affaire->form();
            break;

        case 'modifier_affaire':
            $titre = 'AFFAIRE n° ' . (int) $_GET['Id_affaire'];
            if (isset($_GET['Id_affaire'])) {
                $_SESSION['affaire'] = $_GET['Id_affaire'];
            }
            $affaire = new Affaire($_SESSION['affaire'], array());
            if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_GET['Id_affaire'])) {
                $contenu .= $affaire->form();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'enregistrer_affaire' :
            if ($_POST['Id_pole'] == 1 || $_POST['Id_pole'] == 2) {
                $upload = new HTTP_Upload('fr');
                $files = $upload->getFiles();
                foreach ($files as $file) {
                    if (PEAR::isError($file)) {
                        die($file->getMessage());
                    }
                    if ($file->isValid()) {
                        $file->setName('real');
                        if ($file->getProp('form_name') == 'doc') {
                            $lien = stripslashes('DOC_' . $_POST['Id_affaire'] . '_' . DATEFR . '.' . $file->upload['ext']);
                            $file->upload['name'] = $lien;
                            $_POST['doc'] = $lien;
                            $dest_dir = PROPALE_DIR;
                        }
                        $dest_name = $file->moveTo($dest_dir);
                        if (PEAR::isError($dest_name)) {
                            die($dest_name->getMessage());
                        }
                        //$real = $file->getProp('real');
                        //echo $real;
                        //echo "Uploaded $real as $dest_name in $dest_dir\n";
                    } elseif ($file->isMissing()) {
                        //echo "No file selected\n";
                    } elseif ($file->isError()) {
                        //echo $file->errorMsg() . "\n";
                    }
                }
            }
            $affaire = new Affaire($_POST['Id_affaire'], $_POST);
            $affaire->save();
            $_POST['Id_affaire'] = $_SESSION['affaire'];
            $description = new Description($_POST['Id_description'], $_POST);
            $description->save();

            if (isset($_POST['Id_decision'])) {
                $decision = new Decision($_POST['Id_decision'], $_POST);
                $decision->save();
            }
            if (isset($_POST['Id_analyse_commerciale'])) {
                $analyse_com = new Analyse($_POST['Id_analyse_commerciale'], 'COM', $_POST);
                $analyse_com->saveBusinessAnalysis();
            }
            if (isset($_POST['Id_analyse_risque'])) {
                $analyse_risque = new Analyse($_POST['Id_analyse_risque'], 'RIS', $_POST);
                $analyse_risque->saveRiskAnalysis();
            }
            if (isset($_POST['Id_environnement'])) {
                $environnement = new Environnement($_POST['Id_environnement'], $_POST);
                $environnement->save();
            }

            $planning = new Planning($_POST['Id_planning'], $_POST);
            $planning->save();

            if ($_POST['Id_pole'] == 6 || $_POST['Id_pole'] == 7) {
                if ($_POST['nb_proposition']) {
                    $tab = $_POST;
                    $i = 1;
                    while ($i <= $_POST['nb_proposition']) {
                        $_POST['periode'] = array();
                        $_POST['annee'] = array();
                        $_POST['duree'] = array();
                        $_POST['debut'] = array();
                        $_POST['fin'] = array();
                        $_POST['cout'] = array();
                        $_POST['ca'] = array();
                        $_POST['marge'] = array();
                        $_POST['chiffre_affaire'] = 0;
                        $_POST['cout_total'] = 0;

                        $nb_annee = $_POST['pr-' . $i . '-an'];
                        $j = 1;
                        while ($j <= $nb_annee) {
                            $nb_periode = $_POST['pr-' . $i . '-n_periode-an-' . $j];
                            $l = 1;
                            while ($l <= $nb_periode) {
                                $_POST['annee'][] = $tab['annee' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['periode'][] = $tab['pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['duree'][] = $tab['duree_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['debut'][] = $tab['debut_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['fin'][] = $tab['fin_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['cout'][] = $tab['cout_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['ca'][] = $tab['ca_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['marge'][] = $tab['marge_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['chiffre_affaire'] += $tab['ca_pe' . $l . '-pr-' . $i . '-an' . $j];
                                $_POST['cout_total'] += $tab['cout_pe' . $l . '-pr-' . $i . '-an' . $j];
                                ++$l;
                            }
                            ++$j;
                        }
                        if ($_POST['chiffre_affaire'] != 0) {
                            $_POST['marge_totale'] = round((100 * ($_POST['chiffre_affaire'] - $_POST['cout_total']) / $_POST['chiffre_affaire']), 2);
                        } else {
                            $_POST['marge_totale'] = 0;
                        }
                        $m = $i - 1;
                        $_POST['remarque_proposition'] = $tab['remarque_proposition'][$m];
                        $_POST['date_remise'] = $tab['date_remise'][$m];
                        $_POST['reference'] = $tab['reference'][$m];
                        ++$i;
                        $proposition = new Proposition($_POST['Id_proposition'], $_POST);
                        $proposition->save(null, $_POST['save_ressource']);
                    }
                }
            } else {
                if ($_POST['Id_type_contrat'] == 1) {
                    $proposition = new Proposition($_POST['Id_proposition'], $_POST);
                    $proposition->save(null, $_POST['save_ressource']);
                } elseif ($_POST['Id_type_contrat'] == 2) {
                    /* $db = connecter();
                      $result = $db->query('SELECT Id_proposition FROM proposition WHERE Id_affaire= ' . mysql_real_escape_string((int) $_POST['Id_affaire']) . '');
                      while ($ligne = $result->fetchRow()) {
                      $proposition = new Proposition($ligne->id_proposition, array());
                      $proposition->delete();
                      }

                      if ($_POST['chiffre_affaire'] != 0) {
                      $_POST['marge_totale'] = round((100 * ($_POST['chiffre_affaire'] - $_POST['cout_total']) / $_POST['chiffre_affaire']), 2);
                      }
                      else {
                      $_POST['marge_totale'] = 0;
                      }
                      $m = $i - 1;
                      $_POST['remarque_proposition'] = $tab['remarque_proposition'][$m];
                      $_POST['date_remise'] = $tab['date_remise'][$m];
                      $_POST['reference'] = $tab['reference'][$m];
                      ++$i;
                     */
                    $proposition = new Proposition($_POST['Id_proposition'], $_POST);
                    $proposition->save(null, $_POST['save_ressource']);
                } elseif ($_POST['Id_type_contrat'] == 3) {
                    //Dans le cas d'une affaire du pôle formation : gestion des données de la session et du bon de commande
                    if ($_POST['Id_pole'] == 3) {
                        //Gestion du bon de commande
                        $upload = new HTTP_Upload('fr');
                        $files = $upload->getFiles();

                        //récupération du fichier importé
                        foreach ($files as $file) {
                            // si erreur lors de la récupération du fichier
                            if (PEAR::isError($file)) {
                                die($file->getMessage());
                            }
                            //si le fichier est présent et correct
                            if ($file->isValid()) {
                                $file->setName('real');
                                if ($file->getProp('form_name') == 'lien_bdc') {
                                    //mise en forme du nom du client
                                    $compte = CompteFactory::create(null, $_POST['Id_compte']);
                                    $societe = htmlscperso(strtoupper(withoutAccent(str_replace("'", "", $compte->nom))), ENT_QUOTES);

                                    //mise en forme du nom de la session
                                    $session = new Session($_POST['Id_session'], array());
                                    $nom_session = htmlscperso(strtoupper(withoutAccent(str_replace("'", "", $session->nom_session))), ENT_QUOTES);
                                    //normalisation du nom de fichier en BDC_Client_Nom session_ N° affaire
                                    $lien = formatageString(stripslashes('BDC_' . $societe . '_' . $nom_session . '_' . $_POST['Id_affaire'] . '.' . $file->upload['ext']));
                                    $file->upload['name'] = $lien;
                                    $_POST['lien_bdc'] = $lien;
                                    //chemin vers le dossier pour stocker le fichier
                                    $dest_dir = BDC_DIR;
                                }
                                //transfert du fichier vers le dossier voulu
                                $dest_name = $file->moveTo($dest_dir);
                                if (PEAR::isError($dest_name)) {
                                    die($dest_name->getMessage());
                                }
                            }
                            //si il n'y a pas de fichier ou que le fichier est invalide
                            elseif ($file->isMissing()) {
                                //echo "No file selected<br/>";
                            } elseif ($file->isError()) {
                                //echo $file->errorMsg() . "<br/>";
                            }
                        }
                        //pour les affaires du pôle formation : qu'une seule proposition
                        $proposition = new Proposition($_POST['Id_proposition'], $_POST);
                        $proposition->save($_POST['Id_pole'], $_POST['save_ressource']);
                        //Si une session est associée à l'affaire :
                        // si il y a eu modification de la session associée mise à jour des données de l'ancienne session (suppresion des inscrits de l'affaire, du ca et de la marge)
                        if (isset($_POST['Id_sessionActuel'])) {
                            if ($_POST['Id_sessionActuel'] != $_POST['Id_session']) {
                                $sessionPrecedente = new Session($_POST['Id_sessionActuel'], array());
                                $sessionPrecedente->updateSession();
                            }
                        }
                        //mise à jour des données de la session associée en rapport avec l'affaire (ajout du nombre d'inscrits, du ca et de la marge)
                        if ($_POST['Id_session'] != ' ') {
                            $session = new Session($_POST['Id_session'], array());
                            $session->updateSession();
                        }
                    } else {
                        /* $db = connecter();
                          $requete = 'SELECT Id_proposition FROM proposition WHERE Id_affaire= ' . mysql_real_escape_string((int) $_POST['Id_affaire']);
                          $result = $db->query($requete);
                          while ($ligne = $result->fetchRow()) {
                          $proposition = new Proposition($ligne->id_proposition, array());
                          $proposition->delete();
                          } */
                        $tab = $_POST;
                        $i = 0;

                        $proposition = new Proposition($_POST['Id_proposition'], $_POST);
                        $proposition->save(null, $_POST['save_ressource']);
                    }
                }
            }

            //$log = new Log($_SESSION['affaire'], $_POST);
            //$log->save();
            ?>
            <script>
                if(confirm('Voulez-vous retourner sur la liste des affaires ?')) {
                    location.replace('index.php?a=consulterAffaire');
                } else {
                    location.replace('index.php?a=modifier_affaire&Id_affaire=<?php echo $_SESSION["affaire"]; ?>');
                }
            </script>
            <?php
            break;

        case 'modifier_ca_affaire':
            $titre = CASES;
            $squelette = DEFAULT_HTML;
            $filtre = Affaire::searchRevenueCaseForm();
            $contenu = "<div id='page'></div><script>afficherCAAffaire()</script>";
            break;

        case 'demande_changement':
            $titre = ASKCHANGE;
            if (!is_null($_GET['Id_ressource']))
                $squelette = DEFAULT_HTML;
            $dc = new DemandeChangement('', $_GET);
            $contenu = $dc->form();
            break;

        case 'genererContratDelegation':
            $lieu = new Agence($_GET['lieu_mission'], '');
            if ($lieu->libelle)
                $_GET['lieu_mission'] = $lieu->libelle;
            if (!is_numeric($_GET['Id_ressource']) && $_GET['Id_ressource'] != 'MAT')
                $_GET['Id_ressource'] = Ressource::getCegidResourceCode($_GET['Id_ressource']);
            if(!is_numeric($_GET['Id_affaire']))
                $_GET['Id_affaire'] = convertSalesforceId($_GET['Id_affaire']);
            $contratDelegation = new ContratDelegation(null, $_GET);
            $squelette = DEFAULT_HTML;
            $contenu .= $contratDelegation->form();
            if ($contratDelegation->reference_affaire) {
                if (is_numeric($contratDelegation->Id_affaire)) {
                    $titre = 'CONTRAT DELEGATION de l\'affaire <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $contratDelegation->Id_affaire . '\'>n° ' . $contratDelegation->reference_affaire . '</a>';
                }
                else {
                    if($contratDelegation->reference_affaire_mere)
                        $titre = '<div style="float:left;"><a href="' . SF_URL . $contratDelegation->Id_affaire . '"> « Retour à l\'affaire SFC</a></div>CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->reference_affaire_mere . ' - Sous affaire n° ' . $contratDelegation->reference_affaire;
                    else
                        $titre = '<div style="float:left;"><a href="' . SF_URL . $contratDelegation->Id_affaire . '"> « Retour à l\'affaire SFC</a></div>CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->reference_affaire;
                }
            }
            else if ($contratDelegation->Id_affaire) {
                if (is_numeric($contratDelegation->Id_affaire))
                    $titre = 'CONTRAT DELEGATION de l\'affaire <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $contratDelegation->Id_affaire . '\'>n° ' . $contratDelegation->Id_affaire . '</a>';
                else {
                    if($contratDelegation->reference_affaire_mere)
                        $titre = '<div style="float:left;"><a href="' . SF_URL . $contratDelegation->Id_affaire . '"> « Retour à l\'affaire SFC</a></div>CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->Id_affaire . ' - Sous affaire n° ' . $contratDelegation->reference_affaire;
                    else
                        $titre = '<div style="float:left;"><a href="' . SF_URL . $contratDelegation->Id_affaire . '"> « Retour à l\'affaire SFC</a></div>CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->Id_affaire;
                }
            }
            break;
            
        case 'creerContratDelegation':
            $contratDelegation = new ContratDelegation(null, null);
            $contenu .= $contratDelegation->createForm();
            break;

        case 'genererContratDelegationHorsAffaire':
            $contratDelegation = new ContratDelegation('', '');
            $squelette = DEFAULT_HTML;
            $contenu = $contratDelegation->withoutCaseForm();
            break;

        case 'enregistrerContratDelegation':
            $squelette = DEFAULT_HTML;
            
            $contratDelegation = new ContratDelegation($_POST['Id'], $_POST);
            $contratDelegation->save();
            
            $cd_saved = CD_SAVED;
            $ressource = RessourceFactory::create($_POST['type_ressource'], $contratDelegation->Id_ressource, array());
            if ($ressource->type_ressource != 'MAT') {
                $dmdChgt = "if(confirm('La ressource change t\'elle de service ? (Ouvrira une demande de changement)')) {
                        window.open('index.php?a=demande_changement&Id_ressource={$_POST['Id_ressource']}','fenetre', propriete);
                    }";
            }
            $contenu = <<<EOT
            <script type="text/javascript">
                propriete = 'top=0,left=0,resizable=yes, toolbar=yes, scrollbars=yes, menubar=yes, location=no, statusbar=no'
                propriete += ',width=' + screen.width + ',height=' + screen.height;

                alert("{$cd_saved}");
                if(confirm('Envoyer le contrat délégation par mail aux services concernés ?')) {
                    {$dmdChgt}
                    new Ajax.Request('index.php?a=envoyerMailCD', {
                        method: 'get',
                        parameters: 'Id={$_SESSION['Id_contrat_delegation']}',
                        onSuccess: function(response) {
                            if(window.opener != null) {
                                window.opener.location.reload();
                                window.close();
                            }
                            else {
                                location.replace('index.php?a=consulterCD');
                            }
                        }
                    });
                    
                } else {
                    {$dmdChgt}
                    if(window.opener != null) {
                        window.opener.location.reload();
                        window.close();
                    }
                    else {
                        location.replace('index.php?a=consulterCD');
                    }
                }
            </script>
EOT;
            break;

        case 'enregistrerContratDelegationHorsAffaire':
            $contratDelegation = new ContratDelegation($_POST['Id'], $_POST);
            $contratDelegation->save();
            $cd_saved = CD_SAVED;
            $contenu = <<<EOT
            <script type="text/javascript">
                propriete = 'top=0,left=0,resizable=yes, toolbar=yes, scrollbars=yes, menubar=yes, location=no, statusbar=no'
                propriete += ',width=' + screen.width + ',height=' + screen.height;

                alert("{$cd_saved}");
                if(confirm('Envoyer le contrat délégation par mail aux services concernés ?')) {
                    new Ajax.Request('index.php?a=envoyerMailCD', {
                        method: 'get',
                        parameters: 'Id={$_SESSION['Id_contrat_delegation']}',
                        onSuccess: function(response) {
                            if(window.opener != null) {
                                window.opener.location.reload();
                                window.close();
                            }
                            else {
                                location.replace('index.php?a=consulterCD');
                            }
                        }
                    });
                } else {
                    if(window.opener != null) {
                        window.opener.location.reload();
                        window.close();
                    }
                    else {
                        location.replace('index.php?a=consulterCD');
                    }
                }
            </script>
EOT;
            break;

        case 'editerContratDelegation':
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->edit();
            break;

        case 'modifierContratDelegation':
            $titre = 'Contrat Délégation n° ' . (int) $_GET['Id'];
            $squelette = DEFAULT_HTML;
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->form();
            if ($contratDelegation->reference_affaire) {
                if (is_numeric($contratDelegation->Id_affaire)) {
                    $titre = 'CONTRAT DELEGATION de l\'affaire <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $contratDelegation->Id_affaire . '\'>n° ' . $contratDelegation->reference_affaire . '</a>';
                }
                else {
                    if($contratDelegation->reference_affaire_mere)
                        $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->reference_affaire_mere . ' - Sous affaire n° ' . $contratDelegation->reference_affaire;
                    else
                        $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->reference_affaire;
                }
            }
            elseif ($contratDelegation->Id_affaire) {
                if (is_numeric($contratDelegation->Id_affaire))
                    $titre = 'CONTRAT DELEGATION de l\'affaire <a href=\'../com/index.php?a=afficherAffaire&Id_affaire=' . $contratDelegation->Id_affaire . '\'>n° ' . $contratDelegation->Id_affaire . '</a>';
                else {
                    if($contratDelegation->reference_affaire_mere)
                        $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->Id_affaire . ' - Sous affaire n° ' . $contratDelegation->reference_affaire;
                    else
                        $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $contratDelegation->Id_affaire;
                }
            }

            break;

        case 'editerContratDelegationHorsAffaire':
            $cd = new ContratDelegation($_GET['Id'], array());
            $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
            if ($utilisateur->getResourceRight(1, 8)) {
                $contenu = $cd->editWithoutCase();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'modifierContratDelegationHorsAffaire':
            $titre = "Contrat Délégation Hors Affaire n° {$_GET['Id']}";
            $squelette = DEFAULT_HTML;
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->withoutCaseForm();
            break;

        case 'dupliquerCD':
            $cd = new ContratDelegation($_GET['Id'], array());
            if ($_GET['Id_affaire'])
                $cd->duplicate($_GET['Id_affaire']);
            else
                $cd->duplicate();
            if ($_GET['HA'] == 1 || $_GET['redirect'] == 1) {
                ?>
                <script type='text/javascript'>
                    alert("<?php echo CD_DUPLICATED; ?>");
                    location.replace('index.php?a=consulterCD');
                </script>
                <?php
            }
            break;

        case 'editerBilanActivite':
            $ba = new BilanActivite($_GET['Id'], array());
            if (Utilisateur::getDroitEditionBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_GET['Id'])) {
                $contenu = $ba->edit();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'modifierBilanActivite':
            $titre = 'BILAN D\'ACTIVITE';
            $ba = new BilanActivite($_GET['Id'], array());
            if (Utilisateur::getDroitBilanActivite($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_GET['Id'])) {
                $contenu .= $ba->form();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'demander_ressource':
            $titre = ASKRESOURCE;
            $squelette = DEFAULT_HTML;
            $affaire = new Affaire($_GET['Id_affaire'], array());
            if (Utilisateur::getCaseModificationRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_GET['Id_affaire'])) {
                $contenu .= $affaire->demandeRessource();
            } else {
                $contenu = GestionDroit::forbiddenAccess();
            }
            break;

        case 'envoyer_demande_ressource':
            $mailing = new Mailing($_POST['Id_mailing'], $_POST);
            $mailing->envoyer($_FILES);
            ?>
            <script type='text/javascript'>
                alert("<?php echo ASK_SEND_OK; ?>");
                location.replace('index.php');
            </script>
            <?php
            break;

        case 'envoyerMailCD':
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            
            $cdsService = false;
            if ($contratDelegation->statut == 'A' || $contratDelegation->statut == 'R') {
                $cdsService = $contratDelegation->ressourceIsCds();
            }
            
            if ($cdsService != false) {
                $contratDelegation->envoyerMailCDS($cdsService);
                if ($_GET['ha'])
                    $close = 'window.close();';
                ?>
                <script type='text/javascript'>
                    alert("Votre e-mail a été correctement envoyé à <?php echo $_SESSION['dest1']; ?>");
                <?php echo $close ?>
                </script>
                <?php
                unset($_SESSION['dest1']);
                unset($_SESSION['dest2']);
            } else {
                if($contratDelegation->Id_affaire == 0)
                    $contratDelegation->editWithoutCase(1);
                else
                    $contratDelegation->edit(1);
                $contratDelegation->envoyerMail();
                if ($_GET['ha'])
                    $close = 'window.close();';
                ?>
                <script type='text/javascript'>
                    alert("Votre e-mail a été correctement envoyé à <?php echo $_SESSION['dest1']; ?>");
                <?php echo $close ?>
                </script>
                <?php
                unset($_SESSION['dest1']);
                unset($_SESSION['dest2']);
            }
            break;

        case 'calculatriceRessource':
            $squelette = DEFAULT_HTML;
            $ressource = RessourceFactory::create('CAN_TAL', null, null);
            $contenu = '<script type=\'text/javascript\'>
		    				var slide; 
							function setSlideOutput(v) {
								$("marge_ressource").value = Math.round(v);
								calculRessource3();
							}
							Event.observe(window, \'load\', function() {
								slide = new Control.Slider("slider-handle", "slider-bar", {
									sliderValue: 0,
									range: $R(0,100),
									increment: 1,
									onSlide:
									function(v) {
										setSlideOutput(v);
									},
									onChange:
									function(v) {
										setSlideOutput(v);
									}
								});
								setSlideOutput(slide.value);
								$(\'header\').hide();
								$(\'footer\').hide();
							});
						</script>
						<div style="text-align:center;">
						<form action="" method="get">
						<table>
						<tr><td></td>
							<select id=\'ressource\' name=\'ressource\' onchange=\'coutJ()\'>
								<option value=\'\'>Sélectionner une ressource</option>
								' . $ressource->getList('VAL') . '
							</select>
						<td></td></tr>
						<tr><td>
							Frais J.<br /><input class="hint" type="text" name="frais_ressource" id="frais_ressource" value="0" onkeyup="if (isNaN(this.value)) this.value =0;" />
						</td>
		    			<td id="coutJ">
		    				Coût J.<br /><input class="hint" type="text"name="cout_ressource" id="cout_ressource" value="0" onkeyup="if (isNaN(this.value)) this.value =0;" />
		    			</td></tr>
		    			<tr><td>
		    				Prix de vente<br /><input class="hint" type="text" name="tarif_ressource" id="tarif_ressource" value="0" onkeyup="if (isNaN(this.value)) this.value =0;" />
		    			</td>
		    			<td>
		    				Durée (en jours)<br /><input class="hint" type="text" name="duree_ressource" id="duree_ressource" value="0" onkeyup="if (isNaN(this.value)) this.value =0;" />
		    			</td><tr>
						<tr><td colspan=\'2\' align=\'center\'><input type="button" value="Calculer" onclick="calculRessource2()" /> <input type="reset" value="Vider" onclick="" /></td></tr>
		    			</table><br />

		    			<table><tr><td align="left">Marge (%)</td><td align="right"><input type="text" name="marge_ressource" id="marge_ressource" vaue="0" size="3" onkeyup="if (this.value == \'\') return; if (isNaN(this.value)) slide.setValue(0); else slide.setValue(this.value);" /></td>
		    			<tr><td colspan="2" align="center">
		    				<div id="slider">
								<div id="slider-bar">
									<div id="slider-handle"></div>
								</div>
							</div>
						</td></tr>
		    			</table>
		    			CA (&euro;) <input type="text" name="ca_ressource" id="ca_ressource" readonly="readonly" />
						</form>
						</div>';
            break;

        ########  CAS CONCERNANT LE POLE FORMATION  ########
        ## Cas associés à une session ##
        //Listing des sessions non archivées
        case 'consulterSession':
            $titre = 'Sessions';
            $filtre = Session::searchForm();
            $contenu = "<div id='page'>" . SESSION_LOAD . " <br /><img src=" . IMG_LOAD . " alt='chargement'></div><script>afficherSession()</script>";
            break;

        // Envoie du formulaire de création d'une session
        case 'formulaire_session':
            $titre = 'SESSION';
            if (isset($_GET['pop'])) {
                $_SESSION['popSession'] = 1;
                $squelette = DEFAULT_HTML;
            }
            $session = new Session('', '');
            $contenu = $session->form();
            break;

        // Envoie du formulaire de modification d'une session
        case 'afficherSession':
            if (isset($_GET['pop'])) {
                $_SESSION['popSession'] = 1;
                $squelette = DEFAULT_HTML;
            }
            $session = new Session($_GET['Id_session'], array());
            $titre = 'SESSION n° ' . (int) $session->Id_session;
            $titre .= ' Créateur : ' . $session->createur;
            $contenu = $session->form();
            break;

        // Enregistrement d'une session
        case 'enregistrer_session':
            $session = new Session($_POST['Id_session'], $_POST);
            $session->save();
            $majAff = 0;
            if ($_POST['Id_session'] == 0) {
                //récupération de l'identifiant de la session
                $_POST['Id_session'] = $_SESSION['session'];
            } else {
                $majAff = 1;
            }
            //Enregistrement des informations du planning de la session
            $planning = new PlanningSession($_POST['Id_planning_session'], $_POST);
            $planning->save();

            //Enregistrement des informations de la checklist de la session
            $logistique = new Logistique($_POST['Id_logistique'], $_POST);
            $logistique->save();

            if ($majAff) {
                //Mise à jour des données de la session dans les affaires associées à la session
                $session->updateRelatedCases();
            }
            ?><script>alert('La session a bien été enregistrée');</script><?php
            //si la création de la session a été réalisée en pop up à partir du formulaire d'une affaire, fermeture de la fenêtre
            if ($_SESSION['popSession']) {
                $_SESSION['popSession'] = 0;
                ?><script>window.close();</script><?php
            }
            //sinon renvoie à la liste des session
            else {
                ?>
                <script>
                    if(confirm('Voulez-vous retourner sur la liste des sessions')) {
                        location.replace('index.php?a=consulterSession');
                    } else {
                        location.replace('index.php?a=afficherSession&Id_session=<?php echo $_SESSION['session']; ?>');
                    }
                </script>
                <?php
            }
            break;

        // Consultation des données d'une session
        case 'infoSession':
            if (isset($_GET['pop'])) {
                $_SESSION['popSession'] = 1;
                $squelette = DEFAULT_HTML;
            }
            $session = new Session($_GET['Id_session'], array());
            $titre = 'Session n° ' . (int) $session->Id_session;
            $contenu = $session->consultation();
            break;

        ## Cas associés à un formateur ##
        // Listing des formateurs non archivés
        case 'consulterFormateur' :
            $titre = TRAINER;
            $filtre = Formateur::searchForm();
            $contenu = '<div id="page">' . TRAINER_LOAD . ' <br /><img src=' . IMG_LOAD . ' alt="chargement"></div><script>afficherFormateur()</script>';
            break;

        // Envoie du formulaire de création d'un formateur
        case 'formulaire_formateur':
            $titre = TRAINER;
            if (isset($_GET['pop'])) {
                $_SESSION['popFormateur'] = 1;
                $squelette = DEFAULT_HTML;
            }
            $formateur = new Formateur('', '');
            $contenu = $formateur->form();
            break;

        // Envoie du formulaire de modification d'un formateur
        case 'afficherFormateur':
            $formateur = new Formateur($_GET['Id_formateur'], array());
            $titre = 'Formateur n° ' . (int) $formateur->Id_formateur;
            $contenu = $formateur->form();
            break;

        // Enregistrement d'un formateur
        case 'enregistrer_formateur':
            $formateur = new Formateur($_POST['Id_formateur'], $_POST);
            $formateur->save();
            ?><script>alert('Le formateur a bien été enregistré');</script><?php
            //si la création du formateur a été réalisée en pop up à partir du formulaire d'une session, fermeture de la fenêtre
            if ($_SESSION['popFormateur']) {
                $_SESSION['popFormateur'] = 0;
                ?><script>window.close();</script><?php
            }
            //sinon renvoie à la liste des formateurs
            else {
                ?><script>location.replace('index.php?a=consulterFormateur');</script><?php
            }
            break;

        // Consultation des données d'un formateur
        case 'infoFormateur':
            $formateur = new Formateur($_GET['Id_formateur'], array());
            $titre = 'Formateur n° ' . (int) $formateur->Id_formateur;
            $contenu = $formateur->consultation();
            break;


        ## Cas associés à une salle ##
        //Listing des salles non archivées
        case 'consulterSalle':
            $titre = ROOM;
            $filtre = Salle::searchForm();
            $contenu = '<div id="page">' . ROOM_LOAD . ' <br /><img src=' . IMG_LOAD . ' alt="chargement"></div><script>afficherSalle()</script>';
            break;

        // Envoie du formulaire de création d'une salle
        case 'formulaire_salle':
            $titre = ROOM;
            if (isset($_GET['pop'])) {
                $_SESSION['popSalle'] = 1;
                $squelette = DEFAULT_HTML;
            }
            $salle = new Salle('', '');
            $contenu = $salle->form();
            break;

        // Envoie du formulaire de modification d'une salle
        case 'afficherSalle':
            $salle = new Salle($_GET['Id_salle'], array());
            $titre = ROOM . ' n° ' . (int) $salle->Id_salle;
            $contenu = $salle->form();
            break;

        // Enregistrement d'une salle
        case 'enregistrer_salle':
            //Gestion du plan d'accès : mise en forme du nom de la salle et de la ville pour normaliser le nom du plan d'accès
            $nom = str_replace("'", "", $_POST['nom_salle']);
            $ville = str_replace("'", "", $_POST['ville']);
            $nom = htmlscperso(strtoupper(withoutAccent($nom)), ENT_QUOTES);
            $ville = htmlscperso(formatPrenom(withoutAccent($ville)), ENT_QUOTES);
            $upload = new HTTP_Upload('fr');
            $files = $upload->getFiles();

            //récupération du fichier importé
            foreach ($files as $file) {
                // si erreur lors de la récupération du fichier
                if (PEAR::isError($file)) {
                    die($file->getMessage());
                }
                //si le fichier est présent et correct
                if ($file->isValid()) {
                    $file->setName('real');
                    if ($file->getProp('form_name') == 'acces') {
                        //normalisation du nom de fichier en Acces_ville_nom de la salle
                        $lien = stripslashes('Acces_' . $ville . '_' . $nom . '.' . $file->upload['ext']);
                        $file->upload['name'] = $lien;
                        $_POST['acces'] = $lien;
                        //chemin vers le dossier pour stocker le fichier
                        $dest_dir = PLANACCES_DIR;
                    }
                    //transfert du fichier vers le dossier voulu
                    $dest_name = $file->moveTo($dest_dir);
                    if (PEAR::isError($dest_name)) {
                        die($dest_name->getMessage());
                    }
                    //$real = $file->getProp('real');
                }
                //si il n'y a pas de fichier ou que le fichier est invalide
                elseif ($file->isMissing()) {
                    // echo "No file selected<br/>";
                } elseif ($file->isError()) {
                    // echo $file->errorMsg() . "<br/>";
                }
            }
            $salle = new Salle($_POST['Id_salle'], $_POST);
            $salle->save();
            ?><script>alert('La salle a bien été enregistrée');</script><?php
            //si la création de la salle a été réalisée en pop up à partir du formulaire d'une session, fermeture de la fenêtre
            if ($_SESSION['popSalle']) {
                $_SESSION['popSalle'] = 0;
                ?><script>window.close();</script><?php
            }
            //sinon renvoie à la liste des salles
            else {
                ?>
                <script>
                    location.replace('index.php?a=consulterSalle');
                </script>
                <?php
            }
            break;

        // Consultation des données d'une salle
        case 'infoSalle':
            $salle = new Salle($_GET['Id_salle'], array());
            $titre = ROOM . ' n° ' . (int) $salle->Id_salle;
            $contenu = $salle->consultation();
            break;


        // Changement de createur
        case 'changeCDOwnerForm':
            $contratDelegation = new ContratDelegation($_GET['Id'], array());
            $contenu = $contratDelegation->changeCDOwnerForm($_GET['Id']);
            $titre = 'CHANGEMENT DE PROPRIETAIRE DU CD '.$_GET['Id'];
            break;

        case 'changeCDOwner':
                $contratDelegation = new ContratDelegation($_GET['Id'], array());
                $contratDelegation->changeCDOwner($_GET['createur']);
                $contenu = <<<EOT
            <script type="text/javascript">
                propriete = 'top=0,left=0,resizable=yes, toolbar=yes, scrollbars=yes, menubar=yes, location=no, statusbar=no'
                propriete += ',width=' + screen.width + ',height=' + screen.height;

                alert("Le propriétaire du contrat délégation a été changé");
                if(window.opener != null) {
                    window.opener.location.reload();
                    window.close();
                }
                else {
                    location.replace('index.php?a=consulterCD&Id_cd={$_GET['Id']}');
                }
            </script>
EOT;
                break;

        /**
         * Page par défaut
         *
         */
        default :
            if(!array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1, 2, 4, 5))) {
                echo '<script language="javascript" type="text/javascript">
                        window.location.replace("../membre/index.php?a=consulterCD");
                      </script>';
            }
            $compte = CompteFactory::create('CG');
            $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, $_POST);
            $debut_semaine = debutsem(date('Y'), date('m'), date('d'));
            $fin_semaine = finsem(date('Y'), date('m'), date('d'));
            $contenu .= Version::getCurrentVersion()->getMessage();
            $contenu .= '<br />
			<fieldset>
				<legend>INFORMATIONS GENERALES</legend><br />
				<div>Vous avez ' . $utilisateur->nbCase('', '', 8) . ' affaires opérationnelles et ' . $utilisateur->nbEmployee() . ' collaborateurs titulaires en suivi.</div>
				<div>Vous avez ' . $utilisateur->nbCaseWithoutModification(3) . ' affaires ' . Statut::getLibelle(3) . ' non modifiées depuis plus d\'un mois. <br />
				Vous avez ' . $utilisateur->nbCaseWithoutModification(4) . ' affaires ' . Statut::getLibelle(4) . ' non modifiées depuis plus d\'un mois. <br />
				Vous avez ' . $utilisateur->nbCaseWithoutModification(5) . ' affaires ' . Statut::getLibelle(5) . ' non modifiées depuis plus d\'un mois.
			</fieldset><br />
			<fieldset>
				<legend>' . MY_CASES_STATES . '</legend><br />
				' . Affaire::searchCasesStatusForm() . '
			</fieldset><br />
			<fieldset>
				<legend>' . MY_NOTE . '</legend><br />
				' . $utilisateur->blocnotes . '
			</fieldset><br />
			<fieldset>
                            <legend>' . MY_CUSTOMERS . '</legend><br />
                            <input id="user" name="user" type="hidden" value="' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '" />
			    <div id="page">
                                ' . $compte::search('', '', '', $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '
                            </div>
			</fieldset><br /><br />';
    }
} catch (AGCException $e) {
    if ($_GET['no_redirect']) {
        die('<a href="../public/?url=' . urlencode($_SERVER['REQUEST_URI']).'">Se connecter</a>');
    }
    $_SESSION[SESSION_PREFIX.'logged']->zoneRedirect();
}

/**
 * Inclusion du HTML
 */
require_once $squelette;
?>
