<?php
class channel_noauth extends CI_Controller{
    public function get_data(){
        $project=$_POST['project'];
        $this->load->model('rank_database');
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //全网销售额
        $sql="SELECT DATE(createtime) AS createtime,SUM(price*number) AS order_fee  FROM meta_order  WHERE DATE(createtime) BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()  GROUP BY DATE(createtime)";
        foreach($this->db->query($sql)->result_array() as $value){
            $data['order_fee']['createtime'][]=$value['createtime'];
            $data['order_fee']['order_fee'][]=(float)$value['order_fee'];
        }
        //全网商家数量
        $sql="SELECT updatetime,seller_num_full FROM kpi_supplier_daily WHERE updatetime BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()";
         foreach($this->db->query($sql)->result_array() as $value){
            $data['seller_num_full']['updatetime'][]=$value['updatetime'];
            $data['seller_num_full']['seller_num_full'][]=(float)$value['seller_num_full'];
        }
        
        //授权分销商销售额占比
        $sql="SELECT updatetime,page_sales_fee_30_auth_srate  FROM kpi_supplier_daily  WHERE updatetime BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()";
        foreach($this->db->query($sql)->result_array() as $value){
            $data['page_sales_fee_30_auth_srate']['updatetime'][]=$value['updatetime'];
            $data['page_sales_fee_30_auth_srate']['page_sales_fee_30_auth_srate'][]=(float)$value['page_sales_fee_30_auth_srate'];
        }
        //全网乱价率
        $sql="SELECT updatetime,sum(case when price_change_number > 0 then 1 else 0 end )/count(sellernick) AS price_change_rate  FROM status_auth_shop  WHERE updatetime BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()  GROUP BY updatetime";
        foreach($this->db->query($sql)->result_array() as $value){
            $data['price_change_rate']['updatetime'][]=$value['updatetime'];
            $data['price_change_rate']['price_change_rate'][]=(float)$value['price_change_rate'];
        }
        echo json_encode($data);
    }
    
    //趋势分析
     public function trend_analysis(){

        $data['title']='趋势分析';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header_add_trend_analysis');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_noauth/trend_analysis');
        $this->load->view('templates/footer');
        
}
    
    //非授权分销商名单
    public function rank_noauth(){

        $data['title']='非授权商家名单';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_noauth/rank_noauth');
        $this->load->view('templates/footer');
}
}
