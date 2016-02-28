<?php



























if (defined('PHPBOOST') !== true)
{
	exit;
}

$Cache->load('media');
load_module_lang('media');

require_once('media_constant.php');

define('FEED_URL', '/syndication.php?m=media');

function bread_crumb($id)
{
	global $Bread_crumb, $MEDIA_CATS;

	$id_parent = $MEDIA_CATS[$id]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$id]['name'], url('media.php?cat=' . $id, 'media-0-' . $id . '+' . url_encode_rewrite($MEDIA_CATS[$id]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-0-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();
}

?>
