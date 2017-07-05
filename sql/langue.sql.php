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


/////////////////////////////////////////////////////////////TABLE LANGUE //////////////////////////////////////////////////////

mysql_query("DROP TABLE `langue`");

mysql_query("
CREATE TABLE `langue` (
    `Id_langue` INT NOT NULL AUTO_INCREMENT,
	`nom` VARCHAR(255) NOT NULL default '',
	PRIMARY KEY ( `Id_langue` ) 
)");

mysql_query("
INSERT INTO `langue` VALUES
('','Anglais'),
('','Espagnol'),
('','Allemand'),
('','Italien'),
('','Arabe'),
('','Japonais'),
('','Russe'),
('','Afrikaans'),
('','Albanais'),
('','Amharique'),
('','Anglais'),
('','Anguar'),
('','Arabe'),
('','Arménien'),
('','Aymara'),
('','Azéri'),
('','Basque'),
('','Bengalî'),
('','Bichelamar'),
('','Biélorusse'),
('','Birman'),
('','Bulgare'),
('','Cantonais'),
('','Carolinien'),
('','Catalan'),
('','Chamorro'),
('','Chichewa'),
('','Chinois'),
('','Cinghalais'),
('','Coréen'),
('','Créole de Guinée-Bissau'),
('','Créole haïtien'),
('','Créole seychellois'),
('','Croate'),
('','Danois'),
('','Divehi'),
('','Dzongkha'),
('','Estonien'),
('','Fidjien'),
('','Filipino'),
('','Finnois'),
('','Français'),
('','Galicien'),
('','Gallois'),
('','Géorgien'),
('','Gilbertin'),
('','Grec'),
('','Guarani'),
('','Hatohabei'),
('','Hawaiien'),
('','Hébreu'),
('','Hindi'),
('','Hindoustani'),
('','Hiri Motu'),
('','Hongrois'),
('','Iban'),
('','Indonésien'),
('','Inuktitut'),
('','Irlandais'),
('','Islandais'),
('','Japonais'),
('','Japonais'),
('','Kazakh'),
('','Khmer'),
('','Kirghiz'),
('','Kirundi'),
('','Kiswahili'),
('','Kurde'),
('','Ladin'),
('','Langue des signes néo-zélandaise'),
('','Lao'),
('','Latin'),
('','Letton'),
('','Limbourgeois'),
('','Lituanien'),
('','Luxembourgeois'),
('','Macédonien'),
('','Malais'),
('','Maltais'),
('','Mandarin'),
('','Mannois'),
('','Maori'),
('','Maori des Îles Cook'),
('','Marshallais'),
('','Mirandais'),
('','Mongol'),
('','Monténégrin'),
('','Nauruan'),
('','Ndébélé'),
('','Néerlandais'),
('','Népalais'),
('','Norvégien'),
('','Occitan-Aranais'),
('','Ouïghour'),
('','Ourdou'),
('','Ouzbek'),
('','Pachto'),
('','Paluan'),
('','Persan'),
('','Polonais'),
('','Portugais'),
('','Quechua'),
('','Romanche'),
('','Roumain'),
('','Ruthène'),
('','Samoan'),
('','Sango'),
('','Sarde'),
('','Serbe'),
('','Sesotho'),
('','Shikomor'),
('','Shona'),
('','Sindebele'),
('','Slovaque'),
('','Slovène'),
('','Somali'),
('','Sorabe'),
('','Sotho du Nord'),
('','Sotho du Sud'),
('','Suédois'),
('','Swati'),
('','Tadjik'),
('','Tamoul'),
('','Tchèque'),
('','Tétoum'),
('','Thaï'),
('','Tibétain'),
('','Tigrinya'),
('','Tok Pisin'),
('','Tongien'),
('','Tsonga'),
('','Tswana'),
('','Turc'),
('','Turkmène'),
('','Tuvaluan'),
('','Ukrainien'),
('','Venda'),
('','Vietnamien'),
('','Xhosa'),
('','Zoulou')
");


?>
<script language='javascript' type='text/javascript'>
alert('La mise à jour des bases a été effectuée avec succès'); 
window.location.replace('../index.php');
</script>