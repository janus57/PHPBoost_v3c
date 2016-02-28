<?php


























define('NO_SESSION_LOCATION', true); 
header('HTTP/1.0 404 Not Found'); 
require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_error'] . ' 404');
require_once('../kernel/header.php'); 

$Template->set_filenames(array(
	'error'=> 'member/error.tpl'
));

$Template->assign_vars(array(
	'C_ERRORH' => true,
	'ERRORH_IMG' => 'important',
	'ERRORH_CLASS' => 'error_warning',
	'L_ERROR' => $LANG['title_error'] . ' 404',
	'L_ERRORH' => '<strong>' . $LANG['title_error'] . ' 404</strong>' . '<br /><br />' . $LANG['e_unexist_page'],
	'U_BACK' => !empty($_SERVER['HTTP_REFERER']) ? '<a href="' . url($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : '<a href="javascript:history.back(1)">' . $LANG['back'] . '</a>',
));

$Template->pparse('error');

require_once('../kernel/footer.php'); 

?>
