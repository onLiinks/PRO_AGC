function ac_return(field, item){
	// on met en place l'expression régulière
	var regex = new RegExp('[0123456789]*-mini', 'i');
	// on l'applique au contenu
	var nomimage = regex.exec($(item).innerHTML);
	//on récupère l'id
	id = nomimage[0].replace('-mini', '');
	// et on l'affecte au champ caché
	$(field.name+'_id').value = id;
	// log
	$(field.name+'_log').innerHTML = '<br/>'+id+' - '+$F(field.name)+'<br/><img src="personne/'+id+'-mini.jpg" />';
}