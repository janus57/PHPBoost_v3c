<?php
















































$language_data = array (
    'LANG_NAME' => 'PHP',
    'COMMENT_SINGLE' => array(1 => '//', 2 => '#'),
    'COMMENT_MULTI' => array('/*' => '*/'),
    
    'COMMENT_REGEXP' => array(3 => '/<<<\s*?(\'?)([a-zA-Z0-9]+)\1[^\n]*?\\n.*\\n\\2(?![a-zA-Z0-9])/siU'),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array("'", '"'),
    'ESCAPE_CHAR' => '\\',
    'HARDQUOTE' => array("'", "'"),
    'HARDESCAPE' => array("\'"),
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC |  GESHI_NUMBER_OCT_PREFIX | GESHI_NUMBER_HEX_PREFIX |
        GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => array(
        1 => array(
            'and', 'or', 'xor', '__FILE__', '__LINE__', 'array', 'as', 'break', 'case', 'cfunction', 'class', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'for', 'foreach', 'function', 'global', 'if', 'include', 'include_once', 'isset', 'list', 'new', 'old_function', 'print', 'require', 'require_once', 'return', 'static', 'switch', 'unset', 'use', 'var', 'while', '__function__', '__class__', 'php_version', 'php_os', 'default_include_path', 'pear_install_dir', 'pear_extension_dir', 'php_extension_dir', 'php_bindir', 'php_libdir', 'php_datadir', 'php_sysconfdir', 'php_localstatedir', 'php_config_file_path', 'php_output_handler_start', 'php_output_handler_cont', 'php_output_handler_end', 'e_error', 'e_warning', 'e_parse', 'e_notice', 'e_core_error', 'e_core_warning', 'e_compile_error', 'e_compile_warning', 'e_user_error', 'e_user_warning', 'e_user_notice', 'e_all', 'true', 'false', 'bool', 'boolean', 'int', 'integer', 'float', 'double', 'real', 'string', 'array', 'object', 'resource', 'null', 'class', 'extends', 'parent', 'stdclass', 'directory', '__sleep', '__wakeup', 'interface', 'implements', 'abstract', 'public', 'protected', 'private', 'self', 'void'
            ),
			
        2 => array(
            ),
        3 => array(
            '&lt;?php', '?&gt;'		
			)
        ),
    'SYMBOLS' => array(
        1 => array(
            '<%', '<%=', '%>', '<?', '<?=', '?>'
            ),
        0 => array(
            '(', ')', '[', ']', '{', '}',
            '!', '@', '%', '&', '|', '/',
            '<', '>',
            '=', '-', '+', '*',
            '.', ':', ',', ';'
            )
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
			1 => 'color: #0000FF; font-weight: bold;',
            2 => 'color: #0000FF; font-weight: bold;',
            3 => 'color: #FF0000; font-weight: normal;'
            ),
        'COMMENTS' => array(
            1 => 'color: #008000; font-style: italic;',
            2 => 'color: #008000; font-style: italic;',
            3 => 'color: #008000; font-style: italic;',
            'MULTI' => 'color: #008000; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;',
            'HARD' => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #8000FF;'
            ),
        'STRINGS' => array(
            0 => 'color: #808080;',
            'HARD' => 'color: #808080;'
            ),
        'NUMBERS' => array(
            0 => 'color: #FF8000;',
            GESHI_NUMBER_OCT_PREFIX => 'color: #208080;',
            GESHI_NUMBER_HEX_PREFIX => 'color: #208080;',
            GESHI_NUMBER_FLT_SCI_ZERO => 'color:#800080;',
            ),
        'METHODS' => array(
            1 => 'color: #000000;',
            2 => 'color: #000000;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #8000FF;',
            1 => 'color: #000000; font-weight: bold;'
            ),
        'REGEXPS' => array(
            0 => 'color: #000080;'
            ),
        'SCRIPT' => array(
            0 => '',
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => ''
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => 'http://www.php.net/{FNAMEL}'
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        1 => '-&gt;',
        2 => '::'
        ),
    'REGEXPS' => array(
        
        0 => "[\\$]{1,2}[a-zA-Z_][a-zA-Z0-9_]*"
        ),
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => array(
        0 => array(
            '<?php' => '?>'
            ),
        1 => array(
            '<?' => '?>'
            ),
        2 => array(
            '<%' => '%>'
            ),
        3 => array(
            '<script language="php">' => '</script>'
            ),
        4 => "/(<\?(?:php)?)(?:'(?:[^'\\\\]|\\\\.)*?'|\"(?:[^\"\\\\]|\\\\.)*?\"|\/\*(?!\*\/).*?\*\/|.)*?(\?>|\Z)/sm",
        5 => "/(<%)(?:'(?:[^'\\\\]|\\\\.)*?'|\"(?:[^\"\\\\]|\\\\.)*?\"|\/\*(?!\*\/).*?\*\/|.)*?(%>|\Z)/sm"
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        0 => true,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true
        ),
    'TAB_WIDTH' => 4
);

?>
