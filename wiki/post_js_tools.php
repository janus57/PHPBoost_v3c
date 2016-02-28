<?php


























if (defined('PHPBOOST') !== true) exit;


$Template->set_filenames(array('post_js_tools'=> 'wiki/wiki_js_tools.tpl'));

$Template->assign_vars(array(
	'L_PLEASE_ENTER_A_TITLE' => $LANG['wiki_please_enter_a_link_name'],
	'L_INSERT_LINK' => $LANG['wiki_insert_a_link'],
	'L_INSERT' => $LANG['wiki_insert_link'],
	'L_TITLE_LINK' => $LANG['wiki_title_link'],
	'L_NO_JS' => $LANG['wiki_no_js_insert_link'],
	'L_EXPLAIN_PARAGRAPH_1' => sprintf($LANG['wiki_explain_paragraph'], 1),
	'L_EXPLAIN_PARAGRAPH_2' => sprintf($LANG['wiki_explain_paragraph'], 2),
	'L_EXPLAIN_PARAGRAPH_3' => sprintf($LANG['wiki_explain_paragraph'], 3),
	'L_EXPLAIN_PARAGRAPH_4' => sprintf($LANG['wiki_explain_paragraph'], 4),
	'L_EXPLAIN_PARAGRAPH_5' => sprintf($LANG['wiki_explain_paragraph'], 5),
	'L_HELP_WIKI_TAGS' => $LANG['wiki_help_tags'],
	'L_PARAGRAPH_NAME' => $LANG['wiki_paragraph_name'],
	'PARAGRAPH_NAME' => $LANG['wiki_paragraph_name_example'],
	'WIKI_PATH' => $Template->get_module_data_path('wiki')
));


?>
