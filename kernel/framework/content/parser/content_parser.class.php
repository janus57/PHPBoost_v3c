<?php


























import('content/parser/parser');







class ContentParser extends Parser
{
######## Public #######



function ContentParser()
{
global $CONFIG;

parent::Parser();
$this->html_auth=&$CONFIG['html_auth'];
$this->forbidden_tags=&$CONFIG['forbidden_tags'];
}





function parse()
{
import('util/url');
$this->content=Url::html_convert_absolute2root_relative($this->content,$this->path_to_root,$this->page_path);
}





function set_forbidden_tags($forbidden_tags)
{
if(is_array($forbidden_tags))
{
$this->forbidden_tags=$forbidden_tags;
}
}





function get_forbidden_tags()
{
return $this->forbidden_tags;
}






function set_html_auth($array_auth)
{
global $CONFIG;
if(is_array($array_auth))
{
$this->auth=$array_auth;
}
else
{
$this->auth=&$CONFIG['html_auth'];
}
}

## Private ##












function _split_imbricated_tag(&$content,$tag,$attributes)
{
$content=ContentParser::_preg_split_safe_recurse($content,$tag,$attributes);

$nbr_occur=count($content);
for($i=0;$i<$nbr_occur;$i++)
{

if(($i%3)===2&&preg_match('`\['.$tag.'(?:'.$attributes.')?\].+\[/'.$tag.'\]`s',$content[$i]))
{
ContentParser::_split_imbricated_tag($content[$i],$tag,$attributes);
}
}
}















function _preg_split_safe_recurse($content,$tag,$attributes)
{

$_index_tags=$this->_index_tags($content,$tag,$attributes);
$size=count($_index_tags);
$parsed=array();


if($size>=1)
{
array_push($parsed,substr($content,0,$_index_tags[0]));
}
else
{
array_push($parsed,$content);
}

for($i=0;$i<$size;$i++)
{
$current_index=$_index_tags[$i];

if($i==($size-1))
{
$sub_str=substr($content,$current_index);
}
else
{
$sub_str=substr($content,$current_index,$_index_tags[$i+1]-$current_index);
}


$mask='`\['.$tag.'('.$attributes.')?\](.*)\[/'.$tag.'\](.+)?`s';
$local_parsed=preg_split($mask,$sub_str,-1,PREG_SPLIT_DELIM_CAPTURE);

if(count($local_parsed)==1)
{

$parsed[count($parsed)-1].=$local_parsed[0];
}
else
{

array_push($parsed,$local_parsed[1]);
array_push($parsed,$local_parsed[2]);
}


if($i<($size-1))
{

$current_tag_len=strlen('['.$tag.$local_parsed[1].']'.$local_parsed[2].'[/'.$tag.']');
$end_pos=$_index_tags[$i+1]-($current_index+$current_tag_len);
array_push($parsed,substr($local_parsed[3],0,$end_pos));
}
elseif(isset($local_parsed[3]))
{
array_push($parsed,$local_parsed[3]);
}
}
return $parsed;
}








function _index_tags($content,$tag,$attributes)
{
$pos=-1;
$nb_open_tags=0;
$tag_pos=array();

while(($pos=strpos($content,'['.$tag,$pos+1))!==false)
{

$nb_close_tags=substr_count(substr($content,0,($pos+strlen('['.$tag))),'[/'.$tag.']');


if($nb_open_tags==$nb_close_tags)
{
$open_tag=substr($content,$pos,(strpos($content,']',$pos+1)+1-$pos));
$match=preg_match('`\['.$tag.'('.$attributes.')?\]`',$open_tag);
if($match==1)
{
$tag_pos[count($tag_pos)]=$pos;
}
}
$nb_open_tags++;
}
return $tag_pos;
}









function _pick_up_tag($tag,$arguments='')
{

$split_code=$this->_preg_split_safe_recurse($this->content,$tag,$arguments);

$num_codes=count($split_code);

if($num_codes>1)
{
$this->content='';
$id_code=0;

for($i=0;$i<$num_codes;$i++)
{

if($i%3==0)
{
$this->content.=$split_code[$i];

if($i<$num_codes-1)
{
$this->content.='['.strtoupper($tag).'_TAG_'.$id_code++.']';
}
}

elseif($i%3==2)
{

$this->array_tags[$tag][]='['.$tag.$split_code[$i-1].']'.$split_code[$i].'[/'.$tag.']';
}
}
}
}






function _reimplant_tag($tag)
{

if(!array_key_exists($tag,$this->array_tags))
return false;

$num_code=count($this->array_tags[$tag]);


for($i=0;$i<$num_code;$i++)
{
$this->content=str_replace('['.strtoupper($tag).'_TAG_'.$i.']',$this->array_tags[$tag][$i],$this->content);
}


$this->array_tags[$tag]=array();

return true;
}






var $tag=array('b','i','u','s','title','stitle','style','url',
'img','quote','hide','list','color','bgcolor','font','size','align','float','sup',
'sub','indent','pre','table','swf','movie','sound','code','math','anchor','acronym');



var $html_auth=array();



var $forbidden_tags=array();
}
?>