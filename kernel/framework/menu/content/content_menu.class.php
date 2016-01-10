<?php


























import('menu/menu');

define('CONTENT_MENU__CLASS','ContentMenu');







class ContentMenu extends Menu
{
## Public Methods ##
function ContentMenu($title)
{
parent::Menu($title);
}

## Setters ##




function set_display_title($display_title){$this->display_title=$display_title;}




function set_content($content){$this->content=strparse($content,array(),DO_NOT_ADD_SLASHES);}

## Getters ##




function get_display_title(){return $this->display_title;}




function get_content(){return $this->content;}





function display()
{
$tpl=new Template('framework/menus/content/display.tpl');
$tpl->assign_vars(array(
'C_DISPLAY_TITLE'=>$this->display_title,
'C_VERTICAL_BLOCK'=>($this->get_block()==BLOCK_POSITION__LEFT || $this->get_block()==BLOCK_POSITION__RIGHT),
'TITLE'=>$this->title,
'CONTENT'=>second_parse($this->content)
));
return $tpl->parse(TEMPLATE_STRING_MODE);
}

function cache_export()
{
return parent::cache_export_begin().trim(var_export($this->display(),true),'\'').parent::cache_export_end();
}


## Private Attributes




var $content='';




var $display_title=true;

}

?>