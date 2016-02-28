<?php


























require_once('../kernel/begin.php');
require_once('../poll/poll_begin.php'); 
require_once('../kernel/header.php'); 

$poll = array();
$poll_id = retrieve(GET, 'id', 0);
if (!empty($poll_id))
{
	$poll = $Sql->query_array(PREFIX . 'poll', 'id', 'question', 'votes', 'answers', 'type', 'timestamp', "WHERE id = '" . $poll_id . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
	
	
	if (empty($poll['id']))
		$Errorh->handler('e_unexist_poll', E_USER_REDIRECT); 
}	
	
$archives = retrieve(GET, 'archives', false); 
$show_result = retrieve(GET, 'r', false); 

if (!empty($_POST['valid_poll']) && !empty($poll['id']) && !$archives)
{
	
	if ($User->check_level($CONFIG_POLL['poll_auth']))
	{
		
		if (isset($_COOKIE[$CONFIG_POLL['poll_cookie']])) 
		{
			$array_cookie = explode('/', $_COOKIE[$CONFIG_POLL['poll_cookie']]);
			if (in_array($poll['id'], $array_cookie))
				$check_cookie = true;
			else
			{
				$check_cookie = false;
				
				$array_cookie[] = $poll['id']; 
				$value_cookie = implode('/', $array_cookie); 
	
				setcookie($CONFIG_POLL['poll_cookie'], $value_cookie, time() + $CONFIG_POLL['poll_cookie_lenght'], '/');						
			}
		}
		else 
		{	
			$check_cookie = false;
			setcookie($CONFIG_POLL['poll_cookie'], $poll['id'], time() + $CONFIG_POLL['poll_cookie_lenght'], '/');
		}
		
		$check_bdd = true;
		if ($CONFIG_POLL['poll_auth'] == -1) 
		{
			
			$ip = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE ip = '" . USER_IP . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
			if (empty($ip))
			{
				
				$Sql->query_inject("INSERT INTO " . PREFIX . "poll_ip (ip, user_id, idpoll, timestamp) VALUES('" . USER_IP . "', -1, '" . $poll['id'] . "', '" . time() . "')", __LINE__, __FILE__);
				$check_bdd = false;
			}
		}
		else 
		{
			
			$user_id = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
			if (empty($user_id))
			{
				
				$Sql->query_inject("INSERT INTO " . PREFIX . "poll_ip (ip, user_id, idpoll, timestamp) VALUES('" . USER_IP . "', '" . $User->get_attribute('user_id') . "', '" . $poll['id'] . "', '" . time() . "')", __LINE__, __FILE__);
				$check_bdd = false;
			}
		}
		
		
		if ($check_bdd || $check_cookie)
			redirect(HOST . DIR . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_already_vote', '-' . $poll['id'] . '.php?error=e_already_vote', '&') . '#errorh');
		
		
		$check_answer = false;
		$array_votes = explode('|', $poll['votes']);
		if ($poll['type'] == '1') 
		{	
			$id_answer = retrieve(POST, 'radio', -1);		
			if (isset($array_votes[$id_answer]))
			{
				$array_votes[$id_answer]++;
				$check_answer = true;
			}
		}
		else 
		{
			
			$nbr_answer = count($array_votes);
			for ($i = 0; $i < $nbr_answer; $i++)
			{	
				if (isset($_POST[$i]))
				{
					$array_votes[$i]++;
					$check_answer = true;
				}
			}
		}

		if ($check_answer) 
		{
			$Sql->query_inject("UPDATE " . PREFIX . "poll SET votes = '" . implode('|', $array_votes) . "' WHERE id = '" . $poll['id'] . "'", __LINE__, __FILE__);
			
			
			redirect_confirm(HOST . DIR . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'), $LANG['confirm_vote'], 2);
			
			if (in_array($poll['id'], $CONFIG_POLL['poll_mini']) ) 
				$Cache->Generate_module_file('poll');
		}	
		else 
			redirect_confirm(HOST . DIR . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'), $LANG['no_vote'], 2);
	}
	else
		redirect(HOST . DIR . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_unauth_poll', '-' . $poll['id'] . '.php?error=e_unauth_poll', '&') . '#errorh');
}
elseif (!empty($poll['id']) && !$archives) 
{
	$Template->set_filenames(array(
		'poll'=> 'poll/poll.tpl'
	));

	
	$check_bdd = false;
	if ($CONFIG_POLL['poll_auth'] == -1) 
	{
		
		$ip = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE ip = '" . USER_IP . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
		if (!empty($ip))
			$check_bdd = true;
	}
	else 
	{
		
		$user_id = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
		if (!empty($user_id))
			$check_bdd = true;
	}
	
	
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'e_already_vote':
		$errstr = $LANG['e_already_vote'];
		$type = E_USER_WARNING;
		break;
		case 'e_unauth_poll':
		$errstr = $LANG['e_unauth_poll'];
		$type = E_USER_WARNING;
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$Errorh->handler($errstr, $type);
	
	
	$array_cookie = isset($_COOKIE[$CONFIG_POLL['poll_cookie']]) ? explode('/', $_COOKIE[$CONFIG_POLL['poll_cookie']]) : array();
	if ($show_result || in_array($poll['id'], $array_cookie) === true || $check_bdd) 
	{		
		$array_answer = explode('|', $poll['answers']);
		$array_vote = explode('|', $poll['votes']);
		
		$sum_vote = array_sum($array_vote);
		$Template->assign_vars(array(
			'C_POLL_VIEW' => true,
			'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
			'IDPOLL' => $poll['id'],
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short', $poll['timestamp']),
			'VOTES' => $sum_vote,
			'MODULE_DATA_PATH' => $Template->get_module_data_path('poll'),
			'L_DELETE_POLL' => $LANG['alert_delete_poll'],
			'L_POLL' => $LANG['poll'],
			'L_BACK_POLL' => $LANG['poll_back'],
			'L_VOTE' => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote']),
			'L_ON' => $LANG['on'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete']
		));
		
		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; 
		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$Template->assign_block_vars('result', array(
				'ANSWERS' => $answer, 
				'NBRVOTE' => (int)$nbrvote,
				'WIDTH' => number_round(($nbrvote * 100 / $sum_vote), 1) * 4, 
				'PERCENT' => number_round(($nbrvote * 100 / $sum_vote), 1)
			));
		}

		$Template->pparse('poll');
	}
	else 
	{
		$Template->assign_vars(array(
			'C_POLL_VIEW' => true,
			'C_POLL_QUESTION' => true,
			'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
			'IDPOLL' => $poll['id'],
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short'),
			'VOTES' => 0,
			'ID_R' => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short', $poll['timestamp']),
			'U_POLL_ACTION' => url('.php?id=' . $poll['id'] . '&amp;token=' . $Session->get_token(), '-' . $poll['id'] . '.php?token=' . $Session->get_token()),
			'U_POLL_RESULT' => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'L_DELETE_POLL' => $LANG['alert_delete_poll'],
			'L_POLL' => $LANG['poll'],
			'L_BACK_POLL' => $LANG['poll_back'],
			'L_VOTE' => $LANG['poll_vote'],
			'L_RESULT' => $LANG['poll_result'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_ON' => $LANG['on']
		));
	
		$z = 0;
		$array_answer = explode('|', $poll['answers']);
		if ($poll['type'] == '1')
		{
			foreach ($array_answer as $answer)
			{						
				$Template->assign_block_vars('radio', array(
					'NAME' => $z,
					'TYPE' => 'radio',
					'ANSWERS' => $answer
				));
				$z++;
			}
		}	
		elseif ($poll['type'] == '0') 
		{
			
			foreach ($array_answer as $answer)
			{						
				$Template->assign_block_vars('checkbox', array(
					'NAME' => $z,
					'TYPE' => 'checkbox',
					'ANSWERS' => $answer
				));
				$z++;	
			}
		}		
		$Template->pparse('poll');
	}
}
elseif (!$archives) 
{
	$Template->set_filenames(array(
		'poll'=> 'poll/poll.tpl'
	));

	$show_archives = $Sql->query("SELECT COUNT(*) as compt FROM " . PREFIX . "poll WHERE archive = 1 AND visible = 1", __LINE__, __FILE__);
	$show_archives = !empty($show_archives) ? '<a href="poll' . url('.php?archives=1', '.php?archives=1') . '">' . $LANG['archives'] . '</a>' : '';
	
	$edit = '';	
	if ($User->check_level(ADMIN_LEVEL))
		$edit = '<a href="../poll/admin_poll.php" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
	
	$Template->assign_vars(array(
		'C_POLL_MAIN' => true,
		'EDIT' => $edit,
		'U_ARCHIVE' => $show_archives,
		'L_POLL' => $LANG['poll'],
		'L_POLL_MAIN' => $LANG['poll_main']		
	));
	
	$result = $Sql->query_while("SELECT id, question 
	FROM " . PREFIX . "poll 
	WHERE archive = 0 AND visible = 1
	ORDER BY id DESC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('list', array(
			'U_POLL_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
			'QUESTION' => $row['question']
		));
	}
	$Sql->query_close($result);
	
	$Template->pparse('poll');	
}
elseif ($archives) 
{
	$Template->set_filenames(array(
		'poll'=> 'poll/poll.tpl'
	));
		
	$nbrarchives = $Sql->query("SELECT COUNT(*) as id FROM " . PREFIX . "poll WHERE archive = 1 AND visible = 1", __LINE__, __FILE__);
	
	import('util/pagination'); 
	$Pagination = new Pagination();
	
	$Template->assign_vars(array(
		'C_POLL_ARCHIVES' => true,
		'SID' => SID,
		'THEME' => get_utheme(),		
		'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
		'PAGINATION' => $Pagination->display('poll' . url('.php?p=%d', '-0-0-%d.php'), $nbrarchives, 'p', 10, 3),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('poll'),
		'L_ALERT_DELETE_POLL' => $LANG['alert_delete_poll'],
		'L_ARCHIVE' => $LANG['archives'],
		'L_BACK_POLL' => $LANG['poll_back'],		
		'L_ON' => $LANG['on'],
		'L_EDIT' => $LANG['edit'],
		'L_DELETE' => $LANG['delete']
	));	
	
	
	$result = $Sql->query_while("SELECT id, question, votes, answers, type, timestamp
	FROM " . PREFIX . "poll
	WHERE archive = 1 AND visible = 1
	ORDER BY timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg(10, 'archives'), 10), __LINE__, __FILE__); 
	while ($row = $Sql->fetch_assoc($result))
	{
		$array_answer = explode('|', $row['answers']);
		$array_vote = explode('|', $row['votes']);
		
		$sum_vote = array_sum($array_vote);
		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; 

		$Template->assign_block_vars('list', array(
			'ID' => $row['id'],
			'QUESTION' => $row['question'],
			'EDIT' => '<a href="../poll/admin_poll' . url('.php?id=' . $row['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>',
			'DEL' => '&nbsp;&nbsp;<a href="../poll/admin_poll' . url('.php?delete=1&amp;id=' . $row['id']) . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" /></a>',
			'VOTE' => $sum_vote,
			'DATE' => gmdate_format('date_format'),			
			'L_VOTE' => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
		));		

		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$Template->assign_block_vars('list.result', array(
				'ANSWERS' => $answer, 
				'NBRVOTE' => $nbrvote,
				'WIDTH' => number_round(($nbrvote * 100 / $sum_vote), 1) * 4, 
				'PERCENT' => number_round(($nbrvote * 100 / $sum_vote), 1),
				'L_VOTE' => (($nbrvote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
			));
		}
	}
	$Sql->query_close($result);

	$Template->pparse('poll');
}
else
	$Errorh->handler('e_unexist_page', E_USER_REDIRECT); 
	
require_once('../kernel/footer.php');

?>
