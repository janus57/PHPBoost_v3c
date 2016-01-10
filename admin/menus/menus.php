<?php
/*##################################################
 *                                 menus.php
 *                            -------------------
 *   begin                : March, 05 2007
 *   copyright            : (C) 2009 R�gis Viarre, Lo�c Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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

$id = retrieve(GET, 'id', 0);

$action = retrieve(GET, 'action', '');
$move = retrieve(GET, 'move', '');

import('core/menu_service');

function menu_admin_link(&$menu, $mode)
{
    $link = '';
    switch ($mode)
    {
        case 'edit':
            if (of_class($menu, LINKS_MENU__CLASS))
                $link = 'links.php?';
            elseif (of_class($menu, CONTENT_MENU__CLASS))
                $link = 'content.php?';
            elseif (of_class($menu, FEED_MENU__CLASS))
                $link = 'feed.php?';
            else
                $link = 'auth.php?';
            break;
        case 'delete':
            if (of_class($menu, CONTENT_MENU__CLASS) || of_class($menu, LINKS_MENU__CLASS) || of_class($menu, FEED_MENU__CLASS))
                $link = 'menus.php?action=delete&amp;';
            else
            	return '';
            break;
        case 'up':
            $link = 'menus.php?action=up&amp;';
            break;
        case 'down':
            $link = 'menus.php?action=down&amp;';
            break;
        case 'move':
            $link = 'menus.php?';
            break;
    }
    global $Session;
    return $link . 'id=' . $menu->get_id() . '&amp;token=' . $Session->get_token();
}

if (!empty($id))
{
    $menu = MenuService::load($id);
    if ($menu == null)
        redirect('menus.php');
    
    // In GET mode so we check it
    $Session->csrf_get_protect();
        
    switch ($action)
    {
        case 'enable':
            MenuService::enable($menu);
        	break;
        case 'disable':
            MenuService::disable($menu);
            break;
        case 'delete':
            MenuService::delete($id);
            break;
        case 'up':
        case 'down':
            // Move up or down a Menu in a block
        	if ($action == 'up')
        	   MenuService::change_position($menu, MOVE_UP);
        	else
               MenuService::change_position($menu, MOVE_DOWN);
            break;
        default:
            if (!empty($move))
            {   // Move a Menu
                MenuService::move($menu, $move);
            }
            break;
    }
    
    MenuService::generate_cache();
    $Cache->Generate_file('css');
    redirect('menus.php#m' . $id);
}

// Try to find out new mini-modules and delete old ones
MenuService::update_mini_modules_list(false);
// The same with the mini menus
MenuService::update_mini_menus_list();

// Display the Menu dispositions
include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/menus.tpl');
$Cache->load('themes');

// Compute the column number
$right_column = $THEME_CONFIG[get_utheme()]['right_column'];
$left_column = $THEME_CONFIG[get_utheme()]['left_column'];
$colspan = 1 + (int) $right_column + (int) $left_column;

// Retrieves all the menu
$menus_blocks = MenuService::get_menus_map();
$blocks = array(
   BLOCK_POSITION__HEADER => 'mod_header',
   BLOCK_POSITION__SUB_HEADER => 'mod_subheader',
   BLOCK_POSITION__TOP_CENTRAL => 'mod_topcentral',
   BLOCK_POSITION__BOTTOM_CENTRAL => 'mod_bottomcentral',
   BLOCK_POSITION__TOP_FOOTER => 'mod_topfooter',
   BLOCK_POSITION__FOOTER => 'mod_footer',
   BLOCK_POSITION__LEFT => 'mod_left',
   BLOCK_POSITION__RIGHT => 'mod_right',
   BLOCK_POSITION__NOT_ENABLED => 'mod_main'
);

$menu_template = new Template('admin/menus/menu.tpl');
$menu_template->assign_vars(array(
    'THEME' => get_utheme(),
    'L_ENABLED' => $LANG['enabled'],
    'L_DISABLED' => $LANG['disabled'],
    'I_HEADER' => BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => BLOCK_POSITION__FOOTER,
    'I_LEFT' => BLOCK_POSITION__LEFT,
    'I_RIGHT' => BLOCK_POSITION__RIGHT,
    'L_HEADER' => $LANG['menu_header'],
    'L_SUB_HEADER' => $LANG['menu_subheader'],
    'L_LEFT_MENU' => $LANG['menu_left'],
    'L_RIGHT_MENU' => $LANG['menu_right'],
    'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
    'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
    'L_TOP_FOOTER' => $LANG['menu_top_footer'],
    'L_FOOTER' => $LANG['menu_footer'],
    'L_MOVETO' => $LANG['moveto'],
	'U_TOKEN' => $Session->get_token()
));

foreach ($menus_blocks as $block_id => $menus)
{   // For each block
    $i = 0;
    $max = count($menus);
    foreach ($menus as $menu)
    {   // For each Menu in this block
        $menu_tpl = $menu_template->copy();
        
        $id = $menu->get_id();
        $enabled = $menu->is_enabled();
        
        if (!$enabled)
           $block_id = BLOCK_POSITION__NOT_ENABLED;
        
        $edit_link = menu_admin_link($menu, 'edit');
        $del_link = menu_admin_link($menu, 'delete');
        
        $menu_tpl->assign_vars(array(
            'NAME' => $menu->get_title(),
            'IDMENU' => $id,
            'U_ONCHANGE_ENABLED' => to_js_string('menus.php?action=' . ($enabled ? 'disable' : 'enable') . '&amp;id=' . $id . '&amp;token=' . $Session->get_token() . '#m' . $id),
            'SELECT_ENABLED' => $enabled ? 'selected="selected"' : '',
            'SELECT_DISABLED' => !$enabled ? 'selected="selected"' : '',
            'CONTENTS' => $menu->admin_display(),
           'C_MENU_ACTIVATED' => $enabled,
            'C_EDIT' => !empty($edit_link),
            'C_DEL' => !empty($del_link),
            'C_UP' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i > 0,
            'C_DOWN' => $block_id != BLOCK_POSITION__NOT_ENABLED && $i < $max - 1,
            'C_MINI' => in_array($block_id, array(BLOCK_POSITION__LEFT, BLOCK_POSITION__NOT_ENABLED, BLOCK_POSITION__RIGHT)),
            'STYLE' => $block_id == BLOCK_POSITION__NOT_ENABLED ? 'margin:5px;margin-top:0px;float:left' : '',
            'U_EDIT' => menu_admin_link($menu, 'edit'),
            'U_DELETE' => menu_admin_link($menu, 'delete'),
            'U_UP' => menu_admin_link($menu, 'up'),
            'U_DOWN' => menu_admin_link($menu, 'down'),
            'U_MOVE' => menu_admin_link($menu, 'move'),
        ));
        
        $tpl->assign_block_vars($blocks[$block_id], array('MENU' => $menu_tpl->parse(TEMPLATE_STRING_MODE)));
        $i++;
    }
}


$tpl->assign_vars(array(
    'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
    'COLSPAN' => $colspan,
    'LEFT_COLUMN' => $left_column,
    'RIGHT_COLUMN' => $right_column,
    'START_PAGE' => get_start_page(),
	'L_INDEX' => $LANG['home'],
    'L_CONFIRM_DEL_MENU' => $LANG['confirm_del_menu'],
    'L_ACTIVATION' => $LANG['activation'],
    'L_MOVETO' => $LANG['moveto'],
    'L_GUEST' => $LANG['guest'],
    'L_USER' => $LANG['member'],
    'L_MODO' => $LANG['modo'],
    'L_ADMIN' => $LANG['admin'],
    'L_HEADER' => $LANG['menu_header'],
    'L_SUB_HEADER' => $LANG['menu_subheader'],
    'L_LEFT_MENU' => $LANG['menu_left'],
    'L_RIGHT_MENU' => $LANG['menu_right'],
    'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
    'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
    'L_TOP_FOOTER' => $LANG['menu_top_footer'],
    'L_FOOTER' => $LANG['menu_footer'],
	'I_HEADER' => BLOCK_POSITION__HEADER,
    'I_SUBHEADER' => BLOCK_POSITION__SUB_HEADER,
    'I_TOPCENTRAL' => BLOCK_POSITION__TOP_CENTRAL,
    'I_BOTTOMCENTRAL' => BLOCK_POSITION__BOTTOM_CENTRAL,
    'I_TOPFOOTER' => BLOCK_POSITION__TOP_FOOTER,
    'I_FOOTER' => BLOCK_POSITION__FOOTER,
    'I_LEFT' => BLOCK_POSITION__LEFT,
    'I_RIGHT' => BLOCK_POSITION__RIGHT,
    'L_MENUS_AVAILABLE' => count($menus_blocks[BLOCK_POSITION__NOT_ENABLED]) ? $LANG['available_menus'] : $LANG['no_available_menus'],
    'L_INSTALL' => $LANG['install'],
    'L_UPDATE' => $LANG['update'],
    'L_RESET' => $LANG['reset'],
	'U_TOKEN' => $Session->get_token()
));
$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
