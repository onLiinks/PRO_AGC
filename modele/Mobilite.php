<?php

/**
 * @file Mobilite.php
 * Classe pour gérer la mobilite d'un candidat
 *
 * @author Marc Olivier ETOURNEAU
 * @since Juillet 2008
 */
class Mobilite {

    /**
     * Identifiant de l'entretien
     *
     * @access private
     * @var int
     */
    private $Id_entretien;
    /**
     * region_souhaitee La ou les régions souhaitées pour la mobilité
     * @var array
     * @access private
     */
    private $region_souhaitee;

    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_entretien = '';
                $this->region_souhaitee = '';
            }
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_entretien = '';
                if (!empty($tab['region_souhaitee'])) {
                    foreach ($tab['region_souhaitee'] as $i) {
                        $this->region_souhaitee[] = $i;
                    }
                }
            }
            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $result = $db->query('SELECT Id_mobilite FROM entretien_mobilite WHERE Id_entretien = ' . mysql_real_escape_string((int) $code));
                $this->Id_entretien = $code;
                while ($ligne = $result->fetchRow()) {
                    $this->region_souhaitee[] = $ligne->id_mobilite;
                }
            }
            /* Cas 4 : un code et un tableau : prendre des infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_entretien = $code;
                if (!empty($tab['region_souhaitee'])) {
                    foreach ($tab['region_souhaitee'] as $i) {
                        $this->region_souhaitee[] = $i;
                    }
                }
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    public function form() {
        $html = '<div class="container"><div class="form">';
        $db = connecter();
        $mobilite = array();
        if (!empty($this->region_souhaitee)) {
            foreach ($this->region_souhaitee as $i) {
                $mobilite[$i] = 'checked="checked"';
                $temp = split('-', $i);
                $taille = count($temp);
                if ($taille == 2) {
                    if ($temp[0] != '') {
                        $func .= 'disable_sup(\'' . $temp[0] . '-' . $temp[1] . '\');';
                    }
                } elseif ($taille == 3) {
                    $func .= 'disable_sup(\'' . $temp[0] . '-' . $temp[1] . '-' . $temp[2] . '\');';
                }
            }
        }
        $html .= '<div class="form">';
        $result = $db->query('SELECT * FROM zone');

        while ($reponse = $result->fetchRow()) {
            if ($reponse->id_zone == 1) {
                $html .= '<img src="' . IMG_PLUS . '" alt="+" id="i' . $reponse->id_zone . '" onclick="deplie(\'' . $reponse->id_zone . '\')"><label for="r' . $reponse->id_zone . '"><input type="checkbox" name="region_souhaitee[]" value="' . $reponse->id_zone . '" ' . $mobilite[$reponse->id_zone] . ' id="r' . $reponse->id_zone . '" onclick="disable_sup(\'' . $reponse->id_zone . '\')">
			    ' . $reponse->libelle . '
			    </input></label><br />';
            } else {
                $html .= '<label for="r' . $reponse->id_zone . '"><input type="checkbox" name="region_souhaitee[]" value="' . $reponse->id_zone . '" ' . $mobilite[$reponse->id_zone] . ' id="r' . $reponse->id_zone . '">
			    ' . $reponse->libelle . '
			    </input></label><br />';
            }
            $html .= '<div id="m' . $reponse->id_zone . '" style="display: none">';
            $result2 = $db->query('SELECT * FROM region WHERE Id_zone = ' . mysql_real_escape_string((int) $reponse->id_zone) . '');
            while ($reponse2 = $result2->fetchRow()) {
                $html .= '&nbsp;&nbsp;|--<img src="' . IMG_PLUS . '" alt="+" id="i' . $reponse->id_zone . '-' . $reponse2->id_region . '" onclick="deplie(\'' . $reponse->id_zone . '-' . $reponse2->id_region . '\')"><label for="r' . $reponse->id_zone . '-' . $reponse2->id_region . '"><input type="checkbox" name="region_souhaitee[]" value="' . $reponse->id_zone . '-' . $reponse2->id_region . '" ' . $mobilite[$reponse->id_zone . '-' . $reponse2->id_region] . ' id="r' . $reponse->id_zone . '-' . $reponse2->id_region . '" onclick="disable_sup(\'' . $reponse->id_zone . '-' . $reponse2->id_region . '\')">
				' . $reponse2->libelle . '</input></label><br />';
                $html .= '<div id="m' . $reponse->id_zone . '-' . $reponse2->id_region . '" style="display: none">';
                $result3 = $db->query('SELECT * FROM departement WHERE Id_region=' . mysql_real_escape_string((int) $reponse2->id_region) . '');

                while ($reponse3 = $result3->fetchRow()) {
                    $html .= '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--<label for="r' . $reponse->id_zone . '-' . $reponse2->id_region . '-' . $reponse3->id_departement . '"><input type="checkbox" name="region_souhaitee[]" value="' . $reponse->id_zone . '-' . $reponse2->id_region . '-' . $reponse3->id_departement . '" id="r' . $reponse->id_zone . '-' . $reponse2->id_region . '-' . $reponse3->id_departement . '" ' . $mobilite[$reponse->id_zone . '-' . $reponse2->id_region . '-' . $reponse3->id_departement] . ' onclick="disable_sup(\'' . $reponse->id_zone . '-' . $reponse2->id_region . '-' . $reponse3->id_departement . '\')">
					' . $reponse3->nom . '</input></label><br />';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        $html .= '</div></div></div><script>' . $func . '</script>';
        return $html;
    }

    /**
     * Méthode getId_entretien
     * @return donnée membre Id_entretien
     */
    public function getId_entretien() {
        return $this->Id_entretien;
    }

    /**
     * Méthode getRegion_souhaitee
     * @return donnée membre region_souhaitee
     */
    public function getRegion_souhaitee() {
        return $this->region_souhaitee;
    }

    /**
     * Méthode setRegion_souhaitee : changer la région souhaitee
     * @param region_souhaitee La nouvelle region_souhaitee
     */
    public function setRegion_souhaitee($region_souhaitee) {
        $this->region_souhaitee = $region_souhaitee;
    }

}

?>