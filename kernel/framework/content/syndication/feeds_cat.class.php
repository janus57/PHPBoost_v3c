<?php


























import('util/url');







class FeedsCat
{






function FeedsCat($module_id,$category_id,$category_name)
{
$this->id=$category_id;
$this->module_id=$module_id;
$this->cat_name=$category_name;
}






function get_url($feed_type='')
{
$url=new Url('/syndication.php?m='.$this->module_id.'&amp;cat='.$this->id.'&amp;name='.$feed_type);
return $url->relative();
}





function get_module_id()
{
return $this->module_id;
}






function get_category_id()
{
return $this->id;
}





function get_category_name()
{
return $this->cat_name;
}





function add_child($child)
{
$this->children[]=$child;
}





function get_children()
{
return $this->children;
}

var $id=0;
var $cat_name='';
var $module_id='';
var $children=array();
}

?>