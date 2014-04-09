<?php
class channel_noauth extends CI_Controller{
    public function get_data(){
        $project=$_POST['project'];
        $this->load->model('rank_database');
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //全网销售额
        $sql="SELECT DATE(createtime) AS createtime,SUM(price*number) AS order_fee  FROM status_order  WHERE DATE(createtime) BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()  GROUP BY DATE(createtime)";
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
        $this->load->view('channel_noauth/header-add');
        $this->load->view('channel_noauth/header_add_rank_noauth');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_noauth/rank_noauth');
        $this->load->view('templates/footer');
}

    public function rank_noauth_data()
    {
        $startDate = $this->input->post('startdate', TRUE);
        $endDate = $this->input->post('enddate', TRUE);
        $db = $this->input->post('db', TRUE);
        $sop = $this->input->post('sop', TRUE);
        
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);

     
        if($startDate !== $endDate)
        {
            $sql = "select `a`.`sellernick`, `a`.`total`, `status_auth_shop`.`price_range` from
                    (select `sellernick`, sum(`number`) as `total` from
                    `status_order` where
                    `sellernick` in (select `sellernick` from `meta_cooperation` where `status` = '0')
                    and `createtime` between '". $startDate ."' and '". $endDate ."' 
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `sellernick`
                    ) as a
                    left join
                    `status_auth_shop`
                    on `a`.`sellernick` = `status_auth_shop`.`sellernick`
                    group by `sellernick`";
        }
        else
        {
            $sql = "select `a`.`sellernick`, `a`.`total`, `status_auth_shop`.`price_range` from
                    (select `sellernick`, sum(`number`) as `total` from
                    `status_order` where
                    `sellernick` in (select `sellernick` from `meta_cooperation` where `status` = '0')
                    and date(`createtime`) = '". $startDate ."'
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `sellernick`
                    ) as a
                    left join
                    `status_auth_shop`
                    on `a`.`sellernick` = `status_auth_shop`.`sellernick`
                    group by `sellernick`";
        }
        
        //如果是按照销量排序
        if($sop == 'true')
        {
            $sql .= " order by `total` DESC LIMIT 20"; 
        }
        else 
        {
            $sql .= " order by abs(`price_range`) DESC LIMIT 20"; 
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
