<?php


























if (defined('PHPBOOST') !== true) exit;

function search_mini($position, $block)
{
    global $LANG;
    load_module_lang('search');
    
    $search = retrieve(REQUEST, 'q', '');
    
    $tpl = new Template('search/search_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    $tpl->assign_vars(Array(
        'TITLE_SEARCH' => TITLE,
        'SEARCH' => $LANG['title_search'],
        'TEXT_SEARCHED' => !empty($search) ? stripslashes(retrieve(REQUEST, 'q', '')) : $LANG['search'] . '...',
        'WARNING_LENGTH_STRING_SEARCH' => addslashes($LANG['warning_length_string_searched']),
    	'L_SEARCH' => $LANG['search'],
        'U_FORM_VALID' => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
        'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
        'U_ADVANCED_SEARCH' => url(TPL_PATH_TO_ROOT . '/search/search.php'),
    ));
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}

?>
