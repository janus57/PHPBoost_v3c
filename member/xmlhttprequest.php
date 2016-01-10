<?php
header('Content-type: text/html; charset=iso-8859-15');

define('NO_SESSION_LOCATION', true); //Ne r�actualise pas l'emplacement du visiteur/membre
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

$id_cat = retrieve(POST, 'id_cat', 0);
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = retrieve(POST, 'selected_cat', 0);
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = retrieve(POST, 'open_cat', 0);
$root = !empty($_GET['root']) ? 1 : 0;


//Listage des r�pertoires dont le r�pertoire parent est connu
if ($id_cat != 0)
{
	echo '<ul style="margin:0;padding:0;list-style-type:none;padding-left:30px;">';
	//On s�lectionne les r�petoires dont l'id parent est connu
	$result = $Sql->query_while("SELECT id, id_parent, name
	FROM " . PREFIX . "upload_cat
	WHERE id_parent = " . $id_cat . "
	ORDER BY name ASC", __LINE__, __FILE__);
	$nbr_subcats = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id_parent = '" . $id_cat. "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On compte le nombre de cat�gories pr�sentes pour savoir si on donne la possibilit� de faire un sous dossier
		$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
		//Si cette cat�gorie contient des sous cat�gories, on propose de voir son contenu
		if ($sub_cats_number > 0)
			echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/plus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . get_utheme() . '/images/upload/closed_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
		else //Sinon on n'affiche pas le "+"
			echo '<li style="padding-left:17px;"><img src="../templates/' . get_utheme() . '/images/upload/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
	}
	$Sql->query_close($result);
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif ($select_cat && empty($open_cat) && $root == 0)
{
	if ($selected_cat > 0)
	{
		$localisation = array();
		$Cache->load('wiki');
		$id = $selected_cat; //Permier id
		do
		{
			$localisation[] = isset($_WIKI_CATS[$id]['name']) ? $_WIKI_CATS[$id]['name'] : '';
			$id = isset($_WIKI_CATS[$id]['id_parent']) ? (int)$_WIKI_CATS[$id]['id_parent'] : 0;
		}	
		while ($id > 0);
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('wiki');
		echo $LANG['wiki_no_selected_cat'];
	}
}

require_once('../kernel/footer_no_display.php');
?>