<?php



















































$language_data = array (
    'LANG_NAME' => 'Perl',
    'COMMENT_SINGLE' => array(1 => '#'),
    'COMMENT_MULTI' => array(
        '=back' => '=cut',
        '=head' => '=cut',
        '=item' => '=cut',
        '=over' => '=cut',
        '=begin' => '=cut',
        '=end' => '=cut',
        '=for' => '=cut',
        '=encoding' => '=cut',
        '=pod' => '=cut'
        ),
    'COMMENT_REGEXP' => array(
        
        2 => "/(?<=[\\s^])(s|tr|y)\\/(?:\\\\.|(?!\n)[^\\/\\\\])+\\/(?:\\\\.|(?!\n)[^\\/\\\\])*\\/[msixpogcde]*(?=[\\s$\\.\\;])|(?<=[\\s^(=])(m|q[qrwx]?)?\\/(?:\\\\.|(?!\n)[^\\/\\\\])+\\/[msixpogc]*(?=[\\s$\\.\\,\\;\\)])/iU",
        
        3 => '/\$\d+/',
        
        4 => '/<<\s*?([\'"]?)([a-zA-Z0-9]+)\1;[^\n]*?\\n.*\\n\\2(?![a-zA-Z0-9])/siU',
        
        5 => '/\$(\^[a-zA-Z]?|[\*\$`\'&_\.,+\-~:;\\\\\/"\|%=\?!@#<>\(\)\[\]])(?!\w)|@[_+\-]|%[!]|\$(?=\{)/',
        ),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"','`'),
    'HARDQUOTE' => array("'", "'"),            
    'HARDESCAPE' => array('\\\'',),
        
        
        
        
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => array(
        1 => array(
            'case', 'do', 'else', 'elsif', 'for', 'if', 'then', 'until', 'while', 'foreach', 'my',
            'xor', 'or', 'and', 'unless', 'next', 'last', 'redo', 'not', 'our',
            'reset', 'continue', 'cmp', 'ne', 'eq', 'lt', 'gt', 'le', 'ge',
            ),
        2 => array(
            'use', 'sub', 'new', '__END__', '__DATA__', '__DIE__', '__WARN__', 'BEGIN',
            'STDIN', 'STDOUT', 'STDERR', 'ARGV', 'ARGVOUT'
            ),
        3 => array(
            'abs', 'accept', 'alarm', 'atan2', 'bind', 'binmode', 'bless',
            'caller', 'chdir', 'chmod', 'chomp', 'chop', 'chown', 'chr',
            'chroot', 'close', 'closedir', 'connect', 'cos',
            'crypt', 'dbmclose', 'dbmopen', 'defined', 'delete', 'die',
            'dump', 'each', 'endgrent', 'endhostent', 'endnetent', 'endprotoent',
            'endpwent', 'endservent', 'eof', 'eval', 'exec', 'exists', 'exit',
            'exp', 'fcntl', 'fileno', 'flock', 'fork', 'format', 'formline',
            'getc', 'getgrent', 'getgrgid', 'getgrnam', 'gethostbyaddr',
            'gethostbyname', 'gethostent', 'getlogin', 'getnetbyaddr', 'getnetbyname',
            'getnetent', 'getpeername', 'getpgrp', 'getppid', 'getpriority',
            'getprotobyname', 'getprotobynumber', 'getprotoent', 'getpwent',
            'getpwnam', 'getpwuid', 'getservbyname', 'getservbyport', 'getservent',
            'getsockname', 'getsockopt', 'glob', 'gmtime', 'goto', 'grep',
            'hex', 'import', 'index', 'int', 'ioctl', 'join', 'keys', 'kill',
            'lc', 'lcfirst', 'length', 'link', 'listen', 'local',
            'localtime', 'log', 'lstat', 'm', 'map', 'mkdir', 'msgctl', 'msgget',
            'msgrcv', 'msgsnd', 'no', 'oct', 'open', 'opendir',
            'ord', 'pack', 'package', 'pipe', 'pop', 'pos', 'print',
            'printf', 'prototype', 'push', 'qq', 'qr', 'quotemeta', 'qw',
            'qx', 'q', 'rand', 'read', 'readdir', 'readline', 'readlink', 'readpipe',
            'recv', 'ref', 'rename', 'require', 'return',
            'reverse', 'rewinddir', 'rindex', 'rmdir', 's', 'scalar', 'seek',
            'seekdir', 'select', 'semctl', 'semget', 'semop', 'send', 'setgrent',
            'sethostent', 'setnetent', 'setpgrp', 'setpriority', 'setprotoent',
            'setpwent', 'setservent', 'setsockopt', 'shift', 'shmctl', 'shmget',
            'shmread', 'shmwrite', 'shutdown', 'sin', 'sleep', 'socket', 'socketpair',
            'sort', 'splice', 'split', 'sprintf', 'sqrt', 'srand', 'stat',
            'study', 'substr', 'symlink', 'syscall', 'sysopen', 'sysread',
            'sysseek', 'system', 'syswrite', 'tell', 'telldir', 'tie', 'tied',
            'time', 'times', 'tr', 'truncate', 'uc', 'ucfirst', 'umask', 'undef',
            'unlink', 'unpack', 'unshift', 'untie', 'utime', 'values',
            'vec', 'wait', 'waitpid', 'wantarray', 'warn', 'write', 'y'
            )
        ),
    'SYMBOLS' => array(
        '<', '>', '=',
        '!', '@', '~', '&', '|', '^',
        '+','-', '*', '/', '%',
        ',', ';', '?', '.', ':'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #b1b100;',
            2 => 'color: #000000; font-weight: bold;',
            3 => 'color: #000066;'
            ),
        'COMMENTS' => array(
            1 => 'color: #666666; font-style: italic;',
            2 => 'color: #009966; font-style: italic;',
            3 => 'color: #0000ff;',
            4 => 'color: #cc0000; font-style: italic;',
            5 => 'color: #0000ff;',
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
            1 => 'color: #006600;',
            2 => 'color: #006600;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #339933;'
            ),
        'REGEXPS' => array(
            0 => 'color: #0000ff;',
            4 => 'color: #009999;',
            ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => 'http://perldoc.perl.org/functions/{FNAMEL}.html'
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        1 => '-&gt;',
        2 => '::'
        ),
    'REGEXPS' => array(
        
        0 => '(?:\$[\$#]?|\\\\(?:[@%*]?|\\\\*\$|&amp;)|%[$]?|@[$]?|\*[$]?|&amp;[$]?)[a-zA-Z_][a-zA-Z0-9_]*',
        
        4 => '&lt;[a-zA-Z_][a-zA-Z0-9_]*&gt;',
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'PARSER_CONTROL' => array(
        'COMMENTS' => array(
            'DISALLOWED_BEFORE' => '$'
        )
    )
);

?>
