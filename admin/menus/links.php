<?php
/*##################################################
 *                           admin_link_menus.php
 *                            -------------------
 *   begin                : November, 13 2008
 *   copyright            : (C) 2008 Lo�c Rouchon
 *   email                : horn@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

import('core/menu_service');

$menu_id = retrieve(REQUEST, 'id', 0);
$action = retrieve(GET, 'action', '');

if ($action == 'save')
{   // Save a Menu (New / Edit)
    $menu_uid = retrieve(POST, 'menu_uid', 0);
    
	//Properties of the menu we are creating/editing
	$type = retrieve(POST, 'menu_element_' . $menu_uid . '_type', VERTICAL_MENU);
    
    function build_menu_from_form(&$elements_ids, $level = 0)
    {
        $menu = null;
        $menu_element_id = $elements_ids['id'];
        $menu_name = retrieve(POST, 'menu_element_' . $menu_element_id . '_name', '', TSTRING_UNCHANGE);
        $menu_url = retrieve(POST, 'menu_element_' . $menu_element_id . '_url', '');
        $menu_image = retrieve(POST, 'menu_element_' . $menu_element_id . '_image', '');
        
    	$array_size = count($elements_ids);
    	if ($array_size == 1 && $level > 0)
    	{   // If it's a menu, there's only one element;
    		$menu = new LinksMenuLink($menu_name, $menu_url, $menu_image);
    	}
    	else
    	{
            $menu = new LinksMenu($menu_name, $menu_url, $menu_image);
    		
            // We unset the id key of the array
    		unset($elements_ids['id']);
    		
    		$array_size = count($elements_ids);
    		for ($i = 0; $i < $array_size; $i++)
	    	{	// We build all its children and add it to its father
	    		$menu->add(build_menu_from_form($elements_ids[$i], $level + 1));
	    	}
    	}
        
        $menu->set_auth(Authorizations::build_auth_array_from_form(
            AUTH_MENUS, 'menu_element_' . $menu_element_id . '_auth')
        );
    	return $menu;
    }
    
    // We build the array representing the tree
    $result = array();
    parse_str('tree=' . retrieve(POST, 'menu_tree', ''), $result);
    
    // We build the tree
    // The parsed tree is not absolutely regular, we correct it
    $id_first_menu = preg_replace('`[^=]*=([0-9]+)`isU', '$1', $result['tree']);
    
    // Correcting the first item
    if (!empty($id_first_menu))
    {   // This menu contains item
        $menus =& $result['amp;menu_element_' . $menu_uid . '_list'];
	    if (!empty($menus[0]))
	    {   // The first item is a sub menu
	        $menus[0] = array_merge(
		        array('id' => $id_first_menu),
		        $menus[0]
		    );
	    }
	    else
	    {   // The first item is a link
	        $menus[0] = array('id' => $id_first_menu);
	    }
	    ksort($menus);  // Sort the menus to get the first at the beginning
	    // Adding the root element
	    $menu_tree = array_merge(
	        array('id' => $menu_uid),
	        $menus
	    );
    }
    else
    {   // There no item in this menu
        $menu_tree = array('id' => $menu_uid);
    }
    // We build the menu
    $menu = build_menu_from_form($menu_tree);
    $menu->set_type($type);
    
    $previous_menu = null;
    //If we edit the menu
    if ($menu_id > 0)
    {   // Edit the Menu
        $menu->id($menu_id);
        $previous_menu = MenuService::load($menu_id);
    }
    
    //Menu enabled?
    $menu->enabled(retrieve(POST, 'menu_element_' . $menu_uid . '_enabled', MENU_NOT_ENABLED));
    $menu->set_block(retrieve(POST, 'menu_element_' . $menu_uid . '_location', BLOCK_POSITION__NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(
        AUTH_MENUS, 'menu_element_' . $menu_uid . '_auth'
    ));
    
    if ($menu->is_enabled())
    {
        if ($previous_menu != null && $menu->get_block() == $previous_menu->get_block())
        {   // Save the menu if enabled
            $menu->set_block_position($previous_menu->get_block_position());
            MenuService::save($menu);
        }
        else
        {   // Move the menu to its new location and save it
            MenuService::move($menu, $menu->get_block());
        }
    }
    else
    {   // The menu is not enabled, we only save it with its block location
        // When enabling it, the menu will be moved to this block location
        $block = $menu->get_block();
        // Disable the menu and move it to the disabled position computing new positions
        MenuService::move($menu, BLOCK_POSITION__NOT_ENABLED);
        
        // Restore its position and save it
        $menu->set_block($block);
        MenuService::save($menu);
    }
   	MenuService::generate_cache();
    redirect('menus.php#m' . $menu->get_id());
}

// Display the Menu administration

include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/links.tpl');

$tpl->assign_vars(array(
	'L_REQUIRE_TITLE' => $LANG['require_title'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_NAME' => $LANG['name'],
	'L_URL' => $LANG['url'],
	'L_IMAGE' => $LANG['img'],
	'L_STATUS' => $LANG['status'],
	'L_AUTHS' => $LANG['auths'],
	'L_ENABLED' => $LANG['enabled'],
	'L_DISABLED' => $LANG['disabled'],
	'L_ACTIVATION' => $LANG['activation'],
	'L_GUEST' => $LANG['guest'],
	'L_USER' => $LANG['member'],
	'L_MODO' => $LANG['modo'],
	'L_ADMIN' => $LANG['admin'],
	'L_LOCATION' => $LANG['location'],
	'L_ACTION_MENUS' => ($menu_id > 0) ? $LANG['menus_edit'] : $LANG['add'],
	'L_ACTION' => ($menu_id > 0) ? $LANG['update'] : $LANG['submit'],
	'L_RESET' => $LANG['reset'],
    'ACTION' => 'save',
    'L_TYPE' => $LANG['type'],
    'L_CONTENT' => $LANG['content'],
    'L_AUTHORIZATIONS' => $LANG['authorizations'],
    'L_ADD' => $LANG['add'],
    'J_AUTH_FORM' => to_js_string(Authorizations::generate_select(
        AUTH_MENUS, array('r-1' => AUTH_MENUS, 'r0' => AUTH_MENUS, 'r1' =>AUTH_MENUS),
        array(), 'menu_element_##UID##_auth'
     )),
    'JL_AUTHORIZATIONS' => to_js_string($LANG['authorizations']),
    'JL_PROPERTIES' => to_js_string($LANG['properties']),
    'JL_NAME' => to_js_string($LANG['name']),
    'JL_URL' => to_js_string($LANG['url']),
    'JL_IMAGE' => to_js_string($LANG['img']),
    'JL_DELETE_ELEMENT' => to_js_string($LANG['confirm_delete_element']),
    'JL_MORE' => to_js_string($LANG['more_details']),
    'JL_DELETE' => to_js_string($LANG['delete']),
    'JL_ADD_SUB_ELEMENT' => to_js_string($LANG['add_sub_element']),
    'JL_ADD_SUB_MENU' => to_js_string($LANG['add_sub_menu']),
));

//Localisation possibles.
$block = BLOCK_POSITION__HEADER;
$array_location = array(
    BLOCK_POSITION__HEADER => $LANG['menu_header'],
    BLOCK_POSITION__SUB_HEADER => $LANG['menu_subheader'],
    BLOCK_POSITION__LEFT => $LANG['menu_left'],
    BLOCK_POSITION__TOP_CENTRAL => $LANG['menu_top_central'],
    BLOCK_POSITION__BOTTOM_CENTRAL => $LANG['menu_bottom_central'],
    BLOCK_POSITION__RIGHT => $LANG['menu_right'],
    BLOCK_POSITION__TOP_FOOTER => $LANG['menu_top_footer'],
    BLOCK_POSITION__FOOTER => $LANG['menu_footer']
);

$edit_menu_tpl = new Template('admin/menus/menu_edition.tpl');
$edit_menu_tpl->assign_vars(array(
    'L_NAME' => $LANG['name'],
    'L_IMAGE' => $LANG['img'],
    'L_URL' => $LANG['url'],
    'L_PROPERTIES' => $LANG['properties'],
    'L_AUTHORIZATIONS' => $LANG['authorizations'],
    'L_ADD_SUB_ELEMENT' => $LANG['add_sub_element'],
    'L_ADD_SUB_MENU' => $LANG['add_sub_menu'],
    'L_MORE' => $LANG['more_details'],
    'L_DELETE' => $LANG['delete']
));

$menu = null;
if ($menu_id > 0)
{
	$menu = MenuService::load($menu_id);
	
    if (!of_class($menu, LINKS_MENU__CLASS))
        redirect('menus.php');
}
else
{   // Create a new generic menu
    $menu = new LinksMenu('', '', '', VERTICAL_MENU);
}

$block = $menu->get_block();
$tpl->assign_vars(array(
	'IDMENU' => $menu_id,
	'AUTH_MENUS' => Authorizations::generate_select(
        AUTH_MENUS, $menu->get_auth(), array(), 'menu_element_' . $menu->get_uid() . '_auth'
    ),
    'C_ENABLED' => !empty($menu_id) ? $menu->is_enabled() : true,
	'MENU_ID' => $menu->get_id(),
	'MENU_TREE' => $menu->display($edit_menu_tpl, LINKS_MENU_ELEMENT__FULL_DISPLAYING),
	'MENU_NAME' => $menu->get_title(),
	'MENU_URL' => $menu->get_url(true),
	'MENU_IMG' => $menu->get_image(true),
    'ID' => $menu->get_uid()
));

foreach (LinksMenu::get_menu_types_list() as $type_name)
{
	$tpl->assign_block_vars('type', array(
		'NAME' => $type_name,
		'L_NAME' => $LANG[$type_name . '_menu'],
		'SELECTED' => $menu->get_type() == $type_name ? ' selected="selected"' : ''
	));
}

foreach ($array_location as $key => $name)
{
    $tpl->assign_block_vars('location', array(
        'C_SELECTED' => $block == $key,
        'VALUE' => $key,
        'NAME' => $name
    ));
}

$tpl->assign_vars(array(
    'ID_MAX' => get_uid()
));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>