<?php



















































$language_data = array (
    'LANG_NAME' => 'LaTeX',
    'COMMENT_SINGLE' => array(
        1 => '%'
        ),
    'COMMENT_MULTI' => array(),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array(),
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => array(
        1 => array(
            'appendix','backmatter','caption','captionabove','captionbelow',
            'def','documentclass','edef','equation','flushleft','flushright',
            'footnote','frontmatter','hline','include','input','item','label',
            'let','listfiles','listoffigures','listoftables','mainmatter',
            'makeatletter','makeatother','makebox','mbox','par','raggedleft',
            'raggedright','raisebox','ref','rule','table','tableofcontents',
            'textbf','textit','texttt','today'
            )
        ),
    'SYMBOLS' => array(
        "&", "\\", "{", "}", "[", "]"
        ),
    'CASE_SENSITIVE' => array(
        1 => true,
        GESHI_COMMENTS => false,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #800000; font-weight: bold;',
            ),
        'COMMENTS' => array(
            1 => 'color: #2C922C; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 =>  'color: #000000; font-weight: bold;'
            ),
        'BRACKETS' => array(
            ),
        'STRINGS' => array(
            0 =>  'color: #000000;'
            ),
        'NUMBERS' => array(
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            0 =>  'color: #E02020; '
            ),
        'REGEXPS' => array(
            1 => 'color: #8020E0; font-weight: normal;',  
            2 => 'color: #C08020; font-weight: normal;', 
            3 => 'color: #8020E0; font-weight: normal;', 
            4 => 'color: #800000; font-weight: normal;', 
            5 => 'color: #00008B; font-weight: bold;',  
            6 => 'color: #800000; font-weight: normal;', 
            7 => 'color: #0000D0; font-weight: normal;', 
            8 => 'color: #C00000; font-weight: normal;', 
            9 => 'color: #2020C0; font-weight: normal;', 
            10 => 'color: #800000; font-weight: normal;', 
            11 => 'color: #E00000; font-weight: normal;', 
            12 => 'color: #800000; font-weight: normal;', 
        ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        1 => 'http://www.golatex.de/wiki/index.php?title=\\{FNAME}',
        ),
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => array(
        ),
    'REGEXPS' => array(
        
        1 => array(
            GESHI_SEARCH => "(\\\\begin\\{(equation|displaymath|eqnarray|subeqnarray|math|multline|gather|align|alignat|flalign)\\})(.*)(\\\\end\\{\\2\\})",
            GESHI_REPLACE => '\3',
            GESHI_MODIFIERS => 'Us',
            GESHI_BEFORE => '\1',
            GESHI_AFTER => '\4'
            ),
        
        2 => array(
            GESHI_SEARCH => "(?<=\[).+(?=\])",
            GESHI_REPLACE => '\0',
            GESHI_MODIFIERS => 'Us',
            GESHI_BEFORE => '',
            GESHI_AFTER => ''
            ),
        
        3 => array(
            GESHI_SEARCH => "\\$.+\\$",
            GESHI_REPLACE => '\0',
            GESHI_MODIFIERS => 'Us',
            GESHI_BEFORE => '',
            GESHI_AFTER => ''
            ),
        
        4 => "\\\\(?:label|pageref|ref|cite)(?=[^a-zA-Z])",
        
        5 => array(
            GESHI_SEARCH => "(\\\\(?:part|chapter|(?:sub){0,2}section|(?:sub)?paragraph|addpart|addchap|addsec)\*?\\{)(.*)(?=\\})",
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => 'U',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => ''
            ),
        
        6 => "\\\\(?:part|chapter|(?:sub){0,2}section|(?:sub)?paragraph|addpart|addchap|addsec)\*?(?=[^a-zA-Z])",
        
        7 => array(
            GESHI_SEARCH => "(\\\\(?:begin|end)\\{)(.*)(?=\\})",
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => 'U',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => ''
            ),
        
        8 => "\\\\(?:end|begin)(?=[^a-zA-Z])",
        
        9 => array(
            GESHI_SEARCH => "(?<=\\{)(?!<\|!REG3XP5!>).*(?=\\})",
            GESHI_REPLACE => '\0',
            GESHI_MODIFIERS => 'Us',
            GESHI_BEFORE => '',
            GESHI_AFTER => ''
            ),
        
        10 => "\\\\(?:[_$%]|&amp;)",
        
        11 => "(?<!<\|!REG3XP[8]!>)\\\\@[a-zA-Z]+\*?",
        
        12 => "(?<!<\|!REG3XP[468]!>)\\\\[a-zA-Z]+\*?",


        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'PARSER_CONTROL' => array(
        'COMMENTS' => array(
            'DISALLOWED_BEFORE' => '\\'
        ),
        'KEYWORDS' => array(
            'DISALLOWED_BEFORE' => "(?<=\\\\)",
            'DISALLOWED_AFTER' => "(?=\b)(?!\w)"
        ),
        'ENABLE_FLAGS' => array(
            'NUMBERS' => GESHI_NEVER,
            'BRACKETS' => GESHI_NEVER
        )
    )
);

?>
