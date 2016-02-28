<?php


























require_once('../kernel/begin.php'); 
require_once('../pages/pages_begin.php'); 
include_once('pages_functions.php');

$id_edit = retrieve(GET, 'id', 0);
$id_edit_post = retrieve(POST, 'id_edit', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_post;
$title = retrieve(POST, 'title', '');
$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
$count_hits = !empty($_POST['count_hits']) ? 1 : 0;
$enable_com = !empty($_POST['activ_com']) ? 1 : 0;
$own_auth = !empty($_POST['own_auth']);
$is_cat = !empty($_POST['is_cat']) ? 1 : 0;
$id_cat = retrieve(POST, 'id_cat', 0);
$preview = !empty($_POST['preview']);
$del_article = retrieve(GET, 'del', 0);


$error = '';
if ($id_edit > 0)
	define('TITLE', $LANG['pages_edition']);
else
	define('TITLE', $LANG['pages_creation']);
	
if ($id_edit > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
	$Bread_crumb->add(TITLE, url('post.php?id=' . $id_edit));
	$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . url_encode_rewrite($_PAGES_CATS[$id]['name']), url_encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'));
	
require_once('../kernel/header.php');


if (!empty($contents))
{
	if ($own_auth)
	{
		
		$array_auth_all = Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM);
		$page_auth = addslashes(serialize($array_auth_all));
	}
	else
		$page_auth = '';
	
	
	if (!$preview)
	{
		
		if ($id_edit > 0)
		{
			$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'contents', 'auth', 'encoded_title', 'is_cat', 'id_cat', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			
			
			$special_auth = !empty($page_infos['auth']);
			$array_auth = unserialize($page_infos['auth']);
			
			if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
				redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
			
			
			if ($page_infos['is_cat'] == 1)
			{
				$sub_cats = array();
				pages_find_subcats($sub_cats, $page_infos['id_cat']);
				$sub_cats[] = $page_infos['id_cat'];
				if (in_array($id_cat, $sub_cats)) 
					$error = 'cat_contains_cat';
			}
			
			
			if ($page_infos['is_cat'] == 0)
			{		
				
				$Sql->query_inject("UPDATE " . PREFIX . "pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $enable_com . "', auth = '" . $page_auth . "', id_cat = '" . $id_cat . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				
				redirect(HOST . DIR . '/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
			
			elseif ($page_infos['is_cat'] == 1 && empty($error))
			{
				
				if ($id_cat != $page_infos['id_cat'])
				{
					$Sql->query_inject("UPDATE " . PREFIX . "pages_cats SET id_parent = '" . $id_cat . "' WHERE id = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
				}
				
				$Sql->query_inject("UPDATE " . PREFIX . "pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $enable_com . "', auth = '" . $page_auth . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				
				$Cache->Generate_module_file('pages');
				
				redirect(HOST . DIR . '/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
		}
		
		elseif (!empty($title))
		{
			if (!$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
				redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
			
			$encoded_title = url_encode_rewrite($title);
			$is_already_page = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
			
			
			if ($is_already_page == 0)
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "pages (title, encoded_title, contents, user_id, count_hits, activ_com, timestamp, auth, is_cat, id_cat) VALUES ('" . $title . "', '" . $encoded_title . "', '" .  pages_parse($contents) . "', '" . $User->get_attribute('user_id') . "', '" . $count_hits . "', '" . $enable_com . "', '" . time() . "', '" . $page_auth . "', '" . $is_cat . "', '" . $id_cat . "')", __LINE__, __FILE__);
				
				if ($is_cat > 0)
				{
					$last_id_page = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "pages");  
					$Sql->query_inject("INSERT INTO " . PREFIX . "pages_cats (id_parent, id_page) VALUES ('" . $id_cat . "', '" . $last_id_page . "')", __LINE__, __FILE__);
					$last_id_pages_cat = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "pages_cats");
					$Sql->query_inject("UPDATE " . PREFIX . "pages SET id_cat = '" . $last_id_pages_cat . "' WHERE id = '" . $last_id_page . "'", __LINE__, __FILE__);
					
					$Cache->Generate_module_file('pages');
				}
				
				redirect(HOST . DIR . '/pages/' . url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
			}
			
			else
			{
				$error = 'page_already_exists';
			}
		}
	}
	else
		$error = 'preview';
}

elseif ($del_article > 0)
{
    
    $Session->csrf_get_protect();
    
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	
	
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
		
	
	if (!empty($page_infos['title']))
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE redirect = '" . $del_article . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'pages' AND idprov = '" . $del_article . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . url('/pages/pages.php?error=delete_success', '', '&'));
	}
	else
		redirect(HOST . DIR . url('/pages/pages.php?error=delete_failure', '', '&'));
}

$Template->set_filenames(array('post'=> 'pages/post.tpl'));

if ($id_edit > 0)
{
	
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
	
	
	if ($error == 'cat_contains_cat')
		$Errorh->handler($LANG['pages_cat_contains_cat'], E_USER_WARNING);
	elseif ($error == 'preview')
	{
		$Errorh->handler($LANG['pages_notice_previewing'], E_USER_NOTICE);
		$Template->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}

	
	$cats = array();
	
	$id_cat_display = $page_infos['is_cat'] == 1 ? $_PAGES_CATS[$page_infos['id_cat']]['id_parent'] : $page_infos['id_cat'];
	$cat_list = display_cat_explorer($id_cat_display, $cats, 1);
	
	$Template->assign_vars(array(
		'CONTENTS' => !empty($error) ? htmlspecialchars(stripslashes($contents), ENT_COMPAT, 'ISO-8859-1') : pages_unparse($page_infos['contents']),
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($page_infos['count_hits'] == 1 ? 'checked="checked"' : ''),
		'ACTIV_COM_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') : ($page_infos['activ_com'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => !empty($page_infos['auth']) ? 'checked="checked"' : '',
		'CAT_0' => $id_cat_display == 0 ? 'pages_selected_cat' : '',
		'ID_CAT' => $id_cat_display,
		'SELECTED_CAT' => $id_cat_display,
		'CHECK_IS_CAT' => 'disabled="disabled"' . ($page_infos['is_cat'] == 1 ? ' checked="checked"' : '')
	));
}
else
{
	
	if (!$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		redirect(HOST . DIR . '/pages/pages.php?error=e_auth');
		
	
	if ($error == 'page_already_exists')
		$Errorh->handler($LANG['pages_already_exists'], E_USER_WARNING);
	elseif ($error == 'preview')
	{
		$Errorh->handler($LANG['pages_notice_previewing'], E_USER_NOTICE);
		$Template->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}
	if (!empty($error))
		$Template->assign_vars(array(
			'CONTENTS' => htmlspecialchars(stripslashes($contents), ENT_COMPAT, 'ISO-8859-1'),
			'PAGE_TITLE' => stripslashes($title)
		));
	
	$Template->assign_block_vars('create', array());
	
	
	$cats = array();
	$cat_list = display_cat_explorer(0, $cats, 1);
	$current_cat = $LANG['pages_root'];
	
	$Template->assign_vars(array(
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($_PAGES_CONFIG['count_hits'] == 1 ? 'checked="checked"' : ''),
		'ACTIV_COM_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') :($_PAGES_CONFIG['activ_com'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => '',
		'CAT_0' => 'pages_selected_cat',
		'ID_CAT' => '0',
		'SELECTED_CAT' => '0'
	));
}

if (!empty($page_infos['auth']))
	$array_auth = unserialize($page_infos['auth']);
else
	$array_auth = !empty($_PAGES_CONFIG['auth']) ? $_PAGES_CONFIG['auth'] : array();

$Template->assign_vars(array(
	'ID_EDIT' => $id_edit,
	'SELECT_READ_PAGE' => Authorizations::generate_select(READ_PAGE, $array_auth),
	'SELECT_EDIT_PAGE' => Authorizations::generate_select(EDIT_PAGE, $array_auth),
	'SELECT_READ_COM' => Authorizations::generate_select(READ_COM, $array_auth),
	'OWN_AUTH_DISABLED' => !empty($page_infos['auth']) ? 'false' : 'true',
	'DISPLAY' => empty($page_infos['auth']) ? 'display:none;' : '',
	'PAGES_PATH' => $Template->get_module_data_path('pages'),
	'CAT_LIST' => $cat_list,
	'KERNEL_EDITOR' => display_editor(),
	'L_AUTH' => $LANG['pages_auth'],
	'L_ACTIV_COM' => $LANG['pages_activ_com'],
	'L_COUNT_HITS' => $LANG['pages_count_hits'],
	'L_ALERT_CONTENTS' => $LANG['page_alert_contents'],
	'L_ALERT_TITLE' => $LANG['page_alert_title'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_OWN_AUTH' => $LANG['pages_own_auth'],
	'L_IS_CAT' => $LANG['pages_is_cat'],
	'L_CAT' => $LANG['pages_parent_cat'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_PATH' => $LANG['pages_page_path'],
	'L_PROPERTIES' => $LANG['pages_properties'],
	'L_TITLE_POST' => $id_edit > 0 ? sprintf($LANG['pages_edit_page'], $page_infos['title']) : $LANG['pages_creation'],
	'L_TITLE_FIELD' => $LANG['page_title'],
	'L_CONTENTS' => $LANG['page_contents'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUMBIT' => $LANG['submit'],
	'L_ROOT' => $LANG['pages_root'],
	'L_PREVIEWING' => $LANG['pages_previewing'],
	'L_CONTENTS_PART' => $LANG['pages_contents_part'],
	'L_SUBMIT' => $id_edit > 0 ? $LANG['update'] : $LANG['submit'],
	'TARGET' => url('post.php?token=' . $Session->get_token())
));

$Template->pparse('post');

require_once('../kernel/footer.php'); 

?>
