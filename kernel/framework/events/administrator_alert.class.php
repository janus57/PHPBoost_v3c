<?php


























import('events/event');

## Constants ##


define('ADMIN_ALERT_VERY_LOW_PRIORITY',1);

define('ADMIN_ALERT_LOW_PRIORITY',2);

define('ADMIN_ALERT_MEDIUM_PRIORITY',3);

define('ADMIN_ALERT_HIGH_PRIORITY',4);

define('ADMIN_ALERT_VERY_HIGH_PRIORITY',5);



define('ADMIN_ALERT_STATUS_UNREAD',EVENT_STATUS_UNREAD);

define('ADMIN_ALERT_STATUS_PROCESSED',EVENT_STATUS_PROCESSED);








class AdministratorAlert extends Event
{



function AdministratorAlert()
{
parent::Event();
$this->priority=ADMIN_ALERT_MEDIUM_PRIORITY;
$this->properties='';
}














function build($id,$entitled,$properties,$fixing_url,$current_status,$creation_date,$id_in_module,$identifier,$type,$priority)
{
parent::build($id,$entitled,$fixing_url,$current_status,$creation_date,$id_in_module,$identifier,$type);
$this->set_priority($priority);
$this->set_properties($properties);
}












function get_priority()
{
return $this->priority;
}





function get_properties()
{
return $this->properties;
}












function set_priority($priority)
{
$priority=intval($priority);
if($priority>=ADMIN_ALERT_VERY_LOW_PRIORITY&&$priority<=ADMIN_ALERT_VERY_HIGH_PRIORITY)
{
$this->priority=$priority;
}
else
{
$this->priority=ADMIN_ALERT_MEDIUM_PRIORITY;
}
}





function set_properties($properties)
{

if(is_string($properties))
{
$this->properties=$properties;
}
}





function get_priority_name()
{
global $LANG;
switch($this->priority)
{
case ADMIN_ALERT_VERY_LOW_PRIORITY:
return $LANG['priority_very_low'];
break;
case ADMIN_ALERT_LOW_PRIORITY:
return $LANG['priority_low'];
break;
case ADMIN_ALERT_MEDIUM_PRIORITY:
return $LANG['priority_medium'];
break;
case ADMIN_ALERT_HIGH_PRIORITY:
return $LANG['priority_high'];
break;
case ADMIN_ALERT_VERY_HIGH_PRIORITY:
return $LANG['priority_very_high'];
break;
default:
return $LANG['normal'];
}
}

## Private ##




var $priority=ADMIN_ALERT_MEDIUM_PRIORITY;





var $properties='';
}

?>