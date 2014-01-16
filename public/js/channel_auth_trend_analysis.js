$(document).ready(function(){
    /*
    $.datepicker.setDefaults(
        $.datepicker.regional["zh-CN"]
    );
    $(".datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    });
    
    $(".switch").bootstrapSwitch();
    */
    $("#query_channel_auth_trend_analysis").on("click",query_channel_auth_trend_analysis);
    
    
    
});
data=null;
function query_channel_auth_trend_analysis(){
    project=$("#project").val();
    operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    zhuicanAll=$("#zhuican-all").bootstrapSwitch("state")?"zhuican":"all";
    
    $.ajax({
        url:"channel_auth/data_channel_auth_trend_analysis",
        type:"post",
        async:false,
        dateType:"json",
        data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate,"zhuicanAll":zhuicanAll}
    }).done(function(d){
        data=$.parseJSON(d);
    });
    Highcharts.setOptions({
        title:{
            text:null
        },
        yAxis:{
		lineWidth:1,
                min:0,
                title:{
                    align:"high",
                    rotation:0,
                    y:-20
                }
	}
    });
    
    $("#order_sales").highcharts({
        xAxis:{
            categories:data.createtime,
            labels:{
                rotation:-90
            }
        },
        yAxis:[
            {
                title:{
                    text:"销售额"
                }
            },{
                title:{
                    text:"销量"
                },
                opposite:true
            }
        ],
        series:[
            {
                name:"销售额",
                data:data.order_fee
            },{
                name:"销量",
                data:data.order_num,
                yAxis:1
            }
        ]
        
    });
   
}
