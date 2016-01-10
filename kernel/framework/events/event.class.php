<?php


























import('util/date');

##Constants##


define('EVENT_STATUS_UNREAD',0);

define('EVENT_STATUS_BEING_PROCESSED',1);

define('EVENT_STATUS_PROCESSED',2);












class Event
{



function Event()
{
$this->current_status=EVENT_STATUS_UNREAD;
$this->creation_date=new Date();
}





function set_id($id)
{
if(is_int($id)&&$id>0)
$this->id=$id;
}





function set_entitled($entitled)
{
$this->entitled=$entitled;
}





function set_fixing_url($fixing_url)
{
$this->fixing_url=$fixing_url;
}










function set_status($new_current_status)
{
if(in_array($new_current_status,array(EVENT_STATUS_UNREAD,EVENT_STATUS_BEING_PROCESSED,EVENT_STATUS_PROCESSED),TRUE))
{
$this->current_status=$new_current_status;
}

else
$this->current_status=EVENT_STATUS_UNREAD;

$this->must_regenerate_cache=true;
}





function set_creation_date($date)
{
if(is_object($date)&&strtolower(get_class($date))=='date')
$this->creation_date=$date;
}






function set_id_in_module($id)
{
$this->id_in_module=$id;
}






function set_identifier($identifier)
{
$this->identifier=$identifier;
}





function set_type($type)
{
$this->type=$type;
}





function set_must_regenerate_cache($must)
{
if(is_bool($must))
$this->must_regenerate_cache=$must;
}





function get_id()
{
return $this->id;
}





function get_entitled()
{
return $this->entitled;
}





function get_fixing_url()
{
return $this->fixing_url;
}










function get_status()
{
return $this->current_status;
}





function get_creation_date()
{
return $this->creation_date;
}





function get_id_in_module()
{
return $this->id_in_module;
}






function get_identifier()
{
return $this->identifier;
}





function get_type()
{
return $this->type;
}





function get_must_regenerate_cache()
{
return $this->must_regenerate_cache;
}





function get_status_name()
{
global $LANG;

switch($this->current_status)
{
case EVENT_STATUS_UNREAD:
return $LANG['contribution_status_unread'];
case EVENT_STATUS_BEING_PROCESSED:
return $LANG['contribution_status_being_processed'];
case EVENT_STATUS_PROCESSED:
return $LANG['contribution_status_processed'];
}
}












function build($id,$entitled,$fixing_url,$current_status,$creation_date,$id_in_module,$identifier,$type)
{
$this->id=$id;
$this->entitled=$entitled;
$this->fixing_url=$fixing_url;
$this->current_status=$current_status;
$this->creation_date=$creation_date;
$this->id_in_module=$id_in_module;
$this->identifier=$identifier;
$this->type=$type;
$this->must_regenerate_cache=false;
}

## Protected ##




var $id=0;





var $entitled='';





var $fixing_url='';





var $current_status=EVENT_STATUS_UNREAD;





var $creation_date;






var $id_in_module=0;





var $identifier='';





var $type='';





var $must_regenerate_cache=true;
}

?>