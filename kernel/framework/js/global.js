/*##################################################
 *                                global.js
 *                            -------------------
 *   begin                : Februar 06 2007
 *   copyright            : (C) 2007 R�gis Viarre, Lo�c Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Javascript frame breaker necessary to the CSRF attack protection
if (top != self)
{
	top.location = self.location;
}

__uid = 42;

function getUid() {
	return __uid++;
}

var menu_delay = 800; //Dur�e apr�s laquelle le menu est cach� lors du d�part de la souris.
var menu_delay_onmouseover = 180; //Dur�e apr�s laquelle la menu est affich� lors du passage de la souris dessus.
var menu_previous = new Array();
var menu_timeout = new Array();
var menu_timeout_tmp = new Array();
var menu_started = new Array();
var max_level = 3;

for(var i = 0; i < max_level; i++)
{
	menu_previous.push('');
	menu_timeout.push(null);
	menu_timeout_tmp.push(null);
	menu_started.push(false);
}

//Fonction de temporisation, permet d'�viter que le menu d�roulant perturbe la navigation lors du survol rapide de la souris.
function showMenu(idmenu, level)
{
	if( !menu_started[level] )
	{	// On ouvre le menu avec le d�lais
		menu_timeout_tmp[level] = setTimeout('temporiseMenu(\'' + idmenu + '\', ' + level + ')', menu_delay_onmouseover);
	}
	else if( menu_previous[level] != idmenu )
	{	// On ouvre le menu sans d�lais car le niveau est d�j� ouvert
		temporiseMenu(idmenu, level);
	}
	else	
	{	// Le menu actuel est d�j� ouvert, on enl�ve les timeout
		if( menu_timeout[level] )
			clearTimeout(menu_timeout[level]);
		if( menu_timeout_tmp[level] )
			clearTimeout(menu_timeout_tmp[level]);
	}
}

//Fonction d'affichage du menu d�roulant.
function temporiseMenu(idmenu, level) 
{
	var	id = document.getElementById(idmenu);
	//Destruction du timeout.
	if( menu_timeout[level] )
		clearTimeout(menu_timeout[level]);
	if( menu_timeout_tmp[level] )
		clearTimeout(menu_timeout_tmp[level]);
	
	//Masque les menus
	if( document.getElementById(menu_previous[level]) ) 
	{
		document.getElementById(menu_previous[level]).style.visibility = 'hidden';
		menu_started[level] = false;
		
		for(var i = level; i < max_level; i++) //Masque le sous menus.
		{
			if( document.getElementById(menu_previous[i]) )
				document.getElementById(menu_previous[i]).style.visibility = 'hidden';
		}
	}
	
	//Affichage du menu, et enregistrement dans le tableau de gestion.
	if( id ) 
	{	
		id.style.visibility = 'visible';
		menu_previous[level] = idmenu;
		menu_started[level] = true;
	}
}	

//Cache le menu d�roulant lorsque le curseur de la souris n'y est plus pendant delay_menu millisecondes.
function hideMenu(level)
{			
	//Destruction du timeout lors du d�part de la souris.
	for(var i = 0; i < max_level; i++)
	{
		if( menu_timeout_tmp[i] && !menu_started[i] )
			clearTimeout(menu_timeout_tmp[i]);
	}
	
	//Masque le menu, apr�s le d�lai d�fini.
	if( menu_started[level] )
		menu_timeout[level] = setTimeout('temporiseMenu(\'\', ' + level + ')', menu_delay);
}

//Fonction de temporisation, permet d'�viter que le menu d�roulant perturbe la navigation lors du survol rapide de la souris.
function show_menu(idmenu, level)
{
	if( !menu_started[level] )
		menu_timeout_tmp[level] = setTimeout('temporise_menu(\'' + idmenu + '\', ' + level + ')', menu_delay_onmouseover);
	else if( menu_previous[level] != idmenu )
		temporise_menu(idmenu, level);
	else
	{
		if( menu_timeout[level] )
			clearTimeout(menu_timeout[level]);
		if( menu_timeout_tmp[level] )
			clearTimeout(menu_timeout_tmp[level]);
	}
}

//Fonction d'affichage du menu d�roulant.
function temporise_menu(idmenu, level) 
{
	var divID = str_repeat('s', level) + 'smenu';
	var	id = document.getElementById(divID + idmenu);

	//Destruction du timeout.
	if( menu_timeout[level] )
		clearTimeout(menu_timeout[level]);
	if( menu_timeout_tmp[level] )
		clearTimeout(menu_timeout_tmp[level]);
	
	//Masque les menus
	if( document.getElementById(divID + menu_previous[level]) ) 
	{
		document.getElementById(divID + menu_previous[level]).style.visibility = 'hidden';
		menu_started[level] = false;
		
		for(var i = level; i < max_level; i++) //Masque le sous menus.
		{
			var divID2 = str_repeat('s', i) + 'smenu';
			if( document.getElementById(divID2 + menu_previous[i]) )
				document.getElementById(divID2 + menu_previous[i]).style.visibility = 'hidden';
		}
	}
	
	//Affichage du menu, et enregistrement dans le tableau de gestion.
	if( id ) 
	{	
		id.style.visibility = 'visible';
		menu_previous[level] = idmenu;
		menu_started[level] = true;
	}
}	

//Cache le menu d�roulant lorsque le curseur de la souris n'y est plus pendant delay_menu millisecondes.
function hide_menu(level)
{			
	//Destruction du timeout lors du d�part de la souris.
	for(var i = 0; i < max_level; i++)
	{
		if( menu_timeout_tmp[i] && !menu_started[i] )
			clearTimeout(menu_timeout_tmp[i]);
	}
	
	//Masque le menu, apr�s le d�lai d�fini.
	if( menu_started[level] )
		menu_timeout[level] = setTimeout('temporise_menu(\'\', ' + level + ')', menu_delay);
}

//R�p�tition d'un caract�re.
function str_repeat(charrepeat, nbr)
{
	var string = '';
	for(var i = 0; i < nbr; i++)
		string += charrepeat;
	return string;
}

//Recherche d'une cha�ne dans une autre.
function strpos(haystack, needle)
{
    var i = haystack.indexOf(needle, 0); // returns -1
    return i >= 0 ? i : false;
}

//Supprime les espaces en d�but et fin de cha�ne.
function trim(myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
} 

//V�rifie une adresse email
function check_mail_validity(mail)
{
	regex = new RegExp("^[a-z0-9._!#$%&\'*+/=?^|~-]+@([a-z0-9._-]{2,}\.)+[a-z]{2,4}$", "i");
	return regex.test(trim(mail));
}

//Affichage/Masquage de la balise hide.
function bb_hide(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if( div3.style.visibility == 'visible' )
	{
		div3.style.visibility = 'hidden';
		div2.style.height = '10px';
	}
	else
	{	
		div3.style.visibility = 'visible';
		div2.style.height = 'auto';
	}
	
	return true;
}

//Masque un bloc.
function hide_div(divID, useEffects)
{
    var use_effects = false
    if( arguments.length > 1 )
        use_effects = useEffects;
    
    if( document.getElementById(divID) )
    {
        if( useEffects ) Effect.SwitchOff(divID);
        document.getElementById(divID).style.display = 'none';
    }
}

//Affiche un bloc
function show_div(divID, useEffects)
{
    var use_effects = false
    if( arguments.length > 1 )
        use_effects = useEffects;
    
    if( document.getElementById(divID) )
    {
        if( useEffects ) Effect.Appear(divID, { duration: 0.5 });
        document.getElementById(divID).style.display = 'block';
    }
}

//Masque un bloc.
function hide_inline(divID)
{
	if( document.getElementById(divID) )
	{
		Effect.SwitchOff(divID);
		document.getElementById(divID).style.visibility = 'hidden';
	}
}

//Affiche un bloc
function show_inline(divID)
{
	if( document.getElementById(divID) )
	{	
		Effect.Appear(divID, { duration: 0.5 });
		document.getElementById(divID).style.visibility = 'visible';
	}
}

//Change l'adresse d'une image
function change_img_path(id, path)
{
	if( document.getElementById(id) )
		document.getElementById(id).src = path;
}

//Switch entre deux images.
function switch_img(id, path, path2)
{
	if( document.getElementById(id) )
	{	
		if( strpos(document.getElementById(id).src, path.replace(/\.\./g, '')) != false )	
			document.getElementById(id).src = path2;
		else
			document.getElementById(id).src = path;
	}
}

//Afffiche/masque automatiquement un bloc.
function display_div_auto(divID, type)
{
	if( document.getElementById(divID) )
	{	
		if( type == '')
			type = 'block';
			
		if( document.getElementById(divID).style.display == type )
		{	
			Effect.SwitchOff(divID);
			document.getElementById(divID).style.display = 'none';
		}
		else if( document.getElementById(divID).style.display == 'none' )
			document.getElementById(divID).style.display = type;
	}
}

//Popup
function popup(page,name)
{
   var screen_height = screen.height;
   var screen_width = screen.width;

	if( screen_height == 600 && screen_width == 800 )
		window.open(page, name, "width=680, height=540,location=no,status=no,toolbar=no,scrollbars=yes");
	else if( screen_height == 768 && screen_width == 1024 )
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else if( screen_height == 864 && screen_width == 1152 )
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
}

//Teste la pr�sence d'une valeur dans un tableau
function inArray(aValue, anArray)
{
    for( var i = 0; i < anArray.length; i++)
    {
        if( anArray[i] == aValue )
            return true;
    }
    return false;
}

//Barre de progression, 
var timeout_progress_bar = null;
var max_percent = 0;
var info_progress_tmp = '';
var progressbar_speed = 20; //Vitesse de la progression.
var progressbar_size = 55; //Taille de la barre de progression.
var progressbar_id = 'progress_info'; //Identifiant de la barre de progression.
var restart_progress = false;
var theme = '';

//Configuration de la barre de progression.
function load_progress_bar(progressbar_speed_tmp, theme_tmp, progressbar_id_tmp)
{
	progressbar_speed = progressbar_speed_tmp;
	restart_progress = true;
	theme = theme_tmp;
	progressbar_id = progressbar_id_tmp;
	if( arguments.length == 4 ) //Argument optionnel.
		progressbar_size = arguments[3];
}

//Barre de progression.
function progress_bar(percent_progress, info_progress, result_msg, result_id)
{
	bar_progress = (percent_progress * progressbar_size) / 100;
	if( arguments.length < 4 )
	{
		result_id = "";
		result_msg = "";
	}
    
	// D�claration et initialisation d'une variable statique
	if( restart_progress )
	{	
		clearTimeout(timeout_progress_bar);
		this.percent_begin = 0;
		max_percent = 0;
		if( document.getElementById('progress_bar' + progressbar_id) )
			document.getElementById('progress_bar' + progressbar_id).innerHTML = '';
		restart_progress = false;
	}

	if( this.percent_begin <= bar_progress )
	{
		if( document.getElementById('progress_bar' + progressbar_id) )
			document.getElementById('progress_bar' + progressbar_id).innerHTML += '<img src="' + PATH_TO_ROOT + '/templates/' + theme + '/images/progress.png" alt="" />';
		if( document.getElementById('progress_percent' + progressbar_id) )
			document.getElementById('progress_percent' + progressbar_id).innerHTML = Math.round((this.percent_begin * 100) / progressbar_size);
		if( document.getElementById('progress_info' + progressbar_id) )
		{	
			if( percent_progress > max_percent )
			{	
				max_percent = percent_progress;
				info_progress_tmp = info_progress;
			}
			document.getElementById('progress_info' + progressbar_id).innerHTML = info_progress_tmp;
		}
		//Message de fin
		if( this.percent_begin == progressbar_size && result_id != "" && result_msg != "" )
			document.getElementById(result_id).innerHTML = result_msg;
            
		timeout_progress_bar = setTimeout('progress_bar(' + percent_progress + ', "' + info_progress + '", "' + result_id + '", "' + result_msg.replace(/"/g, "\\\"") + '")', progressbar_speed);
	}
	else
		this.percent_begin = this.percent_begin - 1;
	this.percent_begin++;
}

//Fonction de pr�paration de l'ajax.
function xmlhttprequest_init(filename)
{
	var xhr_object = null;
	if( window.XMLHttpRequest ) //Firefox
	   xhr_object = new XMLHttpRequest();
	else if( window.ActiveXObject ) //Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

	xhr_object.open('POST', filename, true);

	return xhr_object;
}

//Fonction ajax d'envoi.
function xmlhttprequest_sender(xhr_object, data)
{
	xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr_object.send(data);
}

//Echape les variables de type cha�nes dans les requ�tes xmlhttprequest.
function escape_xmlhttprequest(contents)
{
	contents = contents.replace(/\+/g, '%2B');
	contents = contents.replace(/&/g, '%26');
	
	return contents;
}

//Informe sur la capacit� du navigateur � supporter AJAX
function browserAJAXFriendly()
{
    if ( window.XMLHttpRequest || window.ActiveXObject )
        return true;
    else
        return false;
}

//Fonction de recherche des membres.
function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
{
	var login = document.getElementById('login' + searchid).value;
	if( login != '' )
	{
		if( document.getElementById('search_img' + searchid) )
			document.getElementById('search_img' + searchid).innerHTML = '<img src="' + PATH_TO_ROOT + '/templates/' + theme + '/images/loading_mini.gif" alt="" class="valign_middle" />';
		var xhr_object = xmlhttprequest_init(PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?token=' + TOKEN + '&' + insert_mode + '=1');
		data = 'login=' + login + '&divid=' + searchid;
		xhr_object.onreadystatechange = function() 
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
			{
				if( document.getElementById('search_img' + searchid) )
					document.getElementById('search_img' + searchid).innerHTML = '';
				if( document.getElementById("xmlhttprequest_result_search" + searchid) )
					document.getElementById("xmlhttprequest_result_search" + searchid).innerHTML = xhr_object.responseText;
				Effect.BlindDown('xmlhttprequest_result_search' + searchid, { duration: 0.5 });
			}
			else if( xhr_object.readyState == 4 ) 
			{
				if( document.getElementById('search_img' + searchid) )
					document.getElementById('search_img' + searchid).innerHTML = '';
			}
		}
		xmlhttprequest_sender(xhr_object, data);
	}	
	else
		alert(alert_empty_login);
}

//Fonction d'ajout de membre dans les autorisations.
function XMLHttpRequest_add_member_auth(searchid, user_id, login, alert_already_auth)
{
    var selectid = document.getElementById('members_auth' + searchid);
    for(var i = 0; i < selectid.length; i++) //V�rifie que le membre n'est pas d�j� dans la liste.
    {
        if( selectid[i].value == user_id )
        {
            alert(alert_already_auth);
            return;
        }
    }
    var oOption = new Option(login, user_id);
    oOption.id = searchid + 'm' + (selectid.length - 1);
        oOption.selected = true;

    if( document.getElementById('members_auth' + searchid) ) //Ajout du membre.
        document.getElementById('members_auth' + searchid).options[selectid.length] = oOption;
}

//S�lection des formulaires.
function check_select_multiple(id, status)
{
	var i;	

	//S�lection des groupes.
	var selectidgroups = document.getElementById('groups_auth' + id);
	for(i = 0; i < selectidgroups.length; i++)
	{	
		if( selectidgroups[i] )
			selectidgroups[i].selected = status;
	}
	
	//S�lection des membres.
	var selectidmember = document.getElementById('members_auth' + id);
	for(i = 0; i < selectidmember.length; i++)
	{	
		if( selectidmember[i] )
			selectidmember[i].selected = status;
	}	
}

//S�lection auto des rangs sup�rieur � celui cliqu�.
function check_select_multiple_ranks(id, start)
{
	var i;			
	for(i = start; i <= 2; i++)
	{	
		if( document.getElementById(id + i) )
			document.getElementById(id + i).selected = true;
	}
}

// Cr�e un lien de pagination javascript
function writePagin(fctName, fctArgs, isCurrentPage, textPagin, i)
{
    pagin = '<span class="pagination';
    if ( isCurrentPage )
        pagin += ' pagination_current_page text_strong';
    pagin += '">';
    pagin += '<a href="javascript:' + fctName + '(' + i + fctArgs + ')">' + textPagin + '</a>';
    pagin += '</span>&nbsp;';
    
    return pagin;
}

// Cr�e la pagination � partir du nom du bloc de page, du bloc de pagination, du nombre de r�sultats
// du nombre de r�sultats par page ...
function ChangePagination(page, nbPages, blocPagin, blocName, nbPagesBefore, nbPagesAfter )
{
    var pagin = '';
    if ( nbPages > 1 )
    {
        if( arguments.length < 5 )
        {
            nbPagesBefore = 3;
            nbPagesAfter = 3;
        }
        
        var before = Math.max(0, page - nbPagesBefore);
        var after = Math.min(nbPages, page + nbPagesAfter + 1);
        
        var fctName = 'ChangePagination';
        var fctArgs = ', '  + nbPages + ', \'' + blocPagin + '\', \'' + blocName + '\', ' + nbPagesBefore + ', ' + nbPagesAfter;
        
        // D�but
        if( page != 0 )
            pagin += writePagin(fctName, fctArgs, false, '&laquo;', 0);
        
        // Before
        for ( var i = before; i < page; i++ )
            pagin += writePagin(fctName, fctArgs, false, i + 1, i );
        
        // Page courante
        pagin += writePagin(fctName, fctArgs, true, page + 1, page );
        
        // After
        for ( var i = page + 1; i < after; i++ )
            pagin += writePagin(fctName, fctArgs, false, i + 1, i );
        
        // Fin
        if( page != nbPages - 1 )
            pagin += writePagin(fctName, fctArgs, false, '&raquo;', nbPages - 1 );
    }
    
    // On cache tous les autre r�sultats du module
    for ( var i = 0; i < nbPages; i++ )
        hide_div(blocName + '_' + i);
        
    // On montre la page demand�e
    show_div(blocName + '_' + page);
    
    // Mise � jour de la pagination
    document.getElementById(blocPagin).innerHTML = pagin;
}

// Teste si une chaine est numerique
function isNumeric(number)
{
    var numbers = "0123456789.";
    for ( var i = 0; i < number.length && numbers.indexOf(number[i]) != -1; i++ );
    return i == number.length ;
}

// Teste si une chaine est un entier
function isInteger(number)
{
    var numbers = "0123456789";
    for ( var i = 0; i < number.length && numbers.indexOf(number[i]) != -1; i++ );
    return i == number.length ;
}


/*#######Feeds menu gestion######*/
var feed_menu_timeout_in = null;
var feed_menu_timeout_out = null;
var feed_menu_elt = null;
var feed_menu_delay = 800; //Dur�e apr�s laquelle le menu est cach� lors du d�part de la souris.

// Print the syndication's choice menu
function ShowSyndication(element) {
    if( feed_menu_elt )
        feed_menu_elt.style.visibility = 'hidden';
    feed_menu_elt = null;
    var elts = null;
    elts = element.parentNode.getElementsByTagName('div');
    for( var i = 0; i < elts.length; i++ ) {
        if( elts[i].title == 'L_SYNDICATION_CHOICES') {
            feed_menu_elt = elts[i];
            break;
        }
    }
	feed_menu_elt.style.visibility = 'visible';
    clearTimeout(feed_menu_timeout_out);
}
function ShowSyndicationMenu(element) {
	element.style.visibility = 'visible';
    clearTimeout(feed_menu_timeout_out);
}
function HideSyndication(element) {
    feed_menu_elt = element;
    feed_menu_timeout_out = setTimeout('feed_menu_elt.style.visibility = \'hidden\'', feed_menu_delay);
    clearTimeout(feed_menu_timeout_in);
}

//Pour savoir si une fonction existe
function functionExists(function_name) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Steve Clay
    // +   improved by: Legaev Andrey
    // *     example 1: function_exists('isFinite');
    // *     returns 1: true 
    if (typeof function_name == 'string'){
        return (typeof window[function_name] == 'function');
    } else{
        return (function_name instanceof Function);
    }
}

//Includes synchronously a js file
function include(file)
{
	if (window.document.getElementsByTagName)
	{
		script = window.document.createElement("script");
		script.type = "text/javascript";
		script.src = file;
		$("header").appendChild(script);
	}
}

//Affiche le lecteur vid�o avec la bonne URL, largeur et hauteur
playerflowPlayerRequired = false;
function insertMoviePlayer(id) {
	if (!playerflowPlayerRequired) {
		include(PATH_TO_ROOT + '/kernel/data/flowplayer/flowplayer-3.1.1.min.js');
		playerflowPlayerRequired = true;
	}
	flowPlayerDisplay(id);
}

//Construit le lecteur � partir du moment o� son code a �t� interpr�t� par l'interpr�teur javascript
function flowPlayerDisplay(id)
{
	//Construit et affiche un lecteur vid�o de type flowplayer
	//Si la fonction n'existe pas, on attend qu'elle soit interpr�t�e
	if (!functionExists('flowplayer'))
	{
		setTimeout('flowPlayerDisplay(\'' + id + '\')', 100);
		return;
	}
	//On lance le flowplayer
	flowplayer(id, PATH_TO_ROOT + '/kernel/data/flowplayer/flowplayer-3.1.1.swf', { 
		    clip: { 
		        url: $(id).href,
		        autoPlay: false 
		    }
	    }
	);
}