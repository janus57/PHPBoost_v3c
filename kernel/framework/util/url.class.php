<?php


























define('URL__CLASS','url');
define('SERVER_URL', $_SERVER['PHP_SELF']);














class Url
{
	







	function Url($url = '.', $path_to_root = null, $server_url = null)
	{
		if (!empty($url))
		{
			if ($path_to_root !== null)
			{
				$this->path_to_root = $path_to_root;
			}
			else
			{
				$this->path_to_root = Url::path_to_root();
			}

			if ($server_url !== null)
			{
				$this->server_url = $server_url;
			}
			else
			{
				$this->server_url = Url::server_url();
			}

			$anchor = '';
			if (($pos = strpos($url, '#')) !== false)
			{
				
				if ($pos == 0)
				{
					
					$this->url = $url;
					$this->is_relative = false; 
					return;
				}
				else
				{
					$anchor = substr($url, $pos);
					$url = substr($url, 0, $pos);
				}
			}

			if (preg_match('`^[a-z0-9]+\:(?!//).+`iU', $url) > 0)
			{	
				$this->url = $url;
				return;
			}
			else if (strpos($url, 'www.') === 0)
			{   
				$url = 'http://' . $url;
			}

			$url = str_replace(Url::get_absolute_root() . '/', '/', Url::compress($url));
			if (!strpos($url, '://'))
			{
				$this->is_relative = true;
				if (substr($url, 0, 1) == '/')
				{   
					$this->url = $url;
				}
				else
				{   
					$this->url = $this->root_to_local() . $url;
				}
			}
			else
			{
				$this->is_relative = false;
				$this->url = $url;
			}
			$this->url = Url::compress($this->url) . $anchor;
		}
	}

	


	function is_relative()
	{
		return $this->is_relative;
	}

	



	function relative()
	{
		if ($this->is_relative())
		{
			return $this->url;
		}
		else
		{
			return $this->absolute();
		}
	}

	



	function absolute()
	{
		if ($this->is_relative())
		{
			return Url::compress($this->get_absolute_root() . $this->url);
		}
		else
		{
			return $this->url;
		}
	}


	





	static function compress($url)
	{
		$args = '';
		if (($pos = strpos($url, '?')) !== false)
		{
			
			$args = substr($url, $pos);
			$url = substr($url, 0, $pos);
		}
		$url = preg_replace(array('`([^:]|^)/+`', '`(?<!\.)\./`'), array('$1/', ''), $url);

		do
		{
			$url = preg_replace('`/?[^/]+/\.\.`', '', $url);

		}
		while (preg_match('`/?[^/]+/\.\.`', $url) > 0);
		return preg_replace('`^//`', '/', $url) . $args;
	}

	



	function root_to_local()
	{
		global $CONFIG;

		$local_path = $this->server_url;
		$local_path = substr(trim($local_path, '/'), strlen(trim($CONFIG['server_path'], '/')));
		$file_begun = strrpos($local_path, '/');
		if ($file_begun >= 0)
		{
			$local_path = substr($local_path, 0, $file_begun) . '/';
		}

		return '/' . ltrim($local_path, '/');
	}

	




	static function get_absolute_root()
	{
		global $CONFIG;
		return trim(trim($CONFIG['server_name'], '/') . '/' . trim($CONFIG['server_path'], '/'), '/');
	}

	








	static function html_convert_root_relative2absolute($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = Url::path_to_root();
		$server_url_bak = Url::server_url();

		Url::path_to_root($path_to_root);
		Url::server_url($server_url);

		$result = preg_replace_callback(Url::_build_html_match_regex(true),
		array('Url', '_convert_url_to_absolute'), $html_text);

		Url::path_to_root($path_to_root_bak);
		Url::server_url($server_url_bak);

		return $result;
	}

	







	static function html_convert_absolute2root_relative($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = Url::path_to_root();
		$server_url_bak = Url::server_url();

		Url::path_to_root($path_to_root);
		Url::server_url($server_url);

		$result = preg_replace_callback(Url::_build_html_match_regex(),
		  array('Url', '_convert_url_to_root_relative'), $html_text);

		Url::path_to_root($path_to_root_bak);
		Url::server_url($server_url_bak);

		return $result;
	}

	







	static function html_convert_root_relative2relative($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = Url::path_to_root();
		$server_url_bak = Url::server_url();

		Url::path_to_root($path_to_root);
		Url::server_url($server_url);

		$result = preg_replace_callback(Url::_build_html_match_regex(true),
		array('Url', '_convert_url_to_relative'), $html_text);

		Url::path_to_root($path_to_root_bak);
		Url::server_url($server_url_bak);

		return $result;
	}

	





	static function _convert_url_to_absolute($url_params)
	{
		$url = new Url($url_params[2]);
		$url_params[2] = $url->absolute();
		return $url_params[1] . $url_params[2] . $url_params[3];
	}

	





	static function _convert_url_to_root_relative($url_params)
	{
		$url = new Url($url_params[2]);
		$url_params[2] = $url->relative();
		return $url_params[1] . $url_params[2] . $url_params[3];
	}

	





	static function _convert_url_to_relative($url_params)
	{
		$url = new Url($url_params[2]);
		if ($url->is_relative())
		{
			$url_params[2] = Url::compress(Url::path_to_root() . $url->relative());
		}
		return $url_params[1] . $url_params[2] . $url_params[3];
	}


	static function _build_html_match_regex($only_match_relative = false)
	{
		static $regex_match_all = null;
		static $regex_only_match_relative = null;

		
		if ((!$only_match_relative && $regex_match_all === null) || ($only_match_relative && $regex_only_match_relative === null))
		{
			$regex = array();
			$nodes =      array('a',    'img', 'form',   'object', 'param name="movie"');
			$attributes = array('href', 'src', 'action', 'data',   'value');

			$nodes_length = count($nodes);
			for ($i = 0; $i < $nodes_length; $i++)
			{
				$a_regex = '`(<' . $nodes[$i] . ' [^>]*(?<= )' . $attributes[$i] . '=")(';
				if ($only_match_relative)
				{
					$a_regex .= '/';
				}
				$a_regex .= '[^"]+)(")`isU';
				$regex[] = $a_regex;
			}
			
			$a_regex = '`(<script type="text/javascript"><!--\s*insert(?:Sound|Movie|Swf)Player\\(")(';
			if ($only_match_relative)
			{
				$a_regex .= '/';
			}
			$a_regex .= '[^"]+)("\\)\s*--></script>)`isU';
			$regex[] = $a_regex;

			
			if ($only_match_relative)
			{
				$regex_only_match_relative = $regex;
			}
			else
			{
				$regex_match_all = $regex;
			}
		}

		if ($only_match_relative)
		{
			return $regex_only_match_relative;
		}
		else
		{
			return $regex_match_all;
		}
	}

	






	static function get_relative($url, $path_to_root = null, $server_url = null)
	{
		$o_url = new Url($url, $path_to_root, $server_url);
		return $o_url->relative();
	}

	






	function path_to_root($path = null)
	{
		static $path_to_root = PATH_TO_ROOT;
		if ($path != null)
		{
			$path_to_root = $path;
		}
		return $path_to_root;
	}

	






	function server_url($url = null)
	{
		static $server_url = SERVER_URL;
		if ($url !== null)
		{
			$server_url = $url;
		}
		return $server_url;
	}

	

















	static function get_wellformness_regex($protocol = REGEX_MULTIPLICITY_OPTIONNAL,
	$user = REGEX_MULTIPLICITY_OPTIONNAL, $domain = REGEX_MULTIPLICITY_OPTIONNAL,
	$folders = REGEX_MULTIPLICITY_OPTIONNAL, $file = REGEX_MULTIPLICITY_OPTIONNAL,
	$args = REGEX_MULTIPLICITY_OPTIONNAL, $anchor = REGEX_MULTIPLICITY_OPTIONNAL, $forbid_js = true)
	{
		static $forbid_js_regex = '(?!javascript:)';
		static $protocol_regex = '[a-z0-9-_]+(?::[a-z0-9-_]+)*://';
		static $user_regex = '[a-z0-9-_]+(?::[a-z0-9-_]+)?@';
		static $domain_regex = '(?:[a-z0-9-_~]+\.)*[a-z0-9-_~]+(?::[0-9]{1,5})?/';
		static $folders_regex = '/*(?:[a-z0-9~_\.-]+/+)*';
		static $file_regex = '[a-z0-9-+_,~:\.\%]+';
		static $args_regex = '(?:\?(?!&)(?:(?:&amp;|&)?[a-z0-9-+=,_~:;/\.\?\'\%]+(?:=[a-z0-9-+=!_~:;/\.\?\'\%+=]+)?)*)?';
        static $anchor_regex = '\#[a-z0-9-+=!_~:;/\.\?\'\%+=]*';

		if ($forbid_js)
		{
			$protocol_regex_secured = $forbid_js_regex . $protocol_regex;
		}
		else
		{
			$protocol_regex_secured = $protocol_regex;
		}

		$regex = set_subregex_multiplicity($protocol_regex_secured, $protocol) .
		set_subregex_multiplicity($user_regex, $user) .
		set_subregex_multiplicity($domain_regex, $domain) .
		set_subregex_multiplicity($folders_regex, $folders) .
		set_subregex_multiplicity($file_regex, $file);
        if ($anchor == REGEX_MULTIPLICITY_OPTIONNAL)
		{
			$regex .= set_subregex_multiplicity($anchor_regex, REGEX_MULTIPLICITY_OPTIONNAL);
		}
		$regex .=  set_subregex_multiplicity($args_regex, $args) .
		set_subregex_multiplicity($anchor_regex, $anchor);

		return $regex;
	}

	

















	static function check_wellformness($url, $protocol = REGEX_MULTIPLICITY_OPTIONNAL,
	$user = REGEX_MULTIPLICITY_OPTIONNAL, $domain = REGEX_MULTIPLICITY_OPTIONNAL,
	$folders = REGEX_MULTIPLICITY_OPTIONNAL, $file = REGEX_MULTIPLICITY_OPTIONNAL,
	$args = REGEX_MULTIPLICITY_OPTIONNAL, $anchor = REGEX_MULTIPLICITY_OPTIONNAL, $forbid_js = true)
	{
		return preg_match('`^' . Url::get_wellformness_regex($protocol, $user, $domain,
		$folders, $file, $args, $anchor, $forbid_js) . '$`i', $url);
	}

	var $url = '';
	var $is_relative = false;
	var $path_to_root = '';
	var $server_url = '';
}
?>
