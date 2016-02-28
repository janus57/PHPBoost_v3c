<?php



























require_once('../kernel/begin.php');
require_once('media_begin.php');

define('TITLE', $MEDIA_LANG['contribution_confirmation']);

$Bread_crumb->add($MEDIA_LANG['media'], url('media.php'));
$Bread_crumb->add($MEDIA_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

$media_template = new Template('media/contribution.tpl');

$media_template->assign_vars(array(
	'L_CONTRIBUTION_CONFIRMATION' => $MEDIA_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $MEDIA_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $MEDIA_LANG['contribution_confirmation_explain']
));

$media_template->parse();

require_once('../kernel/footer.php');

?>
