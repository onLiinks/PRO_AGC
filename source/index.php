<?php

header('Content-Type:text/html; charset=iso-8859-1');

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

if (@get_class($_SESSION[SESSION_PREFIX.'logged']) != 'Auth') {
    $_SESSION[SESSION_PREFIX.'logged'] = new Auth();
}
if ($_GET['a'] == 'selectBdd') {
    $_SESSION['societe'] = Bdd::getCegidDatabase($_GET['Id_societe']);
    $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_GET['Id_societe']);
} else {
    if ($_SESSION[SESSION_PREFIX.'logged']->auth) {
        /**
         * Fabrication de la page à envoyer au client
         */
        switch ($_GET['a']) {           
            case 'consulterCompte':
                $_SESSION['filtre'] = $_GET;
                $compte = CompteFactory::create('CG');
                echo $compte::search($_SESSION['filtre']['nom'], $_SESSION['filtre']['ville'], $_SESSION['filtre']['cp'], $_SESSION['filtre']['createur'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterAffaire':
                $_SESSION['filtre'] = $_GET;
                echo Affaire::search($_SESSION['filtre']['Id_type_contrat'], $_SESSION['filtre']['Id_compte'], $_SESSION['filtre']['Id_statut'], $_SESSION['filtre']['commercial'], $_SESSION['filtre']['redacteur'], $_SESSION['filtre']['Id_pole'], $_SESSION['filtre']['Id_agence'], $_SESSION['filtre']['ville'], $_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['ca'], $_SESSION['filtre']['type_ca'], $_SESSION['filtre']['marge'], $_SESSION['filtre']['type_marge'], $_SESSION['filtre']['Id_contact'], $_SESSION['filtre']['Id_intitule'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], $_SESSION['filtre']['nb'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['Id_raison_perdue'],$_SESSION['filtre']['motcleaffaire'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'consulterCAAffaire':
                $_SESSION['filtre'] = $_GET;
                echo Affaire::editRevenueCase($_SESSION['filtre']['Id_type_contrat'], $_SESSION['filtre']['Id_statut_ponderation'], $_SESSION['filtre']['commercial'], $_SESSION['filtre']['Id_pole'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'consulterPropositionAV':
                $_SESSION['filtre'] = $_GET;
                if ($_SESSION['filtre']['Id_statut_prop'] == 1) {
                    $titre = 'PROPOSITIONS PISTE';
                    $contenu = Proposition::rechercher1($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 2) {
                    $titre = 'PROPOSITIONS NON TRAITEES';
                    $contenu = Proposition::rechercher2($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 3) {
                    $titre = 'PROPOSITIONS EN COURS DE REDACTION';
                    $contenu = Proposition::rechercher3($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 4) {
                    $titre = 'PROPOSITIONS REMISES';
                    $contenu = Proposition::rechercher4($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 5) {
                    $titre = 'PROPOSITIONS GAGNEES';
                    $contenu = Proposition::rechercher5($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 6) {
                    $titre = 'PROPOSITIONS PERDUES';
                    $contenu = Proposition::rechercher6($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 7) {
                    $titre = 'PROPOSITIONS ATTRIBUEES';
                    $contenu = Proposition::rechercher7($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 8) {
                    $titre = 'PROPOSITIONS OPERATIONNELLES';
                    $contenu = Proposition::rechercher8($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                elseif ($_SESSION['filtre']['Id_statut_prop'] == 9) {
                    $titre = 'PROPOSITIONS TERMINEES';
                    $contenu = Proposition::rechercher9($_SESSION['filtre']['Id_type_contrat_prop'], $_SESSION['filtre']['Id_pole_prop'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                }
                echo $contenu;
                break;

            case 'consulterDemandeChangement':
                $_SESSION['filtre'] = $_GET;
                echo DemandeChangement::search($_SESSION['filtre']['Id_demande_changement'], $_SESSION['filtre']['createur'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], $_SESSION['filtre']['valide'], $_SESSION['filtre']['integre'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterContact':
                $_SESSION['filtre'] = $_GET;
                $contact = ContactFactory::create('CG');
                echo $contact::search($_SESSION['filtre']['nom'], $_SESSION['filtre']['societe'], $_SESSION['filtre']['ville'], $_SESSION['filtre']['cp'], $_SESSION['filtre']['mail'], $_SESSION['filtre']['nature'],$_SESSION['filtre']['createur'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterBilanActivite':
                $_SESSION['filtre'] = $_GET;
                echo BilanActivite::search($_SESSION['filtre']['Id_bilan_activite'], $_SESSION['filtre']['responsable'], $_SESSION['filtre']['commercial'], $_SESSION['filtre']['mois'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterSousTraitant':
                echo SousTraitant::search($_GET['nom']);
                break;

            case 'selectBdd':
                $_SESSION['societe'] = Bdd::getCegidDatabase($_GET['Id_societe']);
                $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_GET['Id_societe']);
                break;

            case 'afficheMenu':
                $_SESSION['cacheMenu'] = $_GET['cacheMenu'];
                break;

            case 'afficheFiltre':
                $_SESSION['cacheFiltre'] = $_GET['cacheFiltre'];
                break;

            case 'consulterCandidature':
                $_SESSION['filtre'] = $_GET;
                $_SESSION['filtre']['agence_souhaitee'] = explode(';', $_GET['agence']);
                $_SESSION['filtre']['type_contrat'] = explode(';', $_GET['type_contrat']);
                $_SESSION['filtre']['Id_specialite'] = explode(';', $_GET['Id_specialite']);

                echo Candidature::search($_SESSION['filtre']['createur'], $_SESSION['filtre']['commercial'], $_SESSION['filtre']['Id_etat'], $_SESSION['filtre']['Id_nature'], $_SESSION['filtre']['nom_candidat'], $_SESSION['filtre']['Id_preavis'], $_SESSION['filtre']['Id_profil'], $_SESSION['filtre']['pretention_basse'], $_SESSION['filtre']['pretention_haute'], $_SESSION['filtre']['type_date'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], $_SESSION['filtre']['cp'], $_SESSION['filtre']['type_contrat'], $_SESSION['filtre']['Id_cursus'], $_SESSION['filtre']['Id_candidature'], $_SESSION['filtre']['tv'], $_SESSION['filtre']['te'], $_SESSION['filtre']['motcle'], $_SESSION['filtre']['embauche'], $_SESSION['filtre']['Id_action_mener'], $_SESSION['filtre']['Id_specialite'], $_SESSION['filtre']['mobilite'], $_SESSION['filtre']['agence_souhaitee'], $_SESSION['filtre']['createurEtat'], $_SESSION['filtre']['Id_exp_info'], $_SESSION['filtre']['th'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterLieuxPrestation':
                $_SESSION['filtre'] = $_GET;
                echo LieuxPrestation::search($_SESSION['filtre']['libelle'], $_SESSION['filtre']['id_type_lieux_prestation'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterODM':
                $_SESSION['filtre'] = $_GET;
                echo OrdreMission::search($_SESSION['filtre']['Id_odm'], $_SESSION['filtre']['createur'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], $_SESSION['filtre']['Id_agence'], $_SESSION['filtre']['responsable'], $_SESSION['filtre']['Id_compte'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']),$_SESSION['filtre']['finishing']);
                break;

            case 'consulterLog':
                $_SESSION['filtre'] = $_GET;
                echo Log::search($_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['Id_utilisateur'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin']);
                break;

            case 'consulterAnnonce':
                $_SESSION['filtre'] = $_GET;
                echo Annonce::search($_SESSION['filtre']['metier'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], $_SESSION['filtre']['mot_cle'], $_SESSION['filtre']['localisation'], $_SESSION['filtre']['createur_annonce'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'consulterMetier':
                $_SESSION['filtre'] = $_GET;
                echo Metier::search($_SESSION['filtre']['libelle'], $_SESSION['filtre']['Id_categorie_metier'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'consulterCD':
                $_SESSION['filtre'] = $_GET;
                echo ContratDelegation::search($_SESSION['filtre']['Id_cd'], $_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['createur'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['Id_agence'], $_SESSION['filtre']['statut'], $_SESSION['filtre']['archive'], $_SESSION['filtre']['motclecd'], $_SESSION['filtre']['reference_affaire_mere'], $_SESSION['filtre']['origine'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']),$_SESSION['filtre']['finishing']);
                break;
            
            case 'consulterCollabSansCD':
                $_SESSION['filtre'] = $_GET;
                echo ContratDelegation::getCollabWithoutContratDeleg($_SESSION['filtre']['date'], $_SESSION['filtre']['Id_agence'], $_SESSION['filtre']['cds'], $_SESSION['filtre']['origine'], $_SESSION['filtre']['withha'], $_SESSION['filtre']['cdstatus'], $_SESSION['filtre']['retirerabsent'], $_SESSION['filtre']['retirerstaff'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'consulterContratDelegWorkTime':
                $_SESSION['filtre'] = $_GET;
                echo ContratDelegation::getContratDelegWorkTime($_SESSION['filtre']['Id_cd'], $_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['Id_createur'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['month'], $_SESSION['filtre']['year'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'consulterRefacturationCD':
                $_SESSION['filtre'] = $_GET;
                echo ContratDelegation::refacturationCD($_SESSION['filtre']['Id_cd'], $_SESSION['filtre']['Id_affaire'], $_SESSION['filtre']['Id_ressource'], $_SESSION['filtre']['motclecd'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'exportCD':
                $_SESSION['filtre'] = $_GET;
                echo ContratDelegation::searchExport($_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;
            
            case 'dupliquerCD':
            $cd = new ContratDelegation($_GET['Id'], array());
            echo $cd->duplicateForm();
            /*uerCD':
?>
            <script type='text/javascript'>
                alert("<?php echo CD_DUPLICATED; ?>");
                location.replace('index.php?a=consulterCD');
            </script>
<?php
             */
            break;
        
            case 'annonceInPlaceEditor':
                $l = utf8_decode($_POST['libelle']);
                if($_POST['type'] == 'Id_metier'|| $_POST['type'] == 'Id_evolution_possible') {
                    $metier = new Metier($_POST['value'], null);
                    if($metier->url != '')
                        echo '<a href="' . PROSERVIA_RH_WEBSITE . $metier->url . '" target="_blank">' . $l . '</a>';
                    else
                        echo $l;
                }
                else {
                    echo $l;
                }
                break;
            
            case 'updateOpportunityCD':
                echo Affaire::getList($_GET['type'], $_GET['Id_affaire']);
                break;

            case 'afficherTypeReponse':
                $decision = new Decision($_GET['Id_decision'], array());
                echo $decision->goNoGoAnswerForm($_GET['reponse']);
                break;

            case 'afficherIframeRapport':
                $fin = date('Y-m', strtotime($_GET['mois'] . '+1 month'));
                echo '<iframe height="1024px" width="100%" src="' . URL_LINK_REPORT . '?%2fAGC%2fAGC+Bilan%2fBilan&rs:Command=Render&rc:toolbar=false&Commercial=' . $_GET['commercial'] . '&Debut=' . $_GET['mois'] . '&Fin=' . $fin . '"></iframe>';
                break;

            case 'homonyme':
                $msg = '';
                if (Candidature::homonym($_GET['prenom'], $_GET['nom'])) {
                    $msg = '<p class="rouge">' . HOMONYME . '</p>';
                }
                echo $msg;
                break;

            case 'supprimerHc':
                Candidature::deleteHistory($_GET['Id_candidature'], $_GET['Id_etat'], $_GET['date']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo json_encode(array('etat' => $candidature->Id_etat, 'history' => utf8_encode($candidature->history(1))));
                break;

            case 'validerHc':
                Candidature::validateHistory($_GET['Id_candidature'], $_GET['Id_etat'], $_GET['date'], $_GET['Id_utilisateur']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo json_encode(array('etat' => $candidature->Id_etat, 'history' => utf8_encode($candidature->history(1))));
                break;

            //suppression d'un enregistrement de l'historique des actions d'une candidature
            case 'supprimerHAction':
                $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, array());
                Candidature::deleteActionHistory($_GET['Id_candidature'], $_GET['Id_action_mener'], $_GET['date_action']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                if ($_GET['Id_positionnement'] == 0)
                    echo $candidature->actionHistory(1);
                else
                    echo $ressource->getPositioning(1);
                break;

            //validation des modifications d'un enregistrement de l'historique des actions d'une candidature	
            case 'validerHAction':
                $ressource = RessourceFactory::create('CAN_AGC', $this->Id_ressource, array());
                Candidature::validateActionHistory($_GET['Id_candidature'], $_GET['Id_action_mener'], $_GET['date_action'], $_GET['Id_positionnement']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                if ($_GET['Id_positionnement'] == 0)
                    echo $candidature->actionHistory(1);
                else
                    echo $ressource->getPositioning(1);
                break;

            case 'afficherTypeVal':
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->typeValidation();
                break;

            case 'afficherTypeEmbauche':
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->typeEmbauche();
                break;

            case 'prixVenteRessource':
                $ressource = RessourceFactory::create($_GET['type_ressource'], $_GET['Id_ressource'], null);
                echo '<input type="text" id="tarif_ressource' . $_GET['n_r'] . '" name="tarif_journalier[]" size="5" />';
                break;

            case 'coutJRessource':
                $ressource = RessourceFactory::create($_GET['type_ressource'], $_GET['Id_ressource'], null);
                if (isset($_GET['n_r']))
                    echo '<input type="text" id="cout_ressource' . $_GET['n_r'] . '" name="cout_journalier[]" value="' . $ressource->dailyCost() . '" size="5" />';
                else
                    echo 'Coût J.<br /><input type="text" value="' . $ressource->dailyCost() . '" name="cout_ressource" id="cout_ressource" />';
                break;

            case 'ajoutRessource':
                $nb = $_GET['nb'] + 1;
                $proposition = new Proposition('', '');
                echo $proposition->addResource($nb);
                break;

            case 'supprRessource':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreRessource' . $nb . '"><input type="hidden" id="nb_ressource" value=' . $nb . '></div>';
                break;
            
            case 'envoyerMailCD':              
                $contratDelegation = new ContratDelegation($_GET['Id_cd'], array());
            
                $cdsService = false;
                if ($contratDelegation->statut == 'A' || $contratDelegation->statut == 'R') {
                    $cdsService = $contratDelegation->ressourceIsCds();
                }

                if ($cdsService != false) {
                    $contratDelegation->envoyerMailCDS($cdsService);
                    $proposition = new Proposition($_GET['Id_proposition'], array());
                } else {
                    if($contratDelegation->Id_affaire == 0)
                        $contratDelegation->editWithoutCase(1);
                    else
                        $contratDelegation->edit(1);
                        $contratDelegation->envoyerMail();
                }
                $proposition = new Proposition($_GET['Id_proposition'], array());
                $msg = 'Votre e-mail a été correctement envoyé à ';
                $msg .= implode(', ', $_SESSION['dest1']);
                if($_SESSION['dest2'])
                    $msg .= ', ' . implode(', ', $_SESSION['dest2']);
                unset($_SESSION['dest1']);
                unset($_SESSION['dest2']);
                echo json_encode(array('msg' => utf8_encode($msg), 'html' => utf8_encode($proposition->resourceForm())));
                break;

            case 'ajoutRessourceInclus':
                $nb = $_GET['nb'] + 1;
                $proposition = new Proposition('', '');
                echo $proposition->addIncludedRessource($nb);
                break;

            case 'supprRessourceInclus':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreRessourceInclus' . $nb . '"><input type="hidden" id="nb_ressource_i" value=' . $nb . '></div>';
                break;

            case 'ajout':
                $nb = $_GET['nb'] + 1;
                $class = new $_GET['class']('', '');
                echo $class->add($nb);
                break;

            case 'suppr':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autre' . $_GET['class'] . $nb . '"><input type="hidden" id="nb_' . $_GET['class'] . '" value=' . $nb . '></div>';
                break;

            case 'ajoutSysteme':
                $nb = $_GET['nb'] + 1;
                echo Environnement::addSystemForm($nb);
                break;

            case 'supprSysteme':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreSysteme' . $nb . '"><input type="hidden" id="nb_systeme" value=' . $nb . '></div>';
                break;

            case 'ajoutReseau':
                $nb = $_GET['nb'] + 1;
                echo Environnement::addNetworkForm($nb);
                break;

            case 'supprReseau':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreReseau' . $nb . '"><input type="hidden" id="nb_reseau" value=' . $nb . '></div>';
                break;

            case 'ajoutPdt':
                $nb = $_GET['nb'] + 1;
                echo Environnement::addWorkStationForm($nb);
                break;

            case 'supprPdt':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autrePdt' . $nb . '"><input type="hidden" id="nb_pdt" value=' . $nb . '></div>';
                break;

            case 'ajoutProposition':
                $nb = $_GET['nb'] + 1;
                $propositon = new Proposition('', '');
                echo $propositon->ajoutProposition2($nb);
                break;

            case 'supprProposition':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreProposition' . $nb . '"><input type="hidden" id="nb_proposition" value=' . $nb . '></div>';
                break;

            case 'ajoutPropCom':
                $nb = $_GET['nb'] + 1;
                $propositon = new Proposition('', '');
                echo $propositon->ajoutProposition($nb);
                break;

            case 'ajoutPeriode':
                $_GET['n_pe'] = $_GET['n_pe'] + 1;
                $propositon = new Proposition('', '');
                echo $propositon->addPeriod($_GET['n_pr'], $_GET['n_pe'], $_GET['n_an']);
                break;

            case 'supprPeriode':
                $pr = $_GET['n_pr'];
                $an = $_GET['n_an'];
                $pe = $_GET['n_pe'] - 1;
                echo '<div id="pr' . $pr . '|pe' . $pe . '"><input type="hidden" id="pr-' . $pr . '-n_periode-an-' . $an . '" name="pr-' . $pr . '-n_periode-an-' . $an . '" value=' . $pe . '></div>';
                break;

            case 'ajoutAnnee':
                $_GET['n_an'] = $_GET['n_an'] + 1;
                $propositon = new Proposition('', '');
                echo $propositon->addYear($_GET['n_pr'], $_GET['n_an']);
                break;

            case 'supprAnnee':
                $pr = $_GET['n_pr'];
                $an = $_GET['n_an'] - 1;
                echo '<div id="pr' . $pr . '|an' . $an . '"><input type="hidden" id="pr-' . $pr . '-an" name="pr-' . $n_pr . '-an" value=' . $an . '></div>';
                break;

            case 'afficherIndemnites':
                if($_GET['version'] == 1)
                    echo Indemnite::getOldCheckboxList($_GET['Id_cd'], $_GET['type_indemnite']);
                elseif($_GET['version'] == 2)
                    echo Indemnite::getCheckboxList($_GET['Id_cd'], $_GET['indemnite_destination'], $_GET['indemnite_region'], $_GET['indemnite_type_deplacement']);
                break;

            case 'afficherCoordonnee':
                $affaire = new Affaire($_GET['Id_affaire'], array());
                echo $affaire->contactDetailsForm();
                break;

            case 'afficherListeCompte':
                $compte = CompteFactory::create(null, 'CG');
                echo '
		    Affaire :
                    <select name="Id_compte" id="Id_compte" onchange="changeCompte(this.value); infoCompte(this.value)" >
                        <option value="">' . CUSTOMERS_SELECT . '</option>
                        <option value="">-------------------------</option>
                        ' . $compte->getList($_GET['prefix']) . '
                    </select>';
                break;
                
            case 'afficherListeCompteCD':
                $lc = ($_GET['Id_compte']) ? true : false;
                if($_GET['type']=='agc')
                    $compte = CompteFactory::create(null, 'CG');
                elseif($_GET['type']=='sfc') {
                    if(!$_GET['Id_compte']) $_GET['Id_compte'] = 'SF-';
                    $compte = CompteFactory::create(null, $_GET['Id_compte']);
                }
                if($compte){
                    echo '
                    <select name="Id_compte" id="Id_compte" onchange="showCaseList(this.value, ' . $_GET['update'] . ');" >
                        <option value="">' . CUSTOMERS_SELECT . '</option>
                        <option value="">-------------------------</option>';
                    echo $compte->getList($_GET['prefix'], $lc);
                    echo '</select>';
                }
                break;
                
            case 'afficherLieuxPrestation':
                $LieuxPrestation = new LieuxPrestation(0);
                echo '
                    <select id="lieuxPresta" name="lieuxPresta" onChange="updateLieuMission(this)">
                        <option value="">Sélectionner un lieu de prestation</option>
                        ' . $LieuxPrestation->getList() . '
                    </select>';
                break;
            
            case 'afficherDetailsCommune':
                $LieuxPrestation = new LieuxPrestation(0);
                echo $LieuxPrestation->getCommunesDetails($_GET['id_communes']);
                break;

          case 'afficherDetailsLieuPrestation':
                $LieuxPrestation = new LieuxPrestation(0);
                echo $LieuxPrestation->getLieuPrestationDetails($_GET['lieuxPresta']);
                break;

            case 'updateCaseInformation':
                // Vérification si opportunité AGC ou Salesforce
                if(is_numeric($_GET['Id_affaire'])) {
                    $affaire = new Affaire($_GET['Id_affaire'], null);
                    $pole = Pole::getLibelle($affaire->Id_pole);
                    $type_contrat = TypeContrat::getLibelle($affaire->Id_type_contrat);
                    $agence = Agence::getLibelle($affaire->Id_agence);
                    $intitule = Intitule::getLibelle($affaire->Id_intitule);
                    $ca_affaire = Proposition::getCa(Affaire::lastProposition($affaire->Id_affaire));
                    $type_affaire['CG'] = "selected='selected'";
                    
                    $compte = CompteFactory::create(null, $affaire->Id_compte);
                    $mode_reglement = $compte->getModeReglement();
                    $adresse_facturation = $compte->getAdresseFacturation();
                    $contact_principal = $compte->getContactPrincipal();
                    $fonction_cprincipal = $compte->getFonctionContactPrincipal();
                    $nom_compte = $compte->nom;
                    $description = new Description($affaire->Id_description, array());
                    /*if ($description->resume) {
                        $this->tache = strip_tags(htmlenperso_decode($description->resume));
                    }*/
                    $description = strip_tags(htmlenperso_decode($description->resume));
                    $date_debut = $affaire->date_debut;
                    $date_fin_commande = $affaire->date_fin_commande;
                    $duree = $affaire->duree;
                    
                    $proposition = new Proposition(Affaire::lastProposition($_GET['Id_affaire']), array());
                    $prop = $proposition->consultation($affaire->Id_type_contrat, $affaire->Id_pole, false);
                    
                    $type_mission = '
                        <label><input type="radio" name="type_mission" value="Régie" /> Régie</label><br />
                        <label><input type="radio" name="type_mission" value="Forfait" /> Forfait</label><br />
                        <label><input type="radio" name="type_mission" value="Préembauche" /> Préembauche</label>';
                }
                else {
                    $affaire = new Opportunite($_GET['Id_affaire'], null);
                    $dates = $affaire->getRangeDates();

                    //Si pas de devis renseigné, on cherche le devis synchro, sinon on continue sans devis
                    $Id_devis = $_GET['Id_devis'];
                    if ($Id_devis == '' || $Id_devis == null) {
                        $Id_devis = $affaire->Id_devis;
                    }
                    
                    // Champs commun à tous les types d'opportunités SFC
                    $contactPrincipal = $affaire->getContactPrincipal();
                    $contact_principal = $contactPrincipal[0];
                    $fonction_cprincipal = $contactPrincipal[1];
                    $agence = $affaire->Id_agence;
                    // Champs concernant la politique de sécurité client
                    $politique_securite_demandee = $affaire->politique_securite_demandee;
                    $necessite_plan_prevention = $affaire->necessite_plan_prevention;
                    $equipement_securite_a_prevoir = $affaire->equipement_securite_a_prevoir;
                    $mission_implique_isolement = $affaire->mission_implique_isolement;
                    $formations_specifiques_exigees = $affaire->formations_specifiques_exigees;
                    $poste_implique_habilitation = $affaire->poste_implique_habilitation;
                    $presence_politique_client = $affaire->presence_politique_client;
                    $documents_Proservia_specifiques = $affaire->documents_Proservia_specifiques;
                    $smr = $affaire->smr;
                    
                    // Traitement en fonction du type d'opportunité
                    $version = new Version(370);
                    if(($affaire->Id_type_contrat == 'Assistance Technique' || $affaire->Id_type_contrat == 'Assistance Technique Proservia') && !$affaire->debloquer_devis && $affaire->date_creation >= $version->date_version) {
                        //Si pas de devis renseigné, on cherche le devis synchro, sinon on continue sans devis
                        if ($Id_devis != '' && $Id_devis != null) {
                            $devis = new Devis($Id_devis, null);
                            $profil = '
                                <select id="profil" name="profil" onChange="updateProfilInformation(this)">
                                    <option value="">' . PROFIL_SELECT . '</option>
                                    <option value="">----------------------------</option>
                                    ' . $devis->getProfilList() . '
                                </select><span id="desc"></span>';

                            $codeOtp = $devis->code_otp;
                            $reference_devis = $devis->reference_devis;
                            $intitule = $devis->nom;
                            $compte = CompteFactory::create('SF', $devis->Id_compte);
                            $nom_compte = $compte->nom;
                            $Id_cegid = $compte->Id_cegid;
                            $adresse_facturation = $compte->getAdresseFacturation();
                            $con1 = ContactFactory::create(null, $devis->Id_contact);
                            $contact1 = $con1->nom . ' ' . $con1->prenom;
                            $mode_reglement = $devis->condition_reglement;
                            $ca_affaire = $devis->ca;
                            $date_debut = $devis->date_debut;
                            $date_fin_commande = $devis->date_fin_commande;
                            $duree = $devis->duree;
                            $lieu_mission = $devis->adresse_fac . ' ' . ' ' . $devis->code_postal_fac . ' ' . $devis->ville_fac . ' ' . $devis->pays_fac;
                            $description = $devis->description;
                            $comHoraire = $devis->couverture_horaire;
                            $sous_titre = 'DEVIS N° : ' . $devis->reference_devis;
                            $type_mission = '
                                <label><input type="radio" name="type_mission" value="Régie" checked="checked" /> Régie</label><br />
                                <label><input type="radio" name="type_mission" value="Forfait" /> Forfait</label><br />
                                <label><input type="radio" name="type_mission" value="Préembauche" /> Préembauche</label>';
                        } else {
                            $erreur = 'Aucun devis créé ou votre dernier devis n\'est pas en statut "Accepté" ou "Signé".';
                        }
                    }
                    else {
                        if($affaire->Id_type_contrat ==  'Infogérance') {
                            $type_inf = 'Type d\'infogérance : ' . $affaire->type_infogerance . ' <input type="hidden" name="type_infogerance" id="type_infogerance" value="' . $affaire->type_infogerance . '"/><br /><br />';
                            $type_mission = '
                                <label><input type="radio" name="type_mission" value="Forfait" checked="checked" /> Forfait</label>';
                        }
                        else {
                            $type_mission = '
                                <label><input type="radio" name="type_mission" value="Régie" checked="checked" /> Régie</label><br />
                                <label><input type="radio" name="type_mission" value="Forfait" /> Forfait</label><br />
                                <label><input type="radio" name="type_mission" value="Préembauche" /> Préembauche</label>';
                        }
                        $profil = '
                            <select id="profil" name="profil" onChange="updateProfilInformation(this)">
                                <option value="">' . PROFIL_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $affaire->getProfilList() . '
                            </select><span id="desc"></span>';
                        
                        $intitule = $affaire->Id_intitule;
                        $compte = CompteFactory::create(null, $affaire->Id_compte);
                        $nom_compte = $compte->nom;
                        $Id_cegid = $compte->Id_cegid;
                        $adresse_facturation = $compte->getAdresseFacturation();
                        $con1 = ContactFactory::create(null, $affaire->Id_contact1);
                        $contact1 = $con1->nom . ' ' . $con1->prenom;
                        $con2 = ContactFactory::create(null, $affaire->Id_contact2);
                        $contact2 = $con2->nom . ' ' . $con2->prenom;
                        $mode_reglement = $compte->getModeReglement();
                        $ca_affaire = $affaire->ca;
                        $date_debut = $affaire->date_debut;
                        $date_fin_commande = $affaire->date_fin_commande;
                        $duree = $affaire->duree;
                        $description = $affaire->description;
                    }
                    $pole = $affaire->Id_pole;
                    $type_contrat = $affaire->Id_type_contrat;
                }
                
                // Champs commun à tous les types d'opportunités (AGC et SFC)
                $apporteur = formatPrenomNom($affaire->apporteur);
                
                $com = new Utilisateur($affaire->commercial, array());
                $commercial = $com->prenom . ' ' . $com->nom;
                
                $type_affaire['SF'] = "selected='selected'";
                $type = $affaire->type;
                
                if($affaire->reference_affaire_mere)
                    $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $affaire->reference_affaire_mere . ' - Sous affaire n° ' . $affaire->reference_affaire;
                else
                    $titre = 'CONTRAT DELEGATION de l\'affaire n° ' . $affaire->reference_affaire;
                
                if($politique_securite_demandee == 1) {
                    $politique_secu = 
                        '<br /><br />
                        <fieldset>
                            <legend id="politiqueSecu">POLITIQUE SECURITE</legend><br />
                            Politique de sécurité demandée : ' . (($politique_securite_demandee == 1) ? 'Oui' : 'Non') . '<input type="hidden" id="politique_securite_demandee" name="politique_securite_demandee" value="' . $politique_securite_demandee . '" /><br /><br />
                            Nécessite t-il un plan de prévention : ' . $necessite_plan_prevention . '<input type="hidden" id="necessite_plan_prevention" name="necessite_plan_prevention" value="' . $necessite_plan_prevention . '" /><br /><br />
                            Equipement de sécurité à prévoir : ' . $equipement_securite_a_prevoir . '<input type="hidden" id="equipement_securite_a_prevoir" name="equipement_securite_a_prevoir" value="' . $equipement_securite_a_prevoir . '" /><br /><br />
                            Mission implique l\'isolement travailleur : ' . (($mission_implique_isolement == 1) ? 'Oui' : 'Non') . '<input type="hidden" id="mission_implique_isolement" name="mission_implique_isolement" value="' . $mission_implique_isolement . '" /><br /><br />
                            Suivi Médical Renforcé à prévoir : ' . $smr . '<input type="hidden" id="smr" name="smr" value="' . $smr . '" /><br /><br />
                            Formations spécifiques exigées : ' . $formations_specifiques_exigees . '<input type="hidden" id="formations_specifiques_exigees" name="formations_specifiques_exigees" value="' . $formations_specifiques_exigees . '" /><br /><br />
                            Le poste implique une habilitation : ' . $poste_implique_habilitation . '<input type="hidden" id="poste_implique_habilitation" name="poste_implique_habilitation" value="' . $poste_implique_habilitation . '" /><br /><br />
                            Présence politique ou documents sécurité clients : ' . $presence_politique_client . '<input type="hidden" id="presence_politique_client" name="presence_politique_client" value="' . $presence_politique_client . '" /><br /><br />
                            Documents Proservia spécifiques : ' . $documents_Proservia_specifiques . '<input type="hidden" id="documents_Proservia_specifiques" name="documents_Proservia_specifiques" value="' . $documents_Proservia_specifiques . '" />
                        </fieldset>';
                }
                
                $html = 
                    '<legend>INFORMATIONS GENERALES</legend><br />
                    <input type="hidden" name="Id_cegid" id="Id_cegid" value="' . $Id_cegid . '"/>
                    <input type="hidden" name="Id_devis" id="Id_devis" value="' . $Id_devis . '"/>
                    Commercial : ' . $commercial . ' <input type="hidden" name="commercial" id="commercial" value="' . $commercial . '"/><br /><br />
                    Apporteur d\'affaire : ' . $apporteur . ' <input type="hidden" name="apporteur" id="apporteur" value="' . $apporteur . '"/><br /><br />
                    ' . $sdm . '
                    Agence : ' . $agence . ' <input type="hidden" name="agence" id="agence" value="' . $agence . '"/><br /><br />
                    Pôle : ' . $pole . ' <input type="hidden" name="pole" id="pole" value="' . $pole . '"/><br /><br />
                    Type de contrat : ' . $type_contrat . ' <input type="hidden" name="type_contrat" id="type_contrat" value="' . $type_contrat . '"/><br /><br />
                    ' . $type_inf . '
                    Intitule : ' . $intitule . ' <input type="hidden" name="intitule" id="intitule" value="' . $intitule . '"/><br /><br />
                    Client : ' . $nom_compte . ' <input type="hidden" name="compte" id="compte" value="' . $nom_compte . '"/><br /><br />
                    Client final : ' . $affaire->compte_final . ' <input type="hidden" name="compte_final" id="compte_final" value="' . $affaire->compte_final . '"/><input type="hidden" name="Id_compte_final" id="Id_compte_final" value="' . $affaire->Id_compte_final . '"/><br /><br />
                    <span class="infoFormulaire"> * </span> Adresse de Facturation : 
                    <input type="text" id="adresse_facturation" name="adresse_facturation" value="' . $adresse_facturation . '" size="50" />
                    <br /><br />
                    Contact client 1 : ' . $contact1 . ' <input type="hidden" name="contact1" id="contact1" value="' . $contact1 . '"/><br /><br />
                    Contact client 2 : ' . $contact2 . ' <input type="hidden" name="contact2" id="contact2" value="' . $contact2 . '"/><br /><br />
                    <span class="infoFormulaire"> * </span> Nom de la personne habilitée à signer les contrats : 
                    <input type="text" id="contact_principal" name="contact_principal" value="' . $contact_principal . '" />
                    <br /><br />
                    <span class="infoFormulaire"> * </span> Fonction :
                    <input type="text" id="fonction_cprincipal" name="fonction_cprincipal" value="' . $fonction_cprincipal . '" />
                    <br /><br />
                    Condition de règlement :
                    ' . $mode_reglement . ' <input type="hidden" name="mode_reglement" id="mode_reglement" value="' . $mode_reglement . '"/>
                    <br /><br />
                    CA Opportunité :
                    ' . number_format($ca_affaire, 0, ',', ' ') . '&euro; <input type="hidden" name="ca_affaire" id="ca_affaire" value="' . $ca_affaire . '"/>
                    <br />
                    ' . $prop . '
                    <br />
                    <span id="infoGen">
                        <span class="infoFormulaire"> * </span> Type mission :<br />
                        ' . $type_mission . '
                        <br /><br />
                        <span class="infoFormulaire"> * </span> Indemnités à refacturer :
                        <select name="indemnites_ref">
                            <option value=""></option>
                            <option value="Oui">Oui</option>
                            <option value="Non">Non</option>
                        </select>
                         <br /><br />
                        Code OTP :
                    <input type="text" id="code_otp" name="code_otp" value="' . $codeOtp . '" />
                        <br />
                    </span>';
            
                echo json_encode(array(
                    'profil' => utf8_encode($profil), 
                    'date_debut' => DateMysqltoFr($date_debut), 
                    'date_fin' => DateMysqltoFr($date_fin_commande),
                    'duree' => $duree,
                    'lieu_mission' => utf8_encode($lieu_mission),
                    'reference_affaire' => $affaire->reference_affaire,
                    'reference_affaire_mere' => $affaire->reference_affaire_mere,
                    'reference_devis' => $reference_devis,
                    'reference_bdc' => $affaire->Id_bdc,
                    'date_min' => $dates['date_min'],
                    'date_max' => $dates['date_max'],
                    'infoOpp' => utf8_encode($html),
                    'description' => utf8_encode($description),
                    'type' => $type,
                    'titre' => utf8_encode($titre),
                    'sous_titre' => utf8_encode($sous_titre),
                    'commentaire_horaire' => utf8_encode($comHoraire),
                    'politique_secu' => utf8_encode($politique_secu),
                    'erreur' => utf8_encode($erreur)
                ));
                break;
                
            case 'updateTrainingCaseInformation':
                $affaire = new Opportunite($_GET['Id_affaire'], null);
                $commercial = new Utilisateur($affaire->commercial, array());
                $compte = CompteFactory::create(null, $affaire->Id_compte);
                $contact1 = ContactFactory::create(null, $affaire->Id_contact1);
                $contact2 = ContactFactory::create(null, $affaire->Id_contact2);
                $apporteur = formatPrenomNom($affaire->apporteur);

                $produits .= '<br /><table class="hovercolored borderedtable">
                    <tr>
                        <th style="font-size:11px; width:20%"></th>
                        <th style="font-size:11px; width:15%">Quantité (unité)</th>
                        <th style="font-size:11px; width:10%">Frais Dép.</th>
                        <th style="font-size:11px; width:15%">Coût de revient Total</th>
                        <th style="font-size:11px; width:15%">Prix vente total</th>
                        <th style="font-size:11px; width:10%">Marge</th>
                        <th style="font-size:11px;">Description de la ligne</th>
                    </tr>';
                
                foreach ($affaire->profil as $ligne) {
                    $produits .= '<tr class="row">';
                    $produits .= '<td>' . $ligne['nom'] . '</td>';
                    $produits .= '<td>' . $ligne['quantite'] . '</td>';
                    $produits .= '<td>' . $ligne['frais'] . '</td>';
                    $produits .= '<td>' . $ligne['cout_revient_total'] . '</td>';
                    $produits .= '<td>' . $ligne['prix_vente_total'] . '</td>';
                    $produits .= '<td>' . $ligne['marge'] . '</td>';
                    $produits .= '<td>' . $ligne['description'] . '</td>';
                    $produits .= '</tr>';
                }
                $produits .= '</table>';
 
                $html = '
                    <fieldset>
                        <legend>Informations compte</legend><br />
                        <div class="left">
                            Client : ' . $compte->nom . ' <input type="hidden" name="compte" id="compte" value="' . $compte->nom . '"/><br />
                            ' . $compte->infoCompte() . '
                        </div>
                    </fieldset><br />
                    <fieldset>
                        <legend>Informations opportunité</legend><br />
                        <div class="left">
                            Commercial : ' . $commercial->prenom . ' ' . $commercial->nom . '<br />
                            Apporteurd\'affaire : ' . $apporteur . '<br />
                            Agence : ' . $affaire->Id_agence . '<br />
                            Pôle : ' . $affaire->Id_pole . '<br />
                            Type de contrat : ' . $affaire->Id_type_contrat . '<br />
                            Type d\'opportunité : ' . $affaire->type_opportunite . '<br />
                            Intitule : ' . $affaire->Id_intitule . '<br />
                            Statut : ' . $affaire->Id_statut . '<br />
                            Adresse de Facturation : ' . $compte->getAdresseFacturation() . '
                            <input type="hidden" id="adresse_facturation" name="adresse_facturation" value="' . $compte->getAdresseFacturation() . '" size="50" /><br />
                            Contact client 1 : ' . $contact1->nom . ' ' . $contact1->prenom . '<br />
                            Contact client 2 : ' . $contact2->nom . ' ' . $contact2->prenom . '<br />
                            Condition de règlement :' . $compte->getModeReglement() . '<br />
                        </div>
                        <div class="right">
                            Date limite de réponse : <br />
                            Date de soutenance : <br />
                            Date début de prestation : ' . FormatageDate($affaire->date_debut) . '<br />
                            Date fin de prestation : ' . FormatageDate($affaire->date_fin_commande) . '<br />
                            Durée : ' . $affaire->duree . '<br />
                        </div>
                    </fieldset><br />
                    <fieldset>
                        <legend>Informations financière</legend><br />
                        <div class="left">
                            Montant global : ' . number_format($affaire->ca, 0, ',', ' ') . '&euro;<br />
                            Revenu escompté : ' . number_format($affaire->ca_prev, 0, ',', ' ') . '&euro;<br />
                            Probabilité (%) : ' . $affaire->probabilite . '<br />
                            ' . $produits . '
                        </div>
                    </fieldset>';
                $html .= $cout;

                echo json_encode(array(
                    'profil' => utf8_encode($profil), 
                    'date_debut' => DateMysqltoFr($affaire->date_debut), 
                    'date_fin' => DateMysqltoFr($affaire->date_fin_commande),
                    'duree' => $affaire->duree,
                    'lieu_mission' => $agence,
                    'reference_affaire' => $affaire->reference_affaire,
                    'infoOpp' => utf8_encode($html),
                    'erreur' => ''
                ));
                break;
                
            case 'afficherListeAffaire':
                //if($_GET['update'])
                    $onChange = 'onchange="displayLieuxPrestation();updateCaseInformation(this.value);"';
                if($_GET['Id_compte']) {
                    $compte = CompteFactory::create(null, $_GET['Id_compte']);
                    $c = $compte->getListOp($_GET['Id_affaire'], '', $_GET['reference_affaire_mere']);
                }
                echo '<span class="infoFormulaire"> * </span>Opportunité : 
                    <select name="Id_affaire" id="Id_affaire" ' . $onChange . ' >
                        <option value="">Sélectionner une opportunité</option>
                        <option value="">-------------------------</option>
                        ' . $c . '
                    </select>';
                break;
                
            case 'afficherListeAffaireFormation':
                //if($_GET['update'])
                    $onChange = 'onchange="displayLieuxPrestation();updateCaseInformation(this.value);"';
                if($_GET['Id_compte']) {
                    $compte = CompteFactory::create(null, $_GET['Id_compte']);
                    $c = $compte->getListOpFor($_GET['Id_affaire'], '', $_GET['reference_affaire_mere']);
                }
                echo '<span class="infoFormulaire"> * </span>Opportunité : 
                    <select name="Id_affaire" id="Id_affaire" ' . $onChange . ' >
                        <option value="">Sélectionner une opportunité</option>
                        <option value="">-------------------------</option>
                        ' . $c . '
                    </select>';
                break;

            case 'creerAffaire':
                $affaire = new Affaire('', $_GET);
                echo $affaire->save();
                $tab['Id_affaire'] = $_SESSION['affaire'];
                $tab['chiffre_affaire'] = $_GET['chiffre_affaire'];
                $planning = new Planning('', $tab);
                echo $planning->save();
                $description = new Description('', $tab);
                echo $description->save();
                $proposition = new Proposition('', $tab);
                echo $proposition->save();
                break;
            
            case 'sauvegarderPonderation':
                $proposition = new Proposition($_GET['Id_proposition'], array('ponderation' => $_POST['value'], 'chiffre_affaire' => $_GET['chiffre_affaire']));
                $proposition->saveWeighting();
                echo $_POST['value'];
                break;

            case 'afficherListeCompte2':
                $compte = CompteFactory::create('CG');
                if ($_GET["refresh"]) {
                    $aff = 'afficherRdv();';
                    $_GET['nb'] = 1;
                    $id = 'id="Id_compte"';
                } else {
                    $id = 'id="idco' . $_GET["nb"] . '"';
                }
                echo '
		    <span class="infoFormulaire"> * </span>
			<select name="Id_compte[]" ' . $id . ' onchange="contactCompte(this.value,' . $_GET["nb"] . ');' . $aff . '">
                <option value="">Compte</option>
                <option value="">-------------------------</option>
                ' . $compte->getList($_GET['prefix']) . '
            </select>
';
                break;

            case 'afficherListeContact':
                $contact = ContactFactory::create(null, 'CG-');
                $ouv[Affaire::isNewCustomer($_GET['Id_affaire'])] = 'checked="checked"';
                echo '
            <span class="infoFormulaire"> * </span> Contact 1 :
            <select name="Id_contact1">
                <option value="">' . CONTACT_SELECT . '</option>
                <option value="">-------------------------</option>
                ' . $contact->getList($_GET['Id_compte']) . '
            </select>
            <br /><br />
            Contact 2 :
            <select name="Id_contact2">
                <option value="">' . CONTACT_SELECT . '</option>
                <option value="">-------------------------</option>
                ' . $contact->getList($_GET['Id_compte']) . '
            </select>
			<br /><br />
			Ouverture de compte :
			' . YES . ' <input type="radio" name="ouverture_compte" value="1" ' . $ouv['1'] . ' />
			' . NO . ' <input type="radio" name="ouverture_compte" value="0" ' . $ouv['0'] . ' />
			<br />
';
                break;

            case 'contactCompte':
                $nature = CompteFactory::create(null, $_GET['Id_compte']);
                $type[$compte->nature] = 'selected="selected"';
                $contact = ContactFactory::create('CG');
                if ($_GET["refresh"]) {
                    $aff = 'onchange="afficherRdv();"';
                    $id = 'id="Id_contact"';
                } else {
                    $typeCompte = '
				&nbsp;&nbsp;
			    <span class="infoFormulaire"> * </span> <select name="type[]" id="type' . $_GET['nb'] . '">
				    <option value="">Type</option>
				    <option value="">------------</option>
				    <option value="Client" ' . $type['Client'] . '>Client</option>
				    <option value="Prospect" ' . $type['Prospect'] . '>Prospect</option>
			    </select>';
                }
                echo '
            <select name="Id_contact[]" ' . $id . ' ' . $aff . '>
                <option value="">Contact</option>
                <option value="">-------------------------</option>
                ' . $contact->getList($_GET['Id_compte']) . '
            </select>
			' . $typeCompte . '
';
                break;

            case 'lientc':
                echo TypeContrat::getMenuList($_GET['Id_pole']);
                break;

            case 'statutPerdu':
                $affaire = new Affaire($_GET['Id_affaire'], array());
                echo $affaire->lostReason();
                break;

            case 'statutPerduSearch':
                $affaire = new Affaire('', array());
                if ($_GET['Id_statut'] == 6)
                    echo '<select id="Id_raison_perdue" name="Id_raison_perdue" onchange="afficherAffaire()">
				    <option value="">' . REASON_SELECT . '</option>
				    <option value="">----------------------------------</option>
				    ' . $affaire->getLostReasonList($_SESSION['filtre']['Id_raison_perdue']) . '
				</select>';
                else
                    echo '';
                break;

            case 'deleteHistoriqueStatut':
                $date = urldecode($_GET['date']);
                Affaire::deleteStatusHistory($_GET['Id_affaire'], $_GET['Id_statut'], $date);
                $affaire = new Affaire($_GET['Id_affaire'], array());
                echo $affaire->formStatusHistory();
                break;

            case 'validHistoriqueStatut':
                $date = urldecode($_GET['date']);
                Affaire::validStatusHistory($_GET['Id_affaire'], $_GET['Id_statut'], $_GET['date'], $_GET['Id_utilisateur']);
                $affaire = new Affaire($_GET['Id_affaire'], array());
                echo $affaire->formStatusHistory();
                break;
            
            case 'duplicateRessourceProposition':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                $cd->duplicate();
                
                $proposition = new Proposition($_GET['Id_proposition'], array());
                echo $proposition->resourceForm();
                break;
                
            case 'deleteRessourceProposition':
                if($_GET['Id_cd']) {
                    $cd = new ContratDelegation($_GET['Id_cd'], array());
                    $cd->delete();
                }
                Proposition::deleteRessourceProposition($_GET['Id_prop_ress']);
                $proposition = new Proposition($_GET['Id_proposition'], array());
                echo $proposition->resourceForm();
                break;

            case 'updateEtatCandidature':
                Candidature::updateEtat($_GET['Id_etat'], $_GET['Id_candidature']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->history(1);
                break;

            //Mise à jour de l'affichage des actions menées sur une candidature
            case 'updateActionCandidature':
                Candidature::updateAction($_GET['Id_action_mener'], $_GET['Id_candidature']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->actionHistory($modification);
                break;

            case 'afficherInfoCompte':
                $compte = CompteFactory::create(null, $_GET['Id_compte']);
                echo $compte->infoCompte();
                break;

            case 'coutJCD':
                $ressource = RessourceFactory::create($_GET['type_ressource'], $_GET['Id_ressource'], null);
                echo '<input type="text" id="cout_journalier_ress" name="cout_journalier_ress" value="' . $ressource->dailyCost() . '" size="4" />  &euro;';
                break;

            case 'afficherListePole':
                $pole = new Pole('', '');
                echo $pole->getList();
                break;

            case 'infoRessourceCD':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                echo $cd->infoRessourceCD($_GET['type_ressource'], $_GET['Id_ressource']);
                break;
            
            case 'validateCD':
                $contratDelegation = new ContratDelegation($_GET['Id_cd'], array());
                if ($contratDelegation->statut == 'I' || $contratDelegation->statut == 'U') {//validation CDS
                    //verifier droits
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
                } else {
                    //verifier droits
                    $contratDelegation->edit(1);//génération pdf ?
                    $contratDelegation->validate();
                }
                break;
            
            case 'rejectCDForm':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                echo $cd->rejectForm();
                break;
            
            case 'rejectCD':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                $cd->reject($_GET['Id_refus'], $_GET['commentaire_refus']);
                break;
            
            case 'reopenCDForm':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                echo $cd->reopenForm();
                break;
            
            case 'reopenCD':
                $cd = new ContratDelegation($_GET['Id_cd'], array());
                $cd->reopen($_GET['Id_reouverture'], $_GET['commentaire_reouverture']);
                break;

            case 'ODMEnvoye':
                $odm = new OrdreMission($_GET['Id_ordre_mission'], array());
                $odm->send($_GET['date_envoi'], $_GET['envoiOK']);
                break;

            case 'ODMRetour':
                OrdreMission::sendBack($_GET['Id_ordre_mission'], $_GET['date_retour'], $_GET['retourOK']);
                break;
            
            case 'updateODMTime':
                $salarie = new Salarie($_GET['Id_ressource'], null);
                echo OrdreMission::contractStatusTimeMsg($salarie->statut, $_GET['horaire'], $salarie->societe);
                break;

            case 'DCValide':
                $dc = new DemandeChangement($_GET['Id_demande_changement'], array());
                echo $dc->validate($_GET['date_validation'], $_GET['commentaire_valideur'], $_GET['table']);
                break;

            case 'DCIntegre':
                $dc = new DemandeChangement($_GET['Id_demande_changement'], array());
                echo $dc->integrate($_GET['date_integration'], $_GET['table']);
                break;
            
            case 'DCReject':
                $dc = new DemandeChangement($_GET['Id_demande_changement'], array());
                echo $dc->reject($_GET['commentaire_valideur']);
                break;
            
            case 'DCReopen':
                $dc = new DemandeChangement($_GET['Id_demande_changement'], array());
                echo $dc->reopen();
                break;

            case 'demande_changement':
                $dc = new DemandeChangement(null, $_GET);
                echo $dc->form();
                break;

            case 'joursOuvresRessource':
                echo '<input type="text" id="duree_ressource' . $_GET['Id'] . '" name="duree_ressource[]" value="' . workingDays(DateMysqltoFr($_GET['debut']), DateMysqltoFr($_GET['fin'])) . '" size="2" />';
                break;

            case 'joursOuvresRessourceInclus':
                echo '<input type="text" id="duree_ressource_i' . $_GET['Id'] . '" name="duree_ressource_i[]" value="' . workingDays(DateMysqltoFr($_GET['debut']), DateMysqltoFr($_GET['fin'])) . '" size="2" />';
                break;

            case 'joursOuvresPlanning':
                echo '<input type="text" id="duree" name="duree" value="' . workingDays(DateMysqltoFr($_GET['debut']), DateMysqltoFr($_GET['fin'])) . '" size="1">';
                break;

            case 'appliquerDroitGroupeAdZoneAcces':
                GestionDroit::applyGroupAdZoneAccesRight($_GET['Id_groupe_ad'], $_GET['Id_zone_acces']);
                break;

            case 'appliquerDroitGroupeAdMenu':
                GestionDroit::applyGroupAdMenuRight($_GET['Id_groupe_ad'], $_GET['Id_menu']);
                break;

            case 'initForm':
                unset($_SESSION['filtre']);
                echo call_user_func(array($_GET['c'], 'searchForm'));
                break;

            case 'calculProjetForfaitaire':
                $cout_total = $_GET['cout_phase1'] + $_GET['cout_phase2'] + $_GET['cout_phase3'] + $_GET['cout_phase4'] + $_GET['cout_licence'] + $_GET['cout_materiel'] + $_GET['cout_autre'];
                $ca_total = $_GET['ca_phase1'] + $_GET['ca_phase2'] + $_GET['ca_phase3'] + $_GET['ca_phase4'] + $_GET['ca_licence'] + $_GET['ca_materiel'] + $_GET['ca_autre'];
                if ($ca_total != 0) {
                    $marge_total = round((100 * ($ca_total - $cout_total) / $ca_total), 2);
                } else {
                    $marge_total = 0;
                }
                echo '<td><h2>TOTAL PROJET</h2></td>
            <td></td>
            <td><h3>Coût de revient global</h3><h2> ' . $cout_total . ' &euro;</h2></td>
            <td><h3>CA global affaire</h3><h2> ' . $ca_total . ' &euro;</h2></td>
            <td><h3>Marge Théorique</h3><h2> ' . $marge_total . ' (%)</h2></td>
            <input type="hidden" name="cout_total[]" value="' . $cout_total . '" />
            <input type="hidden" name="marge_totale[]" value="' . $marge_total . '" />
            <input type="hidden" name="chiffre_affaire[]" value="' . $ca_total . '" />';
                break;

            case 'calculMargeForfaitaire':
                if ($_GET['ca_phase1'] != 0) {
                    $marge = 100 * ($_GET['ca_phase1'] - $_GET['cout_phase1']) / $_GET['ca_phase1'];
                }
                if ($_GET['ca_phase2'] != 0) {
                    $marge = 100 * ($_GET['ca_phase2'] - $_GET['cout_phase2']) / $_GET['ca_phase2'];
                }
                if ($_GET['ca_phase3'] != 0) {
                    $marge = 100 * ($_GET['ca_phase3'] - $_GET['cout_phase3']) / $_GET['ca_phase3'];
                }
                if ($_GET['ca_phase4'] != 0) {
                    $marge = 100 * ($_GET['ca_phase4'] - $_GET['cout_phase4']) / $_GET['ca_phase4'];
                }
                if ($_GET['ca_licence'] != 0) {
                    $marge = 100 * ($_GET['ca_licence'] - $_GET['cout_licence']) / $_GET['ca_licence'];
                }
                if ($_GET['ca_materiel'] != 0) {
                    $marge = 100 * ($_GET['ca_materiel'] - $_GET['cout_materiel']) / $_GET['ca_materiel'];
                }
                if ($_GET['ca_autre'] != 0) {
                    $marge = 100 * ($_GET['ca_autre'] - $_GET['cout_autre']) / $_GET['ca_autre'];
                }
                echo round($marge, 2) . '%';
                break;

            case 'afficherEtatTech':
                if ($_GET['Id_pole'] == 1 && ($_GET['Id_type_contrat'] == 2 || $_GET['Id_type_contrat'] == 3)) {
                    $write = 'disabled="disabled"';
                    $hiddenRQ = '<input type="hidden" name="resp_qualif" value="' . $_GET['resp_qualif'] . '">';
                    $hiddenSDM = '<input type="hidden" name="sdm" value="' . $_GET['sdm'] . '">';
                    $hiddenRT1 = '<input type="hidden" name="resp_tec1" value="' . $_GET['resp_tec1'] . '">';
                    $hiddenRT2 = '<input type="hidden" name="resp_tec2" value="' . $_GET['resp_tec2'] . '">';
                    if (Utilisateur::getTechnicalModuleRight($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur)) {
                        $write = $hiddenRQ = $hiddenSDM = $hiddenRT1 = $hiddenRT2 = '';
                    }
                    $resp_qualif = new Utilisateur($_GET['resp_qualif'], array());
                    $sdm = new Utilisateur($_GET['sdm'], array());
                    $resp_tec1 = new Utilisateur($_GET['resp_tec1'], array());
                    $resp_tec2 = new Utilisateur($_GET['resp_tec2'], array());
                    echo '
	            <hr />
				<h2><img src="' . IMG_HELP . '" onmouseover="return overlib(\'<div class=commentaire>' . HELP_TECHNICAL_MODULE . '</div>\', FULLHTML);" onmouseout="return nd();"></img> Etat technique</h2><br />
				Responsable de la qualification :
			    <select name="resp_qualif" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_qualif->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_qualif->getList('OP') . '
	            </select>
	            ' . $hiddenRQ . '
				&nbsp;&nbsp;
				Service Delivery Manager :
			    <select name="sdm" ' . $write . '>
	                <option value="">' . SDM_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $sdm->getList('COM') . '
					<option value="">-------------------------</option>
					' . $sdm->getList('OP') . '
	            </select>
	            ' . $hiddenSDM . '
				<br /><br />
				Responsable technique 1 :
			    <select name="resp_tec1" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_tec1->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_tec1->getList('OP') . '
	            </select>
	            ' . $hiddenRT1 . '
				&nbsp;&nbsp;
				Responsable technique 2 :
			    <select name="resp_tec2" ' . $write . '>
	                <option value="">' . MANAGER_SELECT . '</option>
				    <option value="">-------------------------</option>
	                ' . $resp_tec2->getList('COM') . '
					<option value="">-------------------------</option>
					' . $resp_tec2->getList('OP') . '
	            </select>
	            ' . $hiddenRT2 . '
	            <br /><br />
				Nombre de jours de charges estimés :
			    <input type="text"  $write name="nb_jour_estime" value="' . $_GET['nb_jour_estime'] . '" size="2" />';
                } else {
                    echo '';
                }
                break;

            case 'afficherEnvTech':
                if ($_GET['Id_type_contrat'] != 1) {
                    //Modification pour le pole formation
                    if (!($_GET['Id_type_contrat'] == 3 && $_GET['Id_pole'] == 3)) {
                        $environnement = new Environnement($_GET['Id_environnement'], array());
                        echo '
						<h2 onclick="toggleZone(' . "'competence'" . ')" class="cliquable">Environnement Technique</h2><br />
						<div id="competence" style="display:none">
							' . $environnement->form($_GET['Id_type_contrat'], $_GET['Id_pole']) . '
						</div>
						<div class="clearer"><hr /></div><br />';
                    }
                } elseif ($_GET['Id_type_contrat'] == 1 && ($_GET['Id_pole'] == 6 || $_GET['Id_pole'] == 7)) {
                    $environnement = new Environnement($_GET['Id_environnement'], array());
                    echo '
						<h2 onclick="toggleZone(' . "'competence'" . ')" class="cliquable">Environnement Technique</h2><br />
						<div id="competence" style="display:none">
							' . $environnement->form($_GET['Id_type_contrat'], $_GET['Id_pole']) . '
						</div>
						<div class="clearer"><hr /></div><br />';
                } else {
                    echo '';
                }
                break;

            case 'afficherIntituleAffaire':
                $intitule = new Intitule($_GET['Id_intitule'], array());
                echo 'Intitulé : 
					<select name="Id_intitule">
						<option value="">' . TITLE_SELECT . '</option>
						<option value="">-------------------------</option>
							' . $intitule->getList($_GET['Id_pole']) . '
					</select>
				<br /><br />';
                break;


            ########  CAS CONCERNANT LES DEMANDES DE RESSOURCE  ########

            case 'consulterDemandeRessource':
                $_SESSION['filtre'] = $_GET;
                $_SESSION['filtre']['agenceDemandesRessource'] = explode(';', $_GET['agenceDemandesRessource']);
                echo DemandeRessource::search($_SESSION['filtre']['Id_affaire_demande'], $_SESSION['filtre']['profil'], $_SESSION['filtre']['statut'], $_SESSION['filtre']['dr_debut'], $_SESSION['filtre']['dr_fin'], $_SESSION['filtre']['agenceDemandesRessource'], $_SESSION['filtre']['ic'], $_SESSION['filtre']['cr'], $_SESSION['filtre']['archive'], $_SESSION['filtre']['action'], $_SESSION['filtre']['h_cr'], $_SESSION['filtre']['h_date_debut'], $_SESSION['filtre']['h_date_fin'], $_SESSION['filtre']['h_statut'], $_SESSION['filtre']['prioritaire'], $_SESSION['filtre']['type_recrutement'], $_SESSION['filtre']['Id_demande_ressource'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));
                break;

            case 'ajoutCandidatDemandeRessource':
                $nb = $_GET['nb'] + 1;
                $dr = new DemandeRessource('', array('candidats' => array('Id_demande_ressource' => $_GET['Id_demande'], 'Id_ressource' => $_GET['Id_ressource'],
                                'Id_cr' => $_GET['Id_cr'], 'commentaire' => $_GET['commentaire'],
                                'date' => DateMysqltoFr($_GET['dateCandidat'], 'mysql'), 'Id_candidat' => $_GET['Id_candidat'])));
                if ($_GET['Id_demande']) {
                    $dr->saveCandidat();
                    echo $dr->addApplicantRessource($nb, 0);
                } else {
                    echo $dr->addApplicantRessource($nb, 1);
                }
                break;

            case 'majCandidatDemandeRessource':
                $dr = new DemandeRessource('', array('candidats' => array('Id_demande_ressource' => $_GET['Id_demande'], 'Id_ressource' => $_GET['Id_ressource'],
                                'Id_cr' => $_GET['Id_cr'], 'commentaire' => $_GET['commentaire'],
                                'date' => DateMysqltoFr($_GET['dateCandidat'], 'mysql'), 'Id_candidat' => $_GET['Id_candidat'])));
                $dr->saveCandidat();
                break;

            case 'suppressionCandidatDemandeRessource':
                DemandeRessource::deleteApplicant($_GET['Id_candidat']);
                break;

            case 'recapDemandeRessource':
                $idDemande = array();
                array_push($idDemande, $_GET['Id_demande_ressource']);
                $dr = new DemandeRessource($_GET['Id_demande_ressource'], array());
                DemandeRessource::edit(serialize($idDemande), $dr->Id_ic);
                $ic = formatPrenomNom($dr->Id_ic);
                $cr = formatPrenomNom($dr->Id_cr);
                $hdrs = array(
                    'From' => MAIL_CONTACT,
                    'Subject' => 'Récapitulatif d\'une demandes de ressource',
                    'To' => $dr->Id_ic . '@proservia.fr'
                );
                $text = <<<EOT
Bonjour {$ic},
				
Voici le récapitulatif d'une de vos demandes de ressource en cours.
Celle-ci vous est envoyé par {$cr}.
EOT;
                $crlf = "\n";
                $file = $dr->Id_ic . '.pdf';

                $mime = new Mail_mime($crlf);
                $mime->setTXTBody($text);
                $mime->addAttachment($file, 'application/pdf');

                $body = $mime->get();
                $hdrs = $mime->headers($hdrs);

                // Create the mail object using the Mail::factory method
                $params['host'] = SMTP_HOST;
                $params['port'] = SMTP_PORT;
                $mail_object = Mail::factory('smtp', $params);

                $send = $mail_object->send($dr->Id_ic . '@proservia.fr', $hdrs, $body);
                if (PEAR::isError($send)) {
                    print($send->getMessage());
                }
                unlink($file);
                break;

            case 'majEtatCandidat':
                $db = connecter();
                $ligne = $db->query('SELECT ec.libelle, c.Id_candidature,lien_cv FROM candidature c 
        				LEFT JOIN etat_candidature ec ON ec.Id_etat_candidature = c.Id_etat 
        				WHERE c.Id_ressource = ' . mysql_real_escape_string($_GET['Id_ressource']))->fetchRow();
                echo 'Etat candidature : ' . $ligne->libelle . ' - <a href="../membre/index.php?a=afficherCandidature&Id_candidature=' . $ligne->id_candidature . '">Lien vers la fiche du candidat</a>
			| <a href="../membre/index.php?a=ouvrirCV&cv=' . CV_DIR . $ligne->lien_cv . '><img src=' . IMG_CV . '></a></td>';
                break;

            case 'getListeClient':
                $demande = new DemandeRessource(0, array());
                echo $demande->getCustomerList($_GET['client']);
                break;

            case 'getCityList':
                echo Ville::getList($_GET['search']);
                break;

            case 'getListeCandidat':
                $candidats = new Candidature(0, array());
                echo $candidats->getApplicantList(3, $_GET['Id_ressource'], $_GET['search']);
                break;

            case 'getAffaire':
                $db = connecter();
                $id = $db->queryOne('SELECT Id_affaire FROM affaire WHERE Id_affaire =' . mysql_real_escape_string((int) $_GET['Id_affaire']));
                if (empty($id))
                    echo "false";
                break;

            case 'afficherCandidat':
                $db = connecter();
                $result = $db->query('SELECT c.Id_candidature, drc.Id_candidat, drc.Id_demande_ressource, DATE_FORMAT(drc.date, "%d-%m-%Y") as datefr, drc.commentaire, drc.Id_cr, r.nom, r.prenom,lien_cv FROM demande_ressource_candidat AS drc 
				INNER JOIN demande_ressource AS dr ON drc.Id_demande_ressource = dr.Id_demande_ressource 
				INNER JOIN ressource AS r ON drc.Id_ressource = r.Id_ressource 
				INNER JOIN candidature AS c ON c.Id_ressource = r.Id_ressource 
				WHERE dr.archive = 0 AND dr.Id_demande_ressource = ' . mysql_real_escape_string((int) $_GET['id']) . ' ORDER BY drc.date DESC');

                echo '<td colspan="6"><table>';
                while ($ligne = $result->fetchRow()) {
                    $result2 = $db->query('SELECT am.libelle FROM historique_action_candidature AS hac INNER JOIN action_mener am ON am.Id_action_mener = hac.Id_action_mener WHERE hac.Id_positionnement = ' . mysql_real_escape_string($ligne->id_candidat) . ' ORDER BY hac.date_action DESC');
                    $action = $result2->fetchOne();
                    $result3 = $db->query('SELECT ec.libelle FROM historique_candidature AS hc INNER JOIN etat_candidature ec ON ec.Id_etat_candidature = hc.Id_etat WHERE hc.Id_candidature = ' . mysql_real_escape_string($ligne->id_candidature) . ' ORDER BY hc.date DESC');
                    $etat = $result3->fetchOne();
                    $lien_cv = '';
                    if ($ligne->lien_cv) {
                        $lien_cv = $lien_cv = "<td><a href='../membre/index.php?a=ouvrirCV&cv=" . CV_DIR . $ligne->lien_cv . "'><img src=" . IMG_CV . "></a></td>";
                    }
                    echo '
				    <tr candidats' . $ligne->id_demande_ressource . '" style="background-color:#DDDDDD;"> 
			            <td>' . $ligne->datefr . '</td>
			            <td>' . $ligne->nom . ' ' . $ligne->prenom . '</td>
						<td>' . formatPrenomNom($ligne->id_cr) . '</td>
						<td>' . str_replace('"', "'", mysql_escape_string($ligne->commentaire)) . '</td>
						<td>' . $etat . '</td>
						<td>' . $action . '</td>
						<td><a href="../membre/index.php?a=afficherCandidature&Id_candidature=' . $ligne->id_candidature . '"><img src="../ui/images/view_inline.gif"/></a></td>
						' . $lien_cv . '
                    </tr>';
                }
                echo '</table></td>';
                break;

            case 'updateActionDemandeRessource':
                Candidature::updateAction($_GET['Id_action_mener'], $_GET['Id_candidature'], $_GET['Id_positionnement']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->actionDemandeRessource($_GET['Id_positionnement'], $_GET['nb']);
                break;

            //suppression d'un enregistrement de l'historique des actions d'une candidature
            case 'supprimerActionDemandeRessource':
                Candidature::deleteActionHistory($_GET['Id_candidature'], $_GET['Id_action_mener'], $_GET['date_action']);
                $candidature = new Candidature($_GET['Id_candidature'], array());
                echo $candidature->actionDemandeRessource($_GET['Id_positionnement'], $_GET['nb']);
                break;

            case 'supprimerStatutDR':
                DemandeRessource::deleteHistory($_GET['Id_demande_ressource'], $_GET['Id_statut'], $_GET['date']);
                $demande = new DemandeRessource($_GET['Id_demande_ressource'], array());
                echo json_encode(array('statut' => $demande->statut, 'history' => utf8_encode($demande->getStatusHistory())));
                break;

            case 'validerStatutDR':
                DemandeRessource::validateHistory($_GET['Id_demande_ressource'], $_GET['Id_statut'], $_GET['date'], $_GET['Id_utilisateur']);
                $demande = new DemandeRessource($_GET['Id_demande_ressource'], array());
                echo $demande->getStatusHistory();
                break;

            case 'updateStatutDemandeRessource':
                DemandeRessource::updateStatut($_GET['Id_statut'], $_GET['Id_demande_ressource']);
                $dr = new DemandeRessource($_GET['Id_demande_ressource'], array());
                echo $dr->getStatusHistory();
                break;
            
            case 'duplicateDemandeRessource':
                $dr = new DemandeRessource($_GET['Id'], null);
                echo $dr->duplicateForm();
                break;

            case 'updateResourceListByType':
                $ressource = RessourceFactory::create($_GET['type_ressource'], $_GET['Id_ressource'], array());
                echo $ressource->getList();
                break;

            ########  CAS CONCERNANT LE POLE FORMATION  ########
            ## Cas conduisant à la mise à jour de la zone planning d'une session ##
            //Ajax : mise à jour du champs 'nb_Jour' du planning d'une session après calcul de la durée de la session
            case 'joursOuvresSession':
                $duree = $_GET['duree'];
                echo '<input type="button" onclick="joursOuvresSession(1)" value="Calcul nb Jours">&nbsp;&nbsp;&nbsp;&nbsp;
					Durée de la session :&nbsp;&nbsp;
					<input type="text" id="nb_Jour" name="nb_Jour" value="' . $duree . '" size="8">&nbsp;&nbsp;jours<br/><br/>
				 ';
                break;

            //Ajax : ajout de champs 'dates ponctuelles' au planning d'une session
            case 'ajoutDateSession':
                $nb = $_GET['nb'] + 1;
                $planning = new PlanningSession('', '');
                echo $planning->addDate($nb);
                break;

            //Ajax : suppression de champs 'dates ponctuelles' au planning d'une session
            case 'supprDateSession':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreDate' . $nb . '"><input type="hidden" id="nb_Date" value=' . $nb . '></div>';
                break;

            //Ajax : ajout de champs 'période' au planning d'une session
            case 'ajoutPeriodeSession':
                $nb = $_GET['nb'] + 1;
                $planning = new PlanningSession('', '');
                echo $planning->addPeriod($nb);
                break;

            //Ajax : suppression de champs 'periode' au planning d'une session
            case 'supprPeriodeSession':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autrePeriode' . $nb . '"><input type="hidden" id="nb_Periode" value=' . $nb . '></div>';
                break;

            ## Cas conduisant à la mise à jour de la zone commerciale d'une session ##
            //Ajax : Mise à jour des charges dûes à la salle après selection de celle-ci
            case 'prixSalle':
                $Id_salle = $_GET['salle'];
                if ($Id_salle != '' && $Id_salle != -1) {
                    $salle = new Salle($Id_salle, array());
                    echo $salle->roomCost($_GET['nbJ']);
                } else {
                    if ($Id_salle == -1) {
                        $cout = $_GET['total'];
                    } else {
                        $cout = '';
                    }
                    echo '<table><tr>
						<td width="180" height="25">Coût de la salle : </td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25"><input type="text" id="coutSalleJ" name="coutSalleJ" onKeyup="calculcoutSTotal()" value="" size="8"> euros</td>
					</tr><tr>
						<td width="180" height="25"></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25"><div id="coutSalleTotal"><input type="text" id="coutSalle" name="coutSalle" onKeyup="chargeTotalSession()" value="' . $cout . '" size="8"> euros</div></td>
					</tr></table>';
                }
                break;

            //Ajax : Mise à jour des charges dûes au formateur après selection de celui-ci
            case 'prixFormateur':
                $Id_formateur = $_GET['formateur'];
                if ($Id_formateur != '' && $Id_formateur != -1) {
                    $formateur = new Formateur($Id_formateur, array());
                    echo $formateur->trainerCost($_GET['nbJ']);
                } else {
                    if ($Id_formateur == -1) {
                        $cout = $_GET['total'];
                    } else {
                        $cout = '';
                    }
                    echo '<table><tr>
						<td width="180" height="25">Coût formateur : </td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25"><input type="text" id="coutFormateurJ" name="coutFormateurJ" onKeyup="calculcoutFTotal()" value="" size="8"> euros</td>
					</tr><tr>
						<td width="180" height="25"> </td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25"><div id="coutFormateurTotal"><input type="text" id="coutFormateur" name="coutFormateur" onKeyup="chargeTotalSession()" value="' . $cout . '" size="8"> euros</div></td>
					</tr></table>';
                }
                break;

            //Ajax : calcul  du coût total des supports à partir du prix unitaire et mise à jour du champs équivalent dans la zone commerciale de la session
            case 'calculcoutSupTotal':
                $coutTotal = (float) (int) (($_GET['coutUnitaire'] * $_GET['nbInscrit']) * 100) / 100;
                echo '<input type="text" id="coutSupport" name="coutSupport" onKeyup="chargeTotalSession()" value="' . $coutTotal . '" size="8"> euros';
                break;

            //Ajax : calcul  du coût total du formateur à partir du prix journalier et mise à jour du champs équivalent dans la zone commerciale de la session
            case 'calculcoutFTotal':
                $coutTotal = (float) ((int) (($_GET['coutJ'] * $_GET['nbJ']) * 100)) / 100;
                echo '<input type="text" id="coutFormateur" name="coutFormateur" onKeyup="chargeTotalSession()" value="' . $coutTotal . '" size="8"> euros';
                break;

            //Ajax : calcul  du coût total de la salle à partir du prix journalier et mise à jour du champs équivalent dans la zone commerciale de la session
            case 'calculcoutSTotal':
                $coutTotal = (float) ((int) (($_GET['coutJ'] * $_GET['nbJ']) * 100)) / 100;
                echo '<input type="text" id="coutSalle" name="coutSalle" onKeyup="chargeTotalSession()" value="' . $coutTotal . '" size="8"> euros';
                break;

            //Ajax : mise à jour des données de la marge et du total des charges d'une session suite à la sélection d'un formateur ou d'une salle
            case 'majCharge':
                $Identifiant = $_GET['identifiant'];
                $cas = $_GET['cas'];
                //mise à jour suite à la selection d'un formateur 
                if ($cas == 1) {
                    //récupération du salaire dans la base de données si un seul formateur a été selectionné
                    if ($Identifiant != '' && $Identifiant != -1) {
                        $formateur = new Formateur($Identifiant, array());
                        $coutFormateur = $formateur->getSalary() * $_GET['nbJ'];
                    } else {
                        //récupération du coût total envoyé si plusieurs formateurs ont été sélectionnés
                        if ($Identifiant == -1) {
                            $coutFormateur = (float) $_GET['total'];
                        }
                        //Si aucun formateur n'est sélectionné
                        else {
                            $coutFormateur = '';
                        }
                    }
                    $coutSalle = (float) $_GET['salle'];
                }
                //mise à jour suite à la selection d'une salle
                else if ($cas == 2) {
                    //récupération du salaire dans la base de données si une seule salle a été selectionnée
                    if ($Identifiant != '' && $Identifiant != -1) {
                        $salle = new Salle($Identifiant, array());
                        $coutSalle = $salle->getPrice() * $_GET['nbJ'];
                        ;
                    } else {
                        //récupération du coût total envoyé si plusieurs salles ont été sélectionnées
                        if ($Identifiant == -1) {
                            $coutSalle = $_GET['total'];
                        }
                        //Si aucune salle n'est sélectionnée
                        else {
                            $coutSalle = '';
                        }
                    }
                    $coutFormateur = (float) $_GET['formateur'];
                }
                //Mise à jour par le bouton "calcul coût formateur et salle" de la zone proposition de la session
                else {
                    $coutFormateur = (float) $_GET['formateur'];
                    $coutSalle = (float) $_GET['salle'];
                }
                $charge = $coutSalle + $coutFormateur + (float) $_GET['autre'] + (float) $_GET['support'] + (float) $_GET['supportF'];
                $ca = $_GET['ca'];
                if ($ca != 0) {
                    $marge = (($ca - $charge) * 100) / $ca;
                    $marge = (float) ((int) ($marge * 100)) / 100;
                }
                echo '<br/><br/>
				<table> 
					<tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">CA total :</td>
						<td width="200" height="25" align="left"><input type="text" id="ca" name="ca" value="' . $ca . '" disabled=true > euros </td>
						<td width="100" height="25"></td>
					</tr><tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">Total des charges :</td>
						<td width="200" height="25" align="left"><input type="text" id="charge" name="charge" value="' . $charge . '" disabled=true > euros </td>
						<td width="100" height="25"></td>
					</tr>
					<tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">Marge :</td>
						<td width="200" height="25" align="left"><input type="text" id="marge" name="marge" value="' . (float) $marge . '" disabled=true > % </td>
						<td width="100" height="25"></td>
					</tr>
				</table>';

                break;

            //Ajax : mise à jour des données de la marge et du total des charges d'une session suite à une modification directe des charges
            case 'chargeSession':
                echo '<br/><br/>
				<table> 
					<tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">CA total :</td>
						<td width="200" height="25" align="left"><input type="text" id="ca" name="ca" value="' . $_GET['ca'] . '" disabled=true /> euros </td>
						<td width="100" height="25"></td>
					</tr><tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">Total des charges :</td>
						<td width="200" height="25" align="left"><input type="text" id="charge" name="charge" value="' . $_GET['charge'] . '" disabled=true /> euros </td>
						<td width="100" height="25"></td>
					</tr><tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left">Marge :</td>
						<td width="200" height="25" align="left"><input type="text" id="marge" name="marge" value="' . $_GET['marge'] . '" disabled=true /> % </td>
						<td width="100" height="25"></td>
					</tr>
				</table>';
                break;

            //Ajax : nouveau calcul de charge et de marge pour les affaires, pour afficher dans la session par le bouton 'Calcul affaire'
            case 'calculAffaire':
                $propSession = new PropSession($_GET['Id_propSession'], array());
                echo $propSession->sessionCasesList(1, $_GET['charge']);
                break;


            ## Cas conduisant à la mise à jour de la zone description d'une session ##
            //Ajax : ajout des champs 'formateur' et 'salle' à une session
            case 'ajoutFormateur':
                $nb = $_GET['nb'] + 1;
                $session = new Session('', '');
                echo $session->addTrainerRoom($nb);
                break;

            //Ajax : suppression des champs 'formateur' et 'salle' à une session
            case 'supprFormateur':
                $nb = $_GET['nb'] - 1;
                echo '<div id="autreFormateur' . $nb . '"><input type="hidden" id="nb_formateur" name="nb_formateur" value=' . $nb . '></div>';
                break;

            //Ajax : mise à jour des listes des formateurs et des salles dans une session
            case 'majFormateur':
                $formateur = new Formateur($_GET['formateur'], '');
                $salle = new Salle($_GET['salle'], '');
                $nb = $_GET['nb'];
                echo '<div id="Dformateur' . $nb . '">
					Formateur : <select id="formateur' . $nb . '" name="formateur[]" onchange="prixFormateur()">
						<option value="">' . TRAINER_SELECT . '</option>
						' . $formateur->getList() . '
					</select><span class="marge"></span>
					Salle : <select id="salle' . $nb . '" name="salle[]" onchange="prixSalle()">
						<option value="">' . ROOM_SELECT . '</option>' . $salle->getList() . '
					</select><span class="marge"></span>
					<input type="button" onclick="majListe(' . $nb . ')" value="Rafraichir listes">
				</div>';
                break;

            ## Cas conduisant à la mise à jour de la zone logistique d'une session ##	
            //Ajax : blocage et déblocage d'un élément de la checklist
            case 'barrer':
                echo Logistique::crossOut($_GET['numero'], $_GET['valeur'], $_GET['row'], $_GET['rmq']);
                break;

            ######## Dans les affaires du pôle formation #######
            //Ajax : ajout d'une inscription à une session dans une affaire du pôle formation
            case 'ajoutParticipant':
                $nb = $_GET['nb'] + 1;
                $session = new Session($_GET['Id_session'], '');
                echo $session->addParticipant($nb);
                break;

            //Ajax : suppression d'une inscription à une session dans une affaire du pôle formation
            case 'supprParticipant':
                $nb = $_GET['nb'];
                echo '<input id="nomParticipant' . $nb . '" name="nomParticipant[]" type="hidden" value="">
				 <input id="prix_unitaireParticipant' . $nb . '" name="prix_unitaireParticipant[]" type="hidden" value=(float)0>';
                break;

            //Ajax : mise à jour du nombre d'inscrits à une session dans une affaire du pôle formation
            case 'calculNbInscrit':
                echo '<input type="button" onclick="addTrainee()" value="Ajouter Inscription"><span class="marge"></span><span class="marge"></span>
					<b>Nombre d\'inscrits : </b><input type="text" id="nb_inscrit" name="nb_inscrit" value="' . $_GET['nb_inscrit'] . '" >';
                break;

            //Ajax : mise à jour du chiffre d'affaire d'une affaire du pôle formation à partir d'une inscription d'un participant
            case 'calculCaAffaireSession':
                echo '<table> 
					<tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">CA total :</td>
						<td width="200" height="25" align="left"><input type="text" onkeyup="infoSessionProposition1()" id="chiffre_affaire" name="chiffre_affaire" value="' . $_GET['ca'] . '" > euros &nbsp;&nbsp;<input type="button" onclick="calculCaAffaireSession(3,0)" value="Somme des inscriptions"></td>
						<td width="100" height="25"></td>
					</tr>
				</table>';
                break;

            //Ajax : Mise à jour de la liste des sessions dans une affaire du pôle formation dans la zone description de l'affaire
            case 'majListeSession':
                echo Session::getAvailableList($_GET['affaire'], $_GET['Id_session']);
                break;

            //Ajax : Affichage dans une affaire du pôle formation des informations concernant la session sélectionnée dans la zone description de l'affaire
            case 'informationSessionDescription':
                if ($_GET['Id_session'] != '') {
                    $session = new Session($_GET['Id_session'], array());
                    echo $session->infoSession();
                } else {
                    echo ' ';
                }
                break;

            //Ajax : Affichage dans une affaire du pôle formation des informations concernant la session sélectionnée dans la zone planning de l'affaire
            case 'informationSessionPlanning':
                if ($_GET['Id_session'] != '') {
                    echo PlanningSession::infoSession($_GET['Id_session']);
                } else {
                    echo ' ';
                }
                break;

            //Ajax : Affichage dans une affaire du pôle formation des informations concernant la session sélectionnée dans la zone commerciale de l'affaire
            case 'informationSessionProposition':
                if ($_GET['Id_session'] != '') {
                    echo PropSession::infoSession($_GET['Id_session'], $_GET['ca'], $_GET['nb_inscrit'], $_GET['Id_affaire']);
                } else {
                    echo '
				<table> 
					<tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">Total des charges :</td>
						<td width="200" height="25" align="left"><input type="text" id="cout_total" name="cout_total" value=" " disabled=true> euros </td>
						<td width="100" height="25"></td>
					</tr>
					<tr>
						<td width="110" height="25"></td>
						<td width="110" height="25" align="left">Marge :</td>
						<td width="200" height="25" align="left"><input type="text" id="marge_totale" name="marge_totale" value=" " disabled=true> % </td>
						<td width="100" height="25"></td>
					</tr>
				</table> ';
                }
                break;

            #### Consultation ####	
            //Ajax : Affichage de la liste des sessions existantes selon les critéres demandés
            case 'consulterSession':
                $_SESSION['filtre'] = $_GET;
                $_SESSION['filtre']['nom_session'] = utf8_decode($_GET['nom_session']);
                $_SESSION['filtre']['ville'] = utf8_decode($_GET['ville']);
                echo Session::search($_SESSION['filtre']['Id_session'], $_SESSION['filtre']['reference_affaire'], $_SESSION['filtre']['nom_session'], $_SESSION['filtre']['type_session'], $_SESSION['filtre']['ville'], $_SESSION['filtre']['Id_intitule'], $_SESSION['filtre']['debut'], $_SESSION['filtre']['fin'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));

                break;

            //Ajax : Affichage de la liste des formateurs existantes selon les critéres demandés
            case 'consulterFormateur':
                $_SESSION['filtre'] = $_GET;
                $_SESSION['filtre']['competence'] = utf8_decode($_GET['competence']);
                echo Formateur::search($_SESSION['filtre']['Id_formateur'], $_SESSION['filtre']['nom_formateur'], $_SESSION['filtre']['type_salaire'], $_SESSION['filtre']['salaire'], $_SESSION['filtre']['competence'], $_SESSION['filtre']['nb'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));

                break;

            //Ajax : Affichage de la liste des salles existantes selon les critéres demandés
            case 'consulterSalle':
                $_SESSION['filtre'] = $_GET;
                $_SESSION['filtre']['nom_salle'] = utf8_decode($_GET['nom_salle']);
                $_SESSION['filtre']['lieu'] = utf8_decode($_GET['lieu']);
                $_SESSION['filtre']['ville'] = utf8_decode($_GET['ville']);
                $_SESSION['filtre']['nb'] = $_GET['nb'];
                echo Salle::search($_SESSION['filtre']['Id_salle'], $_SESSION['filtre']['nom_salle'], $_SESSION['filtre']['lieu'], $_SESSION['filtre']['type_prix'], $_SESSION['filtre']['prix'], $_SESSION['filtre']['ville'], $_SESSION['filtre']['nb'], array('type' => $_GET['type'], 'orderBy' => $_GET['orderBy'], 'direction' => $_GET['direction']));

                break;
            
            case 'linkToOpportunity':
                $session = new Session($_GET['Id_session'], null);
                $session->linkToOpportunity($_GET['Id_opportunity']);
                echo $session->relatedOpportunitiesList(true);
                break;
        }
    } else {
        header("HTTP/1.0 403 Forbidden");
        exit();
    }
}
?>
