<?php


























if (defined('PHPBOOST') !== true) exit;

function shoutbox_mini($position, $block)
{
    global $Cache, $LANG, $User, $CONFIG_SHOUTBOX, $nbr_members, $last_member_id, $last_member_login, $Sql;
    
    
    if (strpos(SCRIPT, '/shoutbox/shoutbox.php') === false)
    {
    	load_module_lang('shoutbox');
    	$Cache->load('shoutbox'); 
    	
    	###########################Insertion##############################
    	$shoutbox = retrieve(POST, 'shoutbox', false);
    	if ($shoutbox)
    	{
    		
    		if ($User->get_attribute('user_readonly') > time())
    			$Errorh->handler('e_readonly', E_USER_REDIRECT);
    			
    		$shout_pseudo = substr(retrieve(POST, 'shout_pseudo', $LANG['guest']), 0, 25); 
    		$shout_contents = retrieve(POST, 'shout_contents', '', TSTRING_UNCHANGE);
    		if (!empty($shout_pseudo) && !empty($shout_contents))
    		{
    			
    			if ($User->check_level($CONFIG_SHOUTBOX['shoutbox_auth']))
    			{
    				
    				$check_time = ($User->get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
    				if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
    				{
    					if ($check_time >= (time() - $CONFIG['delay_flood']))
    						redirect(HOST . DIR . '/shoutbox/shoutbox.php' . url('?error=flood', '', '&'));
    				}
    				
    				
    				$shout_contents = strparse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
    				if (!check_nbr_links($shout_pseudo, 0)) 
    					redirect(HOST . DIR . '/shoutbox/shoutbox.php' . url('?error=lp_flood', '', '&'));
    				if (!check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link'])) 
    					redirect(HOST . DIR . '/shoutbox/shoutbox.php' . url('?error=l_flood', '', '&'));
    					
    				$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES ('" . $shout_pseudo . "', '" . $User->get_attribute('user_id') . "', '" . $User->get_attribute('level') . "', '" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
    				
    				redirect(HOST . url(SCRIPT . '?' . QUERY_STRING, '', '&'));
    			}
    			else 
    				redirect(HOST . DIR . '/shoutbox/shoutbox.php' . url('?error=auth', '', '&'));
    		}
    	}
    	
    	###########################Affichage##############################
    	$tpl = new Template('shoutbox/shoutbox_mini.tpl');
        import('core/menu_service');
        MenuService::assign_positions_conditions($tpl, $block);
    
    	
    	if ($User->get_attribute('user_id') !== -1)
    		$tpl->assign_vars(array(
    			'SHOUTBOX_PSEUDO' => $User->get_attribute('login'),
    			'C_HIDDEN_SHOUT' => true
    		));
    	else
    		$tpl->assign_vars(array(
    			'SHOUTBOX_PSEUDO' => $LANG['guest'],
    			'C_VISIBLE_SHOUT' => true
    		));
    	
		$refresh_delay = empty($CONFIG_SHOUTBOX['shoutbox_refresh_delay']) ? 60 : $CONFIG_SHOUTBOX['shoutbox_refresh_delay'];
    	$tpl->assign_vars(array(
    		'SID' => SID,
    		'SHOUT_REFRESH_DELAY' => (int)max($refresh_delay, 0),
    		'L_ALERT_TEXT' => $LANG['require_text'],
    		'L_ALERT_UNAUTH_POST' => $LANG['e_unauthorized'],
    		'L_ALERT_FLOOD' => $LANG['e_flood'],
    		'L_ALERT_LINK_FLOOD' => sprintf($LANG['e_l_flood'], $CONFIG_SHOUTBOX['shoutbox_max_link']),
    		'L_ALERT_LINK_PSEUDO' => $LANG['e_link_pseudo'],
    		'L_ALERT_INCOMPLETE' => $LANG['e_incomplete'],
    		'L_ALERT_READONLY' => $LANG['e_readonly'],
    		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
    		'L_SHOUTBOX' => $LANG['title_shoutbox'],
    		'L_MESSAGE' => $LANG['message'],
    		'L_PSEUDO' => $LANG['pseudo'],
    		'L_SUBMIT' => $LANG['submit'],
    		'L_REFRESH' => $LANG['refresh'],
    		'L_ARCHIVES' => $LANG['archives']
    	));
    	
    	$array_class = array('member', 'modo', 'admin');
    	$result = $Sql->query_while("SELECT id, login, user_id, level, contents
    	FROM " . PREFIX . "shoutbox
    	ORDER BY timestamp DESC
    	" . $Sql->limit(0, 25), __LINE__, __FILE__);
    	while ($row = $Sql->fetch_assoc($result))
    	{
    		$row['user_id'] = (int)$row['user_id'];
    		if ($User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
    			$del_message = '<script type="text/javascript"><!--
    			document.write(\'<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="' . TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a>\');
    			--></script><ins><noscript><p><a href="' . TPL_PATH_TO_ROOT . '/shoutbox/shoutbox' . url('.php?del=true&amp;id=' . $row['id']) . '"><img src="' . TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a></p></noscript></ins>';
    		else
    			$del_message = '';
    	
    		if ($row['user_id'] !== -1)
    			$row['login'] = $del_message . ' <a style="font-size:10px;" class="' . $array_class[$row['level']] . '" href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
    		else
    			$row['login'] = $del_message . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
    		
    		$tpl->assign_block_vars('shout', array(
    			'IDMSG' => $row['id'],
    			'PSEUDO' => $row['login'],
    			'CONTENTS' => ucfirst(second_parse($row['contents'])) 
    		));
    	}
    	$Sql->query_close($result);
    	
    	return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    return '';
}

?>
