<?php

/**
 * Fichier CompteFactory.php
 *
 * @author    Mathieu Perrin
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe CompteFactory
 */
class CompteFactory {
    public static function create($type = null, $id = null) {
        if (is_null($type)) {
            if (strpos($id, '-') !== false) {
                list($pref, $id) = explode('-', $id, 2);
            }
            switch ($pref) {
                case 'CG':
                    $compte = new CompteCegid($id, array());
                    break;
                case 'SF':
                    $compte = new CompteSF($id, array());
                    break;
                default:
                    $compte = new CompteCegid($id, array());
            }
        } else {
            if (is_null($id)) {
                switch ($type) {
                    case 'CG':
                        $compte = 'CompteCegid';
                        break;
                    case 'SF':
                        $compte = 'CompteSF';
                        break;
                    default:
                        $compte = 'CompteCegid';
                }
            }
            else {
                if (strpos($id, '-') !== false) {
                    list($pref, $id) = explode('-', $id, 2);
                }
                switch ($type) {
                    case 'CG':
                        $compte = new CompteCegid($id, array());
                        break;
                    case 'SF':
                        $compte = new CompteSF($id, array());
                        break;
                    default:
                        $compte = new CompteCegid($id, array());
                }
            }
        }
        return $compte;
    }

}

?>