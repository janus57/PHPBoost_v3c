<?php


























if (defined('PHPBOOST') !== true) exit;


$login = retrieve(POST, 'login', '');
$password = retrieve(POST, 'password', '', TSTRING_UNCHANGE);
$autoconnexion = retrieve(POST, 'auto', false);
$unlock = strhash(retrieve(POST, 'unlock', '', TSTRING_UNCHANGE));

if (retrieve(GET, 'disconnect', false)) 
{
    $Session->end();
    redirect(get_start_page());
}



if (retrieve(POST, 'connect', false) && !empty($login) && !empty($password))
{
	$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "' AND level = 2", __LINE__, __FILE__);
	if (!empty($user_id)) 
	{
		$info_connect = $Sql->query_array(DB_TABLE_MEMBER, 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__);
		$delay_connect = (time() - $info_connect['last_connect']); 
		$delay_ban = (time() - $info_connect['user_ban']); 
		
		if ($delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100') 
		{
			
			if ($info_connect['test_connect'] < '5' || $unlock === $CONFIG['unlock_admin']) 
			{
				$error_report = $Session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); 
			}
			elseif ($delay_connect >= 600 && $info_connect['test_connect'] == '5') 
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); 
				$error_report = $Session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); 
			}
			elseif ($delay_connect >= 300 && $info_connect['test_connect'] == '5') 
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); 
				$error_report = $Session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); 
			}
			else 
				redirect(HOST . DIR . '/admin/admin_index.php?flood=0');
		}
		elseif ($info_connect['user_aprob'] == '0')
			redirect(HOST . DIR . '/member/error.php?activ=1');
		elseif ($info_connect['user_warning'] == '100')
			redirect(HOST . DIR . '/member/error.php?ban_w=1');
		else
		{
			$delay_ban = ceil((0 - $delay_ban)/60);
			redirect(HOST . DIR . '/member/error.php?ban=' . $delay_ban);
		}
		
		if (!empty($error_report)) 
		{
			$info_connect['test_connect']++;
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = test_connect + 1 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
			redirect(HOST . DIR . '/admin/admin_index.php?flood=' . $info_connect['test_connect']);
		}
		elseif (!empty($unlock) && $unlock !== $CONFIG['unlock_admin'])
		{
			$Session->end(); 
			redirect(HOST . DIR . '/admin/admin_index.php?flood=0');
		}
		else 
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = 0 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__); 
	}
	else
		redirect(HOST . DIR . '/member/error.php?unexist=1');
	
	redirect(HOST . SCRIPT);
}

if (!$User->check_level(ADMIN_LEVEL))
{
	$Template->set_filenames(array(
		'admin_connect'=> 'admin/admin_connect.tpl'
	));
	
	$Template->assign_vars(array(
		'L_XML_LANGUAGE' => $LANG['xml_lang'],
		'SITE_NAME' => $CONFIG['site_name'],
		'TITLE' => TITLE,
		'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
		'SID' => SID,
		'LANG' => get_ulang(),
		'THEME' => get_utheme(),
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_PASSWORD' => $LANG['require_password'],
		'L_CONNECT' => $LANG['connect'],
		'L_ADMIN' => $LANG['admin'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_AUTOCONNECT'	=> $LANG['autoconnect']	
	));
	
	$Template->pparse('admin_connect'); 
	exit;
}
elseif (isset($_GET['flood']))
{
	$flood = numeric($_GET['flood']);
	if ($flood == '0')
	{
		$Template->assign_block_vars('unlock', array(
		));
	}
	
	$Template->set_filenames(array(
		'admin_connect'=> 'admin/admin_connect.tpl'
	));
	
		$Template->assign_vars(array(
		'L_XML_LANGUAGE' => $LANG['xml_lang'],
		'SITE_NAME' => $CONFIG['site_name'],
		'TITLE' => TITLE,
		'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
		'SID' => SID,
		'LANG' => get_ulang(),
		'THEME' => get_utheme(),
		'ERROR' => (($flood > '0') ? sprintf($LANG['flood_block'], $flood) : $LANG['flood_max']),
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_PASSWORD' => $LANG['require_password'],
		'L_CONNECT' => $LANG['connect'],
		'L_ADMIN' => $LANG['admin'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_UNLOCK' => $LANG['unlock_admin_panel'],
		'L_AUTOCONNECT'	=> $LANG['autoconnect']	
	));

	$Template->pparse('admin_connect'); 
	exit;
}

?>
