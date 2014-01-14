/* 
 * File : sales_rate_rank.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-13
 */
$(document).ready(function(){
    
     //默认加载昨天的
    $('#t1').css('backgroud-color','#000');
    $('#rank').html('<img src="public/images/loading.gif" alt="loading..." />');
    $.ajax({
      url:'rank_list/sales_rate_rank_data',
      type:'POST',
      data:'time=1&db='+$('#db-select').val(),
      dataType:'json',
      success:function(json){
          var html='<table class="project-table">\n\
<tr>\n\
<th>增长率</th>\n\
<th>旺旺昵称</th>\n\
</tr>';
          for(var i = 0; i < 20; i++)
          {
              html += '<tr><td>' + (json[i]['diff']*100).toFixed(2) + '%</td><td>' + json[i]['sellernick'] + '</td></tr>' ;
          }
          html += '</table>';
          $('#rank').html(html);
      }
    });
    
    
    
    $("#t1").click(function(){
        
      //显示正在加载  
      $('#rank').html('<img src="public/images/loading.gif" alt="loading..." />');
      
            $.ajax({
	    url:'rank_list/sales_rate_rank_data',
            type:'POST',
            data:'time=1&db='+$('#db-select').val(),
            dataType:'json',
            success:function(json){
                var html='<table class="project-table">\n\
<tr>\n\
<th>增长率</th>\n\
<th>旺旺昵称</th>\n\
</tr>';
                for(var i = 0; i < 20; i++)
                {
                    html += '<tr><td>' + (json[i]['diff']*100).toFixed(2) + '%</td><td>' + json[i]['sellernick'] + '</td></tr>' ;
                }
                html += '</table>';
                $('#rank').html(html);
            }
	  });

    });
    
   
  });
  


