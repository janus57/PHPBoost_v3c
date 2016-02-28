<?php


























if (defined('PHPBOOST') !== true)	exit;

function newsletter_mini($position, $block)
{
    global  $LANG, $User;
    
    load_module_lang('newsletter');
    
    $tpl = new Template('newsletter/newsletter_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $tpl->assign_vars(array(
    	'SUBSCRIBE' => $LANG['subscribe'],
    	'UNSUBSCRIBE' => $LANG['unsubscribe'],
    	'USER_MAIL' => ($User->get_attribute('user_mail') != '') ? $User->get_attribute('user_mail') : '',
    	'L_NEWSLETTER' => $LANG['newsletter'],
    	'L_SUBMIT' => $LANG['submit'],
    	'L_ARCHIVES' => $LANG['archives']
    ));
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>
