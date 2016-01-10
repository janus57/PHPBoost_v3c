<?php


























define('ADMIN_NOAUTH_DEFAULT',false);
define('GROUP_DEFAULT_IDSELECT','');
define('GROUP_DISABLE_SELECT','disabled="disabled" ');
define('GROUP_DISABLED_ADVANCED_AUTH',true);






class Group
{
## Public methods ##




function Group(&$groups_info)
{
$this->groups_name=array();
foreach($groups_info as $idgroup=>$array_group_info)
$this->groups_name[$idgroup]=$array_group_info['name'];
}







function add_member($user_id,$idgroup)
{
global $Sql;


$user_groups=$Sql->query("SELECT user_groups FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
if(strpos($user_groups,$idgroup.'|')===false)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_groups = '".(!empty($user_groups)?$user_groups:'').$idgroup."|' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
else
return false;


$group_members=$Sql->query("SELECT members FROM ".DB_TABLE_GROUP." WHERE id = '".$idgroup."'",__LINE__,__FILE__);
if(strpos($group_members,$user_id.'|')===false)
$Sql->query_inject("UPDATE ".DB_TABLE_GROUP." SET members = '".$group_members.$user_id."|' WHERE id = '".$idgroup."'",__LINE__,__FILE__);
else
return false;

return true;
}






function edit_member($user_id,$array_user_groups)
{
global $Sql;


$user_groups_old=$Sql->query("SELECT user_groups FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$array_user_groups_old=explode('|',$user_groups_old);


$array_diff_pos=array_diff($array_user_groups,$array_user_groups_old);
foreach($array_diff_pos as $key=>$idgroup)
{
if(!empty($idgroup))
$this->add_member($user_id,$idgroup);
}


$array_diff_neg=array_diff($array_user_groups_old,$array_user_groups);
foreach($array_diff_neg as $key=>$idgroup)
{
if(!empty($idgroup))
$this->remove_member($user_id,$idgroup);
}
}





function get_groups_array()
{
return $this->groups_name;
}






function remove_member($user_id,$idgroup)
{
global $Sql;


$user_groups=$Sql->query("SELECT user_groups FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_groups = '".str_replace($idgroup.'|','',$user_groups)."' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);


$members_group=$Sql->query("SELECT members FROM ".DB_TABLE_GROUP." WHERE id = '".$idgroup."'",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_GROUP." SET members = '".str_replace($user_id.'|','',$members_group)."' WHERE id = '".$idgroup."'",__LINE__,__FILE__);
}

var $groups_name;
var $groups_auth;
}

?>
