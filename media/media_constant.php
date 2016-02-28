<?php




























define('MEDIA_AUTH_READ', 1);
define('MEDIA_AUTH_CONTRIBUTION', 2);
define('MEDIA_AUTH_WRITE', 4);


define('MEDIA_TYPE_BOTH', 0);
define('MEDIA_TYPE_MUSIC', 1);
define('MEDIA_TYPE_VIDEO', 2);


define('MEDIA_STATUS_UNAPROBED', 0);
define('MEDIA_STATUS_UNVISIBLE', 1);
define('MEDIA_STATUS_APROBED', 2);


define('MEDIA_DL_COM', 1); 
define('MEDIA_DV_COM', 2); 
define('MEDIA_DL_NOTE', 4); 
define('MEDIA_DV_NOTE', 8); 
define('MEDIA_DL_USER', 16); 
define('MEDIA_DV_USER', 32); 
define('MEDIA_DL_COUNT', 64); 
define('MEDIA_DV_COUNT', 128); 
define('MEDIA_DL_DATE', 256); 
define('MEDIA_DV_DATE', 512); 
define('MEDIA_DL_DESC', 1024); 
define('MEDIA_DV_DESC', 2048); 
define('MEDIA_NBR', 4096); 


define('TIME_REDIRECT', 5);

define('NUM_MODO_MEDIA', 25);


$mime_type = array(
	'audio' => array(
		'mp3' => 'audio/mpeg',
	),
	'video' => array(
		'flv' => 'video/x-flv',
		'swf' => 'application/x-shockwave-flash'
	)
);


$mime_type_tpl = array(
	'video/x-flv' => 'format/media_flv.tpl',
	'application/x-shockwave-flash' => 'format/media_swf.tpl',
	'audio/mpeg' => 'format/media_mp3.tpl'
);


$host_ok = array(
	'video' => array(
		'www.dailymotion.com',
		'www.youtube.com',
		'video.google.fr',
		'www.wat.tv'
	),
	'audio' => array(
		'www.deezer.com',
		'widgets.jamendo.com'
	)
);

?>
