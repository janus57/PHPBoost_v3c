<?php



























if (defined('PHPBOOST') !== true) exit;

function stats_mini($position, $block)
{
    global $LANG, $Cache, $nbr_members, $last_member_id, $last_member_login;
    
    load_module_lang('stats');
    
    #########################Stats.tpl###########################
    $tpl = new Template('stats/stats_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $Cache->load('stats');
    $l_member_registered = ($nbr_members > 1) ? $LANG['member_registered_s'] : $LANG['member_registered'];
    
    $tpl->assign_vars(array(
    	'SID' => SID,
    	'L_STATS' => $LANG['stats'],
    	'L_MORE_STAT' => $LANG['more_stats'],
    	'L_USER_REGISTERED' => sprintf($l_member_registered, $nbr_members),
    	'L_LAST_REGISTERED_USER' => $LANG['last_member'],
    	'U_LINK_LAST_USER' => '<a href="' . HOST . DIR . '/member/member' . url('.php?id=' . $last_member_id, '-' . $last_member_id  . '.php') . '">' . $last_member_login . '</a>'
    ));
    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>
