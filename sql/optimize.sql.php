<?php
###############################################
#													#
# 		Script permettant d'optimiser les tables de la base de données		#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');
mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 
$alltables = mysql_query('SHOW TABLES');
while ($table = mysql_fetch_assoc($alltables)) {
   foreach ($table as $db => $tablename) {
      mysql_query('OPTIMIZE TABLE '.$tablename.'') or die(mysql_error());
   }
}

?>
<script language='javascript' type='text/javascript'>
alert('Optimisation terminée'); 
window.location.replace('../index.php');
</script>