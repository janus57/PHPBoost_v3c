<?php



























define('DELETE_ON_ERROR',true);
define('NO_DELETE_ON_ERROR',false);
define('UNIQ_NAME',true);
define('CHECK_EXIST',true);
define('NO_UNIQ_NAME',false);






class Upload
{
## Public Attributes ##
var $error='';


## Public Methods ##




function Upload($base_directory='upload')
{
$this->base_directory=$base_directory;
}










function file($filepostname,$regexp='',$uniq_name=false,$weight_max=100000000,$check_exist=true)
{
global $LANG;

$file=$_FILES[$filepostname];
if(!empty($file)&&$file['size']>0)
{
if(($file['size']/1024)<=$weight_max)
{

$this->_generate_file_info($file['name'],$filepostname,$uniq_name);
if($this->_check_file($file['name'],$regexp))
{
if(!$check_exist ||!file_exists($this->base_directory.$this->filename[$filepostname]))
{
$this->error=$this->_error_manager($file['error']);
if(empty($this->error))
{
if(empty($this->filename[$filepostname])|| empty($file['tmp_name'])||!move_uploaded_file($file['tmp_name'],$this->base_directory.$this->filename[$filepostname]))
$this->error='e_upload_error';
else
return true;
}
}
else
$this->error='e_upload_already_exist';
}
else
$this->error='e_upload_invalid_format';
}
else
$this->error='e_upload_max_weight';
}
else
$this->error='e_upload_error';

return false;
}









function validate_img($filepath,$width_max,$height_max,$delete=true)
{
$error='';
list($width,$height,$ext)=function_exists('getimagesize')?@getimagesize($filepath):array(0,0,0);
if($width>$width_max || $height>$height_max)
$error='e_upload_max_dimension';

if(!empty($error)&&$delete)
@unlink($filepath);

return $error;
}


## Private Methods ##






function _check_file($filename,$regexp)
{
if(!empty($regexp))
{
if(preg_match($regexp,$filename)&&strpos($filename,'.php')===false)
return true;
return false;
}
return true;
}






function _clean_filename($string)
{
$string=strtolower($string);
$string=strtr($string,' éèêàâùüûïîôç','-eeeaauuuiioc');
$string=preg_replace('`([^a-z0-9]|[\s])`','_',$string);
$string=preg_replace('`[_]{2,}`','_',$string);
$string=trim($string,' _');

return $string;
}







function _generate_file_info($filename,$filepostname,$uniq_name)
{
$this->extension[$filepostname]=strtolower(substr(strrchr($filename,'.'),1));
if(strrpos($filename,'.')!==FALSE)
{
$filename=substr($filename,0,strrpos($filename,'.'));
}
$filename=str_replace('.','_',$filename);
$filename=$this->_clean_filename($filename);

if($uniq_name)
{
$filename_tmp=$filename;
if(!empty($this->extension[$filepostname]))
$filename_tmp.='.'.$this->extension[$filepostname];
$filename1=$filename;
while(file_exists($this->base_directory.$filename_tmp))
{
$filename1=$filename.'_'.substr(strhash(uniqid(mt_rand(),true)),0,5);
$filename_tmp=$filename1;
if(!empty($this->extension[$filepostname]))
$filename_tmp.='.'.$this->extension[$filepostname];
}
$filename=$filename1;
}

if(!empty($this->extension[$filepostname]))
$filename.='.'.$this->extension[$filepostname];
$this->filename[$filepostname]=$filename;
}






function _error_manager($error)
{
switch($error)
{

case 0:
$error='';
break;

case 1:
case 2:
$error='e_upload_max_weight';
break;

case 3:
$error='e_upload_error';
break;
default:
$error='e_upload_error';
}
return $error;
}


## Private Attributes ##
var $base_directory;
var $extension=array();
var $filename=array();
}

?>