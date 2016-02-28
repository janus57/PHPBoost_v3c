<?php








































$language_data = array (
    'LANG_NAME' => 'OCaml',
    'COMMENT_SINGLE' => array(),
    'COMMENT_MULTI' => array('(*' => '*)'),
    'CASE_KEYWORDS' => 0,
    'QUOTEMARKS' => array('"'),
    'ESCAPE_CHAR' => "",
    'KEYWORDS' => array(
        
        1 => array(
            'and', 'as', 'asr', 'begin', 'class', 'closed', 'constraint', 'do', 'done', 'downto', 'else',
            'end', 'exception', 'external', 'failwith', 'false', 'for', 'fun', 'function', 'functor',
            'if', 'in', 'include', 'inherit',  'incr', 'land', 'let', 'load', 'los', 'lsl', 'lsr', 'lxor',
            'match', 'method', 'mod', 'module', 'mutable', 'new', 'not', 'of', 'open', 'option', 'or', 'parser',
            'private', 'ref', 'rec', 'raise', 'regexp', 'sig', 'struct', 'stdout', 'stdin', 'stderr', 'then',
            'to', 'true', 'try', 'type', 'val', 'virtual', 'when', 'while', 'with'
            ),
        
        2 => array(
            'Arg', 'Arith_status', 'Array', 'ArrayLabels', 'Big_int', 'Bigarray', 'Buffer', 'Callback',
            'CamlinternalOO', 'Char', 'Complex', 'Condition', 'Dbm', 'Digest', 'Dynlink', 'Event',
            'Filename', 'Format', 'Gc', 'Genlex', 'Graphics', 'GraphicsX11', 'Hashtbl', 'Int32', 'Int64',
            'Lazy', 'Lexing', 'List', 'ListLabels', 'Map', 'Marshal', 'MoreLabels', 'Mutex', 'Nativeint',
            'Num', 'Obj', 'Oo', 'Parsing', 'Pervasives', 'Printexc', 'Printf', 'Queue', 'Random', 'Scanf',
            'Set', 'Sort', 'Stack', 'StdLabels', 'Str', 'Stream', 'String', 'StringLabels', 'Sys', 'Thread',
            'ThreadUnix', 'Tk'
            ),
        
        3 => array(
            'abs', 'abs_float', 'acos', 'asin', 'at_exit', 'atan', 'atan2',
            'bool_of_string', 'ceil', 'char_of_int', 'classify_float',
            'close_in', 'close_in_noerr', 'close_out', 'close_out_noerr',
            'compare', 'cos', 'cosh', 'decr', 'epsilon_float', 'exit', 'exp',
            'float', 'float_of_int', 'float_of_string', 'floor', 'flush',
            'flush_all', 'format_of_string', 'frexp', 'fst', 'ignore',
            'in_channel_length', 'infinity', 'input', 'input_binary_int',
            'input_byte', 'input_char', 'input_line', 'input_value',
            'int_of_char', 'int_of_float', 'int_of_string', 'invalid_arg',
            'ldexp', 'log', 'log10', 'max', 'max_float', 'max_int', 'min',
            'min_float', 'min_int', 'mod_float', 'modf', 'nan', 'open_in',
            'open_in_bin', 'open_in_gen', 'open_out', 'open_out_bin',
            'open_out_gen', 'out_channel_length', 'output', 'output_binary_int',
            'output_byte', 'output_char', 'output_string', 'output_value',
            'pos_in', 'pos_out',  'pred', 'prerr_char', 'prerr_endline',
            'prerr_float', 'prerr_int', 'prerr_newline', 'prerr_string',
            'print_char', 'print_endline', 'print_float', 'print_int',
            'print_newline', 'print_string', 'read_float', 'read_int',
            'read_line', 'really_input', 'seek_in', 'seek_out',
            'set_binary_mode_in', 'set_binary_mode_out', 'sin', 'sinh', 'snd',
            'sqrt', 'string_of_bool', 'string_of_float', 'string_of_format',
            'string_of_int', 'succ', 'tan', 'tanh', 'truncate'
            ),
        
        4 => array (
            'fpclass', 'in_channel', 'out_channel', 'open_flag', 'Sys_error', 'format'
            ),
        
        5 => array (
            'Exit', 'Invalid_Argument', 'Failure', 'Division_by_zero'
            )
        ),
    
    'SYMBOLS' => array(
        ';', '!', ':', '.', '=', '%', '^', '*', '-', '/', '+',
        '>', '<', '(', ')', '[', ']', '&', '|', '#', "'"
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => true, 
        3 => true, 
        4 => true, 
        5 => true  
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #06c; font-weight: bold;', 
            2 => 'color: #06c; font-weight: bold;', 
            3 => 'color: #06c; font-weight: bold;', 
            4 => 'color: #06c; font-weight: bold;', 
            5 => 'color: #06c; font-weight: bold;' 
            ),
        'COMMENTS' => array(
            'MULTI' => 'color: #5d478b; font-style: italic;' 
            ),
        'ESCAPE_CHAR' => array(
            ),
        'BRACKETS' => array(
            0 => 'color: #6c6;'
            ),
        'STRINGS' => array(
            0 => 'color: #3cb371;' 
            ),
        'NUMBERS' => array(
            0 => 'color: #c6c;' 
            ),
        'METHODS' => array(
            1 => 'color: #060;' 
            ),
        'REGEXPS' => array(
            ),
        'SYMBOLS' => array(
            0 => 'color: #a52a2a;' 
            ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        
        1 => '',
        
        2 => 'http://caml.inria.fr/pub/docs/manual-ocaml/libref/{FNAME}.html',
        
        3 => 'http://caml.inria.fr/pub/docs/manual-ocaml/libref/Pervasives.html#VAL{FNAME}',
        
        4 => 'http://caml.inria.fr/pub/docs/manual-ocaml/libref/Pervasives.html#TYPE{FNAME}',
        
        5 => 'http://caml.inria.fr/pub/docs/manual-ocaml/libref/Pervasives.html#EXCEPTION{FNAME}'
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
        )
);

?>
