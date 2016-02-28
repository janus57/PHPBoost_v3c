<?php



























if (defined('PHPBOOST') !== true) exit;

function connect_mini($position, $block)
{
    global $User, $LANG, $CONFIG_USER, $CONTRIBUTION_PANEL_UNREAD, $ADMINISTRATOR_ALERTS, $Session;
    
    $tpl = new Template('connect/connect_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    if ($User->check_level(MEMBER_LEVEL)) 
    {
    	
    	
    	$contribution_number = 0;
    	
    	
    	if ($User->check_level(ADMIN_LEVEL))
    		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r2'];
    	elseif ($User->check_level(MODERATOR_LEVEL))
    		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r1'];
    	
    	else
    	{
    		
    		if ($CONTRIBUTION_PANEL_UNREAD['r0'] > 0)
    			$contribution_number = -1;
    		
    		
    		if ($contribution_number == 0)
    			if (!empty($CONTRIBUTION_PANEL_UNREAD['m' . $User->get_attribute('user_id')]) && $CONTRIBUTION_PANEL_UNREAD['m' . $User->get_attribute('user_id')] == 1)
    				$contribution_number = -1;
    		
    		
    		if ($contribution_number == 0)
    		{
    			foreach ($User->get_groups() as $id_group)
    			{
    				if (!empty($CONTRIBUTION_PANEL_UNREAD['g' . $id_group]) && $CONTRIBUTION_PANEL_UNREAD['g' . $id_group] == 1)
    				{
    					$contribution_number = -1;
    					break;
    				}
    			}
    		}
    	}
    
    	import('events/administrator_alert_service');
    	
    	$tpl->assign_vars(array(
    		'C_ADMIN_AUTH' => $User->check_level(ADMIN_LEVEL),
    		'C_MODERATOR_AUTH' => $User->check_level(MODERATOR_LEVEL),
    		'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
    		'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
    		'C_UNREAD_ALERT' => (bool)AdministratorAlertService::get_number_unread_alerts(),
    		'NUM_UNREAD_CONTRIBUTIONS' => $contribution_number,
    		'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
    		'IMG_PM' => $User->get_attribute('user_pm') > 0 ? 'new_pm.gif' : 'pm_mini.png',
    		'U_USER_PM' => TPL_PATH_TO_ROOT . '/member/pm' . url('.php?pm=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php'),
    		'U_USER_ID' => url('.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', '-' . $User->get_attribute('user_id') . '.php?view=1'),
    		'U_DISCONNECT' => HOST . DIR . '/member/member.php?disconnect=true&amp;token=' . $Session->get_token(),
    		'L_NBR_PM' => ($User->get_attribute('user_pm') > 0 ? ($User->get_attribute('user_pm') . ' ' . (($User->get_attribute('user_pm') > 1) ? $LANG['message_s'] : $LANG['message'])) : $LANG['private_messaging']),
    		'L_PROFIL' => $LANG['profile'],
    		'L_ADMIN_PANEL' => $LANG['admin_panel'],
    		'L_MODO_PANEL' => $LANG['modo_panel'],
    		'L_PRIVATE_PROFIL' => $LANG['my_private_profile'],
    		'L_DISCONNECT' => $LANG['disconnect'],
    		'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel']
    	));
    }
    else
    {
    	$tpl->assign_vars(array(
    		'C_USER_REGISTER' => (bool)$CONFIG_USER['activ_register'],
    		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_PASSWORD' => $LANG['require_password'],
			'L_CONNECT' => $LANG['connect'],
    		'L_PSEUDO' => $LANG['pseudo'],
    		'L_PASSWORD' => $LANG['password'],
    		'L_AUTOCONNECT' => $LANG['autoconnect'],
    		'L_FORGOT_PASS' => $LANG['forget_pass'],
    		'L_REGISTER' => $LANG['register'],
    		'U_CONNECT' => (QUERY_STRING != '') ? '?' . str_replace('&', '&amp;', QUERY_STRING) . '&amp;' : '',
    		'U_REGISTER' => TPL_PATH_TO_ROOT . '/member/register.php' . SID
    	));
    }
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>
