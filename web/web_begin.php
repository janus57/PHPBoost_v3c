<?php


























if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('web'); 

$get_note = retrieve(GET, 'note', 0);
$idweb = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'cat', 0);

$Cache->load('web'); 

$CAT_WEB[$idcat]['name'] = !empty($CAT_WEB[$idcat]['name']) ? $CAT_WEB[$idcat]['name'] : '';
$web['title'] = '';
if (!empty($idweb) && !empty($idcat))
{ 
	$web = $Sql->query_array(PREFIX . 'web' , '*', "WHERE aprob = 1 AND id = '" . $idweb . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($web['title']));
}
elseif (!empty($idcat))
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($CAT_WEB[$idcat]['name']));
else
	define('TITLE', $LANG['title_web']);
	
$l_com_note = !empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '');
$Bread_crumb->add($LANG['title_web'], url('web.php')); 
$Bread_crumb->add($CAT_WEB[$idcat]['name'], (empty($idweb) ? '' : url('web.php?cat=' . $idcat, 'web-' . $idcat . '.php')));
$Bread_crumb->add($web['title'], ((!empty($get_note) || !empty($_GET['i'])) ? url('web.php?cat=' . $idcat . '&amp;id=' . $idweb, 'web-' . $idcat . '-' . $idweb . '.php') : ''));
$Bread_crumb->add($l_com_note, '');

?>
