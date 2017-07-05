<?php

/**
 * @file Annonce.php
 * Classe pour gérer les annonces
 *
 * @author Marc Olivier ETOURNEAU et Anthony ANNE
 * @since Juillet 2008
 */
class Annonce {

    /**
     * Identifiant de l'annonce
     *
     * @access private
     * @var int
     */
    private $Id_annonce;
    /**
     * Libellé d'une annonce
     *
     * @access private
     * @var string
     */
    private $libelle;
    /**
     * Référence de l'annonce
     *
     * @access public
     * @var string
     */
    public $reference;
    /**
     * Date de création de l'annonce
     *
     * @access private
     * @var string
     */
    private $date_creation;
    /**
     * Date de modification de l'offre
     *
     * @access private
     * @var date
     */
    public $date_modification;
    /**
     * Descriptif de l'annonce
     *
     * @access private
     * @var string
     */
    private $descriptif_poste;
    /**
     * Descriptif de l'annonce
     *
     * @access private
     * @var string
     */
    private $descriptif_profil;
    /**
     * Créateur de l'annonce
     *
     * @access private
     * @var string
     */
    private $createur;
    /**
     * Localisation de l'annonce
     *
     * @access public
     * @var string
     */
    public $localisation;
    /**
     * Type de contrat de l'annonce
     *
     * @access public
     * @var string
     */
    public $type_contrat;
    /**
     * Métier de l'annonce
     *
     * @access public
     * @var string
     */
    public $Id_metier;
    /**
     * Indique si l'annonce est une archive
     *
     * @access private
     * @var int
     */
    private $archive;
    /**
     * Tableau contenant les erreurs suite à la création / modification d'une annonce
     *
     * @access private
     * @var array
     */
    private $erreurs;

    public function __construct($code, $tab) {
        $this->erreurs = array();
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_annonce = '';
                $this->Id_metier = 12;
                $this->date_creation = '';
                $this->date_modification = '';
                $this->createur = '';
                $this->archive = 0;
                $this->Id_agence = '001';
                $this->secteur = 'de la Finance';
                $this->localisation = 28047;
                $this->mission = '<ul><li>Aaaa</li><li>Bbbb</li></ul>';
                $this->environnement = 'Microsoft';
                $this->outils = '<ul><li>Aaaa, Bbbb, Cccc</li><li>Dddd, Eeee, Ffff</li></ul>';
                $this->gouts = '<ul><li>Le travail en équipe</li><li>La relation utilisateurs (écoute)</li><li>La pratique de l\'Anglais</li><li>Le travail de nuit</li><li>Le travail en horaires décalées</li><li>La mobilité dans votre travail</li></ul>';
                $this->qualites = 'rigueur, relationnel, capacité de synthèse, pédagogie, discrétion ...';
                $this->experience_basse = 0;
                $this->experience_haute = 3;
                $this->type_contrat = 'CDI';
                $this->duree_contrat = '3';
                $this->date_demarrage = 'Novembre 2012';
                $this->Id_evolution_possible = '3';
                $this->reference_recruteur = 'ALC';
                $this->reference_commercial = 'AM';
                $this->texte_annonce = '';
            }
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_annonce = '';
                $this->Id_metier = (int) $tab['Id_metier'];
                $this->date_creation = DATETIME;
                $this->date_modification = DATETIME;
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->archive = (int) $tab['archive'];
                $this->Id_agence = $tab['Id_agence'];
                $this->secteur = htmlscperso(stripslashes($tab['secteur']), ENT_QUOTES);
                $this->localisation = (int) $tab['localisation'];
                $this->mission = htmlscperso(stripslashes($tab['mission']), ENT_QUOTES);
                $this->environnement = htmlscperso(stripslashes($tab['environnement']), ENT_QUOTES);
                $this->outils = htmlscperso(stripslashes($tab['outils']), ENT_QUOTES);
                $this->gouts = htmlscperso(stripslashes($tab['gouts']), ENT_QUOTES);
                $this->qualites = htmlscperso(stripslashes($tab['qualites']), ENT_QUOTES);
                $this->experience_basse = (int) $tab['experience_basse'];
                $this->experience_haute = (int) $tab['experience_haute'];
                $this->type_contrat = htmlscperso(stripslashes($tab['type_contrat']), ENT_QUOTES);
                $this->duree_contrat = (int) $tab['duree_contrat'];
                $this->date_demarrage = htmlscperso(stripslashes($tab['date_demarrage']), ENT_QUOTES);
                $this->Id_evolution_possible = (int) $tab['Id_evolution_possible'];
                $this->reference_recruteur = htmlscperso(stripslashes($tab['reference_recruteur']), ENT_QUOTES);
                $this->reference_commercial = htmlscperso(stripslashes($tab['reference_commercial']), ENT_QUOTES);
                $this->texte_annonce = htmlscperso(stripslashes($tab['texte_annonce']), ENT_QUOTES);
            }
            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM annonce WHERE Id_annonce = ' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_annonce = $code;
                $this->Id_metier = $ligne->id_metier;
                $this->date_creation = $ligne->date_creation;
                $this->date_modification = $ligne->date_modification;
                $this->createur = $ligne->createur;
                $this->archive = $ligne->archive;
                $this->Id_agence = $ligne->id_agence;
                $this->secteur = $ligne->secteur;
                $this->localisation = $ligne->localisation;
                $this->mission = $ligne->mission;
                $this->environnement = $ligne->environnement;
                $this->outils = $ligne->outils;
                $this->gouts = $ligne->gouts;
                $this->qualites = $ligne->qualites;
                $this->experience_basse = $ligne->experience_basse;
                $this->experience_haute = $ligne->experience_haute;
                $this->type_contrat = $ligne->type_contrat;
                $this->duree_contrat = $ligne->duree_contrat;
                $this->date_demarrage = $ligne->date_demarrage;
                $this->Id_evolution_possible = $ligne->id_evolution_possible;
                $this->reference_recruteur = $ligne->reference_recruteur;
                $this->reference_commercial = $ligne->reference_commercial;
                $this->texte_annonce = $ligne->texte_annonce;
            }
            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            if ($code && !empty($tab)) {
                $this->Id_annonce = $code;
                $this->Id_metier = (int) $tab['Id_metier'];
                $this->date_modification = DATETIME;
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->archive = (int) $tab['archive'];
                $this->Id_agence = $tab['Id_agence'];
                $this->secteur = htmlscperso(stripslashes($tab['secteur']), ENT_QUOTES);
                $this->localisation = (int) $tab['localisation'];
                $this->mission = htmlscperso(stripslashes($tab['mission']), ENT_QUOTES);
                $this->environnement = htmlscperso(stripslashes($tab['environnement']), ENT_QUOTES);
                $this->outils = htmlscperso(stripslashes($tab['outils']), ENT_QUOTES);
                $this->gouts = htmlscperso(stripslashes($tab['gouts']), ENT_QUOTES);
                $this->qualites = htmlscperso(stripslashes($tab['qualites']), ENT_QUOTES);
                $this->experience_basse = (int) $tab['experience_basse'];
                $this->experience_haute = (int) $tab['experience_haute'];
                $this->type_contrat = htmlscperso(stripslashes($tab['type_contrat']), ENT_QUOTES);
                $this->duree_contrat = (int) $tab['duree_contrat'];
                $this->date_demarrage = htmlscperso(stripslashes($tab['date_demarrage']), ENT_QUOTES);
                $this->Id_evolution_possible = (int) $tab['Id_evolution_possible'];
                $this->reference_recruteur = htmlscperso(stripslashes($tab['reference_recruteur']), ENT_QUOTES);
                $this->reference_commercial = htmlscperso(stripslashes($tab['reference_commercial']), ENT_QUOTES);
                $this->texte_annonce = htmlscperso(stripslashes($tab['texte_annonce']), ENT_QUOTES);
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification d'une annonce
     *
     * @return string
     */
    public function form() {
        if ($this->localisation) {
            $ville = new Ville($this->localisation, array());
            $localisation = $ville->nom . ' (' . $ville->code_postal . ')';
        }
        $metier = new Metier($this->Id_metier, array());
        $agence = Agence::getLibelle($this->Id_agence);
        /* A ajouter quand les liens vers les métiers seront existant
         <br /><br />    
            Pour découvrir les évolutions possibles sur ce poste, ainsi que les témoignages de collaborateurs, cliquez ICI.
         */
        $html2 = '    
        <form enctype="multipart/form-data" action="../rh/index.php?a=modification_annonce" method="post">
            Notre agence de <span id="editAgence" class="inlinePlaceEditor">' . $agence . '</span> recherche un <span id="editMetier" class="inlinePlaceEditor">' . $metier->libelle . '</span> pour un de nos Clients du secteur <span id="editSecteur" class="inlinePlaceEditor">' . $this->secteur . '</span> basé à <span id="editLocalisation" class="inlinePlaceEditor">' . $localisation . '</span>.<br /><br />
            La mission consiste à :<br />
            <div id="editMission" class="inlinePlaceEditor">
                ' . $this->mission . '
            </div>
            Vous avez idéalement évolué dans les environnements <span id="editEnvironnement" class="inlinePlaceEditor">' . $this->environnement . '</span> et maitrisez les outils suivants : <br />
            <div id="editOutils" class="inlinePlaceEditor">
                ' . $this->outils . '
            </div>
            <br />
            Les qualités qui vous sont reconnues : <span id="editQualites" class="inlinePlaceEditor">' . $this->qualites . '</span> et votre goût pour :<br />
            <div id="editGouts" class="inlinePlaceEditor">
                ' . $this->gouts . '
            </div>
            seront des atouts pour réussir et évoluer dans votre mission.
            <br /><br />
            Vous avez entre <span id="editExperience1" class="inlinePlaceEditor">' . $this->experience_basse . '</span> et <span id="editExperience2" class="inlinePlaceEditor">' . $this->experience_haute . '</span> années d\'expérience(s) sur une fonction identique ou similaire.<br />
            <br /><br />
            Type de contrat : <span id="editTypeContrat" class="inlinePlaceEditor">' . $this->type_contrat . '</span><br />
            <span id="duree" style="display:none;">Durée : <span id="editDureeContrat" class="inlinePlaceEditor">' . $this->duree_contrat . '</span> mois<br /></span>
            Date de démarrage : <span id="editDateDemarrage" class="inlinePlaceEditor">' . $this->date_demarrage . '</span><br />
            Référence annonce : <span id="editRecruteur" class="inlinePlaceEditor">' . $this->reference_recruteur . '</span> <span id="editCommercial" class="inlinePlaceEditor">' . $this->reference_commercial . '</span> ' . $this->Id_annonce . '
            <br /><br />
            <strong>Proservia s\'engage à défendre l\'égalité des chances et à lutter contre toute forme de discrimination face à l\'emploi que ce soit sur l\'âge, le sexe, l\'origine (sociale, ethnique, culturelle), le handicap mais aussi les diplômes. La richesse de nos métiers et la diversité des secteurs d\'activité de nos clients nous permettent de vous proposer des missions évolutives et variées où chacun aura l\'opportunité de s\'épanouir et de révéler ses talents.<br />
            Rejoignez-nous !</strong>
            <br /><br />
            <input type="hidden" value="' . $this->Id_agence . '" name="Id_agence" id="Id_agence">
            <input type="hidden" value="' . $this->Id_metier . '" name="Id_metier" id="Id_metier">
            <input type="hidden" value="' . htmlscperso($this->secteur) . '" name="secteur" id="secteur">
            <input type="hidden" value="' . $this->localisation . '" name="localisation" id="localisation">
            <input type="hidden" value="' . htmlscperso($this->mission) . '" name="mission" id="mission">
            <input type="hidden" value="' . htmlscperso($this->environnement) . '" name="environnement" id="environnement">
            <input type="hidden" value="' . htmlscperso($this->outils) . '" name="outils" id="outils">
            <input type="hidden" value="' . htmlscperso($this->gouts) . '" name="gouts" id="gouts">
            <input type="hidden" value="' . htmlscperso($this->qualites) . '" name="qualites" id="qualites">
            <input type="hidden" value="' . $this->experience_basse . '" name="experience_basse" id="experience_basse">
            <input type="hidden" value="' . $this->experience_haute . '" name="experience_haute" id="experience_haute">
            <input type="hidden" value="' . $this->type_contrat . '" name="type_contrat" id="type_contrat">
            <input type="hidden" value="' . $this->duree_contrat . '" name="duree_contrat" id="duree_contrat">
            <input type="hidden" value="' . htmlscperso($this->date_demarrage) . '" name="date_demarrage" id="date_demarrage">
            <input type="hidden" value="' . $this->Id_evolution_possible . '" name="Id_evolution_possible" id="Id_evolution_possible">
            <input type="hidden" value="' . $this->reference_recruteur . '" name="reference_recruteur" id="reference_recruteur">
            <input type="hidden" value="' . $this->reference_commercial . '" name="reference_commercial" id="reference_commercial">
            <button type="submit" value="Suivant" class="button" >Suivant</button>
        </form>';
        
        $html2 .= "
            <script type=\"text/javascript\">
                new Ajax.InPlaceCollectionEditor('editAgence', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: " . Agence::getJsonList() . ",
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('Id_agence').value = value;
                        return 'type=Id_agence&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceCollectionEditor('editMetier', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: " . Metier::getJsonList() . ",
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('Id_metier').value = value;
                        return 'type=Id_metier&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceEditor('editSecteur', '../source/index.php?a=annonceInPlaceEditor',
                {
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('secteur').value = value;
                        return 'type=secteur&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceEditor('editLocalisation', '../source/index.php?a=annonceInPlaceEditor',
                {
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        return 'type=localisation&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    },
                    onFormReady: function(obj, form) {
                        form.insert(\"<div style='display: none; position: absolute;' id='updateLocalisation'></div>\");
                        getCityList(obj._controls.editor);
                    }
                    
                });
                new Ajax.InPlaceRichEditor($('editMission'), '../source/index.php?a=annonceInPlaceEditor',{
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('mission').value = value;
                        return 'type=mission&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                }, {
                    mode : 'textareas',
                    theme : 'advanced',
                    width : '100%',
                    theme_advanced_disable : 'italic,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent,styleselect,sub,sup,image,help,formatselect,anchor,removeformat,visualaid,separator,code,cleanup',
                    theme_advanced_resize_horizontal : true,
                    theme_advanced_resizing : true,
                    apply_source_formatting : true,
                    spellchecker_languages : '+English=en,French=fr'
                });
                new Ajax.InPlaceEditor('editEnvironnement', '../source/index.php?a=annonceInPlaceEditor',
                {
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('environnement').value = value;
                        return 'type=environnement&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceRichEditor($('editOutils'), '../source/index.php?a=annonceInPlaceEditor',{
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('outils').value = value;
                        return 'type=outils&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                }, {
                    mode : 'textareas',
                    theme : 'advanced',
                    width : '100%',
                    theme_advanced_disable : 'italic,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent,styleselect,sub,sup,image,help,formatselect,anchor,removeformat,visualaid,separator,code,cleanup',
                    theme_advanced_resize_horizontal : true,
                    theme_advanced_resizing : true,
                    apply_source_formatting : true,
                    spellchecker_languages : '+English=en,French=fr'
                });
                new Ajax.InPlaceEditor('editQualites', '../source/index.php?a=annonceInPlaceEditor',
                {
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('qualites').value = value;
                        return 'type=qualites&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceRichEditor($('editGouts'), '../source/index.php?a=annonceInPlaceEditor',{
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('gouts').value = value;
                        return 'type=gouts&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    }
                }, {
                    mode : 'textareas',
                    theme : 'advanced',
                    width : '100%',
                    theme_advanced_disable : 'italic,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent,styleselect,sub,sup,image,help,formatselect,anchor,removeformat,visualaid,separator,code,cleanup',
                    theme_advanced_resize_horizontal : true,
                    theme_advanced_resizing : true,
                    apply_source_formatting : true,
                    spellchecker_languages : '+English=en,French=fr'
                });
                new Ajax.InPlaceCollectionEditor('editExperience1', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: [['0','0'],['1','1'],['2','2'],['3','3'],['4','4'],['5','5'],
                                ['6','6'],['7','7'],['8','8'],['9','9'],['10','10']],
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('experience_basse').value = value;
                        return 'type=experience_basse&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceCollectionEditor('editExperience2', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: [['0','0'],['1','1'],['2','2'],['3','3'],['4','4'],['5','5'],
                                ['6','6'],['7','7'],['8','8'],['9','9'],['10','10']],
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('experience_haute').value = value;
                        return 'type=experience_haute&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceCollectionEditor('editTypeContrat', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: [['CDI','CDI'],['CDD','CDD'],['Alternance','Alternance'],['Stage','Stage']],
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('type_contrat').value = value;
                        if(value == 'CDD')
                            $('duree').show();
                        else
                            $('duree').hide();
                        return 'type=type_contrat&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceCollectionEditor('editDureeContrat', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: [,['1','1'],['2','2'],['3','3'],['4','4'],['5','5'],
                                ['6','6'],['7','7'],['8','8'],['9','9'],['10','10'],
                                ['11','11'],['12','12'],['13','13'],['14','14'],['15','15'],
                                ['16','16'],['17','17'],['18','18']],
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('duree_contrat').value = value;
                        return 'type=duree_contrat&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceEditor('editDateDemarrage', '../source/index.php?a=annonceInPlaceEditor',
                {
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        $('date_demarrage').value = value;
                        return 'type=date_demarrage&libelle=' + encodeURIComponent(value) + '&value=' + encodeURIComponent(value);
                    },
                    onFormReady: function(obj, form) {
                        showCalendarControl(obj._controls.editor, function(b,c,a){
                            this.Calendar.prototype.Month = c - 1;
                            obj._controls.editor.value = this.Calendar.prototype.GetMonthName(true) + ' ' + b;
                        });
                    }
                });
                new Ajax.InPlaceCollectionEditor('editRecruteur', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: " . Utilisateur::getJsonList('RH') . ",
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('reference_recruteur').value = value;
                        return 'type=reference_recruteur&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
                new Ajax.InPlaceCollectionEditor('editCommercial', '../source/index.php?a=annonceInPlaceEditor',
                {
                    collection: " . Utilisateur::getJsonList('COM') . ",
                    savingText: 'Enregistrement',
                    okText: 'Valider',
                    cancelText: 'Annuler',
                    highlightColor: '#BEC9DF',
                    highlightEndColor: '#FFFF99',
                    clickToEditText: 'Modifier',
                    callback: function(form,value){
                        v = form.value.options[form.value.selectedIndex].text;
                        $('reference_commercial').value = value;
                        return 'type=reference_commercial&libelle=' + encodeURIComponent(v) + '&value=' + encodeURIComponent(value);
                    }
                });
            </script>";
        return $html2;
    }
    
    /**
     * Formulaire de création / modification d'une annonce
     *
     * @return string
     */
    public function form_part2() {
        if ($this->localisation) {
            $ville = new Ville($this->localisation, array());
            $localisation = $ville->nom . ' (' . $ville->code_postal . ')';
        }
        $metier = new Metier($this->Id_metier, array());
        if($metier->url) {
            $m = '<a href="' . PROSERVIA_RH_WEBSITE . $metier->url . '" target="_blank">' . $metier->libelle . '</a>';
        }
        else {
            $m = $metier->libelle;
        }
        $agence = Agence::getLibelle($this->Id_agence);
        if(empty($this->texte_annonce)) {
            if($this->type_contrat == 'CDD')
                $duree = 'Durée : ' . $this->duree_contrat . ' mois<br />';
            $html2 = '
                Notre agence de ' . $agence . ' recherche un ' . $m . ' pour un de nos Clients du secteur ' . $this->secteur . ' basé à ' . $localisation . '.<br /><br />
                La mission consiste à :<br />
                    ' . $this->mission . '
                Vous avez idéalement évolué dans les environnements ' . $this->environnement . ' et maitrisez les outils suivants : <br />
                    ' . $this->outils . '
                <br />
                Les qualités qui vous sont reconnues : ' . $this->qualites . ' et votre goût pour :<br />
                    ' . $this->gouts . '
                seront des atouts pour réussir et évoluer dans votre mission.
                <br /><br />
                Vous avez entre ' . $this->experience_basse . ' et ' . $this->experience_haute . ' années d\'expérience(s) sur une fonction identique ou similaire.<br />
                <br /><br />
                Type de contrat : ' . $this->type_contrat . '<br />
                ' . $duree . '
                Date de démarrage : ' . $this->date_demarrage . '<br />
                Référence annonce : ' . $this->reference_recruteur . ' ' . $this->reference_commercial . ' ' . $this->Id_annonce . '
                <br /><br />
                <strong>Proservia s\'engage à défendre l\'égalité des chances et à lutter contre toute forme de discrimination face à l\'emploi que ce soit sur l\'âge, le sexe, l\'origine (sociale, ethnique, culturelle), le handicap mais aussi les diplômes. La richesse de nos métiers et la diversité des secteurs d\'activité de nos clients nous permettent de vous proposer des missions évolutives et variées où chacun aura l\'opportunité de s\'épanouir et de révéler ses talents.<br />
                Rejoignez-nous !</strong>
            ';
        }
        else
            $html2 = $this->texte_annonce;
        
        $html = '
        <form enctype="multipart/form-data" action="../membre/index.php?a=enregistrer" method="post">
            <textarea name="texte_annonce" id="texte_annonce">' . $html2 . '</textarea>
            <script type="text/javascript">
                tinyMCE.init({
                    mode : "exact",
                    elements : "texte_annonce",
                    theme : "advanced",
                    width : "100%",
                    height : "550",
                    plugins : "layer,table,save,advlink,inlinepopups,preview,searchreplace,print,contextmenu,paste,noneditable,nonbreaking,pagebreak,fullscreen",
                    theme_advanced_buttons3_add : "print,fullscreen",
                    theme_advanced_buttons4 : "fontselect,fontsizeselect",
                    theme_advanced_statusbar_location : "bottom",
                    theme_advanced_resize_horizontal : true,
                    theme_advanced_resizing : true,
                    apply_source_formatting : true,
                    spellchecker_languages : "+English=en,French=fr",
                });
            </script>
            <br /><br />
            <div class="submit">
                <input type="hidden" id="dirty" name="dirty" value="0" />
                <input type="hidden" value="' . $this->Id_annonce . '" name="Id">
                <input type="hidden" value="Annonce" name="class">
                <input type="hidden" value="' . $this->Id_agence . '" name="Id_agence">
                <input type="hidden" value="' . $this->Id_metier . '" name="Id_metier">
                <input type="hidden" value="' . $this->secteur . '" name="secteur">
                <input type="hidden" value="' . $this->localisation . '" name="localisation">
                <input type="hidden" value="' . $this->mission . '" name="mission">
                <input type="hidden" value="' . $this->environnement . '" name="environnement">
                <input type="hidden" value="' . $this->outils . '" name="outils">
                <input type="hidden" value="' . $this->gouts . '" name="gouts">
                <input type="hidden" value="' . $this->qualites . '" name="qualites">
                <input type="hidden" value="' . $this->experience_basse . '" name="experience_basse">
                <input type="hidden" value="' . $this->experience_haute . '" name="experience_haute">
                <input type="hidden" value="' . $this->type_contrat . '" name="type_contrat">
                <input type="hidden" value="' . $this->duree_contrat . '" name="duree_contrat">
                <input type="hidden" value="' . $this->date_demarrage . '" name="date_demarrage">
                <input type="hidden" value="' . $this->Id_evolution_possible . '" name="Id_evolution_possible">
                <input type="hidden" value="' . $this->reference_recruteur . '" name="reference_recruteur">
                <input type="hidden" value="' . $this->reference_commercial . '" name="reference_commercial">
                <button type="submit" value="Suivant" class="button save" onClick="onUpdateAnnonce(tinymce.editors[\'texte_annonce\']);">Enregistrer</button>
            </div>
        </form>';
        return $html;
    }

    /**
     * Vérification du formulaire
     *
     * Le champ libelle est obligatoire
     * Le champ référence est obligatoire
     *
     * @return bool
     */
    public function check() {
        if ($this->localisation == '') {
            $this->erreurs['localisation'] = 'Veuillez entrer une localisation';
        }
        return count($this->erreurs) == 0;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_annonce = ' . mysql_real_escape_string((int) $this->Id_annonce) . ',
                date_modification = "' . mysql_real_escape_string($this->date_modification) . '",
                Id_metier = ' . mysql_real_escape_string((int) $this->Id_metier) . ',
                Id_agence = "' . mysql_real_escape_string($this->Id_agence) . '",
                secteur = "' . mysql_real_escape_string($this->secteur) . '",
                localisation = "' . mysql_real_escape_string((int) $this->localisation) . '",
                mission = "' . mysql_real_escape_string($this->mission) . '",
                environnement = "' . mysql_real_escape_string($this->environnement) . '",
                outils = "' . mysql_real_escape_string($this->outils) . '",
                gouts = "' . mysql_real_escape_string($this->gouts) . '",
                qualites = "' . mysql_real_escape_string($this->qualites) . '",
                experience_basse = "' . mysql_real_escape_string((int) $this->experience_basse) . '",
                experience_haute = "' . mysql_real_escape_string((int) $this->experience_haute) . '",
                Id_evolution_possible = "' . mysql_real_escape_string((int) $this->Id_evolution_possible) . '",
                type_contrat = "' . mysql_real_escape_string($this->type_contrat) . '",
                duree_contrat = "' . mysql_real_escape_string((int) $this->duree_contrat) . '",
                date_demarrage = "' . mysql_real_escape_string($this->date_demarrage) . '",
                reference_recruteur = "' . mysql_real_escape_string($this->reference_recruteur) . '",
                reference_commercial = "' . mysql_real_escape_string($this->reference_commercial) . '",
                texte_annonce = "' . mysql_real_escape_string($this->texte_annonce) . '",
                archive = ' . mysql_real_escape_string((int) $this->archive) . '';
        if ($this->Id_annonce) {
            $requete = 'UPDATE annonce ' . $set . ' WHERE Id_annonce = ' . mysql_real_escape_string((int) $this->Id_annonce) . '';
        } else {
            $requete = 'INSERT INTO annonce ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '",
                        date_creation = "' . mysql_real_escape_string(DATETIME) . '"';
        }
        $db->query($requete);
    }

    /**
     * Recherche d'une annonce
     *
     * @param string Libellé de l'annonce
     * @param string Référence de l'annonce
     * @param date Permet de trier par rapport à une date de début de l'annonce
     * @param date Permet de trier par rapport à une date de fin de l'annonce
     * @param string Descriptif de l'annonce
     * @param string Localisation de l'annonce
     * @param string Créateur de l'annonce
     *
     * @return string
     */
    public static function search($metier, $debut, $fin, $mot_cle, $localisation, $createur, $output = array('type' => 'TABLE')) {
        $arguments = array('metier', 'reference', 'debut', 'fin', '$mot_cle', 'localisation', 'createur', 'output');
        $columns = array(array('Date','date_modification'), array('Ref','reference_recruteur'), array('Métier','lMetier'), array('Localisation','city'));
        $db = connecter();
        $requete = 'SELECT Id_annonce, date_modification, localisation, gc.city, a.Id_metier,
                    gc.zip, DATE_FORMAT(date_modification, "%d-%m-%Y") as date_modification_fr,
                    reference_recruteur, reference_commercial, texte_annonce, m.libelle AS lMetier
                    FROM annonce a
                    LEFT JOIN geography_city gc ON gc.id_geography_city = a.localisation
                    INNER JOIN metier m ON m.Id_metier = a.Id_metier
                    WHERE a.archive=0';
        if ($metier) {
            $requete .= ' AND a.Id_metier = ' . $metier;
        }
        if ($debut && $fin) {
            $requete .= ' AND date_modification BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        if ($mot_cle) {
            $requete .= ' AND (texte_annonce LIKE "%' . $mot_cle . '%")';
        }
        if ($localisation) {
            $requete .= ' AND localisation = "' . $localisation . '"';
        }
        if ($createur) {
            $requete .= ' AND createur="' . $createur . '"';
        }

        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if(isset($output['orderBy'])) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        }
        else {
            $paramsOrder .= 'orderBy=date_modification';
            $orderBy = 'date_modification';
        }
        if(isset($output['direction'])) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        
        if ((isset($output['type']) && $output['type'] == 'CSV')) {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="annonces.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo $ligne['date_modification_fr'] . ';';
                echo '"' . $ligne['reference_recruteur'] . ' ' . $ligne['reference_commercial'] . ' ' . $ligne['id_annonce'] . '";';
                echo '"' . $ligne['lmetier'] . '";';
                echo '"' . self::showCity($ligne, array('csv' => true)) . '";';
                echo PHP_EOL;
            }
        }else {
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherAnnonce({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterAnnonce&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if(isset($output['orderBy']) && $value[1] == $output['orderBy'])
                        if(isset($output['direction']) && $output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!isset($output['orderBy'])) {
                        $direction = 'ASC';
                        $img['date_modification'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherAnnonce({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['date_modification_fr'] . '</td>
                            <td>' . $ligne['reference_recruteur'] . ' ' . $ligne['reference_commercial'] . ' ' . $ligne['id_annonce'] . '</td>
                            <td>' . $ligne['lmetier'] . '</td>
                            <td>' . self::showCity($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        }
        return $html;
    }

    /**
     * Archivage d'une annonce
     */
    public function archive() {
        $db = connecter();
        $db->query('UPDATE annonce SET archive="1" WHERE Id_annonce = ' . mysql_real_escape_string((int) $this->Id_annonce));
    }

    /**
     * Desarchivage d'une annonce
     */
    public function unarchive() {
        $db = connecter();
        $db->query('UPDATE annonce SET archive="0" WHERE Id_annonce = ' . mysql_real_escape_string((int) $this->Id_annonce));
    }

    /**
     * Suppression d'une annonce
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM annonce WHERE Id_annonce = ' . mysql_real_escape_string((int) $this->Id_annonce));
    }

    /**
     * Affichage du formulaire de recherche d'une annonce
     *
     * @return string
     */
    public static function searchForm() {
        if (empty($_SESSION['filtre']['createur_annonce'])) {
            $_SESSION['filtre']['createur_annonce'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        $createur = new Utilisateur($_SESSION['filtre']['createur_annonce'], array());
        $metier = new Metier($_SESSION['filtre']['metier'], null);
        if ($_SESSION['filtre']['localisation']) {
            $ville = new Ville($_SESSION['filtre']['localisation'], array());
            $localisation = $ville->nom . ' (' . $ville->code_postal . ')';
        }
        $html = '
            <select id="createur_annonce" onchange="afficherAnnonce()">
                <option value="">Par créateur</option>
                <option value="">----------------------------</option>
                ' . $createur->getList('RH') . '
            </select>
            <select id="metier" onchange="afficherAnnonce()">
                <option value="">Par métier</option>
                <option value="">----------------------------</option>
                ' . $metier->getList() . '
            </select>
			&nbsp;
			Mot clé <input id="mot_cle" type="text" onkeyup="afficherAnnonce()" value="' . $_SESSION['filtre']['mot_cle'] . '" />
            &nbsp;
			Localisation <input id="ville" type="text" onkeyup="afficherAnnonce()" value="' . $localisation . '" />
                        <input type="hidden" name="localisation" id="localisation" value="' . $_SESSION['filtre']['localisation'] . '" />
                        <div style="display: none; position: absolute;" id="updateLocalisation"></div>
                        <script type="text/javascript">getCityList($(\'ville\'))</script>
            &nbsp;
			du <input id="debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['debut'] . '" size="8"/>
            &nbsp;
			au <input id="fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['fin'] . '" size="8"/>
            &nbsp;&nbsp;
			<input type="button" onclick="afficherAnnonce()" value="Go !">
                        <input type="reset" value="Refresh" onclick="initForm(\'Annonce\')">
';
        return $html;
    }
    
    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */

    public function showCity($record) {
        if($record['city']) return $record['city'] . ' (' . $record['zip'] . ')';
        else return '';
    }

    public function showButtons($record) {
        $htmlAdmin = '<td><a href="../rh/index.php?a=modification_annonce&amp;Id=' . $record['id_annonce'] . '"><img src="' . IMG_EDIT . '"></a></td>';
        if ($record['archive'] == 0) {
            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_ARCHIVE . ' cette annonce ?\')) { location.replace(\'../membre/index.php?a=archiver&amp;Id=' . $record['id_annonce'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_BAS . '"></a></td>';
        } elseif ($record['archive'] == 1) {
            $htmlAdmin .= '<td><a href="javascript:void(0)" onclick="if (confirm(\'' . CONFIRM_UNARCHIVE . ' cette annonce ?\')) { location.replace(\'../membre/index.php?a=desarchiver&amp;Id=' . $record['id_annonce'] . '&amp;class=' . __CLASS__ . '\') }"><img src="' . IMG_FLECHE_HAUT . '"></a></td>';
        }
        $htmlAdmin .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'../rh/index.php?a=supprimer_annonce&amp;Id=' . $record['id_annonce'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        return $htmlAdmin;
    }
}

?>
