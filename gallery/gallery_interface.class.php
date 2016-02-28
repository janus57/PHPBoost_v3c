<?php


























if (defined('PHPBOOST') !== true) exit;


import('modules/module_interface');


class GalleryInterface extends ModuleInterface
{
    ## Public Methods ##
    function GalleryInterface() 
    {
        parent::ModuleInterface('gallery');
    }
    
	
	function get_cache()
	{
		global $Sql;
		global $CONFIG_GALLERY;
		
		$gallery_config = 'global $CONFIG_GALLERY;' . "\n";
		
		
		$CONFIG_GALLERY = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'gallery'", __LINE__, __FILE__));
		$CONFIG_GALLERY = is_array($CONFIG_GALLERY) ? $CONFIG_GALLERY : array();
		if (isset($CONFIG_GALLERY['auth_root']))
			$CONFIG_GALLERY['auth_root'] = unserialize($CONFIG_GALLERY['auth_root']);
		
		$gallery_config .= '$CONFIG_GALLERY = ' . var_export($CONFIG_GALLERY, true) . ';' . "\n";

		$cat_gallery = 'global $CAT_GALLERY;' . "\n";
		$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, aprob, auth
		FROM " . PREFIX . "gallery_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{		
			if (empty($row['auth']))
				$row['auth'] = serialize(array());
			
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$Sql->query_close($result);
		
		include_once(PATH_TO_ROOT . '/gallery/gallery.class.php'); 
		$Gallery = new Gallery;	
				
		$_array_random_pics = 'global $_array_random_pics;' . "\n" . '$_array_random_pics = array(';
		$result = $Sql->query_while("SELECT g.id, g.name, g.path, g.width, g.height, g.idcat, gc.auth 
		FROM " . PREFIX . "gallery g
		LEFT JOIN " . PREFIX . "gallery_cats gc on gc.id = g.idcat
		WHERE g.aprob = 1 AND (gc.aprob = 1 OR g.idcat = 0)
		ORDER BY RAND()
		" . $Sql->limit(0, 30), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['idcat'] == 0)
				$row['auth'] = serialize($CONFIG_GALLERY['auth_root']);
			
			
			list($width, $height) = $Gallery->get_resize_properties($row['width'], $row['height']);
			
			$_array_random_pics .= 'array(' . "\n" .
			'\'id\' => ' . var_export($row['id'], true) . ',' . "\n" .
			'\'name\' => ' . var_export($row['name'], true) . ',' . "\n" .
			'\'path\' => ' . var_export($row['path'], true) . ',' . "\n" .
			'\'width\' => ' . var_export($width, true) . ',' . "\n" .
			'\'height\' => ' . var_export($height, true) . ',' . "\n" .
			'\'idcat\' => ' . var_export($row['idcat'], true) . ',' . "\n" .
			'\'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}
		$Sql->query_close($result);	
		$_array_random_pics .= ');';
		
		return $gallery_config . "\n" . $cat_gallery . "\n" . $_array_random_pics;
	}

	
	function on_changeday()
	{
		$this->get_cache();
	}		
}

?>
