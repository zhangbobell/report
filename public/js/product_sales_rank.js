/* 
 * File : sales_rate_rank.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-16
 */
var jsonRank;
var rankCurFlag=true;
var rankPriceFlag=true;

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
  
  $(document).on("click","#curSalesNum",function(){
    if(rankCurFlag)
        jsonRank.sort(sortSalesNum);
    else
        jsonRank.sort(resortSalesNum);
    
    rankCurFlag=!rankCurFlag;
    output(jsonRank);
});

$(document).on("click","#price",function(){
    if(rankPriceFlag)
        jsonRank.sort(sortPrice);
    else
        jsonRank.sort(resortPrice);
    
    rankPriceFlag=!rankPriceFlag;
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
	    url:'rank_list/product_sales_rank_data',
            type:'POST',
            data:'time='+ timeSpan +'&db='+$('#db-select').val(),
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
                <th>货号</th>\n\
                <th><a href="javascript:void(0);" id="price" title="点击排序">价格</a></th>\n\
                </tr>';
    for(var i = 0; i < (jsonRank.length > 20 ? 20:jsonRank.length); i++)
    {
        html += '<tr><td>' + jsonRank[i]['salesNum'] + '</td>\n\
                <td>' + jsonRank[i]['itemnum']+'</td>\n\
                <td>' + jsonRank[i]['price']+'</td>\n\
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
function sortPrice(a, b)
{
    return b.price - a.price;
}

function resortPrice(a, b)
{
    return a.price - b.price;
}

  


