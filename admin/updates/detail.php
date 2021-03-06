<?php


























define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$identifier = retrieve(GET, 'identifier', '');
$tpl = new Template('admin/updates/detail.tpl');

$tpl->assign_vars(array(
    'L_WEBSITE_UPDATES' => $LANG['website_updates'],
    'L_KERNEL' => $LANG['kernel'],
    'L_MODULES' => $LANG['modules'],
    'L_THEMES' => $LANG['themes']
));

$app = null;
import('events/administrator_alert_service');

if (($update = AdministratorAlertService::find_by_identifier($identifier, 'updates')) !== null)
{
    import('core/application');
    $app = unserialize($update->get_properties());
}

if ($app !== null && $app->check_compatibility())
{
    $authors = $app->get_authors();
    $new_features = $app->get_new_features();
    $improvments = $app->get_improvments();
    $bug_corrections = $app->get_bug_corrections();
    $security_improvments = $app->get_security_improvments();
    
    $nb_authors = count($authors);
    $has_new_feature = count($new_features) > 0 ? true : false;
    $has_improvments = count($improvments) > 0 ? true : false;
    $has_bug_corrections = count($bug_corrections) > 0 ? true : false;
    $has_security_improvments = count($security_improvments) > 0 ? true : false;
    
    switch ($update->get_priority())
    {
        case ADMIN_ALERT_VERY_HIGH_PRIORITY:
            $priority = 'priority_very_high';
            break;
        case ADMIN_ALERT_HIGH_PRIORITY:
            $priority = 'priority_high';
            break;
        case ADMIN_ALERT_MEDIUM_PRIORITY:
            $priority = 'priority_medium';
            break;
        default:
            $priority = 'priority_low';
            break;
    }
    
    $tpl->assign_vars(array(
        'APP_NAME' => $app->get_name(),
        'APP_VERSION' => $app->get_version(),
        'APP_LANGUAGE' => $app->get_localized_language(),
        'APP_PUBDATE' => $app->get_pubdate(),
        'APP_DESCRIPTION' => $app->get_description(),
        'APP_WARNING_LEVEL' => $app->get_warning_level(),
        'APP_WARNING' => $app->get_warning(),
        'U_APP_DOWNLOAD' => $app->get_download_url(),
        'U_APP_UPDATE' => $app->get_update_url(),
        'PRIORITY_CSS_CLASS' => 'row_' . $priority,
        'L_AUTHORS' => $nb_authors > 1 ? $LANG['authors'] : $LANG['author'],
        'L_NEW_FEATURES' => $LANG['new_features'],
        'L_IMPROVMENTS' => $LANG['improvments'],
        'L_FIXED_BUGS' => $LANG['fixed_bugs'],
        'L_SECURITY_IMPROVMENTS' => $LANG['security_improvments'],
        'L_DOWNLOAD' => $LANG['app_update__download'],
        'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
        'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
        'L_WARNING' => $LANG['warning'],
        'L_APP_UPDATE_MESSAGE' => $update ->get_entitled(),
        'C_NEW_FEATURES' => $has_new_feature,
        'C_IMPROVMENTS' => $has_improvments,
        'C_BUG_CORRECTIONS' => $has_bug_corrections,
        'C_SECURITY_IMPROVMENTS' => $has_security_improvments,
        'C_NEW' => $has_new_feature || $has_improvments || $has_bug_corrections || $has_security_improvments
    ));
    
    foreach ($authors as $author)
        $tpl->assign_block_vars('authors', array('name' => $author['name'], 'email' => $author['email']));
    
    foreach ($new_features as $new_feature)
        $tpl->assign_block_vars('new_features', array('description' => $new_feature));
        
    foreach ($improvments as $improvment)
        $tpl->assign_block_vars('improvments', array('description' => $improvment));
    
    foreach ($bug_corrections as $bug_correction)
        $tpl->assign_block_vars('bugs', array('description' => $bug_correction));
    
    foreach ($security_improvments as $security_improvment)
        $tpl->assign_block_vars('security', array('description' => $security_improvment));
}
else $tpl->assign_vars((array('C_UNEXISTING_UPDATE' => true, 'L_UNEXISTING_UPDATE' => $LANG['unexisting_update'])));
    
$tpl->parse();
require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>
