<?php



























import('modules/module_interface');


class ShoutboxInterface extends ModuleInterface
{
    ## Public Methods ##
    function ShoutboxInterface() 
    {
        parent::ModuleInterface('shoutbox');
    }
    
    
	function get_cache()
	{
		global $Sql;
	
		$shoutbox_config = 'global $CONFIG_SHOUTBOX;' . "\n";
			
		
		$CONFIG_SHOUTBOX = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'shoutbox'", __LINE__, __FILE__));
		$CONFIG_SHOUTBOX = is_array($CONFIG_SHOUTBOX) ? $CONFIG_SHOUTBOX : array();
		
		if (isset($CONFIG_SHOUTBOX['shoutbox_forbidden_tags']))
			$CONFIG_SHOUTBOX['shoutbox_forbidden_tags'] = unserialize($CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
		
		$shoutbox_config .= '$CONFIG_SHOUTBOX = ' . var_export($CONFIG_SHOUTBOX, true) . ';' . "\n";
		
		return $shoutbox_config;
	}

	
	function on_changeday()
	{
		global $Sql, $Cache, $CONFIG_SHOUTBOX;
		
		$Cache->load('shoutbox'); 

		if ($CONFIG_SHOUTBOX['shoutbox_max_msg'] != -1)
		{
			
			$Sql->query_inject("SELECT @compt := id AS compt
			FROM " . PREFIX . "shoutbox
			ORDER BY id DESC
			" . $Sql->limit(0, $CONFIG_SHOUTBOX['shoutbox_max_msg']), __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id < @compt", __LINE__, __FILE__);
		}
	}
}

?>
