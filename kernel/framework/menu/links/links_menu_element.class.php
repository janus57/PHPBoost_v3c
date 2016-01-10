<?php






























import('menu/menu');
import('util/url');

define('LINKS_MENU_ELEMENT__CLASS','LinksMenuElement');
define('LINKS_MENU_ELEMENT__FULL_DISPLAYING',true);
define('LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING',false);








class LinksMenuElement extends Menu
{
## Public Methods ##







function LinksMenuElement($title,$url,$image='')
{
parent::Menu($title);
$this->set_url($url);
$this->set_image($image);
$this->uid=get_uid();
}

## Setters ##



function set_image($image)
{
$this->image=Url::get_relative($image);
}



function set_url($url)
{
$this->url=Url::get_relative($url);
}

## Getters ##




function get_uid()
{
return $this->uid;
}



function update_uid()
{
$this->uid=get_uid();
}




function get_url($compute_relative_url=true)
{
return $this->_get_url($this->url,$compute_relative_url);
}





function get_image($compute_relative_url=true)
{
return $this->_get_url($this->image,$compute_relative_url);
}






function _get_url($string_url,$compute_relative_url=true)
{
$url=new Url($string_url);
if($compute_relative_url)
{
return $url->relative();
}
else
{
return $url->absolute();
}
}





function cache_export_begin()
{
return str_replace('\'','##',parent::cache_export_begin());
}





function cache_export_end()
{
return str_replace('\'','##',parent::cache_export_end());
}









function display($template=false,$mode=LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
{
}





function cache_export()
{
return parent::cache_export();
}

## Private Methods ##








function _assign(&$template,$mode=LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
{
parent::_assign($template);
$template->assign_vars(array(
'TITLE'=>$this->title,
'C_FIRST_LEVEL'=>$this->depth==1,
'DEPTH'=>$this->depth,
'PARENT_DEPTH'=>$this->depth-1,
'C_URL'=>!empty($this->url),
'C_IMG'=>!empty($this->image),
'ABSOLUTE_URL'=>$this->get_url(false),
'ABSOLUTE_IMG'=>$this->get_image(false),
'RELATIVE_URL'=>$this->get_url(true),
'RELATIVE_IMG'=>$this->get_image(true),
'ID'=>$this->get_uid(),
'ID_VAR'=>$this->get_uid()
));


if($mode)
{
$template->assign_vars(array(
'AUTH_FORM'=>Authorizations::generate_select(AUTH_MENUS,$this->get_auth(),array(),'menu_element_'.$this->uid.'_auth')
));
}
}





function _parent()
{
$this->depth++;
}


## Private attributes ##




var $url='';




var $image='';




var $uid=null;




var $depth=0;
}

?>
