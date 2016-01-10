<?php
/*##################################################
 *                               move.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright          : (C) 2005 Viarre R�gis
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

require_once('../kernel/begin.php'); 
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php'); 

//Variables $_GET.
$id_get = retrieve(GET, 'id', 0); //Id du topic � d�placer.
$id_post = retrieve(POST, 'id', 0); //Id du topic � d�placer.
$id_get_msg = retrieve(GET, 'idm', 0); //Id du message � partir duquel il faut scinder le topic.
$id_post_msg = retrieve(POST, 'idm', 0); //Id du message � partir duquel il faut scinder le topic.
$error_get = retrieve(GET, 'error', ''); //Gestion des erreurs.
$post_topic = retrieve(POST, 'post_topic', ''); //Cr�ation du topic scind�.
$preview_topic = retrieve(POST, 'prw_t', ''); //Pr�visualisation du topic scind�.

if (!empty($id_get)) //D�placement du sujet.
{
	$Template->set_filenames(array(
		'forum_move'=> 'forum/forum_move.tpl',
		'forum_top'=> 'forum/forum_top.tpl',
		'forum_bottom'=> 'forum/forum_bottom.tpl'
	));

	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	if (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) //Acc�s en �dition
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	$cat = $Sql->query_array(PREFIX . 'forum_cats', 'id', 'name', "WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);
	
	$auth_cats = '';
	if (is_array($CAT_FORUM))
	{
		foreach ($CAT_FORUM as $idcat => $key)
		{
			if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM))
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? "AND id NOT IN (" . trim($auth_cats, ',') . ")" : '';
	}

	//Listing des cat�gories disponibles, sauf celle qui va �tre supprim�e.			
	$cat_forum = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, name, level
	FROM " . PREFIX . "forum_cats 
	WHERE url = '' " . $auth_cats . "
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{	
		$disabled = ($row['id'] == $topic['idcat']) ? ' disabled="disabled"' : '';
		$cat_forum .= ($row['level'] > 0) ? '<option value="' . $row['id'] . '"' . $disabled . '>' . str_repeat('--------', $row['level']) . ' ' . $row['name'] . '</option>' : '<option value="' . $row['id'] . '" disabled="disabled">-- ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);
	
	$Template->assign_vars(array(
		'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['move_topic'],
		'ID' => $id_get,
		'TITLE' => $topic['title'],
		'U_MOVE' => url('.php?token=' . $Session->get_token()),
		'CATEGORIES' => $cat_forum,
		'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $topic['title'] . '</a>',
		'L_SELECT_SUBCAT' => $LANG['require_subcat'],
		'L_MOVE_SUBJECT' => $LANG['forum_move_subject'],
		'L_CAT' => $LANG['category'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_SUBMIT' => $LANG['submit']
	));	

	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script LIKE '/forum/%'");

	$Template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online']),
	));
	
	$Template->pparse('forum_move');
}
elseif (!empty($id_post)) //D�placement du topic
{
	$idcat = $Sql->query("SELECT idcat FROM " . PREFIX . "forum_topics WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	if ($User->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM)) //Acc�s en �dition
	{		
		$to = retrieve(POST, 'to', $idcat); //Cat�gorie cible.
		$level = $Sql->query("SELECT level FROM " . PREFIX . "forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
		if (!empty($to) && $level > 0 && $idcat != $to)
		{
			//Instanciation de la class du forum.
			include_once('../forum/forum.class.php');
			$Forumfct = new Forum;

			$Forumfct->Move_topic($id_post, $idcat, $to); //D�placement du topic

			redirect(HOST . DIR . '/forum/topic' . url('.php?id=' . $id_post, '-' .$id_post  . '.php', '&'));
		}
		else
			$Errorh->handler('e_incomplete', E_USER_REDIRECT); 
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
}
elseif ((!empty($id_get_msg) || !empty($id_post_msg)) && empty($post_topic)) //Choix de la nouvelle cat�gorie, titre, sous-titre du topic � scinder.
{
	$Template->set_filenames(array(
		'forum_move'=> 'forum/forum_post.tpl',
		'forum_top'=> 'forum/forum_top.tpl',
		'forum_bottom'=> 'forum/forum_bottom.tpl'
	));

	$idm = !empty($id_get_msg) ? $id_get_msg : $id_post_msg;
	$msg =  $Sql->query_array(PREFIX . 'forum_msg', 'idtopic', 'contents', "WHERE id = '" . $idm . "'", __LINE__, __FILE__);
	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	
	if (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) //Acc�s en �dition
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	$id_first = $Sql->query("SELECT MIN(id) as id FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if ($id_first == $idm)
		$Errorh->handler('e_unable_cut_forum', E_USER_REDIRECT); 
			
	$cat = $Sql->query_array(PREFIX . 'forum_cats', 'id', 'name', "WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);
	$to = retrieve(POST, 'to', $cat['id']); //Cat�gorie cible.
	
	$auth_cats = '';
	if (is_array($CAT_FORUM))
	{	
		foreach ($CAT_FORUM as $idcat => $key)
		{
			if (!$User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM))
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? "AND id NOT IN (" . trim($auth_cats, ',') . ")" : '';
	}
	
	//Listing des cat�gories disponibles, sauf celle qui va �tre supprim�e.			
	$cat_forum = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, name, level
	FROM " . PREFIX . "forum_cats 
	WHERE url = '' " . $auth_cats . "
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{	
		$cat_forum .= ($row['level'] > 0) ? '<option value="' . $row['id'] . '">' . str_repeat('--------', $row['level']) . ' ' . $row['name'] . '</option>' : '<option value="' . $row['id'] . '" disabled="disabled">-- ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);
	
	$Template->assign_vars(array(
		'C_FORUM_CUT_CAT' => true,
		'CATEGORIES' => $cat_forum,
		'KERNEL_EDITOR' => display_editor(),
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('forum'),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['cut_topic'],
		'SID' => SID,			
		'IDTOPIC' => 0,
		'U_ACTION' => url('move.php?token=' . $Session->get_token()),
		'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php') . '">' . ucfirst($topic['title']) . '</a>',	
		'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'L_ACTION' => $LANG['forum_cut_subject'] . ' : ' . $topic['title'],
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_CAT' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_DESC' => $LANG['description'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['forum_cut_subject'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_POLL' => $LANG['poll'],
		'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
		'L_QUESTION' => $LANG['question'],
		'L_ANSWERS' => $LANG['answers'],
		'L_POLL_TYPE' => $LANG['poll_type'],
		'L_SINGLE' => $LANG['simple_answer'],
		'L_MULTIPLE' => $LANG['multiple_answer']
	));
		
	if (empty($post_topic) && empty($preview_topic))
	{
		//Liste des choix des sondages => 20 maxi
		$nbr_poll_field = 0;
		for ($i = 0; $i < 5; $i++)
		{	
			$Template->assign_block_vars('answers_poll', array(
				'ID' => $i,
				'ANSWER' => ''
			));
			$nbr_poll_field++;
		}
		
		$Template->assign_vars(array(
			'TITLE' => '',
			'DESC' => '',
			'CONTENTS' => unparse($msg['contents']),
			'IDM' => $id_get_msg,
			'CHECKED_NORMAL' => 'checked="checked"',
			'SELECTED_SIMPLE' => 'checked="checked"',
			'NO_DISPLAY_POLL' => 'true',
			'NBR_POLL_FIELD' => $nbr_poll_field,
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce'],
			'C_FORUM_POST_TYPE' => true,
			'C_ADD_POLL_FIELD' => true
		));
	}
	elseif (!empty($preview_topic) && !empty($id_post_msg))
	{
		$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
		$subtitle = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
		$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
		$question = retrieve(POST, 'question', '', TSTRING_UNCHANGE);
		$type = retrieve(POST, 'type', 0); 
		
		$checked_normal = ($type == 0) ? 'checked="ckecked"' : '';
		$checked_postit = ($type == 1) ? 'checked="ckecked"' : '';
		$checked_annonce = ($type == 2) ? 'checked="ckecked"' : '';

		//Liste des choix des sondages => 20 maxi
		$nbr_poll_field = 0;
		for ($i = 0; $i < 20; $i++)
		{	
			$answer = retrieve(POST, 'a'.$i, '', TSTRING_UNCHANGE);
			if (!empty($answer))
			{
				$Template->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => $answer
				));
				$nbr_poll_field++;
			}	
			elseif ($i <= 5) //On compl�te s'il y a moins de 5 r�ponses.
			{
				$Template->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}			
		}
		
		//Type de r�ponses du sondage.
		$poll_type = retrieve(POST, 'poll_type', 0);
		
		$Template->assign_vars(array(	
			'TITLE' => $title,
			'DESC' => $subtitle,
			'CONTENTS' => $contents,
			'QUESTION' => $question,
			'IDM' => $id_post_msg,			
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
			'CONTENTS_PREVIEW' => second_parse(stripslashes(strparse($contents))),
			'CHECKED_NORMAL' => $checked_normal,
			'CHECKED_POSTIT' => $checked_postit,
			'CHECKED_ANNONCE' => $checked_annonce,
			'SELECTED_SIMPLE' => ($poll_type == 0) ? 'checked="ckecked"' : '',
			'SELECTED_MULTIPLE' => ($poll_type == 1) ? 'checked="ckecked"' : '',
			'NO_DISPLAY_POLL' => !empty($question) ? 'false' : 'true',
			'NBR_POLL_FIELD' => $nbr_poll_field,
			'C_FORUM_PREVIEW_MSG' => true,
			'C_ADD_POLL_FIELD' => ($nbr_poll_field <= 18) ? true : false,
			'C_FORUM_POST_TYPE' => true,
			'L_PREVIEW' => $LANG['preview'],
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce']
		));
	}
	
	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script LIKE '/forum/%'");

	$Template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	));	
	
	$Template->pparse('forum_move');
}
elseif (!empty($id_post_msg) && !empty($post_topic)) //Scindage du topic
{
	$msg =  $Sql->query_array(PREFIX . 'forum_msg', 'idtopic', 'user_id', 'timestamp', 'contents', "WHERE id = '" . $id_post_msg . "'", __LINE__, __FILE__);
	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', 'last_user_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	$to = retrieve(POST, 'to', 0); //Cat�gorie cible.
	
	if (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) //Acc�s en �dition
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$id_first = $Sql->query("SELECT MIN(id) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if ($id_first == $id_post_msg)
		$Errorh->handler('e_unable_cut_forum', E_USER_REDIRECT); 
	
	$level = $Sql->query("SELECT level FROM " . PREFIX . "forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
	if (!empty($to) && $level > 0)
	{
		$title = retrieve(POST, 'title', '');
		$subtitle = retrieve(POST, 'desc', '');
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$type = retrieve(POST, 'type', 0); 
		
		//Requ�te de "scindage" du topic.
		if (!empty($to) && !empty($contents) && !empty($title))
		{
			//Instanciation de la class du forum.
			include_once('../forum/forum.class.php');
			$Forumfct = new Forum;

			$last_topic_id = $Forumfct->Cut_topic($id_post_msg, $msg['idtopic'], $topic['idcat'], $to, $title, $subtitle, $contents, $type, $msg['user_id'], $topic['last_user_id'], $topic['last_msg_id'], $topic['last_timestamp']); //Scindement du topic
			
			//Ajout d'un sondage en plus du topic.
			$question = retrieve(POST, 'question', '');
			if (!empty($question))
			{
				$poll_type = retrieve(POST, 'poll_type', 0);
				$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;
				
				$answers = array();
				$nbr_votes = 0;
				for ($i = 0; $i < 20; $i++)
				{
					$answer = str_replace('|', '', retrieve(POST, 'a'.$i, ''));
					if (!empty($answer))
					{				
						$answers[$i] = $answer;
						$nbr_votes++;
					}
				}
				$Forumfct->Add_poll($last_topic_id, $question, $answers, $nbr_votes, $poll_type); //Ajout du sondage.
			}
			
			redirect(HOST . DIR . '/forum/topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));
		}
		else
			redirect(url(HOST . SCRIPT . '?error=false_t&idm=' . $id_post_msg, '', '&') . '#errorh');
	}
	else
		$Errorh->handler('e_incomplete', E_USER_REDIRECT); 
}
else
	$Errorh->handler('unknow_error', E_USER_REDIRECT); 

include('../kernel/footer.php');

?>