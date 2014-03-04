<?php require 'chineseSpell.php';?>
<?php

/* 
 * File : competence-management.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-8
 */

class Competence_management extends CI_Controller
{
    function __construct() {
        parent::__construct();
        
        if(array_key_exists('groupID', $this->session->all_userdata()))
        {
            if($this->session->userdata('groupID') != '0')
            {
                $page = 'error-lack-competence';
                $data['title'] = "权限不足";

                $this->load->view('templates/header', $data);
                $this->load->view('templates/banner');
                $this->load->view('templates/sidebar');
                $this->load->view('competence_management/'.$page, $data);
                $this->load->view('templates/footer');
            }
        }
        else
        {
            $page = 'error-not-login';
                $data['title'] = "没有登录";

                $this->load->view('templates/header', $data);
                $this->load->view('templates/banner');
                $this->load->view('templates/sidebar');
                $this->load->view('competence_management/'.$page, $data);
                $this->load->view('templates/footer');
        }
    }
    
    public function project_management( $pageNum = '0' )    
    {
        $page = 'project-management';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "项目管理";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        /*$query = $this->db->get('etc_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'][] = $row;
        }*/
        $sql = 'SELECT
                `etc_project`.*,
                `etc_user`.`username`
                FROM 
                `etc_project` 
                LEFT JOIN 
                `rep_competence`
                ON `etc_project`.`pid` = `rep_competence`.`pid`
                LEFT JOIN
                `etc_user`
                ON  `rep_competence`.`uid` = `etc_user`.`userid` ';
        $query = $this->db->query($sql);
        $totalRecord = $query->num_rows();
        
        $sql = $sql.' LIMIT '. $pageNum .', 20 ';
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
           $data['project'][] = $row;
        }
        //合并项目管理员
        $rs_arr = array();
	$j = 0;
	$isUnique = true;
	for($i = 0; $i < count($data['project']); $i++)
	{
		if($isUnique)
		{
			foreach($data['project'][$i] as $key => $val)
                        {
				$rs_arr[$j][$key] = $val;
                        }
			$isUnique = false;
			$uniqueIndex = $i;
			continue;
		}
		
		if($data['project'][$uniqueIndex]['pid'] == $data['project'][$i]['pid'])
		{
			$rs_arr[$j]['username'] .= ", ".$data['project'][$i]['username'];	
		}
		else if($data['project'][$uniqueIndex]['pid'] != $data['project'][$i]['pid'])
		{
			$j++;
			foreach($data['project'][$i] as $key => $val)
                        {
				$rs_arr[$j][$key] = $val;
                        }
			$isUnique = false;
			$uniqueIndex = $i;
		}
	}
        $data['project'] = $rs_arr;
        //创建分页
        $this->load->library('pagination');

        $config['base_url'] = base_url().'competence_management/project_management/';
        $config['total_rows'] = $totalRecord;
        $config['per_page'] = 20; 

        $this->pagination->initialize($config); 

        $data['partipation']=$this->pagination->create_links();
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function project_add( $page = 'project-add' )    
    {
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "增加项目";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $this->db->select('userid,username');
        $query = $this->db->get('etc_user');
        foreach ($query->result_array() as $row)
        {
           $data['user'][] = $row;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
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
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
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
               'dbname' => 'db_'.$this->input->post('project-db', TRUE),
               'is_valid' => $this->input->post('is_valid', TRUE)
            );

        if($this->db->insert('etc_project', $project))
        {
            $data['insertProjectResult'] = '增加项目成功';
        }
        else 
        {
            $data['insertProjectResult'] = '增加项目失败';
        }
        
        $user = array();
        if($this->input->post('padmin', TRUE))
        {
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
                $data['insertUserResult'] = '<br />增加管理员失败';
            }
        }
        else
        {
            $data['insertUserResult'] = '<br />你没有为该项目指定管理员';
        }
                
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function project_edit( $id )    
    {
        $page = 'project-edit';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "修改项目";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        //获取项目信息
        $this->db->select('pid,projectname,dbname,is_valid');
        $this->db->where('id', $id); 
        $query = $this->db->get('etc_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'] = $row;
        }
        
        //获取项目管理员
        $this->db->select('uid');
        $this->db->where('pid', $data['project']['pid']); 
        $query = $this->db->get('rep_competence');
        if($query->result_array())
        {
            foreach ($query->result_array() as $row)
            {
               $data['user'][] = $row['uid'];
            }
        }
        else 
        {
            $data['user'][] = null;
        }
        
        //获取所有管理员
        $this->db->select('userid,username');
        $query = $this->db->get('etc_user');
        foreach ($query->result_array() as $row)
        {
           $data['userAll'][] = $row;
        }
        $data['id'] = $id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function project_edit_data( $id )    
    {
        $page = 'project-edit-data';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "修改项目结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $project = array(
               'pid' => $this->input->post('pid', TRUE) ,
               'projectname' => $this->input->post('project-name', TRUE) ,
               'dbname' => 'db_'.$this->input->post('project-db', TRUE),
               'is_valid' => $this->input->post('is_valid', TRUE)
            );
        
        $this->db->where('id', $id); 
        if($this->db->update('etc_project', $project))
        {
            $data['updateProjectResult'] = '修改项目成功';
        }
        else 
        {
            $data['updateProjectResult'] = '修改项目失败';
        }
        
        //删除旧的授权关系
        $this->db->where('pid', $this->input->post('pid', TRUE));
        $this->db->delete('rep_competence'); 
        
        $user = array();
        if($this->input->post('padmin', TRUE))
        {
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
                $data['updateUserResult'] = '<br />修改项目管理员成功';
            }
            else 
            {
                $data['updateUserResult'] = '<br />修改项目管理员失败';
            }
        }
        else
        {
            $data['updateUserResult'] = '<br />你没有为该项目指定管理员';
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function project_delete( $id )    
    {
        $page = 'project-delete';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "删除项目结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        //获取项目编号，为删除项目管理员做准备
        $this->db->select('pid');
        $this->db->where('id', $id); 
        $query = $this->db->get('etc_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'] = $row;
        }
        //删除项目信息
        $this->db->where('id', $id);
        if($this->db->delete('etc_project'))
        {
            $data['deleteProjectResult'] = '删除项目成功';
        }
        else 
        {
            $data['deleteProjectResult'] = '删除项目失败';
        }
        
        //删除项目管理员权限信息
        $this->db->where('pid', $data['project']['pid']);
        if($this->db->delete('rep_competence'))
        {
            $data['deleteUserResult'] = '<br />删除项目管理员成功';
        }
        else 
        {
            $data['deleteUserResult'] = '<br />删除项目管理员失败';
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function user_management( $pageNum = '0' )    
    {
        $page = 'user-management';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "用户管理";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $sql = 'SELECT
                `etc_user`.`userid`,
                `etc_user`.`username`,
                `etc_user`.`group`,
                `etc_project`.`projectname`,
                `etc_user`.`is_valid`
                FROM 
                `etc_user` 
                LEFT JOIN 
                `rep_competence`
                ON `rep_competence`.`uid` = `etc_user`.`userid`
                LEFT JOIN
                `etc_project`
                ON `etc_project`.`pid` = `rep_competence`.`pid`';
        
        $query = $this->db->query($sql);
        $totalRecord = $query->num_rows();
        
        $sql = $sql.' LIMIT '. $pageNum .', 20 ';
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
           $data['user'][] = $row;
        }
        
        //合并项目
        $rs_arr = array();
	$j = 0;
	$isUnique = true;
	for($i = 0; $i < count($data['user']); $i++)
	{
		if($isUnique)
		{
			foreach($data['user'][$i] as $key => $val)
                        {
				$rs_arr[$j][$key] = $val;
                        }
			$isUnique = false;
			$uniqueIndex = $i;
			continue;
		}
		
		if($data['user'][$uniqueIndex]['userid'] == $data['user'][$i]['userid'])
		{
			$rs_arr[$j]['projectname'] .= ", ".$data['user'][$i]['projectname'];	
		}
		else if($data['user'][$uniqueIndex]['userid'] != $data['user'][$i]['userid'])
		{
			$j++;
			foreach($data['user'][$i] as $key => $val)
                        {
				$rs_arr[$j][$key] = $val;
                        }
			$isUnique = false;
			$uniqueIndex = $i;
		}
	}
        $data['user'] = $rs_arr;
        //创建分页
        $this->load->library('pagination');

        $config['base_url'] = base_url().'competence_management/user_management/';
        $config['total_rows'] = $totalRecord;
        $config['per_page'] = 20; 

        $this->pagination->initialize($config); 

        $data['partipation']=$this->pagination->create_links();
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function user_add( $page = 'user-add' )    
    {
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "增加用户";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        //生成授权项目多选列表
        $this->db->select('pid,projectname');
        $query = $this->db->get('etc_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'][] = $row;
        }
        
        //生成组别
        $sql = 'SELECT DISTINCT `groupid` ,`group` FROM `etc_user` ';
        $query = $this->db->query( $sql );
        foreach ($query->result_array() as $row)
        {
           $data['group'][] = $row;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
     public function user_add_data( $page = 'user-add-data' )    
    {
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "增加项目结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        $groupMap = array(
            '0' => '系统管理员',
            '1' => '运营',
            '2' => '维权专员',
            '3' => '数据分析',
            '4' => '招商客服',
            '5' => '招商管理员',
            '6' => '客户经理',
        );
        
        $project = array(
               'username' => $this->input->post('username', TRUE) ,
               'password' => md5($this->input->post('password', TRUE)) ,
               'groupid' => $this->input->post('group', TRUE),
               'group' => $groupMap[$this->input->post('group', TRUE)],
               'is_valid' => $this->input->post('is_valid', TRUE)
            );

        if($this->db->insert('etc_user', $project))
        {
            $data['insertUserResult'] = '增加用户成功';
        }
        else 
        {
            $data['insertUserResult'] = '增加用户失败';
        }
        
        $sql = 'SELECT LAST_INSERT_ID()';
        $query = $this->db->query( $sql );
        $qry_arr = $query->result_array();
        $userid = $qry_arr[0]['LAST_INSERT_ID()'];
        
        $project = array();
        if($this->input->post('auth-project', TRUE))
        {
            foreach( $this->input->post('auth-project', TRUE) as $pid)
            {
                $temp = array(
                  'uid' => $userid ,
                  'pid' => $pid
                );
                $project[] = $temp; 
            }

            if($this->db->insert_batch('rep_competence', $project))
            {
                $data['insertAuthProjectResult'] = '<br />增加授权项目成功';
            }
            else 
            {
                $data['insertAuthProjectResult'] = '<br />增加授权项目失败';
            }
        }
        else
        {
            $data['insertAuthProjectResult'] = '<br />你没有为该用户授权项目';
        }
                
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page, $data);
        $this->load->view('templates/footer');
    }
    
    public function user_edit( $id )    
    {
        $page = 'user-edit';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "修改用户";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        //获取用户信息
        $this->db->select('userid, username, groupid, group, is_valid');
        $this->db->where('userid', $id); 
        $query = $this->db->get('etc_user');
        foreach ($query->result_array() as $row)
        {
           $data['user'] = $row;
        }
        
        //获取用户授权项目信息
        $this->db->select('pid');
        $this->db->where('uid', $data['user']['userid']); 
        $query = $this->db->get('rep_competence');
        if($query->result_array())
        {
            foreach ($query->result_array() as $row)
            {
               $data['authProject'][] = $row['pid'];
            }
        }
        else 
        {
            $data['authProject'][] = null;
        }        
        
        //获取所有项目
        $this->db->select('pid,projectname');
        $query = $this->db->get('etc_project');
        foreach ($query->result_array() as $row)
        {
           $data['project'][] = $row;
        }
        
        //生成组别
        $sql = 'SELECT DISTINCT `groupid` ,`group` FROM `etc_user` ';
        $query = $this->db->query( $sql );
        foreach ($query->result_array() as $row)
        {
           $data['group'][] = $row;
        }
        
        $data['id'] = $id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function user_edit_data( $id )    
    {
        $page = 'user-edit-data';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "修改用户结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);

        $groupMap = array(
            '0' => '系统管理员',
            '1' => '运营',
            '2' => '维权专员',
            '3' => '数据分析',
            '4' => '招商客服',
            '5' => '招商管理员',
            '6' => '客户经理',
        );
        
        
        $project = array(
               'username' => $this->input->post('username', TRUE) ,
               'groupid' => $this->input->post('group', TRUE),
               'group' => $groupMap[$this->input->post('group', TRUE)],
               'is_valid' => $this->input->post('is_valid', TRUE)
            );
        if($this->input->post('password', TRUE))
        {
            $project['password']=md5($this->input->post('password', TRUE));
        }
        
        $this->db->where('userid', $id); 
        if($this->db->update('etc_user', $project))
        {
            $data['updateUserResult'] = '修改用户成功';
        }
        else 
        {
            $data['updateUserResult'] = '修改用户失败';
        }
        
        $this->db->where('uid', $id);
        $this->db->delete('rep_competence'); 
        
        $project = array();
        if($this->input->post('auth-project', TRUE))
        {
            foreach( $this->input->post('auth-project', TRUE) as $pid)
            {
                $temp = array(
                  'uid' => $id ,
                  'pid' => $pid
                );
                $project[] = $temp; 
            }

            if($this->db->insert_batch('rep_competence', $project))
            {
                $data['updateAuthProjectResult'] = '<br />修改授权项目成功';
            }
            else 
            {
                $data['updateAuthProjectResult'] = '<br />修改授权项目失败';
            }
        }
        else
        {
            $data['updateAuthProjectResult'] = '<br />你没有为该用户授权项目';
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
    
    public function user_delete( $id )    
    {
        $page = 'user-delete';
        if ( ! file_exists('application/views/competence_management/'.$page.'.php'))
        {
          show_404();
        }
        $data['title'] = "删除用户结果";
        
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        //删除用户信息
        $this->db->where('userid', $id);
        if($this->db->delete('etc_user'))
        {
            $data['deleteUserResult'] = '删除用户成功';
        }
        else 
        {
            $data['deleteUserResult'] = '删除用户失败';
        }
        
        //删除项目管理员权限信息
        $this->db->where('uid', $id);
        if($this->db->delete('rep_competence'))
        {
            $data['deleteProjectResult'] = '<br />删除项目管理员成功';
        }
        else 
        {
            $data['deleteProjectResult'] = '<br />删除项目管理员失败';
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('competence_management/header-add');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('competence_management/'.$page);
        $this->load->view('templates/footer');
    }
}

