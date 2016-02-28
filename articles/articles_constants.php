<?php


























if (defined('PHPBOOST') !== true)
    exit;

define('READ_CAT_ARTICLES', 0x01);
define('WRITE_CAT_ARTICLES', 0x02);
define('EDIT_CAT_ARTICLES', 0x04);

$Cache->load('articles');
load_module_lang('articles'); 

$idartcat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);

if (empty($idartcat))
{
    $CAT_ARTICLES[0]['auth'] = $CONFIG_ARTICLES['auth_root'];
    $CAT_ARTICLES[0]['aprob'] = 1;
    $CAT_ARTICLES[0]['name'] = $LANG['root'];
    $CAT_ARTICLES[0]['level'] = -1;
    $CAT_ARTICLES[0]['id_left'] = 0;
    $CAT_ARTICLES[0]['id_right'] = 0;
}

?>
