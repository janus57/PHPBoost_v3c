<?php




























import('modules/module_interface');

define('MEDIA_MAX_SEARCH_RESULTS', 100);


class MediaInterface extends ModuleInterface
{
    ## Public Methods ##
    function MediaInterface() 
    {
        parent::ModuleInterface('media');
    }

	
	function get_cache()
	{
		global $Sql;
		
		require_once PATH_TO_ROOT . '/media/media_constant.php';

		
		$i = 0;
		$config = array();
		$config = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'media'", __LINE__, __FILE__));
		$root_config = $config['root'];
		unset($config['root']);

		$string = 'global $MEDIA_CONFIG, $MEDIA_CATS;' . "\n\n" . '$MEDIA_CONFIG = $MEDIA_CATS = array();' . "\n\n";
		$string .= '$MEDIA_CONFIG = ' . var_export($config, true) . ';' . "\n\n";

		
		$string .= '$MEDIA_CATS[0] = ' . var_export($root_config, true) . ';' . "\n\n";
		$result = $Sql->query_while("SELECT * FROM " . PREFIX . "media_cat ORDER BY id_parent, c_order ASC", __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
		{
			$string .= '$MEDIA_CATS[' . $row['id'] . '] = ' . var_export(array(
				'id_parent' => (int)$row['id_parent'],
				'order' => (int)$row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'image' => $row['image'],
				'num_media' => (int)$row['num_media'],
				'mime_type' => (int)$row['mime_type'],
				'active' => (int)$row['active'],
				'auth' => (array)unserialize($row['auth'])
			), true) . ';' . "\n\n";
		}

		$Sql->query_close($result);

		return $string;
	}

	
    function get_feed_data_struct($idcat = 0)
    {
    	global $Cache, $Sql, $LANG, $MEDIA_LANG, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS;
        
        $Cache->load('media');
		load_module_lang('media');

        require_once(PATH_TO_ROOT . '/media/media_constant.php');
        require_once(PATH_TO_ROOT . '/media/media_cats.class.php');
		import('content/syndication/feed_data');
		import('util/date');
		import('util/url');
        
        $data = new FeedData();
        
        
        $data->set_title($MEDIA_LANG['xml_media_desc']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=media&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($MEDIA_LANG['xml_media_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(MEDIA_AUTH_READ);

        
        $cats = new MediaCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);

        $result = $Sql->query_while ("SELECT id, idcat, name, contents, timestamp FROM " . PREFIX . "media WHERE infos = '" . MEDIA_STATUS_APROBED . "' AND idcat IN (" . implode($children_cats, ','). " ) ORDER BY timestamp DESC" . $Sql->limit(0, $MEDIA_CONFIG['pagin']), __LINE__, __FILE__);
        
        
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            
            
            $link = new Url('/media/media' . url(
                '.php?id=' . $row['id'],
                '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php'
            ));
            
            
            $item->set_title($row['name']);
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($MEDIA_CATS[$row['idcat']]['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], MEDIA_AUTH_READ, AUTH_PARENT_PRIORITY));
            
            
            $data->add_item($item);
        }

        $Sql->query_close($result);
        
        return $data;
    }
    
    function get_search_request($args)
    


    {
        global $Sql, $Cache;
		$Cache->load('media');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        require_once PATH_TO_ROOT . '/media/media_cats.class.php';
        $Cats = new MediaCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $auth_cats);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            f.id AS id_content,
            f.name AS title,
            ( 2 * MATCH(f.name) AGAINST('" . $args['search'] . "') + MATCH(f.contents) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
            . $Sql->concat("'../media/media.php?id='","f.id","'&amp;cat='","f.idcat") . " AS link
            FROM " . PREFIX . "media f
            WHERE ( MATCH(f.name) AGAINST('" . $args['search'] . "') OR MATCH(f.contents) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $Sql->limit(0, MEDIA_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
	



    function get_feeds_list()
	{
        require_once PATH_TO_ROOT . '/media/media_cats.class.php';
        $media_cats = new MediaCats();
        return $media_cats->get_feeds_list();	    
	}
}

?>
