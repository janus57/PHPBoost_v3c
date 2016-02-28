<?php



























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if ($User->check_level(ADMIN_LEVEL)) 
{	
	$Cache->load('gallery');
	$Session->csrf_get_protect(); 
	
	$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;
	$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
	$get_parent_up = !empty($_GET['g_up']) ? numeric($_GET['g_up']) : 0;
	$get_parent_down = !empty($_GET['g_down']) ? numeric($_GET['g_down']) : 0;

	
	if (!empty($get_parent_up))
	{
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats WHERE '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' AND id_right > '" . $CAT_GALLERY[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			if (!empty($list_parent_cats))
			{
				
				$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE id_left < '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$get_parent_up]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
				if (isset($CAT_GALLERY[$change_cat]))
				{	
					$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats 
					WHERE id_left > '" . $CAT_GALLERY[$change_cat]['id_right'] . "'
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
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats WHERE id_left - '" . $CAT_GALLERY[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
			WHERE id_left > '" . $CAT_GALLERY[$get_parent_down]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$get_parent_down]['level'] - 1) . "'
			ORDER BY id_left" . 
			$Sql->limit(0, 1), __LINE__, __FILE__);
			if (isset($CAT_GALLERY[$change_cat]))
			{	
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats 
				WHERE id_left < '" . $CAT_GALLERY[$change_cat]['id_right'] . "'
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
			if (!empty($switch_id_cat))
				echo 's' . $switch_id_cat;
		}	
	}

	
	if (!empty($move) && !empty($id))
	{
		
		if (array_key_exists($id, $CAT_GALLERY))
		{
			
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < '" . $CAT_GALLERY[$id]['id_left'] . "' AND id_right > '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			$to = 0;
			if ($move == 'up')
			{	
				
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE '" . $CAT_GALLERY[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
				if (!empty($switch_id_cat))
				{
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left + '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$switch_id_cat]['id_left'] . "' AND '" . $CAT_GALLERY[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
					
					$Cache->Generate_module_file('gallery');
				}		
				elseif (!empty($list_parent_cats) )
				{
					
					$to = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left < '" . $CAT_GALLERY[$id]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$id]['level'] - 1) . "' AND
					id NOT IN (" . $list_parent_cats . ")
					ORDER BY id_left DESC" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
			}
			elseif ($move == 'down')
			{
				
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE id_left - '" . $CAT_GALLERY[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
				if (!empty($switch_id_cat))
				{
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left - '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$switch_id_cat]['id_left'] . "' AND '" . $CAT_GALLERY[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
					
					$Cache->Generate_module_file('gallery');
				}
				elseif (!empty($list_parent_cats) )
				{
					
					$to = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left > '" . $CAT_GALLERY[$id]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$id]['level'] - 1) . "'
					ORDER BY id_left" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
			}

			if (!empty($to)) 
			{
				
				$nbr_cat = (($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] - 1) / 2) + 1;
			
				
				$list_cats = '';
				$result = $Sql->query_while("SELECT id
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'
				ORDER BY id_left", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					$list_cats .= $row['id'] . ', ';
				}
				$Sql->query_close($result);
				$list_cats = trim($list_cats, ', ');
			
				if (empty($list_cats))
					$clause_cats = " id = '" . $id . "'";
				else
					$clause_cats = " id IN (" . $list_cats . ")";
					
				
				$nbr_pics_aprob = $Sql->query("SELECT nbr_pics_aprob FROM " . PREFIX . "gallery_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
				$nbr_pics_unaprob = $Sql->query("SELECT nbr_pics_unaprob FROM " . PREFIX . "gallery_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
				
				
				$list_parent_cats_to = '';
				$result = $Sql->query_while("SELECT id, level 
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left <= '" . $CAT_GALLERY[$to]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$to]['id_right'] . "'", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					$list_parent_cats_to .= $row['id'] . ', ';
				}
				$Sql->query_close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
				if (empty($list_parent_cats_to))
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
				########## Suppression ##########
				
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left, id_right = - id_right WHERE " . $clause_cats, __LINE__, __FILE__);					
				
				if (!empty($list_parent_cats))
				{
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "', nbr_pics_aprob = nbr_pics_aprob - '" . $nbr_pics_aprob . "', nbr_pics_unaprob = nbr_pics_unaprob - '" . $nbr_pics_unaprob . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				}
				
				
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);

				########## Ajout ##########
				
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + '" . ($nbr_cat*2) . "', nbr_pics_aprob = nbr_pics_aprob + '" . $nbr_pics_aprob . "', nbr_pics_unaprob = nbr_pics_unaprob + '" . $nbr_pics_unaprob . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				
				if ($CAT_GALLERY[$id]['id_left'] > $CAT_GALLERY[$to]['id_left'] ) 
				{	
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_GALLERY[$to]['id_right'] . "'", __LINE__, __FILE__);						
					$limit = $CAT_GALLERY[$to]['id_right'];
					$end = $limit + ($nbr_cat*2) - 1;
				}
				else
				{	
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_GALLERY[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
					$limit = $CAT_GALLERY[$to]['id_right'] - ($nbr_cat*2);
					$end = $limit + ($nbr_cat*2) - 1;						
				}	

				
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_GALLERY[$array_sub_cats[$z]]['id_left'] - $CAT_GALLERY[$id]['id_left']);
					$id_right = $end - ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$array_sub_cats[$z]]['id_right']);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
				
				$Cache->Generate_module_file('gallery');
			}
			
			
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = $Sql->query_while("SELECT id, id_left, id_right
			FROM " . PREFIX . "gallery_cats 
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
