/* 
 * File : sales_rate_rank.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-16
 */
$(document).ready(function(){
    
    //默认加载昨天的
    $('#t1').css('background-color','#ccc');
    get_rank();
    
    $(".switch").bootstrapSwitch();
    
    //选择的时间跨度
    $('.r input').click(function(){
        
        $('.r input').css('background-color','#fff');
        $(this).css('background-color','#ccc');        
        get_rank();
    });
    
    //选择项目
    $('#db-select').change(function(){  
        get_rank();
    });
   
  });
  
  function get_rank(){
      
            //显示正在加载 
            $('#rank').html('<img src="public/images/loading.gif" alt="loading..." />');
            $(".btn-group input ").each(function(){
                if($(this).css("background-color") === 'rgb(204, 204, 204)')
                    str = $(this).val();
             });
            timeSpan = str.replace(/[^0-9]/g,"");
            if(timeSpan === '')
                timeSpan = 1;
            
            $.ajax({
	    url:'rank_list/product_sales_rank_data',
            type:'POST',
            data:'time='+ timeSpan +'&db='+$('#db-select').val(),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    var html='<table class="project-table">\n\
                                <tr>\n\
                                <th>销量</th>\n\
                                <th>skuid</th>\n\
                                <th>价格</th>\n\
                                </tr>';
                    for(var i = 0; i < eval(json).length; i++)
                    {
                        html += '<tr><td>' + json[i]['total'] + '</td>\n\
                                <td>' + json[i]['skuid'] + '</td>\n\
                                <td>' + json[i]['price'] + '</td>\n\
                                </tr>' ;
                    }
                    html += '</table>';
                    $('#rank').html(html);
            }
            else
                    $('#rank').html("<h2>&nbsp;&nbsp;&nbsp;&nbsp;没有找到符合条件的数据</h2>");
            }
	  });//ajax函数结束
          }
  


