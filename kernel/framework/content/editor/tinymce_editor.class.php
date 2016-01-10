<?php


























import('content/editor/editor');







class TinyMCEEditor extends ContentEditor
{
function TinyMCEEditor()
{
parent::ContentEditor();
}





function display()
{
global $CONFIG,$Sql,$LANG,$Cache,$User,$CONFIG_UPLOADS;

$template=$this->get_template();


$Cache->load('uploads');

$template->assign_vars(array(
'PAGE_PATH'=>$_SERVER['PHP_SELF'],
'C_BBCODE_NORMAL_MODE'=>false,
'C_BBCODE_TINYMCE_MODE'=>true,
'C_UPLOAD_MANAGEMENT'=>$User->check_auth($CONFIG_UPLOADS['auth_files'],AUTH_FILES),
'EDITOR_NAME'=>'tinymce',
'FIELD'=>$this->identifier,
'FORBIDDEN_TAGS'=>implode(',',$this->forbidden_tags),
'TINYMCE_TRIGGER'=>'tinyMCE.triggerSave();',
'IDENTIFIER'=>$this->identifier,
'L_REQUIRE_TEXT'=>$LANG['require_text'],
'L_BB_UPLOAD'=>$LANG['bb_upload']
));

list($theme_advanced_buttons1,$theme_advanced_buttons2,$theme_advanced_buttons3)=array('','','');
foreach($this->array_tags as $tag=>$tinymce_tag)
{
$tag=preg_replace('`[0-9]`','',$tag);

if(!in_array($tag,$this->forbidden_tags))
{
$theme_advanced_buttons1.=$tinymce_tag.',';
}
}
foreach($this->array_tags2 as $tag=>$tinymce_tag)
{
$tag=preg_replace('`[0-9]`','',$tag);
if(!in_array($tag,$this->forbidden_tags))
{
$theme_advanced_buttons2.=$tinymce_tag.',';
}
}
foreach($this->array_tags3 as $tag=>$tinymce_tag)
{
$tag=preg_replace('`[0-9]`','',$tag);
if(!in_array($tag,$this->forbidden_tags))
{
$theme_advanced_buttons3.=$tinymce_tag.',';
}
}
$template->assign_vars(array(
'THEME_ADVANCED_BUTTONS1'=>preg_replace('`\|(,\|)+`','|',trim($theme_advanced_buttons1,',')),
'THEME_ADVANCED_BUTTONS2'=>preg_replace('`\|(,\|)+`','|',trim($theme_advanced_buttons2,',')),
'THEME_ADVANCED_BUTTONS3'=>preg_replace('`\|(,\|)+`','|',trim($theme_advanced_buttons3,','))
));

return $template->parse(TEMPLATE_STRING_MODE);
}


var $array_tags=array('align1'=>'justifyleft','align2'=>'justifycenter','align3'=>'justifyright','align4'=>'justifyfull','|1'=>'|','title'=>'formatselect','|2'=>'|','list1'=>'bullist','list2'=>'numlist','|3'=>'|','indent1'=>'outdent','indent2'=>'indent','|4'=>'|','quote'=>'blockquote','line'=>'hr','|5'=>'|','_cut'=>'cut','_copy'=>'copy','_paste'=>'paste','|6'=>'|','_undo'=>'undo','_redo'=>'redo');
var $array_tags2=array('b'=>'bold','i'=>'italic','u'=>'underline','s'=>'strikethrough','|1'=>'|','color1'=>'forecolor','color2'=>'backcolor','|1'=>'|','|2'=>'|','size'=>'fontsizeselect','font'=>'fontselect','|3'=>'|','sub'=>'sub','sup'=>'sup','|4'=>'|','url1'=>'link','url2'=>'unlink','|5'=>'|','img'=>'image','swf'=>'flash');
var $array_tags3=array('emotions'=>'emotions','table'=>'tablecontrols','|2'=>'|','image','anchor'=>'anchor','_charmap'=>'charmap','3|'=>'|','_cleanup'=>'cleanup','_removeformat'=>'removeformat','|4'=>'|','_search'=>'search','_replace'=>'replace','|5'=>'|','_fullscreen'=>'fullscreen');
}

?>