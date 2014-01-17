<?php

/* 
 * File : index.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-17
 */
require_once("phpmailer/class.phpmailer.php"); 

function smtp_mail ( $sendto_email, $subject, $body, $extra_hdrs, $user_name) { 
$mail = new PHPMailer(); 
$mail->IsSMTP(); // send via SMTP 
$mail->Host = "smtp.exmail.qq.com"; // SMTP servers 
$mail->SMTPAuth = true; // turn on SMTP authentication 
$mail->Username = "noreply@e-corp.cn"; // SMTP username 注意：普通邮件认证不需要加 @域名 
$mail->Password = "zczx2123cc"; // SMTP password 

$mail->From = "noreply@e-corp.cn"; // 发件人邮箱 
$mail->FromName = "noreply"; // 发件人 

$mail->CharSet = "utf-8"; // 这里指定字符集！ 
$mail->Encoding = "base64"; 

$mail->AddAddress($sendto_email,"931307340"); // 收件人邮箱和姓名 
$mail->AddReplyTo("931307340@qq.com"); 

//$mail->WordWrap = 50; // set word wrap 
//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment 
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); 
$mail->IsHTML(true); // send as HTML 
// 邮件主题 
$mail->Subject = $subject; 
// 邮件内容 
$mail->Body = $body;
/*' 
<html><head> 
<meta http-equiv="Content-Language" content="zh-cn"> 
<meta http-equiv="Content-Type" content="text/html; charset=GB2312"></head> 
<body> 
欢迎来到<a href="http://www.cgsir.com">http://www.cgsir.com</a> <br /><br /> 
感谢您注册为本站会员！<br /><br /> 
</body> 
</html> 
'; */

$mail->AltBody ="text/html"; 
if(!$mail->Send()) 
{ 
echo "发表意见失败 <p>"; 
echo "错误信息: " . $mail->ErrorInfo; 
exit; 
} 
else { 
echo "$user_name 意见反馈成功!<br />"; 
} 
} 

// 参数说明(发送到, 邮件主题, 邮件内容, 附加信息, 用户名)
if(isset($_POST['comment']) && $_POST['comment']!='' && isset($_POST['email']) && $_POST['email']!='')
{
    smtp_mail('931307340@qq.com', 'report平台意见反馈', '来自'.$_POST['email'].'的意见：<br />'.$_POST['comment'], 'cgsir.com', '您好，'); 
}
else if(isset($_POST['comment']) && $_POST['comment']=='')
{
    echo '<br />请填写意见';
}
else if(isset($_POST['email']) && $_POST['email']=='')
{
    echo '<br />请填写邮件地址';
}
else
{
    echo '<div class="mt10 ml20 bgwh">
            <form action="feedback/index" method="post">
                您的邮箱：<input type="text" placeholder="请输入您的邮箱" width="10" class="mt10 mb10" name="email"><br />
                您的意见：<br /><textarea rows="10" cols="25" name="comment" class="ml80 mb10" placeholder="请留下您的意见"></textarea><br />
                <input class="ml80" type="submit" name="submit" value="提交">
            </form>
        </div>';
}