<?php
















































$language_data = array (
    'LANG_NAME' => 'Apache configuration',
    'COMMENT_SINGLE' => array(1 => '#'),
    'COMMENT_MULTI' => array(),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array("'", '"'),
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => array(
        
        1 => array(
            'accessconfig','accessfilename','action','addalt',
            'addaltbyencoding','addaltbytype','addcharset',
            'adddefaultcharset','adddescription',
            'addencoding','addhandler','addicon','addiconbyencoding',
            'addiconbytype','addlanguage','addmodule','addmoduleinfo',
            'addtype','agentlog','alias','aliasmatch',
            'allow','allowconnect','allowoverride','anonymous',
            'anonymous_authoritative','anonymous_logemail','anonymous_mustgiveemail',
            'anonymous_nouserid','anonymous_verifyemail','authauthoritative',
            'authdbauthoritative','authdbgroupfile','authdbmauthoritative',
            'authdbmgroupfile','authdbuserfile','authdbmuserfile',
            'authdigestfile','authgroupfile','authname','authtype',
            'authuserfile','bindaddress','browsermatch','browsermatchnocase',
            'bs2000account','cachedefaultexpire','cachedirlength','cachedirlevels',
            'cacheforcecompletion','cachegcinterval','cachelastmodifiedfactor','cachemaxexpire',
            'cachenegotiateddocs','cacheroot','cachesize','checkspelling',
            'clearmodulelist','contentdigest','cookieexpires','cookielog',
            'cookietracking','coredumpdirectory','customlog',
            'defaulticon','defaultlanguage','defaulttype','define',
            'deny','directory','directorymatch','directoryindex',
            'documentroot','errordocument','errorlog','example',
            'expiresactive','expiresbytype','expiresdefault','extendedstatus',
            'fancyindexing','files','filesmatch','forcetype',
            'group','header','headername','hostnamelookups',
            'identitycheck','ifdefine','ifmodule','imapbase',
            'imapdefault','imapmenu','include','indexignore','indexorderdefault',
            'indexoptions','keepalive','keepalivetimeout','languagepriority',
            'limit','limitexcept','limitrequestbody','limitrequestfields',
            'limitrequestfieldsize','limitrequestline','listen','listenbacklog',
            'loadfile','loadmodule','location','locationmatch',
            'lockfile','logformat','loglevel','maxclients',
            'maxkeepaliverequests','maxrequestsperchild','maxspareservers','maxsparethreads','metadir',
            'metafiles','metasuffix','mimemagicfile','minspareservers','minsparethreads',
            'mmapfile','namevirtualhost','nocache','options','order',
            'passenv','php_admin_value','php_admin_flag','php_value','pidfile','port','proxyblock','proxydomain',
            'proxypass','proxypassreverse','proxyreceivebuffersize','proxyremote',
            'proxyrequests','proxyvia','qsc','readmename',
            'redirect','redirectmatch','redirectpermanent','redirecttemp',
            'refererignore','refererlog','removehandler','require',
            'resourceconfig','rewritebase','rewritecond','rewriteengine',
            'rewritelock','rewritelog','rewriteloglevel','rewritemap',
            'rewriteoptions','rewriterule','rlimitcpu','rlimitmem',
            'rlimitnproc','satisfy','scoreboardfile','script',
            'scriptalias','scriptaliasmatch','scriptinterpretersource','scriptlog',
            'scriptlogbuffer','scriptloglength','sendbuffersize',
            'serveradmin','serveralias','servername','serverpath',
            'serverroot','serversignature','servertokens','servertype',
            'setenv','setenvif','setenvifnocase','sethandler',
            'singlelisten','startservers','threadsperchild','timeout',
            'transferlog','typesconfig','unsetenv','usecanonicalname',
            'user','userdir','virtualhost','virtualdocumentroot',
            'virtualdocumentrootip','virtualscriptalias','virtualscriptaliasip',
            'xbithack','from','all'
            ),
        
        2 => array(
            'on','off','standalone','inetd','indexes',
            'force-response-1.0','downgrade-1.0','nokeepalive',
            'ndexes','includes','followsymlinks','none',
            'x-compress','x-gzip'
        ),
        
        3 => array(
            'Directory',
            'DirectoryMatch',
            'Files',
            'FilesMatch',
            'IfDefine',
            'IfModule',
            'IfVersion',
            'Location',
            'LocationMatch',
            'Proxy',
            'ProxyMatch',
            'VirtualHost'
        )
    ),
    'SYMBOLS' => array(
        '+', '-'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #00007f;',
            2 => 'color: #0000ff;',
            3 => 'color: #000000; font-weight:bold;',
            ),
        'COMMENTS' => array(
            1 => 'color: #adadad; font-style: italic;',
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #339933;'
            ),
        'STRINGS' => array(
            0 => 'color: #7f007f;'
            ),
        'NUMBERS' => array(
            0 => 'color: #ff0000;'
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            0 => 'color: #008000;'
            ),
        'REGEXPS' => array(
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
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'PARSER_CONTROL' => array(
        'ENABLE_FLAGS' => array(
            'BRACKETS' => GESHI_NEVER,
            'SYMBOLS' => GESHI_NEVER
        ),
        'KEYWORDS' => array(
            3 => array(
                'DISALLOWED_BEFORE' => '(?<=&lt;|&lt;\/)',
                'DISALLOWED_AFTER' => '(?=\s|\/|&gt;)',
            )
        )
    )
);

?>
