<?php



























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Template->set_filenames(array(
	'admin_extend'=> 'admin/admin_extend.tpl'
));

$Template->assign_vars(array(
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'L_CONFIGURATION' => $LANG['configuration'],
	'L_INDEX_SITE' => $LANG['site'],
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_USER' => $LANG['member'],
	'L_EXTEND_FIELD' => $LANG['extend_field'],
	'L_RANKS' => $LANG['ranks'],
	'L_GROUP' => $LANG['group'],
	'L_THEME' => $LANG['themes'],
	'L_SMILEY' => $LANG['smile'],
	'L_ROBOTS' => $LANG['robots'],	
	'L_ERRORS' => $LANG['errors'],
	'L_COM' => $LANG['com'],
	'L_UPDATER' => $LANG['updater'],
	'L_MODULES' => $LANG['modules'],
	'L_CACHE' => $LANG['cache'],
	'U_INDEX_SITE' => ((substr($CONFIG['start_page'], 0, 1) == '/') ? '..' . $CONFIG['start_page'] : $CONFIG['start_page']) ,
    'L_WEBSITE_UPDATES' => $LANG['website_updates']
));


$i = 1;
$nbr_modules = count($modules_config);
foreach ($modules_config as $module_name => $auth)
{
	if (is_array($modules_config[$module_name]))
	{
		$name = $modules_config[$module_name]['module_name'];
		if (!empty($modules_config[$module_name]['admin']))
		{
			$Template->assign_block_vars('modules_extend', array(
				'NAME' => $module_name,
				'IMG' => '../' . $name . '/' . $name . '.png',
				'START_TR' => ((($i - 1) % 5) == 0 || $i == 1)? '<tr style="text-align:center;">' : '',
				'END_TR' => ((($i % 5) == 0 && $i != 1) || $i == $nbr_modules ) ? '</tr>' : '',			
				'U_ADMIN_MODULE' => '../' . $name . '/admin_' . $name . '.php'
			));			
			$i++;
		}
	}
}

if ($i != 0)
{
	$i--;
	while (($i % 5) != 0)
	{
		$Template->assign_block_vars('modules_extend.td', array(
			'TD' => '<td class="row2" style="width:20%;">&nbsp;</td>'
		));	
		$i++;
	}
}

$Template->pparse('admin_extend'); 

require_once('../admin/admin_footer.php');

?>
