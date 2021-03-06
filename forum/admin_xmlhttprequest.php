<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if ($User->check_level(ADMIN_LEVEL)) 
{			
	$Session->csrf_get_protect(); 
	
	$Cache->load('forum');

	$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);
	$id = retrieve(GET, 'id', 0);
	$get_parent_up = retrieve(GET, 'g_up', 0);
	$get_parent_down = retrieve(GET, 'g_down', 0);

	
	if (!empty($get_parent_up))
	{
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE '" . $CAT_FORUM[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < '" . $CAT_FORUM[$get_parent_up]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_parent_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			if (!empty($list_parent_cats))
			{
				
				$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
				WHERE id_left < '" . $CAT_FORUM[$get_parent_up]['id_left'] . "' AND level = '" . ($CAT_FORUM[$get_parent_up]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
				if (isset($CAT_FORUM[$change_cat]))
				{	
					$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats 
					WHERE id_left > '" . $CAT_FORUM[$change_cat]['id_right'] . "'
					ORDER BY id_left" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
				if (!empty($switch_id_cat))
					echo 's' . $switch_id_cat;
			}
		}	
	}
	elseif (!empty($get_parent_down))
	{
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id_left - '" . $CAT_FORUM[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
			WHERE id_left > '" . $CAT_FORUM[$get_parent_down]['id_left'] . "' AND level = '" . ($CAT_FORUM[$get_parent_down]['level'] - 1) . "'
			ORDER BY id_left" . 
			$Sql->limit(0, 1), __LINE__, __FILE__);
			if (isset($CAT_FORUM[$change_cat]))
			{	
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats 
				WHERE id_left < '" . $CAT_FORUM[$change_cat]['id_right'] . "'
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
			if (!empty($switch_id_cat))
				echo 's' . $switch_id_cat;
		}	
	}

	
	if (!empty($move) && !empty($id))
	{
		
		if (array_key_exists($id, $CAT_FORUM))
		{
			
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < '" . $CAT_FORUM[$id]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_parent_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
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
			
				
				$list_cats = '';
				$result = $Sql->query_while("SELECT id
				FROM " . PREFIX . "forum_cats 
				WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'
				ORDER BY id_left", __LINE__, __FILE__);
				
				while ($row = $Sql->fetch_assoc($result))
					$list_cats .= $row['id'] . ', ';
				
				$Sql->query_close($result);
				$list_cats = trim($list_cats, ', ');
			
				
				if (empty($list_cats))
					redirect(HOST . SCRIPT);
					
				
				if (!empty($list_parent_cats))
				{
					$max_timestamp_parent = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE idcat IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
					$max_topic_id_parent = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . $max_timestamp_parent . "'", __LINE__, __FILE__); 
				}
				
				## Dernier topic des enfants du forum � supprimer ##
				
				$list_parent_cats_to = '';
				$result = $Sql->query_while("SELECT id, level 
				FROM " . PREFIX . "forum_cats 
				WHERE id_left <= '" . $CAT_FORUM[$to]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$to]['id_right'] . "'", __LINE__, __FILE__);
				
				while ($row = $Sql->fetch_assoc($result))
					$list_parent_cats_to .= $row['id'] . ', ';
				
				$Sql->query_close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
				if (empty($list_parent_cats_to))
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
				
				$max_timestamp = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE idcat IN (" . $list_cats . ")", __LINE__, __FILE__);
				if (empty($list_parent_cats_to))
					$max_timestamp_to = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $to . "'", __LINE__, __FILE__); 
				else
					$max_timestamp_to = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE idcat IN (" . $list_parent_cats_to . ")", __LINE__, __FILE__);
				
				$max_topic_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . max($max_timestamp, $max_timestamp_to) . "'", __LINE__, __FILE__);

				########## Suppression ##########
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);					
				
				
				if (!empty($list_parent_cats))
				{
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . numeric($max_topic_id_parent) . "', id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				}
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);

				########## Ajout ##########
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				
				if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] ) 
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
						
				
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . numeric($max_topic_id) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
				
				$Cache->Generate_module_file('forum');
			}
			
			
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = $Sql->query_while("SELECT id, id_left, id_right
			FROM " . PREFIX . "forum_cats 
			ORDER BY id_left", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_cats_js .= $row['id'] . ', ';		
				$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";";
				$i++;
			}
			$Sql->query_close($result);
			echo 'list_cats = new Array(' . trim($list_cats_js, ', ') . ');' . $array_js;
		}	
	}
}

?>
