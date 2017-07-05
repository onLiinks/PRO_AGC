<?php

/**
 * Fichier ContactFactory.php
 *
 * @author    Mathieu Perrin
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe ContactFactory
 */
class ContactFactory {
    public static function create($type = null, $id = null) {
        if (is_null($type)) {
            if (substr_count($id, '-') >= 2) {
                list($pref, $id, $compte) = explode('-', $id, 3);
            }
            else {
                list($pref, $id) = explode('-', $id, 2);
            }
            switch ($pref) {
                case 'CG':
                    $contact = new ContactCegid($id . '-' . $compte, array());
                    break;
                case 'SF':
                    $contact = new ContactSF($id, array());
                    break;
                default:
                    $contact = new ContactCegid($id . '-' . $compte, array());
            }
        } else {
            if (is_null($id)) {
                switch ($type) {
                    case 'CG':
                        $contact = 'ContactCegid';
                        break;
                    case 'SF':
                        $contact = 'ContactSF';
                        break;
                    default:
                        $contact = 'ContactCegid';
                }
            }
            else {
                if (substr_count($id, '-') >= 2) {
                    list($pref, $id, $compte) = explode('-', $id, 3);
                }
                else {
                    list($pref, $id) = explode('-', $id, 2);
                }
                switch ($type) {
                    case 'CG':
                        $contact = new ContactCegid($id . '-' . $compte, array());
                        break;
                    case 'SF':
                        $contact = new ContactSF($id, array());
                        break;
                    default:
                        $contact = new ContactCegid($id . '-' . $compte, array());
                }
            }
        }
        return $contact;
    }

}

?>