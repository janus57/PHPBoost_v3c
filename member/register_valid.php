<?php
/*##################################################
 *                             register_valid.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright            : (C) 2005 Viarre R�gis
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

require_once('../kernel/begin.php');
define('TITLE', $LANG['title_register']);
require_once('../kernel/header.php');

$Cache->load('member');
if (!$CONFIG_USER['activ_register'])
	redirect(get_start_page());

$user_mail = strtolower(retrieve(POST, 'mail', ''));
$valid = retrieve(POST, 'valid', false);
if ($valid && !empty($user_mail) && check_mail($user_mail))
{
	//Info de connexion
	$login = !empty($_POST['log']) ? strprotect(substr($_POST['log'], 0, 25)) : '';
	$password = retrieve(POST, 'pass', '', TSTRING_UNCHANGE);
	$password_hash = strhash($password);
	$password_bis = retrieve(POST, 'pass_bis', '', TSTRING_UNCHANGE);
	$password_bis_hash = strhash($password_bis);
		
	//Configuration
	$user_show_mail = retrieve(POST, 'user_show_mail', 0) ? 1 : 0;
	$user_lang = retrieve(POST, 'user_lang', '');
	$user_theme = retrieve(POST, 'user_theme', '');
	$user_editor = retrieve(POST, 'user_editor', '');
	$user_timezone = retrieve(POST, 'user_timezone', 0);
	
	//Informations.
	$user_avatar = retrieve(POST, 'user_avatar', '');
	$user_local = retrieve(POST, 'user_local', '');
	$user_occupation = retrieve(POST, 'user_occupation', '');
	$user_hobbies = retrieve(POST, 'user_hobbies', '');
	$user_desc = retrieve(POST, 'user_desc', '', TSTRING_PARSE);
	$user_sex = retrieve(POST, 'user_sex', 0);
	$user_sign = retrieve(POST, 'user_sign', '', TSTRING_PARSE);
	$user_msn = retrieve(POST, 'user_msn', '');
	$user_yahoo = retrieve(POST, 'user_yahoo', '');
	$user_web = retrieve(POST, 'user_web', '');
	
	if (!empty($user_web) && strpos($user_web, '://') === false)
	{
		$user_web = 'http://' . $user_web;
	}
	
	//Gestion de la date de naissance.
	$user_born = strtodate(retrieve(POST, 'user_born', '0'), $LANG['date_birth_parse']);
		
	//Code de v�rification si activ�
	import('util/captcha');
	$Captcha = new Captcha();
	$Captcha->set_difficulty($CONFIG_USER['verif_code_difficulty']);
	
	if (!($CONFIG_USER['verif_code'] == '1') || $Captcha->is_valid()) //Code de v�rification si activ�
	{
		if (strlen($login) >= 3 && strlen($password) >= 6 && strlen($password_bis) >= 6)
		{
			if (!empty($login) && !empty($user_mail) && $password_hash === $password_bis_hash)
			{
				####V�rification de la validit� de l'avatar####
				$user_avatar = '';
				//Gestion upload d'avatar.
				$dir = '../images/avatars/';
				import('io/upload');
				$Upload = new Upload($dir);
				
				if (is_writable($dir) && $CONFIG_USER['activ_up_avatar'] == 1)
				{
					if ($_FILES['avatars']['size'] > 0)
					{
						$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', UNIQ_NAME, $CONFIG_USER['weight_max']*1024);
						
						if (!empty($Upload->error)) //Erreur, on arr�te ici
							redirect(HOST . DIR . '/member/register' . url('.php?erroru=' . $Upload->error) . '#errorh');
						else
						{
							$path = $dir . $Upload->filename['avatars'];
							$error = $Upload->validate_img($path, $CONFIG_USER['width_max'], $CONFIG_USER['height_max'], DELETE_ON_ERROR);
							if (!empty($error)) //Erreur, on arr�te ici
								redirect(HOST . DIR . '/member/register' . url('.php?erroru=' . $error) . '#errorh');
							else
								$user_avatar = $path; //Avatar upload� et valid�.
						}
					}
				}
				
				$path = retrieve(POST, 'avatar', '');
				if (!empty($path))
				{
					$error = $Upload->validate_img($path, $CONFIG_USER['width_max'], $CONFIG_USER['height_max'], DELETE_ON_ERROR);
					if (!empty($error)) //Erreur, on arr�te ici
						redirect(HOST . DIR . '/member/register' . url('.php?erroru=' . $error) . '#errorh');
					else
						$user_avatar = $path; //Avatar post� et valid�.
				}
				
				$admin_sign = $CONFIG['sign'];
						
				$check_user = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
				$check_mail = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
			
				if ($check_user >= 1)
					redirect(HOST . DIR . '/member/register' . url('.php?error=pseudo_auth') . '#errorh');
				elseif ($check_mail >= 1)
					redirect(HOST . DIR . '/member/register' . url('.php?error=mail_auth') . '#errorh');
				else //Succes.
				{
					$user_aprob = ($CONFIG_USER['activ_mbr'] == 0) ? 1 : 0;
					$activ_mbr = ($CONFIG_USER['activ_mbr'] == 1) ? substr(strhash(uniqid(rand(), true)), 0, 15) : ''; //G�n�ration de la cl�e d'activation!
					
					//Suppression des images des stats concernant les membres, si l'info � �t� modifi�e.
					@unlink('../cache/sex.png');
					@unlink('../cache/theme.png');
					
					$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_lang,user_theme,user_mail,user_show_mail,user_editor,user_timezone,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob)
					VALUES ('" . $login . "', '" . $password_hash . "', 0, '0', '" . $user_lang . "', '" . $user_theme . "', '" . $user_mail . "', '" . $user_show_mail . "', '" . $user_editor . "', '" . $user_timezone . "', '" . time() . "', '" . $user_avatar . "', 0, '" . $user_local . "', '" . $user_msn . "', '" . $user_yahoo . "', '" . $user_web . "', '" . $user_occupation . "', '" . $user_hobbies . "', '" . $user_desc . "', '" . $user_sex . "', '" . $user_born . "', '" . $user_sign . "', 0, 0, '" . time() . "', 0, '" . $activ_mbr . "', '', 0, '" . $user_aprob . "')", __LINE__, __FILE__); //Compte membre
					
					$last_mbr_id = $Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_MEMBER); //Id du membre qu'on vient d'enregistrer
					
					//Si son inscription n�cessite une approbation, on en avertit l'administration au biais d'une alerte
					if ($CONFIG_USER['activ_mbr'] == 2)
					{
						import('events/administrator_alert_service');
						
						$alert = new AdministratorAlert();
						$alert->set_entitled($LANG['member_registered_to_approbate']);
						$alert->set_fixing_url('admin/admin_members.php?id=' . $last_mbr_id);
						//Priorit� 3/5
						$alert->set_priority(ADMIN_ALERT_MEDIUM_PRIORITY);
						//Code pour retrouver l'alerte
						$alert->set_id_in_module($last_mbr_id);
						$alert->set_type('member_account_to_approbate');
						
						//Enregistrement
						AdministratorAlertService::save_alert($alert);
					}
					else //R�g�n�ration du cache des stats.
						$Cache->Generate_file('stats');
					
					//Champs suppl�mentaires.
					$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
					if ($extend_field_exist > 0)
					{
						$req_update = '';
						$req_field = '';
						$req_insert = '';
						$result = $Sql->query_while("SELECT field_name, field, possible_values, regex, required
						FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "
						WHERE display = 1", __LINE__, __FILE__);
						while ($row = $Sql->fetch_assoc($result))
						{
							$field = retrieve(POST, $row['field_name'], '', TSTRING_UNCHANGE);
							
							//Champs requis, si vide redirection.
							if ($row['required'] && $row['field'] != 6 && empty($field))
								redirect(HOST . DIR . '/member/register' . url('.php?error=incomplete') . '#errorh');
							
							//Validation par expressions r�guli�res.
							if (is_numeric($row['regex']) && $row['regex'] >= 1 && $row['regex'] <= 5)
							{
								$array_regex = array(
									1 => '`^[0-9]+$`',
									2 => '`^[a-z]+$`',
									3 => '`^[a-z0-9]+$`',
									4 => '`^[a-z0-9._-]+@(?:[a-z0-9_-]{2,}\.)+[a-z]{2,4}$`i',
									5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`i'
								);
								$row['regex'] = $array_regex[$row['regex']];
							}
							
							$valid_field = true;
							if (!empty($row['regex']) && $row['field'] <= 2)
							{
								if (@preg_match($row['regex'], $field))
									$valid_field = true;
								else
									$valid_field = false;
							}
						
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
									$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? addslashes($_POST[$row['field_name'] . '_' . $i]) . '|' : '';
									$i++;
								}
								if ($row['required'] && empty($field))
									redirect(HOST . DIR . '/member/register' . url('.php?error=incomplete') . '#errorh');
							}
							else
								$field = strprotect($field);
								
							if (!empty($field))
							{
								if ($valid_field) //Validation par expression r�guli�re si pr�sente.
								{
									$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
									$req_field .= $row['field_name'] . ', ';
									$req_insert .= '\'' . trim($field, '|') . '\', ';
								}
							}
						}
						$Sql->query_close($result);
						
						$check_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__);
						if ($check_member && !empty($req_update))
								$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__);
						else if (!empty($req_insert))
								$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $last_mbr_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
					}
					
					//Ajout du lien de confirmation par mail si activ� et activation par admin d�sactiv�.
					if ($CONFIG_USER['activ_mbr'] == 1)
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_valid_email_confirm'];
						$valid = sprintf($LANG['register_valid_email'], HOST . DIR . '/member/register.php?key=' . $activ_mbr);
					}
					elseif ($CONFIG_USER['activ_mbr'] == 2)
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_valid_admin'];
						$valid = $LANG['register_valid_admin'];
					}
					else
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_ready'];
						$valid_mail = '';
						$valid = '';
					}
					
					import('io/mail');
					$Mail = new Mail();
					
					$Mail->send_from_properties($user_mail, sprintf($LANG['register_title_mail'], $CONFIG['site_name']), sprintf($LANG['register_mail'], $login, $CONFIG['site_name'], $CONFIG['site_name'], stripslashes($login), $password, $valid, $CONFIG['sign']), $CONFIG['mail_exp']);
					
					//On connecte le membre directement si aucune activation demand�e.
					if ($CONFIG_USER['activ_mbr'] == 0)
					{
						$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "' WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__); //Remise � z�ro du compteur d'essais.
						$Session->start($last_mbr_id, $password, 0, SCRIPT, QUERY_STRING, TITLE, 1); //On lance la session.
					}
					unset($password, $password_hash);
					
					//Affichage de la confirmation d'inscription.
					redirect_confirm(get_start_page(), sprintf($l_register_confirm, stripslashes($login)), 5);
				}
			}
			elseif (!empty($_POST['register_valid']) && $password !== $password_bis)
				redirect(HOST . DIR . '/member/register' . url('.php?error=pass_same') . '#errorh');
			else
				redirect(HOST . DIR . '/member/register' . url('.php?error=incomplete') . '#errorh');
		}
		else
			redirect(HOST . DIR . '/member/register' . url('.php?error=lenght_mini') . '#errorh');
	}
	else
		redirect(HOST . DIR . '/member/register' . url('.php?error=verif_code') . '#errorh');
}
elseif (!empty($user_mail))
	redirect(HOST . DIR . '/member/register' . url('.php?error=invalid_mail') . '#errorh');
else
	redirect(get_start_page());
	
require_once('../kernel/footer.php');

?>