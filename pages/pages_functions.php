<?php


























if (defined('PHPBOOST') !== true)	exit;


function display_cat_explorer($id, &$cats, $display_select_link = 1)
{
	global $_PAGES_CATS;
		
	if ($id > 0)
	{
		$id_cat = $id;
		
		do
		{
			$cats[] = (int)$_PAGES_CATS[$id_cat]['id_parent'];
			$id_cat = (int)$_PAGES_CATS[$id_cat]['id_parent'];
		}	
		while ($id_cat > 0);
	}
	

	
	$cats_list = '<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">' . show_cat_contents(0, $cats, $id, $display_select_link) . '</ul>';
	
	
	$opened_cats_list = '';
	foreach ($cats as $key => $value)
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


function show_cat_contents($id_cat, $cats, $id, $display_select_link)
{
	global $_PAGES_CATS, $Sql, $Template;
	$line = '';
	foreach ($_PAGES_CATS as $key => $value)
	{
		
		if ($value['id_parent']  == $id_cat)
		{
			if (in_array($key, $cats)) 
			{
				$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('pages') . '/images/minus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('pages') . '/images/opened_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '">
				<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;padding-left:30px;">'
				. show_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				
				$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $key . "'", __LINE__, __FILE__);
				
				if ($sub_cats_number > 0)
					$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('pages') . '/images/plus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('pages') . '/images/closed_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '"></span></li>';
				else 
					$line .= '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('pages') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span></li>';
			}
		}
	}
	return "\n" . $line;
}


function pages_find_subcats(&$array, $id_cat)
{
	global $_PAGES_CATS;
	
	foreach ($_PAGES_CATS as $key => $value)
	{
		if ($value['id_parent'] == $id_cat)
		{
			$array[] = $key;
			
			pages_find_subcats($array, $key);
		}
	}
}


function pages_parse($contents)
{
	$contents = strparse($contents);
	$contents = preg_replace('`\[link=([a-z0-9+#-]+)\](.+)\[/link\]`isU', '<a href="/pages/$1">$2</a>', $contents);
	
	return (string) $contents;
}


function pages_unparse($contents)
{
	$contents = link_unparse($contents);
	return unparse($contents);
}


function pages_second_parse($contents)
{
	global $CONFIG;
	
	if ($CONFIG['rewrite'] == 0) 
	{
			$contents = preg_replace('`<a href="/pages/([a-z0-9+#-]+)">(.*)</a>`sU', '<a href="/pages/pages.php?title=$1">$2</a>', $contents);
	}
	$contents = second_parse($contents);
	return $contents;
}


function link_unparse($contents)
{
	$contents = is_array($contents) ? $contents[0] : $contents;
	return preg_replace('`<a href="(?:/pages/)?([a-z0-9+#-]+)">(.*)</a>`sU', "[link=$1]$2[/link]", $contents);
}

?>
