<?php



























define('PHPBOOST_OFFICIAL_REPOSITORY','http://www.phpboost.com/repository/main.xml');
define('PHP_MIN_VERSION_UPDATES','5');

define('CHECK_KERNEL',0X01);
define('CHECK_MODULES',0X02);
define('CHECK_THEMES',0X04);
define('CHECK_ALL_UPDATES',CHECK_KERNEL|CHECK_MODULES|CHECK_THEMES);

import('core/application');
import('core/repository');






class Updates
{




function Updates($checks=CHECK_ALL_UPDATES)
{
$this->_load_apps($checks);
$this->_load_repositories();
$this->_check_repositories();
}





function _load_apps($checks=CHECK_ALL_UPDATES)
{
if(phpversion()>PHP_MIN_VERSION_UPDATES)
{
global $CONFIG;

if($checks&CHECK_KERNEL)
{
$this->apps[]=new Application('kernel',get_ulang(),APPLICATION_TYPE__KERNEL,phpboost_version(),PHPBOOST_OFFICIAL_REPOSITORY);
}

if($checks&CHECK_MODULES)
{
global $MODULES;

$kModules=array_keys($MODULES);
foreach($kModules as $module)
{
$infos=load_ini_file(PATH_TO_ROOT.'/'.$module.'/lang/',get_ulang());
$repository=!empty($infos['repository'])?$infos['repository']:PHPBOOST_OFFICIAL_REPOSITORY;
if(!empty($infos['repository']))
{
$this->apps[]=new Application($module,get_ulang(),APPLICATION_TYPE__MODULE,$infos['version'],$repository);
}
}
}

if($checks&CHECK_THEMES)
{
global $THEME_CONFIG;

$kThemes=array_keys($THEME_CONFIG);
foreach($kThemes as $theme)
{
$infos=get_ini_config(PATH_TO_ROOT.'/templates/'.$theme.'/config/',get_ulang());
if(!empty($infos['repository']))
{
$this->apps[]=new Application($theme,get_ulang(),APPLICATION_TYPE__TEMPLATE,$infos['version'],$infos['repository']);
}
}
}
}
}




function _load_repositories()
{
if(phpversion()>PHP_MIN_VERSION_UPDATES)
{
foreach($this->apps as $app)
{
$rep=$app->get_repository();
if(!empty($rep)&&!isset($this->repositories[$rep]))
$this->repositories[$rep]=new Repository($rep);
}
}
}




function _check_repositories()
{
if(phpversion()>PHP_MIN_VERSION_UPDATES)
{
foreach($this->apps as $app)
{
$result=$this->repositories[$app->get_repository()]->check($app);
if($result!==null)
{
$this->_add_update_alert($result);
}
}
}
}




function _add_update_alert(&$app)
{
import('events/administrator_alert_service');
$identifier=$app->get_identifier();

if(AdministratorAlertService::find_by_identifier($identifier,'updates','kernel')===null)
{
$alert=new AdministratorAlert();
global $LANG,$CONFIG;
require_once(PATH_TO_ROOT.'/lang/'.get_ulang().'/admin.php');
if($app->get_type()==APPLICATION_TYPE__KERNEL)
$alert->set_entitled(sprintf($LANG['kernel_update_available'],$app->get_version()));
else
$alert->set_entitled(sprintf($LANG['update_available'],$app->get_type(),$app->get_name(),$app->get_version()));

$alert->set_fixing_url('admin/updates/detail.php?identifier='.$identifier);
$alert->set_priority($app->get_priority());
$alert->set_properties(serialize($app));
$alert->set_type('updates');
$alert->set_identifier($identifier);


AdministratorAlertService::save_alert($alert);
}
}

var $repositories=array();
var $apps=array();
};

?>
