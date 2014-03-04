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
 
    //运营效果查询
    public function operation_effect(){

        $data['title']='运营效果';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('operation/operation_effect');
        $this->load->view('templates/footer');
    }
    //名单查询
    public function list_query(){

        $data['title']='乱价分销商名单';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('operation/header_add_list_query');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('operation/list_query');
        $this->load->view('templates/footer');
    }
    
    //0上架分销商名单
    public function zero_up_list(){

        $data['title']='0上架分销商名单';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('operation/header_add_zero_up');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('operation/zero_up_list');
        $this->load->view('templates/footer');
    }
    //分销商搜索
    public function seller_search(){

        $data['title']='分销商搜索';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('operation/seller_search');
        $this->load->view('templates/footer');
    }
     //产品搜索
    public function product_search(){

        $data['title']='分销商搜索';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('operation/product_search');
        $this->load->view('templates/footer');
    }
    
    //乱价分销商名单js数据处理
    public function list_query_data()
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
     
        $sql =  "select `sellernick`, `price_range`, `price_change_number` from 
                (select `sellernick`, `price_range`, `price_change_number` 
                from `status_auth_shop` 
                where `status` > '0' 
                and `updatetime` = '". $time ."'
                ) as all_seller
                where all_seller.`sellernick` in (select `sellernick` from `status_price_log` where `status` = '1')
                order by abs(`price_range`) DESC
                LIMIT 20";
        
        if($isZC == 'true')
        {
            $sql =  "select `sellernick`, `price_range`, `price_change_number` from 
                (select `sellernick`, `price_range`, `price_change_number` 
                from `status_auth_shop` 
                where `status` > '0' 
                and `updatetime` = '". $time ."'
                ) as all_seller
                where all_seller.`sellernick` in (select `sellernick` from `status_price_log` where `status` = '1')
                and all_seller.`sellernick` not in (select `sellernick` from `up_cooperation_register`)
                order by abs(`price_range`) DESC
                LIMIT 20";
        }
        
        $query = $this->db->query($sql);
        $rank=null;
        foreach($query->result_array() as $item)
        {
            $rank[] = $item;
        }
        echo json_encode($rank); 
    }
    
    //0上架分销商名单js数据处理
    public function zero_up_data()
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
     
        $sql =  "select `sellernick`, `startdate`
                from `meta_cooperation`
                where `updatetime` = '". $time ."'
                and `sellernick` in 
                (select `sellernick` from `status_up_shop` where `up_number` = '0')
                order by `startdate` DESC";
        
        if($isZC == 'true')
        {
            $sql =  "select `sellernick`, `startdate`
                    from `meta_cooperation`
                    where `updatetime` = '". $time ."'
                    and `sellernick` in 
                    (select `sellernick` from `status_up_shop` where `up_number` = '0')
                    and `sellernick` not in (select `sellernick` from `up_cooperation_register`)
                    order by `startdate` DESC";
        }
        
        $query = $this->db->query($sql);
        $rank=null;
        foreach($query->result_array() as $item)
        {
            $rank[] = $item;
        }
        echo json_encode($rank); 
    }
}
