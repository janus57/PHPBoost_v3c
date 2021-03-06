<?php


























if (defined('PHPBOOST') !== true) exit;

function poll_mini($position, $block)
{
    global $Cache, $LANG, $CONFIG_POLL, $_array_poll;
    $Cache->load('poll'); 
    if (!empty($CONFIG_POLL['poll_mini']) && $CONFIG_POLL['poll_mini'] != array() && strpos(SCRIPT, '/poll/poll.php') === false)
    {
    	
    	load_module_lang('poll');
    	$poll_mini = $_array_poll[array_rand($_array_poll)]; 
    	
    	$tpl = new Template('poll/poll_mini.tpl');
        import('core/menu_service');
        MenuService::assign_positions_conditions($tpl, $block);
    		
    	#####################R�sultats######################
    	
    	$array_cookie = isset($_COOKIE[$CONFIG_POLL['poll_cookie']]) ? explode('/', $_COOKIE[$CONFIG_POLL['poll_cookie']]) : array();
    	if (in_array($poll_mini['id'], $array_cookie))
    	{
    		$tpl->assign_vars(array(
    			'THEME' => get_utheme(),
    			'MODULE_DATA_PATH' => $tpl->get_module_data_path('poll'),
    			'L_MINI_POLL' => $LANG['mini_poll'],
    			'L_VOTE' => ($poll_mini['total'] > 1) ? $LANG['poll_vote_s'] : $LANG['poll_vote']
    		));
    		
    		$tpl->assign_block_vars('result', array(
    			'QUESTION' => $poll_mini['question'],
    			'VOTES' => $poll_mini['total'],
    		));
    		
    		foreach ($poll_mini['votes'] as $answer => $width)
    		{
    			$tpl->assign_block_vars('result.answers', array(
    				'ANSWERS' => $answer,
    				'WIDTH' => number_round($width, 0),
    				'PERCENT' => $width
    			));
    		}
    	}
    	else
    	{
    		#####################Questions######################
    		$tpl->assign_vars(array(
    			'L_MINI_POLL' => $LANG['mini_poll'],
    			'L_VOTE' => $LANG['poll_vote'],
    			'L_POLL_RESULT' => $LANG['poll_result'],
    			'U_POLL_RESULT' => url('.php?id=' . $poll_mini['id'] . '&amp;r=1', '-' . $poll_mini['id'] . '-1.php')
    		));
    		
    		global $Session;
    		$tpl->assign_block_vars('question', array(
    			'ID' => url('.php?id=' . $poll_mini['id'] . '&amp;token=' . $Session->get_token(), '-' . $poll_mini['id'] . '.php?token=' . $Session->get_token()),
    			'QUESTION' => $poll_mini['question']
    		));
    			
    		$z = 0;
    		if ($poll_mini['type'] == '1')
    		{
    			foreach ($poll_mini['votes'] as $answer => $width)
    			{
    				$tpl->assign_block_vars('question.radio', array(
    					'NAME' => $z,
    					'ANSWERS' => $answer
    				));
    				$z++;
    			}
    		}
    		elseif ($poll_mini['type'] == '0')
    		{
    			foreach ($poll_mini['votes'] as $answer => $width)
    			{
    				$tpl->assign_block_vars('question.checkbox', array(
    					'NAME' => $z,
    					'ANSWERS' => $answer
    				));
    				$z++;
    			}
    		}
    	}
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    return '';
}
?>
