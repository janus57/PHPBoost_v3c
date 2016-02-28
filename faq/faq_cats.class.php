<?php


























import('content/categories_manager');

define('FAQ_DO_NOT_GENERATE_CACHE', false);

class FaqCats extends CategoriesManager
{
	## Public methods ##
	
	
	function FaqCats()
	{
		global $Cache, $FAQ_CATS;
		if (!isset($FAQ_CATS))
			$Cache->load('faq');
		
		parent::CategoriesManager('faq_cats', 'faq', $FAQ_CATS);
	}
	
	
	function delete_category_recursively($id)
	{
		
		$this->_delete_category_with_content($id);
		
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
				$this->Delete_category_recursively($id_cat);
		}
		
		$this->recount_subquestions();
	}
	
	
	function delete_category_and_move_content($id_category, $new_id_cat_content)
	{
		global $Sql;
		
		if (!array_key_exists($id_category, $this->cache_var))
		{
			parent::_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		parent::delete($id_category);
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id_category)
				parent::move_into_another($id_cat, $new_id_cat_content);			
		}
		
		$max_q_order = $Sql->query("SELECT MAX(q_order) FROM " . PREFIX . "faq WHERE idcat = '" . $new_id_cat_content . "'", __LINE__, __FILE__);
		$max_q_order = $max_q_order > 0 ? $max_q_order : 1;
		$Sql->query_inject("UPDATE " . PREFIX . "faq SET idcat = '" . $new_id_cat_content . "', q_order = q_order + " . $max_q_order . " WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);
		
		$this->recount_subquestions();
		
		return true;
	}
	
	
	function add($id_parent, $name, $description, $image)
	{
		global $Sql;
		if (array_key_exists($id_parent, $this->cache_var))
		{
			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET description = '" . $description . "', image = '" . $image . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			
			return 'e_success';
		}
		else
			return 'e_unexisting_cat';
	}
	
	
	function update_category($id_cat, $id_parent, $name, $description, $image)
	{
		global $Sql, $Cache;
		if (array_key_exists($id_cat, $this->cache_var))
		{
			if ($id_parent != $this->cache_var[$id_cat]['id_parent'])
			{
				if (!parent::move_into_another($id_cat, $id_parent))			
				{
					if ($this->check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST))
						return 'e_new_cat_does_not_exist';
					if ($this->check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS))
						return 'e_infinite_loop';
				}
				else
				{
					$Cache->load('faq', RELOAD_CACHE);
					$this->recount_subquestions(FAQ_DO_NOT_GENERATE_CACHE);
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('faq');
			
			return 'e_success';
		}
		else
			return 'e_unexisting_category';
	}
	
	
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);
		if ($result)
			$this->recount_subquestions();
		return $result;
	}
	
	
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, DO_NOT_LOAD_CACHE);
		$this->recount_subquestions($generate_cache);
		return $result;
	}
	
	
	function check_auth($id)
	{
		global $User, $FAQ_CATS, $FAQ_CONFIG;
		$auth_read = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
		$id_cat = $id;

		
		while ($id_cat > 0)
		{
			if (!empty($FAQ_CONFIG[$id_cat]['auth']))
				$auth_read  = $auth_read && $User->check_auth($FAQ_CATS[$id_cat]['auth'], AUTH_READ);
			
			$id_cat = (int)$FAQ_CATS[$id_cat]['id_parent'];
		}
		return $auth_read;
	}
	
	
	function recount_subquestions($generate_cache = true)
	{
		global $Cache, $FAQ_CATS;
		$this->_recount_cat_subquestions($FAQ_CATS, 0);

		if ($generate_cache)
			$Cache->Generate_module_file('faq');
		return;
	}
	
	## Private methods ##
	
	
	function _delete_category_with_content($id)
	{
		global $Sql;
		
		
		if ($test = parent::delete($id))
		{
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "faq WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
	
	
	function _recount_cat_subquestions($FAQ_CATS, $cat_id)
	{
		global $Sql;
		
		$num_subquestions = 0;
		
		foreach ($FAQ_CATS as $id => $value)
		{
			if ($id != 0 && $value['id_parent'] == $cat_id)
				$num_subquestions += $this->_recount_cat_subquestions($FAQ_CATS, $id);
		}
		
		
		if ($cat_id != 0)
		{
			
			$num_subquestions += (int) $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "faq WHERE idcat = '" . $cat_id . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = '" . $num_subquestions . "' WHERE id = '" . $cat_id . "'", __LINE__, __FILE__);
			
			return $num_subquestions;
		}
		return ;
	}
}

?>
