<?php


























require_once('../kernel/begin.php'); 
include_once('../wiki/wiki_functions.php'); 

load_module_lang('wiki');

require('../wiki/wiki_auth.php');

$id_auth = retrieve(POST, 'id_auth', 0);
$id_status = retrieve(POST, 'id_status', 0);
$type_status = retrieve(POST, 'status', '');
$id_change_status = retrieve(POST, 'id_change_status', 0);
$contents = wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));
$move = retrieve(POST, 'id_to_move', 0);
$new_cat = retrieve(POST, 'new_cat', 0);
$id_to_rename = retrieve(POST, 'id_to_rename', 0);
$new_title = retrieve(POST, 'new_title', '');
$create_redirection_while_renaming = retrieve(POST, 'create_redirection_while_renaming', false);
$create_redirection = retrieve(POST, 'create_redirection', 0);
$redirection_title = retrieve(POST, 'redirection_title', '');
$del_redirection = retrieve(GET, 'del_redirection', 0);
$restore = retrieve(GET, 'restore', 0);
$del_archive = retrieve(GET, 'del_contents', 0);
$del_article = retrieve(GET, 'del_article', 0);
$del_to_remove = retrieve(POST, 'id_to_remove', 0);
$report_cat = retrieve(POST, 'report_cat', 0);
$remove_action = retrieve(POST, 'action', ''); 

if ($id_auth > 0)
{
	if (!$User->check_auth($_WIKI_CONFIG['auth'], WIKI_RESTRICTION))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	$encoded_title = $Sql->query("SELECT encoded_title FROM " . PREFIX . "wiki_articles WHERE id = '" . $id_auth . "'", __LINE__, __FILE__);
	if (empty($encoded_title))
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
		
	if (!empty($_POST['default'])) 
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET auth = '' WHERE id= '" . $id_auth . "'", __LINE__, __FILE__);
	else
	{
		
		$array_auth_all = Authorizations::build_auth_array_from_form(WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM);
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET auth = '" . addslashes(serialize($array_auth_all)) . "' WHERE id= '" . $id_auth . "'", __LINE__, __FILE__);
	}

	
	redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $encoded_title, $encoded_title, '&'));
}
if ($id_change_status > 0)
{
	$type_status = ($type_status == 'radio_undefined') ? 'radio_undefined' : 'radio_defined';
	
	
	if ($type_status == 'radio_undefined' && $contents != '')
	{
		$id_status = -1;
	}
	elseif ($type_status == 'radio_defined' && $id_status > 0 && is_array($LANG['wiki_status_list'][$id_status - 1]))
	{
		$contents = '';
	}
	else
		$id_status = 0;
		
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $id_change_status . "'", __LINE__, __FILE__);
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_STATUS)) && ($general_auth || $User->check_auth($article_auth , WIKI_STATUS))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	if (!empty($article_infos['encoded_title']))
	{
		
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET defined_status = '" . $id_status . "', undefined_status = '" . $contents . "' WHERE id = '" . $id_change_status . "'", __LINE__, __FILE__);
		
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($move > 0) 
{
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "is_cat", "encoded_title", "id_cat", "auth", "WHERE id = '" . $move . "'", __LINE__, __FILE__);
	if ( empty($article_infos['encoded_title']))
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
		
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_MOVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_MOVE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	if ($article_infos['is_cat'] == 0)
	{
		if (array_key_exists($new_cat, $_WIKI_CATS) || $new_cat == 0)
		{
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_cat = '" . $new_cat . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('wiki');
		}
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	
	elseif ($article_infos['is_cat'] == 1)
	{
		
		$sub_cats = array();
		wiki_find_subcats($sub_cats, $article_infos['id_cat']);
		$sub_cats[] = $article_infos['id_cat'];

		if (!in_array($new_cat, $sub_cats)) 
		{
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET id_parent = '" . $new_cat . "' WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('wiki');
			
			redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
		}
		else 
			redirect(HOST . DIR . '/wiki/' .  url('property.php?move=' . $move  . '&error=e_cat_contains_cat', '', '&') . '#errorh');
	}
}
elseif ($id_to_rename > 0 && !empty($new_title)) 
{
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "*", "WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
		
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_RENAME)) && ($general_auth || $User->check_auth($article_auth , WIKI_RENAME))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$already_exists = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . url_encode_rewrite($new_title) . "'", __LINE__, __FILE__);

	if (empty($article_infos['encoded_title']))
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
	elseif (url_encode_rewrite($new_title) == $article_infos['encoded_title'])
	{
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET title = '" . $new_title . "' WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	elseif ($already_exists > 0) 
		redirect(HOST . DIR . '/wiki/' . url('property.php?rename=' . $id_to_rename  . '&error=title_already_exists', '', '&') . '#errorh');
	elseif ($already_exists == 0)
	{
		if ($create_redirection_while_renaming) 
		{
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_articles (id_contents, title, encoded_title, hits, id_cat, is_cat, defined_status, undefined_status, redirect, auth) VALUES ('" . $article_infos['id_contents'] . "', '" . $new_title . "', '" . url_encode_rewrite($new_title) . "', '" . $article_infos['hits'] . "', '" . $article_infos['id_cat'] . "', '" . $article_infos['is_cat'] . "', '" . $article_infos['defined_status'] . "', '" . $article_infos['undefied_status'] . "', 0, '" . $article_infos['auth'] . "')", __LINE__, __FILE__);
			$new_id_article = $Sql->insert_id("SELECT MAX(id_contents) FROM " . PREFIX . "wiki_contents");
			
			
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET id_article = '" . $new_id_article . "' WHERE id_article = '" . $id_to_rename . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET redirect = '" . $new_id_article . "', id_contents = 0 WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET redirect = '" . $new_id_article . "' WHERE redirect = '" . $id_to_rename . "'", __LINE__, __FILE__);
			
			if ($article_infos['is_cat'] == 1)
			{
				$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET article_id = '" . $new_id_article . "' WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
				$Cache->Generate_module_file('wiki');
			}
    		 
             import('content/syndication/feed');
             Feed::clear_cache('wiki');
		   redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($new_title), url_encode_rewrite($new_title), '&'));
		}
		else 
		{
            $Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET title = '" . $new_title . "', encoded_title = '" . url_encode_rewrite($new_title) . "' WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
			
            
            import('content/syndication/feed');
            Feed::clear_cache('wiki');
            
            redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($new_title), url_encode_rewrite($new_title), '&'));
		}
	}
}
elseif ($del_redirection > 0)
{
    
    $Session->csrf_get_protect();
    
	$is_redirection = $Sql->query("SELECT redirect FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
	if ($is_redirection > 0)
	{
		$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $is_redirection . "'", __LINE__, __FILE__);
		
		$general_auth = empty($article_infos['auth']) ? true : false;
		$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
		if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $User->check_auth($article_auth , WIKI_REDIRECT))))
			$Errorh->handler('e_auth', E_USER_REDIRECT); 
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($create_redirection > 0 && !empty($redirection_title))
{
	$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', '*', "WHERE id = '" . $create_redirection . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $User->check_auth($article_auth , WIKI_REDIRECT))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$num_title = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title =  '" . url_encode_rewrite($redirection_title) . "'", __LINE__, __FILE__);

	if (!empty($article_infos['encoded_title']))
	{
		if ($num_title == 0) 
		{
			$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_articles (title, encoded_title, redirect, undefined_status, auth) VALUES ('" . $redirection_title . "', '" . url_encode_rewrite($redirection_title) . "', '" . $create_redirection . "', '', '')", __LINE__, __FILE__);
			redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($redirection_title), url_encode_rewrite($redirection_title), '&'));
		}
		else
			redirect(HOST . DIR . '/wiki/' . url('property.php?create_redirection=' . $create_redirection  . '&error=title_already_exists', '', '&') . '#errorh');
	}
}

elseif (!empty($restore)) 
{
	
	$id_article = $Sql->query("SELECT id_article FROM " . PREFIX . "wiki_contents WHERE id_contents = " . $restore, __LINE__, __FILE__);
	if (!empty($id_article))
	{
		
		$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', 'id_contents', 'encoded_title', 'auth', 'WHERE id = ' . $id_article, __LINE__, __FILE__);
		
		$general_auth = empty($article_infos['auth']) ? true : false;
		$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
		if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE_ARCHIVE))))
			$Errorh->handler('e_auth', E_USER_REDIRECT); 
		
		
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_contents = " . $restore . " WHERE id = " . $id_article, __LINE__, __FILE__);
		
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET activ = 1 WHERE id_contents = " . $restore, __LINE__, __FILE__);
		
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET activ = 0 WHERE id_contents = " . $article_infos['id_contents'], __LINE__, __FILE__);
	}
	
	redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'] , '&'));
}

elseif ($del_archive > 0)
{
    
    $Session->csrf_get_protect();
    
	$contents_infos = $Sql->query_array(PREFIX . "wiki_contents", "activ", "id_article", "WHERE id_contents = '" . $del_archive . "'", __LINE__, __FILE__);
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $contents_infos['id_article'] . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE_ARCHIVE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	if ($is_activ == 0) 
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_contents = '" . $del_archive . "'", __LINE__, __FILE__);
	if (!empty($article_infos['encoded_title'])) 
		redirect(HOST . DIR . '/wiki/' . url('history.php?id=' . $contents_infos['id_article'], '', '&'));
}
elseif ($del_article > 0) 
{
    
    $Session->csrf_get_protect();
    
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "auth", "encoded_title", "id_cat", "WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $del_article . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $del_article . "'", __LINE__, __FILE__); 
	
	 
     import('content/syndication/feed');
     Feed::clear_cache('wiki');
	
	if (array_key_exists($article_infos['id_cat'], $_WIKI_CATS))
		redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$article_infos['id_cat']]['name']), url_encode_rewrite($_WIKI_CATS[$article_infos['id_cat']]['name']), '&'));
	else
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
}
elseif ($del_to_remove > 0 && $report_cat >= 0) 
{
	$remove_action = ($remove_action == 'move_all') ? 'move_all' : 'remove_all';
	
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "id_cat", "auth", "WHERE id = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$sub_cats = array();
	
	wiki_find_subcats($sub_cats, $article_infos['id_cat']);
	$sub_cats[] = $article_infos['id_cat']; 
	
	if (empty($article_infos['encoded_title'])) 
		redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
	
	if ($remove_action == 'move_all') 
	{	
		
		if (!array_key_exists($report_cat, $_WIKI_CATS) && $report_cat > 0)
			redirect(HOST . DIR . '/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_not_a_cat#errorh', '', '&'));
			
		
		if (($report_cat > 0 && in_array($report_cat, $sub_cats)) || $report_cat == $article_infos['id_cat'])
			redirect(HOST . DIR . '/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_cat_contains_cat#errorh', '','&'));
	}

	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $del_to_remove . "'", __LINE__, __FILE__);	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_cats WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	if ($remove_action == 'remove_all') 
	{
		foreach ($sub_cats as $id) 
		{
			$result = $Sql->query_while ("SELECT id FROM " . PREFIX . "wiki_articles WHERE id_cat = '" . $id . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result)) 
			{
				$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $row['id'] . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $row['id'] . "'", __LINE__);
			}
				
			$Sql->query_close($result);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id_cat = '" . $id . "'", __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		$Cache->Generate_module_file('wiki');

		
        import('content/syndication/feed');
        Feed::clear_cache('wiki');
		
		
		if (array_key_exists($article_infos['id_cat'], $_WIKI_CATS) && $_WIKI_CATS[$article_infos['id_cat']]['id_parent'] > 0)
		{
			$title = $_WIKI_CATS[$_WIKI_CATS[$article_infos['id_cat']]['id_parent']]['name'];
			redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($title), url_encode_rewrite($title), '&'));
		}
		else
			redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
	}
	elseif ($remove_action == 'move_all') 
	{
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_cat = '" . $report_cat . "' WHERE id_cat = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET id_parent = '" . $report_cat . "' WHERE id_parent = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Cache->Generate_module_file('wiki');
		
		if (array_key_exists($report_cat, $_WIKI_CATS))
		{
			$title = $_WIKI_CATS[$report_cat]['name'];
			redirect(HOST . DIR . '/wiki/' . url('wiki.php?title=' . url_encode_rewrite($title), url_encode_rewrite($title), '&'));
		}
		else
			redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));
	}
}


redirect(HOST . DIR . '/wiki/' . url('wiki.php', '', '&'));

?>
