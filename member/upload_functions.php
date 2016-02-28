<?php


























if (defined('PHPBOOST') !== true)	exit;


function display_cat_explorer($id, &$cats, $display_select_link = 1, $user_id)
{
	global $Sql;
	if ($id > 0)
	{
		$id_cat = $id;
		
		do
		{
			$id_cat = $Sql->query("SELECT id_parent FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $id_cat . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
			$cats[] = $id_cat;
		}	
		while ($id_cat > 0);
	}	

	
	$cats_list = '<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">' . show_cat_contents(0, $cats, $id, $display_select_link, $user_id) . '</ul>';
	
	
	$opened_cats_list = '';
	foreach ($cats as $key => $row)
	{
		if ($key != 0)
			$opened_cats_list .= 'cat_status[' . $key . '] = 1;' . "\n";
	}
	return '<script type="text/javascript">
	<!--
' . $opened_cats_list . '
	-->
	</script>
	' . $cats_list;
	
}


function show_cat_contents($id_cat, $cats, $id, $display_select_link, $user_id)
{
	global $Sql, $CONFIG;
	$line = '';
	$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "upload_cat
	WHERE user_id = '" . $user_id . "'
	AND id_parent = '" . $id_cat . "'
	ORDER BY name", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		if (in_array($row['id'], $cats)) 
		{
			$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/minus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/opened_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '">
			<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;padding-left:30px;">'
			. show_cat_contents($row['id'], $cats, $id, $display_select_link, $user_id) . '</ul></span></li>';
		}
		else
		{
			
			$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			
			if ($sub_cats_number > 0)
				$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/plus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/closed_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
			else 
				$line .= '<li style="padding-left:17px;"><img src="../templates/' . get_utheme() . '/images/upload/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
		}
	}
	$Sql->query_close($result);
	return "\n" . $line;
}


function upload_find_subcats(&$array, $id_cat, $user_id)
{
	global $Sql;
	$result = $Sql->query_while ("SELECT id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id_parent = '" . $id_cat . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$array[] = $row['id'];
		
		upload_find_subcats($array, $row['id'], $user_id);
	}
	$Sql->query_close($result);
}

?>
