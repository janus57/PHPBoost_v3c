<?php


























if (defined('PHPBOOST') !== true)	
    exit;

require_once('articles_constants.php');
	
if (isset($CAT_ARTICLES[$idartcat]) && isset($_GET['cat']))
{ 
	
	$Bread_crumb->add($LANG['title_articles'], url('articles.php'));
	foreach ($CAT_ARTICLES as $id => $array_info_cat)
	{
		if (!empty($idartcat) && $CAT_ARTICLES[$idartcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_ARTICLES[$idartcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_ARTICLES[$idartcat]['level'])
			$Bread_crumb->add($array_info_cat['name'], 'articles' . url('.php?cat=' . $id, '-' . $id . '.php'));
	}
	if (!empty($idart))
	{
		$articles = $Sql->query_array(PREFIX . 'articles', '*', "WHERE visible = 1 AND id = '" . $idart . "' AND idcat = " . $idartcat, __LINE__, __FILE__);
		$idartcat = $articles['idcat'];
		
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($articles['title']));
		$Bread_crumb->add($articles['title'], 'articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart, '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php'));
		
		if (!empty($get_note))
			$Bread_crumb->add($LANG['note'], '');
		elseif (!empty($_GET['i']))
			$Bread_crumb->add($LANG['com'], '');
	}
	else
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($CAT_ARTICLES[$idartcat]['name']));
}
else
{
	$Bread_crumb->add($LANG['title_articles'], '');
	if (!defined('TITLE'))
		define('TITLE', $LANG['title_articles']);
}

?>
