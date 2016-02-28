<?php


























if (defined('PHPBOOST') !== true)
    exit;


define('FLOOD_FORUM', 0x01);
define('EDIT_MARK_FORUM', 0x02);
define('TRACK_TOPIC_FORUM', 0x04);


define('READ_CAT_FORUM', 0x01);
define('WRITE_CAT_FORUM', 0x02);
define('EDIT_CAT_FORUM', 0x04);


define('H_DELETE_MSG', 'delete_msg'); 
define('H_DELETE_TOPIC', 'delete_topic'); 
define('H_LOCK_TOPIC', 'lock_topic'); 
define('H_UNLOCK_TOPIC', 'unlock_topic'); 
define('H_MOVE_TOPIC', 'move_topic'); 
define('H_CUT_TOPIC', 'cut_topic'); 
define('H_SET_WARNING_USER', 'set_warning_user'); 
define('H_BAN_USER', 'ban_user'); 
define('H_EDIT_MSG', 'edit_msg'); 
define('H_EDIT_TOPIC', 'edit_topic'); 
define('H_SOLVE_ALERT', 'solve_alert'); 
define('H_WAIT_ALERT', 'wait_alert'); 
define('H_DEL_ALERT', 'del_alert'); 
define('H_READONLY_USER', 'readonly_user'); 


?>
