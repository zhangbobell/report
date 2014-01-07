<?php
class Operation extends CI_Controller{
    public function get_data(){
        $startDate=$_POST['startDate']='2013-12-25';
        $endDate=$_POST['endDate']='2013-12-25';
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        //乱价分销商名单
        $sql='select updatetime,shopid,sellernick,status,price_range from status_auth_shop where updatetime between "'.$startDate.'" AND "'.$endDate.'"having cast(status as signed integer)>0 and price_range<0';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
 
}
