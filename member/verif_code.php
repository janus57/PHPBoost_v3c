<?php



























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');
header('Content-type: image/jpeg'); 

$instance = retrieve(GET, 'instance', 1);
$width = retrieve(GET, 'width', 160);
$height = retrieve(GET, 'height', 50);
$font = retrieve(GET, 'font', PATH_TO_ROOT . '/kernel/data/fonts/impact.ttf');
$difficulty = retrieve(GET, 'difficulty', 4);

import('util/captcha');
$Captcha = new Captcha();

$Captcha->set_instance($instance);
$Captcha->set_width($width);
$Captcha->set_height($height);
$Captcha->set_font($font);
$Captcha->set_difficulty($difficulty);

if ($Captcha->is_available())
	$Captcha->display();

require_once('../kernel/footer_no_display.php');
?>
