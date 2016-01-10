<?php
/*##################################################
 *                              calendar.php
 *                            -------------------
 *   begin                : January 29, 2006
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

require_once('../kernel/begin.php');
require_once('../calendar/calendar_begin.php');
require_once('../kernel/header.php');

$time = gmdate_format('Ymd');
$year = substr($time, 0, 4);
$month = substr($time, 4, 2);
$day = substr($time, 6, 2);

$year = retrieve(GET, 'y', $year);
$year = empty($year) ? 0 : $year;
$month = retrieve(GET, 'm', $month);
$month = empty($month) ? 0 : $month;
$day = retrieve(GET, 'd', $day);
$day = empty($day) ? 0 : $day;
$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;

$get_event = retrieve(GET, 'e', '');
$id = retrieve(GET, 'id', 0);
$add = retrieve(GET, 'add', false);
$delete = retrieve(GET, 'delete', false);
$edit = retrieve(GET, 'edit', false);

if ($delete)
    $Session->csrf_get_protect();

$checkdate = checkdate($month, $day, $year); //Validit� de la date entr�e.
if ($checkdate === true && empty($id) && !$add)
{
	//Redirection vers l'�venement suivant/pr�c�dent.
	if ($get_event == 'up')
	{
		$event_up = $Sql->query("SELECT timestamp
		FROM " . PREFIX . "calendar
		WHERE timestamp > '" . mktime(23, 59, 59, $month, $day, $year, 0) . "'
		ORDER BY timestamp
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
		
		if (!empty($event_up))
		{
			$time = gmdate_format('Ymd', $event_up);
			$year = substr($time, 0, 4);
			$month = substr($time, 4, 2);
			$day = substr($time, 6, 2);
			
			redirect(HOST . DIR . '/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}
		else
			redirect(HOST . DIR . '/calendar/calendar' . url('.php?e=fu&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fu', '&'));
	}
	elseif ($get_event == 'down')
	{
		$event_down = $Sql->query("SELECT timestamp
		FROM " . PREFIX . "calendar
		WHERE timestamp < '" . mktime(0, 0, 0, $month, $day, $year, 0) . "'
		ORDER BY timestamp DESC
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
			
		if (!empty($event_down))
		{
			$time = gmdate_format('Ymd', $event_down);
			$year = substr($time, 0, 4);
			$month = substr($time, 4, 2);
			$day = substr($time, 6, 2);
			
			redirect(HOST . DIR . '/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}
		else
			redirect(HOST . DIR . '/calendar/calendar' . url('.php?e=fd&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fd', '&'));
	}
	
	$Template->set_filenames(array(
		'calendar'=> 'calendar/calendar.tpl'
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'invalid_date':
		$errstr = $LANG['e_invalid_date'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_NOTICE);
		
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	$array_l_month = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'],
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
	$month_day = $array_month[$month - 1];
	
	if ($User->check_level($CONFIG_CALENDAR['calendar_auth'])) //Autorisation de poster?
		$add_event = '<a href="calendar' . url('.php?add=1') . '" title="' . $LANG['add_event'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="" /></a><br />';
	else
		$add_event = '';
	
	$Template->assign_vars(array(
		'C_CALENDAR_DISPLAY' => true,
		'ADMIN_CALENDAR' => ($User->check_level(ADMIN_LEVEL)) ? '<a href="' . HOST . DIR . '/calendar/admin_calendar.php"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt ="" style="vertical-align:middle;" /></a>' : '',
		'ADD' => $add_event,
		'DATE' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
		'U_PREVIOUS' => ($month == 1) ? url('.php?d=' . $day . '&amp;m=12&amp;y=' . ($year - 1), '-' . $day . '-12-' . ($year - 1) . '.php') :  url('.php?d=1&amp;m=' . ($month - 1) . '&amp;y=' . $year, '-1-' . ($month - 1) . '-' . $year . '.php'),
		'U_NEXT' => ($month == 12) ? url('.php?d=' . $day . '&amp;m=1&amp;y=' . ($year + 1), '-' . $day . '-1-' . ($year + 1) . '.php') :  url('.php?d=1&amp;m=' . ($month + 1) . '&amp;y=' . $year, '-1-' . ($month + 1) . '-' . $year . '.php'),
		'U_PREVIOUS_EVENT' => ( $get_event != 'fd' ) ? '<a href="calendar' . url('.php?e=down&amp;d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=down') . '#act" title="">&laquo;</a>' : '',
		'U_NEXT_EVENT' => ( $get_event != 'fu') ? '<a href="calendar' . url('.php?e=up&amp;d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=up') . '#act" title="">&raquo;</a>' : '',
		'L_CALENDAR' => $LANG['calendar'],
		'L_ACTION' => $LANG['action'],
		'L_EVENTS' => $LANG['events'],
		'L_SUBMIT' => $LANG['submit']
	));
	
	//G�n�ration des select.
	for ($i = 1; $i <= 12; $i++)
	{
		$selected = ($month == $i) ? 'selected="selected"' : '';
		$Template->assign_block_vars('month', array(
			'MONTH' => '<option value="' . $i . '" ' . $selected . '>' . $array_l_month[$i - 1] . '</option>'
		));
	}
	for ($i = 1970; $i <= 2037; $i++)
	{
		$selected = ($year == $i) ? 'selected="selected"' : '';
		$Template->assign_block_vars('year', array(
			'YEAR' => '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>'
		));
	}
	
	//R�cup�ration des actions du mois en cours.
	$result = $Sql->query_while("SELECT timestamp
	FROM " . PREFIX . "calendar
	WHERE timestamp BETWEEN '" . mktime(0, 0, 0, $month, 1, $year, 0) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year, 0) . "'
	ORDER BY timestamp
	" . $Sql->limit(0, ($array_month[$month - 1] - 1)), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$day_action = gmdate_format('j', $row['timestamp']);
		$array_action[$day_action] = true;
	}
	$Sql->query_close($result);
	
	//G�n�ration des jours du calendrier.
	$array_l_days =  array($LANG['monday'], $LANG['tuesday'], $LANG['wenesday'], $LANG['thursday'], $LANG['friday'], $LANG['saturday'],
	$LANG['sunday']);
	foreach ($array_l_days as $l_day)
	{
		$Template->assign_block_vars('day', array(
			'L_DAY' => '<td class="row3"><span class="text_small">' . $l_day . '</span></td>'
		));
	}
	
	//Premier jour du mois.
	$first_day = @gmdate_format('w', @mktime(1, 0, 0, $month, 1, $year)); 
	if ($first_day == 0)
		$first_day = 7;
		
	//G�n�ration du calendrier.
	$j = 1;
	$last_day = ($month_day + $first_day);
	for ($i = 1; $i <= 42; $i++)
	{
		if ($i >= $first_day && $i < $last_day)
		{
			$action = $j;
			if ( !empty($array_action[$j]) )
			{
				$action = '<a href="calendar' . url('.php?d=' . $j . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $j . '-' . $month . '-' . $year . '.php') . '#act">' . $j . '</a>';
				$class = 'calendar_event';
			}
			elseif ($day == $j)
				$class = 'calendar_today';
			else
				$class = 'calendar_other';
			
			$contents = '<td class="' . $class . '">' . $action . '</td>';
			$j++;
		}
		else
			$contents = '<td class="calendar_none">&nbsp;</td>';

		$Template->assign_block_vars('calendar', array(
			'DAY' => $contents,
			'TR' => (($i % 7) == 0 && $i != 42) ? '</tr><tr style="text-align:center;">' : ''
		));
	}
	
	
	//Affichage de l'action pour la p�riode du jour donn�.
	if (!empty($day))
	{
		$java = '';
		$result = $Sql->query_while("SELECT cl.id, cl.timestamp, cl.title, cl.contents, cl.user_id, cl.nbr_com, m.login
		FROM " . PREFIX . "calendar cl
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id=cl.user_id
		WHERE cl.timestamp BETWEEN '" . mktime(0, 0, 0, $month, $day, $year, 0) . "' AND '" . mktime(23, 59, 59, $month, $day, $year, 0) . "'
		GROUP BY cl.id", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($User->check_level(ADMIN_LEVEL))
			{
				$edit = '&nbsp;&nbsp;<a href="calendar' . url('.php?edit=1&amp;id=' . $row['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="calendar' . url('.php?delete=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm_del();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" alt="" /></a>';
				$java = '<script type="text/javascript">
				<!--
				function Confirm_del() {
				return confirm("' . $LANG['alert_delete_msg'] . '");
				}
				-->
				</script>';
			}
			else
			{
				$edit = '';
				$del = '';
				$java = '';
			}
			
			import('content/comments');
			
			$Template->assign_block_vars('action', array(
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'TITLE' => $row['title'],
				'CONTENTS' => second_parse($row['contents']),
				'LOGIN' => '<a class="com" href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'COM' => Comments::com_display_link($row['nbr_com'], '../calendar/calendar' . url('.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&amp;e=' . $row['id'] . '&amp;com=0', '-' . $day . '-' . $month . '-' . $year . '-' . $row['id'] . '.php?com=0'), $row['id'], 'calendar'),
				'EDIT' => $edit,
				'DEL' => $del,
				'L_ON' => $LANG['on']
			));
			
			$check_action = true;
		}
		$Sql->query_close($result);
			
		if (!isset($check_action))
		{
			$Template->assign_block_vars('action', array(
				'TITLE' => '&nbsp;',
				'LOGIN' => '',
				'DATE' => gmdate_format('date_format_short', mktime(0, 0, 0, $month, $day, $year, 0)),
				'CONTENTS' => '<p style="text-align:center;">' . $LANG['no_current_action'] . '</p>'
			));
		}
		
		$Template->assign_vars(array(
			'JAVA' => $java,
			'L_ON' => $LANG['on']
		));
	}
	
	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$Template->assign_vars(array(
			'COMMENTS' => display_comments('calendar', $get_event, url('calendar.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&amp;e=' . $get_event . '&amp;com=%s', 'calendar-' . $day . '-' . $month . '-' . $year . '-' . $get_event . '.php?com=%s'))
		));
	}

	$Template->pparse('calendar');
}
elseif (!empty($id))
{
	if (!$User->check_level(ADMIN_LEVEL)) //Admins seulement autoris�s � editer/supprimer!
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	
	if ($delete) //Suppression simple.
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "calendar WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Suppression des commentaires associ�s.
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $id . "' AND script = 'calendar'", __LINE__, __FILE__);
		
		redirect(HOST . SCRIPT . SID2);
	}
	elseif ($edit)
	{
		if (!empty($_POST['valid']))
		{
			$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
			$title = retrieve(POST, 'title', '');
			
			//Cacul du timestamp � partir de la date envoy�.
			$date = retrieve(POST, 'date', '', TSTRING_UNCHANGE);
			$hour = retrieve(POST, 'hour', 0);
			$min = retrieve(POST, 'min', 0);
			
			$timestamp = strtotimestamp($date, $LANG['date_format_short']);
			if ($timestamp > 0)
				$timestamp += ($hour*3600) + ($min*60);
			else
				$timestamp = 0;
				
			if ($timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59)) //Validit� de la date entr�e.
			{
				if (!empty($title) && !empty($contents)) //succ�s
				{
					$Sql->query_inject("UPDATE " . PREFIX . "calendar SET title = '" . $title . "', contents = '" . $contents . "', timestamp = '" . $timestamp . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$day = gmdate_format('d', $timestamp);
					$month = gmdate_format('m', $timestamp);
					$year = gmdate_format('Y', $timestamp);
					
					redirect(HOST . DIR . '/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
				}
				else
					redirect(HOST . SCRIPT . url('?edit=1&error=incomplete', '', '&') . '#errorh');
			}
			else
				redirect(HOST . SCRIPT . url('?add=1&error=invalid_date', '', '&') . '#errorh');
		}
		else //Formulaire d'�dition
		{
			$Template->set_filenames(array(
				'calendar'=> 'calendar/calendar.tpl'
			));
			
			//R�cup�ration des infos
			$row = $Sql->query_array(PREFIX . 'calendar', 'timestamp', 'title', 'contents', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			$Template->assign_vars(array(
				'C_CALENDAR_FORM' => true,
				'KERNEL_EDITOR' => display_editor(),
				'UPDATE' => url('?edit=1&amp;id=' . $id . '&amp;token=' . $Session->get_token()),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'DAY_DATE' => !empty($row['timestamp']) ? gmdate_format('d', $row['timestamp']) : '',
				'MONTH_DATE' => !empty($row['timestamp']) ? gmdate_format('m', $row['timestamp']) : '',
				'YEAR_DATE' => !empty($row['timestamp']) ? gmdate_format('Y', $row['timestamp']) : '',
				'HOUR' => !empty($row['timestamp']) ? gmdate_format('h', $row['timestamp']) : '',
				'MIN' => !empty($row['timestamp']) ? gmdate_format('i', $row['timestamp']) : '',
				'CONTENTS' => unparse($row['contents']),
				'TITLE'	 => $row['title'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_EDIT_EVENT' => $LANG['edit_event'],
				'L_DATE_CALENDAR' => $LANG['date_calendar'],
				'L_ON' => $LANG['on'],
				'L_AT' => stripslashes($LANG['at']),
				'L_TITLE' => $LANG['title'],
				'L_ACTION' => $LANG['action'],
				'L_SUBMIT' => $LANG['update'],
				'L_RESET' => $LANG['reset']
			));
		
			//Gestion erreur.
			$get_error = retrieve(GET, 'error', '');
			switch ($get_error)
			{
				case 'invalid_date':
				$errstr = $LANG['e_invalid_date'];
				break;
				case 'incomplete':
				$errstr = $LANG['e_incomplete'];
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$Errorh->handler($errstr, E_USER_NOTICE);
			
			$Template->pparse('calendar');
		}
	}
	else
		redirect(HOST . SCRIPT . SID2);
}
elseif ($add) //Ajout d'un �venement
{
	if (!$User->check_level($CONFIG_CALENDAR['calendar_auth'])) //Autorisation de poster?
		$Errorh->handler('e_auth', E_USER_REDIRECT);

	if (!empty($_POST['valid'])) //Enregistrement
	{
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$title = retrieve(POST, 'title', '');
		
		//Cacul du timestamp � partir de la date envoy�.
		$date = retrieve(POST, 'date', '', TSTRING_UNCHANGE);
		$hour = retrieve(POST, 'hour', 0);
		$min = retrieve(POST, 'min', 0);
		
		$timestamp = strtotimestamp($date, $LANG['date_format_short']);
		if ($timestamp > 0)
			$timestamp += ($hour*3600) + ($min*60);
		else
			$timestamp = 0;
			
		if ($timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59)) //Validit� de la date entr�e.
		{
			if (!empty($title) && !empty($contents)) //succ�s
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "calendar (timestamp,title,contents,user_id,nbr_com) VALUES ('" . $timestamp . "', '" . $title . "', '" . $contents . "', '" . $User->get_attribute('user_id') . "', 0)", __LINE__, __FILE__);
				
				$day = gmdate_format('d', $timestamp);
				$month = gmdate_format('m', $timestamp);
				$year = gmdate_format('Y', $timestamp);
				
				redirect(HOST . DIR . '/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
			}
			else //Champs incomplet!
				redirect(HOST . SCRIPT . url('?add=1&error=incomplete', '', '&') . '#errorh');
		}
		else
			redirect(HOST . SCRIPT . url('?add=1&error=invalid_date', '', '&') . '#errorh');
	}
	else
	{
		$Template->set_filenames(array(
			'calendar'=> 'calendar/calendar.tpl'
		));

		$time = gmdate_format('YmdHi');
		$year = substr($time, 0, 4);
		$month = substr($time, 4, 2);
		$day = substr($time, 6, 2);
		$hour = substr($time, 8, 2);
		$min = substr($time, 10, 2);

		$array_l_month = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'],
		$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
		
		$Template->assign_vars(array(
			'C_CALENDAR_FORM' => true,
			'KERNEL_EDITOR' => display_editor(),
			'UPDATE' => url('?add=1&amp;token=' . $Session->get_token()),
			'DATE' => gmdate_format('date_format_short'),
			'DAY_DATE' => $day,
			'MONTH_DATE' => $month,
			'YEAR_DATE' => $year,
			'HOUR' => $hour,
			'MIN' => $min,
			'CONTENTS' => '',
			'TITLE'	 => '',
			'L_REQUIRE_TITLE' => $LANG['require_title'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_EDIT_EVENT' => $LANG['add_event'],
			'L_DATE_CALENDAR' => $LANG['date_calendar'],
			'L_ON' => $LANG['on'],
			'L_AT' => stripslashes($LANG['at']),
			'L_TITLE' => $LANG['title'],
			'L_ACTION' => $LANG['action'],
			'L_SUBMIT' => $LANG['submit'],
			'L_RESET' => $LANG['reset']
		));
		
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'invalid_date':
			$errstr = $LANG['e_invalid_date'];
			break;
			case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
			default:
			$errstr = '';
		}
		if (!empty($errstr))
			$Errorh->handler($errstr, E_USER_NOTICE);

		$Template->pparse('calendar');
	}
}
else
	redirect(HOST . SCRIPT . url('?error=invalid_date', '', '&') . '#errorh');

require_once('../kernel/footer.php');

?>