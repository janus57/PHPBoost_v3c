<?php



























function forum_list_user_online($sql_condition)
{
	global $Sql, $CONFIG;
	
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $Sql->query_while("SELECT s.user_id, s.level, m.login, m.user_groups
	FROM " . DB_TABLE_SESSIONS . " s 
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id 
	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' " . $sql_condition . "
	ORDER BY s.session_time DESC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$group_color = User::get_group_color($row['user_groups'], $row['level']);
		switch ($row['level']) 
		{ 		
			case -1:
			$status = 'visiteur';
			$total_visit++;
			break;			
			case 0:
			$status = 'member';
			$total_member++;
			break;			
			case 1: 
			$status = 'modo';
			$total_modo++;
			break;			
			case 2: 
			$status = 'admin';
			$total_admin++;
			break;
		} 
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['login']) && $row['level'] != -1) ?  $coma . '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>' : '';
	}
	$Sql->query_close($result);
	
	return array($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_admin + $total_modo + $total_member + $total_visit);
}


function forum_list_cat($id_select, $level)
{
	global $Group, $CAT_FORUM, $AUTH_READ_FORUM;
	
	$select = '';
	foreach ($CAT_FORUM as $idcat => $array_cat)
	{
		$selected = '';
		if ($id_select == $idcat && $array_cat['level'] == $level)
			$selected = ' selected="selected"';
		
		$margin = ($array_cat['level'] > 0) ? str_repeat('------', $array_cat['level']) : '';
		$select .= $AUTH_READ_FORUM[$idcat] && empty($CAT_FORUM[$idcat]['url']) ? '<option value="' . $idcat . '"' . $selected . '>' . $margin . ' ' . $array_cat['name'] . '</option>' : '';
	}
	
	return $select;
}


function forum_limit_time_msg()
{
	global $User, $CONFIG_FORUM;
	
	$last_view_forum = $User->get_attribute('last_view_forum');
	$max_time = (time() - $CONFIG_FORUM['view_time']);
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	
	return $max_time_msg;
}


function mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp)
{
	global $Sql, $User, $CONFIG_FORUM;
	
	
	$last_view_forum = ($User->get_attribute('last_view_forum') > 0) ? $User->get_attribute('last_view_forum') : 0;
	$max_time = (time() - $CONFIG_FORUM['view_time']);
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	if ($User->get_attribute('user_id') !== -1 && $last_timestamp >= $max_time_msg)
	{
		$check_view_id = $Sql->query("SELECT last_view_id FROM " . PREFIX . "forum_view WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		if (!empty($check_view_id) && $check_view_id != $last_msg_id) 
		{
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_view SET last_view_id = '" . $last_msg_id . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
		elseif (empty($check_view_id))
		{			
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			$Sql->query_inject("INSERT ".LOW_PRIORITY." INTO " . PREFIX . "forum_view (idtopic, last_view_id, user_id, timestamp) VALUES('" . $idtopic . "', '" . $last_msg_id . "', '" . $User->get_attribute('user_id') . "', '" . time() . "')", __LINE__, __FILE__);			
		}
		else
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
	}
	else
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
}
	

function forum_history_collector($type, $user_id_action = '', $url_action = '')
{
	global $Sql, $User;
	
	$Sql->query_inject("INSERT INTO " . PREFIX . "forum_history (action, user_id, user_id_action, url, timestamp) VALUES('" . strprotect($type) . "', '" . $User->get_attribute('user_id') . "', '" . numeric($user_id_action) . "', '" . strprotect($url_action) . "', '" . time() . "')", __LINE__, __FILE__);
}


function forum_generate_feeds()
{
    import('content/syndication/feed');
    Feed::clear_cache('forum');
}


function token_colorate($matches)
{
    static $open_tag = 0;
    static $close_tag = 0;
    
    $open_tag += substr_count($matches[1], '<');
    $close_tag += substr_count($matches[1], '>');
    
    if ($open_tag == $close_tag)
        return $matches[1] . '<span style="background:yellow;">' . $matches[2] . '</span>' . $matches[3];
    else
        return $matches[0];
}

?>
