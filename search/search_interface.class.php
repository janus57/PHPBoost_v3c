<?php



























import('modules/module_interface');


class SearchInterface extends ModuleInterface
{
    ## Public Methods ##
    function SearchInterface() 
    {
        parent::ModuleInterface('search');
    }
    
    
	function get_cache()
	{
		global $Sql;
		    
		
		$search_config = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'search'", __LINE__, __FILE__));
		
		return 'global $SEARCH_CONFIG;' . "\n" . '$SEARCH_CONFIG = '.var_export($search_config, true).';';	
	}

	
	function on_changeday()
	{
		global $Sql;
		
		
		$Sql->query_inject("TRUNCATE " . PREFIX . "search_results", __LINE__, __FILE__);
		$Sql->query_inject("TRUNCATE " . PREFIX . "search_index", __LINE__, __FILE__);
	}
}

?>
