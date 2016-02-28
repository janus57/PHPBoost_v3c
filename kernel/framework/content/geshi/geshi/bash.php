<?php





















































$language_data = array (
    'LANG_NAME' => 'Bash',
    
    
    
    'COMMENT_SINGLE' => array('#'),
    'COMMENT_MULTI' => array(),
    'COMMENT_REGEXP' => array(
        
        1 => "/\\$\\{[^\\n\\}]*?\\}/i",
        
        2 => '/<<-?\s*?(\'?)([a-zA-Z0-9]+)\1\\n.*\\n\\2(?![a-zA-Z0-9])/siU',
        
        3 => "/\\\\['\"]/siU"
        ),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"'),
    'HARDQUOTE' => array("'", "'"),
    'HARDESCAPE' => array("\'"),
    'ESCAPE_CHAR' => '',
    'ESCAPE_REGEXP' => array(
        
        1 => "#\\\\[nfrtv\\$\\\"\n]#i",
        
        2 => "#\\$[a-z_][a-z0-9_]*#i",
        
        3 => "/\\$\\{[^\\n\\}]*?\\}/i",
        
        4 => "/\\$\\([^\\n\\)]*?\\)/i",
        
        5 => "/`[^`]*`/"
        ),
    'KEYWORDS' => array(
        1 => array(
            'case', 'do', 'done', 'elif', 'else', 'esac', 'fi', 'for', 'function',
            'if', 'in', 'select', 'set', 'then', 'until', 'while', 'time'
            ),
        2 => array(
            'aclocal', 'aconnect', 'aplay', 'apm', 'apmsleep', 'apropos',
            'apt-cache', 'apt-get', 'apt-key', 'aptitude',
            'ar', 'arch', 'arecord', 'as', 'as86', 'ash', 'autoconf',
            'autoheader', 'automake', 'awk',

            'basename', 'bash', 'bc', 'bison', 'bunzip2', 'bzcat',
            'bzcmp', 'bzdiff', 'bzegrep', 'bzfgrep', 'bzgrep',
            'bzip2', 'bzip2recover', 'bzless', 'bzmore',

            'c++', 'cal', 'cat', 'chattr', 'cc', 'cdda2wav', 'cdparanoia',
            'cdrdao', 'cd-read', 'cdrecord', 'chfn', 'chgrp', 'chmod',
            'chown', 'chroot', 'chsh', 'chvt', 'clear', 'cmp', 'comm', 'co',
            'col', 'cp', 'cpio', 'cpp', 'csh', 'cut', 'cvs', 'cvs-pserver',

            'dash', 'date', 'dd', 'dc', 'dcop', 'deallocvt', 'df', 'dialog',
            'diff', 'diff3', 'dir', 'dircolors', 'directomatic', 'dirname',
            'dmesg', 'dnsdomainname', 'domainname', 'dpkg', 'dselect', 'du',
            'dumpkeys',

            'ed', 'egrep', 'env', 'expr',

            'false', 'fbset', 'ffmpeg', 'fgconsole','fgrep', 'file', 'find',
            'flex', 'flex++', 'fmt', 'free', 'ftp', 'funzip', 'fuser',

            'g++', 'gawk', 'gc','gcc', 'gdb', 'getent', 'getkeycodes',
            'getopt', 'gettext', 'gettextize', 'gimp', 'gimp-remote',
            'gimptool', 'gmake', 'gocr', 'grep', 'groups', 'gs', 'gunzip',
            'gzexe', 'gzip',

            'head', 'hexdump', 'hostname',

            'id', 'ifconfig', 'igawk', 'install',

            'join',

            'kbd_mode','kbdrate', 'kdialog', 'kfile', 'kill', 'killall',

            'lame', 'last', 'lastb', 'ld', 'ld86', 'ldd', 'less', 'lex', 'link',
            'ln', 'loadkeys', 'loadunimap', 'locate', 'lockfile', 'login',
            'logname', 'lp', 'lpr', 'ls', 'lsattr', 'lsmod', 'lsmod.old',
            'lspci', 'ltrace', 'lynx',

            'm4', 'make', 'man', 'mapscrn', 'mesg', 'mkdir', 'mkfifo',
            'mknod', 'mktemp', 'more', 'mount', 'mplayer', 'msgfmt', 'mv',

            'namei', 'nano', 'nasm', 'nawk', 'netstat', 'nice',
            'nisdomainname', 'nl', 'nm', 'nm86', 'nmap', 'nohup', 'nop',

            'od', 'openvt',

            'passwd', 'patch', 'pcregrep', 'pcretest', 'perl', 'perror',
            'pgawk', 'pidof', 'ping', 'pr', 'procmail', 'prune', 'ps', 'pstree',
            'ps2ascii', 'ps2epsi', 'ps2frag', 'ps2pdf', 'ps2ps', 'psbook',
            'psmerge', 'psnup', 'psresize', 'psselect', 'pstops',

            'rbash', 'rcs', 'rcs2log', 'read', 'readlink', 'red', 'resizecons',
            'rev', 'rm', 'rmdir', 'rsh', 'run-parts',

            'sash', 'scp', 'screen', 'sed', 'seq', 'sendmail', 'setfont',
            'setkeycodes', 'setleds', 'setmetamode', 'setserial', 'setterm',
            'sh', 'showkey', 'shred', 'size', 'size86', 'skill', 'sleep',
            'slogin', 'snice', 'sort', 'sox', 'split', 'ssed', 'ssh', 'ssh-add',
            'ssh-agent', 'ssh-keygen', 'ssh-keyscan', 'stat', 'strace',
            'strings', 'strip', 'stty', 'su', 'sudo', 'suidperl', 'sum', 'svn',
            'svnadmin', 'svndumpfilter', 'svnlook', 'svnmerge', 'svnmucc',
            'svnserve', 'svnshell', 'svnsync', 'svnversion', 'svnwrap', 'sync',

            'tac', 'tail', 'tar', 'tee', 'tempfile', 'touch', 'tr', 'tree',
            'true',

            'umount', 'uname', 'unicode_start', 'unicode_stop', 'uniq',
            'unlink', 'unzip', 'updatedb', 'updmap', 'uptime', 'users',
            'utmpdump', 'uuidgen',

            'valgrind', 'vdir', 'vi', 'vim', 'vmstat',

            'w', 'wall', 'wc', 'wget', 'whatis', 'whereis', 'which', 'whiptail',
            'who', 'whoami', 'write',

            'xargs', 'xhost', 'xmodmap', 'xset',

            'yacc', 'yes', 'ypdomainname',

            'zcat', 'zcmp', 'zdiff', 'zdump', 'zegrep', 'zfgrep', 'zforce',
            'zgrep', 'zip', 'zipgrep', 'zipinfo', 'zless', 'zmore', 'znew',
            'zsh', 'zsoelim'
            ),
        3 => array(
            'alias', 'bg', 'bind', 'break', 'builtin', 'cd', 'command',
            'compgen', 'complete', 'continue', 'declare', 'dirs', 'disown',
            'echo', 'enable', 'eval', 'exec', 'exit', 'export', 'fc',
            'fg', 'getopts', 'hash', 'help', 'history', 'jobs', 'let',
            'local', 'logout', 'popd', 'printf', 'pushd', 'pwd', 'readonly',
            'return', 'shift', 'shopt', 'source', 'suspend', 'test', 'times',
            'trap', 'type', 'typeset', 'ulimit', 'umask', 'unalias', 'unset',
            'wait'
            )
        ),
    'SYMBOLS' => array(
        '(', ')', '[', ']', '!', '@', '%', '&', '*', '|', '/', '<', '>', ';;', '`'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #000000; font-weight: bold;',
            2 => 'color: #c20cb9; font-weight: bold;',
            3 => 'color: #7a0874; font-weight: bold;'
            ),
        'COMMENTS' => array(
            0 => 'color: #666666; font-style: italic;',
            1 => 'color: #800000;',
            2 => 'color: #cc0000; font-style: italic;',
            3 => 'color: #000000; font-weight: bold;'
            ),
        'ESCAPE_CHAR' => array(
            1 => 'color: #000099; font-weight: bold;',
            2 => 'color: #007800;',
            3 => 'color: #007800;',
            4 => 'color: #007800;',
            5 => 'color: #780078;',
            'HARD' => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #7a0874; font-weight: bold;'
            ),
        'STRINGS' => array(
            0 => 'color: #ff0000;',
            'HARD' => 'color: #ff0000;'
            ),
        'NUMBERS' => array(
            0 => 'color: #000000;'
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            0 => 'color: #000000; font-weight: bold;'
            ),
        'REGEXPS' => array(
            0 => 'color: #007800;',
            1 => 'color: #007800;',
            2 => 'color: #007800;',
            4 => 'color: #007800;',
            5 => 'color: #660033;'
            ),
        'SCRIPT' => array(
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => ''
        ),
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => array(
        ),
    'REGEXPS' => array(
        
        0 => "\\$\\{[a-zA-Z_][a-zA-Z0-9_]*?\\}",
        
        1 => "\\$[a-zA-Z_][a-zA-Z0-9_]*",
        
        2 => "(?<![\.a-zA-Z_\-])([a-zA-Z_][a-zA-Z0-9_]*?)(?==)",
        
        4 => "\\$[*#\$\\-\\?!]",
        
        5 => "(?<=\s)--?[0-9a-zA-Z\-]+(?=[\s=]|$)"
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'TAB_WIDTH' => 4,
    'PARSER_CONTROL' => array(
        'COMMENTS' => array(
            'DISALLOWED_BEFORE' => '$'
        ),
        'KEYWORDS' => array(
            'DISALLOWED_BEFORE' => "(?<![\.\-a-zA-Z0-9_\$\#])",
            'DISALLOWED_AFTER' =>  "(?![\.\-a-zA-Z0-9_%\\/])"
        )
    )
);

?>
