<?php


























import('io/filesystem/file_system_element');

define('ERASE',false);
define('ADD',true);

define('READ',0x1);
define('WRITE',0x2);
define('READ_WRITE',0x3);
define('LOCK',0x4);

define('CLOSEFILE',0x1);
define('NOTCLOSEFILE',0x2);







class File extends FileSystemElement
{






function File($path,$mode=READ_WRITE,$whenopen=OPEN_AFTER)
{
parent::FileSystemElement($path);

$this->mode=$mode;

if(@file_exists($this->path))
{
if(!@is_file($this->path))
{
return false;
}

if($whenopen==OPEN_NOW)
{
$this->open();
}
}
else if(!@touch($this->path))
{
return false;
}

return true;
}




function open()
{
if(!$this->is_open())
{
parent::open();
if(file_exists($this->path)&&is_file($this->path))
{
$this->fd=fopen($this->path,'r+');
}
else if(!file_exists($this->path))
{
$this->fd=fopen($this->path,'x+');
}

if($this->mode&READ)
{
$this->contents=file_get_contents_emulate($this->path);
$this->lines=explode("\n",$this->contents);
}
}
}







function get_contents($start=0,$len=-1)
{
if($this->mode&READ)
{
parent::get();

if(!$start&&$len==-1)
{
return $this->contents;
}
else if($len==-1)
{
return substr($this->contents,$start);
}
else
{
return substr($this->contents,$start,$len);
}
}
else
{
user_error('File '.$this->path.' is not open for read');
}
}







function get_lines($start=0,$n=-1)
{
if($this->mode&READ)
{
parent::get();

if(!$start&&$n==-1)
{
return $this->lines;
}
else if($n==-1)
{
return array_slice($this->lines,$start);
}
else
{
return array_slice($this->lines,$start,$n);
}
}
else
{
user_error('File '.$this->path.' is open in the write only mode, it can\'t be read');
}
}








function write($data,$how=ERASE,$mode=CLOSEFILE)
{
if($this->mode&WRITE)
{
if(($mode==NOTCLOSEFILE&&!is_ressource($this->fd))|| $mode==CLOSEFILE)
{
if(!($this->fd=@fopen($this->path,($how==ADD)?'a':'w')))
{
return false;
}
}

$bytes_to_write=strlen($data);
$bytes_written=0;
while($bytes_written<$bytes_to_write)
{

$bytes=fwrite($this->fd,substr($data,$bytes_written,4096));

if($bytes===false || $bytes==0)
{
break;
}

$bytes_written+=$bytes;
}

parent::write();

return $bytes_written==$bytes_to_write;
}
else
{
user_error('File '.$this->path.' is open in the read only mode, it can\'t be written.');
}
}




function close()
{
$this->contents='';
$this->lines=array();

if(is_resource($this->fd))
{
fclose($this->fd);
}
}




function delete()
{
$this->close();

if(!@unlink($this->path))
{
$this->write('');
}


}





function is_open()
{
return $this->is_open&&is_resource($this->fd);
}





function lock($blocking=true)
{
if(!$this->is_open())
{
$this->open();
}

return @flock($this->fd,LOCK_EX,$blocking);
}




function unlock()
{
if(!$this->is_open())
{
$this->open();
}

return @flock($this->fd,LOCK_UN);
}




function flush()
{
if($this->is_open())
{
fflush($this->fd);
}
}







function finclude($once=true)
{
if($once)
{
return include_once $this->path;
}
else
{
return include $this->path;
}
}






function frequire($once=true)
{
if($once)
return require_once $this->path;
return require $this->path;
}





function get_last_modification_date()
{
return filemtime($this->path);
}





function get_last_access_date()
{
return filectime($this->path);
}

## Private Attributes ##



var $lines=array();




var $contents;




var $mode;




var $fd;
}

?>