<?php


























include_once('../kernel/begin.php');
include_once('faq_begin.php');


$faq_del_id = retrieve(GET, 'del', 0);
$down = retrieve(GET, 'down', 0);
$up = retrieve(GET, 'up', 0);
$id_question = retrieve(POST, 'id_question', 0);
$entitled = retrieve(POST, 'entitled', '');
$answer = retrieve(POST, 'answer', '', TSTRING_PARSE);
$new_id_cat = retrieve(POST, 'id_cat', 0);
$id_after = retrieve(POST, 'after', 0);

$cat_properties = retrieve(GET, 'cat_properties', 0);
$id_cat = retrieve(POST, 'id_faq', 0);
$display_mode = retrieve(POST, 'display_mode', 0);
$global_auth = retrieve(POST, 'global_auth', array());
$cat_name = retrieve(POST, 'cat_name', '');
$description = retrieve(POST, 'description', '', TSTRING_PARSE);

$target = retrieve(POST, 'target', 0);
$move_question = retrieve(POST, 'move_question', false);

if ($faq_del_id > 0)
{    
    
    $Session->csrf_get_protect();

	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__);
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write)
	{
		if (!empty($faq_infos['question'])) 
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order > '" . $faq_infos['q_order'] . "'", __LINE__, __FILE__); 
			$Sql->query_inject("DELETE FROM " . PREFIX . "faq WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__);			 
			if ($faq_infos['idcat'] != 0)
			{
				include_once('faq_cats.class.php');
				$faq_cats = new FaqCats();
				$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($faq_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('faq');
			redirect(HOST . DIR . url('/faq/management.php?faq=' . $faq_infos['idcat'], '', '&'));
		}
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif ($down > 0)
{
	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $down . "'", __LINE__, __FILE__);
	
	$num_questions = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "faq WHERE idcat = '" . $faq_infos['idcat'] . "'", __LINE__, __FILE__);
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write && !empty($faq_infos['question'])) 
	{
		if ($faq_infos['q_order'] < $num_questions) 
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] + 1) . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE id = '" . $down . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . url('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . ($faq_infos['q_order'] + 1), '', '&'));
		}
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif ($up > 0)
{
	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $up . "'", __LINE__, __FILE__);
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write && !empty($faq_infos['question'])) 
	{
		if ($faq_infos['q_order'] > 1) 
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] - 1) . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE id = '" . $up . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . url('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . ($faq_infos['q_order'] - 1), '', '&'));
		}
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}

elseif (!empty($entitled) && !empty($answer))
{
	if ($id_question > 0)
	{
		$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', "WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		$id_cat_for_bread_crumb = $faq_infos['idcat'];
		include('faq_bread_crumb.php');
		if ($auth_write)
		{			
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET question = '" . $entitled . "', answer = '" . $answer . "' WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . '/faq/' . url('faq.php?id=' . $faq_infos['idcat'] . '&question=' . $id_question, 'faq-' . $faq_infos['idcat'] . '+' . url_encode_rewrite($FAQ_CATS[$faq_infos['idcat']]['name']) . '.php?question=' . $id_question, '&') . '#q' . $id_question);
			
		}
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	else
	{
		$id_cat_for_bread_crumb = $new_id_cat;
		include('faq_bread_crumb.php');
		if ($auth_write)
		{
			
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE idcat = '" . $new_id_cat . "' AND q_order > '" . $id_after . "'", __LINE__, __FILE__);
			$Sql->query_inject("INSERT INTO " . PREFIX . "faq (idcat, q_order, question, answer, user_id, timestamp) VALUES ('" . $new_id_cat . "', '" . ($id_after + 1 ) . "', '" . $entitled . "', '" . $answer . "', '" . $User->get_attribute('user_id') . "', '" . time() . "')", __LINE__, __FILE__);
			
			$new_question_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "faq");
			
			
			if ($new_id_cat != 0)
			{
				include_once('faq_cats.class.php');
				$faq_cats = new FaqCats();
				$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($new_id_cat, ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('faq');
			
			
			$Cache->load('faq', RELOAD_CACHE);
	
			redirect(HOST . DIR . '/faq/' . url('faq.php?id=' . $new_id_cat . '&question=' . $new_question_id, 'faq-' . $new_id_cat . '+' . url_encode_rewrite($FAQ_CATS[$new_id_cat]['name']) . '.php?question=' . $new_question_id, '&') . '#q' . $new_question_id);
		}
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
}
elseif ($cat_properties && (!empty($cat_name) || $id_cat == 0))
{
	$id_cat_for_bread_crumb = $id_cat;
	include('faq_bread_crumb.php');
	if ($auth_write)
	{
		if ($global_auth)
		{
			$array_auth_all = Authorizations::build_auth_array_from_form(AUTH_READ, AUTH_WRITE);
			$new_auth = addslashes(serialize($array_auth_all));
		}
		else
			$new_auth = '';
			
		$display_mode = ($display_mode <= 2 || $display_mode >= 0) ? $display_mode : 0;

		
		if ($id_cat > 0)
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET display_mode = '" . $display_mode . "', auth = '" . $new_auth . "', description = '" . $description . "', name = '" . $cat_name . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
		}
		
		else
		{
			$FAQ_CONFIG['root'] = array(
				'display_mode' => $display_mode,
				'auth' => $FAQ_CATS[0]['auth'],
				'description' => stripslashes($description)
			);
			$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
		}
		$Cache->Generate_module_file('faq');
		redirect(HOST . DIR . url('/faq/management.php?faq=' . $id_cat, '', '&'));
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}

elseif ($id_question > 0 && $move_question && $target >= 0)
{
	
	if (array_key_exists($target, $FAQ_CATS) || $target == 0)
	{
		$question_infos = $Sql->query_array(PREFIX . "faq", "*", "WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		$id_cat_for_bread_crumb = $question_infos['idcat'];
		$auth_write = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);
		while ($id_cat_for_bread_crumb > 0)
		{
			$id_cat_for_bread_crumb = (int)$FAQ_CATS[$id_cat_for_bread_crumb]['id_parent'];
			if (!empty($FAQ_CATS[$id_cat_for_bread_crumb]['auth']))
				$auth_write = $User->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_WRITE);
		}
		if ($auth_write)
		{
			if ($target != $question_infos['idcat'])
			{
				$max_order = $Sql->query("SELECT MAX(q_order) FROM " . PREFIX . "faq WHERE idcat = '" . $target . "'", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "faq SET idcat = '" . $target . "', q_order = '" . ($max_order + 1) . "' WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $question_infos['idcat'] . "' AND q_order > '" . $question_infos['q_order'] . "'", __LINE__, __FILE__);
				
				
				if ($question_infos['idcat'] != 0)
				{
					include_once('faq_cats.class.php');
					$faq_cats = new FaqCats();
					$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($question_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
				}
				
				
				if ($target != 0)
				{
					include_once('faq_cats.class.php');
					$faq_cats = new FaqCats();
					$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($target, ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
				}
				
				if ($question_infos['idcat'] != 0 || $target != 0)
					$Cache->Generate_module_file('faq');
			}
			redirect(HOST . DIR . url('/faq/management.php?faq=' . $target, '', '&'));
		}
	}
	$Errorh->handler('e_auth', E_USER_REDIRECT);
}
else
	redirect(HOST . DIR . url('/faq/faq.php', '', '&'));

?>
