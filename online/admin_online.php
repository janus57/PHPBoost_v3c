<?php



























require_once('../admin/admin_begin.php');
load_module_lang('online'); 
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']))
{
	$config_online = array();
	$config_online['online_displayed'] = retrieve(POST, 'online_displayed', 4);
	$config_online['display_order_online'] = retrieve(POST, 'display_order_online', 's.level, s.session_time DESC');
		
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_online)) . "' WHERE name = 'online'", __LINE__, __FILE__);
	
	###### Régénération du cache des online #######
	$Cache->Generate_module_file('online');
	
	redirect(HOST . SCRIPT);	
}

else	
{		
	$Template->set_filenames(array(
		'admin_online'=> 'online/admin_online.tpl'
	));
	
	$Cache->load('online');
	
	$Template->assign_vars(array(
		'NBR_ONLINE_DISPLAYED' => !empty($CONFIG_ONLINE['online_displayed']) ? $CONFIG_ONLINE['online_displayed'] : 4,
		'L_ONLINE_CONFIG' => $LANG['online_config'],
		'L_NBR_ONLINE_DISPLAYED' => $LANG['nbr_online_displayed'],
		'L_DISPLAY_ORDER' => $LANG['display_order_online'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$array_order_online = array(
		's.level DESC' => $LANG['ranks'], 
		's.session_time DESC' => $LANG['last_update'], 
		's.level DESC, s.session_time DESC' => $LANG['ranks'] . ' ' . $LANG['and'] . ' ' . $LANG['last_update']
	);
	foreach ($array_order_online as $key => $value)
	{
		$selected = ($CONFIG_ONLINE['display_order_online'] == $key) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('display_order', array(
			'ORDER' => '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>'
		));
	}

	$Template->pparse('admin_online'); 
}

require_once('../admin/admin_footer.php');

?>
