<?php
class Recruit extends CI_Controller{
    public function get_data(){
        $startDate=$_POST['startDate']='2013-12-25';
        $endDate=$_POST['endDate']='2013-12-25';
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select status_recruit_group.updatetime,kpi_supplier_daily.up_seller_num_new_7_crate,avg(if(group_id=0,rate_success,0)) as  incorporation_success_rate,avg(if(group_id<>0,rate_success,0)) as recruit_success_rate,sum(if(group_id<>0,num_success,0)) as recruit_success_num from status_recruit_group left join kpi_supplier_daily on status_recruit_group.updatetime=kpi_supplier_daily.updatetime where       status_recruit_group.updatetime'
                . '  BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
}
