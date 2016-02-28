<?php



























function lateral_menu()
{
    global $LANG, $CONFIG;
    $tpl = new Template('admin/menus/panel.tpl');
    $tpl->assign_vars(array(
        'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
        'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
        'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
        'L_ADD_FEED_MENUS' => $LANG['menus_feed_add'],
        'L_MANAGE_THEME_COLUMNS' => $LANG['manage_theme_columns'],
        'THEME_NAME' => get_utheme()
    ));
    $tpl->parse();
}
?>
