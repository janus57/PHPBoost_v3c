<?php


























import('content/parser/parser');


define('TPL_BRACES_STYLE','color:#7F3300;');
define('TPL_VARIABLE_STYLE','color:#FF6600; font-weight: bold;');
define('TPL_NESTED_VARIABLE_STYLE','color:#8F5211;');
define('TPL_SHARP_STYLE','color:#9915AF; font-weight: bold;');
define('TPL_KEYWORD_STYLE','color:#000066; font-weight: bold;');







class TemplateHighlighter extends Parser
{
######## Public #######



function TemplateHighlighter()
{
parent::Parser();
}






function parse($line_number=GESHI_NO_LINE_NUMBERS,$inline_code=false)
{

require_once(PATH_TO_ROOT.'/kernel/framework/content/geshi/geshi.php');

$geshi=new GeSHi($this->content,'html');

if($line_number)
{
$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
}


if($inline_code)
{
$geshi->set_header_type(GESHI_HEADER_NONE);
}

$this->content=$geshi->parse_code();




$this->content=preg_replace('`# IF( NOT)? ((?:\w+\.)*)(\w+) #`i','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">IF$1</span> <span style="'.TPL_NESTED_VARIABLE_STYLE.'">$2</span><span style="'.TPL_VARIABLE_STYLE.'">$3</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);
$this->content=preg_replace('`# ELSEIF( NOT)? ((?:\w+\.)*)(\w+) #`i','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">ELSEIF$1</span> <span style="'.TPL_NESTED_VARIABLE_STYLE.'">$2</span><span style="'.TPL_VARIABLE_STYLE.'">$3</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);
$this->content=str_replace('# ELSE #','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">ELSE</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);
$this->content=str_replace('# ENDIF #','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">ENDIF</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);


$this->content=preg_replace('`# START ((?:\w+\.)*)(\w+) #`i','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">START</span> <span style="'.TPL_NESTED_VARIABLE_STYLE.'">$1</span><span style="'.TPL_VARIABLE_STYLE.'">$2</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);
$this->content=preg_replace('`# END ((?:\w+\.)*)(\w+) #`i','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">END</span> <span style="'.TPL_NESTED_VARIABLE_STYLE.'">$1</span><span style="'.TPL_VARIABLE_STYLE.'">$2</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);


$this->content=preg_replace('`# INCLUDE ([\w]+) #`','<span style="'.TPL_SHARP_STYLE.'">#</span> <span style="'.TPL_KEYWORD_STYLE.'">INCLUDE </span> <span style="'.TPL_VARIABLE_STYLE.'">$1</span> <span style="'.TPL_SHARP_STYLE.'">#</span>',$this->content);


$this->content=preg_replace('`{([\w]+)}`i','<span style="'.TPL_BRACES_STYLE.'">{</span><span style="'.TPL_VARIABLE_STYLE.'">$1</span><span style="'.TPL_BRACES_STYLE.'">}</span>',$this->content);

$this->content=preg_replace('`{((?:[\w]+\.)+)([\w]+)}`i','<span style="'.TPL_BRACES_STYLE.'">{</span><span style="'.TPL_NESTED_VARIABLE_STYLE.'">$1</span><span style="'.TPL_VARIABLE_STYLE.'">$2</span><span style="'.TPL_BRACES_STYLE.'">}</span>',$this->content);

if($inline_code)
{
$this->content='<pre style="display:inline; font-color:courier new;">'.$this->content.'</pre>';
}
}
}
?>