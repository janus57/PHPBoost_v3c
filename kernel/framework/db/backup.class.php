<?php































class Backup
{



function Backup()
{
$this->list_db_tables();


@set_time_limit(600);
}




function list_db_tables()
{
global $Sql;

if(empty($this->tables))
{
$this->tables=$Sql->list_tables();
}
}







function generate_drop_table_query($table_list=array())
{
$selected_tables=array();
$all_tables=count($table_list)==0;
foreach($this->tables as $id=>$properties)
{
if(in_array($properties['name'],$table_list)|| $all_tables)
$selected_tables[]=$properties['name'];
}
$this->backup_script.='DROP TABLE IF EXISTS '.implode(', ',$selected_tables).';'."\n";
}







function generate_create_table_query($table_list=array())
{
global $Sql;

$all_tables=count($table_list)==0?true:false;

foreach($this->tables as $id=>$properties)
{
if(in_array($properties['name'],$table_list)|| $all_tables)
{
$result=$Sql->query_while('SHOW CREATE TABLE '.$properties['name'],__LINE__,__FILE__);
while($row=$Sql->fetch_row($result))
$this->backup_script.=$row[1].';'."\n\n";
$Sql->query_close($result);
}
}
}







function generate_insert_values_query($tables=array())
{
global $Sql;

$all_tables=count($tables)==0?true:false;

foreach($this->tables as $id=>$table_info)
{
if($all_tables || in_array($table_info['name'],$tables))
{
$rows_number=$Sql->query("SELECT COUNT(*) FROM ".$table_info['name'],__LINE__,__FILE__);
if($rows_number>0)
{
$this->backup_script.="INSERT INTO ".$table_info['name']." (`";
$this->backup_script.=implode('`, `',$Sql->list_fields($table_info['name']));
$this->backup_script.="`) VALUES ";

$i=1;
$list_fields=$Sql->list_fields($table_info['name']);
$result=$Sql->query_while('SELECT * FROM '.$table_info['name'],__LINE__,__FILE__);
while($row=$Sql->fetch_row($result))
{
if($i%10==0)
{
$this->backup_script.=";\n";
$this->backup_script.="INSERT INTO ".$table_info['name']." (";
$this->backup_script.=implode(', ',$list_fields);
$this->backup_script.=") VALUES ";
}
elseif($i>1)
$this->backup_script.=", ";
$this->backup_script.="(";
foreach($row as $key=>$value)
$row[$key]='\''.str_replace(chr(13),'\r',str_replace(chr(10),'\n',str_replace('\\','\\\\',str_replace("'","''",$value)))).'\'';
$this->backup_script.=implode(', ',$row).")";
$i++;
}
$this->backup_script.=";\n";
$Sql->query_close($result);
}
}
}
}





function concatenate_to_query($string)
{
$this->backup_script.=$string;
}





function get_script()
{
return $this->backup_script;
}



















function get_tables_properties_list()
{
$this->list_db_tables();
return $this->tables;
}





function get_tables_list()
{
$this->list_db_tables();
return array_keys($this->tables);
}





function get_tables_number()
{
$this->list_db_tables();
return count($this->tables);
}





function export_file($file_path)
{
import('io/filesystem/file');
$file=new File($file_path);
$file->open(WRITE);
$file->write($this->backup_script);
$file->close();
}






function extract_table_structure($tables=array())
{
$this->generate_create_table_query($tables);

$structure=array();
$structure['fields']=array();
$structure['index']=array();
$struct=substr(strstr($this->backup_script,'('),1);
$struct=substr($struct,0,strrpos($struct,')'));
$array_struct=explode(",\n",$struct);
foreach($array_struct as $field)
{
preg_match('!`([a-z_]+)`!i',$field,$match);
$name=isset($match[1])?$match[1]:'';
if(strpos($field,'KEY')!==false)
{
$type=trim(substr($field,0,strpos($field,'KEY')+3));
preg_match('!\(([a-z_`,]+)\)!i',$field,$match);
$index_fields=isset($match[1])?str_replace('`','',$match[1]):'';
$structure['index'][]=array('name'=>$name,'fields'=>$index_fields,'type'=>$type);
}
else
{
preg_match('!` ([a-z0-9()]+)!i',$field,$match);
$type=isset($match[1])?$match[1]:'';
$attribute=strpos($field,'unsigned')!==false?'unsigned':'';
$null=strpos($field,'NOT NULL')!==false?false:true;
preg_match('`default (.+)`i',$field,$match);
$default=isset($match[1])?str_replace("'",'',$match[1]):'';
$extra=strpos($field,'auto_increment')!==false?'auto_increment':'';
$structure['fields'][]=array('name'=>$name,'type'=>$type,'attribute'=>$attribute,'null'=>$null,'default'=>$default,'extra'=>$extra);
}
}

return $structure;
}

## Private Attributes ##



var $tables=array();



var $backup_script='';
}

?>