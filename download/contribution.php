<?php


























require_once('../kernel/begin.php');


require_once 'download_auth.php';

$Cache->load('download');

load_module_lang('download');

if (!$User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT))
	$Errorh->handler('e_auth', E_USER_REDIRECT);

define('TITLE', $DOWNLOAD_LANG['contribution_confirmation']);

$Bread_crumb->add($DOWNLOAD_LANG['download'], url('download.php'));
$Bread_crumb->add($DOWNLOAD_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');


$download_template = new Template('download/contribution.tpl');

$download_template->assign_vars(array(
	'L_CONTRIBUTION_CONFIRMATION' => $DOWNLOAD_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $DOWNLOAD_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $DOWNLOAD_LANG['contribution_confirmation_explain']
));

$download_template->parse();

require_once('../kernel/footer.php'); 

?>
