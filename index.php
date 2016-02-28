<?php


























define('PATH_TO_ROOT', './');
@include_once('./kernel/db/config.php'); 
unset($sql_host, $sql_login, $sql_pass); 

require_once('./kernel/framework/functions.inc.php');
$CONFIG = array();

@include_once('./cache/config.php');


if (!defined('PHPBOOST_INSTALLED'))
{
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('install/install.php'));
}
elseif (empty($CONFIG))
{   
    
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('member/member.php'));
}


define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);
$start_page = get_start_page();

if ($start_page != HOST . DIR . '/index.php' && $start_page != './index.php') 
	redirect($start_page);
else
	redirect(HOST . DIR . '/member/member.php');

?>
