<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('gescom') or die ('pas de connection'); 

mysql_query("ALTER TABLE `contrat_delegation` add column tache TEXT");

?>
<script language='javascript' type='text/javascript'>
alert('La mise à jour des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>