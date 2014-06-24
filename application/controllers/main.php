<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    Class Main extends CI_Controller
    {
        function __construct() {
            parent::__construct();
            
            $this->load->helper('captcha');
            $this->load->library('session');
            
        }


        public function index($page = 'index')
        {

          if ( ! file_exists('application/views/main/'.$page.'.php'))
          {
            // 页面不存在
            show_404();
          }
   
          $data['title'] = "用户登录";

          $this->load->view('main/'.$page, $data);
          $this->load->view('templates/login-footer', $data);

        }
        
        public function get_captcha()
        {
            if($this->session->userdata('captcha') != "")
            {
                    $this->del_captcha();
            }
            
            $vals = array(
              'word' => rand(1000, 10000),
              'img_path' => IMG_DIR.'/captcha/',
              'img_url' => IMG_DIR.'/captcha/',
              'img_width' => '100',
              'img_height' => '30',
              'font_path' => PUB_DIR.'/fonts/ank.ttf'
               );

            $cap = create_captcha($vals); 
            $this->session->set_userdata('captcha',$cap['word']);
            $this->session->set_userdata('captcha_url',$cap['time']);
            
            echo $cap['image'];
        }
        
        public function del_captcha() 
        {
            $path = IMG_DIR.'/captcha/'.$this->session->userdata('captcha_url').'.jpg';
            $this->load->helper('file');
            unlink($path);
        }

        public function validate()
        {
            //验证输入的验证码字段
            if($this->input->post('captcha', TRUE) != $this->session->userdata('captcha') )
            {
                echo '2';
                return;
            }

            //验证用户名密码
            $this->load->model('rank_database');
            //现在使用的是90数据库里面的test库作为etc_privileges的测试库
            $etc_privileges = $this->rank_database->select_DB('etc_privileges');
            $this->load->database($etc_privileges);
            
            $sql = 'SELECT `userid`,`groupid` '
                    . 'FROM `etc_user` '
                    . 'WHERE `username` = \''. $this->input->post('username', TRUE) .'\' '
                    . 'AND `password` = \''. $this->input->post('password', TRUE) .'\' ';
            $query = $this->db->query($sql);
            echo $query->num_rows();
            
            if($query->num_rows() === 1)
            {
                //删除验证码
                $this->del_captcha();
                $this->session->unset_userdata('captcha');
                $this->session->unset_userdata('captcha_url');
                
                //设置session数据
                $id = $query->result_array();
                $sql = 'SELECT `etc_project`.`projectname`, `etc_project`.`dbname` '
                        . 'FROM `etc_project` '
                        . 'LEFT JOIN `rep_competence` '
                        . 'ON `rep_competence`.`pid` = `etc_project`.`pid` '
                        . 'WHERE `rep_competence`.`uid` = \''. $id[0]['userid'] .'\' ';
                $query = $this->db->query($sql);
                foreach ($query->result_array() as $row )
                {
                    $authDB[$row['dbname']] = $row['projectname'];
                }
                
                $userdata = array(
                   'username'  => $this->input->post('username', TRUE),
                   'authDB'    => $authDB,
                   'groupID'   => $id[0]['groupid'],
                   'logged_in' => TRUE
               );
                $this->session->set_userdata($userdata);
                
                //存入登录日志rep_log
                /*$sql="INSERT INTO `rep_log` (`createtime`, `username`, `title`, `content`) "
                        . "VALUES ('". date("Y-m-d H:i:s") ."', "
                        . "'". $this->session->userdata('username') ."',"
                        . "'login',"
                        . "'". $this->input->ip_address() ."')";
                if(!($query = $this->db->query($sql)))
                {
                        $this->db->_error_message();
                }*/
            }
        }
        
        public function logout()
        {
            $page = 'logout';
            
            $this->load->model('rank_database');
            //现在使用的是90数据库里面的test库作为etc_privileges的测试库
            $etc_privileges = $this->rank_database->select_DB('etc_privileges');
            $this->load->database($etc_privileges);
            //存入日志
            $sql="INSERT INTO `rep_log` (`createtime`, `username`, `title`, `content`) "
                        . "VALUES ('". date("Y-m-d H:i:s") ."', "
                        . "'". $this->session->userdata('username') ."',"
                        . "'logout',"
                        . "'". $this->input->ip_address() ."')";
            if(!($query = $this->db->query($sql)))
            {
                    $this->db->_error_message();
            }
                
            $this->session->sess_destroy();
            $data['title'] = '注销成功';
            $this->load->view('main/'.$page, $data);
            $this->load->view('templates/login-footer');
        }
        
        public function register()
        {
          $page = "register";
          if ( ! file_exists('application/views/main/'.$page.'.php'))
          {
            // 页面不存在
            show_404();
          }
   
          $data['title'] = "用户注册";

          $this->load->view('main/'.$page, $data);
          $this->load->view('templates/login-footer', $data);

        }
        
        public function register_validate()
        {

            $registerResult;
            //插入用户名密码
            $this->load->model('rank_database');
            //现在使用的是90数据库里面的etc_privileges库
            $etc_privileges = $this->rank_database->select_DB('etc_privileges');
            $this->load->database($etc_privileges);
            
            $is_unique;
            $sql = "SELECT count(`username`) as `is_exist` from `etc_user` WHERE `username` = '". $this->input->post('username', TRUE) ."'";
            $query = $this->db->query($sql);
            foreach ($query->result_array() as $row)
            {
               $is_unique = $row['is_exist'];
            }
            if($is_unique)
            {
                echo '2';
                return;
            }
            
            //唯一则插入
            $sql ='INSERT INTO `etc_user` (`username`, `password`, `group`, `groupid`, `is_valid`) VALUES (\''. $this->input->post('username', TRUE) .'\',\''. $this->input->post('password', TRUE) .'\',\'维权专员\', \'2\', \'1\')';
            $query = $this->db->query($sql);
            if($query)
            {           
                $sql = 'SELECT LAST_INSERT_ID()';
                $query = $this->db->query( $sql );
                $qry_arr = $query->result_array();
                $userid = $qry_arr[0]['LAST_INSERT_ID()'];

                //获取所有项目
                $data;
                $this->db->select('pid');
                $query = $this->db->get('etc_project');
                foreach ($query->result_array() as $row)
                {
                   $data[] = $row['pid'];
                }
                $project = array();
                foreach( $data as $pid)
                {
                    $temp = array(
                      'uid' => $userid ,
                      'pid' => $pid
                    );
                    $project[] = $temp; 
                }

                if($this->db->insert_batch('rep_competence', $project))
                {
                    $registerResult = 1;
                }
                else 
                {
                    $registerResult = 0;//注册失败
                }
            }
            else 
            {
                $registerResult=0;//注册失败
            }
            
            
            
            if($registerResult)
            {
                echo '1';//注册成功
            }
            else 
            {
                echo '0';//注册失败
            }
 
        }
    }

