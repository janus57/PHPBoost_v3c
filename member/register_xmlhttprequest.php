<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

$login = substr(retrieve(POST, 'login', ''), 0, 25);
$email = retrieve(POST, 'mail', '');

if (!empty($login) && !empty($email))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $email . "' AND login <> '" . $login . "'", __LINE__, __FILE__);
elseif (!empty($login))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
elseif (!empty($email))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $email . "'", __LINE__, __FILE__);
else
	echo -1;
	
$Sql->close(); 

?>
