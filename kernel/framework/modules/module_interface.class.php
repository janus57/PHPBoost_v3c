<?php




























define('MODULE_NOT_AVAILABLE',1);
define('ACCES_DENIED',2);
define('MODULE_NOT_YET_IMPLEMENTED',4);
define('FUNCTIONNALITY_NOT_IMPLEMENTED',8);
define('MODULE_ATTRIBUTE_DOES_NOT_EXIST',16);









class ModuleInterface
{






function ModuleInterface($moduleId='',$error=0)
{



global $CONFIG,$MODULES;
$this->id=$moduleId;
$this->name=$this->id;
$this->attributes=array();
$this->infos=array();
$this->functionalities=array();
$this->enabled=!empty($MODULES[strtolower($this->get_id())])&&($MODULES[strtolower($this->get_id())]['activ']=='1');


$this->infos=load_ini_file(PATH_TO_ROOT.'/'.$this->id.'/lang/',get_ulang());
if(isset($this->infos['name']))
{
$this->name=$this->infos['name'];
}

if($error==0)
{
$class=ucfirst($moduleId).'Interface';

$module_methods=get_class_methods($class);

$generics_methods=get_class_methods('ModuleInterface');
$generics_methods[]=$class;

$methods_diff=array_diff($module_methods,$generics_methods);


foreach($methods_diff as $method)
{
if(substr($method,0,1)!='_')
{
$this->functionalities[]=$method;
}
}
$this->functionalities[]='none';
}
$this->errors=$error;
}





function get_id()
{
return $this->id;
}




function is_enabled()
{
return $this->enabled;
}




function get_name()
{
return $this->name;
}




function get_infos()
{
return array(
'name'=>$this->name,
'infos'=>$this->infos,
'functionalities'=>$this->functionalities,
);
}







function get_attribute($attribute)

{
$this->_clear_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
if(isset($this->attributes[$attribute]))
return $this->attributes[$attribute];

$this->_set_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
return-1;
}






function set_attribute($attribute,$value)
{
$this->attributes[$attribute]=$value;
}





function unset_attribute($attribute)
{
unset($this->attributes[$attribute]);
}







function got_error($error=0)
{
if($error==0)
return $this->errors!=0;
else
return($this->errors&$error)!=0;
}




function get_errors()
{
return $this->errors;
}








function functionality($functionality,$args=null)
{
$this->_clear_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
if($this->has_functionality($functionality))
return $this->$functionality($args);
$this->_set_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
return false;
}






function has_functionality($functionality)
{
return in_array(strtolower($functionality),$this->functionalities);
}






function has_functionalities($functionalities)
{
$nbFunctionnalities=count($functionalities);
for($i=0;$i<$nbFunctionnalities;$i++)
$functionalities[$i]=strtolower($functionalities[$i]);
return $functionalities===array_intersect($functionalities,$this->functionalities);
}






function set_error($error=0)
{
$this->errors |=$error;
}








function _clear_error($error)
{
$this->errors&=(~$error);
}






var $id;




var $name;




var $infos;




var $functionalities;




var $errors;




var $attributes;

var $enabled=false;


}

?>
