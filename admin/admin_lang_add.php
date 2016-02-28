<?php

























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');



$install = !empty($_GET['install']) ? true : false;
$error = retrieve(GET, 'error', '');


if ($install)
{
	
	$lang = '';
	foreach ($_POST as $key => $value)
		if ($value == $LANG['install'])
			$lang = strprotect($key);
			
	$secure = retrieve(POST, $lang . 'secure', -1);
	$activ = retrieve(POST, $lang . 'activ', 0);
		
	$check_lang = $Sql->query("SELECT lang FROM " . DB_TABLE_LANG . " WHERE lang = '" . $lang . "'", __LINE__, __FILE__);	
	if (empty($check_lang) && !empty($lang))
	{
		$Sql->query_inject("INSERT INTO " . DB_TABLE_LANG . " (lang, activ, secure) VALUES('" . $lang . "', '" . $activ . "', '" .  $secure . "')", __LINE__, __FILE__);
		
		
		$Cache->Generate_file('langs');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect(HOST . DIR . '/admin/admin_modules_add.php?error=e_lang_already_exist#errorh');
}
elseif (!empty($_FILES['upload_lang']['name'])) 
{
	
	@clearstatcache();
	$dir = '../lang/';
	if (!is_writable($dir))
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if (is_writable($dir)) 
	{
		$check_lang = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_LANG . " WHERE lang = '" . strprotect($_FILES['upload_lang']['name']) . "'", __LINE__, __FILE__);
		if (empty($check_lang))
		{
			import('io/upload');
			$Upload = new Upload($dir);
			if ($Upload->file('upload_lang', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
			{					
				$archive_path = '../lang/' . $Upload->filename['upload_lang'];
				
				if ($Upload->extension['upload_lang'] == 'gzip')
				{
					import('lib/pcl/pcltar', LIB_IMPORT);
					if (!$zip_files = PclTarExtract($Upload->filename['upload_lang'], '../lang/'))
						$error = $Upload->error;
				}
				elseif ($Upload->extension['upload_lang'] == 'zip')
				{
					import('lib/pcl/pclzip', LIB_IMPORT);
					$Zip = new PclZip($archive_path);
					if (!$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../lang/', PCLZIP_OPT_SET_CHMOD, 0666))
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
		'admin_lang_add'=> 'admin/admin_lang_add.tpl'
	));
	
	$Template->assign_vars(array(
		'THEME' => get_utheme(),		
		'LANG' => get_ulang(),
		'L_LANG_ADD' => $LANG['lang_add'],	
		'L_UPLOAD_LANG' => $LANG['upload_lang'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
		'L_LANG_MANAGEMENT' => $LANG['lang_management'],
		'L_LANG' => $LANG['lang'],
		'L_NO_LANG_ON_SERV' => $LANG['no_lang_on_serv'],
		'L_RANK' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_INSTALL' => $LANG['install']
	));
	
	
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_lang_already_exist', 'e_unlink_disabled');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
		
	
	import('io/filesystem/folder');
	$dir_array = array();
	$lang_folder_path = new Folder('../lang/');
	foreach ($lang_folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $lang)
		$dir_array[] = $lang->get_name();
	
	$result = $Sql->query_while("SELECT lang 
	FROM " . PREFIX . "lang", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		
		$key = array_search($row['lang'], $dir_array);
		if ($key !== false)
			unset($dir_array[$key]); 
	}
	$Sql->query_close($result);
	
	$z = 0;
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	foreach ($dir_array as $lang_array => $value_array) 
	{
		$options = '';
		for ($i = -1 ; $i <= 2 ; $i++) 
		{
			$selected = ($i == -1) ? 'selected="selected"' : '';
			$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
		}
		
		$info_lang = load_ini_file('../lang/', $value_array);
		$Template->assign_block_vars('list', array(
			'IDLANG' =>  $value_array,		
			'LANG' =>  $info_lang['name'],	
			'IDENTIFIER' =>  $info_lang['identifier'],
			'AUTHOR' => (!empty($info_lang['author_mail']) ? '<a href="mailto:' . $info_lang['author_mail'] . '">' . $info_lang['author'] . '</a>' : $info_lang['author']),
			'AUTHOR_WEBSITE' => (!empty($info_lang['author_link']) ? '<a href="' . $info_lang['author_link'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'COMPAT' => $info_lang['compatibility'],
			'OPTIONS' => $options
		));
		$z++;
	}

	if ($z != 0)
		$Template->assign_vars(array(		
			'C_LANG_PRESENT' => true
		));
	else
		$Template->assign_vars(array(		
			'C_NO_LANG_PRESENT' => true
		));
	
	$Template->pparse('admin_lang_add'); 
}

require_once('../admin/admin_footer.php');

?>
