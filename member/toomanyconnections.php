<?php



























define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';

import('core/cache');

$CONFIG = array();
$Cache = new Cache();
$Cache->load('config');
$Cache->load('member');

import('members/user');
$user_data = array(
    'm_user_id' => 1,
    'login' => 'login',
    'level' => ADMIN_LEVEL,
    'user_groups' => '',
    'user_lang' => $CONFIG['lang'],
    'user_theme' => DISTRIBUTION_THEME,
    'user_mail' => '',
    'user_pm' => 0,
    'user_editor' => 'bbcode',
    'user_timezone' => 1,
    'avatar' => '',
    'user_readonly' => 0,
    'user_id' => 1,
    'session_id' => ''
);
$user_groups = array();
$User = new User($user_data, $user_groups);


if ($CONFIG['ob_gzhandler'] == 1)
{
    ob_start('ob_gzhandler'); 
}
else
{
    ob_start();
}

$LANG = array();
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/admin.php');
require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php';
require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php';

define('TPL_PATH_TO_ROOT', !empty($CONFIG['server_path']) ? $CONFIG['server_path'] : '/');

header('Content-type: text/html; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');

import('io/template');
$tpl = new Template('framework/fatal.tpl');
$tpl->assign_vars(array(
    'ERROR_TITLE' => $LANG['too_many_connections'],
    'ERROR_EXPLAIN' => $LANG['too_many_connections_explain'],
    'PREVIOUS_PAGE' =>  $LANG['previous_page']
));
$tpl->parse();


?>
