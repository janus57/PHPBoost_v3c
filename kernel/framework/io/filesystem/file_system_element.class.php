<?php


























define('OPEN_NOW',true);
define('OPEN_AFTER',false);








class FileSystemElement
{




function FileSystemElement($path)
{
$this->path=$path;
$this->is_open=false;
}





function exists()
{
return file_exists($this->path);
}




function open()
{
if($this->is_open)
{
return;
}

$this->is_open=true;
}




function get()
{
if(!$this->is_open)
{
$this->open();
}
}






function write()
{
$this->is_open=false;
$this->open();
}







function get_name($full_path=false,$no_extension=false)
{
if($full_path)
{
return $this->path;
}

$path=trim($this->path,'/');
$parts=explode('/',$path);
$name=$parts[count($parts)-1];

if($no_extension)
{
return substr($name,0,strrpos($name,'.'));
}

return $name;
}





function change_chmod($chmod)
{
if(!empty($this->path))
{
@chmod($this->path,$chmod);
}
}





function delete(){}

## Public Attributes ##



var $path;




var $is_open;

}

?>
