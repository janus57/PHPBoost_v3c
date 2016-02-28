<?php
















































$language_data = array (
    'LANG_NAME' => 'Pascal',
    'COMMENT_SINGLE' => array(1 => '//'),
    'COMMENT_MULTI' => array('{' => '}','(*' => '*)'),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"'),
    'HARDQUOTE' => array("'", "'"),
    'HARDESCAPE' => array("''"),
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => array(
        1 => array(
            'absolute','asm','assembler','begin','break','case','catch','cdecl',
            'const','constructor','default','destructor','div','do','downto',
            'else','end','except','export','exports','external','far',
            'finalization','finally','for','forward','function','goto','if',
            'implementation','in','index','inherited','initialization','inline',
            'interface','interrupt','label','library','mod','name','not','of',
            'or','overload','override','private','procedure','program',
            'property','protected','public','published','raise','repeat',
            'resourcestring','shl','shr','stdcall','stored','switch','then',
            'to','try','type','unit','until','uses','var','while','xor'
            ),
        2 => array(
            'nil', 'false', 'true',
            ),
        3 => array(
            'abs','and','arc','arctan','blockread','blockwrite','chr','dispose',
            'cos','eof','eoln','exp','get','ln','new','odd','ord','ordinal',
            'pred','read','readln','sin','sqrt','succ','write','writeln'
            ),
        4 => array(
            'ansistring','array','boolean','byte','bytebool','char','file',
            'integer','longbool','longint','object','packed','pointer','real',
            'record','set','shortint','smallint','string','union','word'
            ),
        ),
    'SYMBOLS' => array(
        ',', ':', '=', '+', '-', '*', '/'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #000000; font-weight: bold;',
            2 => 'color: #000000; font-weight: bold;',
            3 => 'color: #000066;',
            4 => 'color: #000066; font-weight: bold;'
            ),
        'COMMENTS' => array(
            1 => 'color: #666666; font-style: italic;',
            'MULTI' => 'color: #666666; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;',
            'HARD' => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #009900;'
            ),
        'STRINGS' => array(
            0 => 'color: #ff0000;',
            'HARD' => 'color: #ff0000;'
            ),
        'NUMBERS' => array(
            0 => 'color: #cc66cc;'
            ),
        'METHODS' => array(
            1 => 'color: #0066ee;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #339933;'
            ),
        'REGEXPS' => array(
            ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => '',
        4 => ''
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        1 => '.'
        ),
    'REGEXPS' => array(
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'TAB_WIDTH' => 4
);

?>
