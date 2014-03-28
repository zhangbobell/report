<?php
class Rank_list extends CI_Controller{
    
    //分销商销量增长率排行榜
    public function sales_rate_rank(){

        $data['title']='分销商销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-sales-rate');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //分销商销量排行榜
    public function sales_rank(){

        $data['title']='分销商销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-sales');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量增长率排行榜
    public function product_sales_rate_rank(){

        $data['title']='产品销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-product-sales-rate');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/product_sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量排行榜
    public function product_sales_rank(){

        $data['title']='产品销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-product-sales');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/product_sales_rank');
        $this->load->view('templates/footer');
    }
    
    public function seller_num(){
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select createtime,number from meta_order limit 10';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    public function seller_num_rate(){
         $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select sellernick,sum(number) as sum_number from meta_order where createtime between "2013-12-01" and "2013-12-31" group by sellernick order by sum_number desc';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    
     public function product_num(){
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select createtime,sku,number from meta_order limit 10';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
     public function product_num_rate(){
         $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select sku,sum(number) as sum_number from meta_order where createtime between "2013-12-01" and "2013-12-31" group by sku order by sum_number desc';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    
    //分销商销量增长率排行榜
    public function sales_rate_rank_data( )
    {
        header('Content-Type: text/html; charset=utf-8');
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
        $prior_prior = "date_sub(curdate(), interval ". ($time*2) ." day)";
        
        $data;
        $sql = "SELECT `sellernick`, 
                sum(if(`created`>=". $last_prior .",`number`,0)) as `curSalesNum`, 
                sum(if(`created`<". $last_prior .",`number`,0)) as `lastSalesNum` "
                . "FROM `meta_order` "
                . "WHERE `created`>=". $prior_prior ."  
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%') 
                and `sellernick` is not NULL";
        
        if($isZC == 'true')
        {
            $sql .= " and `sellernick` not in (select `sellernick` from `up_cooperation_register`)"; 
        }
        
        $sql.=" GROUP BY `sellernick`";
        
        $query=  $this->db->query($sql);
        foreach($query->result_array() as $key => $item)
        {
            $data[$key]->curSalesNum=$item['curSalesNum'];
            $data[$key]->lastSalesNum=$item['lastSalesNum'];
            $data[$key]->sellernick=$item['sellernick'];
            if($item['lastSalesNum']!=0)
                $data[$key]->sales_rate=($item['curSalesNum']-$item['lastSalesNum'])/$item['lastSalesNum'];
            else
                $data[$key]->sales_rate=NULL;
        }
        //var_dump($data);
        echo json_encode($data);
         
    }
    
 
    
    //分销商销量排行榜
     public function sales_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
     
        $data;
        $sql = "SELECT `sellernick`,
                sum(if(`created`<=". $last_last .",`number`,0)) as `salesNum` "
                . "FROM `meta_order` "
                . "WHERE `created`>=". $last_prior ."  
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%') 
                and `sellernick` is not NULL";
        
        if($isZC == 'true')
        {
            $sql .= " and `sellernick` not in (select `sellernick` from `up_cooperation_register`)"; 
        }
        
        $sql.=" GROUP BY `sellernick`";
        
        $query=  $this->db->query($sql);
        foreach($query->result_array() as $key => $item)
        {
            $data[$key]->salesNum=$item['salesNum'];
            $data[$key]->sellernick=$item['sellernick'];
        }
        echo json_encode($data);      
    }
    
    //产品销量增长率排行榜
    public function product_sales_rate_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
        $prior_prior = "date_sub(curdate(), interval ". ($time*2) ." day)";
        
        $data;
        $sql = "SELECT `itemnum`,`price`, 
                sum(if(`created`>=". $last_prior .",`number`,0)) as `curSalesNum`, 
                sum(if(`created`<". $last_prior .",`number`,0)) as `lastSalesNum` "
                . "FROM `meta_order` "
                . "WHERE `created`>=". $prior_prior ."  
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%') 
                and `sellernick` is not NULL";
               
        $sql.=" GROUP BY `itemnum`";
        
        $query=  $this->db->query($sql);
        foreach($query->result_array() as $key => $item)
        {
            $data[$key]->curSalesNum=$item['curSalesNum'];
            $data[$key]->lastSalesNum=$item['lastSalesNum'];
            $data[$key]->price=$item['price'];
            $data[$key]->itemnum=$item['itemnum'];
            if($item['lastSalesNum']!=0)
                $data[$key]->sales_rate=($item['curSalesNum']-$item['lastSalesNum'])/$item['lastSalesNum'];
            else
                $data[$key]->sales_rate=NULL;
        }
        //var_dump($data);
        echo json_encode($data);      
    }
    
    //产品销量排行榜
     public function product_sales_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
     
        $data;
        $sql = "SELECT `itemnum`,`price`, 
                sum(if(`created`<=". $last_last .",`number`,0)) as `salesNum` "
                . "FROM `meta_order` "
                . "WHERE `created`>=". $last_prior ."  
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%') 
                and `sellernick` is not NULL";
        
        $sql.=" GROUP BY `itemnum`";
        
        $query=  $this->db->query($sql);
        foreach($query->result_array() as $key => $item)
        {
            $data[$key]->salesNum=$item['salesNum'];
            $data[$key]->itemnum=$item['itemnum'];
            $data[$key]->price=$item['price'];
        }
        echo json_encode($data);         
    }

}
