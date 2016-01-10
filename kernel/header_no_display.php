<?php


























if(defined('PHPBOOST')!==true)
{
exit;
}

if(!defined('TITLE'))
{
define('TITLE',$LANG['unknow']);
}

$Session->check(TITLE);


if(($CONFIG['maintain']==-1 || $CONFIG['maintain']>time())&&!$User->check_level(ADMIN_LEVEL))
{
if(SCRIPT!==(DIR.'/member/maintain.php'))
{
redirect(HOST.DIR.'/member/maintain.php');
}
}

?>
