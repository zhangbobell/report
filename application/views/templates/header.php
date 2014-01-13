<?php
if(!array_key_exists('logged_in', $this->session->all_userdata()))
 {
    header("content-type: text/html; charset=utf8");
    echo "该页面需要登录";
    echo "<br />正在跳转到登录页面...";
    
    $returnPage = base_url();
    echo "<br />如果浏览器没有反应，请点击<a href=\"". $returnPage ."\">这里</a>.";
    echo "<script type='text/javascript'>";  
    echo "window.setTimeout(\"window.location='". $returnPage ."'\",1000); ";  
    echo "</script>";
    exit();
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <base href="<?php echo base_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?> -- 追灿数据决策系统 </title>
<link href="<?php echo base_url().CSS_DIR; ?>/zxx.lib.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().CSS_DIR; ?>/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().CSS_DIR; ?>/index.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url().JS_DIR; ?>/jquery.js"  type="text/javascript" ></script>
<script src="<?php echo base_url().JS_DIR; ?>/jquery.md5.js"  type="text/javascript" ></script>
<script src="<?php echo base_url().JS_DIR; ?>/admin-public.js"  type="text/javascript" ></script>
