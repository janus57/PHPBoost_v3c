<?php


























import('menu/menu');

define('MINI_MENU__CLASS','MiniMenu');







class MiniMenu extends Menu
{
## Public Methods ##
function MiniMenu($title,$filename)
{
$this->function_name='menu_'.strtolower($title).'_'.strtolower($filename);
parent::Menu($title.'/'.$filename);
}



function cache_export()
{
$cache_str='\';include_once PATH_TO_ROOT.\'/menus/'.strtolower($this->title).'.php\';';
$cache_str.='if(function_exists(\''.$this->function_name.'\')) { $__menu.='.$this->function_name.'('.$this->position.','.$this->block.');} $__menu.=\'';
return parent::cache_export_begin().$cache_str.parent::cache_export_end();
}
var $function_name='';
}

?>