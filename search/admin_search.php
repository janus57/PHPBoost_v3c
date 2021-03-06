<?php



























require_once('../admin/admin_begin.php');


load_module_lang('search'); 
define('TITLE', $LANG['administration']);


require_once('../admin/admin_header.php');


$clearOutCache = !empty($_GET['clear']) ? true : false;
$weighting = retrieve(GET, 'weighting', false);

$Cache->load('search');


if (!empty($_POST['valid']))
{
    if (!$weighting)
    {
        
        $CONFIG['search_cache_time'] = retrieve(POST, 'cache_time', 15);
        $CONFIG['search_max_use'] = retrieve(POST, 'max_use', 200);

        
        if (!is_array($SEARCH_CONFIG))
        $SEARCH_CONFIG = array();
        $SEARCH_CONFIG['nb_results_per_page'] = retrieve(POST, 'nb_results_p', 15);
        $SEARCH_CONFIG['unauthorized_modules'] = retrieve(POST, 'authorized_modules', array());
        
        
        $config_string = addslashes(serialize($CONFIG));
        $request = "UPDATE " . DB_TABLE_CONFIGS . " SET value = '".$config_string."' WHERE name = 'config'";
        $Sql->query_inject($request, __LINE__, __FILE__);

        
        $search_cfg = addslashes(serialize($SEARCH_CONFIG));
        $request = "UPDATE " . DB_TABLE_CONFIGS . " SET value = '".$search_cfg."' WHERE name = 'search'";
        $Sql->query_inject($request, __LINE__, __FILE__);

        
        $Cache->Generate_file('config');
        $Cache->Generate_module_file('search');

        redirect(HOST . SCRIPT);
    }
    else
    {
		$SEARCH_CONFIG['modules_weighting'] = array();
		import('modules/modules_discovery_service');
		$Modules = new ModulesDiscoveryService();
		$searchModules = $Modules->get_available_modules('get_search_request');

        
		foreach ($searchModules as $module)
		{
			if (!in_array($module->get_id(), $SEARCH_CONFIG['unauthorized_modules']))
			{
				$SEARCH_CONFIG['modules_weighting'][$module->get_id()] = retrieve(POST, $module->get_id(), 1);
			}
		}

        
        $search_cfg = addslashes(serialize($SEARCH_CONFIG));
        $request = "UPDATE " . DB_TABLE_CONFIGS . " SET value = '".$search_cfg."' WHERE name = 'search'";
        $Sql->query_inject($request, __LINE__, __FILE__);

        
        $Cache->Generate_module_file('search');

        redirect(HOST . SCRIPT . '?weighting=true');
    }
}
elseif ($clearOutCache) 
{
    $Sql->query_inject("TRUNCATE " . PREFIX . "search_results", __LINE__, __FILE__);
    $Sql->query_inject("TRUNCATE " . PREFIX . "search_index", __LINE__, __FILE__);
    redirect(HOST.SCRIPT);
}
else
{
    $Tpl = new Template('search/admin_search.tpl');

    import('modules/modules_discovery_service');

    $Tpl->assign_vars(array(
        'THEME' => get_utheme(),
        'L_SEARCH_MANAGEMENT' => $LANG['search_management'],
        'L_SEARCH_CONFIG' => $LANG['search_config'],
        'L_SEARCH_CONFIG_WEIGHTING' => $LANG['search_config_weighting'],
        'L_UPDATE' => $LANG['update'],
        'L_RESET' => $LANG['reset'],
        'C_WEIGHTING' => $weighting
    ));

    if (!$weighting)
    {
        $SEARCH_CONFIG['search_cache_time'] = isset($CONFIG['search_cache_time']) ? $CONFIG['search_cache_time'] : 15;
        $SEARCH_CONFIG['search_max_use'] = isset($CONFIG['search_max_use']) ? $CONFIG['search_max_use'] : 200;
        $SEARCH_CONFIG['nb_results_per_page'] = isset($SEARCH_CONFIG['nb_results_per_page']) ? $SEARCH_CONFIG['nb_results_per_page'] : 15;
        $SEARCH_CONFIG['unauthorized_modules'] = isset($SEARCH_CONFIG['unauthorized_modules']) && is_array($SEARCH_CONFIG['unauthorized_modules']) ? $SEARCH_CONFIG['unauthorized_modules'] : array();

        $Modules = new ModulesDiscoveryService();
        $searchModules = $Modules->get_available_modules('get_search_request');

        foreach ($searchModules as $module)
        {
            if ( in_array($module->get_id(), $SEARCH_CONFIG['unauthorized_modules']) )
            $selected = ' selected="selected"';
            else
            $selected = '';

            $Tpl->assign_block_vars('authorized_modules', array(
                'MODULE' => $module->get_id(),
                'SELECTED' => $selected,
                'L_MODULE_NAME' => ucfirst($module->get_name())
            ));
        }

        $Tpl->assign_vars(array(
            'L_CACHE_TIME' => $LANG['cache_time'],
            'L_CACHE_TIME_EXPLAIN' => $LANG['cache_time_explain'],
            'L_NB_RESULTS_P' => $LANG['nb_results_per_page'],
            'L_MAX_USE' => $LANG['max_use'],
            'L_MAX_USE_EXPLAIN' => $LANG['max_use_explain'],
            'L_CLEAR_OUT_CACHE' => $LANG['clear_out_cache'],
            'L_AUTHORIZED_MODULES' => $LANG['unauthorized_modules'],
            'L_AUTHORIZED_MODULES_EXPLAIN' => $LANG['unauthorized_modules_explain'],
            'L_SEARCH_CACHE' => $LANG['search_cache'],
            'CACHE_TIME' => $SEARCH_CONFIG['search_cache_time'],
            'MAX_USE' => $SEARCH_CONFIG['search_max_use'],
            'NB_RESULTS_P' => $SEARCH_CONFIG['nb_results_per_page']
        ));
    }
    else
    {
        $modules = new ModulesDiscoveryService();
        $all_modules = $modules->get_available_modules('get_search_request');
        $authorized_modules = array_diff(array_keys($all_modules), $SEARCH_CONFIG['unauthorized_modules']);
        foreach ($authorized_modules as $module_id)
        {
            $module = $all_modules[$module_id];
            if (!$module->got_error())
            {
                $Tpl->assign_block_vars('weights', array(
                    'MODULE' => $module->get_id(),
                    'L_MODULE_NAME' => ucfirst($module->get_name()),
                    'WEIGHT' => (!empty($SEARCH_CONFIG['modules_weighting']) && !empty($SEARCH_CONFIG['modules_weighting'][$module->get_id()])) ? $SEARCH_CONFIG['modules_weighting'][$module->get_id()] : 1
                ));
            }
        }

        $Tpl->assign_vars(array(
            'L_MODULES' => $LANG['modules'],
            'L_WEIGHTS' => $LANG['search_weights'],
            'L_SEARCH_CONFIG_WEIGHTING_EXPLAIN' => $LANG['search_config_weighting_explain']
        ));
    }

    $Tpl->parse();
}


require_once('../admin/admin_footer.php');

?>
