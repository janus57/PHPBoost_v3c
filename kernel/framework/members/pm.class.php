<?php


























define('NOCHECK_PM_BOX',false);
define('CHECK_PM_BOX',true);
define('SYSTEM_PM',true);
define('DEL_PM_CONVERS',true);
define('UPDATE_MBR_PM',true);






class PrivateMsg
{
## Public Methods ##





function count_conversations($userid)
{
global $Sql;

$total_pm=$Sql->query("SELECT COUNT(*)
		FROM ".DB_TABLE_PM_TOPIC."
		WHERE
		(
			'".$userid."' IN (user_id, user_id_dest)
		)
		AND
		(
			user_convers_status = 0
			OR
			(
				(user_id_dest = '".$userid."' AND user_convers_status = 1)
				OR
				(user_id = '".$userid."' AND user_convers_status = 2)
			)
		)
		",__LINE__,__FILE__);
return $total_pm;
}










function start_conversation($pm_to,$pm_objet,$pm_contents,$pm_from,$system_pm=false)
{
global $CONFIG,$Sql;


if($system_pm)
{
$pm_from='-1';
$user_convers_status='1';
}
else
$user_convers_status='0';


$Sql->query_inject("INSERT INTO ".DB_TABLE_PM_TOPIC."  (title, user_id, user_id_dest, user_convers_status, user_view_pm, nbr_msg, last_user_id, last_msg_id, last_timestamp) VALUES ('".$pm_objet."', '".$pm_from."', '".$pm_to."', '".$user_convers_status."', 0, 0, '".$pm_from."', 0, '".time()."')",__LINE__,__FILE__);
$this->pm_convers_id=$Sql->insert_id("SELECT MAX(id) FROM ".DB_TABLE_PM_TOPIC." ");

$this->send($pm_to,$this->pm_convers_id,$pm_contents,$pm_from,$user_convers_status,false);
}










function send($pm_to,$pm_idconvers,$pm_contents,$pm_from,$pm_status,$check_pm_before_send=true)
{
global $Sql;


if($check_pm_before_send)
{
$info_convers=$Sql->query_array(DB_TABLE_PM_TOPIC." ","last_user_id","user_view_pm","WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
if($info_convers['last_user_id']!=$pm_from&&$info_convers['user_view_pm']>0)
{
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_pm = user_pm - '".$info_convers['user_view_pm']."' WHERE user_id = '".$pm_from."'",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_PM_TOPIC."  SET user_view_pm = 0 WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
}
}


$Sql->query_inject("INSERT INTO ".DB_TABLE_PM_MSG." (idconvers, user_id, contents, timestamp, view_status) VALUES('".$pm_idconvers."', '".$pm_from."', '".strparse($pm_contents)."', '".time()."', 0)",__LINE__,__FILE__);
$this->pm_msg_id=$Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."pm_msg");


$Sql->query_inject("UPDATE ".DB_TABLE_PM_TOPIC."  SET user_view_pm = user_view_pm + 1, nbr_msg = nbr_msg + 1, last_user_id = '".$pm_from."', last_msg_id = '".$this->pm_msg_id."', last_timestamp = '".time()."' WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);


$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_pm = user_pm + 1 WHERE user_id = '".$pm_to."'",__LINE__,__FILE__);
}









function delete_conversation($pm_userid,$pm_idconvers,$pm_expd,$pm_del,$pm_update)
{
global $CONFIG,$Sql;

$info_convers=$Sql->query_array(DB_TABLE_PM_TOPIC." ","user_view_pm","last_user_id","WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
if($pm_update&&$info_convers['last_user_id']!=$pm_userid)
{

if($info_convers['user_view_pm']>0)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_pm = user_pm - '".$info_convers['user_view_pm']."' WHERE user_id = '".$pm_userid."'",__LINE__,__FILE__);
}

if($pm_expd)
{
if($pm_del)
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_PM_TOPIC."  WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_PM_MSG." WHERE idconvers = '".$pm_idconvers."'",__LINE__,__FILE__);
}
else
$Sql->query_inject("UPDATE ".DB_TABLE_PM_TOPIC."  SET user_convers_status = 1 WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
}
else
{
if($pm_del)
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_PM_TOPIC."  WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_PM_MSG." WHERE idconvers = '".$pm_idconvers."'",__LINE__,__FILE__);
}
else
$Sql->query_inject("UPDATE ".DB_TABLE_PM_TOPIC."  SET user_convers_status = 2 WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
}
}








function delete($pm_to,$pm_idmsg,$pm_idconvers)
{
global $Sql;


$Sql->query_inject("DELETE FROM ".DB_TABLE_PM_MSG." WHERE id = '".$pm_idmsg."' AND idconvers = '".$pm_idconvers."'",__LINE__,__FILE__);

$pm_max_id=$Sql->query("SELECT MAX(id) FROM ".DB_TABLE_PM_MSG." WHERE idconvers = '".$pm_idconvers."'",__LINE__,__FILE__);
$pm_last_msg=$Sql->query_array(DB_TABLE_PM_MSG,'user_id','timestamp',"WHERE id = '".$pm_max_id."'",__LINE__,__FILE__);

if(!empty($pm_max_id))
{

$user_view_pm=$Sql->query("SELECT user_view_pm FROM ".DB_TABLE_PM_TOPIC." WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_PM_TOPIC."  SET nbr_msg = nbr_msg - 1, user_view_pm = '".($user_view_pm-1)."', last_user_id = '".$pm_last_msg['user_id']."', last_msg_id = '".$pm_max_id."', last_timestamp = '".$pm_last_msg['timestamp']."' WHERE id = '".$pm_idconvers."'",__LINE__,__FILE__);


$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET user_pm = user_pm - 1 WHERE user_id = '".$pm_to."'",__LINE__,__FILE__);
}

return $pm_max_id;
}

## Private attributes ##
var $pm_convers_id;
var $pm_msg_id;
}

?>