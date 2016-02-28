<?php










































define('GESHI_VERSION', '1.0.8.3');


if (!defined('GESHI_ROOT')) {
    
    define('GESHI_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}


define('GESHI_LANG_ROOT', GESHI_ROOT . 'geshi' . DIRECTORY_SEPARATOR);


if (!defined('GESHI_SECURITY_PARANOID')) {
    
    define('GESHI_SECURITY_PARANOID', false);
}



define('GESHI_NO_LINE_NUMBERS', 0);

define('GESHI_NORMAL_LINE_NUMBERS', 1);

define('GESHI_FANCY_LINE_NUMBERS', 2);



define('GESHI_HEADER_NONE', 0);

define('GESHI_HEADER_DIV', 1);

define('GESHI_HEADER_PRE', 2);

define('GESHI_HEADER_PRE_VALID', 3);













define('GESHI_HEADER_PRE_TABLE', 4);



define('GESHI_CAPS_NO_CHANGE', 0);

define('GESHI_CAPS_UPPER', 1);

define('GESHI_CAPS_LOWER', 2);



define('GESHI_LINK', 0);

define('GESHI_HOVER', 1);

define('GESHI_ACTIVE', 2);

define('GESHI_VISITED', 3);





define('GESHI_START_IMPORTANT', '<BEGIN GeSHi>');

define('GESHI_END_IMPORTANT', '<END GeSHi>');






define('GESHI_NEVER', 0);


define('GESHI_MAYBE', 1);

define('GESHI_ALWAYS', 2);



define('GESHI_SEARCH', 0);


define('GESHI_REPLACE', 1);

define('GESHI_MODIFIERS', 2);


define('GESHI_BEFORE', 3);


define('GESHI_AFTER', 4);


define('GESHI_CLASS', 5);


define('GESHI_COMMENTS', 0);


define('GESHI_PHP_PRE_433', !(version_compare(PHP_VERSION, '4.3.3') === 1));


if (!function_exists('stripos')) {
    
    if (GESHI_PHP_PRE_433) {
        


        function stripos($haystack, $needle, $offset = null) {
            if (!is_null($offset)) {
                $haystack = substr($haystack, $offset);
            }
            if (preg_match('/'. preg_quote($needle, '/') . '/', $haystack, $match, PREG_OFFSET_CAPTURE)) {
                return $match[0][1];
            }
            return false;
        }
    }
    else {
        


        function stripos($haystack, $needle, $offset = null) {
            if (preg_match('/'. preg_quote($needle, '/') . '/', $haystack, $match, PREG_OFFSET_CAPTURE, $offset)) {
                return $match[0][1];
            }
            return false;
        }
    }
}





define('GESHI_MAX_PCRE_SUBPATTERNS', 500);





define('GESHI_MAX_PCRE_LENGTH', 12288);



define('GESHI_NUMBER_INT_BASIC', 1);        

define('GESHI_NUMBER_INT_CSTYLE', 2);       

define('GESHI_NUMBER_BIN_SUFFIX', 16);           

define('GESHI_NUMBER_BIN_PREFIX_PERCENT', 32);   

define('GESHI_NUMBER_BIN_PREFIX_0B', 64);        

define('GESHI_NUMBER_OCT_PREFIX', 256);           

define('GESHI_NUMBER_OCT_SUFFIX', 512);           

define('GESHI_NUMBER_HEX_PREFIX', 4096);           

define('GESHI_NUMBER_HEX_SUFFIX', 8192);           

define('GESHI_NUMBER_FLT_NONSCI', 65536);          

define('GESHI_NUMBER_FLT_NONSCI_F', 131072);       

define('GESHI_NUMBER_FLT_SCI_SHORT', 262144);      

define('GESHI_NUMBER_FLT_SCI_ZERO', 524288);       






define('GESHI_ERROR_NO_INPUT', 1);

define('GESHI_ERROR_NO_SUCH_LANG', 2);

define('GESHI_ERROR_FILE_NOT_READABLE', 3);

define('GESHI_ERROR_INVALID_HEADER_TYPE', 4);

define('GESHI_ERROR_INVALID_LINE_NUMBER_TYPE', 5);














class GeSHi {
    


    



    var $source = '';

    



    var $language = '';

    



    var $language_data = array();

    



    var $language_path = GESHI_LANG_ROOT;

    




    var $error = false;

    



    var $error_messages = array(
        GESHI_ERROR_NO_SUCH_LANG => 'GeSHi could not find the language {LANGUAGE} (using path {PATH})',
        GESHI_ERROR_FILE_NOT_READABLE => 'The file specified for load_from_file was not readable',
        GESHI_ERROR_INVALID_HEADER_TYPE => 'The header type specified is invalid',
        GESHI_ERROR_INVALID_LINE_NUMBER_TYPE => 'The line number type specified is invalid'
    );

    



    var $strict_mode = false;

    



    var $use_classes = false;

    









    var $header_type = GESHI_HEADER_PRE;

    



    var $lexic_permissions = array(
        'KEYWORDS' =>    array(),
        'COMMENTS' =>    array('MULTI' => true),
        'REGEXPS' =>     array(),
        'ESCAPE_CHAR' => true,
        'BRACKETS' =>    true,
        'SYMBOLS' =>     false,
        'STRINGS' =>     true,
        'NUMBERS' =>     true,
        'METHODS' =>     true,
        'SCRIPT' =>      true
    );

    



    var $time = 0;

    



    var $header_content = '';

    



    var $footer_content = '';

    



    var $header_content_style = '';

    



    var $footer_content_style = '';

    




    var $force_code_block = false;

    



    var $link_styles = array();

    





    var $enable_important_blocks = false;

    






    var $important_styles = 'font-weight: bold; color: red;'; 

    



    var $add_ids = false;

    



    var $highlight_extra_lines = array();

    



    var $highlight_extra_lines_styles = array();

    



    var $highlight_extra_lines_style = 'background-color: #ffc;';

    





    var $line_ending = null;

    



    var $line_numbers_start = 1;

    



    var $overall_style = 'font-family:monospace;';

    



    var $code_style = 'font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;';

    



    var $overall_class = '';

    



    var $overall_id = '';

    



    var $line_style1 = 'font-weight: normal; vertical-align:top;';

    



    var $line_style2 = 'font-weight: bold; vertical-align:top;';

    



    var $table_linenumber_style = 'width:1px;text-align:right;margin:0;padding:0 2px;vertical-align:top;';

    



    var $line_numbers = GESHI_NO_LINE_NUMBERS;

    




    var $allow_multiline_span = true;

    



    var $line_nth_row = 0;

    



    var $tab_width = 8;

    



    var $use_language_tab_width = false;

    



    var $link_target = '';

    




    var $encoding = 'utf-8';

    



    var $keyword_links = true;

    




    var $loaded_language = '';

    





    var $parse_cache_built = false;

    














    var $_kw_replace_group = 0;
    var $_rx_key = 0;

    






    var $_hmr_before = '';
    var $_hmr_replace = '';
    var $_hmr_after = '';
    var $_hmr_key = 0;

    

    













    function GeSHi($source = '', $language = '', $path = '') {
        if (!empty($source)) {
            $this->set_source($source);
        }
        if (!empty($language)) {
            $this->set_language($language);
        }
        $this->set_language_path($path);
    }

    






    function error() {
        if ($this->error) {
            
            $debug_tpl_vars = array(
                '{LANGUAGE}' => $this->language,
                '{PATH}' => $this->language_path
            );
            $msg = str_replace(
                array_keys($debug_tpl_vars),
                array_values($debug_tpl_vars),
                $this->error_messages[$this->error]);

            return "<br /><strong>GeSHi Error:</strong> $msg (code {$this->error})<br />";
        }
        return false;
    }

    






    function get_language_name() {
        if (GESHI_ERROR_NO_SUCH_LANG == $this->error) {
            return $this->language_data['LANG_NAME'] . ' (Unknown Language)';
        }
        return $this->language_data['LANG_NAME'];
    }

    





    function set_source($source) {
        $this->source = $source;
        $this->highlight_extra_lines = array();
    }

    








    function set_language($language, $force_reset = false) {
        if ($force_reset) {
            $this->loaded_language = false;
        }

        
        $language = preg_replace('#[^a-zA-Z0-9\-_]#', '', $language);

        $language = strtolower($language);

        
        $file_name = $this->language_path . $language . '.php';
        if ($file_name == $this->loaded_language) {
            
            return;
        }

        $this->language = $language;

        $this->error = false;
        $this->strict_mode = GESHI_NEVER;

        
        if (!is_readable($file_name)) {
            $this->error = GESHI_ERROR_NO_SUCH_LANG;
            return;
        }

        
        $this->load_language($file_name);
    }

    











    function set_language_path($path) {
        if(strpos($path,':')) {
            
            if(DIRECTORY_SEPARATOR == "\\") {
                if(!preg_match('#^[a-zA-Z]:#', $path) || false !== strpos($path, ':', 2)) {
                    return;
                }
            } else {
                return;
            }
        }
        if(preg_match('#[^/a-zA-Z0-9_\.\-\\\s:]#', $path)) {
            
            return;
        }
        if(GESHI_SECURITY_PARANOID && false !== strpos($path, '/.')) {
            
            return;
        }
        if(GESHI_SECURITY_PARANOID && false !== strpos($path, '..')) {
            
            return;
        }
        if ($path) {
            $this->language_path = ('/' == $path[strlen($path) - 1]) ? $path : $path . '/';
            $this->set_language($this->language); 
        }
    }

    













    function set_header_type($type) {
        
        if (!in_array($type, array(GESHI_HEADER_NONE, GESHI_HEADER_DIV,
            GESHI_HEADER_PRE, GESHI_HEADER_PRE_VALID, GESHI_HEADER_PRE_TABLE))) {
            $this->error = GESHI_ERROR_INVALID_HEADER_TYPE;
            return;
        }

        
        $this->header_type = $type;
    }

    








    function set_overall_style($style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->overall_style = $style;
        } else {
            $this->overall_style .= $style;
        }
    }

    







    function set_overall_class($class) {
        $this->overall_class = $class;
    }

    






    function set_overall_id($id) {
        $this->overall_id = $id;
    }

    






    function enable_classes($flag = true) {
        $this->use_classes = ($flag) ? true : false;
    }

    














    function set_code_style($style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->code_style = $style;
        } else {
            $this->code_style .= $style;
        }
    }

    











    function set_line_style($style1, $style2 = '', $preserve_defaults = false) {
        
        if (is_bool($style2)) {
            $preserve_defaults = $style2;
            $style2 = '';
        }

        
        if (!$preserve_defaults) {
            $this->line_style1 = $style1;
            $this->line_style2 = $style2;
        } else {
            $this->line_style1 .= $style1;
            $this->line_style2 .= $style2;
        }
    }

    
















    function enable_line_numbers($flag, $nth_row = 5) {
        if (GESHI_NO_LINE_NUMBERS != $flag && GESHI_NORMAL_LINE_NUMBERS != $flag
            && GESHI_FANCY_LINE_NUMBERS != $flag) {
            $this->error = GESHI_ERROR_INVALID_LINE_NUMBER_TYPE;
        }
        $this->line_numbers = $flag;
        $this->line_nth_row = $nth_row;
    }

    








    function enable_multiline_span($flag) {
        $this->allow_multiline_span = (bool) $flag;
    }

    





    function get_multiline_span() {
        return $this->allow_multiline_span;
    }

    










    function set_keyword_group_style($key, $style, $preserve_defaults = false) {
        
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['KEYWORDS'][$key] = $style;
        } else {
            $this->language_data['STYLES']['KEYWORDS'][$key] .= $style;
        }

        
        if (!isset($this->lexic_permissions['KEYWORDS'][$key])) {
            $this->lexic_permissions['KEYWORDS'][$key] = true;
        }
    }

    






    function set_keyword_group_highlighting($key, $flag = true) {
        $this->lexic_permissions['KEYWORDS'][$key] = ($flag) ? true : false;
    }

    










    function set_comments_style($key, $style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['COMMENTS'][$key] = $style;
        } else {
            $this->language_data['STYLES']['COMMENTS'][$key] .= $style;
        }
    }

    






    function set_comments_highlighting($key, $flag = true) {
        $this->lexic_permissions['COMMENTS'][$key] = ($flag) ? true : false;
    }

    









    function set_escape_characters_style($style, $preserve_defaults = false, $group = 0) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['ESCAPE_CHAR'][$group] = $style;
        } else {
            $this->language_data['STYLES']['ESCAPE_CHAR'][$group] .= $style;
        }
    }

    





    function set_escape_characters_highlighting($flag = true) {
        $this->lexic_permissions['ESCAPE_CHAR'] = ($flag) ? true : false;
    }

    













    function set_brackets_style($style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['BRACKETS'][0] = $style;
        } else {
            $this->language_data['STYLES']['BRACKETS'][0] .= $style;
        }
    }

    









    function set_brackets_highlighting($flag) {
        $this->lexic_permissions['BRACKETS'] = ($flag) ? true : false;
    }

    










    function set_symbols_style($style, $preserve_defaults = false, $group = 0) {
        
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['SYMBOLS'][$group] = $style;
        } else {
            $this->language_data['STYLES']['SYMBOLS'][$group] .= $style;
        }

        
        if (0 == $group) {
            $this->set_brackets_style ($style, $preserve_defaults);
        }
    }

    





    function set_symbols_highlighting($flag) {
        
        $this->lexic_permissions['SYMBOLS'] = ($flag) ? true : false;

        
        $this->set_brackets_highlighting ($flag);
    }

    









    function set_strings_style($style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['STRINGS'][0] = $style;
        } else {
            $this->language_data['STYLES']['STRINGS'][0] .= $style;
        }
    }

    





    function set_strings_highlighting($flag) {
        $this->lexic_permissions['STRINGS'] = ($flag) ? true : false;
    }

    









    function set_numbers_style($style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['NUMBERS'][0] = $style;
        } else {
            $this->language_data['STYLES']['NUMBERS'][0] .= $style;
        }
    }

    





    function set_numbers_highlighting($flag) {
        $this->lexic_permissions['NUMBERS'] = ($flag) ? true : false;
    }

    












    function set_methods_style($key, $style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['METHODS'][$key] = $style;
        } else {
            $this->language_data['STYLES']['METHODS'][$key] .= $style;
        }
    }

    





    function set_methods_highlighting($flag) {
        $this->lexic_permissions['METHODS'] = ($flag) ? true : false;
    }

    









    function set_regexps_style($key, $style, $preserve_defaults = false) {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['REGEXPS'][$key] = $style;
        } else {
            $this->language_data['STYLES']['REGEXPS'][$key] .= $style;
        }
    }

    






    function set_regexps_highlighting($key, $flag) {
        $this->lexic_permissions['REGEXPS'][$key] = ($flag) ? true : false;
    }

    






    function set_case_sensitivity($key, $case) {
        $this->language_data['CASE_SENSITIVE'][$key] = ($case) ? true : false;
    }

    









    function set_case_keywords($case) {
        if (in_array($case, array(
            GESHI_CAPS_NO_CHANGE, GESHI_CAPS_UPPER, GESHI_CAPS_LOWER))) {
            $this->language_data['CASE_KEYWORDS'] = $case;
        }
    }

    







    function set_tab_width($width) {
        $this->tab_width = intval($width);

        
        if ($this->tab_width < 1) {
            
            $this->tab_width = 8;
        }
    }

    





    function set_use_language_tab_width($use) {
        $this->use_language_tab_width = (bool) $use;
    }

    






    function get_real_tab_width() {
        if (!$this->use_language_tab_width ||
            !isset($this->language_data['TAB_WIDTH'])) {
            return $this->tab_width;
        } else {
            return $this->language_data['TAB_WIDTH'];
        }
    }

    







    function enable_strict_mode($mode = true) {
        if (GESHI_MAYBE == $this->language_data['STRICT_MODE_APPLIES']) {
            $this->strict_mode = ($mode) ? GESHI_ALWAYS : GESHI_NEVER;
        }
    }

    






    function disable_highlighting() {
        $this->enable_highlighting(false);
    }

    









    function enable_highlighting($flag = true) {
        $flag = $flag ? true : false;
        foreach ($this->lexic_permissions as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->lexic_permissions[$key][$k] = $flag;
                }
            } else {
                $this->lexic_permissions[$key] = $flag;
            }
        }

        
        $this->enable_important_blocks = $flag;
    }

    










    function get_language_name_from_extension( $extension, $lookup = array() ) {
        if ( !is_array($lookup) || empty($lookup)) {
            $lookup = array(
                'actionscript' => array('as'),
                'ada' => array('a', 'ada', 'adb', 'ads'),
                'apache' => array('conf'),
                'asm' => array('ash', 'asm', 'inc'),
                'asp' => array('asp'),
                'bash' => array('sh'),
                'bf' => array('bf'),
                'c' => array('c', 'h'),
                'c_mac' => array('c', 'h'),
                'caddcl' => array(),
                'cadlisp' => array(),
                'cdfg' => array('cdfg'),
                'cobol' => array('cbl'),
                'cpp' => array('cpp', 'hpp', 'C', 'H', 'CPP', 'HPP'),
                'csharp' => array('cs'),
                'css' => array('css'),
                'd' => array('d'),
                'delphi' => array('dpk', 'dpr', 'pp', 'pas'),
                'diff' => array('diff', 'patch'),
                'dos' => array('bat', 'cmd'),
                'gettext' => array('po', 'pot'),
                'gml' => array('gml'),
                'gnuplot' => array('plt'),
                'groovy' => array('groovy'),
                'haskell' => array('hs'),
                'html4strict' => array('html', 'htm'),
                'ini' => array('ini', 'desktop'),
                'java' => array('java'),
                'javascript' => array('js'),
                'klonec' => array('kl1'),
                'klonecpp' => array('klx'),
                'latex' => array('tex'),
                'lisp' => array('lisp'),
                'lua' => array('lua'),
                'matlab' => array('m'),
                'mpasm' => array(),
                'mysql' => array('sql'),
                'nsis' => array(),
                'objc' => array(),
                'oobas' => array(),
                'oracle8' => array(),
                'oracle10' => array(),
                'pascal' => array('pas'),
                'perl' => array('pl', 'pm'),
                'php' => array('php', 'php5', 'phtml', 'phps'),
                'povray' => array('pov'),
                'providex' => array('pvc', 'pvx'),
                'prolog' => array('pl'),
                'python' => array('py'),
                'qbasic' => array('bi'),
                'reg' => array('reg'),
                'ruby' => array('rb'),
                'sas' => array('sas'),
                'scala' => array('scala'),
                'scheme' => array('scm'),
                'scilab' => array('sci'),
                'smalltalk' => array('st'),
                'smarty' => array(),
                'tcl' => array('tcl'),
                'vb' => array('bas'),
                'vbnet' => array(),
                'visualfoxpro' => array(),
                'whitespace' => array('ws'),
                'xml' => array('xml', 'svg'),
                'z80' => array('z80', 'asm', 'inc')
            );
        }

        foreach ($lookup as $lang => $extensions) {
            if (in_array($extension, $extensions)) {
                return $lang;
            }
        }
        return '';
    }

    
















    function load_from_file($file_name, $lookup = array()) {
        if (is_readable($file_name)) {
            $this->set_source(file_get_contents($file_name));
            $this->set_language($this->get_language_name_from_extension(substr(strrchr($file_name, '.'), 1), $lookup));
        } else {
            $this->error = GESHI_ERROR_FILE_NOT_READABLE;
        }
    }

    






    function add_keyword($key, $word) {
        if (!in_array($word, $this->language_data['KEYWORDS'][$key])) {
            $this->language_data['KEYWORDS'][$key][] = $word;

            
            if ($this->parse_cache_built) {
                $subkey = count($this->language_data['CACHED_KEYWORD_LISTS'][$key]) - 1;
                $this->language_data['CACHED_KEYWORD_LISTS'][$key][$subkey] .= '|' . preg_quote($word, '/');
            }
        }
    }

    












    function remove_keyword($key, $word, $recompile = true) {
        $key_to_remove = array_search($word, $this->language_data['KEYWORDS'][$key]);
        if ($key_to_remove !== false) {
            unset($this->language_data['KEYWORDS'][$key][$key_to_remove]);

            
            if ($recompile && $this->parse_cache_built) {
                $this->optimize_keyword_group($key);
            }
        }
    }

    








    function add_keyword_group($key, $styles, $case_sensitive = true, $words = array()) {
        $words = (array) $words;
        if  (empty($words)) {
            
            return false;
        }

        
        $this->language_data['KEYWORDS'][$key] = $words;
        $this->lexic_permissions['KEYWORDS'][$key] = true;
        $this->language_data['CASE_SENSITIVE'][$key] = $case_sensitive;
        $this->language_data['STYLES']['KEYWORDS'][$key] = $styles;

        
        if ($this->parse_cache_built) {
            $this->optimize_keyword_group($key);
        }
    }

    





    function remove_keyword_group ($key) {
        
        unset($this->language_data['KEYWORDS'][$key]);
        unset($this->lexic_permissions['KEYWORDS'][$key]);
        unset($this->language_data['CASE_SENSITIVE'][$key]);
        unset($this->language_data['STYLES']['KEYWORDS'][$key]);

        
        unset($this->language_data['CACHED_KEYWORD_LISTS'][$key]);
    }

    





    function optimize_keyword_group($key) {
        $this->language_data['CACHED_KEYWORD_LISTS'][$key] =
            $this->optimize_regexp_list($this->language_data['KEYWORDS'][$key]);
        $space_as_whitespace = false;
        if(isset($this->language_data['PARSER_CONTROL'])) {
            if(isset($this->language_data['PARSER_CONTROL']['KEYWORDS'])) {
                if(isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['SPACE_AS_WHITESPACE'])) {
                    $space_as_whitespace = $this->language_data['PARSER_CONTROL']['KEYWORDS']['SPACE_AS_WHITESPACE'];
                }
                if(isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'])) {
                    if(isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'])) {
                        $space_as_whitespace = $this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'];
                    }
                }
            }
        }
        if($space_as_whitespace) {
            foreach($this->language_data['CACHED_KEYWORD_LISTS'][$key] as $rxk => $rxv) {
                $this->language_data['CACHED_KEYWORD_LISTS'][$key][$rxk] =
                    str_replace(" ", "\\s+", $rxv);
            }
        }
    }

    





    function set_header_content($content) {
        $this->header_content = $content;
    }

    





    function set_footer_content($content) {
        $this->footer_content = $content;
    }

    





    function set_header_content_style($style) {
        $this->header_content_style = $style;
    }

    





    function set_footer_content_style($style) {
        $this->footer_content_style = $style;
    }

    






    function enable_inner_code_block($flag) {
        $this->force_code_block = (bool)$flag;
    }

    








    function set_url_for_keyword_group($group, $url) {
        $this->language_data['URLS'][$group] = $url;
    }

    







    function set_link_styles($type, $styles) {
        $this->link_styles[$type] = $styles;
    }

    





    function set_link_target($target) {
        if (!$target) {
            $this->link_target = '';
        } else {
            $this->link_target = ' target="' . $target . '"';
        }
    }

    





    function set_important_styles($styles) {
        $this->important_styles = $styles;
    }

    







    function enable_important_blocks($flag) {
        $this->enable_important_blocks = ( $flag ) ? true : false;
    }

    





    function enable_ids($flag = true) {
        $this->add_ids = ($flag) ? true : false;
    }

    













    function highlight_lines_extra($lines, $style = null) {
        if (is_array($lines)) {
            
            foreach ($lines as $line) {
                $this->highlight_lines_extra($line, $style);
            }
        } else {
            
            $lines = intval($lines);
            $this->highlight_extra_lines[$lines] = $lines;

            
            if ($style === null) { 
                unset($this->highlight_extra_lines_styles[$lines]);
            } else if ($style === false) { 
                unset($this->highlight_extra_lines[$lines]);
                unset($this->highlight_extra_lines_styles[$lines]);
            } else {
                $this->highlight_extra_lines_styles[$lines] = $style;
            }
        }
    }

    





    function set_highlight_lines_extra_style($styles) {
        $this->highlight_extra_lines_style = $styles;
    }

    





    function set_line_ending($line_ending) {
        $this->line_ending = (string)$line_ending;
    }

    














    function start_line_numbers_at($number) {
        $this->line_numbers_start = abs(intval($number));
    }

    











    function set_encoding($encoding) {
        if ($encoding) {
          $this->encoding = strtolower($encoding);
        }
    }

    





    function enable_keyword_links($enable = true) {
        $this->keyword_links = (bool) $enable;
    }

    








    function build_style_cache() {
        
        if($this->lexic_permissions['NUMBERS']) {
            
            if(!isset($this->language_data['NUMBERS'])) {
                $this->language_data['NUMBERS'] = 0;
            }

            if(is_array($this->language_data['NUMBERS'])) {
                $this->language_data['NUMBERS_CACHE'] = $this->language_data['NUMBERS'];
            } else {
                $this->language_data['NUMBERS_CACHE'] = array();
                if(!$this->language_data['NUMBERS']) {
                    $this->language_data['NUMBERS'] =
                        GESHI_NUMBER_INT_BASIC |
                        GESHI_NUMBER_FLT_NONSCI;
                }

                for($i = 0, $j = $this->language_data['NUMBERS']; $j > 0; ++$i, $j>>=1) {
                    
                    if(isset($this->language_data['STYLES']['NUMBERS'][1<<$i])) {
                        $this->language_data['STYLES']['NUMBERS'][$i] =
                            $this->language_data['STYLES']['NUMBERS'][1<<$i];
                        unset($this->language_data['STYLES']['NUMBERS'][1<<$i]);
                    }

                    
                    if($j&1) {
                        
                        
                        if(isset($this->language_data['STYLES']['NUMBERS'][$i])) {
                            $this->language_data['NUMBERS_CACHE'][$i] = 1 << $i;
                        } else {
                            if(!isset($this->language_data['NUMBERS_CACHE'][0])) {
                                $this->language_data['NUMBERS_CACHE'][0] = 0;
                            }
                            $this->language_data['NUMBERS_CACHE'][0] |= 1 << $i;
                        }
                    }
                }
            }
        }
    }

    






    function build_parse_cache() {
        
        
        
        
        
        if ($this->lexic_permissions['SYMBOLS'] && !empty($this->language_data['SYMBOLS'])) {
            $this->language_data['MULTIPLE_SYMBOL_GROUPS'] = count($this->language_data['STYLES']['SYMBOLS']) > 1;

            $this->language_data['SYMBOL_DATA'] = array();
            $symbol_preg_multi = array(); 
            $symbol_preg_single = array(); 
            foreach ($this->language_data['SYMBOLS'] as $key => $symbols) {
                if (is_array($symbols)) {
                    foreach ($symbols as $sym) {
                        $sym = $this->hsc($sym);
                        if (!isset($this->language_data['SYMBOL_DATA'][$sym])) {
                            $this->language_data['SYMBOL_DATA'][$sym] = $key;
                            if (isset($sym[1])) { 
                                $symbol_preg_multi[] = preg_quote($sym, '/');
                            } else { 
                                if ($sym == '-') {
                                    
                                    $symbol_preg_single[] = '\-';
                                } else {
                                    $symbol_preg_single[] = preg_quote($sym, '/');
                                }
                            }
                        }
                    }
                } else {
                    $symbols = $this->hsc($symbols);
                    if (!isset($this->language_data['SYMBOL_DATA'][$symbols])) {
                        $this->language_data['SYMBOL_DATA'][$symbols] = 0;
                        if (isset($symbols[1])) { 
                            $symbol_preg_multi[] = preg_quote($symbols, '/');
                        } else if ($symbols == '-') {
                            
                            $symbol_preg_single[] = '\-';
                        } else { 
                            $symbol_preg_single[] = preg_quote($symbols, '/');
                        }
                    }
                }
            }

            
            
            
            
            $symbol_preg = array();
            if (!empty($symbol_preg_multi)) {
                rsort($symbol_preg_multi);
                $symbol_preg[] = implode('|', $symbol_preg_multi);
            }
            if (!empty($symbol_preg_single)) {
                rsort($symbol_preg_single);
                $symbol_preg[] = '[' . implode('', $symbol_preg_single) . ']';
            }
            $this->language_data['SYMBOL_SEARCH'] = implode("|", $symbol_preg);
        }

        
        
        $this->language_data['CACHED_KEYWORD_LISTS'] = array();
        foreach (array_keys($this->language_data['KEYWORDS']) as $key) {
            if (!isset($this->lexic_permissions['KEYWORDS'][$key]) ||
                    $this->lexic_permissions['KEYWORDS'][$key]) {
                $this->optimize_keyword_group($key);
            }
        }

        
        if ($this->lexic_permissions['BRACKETS']) {
            $this->language_data['CACHE_BRACKET_MATCH'] = array('[', ']', '(', ')', '{', '}');
            if (!$this->use_classes && isset($this->language_data['STYLES']['BRACKETS'][0])) {
                $this->language_data['CACHE_BRACKET_REPLACE'] = array(
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#91;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#93;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#40;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#41;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#123;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#125;|>',
                );
            }
            else {
                $this->language_data['CACHE_BRACKET_REPLACE'] = array(
                    '<| class="br0">&#91;|>',
                    '<| class="br0">&#93;|>',
                    '<| class="br0">&#40;|>',
                    '<| class="br0">&#41;|>',
                    '<| class="br0">&#123;|>',
                    '<| class="br0">&#125;|>',
                );
            }
        }

        
        if($this->lexic_permissions['NUMBERS']) {
            
            
            if(!isset($this->language_data['NUMBERS_CACHE'])) {
                $this->build_style_cache();
            }

            
            
            static $numbers_format = array(
                GESHI_NUMBER_INT_BASIC =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])([1-9]\d*?|0)(?![0-9a-z\.])',
                GESHI_NUMBER_INT_CSTYLE =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])([1-9]\d*?|0)l(?![0-9a-z\.])',
                GESHI_NUMBER_BIN_SUFFIX =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])[01]+?b(?![0-9a-z\.])',
                GESHI_NUMBER_BIN_PREFIX_PERCENT =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])%[01]+?(?![0-9a-z\.])',
                GESHI_NUMBER_BIN_PREFIX_0B =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])0b[01]+?(?![0-9a-z\.])',
                GESHI_NUMBER_OCT_PREFIX =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])0[0-7]+?(?![0-9a-z\.])',
                GESHI_NUMBER_OCT_SUFFIX =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])[0-7]+?o(?![0-9a-z\.])',
                GESHI_NUMBER_HEX_PREFIX =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])0x[0-9a-f]+?(?![0-9a-z\.])',
                GESHI_NUMBER_HEX_SUFFIX =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\d[0-9a-f]*?h(?![0-9a-z\.])',
                GESHI_NUMBER_FLT_NONSCI =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\d+?\.\d+?(?![0-9a-z\.])',
                GESHI_NUMBER_FLT_NONSCI_F =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])(?:\d+?(?:\.\d*?)?|\.\d+?)f(?![0-9a-z\.])',
                GESHI_NUMBER_FLT_SCI_SHORT =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\.\d+?(?:e[+\-]?\d+?)?(?![0-9a-z\.])',
                GESHI_NUMBER_FLT_SCI_ZERO =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])(?:\d+?(?:\.\d*?)?|\.\d+?)(?:e[+\-]?\d+?)?(?![0-9a-z\.])'
                );

            
            
            $this->language_data['NUMBERS_RXCACHE'] = array();
            foreach($this->language_data['NUMBERS_CACHE'] as $key => $rxdata) {
                if(is_string($rxdata)) {
                    $regexp = $rxdata;
                } else {
                    
                    
                    $rxuse = array();
                    for($i = 1; $i <= $rxdata; $i<<=1) {
                        if($rxdata & $i) {
                            $rxuse[] = $numbers_format[$i];
                        }
                    }
                    $regexp = implode("|", $rxuse);
                }

                $this->language_data['NUMBERS_RXCACHE'][$key] =
                    "/(?<!<\|\/NUM!)(?<!\d\/>)($regexp)(?!\|>)/i";
            }
        }

        $this->parse_cache_built = true;
    }

    









    function parse_code () {
        
        $start_time = microtime();

        
        if ($this->error) {
            
            $result = $this->hsc($this->source);

            
            
            $result = str_replace(array('<SEMI>', '<PIPE>'), array(';', '|'), $result);

            
            $this->set_time($start_time, $start_time);
            $this->finalise($result);
            return $result;
        }

        
        if (!$this->parse_cache_built) {
            $this->build_parse_cache();
        }

        
        $code = str_replace("\r\n", "\n", $this->source);
        $code = str_replace("\r", "\n", $code);

        


        
        $length           = strlen($code);
        $COMMENT_MATCHED  = false;
        $stuff_to_parse   = '';
        $endresult        = '';

        
        
        if ($this->enable_important_blocks) {
            $this->language_data['COMMENT_MULTI'][GESHI_START_IMPORTANT] = GESHI_END_IMPORTANT;
        }

        if ($this->strict_mode) {
            
            
            $k = 0;
            $parts = array();
            $matches = array();
            $next_match_pointer = null;
            
            $delim_copy = $this->language_data['SCRIPT_DELIMITERS'];
            $i = 0;
            while ($i < $length) {
                $next_match_pos = $length + 1; 
                foreach ($delim_copy as $dk => $delimiters) {
                    if(is_array($delimiters)) {
                        foreach ($delimiters as $open => $close) {
                            
                            if (!isset($matches[$dk][$open])) {
                                $matches[$dk][$open] = array(
                                    'next_match' => -1,
                                    'dk' => $dk,

                                    'open' => $open, 
                                    'open_strlen' => strlen($open),

                                    'close' => $close,
                                    'close_strlen' => strlen($close),
                                );
                            }
                            
                            if ($matches[$dk][$open]['next_match'] < $i) {
                                
                                $open_pos = strpos($code, $open, $i);
                                if ($open_pos === false) {
                                    
                                    unset($delim_copy[$dk][$open]);
                                    continue;
                                }
                                $matches[$dk][$open]['next_match'] = $open_pos;
                            }
                            if ($matches[$dk][$open]['next_match'] < $next_match_pos) {
                                
                                $matches[$dk][$open]['close_pos'] =
                                    strpos($code, $close, $matches[$dk][$open]['next_match']+1);

                                $next_match_pointer =& $matches[$dk][$open];
                                $next_match_pos = $matches[$dk][$open]['next_match'];
                            }
                        }
                    } else {
                        
                        





                        if(!GESHI_PHP_PRE_433 && 
                            preg_match($delimiters, $code, $matches_rx, PREG_OFFSET_CAPTURE, $i)) {
                            
                            $matches[$dk] = array(
                                'next_match' => $matches_rx[1][1],
                                'dk' => $dk,

                                'close_strlen' => strlen($matches_rx[2][0]),
                                'close_pos' => $matches_rx[2][1],
                                );
                        } else {
                            
                            unset($delim_copy[$dk]);
                            continue;
                        }

                        if ($matches[$dk]['next_match'] <= $next_match_pos) {
                            $next_match_pointer =& $matches[$dk];
                            $next_match_pos = $matches[$dk]['next_match'];
                        }
                    }
                }
                
                $parts[$k] = array(
                    1 => substr($code, $i, $next_match_pos - $i)
                );
                ++$k;

                if ($next_match_pos > $length) {
                    
                    break;
                }

                
                $parts[$k][0] = $next_match_pointer['dk'];

                
                if(is_array($delim_copy[$next_match_pointer['dk']])) {
                    
                    $i = $next_match_pos + $next_match_pointer['open_strlen'];
                    while (true) {
                        $close_pos = strpos($code, $next_match_pointer['close'], $i);
                        if ($close_pos == false) {
                            break;
                        }
                        $i = $close_pos + $next_match_pointer['close_strlen'];
                        if ($i == $length) {
                            break;
                        }
                        if ($code[$i] == $next_match_pointer['open'][0] && ($next_match_pointer['open_strlen'] == 1 ||
                            substr($code, $i, $next_match_pointer['open_strlen']) == $next_match_pointer['open'])) {
                            
                            foreach ($matches as $submatches) {
                                foreach ($submatches as $match) {
                                    if ($match['next_match'] == $i) {
                                        
                                        break 3;
                                    }
                                }
                            }
                        } else {
                            break;
                        }
                    }
                } else {
                    $close_pos = $next_match_pointer['close_pos'] + $next_match_pointer['close_strlen'];
                    $i = $close_pos;
                }

                if ($close_pos === false) {
                    
                    $parts[$k][1] = substr($code, $next_match_pos);
                    ++$k;
                    break;
                } else {
                    $parts[$k][1] = substr($code, $next_match_pos, $i - $next_match_pos);
                    ++$k;
                }
            }
            unset($delim_copy, $next_match_pointer, $next_match_pos, $matches);
            $num_parts = $k;

            if ($num_parts == 1 && $this->strict_mode == GESHI_MAYBE) {
                
                
                $parts = array(
                    0 => array(
                        0 => '',
                        1 => ''
                    ),
                    1 => array(
                        0 => null,
                        1 => $parts[0][1]
                    )
                );
                $num_parts = 2;
            }

        } else {
            
            
            $parts = array(
                0 => array(
                    0 => '',
                    1 => ''
                ),
                1 => array(
                    0 => null,
                    1 => $code
                )
            );
            $num_parts = 2;
        }

        
        unset($code);

        
        $hq = isset($this->language_data['HARDQUOTE']) ? $this->language_data['HARDQUOTE'][0] : false;
        $hq_strlen = strlen($hq);

        
        
        $check_linenumbers = $this->line_numbers != GESHI_NO_LINE_NUMBERS ||
            !empty($this->highlight_extra_lines) || !$this->allow_multiline_span;

        
        $escaped_escape_char = $this->hsc($this->language_data['ESCAPE_CHAR']);

        
        $sc_disallowed_before = "";
        $sc_disallowed_after = "";

        if (isset($this->language_data['PARSER_CONTROL'])) {
            if (isset($this->language_data['PARSER_CONTROL']['COMMENTS'])) {
                if (isset($this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_BEFORE'])) {
                    $sc_disallowed_before = $this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_BEFORE'];
                }
                if (isset($this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_AFTER'])) {
                    $sc_disallowed_after = $this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_AFTER'];
                }
            }
        }

        
        $is_string_starter = array();
        if ($this->lexic_permissions['STRINGS']) {
            foreach ($this->language_data['QUOTEMARKS'] as $quotemark) {
                if (!isset($is_string_starter[$quotemark[0]])) {
                    $is_string_starter[$quotemark[0]] = (string)$quotemark;
                } else if (is_string($is_string_starter[$quotemark[0]])) {
                    $is_string_starter[$quotemark[0]] = array(
                        $is_string_starter[$quotemark[0]],
                        $quotemark);
                } else {
                    $is_string_starter[$quotemark[0]][] = $quotemark;
                }
            }
        }

        
        
        
        for ($key = 0; $key < $num_parts; ++$key) {
            $STRICTATTRS = '';

            
            if (!($key & 1)) {
                
                $endresult .= $this->hsc($parts[$key][1]);
                unset($parts[$key]);
                continue;
            }

            $result = '';
            $part = $parts[$key][1];

            $highlight_part = true;
            if ($this->strict_mode && !is_null($parts[$key][0])) {
                
                $script_key = $parts[$key][0];
                $highlight_part = $this->language_data['HIGHLIGHT_STRICT_BLOCK'][$script_key];
                if ($this->language_data['STYLES']['SCRIPT'][$script_key] != '' &&
                    $this->lexic_permissions['SCRIPT']) {
                    
                    
                    if (!$this->use_classes &&
                        $this->language_data['STYLES']['SCRIPT'][$script_key] != '') {
                        $attributes = ' style="' . $this->language_data['STYLES']['SCRIPT'][$script_key] . '"';
                    } else {
                        $attributes = ' class="sc' . $script_key . '"';
                    }
                    $result .= "<span$attributes>";
                    $STRICTATTRS = $attributes;
                }
            }

            if ($highlight_part) {
                
                
                

                
                $next_comment_regexp_key = '';
                $next_comment_regexp_pos = -1;
                $next_comment_multi_pos = -1;
                $next_comment_single_pos = -1;
                $comment_regexp_cache_per_key = array();
                $comment_multi_cache_per_key = array();
                $comment_single_cache_per_key = array();
                $next_open_comment_multi = '';
                $next_comment_single_key = '';
                $escape_regexp_cache_per_key = array();
                $next_escape_regexp_key = '';
                $next_escape_regexp_pos = -1;

                $length = strlen($part);
                for ($i = 0; $i < $length; ++$i) {
                    
                    $char = $part[$i];
                    $char_len = 1;

                    
                    if (isset($this->language_data['COMMENT_REGEXP']) && $next_comment_regexp_pos < $i) {
                        $next_comment_regexp_pos = $length;
                        foreach ($this->language_data['COMMENT_REGEXP'] as $comment_key => $regexp) {
                            $match_i = false;
                            if (isset($comment_regexp_cache_per_key[$comment_key]) &&
                                ($comment_regexp_cache_per_key[$comment_key]['pos'] >= $i ||
                                 $comment_regexp_cache_per_key[$comment_key]['pos'] === false)) {
                                
                                if ($comment_regexp_cache_per_key[$comment_key]['pos'] === false) {
                                    
                                    continue;
                                }
                                $match_i = $comment_regexp_cache_per_key[$comment_key]['pos'];
                            } else if (
                                
                                (GESHI_PHP_PRE_433 && preg_match($regexp, substr($part, $i), $match, PREG_OFFSET_CAPTURE)) ||
                                (!GESHI_PHP_PRE_433 && preg_match($regexp, $part, $match, PREG_OFFSET_CAPTURE, $i))
                                ) {
                                $match_i = $match[0][1];
                                if (GESHI_PHP_PRE_433) {
                                    $match_i += $i;
                                }

                                $comment_regexp_cache_per_key[$comment_key] = array(
                                    'key' => $comment_key,
                                    'length' => strlen($match[0][0]),
                                    'pos' => $match_i
                                );
                            } else {
                                $comment_regexp_cache_per_key[$comment_key]['pos'] = false;
                                continue;
                            }

                            if ($match_i !== false && $match_i < $next_comment_regexp_pos) {
                                $next_comment_regexp_pos = $match_i;
                                $next_comment_regexp_key = $comment_key;
                                if ($match_i === $i) {
                                    break;
                                }
                            }
                        }
                    }

                    $string_started = false;

                    if (isset($is_string_starter[$char])) {
                        

                        
                        
                        if (is_array($is_string_starter[$char])) {
                            $char_new = '';
                            foreach ($is_string_starter[$char] as $testchar) {
                                if ($testchar === substr($part, $i, strlen($testchar)) &&
                                    strlen($testchar) > strlen($char_new)) {
                                    $char_new = $testchar;
                                    $string_started = true;
                                }
                            }
                            if ($string_started) {
                                $char = $char_new;
                            }
                        } else {
                            $testchar = $is_string_starter[$char];
                            if ($testchar === substr($part, $i, strlen($testchar))) {
                                $char = $testchar;
                                $string_started = true;
                            }
                        }
                        $char_len = strlen($char);
                    }

                    if ($string_started && $i != $next_comment_regexp_pos) {
                        
                        $string_key = array_search($char, $this->language_data['QUOTEMARKS']);
                        if (!isset($this->language_data['STYLES']['STRINGS'][$string_key]) ||
                            !isset($this->language_data['STYLES']['ESCAPE_CHAR'][$string_key])) {
                            $string_key = 0;
                        }

                        
                        $result .= $this->parse_non_string_part($stuff_to_parse);
                        $stuff_to_parse = '';

                        if (!$this->use_classes) {
                            $string_attributes = ' style="' . $this->language_data['STYLES']['STRINGS'][$string_key] . '"';
                        } else {
                            $string_attributes = ' class="st'.$string_key.'"';
                        }

                        
                        $string = "<span$string_attributes>" . GeSHi::hsc($char);
                        $start = $i + $char_len;
                        $string_open = true;

                        if(empty($this->language_data['ESCAPE_REGEXP'])) {
                            $next_escape_regexp_pos = $length;
                        }

                        do {
                            
                            $close_pos = strpos($part, $char, $start);
                            if(false === $close_pos) {
                                $close_pos = $length;
                            }

                            if($this->lexic_permissions['ESCAPE_CHAR']) {
                                
                                if (isset($this->language_data['ESCAPE_REGEXP']) && $next_escape_regexp_pos < $start) {
                                    $next_escape_regexp_pos = $length;
                                    foreach ($this->language_data['ESCAPE_REGEXP'] as $escape_key => $regexp) {
                                        $match_i = false;
                                        if (isset($escape_regexp_cache_per_key[$escape_key]) &&
                                            ($escape_regexp_cache_per_key[$escape_key]['pos'] >= $start ||
                                             $escape_regexp_cache_per_key[$escape_key]['pos'] === false)) {
                                            
                                            if ($escape_regexp_cache_per_key[$escape_key]['pos'] === false) {
                                                
                                                continue;
                                            }
                                            $match_i = $escape_regexp_cache_per_key[$escape_key]['pos'];
                                        } else if (
                                            
                                            (GESHI_PHP_PRE_433 && preg_match($regexp, substr($part, $start), $match, PREG_OFFSET_CAPTURE)) ||
                                            (!GESHI_PHP_PRE_433 && preg_match($regexp, $part, $match, PREG_OFFSET_CAPTURE, $start))
                                            ) {
                                            $match_i = $match[0][1];
                                            if (GESHI_PHP_PRE_433) {
                                                $match_i += $start;
                                            }

                                            $escape_regexp_cache_per_key[$escape_key] = array(
                                                'key' => $escape_key,
                                                'length' => strlen($match[0][0]),
                                                'pos' => $match_i
                                            );
                                        } else {
                                            $escape_regexp_cache_per_key[$escape_key]['pos'] = false;
                                            continue;
                                        }

                                        if ($match_i !== false && $match_i < $next_escape_regexp_pos) {
                                            $next_escape_regexp_pos = $match_i;
                                            $next_escape_regexp_key = $escape_key;
                                            if ($match_i === $start) {
                                                break;
                                            }
                                        }
                                    }
                                }

                                
                                if('' != $this->language_data['ESCAPE_CHAR']) {
                                    $simple_escape = strpos($part, $this->language_data['ESCAPE_CHAR'], $start);
                                    if(false === $simple_escape) {
                                        $simple_escape = $length;
                                    }
                                } else {
                                    $simple_escape = $length;
                                }
                            } else {
                                $next_escape_regexp_pos = $length;
                                $simple_escape = $length;
                            }

                            if($simple_escape < $next_escape_regexp_pos &&
                                $simple_escape < $length &&
                                $simple_escape < $close_pos) {
                                
                                $es_pos = $simple_escape;

                                
                                $string .= $this->hsc(substr($part, $start, $es_pos - $start));

                                
                                if (!$this->use_classes) {
                                    $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR'][0] . '"';
                                } else {
                                    $escape_char_attributes = ' class="es0"';
                                }

                                
                                $string .= "<span$escape_char_attributes>" .
                                    GeSHi::hsc($this->language_data['ESCAPE_CHAR']);

                                
                                $es_char = $part[$es_pos + 1];
                                if ($es_char == "\n") {
                                    
                                    $string .= "</span>\n";
                                    $start = $es_pos + 2;
                                } else if (ord($es_char) >= 128) {
                                    
                                    
                                    if(function_exists('mb_substr')) {
                                        $es_char_m = mb_substr(substr($part, $es_pos+1, 16), 0, 1, $this->encoding);
                                        $string .= $es_char_m . '</span>';
                                    } else if (!GESHI_PHP_PRE_433 && 'utf-8' == $this->encoding) {
                                        if(preg_match("/[\xC2-\xDF][\x80-\xBF]".
                                            "|\xE0[\xA0-\xBF][\x80-\xBF]".
                                            "|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}".
                                            "|\xED[\x80-\x9F][\x80-\xBF]".
                                            "|\xF0[\x90-\xBF][\x80-\xBF]{2}".
                                            "|[\xF1-\xF3][\x80-\xBF]{3}".
                                            "|\xF4[\x80-\x8F][\x80-\xBF]{2}/s",
                                            $part, $es_char_m, null, $es_pos + 1)) {
                                            $es_char_m = $es_char_m[0];
                                        } else {
                                            $es_char_m = $es_char;
                                        }
                                        $string .= $this->hsc($es_char_m) . '</span>';
                                    } else {
                                        $es_char_m = $this->hsc($es_char);
                                    }
                                    $start = $es_pos + strlen($es_char_m) + 1;
                                } else {
                                    $string .= $this->hsc($es_char) . '</span>';
                                    $start = $es_pos + 2;
                                }
                            } else if ($next_escape_regexp_pos < $length &&
                                $next_escape_regexp_pos < $close_pos) {
                                $es_pos = $next_escape_regexp_pos;
                                
                                $string .= $this->hsc(substr($part, $start, $es_pos - $start));

                                
                                $escape = $escape_regexp_cache_per_key[$next_escape_regexp_key];
                                $escape_str = substr($part, $es_pos, $escape['length']);
                                $escape_key = $escape['key'];

                                
                                if (!$this->use_classes) {
                                    $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR'][$escape_key] . '"';
                                } else {
                                    $escape_char_attributes = ' class="es' . $escape_key . '"';
                                }

                                
                                $string .= "<span$escape_char_attributes>" .
                                    $this->hsc($escape_str) . '</span>';

                                $start = $es_pos + $escape['length'];
                            } else {
                                
                                $string .= $this->hsc(substr($part, $start, $close_pos - $start + $char_len)) . '</span>';
                                $start = $close_pos + $char_len;
                                $string_open = false;
                            }
                        } while($string_open);

                        if ($check_linenumbers) {
                            
                            
                            
                            
                            
                            $string = str_replace("\n", "</span>\n<span$string_attributes>", $string);
                        }

                        $result .= $string;
                        $string = '';
                        $i = $start - 1;
                        continue;
                    } else if ($this->lexic_permissions['STRINGS'] && $hq && $hq[0] == $char &&
                        substr($part, $i, $hq_strlen) == $hq) {
                        
                        if (!$this->use_classes) {
                            $string_attributes = ' style="' . $this->language_data['STYLES']['STRINGS']['HARD'] . '"';
                            $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR']['HARD'] . '"';
                        } else {
                            $string_attributes = ' class="st_h"';
                            $escape_char_attributes = ' class="es_h"';
                        }
                        
                        $result .= $this->parse_non_string_part($stuff_to_parse);
                        $stuff_to_parse = '';

                        
                        $string = '';

                        
                        $start = $i + $hq_strlen;
                        while ($close_pos = strpos($part, $this->language_data['HARDQUOTE'][1], $start)) {
                            $start = $close_pos + 1;
                            if ($this->lexic_permissions['ESCAPE_CHAR'] && $part[$close_pos - 1] == $this->language_data['HARDCHAR']) {
                                
                                foreach ($this->language_data['HARDESCAPE'] as $hardescape) {
                                    if (substr($part, $close_pos - 1, strlen($hardescape)) == $hardescape) {
                                        
                                        $escape_char_pos = $close_pos - 1;
                                        while ($escape_char_pos > 0
                                                && $part[$escape_char_pos - 1] == $this->language_data['HARDCHAR']) {
                                            --$escape_char_pos;
                                        }
                                        if (($close_pos - $escape_char_pos) & 1) {
                                            
                                            continue 2;
                                        }
                                    }
                                }
                            }

                            
                            break;
                        }

                        
                        if (!$close_pos) {
                            
                            $close_pos = $length;
                        }

                        
                        $string = substr($part, $i, $close_pos - $i + 1);
                        $i = $close_pos;

                        
                        
                        if ($this->lexic_permissions['ESCAPE_CHAR'] && $this->language_data['ESCAPE_CHAR']) {
                            $start = 0;
                            $new_string = '';
                            while ($es_pos = strpos($string, $this->language_data['ESCAPE_CHAR'], $start)) {
                                
                                $new_string .= $this->hsc(substr($string, $start, $es_pos - $start));
                                
                                foreach ($this->language_data['HARDESCAPE'] as $hardescape) {
                                    if (substr($string, $es_pos, strlen($hardescape)) == $hardescape) {
                                        
                                        $new_string .= "<span$escape_char_attributes>" .
                                            $this->hsc($hardescape) . '</span>';
                                        $start = $es_pos + strlen($hardescape);
                                        continue 2;
                                    }
                                }
                                
                                
                                $c = 0;
                                while (isset($string[$es_pos + $c]) && isset($string[$es_pos + $c + 1])
                                    && $string[$es_pos + $c] == $this->language_data['ESCAPE_CHAR']
                                    && $string[$es_pos + $c + 1] == $this->language_data['ESCAPE_CHAR']) {
                                    $c += 2;
                                }
                                if ($c) {
                                    $new_string .= "<span$escape_char_attributes>" .
                                        str_repeat($escaped_escape_char, $c) .
                                        '</span>';
                                    $start = $es_pos + $c;
                                } else {
                                    
                                    $new_string .= $escaped_escape_char;
                                    $start = $es_pos + 1;
                                }
                            }
                            $string = $new_string . $this->hsc(substr($string, $start));
                        } else {
                            $string = $this->hsc($string);
                        }

                        if ($check_linenumbers) {
                            
                            
                            
                            
                            
                            $string = str_replace("\n", "</span>\n<span$string_attributes>", $string);
                        }

                        $result .= "<span$string_attributes>" . $string . '</span>';
                        $string = '';
                        continue;
                    } else {
                        
                        if ($i == $next_comment_regexp_pos) {
                            $COMMENT_MATCHED = true;
                            $comment = $comment_regexp_cache_per_key[$next_comment_regexp_key];
                            $test_str = $this->hsc(substr($part, $i, $comment['length']));

                            
                            if ($this->lexic_permissions['COMMENTS']['MULTI']) {
                                if (!$this->use_classes) {
                                    $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS'][$comment['key']] . '"';
                                } else {
                                    $attributes = ' class="co' . $comment['key'] . '"';
                                }

                                $test_str = "<span$attributes>" . $test_str . "</span>";

                                
                                if ($check_linenumbers) {
                                    
                                    $test_str = str_replace(
                                        "\n", "</span>\n<span$attributes>",
                                        str_replace("\n ", "\n&nbsp;", $test_str)
                                    );
                                }
                            }

                            $i += $comment['length'] - 1;

                            
                            $result .= $this->parse_non_string_part($stuff_to_parse);
                            $stuff_to_parse = '';
                        }

                        
                        if (!$COMMENT_MATCHED) {
                            
                            if (!empty($this->language_data['COMMENT_MULTI']) && $next_comment_multi_pos < $i) {
                                $next_comment_multi_pos = $length;
                                foreach ($this->language_data['COMMENT_MULTI'] as $open => $close) {
                                    $match_i = false;
                                    if (isset($comment_multi_cache_per_key[$open]) &&
                                        ($comment_multi_cache_per_key[$open] >= $i ||
                                         $comment_multi_cache_per_key[$open] === false)) {
                                        
                                        if ($comment_multi_cache_per_key[$open] === false) {
                                            
                                            continue;
                                        }
                                        $match_i = $comment_multi_cache_per_key[$open];
                                    } else if (($match_i = stripos($part, $open, $i)) !== false) {
                                        $comment_multi_cache_per_key[$open] = $match_i;
                                    } else {
                                        $comment_multi_cache_per_key[$open] = false;
                                        continue;
                                    }
                                    if ($match_i !== false && $match_i < $next_comment_multi_pos) {
                                        $next_comment_multi_pos = $match_i;
                                        $next_open_comment_multi = $open;
                                        if ($match_i === $i) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($i == $next_comment_multi_pos) {
                                $open = $next_open_comment_multi;
                                $close = $this->language_data['COMMENT_MULTI'][$open];
                                $open_strlen = strlen($open);
                                $close_strlen = strlen($close);
                                $COMMENT_MATCHED = true;
                                $test_str_match = $open;
                                
                                if ($this->lexic_permissions['COMMENTS']['MULTI'] ||
                                    $open == GESHI_START_IMPORTANT) {
                                    if ($open != GESHI_START_IMPORTANT) {
                                        if (!$this->use_classes) {
                                            $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS']['MULTI'] . '"';
                                        } else {
                                            $attributes = ' class="coMULTI"';
                                        }
                                        $test_str = "<span$attributes>" . $this->hsc($open);
                                    } else {
                                        if (!$this->use_classes) {
                                            $attributes = ' style="' . $this->important_styles . '"';
                                        } else {
                                            $attributes = ' class="imp"';
                                        }

                                        
                                        
                                        $test_str = "<span$attributes>";
                                    }
                                } else {
                                    $test_str = $this->hsc($open);
                                }

                                $close_pos = strpos( $part, $close, $i + $open_strlen );

                                if ($close_pos === false) {
                                    $close_pos = $length;
                                }

                                
                                $rest_of_comment = $this->hsc(substr($part, $i + $open_strlen, $close_pos - $i - $open_strlen + $close_strlen));
                                if (($this->lexic_permissions['COMMENTS']['MULTI'] ||
                                    $test_str_match == GESHI_START_IMPORTANT) &&
                                    $check_linenumbers) {

                                    
                                    $test_str .= str_replace(
                                        "\n", "</span>\n<span$attributes>",
                                        str_replace("\n ", "\n&nbsp;", $rest_of_comment)
                                    );
                                } else {
                                    $test_str .= $rest_of_comment;
                                }

                                if ($this->lexic_permissions['COMMENTS']['MULTI'] ||
                                    $test_str_match == GESHI_START_IMPORTANT) {
                                    $test_str .= '</span>';
                                }

                                $i = $close_pos + $close_strlen - 1;

                                
                                $result .= $this->parse_non_string_part($stuff_to_parse);
                                $stuff_to_parse = '';
                            }
                        }

                        
                        if (!$COMMENT_MATCHED) {
                            
                            if (!empty($this->language_data['COMMENT_SINGLE']) && $next_comment_single_pos < $i) {
                                $next_comment_single_pos = $length;
                                foreach ($this->language_data['COMMENT_SINGLE'] as $comment_key => $comment_mark) {
                                    $match_i = false;
                                    if (isset($comment_single_cache_per_key[$comment_key]) &&
                                        ($comment_single_cache_per_key[$comment_key] >= $i ||
                                         $comment_single_cache_per_key[$comment_key] === false)) {
                                        
                                        if ($comment_single_cache_per_key[$comment_key] === false) {
                                            
                                            continue;
                                        }
                                        $match_i = $comment_single_cache_per_key[$comment_key];
                                    } else if (
                                        
                                        ($this->language_data['CASE_SENSITIVE'][GESHI_COMMENTS] &&
                                        ($match_i = stripos($part, $comment_mark, $i)) !== false) ||
                                        
                                        (!$this->language_data['CASE_SENSITIVE'][GESHI_COMMENTS] &&
                                          (($match_i = strpos($part, $comment_mark, $i)) !== false))) {
                                        $comment_single_cache_per_key[$comment_key] = $match_i;
                                    } else {
                                        $comment_single_cache_per_key[$comment_key] = false;
                                        continue;
                                    }
                                    if ($match_i !== false && $match_i < $next_comment_single_pos) {
                                        $next_comment_single_pos = $match_i;
                                        $next_comment_single_key = $comment_key;
                                        if ($match_i === $i) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($next_comment_single_pos == $i) {
                                $comment_key = $next_comment_single_key;
                                $comment_mark = $this->language_data['COMMENT_SINGLE'][$comment_key];
                                $com_len = strlen($comment_mark);

                                
                                
                                if ((empty($sc_disallowed_before) || ($i == 0) ||
                                    (false === strpos($sc_disallowed_before, $part[$i-1]))) &&
                                    (empty($sc_disallowed_after) || ($length <= $i + $com_len) ||
                                    (false === strpos($sc_disallowed_after, $part[$i + $com_len]))))
                                {
                                    
                                    $COMMENT_MATCHED = true;
                                    if ($this->lexic_permissions['COMMENTS'][$comment_key]) {
                                        if (!$this->use_classes) {
                                            $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS'][$comment_key] . '"';
                                        } else {
                                            $attributes = ' class="co' . $comment_key . '"';
                                        }
                                        $test_str = "<span$attributes>" . $this->hsc($this->change_case($comment_mark));
                                    } else {
                                        $test_str = $this->hsc($comment_mark);
                                    }

                                    
                                    $close_pos = strpos($part, "\n", $i);
                                    $oops = false;
                                    if ($close_pos === false) {
                                        $close_pos = $length;
                                        $oops = true;
                                    }
                                    $test_str .= $this->hsc(substr($part, $i + $com_len, $close_pos - $i - $com_len));
                                    if ($this->lexic_permissions['COMMENTS'][$comment_key]) {
                                        $test_str .= "</span>";
                                    }

                                    
                                    if (!$oops) {
                                      $test_str .= "\n";
                                    }

                                    $i = $close_pos;

                                    
                                    $result .= $this->parse_non_string_part($stuff_to_parse);
                                    $stuff_to_parse = '';
                                }
                            }
                        }
                    }

                    
                    if (!$COMMENT_MATCHED) {
                        $stuff_to_parse .= $char;
                    } else {
                        $result .= $test_str;
                        unset($test_str);
                        $COMMENT_MATCHED = false;
                    }
                }
                
                $result .= $this->parse_non_string_part($stuff_to_parse);
                $stuff_to_parse = '';
            } else {
                $result .= $this->hsc($part);
            }
            
            if ($STRICTATTRS != '') {
                $result = str_replace("\n", "</span>\n<span$STRICTATTRS>", $result);
                $result .= '</span>';
            }

            $endresult .= $result;
            unset($part, $parts[$key], $result);
        }

        
        
        
        $endresult = str_replace(array('<SEMI>', '<PIPE>'), array(';', '|'), $endresult);




        


        
        $this->set_time($start_time, microtime());

        $this->finalise($endresult);
        return $endresult;
    }

    







    function indent(&$result) {
        
        if (false !== strpos($result, "\t")) {
            $lines = explode("\n", $result);
            $result = null;
            $tab_width = $this->get_real_tab_width();
            $tab_string = '&nbsp;' . str_repeat(' ', $tab_width);

            for ($key = 0, $n = count($lines); $key < $n; $key++) {
                $line = $lines[$key];
                if (false === strpos($line, "\t")) {
                    continue;
                }

                $pos = 0;
                $length = strlen($line);
                $lines[$key] = ''; 

                $IN_TAG = false;
                for ($i = 0; $i < $length; ++$i) {
                    $char = $line[$i];
                    
                    
                    
                    
                    
                    
                    
                    if ($IN_TAG) {
                        if ('>' == $char) {
                            $IN_TAG = false;
                        }
                        $lines[$key] .= $char;
                    } else if ('<' == $char) {
                        $IN_TAG = true;
                        $lines[$key] .= '<';
                    } else if ('&' == $char) {
                        $substr = substr($line, $i + 3, 5);
                        $posi = strpos($substr, ';');
                        if (false === $posi) {
                            ++$pos;
                        } else {
                            $pos -= $posi+2;
                        }
                        $lines[$key] .= $char;
                    } else if ("\t" == $char) {
                        $str = '';
                        
                        
                        
                        
                        
                        
                        $tab_end_width = $tab_width - ($pos % $tab_width); 
                        if (($pos & 1) || 1 == $tab_end_width) {
                            $str .= substr($tab_string, 6, $tab_end_width);
                        } else {
                            $str .= substr($tab_string, 0, $tab_end_width+5);
                        }
                        $lines[$key] .= $str;
                        $pos += $tab_end_width;

                        if (false === strpos($line, "\t", $i + 1)) {
                            $lines[$key] .= substr($line, $i + 1);
                            break;
                        }
                    } else if (0 == $pos && ' ' == $char) {
                        $lines[$key] .= '&nbsp;';
                        ++$pos;
                    } else {
                        $lines[$key] .= $char;
                        ++$pos;
                    }
                }
            }
            $result = implode("\n", $lines);
            unset($lines);
        }
        
        
        $result = preg_replace('/^ /m', '&nbsp;', $result);
        $result = str_replace('  ', ' &nbsp;', $result);

        if ($this->line_numbers == GESHI_NO_LINE_NUMBERS) {
            if ($this->line_ending === null) {
                $result = nl2br($result);
            } else {
                $result = str_replace("\n", $this->line_ending, $result);
            }
        }
    }

    







    function change_case($instr) {
        switch ($this->language_data['CASE_KEYWORDS']) {
            case GESHI_CAPS_UPPER:
                return strtoupper($instr);
            case GESHI_CAPS_LOWER:
                return strtolower($instr);
            default:
                return $instr;
        }
    }

    









    function handle_keyword_replace($match) {
        $k = $this->_kw_replace_group;
        $keyword = $match[0];

        $before = '';
        $after = '';

        if ($this->keyword_links) {
            

            if (isset($this->language_data['URLS'][$k]) &&
                $this->language_data['URLS'][$k] != '') {
                

                
                
                
                if (!$this->language_data['CASE_SENSITIVE'][$k] &&
                    strpos($this->language_data['URLS'][$k], '{FNAME}') !== false) {
                    foreach ($this->language_data['KEYWORDS'][$k] as $word) {
                        if (strcasecmp($word, $keyword) == 0) {
                            break;
                        }
                    }
                } else {
                    $word = $keyword;
                }

                $before = '<|UR1|"' .
                    str_replace(
                        array(
                            '{FNAME}',
                            '{FNAMEL}',
                            '{FNAMEU}',
                            '.'),
                        array(
                            str_replace('+', '%20', urlencode($this->hsc($word))),
                            str_replace('+', '%20', urlencode($this->hsc(strtolower($word)))),
                            str_replace('+', '%20', urlencode($this->hsc(strtoupper($word)))),
                            '<DOT>'),
                        $this->language_data['URLS'][$k]
                    ) . '">';
                $after = '</a>';
            }
        }

        return $before . '<|/'. $k .'/>' . $this->change_case($keyword) . '|>' . $after;
    }

    









    function handle_regexps_callback($matches) {
        
        return  ' style="' . call_user_func($this->language_data['STYLES']['REGEXPS'][$this->_rx_key], $matches[1]) . '"'. $matches[1] . '|>';
    }

    









    function handle_multiline_regexps($matches) {
        $before = $this->_hmr_before;
        $after = $this->_hmr_after;
        if ($this->_hmr_replace) {
            $replace = $this->_hmr_replace;
            $search = array();

            foreach (array_keys($matches) as $k) {
                $search[] = '\\' . $k;
            }

            $before = str_replace($search, $matches, $before);
            $after = str_replace($search, $matches, $after);
            $replace = str_replace($search, $matches, $replace);
        } else {
            $replace = $matches[0];
        }
        return $before
                    . '<|!REG3XP' . $this->_hmr_key .'!>'
                        . str_replace("\n", "|>\n<|!REG3XP" . $this->_hmr_key . '!>', $replace)
                    . '|>'
              . $after;
    }

    








    function parse_non_string_part($stuff_to_parse) {
        $stuff_to_parse = ' ' . $this->hsc($stuff_to_parse);

        
        foreach ($this->language_data['REGEXPS'] as $key => $regexp) {
            if ($this->lexic_permissions['REGEXPS'][$key]) {
                if (is_array($regexp)) {
                    if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                        
                        $this->_hmr_replace = $regexp[GESHI_REPLACE];
                        $this->_hmr_before = $regexp[GESHI_BEFORE];
                        $this->_hmr_key = $key;
                        $this->_hmr_after = $regexp[GESHI_AFTER];
                        $stuff_to_parse = preg_replace_callback(
                            "/" . $regexp[GESHI_SEARCH] . "/{$regexp[GESHI_MODIFIERS]}",
                            array($this, 'handle_multiline_regexps'),
                            $stuff_to_parse);
                        $this->_hmr_replace = false;
                        $this->_hmr_before = '';
                        $this->_hmr_after = '';
                    } else {
                        $stuff_to_parse = preg_replace(
                            '/' . $regexp[GESHI_SEARCH] . '/' . $regexp[GESHI_MODIFIERS],
                            $regexp[GESHI_BEFORE] . '<|!REG3XP'. $key .'!>' . $regexp[GESHI_REPLACE] . '|>' . $regexp[GESHI_AFTER],
                            $stuff_to_parse);
                    }
                } else {
                    if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                        
                        $this->_hmr_key = $key;
                        $stuff_to_parse = preg_replace_callback( "/(" . $regexp . ")/",
                                              array($this, 'handle_multiline_regexps'), $stuff_to_parse);
                        $this->_hmr_key = '';
                    } else {
                        $stuff_to_parse = preg_replace( "/(" . $regexp . ")/", "<|!REG3XP$key!>\\1|>", $stuff_to_parse);
                    }
                }
            }
        }

        
        $numbers_found = false;
        if ($this->lexic_permissions['NUMBERS'] && preg_match('#\d#', $stuff_to_parse )) {
            $numbers_found = true;

            
            foreach($this->language_data['NUMBERS_RXCACHE'] as $id => $regexp) {
                
                $stuff_to_parse = preg_replace($regexp, "<|/NUM!$id/>\\1|>", $stuff_to_parse);
            }
        }

        
        $disallowed_before = "(?<![a-zA-Z0-9\$_\|\#;>|^&";
        $disallowed_after = "(?![a-zA-Z0-9_\|%\\-&;";
        if ($this->lexic_permissions['STRINGS']) {
            $quotemarks = preg_quote(implode($this->language_data['QUOTEMARKS']), '/');
            $disallowed_before .= $quotemarks;
            $disallowed_after .= $quotemarks;
        }
        $disallowed_before .= "])";
        $disallowed_after .= "])";

        $parser_control_pergroup = false;
        if (isset($this->language_data['PARSER_CONTROL'])) {
            if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'])) {
                $x = 0; 
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_BEFORE'])) {
                    $disallowed_before = $this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_BEFORE'];
                    ++$x;
                }
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_AFTER'])) {
                    $disallowed_after = $this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_AFTER'];
                    ++$x;
                }
                $parser_control_pergroup = (count($this->language_data['PARSER_CONTROL']['KEYWORDS']) - $x) > 0;
            }
        }

        







        foreach (array_keys($this->language_data['KEYWORDS']) as $k) {
            if (!isset($this->lexic_permissions['KEYWORDS'][$k]) ||
                $this->lexic_permissions['KEYWORDS'][$k]) {

                $case_sensitive = $this->language_data['CASE_SENSITIVE'][$k];
                $modifiers = $case_sensitive ? '' : 'i';

                
                $disallowed_before_local = $disallowed_before;
                $disallowed_after_local = $disallowed_after;
                if ($parser_control_pergroup && isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k])) {
                    if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_BEFORE'])) {
                        $disallowed_before_local =
                            $this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_BEFORE'];
                    }

                    if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_AFTER'])) {
                        $disallowed_after_local =
                            $this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_AFTER'];
                    }
                }

                $this->_kw_replace_group = $k;

                
                
                for ($set = 0, $set_length = count($this->language_data['CACHED_KEYWORD_LISTS'][$k]); $set <  $set_length; ++$set) {
                    $keywordset =& $this->language_data['CACHED_KEYWORD_LISTS'][$k][$set];
                    
                    
                    
                    $stuff_to_parse = preg_replace_callback(
                        "/$disallowed_before_local({$keywordset})(?!\<DOT\>(?:htm|php))$disallowed_after_local/$modifiers",
                        array($this, 'handle_keyword_replace'),
                        $stuff_to_parse
                        );
                }
            }
        }

        
        
        
        foreach (array_keys($this->language_data['KEYWORDS']) as $k) {
            if (!$this->use_classes) {
                $attributes = ' style="' .
                    (isset($this->language_data['STYLES']['KEYWORDS'][$k]) ?
                    $this->language_data['STYLES']['KEYWORDS'][$k] : "") . '"';
            } else {
                $attributes = ' class="kw' . $k . '"';
            }
            $stuff_to_parse = str_replace("<|/$k/>", "<|$attributes>", $stuff_to_parse);
        }

        if ($numbers_found) {
            
            foreach($this->language_data['NUMBERS_RXCACHE'] as $id => $regexp) {


                    
                        
                    if (!$this->use_classes) {
                        $attributes = ' style="' . $this->language_data['STYLES']['NUMBERS'][$id] . '"';
                    } else {
                        $attributes = ' class="nu'.$id.'"';
                    }

                    
                    $stuff_to_parse = str_replace("/NUM!$id/", $attributes, $stuff_to_parse);

            }
        }

        
        if ($this->lexic_permissions['METHODS'] && $this->language_data['OOLANG']) {
            $oolang_spaces = "[\s]*";
            $oolang_before = "";
            $oolang_after = "[a-zA-Z][a-zA-Z0-9_]*";
            if (isset($this->language_data['PARSER_CONTROL'])) {
                if (isset($this->language_data['PARSER_CONTROL']['OOLANG'])) {
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_BEFORE'])) {
                        $oolang_before = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_BEFORE'];
                    }
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_AFTER'])) {
                        $oolang_after = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_AFTER'];
                    }
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_SPACES'])) {
                        $oolang_spaces = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_SPACES'];
                    }
                }
            }

            foreach ($this->language_data['OBJECT_SPLITTERS'] as $key => $splitter) {
                if (false !== strpos($stuff_to_parse, $splitter)) {
                    if (!$this->use_classes) {
                        $attributes = ' style="' . $this->language_data['STYLES']['METHODS'][$key] . '"';
                    } else {
                        $attributes = ' class="me' . $key . '"';
                    }
                    $stuff_to_parse = preg_replace("/($oolang_before)(" . preg_quote($this->language_data['OBJECT_SPLITTERS'][$key], '/') . ")($oolang_spaces)($oolang_after)/", "\\1\\2\\3<|$attributes>\\4|>", $stuff_to_parse);
                }
            }
        }

        
        
        
        
        
        
        if ($this->lexic_permissions['BRACKETS']) {
            $stuff_to_parse = str_replace( $this->language_data['CACHE_BRACKET_MATCH'],
                              $this->language_data['CACHE_BRACKET_REPLACE'], $stuff_to_parse );
        }


        
        if ($this->lexic_permissions['SYMBOLS'] && !empty($this->language_data['SYMBOLS'])) {
            
            $n_symbols = preg_match_all("/<\|(?:<DOT>|[^>])+>(?:(?!\|>).*?)\|>|<\/a>|(?:" . $this->language_data['SYMBOL_SEARCH'] . ")+/", $stuff_to_parse, $pot_symbols, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $global_offset = 0;
            for ($s_id = 0; $s_id < $n_symbols; ++$s_id) {
                $symbol_match = $pot_symbols[$s_id][0][0];
                if (strpos($symbol_match, '<') !== false || strpos($symbol_match, '>') !== false) {
                    
                    
                    
                    if(strpos($symbol_match, '<SEMI>') === false &&
                        strpos($symbol_match, '<PIPE>') === false) {
                        continue;
                    }
                }

                

                $symbol_length = strlen($symbol_match);
                $symbol_offset = $pot_symbols[$s_id][0][1];
                unset($pot_symbols[$s_id]);
                $symbol_end = $symbol_length + $symbol_offset;
                $symbol_hl = "";

                
                if ($this->language_data['MULTIPLE_SYMBOL_GROUPS']) {
                    $old_sym = -1;
                    
                    preg_match_all("/" . $this->language_data['SYMBOL_SEARCH'] . "/", $symbol_match, $sym_match_syms, PREG_PATTERN_ORDER);
                    foreach ($sym_match_syms[0] as $sym_ms) {
                        
                        if (isset($this->language_data['SYMBOL_DATA'][$sym_ms])
                            && ($this->language_data['SYMBOL_DATA'][$sym_ms] != $old_sym)) {
                            if (-1 != $old_sym) {
                                $symbol_hl .= "|>";
                            }
                            $old_sym = $this->language_data['SYMBOL_DATA'][$sym_ms];
                            if (!$this->use_classes) {
                                $symbol_hl .= '<| style="' . $this->language_data['STYLES']['SYMBOLS'][$old_sym] . '">';
                            } else {
                                $symbol_hl .= '<| class="sy' . $old_sym . '">';
                            }
                        }
                        $symbol_hl .= $sym_ms;
                    }
                    unset($sym_match_syms);

                    
                    
                    if (-1 != $old_sym) {
                        $symbol_hl .= "|>";
                    }
                } else {
                    if (!$this->use_classes) {
                        $symbol_hl = '<| style="' . $this->language_data['STYLES']['SYMBOLS'][0] . '">';
                    } else {
                        $symbol_hl = '<| class="sy0">';
                    }
                    $symbol_hl .= $symbol_match . '|>';
                }

                $stuff_to_parse = substr_replace($stuff_to_parse, $symbol_hl, $symbol_offset + $global_offset, $symbol_length);

                
                
                $global_offset += strlen($symbol_hl) - $symbol_length;
            }
        }
        

        
        foreach (array_keys($this->language_data['REGEXPS']) as $key) {
            if ($this->lexic_permissions['REGEXPS'][$key]) {
                if (is_callable($this->language_data['STYLES']['REGEXPS'][$key])) {
                    $this->_rx_key = $key;
                    $stuff_to_parse = preg_replace_callback("/!REG3XP$key!(.*)\|>/U",
                        array($this, 'handle_regexps_callback'),
                        $stuff_to_parse);
                } else {
                    if (!$this->use_classes) {
                        $attributes = ' style="' . $this->language_data['STYLES']['REGEXPS'][$key] . '"';
                    } else {
                        if (is_array($this->language_data['REGEXPS'][$key]) &&
                            array_key_exists(GESHI_CLASS, $this->language_data['REGEXPS'][$key])) {
                            $attributes = ' class="' .
                                $this->language_data['REGEXPS'][$key][GESHI_CLASS] . '"';
                        } else {
                           $attributes = ' class="re' . $key . '"';
                        }
                    }
                    $stuff_to_parse = str_replace("!REG3XP$key!", "$attributes", $stuff_to_parse);
                }
            }
        }

        
        $stuff_to_parse = str_replace('<DOT>', '.', $stuff_to_parse);
        
        if (isset($this->link_styles[GESHI_LINK])) {
            if ($this->use_classes) {
                $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' href=', $stuff_to_parse);
            } else {
                $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' style="' . $this->link_styles[GESHI_LINK] . '" href=', $stuff_to_parse);
            }
        } else {
            $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' href=', $stuff_to_parse);
        }

        
        
        

        $stuff_to_parse = str_replace('<|', '<span', $stuff_to_parse);
        $stuff_to_parse = str_replace ( '|>', '</span>', $stuff_to_parse );
        return substr($stuff_to_parse, 1);
    }

    







    function set_time($start_time, $end_time) {
        $start = explode(' ', $start_time);
        $end = explode(' ', $end_time);
        $this->time = $end[0] + $end[1] - $start[0] - $start[1];
    }

    





    function get_time() {
        return $this->time;
    }

    





    function merge_arrays() {
        $arrays = func_get_args();
        $narrays = count($arrays);

        
        
        for ($i = 0; $i < $narrays; $i ++) {
            if (!is_array($arrays[$i])) {
                
                trigger_error('Argument #' . ($i+1) . ' is not an array - trying to merge array with scalar! Returning false!', E_USER_WARNING);
                return false;
            }
        }

        
        $ret = $arrays[0];

        
        for ($i = 1; $i < $narrays; $i ++) {
            foreach ($arrays[$i] as $key => $value) {
                if (is_array($value) && isset($ret[$key])) {
                    
                    
                    $ret[$key] = $this->merge_arrays($ret[$key], $value);
                } else {
                    $ret[$key] = $value;
                }
            }
        }

        return $ret;
    }

    







    function load_language($file_name) {
        if ($file_name == $this->loaded_language) {
            
            return;
        }

        
        $this->loaded_language = $file_name;
        $this->parse_cache_built = false;
        $this->enable_highlighting();
        $language_data = array();

        
        require $file_name;

        
        
        $this->language_data = $language_data;

        
        $this->strict_mode = $this->language_data['STRICT_MODE_APPLIES'];

        
        
        foreach (array_keys($this->language_data['KEYWORDS']) as $key) {
            if (!empty($this->language_data['KEYWORDS'][$key])) {
                $this->lexic_permissions['KEYWORDS'][$key] = true;
            } else {
                $this->lexic_permissions['KEYWORDS'][$key] = false;
            }
        }

        foreach (array_keys($this->language_data['COMMENT_SINGLE']) as $key) {
            $this->lexic_permissions['COMMENTS'][$key] = true;
        }
        foreach (array_keys($this->language_data['REGEXPS']) as $key) {
            $this->lexic_permissions['REGEXPS'][$key] = true;
        }

        
        
        
        
        if (!empty($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS'])) {
            foreach ($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS'] as $flag => $value) {
                
                $perm = $value !== GESHI_NEVER;
                if ($flag == 'ALL') {
                    $this->enable_highlighting($perm);
                    continue;
                }
                if (!isset($this->lexic_permissions[$flag])) {
                    
                    continue;
                }
                if (is_array($this->lexic_permissions[$flag])) {
                    foreach ($this->lexic_permissions[$flag] as $key => $val) {
                        $this->lexic_permissions[$flag][$key] = $perm;
                    }
                } else {
                    $this->lexic_permissions[$flag] = $perm;
                }
            }
            unset($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS']);
        }

        
        
        if(!isset($this->language_data['HARDCHAR'])) {
            $this->language_data['HARDCHAR'] = $this->language_data['ESCAPE_CHAR'];
        }

        
        $style_filename = substr($file_name, 0, -4) . '.style.php';
        if (is_readable($style_filename)) {
            
            if (isset($style_data)) {
                unset($style_data);
            }

            
            include $style_filename;

            
            if (isset($style_data) && is_array($style_data)) {
                $this->language_data['STYLES'] =
                    $this->merge_arrays($this->language_data['STYLES'], $style_data);
            }
        }
    }

    







    function finalise(&$parsed_code) {
        
        
        
        if ($this->enable_important_blocks &&
            (strpos($parsed_code, $this->hsc(GESHI_START_IMPORTANT)) === false)) {
            $parsed_code = str_replace($this->hsc(GESHI_END_IMPORTANT), '', $parsed_code);
        }

        
        if ($this->header_type != GESHI_HEADER_PRE && $this->header_type != GESHI_HEADER_PRE_VALID) {
            $this->indent($parsed_code);
        }

        
        
        $parsed_code = preg_replace('#<span[^>]+>(\s*)</span>#', '\\1', $parsed_code);

        
        
        if ($this->add_ids && !$this->overall_id) {
            $this->overall_id = 'geshi-' . substr(md5(microtime()), 0, 4);
        }

        
        
        $code = explode("\n", $parsed_code);
        $parsed_code = $this->header();

        
        
        if ($this->line_numbers != GESHI_NO_LINE_NUMBERS && $this->header_type != GESHI_HEADER_PRE_TABLE) {
            
            
            $ls = ($this->header_type != GESHI_HEADER_PRE && $this->header_type != GESHI_HEADER_PRE_VALID) ? "\n" : '';

            
            $i = 0;

            
            for ($i = 0, $n = count($code); $i < $n;) {
                
                $attrs = array();

                
                
                if ('' == trim($code[$i])) {
                    $code[$i] = '&nbsp;';
                }

                
                if ($this->line_numbers == GESHI_FANCY_LINE_NUMBERS &&
                    $i % $this->line_nth_row == ($this->line_nth_row - 1)) {
                    
                    if ($this->use_classes) {
                        
                        $attrs['class'][] = 'li2';
                        $def_attr = ' class="de2"';
                    } else {
                        
                        $attrs['style'][] = $this->line_style2;
                        
                        
                        
                        $def_attr = ' style="' . $this->code_style . '"';
                    }
                } else {
                    if ($this->use_classes) {
                        
                        $attrs['class'][] = 'li1';
                        $def_attr = ' class="de1"';
                    } else {
                        
                        $attrs['style'][] = $this->line_style1;
                        $def_attr = ' style="' . $this->code_style . '"';
                    }
                }

                
                if ($this->header_type == GESHI_HEADER_PRE_VALID) {
                    $start = "<pre$def_attr>";
                    $end = '</pre>';
                } else {
                    
                    $start = "<div$def_attr>";
                    $end = '</div>';
                }

                ++$i;

                
                if ($this->add_ids) {
                    $attrs['id'][] = "$this->overall_id-$i";
                }

                
                if (in_array($i, $this->highlight_extra_lines)) {
                    if ($this->use_classes) {
                        if (isset($this->highlight_extra_lines_styles[$i])) {
                            $attrs['class'][] = "lx$i";
                        } else {
                            $attrs['class'][] = "ln-xtra";
                        }
                    } else {
                        array_push($attrs['style'], $this->get_line_style($i));
                    }
                }

                
                $attr_string = '';
                foreach ($attrs as $key => $attr) {
                    $attr_string .= ' ' . $key . '="' . implode(' ', $attr) . '"';
                }

                $parsed_code .= "<li$attr_string>$start{$code[$i-1]}$end</li>$ls";
                unset($code[$i - 1]);
            }
        } else {
            $n = count($code);
            if ($this->use_classes) {
                $attributes = ' class="de1"';
            } else {
                $attributes = ' style="'. $this->code_style .'"';
            }
            if ($this->header_type == GESHI_HEADER_PRE_VALID) {
                $parsed_code .= '<pre'. $attributes .'>';
            } elseif ($this->header_type == GESHI_HEADER_PRE_TABLE) {
                if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                    if ($this->use_classes) {
                        $attrs = ' class="ln"';
                    } else {
                        $attrs = ' style="'. $this->table_linenumber_style .'"';
                    }
                    $parsed_code .= '<td'.$attrs.'><pre'.$attributes.'>';
                    
                    
                    
                    
                    
                    for ($i = 0; $i < $n; ++$i) {
                        $close = 0;
                        
                        if ($this->line_numbers == GESHI_FANCY_LINE_NUMBERS &&
                            $i % $this->line_nth_row == ($this->line_nth_row - 1)) {
                            
                            if ($this->use_classes) {
                                $parsed_code .= '<span class="xtra li2"><span class="de2">';
                            } else {
                                
                                
                                
                                $parsed_code .= '<span style="display:block;' . $this->line_style2 . '">'
                                                  .'<span style="' . $this->code_style .'">';
                            }
                            $close += 2;
                        }
                        
                        if (in_array($i + 1, $this->highlight_extra_lines)) {
                            if ($this->use_classes) {
                                if (isset($this->highlight_extra_lines_styles[$i])) {
                                    $parsed_code .= "<span class=\"xtra lx$i\">";
                                } else {
                                    $parsed_code .= "<span class=\"xtra ln-xtra\">";
                                }
                            } else {
                                $parsed_code .= "<span style=\"display:block;" . $this->get_line_style($i) . "\">";
                            }
                            ++$close;
                        }
                        $parsed_code .= $this->line_numbers_start + $i;
                        if ($close) {
                            $parsed_code .= str_repeat('</span>', $close);
                        } else if ($i != $n) {
                            $parsed_code .= "\n";
                        }
                    }
                    $parsed_code .= '</pre></td><td'.$attributes.'>';
                }
                $parsed_code .= '<pre'. $attributes .'>';
            }
            
            
            $close = 0;
            for ($i = 0; $i < $n; ++$i) {
                
                
                if ('' == trim($code[$i])) {
                    $code[$i] = '&nbsp;';
                }
                
                if ($this->line_numbers == GESHI_FANCY_LINE_NUMBERS &&
                    $i % $this->line_nth_row == ($this->line_nth_row - 1)) {
                    
                    if ($this->use_classes) {
                        $parsed_code .= '<span class="xtra li2"><span class="de2">';
                    } else {
                        
                        
                        
                        $parsed_code .= '<span style="display:block;' . $this->line_style2 . '">'
                                          .'<span style="' . $this->code_style .'">';
                    }
                    $close += 2;
                }
                
                if (in_array($i + 1, $this->highlight_extra_lines)) {
                    if ($this->use_classes) {
                        if (isset($this->highlight_extra_lines_styles[$i])) {
                            $parsed_code .= "<span class=\"xtra lx$i\">";
                        } else {
                            $parsed_code .= "<span class=\"xtra ln-xtra\">";
                        }
                    } else {
                        $parsed_code .= "<span style=\"display:block;" . $this->get_line_style($i) . "\">";
                    }
                    ++$close;
                }

                $parsed_code .= $code[$i];

                if ($close) {
                  $parsed_code .= str_repeat('</span>', $close);
                  $close = 0;
                }
                elseif ($i + 1 < $n) {
                    $parsed_code .= "\n";
                }
                unset($code[$i]);
            }

            if ($this->header_type == GESHI_HEADER_PRE_VALID || $this->header_type == GESHI_HEADER_PRE_TABLE) {
                $parsed_code .= '</pre>';
            }
            if ($this->header_type == GESHI_HEADER_PRE_TABLE && $this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                $parsed_code .= '</td>';
            }
        }

        $parsed_code .= $this->footer();
    }

    






    function header() {
        
        



        $attributes = ' class="' . $this->language;
        if ($this->overall_class != '') {
            $attributes .= " ".$this->overall_class;
        }
        $attributes .= '"';

        if ($this->overall_id != '') {
            $attributes .= " id=\"{$this->overall_id}\"";
        }
        if ($this->overall_style != '') {
            $attributes .= ' style="' . $this->overall_style . '"';
        }

        $ol_attributes = '';

        if ($this->line_numbers_start != 1) {
            $ol_attributes .= ' start="' . $this->line_numbers_start . '"';
        }

        
        $header = $this->header_content;
        if ($header) {
            if ($this->header_type == GESHI_HEADER_PRE || $this->header_type == GESHI_HEADER_PRE_VALID) {
                $header = str_replace("\n", '', $header);
            }
            $header = $this->replace_keywords($header);

            if ($this->use_classes) {
                $attr = ' class="head"';
            } else {
                $attr = " style=\"{$this->header_content_style}\"";
            }
            if ($this->header_type == GESHI_HEADER_PRE_TABLE && $this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                $header = "<thead><tr><td colspan=\"2\" $attr>$header</td></tr></thead>";
            } else {
                $header = "<div$attr>$header</div>";
            }
        }

        if (GESHI_HEADER_NONE == $this->header_type) {
            if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                return "$header<ol$attributes$ol_attributes>";
            }
            return $header . ($this->force_code_block ? '<div>' : '');
        }

        
        if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
            if ($this->header_type == GESHI_HEADER_PRE) {
                return "<pre$attributes>$header<ol$ol_attributes>";
            } else if ($this->header_type == GESHI_HEADER_DIV ||
                $this->header_type == GESHI_HEADER_PRE_VALID) {
                return "<div$attributes>$header<ol$ol_attributes>";
            } else if ($this->header_type == GESHI_HEADER_PRE_TABLE) {
                return "<table$attributes>$header<tbody><tr class=\"li1\">";
            }
        } else {
            if ($this->header_type == GESHI_HEADER_PRE) {
                return "<pre$attributes>$header"  .
                    ($this->force_code_block ? '<div>' : '');
            } else {
                return "<div$attributes>$header" .
                    ($this->force_code_block ? '<div>' : '');
            }
        }
    }

    






    function footer() {
        $footer = $this->footer_content;
        if ($footer) {
            if ($this->header_type == GESHI_HEADER_PRE) {
                $footer = str_replace("\n", '', $footer);;
            }
            $footer = $this->replace_keywords($footer);

            if ($this->use_classes) {
                $attr = ' class="foot"';
            } else {
                $attr = " style=\"{$this->footer_content_style}\"";
            }
            if ($this->header_type == GESHI_HEADER_PRE_TABLE && $this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                $footer = "<tfoot><tr><td colspan=\"2\">$footer</td></tr></tfoot>";
            } else {
                $footer = "<div$attr>$footer</div>";
            }
        }

        if (GESHI_HEADER_NONE == $this->header_type) {
            return ($this->line_numbers != GESHI_NO_LINE_NUMBERS) ? '</ol>' . $footer : $footer;
        }

        if ($this->header_type == GESHI_HEADER_DIV || $this->header_type == GESHI_HEADER_PRE_VALID) {
            if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                return "</ol>$footer</div>";
            }
            return ($this->force_code_block ? '</div>' : '') .
                "$footer</div>";
        }
        elseif ($this->header_type == GESHI_HEADER_PRE_TABLE) {
            if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                return "</tr></tbody>$footer</table>";
            }
            return ($this->force_code_block ? '</div>' : '') .
                "$footer</div>";
        }
        else {
            if ($this->line_numbers != GESHI_NO_LINE_NUMBERS) {
                return "</ol>$footer</pre>";
            }
            return ($this->force_code_block ? '</div>' : '') .
                "$footer</pre>";
        }
    }

    








    function replace_keywords($instr) {
        $keywords = $replacements = array();

        $keywords[] = '<TIME>';
        $keywords[] = '{TIME}';
        $replacements[] = $replacements[] = number_format($time = $this->get_time(), 3);

        $keywords[] = '<LANGUAGE>';
        $keywords[] = '{LANGUAGE}';
        $replacements[] = $replacements[] = $this->language_data['LANG_NAME'];

        $keywords[] = '<VERSION>';
        $keywords[] = '{VERSION}';
        $replacements[] = $replacements[] = GESHI_VERSION;

        $keywords[] = '<SPEED>';
        $keywords[] = '{SPEED}';
        if ($time <= 0) {
            $speed = 'N/A';
        } else {
            $speed = strlen($this->source) / $time;
            if ($speed >= 1024) {
                $speed = sprintf("%.2f KB/s", $speed / 1024.0);
            } else {
                $speed = sprintf("%.0f B/s", $speed);
            }
        }
        $replacements[] = $replacements[] = $speed;

        return str_replace($keywords, $replacements, $instr);
    }

    




















































    function hsc($string, $quote_style = ENT_COMPAT) {
        
        static $aTransSpecchar = array(
            '&' => '&amp;',
            '"' => '&quot;',
            '<' => '&lt;',
            '>' => '&gt;',

            
            

            
            
            
            ';' => '<SEMI>', 
            '|' => '<PIPE>' 
            );                      

        switch ($quote_style) {
            case ENT_NOQUOTES: 
                unset($aTransSpecchar['"']);
                break;
            case ENT_QUOTES: 
                $aTransSpecchar["'"] = '&#39;'; 
                break;
        }

        
        return strtr($string, $aTransSpecchar);
    }

    








    function get_stylesheet($economy_mode = true) {
        
        
        
        if ($this->error) {
            return '';
        }

        
        
        if(!isset($this->language_data['NUMBERS_CACHE'])) {
            $this->build_style_cache();
        }

        
        
        
        if ($this->overall_id) {
            $selector = '#' . $this->overall_id;
        } else {
            $selector = '.' . $this->language;
            if ($this->overall_class) {
                $selector .= '.' . $this->overall_class;
            }
        }
        $selector .= ' ';

        
        if (!$economy_mode) {
            $stylesheet = "/**\n".
                " * GeSHi Dynamically Generated Stylesheet\n".
                " * --------------------------------------\n".
                " * Dynamically generated stylesheet for {$this->language}\n".
                " * CSS class: {$this->overall_class}, CSS id: {$this->overall_id}\n".
                " * GeSHi (C) 2004 - 2007 Nigel McNie, 2007 - 2008 Benny Baumann\n" .
                " * (http://qbnz.com/highlighter/ and http://geshi.org/)\n".
                " * --------------------------------------\n".
                " */\n";
        } else {
            $stylesheet = "/**\n".
                " * GeSHi (C) 2004 - 2007 Nigel McNie, 2007 - 2008 Benny Baumann\n" .
                " * (http://qbnz.com/highlighter/ and http://geshi.org/)\n".
                " */\n";
        }

        
        
        
        
        if (!$economy_mode || $this->line_numbers != GESHI_NO_LINE_NUMBERS) {
            
            $stylesheet .= "$selector.de1, $selector.de2 {{$this->code_style}}\n";
        }

        
        
        if ($this->overall_style != '') {
            $stylesheet .= "$selector {{$this->overall_style}}\n";
        }

        
        
        
        
        foreach ($this->link_styles as $key => $style) {
            if ($style != '') {
                switch ($key) {
                    case GESHI_LINK:
                        $stylesheet .= "{$selector}a:link {{$style}}\n";
                        break;
                    case GESHI_HOVER:
                        $stylesheet .= "{$selector}a:hover {{$style}}\n";
                        break;
                    case GESHI_ACTIVE:
                        $stylesheet .= "{$selector}a:active {{$style}}\n";
                        break;
                    case GESHI_VISITED:
                        $stylesheet .= "{$selector}a:visited {{$style}}\n";
                        break;
                }
            }
        }

        
        
        if ($this->header_content_style != '') {
            $stylesheet .= "$selector.head {{$this->header_content_style}}\n";
        }
        if ($this->footer_content_style != '') {
            $stylesheet .= "$selector.foot {{$this->footer_content_style}}\n";
        }

        
        
        if ($this->important_styles != '') {
            $stylesheet .= "$selector.imp {{$this->important_styles}}\n";
        }

        
        if ((!$economy_mode || $this->line_numbers != GESHI_NO_LINE_NUMBERS) && $this->line_style1 != '') {
            $stylesheet .= "{$selector}li, {$selector}.li1 {{$this->line_style1}}\n";
        }
        if ((!$economy_mode || $this->line_numbers != GESHI_NO_LINE_NUMBERS) && $this->table_linenumber_style != '') {
            $stylesheet .= "{$selector}.ln {{$this->table_linenumber_style}}\n";
        }
        
        if ((!$economy_mode || $this->line_numbers == GESHI_FANCY_LINE_NUMBERS) && $this->line_style2 != '') {
            $stylesheet .= "{$selector}.li2 {{$this->line_style2}}\n";
        }

        
        foreach ($this->language_data['STYLES']['KEYWORDS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                (isset($this->lexic_permissions['KEYWORDS'][$group]) &&
                $this->lexic_permissions['KEYWORDS'][$group]))) {
                $stylesheet .= "$selector.kw$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['COMMENTS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                (isset($this->lexic_permissions['COMMENTS'][$group]) &&
                $this->lexic_permissions['COMMENTS'][$group]) ||
                (!empty($this->language_data['COMMENT_REGEXP']) &&
                !empty($this->language_data['COMMENT_REGEXP'][$group])))) {
                $stylesheet .= "$selector.co$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['ESCAPE_CHAR'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['ESCAPE_CHAR'])) {
                
                if ($group === 'HARD') {
                    $group = '_h';
                }
                $stylesheet .= "$selector.es$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['BRACKETS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['BRACKETS'])) {
                $stylesheet .= "$selector.br$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['SYMBOLS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['SYMBOLS'])) {
                $stylesheet .= "$selector.sy$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['STRINGS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['STRINGS'])) {
                
                if ($group === 'HARD') {
                    $group = '_h';
                }
                $stylesheet .= "$selector.st$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['NUMBERS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['NUMBERS'])) {
                $stylesheet .= "$selector.nu$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['METHODS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['METHODS'])) {
                $stylesheet .= "$selector.me$group {{$styles}}\n";
            }
        }
        
        foreach ($this->language_data['STYLES']['SCRIPT'] as $group => $styles) {
            if ($styles != '') {
                $stylesheet .= "$selector.sc$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['REGEXPS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                (isset($this->lexic_permissions['REGEXPS'][$group]) &&
                $this->lexic_permissions['REGEXPS'][$group]))) {
                if (is_array($this->language_data['REGEXPS'][$group]) &&
                    array_key_exists(GESHI_CLASS, $this->language_data['REGEXPS'][$group])) {
                    $stylesheet .= "$selector.";
                    $stylesheet .= $this->language_data['REGEXPS'][$group][GESHI_CLASS];
                    $stylesheet .= " {{$styles}}\n";
                } else {
                    $stylesheet .= "$selector.re$group {{$styles}}\n";
                }
            }
        }
        
        if (!$economy_mode || (count($this->highlight_extra_lines)!=count($this->highlight_extra_lines_styles))) {
            $stylesheet .= "{$selector}.ln-xtra, {$selector}li.ln-xtra, {$selector}div.ln-xtra {{$this->highlight_extra_lines_style}}\n";
        }
        $stylesheet .= "{$selector}span.xtra { display:block; }\n";
        foreach ($this->highlight_extra_lines_styles as $lineid => $linestyle) {
            $stylesheet .= "{$selector}.lx$lineid, {$selector}li.lx$lineid, {$selector}div.lx$lineid {{$linestyle}}\n";
        }

        return $stylesheet;
    }

    






    function get_line_style($line) {
        
        $style = null;
        if (isset($this->highlight_extra_lines_styles[$line])) {
            $style = $this->highlight_extra_lines_styles[$line];
        } else { 
            $style = $this->highlight_extra_lines_style;
        }

        return $style;
    }

    














    function optimize_regexp_list($list, $regexp_delimiter = '/') {
        $regex_chars = array('.', '\\', '+', '*', '?', '[', '^', ']', '$',
            '(', ')', '{', '}', '=', '!', '<', '>', '|', ':', $regexp_delimiter);
        sort($list);
        $regexp_list = array('');
        $num_subpatterns = 0;
        $list_key = 0;

        
        $tokens = array();
        $prev_keys = array();
        
        $cur_len = 0;
        for ($i = 0, $i_max = count($list); $i < $i_max; ++$i) {
            if ($cur_len > GESHI_MAX_PCRE_LENGTH) {
                
                $regexp_list[++$list_key] = $this->_optimize_regexp_list_tokens_to_string($tokens);
                $num_subpatterns = substr_count($regexp_list[$list_key], '(?:');
                $tokens = array();
                $cur_len = 0;
            }
            $level = 0;
            $entry = preg_quote((string) $list[$i], $regexp_delimiter);
            $pointer = &$tokens;
            
            
            while (true) {
                
                if (isset($prev_keys[$level])) {
                    if ($prev_keys[$level] == $entry) {
                        
                        continue 2;
                    }
                    $char = 0;
                    while (isset($entry[$char]) && isset($prev_keys[$level][$char])
                            && $entry[$char] == $prev_keys[$level][$char]) {
                        ++$char;
                    }
                    if ($char > 0) {
                        
                        if ($char == strlen($prev_keys[$level])) {
                            
                            $pointer = &$pointer[$prev_keys[$level]];
                        } else {
                            
                            $new_key_part1 = substr($prev_keys[$level], 0, $char);
                            $new_key_part2 = substr($prev_keys[$level], $char);

                            if (in_array($new_key_part1[0], $regex_chars)
                                || in_array($new_key_part2[0], $regex_chars)) {
                                
                                $pointer[$entry] = array('' => true);
                                array_splice($prev_keys, $level, count($prev_keys), $entry);
                                $cur_len += strlen($entry);
                                continue;
                            } else {
                                
                                $pointer[$new_key_part1] = array($new_key_part2 => $pointer[$prev_keys[$level]]);
                                unset($pointer[$prev_keys[$level]]);
                                $pointer = &$pointer[$new_key_part1];
                                
                                array_splice($prev_keys, $level, count($prev_keys), array($new_key_part1, $new_key_part2));
                                $cur_len += strlen($new_key_part2);
                            }
                        }
                        ++$level;
                        $entry = substr($entry, $char);
                        continue;
                    }
                    
                }
                if ($level == 0 && !empty($tokens)) {
                    
                    $new_entry = $this->_optimize_regexp_list_tokens_to_string($tokens);
                    $new_subpatterns = substr_count($new_entry, '(?:');
                    if (GESHI_MAX_PCRE_SUBPATTERNS && $num_subpatterns + $new_subpatterns > GESHI_MAX_PCRE_SUBPATTERNS) {
                        $regexp_list[++$list_key] = $new_entry;
                        $num_subpatterns = $new_subpatterns;
                    } else {
                        if (!empty($regexp_list[$list_key])) {
                            $new_entry = '|' . $new_entry;
                        }
                        $regexp_list[$list_key] .= $new_entry;
                        $num_subpatterns += $new_subpatterns;
                    }
                    $tokens = array();
                    $cur_len = 0;
                }
                
                $pointer[$entry] = array('' => true);
                array_splice($prev_keys, $level, count($prev_keys), $entry);

                $cur_len += strlen($entry);
                break;
            }
            unset($list[$i]);
        }
        
        $new_entry = $this->_optimize_regexp_list_tokens_to_string($tokens);
        if (GESHI_MAX_PCRE_SUBPATTERNS && $num_subpatterns + substr_count($new_entry, '(?:') > GESHI_MAX_PCRE_SUBPATTERNS) {
            $regexp_list[++$list_key] = $new_entry;
        } else {
            if (!empty($regexp_list[$list_key])) {
                $new_entry = '|' . $new_entry;
            }
            $regexp_list[$list_key] .= $new_entry;
        }
        return $regexp_list;
    }
    










    function _optimize_regexp_list_tokens_to_string(&$tokens, $recursed = false) {
        $list = '';
        foreach ($tokens as $token => $sub_tokens) {
            $list .= $token;
            $close_entry = isset($sub_tokens['']);
            unset($sub_tokens['']);
            if (!empty($sub_tokens)) {
                $list .= '(?:' . $this->_optimize_regexp_list_tokens_to_string($sub_tokens, true) . ')';
                if ($close_entry) {
                    
                    $list .= '?';
                }
            }
            $list .= '|';
        }
        if (!$recursed) {
            
            
            
            
            
            
            $list = preg_replace('#\(\?\:(.)\)\?#', '\1?', $list);
            
            
            static $callback_2;
            if (!isset($callback_2)) {
                $callback_2 = create_function('$matches', 'return "[" . str_replace("|", "", $matches[1]) . "]";');
            }
            $list = preg_replace_callback('#\(\?\:((?:.\|)+.)\)#', $callback_2, $list);
        }
        
        return substr($list, 0, -1);
    }
} 


if (!function_exists('geshi_highlight')) {
    










    function geshi_highlight($string, $language, $path = null, $return = false) {
        $geshi = new GeSHi($string, $language, $path);
        $geshi->set_header_type(GESHI_HEADER_NONE);

        if ($return) {
            return '<code>' . $geshi->parse_code() . '</code>';
        }

        echo '<code>' . $geshi->parse_code() . '</code>';

        if ($geshi->error()) {
            return false;
        }
        return true;
    }
}

?>
