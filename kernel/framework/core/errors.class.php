<?php




























define('ARCHIVE_ALL_ERRORS',true);
define('ARCHIVE_ERROR',true);
define('NO_ARCHIVE_ERROR',false);
define('NO_LINE_ERROR','');
define('NO_FILE_ERROR','');
define('DISPLAY_ALL_ERROR',false);

if(!defined('E_STRICT'))
define('E_STRICT',2048);






class Errors
{
## Public Methods ##




function Errors($archive_all=false)
{
$this->archive_all=$archive_all;


$server_path=!empty($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:getenv('PHP_SELF');
if(!$server_path)
$server_path=!empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:getenv('REQUEST_URI');
$server_path=trim(dirname($server_path));

$nbr_occur=substr_count(PATH_TO_ROOT,'..');
for($i=0;$i<$nbr_occur;$i++)
$server_path=str_replace(substr(strrchr($server_path,'/'),0),'',$server_path);
$this->redirect='http://'.$_SERVER['HTTP_HOST'].$server_path;


set_error_handler(array($this,'handler_php'));


$this->set_default_template();
}








function handler_php($errno,$errstr,$errfile,$errline)
{
global $LANG,$CONFIG;

if(!($errno&ERROR_REPORTING))
return true;


if(!DISPLAY_ALL_ERROR&&error_reporting()==0)
return true;

switch($errno)
{

case E_USER_NOTICE:
case E_NOTICE:
case E_STRICT:
$errdesc=$LANG['e_notice'];
$errimg='notice';
$errclass='error_notice';
break;

case E_USER_WARNING:
case E_WARNING:
$errdesc=$LANG['e_warning'];
$errimg='important';
$errclass='error_warning';
break;

case E_USER_ERROR:
case E_ERROR:
$errdesc=$LANG['error'];
$errimg='stop';
$errclass='error_fatal';
break;

default:
$errdesc=$LANG['e_unknow'];
$errimg='question';
$errclass='error_unknow';
}


echo '<div class="'.$errclass.'" style="width:500px;margin:auto;padding:15px;">
			<img src="'.PATH_TO_ROOT.'/templates/'.get_utheme().'/images/'.$errimg.'.png" alt="" style="float:left;padding-right:6px;" />
			<strong>'.$errdesc.'</strong> : '.$errstr.' '.$LANG['infile'].' <strong>'.$errfile.'</strong> '.$LANG['atline'].' <strong>'.$errline.'</strong>
			<br />
		</div>';


$this->_error_log($errfile,$errline,$errno,$errstr,true);


if($errno==E_USER_ERROR)
exit;


return true;
}











function handler($errstr,$errno,$errline='',$errfile='',$tpl_cond='',$archive=false,$stop=true)
{
global $LANG;
$_err_stop=retrieve(GET,'_err_stop',false);


if(!empty($errstr))
{
switch($errno)
{
case E_TOKEN:
$this->_error_log($errfile,$errline,$errno,$errstr,$archive);
break;

case E_USER_REDIRECT:
$this->_error_log($errfile,$errline,$errno,$errstr,$archive);
if(!$_err_stop)
redirect($this->redirect.'/member/error'.url('.php?e='.$errstr.'&_err_stop=1'));
else
die($errstr);
break;

case E_USER_SUCCESS:
$errstr=sprintf($LANG['error_success'],$errstr,'','');
$this->template->assign_vars(array(
'C_ERROR_HANDLER'.strtoupper($tpl_cond)=>true,
'ERRORH_IMG'=>'success',
'ERRORH_CLASS'=>'error_success',
'L_ERRORH'=>$errstr
));
break;

case E_USER_NOTICE:
case E_NOTICE:
$errstr=sprintf($LANG['error_notice_tiny'],$errstr,'','');
$this->template->assign_vars(array(
'C_ERROR_HANDLER'.strtoupper($tpl_cond)=>true,
'ERRORH_IMG'=>'notice',
'ERRORH_CLASS'=>'error_notice',
'L_ERRORH'=>$errstr
));
break;

case E_USER_WARNING:
case E_WARNING:
$errstr=sprintf($LANG['error_warning_tiny'],$errstr,'','');
$this->template->assign_vars(array(
'C_ERROR_HANDLER'.strtoupper($tpl_cond)=>true,
'ERRORH_IMG'=>'important',
'ERRORH_CLASS'=>'error_warning',
'L_ERRORH'=>$errstr
));
break;

case E_USER_ERROR:
case E_ERROR:

$error_id=$this->_error_log($errfile,$errline,$errno,$errstr,true);

if($stop)
{
if(!$_err_stop)
{

header('Location:'.$this->redirect.'/member/fatal.php?error='.$error_id.'&_err_stop=1');
exit;
}
else
die($errstr);
}
}


if($this->personal_tpl)
$this->set_default_template();


if($archive)
return $this->_error_log($errfile,$errline,$errno,$errstr,$archive);
return true;
}
}










function display($errstr,$errno,$errline='',$errfile='',$archive=false)
{
global $LANG;


if(!empty($errstr))
{
$Template=new Template('framework/errors.tpl');
switch($errno)
{

case E_USER_SUCCESS:
$errstr=sprintf($LANG['error_success'],$errstr,'','');
$Template->assign_vars(array(
'ERRORH_IMG'=>'success',
'ERRORH_CLASS'=>'error_success',
'L_ERRORH'=>$errstr
));
break;

case E_USER_NOTICE:
case E_NOTICE:
$errstr=sprintf($LANG['error_notice_tiny'],$errstr,'','');
$Template->assign_vars(array(
'ERRORH_IMG'=>'notice',
'ERRORH_CLASS'=>'error_notice',
'L_ERRORH'=>$errstr
));
break;

case E_USER_WARNING:
case E_WARNING:
$errstr=sprintf($LANG['error_warning_tiny'],$errstr,'','');
$Template->assign_vars(array(
'ERRORH_IMG'=>'important',
'ERRORH_CLASS'=>'error_warning',
'L_ERRORH'=>$errstr
));
break;
}
return $Template->parse(TEMPLATE_STRING_MODE);


if($archive)
$this->_error_log($errfile,$errline,$errno,$errstr,$archive);
}
return '';
}




function set_template(&$template)
{
$this->template=&$template;
$this->personal_tpl=true;
}




function set_default_template()
{
global $Template;

$this->template=&$Template;
$this->personal_tpl=false;
}




function get_last__error_log()
{
$errinfo='';
$handle=@fopen(PATH_TO_ROOT.'/cache/error.log','r');
if($handle)
{
$i=1;
while(!feof($handle))
{
$buffer=fgets($handle);
if($i==2)
$errinfo['errno']=$buffer;
if($i==3)
$errinfo['errstr']=$buffer;
if($i==4)
$errinfo['errfile']=$buffer;
if($i==5)
{
$errinfo['errline']=$buffer;
$i=0;
}
$i++;
}
@fclose($handle);
}
return $errinfo;
}




function get_errno_class($errno)
{
switch($errno)
{

case E_USER_REDIRECT:
$class='error_fatal';
break;

case E_USER_NOTICE:
case E_NOTICE:
$class='error_notice';
break;

case E_USER_WARNING:
case E_WARNING:
$class='error_warning';
break;

case E_USER_ERROR:
case E_ERROR:
$class='error_fatal';
break;

default:
$class='error_unknow';
}
return $class;
}


## Private Methods ##



function _error_log($errfile,$errline,$errno,$errstr,$archive)
{
if($archive || $this->archive_all)
{

$errstr=$this->_clean_error_string($errstr);

$error=gmdate_format('Y-m-d H:i:s',time(),TIMEZONE_SYSTEM)."\n";
$error.=$errno."\n";
$error.=$errstr."\n";
$error.=basename($errfile)."\n";
$error.=$errline."\n";

$handle=@fopen(PATH_TO_ROOT.'/cache/error.log','a+');
@fwrite($handle,$error);
@fclose($handle);
return true;
}
return false;
}




function _clean_error_string($errstr)
{
$errstr=preg_replace("`\r|\n|\t`","\n",$errstr);
$errstr=preg_replace("`(\n){1,}`",'<br />',$errstr);
return $errstr;
}


## Private Attribute ##
var $archive_all;
var $redirect;
var $template;
var $personal_tpl=false;
}

?>
