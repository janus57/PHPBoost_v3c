<?php


























require_once('../kernel/begin.php'); 
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$alert = retrieve(GET, 'id', 0);	
$alert_post = retrieve(POST, 'id', 0);	
$topic_id = !empty($alert) ? $alert : $alert_post;
$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $topic_id . "'", __LINE__, __FILE__);

$cat_name = !empty($CAT_FORUM[$topic['idcat']]['name']) ? $CAT_FORUM[$topic['idcat']]['name'] : '';
$topic_name = !empty($topic['title']) ? $topic['title'] : '';
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->add($cat_name, 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . url_encode_rewrite($cat_name) . '.php'));
$Bread_crumb->add($topic['title'], 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'));
$Bread_crumb->add($LANG['alert_topic'], '');

define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['alert_topic']);
require_once('../kernel/header.php');

if (empty($alert) && empty($alert_post) || empty($topic['idcat'])) 
	redirect(HOST . DIR . '/forum/index' . url('.php'));  

if (!$User->check_level(MEMBER_LEVEL)) 
    $Errorh->handler('e_auth', E_USER_REDIRECT); 
	
$Template->set_filenames(array(
	'forum_alert'=> 'forum/forum_alert.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));
	

if (!empty($alert) && empty($alert_post))
{
	
	$nbr_alert = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_alerts WHERE idtopic = '" . $alert ."' AND status = 0", __LINE__, __FILE__);
	if (empty($nbr_alert)) 
	{
		$Template->assign_vars(array(
			'KERNEL_EDITOR' => display_editor(),
			'L_ALERT' => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN' => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE' => $LANG['alert_title'],
			'L_ALERT_CONTENTS' => $LANG['alert_contents'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title']
		));
		
		$Template->assign_block_vars('alert_form', array(
			'TITLE' => $topic_name,
			'U_TOPIC' => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'),
			'ID_ALERT' => $alert,
		));	
	}
	else 
	{
		$Template->assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back'],
			'URL_TOPIC' => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php')
		));	
		
		$Template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}


if (!empty($alert_post))
{
	$Template->assign_vars(array(
		'L_ALERT' => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back'],
		'URL_TOPIC' => 'topic' . url('.php?id=' . $alert_post, '-' . $alert_post . '-' . url_encode_rewrite($topic_name) . '.php')
	));
	
	
	$nbr_alert = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_alerts WHERE idtopic = '" . $alert_post ."' AND status = 0", __LINE__, __FILE__);
	if (empty($nbr_alert)) 
	{
		$alert_title = retrieve(POST, 'title', '');
		$alert_contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		
		
		include_once('../forum/forum.class.php');
		$Forumfct = new Forum;

		$Forumfct->Alert_topic($alert_post, $alert_title, $alert_contents);
		
		$Template->assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
	}
	else 
	{
		$Template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
	
}


list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '/forum/%'");
	
$Template->assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['alert_topic'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
	'DESC' => $topic['subtitle'],
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php') . '">' . $CAT_FORUM[$topic['idcat']]['name'] . '</a>',
	'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $topic_id, '-' . $topic_id . '.php') . '">' . $topic['title'] . '</a>',
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_SUBMIT' => $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$Template->pparse('forum_alert');	

include('../kernel/footer.php');

?>
