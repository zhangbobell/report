/* 
 * File : gen-cn-and-pid.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */
/* This jQuery script is to get the chinese spell asynchronously */
$(document).ready(function(){
  $("#gen-cn-pid").click(function(){
  htmlobj=$.ajax({
	 url:'gen_cn_pid',
         type:'POST',
         data:"projectName="+$("#project-name").val(),
         dataType:'json',
         success:function(json){
			 $("#project-db").val(json[0]);
			 $("#pid").val(json[1]);
         }
	  });
  });
});


