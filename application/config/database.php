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

$db['default']['hostname'] = '192.168.1.90';
$db['default']['username'] = 'data';
$db['default']['password'] = 'data2123';
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
//三枪项目
$db['db_sanqiang']['hostname'] = '127.0.0.1';
$db['db_sanqiang']['username'] = 'webuser';
$db['db_sanqiang']['password'] = 'password';
$db['db_sanqiang']['database'] = 'db_sanqiang';
$db['db_sanqiang']['dbdriver'] = 'mysql';
$db['db_sanqiang']['dbprefix'] = '';
$db['db_sanqiang']['pconnect'] = TRUE;
$db['db_sanqiang']['db_debug'] = TRUE;
$db['db_sanqiang']['cache_on'] = FALSE;
$db['db_sanqiang']['cachedir'] = '';
$db['db_sanqiang']['char_set'] = 'utf8';
$db['db_sanqiang']['dbcollat'] = 'utf8_general_ci';
$db['db_sanqiang']['swap_pre'] = '';
$db['db_sanqiang']['autoinit'] = TRUE;
$db['db_sanqiang']['stricton'] = FALSE;

//汤臣倍健项目
$db['db_tcbj']['hostname'] = '127.0.0.1';
$db['db_tcbj']['username'] = 'webuser';
$db['db_tcbj']['password'] = 'password';
$db['db_tcbj']['database'] = 'db_tcbj';
$db['db_tcbj']['dbdriver'] = 'mysql';
$db['db_tcbj']['dbprefix'] = '';
$db['db_tcbj']['pconnect'] = TRUE;
$db['db_tcbj']['db_debug'] = TRUE;
$db['db_tcbj']['cache_on'] = FALSE;
$db['db_tcbj']['cachedir'] = '';
$db['db_tcbj']['char_set'] = 'utf8';
$db['db_tcbj']['dbcollat'] = 'utf8_general_ci';
$db['db_tcbj']['swap_pre'] = '';
$db['db_tcbj']['autoinit'] = TRUE;
$db['db_tcbj']['stricton'] = FALSE;

$db['etc_privileges']['hostname'] = '127.0.0.1';
$db['etc_privileges']['username'] = 'webuser';
$db['etc_privileges']['password'] = 'password';
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