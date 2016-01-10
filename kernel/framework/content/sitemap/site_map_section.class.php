<?php



























import('content/sitemap/site_map_element');







class SiteMapSection extends SiteMapElement
{




function SiteMapSection($link)
{
$this->set_link($link);
}





function get_link()
{
return $this->link;
}





function set_link($link)
{
$this->link=$link;
}





function set_depth($depth)
{
parent::set_depth($depth);

foreach($this->elements as $element)
{
$element->set_depth($depth+1);
}
}





function add($element)
{

$element->set_depth($this->depth+1);

$this->elements[]=$element;
}














function export(&$export_config)
{

$template=$export_config->get_section_stream();

$template->assign_vars(array(
'SECTION_NAME'=>htmlspecialchars($this->get_name(),ENT_QUOTES),
'SECTION_URL'=>!empty($this->link)?$this->link->get_url():'',
'DEPTH'=>$this->depth,
'LINK_CODE'=>is_object($this->link)?$this->link->export($export_config):'',
'C_SECTION'=>true
));

foreach($this->elements as $element)
{
$template->assign_block_vars('element',array(
'CODE'=>$element->export($export_config)
));
}
return $template->parse(TEMPLATE_STRING_MODE);
}

## Private elements ##



var $link;



var $elements=array();
}

?>
