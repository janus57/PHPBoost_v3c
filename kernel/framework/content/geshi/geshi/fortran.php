<?php










































$language_data = array (
    'LANG_NAME'=>'Fortran',
    'COMMENT_SINGLE'=> array(1 =>'!',2=>'Cf2py'),
    'COMMENT_MULTI'=> array(),
    
    'COMMENT_REGEXP' => array(1 => '/^C.*?$/mi'),
    'CASE_KEYWORDS'=> GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS'=> array("'",'"'),
    'ESCAPE_CHAR'=>'\\',
    'KEYWORDS'=> array(
        1 => array(
            'allocate','block','call','case','contains','continue','cycle','deallocate',
            'default','do','else','elseif','elsewhere','end','enddo','endif','endwhere',
            'entry','exit','function','go','goto','if','interface','module','nullify','only',
            'operator','procedure','program','recursive','return','select','stop',
            'subroutine','then','to','where','while',
            'access','action','advance','blank','blocksize','carriagecontrol',
            'delim','direct','eor','err','exist','file','flen','fmt','form','formatted',
            'iostat','name','named','nextrec','nml','number','opened','pad','position',
            'readwrite','recl','sequential','status','unformatted','unit'
            ),
        2 => array(
            '.AND.','.EQ.','.EQV.','.GE.','.GT.','.LE.','.LT.','.NE.','.NEQV.','.NOT.',
            '.OR.','.TRUE.','.FALSE.'
            ),
        3 => array(
            'allocatable','character','common','complex','data','dimension','double',
            'equivalence','external','implicit','in','inout','integer','intent','intrinsic',
            'kind','logical','namelist','none','optional','out','parameter','pointer',
            'private','public','real','result','save','sequence','target','type','use'
            ),
        4 => array(
            'abs','achar','acos','adjustl','adjustr','aimag','aint','all','allocated',
            'anint','any','asin','atan','atan2','bit_size','break','btest','carg',
            'ceiling','char','cmplx','conjg','cos','cosh','cpu_time','count','cshift',
            'date_and_time','dble','digits','dim','dot_product','dprod dvchk',
            'eoshift','epsilon','error','exp','exponent','floor','flush','fraction',
            'getcl','huge','iachar','iand','ibclr','ibits','ibset','ichar','ieor','index',
            'int','intrup','invalop','ior','iostat_msg','ishft','ishftc','lbound',
            'len','len_trim','lge','lgt','lle','llt','log','log10','matmul','max','maxexponent',
            'maxloc','maxval','merge','min','minexponent','minloc','minval','mod','modulo',
            'mvbits','nbreak','ndperr','ndpexc','nearest','nint','not','offset','ovefl',
            'pack','precfill','precision','present','product','prompt','radix',
            'random_number','random_seed','range','repeat','reshape','rrspacing',
            'scale','scan','segment','selected_int_kind','selected_real_kind',
            'set_exponent','shape','sign','sin','sinh','size','spacing','spread','sqrt',
            'sum system','system_clock','tan','tanh','timer','tiny','transfer','transpose',
            'trim','ubound','undfl','unpack','val','verify'
            ),
        ),
    'SYMBOLS'=> array(
        '(',')','{','}','[',']','=','+','-','*','/','!','%','^','&',':'
        ),
    'CASE_SENSITIVE'=> array(
        GESHI_COMMENTS => true,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        ),
    'STYLES'=> array(
        'KEYWORDS'=> array(
            1 =>'color: #b1b100;',
            2 =>'color: #000000; font-weight: bold;',
            3 =>'color: #000066;',
            4 =>'color: #993333;'
            ),
        'COMMENTS'=> array(
            1 =>'color: #666666; font-style: italic;',
            2 =>'color: #339933;',
            'MULTI'=>'color: #808080; font-style: italic;'
            ),
        'ESCAPE_CHAR'=> array(
            0 =>'color: #000099; font-weight: bold;'
            ),
        'BRACKETS'=> array(
            0 =>'color: #009900;'
            ),
        'STRINGS'=> array(
            0 =>'color: #ff0000;'
            ),
        'NUMBERS'=> array(
            0 =>'color: #cc66cc;'
            ),
        'METHODS'=> array(
            1 =>'color: #202020;',
            2 =>'color: #202020;'
            ),
        'SYMBOLS'=> array(
            0 =>'color: #339933;'
            ),
        'REGEXPS'=> array(
            ),
        'SCRIPT'=> array(
            )
        ),
    'URLS'=> array(
        1 =>'',
        2 =>'',
        3 =>'',
        4 =>''
        ),
    'OOLANG'=> true,
    'OBJECT_SPLITTERS'=> array(
        1 =>'.',
        2 =>'::'
        ),
    'REGEXPS'=> array(
        ),
    'STRICT_MODE_APPLIES'=> GESHI_NEVER,
    'SCRIPT_DELIMITERS'=> array(
        ),
    'HIGHLIGHT_STRICT_BLOCK'=> array(
        )
);

?>
