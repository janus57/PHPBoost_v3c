<?php










































$language_data = array (
    'LANG_NAME' => 'Javascript',
    'COMMENT_SINGLE' => array(1 => '//'),
    'COMMENT_MULTI' => array('/*' => '*/'),
    
    'COMMENT_REGEXP' => array(2 => "/(?<=[\\s^])s\\/(?:\\\\.|(?!\n)[^\\/\\\\])+\\/(?:\\\\.|(?!\n)[^\\/\\\\])+\\/[gimsu]*(?=[\\s$\\.\\;])|(?<=[\\s^(=])m?\\/(?:\\\\.|(?!\n)[^\\/\\\\])+\\/[gimsu]*(?=[\\s$\\.\\,\\;\\)])/iU"),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array("'", '"'),
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => array(
        1 => array(
            'as', 'break', 'case', 'catch', 'continue', 'decodeURI', 'delete', 'do',
            'else', 'encodeURI', 'eval', 'finally', 'for', 'if', 'in', 'is', 'item',
            'instanceof', 'return', 'switch', 'this', 'throw', 'try', 'typeof', 'void',
            'while', 'write', 'with'
            ),
        2 => array(
            'class', 'const', 'default', 'debugger', 'export', 'extends', 'false',
            'function', 'import', 'namespace', 'new', 'null', 'package', 'private',
            'protected', 'public', 'super', 'true', 'use', 'var'
            ),
        3 => array(
            
            'alert', 'back', 'blur', 'close', 'confirm', 'focus', 'forward', 'home',
            'name', 'navigate', 'onblur', 'onerror', 'onfocus', 'onload', 'onmove',
            'onresize', 'onunload', 'open', 'print', 'prompt', 'scroll', 'status',
            'stop',
            )
        ),
    'SYMBOLS' => array(
        '(', ')', '[', ']', '{', '}',
        '+', '-', '*', '/', '%',
        '!', '@', '&', '|', '^',
        '<', '>', '=',
        ',', ';', '?', ':'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #000066; font-weight: bold;',
            2 => 'color: #003366; font-weight: bold;',
            3 => 'color: #000066;'
            ),
        'COMMENTS' => array(
            1 => 'color: #006600; font-style: italic;',
            2 => 'color: #009966; font-style: italic;',
            'MULTI' => 'color: #006600; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #009900;'
            ),
        'STRINGS' => array(
            0 => 'color: #3366CC;'
            ),
        'NUMBERS' => array(
            0 => 'color: #CC0000;'
            ),
        'METHODS' => array(
            1 => 'color: #660066;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #339933;'
            ),
        'REGEXPS' => array(
            ),
        'SCRIPT' => array(
            0 => '',
            1 => '',
            2 => '',
            3 => ''
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => ''
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        1 => '.'
        ),
    'REGEXPS' => array(
        ),
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => array(
        0 => array(
            '<script type="text/javascript">' => '</script>'
            ),
        1 => array(
            '<script language="javascript">' => '</script>'
            )
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        0 => true,
        1 => true
        )
);

?>
