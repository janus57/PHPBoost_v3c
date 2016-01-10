<?php
/*##################################################
 *                              articles_french.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre R�gis
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

//Admin
$LANG['written_by'] = 'Ecrit par';
$LANG['explain_page'] = 'Ins�rer une nouvelle page';
$LANG['page_prompt'] = 'Titre de la nouvelle page';
$LANG['summary'] = 'Sommaire';
$LANG['select_page'] = 'S�lectionnez une page';
$LANG['articles_management'] = 'Gestion des articles';
$LANG['articles_add'] = 'Ajouter un article';
$LANG['articles_config'] = 'Configuration des articles';
$LANG['articles_cats_add'] = 'Ajouter une cat�gorie';
$LANG['edit_article'] = 'Editer l\'article';
$LANG['cat_edit'] = 'Editer la cat�gorie';
$LANG['nbr_articles_max'] = 'Nombre maximum d\'articles affich�s';
$LANG['articles_date'] = 'Date de l\'article <span class="text_small">(jj/mm/aa)</span> <br />
<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)';
$LANG['icon_cat'] = 'Ic�ne de la cat�gorie';
$LANG['icon_cat_explain'] = 'A placer dans le r�pertoire /articles';
$LANG['parent_category'] = 'Cat�gorie parente';
$LANG['explain_article'] = 'La cat�gorie que vous d�sirez supprimer contient <strong>1</strong> article, voulez-vous la conserver en la transf�rant dans une autre cat�gorie, ou bien la supprimer?';
$LANG['explain_articles'] = 'La cat�gorie que vous d�sirez supprimer contient <strong>%d</strong> articles, voulez-vous les conserver en les transf�rants dans une autre cat�gorie, ou bien tout supprimer?';
$LANG['explain_subcat'] = 'La cat�gorie que vous d�sirez supprimer contient <strong>1</strong> sous-cat�gorie, voulez-vous la conserver en la transf�rant dans une autre cat�gorie, ou bien la supprimer ainsi que son contenu?';
$LANG['explain_subcats'] = 'La cat�gorie que vous d�sirez supprimer contient <strong>%d</strong> sous-cat�gories, voulez-vous les conserver en les transf�rants dans une autre cat�gorie, ou bien les supprimer ainsi que leur contenu?';
$LANG['keep_articles'] = 'Conserver le(s) article(s)';
$LANG['keep_subcat'] = 'Conserver la/les cat�gorie(s)';
$LANG['move_articles_to'] = 'D�placer le(s) article(s) vers';
$LANG['move_subcat_to'] = 'D�placer la/les sous-cat�gorie(s) vers';
$LANG['cat_target'] = 'Cat�gorie cible';
$LANG['del_all'] = 'Suppression compl�te';
$LANG['del_articles_contents'] = 'Supprimer la cat�gorie "<strong>%s</strong>", ses <strong>sous-cat�gories</strong> et <strong>tout</strong> son contenu <span class="text_small">(D�finitif)</span>';
$LANG['article_icon'] = 'Ic�ne de l\'article';
$LANG['article_icon_explain'] = 'A placer dans le repertoire /articles';
$LANG['explain_articles_count'] = 'Recompter le nombre d\'articles par cat�gories';
$LANG['recount'] = 'Recompter';

//Erreurs
$LANG['e_unexist_articles'] = 'L\'article que vous avez demand� n\'existe pas';

//Titres
$LANG['title_articles'] = 'Articles';

//Articles
$LANG['articles'] = 'Articles';
$LANG['alert_delete_article'] = 'Supprimer cet article ?';
$LANG['propose_article'] = 'Proposer un article';
$LANG['none_article'] = 'Aucun article dans cette cat�gorie';
$LANG['xml_articles_desc'] = 'Derniers articles';
$LANG['no_note'] = 'Aucune note';
$LANG['actual_note'] = 'Note actuelle';
$LANG['vote'] = 'Voter';
$LANG['nbr_articles_info'] = '%d article(s) dans la cat�gorie';
$LANG['sub_categories'] = 'Sous cat�gories';

//Ajout article.
$MAIL['new_article_website'] = 'Nouvel article sur votre site web';
$MAIL['new_article'] = 'Un nouvel article a �t� ajout� sur votre site web ' . HOST . ', 
il devra �tre approuv� avant d\'�tre visible sur le site par tout le monde.

Titre de l\'article: %s
Contenu: %s...[suite]
Post� par: %s

Rendez-vous dans le panneau gestion des articles de l\'administration, pour l\'approuver.
' . HOST . DIR . '/admin/admin_articles_gestion.php';

$LANG['read_feed'] = 'Lire l\'article';
$LANG['posted_on'] = 'Le';
?>