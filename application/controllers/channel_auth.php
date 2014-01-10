<?php
class Channel_auth extends CI_Controller{
    //渠道规模
    public function channel_scale(){
        $data['project']=$this->project_html();
        $data['operator']=$this->operator_html();
        $data['title']='渠道规模';
        $this->load->view('templates/header',$data);
        $this->load->view('channel_auth/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('channel_auth/channel_scale');
        $this->load->view('templates/footer');
    }
    
    #全部分销商 销售额
    public function order_sales_fee_success(){
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        
        #失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        
        #全部分销商 销售额
        $sql="select sum(order_sales_fee_success)  as order_sales_fee_success from (  select date(meta_order.createtime) as createtime, sum(price*meta_order.number) as order_sales_fee_success,meta_cooperation.account  from meta_order  left join meta_cooperation  on meta_order.sellernick=meta_cooperation.sellernick  where meta_order.status not regexp '$etc_rule'  group by date(meta_order.createtime),account  order by createtime desc  )as temp  where temp.createtime between '$startDate' AND '$endDate'";
        if($operator !='all'){
            $sql .=" and account='$operator'";
        }
        $query=$this->db->query($sql);
        $data=$query->row_array();
        echo json_encode($data);
    }
    
    #追灿分销商 销售额
    public function order_sales_fee_success_ex(){
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        
        #失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        
        #全部分销商 销售额
        $sql="select sum(order_sales_fee_success_ex) as order_sales_fee_success_ex".
             " from (".
             " select date(createtime) as createtime,date(updatetime) as updatetime,sum(price*number) as order_sales_fee_success_ex,zhuican.account "
             ." from meta_order "
             ." right join ( "
              ." select meta_cooperation.sellernick,meta_cooperation.account"
            ." from meta_cooperation"
            ." left join up_cooperation_register"
            ." on meta_cooperation.sellernick=up_cooperation_register.sellernick"
            ." where up_cooperation_register.sellernick is null"
            ." )as zhuican"
            ." on meta_order.sellernick=zhuican.sellernick"
            ." where status not regexp '$etc_rule'"
            ." group by date(createtime)"
            ." order by date(createtime) desc"
                . " ) as a"
                . " where createtime between '$startDate' AND '$endDate'";
        if($operator !='all'){
            $sql .=" and account='$operator'";
        }
        $query=$this->db->query($sql);
        $data=$query->row_array();
        echo json_encode($data);
    }
    
       #全部分销商 销售量
    public function order_sales_num_success(){
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        
        #失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        
        #全部分销商 销售量
        $sql="select sum(order_sales_num_success) as order_sales_num_success  from (  select date(meta_order.createtime) as createtime, sum(meta_order.number) as order_sales_num_success,meta_cooperation.account  from meta_order  left join meta_cooperation  on meta_order.sellernick=meta_cooperation.sellernick  where meta_order.status not regexp '$etc_rule'  group by date(meta_order.createtime),account  order by createtime desc  )as temp  where temp.createtime between '$startDate' AND '$endDate'";
        if($operator !='all'){
            $sql .=" and account='$operator'";
        }
        $query=$this->db->query($sql);
        $data=$query->row_array();
        echo json_encode($data);
    }
    
    #追灿分销商 销售量
    public function order_sales_num_success_ex(){
        $project=$_POST['project'];
        $operator=$_POST['operator'];
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        
        $this->load->model("rank_database");
        $db=$this->rank_database->select_DB($project);
        $this->load->database($db);
        
        #失败订单排除规则
        $sql="select name,rule".
            " from etc_rule".
            " where name='ORDER_FAILED'";      
        $query=$this->db->query($sql);
        $etc_rule=$query->row()->rule;
        preg_match_all('/%([^%]*)%/',$etc_rule,$arr);
        $etc_rule=implode('|',$arr[1]);
        
        #追灿分销商 销售量
        $sql="select sum(order_sales_num_success_ex) as order_sales_num_success_ex".
             " from (".
             " select date(createtime) as createtime,date(updatetime) as updatetime,sum(number) as order_sales_num_success_ex,zhuican.account "
             ." from meta_order "
             ." right join ( "
              ." select meta_cooperation.sellernick,meta_cooperation.account"
            ." from meta_cooperation"
            ." left join up_cooperation_register"
            ." on meta_cooperation.sellernick=up_cooperation_register.sellernick"
            ." where up_cooperation_register.sellernick is null"
            ." )as zhuican"
            ." on meta_order.sellernick=zhuican.sellernick"
            ." where status not regexp '$etc_rule'"
            ." group by date(createtime)"
            ." order by date(createtime) desc"
                . " ) as a"
                . " where createtime between '$startDate' AND '$endDate'";
        if($operator !='all'){
            $sql .=" and account='$operator'";
        }
        $query=$this->db->query($sql);
        $data=$query->row_array();
        echo json_encode($data);
    }
    
    //生成“选择项目”的 <select>
    public function project_html(){
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
    }
    //生成“选择运营人员”的<select>
    public function operator_html(){
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        $sql='SELECT `username` FROM sys_user where `is_valid`="1" AND `groupid`=1';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $html='<select id="operator">';
        foreach($data as $value){
            $html .='<option value="'.$value['username'].'">'.$value['username'].'</option>'; 
        }
        $html .='<option value="all">全部</option>';
        $html .='</select>';
        return $html;
    }
}
