<?php


























import('events/administrator_alert');


define('ADMINISTRATOR_ALERT_TYPE',1);







class AdministratorAlertService
{






function find_by_id($alert_id)
{
global $Sql;


$result=$Sql->query_while("SELECT id, entitled, fixing_url, current_status, id_in_module, identifier, type, priority, creation_date, description
		FROM ".DB_TABLE_EVENTS."
		WHERE id = '".$alert_id."'
		ORDER BY creation_date DESC",__LINE__,__FILE__);

$properties=$Sql->fetch_assoc($result);

if((int)$properties['id']>0)
{

$alert=new AdministratorAlert();
$alert->build($properties['id'],$properties['entitled'],$properties['description'],$properties['fixing_url'],$properties['current_status'],new Date(DATE_TIMESTAMP,TIMEZONE_SYSTEM,$properties['creation_date']),$properties['id_in_module'],$properties['identifier'],$properties['type'],$properties['priority']);
return $alert;
}
else
{
return null;
}
}










function find_by_criteria($id_in_module=null,$type=null,$identifier=null)
{
global $Sql;
$criterias=array();

if($id_in_module!=null)
{
$criterias[]="id_in_module = '".intval($id_in_module)."'";
}

if($type!=null)
{
$criterias[]="type = '".strprotect($type)."'";
}

if($identifier!=null)
{
$criterias[]="identifier = '".strprotect($identifier)."'";
}


if(!empty($criterias))
{
$array_result=array();
$where_clause="contribution_type = '".ADMINISTRATOR_ALERT_TYPE."' AND ".implode($criterias," AND ");
$result=$Sql->query_while("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
			FROM ".DB_TABLE_EVENTS."
			WHERE ".$where_clause,__LINE__,__FILE__);

while($row=$Sql->fetch_assoc($result))
{
$alert=new AdministratorAlert();
$alert->build($row['id'],$row['entitled'],$row['description'],$row['fixing_url'],$row['current_status'],new Date(DATE_TIMESTAMP,TIMEZONE_SYSTEM,$row['creation_date']),$row['id_in_module'],$row['identifier'],$row['type'],$row['priority']);
$array_result[]=$alert;
}

return $array_result;
}

else
{
return AdministratorAlertService::get_all_alerts();
}
}








function find_by_identifier($identifier,$type='')
{
global $Sql;

$result=$Sql->query_while(
"SELECT id, entitled, fixing_url, current_status, creation_date, id_in_module, priority, identifier, type, description
    		FROM ".DB_TABLE_EVENTS."
    		WHERE identifier = '".addslashes($identifier)."'".(!empty($type)?" AND type = '".addslashes($type)."'":'')." ORDER BY creation_date DESC ".$Sql->limit(0,1).";"
,__LINE__,__FILE__);

if($row=$Sql->fetch_assoc($result))
{
$alert=new AdministratorAlert();
$alert->build($row['id'],$row['entitled'],$row['description'],$row['fixing_url'],$row['current_status'],new Date(DATE_TIMESTAMP,TIMEZONE_SYSTEM,$row['creation_date']),$row['id_in_module'],$row['identifier'],$row['type'],$row['priority']);

return $alert;
}
$Sql->query_close($result);

return null;
}











function get_all_alerts($criteria='creation_date',$order='desc',$begin=0,$number=20)
{
global $Sql;

$array_result=array();


$result=$Sql->query_while("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
		FROM ".DB_TABLE_EVENTS."
		WHERE contribution_type = ".ADMINISTRATOR_ALERT_TYPE."
		ORDER BY ".$criteria." ".strtoupper($order)." ".
$Sql->limit($begin,$number),__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
{
$alert=new AdministratorAlert();
$alert->build($row['id'],$row['entitled'],$row['description'],$row['fixing_url'],$row['current_status'],new Date(DATE_TIMESTAMP,TIMEZONE_SYSTEM,$row['creation_date']),$row['id_in_module'],$row['identifier'],$row['type'],$row['priority']);
$array_result[]=$alert;
}

$Sql->query_close($result);

return $array_result;
}






function save_alert(&$alert)
{
global $Sql,$Cache;


if($alert->get_id()>0)
{

$creation_date=$alert->get_creation_date();

$Sql->query_inject("UPDATE ".DB_TABLE_EVENTS." SET entitled = '".addslashes($alert->get_entitled())."', description = '".addslashes($alert->get_properties())."', fixing_url = '".addslashes($alert->get_fixing_url())."', current_status = '".$alert->get_status()."', creation_date = '".$creation_date->get_timestamp()."', id_in_module = '".$alert->get_id_in_module()."', identifier = '".addslashes($alert->get_identifier())."', type = '".addslashes($alert->get_type())."', priority = '".$alert->get_priority()."' WHERE id = '".$alert->get_id()."'",__LINE__,__FILE__);


if($alert->get_must_regenerate_cache())
{
$Cache->generate_file('member');
$alert->set_must_regenerate_cache(false);
}
}
else
{
$creation_date=new Date();
$Sql->query_inject("INSERT INTO ".DB_TABLE_EVENTS." (entitled, description, fixing_url, current_status, creation_date, id_in_module, identifier, type, priority) VALUES ('".addslashes($alert->get_entitled())."', '".addslashes($alert->get_properties())."', '".addslashes($alert->get_fixing_url())."', '".$alert->get_status()."', '".$creation_date->get_timestamp()."', '".$alert->get_id_in_module()."', '".addslashes($alert->get_identifier())."', '".addslashes($alert->get_type())."', '".$alert->get_priority()."')",__LINE__,__FILE__);
$alert->set_id($Sql->insert_id("SELECT MAX(id) FROM ".DB_TABLE_EVENTS));


$Cache->generate_file('member');
}
}





function delete_alert(&$alert)
{
global $Sql,$Cache;


if($alert->get_id()>0)
{
$Sql->query_inject("DELETE FROM ".DB_TABLE_EVENTS." WHERE id = '".$alert->get_id()."'",__LINE__,__FILE__);
$alert->set_id(0);
$Cache->generate_file('member');
}

}










function compute_number_unread_alerts()
{
global $Sql;

return array('unread'=>$Sql->query("SELECT count(*) FROM ".DB_TABLE_EVENTS." WHERE current_status = '".ADMIN_ALERT_STATUS_UNREAD."' AND contribution_type = '".ADMINISTRATOR_ALERT_TYPE."'",__LINE__,__FILE__),
'all'=>$Sql->query("SELECT count(*) FROM ".DB_TABLE_EVENTS." WHERE contribution_type = '".ADMINISTRATOR_ALERT_TYPE."'",__LINE__,__FILE__)
);
}






function get_number_unread_alerts()
{
global $ADMINISTRATOR_ALERTS;
return $ADMINISTRATOR_ALERTS['unread'];
}






function get_number_alerts()
{
global $ADMINISTRATOR_ALERTS;
return $ADMINISTRATOR_ALERTS['all'];
}
}

?>