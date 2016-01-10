<?php






























define('PATH_TO_ROOT','../../..');
define('NO_SESSION_LOCATION',true);

include_once(PATH_TO_ROOT.'/kernel/begin.php');
include_once(PATH_TO_ROOT.'/kernel/header_no_display.php');

$page_path_to_root=retrieve(REQUEST,'path_to_root','');
$page_path=retrieve(REQUEST,'page_path','');


$editor=retrieve(REQUEST,'editor',$CONFIG['editor']);

$contents=utf8_decode(retrieve(POST,'contents','',TSTRING_AS_RECEIVED));

$ftags=retrieve(POST,'ftags',TSTRING_UNCHANGE);
$forbidden_tags=explode(',',$ftags);


$content_manager=new ContentFormattingFactory($editor);
$parser=$content_manager->get_parser($editor);

$parser->set_content($contents,MAGIC_QUOTES);
$parser->set_path_to_root($page_path_to_root);
$parser->set_page_path($page_path);

if(!empty($forbidden_tags))
{
$parser->set_forbidden_tags($forbidden_tags);
}

$parser->parse();


$second_parser=$content_manager->get_second_parser();
$second_parser->set_content($parser->get_content(DO_NOT_ADD_SLASHES),PARSER_DO_NOT_STRIP_SLASHES);
$second_parser->set_path_to_root($page_path_to_root);
$second_parser->set_page_path($page_path);

$second_parser->parse();

$contents=$second_parser->get_content(DO_NOT_ADD_SLASHES);

echo $contents;

include_once(PATH_TO_ROOT.'/kernel/footer_no_display.php');

?>