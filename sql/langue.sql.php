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
('','Arm�nien'),
('','Aymara'),
('','Az�ri'),
('','Basque'),
('','Bengal�'),
('','Bichelamar'),
('','Bi�lorusse'),
('','Birman'),
('','Bulgare'),
('','Cantonais'),
('','Carolinien'),
('','Catalan'),
('','Chamorro'),
('','Chichewa'),
('','Chinois'),
('','Cinghalais'),
('','Cor�en'),
('','Cr�ole de Guin�e-Bissau'),
('','Cr�ole ha�tien'),
('','Cr�ole seychellois'),
('','Croate'),
('','Danois'),
('','Divehi'),
('','Dzongkha'),
('','Estonien'),
('','Fidjien'),
('','Filipino'),
('','Finnois'),
('','Fran�ais'),
('','Galicien'),
('','Gallois'),
('','G�orgien'),
('','Gilbertin'),
('','Grec'),
('','Guarani'),
('','Hatohabei'),
('','Hawaiien'),
('','H�breu'),
('','Hindi'),
('','Hindoustani'),
('','Hiri Motu'),
('','Hongrois'),
('','Iban'),
('','Indon�sien'),
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
('','Langue des signes n�o-z�landaise'),
('','Lao'),
('','Latin'),
('','Letton'),
('','Limbourgeois'),
('','Lituanien'),
('','Luxembourgeois'),
('','Mac�donien'),
('','Malais'),
('','Maltais'),
('','Mandarin'),
('','Mannois'),
('','Maori'),
('','Maori des �les Cook'),
('','Marshallais'),
('','Mirandais'),
('','Mongol'),
('','Mont�n�grin'),
('','Nauruan'),
('','Nd�b�l�'),
('','N�erlandais'),
('','N�palais'),
('','Norv�gien'),
('','Occitan-Aranais'),
('','Ou�ghour'),
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
('','Ruth�ne'),
('','Samoan'),
('','Sango'),
('','Sarde'),
('','Serbe'),
('','Sesotho'),
('','Shikomor'),
('','Shona'),
('','Sindebele'),
('','Slovaque'),
('','Slov�ne'),
('','Somali'),
('','Sorabe'),
('','Sotho du Nord'),
('','Sotho du Sud'),
('','Su�dois'),
('','Swati'),
('','Tadjik'),
('','Tamoul'),
('','Tch�que'),
('','T�toum'),
('','Tha�'),
('','Tib�tain'),
('','Tigrinya'),
('','Tok Pisin'),
('','Tongien'),
('','Tsonga'),
('','Tswana'),
('','Turc'),
('','Turkm�ne'),
('','Tuvaluan'),
('','Ukrainien'),
('','Venda'),
('','Vietnamien'),
('','Xhosa'),
('','Zoulou')
");


?>
<script language='javascript' type='text/javascript'>
alert('La mise � jour des bases a �t� effectu�e avec succ�s'); 
window.location.replace('../index.php');
</script>