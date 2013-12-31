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
        }


        public function index($page = 'index')
        {

          if ( ! file_exists('application/views/main/'.$page.'.php'))
          {
            // 页面不存在
            show_404();
          }
         /* {
              $data['cap'] = "load captcha failed";
          }*/
          $vals = array(
              'word' => rand(1000, 10000),
              'img_path' => IMG_DIR.'/captcha/',
              'img_url' => 'http://localhost/report/public/images/captcha/',
              'img_width' => '100',
              'img_height' => '30',
              'font_path' => PUB_DIR.'/fonts/ank.ttf'
               );
          
          $cap = create_captcha($vals);      
          
          $data['cap'] = $cap;
          $data['title'] = "追灿数据决策系统";

          $this->load->view('main/'.$page, $data);
          $this->load->view('templates/footer', $data);

        }

    }

