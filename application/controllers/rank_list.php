<?php
class Rank_list extends CI_Controller{
    
    //分销商销量增长率排行榜
    public function sales_rate_rank(){

        $data['title']='分销商销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('rank_list/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //分销商销量排行榜
    public function sales_rank(){

        $data['title']='分销商销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量增长率排行榜
    public function product_sales_rate_rank(){

        $data['title']='产品销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/product_sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量排行榜
    public function product_sales_rank(){

        $data['title']='产品销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
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
    
    public function sales_rate_rank_data()
    {
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB('db_sanqiang');
        $this->load->database($db_sanqiang);
        
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $sql = "select (a.`total`-b.`total`)/b.`total` as diff, a.`sellernick` 
                from
                (SELECT if(`createtime` between date_sub('2014-01-09', interval 2 day) and '2014-01-09','1','0') as `idx`, `sellernick`, sum(`number`)
                as `total` 
                from `meta_order` 
                where `createtime` between date_sub('2014-01-09', interval 2 day) and '2014-01-09'
                or `createtime` between date_sub('2014-01-06', interval 2 day) and '2014-01-06'
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                group by `sellernick`, `idx`) a, 
                (SELECT if(`createtime` between date_sub('2014-01-09', interval 2 day) and '2014-01-09','1','0') as `idx`, `sellernick`, sum(`number`)
                as `total` 
                from `meta_order` 
                where `createtime` between date_sub('2014-01-09', interval 2 day) and '2014-01-09'
                or `createtime` between date_sub('2014-01-06', interval 2 day) and '2014-01-06'
                and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                group by `sellernick`, `idx`) b 
                where a.`idx`='1' and b.`idx`='0' and a.`sellernick`=b.`sellernick`
                order by `diff` DESC
                LIMIT 20 ";
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $item)
        {
            $rank[] = $item;
        }
        echo json_encode($rank);
    }
}
