<?php


























require_once('../kernel/begin.php');
require_once('../newsletter/newsletter_begin.php');
require_once('../kernel/header_no_display.php');

$id = retrieve(GET, 'id', 0);

if (!empty($id))
{
	$newsletter = $Sql->query_array(PREFIX . 'newsletter_arch', 'type', 'title', 'message', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	if ($newsletter['type'] == 'html')
	{
		$message = stripslashes($newsletter['message']);
		$message = str_replace('<body', '<body onclick = "window.close()" ', $message);
		$message = str_replace('[UNSUBSCRIBE_LINK]', '', $message);
		echo $message;
	}
	elseif ($newsletter['type'] == 'bbcode')
	{
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $LANG['xml_lang'] .'"><head><title>' . $newsletter['title'] . '</title></head><body onclick = "window.close()"><p>' . $newsletter['message'] . '</p></body></html>';
		echo $message;
	}
	else
		exit;
}
else
	exit;

?>
