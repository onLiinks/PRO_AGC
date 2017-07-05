var form_clean;
var allowPrompt = true;

function noPrompt() {
    allowPrompt = false;
}

document.observe("dom:loaded", function() {
    if($$("form.serialize"))
        if(typeof($$("form.serialize")[0])!='undefined') {
            form_clean = $$("form.serialize")[0].serialize(); 
        }
});


// compare clean and dirty form before leaving
window.onbeforeunload = function (e) {
    if(allowPrompt) {
        var form_dirty;
        if(typeof($$("form.serialize")[0])!='undefined') {
            form_dirty = $$("form.serialize")[0].serialize();
        }
        if(form_clean != form_dirty) {
            e = e || window.event;
            if (e) {
                e.returnValue = 'Des modifications ont �t� apport�es � la page. Voulez vous quitter la page sans sauvegarder ?';
            }
            return 'Des modifications ont �t� apport�es � la page. Voulez vous quitter la page sans sauvegarder ?';
        }
    }
    else {
        allowPrompt = true;
    }
};

/************************************************************************************************/
/******************************Fonctions qui ouvrent une popup*************************************/
/************************************************************************************************/
function verifierDC() {
    var form = $('formulaire');
    if(form['type_ressource'][0].value == '') {
        alert("Veuillez s�lectionner un type de ressource.");
        form['type_ressource'][0].focus();
        return false;    
    }
    if(form['Id_ressource'][0].value == '') {
        alert("Veuillez s�lectionner une ressource.");
        form['Id_ressource'][0].focus();
        return false;    
    }
    if(form['nouveau[]'][4][form['nouveau[]'][4].selectedIndex].text.startsWith('CDS') === true
        && form['nouveau[]'][5].value == '') {
        alert("Veuillez remplir le champ CDS (effectif).");
        form['nouveau[]'][5].focus();
        return false;
    }
    var nbInput = form['date_souhaite[]'].length;
    var i = 0;
    var today = new Date();
    while (i < nbInput) {
        if(form['nouveau[]'][i].value != '') {
            if(form['nouveau[]'][i].value == form['ancien[]'][i].value) {
                alert("La nouvelle valeur est identique � l'ancienne.");
                form['nouveau[]'][i].focus();
                return false;
            }
            var explodedDate = form['date_souhaite[]'][i].value.split('-');
            if(form['date_souhaite[]'][i].value == '') {
                alert("Veuillez indiquer la date souhait�e.");
                form['date_souhaite[]'][i].focus();
                return false;
            }
            else if($('buttonDC').pass !== true && new Date(explodedDate[2], explodedDate[1]-1, explodedDate[0]) <= today) {
                alert("Attention, vous avez saisi une date souhait�e ant�rieure � la date du jour.\nMerci de revalider la demande si cela n'est pas une erreur.");
                $('buttonDC').pass = true;
                return false;
            }
        }
        i++;
    }
    return true;
}

// Correction d'un bug Prototype sur IE9
Ajax.Request.prototype.respondToReadyState_orig = Ajax.Request.prototype.respondToReadyState;
Ajax.Request.prototype.respondToReadyState = function(readyState) {
  // Catch the exception, if there is one.
  try {
    this.respondToReadyState_orig(readyState);
  }
  catch(e) {
    this.dispatchException(e);
  }
};

Ajax.currentRequests = {};

Ajax.Responders.register({
    onCreate: function(request) {
        if($('loading') && Ajax.activeRequestCount>0){
            Effect.Appear('loading');
        }
        if (request.options.onlyLatestOfClass && Ajax.currentRequests[request.options.onlyLatestOfClass]) {
            // if a request of this class is already in progress, attempt to abort it before launching this new request
            try {
                Ajax.currentRequests[request.options.onlyLatestOfClass].transport.abort();
            } catch(e) {}
        }
        // keep note of this request object so we can cancel it if superceded
        Ajax.currentRequests[request.options.onlyLatestOfClass] = request;
    },
    onComplete: function(response) {
        if (response.options.onlyLatestOfClass) {
            // remove the request from our cache once completed so it can be garbage collected
            Ajax.currentRequests[response.options.onlyLatestOfClass] = null;
        }
        if($('loading') && Ajax.activeRequestCount==0) {
            Effect.Fade('loading');
        }
        if (403 == response.transport.status) {
            currentUrl = window.location;
            window.location = "../public/?error=1&url="+encodeURIComponent(currentUrl.pathname+currentUrl.search +currentUrl.hash);
        }
    },
    onException: function(response, ex) {
        if(Ajax.activeRequestCount - 1  >= 0) {
            Ajax.activeRequestCount--;
        }
        if($('loading') && Ajax.activeRequestCount==0) {
            Effect.Fade('loading');
        }
    }
});

Ajax.InPlaceCollectionEditor.prototype.__enterEditMode = Ajax.InPlaceCollectionEditor.prototype.enterEditMode;
Ajax.InPlaceEditor.prototype.__enterEditMode = Ajax.InPlaceEditor.prototype.enterEditMode;
Ajax.InPlaceCollectionEditor.prototype.__handleFormCancellation = Ajax.InPlaceCollectionEditor.prototype.handleFormCancellation;
Object.extend(Ajax.InPlaceCollectionEditor.prototype, {
    enterEditMode:function(e) {
        this.__enterEditMode(e);
        this.triggerCallback('onFormReady',this._form);
    }
});
Object.extend(Ajax.InPlaceEditor.prototype, {
    enterEditMode:function(e) {
        this.__enterEditMode(e);
        this.triggerCallback('onFormReady',this._form);
    }
});
Object.extend(Ajax.InPlaceCollectionEditor.prototype, {
    handleFormCancellation: function(e) {
        this.__handleFormCancellation(e);
        this.triggerCallback('onCancel');
    }
});

tinyMCE.init({
    mode : 'exact',
    elements : 'tinyarea1,tinyarea2,tinyarea3,tinyarea4,tinyarea5,tinyarea6,tinyarea7,tinyarea8,tinyarea9,tinyarea10,tinyarea11,tinyarea12,tinyarea20,tinyarea21,tinyarea22,tinyarea23',
    theme : 'advanced',
    width : '100%',
    plugins : 'layer,table,save,advlink,inlinepopups,preview,searchreplace,print,contextmenu,paste,noneditable,nonbreaking,pagebreak,fullscreen',
    theme_advanced_buttons3_add : 'print,fullscreen',
    theme_advanced_buttons4 : 'fontselect,fontsizeselect',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resize_horizontal : true,
    theme_advanced_resizing : true,
    apply_source_formatting : true,
    spellchecker_languages : '+English=en,French=fr'
})

function getFunctionName(name) {
    name = name.substr('function '.length);
    name = name.substr(0, name.indexOf('('));
    return name;
}

function showInformationMessage(message, duration) {
    $('msg').update(message);
    Effect.toggle('msg', 'appear');
    setTimeout("Effect.toggle('msg', 'appear');", duration);
    setTimeout("$('msg').update('');", duration + 1000);
}

function deplie(i)
{
    nouv = document.getElementById("i"+i).src.split("/");
    if (nouv[nouv.length-1] == "plus.gif")
    {
        nouv[nouv.length-1] = "moins.gif";
        document.getElementById("i"+i).alt="-";
    }
    else
    {
        nouv[nouv.length-1] = "plus.gif";
        document.getElementById("i"+i).alt="+";
    }
    compteur = 0;
    test = "";
    while (compteur < nouv.length)
    {
        test  += nouv[compteur];
        if (compteur != nouv.length-1)
        {
            test += "/";
        }
        ++compteur;
    }
    document.getElementById("i"+i).src = test;
    $("m"+i).toggle();
}

function deroule(i) 
{
    new Effect.toggle(i,"blind");
    if(document.getElementById("img"+i).src =="http://localhost/AGC/ui/images/plus.gif") {
        document.getElementById("img"+i).src="http://localhost/AGC/ui/images/moins.gif";
        return;
    }
    if(document.getElementById("img"+i).src =="http://localhost/AGC/ui/images/moins.gif") {
        document.getElementById("img"+i).src="http://localhost/AGC/ui/images/plus.gif";
        new Effect.toggle(i,"blind");
        return;
    }
}

function disable_sup(i)
{
    var id = i.split('-');
    var taille = id.length;

    if (taille == 3)//dans le cas d\'un d�partement, on d�s�lectionne aussi la r�gion
    {
        document.getElementById("r"+id[0]+"-"+id[1]).checked = false;
        document.getElementById("r"+id[0]).checked = false;
        var containCheck = false;
        $$('#m' + id[0] + '-' + id[1] + ' input[type=checkbox]').each(function(e) {
            if(e.checked == true)
                containCheck = true;
        });
        if(containCheck)
            document.getElementById("r"+id[0]+"-"+id[1]).up().setStyle({
                fontWeight: 'bold'
            });
        else
            document.getElementById("r"+id[0]+"-"+id[1]).up().setStyle({
                fontWeight: ''
            });

        containCheck = false;
        $$('#m' + id[0] + ' input[type=checkbox]').each(function(e) {
            if(e.checked == true)
                containCheck = true;
        });
        if(containCheck)
            document.getElementById("r"+id[0]).up().setStyle({
                fontWeight: 'bold'
            });
        else
            document.getElementById("r"+id[0]).up().setStyle({
                fontWeight: ''
            });
    }
    else if (taille == 2) {
        document.getElementById("r"+id[0]).checked = false;
        if(document.getElementById("r"+id[0]+"-"+id[1]).checked) {
            //$$('#m' + id[0] + '-' + id[1] + ' input[type=checkbox]').invoke('disable');
            $$('#m' + id[0] + '-' + id[1] + ' input[type=checkbox]').each(function(e) {
                e.checked = false;
                e.up().setStyle({
                    fontWeight: ''
                });
            });
        }
        else {
            $$('#m' + id[0] + '-' + id[1] + ' input[type=checkbox]').invoke('enable');
        }
        var containCheck = false;
        $$('#m' + id[0] + '-' + id[1] + ' input[type=checkbox]').each(function(e) {
            if(e.checked == true)
                containCheck = true;
        });
        if(containCheck)
            document.getElementById("r"+id[0]+"-"+id[1]).up().setStyle({
                fontWeight: 'bold'
            });
        else
            document.getElementById("r"+id[0]+"-"+id[1]).up().setStyle({
                fontWeight: ''
            });

        containCheck = false;
        $$('#m' + id[0] + ' input[type=checkbox]').each(function(e) {
            if(e.checked == true)
                containCheck = true;
        });
        if(containCheck)
            document.getElementById("r"+id[0]).up().setStyle({
                fontWeight: 'bold'
            });
        else
            document.getElementById("r"+id[0]).up().setStyle({
                fontWeight: ''
            });
    }
    else if (taille == 1) {
        if(document.getElementById("r"+id[0]).checked) {
            //$$('#m' + id[0] + ' input[type=checkbox]').invoke('disable');
            $$('#m' + id[0] + ' input[type=checkbox]').each(function(e) {
                e.checked = false;
                e.up().setStyle({
                    fontWeight: ''
                });
            });
        }
        else {
            $$('#m' + id[0] + ' input[type=checkbox]').invoke('enable');
        }
    }
    
    
    if(document.getElementById("r"+i).checked)
        document.getElementById("r"+i).up().setStyle({
            fontWeight: 'bold'
        });
    else
        document.getElementById("r"+i).up().setStyle({
            fontWeight: ''
        });
}

/**
* Fonction qui permet d'ouvrir une url en popup
*
*/
function ouvre_popup(url,width,height,scrollbar) 
{
    width = typeof(width) != 'undefined' ? width : screen.width;
    height = typeof(height) != 'undefined' ? height : screen.height;
    scrollbar = typeof(scrollbar) != 'undefined' ? scrollbar : 'yes';
    propriete = 'top=0,left=0,resizable='+scrollbar+', toolbar='+scrollbar+', scrollbars='+scrollbar+', menubar='+scrollbar+', location=no, statusbar=no'
    propriete += ',width=' + width + ',height=' + height;
    window.open(url,'fenetre', propriete);

}

/**
* Fonction qui permet de r�initialiser un champ de formulaire
*/
function clearDefault(el) 
{
    if (el.defaultValue==el.value) el.value = ''
}

/**
* Fonction qui s'occupe de griser un select en fonction du checkbox associ�. Prend en argument respectivement la checkbox puis le champs � griser
*/
function disableComboBox(id1,id2)
{
    document.getElementById(id2).disabled = !(document.getElementById(id1).checked);
}

/**
* Verification du formulaire de saisie des indentifiants de connexion � l'application
*/
function verifConnexion(form)
{
    if(form.Id_societe.value == '') {
        alert("Veuillez s�lectionner une base de donn�es.");
        form.Id_societe.focus();
        return false;
    }
    if(form.login.value == '' || form.pass.value == '') {
        alert("Veuillez remplir le login et le mot de passe.");
        form.login.focus();
        return false;
    }
    new Effect.Fold('authent');
}

/**
* Cette fonction permet de r�initialiser les valeurs d'un formulaire de recherche
*/
function initForm(classe)
{
    var url    = '../source/index.php?a=initForm';
    var pars   = '&c='+classe;
    var target = 'filtre';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        evalScripts: true,
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

function initSearchForm(func) {
    $$('#filtre input[type="text"]', '#filtre select', '#filtre input[type="checkbox"]').each(
        function(e) {
            if(e.type == 'checkbox') {
                e.checked = false;
            }
            else if(e.type=='select-multiple') {
                for (var i = 0; i < e.options.length; i++) {
                    e.options[i].selected = false;
                }
            }
            else {
                e.value = '';
            }
            func();
        }
        );
}

/**
* Verification du formulaire de saisie d'un candidat
*/
function verifForm(form)
{
    if(form.date.value == '') {
        alert("Veuillez saisir une date de candidature.");
        form.date.focus();
        return false;
    }
    if(form.nature.value == '') {
        alert("Veuillez s�lectionner une nature pour la candidature.");
        form.nature.focus();
        return false;
    }
    if(form.etat.value == '') {
        alert("Veuillez s�lectionner un �tat pour la candidature.");
        form.etat.focus();
        return false;
    }
    if(form.nom_ressource.value == '') {
        alert("Veuillez saisir un nom pour le candidat.");
        form.nom_ressource.focus();
        return false;
    }
    if(form.prenom_ressource.value == '') {
        alert("Veuillez saisir un pr�nom pour le candidat.");
        form.prenom_ressource.focus();
        return false;
    }
    if(form.tel_fixe.value == '' && form.tel_portable.value == '') {
        alert("Veuillez saisir un num�ro de t�l�phone.");
        form.tel_fixe.focus();
        return false;
    }
    if(form.mail.value == '') {
        alert("Veuillez saisir une adresse �lectonique.");
        form.mail.focus();
        return false;
    }
    if(form.profil.value == '') {
        alert("Veuillez s�lectionner un profil.");
        form.profil.focus();
        return false;
    }
    if(form.Id_specialite.value == '') {
        alert("Veuillez s�lectionner une sp�cialit�.");
        form.profil.focus();
        return false;
    }
    if(form.Id_cursus.value == '') {
        alert("Veuillez s�lectionner un cursus.");
        form.Id_cursus.focus();
        return false;
    }
    if(form.profil.value != 25) {
        if(form.Id_exp_info.value == '') {
            alert("Veuillez s�lectionner une exp�rience informatique.");
            form.Id_exp_info.focus();
            return false;
        }
    }
    if(form.cv.value == '' && form.cv_actuel.value == '') {
        alert("Veuillez joindre un CV.");
        form.cv.focus();
        return false;
    }
    if(form.agence_souhaitee.value == '') {
        alert("Veuillez s�lectionner une agence de rattachement.");
        form.agence_souhaitee.focus();
        return false;
    }
    if(form.etat.value == 8 || form.etat.value == 9 || form.etat.value == 22) {
        if(!form.civilite_ressource[0].checked && !form.civilite_ressource[1].checked
            && !form.civilite_ressource[2].checked) {
            alert("Veuillez s�lectionner une civilit�.");
            form.civilite_ressource[0].focus();
            return false;
        }
        if ( !document.formulaire.type_embauche_etat[0].checked && !document.formulaire.type_embauche_etat[1].checked ) {
            alert("Veuillez indiquer le type d'embauche.");
            form.type_embauche_etat[0].focus();
            return false;
        }
        if(!form.th[0].checked && !form.th[1].checked) {
            alert("Veuillez sp�cifier s'il s'agit d'un travailleur handicap�.");
            form.th[0].focus();
            return false;
        }
        if(form.Id_nationalite.value == '') {
            alert("Veuillez s�lectionner une nationalit�.");
            form.Id_nationalite.focus();
            return false;
        }
        if(form.adresse.value == '') {
            alert("Veuillez saisir une adresse.");
            form.adresse.focus();
            return false;
        }
        if (document.formulaire.civilite_ressource[1].checked) {
            if(form.nom_jeune_fille.value == '') {
                alert("Veuillez indiquer le nom de jeune fille.");
                form.nom_jeune_fille.focus();
                return false; 
            }
        }
        if(form.date_naiss.value == '00-00-0000' || form.date_naiss.value == '') {
            alert("Veuillez saisir une date de naissance correcte.");
            form.date_naiss.focus();
            return false;
        }
        if(form.ville_naiss.value == '') {
            alert("Veuillez saisir un lieu de naissance.");
            form.ville_naiss.focus();
            return false;
        }
        if(form.Id_pays_naiss.value == '') {
            alert("Veuillez saisir un pays de naissance.");
            form.Id_pays_naiss.focus();
            return false;
        }
        if(form.Id_pays_naiss.value == 72) {
            if(form.Id_dpt_naiss.value == '') {
                alert("Veuillez saisir un d�partement de naissance.");
                form.Id_dpt_naiss.focus();
                return false;
            }
        }
        if ( !document.formulaire.type_embauche[0].checked && !document.formulaire.type_embauche[1].checked 
            && !document.formulaire.type_embauche[2].checked && !document.formulaire.type_embauche[3].checked
            && !document.formulaire.type_embauche[4].checked) {
            alert("Veuillez indiquer le type de contrat du candidat.");
            form.type_embauche[0].focus();
            return false;
        }
        if (document.formulaire.type_embauche[0].checked || document.formulaire.type_embauche[2].checked || 
            document.formulaire.type_embauche[3].checked || document.formulaire.type_embauche[4].checked) {
            if(form.fin_cdd.value == '00-00-0000' || form.fin_cdd.value == '') {
                alert("Veuillez indiquer une date de fin de contrat.");
                form.fin_cdd.focus();
                return false;
            }
        }
        if ( !document.formulaire.statut_ressource[0].checked && !document.formulaire.statut_ressource[1].checked ) {
            alert("Veuillez indiquer le statut du candidat.");
            form.statut_ressource[0].focus();
            return false;
        }

        var nSecu = form.num_ss.value.replace(/ /g, '').replace(/\./g, '').replace(/-/g, '');
        if(nSecu.length < 15) {
            alert("Veuillez saisir un n� de s�curit� sociale correct.");
            form.num_ss.focus();
            return false;
        }
        else {
            var clefSecu = nSecu.substr(13, 15);
            var nSecu = nSecu.substr(0, 13);
            var sexe = nSecu.substr(0, 1);
            var annee = nSecu.substr(1, 2);
            var mois = nSecu.substr(3, 2);
            if(form.Id_dpt_naiss.value >= 970 && form.Id_dpt_naiss.value <= 989) {
                var dept = nSecu.substr(5, 3);
                var commune = nSecu.substr(8, 2);
            }
            else {
                var dept = nSecu.substr(5, 2);
                var commune = nSecu.substr(7, 3);
            }
            var ordre = nSecu.substr(10, 3);
            var clefCalc = 97 - (nSecu.replace(/A/g, '0').replace(/B/g, '0') % 97);
            if((form.civilite_ressource[0].checked || form.civilite_ressource[1].checked)
                && (sexe != 2 && sexe != 8)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, pour une femme le premier chiffre doit �tre 2 ou 8.");
                form.num_ss.focus();
                return false;
            }
            else if(form.civilite_ressource[2].checked && (sexe != 1 && sexe != 7)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, pour un homme le premier chiffre doit �tre 1 ou 7.");
                form.num_ss.focus();
                return false;
            }
            if(annee != form.date_naiss.value.substr(8, 2)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la seconde partie doit correspondre � l'ann�e de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(mois != form.date_naiss.value.substr(3, 2) && mois != 20) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la troisi�me partie doit correspondre au mois de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(dept != form.Id_dpt_naiss.value && dept != 99) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la quatri�me partie doit correspondre au d�partement de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(clefSecu != clefCalc) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la cl� de v�rrification ne correspond pas.");
                form.num_ss.focus();
                return false;
            }
        }
        
        if(form.Id_service.value == '') {
            alert("Veuillez selectionner un service.");
            form.Id_service.focus();
            return false;
        }
        if(form.Id_agence.value == '') {
            alert("Veuillez selectionner une agence.");
            form.Id_agence.focus();
            return false;
        }
        if(form.Id_profil_cegid.value == '') {
            alert("Veuillez selectionner une un profil d'embauche.");
            form.Id_profil_cegid.focus();
            return false;
        }
        if(form.date_embauche.value == '00-00-0000' || form.date_embauche.value == '') {
            alert("Veuillez saisir une date d'embauche correcte.");
            form.date_embauche.focus();
            return false;
        }
        if(form.salaire.value == '' || form.salaire.value == 0) {
            alert("Veuillez saisir un salaire.");
            form.salaire.focus();
            return false;
        }
    }
    return true;
}

/**
* Verification du formulaire de saisie d'un candidat
*/
function verifFormOvialis(form) {
    if(form.etat.value == '') {
        alert("Veuillez s�lectionner un �tat pour la candidature.");
        form.etat.focus();
        return false;
    }
    if(form.nom_ressource.value == '') {
        alert("Veuillez saisir un nom pour le candidat.");
        form.nom_ressource.focus();
        return false;
    }
    if(form.prenom_ressource.value == '') {
        alert("Veuillez saisir un pr�nom pour le candidat.");
        form.prenom_ressource.focus();
        return false;
    }
    if(form.tel_fixe.value == '' && form.tel_portable.value == '') {
        alert("Veuillez saisir un num�ro de t�l�phone.");
        form.tel_fixe.focus();
        return false;
    }
    if(form.profil.value == '') {
        alert("Veuillez s�lectionner un profil.");
        form.profil.focus();
        return false;
    }
    if(form.etat.value == 8 || form.etat.value == 9 || form.etat.value == 22) {
        if(!form.civilite_ressource[0].checked && !form.civilite_ressource[1].checked
            && !form.civilite_ressource[2].checked) {
            alert("Veuillez s�lectionner une civilit�.");
            form.civilite_ressource[0].focus();
            return false;
        }
        if(!form.th[0].checked && !form.th[1].checked) {
            alert("Veuillez sp�cifier s'il s'agit d'un travailleur handicap�.");
            form.th[0].focus();
            return false;
        }
        if(form.Id_nationalite.value == '') {
            alert("Veuillez s�lectionner une nationalit�.");
            form.Id_nationalite.focus();
            return false;
        }
        if(form.adresse.value == '') {
            alert("Veuillez saisir une adresse.");
            form.adresse.focus();
            return false;
        }
        if (document.formulaire.civilite_ressource[1].checked) {
            if(form.nom_jeune_fille.value == '') {
                alert("Veuillez indiquer le nom de jeune fille.");
                form.nom_jeune_fille.focus();
                return false; 
            }
        }
        if(form.date_naiss.value == '00-00-0000' || form.date_naiss.value == '') {
            alert("Veuillez saisir une date de naissance correcte.");
            form.date_naiss.focus();
            return false;
        }
        if(form.ville_naiss.value == '') {
            alert("Veuillez saisir un lieu de naissance.");
            form.ville_naiss.focus();
            return false;
        }
        if(form.Id_pays_naiss.value == '') {
            alert("Veuillez saisir un pays de naissance.");
            form.Id_pays_naiss.focus();
            return false;
        }
        if(form.Id_pays_naiss.value == 72) {
            if(form.Id_dpt_naiss.value == '') {
                alert("Veuillez saisir un d�partement de naissance.");
                form.Id_dpt_naiss.focus();
                return false;
            }
        }
        if ( !document.formulaire.type_embauche[0].checked && !document.formulaire.type_embauche[1].checked 
            && !document.formulaire.type_embauche[2].checked && !document.formulaire.type_embauche[3].checked) {
            alert("Veuillez indiquer le type de contrat du candidat.");
            form.type_embauche[0].focus();
            return false;
        }
        if (document.formulaire.type_embauche[0].checked || document.formulaire.type_embauche[2].checked || 
            document.formulaire.type_embauche[3].checked || document.formulaire.type_embauche[4].checked) {
            if(form.fin_cdd.value == '00-00-0000' || form.fin_cdd.value == '') {
                alert("Veuillez indiquer une date de fin de contrat.");
                form.fin_cdd.focus();
                return false;
            }
        }
        if ( !document.formulaire.statut_ressource[0].checked && !document.formulaire.statut_ressource[1].checked ) {
            alert("Veuillez indiquer le statut du candidat.");
            form.statut_ressource[0].focus();
            return false;
        }

        var nSecu = form.num_ss.value.replace(/ /g, '').replace(/\./g, '').replace(/-/g, '');
        if(nSecu.length < 15) {
            alert("Veuillez saisir un n� de s�curit� sociale correct.");
            form.num_ss.focus();
            return false;
        }
        else {
            var clefSecu = nSecu.substr(13, 15);
            var nSecu = nSecu.substr(0, 13);
            var sexe = nSecu.substr(0, 1);
            var annee = nSecu.substr(1, 2);
            var mois = nSecu.substr(3, 2);
            if(form.Id_dpt_naiss.value >= 970 && form.Id_dpt_naiss.value <= 989) {
                var dept = nSecu.substr(5, 3);
                var commune = nSecu.substr(8, 2);
            }
            else {
                var dept = nSecu.substr(5, 2);
                var commune = nSecu.substr(7, 3);
            }
            var ordre = nSecu.substr(10, 3);
            var clefCalc = 97 - (nSecu.replace(/A/g, '0').replace(/B/g, '0') % 97);
            if((form.civilite_ressource[0].checked || form.civilite_ressource[1].checked)
                && (sexe != 2 && sexe != 8)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, pour une femme le premier chiffre doit �tre 2 ou 8.");
                form.num_ss.focus();
                return false;
            }
            else if(form.civilite_ressource[2].checked && (sexe != 1 && sexe != 7)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, pour un homme le premier chiffre doit �tre 1 ou 7.");
                form.num_ss.focus();
                return false;
            }
            if(annee != form.date_naiss.value.substr(8, 2)) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la seconde partie doit correspondre � l'ann�e de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(mois != form.date_naiss.value.substr(3, 2) && mois != 20) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la troisi�me partie doit correspondre au mois de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(dept != form.Id_dpt_naiss.value && dept != 99) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la quatri�me partie doit correspondre au d�partement de naissance.");
                form.num_ss.focus();
                return false;
            }
            if(clefSecu != clefCalc) {
                alert("Veuillez v�rifier le n� de s�curit� sociale, la cl� de v�rrification ne correspond pas.");
                form.num_ss.focus();
                return false;
            }
        }
        
        if(form.Id_service.value == '') {
            alert("Veuillez selectionner un service.");
            form.Id_service.focus();
            return false;
        }
        if(form.Id_agence.value == '') {
            alert("Veuillez selectionner une agence.");
            form.Id_agence.focus();
            return false;
        }
        if(form.date_embauche.value == '00-00-0000' || form.date_embauche.value == '') {
            alert("Veuillez saisir une date d'embauche correcte.");
            form.date_embauche.focus();
            return false;
        }
        if(form.salaire.value == '' || form.salaire.value == 0) {
            alert("Veuillez saisir un salaire.");
            form.salaire.focus();
            return false;
        }
    }
    return true;
}

/**
* Verification du formulaire de saisie d'une fiche d'entretien d'un candidat
*/
function verifEntretien(form)
{
    if(form.Id_recruteur.value == '') {
        alert("Veuillez s�lectionner un charg� de recrutement.");
        form.Id_recruteur.focus();
        return false;
    }
    if(form.Id_preavis.value == '' && form.date_disponibilite.value == '') {
        alert("Veuillez saisir une date de disponibilit� ou s�lectionner un pr�avis.");
        form.Id_preavis.focus();
        return false;
    }
    TabChecks=document.getElementsByName('region_souhaitee[]');
    var nbChecked = 0;
	
    for(i=0;i<TabChecks.length;i++) {
        if(document.getElementsByName("region_souhaitee[]")[i].checked == true) {
            nbChecked++;
        }
    }
    if(nbChecked == 0) {
        alert("Veuillez s�lectionner une mobilit�.");
        return false;
    }
    if(form.mot_cle.value == '') {
        alert("Veuillez saisir un ou plusieurs mots-cl�s.");
        form.mot_cle.focus();
        return false;
    }
    if(!document.getElementById('tarif_journalier') && !document.getElementById('indemnite_stage')) {
        if(form.pretention_basse.value < 10 || form.pretention_haute.value > 100) {
            alert("Veuillez saisir des pr�tentions salariales correctes en K�.");
            form.pretention_basse.focus();
            return false;
        }
    }
    return true;
}


/**
* Verification du formulaire de saisie d'un contrat d�l�gation
*/
function verifCD(form) {
    if($('Id_affaire')) {
        if($('Id_affaire').value == ''){
            alert("Veuillez s�lectionner une opportunit�.");
            $('Id_affaire').focus();
            return false;
        }
    }
    
    if($('type_ressource')) {
        if($('type_ressource').value == ''){
            alert("Veuillez s�lectionner un type de ressource.");
            $('type_ressource').focus();
            return false;
        }
    }
    
    if(form.adresse_facturation) {
        if(form.adresse_facturation.value == '') {
            alert("Veuillez saisir une adresse de facturation.");
            form.adresse_facturation.focus();
            return false;
        }
    }
    else {
        alert("Veuillez s�lectionner une opportunit�.");
        $('Id_affaire').focus();
        return false;
    }
    
    if(form.contact_principal) {
        if(form.contact_principal.value == '') {
            alert("Veuillez saisir un contact principal.");
            form.contact_principal.focus();
            return false;
        }
    }
    else {
        alert("Veuillez s�lectionner une opportunit�.");
        $('Id_affaire').focus();
        return false;
    }

    if(form.fonction_cprincipal) {
        if(form.fonction_cprincipal.value == '') {
            alert("Veuillez saisir la fonction du contact principal.");
            form.fonction_cprincipal.focus();
            return false;
        }
    }
    else {
        alert("Veuillez s�lectionner une opportunit�.");
        $('Id_affaire').focus();
        return false;
    }
    
    if(document.formulaire.materiel[1].checked) {
        type_mission_check = false;
        if(document.formulaire.type_mission) {
            if(document.formulaire.type_mission) {
                if(document.formulaire.type_mission.checked) {
                    type_mission_check = true;
                }
            }
            if(document.formulaire.type_mission[0]) {
                if(document.formulaire.type_mission[0].checked) {
                    type_mission_check = true;
                }
            }
            if(document.formulaire.type_mission[1]) {
                if(document.formulaire.type_mission[1].checked) {
                    type_mission_check = true;
                }
            }
            if(document.formulaire.type_mission[2]) {
                if(document.formulaire.type_mission[2].checked) {
                    type_mission_check = true;
                }
            }
            if(type_mission_check == false) {
                alert("Veuillez s�lectionner le type de mission.");
                return false;
            }
        }
        else {
            alert("Veuillez s�lectionner une opportunit�.");
            $('Id_affaire').focus();
            return false;
        }
        
        if(form.indemnites_ref) {
            if(form.indemnites_ref.value == '') {
                alert("Veuillez indiquer s'il y a des indemnit�s � refacturer.");
                form.indemnites_ref.focus();
                return false;
            }
        }
        else {
            alert("Veuillez s�lectionner une opportunit�.");
            $('Id_affaire').focus();
            return false;
        }

        if( !document.formulaire.nature_mission[0].checked && !document.formulaire.nature_mission[1].checked ) {
            alert("Veuillez s�lectionner la nature de la mission.");
            return false;
        }
        if( !document.formulaire.remplacement[0].checked && !document.formulaire.remplacement[1].checked ) {
            alert("Veuillez indiquer s'il s'agit d'un remplacement.");
            return false;
        }
    }
    if(form.cout_journalier.value == '') {
        alert("Veuillez saisir un montant.");
        form.cout_journalier.focus();
        return false;
    }
    
    if(form.date_debut.value == '') {
        alert("Veuillez saisir une date de d�but de mission.");
        form.date_debut.focus();
        return false;
    }
    else if(form.date_min.value !== '') {
        var j = (form.date_min.value.substring(0,2));
        var m = (form.date_min.value.substring(3,5));
        var a = (form.date_min.value.substring(6));
        var date_min = new Date(a, m, j);
        
        j = (form.date_debut.value.substring(0,2));
        m = (form.date_debut.value.substring(3,5));
        a = (form.date_debut.value.substring(6));
        date_debut = new Date(a, m, j);
        if(date_debut < date_min) {
            alert("Veuillez saisir une date de d�but de mission dans la fourchette de date des devis/opportunit�s.");
            form.date_debut.focus();
            return false;
        }
    }

    if(form.date_fin_commande.value == '') {
        alert("Veuillez saisir une date de fin de mission.");
        form.date_fin_commande.focus();
        return false;
    }
    else if(form.date_max.value !== '') {
        var j = (form.date_max.value.substring(0,2));
        var m = (form.date_max.value.substring(3,5));
        var a = (form.date_max.value.substring(6));
        var date_max = new Date(a, m, j);
        
        j = (form.date_fin_commande.value.substring(0,2));
        m = (form.date_fin_commande.value.substring(3,5));
        a = (form.date_fin_commande.value.substring(6));
        date_fin = new Date(a, m, j);
        if(date_fin > date_max) {
            alert("Veuillez saisir une date de fin de mission dans la fourchette de date des devis/opportunit�s.");
            form.date_fin_commande.focus();
            return false;
        }
    }
    
    if(!(date_debut <= date_fin)) {
        alert("La date de d�but doit �tre inf�rieur � la date de fin.");
        form.date_fin_commande.focus();
        return false;
    }
    
    if(form.duree.value == '') {
        alert("Veuillez saisir une dur�e de mission.");
        form.duree.focus();
        return false;
    }
    if(form.lieu_mission.value == '') {
        alert("Merci d'indiquer le lieu exact (ville) de mission du collaborateur ; cette information conditionne le paiement d'une cotisation patronale et impacte donc directement le co�t salarial.");
        form.lieu_mission.focus();
        return false;
    }
    if(document.formulaire.materiel[1].checked) {
        if(form.Id_ressource.value == '') {
            alert("Veuillez devez s�lectionner une ressource.");
            form.Id_ressource.focus();
            return false;
        }
        else {
            //on ne bloque pas si interimaire
            if ($('infoRessource').select('span[data-nom="type_embauche"]').innerHtml == 'Int�rimaire') {
                var errorRessource = false;
                var fields = "";
                $('infoRessource').select('span[data-nom]:empty').each(
                    function (e) {
                        fields += e.getAttribute('data-libelle') + ', ';
                        e.previous().writeAttribute('class', 'error');
                        errorRessource = true;
                    }
                );
                
                $('infoRessource').select('span[data-nom=securite_sociale]').each(
                    function (e) {
                        if (e.innerHTML.match(/([1-4])[ \s\.\-]?([0-9]{2})[ \s\.\-]?([0-9]{2})[ \s\.\-]?([0-9]{2}|2[AB])[ \s\.\-]?([0-9]{3})[ \s\.\-]?([0-9]{3})[ \s\.\-]?([0-9]{2})$/g) == null) {
							alert("Num�ro de s�curit� sociale non valide.");
							return false;
						}
                   }
                );
                
                if (errorRessource === true) {
                    alert("Il manque les informations suivantes sur la ressource : " + fields + "\n\nMerci de contacter votre charg� de recrutement afin de remplir les informations de Taleo.");
                    return false;
                }
            }
            
        }
        if(form.horaire.value == '') {
            alert("Veuillez s�lectionner un horaire.");
            form.horaire.focus();
            return false;
        }
        if(form.indemnite_destination.value == '' || (form.indemnite_region.value == '' && form.indemnite_destination.value != 'field')) {
            alert("Veuillez s�lectionner des indemnit�s.");
            form.indemnite_destination.focus();
            return false;
        }
        if(!document.formulaire.astreinte[0].checked && !document.formulaire.astreinte[1].checked) {
            alert("Veuillez indiquer s'il y a des astreintes.");
            return false;
        }
        if(form.st_nom.value != '' && form.st_tarif.value == '') {
            alert("Veuillez devez saisir un tarif journalier pour le sous-traitant.");
            form.st_tarif.focus();
            return false;
        }
    }
    return true;
}

/**
* Verification du formulaire de saisie d'un contrat d�l�gation hors affaire
*/
function verifCDHA(form) {
    if(form.Id_ressource.value == '') {
        alert("Veuillez s�lectionner une ressource ou saisir un sous-traitant.");
        form.Id_ressource.focus();
        return false;
    }
    else {
        var errorRessource = false;
        var fields = "";
        $('infoRessource').select('span[data-nom]:empty').each(
            function (e) {
                console.log(e);
                fields += e.getAttribute('data-libelle') + ', ';
                e.previous().writeAttribute('class', 'error');
                errorRessource = true;
            }
        );
        if (errorRessource === true) {
            alert("Il manque les informations suivantes sur la ressource : " + fields + "\n\nMerci de contacter votre charg� de recrutement afin de remplir les informations de Taleo.");
            return false;
        }
    }
    /*if(form.moyen_acces.value == '') {
        alert("Veuillez saisir le moyen d'acc�s utilis�.");
        form.moyen_acces.focus();
        return false;
    }*/
    if(form.type_indemnite.value == '') {
        alert("Veuillez saisir les indemnit�s.");
        form.type_indemnite.focus();
        return false;
    }
    
    if ( !document.formulaire.astreinte[0].checked && !document.formulaire.astreinte[1].checked ) {
        alert("Veuillez indiquer s'il y a des astreintes.");
        return false;
    }
    return true;
}

/**
* Verification du formulaire de saisie d'une affaire
*/
function verifAffaire(form)
{
    if(form.Id_pole.value == '') {
        alert("Veuillez s�lectionner un p�le.");
        form.Id_pole.focus();
        return false;
        }
    if(form.Id_agence.value == '') {
        alert("Veuillez s�lectionner une agence.");
        form.Id_agence.focus();
        return false;
    }
    if(form.Id_statut.value == '') {
        alert("Veuillez s�lectionner le statut de l'affaire.");
        form.Id_statut.focus();
        return false;
    }
    if(form.Id_compte.value == '') {
        alert("Veuillez s�lectionner un compte.");
        form.Id_compte.focus();
        return false;
    }
    if(form.Id_contact1.value == '' && form.Id_contact2.value == '') {
        alert("Veuillez s�lectionner un contact.");
        form.Id_contact1.focus();
        return false;
    }
    if(form.Id_statut.value == 6) {
        if(form.Id_raison_perdue.value == '') {
            alert("Veuillez s�lectionner une raison pour le statut \"perdue\".");
            form.Id_raison_perdue.focus();
            return false;
        }
    }

    //Si c'est une affaire du pole formation
    if(form.Id_pole.value == 3) {
        // Si l'affaire est ecr
        if(form.Id_statut.value == 3) {
            if(form.date_demande.value == '00-00-0000' || form.date_demande.value == '') {
                alert("Veuillez saisir une date de demande client.");
                form.date_demande.focus();
                return false;
            }
        }
        // Si l'affaire est remise
        if(form.Id_statut.value == 4) {
            if(form.date_remise.value == '00-00-0000' || form.date_remise.value == '') {
                alert("Veuillez saisir une date de remise.");
                form.date_remise.focus();
                return false;
            }
        }
        //une session et un bon de commande doivent �tre associ�s � l'affaire pour les statuts op�rationnel et termin�
        if(form.Id_statut.value == 8 || form.Id_statut.value == 9) {
            if (form.Id_session.value == ' '){
                alert("Veuillez s�lectionner la session associ�e � l'affaire");
                return false;
            }
        }
        //D�blocage des champs de l'affaire provenant de la session
        if ( form.Id_session.value !=' '){
            form.cout_total.disabled        = false;
            form.marge_totale.disabled      = false;
            form.date_debut.disabled        = false;
            form.date_fin_commande.disabled = false;
            form.duree.disabled             = false;
            form.Id_intitule.disabled       = false;
            form.ville.disabled             = false;
            form.cp.disabled                = false;
        }
    } else {
        // Si l'affaire est ecr, remise, sign�e, op�rationnelle ou termin�es
        if(form.Id_statut.value == 3 || form.Id_statut.value == 4 || form.Id_statut.value == 5 || form.Id_statut.value == 8 || form.Id_statut.value == 9) {
            if(form.date_debut.value == '00-00-0000' || form.date_debut.value == '') {
                alert("Veuillez saisir une date de d�but.");
                form.date_debut.focus();
                return false;
            }
            if(form.date_fin_commande.value == '00-00-0000' || form.date_fin_commande.value == '') {
                alert("Veuillez saisir une date de fin de commande.");
                form.date_fin_commande.focus();
                return false;
            }
        }
		
        // Si l'affaire est sign�e ou op�rationnelle
        if(form.Id_statut.value == 5 || form.Id_statut.value == 8) {
            if(form.Id_type_contrat.value == 2) {
                if ( !document.formulaire.typeInfog[0].checked && !document.formulaire.typeInfog[1].checked && !document.formulaire.typeInfog[2].checked) {
                    alert("Veuillez indiquer s'il s'agit d'une Infog�rance sur site, � distance ou coupl�e.");
                    return false;
                }
            }
            if(form.Id_type_contrat.value == 1 ) {
                if ( !document.formulaire.typeAT[0].checked && !document.formulaire.typeAT[1].checked ) {
                    alert("Veuillez indiquer s'il s'agit d'une AT de type R�gie ou Forfaitis�e");
                    return false;
                }
            }
        }

        // Si l'affaire est remise, sign�e ou op�rationnelle
        if(form.Id_statut.value == 4 || form.Id_statut.value == 5 || form.Id_statut.value == 8) {
            if(form.chiffre_affaire.value == '' || form.chiffre_affaire.value == 0) {
                alert("Veuillez saisir un chiffre d\'affaire total.");
                form.chiffre_affaire.focus();
                return false;
            }
        }
    }
    
    return true;
}

/**
* Verification du formulaire de saisie d'un ordre de mission
*/
function verifODM(form)
{
    if(form.Id_ressource.value == '') {
        alert("Veuillez s�lectionner une ressource.");
        form.Id_ressource.focus();
        return false;
    }
    if(form.Id_compte) {
        if(form.Id_compte.value == '') {
            alert("Veuillez s�lectionner un compte.");
            form.Id_compte.focus();
            return false;
        }
    }
    if(form.lieu_mission.value == '') {
        alert("Veuillez saisir un lieu de mission.");
        form.lieu_mission.focus();
        return false;
    }
    if(form.moyen_acces.value == '') {
        alert("Veuillez s�lectionner un moyen d\'acc�s.");
        form.moyen_acces.focus();
        return false;
    }
    if(form.date_debut.value == '') {
        alert("Veuillez saisir une date de d�but.");
        form.date_debut.focus();
        return false;
    }
    if(form.date_fin.value == '') {
        alert("Veuillez saisir une date de fin de commande.");
        form.date_fin_commande.focus();
        return false;
    }
    if(form.duree.value == '') {
        alert("Veuillez saisir une dur�e.");
        form.duree.focus();
        return false;
    }
    if(form.contact.value == '') {
        alert("Veuillez saisir une interlocuteur client.");
        form.contact.focus();
        return false;
    }
    if(form.responsable.value == '') {
        alert("Veuillez s�lectionner un responsable.");
        form.responsable.focus();
        return false;
    }
    return true;
}

/**
* Verification du formulaire de saisie d'un rendezvous
*/
function verifRdv(form)
{
    var i = $F('nb_rdv');
    var j = 1;
    var d = new Date();
    var jour  = d.getDate();
    if(jour < 10) {
        var jour  = "0"+d.getDay();
    }
    var annee = d.getFullYear();
    var mois  = d.getMonth()+1;
    var today = jour+"-"+mois+"-"+annee;
    while (j <= i) {
        if(document.getElementById('date'+j).value == '' || document.getElementById('date'+j).value == false)
        {
            alert('Veuillez saisir une date');
            return false;
        }
        if(compareDate(document.getElementById('date'+j).value,today)) {
            val = document.getElementById('commentaire'+j).value;
            longueur = val.length;
            if(longueur < 10) {
                alert('Veuillez saisir un compte rendu');
                return false;
            }
        }
        j++;
    }
	
    var i = 1;
    var j = document.getElementById('nb_rdv').value;
    while (i <= j) {
        if(document.getElementById('prefix'+i).value == '' || document.getElementById('idco'+i).value == '') {
            alert("Veuillez s�lectionner un compte.");
            return false;
        }
        if(document.getElementById('type'+i).value == "") {
            alert("Veuillez s�lectionner un type.");
            return false;
        }		
        i++;
    }
    return true;
}

/**
* Verification du formulaire de saisie d'une action
*/
function verifAction(form)
{
    var i = 1;
    var j = document.getElementById('nb_action').value;
    while (i <= j) {
        if(document.getElementById('objet'+i).value == "") {
            alert("Veuillez saisir un objet.");
            return false;
        }
        i++;
    }
    return true;
}

/**
* Verification du formulaire de saisie d'une demande de recrutement
*/
function verifDemandeRessource(form) {
    if(form.date_demande.value == '') {
        alert("Veuillez saisir une date.");
        form.date.focus();
        return false;
    }
    if(form.profil.value == '') {
        alert("Veuillez saisir un profil.");
        form.profil.focus();
        return false;
    }
    if(form.statut.value != '4') {
        if(form.client.value == '') {
            alert("Veuillez saisir un client.");
            form.client.focus();
            return false;
        }
    }
    if(form.lieu.value == '') {
        alert("Veuillez saisir un lieu.");
        form.lieu.focus();
        return false;
    }
    if(form.type_demande.value == '') {
        alert("Veuillez s�lectionner un type de recrutement.");
        form.type_demande.focus();
        return false;
    }
    if(form.statut.value == '') {
        alert("Veuillez s�lectionner un statut.");
        form.statut.focus();
        return false;
    }
    if(form.statut.value == 2 && form.candidat_retenu.value == '') {
        alert("Veuillez s�lectionner le candidat retenu.");
        form.candidat_retenu.focus();
        return false;
    }
    if(form.ic.value == '') {
        alert("Veuillez s�lectionner un commercial.");
        form.ic.focus();
        return false;
    }
    if(form.cr.value=="") {
        alert("Veuillez s�lectionner un recruteur.");
        form.cr.focus();
        return false;
    }
    if(form.agence.value=="") {
        alert("Veuillez s�lectionner une agence.");
        form.agence.focus();
        return false;
    }
    if(form.salaire_debut) {
        a = 'Veuillez saisir une fourchette de salaire.';
        if(form.salaire_debut.value == '') {
            alert("Veuillez saisir une fourchette de salaire.");
            form.salaire_debut.focus();
            return false;
        }
    }
    else {
        a = 'Veuillez saisir un salaire maximum.';
    }
    if(form.salaire_fin.value == '') {
        alert(a);
        form.salaire_fin.focus();
        return false;
    }
    if(form.duree_mission.value == '') {
        alert("Veuillez saisir une dur�e de mission.");
        form.duree_mission.focus();
        return false;
    }
    if(form.description.value == '' || form.description.value.length < 20) {
        alert("Veuillez saisir une description du poste (20 caract�res minimum).");
        form.description.focus();
        return false;
    }
    if(form.competences.value == '' || form.competences.value.length < 20) {
        alert("Veuillez saisir les comp�tences requises (20 caract�res minimum).");
        form.competences.focus();
        return false;
    }
    return true;
}

/**
* Cette fonction permet de lister les client d'une demande de ressource selon une autocompl�tion
*/
function getListeClient()
{
    var Id_div = 'updateClient'
    var Id_input = 'client'
    new Ajax.Autocompleter(
        Id_input,
        Id_div,
        "../source/index.php?a=getListeClient",
        {
            method: "get",
            paramName: "client"
        }
        );
}

/**
* Cette fonction permet de lister les villes pour une annonce selon une autocompl�tion
*/
function getCityList(editor)
{
    var Id_div = 'updateLocalisation'
    new Ajax.Autocompleter(
        editor,
        Id_div,
        "../source/index.php?a=getCityList",
        {
            method: "get",
            paramName: "search",
            afterUpdateElement: function getSelectionId(text, li) {
                $('localisation').value = li.id;
            }
        }
        );
}

/**
* Fonction qui permet de comparer si une date est ant�rieure � une autre
*/
function compareDate(d1,d2) 
{ // procedure du bouton v�rifier
    var j = d1.split("-" )[0]; // jour
    var m = d1.split("-" )[1]; // mois
    var a = d1.split("-" )[2]; // ann�e
    d1 = parseInt(""+a+m+j, 10);
    var j = d2.split("-" )[0]; // jour
    var m = d2.split("-" )[1]; // mois
    var a = d2.split("-" )[2]; // ann�e
    d2 = parseInt(""+a+m+j, 10);
    if (d1 < d2) {
        return true;
    }
    return false;
}

/**
* Verification du format de la date
*/
function CheckDate(d) 
{
    // Cette fonction v�rifie le format JJ-MM-AAAA saisi et la validit� de la date.
    // Le s�parateur est d�fini dans la variable separateur
    var amin=1900; // ann�e mini
    var amax=2099; // ann�e maxi
    var separateur="-"; // separateur entre jour-mois-annee
    var j=(d.substring(0,2));
    var m=(d.substring(3,5));
    var a=(d.substring(6));
    var ok=true;
    if ( ((isNaN(j))||(j<1)||(j>31)) && (ok==true) ) {
        alert("Le jour n'est pas correct.");
        ok=false;
    }
    if ( ((isNaN(m))||(m<1)||(m>12)) && (ok==true) ) {
        alert("Le mois n'est pas correct.");
        ok=false;
    }
    if ( ((isNaN(a))||(a<amin)||(a>amax)) && (ok==true) ) {
        alert("L'ann�e n'est pas correcte.");
        ok=false;
    }
    if ( ((d.substring(2,3)!=separateur)||(d.substring(5,6)!=separateur)) && (ok==1) ) {
        alert("Les s�parateurs doivent �tre des "+separateur);
        ok=false;
    }
    if (ok==true) {
        var d2=new Date(a,m-1,j);
        j2=d2.getDate();
        m2=d2.getMonth()+1;
        a2=d2.getFullYear();
        if (a2<=100) {
            a2=1900+a2
        }
        if ( (j!=j2)||(m!=m2)||(a!=a2) ) {
            alert("La date "+d+" n'existe pas !");
            ok=false;
        }
    }
    return ok;
}

/**
* Cette fonction permet d'initialiser la variable de session en PHP $_SESSION['societe'] en fonction de la soci�t� s�lectionn�e dans la liste d�roulante
*/
function selectBdd()
{
    var url    = '../source/index.php?a=selectBdd';
    var pars   = 'Id_societe='+$F('Id_societe');
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de supprimer l'historique de statut d'une affaire
*/
function deleteHistoriqueStatut(Id_affaire,Id_statut,date)
{
    var url    = '../source/index.php?a=deleteHistoriqueStatut';
    var pars   = 'Id_affaire='+Id_affaire+'&Id_statut='+Id_statut+'&date='+escape(date);
    var target = 'historiqueStatut';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de valider l'historique de statut d'une affaire
*/
function validHistoriqueStatut(Id_affaire,i)
{
    var url    = '../source/index.php?a=validHistoriqueStatut';
    var pars   = 'Id_affaire='+Id_affaire+'&Id_statut='+$F('histoStatut'+i)+'&date='+escape($F('histoDate'+i))+'&Id_utilisateur='+$F('histoIdUtilisateur'+i);
    var target = 'historiqueStatut';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de supprimer une ressource de la partie proposition commerciale
*/
function deleteRessourceProposition(idCD, idPropRess, idProposition)
{
    var url    = '../source/index.php?a=deleteRessourceProposition';
    var pars   = 'Id_prop_ress='+idPropRess+'&Id_cd='+idCD+'&Id_proposition='+idProposition;
    var target = 'resourceTable';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de dupliquer une ressource de la partie proposition commerciale
*/
function duplicateRessourceProposition(idCD, idProposition)
{
    var url    = '../source/index.php?a=duplicateRessourceProposition';
    var pars   = 'Id_cd='+idCD+'&Id_proposition='+idProposition;
    var target = 'resourceTable';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Validation d'un contrat d�l�gation
*/
function validateCD(idCD)
{
    var url    = '../source/index.php?a=validateCD';
    var pars   = '&Id_cd='+idCD;
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(response) {
            showInformationMessage('Un mail a �t� envoy� au commercial l\'informant de la validation de son CD', 5000);
            afficherCD();
        }
    });
}

/**
* Rejet d'un contrat d�l�gation
*/
function rejectCD(idCD, step) {
    if(step == 1) {
        new Ajax.Request('../source/index.php?a=rejectCDForm&Id_cd='+idCD, {
            method: 'get',
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(transport, json) {
                var json = transport.responseText.evalJSON(true);
                Modalbox.show(json.content, {
                    title: 'Raison du retour du contrat d�l�gation', 
                    width : 800,
                    footer: json.footer
                });
            }
        });
    }
    else if(step == 2) {
        var url    = '../source/index.php?a=rejectCD';
        var pars   = '&Id_cd='+idCD+'&Id_refus='+$('Id_cause_refus').value+'&commentaire_refus='+$('commentaire_refus').value;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(response) {
                Modalbox.hide();
                showInformationMessage('Un mail a �t� envoy� au commercial l\'informant du retour de son CD', 5000);
                afficherCD();
            }
        });
    }
}

/**
* Rejet d'un contrat d�l�gation
*/
function reopenCD(idCD, step)
{
    if(step == 1) {
        new Ajax.Request('../source/index.php?a=reopenCDForm&Id_cd='+idCD, {
            method: 'get',
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(transport, json) {
                var json = transport.responseText.evalJSON(true);
                Modalbox.show(json.content, {
                    title: 'Raison de la r�-ouverture du contrat d�l�gation', 
                    width : 800,
                    footer: json.footer
                });
            }
        });
    }
    else if(step == 2) {
        var url    = '../source/index.php?a=reopenCD';
        var pars   = '&Id_cd='+idCD+'&Id_reouverture='+$('Id_cause_reouverture').value+'&commentaire_reouverture='+$('commentaire_reouverture').value;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(response) {
                Modalbox.hide();
                showInformationMessage('Un mail a �t� envoy� au commercial l\'informant de la r�ouverture de son CD', 5000);
                afficherCD();
            }
        });
    }
}

/**
* Duplication d'un contrat d�l�gation
*/
function duplicateCD(idCD) {
    if (confirm('Voulez-vous dupliquer le contrat sur la m�me affaire ?')) {
        var url    = '../com/index.php?a=dupliquerCD';
        var pars   = 'Id='+idCD;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(response) {
                showInformationMessage('Votre contrat d�l�gation a �t� correctement dupliqu�.', 5000);
                afficherCD();
            }
        });
    }
    else {
        new Ajax.Request('../source/index.php?a=dupliquerCD&Id='+idCD, {
            method: 'get',
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(transport, json) {
                var json = transport.responseText.evalJSON(true);
                Modalbox.show(json.content, {
                    title: 'Duplication d\'un contrat d�l�gation sur une autre opportunit�', 
                    width : 800,
                    infiniteLoading : true,
                    footer: json.footer,
                    afterLoad: function() {
                        prefixCompteCD(0);
                    }
                });
            }
        });
    }
    return false;
}

/**
* Cette fonction permet d'indiquer si l'ordre de mission a �t� envoy�
*/
function ODMEnvoye(id)
{
    if(document.getElementById('envoye'+id).checked) {
        var envoiOK = 1;
    } else {
        var envoiOK = 0;
    }
    var url    = '../source/index.php?a=ODMEnvoye';
    var pars   = 'Id_ordre_mission='+id+'&date_envoi='+$F('date_envoi'+id)+'&envoiOK='+envoiOK;
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onSuccess: function(transport, json) {
            if(json.disable) {
                if(document.getElementById('envoye'+id).checked) {
                    $('envoye'+id).disable();
                    $('date_envoi'+id).disable();
                }
                else {
                    $('envoye'+id).enable();
                    $('date_envoi'+id).enable();
                }
            }
            else {
                $('envoye'+id).checked = 0;
            }
            alert(json.html);
        }
    });
}

/**
* Cette fonction permet d'indiquer si l'ordre de mission a �t� retourn�
*/
function ODMRetour(id)
{
    if(document.getElementById('retour'+id).checked) {
        var retourOK = 1;
    } else {
        var retourOK = 0;
    }
    var url    = '../source/index.php?a=ODMRetour';
    var pars   = 'Id_ordre_mission='+id+'&date_retour='+$F('date_retour'+id)+'&retourOK='+retourOK;
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onSuccess: function(transport, json) {
            afficherODM();
        },
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de valider une demande de changement
*/
function DCValide(id,table)
{
    var url    = '../source/index.php?a=DCValide';
    var pars   = 'Id_demande_changement='+id+'&date_validation='+$F('date_validation'+id)+'&table='+table;
    if(table == 0) {
        if($F('commentaire_valideur') != "Commentaire du valideur")
            pars   += '&commentaire_valideur='+$F('commentaire_valideur');
    }
    new Ajax.Updater('validate'+id, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(response) {
            $('validate'+id).setStyle({
                backgroundColor:'#BAE0BA'
            });
            $('validate'+id).setStyle({
                display:'inline-block'
            });
            $('validate'+id).setStyle({
                height:'23px'
            });
            showInformationMessage('Demande valid�e.<br />Un mail a �t� envoy� au service paie pour l\'int�gration', 10000);
        }
    });
}

/**
* Cette fonction permet de refuser une demande de changement
*/
function DCReject(id) {
    var url    = '../source/index.php?a=DCReject';
    var pars   = 'Id_demande_changement='+id;
    if($F('commentaire_valideur') != "Commentaire du valideur") {
        pars   += '&commentaire_valideur='+$F('commentaire_valideur');
    }
    new Ajax.Updater('validate'+id, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(response) {
            $('validate'+id).setStyle({
                backgroundColor:'#EDC3C3'
            });
            $('validate'+id).setStyle({
                display:'inline-block'
            });
            $('validate'+id).setStyle({
                height:'23px'
            });
            $('integrate'+id).setStyle({
                backgroundColor:'#EDC3C3'
            });
            $('integrate'+id).setStyle({
                display:'inline-block'
            });
            $('integrate'+id).setStyle({
                height:'23px'
            });
            showInformationMessage('Demande rejet�e.<br />Un mail a �t� envoy� au cr�ateur de la demande pour l\'avertir.', 10000);
        }
    });
}

/**
* Cette fonction permet de r�ouvrir une demande de changement refus�
*/
function DCReopen(id) {
    var url    = '../source/index.php?a=DCReopen';
    var pars   = 'Id_demande_changement='+id;
    new Ajax.Updater('validate'+id, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(response) {
            window.location.reload();
        }
    });
}

/**
* Cette fonction permet d'int�grer une demande de changement
*/
function DCIntegre(id,table) {
    var url    = '../source/index.php?a=DCIntegre';
    var pars   = 'Id_demande_changement='+id+'&date_integration='+$F('date_integration'+id)+'&table='+table;
    new Ajax.Updater('integrate'+id, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(response) {
            $('integrate'+id).setStyle({
                backgroundColor:'#BAE0BA'
            });
            $('integrate'+id).setStyle({
                display:'inline-block'
            });
            $('integrate'+id).setStyle({
                height:'23px'
            });
            showInformationMessage('Demande int�gr�e.<br />Un mail a �t� transmis au contr�le de gestion afin de l\'avertir du changement.', 10000);
        }
    });
}

/**
* Cette fonction permet de mettre � jour les informations lors de la s�lection d'une ressource sur les demandes de changement
*/
function updateDemandeChangement(id)
{
    var url    = '../source/index.php?a=demande_changement';
    var pars   = 'Id_ressource='+id+'&type_ressource='+$F('type_ressource');
    new Ajax.Updater('demandeChangement', url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction v�rifier la pr�sence d'un candidat homonyme dans la base de donn�e
*/
function homonyme()
{
    var url    = '../source/index.php?a=homonyme';
    var pars   = 'prenom='+$F('prenom_ressource')+'&nom='+$F('nom_ressource');
    var target = 'homonyme';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher les informations concernant la ressource s�lectionn�e dans la liste d�roulante
*/
function infoRessourceCD()
{
    var url    = '../source/index.php?a=infoRessourceCD';
    var pars   = 'Id_cd='+$F('Id_cd')+'&type_ressource='+$F('type_ressource')+'&Id_ressource='+$F('Id_ressource');
    var target = 'infoRessource';
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        evalScripts:true,
        onSuccess: function(transport, json) {
            var json = transport.responseText.evalJSON(true);
            $('infoRessource').update(json.infoRessource);
            if(json.tasks) {
                tinyMCE.get(0).setContent(json.tasks);
            }
        }
    });
}

/**
* Cette fonction permet d'ajouter une langue avec un niveau associ�
*/
function ajoutLangue() 
{
    if($F('nb_langue') < 7) {
        var nb   = $F('nb_langue');
        var url  = '../source/index.php?a=ajoutLangue';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreLangue'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever une langue avec un niveau associ�
*/
function enleveLangue() 
{
    if($F('nb_langue') > 1) {
        var nb     = $F('nb_langue');
        var url    = '../source/index.php?a=supprLangue';
        var pars   = 'nb='+nb;
        var target = 'autreLangue'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une ressource dans la partie proposition commerciale
*/
function ajoutRessource() 
{
    var nb   = $F('nb_ressource');
    var url  = '../source/index.php?a=ajoutRessource';
    var pars = 'nb='+nb+'&Id_tc='+$F('Id_type_contrat');
    nb++;
    var target = 'autreRessource'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'enlever une ressource dans la partie proposition commerciale
*/
function enleveRessource() 
{
    if($F('nb_ressource') > 1) {
        var nb     = $F('nb_ressource');
        var url    = '../source/index.php?a=supprRessource';
        var pars   = 'nb='+nb;
        var target = 'autreRessource'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une ressource incluse dans le projet dans la partie proposition commerciale
*/
function ajoutRessourceInclus() 
{
    if($F('nb_ressource_i') < 50) {
        var nb   = $F('nb_ressource_i');
        var url  = '../source/index.php?a=ajoutRessourceInclus';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreRessourceInclus'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter un objet
*/
function ajout(objet) 
{
    if($F('nb_'+objet) < 7) {
        var nb   = $F('nb_'+objet);
        var url  = '../source/index.php?a=ajout&class='+objet;
        var pars = 'nb='+nb;
        nb++;
        var target = 'autre'+objet+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever un objet
*/
function enleve(objet) 
{
    if($F('nb_'+objet) > 1) {
        var nb     = $F('nb_'+objet);
        var url    = '../source/index.php?a=suppr&class='+objet;
        var pars   = 'nb='+nb;
        var target = 'autre'+objet+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever une ressource incluse dans le projet dans la partie proposition commerciale
*/
function enleveRessourceInclus() 
{
    if($F('nb_ressource_i') > 1) {
        var nb     = $F('nb_ressource_i');
        var url    = '../source/index.php?a=supprRessourceInclus';
        var pars   = 'nb='+nb;
        var target = 'autreRessourceInclus'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter l'environnement technique syst�me
*/
function ajoutSysteme() 
{
    if($F('nb_systeme') < 20) {
        var nb   = $F('nb_systeme');
        var url  = '../source/index.php?a=ajoutSysteme';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreSysteme'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever l'environnement technique syst�me
*/
function enleveSysteme() 
{
    if($F('nb_systeme') > 1) {
        var nb     = $F('nb_systeme');
        var url    = '../source/index.php?a=supprSysteme';
        var pars   = 'nb='+nb;
        var target = 'autreSysteme'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter l'environnement r�seau
*/
function ajoutReseau() 
{
    if($F('nb_reseau') < 20) {
        var nb   = $F('nb_reseau');
        var url  = '../source/index.php?a=ajoutReseau';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreReseau'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever l'environnement r�seau
*/
function enleveReseau() 
{
    if($F('nb_reseau') > 1) {
        var nb     = $F('nb_reseau');
        var url    = '../source/index.php?a=supprReseau';
        var pars   = 'nb='+nb;
        var target = 'autreReseau'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter l'environnement poste de travail
*/
function ajoutPdt() 
{
    if($F('nb_pdt') < 20) {
        var nb   = $F('nb_pdt');
        var url  = '../source/index.php?a=ajoutPdt';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autrePdt'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever l'environnement poste de travail
*/
function enlevePdt() 
{
    if($F('nb_pdt') > 1) {
        var nb     = $F('nb_pdt');
        var url    = '../source/index.php?a=supprPdt';
        var pars   = 'nb='+nb;
        var target = 'autrePdt'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter un rendez-vous
*/
function ajoutRdv() 
{
    if($F('nb_rdv') < 20) {
        var nb   = $F('nb_rdv');
        var url  = '../source/index.php?a=ajoutRdv';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreRdv'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever un rendez-vous
*/
function enleveRdv() 
{
    if($F('nb_rdv') > 1) {
        var nb     = $F('nb_rdv');
        var url    = '../source/index.php?a=supprRdv';
        var pars   = 'nb='+nb;
        var target = 'autreRdv'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une action
*/
function ajoutAction() 
{
    if($F('nb_action') < 20) {
        var nb   = $F('nb_action');
        var url  = '../source/index.php?a=ajoutAction';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreAction'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever une action
*/
function enleveAction() 
{
    if($F('nb_action') > 1) {
        var nb     = $F('nb_action');
        var url    = '../source/index.php?a=supprAction';
        var pars   = 'nb='+nb;
        var target = 'autreAction'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une proposition commerciale
*/
function ajoutProposition() 
{
    if($F('nb_proposition') < 20) {
        var nb   = $F('nb_proposition');
        var url  = '../source/index.php?a=ajoutProposition';
        var pars = 'nb='+nb;
        nb++;
        var target = 'autreProposition'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever une proposition commerciale
*/
function enleveProposition() 
{
    if($F('nb_proposition') > 1) {
        var nb     = $F('nb_proposition');
        var url    = '../source/index.php?a=supprProposition';
        var pars   = 'nb='+nb;
        var target = 'autreProposition'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une ann�e dans la partie proposition commerciale
*/
function ajoutAnnee(i) 
{
    var nb   = $F('pr-'+i+'-an');
    var url  = '../source/index.php?a=ajoutAnnee';
    var pars = 'n_pr='+i+'&n_an='+nb;
    nb++;
    var target = 'pr'+i+'|an'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'enlever une ann�e dans la partie proposition commerciale
*/
function enleveAnnee(i) 
{
    if($F('pr-'+i+'-an') > 1) {
        var nb     = $F('pr-'+i+'-an');
        var url    = '../source/index.php?a=supprAnnee';
        var pars   = 'n_pr='+i+'&n_an='+nb;
        var target = 'pr'+i+'|an'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'ajouter une p�riode dans la partie proposition commerciale
*/
function ajoutPeriode(i,j) 
{
    if($F('pr-'+i+'-n_periode-an-'+j) < 7) {
        var nb   = $F('pr-'+i+'-n_periode-an-'+j);
        var url  = '../source/index.php?a=ajoutPeriode';
        var pars = 'n_pr='+i+'&n_pe='+nb+'&n_an='+j;
        nb++;
        var target = 'pr'+i+'|pe'+nb+'|an'+j;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet d'enlever une ann�e dans la partie proposition commerciale
*/
function enlevePeriode(i,j) 
{
    if($F('pr-'+i+'-n_periode-an-'+j) > 1) {
        var nb     = $F('pr-'+i+'-n_periode-an-'+j);
        var url    = '../source/index.php?a=supprPeriode';
        var pars   = 'n_pr='+i+'&n_pe='+nb+'&n_an='+j;
        var target = 'pr'+i+'|pe'+nb+'|an'+j;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Cette fonction permet de supprimer un �tat dans l'historique des �tats d'un candidat
*/
function supprimerHc(i,j,k) 
{
    var url    = '../source/index.php?a=supprimerHc';
    var pars   = 'Id_candidature='+i+'&Id_etat='+j+'&date='+k;
    var target = 'historique';
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onSuccess: function(transport, json) {
            var json = transport.responseText.evalJSON(true);
            $('historique').update(json.history);
            $('etat').setValue(json.etat);
        }
    });
}

/**
* Cette fonction permet de valider un �tat dans l'historique des �tats d'un candidat
*/
function validerHc(i,j,k) 
{
    var url    = '../source/index.php?a=validerHc';
    var pars   = 'Id_candidature='+i+'&Id_etat='+j+'&date='+$F('date'+k)+'&Id_utilisateur='+$F('Id_utilisateur'+k);
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onSuccess: function(transport, json) {
            var json = transport.responseText.evalJSON(true);
            $('historique').update(json.history);
            $('etat').setValue(json.etat);
        }
    });
}

/**
* Cette fonction permet de supprimer un �tat dans l'historique des �tats d'un candidat
*/
function supprimerStatutDR(i,j,k)
{
    var url    = '../source/index.php?a=supprimerStatutDR';
    var pars   = 'Id_demande_ressource='+i+'&Id_statut='+j+'&date='+k;
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onSuccess: function(transport, json) {
            var json = transport.responseText.evalJSON(true);
            $('historique_statut').update(json.history);
            $('statut').setValue(json.statut);
        }
    });
}

/**
* Cette fonction permet de valider un �tat dans l'historique des �tats d'un candidat
*/
function validerStatutDR(i,j,k)
{
    var url    = '../source/index.php?a=validerStatutDR';
    var pars   = 'Id_demande_ressource='+i+'&Id_statut='+j+'&date='+$F('date'+k)+'&Id_utilisateur='+$F('Id_utilisateur'+k);
    var target = 'historique_statut';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'appliquer les droit d'un groupe AD sur une zone d'acc�s
*/
function appliquerDroitGroupeAdZoneAcces(i,j) 
{
    var url    = '../source/index.php?a=appliquerDroitGroupeAdZoneAcces';
    var pars   = 'Id_groupe_ad='+i+'&Id_zone_acces='+j;
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'appliquer les droit d'un groupe AD sur un menu
*/
function appliquerDroitGroupeAdMenu(i,j) 
{
    var url    = '../source/index.php?a=appliquerDroitGroupeAdMenu';
    var pars   = 'Id_groupe_ad='+i+'&Id_menu='+j;
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(transport) {
            //transport.responseText
             var cb = $('conteneur').select('[data-groupe="'+i+'"][data-menu="'+j+'"]')[0];
             if (cb.hasClassName('rowvert')) {
                     cb.removeClassName('rowvert');
                     cb.addClassName('roworange');
             } else {
                 cb.removeClassName('roworange');
                 cb.addClassName('rowvert');
             }
        },
        onFailure: function() { alert('Echec de l\'enregistrement.'); }
    });
}

/**
* Cette fonction permet d'afficher la liste des contacts associ�s � un compte client
*/
function changeCompte(compte) 
{
    var url    = '../source/index.php?a=afficherListeContact';
    var pars   = 'Id_compte='+escape(compte)+'&Id_affaire='+$F('Id_affaire');
    var target = 'contact';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher la liste des contacts associ�s � un compte client
*/
function showCaseList(compte, update) {
    ref_aff = $('reference_affaire') == 'null' ? $('reference_affaire') : '';
    ref_aff_mere = $('reference_affaire_mere') == 'null' ? $('reference_affaire_mere') : '';
    var url = $('Id_session') == null ? '../source/index.php?a=afficherListeAffaire' : '../source/index.php?a=afficherListeAffaireFormation';
    var pars   = 'Id_compte='+escape(compte)+'&reference_affaire='+ref_aff+'&reference_affaire_mere='+ref_aff_mere;
    if(update == 1)
        pars += '&update='+update;
    var target = 'affaire';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport, json) {
            Modalbox.hideLoading();
        }
    });
}

/**
* Cette fonction permet d'afficher la zone de commentaire lorsque l'on selectionne le statut perdu pour une affaire
*/
function changeStatut() 
{
    if($F('Id_statut') == 3 || $F('Id_statut') == 4) {
        $('ponderation').enable();
        $('ponderation').writeAttribute('readonly', null);
        $('ca_pondere').enable()
    }
    else {
        if($F('Id_statut') == 2 || $F('Id_statut') == 6 || $F('Id_statut') == 7) {
            var options = $$('select#ponderation option');
            options[0].selected = true;
        }
        else if($F('Id_statut') == 5 || $F('Id_statut') == 8 || $F('Id_statut') == 9) {
            var options = $$('select#ponderation option');
            options[6].selected = true;
            $('ca_pondere').value = Math.round($('cha').value * ($('ponderation').value / 100));
        }
        $('ponderation').disable();
        $('ponderation').writeAttribute('readonly', 'readonly');
        $('ca_pondere').writeAttribute('readonly', 'readonly');
    }
    if ($F('Id_statut') == 6) {
        if ( $F('Id_affaire') == '') {
            var pars = 'Id_statut='+$F('Id_statut');
        }
        else {
            var pars = 'Id_affaire='+$F('Id_affaire')+'&Id_statut='+$F('Id_statut');
        }
        var url  = '../source/index.php?a=statutPerdu';
        var target = 'maj';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
    else {
        $('maj').update('');
    }
}

/**
* Cette fonction permet d'afficher le champ de s�lection de la raison perdue dans les filtres affaires
*/
function changeStatutSearch() 
{
    var pars = 'Id_statut='+$F('Id_statut');
    var url  = '../source/index.php?a=statutPerduSearch';
    var target = 'maj';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        asynchronous: false
    });
}

/**
* Cette fonction permet d'afficher la partie �tat technique dans un formulaire d'affaire
*/
function changeFormAffaire() 
{
    if($('resp_qualif')!=null)
        var pars2 = '&resp_qualif='+$F('resp_qualif')+'&sdm='+$F('sdm')+'&resp_tec1='+$F('resp_tec1')+'&resp_tec2='+$F('resp_tec2')+'&nb_jour_estime='+$F('nb_jour_estime');
    else
        var pars2 ='';
    var pars = 'Id_pole='+$F('Id_pole')+'&Id_type_contrat='+$F('Id_type_contrat')+pars2
    var url  = '../source/index.php?a=afficherEtatTech';
    new Ajax.Updater('etatTech', url, {
        method: 'get',
        parameters: pars
    });
    
    var pars = 'Id_pole='+$F('Id_pole')+'&Id_type_contrat='+$F('Id_type_contrat')+'&Id_environnement='+$F('Id_environnement')
    var url  = '../source/index.php?a=afficherEnvTech';
    new Ajax.Updater('envTech', url, {
        method: 'get',
        parameters: pars
    });
    
    var pars = 'Id_pole='+$F('Id_pole')+'&Id_type_contrat='+$F('Id_type_contrat')+'&Id_intitule='+$F('Id_intitule')
    var url  = '../source/index.php?a=afficherIntituleAffaire';
    new Ajax.Updater('intituleAffaire', url, {
        method: 'get',
        parameters: pars
    });
}

/**
* Cette fonction permet de mettre � jour l'historique des �tats d'un candidat
*/
function updateEtatCandidature(Id_etat)
{
    if($F('Id_candidature')) {
        var url    = '../source/index.php?a=updateEtatCandidature';
        var pars   = 'Id_candidature='+$F('Id_candidature')+'&Id_etat='+Id_etat;
        var target = 'historique';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
    if(Id_etat == 6) {
        var url    = '../source/index.php?a=afficherTypeVal';
        var pars   = 'Id_candidature='+$F('Id_candidature');
        var target = 'typeval';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())+'2'
        });
    }
    else if(Id_etat == 8 || Id_etat == 9) {
        var url    = '../source/index.php?a=afficherTypeEmbauche';
        var pars   = 'Id_candidature='+$F('Id_candidature');
        var target = 'typeval';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())+'2'
        });
    }
}

/**
* Cette fonction permet de saisir dans un champ les lettres composant le nom du compte client pour affiner la liste d�roulante des comptes client
*/
function prefixCompte() 
{
    var url    = '../source/index.php?a=afficherListeCompte';
    var pars   = 'prefix='+escape($F('prefix'));
    var target = 'compte';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de saisir dans un champ les lettres composant le nom du compte client pour affiner la liste d�roulante des comptes client
*/
function prefixCompteCD(update, disable) {
    if(typeof(disable) != 'undefined'){
        disable = disable;
    }
    else {
        disable = false;
    }
    var opType, Id_compte = '';
    if($('opType')) {
        opType = '&type='+$('opType').value;
    }
    else {
        opType = '&type=agc'
    }
    if($('Id_compte')) {
        Id_compte = '&Id_compte='+$('Id_compte').value;
    }
    var url    = '../source/index.php?a=afficherListeCompteCD';
    var pars   = 'prefix='+escape($F('prefix'))+'&update='+update+Id_compte+opType;
    var target = 'compte';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport, json) {
            var idCompte = $$('body')[0].select('select#Id_compte')[0];
            showCaseList(idCompte[idCompte.selectedIndex].value, undefined);
            if(disable) {
                if($('opType').value == 'sfc') {
                    $('prefix').disable();
                    
                    idCompte.enable();
                }
                else {
                    $('prefix').enable();
                    idCompte.enable();
                }
            }
        }
    });
}

/**
* Cette fonction permet de saisir dans un champ les lettres composant le nom du compte client pour affiner la liste d�roulante des comptes client
*/
function updateCaseInformation(idAffaire) {
    if(idAffaire!='') {
        var url = $('Id_session') == null ? '../source/index.php?a=updateCaseInformation' : '../source/index.php?a=updateTrainingCaseInformation';
        var opType = '';
        if($('opType')) {
            opType = '&type='+$('opType').value;
        }
        else {
            opType = '&type=agc';
        }
        var pars   = 'Id_affaire='+idAffaire;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onSuccess: function(transport, json) {
                var json = transport.responseText.evalJSON(true);
                if(json.erreur === '') {
                    $('infoOpp').update(json.infoOpp);
                    $('profilSpan').update(json.profil);
                    console.dir(json.politique_secu);
                    $('politique_securite').update(json.politique_secu);
                    $('date_debut').value = json.date_debut;
                    $('date_fin_commande').value = json.date_fin;
                    $('duree').value = json.duree;
                    $('lieu_mission').value = json.lieu_mission;
                    $('reference_affaire').value = json.reference_affaire;
                    $('reference_affaire_mere').value = json.reference_affaire_mere;
                    $('reference_devis').value = json.reference_devis;
                    $('reference_bdc').value = json.reference_bdc;
                    $('date_min').value = json.date_min;
                    $('date_max').value = json.date_max;
                    tinyMCE.get(0).setContent(json.description);
                    tinyMCE.get(1).setContent(json.commentaire_horaire);
                }
                else {
                    $('infoOpp').update('<span class="error">' + json.erreur + '</span>');
                }
            }
        });
    }
}

/**
* Cette fonction permet de remplir les champs li�s aux profils
*/
function updateProfilInformation(profil) {
    var selectedOption = profil.options[profil.selectedIndex];
    $('cout_journalier').value = selectedOption.getAttribute('data-prix');
    $('duree').value = selectedOption.getAttribute('data-duree');
    $('date_debut').value = selectedOption.getAttribute('data-datedebut');
    $('date_fin_commande').value = selectedOption.getAttribute('data-datefin');
    $('code_produit').value = selectedOption.getAttribute('data-codeproduit');
    $('agence_mission').value = selectedOption.getAttribute('data-agencemission');
    $('agence_mission_lbl').innerText = selectedOption.getAttribute('data-agencemission');
}

/**
* Cette fonction permet de remplir le champ Lieu de Mission dans Contrat Deleg
*/
function updateLieuMission(site) {
    var pars   = 'lieuxPresta=' + site;
    new Ajax.Updater('detailsLieuPrestation', '../source/index.php?a=afficherDetailsLieuPrestation', {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet d'afficher ou non le select des lieux de prestation du compte
*/
function displayLieuxPrestation() {
    var url = '../source/index.php?a=consulterLieuxPrestation';
    var pars   = 'Id_compte=0&Id_affaire=0';
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
    });
}

/**
* Cette fonction permet de saisir dans un champ les lettres composant le nom du compte client pour affiner la liste d�roulante des comptes client ainsi que les contacts associ�s
*/
function prefixCompte2(i,refresh) 
{
    if(refresh) {
        var pars = 'prefix='+escape($F('prefix'+i))+'&nb='+i+'&refresh='+refresh;
    } else {
        var pars = 'prefix='+escape($F('prefix'+i))+'&nb='+i;
    }
    var url    = '../source/index.php?a=afficherListeCompte2';
    var target = 'compte'+i;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de lister les comptes client selon les diff�rents filtres appliqu�s
*/
function afficherCompte(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterCompte';
    if($('nom') != null){
        pars   += '&nom='+escape($F('nom'))+'&ville='+escape($F('ville'))+'&cp='+escape($F('cp'))+'&createur='+escape($F('createur'));
    }
    else {
        if($('user')){
            pars   += '&createur='+escape($F('user'));
        }
    }
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les affaires selon les diff�rents filtres appliqu�s
*/
function afficherAffaire(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    if($('Id_raison_perdue')!=undefined)
        raison = '&Id_raison_perdue='+$F('Id_raison_perdue');
    else
        raison = '';
    var url    = '../source/index.php?a=consulterAffaire';
    pars   += '&Id_compte='+escape($F('Id_compte'))+'&Id_contact='+escape($F('Id_contact'))+'&Id_type_contrat='+$F('Id_type_contrat')+'&Id_statut='+$F('Id_statut')+'&commercial='+$F('commercial')+'&redacteur='+$F('redacteur')+'&Id_pole='+$F('Id_pole')+'&Id_agence='+$F('Id_agence')+'&ville='+escape($F('ville'))+'&Id_affaire='+$F('Id_affaire')+'&ca='+$F('ca')+'&type_ca='+$F('type_ca')+'&Id_intitule='+$F('Id_intitule')+'&debut='+$F('debut')+'&fin='+$F('fin')+'&nb='+$F('nb')+'&Id_ressource='+$F('Id_ressource')+raison+'&motcleaffaire='+$F('motcleaffaire');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
//Effect.Grow('page');
}

function afficherCAAffaire(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterCAAffaire';
    pars   += '&Id_type_contrat='+$F('Id_type_contrat')+'&Id_statut_ponderation='+$F('Id_statut_ponderation').join(',')+'&commercial='+$F('commercial')+'&Id_pole='+$F('Id_pole');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        evalScripts: true,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

function afficherPropositionAV(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (typeof(info.sort) != 'undefined') {
        if (info.sort.length > 0) {
            pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
        }
    }

    var url    = '../source/index.php?a=consulterPropositionAV';
    pars   += '&Id_type_contrat_prop='+$F('Id_type_contrat_prop')+'&Id_pole_prop='+$F('Id_pole_prop')+'&Id_statut_prop='+info.data;
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        evalScripts: true,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les bilan d'activit� selon les diff�rents filtres appliqu�s
*/
function afficherBilanActivite(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    
    var url    = '../source/index.php?a=consulterBilanActivite';
    pars   += '&Id_bilan_activite='+$F('Id_bilan_activite')+'&responsable='+$F('responsable')+'&commercial='+$F('commercial')+'&mois='+$F('mois');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de lister les demandes de changement selon les diff�rents filtres appliqu�s
*/
function afficherDemandeChangement(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterDemandeChangement';
    pars   += '&Id_demande_changement='+$F('Id_demande_changement')+'&createur='+$F('createur')+'&Id_ressource='+$F('Id_ressource')+'&debut='+$F('debut')+'&fin='+$F('fin')+'&valide='+$F('valide')+'&integre='+$F('integre');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les contrats d�l�gation selon les diff�rents filtres appliqu�s
*/
function afficherCD(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterCD';
    pars   += '&Id_cd='+$F('Id_cd')+'&reference_affaire_mere='+$F('reference_affaire_mere')+'&Id_affaire='+$F('Id_affaire')+'&createur='+$F('createur')+'&Id_ressource='+$F('Id_ressource')+'&Id_agence='+$F('Id_agence')+'&statut='+$F('statut')+'&archive='+$F('archive')+'&motclecd='+$F('motclecd')+'&finishing='+$F('finishing')+'&origine='+$F('origine');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les collab sans contrat deleg
*/
function afficherCollabSansCD(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterCollabSansCD';
    pars   += '&date='+$F('date')+'&Id_agence='+$F('Id_agence')+'&cds='+$F('cds')+'&origine='+$F('origine')+'&withha='+$F('withha')+'&cdstatus='+$F('cdstatus')+'&retirerabsent='+$F('retirerabsent')+'&retirerstaff='+$F('retirerstaff');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister temps de travail associ� aux CD
*/
function afficherContratDelegWorkTime(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterContratDelegWorkTime';
    pars   += '&Id_cd='+$F('Id_cd')+'&Id_affaire='+$F('Id_affaire')+'&Id_createur='+$F('createur')+'&Id_ressource='+$F('Id_ressource')+'&month='+$F('month')+'&year='+$F('year');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les contrats d�l�gation selon les diff�rents filtres appliqu�s
*/
function afficherRefacturationCD(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterRefacturationCD';
    pars   += '&Id_cd='+$F('Id_cd')+'&Id_affaire='+$F('Id_affaire')+'&Id_ressource='+$F('Id_ressource')+'&motclecd='+$F('motclecd')+'&debut='+$F('debut')+'&fin='+$F('fin');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les contrats d�l�gation selon les diff�rents filtres appliqu�s
*/
function afficherExportCD(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=exportCD';
    pars   += '&debut='+$F('debut')+'&fin='+$F('fin');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* R�cup�re la liste des ressources en fonction du type
*/
function updateResourceListByType(idRessource) 
{
    var url    = '../source/index.php?a=updateResourceListByType';
    var pars   = 'type_ressource='+$F('type_ressource')+'&Id_ressource='+idRessource;
    var target = 'Id_ressource';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* R�cup�re la liste des ressources en fonction du type
*/
function updateCaseResourceListByType(i) 
{
    var url    = '../source/index.php?a=updateResourceListByType';
    var pars   = 'type_ressource='+$F('type_ressource_prop'+i);
    var target = 'ressource'+i;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* R�cup�re la liste des ressources en fonction du type
*/
function updateOpportunityListByType(idAffaire) {
    var url    = '../source/index.php?a=updateOpportunityCD';
    var pars   = 'type='+$F('type_affaire')+'&Id_affaire='+idAffaire;
    var target = 'Id_affaire';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de lister les ordres de mission selon les diff�rents filtres appliqu�s
*/
function afficherODM(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterODM';
    if($('Id_odm') != null){
        pars   += '&Id_odm='+$F('Id_odm')+'&createur='+$F('createur')+'&Id_ressource='+$F('Id_ressource')+'&debut='+$F('debut')+'&fin='+$F('fin')+'&Id_agence='+$F('Id_agence')+'&responsable='+$F('responsable')+'&Id_compte='+escape($F('Id_compte'))+'&finishing='+$F('finishing');
    }
    else {
        if($('user') != null)
            pars   += '&createur='+$F('user')
    }
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les logs selon les diff�rents filtres appliqu�s
*/
function afficherLog() 
{
    var url    = '../source/index.php?a=consulterLog';
    var pars   = 'Id_affaire='+$F('Id_affaire')+'&Id_utilisateur='+$F('Id_utilisateur')+'&debut='+$F('debut')+'&fin='+$F('fin');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de lister les candidats selon les diff�rents filtres appliqu�s
*/
function afficherCandidature(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    if(document.getElementById("th") && document.getElementById("th").checked == true) {
        var th=1;
    } else {
        var th=0;
    }
    if(document.getElementById("embauche") && document.getElementById("embauche").checked == true) {
        var embauche=1;
    } else {
        var embauche=0;
    }

    var url = '../source/index.php?a=consulterCandidature';
    if(document.getElementById("createur")) {
        pars += '&createur='+$F('createur')+'&commercial='+$F('commercial')+'&Id_etat='+$F('Id_etat')+'&Id_nature='+$F('Id_nature')
        +'&nom_candidat='+$F('nom_candidat')+'&Id_preavis='+$F('Id_preavis')+'&Id_exp_info='+$F('Id_exp_info')+'&Id_profil='+$F('Id_profil')
        +'&pretention_basse='+$F('pretention_basse')+'&pretention_haute='+$F('pretention_haute')+'&type_date='+$F('type_date')+'&debut='+$F('debut')
        +'&fin='+$F('fin')+'&cp='+$F('cp')+'&Id_cursus='+$F('Id_cursus')+'&Id_candidature='+$F('Id_candidature')
        +'&tv='+$F('tv')+'&motcle='+$F('motcle')+'&Id_action_mener='+$F('Id_action_mener')+'&mobilite='+$F('mobilite')+'&th='+th
        +'&embauche='+embauche+'&agence='+$F('agence').join(';')+'&type_contrat='+$F('type_contrat').join(';')
        +'&Id_specialite='+$F('Id_specialite').join(';')+'&createurEtat='+$F('createurEtat').join('","');
    }
    else {
        pars += '&Id_etat=1';
    }
    var target = 'pageCandidature';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les m�tiers selon les diff�rents filtres appliqu�s
*/
function afficherMetier(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    pars  += '&libelle='+$('libelle').value+'&Id_categorie_metier='+$('Id_categorie_metier').value;
    new Ajax.Updater('pageMetier', '../source/index.php?a=consulterMetier', {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les lieux de prestation selon les diff�rents filtres appliqu�s
*/
function afficherLieuPrestation(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    pars  += '&libelle='+$('libelle').value+'&id_type_lieux_prestation='+$('id_type_lieux_prestation').value;
    new Ajax.Updater('page', '../source/index.php?a=consulterLieuxPrestation', {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les lieux de prestation selon les diff�rents filtres appliqu�s
*/
function afficherDetailsCommune(info) 
{
    var pars   = 'id_communes=' + info;
    new Ajax.Updater('detailsCommune', '../source/index.php?a=afficherDetailsCommune', {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de lister les offre d'emploi selon les diff�rents filtres appliqu�s
*/
function afficherAnnonce(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterAnnonce';

        pars  += '&metier='+$F('metier')+'&debut='+$F('debut')+'&fin='+$F('fin')+'&mot_cle='+$F('mot_cle')+'&localisation='+$F('localisation')+'&createur_annonce='+$F('createur_annonce');

    var target = 'pageAnnonce';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction v�rifie si l'annonce a �t� modifi�
*/
function onUpdateAnnonce(editor) {
    if(editor.isDirty()) {
        $('dirty').value = '1';
    }
    else {
        $('dirty').value = '0';
    }
    return false;
}

/**
* Cette fonction permet de lister les contacts selon les diff�rents filtres appliqu�s
*/
function afficherContact(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterContact';
    pars   += '&nom='+escape($F('nom'))+'&societe='+escape($F('societe'))+'&ville='+escape($F('ville'))+'&cp='+escape($F('cp'))+'&mail='+escape($F('mail'))+'&nature='+escape($F('nature'))+'&createur='+escape($F('createur'));
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Cette fonction permet de rafraichir l'iframe en affichant le rapport AGC BILAN D'ACTIVITE en fonction du commercial et du mois s�lectionn�s dans les filtres de recherche
*/
function afficherIframeRapport() 
{
    var url    = '../source/index.php?a=afficherIframeRapport';
    var pars   = 'commercial='+$F('commercial')+'&mois='+$F('mois');
    var target = 'iframeRapport';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher le formulaire de saisie d'un rendez-vous
*/
function formulaireRdv() 
{
    var url    = '../source/index.php?a=formulaireRdv';
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher le formulaire de saisie d'une action
*/
function formulaireAction() 
{
    var url    = '../source/index.php?a=formulaireAction';
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher les liste d�roulante contenant les contacts associ�s � un compte client
*/
function contactCompte(Id_compte,nb,refresh) 
{
    if(nb == 1) {
        var nb2 = nb;
    } else {
        if(document.getElementById('nb_rdv') != null) {
            var nb2 = $F('nb_rdv');
        } else {
            var nb2 = $F('nb_action');
        }
    }
	
    if(refresh) {
        var pars = 'Id_compte='+escape(Id_compte)+'&refresh='+refresh+'&nb='+nb;
    } else {
        var pars = 'Id_compte='+escape(Id_compte)+'&nb='+nb;
    }
	
    var url    = '../source/index.php?a=contactCompte';
    var target = 'contactCompte'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de d�plier le module les coordonn�es dans le formulaire de saisie d'une affaire
*/
function afficherCoordonnee() 
{
    var url    = '../source/index.php?a=afficherCoordonnee';
    var pars   = 'Id_affaire='+$F('Id_affaire');
    var target = 'coordonnee';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher les coordonn�es et quelques informations compl�mentaires d'un compte client une fois s�lectionn� dans la liste d�roulante
*/
function infoCompte(compte) 
{
    var url    = '../source/index.php?a=afficherInfoCompte';
    var pars   = 'Id_compte='+escape(compte);
    var target = 'infoCompte';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de d�plier le module les coordonn�es dans le formulaire de saisie d'une affaire
*/
function creerAffaire() 
{
    var url  = '../source/index.php?a=creerAffaire';
    var pars = 'commercial='+$F('commercial')+'&Id_compte='+$F('Id_compte')+'&Id_agence='+$F('Id_agence')+'&Id_statut=1&Id_pole='+$F('Id_pole')+'&Id_type_contrat='+$F('Id_type_contrat')+'&chiffre_affaire='+$F('chiffre_affaire');
    new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de donner un effet pulsation lors de l'affichage des derni�re news
*/
function afficherNews() 
{
    new Effect.Pulsate('news');
}

/**
* Cette fonction permet d'afficher le menu des liens vers les affaires du p�le support / prodution
*/
function lienPropale() 
{
    new Effect.toggle('lienpropale');
    new Effect.Move('lienpropale', {
        x: 50,
        y: 0,
        mode: 'absolute'
    });
}

/**
* Cette fonction permet d'afficher le menu des liens de cr�ation d'une affaire : p�le 
*/
function lienAffaire() 
{
    new Effect.toggle('lienaffaire');
    new Effect.Move('lienaffaire', {
        x: -50,
        y: 0,
        mode: 'absolute'
    });
}

/**
* Cette fonction permet d'afficher le menu des liens de cr�ation d'une affaire : type de contrat 
*/
function lienTypeContrat(i) 
{
    var url    = '../source/index.php?a=lientc';
    var pars   = 'Id_pole='+i;
    var target = 'lientc'+i;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    new Effect.toggle('lientc'+i);
    new Effect.Move('lientc'+i, {
        x: -200,
        y: 0,
        mode: 'absolute'
    });
}

/**
* Cette fonction permet d'afficher / cacher les menus de gauche et de droite
*/
function cacherMenu() 
{
    new Effect.toggle('leftMenu');
    new Effect.Move('leftMenu', {
        x: 0,
        y: 0,
        mode: 'absolute'
    });
    new Effect.toggle('rightMenu');
    new Effect.Move('rightMenu', {
        x: 0,
        y: 0,
        mode: 'absolute'
    });
	
	
    ++i;
	
    if(i % 2 == 1) {
        document.getElementById("content").style.width = "100%";
    //document.getElementById("arrowMenu").src="../ui/images/arrow_r.gif";
		
    }
    else if(i % 2 == 0) {
        document.getElementById("content").style.width = "79%";
    //document.getElementById("arrowMenu").src="../ui/images/arrow_l.gif";
    }
}

/**
* Cette fonction permet d'afficher les menus de gauche et de droite
*/
function afficheMenu()
{
    var pars = 'cacheMenu=0';
    var url    = '../source/index.php?a=afficheMenu';
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    Effect.Appear('leftMenu', {
        duration: 3.0
    });
    Effect.Appear('rightMenu', {
        duration: 3.0
    });
    document.getElementById("content").style.width = "79%";
}

/**
* Cette fonction permet de cacher les menus de gauche et de droite
*/
function cacheMenu()
{
    var pars = 'cacheMenu=1';
    var url    = '../source/index.php?a=afficheMenu';
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    Effect.Squish('leftMenu');
    Effect.Squish('rightMenu');
    document.getElementById("content").style.width = "100%";
}

/**
* Cette fonction permet d'afficher les filtres
*/
function afficheFiltre()
{
    var pars = 'cacheFiltre=0';
    var url    = '../source/index.php?a=afficheFiltre';
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    Effect.Appear('filtre', {
        duration: 3.0
    });
}

/**
* Cette fonction permet de cacher les filtres
*/
function cacheFiltre()
{
    var pars = 'cacheFiltre=1';
    var url    = '../source/index.php?a=afficheFiltre';
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    Effect.Squish('filtre');
}

/**
* Cette fonction permet de calculer la somme des co�ts et les marges dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function calcul(nb) 
{
    var url    = '../source/index.php?a=calculProjetForfaitaire';
    var pars   = 'cout_phase1='+$F('cout_phase1'+nb)+'&cout_phase2='+$F('cout_phase2'+nb)+'&cout_phase3='+$F('cout_phase3'+nb)+'&cout_licence='+$F('cout_licence'+nb)+'&cout_materiel='+$F('cout_materiel'+nb)+'&cout_autre='+$F('cout_autre'+nb)+'&ca_phase1='+$F('ca_phase1'+nb)+'&ca_phase2='+$F('ca_phase2'+nb)+'&ca_phase3='+$F('ca_phase3'+nb)+'&ca_licence='+$F('ca_licence'+nb)+'&ca_materiel='+$F('ca_materiel'+nb)+'&ca_autre='+$F('ca_autre'+nb)+'&cout_phase4='+$F('cout_phase4'+nb)+'&ca_phase4='+$F('ca_phase4'+nb)+'&nb='+nb;
    var target = 'total'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
 
    margePhase1(nb);
    margePhase2(nb);
    margePhase3(nb);
    margePhase4(nb);
    margeMateriel(nb);
    margeLicence(nb);
    margeAutre(nb);
}

/**
* Cette fonction permet de calculer la marge de la phase 1 dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margePhase1(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_phase1='+$F('cout_phase1'+nb)+'&ca_phase1='+$F('ca_phase1'+nb);
    var target = 'marge_phase1'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge de la phase 2 dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margePhase2(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_phase2='+$F('cout_phase2'+nb)+'&ca_phase2='+$F('ca_phase2'+nb);
    var target = 'marge_phase2'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge de la phase 3 dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margePhase3(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_phase3='+$F('cout_phase3'+nb)+'&ca_phase3='+$F('ca_phase3'+nb);
    var target = 'marge_phase3'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge de la phase 4 dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margePhase4(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_phase4='+$F('cout_phase4'+nb)+'&ca_phase4='+$F('ca_phase4'+nb);
    var target = 'marge_phase4'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge du mat�riel dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margeMateriel(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_materiel='+$F('cout_materiel'+nb)+'&ca_materiel='+$F('ca_materiel'+nb);
    var target = 'marge_materiel'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge des licences dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margeLicence(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_licence='+$F('cout_licence'+nb)+'&ca_licence='+$F('ca_licence'+nb);
    var target = 'marge_licence'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge autre dans la partie proposition commerciale d'un type de contrat projet forfaitaire
*/
function margeAutre(nb) 
{
    var url    = '../source/index.php?a=calculMargeForfaitaire';
    var pars   = 'cout_autre='+$F('cout_autre'+nb)+'&ca_autre='+$F('ca_autre'+nb);
    var target = 'marge_autre'+nb;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge dans la partie proposition commerciale d'un type de contrat Infog�rance / Forfait de service
*/
function margeInfogerance(i,j,k) 
{
    var ca   = $F('ca_pe'+i+'-pr-'+j+'-an'+k);
    var cout = $F('cout_pe'+i+'-pr-'+j+'-an'+k);
    if (cout != 0 && ca !=0) {
        document.getElementById('marge_pe'+i+'-pr-'+j+'-an'+k).value = (Math.round((100 *(ca-cout) / ca)*100)/100 );
    } else {
        document.getElementById('marge_pe'+i+'-pr-'+j+'-an'+k).value = 0;
    }
    document.getElementById('cha').value = 1;
}

/**
* Cette fonction permet de calculer les couts,les marges, les chiffre d'affaire par ressource et au global dans la partie proposition commerciale d'un type de contrat AT
*/
function calculRessource(i) 
{
    marge(i);
    ca(i);
}

/**
* Cette fonction permet de calculer les couts,les marges, les chiffre d'affaire par ressource et au global dans la partie proposition commerciale d'un type de contrat AT
*/
function calculRessource2() 
{
    marge2();
    ca2();
}

/**
* Cette fonction permet de calculer les couts,les marges, les chiffre d'affaire par ressource et au global dans la partie proposition commerciale d'un type de contrat AT
*/
function calculRessource3() 
{
    prixVente();
    ca2();
}

/**
* Cette fonction permet de calculer la marge pour une ressource dans la calculatrice
*/
function marge2() 
{
    var a = parseFloat($F('cout_ressource'));
    var b = parseFloat($F('frais_ressource'));
    var c = a+b;
    m = 100 * ($F('tarif_ressource') - c) / $F('tarif_ressource');
    m = Math.round(m*100)/100;
	
    slide.setValue(m);
    document.getElementById('marge_ressource').value = m;
}

/**
* Cette fonction permet de calculer le prix de vente pour une ressource dans la calculatrice
*/
function prixVente() 
{
    var m = parseFloat($F('marge_ressource'));
    var c = parseFloat($F('cout_ressource'))+parseFloat($F('frais_ressource'));
    var f = (100 * c) / (100 - m);
    f = Math.round(f);
	
    document.getElementById('tarif_ressource').value = f;
}

/**
* Cette fonction permet de calculer le chiffre d'affaire pour une ressource dans la calculatrice
*/
function ca2() 
{
    chiffre_affaire = $F('tarif_ressource') * $F('duree_ressource');
    chiffre_affaire = Math.round(chiffre_affaire*100)/100;
    document.getElementById('ca_ressource').value = chiffre_affaire;
}


/**
* Cette fonction permet de calculer le cout pour une ressource dans la partie proposition commerciale d'un type de contrat AT
*/
function coutJ(i) 
{
    if(typeof(i) != 'undefined'){
        i = i;
        number = '&n_r='+i;
    }
    else {
        i = '';
        number = '';
    }
    var url    = '../source/index.php?a=coutJRessource';
    var pars   = 'Id_ressource='+$F('ressource'+i)+number;
    var target = 'coutJ'+i;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet de calculer la marge pour une ressource dans la partie proposition commerciale d'un type de contrat AT
*/
function marge(val) 
{
    var a = parseFloat($F('cout_ressource'+val));
    var b = parseFloat($F('frais_ressource'+val));
    var c = a+b;
    m = 100 * ($F('tarif_ressource'+val) - c) / $F('tarif_ressource'+val);
    m = Math.round(m*100)/100;
    document.getElementById('marge_ressource'+val).value = m;
}

/**
* Cette fonction permet de calculer le chiffre d'affaire pour une ressource dans la partie proposition commerciale d'un type de contrat AT
*/
function ca(val) 
{
    var ress = $F('ressource'+val);
    if(ress == 'MAT' || ress == 'LOG' || ress == 'LIC') {
        chiffre_affaire = $F('tarif_ressource'+val);
    }
    else {
        chiffre_affaire = $F('tarif_ressource'+val) * $F('duree_ressource'+val);
        chiffre_affaire = Math.round(chiffre_affaire*100)/100;
    }
    document.getElementById('ca_ressource'+val).value = chiffre_affaire;
}

/**
* Cette fonction permet d'afficher le cout journalier de la ressource dans le formulaire de saisie d'un contrat d�l�gation lorsque la ressource est s�lectionn�e dans la liste d�roulante
*/
function coutJCD() 
{
    var url    = '../source/index.php?a=coutJCD';
    var pars   = 'Id_ressource='+$F('Id_ressource');
    var target = 'coutJCD';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

function updateDureeRessource(i) {
    if(document.getElementById('debut_ressource'+i) && document.getElementById('fin_ressource'+i)) {
        jourOuvresRessource(i)
    }
    document.getElementById('fin_prev_ressource' + i).value = document.getElementById('fin_ressource' + i).value;
}

function updateTotalRevenue() {
    var nbResources = $('nb_ressource').value;
    var ca = cr = 0;
    var i = 1;
    while (i <= nbResources) {
        calculRessource(i);
        ca += parseFloat($('ca_ressource'+i).value);
        cr += (parseFloat($('cout_ressource'+i).value) + parseFloat($('frais_ressource'+i).value)) * parseFloat($('duree_ressource'+i).value);
        i++;
    }
    $('cha').value = Math.round(ca * 100) / 100;
    $('cout_total').value = Math.round(cr * 100) / 100;
    $('ca_pondere').value = Math.round($('cha').value * ($('ponderation').value / 100))
}

/**
* Cette fonction permet de g�nerer le contrat d�l�gation en PDF
*/
function genererCD(i,j) 
{
    if(!document.getElementById('duree_ressource_i'+i)) {
        ouvre_popup('index.php?a=genererContratDelegation&Id_affaire='+$F('Id_affaire')+'&Id_ressource='+j+'&duree='+$F('duree_ressource'+i)+'&debut_mission='+$F('debut_ressource'+i)+'&fin_mission='+$F('fin_ressource'+i)+'&lieu_mission='+$F('Id_agence')+'&cout_journalier='+$F('tarif_ressource'+i)+'&cout_journalier_ress='+$F('cout_ressource'+i)+'&frais_journalier_ress='+$F('frais_ressource'+i)+'&Id_prop_ress='+$F('id_prop_ress'+i)+'&type_ressource='+$F('type_ressource_prop'+i));
    } else {
        ouvre_popup('index.php?a=genererContratDelegation&Id_affaire='+$F('Id_affaire')+'&Id_ressource='+j+'&duree='+$F('duree_ressource_i'+i)+'&debut_mission='+$F('debut_ressource_i'+i)+'&fin_mission='+$F('fin_ressource_i'+i)+'&lieu_mission='+$F('Id_agence')+'&Id_prop_ress='+$F('id_prop_ress'+i));
    }	
}

/**
 * Envoi des contrats d�l�gations aux services concern�s et recharge les ressources/CD pour affichage
 *
 * @param idCD Identifiant du contrat d�l�gation
 * @param idProposition Identifiant de la proposition
 * @param msg Message affich� dans la confirmation
 *
 * @return string Tableau de ressource/CD
 */
function sendCD(idCD, idProposition, msg) {
    if (confirm(msg)) {
        var url    = '../source/index.php?a=envoyerMailCD';
        var pars   = 'Id_cd='+idCD+'&Id_proposition='+idProposition;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onSuccess: function(transport) {
                if(idProposition == undefined || idProposition == 'undefined') {
                    var json = transport.responseText.evalJSON(true);
                    showInformationMessage(json.msg, 5000);
                    afficherCD();
                }
                else {
                    var json = transport.responseText.evalJSON(true);
                    $('resourceTable').update(json.html);
                    showInformationMessage(json.msg, 5000);
                }
            }
        });
    }
}

/**
* Cette fonction permet d'afficher le formulaire correspondant au type de la r�ponse dans le GO / NOGO
*/
function typeReponse(val) 
{
    var url    = '../source/index.php?a=afficherTypeReponse';
    var pars   = 'Id_affaire='+$F('Id_affaire')+'&reponse='+val;
    var target = 'reponse';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/**
* Cette fonction permet d'afficher le formulaire avec les diff�rentes ind�mnit�s en fonction de la version
*/
function afficherIndemnites(version) 
{
    if($('indemnite_destination').value === 'agence' && ($('indemnite_region').value === 'idf' || $('indemnite_region').value === 'province')) {
        var c = $$('option').find(function(n){return n.value==='petit_deplacement'});
        if(typeof(c) !== 'undefined')
            c.remove();
        var c = $$('option').find(function(n){return n.value==='grand_deplacement'});
        if(typeof(c) !== 'undefined')
            c.remove();
    }
    else {
        var c = $$('option').find(function(n){return n.value==='petit_deplacement'});
        if(typeof(c) === 'undefined')
            $('indemnite_type_deplacement').insert(new Element('option', {value: 'petit_deplacement'}).update('Frais petit d�placement'));
        var c = $$('option').find(function(n){return n.value==='grand_deplacement'});
        if(typeof(c) === 'undefined')
            $('indemnite_type_deplacement').insert(new Element('option', {value: 'grand_deplacement'}).update('Frais grand d�placement'));
    }
    
    if ($('indemnite_destination').value === 'field') {
        $('indemnite_region').value = '';
        $('indemnite_type_deplacement').value = '';
    }
    
    if ($('indemnite_destination').value === 'PWS') {
        $$('option').find(function(n){return n.value==='occasionnel'}).show();
        $$('option').find(function(n){return n.value==='mission'}).show();
        $$('option').find(function(n){return n.value==='idf'}).hide();
        $$('option').find(function(n){return n.value==='province'}).hide();
        
        $$('option').find(function(n){return n.value==='pas_deplacement'}).hide();
        $$('option').find(function(n){return n.value==='petit_deplacement'}).hide();
        $$('option').find(function(n){return n.value==='grand_deplacement'}).hide();
        
        $$('option').find(function(n){return n.text==='Type de d�placement'}).hide();
        $$('option').find(function(n){return n.text==='R�gion'}).hide();
    } 
    else if($('indemnite_destination').value === 'agence') {
        $$('option').find(function(n){return n.value==='occasionnel'}).hide();
        $$('option').find(function(n){return n.value==='mission'}).hide();
        $$('option').find(function(n){return n.value==='idf'}).show();
        $$('option').find(function(n){return n.value==='province'}).show();
        
        $$('option').find(function(n){return n.value==='pas_deplacement'}).show();
        
        $$('option').find(function(n){return n.text==='Type de d�placement'}).show();
        $$('option').find(function(n){return n.text==='R�gion'}).show();
    } 
    else {
        $$('option').find(function(n){return n.value==='occasionnel'}).hide();
        $$('option').find(function(n){return n.value==='mission'}).hide();
        $$('option').find(function(n){return n.value==='idf'}).show();
        $$('option').find(function(n){return n.value==='province'}).show();
        
        $$('option').find(function(n){return n.value==='pas_deplacement'}).show();
        $$('option').find(function(n){return n.value==='petit_deplacement'}).show();
        $$('option').find(function(n){return n.value==='grand_deplacement'}).show();
        
        $$('option').find(function(n){return n.text==='Type de d�placement'}).show();
        $$('option').find(function(n){return n.text==='R�gion'}).show();
    }
    
    var url    = '../source/index.php?a=afficherIndemnites';
    var pars   = 'version='+version+'&Id_cd='+$F('Id_cd');
    if($('type_indemnite'))
        pars += '&type_indemnite='+$F('type_indemnite');
    if($('indemnite_destination'))
        pars += '&indemnite_destination='+$F('indemnite_destination');
    if($('indemnite_region'))
        pars += '&indemnite_region='+$F('indemnite_region');
    if($('indemnite_type_deplacement'))
        pars += '&indemnite_type_deplacement='+$F('indemnite_type_deplacement');
    var target = 'indemnites';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport) {
            showIndemnityBox();
        }
    });
}

/**
* Cette fonction permet de cocher toutes les checkbox de l'interface
*/
function cocherTout(ident) 
{
    var dForm = document.formulaire;
    for (i=0;i<dForm.length;i++)
    {
        var element = dForm[i];
        if (element.type=="checkbox")
        {
            if(!ident) {
                if (element.checked ==false) {
                    element.checked = true;
                }
            }
				
            if (element.id ==ident) {
                element.checked = true;
            }
				
        }
    }
}

/**
* Cette fonction permet de d�cocher toutes les checkbox de l'interface
*/
function decocherTout(ident) 
{
    var dForm = document.formulaire;
    for (i=0;i<dForm.length;i++)
    {
        var element = dForm[i];
        if (element.type=="checkbox")
        {
            if(!ident) {
                if (element.checked ==true) {
                    element.checked = false;
                }
            }
				
            if (element.id ==ident) {
                element.checked = false;
            }
        }
    }
}

/**
* Cette fonction permet de demander la confirmation de son action � l'utilisateur apr�s avoir coch� plusieurs checkbox
*/
function checkedModif(type,object) 
{
    var dForm = document.formulaire;
    var iChecked = 0;
    for (i=0;i<dForm.length;i++) {
        var element = dForm[i];
        if (element.type=="checkbox" && element.checked == true) {
            iChecked++;
        }
    }
	
    if (iChecked > 0) {
        if(type == "supprimer") {
            if (confirm('Etes-vous sur de vouloir supprimer ?')) {
                document.forms["formulaire"].elements["class"].value  = object
                document.forms["formulaire"].elements["action"].value = type
                dForm.submit();
            }
        }
    } else {
        alert('Vous devez s�lectionner un item !')
    }
}

/*******************************************************************/
/**                FONCTIONS POUR LE POLE FORMATION              ***/
/*******************************************************************/

//Fonction toggle g�n�ralisable
function toggleZone(balise)
{
    new Effect.toggle(balise,'slide');
}

function toggleNews(balise)
{
    new Effect.toggle(balise,'slide');
    if($(balise).getStyle("display")=="none")
        $(balise+'Image').writeAttribute("src", "../ui/images/moins.gif");
    else
        $(balise+'Image').writeAttribute("src", "../ui/images/plus.gif");
}

//fonction pour ouvrir une fen�tre en pop up de taille inf�rieur � l'�cran
function ouvre_popup2(url, fenetre) 
{
    propriete  = 'top=0,left=0,resizable=yes, toolbar=no, scrollbars=yes, menubar=yes, location=yes, statusbar=no'
    propriete += ',width=' + (screen.width*0.85) + ',height=' + (screen.height*0.85);
    window.open(url,fenetre, propriete)
}

/** FONCTIONS ASSOCIEES A UNE SESSION  **/

function linkToOpportunity(idSession, idOpportunity) {
    new Ajax.Updater('opportunite', '../source/index.php?a=linkToOpportunity', {
            method: 'get',
            evalScripts: true,
            parameters: 'Id_session='+idSession+'&Id_opportunity='+idOpportunity,
            onSuccess: function(transport) {
                showInformationMessage('Opportunit� ajout�e avec succ�s.', 5000);
            }
        });
}

//V�rification des donn�es saisies avant l'enregistrement ou la modification d'une session
function verifSession(form)
{
    //V�rification que le nom de la session est bien remplie
    if (form.nom_session.value == '') {
        alert("Veuillez saisir un nom de session.");
        form.nom_session.focus();
        return false;
    }
	
    //v�rification des dates et traitement des donn�es uniquement si la date de d�but et la date de fin sont saisies
    var debut = form.dateDebut.value;
    var fin   = form.dateFin.value;
    if ((debut && fin) && (debut != "00-00-0000") && (fin != "00-00-0000") 
        && ((form.nb_Jour.value == '') || (form.nb_Jour.value == 0))) {
        if (!joursOuvresSession(0)) {
            return false;
        } else {
            form.nb_Jour.value = joursOuvresSession(0);
        }
    }
    //V�rifier qu'il n'y a pas d'autres dates de saisie si les dates de d�but et de fin ne sont pas saisies
    else if ( (debut == "00-00-0000") || (fin == "00-00-0000") || (debut == '') || (fin == '')) {
        //Traitement des dates ponctutelles
        var nb_date = parseInt($F('nb_Date'));
        var i 		= 1;
        while (i <= nb_date) {
            var date = $F('date'+i);
            if (date != '') {
                alert ('Attention vous avez saisie des dates sans saisir les dates de d�but et de fin de la session');
                return false;
            }
            ++i;
        }
        //Traitement des p�riode interm�diaires
        var nb_periode = parseInt($F('nb_Periode'));
        i = 1;
        while (i <= nb_periode) {
            var debut_periode = $F('periode_debut'+i);
            var fin_periode   = $F('periode_fin'+i);
            if (((debut_periode != '') && (fin_periode == '')) || ((debut_periode == '') && (fin_periode != ''))
                || ((debut_periode != '') && (fin_periode != ''))) {
                alert('Attention vous avez saisie des dates sans saisir les dates de d�but et de fin de la session');
                return false;
            }
            ++i;
        }
    }
	
    //V�rification que le code postal ne fasse que 5 caract�res
    if (form.code_postal.value != '') {
        var cp 	  = form.code_postal.value;
        var longr = cp.length;
        if (longr != 5) {
            alert("Attention le code postal ne fait pas cinq caract�res");
            form.code_postal.focus();
            return false;
        }
    }
	
    //D�blocage des champs ca, charge et marge
    form.charge.disabled = false;
    form.ca.disabled     = false;
    form.marge.disabled  = false;
    //Toutes les charges non saisie sont pass�es � z�ro
    if (form.coutFormateurJ.value == '') {
        form.coutFormateurJ.value = 0;
    }
    if (form.coutFormateur.value == '') {
        form.coutFormateur.value = 0;
    }
    if (form.coutSalleJ.value == '') {
        form.coutSalleJ.value = 0;
    }
    if (form.coutSalle.value == '') {
        form.coutSalle.value = 0;
    }
    if (form.coutSupportU.value == '') {
        form.coutSupportU.value = 0;
    }
    if (form.coutSupport.value == '') {
        form.coutSupport.value = 0;
    }
    if (form.autreFrais.value == '') {
        form.autreFrais.value = 0;
    }
    //Modification des virgules en point dans les diff�rentes charges
    form.nb_Jour.value 			= parseFloat(form.nb_Jour.value.replace(',','.'));
    form.coutFormateurJ.value 	= parseFloat(form.coutFormateurJ.value.replace(',','.'));
    form.coutFormateur.value 	= parseFloat(form.coutFormateur.value.replace(',','.'));
    form.coutSalleJ.value 		= parseFloat(form.coutSalleJ.value.replace(',','.'));
    form.coutSalle.value 		= parseFloat(form.coutSalle.value.replace(',','.'));
    form.coutSupportU.value 	= parseFloat(form.coutSupportU.value.replace(',','.'));
    form.coutSupport.value 		= parseFloat(form.coutSupport.value.replace(',','.'));
    form.autreFrais.value 		= parseFloat(form.autreFrais.value.replace(',','.'));
    //V�rification que toutes les charges sont des r�els
    if (isNaN(form.coutFormateurJ.value) || isNaN(form.coutFormateur.value)
        || isNaN(form.coutSalleJ.value) || isNaN(form.coutSalle.value)
        || isNaN(form.coutSupportU.value) ||isNaN(form.coutSupport.value)
        || isNaN(form.autreFrais.value)) {
        alert('attention certaines charges ne sont pas des chiffres');
        return false;
    }
    //verification que toutes les charges sont positives
    if ((form.coutFormateurJ.value < 0) || (form.coutFormateur.value < 0)
        || (form.coutSalleJ.value < 0) || (form.coutSalle.value < 0)
        || (form.coutSupportU.value < 0) || (form.coutSupport.value < 0)
        || (form.autreFrais.value < 0)) {
        alert("Attention il y a des charges n�gatives");
        return false;
    }
    return(true);
}

//Mise � jour de la liste des sessions affich�es selon les crit�res s�lectionn�s
function afficherSession(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterSession';
    pars   += '&Id_session='+$F('Id_session')+'&nom_session='+$F('nom_session')+'&ville='+$F('ville')+'&type_session='+$F('type_session')
           +'&reference_affaire='+$F('reference_affaire')+'&Id_intitule='+$F('Id_intitule')+'&debut='+$F('debut')+'&fin='+$F('fin');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/** Calcul de dur�e **/
//Calcul du nombre de jours d'une session
function joursOuvresSession(cas) 
{
    var debut = $F('dateDebut');
    var fin   = $F('dateFin');
	
    //Traitement des donn�es uniquement si la date de d�but et la date de fin sont saisies
    if ((debut && fin) && (debut!="00-00-0000") && (fin!="00-00-0000")) {
        var duree = 0;
        var nb    = 0;
        var pars2 = '';
        var i 	  = 1;
        //v�rification que les dates de d�but et de fin sont correctement saisies et que se ne sont pas des jours ferm�s
        if (!CheckDate(debut)) {
            return false;
        }
        var j		= (debut.substring(0,2));
        var m		= (debut.substring(3,5));
        var a		= (debut.substring(6));
        var d_debut = new Date (a, m-1,j);
        if ((d_debut.getDay()== 0) || (d_debut.getDay() == 6)) {
            alert ('Attention la date de d�but est un jour de week-end');
            return false;
        }
        var jFeries = JoursFeries(a);
        for (k = 0; k < jFeries.length; k++) {
            if (Date.parse(jFeries[k]) == Date.parse(d_debut)) {
                alert ('Attention la date de d�but correspond � un jour f�ri�');
                return false;
            }
        }
        if(!CheckDate(fin)) {
            return false;
        }
        var j	  = (fin.substring(0,2));
        var m	  = (fin.substring(3,5));
        var a	  = (fin.substring(6));
        var d_fin = new Date (a, m-1,j);
        if ((d_fin.getDay() == 0) || (d_fin.getDay() == 6)) {
            alert ('Attention la date de fin est un jour de week-end');
            return false;
        }
        var jFeries = JoursFeries(a);
        for (k = 0; k < jFeries.length; k++) {
            if (Date.parse(jFeries[k]) == Date.parse(d_fin)) {
                alert ('Attention la date de fin correspond � un jour f�ri�');
                return false;
            }
        }
        //Cas o� les dates de d�but et de fin sont les m�mes pour les formations d'une journ�e
        if (debut == fin ) {
            if ($F('date'+i) || $F('periode_debut'+i) || $F('periode_fin'+i)) {
                alert('Attention il y a une erreur dans la saisie des dates');
                return false;
            }
            duree = jourOuvres(debut, fin);
        } else if (compareDate(fin,debut)) {	//V�rification que les dates de d�but et de fin de session sont dans le bon ordre
            alert('Attention la date de fin est avant la date d�but');
            return false
        } else {
            //Cas o� les dates de d�but et de fin sont diff�rentes, bien saisies et dans le bon ordre :
            //calcul du nombre de jour
            //Pr�sence de dates ou p�riodes interm�daires :calcul du nombre de jours de chaques interm�diaires
            var intermediaire = false;
            var nb_debut 	  = 0; 	//variable permettant de v�rifier si la date de d�but est saisie plusieurs fois
            var nb_fin   	  = 0;	//variable permettant de v�rifier si la date de fin est saisie plusieurs fois
				
            //Traitement des dates ponctutelles
            var nb_date = parseInt($F('nb_Date'));
            while (i <= nb_date) {
                var date = $F('date'+i);
                //cas o� la date est diff�rente de celle de d�but et de fin
                if (date != '') {
                    //v�rification que la date est bien saisie
                    if (!CheckDate(date)) {
                        return false;
                    }
                    var j = (date.substring(0,2));
                    var m = (date.substring(3,5));
                    var a = (date.substring(6));
                    var d = new Date (a, m-1,j);
                    if ((d.getDay()== 0) || (d.getDay() == 6)) {
                        alert ('Attention une des dates ponctuelles est un jour ferm�');
                        return false;
                    }
                    var jFeries = JoursFeries(a);
                    for (k = 0; k < jFeries.length; k++) {
                        if (Date.parse(jFeries[k]) == Date.parse(d)) {
                            alert ('Attention une des dates ponctuelles correspond � un jour f�ri�');
                            return false;
                        }
                    }
                    intermediaire = true;
					
                    if ((date != debut) && (date != fin)) {
                        //v�rification que la date est bien comprise entre les dates de d�but et de fin
                        if (compareDate(date, debut) || compareDate(fin, date)) {
                            alert('Attention une des dates ponctuelles n\'est pas comprise dans la session');
                            return false;
                        } else {
                            //V�rification que la date n'a pas d�j� �t� saisie
                            var j = 1;
                            while (j < i) {
                                var date2 = $F('date'+j);
                                if ((date2 != '') && (date2 == date)) {
                                    alert('Attention plusieurs dates identiques ont �t� saisies');
                                    return false;
                                }
                                ++j;
                            }
                        }
                        ++duree;
                    } else if (date == debut) {	//cas o� la date est �gale � celle de d�but
                        if (nb_debut == 1) {
                            alert('Attention plusieurs dates identiques ont �t� saisies');
                            return false;
                        } else {
                            ++nb_debut;
                        }
                    } else if (date == fin) {	//cas o� la date est �gale � celle de fin
                        if (nb_fin == 1) {
                            alert('Attention plusieurs dates identiques ont �t� saisies');
                            return false;
                        } else {
                            ++nb_fin;
                        }
                    }
                }
                ++i;
            }
				
            //Traitement des p�riode interm�diaires
            var nb_periode = parseInt($F('nb_Periode'));
            i = 1;
            while (i <= nb_periode) {
                var debut_periode = $F('periode_debut'+i);
                var fin_periode   = $F('periode_fin'+i);
                //calcul du nombre de jours que si les dates de d�but et de fin de la p�riode sont saisies
                if (((debut_periode != '') && (fin_periode == '')) || ((debut_periode == '') && (fin_periode != ''))) {
                    alert('Attention il y a une erreur dans la saisie des dates des p�riodes');
                } else if ((debut_periode != '') && (fin_periode != '')) {
                    intermediaire = true;
                    //V�rification que les dates sont correctement saisies
                    if (!CheckDate(debut_periode)) {
                        return false;
                    }
                    if (!CheckDate(fin_periode)) {
                        return false;
                    }
                    //V�rification que les dates de la p�riode sont dans l'ordre chronologique et comprise dans les dates de la session
                    if (compareDate(fin_periode, debut_periode) || compareDate(debut_periode, debut)
                        || compareDate(fin, fin_periode)) {
                        alert('Attention il y a une erreur dans la saisie des dates des p�riodes');
                        return false;
                    }
                    //Traitement des p�riodes comprenant la date de d�but ou la date de fin de la session
                    if (debut_periode == debut) {
                        if (nb_debut == 1) {
                            alert('Attention plusieurs dates identiques ont �t� saisies');
                            return false;
                        } else {
                            ++nb_debut;
                            --duree;
                        }
                    }
                    if (fin_periode == fin) {
                        if (nb_fin == 1) {
                            alert('Attention plusieurs dates identiques ont �t� saisies');
                            return false;
                        } else {
                            ++nb_fin;
                            --duree;
                        }
                    }
                    //V�rification, si il y a des dates ponctuelles saisies qu'elles ne sont pas dans la p�riode
                    var j = 1;
                    while (j <= nb_date) {
                        var date = $F('date'+j);
                        if(date != '') {
                            if ((compareDate(debut_periode, date) && compareDate(date, fin_periode))
                                || (date == debut_periode) || (date == fin_periode)) {
                                alert('Attention des dates ponctuelles et des p�riodes se recouvrent');
                                return false;
                            }
                        }
                        ++j;
                    }
                    //V�rification que la p�riode n'a pas d�j� �t� saisie
                    j = 1;
                    while (j < i) {
                        var debut_periode2 = $F('periode_debut'+j);
                        var fin_periode2   = $F('periode_fin'+j);
                        if((debut_periode2 != '') && (fin_periode2 != '')) {
                            if ((debut_periode2 == debut_periode) && (fin_periode2 == fin_periode)) {
                                alert('Attention plusieurs p�riodes identiques ont �t� saisies');
                                return false;
                            }
                            if ((compareDate(debut_periode, debut_periode2) && compareDate(debut_periode2, fin_periode))
                                || (debut_periode2 == debut_periode) || (debut_periode2 == fin_periode)) {
                                alert('Attention certaines p�riodes se recouvrent');
                                return false;
                            }
                            if ((compareDate(debut_periode, fin_periode2) && compareDate(fin_periode2, fin_periode))
                                || (fin_periode2 == debut_periode) || (fin_periode2 == fin_periode)) {
                                alert('Attention certaines p�riodes se recouvrent');
                                return false;
                            }
                        }
                        ++j;
                    }
                    duree += jourOuvres(debut_periode, fin_periode);
                }
                ++i;
            }
            duree += 2; //ajout de 2 jours � la dur�e pour tenir compte des dates de d�but et de fin
			
            //Absence de dates ou p�riodes interm�daires : calcul du nombre de jours ouvr�s entre la date de d�but et celle de fin
            if (!intermediaire) {
                duree = jourOuvres(debut, fin);
            }
        }
        if (cas == 1) {
            var url    = '../source/index.php?a=joursOuvresSession';
            var pars   = 'duree='+duree;
            var target = 'nombre_Jour';
            new Ajax.Updater(target, url, {
                method: 'get',
                parameters: pars,
                onlyLatestOfClass:getFunctionName(arguments.callee.toString())
            });
        }
        else {
            return duree;
        }
    }
}

//fonction calculant le nombre de jours ouvr�s entre deux dates
function jourOuvres(d_debut, d_fin)
{
    var j_debut	= (d_debut.substring(0,2));
    var m_debut	= (d_debut.substring(3,5));
    var a_debut	= (d_debut.substring(6));
    var d 		= new Date (a_debut, m_debut-1,j_debut);
	
    var j_fin 	= (d_fin.substring(0,2));
    var m_fin 	= (d_fin.substring(3,5));
    var a_fin 	= (d_fin.substring(6));
    var fin   	= new Date (a_fin, m_fin-1,j_fin);
    var nb_J  	= 0;
	
    //calcul des dates des jours f�ri�s de l'ann�e de la date de d�but
    var jFeries = JoursFeries(a_debut);
    //ajouter au tableau les jours f�ri�s de l'ann�e de loa date de fin si celle-ci est diff�rente de celle de la date de d�but
    if (a_debut != a_fin) {
        jFeries = jFeries.concat(JoursFeries(a_fin));
    }
    while (Date.parse(d) <= Date.parse(fin)) {
        //addition du jour uniquement si il s'agit d'un jour de la semaine
        if ((d.getDay() != 0) && (d.getDay() != 6)) {
            ++nb_J;
            //suppr�ssion des jours f�ri�s du nombre de jour
            for (i = 0; i < jFeries.length; i++) {
                if(Date.parse(jFeries[i]) == Date.parse(d)) {
                    --nb_J;
                }
            }
        }
		
        //calcul de la date suivante
        if (parseInt(j_debut) == 0) {
            //pour les p�riode commen�ant le 08 ou le 09 d'un mois, r�cup�ration uniquement du deuxi�me caract�re
            //(sinon  l'entier �quivalent est un 0 et fait recommenc� le compte au premier du mois)
            j_debut = (d_debut.substring(1,2));
        }
        j_debut = parseInt(j_debut) + parseInt(1);
        d  		= new Date(a_debut, m_debut-1,  j_debut);
    }
    return nb_J;
}
/*
A VOIR SI L'ON CONSERVE OU PAS

function dateRessource(i) 
{
    if($F('duree')) {
        document.getElementById('duree_ressource'+i).value = $F('duree');
	}
    if($F('date_debut')) {
        document.getElementById('debut_ressource'+i).value = $F('date_debut');
	}
    if($F('date_fin_commande')) {
        document.getElementById('fin_ressource'+i).value = $F('date_fin_commande');
	}
    if($F('date_fin_previsionnelle')) {
		document.getElementById('fin_prev_ressource'+i).value = $F('date_fin_previsionnelle');
	}
}


function dateRessourceInclus(i) 
{
    if($F('duree')) {
        document.getElementById('duree_ressource_i'+i).value = $F('duree');
	}
    if($F('date_debut')) {
        document.getElementById('debut_ressource_i'+i).value = $F('date_debut');
	}
    if($F('date_fin_commande')) {
        document.getElementById('fin_ressource_i'+i).value = $F('date_fin_commande');
	}
    if($F('date_fin_previsionnelle')) {
		document.getElementById('fin_prev_ressource_i'+i).value = $F('date_fin_previsionnelle');
	}	
}

*/

//fonction calculant le nombre de jours ouvr�s entre deux dates pour une ressource
function jourOuvresRessource(id_ressource)
{
    date_deb = document.getElementById('debut_ressource'+id_ressource).value;
    date_fin = document.getElementById('fin_ressource'+id_ressource).value;
    //document.getElementById('duree_ressource'+id_ressource).value = jourOuvres(date_deb,date_fin);
    var url    = '../source/index.php?a=joursOuvresRessource';
    var pars   = 'debut='+date_deb+'&fin='+date_fin+'&Id='+id_ressource;
    var target = 'duree'+id_ressource;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(req) {
            $('fin_ressource'+id_ressource).fire("date:frobbed", {
                widgetNumber: 19
            });
        }
    });
}


//fonction calculant le nombre de jours ouvr�s entre deux dates pour une ressource incluse
function jourOuvresRessourceInclus(id_ressource)
{
    date_deb = document.getElementById('debut_ressource_i'+id_ressource).value;
    date_fin = document.getElementById('fin_ressource_i'+id_ressource).value;
    //document.getElementById('duree_ressource_i'+id_ressource).value = jourOuvres(date_deb,date_fin);
	
    var url    = '../source/index.php?a=joursOuvresRessourceInclus';
    var pars   = 'debut='+date_deb+'&fin='+date_fin+'&Id='+id_ressource;
    var target = 'duree_i'+id_ressource;
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}


//fonction calculant les jours f�ri�s 
function JoursFeries(an)
{
    var JourAn = new Date(an, "00", "01");
    var FeteTravail = new Date(an, "04", "01");
    var Victoire1945 = new Date(an, "04", "08");
    var FeteNationale = new Date(an,"06", "14");
    var Assomption = new Date(an, "07", "15");
    var Toussaint = new Date(an, "10", "01");
    var Armistice = new Date(an, "10", "11");
    var Noel = new Date(an, "11", "25");
	
    var G = an%19;
    var C = Math.floor(an/100);
    var H = (C - Math.floor(C/4) - Math.floor((8*C+13)/25) + 19*G + 15)%30;
    var I = H - Math.floor(H/28)*(1 - Math.floor(H/28)*Math.floor(29/(H + 1))*Math.floor((21 - G)/11));
    var J = (an*1 + Math.floor(an/4) + I + 2 - C + Math.floor(C/4))%7;
    var L = I - J;
    var MoisPaques = 3 + Math.floor((L + 40)/44);
    var JourPaques = L + 28 - 31*Math.floor(MoisPaques/4);
    var Paques = new Date(an, MoisPaques-1, JourPaques);
    var LundiPaques = new Date(an, MoisPaques-1, JourPaques+1);
    var Ascension = new Date(an, MoisPaques-1, JourPaques+39);
    var Pentecote = new Date(an, MoisPaques-1, JourPaques+49);
    var LundiPentecote = new Date(an, MoisPaques-1, JourPaques+50);
 
    return new Array(JourAn, Paques, LundiPaques, FeteTravail, Victoire1945, Ascension, Pentecote, LundiPentecote, FeteNationale, Assomption, Toussaint, Armistice, Noel);
}

/** Calcul de co�ts **/
//Calcul du cout total du formateur � partir du cout journalier et mise � jour des charges et de la marge
function calculcoutFTotal()
{
    calculcoutFTotal2();
    //calcul et affichage charge, ca
    chargeTotalSession(1);
}

//Calcul du co�t total du formateur selon la dur�e de la formation
function calculcoutFTotal2()
{
    var nbJ = parseFloat($F('nb_Jour').replace(',','.'));
    if (nbJ != '') {
        var url    = '../source/index.php?a=calculcoutFTotal';
        var pars   = 'coutJ='+parseFloat($F('coutFormateurJ').replace(',','.'))+'&nbJ='+nbJ;
        var target = 'coutFormateurTotal';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//Calcul du cout total de la salle � partir du cout journalier et mise � jour des charges et de la marge
function calculcoutSTotal()
{
    calculcoutSTotal2();
    //calcul et affichage charge, ca
    chargeTotalSession(2);
}

//Calcul du co�t total du formateur selon la dur�e de la formation
function calculcoutSTotal2()
{
    var nbJ = parseFloat($F('nb_Jour').replace(',','.'));
    if (nbJ != '') {
        var url    = '../source/index.php?a=calculcoutSTotal';
        var pars   = 'coutJ='+parseFloat($F('coutSalleJ').replace(',','.'))+'&nbJ='+nbJ;
        var target = 'coutSalleTotal';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//Calcul du cout total des support � partir du cout unitaire et mise � jour des charges et de la marge
function calculcoutSupTotal()
{
    calculcoutSupTotal2();
    //calcul et affichage charge, ca
    chargeTotalSession(3);
}

//Calcul du co�t total des formateurs selon le nombre d'inscrits � la session
function calculcoutSupTotal2()
{
    var nb     = $F('nb_Inscrits');
    nb         = parseInt(nb);
    var coutU  = parseFloat($F('coutSupportU').replace(',','.'));
    var url    = '../source/index.php?a=calculcoutSupTotal';
    var pars   = 'coutUnitaire='+coutU+'&nbInscrit='+nb;
    var target = 'coutSupportTotal';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Lors d'un clique sur le bouton mise � jour, calcul des co�ts totaux du formateur et de la salle selon la dur�e de la session
function calculCout()
{
    //permet de d�finir le cas pour le fonction chargeTotalSession, par d�faut cas = 0 r�cup�re que les co�t totaux sans calcul
    var cas = 0;
    //affichage co�ts totaux formateur, salle et support
    if(($F('coutFormateurJ') != 0) && ($F('coutFormateurJ') != '')) {
        calculcoutFTotal2();
        cas = 1; //dans chargeTotalSession, cas 1 : calcul du co�t du formateur � partir du nb de jour
    }
    if(($F('coutSalleJ')!= 0) && ($F('coutSalleJ') != '')) {
        calculcoutSTotal2();
        if (cas == 0) {
            cas = 2; //dans chargeTotalSession, cas 2 : calcul du co�t de la salle mais pas du formateur � partir du nb de jour
        }
        else {
            cas = 4; //dans chargeTotalSession, cas 4 : calcul du co�t de la salle et du formateur � partir du nb de jour
        }
    }
    //calcul et affichage charge, ca
    chargeTotalSession(cas);
}

//Calcul des charges totales en fonctions du d�tail des co�ts
function chargeTotalSession(cas) 
{
    //Si la fonction est appel�e lors de la saisie du co�t journalier du formateur
    if (cas == 1) {
        var formateurJ 	= parseFloat($F('coutFormateurJ').replace(',','.'));
        var nbJ 		= parseFloat($F('nb_Jour').replace(',','.'));
        var formateur 	= nbJ * formateurJ;
        var salle 		= parseFloat($F('coutSalle').replace(',','.'));
        var support 	= parseFloat($F('coutSupport').replace(',','.'));
    }
    //Si la fonction est appel�e lors de la saisie du co�t journalier de la salle
    else if (cas == 2) {
        var salleJ 		= parseFloat($F('coutSalleJ').replace(',','.'));
        var nbJ 		= parseFloat($F('nb_Jour').replace(',','.'));
        var salle 		= nbJ * salleJ;
        var formateur 	= parseFloat($F('coutFormateur').replace(',','.'));
        var support 	= parseFloat($F('coutSupport').replace(',','.'));
    }
    //Si la fonction est appel�e lors de la saisie du co�t unitaire du support
    else if (cas == 3) {
        var coutU 		= parseFloat($F('coutSupportU').replace(',','.'));
        var nb 			= $F('nb_Inscrits');
        var support 	= nb * coutU;
        var formateur 	= parseFloat($F('coutFormateur').replace(',','.'));
        var salle 		= parseFloat($F('coutSalle').replace(',','.'));
    }
    //Si la fonction est appel�e par la bouton de mise � jour (utilis� notamment apr�s un changement au planning)
    else if (cas == 4) {
        var nbJ 		= parseFloat($F('nb_Jour').replace(',','.'));
        var formateurJ 	= parseFloat($F('coutFormateurJ').replace(',','.'));
        var formateur 	= nbJ * formateurJ;
        var salleJ 		= parseFloat($F('coutSalleJ').replace(',','.'));
        var salle 		= nbJ * salleJ;
        var support 	= parseFloat($F('coutSupport').replace(',','.'));
    }
    else {
        var formateur 	= parseFloat($F('coutFormateur').replace(',','.'));
        var salle     	= parseFloat($F('coutSalle').replace(',','.'));
        var support   	= parseFloat($F('coutSupport').replace(',','.'));
    }
    var autre   = parseFloat($F('autreFrais').replace(',','.'));
    var supForm = parseFloat($F('coutSupForm').replace(',','.'));
	
    //calcul des charges par addition de tous les co�ts
    var charge = 0;
    if(formateur) {
        charge += formateur;
    }
    if(salle) {
        charge += salle;
    }
    if(support) {
        charge += support;
    }
    if(supForm) {
        charge += supForm;
    }
    if(autre) {
        charge += autre;
    }
    charge = parseFloat(parseInt(charge*100))/100;
	
    //r�cup du ca de la session et calcul de la marge si il y a un ca
    var ca = parseInt($F('ca').replace(',','.'));
    if ((ca != "") && (ca != 0)) {
        var marge = ((ca - charge)*100)/ca;
        marge     = parseFloat(parseInt(marge*100))/100;
    }
    else {
        var marge = 0;
    }
    //affichage des informations dans la chargeSession par Ajax
    var url    = '../source/index.php?a=chargeSession';
    var pars   = 'charge='+charge+'&marge='+marge+'&ca='+ca;
    var target = 'chargeSession';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}
 
//R�cup�ration du salaire journalier du formateur lors de sa s�lection (si un seul formateur s�lectionn�) 
//et calcul du co�t total selon la dur�e de la session
function prixFormateur()
{
    var nb_F 	= $F('nb_formateur');
    var i 		= 1;
    var nb_prof = 0;
    var j 		= 1;
    while (i <= nb_F) {
        var formateur = $F('formateur'+i);
        if(formateur != '') {
            ++nb_prof;
            j = i;
        }
        ++i;
    }
    if (nb_prof < 2) {
        formateur = $F('formateur'+j);
        var pars2 = '';
    }
    else {
        formateur = -1;
        var pars2 = '&total='+$F('coutFormateur');
    }
    majCharge(1, formateur, pars2);
    var pars   = 'formateur='+formateur+'&nbJ='+parseFloat($F('nb_Jour').replace(',','.'))+pars2;
    var url    = '../source/index.php?a=prixFormateur';
    var target = 'coutF';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//R�cup�ration du salaire journalier du formateur lors de sa s�lection (si un seul formateur s�lectionn�) 
//et calcul du co�t total selon la dur�e de la session
function prixSalle()
{
    var nb_S 	 = $F('nb_formateur');
    var i 		 = 1;
    var nb_salle = 0;
    var j 		 = 1;
    while (i <= nb_S) {
        var salle = $F('salle'+i);
        if(salle != '') {
            ++nb_salle;
            j = i;
        }
        ++i;
    }
    if (nb_salle < 2) {
        salle 	  = $F('salle'+j);
        var pars2 = '';
    }
    else {
        salle 	  = -1;
        var pars2 = '&total='+$F('coutSalle');
    }
    majCharge(2, salle, pars2);
    var pars 	= 'salle='+salle+'&nbJ='+parseFloat($F('nb_Jour').replace(',','.'))+pars2;
    var url 	= '../source/index.php?a=prixSalle';
    var target 	= 'coutS';
    var myAjax 	= new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Mise � jour de la charge total et de la marge de la session suite � la selection d'un formateur ou d'une salle
function majCharge (cas, identifiant, pars2)
{
    var pars3 = '';
    //mise � jour suite � la selection d'une salle : calcul du co�t des charges pour le formateur et transfert
    //de l'identifiant de la salle s�lectionn�e
    if (cas == 2) {
        var formateurJ = parseFloat($F('coutFormateurJ').replace(',','.'));
        var nbJ 	   = parseFloat($F('nb_Jour').replace(',','.'));
        var formateur  = nbJ * formateurJ;
        pars3		   = '&formateur='+formateur;
    }
    //mise � jour suite � la selection d'un formateur : calcul du co�t des charges pour la salle et transfert
    //de l'identifiant du formateur s�lectionn�
    else if (cas == 1) {
        var salleJ = parseFloat($F('coutSalleJ').replace(',','.'));
        var nbJ    = parseFloat($F('nb_Jour').replace(',','.'));
        var salle  = nbJ * salleJ;
        pars3      = '&salle='+salle;
    }
	 
    pars3 += '&autre='+parseFloat($F('autreFrais').replace(',','.'))+'&support='+parseFloat($F('coutSupport').replace(',','.'))+
    '&ca='+parseFloat($F('ca').replace(',','.'))+'&supportF='+parseFloat($F('coutSupForm').replace(',','.'));
	
    var pars   = 'cas='+cas+'&identifiant='+identifiant+'&nbJ='+parseFloat($F('nb_Jour').replace(',','.'))+pars2+pars3;
    var url    = '../source/index.php?a=majCharge';
    var target = 'chargeSession';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Nouveau calcul de charge et de marge pour les affaires, pour afficher dans la session par le bouton 'Calcul affaire'
function calculAffaire()
{
    var charge = parseFloat($F('charge').replace(',','.'));
    var url    = '../source/index.php?a=calculAffaire';
    var pars   = 'charge='+charge+'&Id_propSession='+$F('Id_propSession');
    var target = 'tableauAffaire';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/** Ajout/Suppression d'�l�ments **/
//Ajout d'un champs formateur et d'un champs salle � une session
function ajoutFormateurSalle() 
{
    var nb   = $F('nb_formateur');
    var url  = '../source/index.php?a=ajoutFormateur';
    var pars = 'nb='+nb;
    nb++;
    var target = 'autreFormateur'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Suppression d'un champs formateur et d'un champs salle � une session
function enleveFormateurSalle() 
{
    var nb = $F('nb_formateur');
    if (nb > 1) {
        var url    = '../source/index.php?a=supprFormateur';
        var pars   = 'nb='+nb;
        var target = 'autreFormateur'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//Ajout de dates ponctuelles � une session
function ajoutDateSession() 
{
    var nb   = $F('nb_Date');
    var url  = '../source/index.php?a=ajoutDateSession';
    var pars = 'nb='+nb;
    nb++;
    var target = 'autreDate'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Suppression de dates ponctuelles � une session
function enleveDateSession() 
{
    var nb = $F('nb_Date');
    if (nb > 1) {
        var url    = '../source/index.php?a=supprDateSession';
        var pars   = 'nb='+nb;
        var target = 'autreDate'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//Ajout de p�riodes interm�diaires � une session
function ajoutPeriodeSession() 
{
    var nb   = $F('nb_Periode');
    var url  = '../source/index.php?a=ajoutPeriodeSession';
    var pars = 'nb='+nb;
    nb++;
    var target = 'autrePeriode'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Suppression de p�riodes interm�diaires � une session
function enlevePeriodeSession() 
{
    var nb = $F('nb_Periode');
    if (nb > 1) {
        var url    = '../source/index.php?a=supprPeriodeSession';
        var pars   = 'nb='+nb;
        var target = 'autrePeriode'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//Mise � jour des listes des formateurs et des salles dans la session
function majListe(nb)
{
    var url    = '../source/index.php?a=majFormateur';
    var pars   = 'nb='+nb+'&formateur='+$F('formateur'+nb)+'&salle='+$F('salle'+nb);
    var target = 'Dformateur'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Dans la session possibilit� de bloquer ou d�bloquer des �l�ments de la checklist
function barrer(numero, valeur, row) 
{
    var rmq    = $F('rmq'+numero);
    var url    = '../source/index.php?a=barrer';
    var pars   = 'numero='+numero+'&valeur='+valeur+'&row='+row+'&rmq='+rmq;
    var target = 'logistique'+numero;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/** FONCTIONS ASSOCIEES A UN FORMATEUR  **/
 
//V�rification des donn�es saisies avant l'enregistrement ou la modification d'un formateur
function verifFormateur(form)
{
    //V�rification que le nom du formateur est bien rempli
    if(form.nom_formateur.value == "") {
        alert("Veuillez saisir le nom du formateur.");
        form.nom_formateur.focus();
        return false;
    }
	
    //Si le salaire est remplie, v�rification que la saisie est correct (un r�el prositif ou nul)
    if (form.salaire.value != '') {
        form.salaire.value = parseFloat(form.salaire.value.replace(',','.'));
        if((isNaN(form.salaire.value)) || (form.salaire.value < 0)) {
            alert('attention le salaire est mal saisi');
            return false;
        }
    }
    return true;
}

//Mise � jour de la liste des formateurs affich�es selon les crit�res s�lectionn�s
function afficherFormateur(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterFormateur'; 
    pars   += '&nb='+$F('nb')+'&Id_formateur='+$F('Id_formateur')+'&nom_formateur='+$F('nom_formateur')
    +'&type_salaire='+$F('type_salaire')+'&salaire='+$F('salaire')
    +'&competence='+$F('competence');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/** FONCTIONS ASSOCIEES A UNE SALLE  **/

//V�rification des donn�es saisies avant l'enregistrement ou la modification d'une salle
function verifSalle(form)
{
    //V�rification que le nom de la salle est bien saisie
    if(form.nom_salle.value == '') {
        alert("Veuillez saisir le nom de la salle.");
        form.nom_salle.focus();
        return false;
    }
		
    //V�rification que le code postal ne fait pas plus de 5 caract�res
    if(form.code_postal.value != '') {
        var cp 	  = form.code_postal.value;
        var longr = cp.length;
        if (longr != 5) {
            alert("Attention le code postal ne fait pas cinq caract�res");
            form.code_postal.focus();
            return false;
        }
    }
	
    //Si le prix est remplie, v�rification que la saisie est correct (un r�el prositif ou nul)
    if (form.prix.value != '') {
        form.prix.value = parseFloat(form.prix.value.replace(',','.'));
        if((isNaN(form.prix.value)) || (form.prix.value < 0)) {
            alert('attention le prix est mal saisi');
            return false;
        }
    }
	
    return true;
}

//Mise � jour de la liste des salles affich�es selon les crit�res s�lectionn�s
function afficherSalle(info) 
{
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    var url    = '../source/index.php?a=consulterSalle';
    pars   += '&nb='+$F('nb')+'&Id_salle='+$F('Id_salle')+'&nom_salle='+$F('nom_salle')+'&lieu='+$F('lieu')
    +'&type_prix='+$F('type_prix')+'&prix='+$F('prix')+'&ville='+$F('ville');
    var target = 'page';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/** FONCTIONS ASSOCIEES AUX AFFAIRES DU POLE FORMATION  **/

/** Ajout/Suppression d'�l�ments **/
//Ajout des champs n�cessaires pour une nouvelle inscription dans une affaire du p�le formation
function ajoutParticipant() 
{
    var nb   = $F('nb_participant');
    var url  = '../source/index.php?a=ajoutParticipant';
    var pars = 'nb='+nb+'&Id_session='+$F('Id_session');
    nb++;
    var target = 'autreParticipant'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}
	
//Suppression d'une inscription dans une affaire du p�le formation conduisant au calcul du nb d'inscrits, 
//au calcul du nouveau ca et � la suppresion de la ligne correspondante
function enleveParticipant(nb) 
{
    var nom 		= $F('nomParticipant'+nb);
    var prixU 		= $F('prix_unitaireParticipant'+nb);
    var nb_inscrit 	= $F('nb_inscrit');
    var ca 			= parseFloat($F('chiffre_affaire').replace(',','.'));
	
    //si il y a une personne inscrite, diminution du nombre d'inscrit
    if (nom != '') {
        calculNbInscrit(2);
    }
    //si il y a un prix associ�es � l'inscription, suppression de la somme dans le ca
    if (prixU != '') {
        prixU  = parseFloat(prixU.replace(',','.'));
        var ca = ca - prixU;
        calculCaAffaireSession(2,ca);
    }
    //suppression de la ligne de champs dans le tableau d'inscription
    supprParticipant(nb);
    //affichage du nouveau ca
    infoSessionProposition(ca,nb_inscrit);
}

//Ajout des champs n�cessaires pour une nouvelle inscription dans une affaire du p�le formation
function addTrainee() 
{
    var url  = '../source/index.php?a=ajoutParticipant';
    var pars = 'nb='+$('nb_inscrit').value+'&Id_session='+$F('Id_session');
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport){
            $('tabTrainee').insert({bottom:transport.responseText});
            $('nb_inscrit').value++;
        }
    });
}

//Suppression d'une ligne d'inscription dans le formulaire d'une affaire du p�le formation 
function supprParticipant(nb) 
{
    var url    = '../source/index.php?a=supprParticipant';
    var pars   = 'nb='+nb;
    var target = 'inscription'+nb;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Suppression d'une ligne d'inscription dans le formulaire d'une session 
function deleteTrainee(nb) {
    $('inscription'+nb).remove();
    $('nb_inscrit').value--;
}

/** Affichage de donn�es de la session dans une affaire du p�le formation **/
//Affichage d'une session en consultation dans une fen�tre en popup � partir de l'affaire
function popUpSession() 
{
    var Id_session = $F('Id_session');
    if (Id_session == ' ') {
        alert('Veuillez s�lectionner une session');
    }
    else {
        ouvre_popup2('../com/index.php?a=infoSession&pop=1&Id_session='+Id_session+'', 'fenetreSession');
    }
}

//Affichage d'une session en consultation dans une fen�tre en popup � partir de l'affaire
function popUpModifSession() 
{
    var Id_session = $F('Id_session');
    if (Id_session == ' ') {
        alert('Veuillez s�lectionner une session');
    }
    else {
        ouvre_popup2('../com/index.php?a=afficherSession&pop=1&Id_session='+Id_session, 'fenetreSessionModif');
    }
}

//Mise � jour de la liste des sessions dans la session
function rafraichirListe()
{
    infoSessionPlanning();
    infoSessionProposition(parseFloat($F('chiffre_affaire').replace(',','.')),$F('nb_inscrit'));
    var url    = '../source/index.php?a=majListeSession';
    var pars   = 'affaire='+$F('Id_affaire')+'&Id_session='+$F('Id_session');
    var target = 'listeSession';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Affichage des donn�es concernant une session dans le formulaire affaire du pole formation
function infoSession() 
{
    infoSessionDescription();
    infoSessionPlanning();
    infoSessionProposition(parseFloat($F('chiffre_affaire').replace(',','.')),$F('nb_inscrit'));
}

//Affichage des donn�es concernant la description d'une session dans le formulaire affaire du pole formation
function infoSessionDescription() 
{
    var url    = '../source/index.php?a=informationSessionDescription';
    var pars   = 'Id_session='+$F('Id_session');
    var target = 'infoSessionDescription';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Affichage des donn�es concernant le planning d'une session dans le formulaire affaire du pole formation
function infoSessionPlanning() 
{
    var url    = '../source/index.php?a=informationSessionPlanning';
    var pars   = 'Id_session='+$F('Id_session');
    var target = 'infoSessionPlanning';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//appel de la fonction infoSessionProsposition apr�s saisie direct dans les chiffre d'affaire total d'une affaire 
function infoSessionProposition1() 
{
    infoSessionProposition(parseFloat($F('chiffre_affaire').replace(',','.')),$F('nb_inscrit'));
}

//Affichage des donn�es concernant les co�ts d'une session dans le formulaire affaire du pole formation 
//(en tenant compte du nombre d'inscrits)
function infoSessionProposition(ca, nbI) 
{
    var url    = '../source/index.php?a=informationSessionProposition';
    var pars   = 'Id_session='+$F('Id_session')+'&ca='+ca+'&Id_affaire='+$F('Id_affaire')+'&nb_inscrit='+nbI;
    var target = 'chargeAffaireSession';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/** Calcul des donn�es associ�es � une affaire du p�le formation **/

//Calcul du nombre d'inscrits dans une affaire du p�le formation, cas 1 augmentation du nombre d'inscription, 
//cas n�2 suppression d'une inscription 
function calculNbInscrit(cas) 
{
    var nb         = parseInt($F('nb_participant'));
    var nb_inscrit = 0;
    var i 		   = 0;
    while (i < nb) {
        ++i;
        var nom = $F('nomParticipant'+i);
        if((nom != '') && (nom != ' ')) {
            nb_inscrit++;
        }
    }
    if (nb_inscrit < parseInt($F('nb_Inscrits'))) {
        nb_inscrit = parseInt($F('nb_Inscrits'));
    }
    else if (cas == 2) {
        nb_inscrit--;
    }
		
    var url    = '../source/index.php?a=calculNbInscrit';
    var pars   = 'nb_inscrit='+nb_inscrit;
    var target = 'nb_inscription';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//Calcul du ca d'une affaire du p�le formation suite � une nouvelle inscription
function calculCaAffaireSession(cas, caMoins) 
{
    var nb = parseInt($F('nb_participant'));
    var ca = 0;
    var i  = 0;
    while (i < nb) {
        ++i;
        var prixU = parseFloat($F('prix_unitaireParticipant'+i).replace(',','.'));
        if(prixU) {
            ca += prixU;
        }
    }
    if ((cas != 3) && (ca<parseFloat($F('chiffre_affaire').replace(',','.')))) {
        ca = parseFloat($F('chiffre_affaire').replace(',','.'));
    }
    else if (cas==2) {
        ca = caMoins;
    }
    if (ca < 0) {
        ca = 0;
    }
    if (cas!=2) {
        infoSessionProposition(ca, $F('nb_inscrit'));
    }
    var url    = '../source/index.php?a=calculCaAffaireSession';
    var pars   = 'ca='+ca;
    var target = 'ca';
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//�dition des documents de la session
function edition(type, id_session, cas)
{
    //si le cas est 1 : il s'agit d'une r�-edition: demande de confirmation d'effacer le fichier pr�c�dent
    if (cas == 1) {
        if (!confirm('Cette action va modifier le document pr�c�dent, �tes vous s�r de vouloir modifier ce document?')) {
            return false;
        }
    }
    var url    = '../for/index.php?a=edition';
    var pars   = 'type='+type+'&id_session='+id_session+'&id_doc='+$F('Id_doc'+type)+'&version='+$F('version');
    if (type == 'BDC') {
        if ($F('condition') == 2) {
            pars += '&condition=2';
        }
        else {
            pars += '&condition=1';
        }
    }
    var target = 'edition'+type;
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//fonction pour afficher les nouvelles actions men�es dans une fiche candidature
function updateActionCandidature(Id_action_mener)
{
    if($F('Id_candidature')) {
        var url = '../source/index.php?a=updateActionCandidature';
        var pars = 'Id_candidature='+$F('Id_candidature')+'&Id_action_mener='+Id_action_mener;
        var target = 'historique_action';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//fonction pour supprimer une ligne dans l'historique des actions d'une candidature
function supprimerHAction(i,j,k,l) 
{
    l = typeof(l) != 'undefined' ? l : 0;
    var url = '../source/index.php?a=supprimerHAction';
    var pars = 'Id_candidature='+i+'&Id_action_mener='+j+'&date_action='+k+'&Id_positionnement='+l;
    if(l==0){
        var target = 'historique_action';
    }
    else{
        var target = 'historique_positionnement';
    }
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

//fonction pour valider une ligne dans l'historique des actions d'une candidature
function validerHAction(i,j,k,l) 
{
    l = typeof(l) != 'undefined' ? l : 0;
    var url = '../source/index.php?a=validerHAction';
    var pars = 'Id_candidature='+i+'&Id_action_mener='+j+'&date_action='+$F('date_action'+k)+'&Id_positionnement='+l;
    if(l==0){
        var target = 'historique_action';
    }
    else{
        var target = 'historique_positionnement';
    }
    var myAjax = new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

/** CAS CONCERNANT LES DEMANDES DE RESSOURCE **/
//fonction ajoutant une ressource sur une demande de ressource
function ajoutCandidatDemandeRessource(newApplication) {
    newApplication = typeof(newApplication) != 'undefined' ? newApplication : 0;
    var nb   = $F('nb_candidat_ressource');
    if($F('candidat'+nb)==""){
        alert('Veuillez s�lectionner un candidat.');
    }
    else if($F('crCandidat'+nb)==""){
        alert('Veuillez s�lectionner un recruteur.');
    }
    else {
        if(newApplication == 0) {
            $('buttonCandidat'+nb).value = "Mettre � jour";
            $('buttonCandidat'+nb).onclick = function(){
                majCandidatDemandeRessource(nb-1);
            };
        }
        else {
            $('buttonCandidat'+nb).type = 'hidden';
        }
        var url  = '../source/index.php?a=ajoutCandidatDemandeRessource';
        var pars = 'nb='+nb+'&Id_demande='+$('Id_demande_ressource').value+'&Id_ressource='+$F('candidat'+nb)+'&Id_cr='+$F('crCandidat'+nb)+'&commentaire='+encodeURIComponent($F('commentaireCandidat'+nb))+'&dateCandidat='+$('dateCandidat'+nb).value;
        nb++;
        var target = 'autreCandidatRessource'+nb;
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onComplete: function(transport){
                $('Id_candidat'+(nb-1)).value = $('dernierCandidat').value;
            }
        });
    }
}

//fonction mettant � jour une ressource sur une demande de ressource
function majCandidatDemandeRessource(nb) {
    if($('candidat'+nb) == ''){
        alert('Veuillez s�lectionner un candidat.');
    }
    else if($('crCandidat'+nb) == ''){
        alert('Veuillez s�lectionner un recruteur.');
    }
    else if($('commentaireCandidat'+nb) == ''){
        alert('Veuillez saisir un commentaire.');
    }
    else {
        var url  = '../source/index.php?a=majCandidatDemandeRessource';
        if($('Id_candidat'+nb).value!="")
            var pars = 'Id_demande='+$('Id_demande_ressource').value+'&Id_ressource='+$('candidat'+nb).value+'&Id_cr='+$('crCandidat'+nb).value+'&commentaire='+encodeURIComponent($('commentaireCandidat'+nb).value)+'&dateCandidat='+$('dateCandidat'+nb).value+'&Id_candidat='+$('Id_candidat'+nb).value;
        else
            var pars = 'Id_demande='+$('Id_demande_ressource').value+'&Id_ressource='+$('candidat'+nb).value+'&Id_cr='+$('crCandidat'+nb).value+'&commentaire='+encodeURIComponent($('commentaireCandidat'+nb).value)+'&dateCandidat='+$('dateCandidat'+nb).value+'&Id_candidat='+$('dernierCandidat').value;
        nb++;
        new Ajax.Request(url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onComplete: function(transport){
                alert('Candidat mis � jour.')
            }
        });
    }
}

//fonction supprimant une ressource d'une demande de ressource
function suppressionCandidatDemandeRessource(id) {
    var url  = '../source/index.php?a=suppressionCandidatDemandeRessource';
    new Ajax.Request(url, {
        method: 'get',
        parameters: 'Id_candidat='+id,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport){
            window.location.reload();
        }
    });
}

//Affichage de la liste des demandes de ressource selon les filtres
function afficherDemandeRessource(info) {
    var url = '../source/index.php?a=consulterDemandeRessource';
    if(typeof(info) != 'undefined'){
        info = info;
    }
    else {
        info = new Array();
        info['page'] = 1;
        info['sort'] = [];
    }
    var pars   = 'page=' + info.page;
    if (info.sort.length > 0) {
        pars += '&orderBy=' + info.sort[0].field + '&direction=' + info.sort[0].direction;
    }
    if($('Id_affaire_demande')!=null){
        if(document.getElementById("prioritaire").checked == true) {
            var prioritaire=1;
        } else {
            var prioritaire=0;
        }
        pars += '&Id_affaire_demande='+$F('Id_affaire_demande')+'&profil='+$F('profil')+'&agenceDemandesRessource='+$F('agenceDemandesRessource').join(';')
        +'&ic='+$F('ic')+'&cr='+$F('cr')+'&statut='+$F('statut')+'&archive='+$F('archive')+'&dr_debut='+$F('dr_debut')+'&dr_fin='+$F('dr_fin')+'&action='+$F('action')
        +'&h_statut='+$F('h_statut')+'&h_cr='+$F('h_cr').join('","')+'&h_date_debut='+$F('h_date_debut')+'&h_date_fin='+$F('h_date_fin')
        +'&prioritaire='+prioritaire+'&type_recrutement='+$F('type_recrutement')+'&Id_demande_ressource='+$F('Id_demande_ressource');
    }
    else{
        if($('user')!=null) {
            pars += '&cr='+$F('user');
        }
        pars += '&nb=10';
    }
    var target = 'pageDemandeRessource';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
    return false;
}

/**
* Duplication d'un contrat d�l�gation
*/
function duplicateDemandeRessource(idDR) {
    new Ajax.Request('../source/index.php?a=duplicateDemandeRessource&Id='+idDR, {
        method: 'get',
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onSuccess: function(transport, json) {
            var json = transport.responseText.evalJSON(true);
            Modalbox.show(json.content, {
                title: 'Duplication d\'une demande de recrutement', 
                width : 800,
                footer: json.footer
            });
        }
    });
    return false;
}

//fonction d�pliant et affichant les candidats d'une demande de ressource
function afficherCandidats(id) {	
    var ligne = $("rowCand"+id).up(1);
    if(ligne.next()!=null&&ligne.next().identify()=='candidats')
        ligne.next().remove();
    else{
        new Ajax.Request('../source/index.php?a=afficherCandidat', {
            method: 'get',
            parameters: 'id='+id,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
            onSuccess: function(response) {
                ligne.insert({
                    after: "<tr id='candidats'>"+response.responseText+"</tr>"
                });
            }
        });
    }
}

//fonction envoyant le r�capitulatif d'une demande de ressource � un commercial par mail
function recapDemandeRessource(id_demande) {
    var url    = '../source/index.php?a=recapDemandeRessource';
    var pars   = 'Id_demande_ressource='+id_demande;
    var myAjax = new Ajax.Request(url, {
        method: 'get',
        parameters: pars,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString()),
        onComplete: function(transport){
            alert('R�capitulatif envoy�.')
        }
    });
}

//fonction affichant l'�tat de candidature d'une ressource
function majEtatCandidat(nb) {
    if($F('candidat'+nb) != '') {
        var url    = '../source/index.php?a=majEtatCandidat';
        var target = 'etatCandidat'+nb;
        var pars   = 'Id_ressource='+$F('candidat'+nb)+'&Id_demande='+$F('Id_demande_ressource');
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//fonction pour afficher les nouvelles actions men�es dans une demande de ressource
function updateActionDemandeRessource(Id_action_mener,nb) {
    if($F('Id_candidature'+nb)) {
        var url = '../source/index.php?a=updateActionDemandeRessource';
        var pars = 'Id_candidature='+$F('Id_candidature'+nb)+'&Id_action_mener='+Id_action_mener+'&Id_positionnement='+$F('Id_candidat'+nb)+'&nb='+nb;
        var target = 'historique_ressource'+nb;
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

//fonction pour supprimer une ligne dans l'historique des actions d'une candidature
function supprimerActionDemandeRessource(i,j,k,l,nb) 
{
    if($F('Id_candidature'+nb)) {
        var url = '../source/index.php?a=supprimerActionDemandeRessource';
        var pars = 'Id_candidature='+i+'&Id_action_mener='+j+'&date_action='+k+'&Id_positionnement='+l+'&nb='+nb;
        var target = 'historique_ressource'+nb;
        var myAjax = new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
}

/**
* Fonction mettant � jour l'historique des statuts d'une demande de ressource
*/
function updateStatutDemandeRessource(Id_statut)
{
    if($F('Id_demande_ressource')) {
        var url    = '../source/index.php?a=updateStatutDemandeRessource';
        var pars   = 'Id_demande_ressource='+$F('Id_demande_ressource')+'&Id_statut='+Id_statut;
        var target = 'historique_statut';
        new Ajax.Updater(target, url, {
            method: 'get',
            parameters: pars,
            onlyLatestOfClass:getFunctionName(arguments.callee.toString())
        });
    }
    if(Id_statut == 2){
        $('candidat_retenu').writeAttribute('disabled', null);
    }
    else{
        $('candidat_retenu').writeAttribute('disabled', 'disabled');
    };
}


function majListeCandidat(search, i)
{
    var target = 'candidat'+i;
    var url    = '../source/index.php?a=getListeCandidat';
    new Ajax.Updater(target, url, {
        method: 'get',
        parameters: 'search='+search.value,
        onlyLatestOfClass:getFunctionName(arguments.callee.toString())
    });
}

function updateDureePlanning() {
    if(document.getElementById('date_fin_commande').value !='' && $F('date_debut')) {
        var url    = '../source/index.php?a=joursOuvresPlanning';
        var pars   = 'debut='+document.getElementById('date_debut').value+'&fin='+document.getElementById('date_fin_commande').value;
        var target = 'duree_planning';
        new Ajax.Updater(target, url, {
            method: 'get', 
            parameters: pars
        });
        //document.getElementById('duree').value = jourOuvres(document.getElementById('date_debut').value,document.getElementById('date_fin_commande').value);
        document.formulaire.type_duree[0].checked = true;
        if(document.getElementById('date_fin_previsionnelle').value == '' || document.getElementById('date_fin_previsionnelle').value == '0000-00-00')
            document.getElementById('date_fin_previsionnelle').value = document.getElementById('date_fin_commande').value
    }
}

// formate un chiffre avec 'decimal' chiffres apr�s la virgule et un separateur
function format(valeur,decimal,separateur) {
    var deci=Math.round( Math.pow(10,decimal)*(Math.abs(valeur)-Math.floor(Math.abs(valeur)))) ; 
    var val=Math.floor(Math.abs(valeur));
    if ((decimal==0)||(deci==Math.pow(10,decimal))) {
        val=Math.floor(Math.abs(valeur));
        deci=0;
    }
    var val_format=val+"";
    var nb=val_format.length;
    for (var i=1;i<4;i++) {
        if (val>=Math.pow(10,(3*i))) {
            val_format=val_format.substring(0,nb-(3*i))+separateur+val_format.substring(nb-(3*i));
        }
    }
    if (decimal>0) {
        var decim=""; 
        for (var j=0;j<(decimal-deci.toString().length);j++) {
            decim+="0";
        }
        deci=decim+deci.toString();
        val_format=val_format+"."+deci;
    }
    if (parseFloat(valeur)<0) {
        val_format="-"+val_format;
    }
    return val_format;
}

/**
* Cache les champs non n�cessaires en cas de CD pour une ressource mat�riel
*/
function cdHideInput() {
    if(document.formulaire.materiel[0].checked) {
        $('posteCollab').update('MATERIEL');
        $('legendPosteCollab').update('Commentaire :');
        $('montantFac').update('Prix de vente:');
        $('infoRessource').hide();
        $('infoMission').hide();
        $('infoGen').hide();
        $('infoPoste').hide();
    }
    else if(document.formulaire.materiel[1].checked) {
        $('posteCollab').update('POSTE DU COLLABORATEUR');
        $('legendPosteCollab').update('D�finition des t�ches (concernant l\'ODM) :');
        $('montantFac').update('Montant HT Factur� / jour et par intervenant : ');
        $('infoRessource').show();
        $('infoMission').show();
        $('infoGen').show();
        $('infoPoste').show();
    }
}

/**
* D�coche les checkbox des indemnit�s incompatibles entre elle
*/
function excludeIndemnity(value) {
    var a = value.split(";");
    for (var i = 0; i < a.length; i++) {
        checkbox = $$('input[name="indemnite[]"]').find(function(n){return n.value===a[i]});
        checkbox.checked = false;
    }
}

/**
* Affiche la case de saisie d'un plafond pour les indemnit�s
*/
function showIndemnityQuota(value) {
    if(!$(value).visible())
        $(value).show();
    else
        $(value).hide();
}

/**
* 
*/
function showIndemnityBox() {
    var a = $$('input[name="indemnite[]"]').find(function(n){return n.value==='66';});
    if(a) {
        if(a.value === '66' && $('indemnite_destination').value === 'client') {
            if(a.checked === false) {
                $('l71').show();
            }
            else {
                $('l71').hide();
            }
        }
    }
    
    var a = $$('input[name="indemnite[]"]').find(function(n){return n.value==='64';});
    if(a) {        
        if(a.value === '64' && $('indemnite_destination').value === 'client') {
            if(a.checked === false) {
                $('l72').show();
            }
            else {
                $('l72').hide();
            }
        }
    }
    
    var a = $$('input[name="indemnite[]"]').find(function(n){return n.value==='67';});
    if(a) {
        if(a.value === '67' && $('indemnite_destination').value === 'client') {
            if(a.checked === false) {
                $('l73').show();
            }
            else {
                $('l73').hide();
            }
        }
    }
	
	// Affichage indemnit�s itin�rant
    // On r�cup�re le bouton radio itin�rant
    var a = $$('input[name="itinerant"]');
    var itinerant = 0;
    if(a && $('indemnite_destination').value === 'field'){
        for(i=0; i<a.length; i++){
            if(a[i].checked)
                itinerant = a[i].value;
        }
        if(itinerant == 0){
            $('l101').hide(); // Petit d�jeuner itin�rant sur justificatif            
            $('i101').checked = false;
            $('l102').hide(); // Indemnit� repas itin�rant
            $('i102').checked = false;
            $('l65').show(); // Petit d�jeuner sur justificatif
        }else if(itinerant == 1){
            $('l101').show(); // Petit d�jeuner itin�rant sur justificatif         
            $('l102').show(); // Indemnit� repas itin�rant
            $('i102').checked = true;
            $('l65').hide(); // Petit d�jeuner sur justificatif
            $('i65').checked = false; 
        }   
    }
}
