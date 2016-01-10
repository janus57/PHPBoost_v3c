<?php
/*##################################################
 *                             admin_members.php
 *                            -------------------
 *   begin                : August 01, 2005
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$delete = !empty($_GET['delete']) ? true : false ;
$add = !empty($_GET['add']) ? true : false;
$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

//Si c'est confirm� on execute
if (!empty($_POST['valid']) && !empty($id_post))
{
	if (!empty($_POST['delete'])) //Suppression du membre.
	{
		$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);	
		
		//Initialisation  de la class de gestion des fichiers.
		import('members/uploads');
		$Uploads = new Uploads; //Suppression de tout les fichiers et dossiers du membre.
		$Uploads->Empty_folder_member($id_post);
			
		//On r�g�n�re le cache
		$Cache->Generate_file('stats');
			
		redirect(HOST . SCRIPT);
	}

	$login = !empty($_POST['name']) ?  strprotect(substr($_POST['name'], 0, 25)) : '';
	$user_mail = strtolower($_POST['mail']);
	if (check_mail($user_mail))
	{	
		//V�rirication de l'unicit� du membre et du mail
		$check_user = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "' AND user_id <> '" . $id_post . "'", __LINE__, __FILE__);
		$check_mail = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_id <> '" . $id_post . "' AND user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
		if ($check_user >= 1) 
			redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pseudo_auth') . '#errorh');
		elseif ($check_mail >= 1) 
			redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=auth_mail') . '#errorh');
		else
		{
			//V�rification des password.
			$password = retrieve(POST, 'pass', '', TSTRING_UNCHANGE);
			$password_hash = !empty($password) ? strhash($password) : '';
			$password_bis = retrieve(POST, 'confirm_pass', '', TSTRING_UNCHANGE);
			$password_bis_hash = !empty($password_bis) ? strhash($password_bis) : '';
            
			if (!empty($password_hash) && !empty($password_bis_hash))
			{
				if ($password_hash === $password_bis_hash)
				{
					if (strlen($password) >= 6)
                    {
						$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = '" . $password_hash . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
                    }
					else //Longueur minimale du password
						redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pass_mini') . '#errorh');
				}
				else
					redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pass_same') . '#errorh');
			}
			
			$MEMBER_LEVEL = retrieve(POST, 'level', -1);  
			$user_aprob = retrieve(POST, 'user_aprob', 0);  
			
			//Informations.
			$user_show_mail = !empty($_POST['user_show_mail']) ? 0 : 1;
			$user_lang = retrieve(POST, 'user_lang', '');
			$user_theme = retrieve(POST, 'user_theme', '');
			$user_editor = retrieve(POST, 'user_editor', '');
			$user_timezone = retrieve(POST, 'user_timezone', 0);
			
			$user_local = retrieve(POST, 'user_local', '');
			$user_occupation = retrieve(POST, 'user_occupation', '');
			$user_hobbies = retrieve(POST, 'user_hobbies', '');
			$user_desc = retrieve(POST, 'user_desc', '', TSTRING_PARSE);
			$user_sex = retrieve(POST, 'user_sex', 0);
			$user_sign = retrieve(POST, 'user_sign', '', TSTRING_PARSE);			
			$user_msn = retrieve(POST, 'user_msn', '');
			$user_yahoo= retrieve(POST, 'user_yahoo', '');
			
			$user_warning = retrieve(POST, 'user_warning', 0);
			$user_readonly = retrieve(POST, 'user_readonly', 0);
			$user_readonly = ($user_readonly > 0) ? (time() + $user_readonly) : 0; //Lecture seule!
			$user_ban = retrieve(POST, 'user_ban', 0);
			$user_ban = ($user_ban > 0) ? (time() + $user_ban) : 0; //Bannissement!
			
			$user_web = retrieve(POST, 'user_web', '');
			if (!empty($user_web) && substr($user_web, 0, 7) != 'http://' && substr($user_web, 0, 6) != 'ftp://' && substr($user_web, 0, 8) != 'https://')
				$user_web = 'http://' . $user_web;
			
			//Gestion des groupes.				
			$array_user_groups = isset($_POST['user_groups']) ? $_POST['user_groups'] : array();
			$Group->edit_member($id_post, $array_user_groups); //Change les groupes du membre, calcul la diff�rence entre les groupes pr�c�dent et nouveaux.
			
			//Gestion de la date de naissance.
			$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
			
			//Gestion de la suppression de l'avatar.
			if (!empty($_POST['delete_avatar']))
			{
				$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
				if (!empty($user_avatar_path))
				{
					$user_avatar_path = str_replace('../images/avatars/', '', $user_avatar_path);
					$user_avatar_path = str_replace('/', '', $user_avatar_path);
					@unlink('../images/avatars/' . $user_avatar_path);
				}
				
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_avatar = '' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
			}

			//Gestion upload d'avatar.					
			$user_avatar = '';
			$dir = '../images/avatars/';
			
			import('io/upload');
			$Upload = new Upload($dir);
			
			if (is_writable($dir))
			{
				if ($_FILES['avatars']['size'] > 0)
				{
					$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', UNIQ_NAME, $CONFIG_USER['weight_max']*1024);
					if (!empty($Upload->error)) //Erreur, on arr�te ici
						redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $Upload->error) . '#errorh');
					else
					{
						$path = $dir . $Upload->filename['avatars'];
						$error = $Upload->validate_img($path, $CONFIG_USER['width_max'], $CONFIG_USER['height_max'], DELETE_ON_ERROR);
						if (!empty($error)) //Erreur, on arr�te ici
							redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $error) . '#errorh');
						else
						{
							//Suppression de l'ancien avatar (sur le serveur) si il existe!
							$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
							if (!empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match))
							{
								if (is_file($user_avatar_path) && isset($match[1]))
									@unlink('../images/avatars/' . $match[1]);
							}						
							$user_avatar = $path; //Avatar upload� et valid�.
						}
					}
				}
			}
			
			if (!empty($_POST['avatar']))
			{
				$path = strprotect($_POST['avatar']);
				$error = $Upload->validate_img($path, $CONFIG_USER['width_max'], $CONFIG_USER['height_max'], DELETE_ON_ERROR);
				if (!empty($error)) //Erreur, on arr�te ici
					redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $error) . '#errorh');
				else
					$user_avatar = $path; //Avatar post� et valid�.
			}

			$user_avatar = !empty($user_avatar) ? "user_avatar = '" . $user_avatar . "', " : '';
			if (!empty($login) && !empty($user_mail))
			{	
				//Suppression des images des stats concernant les membres, si l'info � �t� modifi�e.
				$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, "user_theme", "user_sex", "WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if ($info_mbr['user_sex'] != $user_sex)
					@unlink('../cache/sex.png');
				if ($info_mbr['user_theme'] != $user_theme)
					@unlink('../cache/theme.png');
				
                //Si le membre n'�tait pas approuv� et qu'on l'approuve et qu'il existe une alerte, on la r�gle automatiquement
                $member_infos = $Sql->query_array(DB_TABLE_MEMBER, "user_aprob", "level", "WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if ($member_infos['user_aprob'] != $user_aprob && $member_infos['user_aprob'] == 0)
				{
					//On recherche l'alerte
					import('events/administrator_alert_service');
					
					//Recherche de l'alerte correspondante
					$matching_alerts = AdministratorAlertService::find_by_criteria($id_post, 'member_account_to_approbate');
					
					//L'alerte a �t� trouv�e
					if (count($matching_alerts) == 1)
					{
						$alert = $matching_alerts[0];
						$alert->set_status(ADMIN_ALERT_STATUS_PROCESSED);
						AdministratorAlertService::save_alert($alert);
					}

					//R�g�n�ration du cache des stats.
					$Cache->Generate_file('stats');
				}
				
                $Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET login = '" . $login . "', level = '" . $MEMBER_LEVEL . "', user_lang = '" . $user_lang . "', user_theme = '" . $user_theme . "', user_mail = '" . $user_mail . "', user_show_mail = " . $user_show_mail . ", user_editor = '" . $user_editor . "', user_timezone = '" . $user_timezone . "', user_local = '" . $user_local . "', " . $user_avatar . "user_msn = '" . $user_msn . "', user_yahoo = '" . $user_yahoo . "', user_web = '" . $user_web . "', user_occupation = '" . $user_occupation . "', user_hobbies = '" . $user_hobbies . "', user_desc = '" . $user_desc . "', user_sex = '" . $user_sex . "', user_born = '" . $user_born . "', user_sign = '" . $user_sign . "', user_warning = '" . $user_warning . "', user_readonly = '" . $user_readonly . "', user_ban = '" . $user_ban . "', user_aprob = '" . $user_aprob . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
                //Mise � jour de la session si l'utilisateur change de niveau pour lui donner imm�diatement les droits
                if ($member_infos['level'] != $MEMBER_LEVEL)
					$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET level = '" . $MEMBER_LEVEL . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
				if ($user_ban > 0)	//Suppression de la session si le membre se fait bannir.
				{	
					$Sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					import('io/mail');
					$Mail = new Mail();
					$Mail->send_from_properties($user_mail, addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail_exp']);
				}
				
				//Champs suppl�mentaires.
				$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
				if ($extend_field_exist > 0)
				{
					$req_update = '';
					$req_field = '';
					$req_insert = '';
					$result = $Sql->query_while("SELECT field_name, field, possible_values
					FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "
					WHERE display = 1", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$field = isset($_POST[$row['field_name']]) ? $_POST[$row['field_name']] : '';
						if ($row['field'] == 2)
							$field = strparse($field);
						elseif ($row['field'] == 4)
						{
							$array_field = is_array($field) ? $field : array();
							$field = '';
							foreach ($array_field as $value)
								$field .= strprotect($value) . '|';
						}
						elseif ($row['field'] == 6)
						{
							$field = '';
							$i = 0;
							$array_possible_values = explode('|', $row['possible_values']);
							foreach ($array_possible_values as $value)
							{
								$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? strprotect($value) . '|' : '';
								$i++;
							}
						}
						else
							$field = strprotect($field);
							
						if (!empty($field))
						{
							$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $row['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';
						}
					}
					$Sql->query_close($result);	
					
					$check_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					if ($check_member)
					{	
						if (!empty($req_update))
							$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__); 
					}
					else
					{	
						if (!empty($req_insert))
							$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $id_post . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
					}
				}	
				
				redirect(HOST . SCRIPT);	
			}
			else
				redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=incomplete') . '#errorh');
		}
	}
	else
		redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id_post . '&error=incomplete') . '#errorh');
}
elseif ($add && !empty($_POST['add'])) //Ajout du membre.
{
	$login = !empty($_POST['login2']) ? strprotect(substr($_POST['login2'], 0, 25)) : '';
	$password = retrieve(POST, 'password2', '', TSTRING_UNCHANGE);
	$password_bis = retrieve(POST, 'password2_bis', '', TSTRING_UNCHANGE);
	$password_hash = !empty($password) ? strhash($password) : '';
	$level = retrieve(POST, 'level2', 0);
	$mail = strtolower(retrieve(POST, 'mail2', ''));
	
	if (check_mail($mail))
	{	
		//V�rirication de l'unicit� du membre et du mail
		$check_user = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
		$check_mail = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $mail . "'", __LINE__, __FILE__);
		if ($check_user >= 1) 
			redirect(HOST . DIR . '/admin/admin_members' . url('.php?error=pseudo_auth&add=1') . '#errorh');
		elseif ($check_mail >= 1) 
			redirect(HOST . DIR . '/admin/admin_members' . url('.php?error=auth_mail&add=1') . '#errorh');
		else
		{
			if (strlen($password) >= 6 && strlen($password_bis) >= 6)
			{
				if (!empty($login))
				{	
					//On insere le nouveau membre.
					$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_lang,user_theme,user_mail,user_timezone,user_show_mail,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,user_readonly,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob) 
					VALUES('" . $login . "', '" . $password_hash . "', '" . $level . "', '', '" . $CONFIG['lang'] . "', '', '" . $mail . "', '" . $CONFIG['timezone'] . "', '1', '" . time() . "', '', 0, '', '', '', '', '', '', '', 0, '0000-00-00', '', 0, 0, 0, 0, 0, '', '', 0, 1)", __LINE__, __FILE__);
					
					//On r�g�n�re le cache
					$Cache->Generate_file('stats');
						
					redirect(HOST . SCRIPT); 	
				}
				else
					redirect(HOST . DIR . '/member/member' . url('.php?error=incomplete&add=1') . '#errorh');
			}
			else //Longueur minimale du password
				redirect(HOST . DIR . '/admin/admin_members' . url('.php?id=' .  $id . '&error=pass_mini&add=1') . '#errorh');
		}
	}
	else
		redirect(HOST . DIR . '/admin/admin_members' . url('.php?error=invalid_mail&add=1') . '#errorh');
}
elseif (!empty($id) && $delete) //Suppression du membre.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id . "'", __LINE__, __FILE__);
	
	//Initialisation  de la class de gestion des fichiers.
	import('members/uploads');
	$Uploads = new Uploads; //Suppression de tout les fichiers et dossiers du membre.
	$Uploads->Empty_folder_member($id);
	
	//On r�g�n�re le cache
	$Cache->Generate_file('stats');
		
	redirect(HOST . SCRIPT);
}
elseif ($add)
{
	$Template->set_filenames(array(
		'admin_members_management2'=> 'admin/admin_members_management2.tpl'
	));

	//Gestion des erreurs.
	switch ($get_error)
	{
		case 'pass_mini':
		$errstr = $LANG['e_pass_mini'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		case 'invalid_mail':
		$errstr = $LANG['e_mail_invalid'];
		break;		
		case 'pseudo_auth':
		$errstr = $LANG['e_pseudo_auth'];
		break;
		case 'auth_mail':
		$errstr = $LANG['e_mail_auth'];
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_NOTICE);  
		
	$Template->assign_vars(array(
		'C_USERS_ADD' => true,
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_PASSWORD_CONFIRM' => $LANG['confirm_password'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));
	
	$Template->pparse('admin_members_management2'); 	
}
elseif (!empty($id))	
{		
	$Template->set_filenames(array(
		'admin_members_management2'=> 'admin/admin_members_management2.tpl'
	));
	
	$mbr = $Sql->query_array(DB_TABLE_MEMBER, '*', "WHERE user_id = '" . $id . "'", __LINE__, __FILE__);

	$user_born = '';
	$array_user_born = explode('-', $mbr['user_born']);
	$date_birth = explode('/', $LANG['date_birth_parse']);
	for ($i = 0; $i < 3; $i++)
	{
		if ($date_birth[$i] == 'DD')
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_day = $array_user_born[2 - $i];
		}
		elseif ($date_birth[$i] == 'MM')
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_month = $array_user_born[2 - $i];
		}
		elseif ($date_birth[$i] == 'YYYY')	
		{
			$user_born .= $array_user_born[2 - $i];				
			$born_year = $array_user_born[2 - $i];
		}
		$user_born .= ($i != 2) ? '/' : '';	
	}
	
	//Gestion des erreurs.
	switch ($get_error)
	{
		case 'pass_mini':
		$errstr = $LANG['e_pass_mini'];
		break;
		case 'pass_same':
		$errstr = $LANG['e_pass_same'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		case 'invalid_mail':
		$errstr = $LANG['e_mail_invalid'];
		break;		
		case 'pseudo_auth':
		$errstr = $LANG['e_pseudo_auth'];
		break;
		case 'auth_mail':
		$errstr = $LANG['e_mail_auth'];
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_NOTICE);  

	if (isset($LANG[$get_l_error]))
		$Errorh->handler($errstr, E_USER_WARNING);   

	$user_sex = '';
	if (!empty($mbr['user_sex']))
		$user_sex = ($mbr['user_sex'] == 1) ? 'man.png' : 'woman.png';
	
	//Rang d'autorisation.
	$array_ranks = array(0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$ranks_options = '';
	for ($i = 0 ; $i <= 2 ; $i++)
	{
		$selected = ($mbr['level'] == $i) ? 'selected="selected"' : '' ;
		$ranks_options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
	}
	
	//Groupes.	
	$i = 0;
	$groups_options = '';
	$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "group", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{		
		$selected = '';		
		$search_group = array_search($row['id'], explode('|', $mbr['user_groups']));		
		if (is_numeric($search_group))
			$selected = 'selected="selected"';	
			
		$groups_options .= '<option value="' . $row['id'] . '" id="g' . $i . '" ' . $selected . '>' . $row['name'] . '</option>';
		$i++;
	}
	$Sql->query_close($result);

	//Temps de bannissement.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_ban'] - time());	
	$key_sanction = 0;
	if ($diff > 0)
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for ($i = 12; $i > 0; $i--)
		{					
			$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
			if (($diff - $array_time[$i]) > $avg)  
			{	
				$key_sanction = $i + 1;
				break;
			}
		}
	}	
	//Affichge des sanctions
	$ban_options = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;		
		$ban_options .= '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>';
	}

	//Dur�e de la sanction.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_readonly'] - time());	
	$key_sanction = 0;
	if ($diff > 0)
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for ($i = 12; $i > 0; $i--)
		{					
			$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
			if (($diff - $array_time[$i]) > $avg) 
			{	
				$key_sanction = $i + 1;
				break;
			}
		}
	}	
	//Affichge des sanctions
	$readonly_options = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ($key_sanction == $key) ? ' selected="selected"' : '' ;
		$readonly_options .= '<option value="' . $time . '"' . $selected . '>' . $array_sanction[$key] . '</option>';
	}
		
	//On cr�e le formulaire select
	$warning_options = '';
	$j = 0;
	for ($j = 0; $j <=10; $j++)
	{
		$selected = ((10 * $j) == $mbr['user_warning']) ? ' selected="selected"' : '';
		$warning_options .= '<option value="' . 10 * $j . '"' . $selected . '>' . 10 * $j . '%</option>';
	}
	
	//Gestion LANG par d�faut.
	$array_identifier = '';
	$lang_identifier = '../images/stats/other.png';
	foreach($LANGS_CONFIG as $lang => $array_info)
	{
		$info_lang = load_ini_file('../lang/', $lang);
		$selected = '';
		if ($CONFIG['lang'] == $lang)
		{
			$selected = ' selected="selected"';
			$lang_identifier = '../images/stats/countries/' . $info_lang['identifier'] . '.png';
		}
		$array_identifier .= 'array_identifier[\'' . $lang . '\'] = \'' . $info_lang['identifier'] . '\';' . "\n";
		$Template->assign_block_vars('select_lang', array(
			'NAME' => !empty($info_lang['name']) ? $info_lang['name'] : $lang,
			'IDNAME' => $lang,
			'SELECTED' => $selected
		));
	}
	
	//Gestion th�me par d�faut.
	foreach($THEME_CONFIG as $theme => $array_info)
	{
		if ($theme != 'default')
		{
			$selected = ($CONFIG['theme'] == $theme) ? ' selected="selected"' : '';
			$info_theme = load_ini_file('../templates/' . $theme . '/config/', get_ulang());
			$Template->assign_block_vars('select_theme', array(
				'NAME' => $info_theme['name'],
				'IDNAME' => $theme,
				'SELECTED' => $selected
			));
		}
	}
	
	//Editeur texte par d�faut.
	$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
	$editor_options = '';
	foreach ($editors as $code => $name)
	{
		$selected = ($code == $mbr['user_editor']) ? 'selected="selected"' : '';
		$editor_options .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
	}
	
	//Gestion fuseau horaire par d�faut.
	$timezone_options = '';
	for ($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $mbr['user_timezone']) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$timezone_options .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
		
	//Sex par d�faut
	$i = 0;
	$array_sex = array('--', $LANG['male'], $LANG['female'], );
	$sex_options = '';
	foreach ($array_sex as $value_sex)
	{		
		$selected = ($i == $mbr['user_sex']) ? 'selected="selected"' : '';
		$sex_options .= '<option value="' . $i . '" ' . $selected . '>' . $value_sex . '</option>';
		$i++;
	}
	
	//On assigne les variables pour le POST en pr�cisant l'user_id.
	$Template->assign_vars(array(
		'C_USERS_MANAGEMENT' => true,
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => $lang_identifier,
		'IDMBR' => $mbr['user_id'],
		'NAME' => $mbr['login'],
		'MAIL' => $mbr['user_mail'],
		'USER_THEME' => $mbr['user_theme'],
		'SELECT_UNAPROB' => ($mbr['user_aprob'] == 0) ? 'selected="selected"' : '',
		'SELECT_APROB' => ($mbr['user_aprob'] == 1) ? 'selected="selected"' : '',
		'RANKS_OPTIONS' => $ranks_options,
		'GROUPS_OPTIONS' => $groups_options,
		'NBR_GROUP' => $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "group", __LINE__, __FILE__),
		'EDITOR_OPTIONS' => $editor_options,
		'TIMEZONE_OPTIONS' => $timezone_options,
		'BAN_OPTIONS' => $ban_options,
		'READONLY_OPTIONS' => $readonly_options,
		'WARNING_OPTIONS' => $warning_options,
		'SEX_OPTIONS' => $sex_options,
		'MSN' => $mbr['user_msn'],
		'YAHOO' => $mbr['user_yahoo'],
		'LOCAL' => $mbr['user_local'],
		'WEB' => $mbr['user_web'],
		'IMG_SEX' => !empty($user_sex) ? $user_sex : 0,
		'BORN' => $user_born,
		'BORN_DAY' => $born_day,
		'BORN_MONTH' => $born_month,
		'BORN_YEAR' => $born_year,
		'OCCUPATION' => $mbr['user_occupation'],
		'HOBBIES' => $mbr['user_hobbies'],
		'SIGN' => unparse($mbr['user_sign'], NO_EDITOR_UNPARSE),
		'BIOGRAPHY' => unparse($mbr['user_desc'], NO_EDITOR_UNPARSE),
		'USER_AVATAR' => !empty($mbr['user_avatar']) ? '<img src="' . $mbr['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>',
		'AVATAR_LINK' => $mbr['user_avatar'],
		'SHOW_MAIL_CHECKED' => ($mbr['user_show_mail'] == 0) ? 'checked="checked"' : '',
		'THEME' => get_utheme(),
		'WEIGHT_MAX' => $CONFIG_USER['weight_max'],
		'HEIGHT_MAX' => $CONFIG_USER['height_max'],
		'WIDTH_MAX' => $CONFIG_USER['width_max'],
		'USER_SIGN_EDITOR' => display_editor('user_sign'),
		'USER_DESC_EDITOR' => display_editor('user_desc'),
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONFIRM_DEL_USER' => $LANG['confirm_del_member'],
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_UPDATE' => $LANG['update'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_CONFIRM_PASSWORD' => $LANG['confirm_password'],
		'L_CONFIRM_PASSWORD_EXPLAIN' => $LANG['confirm_password_explain'],
		'L_MAIL' => $LANG['mail'],
		'L_HIDE_MAIL' => $LANG['hide_mail'],
		'L_HIDE_MAIL_EXPLAIN' => $LANG['hide_mail_explain'],
		'L_APROB' => $LANG['aprob'],
		'L_RANK' => $LANG['rank'],
		'L_NO' => $LANG['no'],
		'L_YES' => $LANG['yes'],
		'L_GROUP' => $LANG['group'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],		
		'L_SANCTION' => $LANG['sanction'],		
		'L_BAN' => $LANG['ban'],
		'L_READONLY' => $LANG['readonly_user'],
		'L_WARNING' => $LANG['warning_user'],
		'L_LANG_CHOOSE'  => $LANG['choose_lang'],
		'L_OPTIONS' => $LANG['options'],
		'L_THEME_CHOOSE' => $LANG['choose_theme'],
		'L_EDITOR_CHOOSE' => $LANG['choose_editor'],
		'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
		'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
		'L_INFO' => $LANG['info'],
		'L_WEBSITE' => $LANG['website'],
		'L_WEBSITE_EXPLAIN' => $LANG['website_explain'],
		'L_LOCALISATION' => $LANG['localisation'],
		'L_JOB' => $LANG['job'],
		'L_HOBBIES' => $LANG['hobbies'],
		'L_USER_SIGN' => $LANG['member_sign'],
		'L_USER_SIGN_EXPLAIN' => $LANG['member_sign_explain'],
		'L_USER_BIOGRAPHY' => $LANG['biography'],
		'L_SEX' => $LANG['sex'],
		'L_DATE_BIRTH' => $LANG['date_of_birth'],
		'L_VALID' => $LANG['valid'],
		'L_CONTACT' => $LANG['contact'],
		'L_AVATAR_GESTION' => $LANG['avatar_management'],
		'L_CURRENT_AVATAR' => $LANG['current_avatar'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_UPLOAD_AVATAR' => $LANG['upload_avatar'],
		'L_UPLOAD_AVATAR_WHERE' => $LANG['upload_avatar_where'],
		'L_AVATAR_LINK' => $LANG['avatar_link'],
		'L_AVATAR_LINK_WHERE' => $LANG['avatar_link_where'],
		'L_AVATAR_DEL' => $LANG['avatar_del'],		
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_WEBSITE' => $LANG['website'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	//Champs suppl�mentaires.
	$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
	if ($extend_field_exist > 0)
	{
		$Template->assign_vars(array(			
			'C_MISCELLANEOUS' => true,
			'L_MISCELLANEOUS' => $LANG['miscellaneous']
		));

		$result = $Sql->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, ex.*
		FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{	
			// field: 0 => base de donn�es, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
			$field = '';
			$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
			switch ($row['field'])
			{
				case 1:
				$field = '<label><input type="text" size="30" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" class="text" value="' . $row[$row['field_name']] . '" /></label>';
				break;
				case 2:
				$field = '<label><textarea class="post" rows="4" cols="27" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">' . unparse($row[$row['field_name']]) . '</textarea></label>';
				break;
				case 3:
				$field = '<label><select name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">';
				$array_values = explode('|', $row['possible_values']);
				$i = 0;
				foreach ($array_values as $values)
				{
					$selected = ($values == $row[$row['field_name']]) ? 'selected="selected"' : '';
					$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
					$i++;
				}
				$field .= '</select></label>';
				break;
				case 4:
				$field = '<label><select name="' . $row['field_name'] . '[]" multiple="multiple" id="' . $row['field_name'] . '">';
				$array_values = explode('|', $row['possible_values']);
				$array_default_values = explode('|', $row[$row['field_name']]);
				$i = 0;
				foreach ($array_values as $values)
				{
					$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
					$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
					$i++;
				}
				$field .= '</select></label>';
				break;
				case 5:
				$array_values = explode('|', $row['possible_values']);
				foreach ($array_values as $values)
				{
					$checked = ($values == $row[$row['field_name']]) ? 'checked="checked"' : '';
					$field .= '<label><input type="radio" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
				}
				break;
				case 6:
				$array_values = explode('|', $row['possible_values']);
				$array_default_values = explode('|', $row[$row['field_name']]);
				$i = 0;
				foreach ($array_values as $values)
				{
					$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
					$field .= '<label><input type="checkbox" name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
					$i++;
				}
				break;
			}				
			
			$Template->assign_block_vars('list', array(
				'NAME' => $row['required'] ? '* ' . ucfirst($row['name']) : ucfirst($row['name']),
				'ID' => $row['field_name'],
				'DESC' => !empty($row['contents']) ? ucfirst($row['contents']) : '',
				'FIELD' => $field
			));
		}
		$Sql->query_close($result);	
	}
	
	$Template->pparse('admin_members_management2');
}
else
{			
	$Template->set_filenames(array(
		'admin_members_management'=> 'admin/admin_members_management.tpl'
	));
	 
	$Template->assign_vars(array(
		'C_DISPLAY_SEARCH_RESULT' => false
	));
	
	$search = retrieve(POST, 'login_mbr', ''); 
	if (!empty($search)) //Moteur de recherche des members
	{
		$search = str_replace('*', '%', $search);
		$req = "SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '".$search."%'";
		$nbr_result = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%".$search."%'", __LINE__, __FILE__);

		if (!empty($nbr_result))
		{			
			$result = $Sql->query_while ($req, __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result)) //On execute la requ�te dans une boucle pour afficher tout les r�sultats.
			{ 
				$Template->assign_block_vars('search', array(
					'RESULT' => '<a href="../admin/admin_members.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />'
				));
				$Template->assign_vars(array(
					'C_DISPLAY_SEARCH_RESULT' => true
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			$Template->assign_vars(array(
				'C_DISPLAY_SEARCH_RESULT' => true
			));
			$Template->assign_block_vars('search', array(
				'RESULT' => $LANG['no_result']
			));
		}		
	}

	$nbr_membre = $Sql->count_table("member", __LINE__, __FILE__);
	//On cr�e une pagination si le nombre de membre est trop important.
	import('util/pagination'); 
	$Pagination = new Pagination();
	 
	$get_sort = retrieve(GET, 'sort', '');	
	switch ($get_sort)
	{
		case 'time' : 
		$sort = 'timestamp';
		break;		
		break;		
		case 'alph' : 
		$sort = 'login';
		break;	
		case 'rank' : 
		$sort = 'level';
		break;	
		case 'aprob' : 
		$sort = 'user_aprob';
		break;	
		default :
		$sort = 'timestamp';
	}
	
	$get_mode = retrieve(GET, 'mode', '');	
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '&amp;sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	$Template->assign_vars(array(
		'PAGINATION' => $Pagination->display('admin_members.php?p=%d' . $unget, $nbr_membre, 'p', 25, 3),	
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_PASS' => $LANG['require_pass'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_CONFIRM_DEL_USER' => $LANG['confirm_del_member'],
		'L_CONFIRM_DEL_ADMIN' => $LANG['confirm_del_admin'],
		'L_CONTENTS' => $LANG['content'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['members_punishment'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_APROB' => $LANG['aprob'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_SEARCH_USER' => $LANG['search_member'],
		'L_JOKER' => $LANG['joker'],
		'L_SEARCH' => $LANG['search'],
		'L_WEBSITE' => $LANG['website'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete']
	));
		
	$result = $Sql->query_while("SELECT login, user_id, user_mail, timestamp, user_web, level, user_aprob
	FROM " . DB_TABLE_MEMBER . " 
	ORDER BY " . $sort . " " . $mode . 
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		switch ($row['level']) 
		{	
			case 0:
				$rank = $LANG['member'];
			break;
			
			case 1: 
				$rank = $LANG['modo'];
			break;
	
			case 2:
				$rank = $LANG['admin'];
			break;	
			
			default: 0;
		} 
		
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '';
		
		$Template->assign_block_vars('member', array(
			'IDMBR' => $row['user_id'],
			'NAME' => $row['login'],
			'RANK' => $rank,
			'MAIL' => $row['user_mail'],
			'WEB' => $user_web,
			'LEVEL' => $row['level'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROB' => ($row['user_aprob'] == 0) ? $LANG['no'] : $LANG['yes']		
		));
	}
	$Sql->query_close($result);
	
	$Template->pparse('admin_members_management'); 
}
require_once('../admin/admin_footer.php');

?>