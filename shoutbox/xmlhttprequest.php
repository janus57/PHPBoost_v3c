<?php


























define('NO_SESSION_LOCATION', true); 
require_once('../kernel/begin.php');
require_once('../shoutbox/shoutbox_begin.php');
require_once('../kernel/header_no_display.php');

$add = !empty($_GET['add']) ? true : false;
$del = !empty($_GET['del']) ? true : false;
$refresh = !empty($_GET['refresh']) ? true : false;

if ($add)
{
	
	if ($User->get_attribute('user_readonly') > time()) 
	{
		echo -6;
		exit;
	}
		
	$shout_pseudo = !empty($_POST['pseudo']) ? strprotect(utf8_decode($_POST['pseudo'])) : $LANG['guest'];
	$shout_contents = !empty($_POST['contents']) ? trim(utf8_decode($_POST['contents'])) : '';
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		
		if ($User->check_level($CONFIG_SHOUTBOX['shoutbox_auth']))
		{
			
			$check_time = ($User->get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - $CONFIG['delay_flood']))
				{
					echo -2;
					exit;
				}
			}
			
			
			$shout_contents = strparse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);		
			if (!check_nbr_links($shout_pseudo, 0)) 
			{	
				echo -3;
				exit;
			}
			if (!check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link'])) 
			{	
				echo -4;
				exit;
			}
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES('" . $shout_pseudo . "', '" . $User->get_attribute('user_id') . "', '" . $User->get_attribute('level') . "', '" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
			$last_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "shoutbox"); 
			
			$array_class = array('member', 'modo', 'admin');
			if ($User->get_attribute('user_id') !== -1)
				$shout_pseudo = '<a href="javascript:Confirm_del_shout(' . $last_msg_id . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a> <a style="font-size:10px;" class="' . $array_class[$User->get_attribute('level')] . '" href="../member/member' . url('.php?id=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php') . '">' . (!empty($shout_pseudo) ? wordwrap_html($shout_pseudo, 16) : $LANG['guest'])  . '</a>';
			else
				$shout_pseudo = '<span class="text_small" style="font-style: italic;">' . (!empty($shout_pseudo) ? wordwrap_html($shout_pseudo, 16) : $LANG['guest']) . '</span>';
			
			$test = second_parse($test);
			echo "array_shout[0] = '" . $shout_pseudo . "';";
			echo "array_shout[1] = '" . addslashes(second_parse(str_replace(array("\n", "\r"), array('', ''), ucfirst(stripslashes($shout_contents))))) . "';";
			echo "array_shout[2] = '" . $last_msg_id . "';";
		}
		else 
			echo -1;
	}
	else
		echo -5;
}
elseif ($refresh)
{
	$array_class = array('member', 'modo', 'admin');
	$result = $Sql->query_while("SELECT id, login, user_id, level, contents 
	FROM " . PREFIX . "shoutbox 
	ORDER BY timestamp DESC 
	" . $Sql->limit(0, 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];		
		if ($User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
			$del = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a>';
		else
			$del = '';
	
		if ($row['user_id'] !== -1) 
			$row['login'] = $del . ' <a style="font-size:10px;" class="' . $array_class[$row['level']] . '" href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
		else
			$row['login'] = $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
		
		echo '<p id="shout_container_' . $row['id'] . '">' . $row['login'] . '<span class="text_small"> : ' . str_replace(array("\n", "\r"), array('', ''), ucfirst(second_parse($row['contents']))) . '</span></p>' . "\n";
	}
	$Sql->query_close($result);
}
elseif ($del)
{
	$Session->csrf_get_protect(); 
	
	$shout_id = !empty($_POST['idmsg']) ? numeric($_POST['idmsg']) : '';
	if (!empty($shout_id))
	{
		$user_id = (int)$Sql->query("SELECT user_id FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
		if ($User->check_level(MODO_LEVEL) || ($user_id === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			echo 1;
		}
	}
}

require_once('../kernel/footer_no_display.php');

?>
