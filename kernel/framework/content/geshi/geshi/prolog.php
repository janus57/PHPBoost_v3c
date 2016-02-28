<?php






































$language_data = array (
    'LANG_NAME' => 'Prolog',
    'COMMENT_SINGLE' => array(1 => '%'),
    'COMMENT_MULTI' => array('/*' => '*/'),
    'HARDQUOTE' => array("'", "'"),
    'HARDESCAPE' => array("\'"),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array(),
    'ESCAPE_CHAR' => '',
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC | GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => array(
        1 => array(
            'abolish','abs','arg','asserta','assertz','at_end_of_stream','atan',
            'atom','atom_chars','atom_codes','atom_concat','atom_length',
            'atomic','bagof','call','catch','ceiling','char_code',
            'char_conversion','clause','close','compound','consult','copy_term',
            'cos','current_char_conversion','current_input','current_op',
            'current_output','current_predicate','current_prolog_flag',
            'discontiguous','dynamic','ensure_loaded','exp','fail','findall',
            'float','float_fractional_part','float_integer_part','floor',
            'flush_output','functor','get_byte','get_char','get_code','halt',
            'include','initialization','integer','is','listing','log','mod',
            'multifile','nl','nonvar','notrace','number','number_chars',
            'number_codes','once','op','open','peek_byte','peek_char',
            'peek_code','put_byte','put_char','put_code','read','read_term',
            'rem','repeat','retract','round','set_input','set_output',
            'set_prolog_flag','set_stream_position','setof','sign','sin','sqrt',
            'stream_property','sub_atom','throw','trace','true','truncate',
            'unify_with_occurs_check','univ','var','write','write_canonical',
            'write_term','writeq'
            )
        ),
    'SYMBOLS' => array(
        0 => array('(', ')', '[', ']', '{', '}',),
        1 => array('?-', ':-', '=:='),
        2 => array('\-', '\+', '\*', '\/'),
        3 => array('-', '+', '*', '/'),
        4 => array('.', ':', ',', ';'),
        5 => array('!', '@', '&', '|'),
        6 => array('<', '>', '=')
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #990000;'
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
            0 => 'color: #0000ff;',
            'HARD' => 'color: #0000ff;'
            ),
        'NUMBERS' => array(
            0 => 'color: #800080;'
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            0 => 'color: #339933;',
            1 => 'color: #339933;',
            2 => 'color: #339933;',
            3 => 'color: #339933;',
            4 => 'color: #339933;',
            5 => 'color: #339933;',
            6 => 'color: #339933;'
            ),
        'REGEXPS' => array(
            0 => 'color: #008080;'
            ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        1 => 'http://pauillac.inria.fr/~deransar/prolog/bips.html'
        ),
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => array(
        ),
    'REGEXPS' => array(
        
        0 => "(?<![A-Z_])(?!(?:PIPE|SEMI)[^a-zA-Z0-9_])[A-Z_][a-zA-Z0-9_]*(?![a-zA-Z0-9_])"
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'TAB_WIDTH' => 4
);

?>
