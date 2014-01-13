<?php
class Kpi extends CI_Controller{
    public function kpi_weekly(){
         $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select(select count(*) from meta_cooperation where createtime>date_sub(curdate(),interval 30 day) and sales>0 and cast(cast(status as char)as signed integer)>0)/(select count(*) from meta_cooperation where createtime>date_sub(curdate(),interval 30 day) and cast(cast(status as char)as signed integer)>0) as p from meta_cooperation limit 1';
        $query=$this->db->query($sql);
        
        $data['title']='周度KPI';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('kpi/kpi_weekly');
        $this->load->view('templates/footer');
    }
    
    public function kpi_monthly(){
        $startDate=$_POST['startDate']='2013-12-25';
        $endDate=$_POST['endDate']='2013-12-25';
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='SELECT '
                . ' sum(order_fee -order_fee_failed) as sum_order_fee_success'
                . ' FROM  status_order_shop '
                . 'WHERE createtime BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
        $query=$this->db->query($sql);
        $data['sum_order_fee_success']=$query->row()->sum_order_fee_success;
     
        $sql='select count(sellernick) as recruit_success_num from status_recruit_log'
                . ' where status="2" AND updatetime between "'.$startDate.'" AND "'.$endDate.'"';
        $query=$this->db->query($sql);
        $data['recruit_success_num']=$query->row()->recruit_success_num;
        
        $data['title']='月度KPI';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('kpi/kpi_monthly');
        $this->load->view('templates/footer');
        
    }
}
