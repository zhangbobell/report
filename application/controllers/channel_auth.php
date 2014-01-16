<?php
class Channel_auth extends CI_Controller{
    //渠道规模
    public function channel_scale(){
        $data['operator']=$this->operator_html();
        $data['title']='渠道规模';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_auth/channel_scale');
        $this->load->view('templates/footer');
    }
    
    //渠道质量
    public function channel_quality(){
        $data['operator']=$this->operator_html();
        $data['title']='渠道质量';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_auth/channel_quality');
        $this->load->view('templates/footer');
    }
    
    //趋势分析
     public function trend_analysis(){
        $data['operator']=$this->operator_html();
        $data['title']='趋势分析';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_auth/trend_analysis');
        $this->load->view('templates/footer');
    }
    
    //处理AJAX
    public function get_data(){
        //获取AJAX发送来的数据
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $zhuicanAll=$_POST['zhuicanAll'];
        //选择数据库
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        if($zhuicanAll=='all'){
        //授权分销商的销售额(全部)
        $sql="select date(meta_order.createtime) as createtime, sum(price*meta_order.number) as order_sales_fee_success,meta_order.sellernick,meta_cooperation.account  from meta_order  left join meta_cooperation  on meta_order.sellernick=meta_cooperation.sellernick  where meta_order.status not regexp '$etc_rule' AND date(meta_order.createtime) BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['order_sales_fee_success']=$query->row()->order_sales_fee_success;
        //授权分销商的销售量（全部）
        $sql="select sum(order_sales_num_success) as order_sales_num_success  from (  select date(meta_order.createtime) as createtime, sum(meta_order.number) as order_sales_num_success,meta_cooperation.account  from meta_order  left join meta_cooperation  on meta_order.sellernick=meta_cooperation.sellernick  where meta_order.status not regexp '$etc_rule'  group by date(meta_order.createtime),account  order by createtime desc  )as temp  where temp.createtime between '$startDate' AND '$endDate'".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['order_sales_num_success']=$query->row()->order_sales_num_success;
        //累计授权分销商数量（全部）
        $sql="SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['seller_num']=$query->row()->seller_num;
        //上架的授权分销商数量（全部）
        $sql="select COUNT(temp_2.sellernick) AS up_seller_num  from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1') is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp_0  where number > 0  order by updatetime desc  ) as temp_1  group by sellernick  ) as temp_2   left join meta_cooperation  on temp_2.sellernick=meta_cooperation.sellernick  where temp_2.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['up_seller_num']=$query->row()->up_seller_num;
        
        echo json_encode($data);
        }elseif($zhuicanAll=='zhuican'){
         //授权分销商的销售额（追灿）
        $sql="select sum(order_sales_fee_success_ex) as order_sales_fee_success from ( select date(createtime) as createtime,date(updatetime) as updatetime,sum(price*number) as order_sales_fee_success_ex,zhuican.account  from meta_order right join (  select meta_cooperation.sellernick,meta_cooperation.account from meta_cooperation left join up_cooperation_register on meta_cooperation.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null )as zhuican on meta_order.sellernick=zhuican.sellernick  where status not regexp '$etc_rule'  group by date(createtime)  order by date(createtime) desc  ) as a  where createtime between '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['order_sales_fee_success']=$query->row()->order_sales_fee_success;
        //授权分销商的销售量（追灿）
        $sql="select sum(order_sales_num_success_ex) as order_sales_num_success from (  select date(createtime) as createtime,date(updatetime) as updatetime,sum(number) as order_sales_num_success_ex,zhuican.account   from meta_order   right join (   select meta_cooperation.sellernick,meta_cooperation.account  from meta_cooperation left join up_cooperation_register  on meta_cooperation.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null  )as zhuican  on meta_order.sellernick=zhuican.sellernick  where status not regexp '$etc_rule'  group by date(createtime)  order by date(createtime) desc  ) as a  where createtime between '$startDate' AND '$endDate'".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['order_sales_num_success']=$query->row()->order_sales_num_success;
        //累计授权分销商数量（追灿）
        $sql="SELECT COUNT(meta_cooperation.sellernick)AS seller_num  FROM meta_cooperation LEFT JOIN up_cooperation_register ON meta_cooperation.sellernick= up_cooperation_register.sellernick WHERE status>'0'   AND up_cooperation_register.sellernick IS NULL AND meta_cooperation.startdate < '$endDate'   ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['seller_num']=$query->row()->seller_num;
        
        //上架的授权分销商数量（追灿）
        $sql="select count(temp4.sellernick)  as up_seller_num from(  SELECT updatetime,sellernick,number  up_number,if(updatetime<curdate(),'0','1')  is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp2  where number > 0  order by updatetime desc  ) as temp3  group by sellernick  ) as temp4   left join meta_cooperation  on temp4.sellernick=meta_cooperation.sellernick  left join up_cooperation_register  on temp4.sellernick=up_cooperation_register.sellernick  where up_cooperation_register.sellernick is null AND temp4.updatetime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'");
        $query=$this->db->query($sql);
        $data['up_seller_num']=$query->row()->up_seller_num;

        echo json_encode($data);
        }
    }
    
     
    //生成“选择项目”的 <select>
/*    public function project_html(){
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        $sql='SELECT `projectname`,`dbname` FROM `sys_project` where `is_valid`="1"';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $html='<select id="project">';
        foreach($data as $value){
            $html .='<option value="'.$value['dbname'].'">'.$value['projectname'].'</option>'; 
        }
        $html .='</select>';
        return $html;
    }*/
    //生成“选择运营人员”的<select>
    public function operator_html(){
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('test');
        $this->load->database($etc_privileges);
        $sql='SELECT `username` FROM sys_user where `is_valid`="1" AND `groupid`=1';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $html='<select id="operator">';
        $html .='<option value="all">全部</option>';
        foreach($data as $value){
            $html .='<option value="'.$value['username'].'">'.$value['username'].'</option>'; 
        } 
        $html .='</select>';
        return $html;
    }
    
    /*======================渠道质量==========================================*/
    public function data_channel_quality(){
        //获取AJAX发送来的数据
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $zhuicanAll=$_POST['zhuicanAll'];
        //选择数据库
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        if($zhuicanAll=='all'){
        //上架率（全部）
        $sql="select (select COUNT(temp_2.sellernick) AS up_seller_num  from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1') is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp_0  where number > 0  order by updatetime desc  ) as temp_1  group by sellernick  ) as temp_2   left join meta_cooperation  on temp_2.sellernick=meta_cooperation.sellernick  where temp_2.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS up_seller_rate";
        $query=$this->db->query($sql);
        $data['up_seller_rate']=$query->row()->up_seller_rate;
        //乱价率（全部）
        $sql="select (select count(sellernick) from status_price_log where status='1'  and updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS arbitrary_price_rate";
        $query=$this->db->query($sql);
        $data['arbitrary_price_rate']=$query->row()->arbitrary_price_rate;
        //动销率（全部）
         $sql="select(select count(distinct meta_order.sellernick)   from meta_order   inner join meta_cooperation   on meta_order.sellernick=meta_cooperation.sellernick   where meta_order.status not regexp '$etc_rule' AND meta_order.number >0 and meta_order.createtime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'").")/(SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS dynamic_sales_rate";
        $query=$this->db->query($sql);
        $data['dynamic_sales_rate']=$query->row()->dynamic_sales_rate; 
        //订单关闭比率(全部)
        $sql="select (select count(distinct meta_order.oid) from meta_order inner join meta_cooperation on meta_order.sellernick=meta_cooperation.sellernick where meta_order.number>0 AND meta_order.`status`  regexp '关闭' and meta_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(select count(distinct  meta_order.oid) from meta_order inner join meta_cooperation on meta_order.sellernick=meta_cooperation.sellernick where meta_order.number>0 and meta_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS order_failed_rate";
        $query=$this->db->query($sql);
        $data['order_failed_rate']=$query->row()->order_failed_rate;
        #平均在架商品数(全部)
        $sql="select avg(temp_2.up_number) AS up_item_num  from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1') is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp_0  where number > 0  order by updatetime desc  ) as temp_1  group by sellernick  ) as temp_2   left join meta_cooperation  on temp_2.sellernick=meta_cooperation.sellernick  where temp_2.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['up_item_num']=$query->row()->up_item_num;
        echo $sql;
        echo json_encode($data);
        }elseif($zhuicanAll='zhuican'){
            //上架率（追灿）
            $sql="select (select count(temp4.sellernick)  as up_seller_num from(  SELECT updatetime,sellernick,number  up_number,if(updatetime<curdate(),'0','1')  is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp2  where number > 0  order by updatetime desc  ) as temp3  group by sellernick  ) as temp4   left join meta_cooperation  on temp4.sellernick=meta_cooperation.sellernick  left join up_cooperation_register  on temp4.sellernick=up_cooperation_register.sellernick  where up_cooperation_register.sellernick is null AND temp4.updatetime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'").")/("."SELECT COUNT(meta_cooperation.sellernick)AS seller_num  FROM meta_cooperation LEFT JOIN up_cooperation_register ON meta_cooperation.sellernick= up_cooperation_register.sellernick WHERE status>'0'   AND up_cooperation_register.sellernick IS NULL AND meta_cooperation.startdate < '$endDate'   ".($operator=='all'?'':" AND account='$operator'").") AS up_seller_rate";
            $query=$this->db->query($sql);
            $data['up_seller_rate']=$query->row()->up_seller_rate;
            //乱价率（追灿）
             $sql="select (select count(status_price_log.sellernick) from status_price_log left join up_cooperation_register on status_price_log.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null and status_price_log.status='1'  and status_price_log.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(SELECT COUNT(meta_cooperation.sellernick)AS seller_num  FROM meta_cooperation LEFT JOIN up_cooperation_register ON meta_cooperation.sellernick= up_cooperation_register.sellernick WHERE status>'0'   AND up_cooperation_register.sellernick IS NULL AND meta_cooperation.startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS arbitrary_price_rate";
             $query=$this->db->query($sql);
             $data['arbitrary_price_rate']=$query->row()->arbitrary_price_rate;
             //动销率（追灿）
             $sql="select (select count(distinct meta_order.sellernick)   from meta_order   inner join meta_cooperation    on meta_order.sellernick=meta_cooperation.sellernick   left join up_cooperation_register   on meta_order.sellernick=up_cooperation_register.sellernick   where up_cooperation_register.sellernick is null and meta_order.status not regexp '$etc_rule'  AND meta_order.number >0 and meta_order.createtime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'").")/("."SELECT COUNT(meta_cooperation.sellernick)AS seller_num  FROM meta_cooperation LEFT JOIN up_cooperation_register ON meta_cooperation.sellernick= up_cooperation_register.sellernick WHERE status>'0'   AND up_cooperation_register.sellernick IS NULL AND meta_cooperation.startdate < '$endDate'   ".($operator=='all'?'':" AND account='$operator'").") AS dynamic_sales_rate";
             $query=$this->db->query($sql);
             $data['dynamic_sales_rate']=$query->row()->dynamic_sales_rate; 
             //订单关闭比率（追灿）
             $sql="select (select count(distinct meta_order.oid) from meta_order inner join meta_cooperation on meta_order.sellernick=meta_cooperation.sellernick left join up_cooperation_register on meta_order.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null and  meta_order.number>0 AND meta_order.`status`  regexp '退款|未支付|关闭|等待付款' and meta_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(select count(distinct  meta_order.oid) from meta_order inner join meta_cooperation on meta_order.sellernick=meta_cooperation.sellernick left join up_cooperation_register on meta_order.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null and  meta_order.number>0 and meta_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS order_failed_rate";
             $query=$this->db->query($sql);
             $data['order_failed_rate']=$query->row()->order_failed_rate;
             //平均在架商品数（追灿）
             $sql="select avg(up_number) AS up_item_num from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1')  is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp2  where number > 0  order by updatetime desc  ) as temp3  group by sellernick  ) as temp4   left join meta_cooperation  on temp4.sellernick=meta_cooperation.sellernick  left join up_cooperation_register  on temp4.sellernick=up_cooperation_register.sellernick  where up_cooperation_register.sellernick is null AND temp4.updatetime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'");
            $query=$this->db->query($sql);
            $data['up_item_num']=$query->row()->up_item_num;
            echo json_encode($data);
        }
    }
    public function data_channel_auth_trend_analysis(){
        //获取AJAX发送来的数据
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $zhuicanAll=$_POST['zhuicanAll'];
        //选择数据库
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        if($zhuicanAll=='all'){
            //销售额，销售量（全部）
            $sql="SELECT createtime,SUM(order_fee) AS order_fee , SUM(order_num) AS order_num FROM(      SELECT DATE(meta_order.createtime) AS createtime,(meta_order.price*meta_order.number) AS order_fee,meta_order.number AS order_num,meta_order.sellernick,meta_cooperation.account      FROM meta_order      INNER JOIN meta_cooperation      ON meta_order.sellernick=meta_cooperation.sellernick      ORDER BY createtime DESC  ) AS temp  WHERE createtime BETWEEN '$startDate' AND '$endDate'  GROUP BY createtime".($operator=='all'?'':" AND account='$operator'");
        //$data['order_fee']=$this->db->query($sql)->result_array();
        foreach($this->db->query($sql)->result_array() as $value){
            $data['createtime'][]=$value['createtime'];
            $data['order_fee'][]=(float)$value['order_fee'];
            $data['order_num'][]=(int)$value['order_num'];
        }
        //分销商数量（全部）
        $sql="SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        echo json_encode($data);
        }else{
            
        }
        
    }
}
