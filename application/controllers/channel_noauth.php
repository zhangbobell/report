<?php
class channel_noauth extends CI_Controller{
    public function get_data(){
        $startDate=$_POST['startDate']='2013-12-25';
        $endDate=$_POST['endDate']='2013-12-25';
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='SELECT count(sellernick) as seller_num_all'
                . ' , sum(price*sales) as order_slaes_fee_all '
                . ' FROM  meta_item '
                . 'WHERE createtime BETWEEN "'.$startDate.'" AND "'.$endDate.'" and sales <>-1 and shoptype <>"1"';
        $query=$this->db->query($sql);
        var_dump($query->row());
    }
    
    //趋势分析
     public function trend_analysis(){

        $data['title']='趋势分析';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_noauth/trend_analysis');
        $this->load->view('templates/footer');
}
    
    //
     public function rank_noauth(){

        $data['title']='趋势分析';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_noauth/trend_analysis');
        $this->load->view('templates/footer');
}
}
