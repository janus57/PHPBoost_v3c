<?php


























require_once('../kernel/begin.php');
require_once('articles_constants.php'); 

$Cache->load('articles');


$idart = retrieve(GET, 'id', '', TSTRING);
if ($idart > 0)
{
	$articles = $Sql->query_array(PREFIX . 'articles', '*', "WHERE visible = 1 AND id = '" . $idart . "'", __LINE__, __FILE__);
	
	$idartcat = $articles['idcat'];
	
	
	if (!isset($CAT_ARTICLES[$idartcat]) || !$User->check_auth($CAT_ARTICLES[$idartcat]['auth'], READ_CAT_ARTICLES) || $CAT_ARTICLES[$idartcat]['aprob'] == 0) 
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	
	if (empty($articles['id']))
		$Errorh->handler('e_unexist_articles', E_USER_REDIRECT);
}

if (empty($articles['title']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new Template('framework/content/print.tpl');

$contents = preg_replace('`\[page\](.*)\[/page\]`', '<h2>$1</h2>', $articles['contents']);

$template->assign_vars(array(
	'PAGE_TITLE' => $articles['title'] . ' - ' . $CONFIG['site_name'],
	'TITLE' => $articles['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => second_parse($contents)
));

$template->parse();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
