<?php


























require_once('../admin/admin_begin.php');
load_module_lang('web'); 
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', '');
$top = retrieve(GET, 'top', '');
$bottom = retrieve(GET, 'bot', '');
$del = isset($_GET['del']) ?  true : false;


if (!empty($_POST['valid']))
{
	$result = $Sql->query_while("SELECT id
	FROM " . PREFIX . "web_cat
	ORDER BY class", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$cat = retrieve(POST, $row['id'] . 'cat', '');  
		$contents = retrieve(POST, $row['id'] . 'contents', '');
		$icon = retrieve(POST, $row['id'] . 'icon', ''); 
		$icon_path = retrieve(POST, $row['id'] . 'icon_path', ''); 
		$aprob = retrieve(POST, $row['id'] . 'aprob', 0);
		$secure = retrieve(POST, $row['id'] . 'secure', -1);
		
		if (!empty($icon_path))
			$icon = $icon_path;
			
		if (!empty($cat))
			$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET name = '" . $cat . "', contents = '" . $contents . "', icon = '" . $icon . "', aprob = '" . $aprob . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);

	}
	$Sql->query_close($result);
	
	
	$Cache->Generate_module_file('web');
	
	redirect(HOST . SCRIPT);
}
elseif (empty($top) && empty($bottom) && $del && !empty($id)) 
{
	$Session->csrf_get_protect(); 
	
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "web_cat WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	$Sql->query_inject("UPDATE " . PREFIX . "web SET idcat = '' WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
	
	
	$Cache->Generate_module_file('web');
	
	redirect(HOST . SCRIPT);
}
elseif ((!empty($top) || !empty($bottom)) && !empty($id)) 
{
	$Session->csrf_get_protect(); 
	
	if (!empty($top))
	{	
		$idmoins = $top - 1;
		
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = 0 WHERE class = '" . $top . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = '" . $top . "' WHERE class = '" . $idmoins . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = '" . $idmoins . "' WHERE class = 0", __LINE__, __FILE__);
		
		
		$Cache->Generate_module_file('web');
		
		redirect(HOST . SCRIPT . '#w' . $id);
	}
	elseif (!empty($bottom))
	{
		$idplus = ($bottom + 1);
		
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = 0 WHERE class = '" . $bottom . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = '" . $bottom . "' WHERE class = '" . $idplus . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "web_cat SET class = '" . $idplus . "' WHERE class = 0", __LINE__, __FILE__);
		
		
		$Cache->Generate_module_file('web');
		
		redirect(HOST . SCRIPT . '#w' . $id);
	}
}

elseif (!empty($_POST['add'])) 
{
	$cat = retrieve(POST, 'cat', '');
	$contents = retrieve(POST, 'contents', '');
	$icon = retrieve(POST, 'icon', ''); 
	$icon_path = retrieve(POST, 'icon_path', ''); 
	$aprob = retrieve(POST, 'aprob', 0);
	$secure = retrieve(POST, 'secure', -1);
		
	if (!empty($icon_path))
		$icon = $icon_path;
		
	if (!empty($cat))
	{	
		$order = $Sql->query("SELECT MAX(class) FROM " . PREFIX . "web_cat", __LINE__, __FILE__);
		$order++;
		
		
		$Sql->query_inject("INSERT INTO " . PREFIX . "web_cat (class, name, contents, icon, aprob, secure) VALUES('" . $order . "', '" . $cat . "', '" . $contents . "', '" . $icon . "', '" . $aprob . "', '" . $secure . "')", __LINE__, __FILE__);	
	
		
		$Cache->Generate_module_file('web');
	
		redirect(HOST . SCRIPT);
	}
	else
		redirect(HOST . DIR . '/web/admin_web_cat.php?error=incomplete#errorh');
}

else	
{		
	$Template->set_filenames(array(
		'admin_web_cat'=> 'web/admin_web_cat.tpl'
	));
	  
	
	$rep = './';
	if (is_dir($rep)) 
	{
		$img_array = array();
		$dh = @opendir( $rep);
		while (! is_bool($lang = @readdir($dh)))
		{	
			if (preg_match('`\.(gif|png|jpg|jpeg|tiff)$`i', $lang))
				$img_array[] = $lang; 
		}	
		@closedir($dh); 
	}
	
	$image_list = '<option value="">--</option>';
	foreach ($img_array as $key => $img_path)
		$image_list .= '<option value="' . $img_path . '">' . $img_path . '</option>';
		
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'IMG_LIST' => $image_list,
		'L_DEL_ENTRY' => $LANG['del_entry'],		
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_ICON' => $LANG['icon_cat'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_STATUS' => $LANG['status'],
		'L_POSITION' => $LANG['position'],
		'L_DELETE' => $LANG['delete'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ADD' => $LANG['add'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_RANK' => $LANG['rank'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin']
	));	
		
	
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$min_cat = $Sql->query("SELECT MIN(class) FROM " . PREFIX . "web_cat", __LINE__, __FILE__);
	$max_cat = $Sql->query("SELECT MAX(class) FROM " . PREFIX . "web_cat", __LINE__, __FILE__);

	$result = $Sql->query_while("SELECT id, name, class, contents, icon, aprob, secure
	FROM " . PREFIX . "web_cat
	ORDER BY class", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		
		$row['name'] = html_entity_decode($row['name'], ENT_COMPAT, 'ISO-8859-1');
		$name = strlen($row['name']) > 45 ? substr($row['name'], 0, 45) . '...' : $row['name'];
		$name = htmlspecialchars($name, ENT_COMPAT, 'ISO-8859-1');

		
		$enabled = $row['aprob'] == '1' ? 'checked="checked"' : '';	
		$disabled = $row['aprob'] == '0' ? 'checked="checked"' : '';				
		
		
		$top_link = ($min_cat != $row['class']) ? '<a href="admin_web_cat.php?top=' . $row['class'] . '&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token() . '" title="">
		<img src="../templates/' . get_utheme() . '/images/admin/up.png" alt="" title="" /></a>' : '';
		$bottom_link = ($max_cat != $row['class']) ? '<a href="admin_web_cat.php?bot=' . $row['class'] . '&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token() . '" title="">
		<img src="../templates/' . get_utheme() . '/images/admin/down.png" alt="" title="" /></a>' : '';
		
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
			'TOP' => $top_link,
			'BOTTOM' => $bottom_link,
			'ACTIV_ENABLED' => $enabled,
			'ACTIV_DISABLED' => $disabled
		));			
		
		
		for ($i = -1; $i <= 2; $i++)
		{
			switch ($i) 
			{	
				case -1:
					$rank = $LANG['guest'];
				break;				
				case 0:
					$rank = $LANG['member'];
				break;				
				case 1: 
					$rank = $LANG['modo'];
				break;		
				case 2:
					$rank = $LANG['admin'];
				break;					
				default: -1;
			} 

			$selected = ($row['secure'] == $i) ? 'selected="selected"' : '' ;

			$Template->assign_block_vars('cat.select_secure', array(
				'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
			));
		}
	}
	$Sql->query_close($result);
		
	$Template->pparse('admin_web_cat'); 
}

require_once('../admin/admin_footer.php');

?>
