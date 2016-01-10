<?php




























define('AUTOCONNECT',true);
define('NO_AUTOCONNECT',false);
define('ALREADY_HASHED',true);
define('SEASURF_ATTACK_ERROR_PAGE',PATH_TO_ROOT.'/member/csrf-attack.php');






class Session
{
## Public Attribute ##
var $data=array();
var $session_mod=0;
var $autoconnect=array();

## Public Methods ##



function act()
{
global $Session,$Sql;


$login=retrieve(POST,'login','');
$password=retrieve(POST,'password','',TSTRING_UNCHANGE);
$autoconnexion=retrieve(POST,'auto',false);

if(retrieve(GET,'disconnect',false))
{

$this->csrf_get_protect();

$this->end();
redirect(get_start_page());
}
elseif(retrieve(POST,'connect',false)&&!empty($login)&&!empty($password))
{
$user_id=$Sql->query("SELECT user_id FROM ".DB_TABLE_MEMBER." WHERE login = '".$login."'",__LINE__,__FILE__);
if(!empty($user_id))
{
$info_connect=$Sql->query_array(DB_TABLE_MEMBER,'level','user_warning','last_connect','test_connect','user_ban','user_aprob',"WHERE user_id='".$user_id."'",__LINE__,__FILE__);
$delay_connect=(time()-$info_connect['last_connect']);
$delay_ban=(time()-$info_connect['user_ban']);

if($delay_ban>=0&&$info_connect['user_aprob']=='1'&&$info_connect['user_warning']<'100')
{
if($delay_connect>=600)
{
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect='".time()."', test_connect = 0 WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$error_report=$this->start($user_id,$password,$info_connect['level'],SCRIPT,QUERY_STRING,'',$autoconnexion);
}
elseif($delay_connect>=300)
{
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect='".time()."', test_connect = 3 WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
$error_report=$this->start($user_id,$password,$info_connect['level'],SCRIPT,QUERY_STRING,'',$autoconnexion);
}
elseif($info_connect['test_connect']<5)
$error_report=$this->start($user_id,$password,$info_connect['level'],SCRIPT,QUERY_STRING,'',$autoconnexion);
else
redirect(HOST.DIR.'/member/error.php?e=e_member_flood#errorh');
}
elseif($info_connect['user_aprob']=='0')
redirect(HOST.DIR.'/member/error.php?e=e_unactiv_member#errorh');
elseif($info_connect['user_warning']=='100')
redirect(HOST.DIR.'/member/error.php?e=e_member_ban_w#errorh');
else
{
$delay_ban=ceil((0-$delay_ban)/60);
redirect(HOST.DIR.'/member/error.php?e=e_member_ban&ban='.$delay_ban.'#errorh');
}

if(!empty($error_report))
{
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect='".time()."', test_connect = test_connect + 1 WHERE user_id='".$user_id."'",__LINE__,__FILE__);
$info_connect['test_connect']++;
$info_connect['test_connect']=5-$info_connect['test_connect'];
redirect(HOST.DIR.'/member/error.php?e=e_member_flood&flood='.$info_connect['test_connect'].'#errorh');
}
elseif($info_connect['test_connect']>0)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect='".time()."', test_connect = 0 WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
}
else
redirect(HOST.DIR.'/member/error.php?e=e_unexist_member#errorh');

$query_string=QUERY_STRING;
$query_string=!empty($query_string)?'?'.QUERY_STRING.'&sid='.$this->data['session_id'].'&suid='.$this->data['user_id']:'?sid='.$this->data['session_id'].'&suid='.$this->data['user_id'];


if(SCRIPT!=DIR.'/member/error.php')
redirect(HOST.SCRIPT.$query_string);
else
redirect(get_start_page());
}
}












function start($user_id,$password,$level,$session_script,$session_script_get,$session_script_title,$autoconnect=false,$already_hashed=false)
{
global $CONFIG,$Sql;

$pwd=$password;
if(!$already_hashed)
$password=strhash($password);

$error='';
$session_script=addslashes($session_script);
$session_script_title=addslashes($session_script_title);
$session_script_get=preg_replace('`&token=[^&]+`','',QUERY_STRING);

########Insertion dans le compteur si l'ip est inconnue.########
		$check_ip = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_VISIT_COUNTER . " WHERE ip = '" . USER_IP . "'", __LINE__, __FILE__);
		$_include_once = empty($check_ip) && (Session::_check_bot(USER_IP) === false);
		if ($_include_once)
		{
			//Récupération forcée de la valeur du total de visites, car problème de CAST avec postgresql.
			$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_VISIT_COUNTER . " SET ip = ip + 1, time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', total = total + 1 WHERE id = 1", __LINE__, __FILE__);
			$Sql->query_inject("INSERT ".LOW_PRIORITY." INTO " . DB_TABLE_VISIT_COUNTER . " (ip, time, total) VALUES('" . USER_IP . "', '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', 0)", __LINE__, __FILE__);
			
			//Mise à jour du last_connect, pour un membre qui vient d'arriver sur le site.
if($user_id!=='-1')
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect = '".time()."' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
}


import('core/stats_saver');
StatsSaver::compute_referer();
if($_include_once)
StatsSaver::compute_users();

########Génération d'un ID de session unique########
		$session_uniq_id = strhash(uniqid(mt_rand(), true)); //On génère un numéro de session aléatoire.
		$this->data['user_id'] = $user_id;
        $this->data['session_id'] = $session_uniq_id;
        $this->data['token'] = strhash(uniqid(mt_rand(), true), false);
		
		########Session existe t-elle?#########
		Session::garbage_collector(); //On nettoie avant les sessions périmées.

		if ($user_id !== '-1')
		{
			//Suppression de la session visiteur générée avant l'enregistrement!
$Sql->query_inject("DELETE FROM ".DB_TABLE_SESSIONS." WHERE session_ip = '".USER_IP."' AND user_id = -1",__LINE__,__FILE__);


if(isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
setcookie($CONFIG['site_cookie'].'_data','',time()-31536000,'/');
$Sql->query_inject("DELETE FROM ".DB_TABLE_SESSIONS." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);


$password_m=$Sql->query("SELECT password FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$user_id."' AND user_warning < 100 AND '".time()."' - user_ban >= 0",__LINE__,__FILE__);
if(!empty($password)&&(($password===$password_m)||(md5($pwd)===$password_m)))
{
if(md5($pwd)===$password_m)
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET password = '".$password."' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);

$Sql->query_inject("INSERT INTO ".DB_TABLE_SESSIONS." VALUES('".$session_uniq_id."', '".$user_id."', '".$level."', '".USER_IP."', '".time()."', '".$session_script."', '".$session_script_get."', '".$session_script_title."', '0', '', '', '', '".$this->data['token']."')",__LINE__,__FILE__);
}
else
{
$Sql->query_inject("INSERT INTO ".DB_TABLE_SESSIONS." VALUES('".$session_uniq_id."', -1, -1, '".USER_IP."', '".time()."', '".$session_script."', '".$session_script_get."', '".$session_script_title."', '0', '', '', '', '".$this->data['token']."')",__LINE__,__FILE__);

$delay_ban=$Sql->query("SELECT user_ban FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
if((time()-$delay_ban)>=0)
$error='echec';
else
$error=$delay_ban;
}
}
else
$Sql->query_inject("INSERT INTO ".DB_TABLE_SESSIONS." VALUES('".$session_uniq_id."', -1, -1, '".USER_IP."', '".time()."', '".$session_script."', '".$session_script_get."', '".$session_script_title."', '0', '', '', '', '".$this->data['token']."')",__LINE__,__FILE__);

########Génération du cookie de session########
$data=array();
$data['user_id']=isset($user_id)?numeric($user_id):-1;
$data['session_id']=$session_uniq_id;

setcookie($CONFIG['site_cookie'].'_data',serialize($data),time()+31536000,'/');

########Génération du cookie d'autoconnection########
		if ($autoconnect === true)
		{
			$session_autoconnect['user_id'] = $user_id;
			$session_autoconnect['pwd'] = $password;
			
			setcookie($CONFIG['site_cookie'].'_autoconnect', serialize($session_autoconnect), time() + 31536000, '/');
		}
		
		unset($pwd);
		return $error;
	}
	
	/**
	 * @desc Get informations from the user, and set it for his session.
	 */
	function load()
	{
		global $Sql, $CONFIG;
		
		$this->_get_id(); //Récupération des identifiants de session.
		
		########Valeurs à retourner########
		$userdata = array();
		if ($this->data['user_id'] > 0 && !empty($this->data['session_id']))
		{
			//Récupère également les champs membres supplémentaires
			$result = $Sql->query_while("SELECT m.user_id AS m_user_id, m.login, m.level, m.user_groups, m.user_lang, m.user_theme, m.user_mail, m.user_pm, m.user_editor, m.user_timezone, m.user_avatar avatar, m.user_readonly, s.modules_parameters, s.token AS token, me.*
			FROM " . DB_TABLE_MEMBER . " m
            JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = '" . $this->data['user_id'] . "' AND s.session_id = '" . $this->data['session_id'] . "'
			LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " me ON me.user_id = '" . $this->data['user_id'] . "'
			WHERE m.user_id = '" . $this->data['user_id'] . "'", __LINE__, __FILE__);
			$userdata = $Sql->fetch_assoc($result);
         
			if (!empty($userdata)) //Succès.
			{
				$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
			}
			elseif ($this->session_mod == 0) //Aucune entrée associée dans la base de donnée, on tente une connexion auto.
			{
				$this->autoconnect['user_id'] = $this->data['user_id'];
				$this->autoconnect['session_id'] = $this->data['session_id'];
			}
		}
		else
		{
			//Récupère également les champs membres supplémentaires
			$result = $Sql->query_while("SELECT modules_parameters, user_theme, user_lang
			FROM " . DB_TABLE_SESSIONS . "
			WHERE user_id = '-1' AND session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);
			$userdata = $Sql->fetch_assoc($result);
			
			if (!empty($userdata)) //Succès.
				$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
		}
		
        $this->data['user_id'] = isset($userdata['m_user_id']) ? (int)$userdata['m_user_id'] : -1;
        $this->data['token'] = isset($userdata['token']) ? $userdata['token'] : '';
		$this->data['login'] = isset($userdata['login']) ? $userdata['login'] : '';
		$this->data['level'] = isset($userdata['level']) ? (int)$userdata['level'] : -1;
		$this->data['user_groups'] = isset($userdata['user_groups']) ? $userdata['user_groups'] : '';
		$this->data['user_lang'] = !empty($userdata['user_lang']) ? $userdata['user_lang'] : $CONFIG['lang']; //Langue membre
		$this->data['user_theme'] = !empty($userdata['user_theme']) ? $userdata['user_theme'] : $CONFIG['theme']; //Thème membre
		$this->data['user_mail'] = isset($userdata['user_mail']) ? $userdata['user_mail'] : '';
		$this->data['user_pm'] = isset($userdata['user_pm']) ? $userdata['user_pm'] : '0';
		$this->data['user_readonly'] = isset($userdata['user_readonly']) ? $userdata['user_readonly'] : '0';
		$this->data['user_editor'] = !empty($userdata['user_editor']) ? $userdata['user_editor'] : $CONFIG['editor'];
		$this->data['user_timezone'] = isset($userdata['user_timezone']) ? $userdata['user_timezone'] : $CONFIG['timezone'];
		$this->data['avatar'] = isset($userdata['avatar']) ? $userdata['avatar'] : '';
		$this->data['modules_parameters'] = isset($userdata['modules_parameters']) ? $userdata['modules_parameters'] : '';
	}
	
	/**
	 * @desc Check session validity, and update it
	 * @param string $session_script_title The page title where the session has been check.
	 */
	function check($session_script_title)
	{
		global $CONFIG, $Sql;

		$session_script = preg_replace('`^' . preg_quote(DIR) . '`', '', SCRIPT);
		$session_script_get = preg_replace('`&token=[^&]+`', '', QUERY_STRING);
		$check_autoconnect = (!empty($this->autoconnect['session_id']) && $this->autoconnect['user_id'] > 0);
		if ((!empty($this->data['session_id']) && $this->data['user_id'] > 0) || $check_autoconnect)
		{
			if (!$check_autoconnect) //Mode de connexion directe par le formulaire.
			{
				$this->autoconnect['session_id'] = $this->data['session_id'];
				$this->autoconnect['user_id'] = $this->data['user_id'];
			}
				
			//Localisation du membre.
			if (!defined('NO_SESSION_LOCATION'))
				$location = " session_script = '" . addslashes($session_script) . "', session_script_get = '" . addslashes($session_script_get) . "', session_script_title = '" . addslashes($session_script_title) . "', ";
			else
				$location = '';
			
			//On modifie le session_flag pour forcer mysql à modifier l'entrée,pour prendre en compte la mise à jour par mysql_affected_rows().
$resource=$Sql->query_inject("UPDATE ".LOW_PRIORITY." ".DB_TABLE_SESSIONS." SET session_ip = '".USER_IP."', session_time = '".time()."', ".$location." session_flag = 1 - session_flag WHERE session_id = '".$this->autoconnect['session_id']."' AND user_id = '".$this->autoconnect['user_id']."'",__LINE__,__FILE__);
if($Sql->affected_rows($resource,"SELECT COUNT(*) FROM ".DB_TABLE_SESSIONS." WHERE session_id = '".$this->autoconnect['session_id']."' AND user_id = '".$this->autoconnect['user_id']."'")==0)
{
if($this->_autoconnect($session_script,$session_script_get,$session_script_title)===false)
{
if(isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
setcookie($CONFIG['site_cookie'].'_data','',time()-31536000,'/');


if(QUERY_STRING!='')
redirect(HOST.SCRIPT.'?'.QUERY_STRING);
else
redirect(HOST.SCRIPT);
}
}
}
else
{

if(!defined('NO_SESSION_LOCATION'))
$location=" session_script = '".addslashes($session_script)."', session_script_get = '".addslashes($session_script_get)."', session_script_title = '".addslashes($session_script_title)."', ";
else
$location='';


$resource=$Sql->query_inject("UPDATE ".LOW_PRIORITY." ".DB_TABLE_SESSIONS." SET session_ip = '".USER_IP."', session_time = '".(time()+1)."', ".$location." session_flag = 1 - session_flag WHERE user_id = -1 AND session_ip = '".USER_IP."'",__LINE__,__FILE__);
if($Sql->affected_rows($resource,"SELECT COUNT(*) FROM ".DB_TABLE_SESSIONS." WHERE user_id = -1 AND session_ip = '".USER_IP."'")==0)
{
if(isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
setcookie($CONFIG['site_cookie'].'_data','',time()-31536000,'/');
$this->start('-1','','-1',$session_script,$session_script_get,$session_script_title,false,ALREADY_HASHED);
}
}
}




function end()
{
global $CONFIG,$Sql;

$this->_get_id();


$Sql->query_inject("DELETE FROM ".DB_TABLE_SESSIONS." WHERE session_id = '".$this->data['session_id']."'",__LINE__,__FILE__);

if(isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
setcookie($CONFIG['site_cookie'].'_data','',time()-31536000,'/');

if(isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']))
setcookie($CONFIG['site_cookie'].'_autoconnect','',time()-31536000,'/');

Session::garbage_collector();
}





function set_module_parameters($parameters,$module='')
{
global $Sql;


if(empty($this->data['user_id'])||!is_numeric($this->data['user_id']))
return false;

if(empty($module)||!is_string($module))
$module=MODULE_NAME;

$this->data['modules_parameters']=$Sql->query("SELECT modules_parameters FROM ".DB_TABLE_SESSIONS." WHERE user_id = '".(int)$this->data['user_id']."'",__LINE__,__FILE__);
if($this->data['modules_parameters']!==false)
{
$modules_parameters=unserialize($this->data['modules_parameters']);
$modules_parameters[$module]=$parameters;

$this->data['modules_parameters']=serialize($modules_parameters);

$Sql->query_inject("UPDATE ".DB_TABLE_SESSIONS." SET modules_parameters = '".
strprotect($this->data['modules_parameters'],false).
"' WHERE user_id = '".(int)$this->data['user_id']."'",__LINE__,__FILE__);
}
else
{
$this->data['modules_parameters']='';
}
}






function get_module_parameters($module='')
{
global $Sql;

if(empty($this->data['user_id'])||!is_numeric($this->data['user_id']))
return false;

if(empty($module)||!is_string($module))
$module=MODULE_NAME;

$this->data['modules_parameters']=$Sql->query("SELECT modules_parameters FROM ".DB_TABLE_SESSIONS." WHERE user_id = '".(int)$this->data['user_id']."'",__LINE__,__FILE__);
if($this->data['modules_parameters']!=false)
{
$array=unserialize($this->data['modules_parameters']);
}
else
{
$this->data['modules_parameters']='';
}

return isset($array[$module])?$array[$module]:'';
}

## Private Méthods ##



function _get_id()
{
global $CONFIG,$Sql;


$this->data=array();

$this->data['session_id']='';
$this->data['user_id']=-1;
$this->autoconnect['session_id']='';
$this->autoconnect['user_id']=-1;

$this->session_mod=0;
$sid=retrieve(GET,'sid','');
$suid=retrieve(GET,'suid',0);
########Cookie Existe?########
if(isset($_COOKIE[$CONFIG['site_cookie'].'_data']))
{

if(isset($_GET['sid'])&&isset($_GET['suid']))
{
$query_string=preg_replace('`&?sid=(.*)&suid=(.*)`','',QUERY_STRING);
redirect(HOST.SCRIPT.(!empty($query_string)?'?'.$query_string:''));
}
$session_data=unserialize(retrieve(COOKIE,$CONFIG['site_cookie'].'_data','',TSTRING_UNCHANGE));
if($session_data===false)
{
$session_data=array();
}

$this->data['session_id']=isset($session_data['session_id'])?strprotect($session_data['session_id']):'';
$this->data['user_id']=isset($session_data['user_id'])?numeric($session_data['user_id']):'';
}
########SID Existe?########
elseif(!empty($sid)&&$suid>0)
{
$this->data['session_id']=$sid;
$this->data['user_id']=$suid;
$this->session_mod=1;
}
}







function _autoconnect($session_script,$session_script_get,$session_script_title)
{
global $CONFIG,$Sql;

########Cookie Existe?########
if(isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']))
{
$session_autoconnect=unserialize(retrieve(COOKIE,$CONFIG['site_cookie'].'_autoconnect','',TSTRING_UNCHANGE));
if($session_autoconnect===false)
{
$session_autoconnect=array();
}
$session_autoconnect['user_id']=!empty($session_autoconnect['user_id'])?numeric($session_autoconnect['user_id']):'';
$session_autoconnect['pwd']=!empty($session_autoconnect['pwd'])?strprotect($session_autoconnect['pwd']):'';
$level=$Sql->query("SELECT level FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$session_autoconnect['user_id']."' AND password = '".$session_autoconnect['pwd']."'",__LINE__,__FILE__);
if(!empty($session_autoconnect['user_id'])&&!empty($session_autoconnect['pwd'])&&$level!='')
{
$error_report=$this->start($session_autoconnect['user_id'],$session_autoconnect['pwd'],$level,$session_script,$session_script_get,$session_script_title,true,ALREADY_HASHED);


if($error_report==='echec')
{
$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect='".time()."', test_connect = test_connect + 1 WHERE user_id='".$session_autoconnect['user_id']."'",__LINE__,__FILE__);

$test_connect=$Sql->query("SELECT test_connect FROM ".DB_TABLE_MEMBER." WHERE user_id = '".$session_autoconnect['user_id']."'",__LINE__,__FILE__);

setcookie($CONFIG['site_cookie'].'_autoconnect','',time()-31536000,'/');

redirect(HOST.DIR.'/member/error.php?flood='.(5-($test_connect+1)));
}
elseif(is_numeric($error_report))
{
setcookie($CONFIG['site_cookie'].'_autoconnect','',time()-31536000,'/');

$error_report=ceil($error_report/60);
redirect(HOST.DIR.'/member/error.php?ban='.$error_report);
}
else
{

$Sql->query_inject("UPDATE ".DB_TABLE_MEMBER." SET last_connect = '".time()."' WHERE user_id = '".$session_autoconnect['user_id']."'",__LINE__,__FILE__);

if(QUERY_STRING!='')
redirect(HOST.SCRIPT.'?'.QUERY_STRING);
else
redirect(HOST.SCRIPT);
}
}
else
return false;
}
return false;
}





function garbage_collector()
{
global $CONFIG,$Sql;

$Sql->query_inject("DELETE
		FROM ".DB_TABLE_SESSIONS."
		WHERE session_time < '".(time()-$CONFIG['site_session'])."'
		OR (session_time < '".(time()-$CONFIG['site_session_invit'])."' AND user_id = -1)",__LINE__,__FILE__);
}







function _check_bot($user_ip)
{
$_SERVER['HTTP_USER_AGENT']=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';

if(preg_match('`(w3c|http:\/\/|bot|spider|Gigabot|gigablast.com)+`i',$_SERVER['HTTP_USER_AGENT']))
return 'unknow_bot';


$plage_ip=array(
'66.249.64.0'=>'66.249.95.255',
'209.85.128.0'=>'209.85.255.255',
'65.52.0.0'=>'65.55.255.255',
'207.68.128.0'=>'207.68.207.255',
'66.196.64.0'=>'66.196.127.255',
'68.142.192.0'=>'68.142.255.255',
'72.30.0.0'=>'72.30.255.255',
'193.252.148.0'=>'193.252.148.255',
'66.154.102.0'=>'66.154.103.255',
'209.237.237.0'=>'209.237.238.255',
'193.47.80.0'=>'193.47.80.255'
);


$array_robots=array(
'Google bot',
'Google bot',
'Msn bot',
'Msn bot',
'Yahoo Slurp',
'Yahoo Slurp',
'Yahoo Slurp',
'Voila',
'Gigablast',
'Ia archiver',
'Exalead'
);


$user_ip=ip2long($user_ip);


$r=0;
foreach($plage_ip as $start_ip=>$end_ip)
{
$start_ip=ip2long($start_ip);
$end_ip=ip2long($end_ip);


if($user_ip>=$start_ip&&$user_ip<=$end_ip)
{

$file_path=PATH_TO_ROOT.'/cache/robots.txt';
if(!file_exists($file_path))
{
$file=@fopen($file_path,'w+');
@fwrite($file,serialize(array()));
@fclose($file);
}

if(is_file($file_path)&&is_writable($file_path))
{
$robot=$array_robots[$r];
$time=gmdate_format('YmdHis',time(),TIMEZONE_SYSTEM);

$line=file($file_path);
$data=unserialize($line[0]);

if(!isset($data[$robot]))
$data[$robot]=$robot.'/1/'.$time;

$array_info=explode('/',$data[$robot]);
if($array_robots[$r]===$array_info[0])
{
$array_info[1]++;
$array_info[2]=$time;
$data[$robot]=implode('/',$array_info);
}
else
$data[$robot]=$robot.'/1/'.$time;

$file=@fopen($file_path,'r+');
fwrite($file,serialize($data));
fclose($file);
}
return $array_robots[$r];
}
$r++;
}
return false;
}





function get_token()
{
if(empty($this->data['token']))
{
$this->data['token']=strhash(uniqid(mt_rand(),true),false);
global $Sql;
$Sql->query_inject("UPDATE ".DB_TABLE_SESSIONS." SET token='".$this->data['token']."' WHERE session_id='".$this->data['session_id']."'",__LINE__,__FILE__);

}
return $this->data['token'];
}













function csrf_post_protect($redirect=SEASURF_ATTACK_ERROR_PAGE)
{

if(!empty($_POST))
{

$token=$this->get_token();
if(!empty($token)&&retrieve(REQUEST,'token','')===$token)
{
return true;
}

if(Session::_check_referer())
{
return true;
}

Session::_csrf_attack($redirect);
return false;
}

else
{
return true;
}
}








function csrf_get_protect($redirect=SEASURF_ATTACK_ERROR_PAGE)
{
$token=$this->get_token();
if(empty($token)|| retrieve(GET,'token','')!==$token)
{
Session::_csrf_attack($redirect);
return false;
}
return true;
}






function _check_referer()
{
global $CONFIG;
if(empty($_SERVER['HTTP_REFERER']))
return false;
return strpos($_SERVER['HTTP_REFERER'],trim(trim($CONFIG['server_name'],'/').$CONFIG['server_path'],'/'))===0;
}








function _csrf_attack($redirect=SEASURF_ATTACK_ERROR_PAGE)
{
global $Errorh;
$Errorh->handler('e_token',E_TOKEN);
if($redirect!==false&&!empty($redirect))
redirect($redirect);
}
}

?>
