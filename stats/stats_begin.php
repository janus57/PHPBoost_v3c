<?php


























if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('stats'); 
include_once('../lang/' . get_ulang() . '/stats.php'); 

$visit = retrieve(GET, 'visit', false);
$visit_year = retrieve(GET, 'year', 0);
$pages = retrieve(GET, 'pages', false);
$pages_year = retrieve(GET, 'pages_year', 0);
$referer = retrieve(GET, 'referer', false);
$keyword = retrieve(GET, 'keyword', false);
$members = retrieve(GET, 'members', false);
$browser = retrieve(GET, 'browser', false);
$os = retrieve(GET, 'os', false);
$all = retrieve(GET, 'all', false);
$user_lang = retrieve(GET, 'lang', false);

$l_title = $LANG['site'];
$l_title = ($visit || $visit_year) ? $LANG['guest_s'] : $l_title;
$l_title = $pages ? $LANG['page_s'] : $l_title;
$l_title = $referer ? $LANG['referer_s'] : $l_title;
$l_title = $keyword ? $LANG['keyword_s'] : $l_title;
$l_title = $members ? $LANG['member_s'] : $l_title;
$l_title = $browser ? $LANG['browser_s'] : $l_title;
$l_title = $os ? $LANG['os'] : $l_title;
$l_title = $user_lang ? $LANG['stat_lang'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if (!empty($l_title)) 
	$Bread_crumb->add($LANG['stats'], url('stats.php'));
	$Bread_crumb->add($l_title, '');	
define('TITLE', $LANG['stats'] . (!empty($l_title) ? ' - ' . $l_title : ''));

?>
