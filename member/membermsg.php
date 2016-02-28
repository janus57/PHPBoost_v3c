<?php


























require_once('../kernel/begin.php'); 
$Bread_crumb->add($LANG['member_area'], 'member.php' . SID);
$Bread_crumb->add($LANG['member_msg'], 'membermsg.php' . SID);
define('TITLE', $LANG['member_msg']);
require_once('../kernel/header.php'); 

$memberId = retrieve(GET, 'id', 0, TUNSIGNED_INT);
$script = retrieve(GET, 'script', '');

if (!empty($memberId)) 
{
	$Template->set_filenames(array(
		'membermsg'=> 'member/membermsg.tpl',
	));
	
	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$modules = $modulesLoader->get_available_modules('get_member_msg_link');
	foreach ($modules as $module)
	{
		$img = $module->functionality('get_member_msg_img');
		$Template->assign_block_vars('available_modules_msg', array(
			'NAME_USER_MSG' => $module->functionality('get_member_msg_name'),
			'IMG_USER_MSG' => $img,
			'C_IMG_USER_MSG' => !empty($img) ? true : false,
			'U_LINK_USER_MSG' => $module->functionality('get_member_msg_link', array($memberId))
		));
	}
	
	$Template->assign_vars(array(
		'L_USER_MSG' => $LANG['member_msg'],
		'L_USER_MSG_DISPLAY' => $LANG['member_msg_display'],
		'L_COMMENTS' => $LANG['com_s'],
		'L_BACK' => $LANG['back'],
		'U_BACK' => url('.php?id=' . $memberId, '-' . $memberId . '.php'),
		'U_USER_MSG' => url('.php?id=' . $memberId),
		'U_COMMENTS' => url('.php?id=' . $memberId . '&amp;script=com')
	));
		
	if (!empty($script))
	{
		
		import('util/pagination'); 
		$Pagination = new Pagination();

		$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_COM . " WHERE user_id = '" . $memberId . "'", __LINE__, __FILE__);
		$Template->assign_vars(array(
			'C_START_MSG' => true,
			'PAGINATION' => $Pagination->display('membermsg.php?pmsg=%d', $nbr_msg, 'pmsg', 25, 3),
			'L_GO_MSG' => $LANG['go_msg'],
			'L_ON' => $LANG['on']
		));

		$result = $Sql->query_while("SELECT c.timestamp, c.script, c.path, m.login, s.user_id AS connect, c.contents
		FROM " . DB_TABLE_COM . " c
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
		WHERE m.user_id = '" . $memberId . "'
		ORDER BY c.timestamp DESC 
		" . $Sql->limit($Pagination->get_first_msg(25, 'pmsg'), 25), __LINE__, __FILE__);
		$row = $Sql->fetch_assoc($result);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('msg_list', array(
				'USER_PSEUDO' => '<a class="msg_link_pseudo" href="../member/member' . url('.php?id=' . $memberId, '-' . $memberId . '.php') . '"><span class="text_strong">' . wordwrap_html($row['login'], 13) . '</span></a>',
				'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . (!empty($row['connect']) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'CONTENTS' => ucfirst(second_parse($row['contents'])),
				'U_TITLE' => url($row['path'] . '#' . $row['script'])
			));
		}
	}
	
	$Template->pparse('membermsg');
}
else
	redirect(HOST . DIR . '/member/member.php');

require_once('../kernel/footer.php');

?>
