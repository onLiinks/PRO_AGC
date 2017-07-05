<?php

/**
 * Fichier fonctions.php
 *
 * @author Anthony Anne
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Connection à la base de données
 *
 * @return string
 */
function connecter() {
    if (!isset($_SESSION['societe'])) {
        $_SESSION['societe'] = 'PROSERVIA';
    }
    $dsn = array('phptype' => 'mysql', 'username' => USER, 'password' => PASSWD, 'hostspec' => SERVEUR,
        'database' => DB_PREFIX . $_SESSION['societe']);
    $options = array('portability' => MDB2_PORTABILITY_ALL);
    $db = MDB2::connect($dsn, $options);
    if (PEAR::isError($db)) {
        $cause = "Standard Message  : " . $db->getMessage() . "<br>" . 
                 "User Information  : " . $db->getUserInfo() . "<br>" .
                 "Debug Information : " . $db->getDebugInfo() . "<br>";
        if (DEBUG >= 1) {
            die($cause);
        }
        else {
            die(MAINTENANCE);
        }
    }
    $db->setOption('field_case', CASE_LOWER);
    $db->setOption('portability', MDB2_PORTABILITY_FIX_CASE);
    $db->setFetchMode(MDB2_FETCHMODE_OBJECT);
    return $db;
}

/**
 * Connection à la base de données par default PROSERVIA
 *
 * @return string
 */
function connecter_default() {
    $dsn = array('phptype' => 'mysql', 'username' => USER, 'password' => PASSWD, 'hostspec' => SERVEUR,
        'database' => DB_PREFIX . BASE);
    $options = array('portability' => MDB2_PORTABILITY_ALL);
    $db = MDB2::connect($dsn, $options);
    if (PEAR::isError($db)) {
        $cause = "Standard Message  : " . $db->getMessage() . "<br>" . 
                 "User Information  : " . $db->getUserInfo() . "<br>" .
                 "Debug Information : " . $db->getDebugInfo() . "<br>";
        if (DEBUG >= 1) {
            die($cause);
        }
        else {
            die(MAINTENANCE);
        }
    }
    $db->setOption('field_case', CASE_LOWER);
    $db->setOption('portability', MDB2_PORTABILITY_FIX_CASE);
    $db->setFetchMode(MDB2_FETCHMODE_OBJECT);
    return $db;
}

/**
 * Connection à la base de données CEGID
 *
 * @return string
 */
function connecter_cegid() {
    if (!isset($_SESSION['societe'])) {
        $_SESSION['societe'] = 'PROSERVIA';
    }
    $dsn = array('phptype' => 'mssql', 'username' => CEGID_USER, 'password' => CEGID_PASSWD,
        'hostspec' => CEGID_SERVEUR, 'database' => $_SESSION['societe']);
    $options = array('portability' => MDB2_PORTABILITY_ALL);
    $db = MDB2::connect($dsn, $options);
    if (PEAR::isError($db)) {
        $cause = "Standard Message  : " . $db->getMessage() . "<br>" . 
                 "User Information  : " . $db->getUserInfo() . "<br>" .
                 "Debug Information : " . $db->getDebugInfo() . "<br>";
        if (DEBUG >= 1) {
            die($cause);
        }
        else {
            die(MAINTENANCE);
        }
    }
    $db->setOption('field_case', CASE_LOWER);
    $db->setOption('portability', MDB2_PORTABILITY_FIX_CASE);
    $db->setFetchMode(MDB2_FETCHMODE_OBJECT);
    @ini_set('mssql.datetimeconvert', '1');
    return $db;
}

/**
 * Vérifier qu'une date est au bon format jj//mm/aaaa
 *
 * @param string Date saisie
 *
 * @return bool
 */
function checkFormat($date) {
    return preg_match('`^(((0[1-9])|(1\d)|(2\d)|(3[0-1]))\/((0[1-9])|(1[0-2]))\/(\d{4}))$`', $date);
}

/**
 * Vérifier qu'une date est au bon format aaaa--mm--jj
 *
 * @param string Date saisie
 *
 * @return bool
 */
function checkFormatMysql($date) {
    return preg_match('`^(([0-9]{4})(-)([0-1]{1,}[0-9]{1,})(-)([0-3]{1,}[0-9]{1,}))$`', $date);
}

/**
 * Permet de convertir une date selon les 2 formats : sql ou francais
 *
 * @param string Date saisie
 * @param string Choix du format d'affichage
 *
 * @return bool
 */
function DateMysqltoFr($DateMysql, $conv = 'fr', $time = false) {
    switch ($conv) {
        case 'fr':
            if (!checkFormat($DateMysql)) {
                if ($time) {
                    list($date, $heure) = explode(' ', $DateMysql);
                    list($annee, $mois, $jour) = explode('-', $date);
                    return ($jour . '-' . $mois . '-' . $annee . ' ' . $heure);
                } else {
                    list($date, $heure) = explode(' ', $DateMysql);
                    list($annee, $mois, $jour) = explode('-', $date);
                    return ($jour . '-' . $mois . '-' . $annee);
                }
            }
            break;

        case 'mysql':
            if (!checkFormatMysql($DateMysql)) {
                if (strpos($DateMysql, '-') === false) {
                    if ($time) {
                        list($date, $heure) = explode(' ', $DateMysql);
                        list($jour, $mois, $annee) = explode('/', $date);
                        return ($annee . '-' . $mois . '-' . $jour . ' ' . $heure);
                    } else {
                        list($jour, $mois, $annee) = explode('/', $DateMysql);
                        return ($annee . '-' . $mois . '-' . $jour);
                    }
                } else {
                    if ($time) {
                        list($date, $heure) = explode(' ', $DateMysql);
                        list($jour, $mois, $annee) = explode('-', $date);
                        return ($annee . '-' . $mois . '-' . $jour . ' ' . $heure);
                    } else {
                        list($jour, $mois, $annee) = explode('-', $DateMysql);
                        return ($annee . '-' . $mois . '-' . $jour);
                    }
                }
            }
            else
                return ($DateMysql);
            break;
    }
}

/**
 * Donne le bon format de date lors de la saisie
 *
 * @param string Date saisie
 *
 * @return bool
 */
function FormatageDate($DateMysql) {
    //var_dump($DateMysql);
    
    if (!checkFormat($DateMysql)) {
        if (checkFormatMysql($DateMysql)) {
            $tab = explode('-', $DateMysql);
            return $tab[2].'-'.$tab[1].'-'.$tab[0];
        }
        return '';
    }
    return $DateMysql;
}

/**
 * Donne le bon format au numéro de téléphone
 *
 * @param string telephone
 *
 * @return string
 */
function formatTel($tel) {
    $tel = str_replace(' ', '', $tel);
    $tel = str_replace('.', '', $tel);
    $tel = str_replace('-', '', $tel);
    $tel = str_replace('/', '', $tel);
    return $tel;
}

/**
 * Supprimer les accents d'un nom
 *
 * @param string nom
 *
 * @return string
 */
function withoutAccent($name) {
    return strtr($name, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
}

/**
 * Supprimer les accents et les caractères spéciaux d'une chaine de caractères
 *
 * @param string nom
 *
 * @return string
 */
function formatageString($chaine) {
    $chaine = strtr($chaine, '\\/:*?"<>|+& ', '____________');
    return withoutAccent($chaine);
}

function formatPrenom($str) {
    return ucfirst(strtolower(trim($str)));
}

/**
 * Mis en forme d'un login ou d'une adresse mail pour récupérer le nom et prénom
 *
 * @param string Chaine a formatter
 * @param bool S'agit il d'un login ou d'une adresse mail
 * @param bool Faut il mettre le nom en majuscule
 *
 * @return string
 */
function formatPrenomNom($str, $mailAdress = false, $uppercase = false) {
    if ($mailAdress) {
        list($login, $domain) = split('[@]', $str);
        list($prenom, $nom) = split('[.]', $login);
        if($uppercase)
            $r = ucfirst(strtolower(trim($prenom))) . " " . strtoupper (trim($nom));
        else 
            $r = ucfirst(strtolower(trim($prenom))) . " " . ucfirst(strtolower(trim($nom)));
    } else {
        list($prenom, $nom) = split('[.]', $str);
        $r = ucfirst(strtolower(trim($prenom))) . " " . ucfirst(strtolower(trim($nom)));
    }
    return $r;
}

/*
  function ouvres($date_start, $date_stop) {
  $date_start = strtotime(DateMysqltoFr($date_start,'mysql'));
  $date_stop  = strtotime(DateMysqltoFr($date_stop,'mysql'));
  $arr_bank_holidays = array(); // Tableau des jours feriés
  // On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
  $diff_year = date('Y', $date_stop) - date('Y', $date_start);
  $i = 0;
  while ($i <= $diff_year) {
  $year = (int)date('Y', $date_start) + $i;
  // Liste des jours feriés
  $arr_bank_holidays[] = '1_1_'.$year; // Jour de l'an
  $arr_bank_holidays[] = '1_5_'.$year; // Fete du travail
  $arr_bank_holidays[] = '8_5_'.$year; // Victoire 1945
  $arr_bank_holidays[] = '14_7_'.$year; // Fete nationale
  $arr_bank_holidays[] = '15_8_'.$year; // Assomption
  $arr_bank_holidays[] = '1_11_'.$year; // Toussaint
  $arr_bank_holidays[] = '11_11_'.$year; // Armistice 1918
  $arr_bank_holidays[] = '25_12_'.$year; // Noel

  // Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
  $easter = easter_date($year);
  $arr_bank_holidays[] = date('j_n_'.$year, $easter + 86400); // Paques
  $arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*39)); // Ascension
  $arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*50)); // Pentecote
  ++$i;
  }
  $nb_days_open = 0;
  while ($date_start <= $date_stop) {
  // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés
  if (!in_array(date('w', $date_start), array(0, 6)) && !in_array(date('j_n_'.date('Y', $date_start), $date_start), $arr_bank_holidays)) {
  $nb_days_open++;
  }
  $date_start += 86400;
  }
  return $nb_days_open;
  } */

/**
 * Fonction qui met à jour la table jour
 *
 */
function workingDays($debut, $fin) {
    return connecter()->query('SELECT count(jour) as nb FROM jours WHERE jour BETWEEN "' . mysql_real_escape_string($debut) . '" AND "' . mysql_real_escape_string($fin) . '"')->fetchRow()->nb;
}

function debutsem($year, $month, $day) {
    $num_day = date('w', mktime(0, 0, 0, $month, $day, $year));
    $premier_jour = mktime(0, 0, 0, $month, $day - (!$num_day ? 7 : $num_day) + 1, $year);
    $datedeb = date('d-m-Y', $premier_jour);
    return $datedeb;
}

function finsem($year, $month, $day) {
    $num_day = date('w', mktime(0, 0, 0, $month, $day, $year));
    $dernier_jour = mktime(0, 0, 0, $month, 7 - (!$num_day ? 7 : $num_day) + $day, $year);
    $datedeb = date('d-m-Y', $dernier_jour);
    return $datedeb;
}

function weekDates($week, $year) {
    $week_dates = array();
    // Get timestamp of first week of the year
    $first_day = mktime(12, 0, 0, 1, 1, $year);
    /* if (date("W",$first_day) > 1) {
      $first_day = strtotime("+1 week",$first_day); // skip to next if year does not begin with week 1
      } */
    // Get timestamp of the week
    $timestamp = strtotime("+$week week", $first_day);
    // Adjust to Monday of that week
    $what_day = date("w", $timestamp); // I wanted to do "N" but only version 4.3.9 is installed :-(
    if ($what_day == 0) {
        // actually Sunday, last day of the week. FIX;
        $timestamp = strtotime("-6 days", $timestamp);
    } elseif ($what_day > 1) {
        --$what_day;
        $timestamp = strtotime("-$what_day days", $timestamp);
    }
    $week_dates[1] = date("Y-m-d", $timestamp); // Monday
    //$week_dates[2] = date("Y-m-d",strtotime("+1 day",$timestamp)); // Tuesday
    //$week_dates[3] = date("Y-m-d",strtotime("+2 day",$timestamp)); // Wednesday
    //$week_dates[4] = date("Y-m-d",strtotime("+3 day",$timestamp)); // Thursday
    //$week_dates[5] = date("Y-m-d",strtotime("+4 day",$timestamp)); // Friday
    //$week_dates[6] = date("Y-m-d",strtotime("+5 day",$timestamp)); // Saturday
    $week_dates[7] = date("Y-m-d", strtotime("+6 day", $timestamp)); // Sunday
    return($week_dates);
}

/**
 * Fonction qui permet d'afficher Oui ou Non en fonction d'un int
 *
 * @param int Valeur 1 : Oui 0 : Non
 *
 * @return string
 */
function yesno($i) {
    if ($i == 1) {
        return YES;
    } elseif ($i == 0) {
        return NO;
    }
    return '';
}

/**
 * Fonction qui formate un numéro de sécurité social
 *
 * @param string numero de secu
 *
 * @return string
 */
function formatSecuriteSocial($chaine) {
    $chaine = str_replace(' ', '', $chaine);
    $chaine = str_replace('.', '', $chaine);
    $chaine = str_replace('-', '', $chaine);
    $chaine = str_replace('/', '', $chaine);
    $sex = substr($chaine, 0, 1);
    $annee_naiss = substr($chaine, 1, 2);
    $mois_naiss = substr($chaine, 3, 2);
    # Cas Département de naissance en outre mer : http://fr.wikipedia.org/wiki/Numéro_INSEE
    $dpt = substr($chaine, 5, 3);
    $domtom = array(971, 972, 973, 974, 975, 976);
    if (in_array($dpt, $domtom)) {
        $dpt_naiss = substr($chaine, 5, 3);
        $com_naiss = substr($chaine, 8, 2);
    } else {
        $dpt_naiss = substr($chaine, 5, 2);
        $com_naiss = substr($chaine, 7, 3);
    }
    $position = substr($chaine, 10, 3);
    $cle = substr($chaine, 13, 2);
    return $sex . ' ' . $annee_naiss . ' ' . $mois_naiss . ' ' . $dpt_naiss . ' ' . $com_naiss . ' ' . $position . ' ' . $cle;
}

function convert_smart_quotes($string) {
    $search = array("&lsquo;", "&rsquo;", '&ldquo;', '&rdquo;', '&euro;', '&bull;');
    $replace = array(chr(145), chr(146), chr(147), chr(148), '€', '-');
    return str_replace($search, $replace, $string);
}

function unhtmlenperso($chaineHtml) {
    return strtr($chaineHtml, array_flip(get_html_translation_table(HTML_ENTITIES)));
}

/**
 * Retour de la date formatée en français.
 * Si le numéro du jour est inférieur à 10 alors on affiche un 0 avant sinon on affiche rien.
 *  Comme le tableau des mois commence à l'indice 0, pour afficher la bon mois, on retranche 1 à la valeur 
 *  du mois renvoyée par date("n").
 *
 * @return string
 */
function dateFr($jour = false) {
    // Tableau des mois.
    $mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $jours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
    if ($jour)
        return $jours[date("N") - 1] . " " . date("j") . " " . $mois[date("n") - 1] . " " . date("Y");
    else
        return date("j") . " " . $mois[date("n") - 1] . " " . date("Y");
}

/**
 * Affichage d'une select box contenant les mois
 *
 * @return string
 */
function getMonthList($m) {
    $mois[$m] = 'selected="selected"';
    $db = connecter();
    $result = $db->query('SELECT * FROM mois ORDER BY mois');
    while ($ligne = $result->fetchRow()) {
        $html .= '<option value=' . $ligne->mois . ' ' . $mois[$ligne->mois] . '>' . $ligne->libelle . '</option>';
    }
    return $html;
}

/**
 * Affichage du libelle du mois en fonction du mois passé en paramètre
 *
 * @return string
 */
function getMonthLibelle($m) {
    $db = connecter();
    return $db->query('SELECT libelle FROM mois WHERE mois="' . mysql_real_escape_string($m) . '"')->fetchRow()->libelle;
}

function checkSession() {
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = true;
    }
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'] . 'AGCRANDOM')) {
            echo 'Votre session a été réinitialisée. Cliquez <a href="../public/">ici</a> pour retourner à l\'accueil';
            session_destroy();
            exit;
        }
    } else {
        $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT'] . 'AGCRANDOM');
    }
}

function crypter($maCleDeCryptage = "agc2010auth", $maChaineACrypter) {
    if ($maCleDeCryptage == '') {
        $maCleDeCryptage = $GLOBALS['PHPSESSID'];
    }
    $maCleDeCryptage = md5($maCleDeCryptage);
    $letter = -1;
    $newstr = '';
    $strlen = strlen($maChaineACrypter);
    for ($i = 0; $i < $strlen; $i++) {
        $letter++;
        if ($letter > 31) {
            $letter = 0;
        }
        $neword = ord($maChaineACrypter{$i}) + ord($maCleDeCryptage{$letter});
        if ($neword > 255) {
            $neword -= 256;
        }
        $newstr .= chr($neword);
    }
    return base64_encode($newstr);
}

function decrypter($maCleDeCryptage = "agc2010auth", $maChaineCrypter) {
    if ($maCleDeCryptage == '') {
        $maCleDeCryptage = $GLOBALS['PHPSESSID'];
    }
    $maCleDeCryptage = md5($maCleDeCryptage);
    $letter = -1;
    $newstr = '';
    $maChaineCrypter = base64_decode($maChaineCrypter);
    $strlen = strlen($maChaineCrypter);
    for ($i = 0; $i < $strlen; $i++) {
        $letter++;
        if ($letter > 31) {
            $letter = 0;
        }
        $neword = ord($maChaineCrypter{$i}) - ord($maCleDeCryptage{$letter});
        if ($neword < 1) {
            $neword += 256;
        }
        $newstr .= chr($neword);
    }
    return $newstr;
}

/**
 * Fonction de comparaison pour classer un tableau contenant des objets DemandeRessource par agence et ic
 *
 * @return Array
 */
function compare_demanderessource_agence_ic(DemandeRessource $a, DemandeRessource $b) {
    $compare_agence = strnatcasecmp($a->libelleAgence, $b->libelleAgence);
    if ($compare_agence == 0) {
        return strnatcasecmp($a->Id_ic, $b->Id_ic);
    }
    return $compare_agence;
}

function datediff($a, $b) {
    $date1 = intval(substr($a, 0, 4)) * 12 + intval(substr($a, 4, 2));
    $date2 = intval(substr($b, 0, 4)) * 12 + intval(substr($b, 4, 2));
    return abs($date1 - $date2) + 1; //abs pour éviter les résultas négatifs suivant l'ordre des arguments de la fonction
}

function getFirstMonday($week, $year) {
    // Si le 1er janvier tombe semaine 53 de l'année précédent on doit tenir compte d'une semaine en plus
    if (date("W", mktime(0, 0, 0, 1, 1, $year)) == 53) {
        $week++;
    }
    $startdate = strtotime('+' . ($week - 1) . ' week', mktime(0, 0, 0, 1, 1, $year));
    return strtotime('last monday', $startdate);
}

/**
 *  Converti un ID Salesforce 15 char -> 18 char ou 18 char -> 15 char
 *  
 * @param string Identifiant Salesforce
 * @param int|null Sens de transformation (18 ou 15 char)
 *
 * @return string Identifiant converti
 */
function convertSalesforceId($id = null, $way = '18') {
    if ($id == null) {
        return 'Le SFID ne peut pas être nul.';
    }
    if ($way == 18) {
        if (strlen($id) != 15) {
            return 'Le SFID ne fait pas 15 caractères.';
        }
    } else if ($way == 15) {
        if (strlen($id) != 18) {
            return 'Le SFID ne fait pas 18 caractères.';
        }
    }

    if ($way == 18) {
        $suffix = "";
        for ($i = 0; $i < 3; $i++) {
            $flags = 0;
            for ($j = 0; $j < 5; $j++) {
                $c = substr($id, ($i * 5 + $j), 1);
                if (strpos("ABCDEFGHIJKLMNOPQRSTUVWXYZ", $c) !== false) {
                    $flags += 1 << $j;
                }
            }
            $suffix .= substr("ABCDEFGHIJKLMNOPQRSTUVWXYZ012345", $flags, 1);
        }
        return $id . "" . $suffix;
    } else if ($way == 15) {
        return substr($id, 0, -3);
    }
}

/**
 *  Ajoute la date de modification au nom du fichier
 *  
 * @param string Chemin du fichier
 * 
 * @return string Chemin du fichier modifié
 */
function auto_version($file) {
    if (!file_exists(ROOT . '/' . $file))
        return $file;

    $mtime = filemtime(ROOT . '/' . $file);
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

function normalize ($str) {
    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ??';
    $b = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $str = utf8_decode($str);    
    $str= strtr($str, utf8_decode($a), $b);
    $str = strtolower($str);
    return utf8_encode($str);
}

function normalize2($str, $charset='utf-8')
{
    $str = htmlenperso($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}

function cleanStr ($str)
{
	/** strtr() sait gérer le multibyte */
	$str = strtr($str, array(
	'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'a'=>'a', 'a'=>'a', 'a'=>'a', 'ç'=>'c', 'c'=>'c', 'c'=>'c', 'c'=>'c', 'c'=>'c', 'd'=>'d', 'd'=>'d', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'e'=>'e', 'g'=>'g', 'g'=>'g', 'g'=>'g', 'h'=>'h', 'h'=>'h', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', 'i'=>'i', '?'=>'i', 'j'=>'j', 'k'=>'k', '?'=>'k', 'l'=>'l', 'l'=>'l', 'l'=>'l', '?'=>'l', 'l'=>'l', 'ñ'=>'n', 'n'=>'n', 'n'=>'n', 'n'=>'n', '?'=>'n', '?'=>'n', 'ð'=>'o', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'o'=>'o', 'o'=>'o', 'o'=>'o', '½'=>'o', 'ø'=>'o', 'r'=>'r', 'r'=>'r', 's'=>'s', 's'=>'s', 's'=>'s', '¨'=>'s', '?'=>'s', 't'=>'t', 't'=>'t', 't'=>'t', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'u'=>'u', 'w'=>'w', 'ý'=>'y', 'ÿ'=>'y', 'y'=>'y', 'z'=>'z', 'z'=>'z', '¸'=>'z'
	));
	return $str;
}


function sendMailInformatiqueInterne ($subject, $message) {
            $hdrs = array(
                'From' => 'agc@proservia.fr',
                'Subject' => $subject,
                'To' => 'informatique.interne@proservia.fr'
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->setHTMLBody($message);

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send(array('informatique.interne@proservia.fr'), $hdrs, $body);
            return $send;
}

function sendNewEmployeeForADV($subject, $message) {
            $hdrs = array(
                'From' => 'agc@proservia.fr',
                'Return-Path'   => 'noreply@proservia.fr',
                'Subject' => $subject,
                'Content-Type'  => 'text/html; charset=UTF-8',
                'text_encoding' => '8bit',
                'text_charset'  => 'UTF-8',
                'html_charset'  => 'UTF-8',
                'head_charset'  => 'UTF-8'
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->setHTMLBody($message);

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send(array('guenola.baudry@proservia.fr','marie.chesneau@proservia.fr'), $hdrs, $body);
            return $send;
}

function sendMailCron ($subject, $message) {
            $hdrs = array(
                'From' => 'agc@proservia.fr',
                'Subject' => $subject,
                'To' => 'cron.etudedev.dsi@proservia.fr'
            );
            $crlf = "\n";

            $mime = new Mail_mime($crlf);
            $mime->setHTMLBody($message);

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            // Create the mail object using the Mail::factory method
            $params['host'] = SMTP_HOST;
            $params['port'] = SMTP_PORT;
            $mail_object = Mail::factory('smtp', $params);

            $send = $mail_object->send(array('cron.etudedev.dsi@proservia.fr'), $hdrs, $body);
            return $send;
}


/**
 * envoi du mail pour remplir le formulaire d'arrivée
*/
function sendNewEmployeeMessage($serviceManagerName, $serviceManagerMail, $ressourcePrenom, $ressourceNom) {
    $crlf = "\n";
    
            
    $htmlInner .= 'Bonjour '.$serviceManagerName.'.'.'</br>'.'</br>'.
        '<i>Si vous avez déjà transmis  votre demande de matériel ou d’accès, merci de ne pas tenir compte de ce message.</i></br></br>'.
        ''.$ressourcePrenom.' '.$ressourceNom.' va rejoindre votre service comme nouveau salarié.'.'</br>'.
        'Afin d\'anticiper au mieux son arrivée au sein de la société, nous vous demandons de compléter dès à présent le formulaire de demande de matériel et d\'accès.'.'</br>'.'</br>'.
        'Ce formulaire doit être transmis au support DSI à l\'adresse mail Support <support@manpowergroup-companies.fr> afin d\'être pris en charge dans les plus bref délais.'.'</br>'.'</br>'.
        'Vous pouvez télécharger le formulaire à l\'adresse suivante : '.'</br>'.
        '<a href="'.FICHE_DEMANDE_ACCES.'">Accéder à la fiche</a>'.'</br>'.
        'Une fois téléchargé, merci de le compléter pour envoi au support.'.'</br>'
        /*'Cordialement,'.'</br>'.
        'La Direction des Systèmes d\'Information Filiales'.'</br>'.
        'ManpowerGroup'.'</br>'.
        'Pour nous contacter :'.'</br>'.
        'Par téléphone : 05 49 76 80 20'.'</br>'.
        'Par Self Service : https://proservia.service-now.com/'.'</br>'.
        'Par e-mail : support@manpowergroup-companies.fr'.'</br>'.
        'Nous sommes à votre service du lundi au vendredi, de 08h00 à 18h00'*/;
            
            
            $html = '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8;">
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40"><head><meta name=Generator content="Microsoft Word 15 (filtered medium)"><!--[if !mso]><style>v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
w\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style><![endif]--><style><!--
/* Font Definitions */
@font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:Verdana;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
/* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman","serif";}
a:link, span.MsoHyperlink
	{mso-style-priority:99;
	color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{mso-style-priority:99;
	color:purple;
	text-decoration:underline;}
span.EmailStyle17
	{mso-style-type:personal-reply;
	font-family:"Verdana","sans-serif";
	color:#1F497D;}
.MsoChpDefault
	{mso-style-type:export-only;
	font-size:10.0pt;}
@page WordSection1
	{size:612.0pt 792.0pt;
	margin:70.85pt 70.85pt 70.85pt 70.85pt;}
div.WordSection1
	{page:WordSection1;}
--></style><!--[if gte mso 9]><xml>
<o:shapedefaults v:ext="edit" spidmax="1026" />
</xml><![endif]--><!--[if gte mso 9]><xml>
<o:shapelayout v:ext="edit">
<o:idmap v:ext="edit" data="1" />
</o:shapelayout></xml><![endif]--></head><body lang=FR link=blue vlink=purple><div class=WordSection1>
<p class=MsoNormal style=\'font-size:10.0pt;font-family:"Verdana","sans-serif";color:#1F497D\'>'.$htmlInner.'</p>
<p class=MsoNormal><span style=\'font-size:10.0pt;font-family:"Verdana","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal>
<span style=\'font-size:10.0pt;font-family:"Verdana","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal>
<span style=\'font-size:10.0pt;font-family:"Verdana","sans-serif";color:#1F497D\'>
<img border=0 width=131 height=71  src="cid:manpogroupsignature.png" alt="Manpower_signature"><o:p></o:p></span></p></td>
<td width=48 valign=top style=\'width:36.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:128.05pt\'><p class=MsoNormal><span style=\'font-size:10.0pt;font-family:"Verdana","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p>
</span></p></td><td width=474 valign=top style=\'width:355.6pt;padding:0cm 5.4pt 0cm 5.4pt;height:128.05pt\'><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Calibri","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Calibri","sans-serif";color:#1F497D\'>
<o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Calibri","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>La Direction des Systèmes d&#8217;Information Filiales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <o:p></o:p></span></p><p class=MsoNormal>
<span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>ManpowerGroup<o:p></o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>Pour nous contacter :<o:p></o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>Par téléphone : 05 49 76 80 20<o:p></o:p></span></p>
<p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>Par Self Service : </span><span style=\'font-size:11.0pt;font-family:"Calibri","sans-serif";color:#1F497D\'><a href="https://proservia.service-now.com/"><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif"\'>https://proservia.service-now.com/</span></a></span>
<span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'><o:p></o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>Par e-mail : </span><span style=\'font-size:11.0pt;font-family:"Calibri","sans-serif";color:#1F497D\'><a href="mailto:support@manpowergroup-companies.fr">
<span style=\'font-size:8.0pt;font-family:"Arial","sans-serif"\'>support@manpowergroup-companies.fr</span></a></span><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'><o:p></o:p></span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>&nbsp;<o:p></o:p>
</span></p><p class=MsoNormal><span style=\'font-size:8.0pt;font-family:"Arial","sans-serif";color:#1F497D\'>Nous sommes à votre service du lundi au vendredi, de 08h00 à 18h00<b><o:p></o:p></b></span></p></td></tr></table><p class=MsoNormal><span style=\'font-size:7.5pt;font-family:"Arial","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style=\'font-size:7.5pt;font-family:"Arial","sans-serif";color:#1F497D\'><o:p>&nbsp;</o:p></span></p><p class=MsoNormal><span style=\'font-size:7.5pt;font-family:"Arial","sans-serif";color:#1F497D\'>Pensez à l\'environnement avant d\'imprimer ce message / Think of the environment before printing this message<o:p></o:p></span></p></div></div></body></html>
';
    
    $to = array();
    $to[] = $serviceManagerMail;
    //$to[] = 'benoit.chautard@manpowergroup-companies.fr';            
    $to[] = 'cron.etudedev.dsi@proservia.fr';

    if ($serviceManagerMail == 'gael.riou@proservia.fr') {
        $cc = array('morgane.guehennec@proservia.fr', 'benoit.chautard@manpowergroup-companies.fr');
        $to[] = 'morgane.guehennec@proservia.fr';
    } else {
        $cc = 'benoit.chautard@manpowergroup-companies.fr';
    }
    
    $params = array();
    $params['host'] = SMTP_HOST;
    $params['port'] = SMTP_PORT;

    $crlf = "\n";
    $mailObject = Mail::factory('smtp', $params);

    $mimeparams=array();
    $mimeparams['text_encoding']="8bit"; 
    $mimeparams['text_charset']="UTF-8";
    $mimeparams['html_charset']="UTF-8"; 
    //$mimeparams['head_charset']="UTF-8"; 

    $hdrsMail = array(
            'From' => 'dsi_filiales@manpowergroup-companies.fr',
            'Subject' => '[RAPPEL] Arrivée personnel – Demande Matériel et Accès',
            'To' => $serviceManagerMail,
            'Cc' => $cc
    );


    $mimeMail = new Mail_mime($crlf);

    $mimeMail->addHTMLImage ('../ui/images/manpogroupsignature.png', 'image/png' , 'manpogroupsignature.png' , true ,'manpogroupsignature.png');


    $message = $html;

    $mimeMail->setHTMLBody(utf8_encode($message));

    $bodyMail = $mimeMail->get($mimeparams);
    $hdrsMail = $mimeMail->headers($hdrsMail);


    if (!$mailObject->send($to, $hdrsMail, $bodyMail)) {
        return false;
    } 
    return true;
}


function htmlscperso ($str, $flags = null) {
    return htmlspecialchars($str, $flags, 'ISO8859-1');
}

function htmlscperso_decode ($str, $flags = null) {
    return htmlspecialchars_decode($str, $flags);
}

function htmlenperso ($str, $flags = null) {
    return htmlentities($str, $flags, 'ISO8859-1');
}

function htmlenperso_decode ($str, $flags = null) {
    return html_entity_decode($str, $flags, 'ISO8859-1');
}
?>
