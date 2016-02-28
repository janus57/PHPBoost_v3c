<?php


























require_once('../kernel/begin.php');

require_once('download_auth.php');

$idurl = retrieve(GET, 'id', 0);

if (!empty($idurl))
{
	$Sql->query_inject("UPDATE " . PREFIX . "download SET count = count + 1 WHERE id = '" . $idurl . "'", __LINE__, __FILE__); 
	$info_file = $Sql->query_array(PREFIX . "download", "url", "force_download", "idcat", "size", "WHERE id = '" . $idurl . "'", __LINE__, __FILE__);

	
	$Cache->load('download');
	
    $auth_read = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
    $id_cat_for_download = (int)$info_file['idcat'];
    
    
    while ($id_cat_for_download > 0)
    {
    	$Bread_crumb->add($DOWNLOAD_CATS[$id_cat_for_download]['name'], url('download.php?cat=' . $id_cat_for_download, 'category-' . $id_cat_for_download . '+' . url_encode_rewrite($DOWNLOAD_CATS[$id_cat_for_download]['name']) . '.php'));
    	if (!empty($DOWNLOAD_CATS[$id_cat_for_download]['auth']))
    	{
    		
    		$auth_read = $auth_read && $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
    	}
    	$id_cat_for_download = (int)$DOWNLOAD_CATS[$id_cat_for_download]['id_parent'];
    }
    
    
    if (!$auth_read)
        $Errorh->handler('e_auth', E_USER_REDIRECT);
    
	if (empty($info_file['url']))
		$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
    
	
	if ($info_file['force_download'] == DOWNLOAD_FORCE_DL && strpos($info_file['url'], '://') === false)	
	{
		$info_file['url'] = second_parse_url($info_file['url']);
		
		
    	$filesize = @filesize($info_file['url']);
    	$filesize = ($filesize !== false) ? $filesize : (!empty($info_file) ? number_round($info_file['size'] * 1048576, 0) : false);
    	if ($filesize !== false)
    		header('Content-Length: ' . $filesize);
    	header('content-type:application/force-download');
    	header('Content-Disposition:attachment;filename="' . substr(strrchr($info_file['url'], '/'), 1) . '"');
    	header('Expires:0');
    	header('Cache-Control:must-revalidate');
    	header('Pragma:public');
    	if (@readfile($info_file['url']) === false)
    		redirect($info_file['url']);
	}
	
	else
	{
	    redirect($info_file['url']);
	}
}
else
	$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
?>
