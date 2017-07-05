<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

mysql_select_db('AGC_PROSERVIA') or die ('pas de connection');

mysql_query("ALTER TABLE `pole` add column trig VARCHAR(20) NOT NULL DEFAULT ''");

mysql_query("UPDATE `pole` SET trig='SUPPORT / PROD' WHERE Id_pole=1");
mysql_query("UPDATE `pole` SET trig='DEV' WHERE Id_pole=2");
mysql_query("UPDATE `pole` SET trig='FOR' WHERE Id_pole=3");
mysql_query("UPDATE `pole` SET trig='CONS / EXP' WHERE Id_pole=4");
mysql_query("UPDATE `pole` SET trig='GOUV' WHERE Id_pole=5");
mysql_query("UPDATE `pole` SET trig='IPTV' WHERE Id_pole=6");
mysql_query("UPDATE `pole` SET trig='NEEDP' WHERE Id_pole=7");


//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `pole` add column trig VARCHAR(20) NOT NULL DEFAULT ''");

mysql_query("UPDATE `pole` SET trig='SUPPORT / PROD' WHERE Id_pole=1");
mysql_query("UPDATE `pole` SET trig='DEV' WHERE Id_pole=2");
mysql_query("UPDATE `pole` SET trig='FOR' WHERE Id_pole=3");
mysql_query("UPDATE `pole` SET trig='CONS / EXP' WHERE Id_pole=4");
mysql_query("UPDATE `pole` SET trig='GOUV' WHERE Id_pole=5");
mysql_query("UPDATE `pole` SET trig='IPTV' WHERE Id_pole=6");
mysql_query("UPDATE `pole` SET trig='NEEDP' WHERE Id_pole=7");




//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `pole` add column trig VARCHAR(20) NOT NULL DEFAULT ''");

mysql_query("UPDATE `pole` SET trig='SUPPORT / PROD' WHERE Id_pole=1");
mysql_query("UPDATE `pole` SET trig='DEV' WHERE Id_pole=2");
mysql_query("UPDATE `pole` SET trig='FOR' WHERE Id_pole=3");
mysql_query("UPDATE `pole` SET trig='CONS / EXP' WHERE Id_pole=4");
mysql_query("UPDATE `pole` SET trig='GOUV' WHERE Id_pole=5");
mysql_query("UPDATE `pole` SET trig='IPTV' WHERE Id_pole=6");
mysql_query("UPDATE `pole` SET trig='NEEDP' WHERE Id_pole=7");

//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `pole` add column trig VARCHAR(20) NOT NULL DEFAULT ''");

mysql_query("UPDATE `pole` SET trig='SUPPORT / PROD' WHERE Id_pole=1");
mysql_query("UPDATE `pole` SET trig='DEV' WHERE Id_pole=2");
mysql_query("UPDATE `pole` SET trig='FOR' WHERE Id_pole=3");
mysql_query("UPDATE `pole` SET trig='CONS / EXP' WHERE Id_pole=4");
mysql_query("UPDATE `pole` SET trig='GOUV' WHERE Id_pole=5");
mysql_query("UPDATE `pole` SET trig='IPTV' WHERE Id_pole=6");
mysql_query("UPDATE `pole` SET trig='NEEDP' WHERE Id_pole=7");

?>