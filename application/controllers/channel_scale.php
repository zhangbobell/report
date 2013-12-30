<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Channel_scale extends CI_Controller{
    public function index(){
        /*$this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $this->db->select('id,updatetime,order_sales_fee');
        $this->db->from('kpi_supplier_daily');
        $query=$this->db->get();
        var_dump($query->result());*/
     $this->load->model('rank_database');
     $etc_privileges = $this->rank_database->select_DB('db_sanqiang');
     $this->load->database($etc_privileges);
     
    var_dump( $this->rank_database->get_kpi_supplier_daily() );
    }
}