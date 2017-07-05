<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

	$tabmois = array (
        '1' => 'Janvier',
        '2' => 'Février',
        '3' => 'Mars',
        '4' => 'Avril',
        '5' => 'Mai',
        '6' => 'Juin',
        '7' => 'Juillet',
        '8' => 'Août',
        '9' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
        );


		
mysql_select_db('AGC_PROSERVIA') or die ('pas de connection'); 
mysql_query('DROP TABLE mois');
mysql_query("CREATE TABLE mois(
    mois VARCHAR(7), 
	debmois date, 
	finmois date,
	libelle VARCHAR(20)
)");

$a = 2008;
$k = '01';
while ($a < 2016) {
    $i = 1;
    while ($i < 13) {
	    $j       = ($i < 10 ) ? '0'.$i : $i;
		$m       = mktime( 0, 0, 0, $j, 1, $a );
		$mois    = $a.'-'.$j;
		$libelle = $tabmois[$i].' '.$a;
	    $nb      = date('t',$m);
		mysql_query("INSERT INTO mois VALUES ('".$mois."', $a$j$k, $a$j$nb,'".$libelle."')");
	    ++$i;
	}
    ++$a;
}

mysql_select_db('AGC_OVIALIS') or die ('pas de connection'); 
mysql_query('DROP TABLE mois');
mysql_query("CREATE TABLE mois(
    mois VARCHAR(7), 
	debmois date, 
	finmois date,
	libelle VARCHAR(20)
)");

$a = 2008;
$k = '01';
while ($a < 2016) {
    $i = 1;
    while ($i < 13) {
	    $j       = ($i < 10 ) ? '0'.$i : $i;
		$m       = mktime( 0, 0, 0, $j, 1, $a );
		$mois    = $a.'-'.$j;
		$libelle = $tabmois[$i].' '.$a;
	    $nb      = date('t',$m);
		mysql_query("INSERT INTO mois VALUES ('".$mois."', $a$j$k, $a$j$nb,'".$libelle."')");
	    ++$i;
	}
    ++$a;
}

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection'); 
mysql_query('DROP TABLE mois');
mysql_query("CREATE TABLE mois(
    mois VARCHAR(7), 
	debmois date, 
	finmois date,
	libelle VARCHAR(20)
)");

$a = 2008;
$k = '01';
while ($a < 2016) {
    $i = 1;
    while ($i < 13) {
	    $j       = ($i < 10 ) ? '0'.$i : $i;
		$m       = mktime( 0, 0, 0, $j, 1, $a );
		$mois    = $a.'-'.$j;
		$libelle = $tabmois[$i].'-'.$a;
	    $nb      = date('t',$m);
		mysql_query("INSERT INTO mois VALUES ('".$mois."', $a$j$k, $a$j$nb,'".$libelle."')");
	    ++$i;
	}
    ++$a;
}


?>
<script language='javascript' type='text/javascript'>
alert('Création des mois avec succès'); 
</script>