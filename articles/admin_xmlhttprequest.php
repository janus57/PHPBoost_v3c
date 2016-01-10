<?php
header('Content-type: text/html; charset=iso-8859-15');

require_once('../kernel/begin.php');
define('TITLE', 'Ajax articles');
require_once('../kernel/header_no_display.php');

$Cache->load('articles');

$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);
$id = retrieve(GET, 'id', 0);
$get_parent_up = retrieve(GET, 'g_up', 0);
$get_parent_down = retrieve(GET, 'g_down', 0);

//R�cup�ration de la cat�gorie d'�change.
if (!empty($get_parent_up))
{
	$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE '" . $CAT_ARTICLES[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
	if (!empty($switch_id_cat))
		echo $switch_id_cat;
	else
	{	
		//Articless parentes de l'articles � supprimer.
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "articles_cats 
		WHERE id_left < '" . $CAT_ARTICLES[$get_parent_up]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');
		
		if (!empty($list_parent_cats))
		{
			//Changement de cat�gorie.
			$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
			WHERE id_left < '" . $CAT_ARTICLES[$get_parent_up]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$get_parent_up]['level'] - 1) . "' AND
			id NOT IN (" . $list_parent_cats . ")
			ORDER BY id_left DESC" . 
			$Sql->limit(0, 1), __LINE__, __FILE__);
			if (isset($CAT_ARTICLES[$change_cat]))
			{	
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats 
				WHERE id_left > '" . $CAT_ARTICLES[$change_cat]['id_right'] . "'
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
	$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE id_left - '" . $CAT_ARTICLES[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
	if (!empty($switch_id_cat))
		echo $switch_id_cat;
	else
	{	
		$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
		WHERE id_left > '" . $CAT_ARTICLES[$get_parent_down]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$get_parent_down]['level'] - 1) . "'
		ORDER BY id_left" . 
		$Sql->limit(0, 1), __LINE__, __FILE__);
		if (isset($CAT_ARTICLES[$change_cat]))
		{	
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats 
			WHERE id_left < '" . $CAT_ARTICLES[$change_cat]['id_right'] . "'
			ORDER BY id_left DESC" . 
			$Sql->limit(0, 1), __LINE__, __FILE__);
		}
		if (!empty($switch_id_cat))
			echo 's' . $switch_id_cat;
	}	
}

//D�placement.
if (!empty($move) && !empty($id))
{
	//Si la cat�gorie existe, et d�placement possible
	if (array_key_exists($id, $CAT_ARTICLES))
	{
		//Articless parentes de l'articles � d�placer.
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "articles_cats 
		WHERE id_left < '" . $CAT_ARTICLES[$id]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');
		
		$to = 0;
		if ($move == 'up')
		{	
			//M�me cat�gorie
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
			WHERE '" . $CAT_ARTICLES[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
			if (!empty($switch_id_cat))
			{
				//On monte la cat�gorie � d�placer, on lui assigne des id n�gatifs pour assurer l'unicit�.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left + '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
				//On descend la cat�gorie cible.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$switch_id_cat]['id_left'] . "' AND '" . $CAT_ARTICLES[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				//On r�tablit les valeurs absolues.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
				
				$Cache->Generate_module_file('articles');
			}		
			elseif (!empty($list_parent_cats) )
			{
				//Changement de cat�gorie.
				$to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
				WHERE id_left < '" . $CAT_ARTICLES[$id]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$id]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
		}
		elseif ($move == 'down')
		{
			//Doit-on changer de cat�gorie parente ou non ?
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
			WHERE id_left - '" . $CAT_ARTICLES[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
			if (!empty($switch_id_cat))
			{
				//On monte la cat�gorie � d�placer, on lui assigne des id n�gatifs pour assurer l'unicit�.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left - '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
				//On descend la cat�gorie cible.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$switch_id_cat]['id_left'] . "' AND '" . $CAT_ARTICLES[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				//On r�tablit les valeurs absolues.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
				
				$Cache->Generate_module_file('articles');
			}
			elseif (!empty($list_parent_cats) )
			{
				//Changement de cat�gorie.
				$to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
				WHERE id_left > '" . $CAT_ARTICLES[$id]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$id]['level'] - 1) . "'
				ORDER BY id_left" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
		}

		if (!empty($to)) //Changement de cat�gorie possible?
		{
			//On v�rifie si la cat�gorie contient des sous articless.
			$nbr_cat = (($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] - 1) / 2) + 1;
		
			//Sous articless de l'articles � d�placer.
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "articles_cats 
			WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_cats = trim($list_cats, ', ');
		
			if (empty($list_cats))
				$clause_cats = " id = '" . $id . "'";
			else
				$clause_cats = " id IN (" . $list_cats . ")";
				
			//R�cup�ration du nombre d'images de l'articles.
			$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
			$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			//Articless parentes de l'articles cible.
			$list_parent_cats_to = '';
			$result = $Sql->query_while("SELECT id, level 
			FROM " . PREFIX . "articles_cats 
			WHERE id_left <= '" . $CAT_ARTICLES[$to]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
				$list_parent_cats_to .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_parent_cats_to = trim($list_parent_cats_to, ', ');
		
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $to . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
				
			########## Suppression ##########
			//On supprime virtuellement (changement de signe des bornes) les enfants.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left, id_right = - id_right WHERE " . $clause_cats, __LINE__, __FILE__);					
			//On modifie les bornes droites des parents.
			if (!empty($list_parent_cats))
			{
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible - '" . $nbr_articles_visible . "', nbr_articles_unvisible = nbr_articles_unvisible - '" . $nbr_articles_unvisible . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			}
			
			//On r�duit la taille de l'arbre du nombre de articless supprim�es � partir de la position de celui-ci.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);

			########## Ajout ##########
			//On modifie les bornes droites des parents de la cible.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right + '" . ($nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible + '" . $nbr_articles_visible . "', nbr_articles_unvisible = nbr_articles_unvisible + '" . $nbr_articles_unvisible . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

			//On augmente la taille de l'arbre du nombre de articless supprim�es � partir de la position de l'articles cible.
			if ($CAT_ARTICLES[$id]['id_left'] > $CAT_ARTICLES[$to]['id_left'] ) //Direction articles source -> articles cible.
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);						
				$limit = $CAT_ARTICLES[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
				$limit = $CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;						
			}	

			//On replace les articless supprim�es virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$id]['id_left']);
				$id_right = $end - ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}
			
			$Cache->Generate_module_file('articles');
		}
		
		//G�n�ration de la liste des cat�gories en cache.
		$list_cats_js = '';
		$array_js = '';	
		$i = 0;
		$result = $Sql->query_while("SELECT id, id_left, id_right
		FROM " . PREFIX . "articles_cats 
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


?>