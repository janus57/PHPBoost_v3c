<?php


























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Cache->load('member');

if (!empty($_POST['msg_register'])) 
{
	$config_member['activ_register'] = isset($CONFIG_USER['activ_register']) ? numeric($CONFIG_USER['activ_register']) : 0;
	$config_member['activ_mbr'] = isset($CONFIG_USER['activ_mbr']) ? numeric($CONFIG_USER['activ_mbr']) : 0; 
	$config_member['verif_code'] = isset($CONFIG_USER['verif_code']) ? numeric($CONFIG_USER['verif_code']) : 0; 
	$config_member['delay_unactiv_max'] = isset($CONFIG_USER['delay_unactiv_max']) ? numeric($CONFIG_USER['delay_unactiv_max']) : '';
	$config_member['force_theme'] = isset($CONFIG_USER['force_theme']) ? numeric($CONFIG_USER['force_theme']) : 0; 
	$config_member['activ_up_avatar'] = isset($CONFIG_USER['activ_up_avatar']) ? numeric($CONFIG_USER['activ_up_avatar']) : 0; 
	$config_member['width_max'] = isset($CONFIG_USER['width_max']) ? numeric($CONFIG_USER['width_max']) : 120;
	$config_member['height_max'] = isset($CONFIG_USER['height_max']) ? numeric($CONFIG_USER['height_max']) : 120;
	$config_member['weight_max'] = isset($CONFIG_USER['weight_max']) ? numeric($CONFIG_USER['weight_max']) : 20;
	$config_member['activ_avatar'] = isset($CONFIG_USER['activ_avatar']) ? numeric($CONFIG_USER['activ_avatar']) : 0;
	$config_member['avatar_url'] = isset($CONFIG_USER['avatar_url']) ? $CONFIG_USER['avatar_url'] : 0;
	$config_member['msg_mbr'] = isset($CONFIG_USER['msg_mbr']) ? $CONFIG_USER['msg_mbr'] : '';
	
	$config_member['msg_register'] = stripslashes(strparse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED)));
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_member)) . "' WHERE name = 'member'", __LINE__, __FILE__); 
	
	###### R�g�n�ration du cache $CONFIG_USER #######
	$Cache->Generate_file('member');
		
	redirect(HOST . SCRIPT); 	
}
else
{			
	$Template->set_filenames(array(
		'admin_terms'=> 'admin/admin_terms.tpl'
	));
	
	$Template->assign_vars(array(
		'L_TERMS' => $LANG['register_terms'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
	));
	
	$msg_register = $Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'member'", __LINE__, __FILE__); 
	
	$Template->assign_vars(array(
		'CONTENTS' => unparse($CONFIG_USER['msg_register']),
		'KERNEL_EDITOR' => display_editor(),
		'L_TERMS' => $LANG['register_terms'],
		'L_EXPLAIN_TERMS' => $LANG['explain_terms'],
		'L_CONTENTS' => $LANG['content'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));		
	
	$Template->pparse('admin_terms'); 
}

require_once('../admin/admin_footer.php');

?>
