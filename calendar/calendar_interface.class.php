<?php


























if (defined('PHPBOOST') !== true) exit;


import('modules/module_interface');


class CalendarInterface extends ModuleInterface
{
    ## Public Methods ##
    function CalendarInterface() 
    {
        parent::ModuleInterface('calendar');
    }
    
	
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $CONFIG_CALENDAR;' . "\n";
			
		
		$CONFIG_CALENDAR = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'calendar'", __LINE__, __FILE__));
		$CONFIG_CALENDAR = is_array($CONFIG_CALENDAR) ? $CONFIG_CALENDAR : array();
		
		$code .= '$CONFIG_CALENDAR = ' . var_export($CONFIG_CALENDAR, true) . ';' . "\n";
		
		return $code;
	}

	
	




}

?>
