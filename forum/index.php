<?php


























require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$id_get = retrieve(GET, 'id', 0);
$cat_name = !empty($CAT_FORUM[$id_get]['name']) ? $CAT_FORUM[$id_get]['name'] : '';
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->add($cat_name, '');

if (!empty($id_get) && !empty($CAT_FORUM[$id_get]['name']))
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php'); 

$Template->set_filenames(array(
	'forum_index'=> 'forum/forum_index.tpl'
));


$display_sub_cat = ' AND c.level BETWEEN 0 AND 1';
$display_cat = !empty($id_get);
if ($display_cat)
{
	$intervall = $Sql->query_array(PREFIX . "forum_cats", "id_left", "id_right", "level", "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	$display_sub_cat = ' AND c.id_left > \'' . $intervall['id_left'] . '\'
   AND c.id_right < \'' . $intervall['id_right'] . '\'
   AND c.level = \'' . $intervall['level'] . '\' + 1';
}


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


$max_time_msg = forum_limit_time_msg();

$is_guest = ($User->get_attribute('user_id') !== -1) ? false : true;
$total_topic = 0;
$total_msg = 0;	
$i = 0;

$result = $Sql->query_while("SELECT c.id AS cid, c.level, c.name, c.subname, c.url, c.nbr_msg, c.nbr_topic, c.status, c.last_topic_id, t.id AS tid, 
t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, v.last_view_id 
FROM " . PREFIX . "forum_cats c
LEFT JOIN " . PREFIX . "forum_topics t ON t.id = c.last_topic_id
LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = '" . $User->get_attribute('user_id') . "' AND v.idtopic = t.id
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
WHERE c.aprob = 1 " . $display_sub_cat . " " . $unauth_cats . "
ORDER BY c.id_left", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{	
	$Template->assign_block_vars('forums_list', array(
	));	
	
	if ($CAT_FORUM[$row['cid']]['level'] == 0 && $i > 0) 
	{
		$Template->assign_block_vars('forums_list.endcats', array(
		));	
	}
	$i++;
		
	if ($row['level'] === '0') 
	{
		$Template->assign_block_vars('forums_list.cats', array(
			'IDCAT' => $row['cid'],
			'NAME' => $row['name'],
			'U_FORUM_VARS' => url('index.php?id=' . $row['cid'], 'cat-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php')
		));
	}
	else 
	{
		if ($display_cat) 
		{
			$Template->assign_block_vars('forums_list.cats', array(
				'IDCAT' => $id_get,
				'NAME' => $CAT_FORUM[$id_get]['name'],
				'U_FORUM_VARS' => url('index.php?id=' . $id_get, 'cat-' . $id_get . '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php')
			));
			$display_cat = false;
		}
		
		$subforums = '';
		$Template->assign_vars(array(
			'C_FORUM_ROOT_CAT' => false,
			'C_FORUM_CHILD_CAT' => true,
			'C_END_S_CATS' => false
		));			
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
		
		if (!empty($row['last_topic_id']))
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
			$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
			
			$last = '<a href="topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . $last_topic_title . '</a><br />
			<a href="topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />' . $LANG['by'] . ' ' . ($row['last_user_id'] != '-1' ? '<a href="../member/member' . url('.php?id=' . $row['last_user_id'], '-' . $row['last_user_id'] . '.php') . '" class="small_link">' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>');
		}
		else
		{
			$row['last_timestamp'] = '';
			$last = '<br />' . $LANG['no_message'] . '<br /><br />';
		}

		
		$img_announce = 'announce';		
		if (!$is_guest)
		{
			if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) 
				$img_announce =  'new_' . $img_announce; 
		}
		$img_announce .= ($row['status'] == '0') ? '_lock' : '';
		
		$total_topic += $row['nbr_topic'];
		$total_msg += $row['nbr_msg'];
		$Template->assign_block_vars('forums_list.subcats', array(
			'IMG_ANNOUNCE' => $img_announce,
			'NAME' => $row['name'],
			'DESC' => second_parse($row['subname']),
			'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
			'NBR_TOPIC' => $row['nbr_topic'],
			'NBR_MSG' => $row['nbr_msg'],
			'U_FORUM_URL' => $row['url'],
			'U_FORUM_VARS' => url('.php?id=' . $row['cid'], '-' . $row['cid'] . '+' . url_encode_rewrite($row['name']) . '.php'),
			'U_LAST_TOPIC' => $last				
		));
	}
}
$Sql->query_close($result);
if ($i > 0) 
{
	$Template->assign_block_vars('forums_list', array(
	));
	$Template->assign_block_vars('forums_list.endcats', array(
	));	
}
	

list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script LIKE '/forum/%'");

$Template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'NBR_MSG' => $total_msg,
	'NBR_TOPIC' => $total_topic,
	'TOTAL_ONLINE' => $total_online,	
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SID' => SID,
	'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
	'SELECT_CAT' => !empty($id_get) ? forum_list_cat($id_get, 0) : '', 
	'C_TOTAL_POST' => true,
	'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
	'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_FORUM' => $LANG['forum'],
	'L_TOPIC' => ($total_topic > 1) ? $LANG['topic_s'] : $LANG['topic'],
	'L_MESSAGE' => ($total_msg > 1) ? $LANG['message_s'] : $LANG['message'],
	'L_LAST_MESSAGE' => $LANG['last_message'],
	'L_STATS' => $LANG['stats'],
	'L_DISPLAY_UNREAD_MSG' => $LANG['show_not_reads'],
	'L_MARK_AS_READ' => $LANG['mark_as_read'],
	'L_TOTAL_POST' => $LANG['nbr_message'],
	'L_DISTRIBUTED' => strtolower($LANG['distributed']),
	'L_AND' => $LANG['and'],	
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$Template->pparse('forum_index');	

include('../kernel/footer.php');

?>
