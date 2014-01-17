<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
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
$mail->Body = ' 
<html><head> 
<meta http-equiv="Content-Language" content="zh-cn"> 
<meta http-equiv="Content-Type" content="text/html; charset=GB2312"></head> 
<body> 
欢迎来到<a href="http://www.cgsir.com">http://www.cgsir.com</a> <br /><br /> 
感谢您注册为本站会员！<br /><br /> 
</body> 
</html> 
'; 

$mail->AltBody ="text/html"; 
if(!$mail->Send()) 
{ 
echo "邮件发送有误 <p>"; 
echo "邮件错误信息: " . $mail->ErrorInfo; 
exit; 
} 
else { 
echo "$user_name 邮件发送成功!<br />"; 
} 
} 

// 参数说明(发送到, 邮件主题, 邮件内容, 附加信息, 用户名) 
smtp_mail('931307340@qq.com', '欢迎来到cgsir.com！', 'NULL', 'cgsir.com', 'username'); 

?> 
</body>
</html>