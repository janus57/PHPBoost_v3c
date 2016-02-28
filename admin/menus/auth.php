<?php



























define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = retrieve(REQUEST, 'id', 0);
$post = retrieve(POST, 'id', -1) >= 0 ? true : false;

import('core/menu_service');

$menu = MenuService::load($id);

if ($menu == null)
    redirect('auth.php');
        
if ($post)
{   
    $menu->enabled(retrieve(POST, 'activ', MENU_NOT_ENABLED));
    $menu->set_auth(Authorizations::build_auth_array_from_form(AUTH_MENUS));
    
    MenuService::save($menu);
    MenuService::generate_cache();
    
    redirect('menus.php#m' . $id);
}


include('lateral_menu.php');
lateral_menu();

$tpl = new Template('admin/menus/auth.tpl');
$Cache->load('themes');

$tpl->assign_vars(array(
    'KERNEL_EDITOR' => display_editor(),
    'L_REQUIRE_TITLE' => $LANG['require_title'],
    'L_REQUIRE_TEXT' => $LANG['require_text'],
    'L_NAME' => $LANG['name'],
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
    'L_ACTION_MENUS' => $LANG['menus_edit'],
    'L_ACTION' => $LANG['update'],
    'L_RESET' => $LANG['reset'],
    'ACTION' => 'save',
));


$tpl->assign_vars(array(
    'IDMENU' => $id,
    'NAME' => $menu->get_title(),
    'AUTH_MENUS' => Authorizations::generate_select(AUTH_MENUS, $menu->get_auth()),
    'C_ENABLED' => $menu->is_enabled(),
));

$tpl->parse();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
