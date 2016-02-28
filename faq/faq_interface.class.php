<?php



























import('modules/module_interface');

define('FAQ_MAX_SEARCH_RESULTS', 100);


class FaqInterface extends ModuleInterface
{
    ## Public Methods ##
    function FaqInterface() 
    {
        parent::ModuleInterface('faq');
    }
    
	
	function get_cache()
	{
		global $Sql;
	
		
		$config = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'faq'", __LINE__, __FILE__));
		$root_config = $config['root'];
		$root_config['auth'] = $config['global_auth'];
		unset($config['root']);
		$string = 'global $FAQ_CONFIG, $FAQ_CATS, $RANDOM_QUESTIONS;' . "\n\n";
		$string .= '$FAQ_CONFIG = ' . var_export($config, true) . ';' . "\n\n";
		
		
		$string .= '$FAQ_CATS = array();' . "\n\n";
		$string .= '$FAQ_CATS[0] = ' . var_export($root_config, true) . ';' . "\n";
		$string .= '$FAQ_CATS[0][\'name\'] = \'\';' . "\n";
		$result = $Sql->query_while("SELECT id, id_parent, c_order, auth, name, visible, display_mode, image, num_questions, description
		FROM " . PREFIX . "faq_cats
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$string .= '$FAQ_CATS[' . $row['id'] . '] = ' .
				var_export(array(
				'id_parent' => $row['id_parent'],
				'order' => $row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'display_mode' => $row['display_mode'],
				'image' => $row['image'],
				'num_questions' => $row['num_questions'],
				'description' => $row['description'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
		}
		
		
		$query = $Sql->query_while ("SELECT id, question, idcat FROM " . PREFIX . "faq LIMIT 0, 20", __LINE__, __FILE__);
		$questions = array();
		while ($row = $Sql->fetch_assoc($query))
			$questions[] = array('id' => $row['id'], 'question' => $row['question'], 'idcat' => $row['idcat']);
		
		$string .= "\n" . '$RANDOM_QUESTIONS = ' . var_export($questions, true) . ';';
		
		return $string;
	}

	




	function get_search_request($args)
    {
        global $Sql, $Cache;
		$Cache->load('faq');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        require_once(PATH_TO_ROOT . '/faq/faq_cats.class.php');
        $Cats = new FaqCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $auth_cats);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            f.id AS id_content,
            f.question AS title,
            ( 2 * MATCH(f.question) AGAINST('" . $args['search'] . "') + MATCH(f.answer) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
            . $Sql->concat("'../faq/faq.php?id='","f.idcat","'&amp;question='","f.id","'#q'","f.id") . " AS link
            FROM " . PREFIX . "faq f
            WHERE ( MATCH(f.question) AGAINST('" . $args['search'] . "') OR MATCH(f.answer) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $Sql->limit(0, FAQ_MAX_SEARCH_RESULTS);
        
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
        
        $request = "SELECT idcat, id, question, answer
            FROM " . PREFIX . "faq
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
        $tpl = new Template('faq/search_result.tpl');
        
        $tpl->assign_vars(array(
            'U_QUESTION' => PATH_TO_ROOT . '/faq/faq.php?id=' . $result_data['idcat'] . '&amp;question=' . $result_data['id'] . '#q' . $result_data['id'],
            'QUESTION' => $result_data['question'],
            'ANSWER' => second_parse($result_data['answer'])
        ));
        
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    
    




	function get_module_map($auth_mode = SITE_MAP_AUTH_GUEST)
	{
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG, $Cache;
		
		import('content/sitemap/module_map');
		import('util/url');
		
		include_once(PATH_TO_ROOT . '/faq/faq_begin.php');
		
		$faq_link = new SiteMapLink($FAQ_LANG['faq'], new Url('/faq/faq.php'), SITE_MAP_FREQ_DEFAULT, SITE_MAP_PRIORITY_MAX);
		
		$module_map = new ModuleMap($faq_link);
		$module_map->set_description('<em>Test</em>');
		
		$id_cat = 0;
	    $keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->_create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map;
	}
	
	#Private#
	function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG;
		
		$this_category = new SiteMapLink($FAQ_CATS[$id_cat]['name'], new Url('/faq/' . url('faq.php?id=' . $id_cat, 'faq-' . $id_cat . '+' . url_encode_rewrite($FAQ_CATS[$id_cat]['name']) . '.php')));
			
		$category = new SiteMapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->_create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0	)
			$category = $this_category;
		
		return $category;
	}
}

?>
