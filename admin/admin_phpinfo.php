<?php


























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

ob_start();

$template = new Template('admin/admin_phpinfo.tpl');

phpinfo();
$phpinfo = ob_get_contents();
$phpinfo = preg_replace('`^.*<body>`is', '', $phpinfo);
$phpinfo = str_replace(array('class="e"', 'class="v"', 'class="h"', '<i>', '</i>', '<hr />', '<img border="0"', '<table border="0" cellpadding="3" width="600">', '</body></html>'), 
array('class="row1"', 'class="row2"', 'class="row3"', '<em class="em">', '</em>', '', '<img style="float:right;"', '<table class="module_table">', ''), $phpinfo);
ob_end_clean();

ob_start();

$template->assign_vars(array(
    'PHPINFO' => $phpinfo,
	'L_SYSTEM_REPORT' => $LANG['system_report'],
	'L_SERVER' => $LANG['server'],
    'L_PHPINFO' => $LANG['phpinfo']
));

$template->parse();

require_once('../admin/admin_footer.php');

?>
