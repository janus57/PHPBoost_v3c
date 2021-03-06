<?php


























define('NO_HISTORY', false);
define('FORUM_EMAIL_TRACKING', 1);
define('FORUM_PM_TRACKING', 2);

class Forum
{
	## Public Methods ##
	
	function Forum()
	{
	}

	
	function Add_msg($idtopic, $idcat, $contents, $title, $last_page, $last_page_rewrite, $new_topic = false)
	{
		global $CONFIG, $Sql, $User, $CAT_FORUM, $LANG;

		##### Insertion message #####
		$last_timestamp = time();
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_msg (idtopic, user_id, contents, timestamp, timestamp_edit, user_id_edit, user_ip) VALUES ('" . $idtopic . "', '" . $User->get_attribute('user_id') . "', '" . strparse($contents) . "', '" . $last_timestamp . "', '0', '0', '" . USER_IP . "')", __LINE__, __FILE__);
		$last_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_msg");

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET " . ($new_topic ? '' : 'nbr_msg = nbr_msg + 1, ') . "last_user_id = '" . $User->get_attribute('user_id') . "', last_msg_id = '" . $last_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $idtopic . "', nbr_msg = nbr_msg + 1" . ($new_topic ? ', nbr_topic = nbr_topic + 1' : '') . " WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);

		
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_msg = user_msg + 1 WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);

		
		mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp);

		##### Gestion suivi du sujet mp/mail #####
		if (!$new_topic)
		{
			
			$previous_msg_id = $Sql->query("SELECT MAX(id) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $last_msg_id . "'", __LINE__, __FILE__);

			$title_subject = html_entity_decode($title, ENT_COMPAT, 'ISO-8859-1');
			$title_subject_pm = '[url=' . HOST . DIR . '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id . ']' . $title_subject . '[/url]';
			if ($User->get_attribute('user_id') > 0)
			{
				$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
				$pseudo_pm = '[url=' . HOST . DIR . '/member/member.php?id=' . $User->get_attribute('user_id') . ']' . $pseudo . '[/url]';
			}
			else
			{
				$pseudo = $LANG['guest'];
				$pseudo_pm = $LANG['guest'];
			}
			$next_msg_link = HOST . DIR . '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id;
			$preview_contents = substr($contents, 0, 300);

			import('io/mail');
			$Mail = new Mail();
			import('members/pm');
			$Privatemsg = new PrivateMsg();

			
			$max_time = time() - $CONFIG['site_session_invit'];
			$result = $Sql->query_while("SELECT m.user_id, m.login, m.user_mail, tr.pm, tr.mail, v.last_view_id
			FROM " . PREFIX . "forum_track tr
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = tr.user_id
			LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = '" . $idtopic . "' AND v.user_id = tr.user_id
			WHERE tr.idtopic = '" . $idtopic . "' AND v.last_view_id IS NOT NULL AND m.user_id != '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				
				if ($row['last_view_id'] == $previous_msg_id && $row['mail'] == '1')
				{	
					$Mail->send_from_properties(
						$row['user_mail'], 
						$LANG['forum_mail_title_new_post'], 
						sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject, $User->get_attribute('login'), $preview_contents, $next_msg_link, HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=1', 1), 
						$CONFIG['mail_exp']
					);
				}	
				
				
				if ($row['last_view_id'] == $previous_msg_id && $row['pm'] == '1')
				{
					$Privatemsg->start_conversation(
						$row['user_id'], 
						addslashes($LANG['forum_mail_title_new_post']), 
						sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject_pm, $User->get_attribute('login'), $preview_contents, '[url]'.$next_msg_link.'[/url]', '[url]' . HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=2[/url]'), 
						'-1', 
						SYSTEM_PM
					);
				}
			}
				
			forum_generate_feeds(); 
		}

		return $last_msg_id;
	}

	
	function Add_topic($idcat, $title, $subtitle, $contents, $type)
	{
		global $Sql, $User;

		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob, display_msg) VALUES ('" . $idcat . "', '" . $title . "', '" . $subtitle . "', '" . $User->get_attribute('user_id') . "', 1, 0, '" . $User->get_attribute('user_id') . "', '0', '" . time() . "', 0, '" . $type . "', 1, 0, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_topics");	

		$last_msg_id = $this->Add_msg($last_topic_id, $idcat, $contents, $title, 0, 0, true); 
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET first_msg_id = '" . $last_msg_id . "' WHERE id = '" . $last_topic_id . "'", __LINE__, __FILE__);

		forum_generate_feeds(); 

		return array($last_topic_id, $last_msg_id);
	}

	
	function Update_msg($idtopic, $idmsg, $contents, $user_id_msg, $history = true)
	{
		global $Sql, $User, $Group, $CONFIG_FORUM;

		
		$edit_mark = (!$User->check_auth($CONFIG_FORUM['auth'], EDIT_MARK_FORUM)) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . $User->get_attribute('user_id') . "'" : '';
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . strparse($contents) . "'" . $edit_mark . " WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);

		$nbr_msg_before = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);

		
		$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
		$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
		$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
			
		
		if ($User->get_attribute('user_id') != $user_id_msg && $history)
		forum_history_collector(H_EDIT_MSG, $user_id_msg, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $idmsg);

		return $nbr_msg_before;
	}

	
	function Update_topic($idtopic, $idmsg, $title, $subtitle, $contents, $type, $user_id_msg)
	{
		global $Sql, $User;

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET title = '" . $title . "', subtitle = '" . $subtitle . "', type = '" . $type . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		
		$this->Update_msg($idtopic, $idmsg, $contents, $user_id_msg, NO_HISTORY);

		
		if ($User->get_attribute('user_id') != $user_id_msg)
		forum_history_collector(H_EDIT_TOPIC, $user_id_msg, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	
	function Del_msg($idmsg, $idtopic, $idcat, $first_msg_id, $last_msg_id, $last_timestamp, $msg_user_id)
	{
		global $Sql, $User, $CAT_FORUM, $CONFIG_FORUM;

		if ($first_msg_id != $idmsg) 
		{
			
			$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
			
			$previous_msg_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "' ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

			if ($last_msg_id == $idmsg) 
			{
				
				$id_before_last = $Sql->query_array(PREFIX . 'forum_msg', 'user_id', 'timestamp', "WHERE id = '" . $previous_msg_id . "'", __LINE__, __FILE__);
				$last_timestamp = $id_before_last['timestamp'];
				$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $id_before_last['user_id'] . "', last_msg_id = '" . $previous_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

				
				$this->Update_last_topic_id($idcat);
			}
				
			
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_msg = user_msg - 1 WHERE user_id = '" . $msg_user_id . "'", __LINE__, __FILE__);
				
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_view SET last_view_id = '" . $previous_msg_id . "' WHERE last_view_id = '" . $idmsg . "'", __LINE__, __FILE__);
			
			if ($last_msg_id == $idmsg)
			mark_topic_as_read($idtopic, $previous_msg_id, $last_timestamp);
				
			
			if ($msg_user_id != $User->get_attribute('user_id'))
			{
				
				$msg_page = ceil($nbr_msg / $CONFIG_FORUM['pagination_msg']);
				$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
				$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
				forum_history_collector(H_DELETE_MSG, $msg_user_id, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			}
			forum_generate_feeds(); 
				
			return array($nbr_msg, $previous_msg_id);
		}

		return array(false, false);
	}

	
	function Del_topic($idtopic, $generate_rss = true)
	{
		global $Sql, $User, $CAT_FORUM;

		$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'user_id', "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['user_id'] = (int)$topic['user_id'];

		
		
		$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;

		
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_topics WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic - 1, nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$topic['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$topic['idcat']]['id_right'] ."' AND level <= '" . $CAT_FORUM[$topic['idcat']]['level'] . "'", __LINE__, __FILE__);

		
		$this->Update_last_topic_id($topic['idcat']);

		
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_view WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		
		$this->Del_alert_topic($idtopic);
		
		
		if ($topic['user_id'] != $User->get_attribute('user_id'))
			forum_history_collector(H_DELETE_TOPIC, $topic['user_id'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));

		if ($generate_rss)
			forum_generate_feeds(); 
	}

	
	function Track_topic($idtopic, $tracking_type = 0)
	{
		global $Sql, $Group, $User, $CONFIG_FORUM;

		list($mail, $pm, $track) = array(0, 0, 0);
		if ($tracking_type == 0) 
			$track = '1';
		elseif ($tracking_type == 1) 
			$mail = '1';
		elseif ($tracking_type == 2) 
			$pm = '1';
			
		$exist = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_track WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		if ($exist == 0)
			$Sql->query_inject("INSERT INTO " . PREFIX . "forum_track (idtopic, user_id, track, pm, mail) VALUES('" . $idtopic . "', '" . $User->get_attribute('user_id') . "', '" . $track . "', '" . $pm . "', '" . $mail . "')", __LINE__, __FILE__);
		elseif ($tracking_type == 0)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET track = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		elseif ($tracking_type == 1)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET mail = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		elseif ($tracking_type == 2)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET pm = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			
		
		if (!$User->check_auth($CONFIG_FORUM['auth'], TRACK_TOPIC_FORUM))
		{
			
			$Sql->query("SELECT @compt := id
			FROM " . PREFIX . "forum_track
			WHERE user_id = '" . $User->get_attribute('user_id') . "'
			ORDER BY id DESC
			" . $Sql->limit(0, $CONFIG_FORUM['topic_track']), __LINE__, __FILE__);
				
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id < @compt", __LINE__, __FILE__);
		}
	}

	
	function Untrack_topic($idtopic, $tracking_type = 0)
	{
		global $Sql, $User;

		if ($tracking_type == 1) 
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "pm", "track", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['track'] == 0 && $info['pm'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET mail = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
		elseif ($tracking_type == 2) 
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "mail", "track", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['mail'] == 0 && $info['track'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET pm = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
		else 
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "mail", "pm", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['mail'] == 0 && $info['pm'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET track = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
	}

	
	function Lock_topic($idtopic)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET status = 0 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		
		forum_history_collector(H_LOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	
	function Unlock_topic($idtopic)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET status = 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		
		forum_history_collector(H_UNLOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	
	function Move_topic($idtopic, $idcat, $idcat_dest)
	{
		global $Sql, $User, $CAT_FORUM;

		
		$topic = $Sql->query_array(PREFIX . "forum_topics", "user_id", "nbr_msg", "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['nbr_msg'] = !empty($topic['nbr_msg']) ? numeric($topic['nbr_msg']) : 1;

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET idcat = '" . $idcat_dest . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		
		$this->Update_last_topic_id($idcat);

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
		
		$this->Update_last_topic_id($idcat_dest);

		
		forum_history_collector(H_MOVE_TOPIC, $topic['user_id'], 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	
	function Cut_topic($id_msg_cut, $idtopic, $idcat, $idcat_dest, $title, $subtitle, $contents, $type, $msg_user_id, $last_user_id, $last_msg_id, $last_timestamp)
	{
		global $Sql, $User, $CAT_FORUM;

		
		$nbr_msg = $Sql->query("SELECT COUNT(*) as compt FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;

		
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob) VALUES ('" . $idcat_dest . "', '" . $title . "', '" . $subtitle . "', '" . $msg_user_id . "', '" . $nbr_msg . "', 0, '" . $last_user_id . "', '" . $last_msg_id . "', '" . $last_timestamp . "', '" . $id_msg_cut . "', '" . $type . "', 1, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_topics");	

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . $contents . "' WHERE id = '" . $id_msg_cut . "'", __LINE__, __FILE__);

		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET idtopic = '" . $last_topic_id . "' WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);

		
		$previous_topic = $Sql->query_array(PREFIX . 'forum_msg', 'id', 'user_id', 'timestamp', "WHERE id < '" . $id_msg_cut . "' AND idtopic = '" . $idtopic . "' ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		
		if ($idcat != $idcat_dest)
		{
			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
			
			$this->Update_last_topic_id($idcat_dest);

			
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		}
		else 
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);

		
		$this->Update_last_topic_id($idcat);
			
		
		$Sql->query_inject("UPDATE " . PREFIX . "forum_view SET last_view_id = '" . $previous_topic['id'] . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		
		forum_history_collector(H_CUT_TOPIC, 0, 'topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));

		return $last_topic_id;
	}

	
	function Merge_topic($idtopic, $idtopic_merge)
	{
		global $Sql;

	}

	
	function Alert_topic($alert_post, $alert_title, $alert_contents)
	{
		global $Sql, $User, $CAT_FORUM, $LANG;

		$topic_infos = $Sql->query_array(PREFIX . "forum_topics", "idcat", "title", "WHERE id = '" . $alert_post . "'", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_alerts (idcat, idtopic, title, contents, user_id, status, idmodo, timestamp) VALUES ('" . $topic_infos['idcat'] . "', '" . $alert_post . "', '" . $alert_title . "', '" . $alert_contents . "', '" . $User->get_attribute('user_id') . "', 0, 0, '" . time() . "')", __LINE__, __FILE__);

		$alert_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_alerts");

		
		import('events/contribution');
		import('events/contribution_service');

		$contribution = new Contribution();

		
		$contribution->set_id_in_module($alert_id);
		
		$contribution->set_entitled(sprintf($LANG['contribution_alert_moderators_for_topics'], stripslashes($alert_title)));
		
		$contribution->set_fixing_url('/forum/moderation_forum.php?action=alert&id=' . $alert_id);
		
		$contribution->set_description(stripslashes($alert_contents));
		
		$contribution->set_poster_id($User->get_attribute('user_id'));
		
		$contribution->set_module('forum');
		
		$contribution->set_type('alert');

		
		$contribution->set_auth(
		
		
		Authorizations::capture_and_shift_bit_auth(
		$CAT_FORUM[$topic_infos['idcat']]['auth'],
		EDIT_CAT_FORUM, CONTRIBUTION_AUTH_BIT
		)
		);

		
		ContributionService::save_contribution($contribution);
	}

	
	function Solve_alert_topic($id_alert)
	{
		global $Sql, $User;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_alerts SET status = 1, idmodo = '" . $User->get_attribute('user_id') . "' WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);

		
		forum_history_collector(H_SOLVE_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert, '', '&');

		
		import('events/contribution');
		import('events/contribution_service');
		 
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];
			
			$file_contribution->set_status(EVENT_STATUS_PROCESSED);

			
			ContributionService::save_contribution($file_contribution);
		}
	}

	
	function Wait_alert_topic($id_alert)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_alerts SET status = 0, idmodo = 0 WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);

		
		forum_history_collector(H_WAIT_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert);
	}

	
	function Del_alert_topic($id_alert)
	{
		global $Sql;

		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_alerts WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
		
		
		import('events/contribution');
		import('events/contribution_service');
		 
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];

			
			ContributionService::delete_contribution($file_contribution);
		}

		
		forum_history_collector(H_DEL_ALERT);
	}

	
	function Add_poll($idtopic, $question, $answers, $nbr_votes, $type)
	{
		global $Sql;

		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_poll (idtopic, question, answers, voter_id, votes,type) VALUES ('" . $idtopic . "', '" . $question . "', '" . implode('|', $answers) . "', '0', '" . trim(str_repeat('0|', $nbr_votes), '|') . "', '" . numeric($type) . "')", __LINE__, __FILE__);
	}

	
	function Update_poll($idtopic, $question, $answers, $type)
	{
		global $Sql;

		
		$previous_votes = explode('|', $Sql->query("SELECT votes FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__));

		$votes = array();
		foreach ($answers as $key => $answer_value) 
		$votes[$key] = isset($previous_votes[$key]) ? $previous_votes[$key] : 0;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_poll SET question = '" . $question . "', answers = '" . implode('|', $answers) . "', votes = '" . implode('|', $votes) . "', type = '" . $type . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}

	
	function Del_poll($idtopic)
	{
		global $Sql;

		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}


	



	function get_cats_tree()
	{
		global $LANG, $CAT_FORUM;
		Cache::load('forum');
	  
		if (!(isset($CAT_FORUM) && is_array($CAT_FORUM)))
		{
			$CAT_ARTICLES = array();
		}

		$ordered_cats = array();
		foreach ($CAT_FORUM as $id => $cat)
		{   
			$cat['id'] = $id;
			$ordered_cats[numeric($cat['id_left'])] = array('this' => $cat, 'children' => array());
		}
	  
		$level = 0;
		$cats_tree = array(array('this' => array('id' => 0, 'name' => $LANG['root']), 'children' => array()));
		$parent =& $cats_tree[0]['children'];
		$nb_cats = count($CAT_FORUM);
		foreach ($ordered_cats as $cat)
		{
			if (($cat['this']['level'] == $level + 1) && count($parent) > 0)
			{   
				$parent =& $parent[count($parent) - 1]['children'];
			}
			elseif ($cat['this']['level'] < $level)
			{   
				$j = 0;
				$parent =& $cats_tree[0]['children'];
				while ($j < $cat['this']['level'])
				{
					$parent =& $parent[count($parent) - 1]['children'];
					$j++;
				}
			}

			
			$parent[] = $cat;
			$level = $cat['this']['level'];
		}
		return $cats_tree[0];
	}

	## Private Method ##
	
	function update_last_topic_id($idcat)
	{
		global $Sql, $CAT_FORUM;

		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) 
		{
			
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
				
			while ($row = $Sql->fetch_assoc($result))
			$list_cats .= $row['id'] . ', ';
				
			$Sql->query_close($result);
			$clause = "idcat IN (" . trim($list_cats, ', ') . ")";
		}

		
		$last_timestamp = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE " . $clause, __LINE__, __FILE__);
		$last_topic_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . $last_timestamp . "'", __LINE__, __FILE__);
		if (!empty($last_topic_id))
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			
		if ($CAT_FORUM[$idcat]['level'] > 1) 
		{
			
			$idcat_parent = $Sql->query("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "' AND level = '" .  ($CAT_FORUM[$idcat]['level'] - 1) . "'", __LINE__, __FILE__);

			$this->Update_last_topic_id($idcat_parent); 
		}
	}

	
	function array_diff_key_emulate()
	{
		$args = func_get_args();
		if (count($args) < 2) {
			user_error('Wrong parameter count for array_diff_key()', E_USER_WARNING);
			return;
		}

		
		$array_count = count($args);
		for ($i = 0; $i !== $array_count; $i++) {
			if (!is_array($args[$i])) {
				user_error('array_diff_key() Argument #' .
				($i + 1) . ' is not an array', E_USER_WARNING);
				return;
			}
		}

		$result = $args[0];
		foreach ($args[0] as $key1 => $value1) {
			for ($i = 1; $i !== $array_count; $i++) {
				foreach ($args[$i] as $key2 => $value2) {
					if ((string) $key1 === (string) $key2) {
						unset($result[$key2]);
						break 2;
					}
				}
			}
		}
		return $result;
	}
}

?>
