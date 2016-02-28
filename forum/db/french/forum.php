<?php


























if (defined('PHPBOOST') !== true) exit;

$user_id = (isset($Session) && is_object($Session) && $User->get_attribute('user_id') != '') ? $User->get_attribute('user_id') : 1;
$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET user_id = '" . $user_id . "', last_user_id = '" . $user_id . "' WHERE id = '" . $user_id . "'", __LINE__, __FILE__);
$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET user_ip = '" . USER_IP . "' WHERE id = '" . $user_id . "'", __LINE__, __FILE__);
$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_msg = user_msg + 1 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);

	
?>
