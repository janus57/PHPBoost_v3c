<?php


























require_once('../kernel/begin.php'); 
load_module_lang('wiki');

define('TITLE' , $LANG['wiki_favorites']);

$bread_crumb_key = 'wiki_favorites';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php'); 

if (!$User->check_level(MEMBER_LEVEL))
	$Errorh->handler('e_auth', E_USER_REDIRECT); 

$add_favorite = retrieve(GET, 'add', 0);
$remove_favorite = retrieve(GET, 'del', 0);

if ($add_favorite > 0)
{
	
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "WHERE id = '" . $add_favorite . "'", __LINE__, __FILE__);
	if (empty($article_infos['encoded_title'])) 
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
	
	$is_favorite = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_favorites WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id_article = '" . $add_favorite . "'", __LINE__, __FILE__);
	if ($is_favorite == 0)
	{
		$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_favorites (id_article, user_id) VALUES ('" . $add_favorite . "', '" . $User->get_attribute('user_id') . "')", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else 
		redirect(HOST . DIR . '/wiki/' . url('favorites.php?error=e_already_favorite', '', '&') . '#errorh');
}
elseif ($remove_favorite > 0)
{
    
    $Session->csrf_get_protect();
    
	
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "WHERE id = '" . $remove_favorite . "'", __LINE__, __FILE__);
	if (empty($article_infos['encoded_title'])) 
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
		
	
	$is_favorite = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_favorites WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id_article = '" . $remove_favorite . "'", __LINE__, __FILE__);
	
	if ($is_favorite > 0)
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_favorites WHERE id_article = '" . $remove_favorite . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else 
		redirect(HOST . DIR . '/wiki/' . url('favorites.php?error=e_no_favorite', '', '&') . '#errorh');
}
else
{
	$Template->set_filenames(array('wiki_favorites'=> 'wiki/favorites.tpl'));
	
	
	$error = !empty($_GET['error']) ? strprotect($_GET['error']) : '';
	if ($error == 'e_no_favorite')
		$errstr = $LANG['wiki_article_is_not_a_favorite'];
	elseif ($error == 'e_already_favorite')
		$errstr = $LANG['wiki_already_favorite'];
	else
		$errstr = '';
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_WARNING);
	
	
	$result = $Sql->query_while("SELECT f.id, a.id, a.title, a.encoded_title
	FROM " . PREFIX . "wiki_favorites f
	LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = f.id_article
	WHERE user_id = '" . $User->get_attribute('user_id') . "'"
	, __LINE__, __FILE__);
	
	$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if ($num_rows == 0)
	{
		$Template->assign_block_vars('no_favorite', array(
			'L_NO_FAVORITE' => $LANG['wiki_no_favorite']
		));
	}
	
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('list', array(
			'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
			'ARTICLE' => $row['title'],
			'ID' => $row['id'],
			'ACTIONS' => '<a href="' . url('favorites.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['wiki_unwatch_this_topic'] . '" onclick="javascript: return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_unwatch_this_topic']) . '\');"><img src="' . $Template->get_module_data_path('wiki') . '/images/delete.png" alt="' . $LANG['wiki_unwatch_this_topic'] . '" /></a>'
		));
	}

	$Template->assign_vars(array(
		'L_FAVORITES' => $LANG['wiki_favorites'],
		'L_TITLE' => $LANG['title'],
		'L_UNTRACK' => $LANG['wiki_unwatch']
	));

	$Template->pparse('wiki_favorites');
}

require_once('../kernel/footer.php'); 

?>
