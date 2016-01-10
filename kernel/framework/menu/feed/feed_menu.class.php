<?php


























import('menu/menu');
import('content/syndication/feed');

define('FEED_MENU__CLASS','FeedMenu');







class FeedMenu extends Menu
{
## Public Methods ##
function FeedMenu($title,$module_id,$category=0,$name=DEFAULT_FEED_NAME,$number=10,$begin_at=0)
{
parent::Menu($title);
$this->module_id=$module_id;
$this->category=$category;
$this->name=$name;
$this->number=$number;
$this->begin_at=$begin_at;
}

## Getters ##



function get_module_id(){return $this->module_id;}





function get_url($relative=false)
{
import('util/url');
$url=new Url('/syndication.php?m='.$this->module_id.'&amp;cat='.$this->category.'&amp;name='.$this->name);
if($relative)
{
return $url->relative();
}
return $url->absolute();
}

## Setters ##




function set_module_id($value){$this->module_id=$value;}



function set_cat($value){$this->category=is_numeric($value)?numeric($value):0;}



function set_name($value){$this->name=$value;}

function display()
{
return Feed::get_parsed($this->module_id,$this->name,$this->category,
FeedMenu::get_template($this->get_title(),$this->get_block()),$this->number,$this->begin_at
);
}

function cache_export()
{
return parent::cache_export_begin().
'\';import(\'content/syndication/feed\');$__menu=Feed::get_parsed('.
var_export($this->module_id,true).','.var_export($this->name,true).','.
$this->category.',FeedMenu::get_template('.var_export($this->get_title(),true).', '.var_export($this->get_block(),true).'),'.$this->number.','.$this->begin_at.');'.
'$__menu.=\''.parent::cache_export_end();
}








function get_template($name='',$block_position=BLOCK_POSITION__LEFT)
{
$tpl=new Template('framework/menus/feed/feed.tpl');

$tpl->assign_vars(array(
'NAME'=>$name,
'C_NAME'=>!empty($name),
'C_VERTICAL_BLOCK'=>($block_position==BLOCK_POSITION__LEFT || $block_position==BLOCK_POSITION__RIGHT)
));

return $tpl;
}

## Private Attributes




var $url='';
var $module_id='';
var $name='';
var $category=0;
var $number=10;
var $begin_at=0;

}

?>