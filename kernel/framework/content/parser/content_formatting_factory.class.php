<?php


























define('BBCODE_LANGUAGE','bbcode');
define('TINYMCE_LANGUAGE','tinymce');
define('DEFAULT_LANGUAGE','default');









class ContentFormattingFactory
{










function ContentFormattingFactory($language_type=false)
{
if($language_type!==false)
{
$this->set_language($language_type);
}
}











function set_language($language_type=DEFAULT_LANGUAGE)
{

if(in_array($language_type,array(BBCODE_LANGUAGE,TINYMCE_LANGUAGE)))
{
$this->language_type=$language_type;
}
else
{
$this->language_type=DEFAULT_LANGUAGE;
}
}





function get_language()
{
return $this->language_type;
}





function get_parser()
{
global $CONFIG;
switch($this->language_type)
{
case BBCODE_LANGUAGE:
import('content/parser/bbcode_parser');
return new BBCodeParser();
case TINYMCE_LANGUAGE:
import('content/parser/tinymce_parser');
return new TinyMCEParser();
default:
if($this->get_user_editor()==TINYMCE_LANGUAGE)
{
import('content/parser/tinymce_parser');
return new TinyMCEParser();
}
else
{
import('content/parser/bbcode_parser');
return new BBCodeParser();
}
}
}





function get_unparser()
{
global $CONFIG;
switch($this->language_type)
{
case BBCODE_LANGUAGE:
import('content/parser/bbcode_unparser');
return new BBCodeUnparser();
case TINYMCE_LANGUAGE:
import('content/parser/tinymce_unparser');
return new TinyMCEUnparser();
default:
if($this->get_user_editor()==TINYMCE_LANGUAGE)
{
import('content/parser/tinymce_unparser');
return new TinyMCEUnparser();
}
else
{
import('content/parser/bbcode_unparser');
return new BBCodeUnparser();
}
}
}





function get_second_parser()
{
import('content/parser/content_second_parser');
return new ContentSecondParser();
}





function get_editor()
{
switch($this->language_type)
{
case BBCODE_LANGUAGE:
import('content/editor/bbcode_editor');
return new BBCodeEditor();
case TINYMCE_LANGUAGE:
import('content/editor/tinymce_editor');
return new TinyMCEEditor();
default:
if($this->get_user_editor()==TINYMCE_LANGUAGE)
{
import('content/editor/tinymce_editor');
return new TinyMCEEditor();
}
else
{
import('content/editor/bbcode_editor');
return new BBCodeEditor();
}
}
}





function get_user_editor()
{
global $User;
return $User->get_attribute('user_editor');
}







function get_available_tags()
{
global $LANG;
return array(
'b'=>$LANG['format_bold'],
'i'=>$LANG['format_italic'],
'u'=>$LANG['format_underline'],
's'=>$LANG['format_strike'],
'title'=>$LANG['format_title'],
'style'=>$LANG['format_style'],
'url'=>$LANG['format_url'],
'img'=>$LANG['format_img'],
'quote'=>$LANG['format_quote'],
'hide'=>$LANG['format_hide'],
'list'=>$LANG['format_list'],
'color'=>$LANG['format_color'],
'bgcolor'=>$LANG['format_bgcolor'],
'font'=>$LANG['format_font'],
'size'=>$LANG['format_size'],
'align'=>$LANG['format_align'],
'float'=>$LANG['format_float'],
'sup'=>$LANG['format_sup'],
'sub'=>$LANG['format_sub'],
'indent'=>$LANG['format_indent'],
'pre'=>$LANG['format_pre'],
'table'=>$LANG['format_table'],
'swf'=>$LANG['format_flash'],
'movie'=>$LANG['format_movie'],
'sound'=>$LANG['format_sound'],
'code'=>$LANG['format_code'],
'math'=>$LANG['format_math'],
'anchor'=>$LANG['format_anchor'],
'acronym'=>$LANG['format_acronym'],
'block'=>$LANG['format_block'],
'fieldset'=>$LANG['format_fieldset'],
'mail'=>$LANG['format_mail'],
'line'=>$LANG['format_line'],
'wikipedia'=>$LANG['format_wikipedia'],
'html'=>$LANG['format_html']
);
}

## Private ##



var $language_type=DEFAULT_LANGUAGE;
}

?>
