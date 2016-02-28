<?php


























if( !defined('PHPBOOST') ) 
{
	exit;
}

$Template->Set_filenames(array('admin_media_menu'=> 'media/admin_media_menu.tpl'));

$Template->Assign_vars(array(
	'L_MANAGEMENT_MEDIA' => $MEDIA_LANG['management_media'],
	'L_CONFIGURATION' => $MEDIA_LANG['configuration'],
	'L_MANAGEMENT_CAT' => $MEDIA_LANG['management_cat'],
	'L_ADD_CAT' => $MEDIA_LANG['add_cat'],
	'L_LIST_MEDIA' => $MEDIA_LANG['list_media'],
	'L_ADD_MEDIA' => $MEDIA_LANG['add_media']
));

?>
