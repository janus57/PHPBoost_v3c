<?php


























define('FORUM_CAT_INCLUDED', true);
define('FORUM_CAT_NO_INCLUDED', false);

class Admin_forum
{	
	
	function Admin_forum() 
	{
	}
	
	
	function move_updown_cat($id, $move)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		$list_parent_cats = $this->get_parent_list($idcat); 
		
		$to = 0;
		if ($move == 'up')
		{	
			
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
			WHERE '" . $CAT_FORUM[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
			if (!empty($switch_id_cat))
			{
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
				
				$Cache->Generate_module_file('forum');
			}		
			elseif (!empty($list_parent_cats) )
			{
				
				$to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
				WHERE id_left < '" . $CAT_FORUM[$id]['id_left'] . "' AND level = '" . ($CAT_FORUM[$id]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
		}
		elseif ($move == 'down')
		{
			
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
			WHERE id_left - '" . $CAT_FORUM[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
			if (!empty($switch_id_cat))
			{
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
				
				$Cache->Generate_module_file('forum');
			}
			elseif (!empty($list_parent_cats) )
			{
				
				$to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
				WHERE id_left > '" . $CAT_FORUM[$id]['id_left'] . "' AND level = '" . ($CAT_FORUM[$id]['level'] - 1) . "'
				ORDER BY id_left" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
				
			}
		}

		if (!empty($to)) 
		{
			
			$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
			$list_cats = $this->get_child_list($id); 
	
			
			if (empty($list_cats))
				return false;
						
			## Dernier topic des enfants du forum à supprimer ##
			$list_parent_cats_to = $this->get_parent_list($to, FORUM_CAT_INCLUDED); 
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $to . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
				
			########## Suppression ##########
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);								
			
			if (!empty($list_parent_cats))
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);

			########## Ajout ##########
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

			
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($f_to, $array_parents_cats) ) 
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'", __LINE__, __FILE__);						
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;						
			}	

			
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}
					
			$Cache->Generate_module_file('forum'); 
			
			$this->update_last_topic_id($id); 
			$this->update_last_topic_id($to); 
		}
		
		return true;
	}
	
	
	function del_cat($idcat, $confirm_delete)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		
		if ($confirm_delete) 
		{
			$first_parent = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > " . $CAT_FORUM[$idcat]['id_right'] . " ORDER BY id_left DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);
			$list_parent_cats = $this->get_parent_list($idcat); 
			
			$nbr_del = $CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] + 1;
			if (!empty($list_parent_cats))
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . $nbr_del . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);	
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . $nbr_del . "', id_right = id_right - '" . $nbr_del . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE idtopic IN (
			SELECT id FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "')", __LINE__, __FILE__); 
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__); 
				
			$Cache->Generate_module_file('forum'); 
			$Cache->load('forum', RELOAD_CACHE); 
			
			$this->update_last_topic_id($first_parent); 
		}
		else 
		{
			
			$f_to = retrieve(POST, 'f_to', 0);
			$f_to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $f_to . "' AND id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
			
			$t_to = retrieve(POST, 't_to', 0);
			$t_to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $t_to . "' AND id <> '" . $idcat . "'", __LINE__, __FILE__);
			
			
			if (!empty($t_to))
			{
				
				$nbr_msg = $Sql->query("SELECT SUM(nbr_msg) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);
				$nbr_msg = !empty($nbr_msg) ? $nbr_msg : 0;
				
				$nbr_topic = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__); 
				$nbr_topic = !empty($nbr_topic) ? $nbr_topic : 0;
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET idcat = '" . $t_to . "' WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);

				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + " . $nbr_msg . ", nbr_topic = nbr_topic + " . $nbr_topic . " WHERE id = '" . $t_to . "'", __LINE__, __FILE__);
				
				
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			}
			
			
			if ($nbr_sub_cat > 0)
			{
				$list_sub_cats = $this->get_child_list($idcat, FORUM_CAT_NO_INCLUDED); 
				$list_parent_cats = $this->get_parent_list($idcat);  
				$list_parent_cats_to = $this->get_parent_list($f_to, FORUM_CAT_INCLUDED);  
				
				
				if (empty($list_sub_cats))
					return false;
					
				########## Suppression ##########
				
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);					
				
				
				if (!empty($list_parent_cats))
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . (2 + $nbr_sub_cat*2) . "', id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
				########## Ajout ##########
				if (!empty($f_to)) 
				{
					if (empty($list_parent_cats_to))
						$clause_parent_cats_to = " id = '" . $f_to . "'";
					else
						$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
					
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
					
					
					$array_parents_cats = explode(', ', $list_parent_cats);
					if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$f_to]['id_left'] && !in_array($f_to, $array_parents_cats)) 
					{	
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$f_to]['id_right'] . "'", __LINE__, __FILE__);						
						$limit = $CAT_FORUM[$f_to]['id_right'];
						$end = $limit + ($nbr_sub_cat*2) - 1;
					}
					else
					{	
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2)) . "'", __LINE__, __FILE__);
						$limit = $CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2);
						$end = $limit + ($nbr_sub_cat*2) - 1;						
					}
					
					
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
						$z++;
					}								

					
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level']) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
				}
				else 
				{
					$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
					
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					$limit = $max_id + 1;
					$end = $limit + ($nbr_sub_cat*2) - 1;	
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
						$z++;
					}		
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level'] + 1) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
				}
			}
			else 
			{
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - 2 WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - 2, id_right = id_right - 2 WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('forum'); 
			$Cache->load('forum', RELOAD_CACHE); 
			
			$this->update_last_topic_id($idcat); 
			$this->update_last_topic_id($f_to); 
			$this->update_last_topic_id($t_to); 
		}
		
		return true;
	}
	
	
	function move_cat($id, $to)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		
		$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
		
		$list_cats = $this->get_child_list($id); 
		$list_parent_cats = $this->get_parent_list($id);  
		
		
		if (empty($list_cats))
			redirect(HOST . SCRIPT);

		########## Suppression ##########
		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);	
		
		if (!empty($list_parent_cats))
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
		
		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
		
		########## Ajout ##########
		if (!empty($to)) 
		{
			
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($to, $array_parents_cats)) 
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'", __LINE__, __FILE__);						
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;
			}	
			
			
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}
			
			
			$Cache->Generate_module_file('forum');
			$Cache->load('forum', RELOAD_CACHE); 
			
			$list_parent_cats_to = $this->get_parent_list($to, FORUM_CAT_INCLUDED); 
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $to . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
			
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . (($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) - 1) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);
			
			$Cache->Generate_module_file('forum');
			$Cache->load('forum', RELOAD_CACHE); 
			
			$this->update_last_topic_id($id); 
			$this->update_last_topic_id($to); 
			
			return true;
		}
		else 
		{
			$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
			
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			$limit = $max_id + 1;
			$end = $limit + ($nbr_cat*2) - 1;	
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}		
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);		
		}
		
		$Cache->Generate_module_file('forum');
		return true;
	}
	
	
	function get_parent_list($idcat, $cat_include = false)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = $cat_include ? "WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] . "'" : "WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "'";
		
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id
		FROM " . PREFIX . "forum_cats 
		" . $clause, __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$list_parent_cats .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');

		return $list_parent_cats;
	}
	
	
	function get_child_list($id, $cat_include = true)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = $cat_include ? "WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'" : "WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "' AND id != '" . $id . "'";
		
		$list_cats = '';
		$result = $Sql->query_while("SELECT id
		FROM " . PREFIX . "forum_cats 
		" . $clause . "
		ORDER BY id_left", __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
			$list_cats .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_cats = trim($list_cats, ', ');
		
		return $list_cats;
	}
	
	
	function update_last_topic_id($idcat)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) 
		{
			
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats 
			WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);

			$clause = !empty($list_cats) ? "idcat IN (" . trim($list_cats, ', ') . ")" : "1";
		}
		
		
		$last_timestamp = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE " . $clause, __LINE__, __FILE__);
		$last_topic_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . $last_timestamp . "'", __LINE__, __FILE__);
		if (!empty($last_topic_id))
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
		
		if ($CAT_FORUM[$idcat]['level'] > 1) 
		{	
			
			$idcat_parent = $Sql->query("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "' AND level = '" .  ($CAT_FORUM[$idcat]['level'] - 1) . "'", __LINE__, __FILE__);

			$this->update_last_topic_id($idcat_parent); 
		}
	}
}

?>
