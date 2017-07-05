<?php
###############################################
#													#
# 		Script permettant de créer la base de données				#
#													#
###############################################

// CONNEXION AU SERVEUR MySQL

$connection = mysql_connect('localhost','root','');  
if (!$connection ) die ('connection impossible');

$creation_bd = mysql_query('CREATE DATABASE gescom');
mysql_select_db('gescom') or die ('pas de connection'); 

mysql_query("
CREATE TABLE `utilisateur` (
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	`statut` VARCHAR(255) NOT NULL default '',
	`blocnotes` TEXT,
	`last_visit` DATETIME,
	`archive` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_utilisateur` ) 
)");

mysql_query("
CREATE TABLE `bilan_utilisateur` (
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	`responsable` VARCHAR(255) NOT NULL default '',
	`mois` INT(2) NOT NULL default '0',
	`annee` INT(4) NOT NULL default '0',
	`date_creation` DATETIME,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_utilisateur`, `mois`, `annee` )
)");

mysql_query("
INSERT INTO `utilisateur` VALUES ('anthony.anne','admin','','','')
");

mysql_query("
CREATE TABLE `fonction` (
	`Id_fonction` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`Id_groupe` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_fonction` ) 
)");

mysql_query("
CREATE TABLE `rendezvous` (
	`Id_rendezvous` INT NOT NULL AUTO_INCREMENT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date` DATE,
	`type` VARCHAR(255) NOT NULL default '',
	`compte` VARCHAR(255) NOT NULL default '',
	`contact` VARCHAR(255) NOT NULL default '',
	`compte_rendu` INT(1) NOT NULL default '0',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_rendezvous` ) 
)");

mysql_query("
CREATE TABLE `action` (
	`Id_action` INT NOT NULL AUTO_INCREMENT,
	`tache` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	`date_debut` DATE,
	`date_fin` DATE,
	`nature` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_action` ) 
)");

mysql_query("
CREATE TABLE `organisation` (
  `Id_utilisateur` VARCHAR(255) NOT NULL default '',
  `Id_fonction` INT NOT NULL default '0',
  `Id_pole` INT NOT NULL default '0',
  `Id_agence` VARCHAR (255) NOT NULL default ''
)");


mysql_query("
CREATE TABLE `affaire` (
	`Id_affaire` INT NOT NULL AUTO_INCREMENT,
	`Id_compte` VARCHAR (255) NOT NULL default '',
	`Id_contact1` VARCHAR (255) NOT NULL default '',
	`Id_contact2` VARCHAR (255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',
	`redacteur1` VARCHAR(255) NOT NULL default '',
	`redacteur2` VARCHAR(255) NOT NULL default '',
	`Id_prestation` INT NOT NULL default '0',
	`Id_statut` INT NOT NULL default '0',
	`Id_agence` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	`date_modification` DATETIME,
	`archive` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire` ,`Id_compte` , `createur` , `Id_prestation` ) 
)");

mysql_query("
CREATE TABLE `historique_statut` (
	`Id_affaire` INT NOT NULL default '0',
	`date` DATETIME,
	`Id_statut` INT NOT NULL default '0',
	`Id_raison` INT NOT NULL default '0',
	`commentaire` TEXT,
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_affaire` , `date` , `Id_statut`, `Id_utilisateur`)
)");


mysql_query("
CREATE TABLE `affaire_pole` (
	`Id_affaire` INT NOT NULL default '0',
	`Id_pole` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire` , `Id_pole`) 
)");

mysql_query("
CREATE TABLE `lien_cegid` (
	`Id_affaire` INT NOT NULL default '0',
	`AFF_AFFAIRE` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_affaire` , `AFF_AFFAIRE`) 
)");

mysql_query("
CREATE TABLE `proposition` (
	`Id_proposition` INT NOT NULL AUTO_INCREMENT,
	`date_saisie` DATETIME,
	`designation` TEXT ,
	`cout` DOUBLE NOT NULL default '0',
	`frais_journalier` DOUBLE NOT NULL default '0',
	`tarif_journalier` DOUBLE NOT NULL default '0',
	`chiffre_affaire` DOUBLE NOT NULL default '0',
	`marge` DOUBLE NOT NULL default '0',
	`intitule_phase1` VARCHAR(255) NOT NULL default '',
	`intitule_phase2` VARCHAR(255) NOT NULL default '',
	`intitule_phase3` VARCHAR(255) NOT NULL default '',
	`intitule_phase4` VARCHAR(255) NOT NULL default '',
	`intitule_licence` VARCHAR(255) NOT NULL default '',
	`intitule_materiel` VARCHAR(255) NOT NULL default '',
	`intitule_autre` VARCHAR(255) NOT NULL default '',
	`cout_phase1` DOUBLE NOT NULL default '0',
	`cout_phase2` DOUBLE NOT NULL default '0',
	`cout_phase3` DOUBLE NOT NULL default '0',
	`cout_phase4` DOUBLE NOT NULL default '0',
	`cout_licence` DOUBLE NOT NULL default '0',
	`cout_materiel` DOUBLE NOT NULL default '0',
	`cout_autre` DOUBLE NOT NULL default '0',
	`ca_phase1` DOUBLE NOT NULL default '0',
	`ca_phase2` DOUBLE NOT NULL default '0',
	`ca_phase3` DOUBLE NOT NULL default '0',
	`ca_phase4` DOUBLE NOT NULL default '0',
	`ca_licence` DOUBLE NOT NULL default '0',
	`ca_materiel` DOUBLE NOT NULL default '0',
	`ca_autre` DOUBLE NOT NULL default '0',
	`Id_affaire` INT NOT NULL default '0',
	`remarque` TEXT,
	PRIMARY KEY ( `Id_proposition` , `Id_affaire`) 
)");

mysql_query("
CREATE TABLE `proposition_ressource` (
	`Id_proposition` INT NOT NULL default '0',
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`frais_journalier` DOUBLE NOT NULL default '0',
	`cout_journalier` DOUBLE NOT NULL default '0',
	`tarif_journalier` DOUBLE NOT NULL default '0',
	`duree` DOUBLE NOT NULL default '0',
	`marge` DOUBLE NOT NULL default '0',
	`ca` DOUBLE NOT NULL default '0',
	PRIMARY KEY ( `Id_proposition`, `Id_ressource` ) 
)");


mysql_query("
CREATE TABLE `ressource` (
	`Id_ressource` INT NOT NULL AUTO_INCREMENT,
	`code_ressource` VARCHAR(255) NOT NULL default '',
	`origine` VARCHAR(255) NOT NULL default '',
	`civilite` VARCHAR(5) NOT NULL default '',
	`nom` VARCHAR(255) NOT NULL default '',
	`nom_jeune_fille` VARCHAR(255) NOT NULL default '',
	`prenom` VARCHAR(255) NOT NULL default '',
	`Id_situation` INT NOT NULL default '0',
	`adresse` VARCHAR(255) NOT NULL default '',
	`code_postal` VARCHAR(255) NOT NULL default '',
	`ville` VARCHAR(255) NOT NULL default '',
	`departement` VARCHAR(255) NOT NULL default '',
	`Id_pays` INT NOT NULL default '0',
	`tel_fixe` VARCHAR(255) NOT NULL default '',
	`tel_portable` VARCHAR(255) NOT NULL default '',
	`mail` VARCHAR(255) NOT NULL default '',
	`statut` VARCHAR(255) NOT NULL default '',
	`securite_sociale` VARCHAR(255) NOT NULL default '',
	`date_naissance` DATE,
	`lieu_naissance` VARCHAR(255) NOT NULL default '',
	`Id_nationalite` INT NOT NULL default '0',
	`type_embauche` VARCHAR(3) NOT NULL default '',
	`date_embauche` DATE,
	`heure_embauche` DOUBLE NOT NULL default '0',
    `fin_cdd` DATE,
	`periode_essai` DOUBLE NOT NULL default '0',
	`Id_profil` INT NOT NULL default '0',
	`Id_cursus` INT NOT NULL default '0',
	`salaire` DOUBLE NOT NULL default '0',
	`Id_service` VARCHAR(255) NOT NULL default '',
	`resp_abs` VARCHAR(255) NOT NULL default '',
	`resp_augm` VARCHAR(255) NOT NULL default '',
	`createur` VARCHAR(255) NOT NULL default '',
	`archive` INT(2) NOT NULL default '0',
	PRIMARY KEY ( `Id_ressource` )
)");

mysql_query("
CREATE TABLE `situation` (
	`Id_situation` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_situation` ) 
)");

mysql_query("
CREATE TABLE `mobilite` (
    `Id_mobilite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_mobilite` ) 
)");

mysql_query("
CREATE TABLE `disponibilite` (
    `Id_disponibilite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_disponibilite` ) 
)");

mysql_query("
CREATE TABLE `langue` (
    `Id_langue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_langue` ) 
)");

mysql_query("
CREATE TABLE `critere_recherche` (
    `Id_critere_recherche` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_critere_recherche` ) 
)");

mysql_query("
CREATE TABLE `description` (
	`Id_description` INT NOT NULL AUTO_INCREMENT,
	`Id_intitule` INT NOT NULL default '0',
	`ville` VARCHAR(255) NOT NULL default '',
	`cp` INT(5) NOT NULL default '0',
	`site` VARCHAR(255) NOT NULL default '',
	`origine` VARCHAR(255) NOT NULL default '',
	`besoin` TEXT,
	`nature` VARCHAR(255) NOT NULL default '',
	`resume` TEXT ,
	`Id_profil1` INT NOT NULL default '0',
	`Id_profil2` INT NOT NULL default '0',
	`Id_cursus` INT NOT NULL default '0',
	`experience_requise` INT NOT NULL default '0',
	`Id_affaire` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_description`, `Id_affaire` ) 
)");

mysql_query("
CREATE TABLE `analyse_commerciale` (
	`Id_analyse_commerciale` INT NOT NULL AUTO_INCREMENT,
	`Id_affaire` INT NOT NULL default '0',
	`dernier_projet` TEXT,
	`cdc` INT(1) NOT NULL default '0',
	`decideur` INT(1) NOT NULL default '0',
	`budget_defini` INT(1) NOT NULL default '0',
	`montant_budget` DOUBLE NOT NULL default '0',
	`rdv` INT(1) NOT NULL default '0',
	`concurrents_identifies` INT(1) NOT NULL default '0',
	`partenaires_identifies` INT(1) NOT NULL default '0',
	`concurrents` TEXT,
	`partenaires` TEXT,	
	PRIMARY KEY ( `Id_analyse_commerciale` , `Id_affaire` ) 
)");

mysql_query("
CREATE TABLE `analyse_risque` (
	`Id_analyse_risque` INT NOT NULL AUTO_INCREMENT,
	`Id_affaire` INT NOT NULL default '0',
	`risque_proposition` TEXT,
	`risque_projet` TEXT,
	`niveau` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_analyse_risque` , `Id_affaire` ) 
)");

mysql_query("
CREATE TABLE `decision` (
	`Id_decision` INT NOT NULL AUTO_INCREMENT,
	`Id_affaire` INT NOT NULL default '0',
	`repondre` VARCHAR(255) NOT NULL default '',
	`Id_raison_decision` INT NOT NULL default '0',
	`date_report` DATE,
	`expediteur` VARCHAR(255) NOT NULL default '',
	`decideur_reunion_lancement` VARCHAR(255) NOT NULL default '',
	`decideur_reunion_bouclage` VARCHAR(255) NOT NULL default '',
	`date_reunion_lancement` DATE,
	`date_reunion_bouclage` DATE,
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_decision`, `Id_affaire` )
)");

mysql_query("
CREATE TABLE `raison_decision` (
	`Id_raison_decision` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_raison_decision` ) 
)");

mysql_query("
CREATE TABLE `raison_perdue` (
	`Id_raison_perdue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_raison_perdue` )
)");

mysql_query("
CREATE TABLE `environnement` (
	`Id_environnement` INT NOT NULL AUTO_INCREMENT,
	`Id_affaire` INT NOT NULL default '0',
	`nb_poste` INT NOT NULL default '0',
	`nb_pcfixe` INT NOT NULL default '0',
	`nb_portable` INT NOT NULL default '0',
	`nb_serveur` INT NOT NULL default '0',
	`nb_site` INT NOT NULL default '0',
	`nb_utilisateur` INT NOT NULL default '0',
	`complement` TEXT,
	`env_technique` TEXT,
	PRIMARY KEY ( `Id_environnement` , `Id_affaire` ) 
)");


mysql_query("
CREATE TABLE `affaire_competence` (
	`Id_affaire` INT NOT NULL default '0',
	`Id_competence` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire` , `Id_competence`  ) 
)");

mysql_query("
CREATE TABLE `affaire_exigence` (
	`Id_affaire` INT NOT NULL default '0',
	`Id_exigence` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_affaire` , `Id_exigence`  ) 
)");

mysql_query("
CREATE TABLE `planning` (
	`Id_planning` INT NOT NULL AUTO_INCREMENT,
	`date_demande` DATE default '0000-00-00',
	`date_limite` DATE default '0000-00-00',
	`date_debut` DATE default '0000-00-00',
	`date_soutenance` DATE default '0000-00-00',
	`duree` DOUBLE NOT NULL default '0',
	`date_fin` DATE default '0000-00-00',
	`commentaire` TEXT ,
	`Id_affaire` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_planning` , `Id_affaire` ) 
)");

mysql_query("
CREATE TABLE `statut` (
	`Id_statut` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(40) NOT NULL default '',
	PRIMARY KEY ( `Id_statut` ) 
)");

mysql_query("
CREATE TABLE `domaine` (
	`Id_domaine` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_domaine` ) 
)");

mysql_query("
CREATE TABLE `intitule` (
	`Id_intitule` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`Id_prestation` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_intitule` , `Id_prestation` )
)");

mysql_query("
CREATE TABLE `competence` (
	`Id_competence` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`description` TEXT,
	`Id_domaine` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_competence` , `Id_domaine` ) 
)");

mysql_query("
CREATE TABLE `exigence` (
	`Id_exigence` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`description` TEXT,
	PRIMARY KEY ( `Id_exigence` ) 
)");

mysql_query("
CREATE TABLE `cursus` (
	`Id_cursus` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_cursus` ) 
)");

mysql_query("
CREATE TABLE `profil` (
	`Id_profil` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_profil` ) 
)");

mysql_query("
CREATE TABLE `experience` (
	`Id_experience` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`commentaire` TEXT,
	PRIMARY KEY ( `Id_experience` ) 
)");

mysql_query("
CREATE TABLE `prestation` (
	`Id_prestation` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`description` TEXT NOT NULL default '',
	PRIMARY KEY ( `Id_prestation` ) 
)");

mysql_query("
CREATE TABLE `pole` (
	`Id_pole` INT(2) NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_pole` ) 
)");

mysql_query("
CREATE TABLE `groupe` (
	`Id_groupe` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_groupe` ) 
)");


mysql_query("
CREATE TABLE `indemnite` (
	`Id_indemnite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	`type` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_indemnite` ) 
)");

mysql_query("
CREATE TABLE `type_indemnite` (
	`Id_type_indemnite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_type_indemnite` ) 
)");

mysql_query("
INSERT INTO `indemnite` VALUES
('','Indemnités de repas (Ticket Restaurant)','1'),
('','Repas du soir','1'),
('','Forfait SNCF 2ème classe','1'),
('','Indemnités kilométriques','1'),
('','Péage','1'),
('','Carburant','1'),
('','Taxi','1'),
('','Transport en commun','1'),
('','Hébergement','1'),
('','Indemnités de repas (Ticket Restaurant)','2'),
('','Repas du soir (sur justificatif)','2'),
('','Repas du soir (forfaitaire)','2'),
('','Forfait SNCF 2ème classe','2'),
('','Indemnités kilométriques','2'),
('','Péage','2'),
('','Carburant','2'),
('','Taxi','2'),
('','Transport en commun','2'),
('','Hotel prise en charge Collaborateur','2'),
('','Hotel prise en charge Proservia','2'),
('','Forfait Province','2'),
('','Forfait Département (75/92/93/94)','2'),
('','Indemnités de repas (Ticket Restaurant)','3'),
('','Abonnement Région parisienne','3')
");

mysql_query("
CREATE TABLE `cd_indemnite` (
	`Id_contrat_delegation` INT NOT NULL default '0',
	`Id_indemnite` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_contrat_delegation` , `Id_indemnite` ) 
)");

mysql_query("
CREATE TABLE `contrat_delegation` (
	`Id_contrat_delegation` INT NOT NULL AUTO_INCREMENT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	`date_modification` DATETIME,
	`Id_affaire` INT NOT NULL default '0',
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`date_embauche` DATE,
	`heure_embauche` DOUBLE NOT NULL default '0',
	`salaire` DOUBLE NOT NULL default '0',
	`cout_journalier` DOUBLE NOT NULL default '0',
	`type_mission` VARCHAR(255) NOT NULL default '',
	`debut_mission` DATE,
	`fin_mission` DATE,
	`duree_mission` DOUBLE NOT NULL default '0',
	`lieu_mission` VARCHAR(255) NOT NULL default '',
	`indemnites_ref` VARCHAR(255) NOT NULL default '',
	`nature_mission` VARCHAR(255) NOT NULL default '',
	`st_nom` VARCHAR(255) NOT NULL default '',
	`st_prenom` VARCHAR(255) NOT NULL default '',
	`st_societe` VARCHAR(255) NOT NULL default '',
	`st_adresse` VARCHAR(255) NOT NULL default '',
	`st_siret` VARCHAR(255) NOT NULL default '',
	`st_ape` VARCHAR(255) NOT NULL default '',
	`st_tarif` DOUBLE NOT NULL default '0',
	`st_commentaire` TEXT,
	`horaire` VARCHAR(255) NOT NULL default '',
	`commentaire_horaire` TEXT,
	`moyen_acces` VARCHAR(255) NOT NULL default '',
	`Id_type_indemnite` INT NOT NULL default '0',
	`indemnite` TEXT,
	`commentaire_indemnite` TEXT,
	`astreinte` VARCHAR(3) NOT NULL default '',
	`commentaire_astreinte` TEXT,	
	`archive` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_contrat_delegation` , `Id_affaire`, `Id_ressource`)
)");


mysql_query("
CREATE TABLE `nature_candidature` (
	`Id_nature_candidature` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_nature_candidature` ) 
)");

mysql_query("
INSERT INTO `nature_candidature` VALUES
('','ANPE'),
('','APEC CVthèque'),
('','APEC Annonce'),
('','RégionJob CVthèque'),
('','RégionJob Annonce'),
('','Spontanée'),
('','CVWeb Proservia'),
('','Les Jeudis Annonce'),
('','Les Jeudis CVthèque'),
('','Cooptation'),
('','Forum/Salon (précisez)')
");

mysql_query("
CREATE TABLE `etat_candidature` (
	`Id_etat_candidature` INT(2) NOT NULL AUTO_INCREMENT,
	`libelle` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_etat_candidature` ) 
)");

mysql_query("
INSERT INTO `etat_candidature` VALUES
('','Non lu'),
('','Lu'),
('','Convoqué'),
('','1er entretien'),
('','2nd entretien'),
('','Validé'),
('','Non validé'),
('','Embauché CDD'),
('','Embauché CDI'),
('','Intégré'),
('','CV envoyé client'),
('','Présentation Client'),
('','Reprise de contact'),
('','A relancer')
");


mysql_query("
CREATE TABLE `candidature` (
	`Id_candidature` INT NOT NULL AUTO_INCREMENT,
	`Id_ressource` VARCHAR(255) NOT NULL default '',
	`date` DATE,
	`Id_nature` INT NOT NULL default '0',
	`Id_etat` INT NOT NULL default '0',
	`type_validation` VARCHAR(255) NOT NULL default '',
    `stage` INT(1) NOT NULL default '0',
	`alternance` INT(1) NOT NULL default '0',
	`createur` VARCHAR(255) NOT NULL default '',
	`lien_cv` TEXT,
	`lien_cvp` TEXT,
	`lien_lm` TEXT,
	`commentaire` TEXT,
	`archive` INT(1) NOT NULL default '0',
	PRIMARY KEY ( `Id_candidature` , `Id_ressource` , `Id_nature` , `Id_etat` , `createur`)
)");

/*
while($i < 500) {
    $j = $i%6;
	$k = $i%3;
	
	if($j == 0) {
	   $createur = 'anthony.anne';
	} else {
	    $createur = 'cathy.cuccia';
	}
	
    mysql_query("
    INSERT INTO `candidature` VALUES
    ('$i','$j','','$j','$k','$createur','commentaire blablabla','0')
    ");
	
	
	mysql_query("
    INSERT INTO `rendezvous` VALUES
    ('','$createur','2008-06-17','Client','compte$k','contact$k','1','Commentaire$k')
    ");
	
	
    mysql_query("
    INSERT INTO `action` VALUES
    ('','tache$j','$createur','2008-06-17 17:25:24','2008-06-17','2008-07-28','nature$k')
    ");
	
	
    ++$i;
	
	
}*/



mysql_query("
CREATE TABLE `entretien` (
	`Id_entretien` INT NOT NULL AUTO_INCREMENT,
	`Id_candidature` INT NOT NULL default '0',
	`Id_recruteur` VARCHAR(255) NOT NULL default '',
	`Id_commercial` VARCHAR(255) NOT NULL default '',
	`Id_disponibilite` INT NOT NULL default '0',
	`date_disponibilite` DATE,
	`dispo_nego` INT NOT NULL default '0',
	`ps_inf` DOUBLE NOT NULL default '0',
	`ps_sup` DOUBLE NOT NULL default '0',
	`attente_pro` TEXT,
	`Id_profil_envisage` INT NOT NULL default '0',
	`avancement_recherche` VARCHAR(255) NOT NULL default '',
	`connaissance_proservia` INT(1) NOT NULL default '0',
	`debut_stage` DATE,
	`fin_stage` DATE,
	`commentaire_rh` TEXT,
	`commentaire_com` TEXT,
	`commentaire_rt` TEXT,
	`createur` VARCHAR(255) NOT NULL default '',
	`date_creation` DATETIME,
	PRIMARY KEY ( `Id_entretien`, `Id_candidature`, `Id_recruteur`, `date_creation` )
)");

mysql_query("
CREATE TABLE `entretien_mobilite` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_mobilite` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien` , `Id_mobilite` ) 
)");

mysql_query("
CREATE TABLE `niveau_langue` (
	`Id_niveau_langue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NULL default '',
	PRIMARY KEY ( `Id_niveau_langue` ) 
)");

mysql_query("
CREATE TABLE `entretien_langue` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_langue` INT NOT NULL default '0',
	`Id_niveau` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien` , `Id_langue` , `Id_niveau`) 
)");

mysql_query("
CREATE TABLE `entretien_critere` (
	`Id_entretien` INT NOT NULL default '0',
	`Id_critere` INT NOT NULL default '0',
	PRIMARY KEY ( `Id_entretien` , `Id_critere` ) 
)");

mysql_query("
CREATE TABLE `historique_candidature` (
    `Id_candidature` INT NOT NULL default '0',
	`Id_etat` INT NOT NULL default '0',
	`date` DATETIME,
	`Id_utilisateur` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_candidature` , `Id_etat`, `date`, `Id_utilisateur` )
)");

mysql_query("
INSERT INTO `disponibilite` VALUES
('','Immédiate'),
('','1 mois'),
('','2 mois'),
('','3 mois')
");

mysql_query("
INSERT INTO `type_indemnite` VALUES
('','Petit Déplacement'),
('','Grand Déplacement'),
('','Région Parisienne')
");

mysql_query("
INSERT INTO `niveau_langue` VALUES
('','Technique'),
('','Courant'),
('','Bilingue')
");

mysql_query("
INSERT INTO `mobilite` VALUES
('','Ouest'),
('','Est'),
('','IDF'),
('','Sud Est'),
('','Sud Ouest'),
('','Nord Est'),
('','Nord Ouest'),
('','Centre'),
('','Nantes'),
('','Rennes'),
('','Lannion'),
('','Brest'),
('','Le Havre'),
('','Caen'),
('','Rouen'),
('','Paris'),
('','Lille'),
('','Niort'),
('','Lyon'),
('','Bordeaux'),
('','Toulouse'),
('','Aix en Provence'),
('','Sophia'),
('','Tours'),
('','Orléans'),
('','Le Mans'),
('','Redon')
");

mysql_query("
INSERT INTO `pole` VALUES
('','Téléservices'),
('','Développement'),
('','Formation'),
('','Conseil / Expertise'),
('','Infogérance'),
('','Gouvernance des SI')
");

mysql_query("
INSERT INTO `prestation` VALUES
('','Assistance Technique',''),
('','Téléservices / Infogérance',''),
('','TMA / Centre de services',''),
('','Formation',''),
('','Conseil & Expertise',''),
('','Gouvernance des SI','')
");

mysql_query("
INSERT INTO `statut` VALUES
('','Piste'),
('','Non traitée'),
('','En cours de rédaction'),
('','Remise'),
('','Signée'),
('','Perdue')
");

mysql_query("
INSERT INTO `situation` VALUES
('','Mariée'),
('','Célibataire'),
('','Bénéficiaire du PACS'),
('','Vie maritale'),
('','Veuf'),
('','Divorcé')
");

mysql_query("
INSERT INTO `domaine` VALUES
('','Bases de données'),
('','Langage'),
('','Langue'),
('','Réseaux')
");

mysql_query("
INSERT INTO `langue` VALUES
('','Anglais'),
('','Espagnol'),
('','Allemand'),
('','Italien')
");

mysql_query("
INSERT INTO `critere_recherche` VALUES
('','Salaire'),
('','Mission'),
('','Evolution'),
('','Formation'),
('','Culture entreprise')
");

mysql_query("
INSERT INTO `competence` VALUES
('','Access','','1'),
('','Business object','','1'),
('','Delphi','','1'),
('','Developer 2000','','1'),
('','Adabas','','1'),
('','Natstar','','1'),
('','Visualage pacbase','','1'),
('','Db2','','1'),
('','Ingres','','1'),
('','Adaptive server entreprise','','1'),
('','Informix se','','1'),
('','Oracle designer','','1'),
('','Corel paradox','','1'),
('','Vsam','','1'),
('','Progress','','1'),
('','Dbase 2, 3 ou +','','1'),
('','Dl1','','1'),
('','Reflex','','1'),
('','Foxpro','','1'),
('','Autre','','1'),
('','Ids2','','1'),
('','Jdbc','','1'),
('','O2','','1'),
('','Oracle','','1'),
('','Microsoft SQL server','','1'),
('','Sybase','','1'),
('','Assembleur','','2'),
('','Ada 95','','2'),
('','C','','2'),
('','C++','','2'),
('','Clist','','2'),
('','Clipper','','2'),
('','Cobol','','2'),
('','Fortran','','2'),
('','Gap/rpg','','2'),
('','Pascal','','2'),
('','Turbo pascal','','2'),
('','Perl','','2'),
('','Sql','','2'),
('','Pl1','','2'),
('','Shell','','2'),
('','Smalltalk','','2'),
('','Transac sql','','2'),
('','Xml','','2'),
('','Html','','2'),
('','Java','','2'),
('','Ns-dk','','2'),
('','Visual basic','','2'),
('','4gl','','2'),
('','Windows isql','','2'),
('','Magic vx.x','','2'),
('','Orion','','2'),
('','Rexx','','2'),
('','Basic','','2'),
('','Visual c++','','2'),
('','Delphi','','2'),
('','Powerbuilder','','2'),
('','Gap2','','2'),
('','Gap4','','2'),
('','Prolog','','2'),
('','Windev','','2'),
('','Autre','','2'),
('','Vhdl','','2'),
('','Abap','','2'),
('','Activex','','2'),
('','Caml','','2'),
('','Forth','','2'),
('','Php','','2'),
('','Servlet','','2'),
('','Lotus script','','2'),
('','Ejb','','2'),
('','Dhtml','','2'),
('','Vbscript','','2'),
('','Javascript','','2'),
('','Asp','','2'),
('','Jsp','','2'),
('','Uml','','2'),
('','Python','','2'),
('','Anglais','','3'),
('','Espagnol','','3'),
('','Allemand','','3'),
('','Corba','','4'),
('','Encina','','4'),
('','Ipx/spx','','4'),
('','Lan manager','','4'),
('','Lan server','','4'),
('','Mq series','','4'),
('','Sna','','4'),
('','Tcp/ip','','4'),
('','Tds','','4'),
('','Top end','','4'),
('','Tuxedo','','4'),
('','Netware','','4'),
('','As/400','','4'),
('','Transpac','','4'),
('','Rnis','','4'),
('','Internet','','4'),
('','Ethernet','','4'),
('','Intranet','','4'),
('','X25','','4'),
('','Ftp','','4'),
('','Numeris','','4'),
('','Netview','','4'),
('','Cisco','','4'),
('','Autre','','4'),
('','Hp openview','','4'),
('','Patrol','','4'),
('','Wan','','4'),
('','Ntbeui','','4'),
('','FDDI','','4'),
('','ATM','','4'),
('','HDLC','','4'),
('','PPP','','4'),
('','ADSL','','4')
");

mysql_query("
INSERT INTO `exigence` VALUES
('','Engagement de résultat',''),
('','Engagement de moyen',''),
('','SLA / Pénalités',''),
('','Garanties',''),
('','Astreintes',''),
('','Normes Qualités (CMMI, ITIL, ISO, ...)',''),
('','Sécurité (défense, éléctrique)','')
");


mysql_query("
INSERT INTO `cursus` VALUES
('','BAC'),
('','Autre bac+2'),
('','Autre bac+3'),
('','Autre bac+4'),
('','Autre bac+5'),
('','BTS'),
('','DUT'),
('','Licence'),
('','Miage'),
('','Maîtrise'),
('','Ecole d\'ingénieur'),
('','DUT GTR')
");

mysql_query("
INSERT INTO `intitule` VALUES
('','Support de proximité','1'),
('','Support Help Desk','1'),
('','Intégration Mise en production','1'),
('','Test / validation','1'),
('','Déploiement','1'),
('','Ingénierie Système','1'),
('','Ingénierie Réseaux','1'),
('','Téléphonie','1'),
('','Ingénierie télécom','1'),
('','SAV réseaux','1'),
('','Supervision réseaux','1'),
('','Exploitation','1'),
('','Analyse','1'),
('','Conception','1'),
('','développement','1'),
('','Intégration','1'),
('','Tests – recette','1'),
('','Revue de code','1'),
('','Audit de code','1'),
('','Audit applicatif','1'),
('','Pilotage projet Technique','1'),
('','Pilotage projet Fonctionnel','1'),
('','Pilotage projet MOE','1'),
('','Pilotage projet MOA','1'),
('','Forfait développement','3'),
('','TMA','3'),
('','Centre de service','3'),
('','Centre de compétence','3'),
('','audit','5'),
('','conseil','5'),
('','intégration','5'),
('','mise en œuvre','5'),
('','Centre d’expertise','5'),
('','Audit Technique','5'),
('','Audit Fonctionnel','5'),
('','Architecture','5'),
('','Expertise Annuaire','5'),
('','Expertise Bases de données','5'),
('','Conception de salle Informatique','5'),
('','Audit d’impression','5'),
('','Achat Licence','5'),
('','Achat Logiciel','5'),
('','Achat Matériel','5'),
('','Expertise Messagerie','5'),
('','Migration','5'),
('','Mobilité','5'),
('','Expertise Postes Clients','5'),
('','PRA / PCA','5'),
('','Expertise Réseau','5'),
('','Sauvegarde / restauration','5'),
('','Sécurité','5'),
('','Serveurs','5'),
('','Stockage','5'),
('','Support Niveau 3','5'),
('','Expertise Telecom','5'),
('','Virtualisation','5'),
('','Administration : systèmes, réseaux, SGBD, serveurs','2'),
('','Déploiement et gestion des postes de travail','2'),
('','Gestion de configuration','2'),
('','Exploitation / Pilotage / Supervision','2'),
('','Help Desk et Support N1 N2 N3','2'),
('','Homologation/Intégration/Mise en Production','2'),
('','Infrastructure','4'),
('','Citrix','4'),
('','Portail','4'),
('','Développement','4'),
('','Projet/qualité','4'),
('','Messagerie','4'),
('','Base de données','4'),
('','Sécurité','4'),
('','Production','4'),
('','Qualité (ITIL, CMMI)','4'),
('','BUREAUTIQUE','4'),
('','MULTIMEDIA','4'),
('','RESSOURCES HUMAINES','4'),
('','LANGUES ETRANGERES','4')
");


mysql_query("
INSERT INTO `profil` VALUES
('','Développeur Web'),
('','Chef de Projet'),
('','Support Technique'),
('','Ingenieur Systèmes et Réseaux'),
('','Ingenieur Réseaux'),
('','Ingenieur Qualité'),
('','Ingenieur Développeur'),
('','Administrateur Base de Données'),
('','Responsable Etudes'),
('','Responsable Informatique'),
('','Responsable Recrutement'),
('','Technicien Réseaux et Télécoms'),
('','Administrateur Unix\Linux'),
('','Analyste d\'Exploitation'),
('','Analyste Programmeur'),
('','Technicien d\'Exploitation'),
('','Technicien Micro & Réseaux'),
('','Administrateur Systemes'),
('','Ingenieur Commercial'),
('','Assistant Commercial'),
('','Responsable Technique'),
('','Consultant'),
('','Chef de Projet Web'),
('','Expert ntic'),
('','Concepteur asic/gsm/gprs/umts'),
('','Ingénieur Info Industrielle'),
('','Webdesigner'),
('','Webmaster'),
('','Ingenieur Intégration'),
('','Ingenieur TV Numérique'),
('','Expert Langages Objets'),
('','Admin Systèmes et Réseaux'),
('','Infographistes'),
('','Développeur'),
('','Architecte de Production'),
('','Architecte Réseaux'),
('','Architecte Technique'),
('','Formateur')
");

mysql_query("
INSERT INTO `experience` VALUES
('','Junior','< 2 ans'),
('','Confirmé','de 2 à 5 ans'),
('','Expert','> 5 ans')
");

mysql_query("
INSERT INTO `groupe` VALUES
('','Commerciaux'),
('','Administratif'),
('','Opérationnel'),
('','Ressources Humaines')
");

mysql_query("
INSERT INTO `raison_decision` VALUES
('','Abandon projet client'),
('','Délai trop court'),
('','Pas de profil'),
('','Pas de RT disponible')
");

mysql_query("
INSERT INTO `raison_perdue` VALUES
('','Trop cher'),
('','Concurrent meilleur'),
('','Technique'),
('','Délai')
");

mysql_query("
INSERT INTO `fonction` VALUES
('','Directeur Commercial','1'),
('','Directeur d\'agence','1'),
('','Directeur Administratif et Financier','2'),
('','Directeur Opérationnel','3'),
('','Directeur Ressources Humaines','4'),
('','Responsable de Pôle','3'),
('','Responsable Technique Administratif','2'),
('','Chef de Projet Administratif','2'),
('','Responsable Technique Opérationnel','3'),
('','Chef de Projet Opérationnel','3'),
('','Responsable Facturation','2'),
('','Responsable Compta','2'),
('','Responsable Paye','2'),
('','Responsable RH','4'),
('','Chargé de recrutement','4'),
('','Commercial','1')
");


mysql_query("
CREATE TABLE `departement` (
	`code` VARCHAR(2) NOT NULL default '',
	`nom` VARCHAR(100) NOT NULL default '',
	PRIMARY KEY ( `code` )
)");

mysql_query("
CREATE TABLE `pays` (
    `id` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(80) NOT NULL default '',
	`code` CHAR(2) NOT NULL default '',
	PRIMARY KEY ( `id` )
)");



mysql_query("
INSERT INTO `departement` VALUES
('01','Ain'),
('02','Aisne'),
('03','Allier'),
('04','Alpes-de-Haute-Provence'),
('05','Hautes-Alpes'),
('06','Alpes-Maritimes'),
('07','Ardèche'),
('08','Ardennes'),
('09','Ariège'),
('10','Aube'),
('11','Aude'),
('12','Aveyron'),
('13','Bouches-du-Rhône'),
('14','Calvados'),
('15','Cantal'),
('16','Charente'),
('17','Charente-Maritime'),
('18','Cher'),
('19','Corrèze'),
('21','Côte-d\'Or'),
('22','Côtes-d\'Armor'),
('23','Creuse'),
('24','Dordogne'),
('25','Doubs'),
('26','Drôme'),
('27','Eure'),
('28','Eure-et-loir'),
('29','Finistère'),
('2A','Corse-du-sud'),
('2B','Haute-Corse'),
('30','Gard'),
('31','Haute-Garonne'),
('32','Gers'),
('33','Gironde'),
('34','Hérault'),
('35','Ille-et-Vilaine'),
('36','Indre'),
('37','Indre-et-Loire'),
('38','Isère'),
('39','Jura'),
('40','Landes'),
('41','Loir-et-Cher'),
('42','Loire'),
('43','Haute-Loire'),
('44','Loire-Atlantique'),
('45','Loiret'),
('46','Lot'),
('47','Lot-et-Garonne'),
('48','Lozère'),
('49','Maine-et-Loire'),
('50','Manche'),
('51','Marne'),
('52','Haute-Marne'),
('53','Mayenne'),
('54','Meurthe-et-Moselle'),
('55','Meuse'),
('56','Morbihan'),
('57','Moselle'),
('58','Nièvre'),
('59','Nord'),
('60','Oise'),
('61','Orne'),
('62','Pas-de-Calais'),
('63','Puy-de-Dôme'),
('64','Pyrénées-Atlantiques'),
('65','Hautes-Pyrénées'),
('66','Pyrénées-orientales'),
('67','Bas-Rhin'),
('68','Haut-Rhin'),
('69','Rhône'),
('70','Haute-Saône'),
('71','Saône-et-Loire'),
('72','Sarthe'),
('73','Savoie'),
('74','Haute-Savoie'),
('75','Ville de Paris'),
('76','Seine-Maritime'),
('77','Seine-et-Marne'),
('78','Yvelines'),
('79','Deux-sèvres'),
('80','Somme'),
('81','Tarn'),
('82','Tarn-et-Garonne'),
('83','Var'),
('84','Vaucluse'),
('85','Vendée'),
('86','Vienne'),
('87','Haute-Vienne'),
('88','Vosges'),
('89','Yonne'),
('90','Territoire de Belfort'),
('91','Essonne'),
('92','Hauts-de-Seine'),
('93','Seine-Saint-Denis'),
('94','Val-de-Marne'),
('95','Val-d\'Oise')
");



mysql_query("
INSERT INTO pays VALUES
('','France','fr'),
('','Afghanistan','af'),
('','Afrique du sud','za'),
('','Albanie','al'),
('','Algérie','dz'),
('','Allemagne','de'),
('','Arabie saoudite','sa'),
('','Argentine','ar'),
('','Australie','au'),
('','Autriche','at'),
('','Belgique','be'),
('','Brésil','br'),
('','Bulgarie','bg'),
('','Canada','ca'),
('','Chili','cl'),
('','Chine (Rép. pop.)','cn'),
('','Colombie','co'),
('','Corée, Sud','kr'),
('','Costa Rica','cr'),
('','Croatie','hr'),
('','Danemark','dk'),
('','Égypte','eg'),
('','Émirats arabes unis','ae'),
('','Équateur','ec'),
('','États-Unis','us'),
('','El Salvador','sv'),
('','Espagne','es'),
('','Finlande','fi'),
('','Grèce','gr'),
('','Hong Kong','hk'),
('','Hongrie','hu'),
('','Inde','in'),
('','Indonésie','id'),
('','Irlande','ie'),
('','Israël','il'),
('','Italie','it'),
('','Japon','jp'),
('','Jordanie','jo'),
('','Liban','lb'),
('','Malaisie','my'),
('','Maroc','ma'),
('','Mexique','mx'),
('','Norvège','no'),
('','Nouvelle-Zélande','nz'),
('','Pérou','pe'),
('','Pakistan','pk'),
('','Pays-Bas','nl'),
('','Philippines','ph'),
('','Pologne','pl'),
('','Porto Rico','pr'),
('','Portugal','pt'),
('','République tchèque','cz'),
('','Roumanie','ro'),
('','Royaume-Uni','uk'),
('','Russie','ru'),
('','Singapour','sg'),
('','Suède','se'),
('','Suisse','ch'),
('','Taiwan','tw'),
('','Thailande','th'),
('','Turquie','tr'),
('','Ukraine','ua'),
('','Venezuela','ve'),
('','Yougoslavie','yu'),
('','Samoa','as'),
('','Andorre','ad'),
('','Angola','ao'),
('','Anguilla','ai'),
('','Antarctique','aq'),
('','Antigua et Barbuda','ag'),
('','Arménie','am'),
('','Aruba','aw'),
('','Azerbaïdjan','az'),
('','Bahamas','bs'),
('','Bahrain','bh'),
('','Bangladesh','bd'),
('','Biélorussie','by'),
('','Belize','bz'),
('','Benin','bj'),
('','Bermudes (Les)','bm'),
('','Bhoutan','bt'),
('','Bolivie','bo'),
('','Bosnie-Herzégovine','ba'),
('','Botswana','bw'),
('','Bouvet (Îles)','bv'),
('','Territoire britannique de l\'océan Indien','io'),
('','Vierges britanniques (Îles)','vg'),
('','Brunei','bn'),
('','Burkina Faso','bf'),
('','Burundi','bi'),
('','Cambodge','kh'),
('','Cameroun','cm'),
('','Cap Vert','cv'),
('','Cayman (Îles)','ky'),
('','République centrafricaine','cf'),
('','Tchad','td'),
('','Christmas (Île)','cx'),
('','Cocos (Îles)','cc'),
('','Comores','km'),
('','Rép. Dém. du Congo','cg'),
('','Cook (Îles)','ck'),
('','Cuba','cu'),
('','Chypre','cy'),
('','Djibouti','dj'),
('','Dominique','dm'),
('','République Dominicaine','do'),
('','Timor','tp'),
('','Guinée Equatoriale','gq'),
('','Érythrée','er'),
('','Estonie','ee'),
('','Ethiopie','et'),
('','Falkland (Île)','fk'),
('','Féroé (Îles)','fo'),
('','Fidji (République des)','fj'),
('','Guyane française','gf'),
('','Polynésie française','pf'),
('','Territoires français du sud','tf'),
('','Gabon','ga'),
('','Gambie','gm'),
('','Géorgie','ge'),
('','Ghana','gh'),
('','Gibraltar','gi'),
('','Groenland','gl'),
('','Grenade','gd'),
('','Guadeloupe','gp'),
('','Guam','gu'),
('','Guatemala','gt'),
('','Guinée','gn'),
('','Guinée-Bissau','gw'),
('','Guyane','gy'),
('','Haïti','ht'),
('','Heard et McDonald (Îles)','hm'),
('','Honduras','hn'),
('','Islande','is'),
('','Iran','ir'),
('','Irak','iq'),
('','Côte d\'Ivoire','ci'),
('','Jamaïque','jm'),
('','Kazakhstan','kz'),
('','Kenya','ke'),
('','Kiribati','ki'),
('','Corée du Nord','kp'),
('','Koweit','kw'),
('','Kirghizistan','kg'),
('','Laos','la'),
('','Lettonie','lv'),
('','Lesotho','ls'),
('','Libéria','lr'),
('','Libye','ly'),
('','Liechtenstein','li'),
('','Lithuanie','lt'),
('','Luxembourg','lu'),
('','Macau','mo'),
('','Macédoine','mk'),
('','Madagascar','mg'),
('','Malawi','mw'),
('','Maldives (Îles)','mv'),
('','Mali','ml'),
('','Malte','mt'),
('','Marshall (Îles)','mh'),
('','Martinique','mq'),
('','Mauritanie','mr'),
('','Maurice','mu'),
('','Mayotte','yt'),
('','Micronésie (États fédérés de)','fm'),
('','Moldavie','md'),
('','Monaco','mc'),
('','Mongolie','mn'),
('','Montserrat','ms'),
('','Mozambique','mz'),
('','Myanmar','mm'),
('','Namibie','na'),
('','Nauru','nr'),
('','Nepal','np'),
('','Antilles néerlandaises','an'),
('','Nouvelle Calédonie','nc'),
('','Nicaragua','ni'),
('','Niger','ne'),
('','Nigeria','ng'),
('','Niue','nu'),
('','Norfolk (Îles)','nf'),
('','Mariannes du Nord (Îles)','mp'),
('','Oman','om'),
('','Palau','pw'),
('','Panama','pa'),
('','Papouasie-Nouvelle-Guinée','pg'),
('','Paraguay','py'),
('','Pitcairn (Îles)','pn'),
('','Qatar','qa'),
('','Réunion (La)','re'),
('','Rwanda','rw'),
('','Géorgie du Sud et Sandwich du Sud (Îles)','gs'),
('','Saint-Kitts et Nevis','kn'),
('','Sainte Lucie','lc'),
('','Saint Vincent et les Grenadines','vc'),
('','Samoa','ws'),
('','Saint-Marin (Rép. de)','sm'),
('','São Tomé et Príncipe (Rép.)','st'),
('','Sénégal','sn'),
('','Seychelles','sc'),
('','Sierra Leone','sl'),
('','Slovaquie','sk'),
('','Slovénie','si'),
('','Somalie','so'),
('','Sri Lanka','lk'),
('','Sainte Hélène','sh'),
('','Saint Pierre et Miquelon','pm'),
('','Soudan','sd'),
('','Suriname','sr'),
('','Svalbard et Jan Mayen (Îles)','sj'),
('','Swaziland','sz'),
('','Syrie','sy'),
('','Tadjikistan','tj'),
('','Tanzanie','tz'),
('','Togo','tg'),
('','Tokelau','tk'),
('','Tonga','to'),
('','Trinité et Tobago','tt'),
('','Tunisie','tn'),
('','Turkménistan','tm'),
('','Turks et Caïques (Îles)','tc'),
('','Tuvalu','tv'),
('','Îles Mineures Éloignées des États-Unis','um'),
('','Ouganda','ug'),
('','Uruguay','uy'),
('','Ouzbékistan','uz'),
('','Vanuatu','vu'),
('','Vatican (Etat du)','va'),
('','Vietnam','vn'),
('','Vierges (Îles)','vi'),
('','Wallis et Futuna (Îles)','wf'),
('','Sahara Occidental','eh'),
('','Yemen','ye'),
('','Zaïre','zr'),
('','Zambie','zm'),
('','Zimbabwe','zw'),
('','La Barbad','bb')
");


mysql_query("
CREATE TABLE `nationalite` (
	`Id_nationalite` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NULL default '',
	PRIMARY KEY ( `Id_nationalite` ) 
)");


mysql_query("
INSERT INTO `nationalite` VALUES
('','Afghane'), 
('','Sud-africaine'), 
('','Aland Islander'), 
('','Albanaise'), 
('','Algérienne'), 
('','Allemande'), 
('','American Samoan'), 
('','Angolaise'), 
('','Anguillan'), 
('','Antigua et Barbuda'), 
('','Antillan'), 
('','Saoudienne'), 
('','Argentine'), 
('','Arménienne'), 
('','Aruban'), 
('','Australienne'), 
('','Autrichienne'), 
('','Azerbaijani'), 
('','Bahaméenne'), 
('','Bahraini'), 
('','Barbadian'), 
('','Biélorusse'), 
('','Belge'), 
('','Belizean'), 
('','Bengladeshi'), 
('','Béninoise'), 
('','Bermudian'), 
('','Bhutanese'), 
('','Bolivian'), 
('','Bosniaque'), 
('','Botswanan'), 
('','Bouvet Island'), 
('','Brésilienne'), 
('','Bruneian'), 
('','Bulgare'), 
('','Burkinabé'), 
('','Burundaise'), 
('','Cambodgienne'), 
('','Camerounaise'), 
('','Canadienne'), 
('','Cap-Verdienne'), 
('','Centrafricaine'), 
('','Chilienne'), 
('','Chinoise'), 
('','Christmas Islander'), 
('','Chypriote'), 
('','Cocos (Keeling) Islands'), 
('','Colombienne'), 
('','Congolaise'), 
('','Cook Islander'), 
('','Nord-Coréenne'), 
('','Sud-Coréenne'), 
('','Costaricienne'), 
('','Ivoirienne'), 
('','Croate'), 
('','Cubaine'), 
('','Danoise'), 
('','Djiboutienne'), 
('','Dominicain'), 
('','Dubai'), 
('','Egyptienne'), 
('','Emirati'), 
('','Equatorienne'), 
('','Erythréenne'), 
('','Espagnole'), 
('','Estonienne'), 
('','Micronésien'), 
('','Ethiopienne'), 
('','Fidjienne'), 
('','Finlandaise'), 
('','Française'), 
('','Gabonaise'), 
('','Gambaise'), 
('','Géorgienne'), 
('','Géorgie du Sud et Les Îles Sandwich du Sud'), 
('','Ghanéenne'), 
('','Britannique'), 
('','Grecque'), 
('','Grenadine'), 
('','Groënlandaise'), 
('','Française (Guadeloupe)'), 
('','Guam'), 
('','Guatémaltèque'), 
('','Britannique'), 
('','Guinéenne (Guinée)'), 
('','Guinéenne'), 
('','Guinéenne (Guinée équatoriale)'), 
('','Bissau-Guinéenne'), 
('','Guinean'), 
('','Française (Guyane)'), 
('','Haïtienne'), 
('','Hollandaise'), 
('','Hongroise'), 
('','Mauricienne'), 
('','Comorienne'), 
('','Féroé'), 
('','Malouin'), 
('','Indienne'), 
('','Indonésienne'), 
('','Iranienne'), 
('','Irlandaise'), 
('','Islandaise'), 
('','Israélienne'), 
('','Italienne'), 
('','Jamaïcaine'), 
('','Japonaise'), 
('','Kenyane'), 
('','Koweitienne'), 
('','Libanaise'), 
('','Lituanienne'), 
('','Luxembourgeoise'), 
('','Lybienne'), 
('','Macédonienne'), 
('','Malgache'), 
('','Malaisienne'), 
('','Malgache'), 
('','Malienne'), 
('','Maltaise'), 
('','Marocaine'), 
('','Française (Martinique)'), 
('','Mauritanienne'), 
('','Française (Mayotte)'), 
('','Mexicaine'), 
('','Moldave'), 
('','Monégasque'), 
('','Mongole'), 
('','Monténégraine'), 
('','Népalaise'), 
('','Nicaraguayénne'), 
('','Nigérienne'), 
('','Nigérianne'), 
('','Norvégienne'), 
('','Française (Nouvelle Calédonie)'), 
('','Néo-zélandaise'), 
('','Pakistanaise'), 
('','Papouane-néo-guinéenne'), 
('','Néerlandaise'), 
('','Polonaise'), 
('','Polynésienne'), 
('','Portugaise'), 
('','Congolaise'), 
('','Dominicaine'), 
('','Malgache (Rep.)'), 
('','Tchèque'), 
('','Française (Réunion)'), 
('','Roumaine'), 
('','Britannique'), 
('','Britannique'), 
('','Russe'), 
('','Rwandaise'), 
('','Sahraouite'), 
('','Saint Barthelemy'), 
('','Française (Saint-Pierre et Miquelon)'), 
('','Sénégalaise'), 
('','Serbe'), 
('','Singapourienne'), 
('','Slovaque'), 
('','Slovène'), 
('','Sri-Lankaise'), 
('','Suédoise'), 
('','Suisse'), 
('','Syrienne'), 
('','Tahitienne'), 
('','Taïwanaise'), 
('','Tchadienne'), 
('','Thaïlandaise'), 
('','Togolaise'), 
('','Tunisienne'), 
('','Turque'), 
('','Urugayénne'), 
('','Américaine'), 
('','Vénézuélienne'), 
('','Vietnamienne'), 
('','Yougoslave')
");



?>
<script language='javascript' type='text/javascript'>
alert('La création des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>	