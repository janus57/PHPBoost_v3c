<?php
/*##################################################
 *                            download_french.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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


####################################################
#                                                          French                                                                        #
####################################################

global $DOWNLOAD_LANG, $LANG;
$DOWNLOAD_LANG = array();

//Gestion des fichiers
$DOWNLOAD_LANG['files_management'] = 'Gestion des fichiers';
$DOWNLOAD_LANG['file_management'] = 'Modification du fichier';
$DOWNLOAD_LANG['file_addition'] = 'Ajout d\'un fichier';
$DOWNLOAD_LANG['add_file'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['update_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['warning_previewing'] = 'Attention, vous pr�visualisez la fiche correspondant � votre fichier. Tant que vous ne validez pas vos modifications elles ne seront pas prises en compte.';
$DOWNLOAD_LANG['file_image'] = 'Adresse de l\'image illustrant le fichier';
$DOWNLOAD_LANG['require_description'] = 'Veuillez entrer une description !';
$DOWNLOAD_LANG['require_url'] = 'Veuillez entrer une adresse correcte pour le fichier !';
$DOWNLOAD_LANG['require_creation_date'] = 'Veuillez entrer une date de cr�ation au bon format (jj/mm/aa) !';
$DOWNLOAD_LANG['require_release_date'] = 'Veuillez entrer une date de sortie (ou de mise � jour) au bon format (jj/mm/aa) !';
$DOWNLOAD_LANG['download_add'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['download_management'] = 'Gestion T�l�chargements';
$DOWNLOAD_LANG['download_config'] = 'Configuration des t�l�chargements';
$DOWNLOAD_LANG['file_list'] = 'Liste des fichiers';
$DOWNLOAD_LANG['edit_file'] = 'Edition du fichier';
$DOWNLOAD_LANG['nbr_download_max'] = 'Nombre maximum de fichiers affich�s par page';
$DOWNLOAD_LANG['nbr_columns_for_cats'] = 'Nombre de colonnes dans lesquelles sont pr�sent�es les cat�gories';
$DOWNLOAD_LANG['download_date'] = 'Date d\'ajout du fichier';
$DOWNLOAD_LANG['release_date'] = 'Date de sortie (ou derni�re mise � jour) du fichier';
$DOWNLOAD_LANG['ignore_release_date'] = 'Ignorer la date de sortie du fichier';
$DOWNLOAD_LANG['file_visibility'] = 'Parution du fichier';
$DOWNLOAD_LANG['icon_cat'] = 'Image de la cat�gorie';
$DOWNLOAD_LANG['explain_icon_cat'] = 'Vous pouvez choisir une image du r�pertoire download/ ou mettre son adresse dans le champ pr�vu � cet effet';
$DOWNLOAD_LANG['root_description'] = 'Description de la racine des t�l�chargements';
$DOWNLOAD_LANG['approved'] = 'Approuv�';
$DOWNLOAD_LANG['hidden'] = 'Cach�';
$DOWNLOAD_LANG['number_of_hits'] = 'Nombre de t�l�chargements';
$DOWNLOAD_LANG['download_method'] = 'M�thode de t�l�chargement';
$DOWNLOAD_LANG['download_method_explain'] = 'Choisissez de faire une redirection vers le fichier sauf si le fichier s\'affiche dans le navigateur au lieu d\'�tre t�l�charg� par le t�l�chargement et que ce fichier est <strong>sur votre serveur</strong>, dans ce cas, choisissez de forcer le t�l�chargement';
$DOWNLOAD_LANG['force_download'] = 'Forcer le t�l�chargement';
$DOWNLOAD_LANG['redirection_up_to_file'] = 'Rediriger vers le fichier';

//Titre
$DOWNLOAD_LANG['title_download'] = 'T�l�chargements';

//DL
$DOWNLOAD_LANG['file'] = 'Fichier';
$DOWNLOAD_LANG['size'] = 'Taille';
$DOWNLOAD_LANG['download'] = 'T�l�chargements';
$DOWNLOAD_LANG['none_download'] = 'Aucun fichier dans cette cat�gorie';
$DOWNLOAD_LANG['xml_download_desc'] = 'Derniers fichiers';
$DOWNLOAD_LANG['no_note'] = 'Aucune note';
$DOWNLOAD_LANG['actual_note'] = 'Note actuelle';
$DOWNLOAD_LANG['vote_action'] = 'Voter';
$DOWNLOAD_LANG['add_on_date'] = 'Ajout� le %s';
$DOWNLOAD_LANG['downloaded_n_times'] = 'T�l�charg� %d fois';
$DOWNLOAD_LANG['num_com'] = '%d commentaire';
$DOWNLOAD_LANG['num_coms'] = '%d commentaires';
$DOWNLOAD_LANG['this_note'] = 'Note :';
$DOWNLOAD_LANG['short_contents'] = 'Courte description';
$DOWNLOAD_LANG['complete_contents'] = 'Description compl�te';
$DOWNLOAD_LANG['url'] = 'Adresse du fichier';
$DOWNLOAD_LANG['confirm_delete_file'] = 'Etes-vous certain de vouloir supprimer ce fichier ?';
$DOWNLOAD_LANG['download_file'] = 'T�l�charger le fichier';
$DOWNLOAD_LANG['file_infos'] = 'Informations sur le fichier';
$DOWNLOAD_LANG['insertion_date'] = 'Date d\'ajout';
$DOWNLOAD_LANG['last_update_date'] = 'Date de sortie ou de derni�re mise � jour';
$DOWNLOAD_LANG['downloaded'] = 'T�l�charg�';
$DOWNLOAD_LANG['n_times'] = '%d fois';
$DOWNLOAD_LANG['num_notes'] = '%d note(s)';
$DOWNLOAD_LANG['edit_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['delete_file'] = 'Supprimer le fichier';
$DOWNLOAD_LANG['unknown_size'] = 'inconnue';
$DOWNLOAD_LANG['unknown_date'] = 'inconnue';
$DOWNLOAD_LANG['read_feed'] = 'T�l�chager';

//Cat�gories
$DOWNLOAD_LANG['add_category'] = 'Ajouter une cat�gorie';
$DOWNLOAD_LANG['removing_category'] = 'Suppression d\'une cat�gorie';
$DOWNLOAD_LANG['explain_removing_category'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (fichiers et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de son cat�gorie. <strong>Attention, cette action est irr�versible !</strong>';
$DOWNLOAD_LANG['delete_category_and_its_content'] = 'Supprimer la cat�gorie et tout son contenu';
$DOWNLOAD_LANG['move_category_content'] = 'D�placer son contenu dans :';
$DOWNLOAD_LANG['required_fields'] = 'Les champs marqu�s * sont obligatoires !';
$DOWNLOAD_LANG['category_name'] = 'Nom de la cat�gorie';
$DOWNLOAD_LANG['category_location'] = 'Emplacement de la cat�gorie';
$DOWNLOAD_LANG['cat_description'] = 'Description de la cat�gorie';
$DOWNLOAD_LANG['num_files_singular'] = '%d fichier';
$DOWNLOAD_LANG['num_files_plural'] = '%d fichiers';
$DOWNLOAD_LANG['recount_subfiles'] = 'Recompter le nombre de fichiers de chaque cat�gorie';
$DOWNLOAD_LANG['popularity'] = 'Popularit�';
$DOWNLOAD_LANG['sort_alpha'] = 'Alphab�tique';
$DOWNLOAD_LANG['order_by'] = 'Trier selon';

//Autorisations
$DOWNLOAD_LANG['auth_read'] = 'Permissions de lecture';
$DOWNLOAD_LANG['auth_write'] = 'Permissions d\'�criture';
$DOWNLOAD_LANG['auth_contribute'] = 'Permissions de contribution';
$DOWNLOAD_LANG['special_auth'] = 'Permissions sp�ciales';
$DOWNLOAD_LANG['special_auth_explain'] = 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.';
$DOWNLOAD_LANG['global_auth'] = 'Permissions globales';
$DOWNLOAD_LANG['global_auth_explain'] = 'Vous d�finissez ici les permissions globales au module. Vous pourrez changer ces permissions localement sur chaque cat�gorie';

//Erreurs
$DOWNLOAD_LANG['successful_operation'] = 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s';
$DOWNLOAD_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement';
$DOWNLOAD_LANG['unexisting_category'] = 'La cat�gorie que vous avez s�lectionn�e n\'existe pas';
$DOWNLOAD_LANG['new_cat_does_not_exist'] = 'La cat�gorie cible n\'existe pas';
$DOWNLOAD_LANG['infinite_loop'] = 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie';
$DOWNLOAD_LANG['recount_success'] = 'Le nombre de fichiers pour chaque cat�gorie a �t� recompt� avec succ�s.';

//Syndication
$DOWNLOAD_LANG['read_feed'] = 'T�l�charger';
$DOWNLOAD_LANG['posted_on'] = 'Le';

//Contribution
$DOWNLOAD_LANG['notice_contribution'] = 'Vous n\'�tes pas autoris� � cr�er un fichier, cependant vous pouvez proposer un fichier. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.';
$DOWNLOAD_LANG['contribution_counterpart'] = 'Compl�ment de contribution';
$DOWNLOAD_LANG['contribution_counterpart_explain'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier au t�l�chargement). Ce champ est facultatif.';
$DOWNLOAD_LANG['contribution_entitled'] = 'Un fichier a �t� propos� : %s';
$DOWNLOAD_LANG['contribution_confirmation'] = 'Confirmation de contribution';
$DOWNLOAD_LANG['contribution_confirmation_explain'] = '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>';
$DOWNLOAD_LANG['contribution_success'] = 'Votre contribution a bien �t� enregistr�e.';

//Erreurs
$LANG['e_unexist_file_download'] = 'Le fichier que vous demandez n\'existe pas !';
$LANG['e_unexist_category_download'] = 'La cat�gorie que vous demandez n\'existe pas !';

?>