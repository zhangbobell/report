/* 
 * File : sales_rate_rank.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-13
 */
$(document).ready(function(){
    
    //默认加载昨天的
    $('#t1').css('background-color','#ccc');
    get_rate_rank();
    
    $(".datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    }).datepicker("setDate","-1d");
   //开关按钮
    $(".switch").bootstrapSwitch();
    
    //选择的时间跨度
    $('.r input').click(function(){
        
        $('.r input').css('background-color','#fff');
        $(this).css('background-color','#ccc');        
        get_rate_rank();
    });
    
    //选择项目
    $('#db-select').change(function(){  
        get_rate_rank();
    });
    //选择追灿或全部
    //全部为false, 追灿为ture
    $('#zhuican-all').on('switch-change', function () {
        get_rate_rank();
});
   
  });
  
  function get_rate_rank(){
      
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
	    url:'rank_list/sales_rate_rank_data',
            type:'POST',
            data:'time='+ timeSpan +'&db='+$('#db-select').val()+'&is_zc='+$('#zhuican-all').bootstrapSwitch('state'),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    var html='<table class="project-table">\n\
                                <tr>\n\
                                <th>增长率</th>\n\
                                <th>旺旺昵称</th>\n\
                                <th>店铺ID</th>\n\
                                <th>本期销量</th>\n\
                                <th>上期销量</th>\n\
                                <th>运营人员</th>\n\
                                </tr>';
                    for(var i = 0; i < eval(json).length; i++)
                    {
                        html += '<tr><td>' + (json[i]['diff']*100).toFixed(2) + '%</td>\n\
                                <td>' + json[i]['sellernick'] + '</td>\n\
                                <td>' + json[i]['shopid'] + '</td>\n\
                                <td>' + json[i]['total_a'] + '</td>\n\
                                <td>' + json[i]['total_b'] + '</td>\n\
                                <td>' + json[i]['account'] + '</td>\n\
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
  


