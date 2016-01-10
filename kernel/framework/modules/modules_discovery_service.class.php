<?php



























import('modules/module_interface');








class ModulesDiscoveryService
{



function ModulesDiscoveryService()
{
global $MODULES;

$this->loaded_modules=array();
$this->availables_modules=array();
foreach($MODULES as $module_id=>$module)
{
if(!empty($module['activ'])&&$module['activ']==true)
{
$this->availables_modules[]=$module_id;
}
}
}











function functionality($functionality,$modules)
{
$results=array();
foreach($modules as $module_id=>$args)
{

$module=$this->get_module($module_id);

$module->clear_functionality_error();
if($module->has_functionality($functionality)==true)
{
$results[$module_id]=$module->functionality($functionality,$args);
}
}
return $results;
}








function get_all_modules()
{
return $this->get_available_modules();
}











function get_available_modules($functionality='none',$modulesList=array())
{
$modules=array();
if($modulesList===array())
{
global $MODULES;
foreach(array_keys($MODULES)as $module_id)
{
$module=$this->get_module($module_id);
if(!$module->got_error()&&$module->has_functionality($functionality))
{
$modules[$module->get_id()]=$module;
}
}
}
else
{
foreach($modulesList as $module)
{
if(!$module->got_error()&&$module->has_functionality($functionality))
{
$modules[$module->get_id()]=$module;
}
}
}
return $modules;
}







function get_module($module_id='')
{
$module_constructor=ucfirst($module_id.'Interface');
$file=PATH_TO_ROOT.'/'.$module_id.'/'.$module_id.'_interface.class.php';

if(!DEBUG)
{
$include=@include_once($file);
}
elseif(file_exists($file))
{
$include=include_once($file);
}
else
{
$include=FALSE;
}
if($include&&class_exists($module_constructor))
{
$module=new $module_constructor();

if(isset($this->loaded_modules[$module_id]))
{
return $this->loaded_modules[$module_id];
}

if(in_array($module_id,$this->availables_modules))
{
global $User,$MODULES;

if(!$User->check_auth($MODULES[$module_id]['auth'],ACCESS_MODULE))
{
$module->set_error(ACCES_DENIED);
}
}
else
{
$module->set_error(MODULE_NOT_AVAILABLE);
}
}
else
{
$module=new ModuleInterface($module_id,MODULE_NOT_YET_IMPLEMENTED);
}

$this->loaded_modules[$module_id]=$module;
return $this->loaded_modules[$module_id];
}


var $loaded_modules;
var $available_modules;
}

?>
