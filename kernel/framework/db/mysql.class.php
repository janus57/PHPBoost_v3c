<?php


























define('LOW_PRIORITY','LOW_PRIORITY');
define('DB_NO_CONNECT',false);
define('ERRORS_MANAGEMENT_BY_RETURN',false);
define('EXPLICIT_ERRORS_MANAGEMENT',true);


define('CONNECTION_FAILED',1);
define('UNEXISTING_DATABASE',2);
define('CONNECTED_TO_DATABASE',3);

define('DBTYPE','mysql');
















class Sql
{



function Sql()
{
}





















function connect($sql_host,$sql_login,$sql_pass,$base_name,$errors_management=EXPLICIT_ERRORS_MANAGEMENT)
{

if($this->link=@mysql_connect($sql_host,$sql_login,$sql_pass))
{

if(@mysql_select_db($base_name,$this->link))
{
$this->connected=true;
$this->base_name=$base_name;
return CONNECTED_TO_DATABASE;
}
else
{

if($errors_management)
{
$this->_error('','Can \'t select database!',__LINE__,__FILE__);
}
else
{
return UNEXISTING_DATABASE;
}
}
}

else
{
if($errors_management)
{
$this->_error('','Can\'t connect to database!',__LINE__,__FILE__);
}
else
{
return CONNECTION_FAILED;
}
}
}







function auto_connect()
{

@include_once(PATH_TO_ROOT.'/kernel/db/config.php');


if(!defined('PHPBOOST_INSTALLED'))
{
import('util/unusual_functions',INC_IMPORT);
redirect(get_server_url_page('install/install.php'));
}


$result=$this->connect($sql_host,$sql_login,$sql_pass,$sql_base);
$this->base_name=$sql_base;
}











function query($query,$errline,$errfile)
{
$resource=mysql_query($query,$this->link)or $this->_error($query,'Invalid SQL request',$errline,$errfile);
if(is_resource($resource))
{
$result=mysql_fetch_row($resource);
$this->query_close($resource);
$this->req++;
return $result[0];
}
else
{
return false;
}
}















function query_array()
{
$table=func_get_arg(0);
$nbr_arg=func_num_args();

if(func_get_arg(1)!=='*')
{
$nbr_arg_field_end=($nbr_arg-4);
for($i=1;$i<=$nbr_arg_field_end;$i++)
{
if($i>1)
$field.=', '.func_get_arg($i);
else
$field=func_get_arg($i);
}
$end_req=' '.func_get_arg($nbr_arg-3);
}
else
{
$field='*';
$end_req=($nbr_arg>4)?' '.func_get_arg($nbr_arg-3):'';
}

$error_line=func_get_arg($nbr_arg-2);
$error_file=func_get_arg($nbr_arg-1);
$resource=mysql_query('SELECT '.$field.' FROM '.$table.$end_req,$this->link)or $this->_error('SELECT '.$field.' FROM '.$table.''.$end_req,'Invalid SQL request',$error_line,$error_file);
if($resource){
$result=mysql_fetch_assoc($resource);

$this->query_close($resource);
$this->req++;
return $result;
}else{
return false;
}

}











function query_inject($query,$errline,$errfile)
{
$resource=mysql_query($query,$this->link)or $this->_error($query,'Invalid inject request',$errline,$errfile);
$this->req++;

return $resource;
}










function query_while($query,$errline,$errfile)
{
$result=mysql_query($query,$this->link)or $this->_error($query,'invalid while request',$errline,$errfile);
$this->req++;

return $result;
}










function count_table($table,$errline,$errfile)
{
$resource=mysql_query('SELECT COUNT(*) AS total FROM '.PREFIX.$table,$this->link)or $this->_error('SELECT COUNT(*) AS total FROM '.PREFIX.$table,'Invalid count request',$errline,$errfile);
$result=mysql_fetch_assoc($resource);
$this->query_close($resource);
$this->req++;

return $result['total'];
}







function limit($start,$num_lines=0)
{
return ' LIMIT '.$start.', '.$num_lines;
}









function concat()
{
$nbr_args=func_num_args();
$concat_string=func_get_arg(0);
for($i=1;$i<$nbr_args;$i++)
{
$concat_string='CONCAT('.$concat_string.','.func_get_arg($i).')';
}

return ' '.$concat_string.' ';
}








function fetch_assoc($result)
{
return mysql_fetch_assoc($result);
}








function fetch_row($result)
{
return mysql_fetch_row($result);
}








function affected_rows($resource,$query='')
{
return mysql_affected_rows();
}







function num_rows($resource,$query)
{
return mysql_num_rows($resource);
}







function insert_id($query='')
{
return mysql_insert_id();
}






function date_diff($field)
{
return '(YEAR(CURRENT_DATE) - YEAR('.$field.')) - (RIGHT(CURRENT_DATE, 5) < RIGHT('.$field.', 5))';
}






function query_close($resource)
{
if(is_resource($resource))
return mysql_free_result($resource);
}





function close()
{
if($this->connected)
{
$this->connected=false;
return mysql_close($this->link);
}
else
{
return false;
}
}






function list_fields($table)
{
if(!empty($table))
{
$array_fields_name=array();
$result=$this->query_while("SHOW COLUMNS FROM ".$table." FROM `".$this->base_name."`",__LINE__,__FILE__);
if(!$result)return array();
while($row=mysql_fetch_row($result))
{
$array_fields_name[]=$row[0];
}
return $array_fields_name;
}
else
return array();
}



















function list_tables()
{
$array_tables=array();

$result=$this->query_while("SHOW TABLE STATUS FROM `".$this->base_name."` LIKE '".PREFIX."%'",__LINE__,__FILE__);
while($row=mysql_fetch_row($result))
{
$array_tables[$row[0]]=array(
'name'=>$row[0],
'engine'=>$row[1],
'row_format'=>$row[3],
'rows'=>$row[4],
'data_length'=>$row[6],
'index_lenght'=>$row[8],
'data_free'=>$row[9],
'collation'=>$row[14],
'auto_increment'=>$row[10],
'create_time'=>$row[11],
'update_time'=>$row[12]
);
}
return $array_tables;
}






function parse($file_path,$tableprefix='')
{
$handle_sql=@fopen($file_path,'r');
if($handle_sql)
{
$req='';
while(!feof($handle_sql))
{
$sql_line=trim(fgets($handle_sql));

if(!empty($sql_line)&&substr($sql_line,0,2)!=='--')
{

if(substr($sql_line,-1)==';')
{
if(empty($req))
$req=$sql_line;
else
$req.=' '.$sql_line;

if(!empty($tableprefix))
$this->query_inject(str_replace('phpboost_',$tableprefix,$req),__LINE__,__FILE__);
else
$this->query_inject($req,__LINE__,__FILE__);
$req='';
}
else
$req.=' '.$sql_line;
}
}
@fclose($handle);
}
}





function get_executed_requests_number()
{
return $this->req;
}







function highlight_query($query)
{
$query=' '.strtolower($query).' ';


$query=preg_replace('`(\s){2,}(\s){2,}`','$1',$query);


$query=preg_replace('`\b('.implode('|',array('select','update','insert into','from','left join','right join','cross join','natural join','inner join','left outer join','right outer join','full outer join','full join','drop','truncate','where','order by','group by','limit','having','union')).')+`',"\r\n".'$1',$query);


$query=preg_replace('`('.implode('|',array_map('preg_quote',array('*','=',',','!=','<>','>','<','.','(',')'))).')+`U','<span style="color:#FF00FF;">$1</span>',$query);


$key_words=array('select','update','delete','insert into','truncate','alter','table','status','set','drop','from','values','count','distinct','having','left','right','join','natural','outer','inner','between','where','group by','order by','limit','union','or','and','not','in','as','on','all','any','like','concat','substring','collate','collation','primary','key','default','null','exists','status','show');
$query=preg_replace_callback('`\b('.implode('|',$key_words).')+\b`',create_function('$matches','return \'<span style="color:#990099;">\' . strtoupper($matches[1]) . \'</span>\';'),$query);


$query=preg_replace('`\'(.+)\'`U','<span style="color:#008000;">\'$1\'</span>',$query);
$query=preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`','<span style="color:#008080;">$1</span>',$query);


$query=preg_replace('`(\s){2,}(\s){2,}`','$1',$query);

return nl2br(trim($query));
}







function indent_query($query)
{
$query=' '.strtolower($query).' ';


$query=preg_replace('`(\s){2,}(\s){2,}`','$1',$query);


$query=preg_replace('`\b('.implode('|',array('select','update','insert into','from','left join','right join','cross join','natural join','inner join','left outer join','right outer join','full outer join','full join','drop','truncate','where','order by','group by','limit','having','union')).')+`',"\r\n".'$1',$query);


$key_words=array('select','update','delete','insert into','truncate','alter','table','status','set','drop','from','values','count','distinct','having','left','right','join','natural','outer','inner','between','where','group by','order by','limit','union','or','and','not','in','as','on','all','any','like','concat','substring','collate','collation','primary','key','default','null','exists','status','show');
$query=preg_replace_callback('`\b('.implode('|',$key_words).')+\b`',create_function('$matches','return strtoupper($matches[1]);'),$query);


$query=preg_replace('`(\s){2,}(\s){2,}`','$1',$query);

return trim($query);
}





function get_dbms_version()
{
return 'MySQL '.mysql_get_server_info($this->link);
}





function get_data_base_name()
{
return $this->base_name;
}






function list_databases()
{
$db_list=mysql_list_dbs($this->link);

$result=array();

while($row=mysql_fetch_assoc($db_list))
$result[]=$row['Database'];

return $result;
}






function create_database($db_name)
{
$db_name=Sql::clean_database_name($db_name);
mysql_query("CREATE DATABASE `".$db_name."`");
return $db_name;
}






function escape($value)
{
if(function_exists('mysql_real_escape_string')&&!empty($this->link)&&is_resource($this->link))
{
return mysql_real_escape_string($value,$this->link);
}
elseif(is_string($value))
{
return str_replace("'","\\'",str_replace('\\','\\\\',str_replace("\0","\\\0",$value)));
}
else
{
return $value;
}
}





function optimize_tables($table_array)
{
global $Sql;

if(count($table_array)!=0)
$Sql->query_inject("OPTIMIZE TABLE ".implode(', ',$table_array),__LINE__,__FILE__);
}





function repair_tables($table_array)
{
if(count($table_array)!=0)
{
$this->query_inject("REPAIR TABLE ".implode(', ',$table_array),__LINE__,__FILE__);
}
}





function truncate_tables($table_array)
{
if(count($table_array)!=0)
$this->query_inject("TRUNCATE TABLE ".implode(', ',$table_array),__LINE__,__FILE__);
}





function drop_tables($table_array)
{
if(count($table_array)!=0)
$this->query_inject("DROP TABLE ".implode(', ',$table_array),__LINE__,__FILE__);
}

## Private Methods ##









function _error($query,$errstr,$errline='',$errfile='')
{
global $Errorh;


$too_many_connections=strpos($errstr,'already has more than \'max_user_connections\' active connections')>0;
$Errorh->handler($errstr.'<br /><br />'.$query.'<br /><br />'.mysql_error(),E_USER_ERROR,$errline,$errfile,false,!$too_many_connections);
redirect(PATH_TO_ROOT.'/member/toomanyconnections.php');
}







function clean_database_name($db_name)
{
return str_replace(array('/','\\','.',' ','"','\''),'_',$db_name);
}


## Private attributes ##



var $link;



var $req=0;



var $connected=false;



var $base_name='';
}
?>
