<?php


























define('APPLICATION_TYPE__KERNEL','kernel');
define('APPLICATION_TYPE__MODULE','module');
define('APPLICATION_TYPE__TEMPLATE','template');

import('util/date');
import('events/administrator_alert');






class Application
{








function Application($id,$language,$type=APPLICATION_TYPE__MODULE,$version=0,$repository='')
{
$this->id=$id;
$this->name=$id;
$this->language=$language;
$this->type=$type;

$this->repository=$repository;

$this->version=$version;

$this->pubdate=new Date();
}




function load(&$xml_desc)
{
$attributes=$xml_desc->attributes();

$name=Application::_get_attribute($xml_desc,'name');
$this->name=!empty($name)?$name:$this->id;

$this->language=Application::_get_attribute($xml_desc,'language');

$language=Application::_get_attribute($xml_desc,'localized_language');
$this->localized_language=!empty($language)?($language):$this->language;

$this->version=Application::_get_attribute($xml_desc,'num');

$this->compatibility_min=Application::_get_attribute($xml_desc,'min','compatibility');
$this->compatibility_max=Application::_get_attribute($xml_desc,'max','compatibility');

$pubdate=Application::_get_attribute($xml_desc,'pubdate');
if(!empty($pubdate))
{
$this->pubdate=new Date(DATE_FROM_STRING,TIMEZONE_SYSTEM,$pubdate,'y/m/d');
}
else
{
$this->pubdate=new Date();
}

$this->security_update=Application::_get_attribute($xml_desc,'security-update');
$this->security_update=strtolower($this->security_update)=='true'?true:false;

$this->priority=Application::_get_attribute($xml_desc,'priority');
switch($this->priority)
{
case 'high':
$this->priority=ADMIN_ALERT_HIGH_PRIORITY;
break;
case 'medium':
$this->priority=ADMIN_ALERT_MEDIUM_PRIORITY;
break;
default:
$this->priority=ADMIN_ALERT_LOW_PRIORITY;
break;
}
if($this->security_update)
$this->priority++;

$this->download_url=Application::_get_attribute($xml_desc,'url','//download');
$this->update_url=Application::_get_attribute($xml_desc,'url','//update');;

$this->authors=array();
$authors_elts=$xml_desc->xpath('authors/author');
foreach($authors_elts as $author)
{
$this->authors[]=array(
'name'=>Application::_get_attribute($author,'name'),
'email'=>Application::_get_attribute($author,'email')
);
}

$this->description=$xml_desc->xpath('description');
$this->description=utf8_decode((string)$this->description[0]);

$this->new_features=array();
$this->improvments=array();
$this->bug_corrections=array();
$this->security_improvments=array();

$novelties=$xml_desc->xpath('whatsnew/new');
foreach($novelties as $novelty)
{
$attributes=$novelty->attributes();
$type=isset($attributes['type'])?$attributes['type']:'feature';
switch($type)
{
case 'improvment':
$this->improvments[]=utf8_decode((string)$novelty);
break;
case 'bug':
$this->bug_corrections[]=utf8_decode((string)$novelty);
break;
case 'security':
$this->security_improvments[]=utf8_decode((string)$novelty);
break;
default:
$this->new_features[]=utf8_decode((string)$novelty);
break;
}
}

$this->warning_level=Application::_get_attribute($xml_desc,'level','warning');
if(!empty($this->warning_level))
{
$this->warning=$xml_desc->xpath('warning');
$this->warning=utf8_decode((string)$this->warning[0]);
}
}




function get_identifier()
{
return md5($this->type.'_'.$this->id.'_'.$this->version.'_'.$this->language);
}




function check_compatibility()
{
global $CONFIG;
$current_version='0';
switch($this->type)
{
case APPLICATION_TYPE__KERNEL:
$current_version=phpboost_version();
break;
case APPLICATION_TYPE__MODULE:
$kModules=array_keys($MODULES);
foreach($kModules as $module)
{
if($module==$this->name)
{
$infos=load_ini_file(PATH_TO_ROOT.'/'.$module.'/lang/',get_ulang());
$current_version=$infos['version'];
break;
}
}
break;
case APPLICATION_TYPE__TEMPLATE:
global $THEME_CONFIG;
$kThemes=array_keys($THEME_CONFIG);
foreach($kThemes as $theme)
{
if($theme==$this->name)
{
$infos=get_ini_config(PATH_TO_ROOT.'/templates/'.$theme.'/config/',get_ulang());
$current_version=$infos['version'];
break;
}
}
break;
default:
return false;
}

if($current_version=='0')
{
return false;
}

return version_compare($current_version,$this->get_version(),'<')>0&&
(($CONFIG['version']>=$this->compatibility_min)&&($this->compatibility_max==null ||
($CONFIG['version']<=$this->compatibility_max&&$this->compatibility_max>=$this->compatibility_min)));
}

## PUBLIC ACCESSORS ##



function get_id(){return $this->id;}



function get_name(){return $this->name;}



function get_language(){return $this->language;}



function get_localized_language(){return!empty($this->localized_language)?$this->localized_language:$this->language;}



function get_type(){return $this->type;}



function get_repository(){return $this->repository;}



function get_version(){return $this->version;}



function get_compatibility_min(){return $this->compatibility_min;}



function get_compatibility_max(){return $this->compatibility_max;}



function get_pubdate(){return!empty($this->pubdate)&&is_object($this->pubdate)?$this->pubdate->format(DATE_FORMAT_SHORT,TIMEZONE_USER):'';}



function get_priority(){return $this->priority;}



function get_security_update(){return $this->security_update;}



function get_download_url(){return $this->download_url;}



function get_update_url(){return $this->update_url;}



function get_authors(){return $this->authors;}



function get_description(){return $this->description;}



function get_new_features(){return $this->new_features;}



function get_improvments(){return $this->improvments;}



function get_bug_corrections(){return $this->bug_corrections;}



function get_security_improvments(){return $this->security_improvments;}



function get_warning_level(){return $this->warning_level;}



function get_warning(){return $this->warning;}

## PRIVATE METHODS ##







function _get_attribute(&$xdoc,$attibute_name,$xpath_query='.')
{
$elements=$xdoc->xpath($xpath_query);
if(count($elements)>0)
{
$attributes=$elements[0]->attributes();
return isset($attributes[$attibute_name])?utf8_decode((string)$attributes[$attibute_name]):null;
}
return null;
}




function _get_installed_version()
{
global $CONFIG;
switch($this->type)
{
case APPLICATION_TYPE__KERNEL:
return $CONFIG['version'];
case APPLICATION_TYPE__MODULE:
$infos=get_ini_config(PATH_TO_ROOT.'/'.$this->id.'/lang/',get_ulang());
return!empty($infos['version'])?$infos['version']:'0';
case APPLICATION_TYPE__THEME:
$infos=get_ini_config(PATH_TO_ROOT.'/templates/'.$this->id.'/config/',get_ulang());
return!empty($infos['version'])?$infos['version']:'0';
default:
return '0';
}
}

## PRIVATE ATTRIBUTES ##

var $id='';
var $name='';
var $language='';
var $localized_language='';
var $type='';

var $repository='';

var $version='';
var $compatibility_min='';
var $compatibility_max='';
var $pubdate=null;
var $priority=null;
var $security_update=false;

var $download_url='';
var $update_url='';

var $authors=array();

var $description='';
var $new_features=array();
var $improvments=array();
var $bug_corrections=array();
var $security_improvments=array();

var $warning_level=null;
var $warning=null;

};

?>
