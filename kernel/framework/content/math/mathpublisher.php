<?php












define('DIR_IMG', PATH_TO_ROOT . '/images/maths'); 
define('DIR_FONT', PATH_TO_ROOT . '/kernel/data/fonts'); 

function detectimg($n)
{
	
	$ret = 0;
	$handle = opendir(DIR_IMG);
	while (!is_bool($fi = readdir($handle)))
	{
		if (strpos($fi, $n) !== false) 
		{
			$v = explode('_', $fi);
			$ret = $v[1];
			break;
		}
	}
	closedir($handle);
	
	return $ret;
}

function mathimage($text, $size)
{
	
	$nameimg = md5(trim($text) . $size) . '.png';
	$v = detectimg($nameimg);
	if ($v == 0)
	{
		
		global $symboles, $fontesmath;
		import('content/math/mathpublisher');
		
		$formula = new expression_math(tableau_expression(trim($text)));
		$formula->dessine($size);
		$v = 1000 - imagesy($formula->image) + $formula->base_verticale + 3; 
		imagepng($formula->image, DIR_IMG . '/math_' . $v . '_' . $nameimg);
	}
	$valign = $v - 1000;
	
	
	$text = htmlentities(strip_tags($text), ENT_COMPAT, 'ISO-8859-1');
	
	return '<img src="/images/maths/math_' . $v . '_' . $nameimg . '" style="vertical-align:' . $valign . 'px;display:inline-block;background-color:#FFFFFF;" alt="' . $text . '" title="' . $text . '"/>';
}

function mathfilter($text, $size) 
{
	$text = stripslashes($text);
	$size = max($size, 10);
	$size = min($size, 24);
	
	return mathimage(trim($text), $size);	
}

?>
