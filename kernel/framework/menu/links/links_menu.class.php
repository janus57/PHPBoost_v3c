<?php


























import('menu/links/links_menu_link');

define('LINKS_MENU__CLASS','LinksMenu');

## Menu types ##
define('VERTICAL_MENU','vertical');
define('HORIZONTAL_MENU','horizontal');
define('TREE_MENU','tree');
define('VERTICAL_SCROLLING_MENU','vertical_scrolling');
define('HORIZONTAL_SCROLLING_MENU','horizontal_scrolling');









class LinksMenu extends LinksMenuElement
{
## Public Methods ##








function LinksMenu($title,$url,$image='',$type=VERTICAL_SCROLLING_MENU)
{

$this->type=in_array($type,LinksMenu::get_menu_types_list())?$type:VERTICAL_SCROLLING_MENU;


parent::LinksMenuElement($title,$url,$image);
}





function add_array(&$menu_elements)
{
foreach($menu_elements as $element)
$this->add($element);
}





function add($element)
{
if(get_class($element)==get_class($this))
$element->_parent($this->type);
else
$element->_parent();

$this->elements[]=$element;
}




function update_uid()
{
parent::update_uid();
foreach($this->elements as $element)
$element->update_uid();
}






function display($template=false,$mode=LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
{

if(!$this->_check_auth())
{
return '';
}


if(!is_object($template)|| strtolower(get_class($template))!='template')
{
$tpl=new Template('framework/menus/links/'.$this->type.'.tpl');
}
else
{
$tpl=$template->copy();
}
$original_tpl=$tpl->copy();


foreach($this->elements as $element)
{
$tpl->assign_block_vars('elements',array('DISPLAY'=>$element->display($original_tpl->copy(),$mode)));
}


parent::_assign($tpl,$mode);
$tpl->assign_vars(array(
'C_MENU'=>true,
'C_NEXT_MENU'=>($this->depth>0)?true:false,
'C_FIRST_MENU'=>($this->depth==0)?true:false,
'C_HAS_CHILD'=>count($this->elements)>0
));

return $tpl->parse(TEMPLATE_STRING_MODE);
}





function cache_export($template=false)
{

if(!is_object($template)|| strtolower(get_class($template))!='template')
{
$tpl=new Template('framework/menus/links/'.$this->type.'.tpl');
}
else
{
$tpl=$template->copy();
}
$original_tpl=$tpl->copy();


foreach($this->elements as $element)
{
$tpl->assign_block_vars('elements',array('DISPLAY'=>$element->cache_export($original_tpl->copy())));
}


parent::_assign($tpl,LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING);
$tpl->assign_vars(array(
'C_MENU'=>true,
'C_NEXT_MENU'=>$this->depth>0,
'C_FIRST_MENU'=>$this->depth==0,
'C_HAS_CHILD'=>count($this->elements)>0,
'ID'=>'##.#GET_UID#.##',
'ID_VAR'=>'##.#GET_UID_VAR#.##',
));

if($this->depth==0)
{
$cache_str=parent::cache_export_begin().'\'.'.
var_export($tpl->parse(TEMPLATE_STRING_MODE),true).
'.\''.parent::cache_export_end();
$cache_str=str_replace(
array('#GET_UID#','#GET_UID_VAR#','##'),
array('($__uid = get_uid())','$__uid','\''),
$cache_str
);
return $cache_str;
}
return parent::cache_export_begin().$tpl->parse(TEMPLATE_STRING_MODE).parent::cache_export_end();
}

## Getters ##




function get_type(){return $this->type;}






function set_type($type){$this->type=$type;}




function get_children(){return $this->elements;}







function get_menu_types_list()
{
return array(VERTICAL_MENU,HORIZONTAL_MENU,VERTICAL_SCROLLING_MENU,HORIZONTAL_SCROLLING_MENU);
}

## Private Methods ##





function _parent($type)
{
parent::_parent($type);

$this->type=$type;
foreach($this->elements as $element)
{
$element->_parent($type);
}
}
## Private attributes ##





var $type;




var $elements=array();
}

?>
