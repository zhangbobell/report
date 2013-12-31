<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Channel_scale extends CI_Controller{
    public function index(){
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $this->db->select('id,updatetime,order_sales_fee');
        $this->db->from('kpi_supplier_daily');
        $this->db->order_by('updatetime desc');
        $this->db->limit(7);
        $query=$this->db->get();
        $rows=$query->result();
        for($i=0;$i<count($rows);$i++){
            foreach($rows[$i] as $key=>$value){
                $columns[$key][]=$value;
            }  
        }
       // var_dump($columns);
        $this->load->view('channel_scale/index');
    }
}