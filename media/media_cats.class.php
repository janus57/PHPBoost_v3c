<?php



























import('content/categories_manager');
require_once PATH_TO_ROOT . '/media/media_constant.php';

define('MEDIA_DO_NOT_GENERATE_CACHE', false);

class MediaCats extends CategoriesManager
{
	## Public methods ##

	
	function MediaCats()
	{
		global $Cache, $MEDIA_CATS;
		
		if (!isset($MEDIA_CATS))
			$Cache->load('media');
		
		parent::CategoriesManager('media_cat', 'media', $MEDIA_CATS);
	}

	
	function Delete_category_recursively($id)
	{
		
		$this->delete_category_with_content($id);
		
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
			{
				$this->Delete_category_recursively($id_cat);
			}
		}

		$this->recount_media_per_cat();
	}

	
	function Delete_category_and_move_content($id_category, $new_id_cat_content)
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
			{
				parent::move_into_another($id_cat, $new_id_cat_content);
			}
		}

		$Sql->query_inject("UPDATE ".PREFIX."media SET idcat = '" . $new_id_cat_content . "' WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);

		$this->recount_media_per_cat();

		return true;
	}

	
	function add($id_parent, $name, $description, $image, $new_auth, $mime_type, $activ)
	{
		global $Sql;

		if (array_key_exists($id_parent, $this->cache_var))
		{
			if (empty($image))
			{
				if ($mime_type == MEDIA_TYPE_MUSIC)
				{
					$image = '../media/templates/images/audio.png';
				}
				elseif ($mime_type == MEDIA_TYPE_VIDEO)
				{
					$image = '../media/templates/images/video.png';
				}
				else
				{
					$image = '../media/media.png';
				}
			}

			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE ".PREFIX."media_cat SET description = '" . $description . "', image = '" . $image . "', auth = '" . $new_auth . "', mime_type = '" . $mime_type . "', active = '" . $activ . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			
			return 'e_success';
		}
		else
		{
			return 'e_unexisting_cat';
		}
	}

	
	function Update_category($id_cat, $id_parent, $name, $description, $image, $new_auth, $mime_type, $activ)
	{
		global $Sql, $Cache;

		if (array_key_exists($id_cat, $this->cache_var))
		{
			if ($id_parent != $this->cache_var[$id_cat]['id_parent'])
			{
				if (!parent::move_into_another($id_cat, $id_parent))
				{
					if ($this->check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST))
					{
						return 'e_new_cat_does_not_exist';
					}
					if ($this->check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS))
					{
						return 'e_infinite_loop';
					}
				}
				else
				{
					$Cache->load('media', RELOAD_CACHE);
					$this->recount_media_per_cat(MEDIA_DO_NOT_GENERATE_CACHE);
				}
			}
			
			if (empty($image))
			{
				if ($mime_type == MEDIA_TYPE_MUSIC)
				{
					$image = '../media/templates/images/audio.png';
				}
				elseif ($mime_type == MEDIA_TYPE_VIDEO)
				{
					$image = '../media/templates/images/video.png';
				}
				else
				{
					$image = '../media/media.png';
				}
			}

			$Sql->query_inject("UPDATE ".PREFIX."media_cat SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "', auth = '" . $new_auth . "', mime_type = '" . $mime_type . "', active = '" . $activ . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('media');

			return 'e_success';
		}
		else
		{
			return 'e_unexisting_category';
		}
	}

	
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);

		if ($result)
		{
			$this->recount_media_per_cat();
		}

		return $result;
	}

	
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, DO_NOT_LOAD_CACHE);

		$this->recount_media_per_cat($generate_cache);

		return $result;
	}

	
	function recount_media_per_cat($id = null, $num = null, $generate_cache = true)
	{
		global $Sql, $Cache, $MEDIA_CATS;

		if (!empty($MEDIA_CATS))
		{
			if (!function_exists('array_fill_keys'))
			{
				$num_media = array();
   				$array_keys = array_keys($MEDIA_CATS);
   				
   				foreach ($array_keys as $idkey)
   				{
   					$num_media[$idkey] = 0;
   				}
			}
			else
			{
				$num_media = array_fill_keys(array_keys($MEDIA_CATS), 0);
			}
		}

		if (is_null($id))
		{
			$result = $Sql->query_while("SELECT id, idcat FROM ".PREFIX."media WHERE infos = '" . MEDIA_STATUS_APROBED . "' ORDER BY idcat", __LINE__, __FILE__);

			while ($row = $Sql->fetch_assoc($result))
			{
				if (!empty($MEDIA_CATS[$row['idcat']]))
				{
					$num_media[$row['idcat']]++;
				}
			}

			$Sql->query_close($result);

			if (!empty($MEDIA_CATS[0]) && $MEDIA_CATS[0]['num_media'] != $num_media[0])
			{
				$config = $Sql->query_array(PREFIX . 'configs', 'value', "WHERE name = 'media'", __LINE__, __FILE__);
				$config = unserialize($config['value']);
				$config['root']['num_media'] = $num_media[0];

				$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'media'", __LINE__, __FILE__);
			}

			if (!empty($num_media))
			{
				foreach ($num_media as $idcat => $number)
				{
					if ($idcat != 0 && $MEDIA_CATS[$idcat] != $number)
					{
						$Sql->query_inject("UPDATE ".PREFIX."media_cat SET num_media = '" . $number . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
					}
				}
			}
		}
		else
		{
			if (is_null($num))
			{
				$num = (int) $Sql->query("SELECT COUNT(*) FROM ".PREFIX."media WHERE idcat = '" . $id . "' AND infos = '" . MEDIA_STATUS_APROBED . "'", __LINE__, __FILE__);
			}

			if ($id > 0)
			{
				$Sql->query_inject("UPDATE ".PREFIX."media_cat SET num_media = '" . $num . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
			}
			else
			{
				$config = $Sql->query_array(PREFIX . 'configs', 'value', "WHERE name = 'media'", __LINE__, __FILE__);
				$config = unserialize($config['value']);
				$config['root']['num_media'] = $num;

				$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'media'", __LINE__, __FILE__);
			}
		}

		if ($generate_cache)
		{
			$Cache->Generate_module_file('media');
		}

		return true;
	}

	## Private methods ##

	
	function delete_category_with_content($id)
	{
		global $Sql;

		
		if (parent::delete($id))
		{
			
			$Sql->query_inject("DELETE FROM ".PREFIX."media WHERE idcat = '" . $id . "'", __LINE__, __FILE__);

			return true;
		}
		else
		{
			return false;
		}
	}
}

?>
