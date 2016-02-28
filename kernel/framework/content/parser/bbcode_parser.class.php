<?php


























import('content/parser/content_parser');









class BBCodeParser extends ContentParser
{
    


    function BBCodeParser()
    {
        parent::ContentParser();
    }

    



    function parse()
    {
        global $User;

        
        if (!in_array('code', $this->forbidden_tags))
        {
            $this->_pick_up_tag('code', '=[A-Za-z0-9#+-]+(?:,[01]){0,2}');
        }

        
        if (!in_array('html', $this->forbidden_tags) && $User->check_auth($this->html_auth, 1))
        {
            $this->_pick_up_tag('html');
        }

        
        $this->content = ' ' . $this->content . ' ';

        
        $this->_protect_content();

        
        $this->_parse_smilies();

        
        $this->content = nl2br($this->content);

        
        $this->_parse_simple_tags();

        
        if (!in_array('table', $this->forbidden_tags) && strpos($this->content, '[table') !== false)
        {
            $this->_parse_table();
        }

        
        if (!in_array('list', $this->forbidden_tags)&& strpos($this->content, '[list') !== false)
        {
            $this->_parse_list();
        }


        
        if (!empty($this->array_tags['html']))
        {
            $this->array_tags['html'] = array_map(create_function('$string', 'return str_replace("[html]", "<!-- START HTML -->\n", str_replace("[/html]", "\n<!-- END HTML -->", $string));'), $this->array_tags['html']);
            $this->_reimplant_tag('html');
        }

        parent::parse();

        
        if (!empty($this->array_tags['code']))
        {
            $this->array_tags['code'] = array_map(create_function('$string', 'return preg_replace(\'`^\[code(=.+)?\](.+)\[/code\]$`isU\', \'[[CODE$1]]$2[[/CODE]]\', htmlspecialchars($string, ENT_NOQUOTES, \'ISO-8859-1\'));'), $this->array_tags['code']);
            $this->_reimplant_tag('code');
        }
    }

    ## Private ##
    







    function _protect_content()
    {
        
        $this->content = htmlspecialchars($this->content, ENT_NOQUOTES, 'ISO-8859-1');
        $this->content = strip_tags($this->content);

        
        $this->content = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $this->content);

        
        $array_str = array(
			'€', '‚', 'ƒ', '„', '…', '†', '‡', 'ˆ', '‰',
			'Š', '‹', 'Œ', 'Ž', '‘', '’', '“', '”', '•',
			'–', '—',  '˜', '™', 'š', '›', 'œ', 'ž', 'Ÿ'
			);

			$array_str_replace = array(
			'&#8364;', '&#8218;', '&#402;', '&#8222;', '&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;',
			'&#352;', '&#8249;', '&#338;', '&#381;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;',
			'&#8211;', '&#8212;', '&#732;', '&#8482;', '&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
			);
			$this->content = str_replace($array_str, $array_str_replace, $this->content);
    }

    


    function _parse_smilies()
    {
        @include(PATH_TO_ROOT . '/cache/smileys.php');
        if (!empty($_array_smiley_code))
        {
            
            foreach ($_array_smiley_code as $code => $img)
            {
                $smiley_code[] = '`(?:(?![a-z0-9]))(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . preg_quote($code) . ')(?:(?![a-z0-9]))`';
                $smiley_img_url[] = '<img src="/images/smileys/' . $img . '" alt="' . addslashes($code) . '" class="smiley" />';
            }
            $this->content = preg_replace($smiley_code, $smiley_img_url, $this->content);
        }
    }

    




    function _parse_simple_tags()
    {
        global $LANG;
        import('util/url');
        $array_preg = array(
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'sup' => '`\[sup\](.+)\[/sup\]`isU',
			'sub' => '`\[sub\](.+)\[/sub\]`isU',
			'img' => '`\[img(?:=(top|middle|bottom))?\]((?:[./]+|(?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
			'color' => '`\[color=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/color\]`isU',
			'bgcolor' => '`\[bgcolor=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/bgcolor\]`isU',
			'size' => '`\[size=([1-9]|(?:[1-4][0-9]))\](.+)\[/size\]`isU',
			'font' => '`\[font=(arial|times|courier(?: new)?|impact|geneva|optima)\](.+)\[/font\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'align' => '`\[align=(left|center|right|justify)\](.+)\[/align\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'anchor' => '`\[anchor=([a-z_][a-z0-9_-]*)\](.*)\[/anchor\]`isU',
			'acronym' => '`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-9]{1,3}),([0-9]{1,3})\](((?:[./]+|(?:https?|ftps?)://([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4})+(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*))\[/swf\]`iU',
			'movie' => '`\[movie=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/movie\]`iU',
            'sound' => '`\[sound\]([a-z0-9_+.:?/=#%@&;,-]*)\[/sound\]`iU',
			'math' => '`\[math\](.+)\[/math\]`iU',
            'mail' => '`(?<=\s|^)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(?=\s|\n|\r|<|$)`iU',
            'mail2' => '`\[mail=([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})\]([^\n\r\t\f]+)\[/mail\]`iU',
			'url1' => '`\[url\]((?!javascript:)' . Url::get_wellformness_regex() . ')\[/url\]`isU',
			'url2' => '`\[url=((?!javascript:)' . Url::get_wellformness_regex() . ')\]([^\n\r\t\f]+)\[/url\]`isU',
			'url3' => '`(\s+)(' . Url::get_wellformness_regex(REGEX_MULTIPLICITY_REQUIRED) . ')(\s|<+)`isU',
			'url4' => '`(\s+)(www\.' . Url::get_wellformness_regex(REGEX_MULTIPLICITY_NOT_USED) . ')(\s|<+)`isU'
			);
			
			$array_preg_replace = array(
			'b' => "<strong>$1</strong>",
			'i' => "<em>$1</em>",
			'u' => "<span style=\"text-decoration: underline;\">$1</span>",
			's' => "<strike>$1</strike>",
			'sup' => '<sup>$1</sup>',
			'sub' => '<sub>$1</sub>',
			'img' => "<img src=\"$2\" alt=\"\" class=\"valign_$1\" />",
			'color' => "<span style=\"color:$1;\">$2</span>",
			'bgcolor' => "<span style=\"background-color:$1;\">$2</span>",
			'size' => "<span style=\"font-size: $1px;\">$2</span>",
			'font' => "<span style=\"font-family: $1;\">$2</span>",
			'pre' => "<pre>$1</pre>",
			'align' => "<p style=\"text-align:$1\">$2</p>",
			'float' => "<p class=\"float_$1\">$2</p>",
			'anchor' => "<span id=\"$1\">$2</span>",
			'acronym' => "<acronym title=\"$1\" class=\"bb_acronym\">$2</acronym>",
			'style' => "<span class=\"$1\">$2</span>",
			'swf' => '[[MEDIA]]insertSwfPlayer(\'$3\', $1, $2);[[/MEDIA]]',
			'movie' => '[[MEDIA]]insertMoviePlayer(\'$3\', $1, $2);[[/MEDIA]]',
			'sound' => '[[MEDIA]]insertSoundPlayer(\'$1\');[[/MEDIA]]',
			'math' => '[[MATH]]$1[[/MATH]]',
            'mail' => "<a href=\"mailto:$1\">$1</a>",
            'mail2' => "<a href=\"mailto:$1\">$2</a>",
			'url1' => '<a href="$1">$1</a>',
			'url2' => '<a href="$1">$2</a>',
            'url3' => '$1<a href="$2">$2</a>$3',
            'url4' => '$1<a href="$2">$2</a>$3'
			);

			$parse_line = true;

			
			if (!empty($this->forbidden_tags))
			{
			    
			    if (in_array('url', $this->forbidden_tags))
			    {
			        $this->forbidden_tags[] = 'url1';
			        $this->forbidden_tags[] = 'url2';
			        $this->forbidden_tags[] = 'url3';
			        $this->forbidden_tags[] = 'url4';
			    }
			    if (in_array('mail', $this->forbidden_tags))
			    {
                    $this->forbidden_tags[] = 'mail';
                    $this->forbidden_tags[] = 'mail2';
			    }
			    	
			    foreach ($this->forbidden_tags as $key => $tag)
			    {
			        if ($tag == 'line')
			        {
			            $parse_line = false;
			        }
			        else
			        {
			            unset($array_preg[$tag]);
			            unset($array_preg_replace[$tag]);
			        }
			    }
			}

			
			$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

			
			if ($parse_line)
			$this->content = str_replace('[line]', '<hr class="bb_hr" />', $this->content);
				
			
			if (!in_array('title', $this->forbidden_tags))
			{
			    $this->content = preg_replace_callback('`\[title=([1-4])\](.+)\[/title\]`iU', array(&$this, '_parse_title'), $this->content);
			}

			
			if (!in_array('wikipedia', $this->forbidden_tags))
			{
			    $this->content = preg_replace_callback('`\[wikipedia(?: page="([^"]+)")?(?: lang="([a-z]+)")?\](.+)\[/wikipedia\]`isU', array(&$this, '_parse_wikipedia_links'), $this->content);
			}

			##Parsage des balises imbriquées.
			
			if (!in_array('quote', $this->forbidden_tags))
			{
			    $this->_parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $this->content);
			    $this->_parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $this->content);
			}

			
			if (!in_array('hide', $this->forbidden_tags))
			{
			    $this->_parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $this->content);
			}

			
			if (!in_array('indent', $this->forbidden_tags))
			{
			    $this->_parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $this->content);
			}

			
			if (!in_array('block', $this->forbidden_tags))
			{
			    $this->_parse_imbricated('[block]', '`\[block\](.+)\[/block\]`sU', '<div class="bb_block">$1</div>', $this->content);
			    $this->_parse_imbricated('[block style=', '`\[block style="([^"]+)"\](.+)\[/block\]`sU', '<div class="bb_block" style="$1">$2</div>', $this->content);
			}

			
			if (!in_array('fieldset', $this->forbidden_tags))
			{
			    $this->_parse_imbricated('[fieldset', '`\[fieldset(?: legend="(.*)")?(?: style="([^"]*)")?\](.+)\[/fieldset\]`sU', '<fieldset class="bb_fieldset" style="$2"><legend>$1</legend>$3</fieldset>', $this->content);
			}
    }

    



    function _parse_imbricated_table(&$content)
    {
        if (is_array($content))
        {
            $string_content = '';
            $nbr_occur = count($content);
            for ($i = 0; $i < $nbr_occur; $i++)
            {
                
                if ($i % 3 === 2)
                {
                    
                    $this->_parse_imbricated_table($content[$i]);
                    
                    if (preg_match('`^(?:\s|<br />)*\[row(?: style="[^"]+")?\](?:\s|<br />)*\[(?:col|head)(?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?\].*\[/(?:col|head)\](?:\s|<br />)*\[/row\](?:\s|<br />)*$`sU', $content[$i]))
                    {
                        
                        $content[$i] = preg_replace_callback('`^(\s|<br />)+\[row.*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+$`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+\[row.*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[/row\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[/row\]`U', array(&$this, 'clear_html_br'), $content[$i]);
                        
                        $content[$i] = preg_replace('`\[row( style="[^"]+")?\](.*)\[/row\]`sU', '<tr class="bb_table_row"$1>$2</tr>', $content[$i]);
                        $content[$i] = preg_replace('`\[col((?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/col\]`sU', '<td class="bb_table_col"$1>$2</td>', $content[$i]);
                        $content[$i] = preg_replace('`\[head((?: colspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/head\]`sU', '<th class="bb_table_head"$1>$2</th>', $content[$i]);
                        
                        $content[$i] = '<table class="bb_table"' . $content[$i - 1] . '>' . $content[$i] . '</table>';

                    }
                    else
                    {
                        
                        $content[$i] = str_replace(array('[col', '[row', '[/col', '[/row', '[head', '[/head'), array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), $content[$i]);
                        $content[$i] = '[table' . $content[$i - 1] . ']' . $content[$i] . '[/table]';
                    }
                }
                
                if ($i % 3 !== 1)
                $string_content .= $content[$i];
            }
            $content = $string_content;
        }
    }

    


    function _parse_table()
    {
        
        $this->_split_imbricated_tag($this->content, 'table', ' style="[^"]+"');
        $this->_parse_imbricated_table($this->content);
        
        $this->content = str_replace(array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), array('[col', '[row', '[/col', '[/row', '[head', '[/head'), $this->content);
    }

    




    function _parse_imbricated_list(&$content)
    {
        if (is_array($content))
        {
            $string_content = '';
            $nbr_occur = count($content);
            for ($i = 0; $i < $nbr_occur; $i++)
            {
                
                if ($i % 3 === 2)
                {
                    
                    if (is_array($content[$i]))
                    $this->_parse_imbricated_list($content[$i]);
                    	
                    if (strpos($content[$i], '[*]') !== false) 
                    {
                        
                        $content[$i] = preg_replace_callback('`\[\*\]((?:\s|<br />)+)`', array(&$this, 'clear_html_br'), $content[$i]);
                        $content[$i] = preg_replace_callback('`((?:\s|<br />)+)\[\*\]`', array(&$this, 'clear_html_br'), $content[$i]);
                        if (substr($content[$i - 1], 0, 8) == '=ordered')
                        {
                            $list_tag = 'ol';
                            $content[$i - 1] = substr($content[$i - 1], 8);
                        }
                        else
                        {
                            $list_tag = 'ul';
                        }
                        $content[$i] = preg_replace_callback('`^((?:\s|<br />)*)\[\*\]`U', create_function('$var', 'return str_replace("<br />", "", str_replace("[*]", "<li class=\"bb_li\">", $var[0]));'), $content[$i]);
                        $content[$i] = '<' . $list_tag . $content[$i - 1] . ' class="bb_' . $list_tag . '">' . str_replace('[*]', '</li><li class="bb_li">', $content[$i]) . '</li></' . $list_tag . '>';
                    }
                }
                
                if ($i % 3 !== 1)
                $string_content .= $content[$i];
            }
            $content = $string_content;
        }
    }

    


    function _parse_list()
    {
        
        
        if (preg_match('`\[list(=(?:un)?ordered)?( style="[^"]+")?\](\s|<br />)*\[\*\].*\[/list\]`s', $this->content))
        {
            $this->_split_imbricated_tag($this->content, 'list', '(?:=ordered)?(?: style="[^"]+")?');
            $this->_parse_imbricated_list($this->content);
        }
    }

    




    function _parse_title($matches)
    {
        $level = (int)$matches[1];
        if ($level <= 2)
        return '<h3 class="title' . $level . '">' . $matches[2] . '</h3>';
        else
        return '<br /><h4 class="stitle' . ($level - 2) . '">' . $matches[2] . '</h4><br />';
    }

    




    function _parse_wikipedia_links($matches)
    {
        global $LANG;

        
        $lang = $LANG['wikipedia_subdomain'];
        if (!empty($matches[2]))
        $lang = $matches[2];

        $page_url = !empty($matches[1]) ? $matches[1] : $matches[3];

        return '<a href="http://' . $lang . '.wikipedia.org/wiki/' . $page_url . '" class="wikipedia_link">' . $matches[3] . '</a>';
    }

    




    function clear_html_br($matches)
    {
        return str_replace("<br />", "", $matches[0]);
    }
}

?>
