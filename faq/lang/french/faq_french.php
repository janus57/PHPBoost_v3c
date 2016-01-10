<?php
/*##################################################
 *                              faq_french.php
 *                            -------------------
 *   begin                : October 20, 2007
 *   copyright          : (C) 2007 Beno�t Sautel
 *   email                : ben.popeye@phpboost.com
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

global $FAQ_LANG;
$FAQ_LANG = array();

//G�n�ralit�s
$FAQ_LANG['faq'] = 'FAQ';
$FAQ_LANG['faq_no_question_here'] = 'Aucune question pr�sente dans cette cat�gorie';
$FAQ_LANG['faq_page_title'] = 'FAQ - %s';
$FAQ_LANG['cat_name'] = 'Nom de la cat�gorie';
$FAQ_LANG['num_questions_singular'] = '%d question';
$FAQ_LANG['num_questions_plural'] = '%d questions';
$FAQ_LANG['url_of_question'] = 'URL de la question';

//Gestion
$FAQ_LANG['cat_properties'] = 'Propri�t�s de la cat�gorie';
$FAQ_LANG['cat_description'] = 'Description';
$FAQ_LANG['go_back_to_cat'] = 'Retour � la cat�gorie';
$FAQ_LANG['display_mode'] = 'Mode d\'affichage';
$FAQ_LANG['display_block'] = 'Par blocs';
$FAQ_LANG['display_inline'] = 'En lignes';
$FAQ_LANG['display_auto'] = 'Automatique';
$FAQ_LANG['display_explain'] = 'En automatique l\'affichage suivra la configuration g�n�rale, en ligne les r�ponses seront masqu�es et un clic sur la question affichera la r�ponse correspondante tandis que en blocs les questions seront suivies de leurs r�ponses.';
$FAQ_LANG['global_auth'] = 'Autorisations sp�ciales';
$FAQ_LANG['global_auth_explain'] = 'Permet d\'appliquer des autorisations particuli�res � la cat�gorie. Attention les autorisations de lecture se transmettent dans les sous cat�gories, c\'est-�-dire que si vous ne pouvez pas voir une cat�gorie vous ne pouvez pas voir ses filles.';
$FAQ_LANG['read_auth'] = 'Autorisations de lecture';
$FAQ_LANG['write_auth'] = 'Autorisations d\'�criture';
$FAQ_LANG['questions_list'] = 'Liste des questions';
$FAQ_LANG['ranks'] = 'Rangs';
$FAQ_LANG['insert_question'] = 'Ins�rer une question';
$FAQ_LANG['insert_question_begening'] = 'Ins�rer une question au d�but';
$FAQ_LANG['update'] = 'Modifier';
$FAQ_LANG['delete'] = 'Supprimer';
$FAQ_LANG['up'] = 'Monter';
$FAQ_LANG['down'] = 'Descendre';
$FAQ_LANG['confirm_delete'] = 'Etes-vous s�r de vouloir supprimer cette question ?';
$FAQ_LANG['category_management'] = 'Gestion d\'une cat�gorie';
$FAQ_LANG['category_manage'] = 'G�rer la cat�gorie';
$FAQ_LANG['question_edition'] = 'Modification d\'une question';
$FAQ_LANG['question_creation'] = 'Cr�ation d\'une question';
$FAQ_LANG['question'] = 'Question';
$FAQ_LANG['entitled'] = 'Intitul�';
$FAQ_LANG['answer'] = 'R�ponse';

//Management
$FAQ_LANG['faq_management'] = 'Gestion de la FAQ';
$FAQ_LANG['faq_configuration'] = 'Configuration de la FAQ';
$FAQ_LANG['faq_questions_list'] = 'Liste des questions';
$FAQ_LANG['cats_management'] = 'Gestion des cat�gories';
$FAQ_LANG['add_cat'] = 'Ajouter une cat�gorie';
$FAQ_LANG['add_question'] = 'Ajouter une question';
$FAQ_LANG['show_all_answers'] = 'Afficher toutes les r�ponses';
$FAQ_LANG['hide_all_answers'] = 'Cacher toutes les r�ponses';
$FAQ_LANG['move'] = 'D�placer';
$FAQ_LANG['moving_a_question'] = 'D�placement d\'une question';
$FAQ_LANG['target_category'] = 'Cat�gorie cible';

//Avertissement
$FAQ_LANG['required_fields'] = 'Les champs marqu�s * sont obligatoires !';
$FAQ_LANG['require_entitled'] = 'Veuillez entrer l\'intitul� de la question';
$FAQ_LANG['require_answer'] = 'Veuillez entrer la r�ponse';
$FAQ_LANG['require_cat_name'] = 'Veuillez entrer le nom de la cat�gorie';

//Administration / categories
$FAQ_LANG['category'] = 'Cat�gorie';
$FAQ_LANG['category_name'] = 'Nom de la cat�gorie';
$FAQ_LANG['category_location'] = 'Emplacement de la cat�gorie';
$FAQ_LANG['category_image'] = 'Image de la cat�gorie';
$FAQ_LANG['removing_category'] = 'Suppression d\'une cat�gorie';
$FAQ_LANG['explain_removing_category'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (questions et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de sa cat�gorie. <strong>Attention, cette action est irr�versible !</strong>';
$FAQ_LANG['delete_category_and_its_content'] = 'Supprimer la cat�gorie et tout son contenu';
$FAQ_LANG['move_category_content'] = 'D�placer son contenu dans :';
$FAQ_LANG['faq_name'] = 'Nom de la FAQ';
$FAQ_LANG['faq_name_explain'] = 'Le nom de la FAQ appara�tra dans le titre et dans l\'arborescence de chaque page';
$FAQ_LANG['nbr_cols'] = 'Nombre de cat�gories par colonne';
$FAQ_LANG['nbr_cols_explain'] = 'Ce nombre est le nombre de colonnes dans lesquelles seront pr�sent�es les sous cat�gories d\'une cat�gorie';
$FAQ_LANG['display_mode_admin_explain'] = 'Vous pouvez choisir la fa�on dont les questions seront affich�es. Le mode en ligne permet d\'afficher les questions et un clic sur la question affiche la r�ponse, alors que le mode en blocs affiche l\'encha�nement des questions et des r�ponses. Il sera possible de choisir pour chaque cat�gorie le mode d\'affichage, il ne s\'agit ici que de la configuration par d�faut.';
$FAQ_LANG['general_auth'] = 'Autorisations g�n�rales';
$FAQ_LANG['general_auth_explain'] = 'Vous configurez ici les autorisations g�n�rales de lecture et d\'�criture sur la FAQ. Vous pourrez ensuite pour chaque cat�gorie lui appliquer des autorisations particuli�res.';

//Errors
$FAQ_LANG['successful_operation'] = 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s';
$FAQ_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement';
$FAQ_LANG['unexisting_category'] = 'La cat�gorie que vous avez s�lectionn�e n\'existe pas';
$FAQ_LANG['new_cat_does_not_exist'] = 'La cat�gorie cible n\'existe pas';
$FAQ_LANG['infinite_loop'] = 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie';

//Module mini
$FAQ_LANG['random_question'] = 'Question al�atoire';
$FAQ_LANG['no_random_question'] = 'Aucune question disponible';

//Others
$FAQ_LANG['recount_success'] = 'Le nombre de questions pour chaque cat�gorie a �t� recompt� avec succ�s.';
$FAQ_LANG['recount_questions_number'] = 'Recompter le nombre de questions pour chaque cat�gorie';

?>