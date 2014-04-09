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
        
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB($dbname);
        $this->load->database($etc_privileges);
        
        $sql = "SELECT `id`, `sellernick`, `shop_sales`, `rank`, `mg`, `halfyeargoodrate` FROM `meta_cooperation` WHERE `status`>0";
        
        $data;
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $key => $item)
        {
            $data[$key]->id = $item['id'];
            $data[$key]->sellerNick = $item['sellernick'];
            $data[$key]->shopSales = $item['shop_sales'];
            $data[$key]->rank = $item['rank'];
            $data[$key]->mg = $item['mg'];
            $data[$key]->halfYearGoodRate = $item['halfyeargoodrate'];
        }
        
        echo json_encode($data);       
            
    }
    
    public function getTag()
    {
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('test');
        $this->load->database($etc_privileges);
        $data=array();
        
        $sellerNick = $this->input->get('sellerNick', true);
        $sql="SELECT `sellernick`,"
                . "`meta_cooperation_tag`.`tag`"
                . "FROM `meta_tag` "
                . "LEFT JOIN `meta_cooperation_tag` "
                . "ON `meta_tag`.`tag`=`meta_cooperation_tag`.`tag` WHERE `sellernick`='$sellerNick' ";
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $item)
        {
            $data['assignedTags'][] = $item['tag'];
        }
        
        echo json_encode($data);
    }
    
    public function updateTag()
    {
        /*
            INSERT INTO `meta_tag` (`tag`,`type`,`createtime`,`account`,`click`) 
            VALUES ('培训2','0',now(),'admin','1'),('培训3','0',now(),'abc','1')
            ON DUPLICATE KEY UPDATE `click`=`click`+1; 
         *          */
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('test');
        $this->load->database($etc_privileges);
        
        $sellerNick = $this->input->post('sellerNick', true);
        $tags = $this->input->post('tags', true);
        
        // ===========================  获取原有的tag ======================================
        $sql = "SELECT `tag` FROM `meta_cooperation_tag` WHERE `sellernick`='$sellerNick'";
        $query=$this->db->query($sql);
        foreach($query->result_array() as $item)
        {
            $sql = "UPDATE `meta_tag` SET `click`=`click`-1 WHERE `tag`='".$item['tag']."'";
            $this->db->query($sql);
            $sql = "";
        }
        
        $sql = "DELETE FROM `meta_cooperation_tag` WHERE `sellernick`='$sellerNick'";
        $query = $this->db->query($sql);
        
        //============================   tags非空的情况下更新meta_tag表  ===================================
        if($tags)
        {
            $sql ="INSERT INTO `meta_tag` (`tag`,`type`,`createtime`,`account`,`click`) VALUES ";
            $dotFlag=true;
            foreach($tags as $v)
            {
                if($dotFlag)
                {
                    $sql.= "('$v','0','". date('Y-m-d H:i:s') ."','". $this->session->userdata('username') ."','1')";
                    $dotFlag = false;
                }
                else
                {
                    $sql.=",('$v','0','". date('Y-m-d H:i:s') ."','". $this->session->userdata('username') ."','1')";
                }
            }

            $sql.= " ON DUPLICATE KEY UPDATE `click`=`click`+1";
            $this->db->query($sql);
        }
        
               
        //====================================  tags非空的情况下更新meta_cooperation_tag表  =============================
        if($tags)
        {
            $sql = "INSERT INTO `meta_cooperation_tag` (`createtime`, `sellernick`, `tag`) VALUES ";     
            $dotFlag=true;
            foreach($tags as $v)
            {
                if($dotFlag)
                {
                    $sql.= "('". date('Y-m-d H:i:s') ."','$sellerNick','$v')";
                    $dotFlag = false;
                }
                else
                {
                    $sql.= ",('". date('Y-m-d H:i:s') ."','$sellerNick','$v')";
                }
            }       
            $this->db->query($sql);
        }
        
        echo json_encode("true");
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