<?php



























import('modules/module_interface');


class NewsletterInterface extends ModuleInterface
{
    ## Public Methods ##
    function NewsletterInterface() 
    {
        parent::ModuleInterface('newsletter');
    }
    
    
	function get_cache()
	{
		global $Sql, $CONFIG;
		
		
		$code = 'global $_NEWSLETTER_CONFIG;' . "\n" . '$_NEWSLETTER_CONFIG = array();' . "\n";
		$NEWSLETTER_CONFIG = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'newsletter'", __LINE__, __FILE__));
		if (is_array($NEWSLETTER_CONFIG))
		{
			$mails = explode(';', $CONFIG['mail']);
			$code .= '$_NEWSLETTER_CONFIG[\'sender_mail\'] = ' . var_export(!empty($NEWSLETTER_CONFIG['sender_mail']) ? $NEWSLETTER_CONFIG['sender_mail'] : $mails[0], true) . ';' . "\n";
			$code .= '$_NEWSLETTER_CONFIG[\'newsletter_name\'] = ' . var_export(!empty($NEWSLETTER_CONFIG['newsletter_name']) ? $NEWSLETTER_CONFIG['newsletter_name'] : $CONFIG['site_name'], true) . ';' . "\n";
		}
		
		return $code;
	}

	
	




}

?>
