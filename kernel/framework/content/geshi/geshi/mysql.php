<?php













































$language_data = array (
    'LANG_NAME' => 'MySQL',
    
    'COMMENT_SINGLE' => array(
        1 =>'-- ',
        2 => '#'
        ),
    'COMMENT_REGEXP' => array(
        1 => "/(?:--\s).*?$/",                          
        ),
    'COMMENT_MULTI' => array('/*' => '*/'),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,            
    'QUOTEMARKS' => array("'", '"', '`'),
    'ESCAPE_CHAR' => '\\',                              
    'ESCAPE_REGEXP' => array(
        1 => "/[_%]/",                                  
        ),
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC |
        GESHI_NUMBER_OCT_PREFIX |
        GESHI_NUMBER_HEX_PREFIX |
        GESHI_NUMBER_FLT_NONSCI |
        GESHI_NUMBER_FLT_SCI_SHORT |
        GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => array(
        1 => array(
            
            
            'ACTION','ADD','AFTER','ALGORITHM','ALL','ALTER','ANALYZE','ANY',
            'ASC','AS','BDB','BEGIN','BERKELEYDB','BINARY','BTREE','CALL',
            'CASCADED','CASCADE','CHAIN','CHECK','COLUMNS','COLUMN','COMMENT',
            'COMMIT','COMMITTED','CONSTRAINT','CONTAINS SQL','CONSISTENT',
            'CONVERT','CREATE','CROSS','DATA','DATABASES',
            'DECLARE','DEFINER','DELAYED','DELETE','DESCRIBE','DESC',
            'DETERMINISTIC','DISABLE','DISCARD','DISTINCTROW','DISTINCT','DO',
            'DROP','DUMPFILE','DUPLICATE KEY','ENABLE','ENCLOSED BY','ENGINE',
            'ERRORS','ESCAPED BY','EXISTS','EXPLAIN','EXTENDED','FIELDS',
            'FIRST','FOR EACH ROW','FORCE','FOREIGN KEY','FROM','FULL',
            'FUNCTION','GLOBAL','GRANT','GROUP BY','HANDLER','HASH','HAVING',
            'HELP','HIGH_PRIORITY','IF NOT EXISTS','IGNORE','IMPORT','INDEX',
            'INFILE','INNER','INNODB','INOUT','INTO','INVOKER',
            'ISOLATION LEVEL','JOIN','KEYS','KEY','KILL','LANGUAGE SQL','LAST',
            'LIMIT','LINES','LOAD','LOCAL','LOCK','LOW_PRIORITY',
            'MASTER_SERVER_ID','MATCH','MERGE','MIDDLEINT','MODIFIES SQL DATA',
            'MODIFY','MRG_MYISAM','NATURAL','NEXT','NO SQL','NO','ON',
            'OPTIMIZE','OPTIONALLY','OPTION','ORDER BY','OUTER','OUTFILE','OUT',
            'PARTIAL','PREV','PRIMARY KEY','PRIVILEGES','PROCEDURE','PURGE',
            'QUICK','READS SQL DATA','READ','REFERENCES','RELEASE','RENAME',
            'REPEATABLE','REQUIRE','RESTRICT','RETURNS','REVOKE',
            'ROLLBACK','ROUTINE','RTREE','SAVEPOINT','SELECT',
            'SERIALIZABLE','SESSION','SET','SHARE MODE','SHOW','SIMPLE',
            'SNAPSHOT','SOME','SONAME','SQL SECURITY','SQL_BIG_RESULT',
            'SQL_BUFFER_RESULT','SQL_CACHE','SQL_CALC_FOUND_ROWS',
            'SQL_NO_CACHE','SQL_SMALL_RESULT','SSL','START','STARTING BY',
            'STATUS','STRAIGHT_JOIN','STRIPED','TABLESPACE','TABLES','TABLE',
            'TEMPORARY','TEMPTABLE','TERMINATED BY','TO','TRANSACTIONS',
            'TRANSACTION','TRIGGER','TYPES','TYPE','UNCOMMITTED','UNDEFINED',
            'UNION','UNLOCK_TABLES','UPDATE','USAGE','USE','USER_RESOURCES',
            'USING','VALUES','VALUE','VIEW','WARNINGS','WHERE','WITH ROLLUP',
            'WITH','WORK','WRITE',
            ),
        2 => array(     
            
            "CURRENT_USER", "DATABASE", "IN", "INSERT", "DEFAULT", "REPLACE", "SCHEMA", "TRUNCATE"
            ),
        3 => array(
            
            'FALSE','NULL','TRUE',
            ),
        4 => array(
            
            'BIGINT','BIT','BLOB','BOOLEAN','BOOL','CHARACTER VARYING',
            'CHAR VARYING','DATETIME','DECIMAL','DEC','DOUBLE PRECISION',
            'DOUBLE','ENUM','FIXED','FLOAT','GEOMETRYCOLLECTION','GEOMETRY',
            'INTEGER','INT','LINESTRING','LONGBLOB','LONGTEXT','MEDIUMBLOB',
            'MEDIUMINT','MEDIUMTEXT','MULTIPOINT','MULTILINESTRING',
            'MULTIPOLYGON','NATIONAL CHARACTER','NATIONAL CHARACTER VARYING',
            'NATIONAL CHAR VARYING','NATIONAL VARCHAR','NCHAR VARCHAR','NCHAR',
            'NUMERIC','POINT','POLYGON','REAL','SERIAL',
            'SMALLINT','TEXT','TIMESTAMP','TINYBLOB','TINYINT',
            'TINYTEXT','VARBINARY','VARCHARACTER','VARCHAR',
            ),
        5 => array(     
            
            "CHAR", "DATE", "TIME"
            ),
        6 => array(
            
            'AUTO_INCREMENT','AVG_ROW_LENGTH','BOTH','CHECKSUM','CONNECTION',
            'DATA DIRECTORY','DEFAULT NULL','DELAY_KEY_WRITE','FULLTEXT',
            'INDEX DIRECTORY','INSERT_METHOD','LEADING','MAX_ROWS','MIN_ROWS',
            'NOT NULL','PACK_KEYS','ROW_FORMAT','SERIAL DEFAULT VALUE','SIGNED',
            'SPATIAL','TRAILING','UNIQUE','UNSIGNED','ZEROFILL'
            ),
        7 => array(     
            
            "CHARSET"
            ),
        8 => array(
            
            'DAY_HOUR','DAY_MICROSECOND','DAY_MINUTE','DAY_SECOND',
            'HOUR_MICROSECOND','HOUR_MINUTE','HOUR_SECOND',
            'MINUTE_MICROSECOND','MINUTE_SECOND',
            'SECOND_MICROSECOND','YEAR_MONTH'
            ),
        9 => array(     
            
            "DAY", "HOUR", "MICROSECOND", "MINUTE", "MONTH", "QUARTER", "SECOND", "WEEK", "YEAR"
            ),
        10 => array(
            
            'AND','BETWEEN','CHARACTER SET','COLLATE','DIV','IS NOT NULL',
            'IS NOT','IS NULL','IS','LIKE','NOT','OFFSET','OR','REGEXP','RLIKE',
            'SOUNDS LIKE','XOR'
            ),
        11 => array(     
            
            "INTERVAL"
            ),
        12 => array(
            
            'CASE','ELSE','END','IFNULL','IF','NULLIF','THEN','WHEN',
            ),
        13 => array(
            
            'ASCII','BIN','BIT_LENGTH','CHAR_LENGTH','CHARACTER_LENGTH',
            'CONCAT_WS','CONCAT','ELT','EXPORT_SET','FIELD',
            'FIND_IN_SET','FORMAT','HEX','INSTR','LCASE','LEFT','LENGTH',
            'LOAD_FILE','LOCATE','LOWER','LPAD','LTRIM','MAKE_SET','MID',
            'OCTET_LENGTH','ORD','POSITION','QUOTE','REPEAT','REVERSE',
            'RIGHT','RPAD','RTRIM','SOUNDEX','SPACE','STRCMP','SUBSTRING_INDEX',
            'SUBSTRING','TRIM','UCASE','UNHEX','UPPER',
            ),
        14 => array(     
            
            "INSERT", "REPLACE", "CHAR"
            ),
        15 => array(
            
            'ABS','ACOS','ASIN','ATAN2','ATAN','CEILING','CEIL',
            'CONV','COS','COT','CRC32','DEGREES','EXP','FLOOR','LN','LOG10',
            'LOG2','LOG','MOD','OCT','PI','POWER','POW','RADIANS','RAND',
            'ROUND','SIGN','SIN','SQRT','TAN',
            ),
        16 => array(     
            
            "TRUNCATE"
            ),
        17 => array(
            
            'ADDDATE','ADDTIME','CONVERT_TZ','CURDATE','CURRENT_DATE',
            'CURRENT_TIME','CURRENT_TIMESTAMP','CURTIME','DATE_ADD',
            'DATE_FORMAT','DATE_SUB','DATEDIFF','DAYNAME','DAYOFMONTH',
            'DAYOFWEEK','DAYOFYEAR','EXTRACT','FROM_DAYS','FROM_UNIXTIME',
            'GET_FORMAT','LAST_DAY','LOCALTIME','LOCALTIMESTAMP','MAKEDATE',
            'MAKETIME','MONTHNAME','NOW','PERIOD_ADD',
            'PERIOD_DIFF','SEC_TO_TIME','STR_TO_DATE','SUBDATE','SUBTIME',
            'SYSDATE','TIME_FORMAT','TIME_TO_SEC',
            'TIMESTAMPADD','TIMESTAMPDIFF','TO_DAYS',
            'UNIX_TIMESTAMP','UTC_DATE','UTC_TIME','UTC_TIMESTAMP','WEEKDAY',
            'WEEKOFYEAR','YEARWEEK',
            ),
        18 => array(     
            
            "DATE", "DAY", "HOUR", "MICROSECOND", "MINUTE", "MONTH", "QUARTER",
            "SECOND", "TIME", "WEEK", "YEAR"
            ),
        19 => array(
            
            'COALESCE','GREATEST','ISNULL','LEAST',
            ),
        20 => array(     
            
            "IN", "INTERVAL"
            ),
        21 => array(
            
            'AES_DECRYPT','AES_ENCRYPT','COMPRESS','DECODE','DES_DECRYPT',
            'DES_ENCRYPT','ENCODE','ENCRYPT','MD5','OLD_PASSWORD','PASSWORD',
            'SHA1','SHA','UNCOMPRESS','UNCOMPRESSED_LENGTH',
            ),
        22 => array(
            
            'AVG','BIT_AND','BIT_OR','BIT_XOR','COUNT','GROUP_CONCAT',
            'MAX','MIN','STDDEV_POP','STDDEV_SAMP','STDDEV','STD','SUM',
            'VAR_POP','VAR_SAMP','VARIANCE',
            ),
        23 => array(
            
            'BENCHMARK','COERCIBILITY','COLLATION','CONNECTION_ID',
            'FOUND_ROWS','LAST_INSERT_ID','ROW_COUNT',
            'SESSION_USER','SYSTEM_USER','USER','VERSION',
            ),
        24 => array(     
            
            "CURRENT_USER", "DATABASE", "SCHEMA", "CHARSET"
            ),
        25 => array(
            
            'ExtractValue','BIT_COUNT','GET_LOCK','INET_ATON','INET_NTOA',
            'IS_FREE_LOCK','IS_USED_LOCK','MASTER_POS_WAIT','NAME_CONST',
            'RELEASE_LOCK','SLEEP','UpdateXML','UUID',
            ),
        26 => array(     
            
            "DEFAULT"
            ),
        27 => array(
            
            'Area','AsBinary','AsText','AsWKB','AsWKT','Boundary','Buffer',
            'Centroid','Contains','ConvexHull','Crosses',
            'Difference','Dimension','Disjoint','Distance',
            'EndPoint','Envelope','Equals','ExteriorRing',
            'GLength','GeomCollFromText','GeomCollFromWKB','GeomFromText',
            'GeomFromWKB','GeometryCollectionFromText',
            'GeometryCollectionFromWKB','GeometryFromText','GeometryFromWKB',
            'GeometryN','GeometryType',
            'InteriorRingN','Intersection','Intersects','IsClosed','IsEmpty',
            'IsRing','IsSimple',
            'LineFromText','LineFromWKB','LineStringFromText',
            'LineStringFromWKB',
            'MBRContains','MBRDisjoint','MBREqual','MBRIntersects',
            'MBROverlaps','MBRTouches','MBRWithin','MLineFromText',
            'MLineFromWKB','MPointFromText','MPointFromWKB','MPolyFromText',
            'MPolyFromWKB','MultiLineStringFromText','MultiLineStringFromWKB',
            'MultiPointFromText','MultiPointFromWKB','MultiPolygonFromText',
            'MultiPolygonFromWKB',
            'NumGeometries','NumInteriorRings','NumPoints',
            'Overlaps',
            'PointFromText','PointFromWKB','PointN','PointOnSurface',
            'PolyFromText','PolyFromWKB','PolygonFromText','PolygonFromWKB',
            'Related','SRID','StartPoint','SymDifference',
            'Touches',
            'Union',
            'Within',
            'X',
            'Y',
            ),
        ),
    'SYMBOLS' => array(
        1 => array(
            
            '=', ':=',                                      
            '||', '&&', '!',                                
            '=', '<=>', '>=', '>', '<=', '<', '<>', '!=',   
            '|', '&', '^', '~', '<<', '>>',                 
            '-', '+', '*', '/', '%',                        
            ),
        2 => array(
            
            '(', ')',
            ',', ';',
            ),
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false,
        7 => false,
        8 => false,
        9 => false,
        10 => false,
        11 => false,
        12 => false,
        13 => false,
        13 => false,
        14 => false,
        15 => false,
        16 => false,
        17 => false,
        18 => false,
        19 => false,
        20 => false,
        21 => false,
        22 => false,
        23 => false,
        24 => false,
        25 => false,
        26 => false,
        27 => false,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #990099; font-weight: bold;',      
            2 => 'color: #990099; font-weight: bold;',      
            3 => 'color: #9900FF; font-weight: bold;',      
            4 => 'color: #999900; font-weight: bold;',      
            5 => 'color: #999900; font-weight: bold;',      
            6 => 'color: #FF9900; font-weight: bold;',      
            7 => 'color: #FF9900; font-weight: bold;',      
            8 => 'color: #9900FF; font-weight: bold;',      
            9 => 'color: #9900FF; font-weight: bold;',      

            10 => 'color: #CC0099; font-weight: bold;',      
            11 => 'color: #CC0099; font-weight: bold;',      

            12 => 'color: #009900;',     
            13 => 'color: #000099;',     
            14 => 'color: #000099;',     
            15 => 'color: #000099;',     
            16 => 'color: #000099;',     
            17 => 'color: #000099;',     
            18 => 'color: #000099;',     
            19 => 'color: #000099;',     
            20 => 'color: #000099;',     
            21 => 'color: #000099;',     
            22 => 'color: #000099;',     
            23 => 'color: #000099;',     
            24 => 'color: #000099;',     
            25 => 'color: #000099;',     
            26 => 'color: #000099;',     
            27 => 'color: #00CC00;',     
            ),
        'COMMENTS' => array(
            'MULTI' => 'color: #808000; font-style: italic;',
            1 => 'color: #808080; font-style: italic;',
            2 => 'color: #808080; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #004000; font-weight: bold;',
            1 => 'color: #008080; font-weight: bold;'       
            ),
        'BRACKETS' => array(
            0 => 'color: #FF00FF;'
            ),
        'STRINGS' => array(
            0 => 'color: #008000;'
            ),
        'NUMBERS' => array(
            0 => 'color: #008080;'
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            1 => 'color: #CC0099;',         
            2 => 'color: #000033;',         
            ),
        'SCRIPT' => array(
            ),
        'REGEXPS' => array(
            )
        ),
    'URLS' => array(
        1 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        2 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        3 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        4 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        5 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        6 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        7 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        8 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',
        9 => 'http://search.mysql.com/search?site=refman-51&amp;q={FNAME}&amp;lr=lang_en',

        10 => 'http://dev.mysql.com/doc/refman/5.1/en/non-typed-operators.html',
        11 => 'http://dev.mysql.com/doc/refman/5.1/en/non-typed-operators.html',

        12 => 'http://dev.mysql.com/doc/refman/5.1/en/control-flow-functions.html',
        13 => 'http://dev.mysql.com/doc/refman/5.1/en/string-functions.html',
        14 => 'http://dev.mysql.com/doc/refman/5.1/en/string-functions.html',
        15 => 'http://dev.mysql.com/doc/refman/5.1/en/numeric-functions.html',
        16 => 'http://dev.mysql.com/doc/refman/5.1/en/numeric-functions.html',
        17 => 'http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html',
        18 => 'http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html',
        19 => 'http://dev.mysql.com/doc/refman/5.1/en/comparison-operators.html',
        20 => 'http://dev.mysql.com/doc/refman/5.1/en/comparison-operators.html',
        21 => 'http://dev.mysql.com/doc/refman/5.1/en/encryption-functions.html',
        22 => 'http://dev.mysql.com/doc/refman/5.1/en/group-by-functions-and-modifiers.html',
        23 => 'http://dev.mysql.com/doc/refman/5.1/en/information-functions.html',
        24 => 'http://dev.mysql.com/doc/refman/5.1/en/information-functions.html',
        25 => 'http://dev.mysql.com/doc/refman/5.1/en/func-op-summary-ref.html',
        26 => 'http://dev.mysql.com/doc/refman/5.1/en/func-op-summary-ref.html',
        27 => 'http://dev.mysql.com/doc/refman/5.1/en/analysing-spatial-information.html',
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
    'TAB_WIDTH' => 4,
    'PARSER_CONTROL' => array(
        'KEYWORDS' => array(
            2 => array(
                'DISALLOWED_AFTER' => '(?![\(\w])'
                ),
            5 => array(
                'DISALLOWED_AFTER' => '(?![\(\w])'
                ),
            7 => array(
                'DISALLOWED_AFTER' => '(?![\(\w])'
                ),
            9 => array(
                'DISALLOWED_AFTER' => '(?![\(\w])'
                ),
            11 => array(
                'DISALLOWED_AFTER' => '(?![\(\w])'
                ),

            14 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                ),
            16 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                ),
            18 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                ),
            20 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                ),
            24 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                ),
            26 => array(
                'DISALLOWED_AFTER' => '(?=\()'
                )
            )
        )
);

?>
