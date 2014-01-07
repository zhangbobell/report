<?php
class Rank_list extends CI_Controller{
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