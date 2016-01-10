<?php


























define('FEEDS_PATH',PATH_TO_ROOT.'/cache/syndication/');
define('DEFAULT_FEED_NAME','master');
define('ERROR_GETTING_CACHE','Error regenerating and / or retrieving the syndication cache of the %s (%s)');

import('functions',INC_IMPORT);
import('content/syndication/feed_data');








class Feed
{
## Public Methods ##







function Feed($module_id,$name=DEFAULT_FEED_NAME,$id_cat=0)
{
$this->module_id=$module_id;
$this->name=$name;
$this->id_cat=$id_cat;
}





function load_data($data){$this->data=$data;}




function load_file($url){}










function export($template=false,$number=10,$begin_at=0)
{
import('content/parser/content_second_parser');

if($template===false)
{
$tpl=$this->tpl->copy();
}
else
{
$tpl=$template->copy();
}

global $User,$MODULES;
if($User->check_auth($MODULES[$this->module_id]['auth'],ACCESS_MODULE))
{
if(!empty($this->data))
{
$tpl->assign_vars(array(
'DATE'=>$this->data->get_date(),
'DATE_RFC822'=>$this->data->get_date_rfc822(),
'DATE_RFC3339'=>$this->data->get_date_rfc3339(),
'TITLE'=>$this->data->get_title(),
'U_LINK'=>$this->data->get_link(),
'HOST'=>$this->data->get_host(),
'DESC'=>htmlspecialchars($this->data->get_desc()),
'LANG'=>$this->data->get_lang()
));

$items=$this->data->subitems($number,$begin_at);
foreach($items as $item)
{
$desc=$item->get_desc();
$tpl->assign_block_vars('item',array(
'TITLE'=>$item->get_title(),
'U_LINK'=>$item->get_link(),
'U_GUID'=>$item->get_guid(),
'DESC'=>htmlspecialchars(second_parse($desc)),
'DATE'=>$item->get_date(),
'DATE_RFC822'=>$item->get_date_rfc822(),
'DATE_RFC3339'=>$item->get_date_rfc3339(),
'C_IMG'=>($item->get_image_url()!='')?true:false,
'U_IMG'=>$item->get_image_url()
));
}
}
}
return $tpl->parse(TEMPLATE_STRING_MODE);
}





function read()
{
if($this->is_in_cache())
{
$include=@include($this->get_cache_file_name());
if($include)
{
$this->data=$__feed_object;
return $this->export();
}
}
return '';
}




function cache()
{
Feed::update_cache($this->module_id,$this->name,$this->data,$this->id_cat);
}





function is_in_cache(){return file_exists($this->get_cache_file_name());}





function get_cache_file_name(){return FEEDS_PATH.$this->module_id.'_'.$this->name.'_'.$this->id_cat.'.php';}

## Private Methods ##
## Private attributes ##
var $module_id='';
var $id_cat=0;
var $name='';
var $str='';
var $tpl=null;
var $data=null;

## Statics Methods ##









function clear_cache($module_id=false)
{
import('io/filesystem/folder');
$folder=new Folder(FEEDS_PATH,OPEN_NOW);
$files=null;
if($module_id!==false)
{
$files=$folder->get_files('`'.$module_id.'_.*`');
}
else
{
$files=$folder->get_files();
}

foreach($files as $file)
$file->delete();
}










function update_cache($module_id,$name,&$data,$idcat=0)
{
import('io/filesystem/file');
$file=new File(FEEDS_PATH.$module_id.'_'.$name.'_'.$idcat.'.php',WRITE);
$file->write('<?php $__feed_object = unserialize('.var_export($data->serialize(),true).'); ?>');
$file->close();
}














function get_parsed($module_id,$name=DEFAULT_FEED_NAME,$idcat=0,$tpl=false,$number=10,$begin_at=0)
{

if(of_class($tpl,'template'))
{
$template=$tpl->copy();
}
else
{
import('io/template');
$template=new Template($module_id.'/framework/content/syndication/feed.tpl');
if(gettype($tpl)=='array')
$template->assign_vars($tpl);
}


$feed_data_cache_file=FEEDS_PATH.$module_id.'_'.$name.'_'.$idcat.'.php';
$result=@include($feed_data_cache_file);
if($result===false)
{
import('modules/modules_discovery_service');
$modules=new ModulesDiscoveryService();
$module=$modules->get_module($module_id);

if($module->got_error()||!$module->has_functionality('get_feed_data_struct'))
{

return '';
}

$data=$module->functionality('get_feed_data_struct',$idcat);
if(!$module->got_error())
{
Feed::update_cache($module_id,$name,$data,$idcat);
}
}
if(!DEBUG)
{
$result=@include($feed_data_cache_file);
}
else
{
if(file_exists($feed_data_cache_file))
{
$result=include($feed_data_cache_file);
}
else
{
$result=FALSE;
}
}
if($result===false)
{
user_error(sprintf(ERROR_GETTING_CACHE,$module_id,$idcat),E_USER_WARNING);
return '';
}

$feed=new Feed($module_id,$name);
$feed->load_data($__feed_object);
return $feed->export($template,$number,$begin_at);
}







function get_feed_menu($feed_url)
{
global $LANG,$CONFIG;

$feed_menu=new Template('framework/content/syndication/menu.tpl');

$feed_absolut_url=$CONFIG['server_name'].$CONFIG['server_path'].'/'.trim($feed_url,'/');

$feed_menu->assign_vars(array(
'PATH_TO_ROOT'=>TPL_PATH_TO_ROOT,
'PATH_TO_MENU'=>dirname($feed_menu->tpl),
'THEME'=>get_utheme(),
'U_FEED'=>$feed_absolut_url,
'SEPARATOR'=>strpos($feed_absolut_url,'?')!==false?'&amp;':'?',
'L_RSS'=>$LANG['rss'],
'L_ATOM'=>$LANG['atom']
));

return $feed_menu->parse(TEMPLATE_STRING_MODE);
}
}
?>
