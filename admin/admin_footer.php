<?php




























########################admin_body_footer.tpl#######################

$Sql->close(); 

$Template->set_filenames(array(
	'admin_footer'=> 'admin/admin_footer.tpl'
));


$THEME = load_ini_file(PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());
	
$Template->assign_vars(array(
	'HOST' => HOST,
	'DIR' => DIR,
	'VERSION' => $CONFIG['version'],
	'THEME' => get_utheme(),
	'C_DISPLAY_AUTHOR_THEME' => ($CONFIG['theme_author'] ? true : false),
	'L_POWERED_BY' => $LANG['powered_by'],
	'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
	'L_THEME' => $LANG['theme'],
	'L_THEME_NAME' => $THEME['name'],
	'L_BY' => strtolower($LANG['by']),
	'L_THEME_AUTHOR' => $THEME['author'],
	'U_THEME_AUTHOR_LINK' => $THEME['author_link'],
    'PHPBOOST_VERSION' => $CONFIG['version']
));

if ($CONFIG['bench'])
{
	$Bench->stop(); 
	$Template->assign_vars(array(
		'C_DISPLAY_BENCH' => true,
		'BENCH' => $Bench->to_string(), 
		'REQ' => $Sql->get_executed_requests_number(),
		'L_UNIT_SECOND' => HOST,
		'L_REQ' => $LANG['sql_req'],
		'L_ACHIEVED' => $LANG['achieved'],
		'L_UNIT_SECOND' => $LANG['unit_seconds_short']
	));
}

$Template->pparse('admin_footer'); 

ob_end_flush();

?>
