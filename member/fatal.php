<?php



























require_once '../kernel/begin.php';

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<title>' . $LANG['error'] . '</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="../templates/' . get_utheme() . '/theme/design.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/global.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/generic.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/bbcode.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/content.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut" href="../favicon.ico" />
</head>
<body><br /><br /><br />';


$errinfo = $Errorh->get_last__error_log();
if (empty($errinfo))
	list($errinfo['errno'], $errinfo['errstr'], $errinfo['errline'], $errinfo['errfile']) = array('-1', '???', '0', 'unknow');

$Template->set_filenames(array(
	'error'=> 'member/error.tpl'
));

$class = $Errorh->get_errno_class($errinfo['errno']);
	
$Template->assign_vars(array(
	'THEME' => get_utheme(),
	'ERRORH_IMG' => 'stop',
	'ERRORH_CLASS' => $class,
	'C_ERRORH_CONNEXION' => false,
	'C_ERRORH' => true,
	'L_ERRORH' => sprintf($LANG[$class], $errinfo['errstr'], $errinfo['errline'], basename($errinfo['errfile'])),
	'L_ERROR' => $LANG['error'],
	'U_BACK' => '<a href="' . get_start_page() . '">' . $LANG['home'] . '</a>' . (!empty($_SERVER['HTTP_REFERER']) ? ' &raquo; <a href="' . url($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : ' &raquo; <a href="javascript:history.back(1)">' . $LANG['back'] . '</a>'),
));

$Template->pparse('error');

echo '</body></html>';

require_once '../kernel/footer_no_display.php';

?>
