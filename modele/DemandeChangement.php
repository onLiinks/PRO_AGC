<?php

/**
 * Fichier DemandeChangement.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe DemandeChangement
 */
class DemandeChangement {

    /**
     * Identifiant de la demande
     *
     * @access private
     * @var int
     */
    private $Id_demande_changement;

    /**
     * Identifiant de la ressource associée à la demande
     *
     * @access public
     * @var string
     */
    public $Id_ressource;

    /**
     * Libellé de la demande de changement
     *
     * @access public
     * @var string
     */
    public $libelle;

    /**
     * Ancienne valeur
     *
     * @access public
     * @var string
     */
    public $ancien;

    /**
     * Nouvelle valeur
     *
     * @access public
     * @var string
     */
    public $nouveau;

    /**
     * Créateur de la demande
     *
     * @access public
     * @var string
     */
    public $createur;

    /**
     * Date de création de la demande
     *
     * @access public
     * @var date
     */
    public $date_creation;

    /**
     * Date de souhait d'intégration de la demande
     *
     * @access public
     * @var date
     */
    public $date_souhaite;

    /**
     * Personne ayant validé la demande
     *
     * @access public
     * @var string
     */
    public $valide_par;

    /**
     * Date de validation de la demande
     *
     * @access public
     * @var date
     */
    public $date_validation;

    /**
     * Personne ayant intégré la demande
     *
     * @access public
     * @var string
     */
    public $integre_par;

    /**
     * Date de l'intégration de la demande
     *
     * @access public
     * @var date
     */
    public $date_integration;

    /**
     * Commentaire relatif à la demande
     *
     * @access public
     * @var string
     */
    public $commentaire;
    
    /**
     * Commentaire relatif à validation de la demande
     *
     * @access public
     * @var string
     */
    public $commentaireValideur;
    
    /**
     * Statut de la demande (V = attente validation, I = attente intégration, R = refusé et C = complété)
     *
     * @access public
     * @var string
     */
    public $statut;

    /**
     * Libellés des demandes de changement
     *
     * @access private
     * @var array
     */
    private static $libelles = array(
        0 => 'Nom',
        1 => 'Adresse',
        2 => 'Tél fixe',
        3 => 'Tél portable',
        4 => 'Mail',
        5 => 'Date embauche',
        6 => 'Libellé emploi',
        7 => 'Statut',
        8 => 'Type embauche',
        9 => 'Fin CDD',
        10 => 'Etat',
        11 => 'Service',
        12 => 'CDS',
        13 => 'Agence',
        14 => 'Pôle',
        15 => 'Profil',
        16 => 'Section',
        17 => 'Indemnité repas'
    );

    /**
     * Tableau contenant les erreurs suite à la création d'une demande de changement
     *
     * @access private
     * @var array
     */
    private $erreurs;

    /**
     * Constructeur de la classe DemandeChangement
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     *
     * @param int Valeur de l'identifiant de la demande
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_demande_changement = '';
                $this->Id_ressource = '';
                $this->type_ressource = '';
                $this->libelle = '';
                $this->ancien = '';
                $this->nouveau = '';
                $this->createur = '';
                $this->date_creation = '';
                $this->date_souhaite = '';
                $this->valide_par = '';
                $this->date_validation = '';
                $this->integre_par = '';
                $this->date_integration = '';
                $this->commentaire = '';
                $this->commentaireValideur = '';
                $this->statut = '';
            }
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_demande_changement = '';
                $this->Id_ressource = htmlscperso(stripslashes($tab['Id_ressource']), ENT_QUOTES);
                $this->type_ressource = htmlscperso(stripslashes($tab['type_ressource']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->ancien = htmlscperso(stripslashes($tab['ancien']), ENT_QUOTES);
                $this->nouveau = htmlscperso(stripslashes($tab['nouveau']), ENT_QUOTES);
                $this->createur = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
                $this->date_souhaite = $tab['date_souhaite'];
                $this->valide_par = htmlscperso(stripslashes($tab['valide_par']), ENT_QUOTES);
                $this->date_validation = $tab['date_validation'];
                $this->integre_par = htmlscperso(stripslashes($tab['integre_par']), ENT_QUOTES);
                $this->date_integration = $tab['date_integration'];
                $this->commentaire = $tab['commentaire'];
                $this->commentaireValideur = $tab['commentaire_valideur'];
            }
            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM demande_changement WHERE Id_demande_changement=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_demande_changement = $code;
                $this->Id_ressource = $ligne->id_ressource;
                $this->type_ressource = $ligne->type_ressource;
                $this->libelle = $ligne->libelle;
                $this->ancien = $ligne->ancien;
                $this->nouveau = $ligne->nouveau;
                $this->createur = $ligne->createur;
                $this->date_creation = $ligne->date_creation;
                $this->date_souhaite = $ligne->date_souhaite;
                $this->valide_par = $ligne->valide_par;
                $this->date_validation = $ligne->date_validation;
                $this->integre_par = $ligne->integre_par;
                $this->date_integration = $ligne->date_integration;
                $this->commentaire = $ligne->commentaire;
                $this->commentaireValideur = $ligne->commentaire_valideur;
                $this->statut = $ligne->statut;
            }
            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_demande_changement = $code;
                $db = connecter();
                $ligne = $db->query('SELECT createur, statut FROM demande_changement WHERE Id_demande_changement=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_ressource = htmlscperso(stripslashes($tab['Id_ressource']), ENT_QUOTES);
                $this->type_ressource = htmlscperso(stripslashes($tab['type_ressource']), ENT_QUOTES);
                $this->libelle = htmlscperso(stripslashes($tab['libelle']), ENT_QUOTES);
                $this->ancien = htmlscperso(stripslashes($tab['ancien']), ENT_QUOTES);
                $this->nouveau = htmlscperso(stripslashes($tab['nouveau']), ENT_QUOTES);
                $this->date_souhaite = $tab['date_souhaite'];
                $this->valide_par = htmlscperso(stripslashes($tab['valide_par']), ENT_QUOTES);
                $this->date_validation = $tab['date_validation'];
                $this->integre_par = htmlscperso(stripslashes($tab['integre_par']), ENT_QUOTES);
                $this->date_integration = $tab['date_integration'];
                $this->commentaire = $tab['commentaire'];
                $this->commentaireValideur = $tab['commentaire_valideur'];
                $this->statut = $ligne->statut;
                $this->createur = $ligne->createur;
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création d'une demande de changement
     *
     * @return string
     */
    public function form() {
        $db = connecter();
        $nb = count(self::$libelles);
        $i = 0;
        $ancien = array();
        while ($i < $nb) {
            $ligne = $db->query('SELECT libelle,Id_demande_changement,date_souhaite,ancien,nouveau,commentaire FROM demande_changement
                        WHERE libelle="' . self::$libelles[$i] . '" AND Id_demande_changement=' . mysql_real_escape_string((int) $this->Id_demande_changement))->fetchRow();
            $nouveau[self::$libelles[$i]] = $ligne->nouveau;
            $ancien[self::$libelles[$i]] = $ligne->ancien;
            $commentaire[self::$libelles[$i]] = $ligne->commentaire;
            $date_souhaitee[self::$libelles[$i]] = $ligne->date_souhaite;
            ++$i;
        }

        if ($this->createur == $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur)
            $submit = '<input id="buttonDC" type="submit" value="' . SAVE_BUTTON . '" onclick="return verifierDC()" />';

        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);
        if ($ressource->mail == $_SESSION[SESSION_PREFIX.'logged']->mail) {
            return 'Impossible de créer une demande de changement pour soi même';
        }
        $id_agence = $ressource->getAgency();
        $ressourceList = $ressource->getList();
  
        if ($this->type_ressource == '') $this->type_ressource = 'SAL';
        $type_ressource[$this->type_ressource] = 'selected="selected"';

        $service = new Service($nouveau['Service'], $ressource->societe);
        $cds = new Cds($nouveau['CDS']);
        $section = new Section($nouveau['Section']);
        $agence = new Agence($nouveau['Agence'], array());
        $profil = new Profil($nouveau['Libellé emploi'], array());
        if(is_numeric($nouveau['Libellé emploi']))
            $pList = $profil->getList();
        else
            $pList = $profil->getEntitledList($ressource->societe);
        $profil_cegid = new Profil($nouveau['Profil'], array());
        $pole = new Pole($nouveau['Pôle'], array());
        $indemniteRepas = new IndemniteRepas($nouveau['Indemnité repas']);
        $statut[$nouveau['Statut']] = 'selected="selected"';
        $type_embauche[$nouveau['Type embauche']] = 'selected="selected"';
        $etat[$nouveau['Etat']] = 'selected="selected"';
        
        $dcValideCheckbox = $dcValideInput = $dcIntegreCheckbox = $dcIntegreInput = $comValideur = '';
        $rejectInput = '<input type="button" class="boutonRejeter" onclick="DCReject(' . $this->Id_demande_changement . ')" onmouseover="return overlib(\'<div class=commentaire>Refuser</div>\', FULLHTML);" onmouseout="return nd();" />';
        if($this->statut == 'V') {
            $colorValidate = '#A4A4F9';
            $colorIntegrate = '#A4A4F9';
        }
        else if($this->statut == 'I') {
            $colorValidate = '#BAE0BA';
            $colorIntegrate = '#A4A4F9';
        }
        else if($this->statut == 'R') {
            $colorValidate = '#EDC3C3';
            $colorIntegrate = '#EDC3C3';
            $dcValideCheckbox = 'disabled="disabled"';
            $dcValideInput = 'readonly="readonly" ';
            $dcIntegreCheckbox = 'disabled="disabled"';
            $dcIntegreInput = 'readonly="readonly" ';
            $rejectInput = '<input type="button" class="boutonExclamation" onclick="DCReopen(' . $this->Id_demande_changement . ')" onmouseover="return overlib(\'<div class=commentaire>Réouvrir</div>\', FULLHTML);" onmouseout="return nd();" />';
        }
        else if($this->statut == 'C') {
            $colorValidate = '#BAE0BA';
            $colorIntegrate = '#BAE0BA';
        }
        
        if (Utilisateur::getDroitIntegrationDemandeChangement($this->Id_demande_changement)) {
            if (!$this->integre_par) {
                if (in_array($this->libelle, array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Indemnité repas')) && !$this->valide_par ) {
                    $dcIntegreCheckbox = 'checked="checked" disabled="disabled"';
                    $dcIntegreInput = 'readonly="readonly" ';
                    $dcIntegre = $this->integre_par . ' le <input id="date_integration' . $this->Id_demande_changement . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($this->date_integration) . '" size="8"><input type="checkbox" id="integre' . $this->Id_demande_changement . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $this->Id_demande_changement . ', 0)" />';

                    $dcIntegre = 'Attente validation';
                } else {
                    $dcIntegreInput = 'onfocus="showCalendarControl(this)"';
                    $dcIntegre = $this->integre_par . ' le <input id="date_integration' . $this->Id_demande_changement . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($this->date_integration) . '" size="8"><input type="checkbox" id="integre' . $this->Id_demande_changement . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $this->Id_demande_changement . ', 0)" />';
                }
            } else {
                $dcIntegre = 'Intégrée par ' . $this->integre_par . ' le ' . FormatageDate($this->date_integration);
            }
        } else {
            if (!$this->integre_par) {
                $dcIntegreCheckbox = 'disabled="disabled"';
                $dcIntegreInput = 'readonly="readonly" ';
                $dcIntegre = $this->integre_par . ' le <input id="date_integration' . $this->Id_demande_changement . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($this->date_integration) . '" size="8"><input type="checkbox" id="integre' . $this->Id_demande_changement . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $this->Id_demande_changement . ', 0)" />';
            } else {
                $dcIntegre = 'Intégrée par ' . $this->integre_par . ' le ' . FormatageDate($this->date_integration);
            }
        }
        $htmlIntegration = '<td bgColor="' . $colorIntegrate . '"><span id="integrate' . $this->Id_demande_changement . '">' . $dcIntegre . '</span></td>';

        $htmlValidation = '';
        if (Utilisateur::getDroitValidationDemandeChangement(($this->type_ressource == 'SAL_STA')?true:false)) {
            if (!$this->valide_par) {
                if (in_array($this->libelle, array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Profil', 'Indemnité repas'))) {
                    if($this->statut != 'R') {
                        $dcIValideInput = 'onfocus="showCalendarControl(this)"';
                        $dcValide = $this->valide_par . ' le <input id="date_validation' . $this->Id_demande_changement . '" ' . $dcValideInput . ' type="text" value="' . FormatageDate($this->date_validation) . '" size="8"><input type="checkbox" id="valide' . $this->Id_demande_changement . '" ' . $dcValideCheckbox . ' onclick="DCValide(' . $this->Id_demande_changement . ', 0)" />';
                    }
                    else {
                        $dcValide = 'Refusée';
                    }
                    if($this->commentaireValideur)
                        $comValideur = ' - <input type="text" id="commentaire_valideur" name="commentaire_valideur" size="60" value="'. $this->commentaireValideur .'" />' . $rejectInput;
                    else
                        $comValideur = ' - <input type="text" id="commentaire_valideur" name="commentaire_valideur" size="60" value="Commentaire du valideur" onFocus="if(this.value == \'Commentaire du valideur\') this.value = \'\'" onblur="if(this.value==\'\') this.value = \'Commentaire du valideur\'" />' . $rejectInput;
                } else {
                    $dcValide = 'Validation non nécessaire';
                }
            } else {
                $dcValide = 'Validée par ' . $this->valide_par . ' le ' . FormatageDate($this->date_validation);
                $comValideur = ' - <input type="text" id="commentaire_valideur" name="commentaire_valideur" size="60" value="'. $this->commentaireValideur .'" readonly="readonly" />';
            }
        } else {
            if (!$this->valide_par) {
                if (array($this->libelle, array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Profil', 'Indemnité repas'))) {
                    $dcValideCheckbox = 'disabled="disabled"';
                    $dcValideInput = 'readonly="readonly" ';
                    if($this->statut != 'R') {
                        $dcValide = $this->valide_par . ' le <input id="date_validation' . $this->Id_demande_changement . '" ' . $dcValideInput . ' type="text" value="' . FormatageDate($this->date_validation) . '" size="8"><input type="checkbox" id="valide' . $this->Id_demande_changement . '" ' . $dcValideCheckbox . ' onclick="DCValide(' . $this->Id_demande_changement . ', 0)" />';
                    } else {
                        $dcValide = 'Refusée';
                    }
                } else {
                    $dcValide = 'Validation non nécessaire';
                }
            } else {
                $dcValide = 'Validée par ' . $this->valide_par . ' le ' . FormatageDate($this->date_validation);
                if($this->commentaireValideur)
                    $comValideur = ' - <input type="text" id="commentaire_valideur" name="commentaire_valideur" size="60" value="'. $this->commentaireValideur .'" readonly="readonly" />';
            }
        }
        $htmlValidation = '<td bgColor="' . $colorValidate . '"><span id="validate' . $this->Id_demande_changement . '">' . $dcValide . '</span></td>';

        if ($this->Id_demande_changement)
            $disabledResource = 'disabled="disabled"';

        $html = '
        <div id="demandeChangement">
            <form enctype="multipart/form-data" action="../membre/index.php?a=enregistrer_demande_changement" method="post" id="formulaire">
                <select id="type_ressource" name="type_ressource" onchange="updateResourceListByType(\'' . $this->Id_ressource . '\')" ' . $disabledResource . '>
                    <option value="SAL">' . TYPE_RESSOURCE_SELECT . '</option>
                    <option value="SAL" ' . $type_ressource['SAL'] . '>Salariés</option>
                    <option value="SAL_STA" ' . $type_ressource['SAL_STA'] . '>Personnel de Structure</option>
                </select>
                <select id="Id_ressource" name="Id_ressource" onchange="updateDemandeChangement(this.value)" ' . $disabledResource . '>
                    <option value="">' . RESSOURCE_SELECT . '</option>
                    ' . $ressourceList . '
                </select>
                <br /><br />';
        if ($this->Id_demande_changement)
            $html .= '<span id="validate' . $this->Id_demande_changement . '" style="background-color:' . $colorValidate . ';display: inline-block;height: 20px;">' . $htmlValidation . '</span> - <span id="integrate' . $this->Id_demande_changement . '" style="background-color:' . $colorIntegrate . ';display: inline-block;height: 20px;">' . $htmlIntegration . '</span>' . $comValideur . '<br />';
        $html .= '<br /><table class="hovercolored">
                    <tr>
                        <th style="text-align:left;">Libellé</th>
                        <th style="text-align:left;">Valeurs Actuelles</th>
                        <th style="text-align:left;">Nouvelles valeurs (ne remplir que si changement)</th>
                        <th style="text-align:left;">Date Souhaitée</th>
                        <th style="text-align:left;">Commentaires</th>
                    </tr>
                    <tr class="row">
                        <td>Libellé emploi</td>
                        <input type="hidden" name="libelle[]" value="Libellé emploi">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="ancien[]" value="' . (($ancien['Libellé emploi']) ? $ancien['Libellé emploi'] : $ressource->profil) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . PROFIL_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $pList . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_libelle_emploi" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Libellé emploi']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Libellé emploi'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Profil</td>
                        <input type="hidden" name="libelle[]" value="Profil">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="ancien[]" value="' . (($ancien['Profil']) ? $ancien['Profil'] : $ressource->profil_cegid) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . PROFIL_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $profil_cegid->getListCegid() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_profil" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Profil']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Profil'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Statut</td>
                        <input type="hidden" name="libelle[]" value="Statut">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td><input type="text" readonly name="ancien[]" value="' . (($ancien['Statut']) ? $ancien['Statut'] : $ressource->statut) . '" /></td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . TYPE_SELECT . '</option>
                                <option value="">----------------------------</option>
                                <option value="ETAM" ' . $statut['ETAM'] . '>ETAM</option>
                                <option value="CADRE" ' . $statut['CADRE'] . '>CADRE</option>
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_statut" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Statut']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Statut'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Fin CDD</td>
                        <input type="hidden" name="libelle[]" value="Fin CDD">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td><input type="text" name="ancien[]" readonly value="' . (($ancien['Fin CDD']) ? $ancien['Fin CDD'] : DateMysqltoFr(str_replace('.000','',$ressource->fin_cdd))) . '" size="8"></td>
                        <td><input type="text" name="nouveau[]" onfocus="showCalendarControl(this)" value="' . $nouveau['Fin CDD'] . '" size="8"></td>
                        <td><input type="text" id="date_souhaite_fin_cdd" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Fin CDD']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Fin CDD'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Etat</td>
                        <input type="hidden" name="libelle[]" value="Etat">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td><input type="text" readonly name="ancien[]" value="' . (($ancien['Etat']) ? $ancien['Etat'] : $ressource->etat) . '" /></td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . STATE_SELECT . '</option>
                                <option value="">----------------------------</option>
                                <option value="Personnel de structure" ' . $etat['Personnel de structure'] . '>Personnel de structure</option>
                                <option value="Collaborateur" ' . $etat['Collaborateur'] . '>Collaborateur</option>
                             </select>
                        </td>
                        <td><input type="text" id="date_souhaite_etat" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Etat']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Etat'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Service (econgé/horoquartz)</td>
                        <input type="hidden" name="libelle[]" value="Service">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="vAncien[]" value="' . (($ancien['Service']) ? Service::GetLibelle($ancien['Service'], $ressource->societe) : Service::GetLibelle($ressource->Id_service, $ressource->societe)) . '" />
                            <input type="hidden" name="ancien[]" value="' . (($ancien['Service']) ? $ancien['Service'] : $ressource->Id_service) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . SERVICE_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $service->getList() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_service" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Service']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Service'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>CDS (effectif)</td>
                        <input type="hidden" name="libelle[]" value="CDS">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="vAncien[]" value="' . (($ancien['CDS']) ? Cds::getLibelle($ancien['CDS']) : Cds::getCDSByResource($ressource->code_ressource)) . '" />
                            <input type="hidden" name="ancien[]" value="' . (($ancien['CDS']) ? $ancien['CDS'] : Cds::getIdCDSByResource($ressource->code_ressource)) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . CDS_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $cds->getList() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_cds" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['CDS']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['CDS'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Agence (mutation)</td>
                        <input type="hidden" name="libelle[]" value="Agence">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="vAncien[]" value="' . (($ancien['Agence']) ? Agence::getLibelle($ancien['Agence']) : Agence::getLibelle($id_agence)) . '">
                            <input type="hidden" name="ancien[]" value="' . (($ancien['Agence']) ? $ancien['Agence'] : $id_agence) . '">
                        </td>
                        <td>
                            <select ' . 'onchange="if (this.value == \'PAR\') { document.getElementById(\'row_section\').style.display=\'\'; } else { document.getElementById(\'row_section\').style.display=\'none\'; }" ' . ' name="nouveau[]">
                                <option value="">' . AGENCE_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $agence->getList() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_agence" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Agence']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Agence'] . '"></td>
                    </tr>
                   
                   <tr class="row" id="row_section" '. (($ancien['Agence'] == 'PAR' || $id_agence == 'PAR') ? /* BU Seulement pour paris */ '' : ' style="display:none;" ') .'>
                        <td>BU - Ventiliation analytique</td>
                        <input type="hidden" name="libelle[]" value="Section">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="vAncien[]" value="' . (($ancien['Section']) ? $section::getLibelle($ancien['Section']) : Section::getSectionByResource($ressource->code_ressource)) . '">
                            <input type="hidden" name="ancien[]" value="' . (($ancien['Section']) ? $ancien['Section'] : Section::getIdSectionByResource($ressource->code_ressource)) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . SECTION_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $section->getList() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_agence" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Section']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Section'] . '"></td>
                    </tr>
                    <tr class="row">
                        <td>Indemnité repas</td>
                        <input type="hidden" name="libelle[]" value="Indemnité repas">
                        <input type="hidden" name="Id[]" value="' . $this->Id_demande_changement . '" />
                        <td>
                            <input type="text" readonly name="vAncien[]" value="' . (($ancien['Indemnité repas']) ? $indemniteRepas::getLibelle($ancien['Indemnité repas']) : IndemniteRepas::getIndemniteByResource($ressource->code_ressource)) . '">
                            <input type="hidden" name="ancien[]" value="' . (($ancien['Indemnité repas']) ? $ancien['Indemnité repas'] : IndemniteRepas::getIdIndemniteByResource($ressource->code_ressource)) . '">
                        </td>
                        <td>
                            <select name="nouveau[]">
                                <option value="">' . INDEMNITE_REPAS_SELECT . '</option>
                                <option value="">----------------------------</option>
                                ' . $indemniteRepas->getList() . '
                            </select>
                        </td>
                        <td><input type="text" id="date_souhaite_indemnite_repas" name="date_souhaite[]" onfocus="showCalendarControl(this)" value="' . FormatageDate($date_souhaitee['Indemnité repas']) . '" size="8"></td>
                        <td><input type="text" name="commentaire[]" size="60" value="' . $commentaire['Indemnité repas'] . '"></td>
                    </tr>
                </table>
                <div class="submit">
                    <input type="hidden" name="Id_ressource" value="' . $this->Id_ressource . '" />
                    <input type="hidden" name="type_ressource" value="' . $this->type_ressource . '" />
                    <input type="hidden" name="class" value="' . __CLASS__ . '" />
                    ' . $submit . '
                    <button type="button" title="Retour" class="button" onclick="self.location.href=\'../membre/index.php?a=consulterDemandeChangement\'">Retour</button> 
                </div>
            </form>
        </div>';
        return $html;
    }

    /**
     * Enregistre les données dans la BDD
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_demande_changement = "' . mysql_real_escape_string($this->Id_demande_changement) . '", Id_ressource = "' . mysql_real_escape_string($this->Id_ressource) . '"
                 , libelle = "' . mysql_real_escape_string($this->libelle) . '", ancien = "' . mysql_real_escape_string($this->ancien) . '", nouveau = "' . mysql_real_escape_string($this->nouveau) . '"
		 , date_souhaite = "' . mysql_real_escape_string(DateMysqltoFr($this->date_souhaite, 'mysql')) . '", valide_par = "' . mysql_real_escape_string($this->valide_par) . '"
		 , date_validation = "' . mysql_real_escape_string(DateMysqltoFr($this->date_validation, 'mysql')) . '", integre_par = "' . mysql_real_escape_string($this->valide_par) . '"
		 , date_integration = "' . mysql_real_escape_string(DateMysqltoFr($this->date_integration, 'mysql')) . '", commentaire = "' . mysql_real_escape_string($this->commentaire) . '"
		 , commentaire_valideur = "", type_ressource = "' . mysql_real_escape_string($this->type_ressource) . '"';
        if ($this->Id_demande_changement) {
            $requete = 'UPDATE demande_changement ' . $set . ' WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement);
        } else {
            $requete = 'INSERT INTO demande_changement ' . $set . ' , createur = "' . mysql_real_escape_string($this->createur) . '", date_creation = "' . mysql_real_escape_string(DATETIME) . '"';
        }

        $db->query($requete);
        $idDemandeChangement = (empty($this->Id_demande_changement)) ? $db->queryOne('SELECT LAST_INSERT_ID()') : $this->Id_demande_changement;
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);
        $id_agence = $ressource->getAgency();

        $createur = formatPrenomNom($this->createur);
        $libelle = strtolower($this->libelle);
        $lien = '<a href="' . BASE_URL . 'membre/index.php?a=modifier&class=DemandeChangement&Id=' . $idDemandeChangement . '">demande n° ' . $idDemandeChangement . '</a>';

        switch ($this->libelle) {
            case 'Libellé emploi':
                $nouveau = Profil::getLibelle($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Profil':
                $nouveau = Profil::getLibelleCegid($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Pôle':
                $nouveau = Pole::getCegidLibelle($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Agence':
                $nouveau = Agence::getLibelle($this->nouveau);
                $ancien = Agence::getLibelle($this->ancien);
                break;
            case 'Service':
                $nouveau = $this->nouveau.' - '.Service::getLibelle($this->nouveau, $ressource->societe);
                $ancien = $this->ancien.' - '.Service::getLibelle($this->ancien, $ressource->societe);
                break;
            case 'CDS':
                $nouveau = Cds::getLibelle($this->nouveau);
                $ancien = Cds::getLibelle($this->ancien);
                break;
            case 'Section':
                $nouveau = $this->nouveau.' - '.Section::getLibelle($this->nouveau);
                $ancien = $this->ancien.' - '.Section::getLibelle($this->ancien);
                break;
            case 'Indemnité repas':
                $nouveau = IndemniteRepas::getLibelle($this->nouveau);
                $ancien = IndemniteRepas::getLibelle($this->ancien);
                break;

            default:
                $nouveau = strtolower($this->nouveau);
                $ancien = strtolower($this->ancien);
                break;
        }

        if (!$this->Id_demande_changement || $this->statut == 'R') {
            if ("Libellé emploi" == $this->libelle || "Statut" == $this->libelle ||
                    "Type embauche" == $this->libelle || "Etat" == $this->libelle ||
                    "Agence" == $this->libelle || "CDS" == $this->libelle ||
                    "Profil" == $this->libelle || "Indemnité repas" == $this->libelle
            ) {
                $this->Id_demande_changement = $idDemandeChangement;
                $this->updateStatut('V');
                $rh = Agence::getRHrecipientMail($id_agence);
                if ($this->type_ressource == 'SAL') {                   
                    if(strpos(Cds::getCDSByResource($ressource->code_ressource), 'Hors CDS') === false) {
                        $to = Agence::getRHrecipientMail('CDSN');
                    }
                    else if($this->libelle == 'Etat' && $this->nouveau == 'Personnel de structure') {
                        $to = GESTIONNAIRE_STAFF;
                    }
                    else {
                        $to = $rh;
                    }
                }
                elseif ($this->type_ressource == 'SAL_STA') {
                    $to = explode(',', MAIL_EMBAUCHE_STAFF_DEST);
                }
                $message = new Mail_mime("\r\n");
                $headers = array("From" => $this->createur . '@proservia.fr', "To" => $to, "Subject" => 'Nouvelle demande de changement');
                $body = <<<EOT
    <html><body>Bonjour,<br /><br />

    {$createur} vient de soumettre une demande de changement à validation pour {$ressource->prenom} {$ressource->nom} - matricule {$ressource->code_ressource}.<br />
    Celle-ci concerne un changement de {$libelle} ({$ancien} en {$nouveau}) à la date souhaitée {$this->date_souhaite}<br />
    Commentaire associé : {$this->commentaire}<br /><br />
    Lien vers la demande de changement : {$lien}</body></html>
EOT;
                $message->setHTMLBody($body);

                $params['host'] = SMTP_HOST;
                $params['port'] = SMTP_PORT;
                $body = $message->get();
                $hdrs = $message->headers($headers);
                $mail_object = Mail::factory('smtp', $params);
                $send = $mail_object->send($to, $hdrs, $body);
                if (PEAR::isError($send)) {
                    print($send->getMessage());
                }
            } else {
                $this->Id_demande_changement = $idDemandeChangement;
                $this->updateStatut('I');
                $gestionnairePaieMail = null;
                 if ($ressource->type_ressource == 'SAL') {
                     $gestionnairePaieMail = $ressource->getGestionnairePaieMail();
                 }
                 if ($gestionnairePaieMail != null) {
                     $to = $gestionnairePaieMail;
	
                 }else {
                     $to = $this->getDestinataireMail($id_agence, 2);
                 }
                $message = new Mail_mime("\r\n");
                $headers = array("From" => $this->createur . '@proservia.fr', "To" => $to, "Subject" => 'Nouvelle demande de changement');
                $body = <<<EOT
    <html><body>Bonjour,<br /><br />

    {$createur} vient de soumettre une demande de changement à intégrer pour {$ressource->prenom} {$ressource->nom} - matricule {$ressource->code_ressource}.<br />
    Celle-ci concerne un changement de {$libelle} ({$ancien} en {$nouveau}) à la date souhaitée {$this->date_souhaite}<br />
    Commentaire associé : {$this->commentaire}<br /><br />
    Lien vers la demande de changement : {$lien}</body></html>
EOT;
                $message->setHTMLBody($body);

                $params['host'] = SMTP_HOST;
                $params['port'] = SMTP_PORT;
                $body = $message->get();
                $hdrs = $message->headers($headers);
                $mail_object = Mail::factory('smtp', $params);
                $send = $mail_object->send($to, $hdrs, $body);
                if (PEAR::isError($send)) {
                    print($send->getMessage());
                }
            }
        }
    }

    /**
     * Recherche d'une demande de changement
     *
     * @return string
     */
    public static function search($Id_demande, $createur, $Id_ressource, $debut=ANNEE_DEBUT, $fin=ANNEE_FIN, $aValider = '', $aIntegrer ='', $output = array('type' => 'TABLE')) {
        $arguments = array('Id_demande_changement', 'createur', 'Id_ressource', 'debut', 'fin', 'valide', 'integre', 'output');
        $columns = array(array('Id','Id_demande_changement'), array('Créateur','createur'), array('Date Création','date_creation'),
                         array('Date Souhaitée','date_souhaite'),array('Concernant','none'),array('Libellé','libelle'),
                         array('Ancien','none'),array('Nouveau','none'),array('Validé Par','valide_par'),
                         array('Intégré Par','integre_par'));
        $db = connecter();
        $requete = 'SELECT Id_demande_changement, Id_ressource, libelle, ancien, nouveau, createur, 
                        DATE_FORMAT(date_creation, "%Y-%m-%d") AS date_creation,
                        DATE_FORMAT(date_creation, "%d-%m-%Y") AS date_creation_fr,
                        date_souhaite,DATE_FORMAT(date_souhaite, "%d-%m-%Y") AS date_souhaite_fr,
                        valide_par, type_ressource,
                        date_validation, integre_par, date_integration, commentaire, commentaire_valideur, statut
                    FROM demande_changement WHERE Id_demande_changement!=""';
        if ($Id_demande) {
            $requete .= ' AND Id_demande_changement=' . (int) $Id_demande;
        }
        if ($createur) {
            $requete .= ' AND createur="' . $createur . '"';
        }
        if ($Id_ressource) {
            $requete .= ' AND Id_ressource="' . $Id_ressource . '"';
        }
        if ($debut && $fin) {
            $requete .= ' AND date_creation BETWEEN "' . DateMysqltoFr($debut, 'mysql') . '" AND "' . DateMysqltoFr($fin, 'mysql') . '"';
        }
        if (is_numeric($aValider)) {
            if ($aValider == 0)
                $requete .= ' AND valide_par = ""';
            else
                $requete .= ' AND valide_par != ""';
        }
        if (is_numeric($aIntegrer)) {
            if ($aIntegrer == 0)
                $requete .= ' AND integre_par = ""';
            else
                $requete .= ' AND integre_par != ""';
        }

        $params = '';
        foreach (func_get_args() as $key => $value) {
            if ($arguments[$key] != 'output')
                $params .= $arguments[$key] . '=' . $value . '&';
        }
        if($output['orderBy']) {
            $paramsOrder .= 'orderBy=' . $output['orderBy'];
            $orderBy = $output['orderBy'];
        }
        else {
            $paramsOrder .= 'orderBy=date_creation';
            $orderBy = 'date_creation';
        }
        if($output['direction']) {
            $paramsOrder .= '&direction=' . $output['direction'];
            $direction = $output['direction'];
        }
        else {
            $paramsOrder .= '&direction=DESC';
            $direction = 'DESC';
        }
        $requete .= ' ORDER BY ' . $orderBy . ' ' . $direction . ', Id_demande_changement ' . $direction;
        
        if ($output['type'] == '' || $output['type'] == 'TABLE') {
            $utilisateur = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur, array());
            $pager_params = array('mode' => MODE, 'append' => false, 'path' => '',
                'fileName' => '#%d', 'urlVar' => 'page',
                'onclick' => 'afficherDemandeChangement({\'page\' : %d, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});',
                'perPage' => TAILLE_LISTE, 'delta' => DELTA);
            $paged_data = Pager_Wrapper_MDB2($db, $requete, $pager_params);
            
            if (!$paged_data['totalItems']) {
                $html = NO_DATA_INFO;
            } else {
                $html .= '
                    <p class="pagination">' . $paged_data['links'] . '<span style="float:left"><a href="../source/index.php?a=consulterDemandeChangement&type=CSV&' . $params . $paramsOrder . '" /><img src="' . IMG_CSV . '" alt="Export en CSV" onmouseout="return nd();" onmouseover="return overlib(\'<div class=commentaire>Export Excel</div>\', FULLHTML);" /></a>&nbsp;&nbsp;&nbsp;' . $paged_data['totalItems'] . ' résultat(s)</span></p>
                    <table class="hovercolored">
                        <tr>';
                foreach ($columns as $value) {
                    $orderBy = $value[1];
                    if($value[1] == $output['orderBy'])
                        if($output['direction'] == 'DESC') {
                            $direction = 'ASC';
                            $img[$value[1]] = '<img src="' . IMG_DESC . '" />';
                        }
                        else {
                             $direction = 'DESC';
                             $img[$value[1]] = '<img src="' . IMG_ASC . '" />';
                        }
                    else if(!$output['orderBy']) {
                        $direction = 'ASC';
                        $img['date_creation'] = '<img src="' . IMG_DESC . '" />';
                    }
                    else {
                        $direction = 'ASC';
                    }
                    if($value[1] == 'none')
                        $html .= '<th>' . $value[0] . '</th>';
                    else
                        $html .= '<th><a href="#" onclick="afficherDemandeChangement({\'page\' : 1, \'sort\' : [{\'field\' : \'' . $orderBy . '\', \'direction\' : \'' . $direction . '\'}]});">' . $value[0] . '</a>' . $img[$value[1]] . '</th>';
                }
                $html .= '</tr>';

                $i = 0;
                foreach ($paged_data['data'] as $ligne) {
                    $staff = ($ligne['type_ressource']=='SAL_STA') ? true : false;
                    if ($utilisateur->getResourceRight($staff, 8) === true) {

                    } elseif ($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur == $ligne['createur']) {

                    } else {
                        continue;
                    }
                    
                    $j = ($i % 2 == 0) ? 'class="rowodd"' : 'class="roweven"';
                    $html .= '
                        <tr ' . $j . '>
                            <td>' . $ligne['id_demande_changement'] . '</td>
                            <td>' . self::showCreator($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showDateCreation($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showDate($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showResource($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showTitle($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showAncient($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showNew($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showValidateBy($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showIntegrateBy($ligne, array('csv' => false)) . '</td>
                            <td>' . self::showButtons($ligne) . '</td>
                        </tr>';
                    ++$i;
                }
                $html .= '</table><br /><p class="pagination">' . $paged_data['links'] . '</p>';
            }
        }
        elseif ($output['type'] == 'CSV') {
            $result = $db->query($requete);

            header("Pragma: public");
            header('Content-type: text/x-csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="demande_changement.csv"');
            
            foreach ($columns as $value) {
                echo $value[0] . ';';
            }
            echo PHP_EOL;
            while ($ligne = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {                
                echo $ligne['id_demande_changement'] . ';';
                echo '"' . self::showCreator($ligne, array('csv' => true)) . '";';
                echo self::showDateCreation($ligne, array('csv' => true)) . ';';
                echo self::showDate($ligne, array('csv' => true)) . ';';
                echo '"' . self::showResource($ligne, array('csv' => true)) . '";';
                echo '"' . self::showTitle($ligne, array('csv' => true)) . '";';
                echo '"' . self::showAncient($ligne, array('csv' => true)) . '";';
                echo '"' . self::showNew($ligne, array('csv' => true)) . '";';
                echo '"' . $ligne['valide_par'] . '";';
                echo $ligne['date_validation'] . ';';
                echo '"' . $ligne['integre_par'] . '";';
                echo $ligne['date_integration'] . ';';
                echo PHP_EOL;
            }
        }
        return $html;
    }

    /**
     * Affichage du formulaire de recherche des demandes de changement
     *
     * @return string
     */
    public static function searchForm() {
        if (empty($_SESSION['filtre']['createur'])) {
            $_SESSION['filtre']['createur'] = $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur;
        }
        $createur = new Utilisateur($_SESSION['filtre']['createur'], array());
        $salaries = RessourceFactory::create('SAL', $_SESSION['filtre']['Id_ressource'], null);
        $staff = RessourceFactory::create('SAL_STA', $_SESSION['filtre']['Id_ressource'], null);
        
        $html = '
			Id : <input id="Id_demande_changement" type="text" onkeyup="afficherDemandeChangement()" value="' . $_SESSION['filtre']['Id_demande_changement'] . '" size="2" />
			&nbsp;&nbsp;
		    <select id="createur" onchange="afficherDemandeChangement()">
                <option value="">Par créateur</option>
                <option value="">----------------------------</option>
                ' . $createur->getList("COM") . '
                <option value="">----------------------------</option>
                ' . $createur->getList("OP") . '
                    <option value="">----------------------------</option>
                ' . $createur->getList("RH") . '
            </select>
			&nbsp;&nbsp;
		    <select id="Id_ressource" onchange="afficherDemandeChangement()">
                <option value="">Par salarié</option>
                <option value="">----------------------------</option>
			    ' . $salaries->getEmployeeList() . '
                            ' . $staff->getList() . '
            </select>
			&nbsp;&nbsp;			
			du <input id="debut" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['debut'] . '" size="8" />
            &nbsp;
			au <input id="fin" type="text" onfocus="showCalendarControl(this)" value="' . $_SESSION['filtre']['fin'] . '" size="8" />
			&nbsp;&nbsp;
                        &nbsp;&nbsp;
                        <select id="valide" onchange="afficherDemandeChangement()">
                            <option value="">Validé</option>
                            <option value="">----------------------------</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                        &nbsp;&nbsp;
                        <select id="integre" onchange="afficherDemandeChangement()">
                            <option value="">Intégré</option>
                            <option value="">----------------------------</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
			<input type="button" onclick="afficherDemandeChangement()" value="Go !">
';
        return $html;
    }

    /**
     * Mise à jour des champs valide_par et date_validation de la demande de changement
     *
     * @param int Identifiant de la demande de changement
     * @param date Date de validation de la demande de changement
     *
     */
    public function validate($date_validation, $commentaireValideur = NULL, $table = 0) {
        $db = connecter();
        if ($date_validation == '00-00-0000')
            $date_validation = date('d-m-Y');
        $db->query('UPDATE demande_changement SET statut = "I", valide_par="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '", date_validation="' . mysql_real_escape_string(DateMysqltoFr($date_validation, 'mysql')) . '", commentaire_valideur = "' . mysql_real_escape_string($commentaireValideur) . '" WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));
        
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);
        $id_agence = $ressource->getAgency();

        $createur = formatPrenomNom($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur);
        $libelle = strtolower($this->libelle);
        $date_souhaite = DateMysqltoFr($this->date_souhaite);
        $lien = '<a href="' . BASE_URL . 'membre/index.php?a=modifier&class=DemandeChangement&Id=' . $this->Id_demande_changement . '">demande n° ' . $this->Id_demande_changement . '</a>';

        switch ($this->libelle) {
            case 'Libellé emploi':
                $nouveau = Profil::getLibelle($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Profil':
                $nouveau = Profil::getLibelleCegid($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Pôle':
                $nouveau = Pole::getCegidLibelle($this->nouveau);
                $ancien = $this->ancien;
                break;
            case 'Agence':
                $nouveau = Agence::getLibelle($this->nouveau);
                $ancien = Agence::getLibelle($this->ancien);
                break;
            case 'Service':
                $nouveau = $this->nouveau.' - '.Service::getLibelle($this->nouveau);
                $ancien = $this->ancien.' - '.Service::getLibelle($this->ancien);
                break;
            case 'CDS':
                $nouveau = Cds::getLibelle($this->nouveau);
                $ancien = Cds::getLibelle($this->ancien);
                break;
            case 'Section':
                $nouveau = $this->nouveau.' - '.Section::getLibelle($this->nouveau);
                $ancien = $this->ancien.' - '.Section::getLibelle($this->ancien);
                break;            
            case 'Indemnité repas':
                $nouveau = IndemniteRepas::getLibelle($this->nouveau);
                $ancien = IndemniteRepas::getLibelle($this->ancien);
                break;

            default:
                $nouveau = strtolower($this->nouveau);
                $ancien = strtolower($this->ancien);
                break;
        }

         $gestionnairePaieMail = null;
         if ($ressource->type_ressource == 'SAL') {
             $gestionnairePaieMail = $ressource->getGestionnairePaieMail();
         }
         if ($gestionnairePaieMail != null) {
             $to = $gestionnairePaieMail;
         }else {
             $to = $this->getDestinataireMail($id_agence, 2);
         }
        $message = new Mail_mime("\r\n");
        $headers = array("From" => $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr', "To" => $to, "Subject" => 'Intégration demande de changement');
        $body = <<<EOT
<html><body>Bonjour,<br /><br />

{$createur} vient de valider la demande de changement pour {$ressource->prenom} {$ressource->nom} - matricule {$ressource->code_ressource}. La date d'intégration souhaitée est le {$date_souhaite}.<br />
Celle-ci concerne un changement de {$libelle} ({$ancien} en {$nouveau})<br />
Commentaire associé : {$this->commentaire}<br />
Commentaire du valideur : {$commentaireValideur}<br /><br /><br /><br />
Lien vers la demande de changement : {$lien}</body></html>
EOT;
        $message->setHTMLBody($body);

        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $body = $message->get();
        $hdrs = $message->headers($headers);
        $mail_object = Mail::factory('smtp', $params);
        $send = $mail_object->send($to, $hdrs, $body);
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }
        if (!$table) {
            $msg .= 'Validée par ';
        }
        return $msg . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . ' le ' . $date_validation;
    }

    /**
     * Mise à jour des champs integre_par et date_integration de la demande de changement
     *
     * @param int Identifiant de la demande de changement
     * @param date Date d'intégration de la demande de changement
     *
     */
    public function integrate($date_integration, $table) {
        $db = connecter();
        if ($date_integration == '00-00-0000')
            $date_integration = date('d-m-Y');
        $db->query('UPDATE demande_changement SET statut = "C", integre_par="' . mysql_real_escape_string($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur) . '", date_integration="' . mysql_real_escape_string(DateMysqltoFr($date_integration, 'mysql')) . '" WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));

        if ("Service" == $this->libelle || "Pôle" == $this->libelle ||
                "Etat" == $this->libelle || "Agence" == $this->libelle ||
                "CDS" == $this->libelle
        ) {
            $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);

            $createur = formatPrenomNom($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur);
            $libelle = strtolower($this->libelle);
            $date_souhaite = DateMysqltoFr($this->date_souhaite);
            $lien = '<a href="' . BASE_URL . 'membre/index.php?a=modifier&class=DemandeChangement&Id=' . $this->Id_demande_changement . '">demande n° ' . $this->Id_demande_changement . '</a>';

            switch ($this->libelle) {
                case 'Libellé emploi':
                    $nouveau = Profil::getLibelle($this->nouveau);
                    $ancien = $this->ancien;
                    break;
                case 'Profil':
                    $nouveau = Profil::getLibelleCegid($this->nouveau);
                    $ancien = $this->ancien;
                    break;
                case 'Pôle':
                    $nouveau = Pole::getCegidLibelle($this->nouveau);
                    $ancien = $this->ancien;
                    break;
                case 'Agence':
                    $nouveau = Agence::getLibelle($this->nouveau);
                    $ancien = $this->ancien;
                    break;
                case 'Service':
                    $nouveau = $this->nouveau.' - '.Service::getLibelle($this->nouveau);
                    $ancien = $this->ancien.' - '.Service::getLibelle($this->ancien);
                    break;
                case 'CDS':
                    $nouveau = Cds::getLibelle($this->nouveau);
                    $ancien = $this->ancien;
                    break;
                case 'Section':
                    $nouveau = $this->nouveau.' - '.Section::getLibelle($this->nouveau);
                    $ancien = $this->ancien.' - '.Section::getLibelle($this->ancien);
                    break;
                case 'Indemnité repas':
                    $nouveau = IndemniteRepas::getLibelle($this->nouveau);
                    $ancien = IndemniteRepas::getLibelle($this->ancien);
                    break;

                default:
                    $nouveau = strtolower($this->nouveau);
                    $ancien = strtolower($this->ancien);
                    break;
            }

            $to = MAIL_MANAGEMENT_CONTROL_DEST;
            $recepteur = formatPrenomNom($to, true);
            $message = new Mail_mime("\r\n");
            $headers = array("From" => $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr', "To" => $to, "Subject" => 'Demande de changement concernant ' . $ressource->prenom . ' ' . $ressource->nom);
            $body = <<<EOT
<html><body>Bonjour {$recepteur},<br /><br />

Une nouvelle demande de changement a été intégrée pour {$ressource->prenom} {$ressource->nom} - matricule {$ressource->code_ressource}. La date d'intégration souhaitée est le {$date_souhaite}.<br />
Celle-ci concerne un changement de {$libelle} ({$ancien} en {$nouveau})<br />
Commentaire associé : {$this->commentaire}<br /><br />
Lien vers la demande de changement : {$lien}</body></html>
EOT;
            $message->setHTMLBody($body);

            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $body = $message->get();
            $hdrs = $message->headers($headers);
            $mail_object = Mail::factory('smtp', $params);
            $send = $mail_object->send($to, $hdrs, $body);
            if (PEAR::isError($send)) {
                print($send->getMessage());
            }
        }

        if (!$table) {
            $msg .= 'Intégrée par ';
        }
        return $msg . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . ' le ' . $date_integration;
    }
    
    /**
     * Refus de la demande de changement
     *
     */
    public function reject($commentaireValideur = NULL) {
        $db = connecter();
        if ($date_integration == '00-00-0000')
            $date_integration = date('d-m-Y');
        $db->query('UPDATE demande_changement SET statut = "R", commentaire_valideur = "' . mysql_real_escape_string($commentaireValideur) . '" WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));
        
        $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);
        $lien = '<a href="' . BASE_URL . 'membre/index.php?a=modifier&class=DemandeChangement&Id=' . $this->Id_demande_changement . '">demande n° ' . $this->Id_demande_changement . '</a>';

        $to = (new Utilisateur($this->createur,array()))->mail;
        $recepteur = formatPrenomNom($to, true);
        $message = new Mail_mime("\r\n");
        $headers = array("From" => $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . '@proservia.fr', "To" => $to, "Subject" => 'Demande de changement n°  ' . $this->Id_demande_changement . ' : non validé');
        $body = <<<EOT
<html><body>Bonjour {$recepteur},<br /><br />

Votre demande de changement pour {$ressource->prenom} {$ressource->nom} - matricule {$ressource->code_ressource} a été refusée.<br />
Commentaire RH : {$commentaireValideur}<br />
Lien vers la demande de changement : {$lien}</body></html>
EOT;
        $message->setHTMLBody($body);

        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $body = $message->get();
        $hdrs = $message->headers($headers);
        $mail_object = Mail::factory('smtp', $params);
        $send = $mail_object->send($to, $hdrs, $body);
        if (PEAR::isError($send)) {
            print($send->getMessage());
        }

        return 'Refusée';
    }
    
    /**
     * Réouvre la demande de changement
     *
     */
    public function reopen() {
        $db = connecter();
        $db->query('UPDATE demande_changement SET statut = "V", valide_par = "", integre_par = "", date_validation = "00-00-0000", date_integration = "00-00-0000" WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));

        return 'Réouvert';
    }

    /**
     * Suppression d'une demande de changement
     */
    public function delete() {
        $db = connecter();
        $db->query('DELETE FROM demande_changement WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));
    }

    /**
     * Récupération du mail destinataire de la demande de changement
     *
     * @param string Identifiant de l'agence
     * @param int Identifiant du service
     *
     * @return string
     */
    public function getDestinataireMail($Id_agence, $service) {
        $db = connecter();
        if($this->type_ressource == 'SAL') {
            $ressource = RessourceFactory::create($this->type_ressource, $this->Id_ressource, null);
            $staff = $ressource->isStaff();
        }
        elseif($this->type_ressource == 'SAL_STA') {
            $staff = true;
        }
        if ($staff === true) {
            return MAIL_CDHA_DEST;
        }
        else {
            $r = $db->query('SELECT mail FROM destinataire_cd WHERE Id_agence = "' . mysql_real_escape_string($Id_agence) . '" AND service="' . mysql_real_escape_string($service) . '"');
            $a = array();
            while ($ligne = $r->fetchRow()) {
                array_push($a, $ligne->mail);
            }
            return $a;
        }
    }
    
    /**
     * Mise à jour du statut
     */
    public function updateStatut($state = 'V') {
        $db = connecter();
        $db->query('UPDATE demande_changement SET statut = "' . $state . '" WHERE Id_demande_changement = ' . mysql_real_escape_string((int) $this->Id_demande_changement));
    }

    /*
     *
     * Fonctions d'affichage des valeurs pour le datagrid
     *
     */
    
    public function showCreator($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        if (!$args['csv']) return '<div ' . $info . '>' . $record['createur'] . '</div>';
        else return $record['createur'];
    }
    
    public function showDateCreation($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        if (!$args['csv']) return '<div ' . $info . '>' . FormatageDate($record['date_creation']) . '</div>';
        else return FormatageDate($record['date_creation']);
    }
    
    public function showDate($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        if (!$args['csv']) return '<div ' . $info . '>' . FormatageDate($record['date_souhaite']) . '</div>';
        else return FormatageDate($record['date_souhaite']);
    }

    public function showResource($record, $args){
        if($record['type_ressource'] == 'SAL_STA'){
            $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], null);
            $n = $ressource->getName();
        }else{
            $record['type_ressource'] = 'SAL';
            $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], null);
            $n = $ressource->getName();
        }
        if($n == ''){
            $record['type_ressource'] = 'SAL_STA';
            $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], null);
            $n = $ressource->getName();
        }


        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        if (!$args['csv']) return '<div ' . $info . '>' . $n . '</div>';
        else return $n;
    }
    
    public function showTitle($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        if (!$args['csv']) return '<div ' . $info . '>' . $record['libelle'] . '</div>';
        else return $record['libelle'];
    }

    public function showAncient($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        $nouveau = $record['nouveau'];
        switch ($record['libelle']) {
            case 'Libellé emploi':
                $ancien = $record['ancien'];
                break;
            case 'Profil':
                $ancien = $record['ancien'];
                break;
            case 'Pôle':
                $ancien = $record['ancien'];
                break;
            case 'Agence':
                $ancien = Agence::getLibelle($record['ancien']);
                break;
            case 'Service':
                $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], null);
                $ancien = Service::getLibelle($record['ancien'], $ressource->societe);
                break;
            case 'CDS':
                $ancien = Cds::getLibelle($record['ancien']);
                break;
            case 'Section':
                $ancien = Section::getLibelle($record['ancien']);
                break;
            case 'Indemnité repas':
                $ancien = IndemniteRepas::getLibelle($record['ancien']);
                break;

            default:
                $ancien = $record['ancien'];
                break;
        }
        if (!$args['csv']) return '<div ' . $info . '>' . $ancien . '</div>';
        else return $ancien;
    }
    
    public function showNew($record, $args) {
        if ($record['commentaire'] || $record['commentaire_valideur']) {
            if ($record['commentaire_valideur']) {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '<br />Commentaire valideur : ' . str_replace('"', "'", mysql_escape_string($record['commentaire_valideur'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
            else {
                $info = 'onmouseover="return overlib(\'<div class=commentaire>Commentaire : ' . str_replace('"', "'", mysql_escape_string($record['commentaire'])) . '</div>\', FULLHTML);" onmouseout="return nd();"';
            }
        }
        else
            $info = '';
        
        $nouveau = $record['nouveau'];
        switch ($record['libelle']) {
            case 'Libellé emploi':
                $nouveau = Profil::getLibelle($record['nouveau']);
                break;
            case 'Profil':
                $nouveau = Profil::getLibelleCegid($record['nouveau']);
                break;
            case 'Pôle':
                $nouveau = Pole::getCegidLibelle($record['nouveau']);
                break;
            case 'Agence':
                $nouveau = Agence::getLibelle($record['nouveau']);
                break;
            case 'Service':
                $ressource = RessourceFactory::create($record['type_ressource'], $record['id_ressource'], null);
                $nouveau = Service::getLibelle($record['nouveau'], $ressource->societe);
                break;
            case 'CDS':
                $nouveau = Cds::getLibelle($record['nouveau']);
                break;
            case 'Section':
                $nouveau = Section::getLibelle($record['nouveau']);
                break;
            case 'Indemnité repas':
                $nouveau = IndemniteRepas::getLibelle($record['nouveau']);
                break;
                
            default:
                $nouveau = $record['nouveau'];
                break;
        }
        if (!$args['csv']) return '<div ' . $info . '>' . $nouveau . '</div>';
        else return $nouveau;
    }
    
    public function showValidateBy($record, $args) {
        $dcvalide = $dcValideCheckbox = $dcValideInput = $htmlValidation = '';
        if ($record['valide_par']) {
            $dcvalide = 'checked="checked"';
        }
        
        if($record['statut'] == 'V') {
            $colorValidate = '#A4A4F9';
        }
        else if($record['statut'] == 'I') {
            $colorValidate = '#BAE0BA';
        }
        else if($record['statut'] == 'R') {
            $colorValidate = '#EDC3C3';
            $dcValideCheckbox = 'disabled="disabled"';
            $dcValideInput = 'readonly="readonly" ';
        }
        else if($record['statut'] == 'C') {
            $colorValidate = '#BAE0BA';
        }

        
        if (Utilisateur::getDroitValidationDemandeChangement(($record['type_ressource'] == 'SAL_STA'))) {
            if (!$record['valide_par']) {
                if (in_array($record['libelle'], array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Profil', 'Section', 'Indemnité repas'))) {
                    if($record['statut'] != 'R') {
                        $dcValideInput = 'onfocus="showCalendarControl(this)"';
                        $dcValide = $record['valide_par'] . ' le <input id="date_validation' . $record['id_demande_changement'] . '" ' . $dcValideInput . ' type="text" value="' . FormatageDate($record['date_validation']) . '" size="8"><input type="checkbox" id="valide' . $record['id_demande_changement'] . '" ' . $dcValideCheckbox . ' onclick="DCValide(' . $record['id_demande_changement'] . ', 1)" />';
                    } else {
                        $dcValide = 'Refusé';
                    }
                } else {
                    $dcValide = 'Validation non nécessaire';
                }
            } else {
                $dcValide = $record['valide_par'] . ' le ' . FormatageDate($record['date_validation']);
            }
        } else {
            if (!$record['valide_par']) {
                if (in_array($record['libelle'], array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Profil', 'Section', 'Indemnité repas'))) {
                    if($record['statut'] != 'R') {
                        $dcValideCheckbox = 'disabled="disabled"';
                        $dcValideInput = 'readonly="readonly" ';
                        $dcValide = $record['valide_par'] . ' le <input id="date_validation' . $record['id_demande_changement'] . '" ' . $dcValideInput . ' type="text" value="' . FormatageDate($record['date_validation']) . '" size="8"><input type="checkbox" id="valide' . $record['id_demande_changement'] . '" ' . $dcValideCheckbox . ' onclick="DCValide(' . $record['id_demande_changement'] . ', 1)" />';
                    } else {
                        $dcValide = 'Refusé';
                    }
                } else {
                    $dcValide = 'Validation non nécessaire';
                }
            } else {
                $dcValide = $record['valide_par'] . ' le ' . FormatageDate($record['date_validation']);
            }
        }
        $htmlValidation = '<div id="validate' . $record['id_demande_changement'] . '" style="background-color:' . $colorValidate . '">' . $dcValide . '</div>';
        return $htmlValidation;
    }
    
    public function showIntegrateBy($record, $args) {
        $dcintegre = '';
        if ($record['valide_par']) {
            $dcvalide = 'checked="checked"';
        }
        if ($record['integre_par']) {
            $dcintegre = 'checked="checked"';
        }
        
        if($record['statut'] == 'V') {
            $colorIntegrate = '#A4A4F9';
        }
        else if($record['statut'] == 'I') {
            $colorIntegrate = '#A4A4F9';
        }
        else if($record['statut'] == 'R') {
            $colorIntegrate = '#EDC3C3';
            $dcIntegreCheckbox = 'disabled="disabled"';
            $dcIntegreInput = 'readonly="readonly" ';
        }
        else if($record['statut'] == 'C') {
            $colorIntegrate = '#BAE0BA';
        }
        
        $dcIntegreCheckbox = $dcIntegreInput = $htmlIntegration = '';
        if (Utilisateur::getDroitIntegrationDemandeChangement($record['id_demande_changement'])) {
            if (!$record['integre_par']) {
                if (in_array($record['libelle'], array('Libellé emploi', 'Statut', 'Type embauche', 'Etat', 'Agence', 'CDS', 'Profil', 'Indemnité repas')) && !$record['valide_par']) {
                    $dcIntegreCheckbox = 'checked="checked" disabled="disabled"';
                    $dcIntegreInput = 'readonly="readonly" ';
                    $dcIntegre = $record['integre_par'] . ' le <input id="date_integration' . $record['id_demande_changement'] . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($record['date_integration']) . '" size="8"><input type="checkbox" id="integre' . $record['id_demande_changement'] . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $record['id_demande_changement'] . ', 1)" />';

                    $dcIntegre = 'Attente validation';
                } else {
                    $dcIntegreInput = 'onfocus="showCalendarControl(this)"';
                    $dcIntegre = $record['integre_par'] . ' le <input id="date_integration' . $record['id_demande_changement'] . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($record['date_integration']) . '" size="8"><input type="checkbox" id="integre' . $record['id_demande_changement'] . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $record['id_demande_changement'] . ', 1)" />';
                }
            } else {
                $dcIntegre = $record['integre_par'] . ' le ' . FormatageDate($record['date_integration']);
            }
        } else {
            if (!$record['integre_par']) {
                $dcIntegreCheckbox = 'disabled="disabled"';
                $dcIntegreInput = 'readonly="readonly" ';
                $dcIntegre = $record['integre_par'] . ' le <input id="date_integration' . $record['id_demande_changement'] . '" ' . $dcIntegreInput . ' type="text" value="' . FormatageDate($record['date_integration']) . '" size="8"><input type="checkbox" id="integre' . $record['id_demande_changement'] . '" ' . $dcIntegreCheckbox . ' onclick="DCIntegre(' . $record['id_demande_changement'] . ', 1)" />';
            } else {
                $dcIntegre = $record['integre_par'] . ' le ' . FormatageDate($record['date_integration']);
            }
        }
        $htmlIntegration = '<div id="integrate' . $record['id_demande_changement'] . '" style="background-color:' . $colorIntegrate . '">' . $dcIntegre . '</div>';
        return $htmlIntegration;
    }

    public function showButtons($record) {
        $html = '<a href="../membre/index.php?a=modifier&amp;Id=' . $record['id_demande_changement'] . '&amp;class=' . __CLASS__ . '"><img src="' . IMG_EDIT . '"></a>';
        if (array_intersect($_SESSION[SESSION_PREFIX.'logged']->groupe_ad, array(1))) {
            $html .= '<td><input type="button" class="boutonSupprimer" onclick="if (confirm(\'' . CONFIRM_DELETE . '\')) { location.replace(\'index.php?a=supprimer&amp;Id=' . $record['id_demande_changement'] . '&amp;class=' . __CLASS__ . '\') }" /></td>';
        }
        return $html;
    }
}

?>
