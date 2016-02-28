<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');

load_module_lang('search');



$search_txt = retrieve(POST, 'q', '');
$module_id = strtolower(retrieve(POST, 'moduleName', ''));
$id_search = retrieve(POST, 'idSearch', -1);
$selected_modules = retrieve(POST, 'searched_modules', array());

import('modules/modules_discovery_service');
require_once(PATH_TO_ROOT . '/search/search.inc.php');




$modules = new ModulesDiscoveryService();
$modules_args = array();

if (($id_search >= 0) && ($module_id != ''))
{
    echo 'var syncErr = false;';
    
    $search = new Search();
    if (!$search->is_search_id_in_cache($id_search))
    {   
        
        $search_modules = array();
        $all_search_modules = $modules->get_available_modules('get_search_request');
        foreach ($all_search_modules as $search_module)
        {
            if (in_array($search_module->get_id(), $selected_modules))
                $search_modules[] = $search_module;
        }
        
        
        $forms_module = $modules->get_available_modules('get_search_form', $search_modules);
        
        
        foreach ($search_modules as $module)
            $modules_args[$module->get_id()] = array('search' => $search_txt);
        
        
        foreach ($forms_module as $form_module)
        {
            if ($form_module->has_functionality('get_search_args'))
            {
                
                $form_module_args = $form_module->functionality('get_search_args');
                
                
                foreach ($form_module_args as $arg)
                {
                    if ( isset($_POST[$arg]) )
                        $modules_args[$form_module->get_id()][$arg] = $_POST[$arg];
                }
            }
        }
        
        $results = array();
        $ids_search = array();
        
        get_search_results($search_txt, $search_modules, $modules_args, $results, $ids_search, true);
        
        if (empty($ids_search[$module_id]))
        {
            $ids_search[$module_id] = 0;
        }
        
        
        foreach ( $ids_search as $module_name => $id_search )
        {
            $search->id_search[$module_name] = $id_search;
            echo 'idSearch[\'' . $module_name . '\'] = ' . $id_search . ';';
        }
    }
    else
    {
        $search->id_search[$module_id] = $id_search;
    }
    echo   'var resultsAJAX = new Array();';
    $nb_results = $search->get_results_by_id($results, $search->id_search[$module_id]);;
    if ($nb_results > 0)
    {
        $module = $modules->get_module($module_id);
        $html_results = '';
        get_html_results($results, $html_results, $module_id);
    
        echo   'nbResults[\'' . $module_id . '\'] = ' . $nb_results . ';
                resultsAJAX[\'nbResults\'] = \'' . $nb_results . ' '.addslashes($nb_results > 1 ? $LANG['nb_results_found'] : $LANG['one_result_found']) . '\';
                resultsAJAX[\'results\'] = \''.str_replace(array("\r", "\n", '\''), array('', ' ', '\\\''), $html_results) . '\';';
    }
    else
    {
        echo   'nbResults[\'' . $module_id . '\'] = 0;
                resultsAJAX[\'nbResults\'] = \''.addslashes($LANG['no_results_found']) . '\';
                resultsAJAX[\'results\'] = \'\';';
    }
}
else
{
    echo 'var syncErr = true;';
}

?>
