<?php



























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');


if (!empty($_POST['add']))
{
	$name = retrieve(POST, 'name', '');
	$msg = retrieve(POST, 'msg', 0);    
	$icon = retrieve(POST, 'icon', ''); 
	$icon = retrieve(POST, 'icon', ''); 
	
	if (!empty($name) && $msg >= 0)
	{	
		
		$Sql->query_inject("INSERT INTO " . DB_TABLE_RANKS . " (name,msg,icon,special) 
		VALUES('" . $name . "', '" . $msg . "', '" . $icon . "', '0')", __LINE__, __FILE__);	
				
		###### Régénération du cache des rangs #######
		$Cache->Generate_file('ranks');
		
		redirect(HOST . DIR . '/admin/admin_ranks.php');	
	}
	else
		redirect(HOST . DIR . '/admin/admin_ranks_add.php?error=incomplete#errorh');
}
elseif (!empty($_FILES['upload_ranks']['name'])) 
{
	
	@clearstatcache();
	$dir = PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks/';
	if (!is_writable($dir))
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if (is_writable($dir)) 
	{
		import('io/upload');
		$Upload = new Upload($dir);
		if (!$Upload->file('upload_ranks', '`([a-z0-9_ -])+\.(jpg|gif|png|bmp)+$`i'))
			$error = $Upload->error;
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	redirect(HOST . SCRIPT . $error);	
}
else 
{	
	$Template->set_filenames(array(
		'admin_ranks_add'=> 'admin/admin_ranks_add.tpl'
	));

	
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	
	$rank_options = '<option value="">--</option>';
	
	import('io/filesystem/folder');
	$image_folder_path = new Folder(PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
	{
		$file = $image->get_name();
		$rank_options .= '<option value="' . PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks/' . $file . '">' . $file . '</option>';
	}
	
	$Template->assign_vars(array(
		'RANK_OPTIONS' => $rank_options,
		'L_REQUIRE_RANK_NAME' => $LANG['require_rank_name'],
		'L_REQUIRE_NBR_MSG_RANK' => $LANG['require_nbr_msg_rank'],
		'L_CONFIRM_DEL_RANK' => $LANG['confirm_del_rank'],
		'L_RANKS_MANAGEMENT' => $LANG['rank_management'],
		'L_ADD_RANKS' => $LANG['rank_add'],
		'L_UPLOAD_RANKS' => $LANG['upload_rank'],
		'L_UPLOAD_FORMAT' => $LANG['upload_rank_format'],
		'L_UPLOAD' => $LANG['upload'],
		'L_RANK_NAME' => $LANG['rank_name'],
		'L_NBR_MSG' => $LANG['nbr_msg'],
		'L_IMG_ASSOC' => $LANG['img_assoc'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));

	$Template->pparse('admin_ranks_add');
}

require_once('../admin/admin_footer.php');

?>
