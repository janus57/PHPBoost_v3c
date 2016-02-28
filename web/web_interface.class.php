<?php



























import('modules/module_interface');


class WebInterface extends ModuleInterface
{
    ## Public Methods ##
    function WebInterface() 
    {
        parent::ModuleInterface('web');
    }
    
    
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $CAT_WEB;' . "\n" . 'global $CONFIG_WEB;' . "\n";
			
		
		$CONFIG_WEB = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'web'", __LINE__, __FILE__));
		$CONFIG_WEB = is_array($CONFIG_WEB) ? $CONFIG_WEB : array();
		
		$code .= '$CONFIG_WEB = ' . var_export($CONFIG_WEB, true) . ';' . "\n";
		$code .= "\n";
		
		$result = $Sql->query_while("SELECT id, name, secure
		FROM " . PREFIX . "web_cat
		WHERE aprob = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{		
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n";
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
		}
		
		return $code;	
	}

	
	




}

?>
