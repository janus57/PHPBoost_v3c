<?php


























if (defined('PHPBOOST') !== true) exit;


import('modules/module_interface');


class GuestbookInterface extends ModuleInterface
{
    ## Public Methods ##
    function GuestbookInterface() 
    {
        parent::ModuleInterface('guestbook');
    }
    
	
	function get_cache()
	{
		global $Sql;
	
		$guestbook_code = 'global $CONFIG_GUESTBOOK;' . "\n";
			
		
		$CONFIG_GUESTBOOK = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'guestbook'", __LINE__, __FILE__));
		$CONFIG_GUESTBOOK = is_array($CONFIG_GUESTBOOK) ? $CONFIG_GUESTBOOK : array();
		
		if (isset($CONFIG_GUESTBOOK['guestbook_forbidden_tags']))
			$CONFIG_GUESTBOOK['guestbook_forbidden_tags'] = unserialize($CONFIG_GUESTBOOK['guestbook_forbidden_tags']);
			
		$guestbook_code .= '$CONFIG_GUESTBOOK = ' . var_export($CONFIG_GUESTBOOK, true) . ';' . "\n";
		
		$guestbook_code .= "\n\n" . 'global $_guestbook_rand_msg;' . "\n";
		$guestbook_code .= "\n" . '$_guestbook_rand_msg = array();' . "\n";
		$result = $Sql->query_while("SELECT g.id, g.login, g.user_id, g.timestamp, m.login as mlogin, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		ORDER BY g.timestamp DESC
		" . $Sql->limit(0, 10), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$guestbook_code .= '$_guestbook_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'contents\' => ' . var_export(nl2br(substr_html(strip_tags(second_parse($row['contents'])), 0, 150)), true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ', \'login\' => ' . var_export($row['login'], true) . ');' . "\n";
		}
		$Sql->query_close($result);
		
		return $guestbook_code;
	}

	
	function on_changeday()
	{
		global $Cache;
		
		$Cache->Generate_module_file('guestbook');
	}
}

?>
