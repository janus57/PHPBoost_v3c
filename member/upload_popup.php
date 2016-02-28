<?php


























require_once('../kernel/begin.php');
define('TITLE', $LANG['files_management']);
require_once('../kernel/header_no_display.php');

$id = retrieve(GET, 'id', 0);
if (!empty($id))
{
	$basedir = '../upload/';
	$info_file = $Sql->query_array(PREFIX . "upload", "id", "path", "type", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	if (!empty($info_file['id']))
	{
		switch ($info_file['type'])
		{
			
			case 'jpg':
			header('Content-type: image/jpeg');
			@readfile($basedir . $info_file['path']);
			break;
			case 'png':
			header('Content-type: image/png');
			@readfile($basedir . $info_file['path']);
			break;
			case 'gif':
			header('Content-type: image/gif');
			@readfile($basedir . $info_file['path']);
			break;
			case 'bmp':
			header('Content-type: image/bmp');
			@readfile($basedir . $info_file['path']);
			break;
			case 'svg':
			header("Content-type: image/svg+xml");
			@readfile($basedir . $info_file['path']);
			break;
			
			case 'mp3':
			echo '<br />
				<object type="application/x-shockwave-flash" data="../kernel/data/dewplayer.swf?son=' . $basedir . $info_file['path'] . '" width="200" height="20">
				<param name="allowScriptAccess" value="never" />
				<param name="play" value="true" />
				<param name="movie" value="../kernel/data/dewplayer.swf?son=' . $basedir . $info_file['path'] . '" />
				<param name="menu" value="false" />
				<param name="quality" value="high" />
				<param name="scalemode" value="noborder" />
				<param name="wmode" value="transparent" />
				<param name="bgcolor" value="#FFFFFF" />
			</object>';
			break;
		}
	}
}

require_once('../kernel/footer_no_display.php');

?>
