<?php



























define('HTML_NO_PROTECT',false);
define('HTML_PROTECT',true);

define('ADDSLASHES_AUTO',0);

define('ADDSLASHES_FORCE',1);

define('ADDSLASHES_NONE',2);
define('MAGIC_QUOTES_DISABLED',false);
define('NO_UPDATE_PAGES',true);
define('NO_FATAL_ERROR',false);
define('NO_EDITOR_UNPARSE',false);
define('TIMEZONE_SITE',1);
define('TIMEZONE_SYSTEM',2);
define('TIMEZONE_USER',3);




























function retrieve($var_type,$var_name,$default_value,$force_type=NULL,$flags=0)
{
$var=null;
switch($var_type)
{
case GET:
if(isset($_GET[$var_name]))
{
$var=$_GET[$var_name];
}
break;
case POST:
if(isset($_POST[$var_name]))
{
$var=$_POST[$var_name];
}
break;
case REQUEST:
if(isset($_REQUEST[$var_name]))
{
$var=$_REQUEST[$var_name];
}
break;
case COOKIE:
if(isset($_COOKIE[$var_name]))
{
$var=$_COOKIE[$var_name];
}
break;
case FILES:
if(isset($_FILES[$var_name]))
{
$var=$_FILES[$var_name];
}
break;
default:
break;
}


if($var===null ||(($flags&USE_DEFAULT_IF_EMPTY!=0)&&empty($var)))
{
return $default_value;
}

$force_type=!isset($force_type)?gettype($default_value):$force_type;
switch($force_type)
{
case TINTEGER:
return(int)$var;
case TSTRING:
return strprotect($var);
case TSTRING_UNCHANGE:
if(MAGIC_QUOTES)
{
$var=trim(stripslashes($var));
}
else
{
$var=trim($var);
}
return(string)$var;
case TSTRING_PARSE:
return strparse($var);
case TBOOL:
return(bool)$var;
case TUNSIGNED_INT:
$var=(int)$var;
return $var>0?$var:max(0,$default_value);
case TUNSIGNED_DOUBLE:
$var=(double)$var;
return $var>0.0?$var:max(0.0,$default_value);
case TSTRING_HTML:
return strprotect($var,HTML_NO_PROTECT);
case TSTRING_AS_RECEIVED:
return(string)$var;
case TARRAY:
return(array)$var;
case TDOUBLE:
return(double)$var;
case TNONE:
return $var;
default:
return $default_value;
}
}










function strprotect($var,$html_protect=HTML_PROTECT,$addslashes=ADDSLASHES_AUTO)
{
$var=trim((string)$var);


if($html_protect)
{
$var=htmlspecialchars($var);

$var=preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i',"&$1;",$var);
}

switch($addslashes)
{
case ADDSLASHES_FORCE:

$var=addslashes($var);
break;
case ADDSLASHES_NONE:

$var=stripslashes($var);
break;

case ADDSLASHES_AUTO:
default:

if(!MAGIC_QUOTES)
{
$var=addslashes($var);
}
}

return $var;
}







function numeric($var,$type='int')
{
if(is_numeric($var))
{
if($type==='float')
{
return(float)$var;
}
else
{
return(int)$var;
}
}
else
{
return 0;
}
}





function get_utheme()
{
global $User;
return!empty($User)?$User->get_attribute('user_theme'):'default';
}





function get_ulang()
{
global $User;
return $User->get_attribute('user_lang');
}










function wordwrap_html(&$str,$lenght,$cut_char='<br />',$cut=true)
{
$str=wordwrap(html_entity_decode($str),$lenght,$cut_char,$cut);
return str_replace('&lt;br /&gt;','<br />',htmlspecialchars($str,ENT_NOQUOTES));
}











function substr_html(&$str,$start,$end='')
{
if($end=='')
{
return htmlspecialchars(substr(html_entity_decode($str),$start),ENT_NOQUOTES);
}
else
{
return htmlspecialchars(substr(html_entity_decode($str),$start,$end),ENT_NOQUOTES);
}
}







function display_editor($field='contents',$forbidden_tags=array())
{
$content_editor=new ContentFormattingFactory();
$editor=$content_editor->get_editor();
if(!empty($forbidden_tags)&&is_array($forbidden_tags))
{
$editor->set_forbidden_tags($forbidden_tags);
}
$editor->set_identifier($field);

return $editor->display();
}










function display_comments($script,$idprov,$vars,$module_folder='')
{
import('content/comments');
$comments=new Comments($script,$idprov,$vars,$module_folder);

return $comments->display();
}







function load_module_lang($module_name,$path=PATH_TO_ROOT)
{
global $LANG;

$file=$path.'/'.$module_name.'/lang/'.get_ulang().'/'.$module_name.'_'.get_ulang().'.php';
if(!DEBUG){
$result=@include_once($file);
}
else
{
$result=include_once($file);
}

if(!$result)
{
$lang=find_require_dir(PATH_TO_ROOT.'/'.$module_name.'/lang/',get_ulang(),NO_FATAL_ERROR);
$file2=PATH_TO_ROOT.'/'.$module_name.'/lang/'.$lang.'/'.$module_name.'_'.$lang.'.php';

if(!DEBUG)
{
$result2=@include_once($file2);
}
else
{
$result2=include_once($file2);
}

if(!$result2)
{
global $Errorh;


$Errorh->handler(sprintf('Unable to load lang file \'%s\'!',PATH_TO_ROOT.'/'.$module_name.'/lang/'.$lang.'/'.$module_name.'_'.$lang.'.php'),E_USER_ERROR,__LINE__,__FILE__);
exit;
}
}
}






function load_menu_lang($menu_name)
{
load_module_lang($menu_name,PATH_TO_ROOT.'/menus');
}









function load_ini_file($dir_path,$require_dir,$ini_name='config.ini')
{
$dir=find_require_dir($dir_path,$require_dir,false);
$file=$dir_path.$dir.'/'.$ini_name;
if(!DEBUG)
{
$result=@parse_ini_file($file);
}
elseif(file_exists($file))
{
$result=parse_ini_file($file);
}
else
{
$result=FALSE;
}
return $result;
}








function parse_ini_array($links_format)
{
$links_format=preg_replace('` ?=> ?`','=',$links_format);
$links_format=preg_replace(' ?, ?',',',$links_format).' ';
list($key,$value,$open,$cursor,$check_value,$admin_links)=array('','','',0,false,array());
$string_length=strlen($links_format);
while($cursor<$string_length)
{
$char=substr($links_format,$cursor,1);
if(!$check_value)
{
if($char!='=')
{
$key.=$char;
}
else
{
$check_value=true;
}
}
else
{
if($char=='(')
{
$open=$key;
}

if($char!=','&&$char!='('&&$char!=')'&&($cursor+1)<$string_length)
{
$value.=$char;
}
else
{
if(!empty($open)&&!empty($value))
{
$admin_links[$open][$key]=$value;
}
else
{
$admin_links[$key]=$value;
}
list($key,$value,$check_value)=array('','',false);
}
if($char==')')
{
$open='';
$cursor++;
}
}
$cursor++;
}
return $admin_links;
}










function get_ini_config($dir_path,$require_dir,$ini_name='config.ini')
{
$dir=find_require_dir($dir_path,$require_dir,false);
import('io/filesystem/file');

$module_config_file=new File($dir_path.$dir.'/config.ini',READ);
$module_config_file->open();
$module_config_text=$module_config_file->get_contents();


$result=array();


if(preg_match('`;config="(.*)"\s*$`s',$module_config_text,$result))
{
return str_replace('\n',"\r\n",$result[1]);
}

else
{
return '';
}
}











function find_require_dir($dir_path,$require_dir,$fatal_error=true)
{

if(!@file_exists($dir_path.$require_dir))
{
if(@is_dir($dir_path)&&$dh=@opendir($dir_path))
{
while(!is_bool($dir=readdir($dh)))
{
if(strpos($dir,'.')===false)
{
closedir($dh);
return $dir;
}
}
closedir($dh);
}
}
else
{
return $require_dir;
}

if($fatal_error)
{
global $Errorh;


$Errorh->handler(sprintf('Unable to load required directory \'%s\'!',$dir_path.$require_dir),E_USER_ERROR,__LINE__,__FILE__);
exit;
}
}





function get_module_name()
{
$path=str_replace(DIR,'',SCRIPT);
$path=trim($path,'/');
$module_name=explode('/',$path);

return $module_name[0];
}





function redirect($url)
{
global $Sql,$CONFIG;

if(!empty($Sql)&&is_object($Sql))
{
$Sql->close();
}
if(!empty($CONFIG)&&is_array($CONFIG))
{
import('util/url');
$url=new Url($url);
$url=$url->absolute();
}
header('Location:'.$url);
exit;
}







function redirect_confirm($url_error,$l_error,$delay_redirect=3)
{
global $LANG;

$template=new Template('framework/confirm.tpl');

$template->assign_vars(array(
'URL_ERROR'=>!empty($url_error)?$url_error:get_start_page(),
'DELAY_REDIRECT'=>$delay_redirect,
'L_ERROR'=>$l_error,
'L_REDIRECT'=>$LANG['redirect']
));

$template->parse();
}





function get_start_page()
{
global $CONFIG;

$start_page=(substr($CONFIG['start_page'],0,1)=='/')?url(HOST.DIR.$CONFIG['start_page']):$CONFIG['start_page'];
return $start_page;
}







function check_nbr_links($contents,$max_nbr)
{
if($max_nbr==-1)
{
return true;
}

$nbr_link=preg_match_all('`(?:ftp|https?)://`',$contents,$array);
if($nbr_link!==false&&$nbr_link>$max_nbr)
{
return false;
}

return true;
}








function check_mail($mail)
{
import('io/mail');
return Mail::check_validity($mail);
}









function strparse(&$content,$forbidden_tags=array(),$addslashes=true)
{

$content_manager=new ContentFormattingFactory();

$parser=$content_manager->get_parser();


$parser->set_content($content,MAGIC_QUOTES);


if(!empty($forbidden_tags))
{
$parser->set_forbidden_tags($forbidden_tags);
}

$parser->parse();


return $parser->get_content($addslashes);
}









function unparse(&$content)
{
$content_manager=new ContentFormattingFactory();
$parser=$content_manager->get_unparser();
$parser->set_content($content,PARSER_DO_NOT_STRIP_SLASHES);
$parser->parse();

return $parser->get_content(DO_NOT_ADD_SLASHES);
}









function second_parse(&$content)
{
$content_manager=new ContentFormattingFactory();

$parser=$content_manager->get_second_parser();
$parser->set_content($content,PARSER_DO_NOT_STRIP_SLASHES);
$parser->parse();

return $parser->get_content(DO_NOT_ADD_SLASHES);
}







function second_parse_url(&$url)
{
import('util/url');
$Url=new Url($url);
return $Url->absolute();
}









function url($url,$mod_rewrite='',$ampersand='&amp;')
{
global $CONFIG,$Session;

if(!is_object($Session))
{
$session_mod=0;
}
else
{
$session_mod=$Session->session_mod;
}

if($session_mod==0)
{
if($CONFIG['rewrite']==1&&!empty($mod_rewrite))
{
return $mod_rewrite;
}
else
{
return $url;
}
}
elseif($session_mod==1)
{
return $url.((strpos($url,'?')===false)?'?':$ampersand).'sid='.$Session->data['session_id'].$ampersand.'suid='.$Session->data['user_id'];
}
}






function url_encode_rewrite($string)
{
$string=strtolower(html_entity_decode($string));
$string=strtr($string,' éèêàâùüûïîôç','-eeeaauuuiioc');
$string=preg_replace('`([^a-z0-9]|[\s])`','-',$string);
$string=preg_replace('`[-]{2,}`','-',$string);
$string=trim($string,' -');

return $string;
}










function gmdate_format($format,$timestamp=false,$timezone_system=0)
{
global $User,$CONFIG,$LANG;

if(strpos($format,'date_format')!==false)
{
switch($format)
{
case 'date_format':
$format=$LANG['date_format'];
break;
case 'date_format_tiny':
$format=$LANG['date_format_tiny'];
break;
case 'date_format_short':
$format=$LANG['date_format_short'];

break;
case 'date_format_long':
$format=$LANG['date_format_long'];
break;
}
}

if($timestamp===false)
{
$timestamp=time();
}


$serveur_hour=number_round(date('Z')/3600,0)-date('I');

if($timezone_system==1)
{
$timezone=$CONFIG['timezone']-$serveur_hour;
}
elseif($timezone_system==2)
{
$timezone=0;
}
else
{
$timezone=$User->get_attribute('user_timezone')-$serveur_hour;
}

if($timezone!=0)
{
$timestamp+=$timezone*3600;
}

if($timestamp<=0)
{
return '';
}

return date($format,$timestamp);
}









function strtotimestamp($str,$date_format)
{
global $CONFIG,$User;

list($month,$day,$year)=array(0,0,0);
$array_timestamp=explode('/',$str);
$array_date=explode('/',$date_format);
for($i=0;$i<3;$i++)
{
switch($array_date[$i])
{
case 'd':
$day=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;
case 'm':
$month=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;
case 'y':
$year=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;
}
}


if(checkdate($month,$day,$year))
{
$timestamp=@mktime(0,0,1,$month,$day,$year);
}
else
{
$timestamp=time();
}

$serveur_hour=number_round(date('Z')/3600,0)-date('I');
$timezone=$User->get_attribute('user_timezone')-$serveur_hour;
if($timezone!=0)
{
$timestamp-=$timezone*3600;
}

return($timestamp>0)?$timestamp:time();
}









function strtodate($str,$date_format)
{
list($month,$day,$year)=array(0,0,0);
$array_date=explode('/',$str);
$array_format=explode('/',$date_format);
for($i=0;$i<3;$i++)
{
switch($array_format[$i])
{
case 'DD':
$day=(isset($array_date[$i]))?numeric($array_date[$i]):0;
break;
case 'MM':
$month=(isset($array_date[$i]))?numeric($array_date[$i]):0;
break;
case 'YYYY':
$year=(isset($array_date[$i]))?numeric($array_date[$i]):0;
break;
}
}


if(checkdate($month,$day,$year))
{
$date=$year.'-'.$month.'-'.$day;
}
else
{
$date='0000-00-00';
}

return $date;
}







function delete_file($file)
{
global $LANG;

if(function_exists('unlink'))
{
if(file_exists($file))
{
return @unlink($file);
}
}
else
{
return false;
}
}






function pages_displayed($no_update=false)
{
$data=array();
if($file=@fopen(PATH_TO_ROOT.'/cache/pages.txt','r+'))
{
$hour=gmdate_format('G');
$data=unserialize(fgets($file,4096));
if(!$no_update)
{
if(isset($data[$hour]))
{
$data[$hour]++;
}
else
{
$data[$hour]=1;
}
}

rewind($file);
fwrite($file,serialize($data));
fclose($file);
}
else if($file=@fopen(PATH_TO_ROOT.'/cache/pages.txt','w+'))
{
$data=array();
fwrite($file,serialize($data));
fclose($file);
}

return $data;
}







function number_round($number,$dec)
{
return trim(number_format($number,$dec,'.',''));
}








function file_get_contents_emulate($filename,$incpath=false,$resource_context=null)
{
if(false===($fh=@fopen($filename,'rb',$incpath)))
{
user_error('file_get_contents_emulate() failed to open stream: No such file or directory',E_USER_WARNING);
return false;
}

clearstatcache();
if($fsize=@filesize($filename))
{
$data=fread($fh,$fsize);
}
else
{
$data='';
while(!feof($fh))
{
$data.=fread($fh,8192);
}
}
fclose($fh);
return $data;
}


if(!function_exists('html_entity_decode'))
{
function html_entity_decode($string,$quote_style=ENT_COMPAT,$charset=null)
{
if(!is_int($quote_style))
{
user_error('html_entity_decode() expects parameter 2 to be long, '.
gettype($quote_style).' given',E_USER_WARNING);
return;
}

$trans_tbl=array_flip(get_html_translation_table(HTML_ENTITIES));


$trans_tbl['&#039;']='\'';


if($quote_style&ENT_NOQUOTES)
{
unset($trans_tbl['&quot;']);
}
return strtr($string,$trans_tbl);
}
}


if(!function_exists('htmlspecialchars_decode'))
{
function htmlspecialchars_decode($string,$quote_style=null)
{

if(!is_scalar($string))
{
user_error('htmlspecialchars_decode() expects parameter 1 to be string, '.gettype($string).' given',E_USER_WARNING);
return;
}

if(!is_int($quote_style)&&$quote_style!==null)
{
user_error('htmlspecialchars_decode() expects parameter 2 to be integer, '.gettype($quote_style).' given',E_USER_WARNING);
return;
}


$from=array('&amp;','&lt;','&gt;');
$to=array('&','<','>');



if($quote_style&ENT_COMPAT || $quote_style&ENT_QUOTES)
{
$from[]='&quot;';
$to[]='"';

$from[]='&#039;';
$to[]="'";
}

return str_replace($from,$to,$string);
}
}


if(!function_exists('array_combine'))
{
function array_combine($keys,$values)
{
if(!is_array($keys))
{
user_error('array_combine() expects parameter 1 to be array, '.
gettype($keys).' given',E_USER_WARNING);
return;
}

if(!is_array($values))
{
user_error('array_combine() expects parameter 2 to be array, '.
gettype($values).' given',E_USER_WARNING);
return;
}

$key_count=count($keys);
$value_count=count($values);
if($key_count!==$value_count){
user_error('array_combine() Both parameters should have equal number of elements',E_USER_WARNING);
return false;
}

if($key_count===0 || $value_count===0)
{
user_error('array_combine() Both parameters should have number of elements at least 0',E_USER_WARNING);
return false;
}

$keys=array_values($keys);
$values=array_values($values);

$combined=array();
for($i=0;$i<$key_count;$i++)
{
$combined[$keys[$i]]=$values[$i];
}

return $combined;
}
}









function strhash($str,$salt=true)
{

if($salt===true)
{
$str=md5($str).$str;
}
elseif($salt!==false)
{
$str=$salt.$str;
}

if(phpversion()>='5.1.2'&&@extension_loaded('pecl'))
{
return hash('sha256',$str);
}
else
{
import('lib/sha256');
return SHA256::hash($str);
}
}





function get_uid()
{
static $uid=1764;
return $uid++;
}

define('CLASS_IMPORT','.class.php');
define('INC_IMPORT','.inc.php');
define('LIB_IMPORT','.lib.php');








function import($path,$import_type=CLASS_IMPORT)
{
require_once(PATH_TO_ROOT.'/kernel/framework/'.$path.$import_type);
}






function req($file,$once=true)
{
if($once)
{
if(!DEBUG)
@require_once PATH_TO_ROOT.$file;
else
require_once PATH_TO_ROOT.$file;
}
else
{
if(!DEBUG)
@require PATH_TO_ROOT.$file;
else
require PATH_TO_ROOT.$file;
}
}








function inc($file,$once=true)
{
if($once)
{
if(!DEBUG)
return(@include_once(PATH_TO_ROOT.$file))!==false;
else
return(include_once(PATH_TO_ROOT.$file))!==false;
}
else
{
if(!DEBUG)
return(@include(PATH_TO_ROOT.$file))!==false;
else
return(include(PATH_TO_ROOT.$file))!==false;
}
}








function of_class(&$object,$classname)
{
if(!is_object($object))
{
return false;
}

return strtolower(get_class($object))==strtolower($classname)||
is_subclass_of(strtolower(get_class($object)),strtolower($classname));
}






function to_js_string($string)
{
return '\''.str_replace(array("\r\n","\r","\n"),array('\n','\n','\n'),
addcslashes($string,'\'')).'\'';
}













function set_subregex_multiplicity($sub_regex,$multiplicity_option)
{
switch($multiplicity_option)
{
case REGEX_MULTIPLICITY_OPTIONNAL:

return '(?:'.$sub_regex.')?';
case REGEX_MULTIPLICITY_REQUIRED:

return $sub_regex;
case REGEX_MULTIPLICITY_AT_LEAST_ONE:

return '(?:'.$sub_regex.')+';
case REGEX_MULTIPLICITY_ALL:

return '(?:'.$sub_regex.')*';
case REGEX_MULTIPLICITY_NOT_USED:
default:

return '';
}
}





function phpboost_version(){
global $CONFIG;
import('io/filesystem/file');
$file=new File(PATH_TO_ROOT.'/kernel/.build');
$build=$file->get_contents();
$file->close();
return $CONFIG['version'].'.'.trim($build);
}

?>
