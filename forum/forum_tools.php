<?php


























if (defined('PHPBOOST') !== true)	
	exit;

############### Header du forum ################
$Template->set_filenames(array(
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

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

	
	$clause_topic = '';
	if (strpos(SCRIPT, '/forum/topic.php') !== false)
	{
		$id_get = retrieve(GET, 'id', 0);
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}
	
	
	$nbr_msg_not_read = $Sql->query("SELECT COUNT(*)
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
	LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = '" . $User->get_attribute('user_id') . "'
	WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . $unauth_cats, __LINE__, __FILE__);
}


if ($CONFIG_FORUM['display_connexion'])
{
	$Template->assign_vars(array(	
		'C_FORUM_CONNEXION' => true,
		'L_CONNECT' => $LANG['connect'],
		'L_DISCONNECT' => $LANG['disconnect'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'L_REGISTER' => $LANG['register']
	));
}

$sid = (SID != '' ? '?' . SID : '');
$Template->assign_vars(array(	
	'C_DISPLAY_UNREAD_DETAILS' => ($User->get_attribute('user_id') !== -1) ? true : false,
	'C_MODERATION_PANEL' => $User->check_level(1) ? true : false,
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' . $sid . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . $sid . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . $sid  . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . ($User->get_attribute('user_id') !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . url('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_AUTH_ERROR' => $LANG['e_auth'],
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search']
));

?>
