<?php



























import('util/date');
import('util/url');
import('content/sitemap/site_map_element');







class SiteMapLink extends SiteMapElement
{











function SitemapLink($name='',$link=null,$change_freq=SITE_MAP_FREQ_MONTHLY,$priority=SITE_MAP_PRIORITY_AVERAGE,$last_modification_date=null)
{
$this->name=$name;
$this->set_link($link);
$this->set_change_freq($change_freq);
$this->set_priority($priority);
$this->set_last_modification_date($last_modification_date);
}





function get_name()
{
return $this->name;
}





function get_link()
{
return $this->link;
}







function get_change_freq()
{
return $this->change_freq;
}






function get_priority()
{
return $this->priority;
}





function get_last_modification_date()
{
return $this->last_modification_date;
}





function get_url()
{
if(is_object($this->link))
{
return $this->link->absolute();
}
else
{
return '';
}
}





function set_name($name)
{
$this->name=$name;
}





function set_link($link)
{
if(is_object($link))
{
$this->link=$link;
}
else if(is_string($link))
{
$this->link=new Url($link);
}
}







function set_change_freq($change_freq)
{

if(in_array($change_freq,array(SITE_MAP_FREQ_ALWAYS,SITE_MAP_FREQ_HOURLY,SITE_MAP_FREQ_DAILY,SITE_MAP_FREQ_WEEKLY,SITE_MAP_FREQ_MONTHLY,SITE_MAP_FREQ_YEARLY,SITE_MAP_FREQ_NEVER,SITE_MAP_FREQ_DEFAULT)))
{
$this->change_freq=$change_freq;
}
else
{
$this->change_freq=SITE_MAP_FREQ_DEFAULT;
}
}






function set_priority($priority)
{
if(in_array($priority,array(SITE_MAP_PRIORITY_MAX,SITE_MAP_PRIORITY_HIGH,SITE_MAP_PRIORITY_AVERAGE,SITE_MAP_PRIORITY_LOW,SITE_MAP_PRIORITY_MIN)))
{
$this->priority=$priority;
}
else
{
$this->priority=SITE_MAP_PRIORITY_AVERAGE;
}
}





function set_last_modification_date($last_modification_date)
{
if(is_object($last_modification_date))
{
$this->last_modification_date=$last_modification_date;
}
}















function export(&$export_config)
{
$display_date=is_object($this->last_modification_date);


$template=$export_config->get_link_stream();

$template->assign_vars(array(
'LOC'=>$this->get_url(),
'TEXT'=>htmlspecialchars($this->name,ENT_QUOTES),
'C_DISPLAY_DATE'=>$display_date,
'DATE'=>$display_date?$this->last_modification_date->to_date():'',
'ACTUALIZATION_FREQUENCY'=>$this->change_freq,
'PRIORITY'=>$this->priority,
'C_LINK'=>true
));

return $template->parse(TEMPLATE_STRING_MODE);
}

## Private elements ##



var $name='';



var $link;





var $change_freq=SITE_MAP_FREQ_DEFAULT;




var $last_modification_date;




var $priority=SITE_MAP_PRIORITY_AVERAGE;
}

?>