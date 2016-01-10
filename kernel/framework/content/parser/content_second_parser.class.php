<?php


























import('content/parser/parser');











class ContentSecondParser extends Parser
{
######## Public #######



function ContentSecondParser()
{
parent::Parser();
}




function parse()
{
global $LANG;


if(strpos($this->content,'[[CODE')!==false)
{
$this->content=preg_replace_callback('`\[\[CODE(?:=([A-Za-z0-9#+-]+))?(?:,(0|1)(?:,(0|1))?)?\]\](.+)\[\[/CODE\]\]`sU',array(&$this,'_callback_highlight_code'),$this->content);
}


if(strpos($this->content,'[[MEDIA]]')!==false)
{
$this->_process_media_insertion();
}


if(strpos($this->content,'[[MATH]]')!==false)
{
require_once(PATH_TO_ROOT.'/kernel/framework/content/math/mathpublisher.php');
$this->content=preg_replace_callback('`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU',array(&$this,'_math_code'),$this->content);
}

import('util/url');
$this->content=Url::html_convert_root_relative2absolute($this->content,$this->path_to_root,$this->page_path);
}






function export_html_text($html_content)
{
import('util/url');


$html_content=preg_replace('`<a href="([^"]+)" style="display:block;margin:auto;width:([0-9]+)px;height:([0-9]+)px;" id="[^"]*"></a><br /><div id=".*"></div>\s*<script type="text/javascript"><!--\s*insertMoviePlayer(\'([^\']+)\', ([0-9]+), ([0-9]+), \'[^\']*\');\s*--></script>`isU',
'<object type="application/x-shockwave-flash" data="/kernel/data/movieplayer.swf" width="$2" height="$3">
            	<param name="FlashVars" value="flv=$1&width=$2&height=$3" />
            	<param name="allowScriptAccess" value="never" />
                <param name="play" value="true" />
                <param name="movie" value="$1" />
                <param name="menu" value="false" />
                <param name="quality" value="high" />
                <param name="scalemode" value="noborder" />
                <param name="wmode" value="transparent" />
                <param name="bgcolor" value="#FFFFFF" />
            </object>',
$html_content);

return Url::html_convert_root_relative2absolute($html_content);
}

## Private ##












function _highlight_code($contents,$language,$line_number,$inline_code)
{
$contents=htmlspecialchars_decode($contents);


if(strtolower($language)=='bbcode')
{
import('content/parser/bbcode_highlighter');
$bbcode_highlighter=new BBCodeHighlighter();
$bbcode_highlighter->set_content($contents,PARSER_DO_NOT_STRIP_SLASHES);
$bbcode_highlighter->parse($inline_code);
$contents=$bbcode_highlighter->get_content(DO_NOT_ADD_SLASHES);
}

elseif(strtolower($language)=='tpl' || strtolower($language)=='template')
{
import('content/parser/template_highlighter');
require_once(PATH_TO_ROOT.'/kernel/framework/content/geshi/geshi.php');

$template_highlighter=new TemplateHighlighter();
$template_highlighter->set_content($contents,PARSER_DO_NOT_STRIP_SLASHES);
$template_highlighter->parse($line_number?GESHI_NORMAL_LINE_NUMBERS:GESHI_NO_LINE_NUMBERS,$inline_code);
$contents=$template_highlighter->get_content(DO_NOT_ADD_SLASHES);
}
elseif($language!='')
{
require_once(PATH_TO_ROOT.'/kernel/framework/content/geshi/geshi.php');
$Geshi=new GeSHi($contents,$language);

if($line_number)
$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);


if($inline_code)
$Geshi->set_header_type(GESHI_HEADER_NONE);

$contents='<pre style="display:inline;">'.$Geshi->parse_code().'</pre>';
}
else
{
$highlight=highlight_string($contents,true);
$font_replace=str_replace(array('<font ','</font>'),array('<span ','</span>'),$highlight);
$contents=preg_replace('`color="(.*?)"`','style="color:$1"',$font_replace);
}

return $contents;
}








function _callback_highlight_code($matches)
{
global $LANG;

$line_number=!empty($matches[2]);
$inline_code=!empty($matches[3]);

$contents=$this->_highlight_code($matches[4],$matches[1],$line_number,$inline_code);

if(!$inline_code&&!empty($matches[1]))
{
$contents='<span class="text_code">'.sprintf($LANG['code_langage'],strtoupper($matches[1])).'</span><div class="code">'.$contents.'</div>';
}
else if(!$inline_code&&empty($matches[1]))
{
$contents='<span class="text_code">'.$LANG['code_tag'].'</span><div class="code">'.$contents.'</div>';
}

return $contents;
}







function _math_code($matches)
{
$matches[1]=str_replace('<br />','',$matches[1]);
$matches=mathfilter(html_entity_decode($matches[1]),12);

return $matches;
}




function _process_media_insertion()
{

$this->content=preg_replace_callback('`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isU',array('ContentSecondParser','_process_swf_tag'),$this->content);

$this->content=preg_replace_callback('`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isU',array('ContentSecondParser','_process_movie_tag'),$this->content);

$this->content=preg_replace_callback('`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`isU',array('ContentSecondParser','_process_sound_tag'),$this->content);
}






function _process_swf_tag($matches)
{
return "<object type=\"application/x-shockwave-flash\" data=\"".$matches[1]."\" width=\"".$matches[2]."\" height=\"".$matches[3]."\">".
"<param name=\"allowScriptAccess\" value=\"never\" />".
"<param name=\"play\" value=\"true\" />".
"<param name=\"movie\" value=\"".$matches[1]."\" />".
"<param name=\"menu\" value=\"false\" />".
"<param name=\"quality\" value=\"high\" />".
"<param name=\"scalemode\" value=\"noborder\" />".
"<param name=\"wmode\" value=\"transparent\" />".
"<param name=\"bgcolor\" value=\"#000000\" />".
"</object>";
}






function _process_movie_tag($matches)
{
$id='movie_'.get_uid();
return '<a href="'.$matches[1].'" style="display:block;margin:auto;width:'.$matches[2].'px;height:'.$matches[3].'px;" id="'.$id.'"></a><br />'.
'<script type="text/javascript"><!--'."\n".
'insertMoviePlayer(\''.$id.'\');'.
"\n".'--></script>';
}






function _process_sound_tag($matches)
{

return '<object type="application/x-shockwave-flash" data="'.PATH_TO_ROOT.'/kernel/data/dewplayer.swf?son='.$matches[1].'" width="200" height="20">
         		<param name="allowScriptAccess" value="never" />
                <param name="play" value="true" />
                <param name="movie" value="'.PATH_TO_ROOT.'/kernel/data/dewplayer.swf?son='.$matches[1].'" />
                <param name="menu" value="false" />
                <param name="quality" value="high" />
                <param name="scalemode" value="noborder" />
                <param name="wmode" value="transparent" />
                <param name="bgcolor" value="#FFFFFF" />
            </object>';
}
}
?>