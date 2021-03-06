<?php


























if (defined('PHPBOOST') !== true)	exit;

function online_mini($position, $block)
{
    if (strpos(SCRIPT, '/online/online.php') === false)
    {
        global $LANG, $Cache, $Sql, $CONFIG, $CONFIG_ONLINE;
        
    	
    	load_module_lang('online');
    	$Cache->load('online');
    	
    	$tpl = new Template('online/online_mini.tpl');
        import('core/menu_service');
        MenuService::assign_positions_conditions($tpl, $block);
    
    	
    	list($count_visit, $count_member, $count_modo, $count_admin) = array(0, 0, 0, 0);
    
    	$i = 0;
    	$array_class = array('member', 'modo', 'admin');
    	$result = $Sql->query_while("SELECT s.user_id, s.level, s.session_time, m.user_groups, m.login
    	FROM " . DB_TABLE_SESSIONS . " s
    	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
    	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
    	ORDER BY " . $CONFIG_ONLINE['display_order_online'], __LINE__, __FILE__); 
    	while ($row = $Sql->fetch_assoc($result))
    	{
    		if ($i < $CONFIG_ONLINE['online_displayed'])
    		{
    			
    			if ($row['level'] !== '-1')
    			{
    				$group_color = User::get_group_color($row['user_groups'], $row['level']);
					$tpl->assign_block_vars('online', array(
    					'USER' => '<a href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . wordwrap_html($row['login'], 19) . '</a><br />'
    				));
    				$i++;
    			}
    		}
    		
    		switch ($row['level'])
    		{
    			case '-1':
    			$count_visit++;
    			break;
    			case '0':
    			$count_member++;
    			break;
    			case '1':
    			$count_modo++;
    			break;
    			case '2':
    			$count_admin++;
    			break;
    		}
    	}
    	$Sql->query_close($result);
    
    
    	$count_visit = (empty($count_visit) && empty($count_member) && empty($count_modo) && empty($count_admin)) ? '1' : $count_visit;
    
    	$total = $count_visit + $count_member + $count_modo + $count_admin;
    	$total_member = $count_member + $count_modo + $count_admin;
    
    	$member_online = $LANG['member_s'] . ' ' . strtolower($LANG['online']);
    	$more = '<br /><a href="../online/online.php' . SID . '" title="' . $member_online . '">' . $member_online . '</a><br />';
    	$more = ($total_member > $CONFIG_ONLINE['online_displayed']) ? $more : ''; 
    
    	$l_guest = ($count_visit > 1) ? $LANG['guest_s'] : $LANG['guest'];
    	$l_member = ($count_member > 1) ? $LANG['member_s'] : $LANG['member'];
    	$l_modo = ($count_modo > 1) ? $LANG['modo_s'] : $LANG['modo'];
    	$l_admin = ($count_admin > 1) ? $LANG['admin_s'] : $LANG['admin'];
    
    	$tpl->assign_vars(array(
    		'VISIT' => $count_visit,
    		'USER' => $count_member,
    		'MODO' => $count_modo,
    		'ADMIN' => $count_admin,
    		'MORE' => $more,
    		'TOTAL' => $total,
    		'L_VISITOR' => $l_guest,
    		'L_USER' => $l_member,
    		'L_MODO' => $l_modo,
    		'L_ADMIN' => $l_admin,
    		'L_ONLINE' => $LANG['online'],
    		'L_TOTAL' => $LANG['total']
    	));
		return $tpl->parse(TEMPLATE_STRING_MODE);
    }
	
    return '';
}

?>
