<?php



























@ini_set('open_basedir',NULL);
@set_magic_quotes_runtime(0);

if(@ini_get('register_globals')=='1' || strtolower(@ini_get('register_globals'))=='on')
{
require_once(PATH_TO_ROOT.'/kernel/framework/util/unusual_functions.inc.php');
securit_register_globals();
}


if(get_magic_quotes_gpc())
{

if(ini_get('magic_quotes_sybase')&&(strtolower(ini_get('magic_quotes_sybase'))!="off"))
{

define('MAGIC_QUOTES',false);


foreach($_REQUEST as $var_name=>$value)
{
$_REQUEST[$var_name]=str_replace('\'\'','\'',$value);
}
}

else
{
define('MAGIC_QUOTES',true);
}
}
else
{
define('MAGIC_QUOTES',false);
}

### Définition des constantes utiles.###
define('GUEST_LEVEL',-1);
define('MEMBER_LEVEL',0);
define('MODO_LEVEL',1);
define('MODERATOR_LEVEL',1);
define('ADMIN_LEVEL',2);
define('SCRIPT',$_SERVER['PHP_SELF']);
define('QUERY_STRING',addslashes($_SERVER['QUERY_STRING']));
define('PHPBOOST',true);
define('ERROR_REPORTING',       E_ALL & ~E_STRICT);
define('E_TOKEN',-3);
define('E_USER_REDIRECT',-1);
define('E_USER_SUCCESS',-2);
define('HTML_UNPROTECT',false);

### Autorisations ###
define('AUTH_MENUS',0x01);
define('AUTH_FILES',0x01);
define('ACCESS_MODULE',0x01);
define('AUTH_FLOOD','auth_flood');
define('PM_GROUP_LIMIT','pm_group_limit');
define('DATA_GROUP_LIMIT','data_group_limit');


define('GET',1);
define('POST',2);
define('REQUEST',3);
define('COOKIE',4);
define('FILES',5);

define('TBOOL','boolean');
define('TINTEGER','integer');
define('TDOUBLE','double');
define('TFLOAT','double');
define('TSTRING','string');
define('TSTRING_PARSE','string_parse');
define('TSTRING_UNCHANGE','string_unsecure');
define('TSTRING_HTML','string_html');
define('TSTRING_AS_RECEIVED','string_unchanged');
define('TARRAY','array');
define('TUNSIGNED_INT','uint');
define('TUNSIGNED_DOUBLE','udouble');
define('TUNSIGNED_FLOAT','udouble');
define('TNONE','none');

define('USE_DEFAULT_IF_EMPTY',1);


if($_SERVER)
{
if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
{
$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
elseif(isset($_SERVER['HTTP_CLIENT_IP']))
{
$ip=$_SERVER['HTTP_CLIENT_IP'];
}
else
{
$ip=$_SERVER['REMOTE_ADDR'];
}
}
else
{
if(getenv('HTTP_X_FORWARDED_FOR'))
{
$ip=getenv('HTTP_X_FORWARDED_FOR');
}
elseif(getenv('HTTP_CLIENT_IP'))
{
$ip=getenv('HTTP_CLIENT_IP');
}
else
{
$ip=getenv('REMOTE_ADDR');
}
}
define('USER_IP',addslashes($ip));


define('REGEX_MULTIPLICITY_NOT_USED',0x01);
define('REGEX_MULTIPLICITY_OPTIONNAL',0x02);
define('REGEX_MULTIPLICITY_REQUIRED',0x03);
define('REGEX_MULTIPLICITY_AT_LEAST_ONE',0x04);
define('REGEX_MULTIPLICITY_ALL',0x05);

?>