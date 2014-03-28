<?php

/* 
 * File : tag.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-3-12
 */
class Tag extends CI_Controller
{
    function __construct() {
        parent::__construct();
        {
            $this->load->library('session');
        }
    }
    
    public function addTag()
    {
         
        $data['title']='项目标签管理';
        
        $this->load->view('templates/header',$data);
        $this->load->view('tag/header_add_addTag');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('tag/addTag');
        $this->load->view('templates/footer');
    }
    
    public function loadField()
    {
        $dbname = $this->input->post('db',TRUE);
        
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB($dbname);
        $this->load->database($etc_privileges);
        
        $sql="SHOW FULL FIELDS FROM `meta_cooperation`";
        $query = $this->db->query($sql);
        
        foreach ($query->result_array() as $item)
        {
            $data['field'][]=$item['Field'];
            $data['comment'][]=$item['Comment'];
        }
        
        echo json_encode($data);
    }
    
    public function getList()
    {
        $dbname=$this->input->post('db', true);
        $salesNumFrom = $this->input->post('salesNumFrom', true);
        $salesNumTo = $this->input->post('salesNumTo', true);
        $rankFrom = $this->input->post('rankFrom', true);
        $rankTo = $this->input->post('rankTo', true);
        $descFrom = $this->input->post('descFrom', true);
        $descTo = $this->input->post('descTo', true);
        $goodRateFrom = $this->input->post('goodRateFrom', true);
        $goodRateTo = $this->input->post('goodRateTo', true);       
        
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB($dbname);
        $this->load->database($etc_privileges);
        
        $sql = "SELECT `sellernick`, `shop_sales`, `rank`, `mg`, `halfyeargoodrate` FROM `meta_cooperation` WHERE `status`>0";
        
        $data;
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $key => $item)
        {
            $data[$key]->sellerNick = $item['sellernick'];
            $data[$key]->shopSales = $item['shop_sales'];
            $data[$key]->rank = $item['rank'];
            $data[$key]->mg = $item['mg'];
            $data[$key]->halfYearGoodRate = $item['halfyeargoodrate'];
        }
        
        echo json_encode($data);
        
        /*//月销笔数范围选择
        if($salesNumFrom!="")
            $sql.=" AND `sales` >= ". $salesNumFrom ."";
        if($salesNumTo!="")
            $sql.=" AND `sales` <= ". $salesNumTo ."";
        
        //店铺等级
        if($rankFrom!="")
        {
            $rateNumberFrom=  $this->getRankFrom($rankFrom);
            $sql.=" AND `ratenumber` > ". $rateNumberFrom ."";
        }                 
       if($rankTo!="" && $rankTo!=20)
       {
            $rateNumberTo=  $this->getRankTo($rankTo);
            $sql.=" AND `ratenumber` < ". $rateNumberTo ."";
        }
        
        //描述相符高于
        if($descFrom!="")
            $sql.=" AND `mg` >= ". $descFrom ."";
        if($descTo!="")
            $sql.=" AND `mg` <= ". $descTo ."";
        
        //好评/差评比
        if($goodRateFrom!="")
            $sql.=" AND `halfyeargoodrate` >= ". $goodRateFrom ."";
        if($goodRateTo!="")
            $sql.=" AND `halfyeargoodrate` <= ". $goodRateTo ."";*/
        
        
       
            
    }
    
    public function getRankTo($rankTo)
    {
        switch ($rankTo)
            {
                case '1':
                    return 11;
                case '2':
                    return 41;
                case '3':
                    return 91;
                case '4':
                    return 151;
                case '5':
                    return 251;
                case '6':
                    return 501;
                case '7':
                    return 1001;
                case '8':
                    return 2001;
                case '9':
                    return 5001;
                case '10':
                    return 10001;
                case '11':
                    return 20001;
                case '12':
                    return 50001;
                case '13':
                    return 100001;
                case '14':
                    return 200001;
                case '15':
                    return 500001;
                case '16':
                    return 1000001;
                case '17':
                    return 2000001;
                case '18':
                    return 5000001;
                case '19':
                    return 10000001;
                default :
                    return 0;
            }
    }
    
    public function getRankFrom($rankFrom)
    {
        switch ($rankFrom)
            {
                case '1':
                    return 3;
                case '2':
                    return 10;
                case '3':
                    return 40;
                case '4':
                    return 90;
                case '5':
                    return 150;
                case '6':
                    return 250;
                case '7':
                    return 500;
                case '8':
                    return 1000;
                case '9':
                    return 2000;
                case '10':
                    return 5000;
                case '11':
                    return 10000;
                case '12':
                    return 20000;
                case '13':
                    return 50000;
                case '14':
                    return 100000;
                case '15':
                    return 200000;
                case '16':
                    return 500000;
                case '17':
                    return 1000000;
                case '18':
                    return 2000000;
                case '19':
                    return 5000000;
                case '20':
                    return 10000000;
                default :
                    return 0;
            }
    }
}