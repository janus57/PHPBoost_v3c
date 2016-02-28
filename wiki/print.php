<?php


























require_once('../kernel/begin.php'); 
load_module_lang('wiki');

include('../wiki/wiki_functions.php');


$article_id = retrieve(GET, 'id', 0);


if ($article_id > 0) 
{
	$result = $Sql->query_while("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, a.nbr_com, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
	FROM " . PREFIX . "wiki_articles a
	LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
	LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
	WHERE a.id = '" . $article_id . "'
	GROUP BY a.id", __LINE__, __FILE__);	
	$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE id = '" . $article_id . "'", __LINE__, __FILE__);
	$article_infos = $Sql->fetch_assoc($result);
	$Sql->query_close($result);

	if (!empty($article_infos['redirect']))
	{
		$id_redirection = $article_infos['id'];
		
		$result = $Sql->query_while("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.nbr_com, a.defined_status, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
		WHERE a.id = '" . $article_infos['redirect'] . "'
		GROUP BY a.id", __LINE__, __FILE__);	
		$article_infos = $Sql->fetch_assoc($result);
		$Sql->query_close($result);
	}
}

if (empty($article_infos['id']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new Template('framework/content/print.tpl');

$template->assign_vars(array(
	'PAGE_TITLE' => $article_infos['title'] . (!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']),
	'TITLE' => $article_infos['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => second_parse($article_infos['content'])
));

$template->parse();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
