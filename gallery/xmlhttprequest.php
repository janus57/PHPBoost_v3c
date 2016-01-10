<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : August 30, 2007
 *   copyright          : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
include_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

//Notation.
if (!empty($_GET['increment_view']))
{
	$g_idpics = retrieve(GET, 'id', 0);
	$g_idcat = retrieve(GET, 'cat', 0);
	if (empty($g_idpics))
		exit;
	elseif (!empty($g_idcat))
	{
		if (!isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0) 
			exit;
	}
	else //Racine.
	{
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		$CAT_GALLERY[0]['aprob'] = 1;
	}
	//Niveau d'autorisation de la cat�gorie
	if (!$User->check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY))
		exit;
		
	//Mise � jour du nombre de vues.
	$Sql->query_inject("UPDATE LOW_PRIORITY " . PREFIX . "gallery SET views = views + 1 WHERE idcat = '" . $g_idcat . "' AND id = '" . $g_idpics . "'", __LINE__, __FILE__);
}
elseif (!empty($_GET['note']) ) //Utilisateur connect�.
{	
	if ($User->check_level(MEMBER_LEVEL))
	{
		$id = retrieve(POST, 'id', 0);
		$note = retrieve(POST, 'note', 0);

		//Initialisation  de la class de gestion des fichiers.
		import('content/note');
		$Note = new Note('gallery', $id, '', $CONFIG_GALLERY['note_max'], '', NOTE_DISPLAY_NOTE);
		
		if (!empty($note) && !empty($id))
			echo $Note->add($note); //Ajout de la note.
	}
	else
		echo -2;
}
else
{	
	$Session->csrf_get_protect(); //Protection csrf
	
	if (!empty($_GET['rename_pics'])) //Renomme une image.
	{
		$id_file = retrieve(POST, 'id_file', 0);
		$id_cat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = " .$id_file. " ", __LINE__, __FILE__);
		
		if ($User->check_auth($CAT_GALLERY[$id_cat]['auth'], EDIT_CAT_GALLERY)) //Modo
		{	
			//Initialisation  de la class de gestion des fichiers.
			include_once('../gallery/gallery.class.php');
			$Gallery = new Gallery;

			$name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
			$previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';
			
			if (!empty($id_file))
				echo $Gallery->Rename_pics($id_file, $name, $previous_name);
			else 
				echo -1;
				
		}
	}
	elseif (!empty($_GET['aprob_pics']))
	{
		$id_file = retrieve(POST, 'id_file', 0);
		$id_cat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = " .$id_file. " ", __LINE__, __FILE__);
		
		if ($User->check_auth($CAT_GALLERY[$id_cat]['auth'], EDIT_CAT_GALLERY)) //Modo
		{
			//Initialisation  de la class de gestion des fichiers.
			include_once('../gallery/gallery.class.php');
			$Gallery = new Gallery;
			
			if (!empty($id_file))
			{
				echo $Gallery->Aprob_pics($id_file);
				//R�g�n�ration du cache des photos al�atoires.
				$Cache->Generate_module_file('gallery');
			}
			else 
				echo 0;
		}
	}
}

?>