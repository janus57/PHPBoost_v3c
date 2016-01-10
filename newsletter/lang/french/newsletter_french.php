<?php
/*##################################################
 *                             newsletter_french.php
 *                            -------------------
 *   begin                :  July 11 2006
 *   last modified		: July 31, 2009 - Forensic
 *   copyright          : (C) 2006 ben.popeye
 *   email                : ben.popeye@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                                                          French                                                                        #
####################################################

//Admin
$LANG['newsletter'] = 'Newsletter';
$LANG['newsletter_select_type'] = 'Vous devez choisir un type de message';
$LANG['newsletter_select_type_text'] = 'Texte simple';
$LANG['newsletter_select_type_text_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous ne pourrez proc�der � aucune mise en forme du message.';
$LANG['newsletter_select_type_bbcode_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous pouvez formater le texte gr�ce au BBCode, le langage de mise en forme simplifi�e adopt� sur tout le portail.';
$LANG['newsletter_select_type_html_explain'] = '<span style="color:red;"><strong>Utilisateurs exp�riment�s seulement</strong></span><br />Vous pouvez mettre en forme le texte � votre guise, mais vous devez conna�tre le langage html.';
$LANG['newsletter_write_type'] = 'R�diger une newsletter';
$LANG['newsletter_unscubscribe_text'] = 'Pour se d�sinscrire de la newsletter, merci de cliquer ici. ';
$LANG['newsletter_mail_from'] = 'E-mail qui envoit la newsletter';
$LANG['newsletter_send'] = 'Envoyer';
$LANG['newsletter_error'] = 'La newsletter n\'a pas pu �tre d�livr�e au destinataire suivant: ';
$LANG['newsletter_go_to_archives'] = 'Cliquez ici pour voir les archives.';
$LANG['newsletter_subscribe_link'] = 'N\'oubliez pas de mettre <em>[UNSUBSCRIBE_LINK]</em> pour qu\'appara�sse dans votre message le lien pour que le membre puisse se d�sinscrire. Cela sera automatiquement remplac� par le lien. C\'est important vis-�-vis de la libert� � se d�sinscrire de vos utilisateurs!';
$LANG['newsletter_back'] = 'Revenir � l\'envoi de newsletter';
$LANG['newsletter_confirm'] = 'La newsletter a �t� envoy�e avec succ�s.';
$LANG['newsletter_nbr_subscribers'] = 'Nombre d\'abonn�s: ';
$LANG['newsletter_test'] = 'M\'envoyer un test';
$LANG['newsletter_sent_successful'] = 'La newsletter a �t� envoy�e avec succ�s � tous les membres inscrits !';
$LANG['send_newsletter'] = 'Envoyer une newsletter';
$LANG['newsletter_member_list'] = 'Liste des membres';
$LANG['newsletter_test_sent'] = 'Une newsletter vous a �t� envoy�e � l\'adresse %s pour avoir un aper�u de ce que vous envoyez.';
$LANG['newsletter_bbcode_warning'] = 'Lorsque vous enverrez une newsletter en BBCode, cette derni�re sera transform� en HTML lors de l\'envoi. Seulement toutes les balises ne seront pas accept�es chez les fournisseurs de messagerie, c\'est pourquoi nous vous invitons � envoyer un test de la newsletter en utilisant le bouton appropri� pour voir le rendu chez votre fournisseur';

//Newsletter
$LANG['newsletter'] = 'Newsletter';
$LANG['subscribe'] = 'S\'inscrire';
$LANG['unsubscribe'] = 'Se d�sinscrire';
$LANG['newsletter_add_success'] = 'Votre adresse a �t� ajout�e avec succ�s � la liste de la newsletter!';
$LANG['newsletter_add_failure'] = 'Erreur: Vous �tes d�j� inscrit!';
$LANG['newsletter_del_success'] = 'Votre adresse a �t� supprim�e de la base de donn�es avec succ�s';
$LANG['newsletter_del_failure'] = 'L\'adresse que vous voulez supprimer de la liste n\'existe pas.';
$LANG['newsletter_msg_html'] = 'Ce message a �t� envoy� en html, cliquez ici pour voir son contenu';
$LANG['newsletter_nbr'] = 'Nombre d\'abonn�s : %d';
$LANG['newsletter_no_archives'] = 'Aucune archive disponible';
$LANG['newsletter_archives'] = 'Archives de la newsletter';
$LANG['newsletter_archives_explain'] = 'Vous trouverez ici toutes les archives des pr�c�dentes newsletters qui ont �t� envoy�es.
<br />
Pour les recevoir r�guli�rement vous devez vous inscrire.';
$LANG['newsletter_email_address_is_not_valid'] = 'L\'adresse email que vous avez propos�e n\'a pas un format valide. Merci de corriger.';
$LANG['newsletter_error_list'] = 'La newsletter n\'a pas pu �tre envoy�e aux destinataires suivants : <em>%s</em>';
$LANG['archives'] = 'Archives';

//Config
$LANG['newsletter_config'] = 'Configuration de la newsletter';
$LANG['newsletter_sender_mail'] = 'Adresse d\'envoi';
$LANG['newsletter_name'] = 'Nom de la newsletter <span class="text_small">(objet du mail envoy�)</span>';
$LANG['newsletter_confirm_delete_user'] = 'Etes-vous sur de vouloir retirer ce membre de la liste ?';
$LANG['newsletter_email_address'] = 'Adresse email';
$LANG['newsletter_del_member_success'] = 'L\'adresse %s a �t� supprim�e avec succ�s';
$LANG['newsletter_member_does_not_exists'] = 'L\'adresse que vous souhaitez supprimer n\'existe pas';

?>