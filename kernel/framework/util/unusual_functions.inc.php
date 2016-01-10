<?php




































function securit_register_globals()
{
$not_unset=array(
'GLOBALS'=>true,
'_GET'=>true,
'_POST'=>true,
'_COOKIE'=>true,
'_REQUEST'=>true,
'_SERVER'=>true,
'_SESSION'=>true,
'_ENV'=>true,
'_FILES'=>true
);


$input=array_merge(
array_keys($_GET),
array_keys($_POST),
array_keys($_COOKIE),
array_keys($_SERVER),
array_keys($_ENV),
array_keys($_FILES)
);

foreach($input as $varname)
{
if(isset($not_unset[$varname]))
{

if($varname!=='GLOBALS' || isset($_GET['GLOBALS'])|| isset($_POST['GLOBALS'])|| isset($_SERVER['GLOBALS'])|| isset($_SESSION['GLOBALS'])|| isset($_ENV['GLOBALS'])|| isset($_FILES['GLOBALS']))
exit;
else
{
$cookie=&$_COOKIE;
while(isset($cookie['GLOBALS']))
{
foreach($cookie['GLOBALS']as $registered_var=>$value)
{
if(!isset($not_unset[$registered_var]))
unset($GLOBALS[$registered_var]);
}
$cookie=&$cookie['GLOBALS'];
}
}
}
unset($GLOBALS[$varname]);
}
unset($input);
}






function get_server_url_page($path)
{
$server_name=!empty($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:getenv('HTTP_HOST');
$server_path=!empty($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:getenv('PHP_SELF');
if(!$server_path)
$server_path=!empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:getenv('REQUEST_URI');

$server_path=rtrim($server_path,'/');
$real_path=substr($server_path,0,strrpos($server_path,'/')).'/'.$path;

return 'http://'.$server_name.$real_path;
}

?>