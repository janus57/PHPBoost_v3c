<?php


























if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('calendar'); 
define('TITLE', $LANG['title_calendar']);
$Cache->load('calendar');


define('ALTERNATIVE_CSS', 'calendar');

?>
