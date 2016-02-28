<?php


























if (defined('PHPBOOST') !== true)	
	exit;

load_module_lang('download'); 
$Cache->load('download');

require_once('download_auth.php');

$page = retrieve(GET, 'p', 1);
$category_id = retrieve(GET, 'cat', 0);
$file_id = retrieve(GET, 'id', 0);
$id_cat_for_download = 0;

if (!empty($file_id))
{
	$download_info = $Sql->query_array(PREFIX . 'download', '*', "WHERE visible = 1 AND approved = 1 AND id = '" . $file_id . "'", __LINE__, __FILE__);
	
	if (empty($download_info['id']))
		$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
	$Bread_crumb->add($download_info['title'], url('download.php?id=' . $file_id, 'download-' . $file_id . '+' . url_encode_rewrite($download_info['title']) . '.php'));
	$id_cat_for_download = $download_info['idcat'];
	define('TITLE', $DOWNLOAD_LANG['title_download'] . ' - ' . $download_info['title']);
}
elseif (!empty($category_id))
{
	if (!array_key_exists($category_id, $DOWNLOAD_CATS))
		$Errorh->handler('e_unexist_category_download', E_USER_REDIRECT);
	
	$Bread_crumb->add($DOWNLOAD_LANG['title_download'] . ' - ' . $DOWNLOAD_CATS[$category_id]['name']);
	$id_cat_for_download = $category_id;
	define('TITLE', $DOWNLOAD_LANG['title_download'] . ' - ' . $DOWNLOAD_CATS[$category_id]['name']);
}
else
	define('TITLE', $DOWNLOAD_LANG['title_download']);

$l_com_note = !empty($idurl) ? (!empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '') ) : '';

$auth_read = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
$auth_write = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
$auth_contribution = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);


while ($id_cat_for_download > 0)
{
	$Bread_crumb->add($DOWNLOAD_CATS[$id_cat_for_download]['name'], url('download.php?cat=' . $id_cat_for_download, 'category-' . $id_cat_for_download . '+' . url_encode_rewrite($DOWNLOAD_CATS[$id_cat_for_download]['name']) . '.php'));
	$auth_read = $auth_read && $DOWNLOAD_CATS[$id_cat_for_download]['visible'];
	if (!empty($DOWNLOAD_CATS[$id_cat_for_download]['auth']))
	{
		
		$auth_read = $auth_read && $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
		$auth_write = $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
		$auth_contribution = $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);
	}
	$id_cat_for_download = (int)$DOWNLOAD_CATS[$id_cat_for_download]['id_parent'];
}

$Bread_crumb->add($DOWNLOAD_LANG['download'], url('download.php'));

$Bread_crumb->reverse();

if (!$auth_read)
	$Errorh->handler('e_auth', E_USER_REDIRECT);

?>
