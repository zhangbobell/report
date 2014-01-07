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
    public function get_data(){
        $sql="select date(createtime) as datetime,sum(number) as sales_num from meta_order where `status` not like '%退款%' and `status` not  like '%未支付%' and  `status` not like '%关闭%' and  `status` not  like '%等待付款%' group by date(createtime) order by date(createtime) desc";
        echo $sql;
    }
    //生成“选择项目”的 <select>
    public function project_html(){
        $this->load->database("etc_privileges");
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
        $this->load->database("etc_privileges");
        $sql='SELECT `username` FROM sys_user where `is_valid`="1" AND `groupid`=1';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $html='<select>';
        foreach($data as $value){
            $html .='<option value="'.$value['username'].'">'.$value['username'].'</option>'; 
        }
        $html .='</select>';
        return $html;
    }
}