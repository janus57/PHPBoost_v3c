<?php



























import('content/sitemap/site_map_section');









class ModuleMap extends SiteMapSection
{




function ModuleMap($link)
{

parent::SiteMapSection($link);
}





function get_description()
{
return $this->description;
}





function set_description($description)
{
$this->description=$description;
}
















function export(&$export_config)
{

$template=$export_config->get_module_map_stream();

$template->assign_vars(array(
'MODULE_NAME'=>htmlspecialchars($this->get_name(),ENT_QUOTES),
'MODULE_DESCRIPTION'=>$this->description,
'MODULE_URL'=>!empty($this->link)?$this->link->get_url():'',
'DEPTH'=>$this->depth,
'LINK_CODE'=>is_object($this->link)?$this->link->export($export_config):'',
'C_MODULE_MAP'=>true
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



var $description;
}

?>
