/* 
 * File : admin-public.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */
/* This jQuery script is to achieve the public effect of the admin page */
$(document).ready(function(){
    
  	//当前链接突出显示
        var isIndex = true;//是否是首页
        
	for(var i = 1; i < ($("ul li a").length); i++)
	{
		if((window.location.href).indexOf($("ul li a")[i].href)>=0)
                {
			$("ul li a")[i].style.backgroundColor="#303030";
                        isIndex = false;
                }           
	}
        
        if(isIndex && ((window.location.href) === ($("ul li a")[0].href)) )
        {
            $("ul li a")[0].style.backgroundColor="#303030";
        }
});


