<?php
/*##################################################
 *                              media_french.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                     French                       #
####################################################

global $MEDIA_LANG;

$MEDIA_LANG = array(
// admin_media.php
'aprob_media' => 'Approuver le fichier multim�dia',
'confirm_delete_media' => '�tes-vous certain de vouloir supprimer ce fichier multim�dia ?',
'hide_media' => 'Cacher ce fichier multim�dia',
'recount_per_cat' => 'Recompter le nombre de fichiers multim�dias par cat�gorie',
'show_media' => 'Montrer ce fichier multim�dia',

// admin_media_cats.php
'auth_read' => 'Permissions de lecture',
'auth_contrib' => 'Permissions de contribution',
'auth_write' => 'Permissions d\'�criture',
'category' => 'Cat�gorie',
'cat_description' => 'Description de la cat�gorie',
'cat_image' => 'Image de la cat�gorie',
'cat_location' => 'Emplacement de la cat�gorie',
'cat_name' => 'Nom de la cat�gorie',
'display' => 'Affichage',
'display_com' => 'Afficher les commentaires',
'display_date' => 'Afficher la date du fichier multim�dia',
'display_desc' => 'Afficher la description du fichier multim�dia',
'display_in_list' => 'Liste',
'display_in_media' => 'Fichier',
'display_nbr' => 'Afficher le nombre de fichier dans la cat�gorie',
'display_note' => 'Afficher la note du fichier multim�dia',
'display_poster' => 'Afficher le posteur du fichier multim�dia',
'display_view' => 'Afficher le nombre de consultation du fichier multim�dia',
'infinite_loop' => 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie',
'move_category_content' => 'D�placer son contenu dans :',
'new_cat_does_not_exist' => 'La cat�gorie cible n\'existe pas',
'recount_success' => 'Le recomptage des fichiers multim�dias a �t� r�alis� avec succ�s.',
'remove_category_and_its_content' => 'Supprimer la cat�gorie et tout son contenu',
'removing_category' => 'Suppression d\'une cat�gorie',
'removing_category_explain' => 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (cat�gories et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de son contenu. <strong>Attention, cette action est irr�versible !</strong>',
'required_fields' => 'Les champs marqu�s * sont obligatoires !',
'required_fields_empty' => 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement',
'special_auth' => 'Autorisations sp�ciales',
'successful_operation' => 'L\'op�ration que vous avez demand� a �t� effectu�e avec succ�s',
'unexisting_category' => 'La cat�gorie que vous avez s�lectionn� n\'existe pas',

// admin_media_config.php
'config_auth' => 'Autorisations g�n�rales',
'config_auth_explain' => 'Configurez ici les autorisations g�n�rales de lecture et d\'�criture du module MULTIMEDIA. Vous pourrez ensuite pour chaque cat�gorie appliquer des autorisations particuli�res.',
'config_display' => 'Configuration de l\'affichage',
'config_general' => 'Configuration g�n�rale',
'mime_type' => 'Types de fichiers autoris�s',
'module_desc' => 'Description du module',
'module_name' => 'Nom du module',
'module_name_explain' => 'Le nom du module appara�tra dans le titre et dans l\'arborescence de chaque page',
'nbr_cols' => 'Nombre de cat�gories par colonne',
'note' => '�chelle de notation',
'pagination' => 'Nombre de fichiers multim�dia affich�s par page',
'require' => 'Veuillez compl�ter le champ : ',
'type_both' => 'Musique & Vid�o',
'type_music' => 'Musique',
'type_video' => 'Vid�o',

// admin_media_menu.php
'add_cat' => 'Ajouter une cat�gorie',
'add_media' => 'Ajouter un fichier multim�dia',
'configuration' => 'Configuration',
'list_media' => 'Liste des fichiers multim�dias',
'management_cat' => 'Gestion des cat�gories',
'management_media' => 'Gestion multim�dia',

// contribution.php
'contribution_confirmation' => 'Confirmation de contribution',
'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>',
'contribution_success' => 'Votre contribution a bien �t� enregistr�e.',

// media.php
'add_on_date' => 'Ajout� le %s',
'n_time' => '%d fois',
'n_times' => '%d fois',
'none_media' => 'Il n\'y a aucun fichier multim�dia dans cette cat�gorie !',
'num_note' => '%d note',
'num_notes' => '%d notes',
'num_media' => '%d fichier multim�dia',
'num_medias' => '%d fichiers multim�dias',
'sort_popularity' => 'Popularit�',
'sort_title' => 'Titre',
'media_infos' => 'Information sur le fichier multim�dia',
'media_added' => '<a href="%2$s"%3$s>%1$s</a>',
'media_added_by' => 'Par <a href="%2$s"%3$s>%1$s</a>',
'view_n_times' => 'Vu %d fois',

// media_action.php
'action_success' => 'L\'action demand�e a �t� r�alis�e avec succ�s !',
'add_success' => 'Le fichier a �t� ajout� avec succ�s !',
'contribution_counterpart' => 'Compl�ment de contribution',
'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier). Ce champ est facultatif.',
'contribution_entitled' => '[Multim�dia] %s',
'contribute_media' => 'Proposer un fichier multim�dia',
'delete_media' => 'Supprimer un fichier multim�dia',
'deleted_success' => 'Le fichier multim�dia a �t� supprim�e avec succ�s !',
'edit_success' => 'Le fichier multim�dia a �t� �dit� avec succ�s !',
'edit_media' => '�diter un fichier multim�dia',
'media_aprobe' => 'Approbation',
'media_approved' => 'Approuv�e',
'media_category' => 'Cat�gorie du fichier multim�dia',
'media_description' => 'Description du fichier multim�dia',
'media_height' => 'Hauteur de la vid�o',
'media_moderation' => 'Mod�ration',
'media_name' => 'Titre du fichier multim�dia',
'media_url' => 'Lien du fichier multim�dia',
'media_width' => 'Largeur de la vid�o',
'notice_contribution' => 'Vous n\'�tes pas autoris� � ajouter un fichier multim�dia, cependant vous pouvez proposer un fichier multim�dia. Votre contribution suivra le parcours classique et sera trait�e dans la panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.',
'require_name' => 'Vous devez donnez un titre � ce fichier multim�dia !',
'require_url' => 'Vous devez renseigner le lien de votre fichier multim�dia !',

// media_interface.class.php
'media' => 'Fichier Multim�dia',
'all_cats' => 'Toutes les cat�gories',
'xml_media_desc' => 'Derniers m�dias',

// moderation_media.php
'all_file' => 'Tous les fichiers',
'confirm_delete_media_all' => 'Cette action supprimera D�FINITIVEMENT tous les fichiers s�lectionn�s !',
'display_file' => 'Afficher les fichiers',
'file_unaprobed' => 'Fichier d�sapprouv�',
'file_unvisible' => 'Fichier invisible',
'file_visible' => 'Fichier approuv� et visible',
'filter' => 'Filtre',
'from_cats' => 'de la cat�gorie',
'hide_media_short' => 'Cacher',
'include_sub_cats' => ', inclure les sous-cat�gories :',
'legend' => 'L�gende',
'moderation_success' => 'Les actions ont �t� r�alis�es avec succ�s !',
'no_media_moderate' => 'Aucun fichier multim�dia � mod�rer !',
'show_media_short' => 'Montrer',
'unaprobed' => 'D�sapprouv�s',
'unvisible' => 'Invisibles',
'unaprob_media' => 'Fichier d�sapprouv�',
'unaprobed_media_short' => 'D�sapprouver',
'unvisible_media' => 'Fichier invisible',
'visible' => 'Approuv�s',
);

$LANG['e_mime_disable_media'] = 'Le type du fichier multim�dia que vous souhaitez proposer est d�sactiv� !';
$LANG['e_mime_unknow_media'] = 'Impossible de d�terminer le mime type de ce fichier !';
$LANG['e_link_empty_media'] = 'Veuillez renseigner le lien de votre fichier multim�dia !';
$LANG['e_unexist_media'] = 'Le fichier multim�dia demand�e n\'existe pas !';

?>