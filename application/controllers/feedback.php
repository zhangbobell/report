<?php

/* 
 * File : feedback.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-17
 */
class Feedback extends CI_Controller
{
    function __construct() {
        parent::__construct();
        {
            ok;
        }
    }
    
    public function index()
    {
        $data['title']='意见反馈';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('feedback/index');
        $this->load->view('templates/footer');
    }
}

