<?php


























import('io/filesystem/file_system_element');
import('io/filesystem/file');







class Folder extends FileSystemElement
{





function Folder($path,$whenopen=OPEN_AFTER)
{
parent::FileSystemElement(rtrim($path,'/'));

if(@file_exists($this->path))
{
if(!@is_dir($this->path))
{
return false;
}

if($whenopen==OPEN_NOW)
{
$this->open();
}
}
else if(!@mkdir($this->path))
{
return false;
}

return true;
}




function open()
{
parent::open();

$this->files=$this->folders=array();
if($dh=@opendir($this->path))
{
while(!is_bool($fse_name=readdir($dh)))
{
if($fse_name=='.' || $fse_name=='..')
{
continue;
}

if(is_file($this->path.'/'.$fse_name))
{
$this->files[]=new File($this->path.'/'.$fse_name);
}
else
{
$this->folders[]=new Folder($this->path.'/'.$fse_name);
}
}
closedir($dh);
}
}






function get_files($regex='')
{
parent::get();

$ret=array();
if(empty($regex))
{
foreach($this->files as $file)
{
$ret[]=$file;
}
}
else
{
foreach($this->files as $file)
{
if(preg_match($regex,$file->get_name()))
{
$ret[]=$file;
}
}
}
return $ret;
}






function get_folders($regex='')
{
parent::get();
if(empty($regex))
{
$ret=array();
foreach($this->folders as $folder)
{
$ret[]=$folder;
}
return $ret;
}
else
{
$ret=array();
foreach($this->folders as $folder)
{
if(preg_match($regex,$folder->get_name()))
{
$ret[]=$folder;
}
}
return $ret;
}
}





function get_first_folder()
{
parent::get();

if(isset($this->folders[0]))
{
return $this->folders[0];
}
else
{
return null;
}
}





function get_all_content()
{
return array_merge($this->get_files(),$this->get_folders());
}





function delete()
{
$this->open();

$fs=array_merge($this->files,$this->folders);

foreach($fs as $fse)
{
$fse->delete();
}

if(!@rmdir($this->path))
{
return false;
}
return true;
}

## Private Attributes ##



var $files=array();




var $folders=array();
}

?>