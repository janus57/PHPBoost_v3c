<?php


























if (defined('PHPBOOST') !== true) exit;

function guestbook_mini($position, $block)
{
    global $LANG, $Cache, $_guestbook_rand_msg;
    
    
    if (strpos(SCRIPT, '/guestbook/guestbook.php') === false)
    {
    	load_module_lang('guestbook');
    	$Cache->load('guestbook'); 
    	
    	###########################Affichage##############################
    	$tpl = new Template('guestbook/guestbook_mini.tpl');
        import('core/menu_service');
        MenuService::assign_positions_conditions($tpl, $block);

		$rand = array_rand($_guestbook_rand_msg);
    	$guestbook_rand = isset($_guestbook_rand_msg[$rand]) ? $_guestbook_rand_msg[$rand] : array();
		
		if ($guestbook_rand === array())
		{
			$tpl->assign_vars(array(
	    		'C_ANY_MESSAGE_GESTBOOK' => false,
				'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
				'L_NO_MESSAGE_GESTBOOK' => $LANG['no_message_guestbook']
	    	));
		}
		else
		{
	    	
	    	if ($guestbook_rand['user_id'] != -1)
	    		$guestbook_login = '<a class="small_link" href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $guestbook_rand['user_id'], '-' . $guestbook_rand['user_id'] . '.php') . '" title="' . $guestbook_rand['login'] . '"><span style="font-weight:bold;">' . wordwrap_html($guestbook_rand['login'], 13) . '</span></a>';
	    	else
	    		$guestbook_login = '<span style="font-style:italic;">' . (!empty($guestbook_rand['login']) ? wordwrap_html($guestbook_rand['login'], 13) : $LANG['guest']) . '</span>';
	    	
	    	$tpl->assign_vars(array(
				'C_ANY_MESSAGE_GESTBOOK' => true,
				'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
	    		'RAND_MSG_ID' => $guestbook_rand['id'],
	    		'RAND_MSG_CONTENTS' => (strlen($guestbook_rand['contents']) > 149) ? ucfirst($guestbook_rand['contents']) . ' <a href="' . TPL_PATH_TO_ROOT . '/guestbook/guestbook.php" class="small_link">' . $LANG['guestbook_more_contents'] . '</a>' : ucfirst($guestbook_rand['contents']),
	    		'RAND_MSG_LOGIN' => $guestbook_login,
	    		'L_BY' => $LANG['by']
	    	));
		}
		return $tpl->parse(TEMPLATE_STRING_MODE);
    }
	return '';
}

?>
