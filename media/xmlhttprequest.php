<?php



























define('NO_SESSION_LOCATION', true); 

require_once('../kernel/begin.php');
require_once('media_begin.php');
require_once('../kernel/header_no_display.php');


if (!empty($_GET['note']) && $User->check_level(MEMBER_LEVEL)) 
{	
	$id = retrieve(POST, 'id', 0);
	$note = retrieve(POST, 'note', 0);

	
	import('content/note');
	$Note = new Note('media', $id, '', $MEDIA_CONFIG['note_max'], '', NOTE_DISPLAY_NOTE);
	
	if (!empty($note) && !empty($id))
	{
		echo $Note->add($note); 
	}
}

?>
