<?php



























import('modules/module_interface');


class PollInterface extends ModuleInterface
{
    ## Public Methods ##
    function PollInterface() 
    {
        parent::ModuleInterface('poll');
    }
    
    
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $CONFIG_POLL;' . "\n";
			
		
		$CONFIG_POLL = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'poll'", __LINE__, __FILE__));
		$CONFIG_POLL = is_array($CONFIG_POLL) ? $CONFIG_POLL : array();

		$code .= '$CONFIG_POLL = ' . var_export($CONFIG_POLL, true) . ';' . "\n";

		$_array_poll = '';
		if (!empty($CONFIG_POLL['poll_mini']) && is_array($CONFIG_POLL['poll_mini']))
		{
			foreach ($CONFIG_POLL['poll_mini'] as $key => $idpoll)
			{
				$poll = $Sql->query_array(PREFIX . 'poll', 'id', 'question', 'votes', 'answers', 'type', "WHERE id = '" . $idpoll . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
				if (!empty($poll['id'])) 
				{
					$array_answer = explode('|', $poll['answers']);
					$array_vote = explode('|', $poll['votes']);
					
					$total_vote = array_sum($array_vote);
					$total_vote = ($total_vote == 0) ? 1 : $total_vote; 
					
					$array_votes = array_combine($array_answer, $array_vote);
					foreach ($array_votes as $answer => $nbrvote)
						$array_votes[$answer] = number_round(($nbrvote * 100 / $total_vote), 1);
						
					$_array_poll .= $key . ' => array(\'id\' => ' . var_export($poll['id'], true) . ', \'question\' => ' . var_export($poll['question'], true) . ', \'votes\' => ' . var_export($array_votes, true) . ', \'total\' => ' . var_export($total_vote, true) . ', \'type\' => ' . var_export($poll['type'], true) . '),' . "\n";
				}
			}
		}
		
		$code .= "\n" . 'global $_array_poll;' . "\n\n" . '$_array_poll = array(' . $_array_poll . ');';

		return $code;
	}

	
	function on_changeday()
	{
		global $Sql;
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "poll_ip WHERE timestamp < '" . (time() - (3600 * 24)) . "' AND user_id = -1", __LINE__, __FILE__);

		
		$result = $Sql->query_while("SELECT id, start, end
		FROM " . PREFIX . "poll
		WHERE visible != 0", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['start'] <= time() && $row['start'] != 0)
				$Sql->query_inject("UPDATE " . PREFIX . "poll SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($row['end'] <= time() && $row['end'] != 0)
				$Sql->query_inject("UPDATE " . PREFIX . "poll SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}
}

?>
