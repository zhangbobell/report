function deleteText(fileName, fileHash)
{
    $.ajax({
            url:'upload/deleteText',
            type:'POST',
            data:'fileName='+fileName+'&fileHash='+fileHash,
            dataType:'json',
            success:function(json)
            {
                if(json === 1)
                {
                    $('#upload-info').text("删除成功");
                    loadUploadText();
                    $('#upload-info').fadeOut("2000");
                }
                else
                {
                    $('#upload-info').text("删除失败");
                    loadUploadText();
                    $('#upload-info').fadeOut("2000");
                }
            }
          });//ajax函数结束
}

function loadUploadText()
{
    $.ajax({
            url:'upload/loadUploadText',
            type:'POST',
            dataType:'json',
            success:function(json)
            {
                if(json !== null)
                {
                    var html='<p>上传的文档：</p>\n\
                                <table class="project-table">\n\
                                <tr>\n\
                                <th>上传时间</th>\n\
                                <th>用户名</th>\n\
                                <th>文件名</th>\n\
                                <th>操作</th>\n\
                                </tr>';
                    for(var i = 0; i < eval(json.createtime).length; i++)
                    {
                        html += '<tr><td>' + json['createtime'][i] + '</td>\n\
                                <td>' + json['username'][i] + '</td>\n\
                                <td><a href="'+ json['filePath'][i] +'">'+json['content'][i]+'</a></td>\n\
                                <td><a onclick="deleteText(\''+json['content'][i]+'\', \''+json['fileHash'][i]+'\')" class="ico del">删除</a></td>\n\
                                </tr>' ;
                    }
                    html += '</table>';
                    $('#uploadText').html(html);
            }
            }
          });//ajax函数结束
}

$(document).ready(function(){
    loadUploadText();
});
        
