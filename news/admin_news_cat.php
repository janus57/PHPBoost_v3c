<?php


























require_once('../admin/admin_begin.php');
load_module_lang('news'); 
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);


if (!empty($_POST['valid']))
{
	$result = $Sql->query_while("SELECT id
	FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$cat = retrieve(POST, $row['id'] . 'cat', '');  
		$icon = retrieve(POST, $row['id'] . 'icon', '');  
		$icon_path = retrieve(POST, $row['id'] . 'icon_path', '');  
		$contents = retrieve(POST, $row['id'] . 'contents', '');
		
		if (!empty($icon_path))
			$icon = $icon_path;
		
		if (!empty($cat))
			$Sql->query_inject("UPDATE " . PREFIX . "news_cat SET name = '" . $cat . "', icon = '" . $icon . "', contents = '" . $contents . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			
	}
	$Sql->query_close($result);
	
	redirect(HOST . SCRIPT);
}
elseif (!empty($_GET['del']) && !empty($id)) 
{
	$Session->csrf_get_protect(); 
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "news_cat WHERE id = " . $id, __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . PREFIX . "news SET idcat = 0 WHERE idcat = " . $id, __LINE__, __FILE__);
		
	redirect(HOST . SCRIPT);
}

elseif (!empty($_POST['add'])) 
{
	$cat = retrieve(POST, 'cat', '');  
	$icon = retrieve(POST, 'icon', ''); 
	$icon_path = retrieve(POST, 'icon_path', ''); 
	$contents = retrieve(POST, 'contents', ''); 
	
	if (!empty($icon_path))
		$icon = $icon_path;
	
	if (!empty($cat))
	{
		
		$Sql->query_inject("INSERT INTO " . PREFIX . "news_cat (name, contents, icon) VALUES('" . $cat . "', '" . $contents . "', '" . $icon . "')", __LINE__, __FILE__);
		
		redirect(HOST . SCRIPT); 	
	}
	else
		redirect(HOST . DIR . '/news/admin_news_cat.php?error=incomplete#errorh');
}

else	
{		
	$Template->set_filenames(array(
		'admin_news_cat'=> 'news/admin_news_cat.tpl'
	));
	
	
	import('io/filesystem/folder');
	$img_array = array();
	$image_list = '<option value="">--</option>';
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		$img_array[] = $image;
		$image_list .= '<option value="' . $image . '">' . $image . '</option>';
	}

	$Template->assign_vars(array(
		'THEME' => get_utheme(),	
		'IMG_LIST' => $image_list,
		'L_DEL_ENTRY' => $LANG['del_entry'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_NAME' => $LANG['name'],
		'L_ICON' => $LANG['icon_cat'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_DESC' => $LANG['description'],
		'L_DELETE' => $LANG['delete'],
		'L_ADD' => $LANG['add'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$result = $Sql->query_while("SELECT a.id, a.name, a.contents, a.icon
	FROM " . PREFIX . "news_cat a", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		
		$row['name'] = html_entity_decode($row['name'], ENT_COMPAT, 'ISO-8859-1');
		$name = strlen($row['name']) > 45 ? substr($row['name'], 0, 45) . '...' : $row['name'];
		$name = htmlspecialchars($name, ENT_COMPAT, 'ISO-8859-1');

		$img_direct_path = (strpos($row['icon'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		foreach ($img_array as $key => $img_path)
		{	
			$selected = ($img_path == $row['icon']) ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $img_path . '"' . ($img_direct_path ? '' : $selected) . '>' . $img_path . '</option>';
		}
		
		$Template->assign_block_vars('cat', array(
			'IDCAT' => $row['id'],
			'CAT' => $name,
			'CONTENTS' => $row['contents'],
			'IMG_PATH' => $img_direct_path ? $row['icon'] : '',
			'IMG_ICON' => !empty($row['icon']) ? '<img src="' . $row['icon'] . '" alt="" class="valign_middle" />' : '',		
			'IMG_LIST' => $image_list,
		));
	}
	$Sql->query_close($result);
		
	$Template->pparse('admin_news_cat'); 
}

require_once('../admin/admin_footer.php');

?>
