<?php


























if (defined('PHPBOOST') !== true)	
	exit;

load_module_lang('gallery'); 
$Cache->load('gallery');

define('READ_CAT_GALLERY', 0x01);
define('WRITE_CAT_GALLERY', 0x02);
define('EDIT_CAT_GALLERY', 0x04);

$g_idcat = retrieve(GET, 'cat', 0);
if (!empty($g_idcat))
{
	
	$Bread_crumb->add($LANG['title_gallery'], url('gallery.php'));
	foreach ($CAT_GALLERY as $id => $array_info_cat)
	{
		if ($CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'])
			$Bread_crumb->add($array_info_cat['name'], 'gallery' . url('.php?cat=' . $id, '-' . $id . '.php'));
	}
}
else
	$Bread_crumb->add($LANG['title_gallery'], '');
	
$title_gallery = !empty($CAT_GALLERY[$g_idcat]['name']) ? addslashes($CAT_GALLERY[$g_idcat]['name']) : '';
define('TITLE', (!empty($title_gallery) ? $LANG['title_gallery'] . ' - ' . $title_gallery : $LANG['title_gallery']));
define('ALTERNATIVE_CSS', 'gallery'); 

?>
