<?php
/*##################################################
 *                                admin.php
 *                            -------------------
 *   begin                : November 20, 2005
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
#                    French                        #
####################################################

$LANG['xml_lang'] = 'fr';
$LANG['administration'] = 'Administration';
$LANG['no_administration'] = 'Aucune administration associ�e avec ce module!';

//Titre Modules par d�fauts
$LANG['index'] = 'Index';
$LANG['tools'] = 'Outils';
$LANG['link_management'] = 'Gestion des liens';
$LANG['menu_management'] = 'Menus';
$LANG['moderation'] = 'Panneau mod�ration';
$LANG['maintain'] = 'Maintenance';
$LANG['updater'] = 'Mises � jour';
$LANG['extend_field'] = 'Champs membres';
$LANG['ranks'] = 'Rangs';
$LANG['terms'] = 'R�glement';
$LANG['pages'] = 'Pages';
$LANG['files'] = 'Fichiers';
$LANG['themes'] = 'Th�mes';
$LANG['languages'] = 'Langues';
$LANG['smile'] = 'Smileys';
$LANG['comments'] = 'Commentaires';
$LANG['group'] = 'Groupes';
$LANG['stats'] = 'Statistiques';
$LANG['errors'] = 'Erreurs archiv�es';
$LANG['server'] = 'Serveur';
$LANG['phpinfo'] = 'PHP info';
$LANG['cache'] = 'Cache';
$LANG['punishement'] = 'Sanction';
$LANG['extend_menu'] = 'Menu �tendu';

//Form
$LANG['add'] = 'Ajouter';

//Alertes formulaires
$LANG['alert_same_pass'] = 'Les mots de passe ne sont pas identiques!';
$LANG['alert_max_dim'] = 'Le fichier d�passe les largeurs et hauteurs maximales sp�cifi�es !';
$LANG['alert_error_avatar'] = 'Erreur d\'enregistrement de l\'avatar!';
$LANG['alert_error_img'] = 'Erreur d\'enregistrement de l\'image!';
$LANG['alert_invalid_file'] = 'Le fichier image n\'est pas valide (jpg, gif, png!)';
$LANG['alert_max_weight'] = 'Image trop lourde';
$LANG['alert_s_already_use'] = 'Code du smiley d�j� utilis�!';
$LANG['alert_no_cat'] = 'Aucun nom/cat�gorie saisi';
$LANG['alert_fct_unlink'] = 'Suppression des miniatures impossible. Vous devez supprimer manuellement sur le ftp!';
$LANG['alert_no_login'] = 'Le pseudo entr� n\'existe pas!';

//Requis
$LANG['require'] = 'Les Champs marqu�s * sont obligatoires!';
$LANG['require_title'] = 'Veuillez entrer un titre !';
$LANG['require_text'] = 'Veuillez entrer un texte!';
$LANG['require_pseudo'] = 'Veuillez entrer un pseudo!';
$LANG['require_password'] = 'Veuillez entrer un password!';
$LANG['require_mail'] = 'Veuillez entrer un mail valide!';
$LANG['require_cat'] = 'Veuillez entrer une cat�gorie!';
$LANG['require_cat_create'] = 'Aucune cat�gorie trouv�e, veuillez d\'abord en cr�er une';
$LANG['require_url'] = 'Veuillez entrer une url valide!';
$LANG['require_serv'] = 'Veuillez entrer un nom pour le serveur!';
$LANG['require_name'] = 'Veuillez entrer un nom!';
$LANG['require_cookie_name'] = 'Veuillez entrer un nom de cookie!';
$LANG['require_session_time'] = 'Veuillez entrer une dur�e pour la session!';
$LANG['require_session_invit'] = 'Veuillez entrer une dur�e pour la session invit�!';
$LANG['require_pass'] = 'Veuillez entrer un mot de passe!';
$LANG['require_rank'] = 'Veuillez entrer un rang!';
$LANG['require_code'] = 'Veuillez entrer un code pour le smiley!';
$LANG['require_max_width'] = 'Veuillez entrer une largeur maximale pour les avatars!';
$LANG['require_height'] = 'Veuillez entrer une hauteur maximale pour les avatars!';
$LANG['require_weight'] = 'Veuillez entrer un poids maximum pour les avatars!';
$LANG['require_rank_name'] = 'Veuillez entrer un nom pour le rang!';
$LANG['require_nbr_msg_rank'] = 'Veuillez entrer un nombre de messages pour le rang!';
$LANG['require_subcat'] = 'Veuillez s�lectionner une sous-cat�gorie!';
$LANG['require_file_name'] = 'Vous devez saisir un nom de fichier';

//Confirmations.
$LANG['redirect'] = 'Redirection en cours...';
$LANG['del_entry'] = 'Supprimer l\'entr�e?';
$LANG['confirm_del_member'] = 'Supprimer le membre? (d�finitif !)';
$LANG['confirm_del_admin'] = 'Supprimer un admin? (irr�versible !)';
$LANG['confirm_theme'] = 'Supprimer le th�me?';
$LANG['confirm_del_smiley'] = 'Supprimer le smiley?';
$LANG['confirm_del_cat'] = 'Supprimer cette cat�gorie ?';
$LANG['confirm_del_article'] = 'Supprimer cet article?';
$LANG['confirm_del_rank'] = 'Supprimer ce rang ?';
$LANG['confirm_del_group'] = 'Supprimer ce groupe ?';
$LANG['confirm_del_member_group'] = 'Supprimer ce membre du groupe ?';

//bbcode
$LANG['bb_bold'] = 'Texte en gras : [b]texte[/b]';
$LANG['bb_italic'] = 'Texte en italique : [i]texte[/i]';
$LANG['bb_underline'] = 'Texte soulign� : [u]texte[/u]';
$LANG['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$LANG['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$LANG['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$LANG['bb_color'] = 'Couleur du texte : [color=X]texte de taille X[/color]';
$LANG['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$LANG['bb_code'] = 'Ins�rer du code (PHP color�) [code]texte[/code]';
$LANG['bb_left'] = 'Positionner � gauche : [align=left]objet � gauche[/align]';
$LANG['bb_center'] = 'Centrer : [align=center]objet centr�[/align]';
$LANG['bb_right'] = 'Positionner � droite : [align=right]objet � droite[/align]';

//Commun
$LANG['pseudo'] = 'Pseudo';
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['description'] = 'Description';
$LANG['view'] = 'Vu';
$LANG['views'] = 'Vues';
$LANG['name'] = 'Nom';
$LANG['title'] = 'Titre';
$LANG['message'] = 'Message';
$LANG['aprob'] = 'Approbation';
$LANG['unaprob'] = 'D�sapprobation';
$LANG['url'] = 'Adresse';
$LANG['categorie'] = 'Cat�gorie';
$LANG['note'] = 'Note';
$LANG['date'] = 'Date';
$LANG['com'] = 'Commentaires';
$LANG['size'] = 'Taille';
$LANG['file'] = 'Fichier';
$LANG['download'] = 'T�l�charg�';
$LANG['delete'] = 'Supprimer';
$LANG['user_ip'] = 'Adresse ip';
$LANG['localisation'] = 'Localisation';
$LANG['activ'] = 'Activ�';
$LANG['unactiv'] = 'D�sactiv�';
$LANG['img'] = 'Image';
$LANG['activation'] = 'Activation';
$LANG['position'] = 'Position';
$LANG['path'] = 'Chemin';
$LANG['on'] = 'Le';
$LANG['at'] = '�';
$LANG['registered'] = 'Enregistr�';
$LANG['website'] = 'Site web';
$LANG['search'] = 'Recherche';
$LANG['mail'] = 'Mail';
$LANG['password'] = 'Mot de passe';
$LANG['contact'] = 'Contact';
$LANG['info'] = 'Informations';
$LANG['language'] = 'Langue';
$LANG['sanction'] = 'Sanction';
$LANG['ban'] = 'Banni';
$LANG['theme'] = 'Th�me';
$LANG['code'] = 'Code';
$LANG['status'] = 'Statut';
$LANG['question'] = 'Question';
$LANG['answers'] = 'R�ponses';
$LANG['archived'] = 'Archiv�';
$LANG['galerie'] = 'Galerie' ;
$LANG['select'] = 'S�lectionner';
$LANG['pics'] = 'Photos';
$LANG['empty'] = 'Vider';
$LANG['show'] = 'Consulter';
$LANG['link'] = 'Lien';
$LANG['type'] = 'Type';
$LANG['of'] = 'de';
$LANG['autoconnect'] = 'Connexion automatique';
$LANG['unspecified'] = 'Non sp�cifi�';
$LANG['configuration'] = 'Configuration';
$LANG['management'] = 'Gestion';
$LANG['add'] = 'Ajouter';
$LANG['category'] = 'Cat�gorie';
$LANG['site'] = 'Site';
$LANG['modules'] = 'Modules';
$LANG['powered_by'] = 'Boost� par';
$LANG['release_date'] = 'Date de parution jj/mm/aa';
$LANG['immediate'] = 'Imm�diate';
$LANG['waiting'] = 'En attente';
$LANG['stats'] = 'Statistiques';
$LANG['cat_management'] = 'Gestion des cat�gories';
$LANG['cat_add'] = 'Ajouter une cat�gorie';
$LANG['visible'] = 'Visible';
$LANG['undefined'] = 'Ind�termin�';
$LANG['nbr_cat_max'] = 'Nombre de cat�gories maximum affich�es';
$LANG['nbr_column_max'] = 'Nombre de colonnes';
$LANG['note_max'] = 'Echelle de notation';
$LANG['max_link'] = 'Nombre de liens maximum dans le message';
$LANG['max_link_explain'] = 'Mettre -1 pour illimit�';
$LANG['generate'] = 'G�n�rer';
$LANG['or_direct_path'] = 'Ou chemin direct';
$LANG['unknow_bot'] = 'Bot inconnu';
$LANG['captcha_difficulty'] = 'Difficult� du code de v�rification';

//Connexion
$LANG['unlock_admin_panel'] = 'D�verrouillage de l\'administration';
$LANG['flood_block'] = 'Il vous reste %d essai(s) apr�s cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$LANG['flood_max'] = 'Vous avez �puis� tous vos essais de connexion, votre compte est verrouill� pendant 5 minutes';

//Rang
$LANG['rank_management'] = 'Gestion des rangs';
$LANG['upload_rank'] = 'Uploader une image de rang';
$LANG['upload_rank_format'] = 'JPG, GIF, PNG, BMP autoris�s';
$LANG['rank_add'] = 'Ajouter un rang';
$LANG['rank'] = 'Rang';
$LANG['special_rank'] = 'Rang sp�cial';
$LANG['rank_name'] = 'Nom du Rang';
$LANG['nbr_msg'] = 'Nombre de message(s)';
$LANG['img_assoc'] = 'Image associ�e';
$LANG['guest'] = 'Visiteur';
$LANG['a_member'] = 'membre';
$LANG['member'] = 'Membre';
$LANG['a_modo'] = 'modo';
$LANG['modo'] = 'Mod�rateur';
$LANG['a_admin'] = 'admin';
$LANG['admin'] = 'Administrateur';

//Champs suppl�mentaires
$LANG['extend_field_management'] = 'Gestion des champs membres';
$LANG['extend_field_add'] = 'Ajouter un champ membre';
$LANG['required_field'] = 'Champ requis';
$LANG['required_field_explain'] = 'Obligatoire dans le profil du membre et � son inscription.';
$LANG['required'] = 'Requis';
$LANG['not_required'] = 'Non requis';
$LANG['regex'] = 'Contr�le de la forme de l\'entr�e';
$LANG['regex_explain'] = 'Permet d\'effectuer un contr�le sur la forme de ce que l\'utilisateur a entr�e. Par exemple, si il s\'agit d\'une adresse mail, on peut contr�ler que sa forme est correcte. <br />Vous pouvez effectuer un contr�le personnali� en tapant une expression r�guli�re (utilisateurs exp�riment�s seulement).';
$LANG['possible_values'] = 'Valeurs possibles';
$LANG['possible_values_explain'] = 'S�parez les diff�rentes valeurs par le symbole |';
$LANG['default_values'] = 'Valeurs par d�faut';
$LANG['default_values_explain'] = 'S�parez les diff�rentes valeurs par le symbole |';
$LANG['short_text'] = 'Texte court (max 255 caract�res)';
$LANG['long_text'] = 'Texte long (illimit�)';
$LANG['sel_uniq'] = 'S�lection unique (parmi plusieurs valeurs)';
$LANG['sel_mult'] = 'S�lection multiple (parmi plusieurs valeurs)';
$LANG['check_uniq'] = 'Choix unique (parmi plusieurs valeurs)';
$LANG['check_mult'] = 'Choix multiples (parmi plusieurs valeurs)';
$LANG['personnal_regex'] = 'Expression r�guli�re personnalis�e';
$LANG['predef_regexp'] = 'Forme pr�d�finie';
$LANG['figures'] = 'Chiffres';
$LANG['letters'] = 'Lettres';
$LANG['figures_letters'] = 'Chiffres et lettres';
$LANG['default_field_possible_values'] = 'Oui|Non';
$LANG['extend_field_edit'] = 'Editer le champs';

//Index
$LANG['update_available'] = 'Mises � jour disponibles';
$LANG['core_update_available'] = 'Nouvelle version <strong>%s</strong> du noyau disponible, pensez � mettre � jour PHPBoost! <a href="http://www.phpboost.com">Plus d\'informations</a>';
$LANG['no_core_update_available'] = 'Aucune nouvelle version disponible, le syst�me est � jour!';
$LANG['module_update_available'] = 'Des mises � jour des modules sont disponibles!';
$LANG['no_module_update_available'] = 'Aucune mise � jour des modules, vous �tes � jour!';
$LANG['unknow_update'] = 'Impossible de d�terminer si une mise � jour est disponible!';
$LANG['user_online'] = 'Utilisateur(s) en ligne';
$LANG['last_update'] = 'Derni�re mise � jour';
$LANG['quick_links'] = 'Liens rapides';
$LANG['members_managment'] = 'Gestion des membres';
$LANG['menus_managment'] = 'Gestion des menus';
$LANG['modules_managment'] = 'Gestion des modules';
$LANG['last_comments'] = 'Derniers commentaires';
$LANG['view_all_comments'] = 'Voir tous les commentaires';
$LANG['writing_pad'] = 'Bloc-notes';
$LANG['writing_pad_explain'] = 'Cet emplacement est r�serv� pour y saisir vos notes personnelles.';

//Alertes administrateur
$LANG['administrator_alerts'] = 'Alertes';
$LANG['administrator_alerts_list'] = 'Liste des alertes';
$LANG['no_unread_alert'] = 'Aucune alerte en attente';
$LANG['unread_alerts'] = 'Des alertes non trait�es sont en attente.';
$LANG['no_administrator_alert'] = 'Aucune alerte existante';
$LANG['display_all_alerts'] = 'Voir toutes les alertes';
$LANG['priority'] = 'Priorit�';
$LANG['priority_very_high'] = 'Imm�diat';
$LANG['priority_high'] = 'Urgent';
$LANG['priority_medium'] = 'Moyenne';
$LANG['priority_low'] = 'Faible';
$LANG['priority_very_low'] = 'Tr�s faible';
$LANG['administrator_alerts_action'] = 'Actions';
$LANG['admin_alert_fix'] = 'R�gler';
$LANG['admin_alert_unfix'] = 'Passer l\'alerte en non r�gl�e';
$LANG['confirm_delete_administrator_alert'] = 'Etes-vous s�r de vouloir supprimer cette alerte ?';
	
//Config
$LANG['config_main'] = 'Configuration g�n�rale';
$LANG['config_advanced'] = 'Configuration avanc�e';
$LANG['serv_name'] = 'URL du serveur';
$LANG['serv_path'] = 'Chemin de PHPBoost';
$LANG['serv_path_explain'] = 'Vide par d�faut : site � la racine du serveur';
$LANG['site_name'] = 'Nom du site';
$LANG['serv_name_explain'] = 'Ex : http://www.phpboost.com';
$LANG['site_desc'] = 'Description du site';
$LANG['site_desc_explain'] = '(facultatif) Utile pour le r�f�rencement dans les moteurs de recherche';
$LANG['site_keywords'] = 'Mots cl�s du site';
$LANG['site_keywords_explain'] = '(facultatif) A rentrer s�par�s par des virgules, ils servent au r�f�rencement dans les moteurs de recherche';
$LANG['default_language'] = 'Langue (par d�faut) du site';
$LANG['default_theme'] = 'Th�me (par d�faut) du site';
$LANG['start_page'] = 'Page de d�marrage du site';
$LANG['no_module_starteable'] = 'Aucun module de d�marrage trouv�';
$LANG['other_start_page'] = 'Autre adresse relative ou absolue';
$LANG['activ_gzhandler'] = 'Activation de la compression des pages, ceci acc�l�re la vitesse d\'affichage';
$LANG['activ_gzhandler_explain'] = 'Attention votre serveur doit le supporter';
$LANG['view_com'] = 'Affichage des commentaires';
$LANG['rewrite'] = 'Activation de la r��criture des urls';
$LANG['explain_rewrite'] = 'L\'activation de la r��criture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compr�hensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre r�f�rencement sera grandement optimis� gr�ce � cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les h�bergeurs. Cette page va vous permettre de tester si votre serveur supporte la r��criture des urls. Si apr�s le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> � la racine de votre site via acc�s FTP � votre serveur, puis revenez sur cette page et d�sactivez la r��criture.';
$LANG['server_rewrite'] = 'R��criture des urls sur votre serveur';
$LANG['htaccess_manual_content'] = 'Contenu du fichier .htaccess';
$LANG['htaccess_manual_content_explain'] = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez int�grer au fichier .htaccess qui se trouve � la racine du site, par exemple pour forcer une configuration du serveur web Apache.';
$LANG['current_page'] = 'Page courante';
$LANG['new_page'] = 'Nouvelle fen�tre';
$LANG['compt'] = 'Compteur';
$LANG['bench'] = 'Benchmark';
$LANG['bench_explain'] = 'Affiche le temps de rendu de la page et le nombre de requ�tes SQL';
$LANG['theme_author'] = 'Info sur le th�me';
$LANG['theme_author_explain'] = 'Affiche des informations sur le th�me dans le pied de page';
$LANG['debug_mode'] = 'Mode Debug';
$LANG['debug_mode_explain'] = 'Ce mode est particuli�rement utile pour les d�veloppeurs car les erreurs sont affich�es explicitement. Il est d�conseill� d\'utiliser ce mode sur un site en production.';
$LANG['user_connexion'] = 'Connexion utilisateurs';
$LANG['cookie_name'] = 'Nom du cookie des sessions';
$LANG['session_time'] = 'Dur�e de la session';
$LANG['session_time_explain'] = '3600 secondes conseill�';
$LANG['session invit'] = 'Dur�e utilisateurs actifs';
$LANG['session invit_explain'] = '300 secondes conseill�';
$LANG['post_management'] = 'Gestion des posts';
$LANG['pm_max'] = 'Nombre maximum de messages priv�s';
$LANG['anti_flood'] = 'Anti-flood';
$LANG['int_flood'] = 'Intervalle minimal de temps entre les messages';
$LANG['pm_max_explain'] = 'Illimit� pour administrateurs et mod�rateurs';
$LANG['anti_flood_explain'] = 'Emp�che les messages trop rapproch�s, sauf si les visiteurs sont autoris�s';
$LANG['int_flood_explain'] = '7 secondes par d�faut';
$LANG['email_management'] = 'Gestion des emails';
$LANG['email_admin_exp'] = 'Email d\'exp�dition';
$LANG['email_admin_explain_exp'] = 'Email qui sera vu par le destinataire';
$LANG['email_admin'] = 'Emails des administrateurs';
$LANG['admin_sign'] = 'Signature du mail';
$LANG['email_admin_explain'] = 'S�parez les mails par ;';
$LANG['admin_sign_explain'] = 'En bas de tous les mails envoy�s par le site';
$LANG['cache_success'] = 'Le cache a �t� r�g�n�r� avec succ�s!';
$LANG['explain_site_cache'] = 'R�g�n�ration totale du cache du site � partir de la base de donn�es.
<br /><br />Le cache permet d\'am�liorer notablement la vitesse d\'ex�cution des pages, et all�ge le travail du serveur SQL. A noter que si vous faites des modifications vous-m�me dans la base de donn�es, elles ne seront visibles qu\'apr�s avoir r�g�n�r� le cache';
$LANG['explain_site_cache_syndication'] = 'R�g�n�ration totale du cache des flux RSS et ATOM du site � partir de la base de donn�es.
<br /><br />Le cache permet d\'am�liorer notablement la vitesse d\'ex�cution des pages, et all�ge le travail du serveur SQL. A noter que si vous faites des modifications vous-m�me dans la base de donn�es, elles ne seront visibles qu\'apr�s avoir r�g�n�r� le cache';
$LANG['confirm_unlock_admin'] = 'Un email va vous �tre envoy� avec le code de d�verrouillage';
$LANG['unlock_admin_confirm'] = 'Le code de d�verrouillage a �t� renvoy� avec succ�s';
$LANG['unlock_admin'] = 'Code de d�verrouillage';
$LANG['unlock_admin_explain'] = 'Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.';
$LANG['send_unlock_admin'] = 'Renvoyer le code de d�verrouillage';
$LANG['unlock_title_mail'] = 'Mail � conserver';
$LANG['unlock_mail'] = 'Code � conserver (Il ne vous sera plus d�livr�) : %s

Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.
Il vous sera demand� dans le formulaire de connexion directe � l\'administration (votreserveur/admin/admin_index.php)

' . $CONFIG['sign'];

//Maintain
$LANG['maintain_for'] = 'Mettre le site en maintenance';
$LANG['maintain_delay'] = 'Afficher la dur�e de la maintenance';
$LANG['maintain_display_admin'] = 'Afficher la dur�e de la maintenance � l\'administrateur';
$LANG['maintain_text'] = 'Texte � afficher lorsque la maintenance du site est en cours';
	
//Gestion des modules
$LANG['modules_management'] = 'Gestion des modules';
$LANG['add_modules'] = 'Ajouter un module';
$LANG['update_modules'] = 'Mettre � jour un module';
$LANG['update_module'] = 'Mettre � jour';
$LANG['upload_module'] = 'Uploader un module';
$LANG['del_module'] = 'Supprimer le module';
$LANG['del_module_data'] = 'Les donn�es du module vont �tre supprim�es, attention vous ne pourrez plus les r�cup�rer!';
$LANG['del_module_files'] = 'Supprimer les fichiers du module';
$LANG['author'] = 'Auteurs';
$LANG['compat'] = 'Compatibilit�';
$LANG['use_sql'] = 'Utilise SQL';
$LANG['use_cache'] = 'Utilise le cache';
$LANG['alternative_css'] = 'Utilise un css alternatif';
$LANG['modules_installed'] = 'Modules install�s';
$LANG['modules_available'] = 'Modules disponibles';
$LANG['no_modules_installed'] = 'Aucun module install�';
$LANG['no_modules_available'] = 'Aucun module disponible';
$LANG['install'] = 'Installer';
$LANG['uninstall'] = 'D�sinstaller';
$LANG['starteable_page'] = 'Page de d�marrage';
$LANG['table'] = 'Table';
$LANG['tables'] = 'Tables';
$LANG['new_version'] = 'Nouvelle version';
$LANG['installed_version'] = 'Version install�e';
$LANG['e_config_conflict'] = 'Conflit avec la configuration du module, installation impossible!';

//Rapport syst�me
$LANG['system_report'] = 'Rapport syst�me';
$LANG['server'] = 'Serveur';
$LANG['php_version'] = 'Version de PHP';
$LANG['dbms_version'] = 'Version du SGBD';
$LANG['dg_library'] = 'Librairie GD';
$LANG['url_rewriting'] = 'R��criture des URL';
$LANG['register_globals_option'] = 'Option <em>register globals</em>';
$LANG['phpboost_config'] = 'Configuration de PHPBoost';
$LANG['kernel_version'] = 'Version du noyau';
$LANG['output_gz'] = 'Compression des pages';
$LANG['directories_auth'] = 'Autorisation des r�pertoires';
$LANG['system_report_summerization'] = 'R�capitulatif';
$LANG['system_report_summerization_explain'] = 'Ceci est le r�capitulatif du rapport. Cela vous sera particuli�rement utile lorsque pour du support on vous demandera la configuration de votre syst�me';

//Gestion de l'upload
$LANG['explain_upload_img'] = 'L\'image upload�e doit �tre au format jpg, gif, png ou bmp';
$LANG['explain_archive_upload'] = 'L\'archive upload�e doit �tre au format zip ou gzip';

//Gestion des fichiers
$LANG['auth_files'] = 'Autorisation requise pour l\'activation de l\'interface de fichiers';
$LANG['size_limit'] = 'Taille maximale des uploads autoris�s aux membres';
$LANG['bandwidth_protect'] = 'Protection de la bande passante';
$LANG['bandwidth_protect_explain'] = 'Interdiction d\'acc�s aux fichiers du r�pertoire upload depuis un autre serveur';
$LANG['auth_extensions'] = 'Extensions autoris�es';
$LANG['extend_extensions'] = 'Extensions autoris�es suppl�mentaires';
$LANG['extend_extensions_explain'] = 'S�parez les extensions avec des virgules';
$LANG['files_image'] = 'Images';
$LANG['files_archives'] = 'Archives';
$LANG['files_text'] = 'Textes';
$LANG['files_media'] = 'Media';
$LANG['files_prog'] = 'Programmation';
$LANG['files_misc'] = 'Divers';

//Gestion des menus
$LANG['confirm_del_menu'] = 'Supprimer ce menu?';
$LANG['confirm_delete_element'] = 'Voulez vous vraiment supprimer cet �l�ment?';
$LANG['menus_management'] = 'Gestion des menus';
$LANG['menus_content_add'] = 'Ajout menu de contenu';
$LANG['menus_links_add'] = 'Ajout menu de liens';
$LANG['menus_feed_add'] = 'Ajout de flux';
$LANG['menus_edit'] = 'Modifier le menu';
$LANG['vertical_menu'] = 'Menu vertical';
$LANG['horizontal_menu'] = 'Menu horizontal';
$LANG['tree_menu'] = 'Menu arborescent';
$LANG['vertical_scrolling_menu'] = 'Menu vertical d�roulant';
$LANG['horizontal_scrolling_menu'] = 'Menu horizontal d�roulant';
$LANG['available_menus'] = 'Menus disponibles';
$LANG['no_available_menus'] = 'Aucun menu disponible';
$LANG['menu_header'] = 'T�te de page';
$LANG['menu_subheader'] = 'Sous ent�te';
$LANG['menu_left'] = 'Menu gauche';
$LANG['menu_right'] = 'Menu droit';
$LANG['menu_top_central'] = 'Menu central haut';
$LANG['menu_bottom_central'] = 'Menu central bas';
$LANG['menu_top_footer'] = 'Sur pied de page';
$LANG['menu_footer'] = 'Pied de page';
$LANG['location'] = 'Emplacement';
$LANG['use_tpl'] = 'Utiliser la structure des templates';
$LANG['add_sub_element'] = 'Ajouter un �l�ment';
$LANG['add_sub_menu'] = 'Ajouter un sous-menu';
$LANG['display_title'] = 'Afficher le titre';
$LANG['choose_feed_in_list'] = 'Veuillez choisir un flux dans la liste';
$LANG['feed'] = 'flux';
$LANG['availables_feeds'] = 'Flux disponibles';



//Gestion du contenu
$LANG['content_config'] = 'Contenu';
$LANG['content_config_extend'] = 'Configuration du contenu';
$LANG['default_formatting_language'] = 'Langage de formatage du contenu par d�faut du site
<span style="display:block;">Chaque utilisateur pourra choisir</span>';
$LANG['content_language_config'] = 'Langage de formatage';
$LANG['content_html_language'] = 'Langage HTML';
$LANG['content_auth_use_html'] = 'Niveau d\'autorisation pour ins�rer du langage HTML
<span style="display:block">Attention : le code HTML peut contenir du code Javascript qui peut constituer une source de faille de s�curit� si quelqu\'un y ins�re un code malveillant. Veillez donc � n\'autoriser seulement les personnes de confiance � ins�rer du HTML.</span>';

//Smiley
$LANG['upload_smiley'] = 'Uploader un smiley';
$LANG['smiley'] = 'Smiley';
$LANG['add_smiley'] = 'Ajouter smiley';
$LANG['smiley_code'] = 'Code du smiley (ex : :D)';
$LANG['smiley_available'] = 'Smileys disponibles';
$LANG['edit_smiley'] = 'Edition des smileys';
$LANG['smiley_management'] = 'Gestion des smileys';
$LANG['e_smiley_already_exist'] = 'Le smiley existe d�j�';
		
//Th�mes
$LANG['upload_theme'] = 'Uploader un th�me';
$LANG['theme_on_serv'] = 'Th�mes disponibles sur le serveur';
$LANG['no_theme_on_serv'] = 'Aucun th�me <strong>compatible</strong> disponible sur le serveur';
$LANG['theme_management'] = 'Gestion des th�mes';
$LANG['theme_add'] = 'Ajouter un th�me';
$LANG['theme'] = 'Th�me';
$LANG['e_theme_already_exist'] = 'Le th�me existe d�j�';
$LANG['xhtml_version'] = 'Version Html';
$LANG['css_version'] = 'Version Css';
$LANG['main_colors'] = 'Couleurs dominantes';
$LANG['width'] = 'Largeur';
$LANG['exensible'] = 'Extensible';
$LANG['del_theme'] = 'Suppression du th�me';
$LANG['del_theme_files'] = 'Supprimer tous les fichiers du th�me';
$LANG['explain_default_theme'] = 'Le th�me par d�faut ne peut pas �tre d�sinstall�, d�sactiv�, ou r�serv�';
$LANG['activ_left_column'] = 'Activer la colonne de gauche';
$LANG['activ_right_column'] = 'Activer la colonne de droite';
$LANG['manage_theme_columns'] = 'G�rer les colonnes du th�me';
		
//Langues
$LANG['upload_lang'] = 'Uploader une langue';
$LANG['lang_on_serv'] = 'Langues disponibles sur le serveur';
$LANG['no_lang_on_serv'] = 'Aucune langue disponible sur le serveur';
$LANG['lang_management'] = 'Gestion des langues';
$LANG['lang_add'] = 'Ajouter une langue';
$LANG['lang'] = 'Langue';
$LANG['e_lang_already_exist'] = 'La langue existe d�j�';
$LANG['del_lang'] = 'Suppression de la langue';
$LANG['del_lang_files'] = 'Supprimer les fichiers de la langue';
$LANG['explain_default_lang'] = 'La langue par d�faut ne peut pas �tre d�sinstall�e, d�sactiv�e ou r�serv�e';
	
//Comments
$LANG['com_management'] = 'Gestion des commentaires';
$LANG['com_config'] = 'Configuration des commentaires';
$LANG['com_max'] = 'Nombre de commentaires par page';
$LANG['rank_com_post'] = 'Rang pour pouvoir poster des commentaires';
$LANG['display_topic_com'] = 'Voir la discussion';
$LANG['display_recent_com'] = 'Voir les derniers commentaires';

//Gestion membre
$LANG['job'] = 'Emploi';
$LANG['hobbies'] = 'Loisirs';
$LANG['members_management'] = 'Gestion des Membres';
$LANG['members_add'] = 'Ajouter un membre';
$LANG['members_config'] = 'Configuration des membres';
$LANG['members_punishment'] = 'Gestion des sanctions';
$LANG['members_msg'] = 'Message � tous les membres';
$LANG['search_member'] = 'Rechercher un membre';
$LANG['joker'] = 'Utilisez * pour joker';
$LANG['no_result'] = 'Aucun r�sultat';
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'heure';
$LANG['hours'] = 'heures';
$LANG['day'] = 'jour';
$LANG['days'] = 'jours';
$LANG['week'] = 'semaine';
$LANG['month'] = 'mois';
$LANG['life'] = 'A vie';
$LANG['confirm_password'] = 'Confirmer le mot de passe';
$LANG['confirm_password_explain'] = 'Remplir seulement en cas de modification';
$LANG['hide_mail'] = 'Cacher l\'email';
$LANG['hide_mail_explain'] = 'Aux autres utilisateurs';
$LANG['website_explain'] = 'Valide sinon non pris en compte';
$LANG['member_sign'] = 'Signature';
$LANG['member_sign_explain'] = 'Appara�t sous chacun de vos messages';
$LANG['avatar_management'] = 'Gestion avatar';
$LANG['activ_up_avatar'] = 'Autoriser l\'upload d\'avatar sur le serveur';
$LANG['current_avatar'] = 'Avatar actuel';
$LANG['upload_avatar'] = 'Uploader avatar';
$LANG['upload_avatar_where'] = 'Avatar directement h�berg� sur le serveur';
$LANG['avatar_link'] = 'Lien avatar';
$LANG['avatar_link_where'] = 'Adresse directe de l\'avatar';
$LANG['avatar_del'] = 'Supprimer l\'avatar courant';
$LANG['no_avatar'] = 'Aucun avatar';
$LANG['weight_max'] = 'Poids maximum';
$LANG['height_max'] = 'Hauteur maximale';
$LANG['width_max'] = 'Largeur maximale';
$LANG['sex'] = 'Sexe';
$LANG['male'] = 'Homme';
$LANG['female'] = 'Femme';
$LANG['verif_code'] = 'Code de v�rification visuel';
$LANG['verif_code_explain'] = 'Bloque les robots';
$LANG['delay_activ_max'] = 'Dur�e apr�s laquelle les membres non activ�s sont effac�s';
$LANG['delay_activ_max_explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$LANG['activ_mbr'] = 'Mode d\'activation du compte membre';
$LANG['no_activ_mbr'] = 'Automatique';
$LANG['allow_theme_mbr'] = 'Permission aux membres de choisir leur th�me';
$LANG['width_max_avatar'] = 'Largeur maximale de l\'avatar';
$LANG['width_max_avatar_explain'] = 'Par d�faut 120';
$LANG['height_max_avatar'] = 'Hauteur maximale de l\'avatar';
$LANG['height_max_avatar_explain'] = 'Par d�faut 120';
$LANG['weight_max_avatar'] = 'Poids maximal de l\'avatar en ko';
$LANG['weight_max_avatar_explain'] = 'Par d�faut 20';
$LANG['avatar_management'] = 'Gestion des avatars';
$LANG['activ_defaut_avatar'] = 'Activer l\'avatar par d�faut';
$LANG['activ_defaut_avatar_explain'] = 'Met un avatar aux membres qui n\'en ont pas';
$LANG['url_defaut_avatar'] = 'Adresse de l\'avatar par d�faut';
$LANG['url_defaut_avatar_explain'] = 'Mettre dans le dossier images de votre th�me ';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais ne peut plus poster sur le site entier (commentaires, etc...)';
$LANG['weeks'] = 'semaines';
$LANG['life'] = 'A vie';
$LANG['readonly_user'] = 'Membre en lecture seule';
$LANG['activ_register'] = 'Activer l\'inscription des membres';

//R�glement
$LANG['explain_terms'] = 'Entrez ci-dessous le r�glement � afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun r�glement.';

//Gestion des groupes
$LANG['groups_management'] = 'Gestion des groupes';
$LANG['groups_add'] = 'Ajouter un groupe';
$LANG['auth_flood'] = 'Autorisation de flooder';
$LANG['pm_group_limit'] = 'Limite de messages priv�s';
$LANG['pm_group_limit_explain'] = 'Mettre -1 pour illimit�';
$LANG['data_group_limit'] = 'Limite de donn�es uploadables';
$LANG['data_group_limit_explain'] = 'Mettre -1 pour illimit�';
$LANG['color_group'] = 'Couleur';
$LANG['color_group_explain'] = 'Couleur associ�e au groupe en hexad�cimal (ex: #FF6600)';
$LANG['img_assoc_group'] = 'Image associ�e au groupe';
$LANG['img_assoc_group_explain'] = 'Mettre dans le dossier images/group/';
$LANG['add_mbr_group'] = 'Ajouter un membre au groupe';
$LANG['mbrs_group'] = 'Membres du groupe';
$LANG['auths'] = 'Autorisations';
$LANG['auth_access'] = 'Autorisation d\'acc�s';
$LANG['auth_read'] = 'Droits de lecture';
$LANG['auth_write'] = 'Droits d\'�criture';
$LANG['auth_edit'] = 'Droits de mod�ration';
$LANG['upload_group'] = 'Uploader une image de groupe';

//Robots
$LANG['robot'] = 'Robot';
$LANG['robots'] = 'Robots';
$LANG['erase_rapport'] = 'Effacer le rapport';
$LANG['number_r_visit'] = 'Nombre de visite(s)';

//Erreurs
$LANG['all_errors'] = 'Afficher toutes les erreurs';
$LANG['error_management'] = 'Gestionnaire d\'erreurs';

//Divers
$LANG['select_type_bbcode'] = 'BBCode';
$LANG['select_type_html'] = 'HTML';

//Statistiques
$LANG['stats'] = 'Statistiques';
$LANG['more_stats'] = 'Plus de stats';
$LANG['site'] = 'Site';
$LANG['browser_s'] = 'Navigateurs';
$LANG['fai'] = 'Fournisseurs d\'acc�s Internet';
$LANG['all_fai'] = 'Voir la liste compl�te des fournisseurs d\'acc�s Internet';
$LANG['10_fai'] = 'Voir les 10 principaux fournisseurs d\'acc�s Internet';
$LANG['os'] = 'Syst�mes d\'exploitation';
$LANG['number'] = 'Nombre ';
$LANG['start'] = 'Cr�ation du site';
$LANG['stat_lang'] = 'Pays des visiteurs';
$LANG['all_langs'] = 'Voir la liste compl�te des pays des visiteurs';
$LANG['10_langs'] = 'Voir les 10 principaux pays des visiteurs';
$LANG['visits_year'] = 'Voir les statistiques de l\'ann�e';
$LANG['unknown'] = 'Inconnu';
$LANG['last_member'] = 'Dernier membre';
$LANG['top_10_posters'] = 'Top 10 : posteurs';
$LANG['version'] = 'Version';
$LANG['colors'] = 'Couleurs';
$LANG['calendar'] = 'Calendrier';
$LANG['events'] = 'Ev�nements';
$LANG['january'] = 'Janvier';
$LANG['february'] = 'F�vrier';
$LANG['march'] = 'Mars';
$LANG['april'] = 'Avril';
$LANG['may'] = 'Mai';
$LANG['june'] = 'Juin';
$LANG['july'] = 'Juillet';
$LANG['august'] = 'Ao�t';
$LANG['september'] = 'Septembre';
$LANG['october'] = 'Octobre';
$LANG['november'] = 'Novembre';
$LANG['december'] = 'D�cembre';
$LANG['monday'] = 'Lun';
$LANG['tuesday'] = 'Mar';
$LANG['wenesday'] = 'Mer';
$LANG['thursday'] = 'Jeu';
$LANG['friday'] = 'Ven';
$LANG['saturday'] = 'Sam';
$LANG['sunday']	= 'Dim';

// Updates
$LANG['website_updates'] = 'Mises � jour';
$LANG['kernel'] = 'Noyau';
$LANG['themes'] = 'Th�mes';
$LANG['update_available'] = 'Le %1$s %2$s est disponible dans sa version %3$s';
$LANG['kernel_update_available'] = 'PHPBoost est disponible dans sa nouvelle version %s';
$LANG['app_update__download'] = 'T�l�chargement';
$LANG['app_update__download_pack'] = 'Pack complet';
$LANG['app_update__update_pack'] = 'Pack de mise � jour';
$LANG['author'] = 'Auteur';
$LANG['authors'] = 'Auteurs';
$LANG['new_features'] = 'Nouvelles Fonctionnalit�s';
$LANG['improvments'] = 'Am�liorations';
$LANG['fixed_bugs'] = 'Corrections de bugs';
$LANG['security_improvments'] = 'Am�liorations de s�curit�';
$LANG['unexisting_update'] = 'La mise � jour recherch�e n\'existe pas';
$LANG['updates_are_available'] = 'Des mises � jours sont disponibles.<br />Veuillez les effectuer au plus vite.';
$LANG['availables_updates'] = 'Mises � jour disponibles';
$LANG['details'] = 'D�tails';
$LANG['more_details'] = 'Plus de d�tails';
$LANG['download_the_complete_pack'] = 'T�l�chargez le pack complet';
$LANG['download_the_update_pack'] = 'T�l�chargez le pack de mise � jour';
$LANG['no_available_update'] = 'Aucune mise � jour n\'est disponible pour l\'instant.';
$LANG['incompatible_php_version'] = 'Impossible de v�rifier la pr�sence de mise � jour.
Veuillez utiliser la version %s ou ult�rieure de PHP.<br />Si vous ne pouvez utiliser PHP5,
veuillez v�rifier la pr�sence de ces mises � jour sur notre <a href="http://www.phpboost.com">site officiel</a>.';
$LANG['check_for_updates_now'] = 'V�rifier la pr�sence de mises � jour';
$LANG['admin_module_update_success'] = 'Modifications t�l�charg�es et base de donn�es mise � jour';
?>
