<?php


























define('PATH_TO_ROOT', '.');

header("Content-Type: application/xml; charset=iso-8859-1");

define('NO_SESSION_LOCATION', true); 
require_once PATH_TO_ROOT . '/kernel/begin.php';
require_once PATH_TO_ROOT . '/kernel/header_no_display.php';

import('content/syndication/feed');

$module_id = retrieve(GET, 'm', '');
if (!empty($module_id))
{
	$feed_name = retrieve(GET, 'name', DEFAULT_FEED_NAME);
	$category_id = retrieve(GET, 'cat', 0);

	$feed = null;

	switch (retrieve(GET, 'feed', 'rss'))
	{
		case 'atom':    
			import('content/syndication/atom');
			$feed= new ATOM($module_id, $feed_name, $category_id);
			break;
		default:        
			import('content/syndication/rss');
			$feed= new RSS($module_id, $feed_name, $category_id);
			break;
	}

	if ($feed != null && $feed->is_in_cache())
	{   
		echo $feed->read();
	}
	else
	{   
		
		import('modules/modules_discovery_service');
		$modules_discovery_service = new ModulesDiscoveryService();
		$module = $modules_discovery_service->get_module($module_id);

		if (is_object($module) && $module->got_error() == 0 && $module->has_functionality('get_feed_data_struct'))
		{
			$feed->load_data($module->get_feed_data_struct($category_id, $feed_name));
			$feed->cache();

			
			echo $feed->export();
		}
		else
		{
			redirect('member/error.php?e=e_uninstalled_module');
		}
	}
}

require_once PATH_TO_ROOT . '/kernel/footer_no_display.php';

?>
