<?php




























define('DB_CONFIG_SUCCESS', 0);

define('DB_CONFIG_ERROR_CONNECTION_TO_DBMS', 1);

define('DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED', 2);

define('DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED', 3);

define('DB_CONFIG_ERROR_TABLES_ALREADY_EXIST', 4);

define('DB_UNKNOW_ERROR', -1);


function check_database_config(&$host, &$login, &$password, &$database_name, $tables_prefix)
{
	import('db/mysql');
	import('core/errors');
	
	
	$Errorh = new Errors;
	$Sql = new Sql;
	
	$status = CONNECTION_FAILED;
	
	$database_name = Sql::clean_database_name($database_name);
	
	
	switch ($Sql->connect($host, $login, $password, $database_name, ERRORS_MANAGEMENT_BY_RETURN))
	{
		
		case CONNECTION_FAILED:
			return DB_CONFIG_ERROR_CONNECTION_TO_DBMS;
		
		case UNEXISTING_DATABASE:
			
			$database_name = $Sql->create_database($database_name);
			
			
			$databases_list = $Sql->list_databases();
			
			$Sql->close();
			
			if (in_array($database_name, $databases_list))
			{
				return DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED;
			}
			else
			{
				return DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED;
			}
		
		case CONNECTED_TO_DATABASE:
			
			define('PREFIX', $tables_prefix);
			$tables_list = $Sql->list_tables();
			
			
			$Sql->close();

			
			if (!empty($tables_list[$tables_prefix . 'member']) || !empty($tables_list[$tables_prefix . 'configs']))
			{
				return DB_CONFIG_ERROR_TABLES_ALREADY_EXIST;
			}
			
			return DB_CONFIG_SUCCESS;
	}
}

function load_db_connection()
{
	global $Sql, $Errorh;
	
	import('core/errors');
	$Errorh = new Errors;
	import('db/mysql');
	$Sql = new Sql;
	$Sql->auto_connect();
}

?>
