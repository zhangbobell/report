/* 
 * File : reload-and-validate.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2013-12-31
 */
function get_captcha() {
    $.get("main/get_captcha", function(data){
        $('#captcha-image').html(data);
    });
};
$(function(){
    get_captcha();
    $('#reload-captcha').click(get_captcha);
});

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
          if($.trim($("#captcha-input").val())==="")
          {
		$("#login_info").html("<i class=\"icon-sign-error\"></i>请填写验证码");
                $("#captcha-input").focus();
                return false;
          }
          
          $("#submit").val("正在登录...");
          //验证输入的验证码字段和用户名，密码
          $.ajax({
	    url:'main/validate',
            type:'POST',
            data:'captcha='+$('#captcha-input').val()+'&username='+$('#username').val()+'&password='+$.md5($('#password').val()),
            dataType:'json',
            success:function(json){
                if(json === 0)
                {
                    $("#submit").val("登  录");
                    $("#login_info").html("<i class='icon-sign-error'></i>用户名密码不匹配");
                    get_captcha();
                    $("#captcha-input").val('');   
                }
                else if(json === 2)
                {
                    $("#submit").val("登  录");
                    $("#login_info").html("<i class='icon-sign-error'></i>验证码输入不正确");
                    get_captcha();
                    $("#captcha-input").val('');
                    $("#captcha-input").focus();
                }
                else if(json === 1)
                   window.location.href = 'admin/';
                else
                {
                    $("#submit").val("登  录");
                   $("#login_info").html("<i class='icon-sign-error'></i>未知错误"); 
                }
            }
	  });
  });
});
