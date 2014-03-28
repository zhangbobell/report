$(document).ready(function(){
    //日历设置
    $.datepicker.setDefaults(
        $.datepicker.regional["zh-CN"]
    );
    $("#start-date").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    }).datepicker("setDate","-30d");
    
    $("#end-date").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    }).datepicker("setDate","-1d");
   //开关按钮
    $(".switch").bootstrapSwitch();
    
    //显示结果的触发事件
    display_result();//页面加载
    $(".datepicker").on("change",display_result);//选择日历
    $("#project,#operator").on("change",display_result);//选择项目、运营人员
    $("#zhuican-all").on("switch-change",display_result);//选择追灿/全部    
});

data=null;
function display_result(){
    project=$("#project").val();
    //operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    zhuicanAll=$("#zhuican-all").bootstrapSwitch("state")?"zhuican":"all";
     $("#order_sales,#seller_num,#seller-quality").html("<img id=\"loading-gif\" src=\"public/images/loading.gif\" />");
    $.ajax({
        url:"channel_auth/data_channel_auth_trend_analysis",
        type:"post",
        async:false,
        dateType:"json",
        data:{"project":project,/*"operator":operator,*/"startDate":startDate,"endDate":endDate,"zhuicanAll":zhuicanAll}
    }).done(function(d){
        data=$.parseJSON(d);
        if(data ==null){
            $("#loading-gif").hide();
        }
    });
    Highcharts.setOptions({
        title:{
            text:null
        },
        xAxis:{
            labels:{
                rotation:-30
            }
        },
        credits: { 
                        enabled: false   //不显示LOGO 
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
    if(data != null){
    $("#order_sales").highcharts({
        xAxis:{
            categories:data['order_fee'].createtime
        },
        credits: { 
                        enabled: false   //不显示LOGO 
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
                data:data['order_fee'].order_fee
            },{
                name:"销量",
                data:data['order_fee'].order_num,
                yAxis:1
            }
        ]
        
    });
    
        $("#seller_num").highcharts({
        xAxis:{
            categories:data['seller_num'].startdate
        },
        credits: { 
                        enabled: false   //不显示LOGO 
                    },
        series:[
            {
                name:"分销商数量",
                data:data['seller_num'].seller_num
            }
        ]
        
    });
    //===================================  第三部分:分销商质量 =========================================================
    $("#seller-quality").highcharts({
        xAxis:{
            categories:data['up_rate'].createtime
        },
        
        tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.2f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
        },
        yAxis:{
		lineWidth:1,
                min:0,
                max:100
        },
        credits: { 
                        enabled: false   //不显示LOGO 
                    },
        series:[
            {
                name:"上架率(%)",
                data:data['up_rate'].up_rate
            }
        ]
        
    });
    }
}
