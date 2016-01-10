<?php




























import('content/sitemap/module_map');
import('content/sitemap/site_map_link');
import('content/sitemap/site_map_section');
import('content/sitemap/site_map_export_config');





define('SITE_MAP_AUTH_GUEST',false);



define('SITE_MAP_AUTH_USER',true);





define('SITE_MAP_USER_MODE',true);




define('SITE_MAP_SEARCH_ENGINE_MODE',false);


define('SITE_MAP_FREQ_ALWAYS','always');
define('SITE_MAP_FREQ_HOURLY','hourly');
define('SITE_MAP_FREQ_DAILY','daily');
define('SITE_MAP_FREQ_WEEKLY','weekly');
define('SITE_MAP_FREQ_MONTHLY','monthly');
define('SITE_MAP_FREQ_YEARLY','yearly');
define('SITE_MAP_FREQ_NEVER','never');
define('SITE_MAP_FREQ_DEFAULT',SITE_MAP_FREQ_MONTHLY);


define('SITE_MAP_PRIORITY_MAX','1');
define('SITE_MAP_PRIORITY_HIGH','0.75');
define('SITE_MAP_PRIORITY_AVERAGE','0.5');
define('SITE_MAP_PRIORITY_LOW','0.25');
define('SITE_MAP_PRIORITY_MIN','0');








class SiteMap
{




function SiteMap($site_name='',$elements=null)
{
if(is_array($elements))
{
$this->elements=$elements;
}
$this->set_site_name($site_name);
}





function get_site_name()
{
return $this->site_name;
}





function set_site_name($site_name)
{
global $CONFIG;
if(!empty($site_name))
{
$this->site_name=$CONFIG['site_name'];
}
elseif(empty($this->site_name))
{
$this->site_name=$CONFIG['site_name'];
}
}





function add($element)
{
$this->elements[]=$element;
}












function export(&$export_config)
{

$template=$export_config->get_site_map_stream();

$template->assign_vars(array(
'C_SITE_MAP'=>true,
'SITE_NAME'=>htmlspecialchars($this->site_name,ENT_QUOTES)
));


foreach($this->elements as $element)
{
$template->assign_block_vars('element',array(
'CODE'=>$element->export($export_config)
));
}
return $template->parse(TEMPLATE_STRING_MODE);
}




function build_modules_maps()
{
import('modules/modules_discovery_service');

$Modules=new ModulesDiscoveryService();
foreach($Modules->get_available_modules('get_module_map')as $module)
{
$module_map=$module->get_module_map(SITE_MAP_AUTH_USER);
$this->add($module_map);
}
}




function build_kernel_map($mode=SITE_MAP_USER_MODE,$auth_mode=SITE_MAP_AUTH_GUEST)
{
global $CONFIG,$LANG,$User;


$kernel_map=new ModuleMap(new SiteMapLink($LANG['home'],new Url($CONFIG['start_page'])));


$kernel_map->set_description(nl2br($CONFIG['site_desc']));


if($mode==SITE_MAP_USER_MODE)
{
$kernel_map->add(new SiteMapLink($LANG['members_list'],new Url('/member/member.php')));


if($auth_mode==SITE_MAP_AUTH_USER&&$User->check_level(MEMBER_LEVEL))
{

$member_space_section=new SiteMapSection(new SiteMapLink($LANG['my_private_profile'],
new Url('/member/'.url('member.php?id='.$User->get_id().'&amp;view=1','member-'.$User->get_id().'.php?view=1'))));


$member_space_section->add(new SiteMapLink($LANG['profile_edition'],
new Url('/member/'.url('member.php?id='.$User->get_id().'&amp;edit=1','member-'.$User->get_id().'.php?edit=1'))));


$member_space_section->add(new SiteMapLink($LANG['private_messaging'],
new Url('/member/'.url('pm.php?pm='.$User->get_id(),'pm-'.$User->get_id().'.php'))));


$member_space_section->add(new SiteMapLink($LANG['contribution_panel'],new Url('/member/contribution_panel.php')));


if($User->check_level(ADMIN_LEVEL))
{
$member_space_section->add(new SiteMapLink($LANG['admin_panel'],new Url('/admin/admin_index.php')));
}


$kernel_map->add($member_space_section);
}
}


$this->add($kernel_map);
}

## Private elements ##



var $elements=array();



var $site_name='';
}

?>
