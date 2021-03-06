<?php



























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$template = new Template('admin/admin_system_report.tpl');

$template->assign_vars(array(
	'L_YES' => $LANG['yes'],
	'L_NO' => $LANG['no'],
	'L_UNKNOWN' => $LANG['unknown'],
	'L_SYSTEM_REPORT' => $LANG['system_report'],
	'L_SERVER' => $LANG['server'],
	'L_PHPINFO' => $LANG['phpinfo'],
	'L_PHP_VERSION' => $LANG['php_version'],
	'L_DBMS_VERSION' => $LANG['dbms_version'],
	'L_GD_LIBRARY' => $LANG['dg_library'],
	'L_URL_REWRITING' => $LANG['url_rewriting'],
	'L_REGISTER_GLOBALS_OPTION' => $LANG['register_globals_option'],
	'L_SERVER_URL' => $LANG['serv_name'],
	'L_SITE_PATH' => $LANG['serv_path'],
	'L_PHPBOOST_CONFIG' => $LANG['phpboost_config'],
	'L_KERNEL_VERSION' => $LANG['kernel_version'],
	'L_DEFAULT_THEME' => $LANG['default_theme'],
	'L_DEFAULT_LANG' => $LANG['default_language'],
	'L_DEFAULT_EDITOR' => $LANG['choose_editor'],
	'L_START_PAGE' => $LANG['start_page'],
	'L_OUTPUT_GZ' => $LANG['output_gz'],
	'L_COOKIE_NAME' => $LANG['cookie_name'],
	'L_SESSION_LENGTH' => $LANG['session_time'],
	'L_SESSION_GUEST_LENGTH' => $LANG['session invit'],
	'L_DIRECTORIES_AUTH' => $LANG['directories_auth'],
	'L_SUMMERIZATION' => $LANG['system_report_summerization'],
	'L_SUMMERIZATION_EXPLAIN' => $LANG['system_report_summerization_explain']
));


$temp_var = function_exists('apache_get_modules') ? apache_get_modules() : array();
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
if (!$server_path)
	$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
$server_path = trim(str_replace('/admin', '', dirname($server_path)));
$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

$lang_ini_file = load_ini_file('../lang/', get_ulang());
$template_ini_file = load_ini_file('../templates/' . get_utheme() . '/config/', get_ulang());

$directories_summerization = '';
$directories_list = array('/', '/cache', '/cache/backup', '/cache/syndication/', '/cache/tpl', '/images/avatars', '/images/group', '/images/maths', '/images/smileys', '/lang', '/menus', '/templates', '/upload');
foreach ($directories_list as $dir)
{
	$dir_status = is_dir('..' . $dir) && is_writable('..' . $dir);
	$template->assign_block_vars('directories', array(
		'NAME' => $dir,
		'C_AUTH_DIR' => $dir_status
	));
	$directories_summerization .= $dir . str_repeat(' ', 25 - strlen($dir)) . ": " . (int)$dir_status . "
";
}

$summerization =
"---------------------------------System report---------------------------------
-----------------------------generated by PHPBoost-----------------------------

SERVER CONFIGURATION-----------------------------------------------------------

php version              : " . phpversion() . "
dbms version             : " . $Sql->get_dbms_version() . "
gd library               : " . (int)@extension_loaded('gd') . "
url rewriting            : " . (function_exists('apache_get_modules') ? (int)!empty($temp_var[5]) : "?") . "
register globals         : " . (int)(@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') . "
server url               : " . $server_name . "
site path                : " . $server_path  . "

PHPBOOST CONFIGURATION---------------------------------------------------------

phpboost version         : " . phpboost_version() . "
server url               : " . $CONFIG['server_name'] . "
site path                : " . $CONFIG['server_path']  . "
default theme            : " . $template_ini_file['name'] . "
default language         : " . get_ulang() . "
default editor           : " . $CONFIG['editor'] . "
start page               : " . $CONFIG['start_page'] . "
url rewriting            : " . $CONFIG['rewrite'] . "
output gz                : " . $CONFIG['ob_gzhandler'] . "
session cookie name      : " . $CONFIG['site_cookie'] . "
session length           : " . $CONFIG['site_session'] . "
guest session length     : " . $CONFIG['site_session_invit'] . "

DIRECTORIES AUTHORIZATIONS-----------------------------------------------------

" . $directories_summerization;

$template->assign_vars(array(
	'PHP_VERSION' => phpversion(),
	'DBMS_VERSION' => $Sql->get_dbms_version(),
	'C_SERVER_GD_LIBRARY' => @extension_loaded('gd'),
	'C_URL_REWRITING_KNOWN' => function_exists('apache_get_modules'),
	'C_SERVER_URL_REWRITING' => function_exists('apache_get_modules') ? !empty($temp_var[5]) : false,
	'C_REGISTER_GLOBALS' => @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on',
	'SERV_SERV_URL' => $server_name,
	'SERV_SITE_PATH' => $server_path,
	'KERNEL_VERSION' => phpboost_version(),
	'KERNEL_SERV_URL' => $CONFIG['server_name'],
	'KERNEL_SITE_PATH' => $CONFIG['server_path'],
	'KERNEL_DEFAULT_THEME' => $template_ini_file['name'],
	'KERNEL_DEFAULT_LANGUAGE' => $lang_ini_file['name'],
	'KERNEL_DEFAULT_EDITOR' => $CONFIG['editor'] == 'tinymce' ? 'TinyMCE' : 'BBCode',
	'KERNEL_START_PAGE' => $CONFIG['start_page'],
	'C_KERNEL_URL_REWRITING' => (bool)$CONFIG['rewrite'],
	'C_KERNEL_OUTPUT_GZ' => (bool)$CONFIG['ob_gzhandler'],
	'COOKIE_NAME' => $CONFIG['site_cookie'],
	'SESSION_LENGTH' => $CONFIG['site_session'],
	'SESSION_LENGTH_GUEST' => $CONFIG['site_session_invit'],
	'SUMMERIZATION' => $summerization
));

$template->parse();

require_once('../admin/admin_footer.php');

?>
