<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo base_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?> -- 追灿数据决策系统 </title>
<link href="<?php echo base_url().CSS_DIR; ?>/zxx.lib.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().CSS_DIR; ?>/index.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url().JS_DIR; ?>/jquery.js"  type="text/javascript" ></script>
<script src="<?php echo base_url().JS_DIR; ?>/jquery.md5.js"  type="text/javascript" ></script>
<script src="<?php echo base_url().JS_DIR; ?>/register-validate.js"  type="text/javascript" ></script>
</head>
    <body class="bg">
        <div class="content">
            <form class="login-form" method="post" action="">
                <label class="form-title">用户注册</label>
                <p><input type="text" name="username" id="username" class="form-control" placeholder="用户名" autofocus="autofocus"/></p>
                <p><input type="password" name="password" id="password" class="form-control" placeholder="密码"/></p>
                <p><input type="password" name="password2" id="password2" class="form-control" placeholder="确认密码"/></p>
		<p><div id="login_info"></div>
		<input id="submit" type="submit" value="注 册" class="login-btn"/></p>
            </form>
        </div>