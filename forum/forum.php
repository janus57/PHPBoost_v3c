<?php


























require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$id_get = retrieve(GET, 'id', 0);


if (empty($id_get) || !isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0)
	$Errorh->handler('e_unexist_cat_forum', E_USER_REDIRECT);
	

if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM) || !empty($CAT_FORUM[$id_get]['url']))
	$Errorh->handler('e_auth', E_USER_REDIRECT);


$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach ($CAT_FORUM as $idcat => $array_info_cat)
{
	if ($CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'])
		$Bread_crumb->add($array_info_cat['name'], ($array_info_cat['level'] == 0) ? url('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php'));
}
if (!empty($CAT_FORUM[$id_get]['name'])) 
	$Bread_crumb->add($CAT_FORUM[$id_get]['name'], '');
if (!empty($id_get))
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);	
require_once('../kernel/header.php'); 


$rewrited_title = ($CONFIG['rewrite'] == 1 && !empty($CAT_FORUM[$id_get]['name'])) ? '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) : '';


$change_cat = retrieve(POST, 'change_cat', '');
if (!empty($change_cat))
	redirect(HOST . DIR . '/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . $rewrited_title . '.php', '&'));
	
if (!empty($id_get))
{
	$Template->set_filenames(array(
		'forum_forum'=> 'forum/forum_forum.tpl',
		'forum_top'=> 'forum/forum_top.tpl',
		'forum_bottom'=> 'forum/forum_bottom.tpl'
	));
	
	
	$is_guest = ($User->get_attribute('user_id') !== -1) ? false : true;
	
	
	$max_time_msg = forum_limit_time_msg();
	
	
	if (($CAT_FORUM[$id_get]['id_right'] - $CAT_FORUM[$id_get]['id_left']) > 1) 
	{
		$Template->assign_vars(array(
			'C_FORUM_SUB_CATS' => true
		));
		
		
		$unauth_cats = '';
		if (is_array($AUTH_READ_FORUM))
		{
			foreach ($AUTH_READ_FORUM as $idcat => $auth)
			{
				if ($auth === false)
					$unauth_cats .= $idcat . ',';
			}
			$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
		}
		
		
		$result = $Sql->query_while("SELECT c.id AS cid, c.name, c.subname, c.url, c.nbr_topic, c.nbr_msg, c.status, t.id AS tid, 
		t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
		FROM " . PREFIX . "forum_cats c
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = c.last_topic_id
		LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = '" . $User->get_attribute('user_id') . "' AND v.idtopic = t.id
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
		WHERE c.aprob = 1 AND c.id_left > '" . $CAT_FORUM[$id_get]['id_left'] . "' AND c.id_right < '" . $CAT_FORUM[$id_get]['id_right'] . "' AND c.level = '" . $CAT_FORUM[$id_get]['level'] . "' + 1  " . $unauth_cats . "
		ORDER BY c.id_left ASC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{	
			if ($row['nbr_msg'] !== '0')
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
				$last_topic_title = (strlen(html_entity_decode($last_topic_title, ENT_COMPAT, 'ISO-8859-1')) > 20) ? substr_html($last_topic_title, 0, 20) . '...' : $last_topic_title;

				$last = '<a href="topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . ucfirst($last_topic_title) . '</a><br />
				<a href="topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />
				' . $LANG['by'] . (!empty($row['login']) ? ' <a href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . wordwrap_html($row['login'], 13) . '</a>' : ' ' . $LANG['guest']);
			}
			else
			{
				$row['last_timestamp'] = '';
				$last = '<br />' . $LANG['no_message'] . '<br /><br />';
			}

			
			$subforums = '';
			if ($CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1)
			{		
				foreach ($CAT_FORUM as $idcat => $key) 
				{
					if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'])
					{
						if ($CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1))
						{
							if ($AUTH_READ_FORUM[$row['cid']]) 
							{
								$link = !empty($CAT_FORUM[$idcat]['url']) ? '<a href="' . $CAT_FORUM[$idcat]['url'] . '" class="small_link">' : '<a href="forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small_link">';
								$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';		
							}
						}	
					}
				}	
				$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
			}
			
			
			$img_announce = 'announce';		
			if (!$is_guest)
			{
				if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) 
					$img_announce =  'new_' . $img_announce; 
			}
			$img_announce .= ($row['status'] == '0') ? '_lock' : '';
			
			$Template->assign_block_vars('subcats', array(					
				'IMG_ANNOUNCE' => $img_announce,
				'NAME' => $row['name'],
				'DESC' => $row['subname'],
				'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
				'NBR_TOPIC' => $row['nbr_topic'],
				'NBR_MSG' => $row['nbr_msg'],
				'U_FORUM_URL' => $row['url'],
				'U_FORUM_VARS' => url('.php?id=' . $row['cid'], '-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php'),
				'U_LAST_TOPIC' => $last					
			));
		}
		$Sql->query_close($result);
	}
		
	
	$check_group_write_auth = $User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM);
	$locked_cat = ($CAT_FORUM[$id_get]['status'] == 1 || $User->check_level(ADMIN_LEVEL)) ? false : true;
	if (!$check_group_write_auth)
	{
		$Template->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
		));
	}
	
	elseif ($locked_cat)
	{
		$Template->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_lock_forum']
		));
	}
	
	
	import('util/pagination'); 
	$Pagination = new Pagination();

	
	$i = 0;
	$forum_cats = '';	
	foreach ($Bread_crumb->array_links as $key => $array)
	{
		if ($i == 2)
			$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
		elseif ($i > 2)		
			$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
		$i++;
	}
	
	
	$check_group_edit_auth = $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);

	$nbr_topic = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $id_get . "'", __LINE__, __FILE__);
	$Template->assign_vars(array(
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
		'SID' => SID,		
		'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
		'PAGINATION' => $Pagination->display('forum' . url('.php?id=' . $id_get . '&amp;p=%d', '-' . $id_get . '-%d.php'), $nbr_topic, 'p', $CONFIG_FORUM['pagination_topic'], 3),
		'IDCAT' => $id_get,
		
		'C_MASS_MODO_CHECK' => false,
		'C_POST_NEW_SUBJECT' => ($check_group_write_auth && !$locked_cat),
		'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . url('.php?read=1&amp;f=' . $id_get, '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
		'U_CHANGE_CAT'=> 'forum' . url('.php?id=' . $id_get . '&amp;token=' . $Session->get_token(), '-' . $id_get . $rewrited_title . '.php?token=' . $Session->get_token()),
		'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
		'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
		'U_FORUM_CAT' => $forum_cats,		
		'U_POST_NEW_SUBJECT' => 'post' . url('.php?new=topic&amp;id=' . $id_get, ''),
		'L_FORUM_INDEX' => $LANG['forum_index'],		
		'L_SUBFORUMS' => $LANG['sub_forums'],
		'L_DISPLAY_UNREAD_MSG' => $LANG['show_not_reads'],
		'L_FORUM' => $LANG['forum'],		
		'L_AUTHOR' => $LANG['author'],
		'L_TOPIC' => $LANG['topic_s'],
		'L_ANSWERS' => $LANG['replies'],
		'L_MESSAGE' => $LANG['message_s'],
		'L_VIEW' => $LANG['views'],
		'L_LAST_MESSAGE' => $LANG['last_messages'],
		'L_POST_NEW_SUBJECT' => $LANG['post_new_subject'],
		'L_FOR_SELECTION' => $LANG['for_selection'],
		'L_CHANGE_STATUT_TO' => sprintf($LANG['change_status_to'], $CONFIG_FORUM['display_msg']),
		'L_CHANGE_STATUT_TO_DEFAULT' => $LANG['change_status_to_default'],
		'L_MOVE_TO' => $LANG['move_to'],
		'L_DELETE' => $LANG['delete'],
		'L_LOCK' => $LANG['forum_lock'],
		'L_UNLOCK' => $LANG['forum_unlock'],
		'L_GO' => $LANG['go']
	));		

	$nbr_topics_display = 0;
	$result = $Sql->query_while("SELECT m1.login AS login, m2.login AS last_login, t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id , t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, v.last_view_id, p.question, tr.id AS idtrack
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = '" . $User->get_attribute('user_id') . "' AND v.idtopic = t.id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = t.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = t.last_user_id
	LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = t.id
	LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = t.id AND tr.user_id = '" . $User->get_attribute('user_id') . "'
	WHERE t.idcat = '" . $id_get . "'
	ORDER BY t.type DESC , t.last_timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg($CONFIG_FORUM['pagination_topic'], 'p'), $CONFIG_FORUM['pagination_topic']), __LINE__, __FILE__);	
	while ( $row = $Sql->fetch_assoc($result) )
	{
		
		$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');
		
		
		$img_announce = 'announce';		
		$new_msg = false;
		if (!$is_guest) 
		{
			$new_msg = false;
			if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) 
			{	
				$img_announce =  'new_' . $img_announce; 
				$new_msg = true;
			}
		}
		$img_announce .= ($row['type'] == '1') ? '_post' : '';
		$img_announce .= ($row['type'] == '2') ? '_top' : '';
		$img_announce .= ($row['status'] == '0' && $row['type'] == '0') ? '_lock' : '';

		
		
		if (!empty($row['last_view_id'])) 
		{
			$last_msg_id = $row['last_view_id']; 
			$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id = $row['last_msg_id']; 
			$last_page = ceil( $row['nbr_msg'] / $CONFIG_FORUM['pagination_msg'] );
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
		}
		
		
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($row['title']) : '';
		
		
		$last_msg = '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br /> ' . $LANG['by'] . ' ' . (!empty($row['last_login']) ? '<a class="small_link" href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '">' . wordwrap_html($row['last_login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>');
		
		
		$new_ancre = ($new_msg === true && !$is_guest) ? '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>' : '';
		
		$Template->assign_block_vars('topics', array(
			'C_IMG_POLL' => !empty($row['question']),
			'C_IMG_TRACK' => !empty($row['idtrack']),
			'C_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $CONFIG_FORUM['icon_activ_display_msg'] && $row['display_msg']),
			'C_HOT_TOPIC' => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $CONFIG_FORUM['pagination_msg'])),
			'IMG_ANNOUNCE' => $img_announce,
			'ANCRE' => $new_ancre,
			'TYPE' => $type[$row['type']],
			'TITLE' => ucfirst($row['title']),			
			'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>',
			'DESC' => $row['subtitle'],
			'PAGINATION_TOPICS' => $Pagination->display('topic' . url('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d.php'), $row['nbr_msg'], 'pt', $CONFIG_FORUM['pagination_msg'], 2, 10, NO_PREVIOUS_NEXT_LINKS, LINK_START_PAGE),
			'MSG' => ($row['nbr_msg'] - 1),
			'VUS' => $row['nbr_views'],
			'L_DISPLAY_MSG' => ($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '', 
			'U_TOPIC_VARS' => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
			'U_LAST_MSG' => $last_msg
		));
		$nbr_topics_display++;
	}
	$Sql->query_close($result);
		
	
	if ($nbr_topics_display == 0)
	{
		$Template->assign_vars(array(
			'C_NO_TOPICS' => true,
			'L_NO_TOPICS' => $LANG['no_topics']
		));
	}
		
	
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '/forum/forum.php' AND s.session_script_get LIKE '%id=" . $id_get . "%'");
	
	$Template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'SELECT_CAT' => forum_list_cat($id_get, $CAT_FORUM[$id_get]['level']), 
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	));
	
	$Template->pparse('forum_forum');
}
else
	redirect(HOST . DIR . '/forum/index.php' . SID2);

include('../kernel/footer.php');

?>
