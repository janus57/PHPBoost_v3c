<?php
header('Content-type: text/html; charset=iso-8859-15');

define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php'); //Fonctions de base.
require_once(PATH_TO_ROOT . '/kernel/constant.php'); //Constante utiles.

@error_reporting(ERROR_REPORTING);

$lang = !empty($_GET['lang']) ? trim($_GET['lang']) : 'french';
if (!@include_once('lang/' . $lang . '/install_' . $lang . '.php'))
	include_once('lang/french/install_french.php');
$chmod = retrieve(GET, 'chmod', false);
$db = retrieve(GET, 'db', false);

if ($chmod)
{
	//Mise � jour du cache.
	@clearstatcache();
	
	$chmod_dir = array('../cache', '../cache/backup', '../cache/syndication', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../kernel/db', '../lang', '../menus', '../templates', '../upload');
	
	//V�rifications et le cas �ch�ants changements des autorisations en �criture.
	foreach ($chmod_dir as $dir)
	{
		$is_writable = $is_dir = true;
		if (file_exists($dir) && is_dir($dir))
		{
			if (!is_writable($dir))
				$is_writable = (@chmod($dir, 0777)) ? true : false;			
		}
		else
			$is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;
		$found = ($is_dir === true) ? '<div class="success_block">' . $LANG['existing'] . '</div>' : '<div class="failure_block">' . $LANG['unexisting'] . '</div>';
		$writable = ($is_writable === true) ? '<div class="success_block">' . $LANG['writable'] . '</div>' : '<div class="failure_block">' . $LANG['unwritable'] . '</div>';
		
		echo '<dl>
			<dt><label>' . str_replace('..' , '', $dir) . '</label></dt>
			<dd>
				' . $found . '
				' . $writable . '
			</dd>								
		</dl>';
	}

}
elseif ($db)
{
	//Assignation des variables et erreurs
	$host = retrieve(POST, 'host', 'localhost');
	$login = retrieve(POST, 'login', '');
	$password = retrieve(POST, 'password', '');
	$database = retrieve(POST, 'database', '');
	$tables_prefix = str_replace('.', '_', retrieve(POST, 'prefix', 'phpboost_'));
	
	include_once('functions.php');
	
	if (!empty($host) && !empty($login) && !empty($database))
		echo check_database_config($host, $login, $password, $database, $tables_prefix);
	else
		echo DB_UNKNOW_ERROR;
}

?>