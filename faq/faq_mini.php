<?php


























if (defined('PHPBOOST') !== true) exit;

function faq_mini($position, $block)
{
    global $Cache, $Template, $FAQ_LANG, $FAQ_CATS, $RANDOM_QUESTIONS;
    
    load_module_lang('faq');
    $Cache->load('faq'); 
    
    include_once(PATH_TO_ROOT . '/faq/faq_begin.php');
    include_once(PATH_TO_ROOT . '/faq/faq_cats.class.php');
    
    $tpl = new Template('faq/faq_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $no_random_question = array(
    	'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
    	'FAQ_QUESTION' => $FAQ_LANG['no_random_question'],
    	'U_FAQ_QUESTION' => TPL_PATH_TO_ROOT . '/faq/' . url('faq.php')
    );
    
    
    if (empty($RANDOM_QUESTIONS))
    {
    	$tpl->assign_vars($no_random_question);
    	return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    $random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];
    
    $faq_cats = new FaqCats();
    
    $i = 0;
    
    
    
    while (!$faq_cats->check_auth($random_question['idcat']) && $i < 5)
    {
    	$random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];
    	$i++;
    }
    
    
    if ($i < 5 && !empty($random_question['question']))
    {
    	$tpl->assign_vars(array(
    		'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
    		'FAQ_QUESTION' => $random_question['question'],
    		'U_FAQ_QUESTION' => PATH_TO_ROOT . '/faq/' . ($random_question['idcat'] > 0 ? url('faq.php?id=' . $random_question['idcat'] . '&amp;question=' . $random_question['id'], 'faq-' . $random_question['idcat'] . '+' . url_encode_rewrite($FAQ_CATS[$random_question['idcat']]['name']) . '.php?question=' . $random_question['id']) . '#q' . $random_question['id'] : url('faq.php?question=' . $random_question['id'], 'faq.php?question=' . $random_question['id']) . '#q' . $random_question['id'])
    	));
    }
    
    else
    {
    	$tpl->assign_vars($no_random_question);
    }
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>
