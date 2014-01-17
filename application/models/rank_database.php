<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Rank_database extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
        
        /*
         *  使用实例： 
         *  一切采用用户手册的标准，以下是获取已选择数据库的etc_project表里的所有字段 
         *  $query = $this->db->get('etc_project');
         *  return $query->result_array();
         */
    }
    
    public function get_kpi_supplier_daily()
    {
        $query = $this->db->get('kpi_supplier_daily');
        return $query->result_array();  
    }


    /*
    *   funtion selectDB: 通过传入数据库名，选择数据库 
    *   parameters : $databaseName -- 要选择的数据库名
    *   return : 数据库连接对象
    *   Author : zhang bo
    *   Last modified : 2013.12.30
    */
    public function select_DB($databaseName)
    {
        /*
        $db_config['hostname'] = '192.168.1.90';
        $db_config['username'] = 'data';
        $db_config['password'] = 'data2123';
        */
        $db_config['hostname'] = '127.0.0.1';
        $db_config['username'] = 'webuser';
        $db_config['password'] = 'password';
        
        $db_config['database'] = $databaseName;
        $db_config['dbdriver'] = 'mysqli';
        $db_config['dbprefix'] = '';
        $db_config['pconnect'] = TRUE;
        $db_config['db_debug'] = TRUE;
        $db_config['cache_on'] = FALSE;
        $db_config['cachedir'] = '';
        $db_config['char_set'] = 'utf8';
        $db_config['dbcollat'] = 'utf8_bin';
        $db_config['swap_pre'] = '';
        $db_config['autoinit'] = TRUE;
        $db_config['stricton'] = FALSE;
        
        return $db_config;
    }
}
