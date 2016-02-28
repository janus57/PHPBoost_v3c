<?php


























function menu_themeswitcher_themeswitcher($position, $block)
{
   global $CONFIG, $THEME_CONFIG, $User, $LANG, $Session;

	$switchtheme = !empty($_GET['switchtheme']) ? urldecode($_GET['switchtheme']) : '';
    if (!empty($switchtheme))
    {
        if ($User->check_level(MEMBER_LEVEL))
        {
            $Session->csrf_get_protect();
        }
        
    	if (preg_match('`[ a-z0-9_-]{3,20}`i', $switchtheme) && strpos($switchtheme, '\'') === false)
    	{
    		$User->update_user_theme($switchtheme); 
    		if (QUERY_STRING != '')
    			redirect(trim(HOST . SCRIPT . '?' . preg_replace('`switchtheme=[^&]+`', '', QUERY_STRING), '?'));
    		else
    			redirect(HOST . SCRIPT);
    	}
    }
    
    $tpl = new Template('menus/themeswitcher/themeswitcher.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $utheme = get_utheme();
    foreach($THEME_CONFIG as $theme => $array_info)
    {
    	if ($User->check_level($array_info['secure']) && $theme != 'default')
    	{
			$selected = ($utheme == $theme) ? ' selected="selected"' : '';
    		$info_theme = @parse_ini_file(PATH_TO_ROOT . '/templates/' . $theme . '/config/' . get_ulang() . '/config.ini');
    		$tpl->assign_block_vars('themes', array(
    			'NAME' => $info_theme['name'],
    			'IDNAME' => $theme,
    			'SELECTED' => $selected
    		));
    	}
    }
    
    $tpl->assign_vars(array(
    	'DEFAULT_THEME' => $CONFIG['theme'],
    	'L_SWITCHTHEME' => 'Changer le thème',
    	'L_DEFAULT_THEME' => 'Thème par défaut',
    	'L_SUBMIT' => $LANG['submit']
    ));
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}

?>
