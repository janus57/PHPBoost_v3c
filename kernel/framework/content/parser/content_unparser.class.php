<?php


























import('content/parser/parser');







class ContentUnparser extends Parser
{
	######## Public #######
	


	function ContentUnparser()
	{
		parent::Parser();
	}
	
	## Private ##
	



	function _unparse_html($action)
	{
		
		if ($action == PICK_UP)
		{
			$mask = '`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`sU';
			$content_split = preg_split($mask, $this->content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if ($content_length > 1)
			{
				$this->content = '';
				for ($i = 0; $i < $content_length; $i++)
				{
					
					if ($i % 2 == 0)
					{
						$this->content .= $content_split[$i];
						
						if ($i < $content_length - 1)
							$this->content .= '[HTML_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					else
					{
						$this->array_tags['html_unparse'][] = $content_split[$i];
					}
				}
				
				
				$this->array_tags['html_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES, \'ISO-8859-1\');'), $this->array_tags['html_unparse']);
			}
			return true;
		}
		
		else
		{
			if (!array_key_exists('html_unparse', $this->array_tags))
				return false;
				
			$content_length = count($this->array_tags['html_unparse']);

			if ($content_length > 0)
			{
				for ($i = 0; $i < $content_length; $i++)
					$this->content = str_replace('[HTML_UNPARSE_TAG_' . $i . ']', '[html]' . $this->array_tags['html_unparse'][$i] . '[/html]', $this->content);
				$this->array_tags['html_unparse'] = array();
			}
			return true;
		}
	}
	
	



	function _unparse_code($action)
	{
		
		if ($action == PICK_UP)
		{
			$mask = '`\[\[CODE(=[A-Za-z0-9#+-]+(?:,(?:0|1)(?:,1)?)?)?\]\]' . '(.+)' . '\[\[/CODE\]\]`sU';
			$content_split = preg_split($mask, $this->content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if ($content_length > 1)
			{
				$this->content = '';
				for ($i = 0; $i < $content_length; $i++)
				{
					
					if ($i % 3 == 0)
					{
						$this->content .= $content_split[$i];
						
						if ($i < $content_length - 1)
							$this->content .= '[CODE_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					elseif ($i % 3 == 2)
					{
						$this->array_tags['code_unparse'][] = '[code' . $content_split[$i - 1] . ']' . $content_split[$i] . '[/code]';
					}
				}
			}
			return true;
		}
		
		else
		{
			if (!array_key_exists('code_unparse', $this->array_tags))
				return false;
				
			$content_length = count($this->array_tags['code_unparse']);

			if ($content_length > 0)
			{
				for ($i = 0; $i < $content_length; $i++)
					$this->content = str_replace('[CODE_UNPARSE_TAG_' . $i . ']', $this->array_tags['code_unparse'][$i], $this->content);
				$this->array_tags['code_unparse'] = array();
			}
			return true;
		}
	}
}
?>
