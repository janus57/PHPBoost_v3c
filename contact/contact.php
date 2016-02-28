<?php


























require_once('../kernel/begin.php');
require_once('../contact/contact_begin.php');
require_once('../kernel/header.php');

$mail_from = retrieve(POST, 'mail_email', '', TSTRING_UNCHANGE);
$mail_object = retrieve(POST, 'mail_object', '', TSTRING_UNCHANGE);
$mail_contents = retrieve(POST, 'mail_contents', '', TSTRING_UNCHANGE);
$mail_valid = retrieve(POST, 'mail_valid', '');
$get_error = '';

import('util/captcha');
$captcha = new Captcha();
$captcha->set_difficulty($CONFIG_CONTACT['contact_difficulty_verifcode']);

###########################Envoi##############################
if (!empty($mail_valid))
{
    
    if (!$CONFIG_CONTACT['contact_verifcode'] || $captcha->is_valid()) 
    {
        import('io/mail');
        $mail = new Mail();

        if ($mail->send_from_properties($CONFIG['mail'], $mail_object, $mail_contents, $mail_from, '', 'user')) 
        {
            $get_error = 'success';
        }
        else 
        {
            $get_error = 'error';
        }
    }
    else 
    {
        $get_error = 'verif';
    }
}
elseif (!empty($_POST['mail_valid']) && ( empty($mail_email) || empty($mail_contents) )) 
{
    $get_error = 'incomplete';
}

###########################Affichage##############################
$Template->set_filenames(array(
	'contact'=> 'contact/contact.tpl'
));


if ($get_error == 'incomplete')
{
    $Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
}
elseif ($get_error == 'verif')
{
    $Errorh->handler($LANG['e_incorrect_verif_code'], E_USER_WARNING);
}
elseif ($get_error == 'success')
{
    $Errorh->handler($LANG['success_mail'], E_USER_SUCCESS);
}
elseif ($get_error == 'error')
{
    $Errorh->handler($LANG['error_mail'], E_USER_WARNING);
}


if ($captcha->is_available() && $CONFIG_CONTACT['contact_verifcode'])
{
    $Template->assign_vars(array(
		'C_VERIF_CODE' => true,
		'VERIF_CODE' => $captcha->display_form(),
		'L_REQUIRE_VERIF_CODE' => $captcha->js_require()
    ));
}

$Template->assign_vars(array(
	'MAIL' => $User->get_attribute('user_mail'),
	'CONTACT_OBJECT' => stripslashes(retrieve(POST, 'mail_object', '')),
	'CONTACT_CONTENTS' => $mail_contents,
	'L_REQUIRE_MAIL' => $LANG['require_mail'],
	'L_REQUIRE_TEXT' => $LANG['require_text'] ,
	'L_CONTACT_MAIL' => $LANG['contact_mail'],
	'L_MAIL' => $LANG['mail'],
	'L_VERIF_CODE' => $LANG['verif_code'],
	'L_REQUIRE' => $LANG['require'],
	'L_VALID_MAIL' => $LANG['valid_mail'],
	'L_OBJET' => $LANG['objet'],
	'L_CONTENTS' => $LANG['content'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'U_ACTION_CONTACT' => url('contact.php?token=' . $Session->get_token())
));

$Template->pparse('contact');

require_once('../kernel/footer.php');

?>
