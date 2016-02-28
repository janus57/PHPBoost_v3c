<?php



























define('DEBUG_MODE', true);
define('PRODUCTION_MODE', false);
define('NORMAL_MODE', true);
define('AJAX_MODE', false);
define('RECURSIVE_EXPLORATION', true);
define('NOT_RECURSIVE_EXPLORATION', false);
define('MOVE_CATEGORY_UP', 'up');
define('MOVE_CATEGORY_DOWN', 'down');
define('DO_NOT_LOAD_CACHE', false);
define('LOAD_CACHE', true);
define('CAT_VISIBLE', true);
define('CAT_UNVISIBLE', false);
define('ADD_THIS_CATEGORY_IN_LIST', true);
define('DO_NOT_ADD_THIS_CATEGORY_IN_LIST', false);
define('STOP_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH', 1);
define('IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH', 2);


define('ERROR_UNKNOWN_MOTION', 0x01);
define('ERROR_CAT_IS_AT_TOP', 0x02);
define('ERROR_CAT_IS_AT_BOTTOM', 0x04);
define('CATEGORY_DOES_NOT_EXIST', 0x08);
define('NEW_PARENT_CATEGORY_DOES_NOT_EXIST', 0x10);
define('DISPLAYING_CONFIGURATION_NOT_SET', 0x20);
define('INCORRECT_DISPLAYING_CONFIGURATION', 0x40);
define('NEW_CATEGORY_IS_IN_ITS_CHILDRENS', 0x80);
define('NEW_STATUS_UNKNOWN', 0x100);













































class CategoriesManager
{
	## Public methods ##
	





	function CategoriesManager($table, $cache_file_name, &$cache_var)
	{
		$this->table = $table;
		$this->cache_file_name = $cache_file_name;
		
		$this->cache_var =& $cache_var;
	}
	
	








	function add($id_parent, $name, $visible = CAT_VISIBLE, $order = 0)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		
		if (!is_int($visible))
			$visible = (int)$visible;
		
		$max_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $id_parent . "'", __LINE__, __FILE__);
		$max_order = numeric($max_order);
		
		if ($id_parent == 0 || array_key_exists($id_parent, $this->cache_var))
		{
			
			if ($order <= 0 || $order > $max_order)
				$Sql->query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . ($max_order + 1) . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			else
			{
				$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $id_parent . "' AND c_order >= '" . $order . "'", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . $order . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			}
			return $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . $this->table);
		}
		else
		{
			$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return 0;
		}
	}

	












	function move($id, $way)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		if (in_array($way, array(MOVE_CATEGORY_UP, MOVE_CATEGORY_DOWN)))
		{
			$cat_info = $Sql->query_array(PREFIX . $this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			
			if (empty($cat_info['c_order']))
			{
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				return false;
			}
			
			if ($way == MOVE_CATEGORY_DOWN)
			{
				
				$max_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $cat_info['id_parent'] . "'", __LINE__, __FILE__);
				if ($cat_info['c_order'] < $max_order)
				{
					
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] + 1) . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$Cache->Generate_module_file($this->cache_file_name);
					
					return true;
				}
				else
				{
					$this->_add_error(ERROR_CAT_IS_AT_BOTTOM);
					return false;
				}
			}
			else
			{
				if ($cat_info['c_order'] > 1)
				{
					
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] - 1) . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$Cache->Generate_module_file($this->cache_file_name);
					return true;
				}
				else
				{
					$this->_add_error(ERROR_CAT_IS_AT_TOP);
					return false;
				}
			}
		}
		else
		{
			$this->_add_error(ERROR_UNKNOWN_MOTION);
			return false;
		}
	}

	












	function move_into_another($id, $new_id_cat, $position = 0)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		
		if (($id == 0 || array_key_exists($id, $this->cache_var)) && ($new_id_cat == 0 || array_key_exists($new_id_cat, $this->cache_var)))
		{
			
			$subcats_list = array($id);
			$this->build_children_id_list($id, $subcats_list);
			if (!in_array($new_id_cat, $subcats_list))
			{
				$max_new_cat_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $new_id_cat . "'", __LINE__, __FILE__);	
				
				if ($position <= 0 || $position > $max_new_cat_order)
				{
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . ($max_new_cat_order + 1). "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
				}
				
				else
				{
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $new_id_cat . "' AND c_order >= '" . $position . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . $position . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
				}
				
				
				$Cache->Generate_module_file($this->cache_file_name);
				return true;
			}
			else
			{
				$this->_add_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS);
				return false;
			}
		}
		else
		{
			if ($new_id_cat != 0 && !array_key_exists($new_id_cat, $this->cache_var))
				$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			if ($id != 0 && !array_key_exists($id, $this->cache_var))
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				
			return false;
		}
	}

	





	function delete($id)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		
		if ($id != 0 && !array_key_exists($id, $this->cache_var))
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		$cat_infos = $this->cache_var[$id];
		
		
		$Sql->query_inject("DELETE FROM " . PREFIX . $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		
		$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '". $cat_infos['id_parent'] . "' AND c_order > '" . $cat_infos['order'] . "'", __LINE__, __FILE__);
		
		
		$Cache->Generate_module_file($this->cache_file_name);
		
		return true;
	}
	
	










	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		global $Sql, $Cache;
		
		
		if (!in_array($visibility, array(CAT_VISIBLE, CAT_UNVISIBLE)))
		{
			$this->_add_error(NEW_STATUS_UNKNOWN);
			return false;
		}
			
		if ($category_id > 0 && array_key_exists($category_id, $this->cache_var))
		{
			$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET visible = '" . (int)$visibility . "' WHERE id = '" . $category_id . "'", __LINE__, __FILE__);

			
			if ($generate_cache)
				$Cache->Generate_module_file($this->cache_file_name);
			
			return true;
		}
		else
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
	}

	













	function set_display_config($config)
	{
		
		$this->display_config = $config;
		
		return $this->check_display_config();
	}
 
	




	function check_display_config($debug = PRODUCTION_MODE)
	{
		if (!empty($this->display_config))
		{
			if (array_key_exists('administration_file_name', $this->display_config) && array_key_exists('url' ,$this->display_config) && array_key_exists('xmlhttprequest_file', $this->display_config) && array_key_exists('unrewrited', $this->display_config['url'])
			 )
				return true;
			else
			{
				if ($debug)
					return false;
				
				if (!array_key_exists('administration_file_name', $this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>administration_file_name</em>');
				if (!array_key_exists('url' ,$this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>url</em>');
				if (!array_key_exists('unrewrited', $this->display_config['url']))
					die('<strong>Categories_management error : </strong> you must specify the key <em>unrewrited</em> in the <em>url</em> part');
				if (!array_key_exists('xmlhttprequest_file', $this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>xhtmlhttprequest_file</em>');
				return false;
			}
		}
		else
			return false;
	}

	









	function build_administration_interface($ajax_mode = NORMAL_MODE, $category_template = NULL)
	{
		global $CONFIG, $LANG;
		
		if (is_null($category_template) || !is_object($category_template) || !strtolower(get_class($category_template)) == 'template')
			$category_template = new Template('framework/content/category.tpl');
		
		$template = new Template('framework/content/categories.tpl');
		
		$this->_clear_error();
		
		if (!$this->check_display_config())
		{
			$this->_add_error(INCORRECT_DISPLAYING_CONFIGURATION);
			return false;
		}
		
		
		if (count($this->cache_var) == 0)
		{
			$template->assign_vars(array(
				'L_NO_EXISTING_CATEGORY' => $LANG['cats_managment_no_category_existing'],
				'C_NO_CATEGORY' => true
			));
			return $template->parse(TEMPLATE_STRING_MODE);
		}
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => (int)$ajax_mode,
			'CONFIG_XMLHTTPREQUEST_FILE' => $this->display_config['xmlhttprequest_file'],
			'L_COULD_NOT_BE_MOVED' => $LANG['cats_managment_could_not_be_moved'],
			'L_VISIBILITY_COULD_NOT_BE_CHANGED' => $LANG['cats_managment_visibility_could_not_be_changed'],
			
			'NESTED_CATEGORIES' => $this->_create_row_interface(0, 0, $ajax_mode, $category_template)
		));
				
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	











	function build_select_form($selected_id, $form_id, $form_name, $current_id_cat = 0, $num_auth = 0, $array_auth = array(), $recursion_mode = STOP_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $template = NULL)
	{
		global $LANG, $User;
		
		$general_auth = false;
		
		if (is_null($template) || !is_object($template) || strtolower(get_class($template)) != 'template')
			$template = new Template('framework/content/categories_select_form.tpl');
		
		if ($num_auth != 0)
			$general_auth = $User->check_auth($array_auth, $num_auth);
		
		$template->assign_vars(array(
			'FORM_ID' =>  $form_id,
			'FORM_NAME' =>  $form_name,
			'SELECTED_ROOT' => $selected_id == 0 ? ' selected="selected"' : '',
			'L_ROOT' => $LANG['root']
		));
				
		$this->_create_select_row(0, 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);

		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	








	function build_children_id_list($category_id, &$list, $recursive_exploration = RECURSIVE_EXPLORATION, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth = 0)
	{
		global $User;
		
		$end_of_category = false;
		
		if ($add_this && ($category_id == 0 || (($num_auth > 0 && $User->check_auth($this->cache_var[$category_id], $num_auth) || $num_auth == 0))))
			$list[] = $category_id;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if ($id != 0 && $value['id_parent'] == $category_id)
			{
				$list[] = $id;
				if ($recursive_exploration && (($num_auth > 0 && $User->check_auth($this->cache_var[$id]['auth'], $num_auth) || $num_auth == 0)))
					$this->build_children_id_list($id, $list, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth);
				
				if (!$end_of_category)
					$end_of_category = true;
			}
			elseif ($end_of_category)
				break;
		}
	}
	
	





	function build_parents_id_list($category_id, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST)
	{
		$list = array();
		if ($add_this)
			$list[] = $category_id;
	
		if ($category_id > 0)
		{
			while ((int)$this->cache_var[$category_id]['id_parent'] != 0)
			{
			    $list[] = $category_id = (int)$this->cache_var[$category_id]['id_parent'];
			}
		}
		return $list;
	}
	
	





	function check_error($error)
	{
		return (bool)($this->errors ^ $error);
	}
	
	






	function compute_heritated_auth($category_id, $bit, $mode)
	{
		$ids = array_reverse($this->build_parents_id_list($category_id, ADD_THIS_CATEGORY_IN_LIST));
		$length = count($ids);

		$result = array();
		
		if (count($ids) > 0)
		{
			$result = $this->cache_var[$ids[0]]['auth'];
		
			for ($i = 1; $i < $length; $i++)
				$result = Authorizations::merge_auth($result, $this->cache_var[$ids[$i]]['auth'], $bit, $mode);
		}

		return $result;
	}
	
	



	function get_feeds_list()
	{
	    global $LANG;
	    import('content/syndication/feeds_list');
	    import('content/syndication/feeds_cat');
	    
	    $list = new FeedsList();
	    
	    $cats_tree = new FeedsCat($this->cache_file_name, 0, $LANG['root']);
	    
	    $this->_build_feeds_sub_list($cats_tree, 0);
	    
	    $list->add_feed($cats_tree, DEFAULT_FEED_NAME);
	    
	    return $list;
	}
	
	## Private methods ##	
	







	function _create_row_interface($id_cat, $level, $ajax_mode, &$reference_template)
	{
		global $CONFIG, $LANG, $Session;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		$template = $reference_template->copy();
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => $ajax_mode,
			'L_MANAGEMENT_HIDE_CAT' => $LANG['cats_management_hide_cat'],
			'L_MANAGEMENT_SHOW_CAT' => $LANG['cats_management_show_cat'],
			'L_CONFIRM_DELETE' => $LANG['cats_management_confirm_delete']
		));
		
		
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$values =& $this->cache_var[$id];
			
			
			if ($id != 0 && $values['id_parent'] == $id_cat)
			{
				$template->assign_block_vars('categories', array(
					'ID' => $id,
					'MARGIN_LEFT' => $level * 50,
					'C_DISPLAY_URL' => !empty($this->display_config['url']),
					'URL' => (empty($this->display_config['url']['rewrited']) ?
									url(sprintf($this->display_config['url']['unrewrited'], $id))
								:
									
									(!empty($this->display_config['url']['rewrited']) ?
									
									(strpos($this->display_config['url']['rewrited'], '%s') !== false ?
										url(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id, url_encode_rewrite($values['name']))) :
										
										url(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id)))
									: '')
								),
					'NAME' => $values['name'],
					
					'C_NOT_FIRST_CAT' => $values['order'] > 1,
					'ACTION_GO_UP' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?id_up=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_move_cat(' . $id . ', \'up\');',
					
					'C_NOT_LAST_CAT' => $i != $num_cats  - 1 && $this->cache_var[$id_categories[$i + 1]]['id_parent'] == $id_cat,
					'ACTION_GO_DOWN' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?id_down=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_move_cat(' . $id . ', \'down\');',
					'C_VISIBLE' => $values['visible'],
					'ACTION_HIDE' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?hide=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'hide\');',
					'ACTION_SHOW' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?show=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'show\');',
					'ACTION_EDIT' => url($this->display_config['administration_file_name'] . '?edit=' . $id),
					'ACTION_DELETE' => url($this->display_config['administration_file_name'] . '?del=' . $id . '&amp;token=' . $Session->get_token()),
					'CONFIRM_DELETE' => $LANG['cats_management_confirm_delete'],
					
					'NEXT_CATEGORY' => $this->_create_row_interface($id, $level + 1, $ajax_mode, $reference_template)
				));
				
				
				if ($i + 1 < $num_cats && $this->cache_var[$id_categories[$i + 1]]['id_parent'] != $id_cat)
					break;
			}
		}
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	










	function _create_select_row($id_cat, $level, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, &$template)
	{
		global $User;
		
		$end_of_category = false;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats = count($id_categories);
		
		
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			
			if ($id == $current_id_cat)
				continue;
				
			if ($id != 0 && $value['id_parent'] == $id_cat)
			{
				
				
				if ($recursion_mode != IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH)
				{
					if ($num_auth == 0 || $general_auth || $User->check_auth($value['auth'], $num_auth))
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);
					}
				}
				
				else
				{
					
					if ($num_auth == 0)
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);
					}
					
					elseif ((empty($value['auth']) && $general_auth) || (!empty($value['auth']) && $User->check_auth($value['auth'], $num_auth)))
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, true, $template);
					}
					
					elseif ((empty($value['auth']) && !$general_auth) || (!empty($value['auth']) && !$User->check_auth($value['auth'], $num_auth)))
					{
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, false, $template);
					}
				}
				if (!$end_of_category)
					$end_of_category = true;
			}
			elseif ($end_of_category)
				break;
		}
	}
	
	



	function _add_error($error)
	{
		$this->errors |= $error;
	}

	



	function _clear_error($error = 0)
	{
		if ($error != 0)
		{
			$this->errors &= (~$error);
		}
		else
		{
			$this->errors = 0;
		}
	}
	
	




	function _build_feeds_sub_list(&$tree, $parent_id)
	{
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if ($id != 0 && $value['id_parent'] == $parent_id)
			{
			    $sub_tree = new FeedsCat($this->cache_file_name, $id, $value['name']);
			    
			    $this->_build_feeds_sub_list($sub_tree, $id);
			    
			    $tree->add_child($sub_tree);
			}
		}
	}	

	## Private attributes ##
	


	var $table = '';
	
	


	var $cache_file_name = '';
	
	


	var $errors = 0;
	
	


	var $display_config = array();
	
	


	var $cache_var = array();
}

?>
