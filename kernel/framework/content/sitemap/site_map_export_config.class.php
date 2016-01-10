<?php







































class SitemapExportConfig
{
## Public methods ##






function SitemapExportConfig($site_map_file,$module_map_file,$section_file,$link_file)
{

$this->site_map_file=is_string($site_map_file)?new Template($site_map_file):$site_map_file;
$this->module_map_file=is_string($module_map_file)?new Template($module_map_file):$module_map_file;
$this->section_file=is_string($section_file)?new Template($section_file):$section_file;
$this->link_file=is_string($link_file)?new Template($link_file):$link_file;
}





function get_site_map_stream()
{
return $this->site_map_file->copy();
}





function get_module_map_stream()
{
return $this->module_map_file->copy();
}





function get_section_stream()
{
return $this->section_file->copy();
}





function get_link_stream()
{
return $this->link_file->copy();
}





function set_site_map_stream($site_map_file)
{
$this->site_map_file=is_string($site_map_file)?new Template($site_map_file):$site_map_file;
}





function set_module_map_stream($module_map_file)
{
$this->module_map_file=is_string($module_map_file)?new Template($module_map_file):$module_map_file;
}





function set_section_stream($section_file)
{
$this->section_file=is_string($section_file)?new Template($section_file):$section_file;
}





function set_link_stream($link_file)
{
$this->link_file=is_string($link_file)?new Template($link_file):$link_file;
}





var $site_map_file;




var $module_map_file;




var $section_file;



var $link_file;
}

?>