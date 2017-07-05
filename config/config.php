<?php
/**
  * Fichier config.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Fichier où sont stockées les informations de la BDD
  */ 
require_once(realpath(dirname(__FILE__)) . '/../config/connect.php');

/**
  * Constantes où l'on stocke les urls des fichiers
  */

define('SESSION_PREFIX', 'agc_');

define('ROOT', realpath(dirname(__FILE__)));
define('DEBUG','1');
define('AUTH_TYPE','AD'); // AD -> Active Directory, SSO -> Single Sign On
define('VERSION','3.9.0');
define('DB_PREFIX', 'RHODON_AGC_');
define('BASE_URL','https://srvintra.proservia.lan/AGC/');
define('PROSERVIA_WEBSITE','http://www.proservia.fr');
define('PROSERVIA_RH_WEBSITE','http://www.proserviarecrute.fr/');
define('OSC_URL','https://fpvmch-rhodon.proservia.lan/OSC/');
define('SF_URL','https://manpowergroupfr.my.salesforce.com/');
define('MENU_DROIT','../ui/templates/menuDroit.html.php');
define('MENU_GAUCHE','../ui/templates/menuGauche.html.php');
define('SOURCE_HTML','../ui/affiche.html.php');
define('DEFAULT_HTML','../ui/default.html.php');
define('CONFIG_URL','../config/config.php');
define('FUNCTION_URL','../outils/fonctions.php');
define('AUTOLOAD_URL','../outils/autoload.php');
define('EDITION_URL','../ui/edition.html.php');
define('FPDF_FONTPATH','../libraries/fpdf/font/');
define('SESSION_PREFIX', 'agc_');

define('URL_TMP','../tmp/');
define('CD_NAME_FILE','DCLE-CDLG-');

define('FILES_ODM_VALIDATE','../odm-valide/');
define('FILES_ODM_WAITING','../odm-attente-validation/');

define('JOUR_RAPPORT_BILAN','-13T07:51:10');
define('URL_REPORT','https://fpspch-teysonne.proservia.lan/Reports/Pages/Folder.aspx?ItemPath=%2fAGC');
define('URL_LINK_REPORT','https://fpspch-teysonne.proservia.lan/ReportServer/Pages/ReportViewer.aspx');
define('URL_DOC_AGC','https://srvextra.proservia.lan/partage/3-DGAF/36-DSI/AGC/DSI-GDE-Gestion%20des%20Affaires%20Commerciales.pdf');
define('URL_DOC_REPORT_COM_AGC','https://srvextra.proservia.lan/partage/3-DGAF/36-DSI/AGC/DSI-DOCUMENTATION_AGC_COM_REPORTING.pdf');
define('URL_DOC_REPORT_RH_AGC','https://srvextra.proservia.lan/partage/3-DGAF/36-DSI/AGC/DSI-DOCUMENTATION_AGC_RH_REPORTING.pdf');
define('MAIL_CONTACT','agc@proservia.fr');
define('MAIL_CDHA_DEST', 'helene.barreau@proservia.fr,franck.dubot@proservia.fr,lionel.brossault@proservia.fr,eliane.merdrignac@proservia.fr');
define('MAIL_MANAGEMENT_CONTROL_DEST','controle.gestion@proservia.fr');
define('MAIL_EMBAUCHE_STAFF_DEST','gael.riou@proservia.fr,morgane.guehennec@proservia.fr');
define('MAIL_EMBAUCHE_STAFF_ADV','guenola.baudry@proservia.fr,marie.chesneau@proservia.fr');
define('MAIL_EMBAUCHE_HANDICAPE_DEST','cecile.carre@proservia.fr,david.latimier@proservia.fr');
define('MAIL_CHANGE_VALIDATION','gael.riou@proservia.fr,cecile.carre@proservia.fr,david.latimier@proservia.fr');
define('MAIL_ERREUR','cron.etudedev.dsi@proservia.fr');
define('GESTIONNAIRE_STAFF','eliane.merdrignac@proservia.fr');
define('FICHE_DEMANDE_ACCES', 'https://drive.google.com/file/d/0BzQ4c4XjBoVpRVZ1NnBheXo0bTA/view?usp=sharing');

define('PROSERVIA_GROUP','PROSERVIA ManpowerGroup&trade; Solutions');

define('CREATION','CREATION');
define('SYNTHESIS','SYNTHESE');
define('CONSULTATION','CONSULTATION');

define('LOG_IN','Se connecter');
define('LOG_OFF','Déconnexion');

define('SAVE_BUTTON','Sauvegarder');

define('ZONE_ADMINISTRATION','1');
define('ZONE_COMMERCIALE','2');
define('ZONE_RH','3');
define('ZONE_ADMINISTRATIF','4');
define('ZONE_PARTAGE','5');

define('CALENDAR_JS','../outils/calendrier.js');
define('CALENDAR2_JS','../outils/calendar/datetimepicker_css.js');
define('PROTOTYPE','../outils/lib/prototype.js');
define('SCRIPTACULOUS','../outils/lib/scriptaculous.js');
define('OVERLIB','../outils/overlib/overlib.js');
define('TINYMCE','../outils/tinymce/jscripts/tiny_mce/tiny_mce.js');
define('FUNCTION_JS','../outils/fonctions.js');
define('MULTISELECT','../outils/dropdown_multiselect.js');
define('MODALBOX','../outils/modalbox.js');
define('RICH_INPLACE_EDITOR','../outils/inplacericheditor.js');
define('SCREEN_CSS','../ui/screen.css');
define('BASE_CSS','../ui/base.css');
define('CALENDAR_CSS','../ui/CalendarControl.css');
define('PRINT_CSS','../ui/print.css');
define('MODALBOX_CSS','../ui/modalbox.css');
define('CV_DIR','../CVtheque/');
define('LM_DIR','../LMtheque/');
define('RECHERCHE_CV_DIR','../rechercheCV/');
define('PROPALE_DIR','../Propale/');
define('PLANACCES_DIR','../for/salle/');
define('BDC_DIR','../DocFormation/');
define('SIDCDGC_BUDGET_URL','../SID/SIDCDGC_BUDGET.txt');
define('SIDCDGC_NBJPRESENCECCIAUXPARSEM_URL','../SID/SIDCDGC_NBJPRESENCECCIAUXPARSEM.txt');
define('ANNUAL_BUDGET','../script/budgetAnnuel.csv');
define('SF_WSDL', realpath(dirname(__FILE__)) . '/../libraries/salesforce/enterprise.wsdl.xml');
define('SF_METADATA_WSDL', realpath(dirname(__FILE__)) . '/../libraries/salesforce/metadata.wsdl.xml');
define('SF_TMP', realpath(dirname(__FILE__)) . '/../tmp/sf');
define('SF_METADATA_TMP', realpath(dirname(__FILE__)) . '/../tmp/sf_meta');
define('CD_RELOAD_TMP', realpath(dirname(__FILE__)) . '/../tmp/last_cd_reload');
define('TALEO_FIND_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/FindService.wsdl.xml');
define('TALEO_SMARTORGFIND_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/SmartorgFindService.wsdl.xml');
define('TALEO_REQUISITION_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/RequisitionService.wsdl.xml');
define('TALEO_CANDIDATE_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/CandidateService.wsdl.xml');
define('TALEO_SMARTORGINTEGRATION_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/SmartorgIntegrationManagementService.wsdl.xml');
define('TALEO_ENTERPRISEINTEGRATION_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/EnterpriseIntegrationManagementService.wsdl.xml');
define('TALEO_UDS_WSDL', realpath(dirname(__FILE__)) . '/../libraries/taleo/UserDefinedSelectionService.wsdl.xml');
define('RIGHT_RJO_WSDL', realpath(dirname(__FILE__)) . '/../libraries/right_rjo/staging_jobservice.asmx.wsdl');
define('RIGHT_RJO_SERVICE_URL', 'http://jobtools.rightstaging.com/services');
        
/**
  * Constantes où l'on stocke la date du jour
  */ 
define('DATE',date('Y-m-d'));
define('DATEFR',date('d-m-Y'));

/**
  * Constantes où l'on stocke le mois en cours
  */
define('MOIS',date('m'));

/**
  * Constantes où l'on stocke le mois en cours
  */
define('ANNEE',date('Y'));

/**
  * Constante où l'on stocke le début de l'année en cours
  */
define('ANNEE_DEBUT',date('Y').'-01-01');

/**
  * Constante où l'on stocke la fin de l'année en cours
  */
define('ANNEE_FIN',date('Y').'-12-31');

/**
  * Constantes où l'on stocke la date du jour avec heures et minutes
  */ 
define('DATETIME',date('Y-m-d H:i:s'));


/**
  * Constantes où l'on stocke les informations de pagination
  */ 
define('TAILLE_LISTE',50);
define('MODE','Sliding');
define('DELTA',4);

/**
  * Constantes où l'on stocke les messages d'erreur
  */ 
define('LOGIN_ERROR','Identifiant et/ou mot de passe incorrect.');
define('RIGHTS_ERROR','Droits insuffisants.');
define('NAME_ERROR','Veuillez entrer un nom');
define('NAISS_ERROR','Veuillez entrer une date de naissance');
define('DATE_ERROR','Veuillez entrer une date');
define('DESIGNATION_ERROR','Veuillez entrer une désignation');
define('MAIL_ERROR','Veuillez entrer une adresse mail valide');
define('PROFIL_ERROR','Veuillez sélectionner un profil');
define('CASE_ERROR','Veuillez sélectionner une affaire');
define('ACCOUNT_ERROR','Veuillez sélectionner un compte');
define('STATUT_ERROR','Veuillez sélectionner un statut');
define('STATE_ERROR','Veuillez sélectionner un état');
define('DOMAIN_ERROR','Veuillez sélectionner un domaine');
define('LIBELLE_ERROR','Veuillez entrer un libellé d\'annonce');
define('REF_ERROR','Veuillez entrer une référence');
define('SALAIRE_ERROR','Veuillez saisir le salaire annuel brut');
define('ASTREINTE_ERROR','Veuillez indiquer s\'il y a des astreintes');
define('RESSOURCE_ERROR','Veuillez sélectionner une ressource');
define('COST_ERROR','Veuillez saisir le montant facturé');
define('TYPE_ERROR','Veuillez sélectionner un type');
define('NATURE_ERROR','Veuillez sélectionner une nature');
define('MANAGER_ERROR','Veuillez sélectionner un responsable');
define('COMMERCIAL_ERROR','Veuillez sélectionner un commercial');
define('INDEMNITE_ERROR','Veuillez indiquer s\'il y a des indemnités à refacturer');
define('AUTH_ERROR','Login ou mot de passe incorrect');
define('ACCESS_ERROR','Accès interdit ! Veuillez vous identifier.');
define('ACCESS_REFUSE','VOUS N\'AVEZ PAS LES AUTORISATIONS NECESSAIRES POUR ACCEDER A CETTE PARTIE !');
define('NON_AUTHORIZED','Vous n\'avez pas accès à cette zone');
define('SRV_ERROR','Le serveur est indisponible pour le moment');
define('ASK_ERROR','Votre demande n\'a pu être traitée. Merci de recommencer.');


/**
  * Constantes où l'on stocke les messages d'information
  */ 
define('SITE_TITLE','GESTION DES AFFAIRES COMMERCIALES ET DES RESSOURCES HUMAINES');
define('WELCOME_INFO','Bienvenue dans l\'AGC');
define('HELLO_INFO','Bienvenue');
define('NO_DATA_INFO','Aucun résultat n\'a été trouvé pour votre requête');
define('SAVEOK','Votre affaire a été correctement enregistrée.');
define('MSG_ERROR','Le message d\'erreur est le suivant : ');
define('MISS_DATA','Il manque des données pour cet item');
define('DELETE_ALL','Supprimer tous les items sélectionnés ?');
define('CONFIRM_DELETE','Confirmer la suppression ?');
define('CONFIRM_DELETE_LAST_PROP','Voulez vous supprimer la dernière Proposition ?');
define('SEND_RH_MAIL','Envoyer le mail au service RH ?');
define('CONFIRM_ARCHIVE','Voulez vous archiver');
define('CONFIRM_UNARCHIVE','Voulez vous désarchiver');
define('FORCED_FIELD','Champs obligatoires');
define('HIRE_FORCED_FIELD','Champs obligatoires lorsque l\'état de la candidature est Embauche CDD, Embauche CDI ou Embauche Intérimaire');
define('CASE_LOAD','CHARGEMENT DES AFFAIRES EN COURS');
define('SESSION_LOAD','CHARGEMENT DES SESSIONS EN COURS');
define('TRAINER_LOAD','CHARGEMENT DES FORMATEURS EN COURS');
define('ROOM_LOAD','CHARGEMENT DES SALLES EN COURS');
define('SUCCESS_ARCHIV','L\'archivage a été réalisé avec succès.');
define('SUCCESS_UNARCHIV','Le désarchivage a été réalisé avec succès.');
define('CANDIDATE_SAVED','Votre candidature a été enregistrée.');
define('MEETING_SAVED','Votre feuille d\'entretien a été enregistrée.');
define('LIEU_PRESTATION_SAVED','Votre lieu de prestation est enregistré.');
define('CD_SAVED','Le contrat délégation a été enregistré.');
define('REF_SAVED','La référence a été enregistré.');
define('CD_DUPLICATED','Le contrat délégation a été dupliqué');
define('ODM_SAVED','L\'ordre de mission a été enregistré.');
define('UNAVAILABLE_DOC','Le document est indisponible.');
define('ODM','ORDRE DE MISSION');
define('ANNONCE','ANNONCES');
define('CONTRAT_DELEGATION','CONTRAT DELEGATION');
define('CONTRAT_DELEGATION_WORK_TIME','Temps de travail contrat délégation');
define('COLLAB_SANS_CONTRAT_DELEGATION','Collaborateurs sans contrat délégation');
define('CASES','AFFAIRES');
define('ASKCHANGE','DEMANDE DE CHANGEMENT');
define('USERS','UTILISATEURS');
define('HELP','AIDE APPLICATION');
define('HELP_WEIGHTING','REFERENTIEL DE PONDERATION');
define('APP_UPDATE','MISE A JOUR DE L\'APPLICATION');
define('MY_NOTE','MON BLOC NOTES');
define('COLOR_HELP','Le code couleur');
define('MY_ANNONCES','Mes annonces');
define('CV_SEARCH','RECHERCHE DE CV');
define('RESOURCES_ASKING','Demande de recrutement');
define('RESOURCES_ASKING_DUPLICATED','La demande de recrutement a été dupliquée');
define('REFERENCE','REFERENCE');
define('LIEUX_PRESTATION','Lieux de prestation');


define('MY_CASES_STATES','ACCES RAPIDE AUX AFFAIRES');
define('MY_CUSTOMERS','MES COMPTES (Clients / Prospects)');
define('MY_WEEK_MEETINGS','MES RENDEZ-VOUS DE LA SEMAINE');
define('MY_WEEK_ACTIONS','MES ACTIONS DE LA SEMAINE');
define('AD_GROUP_ACCES_ZONE_RIGHTS','Gestion des zones d\'accès par groupes d\'utilisateurs');
define('AD_GROUP_ACCES_MENU_RIGHTS','Gestion des menus d\'accès par groupes d\'utilisateurs');


define('CVWEB_MODIFICATION','Les modifications cvweb');
define('UNREAD_APPLICATION','Les candidatures non lues');
define('MY_WEEK_ODM','MES ORDRES DE MISSION DE LA SEMAINE');
define('LANGUAGE_SELECT','Sélectionner une langue');
define('LEVEL_SELECT','Sélectionner un niveau');
define('TYPE_SELECT','Sélectionner un type');
define('TYPE_RESSOURCE_SELECT','Sélectionner un type de ressource');
define('RESSOURCE_SELECT','Sélectionner une ressource');
define('CUSTOMERS_SELECT','Sélectionner un compte');
define('RECRUITER_SELECT','Sélectionner un recruteur');
define('MARKETING_PERSON_SELECT','Sélectionner un commercial');
define('EDITOR_SELECT','Sélectionner un rédacteur');
define('NOTICE_SELECT','Sélectionner un préavis');
define('PROFIL_SELECT','Sélectionner un profil');
define('CONTACT_SELECT','Sélectionner un contact');
define('TRAINER_SELECT','Sélectionner un formateur');
define('ROOM_SELECT','Sélectionner une salle');
define('SERVICE_SELECT','Sélectionner un service');
define('CDS_SELECT','Sélectionner un CDS');
define('AGENCE_SELECT','Sélectionner une agence');
define('SECTION_SELECT','Sélectionner une section');
define('NATIONALITY_SELECT','Sélectionner une nationalité');
define('COUNTRY_SELECT','Sélectionner un pays');
define('SPECIALTY_SELECT','Sélectionner une spécialité');
define('CURSUS_SELECT','Sélectionner un cursus');
define('DURATION_SELECT','Sélectionner une durée');
define('DEPARTMENT_SELECT','Sélectionner un département');
define('MANAGER_SELECT','Sélectionner un responsable');
define('SDM_SELECT','Sélectionner un sdm');
define('STATUS_SELECT','Sélectionner un statut');
define('NATURE_SELECT','Sélectionner une nature');
define('STATE_SELECT','Sélectionner un état');
define('ACTION_SELECT','Sélectionner une action');
define('TITLE_SELECT','Sélectionner un intitulé');
define('PERIOD_SELECT','Sélectionner une période');
define('SESSION_SELECT','Sélectionner une session');
define('REASON_SELECT','Sélectionner une raison');
define('PROGRESS_SELECT','Sélectionner un avancement');
define('MONTH_SELECT','Sélectionner un mois');
define('POLE_SELECT','Sélectionner un pôle');
define('SUBCONTRACTOR_SELECT','Sélectionner un sous-traitant');
define('PROSERVIA_CONTRACT_SELECT','Sélectionner un contrat');
define('CIVIL_STATUS_SELECT','Sélectionner un état matrimonial');
define('INDEMNITE_REPAS_SELECT','Sélectionner une indemnité repas');



define('INTRA_SESSION','Session intra-entreprise');
define('INTER_SESSION','Session inter-entreprise');
define('WITHOUT_TYPE_SESSION','Session sans type');
define('YES','Oui');
define('NO','Non');
define('UNKNOWN','Ne sais pas');
define('MISTER','Mr');
define('MADAM','Mme');
define('MISS','Mlle');
define('SOCIETY','Société');
define('EMPLOYEES','SALARIES');
define('EX_EMPLOYEES','ANCIENS SALARIES');
define('APPLICANT','CANDIDATS');
define('HIRED','EMBAUCHES');
define('TRAINER','FORMATEUR');
define('ROOM','SALLE');
define('CUSTOMERS','COMPTES');
define('CONTACTS','CONTACTS');
define('AGENCES','AGENCES');
define('ACTION','ACTION');
define('APPOINTMENT','RENDEZ-VOUS');
define('STATUS','STATUTS');
define('POLES','POLES');
define('DEMANDS','EXIGENCES');
define('PROFILES','PROFILS');
define('EXPERIENCE','EXPERIENCES');
define('DEGREE_COURSES','CURSUS');
define('ASKRESOURCE','DEMANDE DE RECRUTEMENT');
define('SUBCONTRACTOR','SOUS-TRAITANT');
define('INTERIM','INTERIMAIRE');
define('ASK_SEND_OK','Votre demande a bien été envoyée');
define('ODMCADRETIME','<t2>Organisation du temps de travail / de gestion :</t2> <t3>Autonomie, dans le respect des contrats de la mission.</t3>');
define('ODMETAMTIME','<t2>Horaire d\'intervention sur la prestation à respecter : </t2><t3>%s hebdomadaires. Tout dépassement d\'horaire au-delà des %s hebdomadaires sera considéré comme une prestation complémentaire et devra être soumis à la validation de votre responsable hiérarchique %s.</t3>');
define('ODMCANCELINFO','Cet ordre de mission annule et remplace le précédent');
define('ODMRETURNINFOPROSERVIA','(dès réception, merci de nous retourner deux exemplaires dûment signés)');
define('ODMRETURNINFOOVIALIS','(dès réception, merci de nous retourner un exemplaire dûment signé)');
define('HOMONYME','Il y a un homonyme pour ce candidat, merci de vérifier les candidats existants.');
define('MAINTENANCE','Nous effectuons une mise à jour de votre service pendant quelques minutes. Merci de votre compréhension.');


define('DAYS','jour(s)');
define('MONTHS','mois');
define('YEARS','année(s)');

define('LUNCHEON_VOUCHER','1 ticket restaurant par jour travaillé sauf renonciation individuelle');


define('COMPENSATION_CASE1','Dans cette situation les remboursements se font uniquement sur la base de justificatifs, au réel, selon les conditions suivantes : <br /><br />a) Remboursement des repas : <br />		    - Soit : ticket restaurant <br />			- Soit : remboursement des repas du soir sur justificatif dans la limite de 16 &euro;, si prise en charge d\'un hébergement. <br /><br />	b) Remboursement des hébergements : (sur justificatif)');
define('COMPENSATION_CASE2','Pour être considéré dans cette situation, il faut remplir cumulativement deux conditions : <br /><br /> - condition de distance : au moins 50 km (trajet aller) entre le lieu de résidence et le lieu de travail <br />		 - condition de temps : les transports en commun ne permettent pas au salarié de parcourir cette distance dans un temps inférieur à 1h30 (trajet aller)');
define('COMPENSATION_CASE3','Participation au frais de déplacement : <br /> - Prise en charge de 50 % des abonnements aux transports en commun (Fourniture du justificatifs impératifs à joindre à la note de frais)');


/**
  * Constantes où l'on stocke l'adresse du relai smtp
  */ 
define('SMTP_HOST','mailTESTSTES.proservia.lan');

/**
  * Constantes où l'on stocke le port du relai smtp
  */ 
define('SMTP_PORT',25);

/**
  * Constantes où l'on stocke les paramètres de connexion au serveur LDAP
  */ 
define('LDAP_SRV','gazeille');
define('LDAP_PORT','636');
define('BASE_DN','DC=proservia,DC=lan');
define('LDAP_SEARCH_STRING', 'sAMAccountName=[search]');
define('USE_LDAP_V3',1);
define('NO_REFERRALS',0);
define('NEGOTIATE_TLS',0);
define('ACCOUNT_SUFFIX','@proservia.lan');
define('LDAP_EMAIL','mail');
define('LDAP_FULLNAME','displayName');
define('LDAP_UID','sAMAccountName');
define('AUTH_METHOD','bind');


/*
 * Configuration SSO
 */
define('SSO_PROVIDER_NAME','myauth');

/*
 * Constantes de connexion SSH à l'OSC
 */
define('OSC_SRV','213.56.106.5');
define('OSC_PORT','22');

/**
  * Constantes où l'on stocke les chemins des images
  */
define('IMG_ENTRETIEN','../ui/images/entretien.jpg');
define('IMG_CV','../ui/images/cv.gif');
define('IMG_CVP','../ui/images/cv_proservia.png');
define('IMG_LM','../ui/images/icon1.gif');
define('IMAGE_ICO','../ui/images/favicon.ico');
define('IMG_FLECHE_BAS','../ui/images/fleche-bas.gif');
define('IMG_FLECHE_HAUT','../ui/images/fleche-haut.gif');
define('IMG_CONSULT','../ui/images/view_inline.gif');
define('IMG_EDIT','../ui/images/edit_inline.gif');
define('IMG_CLOSE','../ui/images/close_inline.gif');
define('IMG_COPY','../ui/images/copy.png');
define('IMG_PDF','../ui/images/pdf.png');
define('IMG_ODM','../ui/images/icon1.gif');
define('IMG_PLUS','../ui/images/plus.gif');
define('IMG_MOINS','../ui/images/moins.gif');
define('IMG_LOAD','../ui/images/pleasewait.gif');
define('IMG_CDELEG','../ui/images/cd.jpg');
define('IMG_INFO','../ui/images/info.gif');
define('IMG_HELP','../ui/images/help.jpg');
define('IMG_MAIL','../ui/images/outlook.png');
define('IMG_LEFT_ARROW','../ui/images/arrow_l.gif');
define('IMG_STOP','../ui/images/stop.png');
define('IMG_CALENDAR','../ui/images/calendar.png');
define('IMG_DESC','../ui/images/down.gif');
define('IMG_ASC','../ui/images/up.gif');
define('IMG_UP','../ui/images/up.png');
define('IMG_DOWN','../ui/images/down.png');
define('IMG_BACK','../ui/images/back.png');
define('IMG_FORWARD','../ui/images/forward.png');
define('IMG_LOADING','../ui/images/loading.gif');
define('IMG_CSV','../ui/images/export_csv.png');
define('IMG_IMPERSONATE','../ui/images/impersonate.png');
define('IMG_HELP_OVER','../ui/images/help_over.gif');
define('IMG_HELP','../ui/images/help.gif');

/**
  * Constantes où l'on stocke les messages d'aide
  */
define('HELP_REDACTOR','Les rédacteurs sont les personnes pouvant intervenir en modification sur la fiche affaire.');
define('HELP_PERIOD','Vous ne pouvez pas sélectionner plusieurs fois la même période pour une même année, ni saisir une durée mensuelle < 1 mois. Si la durée est < 1 mois, merci de saisir 1');

define('HELP_TECHNICAL_MODULE','Ce module est réservé aux personnels opérationnels. Il permet la gestion des différents responsables technique et opérationnel intervenant sur votre affaire.');
define('HELP_CONTACT_DETAILS_MODULE','Ce module permet de sélectionner le compte client associé à votre affaire. Les comptes et contacts proviennent de CEGID. En orange et rouge apparaissent les clients à risque de CEGID.');
define('HELP_RESOURCE_CD','Si vous cochez oui, il ne sera pas nécessaire de sélectionner un collaborateur, de même que les horaires, indemnités, astreintes etc.');



/**
  * Constantes pour l'édition des documents du pôle formation
  */ 
define('DOC_SESSION_DIR','../for/DocEdite/');
define('COULEUR_TAB1','#466EA5');
define('COULEUR_TEXTE1','#466EA5');
define('COULEUR_TEXTE2','#FFFFFF');
define('LIGNE1','#8EA3C8');
define('LIGNE2','#BEC9DF');
define('VERSION_WORD','2003');
define('ENTETE','../for/entete.html');
define('SMILEY_T_BON','../for/imageFormation/tbon.jpg');
define('SMILEY_BON','../for/imageFormation/bon.jpg');
define('SMILEY_PASSABLE','../for/imageFormation/passable.jpg');
define('SMILEY_MAUVAIS','../for/imageFormation/mauvais.jpg');

/**
  * Constantes pour les couleurs des candidatures non lues
  */ 
define('CANDIDAT_AIX_SOP','#A9702F');
define('CANDIDAT_BDX','#BA0051');
define('CANDIDAT_LAN','#5151C7');
define('CANDIDAT_LIL','#F47777');
define('CANDIDAT_LYO','#FDD25B');
define('CANDIDAT_001','#F90000');
define('CANDIDAT_NIO','#008E05');
define('CANDIDAT_CAE_LHA_ROU','#F3F94A');
define('CANDIDAT_PAR','#FA8500');
define('CANDIDAT_REN','#A4A4F9');
define('CANDIDAT_TOU','#C2F477');
define('CANDIDAT_TRS','#9E9EA2');

/**
  * Constantes pour les url d'enregistrement des formulaires
  */ 
define('FORM_URL_COM_SAVE','../com/index.php?a=enregistrer');
define('FORM_URL_ADMIN_SAVE','../gestion/index.php?a=enregistrer');
define('FORM_URL_ADMIN_ACTION','../gestion/index.php?a=action');



/**
  * Messages de documentation sur les différents chiffres d'affaires.
  */ 
define('FACT_CA','Ce CA est une donnée extraite directement du système décisionnel. Grâce au lien effectué entre une affaire AGC et une affaire CEGID, il est possible de remonter le CA rééllement facturé pour une affaire AGC.');
define('EXPECTED_CA','Ce CA est calculé à partir des informations de la partie proposition commerciale. Vous pouvez vous référer à la documentation pour avoir le détail des calculs.');
define('PROBABLE_CA','Ce CA est calculé de la même manière que le CA commandé (en jaune) mais pour les périodes comprises entre la date de fin de commande et la date de fin prévisionnelle de votre affaire.');
define('OTHER_CA','Ce CA est calculé de la même manière que le CA commandé mais il est valable uniquement pour les affaires NON SIGNEES, NON OPERATIONNELLES OU NON TERMINEES.');



?>
