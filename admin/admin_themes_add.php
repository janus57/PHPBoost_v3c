<?php

























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

##########################admin_themes_add.tpl###########################


$install = !empty($_GET['install']) ? true : false;


if ($install)
{
	
	$theme = '';
	foreach ($_POST as $key => $value)
		if ($value == $LANG['install'])
			$theme = $key;
			
	$secure = retrieve(POST, $theme . 'secure', -1);
	$activ = retrieve(POST, $theme . 'activ', 0);
		
	$check_theme = $Sql->query("SELECT theme FROM " . DB_TABLE_THEMES . " WHERE theme = '" . strprotect($theme) . "'", __LINE__, __FILE__);	
	if (empty($check_theme) && !empty($theme))
	{
		
		$info_theme = load_ini_file('../templates/' . $theme . '/config/', get_ulang());

		$Sql->query_inject("INSERT INTO " . DB_TABLE_THEMES . " (theme, activ, secure, left_column, right_column) VALUES('" . strprotect($theme) . "', '" . $activ . "', '" .  $secure . "', '" . (int)$info_theme['left_column'] . "', '" . (int)$info_theme['right_column'] . "')", __LINE__, __FILE__);
		
		
		$Cache->Generate_file('themes');
		
		$Cache->load('themes', RELOAD_CACHE);
		$Cache->Generate_file('css');

		redirect(HOST . SCRIPT); 
	}
	else
		redirect(HOST . DIR . '/admin/admin_themes_add.php?error=e_theme_already_exist#errorh');
}
elseif (!empty($_FILES['upload_theme']['name'])) 
{
	
	@clearstatcache();
	$dir = '../templates/';
	if (!is_writable($dir))
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if (is_writable($dir)) 
	{
		$check_theme = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_THEMES . " WHERE theme = '" . strprotect($_FILES['upload_theme']['name']) . "'", __LINE__, __FILE__);
		if (empty($check_theme) && !is_dir('../templates/' . $_FILES['upload_theme']['name']))
		{
			import('io/upload');
			$Upload = new Upload($dir);
			if ($Upload->file('upload_theme', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
			{					
				$archive_path = '../templates/' . $Upload->filename['upload_theme'];
				
				if ($Upload->extension['upload_theme'] == 'gzip')
				{
					import('lib/pcl/pcltar', LIB_IMPORT);
					if (!$zip_files = PclTarExtract($Upload->filename['upload_theme'], '../templates/'))
						$error = $Upload->error;
				}
				elseif ($Upload->extension['upload_theme'] == 'zip')
				{
					import('lib/pcl/pclzip', LIB_IMPORT);
					$Zip = new PclZip($archive_path);
					if (!$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../templates/', PCLZIP_OPT_SET_CHMOD, 0666))
						$error = $Upload->error;
				}
				else
					$error = 'e_upload_invalid_format';
				
				
				if (!@unlink($archive_path))
					$error = 'e_unlink_disabled';
			}
			else
				$error = 'e_upload_error';
		}
		else
			$error = 'e_upload_already_exist';
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	redirect(HOST . SCRIPT . $error);	
}
else  
{
	$Template->set_filenames(array(
		'admin_themes_add'=> 'admin/admin_themes_add.tpl'
	));
	
	$Template->assign_vars(array(
		'THEME' => get_utheme(),		
		'LANG' => get_ulang(),
		'L_THEME_ADD' => $LANG['theme_add'],	
		'L_UPLOAD_THEME' => $LANG['upload_theme'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
		'L_THEME_MANAGEMENT' => $LANG['theme_management'],
		'L_THEME' => $LANG['theme'],
		'L_PREVIEW' => $LANG['preview'],
		'L_NO_THEME_ON_SERV' => $LANG['no_theme_on_serv'],
		'L_RANK' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_XHTML' => $LANG['xhtml_version'],
		'L_CSS' => $LANG['css_version'],
		'L_MAIN_COLOR' => $LANG['main_colors'],
		'L_VARIABLE_WIDTH' => $LANG['exensible'],
		'L_WIDTH' => $LANG['width'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_INSTALL' => $LANG['install']
	));

	
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_theme_already_exist', 'e_unlink_disabled');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
		
	
	$z = 0;
	import('io/filesystem/folder');
	$tpl_array = array();
	$lang_folder_path = new Folder('../templates/');
	foreach ($lang_folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $lang)
		$tpl_array[] = $lang->get_name();
	
	
	$key = array_search('default', $tpl_array);
	if (isset($key))
		unset($tpl_array[$key]);
	
	$result = $Sql->query_while("SELECT theme 
	FROM " . DB_TABLE_THEMES . "", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		
		$key = array_search($row['theme'], $tpl_array);
		if ($key !== false)
			unset($tpl_array[$key]); 
	}
	$Sql->query_close($result);
	
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	foreach ($tpl_array as $theme_array => $value_array) 
	{
		$info_theme = load_ini_file('../templates/' . $value_array . '/config/', get_ulang());
	
		$options = '';
		for ($i = -1 ; $i <= 2 ; $i++) 
		{
			$selected = ($i == -1) ? 'selected="selected"' : '';
			$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
		}
		
		$Template->assign_block_vars('list', array(
			'IDTHEME' =>  $value_array,		
			'THEME' =>  $info_theme['name'],			
			'ICON' => $value_array,
			'VERSION' => $info_theme['version'],
			'AUTHOR' => (!empty($info_theme['author_mail']) ? '<a href="mailto:' . $info_theme['author_mail'] . '">' . $info_theme['author'] . '</a>' : $info_theme['author']),
			'AUTHOR_WEBSITE' => (!empty($info_theme['author_link']) ? '<a href="' . $info_theme['author_link'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $info_theme['info'],
			'COMPAT' => $info_theme['compatibility'],
			'HTML_VERSION' => $info_theme['html_version'],
			'CSS_VERSION' => $info_theme['css_version'],
			'MAIN_COLOR' => $info_theme['main_color'],
			'VARIABLE_WIDTH' => ($info_theme['variable_width'] ? $LANG['yes'] : $LANG['no']),
			'WIDTH' => $info_theme['width'],
			'OPTIONS' => $options
		));
		$z++;
	}

	if ($z != 0)
		$Template->assign_vars(array(		
			'C_THEME_PRESENT' => true
		));
	else
		$Template->assign_vars(array(		
			'C_NO_THEME_PRESENT' => true
		));
	
	$Template->pparse('admin_themes_add'); 
}

require_once('../admin/admin_footer.php');

?>
