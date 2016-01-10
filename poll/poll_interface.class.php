<?php
/*##################################################
 *                              poll_interface.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe ForumInterface qui h�rite de la classe ModuleInterface
class PollInterface extends ModuleInterface
{
    ## Public Methods ##
    function PollInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('poll');
    }
    
    //R�cup�ration du cache.
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $CONFIG_POLL;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_POLL = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'poll'", __LINE__, __FILE__));
		$CONFIG_POLL = is_array($CONFIG_POLL) ? $CONFIG_POLL : array();

		$code .= '$CONFIG_POLL = ' . var_export($CONFIG_POLL, true) . ';' . "\n";

		$_array_poll = '';
		if (!empty($CONFIG_POLL['poll_mini']) && is_array($CONFIG_POLL['poll_mini']))
		{
			foreach ($CONFIG_POLL['poll_mini'] as $key => $idpoll)
			{
				$poll = $Sql->query_array(PREFIX . 'poll', 'id', 'question', 'votes', 'answers', 'type', "WHERE id = '" . $idpoll . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
				if (!empty($poll['id'])) //Sondage existant.
				{
					$array_answer = explode('|', $poll['answers']);
					$array_vote = explode('|', $poll['votes']);
					
					$total_vote = array_sum($array_vote);
					$total_vote = ($total_vote == 0) ? 1 : $total_vote; //Emp�che la division par 0.
					
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

	//Actions journali�e.
	function on_changeday()
	{
		global $Sql;
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "poll_ip WHERE timestamp < '" . (time() - (3600 * 24)) . "' AND user_id = -1", __LINE__, __FILE__);

		//Publication des news en attente pour la date donn��.
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