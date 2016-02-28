<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../kernel/header_no_display.php');

$track = retrieve(GET, 't', '');	
$untrack = retrieve(GET, 'ut', '');	
$track_pm = retrieve(GET, 'tp', '');	
$untrack_pm = retrieve(GET, 'utp', '');	
$track_mail = retrieve(GET, 'tm', '');	
$untrack_mail = retrieve(GET, 'utm', '');	
$msg_d = retrieve(GET, 'msg_d', '');

if (retrieve(GET, 'refresh_unread', false)) 
{
	$is_guest = ($User->get_attribute('user_id') !== -1) ? false : true;
	$nbr_msg_not_read = 0;
	if (!$is_guest)
	{
		
		$max_time_msg = forum_limit_time_msg();
		
		
		$unauth_cats = '';
		if (is_array($AUTH_READ_FORUM))
		{
			foreach ($AUTH_READ_FORUM as $idcat => $auth)
			{
				if (!$auth)
					$unauth_cats .= $idcat . ',';
			}
			$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
		}
		
		$contents = '';			
		
		$nbr_msg_not_read = 0;
		$result = $Sql->query_while("SELECT t.id AS tid, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
		FROM " . PREFIX . "forum_topics t
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
		LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = '" . $User->get_attribute('user_id') . "'
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
		WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $unauth_cats . "
		ORDER BY t.last_timestamp DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			
			if (!empty($row['last_view_id'])) 
			{
				$last_msg_id = $row['last_view_id']; 
				$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
				$last_page_rewrite = '-0-' . $row['last_view_id'];
			}
			else
			{
				$last_msg_id = $row['last_msg_id']; 
				$last_page = ceil($row['t_nbr_msg'] / $CONFIG_FORUM['pagination_msg']);
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';					
			}	

			$last_topic_title = (($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '') . ' ' . ucfirst($row['title']);			
			$last_topic_title = (strlen(html_entity_decode($last_topic_title, ENT_COMPAT, 'ISO-8859-1')) > 25) ? substr_html($last_topic_title, 0, 25) . '...' : $last_topic_title;			
			$last_topic_title = addslashes($last_topic_title);			
			$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];

			$contents .= '<tr><td class="forum_notread" style="width:100%"><a href="topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . addslashes(url_encode_rewrite($row['title']))  . '.php') . '#m' .  $last_msg_id . '"><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a> <a href="topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . addslashes(url_encode_rewrite($row['title']))  . '.php') . '" class="small_link">' . $last_topic_title . '</a></td><td class="forum_notread" style="white-space:nowrap">' . ($row['last_user_id'] != '-1' ? '<a href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . addslashes($row['login']) . '</a>' : '<em>' . addslashes($LANG['guest']) . '</em>') . '</td><td class="forum_notread" style="white-space:nowrap">' . gmdate_format('date_format', $row['last_timestamp']) . '</td></tr>';
			$nbr_msg_not_read++;
		}
		$Sql->query_close($result);

		$max_visible_topics = 10;
		$height_visible_topics = ($nbr_msg_not_read < $max_visible_topics) ? (23 * $nbr_msg_not_read) : 23 * $max_visible_topics;
			
		echo "array_unread_topics[0] = '" . $nbr_msg_not_read . "';\n";
		echo "array_unread_topics[1] = '" . '<a class="small_link" href="../forum/unread.php' .SID . '" title="' . addslashes($LANG['show_not_reads']) . '">' . addslashes($LANG['show_not_reads']) . ($User->get_attribute('user_id') !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>' . "';\n";
		echo "array_unread_topics[2] = '" . '<div class="row2" style="width:438px;height:' . max($height_visible_topics, 65) . 'px;overflow:auto;padding:0px;" onmouseover="forum_hide_block(\\\'forum_unread\\\', 1);" onmouseout="forum_hide_block(\\\'forum_unread\\\', 0);"><table class="module_table" style="margin:2px;width:99%">' . $contents . "</table></div>';";
	}
	else
		echo '';
}
elseif (retrieve(GET, 'del', false)) 
{
	$Session->csrf_get_protect(); 
	
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$idm_get = retrieve(GET, 'idm', '');	
	
	$msg = $Sql->query_array(PREFIX . 'forum_msg', 'user_id', 'idtopic', "WHERE id = '" . $idm_get . "'", __LINE__, __FILE__);	
	
	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'id', 'user_id', 'idcat', 'first_msg_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	if (!empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get) 
	{	
		if (!empty($topic['idcat']) && ($User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $User->get_attribute('user_id') == $msg['user_id'])) 
		{
			list($nbr_msg, $previous_msg_id) = $Forumfct->Del_msg($idm_get, $msg['idtopic'], $topic['idcat'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']); 
			if ($nbr_msg === false && $previous_msg_id === false) 
				echo '-1';
			else
				echo '1';
		}
		else
			echo '-1';
	}
	else
		echo '-1';	
}
elseif (!empty($track) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Track_topic($track); 
	echo 1;
}
elseif (!empty($untrack) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Untrack_topic($untrack); 
	echo 2;
}
elseif (!empty($track_pm) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Track_topic($track_pm, FORUM_PM_TRACKING); 
	echo 1;
}
elseif (!empty($untrack_pm) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Untrack_topic($untrack_pm, FORUM_PM_TRACKING); 
	echo 2;
}
elseif (!empty($track_mail) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Track_topic($track_mail, FORUM_EMAIL_TRACKING); 
	echo 1;
}
elseif (!empty($untrack_mail) && $User->check_level(MEMBER_LEVEL)) 
{
	
	include_once('../forum/forum.class.php');
	$Forumfct = new Forum;

	$Forumfct->Untrack_topic($untrack_mail, FORUM_EMAIL_TRACKING); 
	echo 2;
}
elseif (!empty($msg_d))
{
	$Session->csrf_get_protect(); 
	
	
	$topic = $Sql->query_array(PREFIX . "forum_topics", "idcat", "user_id", "display_msg", "WHERE id = '" . $msg_d . "'", __LINE__, __FILE__);
	if ((!empty($topic['user_id']) && $User->get_attribute('user_id') == $topic['user_id']) || $User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM))
	{
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET display_msg = 1 - display_msg WHERE id = '" . $msg_d . "'", __LINE__, __FILE__);
		echo ($topic['display_msg']) ? 2 : 1;
	}	
}
elseif (retrieve(GET, 'warning_moderation_panel', false) || retrieve(GET, 'punish_moderation_panel', false)) 
{
	$login = !empty($_POST['login']) ? strprotect(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	if (!empty($login))
	{
		$i = 0;
		$result = $Sql->query_while ("SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (retrieve(GET, 'warning_moderation_panel', false))
				echo '<a href="moderation_forum.php?action=warning&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			elseif (retrieve(GET, 'punish_moderation_panel', false))
				echo '<a href="moderation_forum.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			
			$i++;
		}
		
		if ($i == 0) 
			echo $LANG['no_result'];
	}
	else
		echo $LANG['no_result'];
	
	$Sql->close(); 
}

?>
