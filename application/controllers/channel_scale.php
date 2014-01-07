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
    
    public function get_data(){
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $sql='SELECT'
                . ' sum(order_sales_fee_success) as sum_order_sales_fee_success,'
                . ' sum(order_sales_fee_success_ex) as sum_order_sales_fee_success_ex,'
                . ' sum(order_sales_num_success)as sum_order_sales_num_success,'
                . ' sum(order_sales_num_success_ex)as sum_order_sales_num_success_ex,'
                . ' avg(up_seller_num) as avg_up_seller_num,'
                . ' avg(seller_num)as avg_seller_num,'
                . ' avg(seller_num_ex)as avg_seller_num_ex,'
                . ' avg(avg_item_num_up) as avg_avg_item_num_up,'
                . ' up_seller_num_crate,'
                . ' seller_num_pc_crate'
                . ' FROM kpi_supplier_daily'
                . ' WHERE updatetime BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $query=$this->db->query($sql);
        $data=$query->row_array();
        $data['avg_up_seller_num_ex']=NULL;
        $data['avg_avg_item_num_up_ex']=NULL;
       // var_dump($query->result_array());
       echo json_encode($data);
    }
    
   
}