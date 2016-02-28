<?php


























if (defined('PHPBOOST') !== true)	
    exit;

require_once('../forum/forum_init_auth_cats.php');


if ($CONFIG_FORUM['no_left_column'] == 1) 
    define('NO_LEFT_COLUMN', true);
if ($CONFIG_FORUM['no_right_column'] == 1) 
    define('NO_RIGHT_COLUMN', true);


define('ALTERNATIVE_CSS', 'forum');


require_once('../forum/forum_functions.php');

?>
