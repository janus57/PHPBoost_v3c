<?php


























import('util/date');
import('events/event');

## Constants ##


define('CONTRIBUTION_AUTH_BIT',1);







class Contribution extends Event
{



function Contribution()
{
$this->current_status=EVENT_STATUS_UNREAD;
$this->creation_date=new Date();
$this->fixing_date=new Date();
if(defined('MODULE_NAME'))
$this->module=MODULE_NAME;
}



















function build($id,$entitled,$description,$fixing_url,$module,$status,$creation_date,$fixing_date,$auth,$poster_id,$fixer_id,$id_in_module,$identifier,$type,$poster_login='',$fixer_login='')
{

parent::build($id,$entitled,$fixing_url,$status,$creation_date,$id_in_module,$identifier,$type);


$this->description=$description;
$this->module=$module;
$this->fixing_date=$fixing_date;
$this->auth=$auth;
$this->poster_id=$poster_id;
$this->fixer_id=$fixer_id;
$this->poster_login=$poster_login;
$this->fixer_login=$fixer_login;


$this->must_regenerate_cache=false;
}





function set_module($module)
{
$this->module=$module;
}





function set_fixing_date($date)
{
if(is_object($date)&&strtolower(get_class($date))=='date')
$this->fixing_date=$date;
}










function set_status($new_current_status)
{
global $User;

if(in_array($new_current_status,array(EVENT_STATUS_UNREAD,EVENT_STATUS_BEING_PROCESSED,EVENT_STATUS_PROCESSED),TRUE))
{

if($this->current_status!=EVENT_STATUS_PROCESSED&&$new_current_status==EVENT_STATUS_PROCESSED)
{
$this->fixing_date=new Date();

if($this->fixer_id==0)
$this->fixer_id=$User->get_attribute('user_id');
}

$this->current_status=$new_current_status;
}

else
$this->current_status=EVENT_STATUS_UNREAD;

$this->must_regenerate_cache=true;
}





function set_auth($auth)
{
if(is_array($auth))
$this->auth=$auth;
}





function set_poster_id($poster_id)
{
global $Sql;

if($poster_id>0)
{
$this->poster_id=$poster_id;

$this->poster_login=$Sql->query("SELECT login FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$poster_id."'",__LINE__,__FILE__);
}
}





function set_fixer_id($fixer_id)
{
global $Sql;

if($fixer_id>0)
{
$this->fixer_id=$fixer_id;

$this->fixer_login=$Sql->query("SELECT login FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$fixer_id."'",__LINE__,__FILE__);
}
}





function set_description($description)
{
if(is_string($description))
$this->description=$description;
}





function get_description()
{
return $this->description;
}





function get_module()
{
return $this->module;
}





function get_fixing_date()
{
return $this->fixing_date;
}





function get_auth()
{
return $this->auth;
}





function get_poster_id()
{
return $this->poster_id;
}





function get_fixer_id()
{
return $this->fixer_id;
}





function get_poster_login()
{
return $this->poster_login;
}





function get_fixer_login()
{
return $this->fixer_login;
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





function get_module_name()
{
global $CONFIG;

if(!empty($this->module))
{
$module_ini=load_ini_file(PATH_TO_ROOT.'/'.$this->module.'/lang/',get_ulang());

return isset($module_ini['name'])?$module_ini['name']:'';
}
else
return '';
}

## Protected ##




var $description;





var $module='';





var $fixing_date;





var $auth=array();





var $poster_id=0;





var $fixer_id=0;





var $poster_login='';





var $fixer_login='';
}

?>