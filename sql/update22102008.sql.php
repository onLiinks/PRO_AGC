<?php
###############################################
#													#
# 		Script permettant de cr�er la base de donn�es				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('gescom') or die ('pas de connection'); 

mysql_query("ALTER TABLE `contrat_delegation` add column tache TEXT");

?>
<script language='javascript' type='text/javascript'>
alert('La mise � jour des bases a �t� effectu�e avec succ�s'); 
window.location.replace('../index.php');
</script>