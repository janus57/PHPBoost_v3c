<?php


























if (defined('PHPBOOST') !== true)	exit;

define('WIKI_CREATE_ARTICLE', 0x01);
define('WIKI_CREATE_CAT', 0x02);
define('WIKI_RESTORE_ARCHIVE', 0x04);
define('WIKI_DELETE_ARCHIVE', 0x08);
define('WIKI_EDIT', 0x10);
define('WIKI_DELETE', 0x20);
define('WIKI_RENAME', 0x40);
define('WIKI_REDIRECT', 0x80);
define('WIKI_MOVE', 0x100);
define('WIKI_STATUS', 0x200);
define('WIKI_COM', 0x400);
define('WIKI_RESTRICTION', 0x800);

$Cache->load('wiki');

?>
