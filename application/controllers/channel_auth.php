<?php
class Channel_auth extends CI_Controller{
    //渠道规模
    public function channel_scale(){
        $data['operator']=$this->operator_html();
        $data['title']='渠道规模';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header_add_channel_scale');
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
        $this->load->view('channel_auth/header_add_channel_quality');
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
        $this->load->view('channel_auth/header_add_trend_analysis');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_auth/trend_analysis');
        $this->load->view('templates/footer');
    }
    
    //根据AJAX请求条件，返回相应的JSON数据
    public function data_channel_scale(){
        //获取AJAX发送来的表单数据
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
        $sql="select date(status_order.createtime) as createtime, sum(price*status_order.number) as order_sales_fee_success,status_order.sellernick,meta_cooperation.account  from status_order  left join meta_cooperation  on status_order.sellernick=meta_cooperation.sellernick  where status_order.status not regexp '$etc_rule' AND date(status_order.createtime) BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $data['order_sales_fee_success']=$this->db->query($sql)->row()->order_sales_fee_success;
        //授权分销商的销售量（全部）
        $sql="select sum(order_sales_num_success) as order_sales_num_success  from (  select date(status_order.createtime) as createtime, sum(status_order.number) as order_sales_num_success,meta_cooperation.account  from status_order  left join meta_cooperation  on status_order.sellernick=meta_cooperation.sellernick  where status_order.status not regexp '$etc_rule'  group by date(status_order.createtime),account  order by createtime desc  )as temp  where temp.createtime between '$startDate' AND '$endDate'".($operator=='all'?'':" AND account='$operator'");
        $data['order_sales_num_success']=$this->db->query($sql)->row()->order_sales_num_success;
        //累计授权分销商数量（全部）
        $sql="SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $data['seller_num']=$this->db->query($sql)->row()->seller_num;
        //上架的授权分销商数量（全部）
        $sql="select COUNT(temp_2.sellernick) AS up_seller_num  from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1') is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp_0  where number > 0  order by updatetime desc  ) as temp_1  group by sellernick  ) as temp_2   left join meta_cooperation  on temp_2.sellernick=meta_cooperation.sellernick  where temp_2.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $data['up_seller_num']=$this->db->query($sql)->row()->up_seller_num;
        
        echo json_encode($data);
        }elseif($zhuicanAll=='zhuican'){
         //授权分销商的销售额（追灿）
        $sql="select sum(order_sales_fee_success_ex) as order_sales_fee_success from ( select date(createtime) as createtime,date(updatetime) as updatetime,sum(price*number) as order_sales_fee_success_ex,zhuican.account  from status_order right join (  select meta_cooperation.sellernick,meta_cooperation.account from meta_cooperation left join up_cooperation_register on meta_cooperation.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null )as zhuican on status_order.sellernick=zhuican.sellernick  where status not regexp '$etc_rule'  group by date(createtime)  order by date(createtime) desc  ) as a  where createtime between '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['order_sales_fee_success']=$query->row()->order_sales_fee_success;
        //授权分销商的销售量（追灿）
        $sql="select sum(order_sales_num_success_ex) as order_sales_num_success from (  select date(createtime) as createtime,date(updatetime) as updatetime,sum(number) as order_sales_num_success_ex,zhuican.account   from status_order   right join (   select meta_cooperation.sellernick,meta_cooperation.account  from meta_cooperation left join up_cooperation_register  on meta_cooperation.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null  )as zhuican  on status_order.sellernick=zhuican.sellernick  where status not regexp '$etc_rule'  group by date(createtime)  order by date(createtime) desc  ) as a  where createtime between '$startDate' AND '$endDate'".($operator=='all'?'':" AND account='$operator'");
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
        
        $sql='SELECT `projectname`,`dbname` FROM `etc_project` where `is_valid`="1"';
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
        $sql='SELECT `username` FROM etc_user where `is_valid`="1" AND `groupid`=1';
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
         $sql="select(select count(distinct status_order.sellernick)   from status_order   inner join meta_cooperation   on status_order.sellernick=meta_cooperation.sellernick   where status_order.status not regexp '$etc_rule' AND status_order.number >0 and status_order.createtime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'").")/(SELECT   COUNT(sellernick) AS seller_num FROM meta_cooperation WHERE status>'0' AND startdate < '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS dynamic_sales_rate";
        $query=$this->db->query($sql);
        $data['dynamic_sales_rate']=$query->row()->dynamic_sales_rate; 
        //订单关闭比率(全部)
        $sql="select (select count(distinct status_order.oid) from status_order inner join meta_cooperation on status_order.sellernick=meta_cooperation.sellernick where status_order.number>0 AND status_order.`status`  regexp '关闭' and status_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(select count(distinct  status_order.oid) from status_order inner join meta_cooperation on status_order.sellernick=meta_cooperation.sellernick where status_order.number>0 and status_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS order_failed_rate";
        $query=$this->db->query($sql);
        $data['order_failed_rate']=$query->row()->order_failed_rate;
        #平均在架商品数(全部)
        $sql="select avg(temp_2.up_number) AS up_item_num  from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1') is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp_0  where number > 0  order by updatetime desc  ) as temp_1  group by sellernick  ) as temp_2   left join meta_cooperation  on temp_2.sellernick=meta_cooperation.sellernick  where temp_2.updatetime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'");
        $query=$this->db->query($sql);
        $data['up_item_num']=$query->row()->up_item_num;
        //流失分销商数（全部）
        $sql="select avg(seller_num_lost) as seller_num_lost from(select date_sub(lastdate,interval 1 day) as date,count(sellernick) as seller_num_lost  from meta_cooperation  where lastdate < curdate() group by lastdate)as temp where temp.date between '$startDate' and  '$endDate'";
        $query=$this->db->query($sql);
        $data['seller_num_lost']=(int)$query->row()->seller_num_lost;
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
             $sql="select (select count(distinct status_order.sellernick)   from status_order   inner join meta_cooperation    on status_order.sellernick=meta_cooperation.sellernick   left join up_cooperation_register   on status_order.sellernick=up_cooperation_register.sellernick   where up_cooperation_register.sellernick is null and status_order.status not regexp '$etc_rule'  AND status_order.number >0 and status_order.createtime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'").")/("."SELECT COUNT(meta_cooperation.sellernick)AS seller_num  FROM meta_cooperation LEFT JOIN up_cooperation_register ON meta_cooperation.sellernick= up_cooperation_register.sellernick WHERE status>'0'   AND up_cooperation_register.sellernick IS NULL AND meta_cooperation.startdate < '$endDate'   ".($operator=='all'?'':" AND account='$operator'").") AS dynamic_sales_rate";
             $query=$this->db->query($sql);
             $data['dynamic_sales_rate']=$query->row()->dynamic_sales_rate; 
             //订单关闭比率（追灿）
             $sql="select (select count(distinct status_order.oid) from status_order inner join meta_cooperation on status_order.sellernick=meta_cooperation.sellernick left join up_cooperation_register on status_order.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null and  status_order.number>0 AND status_order.`status`  regexp '退款|未支付|关闭|等待付款' and status_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").")/(select count(distinct  status_order.oid) from status_order inner join meta_cooperation on status_order.sellernick=meta_cooperation.sellernick left join up_cooperation_register on status_order.sellernick=up_cooperation_register.sellernick where up_cooperation_register.sellernick is null and  status_order.number>0 and status_order.createtime BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'").") AS order_failed_rate";
             $query=$this->db->query($sql);
             $data['order_failed_rate']=$query->row()->order_failed_rate;
             //平均在架商品数（追灿）
             $sql="select avg(up_number) AS up_item_num from(  SELECT updatetime,sellernick,number up_number,if(updatetime<curdate(),'0','1')  is_up  from(  select sellernick,number,updatetime  from(  select sellernick,count(itemid) as number,max(updatetime) as updatetime  from meta_item  where updatetime >= curdate() AND sellernick is not null and is_auth_item='1'  UNION  select sellernick,number,updatetime  from(  select sellernick,sales_product as number,updatetime  from up_cooperation   where updatetime >= curdate() and sales_product>0  order by updatetime desc  ) as temp  group by sellernick  )as temp2  where number > 0  order by updatetime desc  ) as temp3  group by sellernick  ) as temp4   left join meta_cooperation  on temp4.sellernick=meta_cooperation.sellernick  left join up_cooperation_register  on temp4.sellernick=up_cooperation_register.sellernick  where up_cooperation_register.sellernick is null AND temp4.updatetime between '$startDate' AND '$endDate'  ".($operator=='all'?'':" AND meta_cooperation.account='$operator'");
            $query=$this->db->query($sql);
            $data['up_item_num']=$query->row()->up_item_num;
            //流失分销商数（追灿）
            $sql="select avg(seller_num_lost) as seller_num_lost from(select date_sub(lastdate,interval 1 day) as date,count(sellernick) as seller_num_lost  from meta_cooperation  where lastdate < curdate() and sellernick not in (select sellernick from up_cooperation_register)  group by lastdate)as temp where temp.date between '$startDate' and  '$endDate'";
        $query=$this->db->query($sql);
        $data['seller_num_lost']=(int)$query->row()->seller_num_lost;
            echo json_encode($data);
        }
    }
    public function data_channel_auth_trend_analysis(){
        //获取AJAX发送来的数据
        $project=$_POST['project'];
        //$operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $zhuicanAll=$_POST['zhuicanAll'];
        //选择数据库
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        //失败订单排除规则
        /*$sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        $data=NULL;
        if($zhuicanAll=='all'){
        //销售额，销售量（全部）
        $sql="SELECT createtime,SUM(order_fee) AS order_fee , SUM(order_num) AS order_num FROM(      SELECT DATE(status_order.createtime) AS createtime,(status_order.price*status_order.number) AS order_fee,status_order.number AS order_num,status_order.sellernick,meta_cooperation.account      FROM status_order      INNER JOIN meta_cooperation      ON status_order.sellernick=meta_cooperation.sellernick      ORDER BY createtime DESC  ) AS temp  WHERE createtime BETWEEN '$startDate' AND '$endDate'  GROUP BY createtime".($operator=='all'?'':" AND account='$operator'");
        $result=$this->db->query($sql)->result_array();
        if(!empty($result)){
        foreach($result as $value){
            $data['order_fee']['createtime'][]=$value['createtime'];
            $data['order_fee']['order_fee'][]=(float)$value['order_fee'];
            $data['order_fee']['order_num'][]=(int)$value['order_num'];
        }
        }
        //分销商数量（全部）
        $sql="select temp.startdate,sum(temp2.seller_num_growth) as seller_num FROM (  SELECT   startdate,count(sellernick) as seller_num_growth  FROM meta_cooperation  WHERE status>'0' AND startdate BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'") ."  GROUP BY startdate  ) as temp  INNER JOIN (  SELECT   startdate,count(sellernick)as seller_num_growth   FROM meta_cooperation  WHERE status>'0' AND startdate BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'") ."  GROUP BY startdate  ) as temp2  ON temp2.startdate<=temp.startdate  GROUP BY temp.startdate";
        foreach($this->db->query($sql)->result_array() as $value){
            $data['seller_num']['startdate'][]=$value['startdate'];
            $data['seller_num']['seller_num'][]=(int)$value['seller_num'];
        }
        echo json_encode($data);
        }else{
         //销售额，销售量（追灿）
        $sql="SELECT createtime,SUM(order_fee) AS order_fee , SUM(order_num) AS order_num FROM(      SELECT DATE(status_order.createtime) AS createtime,(status_order.price*status_order.number) AS order_fee,status_order.number AS order_num,status_order.sellernick,meta_cooperation.account      FROM status_order      INNER JOIN meta_cooperation      ON status_order.sellernick=meta_cooperation.sellernick  WHERE  status_order.sellernick not in(select sellernick from up_cooperation_register)    ORDER BY createtime DESC  ) AS temp  WHERE createtime BETWEEN '$startDate' AND '$endDate'  GROUP BY createtime".($operator=='all'?'':" AND account='$operator'");
        $result=$this->db->query($sql)->result_array();
        if(!empty($result)){
        foreach($result as $value){
            $data['order_fee']['createtime'][]=$value['createtime'];
            $data['order_fee']['order_fee'][]=(float)$value['order_fee'];
            $data['order_fee']['order_num'][]=(int)$value['order_num'];
        }
        }
         //分销商数量（追灿）
        $sql="select temp.startdate,sum(temp2.seller_num_growth) as seller_num FROM (  SELECT   startdate,count(sellernick)  as seller_num_growth FROM meta_cooperation  WHERE status>'0' AND sellernick NOT IN(select sellernick from up_cooperation_register) AND startdate BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'") ."  GROUP BY startdate  ) as temp  INNER JOIN (  SELECT   startdate,count(sellernick)as seller_num_growth   FROM meta_cooperation  WHERE status>'0' AND sellernick NOT IN(select sellernick from up_cooperation_register) AND startdate BETWEEN '$startDate' AND '$endDate' ".($operator=='all'?'':" AND account='$operator'") ."  GROUP BY startdate  ) as temp2  ON temp2.startdate<=temp.startdate  GROUP BY temp.startdate";
        foreach($this->db->query($sql)->result_array() as $value){
            $data['seller_num']['startdate'][]=$value['startdate'];
            $data['seller_num']['seller_num'][]=(int)$value['seller_num'];
        }*/
        
        //-------------------------------- 第一和第二部分 : 分销商销售额 和分销商数量 --------------------------------------------------
        $sql;
        $sql2;
        $data=array();//用于接收所有数据
        $n=4;//展示4天的数据
        if($zhuicanAll!='all')
        {
            $sql = "SELECT `updatetime`, `order_sales_fee_ex` as `sales`, `order_sales_num_ex` as `sales_num`, `seller_num_ex` as `seller_num` FROM `status_kpi_day` WHERE `updatetime` between '". $startDate ."' and '". $endDate ."' order by `updatetime`";
            
        }
        else
        {
            $sql = "SELECT `updatetime`, `order_sales_fee` as `sales`, `order_sales_num` as `sales_num`, `seller_num` as `seller_num` FROM `status_kpi_day` WHERE `updatetime` between '". $startDate ."' and '". $endDate ."' order by `updatetime`";
        }
        $query = $this->db->query($sql);
        foreach($query->result_array() as $value){
            $data['order_fee']['createtime'][]=$value['updatetime'];
            $data['order_fee']['order_fee'][]=(float)$value['sales'];
            $data['order_fee']['order_num'][]=(int)$value['sales_num'];
            $data['seller_num']['startdate'][]=$value['updatetime'];
            $data['seller_num']['seller_num'][]=(int)$value['seller_num'];
        }
        
        //-------------------------------- 第三部分 : 上架率  -----------------------------------------------------
        if($zhuicanAll!='all')
        {
            //先查询上架商家数（追灿招募）
            $sql="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `up_number` >0 AND `sellernick` in 
                (SELECT `sellernick` FROM `meta_cooperation` WHERE `is_zhuican` = '1') 
                AND `updatetime` between '". $startDate ."' and '". $endDate ."'
                GROUP BY `updatetime`  order by `updatetime`";
            //在查询所有（包括未上架）商家数（追灿招募）
            $sql2="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `sellernick` in 
                (SELECT `sellernick` FROM `meta_cooperation` WHERE `is_zhuican` = '1') 
                AND `updatetime` between '". $startDate ."' and '". $endDate ."'
                GROUP BY `updatetime` ORDER BY `updatetime`";
            
        }
        else
        {
            //查询上架商家数（全部）
            $sql="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` 
                WHERE `up_number` >0 
                AND `updatetime` between '". $startDate ."' and '". $endDate ."'
                GROUP BY `updatetime` 
                ORDER BY `updatetime`";
            //查询所有（包括未上架）商家数（全部）
            $sql2="SELECT `updatetime`, count(`sellernick`) AS `up_num` FROM `status_up_shop` "
                    . "WHERE `updatetime` between '". $startDate ."' and '". $endDate ."'"
                    . "GROUP BY `updatetime` ORDER BY `updatetime`";
        }
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $value){
            $data['up_rate']['createtime'][]=$value['updatetime'];
            $data['up_rate']['up_rate'][]=(float)$value['up_num'];
        }
        
        $query = $this->db->query($sql2);
        $i=0;
        foreach($query->result_array() as $item)
        {
            $data['up_rate']['up_rate'][$i] /= $item['up_num'];
            //转换为百分数
            $data['up_rate']['up_rate'][$i] *=100;
            $i++;
        }
        
        //echo json_encode($data);
        echo json_encode($data, JSON_NUMERIC_CHECK );
        
        //echo json_encode($data);
        
    }
}
