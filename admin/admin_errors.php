<?php


























require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$all = !empty($_GET['all']) ? true : false;

$Template->set_filenames(array(
	'admin_errors_management'=> 'admin/admin_errors_management.tpl'
));

$file_path = '../cache/error.log';

if (!empty($_POST['erase']))
	delete_file($file_path); 

$Template->assign_vars(array(
	'L_ERRORS_MANAGEMENT' => $LANG['error_management'],
	'L_ERRORS' => $LANG['errors'],
	'L_ALL_ERRORS' => $LANG['all_errors'],
	'L_DESC' => $LANG['description'],
	'L_DATE' => $LANG['date'],
	'L_ERASE_RAPPORT' => $LANG['erase_rapport'],
	'L_ERASE_RAPPORT_EXPLAIN' => $LANG['final'],
	'L_ERASE' => $LANG['erase']
));

if (is_file($file_path) && is_readable($file_path)) 
{
	$handle = @fopen($file_path, 'r');
	if ($handle) 
	{
		$array_errinfo = array();
		$i = 1;
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);
			switch ($i)
			{
				case 1:
				$errinfo['errdate'] = $buffer;
				break;
				case 2:
				$errinfo['errno'] = $buffer;
				break;
				case 3:
				$errinfo['errstr'] = $buffer;
				break;
				case 4:
				$errinfo['errfile'] = $buffer;
				break;
				case 5:
				$errinfo['errline'] = $buffer;	
				$i = 0;	
				$errinfo['errclass'] = $Errorh->get_errno_class($errinfo['errno']);
				$array_errinfo[] = array(
				'errclass' => $errinfo['errclass'], 
				'errstr' => $errinfo['errstr'], 
				'errline'=> $errinfo['errline'], 
				'errfile' => $errinfo['errfile'], 
				'errdate' => $errinfo['errdate']
				);
				break;	
			}
			$i++;						
		}
		@fclose($handle);
		
		$images = array(
			'error_unknow' => 'question',
			'error_notice' => 'notice',
			'error_warning' => 'important',
			'error_fatal' => 'stop'
		);
		
		
		krsort($array_errinfo);
		$i = 0;
		foreach ($array_errinfo as $key => $errinfo)
		{
			$str_error = sprintf($LANG[$errinfo['errclass']], str_replace('&lt;br /&gt;', '<br />', htmlentities($errinfo['errstr'], ENT_COMPAT, 'ISO-8859-1')), $errinfo['errline'], basename($errinfo['errfile']));
			
			$Template->assign_block_vars('errors', array(
				'IMG' => $images[$errinfo['errclass']],
				'CLASS' => $errinfo['errclass'],
				'DATE' => $errinfo['errdate'],
				'L_ERROR_DESC' => wordwrap(str_replace(',', ', ', $str_error), 80, "\n", true)
			));
			$i++;
			
			if ($i > 15 && !$all)
				break;
		}
	}
	else
	{
		$Template->assign_block_vars('errors', array(
			'TYPE' => '&nbsp;',
			'DESC' => '',
			'FILE' => '',
			'LINE' => '',
			'DATE' => ''
		));
	}
}

$Template->pparse('admin_errors_management');

require_once('../admin/admin_footer.php');

?>
