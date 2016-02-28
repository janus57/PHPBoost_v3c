<?php



























require_once('../kernel/begin.php');
load_module_lang('search');
define('ALTERNATIVE_CSS', 'search');

$Template->set_filenames(array(
    'search_forms' => 'search/search_forms.tpl',
    'search_results' => 'search/search_results.tpl'
));



$search = retrieve(REQUEST, 'q', '');
$unsecure_search = stripslashes(retrieve(REQUEST, 'q', ''));
$search_in = retrieve(POST, 'search_in', 'all');
$selected_modules = retrieve(POST, 'searched_modules', array());
$query_mode = retrieve(POST, 'query_mode', true);


define('TITLE', $LANG['title_search']);

require_once('../kernel/header.php');
$Template->assign_vars(Array(
    'L_TITLE_SEARCH' => TITLE,
    'L_SEARCH' => $LANG['title_search'],
    'TEXT_SEARCHED' => !empty($unsecure_search) ? $unsecure_search : $LANG['search'] . '...',
    'L_SEARCH_ALL' => $LANG['search_all'],
    'L_SEARCH_KEYWORDS' => $LANG['search_keywords'],
    'L_SEARCH_MIN_LENGTH' => $LANG['search_min_length'],
    'L_SEARCH_IN_MODULES' => $LANG['search_in_modules'],
    'L_SEARCH_IN_MODULES_EXPLAIN' => $LANG['search_in_modules_explain'],
    'L_SEARCH_SPECIALIZED_FORM' => $LANG['search_specialized_form'],
    'L_SEARCH_SPECIALIZED_FORM_EXPLAIN' => $LANG['search_specialized_form_explain'],
    'L_WARNING_LENGTH_STRING_SEARCH' => to_js_string($LANG['warning_length_string_searched']),
    'L_FORMS' => $LANG['forms'],
    'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
    'L_SIMPLE_SEARCH' => $LANG['simple_search'],
    'U_FORM_VALID' => url('../search/search.php#results'),
    'C_SIMPLE_SEARCH' => $search_in == 'all' ? true : false,
    'SEARCH_MODE_MODULE' => $search_in
));



import('modules/modules_discovery_service');
require_once('../search/search.inc.php');



$modules = new ModulesDiscoveryService();
$modules_args = array();
$used_modules = array();


$search_module = $modules->get_available_modules('get_search_request');


foreach ($search_module as $module)
{
	if (!in_array($module->get_id(), $SEARCH_CONFIG['unauthorized_modules']))
	{
	    
	    $modules_args[$module->get_id()]['search'] = $search;
	    if ($module->has_functionality('get_search_args'))
	    {
	        
	        $form_module_args = $module->functionality('get_search_args');
	        
	        
	        if ($search_in != 'all')
	        {
	            foreach ($form_module_args as $arg)
	            {
	                if ($arg == 'search')
	                {   
	                    $modules_args[$module->get_id()]['search'] = $search;
	                }
	                elseif (isset($_POST[$arg]))
	                {   
	                    $modules_args[$module->get_id()][$arg] = $_POST[$arg];
	                }
	            }
	        }
	        
	        $Template->assign_block_vars('forms', array(
	            'MODULE_NAME' => $module->get_id(),
	            'L_MODULE_NAME' => ucfirst($module->get_name()),
	            'C_SEARCH_FORM' => true,
	            'SEARCH_FORM' => $module->functionality('get_search_form', $modules_args[$module->get_id()])
	        ));
	    }
	    else
	    {
	        $Template->assign_block_vars('forms', array(
	            'MODULE_NAME' => $module->get_id(),
	            'L_MODULE_NAME' => ucfirst($module->get_name()),
	            'C_SEARCH_FORM' => false,
	            'SEARCH_FORM' => $LANG['search_no_options']
	        ));
	    }
	    
	    
	    if ( ($selected_modules === array()) || ($search_in === $module->get_id()) ||
	    	(($search_in === 'all') && (in_array($module->get_id(), $selected_modules))) )
	    {
	        $selected = ' selected="selected"';
	        $used_modules[$module->get_id()] = $module; 
	    }
	    else
	    {
	    	$selected = '';
	    }
	    
	    $Template->assign_block_vars('searched_modules', array(
	        'MODULE' => $module->get_id(),
	        'L_MODULE_NAME' => ucfirst($module->get_name()),
	        'SELECTED' => $selected
	    ));
	}
	else
	{
		unset($module);
	}
}


$Template->pparse('search_forms');

if (!empty($search))
{
    $results = array();
    $idsSearch = array();
    
    if ( $search_in != 'all' ) 
    {
        if (isset($used_modules[$search_in]) && isset($modules_args[$search_in]))
        {
            $used_modules = array($search_in => $used_modules[$search_in]);
            $modules_args = array($search_in => $modules_args[$search_in]);
        }
        else
        {
            $used_modules = array();
            $modules_args = array();
        }
    }
    else
    {   
        foreach ($modules_args as $module_id => $module_args)
        {
            if (!$query_mode && (!in_array($module_id, $selected_modules) || !isset($modules_args[$module_id])))
            {
                unset($modules_args[$module_id]);
                unset($used_modules[$module_id]);
            }
        }
    }
    
    $nbResults = get_search_results($search, $used_modules, $modules_args, $results, $idsSearch);
    
    foreach ($used_modules as $module)
    {
        $Template->assign_block_vars('results', array(
            'MODULE_NAME' => $module->get_id(),
            'L_MODULE_NAME' => ucfirst($module->get_name()),
            'ID_SEARCH' => $idsSearch[$module->get_id()]
        ));
    }
    
    $all_html_result = '';
    if ( $nbResults > 0 )
        get_html_results($results, $all_html_result, $search_in);
    
    $Template->assign_vars(Array(
        'NB_RESULTS_PER_PAGE' => NB_RESULTS_PER_PAGE,
        'L_TITLE_ALL_RESULTS' => $LANG['title_all_results'],
        'L_RESULTS' => $LANG['results'],
        'L_RESULTS_CHOICE' => $LANG['results_choice'],
        'L_PRINT' => $LANG['print'],
        'L_NB_RESULTS_FOUND' => $nbResults > 1 ? $LANG['nb_results_found'] : ($nbResults == 0 ? $LANG['no_results_found'] : $LANG['one_result_found']),
        'L_SEARCH_RESULTS' => $LANG['search_results'],
        'NB_RESULTS' => $nbResults,
        'ALL_RESULTS' => $all_html_result,
        'SEARCH_IN' => $search_in,
        'C_SIMPLE_SEARCH' => ($search_in == 'all') ? true : false
    ));
    
    
    $Template->pparse('search_results');
}


require_once('../kernel/footer.php');

?>
