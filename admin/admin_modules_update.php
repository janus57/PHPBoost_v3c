<?php

























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$update = !empty($_GET['update']) ? true : false;

if ($update) 
{	
	
	$module_name = retrieve(GET, 'update', '');
	if ( empty($module_name) ) {
		foreach ($_POST as $key => $value)
			if ($value == $LANG['update_module'])
				$module_name = $key;
		
		$activ_module = retrieve(POST, $module_name . 'activ', 0);
	}
	
	$ckeck_module = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MODULES . " WHERE name = '" . strprotect($module_name) . "'", __LINE__, __FILE__);
	
	
	if (!empty($ckeck_module))
	{
		
		$info_module = load_ini_file('../' . $module_name . '/lang/', get_ulang());
		
		
		$previous_version = $Sql->query("SELECT version FROM " . DB_TABLE_MODULES . " WHERE name = '" . strprotect($module_name) . "'", __LINE__, __FILE__);
	
		
		$dir_db_module = get_ulang();
		$dir = '../' . $module_name . '/db';
		
		import('io/filesystem/folder');
		$folder_path = new Folder($dir . '/' . $dir_db_module);
		foreach ($folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $dir)
		{	
			$dir_db_module = $dir->get_name();
			break;
		}

		
		$filesupdate = array();
		$dir_db = '../' . urldecode($module_name) . '/db/' . $dir_db_module . '/';
		$folder_path = new Folder($dir_db);
		foreach ($folder_path->get_files('`.*\.(php|sql)$`i') as $files)
		{	
			$file = $files->get_name();
			if (strpos($file, DBTYPE) !== false)
			{
				$array_info = explode('_', $file);
				if (isset($array_info[1]) && version_compare($info_module['version'], $array_info[1], '>=') && version_compare($previous_version, $array_info[1], '<'))
					$filesupdate[$array_info[1]] = $file;		
			}
		}
		
		
		uksort($filesupdate, 'version_compare');
		
		
		$_GET['filesupdate_errors'] = false;
		foreach ($filesupdate as $key => $module_update_name)
		{	
			if (strpos($module_update_name, '.php') !== false) 
				@include_once($dir_db . $module_update_name);
			else 
				$Sql->parse($dir_db . $module_update_name, PREFIX);
		}
		
		
		$Cache->generate_module_file($module_name, NO_FATAL_ERROR_CACHE);

		
		$Sql->query_inject("UPDATE " . DB_TABLE_MODULES . " SET version = '" . $info_module['version'] . "' WHERE name = '" . $module_name . "'", __LINE__, __FILE__);
		
		
		$Cache->Generate_file('modules');
		$Cache->Generate_file('menus');
		$Cache->Generate_file('css');
		
		
		if ($CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']))
			$Cache->Generate_file('htaccess'); 
		
		if (empty($_GET['filesupdate_errors']))
			redirect(HOST . SCRIPT . '?s=1');	
		unset($_GET['filesupdate_errors']);
	}
	else
		redirect(HOST . SCRIPT . '?error=incomplete#errorh');
}			
elseif (!empty($_FILES['upload_module']['name'])) 
{
	$ext_name = strrchr($_FILES['upload_module']['name'], '.');
	$module_name = '';
	if ($ext_name !== FALSE)
		$module_name = str_replace($ext_name, '', $_FILES['upload_module']['name']);
	
	
	@clearstatcache();
	$dir = '../';
	if (!is_writable($dir))
		@chmod($dir, 0755);
	if (!is_writable($dir . $module_name))
		@chmod($dir . $module_name, 0755);

	@clearstatcache();
	$error = '';
	if (is_writable($dir) && is_writable($dir . $module_name)) 
	{
		if (!is_dir('../' . $_FILES['upload_module']['name']))
		{
			import('io/upload');
			$Upload = new Upload($dir);
			if ($Upload->file('upload_module', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
			{					
				$archive_path = '../' . $Upload->filename['upload_module'];
				
				if ($Upload->extension['upload_module'] == 'gzip')
				{
					import('lib/pcl/pcltar', LIB_IMPORT);
					if (!$zip_files = PclTarExtract($Upload->filename['upload_module'], '../'))
						$error = $Upload->error;
				}
				elseif ($Upload->extension['upload_module'] == 'zip')
				{
					import('lib/pcl/pclzip', LIB_IMPORT);
					$Zip = new PclZip($archive_path);
					if (!$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_SET_CHMOD, 0666))
						$error = $Upload->error;
				}
				else
					$error = 'e_upload_invalid_format';
				
				
				if (!@unlink($archive_path))
					$error = ''; 
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
	
	if (!empty($error)) {
		redirect(HOST . SCRIPT . $error);	
	} else {
		redirect(HOST . SCRIPT . '?update=' . $module_name);	
	}
}
else
{			
	$Template->set_filenames(array(
		'admin_modules_update'=> 'admin/admin_modules_update.tpl'
	));

    {   
        import('core/updates');
        $updates_availables = 0;

        if (phpversion() > PHP_MIN_VERSION_UPDATES)
        {
            
            import('events/administrator_alert_service');
            import('core/application');
            $update_alerts = AdministratorAlertService::find_by_criteria(null, 'updates');
            
            $updates = array();
            $update_type = 'module';
            foreach ($update_alerts as $update_alert)
            {
                
                $update = unserialize($update_alert->get_properties());
                if ($update_type == '' || $update->get_type() == $update_type)
                    $updates[] = $update;
            }

            foreach ($updates as $update)
            {
                switch ($update->get_priority())
                {
                    case ADMIN_ALERT_VERY_HIGH_PRIORITY:
                        $priority = 'priority_very_high';
                        break;
                    case ADMIN_ALERT_HIGH_PRIORITY:
                        $priority = 'priority_high';
                        break;
                    case ADMIN_ALERT_MEDIUM_PRIORITY:
                        $priority = 'priority_medium';
                        break;
                    default:
                        $priority = 'priority_low';
                        break;
                }
                
                $short_description = $update->get_description();
                $maxlength = 300;
                $length = strlen($short_description) > $maxlength ?  $maxlength + strpos(substr($short_description, $maxlength), ' ') : 0;
                $length = $length > ($maxlength * 1.1) ? $maxlength : $length;
                
                $Template->assign_block_vars('apps', array(
                    'type' => $update->get_type(),
                    'name' => $update->get_name(),
                    'version' => $update->get_version(),
                    'short_description' => ($length > 0 ? substr($short_description, 0, $length) . '...' : $short_description),
                    'identifier' => $update->get_identifier(),
                    'L_PRIORITY' => $LANG[$priority],
                    'priority_css_class' => 'row_' . $priority,
                    'download_url' => $update->get_download_url(),
                    'update_url' => $update->get_update_url()
                ));
            }
            
            if ($updates_availables = (count($updates) > 0))
            {
                $Template->assign_vars(array(
                    'L_UPDATES_ARE_AVAILABLE' => $LANG['updates_are_available'],
                    'L_AVAILABLES_UPDATES' => $LANG['availables_updates'],
                    'L_TYPE' => $LANG['type'],
                    'L_DESCRIPTION' => $LANG['description'],
                    'L_PRIORITY' => $LANG['priority'],
                    'L_UPDATE_DOWNLOAD' => $LANG['app_update__download'],
                    'L_NAME' => $LANG['name'],
                    'L_VERSION' => $LANG['version'],
                    'L_MORE_DETAILS' => $LANG['more_details'],
                    'L_DETAILS' => $LANG['details'],
                    'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
                    'L_DOWNLOAD_THE_COMPLETE_PACK' => $LANG['download_the_complete_pack'],
                    'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
                    'L_DOWNLOAD_THE_UPDATE_PACK' => $LANG['download_the_update_pack'],
                    'C_ALL' => $update_type == ''
                ));
                
            }
            else
            {
                $Template->assign_vars(array('L_NO_AVAILABLES_UPDATES' => $LANG['no_available_update']));
            }
        }
        else
        {
            $Template->assign_vars(array(
                'L_INCOMPATIBLE_PHP_VERSION' => sprintf($LANG['incompatible_php_version'], PHP_MIN_VERSION_UPDATES),
                'C_INCOMPATIBLE_PHP_VERSION' => true,
            ));
        }

        $Template->assign_vars(array(
            'L_WEBSITE_UPDATES' => $LANG['website_updates'],
            'L_KERNEL' => $LANG['kernel'],
            'L_MODULES' => $LANG['modules'],
            'L_THEMES' => $LANG['themes'],
            'C_UPDATES' => $updates_availables
        ));
    }

	
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$get_error = retrieve(GET, 's', 0, TINTEGER);
	if ($get_error == 1)
		$Errorh->handler($LANG['admin_module_update_success'], E_USER_SUCCESS);
		
	$Template->assign_vars(array(
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_UPLOAD_MODULE' => $LANG['upload_module'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
	));
		
	$Template->pparse('admin_modules_update'); 
}

require_once('../admin/admin_footer.php');

?>
