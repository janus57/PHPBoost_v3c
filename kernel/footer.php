<?php


























if(defined('PHPBOOST')!==true)
{
exit;
}
global $Sql,$Template,$MENUS,$LANG,$THEME,$CONFIG,$Bench;

$Sql->close();

$Template->set_filenames(array(
'footer'=>'footer.tpl'
));

$Template->assign_vars(array(
'HOST'=>HOST,
'DIR'=>DIR,
'THEME'=>get_utheme(),
'C_MENUS_BOTTOM_CENTRAL_CONTENT'=>!empty($MENUS[BLOCK_POSITION__BOTTOM_CENTRAL]),
'MENUS_BOTTOMCENTRAL_CONTENT'=>$MENUS[BLOCK_POSITION__BOTTOM_CENTRAL],
'C_MENUS_TOP_FOOTER_CONTENT'=>!empty($MENUS[BLOCK_POSITION__TOP_FOOTER]),
'MENUS_TOP_FOOTER_CONTENT'=>$MENUS[BLOCK_POSITION__TOP_FOOTER],
'C_MENUS_FOOTER_CONTENT'=>!empty($MENUS[BLOCK_POSITION__FOOTER]),
'MENUS_FOOTER_CONTENT'=>$MENUS[BLOCK_POSITION__FOOTER],
'C_DISPLAY_AUTHOR_THEME'=>($CONFIG['theme_author']?true:false),
'L_POWERED_BY'=>$LANG['powered_by'],
'L_PHPBOOST_RIGHT'=>$LANG['phpboost_right'],
'L_THEME'=>$LANG['theme'],
'L_THEME_NAME'=>$THEME['name'],
'L_BY'=>strtolower($LANG['by']),
'L_THEME_AUTHOR'=>$THEME['author'],
'U_THEME_AUTHOR_LINK'=>$THEME['author_link'],
'PHPBOOST_VERSION'=>$CONFIG['version']
));


pages_displayed();

if($CONFIG['bench'])
{
$Bench->stop();
$Template->assign_vars(array(
'C_DISPLAY_BENCH'=>true,
'BENCH'=>$Bench->to_string(),
'REQ'=>$Sql->get_executed_requests_number(),
'L_REQ'=>$LANG['sql_req'],
'L_ACHIEVED'=>$LANG['achieved'],
'L_UNIT_SECOND'=>$LANG['unit_seconds_short']
));
}

$Template->pparse('footer');

ob_end_flush();

?>