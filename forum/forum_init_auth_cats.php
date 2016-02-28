<?php


























load_module_lang('forum'); 
require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

$Cache->load('forum');


$AUTH_READ_FORUM = array();
if (is_array($CAT_FORUM))
{
    foreach ($CAT_FORUM as $idcat => $key)
    {
        if ($User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) && $CAT_FORUM[$idcat]['aprob'])
            $AUTH_READ_FORUM[$idcat] = true;
        else
            $AUTH_READ_FORUM[$idcat] = false;
    }
}

?>
