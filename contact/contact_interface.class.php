<?php



























import('modules/module_interface');


class ContactInterface extends ModuleInterface
{
    ## Public Methods ##
    function ContactInterface() 
    {
        parent::ModuleInterface('contact');
    }
    
    
	function get_cache()
	{
		global $Sql;
	
		$contact_config = 'global $CONFIG_CONTACT;' . "\n";
			
		
		$CONFIG_CONTACT = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'contact'", __LINE__, __FILE__));
		$CONFIG_CONTACT = is_array($CONFIG_CONTACT) ? $CONFIG_CONTACT : array();
		
		$contact_config .= '$CONFIG_CONTACT = ' . var_export($CONFIG_CONTACT, true) . ';' . "\n";
		
		return $contact_config;	
	}

	
	




}

?>
