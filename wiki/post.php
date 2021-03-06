<?php


























require_once('../kernel/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
load_module_lang('wiki');

define('TITLE', $LANG['wiki'] . ': ' . $LANG['wiki_contribuate']);
define('ALTERNATIVE_CSS', 'wiki');

$bread_crumb_key = 'wiki_post';
require_once('../wiki/wiki_bread_crumb.php');

$is_cat = retrieve(POST, 'is_cat', false) ? 1 : 0;
$is_cat_get = (retrieve(GET, 'type', '') == 'cat') ? 1 : 0;
$is_cat = $is_cat > 0 ? $is_cat : $is_cat_get;
$id_edit = retrieve(POST, 'id_edit', 0);
$title = retrieve(POST, 'title', '');
$encoded_title = retrieve(GET, 'title', '');
$contents = wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));
$contents_preview = htmlspecialchars(retrieve(POST, 'contents', '', TSTRING_UNCHANGE), ENT_COMPAT, 'ISO-8859-1');
$id_cat = retrieve(GET, 'id_parent', 0);
$new_id_cat = retrieve(POST, 'id_cat', 0);
$id_cat = $id_cat > 0 ? $id_cat : $new_id_cat;
$preview = !empty($_POST['preview']) ? true : false;
$id_edit_get = retrieve(GET, 'id', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_get;

require_once('../kernel/header.php'); 


$error = '';

if (!empty($contents)) 
{
	include_once('../wiki/wiki_functions.php');	
	
	$menu = '';
	
	
	if (preg_match('`[\-]{2,6}`isU', $contents))
	{
		$menu_list = wiki_explode_menu($contents); 
		$menu = wiki_display_menu($menu_list); 
	}
	
	if ($preview)
	{
		$Template->assign_block_vars('preview', array(
			'CONTENTS' => second_parse(wiki_no_rewrite(stripslashes($contents))),
			'TITLE' => stripslashes($title)
		));
		if (!empty($menu))
		{
			$Template->assign_block_vars('preview.menu', array(
				'MENU' => $menu
			));
		}
	}
	else 
	{
		if ($id_edit > 0)
		{		
			$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__); 
			
			$general_auth = empty($article_infos['auth']) ? true : false;
			$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
			if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_EDIT)) && ($general_auth || $User->check_auth($article_auth , WIKI_EDIT))))
				$Errorh->handler('e_auth', E_USER_REDIRECT); 
			
			$previous_id_contents = $Sql->query("SELECT id_contents FROM " . PREFIX . "wiki_articles WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET activ = 0 WHERE id_contents = '" . $previous_id_contents . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_contents (id_article, menu, content, activ, user_id, user_ip, timestamp) VALUES ('" . $id_edit . "', '" . addslashes($menu) . "', '" . $contents . "', 1, " . $User->get_attribute('user_id') . ", '" . USER_IP . "', " . time() . ")", __LINE__, __FILE__);
			
			$id_contents = $Sql->insert_id("SELECT MAX(id_contents) FROM " . PREFIX . "wiki_contents");
            
	 		
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_contents = '" . $id_contents . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
        
            
            import('content/syndication/feed');
            Feed::clear_cache('wiki');
			
			
			$redirect = $article_infos['encoded_title'];
			redirect(url('wiki.php?title=' . $redirect, $redirect, '', '&'));
		}
		elseif (!empty($title)) 
		{
			
			if ($is_cat && !$User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT))
				$Errorh->handler('e_auth', E_USER_REDIRECT); 
			elseif (!$is_cat && !$User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE))
				$Errorh->handler('e_auth', E_USER_REDIRECT); 
			
			
			$article_exists = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . url_encode_rewrite($title) . "'", __LINE__, __FILE__);
			
			
			
			if ($article_exists > 0)
				$errstr = $LANG['wiki_title_already_exists'];
			else 
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_articles (title, encoded_title, id_cat, is_cat, undefined_status, auth) VALUES ('" . $title . "', '" . url_encode_rewrite($title) . "', '" . $new_id_cat . "', '" . $is_cat . "', '', '')", __LINE__, __FILE__);
				
				$id_article = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "wiki_articles");
				
				$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_contents (id_article, menu, content, activ, user_id, user_ip, timestamp) VALUES ('" . $id_article . "', '" . addslashes($menu) . "', '" . $contents . "', 1, " . $User->get_attribute('user_id') . ", '" . USER_IP . "', " . time() . ")", __LINE__, __FILE__);
				
				$id_contents = $Sql->insert_id("SELECT MAX(id_contents) FROM " . PREFIX . "wiki_contents");
				$cat_update = '';
				if ($is_cat == 1)
				{
					$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_cats (id_parent, article_id) VALUES (" . $new_id_cat . ", '" . $id_article . "')", __LINE__, __FILE__);
					
					$id_created_cat = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "wiki_articles");
					$cat_update = ", id_cat = '" . $id_created_cat . "'";
					
					$Cache->Generate_module_file('wiki');
				}
				$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_contents = '" . $id_contents . "'" . $cat_update . " WHERE id = " . $id_article, __LINE__, __FILE__);
				
                
                import('content/syndication/feed');
                Feed::clear_cache('wiki');
                
				$redirect = $Sql->query("SELECT encoded_title FROM " . PREFIX . "wiki_articles WHERE id = '" . $id_article . "'", __LINE__, __FILE__);
				redirect(url('wiki.php?title=' . $redirect, $redirect, '' , '&'));
			}
		}
	}
}


$Template->set_filenames(array('wiki_edit'=> 'wiki/post.tpl'));
$Template->assign_vars(array(
	'WIKI_PATH' => $Template->get_module_data_path('wiki'),
));
if ($id_edit > 0)
{
	$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', '*', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
	
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_EDIT)) && ($general_auth || $User->check_auth($article_auth , WIKI_EDIT))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$article_contents = $Sql->query_array(PREFIX . 'wiki_contents', '*', "WHERE id_contents = '" . $article_infos['id_contents'] . "'", __LINE__, __FILE__);
	$contents = $article_contents['content'];
	if (!empty($article_contents['menu'])) 
	{
		$string_regex = '-';
		for ($i = 1; $i <= 5; $i++)
		{
			$string_regex .= '-';
			$contents = preg_replace('`[\r\n]+<(?:div|h[1-5]) class="wiki_paragraph' .  $i . '" id=".+">(.+)</(?:div|h[1-5])><br />[\r\n]+`sU', "\n" . $string_regex . ' $1 '. $string_regex, "\n" . $contents . "\n");
		}
		$contents = trim($contents);
	}
	
	$l_action_submit = $LANG['update'];
	
	$Template->assign_vars(array(
		'SELECTED_CAT' => $id_edit,
	));
}
else
{
	
	if ($is_cat && !$User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	elseif (!$is_cat && !$User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	
	if (!empty($encoded_title))
		$Errorh->handler($LANG['wiki_article_does_not_exist'], E_USER_WARNING);	
	
	if ($id_cat > 0 && array_key_exists($id_cat, $_WIKI_CATS)) 
	{
		$Template->assign_block_vars('create', array());
		$cats = array();
		$cat_list = display_cat_explorer($id_cat, $cats, 1);
		$cats = array_reverse($cats);
		if (array_key_exists(0, $cats))
			unset($cats[0]);
		$nbr_cats = count($cats);
		$current_cat = '';
		$i = 1;
		foreach ($cats as $key => $value)
		{
			$current_cat .= $_WIKI_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_WIKI_CATS[$id_cat]['name'];
		$Template->assign_vars(array(
			'SELECTED_CAT' => $id_cat,
			'CAT_0' => '',
			'CAT_LIST' => $cat_list,
			'CURRENT_CAT' => $current_cat
		));
	}
	else 
	{
		$Template->assign_block_vars('create', array());
		$contents = '';
		$result = $Sql->query_while("SELECT c.id, a.title, a.encoded_title
		FROM " . PREFIX . "wiki_cats c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
		WHERE c.id_parent = 0
		ORDER BY title ASC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($sub_cats_number > 0)
			{	
				$Template->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', 1);"><img src="' . $Template->get_module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '"  style="vertical-align:middle" /></a> 
					<a href="javascript:show_cat_contents(' . $row['id'] . ', 1);"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" id ="img_' . $row['id'] . '" alt="" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:select_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
			else
			{
				$Template->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:select_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
		}
		$Sql->query_close($result);
		$Template->assign_vars(array(
			'SELECTED_CAT' => 0,
			'CAT_0' => 'wiki_selected_cat',
			'CAT_LIST' => '',
			'CURRENT_CAT' => $LANG['wiki_no_selected_cat']
		));
	}
	$l_action_submit = $LANG['submit'];
}


$content_editor = new ContentFormattingFactory(BBCODE_LANGUAGE);
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$Template->assign_vars(array(
	'TITLE' => $is_cat == 1 ? ($id_edit == 0 ? $LANG['wiki_create_cat'] : sprintf($LANG['wiki_edit_cat'], $article_infos['title'])) : ($id_edit == 0 ? $LANG['wiki_create_article'] : sprintf($LANG['wiki_edit_article'], $article_infos['title'])),
	'KERNEL_EDITOR' => $editor->display(),
	'ID_CAT' => $id_edit > 0 ? $article_infos['id_cat'] : '',
	'CONTENTS' => !empty($contents_preview) ? $contents_preview : ($id_edit > 0 ? wiki_unparse(trim($contents)) : ''),
	'ID_EDIT' => $id_edit,
	'IS_CAT' => $is_cat,
	'ID_CAT' => $id_cat,
	'ARTICLE_TITLE' => !empty($encoded_title) ? $encoded_title : stripslashes($title),'L_TITLE_FIELD' => $LANG['title'],
	'TARGET' => url('post.php' . ($is_cat == 1 ? '?type=cat&amp;token=' . $Session->get_token() : '?token=' . $Session->get_token())),
	'L_CONTENTS' => $LANG['wiki_contents'],
	'L_ALERT_CONTENTS' => $LANG['require_text'],
	'L_ALERT_TITLE' => $LANG['require_title'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUBMIT' => $l_action_submit,
	'L_CAT' => $LANG['wiki_article_cat'],
	'L_CURRENT_CAT' => $LANG['wiki_current_cat'],
	'L_DO_NOT_SELECT_ANY_CAT' => $LANG['wiki_do_not_select_any_cat'],
	'L_PREVIEWING' => $LANG['wiki_previewing'],
	'L_TABLE_OF_CONTENTS' => $LANG['wiki_table_of_contents']
));


include_once('../wiki/post_js_tools.php');


if (!empty($errstr))
	$Errorh->handler($errstr, E_USER_WARNING);

$Template->pparse('wiki_edit');


require_once('../kernel/footer.php');

?>
