<?php


























if (!defined('PATH_TO_ROOT')) 
	define('PATH_TO_ROOT', '..');


if (@include(PATH_TO_ROOT . '/cache/debug.php'))
{
	define('DEBUG', (bool)$DEBUG['debug_mode']);
}
else
{
	define('DEBUG', false);
}

header('Content-type: text/html; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');


require_once PATH_TO_ROOT . '/kernel/framework/util/bench.class.php';
$Bench = new Bench(); 

require_once PATH_TO_ROOT . '/kernel/constant.php'; 
require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; 

import('core/errors');
import('io/template');
import('db/mysql');
import('core/cache');
import('members/session');
import('members/user');
import('members/groups');
import('members/authorizations');
import('core/breadcrumb');
import('content/parser/content_formatting_factory');


$Errorh = new Errors; 
$Template = new Template; 


$Sql = new Sql();

$Sql->auto_connect();

$Cache = new Cache(); 
$Bread_crumb = new BreadCrumb(); 


$CONFIG = array();
$Cache->load('config'); 
$Cache->load('groups'); 
$Cache->load('member'); 
$Cache->load('modules'); 
$Cache->load('themes'); 
$Cache->load('langs'); 

define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);
define('TPL_PATH_TO_ROOT', !empty($CONFIG['server_path']) ? $CONFIG['server_path'] : '');

$Session = new Session(); 


if ($CONFIG['ob_gzhandler'] == 1)
{
	ob_start('ob_gzhandler'); 
}
else
{
	ob_start();
}

$Session->load(); 
$Session->act(); 

$Group = new Group($_array_groups_auth); 
$User = new User($Session->data, $_array_groups_auth); 


if ($Session->session_mod)
{
	define('SID', 'sid=' . $User->get_attribute('session_id') . '&amp;suid=' . $User->get_attribute('user_id'));
	define('SID2', 'sid=' . $User->get_attribute('session_id') . '&suid=' . $User->get_attribute('user_id'));
}
else
{
	define('SID', '');
	define('SID2', '');
}


$user_theme = $User->get_attribute('user_theme');
if ($CONFIG_USER['force_theme'] == 1 || !isset($THEME_CONFIG[$user_theme]['secure']) || !$User->check_level($THEME_CONFIG[$user_theme]['secure'])) 
{
	$user_theme = $CONFIG['theme'];
}
$User->set_user_theme(find_require_dir(PATH_TO_ROOT . '/templates/', $user_theme));


$user_lang = $User->get_attribute('user_lang');
if (!isset($LANGS_CONFIG[$user_lang]['secure']) || !$User->check_level($LANGS_CONFIG[$user_lang]['secure'])) 
{
	$user_lang = $CONFIG['lang'];
}
$User->set_user_lang(find_require_dir(PATH_TO_ROOT . '/lang/', $user_lang));

$LANG = array();
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php'); 
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php'); 

$Cache->load('day');

if (gmdate_format('j', time(), TIMEZONE_SITE) != $_record_day && !empty($_record_day))
{
	import('io/filesystem/file');
	$lock_file = new File(PATH_TO_ROOT . '/cache/changeday_lock');
	if (!$lock_file->exists())
	{
		$lock_file->write('');
		$lock_file->flush();
	}
	$lock_file->lock(false);
	$yesterday_timestamp = time() - 86400;
	if ((int) $Sql->query("
	    SELECT COUNT(*)
            FROM " . DB_TABLE_STATS . "
            WHERE stats_year = '" . gmdate_format('Y', $yesterday_timestamp, TIMEZONE_SYSTEM) . "' AND
                stats_month = '" . gmdate_format('m', $yesterday_timestamp, TIMEZONE_SYSTEM) . "' AND
                stats_day = '" . gmdate_format('d', $yesterday_timestamp, TIMEZONE_SYSTEM) . "'", __LINE__, __FILE__) == 0
	)
	{
		
		$Cache->generate_file('day');

		require_once(PATH_TO_ROOT . '/kernel/changeday.php');
		change_day();
	}
	$lock_file->close();
}


define('MODULE_NAME', get_module_name());
if (isset($MODULES[MODULE_NAME]) )
{
	if ($MODULES[MODULE_NAME]['activ'] == 0 )
	{
		$Errorh->handler('e_unactivated_module', E_USER_REDIRECT);
	}
	else if(!$User->check_auth($MODULES[MODULE_NAME]['auth'], ACCESS_MODULE)) 
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
}
elseif (!in_array(MODULE_NAME, array('member', 'admin', 'kernel', ''))) 
{
	$array_info_module = load_ini_file(PATH_TO_ROOT . '/' . MODULE_NAME . '/lang/', get_ulang());
	if (!empty($array_info_module['name'])) 
	{
		$Errorh->handler('e_uninstalled_module', E_USER_REDIRECT);
	}
}


if ($User->check_level(MEMBER_LEVEL))
{
	$Session->csrf_post_protect();
}

?>
