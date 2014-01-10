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
    
    
}

