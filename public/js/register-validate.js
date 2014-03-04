/* 
 * File : reload-and-validate.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-03-03
 */

$(document).ready(function(){
  $("#submit").click(function(event){
          event.preventDefault();
          
          //验证三个输入框是否为空
          if($.trim($("#username").val())==="")
          {
		$("#login_info").html("<i class=\"icon-sign-error\"></i>请填写用户名");
                $("#username").focus();
                return false;
          }
          if($.trim($("#password").val())==="")
          {
		$("#login_info").html("<i class=\"icon-sign-error\"></i>请填写密码");
                $("#password").focus();
                return false;
          }
          if($.trim($("#password").val())!==$.trim($("#password2").val()))
          {
		$("#login_info").html("<i class=\"icon-sign-error\"></i>两次输入密码不一致");
                $("#password2").focus();
                return false;
          }
          
          $("#submit").val("正在注册...");
          //验证输入的验证码字段和用户名，密码
          $.ajax({
	    url:'main/register_validate',
            type:'POST',
            data:'username='+$('#username').val()+'&password='+$.md5($('#password').val()),
            dataType:'json',
            success:function(json){
                if(json === 0)
                {
                    $("#submit").val("注册");
                    $("#login_info").html("<i class='icon-sign-error'></i>注册失败，请联系管理员。");
                    get_captcha();
                    $("#captcha-input").val('');   
                }
                else if(json === 1)
                {
                    $("#submit").val("注册");
                    $("#login_info").html("<i class='icon-sign-error'></i>注册成功， 点击<a href=\"\">这里</a>回到登录页面");
                }
                else if(json === 2)
                {
                    $("#submit").val("注册");
                    $("#login_info").html("<i class='icon-sign-error'></i>该用户名已经被注册过");
                }
                else
                {
                    $("#submit").val("注 册");
                   $("#login_info").html("<i class='icon-sign-error'></i>未知错误"); 
                }
            }
	  });
  });
});
