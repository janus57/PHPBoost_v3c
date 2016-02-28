<?php


























require_once('../kernel/begin.php'); 

require_once('pages_defines.php');


$encoded_title = retrieve(GET, 'title', '', TSTRING);

$Cache->load('pages');

if (!empty($encoded_title)) 
{
	$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	
	$num_rows =!empty($page_infos['title']) ? 1 : 0;
	
	if ($page_infos['redirect'] > 0)
	{
		$redirect_title = $page_infos['title'];
		$redirect_id = $page_infos['id'];
		$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE id = '" . $page_infos['redirect'] . "'", __LINE__, __FILE__);
	}
	else
		$redirect_title = '';
		
	
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);

	
	if (($special_auth && !$User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
		redirect(HOST . DIR . url('/pages/pages.php?error=e_auth'));
}

if (empty($page_infos['id']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new Template('framework/content/print.tpl');

$template->assign_vars(array(
	'PAGE_TITLE' => $page_infos['title'] . ' - ' . $CONFIG['site_name'],
	'TITLE' => $page_infos['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => second_parse($page_infos['contents'])
));

$template->parse();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
