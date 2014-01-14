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
            $etc_privileges = $this->rank_database->select_DB('test');
            $this->load->database($etc_privileges);
            
            $sql = 'SELECT `userid`,`groupid` '
                    . 'FROM `sys_user` '
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
                $sql = 'SELECT `sys_project`.`projectname`, `sys_project`.`dbname` '
                        . 'FROM `sys_project` '
                        . 'LEFT JOIN `rep_competence` '
                        . 'ON `rep_competence`.`pid` = `sys_project`.`pid` '
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
            }
        }
        
        public function logout()
        {
            $page = 'logout';
            $this->session->sess_destroy();
            $data['title'] = '注销成功';
            $this->load->view('main/'.$page, $data);
            $this->load->view('templates/login-footer');
        }
    }

