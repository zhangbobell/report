$(document).ready(function(){
    //日历设置
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
    }).datepicker("setDate","-1d");
   //开关按钮
    $(".switch").bootstrapSwitch();
    
    //显示结果的触发事件
    display_result();//页面加载
    $(".datepicker").on("change",display_result);//选择日历
    $("#project,#operator").on("change",display_result);//选择项目、运营人员
    $("#zhuican-all").on("switch-change",display_result);//选择追灿/全部        
});

function display_result(){
    project=$("#project").val();
    operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    zhuicanAll=$("#zhuican-all").bootstrapSwitch("state")?"zhuican":"all";
    $("#up_seller_rate,#arbitrary_price_rate,#dynamic_sales_rate,#order_failed_rate,#up_item_num,#seller_num_lost").html("<img id=\"loading-gif\" src=\"public/images/loading.gif\" />");
    $.ajax({
        url:"channel_auth/data_channel_quality",
        type:"post",
        async:false,
        dateType:"json",
        data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate,"zhuicanAll":zhuicanAll}
    }).done(function(data){
           data=$.parseJSON(data);
           if(data ==null){
            $("#loading-gif").hide();
        }
           for(key in data){
               data[key]=Number(data[key]);
           }
          
           $("#up_seller_rate").html((data.up_seller_rate * 100 ).toFixed(2)+"%");
           $("#arbitrary_price_rate").html((data.arbitrary_price_rate * 100).toFixed(2)+"%");
           $("#dynamic_sales_rate").html((data.dynamic_sales_rate * 100).toFixed(2)+"%");
           $("#order_failed_rate").html((data.order_failed_rate * 100).toFixed(2)+"%");
           $("#up_item_num").html(Math.round(data.up_item_num));
           $("#seller_num_lost").html(data.seller_num_lost);
    });
}
