<?php






























define('PATH_TO_ROOT','../../..');
define('NO_SESSION_LOCATION',true);

include_once(PATH_TO_ROOT.'/kernel/begin.php');
include_once(PATH_TO_ROOT.'/kernel/header_no_display.php');


import('members/uploads');
$Uploads=new Uploads;

if(!empty($_GET['new_folder']))
{
$id_parent=!empty($_POST['id_parent'])?numeric($_POST['id_parent']):'0';
$user_id=!empty($_POST['user_id'])?numeric($_POST['user_id']):$User->get_attribute('user_id');
$name=!empty($_POST['name'])?strprotect(utf8_decode($_POST['name'])):'';

if(!empty($user_id)&&$User->get_attribute('user_id')!=$user_id)
{
if($User->check_level(ADMIN_LEVEL))
{
echo $Uploads->Add_folder($id_parent,$user_id,$name);
}
else
{
echo $Uploads->Add_folder($id_parent,$User->get_attribute('user_id'),$name);
}
}
else
{
echo $Uploads->Add_folder($id_parent,$User->get_attribute('user_id'),$name);
}
}
elseif(!empty($_GET['rename_folder']))
{
$id_folder=!empty($_POST['id_folder'])?numeric($_POST['id_folder']):'0';
$name=!empty($_POST['name'])?strprotect(utf8_decode($_POST['name'])):'';
$user_id=!empty($_POST['user_id'])?numeric($_POST['user_id']):$User->get_attribute('user_id');
$previous_name=!empty($_POST['previous_name'])?strprotect(utf8_decode($_POST['previous_name'])):'';

if(!empty($id_folder)&&!empty($name))
{
if($User->get_attribute('user_id')!=$user_id)
{
if($User->check_level(ADMIN_LEVEL))
{
echo $Uploads->Rename_folder($id_folder,$name,$previous_name,$user_id,ADMIN_NO_CHECK);
}
else
{
echo $Uploads->Rename_folder($id_folder,$name,$previous_name,$User->get_attribute('user_id'),ADMIN_NO_CHECK);
}
}
else
{
echo $Uploads->Rename_folder($id_folder,$name,$previous_name,$User->get_attribute('user_id'));
}
}
else
echo 0;
}
elseif(!empty($_GET['rename_file']))
{
$id_file=!empty($_POST['id_file'])?numeric($_POST['id_file']):'0';
$user_id=!empty($_POST['user_id'])?numeric($_POST['user_id']):$User->get_attribute('user_id');
$name=!empty($_POST['name'])?strprotect(utf8_decode($_POST['name'])):'';
$previous_name=!empty($_POST['previous_name'])?strprotect(utf8_decode($_POST['previous_name'])):'';

if(!empty($id_file)&&!empty($name))
{
if($User->get_attribute('user_id')!=$user_id)
{
if($User->check_level(ADMIN_LEVEL))
{
echo $Uploads->Rename_file($id_file,$name,$previous_name,$user_id,ADMIN_NO_CHECK);
}
else
{
echo $Uploads->Rename_file($id_file,$name,$previous_name,$User->get_attribute('user_id'),ADMIN_NO_CHECK);
}
}
else
{
echo $Uploads->Rename_file($id_file,$name,$previous_name,$User->get_attribute('user_id'));
}
}
else
{
echo 0;
}
}

?>