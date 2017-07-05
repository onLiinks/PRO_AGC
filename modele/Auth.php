<?php

/**
 * Fichier Auth.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Auth
 */
class Auth {

    /**
     * @access public
     * @var bool
     */
    public $auth = false;
    
    /**
     * @access public
     * @var bool
     */
    public $auth_type = false;

    /**
     * Identifiant de l'utilisateur
     *
     * @access public
     * @var int 
     */
    public $Id_utilisateur;

    /**
     * Prénom de la personne
     *
     * @access public
     * @var string
     */
    public $prenom;

    /**
     * Nom de la personne
     *
     * @access public
     * @var string
     */
    public $nom;

    /**
     * Groupe de l'active directory de la personne
     *
     * @access public
     * @var array
     */
    public $groupe_ad;

    /**
     * Zone d'accès par défault de l'utilisateur
     *
     * @access public
     * @var array
     */
    public $zone_default;

    /**
     * Constructeur de la classe Auth
     *
     * Constructeur initialisant les données membres
     *
     */
    public function __construct() {
        $this->Id_utilisateur = '';
        $this->prenom = '';
        $this->nom = '';
        $this->groupe_ad = array();
        $this->zone_default = 'public';
        $this->auth = false;
    }

    /**
     * Vérification des droits d'accès d'un utilisateur à une section du site
     *
     * @param string Valeur de la zone
     *
     */
    public function checkEntry($zone) {
        if ($this->auth_type == 'sso') {
            $as = new SimpleSAML_Auth_Simple(SSO_PROVIDER_NAME);
            if (!$as->isAuthenticated()) {
                $as->login();
            }
            $_SESSION[SESSION_PREFIX.'logged']->initializeUser();
        } else if ($this->auth_type == 'ad') {
            if (!$this->auth) {
                $this->checkUser($_POST['login'], $_POST['pass'], $this->auth_type);
            }
        }
        $this->checkAcces($zone);
    }

    /**
     * Redirection de l'utilisateur en fonction de son groupe
     *
     */
    public function zoneRedirect() {
        header('location: ../' . $this->zone_default . '/index.php?url=' . urlencode($_SERVER['REQUEST_URI']));
    }

    /**
     * Vérification des login et mot de passe d'un utilisateur
     *
     * @param string Valeur du login
     * @param string Valeur du password
     *
     * @return bool
     */
    public function checkUser($user, $pwd, $method) {
        if ($method === 'sso') {
            $this->erreur = '';
            $as = new SimpleSAML_Auth_Simple(SSO_PROVIDER_NAME);
            $as->requireAuth();
            $attributes = $as->getAttributes();

            if ($ad = ldap_connect(LDAP_SRV)) {
                ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
                $r = ldap_bind($ad, 'user_osc@proservia.lan', 'Proservi@DSI');

                $sr = ldap_search($ad, 'OU=PROSERVIA,' . BASE_DN, "(samaccountname=" . strtolower(htmlscperso(stripslashes($attributes['http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname'][0]), ENT_QUOTES)) . ")", array('sAMAccountName', 'memberof'));
                $entries = ldap_get_entries($ad, $sr);
                if ((array_key_exists(0, $entries) && array_key_exists('memberof', $entries[0]))) {
                    for ($i = 0; $i < $entries[0]['memberof']["count"]; $i++) {
                        $group = explode(',', $entries[0]["memberof"][$i]);
                        $Id_group_ad = GestionDroit::getIdGroupeAd(substr($group[0], 3, 100));
                        if ($Id_group_ad) {
                            $this->groupe_ad[] = $Id_group_ad;
                        }
                    }
                    
                    $this->zone_default = GestionDroit::getAccueilDefault($this->groupe_ad[0]);
                }
                else {
                    $this->erreur = RIGHTS_ERROR;
                    return false;
                }
            }
            ldap_close($ad);
            
            $this->prenom = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0];
            $this->nom = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0];
            $this->mail = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0];
            $this->Id_utilisateur = strtolower(htmlscperso(stripslashes($attributes['http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname'][0]), ENT_QUOTES));

            $this->auth = true;
            $this->auth_type = $method;
            $db = connecter_cegid();
            $this->Id_agence = $db->query('SELECT PSA_ETABLISSEMENT FROM SALARIES
                             INNER JOIN UTILISAT ON PSA_SALARIE=UTILISAT.US_AUXILIAIRE
                             WHERE US_ABREGE="' . strtoupper($this->Id_utilisateur) . '"')->fetchOne();

            $db = connecter();
            $result = $db->query('SELECT Id_utilisateur FROM utilisateur WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '" and archive=0');

            if (!$result->numRows()) {
                $db->query('INSERT INTO utilisateur SET Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '",
                                    Id_agence="' . mysql_real_escape_string($this->Id_agence) . '",nom="' . mysql_real_escape_string($this->nom) . '",prenom="' . mysql_real_escape_string($this->prenom) . '",
                                    mail="' . mysql_real_escape_string($this->mail) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '"');
            } else {
                $db->query('UPDATE utilisateur SET last_visit="' . mysql_real_escape_string(DATETIME) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '", mail="' . mysql_real_escape_string($this->mail) . '",
                            Id_agence="' . mysql_real_escape_string($this->Id_agence) . '"
                            WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '"');
            }
            $this->utilisateur = new Utilisateur($this->Id_utilisateur, array());
            setcookie('agcid', crypter('', $_SESSION['societe'] . '/' . $this->Id_utilisateur), time() + 604800, '/', $_SERVER['HTTP_HOST'], false);
            $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_SESSION['societe']);
            return true;
        } else if ($method === 'ad') {
            $ds = ldap_connect(LDAP_SRV);
            if ($ds) {
                ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
                $userad = $user . '@proservia.lan';
                $pwd = utf8_encode(stripslashes($pwd));
                $r = @ldap_bind($ds, $userad, $pwd);
                $this->erreur = '';

                // Correction pour bug php sur pass vide
                if ($r == false || $pwd == '') {
                    $this->erreur = LOGIN_ERROR;
                    return false;
                } else {
                    $dn = BASE_DN;
                    $filter = 'sAMAccountName=' . $user;
                    $restriction = array('dn', 'sAMAccountName', 'cn', 'sn', 'givenName', 'mail', 'memberof');
                    $sr = @ldap_search($ds, $dn, $filter, $restriction);
                    $info = @ldap_get_entries($ds, $sr);

                    $this->groupe_ad = array();
                    for ($i = 0; $i < $info['count']; $i++) {
                        $nbgroup = count($info[0]["memberof"]);
                        $j = 0;
                        while ($j < $nbgroup) {
                            $group = explode(',', $info[0]["memberof"][$j]);
                            $Id_group_ad = GestionDroit::getIdGroupeAd(substr($group[0], 3, 100));
                            if ($Id_group_ad) {
                                $this->groupe_ad[] = $Id_group_ad;
                            }
                            ++$j;
                        }
                        
                        if (count($this->groupe_ad) == 0) {
                            $this->erreur = RIGHTS_ERROR;
                            return false;
                        }
                        
                        $this->prenom = $info[$i]['givenname'][0];
                        $this->nom = $info[$i]['sn'][0];
                        $this->mail = $info[$i]['mail'][0];
                        $this->Id_utilisateur = strtolower(htmlscperso(stripslashes($user), ENT_QUOTES));
                        $this->zone_default = GestionDroit::getAccueilDefault($this->groupe_ad[0]);
                    }
                    
                    $this->auth = true;
                    $this->auth_type = $method;
                    $db = connecter_cegid();
                    $this->Id_agence = $db->query('SELECT PSA_ETABLISSEMENT FROM SALARIES
                                     INNER JOIN UTILISAT ON PSA_SALARIE=UTILISAT.US_AUXILIAIRE
                                     WHERE US_ABREGE="' . strtoupper($this->Id_utilisateur) . '"')->fetchOne();

                    $db = connecter();
                    $result = $db->query('SELECT Id_utilisateur FROM utilisateur WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '" and archive=0');

                    if (!$result->numRows()) {
                        $db->query('INSERT INTO utilisateur SET Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '",
                                            Id_agence="' . mysql_real_escape_string($this->Id_agence) . '",nom="' . mysql_real_escape_string($this->nom) . '",prenom="' . mysql_real_escape_string($this->prenom) . '",
                                            mail="' . mysql_real_escape_string($this->mail) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '"');
                        
                        $a = array_intersect($this->groupe_ad, Utilisateur::getGroupAdList('RH'));
                        if($a[0]) {
                            $idMail = $db->query('SELECT Id_mail FROM mail_proservia WHERE Id_agence = "' . mysql_real_escape_string($this->Id_agence) . '"')->fetchOne();
                            $db->query('INSERT INTO createur_mail_proservia SET createur ="' . mysql_real_escape_string($this->Id_utilisateur) . '",
                                        Id_mail="' .  mysql_real_escape_string($idMail) . '"');
                        }
                    } else {
                        $db->query('UPDATE utilisateur SET last_visit="' . mysql_real_escape_string(DATETIME) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '", mail="' . mysql_real_escape_string($this->mail) . '",
                                    Id_agence="' . mysql_real_escape_string($this->Id_agence) . '"
                                    WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '"');
                    }
                    $this->utilisateur = new Utilisateur($this->Id_utilisateur, array());
                    setcookie('agcid', crypter('', $_SESSION['societe'] . '/' . $this->Id_utilisateur), time() + 604800, '/', $_SERVER['HTTP_HOST'], false);
                    $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_SESSION['societe']);
                    return true;
                }
                ldap_close($ds);
            }
        } else {
            echo SRV_ERROR;
            return false;
        }
    }

    public function impersonate($user) {
        $this->impersonate['id'] = $this->Id_utilisateur;
        if($user == $this->Id_utilisateur)
            $this->impersonate['state'] = false;
        else
            $this->impersonate['state'] = true;
        $this->Id_utilisateur = $user;

        if ($ad = ldap_connect(LDAP_SRV)) {
            ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
            $r = ldap_bind($ad, 'user_osc@proservia.lan', 'Proservi@DSI');
            $sr = ldap_search($ad, BASE_DN, "(samaccountname=" . $user . ")", array('sAMAccountName', 'cn', 'sn', 'givenName', 'mail', 'memberof'));
            $entries = ldap_get_entries($ad, $sr);
            $this->prenom = $entries[0]['givenname'][0];
            $this->nom = $entries[0]['sn'][0];
            $this->mail = $entries[0]['mail'][0];
            if ((array_key_exists(0, $entries) && array_key_exists('memberof', $entries[0]))) {
                for ($i = 0; $i < $entries[0]['memberof']["count"]; $i++) {
                    $group = explode(',', $entries[0]["memberof"][$i]);
                    $Id_group_ad = GestionDroit::getIdGroupeAd(substr($group[0], 3, 100));
                    if ($Id_group_ad) {
                        $this->groupe_ad[] = $Id_group_ad;
                    }
                    $this->zone_default = GestionDroit::getAccueilDefault($this->groupe_ad[0]);
                }
            }
        }
        ldap_close($ad);

        $this->auth = true;
        $db = connecter_cegid();
        $this->Id_agence = $db->query('SELECT PSA_ETABLISSEMENT FROM SALARIES
                             INNER JOIN UTILISAT ON PSA_SALARIE=UTILISAT.US_AUXILIAIRE
                             WHERE US_ABREGE="' . strtoupper($this->Id_utilisateur) . '"')->fetchOne();

        $db = connecter();
        $result = $db->query('SELECT Id_utilisateur FROM utilisateur WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '" and archive=0');

        if (!$result->numRows()) {
            $db->query('INSERT INTO utilisateur SET Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '",
                                    Id_agence="' . mysql_real_escape_string($this->Id_agence) . '",nom="' . mysql_real_escape_string($this->nom) . '",prenom="' . mysql_real_escape_string($this->prenom) . '",
                                    mail="' . mysql_real_escape_string($this->mail) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '"');
        } else {
            $db->query('UPDATE utilisateur SET last_visit="' . mysql_real_escape_string(DATETIME) . '", groupe_ad="' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . '",
                            Id_agence="' . mysql_real_escape_string($this->Id_agence) . '"
                            WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '"');
        }
        $this->utilisateur = new Utilisateur($this->Id_utilisateur, array());
        setcookie('agcid', crypter('', $_SESSION['societe'] . '/' . $this->Id_utilisateur), time() + 604800, '/', $_SERVER['HTTP_HOST'], false);
        return true;
    }

    /**
     * Vérification des login et mot de passe d'un utilisateur
     *
     * @param string Valeur du login
     *
     * @return bool
     */
    public function initializeUser() {
        if (AUTH_TYPE == 'SSO') {
            $as = new SimpleSAML_Auth_Simple(SSO_PROVIDER_NAME);
            $attributes = $as->getAttributes();
            if (empty($attributes))
                return false;
            $utilisateur = $attributes['http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname'][0];
            $societe = Bdd::getAgcDatabaseByLibelle($attributes['http://schemas.xmlsoap.org/claims/Group'][0]);
        } else if (AUTH_TYPE == 'AD') {
            list($societe, $utilisateur) = explode('/', htmlscperso(stripslashes(decrypter('', $_COOKIE['agcid'])), ENT_QUOTES));
            if (!$utilisateur || !$societe)
                return false;
        }
        
        $_SESSION['societe'] = $societe;
        $_SESSION['cegid_databases'] = Bdd::getCegidDatabases($_SESSION['societe']);

        $this->Id_utilisateur = strtolower($utilisateur);
        $this->utilisateur = new Utilisateur($this->Id_utilisateur, array());
        $this->prenom = $this->utilisateur->prenom;
        $this->nom = $this->utilisateur->nom;
        $this->mail = $this->utilisateur->mail;

        $db = connecter();
        $result = $db->query('SELECT Id_utilisateur,groupe_ad FROM utilisateur WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '" and archive=0');
        $this->groupe_ad = array();
        while ($ligne = $result->fetchRow()) {
            $this->groupe_ad = explode(',', $ligne->groupe_ad);
        }
        $this->zone_default = GestionDroit::getAccueilDefault($this->groupe_ad[0]);
        $this->auth = true;
        $db->query('UPDATE utilisateur SET last_visit="' . DATETIME . '" WHERE Id_utilisateur ="' . mysql_real_escape_string($this->Id_utilisateur) . '"');
        return true;
    }

    /**
     * Vérification des droits d'accès d'un utilisateur à une section du site
     *
     * @param string Valeur de la zone
     *
     */
    private function checkAcces($zone) {
        if (!empty($this->groupe_ad)) {
            $db = connecter();
            $ligne = $db->query('SELECT Id_groupe_ad FROM groupe_ad_zone_acces WHERE Id_zone_acces=' . mysql_real_escape_string($zone) . '
                    AND Id_groupe_ad IN (' . mysql_real_escape_string(implode(',', $this->groupe_ad)) . ')')->fetchRow();
            if ($ligne->id_groupe_ad == '') {
                throw new AGCException(ACCESS_ERROR);
            }
        } else {
            throw new AGCException(ACCESS_ERROR);
        }
    }

    /**
     * Formulaire d'authentification
     *
     * @return string
     *
     */
    public function logBox() {
        $bdd[$_SESSION['societe']] = 'selected="selected"';
        $url = str_replace("/AGC/", "", htmlscperso($_GET['url'], ENT_QUOTES));
        $error = ($_GET['error'] == 1) ? '<br />Session expirée<br />' : '<br />' . $this->erreur.'<br />';
        $tokenId = $this->createTokenId();
        $tokenValue = $this->createToken();
        
        $html = '
            <div id="authent">
                <div class="roundedcornr_box_736660">
                    <div class="roundedcornr_top_736660"><div></div></div>
                    <div class="roundedcornr_content_736660">
                        <marquee LOOP="infinite" behavior="scroll" direction="left" scrollamount=1 scrolldelay=35 style="text-align: left ; padding: 10px;" 
                            truespeed onmouseover=this.stop() onmouseout=this.start()><h2>' . WELCOME_INFO . '</h2></marquee><br /><br />
                        <form action="../public/index.php?a=verifUser" method="post"><br />
                            <span>Connecter vous avec votre compte Office 365</span>
                            <br><br>
                            <p>
                                <a class="btn btn-primary social-login-btn sso" href="../public/index.php?a=verifUser&method=sso" class="tooltips" data-ot-tip-joint="left" data-ot="' . HELP_SSO . '"><img src="../ui/images/sso.png" /></a>
                            </p>
                            <br /><br />
                            <span>Ou connecter vous avec votre compte AD</span>
                            <br /><br />
                            <table>
                                <tr>
                                    <td>Société :  <br /><br /></td>
                                    <td>
                                        <select id="Id_societe" onchange="selectBdd()">
                                            <option value="">Société</option>
                                            <option value="">----------------</option>
                                            ' . Auth::getCompanyList($bdd) . '
                                        </select>
                                        <br /><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nom d\'utilisateur : <br /><br /></td>
                                    <td><input type="text" id="login" name="login" size="17" /><br /><br /></td>
                                </tr>
                                <tr>
                                    <td>Mot de passe :</td>
                                    <td><input type="password" id="pass" name="pass" size="17" /></td>
                                </tr>
                            </table>
                            <input type="hidden" name="' . $tokenId . '" value="' . $tokenValue . '" />
                            <b>' . $error . '</b>
                            <br /><br />
                            <input type="hidden" name="url" value="' . $url . '" />
                            <input type="hidden" id="method" name="method" value="ad" />
                            <button class="button save" style="width: 10em;"  type="submit" value="' . LOG_IN . '" onclick="return verifConnexion(this.form)">' . LOG_IN . '</button>
                            <br /><br /><br />
                        </form>
                    </div>
                    <div class="roundedcornr_bottom_736660"><div></div></div>
                </div>
            </div>
';
        return $html;
    }

    /**
     * Affiche le message de bienvenue
     *
     * @return string
     *
     */
    public function getWelcomeMsg() {
        if($this->impersonate['state'] === true)
            $logOff = '<input type="button" onclick="location.replace(\'../gestion/index.php?a=changerUtilisateur&user=' . $this->impersonate['id'] . '\')" value="' . LOG_OFF . '" />';
        else
            $logOff = '<input type="button" onclick="location.replace(\'../membre/index.php?a=quitter\')" value="' . LOG_OFF . '" />';
        $html = HELLO_INFO . ', ' . $this->prenom . ' ' . $this->nom . '&nbsp;&nbsp;' . $logOff;
        return $html;
    }

    /**
     * Deconnexion d'un utilisateur
     *
     * Détruit la session PHP en cours
     */
    public function quit() {
        if (AUTH_TYPE == 'SSO') {
            $as = new SimpleSAML_Auth_Simple(SSO_PROVIDER_NAME);
            $as->logout(
                array(
                    'ReturnTo' => BASE_URL . 'membre/?a=clear',
                )
            );
        } else if (AUTH_TYPE == 'AD') {
            $this->clearSession();
        }
    }

    public function clearSession() {
        $_SESSION[SESSION_PREFIX.'logged']->auth = false;
        setcookie('agcid', crypter('', $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur), time() - 604800, '/', $_SERVER['HTTP_HOST'], false);
        $sfClient = new SFClient();
        $sfClient->logout();
        session_destroy();
        header('location: ' . BASE_URL . 'public/');
    }
    
    /**
     * Liste des sociétés utilisées
     *
     */
    public static function getCompanyList($bdd) {
        $db = connecter('PROSERVIA');
        $result = $db->query('SELECT Id_societe, libelle FROM societe WHERE date_entree < NOW() AND (date_affichage > NOW() OR date_affichage = "0000-00-00")');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->id_societe . '" ' . $bdd[$ligne->libelle] . '>' . $ligne->libelle . '</option>';
        }
        return $html;
    }
    
    public function createTokenId($suffix = false) {
        $token_id = uniqid(rand());
        if($suffix !== false) {
            $_SESSION['token'][$suffix]['token_id'] = $token_id;
        }
        else {
            $_SESSION['token']['_']['token_id'] = $token_id;
        }
        return $token_id;
    }
    
    
    public function createToken($suffix = false) {
        $token = hash('sha256', uniqid(rand(), true));
        if($suffix !== false) {
            $_SESSION['token'][$suffix]['token_value'] = $token;
            $_SESSION['token'][$suffix]['token_time'] = time();
        }
        else {
            $_SESSION['token']['_']['token_value'] = $token;
            $_SESSION['token']['_']['token_time'] = time();
        }
        return $token;
    }
    
    public function checkToken($method = 'post', $time = 36000, $referer = false) {
        $post = $_POST;
        $get = $_GET;
        
        foreach($_SESSION['token'] as $key => $value) {
            // Vé²©fication si le token é ©té °assé ?ans la requé?¥
            if(isset(${$method}[$value['token_id']])) {
                // Vé²©fication si le token_id et le token_value existe en session
                if(isset($value['token_id']) && isset($value['token_value'])) {
                    // Vé²©fication que le token passé £orrespond au token en session
                    if($value['token_value'] === ${$method}[$value['token_id']]) {
                        // Vé²©fie que moins de 15mn se sont é£¯ulé ¥ntre la gé®©ration du token 
                        // en session et l'envoi dans la requé?¥
                        if($time === false || ($time !== false && $value['token_time'] >= (time() - $time))) {
                            if($referer !== false) {
                                foreach ($referer as $regex) {
                                    if(preg_match($regex, $_SERVER['HTTP_REFERER'])) {
                                        return true;
                                    } 
                                }
                                unset($_SESSION['token']);
                                echo 'Token not accepted : wrong referer';
                                return false;
                            }
                            else {
                                return true;
                            }
                        }
                        echo 'Token not accepted : token expired';
                        return false;
                    }
                    echo 'Token not accepted : token in request differ token in session';
                    return false;
                }
                echo 'Token not accepted : no token in session';
                return false;
            }
            echo 'Token not accepted : no token in request';
            return false;
        }
        
        echo 'Token not accepted : unknown reason';
        unset($_SESSION['token']);
        return false;
    }
}

?>
