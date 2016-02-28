<?php


























if (defined('PHPBOOST') !== true)	exit;

$auth_read = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
$auth_write = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);



while ($id_cat_for_bread_crumb > 0)
{
	
	$Bread_crumb->add($FAQ_CATS[$id_cat_for_bread_crumb]['name'], url('faq.php?id=' . $id_cat_for_bread_crumb, 'faq-' . $id_cat_for_bread_crumb . '+' . url_encode_rewrite($FAQ_CATS[$id_cat_for_bread_crumb]['name']) . '.php'));
	
	
	if (!empty($FAQ_CATS[$id_cat_for_bread_crumb]['auth']))
	{
			
			$auth_read = $auth_read && $User->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_READ);
			$auth_write = $User->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_WRITE);
	}
	
	
	$id_cat_for_bread_crumb = (int)$FAQ_CATS[$id_cat_for_bread_crumb]['id_parent'];	
}

$Bread_crumb->add($FAQ_CONFIG['faq_name'], url('faq.php'));

$Bread_crumb->reverse();

?>
