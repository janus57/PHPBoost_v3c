<?php






























define('PATH_TO_ROOT','../../..');
require_once(PATH_TO_ROOT.'/kernel/begin.php');
define('TITLE',$LANG['title_com']);
require_once(PATH_TO_ROOT.'/kernel/header_no_display.php');

if(!empty($_GET['com']))
{
if(!preg_match('`([0-9]+)([a-z]+)([0-9]*)`',trim($_GET['com']),$array_get))
{
$array_get=array('','','','');
}
$idcom=(empty($array_get[3])&&!empty($_POST['idcom']))?numeric($_POST['idcom']):$array_get[3];

import('content/comments');
$Comments=new Comments($array_get[2],$array_get[1],url('?com='.$array_get[1].$array_get[2].'%s',''),$array_get[2]);
$Comments->set_arg($idcom,HOST.DIR.'/kernel/framework/ajax/pop_up_comments.php');


echo $Comments->display(POP_UP_WINDOW,null,'');
}

include_once(PATH_TO_ROOT.'/kernel/footer_no_display.php');
?>