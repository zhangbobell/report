<?php
class Rank_list extends CI_Controller{
    
    //分销商销量增长率排行榜
    public function sales_rate_rank(){

        $data['title']='分销商销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
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
}
