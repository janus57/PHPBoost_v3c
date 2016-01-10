<?php




































class SiteMapElement
{




function SiteMapElement($name)
{
$this->name=$name;
}





function get_depth()
{
return $this->depth;
}





function get_name()
{
if(is_object($this->link))
{
return $this->link->get_name();
}
else
{
return '';
}
}





function set_depth($depth)
{
$this->depth=$depth;
}







function export(&$export_config)
{
}

## Private elements ##



var $depth=1;
}

?>
