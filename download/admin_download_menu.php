<?php


























if (!defined('PHPBOOST')) exit;

$Template->set_filenames(array(
	'admin_download_menu'=> 'download/admin_download_menu.tpl'
));

$Template->assign_vars(array(
	'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
	'L_CATS_MANAGEMENT' => $LANG['cat_management'],
	'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
	'L_ADD_CATEGORY' => $DOWNLOAD_LANG['add_category'],
	'L_FILE_LIST' => $DOWNLOAD_LANG['file_list'],
	'L_ADD_FILE' => $DOWNLOAD_LANG['add_file'],
	'U_DOWNLOAD_CONFIG' => url('admin_download_config.php'),
	'U_DOWNLOAD_CATS_MANAGEMENT' => url('admin_download_cat.php'),
	'U_DOWNLOAD_ADD_CAT' => url('admin_download_cat.php?new=1'),
	'U_FILES_LIST' => url('admin_download.php'),
	'U_ADD_FILE' => url('management.php?new=1')
));

?>
