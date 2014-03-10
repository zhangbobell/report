// JavaScript Document

var text_upload=new Array();//初始化数组，存储已经上传的Text文件名
var hash=new Array();//初始化数组，存储服务器上的txt文件名
var i=0;//初始化数组下标
var cur=0;
$(function() {
    $('#file_upload').uploadify({
    	'auto'     : false,//关闭自动上传
    	'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : 'public/images/uploadify.swf',
        'uploader' : 'uploadText',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
	'buttonText' : '选择记录Text',//设置按钮文本
        'multi'    : true,//允许同时上传多张图片
        'uploadLimit' : 20,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'Text Files',//只允许上传图像
        'fileTypeExts' : '*.txt',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过20000KB 
		/*'onUploadStart' : function(file) {       
				var keyWords=$('#key').val();      
				 if(keyWords.replace(/\s/g,'') == ''){     
					  alert("关键字不能为空！");
					  $('#file_upload').uploadify('stop');     
					  return false;     
				 }
		},*/
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
               text_upload[i]=file.name;
               hash[i]=data;
               i++;
               //alert('文件 ' + file.name + ' 上传成功.详细信息： ' + response + ':' + data);
               //alert(img_id_upload);
                //window.location.href='upload/parseText/' + file.name; // 跳转到解析页面
          
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            //if(img_id_upload.length>0)
            //alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
		   //document.getElementById('key').value = "";
		   //location.reload();	
		   //location.reload();
		   //$('#key').val() = "";
            //alert(text_upload[0]);
            //alert(text_upload[1]);
            //alert(text_upload.length);

            function parseText()
            {
                if(cur>=text_upload.length)
                {
                    cur=0;
                    i=0;
                    text_upload=[];
                    return;
                }
                var tempName = text_upload[cur];
                var hashName = hash[cur];
                cur++;
                $('#upload-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />&nbsp;"+(cur) +"/"+ text_upload.length +" : 正在解析"+tempName+"，请稍候...<br />PS: 100kb的txt文件大约需要1分钟，请耐心等待。");
                $.ajax({
                url:'upload/parseText',
                type:'POST',
                data:'fileName='+hashName,
                dataType:'json',
                success:function(json)
                {
                    if(json === 1)
                    {
                        $('#upload-info').text((cur) +"/"+ text_upload.length + " : "+ tempName+"解析成功。");
                    }
                    else
                    {
                        $('#upload-info').text((cur) +"/"+ text_upload.length + " : "+ tempName+"解析失败。");
                    }
                    
                    parseText();
                }
              });//ajax函数结束
            }
            
            parseText();
            loadUploadText();
        }  
        // Put your options here    		
		
    });
});