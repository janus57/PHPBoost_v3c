<?php
/*##################################################
 *                               stats.php
 *                            -------------------
 *   begin                : August 03, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true) exit;

function stats_mini($position, $block)
{
    global $LANG, $Cache, $nbr_members, $last_member_id, $last_member_login;
    //Chargement de la langue du module.
    load_module_lang('stats');
    
    #########################Stats.tpl###########################
    $tpl = new Template('stats/stats_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $Cache->load('stats');
    $l_member_registered = ($nbr_members > 1) ? $LANG['member_registered_s'] : $LANG['member_registered'];
    
    $tpl->assign_vars(array(
    	'SID' => SID,
    	'L_STATS' => $LANG['stats'],
    	'L_MORE_STAT' => $LANG['more_stats'],
    	'L_USER_REGISTERED' => sprintf($l_member_registered, $nbr_members),
    	'L_LAST_REGISTERED_USER' => $LANG['last_member'],
    	'U_LINK_LAST_USER' => '<a href="' . HOST . DIR . '/member/member' . url('.php?id=' . $last_member_id, '-' . $last_member_id  . '.php') . '">' . $last_member_login . '</a>'
    ));
    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>