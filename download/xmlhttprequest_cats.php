<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if ($User->check_level(ADMIN_LEVEL)) 
{	
	include_once('download_cats.class.php');
	$download_categories = new DownloadCats();
	
	$id_up = retrieve(GET, 'id_up', 0);
	$id_down = retrieve(GET, 'id_down', 0);
	$id_show = retrieve(GET, 'show', 0);
	$id_hide = retrieve(GET, 'hide', 0);
	$cat_to_del = retrieve(GET, 'del', 0);
	
	$result = false;
	
	if ($id_up > 0)
		$result = $download_categories->move($id_up, MOVE_CATEGORY_UP);
	elseif ($id_down > 0)
		$result = $download_categories->move($id_down, MOVE_CATEGORY_DOWN);
	elseif ($id_show > 0)
		$result = $download_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	elseif ($id_hide > 0)
		$result = $download_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	
	
	if ($result)
	{	
		$cat_config = array(
			'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
			'administration_file_name' => 'admin_download_cat.php',
			'url' => array(
				'unrewrited' => 'download.php?id=%d',
				'rewrited' => 'category-%d+%s.php'),
			);
		
		$download_categories->set_display_config($cat_config);
		
		$Cache->load('download', RELOAD_CACHE);
	
		echo $download_categories->build_administration_interface(AJAX_MODE);
	}
}
include_once('../kernel/footer_no_display.php');

?>
