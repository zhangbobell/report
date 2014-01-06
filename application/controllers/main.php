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
   
          $data['title'] = "追灿数据决策系统";

          $this->load->view('main/'.$page, $data);
          $this->load->view('templates/footer', $data);

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
              'img_url' => 'http://localhost/report/public/images/captcha/',
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
            $etc_privileges = $this->rank_database->select_DB('etc_privileges');
            $this->load->database($etc_privileges);
            
            $this->db->where('username', $this->input->post('username', TRUE));
            $this->db->where('passwd', $this->input->post('password', TRUE));
            $this->db->from('user');
            echo $this->db->count_all_results();
        }
    }

