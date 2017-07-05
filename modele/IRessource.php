<?php

/**
 *
 * @author mathieu.perrin
 */
interface IRessource {
    /**
     * Affichage d'une select box contenant les ressources
     *
     * @param string Type de la ressource : candidat validé / embauché / non candidat
     *
     * @return string
     */
    public function getList($type = null);

    /**
     * Affichage du Nom et du prénom de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public function getName();

    /**
     * Affichage de l'email de la ressource
     *
     * @param int Identifiant de la ressource
     *
     * @return string
     */
    public static function getMail($i);
}

?>
