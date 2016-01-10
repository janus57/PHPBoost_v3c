<?php


























import('menu/links/links_menu_element');

define('LINKS_MENU_LINK__CLASS','LinksMenuLink');







class LinksMenuLink extends LinksMenuElement
{
## Public Methods ##







function LinksMenuLink($title,$url,$image='')
{
parent::LinksMenuElement($title,$url,$image);
}






function display($template,$mode=LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
{

if(!$this->_check_auth())
return '';

parent::_assign($template,$mode);
$template->assign_vars(array(
'C_LINK'=>true
));

return $template->parse(TEMPLATE_STRING_MODE);
}





function cache_export($template)
{
parent::_assign($template);
$template->assign_vars(array(
'C_LINK'=>true
));
return parent::cache_export_begin().$template->parse(TEMPLATE_STRING_MODE).parent::cache_export_end();
}

## Private Methods ##

## Private attributes ##
}

?>
