<?php

/* 
 * File : admin.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */

class Admin extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();      
    }
    
    public function index( $page = 'index' )
    {
        if ( ! file_exists('application/views/main/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "第一时间";

        $this->load->view('templates/header', $data);
        $this->load->view('admin/header_add_index');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function index_data()
    {
        $this->load->model('rank_database');
        
        $isZC = $this->input->post('is_zc',TRUE);
        $db = $this->input->post('db', TRUE);
        $db_selected = $this->rank_database->select_DB($db);
        $this->load->database($db_selected);
        
        //-------------------------------- 第一和第二部分 : 分销商销售额 和分销商数量 --------------------------------------------------
        $sql;
        $sql2;
        $data;//用于接收所有数据
        $n=4;//展示4天的数据
        if($isZC == 'true')
        {
            $sql = "SELECT `updatetime`, `order_sales_fee_ex` as `sales`, `seller_num_ex` as `seller_num` FROM `status_kpi_day` order by `updatetime` DESC LIMIT ".$n;
            
        }
        else
        {
            $sql = "SELECT `updatetime`, `order_sales_fee` as `sales`, `seller_num` as `seller_num` FROM `status_kpi_day` order by `updatetime` DESC LIMIT ".$n;
        }
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $item)
        {
            $data['sales_1'][] = $item['sales'];
            $data['seller_num_2'][] = $item['seller_num'];
            $data['updatetime_1'][] = $item['updatetime'];
        }        
        
        //-------------------------------- 第三部分 : 上架率  -----------------------------------------------------
        //由于周末数据没有，所以第三部分和第一二部分日期不同，使用updatetime_3储存
        if($isZC == 'true')
        {
            //先查询上架商家数（追灿招募）
            $sql="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `up_number` >0 AND `sellernick` in 
                (SELECT `sellernick` FROM `meta_cooperation` WHERE `is_zhuican` = '1') 
                GROUP BY `updatetime` ORDER BY `updatetime` DESC LIMIT ".$n;
            //在查询所有（包括未上架）商家数（追灿招募）
            $sql2="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `sellernick` in 
                (SELECT `sellernick` FROM `meta_cooperation` WHERE `is_zhuican` = '1') 
                GROUP BY `updatetime` ORDER BY `updatetime` DESC LIMIT ".$n;
            
        }
        else
        {
            //查询上架商家数（全部）
            $sql="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `up_number` >0 
                GROUP BY `updatetime` 
                ORDER BY `updatetime` DESC LIMIT ".$n;
            //查询所有（包括未上架）商家数（全部）
            $sql2="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` GROUP BY `updatetime` ORDER BY `updatetime` DESC LIMIT ".$n;
        }
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $item)
        {
            $data['up_rate_3'][] = $item['up_num'];
            $data['updatetime_3'][] = $item['updatetime'];
        }
        
        $query = $this->db->query($sql2);
        $i=0;
        foreach($query->result_array() as $item)
        {
            $data['up_rate_3'][$i] /= $item['up_num'];
            //转换为百分数
            $data['up_rate_3'][$i] *=100;
            $i++;
        }
        
        //echo json_encode($data);
        echo json_encode($data, JSON_NUMERIC_CHECK );
    }
    
    
}

