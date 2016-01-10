<?php


























define('RANK_TYPE',1);
define('GROUP_TYPE',2);
define('USER_TYPE',3);






class User
{





function User($session_data,&$groups_info)
{
$this->user_data=$session_data;


$groups_auth=array();
foreach($groups_info as $idgroup=>$array_info)
$groups_auth[$idgroup]=$array_info['auth'];
$this->groups_auth=$groups_auth;


$this->user_groups=explode('|',$session_data['user_groups']);
array_unshift($this->user_groups,'r'.$session_data['level']);
array_pop($this->user_groups);
}

## Public methods ##





function get_attribute($attribute)
{
return isset($this->user_data[$attribute])?$this->user_data[$attribute]:'';
}





function get_id()
{
return(int)$this->get_attribute('user_id');
}








function get_group_color($user_groups,$level=0)
{
global $_array_groups_auth;

$user_groups=explode('|',$user_groups);
array_pop($user_groups);
$i=0;
foreach($user_groups as $idgroup)
{
if($i++==0)
return(!empty($_array_groups_auth[$idgroup]['color'])&&$level==0)?'#'.$_array_groups_auth[$idgroup]['color']:'';
}
}






function check_level($secure)
{
if(isset($this->user_data['level'])&&$this->user_data['level']>=$secure)
return true;
return false;
}









function check_auth($array_auth_groups,$authorization_bit)
{

if($this->check_level(ADMIN_LEVEL))
return true;


if(!is_array($array_auth_groups))
return false;


return(bool)($this->_sum_auth_groups($array_auth_groups)&(int)$authorization_bit);
}







function check_max_value($key_auth,$max_value_compare=0)
{
if(!is_array($this->groups_auth))
return false;


$array_user_auth_groups=$this->_array_group_intersect($this->groups_auth);
$max_auth=$max_value_compare;
foreach($array_user_auth_groups as $idgroup=>$group_auth)
{
if($group_auth[$key_auth]==-1)
return-1;
else
$max_auth=max($max_auth,$group_auth[$key_auth]);
}

return $max_auth;
}





function get_groups()
{
return $this->user_groups;
}





function set_user_theme($user_theme)
{
$this->user_data['user_theme']=$user_theme;
}





function update_user_theme($user_theme)
{
global $Sql,$CONFIG_USER;

if($CONFIG_USER['force_theme']==0)
{
if($this->user_data['level']>-1)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_theme = '".strprotect($user_theme)."' WHERE user_id = '".$this->user_data['user_id']."'",__LINE__,__FILE__);
else
$Sql->query_inject("UPDATE ".DB_TABLE_SESSIONS." SET user_theme = '".strprotect($user_theme)."' WHERE level = -1 AND session_id = '".$this->user_data['session_id']."'",__LINE__,__FILE__);
}
}





function set_user_lang($user_lang)
{
$this->user_data['user_lang']=$user_lang;
}





function update_user_lang($user_lang)
{
global $Sql;

if($this->user_data['level']>-1)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_lang = '".strprotect($user_lang)."' WHERE user_id = '".$this->user_data['user_id']."'",__LINE__,__FILE__);
else
$Sql->query_inject("UPDATE ".DB_TABLE_SESSIONS." SET user_lang = '".strprotect($user_lang)."' WHERE level = -1 AND session_id = '".$this->user_data['session_id']."'",__LINE__,__FILE__);
}


## Private methods ##





function _sum_auth_groups($array_auth_groups)
{

$array_user_auth_groups=$this->_array_group_intersect($array_auth_groups);
$max_auth=0;
foreach($array_user_auth_groups as $idgroup=>$group_auth)
$max_auth |=(int)$group_auth;

return $max_auth;
}






function _array_group_intersect($array_auth_groups)
{
global $User;

$array_user_auth_groups=array();
foreach($array_auth_groups as $idgroup=>$auth_group)
{
if(is_numeric($idgroup))
{
if(in_array($idgroup,$this->user_groups))
$array_user_auth_groups[$idgroup]=$auth_group;
}
elseif(substr($idgroup,0,1)=='r')
{
if($User->get_attribute('level')>=(int)str_replace('r','',$idgroup))
$array_user_auth_groups[$idgroup]=$auth_group;
}
else
{
if($User->get_attribute('user_id')==(int)str_replace('m','',$idgroup))
$array_user_auth_groups[$idgroup]=$auth_group;
}
}

return $array_user_auth_groups;
}


## Private attributes ##
var $user_data;
var $groups_auth;
var $user_groups;
}

?>
