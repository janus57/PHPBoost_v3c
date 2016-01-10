<?php



























define('DO_NOT_ADD_SLASHES',false);
define('ADD_SLASHES',true);
define('PARSER_DO_NOT_STRIP_SLASHES',false);
define('PARSER_STRIP_SLASHES',true);
define('PICK_UP',true);
define('REIMPLANT',false);







class Parser
{
######## Public #######



function Parser()
{
$this->content='';
$this->page_path=$_SERVER['PHP_SELF'];
}







function get_content($addslashes=ADD_SLASHES)
{
if($addslashes)
{
return addslashes(trim($this->content));
}
else
{
return trim($this->content);
}
}







function set_content($content,$stripslashes=PARSER_DO_NOT_STRIP_SLASHES)
{
if($stripslashes)
{
$this->content=stripslashes($content);
}
else
{
$this->content=$content;
}
}





function set_path_to_root($path)
{
$this->path_to_root=$path;
}





function get_path_to_root()
{
return $this->path_to_root;
}





function set_page_path($page_path)
{
$this->page_path=$page_path;
}





function get_page_path()
{
return $this->page_path;
}

####### Protected #######



var $content='';




var $array_tags=array();







function _parse_imbricated($match,$regex,$replace)
{
$nbr_match=substr_count($this->content,$match);
for($i=0;$i<=$nbr_match;$i++)
$this->content=preg_replace($regex,$replace,$this->content);
}




var $path_to_root=PATH_TO_ROOT;




var $page_path='';
}

?>