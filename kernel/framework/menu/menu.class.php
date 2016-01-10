<?php


























define('MENU__CLASS','Menu');

define('MENU_AUTH_BIT',1);
define('MENU_ENABLE_OR_NOT',42);
define('MENU_ENABLED',true);
define('MENU_NOT_ENABLED',false);

define('BLOCK_POSITION__NOT_ENABLED',0);
define('BLOCK_POSITION__HEADER',1);
define('BLOCK_POSITION__SUB_HEADER',2);
define('BLOCK_POSITION__TOP_CENTRAL',3);
define('BLOCK_POSITION__BOTTOM_CENTRAL',4);
define('BLOCK_POSITION__TOP_FOOTER',5);
define('BLOCK_POSITION__FOOTER',6);
define('BLOCK_POSITION__LEFT',7);
define('BLOCK_POSITION__RIGHT',8);
define('BLOCK_POSITION__ALL',9);







class Menu
{
## Public Methods ##





function Menu($title)
{
$this->title=strprotect($title,HTML_PROTECT,ADDSLASHES_NONE);
}

## Setters ##



function set_title($title){$this->title=strprotect($title,HTML_PROTECT,ADDSLASHES_NONE);}



function set_auth($auth){$this->auth=$auth;}



function enabled($enabled=MENU_ENABLED){$this->enabled=$enabled;}



function set_block($block){$this->block=$block;}



function set_block_position($position){$this->position=$position;}

## Getters ##



function get_title(){return $this->title;}



function get_auth(){return is_array($this->auth)?$this->auth:array('r-1'=>AUTH_MENUS,'r0'=>AUTH_MENUS,'r1'=>AUTH_MENUS);}



function get_id(){return $this->id;}



function get_block(){return $this->block;}



function get_block_position(){return $this->position;}



function is_enabled(){return $this->enabled;}








function display($tpl=false)
{
return '';
}






function admin_display()
{
return $this->display();
}






function cache_export()
{}



function cache_export_begin()
{
if(is_array($this->auth))
return '\'; $__auth='.preg_replace('`[\s]+`','',var_export($this->auth,true)).';if ($User->check_auth($__auth,1)){$__menu.=\'';
return '';
}




function cache_export_end()
{
if(is_array($this->auth))
return '\';}$__menu.=\'';
return '';
}




function id($id){$this->id=$id;}


## Private Methodss ##





function _assign(&$template)
{
import('core/menu_service');
MenuService::assign_positions_conditions($template,$this->get_block());
}





function _check_auth()
{
global $User;
return empty($this->auth)|| $User->check_auth($this->auth,MENU_AUTH_BIT);
}

## Private Attributes ##




var $id=0;




var $title='';




var $auth=null;




var $enabled=MENU_NOT_ENABLED;




var $block=BLOCK_POSITION__NOT_ENABLED;




var $position=-1;
}

?>