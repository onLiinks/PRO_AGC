<?php
/**
  * Fichier menuGauche.html.php
  *
  * Menu de consultation
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

?>
<h2><?php echo CONSULTATION; ?></h2><br />
<ul class='menu'>
    <?php echo GestionMenu::afficherMenu('g'); ?>
</ul>