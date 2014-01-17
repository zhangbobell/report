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
    $("#query_channel_quality").on("click",query_channel_quality);
    
    query_channel_quality();
    
});

function query_channel_quality(){
    project=$("#project").val();
    operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    zhuicanAll=$("#zhuican-all").bootstrapSwitch("state")?"zhuican":"all";
    
    $.ajax({
        url:"channel_auth/data_channel_quality",
        type:"post",
        async:false,
        dateType:"json",
        data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate,"zhuicanAll":zhuicanAll}
    }).done(function(data){
           data=$.parseJSON(data);
           for(key in data){
               data[key]=Number(data[key]);
           }
          
           $("#up_seller_rate").html((data.up_seller_rate * 100 ).toFixed(2)+"%");
           $("#arbitrary_price_rate").html((data.arbitrary_price_rate * 100).toFixed(2)+"%");
           $("#dynamic_sales_rate").html((data.dynamic_sales_rate * 100).toFixed(2)+"%");
           $("#order_failed_rate").html((data.order_failed_rate * 100).toFixed(2)+"%");
           $("#up_item_num").html(data.up_item_num);
    });
}
