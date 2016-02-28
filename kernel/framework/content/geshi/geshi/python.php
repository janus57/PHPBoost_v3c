<?php












































$language_data = array (
    'LANG_NAME' => 'Python',
    'COMMENT_SINGLE' => array(1 => '#'),
    'COMMENT_MULTI' => array(),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    
    'QUOTEMARKS' => array('"""', '"', "'"),
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => array(

        



        1 => array(
            'and', 'del', 'for', 'is', 'raise', 'assert', 'elif', 'from', 'lambda', 'return', 'break',
            'else', 'global', 'not', 'try', 'class', 'except', 'if', 'or', 'while', 'continue', 'exec',
            'import', 'pass', 'yield', 'def', 'finally', 'in', 'print', 'with', 'as'
            ),

        



        2 => array(
            '__import__', 'abs', 'basestring', 'bool', 'callable', 'chr', 'classmethod', 'cmp',
            'compile', 'complex', 'delattr', 'dict', 'dir', 'divmod', 'enumerate', 'eval', 'execfile',
            'file', 'filter', 'float', 'frozenset', 'getattr', 'globals', 'hasattr', 'hash', 'help',
            'hex', 'id', 'input', 'int', 'isinstance', 'issubclass', 'iter', 'len', 'list', 'locals',
            'long', 'map', 'max', 'min', 'object', 'oct', 'open', 'ord', 'pow', 'property', 'range',
            'raw_input', 'reduce', 'reload', 'reversed', 'round', 'set', 'setattr', 'slice',
            'sorted', 'staticmethod', 'str', 'sum', 'super', 'tuple', 'type', 'unichr', 'unicode',
            'vars', 'xrange', 'zip',
            
            'False', 'True', 'None', 'NotImplemented', 'Ellipsis',
            
            'Exception', 'StandardError', 'ArithmeticError', 'LookupError', 'EnvironmentError',
            'AssertionError', 'AttributeError', 'EOFError', 'FloatingPointError', 'IOError',
            'ImportError', 'IndexError', 'KeyError', 'KeyboardInterrupt', 'MemoryError', 'NameError',
            'NotImplementedError', 'OSError', 'OverflowError', 'ReferenceError', 'RuntimeError',
            'StopIteration', 'SyntaxError', 'SystemError', 'SystemExit', 'TypeError',
            'UnboundlocalError', 'UnicodeError', 'UnicodeEncodeError', 'UnicodeDecodeError',
            'UnicodeTranslateError', 'ValueError', 'WindowsError', 'ZeroDivisionError', 'Warning',
            'UserWarning', 'DeprecationWarning', 'PendingDeprecationWarning', 'SyntaxWarning',
            'RuntimeWarning', 'FutureWarning',
            
            'self',
            
            'any', 'all'
            ),

        



        3 => array(
            '__builtin__', '__future__', '__main__', '_winreg', 'aifc', 'AL', 'al', 'anydbm',
            'array', 'asynchat', 'asyncore', 'atexit', 'audioop', 'base64', 'BaseHTTPServer',
            'Bastion', 'binascii', 'binhex', 'bisect', 'bsddb', 'bz2', 'calendar', 'cd', 'cgi',
            'CGIHTTPServer', 'cgitb', 'chunk', 'cmath', 'cmd', 'code', 'codecs', 'codeop',
            'collections', 'colorsys', 'commands', 'compileall', 'compiler',
            'ConfigParser', 'Cookie', 'cookielib', 'copy', 'copy_reg', 'cPickle', 'crypt',
            'cStringIO', 'csv', 'curses', 'datetime', 'dbhash', 'dbm', 'decimal', 'DEVICE',
            'difflib', 'dircache', 'dis', 'distutils', 'dl', 'doctest', 'DocXMLRPCServer', 'dumbdbm',
            'dummy_thread', 'dummy_threading', 'email', 'encodings', 'errno', 'exceptions', 'fcntl',
            'filecmp', 'fileinput', 'FL', 'fl', 'flp', 'fm', 'fnmatch', 'formatter', 'fpectl',
            'fpformat', 'ftplib', 'gc', 'gdbm', 'getopt', 'getpass', 'gettext', 'GL', 'gl', 'glob',
            'gopherlib', 'grp', 'gzip', 'heapq', 'hmac', 'hotshot', 'htmlentitydefs', 'htmllib',
            'HTMLParser', 'httplib', 'imageop', 'imaplib', 'imgfile', 'imghdr', 'imp', 'inspect',
            'itertools', 'jpeg', 'keyword', 'linecache', 'locale', 'logging', 'mailbox', 'mailcap',
            'marshal', 'math', 'md5', 'mhlib', 'mimetools', 'mimetypes', 'MimeWriter', 'mimify',
            'mmap', 'msvcrt', 'multifile', 'mutex', 'netrc', 'new', 'nis', 'nntplib', 'operator',
            'optparse', 'os', 'ossaudiodev', 'parser', 'pdb', 'pickle', 'pickletools', 'pipes',
            'pkgutil', 'platform', 'popen2', 'poplib', 'posix', 'posixfile', 'pprint', 'profile',
            'pstats', 'pty', 'pwd', 'py_compile', 'pyclbr', 'pydoc', 'Queue', 'quopri', 'random',
            're', 'readline', 'repr', 'resource', 'rexec', 'rfc822', 'rgbimg', 'rlcompleter',
            'robotparser', 'sched', 'ScrolledText', 'select', 'sets', 'sgmllib', 'sha', 'shelve',
            'shlex', 'shutil', 'signal', 'SimpleHTTPServer', 'SimpleXMLRPCServer', 'site', 'smtpd',
            'smtplib', 'sndhdr', 'socket', 'SocketServer', 'stat', 'statcache', 'statvfs', 'string',
            'StringIO', 'stringprep', 'struct', 'subprocess', 'sunau', 'SUNAUDIODEV', 'sunaudiodev',
            'symbol', 'sys', 'syslog', 'tabnanny', 'tarfile', 'telnetlib', 'tempfile', 'termios',
            'test', 'textwrap', 'thread', 'threading', 'time', 'timeit', 'Tix', 'Tkinter', 'token',
            'tokenize', 'traceback', 'tty', 'turtle', 'types', 'unicodedata', 'unittest', 'urllib2',
            'urllib', 'urlparse', 'user', 'UserDict', 'UserList', 'UserString', 'uu', 'warnings',
            'wave', 'weakref', 'webbrowser', 'whichdb', 'whrandom', 'winsound', 'xdrlib', 'xml',
            'xmllib', 'xmlrpclib', 'zipfile', 'zipimport', 'zlib',
            
            'bytes', 'bytearray'
            ),

        



        4 => array(
            









            
            '__new__', '__init__', '__del__', '__repr__', '__str__',
            '__lt__', '__le__', '__eq__', '__ne__', '__gt__', '__ge__', '__cmp__', '__rcmp__',
            '__hash__', '__nonzero__', '__unicode__', '__dict__',
            
            '__setattr__', '__delattr__', '__getattr__', '__getattribute__', '__get__', '__set__',
            '__delete__', '__slots__',
            
            '__metaclass__', '__call__',
            
            '__len__', '__getitem__', '__setitem__', '__delitem__', '__iter__', '__contains__',
            '__getslice__', '__setslice__', '__delslice__',
            
            '__abs__','__add__','__and__','__coerce__','__div__','__divmod__','__float__',
            '__hex__','__iadd__','__isub__','__imod__','__idiv__','__ipow__','__iand__',
            '__ior__','__ixor__', '__ilshift__','__irshift__','__invert__','__int__',
            '__long__','__lshift__',
            '__mod__','__mul__','__neg__','__oct__','__or__','__pos__','__pow__',
            '__radd__','__rdiv__','__rdivmod__','__rmod__','__rpow__','__rlshift__','__rrshift__',
            '__rshift__','__rsub__','__rmul__','__rand__','__rxor__','__ror__',
            '__sub__','__xor__'
            )
        ),
    'SYMBOLS' => array(
            '(', ')', '[', ']', '{', '}', '*', '&', '%', '!', ';', '<', '>', '?', '`'
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
            1 => 'color: #ff7700;font-weight:bold;',    
            2 => 'color: #008000;',                        
            3 => 'color: #dc143c;',                        
            4 => 'color: #0000cd;'                        
            ),
        'COMMENTS' => array(
            1 => 'color: #808080; font-style: italic;',
            'MULTI' => 'color: #808080; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: black;'
            ),
        'STRINGS' => array(
            0 => 'color: #483d8b;'
            ),
        'NUMBERS' => array(
            0 => 'color: #ff4500;'
            ),
        'METHODS' => array(
            1 => 'color: black;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #66cc66;'
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
        )
);

?>
