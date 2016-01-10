<?php


























define('EMPTY_FOLDER',true);
define('ADMIN_NO_CHECK',true);






class Uploads
{
## Public Attributes ##
var $error='';


## Public Methods ##

function Add_folder($id_parent,$user_id,$name)
{
global $Sql;

$check_folder=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_UPLOAD_CAT." WHERE name = '".$name."' AND id_parent = '".$id_parent."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
if(!empty($check_folder)|| preg_match('`/|\.|\\\|"|<|>|\||\?`',stripslashes($name)))
return 0;

$Sql->query_inject("INSERT INTO ".DB_TABLE_UPLOAD_CAT." (id_parent, user_id, name) VALUES ('".$id_parent."', '".$user_id."', '".$name."')",__LINE__,__FILE__);

return $Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."upload_cat");
}


function Empty_folder_member($user_id)
{
global $Sql;


$result=$Sql->Query_while("SELECT path
		FROM ".DB_TABLE_UPLOAD." 
		WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
delete_file(PATH_TO_ROOT.'/upload/'.$row['path']);


$Sql->Query_inject("DELETE FROM ".DB_TABLE_UPLOAD_CAT." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$Sql->Query_inject("DELETE FROM ".DB_TABLE_UPLOAD." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
}


function Del_folder($id_folder)
{
global $Sql;


$result=$Sql->query_while("SELECT path
		FROM ".DB_TABLE_UPLOAD." 
		WHERE idcat = '".$id_folder."'",__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
delete_file(PATH_TO_ROOT.'/upload/'.$row['path']);


$Sql->query_inject("DELETE FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$id_folder."'",__LINE__,__FILE__);

$Sql->query_inject("DELETE FROM ".DB_TABLE_UPLOAD." WHERE idcat = '".$id_folder."'",__LINE__,__FILE__);
$result=$Sql->query_while("SELECT id 
		FROM ".DB_TABLE_UPLOAD_CAT." 
		WHERE id_parent = '".$id_folder."'",__LINE__,__FILE__);
while($row=$Sql->fetch_assoc($result))
{
if(!empty($row['id']))
$this->del_folder($row['id'],false);
}
}


function Del_file($id_file,$user_id,$admin=false)
{
global $Sql;

if($admin)
{
$name=$Sql->query("SELECT path FROM ".DB_TABLE_UPLOAD." WHERE id = '".$id_file."'",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_UPLOAD." WHERE id = '".$id_file."'",__LINE__,__FILE__);
delete_file(PATH_TO_ROOT.'/upload/'.$name);
return '';
}
else
{
$check_id_auth=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD." WHERE id = '".$id_file."'",__LINE__,__FILE__);

if($check_id_auth==$user_id)
{
$name=$Sql->query("SELECT path FROM ".DB_TABLE_UPLOAD." WHERE id = '".$id_file."'",__LINE__,__FILE__);
$Sql->query_inject("DELETE FROM ".DB_TABLE_UPLOAD." WHERE id = '".$id_file."'",__LINE__,__FILE__);
delete_file(PATH_TO_ROOT.'/upload/'.$name);
return '';
}
return 'e_auth';
}
}


function Rename_folder($id_folder,$name,$previous_name,$user_id,$admin=false)
{
global $Sql;


$info_folder=$Sql->query_array(PREFIX."upload_cat","id_parent","user_id","WHERE id = '".$id_folder."'",__LINE__,__FILE__);
$check_folder=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_UPLOAD_CAT." WHERE id_parent = '".$info_folder['id_parent']."' AND name = '".$name."' AND id <> '".$id_folder."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
if($check_folder>0 || preg_match('`/|\.|\\\|"|<|>|\||\?`',stripslashes($name)))
return '';

if($admin)
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD_CAT." SET name = '".$name."' WHERE id = '".$id_folder."'",__LINE__,__FILE__);
return stripslashes((strlen(html_entity_decode($name))>22)?htmlentities(substr(html_entity_decode($name),0,22)).'...':$name);
}
else
{
if($user_id==$info_folder['user_id'])
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD_CAT." SET name = '".$name."' WHERE id = '".$id_folder."'",__LINE__,__FILE__);
return stripslashes((strlen(html_entity_decode($name))>22)?htmlentities(substr(html_entity_decode($name),0,22)).'...':$name);
}
}
return stripslashes((strlen(html_entity_decode($previous_name))>22)?htmlentities(substr(html_entity_decode($previous_name),0,22)).'...':$previous_name);
}


function Rename_file($id_file,$name,$previous_name,$user_id,$admin=false)
{
global $Sql;


$info_cat=$Sql->query_array(PREFIX."upload","idcat","user_id","WHERE id = '".$id_file."'",__LINE__,__FILE__);
$check_file=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_UPLOAD." WHERE idcat = '".$info_cat['idcat']."' AND name = '".$name."' AND id <> '".$id_file."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
if($check_file>0 || preg_match('`/|\\\|"|<|>|\||\?`',stripslashes($name)))
return '/';

if($admin)
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD." SET name = '".$name."' WHERE id = '".$id_file."'",__LINE__,__FILE__);
return stripslashes((strlen(html_entity_decode($name))>22)?htmlentities(substr(html_entity_decode($name),0,22)).'...':$name);
}
else
{
if($user_id==$info_cat['user_id'])
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD." SET name = '".$name."' WHERE id = '".$id_file."'",__LINE__,__FILE__);
return stripslashes((strlen(html_entity_decode($name))>22)?htmlentities(substr(html_entity_decode($name),0,22)).'...':$name);
}
}
return stripslashes((strlen(html_entity_decode($previous_name))>22)?htmlentities(substr(html_entity_decode($previous_name),0,22)).'...':$previous_name);
}


function Move_folder($move,$to,$user_id,$admin=false)
{
global $Sql;

if($admin)
{

$change_user_id=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$to."'",__LINE__,__FILE__);
if(empty($change_user_id))
$change_user_id=-1;
if($to!=$move)
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD_CAT." SET id_parent = '".$to."', user_id = '".$change_user_id."' WHERE id = '".$move."'",__LINE__,__FILE__);
return '';
}
else
{
if($to==0)
{
$get_mbr_folder=$Sql->query("SELECT id FROM ".DB_TABLE_UPLOAD_CAT." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD_CAT." SET id_parent = '".$get_mbr_folder."' WHERE id = '".$move."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
return '';
}


$check_user_id_move=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$move."'",__LINE__,__FILE__);
$check_user_id_to=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$to."'",__LINE__,__FILE__);
if($user_id==$check_user_id_move&&$user_id==$check_user_id_to)
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD_CAT." SET id_parent = '".$to."' WHERE id = '".$move."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
return '';
}
else
return 'e_auth';
}
}


function Move_file($move,$to,$user_id,$admin=false)
{
global $Sql;

if($admin)
{

$change_user_id=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$to."'",__LINE__,__FILE__);
if(empty($change_user_id))
$change_user_id=-1;
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD." SET idcat = '".$to."', user_id = '".$change_user_id."' WHERE id = '".$move."'",__LINE__,__FILE__);
return '';
}
else
{
if($to==0)
{
$get_mbr_folder=$Sql->query("SELECT id FROM ".DB_TABLE_UPLOAD_CAT." WHERE user_id = '".$user_id."' AND id_parent = 0",__LINE__,__FILE__);
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD." SET idcat = '".$get_mbr_folder."' WHERE id = '".$move."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
return '';
}


$check_user_id_move=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD." WHERE id = '".$move."'",__LINE__,__FILE__);
$check_user_id_to=$Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT." WHERE id = '".$to."'",__LINE__,__FILE__);
if($user_id==$check_user_id_move&&$user_id==$check_user_id_to)
{
$Sql->query_inject("UPDATE ".DB_TABLE_UPLOAD." SET idcat = '".$to."' WHERE id = '".$move."' AND user_id = '".$user_id."'",__LINE__,__FILE__);
return '';
}
else
return 'e_auth';
}
}


function Find_subfolder($array_folders,$id_cat,&$array_child_folder)
{

foreach($array_folders as $key=>$value)
{
if($value==$id_cat)
{
$array_child_folder[]=$key;

$this->Find_subfolder($array_folders,$key,$array_child_folder);
}
}
}


function get_admin_url($id_folder,$pwd,$member_link='')
{
global $LANG,$Sql;

$parent_folder=$Sql->query_array(PREFIX."upload_cat","id_parent","name","user_id","WHERE id = '".$id_folder."'",__LINE__,__FILE__);
if(!empty($parent_folder['id_parent']))
{
$pwd.=$this->get_admin_url($parent_folder['id_parent'],$pwd,$member_link);
return $pwd.'/<a href="admin_files.php?f='.$id_folder.'">'.$parent_folder['name'].'</a>';
}
else
return($parent_folder['user_id']=='-1')?$pwd.'/<a href="admin_files.php?f='.$id_folder.'">'.$parent_folder['name'].'</a>':$pwd.'/'.$member_link.'<a href="admin_files.php?f='.$id_folder.'">'.$parent_folder['name'].'</a>';
}


function get_url($id_folder,$pwd,$popup)
{
global $LANG,$Sql;

$parent_folder=$Sql->query_array(PREFIX."upload_cat","id_parent","name","WHERE id = '".$id_folder."' AND user_id <> -1",__LINE__,__FILE__);
if(!empty($parent_folder['id_parent']))
{
$pwd.=$this->get_url($parent_folder['id_parent'],$pwd,$popup);
return $pwd.'/<a href="'.url('upload.php?f='.$id_folder.$popup).'">'.$parent_folder['name'].'</a>';
}
else
return $pwd.'/<a href="'.url('upload.php?f='.$id_folder.$popup).'">'.$parent_folder['name'].'</a>';
}


function Member_memory_used($user_id)
{
global $Sql;

return $Sql->query("SELECT SUM(size) FROM ".DB_TABLE_UPLOAD." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
}


function get_img_mimetype($type)
{
global $LANG;

$filetype=sprintf($LANG['file_type'],strtoupper($type));
switch($type)
{

case 'jpg':
case 'png':
case 'gif':
case 'bmp':
case 'svg':
$img=$type.'.png';
$filetype=sprintf($LANG['image_type'],strtoupper($type));
break;

case 'rar':
case 'gz':
case 'zip':
$img='zip.png';
$filetype=sprintf($LANG['zip_type'],strtoupper($type));
break;

case 'pdf':
$img='pdf.png';
$filetype=$LANG['adobe_pdf'];
break;

case 'wav':
case 'mp3':
$img='audio.png';
$filetype=sprintf($LANG['audio_type'],strtoupper($type));
break;

case 'html':
$img='html.png';
break;
case 'js':
case 'php':
$img='script.png';
break;

case 'wmv':
case 'avi':
$img='video.png';
break;

case 'exe':
$img='exec.png';
break;
default:
$img='text.png';
$filetype=sprintf($LANG['document_type'],strtoupper($type));
}

return array('img'=>$img,'filetype'=>$filetype);
}


## Private Attributes ##
var $base_directory;
var $extension=array();
var $filename=array();
}

?>