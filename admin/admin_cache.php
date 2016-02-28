<?php



























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$cache_mode = retrieve(GET, 'cache', '');

if (empty($cache_mode))    
{
    
    if (!empty($_POST['cache']))
    {
        $Cache->Generate_all_files();
        redirect(HOST . DIR . '/admin/admin_cache.php?s=1');
    }
    else 
    {
        $Template->set_filenames(array(
            'admin_cache'=> 'admin/admin_cache.tpl'
        ));
        
        
        $get_error = retrieve(GET, 's', 0);
        if ($get_error == 1)
            $Errorh->handler($LANG['cache_success'], E_USER_SUCCESS);
        
        $Template->assign_vars(array(
            'L_CACHE' => $LANG['cache'],
            'L_SYNDICATION' => $LANG['syndication'],
            'L_EXPLAIN_SITE_CACHE' => $LANG['explain_site_cache'],
            'L_GENERATE' => $LANG['generate']
        ));
        
        $Template->pparse('admin_cache');
    }
}
else    
{
    
    if (!empty($_POST['cache']))
    {
        import('content/syndication/feed');
        Feed::clear_cache();
        
        redirect(HOST . DIR . '/admin/admin_cache.php?cache=syndication&s=1');
    }
    else 
    {
        $Template->set_filenames(array(
            'admin_cache_syndication'=> 'admin/admin_cache_syndication.tpl'
        ));
        
        
        $get_error = retrieve(GET, 's', 0);
        if ($get_error == 1)
        {
            $Errorh->handler($LANG['cache_success'], E_USER_SUCCESS);
        }
        
        $Template->assign_vars(array(
            'L_CACHE' => $LANG['cache'],
            'L_SYNDICATION' => $LANG['syndication'],
            'L_EXPLAIN_SITE_CACHE' => $LANG['explain_site_cache_syndication'],
            'L_GENERATE' => $LANG['generate']
        ));
        
        $Template->pparse('admin_cache_syndication');
    }
}

require_once('../admin/admin_footer.php');

?>
