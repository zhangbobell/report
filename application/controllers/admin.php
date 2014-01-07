<?php require 'chineseSpell.php';?>
<?php

/* 
 * File : admin.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */

class Admin extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();      
        $this->load->library('session');
    }
    
    public function index( $page = 'index' )
    {
        if ( ! file_exists('application/views/main/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "第一时间";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function project_management( $page = 'project-management' )    
    {
        if ( ! file_exists('application/views/admin/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "项目管理";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $query = $this->db->get('sys_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'][] = $row;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function project_add( $page = 'project-add' )    
    {
        if ( ! file_exists('application/views/admin/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "增加项目";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $this->db->select('userid,username');
        $query = $this->db->get('sys_user');
        foreach ($query->result_array() as $row)
        {
           $data['user'][] = $row;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function gen_cn_pid()
    {
        $obj = new chineseSpell();
	$cnSpell = $obj->getFullSpell($this->input->post('projectName', TRUE),''); 	
	$pid = abs(crc32(crc32($this->input->post('projectName', TRUE)).date('YmdHis')));
	
	$temp_arr = array($cnSpell);
	array_push($temp_arr,$pid);
	
	foreach($temp_arr as $k=>$v){
	 $json_arr[]  = $v;
	}
	
	echo json_encode( $json_arr );
    }
    
     public function project_add_data( $page = 'project-add-data' )    
    {
        if ( ! file_exists('application/views/admin/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "增加项目结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $project = array(
               'pid' => $this->input->post('pid', TRUE) ,
               'projectname' => $this->input->post('project-name', TRUE) ,
               'dbname' => $this->input->post('project-db', TRUE),
               'is_valid' => $this->input->post('is_valid', TRUE)
            );

        if($this->db->insert('sys_project', $project))
        {
            $data['insertProjectResult'] = '增加项目成功';
        }
        else 
        {
            $data['insertProjectResult'] = '增加项目失败';
        }
        
        $user = array();
        foreach( $this->input->post('padmin', TRUE) as $uid)
        {
            $temp = array(
              'uid' => $uid ,
              'pid' => $this->input->post('pid', TRUE)
            );
            $user[] = $temp; 
        }
        if($this->db->insert_batch('rep_competence', $user))
        {
            $data['insertUserResult'] = '<br />增加管理员成功';
        }
        else 
        {
            $data['inserUserResult'] = '<br />增加管理员失败';
        }
                
        $this->load->view('templates/header', $data);
        $this->load->view('admin/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function project_edit( $id )    
    {
        $page = 'project_edit';
        if ( ! file_exists('application/views/admin/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "修改项目";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $this->db->select('pid,projectname,dbname,is_valid');
        $this->db->where('id', $id); 
        $query = $this->db->get('sys_project');
        foreach ($query->result_array() as $row)
        {
           $data['user'][] = $row;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/'.$page, $data);
        $this->load->view('templates/footer');
    }
}

