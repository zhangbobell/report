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
                
          
          $data['title'] = "追灿数据决策系统";

          $this->load->view('main/'.$page, $data);
          $this->load->view('templates/footer', $data);

        }

    }

