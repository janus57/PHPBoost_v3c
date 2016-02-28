<?php


























class Gallery
{	
	## Public Attribute ##
	var $error = ''; 
	
	
	## Public Methods ##
	
	function Gallery() 
	{
	}
	
	
	function Resize_pics($path, $width_max = 0, $height_max = 0)
	{
		global $LANG;
			
		if (file_exists($path))
		{	
			list($width_s, $height_s, $weight, $ext) = $this->Arg_pics($path);
			
			list($width, $height) = $this->get_resize_properties($width_s, $height_s, $width_max, $height_max);
			
			$source = false;
			switch ($ext) 
			{
				case 'jpg':
					$source = @imagecreatefromjpeg($path);
					break;
				case 'gif':
					$source = @imagecreatefromgif ($path);
					break;
				case 'png':
					$source = @imagecreatefrompng($path);
					break;
				default: 
					$this->error = 'e_unsupported_format';
					$source = false;
			}
			
			if (!$source)
			{
				$path_mini = str_replace('pics', 'pics/thumbnails', $path);
				$this->_create_pics_error($path_mini, $width, $height);	
				$this->error = 'e_unabled_create_pics';
			}
			else
			{
				
				if (!function_exists('imagecreatetruecolor'))
				{	
					$thumbnail = @imagecreate($width, $height);
					if ($thumbnail === false)				
						$this->error = 'e_unabled_create_pics';
				}
				else
				{	
					$thumbnail = @imagecreatetruecolor($width, $height);
					if ($thumbnail === false)				
						$this->error = 'e_unabled_create_pics';
				}
				
				
				imagecolortransparent($thumbnail, imagecolorallocate($thumbnail, 0, 0, 0));
				imagealphablending($thumbnail, false);
				
				
				if (!function_exists('imagecopyresampled'))
				{	
					if (@imagecopyresized($thumbnail, $source, 0, 0, 0, 0, $width, $height, $width_s, $height_s) === false)				
						$this->error = 'e_error_resize';
				}
				else
				{	
					if (@imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $width_s, $height_s) === false)				
						$this->error = 'e_error_resize';
				}
			}
			
			
			if (empty($this->error))
				$this->create_pics($thumbnail, $source, $path, $ext);
		}
		else
		{
			$path_mini = str_replace('pics', 'pics/thumbnails', $path);
			$this->_create_pics_error($path_mini, $width_max, $height_max);	
			$this->error = 'e_unabled_create_pics';
		}
	}
	
	
	function Create_pics($thumbnail, $source, $path, $ext)
	{
		global $CONFIG_GALLERY;
		
		
		imagecolortransparent($source, imagecolorallocate($source, 0, 0, 0));	
		imagealphablending($source, false); 
		
		$path_mini = str_replace('pics', 'pics/thumbnails', $path);
		if (function_exists('imagegif') && $ext === 'gif') 
			imagegif ($thumbnail, $path_mini);
		elseif (function_exists('imagejpeg') && $ext === 'jpg') 
			imagejpeg($thumbnail, $path_mini, $CONFIG_GALLERY['quality']);
		elseif (function_exists('imagepng')  && $ext === 'png') 
			imagepng($thumbnail, $path_mini);
		else 
			$this->error = 'e_no_graphic_support';

		switch ($ext) 
		{
			case 'jpg':
				@imagejpeg($source, $path);
				break;
			case 'gif':
				@imagegif ($source, $path);
				break;
			case 'png':
				@imagepng($source, $path);
				break;
			default: 
				$this->error = 'e_no_graphic_support';
		}
	}

	
	function Incrust_pics($path)
	{
		global $CONFIG_GALLERY, $LANG;
		
		if ($CONFIG_GALLERY['activ_logo'] == '1' && is_file($CONFIG_GALLERY['logo'])) 
		{
			list($width_s, $height_s, $weight_s, $ext_s) = $this->Arg_pics($CONFIG_GALLERY['logo']);
			list($width, $height, $weight, $ext) = $this->Arg_pics($path);
			
			if ($width_s <= $width && $height_s <= $height)
			{
				switch ($ext_s) 
				{
					case 'jpg':
						$source = @imagecreatefromjpeg($CONFIG_GALLERY['logo']);
						break;
					case 'gif':
						$source = @imagecreatefromgif ($CONFIG_GALLERY['logo']);
						break;
					case 'png':
						$source = @imagecreatefrompng($CONFIG_GALLERY['logo']);
						break;
					default: 
						$this->error = 'e_unsupported_format';
						$source = false;
				}
				
				if (!$source)
				{
					$path_mini = str_replace('pics', 'pics/thumbnails', $path);
					list($width_mini, $height_mini, $weight_mini, $ext_mini) = $this->Arg_pics($path_mini);
					$this->_create_pics_error($path_mini, $width_mini, $height_mini);	
					$this->error = 'e_unabled_create_pics';
				}
				else
				{
					switch ($ext) 
					{
						case 'jpg':
							$destination = @imagecreatefromjpeg($path);
							break;
						case 'gif':
							$destination = @imagecreatefromgif ($path);
							break;
						case 'png':
							$destination = @imagecreatefrompng($path);
							break;
						default: 
							$this->error = 'e_unsupported_format';
					}
					
					if (function_exists('imagecopymerge'))
					{
						
						$destination_x = $width - $width_s - $CONFIG_GALLERY['d_width'];
						$destination_y =  $height - $height_s - $CONFIG_GALLERY['d_height'];
						
						if (@imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $width_s, $height_s, (100 - $CONFIG_GALLERY['trans'])) === false)
							$this->error = 'e_unabled_incrust_logo';
							
						switch ($ext) 
						{
							case 'jpg':
								imagejpeg($destination);
								break;
							case 'gif':
								imagegif ($destination);
								break;
							case 'png':
								imagepng($destination);
								break;
							default: 
								$this->error = 'e_unabled_create_pics';
						}
					}
					else
						$this->error = 'e_unabled_incrust_logo';
				}
			}
			else
				readfile($path); 
		}
		else
			readfile($path); 
	}
	
	
	function Add_pics($idcat, $name, $path, $user_id)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
		
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $idcat . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);		
		
		list($width, $height, $weight, $ext) = $this->Arg_pics('pics/' . $path);	
		$Sql->query_inject("INSERT INTO " . PREFIX . "gallery (idcat, name, path, width, height, weight, user_id, aprob, views, timestamp, users_note, nbrnote, note, nbr_com) VALUES('" . $idcat . "', '" .strprotect($name,HTML_PROTECT,ADDSLASHES_FORCE). "', '" . $path . "', '" . $width . "', '" . $height . "', '" . $weight ."', '" . $user_id . "', 1, 0, '" . time() . "', '', 0, 0, 0)", __LINE__, __FILE__);
		
		return $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "gallery");
	}
	
	
	function Del_pics($id_pics)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$info_pics = $Sql->query_array(PREFIX . "gallery", "path", "idcat", "aprob", "WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		if (!empty($info_pics['path']))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);	
		
			
			$list_parent_cats_to = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left <= '" . $CAT_GALLERY[$info_pics['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$info_pics['idcat']]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats_to .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $info_pics['idcat'] . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
				
			if ($info_pics['aprob'])
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		
		delete_file('pics/' . $info_pics['path']);
		delete_file('pics/thumbnails/' . $info_pics['path']);
	}
	
	
	function Rename_pics($id_pics, $name, $previous_name)
	{
		global $Sql;
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET name = '" . strprotect($name,HTML_PROTECT,ADDSLASHES_FORCE). "' WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		return stripslashes((strlen(html_entity_decode($name, ENT_COMPAT, 'ISO-8859-1')) > 22) ? htmlentities(substr(html_entity_decode($name, ENT_COMPAT, 'ISO-8859-1'), 0, 22), ENT_COMPAT, 'ISO-8859-1') . PATH_TO_ROOT . '.' : $name);
	}
	
	
	function Aprob_pics($id_pics)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$idcat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
		
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $idcat . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
		$aprob = $Sql->query("SELECT aprob FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		if ($aprob)
		{	
			$Sql->query_inject("UPDATE " . PREFIX . "gallery SET aprob = 0 WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob + 1, nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("UPDATE " . PREFIX . "gallery SET aprob = 1 WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1, nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		return $aprob;
	}
	
	
	function Move_pics($id_pics, $id_move)
	{
		global $CAT_GALLERY, $Sql;
		
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$idcat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');
		
		if (empty($list_parent_cats))
			$clause_parent_cats = " id = '" . $idcat . "'";
		else
			$clause_parent_cats = " id IN (" . $list_parent_cats . ")";
		
		
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$id_move]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$id_move]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
	
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $id_move . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
		$aprob = $Sql->query("SELECT aprob FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		
		if ($aprob)
		{	
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1 WHERE " . $clause_parent_cats, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET idcat = '" . $id_move . "' WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
	}
	
	
	function Auth_upload_pics($user_id, $level)
	{
		global $CONFIG_GALLERY;
		
		switch ($level)
		{
			case 2:
			$pics_quota = 10000;
			break;
			case 1:
			$pics_quota = $CONFIG_GALLERY['limit_modo'];
			break;
			default:
			$pics_quota = $CONFIG_GALLERY['limit_member'];
		}

		if ($this->get_nbr_upload_pics($user_id) >= $pics_quota)
			return false;
			
		return true;
	}
	
	
	function Arg_pics($path)
	{
		global $Errorh, $LANG;
		
		
		if (!@extension_loaded('gd')) 
			$Errorh->handler($LANG['e_no_gd'], E_USER_ERROR, __LINE__, __FILE__);
		
		if (function_exists('getimagesize')) 
		{
			list($width, $height, $type) = @getimagesize($path);
			$weight = @filesize($path);
			$weight = !empty($weight) ? $weight : 0;			
			
			
			$array_type = array( 1 => 'gif', 2 => 'jpg', 3 => 'png');
			if (isset($array_type[$type]))
				return array($width, $height, $weight, $array_type[$type]);
			else
				$this->error = 'e_unsupported_format';
		}
		else
			$Errorh->handler($LANG['e_no_getimagesize'], E_USER_ERROR, __LINE__, __FILE__);
	}
		
	
	function get_nbr_upload_pics($user_id)
	{
		global $Sql;
		
		return $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "gallery WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	}
	
	
	function get_resize_properties($width_s, $height_s, $width_max = 0, $height_max = 0)
	{
		global $CONFIG_GALLERY;
		
		$width_max = ($width_max == 0) ? $CONFIG_GALLERY['width'] : $width_max;
		$height_max = ($height_max == 0) ? $CONFIG_GALLERY['height'] : $height_max;
		if ($width_s > $width_max || $height_s > $height_max) 
		{
			if ($width_s > $height_s)
			{
				$ratio = $width_s / $height_s;
				$width = $width_max;
				$height = ceil($width / $ratio);
			}
			else
			{
				$ratio = $height_s / $width_s;
				$height = $height_max;
				$width = ceil($height / $ratio);
			}
		}
		else
		{
			$width = $width_s;
			$height = $height_s;
		}
		
		return array($width, $height);
	}
	
	
	function Send_header($ext)
	{
		global $LANG;
		
		switch ($ext)
		{
			case 'png':
				$header = header('Content-type: image/png');
				break;
			case 'gif':
				$header = header('Content-type: image/gif');
				break;
			case 'jpg':
				$header = header('Content-type: image/jpeg');
				break;
			default:
				$header = '';
				$this->error = $LANG['e_unable_display_pics'];
		}
		return $header;
	}
	
	
	function Count_cat_pics()
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$info_cat = array();
		$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_pics_aprob 
		FROM " . PREFIX . "gallery 
		WHERE aprob = 1 AND idcat > 0
		GROUP BY idcat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$info_cat[$row['idcat']]['aprob'] = $row['nbr_pics_aprob'];
		$Sql->query_close($result);
		
		$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_pics_unaprob 
		FROM " . PREFIX . "gallery 
		WHERE aprob = 0 AND idcat > 0
		GROUP BY idcat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$info_cat[$row['idcat']]['unaprob'] = $row['nbr_pics_unaprob'];
		$Sql->query_close($result);
		
		$result = $Sql->query_while("SELECT id, id_left, id_right
		FROM " . PREFIX . "gallery_cats", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{			
			$nbr_pics_aprob = 0;
			$nbr_pics_unaprob = 0;
			foreach ($info_cat as $key => $value)
			{			
				if ($CAT_GALLERY[$key]['id_left'] >= $row['id_left'] && $CAT_GALLERY[$key]['id_right'] <= $row['id_right'])
				{	
					$nbr_pics_aprob += isset($info_cat[$key]['aprob']) ? $info_cat[$key]['aprob'] : 0;
					$nbr_pics_unaprob += isset($info_cat[$key]['unaprob']) ? $info_cat[$key]['unaprob'] : 0; 
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = '" . $nbr_pics_aprob . "', nbr_pics_unaprob = '" . $nbr_pics_unaprob . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);	
		}
		$Sql->query_close($result);
	}
	
	
	function Clear_cache()
	{
		
		import('io/filesystem/folder');
		$thumb_folder_path = new Folder('./pics/thumbnails/');
		foreach ($thumb_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $thumbs)
			$this->delete_file('./pics/thumbnails/' . $thumbs->get_name());
	}
	
	## Private Methods ##
	
	function _create_pics_error($path, $width, $height)
	{
		global $CONFIG_GALLERY, $LANG; 
		
		$width = ($width == 0) ? $CONFIG_GALLERY['width'] : $width;
		$height = ($height == 0) ? $CONFIG_GALLERY['height'] : $height;
			
		$font = PATH_TO_ROOT . '/kernel/data/fonts/impact.ttf';		
		$font_size = 12;

		$thumbnail = @imagecreate($width, $height);
		if ($thumbnail === false)				
			$this->error = 'e_unabled_create_pics';
		$background = @imagecolorallocate($thumbnail, 255, 255, 255);
		$text_color = @imagecolorallocate($thumbnail, 0, 0, 0);

		
		$array_size_ttf = imagettfbbox($font_size, 0, $font, $LANG['e_error_img']);
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($width/2) - ($text_width/2);
		$text_y = ($height/2) + ($text_height/2);

		
		imagettftext($thumbnail, $font_size, 0, $text_x, $text_y, $text_color, $font, $LANG['e_error_img']);
		@imagejpeg($thumbnail, $path, 75);
	}
	
	
	function delete_file($path)
	{
		if (function_exists('unlink'))
			return @unlink($path); 
		else 
		{	
			$this->error = 'e_delete_thumbnails';
			return false;
		}		
	}
}
?>
