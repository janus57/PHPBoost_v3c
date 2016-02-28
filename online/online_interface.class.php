<?php



























import('modules/module_interface');


class OnlineInterface extends ModuleInterface
{
    ## Public Methods ##
    function OnlineInterface() 
    {
        parent::ModuleInterface('online');
    }
    
    
	function get_cache()
	{
		global $Sql;
			
		$online_config = 'global $CONFIG_ONLINE;' . "\n";
		
		
		$CONFIG_ONLINE = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'online'", __LINE__, __FILE__));
		$CONFIG_ONLINE = is_array($CONFIG_ONLINE) ? $CONFIG_ONLINE : array();
		
		$online_config .= '$CONFIG_ONLINE = ' . var_export($CONFIG_ONLINE, true) . ';' . "\n";
		
		return $online_config;	
	}

	
	




}

?>
