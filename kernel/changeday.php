<?php


























function change_day()
{
global $Sql,$CONFIG_USER;

#######Taches de maintenance#######
$yesterday_timestamp=time()-86400;


$Sql->query_inject("INSERT INTO ".DB_TABLE_STATS." (stats_year, stats_month, stats_day, nbr, pages, pages_detail) VALUES ('".gmdate_format('Y',$yesterday_timestamp,TIMEZONE_SYSTEM)."', '".gmdate_format('m',$yesterday_timestamp,TIMEZONE_SYSTEM)."', '".gmdate_format('d',$yesterday_timestamp,TIMEZONE_SYSTEM)."', 0, 0, '')",__LINE__,__FILE__);
$last_stats=$Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."stats");

#######Statistiques#######
$Sql->query_inject("UPDATE ".DB_TABLE_STATS_REFERER." SET yesterday_visit = today_visit",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_STATS_REFERER." SET today_visit = 0, nbr_day = nbr_day + 1",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_STATS_REFERER." WHERE last_update < '".(time()-604800)."'",__LINE__,__FILE__);


$pages_displayed=pages_displayed();

import('io/filesystem/file');
$pages_file=new File(PATH_TO_ROOT.'/cache/pages.txt');
$pages_file->delete();


$total_visit=$Sql->query("SELECT total FROM ".DB_TABLE_VISIT_COUNTER." WHERE id = 1",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_VISIT_COUNTER." WHERE id <> 1",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_VISIT_COUNTER." SET time = '".gmdate_format('Y-m-d',time(),TIMEZONE_SYSTEM)."', total = 1 WHERE id = 1",__LINE__,__FILE__);
$Sql->query_inject("INSERT INTO ".DB_TABLE_VISIT_COUNTER." (ip, time, total) VALUES('".USER_IP."', '".gmdate_format('Y-m-d',time(),TIMEZONE_SYSTEM)."', '0')",__LINE__,__FILE__);


$Sql->query_inject("UPDATE ".DB_TABLE_STATS." SET nbr = '".$total_visit."', pages = '".array_sum($pages_displayed)."', pages_detail = '".addslashes(serialize($pages_displayed))."' WHERE id = '".$last_stats."'",__LINE__,__FILE__);


Session::garbage_collector();


import('io/filesystem/folder');
$week=3600*24*7;
$cache_image_folder_path=new Folder(PATH_TO_ROOT.'/images/maths/');
foreach($cache_image_folder_path->get_files('`\.png$`')as $image)
{

if((time()-$image->get_last_modification_date())>$week)
{
$image->delete();
}
}


import('modules/modules_discovery_service');
$modules_loader=new ModulesDiscoveryService();
$modules=$modules_loader->get_available_modules('on_changeday');
foreach($modules as $module)
{
if($module->is_enabled())
{
$module->functionality('on_changeday');
}
}


$CONFIG_USER['delay_unactiv_max']=($CONFIG_USER['delay_unactiv_max']*3600*24);
if(!empty($CONFIG_USER['delay_unactiv_max'])&&$CONFIG_USER['activ_mbr']!=2)
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_MEMBER." WHERE timestamp < '".(time()-$CONFIG_USER['delay_unactiv_max'])."' AND user_aprob = 0",__LINE__,__FILE__);
}


if($CONFIG_USER['verif_code']=='1')
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_VERIF_CODE." WHERE timestamp < '".(time()-(3600*24))."'",__LINE__,__FILE__);
}


import('core/updates');
new Updates();
}
?>