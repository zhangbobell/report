<?php
class Rank_list extends CI_Controller{
    
    //分销商销量增长率排行榜
    public function sales_rate_rank(){

        $data['title']='分销商销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-sales-rate');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //分销商销量排行榜
    public function sales_rank(){

        $data['title']='分销商销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-sales');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/sales_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量增长率排行榜
    public function product_sales_rate_rank(){

        $data['title']='产品销量增长率排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-product-sales-rate');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/product_sales_rate_rank');
        $this->load->view('templates/footer');
    }
    
    //产品销量排行榜
    public function product_sales_rank(){

        $data['title']='产品销量排行榜';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_noauth/header-add');
        $this->load->view('rank_list/header-add-product-sales');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('rank_list/product_sales_rank');
        $this->load->view('templates/footer');
    }
    
    public function seller_num(){
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select createtime,number from meta_order limit 10';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    public function seller_num_rate(){
         $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select sellernick,sum(number) as sum_number from meta_order where createtime between "2013-12-01" and "2013-12-31" group by sellernick order by sum_number desc';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    
     public function product_num(){
        $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select createtime,sku,number from meta_order limit 10';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
     public function product_num_rate(){
         $this->load->database('mysqli://data:data2123@192.168.1.90/db_sanqiang');
        $sql='select sku,sum(number) as sum_number from meta_order where createtime between "2013-12-01" and "2013-12-31" group by sku order by sum_number desc';
        echo $sql;
        $query=$this->db->query($sql);
        var_dump($query->result());
    }
    
    //分销商销量增长率排行榜
    public function sales_rate_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
        $prior_last = "date_sub(curdate(), interval ". ($time+1) ." day)";
        $prior_prior = "date_sub(curdate(), interval ". ($time*2) ." day)";

        
            if($time != '1')
            {
                $sql = "select `diff`, rawRank.`sellernick`, `meta_cooperation`.`shopid`, rawRank.`total_a`, rawRank.`total_b`, `meta_cooperation`.`account` from(
                        select (a.`total`-b.`total`)/b.`total` as diff, a.`sellernick`, a.`total` as `total_a`, b.`total` as `total_b` 
                        from
                        (SELECT if(`createtime` between ". $last_prior ." and ". $last_last .",'1','0') as `idx`, `sellernick`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where `createtime` between ". $last_prior ." and ". $last_last ."
                        or `createtime` between ". $prior_prior ." and ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `sellernick`, `idx`) a, 
                        (SELECT if(`createtime` between ". $last_prior ." and ". $last_last .",'1','0') as `idx`, `sellernick`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where `createtime` between ". $last_prior ." and ". $last_last ."
                        or `createtime` between ". $prior_prior ." and ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `sellernick`, `idx`) b 
                        where a.`idx`='1' and b.`idx`='0' and a.`sellernick`=b.`sellernick`
                        order by `diff` DESC
                        LIMIT 20)
                        as rawRank
                        left join
                        `meta_cooperation`
                        on rawRank.`sellernick` = `meta_cooperation`.`sellernick`";
            }
            else
            {
                $sql = "select `diff`, rawRank.`sellernick`, `meta_cooperation`.`shopid`, rawRank.`total_a`, rawRank.`total_b`, `meta_cooperation`.`account` from(
                        select (a.`total`-b.`total`)/b.`total` as diff, a.`sellernick`, a.`total` as `total_a`, b.`total` as `total_b` 
                        from
                        (SELECT if(date(`createtime`) = ". $last_last .",'1','0') as `idx`, `sellernick`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where date(`createtime`) = ". $last_last ."
                        or date(`createtime`) = ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `sellernick`, `idx`) a, 
                        (SELECT if(date(`createtime`) = ". $last_last .",'1','0') as `idx`, `sellernick`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where date(`createtime`) = ". $last_last ."
                        or date(`createtime`) = ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `sellernick`, `idx`) b 
                        where a.`idx`='1' and b.`idx`='0' and a.`sellernick`=b.`sellernick`
                        order by `diff` DESC
                        LIMIT 20)
                        as rawRank
                        left join
                        `meta_cooperation`
                        on rawRank.`sellernick` = `meta_cooperation`.`sellernick`";
            }
            
            //如果是追灿的
            if($isZC == 'true')
            {
                $sql .= " where rawRank.`sellernick` not in (select `sellernick` from `up_cooperation_register`)"; 
            }
            
            $query = $this->db->query($sql);
            $rank=null;
            foreach($query->result_array() as $item)
            {
                $rank[] = $item;
            }
            echo json_encode($rank);        
    }
    
    //分销商销量排行榜
     public function sales_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $isZC = $this->input->post('is_zc',TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
     
        if($time != '1')
        {
            $sql = "select rawRank.`total`, rawRank.`sellernick`, `meta_cooperation`.`shopid`, `meta_cooperation`.`account` 
                    from(SELECT `sellernick`, sum(`number`) as `total` 
                    from `meta_order` 
                    where `createtime` between ". $last_prior ." and ". $last_last ." 
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `sellernick` order by `total` DESC LIMIT 20
                    ) as rawRank
                    left join
                    `meta_cooperation`
                    on rawRank.`sellernick` = `meta_cooperation`.`sellernick`";
        }
        else
        {
            $sql = "select rawRank.`total`, rawRank.`sellernick`, `meta_cooperation`.`shopid`, `meta_cooperation`.`account` 
                    from(SELECT `sellernick`, sum(`number`) as `total` 
                    from `meta_order` 
                    where date(`createtime`) = ". $last_last ." 
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `sellernick` order by `total` DESC LIMIT 20
                    ) as rawRank
                    left join
                    `meta_cooperation`
                    on rawRank.`sellernick` = `meta_cooperation`.`sellernick`";
        }

        //如果是追灿的
        if($isZC == 'true')
        {
            $sql .= " where rawRank.`sellernick` not in (select `sellernick` from `up_cooperation_register`)"; 
        }

        $query = $this->db->query($sql);
        $rank=null;
        foreach($query->result_array() as $item)
        {
            $rank[] = $item;
        }
        echo json_encode($rank);        
    }
    
    //产品销量增长率排行榜
    public function product_sales_rate_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
        $prior_last = "date_sub(curdate(), interval ". ($time+1) ." day)";
        $prior_prior = "date_sub(curdate(), interval ". ($time*2) ." day)";

        
            if($time != '1')
            {
                $sql = "select (a.`total`-b.`total`)/b.`total` as diff, a.`price`, a.`skuid`, a.`total` as `total_a`, b.`total` as `total_b` 
                        from
                        (SELECT if(`createtime` between ". $last_prior ." and ". $last_last .",'1','0') as `idx`, `skuid`, `price`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where `createtime` between ". $last_prior ." and ". $last_last ."
                        or `createtime` between ". $prior_prior ." and ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `skuid`, `idx`) a, 
                        (SELECT if(`createtime` between ". $last_prior ." and ". $last_last .",'1','0') as `idx`, `skuid`, `price`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where `createtime` between ". $last_prior ." and ". $last_last ."
                        or `createtime` between ". $prior_prior ." and ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `skuid`, `idx`) b 
                        where a.`idx`='1' and b.`idx`='0' and a.`skuid`=b.`skuid`
                        order by `diff` DESC
                        LIMIT 20";
            }
            else
            {
                $sql = "select (a.`total`-b.`total`)/b.`total` as diff, a.`price`, a.`skuid`, a.`total` as `total_a`, b.`total` as `total_b`  
                        from
                        (SELECT if(date(`createtime`) = ". $last_last .",'1','0') as `idx`, `skuid`, `price`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where date(`createtime`) = ". $last_last ."
                        or date(`createtime`) = ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `skuid`, `idx`) a, 
                        (SELECT if(date(`createtime`) = ". $last_last .",'1','0') as `idx`, `skuid`, `price`, sum(`number`)
                        as `total` 
                        from `meta_order` 
                        where date(`createtime`) = ". $last_last ."
                        or date(`createtime`) = ". $prior_last ."
                        and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                        group by `skuid`, `idx`) b 
                        where a.`idx`='1' and b.`idx`='0' and a.`skuid`=b.`skuid`
                        order by `diff` DESC
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
    
    //产品销量排行榜
     public function product_sales_rank_data( )
    {
        $time = $this->input->post('time', TRUE);
        $db = $this->input->post('db', TRUE);
        $this->load->model('rank_database');
        $db_sanqiang = $this->rank_database->select_DB($db);
        $this->load->database($db_sanqiang);
        
        $last_last = "date_sub(curdate(), interval 1 day)";
        $last_prior = "date_sub(curdate(), interval ". $time ." day)";
     
        if($time != '1')
        {
            $sql = "SELECT `skuid`, `price`, sum(`number`) as `total` 
                    from `meta_order` 
                    where `createtime` between ". $last_prior ." and ". $last_last ." 
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `skuid` order by `total` DESC LIMIT 20";
        }
        else
        {
            $sql = "SELECT `skuid`, `price`, sum(`number`) as `total` 
                    from `meta_order` 
                    where date(`createtime`) = ". $last_last ." 
                    and not (`status` like '%退款%' or `status` like '%未支付%' or `status` like '%关闭%' or `status` like '%等待付款%')
                    group by `skuid` order by `total` DESC LIMIT 20";
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
