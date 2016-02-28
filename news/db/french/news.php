<?php


























if (defined('PHPBOOST') !== true) exit;

$user_id = (isset($Session) && is_object($Session) && $User->get_attribute('user_id') != '') ? $User->get_attribute('user_id') : 1;
$Sql->query_inject("UPDATE " . PREFIX . "news SET user_id = '" . $user_id . "' WHERE id = 1", __LINE__, __FILE__);
	
?>
