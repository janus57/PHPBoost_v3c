<?php


























require_once('../kernel/begin.php');

load_module_lang('download'); 
$Cache->load('download');

include_once('download_auth.php');

import('util/date');
import('util/mini_calendar');

include_once('download_cats.class.php');
$download_categories = new DownloadCats();

$edit_file_id = retrieve(GET, 'edit', 0);
$add_file = retrieve(GET, 'new', false);
$preview = retrieve(POST, 'preview', false);
$submit = retrieve(POST, 'submit', false);
$selected_cat = retrieve(GET, 'idcat', 0);
$delete_file = retrieve(GET, 'del', 0);

if ($delete_file || ($submit && ($add_file || $edit_file_id > 0)))
    $Session->csrf_get_protect();


$file_title = retrieve(POST, 'title', '');
$file_image = retrieve(POST, 'image', '');
$file_contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
$file_short_contents = retrieve(POST, 'short_contents', '', TSTRING_AS_RECEIVED);
$file_url = retrieve(POST, 'url', '');
$file_timestamp = retrieve(POST, 'timestamp', 0);
$file_size = retrieve(POST, 'size', 0.0, TUNSIGNED_FLOAT);
$file_hits = retrieve(POST, 'count', 0, TUNSIGNED_INT);
$file_cat_id = retrieve(REQUEST, 'idcat', 0);
$file_visibility = retrieve(POST, 'visibility', 0);
$file_approved = retrieve(POST, 'approved', false);
$ignore_release_date = retrieve(POST, 'ignore_release_date', false);
$file_download_method = retrieve(POST, 'download_method', 'redirect', TSTRING);


$file_creation_date = MiniCalendar::retrieve_date('creation');

if (!$ignore_release_date)
	$file_release_date = MiniCalendar::retrieve_date('release_date');
else
	$file_release_date = new Date(DATE_NOW, TIMEZONE_AUTO);

$begining_date = MiniCalendar::retrieve_date('begining_date');
$end_date = MiniCalendar::retrieve_date('end_date');


if ($delete_file > 0)
{
    
    $Session->csrf_get_protect();
	$file_infos = $Sql->query_array(PREFIX . 'download', '*', "WHERE id = '" . $delete_file . "'", __LINE__, __FILE__);
	if (empty($file_infos['title']))
		redirect(HOST. DIR . url('/download/download.php'));
	
	if ($download_categories->check_auth($file_infos['idcat']))
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "download WHERE id = '" . $delete_file . "'", __LINE__, __FILE__);
		
		if ($file_infos['nbr_com'] > 0)
		{
			import('content/comments');
			$Comments = new Comments('download', $delete_file, url('download.php?id=' . $delete_file . '&amp;com=%s', 'download-' . $delete_file . '.php?com=%s'));
			$Comments->delete_all($delete_file);
		}
		redirect(HOST. DIR . '/download/' . ($file_infos['idcat'] > 0 ? url('download.php?cat=' . $file_infos['idcat'], 'category-' . $file_infos['idcat'] . '+' . url_encode_rewrite($DOWNLOAD_CATS[$file_infos['idcat']]['name']) . '.php') : url('download.php')));
        
        
        import('content/syndication/feed');
        Feed::clear_cache('download');
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}

elseif ($edit_file_id > 0)
{
	$file_infos = $Sql->query_array(PREFIX . 'download', '*', "WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);
	
	if (empty($file_infos['title']))
		redirect(HOST. DIR . url('/download/download.php'));
	define('TITLE', $DOWNLOAD_LANG['file_management']);
	
	
	$auth_write = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
	
	$Bread_crumb->add($DOWNLOAD_LANG['file_management'], url('management.php?edit=' . $edit_file_id));
	
	$Bread_crumb->add($file_infos['title'], url('download.php?id=' . $edit_file_id, 'download-' . $edit_file_id . '+' . url_encode_rewrite($file_infos['title']) . '.php'));
	
	$id_cat = $file_infos['idcat'];

	
	while ($id_cat > 0)
	{
		$Bread_crumb->add($DOWNLOAD_CATS[$id_cat]['name'], url('download.php?id=' . $id_cat, 'category-' . $id_cat . '+' . url_encode_rewrite($DOWNLOAD_CATS[$id_cat]['name']) . '.php'));
		
		if (!empty($DOWNLOAD_CATS[$id_cat]['auth']))
			$auth_write = $User->check_auth($DOWNLOAD_CATS[$id_cat]['auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
		
		$id_cat = (int)$DOWNLOAD_CATS[$id_cat]['id_parent'];
	}
	
	if (!$auth_write)
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
else
{
	$Bread_crumb->add($DOWNLOAD_LANG['file_addition'], url('management.php?new=1'));
	define('TITLE', $DOWNLOAD_LANG['file_addition']);
	
	if (!($auth_write = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT)) && !($auth_contribute = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT)))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}

$Bread_crumb->add($DOWNLOAD_LANG['download'], url('download.php'));

$Bread_crumb->reverse();

require_once('../kernel/header.php');

$Template->set_filenames(array(
	'file_management'=> 'download/file_management.tpl'
));

$Template->assign_vars(array(
	'KERNEL_EDITOR' => display_editor(),
	'KERNEL_EDITOR_SHORT' => display_editor('short_contents'),
	'C_PREVIEW' => $preview,
	'L_PAGE_TITLE' => TITLE,
	'L_EDIT_FILE' => $DOWNLOAD_LANG['edit_file'],
	'L_YES' => $LANG['yes'],
	'L_NO' => $LANG['no'],
	'L_DOWNLOAD_DATE' => $DOWNLOAD_LANG['download_date'],
	'L_IGNORE_RELEASE_DATE' => $DOWNLOAD_LANG['ignore_release_date'],
	'L_RELEASE_DATE' => $DOWNLOAD_LANG['release_date'],
	'L_FILE_VISIBILITY' => $DOWNLOAD_LANG['file_visibility'],
	'L_APPROVED' => $DOWNLOAD_LANG['approved'],
	'L_NOW' => $LANG['now'],
	'L_HIDDEN' => $DOWNLOAD_LANG['hidden'],
	'L_TO_DATE' => $LANG['to_date'],
	'L_FROM_DATE' => $LANG['from_date'],
	'L_DESC' => $LANG['description'],
	'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
	'L_SIZE' => $LANG['size'],
	'L_URL' => $LANG['url'],
	'L_FILE_IMAGE' => $DOWNLOAD_LANG['file_image'],
	'L_TITLE' => $LANG['title'],
	'L_CATEGORY' => $LANG['category'],
	'L_REQUIRE' => $LANG['require'],
	'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
	'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
	'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_UNIT_SIZE' => $LANG['unit_megabytes'],
	'L_CONTENTS' => $DOWNLOAD_LANG['complete_contents'],
	'L_SHORT_CONTENTS' => $DOWNLOAD_LANG['short_contents'],
	'L_SUBMIT' => $edit_file_id > 0 ? $DOWNLOAD_LANG['update_file'] : $DOWNLOAD_LANG['add_file'],
	'L_WARNING_PREVIEWING' => $DOWNLOAD_LANG['warning_previewing'],
	'L_REQUIRE_DESCRIPTION' => $DOWNLOAD_LANG['require_description'],
	'L_REQUIRE_URL' => $DOWNLOAD_LANG['require_url'],
	'L_REQUIRE_CREATION_DATE' => $DOWNLOAD_LANG['require_creation_date'],
	'L_REQUIRE_RELEASE_DATE' => $DOWNLOAD_LANG['require_release_date'],
	'L_REQUIRE_TITLE' => $LANG['require_title'],
	'L_CONTRIBUTION_LEGEND' => $LANG['contribution'],
    'L_NUMBER_OF_HITS' => $DOWNLOAD_LANG['number_of_hits'],
    'L_DOWNLOAD_METHOD' => $DOWNLOAD_LANG['download_method'],
    'L_DOWNLOAD_METHOD_EXPLAIN' => $DOWNLOAD_LANG['download_method_explain'],
    'L_FORCE_DOWNLOAD' => $DOWNLOAD_LANG['force_download'],
    'L_REDIRECTION' => $DOWNLOAD_LANG['redirection_up_to_file']
));

if ($edit_file_id > 0)
{
	if ($submit)
	{
		
		if (!empty($file_title) && $download_categories->check_auth($file_cat_id) && !empty($file_url) && !empty($file_contents))
		{
			$visible = 1;
			
			$date_now = new Date(DATE_NOW);
			
			switch ($file_visibility)
			{
				case 2:
					if (($begining_date->get_timestamp() > $date_now->get_timestamp() || $end_date->get_timestamp() > $date_now->get_timestamp()) && $begining_date->get_timestamp() < $end_date->get_timestamp())
					{
						$start_timestamp = $begining_date->get_timestamp();
						$end_timestamp = $end_date->get_timestamp();
						
						if ($begining_date->get_timestamp() > $date_now->get_timestamp())
							$visible = 0;
					}
					else
						list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				case 1:
					list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				default:
					list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);
			}
			
			$file_properties = $Sql->query_array(PREFIX . "download", "visible", "approved", "WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);
			
			import('util/url');
			$file_relative_url = new Url($file_url);
			
			$Sql->query_inject("UPDATE " . PREFIX . "download SET title = '" . $file_title . "', idcat = '" . $file_cat_id . "', url = '" . $file_relative_url->relative() . "', " .
				"size = '" . $file_size . "', count = '" . $file_hits . "', force_download = '" . ($file_download_method == 'force_download' ? DOWNLOAD_FORCE_DL : DOWNLOAD_REDIRECT) . "', contents = '" . strparse($file_contents) . "', short_contents = '" . strparse($file_short_contents) . "', " .
				"image = '" . $file_image . "', timestamp = '" . $file_creation_date->get_timestamp() . "', release_timestamp = '" . ($ignore_release_date ? 0 : $file_release_date->get_timestamp()) . "', " .
				"start = '" . $start_timestamp . "', end = '" . $end_timestamp . "', visible = '" . $visible . "', approved = " . (int)$file_approved . " " .
				"WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);
			
			
			if ($file_cat_id != $file_infos['idcat'] || (int)$file_properties['visible'] != $visible || (int)$file_properties['approved'] != $file_approved)
			{
				$download_categories->Recount_sub_files();
			}

			
			if ($file_approved && !$file_properties['approved'])
			{
				import('events/contribution');
				import('events/contribution_service');
				
				$corresponding_contributions = ContributionService::find_by_criteria('download', $edit_file_id);
				if (count($corresponding_contributions) > 0)
				{
					$file_contribution = $corresponding_contributions[0];
					
					$file_contribution->set_status(EVENT_STATUS_PROCESSED);
					
					
					ContributionService::save_contribution($file_contribution);
				}
			}
            
            
            import('content/syndication/feed');
            Feed::clear_cache('download');
            
            
            if (!$visible || !$file_approved)
            {
            	if ($$file_cat_id > 0)
					redirect(HOST . DIR . '/download/' . url('download.php?cat=' . $file_cat_id, 'category-' . $file_cat_id . '+' . url_encode_rewrite($DOWNLOAD_CATS[$file_cat_id]['name']) . '.php'));
				else
					redirect(HOST . DIR . '/download/' . url('download.php'));
            }
			else
				redirect(HOST . DIR . '/download/' . url('download.php?id=' . $edit_file_id, 'download-' . $edit_file_id . '+' . url_encode_rewrite($file_title) . '.php'));
		}
		
		else
		{
			redirect(HOST . DIR . '/download/' . url('download.php'));
		}
	}
	
	elseif ($preview)
	{
		$begining_calendar = new MiniCalendar('begining_date');
		$begining_calendar->set_date($begining_date);
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_date($end_date);
		$end_calendar->set_style('margin-left:150px;');

		$Template->set_filenames(array('download' => 'download/download.tpl'));
		
		if ($file_size > 1)
			$size_tpl = $file_size . ' ' . $LANG['unit_megabytes'];
		elseif ($file_size > 0)
			$size_tpl = ($file_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $DOWNLOAD_LANG['unknown_size'];
		
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($file_creation_date);
		$release_calendar = new MiniCalendar('release_date');
		$release_calendar->set_date($file_release_date);
		
		if ($file_visibility < 0 || $file_visibility > 2)
			$file_visibility = 0;

		$Template->assign_vars(array(
			'C_DISPLAY_DOWNLOAD' => true,
			'C_IMG' => !empty($file_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->get_module_data_path('download'),
			'NAME' => stripslashes($file_title),
			'CONTENTS' => second_parse(stripslashes(strparse($file_contents))),
			'CREATION_DATE' => $file_creation_date->format(DATE_FORMAT_SHORT) ,
			'RELEASE_DATE' => $file_release_date->get_timestamp() > 0 ? $file_release_date->format(DATE_FORMAT_SHORT) : $DOWNLOAD_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $file_hits,
			'THEME' => get_utheme(),
			'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$file_hits),
			'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], 0),
			'U_IMG' => $file_image,
			'IMAGE_ALT' => str_replace('"', '\"', $file_title),
			'LANG' => get_ulang(),
		    'FORCE_DOWNLOAD_SELECTED' => $file_download_method == 'force_download' ? ' selected="selected"' : '',
			'REDIRECTION_SELECTED' => $file_download_method != 'force_download' ? ' selected="selected"' : '',
			
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
			'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
			'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
			'L_RELEASE_DATE' => $DOWNLOAD_LANG['release_date'],
			'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
			'L_NOTE' => $LANG['note'],
			'U_DOWNLOAD_FILE' => url('count.php?id=' . $edit_file_id, 'file-' . $edit_file_id . '+' . url_encode_rewrite($file_title) . '.php')
		));

		$Template->assign_vars(array(
			'C_CONTRIBUTION' => false,
			'TITLE' => stripslashes($file_title),
			'COUNT' => $file_hits,
			'DESCRIPTION' => htmlspecialchars(stripslashes($file_contents), ENT_COMPAT, 'ISO-8859-1'),
			'SHORT_DESCRIPTION' => htmlspecialchars(stripslashes($file_short_contents), ENT_COMPAT, 'ISO-8859-1'),
			'FILE_IMAGE' => $file_image,
			'URL' => $file_url,
			'SIZE_FORM' => $file_size,
			'DATE' => $file_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $download_categories->build_select_form($file_cat_id, 'idcat', 'idcat', 0, DOWNLOAD_WRITE_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($file_short_contents))),
			'VISIBLE_WAITING' => $file_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $file_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_HIDDEN' => $file_visibility == 0 ? ' checked="checked"' : '',
			'APPROVED' => $file_approved ? ' checked="checked"' : '',
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display()
		));
	}
	
	else
	{
		$file_creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['timestamp']);
		$file_release_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['release_timestamp']);
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($file_creation_date);
		
		$release_calendar = new MiniCalendar('release_date');
		$ignore_release_date = ($file_release_date->get_timestamp() == 0);
		if (!$ignore_release_date)
			$release_calendar->set_date($file_release_date);
		
		
		$begining_calendar = new MiniCalendar('begining_date');
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_style('margin-left:150px;');
		
		if (!empty($file_infos['start']) && !empty($file_infos['end']))
		{
			$file_visibility = 2;
			$begining_calendar->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['start']));
			$end_calendar->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['end']));
		}
		elseif (!empty($file_infos['visible']))
			$file_visibility = 1;
		else
			$file_visibility = 0;

		$Template->assign_vars(array(
			'C_CONTRIBUTION' => false,
			'TITLE' => $file_infos['title'],
			'COUNT' => !empty($file_infos['count']) ? $file_infos['count'] : 0,
			'DESCRIPTION' => unparse($file_infos['contents']),
			'SHORT_DESCRIPTION' => unparse($file_infos['short_contents']),
			'FILE_IMAGE' => $file_infos['image'],
			'URL' => $file_infos['url'],
			'SIZE_FORM' => $file_infos['size'],
			'DATE' => $file_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $download_categories->build_select_form($file_infos['idcat'], 'idcat', 'idcat', 0, DOWNLOAD_WRITE_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
			'VISIBLE_WAITING' => $file_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $file_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_HIDDEN' => $file_visibility == 0 ? ' checked="checked"' : '',
			'APPROVED' => $file_infos['approved'] ? ' checked="checked"' : '',
		    'FORCE_DOWNLOAD_SELECTED' => $file_infos['force_download'] == DOWNLOAD_FORCE_DL ? ' selected="selected"' : '',
			'REDIRECTION_SELECTED' => $file_infos['force_download'] == DOWNLOAD_REDIRECT ? ' selected="selected"' : '',
			'U_TARGET' => url('management.php?edit=' . $edit_file_id . '&amp;token=' . $Session->get_token())
		));
	}
}

else
{
	$contribution_counterpart = retrieve(POST, 'counterpart', '', TSTRING_PARSE);
	
	
	$file_approved = $auth_write;
	
	if ($submit)
	{
		
		if (!empty($file_title) && ($download_categories->check_auth($file_cat_id) || $download_categories->check_contribution_auth($file_cat_id)) && !empty($file_url) && !empty($file_contents))
		{
			$visible = 1;
			
			$date_now = new Date(DATE_NOW);
            
			switch ($file_visibility)
			{
				
				case 2:
					if ($begining_date->get_timestamp() < $date_now->get_timestamp() &&  $end_date->get_timestamp() > $date_now->get_timestamp())
					{
						$start_timestamp = $begining_date->get_timestamp();
						$end_timestamp = $end_date->get_timestamp();
					}
					else
						$visible = 0;
					break;
				
				case 1:
					list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				default:
					list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);
			}
			
            import('util/url');
            $file_relative_url = new Url($file_url);
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "download (title, idcat, url, size, count, force_download, contents, short_contents, image, timestamp, release_timestamp, start, end, visible, approved, users_note) " .
				"VALUES ('" . $file_title . "', '" . $file_cat_id . "', '" . $file_relative_url->relative() . "', '" . $file_size . "', '" . $file_hits . "', '" . ($file_download_method == 'force_download' ? DOWNLOAD_FORCE_DL : DOWNLOAD_REDIRECT) . "', '" . strparse($file_contents) . "', '" . strparse($file_short_contents) . "', '" . $file_image . "', '" . $file_creation_date->get_timestamp() . "', '" . ($ignore_release_date ? 0 : $file_release_date->get_timestamp()) . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $visible . "', '" . (int)$auth_write . "', '')", __LINE__, __FILE__);
			
			$new_id_file = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "download");
			
			
			if (!$auth_write)
			{
				
				import('events/contribution');
				import('events/contribution_service');
				
				$download_contribution = new Contribution();
				
				
				$download_contribution->set_id_in_module($new_id_file);
				
				$download_contribution->set_description(stripslashes($contribution_counterpart));
				
				$download_contribution->set_entitled(sprintf($DOWNLOAD_LANG['contribution_entitled'], $file_title));
				
				$download_contribution->set_fixing_url('/download/management.php?edit=' . $new_id_file);
				
				$download_contribution->set_poster_id($User->get_attribute('user_id'));
				
				$download_contribution->set_module('download');
				
				
				
				$download_contribution->set_auth(
					
					
					Authorizations::capture_and_shift_bit_auth(
						
						
						
						Authorizations::merge_auth(
							$CONFIG_DOWNLOAD['global_auth'],
							
							$download_categories->compute_heritated_auth($file_cat_id, DOWNLOAD_WRITE_CAT_AUTH_BIT, AUTH_CHILD_PRIORITY),
							DOWNLOAD_WRITE_CAT_AUTH_BIT, AUTH_CHILD_PRIORITY
						),
						DOWNLOAD_WRITE_CAT_AUTH_BIT, CONTRIBUTION_AUTH_BIT
					)
				);

				
				ContributionService::save_contribution($download_contribution);
				
				
				redirect(HOST . DIR . '/download/contribution.php');
			}
			
			
			$download_categories->Recount_sub_files();
            
            
            import('content/syndication/feed');
            Feed::clear_cache('download');
            
			redirect(HOST . DIR . '/download/' . url('download.php?id=' . $new_id_file, 'download-' . $new_id_file . '+' . url_encode_rewrite($file_title) . '.php'));
		}
		
		else
		{
			redirect(HOST . DIR . '/download/' . url('download.php'));
		}
	}
	
	elseif ($preview)
	{
		$contribution_counterpart_source = strprotect(retrieve(POST, 'counterpart', '', TSTRING_AS_RECEIVED), HTML_PROTECT, ADDSLASHES_NONE);
		
		$begining_calendar = new MiniCalendar('begining_date');
		$begining_calendar->set_date($begining_date);
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_date($end_date);
		$end_calendar->set_style('margin-left:150px;');
		
		$Template->set_filenames(array('download' => 'download/download.tpl'));
		
		if ($file_size > 1)
			$size_tpl = $file_size . ' ' . $LANG['unit_megabytes'];
		elseif ($file_size > 0)
			$size_tpl = ($file_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $DOWNLOAD_LANG['unknown_size'];
		
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($file_creation_date);
		$release_calendar = new MiniCalendar('release_date');
		$release_calendar->set_date($file_release_date);
		
		if ($file_visibility < 0 || $file_visibility > 2)
			$file_visibility = 0;

		$Template->assign_vars(array(
			'C_DISPLAY_DOWNLOAD' => true,
			'C_IMG' => !empty($file_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->get_module_data_path('download'),
			'NAME' => stripslashes($file_title),
			'CONTENTS' => second_parse(stripslashes(strparse($file_contents))),
			'CREATION_DATE' => $file_creation_date->format(DATE_FORMAT_SHORT) ,
			'RELEASE_DATE' => $file_release_date->get_timestamp() > 0 ? $file_release_date->format(DATE_FORMAT_SHORT) : $DOWNLOAD_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $file_hits,
			'THEME' => get_utheme(),
			'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$file_hits),
			'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], 0),
			'U_IMG' => $file_image,
			'IMAGE_ALT' => str_replace('"', '\"', $file_title),
			'LANG' => get_ulang(),
			'CONTRIBUTION_COUNTERPART' => $contribution_counterpart_source,
			'CONTRIBUTION_COUNTERPART_PREVIEW' => second_parse(stripslashes($contribution_counterpart)),
		    'FORCE_DOWNLOAD_SELECTED' => $file_download_method == 'force_download' ? ' selected="selected"' : '',
			'REDIRECTION_SELECTED' => $file_download_method != 'force_download' ? ' selected="selected"' : '',
			
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
			'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
			'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
			'L_RELEASE_DATE' => $DOWNLOAD_LANG['release_date'],
			'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
			'L_NOTE' => $LANG['note'],
		    'APPROVED' => ' checked="checked"',
			'U_DOWNLOAD_FILE' => url('count.php?id=' . $edit_file_id, 'file-' . $edit_file_id . '+' . url_encode_rewrite($file_title) . '.php')
		));

		$Template->assign_vars(array(
			'C_CONTRIBUTION' => !$auth_write,
			'TITLE' => stripslashes($file_title),
			'COUNT' => $file_hits,
			'DESCRIPTION' => htmlspecialchars(stripslashes($file_contents), ENT_COMPAT, 'ISO-8859-1'),
			'SHORT_DESCRIPTION' => htmlspecialchars(stripslashes($file_short_contents), ENT_COMPAT, 'ISO-8859-1'),
			'FILE_IMAGE' => $file_image,
			'URL' => $file_url,
			'SIZE_FORM' => $file_size,
			'DATE' => $file_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $auth_write ?
									$download_categories->build_select_form($file_cat_id, 'idcat', 'idcat', 0, DOWNLOAD_WRITE_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH) :
									$download_categories->build_select_form($file_cat_id, 'idcat', 'idcat', 0, DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($file_short_contents))),
			'VISIBLE_WAITING' => $file_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $file_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_HIDDEN' => $file_visibility == 0 ? ' checked="checked"' : '',
			'APPROVED' => $file_approved ? ' checked="checked"' : '',
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
		));
	}
	else
	{
		$file_creation_date = new Date(DATE_NOW, TIMEZONE_AUTO);
		$file_release_date = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($file_creation_date);
		
		$release_calendar = new MiniCalendar('release_date');
		$ignore_release_date = false;
		if (!$ignore_release_date)
			$release_calendar->set_date($file_release_date);
		
		
		$begining_calendar = new MiniCalendar('begining_date');
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_style('margin-left:150px;');
		
		$begining_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
		$end_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
		$file_visibility = 0;
		
		$Template->assign_vars(array(
			'C_CONTRIBUTION' => !$auth_write,
			'TITLE' => '',
			'COUNT' => 0,
			'DESCRIPTION' => '',
			'SHORT_DESCRIPTION' => '',
			'FILE_IMAGE' => '',
			'URL' => '',
			'SIZE_FORM' => '',
			'DATE' => $file_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $auth_write ?
									$download_categories->build_select_form($file_cat_id, 'idcat', 'idcat', 0, DOWNLOAD_WRITE_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH) :
									$download_categories->build_select_form($file_cat_id, 'idcat', 'idcat', 0, DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
			'VISIBLE_WAITING' => '',
			'VISIBLE_ENABLED' => ' checked="checked"',
			'VISIBLE_HIDDEN' => '',
			'APPROVED' => $file_approved ? ' checked="checked"' : '',
			'FORCE_DOWNLOAD_SELECTED' => ' selected="selected"',
			'REDIRECTION_SELECTED' => ' selected="selected"',
			'U_TARGET' => url('management.php?new=1&amp;token=' . $Session->get_token())
		));
	}
	$Template->assign_vars(array(
		'L_NOTICE_CONTRIBUTION' => $DOWNLOAD_LANG['notice_contribution'],
		'L_CONTRIBUTION_COUNTERPART' => $DOWNLOAD_LANG['contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $DOWNLOAD_LANG['contribution_counterpart_explain'],
		'CONTRIBUTION_COUNTERPART_EDITOR' => display_editor('counterpart')
	));
}

$Template->pparse('file_management');
require_once('../kernel/footer.php');

?>
