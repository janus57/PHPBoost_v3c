<?php



























require_once('../admin/admin_begin.php');
load_module_lang('wiki');
define('TITLE', $LANG['administration'] . ' : ' . $LANG['wiki']);
require_once('../admin/admin_header.php');
include_once('../wiki/wiki_functions.php');

$Cache->load('wiki');

$wiki_name = strprotect(retrieve(POST, 'wiki_name', $LANG['wiki'], TSTRING_AS_RECEIVED), HTML_PROTECT, ADDSLASHES_NONE);
$index_text = stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED)));
$last_articles = retrieve(POST, 'last_articles', 0);
$display_cats = !empty($_POST['display_cats']) ? 1 : 0;
$count_hits = !empty($_POST['count_hits']) ? 1 : 0;

if (!empty($_POST['update']))  
{
	$_WIKI_CONFIG['wiki_name'] = $wiki_name;
	$_WIKI_CONFIG['last_articles'] = $last_articles;
	$_WIKI_CONFIG['display_cats'] = $display_cats;
	$_WIKI_CONFIG['index_text'] = $index_text;
	$_WIKI_CONFIG['count_hits'] = $count_hits;
	$_WIKI_CONFIG['auth'] = serialize($_WIKI_CONFIG['auth']);

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($_WIKI_CONFIG)) . "' WHERE name = 'wiki'", __LINE__, __FILE__);
	
	$Cache->Generate_module_file('wiki');	
}

$Cache->load('wiki');

$Template->set_filenames(array(
	'wiki_config'=> 'wiki/admin_wiki.tpl'
));


$content_editor = new ContentFormattingFactory(BBCODE_LANGUAGE);
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$Template->assign_vars(array(
	'KERNEL_EDITOR' => $editor->display(),
	'HITS_SELECTED' => ($_WIKI_CONFIG['count_hits'] > 0) ? 'checked="checked"' : '',
	'WIKI_NAME' => $_WIKI_CONFIG['wiki_name'],
	'NOT_DISPLAY_CATS' => ( $_WIKI_CONFIG['display_cats'] == 0 ) ? 'checked="checked"' : '',
	'DISPLAY_CATS' => ( $_WIKI_CONFIG['display_cats'] != 0 ) ? 'checked="checked"' : '',
	'LAST_ARTICLES' => $_WIKI_CONFIG['last_articles'],
	'DESCRIPTION' => wiki_unparse($_WIKI_CONFIG['index_text']),
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
	'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
	'L_CONFIG_WIKI' => $LANG['wiki_config'],
	'L_WHOLE_WIKI' => $LANG['wiki_config_whole'],
	'L_INDEX_WIKI' => $LANG['wiki_index'],
	'L_COUNT_HITS' => $LANG['wiki_count_hits'], 
	'L_WIKI_NAME' => $LANG['wiki_name'],
	'L_DISPLAY_CATS' => $LANG['wiki_display_cats'],
	'L_NOT_DISPLAY' => $LANG['wiki_no_display'],
	'L_DISPLAY' => $LANG['wiki_display'],
	'L_LAST_ARTICLES' => $LANG['wiki_last_articles'],
	'L_LAST_ARTICLES_EXPLAIN' => $LANG['wiki_last_articles_explain'],
	'L_DESCRIPTION' => $LANG['wiki_desc']
));
	
$Template->pparse('wiki_config');

require_once('../admin/admin_footer.php');

?>
