<?php


























define('DOWNLOAD_MAX_SEARCH_RESULTS', 100);

import('modules/module_interface');


class DownloadInterface extends ModuleInterface
{
    ## Public Methods ##
    function DownloadInterface()
    {
        parent::ModuleInterface('download');
    }
  
	
	function get_cache()
	{
		global $Sql, $LANG, $Cache;
	
		$code = 'global $DOWNLOAD_CATS;' . "\n" . 'global $CONFIG_DOWNLOAD;' . "\n\n";
			
		
		$CONFIG_DOWNLOAD = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'download'", __LINE__, __FILE__));
		
		$code .= '$CONFIG_DOWNLOAD = ' . var_export($CONFIG_DOWNLOAD, true) . ';' . "\n";
		
		
		$code .= '$DOWNLOAD_CATS = array();' . "\n\n";
		
		
		$code .= '$DOWNLOAD_CATS[0] = ' . var_export(array('name' => $LANG['root'], 'auth' => $CONFIG_DOWNLOAD['global_auth']) ,true) . ';' . "\n\n";
		
		$result = $Sql->query_while("SELECT id, id_parent, c_order, auth, name, visible, icon, num_files, contents
		FROM " . PREFIX . "download_cat
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$DOWNLOAD_CATS[' . $row['id'] . '] = ' .
			var_export(array(
			'id_parent' => $row['id_parent'],
			'order' => $row['c_order'],
			'name' => $row['name'],
			'contents' => $row['contents'],
			'visible' => (bool)$row['visible'],
			'icon' => $row['icon'],
			'description' => $row['contents'],
			'num_files' => $row['num_files'],
			'auth' => unserialize($row['auth'])
			), true)
			. ';' . "\n";
		}
		
		return $code;
	}

	
	function on_changeday()
	{
		global $Sql;
		
		
		$result = $Sql->query_while("SELECT id, start, end
		FROM " . PREFIX . "download
		WHERE start > 0 AND end > 0", __LINE__, __FILE__);
		$time = time();
		while ($row = $Sql->fetch_assoc($result))
		{
			
			if ($row['start'] <= $time && $row['end'] >= $time && $row['visible'] = 0)
				$Sql->query_inject("UPDATE " . PREFIX . "download SET visible = 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			
			
			if (($row['start'] >= $time || $row['end'] <= $time) && $row['visible'] = 1)
				$Sql->query_inject("UPDATE " . PREFIX . "download SET visible = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}

	function get_search_request($args)
    


    {
        global $Sql, $Cache;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		$Cache->load('download');
		
        require_once(PATH_TO_ROOT . '/download/download_cats.class.php');
        $cats = new DownloadCats();
        $auth_cats = array();
        $cats->build_children_id_list(0, $auth_cats);
        
        $auth_cats = !empty($auth_cats) ? " AND d.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            d.id AS id_content,
            d.title AS title,
            ( 3 * MATCH(d.title) AGAINST('" . $args['search'] . "') + 2 * MATCH(d.short_contents) AGAINST('" . $args['search'] . "') + MATCH(d.contents) AGAINST('" . $args['search'] . "') ) / 6 * " . $weight . " AS relevance, "
            . $Sql->concat("'" . PATH_TO_ROOT . "/download/download.php?id='","d.id") . " AS link
            FROM " . PREFIX . "download d
            WHERE ( MATCH(d.title) AGAINST('" . $args['search'] . "') OR MATCH(d.short_contents) AGAINST('" . $args['search'] . "') OR MATCH(d.contents) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $Sql->limit(0, DOWNLOAD_MAX_SEARCH_RESULTS);
        
        return $request;

    }
    
    




    function compute_search_results(&$args)
    {
        global $CONFIG, $Sql;
        
        $results_data = array();
        
        $results =& $args['results'];
        $nb_results = count($results);
        
        $ids = array();
        for ($i = 0; $i < $nb_results; $i++)
            $ids[] = $results[$i]['id_content'];
        
        $request = "SELECT id, idcat, title, short_contents, url, note, image, count, timestamp, nbr_com
            FROM " . PREFIX . "download
            WHERE id IN (" . implode(',', $ids) . ")";
        
        $request_results = $Sql->query_while ($request, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($request_results))
        {
            $results_data[] = $row;
        }
        $Sql->query_close($request_results);
        
        return $results_data;
    }
    
    




    function parse_search_result(&$result_data)
    {
        global $Cache, $CONFIG, $LANG, $DOWNLOAD_LANG, $CONFIG_DOWNLOAD;
        $Cache->load('download');
        
        load_module_lang('download'); 
        $tpl = new Template('download/download_generic_results.tpl');
        
        import('util/date');
        $date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $result_data['timestamp']);
        import('content/note'); 
        
        $tpl->assign_vars(array(
            'L_ADDED_ON' => sprintf($DOWNLOAD_LANG['add_on_date'], $date->format(DATE_FORMAT_TINY, TIMEZONE_USER)),
            'U_LINK' => url(PATH_TO_ROOT . '/download/download.php?id=' . $result_data['id']),
            'U_IMG' => $result_data['image'],
            'E_TITLE' => strprotect($result_data['title']),
            'TITLE' => $result_data['title'],
            'SHORT_DESCRIPTION' => second_parse($result_data['short_contents']),
            'L_NB_DOWNLOADS' => $DOWNLOAD_LANG['downloaded'] . ' ' . sprintf($DOWNLOAD_LANG['n_times'], $result_data['count']),
            'L_NB_COMMENTS' => $result_data['nbr_com'] > 1 ? sprintf($DOWNLOAD_LANG['num_com'], $result_data['nbr_com']) : sprintf($DOWNLOAD_LANG['num_coms'], $result_data['nbr_com']),
            'L_MARK' => $result_data['note'] > 0 ? Note::display_img($result_data['note'], $CONFIG_DOWNLOAD['note_max'], 5) : ('<em>' . $LANG['no_note'] . '</em>')
        ));
        
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    
	
    
    function get_feed_data_struct($idcat = 0, $name = '')
    {
        require_once(PATH_TO_ROOT . '/download/download_auth.php');
        require_once(PATH_TO_ROOT . '/download/download_cats.class.php');
        import('content/syndication/feed_data');
        import('util/date');
        import('util/url');
        
        global $Cache, $Sql, $LANG, $DOWNLOAD_LANG, $CONFIG, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
		load_module_lang('download');
        $Cache->load('download');
        $data = new FeedData();
        
        
        $data->set_title($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=download&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(DOWNLOAD_READ_CAT_AUTH_BIT);
		
        
        
        $cats = new DownloadCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);
        
        $req = "SELECT id, idcat, title, contents, timestamp, image
        FROM " . PREFIX . "download
        WHERE visible = 1 AND idcat IN (" . implode($children_cats, ','). " )
        ORDER BY timestamp DESC" . $Sql->limit(0, $CONFIG_DOWNLOAD['nbr_file_max']);
        $result = $Sql->query_while ($req, __LINE__, __FILE__);
        
        
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            
            $link = new Url('/download/download' . url('.php?id=' . $row['id'], '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php'));
            
            $item->set_title($row['title']);
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($row['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], DOWNLOAD_READ_CAT_AUTH_BIT, AUTH_PARENT_PRIORITY));
            
            
            $data->add_item($item);
        }
        $Sql->query_close($result);
        
        return $data;
    }
    
    



    function get_feeds_list()
	{
        require_once(PATH_TO_ROOT . '/download/download_cats.class.php');
        $dl_cats = new DownloadCats();
        return $dl_cats->get_feeds_list();
	}
    
    ## Private ##
    function _check_cats_auth($id_cat, &$list)
    {
        global $DOWNLOAD_CATS, $CONFIG_DOWNLOAD;

        if ($id_cat == 0)
        {
            if (Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT))
                $list[] = 0;
            else
                return;
        }
        else
        {
			if (!empty($DOWNLOAD_CATS[$id_cat]))
			{
				$auth = !empty($DOWNLOAD_CATS[$id_cat]['auth']) ? $DOWNLOAD_CATS[$id_cat]['auth'] : $CONFIG_DOWNLOAD['global_auth'];
				if (Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $auth, DOWNLOAD_READ_CAT_AUTH_BIT))
					$list[] = $id_cat;
            }
			else
                return;
        }
        
        $keys = array_keys($DOWNLOAD_CATS);
        $num_cats = count($DOWNLOAD_CATS);
        
        $properties = array();
        for ($j = 0; $j < $num_cats; $j++)
        {
            $id = $keys[$j];
            $properties = $DOWNLOAD_CATS[$id];
            
            if ($properties['id_parent'] == $id_cat)
            {
                $this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], DOWNLOAD_READ_CAT_AUTH_BIT) :  Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
                
                if ($this_auth)
                {
                    $list[] = $id;
                    $this->_check_cats_auth($id, $list);
                }
            }
        }
    }
	
	function get_cat()
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT *
	            FROM " . PREFIX . "download_cat", __LINE__, __FILE__);
			$data = array();
		while ($row = $Sql->fetch_assoc($result)) {
			$data[$row['id']] = $row['name'];
		}
		$Sql->query_close($result);
		return $data;
	}
}

?>
