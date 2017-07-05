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

mysql_query("ALTER TABLE `ressource` ADD column `Id_agence` VARCHAR( 10 ) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `destinataire_cd` ADD column `service` INT( 1 ) NOT NULL DEFAULT 0");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`, `service`) VALUES 
('eliane.merdrignac@proservia.fr', 'PAR TEL','2'),
('eliane.merdrignac@proservia.fr', 'PAR B&A','2'),
('eliane.merdrignac@proservia.fr', 'PAR TMS','2'),
('eliane.merdrignac@proservia.fr', 'PAR ISP','2'),
('eliane.merdrignac@proservia.fr', 'PAR ENE','2'),
('delphine.charpentier@proservia.fr', 'LAN','2'),
('delphine.charpentier@proservia.fr', 'REN','2'),
('delphine.charpentier@proservia.fr', '001','2'),
('delphine.charpentier@proservia.fr', 'TRS','2'),
('delphine.charpentier@proservia.fr', 'NIO','2'),
('marina.chauvel@proservia.fr', 'CAE','2'),
('marina.chauvel@proservia.fr', 'LHA','2'),
('marina.chauvel@proservia.fr', 'ROU','2'),
('marina.chauvel@proservia.fr', 'LIL','2'),
('marina.chauvel@proservia.fr', 'LYO','2'),
('marina.chauvel@proservia.fr', 'SOP','2'),
('marina.chauvel@proservia.fr', 'AIX','2'),
('marina.chauvel@proservia.fr', 'TOU','2'),
('marina.chauvel@proservia.fr', 'BDX','2')
");

mysql_query("UPDATE `destinataire_cd` SET service=1 WHERE service=''");

//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` ADD column `Id_agence` VARCHAR( 10 ) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `destinataire_cd` ADD column `service` INT( 1 ) NOT NULL DEFAULT 0");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`, `service`) VALUES 
('eliane.merdrignac@proservia.fr', 'PAR TEL','2'),
('eliane.merdrignac@proservia.fr', 'PAR B&A','2'),
('eliane.merdrignac@proservia.fr', 'PAR TMS','2'),
('eliane.merdrignac@proservia.fr', 'PAR ISP','2'),
('eliane.merdrignac@proservia.fr', 'PAR ENE','2'),
('delphine.charpentier@proservia.fr', 'LAN','2'),
('delphine.charpentier@proservia.fr', 'REN','2'),
('delphine.charpentier@proservia.fr', '001','2'),
('delphine.charpentier@proservia.fr', 'TRS','2'),
('delphine.charpentier@proservia.fr', 'NIO','2'),
('marina.chauvel@proservia.fr', 'CAE','2'),
('marina.chauvel@proservia.fr', 'LHA','2'),
('marina.chauvel@proservia.fr', 'ROU','2'),
('marina.chauvel@proservia.fr', 'LIL','2'),
('marina.chauvel@proservia.fr', 'LYO','2'),
('marina.chauvel@proservia.fr', 'SOP','2'),
('marina.chauvel@proservia.fr', 'AIX','2'),
('marina.chauvel@proservia.fr', 'TOU','2'),
('marina.chauvel@proservia.fr', 'BDX','2')
");

mysql_query("UPDATE `destinataire_cd` SET service=1 WHERE service=''");

//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` ADD column `Id_agence` VARCHAR( 10 ) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `destinataire_cd` ADD column `service` INT( 1 ) NOT NULL DEFAULT 0");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`, `service`) VALUES 
('eliane.merdrignac@proservia.fr', 'PAR TEL','2'),
('eliane.merdrignac@proservia.fr', 'PAR B&A','2'),
('eliane.merdrignac@proservia.fr', 'PAR TMS','2'),
('eliane.merdrignac@proservia.fr', 'PAR ISP','2'),
('eliane.merdrignac@proservia.fr', 'PAR ENE','2'),
('delphine.charpentier@proservia.fr', 'LAN','2'),
('delphine.charpentier@proservia.fr', 'REN','2'),
('delphine.charpentier@proservia.fr', '001','2'),
('delphine.charpentier@proservia.fr', 'TRS','2'),
('delphine.charpentier@proservia.fr', 'NIO','2'),
('marina.chauvel@proservia.fr', 'CAE','2'),
('marina.chauvel@proservia.fr', 'LHA','2'),
('marina.chauvel@proservia.fr', 'ROU','2'),
('marina.chauvel@proservia.fr', 'LIL','2'),
('marina.chauvel@proservia.fr', 'LYO','2'),
('marina.chauvel@proservia.fr', 'SOP','2'),
('marina.chauvel@proservia.fr', 'AIX','2'),
('marina.chauvel@proservia.fr', 'TOU','2'),
('marina.chauvel@proservia.fr', 'BDX','2')
");

mysql_query("UPDATE `destinataire_cd` SET service=1 WHERE service=''");
//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');

mysql_query("ALTER TABLE `ressource` ADD column `Id_agence` VARCHAR( 10 ) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE `destinataire_cd` ADD column `service` INT( 1 ) NOT NULL DEFAULT 0");

mysql_query("
INSERT INTO `destinataire_cd` (`mail`, `Id_agence`, `service`) VALUES 
('eliane.merdrignac@proservia.fr', 'PAR TEL','2'),
('eliane.merdrignac@proservia.fr', 'PAR B&A','2'),
('eliane.merdrignac@proservia.fr', 'PAR TMS','2'),
('eliane.merdrignac@proservia.fr', 'PAR ISP','2'),
('eliane.merdrignac@proservia.fr', 'PAR ENE','2'),
('delphine.charpentier@proservia.fr', 'LAN','2'),
('delphine.charpentier@proservia.fr', 'REN','2'),
('delphine.charpentier@proservia.fr', '001','2'),
('delphine.charpentier@proservia.fr', 'TRS','2'),
('delphine.charpentier@proservia.fr', 'NIO','2'),
('marina.chauvel@proservia.fr', 'CAE','2'),
('marina.chauvel@proservia.fr', 'LHA','2'),
('marina.chauvel@proservia.fr', 'ROU','2'),
('marina.chauvel@proservia.fr', 'LIL','2'),
('marina.chauvel@proservia.fr', 'LYO','2'),
('marina.chauvel@proservia.fr', 'SOP','2'),
('marina.chauvel@proservia.fr', 'AIX','2'),
('marina.chauvel@proservia.fr', 'TOU','2'),
('marina.chauvel@proservia.fr', 'BDX','2')
");

mysql_query("UPDATE `destinataire_cd` SET service=1 WHERE service=''");


?>