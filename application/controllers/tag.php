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
        
        //20140429 SQL更新
        
        /*sql="SELECT `id`, `meta_cooperation`.`sellernick`, `shop_sales`, `c`.`sales_30`, `up_rate_7`, `order_fee_30`, `order_fee_60` 
FROM `meta_cooperation`
LEFT JOIN
(
	SELECT `sellernick`, sum(if(`created`>=date_sub(curdate(),INTERVAL 30 DAY),`number`,0)) as `sales_30` 
	FROM `status_order` 
	GROUP BY `sellernick`
) AS c
ON `meta_cooperation`.`sellernick`=`c`.`sellernick`
WHERE `status`>'0'";*/
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
        $sellerNick = $this->input->get('sellerNick', true);
        $db = $this->input->get('db', true);
        $etc_privileges = $this->rank_database->select_DB($db);
        $this->load->database($etc_privileges);
        $data=array();
        
        
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
        */
        $sellerNick = $this->input->post('sellerNick', true);
        $tags = $this->input->post('tags', true);
        $db = $this->input->post('db', true);
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB($db);
        $this->load->database($etc_privileges);
             
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
}