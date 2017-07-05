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
mysql_query("ALTER TABLE `utilisateur` DROP column statut");
mysql_query("ALTER TABLE `utilisateur` ADD column groupe_ad VARCHAR(255) NOT NULL DEFAULT ''");


mysql_query("ALTER TABLE `bilan_activite` DROP column commentaire");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_ca TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_marge TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column affaires_en_cours TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rdv TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column ouverture_compte TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column intercontrats TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_collab_suivi TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rh TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_heures_sup TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column action_a_definir TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column point_divers TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column conclusion TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column date_prochaine_reunion DATE NOT NULL DEFAULT '0000-00-00'");



//**************************************************************************************************//

mysql_select_db('AGC_OVIALIS') or die ('pas de connection');
mysql_query("ALTER TABLE `utilisateur` DROP column statut");
mysql_query("ALTER TABLE `utilisateur` ADD column groupe_ad VARCHAR(255) NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `bilan_activite` DROP column commentaire");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_ca TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_marge TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column affaires_en_cours TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rdv TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column ouverture_compte TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column intercontrats TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_collab_suivi TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rh TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_heures_sup TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column action_a_definir TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column point_divers TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column conclusion TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column date_prochaine_reunion DATE NOT NULL DEFAULT '0000-00-00'");


//**************************************************************************************************//

mysql_select_db('AGC_WIZTIVI') or die ('pas de connection');
mysql_query("ALTER TABLE `utilisateur` DROP column statut");
mysql_query("ALTER TABLE `utilisateur` ADD column groupe_ad VARCHAR(255) NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `bilan_activite` DROP column commentaire");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_ca TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_marge TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column affaires_en_cours TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rdv TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column ouverture_compte TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column intercontrats TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_collab_suivi TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rh TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_heures_sup TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column action_a_definir TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column point_divers TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column conclusion TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column date_prochaine_reunion DATE NOT NULL DEFAULT '0000-00-00'");


//**************************************************************************************************//

mysql_select_db('AGC_NEEDPROFILE') or die ('pas de connection');
mysql_query("ALTER TABLE `utilisateur` DROP column statut");
mysql_query("ALTER TABLE `utilisateur` ADD column groupe_ad VARCHAR(255) NOT NULL DEFAULT ''");

mysql_query("ALTER TABLE `bilan_activite` DROP column commentaire");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_ca TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column avancement_marge TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column affaires_en_cours TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rdv TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column ouverture_compte TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column intercontrats TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_collab_suivi TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column rh TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column bilan_heures_sup TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column action_a_definir TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column point_divers TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column conclusion TEXT");
mysql_query("ALTER TABLE `bilan_activite` ADD column date_prochaine_reunion DATE NOT NULL DEFAULT '0000-00-00'");

?>