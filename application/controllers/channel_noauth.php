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
            $data['createtime'][]=$value['createtime'];
            $data['order_fee'][]=(float)$value['order_fee'];
        }
        echo json_encode($data);
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
