<?php






























define('PATH_TO_ROOT','../../..');
define('NO_SESSION_LOCATION',true);

include_once(PATH_TO_ROOT.'/kernel/begin.php');
include_once(PATH_TO_ROOT.'/kernel/header_no_display.php');

if(!empty($_GET['member'])||!empty($_GET['insert_member'])||!empty($_GET['add_member_auth'])||!empty($_GET['admin_member'])||!empty($_GET['warning_member'])||!empty($_GET['punish_member']))
{
$login=!empty($_POST['login'])?strprotect(utf8_decode($_POST['login'])):'';
$divid=!empty($_POST['divid'])?strprotect(utf8_decode($_POST['divid'])):'';
$login=str_replace('*','%',$login);
if(!empty($login))
{
$i=0;
$result=$Sql->query_while("SELECT user_id, login FROM ".DB_TABLE_MEMBER." WHERE login LIKE '".$login."%'",__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
{
if(!empty($_GET['member']))
{
echo '<a href="member'.url('.php?id='.$row['user_id'],'-'.$row['user_id'].'.php').'">'.$row['login'].'</a><br />';
}
elseif(!empty($_GET['insert_member']))
{
echo '<a href="#" onclick="document.getElementById(\'login\').value = \''.addslashes($row['login']).'\';return false">'.addslashes($row['login']).'</a><br />';
}
elseif(!empty($_GET['add_member_auth']))
{
echo '<a href="javascript:XMLHttpRequest_add_member_auth(\''.addslashes($divid).'\', '.$row['user_id'].', \''.addslashes($row['login']).'\', \''.addslashes($LANG['alert_member_already_auth']).'\');">'.addslashes($row['login']).'</a><br />';
}
elseif(!empty($_GET['admin_member']))
{
echo '<a href="../admin/admin_members.php?id='.$row['user_id'].'#search">'.addslashes($row['login']).'</a><br />';
}
if(!empty($_GET['warning_member']))
{
echo '<a href="admin_members_punishment.php?action=users&amp;id='.$row['user_id'].'">'.addslashes($row['login']).'</a><br />';
}
elseif(!empty($_GET['punish_member']))
{
echo '<a href="admin_members_punishment.php?action=punish&amp;id='.$row['user_id'].'">'.addslashes($row['login']).'</a><br />';
}
$i++;
}
if($i==0)
{
echo $LANG['no_result'];
}
}
else
{
echo $LANG['no_result'];
}

$Sql->close();
}
elseif(!empty($_GET['warning_user'])||!empty($_GET['punish_user'])||!empty($_GET['ban_user']))
{
$login=!empty($_POST['login'])?strprotect(utf8_decode($_POST['login'])):'';
$login=str_replace('*','%',$login);
$admin=!empty($_POST['admin'])?true:false;
if(!empty($login))
{
$i=0;
$result=$Sql->query_while("SELECT user_id, login FROM ".DB_TABLE_MEMBER." WHERE login LIKE '".$login."%'",__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
{
$url_warn=($admin)?'admin_members_punishment.php?action=warning&amp;id='.$row['user_id']:url('moderation_panel.php?action=warning&amp;id='.$row['user_id']);
$url_punish=($admin)?'admin_members_punishment.php?action=punish&amp;id='.$row['user_id']:url('moderation_panel.php?action=punish&amp;id='.$row['user_id']);
$url_ban=($admin)?'admin_members_punishment.php?action=ban&amp;id='.$row['user_id']:url('moderation_panel.php?action=ban&amp;id='.$row['user_id']);

if(!empty($_GET['warning_user']))
{
echo '<a href="'.$url_warn.'">'.$row['login'].'</a><br />';
}
elseif(!empty($_GET['punish_user']))
{
echo '<a href="'.$url_punish.'">'.$row['login'].'</a><br />';
}
elseif(!empty($_GET['ban_user']))
{
echo '<a href="'.$url_ban.'">'.$row['login'].'</a><br />';
}
$i++;
}

if($i==0)
{
echo $LANG['no_result'];
}
}
else
{
echo $LANG['no_result'];
}

$Sql->close();
}

?>