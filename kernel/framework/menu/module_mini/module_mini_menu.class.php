<?php


























import('menu/menu');

define('MODULE_MINI_MENU__CLASS','ModuleMiniMenu');







class ModuleMiniMenu extends Menu
{
## Public Methods ##




function ModuleMiniMenu($module,$filename)
{
parent::Menu($module);
$this->filename=strprotect($filename);
}




function cache_export()
{
$cache_str='\';include_once PATH_TO_ROOT.\'/'.strtolower($this->title).'/'.$this->filename.'.php\';';
$cache_str.='if(function_exists(\''.$this->filename.'\')) { $__menu.='.$this->filename.'('.$this->position.','.$this->block.');} $__menu.=\'';
return parent::cache_export_begin().$cache_str.parent::cache_export_end();
}

function get_title()
{
return $this->title.'/'.$this->filename;
}

var $filename='';
}

?>