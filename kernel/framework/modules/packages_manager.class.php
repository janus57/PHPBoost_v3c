<?php




























define('MODULE_INSTALLED',0);
define('MODULE_UNINSTALLED',0);
define('UNEXISTING_MODULE',1);
define('MODULE_ALREADY_INSTALLED',2);
define('CONFIG_CONFLICT',3);
define('NOT_INSTALLED_MODULE',4);
define('MODULE_FILES_COULD_NOT_BE_DROPPED',5);
define('PHP_VERSION_CONFLICT',6);

define('GENERATE_CACHE_AFTER_THE_OPERATION',true);
define('DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION',false);







class PackagesManager
{















function install_module($module_identifier,$enable_module=true,$generate_cache=GENERATE_CACHE_AFTER_THE_OPERATION)
{
global $Cache,$Sql,$CONFIG,$MODULES;

if(empty($module_identifier)||!is_dir(PATH_TO_ROOT.'/'.$module_identifier))
{
return UNEXISTING_MODULE;
}


$check_module=(int)$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_MODULES." WHERE name = '".strprotect($module_identifier)."'",__LINE__,__FILE__);
if($check_module>0)
{
return MODULE_ALREADY_INSTALLED;
}


$info_module=load_ini_file(PATH_TO_ROOT.'/'.$module_identifier.'/lang/',get_ulang());
if(empty($info_module))
{
return UNEXISTING_MODULE;
}

if(!empty($info_module['php_version']))
{
$phpversion=phpversion();
if(strpos(phpversion(),'-')!==FALSE)
{
$phpversion=substr($phpversion,0,strpos(phpversion(),'-'));
}
if(version_compare($phpversion,$info_module['php_version'],'lt'))
{
return PHP_VERSION_CONFLICT;
}
}


$dir_db_module=get_ulang();
$dir=PATH_TO_ROOT.'/'.$module_identifier.'/db';
if(!is_dir($dir.'/'.$dir_db_module))
{
import('io/filesystem/folder');
$db_scripts_folder=new Folder($dir);

$existing_db_files=$db_scripts_folder->get_folders('`[a-z_-]+`i');
$dir_db_module=count($existing_db_files)>0?$existing_db_files[0]->get_name():'';
}


$config=get_ini_config(PATH_TO_ROOT.'/'.$module_identifier.'/lang/',get_ulang());

if(!empty($config))
{
$check_config=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_CONFIGS." WHERE name = '".$module_identifier."'",__LINE__,__FILE__);
if(empty($check_config))
{
$Sql->query_inject("INSERT INTO ".DB_TABLE_CONFIGS." (name, value) VALUES ('".$module_identifier."', '".addslashes($config)."');",__LINE__,__FILE__);
}
else
{
return CONFIG_CONFLICT;
}
}


$sql_file=PATH_TO_ROOT.'/'.$module_identifier.'/db/'.$dir_db_module.'/'.$module_identifier.'.'.DBTYPE.'.sql';
if(file_exists($sql_file))
{
$Sql->parse($sql_file,PREFIX);
}

$module_identifier=strprotect($module_identifier);


import('core/menu_service');
MenuService::add_mini_module($module_identifier);


$Sql->query_inject("INSERT INTO ".DB_TABLE_MODULES." (name, version, auth, activ) VALUES ('".$module_identifier."', '".addslashes($info_module['version'])."', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', '".((int)$enable_module)."')",__LINE__,__FILE__);


$php_file=PATH_TO_ROOT.'/'.$module_identifier.'/db/'.$dir_db_module.'/'.$module_identifier.'.php';
if(file_exists($php_file)){
if(!DEBUG)
{
@include_once($php_file);
}
else
{
include_once($php_file);
}
}


if($generate_cache)
{
$Cache->Generate_file('modules');
$Cache->load('modules',RELOAD_CACHE);
$Cache->Generate_file('css');
MenuService::generate_cache();


if($CONFIG['rewrite']==1&&!empty($info_module['url_rewrite']))
{
$Cache->Generate_file('htaccess');
}
}


$Cache->generate_module_file($module_identifier,NO_FATAL_ERROR_CACHE);

return MODULE_INSTALLED;
}













function uninstall_module($module_id,$drop_files)
{
global $Cache,$Sql,$CONFIG,$MODULES;

$module_name=$Sql->query("SELECT name FROM ".DB_TABLE_MODULES." WHERE id = '".$module_id."'",__LINE__,__FILE__);


if(!empty($module_id)&&!empty($module_name))
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_MODULES." WHERE id = '".$module_id."'",__LINE__,__FILE__);


$info_module=load_ini_file(PATH_TO_ROOT.'/'.$module_name.'/lang/',get_ulang());


$Cache->delete_file($module_name);


if(!empty($info_module['com']))
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_COM." WHERE script = '".addslashes($info_module['com'])."'",__LINE__,__FILE__);
}


if(!empty($info_module))
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_CONFIGS." WHERE name = '".addslashes($module_name)."'",__LINE__,__FILE__);
}


import('core/menu_service');
MenuService::delete_mini_module($module_name);
MenuService::delete_module_feeds_menus($module_name);

$dir_db_module=get_ulang();
$dir=PATH_TO_ROOT.'/'.$module_name.'/db';


import('io/filesystem/folder');
$folder_path=new Folder($dir.'/'.$dir_db_module);
foreach($folder_path->get_folders('`^[a-z0-9_ -]+$`i')as $dir)
{
$dir_db_module=$dir->get_name();
break;
}

if(file_exists(PATH_TO_ROOT.'/'.$module_name.'/db/'.$dir_db_module.'/uninstall_'.$module_name.'.'.DBTYPE.'.sql'))
{
$Sql->parse(PATH_TO_ROOT.'/'.$module_name.'/db/'.$dir_db_module.'/uninstall_'.$module_name.'.'.DBTYPE.'.sql',PREFIX);
}

if(file_exists(PATH_TO_ROOT.'/'.$module_name.'/db/'.$dir_db_module.'/uninstall_'.$module_name.'.php'))
{
@include_once(PATH_TO_ROOT.'/'.$module_name.'/db/'.$dir_db_module.'/uninstall_'.$module_name.'.php');
}

$Cache->Generate_file('modules');
$Cache->Generate_file('css');
MenuService::generate_cache();

import('content/syndication/feed');
Feed::clear_cache();


if($CONFIG['rewrite']==1&&!empty($info_module['url_rewrite']))
{
$Cache->Generate_file('htaccess');
}


if($drop_files)
{
$folder=new Folder(PATH_TO_ROOT.'/'.$module_name);
if(!$folder->delete())
{
return MODULE_FILES_COULD_NOT_BE_DROPPED;
}
}

return MODULE_UNINSTALLED;
}
else
{
return NOT_INSTALLED_MODULE;
}
}
}

?>