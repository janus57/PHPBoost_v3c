<?php
/*##################################################
 *                              forum_french.php
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
#                                                           French                                                                 #
####################################################

global $CONFIG;

//Admin
$LANG['parent_category'] = 'Cat�gorie parente';
$LANG['subcat'] = 'Sous-cat�gorie';
$LANG['url_explain'] = 'Transforme le forum en lien internet (http://...)';
$LANG['lock'] = 'Verrouill�';
$LANG['unlock'] = 'D�verrouill�';
$LANG['cat_edit'] = 'Editer une cat�gorie';
$LANG['del_cat'] = 'Outil de suppression de sous-cat�gorie';
$LANG['explain_topic'] = 'Le forum que vous d�sirez supprimer contient <strong>1</strong> sujet, voulez-vous le conserver en le transf�rant dans un autre forum, ou bien le supprimer?';
$LANG['explain_topics'] = 'Le forum que vous d�sirez supprimer contient <strong>%d</strong> sujets, voulez-vous les conserver en les transf�rants dans un autre forum, ou bien tout supprimer?';
$LANG['explain_subcat'] = 'Le forum que vous d�sirez supprimer contient <strong>1</strong> sous-forum, voulez-vous le conserver en le transf�rant dans un autre forum, ou bien le supprimer ainsi que son contenu?';
$LANG['explain_subcats'] = 'Le forum que vous d�sirez supprimer contient <strong>%d</strong> sous-forums, voulez-vous les conserver en les transf�rants dans un autre forum, ou bien les supprimer ainsi que leur contenu?';
$LANG['keep_topic'] = 'Conserver le(s) sujet(s)';
$LANG['keep_subforum'] = 'Conserver le(s) sous-forum(s)';
$LANG['move_topics_to'] = 'D�placer le(s) sujet(s) vers';
$LANG['move_sub_forums_to'] = 'D�placer le(s) sous-forum(s) vers';
$LANG['cat_target'] = 'Cat�gorie cible';
$LANG['del_all'] = 'Suppression compl�te';
$LANG['del_forum_contents'] = 'Supprimer le forum "<strong>%s</strong>", ses <strong>sous-forums</strong> et <strong>tout</strong> son contenu <span class="text_small">(D�finitif)</span>';
$LANG['forum_config'] = 'Configuration du forum';
$LANG['forum_management'] = 'Gestion du forum';
$LANG['forum_name'] = 'Nom du forum';
$LANG['nbr_topic_p'] = 'Nombre de sujets par page';
$LANG['nbr_topic_p_explain'] = 'Par d�faut 20';
$LANG['nbr_msg_p'] = 'Nombre de messages par page';
$LANG['nbr_msg_p_explain'] = 'Par d�faut 15';
$LANG['time_new_msg'] = 'Dur�e pour laquelle les messages lus par les membres sont stock�s';
$LANG['time_new_msg_explain'] = 'A r�gler suivant le nombre de messages par jour, par d�faut 30 jours';
$LANG['topic_track_max'] = 'Nombre maximum possible de sujets en favoris';
$LANG['topic_track_max_explain'] = 'Par d�faut 40';
$LANG['edit_mark'] = 'Marqueurs d\'�dition des messages';
$LANG['forum_display_connexion'] = 'Afficher le formulaire de connexion';
$LANG['no_left_column'] = 'Masquer les blocs de gauche du site sur le forum';
$LANG['no_right_column'] = 'Masquer les blocs de droite du site sur le forum';
$LANG['activ_display_msg'] = 'Activer le message devant le topic';
$LANG['display_msg'] = 'Message devant le titre du topic';
$LANG['explain_display_msg'] = 'Explication du message pour les membres';
$LANG['explain_display_msg_explain'] = 'Si statut non chang�';
$LANG['explain_display_msg_bis_explain'] = 'Si statut chang�';
$LANG['icon_display_msg'] = 'Ic�ne associ�e';
$LANG['update_data_cached'] = 'Recompter le nombre de sujets et de messages';
$LANG['explain_forum_groups'] = 'Ces configurations affectent uniquement le forum';
$LANG['forum_groups_config'] = 'Configuration des groupes';
$LANG['flood_auth'] = 'Droit de flooder';
$LANG['edit_mark_auth'] = 'D�sactivation du marqueur d\'�dition des messages';
$LANG['track_topic_auth'] = 'D�sactivation de la limite de sujet suivis';
$LANG['forum_read_feed'] = 'Lire le sujet';

//Requis
$LANG['require_topic_p'] = 'Veuillez entrer le nombre de sujets par page!';
$LANG['require_nbr_msg_p'] = 'Veuillez entrer le nombre de messages par page!';
$LANG['require_time_new_msg'] = 'Veuillez entrer une dur�e pour la vue des nouveaux messages!';
$LANG['require_topic_track_max'] = 'Veuillez entrer le nombre maximum de sujet suivis!';

//Erreurs
$LANG['e_topic_lock_forum'] = 'Sujet verrouill�, vous ne pouvez pas poster de message';
$LANG['e_cat_lock_forum'] = 'Cat�gorie verrouill�e, cr�ation nouveau sujet/message impossible';
$LANG['e_unexist_topic_forum'] = 'Le topic que vous demandez n\'existe pas';
$LANG['e_unexist_cat_forum'] = 'La cat�gorie que vous demandez n\'existe pas';
$LANG['e_unable_cut_forum'] = 'Vous ne pouvez pas scinder le sujet � partir de ce message';
$LANG['e_cat_write'] = 'Vous n\'�tes pas autoris� � �crire dans cette cat�gorie';

//Alertes
$LANG['alert_delete_topic'] = 'Supprimer ce sujet ?';
$LANG['alert_lock_topic'] = 'Verrouiller ce sujet ?';
$LANG['alert_unlock_topic'] = 'D�verrouiller ce sujet ?';
$LANG['alert_move_topic'] = 'D�placer ce sujet ?';
$LANG['alert_warning'] = 'Avertir ce membre ?';
$LANG['alert_history'] = 'Supprimer l\'historique ?';
$LANG['confirm_mark_as_read'] = 'Marquer tous les sujets comme lus ?';
$LANG['contribution_alert_moderators_for_topics'] = 'Sujet non conforme : %s';

//Titres
$LANG['title_forum'] = 'Forum';
$LANG['title_topic'] = 'Sujet';
$LANG['title_post'] = 'Poster';
$LANG['title_search'] = 'Chercher';

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Sous-forums';
$LANG['topic'] = 'Sujet';
$LANG['topic_s'] = 'Sujets';
$LANG['author'] = 'Auteur';
$LANG['advanced_search'] = 'Recherche avanc�e';
$LANG['distributed'] = 'R�partis en';
$LANG['mark_as_read'] = 'Marquer comme lu';
$LANG['show_topic_track'] = 'Sujets suivis';
$LANG['no_msg_not_read'] = 'Aucun message non lu';
$LANG['show_not_reads'] = 'Messages non lus';
$LANG['show_last_read'] = 'Derniers messages lus';
$LANG['no_last_read'] = 'message lu';
$LANG['last_message'] = 'Dernier message';
$LANG['last_messages'] = 'Derniers messages';
$LANG['forum_new_subject'] = 'Nouveau sujet';
$LANG['post_new_subject'] = 'Poster un nouveau sujet';
$LANG['forum_edit_subject'] = 'Editer Sujet';
$LANG['forum_announce'] = 'Annonce';
$LANG['forum_postit'] = 'Epingl�';
$LANG['forum_lock'] = 'Verrouiller';
$LANG['forum_unlock'] = 'D�verrouiller';
$LANG['forum_move'] = 'D�placer';
$LANG['forum_move_subject'] = 'D�placer le sujet';
$LANG['forum_quote_last_msg'] = 'Reprise du message pr�c�dent';
$LANG['edit_message'] = 'Editer Message';
$LANG['edit_by'] = 'Edit� par';
$LANG['no_message'] = 'Pas de message';
$LANG['group'] = 'Groupe';
$LANG['cut_topic'] = 'Scinder le sujet � partir de ce message';
$LANG['forum_cut_subject'] = 'Scinder le sujet';
$LANG['alert_cut_topic'] = 'Voulez-vous scinder le sujet � partir de ce message?';
$LANG['track_topic'] = 'Mettre en favori';
$LANG['untrack_topic'] = 'Retirer des favoris';
$LANG['track_topic_pm'] = 'Suivre par message priv�';
$LANG['untrack_topic_pm'] = 'Arr�ter le suivi message priv�';
$LANG['track_topic_mail'] = 'Suivre par mail';
$LANG['untrack_topic_mail'] = 'Arr�ter le suivi mail';
$LANG['alert_topic'] = 'Alerter les mod�rateurs';
$LANG['banned'] = 'Banni';
$LANG['xml_forum_desc'] = 'Derniers sujets du forum';
$LANG['alert_modo_explain'] = 'Vous �tes sur le point d\'alerter les mod�rateurs. Vous aidez l\'�quipe mod�ratrice en lui signalant des topics qui ne respectent pas certaines r�gles, mais sachez que lorsque vous alertez un mod�rateur votre pseudo est enregistr�, il est donc n�cessaire que votre demande soit justifi�e sans quoi vous risquez des sanctions de la part de l\'�quipe des mod�rateurs et administrateurs en cas d\'abus. Afin d\'aider l\'�quipe, merci d\'expliquer ce qui ne respecte pas les conditions dans ce sujet. 

Vous d�sirez alerter les mod�rateurs d\'un probl�me sur le sujet suivant';
$LANG['alert_title'] = 'Br�ve description';
$LANG['alert_contents'] = 'Merci de d�tailler davantage le probl�me afin d\'aider l\'�quipe mod�ratrice';
$LANG['alert_success'] = 'Vous avez signal� avec succ�s la non-conformit� du sujet <em>%title</em>, l\'�quipe mod�ratrice vous remercie de l\'avoir aid�e.';
$LANG['alert_topic_already_done'] = 'Nous vous remercions d\'avoir pris l\'initiative d\'aider l\'�quipe mod�ratrice, mais un membre a d�j� signal� une non-conformit� de ce sujet.';
$LANG['alert_back'] = 'Retour au sujet';
$LANG['explain_track'] = 'Cochez la case Mp pour recevoir un message priv�, Mail pour un email lors d\'une r�ponse au sujet que vous suivez. Cochez la case supprimer pour ne plus suivre le sujet.';
$LANG['sub_forums'] = 'Sous-forums';
$LANG['moderation_forum'] = 'Mod�ration du forum';
$LANG['no_topics'] = 'Aucun sujet � afficher';
$LANG['for_selection'] = 'Pour la s�lection';
$LANG['change_status_to'] = 'Mettre le statut: %s';
$LANG['change_status_to_default'] = 'Mettre le statut par d�faut';
$LANG['move_to'] = 'D�placer vers...';

//Recherche
$LANG['search_forum'] = 'Recherche sur le Forum';
$LANG['relevance'] = 'Pertinence';
$LANG['no_result'] = 'Aucun r�sultat';
$LANG['invalid_req'] = 'Requ�te invalide';
$LANG['keywords'] = 'Mots cl�s (4 caract�res minimum)';
$LANG['colorate_result'] = 'Colorer les r�sultats';

//Stats
$LANG['stats'] = 'Statistiques';
$LANG['nbr_topics_day'] = 'Nombre de sujets par jour';
$LANG['nbr_msg_day'] = 'Nombre de messages par jour';
$LANG['nbr_topics_today'] = 'Nombre de sujets aujourd\'hui';
$LANG['nbr_msg_today'] = 'Nombre de messages aujourd\'hui';
$LANG['forum_last_msg'] = 'Les 10 derniers sujets ayant eu une r�ponse';
$LANG['forum_popular'] = 'Les 10 sujets les plus populaires';
$LANG['forum_nbr_answers'] = 'Les 10 sujets ayant eu le plus de r�ponses';

//Historique
$LANG['history'] = 'Historique des actions';
$LANG['history_member_concern'] = 'Membre concern�';
$LANG['no_action'] = 'Aucune action enregistr�e';
$LANG['delete_msg'] = 'Suppression d\'un message';
$LANG['delete_topic'] = 'Suppression d\'un sujet';
$LANG['lock_topic'] = 'Verrouillage d\'un sujet';
$LANG['unlock_topic'] = 'D�verrouillage d\'un sujet';
$LANG['move_topic'] = 'D�placement d\'un sujet';
$LANG['cut_topic'] = 'Scindement d\'un sujet';
$LANG['warning_on_user'] = '+10% � un membre';
$LANG['warning_off_user'] = '-10% � un membre';
$LANG['set_warning_user'] = 'Modification pourcentage avertissement';
$LANG['more_action'] = 'Voir 100 actions en plus';
$LANG['ban_user'] = 'Bannissement d\'un membre';
$LANG['edit_msg'] = 'Edition message d\'un membre';
$LANG['edit_topic'] = 'Edition sujet d\'un membre';
$LANG['solve_alert'] = 'R�solution d\'une alerte';
$LANG['wait_alert'] = 'Mise en attente d\'une alerte';
$LANG['del_alert'] = 'Suppression d\'une alerte';

//Messages du membre
$LANG['show_member_msg'] = 'Voir tous les messages du membre';

//Sondages
$LANG['poll'] = 'Sondage';
$LANG['mini_poll'] = 'Mini Sondage';
$LANG['poll_main'] = 'Voila l\'espace de sondage du site, profitez en pour donner votre avis, ou tout simplement r�pondre aux sondages.';
$LANG['poll_back'] = 'Retour au(x) sondage(s)';
$LANG['redirect_none'] = 'Aucun sondage disponible';
$LANG['confirm_vote'] = 'Votre vote a bien �t� pris en compte';
$LANG['already_vote'] = 'Vous avez d�j� vot�';
$LANG['no_vote'] = 'Votre vote nul a �t� consid�r�';
$LANG['poll_vote'] = 'Vote';
$LANG['poll_vote_s'] = 'Votes';
$LANG['poll_result'] = 'R�sultats';
$LANG['alert_delete_poll'] = 'Supprimer ce sondage ?';
$LANG['unauthorized_poll'] = 'Vous n\'�tes pas autoris� � voter !';
$LANG['question'] = 'Question';
$LANG['answers'] = 'R�ponses';
$LANG['poll_type'] = 'Type de sondage';
$LANG['open_menu_poll'] = 'Ouvrir le menu sondage';
$LANG['simple_answer'] = 'Simple r�ponse';
$LANG['multiple_answer'] = 'Multiple r�ponses';
$LANG['delete_poll'] = 'Supprimer le sondage';
$LANG['require_title_poll'] = 'Veuillez entrer un titre pour le sondage!';

//Post
$LANG['forum_mail_title_new_post'] = 'Nouveau message sur le forum';
$LANG['forum_mail_new_post'] = 'Cher %s

Vous suivez le sujet: %s
 
Vous avez demand� � �tre averti lors d\'une r�ponse � celui-ci.

%s a r�pondu sur le sujet: 
%s

[Suite du message : %s]




Si vous ne d�sirez plus �tre averti des r�ponses de ce sujet, cliquez sur le lien ci-dessous. 
%s

' . $CONFIG['sign'];

//Gestion des alertes
$LANG['alert_management'] = 'Gestion des alertes';
$LANG['alert_concerned_topic'] = 'Sujet concern�';
$LANG['alert_concerned_cat'] = 'Cat�gorie du sujet concern�';
$LANG['alert_login'] = 'Posteur de l\'alerte';
$LANG['alert_msg'] = 'Pr�cisions';
$LANG['alert_not_solved'] = 'En attente de traitement';
$LANG['alert_solved'] = 'R�solue par ';
$LANG['change_status_to_0'] = 'Mettre en attente de traitement';
$LANG['change_status_to_1'] = 'Mettre en r�solu';
$LANG['no_alert'] = 'Il n\'y a aucune alerte pour l\'instant';
$LANG['alert_not_auth'] = 'Cette alerte a �t� post�e dans un forum dans lequel vous n\'�tes pas mod�rateur.';
$LANG['delete_several_alerts'] = 'Etes vous sur de vouloir supprimer les alertes s�lectionn�es?';
$LANG['new_alerts'] = 'nouvelle alerte';
$LANG['new_alerts_s'] = 'nouvelles alertes';
$LANG['action'] = 'Action';

?>