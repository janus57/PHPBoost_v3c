<?php



























require_once('../admin/admin_begin.php');
load_module_lang('calendar'); 
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

##########################admin_calendar.tpl###########################
if (!empty($_POST['valid']) )
{
	$config_calendar = array();
	$config_calendar['calendar_auth'] = retrieve(POST, 'calendar_auth', -1);
		
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_calendar)) . "' WHERE name = 'calendar'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('calendar');
	
	redirect(HOST . SCRIPT);	
}

else	
{		
	$Template->set_filenames(array(
		'admin_calendar_config'=> 'calendar/admin_calendar_config.tpl'
	));
	
	$Cache->load('calendar');
	
	$Template->assign_vars(array(
		'L_REQUIRE' => $LANG['require'],	
		'L_CALENDAR' => $LANG['title_calendar'],
		'L_CALENDAR_CONFIG' => $LANG['calendar_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_ERASE' => $LANG['erase'],
	));
	
	$CONFIG_CALENDAR['calendar_auth'] = isset($CONFIG_CALENDAR['calendar_auth']) ? $CONFIG_CALENDAR['calendar_auth'] : '-1';	
	
	for ($i = -1; $i <= 2; $i++)
	{
		switch ($i) 
		{	
			case -1:
				$rank = $LANG['guest'];
			break;				
			case 0:
				$rank = $LANG['member'];
			break;				
			case 1: 
				$rank = $LANG['modo'];
			break;		
			case 2:
				$rank = $LANG['admin'];
			break;					
			default: -1;
		} 

		$selected = ($CONFIG_CALENDAR['calendar_auth'] == $i) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	}
		
	$Template->pparse('admin_calendar_config');
}

require_once('../admin/admin_footer.php');

?>
