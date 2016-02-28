<?php



























require_once('../kernel/begin.php'); 
require_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

$g_idpics = retrieve(GET, 'id', 0);
$g_idcat = retrieve(GET, 'cat', 0);
	
if (!empty($g_idpics))
{
	if (!empty($g_idcat))
	{
		if (!isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0) 
			redirect(HOST . DIR . '/gallery/gallery.php?error=unexist_cat');
	}
	else 
	{
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		$CAT_GALLERY[0]['aprob'] = 1;
	}
	
	if (!$User->check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	
	$Sql->query_inject("UPDATE LOW_PRIORITY " . PREFIX . "gallery SET views = views + 1 WHERE idcat = '" . $g_idcat . "' AND id = '" . $g_idpics . "'", __LINE__, __FILE__);
	
	$clause_admin = $User->check_level(ADMIN_LEVEL) ? '' : ' AND aprob = 1';
	$path = $Sql->query("SELECT path FROM " . PREFIX . "gallery WHERE idcat = '" . $g_idcat . "' AND id = '" . $g_idpics . "'" . $clause_admin, __LINE__, __FILE__);
	if (empty($path))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	include_once('../gallery/gallery.class.php');
	$Gallery = new Gallery;
		
	list($width_s, $height_s, $weight_s, $ext) = $Gallery->Arg_pics('pics/' . $path);
	$Gallery->Send_header($ext); 
	if (!empty($Gallery->error))
		die($Gallery->error);
	$Gallery->incrust_pics('pics/' . $path); 
}
else
{
	die($LANG['no_random_img']); 
}

require_once('../kernel/footer_no_display.php');

?>
