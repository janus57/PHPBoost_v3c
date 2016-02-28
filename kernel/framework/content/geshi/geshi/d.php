<?php











































$language_data = array (
    'LANG_NAME' => 'D',
    'COMMENT_SINGLE' => array(2 => '///', 1 => '//'),
    'COMMENT_MULTI' => array('/*' => '*/'),
    'COMMENT_REGEXP' => array(
        
        3 => '#/\*\*(?![\*\/]).*\*/#sU',
        
        4 => '#r"[^"]*"#s',
        
        5 => "/\A#!(?=\\/).*?$/m"
        ),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"', "'"),
    'ESCAPE_CHAR' => '',
    'ESCAPE_REGEXP' => array(
        
        1 => "#\\\\[abfnrtv\\'\"?\n\\\\]#i",
        
        2 => "#\\\\x[\da-fA-F]{2}#",
        
        3 => "#\\\\u[\da-fA-F]{4}#",
        
        4 => "#\\\\U[\da-fA-F]{8}#",
        
        5 => "#\\\\[0-7]{1,3}#",
        
        






















        
        6 => "#\\\\&(?:A(?:Elig|acute|circ|grave|lpha|ring|tilde|uml)|Beta|".
            "C(?:cedil|hi)|D(?:agger|elta)|E(?:TH|acute|circ|grave|psilon|ta|uml)|".
            "Gamma|I(?:acute|circ|grave|ota|uml)|Kappa|Lambda|Mu|N(?:tilde|u)|".
            "O(?:Elig|acute|circ|grave|m(?:ega|icron)|slash|tilde|uml)|".
            "P(?:hi|i|rime|si)|Rho|S(?:caron|igma)|T(?:HORN|au|heta)|".
            "U(?:acute|circ|grave|psilon|uml)|Xi|Y(?:acute|uml)|Zeta|".
            "a(?:acute|c(?:irc|ute)|elig|grave|l(?:efsym|pha)|mp|n[dg]|ring|".
            "symp|tilde|uml)|b(?:dquo|eta|rvbar|ull)|c(?:ap|cedil|e(?:dil|nt)|".
            "hi|irc|lubs|o(?:ng|py)|rarr|u(?:p|rren))|d(?:Arr|a(?:gger|rr)|".
            "e(?:g|lta)|i(?:ams|vide))|e(?:acute|circ|grave|m(?:pty|sp)|nsp|".
            "psilon|quiv|t[ah]|u(?:ml|ro)|xist)|f(?:nof|orall|ra(?:c(?:1[24]|34)|sl))|".
            "g(?:amma|e|t)|h(?:Arr|arr|e(?:arts|llip))|i(?:acute|circ|excl|grave|mage|".
            "n(?:fin|t)|ota|quest|sin|uml)|kappa|l(?:Arr|a(?:mbda|ng|quo|rr)|ceil|".
            "dquo|e|floor|o(?:wast|z)|rm|s(?:aquo|quo)|t)|m(?:acr|dash|".
            "i(?:cro|ddot|nus)|u)|n(?:abla|bsp|dash|e|i|ot(?:in)?|sub|tilde|u)|".
            "o(?:acute|circ|elig|grave|line|m(?:ega|icron)|plus|r(?:d[fm])?|".
            "slash|ti(?:lde|mes)|uml)|p(?:ar[at]|er(?:mil|p)|hi|iv?|lusmn|ound|".
            "r(?:ime|o[dp])|si)|quot|r(?:Arr|a(?:dic|ng|quo|rr)|ceil|dquo|e(?:al|g)|".
            "floor|ho|lm|s(?:aquo|quo))|s(?:bquo|caron|dot|ect|hy|i(?:gmaf?|m)|".
            "pades|u(?:be?|m|p[123e]?)|zlig)|t(?:au|h(?:e(?:re4|ta(?:sym)?)|insp|".
            "orn)|i(?:lde|mes)|rade)|u(?:Arr|a(?:cute|rr)|circ|grave|ml|".
            "psi(?:h|lon)|uml)|weierp|xi|y(?:acute|en|uml)|z(?:eta|w(?:j|nj)));#",
        ),
    'HARDQUOTE' => array('`', '`'),
    'HARDESCAPE' => array(),
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC | GESHI_NUMBER_INT_CSTYLE | GESHI_NUMBER_BIN_PREFIX_0B |
        GESHI_NUMBER_OCT_PREFIX | GESHI_NUMBER_HEX_PREFIX | GESHI_NUMBER_FLT_NONSCI |
        GESHI_NUMBER_FLT_NONSCI_F | GESHI_NUMBER_FLT_SCI_SHORT | GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => array(
        1 => array(
                'break', 'case', 'continue', 'do', 'else',
                'for', 'foreach', 'goto', 'if', 'return',
                'switch', 'while'
            ),
        2 => array(
                'alias', 'asm', 'assert', 'body', 'cast',
                'catch', 'default', 'delegate', 'delete',
                'extern', 'false', 'finally', 'function',
                'import', 'in', 'inout', 'interface',
                'invariant', 'is', 'mixin', 'module', 'new',
                'null', 'out', 'pragma', 'ref', 'super', 'this',
                'throw', 'true', 'try', 'typedef', 'typeid',
                'typeof', 'union', 'with'
            ),
        3 => array(
                'ArrayBoundsError', 'AssertError',
                'ClassInfo', 'Error', 'Exception',
                'Interface', 'ModuleInfo', 'Object',
                'OutOfMemoryException', 'SwitchError',
                'TypeInfo', '_d_arrayappend',
                '_d_arrayappendb', '_d_arrayappendc',
                '_d_arrayappendcb', '_d_arraycast',
                '_d_arraycast_frombit', '_d_arraycat',
                '_d_arraycatb', '_d_arraycatn',
                '_d_arraycopy', '_d_arraycopybit',
                '_d_arraysetbit', '_d_arraysetbit2',
                '_d_arraysetlength', '_d_arraysetlengthb',
                '_d_callfinalizer',
                '_d_create_exception_object',
                '_d_criticalenter', '_d_criticalexit',
                '_d_delarray', '_d_delclass',
                '_d_delinterface', '_d_delmemory',
                '_d_dynamic_cast', '_d_exception',
                '_d_exception_filter', '_d_framehandler',
                '_d_interface_cast', '_d_interface_vtbl',
                '_d_invariant', '_d_isbaseof',
                '_d_isbaseof2', '_d_local_unwind',
                '_d_monitorenter', '_d_monitorexit',
                '_d_monitorrelease', '_d_monitor_epilog',
                '_d_monitor_handler', '_d_monitor_prolog',
                '_d_new', '_d_newarrayi', '_d_newbitarray',
                '_d_newclass', '_d_obj_cmp', '_d_obj_eq',
                '_d_OutOfMemory', '_d_switch_dstring',
                '_d_switch_string', '_d_switch_ustring',
                '_d_throw',
            ),
        4 => array(
                'abstract', 'align', 'auto', 'bit', 'bool',
                'byte', 'cdouble', 'cent', 'cfloat', 'char',
                'class', 'const', 'creal', 'dchar', 'debug',
                'deprecated', 'double', 'enum', 'export',
                'final', 'float', 'idouble', 'ifloat', 'int',
                'ireal', 'long', 'override', 'package',
                'private', 'protected', 'ptrdiff_t',
                'public', 'real', 'short', 'size_t',
                'static', 'struct', 'synchronized',
                'template', 'ubyte', 'ucent', 'uint',
                'ulong', 'unittest', 'ushort', 'version',
                'void', 'volatile', 'wchar'
            )
        ),
    'SYMBOLS' => array(
        '(', ')', '[', ']', '{', '}', '?', '!', ';', ':', ',', '...', '..',
        '+', '-', '*', '/', '%', '&', '|', '^', '<', '>', '=', '~',
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        4 => true
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #b1b100;',
            2 => 'color: #000000; font-weight: bold;',
            3 => 'color: #aaaadd; font-weight: bold;',
            4 => 'color: #993333;'
            ),
        'COMMENTS' => array(
            1 => 'color: #808080; font-style: italic;',
            2 => 'color: #009933; font-style: italic;',
            3 => 'color: #009933; font-style: italic;',
            4 => 'color: #ff0000;',
            5 => 'color: #0040ff;',
            'MULTI' => 'color: #808080; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;',
            1 => 'color: #000099; font-weight: bold;',
            2 => 'color: #660099; font-weight: bold;',
            3 => 'color: #660099; font-weight: bold;',
            4 => 'color: #660099; font-weight: bold;',
            5 => 'color: #006699; font-weight: bold;',
            6 => 'color: #666699; font-weight: bold; font-style: italic;',
            'HARD' => '',
            ),
        'BRACKETS' => array(
            0 => 'color: #66cc66;'
            ),
        'STRINGS' => array(
            0 => 'color: #ff0000;',
            'HARD' => 'color: #ff0000;'
            ),
        'NUMBERS' => array(
            0 => 'color: #0000dd;',
            GESHI_NUMBER_BIN_PREFIX_0B => 'color: #208080;',
            GESHI_NUMBER_OCT_PREFIX => 'color: #208080;',
            GESHI_NUMBER_HEX_PREFIX => 'color: #208080;',
            GESHI_NUMBER_FLT_SCI_SHORT => 'color:#800080;',
            GESHI_NUMBER_FLT_SCI_ZERO => 'color:#800080;',
            GESHI_NUMBER_FLT_NONSCI_F => 'color:#800080;',
            GESHI_NUMBER_FLT_NONSCI => 'color:#800080;'
            ),
        'METHODS' => array(
            1 => 'color: #006600;',
            2 => 'color: #006600;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #66cc66;'
            ),
        'SCRIPT' => array(
            ),
        'REGEXPS' => array(
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
        1 => '.',
        ),
    'REGEXPS' => array(
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        )
);

?>
