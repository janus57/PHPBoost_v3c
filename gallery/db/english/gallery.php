<?php


























if (defined('PHPBOOST') !== true) exit;


@clearstatcache();
	
$chmod_dir = array('../gallery/pics', '../gallery/pics/thumbnails');

foreach ($chmod_dir as $dir)
{
	if (file_exists($dir) && is_dir($dir))
	{
		if (!is_writable($dir))
			@chmod($dir, 0777);			
	}
	else
		@mkdir($dir, 0777);
}

?>
