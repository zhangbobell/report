<?php
 
class Smart extends CI_Controller
{
    function __construct() {
        parent::__construct();
    }
    
    public function smartHome()
    {
        $data['title']='Smart Reporting';
        $this->load->view('templates/header',$data);
        $this->load->view('smart/header_add_smartHome');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('smart/smartHome');
        $this->load->view('templates/footer');
    }
    
    public function stackedBarChartData()
    {
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('db_sanqiang');
        $this->load->database($etc_privileges);
        
        $data;
        $sql="SELECT `sellernick`, 
                sum(if(`created` = '2014-04-08',`number`,0)) as `04-08`,
                sum(if(`created` = '2014-04-07',`number`,0)) as `04-07`,
                sum(if(`created` = '2014-04-06',`number`,0)) as `04-06`,
                sum(if(`created` = '2014-04-05',`number`,0)) as `04-05`,
                sum(if(`created` = '2014-04-04',`number`,0)) as `04-04`,
                sum(if(`created` = '2014-04-03',`number`,0)) as `04-03`,
                sum(if(`created` = '2014-04-02',`number`,0)) as `04-02`
                FROM `status_order` 
                WHERE `sellernick` is not null AND `sellernick` !='三枪家诚专卖店'
                GROUP BY `sellernick`
                ORDER BY `04-08` DESC
                LIMIT 51";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $key => $item)
        {
            $data[$key]['state'] =$item['sellernick'];        
            $data[$key]['04-02']=$item['04-02'];
            $data[$key]['04-03']=$item['04-03'];
            $data[$key]['04-04']=$item['04-04'];
            $data[$key]['04-05']=$item['04-05'];
            $data[$key]['04-06']=$item['04-06'];
            $data[$key]['04-07']=$item['04-07'];
            $data[$key]['04-08']=$item['04-08'];
        }
        
        echo json_encode($data);
        
        //===========================  以下为test库内的原始数据  ==============================
        /*$data;
        $sql="SELECT * FROM `d3testjson`";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $key => $item)
        {
            $data[$key]['state'] =$item['state'];
            $data[$key]['Under 5 Years']=$item['Under 5 Years'];
            $data[$key]['5 to 13 Years']=$item['5 to 13 Years'];
            $data[$key]['14 to 17 Years']=$item['14 to 17 Years'];
            $data[$key]['18 to 24 Years']=$item['18 to 24 Years'];
            $data[$key]['25 to 44 Years']=$item['25 to 44 Years'];
            $data[$key]['45 to 64 Years']=$item['45 to 64 Years'];
            $data[$key]['65 Years and Over']=$item['65 Years and Over'];
        }
        
        echo json_encode($data);*/
    }
}
