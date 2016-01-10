<?php




























define('DEFAULT_ATOM_TEMPLATE','framework/content/syndication/atom.tpl');

import('io/template');
import('content/syndication/feed');








class ATOM extends Feed
{
## Public Methods ##






function ATOM($module_id,$feed_name=DEFAULT_FEED_NAME,$id_cat=0)
{
parent::Feed($module_id,$feed_name,$id_cat);
$this->tpl=new Template(DEFAULT_ATOM_TEMPLATE);
}





function load_file($url)
{
if(($file=@file_get_contents_emulate($url))!==false)
{
$this->data=new FeedData();
if(preg_match('`<entry>(.*)</entry>`is',$file))
{
$expParsed=explode('<entry>',$file);
$nbItems=(count($expParsed)-1)>$nbItems?$nbItems:count($expParsed)-1;

$this->data->set_date(preg_match('`<updated>(.*)</updated>`is',$expParsed[0],$var)?$var[1]:'');
$this->data->set_title(preg_match('`<title>(.*)</title>`is',$expParsed[0],$var)?$var[1]:'');
$this->data->set_link(preg_match('`<link href="(.*)"/>`is',$expParsed[0],$var)?$var[1]:'');
$this->data->set_host(preg_match('`<link href="(.*)"/>`is',$expParsed[0],$var)?$var[1]:'');

for($i=1;$i<=$nbItems;$i++)
{
$item=new FeedItem();

$item->set_title(preg_match('`<title>(.*)</title>`is',$expParsed[$i],$title)?$title[1]:'');
$item->set_link(preg_match('`<link href="(.*)"/>`is',$expParsed[$i],$url)?$url[1]:'');
$item->set_guid(preg_match('`<id>(.*)</id>`is',$expParsed[$i],$guid)?$guid[1]:'');
$item->set_desc(preg_match('`<summary>(.*)</summary>`is',$expParsed[$i],$desc)?$desc[1]:'');
$item->set_date_rfc3339(preg_match('`<updated>(.*)</updated>`is',$expParsed[$i],$date)?gmdate_format('date_format_tiny',strtotime($date[1])):'');

$this->data->add_item($item);
}
return true;
}
return false;
}
return false;
}

## Private Methods ##
## Private attributes ##
}

?>