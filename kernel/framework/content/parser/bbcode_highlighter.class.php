<?php


























import('content/parser/parser');



define('BBCODE_TAG_COLOR','#0000FF');

define('BBCODE_PARAM_COLOR','#7B00FF');

define('BBCODE_PARAM_NAME_COLOR','#FF0000');

define('BBCODE_LIST_ITEM_COLOR','#00AF07');


define('BBCODE_HIGHLIGHTER_INLINE_CODE',true);
define('BBCODE_HIGHLIGHTER_BLOCK_CODE',false);








class BBCodeHighlighter extends Parser
{
######## Public #######



function BBCodeHighlighter()
{

parent::Parser();
}







function parse($inline_code=BBCODE_HIGHLIGHTER_BLOCK_CODE)
{

$this->content=htmlspecialchars($this->content);


$this->content=str_replace('[line]','<span style="color:'.BBCODE_TAG_COLOR.';">[line]</span>',$this->content);
$this->content=str_replace('[*]','<span style="color:'.BBCODE_LIST_ITEM_COLOR.';">[*]</span>',$this->content);


$simple_tags=array('b','i','u','s','sup','sub','pre','math','quote','block','fieldset','sound','url','img','mail','code','tr','html','row','indent','hide','mail');

foreach($simple_tags as $tag)
{
while(preg_match('`\['.$tag.'\](.*)\[/'.$tag.'\]`isU',$this->content))
{
$this->content=preg_replace('`\['.$tag.'\](.*)\[/'.$tag.'\]`isU','<span style="color:'.BBCODE_TAG_COLOR.';">/[/'.$tag.'/]/</span>$1<span style="color:'.BBCODE_TAG_COLOR.';">/[//'.$tag.'/]/</span>',$this->content);
}
}


$tags_with_simple_property=array('img','color','bgcolor','size','font','align','float','anchor','acronym','title','stitle','style','url','mail','code','quote','movie','swf','mail');

foreach($tags_with_simple_property as $tag)
{
while(preg_match('`\['.$tag.'=([^\]]+)\](.*)\[/'.$tag.'\]`isU',$this->content))
{
$this->content=preg_replace('`\['.$tag.'=([^\]]+)\](.*)\[/'.$tag.'\]`isU','<span style="color:'.BBCODE_TAG_COLOR.';">/[/'.$tag.'</span>=<span style="color:'.BBCODE_PARAM_COLOR.';">$1</span><span style="color:'.BBCODE_TAG_COLOR.';">/]/</span>$2<span style="color:'.BBCODE_TAG_COLOR.';">/[//'.$tag.'/]/</span>',$this->content);
}
}


$tags_with_many_parameters=array('table','col','head','list','fieldset','block','wikipedia');

foreach($tags_with_many_parameters as $tag)
{
while(preg_match('`\[('.$tag.')([^\]]*)\](.*)\[/'.$tag.'\]`isU',$this->content))
{
$this->content=preg_replace_callback('`\[('.$tag.')([^\]]*)\](.*)\[/'.$tag.'\]`isU',array(&$this,'_highlight_bbcode_tag_with_many_parameters'),$this->content);
}
}

if(!$inline_code)
{
$this->content='<pre>'.$this->content.'</pre>';
}
else
{
$this->content='<pre style="display:inline;">'.$this->content.'</pre>';
}


$this->content=str_replace(array('/[/','/]/'),array('[',']'),$this->content);
}

## Private ##





function _highlight_bbcode_tag_with_many_parameters($matches)
{
$content=$matches[3];
$tag_name=$matches[1];

$matches[2]=preg_replace('`([a-z]+)="([^"]*)"`isU','<span style="color:'.BBCODE_PARAM_NAME_COLOR.'">$1</span>=<span style="color:'.BBCODE_PARAM_COLOR.'">"$2"</span>',$matches[2]);

return '<span style="color:'.BBCODE_TAG_COLOR.'">/[/'.$tag_name.'</span>'.$matches[2].'<span style="color:'.BBCODE_TAG_COLOR.'">/]/</span>'.$content.'<span style="color:'.BBCODE_TAG_COLOR.'">/[//'.$tag_name.'/]/</span>';
}
}
?>