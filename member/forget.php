<?php


























require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_forget']);
require_once('../kernel/header.php'); 

$activ_confirm = retrieve(GET, 'activate', false);
$activ_get = retrieve(GET, 'activ', '');
$user_get = retrieve(GET, 'u', 0);
$forget = retrieve(POST, 'forget', '');

if (!$User->check_level(MEMBER_LEVEL))
{
	if (!$activ_confirm)
	{	
		$Template->set_filenames(array(
			'forget'=> 'member/forget.tpl'
		));
			
		if (!empty($forget))
		{
			$user_mail = retrieve(POST, 'mail', '');
			$login = retrieve(POST, 'name', '');

			if (!empty($user_mail) && check_mail($user_mail))
			{	
				$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $user_mail . "' AND login = '" . $login . "'", __LINE__, __FILE__);
				if (!empty($user_id)) 
				{
					$new_pass = substr(strhash(uniqid(rand(), true)), 0, 6); 
					$activ_pass =  substr(strhash(uniqid(rand(), true)), 0, 30); 
					
					$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = '" . $activ_pass . "', new_pass = '" . strhash($new_pass) . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); 
					
					import('io/mail');
					$Mail = new Mail();
					$Mail->send_from_properties($user_mail, $LANG['forget_mail_activ_pass'], sprintf($LANG['forget_mail_pass'], $login, HOST, (HOST . DIR), $user_id, $activ_pass, $new_pass, $CONFIG['sign']), $CONFIG['mail_exp']);

					
					redirect(HOST . DIR . '/member/forget.php?error=forget_mail_send');
				}
				else
					$Errorh->handler($LANG['e_mail_forget'], E_USER_NOTICE);
			}
			else
				$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		}
		
		$get_error = retrieve(GET, 'error', '', TSTRING_UNCHANGE);			
		$errno = E_USER_NOTICE;
		switch ($get_error)
		{ 
			case 'forget_mail_send':
				$errstr = $LANG['e_forget_mail_send'];					
			break;
			case 'forget_echec_change':
				$errstr = $LANG['e_forget_echec_change'];					
				$errno = E_USER_WARNING;
			break;
			case 'forget_confirm_change':
				$errstr = $LANG['e_forget_confirm_change'];
			break;
			default:
			$errstr = '';
		}	
		if (!empty($errstr))
			$Errorh->handler($errstr, $errno);			
	
		$Template->assign_vars(array(
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_REQUIRE' => $LANG['require'],
			'L_NEW_PASS' => $LANG['forget_pass'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_MAIL' => $LANG['mail'],
			'L_NEW_PASS_FORGET' => $LANG['forget_pass_send'],
			'L_SUBMIT' => $LANG['submit']
		));
		
		$Template->pparse('forget');
	}
	elseif (!empty($activ_get) && !empty($user_get) && $activ_confirm)
	{
		$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_get . "' AND activ_pass = '" . $activ_get . "'", __LINE__, __FILE__);
		if (!empty($user_id))
		{
			
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = new_pass WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = '', new_pass = '' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			
			redirect(HOST . DIR . '/member/error.php?e=e_forget_confirm_change');
		}
		else 
			redirect(HOST . DIR . '/member/forget.php?error=forget_echec_change');
	}	
	else 
		redirect(HOST . DIR . '/member/forget.php?error=forget_echec_change');
}
else
	redirect(get_start_page());

require_once('../kernel/footer.php'); 

?>
