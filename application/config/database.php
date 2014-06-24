<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = 'root';
$db['default']['database'] = 'db_sanqiang';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

//数据仓库管理库
$etc_privileges['hostname'] = '127.0.0.1';
$etc_privileges['username'] = 'root';
$etc_privileges['password'] = 'root';
$etc_privileges['database'] = 'etc_privileges';
$etc_privileges['dbdriver'] = 'mysql';
$etc_privileges['dbprefix'] = '';
$etc_privileges['pconnect'] = FALSE;
$etc_privileges['db_debug'] = TRUE;
$etc_privileges['cache_on'] = FALSE;
$etc_privileges['cachedir'] = '';
$etc_privileges['char_set'] = 'utf8';
$etc_privileges['dbcollat'] = 'utf8_general_ci';
$etc_privileges['swap_pre'] = '';
$etc_privileges['autoinit'] = TRUE;
$etc_privileges['stricton'] = FALSE;


//test库
$test['hostname'] = '192.168.1.90';
$test['username'] = 'data';
$test['password'] = 'data2123';
$test['database'] = 'test';
$test['dbdriver'] = 'mysql';
$test['dbprefix'] = '';
$test['pconnect'] = FALSE;
$test['db_debug'] = TRUE;
$test['cache_on'] = FALSE;
$test['cachedir'] = '';
$test['char_set'] = 'utf8';
$test['dbcollat'] = 'utf8_general_ci';
$test['swap_pre'] = '';
$test['autoinit'] = TRUE;
$test['stricton'] = FALSE;

$db['etc_privileges']['hostname'] = '127.0.0.1';
$db['etc_privileges']['username'] = 'root';
$db['etc_privileges']['password'] = 'root';
$db['etc_privileges']['database'] = 'etc_privileges';
$db['etc_privileges']['dbdriver'] = 'mysql';
$db['etc_privileges']['dbprefix'] = '';
$db['etc_privileges']['pconnect'] = TRUE;
$db['etc_privileges']['db_debug'] = TRUE;
$db['etc_privileges']['cache_on'] = FALSE;
$db['etc_privileges']['cachedir'] = '';
$db['etc_privileges']['char_set'] = 'utf8';
$db['etc_privileges']['dbcollat'] = 'utf8_general_ci';
$db['etc_privileges']['swap_pre'] = '';
$db['etc_privileges']['autoinit'] = TRUE;
$db['etc_privileges']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */