<?php
/*##################################################
 *                               explorer.php
 *                            -------------------
 *   begin                : May 31, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
load_module_lang('wiki');

define('TITLE', $LANG['wiki'] . ': ' . $LANG['wiki_explorer']);
define('ALTERNATIVE_CSS', 'wiki');


$bread_crumb_key = 'wiki_explorer';
require_once('../wiki/wiki_bread_crumb.php');

$cat = retrieve(GET, 'cat', 0);

require_once('../kernel/header.php');


$Template->set_filenames(array('wiki_explorer'=> 'wiki/explorer.tpl'));

//Contenu de la racine:
$Cache->load('wiki');
$root = '';
foreach ($_WIKI_CATS as $key => $value)
{
	if ($value['id_parent'] == 0)
		$root .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);">' . $value['name'] . '</a></td></tr>';
}
$result = $Sql->query_while("SELECT title, id, encoded_title
	FROM " . PREFIX . "wiki_articles a
	WHERE id_cat = 0
	AND a.redirect = 0
	ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	$root .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('wiki') . '/images/article.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="' . url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a></td></tr>';
}
$Sql->query_close($result);


$Template->assign_vars(array(
	'WIKI_PATH' => $Template->get_module_data_path('wiki'),
	'TITLE' => $LANG['wiki_explorer'],
	'L_ROOT' => $LANG['wiki_root'],
	'SELECTED_CAT' => $cat > 0 ? $cat : 0,
	'ROOT_CONTENTS' => $root,
	'L_CATS' => $LANG['wiki_cats_tree'],
));

$contents = '';
$result = $Sql->query_while("SELECT c.id, a.title, a.encoded_title
FROM " . PREFIX . "wiki_cats c
LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
WHERE c.id_parent = 0
ORDER BY title ASC", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
	if ($sub_cats_number > 0)
	{	
		$Template->assign_block_vars('list', array(
			'DIRECTORY' => '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $Template->get_module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '"  style="vertical-align:middle" /></a> 
			<a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" id ="img_' . $row['id'] . '" alt="" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
		));
	}
	else
	{
		$Template->assign_block_vars('list', array(
			'DIRECTORY' => '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
		));
	}
}
$Sql->query_close($result);
$Template->assign_vars(array(
	'SELECTED_CAT' => 0,
	'CAT_0' => 'wiki_selected_cat',
	'CAT_LIST' => '',
	'CURRENT_CAT' => $LANG['wiki_no_selected_cat']
));

$Template->pparse('wiki_explorer');


require_once('../kernel/footer.php');

?>