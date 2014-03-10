<?php

/* 
 * File : upload.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-3-4
 */
class Upload extends CI_Controller
{
    function __construct() {
        parent::__construct();
        {
            $this->load->library('session');
        }
    }
    
    public function index()
    {
        $data['title']='旺旺记录上传';
        
        $this->load->view('templates/header',$data);
        $this->load->view('upload/header_add_index');
        $this->load->view('templates/banner');
        $this->load->view('templates/sidebar');
        $this->load->view('upload/index');
        $this->load->view('templates/footer');
    }
    
    public function uploadText()
    {
        set_time_limit(0);
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
	//Key step to make all code well 
	//mysql_query("set names utf8");

	//设置上传目录

	$path = "public/upload/";
	
	if (!empty($_FILES)) {
		
		//得到上传的临时文件流
		$tempFile = $_FILES['Filedata']['tmp_name'];
		
		//允许的文件后缀
		$fileTypes = array('txt'); 
		
		//得到文件原名
		$fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);
		$fileParts = pathinfo($_FILES['Filedata']['name']);
		
		//接受动态传值
		$files=$_POST['typeCode'];
                
		
                
		//最后保存服务器地址
		if(!is_dir($path))
		{
		   	mkdir($path);
		}
                //拼装文件地址
		$fileURL=$path.$fileName;
                //得到该文件唯一标示符
                $hash = md5(date("Y-m-d H:i:s").$fileName.$this->session->userdata("username"));
                $localName = $hash.".txt";
                
		//if(!file_exists($path.$fileName))
		{
			if (move_uploaded_file($tempFile, $path.$localName))
			{
				echo $hash;
                                //存入日志记录
				$sql="INSERT INTO `rep_log` "
                                        . "(`createtime`, `username`, `title`, `content`, `remark`)"
                                        . "VALUES"
                                        . "('".date("Y-m-d H:i:s")."', "
                                        . "'". $this->session->userdata("username") ."',"
                                        . "'upload',"
                                        . "'". $_FILES["Filedata"]["name"] ."', "
                                        . "'". $hash ."')";
                                if(!($query = $this->db->query($sql)))
                                        echo "<br />Insert into db failed.";
                                
                                //开始解析txt文档
                                //$this->parseText($_FILES["Filedata"]["name"]);
                        }
			else
			{
				echo $localName." failed";
			}
		}
		//else
			//echo $fileName." already existed.";
	}
    }
    
    public function parseText()
    {
        set_time_limit(0);
        
        $fileName=$this->input->post('fileName', TRUE);
        
        
        $filePath = base_url().UPLOAD_DIR."/".  $fileName.".txt";
        //特殊字符转义
        $filePath = $this->file_url($filePath);
        
        //$localName =substr($filePath,0,-4).crc32($fileName.$this->session->userdata("username").filesize($filePath)).".txt";
        
        
        $content = file_get_contents($filePath); 
        //echo $content; 
        
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('test');
        $this->load->database($etc_privileges);
        
        $arr = explode("\r\n", $content); 
        //print_r($array);
        //echo "<br /> ************************************************************************************ <br />";
        //echo "<br /> ************************************************************************************ <br />";
        
        
        $blockNum=0;//块号
        $lineNum=0;//行号
        $mode = 1;//解析模式： 1为消息记录模式 2为文件记录模式
        $sql;
        for($i=0; $i<count($arr); $i++) 
        { 
            //echo $array[$i];
            //按行分解
            /*格式如下:
             * result[0] : "2013-11-12"
             * result[1] : "22:29:36"
             * result[2] : "jack6621776:小美(22:29:36):"
             * result[3] : "亲 我是客服 小美"
             */
            $lineNum++;//行号
            //目前没有区别
            if($mode== 1)
            {
                $result = explode(" ",$arr[$i],4);
            }
            else
            {
                $result = explode(" ",$arr[$i],4);
            }
            /*
            * 出现数组大小小于4的情况：
            * 1. 第一行客户昵称
            * 2. 分割线 "=================="
            * 3. "即时消息"或者"文件记录"
            * 4. 聊天块开始
            * 5. 空行
            * 6. 上一条记录的补充
            */
            
            if(count($result)<4)
            {  
                if(substr_count($result[0],"==")>3)//分割线
                {
                    //2.分割线
                    continue;
                }
                else if(iconv("GB2312","UTF-8",$result[0]) == "即时消息" )
                {
                    //3. 即时消息
                    $mode = 1; //进入mode 1 即时消息模式
                    continue;
                }
                else if (iconv("GB2312","UTF-8",$result[0]) == "文件记录") 
                {
                    //3.文件记录
                    $mode = 0; //进入mode 0 文件记录模式
                    continue;
                }
                else if(strlen ($result[0]) == 10 && $result[0][4] == "-" && $result[0][7] == "-")
                {
                    //4. 聊天块开始
                    $blockNum++;
                }
                else if(strlen($result[0])==0)
                {
                    //5. 空行
                    continue;
                }
                else if($lineNum==1)
                {
                    //1.客户昵称
                    continue;
                }
                else
                {
                    //6. 上一条记录的补充
                    $sql="SELECT LAST_INSERT_ID() as `id`";
                    $query = $this->db->query($sql);
                    $id;
                    foreach ($query->result_array() as $item)
                    {
                        $id=$item['id'];
                    }
                    
                    $sql="UPDATE `chart_record` "
                            . "SET "
                            . "`content` = IFNULL(CONCAT(`content`, '". $arr[$i] ."'), '". $arr[$i] ."')"
                            . "WHERE `id` = '". $id ."'";
                    $query = $this->db->query($sql);
                }
            }
            else
            {
                if($mode==1)
                {
                    for($j=0; $j<4; $j++)
                    {
                        $result[$j] = str_replace('"','\"',$result[$j]);//内容处理
                        $result[$j] = str_replace('/','\/',$result[$j]);
                        $result[$j] = str_replace('\'','\\\'',$result[$j]);
                    }
                    $recordtime = $result[0]." ". $result[1];
                    
                    if(strlen($recordtime)!=19)//时间不合法的情况，聊天记录中有三个以上空格
                    {
                        $this->appendContent();
                        continue;
                    }
                    
                    $nick = explode(':', $result[2]);//对昵称字段进行处理，取括号前一段
                    $nick = explode('(', $nick[0]);
                    
                    $sql = "INSERT INTO `chart_record` "
                    . "(`blocknum`, `updatetime`, `nick`, `recordtime`, `recordtype`,`content`, `linenum`, `src`) "
                    . "VALUES "
                    . "('". $blockNum ."', "
                    . "'". date('Y-m-d H:i:s') ."', "
                    . "'". $nick[0] ."', "
                    . "'". $recordtime ."', "
                    . "'1', "
                    . "'". $result[3] ."', "
                    . "'". $lineNum ."', "
                    . "'". iconv("UTF-8","GB2312",$fileName) ."')";
                }
                if($mode==0)
                {  
                    for($j=0; $j<4; $j++)
                    {
                        $result[$j] = str_replace('"','\"',$result[$j]);//内容处理
                        $result[$j] = str_replace('/','\/',$result[$j]);
                        $result[$j] = str_replace('\'','\\\'',$result[$j]);
                    }
                    $recordtime = $result[0]." ". $result[1];
                    
                    if(strlen($recordtime)!=19)//时间不合法的情况，聊天记录中有三个以上空格
                    {
                        $this->appendContent();
                        continue;
                    }
                    
                    $sql = "INSERT INTO `chart_record` "
                    . "(`blocknum`, `updatetime`, `nick`, `recordtime`, `recordtype`,`content`, `linenum`, `src`) "
                    . "VALUES "
                    . "('". $blockNum ."', "
                    . "'". date('Y-m-d H:i:s') ."', "
                    . "'". $result[2] ."', "
                    . "'". $recordtime ."', "
                    . "'0', "
                    . "'". $result[3] ."', "
                    . "'". $lineNum ."', "
                    . "'".iconv("UTF-8","GB2312",$fileName) ."')"; 
                }
                $this->db->query("set names gbk");
                $query = $this->db->query($sql);
            }
        }
        echo "1";
    }
    
    //从stackoverflow上找到的解决办法
    //根本原因在于 urlencode 只需要转义特殊字符就可以，并不是全部，切记！！
    public function file_url($url)
    {
        $parts = parse_url($url);
        $path_parts = array_map('rawurldecode', explode('/', $parts['path']));

        return
        $parts['scheme'] . '://' .
        $parts['host'] .
        implode('/', array_map('rawurlencode', $path_parts))
        ;
    }
    
    public function deleteText()
    {
        
        $fileHash = $this->input->post('fileHash', TRUE);
        $fileName = $this->input->post('fileName', TRUE);
        $filePath = "public/upload/".$this->input->post('fileHash', TRUE).".txt";
        unlink($filePath);
        
        //写入rep_log日志
        $this->load->model('rank_database');
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        $sql = "INSERT INTO `rep_log` (`createtime`, `username`, `title`, `content`, `remark`) "
                . "VALUES('". date("Y-m-d H:i:s") ."', "
                . "'". $this->session->userdata("username") ."', "
                . "'delete', "
                . "'". $fileName ."', "
                . "'". $fileHash ."')";
        
       $query = $this->db->query($sql);
       
       unset($this->db);
       //删除解析结果表chart_record中数据
       $this->load->model('rank_database');
       $etc_privileges = $this->rank_database->select_DB('test');
       $this->load->database($etc_privileges);
       
       $sql = "DELETE FROM `chart_record` WHERE src='". $fileHash ."'";
       $query = $this->db->query($sql);
        
       echo "1";
    }
    
    public function loadUploadText()
    {
        $this->load->model('rank_database');
        //现在使用的是90数据库里面的test库作为测试库
        $etc_privileges = $this->rank_database->select_DB('etc_privileges');
        $this->load->database($etc_privileges);
        
        $sql = "SELECT * FROM `rep_log` WHERE `username`='". $this->session->userdata('username') ."' AND `title`='upload' AND `remark` not in (SELECT `remark` FROM `rep_log` WHERE `title`='delete')";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $item )
        {
            $data['createtime'][]=$item['createtime'];
            $data['username'][]=$item['username'];
            $data['content'][]=$item['content'];
            $data['fileHash'][]= $item['remark'];
            $data['filePath'][]= base_url().UPLOAD_DIR."/".$item['remark'].".txt";
        }
        
        echo json_encode($data);
    }
    
    public function appendContent()
    {
        //6. 上一条记录的补充
        $sql="SELECT LAST_INSERT_ID() as `id`";
        $query = $this->db->query($sql);
        $id;
        foreach ($query->result_array() as $item)
        {
            $id=$item['id'];
        }

        $sql="UPDATE `chart_record` "
                . "SET "
                . "`content` = IFNULL(CONCAT(`content`, '". $arr[$i] ."'), '". $arr[$i] ."')"
                . "WHERE `id` = '". $id ."'";
        $query = $this->db->query($sql);
    }
    
}

