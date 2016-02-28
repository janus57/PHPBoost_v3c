<?php



























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['submit']) )
{
	$editor = retrieve(POST, 'formatting_language', '');
	$CONFIG['editor'] = $editor == 'tinymce' ? 'tinymce' : 'bbcode';
	$CONFIG['html_auth'] = Authorizations::build_auth_array_from_form(1);
	$CONFIG['forbidden_tags'] = isset($_POST['forbidden_tags']) ? $_POST['forbidden_tags'] : array();
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	$Cache->Generate_file('config');
		
	redirect(HOST . SCRIPT);	
}

else	
{		
	$template = new Template('admin/admin_content_config.tpl');
	
	$j = 0;
	
	foreach (ContentFormattingFactory::get_available_tags() as $code => $name)
	{	
		$template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'CODE' => $code,
			'TAG_NAME' => $name,
			'C_ENABLED' => in_array($code, $CONFIG['forbidden_tags'])
		));
	}
	
	$template->assign_vars(array(
		'BBCODE_SELECTED' => $CONFIG['editor'] == 'bbcode' ? 'selected="selected"' : '',
		'TINYMCE_SELECTED' => $CONFIG['editor'] == 'tinymce' ? 'selected="selected"' : '',
		'SELECT_AUTH_USE_HTML' => Authorizations::generate_select(1, $CONFIG['html_auth']),
		'NBR_TAGS' => $j,


		'L_CONTENT_CONFIG' => $LANG['content_config_extend'],
		'L_DEFAULT_LANGUAGE' => $LANG['default_formatting_language'],
		'L_LANGUAGE_CONFIG' => $LANG['content_language_config'],
		'L_HTML_LANGUAGE' => $LANG['content_html_language'],
		'L_AUTH_USE_HTML' => $LANG['content_auth_use_html'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->parse(); 
}

require_once('../admin/admin_footer.php');

?>
