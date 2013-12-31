<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?> </title>
<link href="public/css/zxx.lib.css" rel="stylesheet" type="text/css" />
<link href="public/css/index.css" rel="stylesheet" type="text/css" />
</head>
    <body class="bg">
        <div class="content">
            <form class="login-form" method="post" action="#">
                <label class="form-title">用户登录</label>
                <p><input type="text" name="username" id="username" class="form-control" placeholder="用户名" autofocus="autofocus"/></p>
		<p><input type="password" name="password" id="password" class="form-control" placeholder="密码"/></p>
                <p><input type="text" name="captcha" id="captcha-input" class="form-control" placeholder="验证码"/></p>
                <div class="captcha"><?php echo $cap['image']; ?><span> 看不清？<a id="captcha-change" onclick="refreshCaptcha()">换一张</a></span></div>
		<p><div id="login_info"></div>
		<input type="submit" value="登  录" class="login-btn"/></p>
            </form>
        </div>