<?php


























import('content/parser/content_parser');











class TinyMCEParser extends ContentParser
{



function TinyMCEParser()
{
parent::ContentParser();
}





function parse()
{
global $User;


if(!in_array('code',$this->forbidden_tags))
{
$this->_pick_up_tag('code','=[A-Za-z0-9#+-]+(?:,[01]){0,2}');
}


if(!in_array('html',$this->forbidden_tags)&&$User->check_auth($this->html_auth,1))
{
$this->_pick_up_tag('html');
}


$this->_prepare_content();


$this->_parse_tinymce_formatting();


if(!in_array('table',$this->forbidden_tags))
{
$this->_parse_tables();
}


$this->_parse_smilies();


$this->_parse_bbcode_tags();

$this->_correct();


if(!empty($this->array_tags['html']))
{
$this->array_tags['html']=array_map(create_function('$string','return str_replace("[html]", "<!-- START HTML -->\n", str_replace("[/html]", "\n<!-- END HTML -->", $string));'),$this->array_tags['html']);


$this->array_tags['html']=array_map(array('TinyMCEParser','_clear_html_and_code_tag'),$this->array_tags['html']);

$this->_reimplant_tag('html');
}

parent::parse();


if(!empty($this->array_tags['code']))
{
$this->array_tags['code']=array_map(create_function('$string','return preg_replace(\'`^\[code(=.+)?\](.+)\[/code\]$`isU\', \'[[CODE$1]]$2[[/CODE]]\', htmlspecialchars($string, ENT_NOQUOTES));'),$this->array_tags['code']);


$this->array_tags['code']=array_map(array('TinyMCEParser','_clear_html_and_code_tag'),$this->array_tags['code']);

$this->_reimplant_tag('code');
}
}

## Protected ##




function _prepare_content()
{

$this->content=html_entity_decode($this->content);


$this->content=htmlspecialchars($this->content,ENT_NOQUOTES);


$this->content=preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i',"&$1;",$this->content);
}





function _parse_table_tag($matches)
{
$table_properties=$matches[1];
$style_properties='';

$temp_array=array();


if(preg_match('`border="([0-9]+)"`iU',$table_properties,$temp_array))
{
$style_properties.='border:'.$temp_array[1].'px;';
}


if(preg_match('`width="([0-9]+)"`iU',$table_properties,$temp_array))
{
$style_properties.='width:'.$temp_array[1].'px;';
}


if(preg_match('`height="([0-9]+)"`iU',$table_properties,$temp_array))
{
$style_properties.='height:'.$temp_array[1].'px;';
}


if(preg_match('`align="([^"]+)"`iU',$table_properties,$temp_array))
{
if($temp_array[1]=='center')
{
$style_properties.='margin:auto;';
}
elseif($temp_array[1]=='right')
{
$style_properties.='margin-left:auto;';
}
}


if(preg_match('`style="([^"]+)"`iU',$table_properties,$temp_array))
{
$style_properties.=$temp_array[1];
}

return '<table class="bb_table"'.(!empty($style_properties)?' style="'.$style_properties.'"':'').'>'.$matches[2].'</table>';
}






function _parse_row_tag($matches)
{
$col_properties=$matches[1];
$col_new_properties='';
$col_style='';

$temp_array=array();

if(preg_match('`align="([^"]+)"`iU',$col_properties,$temp_array))
{
$col_style.='text-align:'.$temp_array[1].';';
}


if(preg_match('`style="([^"]+)"`iU',$col_properties,$temp_array))
{
$col_style.=' style="'.$temp_array[1].' '.$col_style.'"';
}
elseif(!empty($col_style))
{
$col_style=' style="'.$col_style.'"';
}

return '<tr class="bb_table_row"'.$col_new_properties.$col_style.'>'.$matches[2].'</tr>';
}






function _parse_col_tag($matches)
{
$tag=$matches[1]=='th'?'th':'td';
$bbcode_tag=$tag=='th'?'head':'col';
$col_properties=$matches[2];
$col_new_properties='';
$col_style='';

$temp_array=array();


if(preg_match('`colspan="([0-9]+)"`iU',$col_properties,$temp_array))
{
$col_new_properties.=' colspan="'.$temp_array[1].'"';
}


if(preg_match('`rowspan="([0-9]+)"`iU',$col_properties,$temp_array))
{
$col_new_properties.=' rowspan="'.$temp_array[1].'"';
}


if(preg_match('`align="([^"]+)"`iU',$col_properties,$temp_array))
{
$col_style.='text-align:'.$temp_array[1].';';
}


if(preg_match('`style="([^"]+)"`iU',$col_properties,$temp_array))
{
$col_style.=' style="'.$temp_array[1].' '.$col_style.'"';
}
elseif(!empty($col_style))
{
$col_style=' style="'.$col_style.'"';
}

return '<'.$tag.' class="bb_table_'.$bbcode_tag.'"'.$col_new_properties.$col_style.'>'.$matches[3].'</'.$tag.'>';
}




function _parse_tinymce_formatting()
{
global $LANG;


$this->content=str_replace(
array(
'&amp;nbsp;&amp;nbsp;&amp;nbsp;',
'&amp;gt;',
'&amp;lt;',
'&lt;br /&gt;',
'&lt;br&gt;',
'&amp;nbsp;'
),array(
"\t",
'&gt;',
'&lt;',
"<br />\n",
"<br />\n",
' '
),$this->content);

$array_preg=array(
'`&lt;p&gt;\s*&nbsp;\s*&lt;/p&gt;\s*`',
'`&lt;div&gt;(.+)&lt;/div&gt;`isU',
'`&lt;p&gt;(.+)&lt;/p&gt;`isU',
'`&lt;h5&gt;(.+)&lt;/h5&gt;`isU',
'`&lt;h6&gt;(.+)&lt;/h6&gt;`isU',
'`&lt;/p&gt;[\s]*`i'
);
$array_preg_replace=array(
'',
'$1'."\n<br />",
'$1'."\n<br />",
'<span style="font-size: 10px;">$1</span><br />',
'<span style="font-size: 8px;">$1</span><br />',
'&lt;/p&gt;'
);


$this->content=preg_replace($array_preg,$array_preg_replace,$this->content);


$this->content=str_replace('\r\n','\n',$this->content);
$this->content=preg_replace('`\s*\n+\s*`isU',"\n",$this->content);

$array_preg=array();
$array_preg_replace=array();


if(!in_array('b',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;strong&gt;(.+)&lt;/strong&gt;`isU');
array_push($array_preg_replace,'<strong>$1</strong>');
}

if(!in_array('i',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;em&gt;(.+)&lt;/em&gt;`isU');
array_push($array_preg_replace,'<em>$1</em>');
}

if(!in_array('u',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;span style="text-decoration: underline;"&gt;(.+)&lt;/span&gt;`isU');
array_push($array_preg_replace,'<span style="text-decoration: underline;">$1</span>');
}

if(!in_array('s',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;span style="text-decoration: line-through;"&gt;(.+)&lt;/span&gt;`isU');
array_push($array_preg_replace,'<strike>$1</strike>');
}

if(!in_array('url',$this->forbidden_tags))
{
import('util/url');
array_push($array_preg,'`&lt;a href="('.Url::get_wellformness_regex().')"&gt;(.+)&lt;/a&gt;`isU');
array_push($array_preg_replace,'<a href="$1">$2</a>');


}

if(!in_array('sub',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;sub&gt;(.+)&lt;/sub&gt;`isU');
array_push($array_preg_replace,'<sub>$1</sub>');
}

if(!in_array('sup',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;sup&gt;(.+)&lt;/sup&gt;`isU');
array_push($array_preg_replace,'<sup>$1</sup>');
}

if(!in_array('pre',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;pre&gt;(.+)(<br />[\s]*)*&lt;/pre&gt;`isU');
array_push($array_preg_replace,'<pre>$1</pre>');
}

if(!in_array('color',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;span style="color: *([#a-z0-9]+);"&gt;(.+)&lt;/span&gt;`isU');
array_push($array_preg_replace,'<span style="color:$1;">$2</span>');
}

if(!in_array('bgcolor',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;span style="background-color: *([#a-z0-9]+);"&gt;(.+)&lt;/span&gt;`isU');
array_push($array_preg_replace,'<span style="background-color:$1;">$2</span>');
}

if(!in_array('align',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;p style="text-align: (left|right|center|justify);"&gt;(.+)&lt;/p&gt;`isU');
array_push($array_preg_replace,'<p style="text-align:$1">$2</p>'."\n");
}

if(!in_array('anchor',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;a(?: class="[^"]+")?(?: title="[^"]+" )? name="([^"]+)"&gt;(.*)&lt;/a&gt;`isU');
array_push($array_preg_replace,'<span id="$1">$2</span>');
}

if(!in_array('title',$this->forbidden_tags))
{

array_push($array_preg,'`&lt;h1[^&]*&gt;(.+)&lt;/h1&gt;`isU');
array_push($array_preg_replace,"\n".'<h3 class="title1">$1</h3>'."\n<br />");

array_push($array_preg,'`&lt;h2[^&]*&gt;(.+)&lt;/h2&gt;`isU');
array_push($array_preg_replace,"\n".'<h3 class="title2">$1</h3>'."\n<br />");

array_push($array_preg,'`&lt;h3[^&]*&gt;(.+)(<br />[\s]*)?&lt;/h3&gt;`isU');
array_push($array_preg_replace,"\n".'<br /><h4 class="stitle1">$1</h4><br />'."\n<br />");

array_push($array_preg,'`&lt;h4[^&]*&gt;(.+)(<br />[\s]*)?&lt;/h4&gt;`isU');
array_push($array_preg_replace,"\n".'<br /><h4 class="stitle2">$1</h4><br />'."\n<br />");
}

if(!in_array('swf',$this->forbidden_tags))
{
array_push($array_preg,'`&lt;object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="([^"]+)%?" height="([^"]+)%?"&gt;&lt;param name="movie" value="([^"]+)"(.*)&lt;/object&gt;`isU');
array_push($array_preg_replace,'[[MEDIA]]insertSwfPlayer(\'$3\', $1, $2);[[/MEDIA]]');
}


$this->content=preg_replace($array_preg,$array_preg_replace,$this->content);


if(!in_array('list',$this->forbidden_tags))
{
while(preg_match('`&lt;o|ul&gt;(.+)&lt;/o|ul&gt;`isU',$this->content))
{
$this->content=preg_replace('`&lt;ul&gt;(.+)&lt;/ul&gt;`isU','<ul class="bb_ul">'."\n".'$1</ul>',$this->content);
$this->content=preg_replace('`&lt;ol&gt;(.+)&lt;/ol&gt;`isU','<ol class="bb_ol">'."\n".'$1</ol>',$this->content);
$this->content=preg_replace('`&lt;li&gt;(.*)&lt;/li&gt;`isU','<li class="bb_li">$1</li>'."\n",$this->content);
}
}


$array_str=array(
'&lt;address&gt;','&lt;/address&gt;','&lt;caption&gt;','&lt;/caption&gt;','&lt;tbody&gt;','&lt;/tbody&gt;','&lt;thead&gt;','&lt;/thead&gt;'
);

$this->content=str_replace($array_str,'',$this->content);



if(!in_array('size',$this->forbidden_tags))
{


$nbr_size_parsing=0;
while(preg_match('`&lt;span style="font-size: ([a-z-]+);"&gt;(.+)&lt;/span&gt;`isU',$this->content)&&$nbr_size_parsing++<10)
{
$this->content=preg_replace_callback('`&lt;span style="font-size: ([a-z-]+);"&gt;(.+)&lt;/span&gt;`isU',array(&$this,'_parse_size_tag'),$this->content);
}
}


if(!in_array('image',$this->forbidden_tags))
{
$this->content=preg_replace_callback('`&lt;img(?: style="[^"]+")? src="([^"]+)"(?: border="[^"]*")? alt="[^"]*"(?: hspace="[^"]*")?(?: vspace="[^"]*")?(?: width="[^"]*")?(?: height="[^"]*")?(?: align="(top|middle|bottom)")? /&gt;`is',create_function('$img','$align = \'\'; if (!empty($img[2])) $align = \'=\' . $img[2]; return \'<img src="\' . $img[1] . \'" alt="" class="valign_"\' . $align . \' />\';'),$this->content);
}


if(!in_array('indent',$this->forbidden_tags))
{
$this->content=preg_replace_callback('`&lt;p style="padding-left: ([0-9]+)px;"&gt;(.+)&lt;/p&gt;`isU',array(&$this,'_parse_indent_tag'),$this->content);
}


if(!in_array('line',$this->forbidden_tags))
{
$this->content=str_replace('&lt;hr /&gt;','<hr class="bb_hr" />',$this->content);
}


if(!in_array('quote',$this->forbidden_tags))
{
$this->content=preg_replace('`(.)(?:\s*<br />\s*)?\s*&lt;blockquote&gt;\s*(?:&lt;p&gt;)?(.+)(?:<br />[\s]*)*\s*(&lt;/p&gt;)?&lt;/blockquote&gt;`isU','$1<span class="text_blockquote">'.$LANG['quotation'].':</span><div class="blockquote">$2</div>',$this->content);
}


if(!in_array('font',$this->forbidden_tags))
{



$nbr_font_parsing=0;
while(preg_match('`&lt;span style="font-family: ([a-z, 0-9-]+);"(?: mce_style="font-family: [^"]+")?&gt;(.*)&lt;/span&gt;`isU',$this->content)&&$nbr_font_parsing++<10)
{
$this->content=preg_replace_callback('`&lt;span style="font-family: ([a-z, 0-9-]+);"(?: mce_style="font-family: [^"]+")?&gt;(.*)&lt;/span&gt;`isU',array(&$this,'_parse_font_tag'),$this->content);
}
}
}




function _parse_tables()
{
$content_contains_table=false;
while(preg_match('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`is',$this->content))
{
$this->content=preg_replace_callback('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`isU',array(&$this,'_parse_table_tag'),$this->content);
$content_contains_table=true;
}

if($content_contains_table)
{

while(preg_match('`&lt;tr([^&]*)&gt;(.+)&lt;/tr&gt;`is',$this->content))
{
$this->content=preg_replace_callback('`&lt;tr([^&]*)&gt;(.+)&lt;/tr&gt;`isU',array(&$this,'_parse_row_tag'),$this->content);
}


while(preg_match('`&lt;td|h([^&]*)&gt;(.+)&lt;/td|h&gt;`is',$this->content))
{
$this->content=preg_replace_callback('`&lt;(td)([^&]*)&gt;(.+)&lt;/td&gt;`isU',array(&$this,'_parse_col_tag'),$this->content);
$this->content=preg_replace_callback('`&lt;(th)([^&]*)&gt;(.+)&lt;/th&gt;`isU',array(&$this,'_parse_col_tag'),$this->content);
}
}
}





function _parse_smilies()
{
$this->content=preg_replace('`&lt;img class="smiley" (?:style="vertical-align:middle" )?src="[\./]*/images/smileys/([^"]+)" alt="([^"]+)" [^/]*/&gt;`i',
'<img src="/images/smileys/$1" alt="$2" class="smiley" />',$this->content);


@include(PATH_TO_ROOT.'/cache/smileys.php');
if(!empty($_array_smiley_code))
{

foreach($_array_smiley_code as $code=>$img)
{
$smiley_code[]='`(?:(?![a-z0-9]))(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")('.str_replace('\'','\\\\\\\'',preg_quote($code)).')(?:(?![a-z0-9]))`';
$smiley_img_url[]='<img src="/images/smileys/'.$img.'" alt="'.addslashes($code).'" class="smiley" />';
}
$this->content=preg_replace($smiley_code,$smiley_img_url,$this->content);
}
}




function _parse_bbcode_tags()
{
global $LANG;
import('util/url');
$array_preg=array(
'pre'=>'`\[pre\](.+)\[/pre\]`isU',
'float'=>'`\[float=(left|right)\](.+)\[/float\]`isU',
'acronym'=>'`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
'style'=>'`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
'swf'=>'`\[swf=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/swf\]`iU',
'movie'=>'`\[movie=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/movie\]`iU',
'sound'=>'`\[sound\]([a-z0-9_+.:?/=#%@&;,-]*)\[/sound\]`iU',
'math'=>'`\[math\](.+)\[/math\]`iU',
'url'=>'`(\s+)('.Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED).')(\s|<+)`isU',
'url2'=>'`(\s+)(www\.'.Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED).')(\s|<+)`isU',
'mail'=>'`(\s+)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(\s+)`i',
);

$array_preg_replace=array(
'pre'=>"<pre>$1</pre>",
'float'=>"<p class=\"float_$1\">$2</p>",
'acronym'=>"<acronym title=\"$1\" class=\"bb_acronym\">$2</acronym>",
'style'=>"<span class=\"$1\">$2</span>",
'swf'=>"[[MEDIA]]insertSwfPlayer('$3', $1, $2);[[/MEDIA]]",
'movie'=>"[[MEDIA]]insertMoviePlayer('$3', $1, $2);[[/MEDIA]]",
'sound'=>"[[MEDIA]]insertSoundPlayer('$1');[[/MEDIA]]",
'math'=>'[[MATH]]$1[[/MATH]]',
'url'=>"$1<a href=\"$2\">$2</a>$3",
'url2'=>"$1<a href=\"http://$2\">$2</a>$3",
'mail'=>"$1<a href=\"mailto:$2\">$2</a>$3",
);


if(!empty($this->forbidden_tags))
{

if(in_array('url',$this->forbidden_tags))
{
$this->forbidden_tags[]='url2';
}

$other_tags=array('table','quote','hide','indent','list');
foreach($this->forbidden_tags as $key=>$tag)
{

if(in_array($tag,$other_tags))
{
$array_preg[$tag]='`\['.$tag.'.*\](.+)\[/'.$tag.'\]`isU';
$array_preg_replace[$tag]="$1";
}
else
{
unset($array_preg[$tag]);
unset($array_preg_replace[$tag]);
}
}
}


$this->content=preg_replace($array_preg,$array_preg_replace,$this->content);

##Nested tags

if(!in_array('hide',$this->forbidden_tags))
{
$this->_parse_imbricated('[hide]','`\[hide\](.+)\[/hide\]`sU','<span class="text_hide">'.$LANG['hide'].':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>',$this->content);
}


if(!in_array('block',$this->forbidden_tags))
{
$this->_parse_imbricated('[block]','`\[block\](.+)\[/block\]`sU','<div class="bb_block">$1</div>',$this->content);
$this->_parse_imbricated('[block style=','`\[block style="([^"]+)"\](.+)\[/block\]`sU','<div class="bb_block" style="$1">$2</div>',$this->content);
}


if(!in_array('fieldset',$this->forbidden_tags))
{
$this->_parse_imbricated('[fieldset','`\[fieldset(?: legend="(.*)")?(?: style="([^"]*)")?\](.+)\[/fieldset\]`sU','<fieldset class="bb_fieldset" style="$2"><legend>$1</legend>$3</fieldset>',$this->content);
}


if(!in_array('wikipedia',$this->forbidden_tags))
{
$this->content=preg_replace_callback('`\[wikipedia(?: page="([^"]+)")?(?: lang="([a-z]+)")?\](.+)\[/wikipedia\]`isU',array(&$this,'_parse_wikipedia_links'),$this->content);
}


if(!in_array('hide',$this->forbidden_tags))
{
$this->_parse_imbricated('[hide]','`\[hide\](.+)\[/hide\]`sU','<span class="text_hide">'.$LANG['hide'].':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>',$this->content);
}


if(!in_array('quote',$this->forbidden_tags))
{
$this->_parse_imbricated('[quote]','`\[quote\](.+)\[/quote\]`sU','<span class="text_blockquote">'.$LANG['quotation'].':</span><div class="blockquote">$1</div>',$this->content);
$this->_parse_imbricated('[quote=','`\[quote=([^\]]+)\](.+)\[/quote\]`sU','<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>',$this->content);
}
}






function _parse_wikipedia_links($matches)
{
global $LANG;


$lang=$LANG['wikipedia_subdomain'];
if(!empty($matches[2]))
{
$lang=$matches[2];
}

$page_url=!empty($matches[1])?$matches[1]:$matches[3];

return '<a href="http://'.$lang.'.wikipedia.org/wiki/'.$page_url.'" class="wikipedia_link">'.$matches[3].'</a>';
}










function _parse_indent_tag($matches)
{
if((int)$matches[1]>0)
{
$nbr_indent=(int)$matches[1]/30;
return str_repeat('<div class="indent">',$nbr_indent).$matches[2].str_repeat('</div>',$nbr_indent)."\n<br />";
}
else
{
return $matches[2];
}
}









function _parse_size_tag($matches)
{
$size=0;

switch($matches[1])
{
case 'xx-small':
$size=8;
break;
case 'x-small':
$size=10;
break;
case 'small':
$size=12;
break;
case 'medium':
$size=14;
break;
case 'large':
$size=18;
break;
case 'x-large':
$size=24;
break;
case 'xx-large':
$size=36;
break;
default:
$size=0;
}

if($size>0)
{
return '<span style="font-size: '.$size.'px;">'.$matches[2].'</span>';
}
else
{
return $matches[2];
}
}








function _parse_font_tag($matches)
{
static $fonts_array=array(
'trebuchet ms,geneva'=>'geneva',
'comic sans ms,sans-serif'=>'optima',
'andale mono,times'=>'times',
'arial,helvetica,sans-serif'=>'arial',
'arial black,avant garde'=>'arial',
'book antiqua,palatino'=>'optima',
'courier new,courier'=>'courier new',
'georgia,palatino'=>'optima',
'helvetica'=>'arial',
'impact,chicago'=>'arial',
'symbol'=>'times',
'tahoma,arial,helvetica,sans-serif'=>'arial',
'terminal,monaco'=>'courier new',
'times new roman,times'=>'times',
'verdana,geneva'=>'arial',
'webdings'=>'times',
'wingdings,zapf dingbats'=>'times'
);

if(!empty($fonts_array[$matches[1]]))
{
return '<span style="font-family: '.$fonts_array[$matches[1]].';">'.$matches[2].'</span>';
}
else
{
return $matches[2];
}
}







function _clear_html_and_code_tag($var)
{
$var=preg_replace('`</p>\s*<p>`i',"\n",$var);
$var=str_replace('<br />',"\n",$var);
$var=html_entity_decode($var);
return $var;
}




function _correct()
{

$this->content=preg_replace(
array(
'`^(\s|(?:<br />))*`i',
'`(\s|(?:<br />))*$`i',
'`<br />\s*(<h3[^>]*>.*</h3>)`iUs',
'`(<h3[^>]*>.*</h3>)\s*<br />`iUs',
'`(<h3[^>]*>.*)\s*<br />\s*(</h3>)`iUs',

"`(\n<br />)[\s]*`"
),
array(
'',
'',
'$1',
"$1\n",
"$1$2",
'$1'
),
$this->content
);

$this->content=str_replace(
array("\n","\r",'<br />'),
array(' ',' ',"\n<br />"),
$this->content
);


$this->content=preg_replace(
array(
'`&lt;(?:p|span|div)[^&]*&gt;`is',
'`&lt;/(?:p|span|div)*&gt;`is'
),
array(
'',
''
),
$this->content
);
}
}

?>