<?php






























define('PATH_TO_ROOT','../../..');

require_once(PATH_TO_ROOT.'/kernel/begin.php');

define('NO_SESSION_LOCATION',true);

require_once(PATH_TO_ROOT.'/kernel/header_no_display.php');


$Session->csrf_get_protect();

if(!$User->check_level(ADMIN_LEVEL))
{
exit;
}

import('events/administrator_alert_service');

$change_status=retrieve(GET,'change_status',0);
$id_to_delete=retrieve(GET,'delete',0);

if($change_status>0)
{
$alert=new AdministratorAlert();


if(($alert=AdministratorAlertService::find_by_id($change_status))!=null)
{

$new_status=$alert->get_status()!=EVENT_STATUS_PROCESSED?EVENT_STATUS_PROCESSED:EVENT_STATUS_UNREAD;

$alert->set_status($new_status);

AdministratorAlertService::save_alert($alert);

echo '1';
}

else
{
echo '0';
}
}
elseif($id_to_delete>0)
{
$alert=new AdministratorAlert();


if(($alert=AdministratorAlertService::find_by_id($id_to_delete))!=null)
{
AdministratorAlertService::delete_alert($alert);
echo '1';
}

else
{
echo '0';
}
}

require_once(PATH_TO_ROOT.'/kernel/footer_no_display.php');

?>