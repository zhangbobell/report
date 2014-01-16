/* 
 * File : zero_up.js
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-16
 */
$(document).ready(function(){
    
    //默认加载昨天的
    var yesterday = GetDateStr(-1);
    $('#date').val(yesterday);
    get_rank();
    
    
    //选择的时间跨度
    $('#date').change(function(){     
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
  
  function get_rank(){
      
            //显示正在加载 
            $('#rank').html('<img src="public/images/loading.gif" alt="loading..." />');
            
            $.ajax({
	    url:'operation/zero_up_data',
            type:'POST',
            data:'time='+ $('#date').val() +'&db='+$('#db-select').val()+'&is_zc='+$('#zhuican-all').bootstrapSwitch('state'),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    var html='<table class="project-table">\n\
                                <tr>\n\
                                <th>旺旺昵称</th>\n\
                                <th>合作开始时间</th>\n\
                                </tr>';
                    for(var i = 0; i < eval(json).length; i++)
                    {
                        html += '<tr><td>' + json[i]['sellernick'] + '</td>\n\
                                <td>' + json[i]['startdate'] + '</td>\n\
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

function Appendzero(obj)
{
 if(obj<10) return "0" +""+ obj;
 else return obj;    
}

function GetDateStr(AddDayCount) {
    var dd = new Date();
    dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
    var y = dd.getFullYear();
    var m = Appendzero(dd.getMonth()+1);//获取当前月份的日期
    var d = Appendzero(dd.getDate());
    return y+"-"+m+"-"+d;
}

  

