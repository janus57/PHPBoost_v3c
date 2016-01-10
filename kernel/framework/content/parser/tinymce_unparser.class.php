<?php


























import('content/parser/content_unparser');












class TinyMCEUnparser extends ContentUnparser
{



function TinyMCEUnparser()
{
parent::ContentUnparser();
}





function parse()
{

import('util/url');
$this->content=Url::html_convert_root_relative2relative($this->content,$this->path_to_root);


$this->_unparse_html(PICK_UP);
$this->_unparse_code(PICK_UP);


$this->_unparse_smilies();


$array_str=array(
"\t",'[b]','[/b]','[i]','[/i]','[s]','[/s]','€','‚','ƒ',
'„','…','†','‡','ˆ','‰','Š','‹','Œ','Ž',
'‘','’','“','”','•','–','—','˜','™','š',
'›','œ','ž','Ÿ','<li class="bb_li">','</table>','<tr class="bb_table_row">'
);

$array_str_replace=array(
'&nbsp;&nbsp;&nbsp;','<strong>','</strong>','<em>','</em>','<strike>','</strike>','&#8364;','&#8218;','&#402;','&#8222;',
'&#8230;','&#8224;','&#8225;','&#710;','&#8240;','&#352;','&#8249;','&#338;','&#381;',
'&#8216;','&#8217;','&#8220;','&#8221;','&#8226;','&#8211;','&#8212;','&#732;','&#8482;',
'&#353;','&#8250;','&#339;','&#382;','&#376;','<li>','</tbody></table>','<tr>'
);

$this->content=str_replace($array_str,$array_str_replace,$this->content);


$this->content=preg_replace(
array(
'`(<h3[^>]*>.*</h3>)\s*`iUs',
'`\s*<br />\s*`i',
'`<p>\s*</p>`i',
'`\s*</p>`i',
'`<p>\s*`i',
'`<p>&nbsp;</p>\s*<p>(<h4[^>]*>.*</h4>)</p>\s*<p>&nbsp;</p>`iUs'
),
array(
"</p>\n$1\n<p>",
"</p>\n<p>",
'<p>&nbsp;</p>',
'</p>',
'<p>',
'<br />$1<br />'
),
'<p>'.$this->content.'</p>'
);


$this->_unparse_tinymce_formatting();

$this->_unparse_bbcode_tags();

$this->content=htmlspecialchars($this->content);


if(!empty($this->array_tags['html_unparse']))
{
$this->array_tags['html_unparse']=array_map(array('TinyMCEUnparser','_clear_html_and_code_tag'),$this->array_tags['html_unparse']);
}
if(!empty($this->array_tags['code_unparse']))
{
$this->array_tags['code_unparse']=array_map(array('TinyMCEUnparser','_clear_html_and_code_tag'),$this->array_tags['code_unparse']);
}


$this->_unparse_code(REIMPLANT);
$this->_unparse_html(REIMPLANT);
}

## Protected ##



function _unparse_smilies()
{
$this->content=preg_replace('`<img src="[\./]*/images/smileys/([^"]+)" alt="([^"]+)" class="smiley" />`i',
'<img class="smiley" style="vertical-align:middle" src="'.PATH_TO_ROOT.'/images/smileys/$1" alt="$2" />',$this->content);
}




function _unparse_tinymce_formatting()
{

$array_preg=array(
'`<img src="([^"]+)" alt="" class="valign_([^"]+)?" />`i',
'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
"`<h3 class=\"title1\">(.*)</h3>(?:[\s]*<br />){0,}`isU",
"`<h3 class=\"title2\">(.*)</h3>(?:[\s]*<br />){0,}`isU",
"`<br /><h4 class=\"stitle1\">(.*)</h4><br />\s*`isU",
"`<br /><h4 class=\"stitle2\">(.*)</h4><br />\s*`isU",
'`<span style="color:([^;]+);">(.+)</span>`isU',
'`<span style="background-color:([^;]+);">(.+)</span>`isU',
'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
);
$array_preg_replace=array(
"<img src=\"$1\" alt=\"\" align=\"$2\" />",
"<p style=\"text-align: $1;\">$2</p>",
"<a title=\"$1\" name=\"$1\">$2</a>",
"<h1>$1</h1>",
"<h2>$1</h2>",
"<h3>$1</h3>",
"<h4>$1</h4>",
'<span style="color: $1;">$2</span>',
'<span style="background-color: $1;">$2</span>',
"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>"
);

$this->content=preg_replace($array_preg,$array_preg_replace,$this->content);


while(preg_match('`<table class="bb_table"( style="([^"]+)")?>`i',$this->content))
{
$this->content=preg_replace('`<table class="bb_table"( style="([^"]+)")?>`i',"<table border=\"0\"$1><tbody>",$this->content);
$this->content=preg_replace('`<td class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',"<td$1$2$3>",$this->content);
$this->content=preg_replace('`<th class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',"<th$1$2$3>",$this->content);
}


while(preg_match('`<ul( style="[^"]+")? class="bb_ul">`i',$this->content))
{
$this->content=preg_replace('`<ul( style="[^"]+")? class="bb_ul">`i',"<ul$1>",$this->content);
$this->content=preg_replace('`<ol( style="[^"]+")? class="bb_ol">`i',"<ol$1>",$this->content);
}


$this->content=str_replace('<hr class="bb_hr" />','<hr />',$this->content);


$this->content=preg_replace_callback('`<span style="font-size: ([0-9-]+)px;">(.+)</span>`isU',array(&$this,'_unparse_size_tag'),$this->content);


$this->_parse_imbricated('<span class="text_blockquote">','`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`isU',"\n".'<blockquote>$2</blockquote>',$this->content);


$this->content=preg_replace('`(?:<p>\s*</p>)?\s*<p>\s*<div class="indent">(.+)</div>\s*</p>`isU',"\n".'<p style="padding-left: 30px;">$1</p>',$this->content);


$this->content=preg_replace_callback('`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',array(&$this,'_unparse_font'),$this->content);
}




function _unparse_bbcode_tags()
{
$array_preg=array(
'`<p class="float_(left|right)">(.*)</p>`isU',
'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
'`<a href="mailto:(.*)">(.*)</a>`isU',
'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
'`<object type="application/x-shockwave-flash" data="(?:\.\.)?/kernel/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
'`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`sU',
'`<object type="application/x-shockwave-flash" data="(?:\.\.)?/(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object>`isU',
'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
'`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
'`<!-- START HTML -->'."\n".'(.+)'."\n".'<!-- END HTML -->`isU',
'`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU'
);

$array_preg_replace=array(
"[float=$1]$2[/float]",
"[acronym=$1]$2[/acronym]",
"[mail=$1]$2[/mail]",
"[style=$1]$2[/style]",
"[sound]$1[/sound]",
"[sound]$1[/sound]",
"[movie=$1,$2]$3[/movie]",
"[movie=$2,$3]$1[/movie]",
"[swf=$2,$3]$1[/swf]",
"[swf=$2,$3]$1[/swf]",
"[html]$1[/html]",
"[math]$1[/math]"
);

$this->content=preg_replace($array_preg,$array_preg_replace,$this->content);

##Remplacement des balises imbriquées


$this->_parse_imbricated('<span class="text_hide">','`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU','[hide]$2[/hide]',$this->content);


$this->_parse_imbricated('<div class="bb_block"','`<div class="bb_block">(.+)</div>`sU','[block]$1[/block]',$this->content);
$this->_parse_imbricated('<div class="bb_block" style=','`<div class="bb_block" style="([^"]+)">(.+)</div>`sU','[block style="$1"]$2[/block]',$this->content);


while(preg_match('`<fieldset class="bb_fieldset" style="([^"]*)"><legend>(.*)</legend>(.+)</fieldset>`sU',$this->content))
{
$this->content=preg_replace_callback('`<fieldset class="bb_fieldset" style="([^"]*)"><legend>(.*)</legend>(.+)</fieldset>`sU',array(&$this,'_unparse_fieldset'),$this->content);
}


$this->content=preg_replace_callback('`<a href="http://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia_link">(.*)</a>`sU',array(&$this,'_unparse_wikipedia_link'),$this->content);


$this->_parse_imbricated('<span class="text_hide">','`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU','[hide]$2[/hide]',$this->content);
}






function _clear_html_and_code_tag($var)
{
$var=str_replace("\n",'<br />',$var);
return htmlentities($var,ENT_NOQUOTES);
}







function _unparse_fieldset($matches)
{
$style='';
$legend='';

if(!empty($matches[1]))
{
$style=' style="'.$matches[1].'"';
}

if(!empty($matches[2]))
{
$legend=' legend="'.$matches[2].'"';
}

if(!empty($legend)||!empty($style))
{
return '[fieldset'.$legend.$style.']'.$matches[3].'[/fieldset]';
}
else
{
return '[fieldset]'.$matches[3].'[/fieldset]';
}
}







function _unparse_wikipedia_link($matches)
{
global $LANG;


if($matches[1]==$LANG['wikipedia_subdomain'])
{
$lang='';
}
else
{
$lang=$matches[1];
}


if($matches[2]!=$matches[3])
{
$page_name=$matches[2];
}
else
{
$page_name='';
}

return '[wikipedia'.(!empty($page_name)?' page="'.$page_name.'"':'').(!empty($lang)?' lang="'.$lang.'"':'').']'.$matches[3].'[/wikipedia]';
}








function _unparse_font($matches)
{
static $fonts_array=array(
'geneva'=>'trebuchet ms,geneva',
'optima'=>'comic sans ms,sans-serif',
'arial'=>'arial,helvetica,sans-serif',
'courier new'=>'courier new,courier'
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









function _unparse_size_tag($matches)
{
$size=0;

switch((int)$matches[1])
{
case 8:
$size='xx-small';
break;
case 10:
$size='x-small';
break;
case 12:
$size='small';
break;
case 14:
$size='medium';
break;
case 18:
$size='large';
break;
case 24:
$size='x-large';
break;
case 36:
$size='xx-large';
break;
default:
$size='';
}

if(!empty($size))
return '<span style="font-size: '.$size.';">'.$matches[2].'</span>';
else
return $matches[2];
}
}

?>