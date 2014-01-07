<?php
class Channel_auth extends CI_Controller{
    public function channel_scale(){
        $data['project']=$this->project_html();
        $this->load->view('channel_auth/channel_scale',$data);
    }
    
    public function project_html(){
        $this->load->database("etc_privileges");
        $sql='SELECT projectname,dbname FROM sys_project where is_valid="1"';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $html='<select>';
        foreach($data as $value){
            $html .='<option value="'.$value['dbname'].'">'.$value['projectname'].'</option>'; 
        }
        $html .='</select>';
        return $html;
    }
}
