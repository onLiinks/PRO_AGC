<?php

/**
 * Fichier IndemniteRepas.php
 *
 * @author Jérôme Lamy
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe IndemniteRepas
 */
class IndemniteRepas {

    /**
     * Identifiant de l'IR
     *
     * @access private
     * @var string
     */
    private $Id_indemnite;
    /**
     * Libellé de l'IR
     *
     * @access public
     * @var string
     */
    public $libelle;

    /**
     * Constructeur de la classe IndemniteRpas
     *
     * @param string Identifiant de l'IR
     *
     */
    public function __construct($Id_indemnite) {
        $this->Id_indemnite = $Id_indemnite;
        $db = connecter_cegid();
        $ligne = $db->query('SELECT PPI_PROFIL, PPI_LIBELLE FROM PROSERVIA.dbo.PROFILPAIE WHERE PPI_PROFIL = "' . $Id_indemnite . '"')->fetchRow();
        $this->libelle = $ligne->PPI_PROFIL . ' - ' . $ligne->PPI_LIBELLE;
    }

    /**
     * Affichage d'une select box contenant les section
     *
     * @return string
     */
    public function getList() {
        $db = connecter_cegid();
        $indemnite[$this->Id_indemnite] = 'selected="selected"';
        $result = $db->query('SELECT PPI_PROFIL, PPI_LIBELLE FROM PROSERVIA.dbo.PROFILPAIE WHERE PPI_PROFIL like "C%" ORDER BY PPI_PROFIL');
        while ($ligne = $result->fetchRow()) {
            $html .= '<option value="' . $ligne->ppi_profil . '"' . $indemnite[$ligne->ppi_profil] . '>' . $ligne->ppi_profil . ' ' . $ligne->ppi_libelle . '</option>';
        }
        return $html;
    }

    /**
     * Affichage du libelle de l'indemnité repas d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getIndemniteByResource($i) {
        $db = connecter_cegid();
        $result = $db->query('SELECT PPI_PROFIL, PPI_LIBELLE FROM PROSERVIA.dbo.PROFILPAIE '
                            . 'JOIN PROSERVIA.dbo.SALARIES ON PPI_PROFIL = PSA_REDREPAS '
                            . 'WHERE PSA_SALARIE = "' . $i . '"')->fetchRow();
        return $result->PPI_PROFIL . ' - ' . $result->PPI_LIBELLE;
    }
    
    /**
     * Affichage de l'identifiant d'indemnité repas d'une ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getIdIndemniteByResource($i) {
        $db = connecter_cegid();
        return $result = $db->query('SELECT PPI_PROFIL FROM PROSERVIA.dbo.PROFILPAIE '
                            . 'JOIN PROSERVIA.dbo.SALARIES ON PPI_PROFIL = PSA_REDREPAS '
                            . 'WHERE PSA_SALARIE = "' . $i . '"')->fetchRow()->PPI_PROFIL;
    }
    
    /**
     * Affichage du libelle de l'IR
     *
     * @param int Id de l'IR
     *
     * @return string
     */
    public static function getLibelle($i) {
        $db = connecter_cegid();
        $result = $db->query('SELECT PPI_LIBELLE FROM PROSERVIA.dbo.PROFILPAIE WHERE PPI_PROFIL = "' . $i . '"')->fetchRow();
        return $i . ' - ' . $result->ppi_libelle;
    }
}

?>