/* 
 * File : sales_rate_rank.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-15
 */
var jsonRank;
var rankCurFlag=true;

$(document).ready(function(){
    
    //默认加载昨天的
    $('#t1').css('background-color','#ccc');
    get_rank();
    
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
        get_rank();
    });
    
    //选择项目
    $('#db-select').change(function(){  
        get_rank();
    });
    //选择追灿或全部
    //全部为false, 追灿为ture
    $('#zhuican-all').on('switch-change', function () {
        get_rank();
});
   
  });
  
  $(document).on("click","#curSalesNum",function(){
    if(rankCurFlag)
        jsonRank.sort(sortSalesNum);
    else
        jsonRank.sort(resortSalesNum);
    
    rankCurFlag=!rankCurFlag;
    output(jsonRank);
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
	    url:'rank_list/sales_rank_data',
            type:'POST',
            data:'time='+ timeSpan +'&db='+$('#db-select').val()+'&is_zc='+$('#zhuican-all').bootstrapSwitch('state'),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    jsonRank=json;
                    jsonRank.sort(sortSalesNum);
                    output(jsonRank);
            }
            else
                    $('#rank').html("<h2>&nbsp;&nbsp;&nbsp;&nbsp;没有找到符合条件的数据</h2>");
            }
	  });//ajax函数结束
          }
          
function output(jsonRank)
{
    var html='<table class="project-table">\n\
                <tr>\n\
                <th><a href="javascript:void(0);" id="curSalesNum" title="点击排序">销量</a></th>\n\
                <th>旺旺昵称</th>\n\
                </tr>';
    for(var i = 0; i < (jsonRank.length > 20 ? 20:jsonRank.length); i++)
    {
        html += '<tr><td>' + jsonRank[i]['salesNum'] + '</td>\n\
                <td>' + jsonRank[i]['sellernick'] + '<a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=' + jsonRank[i]['sellernick'] + '&siteid=cntaobao&status=2&charset=utf-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=' + jsonRank[i]['sellernick'] + '&site=cntaobao&s=2&charset=utf-8" alt="点这里给我发消息" /></a></td>\n\
                </tr>' ;
    }
    html += '</table>';
    $('#rank').html(html);
}
  
function sortSalesNum(a, b)
{
    return b.salesNum - a.salesNum;
}
function resortSalesNum(a, b)
{
    return a.salesNum - b.salesNum;
}

